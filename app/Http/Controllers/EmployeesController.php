<?php

namespace App\Http\Controllers;

use App\Models\Audits;
use App\Models\Employees;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class EmployeesController extends Controller
{
    public function getEmployees() {
        $employees = Employees::all();

        $totalofemployee = Employees::count();

        $totalofbaker =  Employees::where('employee_type', '=' , 'baker')->count();

        $totalofbagger = Employees::where('employee_type',  '=' , 'bagger')->count();

        $adminId = Session::get('id');
        if($adminId) {
            return view("employees")->with('employees', $employees)->with('totalbagger', $totalofbagger)->with('totalbaker', $totalofbaker)->with('totalemployee', $totalofemployee);
        }
        return redirect()->route('login');
    }

    public function addEmployee(Request $request) {

        $data = new Employees;

        $data->name = $request->input('name');
        $data->username = $request->input('username');
        $data->password = $request->input('password');
        $data->address = $request->input('address');
        $data->employee_type = $request->input('employee_type');

        if ($request->hasFile('item_image')) {
            $file = $request->file('item_image');
            $filename = date('YmdHi') . $file->getClientOriginalName();
            $file->move(public_path('Image'), $filename);
            $data->image = $filename;

            $audit = new Audits;

            $audit->name = 'admin';

            $audit->event = 'admin add new employee '. $data->name .' '  ;

            $audit->save();

            $data->save();
        } else {
            return redirect()->route('employees')->withErrors(['message' => 'Image not uploaded.']);
        }


        return redirect()->route('employees')->with('success', 'Employee added successfully!');
    }

    public function deleteEmployee($id) {
        $employee = Employees::find($id);

        if($employee) {

            $image_path = public_path().'/Image/'.$employee->image;

            if (file_exists($image_path)) {
                unlink($image_path);
            }

            $audit = new Audits;

            $audit->name = 'admin';

            $audit->event = 'admin delete employee '. $employee->name .' '  ;

            $audit->save();

            $employee->sales()->delete();
            $employee->delete();

            return redirect()->route('employees')->with('success', 'Employee deleted successfully!');
        }


    }

    public function getEmployee($id) {
        $employee = Employees::find($id);

        return response()->json($employee);

    }

    public function editEmployee(Request $request, $id) {
        $employee = Employees::find($id);


        if($employee) {


            $employee->username = $request->input('username');
            $employee->password = $request->input('password');
            $employee->name = $request->input('name');
            $employee->address = $request->input('address');
            $employee->employee_type = $request->input('employee_type');



            if ($request->hasFile('employee_image')) {
                $file = $request->file('employee_image');
                $filename = date('YmdHi') . $file->getClientOriginalName();
                $file->move(public_path('Image'), $filename);
                $employee->image = $filename;



            }

            $audit = new Audits;

            $audit->name = 'admin';

            $audit->event = 'admin edit employee '. $employee->name .' '  ;

            $audit->save();

            $employee->update();


            return redirect()->route('employees')->with('success', 'Employee updated successfully!');;

        }
    }

    public function showLoginForm()
    {
        $employeeId = Session::get('employeeId');
        $employeeType = Session::get('employeetype');

        if($employeeId && $employeeType === 'bagger') {
            return redirect()->route('baggerproducts');
        }
        if($employeeId && $employeeType === 'baker') {
            return redirect()->route('bakerproducts');
        }


        return view('employee.employeelogin'); // You should have a corresponding view for the login form.
    }

    public function loginEmployee(Request $request) {

        $username = $request->username;
        $password = $request->password;

        $employee = Employees::where('username', $username)->first();

        if($employee && $employee->password === $password) {

            $id = $employee->id;

            $type = $employee->employee_type;





            if($type === 'bagger') {
                Session::put('employeeId', $id);
                Session::put('employeetype', $type);


                $audit = new Audits;

                $audit->name = $employee->name;

                $audit->event = 'employee login'  ;

                $audit->save();

                return redirect()->route('baggerproducts');



            }
            else if($type === 'baker') {

                Session::put('employeeId', $id);
                Session::put('employeetype', $type);

                $audit = new Audits;

                $audit->name = $employee->name;

                $audit->event = 'employee login'  ;

                $audit->save();

                return redirect()->route('bakerproducts');
            }
        }else {
            return redirect()->route('employeelogin')->with('error', 'Login failed');
        }

    }

    public function bakerFormProduct() {
        $products = Products::all();

        $employeeId = Session::get('employeeId');
        $employeeType = Session::get('employeetype');

        if($employeeId && $employeeType === 'baker') {
        return view("employee.bakerproducts")->with('products', $products);

        }

        return redirect()->route('employeelogin');

    }

    public function bakerFormStocks() {
        $products = Products::all();

        $employeeId = Session::get('employeeId');
        $employeeType = Session::get('employeetype');

        if($employeeId && $employeeType === 'baker') {
        return view("employee.bakerproducts")->with('products', $products);

        }

        return redirect()->route('employeelogin');

    }

    public function baggerForm() {


        $employeeId = Session::get('employeeId');
        $employeeType = Session::get('employeetype');

        $employee = Employees::find($employeeId);

        if($employeeId && $employeeType === 'bagger') {
        return view("employee.bagger")->with('employee', $employee);
        }

        return redirect()->route('employeelogin');
    }

    public function bakerForm() {


        $employeeId = Session::get('employeeId');
        $employeeType = Session::get('employeetype');

        $employee = Employees::find($employeeId);

        if($employeeId && $employeeType === 'baker') {
        return view("employee.baker")->with('employee', $employee);
        }

        return redirect()->route('employeelogin');
    }

    public function editEmployeeProfile(Request $request) {

        $employeeId = Session::get('employeeId');
        $employeeType = Session::get('employeetype');

        $employee = Employees::find($employeeId);

        $username = $request->username;
        $password = $request->password;

        if($username && $password) {

            $employee->username = $username;
            $employee->password = $password;

            $employee->save();

            if($employeeId && $employeeType === 'baker') {
                return redirect()->route('bakerlog');
                }
            if($employeeId && $employeeType === 'bagger') {
                return redirect()->route('baggerlog');
                 }
        }
    }

    public function employeeLogout() {

        $employeeId = Session::get('employeeId');

        $employee = Employees::find($employeeId);

        $audit = new Audits;

        $audit->name = $employee->name;

        $audit->event = 'employee log out'  ;

        $audit->save();

        Session::forget('employeeId');
        Session::forget('employeetype');

        return redirect()->route('employeelogin');
    }

}