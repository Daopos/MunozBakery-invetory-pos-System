@extends('layouts.master')
@section('content')


<div class="container-text">
    <h4>Inventory / Sales</h4>
</div>

<div class="container-activity">
    <div class="activity">
        <h1>SALES ACTIVITY</h1>
            <div class="activities">
                <div>
                    <h1>{{  $stockcost }}</h1>
                    <h5>Stock cost</h5>
                </div>
                <div class="line"></div>
                <div>
                    <h1>{{ $estimatedsales }}</h1>
                    <h5>estimated sales</h5>
                </div>
            </div>
    </div>
    <div class="summary">
        <h1>SALES SUMMARY</h1>
            <div class="summaries">
                <div >
                    <h1>{{ $totalQty }}</h1>
                    <h5>total sold</h5>
                </div>
                <div class="line2"></div>
                <div>
                    <h1>{{ $totalSales }}</h1>
                    <h5>total sales</h5>
                </div>
                <div class="line2"></div>
                <div>
                    <h1>{{ $netrevenue <= 0 ? 0 : $netrevenue}}</h1>
                    <h5>net revenue</h5>
                </div>
            </div>
    </div>
</div>

<div class="one-stock">
    <div class="stock-add">
        <h1>All Sales</h1>
    </div>

    <div class="container-stock">
        <table class="stock-table">
            <tr>
              <th></th>
              <th>Employee</th>
              <th>Date_sale</th>
              <th>Product</th>
              <th>qty sold</th>
              <th>total sale</th>
              <th>Action</th>
            </tr>
            @foreach ($data as $datum)
            <tr>
                <td><img src="{{ asset('Image/' . $datum->image) }}" alt=""></td>
                <td>{{ $datum->name }}</td>
                <td>{{ $datum->created_at }}</td>
                <td>{{ $datum->product_name }}</td>
                <td>{{ $datum->total_qty }}</td>
                <td>₱{{ $datum->total_price }}</td>
                <td>
                  <div class="action">
                    {{-- <button class="editstock" data-bs-toggle="modal" data-bs-target="#editstockModal{{ $datum->id }}" data-stock-id="{{ $datum->id }}">Update</button> --}}
                    <button  data-bs-toggle="modal" data-bs-target="#deleteModal{{ $datum->id }}" data-stock-id="{{ $datum->id }}">Delete</button>
                  </div>
              </td>
              </tr>

              <div class="modal fade" id="deleteModal{{ $datum->id }}" tabindex="-1" aria-labelledby="exampleModalLabel{{ $datum->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">Delete Sales</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="deleteform{{ $datum->id }}" method="POST" action="{{ route('deletesale', ['id' => $datum->id]) }}" enctype="multipart/form-data">
                            @csrf
                            @method('DELETE') <!-- Add this line to indicate that it's a DELETE request -->
                            <h4>Are you sure want to Delete this<span class="text-info"> Sales</span>?</h4>
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
        {{ $data->links() }}
    </div>
</div>








  <div class="modal fade" id="employeeModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Add Employee</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form method="POST" action="{{ route('addemployee') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group ">
                    <label for="item_image">Image</label>
                    <input type="file" class="form-control " name="item_image" id="item_image">
                </div>
                <div class="form-group">
                    <label for="item_name">Username</label>
                    <input type="text" class="form-control" name="username" id="item_name">
                </div>
                <div class="form-group">
                    <label for="qty"></label>
                    <input type="text" class="form-control" name="address" id="qty">
                </div>
                <div class="form-group">
                    <label for="price">Price</label>
                    <input type="text" class="form-control" name="employee_type" id="price">
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
    toastr.success('{{ session('success') }}', 'Success!',{"iconClass": 'customer-info'});
</script>
@endif

@if (session('error'))
  <script>
    // Display Toastr notification for success
    toastr.success('{{ session('success') }}', 'Success!');
</script>
@endif


@endsection
