@extends('layouts.master')
@section('content')

<div class="container-text">
    <h4>Inventory / Dashboard</h4>
</div>

<div class="container-activity">
    <div class="activity">
        <h1>SALES ACTIVITY</h1>
            <div class="activities">
                <div>
                    <h1>{{ $dashboard['stocks'] }}</h1>
                    <h5>stocks</h5>
                </div>
                <div class="line"></div>
                <div>
                    <h1>{{ $dashboard['usedstock'] }}</h1>
                    <h5>stocks used</h5>
                </div>
                <div class="line"></div>
                <div>
                    <h1>{{ $dashboard['totalsales'] }}</h1>
                    <h5>total sales</h5>
                </div>
            </div>
    </div>
    <div class="summary">
        <h1>INVENTORY SUMMARY</h1>
            <div class="summaries">
                <div >
                    <h1>{{ $dashboard['made'] }}</h1>
                    <h5>product made</h5>
                </div>
                <div class="line2"></div>
                <div>
                    <h1>{{ $dashboard['sold'] }}</h1>
                    <h5>total quantity sold</h5>
                </div>
            </div>
    </div>
</div>

<div class="container-details">
    <div class="details">
        <h2>Stock Details</h2>
        <div>
            <h4>High Stock items</h4>
            <h4>{{ $stockDetails['high'] }}</h4>
        </div>
        <div>
            <h4>Medium Stock items</h4>
            <h4>{{ $stockDetails['medium'] }}</h4>
        </div>
        <div>
            <h4>Low Stock items</h4>
            <h4>{{ $stockDetails['low'] }}</h4>
        </div>

    </div>
    <div class="top-products">
        <div>
            <h2>Top Selling products</h2>

            <table class="top-table">
                <tr>
                    <th></th>
                  <th></th>
                  <th>Name</th>
                  <th>Price</th>
                  <th>Qty Sold</th>
                  <th>Total Amount</th>
                </tr>
                <?php     $i = 1; ?>
                @foreach ($bestProducts as $products )

                <?php

                // Find the corresponding product in $productsSold
                $productSold = $productsSold->where('product_id', $products->id)->first();

                // Retrieve the total quantity sold for the current product
                $total_sold = $productSold ? $productSold->totalsold : 0;

                $total_price = $products->price * $total_sold;

            ?>
                <tr>
                    <td>{{ $i++ }}</td>
                    <td><img src="{{ asset('Image/' . $products->image) }}" alt=""></td>
                    <td>{{ $products->product_name }}</td>
                    <td>₱{{ $products->price }}</td>
                    <td>{{ $total_sold }}</td>
                    <td>₱{{ $total_price  }}</td>
                  </tr>
                @endforeach


              </table>
        </div>
    </div>
</div>

<div class="container-details">
    <div class="top-products fix-top">
        <div>
            <h2>Recently Sale</h2>

            <table class="top-table">
                <tr>
                  <th></th>
                  <th>Name</th>
                  <th>Price</th>
                  <th>Qty Sold</th>
                  <th>Total Amount</th>
                </tr>
                @foreach ($recentlySale as $sale )


                <tr>
                    <td><img src="{{ asset('Image/' . $sale->image) }}" alt=""></td>
                    <td>{{ $sale->product_name }}</td>
                    <td>₱{{ $sale->price }}</td>
                    <td>{{ $sale->total_qty }}</td>
                    <td>₱{{ $sale->total_price  }}</td>
                  </tr>
                @endforeach


              </table>
        </div>
    </div>
    <div class="top-products">
        <div>
            <h2>Recently Used stocks</h2>
            <table class="top-table fix-top">
                <tr>
                  <th></th>
                  <th>Name</th>
                  <th>Price</th>
                  <th>Qty Used</th>
                  <th>Total Cost</th>
                </tr>
                @foreach ($recentlyUsed as $used )

                <tr>
                    <td><img src="{{ asset('Image/' . $used->image) }}" alt=""></td>
                    <td>{{ $used->item_name }}</td>
                    <td>₱{{ $used->price }}</td>
                    <td>{{ $used->total_qty }}</td>
                    <td>₱{{ $used->total_cost  }}</td>
                  </tr>
                @endforeach


              </table>
        </div>
    </div>
</div>


@endsection
