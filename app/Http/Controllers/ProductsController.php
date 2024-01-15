<?php

namespace App\Http\Controllers;

use App\Models\Audits;
use App\Models\Employees;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Throwable;

class ProductsController extends Controller
{
    public function getProducts() {

        $products = Products::paginate(7);

        $productsSold  = DB::table('sales')
        ->join('products', 'sales.product_id', '=', 'products.id')
        ->select('sales.product_id', DB::raw('SUM(sales.total_qty) as totalsold'))
        ->groupBy('sales.product_id')
        ->get();


        $totalqty = DB::table('products')->sum('qty');
        $totalprice = Products::sum(DB::raw('qty * price'));
        $totalproducts = DB::table('products')->count();

        $totalsold = DB::table('sales')
        ->select(DB::raw('SUM(sales.total_qty) as totalsold'))
        ->join('products', 'sales.product_id', '=', 'products.id')
        ->first()->totalsold ? DB::table('sales')
        ->select(DB::raw('SUM(sales.total_qty) as totalsold'))
        ->join('products', 'sales.product_id', '=', 'products.id')
        ->first()->totalsold : 0;

        $dashboard = array(
            'totalqty' => $totalqty,
            'totalprice'=> $totalprice,
            'totalproducts' => $totalproducts,
            'totalsold' => $totalsold
        );

        $adminId = Session::get('id');
        if($adminId) {
            return view("products")
            ->with('products', $products)
            ->with('productsSold', $productsSold)
            ->with('dashboard', $dashboard);
        }
        return redirect()->route('login');
    }

    public function addProduct(Request $request) {

        $data = new Products;

        $validate = $request->validate([
            'product_name' => 'required',
            'qty' => 'required|integer',
            'price' => 'required|integer',
            'item_image' => 'required|file|mimes:jpeg,png'
         ],[
            'qty.integer' => 'The quantity must be a number.',
            'price.integer' => 'The price must be a number.',
         ]);

         if ($validate) {

        $data->product_name = $request->input('product_name');
        $data->qty = $request->input('qty');
        $data->price = $request->input('price');


        if ($request->hasFile('item_image')) {
            $file = $request->file('item_image');
            $filename = date('YmdHi') . $file->getClientOriginalName();
            $file->move(public_path('Image'), $filename);
            $data->image = $filename;


            $audit = new Audits;

            $audit->name = 'admin';

            $audit->event = 'admin add new product '. $data->product_name .''  ;

            $audit->save();

            $data->save();
            return redirect()->route('products')->with('success', 'Product added successfully!');

        }



        }else {
            // Validation failed, redirect back with errors
            return redirect()->back()->withErrors($validate)->withInput();
        }
    }
    public function deleteProductById(Request $request, $id) {
        $product = Products::find($id);

        if ($product) {
            $image_path = public_path('/Image/'.$product->image);

            if (file_exists($image_path)) {
                unlink($image_path);
            }

            $audit = new Audits;

            $audit->name = 'admin';

            $audit->event = 'admin delete product '. $product->product_name .''  ;

            $audit->save();

            $product->sales()->delete();

            $product->delete();

            return redirect()->route('products')->with('success', 'Product deleted successfully!');
        }
    }

    public function editProduct(Request $request, $id)  {

        $product = Products::find($id);


        $validate = $request->validate([
            'product_name' => 'required',
            'qty' => 'required|integer',
            'price' => 'required|integer',
         ],[
            'qty.integer' => 'The quantity must be a number.',
            'price.integer' => 'The price must be a number.',
         ]);



        if($product) {
            if($validate) {
                $product->product_name = $request->input('product_name');
                $product->qty = $request->input('qty');
                $product->price = $request->input('price');


                if ($request->hasFile('product_image')) {
                    $file = $request->file('product_image');
                    $filename = date('YmdHi') . $file->getClientOriginalName();
                    $file->move(public_path('Image'), $filename);
                    $product->image = $filename;
                }

                $audit = new Audits;

                $audit->name = 'admin';

                $audit->event = 'admin edit product '. $product->product_name .''  ;

                $audit->save();


                $product->update();

                return redirect()->route('products')->with('success', 'Product updated successfully!');
            } else {
                return redirect()->back()->withErrors($validate)->withInput();

            }


        }

    }

    public function getProduct($id) {
        $product = Products::find($id);

        return response()->json($product);

    }

    public function bakerUpdateProduct(Request $request) {

        $employeeId = Session::get('employeeId');

        $employee = Employees::find($employeeId);

        $productIds = $request->input('ProductId');
        $productQty = $request->input('ProductQty');

        foreach ($productIds as $key => $productId) {
            try {
                // Find the stock by ID and update its quantity and used_stock
                Products::find($productId)->increment('qty', $productQty[$key]);

                $product =  Products::find($productId);

                $audit = new Audits;

                $audit->name = $employee->name;

                $audit->event = 'baker add product '. $productQty[$key] .' '. $product->product_name .''  ;

                $audit->save();

            } catch (\Exception $e) {
                return redirect()->route('bakerproducts')->with('success', 'Stock updated successfully!');
            }
        }

        return redirect()->route('bakerproducts')->with('success', 'Stock updated successfully!');
    }



}