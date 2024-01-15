<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Munoz Bakery</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{asset('/assets/photos/bakery-shop.png')}}" />
    <link rel="stylesheet" href="{{  asset('assets/css/admin.css') }}">
    <link rel="stylesheet" href="{{  asset('assets/css/stocks.css') }}">
    <link rel="stylesheet" href="{{  asset('assets/css/modal.css') }}">
    <link rel="stylesheet" href="{{  asset('assets/css/adminstyle.css') }}">
    {{-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.min.js"></script> --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

    <script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/css/toastr.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/js/toastr.js"></script>
</head>
<body>
    <div class="sidebar">
        <nav>
            <img style="margin-bottom: 20px" src="{{asset('/assets/photos/bakery-shop.png')}}" alt="">


            <div class=" dr-header">
                dashboard
            </div>
            <div>
                <a href="/dashboard" class="{{ (strpos(Route::currentRouteName(), 'dashboard') === 0) ? 'isactive' : 'inactive' }}">
                    <img src="{{asset('/assets/photos/dashboard.png')}}" alt="">
                    <h5>Dashboard</h5>
                </a>
            </div>

                <div class="dropdown-btn dr-header">
                    inventory
                    <img src="{{asset('/assets/photos/ferret.png')}}" alt=""  >
                </div>

            <div class="dropdown-container">
                <a href="/stocks" class="{{ (strpos(Route::currentRouteName(), 'stocks') === 0) ? 'isactive' : 'inactive' }}">
                    <img src="{{asset('/assets/photos/stock.png')}}" alt="">
                    <h5>Raw Materials</h5>
                </a >
                <a href="/products" class="{{ (strpos(Route::currentRouteName(), 'products') === 0) ? 'isactive' : 'inactive' }}">
                    <img src="{{asset('/assets/photos/product.png')}}" alt="">
                    <h5>Breads</h5>
                </a>
            </div>

            <div class=" dr-header">
                employee
            </div>
            <a href="/employees" class="{{ (strpos(Route::currentRouteName(), 'employees') === 0) ? 'isactive' : 'inactive' }}">
                <img src="{{asset('/assets/photos/employee.png')}}" alt="">
                <h5>Employees</h5>
            </a>

            <div class="dropdown-btn dr-header">
                assessment
                <img src="{{asset('/assets/photos/ferret.png')}}" alt=""  >
            </div>
            <div class="dropdown-container">
                <a href="/sales" class="{{ (strpos(Route::currentRouteName(), 'sales') === 0) ? 'isactive' : 'inactive' }}">
                    <img src="{{asset('/assets/photos/sales.png')}}" alt="">
                    <h5>Sales</h5>
                </a>
                <a  href="/reports" class="{{ (strpos(Route::currentRouteName(), 'reports') === 0) ? 'isactive' : 'inactive' }}">
                    <img src="{{asset('/assets/photos/reports.png')}}" alt="">
                    <h5>Reports</h5>
                </a>
                <a  href="/audits" class="{{ (strpos(Route::currentRouteName(), 'audits') === 0) ? 'isactive' : 'inactive' }}">
                    <img src="{{asset('/assets/photos/audit.png')}}" alt="">
                    <h5>Audit</h5>
                </a>
            </div>



            <div class=" dr-header">
                admin
            </div>
            <a  href="/admin" class="{{ (strpos(Route::currentRouteName(), 'admin') === 0) ? 'isactive' : 'inactive' }}">
                <img src="{{asset('/assets/photos/admin.png')}}" alt="">
                <h5>Admin</h5>
            </a>

        </nav>
    </div>
    <div class="container-bakery">
        <div class="container-pad">

            @yield('content')

        </div>
    </div>

    <script>
        $(document).ready(function() {
            $("#addStock").click(function() {
                $("#addItemModal").modal('show');
            });
        });

        $(document).ready(function() {
            $("#addProduct").click(function() {
                $("#addProductModal").modal('show');
            });
        });


        </script>

<script>
    var dropdown = document.getElementsByClassName("dropdown-btn");
    var i;

    for (i = 0; i < dropdown.length; i++) {
        dropdown[i].addEventListener("click", function () {
            this.classList.toggle("active");
            var dropdownContent = this.nextElementSibling;
            dropdownContent.classList.toggle("active");
        });

        var dropdownLinks = dropdown[i].nextElementSibling.getElementsByTagName("a");
        for (var j = 0; j < dropdownLinks.length; j++) {
            if (dropdownLinks[j].classList.contains("isactive")) {
                dropdown[i].classList.add("active");
                dropdown[i].nextElementSibling.classList.add("active");
                break;
            }
        }
    }
</script>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>
</html>
