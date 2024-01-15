<?php

namespace App\Http\Controllers;

use App\Models\Audits;
use App\Models\Employees;
use App\Models\Stocks;
use App\Models\UsedStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class StocksController extends Controller
{
    //

    public function getStocks() {

        $stocks = Stocks::paginate(7);

        $allstock = Stocks::count();
        $totalcost = DB::table('stocks')
        ->leftJoin('used_stocks', 'stocks.id', '=', 'used_stocks.stock_id') // Replace 'id' and 'stock_id' with the actual column names
        ->groupBy('stocks.id') // Group by the primary key of the 'stocks' table
        ->select(DB::raw('COALESCE(SUM((stocks.qty + COALESCE(used_stocks.total_qty, 0)) * stocks.price), 0) as total_cost'))
        ->value('total_cost');
        $usedstock = UsedStock::sum('total_qty');

        $usedperstock =  DB::table('used_stocks')
        ->join('stocks', 'used_stocks.stock_id', '=', 'stocks.id')
        ->select('used_stocks.stock_id', DB::raw('SUM(used_stocks.total_qty) as totalused'))
        ->groupBy('used_stocks.stock_id')
        ->get();

        $remainingstock = Stocks::sum('qty');

        $stockqty = Stocks::sum('qty') + UsedStock::sum('total_qty') ;

        $stocksdata = array(
            'allstock' => $allstock,
            'stockqty' => $stockqty,
            'totalcost' => $totalcost,
            'usedstock' => $usedstock,
            'remainingstock' => $remainingstock
        );

        $adminId = Session::get('id');
        if($adminId) {
            return view("stocks")
            ->with('stocks', $stocks)
            ->with('stocksdata', $stocksdata)
            ->with('usedperstock', $usedperstock);
        }
        return redirect()->route('login');
    }

    public function addStockform(Request $request) {

        return view('examplestock');
    }

    public function addStock(Request $request) {


        $data = new Stocks();

        $validate = $request->validate([
            'item_name' => 'required',
            'stock_type' => 'required|not_in:none',
            'qty' => 'required|integer',
            'price' => 'required|integer',
            'item_image' => 'required|file|mimes:jpeg,png'
         ],[
            'qty.integer' => 'The quantity must be a number.',
            'price.integer' => 'The price must be a number.',
         ]);



        if($validate){

        $data->item_name = $request->input('item_name');
        $data->stock_type = $request->input('stock_type');
        $data->qty = $request->input('qty');
        $data->price = $request->input('price');
            if ($request->hasFile('item_image')) {
                $file = $request->file('item_image');
                $filename = date('YmdHi') . $file->getClientOriginalName();
                $file->move(public_path('Image'), $filename);
                $data->image = $filename;

                $data->save();

                $audit = new Audits;

                $audit->name = 'admin';

                $audit->event = 'admin add new stock '. $data->item_name .'';

                $audit->save();

                return redirect()->route('stocks')->with('success', 'Stock added successfully!');

            }


        } else {
            // Validation failed, redirect back with errors
            return redirect()->back()->withErrors($validate)->withInput();
        }


    }

    public function deleteStockById(Request $request, $id) {


        $stock = Stocks::find($id);

        if($stock) {

            $image_path = public_path().'/Image/'.$stock->image;

            if (file_exists($image_path)) {
                unlink($image_path);
            }

            $stock->delete();

            $audit = new Audits;

            $audit->name = 'admin';

            $audit->event = 'admin add new stock '. $stock->item_name .'';

            $audit->save();


            return redirect()->route('stocks')->with('success', 'Stock deleted successfully!');
        }
    }

    public function updateStock(Request $request) {

        $stocksIds = $request->input('stockId');
        $quantities = $request->input('allqty');

        foreach ($stocksIds as $key => $stockId) {
            try {
                // Find the stock by ID and update its quantity and used_stock
                $stock = Stocks::find($stockId); // Retrieve the stock first
                $stock->update(['qty' => $quantities[$key]]);

                $audit = new Audits;

                $audit->name = 'admin';

                $audit->event = 'admin edit stock '. $stock->item_name .' to '. $quantities[$key] .'';

                $audit->save();

            } catch (\Exception $e) {
                return redirect()->route('stocks')->with('success', 'Stock updated successfully!');
            }
        }

        return redirect()->route('stocks')->with('success', 'Stock updated successfully!');

    }

    public function editStock(Request $request, $id)  {

        $stock = Stocks::find($id);


        $validate = $request->validate([
            'item_name' => 'required',
            'stock_type' => 'required',
            'qty' => 'required|integer',
            'price' => 'required|integer',
            // 'item_image' => 'required|file|mimes:jpeg,png',
         ],[
            'qty.integer' => 'The quantity must be a number.',
            'price.integer' => 'The price must be a number.',
         ]);

        if($stock) {

            if($validate) {
                $stock->item_name = $request->input('item_name');
                $stock->stock_type = $request->input('stock_type');
                $stock->qty = $request->input('qty');
                $stock->price = $request->input('price');


                if ($request->hasFile('item_image')) {
                    $file = $request->file('item_image');
                    $filename = date('YmdHi') . $file->getClientOriginalName();
                    $file->move(public_path('Image'), $filename);
                    $stock->image = $filename;



                }


                $stock->update();

                $audit = new Audits;

                $audit->name = 'admin';

                $audit->event = 'admin edit stock '. $stock->item_name. '';

                $audit->save();


                return redirect()->route('stocks')->with('success', 'Stock updated successfully!');

            } else {
                return redirect()->back()->withErrors($validate)->withInput();
            }


        }

    }
    public function getStock($id)
    {
        $stock = Stocks::find($id);

        return response()->json($stock);
    }

    public function getPlasticsStock() {

         $employeeId = Session::get('employeeId');
        $employeeType = Session::get('employeetype');

        if($employeeId && $employeeType === 'bagger') {
            $stock = Stocks::where('stock_type','=', 'plastics')->get();

            return view('employee.baggerstock')->with('stocks', $stock);

        }

        return redirect()->route('employeelogin');


    }

    public function getIngredientsStock() {


        $employeeId = Session::get('employeeId');
        $employeeType = Session::get('employeetype');

        if($employeeId && $employeeType === 'baker') {
            $stock = Stocks::where('stock_type','=', 'ingredients')->get();

        return view('employee.bakerstock')->with('stocks', $stock);

        }
        return redirect()->route('employeelogin');

    }


    public function baggerUpdateStock(Request $request) {

        $stockIds = $request->input('StockId');
        $stockQty = $request->input('StockQty');

        foreach ($stockIds as $key => $stockId) {
            try {
                // Find the stock by ID and update its quantity and used_stock
                Stocks::find($stockId)->update([
                    'qty' => DB::raw('qty - ' . $stockQty[$key]),
                    'used_stock' => DB::raw('used_stock + ' . $stockQty[$key]),
                ]);
            } catch (\Exception $e) {
                return redirect()->route('baggerstock')->with('success', 'Stock updated successfully!');
            }
        }

        return redirect()->route('baggerstock')->with('success', 'Stock updated successfully!');

    }

    public function bakerUpdateStock(Request $request) {

        $employeeId = Session::get('employeeId');

        $employee = Employees::find($employeeId);

        $stockIds = $request->input('StockId');
        $stockQty = $request->input('StockQty');

        foreach ($stockIds as $key => $stockId) {
            try {
                // Find the stock by ID and update its quantity and used_stock
                Stocks::find($stockId)->update([
                    'qty' => DB::raw('qty - ' . $stockQty[$key]),
                    'used_stock' => DB::raw('used_stock + ' . $stockQty[$key]),
                ]);

                $stock =  Stocks::find($stockId);

                $audit = new Audits;

                $audit->name = $employee->name;

                $audit->event = 'baker use stock '. $stockQty[$key] .' '. $stock->item_name .''  ;

                $audit->save();

            } catch (\Exception $e) {
                return redirect()->route('bakerstock')->with('success', 'Stock updated successfully!');
            }
        }

        return redirect()->route('bakerstock')->with('success', 'Stock updated successfully!');

    }

}