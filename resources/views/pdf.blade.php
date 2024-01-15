<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<style>

    * {
        font-family: 'Raleway', sans-serif;
    }
    h2 {
        text-align: right;
        color: #0A2647
    }

    table {
  font-family: Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

table td, table th {
  border: 1px solid #ddd;
  padding: 8px;
}

table tr:nth-child(even){background-color: #f2f2f2;}

table tr:hover {background-color: #ddd;}

table th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: left;
  background-color: #0A2647;
  color: white;
}
</style>
<body>
    <h2>Muñoz Bakery</h2>
    <hr>
    <h1>Invoice</h1>
    <p>{{ $selectedDays == 1 ? 'Today' : 'Last '. $selectedDays .' days ago' }}</p>
    <hr>
    <h3>Product</h3>
    <table>
        <tr>
            <th>product</th>
            <th>price</th>
            <th>sold</th>
            <th>total Amount</th>
        </tr>
        @foreach ($soldProduct as $product)
                <tr>
                    <td >{{ $product->product_name }}</td>
                    <td >P{{ $product->price }}</td>
                    <td >{{ $product->total_qty }}</td>
                    <td >P{{ $product->price *  $product->total_qty }}</td>
                  </tr>
                @endforeach
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>Total: P{{$totalSales }}</td>
                </tr>
    </table>

    <h3>Stocks</h3>
    <table>
        <tr>
            <th>stock</th>
            <th>price</th>
            <th>used</th>
            <th>total Amount</th>
        </tr>
        @foreach ($usedStock as $stock)
                <tr>
                    <td >{{ $stock->item_name }}</td>
                    <td >P{{ $stock->price }}</td>
                    <td >{{ $stock->total_qty }}</td>
                    <td >P{{ $stock->price *  $product->total_qty }}</td>
                  </tr>
                @endforeach
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>Total: P{{$stockCost }}</td>
                </tr>
    </table>

    <hr style="margin-top: 50px">
    <h5>Total Product Sold: {{ $productSold }}</h5>
    <h5>Total Stock Used: {{ $stockUsed }}</h5>
    <h5>Total Stock Cost: P{{ $stockCost }}</h5>
    <h5>Total Sales: P{{ $totalSales}}</h5>
    <h3>Net Revenue: P{{ $totalSales - $stockCost }}</h3>
</body>
</html>
