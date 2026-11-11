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
        return Datatables::of(DB::table('nt_model')->where('status_model', 1))->make(true);
    }
    public function historydatatable(Request $request)	
    {
        // $data = DB::table('nt_model_history')
        // ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
        // ->where('nt_model_history.id_model', $request->modelid)
        // ->get();
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
        return view('master/model/addmodel', 
        [
         'modeltype' => $modeltype,
         'parameterType' => $parameter_type,
         'model_volume'=>$model_volume,
         'model_area'=>$model_area,
         'model_electric'=>$model_electric,
         'model_part'=>$model_part,
         'model_time'=>$model_time,
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
                    $filename_lpc = "LPC-" . $dateid . "-" . $namemodel.".xlsx";
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
                        $sheet->setCellValue('U27',$model_master['digit_lot_number1']);
                        $sheet->setCellValue('V27',$model_master['digit_lot_number2']);
                        $sheet->setCellValue('R28',$model_master['lot_digit']);
                        $sheet->setCellValue('R30',$model_master['part_revision']);
                    })->setFilename($lpcname)->store('xlsm', storage_path('NHK-File/model-rowfile/'.$model_first->file_folder));
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
                    })->setFilename($semname)->store('xlsx', storage_path('NHK-File/model-rowfile/' . $sem_file_folder));
                    
                  
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
                        $sheet->setCellValue('W4',$model_master['digit_lot_number1']);
                        $sheet->setCellValue('X4',$model_master['digit_lot_number2']);
                        $sheet->setCellValue('V5',$model_master['lot_digit']);
                    })->setFilename($icname)->store('xlsx', storage_path('NHK-File/model-rowfile/'. $model_first->file_folder.'/'));
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
                    Excel::load($file, function ($doc)use($model_master,$model_outgassing) {
                        $sheet = $doc->setActiveSheetIndex(3);
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
                    })->setFilename($gcmsname)->store('xlsx', storage_path('NHK-File/model-rowfile/'.$model_first->file_folder.'/'));
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
                    Excel::load($file, function ($doc)use($model_master) {
                        $sheet = $doc->setActiveSheetIndex(0);
                        $sheet->setCellValue('B3',$model_master['name_model']);
                        $sheet->setCellValue('B4',$model_master['part_number']);
                        $sheet->setCellValue('B5',$model_master['part']);
                        $sheet->setCellValue('B6',$model_master['digit_lot_number']);
                        $sheet->setCellValue('C6',$model_master['digit_lot_number1']);
                        $sheet->setCellValue('D6',$model_master['digit_lot_number2']);
                        $sheet->setCellValue('B7',$model_master['surface_area']);
                        $sheet->setCellValue('B8',$model_master['ftir_parts_used']);
                        $sheet->setCellValue('B9',$model_master['lot_digit']);

                    })->setFilename($ftirname)->store('xlsx', storage_path('NHK-File/model-rowfile/' .$model_first->file_folder.'/'));
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
                    Excel::load($file, function ($doc)use($model_master) {
                        $sheet = $doc->setActiveSheetIndex(0);
                        $sheet->setCellValue('C3',$model_master['name_model']);
                        $sheet->setCellValue('C4',$model_master['part']);
                        
                            $sheet->setCellValue('J14',$model_master['part_number']);
                            $sheet->setCellValue('J15',$model_master['digit_lot_number']);
                            $sheet->setCellValue('K15',$model_master['digit_lot_number1']);
                            $sheet->setCellValue('L15',$model_master['digit_lot_number2']);
                            $sheet->setCellValue('J16',$model_master['lot_digit']);

                    })->setFilename($semtestname)->store('xlsx', storage_path('NHK-File/model-rowfile/' .$model_first->file_folder.'/'));
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
   

}
