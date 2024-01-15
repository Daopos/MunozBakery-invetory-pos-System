@extends('layouts.master')
@section('content')

<div class="container-text">
    <h4>Inventory / Admin</h4>
</div>

    <div class="admin-container">
            <div class="admin-box">
                <div>
                    <img src="{{asset('/assets/photos/admin.png')}}" alt="" width="140px">
                </div>
                <div class="admin-inputs">
                    <label for="username">username</label>
                    <input type="text" value="{{ $admin->username }}" disabled>
                </div>
                <div class="admin-inputs">
                    <label for="password">password</label>
                    <input type="text"  value="********" disabled>
                </div>
                <button id="editAdminModal" data-bs-toggle="modal" data-bs-target="#adminModal">EDIT PROFILE</button>
                <form action="{{ route('adminlogout') }}" style="width: 100%">
                    <button style="background-color: #900C3F;">Log Out</button>
                </form>
            </div>
    </div>

    <div class="form-clear">
        <button id="editAdminModal" data-bs-toggle="modal" data-bs-target="#clearadmin">Clear Inventory</button>
    </div>



    <div class="modal fade" id="clearadmin" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Admin</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="GET" action="{{ route('clearinventory') }}" enctype="multipart/form-data">
                    @csrf
                    <h5>Are you sure want to clear the <span class="text-info">Inventory</span>?</h5>
                    <h5 class="mt-3">It will delete all the sales, empty all the stocks and products</h5>
                    <h6 class="mt-3 text-danger">Action cannot undo</h6>
                    <button type="submit" class="btn btn-primary mt-3">Submit</button>
                </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>


    <div class="modal fade" id="adminModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Admin</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form method="POST" action="{{ route('adminedit') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" class="form-control" name="username" value="{{ $admin->username }}" autocomplete="off" placeholder="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" name="password" value="{{ $admin->password }}"  autocomplete="off" placeholder="password" required>
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
@endsection
