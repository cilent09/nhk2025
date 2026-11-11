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

class nhkviewftir
{
      static function ftir($mailidcreatelab,$mailidmodel)
      {
        $oqa = DB::table('nt_oqa_lab')
            ->where('id_model',$mailidmodel)
            ->get();
            $oqalot = DB::table('nt_oqa_lab')
            ->where('id_createlab',$mailidcreatelab)
            ->first();
            $oqadateftir = DB::table('nt_data_ftir')
            ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ftir.id_createlab')
            ->where('nt_data_ftir.id_createlab',$mailidcreatelab)->where('nt_data_ftir.ftir_st',1)
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
    
            //------------------------------------- ftir -------------------------------------//
            if(!empty($oqadateftir))
            {
                  $yeardown = date('Y-m-d 00:00:00', strtotime($oqadateftir->save_date. ' - 1 year'));
                  $dateend = $oqadateftir->save_date;
          
            $ftirdatalab = DB::table('nt_data_ftir')
            ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ftir.id_createlab')
            ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
            ->where('nt_oqa_lab.save_date','<=',$dateend)
            ->where('nt_oqa_lab.id_model',$mailidmodel)
            ->where('nt_oqa_lab.ftir_confirm_st',1)
            ->limit(9)->orderBy('nt_oqa_lab.save_date', 'desc')->get();
      
            $ftirdatalaball = DB::table('nt_data_ftir')
            ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ftir.id_createlab')
            ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
            ->where('nt_oqa_lab.save_date','<=',$dateend)
            ->where('nt_oqa_lab.id_model',$mailidmodel)
            ->where('nt_oqa_lab.ftir_confirm_st',1)->limit(intval($oqasum->ftir_sum))->orderBy('nt_oqa_lab.save_date', 'desc')->get();
          
            $newftirdatalab=array();
            foreach ($ftirdatalaball as $item){     
                $stdate = date('Y-m-d', strtotime($item->save_date));
                $data = DB::table('nt_model_history')
               ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
               ->where('nt_model_history.id_model', $item->id_model)
               ->where('nt_model_history.date_start','<=',$stdate)
               ->where('nt_model_history.date_end','>=',$stdate)
               ->get(); 
                array_push($newftirdatalab,$data);
            }
            $datalcl =  @$newftirdatalab[0][2];
            $dataucl =  @$newftirdatalab[0][1];


            $ftirdatalabvs3 = DB::table('nt_data_ftir')
            ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ftir.id_createlab')
            ->where('nt_oqa_lab.save_date','<=',$dateend)
            ->where('nt_oqa_lab.id_model',$mailidmodel)
            ->where('nt_oqa_lab.ftir_confirm_st',1)
            ->limit(6)->orderBy('nt_oqa_lab.save_date', 'desc')->get();
            $countftirsortdatalab = count($ftirdatalabvs3);
            //$yeardown = date('Y-m-d 00:00:00', strtotime($dateend. ' - 1 year'));
        
            $ftirdff = DB::table('nt_v_data_ftir')
            ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_ftir.id_createlab')
            ->where('nt_oqa_lab.save_date','<',$dateend)
            ->where('nt_oqa_lab.save_date','>=',$yeardown)
            ->where('nt_oqa_lab.ftir_confirm_st',1)
            ->where('nt_oqa_lab.id_model',$mailidmodel);
    
            if($ftirdff->count() < 10 && $ftirdff->count() != 0)
            {        
             $ftirdfflimit = DB::table('nt_v_data_ftir')
             ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_ftir.id_createlab')
             ->where('nt_oqa_lab.save_date','<',$dateend)
             ->where('nt_oqa_lab.ftir_confirm_st',1)
             ->where('nt_oqa_lab.id_model',$mailidmodel)->orderby('nt_oqa_lab.save_date','desc')->limit(10)->get();
            
             $countdfflimit = $ftirdfflimit->count() -1 ;
    
             $ftirdff = DB::table('nt_v_data_ftir')
             ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_ftir.id_createlab')
             ->where('nt_oqa_lab.save_date','<',$dateend)
             ->where('nt_oqa_lab.save_date','>=',$ftirdfflimit[$countdfflimit]->save_date)
             ->where('nt_oqa_lab.ftir_confirm_st',1)
             ->where('nt_oqa_lab.id_model',$mailidmodel)->limit(10);
            }
         
            $ftirslope = DB::table('nt_v_data_ftir')
            ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_ftir.id_createlab')
            ->where('nt_oqa_lab.save_date','<=',$dateend)
            ->where('nt_oqa_lab.save_date','>=',$yeardown)
            ->where('nt_oqa_lab.ftir_confirm_st',1)
            ->where('nt_oqa_lab.id_model',$mailidmodel);
        
            $ftirslopedff7 = DB::table('nt_v_data_ftir')
            ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_ftir.id_createlab')
            ->where('nt_oqa_lab.save_date','<=',$dateend)
            ->where('nt_oqa_lab.save_date','>=',$yeardown)
            ->where('nt_oqa_lab.ftir_confirm_st',1)
            ->where('nt_oqa_lab.id_model',$mailidmodel)->OrderBy('nt_v_data_ftir.ftir_datesave','desc')->limit(7)->get();
    
    
            if($ftirslope->count() < 11 && $ftirslope->count() != 0)
            { 
    
                $ftirslopelimit = DB::table('nt_v_data_ftir')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_ftir.id_createlab')
                ->where('nt_oqa_lab.save_date','<=',$dateend)
                ->where('nt_oqa_lab.ftir_confirm_st',1)
                ->where('nt_oqa_lab.id_model',$mailidmodel)->orderby('nt_oqa_lab.save_date','desc')->limit(11)->get();
    
             $countslopelimit = $ftirslopelimit->count() -1 ;
    
             $ftirslope = DB::table('nt_v_data_ftir')
            ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_ftir.id_createlab')
            ->where('nt_oqa_lab.save_date','<=',$dateend)
            ->where('nt_oqa_lab.save_date','>=',$ftirslopelimit[$countslopelimit]->save_date)
            ->where('nt_oqa_lab.ftir_confirm_st',1)
            ->where('nt_oqa_lab.id_model',$mailidmodel);
    
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
             ->where('nt_oqa_lab.id_model',$mailidmodel)->OrderBy('nt_v_data_ftir.ftir_datesave','desc')->limit(7)->get();
            }
                                           //-------------------------------  slope dff. ftir----------------------------------//
                                           $avgsdff = $ftirdff->avg('ftir_silicone');
                                           $std_dff = $ftirdff->select(DB::raw('STDDEV_SAMP(nt_v_data_ftir.ftir_silicone) as std'))->first();
                                           if($std_dff->std ==0)
                                           {
                                            $dffck = ($oqadateftir->ftir_silicone - $avgsdff) / 1;
                                           }
                                           else
                                           {
                                            $dffck = ($oqadateftir->ftir_silicone - $avgsdff) / $std_dff->std;
                                           }
                                      
                                          
                                          // dd(1);
                                           $dffftir_sc = number_format($dffck,3);
                                           
                                            $avgftir_sc = $ftirslope->avg('ftir_silicone');
                                            $countftir_sc = $ftirslope->count('ftir_silicone');
                                            $std_s = $ftirslope->select(DB::raw('STDDEV_SAMP(nt_v_data_ftir.ftir_silicone) as std'))->first();
                                            $stdftir_sc = number_format($std_s->std,3);
                                            $slopepush=array();
                                            foreach($ftirslopedff7 as $slope)
                                            {
                                                if($std_dff->std == 0)
                                                {
                                                    $slopest = ($slope->ftir_silicone - $avgftir_sc)/1;
                                                }
                                                else
                                                {
                                                    $slopest = ($slope->ftir_silicone - $avgftir_sc)/$std_s->std;
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
                                                $slopefcftir_sc = abs($linearreftir[$arrayck]);
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
            if($countftirsortdatalab >= 7)
            {
            $datalablftir = $ftirdatalabvs3[0]->ftir_silicone;
            $iss = 0;
            foreach ( $ftirdatalabvs3 as $item){ 
                $iss++;
                if( $iss <= 6)
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

                 $error = 0;
            
                 if ($ftirhigher >= 9) {
                     $error++;
                     
                 } else if ($ftirbelow >= 9) {
                     $error++;
                 } else {
     
                 }
                 
                 if ($ftirhighervs3 >= 6) {
                     $error++;
                 } else if ($ftirbelowvs3 >= 6) {
                     $error++;
                 } else {      
                 }
                
                 if ($ftirdatalab[0]->ftir_silicone < @$datalcl->ftir) {
                    
                    if(is_numeric(@$datalcl->ftir) == true){
                      
                         $error++;
                      }
                 } 
                 else if ($ftirdatalab[0]->ftir_silicone > @$dataucl->ftir) {
                   
                    if(is_numeric(@$dataucl->ftir) == true){
                     
                        $error++;
                     }
                 } 
                 else{
                 }
                 if ($ftirdatalab[0]->ftir_silicone > $is->ftir) {
                    
                    if(is_numeric($is->ftir) == true){
                     
                        $error++;
                     }
                 } else {
                 }
     
                 if($slopeftir_sc != null)
                 {
                    if( $slopeftir_sc >= 0.5 || $slopeftir_sc <= -0.5 || $dffftir_sc >= 3.5) {
                        $error++;
                    }
                 }
                 else
                 {
                     if ($dffftir_sc >= 3.5 ) {
                         $error++;
                     }
                 }
              
               
                 $returndata = $error;
                }
                else
                {
                    $returndata = 0;
                }
               
              
      
        return  $returndata;
      }


}
