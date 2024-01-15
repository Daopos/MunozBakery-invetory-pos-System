<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Munoz Bakery</title>
    <link rel="stylesheet" href="{{  asset('assets/css/bagger.css') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{asset('/assets/photos/bakery-shop.png')}}" />

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
<!-- Add these links to your HTML file -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

</head>
<body>
    @include('employee.bakernav')

<div class="container-bakery">
    <div class="container-pad">
        <div class="admin-container">
            <div class="admin-box">
                <div>
                    <img src="{{ asset('Image/' . $employee->image) }}" alt="" width="140px">
                </div>
                <div class="admin-inputs">
                    <label for="username">username</label>
                    <input type="text" value="{{ $employee->username }}" disabled>
                </div>
                <div class="admin-inputs">
                    <label for="password">password</label>
                    <input type="text"  value="*******" disabled>
                </div>
                <button id="editAdminModal" data-bs-toggle="modal" data-bs-target="#adminModal">EDIT PROFILE</button>
                <form style="width: 100%" method="GET" action="{{ route('employeelogout') }}">
                    <button style="background-color: #900C3F;">Log Out</button>
                </form>
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
            <form method="POST" action="{{ route('profileedit') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" class="form-control" name="username" value="{{ $employee->username }}" autocomplete="off" placeholder="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" name="password" value="{{ $employee->password }}"  autocomplete="off" placeholder="password" required>
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
      toastr.success('{{ session('success') }}', 'Success!');
  </script>
  @endif

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>
</html>
