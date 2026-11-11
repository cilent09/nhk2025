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

class nhkviewic
{
      static function ic($mailidcreatelab,$mailidmodel)
      {
          
        $oqa = DB::table('nt_oqa_lab')
            ->where('id_model',$mailidmodel)
            ->get();
            $oqalot = DB::table('nt_oqa_lab')
            ->where('id_createlab',$mailidcreatelab)
            ->first();

            $oqadateic = DB::table('nt_data_ic')
            ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ic.id_createlab')
            ->where('nt_data_ic.id_createlab',$mailidcreatelab)->where('nt_data_ic.ic_st',1)
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
    
          
           //-------------------------------------ic-------------------------------------//
           
           if(!empty($oqadateic))
           {

              $yeardown = date('Y-m-d 00:00:00', strtotime($oqadateic->save_date. ' - 1 year'));
              $dateend = $oqadateic->save_date;
 
           $icdatalab = DB::table('nt_data_ic')
           ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ic.id_createlab')
           ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
           ->where('nt_oqa_lab.save_date','<=',$dateend)
           ->where('nt_oqa_lab.id_model',$mailidmodel)
           ->where('nt_oqa_lab.ic_confirm_st',1)
           ->limit(9)->orderBy('nt_oqa_lab.save_date', 'desc')->get();

            $oqasum_ic_sum = intval($oqasum->ic_sum);
            if($oqasum_ic_sum == 0){
                $oqasum_ic_sum = 1;
            }

           $icdatalaball = DB::table('nt_data_ic')
           ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ic.id_createlab')
           ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
           ->where('nt_oqa_lab.save_date','<=',$dateend)
           ->where('nt_oqa_lab.id_model',$mailidmodel)
        //    ->where('nt_oqa_lab.ic_confirm_st',1)->limit(intval($oqasum->ic_sum))->orderBy('nt_oqa_lab.save_date', 'desc')->get();
           ->where('nt_oqa_lab.ic_confirm_st',1)->limit($oqasum_ic_sum)->orderBy('nt_oqa_lab.save_date', 'desc')->get();

            $newicdatalab=array();
            $datalcl = null;
            $dataucl = null;
            foreach ($icdatalaball as $item){     
               $stdate = date('Y-m-d', strtotime($item->save_date));
               $data = DB::table('nt_model_history')
              ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
              ->where('nt_model_history.id_model', $item->id_model)
              ->where('nt_model_history.date_start','<=',$stdate)
              ->where('nt_model_history.date_end','>=',$stdate)
              ->get(); 
               array_push($newicdatalab,$data);
            }

            if(!empty($newicdatalab[0][0])){
                $datalcl =  $newicdatalab[0][2];
                $dataucl =  $newicdatalab[0][1];
            }

           $icdatalabvs3 = DB::table('nt_data_ic')
           ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ic.id_createlab')
           ->where('nt_oqa_lab.save_date','<=',$dateend)
           ->where('nt_oqa_lab.id_model',$mailidmodel)
           ->where('nt_oqa_lab.ic_confirm_st',1)
           ->limit(6)->orderBy('nt_oqa_lab.save_date', 'desc')->get();
           $counticsortdatalab = count($icdatalabvs3);
           $icdff = DB::table('nt_v_data_ic')
           ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_ic.id_createlab')
            ->where('nt_oqa_lab.save_date','<=',$dateend)
            ->where('nt_oqa_lab.save_date','>=',$yeardown)
            ->where('nt_oqa_lab.ic_confirm_st',1)
           ->where('nt_oqa_lab.id_model',$mailidmodel);
           
           if($icdff->count() < 10 && $icdff->count() != 0)
           {
            $icdfflimit = DB::table('nt_v_data_ic')
            ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_ic.id_createlab')
             ->where('nt_oqa_lab.save_date','<=',$dateend)
             ->where('nt_oqa_lab.ic_confirm_st',1)
            ->where('nt_oqa_lab.id_model',$mailidmodel)->orderby('nt_oqa_lab.save_date','desc')->limit(10)->get(); 
           
            $countdfflimit = $icdfflimit->count() -1 ;

            $icdff = DB::table('nt_v_data_ic')
            ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_ic.id_createlab')
             ->where('nt_oqa_lab.save_date','<=',$dateend)
             ->where('nt_oqa_lab.save_date','>=',$icdfflimit[$countdfflimit]->save_date)
             ->where('nt_oqa_lab.ic_confirm_st',1)
            ->where('nt_oqa_lab.id_model',$mailidmodel)->limit(10);
           }
        
           $icslope = DB::table('nt_v_data_ic')
           ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_ic.id_createlab')
           ->where('nt_oqa_lab.save_date','<=',$dateend)
           ->where('nt_oqa_lab.save_date','>=',$yeardown)
           ->where('nt_oqa_lab.ic_confirm_st',1)
           ->where('nt_oqa_lab.id_model',$mailidmodel);
           $icslopedff7 = DB::table('nt_v_data_ic')
           ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_ic.id_createlab')
            ->where('nt_oqa_lab.save_date','<=',$dateend)
            ->where('nt_oqa_lab.save_date','>=',$yeardown)
            ->where('nt_oqa_lab.ic_confirm_st',1)
           ->where('nt_oqa_lab.id_model',$mailidmodel)->OrderBy('nt_oqa_lab.save_date','desc')->limit(7)->get();


           if($icslope->count() < 11 && $icslope->count() != 0)
           { 
            $icslopelimit = DB::table('nt_v_data_ic')
            ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_ic.id_createlab')
            ->where('nt_oqa_lab.save_date','<=',$dateend)
            ->where('nt_oqa_lab.ic_confirm_st',1)
            ->where('nt_oqa_lab.id_model',$mailidmodel)->orderby('nt_oqa_lab.save_date','desc')->limit(11)->get();
            $countslopelimit = $icslopelimit->count() -1 ;

            $icslope = DB::table('nt_v_data_ic')
            ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_ic.id_createlab')
            ->where('nt_oqa_lab.save_date','<=',$dateend)
            ->where('nt_oqa_lab.save_date','>=',$icslopelimit[$countslopelimit]->save_date)
            ->where('nt_oqa_lab.ic_confirm_st',1)
            ->where('nt_oqa_lab.id_model',$mailidmodel);
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
            ->where('nt_oqa_lab.id_model',$mailidmodel)->OrderBy('nt_oqa_lab.save_date','desc')->limit(7)->get();
           }
    
    
            //-------------------------------------ic anion-------------------------------------//
    
                //------------------------------- slope dff. ic anion ----------------------------------//
                $avgsdff = $icdff->avg('ic_anion_up');
              
                $std_dff = $icdff->select(DB::raw('STDDEV_SAMP(nt_v_data_ic.ic_anion_up) as std'))->first();
                if($std_dff->std == 0)
                {
                $dffck = ($oqadateic->ic_anion_up - $avgsdff) / 1;
                }
                else
                {
                    $dffck = ($oqadateic->ic_anion_up - $avgsdff) / $std_dff->std;
                }
            
                $dffic_anion = number_format($dffck,3);
                 $avgic_anion = $icslope->avg('ic_anion_up');
                 $countic_anion = $icslope->count('ic_anion_up');
                
                 $std_s = $icslope->select(DB::raw('STDDEV_SAMP(nt_v_data_ic.ic_anion_up) as std'))->first();
                
                 $stdic_anion = number_format($std_s->std,3);
                 $slopepush=array();
                
                 foreach($icslopedff7 as $slope)
                 {
                    if($std_dff->std == 0)
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
                    
                    if(!empty($data1)){
                        $datafirst = DB::table('nt_model_history')
                        ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model_history.id_model', $item->id_model)
                        ->where('nt_model_history.date_start','<=',$data1->date_start)  
                        ->where('nt_model_history.date_end','>=',$data1->date_start)
                        ->where('nt_model_history.id_model_type','=',5)
                        ->first(); 
                    }
                }

                if(!empty($datafirst)){
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
                }
                else
                {
                    $natbd = 1;
                }

                if($natbd == 1)
                {
                    if(!empty($datafirst)){
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
                    else {
                        $icanionhigher--;
                        $icanionbelow--;
                    }
                }
            }
         
           $icanionhighervs3 = 0;
           $icanionbelowvs3 = 0;
           if($counticsortdatalab >= 7 )
           {
           $datalablic = $icdatalabvs3[0]->ic_anion_up;
           $iss = 0;
           foreach ( $icdatalabvs3 as $item){ 
               $iss++;
               if( $iss <= 6)
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
                        $avgsdff = $icdff->avg('ic_sulphate');
              
                        $std_dff = $icdff->select(DB::raw('STDDEV_SAMP(nt_v_data_ic.ic_sulphate) as std'))->first();
                        if($std_dff->std == 0)
                            {
                                $dffck = ($oqadateic->ic_sulphate - $avgsdff) /1;
                            }
                            else
                            {
                                $dffck = ($oqadateic->ic_sulphate - $avgsdff) / $std_dff->std;
                            }
                    
                        $dffic_sulphate = number_format($dffck,3);
                         $avgic_sulphate = $icslope->avg('ic_sulphate');
                         $countic_sulphate = $icslope->count('ic_sulphate');
                        
                         $std_s = $icslope->select(DB::raw('STDDEV_SAMP(nt_v_data_ic.ic_sulphate) as std'))->first();
                        
                         $stdic_sulphate = number_format($std_s->std,3);
                         $slopepush=array();
                        
                         foreach($icslopedff7 as $slope)
                         {
                            if($std_dff->std == 0)
                            {
                                $slopest = ($slope->ic_sulphate - $avgic_sulphate)/1;
                            }
                            else
                            {
                                $slopest = ($slope->ic_sulphate - $avgic_sulphate)/$std_s->std;
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
                 if(!empty($data1)){
                    $datafirst = DB::table('nt_model_history')
                    ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model_history.id_model', $item->id_model)
                    ->where('nt_model_history.date_start','<=',$data1->date_start)  
                    ->where('nt_model_history.date_end','>=',$data1->date_start)
                    ->where('nt_model_history.id_model_type','=',5)
                    ->first(); 
                    }
                }

                if(!empty($datafirst)){
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
                }
                else
                {
                    $natbd = 1;
                }

                if($natbd == 1)
                {
                    if(!empty($datafirst)){
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
                    else {
                        $sulphathigher--;
                        $sulphatbelow--;
                    }
                }
            }
         

           $sulphathighervs3 = 0;
           $sulphatbelowvs3 = 0;
           if($counticsortdatalab >= 7 )
           {
           $datalablic = $icdatalabvs3[0]->ic_sulphate;
           $iss = 0;
           foreach ( $icdatalabvs3 as $item){ 
               $iss++;
               if( $iss <= 6)
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
         $avgsdff = $icdff->avg('ic_cation_up');
              
         $std_dff = $icdff->select(DB::raw('STDDEV_SAMP(nt_v_data_ic.ic_cation_up) as std'))->first();
        if($std_dff->std ==0)
        {
            $dffck = ($oqadateic->ic_cation_up - $avgsdff) / 1;
        }
        else
        {
            $dffck = ($oqadateic->ic_cation_up - $avgsdff) / $std_dff->std;
        }
         
     
         $dffic_cation = number_format($dffck,3);
          $avgic_cation = $icslope->avg('ic_cation_up');
          
          $countic_cation = $icslope->count('ic_cation_up');
         
          $std_s = $icslope->select(DB::raw('STDDEV_SAMP(nt_v_data_ic.ic_cation_up) as std'))->first();
         
          $stdic_cation = number_format($std_s->std,3);
          $slopepush=array();
         
          foreach($icslopedff7 as $slope)
          {
            if($std_dff->std == 0)
            {
                $slopest = ($slope->ic_cation_up - $avgic_cation)/1;
            }
            else
            {
                $slopest = ($slope->ic_cation_up - $avgic_cation)/$std_s->std;
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
                    if(!empty($data1)){
                        $datafirst = DB::table('nt_model_history')
                        ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model_history.id_model', $item->id_model)
                        ->where('nt_model_history.date_start','<=',$data1->date_start)  
                        ->where('nt_model_history.date_end','>=',$data1->date_start)
                        ->where('nt_model_history.id_model_type','=',5)
                        ->first(); 
                    }
                }

                if(!empty($datafirst)){
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
                }
                else
                {
                    $natbd = 1;
                }

                if($natbd == 1)
                {
                    if(!empty($datafirst)){
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
                    else {
                        $cationhigher--;
                        $cationbelow--;
                    }
                }
            }
        
          
           $cationhighervs3 = 0;
           $cationbelowvs3 = 0;
           if($counticsortdatalab >= 7 )
           {
           $datalablic = $icdatalabvs3[0]->ic_cation_up;
           $iss = 0;
           foreach ( $icdatalabvs3 as $item){ 
               $iss++;
               if( $iss <= 6)
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
             $error = 0;


            if ($icanionhigher >= 9) {
                $error++;
            } else if ($icanionbelow >= 9) {
                $error++;
            } else {
            }
           
            if ($icanionhighervs3 >= 6) {
                $error++;
            } else if ($icanionbelowvs3 >= 6) {
                $error++;
            } else {
            
            }
            
            if(!empty($icdatalab[0]->ic_anion_up) && !empty($datalcl) && !empty($dataucl)){
                if ( $icdatalab[0]->ic_anion_up < $datalcl->anion) {
                    if(is_numeric($datalcl->anion) == true){
                        $error++;
                    } 
                } else if ($icdatalab[0]->ic_anion_up > $dataucl->anion) {
                    if(is_numeric($dataucl->anion) == true){
                        $error++;
                    } 
                } 
            }

            if(!empty($icdatalab[0]->ic_anion_up) && !empty($is)){
                if ($icdatalab[0]->ic_anion_up > $is->anion) {
                    if(is_numeric($is->anion) == true){
                        $error++;
                    } 
                } 
            } 

            
            if ($sulphathigher >= 9) {
                $error++;
            } else if ($sulphatbelow >= 9) {
                $error++;
            } else {
            }
            
            if ($sulphathighervs3 >= 6) {
                $error++;
            } else if ($sulphatbelowvs3 >= 6) {
                $error++;
            } else {
            
            }

            if(!empty($icdatalab[0]->ic_sulphate) && !empty($datalcl) && !empty($dataucl)){
                if ( $icdatalab[0]->ic_sulphate < $datalcl->sulphate) {
                    if(is_numeric($datalcl->sulphate) == true){
                        $error++;
                    } 
                } else if ($icdatalab[0]->ic_sulphate > $dataucl->sulphate) {
                    if(is_numeric($dataucl->sulphate) == true){
                        $error++;
                    } 
                }
            }

            if(!empty($icdatalab[0]->ic_sulphate) && !empty($is)){
                if ($icdatalab[0]->ic_sulphate > $is->sulphate) {
                    if(is_numeric($is->sulphate) == true){
                        $error++;
                    } 
                }
            }
           

            if ($cationhigher >= 9) {
                $error++;
            } else if ($cationbelow >= 9) {
                $error++;
            }
        
            if ($cationhighervs3 >= 6) {
                $error++;
            } else if ($cationbelowvs3 >= 6) {
                $error++;
            }
          
            if(!empty($icdatalab[0]->ic_cation_up) && !empty($datalcl) && !empty($dataucl)){
                if ( $icdatalab[0]->ic_cation_up < $datalcl->cation) {
                    
                    if(is_numeric($datalcl->cation) == true){
                    if($icdatalab[0]->ic_cation_up != null)
                    {
                        $error++;
                    }
                    } 
                } else if ($icdatalab[0]->ic_cation_up > $dataucl->cation) {
                    if(is_numeric($dataucl->cation) == true){
                        $error++;
                        
                    } 
                } 
            } 
            
            if(!empty($icdatalab[0]->ic_cation_up) && !empty($is)){
                if ($icdatalab[0]->ic_cation_up > $is->cation) {
                    if(is_numeric($is->cation) == true){
                        $error++;
                    
                    } 
                }
            }
        

         
            if($slopeic_anion != null)
            {
                if ($slopeic_anion >= 0.5 || $slopeic_anion <= -0.5 || $dffic_anion >= 3.5) {
                    $error++;
                }
            }
            else
            {
                if ($dffic_anion >= 3.5 ) {
                    $error++;
                }
            }

            if($slopeic_sulphate != null)
            {
                if ($slopeic_sulphate >= 0.5 || $slopeic_sulphate <= -0.5 || $dffic_sulphate >= 3.5) {
                    $error++;
                }
            }
            else
            {
                if ($dffic_sulphate >= 3.5 ) {
                    $error++;
                }
            }
          

            if($slopeic_cation != null)
            {
                if ($slopeic_cation >= 0.5 || $slopeic_cation <= -0.5 || $dffic_cation >= 3.5) {
                    $error++;
                }
            }
            else
            {
                if ($dffic_cation >= 3.5 ) {
                    $error++;
                }
            }
          
            $returndata = $error;
        //-------------------------------------end ic-------------------------------------//
        }
        else
        {
            $returndata =0;
        } 
        
        return  $returndata;
      }


}
