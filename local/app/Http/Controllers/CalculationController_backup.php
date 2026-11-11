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
use Session;
use slope;
use PDF;
use Dompdf\Dompdf;

class CalculationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function datatablecalculation(Request $request)	
    { 
        if(empty($request->date_start) && empty($request->date_end))
        {
            $datalist = DB::table('nt_model')->where('status_model', 1)->get();
            $sQuery	= Datatables::of($datalist)
            ->addIndexColumn()
            ->addColumn('lpccount',function($data){            
                $lpccount = null;
                return $lpccount;
             })
             ->addColumn('semcount',function($data){            
                $semcount = null;
                return $semcount;
             })
             ->addColumn('iccount',function($data){            
                $iccount = null;
                return $iccount;
             })
             ->addColumn('gcmscount',function($data){            
                $gcmscount = null;
                return $gcmscount;
             })
             ->addColumn('ftircount',function($data){            
                $ftircount = null;
                return $ftircount;
             })
            ->addColumn('countdata',function($data){      
                 $chk =1;
                 return $chk;
              })
              ->addColumn('lastdate',function($data){            
                 $value = '';
                 return $value;
              })
              ->addColumn('firstdate',function($data){            
                $value = '';
                return $value;
             })
             ->addColumn('mean',function($data){            
                $value = null;
                return $value;
             })
             ->addColumn('max',function($data){            
                $value = null;
                return $value;
             })
             ->addColumn('min',function($data){            
                $value = null;
                return $value;
             })
             ->addColumn('std',function($data){            
                $value = null;
                return $value;
             })
             ->addColumn('mean3',function($data){            
                $value = null;
                return $value;
             })
             ->addColumn('mean4',function($data){            
                $value = null;
                return $value;
             })
             ->addColumn('mean4_5',function($data){            
                $value = null;
                return $value;
             })
             ->addColumn('mean6',function($data){            
                $value = null;
                return $value;
             });
        }
        else
        {
           $datestart = date('Y-m-d H:i:s', strtotime($request->date_start));
           $dateend = date('Y-m-d 00:00:00', strtotime($request->date_end. ' + 1 day'));
           $start = "'$datestart'";
           $end = "'$dateend'";
           $number = "'$request->number'";
           $datalist = DB::table('nt_model')
           ->where('status_model', 1)
           ->select('nt_model.*',DB::raw(''.$start .' as startdate'),DB::raw(''.$end .' as enddate'),DB::raw(''.$number .' as number'))->get();
           $sQuery	= Datatables::of($datalist)
                ->addIndexColumn()
                ->addColumn('lpccount',function($data){     
                    
                    if($data->number == 0)
                    {           
                        $firstdate = $data->startdate;
                        $lastdate = $data->enddate;
                    }
                    else
                    {          
                    $datachkf = DB::table('nt_model')
                    ->join('nt_oqa_lab','nt_oqa_lab.id_model','=','nt_model.id_model')
                    ->where('nt_oqa_lab.save_date','>=',$data->startdate)
                    ->where('nt_oqa_lab.save_date','<=',$data->enddate)
                    ->where('nt_model.id_model','=',$data->id_model)
                    ->select('nt_oqa_lab.save_date as save_datest')
                    ->orderBy('nt_oqa_lab.save_date', 'DESC')->first();
                     if($datachkf == null)
                     {
                         $firstdate = '';
                     }
                     else
                     {
                         $firstdate = $dateend = date('Y-m-d 00:00:00', strtotime($datachkf->save_datest. ' -'.$data->number.' day')); 
                     }

                     $datachkl = DB::table('nt_model')
                     ->join('nt_oqa_lab','nt_oqa_lab.id_model','=','nt_model.id_model')
                     ->where('nt_oqa_lab.save_date','>=',$data->startdate)
                     ->where('nt_oqa_lab.save_date','<=',$data->enddate)
                     ->where('nt_model.id_model','=',$data->id_model)
                     ->select('nt_oqa_lab.save_date as save_datest')
                     ->orderBy('nt_oqa_lab.save_date', 'DESC')->first();
                      if($datachkl == null)
                      {
                          $lastdate = '';
                      }
                      else
                      {
                          $lastdate = $datachkl->save_datest;
                      }
                    }

                    $lpccount = DB::table('nt_data_lpc')
                    ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_lpc.id_createlab')
                    ->where('nt_oqa_lab.save_date','>=',$firstdate)
                    ->where('nt_oqa_lab.save_date','<=',$lastdate)
                    ->where('nt_oqa_lab.id_model',$data->id_model)->count();
                    return $lpccount;
                 })
                 ->addColumn('semcount',function($data){ 
                    if($data->number == 0)
                    {           
                        $firstdate = $data->startdate;
                        $lastdate = $data->enddate;
                    }
                    else
                    {          
                    $datachkf = DB::table('nt_model')
                    ->join('nt_oqa_lab','nt_oqa_lab.id_model','=','nt_model.id_model')
                    ->where('nt_oqa_lab.save_date','>=',$data->startdate)
                    ->where('nt_oqa_lab.save_date','<=',$data->enddate)
                    ->where('nt_model.id_model','=',$data->id_model)
                    ->select('nt_oqa_lab.save_date as save_datest')
                    ->orderBy('nt_oqa_lab.save_date', 'DESC')->first();
                     if($datachkf == null)
                     {
                         $firstdate = '';
                     }
                     else
                     {
                         $firstdate = $dateend = date('Y-m-d 00:00:00', strtotime($datachkf->save_datest. ' -'.$data->number.' day')); 
                     }

                     $datachkl = DB::table('nt_model')
                     ->join('nt_oqa_lab','nt_oqa_lab.id_model','=','nt_model.id_model')
                     ->where('nt_oqa_lab.save_date','>=',$data->startdate)
                     ->where('nt_oqa_lab.save_date','<=',$data->enddate)
                     ->where('nt_model.id_model','=',$data->id_model)
                     ->select('nt_oqa_lab.save_date as save_datest')
                     ->orderBy('nt_oqa_lab.save_date', 'DESC')->first();
                      if($datachkl == null)
                      {
                          $lastdate = '';
                      }
                      else
                      {
                          $lastdate = $datachkl->save_datest;
                      }
                    }           
                    $semcount = DB::table('nt_data_sem')
                    ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                    ->where('nt_oqa_lab.save_date','>=',$firstdate)
                    ->where('nt_oqa_lab.save_date','<=',$lastdate)
                    ->where('nt_oqa_lab.id_model',$data->id_model)->count();;
                    return $semcount;
                 })
                 ->addColumn('iccount',function($data){
                    if($data->number == 0)
                    {           
                        $firstdate = $data->startdate;
                        $lastdate = $data->enddate;
                    }
                    else
                    {          
                    $datachkf = DB::table('nt_model')
                    ->join('nt_oqa_lab','nt_oqa_lab.id_model','=','nt_model.id_model')
                    ->where('nt_oqa_lab.save_date','>=',$data->startdate)
                    ->where('nt_oqa_lab.save_date','<=',$data->enddate)
                    ->where('nt_model.id_model','=',$data->id_model)
                    ->select('nt_oqa_lab.save_date as save_datest')
                    ->orderBy('nt_oqa_lab.save_date', 'DESC')->first();
                     if($datachkf == null)
                     {
                         $firstdate = '';
                     }
                     else
                     {
                         $firstdate = $dateend = date('Y-m-d 00:00:00', strtotime($datachkf->save_datest. ' -'.$data->number.' day')); 
                     }

                     $datachkl = DB::table('nt_model')
                     ->join('nt_oqa_lab','nt_oqa_lab.id_model','=','nt_model.id_model')
                     ->where('nt_oqa_lab.save_date','>=',$data->startdate)
                     ->where('nt_oqa_lab.save_date','<=',$data->enddate)
                     ->where('nt_model.id_model','=',$data->id_model)
                     ->select('nt_oqa_lab.save_date as save_datest')
                     ->orderBy('nt_oqa_lab.save_date', 'DESC')->first();
                      if($datachkl == null)
                      {
                          $lastdate = '';
                      }
                      else
                      {
                          $lastdate = $datachkl->save_datest;
                      }
                    }            
                    $iccount = DB::table('nt_data_ic')
                    ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ic.id_createlab')
                    ->where('nt_oqa_lab.save_date','>=',$firstdate)
                    ->where('nt_oqa_lab.save_date','<=',$lastdate)
                    ->where('nt_oqa_lab.id_model',$data->id_model)->count();
                    return $iccount;
                 })
                 ->addColumn('gcmscount',function($data){ 
                    if($data->number == 0)
                    {           
                        $firstdate = $data->startdate;
                        $lastdate = $data->enddate;
                    }
                    else
                    {          
                    $datachkf = DB::table('nt_model')
                    ->join('nt_oqa_lab','nt_oqa_lab.id_model','=','nt_model.id_model')
                    ->where('nt_oqa_lab.save_date','>=',$data->startdate)
                    ->where('nt_oqa_lab.save_date','<=',$data->enddate)
                    ->where('nt_model.id_model','=',$data->id_model)
                    ->select('nt_oqa_lab.save_date as save_datest')
                    ->orderBy('nt_oqa_lab.save_date', 'DESC')->first();
                     if($datachkf == null)
                     {
                         $firstdate = '';
                     }
                     else
                     {
                         $firstdate = $dateend = date('Y-m-d 00:00:00', strtotime($datachkf->save_datest. ' -'.$data->number.' day')); 
                     }

                     $datachkl = DB::table('nt_model')
                     ->join('nt_oqa_lab','nt_oqa_lab.id_model','=','nt_model.id_model')
                     ->where('nt_oqa_lab.save_date','>=',$data->startdate)
                     ->where('nt_oqa_lab.save_date','<=',$data->enddate)
                     ->where('nt_model.id_model','=',$data->id_model)
                     ->select('nt_oqa_lab.save_date as save_datest')
                     ->orderBy('nt_oqa_lab.save_date', 'DESC')->first();
                      if($datachkl == null)
                      {
                          $lastdate = '';
                      }
                      else
                      {
                          $lastdate = $datachkl->save_datest;
                      }
                    }           
                    $gcmscount = DB::table('nt_data_gcms')
                    ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
                    ->where('nt_oqa_lab.save_date','>=',$firstdate)
                    ->where('nt_oqa_lab.save_date','<=',$lastdate)
                    ->where('nt_oqa_lab.id_model',$data->id_model)->count();
                    return $gcmscount;
                 })
                 ->addColumn('ftircount',function($data){  
                    if($data->number == 0)
                    {           
                        $firstdate = $data->startdate;
                        $lastdate = $data->enddate;
                    }
                    else
                    {          
                    $datachkf = DB::table('nt_model')
                    ->join('nt_oqa_lab','nt_oqa_lab.id_model','=','nt_model.id_model')
                    ->where('nt_oqa_lab.save_date','>=',$data->startdate)
                    ->where('nt_oqa_lab.save_date','<=',$data->enddate)
                    ->where('nt_model.id_model','=',$data->id_model)
                    ->where('nt_oqa_lab.lpc_confirm_st',1)
                    ->select('nt_oqa_lab.save_date as save_datest')
                    ->orderBy('nt_oqa_lab.save_date', 'DESC')->first();
                     if($datachkf == null)
                     {
                         $firstdate = '';
                     }
                     else
                     {
                         $firstdate = $dateend = date('Y-m-d 00:00:00', strtotime($datachkf->save_datest. ' -'.$data->number.' day')); 
                     }

                     $datachkl = DB::table('nt_model')
                     ->join('nt_oqa_lab','nt_oqa_lab.id_model','=','nt_model.id_model')
                     ->where('nt_oqa_lab.save_date','>=',$data->startdate)
                     ->where('nt_oqa_lab.save_date','<=',$data->enddate)
                     ->where('nt_model.id_model','=',$data->id_model)
                     ->where('nt_oqa_lab.lpc_confirm_st',1)
                     ->select('nt_oqa_lab.save_date as save_datest')
                     ->orderBy('nt_oqa_lab.save_date', 'DESC')->first();
                      if($datachkl == null)
                      {
                          $lastdate = '';
                      }
                      else
                      {
                          $lastdate = $datachkl->save_datest;
                      }
                    }          
                    $ftircount = DB::table('nt_datacf_ftir')
                    ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_datacf_ftir.id_createlab')
                    ->where('nt_oqa_lab.save_date','>=',$firstdate)
                    ->where('nt_oqa_lab.save_date','<=',$lastdate)
                    ->where('nt_oqa_lab.ftir_confirm_st',1)
                    ->where('nt_oqa_lab.id_model',$data->id_model)->count();
                    return $ftircount;
                 })
                 ->addColumn('countdata',function($data){            
                    $chk =2;
                    return $chk;
                  })
                 ->addColumn('lastdate',function($data){    
                    if($data->number == 0)
                    {           
                        $value = date('Y-m-d 00:00:00', strtotime($data->enddate."-1 day"));
                    }
                    else
                    {        
                    $datachk = DB::table('nt_model')
                    ->join('nt_oqa_lab','nt_oqa_lab.id_model','=','nt_model.id_model')
                    ->where('nt_oqa_lab.save_date','>=',$data->startdate)
                    ->where('nt_oqa_lab.save_date','<=',$data->enddate)
                    ->where('nt_model.id_model','=',$data->id_model)
                    ->select('nt_oqa_lab.save_date as save_datest')
                    ->orderBy('nt_oqa_lab.save_date', 'DESC')->first();
                     if($datachk == null)
                     {
                         $value = '';
                     }
                     else
                     {
                         $value = $datachk->save_datest;
                     }
                    }
                     return $value;
                  })
                  ->addColumn('firstdate',function($data){  
                    if($data->number == 0)
                    {           
                        $value = $data->startdate;
                        
                    }
                    else
                    {            
                    $datachk = DB::table('nt_model')
                    ->join('nt_oqa_lab','nt_oqa_lab.id_model','=','nt_model.id_model')
                    ->where('nt_oqa_lab.save_date','>=',$data->startdate)
                    ->where('nt_oqa_lab.save_date','<=',$data->enddate)
                    ->where('nt_model.id_model','=',$data->id_model)
                    ->select('nt_oqa_lab.save_date as save_datest')
                    ->orderBy('nt_oqa_lab.save_date', 'DESC')->first();
                     if($datachk == null)
                     {
                         $value = '';
                     }
                     else
                     {
                         $value = $dateend = date('Y-m-d 00:00:00', strtotime($datachk->save_datest. ' -'.$data->number.' day')); 
                     }
                    }
                     return $value;
                  })
                  ->addColumn('mean',function($data){
                    if($data->number == 0)
                    {           
                        $firstdate = $data->startdate;
                        $lastdate = $data->enddate;
                    }
                    else
                    {          
                    $datachkf = DB::table('nt_model')
                    ->join('nt_oqa_lab','nt_oqa_lab.id_model','=','nt_model.id_model')
                    ->where('nt_oqa_lab.save_date','>=',$data->startdate)
                    ->where('nt_oqa_lab.save_date','<=',$data->enddate)
                    ->where('nt_model.id_model','=',$data->id_model)
                    ->select('nt_oqa_lab.save_date as save_datest')
                    ->orderBy('nt_oqa_lab.save_date', 'DESC')->first();
                     if($datachkf == null)
                     {
                         $firstdate = '';
                     }
                     else
                     {
                         $firstdate = $dateend = date('Y-m-d 00:00:00', strtotime($datachkf->save_datest. ' -'.$data->number.' day')); 
                     }

                     $datachkl = DB::table('nt_model')
                     ->join('nt_oqa_lab','nt_oqa_lab.id_model','=','nt_model.id_model')
                     ->where('nt_oqa_lab.save_date','>=',$data->startdate)
                     ->where('nt_oqa_lab.save_date','<=',$data->enddate)
                     ->where('nt_model.id_model','=',$data->id_model)
                     ->select('nt_oqa_lab.save_date as save_datest')
                     ->orderBy('nt_oqa_lab.save_date', 'DESC')->first();
                      if($datachkl == null)
                      {
                          $lastdate = '';
                      }
                      else
                      {
                          $lastdate = $datachkl->save_datest;
                      }
                    }            
                    $data = DB::table('nt_data_lpc')
                    ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_lpc.id_createlab')
                    ->where('nt_oqa_lab.save_date','>=',$firstdate)
                    ->where('nt_oqa_lab.save_date','<=',$lastdate)
                    ->where('nt_oqa_lab.id_model',$data->id_model)->avg('lpc_data');
                    $value = number_format($data, 2, '.', '');
                    return $value;
                 })
                 ->addColumn('max',function($data){ 
                    if($data->number == 0)
                    {           
                        $firstdate = $data->startdate;
                        $lastdate = $data->enddate;
                    }
                    else
                    {          
                    $datachkf = DB::table('nt_model')
                    ->join('nt_oqa_lab','nt_oqa_lab.id_model','=','nt_model.id_model')
                    ->where('nt_oqa_lab.save_date','>=',$data->startdate)
                    ->where('nt_oqa_lab.save_date','<=',$data->enddate)
                    ->where('nt_model.id_model','=',$data->id_model)
                    ->select('nt_oqa_lab.save_date as save_datest')
                    ->orderBy('nt_oqa_lab.save_date', 'DESC')->first();
                     if($datachkf == null)
                     {
                         $firstdate = '';
                     }
                     else
                     {
                         $firstdate = $dateend = date('Y-m-d 00:00:00', strtotime($datachkf->save_datest. ' -'.$data->number.' day')); 
                     }

                     $datachkl = DB::table('nt_model')
                     ->join('nt_oqa_lab','nt_oqa_lab.id_model','=','nt_model.id_model')
                     ->where('nt_oqa_lab.save_date','>=',$data->startdate)
                     ->where('nt_oqa_lab.save_date','<=',$data->enddate)
                     ->where('nt_model.id_model','=',$data->id_model)
                     ->select('nt_oqa_lab.save_date as save_datest')
                     ->orderBy('nt_oqa_lab.save_date', 'DESC')->first();
                      if($datachkl == null)
                      {
                          $lastdate = '';
                      }
                      else
                      {
                          $lastdate = $datachkl->save_datest;
                      }
                    }           
                    $data = DB::table('nt_data_lpc')
                    ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_lpc.id_createlab')
                    ->where('nt_oqa_lab.save_date','>=',$firstdate)
                    ->where('nt_oqa_lab.save_date','<=',$lastdate)
                    ->where('nt_oqa_lab.id_model',$data->id_model)->max('lpc_data');
                    $value = number_format($data, 2, '.', '');
                    return $value;
                 })
                 ->addColumn('min',function($data){ 
                    if($data->number == 0)
                    {           
                        $firstdate = $data->startdate;
                        $lastdate = $data->enddate;
                    }
                    else
                    {          
                    $datachkf = DB::table('nt_model')
                    ->join('nt_oqa_lab','nt_oqa_lab.id_model','=','nt_model.id_model')
                    ->where('nt_oqa_lab.save_date','>=',$data->startdate)
                    ->where('nt_oqa_lab.save_date','<=',$data->enddate)
                    ->where('nt_model.id_model','=',$data->id_model)
                    ->select('nt_oqa_lab.save_date as save_datest')
                    ->orderBy('nt_oqa_lab.save_date', 'DESC')->first();
                     if($datachkf == null)
                     {
                         $firstdate = '';
                     }
                     else
                     {
                         $firstdate = $dateend = date('Y-m-d 00:00:00', strtotime($datachkf->save_datest. ' -'.$data->number.' day')); 
                     }

                     $datachkl = DB::table('nt_model')
                     ->join('nt_oqa_lab','nt_oqa_lab.id_model','=','nt_model.id_model')
                     ->where('nt_oqa_lab.save_date','>=',$data->startdate)
                     ->where('nt_oqa_lab.save_date','<=',$data->enddate)
                     ->where('nt_model.id_model','=',$data->id_model)
                     ->select('nt_oqa_lab.save_date as save_datest')
                     ->orderBy('nt_oqa_lab.save_date', 'DESC')->first();
                      if($datachkl == null)
                      {
                          $lastdate = '';
                      }
                      else
                      {
                          $lastdate = $datachkl->save_datest;
                      }
                    }           
                    $data = DB::table('nt_data_lpc')
                    ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_lpc.id_createlab')
                    ->where('nt_oqa_lab.save_date','>=',$firstdate)
                    ->where('nt_oqa_lab.save_date','<=',$lastdate)
                    ->where('nt_oqa_lab.id_model',$data->id_model)->min('lpc_data');
                    $value = number_format($data, 2, '.', '');
                    return $value;
                 })
                 ->addColumn('std',function($data){
                    if($data->number == 0)
                    {           
                        $firstdate = $data->startdate;
                        $lastdate = $data->enddate;
                    }
                    else
                    {          
                    $datachkf = DB::table('nt_model')
                    ->join('nt_oqa_lab','nt_oqa_lab.id_model','=','nt_model.id_model')
                    ->where('nt_oqa_lab.save_date','>=',$data->startdate)
                    ->where('nt_oqa_lab.save_date','<=',$data->enddate)
                    ->where('nt_model.id_model','=',$data->id_model)
                    ->select('nt_oqa_lab.save_date as save_datest')
                    ->orderBy('nt_oqa_lab.save_date', 'DESC')->first();
                     if($datachkf == null)
                     {
                         $firstdate = '';
                     }
                     else
                     {
                         $firstdate = $dateend = date('Y-m-d 00:00:00', strtotime($datachkf->save_datest. ' -'.$data->number.' day')); 
                     }

                     $datachkl = DB::table('nt_model')
                     ->join('nt_oqa_lab','nt_oqa_lab.id_model','=','nt_model.id_model')
                     ->where('nt_oqa_lab.save_date','>=',$data->startdate)
                     ->where('nt_oqa_lab.save_date','<=',$data->enddate)
                     ->where('nt_model.id_model','=',$data->id_model)
                     ->select('nt_oqa_lab.save_date as save_datest')
                     ->orderBy('nt_oqa_lab.save_date', 'DESC')->first();
                      if($datachkl == null)
                      {
                          $lastdate = '';
                      }
                      else
                      {
                          $lastdate = $datachkl->save_datest;
                      }
                    }            
                    $data = DB::table('nt_data_lpc')
                    ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_lpc.id_createlab')
                    ->where('nt_oqa_lab.save_date','>=',$firstdate)
                    ->where('nt_oqa_lab.save_date','<=',$lastdate)
                    ->where('nt_oqa_lab.id_model',$data->id_model);
                    $std = $data->select(DB::raw('STDDEV_SAMP(nt_data_lpc.lpc_data) as std'))->first();
                    $value = number_format($std->std, 2, '.', '');
                    return $value;
                 })
                 ->addColumn('mean3',function($data){ 
                    if($data->number == 0)
                    {           
                        $firstdate = $data->startdate;
                        $lastdate = $data->enddate;
                    }
                    else
                    {          
                    $datachkf = DB::table('nt_model')
                    ->join('nt_oqa_lab','nt_oqa_lab.id_model','=','nt_model.id_model')
                    ->where('nt_oqa_lab.save_date','>=',$data->startdate)
                    ->where('nt_oqa_lab.save_date','<=',$data->enddate)
                    ->where('nt_model.id_model','=',$data->id_model)
                    ->select('nt_oqa_lab.save_date as save_datest')
                    ->orderBy('nt_oqa_lab.save_date', 'DESC')->first();
                     if($datachkf == null)
                     {
                         $firstdate = '';
                     }
                     else
                     {
                         $firstdate = $dateend = date('Y-m-d 00:00:00', strtotime($datachkf->save_datest. ' -'.$data->number.' day')); 
                     }

                     $datachkl = DB::table('nt_model')
                     ->join('nt_oqa_lab','nt_oqa_lab.id_model','=','nt_model.id_model')
                     ->where('nt_oqa_lab.save_date','>=',$data->startdate)
                     ->where('nt_oqa_lab.save_date','<=',$data->enddate)
                     ->where('nt_model.id_model','=',$data->id_model)
                     ->select('nt_oqa_lab.save_date as save_datest')
                     ->orderBy('nt_oqa_lab.save_date', 'DESC')->first();
                      if($datachkl == null)
                      {
                          $lastdate = '';
                      }
                      else
                      {
                          $lastdate = $datachkl->save_datest;
                      }
                    }           
                    $stdlist = DB::table('nt_data_lpc')
                    ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_lpc.id_createlab')
                    ->where('nt_oqa_lab.save_date','>=',$firstdate)
                    ->where('nt_oqa_lab.save_date','<=',$lastdate)
                    ->where('nt_oqa_lab.id_model',$data->id_model);
                    $std = $stdlist->select(DB::raw('STDDEV_SAMP(nt_data_lpc.lpc_data) as std'))->first();
                    $avg = DB::table('nt_data_lpc')
                    ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_lpc.id_createlab')
                    ->where('nt_oqa_lab.save_date','>=',$firstdate)
                    ->where('nt_oqa_lab.save_date','<=',$lastdate)
                    ->where('nt_oqa_lab.id_model',$data->id_model)->avg('lpc_data');
                    $data = $avg - (3*$std->std);
                    $value = number_format($data, 2, '.', '');
                    return $value;
                 })
                 ->addColumn('mean4',function($data){ 
                    if($data->number == 0)
                    {           
                        $firstdate = $data->startdate;
                        $lastdate = $data->enddate;
                    }
                    else
                    {          
                    $datachkf = DB::table('nt_model')
                    ->join('nt_oqa_lab','nt_oqa_lab.id_model','=','nt_model.id_model')
                    ->where('nt_oqa_lab.save_date','>=',$data->startdate)
                    ->where('nt_oqa_lab.save_date','<=',$data->enddate)
                    ->where('nt_model.id_model','=',$data->id_model)
                    ->select('nt_oqa_lab.save_date as save_datest')
                    ->orderBy('nt_oqa_lab.save_date', 'DESC')->first();
                     if($datachkf == null)
                     {
                         $firstdate = '';
                     }
                     else
                     {
                         $firstdate = $dateend = date('Y-m-d 00:00:00', strtotime($datachkf->save_datest. ' -'.$data->number.' day')); 
                     }

                     $datachkl = DB::table('nt_model')
                     ->join('nt_oqa_lab','nt_oqa_lab.id_model','=','nt_model.id_model')
                     ->where('nt_oqa_lab.save_date','>=',$data->startdate)
                     ->where('nt_oqa_lab.save_date','<=',$data->enddate)
                     ->where('nt_model.id_model','=',$data->id_model)
                     ->select('nt_oqa_lab.save_date as save_datest')
                     ->orderBy('nt_oqa_lab.save_date', 'DESC')->first();
                      if($datachkl == null)
                      {
                          $lastdate = '';
                      }
                      else
                      {
                          $lastdate = $datachkl->save_datest;
                      }
                    }          
                    $stdlist = DB::table('nt_data_lpc')
                    ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_lpc.id_createlab')
                    ->where('nt_oqa_lab.save_date','>=',$firstdate)
                    ->where('nt_oqa_lab.save_date','<=',$lastdate)
                    ->where('nt_oqa_lab.id_model',$data->id_model);
                    $std = $stdlist->select(DB::raw('STDDEV_SAMP(nt_data_lpc.lpc_data) as std'))->first();
                    $avg = DB::table('nt_data_lpc')
                    ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_lpc.id_createlab')
                    ->where('nt_oqa_lab.save_date','>=',$firstdate)
                    ->where('nt_oqa_lab.save_date','<=',$lastdate)
                    ->where('nt_oqa_lab.id_model',$data->id_model)->avg('lpc_data');
                    $data = $avg - (4*$std->std);
                    $value = number_format($data, 2, '.', '');
                    return $value;
                 })
                 ->addColumn('mean4_5',function($data){ 
                    if($data->number == 0)
                    {           
                        $firstdate = $data->startdate;
                        $lastdate = $data->enddate;
                    }
                    else
                    {          
                    $datachkf = DB::table('nt_model')
                    ->join('nt_oqa_lab','nt_oqa_lab.id_model','=','nt_model.id_model')
                    ->where('nt_oqa_lab.save_date','>=',$data->startdate)
                    ->where('nt_oqa_lab.save_date','<=',$data->enddate)
                    ->where('nt_model.id_model','=',$data->id_model)
                    ->select('nt_oqa_lab.save_date as save_datest')
                    ->orderBy('nt_oqa_lab.save_date', 'DESC')->first();
                     if($datachkf == null)
                     {
                         $firstdate = '';
                     }
                     else
                     {
                         $firstdate = $dateend = date('Y-m-d 00:00:00', strtotime($datachkf->save_datest. ' -'.$data->number.' day')); 
                     }

                     $datachkl = DB::table('nt_model')
                     ->join('nt_oqa_lab','nt_oqa_lab.id_model','=','nt_model.id_model')
                     ->where('nt_oqa_lab.save_date','>=',$data->startdate)
                     ->where('nt_oqa_lab.save_date','<=',$data->enddate)
                     ->where('nt_model.id_model','=',$data->id_model)
                     ->select('nt_oqa_lab.save_date as save_datest')
                     ->orderBy('nt_oqa_lab.save_date', 'DESC')->first();
                      if($datachkl == null)
                      {
                          $lastdate = '';
                      }
                      else
                      {
                          $lastdate = $datachkl->save_datest;
                      }
                    }           
                    $stdlist = DB::table('nt_data_lpc')
                    ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_lpc.id_createlab')
                    ->where('nt_oqa_lab.save_date','>=',$firstdate)
                    ->where('nt_oqa_lab.save_date','<=',$lastdate)
                    ->where('nt_oqa_lab.id_model',$data->id_model);
                    $std = $stdlist->select(DB::raw('STDDEV_SAMP(nt_data_lpc.lpc_data) as std'))->first();
                    $avg = DB::table('nt_data_lpc')
                    ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_lpc.id_createlab')
                    ->where('nt_oqa_lab.save_date','>=',$firstdate)
                    ->where('nt_oqa_lab.save_date','<=',$lastdate)
                    ->where('nt_oqa_lab.id_model',$data->id_model)->avg('lpc_data');
                    $data = $avg - (4.5*$std->std);
                    $value = number_format($data, 2, '.', '');
                    return $value;
                 })
                 ->addColumn('mean6',function($data){  
                    if($data->number == 0)
                    {           
                        $firstdate = $data->startdate;
                        $lastdate = $data->enddate;
                    }
                    else
                    {          
                    $datachkf = DB::table('nt_model')
                    ->join('nt_oqa_lab','nt_oqa_lab.id_model','=','nt_model.id_model')
                    ->where('nt_oqa_lab.save_date','>=',$data->startdate)
                    ->where('nt_oqa_lab.save_date','<=',$data->enddate)
                    ->where('nt_model.id_model','=',$data->id_model)
                    ->select('nt_oqa_lab.save_date as save_datest')
                    ->orderBy('nt_oqa_lab.save_date', 'DESC')->first();
                     if($datachkf == null)
                     {
                         $firstdate = '';
                     }
                     else
                     {
                         $firstdate = $dateend = date('Y-m-d 00:00:00', strtotime($datachkf->save_datest. ' -'.$data->number.' day')); 
                     }

                     $datachkl = DB::table('nt_model')
                     ->join('nt_oqa_lab','nt_oqa_lab.id_model','=','nt_model.id_model')
                     ->where('nt_oqa_lab.save_date','>=',$data->startdate)
                     ->where('nt_oqa_lab.save_date','<=',$data->enddate)
                     ->where('nt_model.id_model','=',$data->id_model)
                     ->select('nt_oqa_lab.save_date as save_datest')
                     ->orderBy('nt_oqa_lab.save_date', 'DESC')->first();
                      if($datachkl == null)
                      {
                          $lastdate = '';
                      }
                      else
                      {
                          $lastdate = $datachkl->save_datest;
                      }
                    }          
                    $stdlist = DB::table('nt_data_lpc')
                    ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_lpc.id_createlab')
                    ->where('nt_oqa_lab.save_date','>=',$firstdate)
                    ->where('nt_oqa_lab.save_date','<=',$lastdate)
                    ->where('nt_oqa_lab.id_model',$data->id_model);
                    $std = $stdlist->select(DB::raw('STDDEV_SAMP(nt_data_lpc.lpc_data) as std'))->first();
                    $avg = DB::table('nt_data_lpc')
                    ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_lpc.id_createlab')
                    ->where('nt_oqa_lab.save_date','>=',$firstdate)
                    ->where('nt_oqa_lab.save_date','<=',$lastdate)
                    ->where('nt_oqa_lab.id_model',$data->id_model)->avg('lpc_data');
                    $data = $avg - (6*$std->std);
                    $value = number_format($data, 2, '.', '');
                    return $value;
                 });

            }
            return $sQuery->escapeColumns([])->make(true);
    }

    public function lpccalculationexport(Request $request)	
    {
        ini_set('memory_limit', '-1');
        $chkexport = [];
        if(isset($request->id))
        {
            if(count($request->id) == 2)
             {
             $a1 = DB::table('nt_model')->where('id_model', $request->id[0])->first();
             $a2 = DB::table('nt_model')->where('id_model', $request->id[1])->first();
             $text1 = $a1->name_model;
             $text2 = $a2->name_model;
             $chktext1 = mb_substr($text1,0,-3);
             $chktext2 = mb_substr($text2,0,-3);
                 if($chktext1 == $chktext2)
                 {
                    if($a1->part == "UP")
                    {
                        $model1 = $a1;
                    }
                    else
                    {
                        $model1 = $a2;
                    }
                    if($a2->part == "DN")
                    {
                        $model2 = $a2;
                    }
                    else
                    {
                        $model2 = $a1;
                    }
                    $modelid1 = $model1->id_model;
                    $modelid2 = $model2->id_model;
                    $datestart1 = date('Y-m-d 00:00:00', strtotime($request->input('datestart'.$model1->id_model)));
                    $datestart2= date('Y-m-d 00:00:00', strtotime($request->input('datestart'.$model2->id_model)));
                    $dateend1 = date('Y-m-d 00:00:00', strtotime($request->input('dateend'.$model1->id_model). ' + 1 day'));
                    $dateend2 = date('Y-m-d 00:00:00', strtotime($request->input('dateend'.$model2->id_model). ' + 1 day'));
                    $dataexport = [];
                    $newdata =  array (
                        'modelid' => $modelid1,
                        'datestart' => $datestart1,
                        'dateend' => $dateend1,
                    );
                    array_push($dataexport,$newdata);
                    $newdata =  array (
                        'modelid' => $modelid2,
                        'datestart' => $datestart2,
                        'dateend' => $dateend2,
                    );
                    array_push($dataexport,$newdata);
                
                 }
                 else
                 {
                 return response()->json(['success' => 2]);
                 }
             }
             else
             {
             return response()->json(['success' => 3]);
             }
         }
        else
        {
        return response()->json(['success' => 4]);
        }
        $namefile = 'Calculation_'.date("YmdHis");
        $file = storage_path('NHK-File/template/report/template_calculation.xlsx');
        Excel::load($file, function ($doc)use($dataexport) {
           
                $i=5;
                $chklpccount = 0;
                $chksemcount = 0;
                $chkiccount = 0;
                $chkgcmscount = 0;
                $chkftircount = 0;
                foreach ($dataexport as $item) {  
                $model = DB::table('nt_model')
                ->join('nt_model_spec','nt_model_spec.id_model','=','nt_model.id_model')
                ->where('nt_model.id_model', $item['modelid'])
                ->where('nt_model_spec.id_model_type', 1)
                ->first();
                
                $lpccount = DB::table('nt_data_lpc')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_lpc.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->count();
                $chklpccount = $chklpccount +$lpccount;
                $maxdata = DB::table('nt_data_lpc')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_lpc.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->max('lpc_data');
                $max = number_format($maxdata, 2, '.', '');
               
                $mindata = DB::table('nt_data_lpc')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_lpc.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->min('lpc_data');
                $min = number_format($mindata, 2, '.', '');

                $meandata = DB::table('nt_data_lpc')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_lpc.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->avg('lpc_data');
                $mean = number_format($meandata, 2, '.', '');
   
                $stddata = DB::table('nt_data_lpc')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_lpc.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid']);
                $stdget = $stddata->select(DB::raw('STDDEV_SAMP(nt_data_lpc.lpc_data) as std'))->first();
                $std = number_format($stdget->std, 2, '.', '');   
                $mean6data = $meandata + (6*$stdget->std);
                $mean6 = number_format($mean6data, 2, '.', '');
                $sheet = $doc->setActiveSheetIndex(0);
                $sheet->setCellValue('A'.$i,$model->name_model);
                $sheet->setCellValue('B'.$i,$model->part);
                $sheet->setCellValue('C'.$i,$model->part_number);
                $sheet->setCellValue('D'.$i,$lpccount);
                $sheet->setCellValue('E'.$i,$max);
                $sheet->setCellValue('F'.$i,$min);
                $sheet->setCellValue('G'.$i,$mean);
                $sheet->setCellValue('H'.$i,$std);
                $sheet->setCellValue('I'.$i,$item['datestart']);
                $sheet->setCellValue('J'.$i,$item['dateend']);
                $sheet->setCellValue('P'.$i,$mean6);
                $i++;
                
            }
            
                $doc->setActiveSheetIndexByName('SEM C 1');
                $sheetsem1 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetsem1);

                $doc->setActiveSheetIndexByName('SEM C 2');
                $sheetsem2 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetsem2);

                $doc->setActiveSheetIndexByName('SEM C 3');
                $sheetsem3 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetsem3);

                $doc->setActiveSheetIndexByName('SEM C 3.1');
                $sheetsem4 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetsem4);

                $doc->setActiveSheetIndexByName('SEM C 3.2');
                $sheetsem5 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetsem5);

                $doc->setActiveSheetIndexByName('SEM C 3.3');
                $sheetsem6 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetsem6);

                $doc->setActiveSheetIndexByName('SEM C 4');
                $sheetsem7 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetsem7);

                $doc->setActiveSheetIndexByName('SEM C 5');
                $sheetsem8 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetsem8);

                $doc->setActiveSheetIndexByName('SEM C 5.1');
                $sheetsem9 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetsem9);

                $doc->setActiveSheetIndexByName('SEM C 6');
                $sheetsem10 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetsem10);

                $doc->setActiveSheetIndexByName('Anion');
                $sheetic11 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetic11);

                $doc->setActiveSheetIndexByName('Sulphate');
                $sheetic12 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetic12);

                $doc->setActiveSheetIndexByName('Cation');
                $sheetic13 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetic13);
     
                $doc->setActiveSheetIndexByName('Outgassing');
                $sheetgcms14 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetgcms14);

                $doc->setActiveSheetIndexByName('Low MW');
                $sheetgcms15 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetgcms15);

                $doc->setActiveSheetIndexByName('High MW');
                $sheetgcms16 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetgcms16);

                $doc->setActiveSheetIndexByName('Smear');
                $sheetgcms17 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetgcms17);
         
                $doc->setActiveSheetIndexByName('FTIR');
            $sheetftir18 = $doc->getActiveSheetIndex(0);
            $doc->removeSheetByIndex($sheetftir18);
            

           })->setFilename($namefile)->store('xlsx', storage_path('NHK-File/report/calculation'));
        return response()->json(['success' =>  1,'filename' => $namefile.'.xlsx']);
    }

    public function semcalculationexport(Request $request)	
    {
        ini_set('memory_limit', '-1');
        $chkexport = [];
        if(isset($request->id))
        {
            if(count($request->id) == 2)
             {
             $a1 = DB::table('nt_model')->where('id_model', $request->id[0])->first();
             $a2 = DB::table('nt_model')->where('id_model', $request->id[1])->first();
             $text1 = $a1->name_model;
             $text2 = $a2->name_model;
             $chktext1 = mb_substr($text1,0,-3);
             $chktext2 = mb_substr($text2,0,-3);
                 if($chktext1 == $chktext2)
                 {
                    if($a1->part == "UP")
                    {
                        $model1 = $a1;
                    }
                    else
                    {
                        $model1 = $a2;
                    }
                    if($a2->part == "DN")
                    {
                        $model2 = $a2;
                    }
                    else
                    {
                        $model2 = $a1;
                    }
                    $modelid1 = $model1->id_model;
                    $modelid2 = $model2->id_model;
                    $datestart1 = date('Y-m-d 00:00:00', strtotime($request->input('datestart'.$model1->id_model)));
                    $datestart2= date('Y-m-d 00:00:00', strtotime($request->input('datestart'.$model2->id_model)));
                    $dateend1 = date('Y-m-d 00:00:00', strtotime($request->input('dateend'.$model1->id_model). ' + 1 day'));
                    $dateend2 = date('Y-m-d 00:00:00', strtotime($request->input('dateend'.$model2->id_model). ' + 1 day'));
                    $dataexport = [];
                    $newdata =  array (
                        'modelid' => $modelid1,
                        'datestart' => $datestart1,
                        'dateend' => $dateend1,
                    );
                    array_push($dataexport,$newdata);
                    $newdata =  array (
                        'modelid' => $modelid2,
                        'datestart' => $datestart2,
                        'dateend' => $dateend2,
                    );
                    array_push($dataexport,$newdata);
                
                 }
                 else
                 {
                 return response()->json(['success' => 2]);
                 }
             }
             else
             {
             return response()->json(['success' => 3]);
             }
         }
        else
        {
        return response()->json(['success' => 4]);
        }
        $namefile = 'Calculation_'.date("YmdHis");
        $file = storage_path('NHK-File/template/report/template_calculation.xlsx');
        Excel::load($file, function ($doc)use($dataexport) {
           
                $i=5;
                $chklpccount = 0;
                $chksemcount = 0;
                $chkiccount = 0;
                $chkgcmscount = 0;
                $chkftircount = 0;
                foreach ($dataexport as $item) {  
                $model = DB::table('nt_model')
                ->join('nt_model_spec','nt_model_spec.id_model','=','nt_model.id_model')
                ->where('nt_model.id_model', $item['modelid'])
                ->where('nt_model_spec.id_model_type', 1)
                ->first();
                
                $count = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->count();
                $chksemcount = $chksemcount + $count;
                $maxdata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->max('sem_class1');
                $max = number_format($maxdata, 2, '.', '');
               
                $mindata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->min('sem_class1');
                $min = number_format($mindata, 2, '.', '');

                $meandata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->avg('sem_class1');
                $mean = number_format($meandata, 2, '.', '');
   
                $stddata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid']);
                $stdget = $stddata->select(DB::raw('STDDEV_SAMP(nt_data_sem.sem_class1) as std'))->first();
                $std = number_format($stdget->std, 2, '.', '');   
                $mean6data = $meandata + (6*$stdget->std);
                $mean6 = number_format($mean6data, 2, '.', '');
                $sheet = $doc->setActiveSheetIndex(1);
                $sheet->setCellValue('A'.$i,$model->name_model);
                $sheet->setCellValue('B'.$i,$model->part);
                $sheet->setCellValue('C'.$i,$model->part_number);
                $sheet->setCellValue('D'.$i,$count);
                $sheet->setCellValue('E'.$i,$max);
                $sheet->setCellValue('F'.$i,$min);
                $sheet->setCellValue('G'.$i,$mean);
                $sheet->setCellValue('H'.$i,$std);
                $sheet->setCellValue('I'.$i,$item['datestart']);
                $sheet->setCellValue('J'.$i,$item['dateend']);
                $sheet->setCellValue('P'.$i,$mean6);

                
                $maxdata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->max('sem_class2');
                $max = number_format($maxdata, 2, '.', '');
               
                $mindata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->min('sem_class2');
                $min = number_format($mindata, 2, '.', '');

                $meandata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->avg('sem_class2');
                $mean = number_format($meandata, 2, '.', '');
   
                $stddata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid']);
                $stdget = $stddata->select(DB::raw('STDDEV_SAMP(nt_data_sem.sem_class2) as std'))->first();
                $std = number_format($stdget->std, 2, '.', '');   
                $mean6data = $meandata + (6*$stdget->std);
                $mean6 = number_format($mean6data, 2, '.', '');
                $sheet = $doc->setActiveSheetIndex(2);
                $sheet->setCellValue('A'.$i,$model->name_model);
                $sheet->setCellValue('B'.$i,$model->part);
                $sheet->setCellValue('C'.$i,$model->part_number);
                $sheet->setCellValue('D'.$i,$count);
                $sheet->setCellValue('E'.$i,$max);
                $sheet->setCellValue('F'.$i,$min);
                $sheet->setCellValue('G'.$i,$mean);
                $sheet->setCellValue('H'.$i,$std);
                $sheet->setCellValue('I'.$i,$item['datestart']);
                $sheet->setCellValue('J'.$i,$item['dateend']);
                $sheet->setCellValue('P'.$i,$mean6);
               

                
                $maxdata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->max('sem_class3');
                $max = number_format($maxdata, 2, '.', '');
               
                $mindata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->min('sem_class3');
                $min = number_format($mindata, 2, '.', '');

                $meandata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->avg('sem_class3');
                $mean = number_format($meandata, 2, '.', '');
   
                $stddata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid']);
                $stdget = $stddata->select(DB::raw('STDDEV_SAMP(nt_data_sem.sem_class3) as std'))->first();
                $std = number_format($stdget->std, 2, '.', '');   
                $mean6data = $meandata + (6*$stdget->std);
                $mean6 = number_format($mean6data, 2, '.', '');
                $sheet = $doc->setActiveSheetIndex(3);
                $sheet->setCellValue('A'.$i,$model->name_model);
                $sheet->setCellValue('B'.$i,$model->part);
                $sheet->setCellValue('C'.$i,$model->part_number);
                $sheet->setCellValue('D'.$i,$count);
                $sheet->setCellValue('E'.$i,$max);
                $sheet->setCellValue('F'.$i,$min);
                $sheet->setCellValue('G'.$i,$mean);
                $sheet->setCellValue('H'.$i,$std);
                $sheet->setCellValue('I'.$i,$item['datestart']);
                $sheet->setCellValue('J'.$i,$item['dateend']);
                $sheet->setCellValue('P'.$i,$mean6);


                
                $maxdata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->max('sem_class3_1');
                $max = number_format($maxdata, 2, '.', '');
               
                $mindata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->min('sem_class3_1');
                $min = number_format($mindata, 2, '.', '');

                $meandata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->avg('sem_class3_1');
                $mean = number_format($meandata, 2, '.', '');
   
                $stddata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid']);
                $stdget = $stddata->select(DB::raw('STDDEV_SAMP(nt_data_sem.sem_class3_1) as std'))->first();
                $std = number_format($stdget->std, 2, '.', '');   
                $mean6data = $meandata + (6*$stdget->std);
                $mean6 = number_format($mean6data, 2, '.', '');
                $sheet = $doc->setActiveSheetIndex(4);
                $sheet->setCellValue('A'.$i,$model->name_model);
                $sheet->setCellValue('B'.$i,$model->part);
                $sheet->setCellValue('C'.$i,$model->part_number);
                $sheet->setCellValue('D'.$i,$count);
                $sheet->setCellValue('E'.$i,$max);
                $sheet->setCellValue('F'.$i,$min);
                $sheet->setCellValue('G'.$i,$mean);
                $sheet->setCellValue('H'.$i,$std);
                $sheet->setCellValue('I'.$i,$item['datestart']);
                $sheet->setCellValue('J'.$i,$item['dateend']);
                $sheet->setCellValue('P'.$i,$mean6);


                
                $maxdata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->max('sem_class3_2');
                $max = number_format($maxdata, 2, '.', '');
               
                $mindata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->min('sem_class3_2');
                $min = number_format($mindata, 2, '.', '');

                $meandata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->avg('sem_class3_2');
                $mean = number_format($meandata, 2, '.', '');
   
                $stddata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid']);
                $stdget = $stddata->select(DB::raw('STDDEV_SAMP(nt_data_sem.sem_class3_2) as std'))->first();
                $std = number_format($stdget->std, 2, '.', '');   
                $mean6data = $meandata + (6*$stdget->std);
                $mean6 = number_format($mean6data, 2, '.', '');
                $sheet = $doc->setActiveSheetIndex(5);
                $sheet->setCellValue('A'.$i,$model->name_model);
                $sheet->setCellValue('B'.$i,$model->part);
                $sheet->setCellValue('C'.$i,$model->part_number);
                $sheet->setCellValue('D'.$i,$count);
                $sheet->setCellValue('E'.$i,$max);
                $sheet->setCellValue('F'.$i,$min);
                $sheet->setCellValue('G'.$i,$mean);
                $sheet->setCellValue('H'.$i,$std);
                $sheet->setCellValue('I'.$i,$item['datestart']);
                $sheet->setCellValue('J'.$i,$item['dateend']);
                $sheet->setCellValue('P'.$i,$mean6);


                
                $maxdata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->max('sem_class3_3');
                $max = number_format($maxdata, 2, '.', '');
               
                $mindata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->min('sem_class3_3');
                $min = number_format($mindata, 2, '.', '');

                $meandata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->avg('sem_class3_3');
                $mean = number_format($meandata, 2, '.', '');
   
                $stddata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid']);
                $stdget = $stddata->select(DB::raw('STDDEV_SAMP(nt_data_sem.sem_class3_3) as std'))->first();
                $std = number_format($stdget->std, 2, '.', '');   
                $mean6data = $meandata + (6*$stdget->std);
                $mean6 = number_format($mean6data, 2, '.', '');
                $sheet = $doc->setActiveSheetIndex(6);
                $sheet->setCellValue('A'.$i,$model->name_model);
                $sheet->setCellValue('B'.$i,$model->part);
                $sheet->setCellValue('C'.$i,$model->part_number);
                $sheet->setCellValue('D'.$i,$count);
                $sheet->setCellValue('E'.$i,$max);
                $sheet->setCellValue('F'.$i,$min);
                $sheet->setCellValue('G'.$i,$mean);
                $sheet->setCellValue('H'.$i,$std);
                $sheet->setCellValue('I'.$i,$item['datestart']);
                $sheet->setCellValue('J'.$i,$item['dateend']);
                $sheet->setCellValue('P'.$i,$mean6);

                
                $maxdata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->max('sem_class4');
                $max = number_format($maxdata, 2, '.', '');
               
                $mindata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->min('sem_class4');
                $min = number_format($mindata, 2, '.', '');

                $meandata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->avg('sem_class4');
                $mean = number_format($meandata, 2, '.', '');
   
                $stddata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid']);
                $stdget = $stddata->select(DB::raw('STDDEV_SAMP(nt_data_sem.sem_class4) as std'))->first();
                $std = number_format($stdget->std, 2, '.', '');   
                $mean6data = $meandata + (6*$stdget->std);
                $mean6 = number_format($mean6data, 2, '.', '');
                $sheet = $doc->setActiveSheetIndex(7);
                $sheet->setCellValue('A'.$i,$model->name_model);
                $sheet->setCellValue('B'.$i,$model->part);
                $sheet->setCellValue('C'.$i,$model->part_number);
                $sheet->setCellValue('D'.$i,$count);
                $sheet->setCellValue('E'.$i,$max);
                $sheet->setCellValue('F'.$i,$min);
                $sheet->setCellValue('G'.$i,$mean);
                $sheet->setCellValue('H'.$i,$std);
                $sheet->setCellValue('I'.$i,$item['datestart']);
                $sheet->setCellValue('J'.$i,$item['dateend']);
                $sheet->setCellValue('P'.$i,$mean6);

                
                $maxdata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->max('sem_class5');
                $max = number_format($maxdata, 2, '.', '');
               
                $mindata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->min('sem_class5');
                $min = number_format($mindata, 2, '.', '');

                $meandata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->avg('sem_class5');
                $mean = number_format($meandata, 2, '.', '');
   
                $stddata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid']);
                $stdget = $stddata->select(DB::raw('STDDEV_SAMP(nt_data_sem.sem_class5) as std'))->first();
                $std = number_format($stdget->std, 2, '.', '');   
                $mean6data = $meandata + (6*$stdget->std);
                $mean6 = number_format($mean6data, 2, '.', '');
                $sheet = $doc->setActiveSheetIndex(8);
                $sheet->setCellValue('A'.$i,$model->name_model);
                $sheet->setCellValue('B'.$i,$model->part);
                $sheet->setCellValue('C'.$i,$model->part_number);
                $sheet->setCellValue('D'.$i,$count);
                $sheet->setCellValue('E'.$i,$max);
                $sheet->setCellValue('F'.$i,$min);
                $sheet->setCellValue('G'.$i,$mean);
                $sheet->setCellValue('H'.$i,$std);
                $sheet->setCellValue('I'.$i,$item['datestart']);
                $sheet->setCellValue('J'.$i,$item['dateend']);
                $sheet->setCellValue('P'.$i,$mean6);

                $maxdata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->max('sem_class5_1');
                $max = number_format($maxdata, 2, '.', '');
               
                $mindata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->min('sem_class5_1');
                $min = number_format($mindata, 2, '.', '');

                $meandata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->avg('sem_class5_1');
                $mean = number_format($meandata, 2, '.', '');
   
                $stddata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid']);
                $stdget = $stddata->select(DB::raw('STDDEV_SAMP(nt_data_sem.sem_class5_1) as std'))->first();
                $std = number_format($stdget->std, 2, '.', '');   
                $mean6data = $meandata + (6*$stdget->std);
                $mean6 = number_format($mean6data, 2, '.', '');
                $sheet = $doc->setActiveSheetIndex(9);
                $sheet->setCellValue('A'.$i,$model->name_model);
                $sheet->setCellValue('B'.$i,$model->part);
                $sheet->setCellValue('C'.$i,$model->part_number);
                $sheet->setCellValue('D'.$i,$count);
                $sheet->setCellValue('E'.$i,$max);
                $sheet->setCellValue('F'.$i,$min);
                $sheet->setCellValue('G'.$i,$mean);
                $sheet->setCellValue('H'.$i,$std);
                $sheet->setCellValue('I'.$i,$item['datestart']);
                $sheet->setCellValue('J'.$i,$item['dateend']);
                $sheet->setCellValue('P'.$i,$mean6);


                
                $maxdata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->max('sem_class6');
                $max = number_format($maxdata, 2, '.', '');
               
                $mindata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->min('sem_class6');
                $min = number_format($mindata, 2, '.', '');

                $meandata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->avg('sem_class6');
                $mean = number_format($meandata, 2, '.', '');
   
                $stddata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid']);
                $stdget = $stddata->select(DB::raw('STDDEV_SAMP(nt_data_sem.sem_class6) as std'))->first();
                $std = number_format($stdget->std, 2, '.', '');   
                $mean6data = $meandata + (6*$stdget->std);
                $mean6 = number_format($mean6data, 2, '.', '');
                $sheet = $doc->setActiveSheetIndex(10);
                $sheet->setCellValue('A'.$i,$model->name_model);
                $sheet->setCellValue('B'.$i,$model->part);
                $sheet->setCellValue('C'.$i,$model->part_number);
                $sheet->setCellValue('D'.$i,$count);
                $sheet->setCellValue('E'.$i,$max);
                $sheet->setCellValue('F'.$i,$min);
                $sheet->setCellValue('G'.$i,$mean);
                $sheet->setCellValue('H'.$i,$std);
                $sheet->setCellValue('I'.$i,$item['datestart']);
                $sheet->setCellValue('J'.$i,$item['dateend']);
                $sheet->setCellValue('P'.$i,$mean6);
                $i++;
                
            }
            
            
            $doc->setActiveSheetIndexByName('ALPC');
            $sheetlpc0 = $doc->getActiveSheetIndex();
            $doc->removeSheetByIndex($sheetlpc0);
           
                $doc->setActiveSheetIndexByName('Anion');
                $sheetic11 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetic11);

                $doc->setActiveSheetIndexByName('Sulphate');
                $sheetic12 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetic12);

                $doc->setActiveSheetIndexByName('Cation');
                $sheetic13 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetic13);
          
                $doc->setActiveSheetIndexByName('Outgassing');
                $sheetgcms14 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetgcms14);

                $doc->setActiveSheetIndexByName('Low MW');
                $sheetgcms15 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetgcms15);

                $doc->setActiveSheetIndexByName('High MW');
                $sheetgcms16 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetgcms16);

                $doc->setActiveSheetIndexByName('Smear');
                $sheetgcms17 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetgcms17);
           
                $doc->setActiveSheetIndexByName('FTIR');
            $sheetftir18 = $doc->getActiveSheetIndex(0);
            $doc->removeSheetByIndex($sheetftir18);
            

           })->setFilename($namefile)->store('xlsx', storage_path('NHK-File/report/calculation'));
        return response()->json(['success' =>  1,'filename' => $namefile.'.xlsx']);


    }

    public function iccalculationexport(Request $request)	
    {
        ini_set('memory_limit', '-1');
        $chkexport = [];
        if(isset($request->id))
        {
            if(count($request->id) == 2)
             {
             $a1 = DB::table('nt_model')->where('id_model', $request->id[0])->first();
             $a2 = DB::table('nt_model')->where('id_model', $request->id[1])->first();
             $text1 = $a1->name_model;
             $text2 = $a2->name_model;
             $chktext1 = mb_substr($text1,0,-3);
             $chktext2 = mb_substr($text2,0,-3);
                 if($chktext1 == $chktext2)
                 {
                    if($a1->part == "UP")
                    {
                        $model1 = $a1;
                    }
                    else
                    {
                        $model1 = $a2;
                    }
                    if($a2->part == "DN")
                    {
                        $model2 = $a2;
                    }
                    else
                    {
                        $model2 = $a1;
                    }
                    $modelid1 = $model1->id_model;
                    $modelid2 = $model2->id_model;
                    $datestart1 = date('Y-m-d 00:00:00', strtotime($request->input('datestart'.$model1->id_model)));
                    $datestart2= date('Y-m-d 00:00:00', strtotime($request->input('datestart'.$model2->id_model)));
                    $dateend1 = date('Y-m-d 00:00:00', strtotime($request->input('dateend'.$model1->id_model). ' + 1 day'));
                    $dateend2 = date('Y-m-d 00:00:00', strtotime($request->input('dateend'.$model2->id_model). ' + 1 day'));
                    $dataexport = [];
                    $newdata =  array (
                        'modelid' => $modelid1,
                        'datestart' => $datestart1,
                        'dateend' => $dateend1,
                    );
                    array_push($dataexport,$newdata);
                    $newdata =  array (
                        'modelid' => $modelid2,
                        'datestart' => $datestart2,
                        'dateend' => $dateend2,
                    );
                    array_push($dataexport,$newdata);
                
                 }
                 else
                 {
                 return response()->json(['success' => 2]);
                 }
             }
             else
             {
             return response()->json(['success' => 3]);
             }
         }
        else
        {
        return response()->json(['success' => 4]);
        }
        $namefile = 'Calculation_'.date("YmdHis");
        $file = storage_path('NHK-File/template/report/template_calculation.xlsx');
        Excel::load($file, function ($doc)use($dataexport) {
           
                $i=5;
                $chklpccount = 0;
                $chksemcount = 0;
                $chkiccount = 0;
                $chkgcmscount = 0;
                $chkftircount = 0;
                foreach ($dataexport as $item) {  
                $model = DB::table('nt_model')
                ->join('nt_model_spec','nt_model_spec.id_model','=','nt_model.id_model')
                ->where('nt_model.id_model', $item['modelid'])
                ->where('nt_model_spec.id_model_type', 1)
                ->first();
                
                $count = DB::table('nt_data_ic')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ic.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->count();
                $chkiccount = $chkiccount + $count;
                $maxdata = DB::table('nt_data_ic')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ic.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->max('ic_anion_up');
                $max = number_format($maxdata, 5, '.', '');
               
                $mindata = DB::table('nt_data_ic')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ic.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->min('ic_anion_up');
                $min = number_format($mindata, 5, '.', '');

                $meandata = DB::table('nt_data_ic')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ic.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->avg('ic_anion_up');
                $mean = number_format($meandata, 5, '.', '');
   
                $stddata = DB::table('nt_data_ic')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ic.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid']);
                $stdget = $stddata->select(DB::raw('STDDEV_SAMP(nt_data_ic.ic_anion_up) as std'))->first();
                $std = number_format( $stdget->std, 5, '.', '');  
                $mean6data = $meandata + (6*$stdget->std);
                $mean6 = number_format($mean6data, 5, '.', '');
                $sheet = $doc->setActiveSheetIndex(11);
                $sheet->setCellValue('A'.$i,$model->name_model);
                $sheet->setCellValue('B'.$i,$model->part);
                $sheet->setCellValue('C'.$i,$model->part_number);
                $sheet->setCellValue('D'.$i,$count);
                $sheet->setCellValue('E'.$i,$max);
                $sheet->setCellValue('F'.$i,$min);
                $sheet->setCellValue('G'.$i,$mean);
                $sheet->setCellValue('H'.$i,$std);
                $sheet->setCellValue('I'.$i,$item['datestart']);
                $sheet->setCellValue('J'.$i,$item['dateend']);
                $sheet->setCellValue('P'.$i,$mean6);

        

                
                $maxdata = DB::table('nt_data_ic')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ic.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->max('ic_sulphate');
                $max = number_format($maxdata, 5, '.', '');
              
                $mindata = DB::table('nt_data_ic')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ic.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->min('ic_sulphate');
                $min =number_format($mindata, 5, '.', '');

                $meandata = DB::table('nt_data_ic')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ic.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->avg('ic_sulphate');
                $mean = number_format($meandata, 5, '.', '');
   
                $stddata = DB::table('nt_data_ic')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ic.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid']);
                $stdget = $stddata->select(DB::raw('STDDEV_SAMP(nt_data_ic.ic_sulphate) as std'))->first();
                $std = number_format($stdget->std, 5, '.', '');   
                $mean6data = $meandata + (6*$stdget->std);
                $mean6 = number_format($mean6data, 5, '.', '');
                $sheet = $doc->setActiveSheetIndex(12);
                $sheet->setCellValue('A'.$i,$model->name_model);
                $sheet->setCellValue('B'.$i,$model->part);
                $sheet->setCellValue('C'.$i,$model->part_number);
                $sheet->setCellValue('D'.$i,$count);
                $sheet->setCellValue('E'.$i,$max);
                $sheet->setCellValue('F'.$i,$min);
                $sheet->setCellValue('G'.$i,$mean);
                $sheet->setCellValue('H'.$i,$std);
                $sheet->setCellValue('I'.$i,$item['datestart']);
                $sheet->setCellValue('J'.$i,$item['dateend']);
                $sheet->setCellValue('P'.$i,$mean6);


                
                $maxdata = DB::table('nt_data_ic')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ic.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->max('ic_cation_up');
                $max = number_format($maxdata, 5, '.', '');
                $mindata = DB::table('nt_data_ic')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ic.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->min('ic_cation_up');
                $min = number_format($mindata, 5, '.', '');

                $meandata = DB::table('nt_data_ic')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ic.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->avg('ic_cation_up');
                $mean = number_format($meandata, 5, '.', '');
   
                $stddata = DB::table('nt_data_ic')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ic.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid']);
                $stdget = $stddata->select(DB::raw('STDDEV_SAMP(nt_data_ic.ic_cation_up) as std'))->first();
                $std = number_format($stdget->std, 5, '.', '');  
                $mean6data = $meandata + (6*$stdget->std);
                $mean6 = number_format($mean6data, 5, '.', '');
                $sheet = $doc->setActiveSheetIndex(13);
                $sheet->setCellValue('A'.$i,$model->name_model);
                $sheet->setCellValue('B'.$i,$model->part);
                $sheet->setCellValue('C'.$i,$model->part_number);
                $sheet->setCellValue('D'.$i,$count);
                $sheet->setCellValue('E'.$i,$max);
                $sheet->setCellValue('F'.$i,$min);
                $sheet->setCellValue('G'.$i,$mean);
                $sheet->setCellValue('H'.$i,$std);
                $sheet->setCellValue('I'.$i,$item['datestart']);
                $sheet->setCellValue('J'.$i,$item['dateend']);
                $sheet->setCellValue('P'.$i,$mean6);

              
                $i++;
                
            }
            
           
            $doc->setActiveSheetIndexByName('ALPC');
            $sheetlpc0 = $doc->getActiveSheetIndex();
            $doc->removeSheetByIndex($sheetlpc0);
          
                $doc->setActiveSheetIndexByName('SEM C 1');
                $sheetsem1 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetsem1);

                $doc->setActiveSheetIndexByName('SEM C 2');
                $sheetsem2 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetsem2);

                $doc->setActiveSheetIndexByName('SEM C 3');
                $sheetsem3 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetsem3);

                $doc->setActiveSheetIndexByName('SEM C 3.1');
                $sheetsem4 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetsem4);

                $doc->setActiveSheetIndexByName('SEM C 3.2');
                $sheetsem5 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetsem5);

                $doc->setActiveSheetIndexByName('SEM C 3.3');
                $sheetsem6 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetsem6);

                $doc->setActiveSheetIndexByName('SEM C 4');
                $sheetsem7 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetsem7);

                $doc->setActiveSheetIndexByName('SEM C 5');
                $sheetsem8 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetsem8);

                $doc->setActiveSheetIndexByName('SEM C 5.1');
                $sheetsem9 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetsem9);

                $doc->setActiveSheetIndexByName('SEM C 6');
                $sheetsem10 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetsem10);
           
          
                $doc->setActiveSheetIndexByName('Outgassing');
                $sheetgcms14 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetgcms14);

                $doc->setActiveSheetIndexByName('Low MW');
                $sheetgcms15 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetgcms15);

                $doc->setActiveSheetIndexByName('High MW');
                $sheetgcms16 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetgcms16);

                $doc->setActiveSheetIndexByName('Smear');
                $sheetgcms17 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetgcms17);
          
                $doc->setActiveSheetIndexByName('FTIR');
            $sheetftir18 = $doc->getActiveSheetIndex(0);
            $doc->removeSheetByIndex($sheetftir18);
            

           })->setFilename($namefile)->store('xlsx', storage_path('NHK-File/report/calculation'));
        return response()->json(['success' =>  1,'filename' => $namefile.'.xlsx']);


    }

    public function gcmscalculationexport(Request $request)	
    {
        ini_set('memory_limit', '-1');
        $chkexport = [];
        if(isset($request->id))
        {
            if(count($request->id) == 2)
             {
             $a1 = DB::table('nt_model')->where('id_model', $request->id[0])->first();
             $a2 = DB::table('nt_model')->where('id_model', $request->id[1])->first();
             $text1 = $a1->name_model;
             $text2 = $a2->name_model;
             $chktext1 = mb_substr($text1,0,-3);
             $chktext2 = mb_substr($text2,0,-3);
                 if($chktext1 == $chktext2)
                 {
                    if($a1->part == "UP")
                    {
                        $model1 = $a1;
                    }
                    else
                    {
                        $model1 = $a2;
                    }
                    if($a2->part == "DN")
                    {
                        $model2 = $a2;
                    }
                    else
                    {
                        $model2 = $a1;
                    }
                    $modelid1 = $model1->id_model;
                    $modelid2 = $model2->id_model;
                    $datestart1 = date('Y-m-d 00:00:00', strtotime($request->input('datestart'.$model1->id_model)));
                    $datestart2= date('Y-m-d 00:00:00', strtotime($request->input('datestart'.$model2->id_model)));
                    $dateend1 = date('Y-m-d 00:00:00', strtotime($request->input('dateend'.$model1->id_model). ' + 1 day'));
                    $dateend2 = date('Y-m-d 00:00:00', strtotime($request->input('dateend'.$model2->id_model). ' + 1 day'));
                    $dataexport = [];
                    $newdata =  array (
                        'modelid' => $modelid1,
                        'datestart' => $datestart1,
                        'dateend' => $dateend1,
                    );
                    array_push($dataexport,$newdata);
                    $newdata =  array (
                        'modelid' => $modelid2,
                        'datestart' => $datestart2,
                        'dateend' => $dateend2,
                    );
                    array_push($dataexport,$newdata);
                
                 }
                 else
                 {
                 return response()->json(['success' => 2]);
                 }
             }
             else
             {
             return response()->json(['success' => 3]);
             }
         }
        else
        {
        return response()->json(['success' => 4]);
        }
        $namefile = 'Calculation_'.date("YmdHis");
        $file = storage_path('NHK-File/template/report/template_calculation.xlsx');
        Excel::load($file, function ($doc)use($dataexport) {
           
                $i=5;
                $chklpccount = 0;
                $chksemcount = 0;
                $chkiccount = 0;
                $chkgcmscount = 0;
                $chkftircount = 0;
                foreach ($dataexport as $item) {  
                $model = DB::table('nt_model')
                ->join('nt_model_spec','nt_model_spec.id_model','=','nt_model.id_model')
                ->where('nt_model.id_model', $item['modelid'])
                ->where('nt_model_spec.id_model_type', 1)
                ->first();
                
                
                $count = DB::table('nt_data_gcms')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->count();
                $chkgcmscount = $chkgcmscount + $count;
                $maxdata = DB::table('nt_data_gcms')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->max('gcms_outgassing');
                $max = number_format($maxdata, 2, '.', '');
               
                $mindata = DB::table('nt_data_gcms')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->min('gcms_outgassing');
                $min = number_format($mindata, 2, '.', '');

                $meandata = DB::table('nt_data_gcms')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->avg('gcms_outgassing');
                $mean = number_format($meandata, 2, '.', '');
   
                $stddata = DB::table('nt_data_gcms')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid']);
                $stdget = $stddata->select(DB::raw('STDDEV_SAMP(nt_data_gcms.gcms_outgassing) as std'))->first();
                $std = number_format($stdget->std, 2, '.', '');   
                $mean6data = $meandata + (6*$stdget->std);
                $mean6 = number_format($mean6data, 2, '.', '');
                $sheet = $doc->setActiveSheetIndex(14);
                $sheet->setCellValue('A'.$i,$model->name_model);
                $sheet->setCellValue('B'.$i,$model->part);
                $sheet->setCellValue('C'.$i,$model->part_number);
                $sheet->setCellValue('D'.$i,$count);
                $sheet->setCellValue('E'.$i,$max);
                $sheet->setCellValue('F'.$i,$min);
                $sheet->setCellValue('G'.$i,$mean);
                $sheet->setCellValue('H'.$i,$std);
                $sheet->setCellValue('I'.$i,$item['datestart']);
                $sheet->setCellValue('J'.$i,$item['dateend']);
                $sheet->setCellValue('P'.$i,$mean6);
                $maxdata = DB::table('nt_data_gcms')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->max('gcms_low_mw');
                $max = number_format($maxdata, 2, '.', '');
               
                $mindata = DB::table('nt_data_gcms')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->min('gcms_low_mw');
                $min = number_format($mindata, 2, '.', '');

                $meandata = DB::table('nt_data_gcms')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->avg('gcms_low_mw');
                $mean = number_format($meandata, 2, '.', '');
   
                $stddata = DB::table('nt_data_gcms')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid']);
                $stdget = $stddata->select(DB::raw('STDDEV_SAMP(nt_data_gcms.gcms_low_mw) as std'))->first();
                $std = number_format($stdget->std, 2, '.', '');   
                $mean6data = $meandata + (6*$stdget->std);
                $mean6 = number_format($mean6data, 2, '.', '');
                $sheet = $doc->setActiveSheetIndex(15);
                $sheet->setCellValue('A'.$i,$model->name_model);
                $sheet->setCellValue('B'.$i,$model->part);
                $sheet->setCellValue('C'.$i,$model->part_number);
                $sheet->setCellValue('D'.$i,$count);
                $sheet->setCellValue('E'.$i,$max);
                $sheet->setCellValue('F'.$i,$min);
                $sheet->setCellValue('G'.$i,$mean);
                $sheet->setCellValue('H'.$i,$std);
                $sheet->setCellValue('I'.$i,$item['datestart']);
                $sheet->setCellValue('J'.$i,$item['dateend']);
                $sheet->setCellValue('P'.$i,$mean6);

                
                $maxdata = DB::table('nt_data_gcms')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->max('gcms_high_mw');
                $max = number_format($maxdata, 2, '.', '');
               
                $mindata = DB::table('nt_data_gcms')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->min('gcms_high_mw');
                $min = number_format($mindata, 2, '.', '');

                $meandata = DB::table('nt_data_gcms')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->avg('gcms_high_mw');
                $mean = number_format($meandata, 2, '.', '');
   
                $stddata = DB::table('nt_data_gcms')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid']);
                $stdget = $stddata->select(DB::raw('STDDEV_SAMP(nt_data_gcms.gcms_high_mw) as std'))->first();
                $std = number_format($stdget->std, 2, '.', '');   
                $mean6data = $meandata + (6*$stdget->std);
                $mean6 = number_format($mean6data, 2, '.', '');
                $sheet = $doc->setActiveSheetIndex(16);
                $sheet->setCellValue('A'.$i,$model->name_model);
                $sheet->setCellValue('B'.$i,$model->part);
                $sheet->setCellValue('C'.$i,$model->part_number);
                $sheet->setCellValue('D'.$i,$count);
                $sheet->setCellValue('E'.$i,$max);
                $sheet->setCellValue('F'.$i,$min);
                $sheet->setCellValue('G'.$i,$mean);
                $sheet->setCellValue('H'.$i,$std);
                $sheet->setCellValue('I'.$i,$item['datestart']);
                $sheet->setCellValue('J'.$i,$item['dateend']);
                $sheet->setCellValue('P'.$i,$mean6);

                
                $maxdata = DB::table('nt_data_gcms')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->max('gcms_smear');
                $max = number_format($maxdata, 2, '.', '');
               
                $mindata = DB::table('nt_data_gcms')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->min('gcms_smear');
                $min = number_format($mindata, 2, '.', '');

                $meandata = DB::table('nt_data_gcms')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->avg('gcms_smear');
                $mean = number_format($meandata, 2, '.', '');
   
                $stddata = DB::table('nt_data_gcms')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid']);
                $stdget = $stddata->select(DB::raw('STDDEV_SAMP(nt_data_gcms.gcms_smear) as std'))->first();
                $std = number_format($stdget->std, 2, '.', '');   
                $mean6data = $meandata + (6*$stdget->std);
                $mean6 = number_format($mean6data, 2, '.', '');
                $sheet = $doc->setActiveSheetIndex(17);
                $sheet->setCellValue('A'.$i,$model->name_model);
                $sheet->setCellValue('B'.$i,$model->part);
                $sheet->setCellValue('C'.$i,$model->part_number);
                $sheet->setCellValue('D'.$i,$count);
                $sheet->setCellValue('E'.$i,$max);
                $sheet->setCellValue('F'.$i,$min);
                $sheet->setCellValue('G'.$i,$mean);
                $sheet->setCellValue('H'.$i,$std);
                $sheet->setCellValue('I'.$i,$item['datestart']);
                $sheet->setCellValue('J'.$i,$item['dateend']);
                $sheet->setCellValue('P'.$i,$mean6);


              

                $i++;
                
            }
            
           
            $doc->setActiveSheetIndexByName('ALPC');
            $sheetlpc0 = $doc->getActiveSheetIndex();
            $doc->removeSheetByIndex($sheetlpc0);
          
                $doc->setActiveSheetIndexByName('SEM C 1');
                $sheetsem1 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetsem1);

                $doc->setActiveSheetIndexByName('SEM C 2');
                $sheetsem2 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetsem2);

                $doc->setActiveSheetIndexByName('SEM C 3');
                $sheetsem3 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetsem3);

                $doc->setActiveSheetIndexByName('SEM C 3.1');
                $sheetsem4 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetsem4);

                $doc->setActiveSheetIndexByName('SEM C 3.2');
                $sheetsem5 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetsem5);

                $doc->setActiveSheetIndexByName('SEM C 3.3');
                $sheetsem6 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetsem6);

                $doc->setActiveSheetIndexByName('SEM C 4');
                $sheetsem7 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetsem7);

                $doc->setActiveSheetIndexByName('SEM C 5');
                $sheetsem8 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetsem8);

                $doc->setActiveSheetIndexByName('SEM C 5.1');
                $sheetsem9 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetsem9);

                $doc->setActiveSheetIndexByName('SEM C 6');
                $sheetsem10 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetsem10);
           
                $doc->setActiveSheetIndexByName('Anion');
                $sheetic11 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetic11);

                $doc->setActiveSheetIndexByName('Sulphate');
                $sheetic12 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetic12);

                $doc->setActiveSheetIndexByName('Cation');
                $sheetic13 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetic13);
          
   
                $doc->setActiveSheetIndexByName('FTIR');
            $sheetftir18 = $doc->getActiveSheetIndex(0);
            $doc->removeSheetByIndex($sheetftir18);
            

           })->setFilename($namefile)->store('xlsx', storage_path('NHK-File/report/calculation'));
        return response()->json(['success' =>  1,'filename' => $namefile.'.xlsx']);


    }

    public function ftircalculationexport(Request $request)	
    {
        ini_set('memory_limit', '-1');
        $chkexport = [];
        if(isset($request->id))
        {
            if(count($request->id) == 2)
             {
             $a1 = DB::table('nt_model')->where('id_model', $request->id[0])->first();
             $a2 = DB::table('nt_model')->where('id_model', $request->id[1])->first();
             $text1 = $a1->name_model;
             $text2 = $a2->name_model;
             $chktext1 = mb_substr($text1,0,-3);
             $chktext2 = mb_substr($text2,0,-3);
                 if($chktext1 == $chktext2)
                 {
                    if($a1->part == "UP")
                    {
                        $model1 = $a1;
                    }
                    else
                    {
                        $model1 = $a2;
                    }
                    if($a2->part == "DN")
                    {
                        $model2 = $a2;
                    }
                    else
                    {
                        $model2 = $a1;
                    }
                    $modelid1 = $model1->id_model;
                    $modelid2 = $model2->id_model;
                    $datestart1 = date('Y-m-d 00:00:00', strtotime($request->input('datestart'.$model1->id_model)));
                    $datestart2= date('Y-m-d 00:00:00', strtotime($request->input('datestart'.$model2->id_model)));
                    $dateend1 = date('Y-m-d 00:00:00', strtotime($request->input('dateend'.$model1->id_model). ' + 1 day'));
                    $dateend2 = date('Y-m-d 00:00:00', strtotime($request->input('dateend'.$model2->id_model). ' + 1 day'));
                    $dataexport = [];
                    $newdata =  array (
                        'modelid' => $modelid1,
                        'datestart' => $datestart1,
                        'dateend' => $dateend1,
                    );
                    array_push($dataexport,$newdata);
                    $newdata =  array (
                        'modelid' => $modelid2,
                        'datestart' => $datestart2,
                        'dateend' => $dateend2,
                    );
                    array_push($dataexport,$newdata);
                
                 }
                 else
                 {
                 return response()->json(['success' => 2]);
                 }
             }
             else
             {
             return response()->json(['success' => 3]);
             }
         }
        else
        {
        return response()->json(['success' => 4]);
        }
        $namefile = 'Calculation_'.date("YmdHis");
        $file = storage_path('NHK-File/template/report/template_calculation.xlsx');
        Excel::load($file, function ($doc)use($dataexport) {
           
                $i=5;
                $chklpccount = 0;
                $chksemcount = 0;
                $chkiccount = 0;
                $chkgcmscount = 0;
                $chkftircount = 0;
                foreach ($dataexport as $item) {  
                $model = DB::table('nt_model')
                ->join('nt_model_spec','nt_model_spec.id_model','=','nt_model.id_model')
                ->where('nt_model.id_model', $item['modelid'])
                ->where('nt_model_spec.id_model_type', 1)
                ->first();
                
                $count = DB::table('nt_data_ftir')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ftir.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->count();
                
                $chkftircount = $chkftircount + $count;
                $maxdata = DB::table('nt_data_ftir')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ftir.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->max('ftir_silicone');
                $max = number_format($maxdata, 2, '.', '');
               
                $mindata = DB::table('nt_data_ftir')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ftir.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->min('ftir_silicone');
                $min = number_format($mindata, 2, '.', '');

                $meandata = DB::table('nt_data_ftir')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ftir.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->avg('ftir_silicone');
                $mean = number_format($meandata, 2, '.', '');
   
                $stddata = DB::table('nt_data_ftir')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ftir.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid']);
                $stdget = $stddata->select(DB::raw('STDDEV_SAMP(nt_data_ftir.ftir_silicone) as std'))->first();
                $std = number_format($stdget->std, 2, '.', '');   
                $mean6data = $meandata + (6*$stdget->std);
                $mean6 = number_format($mean6data, 2, '.', '');
                $sheet = $doc->setActiveSheetIndex(18);
                $sheet->setCellValue('A'.$i,$model->name_model);
                $sheet->setCellValue('B'.$i,$model->part);
                $sheet->setCellValue('C'.$i,$model->part_number);
                $sheet->setCellValue('D'.$i,$count);
                $sheet->setCellValue('E'.$i,$max);
                $sheet->setCellValue('F'.$i,$min);
                $sheet->setCellValue('G'.$i,$mean);
                $sheet->setCellValue('H'.$i,$std);
                $sheet->setCellValue('I'.$i,$item['datestart']);
                $sheet->setCellValue('J'.$i,$item['dateend']);
                $sheet->setCellValue('P'.$i,$mean6);

                $i++;
                
            }
            
            
            $doc->setActiveSheetIndexByName('ALPC');
            $sheetlpc0 = $doc->getActiveSheetIndex();
            $doc->removeSheetByIndex($sheetlpc0);
       
                $doc->setActiveSheetIndexByName('SEM C 1');
                $sheetsem1 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetsem1);

                $doc->setActiveSheetIndexByName('SEM C 2');
                $sheetsem2 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetsem2);

                $doc->setActiveSheetIndexByName('SEM C 3');
                $sheetsem3 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetsem3);

                $doc->setActiveSheetIndexByName('SEM C 3.1');
                $sheetsem4 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetsem4);

                $doc->setActiveSheetIndexByName('SEM C 3.2');
                $sheetsem5 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetsem5);

                $doc->setActiveSheetIndexByName('SEM C 3.3');
                $sheetsem6 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetsem6);

                $doc->setActiveSheetIndexByName('SEM C 4');
                $sheetsem7 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetsem7);

                $doc->setActiveSheetIndexByName('SEM C 5');
                $sheetsem8 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetsem8);

                $doc->setActiveSheetIndexByName('SEM C 5.1');
                $sheetsem9 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetsem9);

                $doc->setActiveSheetIndexByName('SEM C 6');
                $sheetsem10 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetsem10);
         
                $doc->setActiveSheetIndexByName('Anion');
                $sheetic11 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetic11);

                $doc->setActiveSheetIndexByName('Sulphate');
                $sheetic12 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetic12);

                $doc->setActiveSheetIndexByName('Cation');
                $sheetic13 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetic13);
          
                $doc->setActiveSheetIndexByName('Outgassing');
                $sheetgcms14 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetgcms14);

                $doc->setActiveSheetIndexByName('Low MW');
                $sheetgcms15 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetgcms15);

                $doc->setActiveSheetIndexByName('High MW');
                $sheetgcms16 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetgcms16);

                $doc->setActiveSheetIndexByName('Smear');
                $sheetgcms17 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetgcms17);
        

           })->setFilename($namefile)->store('xlsx', storage_path('NHK-File/report/calculation'));
        return response()->json(['success' =>  1,'filename' => $namefile.'.xlsx']);


    }

    public function calculationexport(Request $request)	
    {
        ini_set('memory_limit', '-1');
        $chkexport = [];
        if(isset($request->id))
        {
            if(count($request->id) == 2)
             {
             $a1 = DB::table('nt_model')->where('id_model', $request->id[0])->first();
             $a2 = DB::table('nt_model')->where('id_model', $request->id[1])->first();
             $text1 = $a1->name_model;
             $text2 = $a2->name_model;
             $chktext1 = mb_substr($text1,0,-3);
             $chktext2 = mb_substr($text2,0,-3);
                 if($chktext1 == $chktext2)
                 {
                    if($a1->part == "UP")
                    {
                        $model1 = $a1;
                    }
                    else
                    {
                        $model1 = $a2;
                    }
                    if($a2->part == "DN")
                    {
                        $model2 = $a2;
                    }
                    else
                    {
                        $model2 = $a1;
                    }
                    $modelid1 = $model1->id_model;
                    $modelid2 = $model2->id_model;
                    $datestart1 = date('Y-m-d 00:00:00', strtotime($request->input('datestart'.$model1->id_model)));
                    $datestart2= date('Y-m-d 00:00:00', strtotime($request->input('datestart'.$model2->id_model)));
                    $dateend1 = date('Y-m-d 00:00:00', strtotime($request->input('dateend'.$model1->id_model). ' + 1 day'));
                    $dateend2 = date('Y-m-d 00:00:00', strtotime($request->input('dateend'.$model2->id_model). ' + 1 day'));
                    $dataexport = [];
                    $newdata =  array (
                        'modelid' => $modelid1,
                        'datestart' => $datestart1,
                        'dateend' => $dateend1,
                    );
                    array_push($dataexport,$newdata);
                    $newdata =  array (
                        'modelid' => $modelid2,
                        'datestart' => $datestart2,
                        'dateend' => $dateend2,
                    );
                    array_push($dataexport,$newdata);
                
                 }
                 else
                 {
                 return response()->json(['success' => 2]);
                 }
             }
             else
             {
             return response()->json(['success' => 3]);
             }
         }
        else
        {
        return response()->json(['success' => 4]);
        }
        $namefile = 'Calculation_'.date("YmdHis");
        $file = storage_path('NHK-File/template/report/template_calculation.xlsx');
        Excel::load($file, function ($doc)use($dataexport) {
           
                $i=5;
                $chklpccount = 0;
                $chksemcount = 0;
                $chkiccount = 0;
                $chkgcmscount = 0;
                $chkftircount = 0;
                foreach ($dataexport as $item) {  
                $model = DB::table('nt_model')
                ->join('nt_model_spec','nt_model_spec.id_model','=','nt_model.id_model')
                ->where('nt_model.id_model', $item['modelid'])
                ->where('nt_model_spec.id_model_type', 1)
                ->first();
                
                $lpccount = DB::table('nt_data_lpc')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_lpc.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->count();
                $chklpccount = $chklpccount +$lpccount;
                $maxdata = DB::table('nt_data_lpc')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_lpc.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->max('lpc_data');
                $max = number_format($maxdata, 2, '.', '');
               
                $mindata = DB::table('nt_data_lpc')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_lpc.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->min('lpc_data');
                $min = number_format($mindata, 2, '.', '');

                $meandata = DB::table('nt_data_lpc')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_lpc.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->avg('lpc_data');
                $mean = number_format($meandata, 2, '.', '');
   
                $stddata = DB::table('nt_data_lpc')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_lpc.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid']);
                $stdget = $stddata->select(DB::raw('STDDEV_SAMP(nt_data_lpc.lpc_data) as std'))->first();
                $std = number_format($stdget->std, 2, '.', '');   
                $mean6data = $meandata + (6*$stdget->std);
                $mean6 = number_format($mean6data, 2, '.', '');
                $sheet = $doc->setActiveSheetIndex(0);
                $sheet->setCellValue('A'.$i,$model->name_model);
                $sheet->setCellValue('B'.$i,$model->part);
                $sheet->setCellValue('C'.$i,$model->part_number);
                $sheet->setCellValue('D'.$i,$lpccount);
                $sheet->setCellValue('E'.$i,$max);
                $sheet->setCellValue('F'.$i,$min);
                $sheet->setCellValue('G'.$i,$mean);
                $sheet->setCellValue('H'.$i,$std);
                $sheet->setCellValue('I'.$i,$item['datestart']);
                $sheet->setCellValue('J'.$i,$item['dateend']);
                $sheet->setCellValue('P'.$i,$mean6);
                
                $count = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->count();
                $chksemcount = $chksemcount + $count;
                $maxdata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->max('sem_class1');
                $max = number_format($maxdata, 2, '.', '');
               
                $mindata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->min('sem_class1');
                $min = number_format($mindata, 2, '.', '');

                $meandata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->avg('sem_class1');
                $mean = number_format($meandata, 2, '.', '');
   
                $stddata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid']);
                $stdget = $stddata->select(DB::raw('STDDEV_SAMP(nt_data_sem.sem_class1) as std'))->first();
                $std = number_format($stdget->std, 2, '.', '');   
                $mean6data = $meandata + (6*$stdget->std);
                $mean6 = number_format($mean6data, 2, '.', '');
                $sheet = $doc->setActiveSheetIndex(1);
                $sheet->setCellValue('A'.$i,$model->name_model);
                $sheet->setCellValue('B'.$i,$model->part);
                $sheet->setCellValue('C'.$i,$model->part_number);
                $sheet->setCellValue('D'.$i,$count);
                $sheet->setCellValue('E'.$i,$max);
                $sheet->setCellValue('F'.$i,$min);
                $sheet->setCellValue('G'.$i,$mean);
                $sheet->setCellValue('H'.$i,$std);
                $sheet->setCellValue('I'.$i,$item['datestart']);
                $sheet->setCellValue('J'.$i,$item['dateend']);
                $sheet->setCellValue('P'.$i,$mean6);

                
                $maxdata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->max('sem_class2');
                $max = number_format($maxdata, 2, '.', '');
               
                $mindata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->min('sem_class2');
                $min = number_format($mindata, 2, '.', '');

                $meandata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->avg('sem_class2');
                $mean = number_format($meandata, 2, '.', '');
   
                $stddata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid']);
                $stdget = $stddata->select(DB::raw('STDDEV_SAMP(nt_data_sem.sem_class2) as std'))->first();
                $std = number_format($stdget->std, 2, '.', '');   
                $mean6data = $meandata + (6*$stdget->std);
                $mean6 = number_format($mean6data, 2, '.', '');
                $sheet = $doc->setActiveSheetIndex(2);
                $sheet->setCellValue('A'.$i,$model->name_model);
                $sheet->setCellValue('B'.$i,$model->part);
                $sheet->setCellValue('C'.$i,$model->part_number);
                $sheet->setCellValue('D'.$i,$count);
                $sheet->setCellValue('E'.$i,$max);
                $sheet->setCellValue('F'.$i,$min);
                $sheet->setCellValue('G'.$i,$mean);
                $sheet->setCellValue('H'.$i,$std);
                $sheet->setCellValue('I'.$i,$item['datestart']);
                $sheet->setCellValue('J'.$i,$item['dateend']);
                $sheet->setCellValue('P'.$i,$mean6);
               

                
                $maxdata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->max('sem_class3');
                $max = number_format($maxdata, 2, '.', '');
               
                $mindata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->min('sem_class3');
                $min = number_format($mindata, 2, '.', '');

                $meandata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->avg('sem_class3');
                $mean = number_format($meandata, 2, '.', '');
   
                $stddata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid']);
                $stdget = $stddata->select(DB::raw('STDDEV_SAMP(nt_data_sem.sem_class3) as std'))->first();
                $std = number_format($stdget->std, 2, '.', '');   
                $mean6data = $meandata + (6*$stdget->std);
                $mean6 = number_format($mean6data, 2, '.', '');
                $sheet = $doc->setActiveSheetIndex(3);
                $sheet->setCellValue('A'.$i,$model->name_model);
                $sheet->setCellValue('B'.$i,$model->part);
                $sheet->setCellValue('C'.$i,$model->part_number);
                $sheet->setCellValue('D'.$i,$count);
                $sheet->setCellValue('E'.$i,$max);
                $sheet->setCellValue('F'.$i,$min);
                $sheet->setCellValue('G'.$i,$mean);
                $sheet->setCellValue('H'.$i,$std);
                $sheet->setCellValue('I'.$i,$item['datestart']);
                $sheet->setCellValue('J'.$i,$item['dateend']);
                $sheet->setCellValue('P'.$i,$mean6);


                
                $maxdata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->max('sem_class3_1');
                $max = number_format($maxdata, 2, '.', '');
               
                $mindata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->min('sem_class3_1');
                $min = number_format($mindata, 2, '.', '');

                $meandata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->avg('sem_class3_1');
                $mean = number_format($meandata, 2, '.', '');
   
                $stddata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid']);
                $stdget = $stddata->select(DB::raw('STDDEV_SAMP(nt_data_sem.sem_class3_1) as std'))->first();
                $std = number_format($stdget->std, 2, '.', '');   
                $mean6data = $meandata + (6*$stdget->std);
                $mean6 = number_format($mean6data, 2, '.', '');
                $sheet = $doc->setActiveSheetIndex(4);
                $sheet->setCellValue('A'.$i,$model->name_model);
                $sheet->setCellValue('B'.$i,$model->part);
                $sheet->setCellValue('C'.$i,$model->part_number);
                $sheet->setCellValue('D'.$i,$count);
                $sheet->setCellValue('E'.$i,$max);
                $sheet->setCellValue('F'.$i,$min);
                $sheet->setCellValue('G'.$i,$mean);
                $sheet->setCellValue('H'.$i,$std);
                $sheet->setCellValue('I'.$i,$item['datestart']);
                $sheet->setCellValue('J'.$i,$item['dateend']);
                $sheet->setCellValue('P'.$i,$mean6);


                
                $maxdata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->max('sem_class3_2');
                $max = number_format($maxdata, 2, '.', '');
               
                $mindata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->min('sem_class3_2');
                $min = number_format($mindata, 2, '.', '');

                $meandata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->avg('sem_class3_2');
                $mean = number_format($meandata, 2, '.', '');
   
                $stddata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid']);
                $stdget = $stddata->select(DB::raw('STDDEV_SAMP(nt_data_sem.sem_class3_2) as std'))->first();
                $std = number_format($stdget->std, 2, '.', '');   
                $mean6data = $meandata + (6*$stdget->std);
                $mean6 = number_format($mean6data, 2, '.', '');
                $sheet = $doc->setActiveSheetIndex(5);
                $sheet->setCellValue('A'.$i,$model->name_model);
                $sheet->setCellValue('B'.$i,$model->part);
                $sheet->setCellValue('C'.$i,$model->part_number);
                $sheet->setCellValue('D'.$i,$count);
                $sheet->setCellValue('E'.$i,$max);
                $sheet->setCellValue('F'.$i,$min);
                $sheet->setCellValue('G'.$i,$mean);
                $sheet->setCellValue('H'.$i,$std);
                $sheet->setCellValue('I'.$i,$item['datestart']);
                $sheet->setCellValue('J'.$i,$item['dateend']);
                $sheet->setCellValue('P'.$i,$mean6);


                
                $maxdata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->max('sem_class3_3');
                $max = number_format($maxdata, 2, '.', '');
               
                $mindata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->min('sem_class3_3');
                $min = number_format($mindata, 2, '.', '');

                $meandata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->avg('sem_class3_3');
                $mean = number_format($meandata, 2, '.', '');
   
                $stddata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid']);
                $stdget = $stddata->select(DB::raw('STDDEV_SAMP(nt_data_sem.sem_class3_3) as std'))->first();
                $std = number_format($stdget->std, 2, '.', '');   
                $mean6data = $meandata + (6*$stdget->std);
                $mean6 = number_format($mean6data, 2, '.', '');
                $sheet = $doc->setActiveSheetIndex(6);
                $sheet->setCellValue('A'.$i,$model->name_model);
                $sheet->setCellValue('B'.$i,$model->part);
                $sheet->setCellValue('C'.$i,$model->part_number);
                $sheet->setCellValue('D'.$i,$count);
                $sheet->setCellValue('E'.$i,$max);
                $sheet->setCellValue('F'.$i,$min);
                $sheet->setCellValue('G'.$i,$mean);
                $sheet->setCellValue('H'.$i,$std);
                $sheet->setCellValue('I'.$i,$item['datestart']);
                $sheet->setCellValue('J'.$i,$item['dateend']);
                $sheet->setCellValue('P'.$i,$mean6);

                
                $maxdata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->max('sem_class4');
                $max = number_format($maxdata, 2, '.', '');
               
                $mindata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->min('sem_class4');
                $min = number_format($mindata, 2, '.', '');

                $meandata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->avg('sem_class4');
                $mean = number_format($meandata, 2, '.', '');
   
                $stddata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid']);
                $stdget = $stddata->select(DB::raw('STDDEV_SAMP(nt_data_sem.sem_class4) as std'))->first();
                $std = number_format($stdget->std, 2, '.', '');   
                $mean6data = $meandata + (6*$stdget->std);
                $mean6 = number_format($mean6data, 2, '.', '');
                $sheet = $doc->setActiveSheetIndex(7);
                $sheet->setCellValue('A'.$i,$model->name_model);
                $sheet->setCellValue('B'.$i,$model->part);
                $sheet->setCellValue('C'.$i,$model->part_number);
                $sheet->setCellValue('D'.$i,$count);
                $sheet->setCellValue('E'.$i,$max);
                $sheet->setCellValue('F'.$i,$min);
                $sheet->setCellValue('G'.$i,$mean);
                $sheet->setCellValue('H'.$i,$std);
                $sheet->setCellValue('I'.$i,$item['datestart']);
                $sheet->setCellValue('J'.$i,$item['dateend']);
                $sheet->setCellValue('P'.$i,$mean6);

                
                $maxdata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->max('sem_class5');
                $max = number_format($maxdata, 2, '.', '');
               
                $mindata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->min('sem_class5');
                $min = number_format($mindata, 2, '.', '');

                $meandata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->avg('sem_class5');
                $mean = number_format($meandata, 2, '.', '');
   
                $stddata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid']);
                $stdget = $stddata->select(DB::raw('STDDEV_SAMP(nt_data_sem.sem_class5) as std'))->first();
                $std = number_format($stdget->std, 2, '.', '');   
                $mean6data = $meandata + (6*$stdget->std);
                $mean6 = number_format($mean6data, 2, '.', '');
                $sheet = $doc->setActiveSheetIndex(8);
                $sheet->setCellValue('A'.$i,$model->name_model);
                $sheet->setCellValue('B'.$i,$model->part);
                $sheet->setCellValue('C'.$i,$model->part_number);
                $sheet->setCellValue('D'.$i,$count);
                $sheet->setCellValue('E'.$i,$max);
                $sheet->setCellValue('F'.$i,$min);
                $sheet->setCellValue('G'.$i,$mean);
                $sheet->setCellValue('H'.$i,$std);
                $sheet->setCellValue('I'.$i,$item['datestart']);
                $sheet->setCellValue('J'.$i,$item['dateend']);
                $sheet->setCellValue('P'.$i,$mean6);

                $maxdata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->max('sem_class5_1');
                $max = number_format($maxdata, 2, '.', '');
               
                $mindata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->min('sem_class5_1');
                $min = number_format($mindata, 2, '.', '');

                $meandata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->avg('sem_class5_1');
                $mean = number_format($meandata, 2, '.', '');
   
                $stddata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid']);
                $stdget = $stddata->select(DB::raw('STDDEV_SAMP(nt_data_sem.sem_class5_1) as std'))->first();
                $std = number_format($stdget->std, 2, '.', '');   
                $mean6data = $meandata + (6*$stdget->std);
                $mean6 = number_format($mean6data, 2, '.', '');
                $sheet = $doc->setActiveSheetIndex(9);
                $sheet->setCellValue('A'.$i,$model->name_model);
                $sheet->setCellValue('B'.$i,$model->part);
                $sheet->setCellValue('C'.$i,$model->part_number);
                $sheet->setCellValue('D'.$i,$count);
                $sheet->setCellValue('E'.$i,$max);
                $sheet->setCellValue('F'.$i,$min);
                $sheet->setCellValue('G'.$i,$mean);
                $sheet->setCellValue('H'.$i,$std);
                $sheet->setCellValue('I'.$i,$item['datestart']);
                $sheet->setCellValue('J'.$i,$item['dateend']);
                $sheet->setCellValue('P'.$i,$mean6);


                
                $maxdata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->max('sem_class6');
                $max = number_format($maxdata, 2, '.', '');
               
                $mindata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->min('sem_class6');
                $min = number_format($mindata, 2, '.', '');

                $meandata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->avg('sem_class6');
                $mean = number_format($meandata, 2, '.', '');
   
                $stddata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid']);
                $stdget = $stddata->select(DB::raw('STDDEV_SAMP(nt_data_sem.sem_class6) as std'))->first();
                $std = number_format($stdget->std, 2, '.', '');   
                $mean6data = $meandata + (6*$stdget->std);
                $mean6 = number_format($mean6data, 2, '.', '');
                $sheet = $doc->setActiveSheetIndex(10);
                $sheet->setCellValue('A'.$i,$model->name_model);
                $sheet->setCellValue('B'.$i,$model->part);
                $sheet->setCellValue('C'.$i,$model->part_number);
                $sheet->setCellValue('D'.$i,$count);
                $sheet->setCellValue('E'.$i,$max);
                $sheet->setCellValue('F'.$i,$min);
                $sheet->setCellValue('G'.$i,$mean);
                $sheet->setCellValue('H'.$i,$std);
                $sheet->setCellValue('I'.$i,$item['datestart']);
                $sheet->setCellValue('J'.$i,$item['dateend']);
                $sheet->setCellValue('P'.$i,$mean6);

                $count = DB::table('nt_data_ic')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ic.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->count();
                $chkiccount = $chkiccount + $count;
                $maxdata = DB::table('nt_data_ic')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ic.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->max('ic_anion_up');
                $max = number_format($maxdata, 5, '.', '');
               
                $mindata = DB::table('nt_data_ic')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ic.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->min('ic_anion_up');
                $min = number_format($mindata, 5, '.', '');

                $meandata = DB::table('nt_data_ic')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ic.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->avg('ic_anion_up');
                $mean = number_format($meandata, 5, '.', '');
   
                $stddata = DB::table('nt_data_ic')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ic.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid']);
                $stdget = $stddata->select(DB::raw('STDDEV_SAMP(nt_data_ic.ic_anion_up) as std'))->first();
                $std = number_format($stdget->std, 5, '.', '');   
                $mean6data = $meandata + (6*$stdget->std);
                $mean6 = number_format($mean6data, 5, '.', '');
                $sheet = $doc->setActiveSheetIndex(11);
                $sheet->setCellValue('A'.$i,$model->name_model);
                $sheet->setCellValue('B'.$i,$model->part);
                $sheet->setCellValue('C'.$i,$model->part_number);
                $sheet->setCellValue('D'.$i,$count);
                $sheet->setCellValue('E'.$i,$max);
                $sheet->setCellValue('F'.$i,$min);
                $sheet->setCellValue('G'.$i,$mean);
                $sheet->setCellValue('H'.$i,$std);
                $sheet->setCellValue('I'.$i,$item['datestart']);
                $sheet->setCellValue('J'.$i,$item['dateend']);
                $sheet->setCellValue('P'.$i,$mean6);

        

                
                $maxdata = DB::table('nt_data_ic')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ic.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->max('ic_sulphate');
                $max = number_format($maxdata, 5, '.', '');
               
                $mindata = DB::table('nt_data_ic')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ic.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->min('ic_sulphate');
                $min = number_format($mindata, 5, '.', '');

                $meandata = DB::table('nt_data_ic')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ic.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->avg('ic_sulphate');
                $mean = number_format($meandata, 5, '.', '');
   
                $stddata = DB::table('nt_data_ic')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ic.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid']);
                $stdget = $stddata->select(DB::raw('STDDEV_SAMP(nt_data_ic.ic_sulphate) as std'))->first();
                $std = number_format($stdget->std, 5, '.', '');   
                $mean6data = $meandata + (6*$stdget->std);
                $mean6 = number_format($mean6data, 5, '.', '');
                $sheet = $doc->setActiveSheetIndex(12);
                $sheet->setCellValue('A'.$i,$model->name_model);
                $sheet->setCellValue('B'.$i,$model->part);
                $sheet->setCellValue('C'.$i,$model->part_number);
                $sheet->setCellValue('D'.$i,$count);
                $sheet->setCellValue('E'.$i,$max);
                $sheet->setCellValue('F'.$i,$min);
                $sheet->setCellValue('G'.$i,$mean);
                $sheet->setCellValue('H'.$i,$std);
                $sheet->setCellValue('I'.$i,$item['datestart']);
                $sheet->setCellValue('J'.$i,$item['dateend']);
                $sheet->setCellValue('P'.$i,$mean6);


                
                $maxdata = DB::table('nt_data_ic')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ic.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->max('ic_cation_up');
                $max = number_format($maxdata, 5, '.', '');
               
                $mindata = DB::table('nt_data_ic')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ic.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->min('ic_cation_up');
                $min = number_format($mindata, 5, '.', '');

                $meandata = DB::table('nt_data_ic')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ic.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->avg('ic_cation_up');
                $mean = number_format($meandata, 5, '.', '');
   
                $stddata = DB::table('nt_data_ic')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ic.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid']);
                $stdget = $stddata->select(DB::raw('STDDEV_SAMP(nt_data_ic.ic_cation_up) as std'))->first();
                $std = number_format($stdget->std, 5, '.', '');   
                $mean6data = $meandata + (6*$stdget->std);
                $mean6 = number_format($mean6data, 5, '.', '');
                $sheet = $doc->setActiveSheetIndex(13);
                $sheet->setCellValue('A'.$i,$model->name_model);
                $sheet->setCellValue('B'.$i,$model->part);
                $sheet->setCellValue('C'.$i,$model->part_number);
                $sheet->setCellValue('D'.$i,$count);
                $sheet->setCellValue('E'.$i,$max);
                $sheet->setCellValue('F'.$i,$min);
                $sheet->setCellValue('G'.$i,$mean);
                $sheet->setCellValue('H'.$i,$std);
                $sheet->setCellValue('I'.$i,$item['datestart']);
                $sheet->setCellValue('J'.$i,$item['dateend']);
                $sheet->setCellValue('P'.$i,$mean6);

                $count = DB::table('nt_data_gcms')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->count();
                $chkgcmscount = $chkgcmscount + $count;
                $maxdata = DB::table('nt_data_gcms')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->max('gcms_outgassing');
                $max = number_format($maxdata, 2, '.', '');
               
                $mindata = DB::table('nt_data_gcms')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->min('gcms_outgassing');
                $min = number_format($mindata, 2, '.', '');

                $meandata = DB::table('nt_data_gcms')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->avg('gcms_outgassing');
                $mean = number_format($meandata, 2, '.', '');
   
                $stddata = DB::table('nt_data_gcms')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid']);
                $stdget = $stddata->select(DB::raw('STDDEV_SAMP(nt_data_gcms.gcms_outgassing) as std'))->first();
                $std = number_format($stdget->std, 2, '.', '');   
                $mean6data = $meandata + (6*$stdget->std);
                $mean6 = number_format($mean6data, 2, '.', '');
                $sheet = $doc->setActiveSheetIndex(14);
                $sheet->setCellValue('A'.$i,$model->name_model);
                $sheet->setCellValue('B'.$i,$model->part);
                $sheet->setCellValue('C'.$i,$model->part_number);
                $sheet->setCellValue('D'.$i,$count);
                $sheet->setCellValue('E'.$i,$max);
                $sheet->setCellValue('F'.$i,$min);
                $sheet->setCellValue('G'.$i,$mean);
                $sheet->setCellValue('H'.$i,$std);
                $sheet->setCellValue('I'.$i,$item['datestart']);
                $sheet->setCellValue('J'.$i,$item['dateend']);
                $sheet->setCellValue('P'.$i,$mean6);
                $maxdata = DB::table('nt_data_gcms')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->max('gcms_low_mw');
                $max = number_format($maxdata, 2, '.', '');
               
                $mindata = DB::table('nt_data_gcms')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->min('gcms_low_mw');
                $min = number_format($mindata, 2, '.', '');

                $meandata = DB::table('nt_data_gcms')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->avg('gcms_low_mw');
                $mean = number_format($meandata, 2, '.', '');
   
                $stddata = DB::table('nt_data_gcms')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid']);
                $stdget = $stddata->select(DB::raw('STDDEV_SAMP(nt_data_gcms.gcms_low_mw) as std'))->first();
                $std = number_format($stdget->std, 2, '.', '');   
                $mean6data = $meandata + (6*$stdget->std);
                $mean6 = number_format($mean6data, 2, '.', '');
                $sheet = $doc->setActiveSheetIndex(15);
                $sheet->setCellValue('A'.$i,$model->name_model);
                $sheet->setCellValue('B'.$i,$model->part);
                $sheet->setCellValue('C'.$i,$model->part_number);
                $sheet->setCellValue('D'.$i,$count);
                $sheet->setCellValue('E'.$i,$max);
                $sheet->setCellValue('F'.$i,$min);
                $sheet->setCellValue('G'.$i,$mean);
                $sheet->setCellValue('H'.$i,$std);
                $sheet->setCellValue('I'.$i,$item['datestart']);
                $sheet->setCellValue('J'.$i,$item['dateend']);
                $sheet->setCellValue('P'.$i,$mean6);

                
                $maxdata = DB::table('nt_data_gcms')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->max('gcms_high_mw');
                $max = number_format($maxdata, 2, '.', '');
               
                $mindata = DB::table('nt_data_gcms')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->min('gcms_high_mw');
                $min = number_format($mindata, 2, '.', '');

                $meandata = DB::table('nt_data_gcms')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->avg('gcms_high_mw');
                $mean = number_format($meandata, 2, '.', '');
   
                $stddata = DB::table('nt_data_gcms')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid']);
                $stdget = $stddata->select(DB::raw('STDDEV_SAMP(nt_data_gcms.gcms_high_mw) as std'))->first();
                $std = number_format($stdget->std, 2, '.', '');   
                $mean6data = $meandata + (6*$stdget->std);
                $mean6 = number_format($mean6data, 2, '.', '');
                $sheet = $doc->setActiveSheetIndex(16);
                $sheet->setCellValue('A'.$i,$model->name_model);
                $sheet->setCellValue('B'.$i,$model->part);
                $sheet->setCellValue('C'.$i,$model->part_number);
                $sheet->setCellValue('D'.$i,$count);
                $sheet->setCellValue('E'.$i,$max);
                $sheet->setCellValue('F'.$i,$min);
                $sheet->setCellValue('G'.$i,$mean);
                $sheet->setCellValue('H'.$i,$std);
                $sheet->setCellValue('I'.$i,$item['datestart']);
                $sheet->setCellValue('J'.$i,$item['dateend']);
                $sheet->setCellValue('P'.$i,$mean6);

                
                $maxdata = DB::table('nt_data_gcms')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->max('gcms_smear');
                $max = number_format($maxdata, 2, '.', '');
               
                $mindata = DB::table('nt_data_gcms')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->min('gcms_smear');
                $min = number_format($mindata, 2, '.', '');

                $meandata = DB::table('nt_data_gcms')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->avg('gcms_smear');
                $mean = number_format($meandata, 2, '.', '');
   
                $stddata = DB::table('nt_data_gcms')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid']);
                $stdget = $stddata->select(DB::raw('STDDEV_SAMP(nt_data_gcms.gcms_smear) as std'))->first();
                $std = number_format($stdget->std, 2, '.', '');   
                $mean6data = $meandata + (6*$stdget->std);
                $mean6 = number_format($mean6data, 2, '.', '');
                $sheet = $doc->setActiveSheetIndex(17);
                $sheet->setCellValue('A'.$i,$model->name_model);
                $sheet->setCellValue('B'.$i,$model->part);
                $sheet->setCellValue('C'.$i,$model->part_number);
                $sheet->setCellValue('D'.$i,$count);
                $sheet->setCellValue('E'.$i,$max);
                $sheet->setCellValue('F'.$i,$min);
                $sheet->setCellValue('G'.$i,$mean);
                $sheet->setCellValue('H'.$i,$std);
                $sheet->setCellValue('I'.$i,$item['datestart']);
                $sheet->setCellValue('J'.$i,$item['dateend']);
                $sheet->setCellValue('P'.$i,$mean6);


                $count = DB::table('nt_data_ftir')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ftir.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->count();
                
                $chkftircount = $chkftircount + $count;
                $maxdata = DB::table('nt_data_ftir')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ftir.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->max('ftir_silicone');
                $max = number_format($maxdata, 2, '.', '');
               
                $mindata = DB::table('nt_data_ftir')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ftir.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->min('ftir_silicone');
                $min = number_format($mindata, 2, '.', '');

                $meandata = DB::table('nt_data_ftir')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ftir.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->avg('ftir_silicone');
                $mean = number_format($meandata, 2, '.', '');
   
                $stddata = DB::table('nt_data_ftir')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ftir.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid']);
                $stdget = $stddata->select(DB::raw('STDDEV_SAMP(nt_data_ftir.ftir_silicone) as std'))->first();
                $std = number_format($stdget->std, 2, '.', '');   
                $mean6data = $meandata + (6*$stdget->std);
                $mean6 = number_format($mean6data, 2, '.', '');
                $sheet = $doc->setActiveSheetIndex(18);
                $sheet->setCellValue('A'.$i,$model->name_model);
                $sheet->setCellValue('B'.$i,$model->part);
                $sheet->setCellValue('C'.$i,$model->part_number);
                $sheet->setCellValue('D'.$i,$count);
                $sheet->setCellValue('E'.$i,$max);
                $sheet->setCellValue('F'.$i,$min);
                $sheet->setCellValue('G'.$i,$mean);
                $sheet->setCellValue('H'.$i,$std);
                $sheet->setCellValue('I'.$i,$item['datestart']);
                $sheet->setCellValue('J'.$i,$item['dateend']);
                $sheet->setCellValue('P'.$i,$mean6);

                $i++;
                
            }
            
            if($chklpccount == 0)
            {
            $doc->setActiveSheetIndexByName('ALPC');
            $sheetlpc0 = $doc->getActiveSheetIndex();
            $doc->removeSheetByIndex($sheetlpc0);
            }
            if($chksemcount == 0)
            {
                $doc->setActiveSheetIndexByName('SEM C 1');
                $sheetsem1 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetsem1);

                $doc->setActiveSheetIndexByName('SEM C 2');
                $sheetsem2 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetsem2);

                $doc->setActiveSheetIndexByName('SEM C 3');
                $sheetsem3 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetsem3);

                $doc->setActiveSheetIndexByName('SEM C 3.1');
                $sheetsem4 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetsem4);

                $doc->setActiveSheetIndexByName('SEM C 3.2');
                $sheetsem5 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetsem5);

                $doc->setActiveSheetIndexByName('SEM C 3.3');
                $sheetsem6 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetsem6);

                $doc->setActiveSheetIndexByName('SEM C 4');
                $sheetsem7 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetsem7);

                $doc->setActiveSheetIndexByName('SEM C 5');
                $sheetsem8 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetsem8);

                $doc->setActiveSheetIndexByName('SEM C 5.1');
                $sheetsem9 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetsem9);

                $doc->setActiveSheetIndexByName('SEM C 6');
                $sheetsem10 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetsem10);
            }
            if($chkiccount == 0)
            {
                $doc->setActiveSheetIndexByName('Anion');
                $sheetic11 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetic11);

                $doc->setActiveSheetIndexByName('Sulphate');
                $sheetic12 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetic12);

                $doc->setActiveSheetIndexByName('Cation');
                $sheetic13 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetic13);
            }
            if($chkgcmscount == 0)
            {
                $doc->setActiveSheetIndexByName('Outgassing');
                $sheetgcms14 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetgcms14);

                $doc->setActiveSheetIndexByName('Low MW');
                $sheetgcms15 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetgcms15);

                $doc->setActiveSheetIndexByName('High MW');
                $sheetgcms16 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetgcms16);

                $doc->setActiveSheetIndexByName('Smear');
                $sheetgcms17 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetgcms17);
            }
            if($chkftircount == 0)
            {
                $doc->setActiveSheetIndexByName('FTIR');
            $sheetftir18 = $doc->getActiveSheetIndex(0);
            $doc->removeSheetByIndex($sheetftir18);
            }

           })->setFilename($namefile)->store('xlsx', storage_path('NHK-File/report/calculation'));
        return response()->json(['success' =>  1,'filename' => $namefile.'.xlsx']);


    }
    public function showcalculation(Request $request)	
    {
      
        $dateend_new = date('Y-m-d 00:00:00', strtotime($request->dateend. ' + 1 day'));
        $lpccount = DB::table('nt_data_lpc')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_lpc.id_createlab')
        ->where('nt_oqa_lab.save_date','<=',$dateend_new)
        ->where('nt_oqa_lab.save_date','>=',$request->datestart)
        ->where('nt_oqa_lab.id_model',$request->id)->count();
        $semcount = DB::table('nt_data_sem')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
        ->where('nt_oqa_lab.save_date','<=',$dateend_new)
        ->where('nt_oqa_lab.save_date','>=',$request->datestart)
        ->where('nt_oqa_lab.id_model',$request->id)->count();
        $iccount = DB::table('nt_data_ic')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ic.id_createlab')
        ->where('nt_oqa_lab.save_date','<=',$dateend_new)
        ->where('nt_oqa_lab.save_date','>=',$request->datestart)
        ->where('nt_oqa_lab.id_model',$request->id)->count();
        $gcmscount = DB::table('nt_data_gcms')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
        ->where('nt_oqa_lab.save_date','<=',$dateend_new)
        ->where('nt_oqa_lab.save_date','>=',$request->datestart)
        ->where('nt_oqa_lab.id_model',$request->id)->count();
        $ftircount = DB::table('nt_data_ftir')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ftir.id_createlab')
        ->where('nt_oqa_lab.save_date','<=',$dateend_new)
        ->where('nt_oqa_lab.save_date','>=',$request->datestart)
        ->where('nt_oqa_lab.id_model',$request->id)->count();
        $meandata = DB::table('nt_data_lpc')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_lpc.id_createlab')
        ->where('nt_oqa_lab.save_date','<=',$dateend_new)
        ->where('nt_oqa_lab.save_date','>=',$request->datestart)
        ->where('nt_oqa_lab.id_model',$request->id)->avg('lpc_data');
        $mean = number_format($meandata, 2, '.', '');
        $maxdata = DB::table('nt_data_lpc')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_lpc.id_createlab')
        ->where('nt_oqa_lab.save_date','<=',$dateend_new)
        ->where('nt_oqa_lab.save_date','>=',$request->datestart)
        ->where('nt_oqa_lab.id_model',$request->id)->max('lpc_data');
        $max = number_format($maxdata, 2, '.', '');
        $mindata = DB::table('nt_data_lpc')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_lpc.id_createlab')
        ->where('nt_oqa_lab.save_date','<=',$dateend_new)
        ->where('nt_oqa_lab.save_date','>=',$request->datestart)
        ->where('nt_oqa_lab.id_model',$request->id)->min('lpc_data');
        $min = number_format($mindata, 2, '.', '');
        $stddata = DB::table('nt_data_lpc')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_lpc.id_createlab')
        ->where('nt_oqa_lab.save_date','<=',$dateend_new)
        ->where('nt_oqa_lab.save_date','>=',$request->datestart)
        ->where('nt_oqa_lab.id_model',$request->id);
        $stdget = $stddata->select(DB::raw('STDDEV_SAMP(nt_data_lpc.lpc_data) as std'))->first();
        $std = number_format($stdget->std, 2, '.', '');
        $mean3data = $meandata + (3 * $stdget->std);
        $mean3 = number_format($mean3data, 2, '.', '');
        $mean4data = $meandata + (4*$stdget->std);
        $mean4 = number_format($mean4data, 2, '.', '');
        $mean4_5data = $meandata + (4.5*$stdget->std);
        $mean4_5 = number_format($mean4_5data, 2, '.', '');
        $mean6data = $meandata + (6*$stdget->std);
        $mean6 = number_format($mean6data, 2, '.', '');
        return response()->json([
            'success' =>  1,
            'lpccount'=>$lpccount,
            'semcount'=>$semcount,
            'iccount'=>$iccount,
            'gcmscount'=>$gcmscount,
            'ftircount'=>$ftircount,
            'id_model'=>$request->id,
            'mean'=>$mean,
            'max'=>$max,
            'min'=>$min,
            'std'=>$std,
            'mean3'=>$mean3,
            'mean4'=>$mean4,
            'mean4_5'=>$mean4_5,
            'mean6'=>$mean6,
        ]);
    }


}
