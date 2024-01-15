<div class="sidebar">
    <nav >
        <td><img class="bakerlogo" src="{{asset('/assets/photos/bakery-shop.png')}}" alt=""></td>
        <a href="{{route('baggerproducts')}}" class="{{ (strpos(Route::currentRouteName(), 'baggerproducts') === 0) ? 'isactive' : 'inactive' }}">
            <img src="{{asset('/assets/photos/product.png')}}" alt="">
            <h5>Products</h5>
        </a>
        <a href="{{route('baggerstock')}}" class="{{ (strpos(Route::currentRouteName(), 'baggerstock') === 0) ? 'isactive' : 'inactive' }}">
            <img src="{{asset('/assets/photos/stock.png')}}" alt="">
            <h5>Stocks</h5>
        </a >
        <a href="{{route('baggerlog')}}" class="{{ (strpos(Route::currentRouteName(), 'baggerlog') === 0) ? 'isactive' : 'inactive' }}">
            <img src="{{asset('/assets/photos/employee.png')}}" alt="">
            <h5>Profile</h5>
        </a>
    </nav>
</div>
