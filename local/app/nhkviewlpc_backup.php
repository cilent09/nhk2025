<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
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

class nhkviewlpc
{
        
      static function lpc($mailidcreatelab,$mailidmodel)
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
    
            if(!empty($oqadate))
            {
        //-------------------------------------lpc----------------------------------------//
       //------------------------------- slope dff. lpc ----------------------------------//

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

                    $data = DB::table('nt_model_history')
                    ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model_history.id_model', $item->id_model)
                    ->where('nt_model_history.date_start','<=',$data1->date_start)  
                    ->where('nt_model_history.date_end','>=',$data1->date_start)
                    ->get(); 
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
                if( $slopelpc >= 0.5 || $slopelpc <= -0.5 || $dfflpc >= 3.5) {
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


}
