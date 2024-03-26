<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
   public function index(){

        $role=Auth::user()->role;

        if ($role=='admin') {
            return redirect()->route("admin.accounts");
        }else if($role=='teller'){
            return redirect()->route("teller.dashboard");
        }else if($role=='customer'){
            return redirect()->route("customer.dashboard");
        }else{
            return view("dashboard");
        }

    }
}
