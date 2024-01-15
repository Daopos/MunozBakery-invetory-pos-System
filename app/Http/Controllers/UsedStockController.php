<?php

namespace App\Http\Controllers;

use App\Models\Audits;
use App\Models\Employees;
use App\Models\Sales;
use App\Models\Stocks;
use App\Models\UsedStock;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UsedStockController extends Controller
{
    public function addUsedStock(Request $request) {
        $employeeId = Session::get('employeeId');
        $employeeType = Session::get('employeetype');

        $employee = Employees::find($employeeId);

        $stockIds = $request->input('StockId');
        $quantities = $request->input('total_qty');
        $totalPrices = $request->input('total_cost');

        try {

            // Loop through the submitted data and add sales records
            foreach ($stockIds as $key => $stockId) {
                $stock = new UsedStock();
                $stock->stock_id = $stockId;
                $stock->employee_id = $employeeId;
                $stock->total_qty = $quantities[$key];
                $stock->total_cost = $totalPrices[$key];
                // You might want to add other fields if needed
                // Save the sale record
                $stocks = Stocks::find($stockId)->decrement('qty', $quantities[$key]);

                $stock->save();

                $stock =  Stocks::find($stockId);

                $audit = new Audits;

                $audit->name = $employee->name;

                $audit->event = 'baker use stock '. $quantities[$key] .' '. $stock->item_name .''  ;

                $audit->save();

            }


        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

        if($employeeId && $employeeType === 'bagger') {
            return redirect()->route('baggerstock')->with('success', 'Stocks updated successfully!');
        }
        if($employeeId && $employeeType === 'baker') {
            return redirect()->route('bakerstock')->with('success', 'Stocks updated successfully!');
        }


    }

    public function reportForm(Request $request) {


        $selectedDays = $request->input('days', 1); // Default to 1 if not provided

        if ($selectedDays == 1) {
            $today = Carbon::today();
        } else {
            $today = Carbon::now()->subDays($selectedDays);
        }

        $usedStock = UsedStock::join('stocks', 'used_stocks.stock_id', '=', 'stocks.id')
        ->select(
            'stocks.price',
            'stocks.item_name',
            'stocks.image',
            DB::raw('SUM(used_stocks.total_qty) as total_qty') // Sum the total_qty
        )
        ->whereDate('used_stocks.created_at', '>=', $today)
        ->groupBy('stocks.id', 'stocks.price', 'stocks.item_name', 'stocks.image') // Group by relevant columns
        ->get();

        $soldProduct = Sales::join('products', 'sales.product_id', '=', 'products.id')
        ->select(
            'products.price',
            'products.product_name',
            'products.image',
            DB::raw('SUM(sales.total_qty) as total_qty') // Sum the total_sales
        )
        ->whereDate('sales.created_at', '>=', $today)
        ->groupBy('products.id', 'products.price', 'products.product_name', 'products.image') // Group by relevant columns
        ->get();

        $stockCost = UsedStock::whereDate('used_stocks.created_at', '>=', $today)->sum('total_cost');
        $stockUsed = UsedStock::whereDate('used_stocks.created_at', '>=', $today)->sum('total_qty');
        $productSold = Sales::whereDate('sales.created_at', '>=', $today)->sum('total_qty');
        $totalSales = Sales::whereDate('sales.created_at', '>=', $today)->sum('total_price');


        $netRevenue = $totalSales - $stockCost;

        $print = $request->input('print');

        $test = 'test';

        if ($print) {
            // Assuming this is using the Laravel Dompdf package
            $pdf = PDF::loadView('pdf', compact('usedStock', 'soldProduct', 'totalSales', 'stockCost', 'productSold', 'stockUsed', 'selectedDays')); // Adjust the namespace if needed

            // Get the PDF content as a string
            $pdfContent = $pdf->output();

            // Create a response with the PDF content
            $response = response($pdfContent);

            // Set the appropriate headers for a PDF response
            $response->header('Content-Type', 'application/pdf');
            $response->header('Content-Disposition', 'inline; filename="document.pdf"');

            return $response;
        }

        $adminId = Session::get('id');
        if($adminId){
            return view('reports')
            ->with('usedStock', $usedStock)
            ->with('soldProduct', $soldProduct)
            ->with('selectedDays', $selectedDays)
            ->with('stockCost', $stockCost)
            ->with('stockUsed', $stockUsed)
            ->with('productSold', $productSold)
            ->with('totalSales', $totalSales)
            ->with('netRevenue', $netRevenue);
        }

        return redirect()->route('login');
    }



}