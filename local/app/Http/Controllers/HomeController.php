<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{

   public function __construct()
   {
       $this->middleware('auth');
   }

    public function index()
    {
        // dd(Auth::user()->status);
        return view('welcome');
        // return view('OQA/index');
   
    }

    // public function landingload()
    // {
    //     return view('welcome');
    // }

    public function setlanding($type)
    {
        // เก็บค่า
        Session::put('namemenu', $type);
        // ดึงค่า
        $value = Session::get('namemenu');
        // // ลบค่า
        // Session::forget('namemenu');

        if($type == "oqa"){
            return redirect()->route('index');
        }elseif ($type == "oqa2") {
            return redirect()->route('index');
        }elseif ($type == "queue") {
            return redirect()->route('queue');
        }elseif ($type == "iqa") {
            
        }elseif ($type == "monitor") {
            return redirect()->route('monitoring');
        }elseif ($type == "dailycheck") {
            return redirect()->route('dailycheck');
        }else{
            return view('welcome');
        }


    }

}
