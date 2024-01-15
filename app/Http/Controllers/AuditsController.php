<?php

namespace App\Http\Controllers;

use App\Models\Audits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AuditsController extends Controller
{
    //

    public function showAllAudit() {


        $adminId = Session::get('id');
        if(!$adminId){
            return redirect()->route('login');

        }

        $audit = Audits::paginate(10); // You can set the number of items per page as needed


        return view('audit')->with('audit', $audit);

    }

    public function deleteAuditById($id) {

        $audit = Audits::find($id);

        if($audit) {
            $audit->delete();
            return redirect()->route('audits')->with('success', 'Succesfully delete audit!');
        } else {
            return redirect()->route('audits')->with('error', 'Cannot delete audit!');

        }



    }
}