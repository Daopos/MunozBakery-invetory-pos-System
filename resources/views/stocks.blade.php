@extends('layouts.master')
@section('content')


<div class="container-text">
    <h4>Inventory / Stocks</h4>
</div>

<div class="container-activity">
    <div class="activity">
        <h1>STOCKS ACTIVITY</h1>
            <div class="activities">
                <div>
                    <h1>{{ $stocksdata['allstock'] }}</h1>
                    <h5>all stock</h5>
                </div>
                <div class="line"></div>
                <div>
                    <h1>{{ $stocksdata['stockqty'] ? $stocksdata['stockqty']  : 0  }}</h1>
                    <h5>stock qty</h5>
                </div>
                <div class="line"></div>
                <div>
                    <h1>{{ $stocksdata['totalcost'] ? $stocksdata['totalcost']  : 0 }}</h1>
                    <h5>total cost</h5>
                </div>
            </div>
    </div>
    <div class="summary">
        <h1>INVENTORY SUMMARY</h1>
            <div class="summaries">
                <div >
                    <h1>{{ $stocksdata['usedstock'] }}</h1>
                    <h5>stock used</h5>
                </div>
                <div class="line2"></div>
                <div>
                    <h1>{{ $stocksdata['remainingstock'] }}</h1>
                    <h5>remaining stock</h5>
                </div>
            </div>
    </div>
</div>

<div class="update-stock">
    <button id="updateAllStock" data-bs-toggle="modal" data-bs-target="#allStock">+ UPDATE STOCK</button>
</div>

<div class="one-stock">
    <div class="stock-add">
        <h1>All Stocks</h1>
        <Button id="addStock" data-bs-toggle="modal" data-bs-target="#stockModal">+ New Item</Button>
    </div>

    <div class="container-stock">
        <table class="stock-table">
            <tr>
              <th></th>
              <th>Item Name</th>
              <th>Stock type</th>
              <th>Stock Level</th>
              <th>Price</th>
              <th>Stock on hand</th>
              <th>Stock Used</th>
              <th>Action</th>
            </tr>
            @foreach ($stocks as $stock)
            <?php
            // Find the corresponding product in $productsSold
            $usedstocks = $usedperstock->where('stock_id', $stock->id)->first();

            // Retrieve the total quantity sold for the current product
            $total_used = $usedstocks ? $usedstocks->totalused : 0;
        ?>


            <tr>
                <td><img src="{{ asset('Image/' . $stock->image) }}" alt=""></td>
                <td>{{ $stock->item_name }}</td>
                <td>{{ $stock->stock_type }}</td>
                <td>{{ $stock->qty >= 20 ? 'High' : ($stock->qty <= 9 ? 'Low' : 'Medium') }}</td>
                <td>₱{{ $stock->price }}</td>
                <td>{{ $stock->qty }}</td>
                <td>{{ $total_used }}</td>
                <td>
                  <div class="action">
                    <button class="editstock" data-bs-toggle="modal" data-bs-target="#editstockModal{{ $stock->id }}" data-stock-id="{{ $stock->id }}">Update</button>
                    <button  data-bs-toggle="modal" data-bs-target="#deleteModal{{ $stock->id }}" data-stock-id="{{ $stock->id }}">Delete</button>
                  </div>
              </td>
              </tr>

     <div class="modal fade" id="editstockModal{{ $stock->id }}" tabindex="-1" aria-labelledby="editstockModalLabel{{ $stock->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="editstockModalLabel">Edit Stock</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="{{ route('editstock', ['id' => $stock->id]) }}" enctype="multipart/form-data">
                                @csrf
                                @method('PUT') <!-- Use PUT method for editing -->

                                <div class="form-group ">
                                    <label for="item_image">Image</label>
                                    <input type="file" class="form-control " name="item_image" id="edit_item_image{{ $stock->id }}" value="{{ old('item_image') }}">
                                </div>
                                <div class="form-group">
                                    <label for="item_name">Item Name</label>
                                    <input type="text" class="form-control" name="item_name" id="edit_item_name{{ $stock->id }}" value="{{ old('item_name') }}">
                                </div>
                                <div class="form-group">
                                    <label for="stock_type">stock_type</label>
                                    <select name="stock_type" class="form-select" id="edit_stock_type{{ $stock->id }}" value="{{ old('stock_type') }}"  aria-label="Default select example">
                                        @if(old('stock_type') == 'ingredients') {
                                            <option value="ingredients">INGREDIENTS</option>
                                            <option value="plastics">PLASTICS</option>
                                        } @else {
                                            <option value="plastics">PLASTICS</option>
                                            <option value="ingredients">INGREDIENTS</option>
                                        }
                                        @endif
                                      </select>
                                </div>
                                <div class="form-group">
                                    <label for="qty">Quantity</label>
                                    <input type="text" class="form-control" name="qty" id="edit_qty{{ $stock->id }}" value="{{ old('qty') }}">
                                </div>
                                <div class="form-group">
                                    <label for="price">Price</label>
                                    <input type="text" class="form-control" name="price" id="edit_price{{ $stock->id }}" value="{{ old('price') }}">
                                </div>
                                {{-- <div class="form-group">
                                    <label for="price">Used</label>
                                    <input type="text" class="form-control" name="used_stock" id="edit_used{{ $stock->id }}" value="{{ old('use-stock') }}">
                                </div> --}}
                                <button type="submit" class="btn btn-primary mt-3">Update</button>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="deleteModal{{ $stock->id }}" tabindex="-1" aria-labelledby="exampleModalLabel{{ $stock->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">Delete Product</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                       <form id="deleteform{{ $stock->id }}" method="POST" action="{{ route('deletestock', ['id' => $stock->id]) }}" enctype="multipart/form-data">
                            @csrf
                            @method('DELETE') <!-- Add this line to indicate that it's a DELETE request -->
                            <h5>Are you sure want to Delete <span class="text-info">{{ $stock->item_name }}</span>?</h5>
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
        {{ $stocks->links() }}
        </div>
</div>


<div class="modal fade" id="stockModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Add Stock</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form method="POST" action="{{ route('addstock') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group ">
                    <label for="item_image">Image</label>
                    <input type="file" class="form-control " name="item_image" id="item_image" required>
                </div>
                <div class="form-group">
                    <label for="item_name">Item Name</label>
                    <input type="text" class="form-control" name="item_name" id="item_name" placeholder="stock name" required>
                </div>
                <div class="form-group">
                    <label for="stock_type">stock_type</label>
                    <select name="stock_type" class="form-select" id="stock_type"  aria-label="Default select example" required>
                        <option value="none">None</option>
                        <option value="ingredients">INGREDIENTS</option>
                        <option value="plastics">PLASTICS</option>
                      </select>
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

  <div class="modal fade" id="allStock" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Update Stocks</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form method="POST" action="{{ route('updateStock') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                @foreach ($stocks as $stock)
                <div class="form-group">
                    <label for="">{{ $stock->item_name }}</label>
                    <input type="hidden" name="stockId[]" value="{{ $stock->id }}">
                    <input type="number" min="0" class="form-control" name="allqty[]" id="qty" placeholder="qty" value="{{ $stock->qty }}" required>
                </div>

                @endforeach

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
        const stockId = editButton.getAttribute('data-stock-id');

        // Make an AJAX request to the server to get the stock item data
        $.ajax({
            url: `/getstock/${stockId}`,
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                // Populate the hidden input with the stock ID
                $('#edit_stock_id').val(data.id);
                // Populate the form fields with the retrieved data, using the correct IDs
                $('#edit_item_image' + stockId).val(data.item_image);
                $('#edit_item_name' + stockId).val(data.item_name);
                $('#edit_stock_type' + stockId).val(data.stock_type);
                $('#edit_qty' + stockId).val(data.qty);
                $('#edit_price' + stockId).val(data.price);
                $('#edit_used' + stockId).val(data.used_stock)
            },
            error: function (error) {
                console.error('Error fetching stock item data:', error);
            }
        });
    });
});


</script>


@endsection
