<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Datatables;
use DB;
use Response;
use File;
use Excel;
use Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use DateTime;
//use Hash;

class UserController extends Controller
{

   public function __construct()
   {
       $this->middleware('auth');
   }

   public function logout()	
   {
       Auth::logout();
       return redirect('/login');
   }
   public function datatable()	
   {
       return Datatables::of(DB::table('users'))->make(true);
   }
   public function index()
   {
      return view('user/index');
   }
   public function adduser()
   {
      return view('user/adduser');
   }

   public function edituser($id)
   {
      $users = DB::table('users')->where('id',$id)->first();
      return view('user/edituser',['users'=> $users]);
   }
   public function viewuser($id)
   {
      $users = DB::table('users')->where('id',$id)->first();
      return view('user/viewuser',['users'=> $users]);
   }

   public function saveadduser(Request $request)
   {
   
    $data = [
        'name'				=> $request->input('name'),
        'email'				=> $request->input('email'),
        'status'			=> $request->input('status'),
        'phone'				=> $request->input('phone'),
        'commission'        => 0,
        'password'			=> Hash::make($request->input('password')),
        'created_at'		=> new DateTime(),
        'updated_at'		=> new DateTime(),
    ];
   // dd($data);
   
    DB::table('users')->insert($data);
    return response()->json(['success' => 1]);
   }

   public function saveedituser(Request $request)
   {
    if($request->input('password') == "")
    {
        $data = [
            'name'				=> $request->input('name'),
            'email'				=> $request->input('email'),
            'status'			=> $request->input('status'),
            'phone'				=> $request->input('phone'),
            'commission'        => 0,
            'created_at'		=> new DateTime(),
            'updated_at'		=> new DateTime(),
        ];
    }
    else
    {
        $data = [
            'name'				=> $request->input('name'),
            'email'				=> $request->input('email'),
            'status'			=> $request->input('status'),
            'phone'				=> $request->input('phone'),
            'commission'        => 0,
            'password'			=> Hash::make($request->input('password')),
            'created_at'		=> new DateTime(),
            'updated_at'		=> new DateTime(),
        ];
    }

   // dd($data);
   //dd(0);
     DB::table('users')->where('id', $request->input('userid'))->update($data);  
    return response()->json(['success' => 1]);
   }
   public function chkemail(Request $request)
   {
    $checkdata = DB::table('users')->where('email',$request->email)->count();
    return response()->json($checkdata);
   }

   public function chkeditemail(Request $request)
   {
    $data = DB::table('users')->where('id',$request->id)->first();
    if($data->email == $request->email)
    {
        $checkdata =0;
    }
    else
    {
        
        $checkdata = DB::table('users')->where('email',$request->email)->count();
    }
    return response()->json($checkdata);
   }

   public function userdelete(Request $request)
   {
    DB::table('users')->where('id',$request->id)->delete();
    return response()->json(['success' => 1]);
   }


}
