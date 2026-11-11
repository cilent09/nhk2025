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
use slope;

class ResultsrawControlle extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function outputrawgraph(Request $request)
        {
          
            $oqa = DB::table('nt_oqa_lab')
            ->where('id_model',$request->input('id'))
            ->get();
            $oqalot = DB::table('nt_oqa_lab')
            ->where('id_createlab',$request->input('idcreatelab'))
            ->first();
            $oqadate = DB::table('nt_v_data_lpc')
            ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_lpc.id_createlab')
            ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
            ->where('nt_v_data_lpc.id_createlab',$request->input('idcreatelab'))->where('nt_v_data_lpc.lpc_st',1)
            ->first(); 
            $oqadatesem = DB::table('nt_v_data_sem')
            ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_sem.id_createlab')
            ->where('nt_v_data_sem.id_createlab',$request->input('idcreatelab'))->where('nt_v_data_sem.sem_st',1)
            ->first();
            
            $oqadateic = DB::table('nt_v_data_ic')
            ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_ic.id_createlab')
            ->where('nt_v_data_ic.id_createlab',$request->input('idcreatelab'))->where('nt_v_data_ic.ic_st',1)
            ->first();

            $oqadategcms = DB::table('nt_v_data_gcms')
            ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_gcms.id_createlab')
            ->where('nt_v_data_gcms.id_createlab',$request->input('idcreatelab'))->where('nt_v_data_gcms.gcms_st',1)
            ->first();
    
            $oqadateftir = DB::table('nt_v_data_ftir')
            ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_ftir.id_createlab')
            ->where('nt_v_data_ftir.id_createlab',$request->input('idcreatelab'))->where('nt_v_data_ftir.ftir_st',1)
            ->first();

            $oqadatesemtest = DB::table('nt_datacf_semtest')
            ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_datacf_semtest.id_createlab')
            ->where('nt_datacf_semtest.id_createlab',$request->input('idcreatelab'))->where('nt_datacf_semtest.semtest_st',1)
            ->first();

            $chkoqadate = 0;
            $chkoqadatesem = 0;
            $chkoqadategcms = 0;
            $chkoqadateftir = 0;
            
            if($request->chkpdf == 1)
            {
               
                if(isset($request->lpccount))
                {
                    if($request->lpccount < 1)
                    {
                        $chkoqadate = 0;
                        $oqadate = null;
                    }
                    else
                    {
                        $chkoqadate = 1;
                        $oqadate = DB::table('nt_v_data_lpc')
                        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_lpc.id_createlab')
                        ->first(); 
                    }
                }
                if(isset($request->semcount))
                {
                   // dd($request->semcount);
                    if($request->semcount < 1)
                    {
                        $chkoqadatesem = 0;
                        $oqadatesem = null;
                   
                    }
                    else
                    {
                  
                        $chkoqadatesem = 1;
                        $oqadatesem = DB::table('nt_v_data_sem')
                        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_sem.id_createlab')
                        ->first();
                    }
                }
                if(isset($request->iccount))
                {
                    if($request->iccount < 1)
                    {
                        $chkoqadateic = 0;
                        $oqadateic = null;
                    }
                    else
                    {
                        $chkoqadateic = 1;
                        $oqadateic = DB::table('nt_v_data_ic')
                        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_ic.id_createlab')
                        ->first();
                    }
                }
                else
                {
                    $chkoqadateic = 1;
                }
                if(isset($request->gcmscount))
                {
                    if($request->gcmscount < 1)
                    {
                        $chkoqadategcms = 0;
                        $oqadategcms = null;
                    }
                    else
                    {
                        $chkoqadategcms = 1;
                        $oqadategcms = DB::table('nt_v_data_gcms')
                        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_gcms.id_createlab')
                        ->first();
                    }
                }
           

                if(isset($request->ftircount))
                {
                    if($request->ftircount < 1)
                    {
                        $chkoqadateftir = 0;
                        $oqadateftir = null;
                    }
                    else
                    {
                        $chkoqadateftir = 1;
                        $oqadateftir = DB::table('nt_v_data_ftir')
                        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_ftir.id_createlab')
                        ->first();
                    }
                }
            }
            else
            {
                $chkoqadate = 1;
                $chkoqadatesem = 1;
                $chkoqadateic = 1;
                $chkoqadategcms = 1;
                $chkoqadateftir = 1;
            }

            
        if($request->chkpdf == 2)
        {

            $modelpara = DB::table('nt_model')
            ->join('nt_model_parameter','nt_model_parameter.id_model','=','nt_model.id_model')
            ->where('nt_model.id_model',$request->input('id'))
            ->where('nt_model_parameter.id_model_p',1)
            ->first();
                    if($modelpara->lpc == 1)
                    { 
                     $oqadate = DB::table('nt_v_data_lpc')
                    ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_lpc.id_createlab')
                    ->first(); 
                    }
                    else
                    {
                        $oqadate = null;
                    }
                    if($modelpara->sem == 1)
                    { 
                        $oqadatesem = DB::table('nt_v_data_sem')
                        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_sem.id_createlab')
                        ->first();
                    }
                    else
                    {
                        $oqadatesem = null;
                    }
                    if($modelpara->ic == 1)
                    { 
                        $oqadateic = DB::table('nt_datacf_ic')
                        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_datacf_ic.id_createlab')
                        ->first();
                    }
                    else
                    {
                        $oqadateic = null;
                    }
                    if($modelpara->gcms == 1)
                    {  
                    $oqadategcms = DB::table('nt_v_data_gcms')
                    ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_gcms.id_createlab')
                    ->first();
                    }
                    else
                    {
                        $oqadategcms = null;
                    }

                    if($modelpara->ftir == 1)
                    {  
                        $oqadateftir = DB::table('nt_v_data_ftir')
                        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_ftir.id_createlab')
                        ->first();
                    }
                    else
                    {
                        $oqadateftir = null;
                    }
                  
        }
           
            $modelname = DB::table('nt_model')
            ->where('id_model',$request->input('id'))
            ->first();
    
            $modelparameter = DB::table('nt_model_parameter')
            ->where('id_model',$request->input('id'))
            ->where('id_model_p',1)
            ->first();
    
            $oqasum = DB::table('nt_model')
            ->where('id_model',$request->input('id'))
            ->first();
             
            $model = DB::table('nt_model_spec')
            ->join('nt_model_spec_type','nt_model_spec_type.id_model_type','=','nt_model_spec.id_model_type')
            ->where('nt_model_spec.id_model',$request->input('id'))
            ->get();
            $datalabdone = DB::table('nt_oqa_lab')
            ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
            ->where('nt_oqa_lab.id_createlab',$request->input('idcreatelab'))->first();
            $sp = DB::table('nt_model_spec')
            ->join('nt_model_spec_type','nt_model_spec_type.id_model_type','=','nt_model_spec.id_model_type')
            ->where('nt_model_spec.id_model',$request->input('id'))
            ->where('nt_model_spec.id_model_type',1)->first();
    
            $tl = DB::table('nt_model_spec')
            ->join('nt_model_spec_type','nt_model_spec_type.id_model_type','=','nt_model_spec.id_model_type')
            ->where('nt_model_spec.id_model',$request->input('id'))
            ->where('nt_model_spec.id_model_type',2)->first();
    
            $ucl = DB::table('nt_model_spec')
            ->join('nt_model_spec_type','nt_model_spec_type.id_model_type','=','nt_model_spec.id_model_type')
            ->where('nt_model_spec.id_model',$request->input('id'))
            ->where('nt_model_spec.id_model_type',3)->first();
    
            $lcl = DB::table('nt_model_spec')
            ->join('nt_model_spec_type','nt_model_spec_type.id_model_type','=','nt_model_spec.id_model_type')
            ->where('nt_model_spec.id_model',$request->input('id'))
            ->where('nt_model_spec.id_model_type',4)->first();
    
            $cl = DB::table('nt_model_spec')
            ->join('nt_model_spec_type','nt_model_spec_type.id_model_type','=','nt_model_spec.id_model_type')
            ->where('nt_model_spec.id_model',$request->input('id'))
            ->where('nt_model_spec.id_model_type',5)->first();
    
            $is = DB::table('nt_model_spec')
            ->join('nt_model_spec_type','nt_model_spec_type.id_model_type','=','nt_model_spec.id_model_type')
            ->where('nt_model_spec.id_model',$request->input('id'))
            ->where('nt_model_spec.id_model_type',6)->first();

            $dataalarm = DB::table('nt_alarm')->where('alarm_id',1)->first();
            $alarmtest2 = $dataalarm->test2;
            $alarmtest3 = $dataalarm->test3;
            $alarmtest3y = $dataalarm->test3+1;
    
            if(!empty($oqadate))
            {
        //-------------------------------------lpc----------------------------------------//
       //------------------------------- slope dff. lpc ----------------------------------//
      if($request->chkpdf == 0)
      {
        $yeardown = date('Y-m-d 00:00:00', strtotime($oqadate->save_date. ' - 1 year'));
        $dateend = $oqadate->save_date;
      }
      else if($request->chkpdf == 2)
    {
      $datenow = date("Y-m-d H:i:s");
      $yeardown = date('Y-m-d 00:00:00', strtotime($datenow. ' - 1 year'));
      $dateend = $datenow;
    }
      else
      {
        $yeardown = date('Y-m-d 00:00:00', strtotime($request->datestart));
        $dateend = date('Y-m-d 00:00:00', strtotime($request->dateend. ' + 1 day'));
        
      }
      //dd($request->chkpdf);
       $lpcdff = DB::table('nt_v_data_lpc')
       ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_lpc.id_createlab')
       ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
       ->where('nt_oqa_lab.save_date','<',$dateend)
       ->where('nt_oqa_lab.save_date','>=',$yeardown)
       ->where('nt_v_data_lpc.lpc_data','!=',null)
       ->where('nt_oqa_lab.id_model',$request->input('id'));
       if($lpcdff->count() < 10 && $lpcdff->count() != 0)
       {
        $lpcdfflimit = DB::table('nt_v_data_lpc')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_lpc.id_createlab')
        ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
        ->where('nt_oqa_lab.save_date','<',$dateend)
        ->where('nt_v_data_lpc.lpc_data','!=',null)
        ->where('nt_oqa_lab.id_model',$request->input('id'))->orderby('nt_oqa_lab.save_date','desc')->limit(10)->get(); 
        $countdfflimit = $lpcdfflimit->count() -1 ;
        $lpcdff = DB::table('nt_v_data_lpc')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_lpc.id_createlab')
        ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
        ->where('nt_oqa_lab.save_date','<',$dateend)
        ->where('nt_oqa_lab.save_date','>=',$lpcdfflimit[$countdfflimit]->save_date)
        ->where('nt_v_data_lpc.lpc_data','!=',null)
        ->where('nt_oqa_lab.id_model',$request->input('id'))->limit(10);
       }

       $avgsdff = $lpcdff->avg('lpc_v_data');
       $std_dff = $lpcdff->select(DB::raw('STDDEV_SAMP(nt_v_data_lpc.lpc_v_data) as std'))->first();
        if($std_dff->std == 0)
        {
          $dffck = null;
          $dfflpc = null;
        }
        else
        {
            $dffck = ($oqadate->lpc_v_data - $avgsdff) / $std_dff->std;
            $dfflpc = number_format($dffck,3);
        }   
       $lpcslope = DB::table('nt_v_data_lpc')
       ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_lpc.id_createlab')
       ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
       ->where('nt_oqa_lab.save_date','<=',$dateend)
       ->where('nt_oqa_lab.save_date','>=',$yeardown)
       ->where('nt_oqa_lab.lpc_confirm_st',1)
       ->where('nt_v_data_lpc.lpc_data','!=',null)
       ->where('nt_oqa_lab.id_model',$request->input('id'));   
       $lpcslopedff7 = DB::table('nt_v_data_lpc')
       ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_lpc.id_createlab')
       ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
       ->where('nt_oqa_lab.save_date','<=',$dateend)
       ->where('nt_oqa_lab.save_date','>=',$yeardown)
       ->where('nt_oqa_lab.lpc_confirm_st',1)
       ->where('nt_v_data_lpc.lpc_data','!=',null)
       ->where('nt_oqa_lab.id_model',$request->input('id'))->OrderBy('nt_oqa_lab.save_date','desc')->limit(7)->get();
       if($lpcslope->count() < 11 && $lpcslope->count() != 0)
       {
          
        $lpcslopelimit = DB::table('nt_v_data_lpc')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_lpc.id_createlab')
        ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
        ->where('nt_oqa_lab.save_date','<=',$dateend)
        ->where('nt_oqa_lab.lpc_confirm_st',1)
        ->where('nt_v_data_lpc.lpc_data','!=',null)
        ->where('nt_oqa_lab.id_model',$request->input('id'))->orderby('nt_oqa_lab.save_date','desc')->limit(11)->get(); ; 
        $countslopelimit = $lpcslopelimit->count() -1 ;
        $lpcslope = DB::table('nt_v_data_lpc')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_lpc.id_createlab')
        ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
        ->where('nt_oqa_lab.save_date','<=',$dateend)
        ->where('nt_oqa_lab.save_date','>=',$lpcslopelimit[$countslopelimit]->save_date)
        ->where('nt_oqa_lab.lpc_confirm_st',1)
        ->where('nt_v_data_lpc.lpc_data','!=',null)
        ->where('nt_oqa_lab.id_model',$request->input('id')); 
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
        ->where('nt_oqa_lab.id_model',$request->input('id'))->OrderBy('nt_oqa_lab.save_date','desc')->limit(7)->get();
       }
       $avgslope = $lpcslope->avg('lpc_data');
       $countlpc = $lpcslope->count('lpc_data');
       $std_s = $lpcslope->select(DB::raw('STDDEV_SAMP(nt_v_data_lpc.lpc_v_data) as std'))->first();
       $stdlpc = number_format($std_s->std,3);
       $slopepush=array();
       foreach($lpcslopedff7 as $slope)
       {
        if($std_s->std == 0)
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
        $arrayck = sizeof($slopepush)-1;  
        $slopelpcfc = abs($linearrelpc); 
        $slopelpc = number_format($linearrelpc,3);
       }
        //------------------------------- end slope dff. lpc----------------------------------//
            $datalab = DB::table('nt_datacf_lpc')
            ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_datacf_lpc.id_createlab')
            ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
            ->where('nt_oqa_lab.save_date','<=',$dateend)
            ->where('nt_oqa_lab.id_model',$request->input('id'))
            ->where('nt_oqa_lab.lpc_confirm_st',1)
            ->where('nt_datacf_lpc.lpc_data','!=',null)
            ->limit($alarmtest2)->orderBy('nt_oqa_lab.save_date', 'desc')->get();
            $datalaball = DB::table('nt_datacf_lpc')
            ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_datacf_lpc.id_createlab')
            ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
            ->where('nt_oqa_lab.save_date','<=',$dateend)
            ->where('nt_oqa_lab.id_model',$request->input('id'))
            ->where('nt_oqa_lab.lpc_confirm_st',1)
            ->where('nt_datacf_lpc.lpc_data','!=',null);

            if($request->chkpdf == 0 || $request->chkpdf == 2 )
            {
                $datalaball = $datalaball->limit(intval($oqasum->alpc_sum))->orderBy('nt_oqa_lab.save_date', 'desc')->get();
            }
            else
            {
                $datalaball =  $datalaball->where('nt_oqa_lab.save_date','>=',$yeardown)->orderBy('nt_oqa_lab.save_date', 'desc')->get();
            }
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
                
                $data = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$data1->date_start)  
                ->where('nt_model_history.date_end','>=',$data1->date_start)
                ->get(); 
               }
                array_push($newlpcdatalab,$data);
            }
            $datalabvs3 = DB::table('nt_datacf_lpc')
            ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_datacf_lpc.id_createlab')
            ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
            ->where('nt_oqa_lab.save_date','<=',$dateend)
            ->where('nt_oqa_lab.save_date','>=',$yeardown)
            ->where('nt_oqa_lab.id_model',$request->input('id'))
            ->where('nt_oqa_lab.lpc_confirm_st',1)
            ->where('nt_datacf_lpc.lpc_data','!=',null)
            ->limit($alarmtest3y)->orderBy('nt_oqa_lab.save_date', 'desc')->get();
            $higher = 0;
            $below = 0;
            $countdatalab = count($datalab);
            $countsortdatalab = count($datalabvs3);

            foreach ( $datalab as $item){ 
                $stdate = date('Y-m-d', strtotime($item->date_lpc));
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
     
            $highervs3 = 0;
            $belowvs3 = 0;
            $iss = 0;
            if($countsortdatalab >= $alarmtest3y){
                $datalablpc = $datalabvs3[0]->lpc_data;
               $a=array();
            foreach ( $datalabvs3 as $item){ 
                $iss++;
                if( $iss <= $alarmtest3)
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
            //-------------------------------------end lpc-------------------------------------//
            $chklpcshow = 1;
        }
        else
        {
            $datalaball = null;
            $datalab = null;
                 $higher =null;
                 $below=null;
                 $highervs3=null;
                 $belowvs3=null;
                 $slopelpc=null;
                 $dfflpc=null;
                 $avgslope=null;
                 $stdlpc=null;
                 $countlpc=null;
                 $chklpcshow = null; 
                 $newlpcdatalab = null;
                
        }
       //------------------------------------- sem -------------------------------------//
       if(!empty($oqadatesem))
       {
    
        if($request->chkpdf == 0)
        {
           // dd($oqadatesem->save_date);
          $yeardown = date('Y-m-d 00:00:00', strtotime($oqadatesem->save_date. ' - 1 year'));
          $dateend = $oqadatesem->save_date;
        }
        else if($request->chkpdf == 2)
        {
        $datenow = date("Y-m-d H:i:s");
        $yeardown = date('Y-m-d 00:00:00', strtotime($datenow. ' - 1 year'));
        $dateend = $datenow;
        }
        else
        {
          $yeardown = date('Y-m-d 00:00:00', strtotime($request->datestart));
          $dateend = date('Y-m-d 00:00:00', strtotime($request->dateend. ' + 1 day'));;
        } 

       $semdatalab = DB::table('nt_datacf_sem')
       ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_datacf_sem.id_createlab')
       ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
       ->where('nt_oqa_lab.save_date','<=',$dateend)
       ->where('nt_oqa_lab.id_model',$request->input('id'))
       ->where('nt_oqa_lab.sem_confirm_st',1)
       ->limit($alarmtest2)->orderBy('nt_oqa_lab.save_date', 'desc')->get();
       
       $semdatalaball = DB::table('nt_datacf_sem')
       ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_datacf_sem.id_createlab')
       ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
       ->where('nt_oqa_lab.save_date','<=',$dateend)
       ->where('nt_oqa_lab.id_model',$request->input('id'))
       ->where('nt_oqa_lab.sem_confirm_st',1);

       if($request->chkpdf == 0 || $request->chkpdf == 2)
       {
           //$datalaball = $datalaball->limit(intval($oqasum->alpc_sum))->orderBy('nt_oqa_lab.save_date', 'desc')->get();
           $semdatalaball =  $semdatalaball->limit(intval($oqasum->sem_sum))->orderBy('nt_oqa_lab.save_date', 'desc')->get();
       }
       else
       {
           $semdatalaball =  $semdatalaball->where('nt_oqa_lab.save_date','>=',$yeardown)->orderBy('nt_oqa_lab.save_date', 'desc')->get();
       }

       $newsemdatalab=array();
       foreach ($semdatalaball as $item){     
           $stdate = date('Y-m-d', strtotime($item->save_date));
           $data = DB::table('nt_model_history')
          ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
          ->where('nt_model_history.id_model', $item->id_model)
          ->where('nt_model_history.date_start','<=',$stdate)
          ->where('nt_model_history.date_end','>=',$stdate)
          ->where('nt_model_history.chkstatus',1)
          ->get(); 
          if($data->count() == 0)
          {
            $data1 = DB::table('nt_model_history')
            ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
            ->where('nt_model_history.id_model', $item->id_model)
            ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
            ->first();
            
            $data = DB::table('nt_model_history')
            ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
            ->where('nt_model_history.id_model', $item->id_model)
            ->where('nt_model_history.date_start','<=',$data1->date_start)  
            ->where('nt_model_history.date_end','>=',$data1->date_start)
            ->get(); 
          }
           array_push($newsemdatalab,$data);
       }
       
       
    
       $semdatalabvs3 = DB::table('nt_datacf_sem')
       ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_datacf_sem.id_createlab')
       ->where('nt_oqa_lab.save_date','<=',$dateend)
       ->where('nt_oqa_lab.id_model',$request->input('id'))
       ->where('nt_oqa_lab.sem_confirm_st',1)
       ->limit($alarmtest3y)->orderBy('nt_oqa_lab.save_date', 'desc')->get();
       $countsemdatalabvs3 = count($semdatalabvs3);
    
       $semdff = DB::table('nt_v_data_sem')
       ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_sem.id_createlab')
       ->where('nt_oqa_lab.save_date','<',$dateend)
      ->where('nt_oqa_lab.save_date','>=',$yeardown)
       ->where('nt_oqa_lab.sem_confirm_st',1)
       ->where('nt_oqa_lab.id_model',$request->input('id'));
       // dd($dateend.'<>'.$yeardown);
       if($semdff->count() < 10 && $semdff->count() != 0)
       {
        $semdfflimit = DB::table('nt_v_data_sem')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_sem.id_createlab')
        ->where('nt_oqa_lab.save_date','<',$dateend)
        ->where('nt_oqa_lab.sem_confirm_st',1)
        ->where('nt_oqa_lab.id_model',$request->input('id'))->where('nt_v_data_sem.sem_st',1)->orderby('nt_oqa_lab.save_date','desc')->limit(10)->get(); 
        $countdfflimit = $semdfflimit->count() -1 ;
        $semdff = DB::table('nt_v_data_sem')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_sem.id_createlab')
        ->where('nt_oqa_lab.save_date','<',$dateend)
        ->where('nt_oqa_lab.save_date','>=',$semdfflimit[$countdfflimit]->save_date)
        ->where('nt_oqa_lab.sem_confirm_st',1)
        ->where('nt_oqa_lab.id_model',$request->input('id'))->where('nt_v_data_sem.sem_st',1)->limit(10);
       }
      // dd( $semdff->get());

       $semslope = DB::table('nt_v_data_sem')
       ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_sem.id_createlab')
       ->where('nt_oqa_lab.save_date','<=',$dateend)
       ->where('nt_oqa_lab.save_date','>=',$yeardown)
       ->where('nt_oqa_lab.sem_confirm_st',1)
       ->where('nt_oqa_lab.id_model',$request->input('id'));
       $semslopedff7 = DB::table('nt_v_data_sem')
       ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_sem.id_createlab')
       ->where('nt_oqa_lab.save_date','<=',$dateend)
       ->where('nt_oqa_lab.save_date','>=',$yeardown)
       ->where('nt_oqa_lab.sem_confirm_st',1)
       ->where('nt_oqa_lab.id_model',$request->input('id'))->OrderBy('nt_oqa_lab.save_date','desc')->limit(7)->get();

       if($semslope->count() < 11 && $semslope->count() != 0)
       {    
        $semslopelimit = DB::table('nt_v_data_sem')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_sem.id_createlab')
        ->where('nt_oqa_lab.save_date','<=',$dateend)
        ->where('nt_oqa_lab.sem_confirm_st',1)
        ->where('nt_oqa_lab.id_model',$request->input('id'))->orderby('nt_oqa_lab.save_date','desc')->limit(11)->get(); 
        $countslopelimit = $semslopelimit->count() -1 ;
        $semslope = DB::table('nt_v_data_sem')
       ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_sem.id_createlab')
       ->where('nt_oqa_lab.save_date','<=',$dateend)
       ->where('nt_oqa_lab.save_date','>=',$semslopelimit[$countslopelimit]->save_date)
       ->where('nt_oqa_lab.sem_confirm_st',1)
       ->where('nt_oqa_lab.id_model',$request->input('id'));
       $countslopelimit1 = $semslopelimit->count();
        if($countslopelimit1 <7)
        {
            $numx =  $countslopelimit ;
        }
        else
        {
            $numx = 6;
        }
       $semslopedff7 = DB::table('nt_v_data_sem')
       ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_sem.id_createlab')
       ->where('nt_oqa_lab.save_date','<=',$dateend)
       ->where('nt_oqa_lab.save_date','>=',$semslopelimit[$numx]->save_date)
       ->where('nt_oqa_lab.sem_confirm_st',1)
       ->where('nt_oqa_lab.id_model',$request->input('id'))->OrderBy('nt_oqa_lab.save_date','desc')->limit(7)->get();
       }
      // $semsortslopedff7 = $semslopedff7->sortBy('sem_datesave');     
      $countdatalab = count($semdatalab);
      $countsortdatalab = count($semslopedff7);
        //-------------------------------------semclass1-------------------------------------//
    
           //------------------------------- slope dff. semclass1 ----------------------------------//
           $avgsdff = $semdff->avg('sem_v_class1');
           $std_dff = $semdff->select(DB::raw('STDDEV_SAMP(nt_v_data_sem.sem_v_class1) as std'))->first();
          
           if($std_dff->std == 0)
           {
           $dffck = null;
           $dffsem1 = null;
           }
           else
           {
             
            $dffck = ($oqadatesem->sem_v_class1 - $avgsdff) / $std_dff->std;
            $dffsem1 = number_format($dffck,3);
           }
          
        
            $avgsem1 = $semslope->avg('sem_v_class1');
            $countsem1 = $semslope->count('sem_v_class1');
            $std_s = $semslope->select(DB::raw('STDDEV_SAMP(nt_v_data_sem.sem_v_class1) as std'))->first();
            $stdsem1 = number_format($std_s->std,3);
            $slopepush=array();
             
            foreach($semslopedff7 as $slope)
            {
                //dd($semslopedff7);
                if($std_s->std == 0)
                {
                    $slopest = ($slope->sem_v_class1 - $avgsem1)/1;
                }
                else
                {
                    $slopest = ($slope->sem_v_class1 - $avgsem1)/$std_s->std;
                }
                array_push($slopepush,$slopest);
            }
            if(count($semslopedff7) < 7)
            {
              
             $slopesem1 = null;
            }
            else
            {
                $linearresem = slope::linearreg_slope($slopepush,sizeof($slopepush));
                $arrayck = sizeof($slopepush)-1;
                $slopefcsem1 = abs($linearresem);
                $slopesem1 = number_format($linearresem,3); 
            }
            
            //------------------------------- end slope dff. semclass1----------------------------------// 
            $sem1higher = 0;
            $sem1below = 0;

                foreach ( $semdatalab as $item){ 

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
                    if($datafirst->class1 == "N/A" )
                    {
                        $natbd = 0;
                    }
                    else if($datafirst->class1 == "TBD")
                    {
                        $natbd = 0;
                    }
                    else
                    {
                        $natbd = 1;
                    }
                    if($natbd == 1)
                    {
                        if ($datafirst->class1 < $item->sem_class1) {
                            $sem1higher++;        
                        } 
                        else if ($datafirst->class1 > $item->sem_class1) {
                            $sem1below++;      
                        } 
                        else {
                            $sem1higher--;
                            $sem1below--;
                        }
                    }
                }
       
            $sem1highervs3 = 0;
            $sem1belowvs3 = 0;
            $datalabsem1 = $semdatalabvs3[0]->sem_class1;
            $iss = 0;
            if($countsortdatalab >= $alarmtest3y){         
            foreach ( $semdatalabvs3 as $item){ 
                $iss++;
                if( $iss <= $alarmtest3)
                {
                   
                if ($datalabsem1 < $semdatalabvs3[$iss]->sem_class1) {
                    $sem1highervs3++;
                    $datalabsem1 = $semdatalabvs3[$iss]->sem_class1;
                } 
                else if ($datalabsem1 > $semdatalabvs3[$iss]->sem_class1) {
                    $sem1belowvs3++;
                    $datalabsem1 = $semdatalabvs3[$iss]->sem_class1;
                } 
                else {
                    $sem1highervs3--;
                    $sem1belowvs3--;
                }
           
                }
            }
           }
         
        //-------------------------------------end semclass1 -------------------------------------//
        
         //-------------------------------------semclass2 lpc-------------------------------------//
    
              //------------------------------- slope dff. semclass2 ----------------------------------//
              $avgsdff = $semdff->avg('sem_v_class2');
              $std_dff = $semdff->select(DB::raw('STDDEV_SAMP(nt_v_data_sem.sem_v_class2) as std'))->first();
              if($std_dff->std == 0)
              {
              $dffck = null;
              $dffsem2 = null; 
              }
              else
              {
                $dffck = ($oqadatesem->sem_v_class2 - $avgsdff) / $std_dff->std;
                $dffsem2 = number_format($dffck,3); 
              }
              
               $avgsem2 = $semslope->avg('sem_v_class2');
               $countsem2 = $semslope->count('sem_v_class2');
               $std_s = $semslope->select(DB::raw('STDDEV_SAMP(nt_v_data_sem.sem_v_class2) as std'))->first();
               $stdsem2 = number_format($std_s->std,3);
               $slopepush=array();
               foreach($semslopedff7 as $slope)
               {
                if($std_s->std == 0)
                {
                    $slopest = ($slope->sem_v_class2 - $avgsem2)/1;
                }
                else
                {
                    $slopest = ($slope->sem_v_class2 - $avgsem2)/$std_s->std;
                }
                   
                   array_push($slopepush,$slopest);
               }
    
                if(count($semslopedff7) < 7)
                {
                $slopesem2 = null;
                }
                else
                {
                    $linearresem = slope::linearreg_slope($slopepush,sizeof($slopepush));
                    $arrayck = sizeof($slopepush)-1;
                    $slopefcsem2 = abs($linearresem);
                    $slopesem2 = number_format($linearresem,3);
                }
               
               //------------------------------- end slope dff. semclass2----------------------------------//
       $sem2higher = 0;
       $sem2below = 0;
       //dd($model[4]->class2);
        foreach ( $semdatalab as $item){
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
            if($datafirst->class2 == "N/A" )
            {
                $natbd = 0;
            }
            else if($datafirst->class2 == "TBD")
            {
                $natbd = 0;
            }
            else
            {
                $natbd = 1;
            }
            if($natbd == 1)
            {
                if ($datafirst->class2 < $item->sem_class2) {
                    $sem2higher++;
                    
                } 
                else if ($datafirst->class2 > $item->sem_class2) {
                    $sem2below++;
                
                } 
                else {
                    $sem2higher--;
                    $sem2below--;
                }
            }
        }
  
       $sem2highervs3 = 0;
       $sem2belowvs3 = 0;
       if($countsortdatalab >= $alarmtest3y){
       $datalabsem2 = $semdatalabvs3[0]->sem_class2;
       $iss = 0;
       foreach ( $semdatalabvs3 as $item){ 
           $iss++;
           if( $iss <= $alarmtest3)
           {
           if ($datalabsem2 < $semdatalabvs3[$iss]->sem_class2) {
               $sem2highervs3++;
               $datalabsem2 = $semdatalabvs3[$iss]->sem_class2;
           } 
           else if ($datalabsem2 > $semdatalabvs3[$iss]->sem_class2) {
               $sem2belowvs3++;
               $datalabsem2 = $semdatalabvs3[$iss]->sem_class2;
           } 
           else {
               $sem2highervs3--;
               $sem2belowvs3--;
           }
          }
       }
    }
 
        //-------------------------------------end semclass2 -------------------------------------//
        //-------------------------------------semclass3 -------------------------------------//
               //------------------------------- slope dff. semclass3 ----------------------------------//
               $avgsdff = $semdff->avg('sem_v_class3');
               $std_dff = $semdff->select(DB::raw('STDDEV_SAMP(nt_v_data_sem.sem_v_class3) as std'))->first();
               if($std_dff->std == 0)
               {
               $dffck = null;
               $dffsem3 = null;
               }
               else
               {
                $dffck = ($oqadatesem->sem_v_class3 - $avgsdff) / $std_dff->std;
                $dffsem3 = number_format($dffck,3);
               }
              
                $avgsem3 = $semslope->avg('sem_v_class3');
                $countsem3 = $semslope->count('sem_v_class3');
                $std_s = $semslope->select(DB::raw('STDDEV_SAMP(nt_v_data_sem.sem_v_class3) as std'))->first();
                $stdsem3 = number_format($std_s->std,3);
                $slopepush=array();
            
                foreach($semslopedff7 as $slope)
                {
                    if($std_s->std == 0)
                    {
                        $slopest = ($slope->sem_v_class3 - $avgsem3)/1;
                    }
                    else
                    {
                        $slopest = ($slope->sem_v_class3 - $avgsem3)/$std_s->std;
                    }
                    
                    array_push($slopepush,$slopest);
                }           
                 if(count($semslopedff7) < 7)
                 {
                 $slopesem3 = null;
                 }
                 else
                 {
                    $linearresem = slope::linearreg_slope($slopepush,sizeof($slopepush));
                    $arrayck = sizeof($slopepush)-1;
                    $slopefcsem3 = abs($linearresem);
                    $slopesem3 = number_format($linearresem,3);
                 }
                //------------------------------- end slope dff. semclass3----------------------------------//
       $sem3higher = 0;
       $sem3below = 0;

        foreach ( $semdatalab as $item){ 
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
            if($datafirst->class3 == "N/A" )
            {
                $natbd = 0;
            }
            else if($datafirst->class3 == "TBD")
            {
                $natbd = 0;
            }
            else
            {
                $natbd = 1;
            }
            if($natbd == 1)
            {
                if ($datafirst->class3 < $item->sem_class3) {
                    $sem3higher++;
                    
                } 
                else if ($datafirst->class3 > $item->sem_class3) {
                    $sem3below++;
                
                } 
                else {
                    $sem3higher--;
                    $sem3below--;
                }
            }
        }

       $sem3highervs3 = 0;
       $sem3belowvs3 = 0;
       if($countsortdatalab >= $alarmtest3y){
       $datalabsem3 = $semdatalabvs3[0]->sem_class3;
       $iss = 0;
       foreach ( $semdatalabvs3 as $item){ 
           $iss++;
           if( $iss <= $alarmtest3)
           {
           if ($datalabsem3 < $semdatalabvs3[$iss]->sem_class3) {
               $sem3highervs3++;
               $datalabsem3 = $semdatalabvs3[$iss]->sem_class3;
           } 
           else if ($datalabsem3 > $semdatalabvs3[$iss]->sem_class3) {
               $sem3belowvs3++;
               $datalabsem3 = $semdatalabvs3[$iss]->sem_class3;
           } 
           else {
               $sem3highervs3--;
               $sem3belowvs3--;
           }
          }
       }
    }
       //------------------------------------- end semclass3 ---------------------------------------// 
       //------------------------------------- semclass3.1 ----------------------------------------//
       //------------------------------- slope dff. semclass3.1 ----------------------------------//
                $avgsdff = $semdff->avg('sem_v_class3_1');
                $std_dff = $semdff->select(DB::raw('STDDEV_SAMP(nt_v_data_sem.sem_v_class3_1) as std'))->first();
               
                if($std_dff->std == 0)
                {
                $dffck = null;
                $dffsem3_1 = null;

                }
                else
                {
                    $dffck = ($oqadatesem->sem_v_class3_1 - $avgsdff) / $std_dff->std;
                    $dffsem3_1 = number_format($dffck,3);
                }
                
                 $avgsem3_1 = $semslope->avg('sem_v_class3_1');
                 $countsem3_1 = $semslope->count('sem_v_class3_1');
                 $std_s = $semslope->select(DB::raw('STDDEV_SAMP(nt_v_data_sem.sem_v_class3_1) as std'))->first();
                 $stdsem3_1 = number_format($std_s->std,3);
                 $slopepush=array();
                 foreach($semslopedff7 as $slope)
                 {
                    if($std_s->std == 0)
                    {
                        $slopest = ($slope->sem_v_class3_1 - $avgsem3_1)/1;
                        
                    }
                    else
                    {
                        $slopest = ($slope->sem_v_class3_1 - $avgsem3_1)/$std_s->std;
                    }
                     array_push($slopepush,$slopest);
                 }   
                  if(count($semslopedff7) < 7)
                  {
                  $slopesem3_1 = null;
                  }
                  else
                  {
                    $linearresem = slope::linearreg_slope($slopepush,sizeof($slopepush));
                    $arrayck = sizeof($slopepush)-1;
                    $slopefcsem3_1 = abs($linearresem);
                    $slopesem3_1 = number_format($linearresem,3);
                  }
         
                 //------------------------------- end slope dff. semclass3.1----------------------------------//
        $sem31higher = 0;
        $sem31below = 0;

            foreach ( $semdatalab as $item){ 
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
                if($datafirst->class3_1 == "N/A" )
                {
                    $natbd = 0;
                }
                else if($datafirst->class3_1 == "TBD")
                {
                    $natbd = 0;
                }
                else
                {
                    $natbd = 1;
                }
                if($natbd == 1)
                {
                    if ($datafirst->class3_1 < $item->sem_class3_1) {
                        $sem31higher++;
                        
                    } 
                    else if ($datafirst->class3_1 > $item->sem_class3_1) {
                        $sem31below++;
                    
                    } 
                    else {
                        $sem31higher--;
                        $sem31below--;
                    }
                }
            }
     

        $sem31highervs3 = 0;
        $sem31belowvs3 = 0;
        if($countsortdatalab >= $alarmtest3y){
        $datalabsem31 = $semdatalabvs3[0]->sem_class3_1;
        $iss = 0;
        foreach ( $semdatalabvs3 as $item){ 
            $iss++;
            if( $iss <= $alarmtest3)
            {
            if ($datalabsem31 < $semdatalabvs3[$iss]->sem_class3_1) {
                $sem31highervs3++;
                $datalabsem31 = $semdatalabvs3[$iss]->sem_class3_1;
            } 
            else if ($datalabsem31 > $semdatalabvs3[$iss]->sem_class3_1) {
                $sem31belowvs3++;
                $datalabsem31 = $semdatalabvs3[$iss]->sem_class3_1;
            } 
            else {
                $sem31highervs3--;
                $sem31belowvs3--;
            }
           }
        }
    }
        //-------------------------------------end semclass3.1 -------------------------------------//
        //-------------------------------------semclass3.2-------------------------------------//
        //------------------------------- slope dff. semclass3.2 ----------------------------------//
                     $avgsdff = $semdff->avg('sem_v_class3_2');
                     $std_dff = $semdff->select(DB::raw('STDDEV_SAMP(nt_v_data_sem.sem_v_class3_2) as std'))->first();
                     if($std_dff->std ==0)
                     {
                        $dffck = null;
                        $dffsem3_2 = null;
                     }
                     else
                     {
                        $dffck = ($oqadatesem->sem_v_class3_2 - $avgsdff) / $std_dff->std;
                        $dffsem3_2 = number_format($dffck,3);
                     }
                    
                      $avgsem3_2 = $semslope->avg('sem_v_class3_2');
                      $countsem3_2 = $semslope->count('sem_v_class3_2');
                      $std_s = $semslope->select(DB::raw('STDDEV_SAMP(nt_v_data_sem.sem_v_class3_2) as std'))->first();
                      $stdsem3_2 = number_format($std_s->std,3);
                      $slopepush=array();
                  
                      foreach($semslopedff7 as $slope)
                      {
                        if($std_s->std == 0)
                        {
                            $slopest = ($slope->sem_v_class3_2 - $avgsem3_2)/1;
                        }
                        else
                        {
                            $slopest = ($slope->sem_v_class3_2 - $avgsem3_2)/$std_s->std;
                        }
                         
                          array_push($slopepush,$slopest);
                      }
                       if(count($semslopedff7) < 7)
                       {
                       $slopesem3_2 = null;
                       }
                       else
                       {
                        $linearresem = slope::linearreg_slope($slopepush,sizeof($slopepush));
                        $arrayck = sizeof($slopepush)-1;
                        $slopefcsem3_2 = abs($linearresem);
                        $slopesem3_2 = number_format($linearresem,3);
                       }
              
            //------------------------------- end slope dff. semclass3.2----------------------------------//
          $sem32higher = 0;
          $sem32below = 0;

            foreach ( $semdatalab as $item){ 
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
                if($datafirst->class3_2 == "N/A" )
                {
                    $natbd = 0;
                }
                else if($datafirst->class3_2 == "TBD")
                {
                    $natbd = 0;
                }
                else
                {
                    $natbd = 1;
                }
                if($natbd == 1)
                {
                    if ($datafirst->class3_2 < $item->sem_class3_2) {
                        $sem32higher++;
                        
                    } 
                    else if ($datafirst->class3_2 > $item->sem_class3_2) {
                        $sem32below++;
                    
                    } 
                    else {
                        $sem32higher--;
                        $sem32below--;
                    }
                }
            }
    
          $sem32highervs3 = 0;
          $sem32belowvs3 = 0;
          if($countsortdatalab >= $alarmtest3y){
          $datalabsem32 = $semdatalabvs3[0]->sem_class3_2;
          $iss = 0;
          foreach ( $semdatalabvs3 as $item){ 
              $iss++;
              if( $iss <= $alarmtest3)
              {
              if ($datalabsem32 < $semdatalabvs3[$iss]->sem_class3_2) {
                  $sem32highervs3++;
                  $datalabsem32 = $semdatalabvs3[$iss]->sem_class3_2;
              } 
              else if ($datalabsem32 > $semdatalabvs3[$iss]->sem_class3_2) {
                  $sem32belowvs3++;
                  $datalabsem32 = $semdatalabvs3[$iss]->sem_class3_2;
              } 
              else {
                  $sem32highervs3--;
                  $sem32belowvs3--;
              }
             }
          }
        }
          //-------------------------------------end semclass3.2 -------------------------------------//
    
          //-------------------------------------semclass3.3 -------------------------------------//
    
          //------------------------------- slope dff. semclass3.3 ----------------------------------//
                  $avgsdff = $semdff->avg('sem_v_class3_3');
                  $std_dff = $semdff->select(DB::raw('STDDEV_SAMP(nt_v_data_sem.sem_v_class3_3) as std'))->first();
                  if($std_dff->std ==0)
                  {
                  $dffck = null;
                  $dffsem3_3 = null;
                  }
                  else
                  {
                    $dffck = ($oqadatesem->sem_v_class3_3 - $avgsdff) / $std_dff->std;
                    $dffsem3_3 = number_format($dffck,3);
                  }
    
                  
                   $avgsem3_3 = $semslope->avg('sem_v_class3_3');
                   $countsem3_3 = $semslope->count('sem_v_class3_3');
                   $std_s = $semslope->select(DB::raw('STDDEV_SAMP(nt_v_data_sem.sem_v_class3_3) as std'))->first();
                   $stdsem3_3 = number_format($std_s->std,3);
                   $slopepush=array();
               
                   foreach($semslopedff7 as $slope)
                   {
                    if($std_s->std == 0)
                    {
                        $slopest = ($slope->sem_v_class3_3 - $avgsem3_3)/1;
                    }
                    else
                    {
                        $slopest = ($slope->sem_v_class3_3 - $avgsem3_3)/$std_s->std;
                    }
                       
                       array_push($slopepush,$slopest);
                   }
                    if(count($semslopedff7) < 7)
                    {
                    $slopesem3_3 = null;
                    }
                    else
                    {
                        $linearresem = slope::linearreg_slope($slopepush,sizeof($slopepush));
                        $arrayck = sizeof($slopepush)-1;
                        $slopefcsem3_3 = abs($linearresem);
                        $slopesem3_3 = number_format($linearresem,3);
                    }
          //------------------------------- end slope dff. semclass3.3----------------------------------//
          $sem33higher = 0;
          $sem33below = 0;

            foreach ( $semdatalab as $item){ 
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
                if($datafirst->class3_3 == "N/A" )
                {
                    $natbd = 0;
                }
                else if($datafirst->class3_3 == "TBD")
                {
                    $natbd = 0;
                }
                else
                {
                    $natbd = 1;
                }
                if($natbd == 1)
                {
                    if ($datafirst->class3_3 < $item->sem_class3_3) {
                        $sem33higher++;
                        
                    } 
                    else if ($datafirst->class3_3 > $item->sem_class3_3) {
                        $sem33below++;
                    
                    } 
                    else {
                        $sem33higher--;
                        $sem33below--;
                    }
                }
            }
    
          $sem33highervs3 = 0;
          $sem33belowvs3 = 0;
          if($countsortdatalab >= $alarmtest3y){
          $datalabsem33 = $semdatalabvs3[0]->sem_class3_3;
          $iss = 0;
          foreach ( $semdatalabvs3 as $item){ 
              $iss++;
              if( $iss <= $alarmtest3)
              {
              if ($datalabsem33 < $semdatalabvs3[$iss]->sem_class3_3) {
                  $sem33highervs3++;
                  $datalabsem33 = $semdatalabvs3[$iss]->sem_class3_3;
              } 
              else if ($datalabsem33 > $semdatalabvs3[$iss]->sem_class3_3) {
                  $sem33belowvs3++;
                  $datalabsem33 = $semdatalabvs3[$iss]->sem_class3_3;
              } 
              else {
                  $sem33highervs3--;
                  $sem33belowvs3--;
              }
             }
          }
        }
          //-------------------------------------end semclass3.3 -------------------------------------//
    
          //-------------------------------------semclass4 -------------------------------------//
    
                 //------------------------------- slope dff. semclas4 ----------------------------------//
                 $avgsdff = $semdff->avg('sem_v_class4');
                 $std_dff = $semdff->select(DB::raw('STDDEV_SAMP(nt_v_data_sem.sem_v_class4) as std'))->first();
                 if($std_dff->std ==0)
                 {
                 $dffck = null;
                 $dffsem4 = null;
                 }
                 else
                 {
                    $dffck = ($oqadatesem->sem_v_class4 - $avgsdff) / $std_dff->std;
                    $dffsem4 = number_format($dffck,3);
                 }
                 
                  $avgsem4 = $semslope->avg('sem_v_class4');
                  $countsem4 = $semslope->count('sem_v_class4');
                  $std_s = $semslope->select(DB::raw('STDDEV_SAMP(nt_v_data_sem.sem_v_class4) as std'))->first();
                  $stdsem4 = number_format($std_s->std,3);
                  $slopepush=array();
              
                  foreach($semslopedff7 as $slope)
                  {
                    if($std_s->std == 0)
                    {
                        $slopest = ($slope->sem_v_class4 - $avgsem4)/1;
                    }
                    else
                    {
                        $slopest = ($slope->sem_v_class4 - $avgsem4)/$std_s->std;
                    }
                      array_push($slopepush,$slopest);
                  }
                   if(count($semslopedff7) < 7)
                   {
                   $slopesem4 = null;
                   }
                   else
                   {
                    $linearresem = slope::linearreg_slope($slopepush,sizeof($slopepush));
                    $arrayck = sizeof($slopepush)-1;
                    $slopefcsem4 = abs($linearresem);
                    $slopesem4 = number_format($linearresem,3);
                   }
          
                  //------------------------------- end slope dff. semclass4----------------------------------//
            $sem4higher = 0;
            $sem4below = 0;

                foreach ( $semdatalab as $item){
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
                    if($datafirst->class4 == "N/A" )
                    {
                        $natbd = 0;
                    }
                    else if($datafirst->class4 == "TBD")
                    {
                        $natbd = 0;
                    }
                    else
                    {
                        $natbd = 1;
                    }
                    if($natbd == 1)
                    {
                        if ($datafirst->class4 < $item->sem_class4) {
                            $sem4higher++;
                            
                        } 
                        else if ($datafirst->class4 > $item->sem_class4) {
                            $sem4below++;
                        
                        } 
                        else {
                            $sem4higher--;
                            $sem4below--;
                        }
                    }
                }
        
            $sem4highervs3 = 0;
            $sem4belowvs3 = 0;
            if($countsortdatalab >= $alarmtest3y){
            $datalabsem4 = $semdatalabvs3[0]->sem_class4;
            $iss = 0;
            foreach ( $semdatalabvs3 as $item){ 
                $iss++;
                if( $iss <= $alarmtest3)
                {
                if ($datalabsem4 < $semdatalabvs3[$iss]->sem_class4) {
                    $sem4highervs3++;
                    $datalabsem4 = $semdatalabvs3[$iss]->sem_class4;
                } 
                else if ($datalabsem4 > $semdatalabvs3[$iss]->sem_class4) {
                    $sem4belowvs3++;
                    $datalabsem4 = $semdatalabvs3[$iss]->sem_class4;
                } 
                else {
                    $sem4highervs3--;
                    $sem4belowvs3--;
                }
               }
            }
        }
            //-------------------------------------end semclass4 -------------------------------------//
    
              //-------------------------------------semclass5 -------------------------------------//
                     //------------------------------- slope dff. semclas5 ----------------------------------//
                     $avgsdff = $semdff->avg('sem_v_class5');
                     $std_dff = $semdff->select(DB::raw('STDDEV_SAMP(nt_v_data_sem.sem_v_class5) as std'))->first();
                     if($std_dff->std == 0)
                     {
                     $dffck = null;
                     $dffsem5 = null;
                     }
                     else
                     {
                        $dffck = ($oqadatesem->sem_v_class5 - $avgsdff) / $std_dff->std;
                        $dffsem5 = number_format($dffck,3);
                     }
                    
                      $avgsem5 = $semslope->avg('sem_v_class5');
                      $countsem5 = $semslope->count('sem_v_class5');
                      $std_s = $semslope->select(DB::raw('STDDEV_SAMP(nt_v_data_sem.sem_v_class5) as std'))->first();
                      $stdsem5 = number_format($std_s->std,3);
                      $slopepush=array();
                  
                      foreach($semslopedff7 as $slope)
                      {
                        if($std_s->std == 0)
                        {
                            $slopest = ($slope->sem_v_class5 - $avgsem5)/1;
                        }
                        else
                        {
                            $slopest = ($slope->sem_v_class5 - $avgsem5)/$std_s->std;
                        }
                          array_push($slopepush,$slopest);
                      }
                       if(count($semslopedff7) < 7)
                       {
                       $slopesem5 = null;
                       }
                       else
                       {
                        $linearresem = slope::linearreg_slope($slopepush,sizeof($slopepush));
                        $arrayck = sizeof($slopepush)-1;
                        $slopefcsem5 = abs($linearresem);
                        $slopesem5 = number_format($linearresem,3);
                       }
              
                      //------------------------------- end slope dff. semclass5----------------------------------//
              $sem5higher = 0;
              $sem5below = 0;

                foreach ( $semdatalab as $item){ 
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
                    if($datafirst->class5 == "N/A" )
                    {
                        $natbd = 0;
                    }
                    else if($datafirst->class5 == "TBD")
                    {
                        $natbd = 0;
                    }
                    else
                    {
                        $natbd = 1;
                    }
                    if($natbd == 1)
                    {
                        if ($datafirst->class5 < $item->sem_class5) {
                            $sem5higher++;
                            
                        } 
                        else if ($datafirst->class5 > $item->sem_class5) {
                            $sem5below++;
                        
                        } 
                        else {
                            $sem5higher--;
                            $sem5below--;
                        }
                    }
                }
          

              $sem5highervs3 = 0;
              $sem5belowvs3 = 0;
              if($countsortdatalab >= $alarmtest3y){
              $datalabsem5 = $semdatalabvs3[0]->sem_class5;
              $iss = 0;
              foreach ( $semdatalabvs3 as $item){ 
                   $iss++;
                   //$semdatalabvs3[2]->sem_class5;
                  if( $iss <= $alarmtest3)
                  {
    
                  if ($datalabsem5 < $semdatalabvs3[$iss]->sem_class5) {
                    $sem5highervs3++;
                    $datalabsem5 = $semdatalabvs3[$iss]->sem_class5;
                     } 
                    else if ($datalabsem5 > $semdatalabvs3[$iss]->sem_class5) {
                    $sem5belowvs3++;
                    $datalabsem5 = $semdatalabvs3[$iss]->sem_class5;
                    } 
                    else {
                    $sem5highervs3--;
                    $sem5belowvs3--;
                 }
                 }
              }
            }
              //-------------------------------------end semclass5 -------------------------------------//
    
                 //-------------------------------------semclass5.1 -------------------------------------//
                                  //------------------------------- slope dff. semclas5.1 ----------------------------------//
                                  $avgsdff = $semdff->avg('sem_v_class5_1');
                                  $std_dff = $semdff->select(DB::raw('STDDEV_SAMP(nt_v_data_sem.sem_v_class5_1) as std'))->first();
                                  if($std_dff->std == 0)
                                  {
                                  $dffck = null;
                                  $dffsem5_1 = null;
                                  }
                                  else
                                  {
                                    $dffck = ($oqadatesem->sem_v_class5_1 - $avgsdff) / $std_dff->std;
                                    $dffsem5_1 = number_format($dffck,3);
                                  }
                                  
                                   $avgsem5_1 = $semslope->avg('sem_v_class5_1');
                                   $countsem5_1 = $semslope->count('sem_v_class5_1');
                                   $std_s = $semslope->select(DB::raw('STDDEV_SAMP(nt_v_data_sem.sem_v_class5_1) as std'))->first();
                                   $stdsem5_1 = number_format($std_s->std,3);
                                   $slopepush=array();
                               
                                   foreach($semslopedff7 as $slope)
                                   {
                                    if($std_s->std == 0)
                                    {
                                        $slopest = ($slope->sem_v_class5_1 - $avgsem5_1)/1;
                                    }
                                    else
                                    {
                                        $slopest = ($slope->sem_v_class5_1 - $avgsem5_1)/$std_s->std;
                                    }
                                      
                                       array_push($slopepush,$slopest);
                                   }
    
                                    if(count($semslopedff7) < 7)
                                    {
                                    $slopesem5_1 = null;
                                    }
                                    else
                                    {
                                        $linearresem = slope::linearreg_slope($slopepush,sizeof($slopepush));
                                        $arrayck = sizeof($slopepush)-1;
                                        $slopefcsem5_1 = abs($linearresem);
                                        $slopesem5_1 = number_format($linearresem,3);
                                    }
                           
                                   //------------------------------- end slope dff. semclass5.1----------------------------------//
            $sem51higher = 0;
            $sem51below = 0;

                foreach ( $semdatalab as $item){ 
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
                    if($datafirst->class5_1 == "N/A" )
                    {
                        $natbd = 0;
                    }
                    else if($datafirst->class5_1 == "TBD")
                    {
                        $natbd = 0;
                    }
                    else
                    {
                        $natbd = 1;
                    }
                    if($natbd == 1)
                    {
                        if ($datafirst->class5_1 < $item->sem_class5_1) {
                            $sem51higher++;
                            
                        } 
                        else if ($datafirst->class5_1 > $item->sem_class5_1) {
                            $sem51below++;
                        
                        } 
                        else {
                            $sem51higher--;
                            $sem51below--;
                        }
                    }
                }
        

            $sem51highervs3 = 0;
            $sem51belowvs3 = 0;
            if($countsortdatalab >= $alarmtest3y){
            $datalabsem51 = $semdatalabvs3[0]->sem_class5_1;
            $iss = 0;
            foreach ( $semdatalabvs3 as $item){ 
                $iss++;
                if( $iss <= $alarmtest3)
                {
                if ($datalabsem51 < $semdatalabvs3[$iss]->sem_class5_1) {
                    $sem51highervs3++;
                    $datalabsem51 = $semdatalabvs3[$iss]->sem_class5_1;
                } 
                else if ($datalabsem51 > $semdatalabvs3[$iss]->sem_class5_1) {
                    $sem51belowvs3++;
                    $datalabsem51 = $semdatalabvs3[$iss]->sem_class5_1;
                } 
                else {
                    $sem51highervs3--;
                    $sem51belowvs3--;
                }
               }
            }
        }
            //-------------------------------------end semclass5.1 -------------------------------------//
                 //-------------------------------------semclass6 -------------------------------------//
                 //------------------------------- slope dff. semclas6 ----------------------------------//
                 $avgsdff = $semdff->avg('sem_v_class6');
                 $std_dff = $semdff->select(DB::raw('STDDEV_SAMP(nt_v_data_sem.sem_v_class6) as std'))->first();
                 if($std_dff->std == 0)
                 {
                 $dffck = null;
                 $dffsem6 = null;
                 }
                 else
                 {
                    $dffck = ($oqadatesem->sem_v_class6 - $avgsdff) / $std_dff->std;
                    $dffsem6 = number_format($dffck,3);
                 }
                
                  $avgsem6 = $semslope->avg('sem_v_class6');
                  $countsem6 = $semslope->count('sem_v_class6');
                  $std_s = $semslope->select(DB::raw('STDDEV_SAMP(nt_v_data_sem.sem_v_class6) as std'))->first();
                  $stdsem6 = number_format($std_s->std,3);
                  $slopepush=array();
              
                  foreach($semslopedff7 as $slope)
                  {
                    if($std_s->std== 0)
                    {
                        $slopest = ($slope->sem_v_class6 - $avgsem6)/1;
                    }
                    else
                    {
                        $slopest = ($slope->sem_v_class6 - $avgsem6)/$std_s->std;
                    }
                     
                      array_push($slopepush,$slopest);
                  }
                   if(count($semslopedff7) < 7)
                   {
                   $slopesem6 = null;
                   }
                   else
                   {
                    $linearresem = slope::linearreg_slope($slopepush,sizeof($slopepush));
                    $arrayck = sizeof($slopepush)-1;
                    $slopefcsem6 = abs($linearresem);
                    $slopesem6 = number_format($linearresem,3);
                   }
          
                  //------------------------------- end slope dff. semclass6----------------------------------/
                 $sem6higher = 0;
                 $sem6below = 0;
                    foreach ( $semdatalab as $item){ 
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
                        if($datafirst->class6 == "N/A" )
                        {
                            $natbd = 0;
                        }
                        else if($datafirst->class6 == "TBD")
                        {
                            $natbd = 0;
                        }
                        else
                        {
                            $natbd = 1;
                        }
                        if($natbd == 1)
                        {
                            if ($datafirst->class6 < $item->sem_class6) {
                                $sem6higher++;
                                
                            } 
                            else if ($datafirst->class6 > $item->sem_class6) {
                                $sem6below++;
                            
                            } 
                            else {
                                $sem6higher--;
                                $sem6below--;
                            }
                        }
                    }
            
                 $sem6highervs3 = 0;
                 $sem6belowvs3 = 0;
                      if($countsortdatalab >= $alarmtest3y){
                 $datalabsem6 = $semdatalabvs3[0]->sem_class6;
                 $iss = 0;
                 foreach ( $semdatalabvs3 as $item){ 
                     $iss++;
                     if( $iss <= $alarmtest3)
                     {
                     if ($datalabsem6 < $semdatalabvs3[$iss]->sem_class6) {
                         $sem6highervs3++;
                         $datalabsem6 = $semdatalabvs3[$iss]->sem_class6;
                     } 
                     else if ($datalabsem6 > $semdatalabvs3[$iss]->sem_class6) {
                         $sem6belowvs3++;
                         $datalabsem6 = $semdatalabvs3[$iss]->sem_class6;
                     } 
                     else {
                         $sem6highervs3--;
                         $sem6belowvs3--;
                     }
                    }
                 }
                }
                 $chksemshow = 1;
               
            //-------------------------------------end semclass6 -------------------------------------//
    
           //-------------------------------------end sem-------------------------------------//
        
                }
                else
                {
                   
                    $chksemshow = 0;
                    $semdatalab = null;
                    $semdatalaball = null;
                    $newsemdatalab = null;
                    $sem1higher= null;
                    $sem1below= null;
                    $sem1highervs3= null;
                    $sem1belowvs3= null;
                    $sem2higher= null;
                    $sem2below= null;
                    $sem2highervs3= null;
                    $sem2belowvs3= null;
                    $sem3higher= null;
                    $sem3below= null;
                    $sem3highervs3= null;
                    $sem3belowvs3= null;
                    $sem31higher= null;
                    $sem31below= null;
                    $sem31highervs3= null;
                    $sem31belowvs3= null;
                    $sem32higher = null;
                    $sem32below = null;
                    $sem32highervs3 = null;
                    $sem32belowvs3 = null; 
                    $sem33higher = null;
                    $sem33below = null;
                    $sem33highervs3 = null;
                    $sem33belowvs3 = null;
                    $sem4higher = null;
                    $sem4below = null;
                    $sem4highervs3 = null;
                    $sem4belowvs3 = null;
                    $sem5higher = null;
                    $sem5below = null;
                    $sem5highervs3 = null;
                    $sem5belowvs3 = null;
                    $sem51higher = null;
                    $sem51below = null;
                    $sem51highervs3 = null;
                    $sem51belowvs3 = null;
                    $sem6higher = null;
                    $sem6below = null;
                    $sem6highervs3 = null;
                    $sem6belowvs3 = null;
                    $slopesem1= null;
                    $dffsem1= null;
                    $avgsem1= null;
                    $countsem1= null;
                    $stdsem1= null;
                    $slopesem2= null;
                    $dffsem2= null;
                    $avgsem2= null;
                    $countsem2= null;
                    $stdsem2= null;
                    $slopesem3= null;
                    $dffsem3= null;
                    $avgsem3= null;
                    $countsem3= null;
                    $stdsem3= null;   
                    $slopesem3_1= null;
                    $dffsem3_1= null;
                    $avgsem3_1= null;
                    $countsem3_1= null;
                    $stdsem3_1= null;
                    $slopesem3_2= null;
                    $dffsem3_2= null;
                    $avgsem3_2= null;
                    $countsem3_2= null;
                    $stdsem3_2= null;
                    $slopesem3_3= null;
                    $dffsem3_3= null;
                    $avgsem3_3= null;
                    $countsem3_3= null;
                    $stdsem3_3= null;           
                    $slopesem4= null;
                    $dffsem4= null;
                    $avgsem4= null;
                    $countsem4= null;
                    $stdsem4= null;
                    $slopesem5= null;
                    $dffsem5= null;
                    $avgsem5= null;
                    $countsem5= null;
                    $stdsem5= null;
                    $slopesem5_1= null;
                    $dffsem5_1= null;
                    $avgsem5_1= null;
                    $countsem5_1= null;
                    $stdsem5_1= null;
                    $slopesem6= null;
                    $dffsem6= null;
                    $avgsem6= null;
                    $countsem6= null;
                    $stdsem6 = null; 
                }
    
           //-------------------------------------ic-------------------------------------//
           
           if(!empty($oqadateic) && $chkoqadateic == 1)
           {
            if($request->chkpdf == 0)
            {
              $yeardown = date('Y-m-d 00:00:00', strtotime($oqadateic->save_date. ' - 1 year'));
              $dateend = $oqadateic->save_date;
            }
            else if($request->chkpdf == 2)
    {
      $datenow = date("Y-m-d H:i:s");
      $yeardown = date('Y-m-d 00:00:00', strtotime($datenow. ' - 1 year'));
      $dateend = $datenow;
    }
            else
            {
              $yeardown = date('Y-m-d 00:00:00', strtotime($request->datestart));
              $dateend = date('Y-m-d 00:00:00', strtotime($request->dateend. ' + 1 day'));;
            } 
           $icdatalab = DB::table('nt_datacf_ic')
           ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_datacf_ic.id_createlab')
           ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
           ->where('nt_oqa_lab.save_date','<=',$dateend)
           ->where('nt_oqa_lab.id_model',$request->input('id'))
           ->where('nt_oqa_lab.ic_confirm_st',1)
           ->limit($alarmtest2)->orderBy('nt_oqa_lab.save_date', 'desc')->get();

           $icdatalaball = DB::table('nt_datacf_ic')
           ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_datacf_ic.id_createlab')
           ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
           ->where('nt_oqa_lab.save_date','<=',$dateend)
           ->where('nt_oqa_lab.id_model',$request->input('id'))
           ->where('nt_oqa_lab.ic_confirm_st',1);
           if($request->chkpdf == 0 || $request->chkpdf == 2)
           {
               $icdatalaball =  $icdatalaball->limit(intval($oqasum->ic_sum))->orderBy('nt_oqa_lab.save_date', 'desc')->get();
           }
           else
           {
               $icdatalaball =  $icdatalaball->where('nt_oqa_lab.save_date','>=',$yeardown)->orderBy('nt_oqa_lab.save_date', 'desc')->get();
           }
            $newicdatalab=array();
            foreach ($icdatalaball as $item){     
                $stdate = date('Y-m-d', strtotime($item->save_date));
                $data = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$stdate)
                ->where('nt_model_history.date_end','>=',$stdate)
                ->where('nt_model_history.chkstatus',1)
                ->get(); 
                if($data->count() == 0)
                {
                 $data1 = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                 ->first();
                 
                 $data = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','<=',$data1->date_start)  
                 ->where('nt_model_history.date_end','>=',$data1->date_start)
                 ->get(); 
                }
                array_push($newicdatalab,$data);
            }
           $icdatalabvs3 = DB::table('nt_datacf_ic')
           ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_datacf_ic.id_createlab')
           ->where('nt_oqa_lab.save_date','<=',$dateend)
           ->where('nt_oqa_lab.id_model',$request->input('id'))
           ->where('nt_oqa_lab.ic_confirm_st',1)
           ->limit($alarmtest3)->orderBy('nt_oqa_lab.save_date', 'desc')->get();
           $counticsortdatalab = count($icdatalabvs3);
           $icdff = DB::table('nt_v_data_ic')
           ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_ic.id_createlab')
            ->where('nt_oqa_lab.save_date','<=',$dateend)
            ->where('nt_oqa_lab.save_date','>=',$yeardown)
            ->where('nt_oqa_lab.ic_confirm_st',1)
           ->where('nt_oqa_lab.id_model',$request->input('id'));

           if($icdff->count() < 10 && $icdff->count() != 0)
           {
            $icdfflimit = DB::table('nt_v_data_ic')
            ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_ic.id_createlab')
             ->where('nt_oqa_lab.save_date','<=',$dateend)
             ->where('nt_oqa_lab.ic_confirm_st',1)
            ->where('nt_oqa_lab.id_model',$request->input('id'))->orderby('nt_oqa_lab.save_date','desc')->limit(10)->get(); 
           
            $countdfflimit = $icdfflimit->count() -1 ;

            $icdff = DB::table('nt_v_data_ic')
            ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_ic.id_createlab')
             ->where('nt_oqa_lab.save_date','<=',$dateend)
             ->where('nt_oqa_lab.save_date','>=',$icdfflimit[$countdfflimit]->save_date)
             ->where('nt_oqa_lab.ic_confirm_st',1)
            ->where('nt_oqa_lab.id_model',$request->input('id'))->limit(10);
           }
        
           $icslope = DB::table('nt_v_data_ic')
           ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_ic.id_createlab')
           ->where('nt_oqa_lab.save_date','<=',$dateend)
           ->where('nt_oqa_lab.save_date','>=',$yeardown)
           ->where('nt_oqa_lab.ic_confirm_st',1)
           ->where('nt_oqa_lab.id_model',$request->input('id'));
           $icslopedff7 = DB::table('nt_v_data_ic')
           ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_ic.id_createlab')
            ->where('nt_oqa_lab.save_date','<=',$dateend)
            ->where('nt_oqa_lab.save_date','>=',$yeardown)
            ->where('nt_oqa_lab.ic_confirm_st',1)
           ->where('nt_oqa_lab.id_model',$request->input('id'))->OrderBy('nt_oqa_lab.save_date','desc')->limit(7)->get();


           if($icslope->count() < 11 && $icslope->count() != 0)
           { 
            $icslopelimit = DB::table('nt_v_data_ic')
            ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_ic.id_createlab')
            ->where('nt_oqa_lab.save_date','<=',$dateend)
            ->where('nt_oqa_lab.ic_confirm_st',1)
            ->where('nt_oqa_lab.id_model',$request->input('id'))->orderby('nt_oqa_lab.save_date','desc')->limit(11)->get();
            $countslopelimit = $icslopelimit->count() -1 ;

            $icslope = DB::table('nt_v_data_ic')
            ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_ic.id_createlab')
            ->where('nt_oqa_lab.save_date','<=',$dateend)
            ->where('nt_oqa_lab.save_date','>=',$icslopelimit[$countslopelimit]->save_date)
            ->where('nt_oqa_lab.ic_confirm_st',1)
            ->where('nt_oqa_lab.id_model',$request->input('id'));
           $countslopelimit1 = $icslopelimit->count();
            if($countslopelimit1 <7)
            {
                $numx =  $countslopelimit ;
            }
            else
            {
                $numx = 6;
            }

            $icslopedff7 = DB::table('nt_v_data_ic')
            ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_ic.id_createlab')
             ->where('nt_oqa_lab.save_date','<=',$dateend)
             ->where('nt_oqa_lab.save_date','>=',$icslopelimit[$numx]->save_date)
             ->where('nt_oqa_lab.ic_confirm_st',1)
            ->where('nt_oqa_lab.id_model',$request->input('id'))->OrderBy('nt_oqa_lab.save_date','desc')->limit(7)->get();
           }
    
            //-------------------------------------ic anion-------------------------------------//
    
                //------------------------------- slope dff. ic anion ----------------------------------//
                $avgsdff = $icdff->avg('ic_anion_up');
              
                $std_dff = $icdff->select(DB::raw('STDDEV_SAMP(nt_v_data_ic.ic_anion_up) as std'))->first();
                if($std_dff->std == 0)
                {
                $dffck = null;
                $dffic_anion = null;
                }
                else
                {
                    $dffck = ($oqadateic->ic_anion_up - $avgsdff) / $std_dff->std;
                    $dffic_anion = number_format($dffck,3);
                }
            
                
                 $avgic_anion = $icslope->avg('ic_anion_up');
                 $countic_anion = $icslope->count('ic_anion_up');
                
                 $std_s = $icslope->select(DB::raw('STDDEV_SAMP(nt_v_data_ic.ic_anion_up) as std'))->first();
                
                 $stdic_anion = number_format($std_s->std,3);
                 $slopepush=array();
                
                 foreach($icslopedff7 as $slope)
                 {
                    if($std_s->std == 0)
                    {
                        $slopest = ($slope->ic_anion_up - $avgic_anion)/1;
                    }
                    else
                    {
                        $slopest = ($slope->ic_anion_up - $avgic_anion)/$std_s->std;
                    }
                     
                   
                     array_push($slopepush,$slopest);
                    
                 }       
                  if(count($icslopedff7) < 7)
                  {
                  $slopeic_anion = null;
                  }
                  else
                  {
                    $linearreic = slope::linearreg_slope($slopepush,sizeof($slopepush));
                    $arrayck = sizeof($slopepush)-1;
                    $slopefcic_anion = abs($linearreic);
                    $slopeic_anion = number_format($linearreic,3);
                  }
                 //------------------------------- end slope dff. ic anion----------------------------------/
            
           $icanionhigher = 0;
           $icanionbelow = 0;
            foreach ( $icdatalab as $item){ 
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
                if($datafirst->anion == "N/A" )
                {
                    $natbd = 0;
                }
                else if($datafirst->anion == "TBD")
                {
                    $natbd = 0;
                }
                else
                {
                    $natbd = 1;
                }
                if($natbd == 1)
                {
                    if ($datafirst->anion < $item->ic_anion_up) {
                        $icanionhigher++;   
                    } 
                    else if ($datafirst->anion > $item->ic_anion_up) {
                        $icanionbelow++;
                    } 
                    else {
                        $icanionhigher--;
                        $icanionbelow--;
                    }
                }
            }
  
           $icanionhighervs3 = 0;
           $icanionbelowvs3 = 0;
           if($counticsortdatalab >= $alarmtest3y )
           {
           $datalablic = $icdatalabvs3[0]->ic_anion_up;
           $iss = 0;
           foreach ( $icdatalabvs3 as $item){ 
               $iss++;
               if( $iss <= $alarmtest3)
               {
               if ($datalablic < $icdatalabvs3[$iss]->ic_anion_up) {
                   $icanionhighervs3++;
                   $datalablic = $icdatalabvs3[$iss]->ic_anion_up;
               } 
               else if ($datalablic >  $icdatalabvs3[$iss]->ic_anion_up) {
                   $icanionbelowvs3++;
                   $datalablic = $icdatalabvs3[$iss]->ic_anion_up;
               } 
               else {
                   $icanionhighervs3--;
                   $icanionbelowvs3--;
               }
               }
           }
        }
            //-------------------------------------end ic anion-------------------------------------//
            
            //-------------------------------------ic sulphat-------------------------------------//
            //------------------------------- slope dff.ic sulphat ----------------------------------//
                        $avgsdff = $icdff->avg('ic_v_sulphate');
              
                        $std_dff = $icdff->select(DB::raw('STDDEV_SAMP(nt_v_data_ic.ic_v_sulphate) as std'))->first();
                        if($std_dff->std == 0)
                            {
                                $dffck = null;
                                $dffic_sulphate = null;
                            }
                            else
                            {
                                $dffck = ($oqadateic->ic_v_sulphate - $avgsdff) / $std_dff->std;
                                $dffic_sulphate = number_format($dffck,3);
                            }
                    
                        
                         $avgic_sulphate = $icslope->avg('ic_v_sulphate');
                         $countic_sulphate = $icslope->count('ic_v_sulphate');
                        
                         $std_s = $icslope->select(DB::raw('STDDEV_SAMP(nt_v_data_ic.ic_v_sulphate) as std'))->first();
                        
                         $stdic_sulphate = number_format($std_s->std,3);
                         $slopepush=array();
                        
                         foreach($icslopedff7 as $slope)
                         {
                            if($std_s->std == 0)
                            {
                                $slopest = ($slope->ic_v_sulphate - $avgic_sulphate)/1;
                            }
                            else
                            {
                                $slopest = ($slope->ic_v_sulphate - $avgic_sulphate)/$std_s->std;
                            }
                             
                             array_push($slopepush,$slopest);
                         }                
                          if(count($icslopedff7) < 7)
                          {
                          $slopeic_sulphate = null;
                          }
                          else
                          {
                            $linearreic = slope::linearreg_slope($slopepush,sizeof($slopepush));
                            $arrayck = sizeof($slopepush)-1;
                            $slopefcic_sulphate = abs($linearreic);
                            $slopeic_sulphate = number_format($linearreic,3);
                          }
        //------------------------------- end slope dff. ic sulphat----------------------------------/
           $sulphathigher = 0;
           $sulphatbelow = 0;

            foreach ( $icdatalab as $item){ 
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
                if($datafirst->sulphate == "N/A" )
                {
                    $natbd = 0;
                }
                else if($datafirst->sulphate == "TBD")
                {
                    $natbd = 0;
                }
                
                else
                {
                    $natbd = 1;
                }
                if($natbd == 1)
                {
                    if ($datafirst->sulphate < $item->ic_sulphate) {
                        $sulphathigher++;
                        
                    } 
                    else if ($datafirst->sulphate > $item->ic_sulphate) {
                        $sulphatbelow++;
                    
                    } 
                    else {
                        $sulphathigher--;
                        $sulphatbelow--;
                    }
                }
            }
      

           $sulphathighervs3 = 0;
           $sulphatbelowvs3 = 0;
           if($counticsortdatalab >= $alarmtest3y )
           {
           $datalablic = $icdatalabvs3[0]->ic_sulphate;
           $iss = 0;
           foreach ( $icdatalabvs3 as $item){ 
               $iss++;
               if( $iss <= $alarmtest3)
               {
               if ($datalablic < $icdatalabvs3[$iss]->ic_sulphate) {
                   $sulphathighervs3++;
                   $datalablic = $icdatalabvs3[$iss]->ic_sulphate;
               } 
               else if ($datalablic >  $icdatalabvs3[$iss]->ic_sulphate) {
                   $sulphatbelowvs3++;
                   $datalablic = $icdatalabvs3[$iss]->ic_sulphate;
               } 
               else {
                   $sulphathighervs3--;
                   $sulphatbelowvs3--;
               }
               }
           }
        }
        //-------------------------------------end ic sulphat-------------------------------------//
    
                 //-------------------------------------ic cation-------------------------------------//
         //------------------------------- slope dff. ic cation ----------------------------------//
         $avgsdff = $icdff->avg('ic_v_cation_up');
              
         $std_dff = $icdff->select(DB::raw('STDDEV_SAMP(nt_v_data_ic.ic_v_cation_up) as std'))->first();
        if($std_dff->std ==0)
        {
            $dffck = null;
            $dffic_cation = null;
        }
        else
        {
            $dffck = ($oqadateic->ic_v_cation_up - $avgsdff) / $std_dff->std;
            $dffic_cation = number_format($dffck,3);
        }
         
     
         
          $avgic_cation = $icslope->avg('ic_v_cation_up');
          
          $countic_cation = $icslope->count('ic_v_cation_up');
         
          $std_s = $icslope->select(DB::raw('STDDEV_SAMP(nt_v_data_ic.ic_v_cation_up) as std'))->first();
         
          $stdic_cation = number_format($std_s->std,3);
          $slopepush=array();
         
          foreach($icslopedff7 as $slope)
          {
            if($std_s->std == 0)
            {
                $slopest = ($slope->ic_v_cation_up - $avgic_cation)/1;
            }
            else
            {
                $slopest = ($slope->ic_v_cation_up - $avgic_cation)/$std_s->std;
            }
             
              array_push($slopepush,$slopest);
          }
    
           if(count($icslopedff7) < 7)
           {
           $slopeic_cation = null;
           }
           else
           {
            $linearreic = slope::linearreg_slope($slopepush,sizeof($slopepush));
            $arrayck = sizeof($slopepush)-1;
            $slopefcic_cation = abs($linearreic);
            $slopeic_cation = number_format($linearreic,3);
           }
          //------------------------------- end slope dff. ic cation----------------------------------//
           $cationhigher = 0;
           $cationbelow = 0;

            foreach ( $icdatalab as $item){
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
                if($datafirst->cation == "N/A" )
                {
                    $natbd = 0;
                }
                else if($datafirst->cation == "TBD")
                {
                    $natbd = 0;
                }
                else if($datafirst->cation == "-")
                {
                    $natbd = 0;
                }
                else
                {
                    $natbd = 1;
                }
                if($natbd == 1)
                {
                    if ($datafirst->cation < $item->ic_cation_up) {
                        $cationhigher++;
                        
                    } 
                    else if ($datafirst->cation > $item->ic_cation_up) {
                        $cationbelow++;
                    
                    } 
                    else {
                        $cationhigher--;
                        $cationbelow--;
                    }
                }
            }
      
          
           $cationhighervs3 = 0;
           $cationbelowvs3 = 0;
           if($counticsortdatalab >= $alarmtest3y )
           {
           $datalablic = $icdatalabvs3[0]->ic_cation_up;
           $iss = 0;
           foreach ( $icdatalabvs3 as $item){ 
               $iss++;
               if( $iss <= $alarmtest3)
               {
               if ($datalablic < $icdatalabvs3[$iss]->ic_cation_up) {
                   $cationhighervs3++;
                   $datalablic = $icdatalabvs3[$iss]->ic_cation_up;
               } 
               else if ($datalablic >  $icdatalabvs3[$iss]->ic_cation_up) {
                   $cationbelowvs3++;
                   $datalablic = $icdatalabvs3[$iss]->ic_cation_upp;
               } 
               else {
                   $cationhighervs3--;
                   $cationbelowvs3--;
               }
               }
           }
        }
            //-------------------------------------end ic cation-------------------------------------//
            $chkicshow = 1;
       //-------------------------------------end ic-------------------------------------//
        }
        else
        {
            $chkicshow = 0;
            $icdatalab = null;
            $icdatalaball = null;
            $icanionhigher = null;
            $icanionbelow = null;
            $icanionhighervs3 = null;
            $icanionbelowvs3 = null;
            $sulphathigher = null;
            $sulphatbelow = null;
            $sulphathighervs3 = null;
            $sulphatbelowvs3 = null;
            $cationhigher = null;
            $cationbelow = null;
            $cationhighervs3 = null;
            $cationbelowvs3 = null;
            $slopeic_anion= null;
            $dffic_anion= null;
            $avgic_anion= null;
            $countic_anion= null;
            $stdic_anion= null;          
            $slopeic_sulphate= null;
            $dffic_sulphate= null;
            $avgic_sulphate= null;
            $countic_sulphate= null;
            $stdic_sulphate= null;
            $slopeic_cation= null;
            $dffic_cation= null;
            $avgic_cation= null;
            $countic_cation= null;
            $stdic_cation= null;
            $newicdatalab = null;
        }
        //------------------------------------- gcms -------------------------------------//
        if(!empty($oqadategcms))
        {

            if($request->chkpdf == 0)
            {
              $yeardown = date('Y-m-d 00:00:00', strtotime($oqadategcms->save_date. ' - 1 year'));
              $dateend = $oqadategcms->save_date;
            }
            else if($request->chkpdf == 2)
    {
      $datenow = date("Y-m-d H:i:s");
      $yeardown = date('Y-m-d 00:00:00', strtotime($datenow. ' - 1 year'));
      $dateend = $datenow;
    }
            else
            {
              $yeardown = date('Y-m-d 00:00:00', strtotime($request->datestart));
              $dateend = date('Y-m-d 00:00:00', strtotime($request->dateend. ' + 1 day'));;
            }    
        $gcmsdatalab = DB::table('nt_datacf_gcms')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_datacf_gcms.id_createlab')
        ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
        ->where('nt_oqa_lab.save_date','<=',$dateend)
        ->where('nt_oqa_lab.id_model',$request->input('id'))
        ->where('nt_oqa_lab.gcms_confirm_st',1)
        ->limit($alarmtest2)->orderBy('nt_oqa_lab.save_date', 'desc')->get();
        $gcmsdatalaball = DB::table('nt_datacf_gcms')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_datacf_gcms.id_createlab')
        ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
        ->where('nt_oqa_lab.save_date','<=',$dateend)
        ->where('nt_oqa_lab.id_model',$request->input('id'))
        ->where('nt_oqa_lab.gcms_confirm_st',1);
        if($request->chkpdf == 0 || $request->chkpdf == 2)
        {
            //$datalaball = $datalaball->limit(intval($oqasum->alpc_sum))->orderBy('nt_oqa_lab.save_date', 'desc')->get();
            $gcmsdatalaball =  $gcmsdatalaball->limit(intval($oqasum->gcms_sum))->orderBy('nt_oqa_lab.save_date', 'desc')->get();
        }
        else
        {
            $gcmsdatalaball =  $gcmsdatalaball->where('nt_oqa_lab.save_date','>=',$yeardown)->orderBy('nt_oqa_lab.save_date', 'desc')->get();
        }
        $newgcmsdatalab=array();
       foreach ($gcmsdatalaball as $item){     
           $stdate = date('Y-m-d', strtotime($item->save_date));
           $data = DB::table('nt_model_history')
          ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
          ->where('nt_model_history.id_model', $item->id_model)
          ->where('nt_model_history.date_start','<=',$stdate)
          ->where('nt_model_history.date_end','>=',$stdate)
          ->where('nt_model_history.chkstatus',1)
          ->get(); 
          if($data->count() == 0)
          {
           $data1 = DB::table('nt_model_history')
           ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
           ->where('nt_model_history.id_model', $item->id_model)
           ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
           ->first();
           
           $data = DB::table('nt_model_history')
           ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
           ->where('nt_model_history.id_model', $item->id_model)
           ->where('nt_model_history.date_start','<=',$data1->date_start)  
           ->where('nt_model_history.date_end','>=',$data1->date_start)
           ->get(); 
          }
           array_push($newgcmsdatalab,$data);
       }
        
        $gcmsdatalabvs3 = DB::table('nt_datacf_gcms')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_datacf_gcms.id_createlab')
        ->where('nt_oqa_lab.save_date','<=',$dateend)
        ->where('nt_oqa_lab.id_model',$request->input('id'))
        ->where('nt_oqa_lab.gcms_confirm_st',1)
        ->limit($alarmtest3y)->orderBy('nt_oqa_lab.save_date', 'desc')->get();
        $countgcmssortdatalab = count($gcmsdatalabvs3);
        // $gcmsyeardown = date('Y-m-d 00:00:00', strtotime($dateend. ' - 1 year'));
    
        $gcmsdff = DB::table('nt_v_data_gcms')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_gcms.id_createlab')
        ->where('nt_oqa_lab.save_date','<',$dateend)
        ->where('nt_oqa_lab.save_date','>=',$yeardown)
        ->where('nt_oqa_lab.gcms_confirm_st',1)
        ->where('nt_oqa_lab.id_model',$request->input('id'));

        if($gcmsdff->count() < 10 && $gcmsdff->count() != 0)
        {
         $gcmsdfflimit = DB::table('nt_v_data_gcms')
         ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_gcms.id_createlab')
         ->where('nt_oqa_lab.save_date','<',$dateend)
         ->where('nt_oqa_lab.gcms_confirm_st',1)
         ->where('nt_oqa_lab.id_model',$request->input('id'))->orderby('nt_oqa_lab.save_date','desc')->limit(10)->get(); 
        
         $countdfflimit = $gcmsdfflimit->count() -1 ;

         $gcmsdff = DB::table('nt_v_data_gcms')
         ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_gcms.id_createlab')
         ->where('nt_oqa_lab.save_date','<',$dateend)
         ->where('nt_oqa_lab.save_date','>=',$gcmsdfflimit[$countdfflimit]->save_date)
         ->where('nt_oqa_lab.gcms_confirm_st',1)
         ->where('nt_oqa_lab.id_model',$request->input('id'))->limit(10);
        }
     
        $gcmsslope = DB::table('nt_v_data_gcms')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_gcms.id_createlab')
        ->where('nt_oqa_lab.save_date','<=',$dateend)
        ->where('nt_oqa_lab.save_date','>=',$yeardown)
        ->where('nt_oqa_lab.gcms_confirm_st',1)
        ->where('nt_oqa_lab.id_model',$request->input('id'));
    
        $gcmsslopedff7 = DB::table('nt_v_data_gcms')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_gcms.id_createlab')
        ->where('nt_oqa_lab.save_date','<=',$dateend)
        ->where('nt_oqa_lab.save_date','>=',$yeardown)
        ->where('nt_oqa_lab.gcms_confirm_st',1)
        ->where('nt_oqa_lab.id_model',$request->input('id'))->OrderBy('nt_oqa_lab.save_date','desc')->limit(7)->get(); 

        if($gcmsslope->count() < 11 && $gcmsslope->count() != 0 )
        { 
         $gcmsslopelimit = DB::table('nt_v_data_gcms')
         ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_gcms.id_createlab')
         ->where('nt_oqa_lab.save_date','<=',$dateend)
         ->where('nt_oqa_lab.gcms_confirm_st',1)
         ->where('nt_oqa_lab.id_model',$request->input('id'))->orderby('nt_oqa_lab.save_date','desc')->limit(11)->get();
         $countslopelimit = $gcmsslopelimit->count() -1 ;

         $gcmsslope = DB::table('nt_v_data_gcms')
         ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_gcms.id_createlab')
         ->where('nt_oqa_lab.save_date','<=',$dateend)
         ->where('nt_oqa_lab.save_date','>=',$gcmsslopelimit[$countslopelimit]->save_date)
         ->where('nt_oqa_lab.gcms_confirm_st',1)
         ->where('nt_oqa_lab.id_model',$request->input('id'));

        $countslopelimit1 = $gcmsslopelimit->count();
         if($countslopelimit1 <7)
         {
             $numx =  $countslopelimit ;
         }
         else
         {
             $numx = 6;
         }

             
        $gcmsslopedff7 = DB::table('nt_v_data_gcms')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_gcms.id_createlab')
        ->where('nt_oqa_lab.save_date','<=',$dateend)
        ->where('nt_oqa_lab.save_date','>=',$gcmsslopelimit[$numx]->save_date)
        ->where('nt_oqa_lab.gcms_confirm_st',1)
        ->where('nt_oqa_lab.id_model',$request->input('id'))->OrderBy('nt_oqa_lab.save_date','desc')->limit(7)->get(); 
        }

           
        //$gcmsdatalaball()
         //------------------------------------- gcms outgassing -------------------------------------//
    
          //-------------------------------  slope dff. gcms outgassing----------------------------------//
         $avgsdff = $gcmsdff->avg('gcms_v_outgassing');
              
         $std_dff = $gcmsdff->select(DB::raw('STDDEV_SAMP(nt_v_data_gcms.gcms_v_outgassing) as std'))->first();
         if($std_dff->std ==0)
         {
            $dffck = null;
            $dffgcms_out = null;
         }
         else
         {
            $dffck = ($oqadategcms->gcms_v_outgassing - $avgsdff) / $std_dff->std;
            $dffgcms_out = number_format($dffck,3);
         }
          
        
        
         
         
          $avggcms_out = $gcmsslope->avg('gcms_v_outgassing');
          $countgcms_out = $gcmsslope->count('gcms_v_outgassing');
         
          $std_s = $gcmsslope->select(DB::raw('STDDEV_SAMP(nt_v_data_gcms.gcms_v_outgassing) as std'))->first();
          
          $stdgcms_out = number_format($std_s->std,3);
          $slopepush=array();
         
          foreach($gcmsslopedff7 as $slope)
          {
            if($std_s->std == 0)
            {
                $slopest = ($slope->gcms_v_outgassing - $avggcms_out)/1;
            }
            else
            {
                $slopest = ($slope->gcms_v_outgassing - $avggcms_out)/$std_s->std;
            }
              
              array_push($slopepush,$slopest);
          }
            if(count($gcmsslopedff7) < 7)
            {
                $slopegcms_out = null;
            }
            else
            {
                $linearregcms = slope::linearreg_slope($slopepush,sizeof($slopepush));
                $arrayck = sizeof($slopepush)-1;
                $slopefcgcms_out = abs($linearregcms);
                $slopegcms_out = number_format($linearregcms,3);
            }
          //-------------------------------end  slope dff. gcms outgassing----------------------------------//
    
        $gcms1higher = 0;
        $gcms1below = 0;

            foreach ( $gcmsdatalab as $item){ 
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
                if($datafirst->outgassing == "N/A" )
                {
                    $natbd = 0;
                }
                else if($datafirst->outgassing == "TBD")
                {
                    $natbd = 0;
                }
                else
                {
                    $natbd = 1;
                }
                if($natbd == 1)
                {
                    if ($datafirst->outgassing < $item->gcms_outgassing) {
                        $gcms1higher++;
                        
                    } 
                    else if ($datafirst->outgassing > $item->gcms_outgassing) {
                        $gcms1below++;
                    
                    } 
                    else {
                        $gcms1higher--;
                        $gcms1below--;
                    }
                }
            }
        $gcms1highervs3 = 0;
        $gcms1belowvs3 = 0;
        
        if( $countgcmssortdatalab >= $alarmtest3y)
        {
        $datalablgcms = $gcmsdatalabvs3[0]->gcms_outgassing;
        $iss = 0;
        foreach ( $gcmsdatalabvs3 as $item){ 
            $iss++;
            if( $iss <= $alarmtest3)
            {
            if ($datalablgcms < $gcmsdatalabvs3[$iss]->gcms_outgassing) {
                $gcms1highervs3++;
                $datalablgcms = $gcmsdatalabvs3[$iss]->gcms_outgassing;
            } 
            else if ($datalablgcms >  $gcmsdatalabvs3[$iss]->gcms_outgassing) {
                $gcms1belowvs3++;
                $datalablgcms = $gcmsdatalabvs3[$iss]->gcms_outgassing;
            } 
            else {
                $gcms1highervs3--;
                $gcms1belowvs3--;
            }
            }
        }
        }
      
             //-------------------------------------end gcms outgassing -------------------------------------//
    
                  //------------------------------------- gcms smear -------------------------------------//
    
    
                        //-------------------------------  slope dff. gcms smear----------------------------------//
         $avgsdff = $gcmsdff->avg('gcms_v_smear');
              
         $std_dff = $gcmsdff->select(DB::raw('STDDEV_SAMP(nt_v_data_gcms.gcms_v_smear) as std'))->first();
         if($std_dff->std == 0)
         {
            $dffck = null;
            $dffgcms_smear = null;
         }
         else
         {
            $dffck = ($oqadategcms->gcms_v_smear - $avgsdff) / $std_dff->std;
            $dffgcms_smear = number_format($dffck,3);
         }
         
        
          $avggcms_smear = $gcmsslope->avg('gcms_v_smear');
          $countgcms_smear = $gcmsslope->count('gcms_v_smear');
         
          $std_s = $gcmsslope->select(DB::raw('STDDEV_SAMP(nt_v_data_gcms.gcms_v_smear) as std'))->first();
          
          $stdgcms_smear = number_format($std_s->std,3);
          $slopepush=array();
         
          foreach($gcmsslopedff7 as $slope)
          {
            if($std_s->std == 0)
            {
                $slopest = ($slope->gcms_v_smear - $avggcms_smear)/1;
            }
            else
            {
                $slopest = ($slope->gcms_v_smear - $avggcms_smear)/$std_s->std;
            }
              
              array_push($slopepush,$slopest);
          }
          if(count($gcmsslopedff7) < 7)
          {
              $slopegcms_smear = null;
          }
          else
          {
            $linearregcms = slope::linearreg_slope($slopepush,sizeof($slopepush));
            $arrayck = sizeof($slopepush)-1;
            $slopefcgcms_smear = abs($linearregcms);
            $slopegcms_smear = number_format($linearregcms,3);
          }
          //-------------------------------end  slope dff. gcms smear----------------------------------//
        $gcms2higher = 0;
        $gcms2below = 0;

            foreach ( $gcmsdatalab as $item){ 
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
                if($datafirst->alkylacrylate == "N/A" )
                {
                    $natbd = 0;
                }
                else if($datafirst->alkylacrylate == "TBD")
                {
                    $natbd = 0;
                }
                else
                {
                    $natbd = 1;
                }
                if($natbd == 1)
                {
                    if ($datafirst->alkylacrylate < $item->gcms_smear) {
                        $gcms2higher++;
                        
                    } 
                    else if ($datafirst->alkylacrylate > $item->gcms_smear) {
                        $gcms2below++;
                    
                    } 
                    else {
                        $gcms2higher--;
                        $gcms2below--;
                    }
                }
            }
   
        $gcms2highervs3 = 0;
        $gcms2belowvs3 = 0;
        if( $countgcmssortdatalab >= $alarmtest3y)
        {
        $datalablgcms = $gcmsdatalabvs3[0]->gcms_smear;
        $iss = 0;
        foreach ( $gcmsdatalabvs3 as $item){ 
            $iss++;
            if( $iss <= $alarmtest3)
            {
            if ($datalablgcms < $gcmsdatalabvs3[$iss]->gcms_smear) {
                $gcms2highervs3++;
                $datalablgcms = $gcmsdatalabvs3[$iss]->gcms_smear;
            } 
            else if ($datalablgcms >  $gcmsdatalabvs3[$iss]->gcms_smear) {
                $gcms2belowvs3++;
                $datalablgcms = $gcmsdatalabvs3[$iss]->gcms_smear;
            } 
            else {
                $gcms2highervs3--;
                $gcms2belowvs3--;
            }
            }
        }
    }
             //-------------------------------------end gcms outgassing -------------------------------------//
             //------------------------------------- gcms low_mw -------------------------------------//
    
                          //-------------------------------  slope dff.  gcms low_mw----------------------------------//
         $avgsdff = $gcmsdff->avg('gcms_v_low_mw');
              
         $std_dff = $gcmsdff->select(DB::raw('STDDEV_SAMP(nt_v_data_gcms.gcms_v_low_mw) as std'))->first();
         if($std_dff->std ==0)
         {
            $dffck = null;
            $dffgcms_low_mw = null;
         }
         else
         {
            $dffck = ($oqadategcms->gcms_v_low_mw - $avgsdff) / $std_dff->std;
            $dffgcms_low_mw = number_format($dffck,3);
         }

          $avggcms_low_mw = $gcmsslope->avg('gcms_v_low_mw');
          $countgcms_low_mw = $gcmsslope->count('gcms_v_low_mw');
         
          $std_s = $gcmsslope->select(DB::raw('STDDEV_SAMP(nt_v_data_gcms.gcms_v_low_mw) as std'))->first();
          
          $stdgcms_low_mw = number_format($std_s->std,3);
          $slopepush=array();
         
          foreach($gcmsslopedff7 as $slope)
          {
            if($std_s->std == 0)
            {
                $slopest = ($slope->gcms_v_low_mw - $avggcms_low_mw)/1;
            }
            else
            {
                $slopest = ($slope->gcms_v_low_mw - $avggcms_low_mw)/$std_s->std;
            }
              
              array_push($slopepush,$slopest);
          }
          if(count($gcmsslopedff7) < 7)
          {
              $slopegcms_low_mw = null;
          }
          else
          {
            $linearregcms = slope::linearreg_slope($slopepush,sizeof($slopepush));
            $arrayck = sizeof($slopepush)-1;
            $slopefcgcms_low_mw = abs($linearregcms);
            $slopegcms_low_mw = number_format($linearregcms,3);
          }
          //-------------------------------end  slope dff.  gcms low_mw----------------------------------//
        $gcms3higher = 0;
        $gcms3below = 0;

            foreach ( $gcmsdatalab as $item){ 
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
                if($datafirst->low_MW == "N/A" )
                {
                    $natbd = 0;
                }
                else if($datafirst->low_MW == "TBD")
                {
                    $natbd = 0;
                }
                else
                {
                    $natbd = 1;
                }
                if($natbd == 1)
                {
                    if ($datafirst->low_MW < $item->gcms_low_mw) {
                        $gcms3higher++;
                        
                    } 
                    else if ($datafirst->low_MW > $item->gcms_low_mw) {
                        $gcms3below++;
                    
                    } 
                    else {
                        $gcms3higher--;
                        $gcms3below--;
                    }
                }
            }
    
        $gcms3highervs3 = 0;
        $gcms3belowvs3 = 0;
        if( $countgcmssortdatalab >= $alarmtest3y)
        {
        $datalablgcms = $gcmsdatalabvs3[0]->gcms_low_mw;
        $iss = 0;
        foreach ( $gcmsdatalabvs3 as $item){ 
            $iss++;
            if( $iss <= $alarmtest3)
            {
            if ($datalablgcms < $gcmsdatalabvs3[$iss]->gcms_low_mw) {
                $gcms3highervs3++;
                $datalablgcms = $gcmsdatalabvs3[$iss]->gcms_low_mw;
            } 
            else if ($datalablgcms >  $gcmsdatalabvs3[$iss]->gcms_low_mw) {
                $gcms3belowvs3++;
                $datalablgcms = $gcmsdatalabvs3[$iss]->gcms_low_mw;
            } 
            else {
                $gcms3highervs3--;
                $gcms3belowvs3--;
            }
            }
        }
    }
             //-------------------------------------end gcms low_mw -------------------------------------//
                     //------------------------------------- gcms high_mw -------------------------------------//
    
                                           //-------------------------------  slope dff. gcms high_mw----------------------------------//
         $avgsdff = $gcmsdff->avg('gcms_v_high_mw');
              
         $std_dff = $gcmsdff->select(DB::raw('STDDEV_SAMP(nt_v_data_gcms.gcms_v_high_mw) as std'))->first();
         if($std_dff->std ==0)
         {
            $dffck = null;
            $dffgcms_high_mw = null;
         }
         else
         {
            $dffck = ($oqadategcms->gcms_v_high_mw - $avgsdff) / $std_dff->std;
            $dffgcms_high_mw = number_format($dffck,3);
         }
    
         
          $avggcms_high_mw = $gcmsslope->avg('gcms_v_high_mw');
          $countgcms_high_mw = $gcmsslope->count('gcms_v_high_mw');
         
          $std_s = $gcmsslope->select(DB::raw('STDDEV_SAMP(nt_v_data_gcms.gcms_v_high_mw) as std'))->first();
          
          $stdgcms_high_mw = number_format($std_s->std,3);
          $slopepush=array();
         
          foreach($gcmsslopedff7 as $slope)
          {
            if($std_s->std == 0)
            {
                $slopest = ($slope->gcms_v_high_mw - $avggcms_high_mw)/1;
            }
            else
            {
                $slopest = ($slope->gcms_v_high_mw - $avggcms_high_mw)/$std_s->std;
            }
              
              array_push($slopepush,$slopest);
          }
          if(count($gcmsslopedff7) < 7)
          {
              $slopegcms_high_mw = null;
          }
          else
          {
            $linearregcms = slope::linearreg_slope($slopepush,sizeof($slopepush));
            $arrayck = sizeof($slopepush)-1;
            $slopefcgcms_high_mw = abs($linearregcms);
            $slopegcms_high_mw = number_format($linearregcms,3);
          }
          //-------------------------------end  slope dff. gcms high_mw----------------------------------//
        $gcms4higher = 0;
        $gcms4below = 0;

            foreach ( $gcmsdatalab as $item){ 
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
                if($datafirst->high_MW == "N/A" )
                {
                    $natbd = 0;
                }
                else if($datafirst->high_MW == "TBD")
                {
                    $natbd = 0;
                }
                else
                {
                    $natbd = 1;
                }
                if($natbd == 1)
                {
                    if ($datafirst->high_MW < $item->gcms_high_mw) {
                        $gcms4higher++;
                        
                    } 
                    else if ($datafirst->high_MW > $item->gcms_high_mw) {
                        $gcms4below++;
                    
                    } 
                    else {
                        $gcms4higher--;
                        $gcms4below--;
                    }
                }
            }
    
        $gcms4highervs3 = 0;
        $gcms4belowvs3 = 0;
        if( $countgcmssortdatalab >= $alarmtest3y)
        {
        $datalablgcms = $gcmsdatalabvs3[0]->gcms_high_mw;
        $iss = 0;
        foreach ( $gcmsdatalabvs3 as $item){ 
            $iss++;
            if( $iss <= $alarmtest3)
            {
            if ($datalablgcms < $gcmsdatalabvs3[$iss]->gcms_high_mw) {
                $gcms4highervs3++;
                $datalablgcms = $gcmsdatalabvs3[$iss]->gcms_high_mw;
            } 
            else if ($datalablgcms >  $gcmsdatalabvs3[$iss]->gcms_high_mw) {
                $gcms4belowvs3++;
                $datalablgcms = $gcmsdatalabvs3[$iss]->gcms_high_mw;
            } 
            else {
                $gcms4highervs3--;
                $gcms4belowvs3--;
            }
            }
        }
    }
    
        //-------------------------------------end gcms high_mw -------------------------------------//
         //-------------------------------------end gcms -------------------------------------//
         $chkgcmsshow = 1;
        }
        else
        {
            $chkgcmsshow = 0;
            $gcmsdatalab = null;
            $gcmsdatalaball = null;
            $gcms1higher= null;
            $gcms1below= null;
            $gcms1highervs3= null;
            $gcms1belowvs3= null;
            $gcms2higher= null;
            $gcms2below= null;
            $gcms2highervs3= null;
            $gcms2belowvs3= null;
            $gcms3higher= null;
            $gcms3below= null;
            $gcms3highervs3= null;
            $gcms3belowvs3= null;
            $gcms4higher= null;
            $gcms4below= null;
            $gcms4highervs3= null;
            $gcms4belowvs3= null;
            $slopegcms_out= null;
            $dffgcms_out= null;
            $avggcms_out= null;
            $countgcms_out= null;
            $stdgcms_out= null;
            $slopegcms_smear= null;
            $dffgcms_smear= null;
            $avggcms_smear= null;
            $countgcms_smear= null;
            $stdgcms_smear= null;
            $slopegcms_low_mw= null;
            $dffgcms_low_mw= null;
            $avggcms_low_mw= null;
            $countgcms_low_mw= null;
            $stdgcms_low_mw= null;
            $slopegcms_high_mw= null;
            $dffgcms_high_mw= null;
            $avggcms_high_mw= null;
            $countgcms_high_mw= null;
            $stdgcms_high_mw = null;
            $newgcmsdatalab = null;
    
        }
       
        //------------------------------------- ftir -------------------------------------//
        if(!empty($oqadateftir))
        {
            if($request->chkpdf == 0)
            {
              $yeardown = date('Y-m-d 00:00:00', strtotime($oqadateftir->save_date. ' - 1 year'));
              $dateend = $oqadateftir->save_date;
            }
            else if($request->chkpdf == 2)
            {
            $datenow = date("Y-m-d H:i:s");
            $yeardown = date('Y-m-d 00:00:00', strtotime($datenow. ' - 1 year'));
            $dateend = $datenow;
            }
            else
            {
              $yeardown = date('Y-m-d 00:00:00', strtotime($request->datestart));
              $dateend = date('Y-m-d 00:00:00', strtotime($request->dateend. ' + 1 day'));
            } 
        $ftirdatalab = DB::table('nt_datacf_ftir')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_datacf_ftir.id_createlab')
        ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
        ->where('nt_oqa_lab.save_date','<=',$dateend)
        ->where('nt_oqa_lab.id_model',$request->input('id'))
        ->where('nt_oqa_lab.ftir_confirm_st',1)
        ->limit($alarmtest2)->orderBy('nt_oqa_lab.save_date', 'desc')->get();

        $ftirdatalaball = DB::table('nt_datacf_ftir')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_datacf_ftir.id_createlab')
        ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
        ->where('nt_oqa_lab.save_date','<=',$dateend)
        ->where('nt_oqa_lab.id_model',$request->input('id'))
        ->where('nt_oqa_lab.ftir_confirm_st',1);
        if($request->chkpdf == 0 || $request->chkpdf == 2)
        {
            //$datalaball = $datalaball->limit(intval($oqasum->alpc_sum))->orderBy('nt_oqa_lab.save_date', 'desc')->get();
            $ftirdatalaball =  $ftirdatalaball->limit(intval($oqasum->ftir_sum))->orderBy('nt_oqa_lab.save_date', 'desc')->get();
        }
        else
        {
            $ftirdatalaball =  $ftirdatalaball->where('nt_oqa_lab.save_date','>=',$yeardown)->orderBy('nt_oqa_lab.save_date', 'desc')->get();
        }
        $newftirdatalab=array();
        foreach ($ftirdatalaball as $item){     
           $stdate = date('Y-m-d', strtotime($item->save_date));
           $data = DB::table('nt_model_history')
          ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
          ->where('nt_model_history.id_model', $item->id_model)
          ->where('nt_model_history.date_start','<=',$stdate)
          ->where('nt_model_history.date_end','>=',$stdate)
          ->where('nt_model_history.chkstatus',1)
          ->get(); 
          if($data->count() == 0)
          {
           $data1 = DB::table('nt_model_history')
           ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
           ->where('nt_model_history.id_model', $item->id_model)
           ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
           ->first();
           
           $data = DB::table('nt_model_history')
           ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
           ->where('nt_model_history.id_model', $item->id_model)
           ->where('nt_model_history.date_start','<=',$data1->date_start)  
           ->where('nt_model_history.date_end','>=',$data1->date_start)
           ->get(); 
          }
           array_push($newftirdatalab,$data);
        }
        $ftirdatalabvs3 = DB::table('nt_datacf_ftir')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_datacf_ftir.id_createlab')
        ->where('nt_oqa_lab.save_date','<=',$dateend)
        ->where('nt_oqa_lab.id_model',$request->input('id'))
        ->where('nt_oqa_lab.ftir_confirm_st',1)
        ->limit($alarmtest3)->orderBy('nt_oqa_lab.save_date', 'desc')->get();
        $countftirsortdatalab = count($ftirdatalabvs3);
        //$ftiryeardown = date('Y-m-d 00:00:00', strtotime($dateend. ' - 1 year'));
    
        $ftirdff = DB::table('nt_v_data_ftir')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_ftir.id_createlab')
        ->where('nt_oqa_lab.save_date','<',$dateend)
        ->where('nt_oqa_lab.save_date','>=',$yeardown)
        ->where('nt_oqa_lab.ftir_confirm_st',1)
        ->where('nt_oqa_lab.id_model',$request->input('id'));

        if($ftirdff->count() < 10 && $ftirdff->count() != 0)
        {        
         $ftirdfflimit = DB::table('nt_v_data_ftir')
         ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_ftir.id_createlab')
         ->where('nt_oqa_lab.save_date','<',$dateend)
         ->where('nt_oqa_lab.ftir_confirm_st',1)
         ->where('nt_oqa_lab.id_model',$request->input('id'))->orderby('nt_oqa_lab.save_date','desc')->limit(10)->get();
        
         $countdfflimit = $ftirdfflimit->count() -1 ;

         $ftirdff = DB::table('nt_v_data_ftir')
         ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_ftir.id_createlab')
         ->where('nt_oqa_lab.save_date','<',$dateend)
         ->where('nt_oqa_lab.save_date','>=',$ftirdfflimit[$countdfflimit]->save_date)
         ->where('nt_oqa_lab.ftir_confirm_st',1)
         ->where('nt_oqa_lab.id_model',$request->input('id'))->limit(10);
        }
     
        $ftirslope = DB::table('nt_v_data_ftir')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_ftir.id_createlab')
        ->where('nt_oqa_lab.save_date','<=',$dateend)
        ->where('nt_oqa_lab.save_date','>=',$yeardown)
        ->where('nt_oqa_lab.ftir_confirm_st',1)
        ->where('nt_oqa_lab.id_model',$request->input('id'));
    
        $ftirslopedff7 = DB::table('nt_v_data_ftir')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_ftir.id_createlab')
        ->where('nt_oqa_lab.save_date','<=',$dateend)
        ->where('nt_oqa_lab.save_date','>=',$yeardown)
        ->where('nt_oqa_lab.ftir_confirm_st',1)
        ->where('nt_oqa_lab.id_model',$request->input('id'))->OrderBy('nt_v_data_ftir.ftir_datesave','desc')->limit(7)->get();


        if($ftirslope->count() < 11 &&  $ftirslope->count() != 0)
        { 

            $ftirslopelimit = DB::table('nt_v_data_ftir')
            ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_ftir.id_createlab')
            ->where('nt_oqa_lab.save_date','<=',$dateend)
            ->where('nt_oqa_lab.ftir_confirm_st',1)
            ->where('nt_oqa_lab.id_model',$request->input('id'))->orderby('nt_oqa_lab.save_date','desc')->limit(11)->get();

         $countslopelimit = $ftirslopelimit->count() -1 ;

         $ftirslope = DB::table('nt_v_data_ftir')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_ftir.id_createlab')
        ->where('nt_oqa_lab.save_date','<=',$dateend)
        ->where('nt_oqa_lab.save_date','>=',$ftirslopelimit[$countslopelimit]->save_date)
        ->where('nt_oqa_lab.ftir_confirm_st',1)
        ->where('nt_oqa_lab.id_model',$request->input('id'));

        $countslopelimit1 = $ftirslopelimit->count();
         if($countslopelimit1 <7)
         {
             $numx =  $countslopelimit ;
         }
         else
         {
             $numx = 6;
         }

             
    
         $ftirslopedff7 = DB::table('nt_v_data_ftir')
         ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_ftir.id_createlab')
         ->where('nt_oqa_lab.save_date','<=',$dateend)
         ->where('nt_oqa_lab.save_date','>=',$ftirslopelimit[$numx]->save_date)
         ->where('nt_oqa_lab.ftir_confirm_st',1)
         ->where('nt_oqa_lab.id_model',$request->input('id'))->OrderBy('nt_v_data_ftir.ftir_datesave','desc')->limit(7)->get();
        }

                                       //-------------------------------  slope dff. ftir----------------------------------//
                                       $avgsdff = $ftirdff->avg('ftir_v_silicone');
                                       $std_dff = $ftirdff->select(DB::raw('STDDEV_SAMP(nt_v_data_ftir.ftir_v_silicone) as std'))->first();
                                       if($std_dff->std ==0)
                                       {
                                        $dffck = null;
                                        $dffftir_sc = null;
                                       }
                                       else
                                       {
                                        $dffck = ($oqadateftir->ftir_v_silicone - $avgsdff) / $std_dff->std;
                                        $dffftir_sc = number_format($dffck,3);
                                       }
                                  
                                      
                                      // dd(1);
                                      
                                       
                                        $avgftir_sc = $ftirslope->avg('ftir_v_silicone');
                                        $countftir_sc = $ftirslope->count('ftir_v_silicone');
                                        $std_s = $ftirslope->select(DB::raw('STDDEV_SAMP(nt_v_data_ftir.ftir_v_silicone) as std'))->first();
                                        $stdftir_sc = number_format($std_s->std,3);
                                        $slopepush=array();
                                        foreach($ftirslopedff7 as $slope)
                                        {
                                            if($std_s->std == 0)
                                            {
                                                $slopest = ($slope->ftir_v_silicone - $avgftir_sc)/1;
                                            }
                                            else
                                            {
                                                $slopest = ($slope->ftir_v_silicone - $avgftir_sc)/$std_s->std;
                                            }   
                                            array_push($slopepush,$slopest);
                                        }
                                        if(count($ftirslopedff7) < 7)
                                        {
                                            $slopeftir_sc = null;
                                        }
                                        else
                                        {
                                            $linearreftir = slope::linearreg_slope($slopepush,sizeof($slopepush));
                                            $arrayck = sizeof($slopepush)-1;
                                            $slopefcftir_sc = abs($linearreftir);
                                            $slopeftir_sc = number_format($linearreftir,3);
                                        }
                            
                                        //-------------------------------end  slope dff. ftir----------------------------------//
        $ftirhigher = 0;
        $ftirbelow = 0;
            foreach ( $ftirdatalab as $item){ 
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
                if($datafirst->ftir == "N/A" )
                {
                    $natbd = 0;
                }
                else if($datafirst->ftir == "TBD")
                {
                    $natbd = 0;
                }
                else
                {
                    $natbd = 1;
                }
                if($natbd == 1)
                {
                    if ($datafirst->ftir < $item->ftir_silicone) {
                        $ftirhigher++;              
                    } 
                    else if ($datafirst->ftir > $item->ftir_silicone) {
                        $ftirbelow++;             
                    } 
                    else {
                        $ftirhigher--;
                        $ftirbelow--;
                    }
                }
            }
    
        $ftirhighervs3 = 0;
        $ftirbelowvs3 = 0;
        if($countftirsortdatalab >= $alarmtest3y)
        {
        $datalablftir = $ftirdatalabvs3[0]->ftir_silicone;
        $iss = 0;
        foreach ( $ftirdatalabvs3 as $item){ 
            $iss++;
            if( $iss <= $alarmtest3)
            {
            if ($datalablftir < $ftirdatalabvs3[$iss]->ftir_silicone) {
                $ftirhighervs3++;
                $datalablftir = $ftirdatalabvs3[$iss]->ftir_silicone;
            } 
            else if ($datalablftir >  $ftirdatalabvs3[$iss]->ftir_silicone) {
                $ftirbelowvs3++;
                $datalablftir = $ftirdatalabvs3[$iss]->ftir_silicone;
            } 
            else {
                $ftirhighervs3--;
                $ftirbelowvs3--;
            }
            }
        }
    }
             //-------------------------------------end ftir -------------------------------------//
             $chkftirshow = 1;
            }
            else
            {
                $chkftirshow = 0;
                $ftirdatalab = null;
                $ftirdatalaball = null;
                $ftirhigher = null;
                $ftirbelow= null;
                $ftirhighervs3= null;
                $ftirbelowvs3= null;
                $slopeftir_sc= null;
                $dffftir_sc= null;
                $avgftir_sc= null;
                $countftir_sc= null;
                $stdftir_sc= null;
                $newftirdatalab = null;
            }
            if(!empty($oqadatesemtest))
            {

                $semtestdatalab = $oqadatesemtest;
            }
            else
            {
                $semtestdatalab = null;
            }

            return response()->json([
                'success' => 1,
                'oqa' =>$oqa, 
                'oqalot' =>$oqalot, 
                'model' =>$model,
                'modelname' =>$modelname,   
                'datalab' => $datalab,
                'datalaball' => $datalaball,
                'newlpcdatalab' => $newlpcdatalab,
                'modelparameter'=>$modelparameter,
                'sp' => $sp,
                'tl' => $tl,
                'ucl' => $ucl,
                'lcl' => $lcl,
                'cl' => $cl,
                'is' => $is,
                'higher' => $higher,
                'below' => $below,
                'highervs3' => $highervs3,
                'belowvs3' => $belowvs3,
                'semdatalab' => $semdatalab,
                'semdatalaball' =>  $semdatalaball,
                'newsemdatalab' => $newsemdatalab,
                'sem1higher' => $sem1higher,
                'sem1below' => $sem1below,
                'sem1highervs3' => $sem1highervs3,
                'sem1belowvs3' => $sem1belowvs3,
                'sem2higher' => $sem2higher,
                'sem2below' => $sem2below,
                'sem2highervs3' => $sem2highervs3,
                'sem2belowvs3' => $sem2belowvs3,
                'sem3higher' => $sem3higher,
                'sem3below' => $sem3below,
                'sem3highervs3' => $sem3highervs3,
                'sem3belowvs3' => $sem3belowvs3,
                'sem31higher' => $sem31higher,
                'sem31below' => $sem31below,
                'sem31highervs3' => $sem31highervs3,
                'sem31belowvs3' => $sem31belowvs3,
                'sem32higher' => $sem32higher,
                'sem32below' => $sem32below,
                'sem32highervs3' => $sem32highervs3,
                'sem32belowvs3' => $sem32belowvs3,
                'sem33higher' => $sem33higher,
                'sem33below' => $sem33below,
                'sem33highervs3' => $sem33highervs3,
                'sem33belowvs3' => $sem33belowvs3,
                'sem4higher' => $sem4higher,
                'sem4below' => $sem4below,
                'sem4highervs3' => $sem4highervs3,
                'sem4belowvs3' => $sem4belowvs3,
                'sem5higher' => $sem5higher,
                'sem5below' => $sem5below,
                'sem5highervs3' => $sem5highervs3,
                'sem5belowvs3' => $sem5belowvs3,
                'sem51higher' => $sem51higher,
                'sem51below' => $sem51below,
                'sem51highervs3' => $sem51highervs3,
                'sem51belowvs3' => $sem51belowvs3,
                'sem6higher' => $sem6higher,
                'sem6below' => $sem6below,
                'sem6highervs3' => $sem6highervs3,
                'sem6belowvs3' => $sem6belowvs3,
                'icdatalab' => $icdatalab,
                'icdatalaball' => $icdatalaball,
                'newicdatalab' => $newicdatalab,
                'icanionhigher' =>$icanionhigher,
                'icanionbelow' =>$icanionbelow,
                'icanionhighervs3' =>$icanionhighervs3,
                'icanionbelowvs3' =>$icanionbelowvs3,
                'sulphathigher' => $sulphathigher,
                'sulphatbelow' =>$sulphatbelow,
                'sulphathighervs3' =>$sulphathighervs3,
                'sulphatbelowvs3' =>$sulphatbelowvs3,
                'cationhigher' => $cationhigher,
                'cationbelow' => $cationbelow,
                'cationhighervs3' => $cationhighervs3,
                'cationbelowvs3' => $cationbelowvs3,
                'gcmsdatalab' => $gcmsdatalab,
                'gcmsdatalaball' => $gcmsdatalaball,
                'newgcmsdatalab' => $newgcmsdatalab,
                'gcms1higher' => $gcms1higher,
                'gcms1below' =>$gcms1below,
                'gcms1highervs3' =>$gcms1highervs3,
                'gcms1belowvs3' =>$gcms1belowvs3,
                'gcms2higher' => $gcms2higher,
                'gcms2below' =>$gcms2below,
                'gcms2highervs3' =>$gcms2highervs3,
                'gcms2belowvs3' =>$gcms2belowvs3,
                'gcms3higher' => $gcms3higher,
                'gcms3below' =>$gcms3below,
                'gcms3highervs3' =>$gcms3highervs3,
                'gcms3belowvs3' =>$gcms3belowvs3,
                'gcms4higher' => $gcms4higher,
                'gcms4below' =>$gcms4below,
                'gcms4highervs3' =>$gcms4highervs3,
                'gcms4belowvs3' =>$gcms4belowvs3,
                'ftirdatalab' =>$ftirdatalab,
                'ftirdatalaball' =>  $ftirdatalaball,
                'newftirdatalab' =>  $newftirdatalab,
                'ftirhigher' =>$ftirhigher,
                'ftirbelow' =>$ftirbelow,
                'ftirhighervs3' =>$ftirhighervs3,
                'ftirbelowvs3' =>$ftirbelowvs3,
                'slopelpc' =>$slopelpc,
                'dfflpc' =>$dfflpc,
                'avgslope' =>$avgslope,
                'stdlpc' =>$stdlpc,
                'countlpc' => $countlpc,
                'slopesem1' =>$slopesem1,
                'dffsem1' => $dffsem1,
                'avgsem1' =>$avgsem1,
                'countsem1' =>$countsem1,
                'stdsem1' =>$stdsem1,
                'slopesem2' =>$slopesem2,
                'dffsem2' => $dffsem2,
                'avgsem2' =>$avgsem2,
                'countsem2' =>$countsem2,
                'stdsem2' =>$stdsem2,
                'slopesem3' =>$slopesem3,
                'dffsem3' => $dffsem3,
                'avgsem3' =>$avgsem3,
                'countsem3' =>$countsem3,
                'stdsem3' =>$stdsem3,   
                'slopesem3_1' =>$slopesem3_1,
                'dffsem3_1' => $dffsem3_1,
                'avgsem3_1' =>$avgsem3_1,
                'countsem3_1' =>$countsem3_1,
                'stdsem3_1' =>$stdsem3_1,
                'slopesem3_2' =>$slopesem3_2,
                'dffsem3_2' => $dffsem3_2,
                'avgsem3_2' =>$avgsem3_2,
                'countsem3_2' =>$countsem3_2,
                'stdsem3_2' =>$stdsem3_2,
                'slopesem3_3' =>$slopesem3_3,
                'dffsem3_3' => $dffsem3_3,
                'avgsem3_3' =>$avgsem3_3,
                'countsem3_3' =>$countsem3_3,
                'stdsem3_3' =>$stdsem3_3,           
                'slopesem4' =>$slopesem4,
                'dffsem4' => $dffsem4,
                'avgsem4' =>$avgsem4,
                'countsem4' =>$countsem4,
                'stdsem4' =>$stdsem4,
                'slopesem5' =>$slopesem5,
                'dffsem5' => $dffsem5,
                'avgsem5' =>$avgsem5,
                'countsem5' =>$countsem5,
                'stdsem5' =>$stdsem5,
                'slopesem5_1' =>$slopesem5_1,
                'dffsem5_1' => $dffsem5_1,
                'avgsem5_1' =>$avgsem5_1,
                'countsem5_1' =>$countsem5_1,
                'stdsem5_1' =>$stdsem5_1,
                'slopesem6' =>$slopesem6,
                'dffsem6' => $dffsem6,
                'avgsem6' =>$avgsem6,
                'countsem6' =>$countsem6,
                'stdsem6' =>$stdsem6,    
                'slopeic_anion' =>$slopeic_anion,
                'dffic_anion' => $dffic_anion,
                'avgic_anion' =>$avgic_anion,
                'countic_anion' =>$countic_anion,
                'stdic_anion' =>$stdic_anion,          
                'slopeic_sulphate' =>$slopeic_sulphate,
                'dffic_sulphate' => $dffic_sulphate,
                'avgic_sulphate' =>$avgic_sulphate,
                'countic_sulphate' =>$countic_sulphate,
                'stdic_sulphate' =>$stdic_sulphate,
                'slopeic_cation' =>$slopeic_cation,
                'dffic_cation' => $dffic_cation,
                'avgic_cation' =>$avgic_cation,
                'countic_cation' =>$countic_cation,
                'stdic_cation' =>$stdic_cation,
                'slopegcms_out' =>$slopegcms_out,
                'dffgcms_out' => $dffgcms_out,
                'avggcms_out' =>$avggcms_out,
                'countgcms_out' =>$countgcms_out,
                'stdgcms_out' =>$stdgcms_out,
                'slopegcms_smear' =>$slopegcms_smear,
                'dffgcms_smear' => $dffgcms_smear,
                'avggcms_smear' =>$avggcms_smear,
                'countgcms_smear' =>$countgcms_smear,
                'stdgcms_smear' =>$stdgcms_smear,
                'slopegcms_low_mw' =>$slopegcms_low_mw,
                'dffgcms_low_mw' => $dffgcms_low_mw,
                'avggcms_low_mw' =>$avggcms_low_mw,
                'countgcms_low_mw' =>$countgcms_low_mw,
                'stdgcms_low_mw' =>$stdgcms_low_mw,
                'slopegcms_high_mw' =>$slopegcms_high_mw,
                'dffgcms_high_mw' => $dffgcms_high_mw,
                'avggcms_high_mw' =>$avggcms_high_mw,
                'countgcms_high_mw' =>$countgcms_high_mw,
                'stdgcms_high_mw' =>$stdgcms_high_mw,
    
                'slopeftir_sc' =>$slopeftir_sc,
                'dffftir_sc' => $dffftir_sc,
                'avgftir_sc' =>$avgftir_sc,
                'countftir_sc' =>$countftir_sc,
                'stdftir_sc' =>$stdftir_sc,
                'chklpcshow' =>$chklpcshow,
                'chksemshow' =>$chksemshow,
                'chkicshow' =>$chkicshow,
                'chkgcmsshow' =>$chkgcmsshow,
                'chkftirshow' =>$chkftirshow,
                'datalabdone' => $datalabdone,
                
                'chkoqadate' =>$chkoqadate,
                'chkoqadatesem' =>$chkoqadatesem,
                'chkoqadateic' =>$chkoqadateic,
                'chkoqadategcms' =>$chkoqadategcms,
                'chkoqadateftir' => $chkoqadateftir,
                'semtestdatalab' => $semtestdatalab,

                'alarmtest2' =>$alarmtest2,
                'alarmtest3' => $alarmtest3, 
                'alarmtest3y' => $alarmtest3y, 
                
      
                ]);
        }
}
