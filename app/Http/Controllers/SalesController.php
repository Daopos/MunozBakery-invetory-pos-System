<?php

namespace App\Http\Controllers;

use App\Models\Audits;
use App\Models\Employees;
use App\Models\Products;
use App\Models\Sales;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class SalesController extends Controller
{
    public function getSales() {

        $data = DB::table('sales')
        ->join('employees','sales.employee_id','=','employees.id')
        ->join('products','sales.product_id','=','products.id')
        ->select('sales.*', 'employees.name','employees.image', 'products.product_name', 'products.price')
        ->orderBy('id', 'ASC')
        ->paginate(8);

        $totalSales = Sales::sum('total_price');

        $totalQty = Sales::sum('total_qty');

        $stockcost = DB::table('stocks')
        ->leftJoin('used_stocks', 'stocks.id', '=', 'used_stocks.stock_id') // Replace 'id' and 'stock_id' with the actual column names
        ->groupBy('stocks.id') // Group by the primary key of the 'stocks' table
        ->select(DB::raw('COALESCE(SUM((stocks.qty + COALESCE(used_stocks.total_qty, 0)) * stocks.price), 0) as total_cost'))
        ->value('total_cost') ? DB::table('stocks')
        ->leftJoin('used_stocks', 'stocks.id', '=', 'used_stocks.stock_id') // Replace 'id' and 'stock_id' with the actual column names
        ->groupBy('stocks.id') // Group by the primary key of the 'stocks' table
        ->select(DB::raw('COALESCE(SUM((stocks.qty + COALESCE(used_stocks.total_qty, 0)) * stocks.price), 0) as total_cost'))
        ->value('total_cost') : 0;

        $estimatedsales = Products::sum(DB::raw('qty * price'));

        $netrevenue =  $totalSales -  $stockcost;

        $adminId = Session::get('id');
        if($adminId) {
            return view("sales")
            ->with("data", $data)
            ->with('totalQty', $totalQty)
            ->with('totalSales', $totalSales)
            ->with('stockcost', $stockcost)
            ->with('estimatedsales', $estimatedsales)
            ->with('netrevenue', $netrevenue);
        }
        return redirect()->route('login');
    }

    public function deleteSales($id) {
        $sales = Sales::find($id);

        if($sales) {

            $audit = new Audits;

                $audit->name = 'admin';

                $audit->event = 'delete sales'  ;

                $audit->save();

            $sales->delete();
            return redirect()->route('sales');
        }
    }



    public function getAllProduct() {
        $products = Products::all();

        $employeeId = Session::get('employeeId');
        $employeeType = Session::get('employeetype');

        if($employeeId && $employeeType === 'bagger') {
        return view("employee.baggerproducts")->with('products', $products);

        }

        return redirect()->route('employeelogin');

    }

    public function addSales(Request $request) {
        $employeeId = Session::get('employeeId');
        $employee = Employees::find($employeeId);

        $productIds = $request->input('productId');
        $quantities = $request->input('total_qty');
        $totalPrices = $request->input('total_price');

        try {
            $salesData = [];

            // Loop through the submitted data and add sales records
            foreach ($productIds as $key => $productId) {
                $sale = new Sales();
                $sale->product_id = $productId;
                $sale->employee_id = $employeeId;
                $sale->total_qty = $quantities[$key];
                $sale->total_price = $totalPrices[$key];
                // You might want to add other fields if needed

                // Save the sale record
                $product = Products::find($productId); // Retrieve the product first
                $product->decrement('qty', $quantities[$key]);

                $sale->save();



                $salesData[] = [
                    'productName' => Products::find($productId)->product_name,
                    'quantity' => $quantities[$key],
                    'totalPrice' => $totalPrices[$key],
                ];


                $audit = new Audits;

                $audit->name = $employee->name;

                $audit->event = 'bagger sell '. $quantities[$key] .' '. $product->product_name .''  ;

                $audit->save();
            }


            return response()->json(['message' => 'Sales records added successfully', 'salesData' => $salesData]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }



    }

    public function deleteSaleById($id) {
        $saleId = Sales::find($id);

        if($saleId) {

            $saleId->delete();

            $audit = new Audits;

            $audit->name = 'admin';

            $audit->event = 'admin delete sales';

            $audit->save();

            return redirect()->route('sales')->with('success', 'Sale deleted successfully!');
        }
    }



}