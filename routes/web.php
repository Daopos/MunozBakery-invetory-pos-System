<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuditsController;
use App\Http\Controllers\EmployeesController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\StocksController;
use App\Http\Controllers\UsedStockController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/dashboard', [AdminController::class, 'getdashboard'])->name('dashboard');;


Route::get('/', function () {
    return redirect(route('login'));
});;


// Admin
Route::get('/admin' ,[AdminController::class, 'adminForm'])->name('admin');
Route::put('admin/edit', [AdminController::class, 'editAdmin'])->name("adminedit");
Route::get('admin/clearinventory', [AdminController::class, 'clearInventory'])->name('clearinventory');
Route::get('admin/logout', [AdminController::class, 'adminLogout'])->name('adminlogout');

// Login Page
Route::get('/login', [AdminController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AdminController::class, 'login']);


// Stock Page
Route::get('/stocks', [StocksController::class, 'getStocks'])->name('stocks');
Route::delete('/deletestock/{id}', [StocksController::class,'deleteStockById'])->name('deletestock');
Route::post('/addstock', [StocksController::class, 'addStock'])->name('addstock');
Route::put('/editstock/{id}', [StocksController::class, 'editStock' ])->name('editstock');
Route::get('/getstock/{id}', [StocksController::class, 'getStock' ])->name('getStock');
Route::put('/editallstock', [StocksController::class, 'updateStock'])->name('updateStock');

// Products Page
Route::get('/products', [ProductsController::class, 'getProducts'])->name('products');
Route::post('/addproduct', [ProductsController::class, 'addProduct'])->name('addproduct');
Route::delete('/deleteproduct/{id}', [ProductsController::class, 'deleteproductById'])->name('deleteproduct');
Route::put('/editproduct/{id}', [ProductsController::class, 'editProduct' ])->name('editproduct');
Route::get('/getproduct/{id}', [ProductsController::class, 'getProduct' ])->name('getproduct');

// Employees Page
Route::get('/employees', [EmployeesController::class, 'getEmployees'])->name('employees');
Route::post('/addemployee', [EmployeesController::class, 'addEmployee'])->name('addemployee');
Route::delete('/deleteEmployee/{id}', [EmployeesController::class,'deleteEmployee'])->name('deleteemployee');
Route::get('/getEmployee/{id}', [EmployeesController::class, 'getEmployee'])->name('getemployee');
Route::put('/updateEmployee\{id}', [EmployeesController::class,'editEmployee'])->name('editemployee');

// Sales page
Route::get('/sales', [SalesController::class, 'getSales'])->name('sales');
Route::delete('/deleteSale/{id}', [SalesController::class, 'deleteSaleById'])->name('deletesale');



// EMPLOYEE
Route::get('/employee/login', [EmployeesController::class,'showLoginForm'])->name('employeelogin');
Route::post('/employee/login', [EmployeesController::class,'loginEmployee']);
Route::get('/bagger/products', [SalesController::class,'getAllProduct'])->name('baggerproducts');
Route::post('/bagger/addsale', [SalesController::class,'addSales'])->name('addsales');

Route::get('/bagger/stocks', [StocksController::class,'getPlasticsStock'])->name('baggerstock');
Route::put('/bagger/updatestock', [StocksController::class, 'baggerUpdateStock'])->name('baggerupdatestocks');

Route::get('/baker/products', [EmployeesController::class,'bakerFormProduct'])->name('bakerproducts');
Route::put('/baker/updateproduct', [ProductsController::class, 'bakerUpdateProduct'])->name('bakerupdateproduct');

Route::get('/baker/stocks', [StocksController::class,'getIngredientsStock'])->name('bakerstock');
Route::put('/baker/updatestock', [StocksController::class, 'bakerUpdateStock'])->name('bakerupdatestocks');







Route::get('/bagger' ,[EmployeesController::class, 'baggerForm'])->name('baggerlog');
Route::get('/baker' ,[EmployeesController::class, 'bakerForm'])->name('bakerlog');


Route::put('/employee/edit', [EmployeesController::class, 'editEmployeeProfile'])->name('profileedit');
Route::get('/bagger/logout' ,[EmployeesController::class, 'employeeLogout'])->name('employeelogout');


Route::post('/bagger/usedstock', [UsedStockController::class, 'addUsedStock'])->name('baggerusedstock');

//REPORTS PAGE
Route::get('/reports', [UsedStockController::class, 'reportForm'])->name('reports');

//AUDIT PAGE
Route::get('/audits', [AuditsController::class, 'showAllAudit'])->name('audits');
Route::delete('/audit/delete/{id}', [AuditsController::class, 'deleteAuditById'])->name('auditdelete');