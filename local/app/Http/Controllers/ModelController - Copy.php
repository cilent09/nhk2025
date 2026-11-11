<?php

namespace App\Http\Controllers;
use Datatables;
use DB;
use Illuminate\Http\Request;
use Response;
use File;
use Excel;
use Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\IOFactory; 
use Session;
use slope;

class ModelController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()	
    {
        return view('master/model/index');
    }
    public function indexdata()	
    {
        return view('master/model/indexdata');
    }
    public function datatable()	
    {
        return Datatables::of(DB::table('nt_model')->where('model_type','LIKE','SGT_%')->where('status_model', 1))->make(true);
    }
    public function datatablewd()
    {
        return Datatables::of(DB::table('nt_model')->where('model_type','LIKE','WD_%')->where('status_model',1))->make(true);
    }
    public function historydatatable(Request $request)	
    {
        
        $data = DB::table('nt_model_history')
        ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
        ->select('nt_model_history.sub_number','nt_model_history.id_model','nt_model.name_model','nt_model_history.date_start','nt_model_history.date_end','nt_model_history.note')
        ->groupBy('nt_model_history.sub_number','nt_model_history.id_model','nt_model.name_model','nt_model_history.date_start','nt_model_history.date_end','nt_model_history.note')
        ->where('nt_model_history.id_model', $request->modelid)
        ->get();
        return Datatables::of($data)->make(true);

    }
    public function manageralarm()	
    {
        $data = DB::table('nt_alarm')->where('alarm_id',1)->first();
    
        return view('master/model/manageralarm', ['data' => $data]);
    }
    public function updatealarm(Request $request)
    {
        $data = [
            'test2' => $request->input('test2'),
            'test3' => $request->input('test3'),
            'date_update' =>  date('Y-m-d H:i:s'),
            'user_id' => Auth::user()->id,
        ];
        DB::table('nt_alarm')->where('alarm_id', 1)->update($data);
        return response()->json(['success' => 1]);
    }
    public function addmodel()
    {
        $modeltype = DB::table('nt_model_spec_type')->get();
        $parameter_type = DB::table('nt_model_parameter_type')->get();
        $model_volume = DB::table('nt_model_unit_volume')->where('status',1)->get();
        $model_area = DB::table('nt_model_unit_area')->where('status',1)->get();
        $model_electric = DB::table('nt_model_unit_electric')->where('status',1)->get();
        $model_part = DB::table('nt_model_unit_part')->where('status',1)->get();
        $model_time = DB::table('nt_model_unit_time')->where('status',1)->get();
        $model_part_mdl = DB::table('nt_model_unit_mdl_part')->where('status',1)->get(); // NHK2 27/02/24
        return view('master/model/addmodel', 
        [
         'modeltype' => $modeltype,
         'parameterType' => $parameter_type,
         'model_volume'=>$model_volume,
         'model_area'=>$model_area,
         'model_electric'=>$model_electric,
         'model_part'=>$model_part,
         'model_time'=>$model_time,
         'model_part_mdl'=>$model_part_mdl, // NHK2 27/02/24
        ]);
    }
    public function addmodeldata($id)
    {
        $modeltype = DB::table('nt_model_spec_type')->get();
        $nt_model_spec = DB::table('nt_model_spec')
        ->join('nt_model_spec_type', 'nt_model_spec.id_model_type', '=', 'nt_model_spec_type.id_model_type')
        ->where('nt_model_spec.id_model', $id)
        ->get();
        return view('master/model/addmodeldata', 
        [
         'modelid' => $id,
         'modeltype' => $modeltype,
         'model_spec' => $nt_model_spec,
        ]);
    }
    public function editmodel($id,$chk)
    {
        $nt_model = DB::table('nt_model')->where('id_model', $id)->first();
        $model_volume = DB::table('nt_model_unit_volume')->where('status',1)->get();
        $model_area = DB::table('nt_model_unit_area')->where('status',1)->get();
        $model_electric = DB::table('nt_model_unit_electric')->where('status',1)->get();
        $model_part = DB::table('nt_model_unit_part')->where('status',1)->get();
        $model_time = DB::table('nt_model_unit_time')->where('status',1)->get();
        $nt_model_spec = DB::table('nt_model_spec')
            ->join('nt_model_spec_type', 'nt_model_spec.id_model_type', '=', 'nt_model_spec_type.id_model_type')
            ->where('nt_model_spec.id_model', $id)
            ->get();
        $nt_model_parameter = DB::table('nt_model_parameter')
            ->join('nt_model_parameter_type', 'nt_model_parameter.id_model_p', '=', 'nt_model_parameter_type.id_model_p')
            ->where('nt_model_parameter.id_model', $id)
            ->get();
        $modeltype = DB::table('nt_model_spec_type')->get();
        $parameter_type = DB::table('nt_model_parameter_type')->get();
        $model_part_mdl = DB::table('nt_model_unit_mdl_part')->where('status',1)->get(); // NHK2 27/02/24
        return view('master/model/editmodel', ['modeltype' => $modeltype,
            'parameterType' => $parameter_type,
            'nt_model' => $nt_model,
            'model_spec' => $nt_model_spec,
            'model_parameter' => $nt_model_parameter,
            'model_volume'=>$model_volume,
            'model_area'=>$model_area,
            'model_electric'=>$model_electric,
            'model_part'=>$model_part,
            'model_time'=>$model_time,
            'model_part_mdl'=>$model_part_mdl, // NHK2 27/02/24
            'chk' => $chk
            ]
        );
    }
    public function editmodeldata($id,$idmodel)
    {

        $nt_model_spec = DB::table('nt_model_history')
            ->join('nt_model_spec_type', 'nt_model_history.id_model_type', '=', 'nt_model_spec_type.id_model_type')
            ->where('nt_model_history.sub_number', $id)
            ->where('nt_model_history.id_model', $idmodel)
            ->get();
           
        return view('master/model/editmodeldata', [
            'model_spec' => $nt_model_spec,
            'subnumber' => $id
            ]
        );
    }
    
    public function viewmodel($id)
    {
        $nt_model = DB::table('nt_model')->where('id_model', $id)->first();
        $nt_model_spec = DB::table('nt_model_spec')
            ->join('nt_model_spec_type', 'nt_model_spec.id_model_type', '=', 'nt_model_spec_type.id_model_type')
            ->where('nt_model_spec.id_model', $id)
            ->get();
        $nt_model_parameter = DB::table('nt_model_parameter')
            ->join('nt_model_parameter_type', 'nt_model_parameter.id_model_p', '=', 'nt_model_parameter_type.id_model_p')
            ->join('nt_model', 'nt_model_parameter.id_model', '=', 'nt_model.id_model')
            ->where('nt_model_parameter.id_model', $id)
            ->get();
          
        $modeltype = DB::table('nt_model_spec_type')->get();
        $parameter_type = DB::table('nt_model_parameter_type')->get();
        return view('master/model/viewmodel', ['modeltype' => $modeltype,
            'parameterType' => $parameter_type,
            'nt_model' => $nt_model,
            'model_spec' => $nt_model_spec,
            'model_parameter' => $nt_model_parameter]
        );
    }

    public function viewmodeldata($id,$idmodel)
    {
        $nt_model_spec = DB::table('nt_model_history')
        ->join('nt_model_spec_type', 'nt_model_history.id_model_type', '=', 'nt_model_spec_type.id_model_type')
        ->where('nt_model_history.sub_number', $id)
        ->where('nt_model_history.id_model', $idmodel)
        ->get(); 
        $modeltype = DB::table('nt_model_spec_type')->get();
        return view('master/model/viewmodeldata', ['modeltype' => $modeltype,
            'model_spec' => $nt_model_spec]
        );
    }

    public function indexhistory($id)
    {
        return view('master/model/indexhistory', ['modelid' => $id]);
    }

    public function addmodeldatasave(Request $request)
    {

        $modeltype = DB::table('nt_model_spec_type')->get();
        $maxsubnumber = DB::table('nt_model_history')->where('id_model', $request->input('modelid'))->max('sub_number');

        
        $number = $maxsubnumber + 1;
        foreach ($modeltype as $item) {
            if($item->id_model_type == 2 || $item->id_model_type == 3 ||  $item->id_model_type == 4 ||  $item->id_model_type == 5)
            {
            if(!empty($request->input('alpc' . $item->text)))
            { 
                $alpc = $request->input('alpc' . $item->text);
            }
            else
            {
                $alpc= "N/A";
            }
            if(!empty($request->input('class1' . $item->text)))
            { 
                $class1 = $request->input('class1' . $item->text);
            }
            else
            {
                $class1= "N/A";
            }       
            if(!empty($request->input('class2' . $item->text)))
            { 
                $class2 = $request->input('class2' . $item->text);
            }
            else
            {
                $class2= "N/A";
            }
            if(!empty($request->input('class3' . $item->text)))
            { 
                $class3 = $request->input('class3' . $item->text);
            }
            else
            {
                $class3= "N/A";
            }       
            if(!empty($request->input('class3_1' . $item->text)))
            { 
                $class3_1 = $request->input('class3_1' . $item->text);
            }
            else
            {
                $class3_1= "N/A";
            }          
            if(!empty($request->input('class3_2' . $item->text)))
            { 
                $class3_2 = $request->input('class3_2' . $item->text);
            }
            else
            {
                $class3_2= "N/A";
            }
            if(!empty($request->input('class3_3' . $item->text)))
            { 
                $class3_3 = $request->input('class3_3' . $item->text);
            }
            else
            {
                $class3_3= "N/A";
            }
            if(!empty($request->input('class4' . $item->text)))
            { 
                $class4 = $request->input('class4' . $item->text);
            }
            else
            {
                $class4= "N/A";
            }
            if(!empty($request->input('class5' . $item->text)))
            { 
                $class5 = $request->input('class5' . $item->text);
            }
            else
            {
                $class5= "N/A";
            }
            if(!empty($request->input('class5_1' . $item->text)))
            { 
                $class5_1 = $request->input('class5_1' . $item->text);
            }
            else
            {
                $class5_1= "N/A";
            }
            if(!empty($request->input('class6' . $item->text)))
            { 
                $class6 = $request->input('class6' . $item->text);
            }
            else
            {
                $class6= "N/A";
            }
            if(!empty($request->input('anion' . $item->text)))
            { 
                $anion = $request->input('anion' . $item->text);
            }
            else
            {
                $anion= "N/A";
            }
            if(!empty($request->input('sulphate' . $item->text)))
            { 
                $sulphate = $request->input('sulphate' . $item->text);
            }
            else
            {
                $sulphate= "N/A";
            }
            if(!empty($request->input('cation' . $item->text)))
            { 
                $cation = $request->input('cation' . $item->text);
            }
            else
            {
                $cation= "N/A";
            }
            if(!empty($request->input('outgassing' . $item->text)))
            { 
                $outgassing = $request->input('outgassing' . $item->text);
            }
            else
            {
                $outgassing = "N/A";
            }
            if(!empty($request->input('alkylacrylate' . $item->text)))
            { 
                $alkylacrylate = $request->input('alkylacrylate' . $item->text);
            }
            else
            {
                $alkylacrylate= "N/A";
            }
            if(!empty($request->input('low_MW' . $item->text)))
            { 
                $low_MW = $request->input('low_MW' . $item->text);
            }
            else
            {
                $low_MW= "N/A";
            }
            if(!empty($request->input('high_MW' . $item->text)))
            { 
                $high_MW = $request->input('high_MW' . $item->text);
            }
            else
            {
                $high_MW= "N/A";
            }
            if(!empty($request->input('ftir' . $item->text)))
            { 
                $ftir = $request->input('ftir' . $item->text);
            }
            else
            {
                $ftir= "N/A";
            }
            if($request->input('chkbox1') == 1)
            {
               
                $datestart = Date("Y-m-d", strtotime($request->input('date_start')."-1 Day"));
                $modelspec2 = [
                    'date_end' => $datestart,
                    'chkstatus' => 0
                ];
                DB::table('nt_model_history')
                ->where('id_model', $request->input('modelid'))
                ->where('id_model_type', $item->id_model_type)
                ->where('chkstatus', 1)
                ->update($modelspec2);
                $modelspec = [
                    'id_model' => $request->input('modelid'),
                    'id_model_type' => $request->input('typeID' . $item->text),
                    'sub_number' =>$number,
                    'alpc' => $alpc,
                    'class1' => $class1,
                    'class2' => $class2,
                    'class3' => $class3,
                    'class3_1' => $class3_1,
                    'class3_2' => $class3_2,
                    'class3_3' => $class3_3,
                    'class4' => $class4,
                    'class5' => $class5,
                    'class5_1' => $class5_1,
                    'class6' => $class6,
                    'anion' => $anion,
                    'sulphate' => $sulphate,
                    'cation' => $cation,
                    'outgassing' => $outgassing,
                    'alkylacrylate' => $alkylacrylate,
                    'low_MW' => $low_MW,
                    'high_MW' => $high_MW,
                    'ftir' => $ftir,
                    'date_start' => $request->input('date_start'),
                    'date_end' => '2060-12-31',
                    'note' => $request->input('textnote'),
                    'save_date' => date('Y-m-d H:i:s'),
                    'chkstatus' => 1,
                ];
                DB::table('nt_model_history')->insert($modelspec);
                $ckhistorydate = DB::table('nt_model_history')->where('id_model', $request->input('modelid'))->orderby('date_end','desc')->first();
                $modelspec1 = [
                    'alpc' => $alpc,
                    'class1' => $class1,
                    'class2' => $class2,
                    'class3' => $class3,
                    'class3_1' => $class3_1,
                    'class3_2' => $class3_2,
                    'class3_3' => $class3_3,
                    'class4' => $class4,
                    'class5' => $class5,
                    'class5_1' => $class5_1,
                    'class6' => $class6,
                    'anion' => $anion,
                    'sulphate' => $sulphate,
                    'cation' => $cation,
                    'outgassing' => $outgassing,
                    'alkylacrylate' => $alkylacrylate,
                    'low_MW' => $low_MW,
                    'high_MW' => $high_MW,
                    'ftir' => $ftir,
                ];      
                $idspec = $request->input('chkidspec' . $item->text);
                DB::table('nt_model_spec')->where('id_model_spec', $idspec)->update($modelspec1);
            }
            else
            {
                $modelspec = [
                    'id_model' => $request->input('modelid'),
                    'id_model_type' => $request->input('typeID' . $item->text),
                    'sub_number' =>$number,
                    'alpc' => $alpc,
                    'class1' => $class1,
                    'class2' => $class2,
                    'class3' => $class3,
                    'class3_1' => $class3_1,
                    'class3_2' => $class3_2,
                    'class3_3' => $class3_3,
                    'class4' => $class4,
                    'class5' => $class5,
                    'class5_1' => $class5_1,
                    'class6' => $class6,
                    'anion' => $anion,
                    'sulphate' => $sulphate,
                    'cation' => $cation,
                    'outgassing' => $outgassing,
                    'alkylacrylate' => $alkylacrylate,
                    'low_MW' => $low_MW,
                    'high_MW' => $high_MW,
                    'ftir' => $ftir,
                    'date_start' => $request->input('date_start'),
                    'date_end' => $request->input('date_end'),
                    'note' => $request->input('textnote'),
                    'save_date' => date('Y-m-d H:i:s'),
                    'chkstatus' => 0,
                ];
                DB::table('nt_model_history')->insert($modelspec);
                
            }
           }
        }
        return response()->json(['success' => 1,'id' => $request->input('modelid')]);
        
    }
    public function editmodeldatasave(Request $request)
    {
       
        $modeltype = DB::table('nt_model_spec_type')->get();
        foreach ($modeltype as $item) {
            
            if($item->id_model_type == 2 || $item->id_model_type == 3 ||  $item->id_model_type == 4 ||  $item->id_model_type == 5)
            {
            if(!empty($request->input('alpc' . $item->text)))
            { 
                $alpc = $request->input('alpc' . $item->text);
            }
            else
            {
                $alpc= "N/A";
            }
            if(!empty($request->input('class1' . $item->text)))
            { 
                $class1 = $request->input('class1' . $item->text);
            }
            else
            {
                $class1= "N/A";
            }       
            if(!empty($request->input('class2' . $item->text)))
            { 
                $class2 = $request->input('class2' . $item->text);
            }
            else
            {
                $class2= "N/A";
            }
            if(!empty($request->input('class3' . $item->text)))
            { 
                $class3 = $request->input('class3' . $item->text);
            }
            else
            {
                $class3= "N/A";
            }       
            if(!empty($request->input('class3_1' . $item->text)))
            { 
                $class3_1 = $request->input('class3_1' . $item->text);
            }
            else
            {
                $class3_1= "N/A";
            }          
            if(!empty($request->input('class3_2' . $item->text)))
            { 
                $class3_2 = $request->input('class3_2' . $item->text);
            }
            else
            {
                $class3_2= "N/A";
            }
            if(!empty($request->input('class3_3' . $item->text)))
            { 
                $class3_3 = $request->input('class3_3' . $item->text);
            }
            else
            {
                $class3_3= "N/A";
            }
            if(!empty($request->input('class4' . $item->text)))
            { 
                $class4 = $request->input('class4' . $item->text);
            }
            else
            {
                $class4= "N/A";
            }
            if(!empty($request->input('class5' . $item->text)))
            { 
                $class5 = $request->input('class5' . $item->text);
            }
            else
            {
                $class5= "N/A";
            }
            if(!empty($request->input('class5_1' . $item->text)))
            { 
                $class5_1 = $request->input('class5_1' . $item->text);
            }
            else
            {
                $class5_1= "N/A";
            }
            if(!empty($request->input('class6' . $item->text)))
            { 
                $class6 = $request->input('class6' . $item->text);
            }
            else
            {
                $class6= "N/A";
            }
            if(!empty($request->input('anion' . $item->text)))
            { 
                $anion = $request->input('anion' . $item->text);
            }
            else
            {
                $anion= "N/A";
            }
            if(!empty($request->input('sulphate' . $item->text)))
            { 
                $sulphate = $request->input('sulphate' . $item->text);
            }
            else
            {
                $sulphate= "N/A";
            }
            if(!empty($request->input('cation' . $item->text)))
            { 
                $cation = $request->input('cation' . $item->text);
            }
            else
            {
                $cation= "N/A";
            }
            if(!empty($request->input('outgassing' . $item->text)))
            { 
                $outgassing = $request->input('outgassing' . $item->text);
            }
            else
            {
                $outgassing = "N/A";
            }
            if(!empty($request->input('alkylacrylate' . $item->text)))
            { 
                $alkylacrylate = $request->input('alkylacrylate' . $item->text);
            }
            else
            {
                $alkylacrylate= "N/A";
            }
            if(!empty($request->input('low_MW' . $item->text)))
            { 
                $low_MW = $request->input('low_MW' . $item->text);
            }
            else
            {
                $low_MW= "N/A";
            }
            if(!empty($request->input('high_MW' . $item->text)))
            { 
                $high_MW = $request->input('high_MW' . $item->text);
            }
            else
            {
                $high_MW= "N/A";
            }
            if(!empty($request->input('ftir' . $item->text)))
            { 
                $ftir = $request->input('ftir' . $item->text);
            }
            else
            {
                $ftir= "N/A";
            }
            
            if($request->input('chkbox1') == 1)
            {
                    $datestart = Date("Y-m-d", strtotime($request->input('date_start')."-1 Day"));
                    $modelspec2 = [
                        'date_end' => $datestart,
                        'chkstatus' => 0
                    ];
                    DB::table('nt_model_history')
                    ->where('id_model', $request->input('modelid'))
                    ->where('id_model_type', $item->id_model_type)
                    ->where('chkstatus', 1)
                    ->update($modelspec2);
                    $modelspec = [
                        'alpc' => $alpc,
                        'class1' => $class1,
                        'class2' => $class2,
                        'class3' => $class3,
                        'class3_1' => $class3_1,
                        'class3_2' => $class3_2,
                        'class3_3' => $class3_3,
                        'class4' => $class4,
                        'class5' => $class5,
                        'class5_1' => $class5_1,
                        'class6' => $class6,
                        'anion' => $anion,
                        'sulphate' => $sulphate,
                        'cation' => $cation,
                        'outgassing' => $outgassing,
                        'alkylacrylate' => $alkylacrylate,
                        'low_MW' => $low_MW,
                        'high_MW' => $high_MW,
                        'ftir' => $ftir,
                        'date_start' => $request->input('date_start'),
                        'date_end' => '2060-12-31',
                        'note' => $request->input('textnote'),
                        'save_date' => date('Y-m-d H:i:s'),
                        'chkstatus' => 1
                    ];
                    $idspec = $request->input('chkidspec' . $item->text);
                    DB::table('nt_model_history')->where('id_model_history', $idspec)->update($modelspec);

                    $ckhistorydate = DB::table('nt_model_history')->where('id_model', $request->input('modelid'))->orderby('date_end','desc')->first();
                    $modelspec1 = [
                        'alpc' => $alpc,
                        'class1' => $class1,
                        'class2' => $class2,
                        'class3' => $class3,
                        'class3_1' => $class3_1,
                        'class3_2' => $class3_2,
                        'class3_3' => $class3_3,
                        'class4' => $class4,
                        'class5' => $class5,
                        'class5_1' => $class5_1,
                        'class6' => $class6,
                        'anion' => $anion,
                        'sulphate' => $sulphate,
                        'cation' => $cation,
                        'outgassing' => $outgassing,
                        'alkylacrylate' => $alkylacrylate,
                        'low_MW' => $low_MW,
                        'high_MW' => $high_MW,
                        'ftir' => $ftir,
                    ];   
                   
                    $modelid = $request->input('modelid');
                    $typeID = $request->input('typeID' . $item->text);
                    DB::table('nt_model_spec')->where('id_model', $modelid)->where('id_model_type', $typeID)->update($modelspec1);
                    
            }
            else
            {
                $modelspec = [
                    'alpc' => $alpc,
                    'class1' => $class1,
                    'class2' => $class2,
                    'class3' => $class3,
                    'class3_1' => $class3_1,
                    'class3_2' => $class3_2,
                    'class3_3' => $class3_3,
                    'class4' => $class4,
                    'class5' => $class5,
                    'class5_1' => $class5_1,
                    'class6' => $class6,
                    'anion' => $anion,
                    'sulphate' => $sulphate,
                    'cation' => $cation,
                    'outgassing' => $outgassing,
                    'alkylacrylate' => $alkylacrylate,
                    'low_MW' => $low_MW,
                    'high_MW' => $high_MW,
                    'ftir' => $ftir,
                    'date_start' => $request->input('date_start'),
                    'date_end' => $request->input('date_end'),
                    'note' => $request->input('textnote'),
                    'save_date' => date('Y-m-d H:i:s'),
                    'chkstatus' => 0,
                ];
               
                $idspec = $request->input('chkidspec' . $item->text);
                DB::table('nt_model_history')->where('id_model_history', $idspec)->update($modelspec);
                
            }

           }
        }
        return response()->json(['success' => 1,'id' => $request->input('modelid')]);
    }
    
    public function add_model(Request $request)
    {
       
        $maxids = DB::table('nt_model')->max('id_model');
        $maxid = $maxids+1;
        $dateid = date('YmdHis');
        $namefolder = "M";
        $namemodel = $request->input('txt_name_model');
        $model_master = [
            'name_model' => $request->input('txt_name_model'),
            'part' => $request->input('txt_part'),
            'drive_name' => $request->input('txt_drive_name'),
            'model_type' => $request->input('model_type'),
            'line_type' => $request->input('txt_line_type'),
            'alpc_dc' => $request->input('txt_alpc_dc'),
            'alpc_dr' => $request->input('txt_alpc_dr'),
            'alpc_cs' => $request->input('txt_alpc_cs'),
            'sem_dc' => $request->input('txt_sem_dc'),
            'sem_dr' => $request->input('txt_sem_dr'),
            'sem_cs' => $request->input('txt_sem_cs'),
            'ic_dc' => $request->input('txt_ic_dc'),
            'ic_dr' => $request->input('txt_ic_dr'),
            'ic_cs' => $request->input('txt_ic_cs'),
            'gcms_dc' => $request->input('txt_gcms_dc'),
            'gcms_dr' => $request->input('txt_gcms_dr'),
            'gcms_cs' => $request->input('txt_gcms_cs'),
            'ftir_dc' => $request->input('txt_ftir_dc'),
            'ftir_dr' => $request->input('txt_ftir_dr'),
            'ftir_cs' => $request->input('txt_ftir_cs'),
            'vendor_id' => $request->input('txt_vendor_id'),
            'sub_supplier_name' => $request->input('txt_ssn'),
            'part_number' => $request->input('txt_part_number'),
            'part_revision' => $request->input('txt_part_revision'),
            'drawing_number' => $request->input('txt_drawing_number'),
            'drawing_revision' => $request->input('txt_drawing_revision'),  
            'digit_lot_number' => $request->input('txt_dln'),
            'surface_area' => $request->input('txt_sa'),
            'ic_parts_used' => $request->input('txt_ic_pcs'),
            'ftir_parts_used' => $request->input('txt_ftir_pcs'),
            'alpc_parts_used' => $request->input('txt_alpc_pcs'),
            'sem_parts_used' => $request->input('txt_sem_pcs'),
            'gcms_parts_used' => $request->input('txt_gcms_pcs'),
            'alpc_extraction_vol' => $request->input('txt_alpc_ev'),
            'alpc_water_vol' => $request->input('txt_alpc_wv'),
            'alpc_us_power' => $request->input('txt_alpc_us'),
            'lot_digit' => $request->input('txt_lot_digit_q'),
            'alpc_hang' => $request->input('txt_alpc_hang'),
            'alpc_us_frequency' => $request->input('txt_alpc_usf'),
            'alpc_extraction_time' => $request->input('txt_alpc_et'),
            'sem_filtered_solution' => $request->input('txt_sem_fsv'),
            'sem_filter_spot_area' => $request->input('txt_sem_filter'),
            'ic_extraction_vo' => $request->input('txt_ic_ev'),
            'gcms_sampling_time' => $request->input('txt_gcms_st'),
            'alpc_sum' => $request->input('txt_alpc_sum'),
            'sem_sum' => $request->input('txt_sem_sum'),
            'ic_sum' => $request->input('txt_ic_sum'),
            'gcms_sum' => $request->input('txt_gcms_sum'),
            'ftir_sum' => $request->input('txt_ftir_sum'),   
            'file_folder' =>   $namefolder, 

            'surface_area_unit' => $request->input('surface_area_unit'),
            'alpc_water_vol_unit' => $request->input('alpc_water_vol_unit'),
            'alpc_extraction_vol_unit' => $request->input('alpc_extraction_vol_unit'),
            'alpc_us_power_unit' => $request->input('alpc_us_power_unit'),
            'alpc_parts_used_unit' => $request->input('alpc_parts_used_unit'),
            'alpc_us_frequency_unit' => $request->input('alpc_us_frequency_unit'),
            'alpc_extraction_time_unit' => $request->input('alpc_extraction_time_unit'),
            'sem_filtered_solution_unit' => $request->input('sem_filtered_solution_unit'),
            'gcms_parts_used_unit' => $request->input('gcms_parts_used_unit'),
            'sem_filter_spot_area_unit' => $request->input('sem_filter_spot_area_unit'),
            'gcms_sampling_time_unit' => $request->input('gcms_sampling_time_unit'),
            'sem_parts_used_unit' => $request->input('sem_parts_used_unit'),
            'ic_parts_used_unit' => $request->input('ic_parts_used_unit'),
            'ftir_parts_used_unit' => $request->input('ftir_parts_used_unit'),
            'ic_extraction_vo_unit' => $request->input('ic_extraction_vo_unit'),

            //----- NHK2 27/02/24 ---------------    
            'lpc_mdl' => $request->input('txt_lpc_mdl'),  
            'lpc_mdl_unit' => $request->input('lpc_mdl_unit'), 
            'ic_mdl' => $request->input('txt_ic_mdl'),  
            'ic_mdl_unit' => $request->input('ic_mdl_unit'),  
            'ftir_mdl' => $request->input('txt_ftir_mdl'),  
            'ftir_mdl_unit' => $request->input('ftir_mdl_unit'),  
            'sem_mdl' => $request->input('txt_sem_mdl'),  
            'sem_mdl_unit' => $request->input('sem_mdl_unit'),  
            'gcms_mdl' => $request->input('txt_gcms_mdl'),  
            'gcms_mdl_unit' => $request->input('gcms_mdl_unit'),  
            //----- NHK2 27/02/24 ---------------

            'save_date'=> date('Y-m-d H:i:s'),
            'save_user'=> 1,
            'status_model'=> 1,
        ];
        
        $getidmodel = DB::table('nt_model')->insertGetId($model_master, 'id_model');
        
        $path = storage_path('NHK-File/model-rowfile/'.$namefolder);
        File::makeDirectory($path, $mode = 0777, true, true);
        $modeltype = DB::table('nt_model_spec_type')->get();
        
        foreach ($modeltype as $item) {

            if(!empty($request->input('alpc' . $item->text)))
            { 
                $alpc = $request->input('alpc' . $item->text);
            }
            else
            {
                $alpc= "N/A";
            }
            if(!empty($request->input('class1' . $item->text)))
            { 
                $class1 = $request->input('class1' . $item->text);
            }
            else
            {
                $class1= "N/A";
            }       
            if(!empty($request->input('class2' . $item->text)))
            { 
                $class2 = $request->input('class2' . $item->text);
            }
            else
            {
                $class2= "N/A";
            }
            if(!empty($request->input('class3' . $item->text)))
            { 
                $class3 = $request->input('class3' . $item->text);
            }
            else
            {
                $class3= "N/A";
            }       
            if(!empty($request->input('class3_1' . $item->text)))
            { 
                $class3_1 = $request->input('class3_1' . $item->text);
            }
            else
            {
                $class3_1= "N/A";
            }          
            if(!empty($request->input('class3_2' . $item->text)))
            { 
                $class3_2 = $request->input('class3_2' . $item->text);
            }
            else
            {
                $class3_2= "N/A";
            }
            if(!empty($request->input('class3_3' . $item->text)))
            { 
                $class3_3 = $request->input('class3_3' . $item->text);
            }
            else
            {
                $class3_3= "N/A";
            }
            if(!empty($request->input('class4' . $item->text)))
            { 
                $class4 = $request->input('class4' . $item->text);
            }
            else
            {
                $class4= "N/A";
            }
            if(!empty($request->input('class5' . $item->text)))
            { 
                $class5 = $request->input('class5' . $item->text);
            }
            else
            {
                $class5= "N/A";
            }
            if(!empty($request->input('class5_1' . $item->text)))
            { 
                $class5_1 = $request->input('class5_1' . $item->text);
            }
            else
            {
                $class5_1= "N/A";
            }
            if(!empty($request->input('class6' . $item->text)))
            { 
                $class6 = $request->input('class6' . $item->text);
            }
            else
            {
                $class6= "N/A";
            }
            if(!empty($request->input('anion' . $item->text)))
            { 
                $anion = $request->input('anion' . $item->text);
            }
            else
            {
                $anion= "N/A";
            }
            if(!empty($request->input('sulphate' . $item->text)))
            { 
                $sulphate = $request->input('sulphate' . $item->text);
            }
            else
            {
                $sulphate= "N/A";
            }
            if(!empty($request->input('cation' . $item->text)))
            { 
                $cation = $request->input('cation' . $item->text);
            }
            else
            {
                $cation= "N/A";
            }
            if(!empty($request->input('outgassing' . $item->text)))
            { 
                $outgassing = $request->input('outgassing' . $item->text);
            }
            else
            {
                $outgassing = "N/A";
            }
            if(!empty($request->input('alkylacrylate' . $item->text)))
            { 
                $alkylacrylate = $request->input('alkylacrylate' . $item->text);
            }
            else
            {
                $alkylacrylate= "N/A";
            }
            if(!empty($request->input('low_MW' . $item->text)))
            { 
                $low_MW = $request->input('low_MW' . $item->text);
            }
            else
            {
                $low_MW= "N/A";
            }
            if(!empty($request->input('high_MW' . $item->text)))
            { 
                $high_MW = $request->input('high_MW' . $item->text);
            }
            else
            {
                $high_MW= "N/A";
            }
            if(!empty($request->input('ftir' . $item->text)))
            { 
                $ftir = $request->input('ftir' . $item->text);
            }
            else
            {
                $ftir= "N/A";
            }



            if($item->id_model_type == 1)
            {
                $model_type_excel = $request->input('alpc' . $item->text);
                $model_outgassing = $request->input('outgassing' . $item->text);
            }
            if($item->id_model_type == 3)
            {
                $model_type_excel3 = $request->input('alpc' . $item->text);
                $upper_control_limit_class  = [
                    'id_model' => $getidmodel,
                    'id_model_type' => $request->input('typeID' . $item->text),
                    'alpc' => $alpc,
                    'class1' => $class1,
                    'class2' => $class2,
                    'class3' => $class3,
                    'class3_1' => $class3_1,
                    'class3_2' => $class3_2,
                    'class3_3' => $class3_3,
                    'class4' => $class4,
                    'class5' => $class5,
                    'class5_1' => $class5_1,
                    'class6' => $class6,
                    'anion' => $anion,
                    'sulphate' => $sulphate,
                    'cation' => $cation,
                    'outgassing' => $outgassing,
                    'alkylacrylate' => $alkylacrylate,
                    'low_MW' => $low_MW,
                    'high_MW' => $high_MW,
                    'ftir' => $ftir,
                ];
            }

            if($item->id_model_type == 2)
            {
                $model_type_excel2 = $request->input('alpc' . $item->text);
                $trigger_limit_class  = [
                    'id_model' => $getidmodel,
                    'id_model_type' => $request->input('typeID' . $item->text),
                    'alpc' => $alpc,
                    'class1' => $class1,
                    'class2' => $class2,
                    'class3' => $class3,
                    'class3_1' => $class3_1,
                    'class3_2' => $class3_2,
                    'class3_3' => $class3_3,
                    'class4' => $class4,
                    'class5' => $class5,
                    'class5_1' => $class5_1,
                    'class6' => $class6,
                    'anion' => $anion,
                    'sulphate' => $sulphate,
                    'cation' => $cation,
                    'outgassing' => $outgassing,
                    'alkylacrylate' => $alkylacrylate,
                    'low_MW' => $low_MW,
                    'high_MW' => $high_MW,
                    'ftir' => $ftir,
                ];
            }
            $modelspec = [
                'id_model' => $getidmodel,
                'id_model_type' => $request->input('typeID' . $item->text),
                'alpc' => $alpc,
                'class1' => $class1,
                'class2' => $class2,
                'class3' => $class3,
                'class3_1' => $class3_1,
                'class3_2' => $class3_2,
                'class3_3' => $class3_3,
                'class4' => $class4,
                'class5' => $class5,
                'class5_1' => $class5_1,
                'class6' => $class6,
                'anion' => $anion,
                'sulphate' => $sulphate,
                'cation' => $cation,
                'outgassing' => $outgassing,
                'alkylacrylate' => $alkylacrylate,
                'low_MW' => $low_MW,
                'high_MW' => $high_MW,
                'ftir' => $ftir,
            ];
            DB::table('nt_model_spec')->insert($modelspec);
        }
        $parameter_type = DB::table('nt_model_parameter_type')->get();
        foreach ($parameter_type as $st) {
        $file_lpc = null;
        $file_sem = null;
        $file_ic = null;
        $file_gcms = null;
        $file_ftir = null;
        $file_semtest = null;
            if ($request->input('ck_lpc' . $st->text) == "1") {
                $input_lpc = 1;
                if($st->id_model_p == 1) {
                    $file_lpc = "LPC-" . $dateid . "-" . $namemodel.".xlsm";
                    $file_lpc_sc = "LPC-" . $dateid . "-" . $namemodel;
                    $file = storage_path('NHK-File/template/model-formet/LPC-format.xlsm');
                    Excel::load($file, function ($doc)use($model_master,$model_type_excel,$model_type_excel2,$model_type_excel3) {
                        $sheet = $doc->setActiveSheetIndex(0);
                        $sheet->setCellValue('C3',$model_master['name_model']);
                        $sheet->setCellValue('C4',$model_master['part']);
                        $sheet->setCellValue('C9',$model_master['surface_area']);
                        $sheet->setCellValue('C10',$model_master['alpc_extraction_vol']);
                        $sheet->setCellValue('C11',$model_master['alpc_parts_used']);
                        $sheet->setCellValue('L9',$model_master['alpc_water_vol']);
                        $sheet->setCellValue('N9',$model_master['alpc_water_vol_unit']);
                        $sheet->setCellValue('L10',$model_master['alpc_hang']);
                        $sheet->setCellValue('R9',$model_master['alpc_us_frequency']);
                        $sheet->setCellValue('R10',$model_master['alpc_us_power']);
                        $sheet->setCellValue('R11',$model_master['alpc_extraction_time']);
                        $sheet->setCellValue('R15',$model_type_excel);
                        $sheet->setCellValue('R16',$model_type_excel2);
                        $sheet->setCellValue('R17',$model_type_excel3);
                        $sheet->setCellValue('R25',$model_master['drive_name']);
                        $sheet->setCellValue('R26',$model_master['part_number']);
                        $sheet->setCellValue('R27',$model_master['digit_lot_number']);
                        $sheet->setCellValue('R28',$model_master['lot_digit']);
                        $sheet->setCellValue('R30',$model_master['part_revision']);
                         
                    })->setFilename($file_lpc_sc)->store('xlsm', storage_path('NHK-File/model-rowfile/' . $namefolder));

                }
            } else {
                $input_lpc = 0;
            }
            if ($request->input('ck_sem' . $st->text) == 1) {
                $input_sem = 1;
                if($st->id_model_p == 1) {
                   $file_sem = "SEM-" . $dateid . "-" . $namemodel.".xlsx";
                   $file_sem_sc = "SEM-" . $dateid . "-" . $namemodel;
                   $file = storage_path('NHK-File/template/model-formet/SEM-format.xlsx');
                    Excel::load($file, function ($doc)use($model_master, $upper_control_limit_class,$trigger_limit_class) {
                        $sheet = $doc->setActiveSheetIndex(2);
                        $sheet->setCellValue('C4',$model_master['name_model']);
                        $sheet->setCellValue('C5',$model_master['part']);
                        $sheet->setCellValue('C10',$model_master['alpc_extraction_vol']);
                        $sheet->setCellValue('D10',$model_master['alpc_extraction_vol_unit']);
                        $sheet->setCellValue('C11',$model_master['sem_filtered_solution']);
                        $sheet->setCellValue('D11',$model_master['sem_filtered_solution_unit']);
                        $sheet->setCellValue('C12',$model_master['sem_filter_spot_area']);
                        $sheet->setCellValue('D12',$model_master['sem_filter_spot_area_unit']);
                        $sheet->setCellValue('C14',$model_master['sem_parts_used']);
                        $sheet->setCellValue('D14',$model_master['sem_parts_used_unit']);
                        $sheet->setCellValue('C15',$model_master['surface_area']);
                        $sheet->setCellValue('D15',$model_master['surface_area_unit']);
                        $sheet->setCellValue('S5',$model_master['part_number']);
                        $sheet->setCellValue('S6',$model_master['digit_lot_number']);
                        $sheet->setCellValue('S7',$model_master['lot_digit']);          
                        $sheet->setCellValue('S21',$upper_control_limit_class['class1']);
                        $sheet->setCellValue('S22',$upper_control_limit_class['class2']);
                        $sheet->setCellValue('S23',$upper_control_limit_class['class3']);
                        $sheet->setCellValue('S24',$upper_control_limit_class['class3_1']);
                        $sheet->setCellValue('S25',$upper_control_limit_class['class3_2']);
                        $sheet->setCellValue('S26',$upper_control_limit_class['class3_3']);
                        $sheet->setCellValue('S27',$upper_control_limit_class['class4']);
                        $sheet->setCellValue('S28',$upper_control_limit_class['class5']);
                        $sheet->setCellValue('S29',$upper_control_limit_class['class5_1']);
                        $sheet->setCellValue('S30',$upper_control_limit_class['class6']);
                        $sheet->setCellValue('S31',$trigger_limit_class['class1']);
                        $sheet->setCellValue('S32',$trigger_limit_class['class2']);
                        $sheet->setCellValue('S33',$trigger_limit_class['class3']);
                        $sheet->setCellValue('S34',$trigger_limit_class['class3_1']);
                        $sheet->setCellValue('S35',$trigger_limit_class['class3_2']);
                        $sheet->setCellValue('S36',$trigger_limit_class['class3_3']);
                        $sheet->setCellValue('S37',$trigger_limit_class['class4']);
                        $sheet->setCellValue('S38',$trigger_limit_class['class5']);
                        $sheet->setCellValue('S39',$trigger_limit_class['class5_1']);
                        $sheet->setCellValue('S40',$trigger_limit_class['class6']);
                    })->setFilename($file_sem_sc)->store('xlsx', storage_path('NHK-File/model-rowfile/' . $namefolder));
                }
            } else {
                $input_sem = 0;
            }
            if ($request->input('ck_ic' . $st->text) == 1) {
                $input_ic = 1;
                if($st->id_model_p == 1) {
                    $file_ic = "IC-" . $dateid . "-" . $namemodel.".xlsx";
                    $file_ic_sc = "IC-" . $dateid . "-" . $namemodel;
                    $file = storage_path('NHK-File/template/model-formet/IC-format.xlsx');
                    Excel::load($file, function ($doc)use($model_master) {
                        $sheet = $doc->setActiveSheetIndex(0);
                        $sheet->setCellValue('C4',$model_master['name_model']);
                        $sheet->setCellValue('C5',$model_master['part']);
                        $sheet->setCellValue('E9',$model_master['ic_parts_used_unit']);
                        $sheet->setCellValue('D9',$model_master['ic_parts_used']);
                        $sheet->setCellValue('D10',$model_master['ic_extraction_vo']);
                        $sheet->setCellValue('D11',$model_master['surface_area']);
                        $sheet->setCellValue('V3',$model_master['part_number']);
                        $sheet->setCellValue('V4',$model_master['digit_lot_number']);
                        $sheet->setCellValue('V5',$model_master['lot_digit']);
                    })->setFilename($file_ic_sc)->store('xlsx', storage_path('NHK-File/model-rowfile/'.$namefolder.'/'));
                }
            } else {
                $input_ic = 0;
            }
            if ($request->input('ck_gcms' . $st->text) == 1) {
                $input_gcms = 1;
                if($st->id_model_p == 1) {
                    $file_gcms = "GCMS-" . $dateid . "-" . $namemodel.".xlsx";
                    $file_gcms_sc = "GCMS-" . $dateid . "-" . $namemodel;
                    $file = storage_path('NHK-File/template/model-formet/GCMS-format.xlsx');
                    Excel::load($file, function ($doc)use($model_master,$model_outgassing) {
                        $sheet = $doc->setActiveSheetIndex(3);
                        $sheet->setCellValue('B4',$model_master['gcms_parts_used']);
                        $sheet->setCellValue('B5',$model_master['gcms_sampling_time']);
                        $sheet->setCellValue('B7',$model_master['name_model']);
                        $sheet->setCellValue('B8',$model_master['part']);
                        $sheet->setCellValue('I2',$model_master['part_number']);
                        $sheet->setCellValue('I3',$model_master['digit_lot_number']);
                        $sheet->setCellValue('I4',$model_master['lot_digit']);
                        $sheet->setCellValue('I10',$model_master['drive_name']);
                        $sheet->setCellValue('I11',$model_master['part_revision']);
                        $sheet->setCellValue('C75',$model_outgassing);
                    })->setFilename($file_gcms_sc)->store('xlsx', storage_path('NHK-File/model-rowfile/'.$namefolder.'/'));
                }
            } else {
                $input_gcms = 0;
            }
            if ($request->input('ck_ftir' . $st->text) == 1) {
                $input_ftir = 1;
                if($st->id_model_p == 1) {
                    $file_ftir = "FTIR-" . $dateid . "-" . $namemodel.".xlsx";
                    $file_ftir_sc = "FTIR-" . $dateid . "-" . $namemodel;
                    $file = storage_path('NHK-File/template/model-formet/FTIR-format.xlsx');
                    Excel::load($file, function ($doc)use($model_master) {
                         $sheet = $doc->setActiveSheetIndex(0);
                         $sheet->setCellValue('B3',$model_master['name_model']);
                         $sheet->setCellValue('B5',$model_master['part']);
                         $sheet->setCellValue('B6',$model_master['digit_lot_number']);
                         $sheet->setCellValue('B7',$model_master['surface_area']);
                         $sheet->setCellValue('B8',$model_master['ftir_parts_used']);
                         $sheet->setCellValue('B9',$model_master['lot_digit']);
                    })->setFilename($file_ftir_sc)->store('xlsx', storage_path('NHK-File/model-rowfile/' . $namefolder.'/'));
                }
            } else {
                $input_ftir = 0;
            }
            if ($request->input('ck_semtest' . $st->text) == 1) {
                $input_semtest = 1;
                if($st->id_model_p == 1) {
                    $file_semtest = "GhostTest-" . $dateid . "-" . $namemodel.".xlsx";
                    $file_semtest_sc = "GhostTest-" . $dateid . "-" . $namemodel;
                    $file = storage_path('NHK-File/template/model-formet/GhostTest-format.xlsx');
                    Excel::load($file, function ($doc)use($model_master) {
                         $sheet = $doc->setActiveSheetIndex(0);
                         $sheet->setCellValue('C3',$model_master['name_model']);
                         $sheet->setCellValue('C4',$model_master['part']);
                         $sheet->setCellValue('J14',$model_master['part_number']);
                         $sheet->setCellValue('J15',$model_master['digit_lot_number']);
                         $sheet->setCellValue('J16',$model_master['lot_digit']);
                    })->setFilename($file_semtest_sc)->store('xlsx', storage_path('NHK-File/model-rowfile/' . $namefolder.'/'));
                }
            } else {
                $input_semtest = 0;
            }


            $addparameter_type = [
                'id_model' => $getidmodel,
                'id_model_p' => $st->id_model_p,
                'name' => $st->name,
                'lpc' => $input_lpc,
                'lpc_file' => $file_lpc,
                'sem' => $input_sem,
                'sem_file' => $file_sem,
                'ic' => $input_ic,
                'ic_file' => $file_ic,
                'gcms' => $input_gcms,
                'gcms_file' => $file_gcms,
                'ftir' => $input_ftir,
                'ftir_file' => $file_ftir,
                'semtest' => $input_semtest,
                'semtest_file' => $file_semtest,
            ];
            DB::table('nt_model_parameter')->insert($addparameter_type);
        }
        return response()->json(['success' => 1]);
    }
    public function editmodelSubmit(Request $request)
    {
        ini_set('memory_limit', '-1');
        set_time_limit(5000); 

        //Storage::delete('NHK-File/model-rowfile/test/xxx.txt');
        $maxid = $request->input('chkid_model');
        $dateid = date('YmdHis');
        $model_tv = DB::table('nt_model')->where('id_model', $request->input('chkid_model'))->first();
        $namemodel = $request->input('txt_name_model');
        $model_master = [
            'name_model' => $request->input('txt_name_model'),
            'part' => $request->input('txt_part'),
            'drive_name' => $request->input('txt_drive_name'),
            'line_type' => $request->input('txt_line_type'),
            'model_type' => $request->input('model_type'),
            'alpc_dc' => $request->input('txt_alpc_dc'),
            'alpc_dr' => $request->input('txt_alpc_dr'),
            'alpc_cs' => $request->input('txt_alpc_cs'),
            'sem_dc' => $request->input('txt_sem_dc'),
            'sem_dr' => $request->input('txt_sem_dr'),
            'sem_cs' => $request->input('txt_sem_cs'),
            'ic_dc' => $request->input('txt_ic_dc'),
            'ic_dr' => $request->input('txt_ic_dr'),
            'ic_cs' => $request->input('txt_ic_cs'),
            'gcms_dc' => $request->input('txt_gcms_dc'),
            'gcms_dr' => $request->input('txt_gcms_dr'),
            'gcms_cs' => $request->input('txt_gcms_cs'),
            'ftir_dc' => $request->input('txt_ftir_dc'),
            'ftir_dr' => $request->input('txt_ftir_dr'),
            'ftir_cs' => $request->input('txt_ftir_cs'),
            'vendor_id' => $request->input('txt_vendor_id'),
            'sub_supplier_name' => $request->input('txt_ssn'),
            'part_number' => $request->input('txt_part_number'),
            'part_revision' => $request->input('txt_part_revision'),
            'drawing_number' => $request->input('txt_drawing_number'),
            'drawing_revision' => $request->input('txt_drawing_revision'),
            'digit_lot_number' => $request->input('txt_dln'),
            'digit_lot_number1' => $request->input('txt_dln1'),
            'digit_lot_number2' => $request->input('txt_dln2'),
            'surface_area' => $request->input('txt_sa'),
            'ic_parts_used' => $request->input('txt_ic_pcs'),
            'ftir_parts_used' => $request->input('txt_ftir_pcs'),
            'alpc_parts_used' => $request->input('txt_alpc_pcs'),
            'sem_parts_used' => $request->input('txt_sem_pcs'),
            'gcms_parts_used' => $request->input('txt_gcms_pcs'),
            'alpc_extraction_vol' => $request->input('txt_alpc_ev'),
            'alpc_water_vol' => $request->input('txt_alpc_wv'),
            'alpc_us_power' => $request->input('txt_alpc_us'),
            'lot_digit' => $request->input('txt_lot_digit_q'),
            'alpc_hang' => $request->input('txt_alpc_hang'),
            'alpc_us_frequency' => $request->input('txt_alpc_usf'),
            'alpc_extraction_time' => $request->input('txt_alpc_et'),
            'sem_filtered_solution' => $request->input('txt_sem_fsv'),
            'sem_filter_spot_area' => $request->input('txt_sem_filter'),
            'ic_extraction_vo' => $request->input('txt_ic_ev'),
            'gcms_sampling_time' => $request->input('txt_gcms_st'),
            'alpc_sum' => $request->input('txt_alpc_sum'),
            'sem_sum' => $request->input('txt_sem_sum'),
            'ic_sum' => $request->input('txt_ic_sum'),
            'gcms_sum' => $request->input('txt_gcms_sum'),
            'ftir_sum' => $request->input('txt_ftir_sum'),   
            'surface_area_unit' => $request->input('surface_area_unit'),
            'alpc_water_vol_unit' => $request->input('alpc_water_vol_unit'),
            'alpc_extraction_vol_unit' => $request->input('alpc_extraction_vol_unit'),
            'alpc_us_power_unit' => $request->input('alpc_us_power_unit'),
            'alpc_parts_used_unit' => $request->input('alpc_parts_used_unit'),
            'alpc_us_frequency_unit' => $request->input('alpc_us_frequency_unit'),
            'alpc_extraction_time_unit' => $request->input('alpc_extraction_time_unit'),
            'sem_filtered_solution_unit' => $request->input('sem_filtered_solution_unit'),
            'gcms_parts_used_unit' => $request->input('gcms_parts_used_unit'),
            'sem_filter_spot_area_unit' => $request->input('sem_filter_spot_area_unit'),
            'gcms_sampling_time_unit' => $request->input('gcms_sampling_time_unit'),
            'sem_parts_used_unit' => $request->input('sem_parts_used_unit'),
            'ic_parts_used_unit' => $request->input('ic_parts_used_unit'),
            'ftir_parts_used_unit' => $request->input('ftir_parts_used_unit'),
            'ic_extraction_vo_unit' => $request->input('ic_extraction_vo_unit'),   
            
            //----- NHK2 27/02/24 ---------------     
            'lpc_mdl' => $request->input('txt_lpc_mdl'),  
            'lpc_mdl_unit' => $request->input('lpc_mdl_unit'), 
            'ic_mdl' => $request->input('txt_ic_mdl'),  
            'ic_mdl_unit' => $request->input('ic_mdl_unit'),  
            'ftir_mdl' => $request->input('txt_ftir_mdl'),  
            'ftir_mdl_unit' => $request->input('ftir_mdl_unit'),  
            'sem_mdl' => $request->input('txt_sem_mdl'),  
            'sem_mdl_unit' => $request->input('sem_mdl_unit'),  
            'gcms_mdl' => $request->input('txt_gcms_mdl'),  
            'gcms_mdl_unit' => $request->input('gcms_mdl_unit'),  
            //----- NHK2 27/02/24 --------------- 

            'save_date'=> date('Y-m-d H:i:s'),
            'save_user'=> 1,
        ];
        DB::table('nt_model')->where('id_model', $request->input('chkid_model'))->update($model_master);
        $model_first = DB::table('nt_model')->where('id_model', $request->input('chkid_model'))->first();
        $modeltype = DB::table('nt_model_spec_type')->get();
        foreach ($modeltype as $item) {
           
            if(!empty($request->input('alpc' . $item->text)))
            { 
                $alpc = $request->input('alpc' . $item->text);
            }
            else
            {
                $alpc= "N/A";
            }
            if(!empty($request->input('class1' . $item->text)))
            { 
                $class1 = $request->input('class1' . $item->text);
            }
            else
            {
                $class1= "N/A";
            }       
            if(!empty($request->input('class2' . $item->text)))
            { 
                $class2 = $request->input('class2' . $item->text);
            }
            else
            {
                $class2= "N/A";
            }
            if(!empty($request->input('class3' . $item->text)))
            { 
                $class3 = $request->input('class3' . $item->text);
            }
            else
            {
                $class3= "N/A";
            }       
            if(!empty($request->input('class3_1' . $item->text)))
            { 
                $class3_1 = $request->input('class3_1' . $item->text);
            }
            else
            {
                $class3_1= "N/A";
            }          
            if(!empty($request->input('class3_2' . $item->text)))
            { 
                $class3_2 = $request->input('class3_2' . $item->text);
            }
            else
            {
                $class3_2= "N/A";
            }
            if(!empty($request->input('class3_3' . $item->text)))
            { 
                $class3_3 = $request->input('class3_3' . $item->text);
            }
            else
            {
                $class3_3= "N/A";
            }
            if(!empty($request->input('class4' . $item->text)))
            { 
                $class4 = $request->input('class4' . $item->text);
            }
            else
            {
                $class4= "N/A";
            }
            if(!empty($request->input('class5' . $item->text)))
            { 
                $class5 = $request->input('class5' . $item->text);
            }
            else
            {
                $class5= "N/A";
            }
            if(!empty($request->input('class5_1' . $item->text)))
            { 
                $class5_1 = $request->input('class5_1' . $item->text);
            }
            else
            {
                $class5_1= "N/A";
            }
            if(!empty($request->input('class6' . $item->text)))
            { 
                $class6 = $request->input('class6' . $item->text);
            }
            else
            {
                $class6= "N/A";
            }
            if(!empty($request->input('anion' . $item->text)))
            { 
                $anion = $request->input('anion' . $item->text);
            }
            else
            {
                $anion= "N/A";
            }
            if(!empty($request->input('sulphate' . $item->text)))
            { 
                $sulphate = $request->input('sulphate' . $item->text);
            }
            else
            {
                $sulphate= "N/A";
            }
            if(!empty($request->input('cation' . $item->text)))
            { 
                $cation = $request->input('cation' . $item->text);
            }
            else
            {
                $cation= "N/A";
            }
            if(!empty($request->input('outgassing' . $item->text)))
            { 
                $outgassing = $request->input('outgassing' . $item->text);
            }
            else
            {
                $outgassing = "N/A";
            }
            if(!empty($request->input('alkylacrylate' . $item->text)))
            { 
                $alkylacrylate = $request->input('alkylacrylate' . $item->text);
            }
            else
            {
                $alkylacrylate= "N/A";
            }
            if(!empty($request->input('low_MW' . $item->text)))
            { 
                $low_MW = $request->input('low_MW' . $item->text);
            }
            else
            {
                $low_MW= "N/A";
            }
            if(!empty($request->input('high_MW' . $item->text)))
            { 
                $high_MW = $request->input('high_MW' . $item->text);
            }
            else
            {
                $high_MW= "N/A";
            }
            if(!empty($request->input('ftir' . $item->text)))
            { 
                $ftir = $request->input('ftir' . $item->text);
            }
            else
            {
                $ftir= "N/A";
            }
            if($item->id_model_type == 1)
            {
                $model_type_excel = $request->input('alpc' . $item->text);
                $model_outgassing = $request->input('outgassing' . $item->text);
            }
            if($item->id_model_type == 2)
            {
                $model_type_excel2 = $request->input('alpc' . $item->text);
                $trigger_limit_class  = [
                    'id_model_type' => $request->input('typeID' . $item->text),
                    'alpc' => $alpc,
                    'class1' => $class1,
                    'class2' => $class2,
                    'class3' => $class3,
                    'class3_1' => $class3_1,
                    'class3_2' => $class3_2,
                    'class3_3' => $class3_3,
                    'class4' => $class4,
                    'class5' => $class5,
                    'class5_1' => $class5_1,
                    'class6' => $class6,
                    'anion' => $anion,
                    'sulphate' => $sulphate,
                    'cation' => $cation,
                    'outgassing' => $outgassing,
                    'alkylacrylate' => $alkylacrylate,
                    'low_MW' => $low_MW,
                    'high_MW' => $high_MW,
                    'ftir' => $ftir,
                ];
            }
            if($item->id_model_type == 3)
            {
                $model_type_excel3 = $request->input('alpc' . $item->text);
                $upper_control_limit_class  = [  
                    'id_model_type' => $request->input('typeID' . $item->text),
                    'alpc' => $alpc,
                    'class1' => $class1,
                    'class2' => $class2,
                    'class3' => $class3,
                    'class3_1' => $class3_1,
                    'class3_2' => $class3_2,
                    'class3_3' => $class3_3,
                    'class4' => $class4,
                    'class5' => $class5,
                    'class5_1' => $class5_1,
                    'class6' => $class6,
                    'anion' => $anion,
                    'sulphate' => $sulphate,
                    'cation' => $cation,
                    'outgassing' => $outgassing,
                    'alkylacrylate' => $alkylacrylate,
                    'low_MW' => $low_MW,
                    'high_MW' => $high_MW,
                    'ftir' => $ftir,
                ];
            }
            $modelspec = [
                'alpc' => $alpc,
                'class1' => $class1,
                'class2' => $class2,
                'class3' => $class3,
                'class3_1' => $class3_1,
                'class3_2' => $class3_2,
                'class3_3' => $class3_3,
                'class4' => $class4,
                'class5' => $class5,
                'class5_1' => $class5_1,
                'class6' => $class6,
                'anion' => $anion,
                'sulphate' => $sulphate,
                'cation' => $cation,
                'outgassing' => $outgassing,
                'alkylacrylate' => $alkylacrylate,
                'low_MW' => $low_MW,
                'high_MW' => $high_MW,
                'ftir' => $ftir,
            ];      
            $idspec = $request->input('chkidspec' . $item->text);
            DB::table('nt_model_spec')->where('id_model_spec', $idspec)->update($modelspec);
        }  
        
        $parameter_type = DB::table('nt_model_parameter_type')->get();
        foreach ($parameter_type as $st) {
            
            $modeltype = DB::table('nt_model_parameter')
            ->where('id_model',$request->input('chkid_model'))
            ->where('id_model_p',$st->id_model_p)
            ->first();
            // dd($modeltype); 
            if ($request->input('ck_lpc' . $st->text) == "1") {
                if($modeltype->lpc == 0 )
                {
                    $filename_lpc = "LPC-" . $dateid . "-" . $namemodel.".xlsm";
                    $lpcname = "LPC-" . $dateid . "-" . $namemodel;
                }
                else
                {
                    File::Delete('local/storage/NHK-File/model-rowfile/'.$model_first->file_folder.'/'.$modeltype->lpc_file);
                        $filename_lpc = "LPC-" . $dateid . "-" . $namemodel.".xlsm";
                        $lpcname = "LPC-" . $dateid . "-" . $namemodel;
                }

                $input_lpc = 1;
                if($st->id_model_p == 1) {
                    $file = storage_path('NHK-File/template/model-formet/LPC-format.xlsm');
                    $spreadsheet = IOFactory::load($file);
                    $sheet = $spreadsheet->setActiveSheetIndex(0);
                    
                    $sheet->setCellValue('C3',$model_master['name_model']);
                    $sheet->setCellValue('C4',$model_master['part']);
                    $sheet->setCellValue('C9',$model_master['surface_area']);
                    $sheet->setCellValue('C10',$model_master['alpc_extraction_vol']);
                    $sheet->setCellValue('C11',$model_master['alpc_parts_used']);
                    $sheet->setCellValue('L9',$model_master['alpc_water_vol']);
                    $sheet->setCellValue('N9',$model_master['alpc_water_vol_unit']);
                    $sheet->setCellValue('L10',$model_master['alpc_hang']);
                    $sheet->setCellValue('R9',$model_master['alpc_us_frequency']);
                    $sheet->setCellValue('R10',$model_master['alpc_us_power']);
                    $sheet->setCellValue('R11',$model_master['alpc_extraction_time']);
                    $sheet->setCellValue('R15',$model_type_excel);
                    $sheet->setCellValue('R16',$model_type_excel2);
                    $sheet->setCellValue('R17',$model_type_excel3);
                    $sheet->setCellValue('R25',$model_master['drive_name']);
                    $sheet->setCellValue('R26',$model_master['part_number']);
                    $sheet->setCellValue('R27',$model_master['digit_lot_number']);
                    $sheet->setCellValue('U27',$model_master['digit_lot_number1']);
                    $sheet->setCellValue('V27',$model_master['digit_lot_number2']);
                    $sheet->setCellValue('R28',$model_master['lot_digit']);
                    $sheet->setCellValue('R30',$model_master['part_revision']);

                    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
                    $writer->setPreCalculateFormulas(false);
                    $writer->save(storage_path('NHK-File/model-rowfile/'.$model_first->file_folder.'/'.$lpcname.'.xlsm'));
 
               }
            } else {
                $input_lpc = 0;
                $filename_lpc = null;
                $myFile = 'local/storage/NHK-File/model-rowfile/'.$model_first->file_folder.'/'.$modeltype->lpc_file;
                File::delete($myFile);

            }

            if ($request->input('ck_sem' . $st->text) == 1) {

                if($modeltype->sem == 0 )
                {
                    $filename_sem = "SEM-" . $dateid . "-" . $namemodel.".xlsx";
                    $semname = "SEM-" . $dateid . "-" . $namemodel;
                }
                else
                {
                    File::Delete('local/storage/NHK-File/model-rowfile/'.$model_first->file_folder.'/'.$modeltype->sem_file);
                        $filename_sem = "SEM-" . $dateid . "-" . $namemodel.".xlsx";
                        $semname = "SEM-" . $dateid . "-" . $namemodel;
                }

                $sem_file_folder = $model_first->file_folder;
                $input_sem = 1;
                if($st->id_model_p == 1) {
                   $file = storage_path('NHK-File/template/model-formet/SEM-format.xlsx');
                   $spreadsheet = IOFactory::load($file);
                   $sheet = $spreadsheet->setActiveSheetIndex(2);

                    $sheet->setCellValue('C4',$model_master['name_model']);
                    $sheet->setCellValue('C5',$model_master['part']);
                    $sheet->setCellValue('C10',$model_master['alpc_extraction_vol']);
                    $sheet->setCellValue('D10',$model_master['alpc_extraction_vol_unit']);
                    $sheet->setCellValue('C11',$model_master['sem_filtered_solution']);
                    $sheet->setCellValue('D11',$model_master['sem_filtered_solution_unit']);
                    $sheet->setCellValue('C12',$model_master['sem_filter_spot_area']);
                    $sheet->setCellValue('D12',$model_master['sem_filter_spot_area_unit']);
                    $sheet->setCellValue('C14',$model_master['sem_parts_used']);
                    $sheet->setCellValue('D14',$model_master['sem_parts_used_unit']);
                    $sheet->setCellValue('C15',$model_master['surface_area']);
                    $sheet->setCellValue('D15',$model_master['surface_area_unit']);
                    $sheet->setCellValue('S5',$model_master['part_number']);
                    $sheet->setCellValue('S6',$model_master['digit_lot_number']);
                    $sheet->setCellValue('T6',$model_master['digit_lot_number1']);
                    $sheet->setCellValue('U6',$model_master['digit_lot_number2']);
                    $sheet->setCellValue('S7',$model_master['lot_digit']);
                                 
                    $sheet->setCellValue('S21',$upper_control_limit_class['class1']);
                    $sheet->setCellValue('S22',$upper_control_limit_class['class2']);
                    $sheet->setCellValue('S23',$upper_control_limit_class['class3']);
                    $sheet->setCellValue('S24',$upper_control_limit_class['class3_1']);
                    $sheet->setCellValue('S25',$upper_control_limit_class['class3_2']);
                    $sheet->setCellValue('S26',$upper_control_limit_class['class3_3']);
                    $sheet->setCellValue('S27',$upper_control_limit_class['class4']);
                    $sheet->setCellValue('S28',$upper_control_limit_class['class5']);
                    $sheet->setCellValue('S29',$upper_control_limit_class['class5_1']);
                    $sheet->setCellValue('S30',$upper_control_limit_class['class6']);

                    $sheet->setCellValue('S31',$trigger_limit_class['class1']);
                    $sheet->setCellValue('S32',$trigger_limit_class['class2']);
                    $sheet->setCellValue('S33',$trigger_limit_class['class3']);
                    $sheet->setCellValue('S34',$trigger_limit_class['class3_1']);
                    $sheet->setCellValue('S35',$trigger_limit_class['class3_2']);
                    $sheet->setCellValue('S36',$trigger_limit_class['class3_3']);
                    $sheet->setCellValue('S37',$trigger_limit_class['class4']);
                    $sheet->setCellValue('S38',$trigger_limit_class['class5']);
                    $sheet->setCellValue('S39',$trigger_limit_class['class5_1']);
                    $sheet->setCellValue('S40',$trigger_limit_class['class6']);

                    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx'); 
                    $writer->setPreCalculateFormulas(false);
                    $writer->save(storage_path('NHK-File/model-rowfile/'.$sem_file_folder.'/'.$semname.'.xlsx'));
 
                  
                    // $copy = \File::copy($file, 'local/storage/NHK-File/model-rowfile/'.$sem_file_folder.'/'.$semname.'.xlsx');
                    // ob_end_clean();
                    // $dateid2 = date('YmdHiss');
                    // $semname2 = "SEM-" . $dateid2 . "-" . $namemodel;
                    // $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
                    // $spreadsheet = $reader->load('local/storage/NHK-File/model-rowfile/'.$sem_file_folder.'/'.$semname.'.xlsx');
                    // $sheet = $spreadsheet->getActiveSheet(2);

                    // $sheet->setCellValue('C4',$model_master['name_model']);
               
                    // $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
                    // $writer->save('local/storage/NHK-File/model-rowfile/'.$sem_file_folder.'/'.$semname2.'.xlsx');

                }
            } else {
                $input_sem = 0;
                $filename_sem = null;
                $myFile = 'local/storage/NHK-File/model-rowfile/'.$model_first->file_folder.'/'.$modeltype->sem_file;
                File::delete($myFile);
            }

            if ($request->input('ck_ic' . $st->text) == 1) {      
                if($modeltype->ic == 0 )
                {
                    $filename_ic = "IC-" . $dateid . "-" . $namemodel.".xlsx";
                    $icname = "IC-" . $dateid . "-" . $namemodel;
                }
                else
                {
                    File::Delete('local/storage/NHK-File/model-rowfile/'.$model_first->file_folder.'/'.$modeltype->ic_file);
                        $filename_ic = "IC-" . $dateid . "-" . $namemodel.".xlsx";
                        $icname = "IC-" . $dateid . "-" . $namemodel;
                }
                $input_ic = 1;
                if($st->id_model_p == 1) {
                    $file = storage_path('NHK-File/template/model-formet/IC-format.xlsx');
                    $spreadsheet = IOFactory::load($file);
                    $sheet = $spreadsheet->setActiveSheetIndex(0);

                    $sheet->setCellValue('C4',$model_master['name_model']);
                    $sheet->setCellValue('C5',$model_master['part']);
                    $sheet->setCellValue('E9',$model_master['ic_parts_used_unit']);
                    $sheet->setCellValue('D9',$model_master['ic_parts_used']);
                    $sheet->setCellValue('D10',$model_master['ic_extraction_vo']);
                    $sheet->setCellValue('D11',$model_master['surface_area']);
                    $sheet->setCellValue('V3',$model_master['part_number']);
                    $sheet->setCellValue('V4',$model_master['digit_lot_number']);
                    $sheet->setCellValue('W4',$model_master['digit_lot_number1']);
                    $sheet->setCellValue('X4',$model_master['digit_lot_number2']);
                    $sheet->setCellValue('V5',$model_master['lot_digit']);

                    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx'); 
                    $writer->setPreCalculateFormulas(false);
                    $writer->save(storage_path('NHK-File/model-rowfile/'.$model_first->file_folder.'/'.$icname.'.xlsx'));
 
                }
            } else {
                $input_ic = 0;
                $filename_ic = null;
                $myFile = 'local/storage/NHK-File/model-rowfile/'.$model_first->file_folder.'/'.$modeltype->ic_file;
                File::delete($myFile);
            }
            if ($request->input('ck_gcms' . $st->text) == 1) {
                 if($modeltype->gcms == 0 )
                {
                    $filename_gcms = "GCMS-" . $dateid . "-" . $namemodel.".xlsx";
                    $gcmsname = "GCMS-" . $dateid . "-" . $namemodel;   
                }
                else
                {
                    File::Delete('local/storage/NHK-File/model-rowfile/'.$model_first->file_folder.'/'.$modeltype->gcms_file);
                        $filename_gcms = "GCMS-" . $dateid . "-" . $namemodel.".xlsx";
                        $gcmsname = "GCMS-" . $dateid . "-" . $namemodel;   
                }
               // dd($gcmsname);
                $input_gcms = 1;
                if($st->id_model_p == 1) {
                    $file = storage_path('NHK-File/template/model-formet/GCMS-format.xlsx');
                    $spreadsheet = IOFactory::load($file);
                    $sheet = $spreadsheet->setActiveSheetIndex(3);

                    $sheet->setCellValue('B4',$model_master['gcms_parts_used']);
                    $sheet->setCellValue('B5',$model_master['gcms_sampling_time']);
                    $sheet->setCellValue('B7',$model_master['name_model']);
                    $sheet->setCellValue('B8',$model_master['part']);
                    $sheet->setCellValue('I2',$model_master['part_number']);
                    $sheet->setCellValue('I3',$model_master['digit_lot_number']);
                    $sheet->setCellValue('L3',$model_master['digit_lot_number1']);
                    $sheet->setCellValue('M3',$model_master['digit_lot_number2']);
                    $sheet->setCellValue('I4',$model_master['lot_digit']);
                    $sheet->setCellValue('I10',$model_master['drive_name']);
                    // $sheet->setCellValue('V5',$model_master['lot_digit']);
                    $sheet->setCellValue('I11',$model_master['part_revision']);
                    $sheet->setCellValue('C75',$model_outgassing);

                    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx'); 
                    $writer->setPreCalculateFormulas(false);
                    $writer->save(storage_path('NHK-File/model-rowfile/'.$model_first->file_folder.'/'.$gcmsname.'.xlsx'));
 
                }
            } else {
                $input_gcms = 0;
                $filename_gcms = null;
                $myFile = 'local/storage/NHK-File/model-rowfile/'.$model_first->file_folder.'/'.$modeltype->gcms_file;
                File::delete($myFile);
            }
            if ($request->input('ck_ftir' . $st->text) == 1) {
                    if($modeltype->ftir == 0 )
                    {
                        $filename_ftir = "FTIR-" . $dateid . "-" . $namemodel.".xlsx";
                        $ftirname = "FTIR-" . $dateid . "-" . $namemodel;
                    }
                    else
                    {
                        File::Delete('local/storage/NHK-File/model-rowfile/'.$model_first->file_folder.'/'.$modeltype->ftir_file);
                            $filename_ftir = "FTIR-" . $dateid . "-" . $namemodel.".xlsx";
                            $ftirname = "FTIR-" . $dateid . "-" . $namemodel;
                    }
                $input_ftir = 1;
                if($st->id_model_p == 1) {
                    $file = storage_path('NHK-File/template/model-formet/FTIR-format.xlsx');
                    $spreadsheet = IOFactory::load($file);
                    $sheet = $spreadsheet->setActiveSheetIndex(0);

                    $sheet->setCellValue('B3',$model_master['name_model']);
                    $sheet->setCellValue('B4',$model_master['part_number']);
                    $sheet->setCellValue('B5',$model_master['part']);
                    $sheet->setCellValue('B6',$model_master['digit_lot_number']);
                    $sheet->setCellValue('C6',$model_master['digit_lot_number1']);
                    $sheet->setCellValue('D6',$model_master['digit_lot_number2']);
                    $sheet->setCellValue('B7',$model_master['surface_area']);
                    $sheet->setCellValue('B8',$model_master['ftir_parts_used']);
                    $sheet->setCellValue('B9',$model_master['lot_digit']);

                    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx'); 
                    $writer->setPreCalculateFormulas(false);
                    $writer->save(storage_path('NHK-File/model-rowfile/'.$model_first->file_folder.'/'.$ftirname.'.xlsx')); 

                }
            } else {
                $input_ftir = 0;
                $filename_ftir = null;
                $myFile = 'local/storage/NHK-File/model-rowfile/'.$model_first->file_folder.'/'.$modeltype->ftir_file;
                File::delete($myFile);
            }


            if ($request->input('ck_semtest' . $st->text) == 1) {
                if($modeltype->semtest == 0 )
                {
                    $filename_semtest = "GhostTest-" . $dateid . "-" . $namemodel.".xlsx";
                    $semtestname = "GhostTest-" . $dateid . "-" . $namemodel;
                }
                else
                {
                    File::Delete('local/storage/NHK-File/model-rowfile/'.$model_first->file_folder.'/'.$modeltype->semtest_file);
                        $filename_semtest = "GhostTest-" . $dateid . "-" . $namemodel.".xlsx";
                        $semtestname = "GhostTest-" . $dateid . "-" . $namemodel;
                }
                $input_semtest = 1;
                if($st->id_model_p == 1) {
                    $file = storage_path('NHK-File/template/model-formet/GhostTest-format.xlsx');
                    $spreadsheet = IOFactory::load($file);
                    $sheet = $spreadsheet->setActiveSheetIndex(0);         
                    
                    $sheet->setCellValue('C3',$model_master['name_model']);
                    $sheet->setCellValue('C4',$model_master['part']);
                    $sheet->setCellValue('J14',$model_master['part_number']);
                    $sheet->setCellValue('J15',$model_master['digit_lot_number']);
                    $sheet->setCellValue('K15',$model_master['digit_lot_number1']);
                    $sheet->setCellValue('L15',$model_master['digit_lot_number2']);
                    $sheet->setCellValue('J16',$model_master['lot_digit']);

                    $writer = IOFactory::createWriter($spreadsheet, "Xlsx");
                    $writer->setPreCalculateFormulas(false);
                    $writer->save(storage_path('NHK-File/model-rowfile/'.$model_first->file_folder.'/'.$semtestname.'.xlsx'));
 
                }
            } else {
                $input_semtest = 0;
                $filename_semtest = null;
                $myFile = 'local/storage/NHK-File/model-rowfile/'.$model_first->file_folder.'/'.$modeltype->semtest_file;
                File::delete($myFile);
            }
            $addparameter_type = [
                'lpc' => $input_lpc,
                'lpc_file' => $filename_lpc,
                'sem' => $input_sem,
                'sem_file' => $filename_sem,
                'ic' => $input_ic,
                'ic_file' => $filename_ic,
                'gcms' => $input_gcms,
                'gcms_file' => $filename_gcms,
                'ftir' => $input_ftir,
                'ftir_file' => $filename_ftir,
                'semtest' => $input_semtest,
                'semtest_file' => $filename_semtest,
            ];
            $idparameter = $request->input('ckidparameter' . $st->text);
            DB::table('nt_model_parameter')->where('id_m_parameter', $idparameter)->update($addparameter_type);
        }
        return response()->json(['success' => 1,'test'=>$filename_gcms]);
    }
    public function deletemodelSubmit(Request $request)
    {
        $status = [
            'status_model' => 0,
        ];
        DB::table('nt_model')->where('id_model', $request->input('id'))->update($status);
        return response()->json(['success' => 1]);
    }
    public function deletemodelhtSubmit(Request $request)
    {
        DB::table('nt_model_history')->where('id_model', $request->input('modelid'))->where('sub_number', $request->input('subnumber'))->delete();
        return response()->json(['success' => 1]);
    }

    public function newmodeldata()
    {
        return view('master/model/newindexdata');
    }

    public function downloadcontrolfile($type)
    {

        $styleArray_header = [
            'alignment' => [  // จัดตำแหน่ง
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ];

        // จัดรูปแบบส่วนของข้อมูล
        $styleArray_Data = [
            'borders' => [ // กำหนดเส้นขอบ
                'vertical' => [ // เส้นขอบแนวตั้งด้านใน
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
                'outline' => [ // เส้นขอบด้านนอก
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];

        $dateid = date('YmdHis');
        $selectdata = DB::table('nt_model')->where('model_type','LIKE', $type.'_%')->where('status_model', 1)->orderBy('name_model', 'ASC')->get();
        $semtestname = "MasterControllimitfile_" . $type . "_" . $dateid;

        $file = storage_path('NHK-File/template/model-formet/Template_Controllimit.xlsx');
        

        Excel::load($file, function($doc)use($selectdata) {

            $sheet = $doc->setActiveSheetIndex();

            $row = 8;
            foreach ($selectdata as $key => $value) {

                $sheet->getCell('CH'.$row)->setValue($value->id_model);
                $sheet->getCell('B'.$row)->setValue($value->name_model);
                $sheet->getCell('C'.$row)->setValue($value->part_number);
                $sheet->getCell('D'.$row)->setValue($value->drive_name);

                $row++;
            }

        })->setFilename($semtestname)->store('xlsx', storage_path('NHK-File/report/controllimit'));

        return $semtestname.'.xlsx';

        
    }

    //-------- Begin Control limit -------------
    public function createcontrol($type)
    {
        $data = [
            'model_type' => $type
        ];
        return view('master/model/controllimit',$data);
    }

    public function contollimittables($type) 
    { 
        return Datatables::of(DB::table('nt_model_history_index')->where('model_type','LIKE',$type))->make(true);
    }

    public function getdateControllimit(Request $request) 
    {

        $history = DB::table('nt_model_history_index')->where('id_chistory', $request->id)->first();
        return response()->json(['history' => $history]);

    }

    public function uplaodControlfile(Request $request)
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '600');
        set_time_limit(20000); 

        $dateshow_serial = '';

        $id_chistory = $request->id_chistory;
        $history = DB::table('nt_model_history_index')->where('id_chistory', $id_chistory)->first();

        $gethistory = DB::table('nt_model_history')->where('id_chistory', $id_chistory)->get();
        if($gethistory !== null){
            DB::table('nt_model_history')->where('id_chistory', $id_chistory)->delete();
            @unlink(storage_path('app/public/controllimit/'.$history->file));
        }
 
        $reader = IOFactory::createReader('Xlsx');
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($request->file('customFile'));
        

        $dateshow_serial = $spreadsheet->setActiveSheetIndex(0)->getCell('C4')->getValue();
        if (is_numeric($dateshow_serial)) {
            $dateObject = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($dateshow_serial);
            $getdate = $dateObject->format('Y-m-d');
        } else {
            return response()->json(['success' => 2]);
        }

        // --- หาวันแรกของเดือน ---
        $firstDayObject = clone $dateObject;
        $firstDayObject->modify('first day of this month');
        // $datestart = $firstDayObject->format('Y-m-d');
        $datestart = $firstDayObject->format('MY');

        // --- หาวันสุดท้ายของเดือน ---
        $lastDayObject = clone $dateObject;
        $lastDayObject->modify('last day of this month');
        // $dateend = $lastDayObject->format('Y-m-d');
        $dateend = $lastDayObject->format('MY');

        $rename = 'Controllimit_'. $datestart .'-'. $dateend .'_'.date('dmy').'.xlsx';
        
        $file = $request->file('customFile')->storeAs('controllimit', $rename,'public');
        DB::table('nt_model_history_index')->where('id_chistory', $id_chistory)->update(['file'=> $rename]);

        $newhistory = DB::table('nt_model_history_index')->where('id_chistory', $id_chistory)->first();
        $part = storage_path('app/public/controllimit/'.$newhistory->file);

        Excel::load($part, function($reader) use ($newhistory,$id_chistory,$datestart,$dateend) {
            $lastrow = $reader->setActiveSheetIndex(0)->getHighestRow();
            $sheet = $reader->setActiveSheetIndex(0);

            for ($i=8; $i <= $lastrow; $i++) { 
                $name_model = $sheet->getCell('B'.$i)->getValue();
                if (!empty($name_model)) {
                    
                    $id = $sheet->getCell('CH'.$i)->getValue();

                    //-------- LCP -------------
                    $LPC_LCL = $sheet->getCell('E'.$i)->getValue();
                    if($LPC_LCL != "N/A"){
                        $LPC_LCL = $sheet->getCell('E'.$i)->getOldCalculatedValue();
                    }
                    if($LPC_LCL == null){
                        $LPC_LCL = $sheet->getCell('E'.$i)->getCalculatedValue();
                    }
                    
                    $LPC_UCL = $sheet->getCell('F'.$i)->getValue();
                    if($LPC_UCL != "N/A"){
                        $LPC_UCL = $sheet->getCell('F'.$i)->getOldCalculatedValue();
                    }
                    if($LPC_UCL == null){
                        $LPC_UCL = $sheet->getCell('F'.$i)->getCalculatedValue();
                    }
                    
                    $LPC_TL = $sheet->getCell('G'.$i)->getValue();
                    if($LPC_TL != "N/A"){
                        $LPC_TL = $sheet->getCell('G'.$i)->getOldCalculatedValue();
                    }
                    if($LPC_TL == null){
                        $LPC_TL = $sheet->getCell('G'.$i)->getCalculatedValue();
                    }

                    $LPC_CL = $sheet->getCell('H'.$i)->getValue();
                    if($LPC_CL != "N/A"){
                        $LPC_CL = $sheet->getCell('H'.$i)->getOldCalculatedValue();
                    }
                    if($LPC_CL == null){
                        $LPC_CL = $sheet->getCell('H'.$i)->getCalculatedValue();
                    }

                    //-------- SEM1 -------------
                    $SEM1_LCL = $sheet->getCell('I'.$i)->getValue();
                    if($SEM1_LCL != "N/A"){
                        $SEM1_LCL = $sheet->getCell('I'.$i)->getOldCalculatedValue();
                    }
                    if($SEM1_LCL == null){
                        $SEM1_LCL = $sheet->getCell('I'.$i)->getCalculatedValue();
                    }

                    $SEM1_UCL = $sheet->getCell('J'.$i)->getValue();
                    if($SEM1_UCL != "N/A"){
                        $SEM1_UCL = $sheet->getCell('J'.$i)->getOldCalculatedValue();
                    }
                    if($SEM1_UCL == null){
                        $SEM1_UCL = $sheet->getCell('J'.$i)->getCalculatedValue();
                    }

                    $SEM1_TL = $sheet->getCell('K'.$i)->getValue();
                    if($SEM1_TL != "N/A"){
                        $SEM1_TL = $sheet->getCell('K'.$i)->getOldCalculatedValue();
                    }
                    if($SEM1_TL == null){
                        $SEM1_TL = $sheet->getCell('K'.$i)->getCalculatedValue();
                    }

                    $SEM1_CL = $sheet->getCell('L'.$i)->getValue();
                    if($SEM1_CL != "N/A"){
                        $SEM1_CL = $sheet->getCell('L'.$i)->getOldCalculatedValue();
                    }
                    if($SEM1_CL == null){
                        $SEM1_CL = $sheet->getCell('L'.$i)->getCalculatedValue();
                    }

                    //-------- SEM2 -------------
                    $SEM2_LCL = $sheet->getCell('M'.$i)->getValue();
                    if($SEM2_LCL != "N/A"){
                        $SEM2_LCL = $sheet->getCell('M'.$i)->getOldCalculatedValue();
                    }
                    if($SEM2_LCL == null){
                        $SEM2_LCL = $sheet->getCell('M'.$i)->getCalculatedValue();
                    }

                    $SEM2_UCL = $sheet->getCell('N'.$i)->getValue();
                    if($SEM2_UCL != "N/A"){
                        $SEM2_UCL = $sheet->getCell('N'.$i)->getOldCalculatedValue();
                    }
                    if($SEM2_UCL == null){
                        $SEM2_UCL = $sheet->getCell('N'.$i)->getCalculatedValue();
                    }

                    $SEM2_TL = $sheet->getCell('O'.$i)->getValue();
                    if($SEM2_TL != "N/A"){
                        $SEM2_TL = $sheet->getCell('O'.$i)->getOldCalculatedValue();
                    }
                    if($SEM2_TL == null){
                        $SEM2_TL = $sheet->getCell('O'.$i)->getCalculatedValue();
                    }

                    $SEM2_CL = $sheet->getCell('P'.$i)->getValue();
                    if($SEM2_CL != "N/A"){
                        $SEM2_CL = $sheet->getCell('P'.$i)->getOldCalculatedValue();
                    }
                    if($SEM2_CL == null){
                        $SEM2_CL = $sheet->getCell('P'.$i)->getCalculatedValue();
                    }

                    //-------- SEM3 -------------
                    $SEM3_LCL = $sheet->getCell('Q'.$i)->getValue();
                    if($SEM3_LCL != "N/A"){
                        $SEM3_LCL = $sheet->getCell('Q'.$i)->getOldCalculatedValue();
                    }
                    if($SEM3_LCL == null){
                        $SEM3_LCL = $sheet->getCell('Q'.$i)->getCalculatedValue();
                    }

                    $SEM3_UCL = $sheet->getCell('R'.$i)->getValue();
                    if($SEM3_UCL != "N/A"){
                        $SEM3_UCL = $sheet->getCell('R'.$i)->getOldCalculatedValue();
                    }
                    if($SEM3_UCL == null){
                        $SEM3_UCL = $sheet->getCell('R'.$i)->getCalculatedValue();
                    }

                    $SEM3_TL = $sheet->getCell('S'.$i)->getValue();
                    if($SEM3_TL != "N/A"){
                        $SEM3_TL = $sheet->getCell('S'.$i)->getOldCalculatedValue();
                    }
                    if($SEM3_TL == null){
                        $SEM3_TL = $sheet->getCell('S'.$i)->getCalculatedValue();
                    }

                    $SEM3_CL = $sheet->getCell('T'.$i)->getValue();
                    if($SEM3_CL != "N/A"){
                        $SEM3_CL = $sheet->getCell('T'.$i)->getOldCalculatedValue();
                    } 
                    if($SEM3_CL == null){
                        $SEM3_CL = $sheet->getCell('T'.$i)->getCalculatedValue();
                    }

                    //-------- SEM3.1 -------------
                    $SEM31_LCL = $sheet->getCell('U'.$i)->getValue();
                    if($SEM31_LCL != "N/A"){
                        $SEM31_LCL = $sheet->getCell('U'.$i)->getOldCalculatedValue();
                    } 
                    if($SEM31_LCL == null){
                        $SEM31_LCL = $sheet->getCell('U'.$i)->getCalculatedValue();
                    }

                    $SEM31_UCL = $sheet->getCell('V'.$i)->getValue();
                    if($SEM31_UCL != "N/A"){
                        $SEM31_UCL = $sheet->getCell('V'.$i)->getOldCalculatedValue();
                    }
                    if($SEM31_UCL == null){
                        $SEM31_UCL = $sheet->getCell('V'.$i)->getCalculatedValue();
                    }

                    $SEM31_TL = $sheet->getCell('W'.$i)->getValue();
                    if($SEM31_TL != "N/A"){
                        $SEM31_TL = $sheet->getCell('W'.$i)->getOldCalculatedValue();
                    }
                    if($SEM31_TL == null){
                        $SEM31_TL = $sheet->getCell('W'.$i)->getCalculatedValue();
                    }

                    $SEM31_CL = $sheet->getCell('X'.$i)->getValue();
                    if($SEM31_CL != "N/A"){
                        $SEM31_CL = $sheet->getCell('X'.$i)->getOldCalculatedValue();
                    }
                    if($SEM31_CL == null){
                        $SEM31_CL = $sheet->getCell('X'.$i)->getCalculatedValue();
                    }
                     
                    //-------- SEM3.2 -------------
                    $SEM32_LCL = $sheet->getCell('Y'.$i)->getValue();
                    if($SEM32_LCL != "N/A"){
                        $SEM32_LCL = $sheet->getCell('Y'.$i)->getOldCalculatedValue();
                    }
                    if($SEM32_LCL == null){
                        $SEM32_LCL = $sheet->getCell('Y'.$i)->getCalculatedValue();
                    }
    
                    $SEM32_UCL = $sheet->getCell('Z'.$i)->getValue();
                    if($SEM32_UCL != "N/A"){
                        $SEM32_UCL = $sheet->getCell('Z'.$i)->getOldCalculatedValue();
                    }
                    if($SEM32_UCL == null){
                        $SEM32_UCL = $sheet->getCell('Z'.$i)->getCalculatedValue();
                    }
    
                    $SEM32_TL = $sheet->getCell('AA'.$i)->getValue();
                    if($SEM32_TL != "N/A"){
                        $SEM32_TL = $sheet->getCell('AA'.$i)->getOldCalculatedValue();
                    }
                    if($SEM32_TL == null){
                        $SEM32_TL = $sheet->getCell('AA'.$i)->getCalculatedValue();
                    }
    
                    $SEM32_CL = $sheet->getCell('AB'.$i)->getValue();
                    if($SEM32_CL != "N/A"){
                        $SEM32_CL = $sheet->getCell('AB'.$i)->getOldCalculatedValue();
                    } 
                    if($SEM32_CL == null){
                        $SEM32_CL = $sheet->getCell('AB'.$i)->getCalculatedValue();
                    }

                    //-------- SEM3.3 -------------
                    $SEM33_LCL = $sheet->getCell('AC'.$i)->getValue();
                    if($SEM33_LCL != "N/A"){
                        $SEM33_LCL = $sheet->getCell('AC'.$i)->getOldCalculatedValue();
                    } 
                    if($SEM33_LCL == null){
                        $SEM33_LCL = $sheet->getCell('AC'.$i)->getCalculatedValue();
                    }

                    $SEM33_UCL = $sheet->getCell('AD'.$i)->getValue();
                    if($SEM33_UCL != "N/A"){
                        $SEM33_UCL = $sheet->getCell('AD'.$i)->getOldCalculatedValue();
                    } 
                    if($SEM33_UCL == null){
                        $SEM33_UCL = $sheet->getCell('AD'.$i)->getCalculatedValue();
                    }

                    $SEM33_TL = $sheet->getCell('AE'.$i)->getValue();
                    if($SEM33_TL != "N/A"){
                        $SEM33_TL = $sheet->getCell('AE'.$i)->getOldCalculatedValue();
                    } 
                    if($SEM33_TL == null){
                        $SEM33_TL = $sheet->getCell('AE'.$i)->getCalculatedValue();
                    }

                    $SEM33_CL = $sheet->getCell('AF'.$i)->getValue();
                    if($SEM33_CL != "N/A"){
                        $SEM33_CL = $sheet->getCell('AF'.$i)->getOldCalculatedValue();
                    } 
                    if($SEM33_CL == null){
                        $SEM33_CL = $sheet->getCell('AF'.$i)->getCalculatedValue();
                    }
                     
                    //-------- SEM4 -------------
                    $SEM4_LCL = $sheet->getCell('AG'.$i)->getValue();
                    if($SEM4_LCL != "N/A"){
                        $SEM4_LCL = $sheet->getCell('AG'.$i)->getOldCalculatedValue();
                    } 
                    if($SEM4_LCL == NULL){ $SEM4_LCL = $sheet->getCell('AG'.$i)->getValue(); }

                    $SEM4_UCL = $sheet->getCell('AH'.$i)->getValue();
                    if($SEM4_UCL != "N/A"){
                        $SEM4_UCL = $sheet->getCell('AH'.$i)->getOldCalculatedValue();
                    } 
                    if($SEM4_UCL == NULL){ $SEM4_UCL = $sheet->getCell('AH'.$i)->getValue(); }

                    $SEM4_TL = $sheet->getCell('AI'.$i)->getValue();
                    if($SEM4_TL != "N/A"){
                        $SEM4_TL = $sheet->getCell('AI'.$i)->getOldCalculatedValue();
                    } 
                    if($SEM4_TL == NULL){ $SEM4_TL = $sheet->getCell('AI'.$i)->getValue(); }

                    $SEM4_CL = $sheet->getCell('AJ'.$i)->getValue();
                    if($SEM4_CL != "N/A"){
                        $SEM4_CL = $sheet->getCell('AJ'.$i)->getOldCalculatedValue();
                    } 
                    if($SEM4_CL == NULL){ $SEM4_CL = $sheet->getCell('AJ'.$i)->getValue(); }
                      
                    //-------- SEM5 -------------
                    $SEM5_LCL = $sheet->getCell('AK'.$i)->getValue();
                    if($SEM5_LCL != "N/A"){
                        $SEM5_LCL = $sheet->getCell('AK'.$i)->getOldCalculatedValue();
                    } 
                    if($SEM5_LCL == NULL){ $SEM5_LCL = $sheet->getCell('AK'.$i)->getValue(); }

                    $SEM5_UCL = $sheet->getCell('AL'.$i)->getValue();
                    if($SEM5_UCL != "N/A"){
                        $SEM5_UCL = $sheet->getCell('AL'.$i)->getOldCalculatedValue();
                    } 
                    if($SEM5_UCL == NULL){ $SEM5_UCL = $sheet->getCell('AL'.$i)->getValue(); }

                    $SEM5_TL = $sheet->getCell('AM'.$i)->getValue();
                    if($SEM5_TL != "N/A"){
                        $SEM5_TL = $sheet->getCell('AM'.$i)->getOldCalculatedValue();
                    } 
                    if($SEM5_TL == NULL){ $SEM5_TL = $sheet->getCell('AM'.$i)->getValue(); }

                    $SEM5_CL = $sheet->getCell('AN'.$i)->getValue();
                    if($SEM5_CL != "N/A"){
                        $SEM5_CL = $sheet->getCell('AN'.$i)->getOldCalculatedValue();
                    }
                    if($SEM5_CL == NULL){ $SEM5_CL = $sheet->getCell('AN'.$i)->getValue(); }
  
                    //-------- SEM51 -------------
                    $SEM51_LCL = $sheet->getCell('AO'.$i)->getValue();
                    if($SEM51_LCL != "N/A"){
                        $SEM51_LCL = $sheet->getCell('AO'.$i)->getOldCalculatedValue();
                    } 
                    if($SEM51_LCL == NULL){ $SEM51_LCL = $sheet->getCell('AO'.$i)->getValue(); }

                    $SEM51_UCL = $sheet->getCell('AP'.$i)->getValue();
                    if($SEM51_UCL != "N/A"){
                        $SEM51_UCL = $sheet->getCell('AP'.$i)->getOldCalculatedValue();
                    } 
                    if($SEM51_UCL == NULL){ $SEM51_UCL = $sheet->getCell('AP'.$i)->getValue(); }

                    $SEM51_TL = $sheet->getCell('AQ'.$i)->getValue();
                    if($SEM51_TL != "N/A"){
                        $SEM51_TL = $sheet->getCell('AQ'.$i)->getOldCalculatedValue();
                    } 
                    if($SEM51_TL == NULL){ $SEM51_TL = $sheet->getCell('AQ'.$i)->getValue(); }

                    $SEM51_CL = $sheet->getCell('AR'.$i)->getValue();
                    if($SEM51_CL != "N/A"){
                        $SEM51_CL = $sheet->getCell('AR'.$i)->getOldCalculatedValue();
                    } 
                    if($SEM51_CL == NULL){ $SEM51_CL = $sheet->getCell('AR'.$i)->getValue(); }
                     
                    //-------- SEM6 -------------
                    $SEM6_LCL = $sheet->getCell('AS'.$i)->getValue();
                    if($SEM6_LCL != "N/A"){
                        $SEM6_LCL = $sheet->getCell('AS'.$i)->getOldCalculatedValue();
                    }
                    if($SEM6_LCL == NULL){ $SEM6_LCL = $sheet->getCell('AS'.$i)->getValue(); }

                    $SEM6_UCL = $sheet->getCell('AT'.$i)->getValue();
                    if($SEM6_UCL != "N/A"){
                        $SEM6_UCL = $sheet->getCell('AT'.$i)->getOldCalculatedValue();
                    }
                    if($SEM6_UCL == NULL){ $SEM6_UCL = $sheet->getCell('AT'.$i)->getValue(); }

                    $SEM6_TL = $sheet->getCell('AU'.$i)->getValue();
                    if($SEM6_TL != "N/A"){
                        $SEM6_TL = $sheet->getCell('AU'.$i)->getOldCalculatedValue();
                    }
                    if($SEM6_TL == NULL){ $SEM6_TL = $sheet->getCell('AU'.$i)->getValue(); }

                    $SEM6_CL = $sheet->getCell('AV'.$i)->getValue();
                    if($SEM6_CL != "N/A"){
                        $SEM6_CL = $sheet->getCell('AV'.$i)->getOldCalculatedValue();
                    }
                    if($SEM6_CL == NULL){ $SEM6_CL = $sheet->getCell('AV'.$i)->getValue(); }
                      
                    //-------- Anion -------------
                    $ANION_LCL = $sheet->getCell('AW'.$i)->getValue();
                    if($ANION_LCL != "N/A"){
                        $ANION_LCL = $sheet->getCell('AW'.$i)->getOldCalculatedValue();
                    }
                    if($ANION_LCL == NULL){ $ANION_LCL = $sheet->getCell('AW'.$i)->getValue(); }

                    $ANION_UCL = $sheet->getCell('AX'.$i)->getValue();
                    if($ANION_UCL != "N/A"){
                        $ANION_UCL = $sheet->getCell('AX'.$i)->getOldCalculatedValue();
                    }
                    if($ANION_UCL == NULL){ $ANION_UCL = $sheet->getCell('AX'.$i)->getValue(); }

                    $ANION_TL = $sheet->getCell('AY'.$i)->getValue();
                    if($ANION_TL != "N/A"){
                        $ANION_TL = $sheet->getCell('AY'.$i)->getOldCalculatedValue();
                    }
                    if($ANION_TL == NULL){ $ANION_TL = $sheet->getCell('AY'.$i)->getValue(); }

                    $ANION_CL = $sheet->getCell('AZ'.$i)->getValue();
                    if($ANION_CL != "N/A"){
                        $ANION_CL = $sheet->getCell('AZ'.$i)->getOldCalculatedValue();
                    }
                    if($ANION_CL == NULL){ $ANION_CL = $sheet->getCell('AZ'.$i)->getValue(); }
                     
                    //-------- SULPHATE -------------
                    $SULPHATE_LCL = $sheet->getCell('BA'.$i)->getValue();
                    if($SULPHATE_LCL != "N/A"){
                        $SULPHATE_LCL = $sheet->getCell('BA'.$i)->getOldCalculatedValue();
                    }
                    if($SULPHATE_LCL == NULL){ $SULPHATE_LCL = $sheet->getCell('BA'.$i)->getValue(); }

                    $SULPHATE_UCL = $sheet->getCell('BB'.$i)->getValue();
                    if($SULPHATE_UCL != "N/A"){
                        $SULPHATE_UCL = $sheet->getCell('BB'.$i)->getOldCalculatedValue();
                    }
                    if($SULPHATE_UCL == NULL){ $SULPHATE_UCL = $sheet->getCell('BB'.$i)->getValue(); }

                    $SULPHATE_TL = $sheet->getCell('BC'.$i)->getValue();
                    if($SULPHATE_TL != "N/A"){
                        $SULPHATE_TL = $sheet->getCell('BC'.$i)->getOldCalculatedValue();
                    }
                    if($SULPHATE_TL == NULL){ $SULPHATE_TL = $sheet->getCell('BC'.$i)->getValue(); }

                    $SULPHATE_CL = $sheet->getCell('BD'.$i)->getValue();
                    if($SULPHATE_CL != "N/A"){
                        $SULPHATE_CL = $sheet->getCell('BD'.$i)->getOldCalculatedValue();
                    }
                    if($SULPHATE_CL == NULL){ $SULPHATE_CL = $sheet->getCell('BD'.$i)->getValue(); }
   
                    //-------- CATION -------------
                    $CATION_LCL = $sheet->getCell('BE'.$i)->getValue();
                    if($CATION_LCL != "N/A"){
                        $CATION_LCL = $sheet->getCell('BE'.$i)->getOldCalculatedValue();
                    }
                    if($CATION_LCL == NULL){ $CATION_LCL = $sheet->getCell('BE'.$i)->getValue(); }

                    $CATION_UCL = $sheet->getCell('BF'.$i)->getValue();
                    if($CATION_UCL != "N/A"){
                        $CATION_UCL = $sheet->getCell('BF'.$i)->getOldCalculatedValue();
                    }
                    if($CATION_UCL == NULL){ $CATION_UCL = $sheet->getCell('BF'.$i)->getValue(); }

                    $CATION_TL = $sheet->getCell('BG'.$i)->getValue();
                    if($CATION_TL != "N/A"){
                        $CATION_TL = $sheet->getCell('BG'.$i)->getOldCalculatedValue();
                    }
                    if($CATION_TL == NULL){ $CATION_TL = $sheet->getCell('BG'.$i)->getValue(); }

                    $CATION_CL = $sheet->getCell('BH'.$i)->getValue();
                    if($CATION_CL != "N/A"){
                        $CATION_CL = $sheet->getCell('BH'.$i)->getOldCalculatedValue();
                    }
                    if($CATION_CL == NULL){ $CATION_CL = $sheet->getCell('BH'.$i)->getValue(); }
                     
                    //-------- OUTGASSING -------------
                    $OUTGASSING_LCL = $sheet->getCell('BI'.$i)->getValue();
                    if($OUTGASSING_LCL != "N/A"){
                        $OUTGASSING_LCL = $sheet->getCell('BI'.$i)->getOldCalculatedValue();
                    }
                    if($OUTGASSING_LCL == NULL){ $OUTGASSING_LCL = $sheet->getCell('BI'.$i)->getValue(); }

                    $OUTGASSING_UCL = $sheet->getCell('BJ'.$i)->getValue();
                    if($OUTGASSING_UCL != "N/A"){
                        $OUTGASSING_UCL = $sheet->getCell('BJ'.$i)->getOldCalculatedValue();
                    }
                    if($OUTGASSING_UCL == NULL){ $OUTGASSING_UCL = $sheet->getCell('BJ'.$i)->getValue(); }

                    $OUTGASSING_TL = $sheet->getCell('BK'.$i)->getValue();
                    if($OUTGASSING_TL != "N/A"){
                        $OUTGASSING_TL = $sheet->getCell('BK'.$i)->getOldCalculatedValue();
                    }
                    if($OUTGASSING_TL == NULL){ $OUTGASSING_TL = $sheet->getCell('BK'.$i)->getValue(); }

                    $OUTGASSING_CL = $sheet->getCell('BL'.$i)->getValue();
                    if($OUTGASSING_CL != "N/A"){
                        $OUTGASSING_CL = $sheet->getCell('BL'.$i)->getOldCalculatedValue();
                    } 
                    if($OUTGASSING_CL == NULL){ $OUTGASSING_CL = $sheet->getCell('BL'.$i)->getValue(); }

                    //-------- SMEAR -------------
                    $SMEAR_LCL = $sheet->getCell('BM'.$i)->getValue();
                    if($SMEAR_LCL != "N/A"){
                        $SMEAR_LCL = $sheet->getCell('BM'.$i)->getOldCalculatedValue();
                    }
                    if($SMEAR_LCL == NULL){ $SMEAR_LCL = $sheet->getCell('BM'.$i)->getValue(); }

                    $SMEAR_UCL = $sheet->getCell('BN'.$i)->getValue();
                    if($SMEAR_UCL != "N/A"){
                        $SMEAR_UCL = $sheet->getCell('BN'.$i)->getOldCalculatedValue();
                    }
                    if($SMEAR_UCL == NULL){ $SMEAR_UCL = $sheet->getCell('BN'.$i)->getValue(); }

                    $SMEAR_TL = $sheet->getCell('BO'.$i)->getValue();
                    if($SMEAR_TL != "N/A"){
                        $SMEAR_TL = $sheet->getCell('BO'.$i)->getOldCalculatedValue();
                    }
                    if($SMEAR_TL == NULL){ $SMEAR_TL = $sheet->getCell('BO'.$i)->getValue(); }

                    $SMEAR_CL = $sheet->getCell('BP'.$i)->getValue();
                    if($SMEAR_CL != "N/A"){
                        $SMEAR_CL = $sheet->getCell('BP'.$i)->getOldCalculatedValue();
                    } 
                    if($SMEAR_CL == NULL){ $SMEAR_CL = $sheet->getCell('BP'.$i)->getValue(); }

                    //-------- LOWMW -------------
                    $LOWMW_LCL = $sheet->getCell('BQ'.$i)->getValue();
                    if($LOWMW_LCL != "N/A"){
                        $LOWMW_LCL = $sheet->getCell('BQ'.$i)->getOldCalculatedValue();
                    }  
                    if($LOWMW_LCL == NULL){ $LOWMW_LCL = $sheet->getCell('BQ'.$i)->getValue(); }

                    $LOWMW_UCL = $sheet->getCell('BR'.$i)->getValue();
                    if($LOWMW_UCL != "N/A"){
                        $LOWMW_UCL = $sheet->getCell('BR'.$i)->getOldCalculatedValue();
                    }  
                    if($LOWMW_UCL == NULL){ $LOWMW_UCL = $sheet->getCell('BR'.$i)->getValue(); }

                    $LOWMW_TL = $sheet->getCell('BS'.$i)->getValue();
                    if($LOWMW_TL != "N/A"){
                        $LOWMW_TL = $sheet->getCell('BS'.$i)->getOldCalculatedValue();
                    } 
                    if($LOWMW_TL == NULL){ $LOWMW_TL = $sheet->getCell('BS'.$i)->getValue(); }

                    $LOWMW_CL = $sheet->getCell('BT'.$i)->getValue();
                    if($LOWMW_CL != "N/A"){
                        $LOWMW_CL = $sheet->getCell('BT'.$i)->getOldCalculatedValue();
                    }  
                    if($LOWMW_CL == NULL){ $LOWMW_CL = $sheet->getCell('BT'.$i)->getValue(); }

                    //-------- HIGHMW -------------
                    $HIGHMW_LCL = $sheet->getCell('BU'.$i)->getValue();
                    if($HIGHMW_LCL != "N/A"){
                        $HIGHMW_LCL = $sheet->getCell('BU'.$i)->getOldCalculatedValue();
                    } 
                    if($HIGHMW_LCL == NULL){ $HIGHMW_LCL = $sheet->getCell('BU'.$i)->getValue(); }

                    $HIGHMW_UCL = $sheet->getCell('BV'.$i)->getValue();
                    if($HIGHMW_UCL != "N/A"){
                        $HIGHMW_UCL = $sheet->getCell('BV'.$i)->getOldCalculatedValue();
                    } 
                    if($HIGHMW_UCL == NULL){ $HIGHMW_UCL = $sheet->getCell('BV'.$i)->getValue(); }

                    $HIGHMW_TL = $sheet->getCell('BW'.$i)->getValue();
                    if($HIGHMW_TL != "N/A"){
                        $HIGHMW_TL = $sheet->getCell('BW'.$i)->getOldCalculatedValue();
                    } 
                    if($HIGHMW_TL == NULL){ $HIGHMW_TL = $sheet->getCell('BW'.$i)->getValue(); }

                    $HIGHMW_CL = $sheet->getCell('BX'.$i)->getValue();
                    if($HIGHMW_CL != "N/A"){
                        $HIGHMW_CL = $sheet->getCell('BX'.$i)->getOldCalculatedValue();
                    } 
                    if($HIGHMW_CL == NULL){ $HIGHMW_CL = $sheet->getCell('BX'.$i)->getValue(); }

                    //-------- FTIR -------------
                    $FTIR_LCL = $sheet->getCell('BY'.$i)->getValue();
                    $FTIR_UCL = $sheet->getCell('BZ'.$i)->getValue();
                    $FTIR_TL = $sheet->getCell('CA'.$i)->getValue();
                    $FTIR_CL = $sheet->getCell('CB'.$i)->getValue();

                    //------------ INSERT DATA ------------------------

                    //-------trigger limit---------

                    $maxsubnumber = DB::table('nt_model_history')->where('id_model', $id)->max('sub_number');
                    $number = $maxsubnumber + 1;

                    $datatrigger['id_model'] = $id;
                    $datatrigger['sub_number'] = $number;
                    $datatrigger['id_model_type'] = 2;
                    $datatrigger['alpc'] = slope::nhk_formatnumber($LPC_TL, 2);
                    $datatrigger['class1'] = slope::nhk_formatnumber($SEM1_TL, 1);
                    $datatrigger['class2'] = slope::nhk_formatnumber($SEM2_TL, 1);
                    $datatrigger['class3'] = slope::nhk_formatnumber($SEM3_TL, 1);
                    $datatrigger['class3_1'] = slope::nhk_formatnumber($SEM31_TL, 1);
                    $datatrigger['class3_2'] = slope::nhk_formatnumber($SEM32_TL, 1);
                    $datatrigger['class3_3'] = slope::nhk_formatnumber($SEM33_TL, 1);
                    $datatrigger['class4'] = slope::nhk_formatnumber($SEM4_TL, 1);
                    $datatrigger['class5'] = slope::nhk_formatnumber($SEM5_TL, 1);
                    $datatrigger['class5_1'] = slope::nhk_formatnumber($SEM51_TL, 1);
                    $datatrigger['class6'] = slope::nhk_formatnumber($SEM6_TL, 1);
                    $datatrigger['anion'] = slope::nhk_formatnumber($ANION_TL, 5);
                    $datatrigger['sulphate'] = slope::nhk_formatnumber($SULPHATE_TL, 5);
                    $datatrigger['cation'] = slope::nhk_formatnumber($CATION_TL, 5);
                    $datatrigger['outgassing'] = slope::nhk_formatnumber($OUTGASSING_TL, 2);
                    $datatrigger['alkylacrylate'] = slope::nhk_formatnumber($SMEAR_TL, 2);
                    $datatrigger['low_MW'] = slope::nhk_formatnumber($LOWMW_TL, 2);
                    $datatrigger['high_MW'] = slope::nhk_formatnumber($HIGHMW_TL, 2);
                    $datatrigger['ftir'] = slope::nhk_formatnumber($FTIR_TL, 2);
                    $datatrigger['id_chistory'] = $newhistory->id_chistory;
                    $datatrigger['date_start'] = $newhistory->date_start;
                    $datatrigger['date_end'] = $newhistory->date_end;
                    $datatrigger['save_date'] = date('Y-m-d H:i:s');
                    $datatrigger['chkstatus'] = $newhistory->chkstatus;

                    DB::table('nt_model_history')->insert($datatrigger);

                    //------------Upperp-----------

                    $dataupperp['id_model'] = $id;
                    $dataupperp['sub_number'] = $number;
                    $dataupperp['id_model_type'] = 3;
                    $dataupperp['alpc'] = slope::nhk_formatnumber($LPC_UCL, 2);
                    $dataupperp['class1'] = slope::nhk_formatnumber($SEM1_UCL, 1);
                    $dataupperp['class2'] = slope::nhk_formatnumber($SEM2_UCL, 1);
                    $dataupperp['class3'] = slope::nhk_formatnumber($SEM3_UCL, 1);
                    $dataupperp['class3_1'] = slope::nhk_formatnumber($SEM31_UCL, 1);
                    $dataupperp['class3_2'] = slope::nhk_formatnumber($SEM32_UCL, 1);
                    $dataupperp['class3_3'] = slope::nhk_formatnumber($SEM33_UCL, 1);
                    $dataupperp['class4'] = slope::nhk_formatnumber($SEM4_UCL, 1);
                    $dataupperp['class5'] = slope::nhk_formatnumber($SEM5_UCL, 1);
                    $dataupperp['class5_1'] = slope::nhk_formatnumber($SEM51_UCL, 1);
                    $dataupperp['class6'] = slope::nhk_formatnumber($SEM6_UCL, 1);
                    $dataupperp['anion'] = slope::nhk_formatnumber($ANION_UCL, 5);
                    $dataupperp['sulphate'] = slope::nhk_formatnumber($SULPHATE_UCL, 5);
                    $dataupperp['cation'] = slope::nhk_formatnumber($CATION_UCL, 5);
                    $dataupperp['outgassing'] = slope::nhk_formatnumber($OUTGASSING_UCL, 2);
                    $dataupperp['alkylacrylate'] = slope::nhk_formatnumber($SMEAR_UCL, 2);
                    $dataupperp['low_MW'] = slope::nhk_formatnumber($LOWMW_UCL, 2);
                    $dataupperp['high_MW'] = slope::nhk_formatnumber($HIGHMW_UCL, 2);
                    $dataupperp['ftir'] = slope::nhk_formatnumber($FTIR_UCL, 2);
                    $dataupperp['id_chistory'] = $newhistory->id_chistory;
                    $dataupperp['date_start'] = $newhistory->date_start;
                    $dataupperp['date_end'] = $newhistory->date_end;
                    $dataupperp['save_date'] = date('Y-m-d H:i:s');
                    $dataupperp['chkstatus'] = $newhistory->chkstatus;

                    DB::table('nt_model_history')->insert($dataupperp);

                    //------------Lower------------

                    $datalower['id_model'] = $id;
                    $datalower['sub_number'] = $number;
                    $datalower['id_model_type'] = 4;
                    $datalower['alpc'] = slope::nhk_formatnumber($LPC_LCL, 2);
                    $datalower['class1'] = slope::nhk_formatnumber($SEM1_LCL, 1);
                    $datalower['class2'] = slope::nhk_formatnumber($SEM2_LCL, 1);
                    $datalower['class3'] = slope::nhk_formatnumber($SEM3_LCL, 1);
                    $datalower['class3_1'] = slope::nhk_formatnumber($SEM31_LCL, 1);
                    $datalower['class3_2'] = slope::nhk_formatnumber($SEM32_LCL, 1);
                    $datalower['class3_3'] = slope::nhk_formatnumber($SEM33_LCL, 1);
                    $datalower['class4'] = slope::nhk_formatnumber($SEM4_LCL, 1);
                    $datalower['class5'] = slope::nhk_formatnumber($SEM5_LCL, 1);
                    $datalower['class5_1'] = slope::nhk_formatnumber($SEM51_LCL, 1);
                    $datalower['class6'] = slope::nhk_formatnumber($SEM6_LCL, 1);
                    $datalower['anion'] = slope::nhk_formatnumber($ANION_LCL, 5);
                    $datalower['sulphate'] = slope::nhk_formatnumber($SULPHATE_LCL, 5);
                    $datalower['cation'] = slope::nhk_formatnumber($CATION_LCL, 5);
                    $datalower['outgassing'] = slope::nhk_formatnumber($OUTGASSING_LCL, 2);
                    $datalower['alkylacrylate'] = slope::nhk_formatnumber($SMEAR_LCL, 2);
                    $datalower['low_MW'] = slope::nhk_formatnumber($LOWMW_LCL, 2);
                    $datalower['high_MW'] = slope::nhk_formatnumber($HIGHMW_LCL, 2);
                    $datalower['ftir'] = slope::nhk_formatnumber($FTIR_LCL, 2);
                    $datalower['id_chistory'] = $newhistory->id_chistory;
                    $datalower['date_start'] = $newhistory->date_start;
                    $datalower['date_end'] = $newhistory->date_end;
                    $datalower['save_date'] = date('Y-m-d H:i:s');
                    $datalower['chkstatus'] = $newhistory->chkstatus;

                    DB::table('nt_model_history')->insert($datalower);

                    //------------Center-----------

                    $datacenter['id_model'] = $id;
                    $datacenter['sub_number'] = $number;
                    $datacenter['id_model_type'] = 5;
                    $datacenter['alpc'] = slope::nhk_formatnumber($LPC_CL, 2);
                    $datacenter['class1'] = slope::nhk_formatnumber($SEM1_CL, 1);
                    $datacenter['class2'] = slope::nhk_formatnumber($SEM2_CL, 1);
                    $datacenter['class3'] = slope::nhk_formatnumber($SEM3_CL, 1);
                    $datacenter['class3_1'] = slope::nhk_formatnumber($SEM31_CL, 1);
                    $datacenter['class3_2'] = slope::nhk_formatnumber($SEM32_CL, 1);
                    $datacenter['class3_3'] = slope::nhk_formatnumber($SEM33_CL, 1);
                    $datacenter['class4'] = slope::nhk_formatnumber($SEM4_CL, 1);
                    $datacenter['class5'] = slope::nhk_formatnumber($SEM5_CL, 1);
                    $datacenter['class5_1'] = slope::nhk_formatnumber($SEM51_CL, 1);
                    $datacenter['class6'] = slope::nhk_formatnumber($SEM6_CL, 1);
                    $datacenter['anion'] = slope::nhk_formatnumber($ANION_CL, 5);
                    $datacenter['sulphate'] = slope::nhk_formatnumber($SULPHATE_CL, 5);
                    $datacenter['cation'] = slope::nhk_formatnumber($CATION_CL, 5);
                    $datacenter['outgassing'] = slope::nhk_formatnumber($OUTGASSING_CL, 2);
                    $datacenter['alkylacrylate'] = slope::nhk_formatnumber($SMEAR_CL, 2);
                    $datacenter['low_MW'] = slope::nhk_formatnumber($LOWMW_CL, 2);
                    $datacenter['high_MW'] = slope::nhk_formatnumber($HIGHMW_CL, 2);
                    $datacenter['ftir'] = slope::nhk_formatnumber($FTIR_CL, 2); 
                    $datacenter['id_chistory'] = $newhistory->id_chistory;
                    $datacenter['date_start'] = $newhistory->date_start;
                    $datacenter['date_end'] = $newhistory->date_end;
                    $datacenter['save_date'] = date('Y-m-d H:i:s');
                    $datacenter['chkstatus'] = $newhistory->chkstatus;

                    DB::table('nt_model_history')->insert($datacenter);
                    

                            //---------------------
                            $id_model = $id;
                            $parameter = DB::table('nt_model_parameter')->where('id_model', $id_model)->where('id_model_p', 1)->first();
                            $model = DB::table('nt_model')->where('id_model', $id_model)->first();
                            $dateid = date('YmdHis');
                            $namemodel = $model->name_model;

                            $get_model_spec = DB::table('nt_model_spec')->where('id_model', $id_model)->get();
                            if($get_model_spec !== null){
                                @DB::table('nt_model_spec')->where('id_model', $id_model)->where('id_model_type',2)->delete();
                                @DB::table('nt_model_spec')->where('id_model', $id_model)->where('id_model_type',3)->delete();
                                @DB::table('nt_model_spec')->where('id_model', $id_model)->where('id_model_type',4)->delete();
                                @DB::table('nt_model_spec')->where('id_model', $id_model)->where('id_model_type',5)->delete();
                            }

                            $history_TL = DB::table('nt_model_history')->where('id_model_type',2)->where('id_model', $id_model)->where('id_chistory', $id_chistory)->first(); // Trigger limit
                            $history_UCL = DB::table('nt_model_history')->where('id_model_type',3)->where('id_model', $id_model)->where('id_chistory', $id_chistory)->first(); // Upper control limit
                            $history_LCL = DB::table('nt_model_history')->where('id_model_type',4)->where('id_model', $id_model)->where('id_chistory', $id_chistory)->first(); // Lower control limit
                            $history_CL = DB::table('nt_model_history')->where('id_model_type',5)->where('id_model', $id_model)->where('id_chistory', $id_chistory)->first(); // Center line

                            $modelspec_TL = [
                                'id_model' => $id_model,
                                'id_model_type' => 2,
                                'alpc' => $history_TL->alpc,
                                'class1' => $history_TL->class1,
                                'class2' => $history_TL->class2,
                                'class3' => $history_TL->class3,
                                'class3_1' => $history_TL->class3_1,
                                'class3_2' => $history_TL->class3_2,
                                'class3_3' => $history_TL->class3_3,
                                'class4' => $history_TL->class4,
                                'class5' => $history_TL->class5,
                                'class5_1' => $history_TL->class5_1,
                                'class6' => $history_TL->class6,
                                'anion' => $history_TL->anion,
                                'sulphate' => $history_TL->sulphate,
                                'cation' => $history_TL->cation,
                                'outgassing' => $history_TL->outgassing,
                                'alkylacrylate' => $history_TL->alkylacrylate,
                                'low_MW' => $history_TL->low_MW,
                                'high_MW' => $history_TL->high_MW,
                                'ftir' => $history_TL->ftir,
                            ];
                            DB::table('nt_model_spec')->insert($modelspec_TL);
                            $modelspec_UCL = [
                                'id_model' => $id_model,
                                'id_model_type' => 3,
                                'alpc' => $history_UCL->alpc,
                                'class1' => $history_UCL->class1,
                                'class2' => $history_UCL->class2,
                                'class3' => $history_UCL->class3,
                                'class3_1' => $history_UCL->class3_1,
                                'class3_2' => $history_UCL->class3_2,
                                'class3_3' => $history_UCL->class3_3,
                                'class4' => $history_UCL->class4,
                                'class5' => $history_UCL->class5,
                                'class5_1' => $history_UCL->class5_1,
                                'class6' => $history_UCL->class6,
                                'anion' => $history_UCL->anion,
                                'sulphate' => $history_UCL->sulphate,
                                'cation' => $history_UCL->cation,
                                'outgassing' => $history_UCL->outgassing,
                                'alkylacrylate' => $history_UCL->alkylacrylate,
                                'low_MW' => $history_UCL->low_MW,
                                'high_MW' => $history_UCL->high_MW,
                                'ftir' => $history_UCL->ftir,
                            ];
                            DB::table('nt_model_spec')->insert($modelspec_UCL);
                            $modelspec_LCL = [
                                'id_model' => $id_model,
                                'id_model_type' => 4,
                                'alpc' => $history_LCL->alpc,
                                'class1' => $history_LCL->class1,
                                'class2' => $history_LCL->class2,
                                'class3' => $history_LCL->class3,
                                'class3_1' => $history_LCL->class3_1,
                                'class3_2' => $history_LCL->class3_2,
                                'class3_3' => $history_LCL->class3_3,
                                'class4' => $history_LCL->class4,
                                'class5' => $history_LCL->class5,
                                'class5_1' => $history_LCL->class5_1,
                                'class6' => $history_LCL->class6,
                                'anion' => $history_LCL->anion,
                                'sulphate' => $history_LCL->sulphate,
                                'cation' => $history_LCL->cation,
                                'outgassing' => $history_LCL->outgassing,
                                'alkylacrylate' => $history_LCL->alkylacrylate,
                                'low_MW' => $history_LCL->low_MW,
                                'high_MW' => $history_LCL->high_MW,
                                'ftir' => $history_LCL->ftir,
                            ];
                            DB::table('nt_model_spec')->insert($modelspec_LCL);
                            $modelspec_CL = [
                                'id_model' => $id_model,
                                'id_model_type' => 5,
                                'alpc' => $history_CL->alpc,
                                'class1' => $history_CL->class1,
                                'class2' => $history_CL->class2,
                                'class3' => $history_CL->class3,
                                'class3_1' => $history_CL->class3_1,
                                'class3_2' => $history_CL->class3_2,
                                'class3_3' => $history_CL->class3_3,
                                'class4' => $history_CL->class4,
                                'class5' => $history_CL->class5,
                                'class5_1' => $history_CL->class5_1,
                                'class6' => $history_CL->class6,
                                'anion' => $history_CL->anion,
                                'sulphate' => $history_CL->sulphate,
                                'cation' => $history_CL->cation,
                                'outgassing' => $history_CL->outgassing,
                                'alkylacrylate' => $history_CL->alkylacrylate,
                                'low_MW' => $history_CL->low_MW,
                                'high_MW' => $history_CL->high_MW,
                                'ftir' => $history_CL->ftir,
                            ];
                            DB::table('nt_model_spec')->insert($modelspec_CL);
            
                            $model_spec_2 = DB::table('nt_model_spec')->where('id_model', $id_model)->where('id_model_type',2)->first(); // Trigger limit
                            $model_spec_3 = DB::table('nt_model_spec')->where('id_model', $id_model)->where('id_model_type',3)->first(); // Upper control limit

                            //---------- LPC ----------
                            $fileLPC = storage_path('NHK-File/model-rowfile/'.$model->file_folder.'/'.$parameter->lpc_file); 
                            if(!empty($parameter->lpc_file)){
                                if (file_exists($fileLPC)) {
                                    $filename_lpc = "LPC-" . $dateid . "-" . $namemodel.".xlsm";
                                    $lpcname = "LPC-" . $dateid . "-" . $namemodel;
                                    Excel::load($fileLPC, function ($doc)use($model_spec_2,$model_spec_3) {
                                        $sheet = $doc->setActiveSheetIndex(0);
                                        $sheet->setCellValue('R16',$model_spec_2->alpc);
                                        $sheet->setCellValue('R17',$model_spec_3->alpc);
                                    })->setFilename($lpcname)->store('xlsm', storage_path('NHK-File/model-rowfile/'.$model->file_folder));  
                                    File::Delete('local/storage/NHK-File/model-rowfile/'.$model->file_folder.'/'.$parameter->lpc_file);
                                    $addparameter_type = [
                                        'lpc_file' => $filename_lpc,
                                    ];
                                    DB::table('nt_model_parameter')->where('id_model', $id_model)->update($addparameter_type);
                                }
                            }
            
                            //---------- SEM ----------
                            $fileSEM = storage_path('NHK-File/model-rowfile/'.$model->file_folder.'/'.$parameter->sem_file); 
                            if(!empty($parameter->sem_file)){
                                if (file_exists($fileSEM)) {
                                    $filename_sem = "SEM-" . $dateid . "-" . $namemodel.".xlsx";
                                    $semname = "SEM-" . $dateid . "-" . $namemodel;
                                    Excel::load($fileSEM, function ($doc)use($model_spec_2,$model_spec_3) {
                                        $sheet = $doc->setActiveSheetIndex(2);
                                        $sheet->setCellValue('S21',  $model_spec_3->class1);
                                        $sheet->setCellValue('S22',  $model_spec_3->class2);
                                        $sheet->setCellValue('S23',  $model_spec_3->class3);
                                        $sheet->setCellValue('S24',  $model_spec_3->class3_1);
                                        $sheet->setCellValue('S25',  $model_spec_3->class3_2);
                                        $sheet->setCellValue('S26',  $model_spec_3->class3_3);
                                        $sheet->setCellValue('S27',  $model_spec_3->class4);
                                        $sheet->setCellValue('S28',  $model_spec_3->class5);
                                        $sheet->setCellValue('S29',  $model_spec_3->class5_1);
                                        $sheet->setCellValue('S30',  $model_spec_3->class6);
                                        $sheet->setCellValue('S31',  $model_spec_2->class1);
                                        $sheet->setCellValue('S32',  $model_spec_2->class2);
                                        $sheet->setCellValue('S33',  $model_spec_2->class3);
                                        $sheet->setCellValue('S34',  $model_spec_2->class3_1);
                                        $sheet->setCellValue('S35',  $model_spec_2->class3_2);
                                        $sheet->setCellValue('S36',  $model_spec_2->class3_3);
                                        $sheet->setCellValue('S37',  $model_spec_2->class4);
                                        $sheet->setCellValue('S38',  $model_spec_2->class5);
                                        $sheet->setCellValue('S39',  $model_spec_2->class5_1);
                                        $sheet->setCellValue('S40',  $model_spec_2->class6);
                                    })->setFilename($semname)->store('xlsx', storage_path('NHK-File/model-rowfile/'.$model->file_folder)); 
                                    File::Delete('local/storage/NHK-File/model-rowfile/'.$model->file_folder.'/'.$parameter->sem_file); 
                                    $addparameter_type = [
                                        'sem_file' => $filename_sem,
                                    ];
                                    DB::table('nt_model_parameter')->where('id_model', $id_model)->update($addparameter_type);
            
                                }
                            }

                     
                }
            } 
        });

        return response()->json(['success' => 1]);

    } 

    public function viewcontrollimit($id)
    {
        $data = [
            'id_chistory' => $id
        ];
        return view('master/model/viewcontrollimit',$data);

    }

    public function controllimittable(Request $request) 
    { 
       
        $data = DB::table('nt_model_history')
        ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
        ->select('nt_model_history.sub_number','nt_model_history.id_model','nt_model.name_model','nt_model_history.date_start','nt_model_history.date_end','nt_model_history.note')
        ->groupBy('nt_model_history.sub_number','nt_model_history.id_model','nt_model.name_model','nt_model_history.date_start','nt_model_history.date_end','nt_model_history.note')
        ->where('nt_model_history.id_chistory', $request->id)
        ->get();

        return Datatables::of($data)->make(true);
    }

    public function getmodelControllimit(Request $request) 
    {
        $model = DB::table('nt_model')->where('id_model', $request->id)->first();
        return response()->json(['model' => $model]);
    }

    public function updateControlfile(Request $request) 
    {
        $id_chistory = $request->edit_id_chistory;
        DB::table('nt_model_history_index')->where('id_chistory', $id_chistory)->update(['date_start'=> $request->date_start, 'date_end'=> $request->date_end]);

        $history = DB::table('nt_model_history_index')->where('id_chistory', $id_chistory)->first();

        $datacenter['date_start'] = $history->date_start;
        $datacenter['date_end'] = $history->date_end;
        $datacenter['save_date'] = date('Y-m-d H:i:s');

        DB::table('nt_model_history')->where('id_chistory', $id_chistory)->update($datacenter);

        return response()->json(['success' => 1]);

    }

    public function changestatuscontrol(Request $request) 
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '600');
        set_time_limit(20000); 

        $checkdata = 0;
        $gethistory = DB::table('nt_model_history_index')->where('id_chistory', $request->id)->first();

        //-------------------------------------------------------------
        if($request->status == 1){
            $array = [];
            $getmodal = DB::table('nt_model_history')->where('id_chistory', $gethistory->id_chistory)->get();
            if(count($getmodal) > 0){
                $getsiglemodal = DB::table('nt_model_history')->select('id_chistory','id_model')->where('id_chistory', $gethistory->id_chistory)->first();
                $oldmodal = $getsiglemodal->id_model;
                $getmodalfull = DB::table('nt_model_history')->where('id_model', $oldmodal)->get();
                foreach ($getmodalfull as $key => $value) { 
                    $array[] = $value->id_chistory; 
                }
                $result = array_unique( $array );
                DB::table('nt_model_history')->whereIn('id_chistory', $result)->update(['chkstatus' => 0]);
                DB::table('nt_model_history_index')->whereIn('id_chistory', $result)->update(['chkstatus' => 0]);

                $checkdata++;
            }
        }else{
            $getmodal = DB::table('nt_model_history')->where('id_chistory', $gethistory->id_chistory)->get();
            $checkdata++;
        }

        //-------------------------------------------------------------
        if($checkdata > 0){
            $uppdate_history = DB::table('nt_model_history_index')->where('id_chistory', $request->id)->update(['chkstatus' => $request->status]);
            foreach ($getmodal as $key => $value) {
                DB::table('nt_model_history')->where('id_model_history', $value->id_model_history)->update(['chkstatus' => $request->status]);
                
                if (intval($request->status) == 1) {
                
                    $id_model_history =  $value->id_model_history;
                
                    $newspec = [
                        "alpc" => $value->alpc,
                        "class1" => $value->class1,
                        "class2" => $value->class2,
                        "class3" => $value->class3,
                        "class3_1" => $value->class3_1,
                        "class3_2" => $value->class3_2,
                        "class3_3" => $value->class3_3,
                        "class4" => $value->class4,
                        "class5" => $value->class5,
                        "class5_1" => $value->class5_1,
                        "class6" => $value->class6,
                        "anion" => $value->anion,
                        "sulphate" => $value->sulphate,
                        "cation" => $value->cation,
                        "outgassing" => $value->outgassing,
                        "alkylacrylate" => $value->alkylacrylate,
                        "low_MW" => $value->low_MW,
                        "high_MW" => $value->high_MW,
                        "ftir" => $value->ftir,
                    ];
                
                    if($value->id_model_type == 2){
                        $model_spec_2 = DB::table('nt_model_spec')->where('id_model', $value->id_model)->where('id_model_type',2)->first();
                        DB::table('nt_model_spec')->where('id_model_spec', $model_spec_2->id_model_spec)->update($newspec);
                    }elseif ($value->id_model_type == 3){
                        $model_spec_3 = DB::table('nt_model_spec')->where('id_model', $value->id_model)->where('id_model_type',3)->first();
                        DB::table('nt_model_spec')->where('id_model_spec', $model_spec_3->id_model_spec)->update($newspec);
                    }elseif ($value->id_model_type == 4){
                        $model_spec_4 = DB::table('nt_model_spec')->where('id_model', $value->id_model)->where('id_model_type',4)->first();
                        DB::table('nt_model_spec')->where('id_model_spec', $model_spec_4->id_model_spec)->update($newspec);
                    }elseif ($value->id_model_type == 5){
                        $model_spec_5 = DB::table('nt_model_spec')->where('id_model', $value->id_model)->where('id_model_type',5)->first();
                        DB::table('nt_model_spec')->where('id_model_spec', $model_spec_5->id_model_spec)->update($newspec);
                    }
                
                    
                    $getnt_model = DB::table('nt_model')
                    ->join('nt_model_parameter', 'nt_model.id_model', '=', 'nt_model_parameter.id_model')
                    ->where('nt_model.id_model', $value->id_model)
                    ->first();
                
                    //------------
                    if($value->id_model_type == 2){
                
                        $model_spec_2 = DB::table('nt_model_spec')->where('id_model', $value->id_model)->where('id_model_type',2)->first(); // Trigger limit
                        $model_spec_3 = DB::table('nt_model_spec')->where('id_model', $value->id_model)->where('id_model_type',3)->first(); // Upper control limit
                
                        $dateid = date('YmdHis');
                        $namemodel = $getnt_model->name_model;
                        $parameter_type = DB::table('nt_model_parameter_type')->get();
                        foreach ($parameter_type as $st) {
                            
                            $modeltype = DB::table('nt_model_parameter')
                            ->where('id_model', $value->id_model)
                            ->where('id_model_p',$st->id_model_p)
                            ->first();
                        
                            if($modeltype->lpc == 0 )
                            {
                                $filename_lpc = "LPC-" . $dateid . "-" . $namemodel.".xlsx";
                                $lpcname = "LPC-" . $dateid . "-" . $namemodel;
                            }
                            else
                            {
                                File::Delete('local/storage/NHK-File/model-rowfile/'.$getnt_model->file_folder.'/'.$getnt_model->lpc_file);
                                    $filename_lpc = "LPC-" . $dateid . "-" . $namemodel.".xlsm";
                                    $lpcname = "LPC-" . $dateid . "-" . $namemodel;
                            }
                
                            if($st->id_model_p == 1) {
                                $file = storage_path('NHK-File/template/model-formet/LPC-format.xlsm');
                                Excel::load($file, function ($doc)use($model_spec_2,$model_spec_3) {
                                    $sheet = $doc->setActiveSheetIndex(0);
                                    $sheet->setCellValue('R16',$model_spec_2->alpc);
                                    $sheet->setCellValue('R17',$model_spec_3->alpc);
                                })->setFilename($lpcname)->store('xlsm', storage_path('NHK-File/model-rowfile/'.$getnt_model->file_folder));
                            } 
                
                            if($modeltype->sem == 0 )
                            {
                                $filename_sem = "SEM-" . $dateid . "-" . $namemodel.".xlsx";
                                $semname = "SEM-" . $dateid . "-" . $namemodel;
                            }
                            else
                            {
                                File::Delete('local/storage/NHK-File/model-rowfile/'.$getnt_model->file_folder.'/'.$modeltype->sem_file);
                                    $filename_sem = "SEM-" . $dateid . "-" . $namemodel.".xlsx";
                                    $semname = "SEM-" . $dateid . "-" . $namemodel;
                            }
                
                            $sem_file_folder = $getnt_model->file_folder;
                            if($st->id_model_p == 1) {
                                $file = storage_path('NHK-File/template/model-formet/SEM-format.xlsx');
                                Excel::load($file, function ($doc)use($model_spec_2,$model_spec_3) {
                                    $sheet = $doc->setActiveSheetIndex(2);
                
                                    $sheet->setCellValue('S21',  $model_spec_3->class1);
                                    $sheet->setCellValue('S22',  $model_spec_3->class2);
                                    $sheet->setCellValue('S23',  $model_spec_3->class3);
                                    $sheet->setCellValue('S24',  $model_spec_3->class3_1);
                                    $sheet->setCellValue('S25',  $model_spec_3->class3_2);
                                    $sheet->setCellValue('S26',  $model_spec_3->class3_3);
                                    $sheet->setCellValue('S27',  $model_spec_3->class4);
                                    $sheet->setCellValue('S28',  $model_spec_3->class5);
                                    $sheet->setCellValue('S29',  $model_spec_3->class5_1);
                                    $sheet->setCellValue('S30',  $model_spec_3->class6);
                                    $sheet->setCellValue('S31',  $model_spec_2->class1);
                                    $sheet->setCellValue('S32',  $model_spec_2->class2);
                                    $sheet->setCellValue('S33',  $model_spec_2->class3);
                                    $sheet->setCellValue('S34',  $model_spec_2->class3_1);
                                    $sheet->setCellValue('S35',  $model_spec_2->class3_2);
                                    $sheet->setCellValue('S36',  $model_spec_2->class3_3);
                                    $sheet->setCellValue('S37',  $model_spec_2->class4);
                                    $sheet->setCellValue('S38',  $model_spec_2->class5);
                                    $sheet->setCellValue('S39',  $model_spec_2->class5_1);
                                    $sheet->setCellValue('S40',  $model_spec_2->class6);
                
                                })->setFilename($semname)->store('xlsx', storage_path('NHK-File/model-rowfile/' . $sem_file_folder));
                            
                            }
                        
                            $addparameter_type = [
                                'lpc_file' => $filename_lpc,
                                'sem_file' => $filename_sem,
                            ];
                            DB::table('nt_model_parameter')->where('id_m_parameter', $modeltype->id_m_parameter)->update($addparameter_type);
                        
                        }
                    }
                    
                }

            }

            return response()->json(['success' => 1]);
        }
        return response()->json(['success' => 2]);


    }

    public function deleteControllimit(Request $request)
    {

        $history = DB::table('nt_model_history_index')->where('id_chistory', $request->id)->first();
        if($history->file !== null){
            @unlink(storage_path('app/public/controllimit/'.$history->file));
        }

        DB::table('nt_model_history_index')->where('id_chistory', $request->id)->delete();
        DB::table('nt_model_history')->where('id_chistory', $request->id)->delete();
        return response()->json(['success' => 1]);

    }

    public function createdate($type)
    {
        $data = [
            'model_type' => $type
        ];
        return view('master/model/controllimit_create',$data);
    }

    public function addmodelcreatedate(Request $request)
    {

        if($request->input('chkbox1') == 1)
        {
            $date_start = Date("Y-m-d", strtotime($request->input('date_start')."-1 Day"));
            $date_end = '2060-12-31';


        }else{
                
            $date_start = $request->input('date_start');
            $date_end = $request->input('date_end');
        }

        $modelspec = [
            'model_type' => $request->model_type,
            'date_start' => $date_start,
            'date_end' => $date_end,
            'chkstatus' => 0
        ];

        DB::table('nt_model_history_index')->insert($modelspec);

        return response()->json(['success' => 1]);

    }

    //-------- End Control limit -------------

    //-------- Begin Daily check set up ------------- 
    public function dailycheckset() 
    {
        return view('master/model/dailycheckset');
    }

    public function dailychecksetcreate()  
    {
        
        $unit = DB::table('nt_dailycheck_unit')->get();
        $data = [
            'unit' => $unit
        ];
        return view('master/model/dailychecksetcreate' ,$data);
    }

    public function createDailycheckset(Request $request)  
    { 

        
        $tester = $request->input('txt_tester');
        if( DB::table('nt_dailycheck_unit')->where('id_dyunit',$request->input('txt_tester'))->count() == 0 ){
            $tester = DB::table('nt_dailycheck_unit')->insertGetId([
                'name_tester' => $request->input('txt_tester'),
            ]);
        }

        $day = date('Y-m-d');
        $datetime = date('Y-m-d H:i:s', strtotime($day.' '.$request->input('txt_date')));

        $data = DB::table('nt_dailycheck_set')->where('id_dyunit', $request->txt_tester)->whereDate('due_date', '=', $datetime )->first();
        if(empty($data)){

            $id = DB::table('nt_dailycheck_set')->insertGetId([
                'id_dyunit' => $tester,
                'due_date' => $datetime,
                'save_date' => date('Y-m-d H:i:s'),
            ]); 
            return response()->json(['success' => 1]);

            // if ($id) {
            //     DB::table('nt_dailycheck_info')->insert([ 'id_daily' => $id ]);
            //     return response()->json(['success' => 1]);
            // } else {
            //     return response()->json(['success' => 2]);
            // } 

        }else {
            return response()->json(['success' => 3]);
        } 

    }

    public function dailychecksettable(Request $request)  
    {

        $data = DB::table('nt_dailycheck_set')
        ->join('nt_dailycheck_unit', 'nt_dailycheck_unit.id_dyunit', '=', 'nt_dailycheck_set.id_dyunit')
        ->get();
        return Datatables::of($data)->make(true);

    }

    public function dailychecksetedit($id)  
    {

        $unit = DB::table('nt_dailycheck_unit')->get();
        $daily = DB::table('nt_dailycheck_set')->where('id_daily', $id)->first();

        $data = [
            'daily' => $daily,
            'unit' => $unit
        ];

        return view('master/model/dailychecksetedit',$data);

    }

    public function updateDailycheckset(Request $request) 
    { 

        $tester = $request->input('txt_tester');
        if( DB::table('nt_dailycheck_unit')->where('id_dyunit',$request->input('txt_tester'))->count() == 0 ){
            $tester = DB::table('nt_dailycheck_unit')->insertGetId([
                'name_tester' => $request->input('txt_tester'),
            ]);
        }


        $day = date('Y-m-d');
        $datetime = date('Y-m-d H:i:s', strtotime($day.' '.$request->input('txt_date')));

        $parametor = [
            'id_dyunit' => $tester,
            'due_date' => $datetime,
            'save_date' => date('Y-m-d H:i:s'),
        ];
        $check = DB::table('nt_dailycheck_set')->where('id_daily', $request->input('id_daily'))->update($parametor);
        if ($check) {

            $set = DB::table('nt_dailycheck_set')->where('id_daily', $request->input('id_daily'))->first();
            DB::table('nt_dailycheck_info')->where('id_daily', $set->id_daily)->whereNull('recoredtime')->delete();

            return response()->json(['success' => 1]);
        } else {
            return response()->json(['success' => 2]);
        } 

    }

    public function deleteDailycheckset(Request $request)  
    { 

        DB::table('nt_dailycheck_set')->where('id_daily', $request->id)->delete();
        DB::table('nt_dailycheck_info')->where('id_daily', $request->id)->delete();

        return response()->json(['success' => 1]);

    }

    public function Dailycheckmail()  
    {
 
        // $to = "chadaporn@orange-thailand.com";
        $toemail = "cleanliness.lab@nhkspg.co.th,weerawan.iea@nhkspg.co.th,dataqa.dds@nhkspg.co.th,ekkaparp.jun@nhkspg.co.th,numfon.sin@nhkspg.co.th,Surasak.hoe@nhkspg.co.th,narupon@nhkspg.co.th,piyachat.mab@nhkspg.co.th,sarocha.man@nhkspg.co.th";
        $subject = "[CAS program]_Daily check of tester";     
        $message =  "<html><head><body>
        Dear all,<br><br>
        <p>สรุปผล Daily check of tester ตามรายละเอียด้านล่าง</p>
        </body>
        </html>
        ";
        $message .= "<br> <a href='http://10.6.100.17/cas_program/output/dailycheck'> >>>Link daily check</a> <<< <br><br>";

        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";


        $getdata = DB::table('nt_dailycheck_info')
        ->join('nt_dailycheck_set', 'nt_dailycheck_info.id_daily', '=', 'nt_dailycheck_set.id_daily')
        ->join('nt_dailycheck_unit', 'nt_dailycheck_set.id_dyunit', '=', 'nt_dailycheck_unit.id_dyunit')
        ->where('nt_dailycheck_set.due_date', 'LIKE', date('Y-m-d').'%')
        ->orderby('nt_dailycheck_info.recoredtime','DESC')
        ->get();

        $message .= "<table style=' table-layout: fixed; border-collapse: collapse; border: 1px solid #070707; '>";
            $message .= "<tr style='border: 1px solid;'>";
                $message .= "<th style='border: 1px solid;'>Tester</th>";
                $message .= "<th style='border: 1px solid;'>Operator</th>";
                $message .= "<th style='border: 1px solid;'>Status</th>";
                $message .= "<th style='border: 1px solid;'>Result</th>";
                $message .= "<th style='border: 1px solid;'>Problem</th>";
                $message .= "<th style='border: 1px solid;'>Root cause</th>";
                $message .= "<th style='border: 1px solid;'>Action</th>";
            $message .= "</tr>";


            foreach ($getdata as $key => $value) {

                $problem = "";

                $recoredtime = ($value->recoredtime)? $value->recoredtime : "-";
                $results = ($value->results)? $value->results : "-";
                $action = ($value->action)? $value->action : "-";
                $rootcause = ($value->rootcause)? $value->rootcause : "-";
                
                // Check status Column
                if($value->recoredtime == null){
                    $status = "<button style='background-color: #FFC107; border-radius: 9px;border: 0px solid #ffffff;padding: 5px'>On Going</button>";
                }else{
                    if($value->results == "PASS"){
                        if($value->recoredtime > $value->due_date){ // ส่งล่าช้า
                            $status = "<button style='background-color: #2196F3; border-radius: 9px;border: 0px solid #ffffff;padding: 5px'>DONE<br>(Delay)</button>";
                        }else{
                            $status = "<button style='background-color: #31ae37; border-radius: 9px;border: 0px solid #ffffff;padding: 5px'>DONE</button>";
                        }
                    }else{
                        $status = "<button style='background-color: #31ae37; border-radius: 9px;border: 0px solid #ffffff;padding: 5px'>DONE</button>";
                    }
                }

                // Check Result Column
                if($value->recoredtime != null){
                    if($value->results == "PASS"){
                        $results = "<button style='background-color: #31ae37; border-radius: 9px;border: 0px solid #ffffff;padding: 5px'>PASS</button>";
                    }else{

                        $results = "<button style='background-color: #F44336; border-radius: 9px;border: 0px solid #ffffff;padding: 5px'>NOT PASS</button>";
                    }
                }

                // Check problem Column
                if($value->problem != null){
                    if($value->recoredtime > $value->due_date){ // ส่งล่าช้า
                        $problem .= "ลง daily check ล่าช้า | ";
                    }
                    $problem .= $value->problem;
                }


                $message .= "<tr style='border: 1px solid; text-align: center;'>";
                $message .= "<td style='border: 1px solid;'>".$value->name_tester."</td>";
                $message .= "<td style='border: 1px solid;'>". $recoredtime ."</td>";
                $message .= "<td style='border: 1px solid;'>". $status ."</td>";
                $message .= "<td style='border: 1px solid;'>". $results ."</td>";
                $message .= "<td style='border: 1px solid;'>". $problem ."</td>";
                $message .= "<td style='border: 1px solid;'>". $rootcause ."</td>";
                $message .= "<td style='border: 1px solid;'>". $action ."</td>";
                $message .= "</tr>";
            }    
           
        $message .= "</table>";
        
        if (mail($to, $subject, $message, $headers)) {
          return "true" ;
        }

    }
    //-------- End Daily check set up ------------- 


    //-------- Begin Monitoring set up ------------- 
    public function monitorset()  
    {
        $unit_frequency = DB::table('nt_monitor_frequency_unit')->get();

        $data = [
            'unit_frequency' => $unit_frequency,
        ];
        return view('master/model/monitorset',$data);
    }

    public function monitorsetcreate()  
    {
        $unit_frequency = DB::table('nt_monitor_frequency_unit')->get();
        $unit_tester = DB::table('nt_monitor_unit')->get();
        
        $data = [
            'unit_tester' => $unit_tester,
            'unit_frequency' => $unit_frequency
        ];

        return view('master/model/monitorsetcreate', $data);
    } 

    public function createMonitorset(Request $request) 
    {

        $tester = $request->tester;
        if( DB::table('nt_monitor_unit')->where('id_mnu',$request->tester)->count() == 0 ){
            $tester = DB::table('nt_monitor_unit')->insertGetId([
                'name_tester' => $request->tester,
            ]);
        }

        $startdate = date('Y-m-d H:i:s',strtotime($request->startdate));
        $enddate = date('Y-m-d',strtotime($request->enddate));
        $repeats = $request->repeats;
        $repeatsunit = $request->repeatsunit;
        // $repeatsday = [];
        // foreach ($request->repeatsday as $key => $value) {
        //     $repeatsday[] = $value;
        // }
        // $newrepeatsday = implode(',', $repeatsday);


        $id = DB::table('nt_monitor_set')->insertGetId([
            'id_mnu' => $tester,
            'topic' => $request->topic,
            'start_date' => $startdate,
            'repeats' => $repeats,
            'repeats_unit' => $repeatsunit,
            // 'repeats_date' => $newrepeatsday,
            'end_date' => $enddate,
            'save_date' => date('Y-m-d H:i:s'),
        ]); 

        if ($id) {
            return response()->json(['success' => 1]);
        } else {
            return response()->json(['success' => 2]);
        }


    }

    public function monitorsettable(Request $request)  
    {

        // $arraydate = [
        //     0 => "Monday",
        //     1 => "Tuesday",
        //     2 => "Wednesday",
        //     3 => "Thursday",
        //     4 => "Friday",
        //     5 => "Saturday",
        //     6 => "Sunday",
        // ];

        if(empty($request->date_start) && empty($request->date_end) && empty($request->repeats))
        {
            $data = DB::table('nt_monitor_set')
            ->join('nt_monitor_unit', 'nt_monitor_unit.id_mnu', '=', 'nt_monitor_set.id_mnu')
            ->get();

        }else{

            $data = DB::table('nt_monitor_set')
            ->join('nt_monitor_unit', 'nt_monitor_unit.id_mnu', '=', 'nt_monitor_set.id_mnu');

            if(!empty($request->date_start) && !empty($request->date_end))
            {
                $start = date('Y-m-d', strtotime($request->date_start));
                $end = date('Y-m-d', strtotime($request->date_end. "+1 days"));
                $data->where('nt_monitor_set.start_date','>=',$start)->where('nt_monitor_set.start_date','<=',$end);
            }

            if(!empty($request->repeats))
            {
                if ($request->repeats == "no_repeats") {
                    $data->whereNull('nt_monitor_set.repeats_unit'); 
                }else {
                    $data->where('nt_monitor_set.repeats_unit','=', $request->repeats);
                }
            }

            $data = $data->orderBy('nt_monitor_set.start_date', 'ASC')->get();

        }   

        return Datatables::of($data)
        ->editColumn("newrepeats", function ($query) {
            if(!empty($query->repeats)){
                return $query->repeats." ".$query->repeats_unit;
            }else{
                return "-";
            }
        })
        // ->editColumn("day", function ($query) use ($arraydate) {
        //     $array = [];
        //     $repeatsday = explode( ',', $query->repeats_date );
        //     for ($i=0; $i < count($repeatsday); $i++) { 
        //         if ($repeatsday[$i] == 1) {
        //             $array[] = $arraydate[$i];
        //         }
        //     }
        //     return implode(',', $array);

        // })
        ->make(true);

    }

    public function monitorsetedit($id)  
    {

        $unit_frequency = DB::table('nt_monitor_frequency_unit')->get();
        $unit_tester = DB::table('nt_monitor_unit')->get();

        $repeatsday = array();
        $set = DB::table('nt_monitor_set')->where('id_mns', $id)->first();
        $repeatsday = explode( ',', $set->repeats_date );
            
        $data = [
            'unit_tester' => $unit_tester,
            'unit_frequency' => $unit_frequency,
            'repeatsday' => $repeatsday,
            'set' => $set
        ];

        return view('master/model/monitorsetedit',$data);

    }

    public function updateMonitorset(Request $request)  
    {

        $tester = $request->tester;
        if( DB::table('nt_monitor_unit')->where('id_mnu',$request->tester)->count() == 0 ){
            $tester = DB::table('nt_monitor_unit')->insertGetId([
                'name_tester' => $request->tester,
            ]);
        }

        $startdate = date('Y-m-d H:i:s',strtotime($request->startdate));
        $enddate = date('Y-m-d',strtotime($request->enddate));
        $repeats = $request->repeats;
        $repeatsunit = $request->repeatsunit;
        // $repeatsday = [];
        // foreach ($request->repeatsday as $key => $value) {
        //     $repeatsday[] = $value;
        // }
        // $newrepeatsday = implode(',', $repeatsday);

        $parametor = [
            'id_mnu' => $tester,
            'topic' => $request->topic,
            'start_date' => $startdate,
            'repeats' => $repeats,
            'repeats_unit' => $repeatsunit,
            // 'repeats_date' => $newrepeatsday,
            'end_date' => $enddate,
            'save_date' => date('Y-m-d H:i:s'),
        ];
        $check = DB::table('nt_monitor_set')->where('id_mns', $request->input('id_mns'))->update($parametor);

        $deleteinfo = DB::table('nt_monitor_info')
        ->where('id_mns', $request->input('id_mns'))
        ->whereNull('recoredtime')
        ->delete();

        if ($check) {
            return response()->json(['success' => 1]);
        } else {
            return response()->json(['success' => 2]);
        } 


    }

    public function deleteMonitorset(Request $request)  
    { 

        DB::table('nt_monitor_set')->where('id_mns', $request->id)->delete();
        DB::table('nt_monitor_info')->where('id_mns', $request->id)->delete();

        return response()->json(['success' => 1]);

    }
 
  
    //----------- Begin WD -------------

    public function addmodelWD()
    {
        $modeltype = DB::table('nt_model_spec_type')->get();
        $parameter_type = DB::table('nt_model_parameter_type')->get();
        $model_volume = DB::table('nt_model_unit_volume')->where('status',1)->get();
        $model_area = DB::table('nt_model_unit_area')->where('status',1)->get();
        $model_electric = DB::table('nt_model_unit_electric')->where('status',1)->get();
        $model_part = DB::table('nt_model_unit_part')->where('status',1)->get();
        $model_part_mdl = DB::table('nt_model_unit_mdl_part')->where('status',1)->get(); // NHK2 edit 23/02/24
        $model_time = DB::table('nt_model_unit_time')->where('status',1)->get();
        return view('master/model/addmodelWD', 
        [
         'modeltype' => $modeltype,
         'parameterType' => $parameter_type,
         'model_volume'=>$model_volume,
         'model_area'=>$model_area,
         'model_electric'=>$model_electric,
         'model_part'=>$model_part,
         'model_part_mdl'=>$model_part_mdl, // NHK2 edit 23/02/24
         'model_time'=>$model_time,
        ]);
    }

    public function add_modelWD(Request $request)
    {   

        $maxids = DB::table('nt_model')->max('id_model');
        $maxid = $maxids+1;
        $dateid = date('YmdHis');
        $namefolder = "M";
        $namemodel = $request->input('txt_name_model');
        $model_master = [
            'name_model' => $request->input('txt_name_model'),
            'part' => $request->input('txt_part'),
            'model_type' => $request->input('model_type'), 
            'drive_name' => $request->input('txt_drive_name'),
            'wdqty' => $request->input('txt_wd_qty'), 
            'line_type' => $request->input('txt_line_type'),
            'alpc_dc' => $request->input('txt_alpc_dc'),
            'alpc_dr' => $request->input('txt_alpc_dr'),
            'alpc_cs' => $request->input('txt_alpc_cs'),
            'sem_dc' => $request->input('txt_sem_dc'),
            'sem_dr' => $request->input('txt_sem_dr'),
            'sem_cs' => $request->input('txt_sem_cs'),
            'ic_dc' => $request->input('txt_ic_dc'),
            'ic_dr' => $request->input('txt_ic_dr'),
            'ic_cs' => $request->input('txt_ic_cs'),
            'gcms_dc' => $request->input('txt_gcms_dc'),
            'gcms_dr' => $request->input('txt_gcms_dr'),
            'gcms_cs' => $request->input('txt_gcms_cs'),
            'ftir_dc' => $request->input('txt_ftir_dc'),
            'ftir_dr' => $request->input('txt_ftir_dr'),
            'ftir_cs' => $request->input('txt_ftir_cs'),
            'vendor_id' => $request->input('txt_vendor_id'),
            'sub_supplier_name' => $request->input('txt_ssn'),
            'part_number' => $request->input('txt_part_number'),
            'part_revision' => $request->input('txt_part_revision'),
            'drawing_number' => $request->input('txt_drawing_number'),
            'drawing_revision' => $request->input('txt_drawing_revision'),  
            'digit_lot_number' => $request->input('txt_dln'),
            'surface_area' => $request->input('txt_sa'),
            'ic_parts_used' => $request->input('txt_ic_pcs'),
            'ftir_parts_used' => $request->input('txt_ftir_pcs'),
            'alpc_parts_used' => $request->input('txt_alpc_pcs'),
            'sem_parts_used' => $request->input('txt_sem_pcs'),
            'gcms_parts_used' => $request->input('txt_gcms_pcs'),
            'alpc_extraction_vol' => $request->input('txt_alpc_ev'),
            'alpc_water_vol' => $request->input('txt_alpc_wv'),
            'alpc_us_power' => $request->input('txt_alpc_us'),
            'lot_digit' => $request->input('txt_lot_digit_q'),
            'alpc_hang' => $request->input('txt_alpc_hang'),
            'alpc_us_frequency' => $request->input('txt_alpc_usf'),
            'alpc_extraction_time' => $request->input('txt_alpc_et'),
            'sem_filtered_solution' => $request->input('txt_sem_fsv'),
            'sem_filter_spot_area' => $request->input('txt_sem_filter'),
            'ic_extraction_vo' => $request->input('txt_ic_ev'),
            'gcms_sampling_time' => $request->input('txt_gcms_st'),
            'alpc_sum' => $request->input('txt_alpc_sum'),
            'sem_sum' => $request->input('txt_sem_sum'),
            'ic_sum' => $request->input('txt_ic_sum'),
            'gcms_sum' => $request->input('txt_gcms_sum'),
            'ftir_sum' => $request->input('txt_ftir_sum'),   
            'file_folder' =>   $namefolder, 

            'surface_area_unit' => $request->input('surface_area_unit'),
            'alpc_water_vol_unit' => $request->input('alpc_water_vol_unit'),
            'alpc_extraction_vol_unit' => $request->input('alpc_extraction_vol_unit'),
            'alpc_us_power_unit' => $request->input('alpc_us_power_unit'),
            'alpc_parts_used_unit' => $request->input('alpc_parts_used_unit'),
            'alpc_us_frequency_unit' => $request->input('alpc_us_frequency_unit'),
            'alpc_extraction_time_unit' => $request->input('alpc_extraction_time_unit'),
            'sem_filtered_solution_unit' => $request->input('sem_filtered_solution_unit'),
            'gcms_parts_used_unit' => $request->input('gcms_parts_used_unit'),
            'sem_filter_spot_area_unit' => $request->input('sem_filter_spot_area_unit'),
            'gcms_sampling_time_unit' => $request->input('gcms_sampling_time_unit'),
            'sem_parts_used_unit' => $request->input('sem_parts_used_unit'),
            'ic_parts_used_unit' => $request->input('ic_parts_used_unit'),
            'ftir_parts_used_unit' => $request->input('ftir_parts_used_unit'),
            'ic_extraction_vo_unit' => $request->input('ic_extraction_vo_unit'),
            'lpc_mdl' => $request->input('txt_lpc_mdl'),  
            'lpc_mdl_unit' => $request->input('lpc_mdl_unit'), 
            'ic_mdl' => $request->input('txt_ic_mdl'),  
            'ic_mdl_unit' => $request->input('ic_mdl_unit'),  
            'ftir_mdl' => $request->input('txt_ftir_mdl'),  
            'ftir_mdl_unit' => $request->input('ftir_mdl_unit'),  
            'sem_mdl' => $request->input('txt_sem_mdl'),  
            'sem_mdl_unit' => $request->input('sem_mdl_unit'),  
            'gcms_mdl' => $request->input('txt_gcms_mdl'),  
            'gcms_mdl_unit' => $request->input('gcms_mdl_unit'),  
            'save_date'=> date('Y-m-d H:i:s'),
            'save_user'=> 1,
            'status_model'=> 1,
        ]; 

        $getidmodel = DB::table('nt_model')->insertGetId($model_master, 'id_model');
        
        $path = storage_path('NHK-File/model-rowfile WD/'.$namefolder);
        File::makeDirectory($path, $mode = 0777, true, true);
        $modeltype = DB::table('nt_model_spec_type')->get();

        foreach ($modeltype as $item) {

            if(!empty($request->input('alpc' . $item->text)))
            { 
                $alpc = $request->input('alpc' . $item->text);
            }
            else
            {
                $alpc= "N/A";
            }
            if(!empty($request->input('class1' . $item->text)))
            { 
                $class1 = $request->input('class1' . $item->text);
            }
            else
            {
                $class1= "N/A";
            }       
            if(!empty($request->input('class2' . $item->text)))
            { 
                $class2 = $request->input('class2' . $item->text);
            }
            else
            {
                $class2= "N/A";
            }
            if(!empty($request->input('class3' . $item->text)))
            { 
                $class3 = $request->input('class3' . $item->text);
            }
            else
            {
                $class3= "N/A";
            }       
            if(!empty($request->input('class3_1' . $item->text)))
            { 
                $class3_1 = $request->input('class3_1' . $item->text);
            }
            else
            {
                $class3_1= "N/A";
            }          
            if(!empty($request->input('class3_2' . $item->text)))
            { 
                $class3_2 = $request->input('class3_2' . $item->text);
            }
            else
            {
                $class3_2= "N/A";
            }
            if(!empty($request->input('class3_3' . $item->text)))
            { 
                $class3_3 = $request->input('class3_3' . $item->text);
            }
            else
            {
                $class3_3= "N/A";
            }
            if(!empty($request->input('class4' . $item->text)))
            { 
                $class4 = $request->input('class4' . $item->text);
            }
            else
            {
                $class4= "N/A";
            }
            if(!empty($request->input('class5' . $item->text)))
            { 
                $class5 = $request->input('class5' . $item->text);
            }
            else
            {
                $class5= "N/A";
            }
            if(!empty($request->input('class5_1' . $item->text)))
            { 
                $class5_1 = $request->input('class5_1' . $item->text);
            }
            else
            {
                $class5_1= "N/A";
            }
            if(!empty($request->input('class6' . $item->text)))
            { 
                $class6 = $request->input('class6' . $item->text);
            }
            else
            {
                $class6= "N/A";
            }
            if(!empty($request->input('anion' . $item->text)))
            { 
                $anion = $request->input('anion' . $item->text);
            }
            else
            {
                $anion= "N/A";
            }
            if(!empty($request->input('sulphate' . $item->text)))
            { 
                $sulphate = $request->input('sulphate' . $item->text);
            }
            else
            {
                $sulphate= "N/A";
            }
            if(!empty($request->input('cation' . $item->text)))
            { 
                $cation = $request->input('cation' . $item->text);
            }
            else
            {
                $cation= "N/A";
            }
            if(!empty($request->input('outgassing' . $item->text)))
            { 
                $outgassing = $request->input('outgassing' . $item->text);
            }
            else
            {
                $outgassing = "N/A";
            }
            if(!empty($request->input('alkylacrylate' . $item->text)))
            { 
                $alkylacrylate = $request->input('alkylacrylate' . $item->text);
            }
            else
            {
                $alkylacrylate= "N/A";
            }
            if(!empty($request->input('low_MW' . $item->text)))
            { 
                $low_MW = $request->input('low_MW' . $item->text);
            }
            else
            {
                $low_MW= "N/A";
            }
            if(!empty($request->input('high_MW' . $item->text)))
            { 
                $high_MW = $request->input('high_MW' . $item->text);
            }
            else
            {
                $high_MW= "N/A";
            }
            if(!empty($request->input('ftir' . $item->text)))
            { 
                $ftir = $request->input('ftir' . $item->text);
            }
            else
            {
                $ftir= "N/A";
            }



            if($item->id_model_type == 1)
            {
                $model_type_excel = $request->input('alpc' . $item->text);
                $model_outgassing = $request->input('outgassing' . $item->text);
            }
            if($item->id_model_type == 3)
            {
                $model_type_excel3 = $request->input('alpc' . $item->text);
                $upper_control_limit_class  = [
                    'id_model' => $getidmodel,
                    'id_model_type' => $request->input('typeID' . $item->text),
                    'alpc' => $alpc,
                    'class1' => $class1,
                    'class2' => $class2,
                    'class3' => $class3,
                    'class3_1' => $class3_1,
                    'class3_2' => $class3_2,
                    'class3_3' => $class3_3,
                    'class4' => $class4,
                    'class5' => $class5,
                    'class5_1' => $class5_1,
                    'class6' => $class6,
                    'anion' => $anion,
                    'sulphate' => $sulphate,
                    'cation' => $cation,
                    'outgassing' => $outgassing,
                    'alkylacrylate' => $alkylacrylate,
                    'low_MW' => $low_MW,
                    'high_MW' => $high_MW,
                    'ftir' => $ftir,
                ];
            }

            if($item->id_model_type == 2)
            {
                $model_type_excel2 = $request->input('alpc' . $item->text);
                $trigger_limit_class  = [
                    'id_model' => $getidmodel,
                    'id_model_type' => $request->input('typeID' . $item->text),
                    'alpc' => $alpc,
                    'class1' => $class1,
                    'class2' => $class2,
                    'class3' => $class3,
                    'class3_1' => $class3_1,
                    'class3_2' => $class3_2,
                    'class3_3' => $class3_3,
                    'class4' => $class4,
                    'class5' => $class5,
                    'class5_1' => $class5_1,
                    'class6' => $class6,
                    'anion' => $anion,
                    'sulphate' => $sulphate,
                    'cation' => $cation,
                    'outgassing' => $outgassing,
                    'alkylacrylate' => $alkylacrylate,
                    'low_MW' => $low_MW,
                    'high_MW' => $high_MW,
                    'ftir' => $ftir,
                ];
            }
            $modelspec = [
                'id_model' => $getidmodel,
                'id_model_type' => $request->input('typeID' . $item->text),
                'alpc' => $alpc,
                'class1' => $class1,
                'class2' => $class2,
                'class3' => $class3,
                'class3_1' => $class3_1,
                'class3_2' => $class3_2,
                'class3_3' => $class3_3,
                'class4' => $class4,
                'class5' => $class5,
                'class5_1' => $class5_1,
                'class6' => $class6,
                'anion' => $anion,
                'sulphate' => $sulphate,
                'cation' => $cation,
                'outgassing' => $outgassing,
                'alkylacrylate' => $alkylacrylate,
                'low_MW' => $low_MW,
                'high_MW' => $high_MW,
                'ftir' => $ftir,
            ];
            DB::table('nt_model_spec')->insert($modelspec);
        }

        $parameter_type = DB::table('nt_model_parameter_type')->get();
        foreach ($parameter_type as $st) {
            
            $file_lpc = null;
            $file_sem = null;
            $file_ic = null;
            $file_gcms = null;
            $file_ftir = null;
            $file_semtest = null;

            if ($request->input('ck_lpc' . $st->text) == "1") {
                $input_lpc = 1;
                if($st->id_model_p == 1) {

                    $file_lpc = "LPC-" . $dateid . "-" . $namemodel.".xlsm";
                    $file_lpc_sc = "LPC-" . $dateid . "-" . $namemodel;

                    $file = storage_path('NHK-File/template/model-formet/LPCWD-format2.xlsm');

                    Excel::load($file, function ($doc)use($model_master,$namefolder) {
                        $sheet = $doc->setActiveSheetIndex(0);

                        $sheet->setCellValue('C3',$model_master['name_model']);
                        $sheet->setCellValue('C4',$model_master['part']);
                        $sheet->setCellValue('C5',$model_master['part_number']);
                        $sheet->setCellValue('C8',$model_master['surface_area']);
                        $sheet->setCellValue('C9',$model_master['alpc_extraction_vol']);
                        $sheet->setCellValue('S9',$model_master['alpc_us_power']);
                        $sheet->setCellValue('C10',$model_master['alpc_parts_used']);
                        $sheet->setCellValue('S8',$model_master['alpc_us_frequency']);
                        $sheet->setCellValue('S10',$model_master['alpc_extraction_time']);

                    })->setFilename($file_lpc_sc)->store('xlsm', storage_path('NHK-File/model-rowfile WD/' . $namefolder));
 
                }
            } else {
                $input_lpc = 0;
            }

            if ($request->input('ck_sem' . $st->text) == 1) {
                $input_sem = 1;
                if($st->id_model_p == 1) {
                   $file_sem = "SEM-" . $dateid . "-" . $namemodel.".xlsx";
                   $file_sem_sc = "SEM-" . $dateid . "-" . $namemodel;

                   $file = storage_path('NHK-File/template/model-formet/SEMWD-format.xlsx');
                    
                    Excel::load($file, function ($doc)use($model_master, $namefolder) {
                        $sheet = $doc->getActiveSheet(0);           

                        $sheet->setCellValue('B9',$model_master['name_model']);
                        $sheet->setCellValue('B11',$model_master['part']);
                        $sheet->setCellValue('B13',$model_master['part_number']);

                    })->setFilename($file_sem_sc)->store('xlsx', storage_path('NHK-File/model-rowfile WD/' . $namefolder)); 

                }
            } else {
                $input_sem = 0;
            }

            if ($request->input('ck_ic' . $st->text) == 1) {
                $input_ic = 1;
                if($st->id_model_p == 1) {
                    $file_ic = "IC-" . $dateid . "-" . $namemodel.".xlsx";
                    $file_ic_sc = "IC-" . $dateid . "-" . $namemodel;

                    $file = storage_path('NHK-File/template/model-formet/ICWD-format.xlsx');
                    Excel::load($file, function ($doc)use($model_master,$namefolder) {
                        $sheet = $doc->setActiveSheetIndex(0); 

                        $sheet->setCellValue('C4',$model_master['name_model']);
                        $sheet->setCellValue('C5',$model_master['part']);
                        $sheet->setCellValue('C6',$model_master['part_number']);
                        $sheet->setCellValue('D11',$model_master['surface_area']);
                        $sheet->setCellValue('D9',$model_master['ic_parts_used']);
                        $sheet->setCellValue('D10',$model_master['ic_extraction_vo']);

                    })->setFilename($file_ic_sc)->store('xlsx', storage_path('NHK-File/model-rowfile WD/'.$namefolder.'/'));

                }
            } else {
                $input_ic = 0;
            }

            if ($request->input('ck_gcms' . $st->text) == 1) {
                $input_gcms = 1;
                if($st->id_model_p == 1) {
                    $file_gcms = "GCMSOutgas-" . $dateid . "-" . $namemodel.".xlsx";
                    $file_gcms_sc = "GCMSOutgas-" . $dateid . "-" . $namemodel;
                    $file = storage_path('NHK-File/template/model-formet/GCMSOutgasWD-format.xlsx');
                    
                    Excel::load($file, function ($doc)use($model_master,$namefolder) {
                        $sheet = $doc->getActiveSheet(0);

                        $sheet->setCellValue('K2',$model_master['name_model']);
                        $sheet->setCellValue('K3',$model_master['part']);
                        $sheet->setCellValue('K4',$model_master['part_number']);

                    })->setFilename($file_gcms_sc)->store('xlsx', storage_path('NHK-File/model-rowfile WD/'.$namefolder.'/'));

                }
            } else {
                $input_gcms = 0;
            }

            if ($request->input('ck_ftir' . $st->text) == 1) {
                $input_ftir = 1;
                if($st->id_model_p == 1) {
                    $file_ftir = "GCMSSolvent-" . $dateid . "-" . $namemodel.".xlsx";
                    $file_ftir_sc = "GCMSSolvent-" . $dateid . "-" . $namemodel;

                    $file = storage_path('NHK-File/template/model-formet/GCMSSolventWD-format.xlsx');
                    Excel::load($file, function ($doc)use($model_master,$namefolder) {
                        $sheet = $doc->setActiveSheetIndex(0); 
                        $sheet->setCellValue('K2',$model_master['name_model']);
                        $sheet->setCellValue('K3',$model_master['part']);
                        $sheet->setCellValue('K4',$model_master['part_number']);

                    })->setFilename($file_ftir_sc)->store('xlsx', storage_path('NHK-File/model-rowfile WD/' . $namefolder.'/'));

                }
            } else {
                $input_ftir = 0;
            }

            if ($request->input('ck_semtest' . $st->text) == 1) {
                $input_semtest = 1;
                if($st->id_model_p == 1) {
                    $file_semtest = "MESA-" . $dateid . "-" . $namemodel.".xlsx";
                    $file_semtest_sc = "MESA-" . $dateid . "-" . $namemodel;
                    $file = storage_path('NHK-File/template/model-formet/MESAWD-format.xlsx');

                    Excel::load($file, function ($doc)use($model_master,$namefolder) {
                        $sheet = $doc->setActiveSheetIndex(0);
                        $sheet->setCellValue('C2',$model_master['name_model']);
                        $sheet->setCellValue('C3',$model_master['part']);
                        $sheet->setCellValue('C4',$model_master['part_number']);
                    })->setFilename($file_semtest_sc)->store('xlsx', storage_path('NHK-File/model-rowfile WD/' . $namefolder.'/'));

                }
            } else {
                $input_semtest = 0;
            }

            $addparameter_type = [
                'id_model' => $getidmodel,
                'id_model_p' => $st->id_model_p,
                'name' => $st->name,
                'lpc' => $input_lpc,
                'lpc_file' => $file_lpc,
                'sem' => $input_sem,
                'sem_file' => $file_sem,
                'ic' => $input_ic,
                'ic_file' => $file_ic,
                'gcms' => $input_gcms,
                'gcms_file' => $file_gcms,
                'ftir' => $input_ftir,
                'ftir_file' => $file_ftir,
                'semtest' => $input_semtest,
                'semtest_file' => $file_semtest,
            ];
            DB::table('nt_model_parameter')->insert($addparameter_type);
        }
        return response()->json(['success' => 1]);
        
    }

    public function viewmodelWD($id)
    {
        $nt_model = DB::table('nt_model')->where('id_model', $id)->first();
        $nt_model_spec = DB::table('nt_model_spec')
            ->join('nt_model_spec_type', 'nt_model_spec.id_model_type', '=', 'nt_model_spec_type.id_model_type')
            ->where('nt_model_spec.id_model', $id)
            ->get();
        $nt_model_parameter = DB::table('nt_model_parameter')
            ->join('nt_model_parameter_type', 'nt_model_parameter.id_model_p', '=', 'nt_model_parameter_type.id_model_p')
            ->join('nt_model', 'nt_model_parameter.id_model', '=', 'nt_model.id_model')
            ->where('nt_model_parameter.id_model', $id)
            ->get();
        $modeltype = DB::table('nt_model_spec_type')->get();
        $parameter_type = DB::table('nt_model_parameter_type')->get();

        return view('master/model/viewmodelWD', ['modeltype' => $modeltype,
            'parameterType' => $parameter_type,
            'nt_model' => $nt_model,
            'model_spec' => $nt_model_spec,
            'model_parameter' => $nt_model_parameter]
        );
    }

    public function editmodelWDSubmit(Request $request)
    {
        ini_set('memory_limit', '-1');
        set_time_limit(5000);  

        $maxid = $request->input('chkid_model');
        $dateid = date('YmdHis');
        $model_tv = DB::table('nt_model')->where('id_model', $request->input('chkid_model'))->first();
        $namemodel = $request->input('txt_name_model');
        $model_master = [
            'name_model' => $request->input('txt_name_model'),
            'model_type' => $request->input('model_type'),
            'part' => $request->input('txt_part'),
            'drive_name' => $request->input('txt_drive_name'),
			'wdqty' => $request->input('txt_wd_qty'),
            'line_type' => $request->input('txt_line_type'),
            'alpc_dc' => $request->input('txt_alpc_dc'),
            'alpc_dr' => $request->input('txt_alpc_dr'),
            'alpc_cs' => $request->input('txt_alpc_cs'),
            'sem_dc' => $request->input('txt_sem_dc'),
            'sem_dr' => $request->input('txt_sem_dr'),
            'sem_cs' => $request->input('txt_sem_cs'),
            'ic_dc' => $request->input('txt_ic_dc'),
            'ic_dr' => $request->input('txt_ic_dr'),
            'ic_cs' => $request->input('txt_ic_cs'),
            'gcms_dc' => $request->input('txt_gcms_dc'),
            'gcms_dr' => $request->input('txt_gcms_dr'),
            'gcms_cs' => $request->input('txt_gcms_cs'),
            'ftir_dc' => $request->input('txt_ftir_dc'),
            'ftir_dr' => $request->input('txt_ftir_dr'),
            'ftir_cs' => $request->input('txt_ftir_cs'),
            'vendor_id' => $request->input('txt_vendor_id'),
            'sub_supplier_name' => $request->input('txt_ssn'),
            'part_number' => $request->input('txt_part_number'),
            'part_revision' => $request->input('txt_part_revision'),
            'drawing_number' => $request->input('txt_drawing_number'),
            'drawing_revision' => $request->input('txt_drawing_revision'),
            'digit_lot_number' => $request->input('txt_dln'),
            'digit_lot_number1' => $request->input('txt_dln1'),
            'digit_lot_number2' => $request->input('txt_dln2'),
            'surface_area' => $request->input('txt_sa'),
            'ic_parts_used' => $request->input('txt_ic_pcs'),
            'ftir_parts_used' => $request->input('txt_ftir_pcs'),
            'alpc_parts_used' => $request->input('txt_alpc_pcs'),
            'sem_parts_used' => $request->input('txt_sem_pcs'),
            'gcms_parts_used' => $request->input('txt_gcms_pcs'),
            'alpc_extraction_vol' => $request->input('txt_alpc_ev'),
            'alpc_water_vol' => $request->input('txt_alpc_wv'),
            'alpc_us_power' => $request->input('txt_alpc_us'),
            'lot_digit' => $request->input('txt_lot_digit_q'),
            'alpc_hang' => $request->input('txt_alpc_hang'),
            'alpc_us_frequency' => $request->input('txt_alpc_usf'),
            'alpc_extraction_time' => $request->input('txt_alpc_et'),
            'sem_filtered_solution' => $request->input('txt_sem_fsv'),
            'sem_filter_spot_area' => $request->input('txt_sem_filter'),
            'ic_extraction_vo' => $request->input('txt_ic_ev'),
            'gcms_sampling_time' => $request->input('txt_gcms_st'),
            'alpc_sum' => $request->input('txt_alpc_sum'),
            'sem_sum' => $request->input('txt_sem_sum'),
            'ic_sum' => $request->input('txt_ic_sum'),
            'gcms_sum' => $request->input('txt_gcms_sum'),
            'ftir_sum' => $request->input('txt_ftir_sum'),   
            'surface_area_unit' => $request->input('surface_area_unit'),
            'alpc_water_vol_unit' => $request->input('alpc_water_vol_unit'),
            'alpc_extraction_vol_unit' => $request->input('alpc_extraction_vol_unit'),
            'alpc_us_power_unit' => $request->input('alpc_us_power_unit'),
            'alpc_parts_used_unit' => $request->input('alpc_parts_used_unit'),
            'alpc_us_frequency_unit' => $request->input('alpc_us_frequency_unit'),
            'alpc_extraction_time_unit' => $request->input('alpc_extraction_time_unit'),
            'sem_filtered_solution_unit' => $request->input('sem_filtered_solution_unit'),
            'gcms_parts_used_unit' => $request->input('gcms_parts_used_unit'),
            'sem_filter_spot_area_unit' => $request->input('sem_filter_spot_area_unit'),
            'gcms_sampling_time_unit' => $request->input('gcms_sampling_time_unit'),
            'sem_parts_used_unit' => $request->input('sem_parts_used_unit'),
            'ic_parts_used_unit' => $request->input('ic_parts_used_unit'),
            'ftir_parts_used_unit' => $request->input('ftir_parts_used_unit'),
            'ic_extraction_vo_unit' => $request->input('ic_extraction_vo_unit'),  
            'lpc_mdl' => $request->input('txt_lpc_mdl'),  
            'lpc_mdl_unit' => $request->input('lpc_mdl_unit'), 
            'ic_mdl' => $request->input('txt_ic_mdl'),  
            'ic_mdl_unit' => $request->input('ic_mdl_unit'),  
            'ftir_mdl' => $request->input('txt_ftir_mdl'),  
            'ftir_mdl_unit' => $request->input('ftir_mdl_unit'),  
            'sem_mdl' => $request->input('txt_sem_mdl'),  
            'sem_mdl_unit' => $request->input('sem_mdl_unit'),  
            'gcms_mdl' => $request->input('txt_gcms_mdl'),  
            'gcms_mdl_unit' => $request->input('gcms_mdl_unit'),  
            'save_date'=> date('Y-m-d H:i:s'),
            'save_user'=> 1,
        ];
        DB::table('nt_model')->where('id_model', $request->input('chkid_model'))->update($model_master);
      
        $model_first = DB::table('nt_model')->where('id_model', $request->input('chkid_model'))->first();
        $modeltype = DB::table('nt_model_spec_type')->get();
        foreach ($modeltype as $item) {
           
            if(!empty($request->input('alpc' . $item->text)))
            { 
                $alpc = $request->input('alpc' . $item->text);
            }
            else
            {
                $alpc= "N/A";
            }
            if(!empty($request->input('class1' . $item->text)))
            { 
                $class1 = $request->input('class1' . $item->text);
            }
            else
            {
                $class1= "N/A";
            }       
            if(!empty($request->input('class2' . $item->text)))
            { 
                $class2 = $request->input('class2' . $item->text);
            }
            else
            {
                $class2= "N/A";
            }
            if(!empty($request->input('class3' . $item->text)))
            { 
                $class3 = $request->input('class3' . $item->text);
            }
            else
            {
                $class3= "N/A";
            }       
            if(!empty($request->input('class3_1' . $item->text)))
            { 
                $class3_1 = $request->input('class3_1' . $item->text);
            }
            else
            {
                $class3_1= "N/A";
            }          
            if(!empty($request->input('class3_2' . $item->text)))
            { 
                $class3_2 = $request->input('class3_2' . $item->text);
            }
            else
            {
                $class3_2= "N/A";
            }
            if(!empty($request->input('class3_3' . $item->text)))
            { 
                $class3_3 = $request->input('class3_3' . $item->text);
            }
            else
            {
                $class3_3= "N/A";
            }
            if(!empty($request->input('class4' . $item->text)))
            { 
                $class4 = $request->input('class4' . $item->text);
            }
            else
            {
                $class4= "N/A";
            }
            if(!empty($request->input('class5' . $item->text)))
            { 
                $class5 = $request->input('class5' . $item->text);
            }
            else
            {
                $class5= "N/A";
            }
            if(!empty($request->input('class5_1' . $item->text)))
            { 
                $class5_1 = $request->input('class5_1' . $item->text);
            }
            else
            {
                $class5_1= "N/A";
            }
            if(!empty($request->input('class6' . $item->text)))
            { 
                $class6 = $request->input('class6' . $item->text);
            }
            else
            {
                $class6= "N/A";
            }
            if(!empty($request->input('anion' . $item->text)))
            { 
                $anion = $request->input('anion' . $item->text);
            }
            else
            {
                $anion= "N/A";
            }
            if(!empty($request->input('sulphate' . $item->text)))
            { 
                $sulphate = $request->input('sulphate' . $item->text);
            }
            else
            {
                $sulphate= "N/A";
            }
            if(!empty($request->input('cation' . $item->text)))
            { 
                $cation = $request->input('cation' . $item->text);
            }
            else
            {
                $cation= "N/A";
            }
            if(!empty($request->input('outgassing' . $item->text)))
            { 
                $outgassing = $request->input('outgassing' . $item->text);
            }
            else
            {
                $outgassing = "N/A";
            }
            if(!empty($request->input('alkylacrylate' . $item->text)))
            { 
                $alkylacrylate = $request->input('alkylacrylate' . $item->text);
            }
            else
            {
                $alkylacrylate= "N/A";
            }
            if(!empty($request->input('low_MW' . $item->text)))
            { 
                $low_MW = $request->input('low_MW' . $item->text);
            }
            else
            {
                $low_MW= "N/A";
            }
            if(!empty($request->input('high_MW' . $item->text)))
            { 
                $high_MW = $request->input('high_MW' . $item->text);
            }
            else
            {
                $high_MW= "N/A";
            }
            if(!empty($request->input('ftir' . $item->text)))
            { 
                $ftir = $request->input('ftir' . $item->text);
            }
            else
            {
                $ftir= "N/A";
            }
            if($item->id_model_type == 1)
            {
                $model_type_excel = $request->input('alpc' . $item->text);
                $model_outgassing = $request->input('outgassing' . $item->text);
            }
            if($item->id_model_type == 2)
            {
                $model_type_excel2 = $request->input('alpc' . $item->text);
                $trigger_limit_class  = [
                    'id_model_type' => $request->input('typeID' . $item->text),
                    'alpc' => $alpc,
                    'class1' => $class1,
                    'class2' => $class2,
                    'class3' => $class3,
                    'class3_1' => $class3_1,
                    'class3_2' => $class3_2,
                    'class3_3' => $class3_3,
                    'class4' => $class4,
                    'class5' => $class5,
                    'class5_1' => $class5_1,
                    'class6' => $class6,
                    'anion' => $anion,
                    'sulphate' => $sulphate,
                    'cation' => $cation,
                    'outgassing' => $outgassing,
                    'alkylacrylate' => $alkylacrylate,
                    'low_MW' => $low_MW,
                    'high_MW' => $high_MW,
                    'ftir' => $ftir,
                ];
            }
            if($item->id_model_type == 3)
            {
                $model_type_excel3 = $request->input('alpc' . $item->text);
                $upper_control_limit_class  = [  
                    'id_model_type' => $request->input('typeID' . $item->text),
                    'alpc' => $alpc,
                    'class1' => $class1,
                    'class2' => $class2,
                    'class3' => $class3,
                    'class3_1' => $class3_1,
                    'class3_2' => $class3_2,
                    'class3_3' => $class3_3,
                    'class4' => $class4,
                    'class5' => $class5,
                    'class5_1' => $class5_1,
                    'class6' => $class6,
                    'anion' => $anion,
                    'sulphate' => $sulphate,
                    'cation' => $cation,
                    'outgassing' => $outgassing,
                    'alkylacrylate' => $alkylacrylate,
                    'low_MW' => $low_MW,
                    'high_MW' => $high_MW,
                    'ftir' => $ftir,
                ];
            }
            $modelspec = [
                'alpc' => $alpc,
                'class1' => $class1,
                'class2' => $class2,
                'class3' => $class3,
                'class3_1' => $class3_1,
                'class3_2' => $class3_2,
                'class3_3' => $class3_3,
                'class4' => $class4,
                'class5' => $class5,
                'class5_1' => $class5_1,
                'class6' => $class6,
                'anion' => $anion,
                'sulphate' => $sulphate,
                'cation' => $cation,
                'outgassing' => $outgassing,
                'alkylacrylate' => $alkylacrylate,
                'low_MW' => $low_MW,
                'high_MW' => $high_MW,
                'ftir' => $ftir,
            ];      
            $idspec = $request->input('chkidspec' . $item->text);
            DB::table('nt_model_spec')->where('id_model_spec', $idspec)->update($modelspec);
        }  
        
        $parameter_type = DB::table('nt_model_parameter_type')->get();
        foreach ($parameter_type as $st) {
            
            $modeltype = DB::table('nt_model_parameter')
            ->where('id_model',$request->input('chkid_model'))
            ->where('id_model_p',$st->id_model_p)
            ->first();
            
            if ($request->input('ck_lpc' . $st->text) == "1") {
                if($modeltype->lpc == 0 )
                {
                    $filename_lpc = "LPC-" . $dateid . "-" . $namemodel.".xlsm";
                    $lpcname = "LPC-" . $dateid . "-" . $namemodel;
                }
                else
                {
                    File::Delete('local/storage/NHK-File/model-rowfile WD/'.$model_first->file_folder.'/'.$modeltype->lpc_file);
                    $filename_lpc = "LPC-" . $dateid . "-" . $namemodel.".xlsm";
                    $lpcname = "LPC-" . $dateid . "-" . $namemodel;
                }

                $input_lpc = 1;
                if($st->id_model_p == 1) {
                    $file = storage_path('NHK-File/template/model-formet/LPCWD-format2.xlsm');
                    Excel::load($file, function ($doc)use($model_master,$model_type_excel,$model_type_excel2,$model_type_excel3) {
                        $sheet = $doc->setActiveSheetIndex(0);

                        $sheet->setCellValue('C3',$model_master['name_model']);
                        $sheet->setCellValue('C4',$model_master['part']);
                        $sheet->setCellValue('C5',$model_master['part_number']);
                        $sheet->setCellValue('C8',$model_master['surface_area']);
                        $sheet->setCellValue('C9',$model_master['alpc_extraction_vol']);
                        $sheet->setCellValue('S9',$model_master['alpc_us_power']);
                        $sheet->setCellValue('C10',$model_master['alpc_parts_used']);
                        $sheet->setCellValue('S8',$model_master['alpc_us_frequency']);
                        $sheet->setCellValue('S10',$model_master['alpc_extraction_time']);

                    })->setFilename($lpcname)->store('xlsm', storage_path('NHK-File/model-rowfile WD/'.$model_first->file_folder));
                  
               }
            } else {
                $input_lpc = 0;
                $filename_lpc = null;
                $myFile = 'local/storage/NHK-File/model-rowfile WD/'.$model_first->file_folder.'/'.$modeltype->lpc_file;
                File::delete($myFile);

            }

            if ($request->input('ck_sem' . $st->text) == 1) {

                if($modeltype->sem == 0 )
                {
                    $filename_sem = "SEM-" . $dateid . "-" . $namemodel.".xlsx";
                    $semname = "SEM-" . $dateid . "-" . $namemodel;
                }
                else
                {
                    File::Delete('local/storage/NHK-File/model-rowfile WD/'.$model_first->file_folder.'/'.$modeltype->sem_file);
                        $filename_sem = "SEM-" . $dateid . "-" . $namemodel.".xlsx";
                        $semname = "SEM-" . $dateid . "-" . $namemodel;
                }

                $sem_file_folder = $model_first->file_folder;
                $input_sem = 1;
                if($st->id_model_p == 1) {

                    $file = storage_path('NHK-File/template/model-formet/SEMWD-format.xlsx');
                    Excel::load($file, function ($doc)use($model_master, $upper_control_limit_class,$trigger_limit_class) {
                        $sheet = $doc->setActiveSheetIndex(0); 

                        $sheet->setCellValue('B9',$model_master['name_model']);
                        $sheet->setCellValue('B11',$model_master['part']);
                        $sheet->setCellValue('B13',$model_master['part_number']);

                    })->setFilename($semname)->store('xlsx', storage_path('NHK-File/model-rowfile WD/' . $sem_file_folder)); 
                }
            } else {
                $input_sem = 0;
                $filename_sem = null;
                $myFile = 'local/storage/NHK-File/model-rowfile WD/'.$model_first->file_folder.'/'.$modeltype->sem_file;
                File::delete($myFile);
            }

            if ($request->input('ck_ic' . $st->text) == 1) {      
                if($modeltype->ic == 0 )
                {
                    $filename_ic = "IC-" . $dateid . "-" . $namemodel.".xlsx";
                    $icname = "IC-" . $dateid . "-" . $namemodel;
                }
                else
                {
                    File::Delete('local/storage/NHK-File/model-rowfile WD/'.$model_first->file_folder.'/'.$modeltype->ic_file);
                        $filename_ic = "IC-" . $dateid . "-" . $namemodel.".xlsx";
                        $icname = "IC-" . $dateid . "-" . $namemodel;
                }
                $input_ic = 1;
                if($st->id_model_p == 1) {

                    $file = storage_path('NHK-File/template/model-formet/ICWD-format.xlsx');
                    Excel::load($file, function ($doc)use($model_master) {
                        $sheet = $doc->setActiveSheetIndex(0);
                        $sheet->setCellValue('C4',$model_master['name_model']);
                        $sheet->setCellValue('C5',$model_master['part']);
                        $sheet->setCellValue('C6',$model_master['part_number']);
                        $sheet->setCellValue('D11',$model_master['surface_area']);
                        $sheet->setCellValue('D9',$model_master['ic_parts_used']);
                        $sheet->setCellValue('D10',$model_master['ic_extraction_vo']);
                    })->setFilename($icname)->store('xlsx', storage_path('NHK-File/model-rowfile WD/'. $model_first->file_folder.'/'));

                }
            } else {
                $input_ic = 0;
                $filename_ic = null;
                $myFile = 'local/storage/NHK-File/model-rowfile WD/'.$model_first->file_folder.'/'.$modeltype->ic_file;
                File::delete($myFile);
            }

            if ($request->input('ck_gcms' . $st->text) == 1) {
                 if($modeltype->gcms == 0 )
                {
                    $filename_gcms = "GCMSOutgas-" . $dateid . "-" . $namemodel.".xlsx";
                    $gcmsname = "GCMSOutgas-" . $dateid . "-" . $namemodel;   
                }
                else
                {
                    File::Delete('local/storage/NHK-File/model-rowfile WD/'.$model_first->file_folder.'/'.$modeltype->gcms_file);
                        $filename_gcms = "GCMSOutgas-" . $dateid . "-" . $namemodel.".xlsx";
                        $gcmsname = "GCMSOutgas-" . $dateid . "-" . $namemodel;   
                }

                $input_gcms = 1;
                if($st->id_model_p == 1) {
                    
                    $file = storage_path('NHK-File/template/model-formet/GCMSOutgasWD-format.xlsx');
                    Excel::load($file, function ($doc)use($model_master,$model_outgassing) {
                        $sheet = $doc->setActiveSheetIndex(0);

                        $sheet->setCellValue('K2',$model_master['name_model']);
                        $sheet->setCellValue('K3',$model_master['part']);
                        $sheet->setCellValue('K4',$model_master['part_number']);

                    })->setFilename($gcmsname)->store('xlsx', storage_path('NHK-File/model-rowfile WD/'.$model_first->file_folder.'/'));

                }

            } else {
                $input_gcms = 0;
                $filename_gcms = null;
                $myFile = 'local/storage/NHK-File/model-rowfile WD/'.$model_first->file_folder.'/'.$modeltype->gcms_file;
                File::delete($myFile);
            }

            if ($request->input('ck_ftir' . $st->text) == 1) {
                    if($modeltype->ftir == 0 )
                    {
                        $filename_ftir = "GCMSSolvent-" . $dateid . "-" . $namemodel.".xlsx";
                        $ftirname = "GCMSSolvent-" . $dateid . "-" . $namemodel;
                    }
                    else
                    {
                        File::Delete('local/storage/NHK-File/model-rowfile WD/'.$model_first->file_folder.'/'.$modeltype->ftir_file);
                            $filename_ftir = "GCMSSolvent-" . $dateid . "-" . $namemodel.".xlsx";
                            $ftirname = "GCMSSolvent-" . $dateid . "-" . $namemodel;
                    }
                $input_ftir = 1;
                if($st->id_model_p == 1) {

                    $file = storage_path('NHK-File/template/model-formet/GCMSSolventWD-format.xlsx');
                    Excel::load($file, function ($doc)use($model_master) {
                        $sheet = $doc->setActiveSheetIndex(0);
                        $sheet->setCellValue('K2',$model_master['name_model']);
                        $sheet->setCellValue('K3',$model_master['part']);
                        $sheet->setCellValue('K4',$model_master['part_number']);
                    })->setFilename($ftirname)->store('xlsx', storage_path('NHK-File/model-rowfile WD/' .$model_first->file_folder.'/'));

                }
            } else {
                $input_ftir = 0;
                $filename_ftir = null;
                $myFile = 'local/storage/NHK-File/model-rowfile WD/'.$model_first->file_folder.'/'.$modeltype->ftir_file;
                File::delete($myFile);
            }

            if ($request->input('ck_semtest' . $st->text) == 1) {
                if($modeltype->semtest == 0 )
                {
                    $filename_semtest = "MESA-" . $dateid . "-" . $namemodel.".xlsx";
                    $semtestname = "MESA-" . $dateid . "-" . $namemodel;
                }
                else
                {
                    File::Delete('local/storage/NHK-File/model-rowfile WD/'.$model_first->file_folder.'/'.$modeltype->semtest_file);
                        $filename_semtest = "MESA-" . $dateid . "-" . $namemodel.".xlsx";
                        $semtestname = "MESA-" . $dateid . "-" . $namemodel;
                }
                $input_semtest = 1;
                if($st->id_model_p == 1) {

                    $file = storage_path('NHK-File/template/model-formet/MESAWD-format.xlsx');
                    Excel::load($file, function ($doc)use($model_master) {
                        $sheet = $doc->setActiveSheetIndex(0);

                        $sheet->setCellValue('C2',$model_master['name_model']);
                        $sheet->setCellValue('C3',$model_master['part']);
                        $sheet->setCellValue('C4',$model_master['part_number']);

                    })->setFilename($semtestname)->store('xlsx', storage_path('NHK-File/model-rowfile WD/' .$model_first->file_folder.'/'));

                }

            } else {
                $input_semtest = 0;
                $filename_semtest = null;
                $myFile = 'local/storage/NHK-File/model-rowfile WD/'.$model_first->file_folder.'/'.$modeltype->semtest_file;
                File::delete($myFile);
            }
            
            $addparameter_type = [
                'lpc' => $input_lpc,
                'lpc_file' => $filename_lpc,
                'sem' => $input_sem,
                'sem_file' => $filename_sem,
                'ic' => $input_ic,
                'ic_file' => $filename_ic,
                'gcms' => $input_gcms,
                'gcms_file' => $filename_gcms,
                'ftir' => $input_ftir,
                'ftir_file' => $filename_ftir,
                'semtest' => $input_semtest,
                'semtest_file' => $filename_semtest,
            ];
            $idparameter = $request->input('ckidparameter' . $st->text);
            DB::table('nt_model_parameter')->where('id_m_parameter', $idparameter)->update($addparameter_type);
        }
        return response()->json(['success' => 1,'test'=>$filename_gcms]);
    }
    
    public function editmodelWD($id,$chk)
    {
        $nt_model = DB::table('nt_model')->where('id_model', $id)->first();
        $model_volume = DB::table('nt_model_unit_volume')->where('status',1)->get();
        $model_area = DB::table('nt_model_unit_area')->where('status',1)->get();
        $model_electric = DB::table('nt_model_unit_electric')->where('status',1)->get();
        $model_part = DB::table('nt_model_unit_part')->where('status',1)->get();
        $model_part_mdl = DB::table('nt_model_unit_mdl_part')->where('status',1)->get(); // NHK2 edit 23/02/24
        $model_time = DB::table('nt_model_unit_time')->where('status',1)->get();
        $nt_model_spec = DB::table('nt_model_spec')
            ->join('nt_model_spec_type', 'nt_model_spec.id_model_type', '=', 'nt_model_spec_type.id_model_type')
            ->where('nt_model_spec.id_model', $id)
            ->get();
        $nt_model_parameter = DB::table('nt_model_parameter')
            ->join('nt_model_parameter_type', 'nt_model_parameter.id_model_p', '=', 'nt_model_parameter_type.id_model_p')
            ->where('nt_model_parameter.id_model', $id)
            ->get();
        $modeltype = DB::table('nt_model_spec_type')->get();
        $parameter_type = DB::table('nt_model_parameter_type')->get();

        return view('master/model/editmodelWD', ['modeltype' => $modeltype,
            'parameterType' => $parameter_type,
            'nt_model' => $nt_model,
            'model_spec' => $nt_model_spec,
            'model_parameter' => $nt_model_parameter,
            'model_volume'=>$model_volume,
            'model_area'=>$model_area,
            'model_electric'=>$model_electric,
            'model_part'=>$model_part,
            'model_part_mdl'=>$model_part_mdl, // NHK2 edit 23/02/24
            'model_time'=>$model_time,
            'chk' => $chk
            ]
        );
    }

    //----------- End WD -------------

    public function calculateoption(){
        return view('master/model/calculateoption');
    }
    public function combinedata()
    {
        return view('master/model/combine');
    }

    public function tgahistory()
    {
        return view('master/model/tgadate');
    }

}
