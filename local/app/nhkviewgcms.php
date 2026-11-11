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

class nhkviewgcms
{
    static function gcms($mailidcreatelab,$mailidmodel)
    {
            $oqa = DB::table('nt_oqa_lab')
            ->where('id_model',$mailidmodel)
            ->get();
            $oqalot = DB::table('nt_oqa_lab')
            ->where('id_createlab',$mailidcreatelab)
            ->first();
            $oqadategcms = DB::table('nt_data_gcms')
            ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
            ->where('nt_data_gcms.id_createlab',$mailidcreatelab)->where('nt_data_gcms.gcms_st',1)
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
    
            //------------------------------------- gcms -------------------------------------//
        if(!empty($oqadategcms))
        {

         
              $yeardown = date('Y-m-d 00:00:00', strtotime($oqadategcms->save_date. ' - 1 year'));
              $dateend = $oqadategcms->save_date;
        
        $gcmsdatalab = DB::table('nt_data_gcms')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
        ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
        ->where('nt_oqa_lab.save_date','<=',$dateend)
        ->where('nt_oqa_lab.id_model',$mailidmodel)
        ->where('nt_oqa_lab.gcms_confirm_st',1)
        ->limit(9)->orderBy('nt_oqa_lab.save_date', 'desc')->get();
        $gcmsdatalaball = DB::table('nt_data_gcms')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
        ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
        ->where('nt_oqa_lab.save_date','<=',$dateend)
        ->where('nt_oqa_lab.id_model',$mailidmodel)
        ->where('nt_oqa_lab.gcms_confirm_st',1)->limit(intval($oqasum->gcms_sum))->orderBy('nt_oqa_lab.save_date', 'desc')->get();
   
        $newgcmsdatalab=array();
        foreach ($gcmsdatalaball as $item){     
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
            array_push($newgcmsdatalab,$data);
        }

        if(!empty($newgcmsdatalab[0][1])){
            $datalcl =  $newgcmsdatalab[0][2];
            $dataucl =  $newgcmsdatalab[0][1];
        }
        
        $gcmsdatalabvs3 = DB::table('nt_data_gcms')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
        ->where('nt_oqa_lab.save_date','<=',$dateend)
        ->where('nt_oqa_lab.id_model',$mailidmodel)
        ->where('nt_oqa_lab.gcms_confirm_st',1)
        ->limit(7)->orderBy('nt_oqa_lab.save_date', 'desc')->get();
        $countgcmssortdatalab = count($gcmsdatalabvs3);
        // $gcmsyeardown = date('Y-m-d 00:00:00', strtotime($dateend. ' - 1 year'));
    
        $gcmsdff = DB::table('nt_v_data_gcms')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_gcms.id_createlab')
        ->where('nt_oqa_lab.save_date','<',$dateend)
        ->where('nt_oqa_lab.save_date','>=',$yeardown)
        ->where('nt_oqa_lab.gcms_confirm_st',1)
        ->where('nt_oqa_lab.id_model',$mailidmodel);
        
        if($gcmsdff->count() < 10 && $gcmsdff->count() != 0)
        {
         $gcmsdfflimit = DB::table('nt_v_data_gcms')
         ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_gcms.id_createlab')
         ->where('nt_oqa_lab.save_date','<',$dateend)
         ->where('nt_oqa_lab.gcms_confirm_st',1)
         ->where('nt_oqa_lab.id_model',$mailidmodel)->orderby('nt_oqa_lab.save_date','desc')->limit(10)->get(); 
         //dd($gcmsdff->count());
         $countdfflimit = $gcmsdfflimit->count() -1 ;

         $gcmsdff = DB::table('nt_v_data_gcms')
         ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_gcms.id_createlab')
         ->where('nt_oqa_lab.save_date','<',$dateend)
         ->where('nt_oqa_lab.save_date','>=',$gcmsdfflimit[$countdfflimit]->save_date)
         ->where('nt_oqa_lab.gcms_confirm_st',1)
         ->where('nt_oqa_lab.id_model',$mailidmodel)->limit(10);
        }
        
        $gcmsslope = DB::table('nt_v_data_gcms')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_gcms.id_createlab')
        ->where('nt_oqa_lab.save_date','<=',$dateend)
        ->where('nt_oqa_lab.save_date','>=',$yeardown)
        ->where('nt_oqa_lab.gcms_confirm_st',1)
        ->where('nt_oqa_lab.id_model',$mailidmodel);
    
        $gcmsslopedff7 = DB::table('nt_v_data_gcms')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_gcms.id_createlab')
        ->where('nt_oqa_lab.save_date','<=',$dateend)
        ->where('nt_oqa_lab.save_date','>=',$yeardown)
        ->where('nt_oqa_lab.gcms_confirm_st',1)
        ->where('nt_oqa_lab.id_model',$mailidmodel)->OrderBy('nt_oqa_lab.save_date','desc')->limit(7)->get(); 
       
        if($gcmsslope->count() < 11 && $gcmsslope->count() != 0)
        { 
         $gcmsslopelimit = DB::table('nt_v_data_gcms')
         ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_gcms.id_createlab')
         ->where('nt_oqa_lab.save_date','<=',$dateend)
         ->where('nt_oqa_lab.gcms_confirm_st',1)
         ->where('nt_oqa_lab.id_model',$mailidmodel)->orderby('nt_oqa_lab.save_date','desc')->limit(11)->get();
         $countslopelimit = $gcmsslopelimit->count() -1 ;

         $gcmsslope = DB::table('nt_v_data_gcms')
         ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_gcms.id_createlab')
         ->where('nt_oqa_lab.save_date','<=',$dateend)
         ->where('nt_oqa_lab.save_date','>=',$gcmsslopelimit[$countslopelimit]->save_date)
         ->where('nt_oqa_lab.gcms_confirm_st',1)
         ->where('nt_oqa_lab.id_model',$mailidmodel);
         
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
        ->where('nt_oqa_lab.id_model',$mailidmodel)->OrderBy('nt_oqa_lab.save_date','desc')->limit(7)->get(); 
        }
    
           
        //$gcmsdatalaball()
         //------------------------------------- gcms outgassing -------------------------------------//
    
          //-------------------------------  slope dff. gcms outgassing----------------------------------//
         $avgsdff = $gcmsdff->avg('gcms_outgassing');
              
         $std_dff = $gcmsdff->select(DB::raw('STDDEV_SAMP(nt_v_data_gcms.gcms_outgassing) as std'))->first();
         if($std_dff->std ==0)
         {
            $dffck = null;
         }
         else
         {
            $dffck = ($oqadategcms->gcms_outgassing - $avgsdff) / $std_dff->std;
         }
          
        
        
    
         $dffgcms_out = number_format($dffck,3);
          $avggcms_out = $gcmsslope->avg('gcms_outgassing');
          $countgcms_out = $gcmsslope->count('gcms_outgassing');
         
          $std_s = $gcmsslope->select(DB::raw('STDDEV_SAMP(nt_v_data_gcms.gcms_outgassing) as std'))->first();
          
          $stdgcms_out = number_format($std_s->std,3);
          $slopepush=array();
         
          foreach($gcmsslopedff7 as $slope)
          {
            if($std_dff->std == 0)
            {
                $slopest = ($slope->gcms_outgassing - $avggcms_out)/1;
            }
            else
            {
                $slopest = ($slope->gcms_outgassing - $avggcms_out)/$std_s->std;
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
                    
                    if($datafirst->outgassing == "N/A" )
                    {
                        $chktest2 = 0;
                    }
                    else if($datafirst->outgassing == "TBD")
                    {
                        $chktest2 = 0;
                    }
                    else
                    {
                        $chktest2 = 1;
                    }
                    if($chktest2 == 1)
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

            }
        $gcms1highervs3 = 0;
        $gcms1belowvs3 = 0;
        
        if( $countgcmssortdatalab >= 7)
        {
        $datalablgcms = $gcmsdatalabvs3[0]->gcms_outgassing;
        $iss = 0;
        foreach ( $gcmsdatalabvs3 as $item){ 
            $iss++;
            if( $iss <= 6)
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
         $avgsdff = $gcmsdff->avg('gcms_smear');
              
         $std_dff = $gcmsdff->select(DB::raw('STDDEV_SAMP(nt_v_data_gcms.gcms_smear) as std'))->first();
         if($std_dff->std == 0)
         {
            $dffck = null;
    
         }
         else
         {
            $dffck = ($oqadategcms->gcms_smear - $avgsdff) / $std_dff->std;
         }
         
         $dffgcms_smear = number_format($dffck,3);
          $avggcms_smear = $gcmsslope->avg('gcms_smear');
          $countgcms_smear = $gcmsslope->count('gcms_smear');
         
          $std_s = $gcmsslope->select(DB::raw('STDDEV_SAMP(nt_v_data_gcms.gcms_smear) as std'))->first();
          
          $stdgcms_smear = number_format($std_s->std,3);
          $slopepush=array();
         
          foreach($gcmsslopedff7 as $slope)
          {
            if($std_dff->std == 0)
            {
                $slopest = ($slope->gcms_smear - $avggcms_smear)/1;
            }
            else
            {
                $slopest = ($slope->gcms_smear - $avggcms_smear)/$std_s->std;
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
                    if($datafirst->alkylacrylate == "N/A" )
                    {
                        $chktest2 = 0;
                    }
                    else if($datafirst->alkylacrylate == "TBD")
                    {
                        $chktest2 = 0;
                    }
                    else
                    {
                        $chktest2 = 1;
                    }
                    if($chktest2 == 1)
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
            }
 
        $gcms2highervs3 = 0;
        $gcms2belowvs3 = 0;
        if( $countgcmssortdatalab >= 7)
        {
            $datalablgcms = $gcmsdatalabvs3[0]->gcms_smear;
            $iss = 0;
            foreach ( $gcmsdatalabvs3 as $item){ 
                $iss++;
                if( $iss <= 6)
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
         $avgsdff = $gcmsdff->avg('gcms_low_mw');
              
         $std_dff = $gcmsdff->select(DB::raw('STDDEV_SAMP(nt_v_data_gcms.gcms_low_mw) as std'))->first();
         if($std_dff->std ==0)
         {
            $dffck = null;
         }
         else
         {
            $dffck = ($oqadategcms->gcms_low_mw - $avgsdff) / $std_dff->std;
         }
         
    
         $dffgcms_low_mw = number_format($dffck,3);
          $avggcms_low_mw = $gcmsslope->avg('gcms_low_mw');
          $countgcms_low_mw = $gcmsslope->count('gcms_low_mw');
         
          $std_s = $gcmsslope->select(DB::raw('STDDEV_SAMP(nt_v_data_gcms.gcms_low_mw) as std'))->first();
          
          $stdgcms_low_mw = number_format($std_s->std,3);
          $slopepush=array();
         
          foreach($gcmsslopedff7 as $slope)
          {
            if($std_dff->std == 0)
            {
                $slopest = ($slope->gcms_low_mw - $avggcms_low_mw)/1;
            }
            else
            {
                $slopest = ($slope->gcms_low_mw - $avggcms_low_mw)/$std_s->std;
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
                    if($datafirst->low_MW == "N/A" )
                    {
                        $chktest2 = 0;
                    }
                    else if($datafirst->low_MW == "TBD")
                    {
                        $chktest2 = 0;
                    }
                    else
                    {
                        $chktest2 = 1;
                    }
                    if($chktest2 == 1)
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
                
            }
        
 
        $gcms3highervs3 = 0;
        $gcms3belowvs3 = 0;
        if( $countgcmssortdatalab >= 7)
        {
            $datalablgcms = $gcmsdatalabvs3[0]->gcms_low_mw;
            $iss = 0;
            foreach ( $gcmsdatalabvs3 as $item){ 
                $iss++;
                if( $iss <= 6)
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
         $avgsdff = $gcmsdff->avg('gcms_high_mw');
              
         $std_dff = $gcmsdff->select(DB::raw('STDDEV_SAMP(nt_v_data_gcms.gcms_high_mw) as std'))->first();
         if($std_dff->std ==0)
         {
            $dffck = null;
         }
         else
         {
            $dffck = ($oqadategcms->gcms_high_mw - $avgsdff) / $std_dff->std;
         }
    
         $dffgcms_high_mw = number_format($dffck,3);
         
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
                    if($datafirst->high_MW == "N/A" )
                    {
                        $chktest2 = 0;
                    }
                    else if($datafirst->high_MW == "TBD")
                    {
                        $chktest2 = 0;
                    }
                    else
                    {
                        $chktest2 = 1;
                    }
                    if($chktest2 == 1)
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
            }
    
        $gcms4highervs3 = 0;
        $gcms4belowvs3 = 0;
        if( $countgcmssortdatalab >= 7)
        {
            $datalablgcms = $gcmsdatalabvs3[0]->gcms_high_mw;
            $iss = 0;
            foreach ( $gcmsdatalabvs3 as $item){ 
                $iss++;
                if( $iss <= 6)
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
         $error = 0;


         if ($gcms1higher >= 9) {
            $error++;
           } else if ($gcms1below >= 9) {
            $error++;
            } else {
            }
        if ($gcms1highervs3 >= 6) {
            $error++;
        } else if ($gcms1belowvs3 >= 6) {
            $error++;
        } else {
        }
        if(isset($datalcl) && isset($dataucl)){
            if ($gcmsdatalab[0]->gcms_outgassing < $datalcl->outgassing) {
                if(is_numeric($datalcl->outgassing) == true){
                $error++;
                }
            } else if ($gcmsdatalab[0]->gcms_outgassing > $dataucl->outgassing) {

                if(is_numeric($dataucl->outgassing) == true){
                $error++;
                }
            }
        }
        if ($gcmsdatalab[0]->gcms_outgassing > $is->outgassing) {

            if(is_numeric($is->outgassing) == true){
                $error++;
            }  
        } else {
        }
       

        if ($gcms2higher >= 9) {
            $error++;
           } else if ($gcms2below >= 9) {
            $error++;
            } else {
            }
        if ($gcms2highervs3 >= 6) {
            $error++;
        } else if ($gcms2belowvs3 >= 6) {
            $error++;
        } else {
        }
        
        if(isset($datalcl) && isset($dataucl)){
            if ($gcmsdatalab[0]->gcms_smear < $datalcl->alkylacrylate) {
                if(is_numeric($datalcl->alkylacrylate) == true){
                $error++;
                }
            } else if ($gcmsdatalab[0]->gcms_smear > $dataucl->alkylacrylate) {

                if(is_numeric($dataucl->alkylacrylate) == true){
                $error++;
                }
            } else {

            }
        }

        if ($gcmsdatalab[0]->gcms_smear > $is->alkylacrylate) {

            if(is_numeric($is->alkylacrylate) == true){
                $error++;
            }  
        } else {
        }

        if ($gcms3higher >= 9) {
            $error++;
           } else if ($gcms3below >= 9) {
            $error++;
            } else {
            }
        if ($gcms3highervs3 >= 6) {
            $error++;
        } else if ($gcms3belowvs3 >= 6) {
            $error++;
        } else {
        }
        if(isset($datalcl) && isset($dataucl)){
            if ($gcmsdatalab[0]->gcms_low_mw < $datalcl->low_MW) {
                if(is_numeric($datalcl->low_MW) == true){
                $error++;
                }
            } else if ($gcmsdatalab[0]->gcms_low_mw > $dataucl->low_MW) {

                if(is_numeric($dataucl->low_MW) == true){
                $error++;
                }
            } else {

            }
        }
        if ($gcmsdatalab[0]->gcms_low_mw > $is->low_MW) {

            if(is_numeric($is->low_MW) == true){
                $error++;
            }  
        } else {
        }

        if ($gcms4higher >= 9) {
            $error++;
           } else if ($gcms4below >= 9) {
            $error++;
            } else {
            }
        if ($gcms4highervs3 >= 6) {
            $error++;
        } else if ($gcms4belowvs3 >= 6) {
            $error++;
        } else {
        }
        if(isset($datalcl) && isset($dataucl)){
            if ($gcmsdatalab[0]->gcms_high_mw < $datalcl->high_MW) {
                if(is_numeric($datalcl->high_MW) == true){
                $error++;
                }
            } else if ($gcmsdatalab[0]->gcms_high_mw > $dataucl->high_MW) {

                if(is_numeric($dataucl->high_MW) == true){
                $error++;
                }
            } else {

            }
        }

        if ($gcmsdatalab[0]->gcms_high_mw > $is->high_MW) {

            if(is_numeric($is->high_MW) == true){
                $error++;
            }  
        } else {
        }
        
        if($slopegcms_out != null)
        {
            if ($slopegcms_out >= 0.5 || $slopegcms_out <= -0.5 ||  $dffgcms_out >= 3.5) {
                $error++;
            }
        }
        else
        {
            if ($dffgcms_out >= 3.5  &&  $dffgcms_out != null) {
                $error++;
                
            }
        }
    
        if($slopegcms_smear != null)
        {
            if ($slopegcms_smear >= 0.5 || $slopegcms_smear <= -0.5 || $dffgcms_smear >= 3.5) {
                $error++;
            }
        }
        else
        {
            if ($dffgcms_smear >= 3.5 &&  $dffgcms_smear != null) {
                $error++;
            }
        }
      
        if($slopegcms_low_mw != null)
        {
            if ($slopegcms_low_mw >= 0.5 || $slopegcms_low_mw <= -0.5 || $dffgcms_low_mw >= 3.5) {
                $error++;
            }
        }
        
        else
        {
            if ($dffgcms_low_mw >= 3.5 &&  $dffgcms_low_mw != null) {
                $error++;
            }
        }
       
        if($slopegcms_high_mw != null)
        {

            if (floatval($slopegcms_high_mw) >= 0.5 || floatval($slopegcms_high_mw) <= -0.5 || $dffgcms_high_mw >= 3.5 ) {
                $error++;
               
            }
        }
        else
        {
            if ($dffgcms_high_mw >= 3.5 &&  $dffgcms_high_mw != null ) {
                $error++;
               
            }
        }
       
        $lowhigh = $gcmsdatalab[0]->gcms_low_mw + $gcmsdatalab[0]->gcms_high_mw; 
                
        $outgassingtest6 = $gcmsdatalab[0]->gcms_outgassing - $lowhigh;
       
        if ($outgassingtest6 > 20 || $outgassingtest6 < -20) {
            $error++;
        }

           $returndata = $error;
        }
        else
        {
            $returndata =0;
        }  
        return  $returndata;
    }


}
