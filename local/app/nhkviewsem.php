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

class nhkviewsem
{
    static function sem($mailidcreatelab,$mailidmodel)
    {
        
        $oqa = DB::table('nt_oqa_lab')
        ->where('id_model',$mailidmodel)
        ->get();
        $oqalot = DB::table('nt_oqa_lab')
        ->where('id_createlab',$mailidcreatelab)
        ->first();
        $oqadatesem = DB::table('nt_data_sem')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
        ->where('nt_data_sem.id_createlab',$mailidcreatelab)->where('nt_data_sem.sem_st',1)
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

        //------------------------------------- sem -------------------------------------//
        if(!empty($oqadatesem))
        {
    
  
            $yeardown = date('Y-m-d 00:00:00', strtotime($oqadatesem->save_date. ' - 1 year'));
            $dateend = $oqadatesem->save_date;
            $semdatalab = DB::table('nt_data_sem')
            ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
            ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
            ->where('nt_oqa_lab.save_date','<=',$dateend)
            ->where('nt_oqa_lab.id_model',$mailidmodel)
            ->where('nt_oqa_lab.sem_confirm_st',1)
            ->limit(9)->orderBy('nt_oqa_lab.save_date', 'desc')->get();

            $oqasum_gcms_sum = intval($oqasum->gcms_sum);
            if($oqasum_gcms_sum == 0){
                $oqasum_gcms_sum = 1;
            }
       
            $semdatalaball = DB::table('nt_data_sem')
            ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
            ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
            ->where('nt_oqa_lab.save_date','<=',$dateend)
            ->where('nt_oqa_lab.id_model',$mailidmodel)
            // ->where('nt_oqa_lab.sem_confirm_st',1)->limit(intval($oqasum->sem_sum))->orderBy('nt_oqa_lab.save_date', 'desc')->get();
            ->where('nt_oqa_lab.sem_confirm_st',1)->limit($oqasum_gcms_sum)->orderBy('nt_oqa_lab.save_date', 'desc')->get();
   
            $newsemdatalab=array();
            $datalcl=null;
            $dataucl=null;
            foreach ($semdatalaball as $item){     
               $stdate = date('Y-m-d', strtotime($item->save_date));
               $data = DB::table('nt_model_history')
              ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
              ->where('nt_model_history.id_model', $item->id_model)
              ->where('nt_model_history.date_start','<=',$stdate)
              ->where('nt_model_history.date_end','>=',$stdate)
              ->get(); 
               array_push($newsemdatalab,$data);
            }
            if(!empty( $newsemdatalab[0][0] )){
                $datalcl =  $newsemdatalab[0][2];
                $dataucl =  $newsemdatalab[0][1];
            }
    
            $semdatalabvs3 = DB::table('nt_data_sem')
            ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
            ->where('nt_oqa_lab.save_date','<=',$dateend)
            ->where('nt_oqa_lab.id_model',$mailidmodel)
            ->where('nt_oqa_lab.sem_confirm_st',1)
            ->limit(7)->orderBy('nt_oqa_lab.save_date', 'desc')->get();
            $countsemdatalabvs3 = count($semdatalabvs3);
    
            $semdff = DB::table('nt_v_data_sem')
            ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_sem.id_createlab')
            ->where('nt_oqa_lab.save_date','<',$dateend)
            ->where('nt_oqa_lab.save_date','>=',$yeardown)
            ->where('nt_oqa_lab.sem_confirm_st',1)
            ->where('nt_oqa_lab.id_model',$mailidmodel);

            if($semdff->count() < 10 && $semdff->count() != 0)
            {
                $semdfflimit = DB::table('nt_v_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','<',$dateend)
                ->where('nt_oqa_lab.sem_confirm_st',1)
                ->where('nt_oqa_lab.id_model',$mailidmodel)->where('nt_v_data_sem.sem_st',1)->orderby('nt_oqa_lab.save_date','desc')->limit(10)->get(); 
                $countdfflimit = $semdfflimit->count() -1 ;
                $semdff = DB::table('nt_v_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','<',$dateend)
                ->where('nt_oqa_lab.save_date','>=',$semdfflimit[$countdfflimit]->save_date)
                ->where('nt_oqa_lab.sem_confirm_st',1)
                ->where('nt_oqa_lab.id_model',$mailidmodel)->where('nt_v_data_sem.sem_st',1)->limit(10);
            }
        

            $semslope = DB::table('nt_v_data_sem')
            ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_sem.id_createlab')
            ->where('nt_oqa_lab.save_date','<=',$dateend)
            ->where('nt_oqa_lab.save_date','>=',$yeardown)
            ->where('nt_oqa_lab.sem_confirm_st',1)
            ->where('nt_oqa_lab.id_model',$mailidmodel);
            $semslopedff7 = DB::table('nt_v_data_sem')
            ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_sem.id_createlab')
            ->where('nt_oqa_lab.save_date','<=',$dateend)
            ->where('nt_oqa_lab.save_date','>=',$yeardown)
            ->where('nt_oqa_lab.sem_confirm_st',1)
            ->where('nt_oqa_lab.id_model',$mailidmodel)->OrderBy('nt_oqa_lab.save_date','desc')->limit(7)->get();

           if($semslope->count() < 11 && $semslope->count() != 0)
           {    
                $semslopelimit = DB::table('nt_v_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','<=',$dateend)
                ->where('nt_oqa_lab.sem_confirm_st',1)
                ->where('nt_oqa_lab.id_model',$mailidmodel)->orderby('nt_oqa_lab.save_date','desc')->limit(11)->get(); 
                $countslopelimit = $semslopelimit->count() -1 ;
                $semslope = DB::table('nt_v_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','<=',$dateend)
                ->where('nt_oqa_lab.save_date','>=',$semslopelimit[$countslopelimit]->save_date)
                ->where('nt_oqa_lab.sem_confirm_st',1)
                ->where('nt_oqa_lab.id_model',$mailidmodel);
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
                ->where('nt_oqa_lab.id_model',$mailidmodel)->OrderBy('nt_oqa_lab.save_date','desc')->limit(7)->get();
            }
            // $semsortslopedff7 = $semslopedff7->sortBy('sem_datesave');     
            $countdatalab = count($semdatalab);
            $countsortdatalab = count($semslopedff7);
        //-------------------------------------semclass1-------------------------------------//
    
        //------------------------------- slope dff. semclass1 ----------------------------------//
            $avgsdff = $semdff->avg('sem_class1');
            $std_dff = $semdff->select(DB::raw('STDDEV_SAMP(nt_v_data_sem.sem_class1) as std'))->first();
          
            if($std_dff->std == 0)
            {
                $dffck = null;
            }
            else
            {
                $dffck = ($oqadatesem->sem_class1 - $avgsdff) / $std_dff->std;
            }
          
            $dffsem1 = number_format($dffck,3);
            $avgsem1 = $semslope->avg('sem_class1');
            $countsem1 = $semslope->count('sem_class1');
            $std_s = $semslope->select(DB::raw('STDDEV_SAMP(nt_v_data_sem.sem_class1) as std'))->first();
            $stdsem1 = number_format($std_s->std,3);
            $slopepush=array();

            foreach($semslopedff7 as $slope)
            {
                if($std_dff->std == 0)
                {
                    $slopest = ($slope->sem_class1 - $avgsem1)/1;
                }
                else
                {
                    $slopest = ($slope->sem_class1 - $avgsem1)/$std_s->std;
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
                $slopesem1 = number_format($linearresem,3); 
            }
            //------------------------------- end slope dff. semclass1----------------------------------// 
            $sem1higher = 0;
            $sem1below = 0;
            $natbd = 1;

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
                        if($datafirst->class1 == "N/A")
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
                    }else{
                        $natbd = 1;
                    }
                    
                    if($natbd == 1)
                    {
                        if(!empty($datafirst)){
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
                        }else {
                            $sem1higher--;
                            $sem1below--;
                        }
                    }
                }
               
        
            $sem1highervs3 = 0;
            $sem1belowvs3 = 0;
            $datalabsem1 = $semdatalabvs3[0]->sem_class1;
            $iss = 0;
            if($countsortdatalab >= 7){         
            foreach ( $semdatalabvs3 as $item){ 
                $iss++;
                if( $iss <= 6)
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
              $avgsdff = $semdff->avg('sem_class2');
              $std_dff = $semdff->select(DB::raw('STDDEV_SAMP(nt_v_data_sem.sem_class2) as std'))->first();
              if($std_dff->std == 0)
              {
              $dffck = null;
              }
              else
              {
                $dffck = ($oqadatesem->sem_class2 - $avgsdff) / $std_dff->std;
              }
              $dffsem2 = number_format($dffck,3); 
               $avgsem2 = $semslope->avg('sem_class2');
               $countsem2 = $semslope->count('sem_class2');
               $std_s = $semslope->select(DB::raw('STDDEV_SAMP(nt_v_data_sem.sem_class2) as std'))->first();
               $stdsem2 = number_format($std_s->std,3);
               $slopepush=array();
               foreach($semslopedff7 as $slope)
               {
                if($std_dff->std == 0)
                {
                    $slopest = ($slope->sem_class2 - $avgsem2)/1;
                }
                else
                {
                    $slopest = ($slope->sem_class2 - $avgsem2)/$std_s->std;
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
                    $slopesem2 = number_format($linearresem,3);
                }
               
               //------------------------------- end slope dff. semclass2----------------------------------//
            $sem2higher = 0;
            $sem2below = 0;
            $natbd = 1;

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
                    
                    if(!empty($datafirst)){
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
                }else{
                    $natbd = 1;
                }

                if($natbd == 1)
                {
                    if(!empty($datafirst)){
                        if ($datafirst->class2 < $item->sem_class2) {
                            $sem2higher++;
                            
                        }else if ($datafirst->class2 > $item->sem_class2) {
                            $sem2below++;
                        
                        }else {
                            $sem2higher--;
                            $sem2below--;
                        }
                    }else{
                        $sem2higher--;
                        $sem2below--;
                    }
                }
            }
   
        
           $sem2highervs3 = 0;
           $sem2belowvs3 = 0;
           if($countsortdatalab >= 7){
               $datalabsem2 = $semdatalabvs3[0]->sem_class2;
               $iss = 0;
               foreach ( $semdatalabvs3 as $item){ 
                   $iss++;
                   if( $iss <= 6)
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
                $avgsdff = $semdff->avg('sem_class3');
                $std_dff = $semdff->select(DB::raw('STDDEV_SAMP(nt_v_data_sem.sem_class3) as std'))->first();
                if($std_dff->std == 0)
                {
                    $dffck = null;
                }
                else
                {
                    $dffck = ($oqadatesem->sem_class3 - $avgsdff) / $std_dff->std;
                }
                $dffsem3 = number_format($dffck,3);
                $avgsem3 = $semslope->avg('sem_class3');
                $countsem3 = $semslope->count('sem_class3');
                $std_s = $semslope->select(DB::raw('STDDEV_SAMP(nt_v_data_sem.sem_class3) as std'))->first();
                $stdsem3 = number_format($std_s->std,3);
                $slopepush=array();
            
                foreach($semslopedff7 as $slope)
                {
                    if($std_dff->std == 0)
                    {
                        $slopest = ($slope->sem_class3 - $avgsem3)/1;
                    }
                    else
                    {
                        $slopest = ($slope->sem_class3 - $avgsem3)/$std_s->std;
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
                }else{
                    $natbd = 1;
                }

                if($natbd == 1)
                {
                    if(!empty($datafirst)){
                        if ($datafirst->class3 < $item->sem_class3) {
                            $sem3higher++;
                        } 
                        else if ($datafirst->class3 > $item->sem_class3) {
                            $sem3below++;
                        
                        } else {
                            $sem3higher--;
                            $sem3below--;
                        }
                    }else {
                        $sem3higher--;
                        $sem3below--;
                    }
                }
            }
 
        $sem3highervs3 = 0;
        $sem3belowvs3 = 0;
        if($countsortdatalab >= 7){
           $datalabsem3 = $semdatalabvs3[0]->sem_class3;
           $iss = 0;
           foreach ( $semdatalabvs3 as $item){ 
               $iss++;
               if( $iss <= 6)
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
                $avgsdff = $semdff->avg('sem_class3_1');
                $std_dff = $semdff->select(DB::raw('STDDEV_SAMP(nt_v_data_sem.sem_class3_1) as std'))->first();
    
                if($std_dff->std == 0)
                {
                $dffck = null;
               
                }
                else
                {
                    $dffck = ($oqadatesem->sem_class3_1 - $avgsdff) / $std_dff->std;
                   
                }
                $dffsem3_1 = number_format($dffck,3);
                 $avgsem3_1 = $semslope->avg('sem_class3_1');
                 $countsem3_1 = $semslope->count('sem_class3_1');
                 $std_s = $semslope->select(DB::raw('STDDEV_SAMP(nt_v_data_sem.sem_class3_1) as std'))->first();
                 $stdsem3_1 = number_format($std_s->std,3);
                 $slopepush=array();
                 foreach($semslopedff7 as $slope)
                 {
                    if($std_dff->std == 0)
                    {
                        $slopest = ($slope->sem_class3_1 - $avgsem3_1)/1;
                    }
                    else
                    {
                        $slopest = ($slope->sem_class3_1 - $avgsem3_1)/$std_s->std;
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
                }else
                {
                    $natbd = 1;
                }

                if($natbd == 1)
                {
                    if(!empty($datafirst)){
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
                    }else {
                        $sem31higher--;
                        $sem31below--;
                    }
                }
            }
  

            $sem31highervs3 = 0;
            $sem31belowvs3 = 0;
            if($countsortdatalab >= 7){
                $datalabsem31 = $semdatalabvs3[0]->sem_class3_1;
                $iss = 0;
                foreach ( $semdatalabvs3 as $item){ 
                    $iss++;
                    if( $iss <= 6)
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
                     $avgsdff = $semdff->avg('sem_class3_2');
                     $std_dff = $semdff->select(DB::raw('STDDEV_SAMP(nt_v_data_sem.sem_class3_2) as std'))->first();
                     if($std_dff->std ==0)
                     {
                        $dffck = null;
                     }
                     else
                     {
                        $dffck = ($oqadatesem->sem_class3_2 - $avgsdff) / $std_dff->std;
                     }
                     $dffsem3_2 = number_format($dffck,3);
                      $avgsem3_2 = $semslope->avg('sem_class3_2');
                      $countsem3_2 = $semslope->count('sem_class3_2');
                      $std_s = $semslope->select(DB::raw('STDDEV_SAMP(nt_v_data_sem.sem_class3_2) as std'))->first();
                      $stdsem3_2 = number_format($std_s->std,3);
                      $slopepush=array();
                  
                      foreach($semslopedff7 as $slope)
                      {
                        if($std_dff->std == 0)
                        {
                            $slopest = ($slope->sem_class3_2 - $avgsem3_2)/1;
                        }
                        else
                        {
                            $slopest = ($slope->sem_class3_2 - $avgsem3_2)/$std_s->std;
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
                }else{
                    $natbd = 1;
                }

                if($natbd == 1)
                {
                    if(!empty($datafirst)){
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
                    else {
                        $sem32higher--;
                        $sem32below--;
                    }
                }
            }
     
            $sem32highervs3 = 0;
            $sem32belowvs3 = 0;
            if($countsortdatalab >= 7){
              $datalabsem32 = $semdatalabvs3[0]->sem_class3_2;
              $iss = 0;
              foreach ( $semdatalabvs3 as $item){ 
                  $iss++;
                  if( $iss <= 6)
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
                  $avgsdff = $semdff->avg('sem_class3_3');
                  $std_dff = $semdff->select(DB::raw('STDDEV_SAMP(nt_v_data_sem.sem_class3_3) as std'))->first();
                  if($std_dff->std ==0)
                  {
                  $dffck = null;
                  }
                  else
                  {
                    $dffck = ($oqadatesem->sem_class3_3 - $avgsdff) / $std_dff->std;
                  }
    
                  $dffsem3_3 = number_format($dffck,3);
                   $avgsem3_3 = $semslope->avg('sem_class3_3');
                   $countsem3_3 = $semslope->count('sem_class3_3');
                   $std_s = $semslope->select(DB::raw('STDDEV_SAMP(nt_v_data_sem.sem_class3_3) as std'))->first();
                   $stdsem3_3 = number_format($std_s->std,3);
                   $slopepush=array();
               
                   foreach($semslopedff7 as $slope)
                   {
                    if($std_dff->std == 0)
                    {
                        $slopest = ($slope->sem_class3_3 - $avgsem3_3)/1;
                    }
                    else
                    {
                        $slopest = ($slope->sem_class3_3 - $avgsem3_3)/$std_s->std;
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
                }else
                {
                    $natbd = 1;
                }

                if($natbd == 1)
                {
                    if(!empty($datafirst)){
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
                    }else{
                        $sem33higher--;
                        $sem33below--;
                    }
                }
            }
       
            $sem33highervs3 = 0;
            $sem33belowvs3 = 0;
            if($countsortdatalab >= 7){
                $datalabsem33 = $semdatalabvs3[0]->sem_class3_3;
                $iss = 0;
                foreach ( $semdatalabvs3 as $item){ 
                      $iss++;
                      if( $iss <= 6)
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
                 $avgsdff = $semdff->avg('sem_class4');
                 $std_dff = $semdff->select(DB::raw('STDDEV_SAMP(nt_v_data_sem.sem_class4) as std'))->first();
                 if($std_dff->std ==0)
                 {
                 $dffck = null;
                 }
                 else
                 {
                    $dffck = ($oqadatesem->sem_class4 - $avgsdff) / $std_dff->std;
                 }
                 $dffsem4 = number_format($dffck,3);
                  $avgsem4 = $semslope->avg('sem_class4');
                  $countsem4 = $semslope->count('sem_class4');
                  $std_s = $semslope->select(DB::raw('STDDEV_SAMP(nt_v_data_sem.sem_class4) as std'))->first();
                  $stdsem4 = number_format($std_s->std,3);
                  $slopepush=array();
              
                  foreach($semslopedff7 as $slope)
                  {
                    if($std_dff->std == 0)
                    {
                        $slopest = ($slope->sem_class4 - $avgsem4)/1;
                    }
                    else
                    {
                        $slopest = ($slope->sem_class4 - $avgsem4)/$std_s->std;
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
                    }
                    else
                    {
                        $natbd = 1;
                    }

                    if($natbd == 1)
                    {
                        if(!empty($datafirst)){
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
                        }else {
                            $sem4higher--;
                            $sem4below--;
                        }
                    }
                }
        

            $sem4highervs3 = 0;
            $sem4belowvs3 = 0;
            if($countsortdatalab >= 7){
            $datalabsem4 = $semdatalabvs3[0]->sem_class4;
            $iss = 0;
            foreach ( $semdatalabvs3 as $item){ 
                $iss++;
                if( $iss <= 6)
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
                     $avgsdff = $semdff->avg('sem_class5');
                     $std_dff = $semdff->select(DB::raw('STDDEV_SAMP(nt_v_data_sem.sem_class5) as std'))->first();
                     if($std_dff->std == 0)
                     {
                     $dffck = null;
                     }
                     else
                     {
                        $dffck = ($oqadatesem->sem_class5 - $avgsdff) / $std_dff->std;
                     }
                     $dffsem5 = number_format($dffck,3);
                      $avgsem5 = $semslope->avg('sem_class5');
                      $countsem5 = $semslope->count('sem_class5');
                      $std_s = $semslope->select(DB::raw('STDDEV_SAMP(nt_v_data_sem.sem_class5) as std'))->first();
                      $stdsem5 = number_format($std_s->std,3);
                      $slopepush=array();
                  
                      foreach($semslopedff7 as $slope)
                      {
                        if($std_dff->std == 0)
                        {
                            $slopest = ($slope->sem_class5 - $avgsem5)/1;
                        }
                        else
                        {
                            $slopest = ($slope->sem_class5 - $avgsem5)/$std_s->std;
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
                    }else
                    {
                        $natbd = 1;
                    }

                    if($natbd == 1)
                    {
                        if(!empty($datafirst)){
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
                        else {
                            $sem5higher--;
                            $sem5below--;
                        }
                    }
                }
        

              $sem5highervs3 = 0;
              $sem5belowvs3 = 0;
              if($countsortdatalab >= 7){
              $datalabsem5 = $semdatalabvs3[0]->sem_class5;
              $iss = 0;
              foreach ( $semdatalabvs3 as $item){ 
                   $iss++;
                   //$semdatalabvs3[2]->sem_class5;
                  if( $iss <= 6)
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
                                  $avgsdff = $semdff->avg('sem_class5_1');
                                  $std_dff = $semdff->select(DB::raw('STDDEV_SAMP(nt_v_data_sem.sem_class5_1) as std'))->first();
                                  if($std_dff->std == 0)
                                  {
                                  $dffck = null;
                                  }
                                  else
                                  {
                                    $dffck = ($oqadatesem->sem_class5_1 - $avgsdff) / $std_dff->std;
                                  }
                                  $dffsem5_1 = number_format($dffck,3);
                                   $avgsem5_1 = $semslope->avg('sem_class5_1');
                                   $countsem5_1 = $semslope->count('sem_class5_1');
                                   $std_s = $semslope->select(DB::raw('STDDEV_SAMP(nt_v_data_sem.sem_class5_1) as std'))->first();
                                   $stdsem5_1 = number_format($std_s->std,3);
                                   $slopepush=array();
                               
                                   foreach($semslopedff7 as $slope)
                                   {
                                    if($std_dff->std == 0)
                                    {
                                        $slopest = ($slope->sem_class5_1 - $avgsem5_1)/1;
                                    }
                                    else
                                    {
                                        $slopest = ($slope->sem_class5_1 - $avgsem5_1)/$std_s->std;
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
                    }
                    else
                    {
                        $natbd = 1;
                    }

                    if($natbd == 1)
                    {
                        if(!empty($datafirst)){
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
                        }else {
                            $sem51higher--;
                            $sem51below--;
                        }
                    }
                }
       

            $sem51highervs3 = 0;
            $sem51belowvs3 = 0;
            if($countsortdatalab >= 7){
            $datalabsem51 = $semdatalabvs3[0]->sem_class5_1;
            $iss = 0;
            foreach ( $semdatalabvs3 as $item){ 
                $iss++;
                if( $iss <= 6)
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
                 $avgsdff = $semdff->avg('sem_class6');
                 $std_dff = $semdff->select(DB::raw('STDDEV_SAMP(nt_v_data_sem.sem_class6) as std'))->first();
                 if($std_dff->std == 0)
                 {
                 $dffck = null;
                 }
                 else
                 {
                    $dffck = ($oqadatesem->sem_class6 - $avgsdff) / $std_dff->std;
                 }
                 $dffsem6 = number_format($dffck,3);
                  $avgsem6 = $semslope->avg('sem_class6');
                  $countsem6 = $semslope->count('sem_class6');
                  $std_s = $semslope->select(DB::raw('STDDEV_SAMP(nt_v_data_sem.sem_class6) as std'))->first();
                  $stdsem6 = number_format($std_s->std,3);
                  $slopepush=array();
              
                  foreach($semslopedff7 as $slope)
                  {
                    if($std_dff->std== 0)
                    {
                        $slopest = ($slope->sem_class6 - $avgsem6)/1;
                    }
                    else
                    {
                        $slopest = ($slope->sem_class6 - $avgsem6)/$std_s->std;
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
                            
                            if(!empty($data1) && $item->id_model != null){
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
                        }
                        else
                        {
                            $natbd = 1;
                        }

                        if($natbd == 1)
                        {
                        // if ($datafirst->class6 < $item->sem_class6) {
                        //     $sem6higher++;
                            
                        // } 
                        // else if ($datafirst->class6 > $item->sem_class6) {
                        //     $sem6below++;
                           
                        // } 
                        // else {
                        //     $sem6higher--;
                        //     $sem6below--;
                        // }
                        // edit 20/09/23
                        if($item->last_sem_class6  != null){
                            if(!empty($datafirst)){
                                if ($datafirst->class6 < $item->last_sem_class6) {
                                    $sem6higher++;
                                } 
                                else if ($datafirst->class6 > $item->last_sem_class6) {
                                    $sem6below++;
                                        
                                } 
                                else {
                                    $sem6higher--;
                                    $sem6below--;
                                }
                            }else{
                                $sem6higher--;
                                $sem6below--;
                            }
                        }else{
                            if(!empty($datafirst)){
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
                            }else{
                                $sem6higher--;
                                $sem6below--;
                            }
                        }   
                    }
                }
               
                $sem6highervs3 = 0;
                $sem6belowvs3 = 0;
                if($countsortdatalab >= 7){
                    $datalabsem6 = $semdatalabvs3[0]->sem_class6;
                    $iss = 0;
                    foreach ( $semdatalabvs3 as $item){ 
                        $iss++;
                        if( $iss <= 6)
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
                $error = 0;

                if ($sem1higher >= 9) {
                    $error++;
                } else if ($sem1below >= 9) {
                    $error++;
                } else {
                }
                if ($sem1highervs3 >= 6) {
                    $error++;
                } else if ($sem1belowvs3 >= 6) {
                    $error++;
                } else {
                }

                if(!empty($semdatalab[0]->sem_class1) && !empty($datalcl->class1)){
                    if ($semdatalab[0]->sem_class1 < $datalcl->class1) {
                        if(is_numeric($datalcl->class1) == true){
                        
                        $error++;
                        }
                    } else if ($semdatalab[0]->sem_class1 > $dataucl->class1) {
    
                        if(is_numeric($dataucl->class1) == true){
                        
                        $error++;
                        }
                    } else {

                    }
                }

                if(isset($semdatalab[0]->sem_class1)){
                    if ($semdatalab[0]->sem_class1 > $is->class1) {

                        if(is_numeric($is->class1) == true){
                            $error++;
                        }  
                    } else {
                    }
                }

                if ($sem2higher >= 9) {
                    $error++;
                   } else if ($sem2below >= 9) {
                    $error++;
                    } else {
                    }
                if ($sem2highervs3 >= 6) {
                    $error++;
                } else if ($sem2belowvs3 >= 6) {
                    $error++;
                } else {
                }
                if(!empty($semdatalab[0]->sem_class2) && !empty($datalcl->class2)){
                    if ($semdatalab[0]->sem_class2 < $datalcl->class2) {
                        if(is_numeric($datalcl->class2) == true){
                        $error++;
                        }
                    } else if ($semdatalab[0]->sem_class2 > $dataucl->class2) {
    
                        if(is_numeric($dataucl->class2) == true){
                        $error++;
                        }
                    } else {

                    }
                }

                if(!empty($semdatalab[0]->sem_class2)){
                    if ($semdatalab[0]->sem_class2 > $is->class2) {

                        if(is_numeric($is->class2) == true){
                            $error++;
                        }  
                    } else {
                    }
                }

                if ($sem3higher >= 9) {
                    $error++;
                   } else if ($sem3below >= 9) {
                    $error++;
                    } else {
                    }
                if ($sem3highervs3 >= 6) {
                    $error++;
                } else if ($sem3belowvs3 >= 6) {
                    $error++;
                } else {
                }

                if(!empty($semdatalab[0]->sem_class3) && !empty($datalcl->sem_class3)){
                    if ($semdatalab[0]->sem_class3 < $datalcl->class3) {
                        if(is_numeric($datalcl->class3) == true){
                        $error++;
                        }
                    } else if ($semdatalab[0]->sem_class3 > $dataucl->class3) {
    
                        if(is_numeric($dataucl->class3) == true){
                        $error++;
                        }
                    } else {

                    }
                }
                if(!empty($semdatalab[0]->sem_class3)){
                    if ($semdatalab[0]->sem_class3 > $is->class3) {

                        if(is_numeric($is->class3) == true){
                            $error++;
                        }  
                    } else {
                    }
                }

                if ($sem31higher >= 9) {
                    $error++;
                   } else if ($sem31below >= 9) {
                    $error++;
                    } else {
                    }
                if ($sem31highervs3 >= 6) {
                    $error++;
                } else if ($sem31belowvs3 >= 6) {
                    $error++;
                } else {
                }

                if(!empty($semdatalab[0]->sem_class3_1) && !empty($datalcl->class3_1)){
                    if ($semdatalab[0]->sem_class3_1 < $datalcl->class3_1) {
                        if(is_numeric($datalcl->class3_1) == true){
                        $error++;
                        }
                    } else if ($semdatalab[0]->sem_class3_1 > $dataucl->class3_1) {
    
                        if(is_numeric($dataucl->class3_1) == true){
                        $error++;
                        }
                    } else {

                    }
                }
                if(!empty($semdatalab[0]->sem_class3_1)){
                    if ($semdatalab[0]->sem_class3_1 > $is->class3_1) { 
                        if(is_numeric($is->class3_1) == true){
                            $error++;
                        }  
                    } else {
                    }
                }

                if ($sem32higher >= 9) {
                    $error++;
                   } else if ($sem32below >= 9) {
                    $error++;
                    } else {
                    }
                if ($sem32highervs3 >= 6) {
                    $error++;
                } else if ($sem32belowvs3 >= 6) {
                    $error++;
                } else {
                }
                if(!empty($semdatalab[0]->sem_class3_2) && !empty($datalcl->class3_2)){
                    if ($semdatalab[0]->sem_class3_2 < $datalcl->class3_2) {
                        if(is_numeric($datalcl->class3_2) == true){
                        $error++;
                        }
                    } else if ($semdatalab[0]->sem_class3_2 > $dataucl->class3_2) {
    
                        if(is_numeric($dataucl->class3_2) == true){
                        $error++;
                        }
                    } else {

                    }
                }
                if(!empty($semdatalab[0]->sem_class3_2)){
                    if ($semdatalab[0]->sem_class3_2 > $is->class3_2) {

                        if(is_numeric($is->class3_2) == true){
                            $error++;
                        }  
                    } else {
                    }
                }

                if ($sem33higher >= 9) {
                    $error++;
                   } else if ($sem33below >= 9) {
                    $error++;
                    } else {
                    }
                if ($sem33highervs3 >= 6) {
                    $error++;
                } else if ($sem33belowvs3 >= 6) {
                    $error++;
                } else {
                }

                if(!empty($semdatalab[0]->sem_class3_3) && !empty($datalcl->class3_3)){
                    if ($semdatalab[0]->sem_class3_3 < $datalcl->class3_3) {
                        if(is_numeric($datalcl->class3_3) == true){
                        $error++;
                        }
                    } else if ($semdatalab[0]->sem_class3_3 > $dataucl->class3_3) {
    
                        if(is_numeric($dataucl->class3_3) == true){
                        $error++;
                        }
                    } else {

                    }
                }
                if(!empty($semdatalab[0]->sem_class3_3)){
                    if ($semdatalab[0]->sem_class3_3 > $is->class3_3) { 
                        if(is_numeric($is->class3_3) == true){
                            $error++;
                        }  
                    } else {
                    }
                }

                if ($sem4higher >= 9) {
                    $error++;
                   } else if ($sem4below >= 9) {
                    $error++;
                    } else {
                    }
                if ($sem4highervs3 >= 6) {
                    $error++;
                } else if ($sem4belowvs3 >= 6) {
                    $error++;
                } else {
                }
                if(!empty($semdatalab[0]->sem_class4) && !empty($datalcl->class4)){
                    if ($semdatalab[0]->sem_class4 < $datalcl->class4) {
                        if(is_numeric($datalcl->class4) == true){
                        
                        $error++;
                        }
                    } else if ($semdatalab[0]->sem_class4 > $dataucl->class4) {
    
                        if(is_numeric($dataucl->class4) == true){
                        $error++;
                        }
                    } else {

                    }
                }
                if(!empty($semdatalab[0]->sem_class4)){
                    if ($semdatalab[0]->sem_class4 > $is->class4) {

                        if(is_numeric($is->class4) == true){
                            $error++;
                        }  
                    } else {
                    }
                }

                if ($sem5higher >= 9) {
                    $error++;
                   } else if ($sem5below >= 9) {
                    $error++;
                    } else {
                    }
                if ($sem5highervs3 >= 6) {
                    $error++;
                } else if ($sem5belowvs3 >= 6) {
                    $error++;
                } else {
                }
                if(!empty($semdatalab[0]->sem_class5) && !empty($datalcl->class5)){
                    if ($semdatalab[0]->sem_class5 < $datalcl->class5) {
                        if(is_numeric($datalcl->class5) == true){
                        $error++;
                        }
                    } else if ($semdatalab[0]->sem_class5 > $dataucl->class5) {
    
                        if(is_numeric($dataucl->class5) == true){
                        $error++;
                        }
                    } else {

                    }
                }

                if(!empty($semdatalab[0]->sem_class5)){
                    if ($semdatalab[0]->sem_class5 > $is->class5) {

                        if(is_numeric($is->class5) == true){
                            $error++;
                        }  
                    } else {
                    }
                }

                if ($sem51higher >= 9) {
                    $error++;
                   } else if ($sem51below >= 9) {
                    $error++;
                    } else {
                    }
                if ($sem51highervs3 >= 6) {
                    $error++;
                } else if ($sem51belowvs3 >= 6) {
                    $error++;
                } else {
                }

                if(!empty($semdatalab[0]->sem_class5_1) && !empty($datalcl->class5_1)){
                    if ($semdatalab[0]->sem_class5_1 < $datalcl->class5_1) {
                        if(is_numeric($datalcl->class5_1) == true){
                        $error++;
                        }
                    } else if ($semdatalab[0]->sem_class5_1 > $dataucl->class5_1) {
    
                        if(is_numeric($dataucl->class5_1) == true){
                        $error++;
                        }
                    } else {

                    }
                }

                if(!empty($semdatalab[0]->sem_class5_1)){
                    if ($semdatalab[0]->sem_class5_1 > $is->class5_1) {

                        if(is_numeric($is->class5_1) == true){
                            $error++;
                        }  
                    } else {
                    }
                }


                if ($sem6higher >= 9) {
                    $error++;
                   } else if ($sem6below >= 9) {
                    $error++;
                    } else {
                    }
                if ($sem6highervs3 >= 6) {
                    $error++;
                } else if ($sem6belowvs3 >= 6) {
                    $error++;
                } else {
                }

                if(!empty($semdatalab[0]->sem_class6) && !empty($datalcl->class6)){
                    if ($semdatalab[0]->sem_class6 < $datalcl->class6) {
                        if(is_numeric($datalcl->class6) == true){
                        $error++;
                        }
                    } else if ($semdatalab[0]->sem_class6 > $dataucl->class6) {
    
                        if(is_numeric($dataucl->class6) == true){
                        $error++;
                        }
                    } else {

                    }
                }
                if(!empty($semdatalab[0]->sem_class6)){
                    if ($semdatalab[0]->sem_class6 > $is->class6) {

                        if(is_numeric($is->class6) == true){
                            $error++;
                        }  
                    } else {
                    }
                }
                if($slopesem1 != null)
                {
                    if ($slopesem1 >= 0.5 || $slopesem1 <= -0.5 || $dffsem1 >= 3.5 ) {
                        $error++;
                    }
                }
                
                else
                {
                    
                    if ($dffsem1 >= 3.5 &&  $dffsem1 != null) {
                        $error++;
                    }
                }
               
                if($slopesem2 != null)
                {
                    if ($slopesem2 >= 0.5 || $slopesem2 <= -0.5 || $dffsem2 >= 3.5 ) {
                        $error++;
                        
                    }
                }
                else
                {
                    if ($dffsem2 >= 3.5  &&  $dffsem2 != null) {
                        $error++;
                    }
                }


                if($slopesem3 != null)
                {
                    if ($slopesem3 >= 0.5 || $slopesem3 <= -0.5 || $dffsem3 >= 3.5 ) {
                        $error++;
                    }
                }
                else
                {
                    if ($dffsem3 >= 3.5 &&  $dffsem3 != null) {
                        $error++;
                    }
                }
                
               
                if($slopesem3_1 != null)
                {
                    if ($slopesem3_1 >= 0.5 || $slopesem3_1 <= -0.5 || $dffsem3_1 >= 3.5 ) {
                        $error++;
                    }
                }
                else
                {
                    if ($dffsem3_1 >= 3.5 &&  $dffsem3_1 != null) {
                        $error++;
                    }
                }
        
               
                if($slopesem3_2 != null)
                {
                    if ($slopesem3_2 >= 0.5 || $slopesem3_2 <= -0.5 || $dffsem3_2 >= 3.5 ) {
                        $error++;
                    }
                }
                else
                {
                    if ($dffsem3_2 >= 3.5  &&  $dffsem3_2 != null) {
                        $error++;
                    }
                }
                
                if($slopesem3_3 != null)
                {
                    if ($slopesem3_3 >= 0.5 || $slopesem3_3 <= -0.5 || $dffsem3_3 >= 3.5 ) {
                        $error++;
                    }
                }
                else
                {
                    if ($dffsem3_3 >= 3.5 &&  $dffsem3_3 != null) {
                        $error++;
                    }
                }
                
                if($slopesem4 != null)
                {
                    if ($slopesem4 >= 0.5 || $slopesem4 <= -0.5 || $dffsem4 >= 3.5 ) {
                        $error++;
                    }
                }
                else
                {
                    if ($dffsem4 >= 3.5 &&  $dffsem4 != null) {
                        $error++;
                    }
                }
               

                if($slopesem5 != null)
                {
                    if ($slopesem5 >= 0.5 || $slopesem5 <= -0.5 || $dffsem5 >= 3.5 ) {
                        $error++;
                    }
                }
                else
                {
                    if ($dffsem5 >= 3.5 &&  $dffsem5 != null) {
                        $error++;
                    }
                }
               
                if($slopesem5_1 != null)
                {
                    if ($slopesem5_1 >= 0.5 || $slopesem5_1 <= -0.5 || $dffsem5_1 >= 3.5 ) {
                        $error++;
                    }
                }
                else
                {
                    if ($dffsem5_1 >= 3.5 &&  $dffsem5_1 != null) {
                        $error++;
                    }
                }

                if($slopesem6 != null)
                {
                    if ($slopesem6 >= 0.5 || $slopesem6 <= -0.5 || $dffsem6 >= 3.5 ) {
                        $error++;
                    }
                }
                else
                {
                    if ($dffsem6 >= 3.5 &&  $dffsem6 != null) {
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

    static function semgetresult($mailidcreatelab,$mailidmodel)
    {
            ini_set('memory_limit', '-1');
            set_time_limit(1000); 


            $result_arr = [];
            $result_value_arr = [];

            $oqa = DB::table('nt_oqa_lab')
            ->where('id_model',$mailidmodel)
            ->get();
            $oqalot = DB::table('nt_oqa_lab')
            ->where('id_createlab',$mailidcreatelab)
            ->first();
            $oqadatesem = DB::table('nt_data_sem')
            ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
            ->where('nt_data_sem.id_createlab',$mailidcreatelab)->where('nt_data_sem.sem_st',1)
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

            //------------------------------------- sem -------------------------------------//
            if(!empty($oqadatesem))
            {
        
    
                $yeardown = date('Y-m-d 00:00:00', strtotime($oqadatesem->save_date. ' - 1 year'));
                $dateend = $oqadatesem->save_date;
                $semdatalab = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                ->where('nt_oqa_lab.save_date','<=',$dateend)
                ->where('nt_oqa_lab.id_model',$mailidmodel)
                ->where('nt_oqa_lab.sem_confirm_st',1)
                ->limit(9)->orderBy('nt_oqa_lab.save_date', 'desc')->get();

                $oqasum_gcms_sum = intval($oqasum->gcms_sum);
                if($oqasum_gcms_sum == 0){
                    $oqasum_gcms_sum = 1;
                }
        
                $semdatalaball = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                ->where('nt_oqa_lab.save_date','<=',$dateend)
                ->where('nt_oqa_lab.id_model',$mailidmodel)
                // ->where('nt_oqa_lab.sem_confirm_st',1)->limit(intval($oqasum->sem_sum))->orderBy('nt_oqa_lab.save_date', 'desc')->get();
                ->where('nt_oqa_lab.sem_confirm_st',1)->limit($oqasum_gcms_sum)->orderBy('nt_oqa_lab.save_date', 'desc')->get();
    
                $newsemdatalab=array();
                $datalcl=null;
                $dataucl=null;
                foreach ($semdatalaball as $item){     
                $stdate = date('Y-m-d', strtotime($item->save_date));
                $data = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$stdate)
                ->where('nt_model_history.date_end','>=',$stdate)
                ->get(); 
                array_push($newsemdatalab,$data);
                }
                if(!empty( $newsemdatalab[0][0] )){
                    $datalcl =  $newsemdatalab[0][2];
                    $dataucl =  $newsemdatalab[0][1];
                }
        
                $semdatalabvs3 = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','<=',$dateend)
                ->where('nt_oqa_lab.id_model',$mailidmodel)
                ->where('nt_oqa_lab.sem_confirm_st',1)
                ->limit(7)->orderBy('nt_oqa_lab.save_date', 'desc')->get();
                $countsemdatalabvs3 = count($semdatalabvs3);
        
                $semdff = DB::table('nt_v_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','<',$dateend)
                ->where('nt_oqa_lab.save_date','>=',$yeardown)
                ->where('nt_oqa_lab.sem_confirm_st',1)
                ->where('nt_oqa_lab.id_model',$mailidmodel);

                if($semdff->count() < 10 && $semdff->count() != 0)
                {
                    $semdfflimit = DB::table('nt_v_data_sem')
                    ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_sem.id_createlab')
                    ->where('nt_oqa_lab.save_date','<',$dateend)
                    ->where('nt_oqa_lab.sem_confirm_st',1)
                    ->where('nt_oqa_lab.id_model',$mailidmodel)->where('nt_v_data_sem.sem_st',1)->orderby('nt_oqa_lab.save_date','desc')->limit(10)->get(); 
                    $countdfflimit = $semdfflimit->count() -1 ;
                    $semdff = DB::table('nt_v_data_sem')
                    ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_sem.id_createlab')
                    ->where('nt_oqa_lab.save_date','<',$dateend)
                    ->where('nt_oqa_lab.save_date','>=',$semdfflimit[$countdfflimit]->save_date)
                    ->where('nt_oqa_lab.sem_confirm_st',1)
                    ->where('nt_oqa_lab.id_model',$mailidmodel)->where('nt_v_data_sem.sem_st',1)->limit(10);
                }
            

                $semslope = DB::table('nt_v_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','<=',$dateend)
                ->where('nt_oqa_lab.save_date','>=',$yeardown)
                ->where('nt_oqa_lab.sem_confirm_st',1)
                ->where('nt_oqa_lab.id_model',$mailidmodel);
                $semslopedff7 = DB::table('nt_v_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','<=',$dateend)
                ->where('nt_oqa_lab.save_date','>=',$yeardown)
                ->where('nt_oqa_lab.sem_confirm_st',1)
                ->where('nt_oqa_lab.id_model',$mailidmodel)->OrderBy('nt_oqa_lab.save_date','desc')->limit(7)->get();

                if($semslope->count() < 11 && $semslope->count() != 0)
                {    
                    $semslopelimit = DB::table('nt_v_data_sem')
                    ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_sem.id_createlab')
                    ->where('nt_oqa_lab.save_date','<=',$dateend)
                    ->where('nt_oqa_lab.sem_confirm_st',1)
                    ->where('nt_oqa_lab.id_model',$mailidmodel)->orderby('nt_oqa_lab.save_date','desc')->limit(11)->get(); 
                    $countslopelimit = $semslopelimit->count() -1 ;
                    $semslope = DB::table('nt_v_data_sem')
                    ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_sem.id_createlab')
                    ->where('nt_oqa_lab.save_date','<=',$dateend)
                    ->where('nt_oqa_lab.save_date','>=',$semslopelimit[$countslopelimit]->save_date)
                    ->where('nt_oqa_lab.sem_confirm_st',1)
                    ->where('nt_oqa_lab.id_model',$mailidmodel);
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
                    ->where('nt_oqa_lab.id_model',$mailidmodel)->OrderBy('nt_oqa_lab.save_date','desc')->limit(7)->get();
                }
                // $semsortslopedff7 = $semslopedff7->sortBy('sem_datesave');     
                $countdatalab = count($semdatalab);
                $countsortdatalab = count($semslopedff7);
            //-------------------------------------semclass1-------------------------------------//
        
            //------------------------------- slope dff. semclass1 ----------------------------------//
                $avgsdff = $semdff->avg('sem_class1');
                $std_dff = $semdff->select(DB::raw('STDDEV_SAMP(nt_v_data_sem.sem_class1) as std'))->first();
            
                if($std_dff->std == 0)
                {
                    $dffck = null;
                }
                else
                {
                    $dffck = ($oqadatesem->sem_class1 - $avgsdff) / $std_dff->std;
                }
            
                $dffsem1 = number_format($dffck,3);
                $avgsem1 = $semslope->avg('sem_class1');
                $countsem1 = $semslope->count('sem_class1');
                $std_s = $semslope->select(DB::raw('STDDEV_SAMP(nt_v_data_sem.sem_class1) as std'))->first();
                $stdsem1 = number_format($std_s->std,3);
                $slopepush=array();

                foreach($semslopedff7 as $slope)
                {
                    if($std_dff->std == 0)
                    {
                        $slopest = ($slope->sem_class1 - $avgsem1)/1;
                    }
                    else
                    {
                        $slopest = ($slope->sem_class1 - $avgsem1)/$std_s->std;
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
                    $slopesem1 = number_format($linearresem,3); 
                }
                //------------------------------- end slope dff. semclass1----------------------------------// 
                $sem1higher = 0;
                $sem1below = 0;
                $natbd = 1;

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
                        if($datafirst->class1 == "N/A")
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
                    }else{
                        $natbd = 1;
                    }
                    
                    if($natbd == 1)
                    {
                        if(!empty($datafirst)){
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
                        }else {
                            $sem1higher--;
                            $sem1below--;
                        }
                    }
                }
               
        
            $sem1highervs3 = 0;
            $sem1belowvs3 = 0;
            $datalabsem1 = $semdatalabvs3[0]->sem_class1;
            $iss = 0;
            if($countsortdatalab >= 7){         
            foreach ( $semdatalabvs3 as $item){ 
                $iss++;
                if( $iss <= 6)
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
              $avgsdff = $semdff->avg('sem_class2');
              $std_dff = $semdff->select(DB::raw('STDDEV_SAMP(nt_v_data_sem.sem_class2) as std'))->first();
              if($std_dff->std == 0)
              {
              $dffck = null;
              }
              else
              {
                $dffck = ($oqadatesem->sem_class2 - $avgsdff) / $std_dff->std;
              }
              $dffsem2 = number_format($dffck,3); 
               $avgsem2 = $semslope->avg('sem_class2');
               $countsem2 = $semslope->count('sem_class2');
               $std_s = $semslope->select(DB::raw('STDDEV_SAMP(nt_v_data_sem.sem_class2) as std'))->first();
               $stdsem2 = number_format($std_s->std,3);
               $slopepush=array();
               foreach($semslopedff7 as $slope)
               {
                if($std_dff->std == 0)
                {
                    $slopest = ($slope->sem_class2 - $avgsem2)/1;
                }
                else
                {
                    $slopest = ($slope->sem_class2 - $avgsem2)/$std_s->std;
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
                    $slopesem2 = number_format($linearresem,3);
                }
               
               //------------------------------- end slope dff. semclass2----------------------------------//
            $sem2higher = 0;
            $sem2below = 0;
            $natbd = 1;

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
                    
                    if(!empty($datafirst)){
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
                }else{
                    $natbd = 1;
                }

                if($natbd == 1)
                {
                    if(!empty($datafirst)){
                        if ($datafirst->class2 < $item->sem_class2) {
                            $sem2higher++;
                            
                        }else if ($datafirst->class2 > $item->sem_class2) {
                            $sem2below++;
                        
                        }else {
                            $sem2higher--;
                            $sem2below--;
                        }
                    }else{
                        $sem2higher--;
                        $sem2below--;
                    }
                }
            }
   
        
           $sem2highervs3 = 0;
           $sem2belowvs3 = 0;
           if($countsortdatalab >= 7){
               $datalabsem2 = $semdatalabvs3[0]->sem_class2;
               $iss = 0;
               foreach ( $semdatalabvs3 as $item){ 
                   $iss++;
                   if( $iss <= 6)
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
                $avgsdff = $semdff->avg('sem_class3');
                $std_dff = $semdff->select(DB::raw('STDDEV_SAMP(nt_v_data_sem.sem_class3) as std'))->first();
                if($std_dff->std == 0)
                {
                    $dffck = null;
                }
                else
                {
                    $dffck = ($oqadatesem->sem_class3 - $avgsdff) / $std_dff->std;
                }
                $dffsem3 = number_format($dffck,3);
                $avgsem3 = $semslope->avg('sem_class3');
                $countsem3 = $semslope->count('sem_class3');
                $std_s = $semslope->select(DB::raw('STDDEV_SAMP(nt_v_data_sem.sem_class3) as std'))->first();
                $stdsem3 = number_format($std_s->std,3);
                $slopepush=array();
            
                foreach($semslopedff7 as $slope)
                {
                    if($std_dff->std == 0)
                    {
                        $slopest = ($slope->sem_class3 - $avgsem3)/1;
                    }
                    else
                    {
                        $slopest = ($slope->sem_class3 - $avgsem3)/$std_s->std;
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
                }else{
                    $natbd = 1;
                }

                if($natbd == 1)
                {
                    if(!empty($datafirst)){
                        if ($datafirst->class3 < $item->sem_class3) {
                            $sem3higher++;
                        } 
                        else if ($datafirst->class3 > $item->sem_class3) {
                            $sem3below++;
                        
                        } else {
                            $sem3higher--;
                            $sem3below--;
                        }
                    }else {
                        $sem3higher--;
                        $sem3below--;
                    }
                }
            }
 
        $sem3highervs3 = 0;
        $sem3belowvs3 = 0;
        if($countsortdatalab >= 7){
           $datalabsem3 = $semdatalabvs3[0]->sem_class3;
           $iss = 0;
           foreach ( $semdatalabvs3 as $item){ 
               $iss++;
               if( $iss <= 6)
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
                $avgsdff = $semdff->avg('sem_class3_1');
                $std_dff = $semdff->select(DB::raw('STDDEV_SAMP(nt_v_data_sem.sem_class3_1) as std'))->first();
    
                if($std_dff->std == 0)
                {
                $dffck = null;
               
                }
                else
                {
                    $dffck = ($oqadatesem->sem_class3_1 - $avgsdff) / $std_dff->std;
                   
                }
                $dffsem3_1 = number_format($dffck,3);
                 $avgsem3_1 = $semslope->avg('sem_class3_1');
                 $countsem3_1 = $semslope->count('sem_class3_1');
                 $std_s = $semslope->select(DB::raw('STDDEV_SAMP(nt_v_data_sem.sem_class3_1) as std'))->first();
                 $stdsem3_1 = number_format($std_s->std,3);
                 $slopepush=array();
                 foreach($semslopedff7 as $slope)
                 {
                    if($std_dff->std == 0)
                    {
                        $slopest = ($slope->sem_class3_1 - $avgsem3_1)/1;
                    }
                    else
                    {
                        $slopest = ($slope->sem_class3_1 - $avgsem3_1)/$std_s->std;
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
                }else
                {
                    $natbd = 1;
                }

                if($natbd == 1)
                {
                    if(!empty($datafirst)){
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
                    }else {
                        $sem31higher--;
                        $sem31below--;
                    }
                }
            }
  

            $sem31highervs3 = 0;
            $sem31belowvs3 = 0;
            if($countsortdatalab >= 7){
                $datalabsem31 = $semdatalabvs3[0]->sem_class3_1;
                $iss = 0;
                foreach ( $semdatalabvs3 as $item){ 
                    $iss++;
                    if( $iss <= 6)
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
                     $avgsdff = $semdff->avg('sem_class3_2');
                     $std_dff = $semdff->select(DB::raw('STDDEV_SAMP(nt_v_data_sem.sem_class3_2) as std'))->first();
                     if($std_dff->std ==0)
                     {
                        $dffck = null;
                     }
                     else
                     {
                        $dffck = ($oqadatesem->sem_class3_2 - $avgsdff) / $std_dff->std;
                     }
                     $dffsem3_2 = number_format($dffck,3);
                      $avgsem3_2 = $semslope->avg('sem_class3_2');
                      $countsem3_2 = $semslope->count('sem_class3_2');
                      $std_s = $semslope->select(DB::raw('STDDEV_SAMP(nt_v_data_sem.sem_class3_2) as std'))->first();
                      $stdsem3_2 = number_format($std_s->std,3);
                      $slopepush=array();
                  
                      foreach($semslopedff7 as $slope)
                      {
                        if($std_dff->std == 0)
                        {
                            $slopest = ($slope->sem_class3_2 - $avgsem3_2)/1;
                        }
                        else
                        {
                            $slopest = ($slope->sem_class3_2 - $avgsem3_2)/$std_s->std;
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
                }else{
                    $natbd = 1;
                }

                if($natbd == 1)
                {
                    if(!empty($datafirst)){
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
                    else {
                        $sem32higher--;
                        $sem32below--;
                    }
                }
            }
     
            $sem32highervs3 = 0;
            $sem32belowvs3 = 0;
            if($countsortdatalab >= 7){
              $datalabsem32 = $semdatalabvs3[0]->sem_class3_2;
              $iss = 0;
              foreach ( $semdatalabvs3 as $item){ 
                  $iss++;
                  if( $iss <= 6)
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
                  $avgsdff = $semdff->avg('sem_class3_3');
                  $std_dff = $semdff->select(DB::raw('STDDEV_SAMP(nt_v_data_sem.sem_class3_3) as std'))->first();
                  if($std_dff->std ==0)
                  {
                  $dffck = null;
                  }
                  else
                  {
                    $dffck = ($oqadatesem->sem_class3_3 - $avgsdff) / $std_dff->std;
                  }
    
                  $dffsem3_3 = number_format($dffck,3);
                   $avgsem3_3 = $semslope->avg('sem_class3_3');
                   $countsem3_3 = $semslope->count('sem_class3_3');
                   $std_s = $semslope->select(DB::raw('STDDEV_SAMP(nt_v_data_sem.sem_class3_3) as std'))->first();
                   $stdsem3_3 = number_format($std_s->std,3);
                   $slopepush=array();
               
                   foreach($semslopedff7 as $slope)
                   {
                    if($std_dff->std == 0)
                    {
                        $slopest = ($slope->sem_class3_3 - $avgsem3_3)/1;
                    }
                    else
                    {
                        $slopest = ($slope->sem_class3_3 - $avgsem3_3)/$std_s->std;
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
                }else
                {
                    $natbd = 1;
                }

                if($natbd == 1)
                {
                    if(!empty($datafirst)){
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
                    }else{
                        $sem33higher--;
                        $sem33below--;
                    }
                }
            }
       
            $sem33highervs3 = 0;
            $sem33belowvs3 = 0;
            if($countsortdatalab >= 7){
                $datalabsem33 = $semdatalabvs3[0]->sem_class3_3;
                $iss = 0;
                foreach ( $semdatalabvs3 as $item){ 
                      $iss++;
                      if( $iss <= 6)
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
                 $avgsdff = $semdff->avg('sem_class4');
                 $std_dff = $semdff->select(DB::raw('STDDEV_SAMP(nt_v_data_sem.sem_class4) as std'))->first();
                 if($std_dff->std ==0)
                 {
                 $dffck = null;
                 }
                 else
                 {
                    $dffck = ($oqadatesem->sem_class4 - $avgsdff) / $std_dff->std;
                 }
                 $dffsem4 = number_format($dffck,3);
                  $avgsem4 = $semslope->avg('sem_class4');
                  $countsem4 = $semslope->count('sem_class4');
                  $std_s = $semslope->select(DB::raw('STDDEV_SAMP(nt_v_data_sem.sem_class4) as std'))->first();
                  $stdsem4 = number_format($std_s->std,3);
                  $slopepush=array();
              
                  foreach($semslopedff7 as $slope)
                  {
                    if($std_dff->std == 0)
                    {
                        $slopest = ($slope->sem_class4 - $avgsem4)/1;
                    }
                    else
                    {
                        $slopest = ($slope->sem_class4 - $avgsem4)/$std_s->std;
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
                    }
                    else
                    {
                        $natbd = 1;
                    }

                    if($natbd == 1)
                    {
                        if(!empty($datafirst)){
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
                        }else {
                            $sem4higher--;
                            $sem4below--;
                        }
                    }
                }
        

            $sem4highervs3 = 0;
            $sem4belowvs3 = 0;
            if($countsortdatalab >= 7){
            $datalabsem4 = $semdatalabvs3[0]->sem_class4;
            $iss = 0;
            foreach ( $semdatalabvs3 as $item){ 
                $iss++;
                if( $iss <= 6)
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
                     $avgsdff = $semdff->avg('sem_class5');
                     $std_dff = $semdff->select(DB::raw('STDDEV_SAMP(nt_v_data_sem.sem_class5) as std'))->first();
                     if($std_dff->std == 0)
                     {
                     $dffck = null;
                     }
                     else
                     {
                        $dffck = ($oqadatesem->sem_class5 - $avgsdff) / $std_dff->std;
                     }
                     $dffsem5 = number_format($dffck,3);
                      $avgsem5 = $semslope->avg('sem_class5');
                      $countsem5 = $semslope->count('sem_class5');
                      $std_s = $semslope->select(DB::raw('STDDEV_SAMP(nt_v_data_sem.sem_class5) as std'))->first();
                      $stdsem5 = number_format($std_s->std,3);
                      $slopepush=array();
                  
                      foreach($semslopedff7 as $slope)
                      {
                        if($std_dff->std == 0)
                        {
                            $slopest = ($slope->sem_class5 - $avgsem5)/1;
                        }
                        else
                        {
                            $slopest = ($slope->sem_class5 - $avgsem5)/$std_s->std;
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
                    }else
                    {
                        $natbd = 1;
                    }

                    if($natbd == 1)
                    {
                        if(!empty($datafirst)){
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
                        else {
                            $sem5higher--;
                            $sem5below--;
                        }
                    }
                }
        

              $sem5highervs3 = 0;
              $sem5belowvs3 = 0;
              if($countsortdatalab >= 7){
              $datalabsem5 = $semdatalabvs3[0]->sem_class5;
              $iss = 0;
              foreach ( $semdatalabvs3 as $item){ 
                   $iss++;
                   //$semdatalabvs3[2]->sem_class5;
                  if( $iss <= 6)
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
                                  $avgsdff = $semdff->avg('sem_class5_1');
                                  $std_dff = $semdff->select(DB::raw('STDDEV_SAMP(nt_v_data_sem.sem_class5_1) as std'))->first();
                                  if($std_dff->std == 0)
                                  {
                                  $dffck = null;
                                  }
                                  else
                                  {
                                    $dffck = ($oqadatesem->sem_class5_1 - $avgsdff) / $std_dff->std;
                                  }
                                  $dffsem5_1 = number_format($dffck,3);
                                   $avgsem5_1 = $semslope->avg('sem_class5_1');
                                   $countsem5_1 = $semslope->count('sem_class5_1');
                                   $std_s = $semslope->select(DB::raw('STDDEV_SAMP(nt_v_data_sem.sem_class5_1) as std'))->first();
                                   $stdsem5_1 = number_format($std_s->std,3);
                                   $slopepush=array();
                               
                                   foreach($semslopedff7 as $slope)
                                   {
                                    if($std_dff->std == 0)
                                    {
                                        $slopest = ($slope->sem_class5_1 - $avgsem5_1)/1;
                                    }
                                    else
                                    {
                                        $slopest = ($slope->sem_class5_1 - $avgsem5_1)/$std_s->std;
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
                    }
                    else
                    {
                        $natbd = 1;
                    }

                    if($natbd == 1)
                    {
                        if(!empty($datafirst)){
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
                        }else {
                            $sem51higher--;
                            $sem51below--;
                        }
                    }
                }
       

            $sem51highervs3 = 0;
            $sem51belowvs3 = 0;
            if($countsortdatalab >= 7){
            $datalabsem51 = $semdatalabvs3[0]->sem_class5_1;
            $iss = 0;
            foreach ( $semdatalabvs3 as $item){ 
                $iss++;
                if( $iss <= 6)
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
                 $avgsdff = $semdff->avg('sem_class6');
                 $std_dff = $semdff->select(DB::raw('STDDEV_SAMP(nt_v_data_sem.sem_class6) as std'))->first();
                 if($std_dff->std == 0)
                 {
                 $dffck = null;
                 }
                 else
                 {
                    $dffck = ($oqadatesem->sem_class6 - $avgsdff) / $std_dff->std;
                 }
                 $dffsem6 = number_format($dffck,3);
                  $avgsem6 = $semslope->avg('sem_class6');
                  $countsem6 = $semslope->count('sem_class6');
                  $std_s = $semslope->select(DB::raw('STDDEV_SAMP(nt_v_data_sem.sem_class6) as std'))->first();
                  $stdsem6 = number_format($std_s->std,3);
                  $slopepush=array();
              
                  foreach($semslopedff7 as $slope)
                  {
                    if($std_dff->std== 0)
                    {
                        $slopest = ($slope->sem_class6 - $avgsem6)/1;
                    }
                    else
                    {
                        $slopest = ($slope->sem_class6 - $avgsem6)/$std_s->std;
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
                            
                            if(!empty($data1) && $item->id_model != null){
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
                        }
                        else
                        {
                            $natbd = 1;
                        }

                        if($natbd == 1)
                        {
                        // if ($datafirst->class6 < $item->sem_class6) {
                        //     $sem6higher++;
                            
                        // } 
                        // else if ($datafirst->class6 > $item->sem_class6) {
                        //     $sem6below++;
                           
                        // } 
                        // else {
                        //     $sem6higher--;
                        //     $sem6below--;
                        // }
                        // edit 20/09/23
                        if($item->last_sem_class6  != null){
                            if(!empty($datafirst)){
                                if ($datafirst->class6 < $item->last_sem_class6) {
                                    $sem6higher++;
                                } 
                                else if ($datafirst->class6 > $item->last_sem_class6) {
                                    $sem6below++;
                                        
                                } 
                                else {
                                    $sem6higher--;
                                    $sem6below--;
                                }
                            }else{
                                $sem6higher--;
                                $sem6below--;
                            }
                        }else{
                            if(!empty($datafirst)){
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
                            }else{
                                $sem6higher--;
                                $sem6below--;
                            }
                        }   
                    }
                }
               
                $sem6highervs3 = 0;
                $sem6belowvs3 = 0;
                if($countsortdatalab >= 7){
                    $datalabsem6 = $semdatalabvs3[0]->sem_class6;
                    $iss = 0;
                    foreach ( $semdatalabvs3 as $item){ 
                        $iss++;
                        if( $iss <= 6)
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
                $error = 0;
 
                if ($sem1higher >= 9) {
                    $error++;
                    $result_arr[] = $semdatalab[0]->sem_class1;
                    $result_value_arr[] = $dataucl->class1;
                } else if ($sem1below >= 9) {
                    $error++;
                    $result_arr[] = $semdatalab[0]->sem_class1;
                    $result_value_arr[] = $dataucl->class1;
                } else {
                }
                if ($sem1highervs3 >= 6) {
                    $error++;
                    $result_arr[] = $semdatalab[0]->sem_class1;
                    $result_value_arr[] = $dataucl->class1;
                } else if ($sem1belowvs3 >= 6) {
                    $error++;
                    $result_arr[] = $semdatalab[0]->sem_class1;
                    $result_value_arr[] = $dataucl->class1;
                } else {
                }

                if(!empty($semdatalab[0]->sem_class1) && !empty($datalcl->class1)){
                    if ($semdatalab[0]->sem_class1 < $datalcl->class1) {
                        if(is_numeric($datalcl->class1) == true){
                        
                        $error++;
                        $result_arr[] = $semdatalab[0]->sem_class1;
                        $result_value_arr[] = $dataucl->class1;
                        }
                    } else if ($semdatalab[0]->sem_class1 > $dataucl->class1) {
    
                        if(is_numeric($dataucl->class1) == true){
                        
                        $error++;
                        $result_arr[] = $semdatalab[0]->sem_class1;
                        $result_value_arr[] = $dataucl->class1;
                        }
                    } else {

                    }
                }

                if(isset($semdatalab[0]->sem_class1)){
                    if ($semdatalab[0]->sem_class1 > $is->class1) {

                        if(is_numeric($is->class1) == true){
                            $error++;
                            $result_arr[] = $semdatalab[0]->sem_class1;
                            $result_value_arr[] = $dataucl->class1;
                        }  
                    } else {
                    }
                }

                if ($sem2higher >= 9) {
                    $error++;
                    $result_arr[] = $semdatalab[0]->sem_class2;
                    $result_value_arr[] = $dataucl->class2;
                   } else if ($sem2below >= 9) {
                    $error++;
                    $result_arr[] = $semdatalab[0]->sem_class2;
                    $result_value_arr[] = $dataucl->class2;
                    } else {
                    }
                if ($sem2highervs3 >= 6) {
                    $error++;
                    $result_arr[] = $semdatalab[0]->sem_class2;
                    $result_value_arr[] = $dataucl->class2;
                } else if ($sem2belowvs3 >= 6) {
                    $error++;
                    $result_arr[] = $semdatalab[0]->sem_class2;
                    $result_value_arr[] = $dataucl->class2;
                } else {
                }
                if(!empty($semdatalab[0]->sem_class2) && !empty($datalcl->class2)){
                    if ($semdatalab[0]->sem_class2 < $datalcl->class2) {
                        if(is_numeric($datalcl->class2) == true){
                            $error++;
                            $result_arr[] = $semdatalab[0]->sem_class2;
                            $result_value_arr[] = $dataucl->class2;
                        }
                    } else if ($semdatalab[0]->sem_class2 > $dataucl->class2) {
    
                        if(is_numeric($dataucl->class2) == true){
                            $error++;
                            $result_arr[] = $semdatalab[0]->sem_class2;
                            $result_value_arr[] = $dataucl->class2;
                        }
                    } else {

                    }
                }

                if(!empty($semdatalab[0]->sem_class2)){
                    if ($semdatalab[0]->sem_class2 > $is->class2) {

                        if(is_numeric($is->class2) == true){
                            $error++;
                            $result_arr[] = $semdatalab[0]->sem_class2;
                            $result_value_arr[] = $dataucl->class2;
                        }  
                    } else {
                    }
                }

                if ($sem3higher >= 9) {
                        $error++;
                        $result_arr[] = $semdatalab[0]->sem_class3;
                        $result_value_arr[] = $dataucl->class3;
                   } else if ($sem3below >= 9) {
                        $error++;
                        $result_arr[] = $semdatalab[0]->sem_class3;
                        $result_value_arr[] = $dataucl->class3;
                    } else {
                    }
                if ($sem3highervs3 >= 6) {
                    $error++;
                    $result_arr[] = $semdatalab[0]->sem_class3;
                    $result_value_arr[] = $dataucl->class3;
                } else if ($sem3belowvs3 >= 6) {
                    $error++;
                    $result_arr[] = $semdatalab[0]->sem_class3;
                    $result_value_arr[] = $dataucl->class3;
                } else {
                }

                if(!empty($semdatalab[0]->sem_class3) && !empty($datalcl->sem_class3)){
                    if ($semdatalab[0]->sem_class3 < $datalcl->class3) {
                        if(is_numeric($datalcl->class3) == true){
                            $error++;
                            $result_arr[] = $semdatalab[0]->sem_class3;
                            $result_value_arr[] = $dataucl->class3;
                        }
                    } else if ($semdatalab[0]->sem_class3 > $dataucl->class3) {
    
                        if(is_numeric($dataucl->class3) == true){
                            $error++;
                            $result_arr[] = $semdatalab[0]->sem_class3;
                            $result_value_arr[] = $dataucl->class3;
                        }
                    } else {

                    }
                }
                if(!empty($semdatalab[0]->sem_class3)){
                    if ($semdatalab[0]->sem_class3 > $is->class3) {

                        if(is_numeric($is->class3) == true){
                            $error++;
                            $result_arr[] = $semdatalab[0]->sem_class3;
                            $result_value_arr[] = $dataucl->class3;
                        }  
                    } else {
                    }
                }

                if ($sem31higher >= 9) {
                    $error++;
                    $result_arr[] = $semdatalab[0]->sem_class3_1;
                    $result_value_arr[] = $dataucl->class3_1;
                } else if ($sem31below >= 9) {
                    $error++;
                    $result_arr[] = $semdatalab[0]->sem_class3_1;
                    $result_value_arr[] = $dataucl->class3_1;
                } else {
                }
                if ($sem31highervs3 >= 6) {
                    $error++;
                    $result_arr[] = $semdatalab[0]->sem_class3_1;
                    $result_value_arr[] = $dataucl->class3_1;
                } else if ($sem31belowvs3 >= 6) {
                    $error++;
                    $result_arr[] = $semdatalab[0]->sem_class3_1;
                    $result_value_arr[] = $dataucl->class3_1;
                } else {
                }

                if(!empty($semdatalab[0]->sem_class3_1) && !empty($datalcl->class3_1)){
                    if ($semdatalab[0]->sem_class3_1 < $datalcl->class3_1) {
                        if(is_numeric($datalcl->class3_1) == true){
                            $error++;
                            $result_arr[] = $semdatalab[0]->sem_class3_1;
                            $result_value_arr[] = $dataucl->class3_1;
                        }
                    } else if ($semdatalab[0]->sem_class3_1 > $dataucl->class3_1) {
    
                        if(is_numeric($dataucl->class3_1) == true){
                            $error++;
                            $result_arr[] = $semdatalab[0]->sem_class3_1;
                            $result_value_arr[] = $dataucl->class3_1;
                        }
                    } else {

                    }
                }
                if(!empty($semdatalab[0]->sem_class3_1)){
                    if ($semdatalab[0]->sem_class3_1 > $is->class3_1) { 
                        if(is_numeric($is->class3_1) == true){
                            $error++;
                            $result_arr[] = $semdatalab[0]->sem_class3_1;
                            $result_value_arr[] = $dataucl->class3_1;
                        }  
                    } else {
                    }
                }

                if ($sem32higher >= 9) {
                    $error++;
                    $result_arr[] = $semdatalab[0]->sem_class3_2;
                    $result_value_arr[] = $dataucl->class3_2;
                } else if ($sem32below >= 9) {
                    $error++;
                    $result_arr[] = $semdatalab[0]->sem_class3_2;
                    $result_value_arr[] = $dataucl->class3_2;
                } else {
                }
                if ($sem32highervs3 >= 6) {
                    $error++;
                    $result_arr[] = $semdatalab[0]->sem_class3_2;
                    $result_value_arr[] = $dataucl->class3_2;
                } else if ($sem32belowvs3 >= 6) {
                    $error++;
                    $result_arr[] = $semdatalab[0]->sem_class3_2;
                    $result_value_arr[] = $dataucl->class3_2;
                } else {
                }
                if(!empty($semdatalab[0]->sem_class3_2) && !empty($datalcl->class3_2)){
                    if ($semdatalab[0]->sem_class3_2 < $datalcl->class3_2) {
                        if(is_numeric($datalcl->class3_2) == true){
                            $error++;
                            $result_arr[] = $semdatalab[0]->sem_class3_2;
                            $result_value_arr[] = $dataucl->class3_2;
                        }
                    } else if ($semdatalab[0]->sem_class3_2 > $dataucl->class3_2) {
    
                        if(is_numeric($dataucl->class3_2) == true){
                            $error++;
                            $result_arr[] = $semdatalab[0]->sem_class3_2;
                            $result_value_arr[] = $dataucl->class3_2;
                        }
                    } else {

                    }
                }
                if(!empty($semdatalab[0]->sem_class3_2)){
                    if ($semdatalab[0]->sem_class3_2 > $is->class3_2) {

                        if(is_numeric($is->class3_2) == true){
                            $error++;
                            $result_arr[] = $semdatalab[0]->sem_class3_2;
                            $result_value_arr[] = $dataucl->class3_2;
                        }  
                    } else {
                    }
                }

                if ($sem33higher >= 9) {
                    $error++;
                    $result_arr[] = $semdatalab[0]->sem_class3_3;
                    $result_value_arr[] = $dataucl->class3_3;
                } else if ($sem33below >= 9) {
                    $error++;
                    $result_arr[] = $semdatalab[0]->sem_class3_3;
                    $result_value_arr[] = $dataucl->class3_3;
                } else {
                }
                if ($sem33highervs3 >= 6) {
                    $error++;
                    $result_arr[] = $semdatalab[0]->sem_class3_3;
                    $result_value_arr[] = $dataucl->class3_3;
                } else if ($sem33belowvs3 >= 6) {
                    $error++;
                    $result_arr[] = $semdatalab[0]->sem_class3_3;
                    $result_value_arr[] = $dataucl->class3_3;
                } else {
                }

                if(!empty($semdatalab[0]->sem_class3_3) && !empty($datalcl->class3_3)){
                    if ($semdatalab[0]->sem_class3_3 < $datalcl->class3_3) {
                        if(is_numeric($datalcl->class3_3) == true){
                            $error++;
                            $result_arr[] = $semdatalab[0]->sem_class3_3;
                            $result_value_arr[] = $dataucl->class3_3;
                        }
                    } else if ($semdatalab[0]->sem_class3_3 > $dataucl->class3_3) {
    
                        if(is_numeric($dataucl->class3_3) == true){
                            $error++;
                            $result_arr[] = $semdatalab[0]->sem_class3_3;
                            $result_value_arr[] = $dataucl->class3_3;
                        }
                    } else {

                    }
                }
                if(!empty($semdatalab[0]->sem_class3_3)){
                    if ($semdatalab[0]->sem_class3_3 > $is->class3_3) { 
                        if(is_numeric($is->class3_3) == true){
                            $error++;
                            $result_arr[] = $semdatalab[0]->sem_class3_3;
                            $result_value_arr[] = $dataucl->class3_3;
                        }  
                    } else {
                    }
                }

                if ($sem4higher >= 9) {
                    $error++;
                    $result_arr[] = $semdatalab[0]->sem_class4;
                    $result_value_arr[] = $dataucl->class4;
                } else if ($sem4below >= 9) {
                    $error++;
                    $result_arr[] = $semdatalab[0]->sem_class4;
                    $result_value_arr[] = $dataucl->class4;
                } else {
                }
                if ($sem4highervs3 >= 6) {
                    $error++;
                    $result_arr[] = $semdatalab[0]->sem_class4;
                    $result_value_arr[] = $dataucl->class4;
                } else if ($sem4belowvs3 >= 6) {
                    $error++;
                    $result_arr[] = $semdatalab[0]->sem_class4;
                    $result_value_arr[] = $dataucl->class4;
                } else {
                }
                if(!empty($semdatalab[0]->sem_class4) && !empty($datalcl->class4)){
                    if ($semdatalab[0]->sem_class4 < $datalcl->class4) {
                        if(is_numeric($datalcl->class4) == true){ 
                            $error++;
                            $result_arr[] = $semdatalab[0]->sem_class4;
                            $result_value_arr[] = $dataucl->class4;
                        }
                    } else if ($semdatalab[0]->sem_class4 > $dataucl->class4) {
    
                        if(is_numeric($dataucl->class4) == true){
                            $error++;
                            $result_arr[] = $semdatalab[0]->sem_class4;
                            $result_value_arr[] = $dataucl->class4;
                        }
                    } else {

                    }
                }
                if(!empty($semdatalab[0]->sem_class4)){
                    if ($semdatalab[0]->sem_class4 > $is->class4) {

                        if(is_numeric($is->class4) == true){
                            $error++;
                            $result_arr[] = $semdatalab[0]->sem_class4;
                            $result_value_arr[] = $dataucl->class4;
                        }  
                    } else {
                    }
                }

                if ($sem5higher >= 9) {
                    $error++;
                    $result_arr[] = $semdatalab[0]->sem_class5;
                    $result_value_arr[] = $dataucl->class5;
                } else if ($sem5below >= 9) {
                    $error++;
                    $result_arr[] = $semdatalab[0]->sem_class5;
                    $result_value_arr[] = $dataucl->class5;
                } else {
                }
                if ($sem5highervs3 >= 6) {
                    $error++;
                    $result_arr[] = $semdatalab[0]->sem_class5;
                    $result_value_arr[] = $dataucl->class5;
                } else if ($sem5belowvs3 >= 6) {
                    $error++;
                    $result_arr[] = $semdatalab[0]->sem_class5;
                    $result_value_arr[] = $dataucl->class5;
                } else {
                }
                if(!empty($semdatalab[0]->sem_class5) && !empty($datalcl->class5)){
                    if ($semdatalab[0]->sem_class5 < $datalcl->class5) {
                        if(is_numeric($datalcl->class5) == true){
                            $error++;
                            $result_arr[] = $semdatalab[0]->sem_class5;
                            $result_value_arr[] = $dataucl->class5;
                        }
                    } else if ($semdatalab[0]->sem_class5 > $dataucl->class5) {
    
                        if(is_numeric($dataucl->class5) == true){
                            $error++;
                            $result_arr[] = $semdatalab[0]->sem_class5;
                            $result_value_arr[] = $dataucl->class5;
                        }
                    } else {

                    }
                }

                if(!empty($semdatalab[0]->sem_class5)){
                    if ($semdatalab[0]->sem_class5 > $is->class5) {

                        if(is_numeric($is->class5) == true){
                            $error++;
                            $result_arr[] = $semdatalab[0]->sem_class5;
                            $result_value_arr[] = $dataucl->class5;
                        }  
                    } else {
                    }
                }

                if ($sem51higher >= 9) {
                    $error++;
                    $result_arr[] = $semdatalab[0]->sem_class5_1;
                    $result_value_arr[] = $dataucl->class5_1;
                } else if ($sem51below >= 9) {
                    $error++;
                    $result_arr[] = $semdatalab[0]->sem_class5_1;
                    $result_value_arr[] = $dataucl->class5_1;
                } else {
                }
                if ($sem51highervs3 >= 6) {
                    $error++;
                    $result_arr[] = $semdatalab[0]->sem_class5_1;
                    $result_value_arr[] = $dataucl->class5_1;
                } else if ($sem51belowvs3 >= 6) {
                    $error++;
                    $result_arr[] = $semdatalab[0]->sem_class5_1;
                    $result_value_arr[] = $dataucl->class5_1;
                } else {
                }

                if(!empty($semdatalab[0]->sem_class5_1) && !empty($datalcl->class5_1)){
                    if ($semdatalab[0]->sem_class5_1 < $datalcl->class5_1) {
                        if(is_numeric($datalcl->class5_1) == true){
                            $error++;
                            $result_arr[] = $semdatalab[0]->sem_class5_1;
                            $result_value_arr[] = $dataucl->class5_1;
                        }
                    } else if ($semdatalab[0]->sem_class5_1 > $dataucl->class5_1) {
    
                        if(is_numeric($dataucl->class5_1) == true){
                            $error++;
                            $result_arr[] = $semdatalab[0]->sem_class5_1;
                            $result_value_arr[] = $dataucl->class5_1;
                        }
                    } else {

                    }
                }

                if(!empty($semdatalab[0]->sem_class5_1)){
                    if ($semdatalab[0]->sem_class5_1 > $is->class5_1) {

                        if(is_numeric($is->class5_1) == true){
                            $error++;
                            $result_arr[] = $semdatalab[0]->sem_class5_1;
                            $result_value_arr[] = $dataucl->class5_1;
                        }  
                    } else {
                    }
                }


                if ($sem6higher >= 9) {
                    $error++;
                    $result_arr[] = $semdatalab[0]->sem_class6;
                    $result_value_arr[] = $dataucl->class6;
                } else if ($sem6below >= 9) {
                    $error++;
                    $result_arr[] = $semdatalab[0]->sem_class6;
                    $result_value_arr[] = $dataucl->class6;
                } else {
                }
                if ($sem6highervs3 >= 6) {
                    $error++;
                    $result_arr[] = $semdatalab[0]->sem_class6;
                    $result_value_arr[] = $dataucl->class6;
                } else if ($sem6belowvs3 >= 6) {
                    $error++;
                    $result_arr[] = $semdatalab[0]->sem_class6;
                    $result_value_arr[] = $dataucl->class6;
                } else {
                }
                
                if(!empty($semdatalab[0]->sem_class6) && !empty($datalcl->class6)){
                    if ($semdatalab[0]->sem_class6 < $datalcl->class6) {
                        if(is_numeric($datalcl->class6) == true){
                            $error++;
                            $result_arr[] = $semdatalab[0]->sem_class6;
                            $result_value_arr[] = $dataucl->class6;
                        }
                    } else if ($semdatalab[0]->sem_class6 > $dataucl->class6) {
    
                        if(is_numeric($dataucl->class6) == true){
                            $error++;
                            $result_arr[] = $semdatalab[0]->sem_class6;
                            $result_value_arr[] = $dataucl->class6;
                        }
                    } else {

                    }
                }
                if(!empty($semdatalab[0]->sem_class6)){
                    if ($semdatalab[0]->sem_class6 > $is->class6) {

                        if(is_numeric($is->class6) == true){
                            $error++;
                            $result_arr[] = $semdatalab[0]->sem_class6;
                            $result_value_arr[] = $dataucl->class6;
                        }  
                    } else {
                    }
                }
                if($slopesem1 != null)
                {
                    if ($slopesem1 >= 0.5 || $slopesem1 <= -0.5 || $dffsem1 >= 3.5 ) {
                        $error++;
                        $result_arr[] = $semdatalab[0]->sem_class1;
                        $result_value_arr[] = $dataucl->class1;
                    }
                }
                
                else
                {
                    
                    if ($dffsem1 >= 3.5 &&  $dffsem1 != null) {
                        $error++;
                        $result_arr[] = $semdatalab[0]->sem_class1;
                        $result_value_arr[] = $dataucl->class1;
                    }
                }
               
                if($slopesem2 != null)
                {
                    if ($slopesem2 >= 0.5 || $slopesem2 <= -0.5 || $dffsem2 >= 3.5 ) {
                        $error++;
                        $result_arr[] = $semdatalab[0]->sem_class2;
                        $result_value_arr[] = $dataucl->class2;
                        
                    }
                }
                else
                {
                    if ($dffsem2 >= 3.5  &&  $dffsem2 != null) {
                        $error++;
                        $result_arr[] = $semdatalab[0]->sem_class2;
                        $result_value_arr[] = $dataucl->class2;
                    }
                }


                if($slopesem3 != null)
                {
                    if ($slopesem3 >= 0.5 || $slopesem3 <= -0.5 || $dffsem3 >= 3.5 ) {
                        $error++;
                        $result_arr[] = $semdatalab[0]->sem_class3;
                        $result_value_arr[] = $dataucl->class3;
                    }
                }
                else
                {
                    if ($dffsem3 >= 3.5 &&  $dffsem3 != null) {
                        $error++;
                        $result_arr[] = $semdatalab[0]->sem_class3;
                        $result_value_arr[] = $dataucl->class3;
                    }
                }
                
               
                if($slopesem3_1 != null)
                {
                    if ($slopesem3_1 >= 0.5 || $slopesem3_1 <= -0.5 || $dffsem3_1 >= 3.5 ) {
                        $error++;
                        $result_arr[] = $semdatalab[0]->sem_class3_1;
                        $result_value_arr[] = $dataucl->class3_1;
                    }
                }
                else
                {
                    if ($dffsem3_1 >= 3.5 &&  $dffsem3_1 != null) {
                        $error++;
                        $result_arr[] = $semdatalab[0]->sem_class3_1;
                        $result_value_arr[] = $dataucl->class3_1;
                    }
                }
        
               
                if($slopesem3_2 != null)
                {
                    if ($slopesem3_2 >= 0.5 || $slopesem3_2 <= -0.5 || $dffsem3_2 >= 3.5 ) {
                        $error++;
                        $result_arr[] = $semdatalab[0]->sem_class3_2;
                        $result_value_arr[] = $dataucl->class3_2;
                    }
                }
                else
                {
                    if ($dffsem3_2 >= 3.5  &&  $dffsem3_2 != null) {
                        $error++;
                        $result_arr[] = $semdatalab[0]->sem_class3_2;
                        $result_value_arr[] = $dataucl->class3_2;
                    }
                }
                
                if($slopesem3_3 != null)
                {
                    if ($slopesem3_3 >= 0.5 || $slopesem3_3 <= -0.5 || $dffsem3_3 >= 3.5 ) {
                        $error++;
                        $result_arr[] = $semdatalab[0]->sem_class3_3;
                        $result_value_arr[] = $dataucl->class3_3;
                    }
                }
                else
                {
                    if ($dffsem3_3 >= 3.5 &&  $dffsem3_3 != null) {
                        $error++;
                        $result_arr[] = $semdatalab[0]->sem_class3_3;
                        $result_value_arr[] = $dataucl->class3_3;
                    }
                }
                
                if($slopesem4 != null)
                {
                    if ($slopesem4 >= 0.5 || $slopesem4 <= -0.5 || $dffsem4 >= 3.5 ) {
                        $error++;
                        $result_arr[] = $semdatalab[0]->sem_class4;
                        $result_value_arr[] = $dataucl->class4;
                    }
                }
                else
                {
                    if ($dffsem4 >= 3.5 &&  $dffsem4 != null) {
                        $error++;
                        $result_arr[] = $semdatalab[0]->sem_class4;
                        $result_value_arr[] = $dataucl->class4;
                    }
                }
               

                if($slopesem5 != null)
                {
                    if ($slopesem5 >= 0.5 || $slopesem5 <= -0.5 || $dffsem5 >= 3.5 ) {
                        $error++;
                        $result_arr[] = $semdatalab[0]->sem_class5;
                        $result_value_arr[] = $dataucl->class5;
                    }
                }
                else
                {
                    if ($dffsem5 >= 3.5 &&  $dffsem5 != null) {
                        $error++;
                        $result_arr[] = $semdatalab[0]->sem_class5;
                        $result_value_arr[] = $dataucl->class5;
                    }
                }
               
                if($slopesem5_1 != null)
                {
                    if ($slopesem5_1 >= 0.5 || $slopesem5_1 <= -0.5 || $dffsem5_1 >= 3.5 ) {
                        $error++;
                        $result_arr[] = $semdatalab[0]->sem_class5_1;
                        $result_value_arr[] = $dataucl->class5_1;
                    }
                }
                else
                {
                    if ($dffsem5_1 >= 3.5 &&  $dffsem5_1 != null) {
                        $error++;
                        $result_arr[] = $semdatalab[0]->sem_class5_1;
                        $result_value_arr[] = $dataucl->class5_1;
                    }
                }

                if($slopesem6 != null)
                {
                    if ($slopesem6 >= 0.5 || $slopesem6 <= -0.5 || $dffsem6 >= 3.5 ) {
                        $error++;
                        $result_arr[] = $semdatalab[0]->sem_class6;
                        $result_value_arr[] = $dataucl->class6;

                    }
                }
                else
                {
                    if ($dffsem6 >= 3.5 &&  $dffsem6 != null) {
                        $error++;
                        $result_arr[] = $semdatalab[0]->sem_class6;
                        $result_value_arr[] = $dataucl->class6;
                    }
                }
               
               
                $returndata = $error;

            }else{
                $returndata = 0;
            }
            
            $data = [
                'returndata' => $returndata,
                'result' => $result_arr,
                'ucl' => $result_value_arr,
            ];
            
            return $data;
    }

}
