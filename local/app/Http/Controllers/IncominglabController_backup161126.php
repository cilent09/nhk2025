<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
use DateTime;
use Session;
use Response;
use Datatables;
use Hash;
use Dompdf\Dompdf;
use Excel;
use File;
use App\Mail\Mailnhk;
use nhkviewlpc;
use nhkviewsem;
use nhkviewic;
use nhkviewgcms;
use nhkviewftir;
use nhkviewsemtest;
use nhkmail;
use slope;

use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class IncominglabController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

     public function datatable(Request $request)
    {
        // dd($request->textmodel.'<>'.$request->textlot);
       if(empty($request->date_start) && empty($request->date_end) && empty($request->textmodel) && empty($request->textlot))
       {
            $now = date("Y-m-d 00:00:00");
            $start = date('Y-m-d H:i:s', strtotime($now));
            $end =date('Y-m-d H:i:s', strtotime('+1 day', strtotime($now)));
            $datalist = DB::table('nt_oqa_lab')
            ->join('nt_model', 'nt_model.id_model', '=', 'nt_oqa_lab.id_model')
            ->join('nt_oqa_file', 'nt_oqa_file.id_createlab', '=', 'nt_oqa_lab.id_createlab')
            ->where('nt_oqa_lab.save_date','>=',$start)->where('nt_oqa_lab.save_date','<=',$end)
            ->where('nt_oqa_lab.type_lab','like','SGT_%')
            ->select('nt_model.*','nt_oqa_lab.id_createlab','nt_oqa_lab.type_lab','nt_oqa_lab.name_lab',
            'nt_oqa_lab.code_lab',
            'nt_oqa_lab.lotnumber_lab',
            'nt_oqa_lab.lpc_confirm_st',
            'nt_oqa_lab.sem_confirm_st',
            'nt_oqa_lab.ic_confirm_st',
            'nt_oqa_lab.gcms_confirm_st',
            'nt_oqa_lab.ftir_confirm_st',
            'nt_oqa_lab.semtest_confirm_st',
            'nt_oqa_file.st_lpc',
            'nt_oqa_file.st_sem',
            'nt_oqa_file.st_ic',
            'nt_oqa_file.st_gcms',
            'nt_oqa_file.st_ftir',
            'nt_oqa_file.st_semtest',
            'nt_oqa_lab.tga',
            'nt_oqa_lab.save_date as starttime');
       }
       else
       {
            $start = date('Y-m-d H:i:s', strtotime($request->date_start));
            $end = date('Y-m-d H:i:s', strtotime($request->date_end. "+1 days"));
            $datalist = DB::table('nt_oqa_lab')
            ->join('nt_model', 'nt_model.id_model', '=', 'nt_oqa_lab.id_model')
            ->join('nt_oqa_file', 'nt_oqa_file.id_createlab', '=', 'nt_oqa_lab.id_createlab');
            if(!empty($request->date_start) && !empty($request->date_end))
            {
                $datalist->where('nt_oqa_lab.save_date','>=',$start)->where('nt_oqa_lab.save_date','<=',$end);
            }
            if(!empty($request->textmodel))
            {
                $datalist->where('nt_model.name_model','like','%'.$request->textmodel.'%');
            }
            if(!empty($request->textlot))
            {
                $datalist->where('nt_oqa_lab.lotnumber_lab','like','%'.$request->textlot.'%');
            }
            $datalist->where('nt_oqa_lab.type_lab','like','SGT_%');
            $datalist->select('nt_model.*','nt_oqa_lab.id_createlab','nt_oqa_lab.type_lab','nt_oqa_lab.name_lab',
            'nt_oqa_lab.code_lab',
            'nt_oqa_lab.lotnumber_lab',
            'nt_oqa_lab.lpc_confirm_st',
            'nt_oqa_lab.sem_confirm_st',
            'nt_oqa_lab.ic_confirm_st',
            'nt_oqa_lab.gcms_confirm_st',
            'nt_oqa_lab.ftir_confirm_st',
            'nt_oqa_lab.semtest_confirm_st',
            'nt_oqa_file.st_lpc',
            'nt_oqa_file.st_sem',
            'nt_oqa_file.st_ic',
            'nt_oqa_file.st_gcms',
            'nt_oqa_file.st_ftir',
            'nt_oqa_file.st_semtest',
            'nt_oqa_lab.tga',
            'nt_oqa_lab.save_date as starttime');
       }

       $sQuery	= Datatables::of($datalist)
       ->addIndexColumn()
       ->addColumn('starttimeas',function($data){
           $date = date('d-m-Y H:i:s', strtotime($data->starttime));
           return $date;
        });

       //return Datatables::of($datalist)->make(true);
       return $sQuery->escapeColumns([])->make(true);

    }

    public function split($id)
    {

        $createlab = DB::table('nt_oqa_lab')
            ->join('nt_model', 'nt_model.id_model', '=', 'nt_oqa_lab.id_model')
            ->where('id_createlab',$id)->select('nt_oqa_lab.*','nt_model.*','nt_oqa_lab.save_date as starttime')->first();
        $datapdf = '<!DOCTYPE html>
        <html>
        <title>split Inconming LAB</title>
        <center>  <img width="40%" class="img-fluid" src="template/assets/images/logonhkbig.png" alt="Theme-Logo" /></center>
        <p>----------------------------------</p>
        <h6 align="left">Date :  ' . date('d-m-Y H:i:s',strtotime($createlab->starttime)) . '</h6>
        <h6 align="left">Name :  ' . $createlab->name_lab . '</h6>
        <h6 align="left">Model :  ' . $createlab->name_model . ' (' . $createlab->part . ')</h6>
        <h6 align="left">Lot Number :  ' . $createlab->lotnumber_lab . '</h6>
        <p>----------------------------------</p>
        </html>';
        $dompdf = new Dompdf();
        $dompdf->loadHtml($datapdf);
        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A7', 'h6');
        // Render the HTML as PDF
        $dompdf->render();
        // Output the generated PDF to Browser
        //$dompdf->stream();
        $dompdf->stream("InconmingLAB_".$createlab->save_date, array("Attachment" => 0));
       // $dompdf->stream("InconmingLAB_", array("Attachment" => 0));
    }
    public function index()
    {
        if (!Session::has('namemenu')) {
            return redirect()->route('welcome');
        }
        $mail = new MailController();
        $mail->mailmonitoring();

        $Calculate = new CalculationController();
        $Calculate->check_value_avc_lpc();

        return view('OQA/index');
    }
    public function createlab()
    {
        $model = DB::table('nt_model')->where('status_model',1)->get();
        //$model = DB::table('nt_model')->where('status_model',1)->get();
        return view('OQA/createlab', ['model' =>$model]);
    }

    public function chkmodel(Request $request)
    {
        $id = $request->input('id');
        $model = DB::table('nt_model')->where('id_model',$id)->first();
        $model_p = DB::table('nt_model_parameter')->where('id_model',$id)->where('id_model_p',1)->first();
        return response()->json(['model' =>  $model,'model_p'=> $model_p]);
    }

    // public function chkdaterun(Request $request)
    // {
    //     //dd('oqa');
    //     $chkmodel = DB::table('nt_model_parameter')->where('id_model',$request->input('chkmodel'))->first();
    //     $countlot = DB::table('nt_oqa_lab')
    //     ->join('nt_oqa_file', 'nt_oqa_file.id_createlab', '=', 'nt_oqa_lab.id_createlab')
    //     ->where('nt_oqa_lab.lotnumber_lab',$request->input('txt_lotnumber'))->first();

    //     if( $request->input('chkaddid') == 1 )
    //     {
    //         $datetimenow = date("Y/m/d");
    //         $dateshowview = "";
    //     }
    //     else
    //     {
    //        if( $request->input('specialdate') != null)
    //        {
    //        $datetimenow = date( "Y/m/d", strtotime($request->input('specialdate')));
    //        $dateshowview = date( "d/m/Y H:i:s", strtotime($request->input('specialdate')));
    //        }
    //        else{
    //         $datetimenow = date("Y/m/d");
    //        }
    //     }
    //     $idmodel = $request->input('chkmodel');
    //     $date_w =  date('w', strtotime($datetimenow));
    //     $datenow = date( $datetimenow);
    //     if($date_w == "0") {$ckdate = 2; $stckdate = 1;}
    //     else if($date_w == "1" ){ $ckdate = 3; $stckdate = 2;}
    //     else if($date_w == "2" ){ $ckdate = 4; $stckdate = 3;}
    //     else if($date_w == "3" ){ $ckdate = 5; $stckdate = 4;}
    //     else if($date_w == "4" ){ $ckdate = 6; $stckdate = 5;}
    //     else if($date_w == "5" ){ $ckdate = 7; $stckdate = 6;}
    //     else if($date_w == "6" ){ $ckdate = 1; $stckdate = 0;}
    //     else{$ckdate=0;}
    //     $endday = 7-$ckdate;
    //     $startday = $stckdate+0;
    //     $datestart = date ("Y-m-d", strtotime("-".$startday." day", strtotime($datenow)));
    //     $dateend = date ("Y-m-d", strtotime("+".$endday." day", strtotime($datenow)));

    //     $namemodel = DB::table('nt_model')
    //     ->where('id_model', $idmodel)->first();
    //     $chktext1 = mb_substr($namemodel->name_model,0,-3);
    //    $namemodel1 = DB::table('nt_model')
    //     ->where('name_model', 'like',  $chktext1 . '%' )->get();
    //     if($namemodel1[0]->part == "UP")
    //     {
    //         $model1 = $namemodel1[0]->id_model;
    //     }
    //     else
    //     {
    //         $model1 = $namemodel1[1]->id_model;
    //     }
    //     if($namemodel1[1]->part == "DN")
    //     {
    //         $model2 = $namemodel1[1]->id_model;
    //     }
    //     else
    //     {
    //         $model2 = $namemodel1[0]->id_model;
    //     }
    //     $countdate = DB::table('nt_oqa_lab')
    //     ->where('id_model', $model1)
    //     ->whereBetween('save_date', array($datestart, $dateend))->count();
    //     $countdate1 = DB::table('nt_oqa_lab')
    //     ->where('id_model', $model2)
    //     ->whereBetween('save_date', array($datestart, $dateend))->count();

    //     $getcreate = DB::table('nt_oqa_lab')
    //     ->where('id_model', $model1)
    //     ->whereBetween('save_date', array($datestart, $dateend))
    //     ->first();
    //     $getcreate1 = DB::table('nt_oqa_lab')
    //     ->where('id_model', $model2)
    //     ->whereBetween('save_date', array($datestart, $dateend))
    //     ->first();

    //     $chkday = DB::table('nt_oqa_lab')
    //     ->join('nt_oqa_file', 'nt_oqa_file.id_createlab', '=', 'nt_oqa_lab.id_createlab')
    //     ->where('nt_oqa_lab.id_model', $model1)
    //     ->where('nt_oqa_file.date_lpc', $datetimenow)
    //     ->count();
    //     $chkday1 = DB::table('nt_oqa_lab')
    //     ->join('nt_oqa_file', 'nt_oqa_file.id_createlab', '=', 'nt_oqa_lab.id_createlab')
    //     ->where('nt_oqa_lab.id_model', $model2)
    //     ->where('nt_oqa_file.date_lpc', $datetimenow)
    //     ->count();

    //     $chksemtestday = DB::table('nt_oqa_lab')
    //     ->join('nt_oqa_file', 'nt_oqa_file.id_createlab', '=', 'nt_oqa_lab.id_createlab')
    //     ->where('nt_oqa_lab.id_model', $model1)
    //     ->where('nt_oqa_file.date_semtest', $datetimenow)
    //     ->count();
    //     $chksemtestday1 = DB::table('nt_oqa_lab')
    //     ->join('nt_oqa_file', 'nt_oqa_file.id_createlab', '=', 'nt_oqa_lab.id_createlab')
    //     ->where('nt_oqa_lab.id_model', $model2)
    //     ->where('nt_oqa_file.date_semtest', $datetimenow)
    //     ->count();

    //     $chkdaysem = DB::table('nt_oqa_lab')
    //     ->join('nt_oqa_file', 'nt_oqa_file.id_createlab', '=', 'nt_oqa_lab.id_createlab')
    //     ->where('nt_oqa_lab.id_model', $model1)
    //     ->whereBetween('nt_oqa_file.date_sem', array($datestart, $dateend))
    //     ->count();
    //     $chkdaysem1 = DB::table('nt_oqa_lab')
    //     ->join('nt_oqa_file', 'nt_oqa_file.id_createlab', '=', 'nt_oqa_lab.id_createlab')
    //     ->where('nt_oqa_lab.id_model', $model2)
    //     ->whereBetween('nt_oqa_file.date_sem', array($datestart, $dateend))
    //     ->count();
    //     $chkdayic = DB::table('nt_oqa_lab')
    //     ->join('nt_oqa_file', 'nt_oqa_file.id_createlab', '=', 'nt_oqa_lab.id_createlab')
    //     ->where('nt_oqa_lab.id_model', $model1)
    //     ->whereBetween('nt_oqa_file.date_ic', array($datestart, $dateend))
    //     ->count();
    //     $chkdayic1 = DB::table('nt_oqa_lab')
    //     ->join('nt_oqa_file', 'nt_oqa_file.id_createlab', '=', 'nt_oqa_lab.id_createlab')
    //     ->where('nt_oqa_lab.id_model', $model2)
    //     ->whereBetween('nt_oqa_file.date_ic', array($datestart, $dateend))
    //     ->count();

    //     $chkdaygcms = DB::table('nt_oqa_lab')
    //     ->join('nt_oqa_file', 'nt_oqa_file.id_createlab', '=', 'nt_oqa_lab.id_createlab')
    //     ->where('nt_oqa_lab.id_model', $model1)
    //     ->whereBetween('nt_oqa_file.date_gcms', array($datestart, $dateend))
    //     ->count();
    //     $chkdaygcms1 = DB::table('nt_oqa_lab')
    //     ->join('nt_oqa_file', 'nt_oqa_file.id_createlab', '=', 'nt_oqa_lab.id_createlab')
    //     ->where('nt_oqa_lab.id_model', $model2)
    //     ->whereBetween('nt_oqa_file.date_gcms', array($datestart, $dateend))
    //     ->count();

    //     $chkdayftir = DB::table('nt_oqa_lab')
    //     ->join('nt_oqa_file', 'nt_oqa_file.id_createlab', '=', 'nt_oqa_lab.id_createlab')
    //     ->where('nt_oqa_lab.id_model', $model1)
    //     ->whereBetween('nt_oqa_file.date_ftir', array($datestart, $dateend))
    //     ->count();
    //     $chkdayftir1 = DB::table('nt_oqa_lab')
    //     ->join('nt_oqa_file', 'nt_oqa_file.id_createlab', '=', 'nt_oqa_lab.id_createlab')
    //     ->where('nt_oqa_lab.id_model', $model2)
    //     ->whereBetween('nt_oqa_file.date_ftir', array($datestart, $dateend))
    //     ->count();

    //     if($countdate > 0 || $countdate1 > 0)
    //     {
    //         $ckmodel = 1;
    //     }else{
    //         $ckmodel = 0;
    //     }

    //     if($chkday > 0 || $chkday1 > 0)
    //     {
    //         $cklpc = 1;
    //     }else{
    //         $cklpc = 0;
    //     }

    //     if($chksemtestday > 0 || $chksemtestday1 > 0)
    //     {
    //         $cksemtest = 1;
    //     }else{
    //         $cksemtest = 0;
    //     }

    //     if($cksemtest == 0 || $cklpc == 0)
    //     {
    //         $cksemlpc = 0;
    //     }else{
    //         $cksemlpc = 1;
    //     }

    //     if($cksemlpc == 1 &&  $ckmodel == 1)
    //     {
    //         if($getcreate != null)
    //         {
    //             $getoqafile = DB::table('nt_oqa_file')->where('id_createlab',$getcreate->id_createlab)->first();
    //         }
    //         if($getcreate1 != null)
    //         {
    //             $getoqafile = DB::table('nt_oqa_file')->where('id_createlab',$getcreate1->id_createlab)->first();
    //         }
    //         return response()->json(['chk' =>  0,'getcreate'=> $getcreate,
    //             'getoqafile'=>$getoqafile,
    //             'chkmodel'=>$chkmodel,
    //             'dateshowview'=>$dateshowview,
    //             'chkdaysem'=>$chkdaysem,
    //             'chkdayic'=>$chkdayic,
    //             'chkdaygcms'=>$chkdaygcms,
    //             'chkdayftir'=>$chkdayftir,
    //             'chkdaysem1'=>$chkdaysem1,
    //             'chkdayic1'=>$chkdayic1,
    //             'chkdaygcms1'=>$chkdaygcms1,
    //             'chkdayftir1'=>$chkdayftir1,
    //             'countlot' => $countlot
    //         ]);

    //     }
    //     if( $cksemlpc == 0  &&  $ckmodel == 1)
    //     {

    //         return response()->json(['chk' =>  1,'chkmodel'=>$chkmodel,
    //             'dateshowview'=>$dateshowview,
    //             'chkdaysem'=>$chkdaysem,
    //             'chkdayic'=>$chkdayic,
    //             'chkdaygcms'=>$chkdaygcms,
    //             'chkdayftir'=>$chkdayftir,
    //             'chkdaysem1'=>$chkdaysem1,
    //             'chkdayic1'=>$chkdayic1,
    //             'chkdaygcms1'=>$chkdaygcms1,
    //             'chkdayftir1'=>$chkdayftir1,
    //             'countlot' => $countlot,
    //             'cklpc' =>$cklpc,
    //             'cksemtest' =>$cksemtest,
    //             'cksemlpc' => $cksemlpc,
    //         ]);

    //     }
    //     if($cksemlpc == 0 &&  $ckmodel == 0)
    //     {

    //         return response()->json(['chk' =>  2,'chkmodel'=>$chkmodel,
    //             'dateshowview'=>$dateshowview,
    //             'dateshowview'=>$dateshowview,
    //             'chkdaysem'=>$chkdaysem,
    //             'chkdayic'=>$chkdayic,
    //             'chkdaygcms'=>$chkdaygcms,
    //             'chkdayftir'=>$chkdayftir,
    //             'chkdaysem1'=>$chkdaysem1,
    //             'chkdayic1'=>$chkdayic1,
    //             'chkdaygcms1'=>$chkdaygcms1,
    //             'chkdayftir1'=>$chkdayftir1,
    //             'countlot' => $countlot,
    //             'cksemlpc' => $cksemlpc,
    //         ]);

    //     }
    // }
        public function chkdaterun(Request $request)
    {
        //dd('oqa');
        $chkmodel = DB::table('nt_model_parameter')->where('id_model',$request->input('chkmodel'))->first();
        $countlot = DB::table('nt_oqa_lab')
        ->join('nt_oqa_file', 'nt_oqa_file.id_createlab', '=', 'nt_oqa_lab.id_createlab')
        ->where('nt_oqa_lab.lotnumber_lab',$request->input('txt_lotnumber'))->first();

        if( $request->input('chkaddid') == 1 )
        {
            $datetimenow = date("Y/m/d");
            $dateshowview = "";
        }
        else
        {
           if( $request->input('specialdate') != null)
           {
           $datetimenow = date( "Y/m/d", strtotime($request->input('specialdate')));
           $dateshowview = date( "d/m/Y H:i:s", strtotime($request->input('specialdate')));
           }
           else{
            $datetimenow = date("Y/m/d");
           }
        }
        $idmodel = $request->input('chkmodel');
        $date_w =  date('w', strtotime($datetimenow));
        $datenow = date( $datetimenow);
        if($date_w == "0") {$ckdate = 2; $stckdate = 1;}
        else if($date_w == "1" ){ $ckdate = 3; $stckdate = 2;}
        else if($date_w == "2" ){ $ckdate = 4; $stckdate = 3;}
        else if($date_w == "3" ){ $ckdate = 5; $stckdate = 4;}
        else if($date_w == "4" ){ $ckdate = 6; $stckdate = 5;}
        else if($date_w == "5" ){ $ckdate = 7; $stckdate = 6;}
        else if($date_w == "6" ){ $ckdate = 1; $stckdate = 0;}
        else{$ckdate=0;}
        $endday = 7-$ckdate;
        $startday = $stckdate+0;
        $datestart = date ("Y-m-d", strtotime("-".$startday." day", strtotime($datenow)));
        $dateend = date ("Y-m-d", strtotime("+".$endday." day", strtotime($datenow)));

        $namemodel = DB::table('nt_model')
        ->where('id_model', $idmodel)->first();
        $chktext1 = mb_substr($namemodel->name_model,0,-3);
       $namemodel1 = DB::table('nt_model')
        ->where('name_model', 'like',  $chktext1 . '%' )->get();
        if($namemodel1[0]->part == "UP")
        {
            $model1 = $namemodel1[0]->id_model;
        }
        else
        {
            $model1 = $namemodel1[1]->id_model;
        }
        if($namemodel1[1]->part == "DN")
        {
            $model2 = $namemodel1[1]->id_model;
        }
        else
        {
            $model2 = $namemodel1[0]->id_model;
        }
        $countdate = DB::table('nt_oqa_lab')
        ->where('id_model', $model1)
        ->whereBetween('save_date', array($datestart, $dateend))->count();
        $countdate1 = DB::table('nt_oqa_lab')
        ->where('id_model', $model2)
        ->whereBetween('save_date', array($datestart, $dateend))->count();

        $getcreate = DB::table('nt_oqa_lab')
        ->where('id_model', $model1)
        ->whereBetween('save_date', array($datestart, $dateend))
        ->first();
        $getcreate1 = DB::table('nt_oqa_lab')
        ->where('id_model', $model2)
        ->whereBetween('save_date', array($datestart, $dateend))
        ->first();

        $chkday = DB::table('nt_oqa_lab')
        ->join('nt_oqa_file', 'nt_oqa_file.id_createlab', '=', 'nt_oqa_lab.id_createlab')
        ->where('nt_oqa_lab.id_model', $model1)
        ->where('nt_oqa_file.date_lpc', $datetimenow)
        ->count();
        $chkday1 = DB::table('nt_oqa_lab')
        ->join('nt_oqa_file', 'nt_oqa_file.id_createlab', '=', 'nt_oqa_lab.id_createlab')
        ->where('nt_oqa_lab.id_model', $model2)
        ->where('nt_oqa_file.date_lpc', $datetimenow)
        ->count();

        $chksemtestday = DB::table('nt_oqa_lab')
        ->join('nt_oqa_file', 'nt_oqa_file.id_createlab', '=', 'nt_oqa_lab.id_createlab')
        ->where('nt_oqa_lab.id_model', $model1)
        ->where('nt_oqa_file.date_semtest', $datetimenow)
        ->count();
        $chksemtestday1 = DB::table('nt_oqa_lab')
        ->join('nt_oqa_file', 'nt_oqa_file.id_createlab', '=', 'nt_oqa_lab.id_createlab')
        ->where('nt_oqa_lab.id_model', $model2)
        ->where('nt_oqa_file.date_semtest', $datetimenow)
        ->count();

        $chkdaysem = DB::table('nt_oqa_lab')
        ->join('nt_oqa_file', 'nt_oqa_file.id_createlab', '=', 'nt_oqa_lab.id_createlab')
        ->where('nt_oqa_lab.id_model', $model1)
        ->whereBetween('nt_oqa_file.date_sem', array($datestart, $dateend))
        ->count();
        $chkdaysem1 = DB::table('nt_oqa_lab')
        ->join('nt_oqa_file', 'nt_oqa_file.id_createlab', '=', 'nt_oqa_lab.id_createlab')
        ->where('nt_oqa_lab.id_model', $model2)
        ->whereBetween('nt_oqa_file.date_sem', array($datestart, $dateend))
        ->count();
        $chkdayic = DB::table('nt_oqa_lab')
        ->join('nt_oqa_file', 'nt_oqa_file.id_createlab', '=', 'nt_oqa_lab.id_createlab')
        ->where('nt_oqa_lab.id_model', $model1)
        ->whereBetween('nt_oqa_file.date_ic', array($datestart, $dateend))
        ->count();
        $chkdayic1 = DB::table('nt_oqa_lab')
        ->join('nt_oqa_file', 'nt_oqa_file.id_createlab', '=', 'nt_oqa_lab.id_createlab')
        ->where('nt_oqa_lab.id_model', $model2)
        ->whereBetween('nt_oqa_file.date_ic', array($datestart, $dateend))
        ->count();

        $chkdaygcms = DB::table('nt_oqa_lab')
        ->join('nt_oqa_file', 'nt_oqa_file.id_createlab', '=', 'nt_oqa_lab.id_createlab')
        ->where('nt_oqa_lab.id_model', $model1)
        ->whereBetween('nt_oqa_file.date_gcms', array($datestart, $dateend))
        ->count();
        $chkdaygcms1 = DB::table('nt_oqa_lab')
        ->join('nt_oqa_file', 'nt_oqa_file.id_createlab', '=', 'nt_oqa_lab.id_createlab')
        ->where('nt_oqa_lab.id_model', $model2)
        ->whereBetween('nt_oqa_file.date_gcms', array($datestart, $dateend))
        ->count();

        $chkdayftir = DB::table('nt_oqa_lab')
        ->join('nt_oqa_file', 'nt_oqa_file.id_createlab', '=', 'nt_oqa_lab.id_createlab')
        ->where('nt_oqa_lab.id_model', $model1)
        ->whereBetween('nt_oqa_file.date_ftir', array($datestart, $dateend))
        ->count();
        $chkdayftir1 = DB::table('nt_oqa_lab')
        ->join('nt_oqa_file', 'nt_oqa_file.id_createlab', '=', 'nt_oqa_lab.id_createlab')
        ->where('nt_oqa_lab.id_model', $model2)
        ->whereBetween('nt_oqa_file.date_ftir', array($datestart, $dateend))
        ->count();

        if($countdate > 0 || $countdate1 > 0)
        {
            $ckmodel = 1;
        }else{
            $ckmodel = 0;
        }

        if($chkday > 0 || $chkday1 > 0)
        {
            $cklpc = 1;
        }else{
            $cklpc = 0;
        }

        if($chksemtestday > 0 || $chksemtestday1 > 0)
        {
            $cksemtest = 1;
        }else{
            $cksemtest = 0;
        }

        if($cksemtest == 0 || $cklpc == 0)
        {
            $cksemlpc = 0;
        }else{
            $cksemlpc = 1;
        }

        if($cksemlpc == 1 &&  $ckmodel == 1)
        {
            if($getcreate != null)
            {
                $getoqafile = DB::table('nt_oqa_file')->where('id_createlab',$getcreate->id_createlab)->first();
            }
            if($getcreate1 != null)
            {
                $getoqafile = DB::table('nt_oqa_file')->where('id_createlab',$getcreate1->id_createlab)->first();
            }
            return response()->json(['chk' =>  0,'getcreate'=> $getcreate,
                'getoqafile'=>$getoqafile,
                'chkmodel'=>$chkmodel,
                'dateshowview'=>$dateshowview,
                'chkdaysem'=>$chkdaysem,
                'chkdayic'=>$chkdayic,
                'chkdaygcms'=>$chkdaygcms,
                'chkdayftir'=>$chkdayftir,
                'chkdaysem1'=>$chkdaysem1,
                'chkdayic1'=>$chkdayic1,
                'chkdaygcms1'=>$chkdaygcms1,
                'chkdayftir1'=>$chkdayftir1,
                'countlot' => $countlot
            ]);

        }
        if( $cksemlpc == 0  &&  $ckmodel == 1)
        {

            return response()->json(['chk' =>  1,'chkmodel'=>$chkmodel,
                'dateshowview'=>$dateshowview,
                'chkdaysem'=>$chkdaysem,
                'chkdayic'=>$chkdayic,
                'chkdaygcms'=>$chkdaygcms,
                'chkdayftir'=>$chkdayftir,
                'chkdaysem1'=>$chkdaysem1,
                'chkdayic1'=>$chkdayic1,
                'chkdaygcms1'=>$chkdaygcms1,
                'chkdayftir1'=>$chkdayftir1,
                'countlot' => $countlot,
                'cklpc' =>$cklpc,
                'cksemtest' =>$cksemtest,
                'cksemlpc' => $cksemlpc,
            ]);

        }
        if($cksemlpc == 0 &&  $ckmodel == 0)
        {

            return response()->json(['chk' =>  2,'chkmodel'=>$chkmodel,
                'dateshowview'=>$dateshowview,
                'dateshowview'=>$dateshowview,
                'chkdaysem'=>$chkdaysem,
                'chkdayic'=>$chkdayic,
                'chkdaygcms'=>$chkdaygcms,
                'chkdayftir'=>$chkdayftir,
                'chkdaysem1'=>$chkdaysem1,
                'chkdayic1'=>$chkdayic1,
                'chkdaygcms1'=>$chkdaygcms1,
                'chkdayftir1'=>$chkdayftir1,
                'countlot' => $countlot,
                'cksemlpc' => $cksemlpc,
            ]);

        }
    }

    // public function chkpartnumber(Request $request)
    // {

    //     $chk = 0;
    //     $partnumber = $request->part_number;
    //     $chkmodel = $request->chkmodel;
    //     $model = DB::table('nt_model')->where('id_model',$chkmodel)->first();
	// 	$namemodel = $model->name_model;

    //     $chk = 0;
    //     if($model->part_number != $partnumber){
    //         $chk++;
    //     }
    //     if($model->part_number2 != $partnumber){
    //         $chk++;
    //     }
    //     if($model->part_number3 != $partnumber){
    //         $chk++;
    //     }
    //     if($chk >= 3){
    //         $chk = 3;
    //         return response()->json(['chk' => $chk]);
    //     }

    //     // $chk = 1;
    //     return response()->json(['chk' => $chk]);
    // }

    public function chkpartnumber(Request $request)
    {

        $chk = 0;
        $partnumber = $request->part_number;
        $chkmodel = $request->chkmodel;
        $model = DB::table('nt_model')->where('id_model',$chkmodel)->first();
		$namemodel = $model->name_model;

        $chk = 0;
        if($model->part_number != $partnumber){
            $chk++;
        }
        if($model->part_number2 != $partnumber){
            $chk++;
        }
        if($model->part_number3 != $partnumber){
            $chk++;
        }
        if($chk >= 3){
            $chk = 3;
            return response()->json(['chk' => $chk]);
        }

        // $chk = 1;
        return response()->json(['chk' => $chk]);
    }

    // public function chklognumber(Request $request)
    // {

    //     $chk = 0;
    //     $lotnumber = $request->input('lotnumber');
    //     $chkmodel = $request->input('chkmodel');
    //     $chktype = $request->input('chktype');
    //     $model = DB::table('nt_model')->where('id_model',$chkmodel)->first();
	// 	$namemodel = $model->name_model;

	// 	$check_j = strpos($namemodel, "_JNHK_");
	// 	$countstr = strlen($lotnumber);

    //     if($countstr != intval($model->lot_digit))
    //     {
    //       // จำนวนไม่ตรงตาม lot_digit
    //         $chk = 1;
    //         $text = 'จำนวนไม่ตรงตาม lot_digit';
    //         return response()->json(['chk' => $chk, 'message' => $text]);
    //     }
    //     if($chktype == "SGT_Oversea")
    //     {

	// 		if (str_contains($namemodel, '_TNHK_')) // TNHK
	// 		{
	// 		    $chk = 0;
	// 			$sub8 = substr($lotnumber, 7 ,1);
	// 			$sub9 = substr($lotnumber, 8 ,1);
	// 			$sub10 = substr($lotnumber, 9 ,1);
	// 			$sub11 = substr($lotnumber, 10 ,1);
	// 			if($sub8 != '-')
	// 			{
	// 				// จำนวนไม่ตรงตาม lot_digit
	// 				if($sub9 != 'K')
	// 				{
	// 					$chk = 2;
    //                     $text = 'จำนวนไม่ตรงตาม lot_digit = K';
	// 					return response()->json(['chk' => $chk, 'message' => $text]);
	// 				}
	// 			}

	// 			if($sub10 != '_')
	// 			{
	// 				$chk = 2;
    //                 $text = 'เครื่องหมายไม่ถูกต้อง';
	// 				return response()->json(['chk' => $chk, 'message' => $text]);
	// 			}
	// 			if($model->part == 'UP')
	// 			{
	// 				if($sub11 != 'R')
	// 				{
	// 					$chk = 2;
	// 					$text = 'ไม่พบ R';
	// 				    return response()->json(['chk' => $chk, 'message' => $text]);
	// 				}

	// 			}
	// 			else if($model->part == 'DN')
	// 			{
	// 				if($sub11 != 'L')
	// 				{
	// 					$chk = 2;
    //                     $text = 'ไม่พบ L';
	// 				    return response()->json(['chk' => $chk, 'message' => $text]);
	// 				}
	// 			}

	// 		}else{ // JNHK

	// 			$chk = 0;
	// 			$sub13 = substr($lotnumber, 0 ,3);

	// 			$sub8 = substr($lotnumber, 7 ,1);
	// 			$sub9 = substr($lotnumber, 8 ,1);
	// 			$sub10 = substr($lotnumber, 9 ,1);
	// 			$sub11 = substr($lotnumber, 10 ,1);

    //             $chkerrdg = 0;
    //             $maxchk = 0;
    //             if(!empty($model->digit_lot_number))
    //             {
    //                 $maxchk++;
    //                 if($sub13 != $model->digit_lot_number)
    //                 {
    //                     $chkerrdg++;
    //                 }
    //             }

    //             if(!empty($model->digit_lot_number1))
    //             {
    //                 $maxchk++;
    //                 if($sub13 != $model->digit_lot_number1)
    //                 {
    //                     $chkerrdg++;
    //                 }
    //             }

    //             if(!empty($model->digit_lot_number2))
    //             {
    //                 $maxchk++;
    //                 if($sub13 != $model->digit_lot_number2)
    //                 {
    //                     $chkerrdg++;
    //                 }
    //             }

    //             if($chkerrdg == $maxchk){
    //                 $chk = 2;
    //                 $text = 'จำนวนการตรวจสอบความถูกต้องไม่เท่ากัน';
    //                 return response()->json(['chk' => $chk, 'message' => $text]);
    //             }

	// 			if($sub8 != '-')
	// 			{
	// 				// จำนวนไม่ตรงตาม lot_digit
	// 				if($sub9 != 'K')
	// 				{
	// 					$chk = 2;
	// 					$text = 'จำนวนไม่ตรงตาม lot_digit';
    //                     return response()->json(['chk' => $chk, 'message' => $text]);
	// 				}
	// 			}

	// 			if($sub10 != '_')
	// 			{
	// 				$chk = 2;
	// 				$text = 'ไม่พบเครื่องหมาย _';
	// 				return response()->json(['chk' => $chk, 'message' => $text]);
	// 			}

	// 			if($model->part == 'UP')
	// 			{
	// 				if($sub11 != 'R')
	// 				{
	// 					$chk = 2;
	// 					$text = 'ไม่พบ R';
	// 				    return response()->json(['chk' => $chk, 'message' => $text]);
	// 				}

	// 			}
	// 			else if($model->part == 'DN')
	// 			{
	// 				if($sub11 != 'L')
	// 				{
	// 					$chk = 2;
	// 					$text = 'ไม่พบ L';
	// 				    return response()->json(['chk' => $chk, 'message' => $text]);
	// 				}
	// 			}

	// 		}
    //     }
    //     else if($chktype == "SGT_TNHK")
    //     {

    //         $sub6 = substr($lotnumber, 5 ,1);
    //         $i = 0;
    //         if($sub6 == $model->digit_lot_number)
    //         {
    //             $i++;
    //         }
    //         if($sub6 == $model->digit_lot_number1)
    //         {
    //             $i++;
    //         }
    //         if($sub6 == $model->digit_lot_number2)
    //         {
    //             $i++;
    //         }
    //         if( $i == 0)
    //         {
    //             $chk = 2;
    //             return response()->json(['chk' => $chk]);
    //         }
    //     }
    //     else if($chktype == "WD_TNHK")
    //     {

    //         $digitlot  = $model->digit_lot_number;
    //         $digitlot1 = $model->digit_lot_number1;
    //         $digitlot2 = $model->digit_lot_number2;

    //         // เก็บค่าที่ไม่ว่างไว้เป็น array เพื่อเช็คทีละตัว
    //         $candidates = array_filter([$digitlot, $digitlot1, $digitlot2], function ($v) {
    //             return $v !== null && $v !== '';
    //         });

    //         $matched = false;

    //         foreach ($candidates as $candidate) {
    //             $len = strlen($candidate);
    //             if (substr($lotnumber, 0, $len) === $candidate) {
    //                 $matched = true;
    //                 break; // เจอที่ตรงแล้ว ไม่ต้องเช็คตัวอื่นต่อ
    //             }
    //         }

    //         if (!$matched) {
    //             $chk = 2;
    //             return response()->json(['chk' => $chk]);
    //         }
    //     }
	// 	else if($chktype == "WD_Oversea")
    //     {
	// 		$chk = 0;
    //         $wdqty = $model->wdqty;
    //         $digitlot = $model->digit_lot_number;
    //         $digitlot1 = substr($digitlot, 0 ,1);
    //         $digitlot2 = substr($digitlot, 1 ,1);
    //         $digitlot3 = substr($digitlot, 2 ,1);

    //         $sub1 = substr($lotnumber, 0 ,1);
    //         $sub2 = substr($lotnumber, 1 ,1);
    //         $sub3 = substr($lotnumber, 2 ,1);
    //         $sub4 = substr($lotnumber, 3 ,1);
    //         $sub5 = substr($lotnumber, 4 ,1);
    //         $sub6 = substr($lotnumber, 5 ,1);
    //         $sub7 = substr($lotnumber, 6 ,1);
    //         $sub8 = substr($lotnumber, 7 ,1);
    //         $sub9 = substr($lotnumber, 8 ,1);
    //         $sub10 = substr($lotnumber, 9 ,1);

	// 		$sub13 = substr($lotnumber, 0 ,3);
	// 		$sub14 = substr($lotnumber, 0 ,4);

	// 		if($wdqty == 3)
	// 		{
	// 			$chkerr = 0;
	// 			$chkrowdigit = 0;

	// 			if(!empty($model->digit_lot_number)){ $chkrowdigit++; }
	// 			if(!empty($model->digit_lot_number1)){ $chkrowdigit++; }
	// 			if(!empty($model->digit_lot_number2)){ $chkrowdigit++; }

	// 			if($chkrowdigit == 3){
	// 				if($sub13 != $model->digit_lot_number){
	// 					$chkerr++;
	// 				}
	// 				if($sub13 != $model->digit_lot_number1){
	// 					$chkerr++;
	// 				}
	// 				if($sub13 != $model->digit_lot_number2){
	// 					$chkerr++;
	// 				}
	// 			}elseif($chkrowdigit == 2){
	// 				if($sub13 != $model->digit_lot_number){
	// 					$chkerr++;
	// 				}
	// 				if($sub13 != $model->digit_lot_number1){
	// 					$chkerr++;
	// 				}
	// 			}else{
	// 				if($sub13 != $model->digit_lot_number){
	// 					$chkerr++;

	// 				}
	// 			}

	// 			if($chkrowdigit == $chkerr){
	// 				$chk = 2;
	// 				return response()->json(['chk' => $chk]);
	// 			}

	// 		}
	// 		else if($wdqty == 4)
	// 		{

	// 			$chkerr = 0;
	// 			$chkrowdigit = 0;

	// 			if(!empty($model->digit_lot_number)){ $chkrowdigit++; }
	// 			if(!empty($model->digit_lot_number1)){ $chkrowdigit++; }
	// 			if(!empty($model->digit_lot_number2)){ $chkrowdigit++; }

	// 			if($chkrowdigit == 3){
	// 				if($sub14 != $model->digit_lot_number){
	// 					$chkerr++;
	// 				}
	// 				if($sub14 != $model->digit_lot_number1){
	// 					$chkerr++;
	// 				}
	// 				if($sub14 != $model->digit_lot_number2){
	// 					$chkerr++;
	// 				}
	// 			}elseif($chkrowdigit == 2){
	// 				if($sub14 != $model->digit_lot_number){
	// 					$chkerr++;
	// 				}
	// 				if($sub14 != $model->digit_lot_number1){
	// 					$chkerr++;
	// 				}
	// 			}else{
	// 				if($sub14 != $model->digit_lot_number){
	// 					$chkerr++;
	// 				}
	// 			}

	// 			if($chkrowdigit == $chkerr){
	// 				$chk = 2;
	// 				return response()->json(['chk' => $chk]);
	// 			}

	// 		}
	// 		else
	// 		{
	// 			$chk = 2;
	// 			return response()->json(['chk' => $chk]);
	// 		}

    //     }
    //     $chk = 9;
    //     return response()->json(['chk' => $chk]);
    // }

    public function chklognumber(Request $request)
    {

        $chk = 0;
        $lotnumber = $request->input('lotnumber');
        $chkmodel = $request->input('chkmodel');
        $chktype = $request->input('chktype');
        $model = DB::table('nt_model')->where('id_model',$chkmodel)->first();
		$namemodel = $model->name_model;
        $partnumber = $request->input('part_number');

		$check_j = strpos($namemodel, "_JNHK_");
		$countstr = strlen($lotnumber);

        if($countstr != intval($model->lot_digit))
        {
          // จำนวนไม่ตรงตาม lot_digit
            $chk = 1;
            $text = 'จำนวนไม่ตรงตาม lot_digit';
            return response()->json(['chk' => $chk, 'message' => $text]);
        }
        if($chktype == "SGT_Oversea")
        {

			if (str_contains($namemodel, '_TNHK_')) // TNHK
			{
			    $chk = 0;
				$sub8 = substr($lotnumber, 7 ,1);
				$sub9 = substr($lotnumber, 8 ,1);
				$sub10 = substr($lotnumber, 9 ,1);
				$sub11 = substr($lotnumber, 10 ,1);
				if($sub8 != '-')
				{
					// จำนวนไม่ตรงตาม lot_digit
					if($sub9 != 'K')
					{
						$chk = 2;
                        $text = 'จำนวนไม่ตรงตาม lot_digit = K';
						return response()->json(['chk' => $chk, 'message' => $text]);
					}
				}

				if($sub10 != '_')
				{
					$chk = 2;
                    $text = 'เครื่องหมายไม่ถูกต้อง';
					return response()->json(['chk' => $chk, 'message' => $text]);
				}
				if($model->part == 'UP')
				{
					if($sub11 != 'R')
					{
						$chk = 2;
						$text = 'ไม่พบ R';
					    return response()->json(['chk' => $chk, 'message' => $text]);
					}

				}
				else if($model->part == 'DN')
				{
					if($sub11 != 'L')
					{
						$chk = 2;
                        $text = 'ไม่พบ L';
					    return response()->json(['chk' => $chk, 'message' => $text]);
					}
				}

			}else{ // JNHK

                $chk = 0;
                $first3Lot = substr($lotnumber, 0, 3);
                $validDigitLots[] =  $model->digit_lot_number;

                if(!empty($model->digit_lot_number1)){
                    $validDigitLots[] =  $model->digit_lot_number1;
                }
                if(!empty($model->digit_lot_number2)){
                    $validDigitLots[] =  $model->digit_lot_number2;
                }

                if (!in_array($first3Lot,$validDigitLots)) {
                    $chk = 1;
                    $text = 'Digit lot number incorrect';
                    return response()->json(['chk' => $chk, 'message' => $text]);
                }

                $validParts = [$model->part_number, $model->part_number2,$model->part_number3];

                if (!in_array($partnumber,$validParts)) {
                    $chk = 1;
                    $text = 'Part number incorrect';
                    return response()->json(['chk' => $chk, 'message' =>$text]);
                }

				// $chk = 0;
				// $sub13 = substr($lotnumber, 0 ,3);

				// $sub8 = substr($lotnumber, 7 ,1);
				// $sub9 = substr($lotnumber, 8 ,1);
				// $sub10 = substr($lotnumber, 9 ,1);
				// $sub11 = substr($lotnumber, 10 ,1);

                // $chkerrdg = 0;
                // $maxchk = 0;
                // if(!empty($model->digit_lot_number))
                // {
                //     $maxchk++;
                //     if($sub13 != $model->digit_lot_number)
                //     {
                //         $chkerrdg++;
                //     }
                // }

                // if(!empty($model->digit_lot_number1))
                // {
                //     $maxchk++;
                //     if($sub13 != $model->digit_lot_number1)
                //     {
                //         $chkerrdg++;
                //     }
                // }

                // if(!empty($model->digit_lot_number2))
                // {
                //     $maxchk++;
                //     if($sub13 != $model->digit_lot_number2)
                //     {
                //         $chkerrdg++;
                //     }
                // }

                // if($chkerrdg == $maxchk){
                //     $chk = 2;
                //     $text = 'จำนวนการตรวจสอบความถูกต้องไม่เท่ากัน';
                //     return response()->json(['chk' => $chk, 'message' => $text]);
                // }

				// if($sub8 != '-')
				// {
				// 	// จำนวนไม่ตรงตาม lot_digit
				// 	if($sub9 != 'K')
				// 	{
				// 		$chk = 2;
				// 		$text = 'จำนวนไม่ตรงตาม lot_digit';
                //         return response()->json(['chk' => $chk, 'message' => $text]);
				// 	}
				// }

				// if($sub10 != '_')
				// {
				// 	$chk = 2;
				// 	$text = 'ไม่พบเครื่องหมาย _';
				// 	return response()->json(['chk' => $chk, 'message' => $text]);
				// }

				// if($model->part == 'UP')
				// {
				// 	if($sub11 != 'R')
				// 	{
				// 		$chk = 2;
				// 		$text = 'ไม่พบ R';
				// 	    return response()->json(['chk' => $chk, 'message' => $text]);
				// 	}

				// }
				// else if($model->part == 'DN')
				// {
				// 	if($sub11 != 'L')
				// 	{
				// 		$chk = 2;
				// 		$text = 'ไม่พบ L';
				// 	    return response()->json(['chk' => $chk, 'message' => $text]);
				// 	}
				// }

			}
        }
        else if($chktype == "SGT_TNHK")
        {

            $sub6 = substr($lotnumber, 5 ,1);
            $i = 0;
            if($sub6 == $model->digit_lot_number)
            {
                $i++;
            }
            if($sub6 == $model->digit_lot_number1)
            {
                $i++;
            }
            if($sub6 == $model->digit_lot_number2)
            {
                $i++;
            }
            if( $i == 0)
            {
                $chk = 2;
                return response()->json(['chk' => $chk]);
            }
        }
        else if($chktype == "WD_TNHK")
        {

            $digitlot  = $model->digit_lot_number;
            $digitlot1 = $model->digit_lot_number1;
            $digitlot2 = $model->digit_lot_number2;

            $candidates = array_filter([$digitlot, $digitlot1, $digitlot2], function ($v) {
                return $v !== null && $v !== '';
            });

            $matched = false;

            foreach ($candidates as $candidate) {
                $len = strlen($candidate);
                if (substr($lotnumber, 0, $len) === $candidate) {
                    $matched = true;
                    break;
                }
            }

            if (!$matched) {
                $chk = 2;
                return response()->json(['chk' => $chk]);
            }
        }
		else if($chktype == "WD_Oversea")
        {
			$chk = 0;
            $wdqty = $model->wdqty;
            $digitlot = $model->digit_lot_number;
            $digitlot1 = substr($digitlot, 0 ,1);
            $digitlot2 = substr($digitlot, 1 ,1);
            $digitlot3 = substr($digitlot, 2 ,1);

            $sub1 = substr($lotnumber, 0 ,1);
            $sub2 = substr($lotnumber, 1 ,1);
            $sub3 = substr($lotnumber, 2 ,1);
            $sub4 = substr($lotnumber, 3 ,1);
            $sub5 = substr($lotnumber, 4 ,1);
            $sub6 = substr($lotnumber, 5 ,1);
            $sub7 = substr($lotnumber, 6 ,1);
            $sub8 = substr($lotnumber, 7 ,1);
            $sub9 = substr($lotnumber, 8 ,1);
            $sub10 = substr($lotnumber, 9 ,1);

			$sub13 = substr($lotnumber, 0 ,3);
			$sub14 = substr($lotnumber, 0 ,4);

			if($wdqty == 3)
			{
				$chkerr = 0;
				$chkrowdigit = 0;

				if(!empty($model->digit_lot_number)){ $chkrowdigit++; }
				if(!empty($model->digit_lot_number1)){ $chkrowdigit++; }
				if(!empty($model->digit_lot_number2)){ $chkrowdigit++; }

				if($chkrowdigit == 3){
					if($sub13 != $model->digit_lot_number){
						$chkerr++;
					}
					if($sub13 != $model->digit_lot_number1){
						$chkerr++;
					}
					if($sub13 != $model->digit_lot_number2){
						$chkerr++;
					}
				}elseif($chkrowdigit == 2){
					if($sub13 != $model->digit_lot_number){
						$chkerr++;
					}
					if($sub13 != $model->digit_lot_number1){
						$chkerr++;
					}
				}else{
					if($sub13 != $model->digit_lot_number){
						$chkerr++;

					}
				}

				if($chkrowdigit == $chkerr){
					$chk = 2;
					return response()->json(['chk' => $chk]);
				}

			}
			else if($wdqty == 4)
			{

				$chkerr = 0;
				$chkrowdigit = 0;

				if(!empty($model->digit_lot_number)){ $chkrowdigit++; }
				if(!empty($model->digit_lot_number1)){ $chkrowdigit++; }
				if(!empty($model->digit_lot_number2)){ $chkrowdigit++; }

				if($chkrowdigit == 3){
					if($sub14 != $model->digit_lot_number){
						$chkerr++;
					}
					if($sub14 != $model->digit_lot_number1){
						$chkerr++;
					}
					if($sub14 != $model->digit_lot_number2){
						$chkerr++;
					}
				}elseif($chkrowdigit == 2){
					if($sub14 != $model->digit_lot_number){
						$chkerr++;
					}
					if($sub14 != $model->digit_lot_number1){
						$chkerr++;
					}
				}else{
					if($sub14 != $model->digit_lot_number){
						$chkerr++;
					}
				}

				if($chkrowdigit == $chkerr){
					$chk = 2;
					return response()->json(['chk' => $chk]);
				}

			}
			else
			{
				$chk = 2;
				return response()->json(['chk' => $chk]);
			}

        }
        $chk = 9;
        return response()->json(['chk' => $chk]);
    }

    public function createlabSubmit(Request $request)
    {
        $idmodel = $request->input('chkmodel');
        $chkaddid = $request->input('chkaddid');
        $modelparameter = DB::table('nt_model_parameter')->where('id_model',$idmodel)->where('id_model_p',1)->first();
        $lotcount = DB::table('nt_oqa_lab')->where('lotnumber_lab',$request->input('txt_lotnumber'))->count();
        $lotfirst = DB::table('nt_oqa_lab')->where('lotnumber_lab',$request->input('txt_lotnumber'))->first();
        // $tga = date('Y-m-d', strtotime($request->input('txt_tga')));
        $ntmodel = DB::table('nt_model')->where('id_model',$idmodel)->first();

        // createlabSubmit ใช้ได้เฉพาะเมนู oqa และ model_type ขึ้นต้นด้วย SGT_ เท่านั้น
        $denied = $this->checkCreatelabAccess($ntmodel, 'oqa', 'SGT_');
        if ($denied) {
            return $denied;
        }

		if (
			!$request->input('ck_lpc') &&
			!$request->input('ck_sem') &&
			!$request->input('ck_ic') &&
			!$request->input('ck_gcms') &&
			!$request->input('ck_ftir') &&
			!$request->input('ck_semtest')
		) {
			return response()->json([
				'success' => 0,
				'message' => 'กรุณาเลือกเครื่องอย่างน้อย 1 เครื่อง'
			], 400);
		}

        $tgaDate = null;
        if ($request->filled('txt_tga')) {
            $tgaInput = $request->input('txt_tga');

            try {
                $tgaDate = Carbon::createFromFormat('Y-m-d', $tgaInput)->format('Y-m-d');
            } catch (\InvalidArgumentException $e) {
                try {
                    $tgaDate = Carbon::createFromFormat('d/m/Y', $tgaInput)->format('Y-m-d');
                } catch (\InvalidArgumentException $ex) {
                    try {
                        $tgaDate = Carbon::createFromFormat('m/d/Y', $tgaInput)->format('Y-m-d');
                    } catch (\InvalidArgumentException $exc) {
                        return response()->json([
                            'success' => 0,
                            'message' => 'รูปแบบวันที่ TGA ไม่ถูกต้อง (รองรับ Y-m-d, d/m/Y, m/d/Y)'
                        ], 400);
                    }
                }
            }
        }

        if($chkaddid == 1)
        {
           $savedate = date('Y-m-d H:i:s');
           $datechk = date('Y-m-d');
        }
        else
        {
            $savedate = $request->input('specialdate');
            $datechk = date('Y-m-d');
        }
        $createlab = [
            'id_model' => $idmodel,
            'type_lab' => $request->input('chktype'),
            'name_lab' => $request->input('txt_name'),
            'code_lab' => $request->input('txt_code'),
            'lotnumber_lab' => $request->input('txt_lotnumber'),
            'save_date' => $savedate,
            'date_chk' =>  $datechk,
            'add_lpc_day' => 0,
            'lpc_confirm_st' => 0,
            'sem_confirm_st' => 0,
            'ic_confirm_st' => 0,
            'gcms_confirm_st' => 0,
            'ftir_confirm_st' => 0,
            'semtest_confirm_st' => 0,
            'lpcstatus_done' => 0,
            'semstatus_done' => 0,
            'icstatus_done' => 0,
            'gcmsstatus_done' => 0,
            'ftirstatus_done' => 0,
            'semteststatus_done' => 0,
            'tga' => $tgaDate,
        ];
        if($lotcount == 0)
        {
            $getidcreatelab = DB::table('nt_oqa_lab')->insertGetId($createlab, 'id_createlab');
        }
        else
        {
            $getidcreatelab = $lotfirst->id_createlab;
        }
        $starttime = date('YmdHis',strtotime($savedate));
        $model = $ntmodel->name_model;
        $part = $ntmodel->part;

        $lot = $request->input('txt_lotnumber');
                if( $request->input('ck_lpc') == 1 )
                {
                    $lpcname =  $starttime.'_LPC_'.$model.'_'.$part.'_'.$lot;
                    $lpcfilename = $lpcname.'.xlsm';
                    $st_lpc = 1;
                    $date_lpc =   $savedate;
                    $lpc_startime =   $savedate;

                    $file = storage_path('NHK-File/model-rowfile/'.$ntmodel->file_folder.'/'.$modelparameter->lpc_file);

                    $spreadsheet = IOFactory::load($file);
                    $sheet = $spreadsheet->setActiveSheetIndex(0);

                    $sheet->setCellValue('C5',$createlab['lotnumber_lab']);
                    $sheet->setCellValue('R3',$starttime);
                    $sheet->setCellValue('L2',$tgaDate);

                    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
                    $writer->setPreCalculateFormulas(false);
                    $writer->save(storage_path('app/public/OQA/LPC/'.$lpcname.'.xlsm'));

                    // Excel::load($file, function ($doc)use($createlab,$starttime,$getidcreatelab,$tgaDate) {
                    //     $sheet = $doc->setActiveSheetIndex(0);
                    //     $sheet->setCellValue('C5',$createlab['lotnumber_lab']);
                    //     $sheet->setCellValue('R3',$starttime);
                    //     $sheet->setCellValue('L2',$tgaDate);

                    // })->setFilename($lpcname)->store('xlsm', storage_path('app/public/OQA/LPC/'));

                    $datalpc = [
                        'id_createlab' => $getidcreatelab,
                    ];

                    DB::table('nt_data_lpc')->insert($datalpc);
                    DB::table('nt_datacf_lpc')->insert($datalpc);
                    if($lotcount > 0)
                    {

                        $chk = DB::table('nt_oqa_file')->where('id_createlab', $lotfirst->id_createlab)->first();
                        if(empty($chk)){
                            $datafile = [
                                'id_createlab' =>  $getidcreatelab,
                                'st_lpc' =>  $st_lpc,
                                'file_lpc' =>  $lpcfilename,
                                'date_lpc' =>  $date_lpc,
                                'lpc_startime' =>  $lpc_startime,
                            ];
                            DB::table('nt_oqa_file')->insert($datalpc);
                        }else{
                            DB::table('nt_oqa_file')->where('id_createlab', $lotfirst->id_createlab)->update(['st_lpc' => $st_lpc, 'file_lpc' => $lpcfilename,'date_lpc' => $date_lpc,'lpc_startime'=> $lpc_startime]);
                        }

                    }

                }
                else
                {
                    if($lotcount == 0)
                    {
                    $st_lpc = 0;
                    $lpcfilename = null;
                    $date_lpc =  null;
                    $lpc_startime = null;
                    }
                }



                if( $request->input('ck_sem')  == 1 )
                {
                    $semname =  $starttime.'_SEM_'.$model.'_'.$part.'_'.$lot;
                    $semfilename = $semname.'.xlsx';
                    $st_sem = 1;
                    $date_sem =   $savedate;
                    $sem_startime =   $savedate;
                    $file = storage_path('NHK-File/model-rowfile/'.$ntmodel->file_folder.'/'.$modelparameter->sem_file);

                    $spreadsheet = IOFactory::load($file);
                    $sheet = $spreadsheet->setActiveSheetIndex(2);

                    $sheet->setCellValue('C6',$createlab['lotnumber_lab']);
                    $sheet->setCellValue('H10',$starttime);

                    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
                    $writer->setPreCalculateFormulas(false);
                    $writer->save(storage_path('app/public/OQA/SEM/'.$semname.'.xlsx'));

                    // Excel::load($file, function ($doc)use($createlab,$starttime,$getidcreatelab) {
                    //  $sheet = $doc->setActiveSheetIndex(2);
                    //  $sheet->setCellValue('C6',$createlab['lotnumber_lab']);
                    //  $sheet->setCellValue('H10',$starttime);
                    // })->setFilename($semname)->store('xlsx', storage_path('app/public/OQA/SEM/'));

                    $datasem = [
                        'id_createlab' => $getidcreatelab,
                    ];
                    DB::table('nt_data_sem')->insert($datasem);
                    DB::table('nt_datacf_sem')->insert($datasem);
                    if($lotcount > 0)
                    {
                    DB::table('nt_oqa_file')->where('id_createlab', $lotfirst->id_createlab)->update(['st_sem' => $st_sem, 'file_sem' => $semfilename,'date_sem' => $date_sem,'sem_startime'=>$sem_startime]);
                    }
                }
                else
                {
                    $st_sem = 0;
                    $semfilename = null;
                    $date_sem =  null;
                    $sem_startime = null;
                }
                if( $request->input('ck_ic')  == 1 )
                {
                    $icname =  $starttime.'_IC_'.$model.'_'.$part.'_'.$lot;
                    $icfilename = $icname.'.xlsx';
                    $st_ic = 1;
                    $date_ic =   $savedate;
                    $ic_startime = $savedate;
                    $file = storage_path('NHK-File/model-rowfile/'.$ntmodel->file_folder.'/'.$modelparameter->ic_file);

                    $spreadsheet = IOFactory::load($file);
                    $sheet = $spreadsheet->setActiveSheetIndex(0);

                    $sheet->setCellValue('C6',$createlab['lotnumber_lab']);
                    $sheet->setCellValue('L9',$starttime);

                    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
                    $writer->setPreCalculateFormulas(false);
                    $writer->save(storage_path('app/public/OQA/IC/'.$icname.'.xlsx'));

                    // Excel::load($file, function ($doc)use($createlab,$starttime,$getidcreatelab) {
                    //  $sheet = $doc->setActiveSheetIndex(0);
                    //  $sheet->setCellValue('C6',$createlab['lotnumber_lab']);
                    //  $sheet->setCellValue('L9',$starttime);
                    // })->setFilename($icname)->store('xlsx', storage_path('app/public/OQA/IC/'));

                    $dataic = [
                        'id_createlab' => $getidcreatelab,
                    ];
                    DB::table('nt_data_ic')->insert($dataic);
                    DB::table('nt_datacf_ic')->insert($dataic);
                    if($lotcount > 0)
                    {
                    DB::table('nt_oqa_file')->where('id_createlab', $lotfirst->id_createlab)->update(['st_ic' => $st_ic, 'file_ic' => $icfilename,'date_ic' => $date_ic,'ic_startime'=>$ic_startime]);
                    }
                }
                else
                {
                    $st_ic = 0;
                    $icfilename = null;
                    $date_ic =  null;
                    $ic_startime = null;
                }

                if( $request->input('ck_gcms')  == 1 )
                {
                    $gcmsname =  $starttime.'_GCMS_'.$model.'_'.$part.'_'.$lot;
                    $gcmsfilename = $gcmsname.'.xlsx';
                    $st_gcms = 1;
                    $date_gcms =   $savedate;
                    $gcms_startime = $savedate;
                    $file = storage_path('NHK-File/model-rowfile/'.$ntmodel->file_folder.'/'.$modelparameter->gcms_file);

                    $spreadsheet = IOFactory::load($file);
                    $sheet = $spreadsheet->setActiveSheetIndex(3);

                    $sheet->setCellValue('B9',$createlab['lotnumber_lab']);
                    $sheet->setCellValue('B14',$starttime);

                    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
                    $writer->setPreCalculateFormulas(false);
                    $writer->save(storage_path('app/public/OQA/GCMS/'.$gcmsname.'.xlsx'));

                    // Excel::load($file, function ($doc)use($createlab,$starttime,$getidcreatelab) {
                    //  $sheet = $doc->setActiveSheetIndex(3);
                    //  $sheet->setCellValue('B9',$createlab['lotnumber_lab']);
                    //  $sheet->setCellValue('B14',$starttime);
                    // })->setFilename($gcmsname)->store('xlsx', storage_path('app/public/OQA/GCMS/'));

                    $datagcms = [
                        'id_createlab' => $getidcreatelab,
                    ];
                    DB::table('nt_data_gcms')->insert($datagcms);
                    DB::table('nt_datacf_gcms')->insert($datagcms);
                    if($lotcount > 0)
                    {
                    DB::table('nt_oqa_file')->where('id_createlab', $lotfirst->id_createlab)->update(['st_gcms' => $st_gcms, 'file_gcms' => $gcmsfilename,'date_gcms' => $date_gcms,'gcms_startime'=>$gcms_startime]);
                    }
                }
                else
                {
                    $st_gcms = 0;
                    $gcmsfilename = null;
                    $date_gcms =  null;
                    $gcms_startime =null;
                }
                if( $request->input('ck_ftir')  == 1 )
                {
                    $ftirname =  $starttime.'_FTIR_'.$model.'_'.$part.'_'.$lot;
                    $ftirfilename = $ftirname.'.xlsx';
                    $st_ftir = 1;
                    $date_ftir =   $savedate;
                    $ftir_startime = $savedate;
                    $file = storage_path('NHK-File/model-rowfile/'.$ntmodel->file_folder.'/'.$modelparameter->ftir_file);

                    $spreadsheet = IOFactory::load($file);
                    $sheet = $spreadsheet->setActiveSheetIndex(0);

                    $sheet->setCellValue('B20',$createlab['lotnumber_lab']);
                    $sheet->setCellValue('B75',$starttime);

                    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
                    $writer->setPreCalculateFormulas(false);
                    $writer->save(storage_path('app/public/OQA/FTIR/'.$ftirname.'.xlsx'));

                    // Excel::load($file, function ($doc)use($createlab,$starttime,$getidcreatelab) {
                    //  $sheet = $doc->setActiveSheetIndex(0);
                    //  $sheet->setCellValue('B20',$createlab['lotnumber_lab']);
                    //  $sheet->setCellValue('B75',$starttime);
                    // })->setFilename($ftirname)->store('xlsx', storage_path('app/public/OQA/FTIR/'));

                    $dataftir = [
                        'id_createlab' => $getidcreatelab,
                    ];
                    DB::table('nt_data_ftir')->insert($dataftir);
                    DB::table('nt_datacf_ftir')->insert($dataftir);
                    if($lotcount > 0)
                    {
                    DB::table('nt_oqa_file')->where('id_createlab', $lotfirst->id_createlab)->update(['st_ftir' => $st_ftir, 'file_ftir' => $ftirfilename,'date_ftir' => $date_ftir,'ftir_startime'=>$ftir_startime]);
                    }
                }
                else
                {
                    $st_ftir = 0;
                    $ftirfilename = null;
                    $date_ftir = null;
                    $ftir_startime = null;
                }


                if( $request->input('ck_semtest')  == 1 )
                {
                    $semtestname =  $starttime.'_Ghost_'.$model.'_'.$part.'_'.$lot;
                    $semtestfilename = $semtestname.'.xlsx';
                    $st_semtest = 1;
                    $date_semtest =   $savedate;
                    $semtest_startime = $savedate;
                    $file = storage_path('NHK-File/model-rowfile/'.$ntmodel->file_folder.'/'.$modelparameter->semtest_file);

                    $spreadsheet = IOFactory::load($file);
                    $sheet = $spreadsheet->setActiveSheetIndex(0);

                    $sheet->setCellValue('C5',$createlab['lotnumber_lab']);
                    $sheet->setCellValue('J3',$starttime);

                    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
                    $writer->setPreCalculateFormulas(false);
                    $writer->save(storage_path('app/public/OQA/GhostTest/'.$semtestname.'.xlsx'));

                    // Excel::load($file, function ($doc)use($createlab,$starttime,$getidcreatelab) {
                    //  $sheet = $doc->setActiveSheetIndex(0);
                    //  $sheet->setCellValue('C5',$createlab['lotnumber_lab']);
                    //  $sheet->setCellValue('J3',$starttime);
                    // })->setFilename($semtestname)->store('xlsx', storage_path('app/public/OQA/GhostTest/'));

                    $datasemtest = [
                        'id_createlab' => $getidcreatelab,
                    ];
                    DB::table('nt_data_semtest')->insert($datasemtest);
                    DB::table('nt_datacf_semtest')->insert($datasemtest);
                    if($lotcount > 0)
                    {
                    DB::table('nt_oqa_file')->where('id_createlab', $lotfirst->id_createlab)->update(['st_semtest' => $st_semtest, 'file_semtest' => $semtestfilename,'date_semtest' => $date_semtest,'semtest_startime'=>$semtest_startime]);
                    }
                }
                else
                {
                    $st_semtest = 0;
                    $semtestfilename = null;
                    $date_semtest = null;
                    $semtest_startime = null;
                }


        if($lotcount == 0)
        {
            $oqafile = [
                'id_createlab' => $getidcreatelab,
                'st_lpc' => $st_lpc,
                'file_lpc' => $lpcfilename,
                'date_lpc' =>  $date_lpc,
                'lpc_startime'=> $lpc_startime,
                'st_sem' => $st_sem,
                'file_sem' => $semfilename,
                'date_sem' => $date_sem,
                'sem_startime'=> $sem_startime,
                'st_ic' => $st_ic,
                'file_ic' => $icfilename,
                'date_ic' =>$date_ic,
                'ic_startime'=> $ic_startime,
                'st_gcms' => $st_gcms,
                'file_gcms' => $gcmsfilename,
                'date_gcms' =>$date_gcms,
                'gcms_startime'=> $gcms_startime,
                'st_ftir' => $st_ftir,
                'file_ftir' => $ftirfilename,
                'date_ftir' =>$date_ftir,
                'ftir_startime'=> $ftir_startime,
                'st_semtest' => $st_semtest,
                'file_semtest' => $semtestfilename,
                'date_semtest' =>$date_semtest,
                'semtest_startime'=> $semtest_startime,
                'status'=>1,
            ];
            DB::table('nt_oqa_file')->insert($oqafile);
        }
        else
        {
        }
        return response()->json(['success' =>  1,'id'=>$getidcreatelab]);
    }
    public function createlabSubmitsub(Request $request)
    {
        $idmodel = $request->input('chkmodel');
        // $tga = date('Y-m-d', strtotime($request->input('txt_tga')));
        $modelparameter = DB::table('nt_model_parameter')->where('id_model',$idmodel)->get();
        $ntmodel = DB::table('nt_model')->where('id_model',$idmodel)->first();

        $tgaDate = null;
        if ($request->filled('txt_tga')) {
            $tgaInput = $request->input('txt_tga');

            try {
                $tgaDate = Carbon::createFromFormat('Y-m-d', $tgaInput)->format('Y-m-d');
            } catch (\InvalidArgumentException $e) {
                try {
                    $tgaDate = Carbon::createFromFormat('d/m/Y', $tgaInput)->format('Y-m-d');
                } catch (\InvalidArgumentException $ex) {
                    try {
                        $tgaDate = Carbon::createFromFormat('m/d/Y', $tgaInput)->format('Y-m-d');
                    } catch (\InvalidArgumentException $exc) {
                        return response()->json([
                            'success' => 0,
                            'message' => 'รูปแบบวันที่ TGA ไม่ถูกต้อง (รองรับ Y-m-d, d/m/Y, m/d/Y)'
                        ], 400);
                    }
                }
            }
        }

        $createlab = [
            'id_model' => $idmodel,
            'type_lab' => $request->input('chktype'),
            'name_lab' => $request->input('txt_name'),
            'code_lab' => $request->input('txt_code'),
            'lotnumber_lab' => $request->input('txt_lotnumber'),
            'save_date' => date('Y-m-d H:i:s'),
            'date_chk' => date('Y-m-d'),
            'add_lpc_day' => 0,
            'lpc_confirm_st' => 0,
            'sem_confirm_st' => 0,
            'ic_confirm_st' => 0,
            'gcms_confirm_st' => 0,
            'fitr_confirm_st' => 0,
            'tga' => $tgaDate,
        ];
        $getidcreatelab = DB::table('nt_oqa_lab')->insertGetId($createlab, 'id_createlab');
        $starttime = date('YmdHis');
        $model = $ntmodel->name_model;
        $part = $ntmodel->part;
        $lot = $request->input('txt_lotnumber');
        foreach($modelparameter as $st)
        {
            if($st->id_model_p ==  1)
            {
                if( $st->lpc == 1 )
                {
                    $lpcname =  $starttime.'_LPC_'.$model.'_'.$part.'_'.$lot;
                    $lpcfilename = $lpcname.'.xlsm';
                    $st_lpc = 1;
                    $date_lpc =  date('Y-m-d');
                    $file = storage_path('NHK-File/model-rowfile/'.$ntmodel->file_folder.'/'.$st->lpc_file);
                    Excel::load($file, function ($doc)use($createlab,$starttime,$getidcreatelab) {
                        $sheet = $doc->setActiveSheetIndex(0);
                        $sheet->setCellValue('C3',$ntmodel->name_model);
                        $sheet->setCellValue('D5',$createlab['lotnumber_lab']);
                        $sheet->setCellValue('R3',$starttime);
                    })->setFilename($lpcname)->store('xlsm', storage_path('NHK-File/OQA/LPC/'));
                    $dataftir = [
                        'id_createlab' => $getidcreatelab,
                    ];
                    DB::table('nt_data_ftir')->insert($dataftir);
                }
                else
                {
                    $st_lpc = 0;
                    $lpcfilename = null;
                    $date_lpc =  null;
                }

            }
        }
        $oqafile = [
            'id_createlab' => $getidcreatelab,
            'st_lpc' => $st_lpc,
            'file_lpc' => $lpcfilename,
            'date_lpc' =>  $date_lpc,
            'st_sem' => 0,
            'file_sem' => null,
            'date_sem' => null,
            'st_ic' => 0,
            'file_ic' => null,
            'date_ic' =>null,
            'st_gcms' => 0,
            'file_gcms' => null,
            'date_gcms' =>null,
            'st_ftir' => 0,
            'file_ftir' => null,
            'date_ftir' =>null,
            'status'=>1,
        ];
        DB::table('nt_oqa_file')->insert($oqafile);
        return response()->json(['success' =>  1,'id'=>$getidcreatelab]);
    }
    public function oqafile($id)
    {
        $oqafile = DB::table('nt_oqa_file')->where('id_createlab',$id)->first();
        $oqalab = DB::table('nt_oqa_lab')
        ->join('nt_model', 'nt_model.id_model', '=', 'nt_oqa_lab.id_model')
        ->where('nt_oqa_lab.id_createlab',$id)->select('nt_oqa_lab.*','nt_model.*','nt_oqa_lab.save_date as starttime')->first();
        $aml = DB::table('nt_oqa_aml_file')->where('id_createlab',$id)->first();
        if (Auth::user()->status != 3){
            return view('OQA/oqafile', ['oqafile' =>$oqafile,'oqalab' =>$oqalab,'aml' =>$aml]);
        }else{
            return view('OQA/oqafile_mfg', ['oqafile' =>$oqafile,'oqalab' =>$oqalab,'aml' =>$aml]);
        }
    }
    public function uploadoqafile($id,$labname)
    {
        $oqafile = DB::table('nt_oqa_file')->where('id_createlab',$id)->first();
        $oqalab = DB::table('nt_oqa_lab')->where('id_createlab',$id)->first();
        return view('OQA/uploadoqafile', ['oqafile' =>$oqafile,'oqalab' =>$oqalab,'labname'=>$labname]);
    }
    public function uplaodoqaSubmit(Request $request)
    {
        $id = $request->input('idfile');
        $namefile = $request->input('filename');
        $oqafile = DB::table('nt_oqa_file')->where('id_createlab',$id)->first();
        $oqalab = DB::table('nt_oqa_lab')->where('id_createlab',$id)->first();
        $datenow = date("Y/m/d H:i:s");
        if($request->input('filename') == "LPC")
        {
            if($oqafile->file_lpc !== null){
                $rename = $oqafile->file_lpc;
                $file = $request->file('customFile')->storeAs('OQA/LPC', $rename,'public');

            }else{

                $files = $request->file('customFile');
                $extension = 'xlsm';
                $model = DB::table('nt_model')->where('id_model',$oqalab->id_model)->first();
                $filetime = date('YmdHis');
                $namefile2 = $filetime.'_LPC_'.$model->name_model.'_'.$oqalab->lotnumber_lab.'.'.$extension;
                $rename = $namefile2;

                $destinationPath = storage_path('app/public/OQA/LPC');
                $files->move($destinationPath , $rename); // move files to destination folder

            }

            $updates = [
                'file_lpc' => $rename,
                'lpc_update' => $datenow,
            ];
            $updatesoqalab = [
                'lpc_confirm_st' => 0,
            ];
            DB::table('nt_oqa_lab')->where('id_createlab', $id)->update($updatesoqalab);
            DB::table('nt_oqa_file')->where('id_createlab', $id)->update($updates);
        }
        if($request->input('filename') == "SEM")
        {
           $rename = $oqafile->file_sem;
           $file = $request->file('customFile')->storeAs('OQA/SEM', $rename,'public');
           $updates = [
            'sem_update' => $datenow,
            ];
            $updatesoqalab = [
                'sem_confirm_st' => 0,
            ];
            DB::table('nt_oqa_lab')->where('id_createlab', $id)->update($updatesoqalab);
           DB::table('nt_oqa_file')->where('id_createlab', $id)->update($updates);
        }
        if($request->input('filename') == "IC")
        {
           $rename = $oqafile->file_ic;
           $file = $request->file('customFile')->storeAs('OQA/IC', $rename,'public');
           $updates = [
            'ic_update' => $datenow,
            ];
            $updatesoqalab = [
                'ic_confirm_st' => 0,
            ];
            DB::table('nt_oqa_lab')->where('id_createlab', $id)->update($updatesoqalab);
           DB::table('nt_oqa_file')->where('id_createlab', $id)->update($updates);
        }
        if($request->input('filename') == "GCMS")
        {
           $rename = $oqafile->file_gcms;
           $file = $request->file('customFile')->storeAs('OQA/GCMS', $rename,'public');
           $updates = [
                'gcms_update' => $datenow,
            ];
            $updatesoqalab = [
                'gcms_confirm_st' => 0,
            ];
            DB::table('nt_oqa_lab')->where('id_createlab', $id)->update($updatesoqalab);
           DB::table('nt_oqa_file')->where('id_createlab', $id)->update($updates);
        }
        if($request->input('filename') == "FTIR")
        {

           $rename = $oqafile->file_ftir;
           $file = $request->file('customFile')->storeAs('OQA/FTIR', $rename,'public');
           $updates = [
                'ftir_update' => $datenow,
            ];
            $updatesoqalab = [
                'ftir_confirm_st' => 0,
            ];
            DB::table('nt_oqa_lab')->where('id_createlab', $id)->update($updatesoqalab);
           DB::table('nt_oqa_file')->where('id_createlab', $id)->update($updates);
        }

        if($request->input('filename') == "GhostTest")
        {

           $rename = $oqafile->file_semtest;
           $file = $request->file('customFile')->storeAs('OQA/GhostTest', $rename,'public');
           $updates = [
            'semtest_update' => $datenow,
            ];
            $updatesoqalab = [
                'semtest_confirm_st' => 0,
            ];
            DB::table('nt_oqa_lab')->where('id_createlab', $id)->update($updatesoqalab);
           DB::table('nt_oqa_file')->where('id_createlab', $id)->update($updates);
        }

        return response()->json(['success' =>  1,'id'=>$id ,'namefile'=>$namefile]);
    }

    public function chkconfirmlpc(Request $request)
    {

        DB::beginTransaction();
        try {
            $id = $request->input('id');
            $chkid = $request->input('chkid');
            $oqafile =  DB::table('nt_oqa_file')
            ->join('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_oqa_file.id_createlab')
            ->join('nt_model', 'nt_model.id_model', '=', 'nt_oqa_lab.id_model')
            ->where('nt_oqa_file.id_createlab',$request->input('id'))->first();

            if (empty($oqafile)) {
                DB::rollBack();
                return response()->json([
                    'error' => 'ไม่พบข้อมูล nt_oqa_file/nt_oqa_lab สำหรับ id_createlab = ' . $id,
                ], 404);
            }

            $file = storage_path('app/public/OQA/LPC/'.$oqafile->file_lpc);

            //* 4.SGT AML return */
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
            $worksheet = $spreadsheet->setActiveSheetIndexByName('AML');
            $AML_LPC = $worksheet->getCell('F2')->getOldCalculatedValue();
            $text_aml_lpc = [
                'aml_lpc' => $AML_LPC,
            ];
            DB::table('nt_oqa_lab')->where('id_createlab', $id)->update($text_aml_lpc);
            //* 4.SGT AML return */

            $cell = '';
            $chk = 0;
            Excel::load($file, function($doc)use($id,$oqafile,&$chk,$chkid) {
                $sheet = $doc->setActiveSheetIndex(0);
                $ftimechk = $sheet->getCell('R4')->getCalculatedValue();
                $ftime = date("Y/m/d H:i:s", strtotime($ftimechk));
                $cell=$sheet->getCell('O19')->getOldCalculatedValue();
                if($cell != "")
                {
                    $cell1=$sheet->getCell('O16')->getOldCalculatedValue();
                    $cell2=$sheet->getCell('O17')->getOldCalculatedValue();
                    $cell3=$sheet->getCell('O18')->getOldCalculatedValue();

                    $avg = str_replace("," , "" , $cell);
                    $avg = floatval($avg);

                    $oqalpc = [
                        'lpc_data' => $avg,
                        'lpc1' => $cell1,
                        'lpc2' => $cell2,
                        'lpc3' => $cell3,
                        'lpc_datesave' => date('Y-m-d H:i:s'),
                        'lpc_st' => 1,
                    ];
                    DB::table('nt_data_lpc')->where('id_createlab', $id)->update($oqalpc);
                    DB::table('nt_datacf_lpc')->where('id_createlab', $id)->update($oqalpc);
                    $updates = [
                        'lpc_confirm_st' => 1,
                        'ftime_lpc' =>$ftime,
                    ];
                    DB::table('nt_oqa_lab')->where('id_createlab', $id)->update($updates);
                    $chk = 1;

                    //----------- LPC measurement L/T -------------
                    $file_id = $oqafile->file_id;
                    $restart = $sheet->getCell('Y2')->getValue();
                    if(!empty($restart) && str_contains($restart,'WW') == false){// re-test

                        $start = $sheet->getCell('Y4')->getOldCalculatedValue();
                        $end = $sheet->getCell('U4')->getOldCalculatedValue();

                        $start = str_replace(".0", "", $start);
                        $end = str_replace(".0", "", $end);

                        $start = date('Y-m-d H:i:s',strtotime($start));
                        $end = date('Y-m-d H:i:s',strtotime($end));

                        $dataupdate = [
                            'lpc_begin' => $start,
                            'lpc_lasttime' => $end,
                        ];
                        DB::table('nt_oqa_file')->where('file_id', $file_id)->update($dataupdate);

                    }else{
                        $start = $sheet->getCell('R3')->getCalculatedValue();
                        $end = $sheet->getCell('R4')->getCalculatedValue();

                        $start = date('Y-m-d H:i:s',strtotime($start));
                        $end = date('Y-m-d H:i:s',strtotime($end));

                        $dataupdate = [
                            'lpc_begin' => $start,
                            'lpc_lasttime' => $end,
                        ];
                        DB::table('nt_oqa_file')->where('file_id', $file_id)->update($dataupdate);
                    }

                    //----------- LPC measurement L/T -------------

                }
                else
                {
                    $chk = 0;
                }
            });

            if($oqafile->stlpc_mail == null)
            {
                $dataupdate = [
                    'stlpc_mail' => 1,
                ];
                DB::table('nt_oqa_lab')->where('id_createlab', $oqafile->id_createlab)->update($dataupdate);
            }
            else if($oqafile->stlpc_mail == 1)
            {
                $dataupdate = [
                    'stlpc_mail' => 2,
                ];
                DB::table('nt_oqa_lab')->where('id_createlab', $oqafile->id_createlab)->update($dataupdate);
            }

            $resultlpc = nhkviewlpc::testlpc($oqafile->id_createlab,$oqafile->id_model);

            if (!is_array($resultlpc)) {
                $resultlpc = ['count' => 0, 'reasons' => []];
            }
            $lpc = intval($resultlpc['count']);

            $lpcUCL = nhkviewlpc::UCL($oqafile->id_createlab,$oqafile->id_model);

            $worksheet1 = $spreadsheet->setActiveSheetIndexByName('Seagate');

            $rawUCL = $worksheet1->getCell('R17')->getValue();
            $UCL_LPC = is_numeric($rawUCL) ? floatval($rawUCL) : 0;

            $subject = "[CAS program]_You have a new cleanliness results!!!";

            $data = [
                'subject'=>$subject,
                'testter'=>"LPC",
                'model'=>$oqafile->name_model,
                'lot'=>$oqafile->lotnumber_lab,
                'date'=>$oqafile->lpc_update,
            ];
            $mail = new MailController();
            $mail->mailconfirm($data);

            if($oqafile->stlpc_mail == null)
            {
                $dataupdate = [
                    'stlpc_mail' => 1,
                ];
                DB::table('nt_oqa_lab')->where('id_createlab', $oqafile->id_createlab)->update($dataupdate);

            }
            else if($oqafile->stlpc_mail == 1)
            {
                if($request->input('chkid') == 2)
                {
                $dataupdate = [
                    'stlpc_mail' => 2,
                ];
                DB::table('nt_oqa_lab')->where('id_createlab', $oqafile->id_createlab)->update($dataupdate);
            }
            }
            $oqafilemail =  DB::table('nt_oqa_lab')->where('id_createlab',$request->input('id'))->first();

            if(intval($resultlpc['count']) > 0){
                $totalmessenger = $resultlpc['reasons'][0] ?? '';
                if($lpcUCL && !empty($lpcUCL['Result'])){
                    $totalmessenger .= ' and '. $lpcUCL['Result'];
                }
            }else{
                $totalmessenger = ($lpcUCL && !empty($lpcUCL['Result'])) ? $lpcUCL['Result'] : '';
            }

            $finalUCL = $UCL_LPC;
            if ($lpcUCL) {
                if (isset($lpcUCL['UCL']) && is_numeric($lpcUCL['UCL'])) {
                    $finalUCL = floatval($lpcUCL['UCL']);
                }
            }

            if($chkid == 1 && $lpc > 0)
            {

                $data = [
                    'testter'=>"LPC",
                    'model'=>$oqafile->name_model,
                    'lot'=>$oqafile->lotnumber_lab,
                    'date'=>$oqafile->lpc_update,
                    'alarm'=>$lpc.' point',
                    'idqla'=>$oqafile->id_createlab,
                    'result'=> $totalmessenger,
                    'UCL'=> floatval($finalUCL),
                ];

                $mail = new MailController();
                $mail->mailalarm($data);
                $chk = 2;
            }
            else if($chkid == 2 && $lpc > 0)
            {
                $detaillpc = DB::table('nt_data_lpc')->where('id_createlab', $oqafile->id_createlab)->first();

                $data = DB::table('nt_oqa_lab')
                ->join('nt_model','nt_model.id_model','=','nt_oqa_lab.id_model')
                ->where('id_createlab', $oqafile->id_createlab)->first();

                return view('preview_send_chart', [
                    'id' =>$data->id_model,
                    'idcreatelab' => $data->id_createlab,
                    'laball' => $data,
                    'testter'=>"LPC",
                    'model'=>$oqafile->name_model,
                    'lot'=>$oqafile->lotnumber_lab,
                    'date'=>$oqafile->lpc_update,
                    'alarm'=>$lpc.' point',
                    'idqla'=>$oqafile->id_createlab,
                    'result'=> @$detaillpc->lpc_data,
                    'UCL'=> floatval($finalUCL),
                ]);

                $chk = 2;

            }

            DB::commit();
            return response()->json(['success' =>  $chk,'id'=> $request->input('id'),'chkid'=> $chkid,'lpc'=> $lpc,'messenger'=> $totalmessenger]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'เกิดข้อผิดพลาด: ' . $e->getMessage() .
                           ' ที่บรรทัด ' . $e->getLine() .
                           ' ในไฟล์ ' . $e->getFile()
            ], 500);
        }
    }

    public function chkconfirmsem(Request $request)
    {
        $id = $request->input('id');
        $chkid = $request->input('chkid');
        $oqafile =  DB::table('nt_oqa_file')
        ->join('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_oqa_file.id_createlab')
        ->join('nt_model', 'nt_model.id_model', '=', 'nt_oqa_lab.id_model')
        ->where('nt_oqa_file.id_createlab',$request->input('id'))->first();
        $file = storage_path('app/public/OQA/SEM/'.$oqafile->file_sem);
        $chk = '';

        // $sem = nhkviewsem::sem($oqafile->id_createlab,$oqafile->id_model);
        // dd($file);
        Excel::load($file, function($doc)use($id,$oqafile,&$chk,$chkid) {
            $sheet = $doc->setActiveSheetIndex(2);
            try {
                $cell = $sheet->getCell('P9')->getCalculatedValue();
                $P9 = $cell;
                $P11 = $sheet->getCell('P11')->getCalculatedValue();
            } catch (\PHPExcel_Calculation_Exception $e) {
                $cell = $sheet->getCell('P9')->getOldCalculatedValue();
                $P9 = $cell;
                $P11 = $sheet->getCell('P11')->getOldCalculatedValue();
            }

            if($cell != "")
            {
                $ftimechk = $sheet->getCell('I10')->getCalculatedValue();
                $ftime = date("Y/m/d H:i:s", strtotime($ftimechk));

                // getOldCalculatedValue
                // getCalculatedValue
                $oqasem = [
                    'sem_class1' => $sheet->getCell('P6')->getOldCalculatedValue(),
                    'sem_class2'=> $sheet->getCell('P7')->getCalculatedValue(),
                    'sem_class3' => $sheet->getCell('P8')->getOldCalculatedValue(),
                    'sem_class3_1' => $P9,
                    'sem_class3_2' => $sheet->getCell('P10')->getCalculatedValue(),
                    'sem_class3_3' => $P11,
                    'sem_class4' => $sheet->getCell('P12')->getOldCalculatedValue(),
                    'sem_class5' => $sheet->getCell('P13')->getOldCalculatedValue(),
                    'sem_class5_1' => $sheet->getCell('P14')->getOldCalculatedValue(),
                    'sem_class6' => $sheet->getCell('P15')->getOldCalculatedValue(),
                    'sem_datesave' => date('Y-m-d H:i:s'),
                    'sem_st' => 1,
                ];
                DB::table('nt_datacf_sem')->where('id_createlab', $id)->update($oqasem);

                $oqasem_head = [
                    'sem_class1' => $sheet->getCell('P6')->getOldCalculatedValue(),
                    'sem_class2'=> $sheet->getCell('P7')->getCalculatedValue(),
                    'sem_class3' => $sheet->getCell('P8')->getOldCalculatedValue(),
                    'sem_class3_1' => $P9,
                    'sem_class3_2' => $sheet->getCell('P10')->getCalculatedValue(),
                    'sem_class3_3' => $P11,
                    'sem_class4' => $sheet->getCell('P12')->getOldCalculatedValue(),
                    'sem_class5' => $sheet->getCell('P13')->getOldCalculatedValue(),
                    'sem_class5_1' => $sheet->getCell('P14')->getOldCalculatedValue(),
                    'sem_class6' => $sheet->getCell('P15')->getOldCalculatedValue(),
                    'sem_datesave' => date('Y-m-d H:i:s'),
                    'sem_st' => 1,
                ];
                DB::table('nt_data_sem')->where('id_createlab', $id)->update($oqasem_head);

                $oqasem_element = [];
                for ($i=21; $i < 108 ; $i++) {
                    $namecolumn = 'I'.$i;
                    $value = $sheet->getCell('K'.$i)->getOldCalculatedValue();
                    $value = ($value != null)? $value : 0;
                    $oqasem_element[$namecolumn] = $value;
                }
                DB::table('nt_data_sem')->where('id_createlab', $id)->update($oqasem_element);

                $updates = [
                'sem_confirm_st' => 1,
                'ftime_sem' =>$ftime,
                ];
                DB::table('nt_oqa_lab')->where('id_createlab', $id)->update($updates);
                $chk = 1;

                //----------- measurement L/T -------------
                $sheetLT = $doc->setActiveSheetIndexByName('Seagate Filtration');
                $file_id = $oqafile->file_id;
                $restart = $sheetLT->getCell('I13')->getValue();

                if (!empty($restart)) {
                    $start = $sheetLT->getCell('I15')->getOldCalculatedValue();
                    $end = $sheetLT->getCell('I10')->getCalculatedValue();

                    $start = str_replace(".0", "", $start);

                    $start = date('Y-m-d H:i:s',strtotime($start));
                    $end = date('Y-m-d H:i:s',strtotime($end));

                    $dataupdate = [
                        'sem_begin' => $start,
                        'sem_lasttime' => $end,
                    ];
                    DB::table('nt_oqa_file')->where('file_id', $file_id)->update($dataupdate);
                } else {
                    $start = $sheetLT->getCell('H10')->getCalculatedValue();
                    $end = $sheetLT->getCell('I10')->getCalculatedValue();

                    $start = date('Y-m-d H:i:s',strtotime($start));
                    $end = date('Y-m-d H:i:s',strtotime($end));

                    $dataupdate = [
                        'sem_begin' => $start,
                        'sem_lasttime' => $end,
                    ];
                    DB::table('nt_oqa_file')->where('file_id', $file_id)->update($dataupdate);
                }

                //----------- measurement L/T -------------

            }
            else
            {
                $chk = 0;
            }
        });
        $sem = nhkviewsem::sem($oqafile->id_createlab,$oqafile->id_model);
        $semgetresult = nhkviewsem::semgetresult($oqafile->id_createlab,$oqafile->id_model);
        $subject = "[CAS program]_You have a new cleanliness results!!!";

        $data = [
            'subject'=>$subject,
            'testter'=>"SEM",
            'model'=>$oqafile->name_model,
            'lot'=>$oqafile->lotnumber_lab,
            'date'=>$oqafile->sem_update,
        ];
        $mail = new MailController();
        $mail->mailconfirm($data);
        // nhkmail::mailconfirm($data);
        if($oqafile->stsem_mail == null)
        {
            $dataupdate = [
                'stsem_mail' => 1,
            ];
            DB::table('nt_oqa_lab')->where('id_createlab', $oqafile->id_createlab)->update($dataupdate);

        }
        else if($oqafile->stsem_mail == 1)
        {
            if($request->input('chkid') == 2)
            {
            $dataupdate = [
                'stsem_mail' => 2,
            ];
            DB::table('nt_oqa_lab')->where('id_createlab', $oqafile->id_createlab)->update($dataupdate);
            }

        }
        $oqafilemail =  DB::table('nt_oqa_lab')->where('id_createlab',$request->input('id'))->first();
        if($chkid == 1 && $sem > 0)
        {
            $data = [
                'testter'=>"SEM",
                'model'=>$oqafile->name_model,
                'lot'=>$oqafile->lotnumber_lab,
                'date'=>$oqafile->sem_update,
                'alarm'=>$sem.' point',
                'idqla'=>$oqafile->id_createlab,
                'result' => !empty($semgetresult['result']) ? implode(", ", $semgetresult['result']) : '-',
                'UCL' => !empty($semgetresult['ucl']) ? implode(", ", $semgetresult['ucl']) : '-',
            ];
            $mail = new MailController();
            $mail->mailalarm($data);
            // nhkmail::mailalarm($data);
            $chk = 2;
        }
        else if($chkid == 2 && $sem > 0)
        {
            $data = [
                'testter'=>"SEM",
                'model'=>$oqafile->name_model,
                'lot'=>$oqafile->lotnumber_lab,
                'date'=>$oqafile->sem_update,
                'alarm'=>$sem.' point',
                'idqla'=>$oqafile->id_createlab,
                'result' => !empty($semgetresult['result']) ? implode(", ", $semgetresult['result']) : '-',
                'UCL' => !empty($semgetresult['ucl']) ? implode(", ", $semgetresult['ucl']) : '-',
            ];

            $mail = new MailController();
            $mail->mailalarmstaff($data);
            // nhkmail::mailalarmstaff($data);
            $chk = 2;
        }

        return response()->json(['success' => $chk,'id'=> $request->input('id'),'chkid'=> $chkid,'sem'=> $sem]);
    }

    public function chkconfirmic(Request $request)
    {
        $id = $request->input('id');
        $oqafile =  DB::table('nt_oqa_file')
        ->join('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_oqa_file.id_createlab')
        ->join('nt_model', 'nt_model.id_model', '=', 'nt_oqa_lab.id_model')
        ->where('nt_oqa_file.id_createlab',$request->input('id'))->first();
        $file = storage_path('app/public/OQA/IC/'.$oqafile->file_ic);
        $chk = 0;
        $chkid = $request->input('chkid');
        // $ic = nhkviewic::ic($oqafile->id_createlab,$oqafile->id_model);
        // dd($ic);
         Excel::load($file, function($doc)use($id,$oqafile,&$chk,$chkid) {
            $sheet = $doc->setActiveSheetIndex(0);
            $celli25=$sheet->getCell('I25')->getCalculatedValue();
            $celli23=$sheet->getCell('I23')->getCalculatedValue();
            $cells24=$sheet->getCell('S24')->getCalculatedValue();
            if($celli25 != "" || $celli23 != "" || $cells24 != "")
            {
                $ftimechk = $sheet->getCell('M9')->getCalculatedValue();
                $ftime = date("Y/m/d H:i:s", strtotime($ftimechk));
                if( $sheet->getCell('S18')->getCalculatedValue() == '#DIV/0!' || $sheet->getCell('S18')->getCalculatedValue() == '')
                {
                    $s18 = null;
                }
                else
                {
                    $s18 = $sheet->getCell('S18')->getCalculatedValue();
                }
                if( $sheet->getCell('S19')->getCalculatedValue() == '#DIV/0!'|| $sheet->getCell('S19')->getCalculatedValue() == '')
                {
                    $s19 = null;
                }
                else{
                    $s19 = $sheet->getCell('S19')->getCalculatedValue();
                }
                if( $sheet->getCell('S20')->getCalculatedValue() == '#DIV/0!'|| $sheet->getCell('S20')->getCalculatedValue() == '')
                {
                    $s20 = null;
                }
                else
                {
                    $s20 = $sheet->getCell('S20')->getCalculatedValue();
                }
                if( $sheet->getCell('S21')->getCalculatedValue() == '#DIV/0!'|| $sheet->getCell('S21')->getCalculatedValue() == '')
                {
                    $s21 = null;
                }
                else
                {
                    $s21 = $sheet->getCell('S21')->getCalculatedValue();
                }
                if( $sheet->getCell('S22')->getCalculatedValue() == '#DIV/0!'|| $sheet->getCell('S22')->getCalculatedValue() == '')
                {
                    $s22 = null;
                }
                else
                {
                    $s22 = $sheet->getCell('S22')->getCalculatedValue();
                }
                if( $sheet->getCell('S23')->getCalculatedValue() == '#DIV/0!'|| $sheet->getCell('S23')->getCalculatedValue() == '')
                {
                    $s23 = null;
                }
                else
                {
                    $s23 = $sheet->getCell('S23')->getCalculatedValue();
                }
                if( $sheet->getCell('S24')->getCalculatedValue() == '#DIV/0!' || $sheet->getCell('S24')->getCalculatedValue() == '')
                {
                    $s24 = null;
                }
                else
                {
                    $s24 = $sheet->getCell('S24')->getCalculatedValue();
                }
                $oqaic = [
                    'ic_anion_fluoride' => $sheet->getCell('I18')->getCalculatedValue(),
                    'ic_anion_chloride' => $sheet->getCell('I19')->getCalculatedValue(),
                    'ic_anion_nitrile' => $sheet->getCell('I20')->getCalculatedValue(),
                    'ic_anion_bromid' => $sheet->getCell('I21')->getCalculatedValue(),
                    'ic_anion_nitrate' => $sheet->getCell('I22')->getCalculatedValue(),
                    'ic_anion_sulphate' => $sheet->getCell('I23')->getCalculatedValue(),
                    'ic_anion_phosphate' => $sheet->getCell('I24')->getCalculatedValue(),
                    'ic_anion_up' => $sheet->getCell('I25')->getCalculatedValue(),
                    'ic_sulphate' => $sheet->getCell('I23')->getCalculatedValue(),
                    'ic_cation_lithium' => $s18,
                    'ic_cation_sodium' => $s19,
                    'ic_cation_ammonium' => $s20,
                    'ic_cation_potassium' => $s21,
                    'ic_cation_magnesium' => $s22,
                    'ic_cation_calcium' => $s23,
                    'ic_cation_up' => $s24,
                    'ic_datesave' =>date('Y-m-d H:i:s'),
                    'ic_st' => 1,
                ];
                DB::table('nt_data_ic')->where('id_createlab', $id)->update($oqaic);
                DB::table('nt_datacf_ic')->where('id_createlab', $id)->update($oqaic);
                $updates = [
                'ic_confirm_st' => 1,
                'ftime_ic' =>$ftime,
                ];
                DB::table('nt_oqa_lab')->where('id_createlab', $id)->update($updates);
                $chk = 1;

                //----------- measurement L/T -------------
                $file_id = $oqafile->file_id;
                $restart = $sheet->getCell('S8')->getValue();

                if (!empty($restart)) {
                    $start = $sheet->getCell('S9')->getOldCalculatedValue();
                    $end = $sheet->getCell('M9')->getCalculatedValue();

                    $start = str_replace(".0", "", $start);

                    $start = date('Y-m-d H:i:s',strtotime($start));
                    $end = date('Y-m-d H:i:s',strtotime($end));

                    $dataupdate = [
                        'ic_begin' => $start,
                        'ic_lasttime' => $end,
                    ];
                    DB::table('nt_oqa_file')->where('file_id', $file_id)->update($dataupdate);
                } else {
                    $start = $sheet->getCell('L9')->getCalculatedValue();
                    $end = $sheet->getCell('M9')->getCalculatedValue();

                    $start = date('Y-m-d H:i:s',strtotime($start));
                    $end = date('Y-m-d H:i:s',strtotime($end));

                    $dataupdate = [
                        'ic_begin' => $start,
                        'ic_lasttime' => $end,
                    ];
                    DB::table('nt_oqa_file')->where('file_id', $file_id)->update($dataupdate);
                }
                //----------- measurement L/T -------------

            }
            else
            {
                $chk = 0;
            }
        });
        $ic = nhkviewic::ic($oqafile->id_createlab,$oqafile->id_model);
        $subject = "[CAS program]_You have a new cleanliness results!!!";
        $data = [
            'subject'=>$subject,
            'testter'=>"IC",
            'model'=>$oqafile->name_model,
            'lot'=>$oqafile->lotnumber_lab,
            'date'=>$oqafile->ic_update,
        ];
        $mail = new MailController();
        $mail->mailconfirm($data);
        // nhkmail::mailconfirm($data);
        if($oqafile->stic_mail == null)
        {
            $dataupdate = [
                'stic_mail' => 1,
            ];
            DB::table('nt_oqa_lab')->where('id_createlab', $oqafile->id_createlab)->update($dataupdate);

        }
        else if($oqafile->stic_mail == 1)
        {   if($request->input('chkid') == 2)
            {
            $dataupdate = [
                'stic_mail' => 2,
            ];
            DB::table('nt_oqa_lab')->where('id_createlab', $oqafile->id_createlab)->update($dataupdate);
            }

        }
        $oqafilemail =  DB::table('nt_oqa_lab')->where('id_createlab',$request->input('id'))->first();
        if($chkid == 1 && $ic > 0)
        {
            $data = [
                'testter'=>"IC",
                'model'=>$oqafile->name_model,
                'lot'=>$oqafile->lotnumber_lab,
                'date'=>$oqafile->ic_update,
                'alarm'=>$ic.' point',
                'idqla'=>$oqafile->id_createlab,
            ];
            $mail = new MailController();
            $mail->mailalarm($data);
            // nhkmail::mailalarm($data);
            $chk = 2;
        }
        else if($chkid == 2 && $ic > 0)
        {
            $data = [
                'testter'=>"IC",
                'model'=>$oqafile->name_model,
                'lot'=>$oqafile->lotnumber_lab,
                'date'=>$oqafile->ic_update,
                'alarm'=>$ic.' point',
                'idqla'=>$oqafile->id_createlab,
            ];
            $mail = new MailController();
            $mail->mailalarmstaff($data);
            // nhkmail::mailalarmstaff($data);
            $chk = 2;
        }
        return response()->json(['success' =>  $chk,'id'=> $request->input('id')]);
    }

    public function chkconfirmgcms(Request $request)
    {
        $id = $request->input('id');
        $oqafile =  DB::table('nt_oqa_file')
        ->join('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_oqa_file.id_createlab')
        ->join('nt_model', 'nt_model.id_model', '=', 'nt_oqa_lab.id_model')
        ->where('nt_oqa_file.id_createlab',$request->input('id'))->first();
        // $gcms = nhkviewgcms::gcms($oqafile->id_createlab,$oqafile->id_model);

        $countgcms =  DB::table('nt_outgassing_gcms')->where('id_model',$oqafile->id_model)->count();
        $file = storage_path('app/public/OQA/GCMS/'.$oqafile->file_gcms);
        $chk = 0;
        $chkid = $request->input('chkid');

        Excel::load($file, function($doc)use($id,$oqafile,$countgcms,&$chk,$chkid) {

            $sheet = $doc->setActiveSheetIndexByName('Sheet1');
            $cell=$sheet->getCell('E75')->getCalculatedValue();

            if($cell != "")
            {

                $ftimechk = $sheet->getCell('C14')->getCalculatedValue();
                $ftime = date("Y/m/d H:i:s", strtotime($ftimechk));
                $B21 = $sheet->getCell('E21')->getCalculatedValue();
                $B32 = $sheet->getCell('E32')->getCalculatedValue();
                $B37 = $sheet->getCell('E37')->getCalculatedValue();
                $B45 = $sheet->getCell('E45')->getCalculatedValue();
                $B46 = $sheet->getCell('E46')->getCalculatedValue();
                $B65 = $sheet->getCell('E65')->getCalculatedValue();

                $sum_smear = $B21+ $B32+ $B37+$B45+$B46+$B65;
                $oqagcms = [
                    'gcms_smear' => $sum_smear,
                    'gcms_outgassing' => $sheet->getCell('E75')->getCalculatedValue(),
                    'gcms_low_mw' => $sheet->getCell('E73')->getCalculatedValue(),
                    'gcms_high_mw' => $sheet->getCell('E74')->getCalculatedValue(),
                    'gcms_datesave' => date('Y-m-d H:i:s'),
                    'gcms_st' => 1,
                ];


                $outg = [
                    'id_model' => $oqafile->id_model,
                    'id_createlab' => $oqafile->id_createlab,
                    'savedate' => date('Y-m-d H:i:s'),
                    'c1'=>$sheet->getCell('E17')->getCalculatedValue(),
                    'c2'=>$sheet->getCell('E18')->getCalculatedValue(),
                    'c3'=>$sheet->getCell('E19')->getCalculatedValue(),
                    'c4'=>$sheet->getCell('E20')->getCalculatedValue(),
                    'c5'=>$sheet->getCell('E21')->getCalculatedValue(),
                    'c5_1'=>$sheet->getCell('E22')->getCalculatedValue(),
                    'c5_2'=>$sheet->getCell('E23')->getCalculatedValue(),
                    'c5_3'=>$sheet->getCell('E24')->getCalculatedValue(),
                    'c6'=>$sheet->getCell('E25')->getCalculatedValue(),
                    'c6_1'=>$sheet->getCell('E26')->getCalculatedValue(),
                    'c6_2'=>$sheet->getCell('E27')->getCalculatedValue(),
                    'c7'=>$sheet->getCell('E28')->getCalculatedValue(),
                    'c8'=>$sheet->getCell('E29')->getCalculatedValue(),
                    'c8_1'=>$sheet->getCell('E30')->getCalculatedValue(),
                    'c8_2'=>$sheet->getCell('E31')->getCalculatedValue(),
                    'c9'=>$sheet->getCell('E32')->getCalculatedValue(),
                    'c9_1'=>$sheet->getCell('E33')->getCalculatedValue(),
                    'c9_2'=>$sheet->getCell('E34')->getCalculatedValue(),
                    'c10'=>$sheet->getCell('E35')->getCalculatedValue(),
                    'c11'=>$sheet->getCell('E36')->getCalculatedValue(),
                    'c11_1'=>$sheet->getCell('E37')->getCalculatedValue(),
                    'c11_2'=>$sheet->getCell('E38')->getCalculatedValue(),
                    'c12'=>$sheet->getCell('E39')->getCalculatedValue(),
                    'c13'=>$sheet->getCell('E40')->getCalculatedValue(),
                    'c14'=>$sheet->getCell('E41')->getCalculatedValue(),
                    'c15'=>$sheet->getCell('E42')->getCalculatedValue(),
                    'c15_1'=>$sheet->getCell('E43')->getCalculatedValue(),
                    'c15_2'=>$sheet->getCell('E44')->getCalculatedValue(),
                    'c16'=>$sheet->getCell('E45')->getCalculatedValue(),
                    'c17'=>$sheet->getCell('E46')->getCalculatedValue(),
                    'c18'=>$sheet->getCell('E47')->getCalculatedValue(),
                    'c19'=>$sheet->getCell('E48')->getCalculatedValue(),
                    'c19_1'=>$sheet->getCell('E49')->getCalculatedValue(),
                    'c19_2'=>$sheet->getCell('E50')->getCalculatedValue(),
                    'c19_3'=>$sheet->getCell('E51')->getCalculatedValue(),
                    'c20'=>$sheet->getCell('E52')->getCalculatedValue(),
                    'c20_1'=>$sheet->getCell('E53')->getCalculatedValue(),
                    'c21'=>$sheet->getCell('E54')->getCalculatedValue(),
                    'c21_1'=>$sheet->getCell('E55')->getCalculatedValue(),
                    'c22'=>$sheet->getCell('E56')->getCalculatedValue(),
                    'c22_1'=>$sheet->getCell('E57')->getCalculatedValue(),
                    'c22_2'=>$sheet->getCell('E58')->getCalculatedValue(),
                    'c23'=>$sheet->getCell('E59')->getCalculatedValue(),
                    'c23_1'=>$sheet->getCell('E60')->getCalculatedValue(),
                    'c24'=>$sheet->getCell('E61')->getCalculatedValue(),
                    'c24_1'=>$sheet->getCell('E62')->getCalculatedValue(),
                    'c24_2'=>$sheet->getCell('E63')->getCalculatedValue(),
                    'c25'=>$sheet->getCell('E64')->getCalculatedValue(),
                    'c26'=>$sheet->getCell('E65')->getCalculatedValue(),
                    'c27'=>$sheet->getCell('E66')->getCalculatedValue(),
                    'c28'=>$sheet->getCell('E67')->getCalculatedValue(),
                    'c29'=>$sheet->getCell('E68')->getCalculatedValue(),
                    'c30'=>$sheet->getCell('E69')->getCalculatedValue(),
                    'c31'=>$sheet->getCell('E70')->getCalculatedValue(),
                    'c32'=>$sheet->getCell('E71')->getCalculatedValue(),
                    'c33'=>$sheet->getCell('E72')->getCalculatedValue(),
                    'c34'=>$sheet->getCell('E73')->getCalculatedValue(),
                    'c35'=>$sheet->getCell('E74')->getCalculatedValue(),
                    'total'=>$sheet->getCell('E75')->getCalculatedValue(),

                    'c19_4'=>$sheet->getCell('E76')->getCalculatedValue(),
                    'c19_5'=>$sheet->getCell('E77')->getCalculatedValue(),
                    'c35_1'=>$sheet->getCell('E78')->getCalculatedValue(),
                    'c35_2'=>$sheet->getCell('E79')->getCalculatedValue(),

                    'spare_01'=>(($sheet->getCell('E80')->getCalculatedValue() != 'N/A')? $sheet->getCell('E80')->getCalculatedValue() : null),
                    'spare_02'=>(($sheet->getCell('E81')->getCalculatedValue() != 'N/A')? $sheet->getCell('E81')->getCalculatedValue() : null),
                    'spare_03'=>(($sheet->getCell('E82')->getCalculatedValue() != 'N/A')? $sheet->getCell('E82')->getCalculatedValue() : null),
                    'spare_04'=>(($sheet->getCell('E83')->getCalculatedValue() != 'N/A')? $sheet->getCell('E83')->getCalculatedValue() : null),
                    'spare_05'=>(($sheet->getCell('E84')->getCalculatedValue() != 'N/A')? $sheet->getCell('E84')->getCalculatedValue() : null),
                    'spare_06'=>(($sheet->getCell('E85')->getCalculatedValue() != 'N/A')? $sheet->getCell('E86')->getCalculatedValue() : null),
                    'spare_07'=>(($sheet->getCell('E86')->getCalculatedValue() != 'N/A')? $sheet->getCell('E86')->getCalculatedValue() : null),
                    'spare_08'=>(($sheet->getCell('E87')->getCalculatedValue() != 'N/A')? $sheet->getCell('E87')->getCalculatedValue() : null),
                    'spare_09'=>(($sheet->getCell('E88')->getCalculatedValue() != 'N/A')? $sheet->getCell('E88')->getCalculatedValue() : null),
                    'spare_10'=>(($sheet->getCell('E89')->getCalculatedValue() != 'N/A')? $sheet->getCell('E89')->getCalculatedValue() : null),
                    'spare_11'=>(($sheet->getCell('E90')->getCalculatedValue() != 'N/A')? $sheet->getCell('E90')->getCalculatedValue() : null),
                    'spare_12'=>(($sheet->getCell('E91')->getCalculatedValue() != 'N/A')? $sheet->getCell('E91')->getCalculatedValue() : null),
                    'spare_13'=>(($sheet->getCell('E92')->getCalculatedValue() != 'N/A')? $sheet->getCell('E92')->getCalculatedValue() : null),
                    'spare_14'=>(($sheet->getCell('E93')->getCalculatedValue() != 'N/A')? $sheet->getCell('E93')->getCalculatedValue() : null),
                    'spare_15'=>(($sheet->getCell('E94')->getCalculatedValue() != 'N/A')? $sheet->getCell('E94')->getCalculatedValue() : null),
                    'spare_16'=>(($sheet->getCell('E95')->getCalculatedValue() != 'N/A')? $sheet->getCell('E95')->getCalculatedValue() : null),
                    'spare_17'=>(($sheet->getCell('E96')->getCalculatedValue() != 'N/A')? $sheet->getCell('E96')->getCalculatedValue() : null),
                    'spare_18'=>(($sheet->getCell('E97')->getCalculatedValue() != 'N/A')? $sheet->getCell('E97')->getCalculatedValue() : null),
                    'spare_19'=>(($sheet->getCell('E98')->getCalculatedValue() != 'N/A')? $sheet->getCell('E98')->getCalculatedValue() : null),
                    'spare_20'=>(($sheet->getCell('E99')->getCalculatedValue() != 'N/A')? $sheet->getCell('E99')->getCalculatedValue() : null),
                    'spare_21'=>(($sheet->getCell('E100')->getCalculatedValue() != 'N/A')? $sheet->getCell('E100')->getCalculatedValue() : null),
                    'spare_22'=>(($sheet->getCell('E101')->getCalculatedValue() != 'N/A')? $sheet->getCell('E101')->getCalculatedValue() : null),
                    'spare_23'=>(($sheet->getCell('E102')->getCalculatedValue() != 'N/A')? $sheet->getCell('E102')->getCalculatedValue() : null),
                    'spare_24'=>(($sheet->getCell('E103')->getCalculatedValue() != 'N/A')? $sheet->getCell('E103')->getCalculatedValue() : null),
                    'spare_25'=>(($sheet->getCell('E104')->getCalculatedValue() != 'N/A')? $sheet->getCell('E104')->getCalculatedValue() : null),
                    'spare_26'=>(($sheet->getCell('E105')->getCalculatedValue() != 'N/A')? $sheet->getCell('E105')->getCalculatedValue() : null),
                    'spare_27'=>(($sheet->getCell('E106')->getCalculatedValue() != 'N/A')? $sheet->getCell('E106')->getCalculatedValue() : null),
                    'spare_28'=>(($sheet->getCell('E107')->getCalculatedValue() != 'N/A')? $sheet->getCell('E107')->getCalculatedValue() : null),
                    'spare_29'=>(($sheet->getCell('E108')->getCalculatedValue() != 'N/A')? $sheet->getCell('E108')->getCalculatedValue() : null),
                    'spare_30'=>(($sheet->getCell('E109')->getCalculatedValue() != 'N/A')? $sheet->getCell('E109')->getCalculatedValue() : null),
                    'spare_31'=>(($sheet->getCell('E110')->getCalculatedValue() != 'N/A')? $sheet->getCell('E110')->getCalculatedValue() : null),
                    'spare_32'=>(($sheet->getCell('E111')->getCalculatedValue() != 'N/A')? $sheet->getCell('E111')->getCalculatedValue() : null),
                    'spare_33'=>(($sheet->getCell('E112')->getCalculatedValue() != 'N/A')? $sheet->getCell('E112')->getCalculatedValue() : null),
                    'spare_34'=>(($sheet->getCell('E113')->getCalculatedValue() != 'N/A')? $sheet->getCell('E113')->getCalculatedValue() : null),
                    'spare_35'=>(($sheet->getCell('E114')->getCalculatedValue() != 'N/A')? $sheet->getCell('E114')->getCalculatedValue() : null),
                    'spare_36'=>(($sheet->getCell('E115')->getCalculatedValue() != 'N/A')? $sheet->getCell('E115')->getCalculatedValue() : null),
                    'spare_37'=>(($sheet->getCell('E116')->getCalculatedValue() != 'N/A')? $sheet->getCell('E116')->getCalculatedValue() : null),

                ];
                $countoutg =  DB::table('nt_outgassing_gcms')
                ->where('id_model',$oqafile->id_model)
                ->where('id_createlab',$oqafile->id_createlab)->count();
                if( $countoutg == 0 ){
                   DB::table('nt_outgassing_gcms')->insert($outg);
                }
                else
                {
                    DB::table('nt_outgassing_gcms')->where('id_createlab', $oqafile->id_createlab)->where('id_model', $oqafile->id_model)->update($outg);
                }

                DB::table('nt_datacf_gcms')->where('id_createlab', $id)->update($oqagcms);
                DB::table('nt_data_gcms')->where('id_createlab', $id)->update($oqagcms);
                $updates = [
                   'gcms_confirm_st' => 1,
                   'ftime_gcms' =>$ftime,
                ];
                DB::table('nt_oqa_lab')->where('id_createlab', $id)->update($updates);

                //----------- measurement L/T -------------

                $file_id = $oqafile->file_id;
                $restart = $sheet->getCell('J12')->getValue();

                if (!empty($restart)) {
                    $start = $sheet->getCell('J14')->getOldCalculatedValue();
                    $end = $sheet->getCell('C14')->getCalculatedValue();

                    $start = str_replace(".0", "", $start);

                    $start = date('Y-m-d H:i:s',strtotime($start));
                    $end = date('Y-m-d H:i:s',strtotime($end));

                    $dataupdate = [
                        'gcms_begin' => $start,
                        'gcms_lasttime' => $end,
                    ];
                    DB::table('nt_oqa_file')->where('file_id', $file_id)->update($dataupdate);
                } else {
                    $start = $sheet->getCell('B14')->getCalculatedValue();
                    $end = $sheet->getCell('C14')->getCalculatedValue();

                    $start = date('Y-m-d H:i:s',strtotime($start));
                    $end = date('Y-m-d H:i:s',strtotime($end));

                    $dataupdate = [
                        'gcms_begin' => $start,
                        'gcms_lasttime' => $end,
                    ];
                    DB::table('nt_oqa_file')->where('file_id', $file_id)->update($dataupdate);
                }
                //----------- measurement L/T -------------

                $chk = 1;
            }
            else
            {
                $chk = 0;
            }
        });
        $gcms = nhkviewgcms::gcms($oqafile->id_createlab,$oqafile->id_model);

        $subject = "[CAS program]_You have a new cleanliness results!!!";

        $data = [
            'subject'=>$subject,
            'testter'=>"GCMS",
            'model'=>$oqafile->name_model,
            'lot'=>$oqafile->lotnumber_lab,
            'date'=>$oqafile->gcms_update,
        ];
        $mail = new MailController();
        $mail->mailconfirm($data);
        // // nhkmail::mailconfirm($data);
        if($oqafile->stgcms_mail == null)
        {
            $dataupdate = [
                'stgcms_mail' => 1,
            ];
            DB::table('nt_oqa_lab')->where('id_createlab', $oqafile->id_createlab)->update($dataupdate);

        }
        else if($oqafile->stgcms_mail == 1)
        {
            if($request->input('chkid') == 2)
            {
                $dataupdate = [
                    'stgcms_mail' => 2,
                ];
                DB::table('nt_oqa_lab')->where('id_createlab', $oqafile->id_createlab)->update($dataupdate);
            }

        }
        $oqafilemail =  DB::table('nt_oqa_lab')->where('id_createlab',$request->input('id'))->first();

        // -------------- check status Sigma ratio ----------------------
        $checkretio = new ResultsControlle();
        $val_checkretio =  $checkretio->chkconfirmRatio($oqafile->id_createlab);
        if($val_checkretio > 0){
            $gcms += intval($val_checkretio);
        }
        // -------------- check status Sigma ratio ----------------------

        if($chkid == 1 && $gcms > 0)
        {
            $data = [
                'testter'=>"GCMS",
                'model'=>$oqafile->name_model,
                'lot'=>$oqafile->lotnumber_lab,
                'date'=>$oqafile->gcms_update,
                'alarm'=>$gcms.' point',
                'idqla'=>$oqafile->id_createlab,
            ];
            $mail = new MailController();
            $mail->mailalarm($data);
            // // nhkmail::mailalarm($data);
            $chk = 2;
        }
        else if($chkid == 2 && $gcms > 0)
        {
            $data = [
                'testter'=>"GCMS",
                'model'=>$oqafile->name_model,
                'lot'=>$oqafile->lotnumber_lab,
                'date'=>$oqafile->gcms_update,
                'alarm'=>$gcms.' point',
                'idqla'=>$oqafile->id_createlab,
            ];
            $mail = new MailController();
            $mail->mailalarmstaff($data);
            // nhkmail::mailalarmstaff($data);
            $chk = 2;
        }
        return response()->json(['success' =>  $chk,'id'=> $request->input('id')]);
    }

    public function chkconfirmftir(Request $request)
    {
        $id = $request->input('id');
        $oqafile =  DB::table('nt_oqa_file')
        ->join('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_oqa_file.id_createlab')
        ->join('nt_model', 'nt_model.id_model', '=', 'nt_oqa_lab.id_model')
        ->where('nt_oqa_file.id_createlab',$request->input('id'))->first();
       //$ftir = nhkviewftir::ftir($oqafile->id_createlab,$oqafile->id_model);
        //dd('ftir');
        $file = storage_path('app/public/OQA/FTIR/'.$oqafile->file_ftir);
        $chk = 0;
        $chkid = $request->input('chkid');

        Excel::load($file, function($doc)use($id,$oqafile,&$chk,$chkid) {
            $sheet = $doc->setActiveSheetIndex(0);
            $values=$sheet->getCell('B42')->getCalculatedValue();
            $cell = "$values";

            if($cell != "" || $cell != null)
            {
               // dd($x);
                $ftimechk = $sheet->getCell('B76')->getCalculatedValue();
                $ftime = date("Y/m/d H:i:s", strtotime($ftimechk));
                $oqaftir = [
                    'ftir_silicone' => $sheet->getCell('B42')->getCalculatedValue(),
                    'ftir_datesave' => date('Y-m-d H:i:s'),
                    'ftir_st' => 1,
                ];
                DB::table('nt_data_ftir')->where('id_createlab', $id)->update($oqaftir);
                DB::table('nt_datacf_ftir')->where('id_createlab', $id)->update($oqaftir);
                $updates = [
                'ftir_confirm_st' => 1,
                'ftime_ftir' =>$ftime,
                ];
                DB::table('nt_oqa_lab')->where('id_createlab', $id)->update($updates);
                $chk = 1;

                //----------- measurement L/T -------------
                $file_id = $oqafile->file_id;
                $restart = $sheet->getCell('I74')->getValue();
                if (!empty($restart)) {
                    $start = $sheet->getCell('I76')->getOldCalculatedValue();
                    $end = $sheet->getCell('B76')->getCalculatedValue();

                    $start = str_replace(".0", "", $start);

                    $start = date('Y-m-d H:i:s',strtotime($start));
                    $end = date('Y-m-d H:i:s',strtotime($end));

                    $dataupdate = [
                        'ftir_begin' => $start,
                        'ftir_lasttime' => $end,
                    ];
                    DB::table('nt_oqa_file')->where('file_id', $file_id)->update($dataupdate);
                } else {
                    $start = $sheet->getCell('B75')->getCalculatedValue();
                    $end = $sheet->getCell('B76')->getCalculatedValue();

                    $start = date('Y-m-d H:i:s',strtotime($start));
                    $end = date('Y-m-d H:i:s',strtotime($end));

                    $dataupdate = [
                        'ftir_begin' => $start,
                        'ftir_lasttime' => $end,
                    ];
                    DB::table('nt_oqa_file')->where('file_id', $file_id)->update($dataupdate);
                }

                //----------- measurement L/T -------------

            }
            else
            {
                $chk = 0;
            }
        });
        $ftir = nhkviewftir::ftir($oqafile->id_createlab,$oqafile->id_model);

        $subject = "[CAS program]_You have a new cleanliness results!!!";

        $data = [
            'subject'=>$subject,
            'testter'=>"FTIR",
            'model'=>$oqafile->name_model,
            'lot'=>$oqafile->lotnumber_lab,
            'date'=>$oqafile->ftir_update,
        ];
        $mail = new MailController();
        $mail->mailconfirm($data);
        // nhkmail::mailconfirm($data);

        if($oqafile->stftir_mail == null)
        {
            $dataupdate = [
                'stftir_mail' => 1,
            ];
            DB::table('nt_oqa_lab')->where('id_createlab', $oqafile->id_createlab)->update($dataupdate);

        }
        else if($oqafile->stftir_mail == 1)
        {   if($request->input('chkid') == 2)
            {
                $dataupdate = [
                    'stftir_mail' => 2,
                ];
                DB::table('nt_oqa_lab')->where('id_createlab', $oqafile->id_createlab)->update($dataupdate);
            }

        }
        $oqafilemail =  DB::table('nt_oqa_lab')->where('id_createlab',$request->input('id'))->first();

        if($chkid == 1 && $ftir > 0)
        {
            $data = [
                'testter'=>"FTIR",
                'model'=>$oqafile->name_model,
                'lot'=>$oqafile->lotnumber_lab,
                'date'=>$oqafile->ftir_update,
                'alarm'=>$ftir.' point',
                'idqla'=>$oqafile->id_createlab,
            ];
            $mail = new MailController();
            $mail->mailalarm($data);
            // nhkmail::mailalarm($data);
            $chk = 2;
        }
        else if($chkid == 2 && $ftir > 0)
        {
            $data = [
                'testter'=>"FTIR",
                'model'=>$oqafile->name_model,
                'lot'=>$oqafile->lotnumber_lab,
                'date'=>$oqafile->ftir_update,
                'alarm'=>$ftir.' point',
                'idqla'=>$oqafile->id_createlab,
            ];

            $mail = new MailController();
            $mail->mailalarmstaff($data);
            // nhkmail::mailalarmstaff($data);
            $chk = 2;
        }

        return response()->json(['success' =>  $chk,'id'=> $request->input('id')]);
    }

    public function chkconfirmsemtest(Request $request)
    {
        $id = $request->input('id');
        $oqafile =  DB::table('nt_oqa_file')
        ->join('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_oqa_file.id_createlab')
        ->join('nt_model', 'nt_model.id_model', '=', 'nt_oqa_lab.id_model')
        ->where('nt_oqa_file.id_createlab',$request->input('id'))->first();
        $file = storage_path('app/public/OQA/GhostTest/'.$oqafile->file_semtest);
        $chk = 0;
        $chkid = $request->input('chkid');

        Excel::load($file, function($doc)use($id,$oqafile,&$chk,$chkid) {
            $sheet = $doc->setActiveSheetIndex(0);
            $values=$sheet->getCell('H10')->getCalculatedValue();
            $cell = "$values";

            if($cell != "" || $cell != null)
            {
               // dd($x);
                $ftimechk = $sheet->getCell('J4')->getCalculatedValue();
                $ftime = date("Y/m/d H:i:s", strtotime($ftimechk));
                $oqasemtest = [
                    'semtest_data' => $sheet->getCell('H10')->getCalculatedValue(),
                    'alkane' => $sheet->getCell('D26')->getCalculatedValue(),
                    'rhc' => $sheet->getCell('E26')->getCalculatedValue(),
                    'unknowns' => $sheet->getCell('F26')->getCalculatedValue(),
                    'low_mw' => $sheet->getCell('G26')->getCalculatedValue(),
                    'high_mw' => $sheet->getCell('H26')->getCalculatedValue(),
                    'dop1' => $sheet->getCell('I26')->getCalculatedValue(),
                    'dop2' => $sheet->getCell('J26')->getCalculatedValue(),
                    'total' => $sheet->getCell('K26')->getCalculatedValue(),
                    'semtest_datesave' => date('Y-m-d H:i:s'),
                    'semtest_st' => 1,
                ];
                DB::table('nt_data_semtest')->where('id_createlab', $id)->update($oqasemtest);
                DB::table('nt_datacf_semtest')->where('id_createlab', $id)->update($oqasemtest);
                $updates = [
                'semtest_confirm_st' => 1,
                'ftime_semtest' =>$ftime,
                ];
                DB::table('nt_oqa_lab')->where('id_createlab', $id)->update($updates);
                $chk = 1;

                //----------- measurement L/T -------------

                $file_id = $oqafile->file_id;
                $restart = $sheet->getCell('P2')->getValue();
                if (!empty($restart)) {
                    $start = $sheet->getCell('P4')->getOldCalculatedValue();
                    $end = $sheet->getCell('J4')->getCalculatedValue();

                    $start = str_replace(".0", "", $start);

                    $start = date('Y-m-d H:i:s',strtotime($start));
                    $end = date('Y-m-d H:i:s',strtotime($end));

                    $dataupdate = [
                        'semtest_begin' => $start,
                        'semtest_lasttime' => $end,
                    ];
                    DB::table('nt_oqa_file')->where('file_id', $file_id)->update($dataupdate);
                } else {
                    $start = $sheet->getCell('J3')->getCalculatedValue();
                    $end = $sheet->getCell('J4')->getCalculatedValue();

                    $start = date('Y-m-d H:i:s',strtotime($start));
                    $end = date('Y-m-d H:i:s',strtotime($end));

                    $dataupdate = [
                        'semtest_begin' => $start,
                        'semtest_lasttime' => $end,
                    ];
                    DB::table('nt_oqa_file')->where('file_id', $file_id)->update($dataupdate);
                }

                //----------- measurement L/T -------------
            }
            else
            {
                $chk = 0;
            }
        });
        $semtest = nhkviewsemtest::semtest($oqafile->id_createlab,$oqafile->id_model);
        $subject = "[CAS program]_You have a new cleanliness results!!!";

        $data = [
            'subject'=>$subject,
            'testter'=>"GhostTest",
            'model'=>$oqafile->name_model,
            'lot'=>$oqafile->lotnumber_lab,
            'date'=>$oqafile->semtest_update,
        ];
        $mail = new MailController();
        $mail->mailconfirm($data);
        // nhkmail::mailconfirm($data);

        if($oqafile->stsemtest_mail == null)
        {
            $dataupdate = [
                'stsemtest_mail' => 1,
            ];
            DB::table('nt_oqa_lab')->where('id_createlab', $oqafile->id_createlab)->update($dataupdate);

        }
        else if($oqafile->stsemtest_mail == 1)
        {   if($request->input('chkid') == 2)
            {
                $dataupdate = [
                    'stsemtest_mail' => 2,
                ];
                DB::table('nt_oqa_lab')->where('id_createlab', $oqafile->id_createlab)->update($dataupdate);
            }
        }
        $oqafilemail =  DB::table('nt_oqa_lab')->where('id_createlab',$request->input('id'))->first();

        if($chkid == 1 && $semtest > 0)
        {
            $data = [
                'testter'=>"GhostTest",
                'model'=>$oqafile->name_model,
                'lot'=>$oqafile->lotnumber_lab,
                'date'=>$oqafile->semtest_update,
                'alarm'=>$semtest.' point',
                'idqla'=>$oqafile->id_createlab,
            ];
            $mail = new MailController();
            $mail->mailalarmsemtest($data);
            // nhkmail::mailalarmsemtest($data);
            $chk = 2;
        }
        else if($chkid == 2 && $semtest > 0)
        {
            $data = [
                'testter'=>"GhostTest",
                'model'=>$oqafile->name_model,
                'lot'=>$oqafile->lotnumber_lab,
                'date'=>$oqafile->semtest_update,
                'alarm'=>$semtest.' point',
            ];
            nhkmail::mailalarmstaffsemtest($data);
            $chk = 2;
        }

        return response()->json(['success' =>  $chk,'id'=> $request->input('id')]);
    }
    public function deleteoqaall(Request $request)
    {
        foreach ($request->id as $item) {
            DB::table('nt_oqa_lab')->where('id_createlab',$item)->delete();
            DB::table('nt_oqa_file')->where('id_createlab',$item)->delete();
            DB::table('nt_data_lpc')->where('id_createlab',$item)->delete();
            DB::table('nt_data_sem')->where('id_createlab',$item)->delete();
            DB::table('nt_data_ic')->where('id_createlab',$item)->delete();
            DB::table('nt_data_gcms')->where('id_createlab',$item)->delete();
            DB::table('nt_data_ftir')->where('id_createlab',$item)->delete();
            DB::table('nt_datacf_lpc')->where('id_createlab',$item)->delete();
            DB::table('nt_datacf_sem')->where('id_createlab',$item)->delete();
            DB::table('nt_datacf_ic')->where('id_createlab',$item)->delete();
            DB::table('nt_datacf_gcms')->where('id_createlab',$item)->delete();
            DB::table('nt_datacf_ftir')->where('id_createlab',$item)->delete();
        }
        return response()->json(['success' => 1]);
    }

    public function deleteoqaallwd(Request $request)
    {

        foreach ($request->id as $item) {
            DB::table('nt_oqa_lab')->where('id_createlab',$item)->delete();
            DB::table('nt_oqa_file')->where('id_createlab',$item)->delete();
            DB::table('nt_data_lpc')->where('id_createlab',$item)->delete();
            DB::table('nt_data_sem')->where('id_createlab',$item)->delete();
            DB::table('nt_data_ic')->where('id_createlab',$item)->delete();
            DB::table('nt_data_gcms')->where('id_createlab',$item)->delete();
            DB::table('nt_data_ftir')->where('id_createlab',$item)->delete();
            DB::table('nt_datacf_lpc')->where('id_createlab',$item)->delete();
            DB::table('nt_datacf_sem')->where('id_createlab',$item)->delete();
            DB::table('nt_datacf_ic')->where('id_createlab',$item)->delete();
            DB::table('nt_datacf_gcms')->where('id_createlab',$item)->delete();
            DB::table('nt_datacf_ftir')->where('id_createlab',$item)->delete();
        }
        return response()->json(['success' => 1]);
    }

    public function deleteoqaSubmit(Request $request)
    {
        DB::table('nt_oqa_lab')->where('id_createlab',$request->input('id'))->delete();
        DB::table('nt_oqa_file')->where('id_createlab',$request->input('id'))->delete();
        DB::table('nt_data_lpc')->where('id_createlab',$request->input('id'))->delete();
        DB::table('nt_data_sem')->where('id_createlab',$request->input('id'))->delete();
        DB::table('nt_data_ic')->where('id_createlab',$request->input('id'))->delete();
        DB::table('nt_data_gcms')->where('id_createlab',$request->input('id'))->delete();
        DB::table('nt_data_ftir')->where('id_createlab',$request->input('id'))->delete();

        DB::table('nt_datacf_lpc')->where('id_createlab',$request->input('id'))->delete();
        DB::table('nt_datacf_sem')->where('id_createlab',$request->input('id'))->delete();
        DB::table('nt_datacf_ic')->where('id_createlab',$request->input('id'))->delete();
        DB::table('nt_datacf_gcms')->where('id_createlab',$request->input('id'))->delete();
        DB::table('nt_datacf_ftir')->where('id_createlab',$request->input('id'))->delete();
        return response()->json(['success' => 1]);
    }


    public function sample_nhkviewlpc($mailidcreatelab,$mailidmodel)
    {
        $oqa = DB::table('nt_oqa_lab')
            ->where('id_model',$mailidmodel)
            ->get();
            $oqalot = DB::table('nt_oqa_lab')
            ->where('id_createlab',$mailidcreatelab)
            ->first();
            $oqadate = DB::table('nt_data_lpc')
            ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_lpc.id_createlab')
            ->where('nt_data_lpc.id_createlab',$mailidcreatelab)->where('nt_data_lpc.lpc_st',1)
            ->first();

            $modelname = DB::table('nt_model')
            ->where('id_model',$mailidmodel)
            ->first();

            $modelparameter = DB::table('nt_model_parameter')
            ->where('id_model',$mailidmodel)
            ->where('id_model_p',1)
            ->first();

            $oqasum = DB::table('nt_model')
            ->where('id_model',$mailidmodel)
            ->first();

            $model = DB::table('nt_model_spec')
            ->join('nt_model_spec_type','nt_model_spec_type.id_model_type','=','nt_model_spec.id_model_type')
            ->where('nt_model_spec.id_model',$mailidmodel)
            ->get();
            $datalabdone = DB::table('nt_oqa_lab')
            ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
            ->where('nt_oqa_lab.id_createlab',$mailidcreatelab)->first();
            $sp = DB::table('nt_model_spec')
            ->join('nt_model_spec_type','nt_model_spec_type.id_model_type','=','nt_model_spec.id_model_type')
            ->where('nt_model_spec.id_model',$mailidmodel)
            ->where('nt_model_spec.id_model_type',1)->first();

            $tl = DB::table('nt_model_spec')
            ->join('nt_model_spec_type','nt_model_spec_type.id_model_type','=','nt_model_spec.id_model_type')
            ->where('nt_model_spec.id_model',$mailidmodel)
            ->where('nt_model_spec.id_model_type',2)->first();

            $ucl = DB::table('nt_model_spec')
            ->join('nt_model_spec_type','nt_model_spec_type.id_model_type','=','nt_model_spec.id_model_type')
            ->where('nt_model_spec.id_model',$mailidmodel)
            ->where('nt_model_spec.id_model_type',3)->first();


            $lcl = DB::table('nt_model_spec')
            ->join('nt_model_spec_type','nt_model_spec_type.id_model_type','=','nt_model_spec.id_model_type')
            ->where('nt_model_spec.id_model',$mailidmodel)
            ->where('nt_model_spec.id_model_type',4)->first();

            $cl = DB::table('nt_model_spec')
            ->join('nt_model_spec_type','nt_model_spec_type.id_model_type','=','nt_model_spec.id_model_type')
            ->where('nt_model_spec.id_model',$mailidmodel)
            ->where('nt_model_spec.id_model_type',5)->first();

            $is = DB::table('nt_model_spec')
            ->join('nt_model_spec_type','nt_model_spec_type.id_model_type','=','nt_model_spec.id_model_type')
            ->where('nt_model_spec.id_model',$mailidmodel)
            ->where('nt_model_spec.id_model_type',6)->first();

            $new_slopelpc = 0;
            $new_dfflpc = 0;
            if(!empty($oqadate))
            {
        //-------------------------------------lpc----------------------------------------//
       //------------------------------- slope dff. lpc ----------------------------------//


                $getslopdiff = $this->slopedifflpclpc($mailidcreatelab);
                $new_slopelpc = $getslopdiff['SLOPE'];
                $new_dfflpc = $getslopdiff['DIFF'];

                $yeardown = date('Y-m-d 00:00:00', strtotime($oqadate->save_date. ' - 1 year'));
                $dateend = $oqadate->save_date;
                $lpcdff = DB::table('nt_v_data_lpc')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_lpc.id_createlab')
                ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                ->where('nt_oqa_lab.save_date','<',$dateend)
                ->where('nt_oqa_lab.save_date','>=',$yeardown)
                ->where('nt_v_data_lpc.lpc_data','!=',null)
                ->where('nt_oqa_lab.id_model',$mailidmodel);

            if($lpcdff->count() < 10 && $lpcdff->count() != 0)
            {
                $lpcdfflimit = DB::table('nt_v_data_lpc')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_lpc.id_createlab')
                ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                //->where('nt_oqa_lab.save_date','<',$dateend)
                ->where('nt_v_data_lpc.lpc_data','!=',null)
                ->where('nt_oqa_lab.id_model',$mailidmodel)->orderby('nt_oqa_lab.save_date','desc')->limit(10)->get();
                $countdfflimit = $lpcdfflimit->count() -1 ;

                $lpcdff = DB::table('nt_v_data_lpc')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_lpc.id_createlab')
                ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                ->where('nt_oqa_lab.save_date','<',$dateend)
                ->where('nt_oqa_lab.save_date','>=',$lpcdfflimit[$countdfflimit]->save_date)
                ->where('nt_v_data_lpc.lpc_data','!=',null)
                ->where('nt_oqa_lab.id_model',$mailidmodel)->limit(10);
            }
            $avgsdff = $lpcdff->avg('lpc_data');
            $std_dff = $lpcdff->select(DB::raw('STDDEV_SAMP(nt_v_data_lpc.lpc_data) as std'))->first();
                if($std_dff->std == 0)
                {
                $dffck = null;
                $dfflpc = null;
                }
                else
                {
                    $dffck = ($oqadate->lpc_data - $avgsdff) / $std_dff->std;
                    $dfflpc = number_format($dffck,3);
                }
            $lpcslope = DB::table('nt_v_data_lpc')
            ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_lpc.id_createlab')
            ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
            ->where('nt_oqa_lab.save_date','<=',$dateend)
            ->where('nt_oqa_lab.save_date','>=',$yeardown)
            ->where('nt_oqa_lab.lpc_confirm_st',1)
            ->where('nt_v_data_lpc.lpc_data','!=',null)
            ->where('nt_oqa_lab.id_model',$mailidmodel);



            $lpcslopedff7 = DB::table('nt_v_data_lpc')
            ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_lpc.id_createlab')
            ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
            ->where('nt_oqa_lab.save_date','<=',$dateend)
            ->where('nt_oqa_lab.save_date','>=',$yeardown)
            ->where('nt_oqa_lab.lpc_confirm_st',1)
            ->where('nt_v_data_lpc.lpc_data','!=',null)
            ->where('nt_oqa_lab.id_model',$mailidmodel)->OrderBy('nt_oqa_lab.save_date','desc')->limit(7)->get();
            if($lpcslope->count() < 11 && $lpcslope->count() !=0)
            {

                $lpcslopelimit = DB::table('nt_v_data_lpc')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_lpc.id_createlab')
                ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                ->where('nt_oqa_lab.save_date','<=',$dateend)
                ->where('nt_oqa_lab.lpc_confirm_st',1)
                ->where('nt_v_data_lpc.lpc_data','!=',null)
                ->where('nt_oqa_lab.id_model',$mailidmodel)->orderby('nt_oqa_lab.save_date','desc')->limit(11)->get(); ;
                $countslopelimit = $lpcslopelimit->count() -1 ;
                $lpcslope = DB::table('nt_v_data_lpc')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_lpc.id_createlab')
                ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                ->where('nt_oqa_lab.save_date','<=',$dateend)
                ->where('nt_oqa_lab.save_date','>=',$lpcslopelimit[$countslopelimit]->save_date)
                ->where('nt_oqa_lab.lpc_confirm_st',1)
                ->where('nt_v_data_lpc.lpc_data','!=',null)
                ->where('nt_oqa_lab.id_model',$mailidmodel);
                $countslopelimit1 = $lpcslopelimit->count();
                if($countslopelimit1 <7)
                {
                    $numx =  $countslopelimit ;
                }
                else
                {
                    $numx = 6;
                }
                $lpcslopedff7 = DB::table('nt_v_data_lpc')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_lpc.id_createlab')
                ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                ->where('nt_oqa_lab.save_date','<=',$dateend)
                ->where('nt_oqa_lab.save_date','>=',$lpcslopelimit[$numx]->save_date)
                ->where('nt_oqa_lab.lpc_confirm_st',1)
                ->where('nt_v_data_lpc.lpc_data','!=',null)
                ->where('nt_oqa_lab.id_model',$mailidmodel)->OrderBy('nt_oqa_lab.save_date','desc')->limit(7)->get();
            }


            $avgslope = $lpcslope->avg('lpc_data');
            $countlpc = $lpcslope->count('lpc_data');

            $std_s = $lpcslope->select(DB::raw('STDDEV_SAMP(nt_v_data_lpc.lpc_data) as std'))->first();

            $stdlpc = number_format($std_s->std,3);
            $slopepush=array();
            foreach($lpcslopedff7 as $slope)
            {
                if($std_dff->std == 0)
                {
                $slopest = ($slope->lpc_data - $avgslope)/1;
                }
                else
                {
                    $slopest = ($slope->lpc_data - $avgslope)/$std_s->std;
                }
                array_push($slopepush,$slopest);
            }
            $slopelpc =0;
            if(count($lpcslopedff7) < 7)
            {
                $slopelpc = null;
            }
            else
            {
                $linearrelpc = slope::linearreg_slope($slopepush,sizeof($slopepush));
                $slopelpc = number_format($linearrelpc,3);
            }

                //------------------------------- end slope dff. lpc----------------------------------//
                    $datalab = DB::table('nt_data_lpc')
                    ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_lpc.id_createlab')
                    ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                    ->where('nt_oqa_lab.save_date','<=',$dateend)
                    ->where('nt_oqa_lab.id_model',$mailidmodel)
                    ->where('nt_oqa_lab.lpc_confirm_st',1)
                    ->where('nt_data_lpc.lpc_data','!=',null)
                    ->limit(9)->orderBy('nt_oqa_lab.save_date', 'desc')->get();

                    $oqasum_alpc_sum = intval($oqasum->alpc_sum);
                    if($oqasum_alpc_sum == 0){
                        $oqasum_alpc_sum = 1;
                    }

                    $datalaball = DB::table('nt_data_lpc')
                    ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_lpc.id_createlab')
                    ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                    ->where('nt_oqa_lab.save_date','<=',$dateend)
                    ->where('nt_oqa_lab.id_model',$mailidmodel)
                    ->where('nt_oqa_lab.lpc_confirm_st',1)
                    ->where('nt_data_lpc.lpc_data','!=',null)->limit($oqasum_alpc_sum)->orderBy('nt_oqa_lab.save_date', 'desc')->get();

                    $newlpcdatalab=array();
                    foreach ($datalaball as $item){
                        $stdate = date('Y-m-d', strtotime($item->save_date));
                        $data = DB::table('nt_model_history')
                        ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model_history.id_model', $item->id_model)
                        ->where('nt_model_history.date_start','<=',$stdate)
                        ->where('nt_model_history.date_end','>=',$stdate)
                        ->get();

                        if($data->count() == 0)
                        {

                            $data1 = DB::table('nt_model_history')
                            ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                            ->where('nt_model_history.id_model', $item->id_model)
                            ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                            ->first();

                            if(!empty($data1)){

                                $data = DB::table('nt_model_history')
                                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                                ->where('nt_model_history.id_model', $item->id_model)
                                ->where('nt_model_history.date_start','<=',$data1->date_start)
                                ->where('nt_model_history.date_end','>=',$data1->date_start)
                                ->get();

                            }
                        }
                        array_push($newlpcdatalab,$data);
                    }
                    $datalabvs3 = DB::table('nt_data_lpc')
                    ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_lpc.id_createlab')
                    ->where('nt_oqa_lab.save_date','<=',$dateend)
                    ->where('nt_oqa_lab.save_date','>=',$yeardown)
                    ->where('nt_oqa_lab.id_model',$mailidmodel)
                    ->where('nt_oqa_lab.lpc_confirm_st',1)
                    ->where('nt_data_lpc.lpc_data','!=',null)
                    ->limit(7)->orderBy('nt_oqa_lab.save_date', 'desc')->get();
                    $higher = 0;
                    $below = 0;

                    $countdatalab = count($datalab);
                    $countsortdatalab = count($datalabvs3);

                    if($countdatalab >= 9){

                        foreach ( $datalab as $item){
                            $stdate = date('Y-m-d', strtotime($item->save_date));
                            $datafirst = DB::table('nt_model_history')
                            ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                            ->where('nt_model_history.id_model', $item->id_model)
                            ->where('nt_model_history.date_start','<=',$stdate)
                            ->where('nt_model_history.date_end','>=',$stdate)
                            ->where('nt_model_history.id_model_type','=',5)
                            ->first();
                            if($datafirst ==  null)
                            {
                                $data1 = DB::table('nt_model_history')
                                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                                ->where('nt_model_history.id_model', $item->id_model)
                                ->where('nt_model_history.date_start','>',$stdate)
                                ->where('nt_model_history.id_model_type','=',5)
                                ->orderby('nt_model_history.date_start','asc')
                                ->first();

                                $datafirst = DB::table('nt_model_history')
                                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                                ->where('nt_model_history.id_model', $item->id_model)
                                ->where('nt_model_history.date_start','<=',$data1->date_start)
                                ->where('nt_model_history.date_end','>=',$data1->date_start)
                                ->where('nt_model_history.id_model_type','=',5)
                                ->first();
                            }
                            if($datafirst->alpc == "N/A" )
                            {
                                $natbd = 0;
                            }
                            else if($datafirst->alpc == "TBD")
                            {
                                $natbd = 0;
                            }
                            else
                            {
                                $natbd = 1;
                            }
                            if($natbd == 1)
                            {
                                if ($datafirst->alpc < $item->lpc_data) {
                                    $higher++;
                                }
                                else if ($datafirst->alpc > $item->lpc_data) {
                                    $below++;
                                }
                                else {
                                    $higher--;
                                    $below--;
                                }
                            }
                        }
                    }
                    $highervs3 = 0;
                    $belowvs3 = 0;
                    $iss = 0;
                    if($countsortdatalab >= 7){
                        $datalablpc = $datalabvs3[0]->lpc_data;
                    $a=array();

                    foreach ( $datalabvs3 as $item){
                        $iss++;
                        if( $iss <= 6)
                        {
                        if ($datalablpc < $datalabvs3[$iss]->lpc_data) {
                            $highervs3++;
                            $datalablpc = $datalabvs3[$iss]->lpc_data;
                        }
                        else if ($datalablpc >  $datalabvs3[$iss]->lpc_data) {
                            $belowvs3++;
                            $datalablpc = $datalabvs3[$iss]->lpc_data;
                        }
                        else {
                            $highervs3--;
                            $belowvs3--;
                        }
                        }
                    }
                }
                $chklpcshow = 1;

                if($model[2]->alpc =="N/A")
                {
                    $chk1 = 0;
                }
                else if( $model[2]->alpc == "TBD")
                {
                    $chk1 = 0;
                }
                else
                {
                    $chk1 = 1;
                }
                if($model[3]->alpc =="N/A")
                {
                    $chk2 = 0;
                }
                else if( $model[3]->alpc == "TBD")
                {
                    $chk2 = 0;
                }
                else
                {
                    $chk2 = 1;
                }
                if($model[5]->alpc =="N/A")
                {
                    $chk3 = 0;
                }
                else if( $model[5]->alpc == "TBD")
                {
                    $chk3 = 0;
                }
                else
                {
                    $chk3 = 1;
                }

                $lpcerror = 0;

                if ($higher >= 9) {
                    $lpcerror++;

                } else if ($below >= 9) {
                    $lpcerror++;
                } else {

                }

                if ($highervs3 >= 6) {
                    $lpcerror++;
                } else if ($belowvs3 >= 6) {
                    $lpcerror++;
                } else {
                }

                if(isset($newlpcdatalab[0])){

                    $datalclalpc =  $newlpcdatalab[0][2]->alpc;
                    $datauclalpc =  $newlpcdatalab[0][1]->alpc;


                    if ($datalab[0]->lpc_data < $datalclalpc) {

                        if($chk3 ==1)
                        {
                            $lpcerror++;
                        }
                    }
                    else if ($datalab[0]->lpc_data > $datauclalpc ) {

                        if($chk1 ==1)
                        {
                        $lpcerror++;
                        }
                    }
                    else{
                    }
                }

                if ($datalab[0]->lpc_data > $is->alpc) {

                    if($chk3 ==1)
                    {
                    $lpcerror++;
                    }
                } else {
                }
                if($slopelpc != null)
                {
                    // if( $slopelpc >= 0.5 || $slopelpc <= -0.5 || $dfflpc >= 3.5) {
                    if( $new_slopelpc >= 0.5 || $new_slopelpc <= -0.5 || $new_dfflpc >= 3.5) {
                        $lpcerror++;
                    }
                }
                else
                {
                    if ($dfflpc >= 3.5 ) {
                        $lpcerror++;
                    }
                }
                $returndata = $lpcerror;
        }
        else
        {
            $returndata = 0;
        }
        return  $returndata;

    }

    public static function slopedifflpclpc($idcreatelab)
    {

          $oqalot = DB::table('nt_oqa_lab')
          ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
          ->where('nt_oqa_lab.id_createlab',$idcreatelab)
          ->first();

          $oqadate = DB::table('nt_data_lpc')
          ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_lpc.id_createlab')
          ->where('nt_data_lpc.id_createlab',$idcreatelab)->where('nt_data_lpc.lpc_st',1)
          ->first();


          if($oqalot !== null){

              $id_model = $oqalot->id_model;

              $datestart = date("Y-m-d", strtotime("2022-12-01"));
              $a1 = DB::table('nt_model')->where('id_model', $id_model)->first();
              $text1 = $a1->name_model;
              $chktext1 = mb_substr($text1,0,-3);

              $file = storage_path('NHK-File/template/report/slopediff_lpc_template.xlsx');
              $filetime = date('YmdHis');

              $spacename = str_replace(' ', '', $chktext1);
              $namefile = 'slopediff_lpc_'.$text1;

              $checkfile =  storage_path('NHK-File/report/calculation/'.$namefile.'.xlsx'  );
              if(file_exists($checkfile)){
                  @unlink($checkfile);
              }

              if($oqadate !== null){
                  $yeardown = date('Y-m-d 00:00:00', strtotime($oqadate->save_date. ' - 1 year'));
                  $dateend = $oqadate->save_date;
              }else{
                  $yeardown = date('Y-m-d 00:00:00', strtotime($a1->save_date. ' - 1 year'));
                  $dateend = $a1->save_date;
              }


              $semdatalab = DB::table('nt_data_lpc')
              ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_lpc.id_createlab')
              ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
              ->where('nt_oqa_lab.save_date','<=',$dateend)
              ->where('nt_oqa_lab.id_model',$id_model)
              ->where('nt_oqa_lab.sem_confirm_st',1)
              ->orderBy('nt_oqa_lab.save_date', 'desc')->get();

              $semdatalaball = DB::table('nt_data_lpc')
              ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_lpc.id_createlab')
              ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
              ->where('nt_oqa_lab.save_date','<=',$dateend)
              ->where('nt_oqa_lab.id_model',$id_model)
              ->where('nt_oqa_lab.lpc_confirm_st',1);


              $semdatalaball =  $semdatalaball->where('nt_oqa_lab.save_date','>=',$yeardown)->orderBy('nt_oqa_lab.save_date', 'desc')->get();

              if(count($semdatalaball) >= 7){
                  Excel::load($file, function ($doc)use( $semdatalaball) {
                      $i = 2;
                      $sum_f_agv = 0;
                      $count_f_agv = 0;
                      $agv = 0;
                      $sheet = $doc->setActiveSheetIndex(0);
                      foreach ($semdatalaball as $key => $value) {

                          $sheet->setCellValue('B'.$i, date('d-m-y',strtotime($value->save_date)));
                          $sheet->setCellValue('C'.$i, $value->lotnumber_lab);

                          $sheet->setCellValue('D'.$i, number_format($value->lpc_data, 0, '.', '') );

                          $i++;
                      } ;

                  })->setFilename($namefile)->store('xlsx', storage_path('NHK-File/report/calculation'));

                  $excel = [];
                  $readfile =  storage_path('NHK-File/report/calculation/'.$namefile.'.xlsx'  );
                  $spreadSheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($readfile);

                  $n10 = $spreadSheet->getActiveSheet()->getCell('N10')->getCalculatedValue();
                  $n22 = $spreadSheet->getActiveSheet()->getCell('N22')->getCalculatedValue();

                  $excel = [
                      'SLOPE' => number_format($n10, 3, '.', ''),
                      'DIFF' => number_format($n22, 3, '.', '')
                  ];
              }else{
                  $excel = [
                      'SLOPE' => "",
                      'DIFF' => ""
                  ];
              }
          }else{
              $excel = [
                  'SLOPE' => "",
                  'DIFF' => ""
              ];
          }

          return $excel;
    }


    //-------- Begin WD --------

     public function datatables_wd(Request $request)
    {
        $typelab = 'WD_';
        // dd($request->textmodel.'<>'.$request->textlot);
       if(empty($request->date_start) && empty($request->date_end) && empty($request->textmodel) && empty($request->textlot))
       {
            $now = date("Y-m-d 00:00:00");
            $start = date('Y-m-d H:i:s', strtotime($now));
            $end =date('Y-m-d H:i:s', strtotime('+1 day', strtotime($now)));
            $datalist = DB::table('nt_oqa_lab')
            ->join('nt_model', 'nt_model.id_model', '=', 'nt_oqa_lab.id_model')
            ->join('nt_oqa_file', 'nt_oqa_file.id_createlab', '=', 'nt_oqa_lab.id_createlab')
            ->where('nt_oqa_lab.save_date','>=',$start)->where('nt_oqa_lab.save_date','<=',$end)
            ->where('nt_oqa_lab.type_lab','like',$typelab.'%')
            ->select('nt_model.*','nt_oqa_lab.id_createlab','nt_oqa_lab.type_lab','nt_oqa_lab.name_lab',
            'nt_oqa_lab.tga',
            'nt_oqa_lab.code_lab',
            'nt_oqa_lab.lotnumber_lab',
            'nt_oqa_lab.lpc_confirm_st',
            'nt_oqa_lab.sem_confirm_st',
            'nt_oqa_lab.ic_confirm_st',
            'nt_oqa_lab.gcms_confirm_st',
            'nt_oqa_lab.ftir_confirm_st',
            'nt_oqa_lab.semtest_confirm_st',
            'nt_oqa_file.st_lpc',
            'nt_oqa_file.st_sem',
            'nt_oqa_file.st_ic',
            'nt_oqa_file.st_gcms',
            'nt_oqa_file.st_ftir',
            'nt_oqa_file.st_semtest',
            'nt_oqa_lab.save_date as starttime');
       }
       else
       {
            $start = date('Y-m-d 00:00:00', strtotime($request->date_start));
            $end = date('Y-m-d 23:59:00', strtotime($request->date_end. "+1 days"));

            $datalist = DB::table('nt_oqa_lab')
            ->join('nt_model', 'nt_model.id_model', '=', 'nt_oqa_lab.id_model')
            ->join('nt_oqa_file', 'nt_oqa_file.id_createlab', '=', 'nt_oqa_lab.id_createlab')
            ->where('nt_oqa_lab.save_date','>=',$start)->where('nt_oqa_lab.save_date','<=',$end)
            ->where('nt_oqa_lab.type_lab','like',$typelab.'%');
            if(!empty($request->date_start) && !empty($request->date_end))
            {
                $datalist->where('nt_oqa_lab.save_date','>=',$start)->where('nt_oqa_lab.save_date','<=',$end);
            }
            if(!empty($request->textmodel))
            {
                $datalist->where('nt_model.name_model','like','%'.$request->textmodel.'%');
            }
            if(!empty($request->textlot))
            {
                $datalist->where('nt_oqa_lab.lotnumber_lab','like','%'.$request->textlot.'%');
            }
            $datalist->select('nt_model.*','nt_oqa_lab.id_createlab','nt_oqa_lab.type_lab','nt_oqa_lab.name_lab',
            'nt_oqa_lab.tga',
            'nt_oqa_lab.code_lab',
            'nt_oqa_lab.lotnumber_lab',
            'nt_oqa_lab.lpc_confirm_st',
            'nt_oqa_lab.sem_confirm_st',
            'nt_oqa_lab.ic_confirm_st',
            'nt_oqa_lab.gcms_confirm_st',
            'nt_oqa_lab.ftir_confirm_st',
            'nt_oqa_lab.semtest_confirm_st',
            'nt_oqa_file.st_lpc',
            'nt_oqa_file.st_sem',
            'nt_oqa_file.st_ic',
            'nt_oqa_file.st_gcms',
            'nt_oqa_file.st_ftir',
            'nt_oqa_file.st_semtest',
            'nt_oqa_lab.save_date as starttime');

            // $datalist = DB::table('nt_oqa_lab')
            // ->join('nt_model', 'nt_model.id_model', '=', 'nt_oqa_lab.id_model');
            // // ->join('nt_oqa_file', 'nt_oqa_file.id_createlab', '=', 'nt_oqa_lab.id_createlab');
            // if(!empty($request->date_start) && !empty($request->date_end))
            // {
            //     $datalist->where('nt_oqa_lab.save_date','>=',$start)->where('nt_oqa_lab.save_date','<=',$end);
            // }
            // if(!empty($request->textmodel))
            // {
            //     $datalist->where('nt_model.name_model','like','%'.$request->textmodel.'%');
            // }
            // if(!empty($request->textlot))
            // {
            //     $datalist->where('nt_oqa_lab.lotnumber_lab','like','%'.$request->textlot.'%');
            // }
            // $datalist->where('nt_oqa_lab.type_lab','like',$typelab.'%');
            // $datalist->select('nt_model.*','nt_oqa_lab.id_createlab','nt_oqa_lab.type_lab','nt_oqa_lab.name_lab',
            // 'nt_oqa_lab.code_lab',
            // 'nt_oqa_lab.tga',
            // 'nt_oqa_lab.lotnumber_lab',
            // 'nt_oqa_lab.lpc_confirm_st',
            // 'nt_oqa_lab.sem_confirm_st',
            // 'nt_oqa_lab.ic_confirm_st',
            // 'nt_oqa_lab.gcms_confirm_st',
            // 'nt_oqa_lab.ftir_confirm_st',
            // 'nt_oqa_lab.semtest_confirm_st',
            // 'nt_oqa_lab.save_date as starttime');

       }

       $sQuery  = Datatables::of($datalist)
       ->addIndexColumn()
       ->addColumn('starttimeas',function($data){
           $date = date('d-m-Y H:i:s', strtotime($data->starttime));
           return $date;
        });

       return $sQuery->escapeColumns([])->make(true);

    }

    public function oqafilewd($id)
    {
        $oqafile = DB::table('nt_oqa_file')->where('id_createlab',$id)->first();
        $oqalab = DB::table('nt_oqa_lab')
        ->join('nt_model', 'nt_model.id_model', '=', 'nt_oqa_lab.id_model')
        ->where('nt_oqa_lab.id_createlab',$id)->select('nt_oqa_lab.*','nt_model.*','nt_oqa_lab.save_date as starttime')->first();

        $btnstatus = 1;
        if(Auth::user()->status == 3){
            $btnstatus = 0;
        }
        return view('OQA/oqafileWD', ['oqafile' =>$oqafile,'oqalab' =>$oqalab,'btnstatus' => $btnstatus]);
    }

    /**
     * ตรวจสอบเมนู (Session namemenu) และประเภท model ก่อนสร้าง OQA Incoming
     * คืนค่า JSON response ถ้าไม่ผ่าน, คืน null ถ้าผ่าน
     */
    private function checkCreatelabAccess($ntmodel, $requiredMenu, $requiredPrefix)
    {
        $namemenu = Session::get('namemenu');

        if ($namemenu !== $requiredMenu) {
            return response()->json([
                'success' => 0,
                'message' => 'ไม่สามารถบันทึกได้: เมนูปัจจุบัน (' . ($namemenu ?: '-') . ') ไม่ใช่ ' . strtoupper($requiredMenu)
                    . ' กรุณาเข้าเมนู ' . strtoupper($requiredMenu) . ' ก่อนสร้างรายการ',
            ]);
        }

        if (!$ntmodel) {
            return response()->json([
                'success' => 0,
                'message' => 'ไม่พบข้อมูล Model ที่เลือก',
            ]);
        }

        if (strpos((string) $ntmodel->model_type, $requiredPrefix) !== 0) {
            return response()->json([
                'success' => 0,
                'message' => 'ไม่สามารถบันทึกได้: Model ' . $ntmodel->name_model . ' เป็นประเภท ' . $ntmodel->model_type
                    . ' (เมนู ' . strtoupper($requiredMenu) . ' ใช้ได้เฉพาะ ' . $requiredPrefix . '* เท่านั้น)',
            ]);
        }

        return null;
    }

    public function createlabWDSubmit(Request $request)
    {

        ini_set('memory_limit', '-1');
        set_time_limit(5000);

        $idmodel = $request->input('chkmodel');
        $chkaddid = $request->input('chkaddid');
        $modelparameter = DB::table('nt_model_parameter')->where('id_model',$idmodel)->where('id_model_p',1)->first();
        $lotcount = DB::table('nt_oqa_lab')->where('lotnumber_lab',$request->input('txt_lotnumber'))->count();
        $lotfirst = DB::table('nt_oqa_lab')->where('lotnumber_lab',$request->input('txt_lotnumber'))->first();

        $ntmodel = DB::table('nt_model')->where('id_model',$idmodel)->first();

        // createlabWDSubmit ใช้ได้เฉพาะเมนู oqa2 และ model_type ขึ้นต้นด้วย WD_ เท่านั้น
        $denied = $this->checkCreatelabAccess($ntmodel, 'oqa2', 'WD_');
        if ($denied) {
            return $denied;
        }

        $tgaDate = null;
        if ($request->filled('txt_tga')) {
            $tgaInput = $request->input('txt_tga');

            try {
                $tgaDate = Carbon::createFromFormat('Y-m-d', $tgaInput)->format('Y-m-d');
            } catch (\InvalidArgumentException $e) {
                try {
                    $tgaDate = Carbon::createFromFormat('d/m/Y', $tgaInput)->format('Y-m-d');
                } catch (\InvalidArgumentException $ex) {
                    try {
                        $tgaDate = Carbon::createFromFormat('m/d/Y', $tgaInput)->format('Y-m-d');
                    } catch (\InvalidArgumentException $exc) {
                        return response()->json([
                            'success' => 0,
                            'message' => 'รูปแบบวันที่ TGA ไม่ถูกต้อง (รองรับ Y-m-d, d/m/Y, m/d/Y)'
                        ], 400);
                    }
                }
            }
        }

        if($chkaddid == 1)
        {
           $savedate = date('Y-m-d H:i:s');
           $datechk = date('Y-m-d');
        }
        else
        {
            $savedate = $request->input('specialdate');
            $datechk = date('Y-m-d');
        }
        $createlab = [
            'id_model' => $idmodel,
            'type_lab' => $request->input('chktype'),
            'name_lab' => $request->input('txt_name'),
            'code_lab' => $request->input('txt_code'),
            'lotnumber_lab' => $request->input('txt_lotnumber'),
            'save_date' => $savedate,
            'date_chk' =>  $datechk,
            'add_lpc_day' => 0,
            'lpc_confirm_st' => 0,
            'sem_confirm_st' => 0,
            'ic_confirm_st' => 0,
            'gcms_confirm_st' => 0,
            'ftir_confirm_st' => 0,
            'semtest_confirm_st' => 0,
            'lpcstatus_done' => 0,
            'semstatus_done' => 0,
            'icstatus_done' => 0,
            'gcmsstatus_done' => 0,
            'ftirstatus_done' => 0,
            'semteststatus_done' => 0,
            'tga' => $tgaDate,
        ];
        if($lotcount == 0)
        {
            $getidcreatelab = DB::table('nt_oqa_lab')->insertGetId($createlab, 'id_createlab');
        }
        else
        {
            $getidcreatelab = $lotfirst->id_createlab;
        }

        $starttime = date('YmdHis',strtotime($savedate));
        $model = $ntmodel->name_model;
        $part = $ntmodel->part;

        $lot = $request->input('txt_lotnumber');
        if( $request->input('ck_lpc') == 1 )
        {

            $lpcname =  $starttime.'_LPC_'.$model.'_'.$part.'_'.$lot;
            $lpcfilename = $lpcname.'.xlsm';
            $st_lpc = 1;
            $date_lpc =   $savedate;
            $lpc_startime =   $savedate;

            // $file = storage_path('NHK-File/model-rowfile WD/'.$ntmodel->file_folder.'/'.$modelparameter->lpc_file);
            // Excel::load($file, function ($doc)use($createlab,$starttime) {
            //     $sheet = $doc->setActiveSheetIndex(0);
            //     $sheet->setCellValue('C6',$createlab['lotnumber_lab']);
            //     $sheet->setCellValue('L3',$starttime);
            // })->setFilename($lpcname)->store('xlsm', storage_path('app/public/OQA/LPC/'));

            $file = storage_path('NHK-File/model-rowfile WD/'.$ntmodel->file_folder.'/'.$modelparameter->lpc_file);
            $spreadsheet = IOFactory::load($file);
            $sheet = $spreadsheet->setActiveSheetIndex(0);
            $sheet->setCellValue('C6', $createlab['lotnumber_lab']);
            $sheet->setCellValue('L3', $starttime);
            $exportDirectory = storage_path('app/public/OQA/LPC/');

            if (!file_exists($exportDirectory)) {
                mkdir($exportDirectory, 0755, true);
            }

            $outputFile = $exportDirectory . '/' . $lpcname . '.xlsm';

            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->setPreCalculateFormulas(false);
            $writer->save($outputFile);


            $datalpc = [
                'id_createlab' => $getidcreatelab,
            ];
            DB::table('nt_data_lpc')->insert($datalpc);
            DB::table('nt_datacf_lpc')->insert($datalpc);

            if($lotcount > 0)
            {
                DB::table('nt_oqa_file')->where('id_createlab', $getidcreatelab)->update(['st_lpc' => $st_lpc, 'file_lpc' => $lpcfilename,'date_lpc' => $date_lpc,'lpc_startime'=> $lpc_startime]);
            }

        }else{

            if($lotcount == 0)
            {
                $st_lpc = 0;
                $lpcfilename = null;
                $date_lpc =  null;
                $lpc_startime = null;
            }
        }

        if( $request->input('ck_sem')  == 1 )
        {
            $semname =  $starttime.'_SEM_'.$model.'_'.$part.'_'.$lot;
            $semfilename = $semname.'.xlsx';
            $st_sem = 1;
            $date_sem =   $savedate;
            $sem_startime =   $savedate;
            $file = storage_path('NHK-File/model-rowfile WD/'.$ntmodel->file_folder.'/'.$modelparameter->sem_file);
            Excel::load($file, function ($doc)use($createlab,$starttime,$getidcreatelab) {
                $sheet = $doc->setActiveSheetIndex(0);

                $sheet->getCell('C6')->setValue($createlab['lotnumber_lab']);
                $sheet->getCell('H10')->setValue($starttime);

            })->setFilename($semname)->store('xlsx', storage_path('app/public/OQA/SEM/'));

            $datasem = [
                'id_createlab' => $getidcreatelab,
            ];
            DB::table('nt_data_sem')->insert($datasem);
            DB::table('nt_datacf_sem')->insert($datasem);
            if($lotcount > 0)
            {
                DB::table('nt_oqa_file')->where('id_createlab', $lotfirst->id_createlab)->update(['st_sem' => $st_sem, 'file_sem' => $semfilename,'date_sem' => $date_sem,'sem_startime'=>$sem_startime]);
            }
        }
        else
        {
            $st_sem = 0;
            $semfilename = null;
            $date_sem =  null;
            $sem_startime = null;
        }

        if( $request->input('ck_ic')  == 1 )
        {
            $icname =  $starttime.'_IC_'.$model.'_'.$part.'_'.$lot;
            $icfilename = $icname.'.xlsx';
            $st_ic = 1;
            $date_ic =   $savedate;
            $ic_startime = $savedate;
            $file = storage_path('NHK-File/model-rowfile WD/'.$ntmodel->file_folder.'/'.$modelparameter->ic_file);

            Excel::load($file, function ($doc)use($createlab,$starttime,$getidcreatelab) {
                $sheet = $doc->setActiveSheetIndex(0);
                $sheet->getCell('C7')->setValue($createlab['lotnumber_lab']);
                $sheet->getCell('I4')->setValue($starttime);
            })->setFilename($icname)->store('xlsx', storage_path('app/public/OQA/IC/'));

            $dataic = [
                'id_createlab' => $getidcreatelab,
            ];
            DB::table('nt_data_ic')->insert($dataic);
            DB::table('nt_datacf_ic')->insert($dataic);
            if($lotcount > 0)
            {
                DB::table('nt_oqa_file')->where('id_createlab', $lotfirst->id_createlab)->update(['st_ic' => $st_ic, 'file_ic' => $icfilename,'date_ic' => $date_ic,'ic_startime'=>$ic_startime]);
            }

        }else{

            $st_ic = 0;
            $icfilename = null;
            $date_ic =  null;
            $ic_startime = null;

        }

        if( $request->input('ck_ftir')  == 1 )
        {

            $ftirname =  $starttime.'_GCMSSolvent_'.$model.'_'.$part.'_'.$lot;
            $ftirfilename = $ftirname.'.xlsx';
            $st_ftir = 1;
            $date_ftir =   $savedate;
            $ftir_startime = $savedate;
            // $file = storage_path('NHK-File/model-rowfile WD/'.$ntmodel->file_folder.'/'.$modelparameter->ftir_file);

            $file = storage_path('NHK-File/template/model-formet/GCMSSolventWD-format.xlsx');
            Excel::load($file, function ($doc)use($createlab,$starttime,$getidcreatelab,$ntmodel) {
                $sheet = $doc->setActiveSheetIndex(0);
                $sheet->setCellValue('K2',$ntmodel->name_model);
                $sheet->setCellValue('K3',$ntmodel->part);
                $sheet->setCellValue('K4',$ntmodel->part_number);

                $sheet->getCell('K5')->setValue($createlab['lotnumber_lab']);
                $sheet->getCell('K6')->setValue($starttime);

            })->setFilename($ftirname)->store('xlsx', storage_path('app/public/OQA/FTIR/'));

            $dataftir = [
                'id_createlab' => $getidcreatelab,
            ];
            DB::table('nt_data_ftir')->insert($dataftir);
            DB::table('nt_datacf_ftir')->insert($dataftir);
            if($lotcount > 0)
            {
                DB::table('nt_oqa_file')->where('id_createlab', $lotfirst->id_createlab)->update(['st_ftir' => $st_ftir, 'file_ftir' => $ftirfilename,'date_ftir' => $date_ftir,'ftir_startime'=>$ftir_startime]);
            }

        }else{


            $st_ftir = 0;
            $ftirfilename = null;
            $date_ftir = null;
            $ftir_startime = null;

        }

        if( $request->input('ck_semtest')  == 1 )
        {

            $semtestname =  $starttime.'_MESA_'.$model.'_'.$part.'_'.$lot;
            $semtestfilename = $semtestname.'.xlsx';
            $st_semtest = 1;
            $date_semtest =   $savedate;
            $semtest_startime = $savedate;
            $file = storage_path('NHK-File/model-rowfile WD/'.$ntmodel->file_folder.'/'.$modelparameter->semtest_file);
            Excel::load($file, function ($doc)use($createlab,$starttime,$getidcreatelab) {
                $sheet = $doc->setActiveSheetIndex(0);
                $sheet->getCell('C5')->setValue($createlab['lotnumber_lab']);
                $sheet->getCell('G2')->setValue($starttime);

            })->setFilename($semtestname)->store('xlsx', storage_path('app/public/OQA/GhostTest/'));

            $datasemtest = [
                        'id_createlab' => $getidcreatelab,
            ];
            DB::table('nt_data_semtest')->insert($datasemtest);
            DB::table('nt_datacf_semtest')->insert($datasemtest);
            if($lotcount > 0)
            {
                DB::table('nt_oqa_file')->where('id_createlab', $lotfirst->id_createlab)->update(['st_semtest' => $st_semtest, 'file_semtest' => $semtestfilename,'date_semtest' => $date_semtest,'semtest_startime'=>$semtest_startime]);
            }

        }else{

            $st_semtest = 0;
            $semtestfilename = null;
            $date_semtest = null;
            $semtest_startime = null;

        }

        if( $request->input('ck_gcms')  == 1 )
        {

            $gcmsname =  $starttime.'_GCMSOutgas_'.$model.'_'.$part.'_'.$lot;
            $gcmsfilename = $gcmsname.'.xlsx';
            $st_gcms = 1;
            $date_gcms =   $savedate;
            $gcms_startime = $savedate;
            $file = storage_path('NHK-File/model-rowfile WD/'.$ntmodel->file_folder.'/'.$modelparameter->gcms_file);
            Excel::load($file, function ($doc)use($createlab,$starttime,$getidcreatelab) {
                $sheet = $doc->setActiveSheetIndex(0);
                $sheet->getCell('K5')->setValue($createlab['lotnumber_lab']);
                $sheet->getCell('K6')->setValue($starttime);
            })->setFilename($gcmsname)->store('xlsx', storage_path('app/public/OQA/GCMS/'));

            $datagcms = [
                'id_createlab' => $getidcreatelab,
            ];
            DB::table('nt_data_gcms')->insert($datagcms);
            DB::table('nt_datacf_gcms')->insert($datagcms);
            if($lotcount > 0)
            {
            DB::table('nt_oqa_file')->where('id_createlab', $lotfirst->id_createlab)->update(['st_gcms' => $st_gcms, 'file_gcms' => $gcmsfilename,'date_gcms' => $date_gcms,'gcms_startime'=>$gcms_startime]);
            }

        }else{

            $st_gcms = 0;
            $gcmsfilename = null;
            $date_gcms =  null;
            $gcms_startime = null;

        }

        if($lotcount == 0)
        {

            $oqafile = [
                'id_createlab' => $getidcreatelab,
                'st_lpc' => $st_lpc,
                'file_lpc' => $lpcfilename,
                'date_lpc' =>  $date_lpc,
                'lpc_startime'=> $lpc_startime,
                'st_sem' => $st_sem,
                'file_sem' => $semfilename,
                'date_sem' => $date_sem,
                'sem_startime'=> $sem_startime,
                'st_ic' => $st_ic,
                'file_ic' => $icfilename,
                'date_ic' =>$date_ic,
                'ic_startime'=> $ic_startime,
                'st_gcms' => $st_gcms,
                'file_gcms' => $gcmsfilename,
                'date_gcms' =>$date_gcms,
                'gcms_startime'=> $gcms_startime,
                'st_ftir' => $st_ftir,
                'file_ftir' => $ftirfilename,
                'date_ftir' =>$date_ftir,
                'ftir_startime'=> $ftir_startime,
                'st_semtest' => $st_semtest,
                'file_semtest' => $semtestfilename,
                'date_semtest' =>$date_semtest,
                'semtest_startime'=> $semtest_startime,
                'status'=>1,
            ];
            DB::table('nt_oqa_file')->insert($oqafile);
        }

        return response()->json(['success' =>  1,'id'=> $getidcreatelab]);

    }

    public function uploadoqafileWD($id,$labname)
    {
        $oqafile = DB::table('nt_oqa_file')->where('id_createlab',$id)->first();
        $oqalab = DB::table('nt_oqa_lab')->where('id_createlab',$id)->first();
        return view('OQA/uploadoqafileWD', ['oqafile' =>$oqafile,'oqalab' =>$oqalab,'labname'=>$labname]);
    }

    public function uplaodoqaWDSubmit(Request $request)
    {

        $id = $request->input('idfile');
        $namefile = $request->input('filename');
        $oqafile = DB::table('nt_oqa_file')->where('id_createlab',$id)->first();
        $oqalab = DB::table('nt_oqa_lab')->where('id_createlab',$id)->first();
        $datenow = date("Y/m/d H:i:s");
        if($request->input('filename') == "LPC")
        {
            if($oqafile->file_lpc !== null){
                $rename = $oqafile->file_lpc;
                $file = $request->file('customFile')->storeAs('OQA/LPC', $rename,'public');

            }else{

                $files = $request->file('customFile');
                $extension = 'xlsm';
                $model = DB::table('nt_model')->where('id_model',$oqalab->id_model)->first();
                $filetime = date('YmdHis');
                $namefile2 = $filetime.'_LPC_'.$model->name_model.'_'.$oqalab->lotnumber_lab.'.'.$extension;
                $rename = $namefile2;

                $destinationPath = storage_path('app/public/OQA/LPC');
                $files->move($destinationPath , $rename); // move files to destination folder

            }

            $updates = [
                'file_lpc' => $rename,
                'lpc_update' => $datenow,
            ];
            $updatesoqalab = [
                'lpc_confirm_st' => 0,
            ];
            DB::table('nt_oqa_lab')->where('id_createlab', $id)->update($updatesoqalab);
            DB::table('nt_oqa_file')->where('id_createlab', $id)->update($updates);
        }
        if($request->input('filename') == "SEM")
        {
           $rename = $oqafile->file_sem;
           $file = $request->file('customFile')->storeAs('OQA/SEM', $rename,'public');
           $updates = [
            'sem_update' => $datenow,
            ];
            $updatesoqalab = [
                'sem_confirm_st' => 0,
            ];
            DB::table('nt_oqa_lab')->where('id_createlab', $id)->update($updatesoqalab);
           DB::table('nt_oqa_file')->where('id_createlab', $id)->update($updates);
        }
        if($request->input('filename') == "IC")
        {
           $rename = $oqafile->file_ic;
           $file = $request->file('customFile')->storeAs('OQA/IC', $rename,'public');
           $updates = [
            'ic_update' => $datenow,
            ];
            $updatesoqalab = [
                'ic_confirm_st' => 0,
            ];
            DB::table('nt_oqa_lab')->where('id_createlab', $id)->update($updatesoqalab);
           DB::table('nt_oqa_file')->where('id_createlab', $id)->update($updates);
        }
        if($request->input('filename') == "GCMS")
        {
           $rename = $oqafile->file_gcms;
           $file = $request->file('customFile')->storeAs('OQA/GCMS', $rename,'public');
           $updates = [
                'gcms_update' => $datenow,
            ];
            $updatesoqalab = [
                'gcms_confirm_st' => 0,
            ];
            DB::table('nt_oqa_lab')->where('id_createlab', $id)->update($updatesoqalab);
           DB::table('nt_oqa_file')->where('id_createlab', $id)->update($updates);
        }
        if($request->input('filename') == "FTIR")
        {

           $rename = $oqafile->file_ftir;
           $file = $request->file('customFile')->storeAs('OQA/FTIR', $rename,'public');
           $updates = [
                'ftir_update' => $datenow,
            ];
            $updatesoqalab = [
                'ftir_confirm_st' => 0,
            ];
            DB::table('nt_oqa_lab')->where('id_createlab', $id)->update($updatesoqalab);
           DB::table('nt_oqa_file')->where('id_createlab', $id)->update($updates);
        }

        if($request->input('filename') == "GhostTest")
        {

           $rename = $oqafile->file_semtest;
           $file = $request->file('customFile')->storeAs('OQA/GhostTest', $rename,'public');
           $updates = [
            'semtest_update' => $datenow,
            ];
            $updatesoqalab = [
                'semtest_confirm_st' => 0,
            ];
            DB::table('nt_oqa_lab')->where('id_createlab', $id)->update($updatesoqalab);
           DB::table('nt_oqa_file')->where('id_createlab', $id)->update($updates);
        }

        return response()->json(['success' =>  1,'id'=>$id ,'namefile'=>$namefile]);
    }

    public function chkconfirmlpcWD(Request $request)
    {
        try {
            $id = $request->input('id');
            $chkid = $request->input('chkid');

            if (!$id) {
                return response()->json(['success' => 0, 'message' => 'ไม่พบ ID ที่ร้องขอ'], 400);
            }

            $oqafile = DB::table('nt_oqa_file')
                ->join('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_oqa_file.id_createlab')
                ->join('nt_model', 'nt_model.id_model', '=', 'nt_oqa_lab.id_model')
                ->where('nt_oqa_file.id_createlab', $id)
                ->first();

            if (!$oqafile) {
                return response()->json(['success' => 0, 'message' => 'ไม่พบข้อมูล OQA Lab สำหรับ ID นี้'], 404);
            }

            // 3. ตรวจสอบไฟล์ Excel ก่อนโหลด
            $filePath = storage_path('app/public/OQA/LPC/' . $oqafile->file_lpc);
            if (empty($oqafile->file_lpc) || !file_exists($filePath)) {
                Log::error("File not found for chkconfirmlpcWD: ID {$id}, Path: {$filePath}");
                return response()->json(['success' => 0, 'message' => 'ไม่พบไฟล์ Excel ที่ต้องการประมวลผล'], 404);
            }

            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);

            $sheet = $spreadsheet->setActiveSheetIndex(0);
            $ftimechk = $sheet->getCell('R4')->getCalculatedValue();
            $cell = $sheet->getCell('M24')->getCalculatedValue();

            $datescc = $sheet->getCell('L3')->getCalculatedValue();
            $datescc2 = date("Y/m/d H:i:s", strtotime($datescc));

            $partnumber_wd_scc = $sheet->getCell('C5')->getCalculatedValue();

            $chk = 0;
            if (!empty($cell)) {
                $ftime = date("Y/m/d H:i:s", strtotime($ftimechk));
                DB::table('nt_oqa_lab')->where('id_createlab', $id)->update([
                    'lpc_confirm_st' => 1,
                    'ftime_lpc' => $ftime,
                    'partnumber_wd_scc' =>$partnumber_wd_scc,
                ]);
                $chk = 1;
            }

            if ($oqafile->stlpc_mail === null) {
                DB::table('nt_oqa_lab')->where('id_createlab', $id)->update(['stlpc_mail' => 1]);
            } elseif ($oqafile->stlpc_mail == 1 && $chkid == 2) {
                DB::table('nt_oqa_lab')->where('id_createlab', $id)->update(['stlpc_mail' => 2]);
            }

            $data = [
                'subject' => "[CAS program]_You have a new cleanliness results!!!",
                'testter' => "LPC",
                'model' => $oqafile->name_model,
                'lot' => $oqafile->lotnumber_lab,
                'date' => $oqafile->lpc_update,
            ];

            $mail = new MailController();
            $mail->mailconfirmlpcWD($data);

            $scc = new SCCController();
            $scc->createSCCOQA($id, $datescc2);

            return response()->json(['success' => $chk, 'id' => $id, 'chkid' => $chkid]);

        } catch (\Exception $e) {

            // ส่ง Response แจ้งข้อผิดพลาดกลับไป เพื่อให้รู้ว่าเกิดอะไรขึ้น
            return response()->json([
                'success' => 0,
                'message' => 'เกิดข้อผิดพลาดในระบบ: ' . $e->getMessage()
            ], 500);
        }
    }

    public function chkconfirmsemWD(Request $request)
    {
        $id = $request->input('id');
        $oqafile =  DB::table('nt_oqa_file')
        ->join('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_oqa_file.id_createlab')
        ->join('nt_model', 'nt_model.id_model', '=', 'nt_oqa_lab.id_model')
        ->where('nt_oqa_file.id_createlab',$request->input('id'))
        ->first();

        $file = storage_path('app/public/OQA/SEM/'.$oqafile->file_sem);
        $chk = '';

        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
        $sheet = $spreadsheet->getSheetByName('Summary(Tables)');
        $cell = $sheet->getCell('C3')->getCalculatedValue();

        if($cell != "")
        {
            $updates = [
               'sem_confirm_st' => 1,
               'ftime_sem' =>$ftime,
            ];
            DB::table('nt_oqa_lab')->where('id_createlab', $id)->update($updates);

            $chk = 1;

        }else{
            $chk = 0;
        }

        //--------- Use in host Orange only -----------

        $subject = "[CAS program]_You have a new cleanliness results!!!";

        $data = [
            'subject'=>$subject,
            'testter'=>"SEM",
            'model'=>$oqafile->name_model,
            'lot'=>$oqafile->lotnumber_lab,
            'date'=>$oqafile->sem_update,
        ];
        $mail = new MailController();
        $mail->mailconfirmlpcWD($data);

        if($oqafile->stsem_mail == null)
        {
            $dataupdate = [
                'stsem_mail' => 1,
            ];
            DB::table('nt_oqa_lab')->where('id_createlab', $oqafile->id_createlab)->update($dataupdate);

        }
        else if($oqafile->stsem_mail == 1)
        {
            if($request->input('chkid') == 2)
            {
            $dataupdate = [
                'stsem_mail' => 2,
            ];
            DB::table('nt_oqa_lab')->where('id_createlab', $oqafile->id_createlab)->update($dataupdate);
            }

        }
        $oqafilemail =  DB::table('nt_oqa_lab')->where('id_createlab',$request->input('id'))->first();
        $chkid = $request->input('chkid');


        return response()->json(['success' => $chk,'id'=> $request->input('id'),'chkid'=> $chkid ]);
    }

    public function chkconfirmicWD(Request $request)
    {
        $id = $request->input('id');
        $oqafile =  DB::table('nt_oqa_file')
        ->join('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_oqa_file.id_createlab')
        ->join('nt_model', 'nt_model.id_model', '=', 'nt_oqa_lab.id_model')
        ->where('nt_oqa_file.id_createlab',$request->input('id'))->first();
        $file = storage_path('app/public/OQA/IC/'.$oqafile->file_ic);
        $chk = 0;

        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
        $sheet = $spreadsheet->getActiveSheet(0);
        $cellM23=$sheet->getCell('M23')->getCalculatedValue();
        $cellN23=$sheet->getCell('N23')->getCalculatedValue();
        $cellO23=$sheet->getCell('O23')->getCalculatedValue();
        $ftimechk=$sheet->getCell('M4')->getCalculatedValue();

        $ftime = date("Y/m/d H:i:s", strtotime($ftimechk));
        if($cellM23 != "" && $cellN23 != "" && $cellO23 != "")
        {
            $updates = [
                'ic_confirm_st' => 1,
                'ftime_ic' =>$ftime,
            ];
            DB::table('nt_oqa_lab')->where('id_createlab', $id)->update($updates);
            $chk = 1;

        }else{

            $chk = 0;
        }

        $subject = "[CAS program]_You have a new cleanliness results!!!";
        $data = [
            'subject'=>$subject,
            'testter'=>"IC",
            'model'=>$oqafile->name_model,
            'lot'=>$oqafile->lotnumber_lab,
            'date'=>$oqafile->ic_update,
        ];
        $mail = new MailController();
        $mail->mailconfirmlpcWD($data);

        if($oqafile->stic_mail == null)
        {
            $dataupdate = [
                'stic_mail' => 1,
            ];
            DB::table('nt_oqa_lab')->where('id_createlab', $oqafile->id_createlab)->update($dataupdate);

        }
        else if($oqafile->stic_mail == 1)
        {   if($request->input('chkid') == 2)
            {
            $dataupdate = [
                'stic_mail' => 2,
            ];
            DB::table('nt_oqa_lab')->where('id_createlab', $oqafile->id_createlab)->update($dataupdate);
            }

        }

        $oqafilemail =  DB::table('nt_oqa_lab')->where('id_createlab',$request->input('id'))->first();
        $chkid = $request->input('chkid');

        return response()->json(['success' =>  $chk,'id'=> $request->input('id')]);
    }

    public function chkconfirmgcmsWD(Request $request)
    {
        $id = $request->input('id');
        $oqafile =  DB::table('nt_oqa_file')
        ->join('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_oqa_file.id_createlab')
        ->join('nt_model', 'nt_model.id_model', '=', 'nt_oqa_lab.id_model')
        ->where('nt_oqa_file.id_createlab',$request->input('id'))->first();
        // $gcms = nhkviewgcms::gcms($oqafile->id_createlab,$oqafile->id_model);

        $countgcms =  DB::table('nt_outgassing_gcms')->where('id_model',$oqafile->id_model)->count();
        $file = storage_path('app/public/OQA/GCMS/'.$oqafile->file_gcms);
        $chk = 0;

        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
        $sheet = $spreadsheet->getActiveSheet(0);
        $cell=$sheet->getCell('G18')->getCalculatedValue();

        if($cell != "")
        {
            $updates = [
                'gcms_confirm_st' => 1,
                'ftime_gcms' =>$ftime,
            ];
            DB::table('nt_oqa_lab')->where('id_createlab', $id)->update($updates);

            $chk = 1;
        }
        else
        {
            $chk = 0;
        }

        $subject = "[CAS program]_You have a new cleanliness results!!!";

        $data = [
            'subject'=>$subject,
            'testter'=>"GCMS",
            'model'=>$oqafile->name_model,
            'lot'=>$oqafile->lotnumber_lab,
            'date'=>$oqafile->gcms_update,
        ];
        $mail = new MailController();
        $mail->mailconfirmlpcWD($data);
        if($oqafile->stgcms_mail == null)
        {
            $dataupdate = [
                'stgcms_mail' => 1,
            ];
            DB::table('nt_oqa_lab')->where('id_createlab', $oqafile->id_createlab)->update($dataupdate);

        }
        else if($oqafile->stgcms_mail == 1)
        {
            if($request->input('chkid') == 2)
            {
                $dataupdate = [
                    'stgcms_mail' => 2,
                ];
                DB::table('nt_oqa_lab')->where('id_createlab', $oqafile->id_createlab)->update($dataupdate);
            }

        }
        $oqafilemail =  DB::table('nt_oqa_lab')->where('id_createlab',$request->input('id'))->first();
        $chkid = $request->input('chkid');

        return response()->json(['success' =>  $chk,'id'=> $request->input('id')]);
    }

    public function chkconfirmftirWD(Request $request)
    {
        $id = $request->input('id');
        $oqafile =  DB::table('nt_oqa_file')
        ->join('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_oqa_file.id_createlab')
        ->join('nt_model', 'nt_model.id_model', '=', 'nt_oqa_lab.id_model')
        ->where('nt_oqa_file.id_createlab',$request->input('id'))->first();
        $file = storage_path('app/public/OQA/FTIR/'.$oqafile->file_ftir);
        $chk = 0;
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
        $sheet = $spreadsheet->getActiveSheet(0);
        $values=$sheet->getCell('G18')->getCalculatedValue();
        $cell = "$values";

        if($cell != "" || $cell != null)
        {
            $updates = [
               'ftir_confirm_st' => 1,
               'ftime_ftir' =>$ftime,
            ];
            DB::table('nt_oqa_lab')->where('id_createlab', $id)->update($updates);

            $chk = 1;
        }
        else
        {
            $chk = 0;
        }
        $subject = "[CAS program]_You have a new cleanliness results!!!";

        $data = [
            'subject'=>$subject,
            'testter'=>"FTIR",
            'model'=>$oqafile->name_model,
            'lot'=>$oqafile->lotnumber_lab,
            'date'=>$oqafile->ftir_update,
        ];
        $mail = new MailController();
        $mail->mailconfirmlpcWD($data);

        if($oqafile->stftir_mail == null)
        {
            $dataupdate = [
                'stftir_mail' => 1,
            ];
            DB::table('nt_oqa_lab')->where('id_createlab', $oqafile->id_createlab)->update($dataupdate);

        }
        else if($oqafile->stftir_mail == 1)
        {   if($request->input('chkid') == 2)
            {
                $dataupdate = [
                    'stftir_mail' => 2,
                ];
                DB::table('nt_oqa_lab')->where('id_createlab', $oqafile->id_createlab)->update($dataupdate);
            }

        }
        $oqafilemail =  DB::table('nt_oqa_lab')->where('id_createlab',$request->input('id'))->first();
        $chkid = $request->input('chkid');


        return response()->json(['success' =>  $chk,'id'=> $request->input('id')]);
    }

    public function chkconfirmsemtestWD(Request $request)
    {
        $id = $request->input('id');
        $oqafile =  DB::table('nt_oqa_file')
        ->join('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_oqa_file.id_createlab')
        ->join('nt_model', 'nt_model.id_model', '=', 'nt_oqa_lab.id_model')
        ->where('nt_oqa_file.id_createlab',$request->input('id'))->first();
        $file = storage_path('app/public/OQA/GhostTest/'.$oqafile->file_semtest);
        $chk = 0;

        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
        $sheet = $spreadsheet->getActiveSheet(0);
        $values=$sheet->getCell('G9')->getCalculatedValue();
        $cell = "$values";

        if($cell != "" || $cell != null)
        {
            $updates = [
               'semtest_confirm_st' => 1,
               'ftime_semtest' =>$ftime,
            ];
            DB::table('nt_oqa_lab')->where('id_createlab', $id)->update($updates);
            $chk = 1;
        }
        else
        {
            $chk = 0;
        }

        $subject = "[CAS program]_You have a new cleanliness results!!!";

        $data = [
            'subject'=>$subject,
            'testter'=>"GhostTest",
            'model'=>$oqafile->name_model,
            'lot'=>$oqafile->lotnumber_lab,
            'date'=>$oqafile->semtest_update,
        ];
        $mail = new MailController();
        $mail->mailconfirmlpcWD($data);

        if($oqafile->stsemtest_mail == null)
        {
            $dataupdate = [
                'stsemtest_mail' => 1,
            ];
            DB::table('nt_oqa_lab')->where('id_createlab', $oqafile->id_createlab)->update($dataupdate);

        }
        else if($oqafile->stsemtest_mail == 1)
        {   if($request->input('chkid') == 2)
            {
                $dataupdate = [
                    'stsemtest_mail' => 2,
                ];
                DB::table('nt_oqa_lab')->where('id_createlab', $oqafile->id_createlab)->update($dataupdate);
            }
        }
        $oqafilemail =  DB::table('nt_oqa_lab')->where('id_createlab',$request->input('id'))->first();
        $chkid = $request->input('chkid');

        return response()->json(['success' =>  $chk,'id'=> $request->input('id')]);
    }

    // public function getModalList(Request $request)
    // {
    //     $option = $request->selects;

    //     $model = DB::table('nt_model')->select('id_model','status_model','name_model')->where('model_type','LIKE', $option.'%')->where('status_model',1)->get();
    //     $data = array(
    //         "model" => $model,
    //     );
    //     echo json_encode($data);

    // }
    public function getModalList(Request $request)
    {
        $option = $request->selects;
        $type = $request->type;

        if(isset($request->partnumber)){
            if(!empty($type)){
                $model = DB::table('nt_model')
                ->select('id_model', 'status_model', 'name_model', 'model_type')
                ->where('part_number', $request->partnumber)
                ->where('model_type', 'LIKE', '%' . $type . '%')
                ->where('status_model', 1)
                ->get();
            }else{
                $model = DB::table('nt_model')->select('id_model','status_model','name_model')->where('part_number', $request->partnumber)->where('status_model',1)->get();
            }
        }else{
            $model = DB::table('nt_model')->select('id_model','status_model','name_model')->where('model_type','LIKE', $option.'%')->where('status_model',1)->get();
        }
        $data = array(
            "model" => $model,
        );
        echo json_encode($data);
    }

    public function newchkconfirmgcms(Request $request)
    {

            $id = $request->input('id');
            $oqafile =  DB::table('nt_oqa_file')
            ->join('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_oqa_file.id_createlab')
            ->join('nt_model', 'nt_model.id_model', '=', 'nt_oqa_lab.id_model')
            ->where('nt_oqa_file.id_createlab',$request->input('id'))->first();
            // $gcms = nhkviewgcms::gcms($oqafile->id_createlab,$oqafile->id_model);

            $countgcms =  DB::table('nt_outgassing_gcms')->where('id_model',$oqafile->id_model)->count();
            $file = storage_path('app/public/OQA/GCMS/'.$oqafile->file_gcms);

            //* 4.SGT AML return */
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
            $worksheet = $spreadsheet->setActiveSheetIndexByName('AML');
            $AML_gcms = $worksheet->getCell('F2')->getOldCalculatedValue();
            $text_aml_gcms = [
                'aml_gcms' => $AML_gcms,
            ];
            DB::table('nt_oqa_lab')->where('id_createlab', $id)->update($text_aml_gcms);
            //* 4.SGT AML return */

            $chk = 0;
            Excel::load($file, function($doc)use($id,$oqafile,$countgcms,&$chk) {

                $sheet = $doc->setActiveSheetIndexByName('Sheet1');
                $cell=$sheet->getCell('E75')->getCalculatedValue();

                if($cell != "")
                {

                    $ftimechk = $sheet->getCell('C14')->getCalculatedValue();
                    $ftime = date("Y/m/d H:i:s", strtotime($ftimechk));
                    $B21 = $sheet->getCell('E21')->getCalculatedValue();
                    $B32 = $sheet->getCell('E32')->getCalculatedValue();
                    $B37 = $sheet->getCell('E37')->getCalculatedValue();
                    $B45 = $sheet->getCell('E45')->getCalculatedValue();
                    $B46 = $sheet->getCell('E46')->getCalculatedValue();
                    $B65 = $sheet->getCell('E65')->getCalculatedValue();

                    $sum_smear = $B21+ $B32+ $B37+$B45+$B46+$B65;
                    $oqagcms = [
                        'gcms_smear' => $sum_smear,
                        'gcms_outgassing' => $sheet->getCell('E75')->getCalculatedValue(),
                        'gcms_low_mw' => $sheet->getCell('E73')->getCalculatedValue(),
                        'gcms_high_mw' => $sheet->getCell('E74')->getCalculatedValue(),
                        'gcms_datesave' => date('Y-m-d H:i:s'),
                        'gcms_st' => 1,
                    ];

                    $outg = [
                        'id_model' => $oqafile->id_model,
                        'id_createlab' => $oqafile->id_createlab,
                        'savedate' => date('Y-m-d H:i:s'),
                        'c1'=>$sheet->getCell('E17')->getCalculatedValue(),
                        'c2'=>$sheet->getCell('E18')->getCalculatedValue(),
                        'c3'=>$sheet->getCell('E19')->getCalculatedValue(),
                        'c4'=>$sheet->getCell('E20')->getCalculatedValue(),
                        'c5'=>$sheet->getCell('E21')->getCalculatedValue(),
                        'c5_1'=>$sheet->getCell('E22')->getCalculatedValue(),
                        'c5_2'=>$sheet->getCell('E23')->getCalculatedValue(),
                        'c5_3'=>$sheet->getCell('E24')->getCalculatedValue(),
                        'c6'=>$sheet->getCell('E25')->getCalculatedValue(),
                        'c6_1'=>$sheet->getCell('E26')->getCalculatedValue(),
                        'c6_2'=>$sheet->getCell('E27')->getCalculatedValue(),
                        'c7'=>$sheet->getCell('E28')->getCalculatedValue(),
                        'c8'=>$sheet->getCell('E29')->getCalculatedValue(),
                        'c8_1'=>$sheet->getCell('E30')->getCalculatedValue(),
                        'c8_2'=>$sheet->getCell('E31')->getCalculatedValue(),
                        'c9'=>$sheet->getCell('E32')->getCalculatedValue(),
                        'c9_1'=>$sheet->getCell('E33')->getCalculatedValue(),
                        'c9_2'=>$sheet->getCell('E34')->getCalculatedValue(),
                        'c10'=>$sheet->getCell('E35')->getCalculatedValue(),
                        'c11'=>$sheet->getCell('E36')->getCalculatedValue(),
                        'c11_1'=>$sheet->getCell('E37')->getCalculatedValue(),
                        'c11_2'=>$sheet->getCell('E38')->getCalculatedValue(),
                        'c12'=>$sheet->getCell('E39')->getCalculatedValue(),
                        'c13'=>$sheet->getCell('E40')->getCalculatedValue(),
                        'c14'=>$sheet->getCell('E41')->getCalculatedValue(),
                        'c15'=>$sheet->getCell('E42')->getCalculatedValue(),
                        'c15_1'=>$sheet->getCell('E43')->getCalculatedValue(),
                        'c15_2'=>$sheet->getCell('E44')->getCalculatedValue(),
                        'c16'=>$sheet->getCell('E45')->getCalculatedValue(),
                        'c17'=>$sheet->getCell('E46')->getCalculatedValue(),
                        'c18'=>$sheet->getCell('E47')->getCalculatedValue(),
                        'c19'=>$sheet->getCell('E48')->getCalculatedValue(),
                        'c19_1'=>$sheet->getCell('E49')->getCalculatedValue(),
                        'c19_2'=>$sheet->getCell('E50')->getCalculatedValue(),
                        'c19_3'=>$sheet->getCell('E51')->getCalculatedValue(),
                        'c20'=>$sheet->getCell('E52')->getCalculatedValue(),
                        'c20_1'=>$sheet->getCell('E53')->getCalculatedValue(),
                        'c21'=>$sheet->getCell('E54')->getCalculatedValue(),
                        'c21_1'=>$sheet->getCell('E55')->getCalculatedValue(),
                        'c22'=>$sheet->getCell('E56')->getCalculatedValue(),
                        'c22_1'=>$sheet->getCell('E57')->getCalculatedValue(),
                        'c22_2'=>$sheet->getCell('E58')->getCalculatedValue(),
                        'c23'=>$sheet->getCell('E59')->getCalculatedValue(),
                        'c23_1'=>$sheet->getCell('E60')->getCalculatedValue(),
                        'c24'=>$sheet->getCell('E61')->getCalculatedValue(),
                        'c24_1'=>$sheet->getCell('E62')->getCalculatedValue(),
                        'c24_2'=>$sheet->getCell('E63')->getCalculatedValue(),
                        'c25'=>$sheet->getCell('E64')->getCalculatedValue(),
                        'c26'=>$sheet->getCell('E65')->getCalculatedValue(),
                        'c27'=>$sheet->getCell('E66')->getCalculatedValue(),
                        'c28'=>$sheet->getCell('E67')->getCalculatedValue(),
                        'c29'=>$sheet->getCell('E68')->getCalculatedValue(),
                        'c30'=>$sheet->getCell('E69')->getCalculatedValue(),
                        'c31'=>$sheet->getCell('E70')->getCalculatedValue(),
                        'c32'=>$sheet->getCell('E71')->getCalculatedValue(),
                        'c33'=>$sheet->getCell('E72')->getCalculatedValue(),
                        'c34'=>$sheet->getCell('E73')->getCalculatedValue(),
                        'c35'=>$sheet->getCell('E74')->getCalculatedValue(),
                        'total'=>$sheet->getCell('E75')->getCalculatedValue(),

                        'c19_4'=>$sheet->getCell('E76')->getCalculatedValue(),
                        'c19_5'=>$sheet->getCell('E77')->getCalculatedValue(),
                        'c35_1'=>$sheet->getCell('E78')->getCalculatedValue(),
                        'c35_2'=>$sheet->getCell('E79')->getCalculatedValue(),

                        'spare_01'=>(($sheet->getCell('E80')->getCalculatedValue() != 'N/A')? $sheet->getCell('E80')->getCalculatedValue() : null),
                        'spare_02'=>(($sheet->getCell('E81')->getCalculatedValue() != 'N/A')? $sheet->getCell('E81')->getCalculatedValue() : null),
                        'spare_03'=>(($sheet->getCell('E82')->getCalculatedValue() != 'N/A')? $sheet->getCell('E82')->getCalculatedValue() : null),
                        'spare_04'=>(($sheet->getCell('E83')->getCalculatedValue() != 'N/A')? $sheet->getCell('E83')->getCalculatedValue() : null),
                        'spare_05'=>(($sheet->getCell('E84')->getCalculatedValue() != 'N/A')? $sheet->getCell('E84')->getCalculatedValue() : null),
                        'spare_06'=>(($sheet->getCell('E85')->getCalculatedValue() != 'N/A')? $sheet->getCell('E86')->getCalculatedValue() : null),
                        'spare_07'=>(($sheet->getCell('E86')->getCalculatedValue() != 'N/A')? $sheet->getCell('E86')->getCalculatedValue() : null),
                        'spare_08'=>(($sheet->getCell('E87')->getCalculatedValue() != 'N/A')? $sheet->getCell('E87')->getCalculatedValue() : null),
                        'spare_09'=>(($sheet->getCell('E88')->getCalculatedValue() != 'N/A')? $sheet->getCell('E88')->getCalculatedValue() : null),
                        'spare_10'=>(($sheet->getCell('E89')->getCalculatedValue() != 'N/A')? $sheet->getCell('E89')->getCalculatedValue() : null),
                        'spare_11'=>(($sheet->getCell('E90')->getCalculatedValue() != 'N/A')? $sheet->getCell('E90')->getCalculatedValue() : null),
                        'spare_12'=>(($sheet->getCell('E91')->getCalculatedValue() != 'N/A')? $sheet->getCell('E91')->getCalculatedValue() : null),
                        'spare_13'=>(($sheet->getCell('E92')->getCalculatedValue() != 'N/A')? $sheet->getCell('E92')->getCalculatedValue() : null),
                        'spare_14'=>(($sheet->getCell('E93')->getCalculatedValue() != 'N/A')? $sheet->getCell('E93')->getCalculatedValue() : null),
                        'spare_15'=>(($sheet->getCell('E94')->getCalculatedValue() != 'N/A')? $sheet->getCell('E94')->getCalculatedValue() : null),
                        'spare_16'=>(($sheet->getCell('E95')->getCalculatedValue() != 'N/A')? $sheet->getCell('E95')->getCalculatedValue() : null),
                        'spare_17'=>(($sheet->getCell('E96')->getCalculatedValue() != 'N/A')? $sheet->getCell('E96')->getCalculatedValue() : null),
                        'spare_18'=>(($sheet->getCell('E97')->getCalculatedValue() != 'N/A')? $sheet->getCell('E97')->getCalculatedValue() : null),
                        'spare_19'=>(($sheet->getCell('E98')->getCalculatedValue() != 'N/A')? $sheet->getCell('E98')->getCalculatedValue() : null),
                        'spare_20'=>(($sheet->getCell('E99')->getCalculatedValue() != 'N/A')? $sheet->getCell('E99')->getCalculatedValue() : null),
                        'spare_21'=>(($sheet->getCell('E100')->getCalculatedValue() != 'N/A')? $sheet->getCell('E100')->getCalculatedValue() : null),
                        'spare_22'=>(($sheet->getCell('E101')->getCalculatedValue() != 'N/A')? $sheet->getCell('E101')->getCalculatedValue() : null),
                        'spare_23'=>(($sheet->getCell('E102')->getCalculatedValue() != 'N/A')? $sheet->getCell('E102')->getCalculatedValue() : null),
                        'spare_24'=>(($sheet->getCell('E103')->getCalculatedValue() != 'N/A')? $sheet->getCell('E103')->getCalculatedValue() : null),
                        'spare_25'=>(($sheet->getCell('E104')->getCalculatedValue() != 'N/A')? $sheet->getCell('E104')->getCalculatedValue() : null),
                        'spare_26'=>(($sheet->getCell('E105')->getCalculatedValue() != 'N/A')? $sheet->getCell('E105')->getCalculatedValue() : null),
                        'spare_27'=>(($sheet->getCell('E106')->getCalculatedValue() != 'N/A')? $sheet->getCell('E106')->getCalculatedValue() : null),
                        'spare_28'=>(($sheet->getCell('E107')->getCalculatedValue() != 'N/A')? $sheet->getCell('E107')->getCalculatedValue() : null),
                        'spare_29'=>(($sheet->getCell('E108')->getCalculatedValue() != 'N/A')? $sheet->getCell('E108')->getCalculatedValue() : null),
                        'spare_30'=>(($sheet->getCell('E109')->getCalculatedValue() != 'N/A')? $sheet->getCell('E109')->getCalculatedValue() : null),
                        'spare_31'=>(($sheet->getCell('E110')->getCalculatedValue() != 'N/A')? $sheet->getCell('E110')->getCalculatedValue() : null),
                        'spare_32'=>(($sheet->getCell('E111')->getCalculatedValue() != 'N/A')? $sheet->getCell('E111')->getCalculatedValue() : null),
                        'spare_33'=>(($sheet->getCell('E112')->getCalculatedValue() != 'N/A')? $sheet->getCell('E112')->getCalculatedValue() : null),
                        'spare_34'=>(($sheet->getCell('E113')->getCalculatedValue() != 'N/A')? $sheet->getCell('E113')->getCalculatedValue() : null),
                        'spare_35'=>(($sheet->getCell('E114')->getCalculatedValue() != 'N/A')? $sheet->getCell('E114')->getCalculatedValue() : null),
                        'spare_36'=>(($sheet->getCell('E115')->getCalculatedValue() != 'N/A')? $sheet->getCell('E115')->getCalculatedValue() : null),
                        'spare_37'=>(($sheet->getCell('E116')->getCalculatedValue() != 'N/A')? $sheet->getCell('E116')->getCalculatedValue() : null),

                    ];
                    $countoutg =  DB::table('nt_outgassing_gcms')
                    ->where('id_model',$oqafile->id_model)
                    ->where('id_createlab',$oqafile->id_createlab)->count();
                    if( $countoutg == 0 ){
                       DB::table('nt_outgassing_gcms')->insert($outg);
                    }
                    else
                    {
                        DB::table('nt_outgassing_gcms')->where('id_createlab', $oqafile->id_createlab)->where('id_model', $oqafile->id_model)->update($outg);
                    }

                    DB::table('nt_datacf_gcms')->where('id_createlab', $id)->update($oqagcms);
                    DB::table('nt_data_gcms')->where('id_createlab', $id)->update($oqagcms);
                    $updates = [
                       'gcms_confirm_st' => 1,
                       'ftime_gcms' =>$ftime,
                    ];
                    DB::table('nt_oqa_lab')->where('id_createlab', $id)->update($updates);

                    //----------- measurement L/T -------------
                    $file_id = $oqafile->file_id;
                    $restart = $sheet->getCell('J13')->getValue();
                    if (!empty($restart)) {
                        $start = $sheet->getCell('J14')->getOldCalculatedValue();
                        $end = $sheet->getCell('C14')->getCalculatedValue();

                        $start = str_replace(".0", "", $start);

                        $start = date('Y-m-d H:i:s',strtotime($start));
                        $end = date('Y-m-d H:i:s',strtotime($end));

                        $dataupdate = [
                            'gcms_begin' => $start,
                            'gcms_lasttime' => $end,
                        ];
                        DB::table('nt_oqa_file')->where('file_id', $file_id)->update($dataupdate);
                    } else {
                        $start = $sheet->getCell('B14')->getCalculatedValue();
                        $end = $sheet->getCell('C14')->getCalculatedValue();

                        $start = date('Y-m-d H:i:s',strtotime($start));
                        $end = date('Y-m-d H:i:s',strtotime($end));

                        $dataupdate = [
                            'gcms_begin' => $start,
                            'gcms_lasttime' => $end,
                        ];
                        DB::table('nt_oqa_file')->where('file_id', $file_id)->update($dataupdate);
                    }
                    //----------- measurement L/T -------------
                    $chk = 1;
                }
                else
                {
                    $chk = 0;
                }
            });

            $gcms = nhkviewgcms::gcms($oqafile->id_createlab,$oqafile->id_model);

            $subject = "[CAS program]_You have a new cleanliness results!!!";

            $data = [
                'subject'=>$subject,
                'testter'=>"GCMS",
                'model'=>$oqafile->name_model,
                'lot'=>$oqafile->lotnumber_lab,
                'date'=>$oqafile->gcms_update,
            ];
            $mail = new MailController();
            $mail->mailconfirm($data);
            // // nhkmail::mailconfirm($data);
            if($oqafile->stgcms_mail == null)
            {
                $dataupdate = [
                    'stgcms_mail' => 1,
                ];
                DB::table('nt_oqa_lab')->where('id_createlab', $oqafile->id_createlab)->update($dataupdate);

            }
            else if($oqafile->stgcms_mail == 1)
            {
                if($request->input('chkid') == 2)
                {
                    $dataupdate = [
                        'stgcms_mail' => 2,
                    ];
                    DB::table('nt_oqa_lab')->where('id_createlab', $oqafile->id_createlab)->update($dataupdate);
                }

            }
            $oqafilemail =  DB::table('nt_oqa_lab')->where('id_createlab',$request->input('id'))->first();
            $chkid = $request->input('chkid');

            // -------------- check status Sigma ratio ----------------------
            $ratiogcms = 0;
            $checkretio = new ResultsControlle();
            $val_checkretio =  $checkretio->chkconfirmRatio($oqafile->id_createlab);
            if($val_checkretio > 0){
                $ratiogcms += intval($val_checkretio);
            }
            // -------------- check status Sigma ratio ----------------------

            if($chkid == 1)
            {
		        if($gcms > 0 && $ratiogcms > 0){
                    $data = [
                        'testter'=>"GCMS",
                        'model'=>$oqafile->name_model,
                        'lot'=>$oqafile->lotnumber_lab,
                        'date'=>$oqafile->gcms_update,
                        'alarm'=>$gcms.' point',
                        'idqla'=>$oqafile->id_createlab,
                    ];
                    $mail = new MailController();
                    $mail->mailalarm($data);
                    // nhkmail::mailalarm($data);
                    $chk = 2;
                }
            }
            else if($chkid == 2)
            {
		        if( $gcms > 0 && $ratiogcms > 0 ){
                    $data = [
                        'testter'=>"GCMS",
                        'model'=>$oqafile->name_model,
                        'lot'=>$oqafile->lotnumber_lab,
                        'date'=>$oqafile->gcms_update,
                        'alarm'=>$gcms.' point',
                        'idqla'=>$oqafile->id_createlab,
                    ];
                    $mail = new MailController();
                    $mail->mailalarmstaff($data);
                    // nhkmail::mailalarmstaff($data);
                    $chk = 2;
                }
            }

            // ----------- insert Compound GCMS NHK2 --------------

            $id_createlab = $oqafile->id_createlab;

            if(file_exists($file)){

                $compound_gcms = DB::table('nt_compound_gcms')->where('id_createlab', $id_createlab)->count();
                if ($compound_gcms > 0) {
                    DB::table('nt_compound_gcms')->where('id_createlab', $id_createlab)->delete();
                }

                Excel::load($file, function($doc)use($id,$oqafile,$countgcms,&$chk) {

                    $sheet = $doc->setActiveSheetIndexByName('Sample');
                    $cell=$sheet->getCell('G35')->getCalculatedValue();

                    if($cell == "HUMP")
                    {
                        for ($i=35; $i <= 132 ; $i++) {

                            if($sheet->getCell('G'.$i)->getOldCalculatedValue() != '-'){

                                if ($i == 35) {
                                    $keyword = $cell;
                                } else {
                                    $keyword = $sheet->getCell('H'.$i)->getOldCalculatedValue();
                                }

                                if (DB::table('nt_compound_gcms_unit')->where('compound_name','LIKE', $keyword)->count() == 0) {

                                    $insert = [
                                        'compound_name' => $keyword,
                                    ];
                                    $compound_unit_id = DB::table('nt_compound_gcms_unit')->insert($insert);

                                }else{

                                    $compound_unit = DB::table('nt_compound_gcms_unit')->where('compound_name','LIKE', $keyword)->first();
                                    $compound_unit_id = $compound_unit->id_cmp;
                                }

                                $qty = $sheet->getCell('F'.$i)->getOldCalculatedValue();

                                $data = [
                                    'id_createlab' => $oqafile->id_createlab,
                                    'id_model' => $oqafile->id_model,
                                    'id_cmp' => $compound_unit_id,
                                    'qty' => $qty,
                                    'datesave' => date('Y-m-d H:i:s')
                                ];
                                DB::table('nt_compound_gcms')->insert($data);


                            }

                        }
                    }

                });

            }

            // ----------- insert Compound GCMS NHK2 --------------

            return response()->json(['success' =>  $chk,'id'=> $request->input('id')]);
    }

    public function upload_lpc_jnhk(Request $request)
    {

        $uploadedFile = $request->file('fileInput_LPC');
        if(!empty($uploadedFile)){
            $uploadedFileName = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);

            // อ่านไฟล์ Excel ที่อัปโหลด
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($uploadedFile->getPathname());
            $sheet = $spreadsheet->setActiveSheetIndex(1);

            $modelname = $sheet->getCell('G13')->getValue();
            $partnumber = $sheet->getCell('F13')->getValue();
            $lotnumber = $sheet->getCell('E13')->getValue();

            $lab = DB::table('nt_oqa_lab')->where('id_createlab', $request->id_createlab)->first();
            $model = DB::table('nt_model')->where('id_model', $lab->id_model)->first();

            if (!empty($model)){
                if ($modelname == $model->name_model
                && $partnumber == $model->part_number
                && $lotnumber == $lab->lotnumber_lab ) {

                    $filejnhk = DB::table('nt_oqa_aml_file')->where('id_createlab', $request->id_createlab)->first();
                    $save = [
                        'file_jnhk_lpc' => $uploadedFileName,
                        'jnhk_lpc_update' => date('Y-m-d H:i:s'),
                    ];

                    if(!empty($filejnhk)){
                        DB::table('nt_oqa_aml_file')->where('id_createlab', $request->id_createlab)->update($save);
                    }else{
                        DB::table('nt_oqa_aml_file')->insert($save);
                    }

                    $destinationPath = storage_path('NHK-File/OQA/JNHK');
                    $uploadedFile->move($destinationPath, $uploadedFile->getClientOriginalName());

                    $datamail = [
                        'testter' => 'LPC',
                        'model' => $model->name_model,
                        'lot' => $lab->lotnumber_lab,
                        'date' => $lab->save_date,
                    ];
                    $mail = new MailController();
                    @$mail->mailUploadJNHK($datamail);

                    return response()->json(['success' => 1]);


                }else{
                    return response()->json(['success' => 'Upload wrong detail!']);
                }

            } else {
                return response()->json(['success' => 'Upload wrong file!']);
            }
        }else {
            return response()->json(['success' => 'File not found!']);
        }

    }

    public function upload_gcms_jnhk(Request $request)
    {

        $uploadedFile = $request->file('fileInput_GCMS');
        if(!empty($uploadedFile)){

            $uploadedFileName = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);

            // อ่านไฟล์ Excel ที่อัปโหลด
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($uploadedFile->getPathname());
            $sheet = $spreadsheet->setActiveSheetIndex(1);

            $modelname = $sheet->getCell('E8')->getValue();
            $partnumber = $sheet->getCell('E7')->getValue();
            $lotnumber = $sheet->getCell('E4')->getValue();

            $lab = DB::table('nt_oqa_lab')->where('id_createlab', $request->id_createlab)->first();
            $model = DB::table('nt_model')->where('id_model', $lab->id_model)->first();

            if (!empty($model)){
                if ($modelname == $model->name_model
                && $partnumber == $model->part_number
                && $lotnumber == $lab->lotnumber_lab ) {

                    $filejnhk = DB::table('nt_oqa_aml_file')->where('id_createlab', $request->id_createlab)->first();

                    $save['file_jnhk_gcms'] = $uploadedFileName;
                    $save['file_jnhk_gcms'] = date('Y-m-d H:i:s');

                    // ตรวจสอบว่ามีไฟล์ที่ชื่อ 'zipFile' อัปโหลดมาหรือไม่
                    if ($request->hasFile('zipFile')) {
                        if ($request->file('zipFile')->isValid() && $request->file('zipFile')->getClientOriginalExtension() === 'zip') {
                            $zipFile = $request->file('zipFile');
                            $zipname = $zipFile->getClientOriginalName();;
                            $save['gcms_zip_file'] = $zipname;
                        }
                    }

                    if(!empty($filejnhk)){
                        DB::table('nt_oqa_aml_file')->where('id_createlab', $request->id_createlab)->update($save);
                    }else{
                        DB::table('nt_oqa_aml_file')->insert($save);
                    }

                    $destinationPath = storage_path('NHK-File/OQA/JNHK');
                    $uploadedFile->move($destinationPath, $uploadedFile->getClientOriginalName());

                    $datamail = [
                        'testter' => 'GCMS',
                        'model' => $model->name_model,
                        'lot' => $lab->lotnumber_lab,
                        'date' => $lab->save_date,
                    ];
                    $mail = new MailController();
                    @$mail->mailUploadJNHK($datamail);

                    return response()->json(['success' => 1]);

                }else{
                    return response()->json(['success' => 'Upload wrong detail!']);
                }

            } else {
                return response()->json(['success' => 'Upload wrong file!']);
            }
        }else {
            return response()->json(['success' => 'File not found!']);
        }
    }

    public function log_scanbarcode(Request $request)
    {
        $deta = [
            'datacode' => $request->chktype,
            'type' => $request->barcodetxt,
            'partNumber' => $request->partNumber,
            'lotNumber' => $request->lotNumber,
            'status' => $request->status,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        DB::table('log_scanbarcode')->insert($deta);
        return response()->json(['success' => 1]);
    }


}
