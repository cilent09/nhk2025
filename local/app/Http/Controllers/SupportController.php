<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
use DateTime;
use Session;
use Response;
use Datatables;
use Hash;
use File;
use Illuminate\Support\Facades\Auth;

class SupportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function help()
    {

        $team = DB::table('nt_help_team')->get();
        $status = Auth::user()->status;
        $data = [
            'team' => $team,
            'status' => $status,
        ];
        return view('support/help', $data);

    } 
    public function instruction()
    {
        return view('support/instruction');
    } 
    public function needsupport()
    {
        return view('support/support');
    } 
    public function editteam($id)
    {

        $team = DB::table('nt_help_team')->where('id_members', $id)->first();
        $data = [
            'team' => $team,
            'id' => $id,
        ];

        return view('support/editteam', $data);

    }
    public function saveeditteam(Request $request)
    {

        $check = DB::table('nt_help_team')->where('id_members', $request->input('teamid'))->first();
        if($request->file('image')){

            $image = storage_path('NHK-File/template/help/team_members/'. $check->profile_members);
            if(file_exists($image)) {
                @unlink($image);
            }
            
            $file= $request->file('image');
            $filename= date('YmdHi').'_'.$file->getClientOriginalName();
            $file-> move(storage_path('NHK-File\template\help\team_members'), $filename);
            $data['profile_members']= $filename;
        }
        $data['name_members'] = $request->name;
        DB::table('nt_help_team')->where('id_members', $request->input('teamid'))->update($data);

        return response()->json(['success' => 1]);


    }
    
}