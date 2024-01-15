<div class="sidebar">
    <nav >
        <td><img class="bakerlogo" src="{{asset('/assets/photos/bakery-shop.png')}}" alt=""></td>
        <a href="{{route('bakerproducts')}}" class="{{ (strpos(Route::currentRouteName(), 'bakerproducts') === 0) ? 'isactive' : 'inactive' }}">
            <img src="{{asset('/assets/photos/product.png')}}" alt="">
            <h5>Products</h5>
        </a>
        <a href="{{route('bakerstock')}}" class="{{ (strpos(Route::currentRouteName(), 'bakerstock') === 0) ? 'isactive' : 'inactive' }}">
            <img src="{{asset('/assets/photos/stock.png')}}" alt="">
            <h5>Stocks</h5>
        </a >
        <a href="{{route('bakerlog')}}" class="{{ (strpos(Route::currentRouteName(), 'bakerlog') === 0) ? 'isactive' : 'inactive' }}">
            <img src="{{asset('/assets/photos/employee.png')}}" alt="">
            <h5>Profile</h5>
        </a>
    </nav>
</div>
