@extends('layouts.master')
@section('content')


<div class="container-text">
    <h4>Inventory / Products</h4>
</div>

<div class="container-activity">
    <div class="activity">
        <h1>PRODUCTS ACTIVITY</h1>
            <div class="activities">
                {{-- <div>
                    <h1>10</h1>
                    <h5>all product</h5>
                </div> --}}
                {{-- <div class="line"></div> --}}
                <div>
                    <h1>{{ $dashboard['totalqty'] }}</h1>
                    <h5>total qty</h5>
                </div>
                <div class="line"></div>
                <div>
                    <h1>{{ $dashboard['totalprice'] }}</h1>
                    <h5>total price</h5>
                </div>
            </div>
    </div>
    <div class="summary">
        <h1>INVENTORY SUMMARY</h1>
            <div class="summaries">
                <div >
                    <h1>{{ $dashboard['totalproducts'] }}</h1>
                    <h5>total products</h5>
                </div>
                <div class="line2"></div>
                <div>
                    <h1>{{ $dashboard['totalsold'] }}</h1>
                    <h5>total sold</h5>
                </div>
            </div>
    </div>
</div>

<div class="one-stock">
    <div class="stock-add">
        <h1>All Products</h1>
        <Button id="addProduct" data-bs-toggle="modal" data-bs-target="#productModal">+ New Item</Button>
    </div>

    <div class="container-stock">
        <table class="stock-table">
            <tr>
              <th></th>
              <th>Product</th>
              <th>Price</th>
              <th>qty</th>
              <th>total price</th>
              <th>qty sold</th>
              <th>Action</th>
            </tr>
            @foreach ($products as $product)
            <?php
                $total_price = $product->price * $product->qty;

                // Find the corresponding product in $productsSold
                $productSold = $productsSold->where('product_id', $product->id)->first();

                // Retrieve the total quantity sold for the current product
                $total_sold = $productSold ? $productSold->totalsold : 0;
            ?>

            <tr>
                <td><img src="{{ asset('Image/' . $product->image) }}" alt=""></td>
                <td>{{ $product->product_name }}</td>
                <td>₱{{ $product->price }}</td>
                <td>{{ $product->qty }}</td>
                <td>{{ $total_price }}</td>
                <td>{{ $total_sold }}</td>
                <td>
                  <div class="action">
                    <button class="editstock" data-bs-toggle="modal" data-bs-target="#editproductModal{{ $product->id }}" data-product-id="{{ $product->id }}">Update</button>
                    <button  data-bs-toggle="modal" data-bs-target="#deleteModal{{ $product->id }}" data-product-id="{{ $product->id }}">Delete</button>
                  </div>
              </td>
              </tr>

              <div class="modal fade" id="editproductModal{{ $product->id }}" tabindex="-1" aria-labelledby="editstockModalLabel{{ $product->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="editstockModalLabel">Edit Product</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="{{ route('editproduct', ['id' => $product->id]) }}" enctype="multipart/form-data">
                                @csrf
                                @method('PUT') <!-- Use PUT method for editing -->

                                <div class="form-group ">
                                    <label for="product_image">Image</label>
                                    <input type="file" class="form-control " name="product_image" id="edit_product_image{{ $product->id }}" value="{{ old('product_image') }}">
                                </div>
                                <div class="form-group">
                                    <label for="product_name">Item Name</label>
                                    <input type="text" class="form-control" name="product_name" id="edit_product_name{{ $product->id }}" value="{{ old('product_name') }}">
                                </div>
                                <div class="form-group">
                                    <label for="qty">Quantity</label>
                                    <input type="text" class="form-control" name="qty" id="edit_qty{{ $product->id }}" value="{{ old('qty') }}">
                                </div>
                                <div class="form-group">
                                    <label for="price">Price</label>
                                    <input type="text" class="form-control" name="price" id="edit_price{{ $product->id }}" value="{{ old('price') }}">
                                </div>
                                <button type="submit" class="btn btn-primary mt-3">Update</button>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="deleteModal{{ $product->id }}" tabindex="-1" aria-labelledby="exampleModalLabel{{ $product->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">Delete Product</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="deleteform{{ $product->id }}" method="POST" action="{{ route('deleteproduct', ['id' => $product->id]) }}" enctype="multipart/form-data">
                            @csrf
                            @method('DELETE') <!-- Add this line to indicate that it's a DELETE request -->
                            <h4>Are you sure want to Delete <span class="text-info">{{ $product->product_name }}</span>?</h4>
                            <h5 class="mt-3">It will delete all the sales that are related to this product</h5>
                            <h6 class="mt-3 text-danger">You cannot undo this action</h6>
                    </div>
                    <div class="modal-footer">
                      <input id="deleteModalEmployee" type="submit" value="Delete" class="btn btn-danger">
                        </form>
                    </div>
                  </div>
                </div>
              </div>

            @endforeach

        </table>
    </div>
    <div class="d-flex justify-content-center mt-2">
        {{ $products->links() }}
        </div>
</div>



<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Add Product</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form method="POST" action="{{ route('addproduct') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group ">
                    <label for="item_image">Image</label>
                    <input type="file" class="form-control " name="item_image" id="item_image" required>
                </div>
                <div class="form-group">
                    <label for="item_name">Item Name</label>
                    <input type="text" class="form-control" name="product_name" id="item_name" placeholder="product name"  required>
                </div>
                <div class="form-group">
                    <label for="qty">Quantity</label>
                    <input type="text" class="form-control" name="qty" id="qty" placeholder="qty" required>
                </div>
                <div class="form-group">
                    <label for="price">Price</label>
                    <input type="text" class="form-control" name="price" id="price" placeholder="price" required>
                </div>
                <button type="submit" class="btn btn-primary mt-3">Submit</button>
            </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  @if (session('success'))
  <script>
    // Display Toastr notification for success
    toastr.success('{{ session('success') }}', 'Success!', {"iconClass": 'customer-info'});
</script>
@endif
  @if ($errors->any())

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Validation Error!',
            html: '<ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
        });
    </script>
@endif
  <script>
    document.querySelectorAll('.editstock').forEach(function (editButton) {
      editButton.addEventListener('click', function () {
          const productId = editButton.getAttribute('data-product-id');

          // Make an AJAX request to the server to get the stock item data
          $.ajax({
              url: `/getproduct/${productId}`,
              type: 'GET',
              dataType: 'json',
              success: function (data) {
                  // Populate the hidden input with the stock ID
                  $('#edit_stock_id').val(data.id);
                  // Populate the form fields with the retrieved data, using the correct IDs
                  $('#edit_item_image' + productId).val(data.product_image);
                  $('#edit_product_name' + productId).val(data.product_name);
                  $('#edit_qty' + productId).val(data.qty);
                  $('#edit_price' + productId).val(data.price);
              },
              error: function (error) {
                  console.error('Error fetching stock item data:', error);
              }
          });
      });
  });
  </script>

@endsection
