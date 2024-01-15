<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Audits;
use App\Models\Products;
use App\Models\Sales;
use App\Models\Stocks;
use App\Models\UsedStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // Method to display the login form
    public function showLoginForm()
    {

        $adminId = Session::get('id');
        if($adminId) {
            return redirect()->route('dashboard');
        }

        return view('login'); // You should have a corresponding view for the login form.
    }

    // Method to handle the login form submission
    public function login(Request $request)
    {

        $credentials = $request->only('username', 'password');

        $admin = Admin::all();

        $selectAdmin = Admin::where('username', $credentials['username'])->first();

        if ($selectAdmin && $selectAdmin->password === $credentials['password']) {
            $id = $selectAdmin->id;
            Session::put('id', $id);
            return redirect()->route('dashboard');
        } else {
            return redirect()->route('login')->with('error', 'Login failed');
        }
    }

    public function getDashboard() {

        // $bestProducts = Products::orderBy('qty', 'ASC')->limit(3)->get();

        $bestProducts = DB::table('sales')
        ->join('products', 'sales.product_id', '=', 'products.id')
        ->select('products.image', 'products.id', 'products.product_name', 'products.price', 'products.qty', DB::raw('SUM(sales.total_qty) as total_qty'))
        ->groupBy('products.image', 'products.id', 'products.product_name', 'products.price', 'products.qty')
        ->orderBy('total_qty', 'DESC')
        ->limit(3)
        ->get();

        $productsSold  = DB::table('sales')
        ->join('products', 'sales.product_id', '=', 'products.id')
        ->select('sales.product_id', DB::raw('SUM(sales.total_qty) as totalsold'))
        ->groupBy('sales.product_id')
        ->get();

        $recentlySale = DB::table('sales')
        ->join('products','sales.product_id','=','products.id')
        ->select('sales.*', 'products.product_name', 'products.price', 'products.image')
        ->orderBy('created_at', 'DESC')
        ->limit(4)
        ->get();

        $recentlyUsed = DB::table('used_stocks')
        ->join('stocks','used_stocks.stock_id','=','stocks.id')
        ->select('used_stocks.*', 'stocks.item_name', 'stocks.price', 'stocks.image')
        ->orderBy('created_at', 'DESC')
        ->limit(4)
        ->get();;

        $highStock = Stocks::where('qty', '>=', '20')->count();
        $mediumStock = Stocks::where('qty', '>=', '10')->where('qty', '<=', '19')->count();
        $lowStock = Stocks::where('qty', '<=', '9')->count();

        $stock = Stocks::sum('qty');

        $productmade = Products::sum('qty');

        $totalsale = Sales::sum('total_price');

        $usedstock = UsedStock::sum('total_qty');

        $totalsold = DB::table('sales')
        ->select(DB::raw('SUM(sales.total_qty) as totalsold'))
        ->join('products', 'sales.product_id', '=', 'products.id')
        ->first()->totalsold ? DB::table('sales')
        ->select(DB::raw('SUM(sales.total_qty) as totalsold'))
        ->join('products', 'sales.product_id', '=', 'products.id')
        ->first()->totalsold : 0;

        $stockDetails = array (
            'high' => $highStock,
            'medium' => $mediumStock,
            'low' => $lowStock
        );

        $dashboard = array(
            'stocks' => $stock,
            'totalsales'=> $totalsale,
            'made' => $productmade,
            'sold' => $totalsold,
            'usedstock' => $usedstock
        );



        $adminId = Session::get('id');
        if($adminId) {
            return view("dashboard")
            ->with('bestProducts', $bestProducts)
            ->with('productsSold', $productsSold)
            ->with('stockDetails', $stockDetails)
            ->with('dashboard', $dashboard)
            ->with('recentlySale', $recentlySale)
            ->with('recentlyUsed', $recentlyUsed);
        }
        return redirect()->route('login');
    }

    public function adminForm() {


        $adminId = Session::get('id');

        $admin = Admin::find($adminId);

        if($adminId) {
            return view("admin")->with('admin', $admin);
        }
        return redirect()->route('login');
    }

    public function editAdmin(Request $request) {

        $adminId = Session::get('id');

        $admin = Admin::find($adminId);

        $username = $request->username;
        $password = $request->password;

        if($username && $password) {

            $admin->username = $username;
            $admin->password = $password;

            $admin->save();

        return redirect()->route('admin')->with('success', 'Succesfully edit profile');

        }


    }

    public function clearInventory() {

        Sales::truncate();

        UsedStock::truncate();

        Audits::truncate();

        Products::query()->update(['qty' => 0]);

        Stocks::query()->update(['qty' => 0]);

        return redirect()->route('admin')->with('success', 'Succesfully Clear Inventory');

    }

    public function adminLogout() {

        Session::forget('id');

        return redirect()->route('login');

    }




    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // $username = "admin123";
        // $password = "admin123";
        // $data=array('username'=>$username,"password"=>$password);
        // DB::table('admins')->insert($data);

        // return $data;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Admin $admin)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Admin $admin)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Admin $admin)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Admin $admin)
    {
        //
    }
}
