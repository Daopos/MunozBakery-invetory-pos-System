@extends('layouts.master')
@section('content')

<div class="container-text">
    <h4>Inventory / Reports</h4>
</div>


<div style="display: flex; gap: 20px; align-items: center">
    <form id="reportForm" method="get" action="{{ route('reports') }}">
        @csrf

        <div class="container-text" style="margin-top: 20px">
            <select name="days" id="days" onchange="this.form.submit()">
                <option value="1" {{ $selectedDays == 1 ? 'selected' : '' }}>Today</option>
                <option value="3" {{ $selectedDays == 3 ? 'selected' : '' }}>Last 3 days ago</option>
                <option value="7" {{ $selectedDays == 7 ? 'selected' : '' }}>Last 7 days ago</option>
                <option value="10" {{ $selectedDays == 10 ? 'selected' : '' }}>Last 10 days ago</option>
                <option value="14" {{ $selectedDays == 14 ? 'selected' : '' }}>Last 14 days ago</option>
                <option value="17" {{ $selectedDays == 17 ? 'selected' : '' }}>Last 17 days ago</option>
                <option value="21" {{ $selectedDays == 21 ? 'selected' : '' }}>Last 21 days ago</option>
                <option value="25" {{ $selectedDays == 25 ? 'selected' : '' }}>Last 25 days ago</option>
                <option value="28" {{ $selectedDays == 28 ? 'selected' : '' }}>Last 28 days ago</option>
            </select>
        </div>
    </form>

    <form action="{{ route('reports') }}" style="margin-top: 20px">
        <input type="hidden" name="days" value="{{ $selectedDays }}">
        <input name="print" type="submit" style="background-color: #900C3F; padding: 7px 30px;" value="PrintPdf">
    </form>
</div>





<div class="container-activity">
    <div class="activity">
        <h1>SALES ACTIVITY</h1>
            <div class="activities">
                <div>
                    <h1>{{ $stockCost }}</h1>
                    <h5>stock cost</h5>
                </div>
                <div class="line"></div>
                <div>
                    <h1>{{ $stockUsed }}</h1>
                    <h5>stocks used</h5>
                </div>
                <div class="line"></div>
                <div>
                    <h1>{{ $productSold }}</h1>
                    <h5>product sold</h5>
                </div>
            </div>
    </div>
    <div class="summary">
        <h1>SALES SUMMARY</h1>
            <div class="summaries">
                <div >
                    <h1>{{ $totalSales }}</h1>
                    <h5>total sales</h5>
                </div>
                <div class="line2"></div>
                <div>
                    <h1>{{ $netRevenue ? $netRevenue : 0 }}</h1>
                    <h5>net revenue</h5>
                </div>
            </div>
    </div>
</div>

<div class="container-details">

    <div class="top-products">
        <div>
            <h2>Used Stock</h2>

            <table class="top-table">
                <tr>
                  <th style="padding: 8px 30px"></th>
                  <th style="padding: 8px 30px">Stock</th>
                  <th style="padding: 8px 30px">Price</th>
                  <th style="padding: 8px 30px">Used</th>
                  <th style="padding: 8px 30px">Total Amount</th>
                </tr>
                @foreach ($usedStock as $stock)
                <tr>
                    <td style="padding: 8px 30px"><img src="{{ asset('Image/'. $stock->image .'') }}" alt=""></td>
                    <td style="padding: 8px 30px">{{ $stock->item_name }}</td>
                    <td style="padding: 8px 30px">{{ $stock->price }}</td>
                    <td style="padding: 8px 30px">{{ $stock->total_qty }}</td>
                    <td style="padding: 8px 30px">{{ $stock->price *  $stock->total_qty }}</td>
                  </tr>
                @endforeach


              </table>
        </div>
    </div>


    <div class="top-products">
        <div>
            <h2>Sold Product</h2>

            <table class="top-table">
                <tr>
                  <th style="padding: 8px 30px"></th>
                  <th style="padding: 8px 30px">Product</th>
                  <th style="padding: 8px 30px">Price</th>
                  <th style="padding: 8px 30px">Sold</th>
                  <th style="padding: 8px 30px">Total Amount</th>
                </tr>
                @foreach ($soldProduct as $product)
                <tr>
                    <td style="padding: 8px 30px"><img src="{{ asset('Image/'. $product->image .'') }}" alt=""></td>
                    <td style="padding: 8px 30px">{{ $product->product_name }}</td>
                    <td style="padding: 8px 30px">{{ $product->price }}</td>
                    <td style="padding: 8px 30px">{{ $product->total_qty }}</td>
                    <td style="padding: 8px 30px">{{ $product->price *  $product->total_qty }}</td>
                  </tr>
                @endforeach

              </table>
        </div>
    </div>
</div>

<script>
    $('#days').on('change', function() {
    $(this).closest('form').submit();
});
</script>

@endsection
