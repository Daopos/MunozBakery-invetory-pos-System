@extends('layouts.master')
@section('content')


<div class="container-text">
    <h4>Inventory / Audits</h4>
</div>

<div class="one-stock">
    <div class="stock-add">
        <h1>All Audits</h1>
    </div>

    <div class="container-stock">
        <table class="stock-table">
            <tr>
              <th>Date</th>
              <th>Action take by</th>
              <th>Action</th>
              <th>Remove</th>
            </tr>
            @foreach ($audit as $datum)
            <tr>
                <td>{{ $datum->created_at }}</td>
                <td>{{ $datum->name }}</td>
                <td>{{ $datum->event }}</td>
                <td>
                    <div class="action">
                        <button  data-bs-toggle="modal" data-bs-target="#deleteModal{{ $datum->id }}" data-product-id="{{ $datum->id }}">Delete</button>
                    </div>
                </td>
              </tr>

              <div class="modal fade" id="deleteModal{{ $datum->id }}" tabindex="-1" aria-labelledby="exampleModalLabel{{ $datum->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">Delete Product</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="deleteform{{ $datum->id }}" method="POST" action="{{ route('auditdelete', ['id' => $datum->id]) }}" enctype="multipart/form-data">
                            @csrf
                            @method('DELETE') <!-- Add this line to indicate that it's a DELETE request -->
                            <h4>Are you sure want to Delete this <span class="text-info">Audit</span>?</h4>
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
        {{ $audit->links() }}
    </div>
</div>

@if (session('success'))
  <script>
    // Display Toastr notification for success
    toastr.success('{{ session('success') }}', 'Success!', {"iconClass": 'customer-info'});
</script>
@endif

@if (session('error'))
  <script>
    // Display Toastr notification for success
    toastr.success('{{ session('success') }}', 'Success!');
</script>
@endif

@endsection
