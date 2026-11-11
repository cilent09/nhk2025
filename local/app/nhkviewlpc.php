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
use Excel;
use File;
use slope;
use nhkslopedifflpc;

class nhkviewlpc
{   
        private const MODEL_TYPE_SP = 1;
        private const MODEL_TYPE_TL = 2;
        private const MODEL_TYPE_UCL = 3;
        private const MODEL_TYPE_LCL = 4;
        private const MODEL_TYPE_CL = 5;
        private const MODEL_TYPE_IS = 6;
    
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
                    // $dffck = nhkslopedifflpc::lpc($mailidcreatelab);
                    // $dfflpc = number_format($dffck['DIFF'],3);
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
                $slopelpc = 0;
                if(count($lpcslopedff7) < 7)
                {
                    $slopelpc = null;
                }
                else
                { 
                    // $linearrelpc = slope::linearreg_slope($slopepush,sizeof($slopepush)); 
                    // $slopelpc = number_format($linearrelpc,3);
                    
                    $linearrelpc = nhkslopedifflpc::lpc($mailidcreatelab);
                    // ป้องกัน number_format() error กรณี nhkslopedifflpc::lpc() คืนค่า SLOPE เป็น "" (string ว่าง)
                    // เมื่อข้อมูลไม่พอ (count($semdatalaball) < 7 ในไฟล์ nhkslopedifflpc.php)
                    $slopeRawLegacy = $linearrelpc['SLOPE'] ?? null;
                    $slopelpc = is_numeric($slopeRawLegacy) ? number_format(floatval($slopeRawLegacy), 3) : null;

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
                        ->where('nt_model_history.chkstatus',1)
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
                            ->where('nt_model_history.chkstatus',1)
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
                            // ป้องกัน error 'alpc' of non-object กรณีไม่พบ spec (nt_model_history) เลย
                            // ทั้งช่วงวันที่ปัจจุบันและช่วงถัดไป (ไม่มี fallback ให้หาแล้ว)
                            if(empty($datafirst))
                            {
                                continue;
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

                    // ป้องกัน error กรณี $model[2], $model[3], $model[5] ไม่มีอยู่จริงใน collection
                    // (เช่น ยังไม่ได้ตั้งค่า spec ครบทุก type สำหรับ model นี้ใน nt_model_spec)
                    $chk1 = (isset($model[2]) && !in_array($model[2]->alpc, ["N/A","TBD"])) ? 1 : 0;
                    $chk2 = (isset($model[3]) && !in_array($model[3]->alpc, ["N/A","TBD"])) ? 1 : 0;
                    $chk3 = (isset($model[5]) && !in_array($model[5]->alpc, ["N/A","TBD"])) ? 1 : 0;
                    
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
                
                    if(isset($newlpcdatalab[0][0]) && !empty($newlpcdatalab[0][0])){

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

                    // ป้องกัน error กรณี $is เป็น null (ไม่มี spec type IS สำหรับ model นี้)
                    if($is && is_numeric($is->alpc)){
                        if ($datalab[0]->lpc_data > $is->alpc) {
                        
                            if($chk3 ==1)
                            {
                                $lpcerror++;
                            }
                        }
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

        static function testlpc($mailidcreatelab, $mailidmodel)
        {
            $oqadate = DB::table('nt_data_lpc')
                ->join('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_data_lpc.id_createlab')
                ->where('nt_data_lpc.id_createlab', $mailidcreatelab)->where('nt_data_lpc.lpc_st', 1)
                ->first(); 

            if (empty($oqadate)) {
                return 0; 
            }

            $modelData = DB::table('nt_model')
                ->where('id_model', $mailidmodel)
                ->first();  

            if (empty($modelData)) {
                return 0;
            }

            $modelSpecs = DB::table('nt_model_spec')
                ->join('nt_model_spec_type', 'nt_model_spec_type.id_model_type', '=', 'nt_model_spec.id_model_type')
                ->where('nt_model_spec.id_model', $mailidmodel)
                ->get()->keyBy('id_model_type'); 

            $sp = $modelSpecs->get(self::MODEL_TYPE_SP);   
            $tl = $modelSpecs->get(self::MODEL_TYPE_TL);   
            $ucl = $modelSpecs->get(self::MODEL_TYPE_UCL); 
            $lcl = $modelSpecs->get(self::MODEL_TYPE_LCL);  
            $cl = $modelSpecs->get(self::MODEL_TYPE_CL);   
            $is = $modelSpecs->get(self::MODEL_TYPE_IS);  

            $oqa = DB::table('nt_oqa_lab')
                ->where('id_model', $mailidmodel)
                ->get(); 
            $oqalot = DB::table('nt_oqa_lab')
                ->where('id_createlab', $mailidcreatelab)
                ->first();  
            $modelparameter = DB::table('nt_model_parameter')
                ->where('id_model', $mailidmodel)
                ->where('id_model_p', 1)
                ->first();  
            $datalabdone = DB::table('nt_oqa_lab')
                ->join('nt_oqa_file', 'nt_oqa_lab.id_createlab', '=', 'nt_oqa_file.id_createlab')
                ->where('nt_oqa_lab.id_createlab', $mailidcreatelab)->first(); 


            //-------------------------------------lpc----------------------------------------//
            //------------------------------- slope dff. lpc ----------------------------------// 

            $yeardown = date('Y-m-d 00:00:00', strtotime($oqadate->save_date . ' - 1 year')); 
            $dateend = $oqadate->save_date; 

            $lpcdff = DB::table('nt_v_data_lpc')
                ->join('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_v_data_lpc.id_createlab')
                ->join('nt_oqa_file', 'nt_oqa_lab.id_createlab', '=', 'nt_oqa_file.id_createlab')
                ->where('nt_oqa_lab.save_date', '<', $dateend)
                ->where('nt_oqa_lab.save_date', '>=', $yeardown)
                ->where('nt_v_data_lpc.lpc_data', '!=', null)
                ->where('nt_oqa_lab.id_model', $mailidmodel); 

            if ($lpcdff->count() < 10 && $lpcdff->count() != 0) {
                $lpcdfflimit = DB::table('nt_v_data_lpc')
                    ->join('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_v_data_lpc.id_createlab')
                    ->join('nt_oqa_file', 'nt_oqa_lab.id_createlab', '=', 'nt_oqa_file.id_createlab')
                    ->where('nt_v_data_lpc.lpc_data', '!=', null)
                    ->where('nt_oqa_lab.id_model', $mailidmodel)->orderby('nt_oqa_lab.save_date', 'desc')->limit(10)->get();
                
                $countdfflimit = $lpcdfflimit->count() - 1;

                $lpcdff = DB::table('nt_v_data_lpc')
                    ->join('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_v_data_lpc.id_createlab')
                    ->join('nt_oqa_file', 'nt_oqa_lab.id_createlab', '=', 'nt_oqa_file.id_createlab')
                    ->where('nt_oqa_lab.save_date', '<', $dateend)
                    ->where('nt_oqa_lab.save_date', '>=', $lpcdfflimit[$countdfflimit]->save_date)
                    ->where('nt_v_data_lpc.lpc_data', '!=', null)
                    ->where('nt_oqa_lab.id_model', $mailidmodel)->limit(10);
            } 

            $avgsdff = $lpcdff->avg('lpc_data');  
            $std_dff = $lpcdff->select(DB::raw('STDDEV_SAMP(nt_v_data_lpc.lpc_data) as std'))->first();  

            $stdDevDff = $std_dff->std ?? 0;
            $dfflpc = ($stdDevDff == 0)
                ? null
                : number_format(($oqadate->lpc_data - $avgsdff) / $stdDevDff, 3);  


            $lpcslope = DB::table('nt_v_data_lpc')
                ->join('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_v_data_lpc.id_createlab')
                ->join('nt_oqa_file', 'nt_oqa_lab.id_createlab', '=', 'nt_oqa_file.id_createlab')
                ->where('nt_oqa_lab.save_date', '<=', $dateend)
                ->where('nt_oqa_lab.save_date', '>=', $yeardown)
                ->where('nt_oqa_lab.lpc_confirm_st', 1)
                ->where('nt_v_data_lpc.lpc_data', '!=', null)
                ->where('nt_oqa_lab.id_model', $mailidmodel); 

            $lpcslopedff7 = DB::table('nt_v_data_lpc')
                ->join('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_v_data_lpc.id_createlab')
                ->join('nt_oqa_file', 'nt_oqa_lab.id_createlab', '=', 'nt_oqa_file.id_createlab')
                ->where('nt_oqa_lab.save_date', '<=', $dateend)
                ->where('nt_oqa_lab.save_date', '>=', $yeardown)
                ->where('nt_oqa_lab.lpc_confirm_st', 1)
                ->where('nt_v_data_lpc.lpc_data', '!=', null)
                ->where('nt_oqa_lab.id_model', $mailidmodel)->OrderBy('nt_oqa_lab.save_date', 'desc')->limit(7)->get(); 

            if ($lpcslope->count() < 11 && $lpcslope->count() != 0)
            {
                $lpcslopelimit = DB::table('nt_v_data_lpc')
                    ->join('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_v_data_lpc.id_createlab')
                    ->join('nt_oqa_file', 'nt_oqa_lab.id_createlab', '=', 'nt_oqa_file.id_createlab')
                    ->where('nt_oqa_lab.save_date', '<=', $dateend)
                    ->where('nt_oqa_lab.lpc_confirm_st', 1)
                    ->where('nt_v_data_lpc.lpc_data', '!=', null)
                    ->where('nt_oqa_lab.id_model', $mailidmodel)->orderby('nt_oqa_lab.save_date', 'desc')
                    ->limit(11)->get(); 
               
                $countslopelimit = $lpcslopelimit->count() - 1;
                $lpcslope = DB::table('nt_v_data_lpc')
                    ->join('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_v_data_lpc.id_createlab')
                    ->join('nt_oqa_file', 'nt_oqa_lab.id_createlab', '=', 'nt_oqa_file.id_createlab')
                    ->where('nt_oqa_lab.save_date', '<=', $dateend)
                    ->where('nt_oqa_lab.save_date', '>=', $lpcslopelimit[$countslopelimit]->save_date)
                    ->where('nt_oqa_lab.lpc_confirm_st', 1)
                    ->where('nt_v_data_lpc.lpc_data', '!=', null)
                    ->where('nt_oqa_lab.id_model', $mailidmodel); 
                
                $countslopelimit1 = $lpcslopelimit->count();
                $numx = ($countslopelimit1 < 7) ? $countslopelimit : 6; 

                $lpcslopedff7 = DB::table('nt_v_data_lpc')
                    ->join('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_v_data_lpc.id_createlab')
                    ->join('nt_oqa_file', 'nt_oqa_lab.id_createlab', '=', 'nt_oqa_file.id_createlab')
                    ->where('nt_oqa_lab.save_date', '<=', $dateend)
                    ->where('nt_oqa_lab.save_date', '>=', $lpcslopelimit[$numx]->save_date) 
                    ->where('nt_oqa_lab.lpc_confirm_st', 1)
                    ->where('nt_v_data_lpc.lpc_data', '!=', null)
                    ->where('nt_oqa_lab.id_model', $mailidmodel)->OrderBy('nt_oqa_lab.save_date', 'desc')->limit(7)->get();
            }

            $avgslope = $lpcslope->avg('lpc_data');
            $countlpc = $lpcslope->count('lpc_data'); 
            $std_s = $lpcslope->select(DB::raw('STDDEV_SAMP(nt_v_data_lpc.lpc_data) as std'))->first(); 
            $stdlpc = number_format($std_s->std, 3);
            
            $slopepush = array();
            
            $stdDevSlope = $std_s->std ?? 0;
            $denominator = ($stdDevDff == 0) ? 1 : $stdDevSlope; 

            foreach ($lpcslopedff7 as $slope) 
            {
                $slopest = ($slope->lpc_data - $avgslope) / $denominator;
                array_push($slopepush, $slopest);  
            }

           $slopelpc = 0; 
            if (count($lpcslopedff7) < 7) 
            {
                $slopelpc = null; 
            } else {
                $linearrelpc = nhkslopedifflpc::lpc($mailidcreatelab); 
                $slopeRaw = $linearrelpc['SLOPE'] ?? 0;
                $slopeFloat = is_numeric($slopeRaw) ? floatval($slopeRaw) : 0;
                $slopelpc = number_format($slopeFloat, 3); 
            }
            //------------------------------- end slope dff. lpc---------------------------------- 
            $datalab = DB::table('nt_data_lpc')
                ->join('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_data_lpc.id_createlab')
                ->join('nt_oqa_file', 'nt_oqa_lab.id_createlab', '=', 'nt_oqa_file.id_createlab')
                ->where('nt_oqa_lab.save_date', '<=', $dateend)
                ->where('nt_oqa_lab.id_model', $mailidmodel)  
                ->where('nt_oqa_lab.lpc_confirm_st', 1)
                ->where('nt_data_lpc.lpc_data', '!=', null)
                ->limit(9)->orderBy('nt_oqa_lab.save_date', 'desc')->get();  

            $oqasum_alpc_sum = intval($modelData->alpc_sum); 
            if ($oqasum_alpc_sum == 0) {
                $oqasum_alpc_sum = 1; 
            } 

            $datalaball = DB::table('nt_data_lpc')
                ->join('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_data_lpc.id_createlab')
                ->join('nt_oqa_file', 'nt_oqa_lab.id_createlab', '=', 'nt_oqa_file.id_createlab')
                ->where('nt_oqa_lab.save_date', '<=', $dateend)
                ->where('nt_oqa_lab.id_model', $mailidmodel)  
                ->where('nt_oqa_lab.lpc_confirm_st', 1)
                ->where('nt_data_lpc.lpc_data', '!=', null)->limit($oqasum_alpc_sum)->orderBy('nt_oqa_lab.save_date', 'desc')->get();  

            $newlpcdatalab = array();  
            foreach ($datalaball as $item) {
                $stdate = date('Y-m-d', strtotime($item->save_date));
                $data = DB::table('nt_model_history')
                    ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model_history.id_model', $item->id_model)
                    ->where('nt_model_history.date_start', '<=', $stdate)
                    ->where('nt_model_history.date_end', '>=', $stdate)  
                    ->where('nt_model_history.chkstatus', 1)
                    ->get();  
                
                if ($data->count() == 0)  
                {
                    $data1 = DB::table('nt_model_history')
                        ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model_history.id_model', $item->id_model)  
                        ->where('nt_model_history.date_start', '>', $stdate)->orderby('nt_model_history.date_start', 'asc')
                        ->first(); 
                    
                    if (!empty($data1)) { 
                        $data = DB::table('nt_model_history')
                            ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                            ->where('nt_model_history.id_model', $item->id_model) 
                            ->where('nt_model_history.date_start', '<=', $data1->date_start)
                            ->where('nt_model_history.date_end', '>=', $data1->date_start)
                            ->get(); 
                    }
                }
                array_push($newlpcdatalab, $data); 
            } 

            $datalabvs3 = DB::table('nt_data_lpc')
                ->join('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_data_lpc.id_createlab')
                ->where('nt_oqa_lab.save_date', '<=', $dateend)
                ->where('nt_oqa_lab.save_date', '>=', $yeardown)
                ->where('nt_oqa_lab.id_model', $mailidmodel) 
                ->where('nt_oqa_lab.lpc_confirm_st', 1)
                ->where('nt_data_lpc.lpc_data', '!=', null)
                ->limit(7)->orderBy('nt_oqa_lab.save_date', 'desc')->get(); 

            $higher = 0; 
            $below = 0; 

            $countdatalab = count($datalab);
            $countsortdatalab = count($datalabvs3);
            if ($countdatalab >= 9) { 
                foreach ($datalab as $item) {
                    $stdate = date('Y-m-d', strtotime($item->save_date));
                    $datafirst = DB::table('nt_model_history')
                        ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model_history.id_model', $item->id_model)
                        ->where('nt_model_history.date_start', '<=', $stdate)
                        ->where('nt_model_history.date_end', '>=', $stdate) 
                        ->where('nt_model_history.id_model_type', '=', 5)
                        ->where('nt_model_history.chkstatus', 1)
                        ->first(); 
                    
                    if ($datafirst == null) 
                    {
                        $data1 = DB::table('nt_model_history')
                            ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model') 
                            ->where('nt_model_history.id_model', $item->id_model)
                            ->where('nt_model_history.date_start', '>', $stdate)
                            ->where('nt_model_history.id_model_type', '=', 5) 
                            ->orderby('nt_model_history.date_start', 'asc')
                            ->first(); 
                        if (!empty($data1)) {
                            $datafirst = DB::table('nt_model_history')
                                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                                ->where('nt_model_history.id_model', $item->id_model)
                                ->where('nt_model_history.date_start', '<=', $data1->date_start)  
                                ->where('nt_model_history.date_end', '>=', $data1->date_start)
                                ->where('nt_model_history.id_model_type', '=', 5)
                                ->first(); 
                        }
                    }
                    
                    // ป้องกัน error กรณีไม่พบ $datafirst เลยทั้งสองรอบ
                    if (empty($datafirst)) {
                        continue;
                    }

                    $isAlpcNumeric = !in_array($datafirst->alpc, ["N/A", "TBD"]); 
                    $natbd = $isAlpcNumeric ? 1 : 0; 

                    if ($natbd == 1) 
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
            if ($countsortdatalab >= 7) {
                $datalablpc = $datalabvs3[0]->lpc_data;
                $a = array(); 

                foreach ($datalabvs3 as $item) {
                    $iss++;
                    if ($iss <= 6) 
                    {
                        if ($datalablpc < $datalabvs3[$iss]->lpc_data) {
                            $highervs3++;
                            $datalablpc = $datalabvs3[$iss]->lpc_data; 
                        } else if ($datalablpc > $datalabvs3[$iss]->lpc_data) {
                            $belowvs3++;
                            $datalablpc = $datalabvs3[$iss]->lpc_data; 
                        } else {
                            $highervs3--; 
                            $belowvs3--;
                        }
                    }
                }
            }
            $chklpcshow = 1;

            $isSpecValid = function($spec) {
                return $spec && !in_array($spec->alpc, ["N/A", "TBD"]);
            };
            
            $chk1 = $isSpecValid($ucl) ? 1 : 0; 
            $chk2 = $isSpecValid($lcl) ? 1 : 0; 
            $chk3 = $isSpecValid($is) ? 1 : 0; 

            $lpcerror = 0; 
            $errorReasons = [];

            if ($higher >= 9) {
                $lpcerror++;
                $errorReasons[] = "Rule 1: 9 points in a row above CL";
            } else if ($below >= 9) { 
                $lpcerror++; 
                $errorReasons[] = "Rule 1: 9 points in a row below CL";
            } else {
               
            }

            if ($highervs3 >= 6) {
                $lpcerror++;
                $errorReasons[] = "Rule 2: 6 points steadily increasing";
            } else if ($belowvs3 >= 6) { 
                $lpcerror++; 
                $errorReasons[] = "Rule 2: 6 points steadily decreasing";
            } else {
                
            }

            if (isset($newlpcdatalab[0][0]) && !empty($newlpcdatalab[0][0])) {
                $datalclalpc = $newlpcdatalab[0][2]->alpc;
                $datauclalpc = $newlpcdatalab[0][1]->alpc; 

                if ($datalab[0]->lpc_data < $datalclalpc) {
                    if ($chk3 == 1)
                    {
                        $lpcerror++; 
                        $errorReasons[] = "Rule 3: Point below LCL";
                    } 
                } else if ($datalab[0]->lpc_data > $datauclalpc) {
                    if ($chk1 == 1) 
                    {
                        $lpcerror++; 
                        $errorReasons[] = "Rule 3: Point above UCL";
                    } 
                } else {
                }
            }

            if ($is && is_numeric($is->alpc)) { 
                if ($datalab[0]->lpc_data > $is->alpc) {
                    if ($chk3 == 1) 
                    {
                        $lpcerror++; 
                        $errorReasons[] = "Rule 4: Point above IS";
                    } 
                }
            }

            if ($slopelpc != null) 
            {
                if ($slopelpc >= 0.5 || $slopelpc <= -0.5 || $dfflpc >= 3.5) {
                    $lpcerror++; 
                    $errorReasons[] = "Rule 5: Slope/DFF failed (Slope: $slopelpc, DFF: $dfflpc)";
                }
            } else {
                if ($dfflpc >= 3.5) {
                    $lpcerror++; 
                    $errorReasons[] = "Rule 5: DFF failed (Slope: null, DFF: $dfflpc)"; 
                } 
            }


            $returndata = [
                'count' => $lpcerror,
                'reasons' => $errorReasons
            ];
            return $returndata;
        }

        static function UCL($mailidcreatelab,$mailidmodel)
        {
            $lpcerror = 0;
            $oqafile =  DB::table('nt_oqa_file')
            ->join('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_oqa_file.id_createlab')
            ->join('nt_model', 'nt_model.id_model', '=', 'nt_oqa_lab.id_model')
            ->where('nt_oqa_file.id_createlab',$mailidcreatelab)->first(); 

            if (empty($oqafile)) {
                return null;
            }

            $file = storage_path('app/public/OQA/LPC/'.$oqafile->file_lpc);

            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
            $worksheet = $spreadsheet->setActiveSheetIndexByName('Seagate');
            $ucl = $worksheet->getCell('R17')->getOldCalculatedValue();

            $datafirstUCL = DB::table('nt_model_history')
            ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
            ->where('nt_model_history.id_model', $mailidmodel)
            ->where('nt_model_history.id_model_type','=',3)
            ->where('nt_model_history.chkstatus', 1)
            ->first(); 

            if ($datafirstUCL !== null && is_numeric($datafirstUCL->alpc) && is_numeric($ucl)) {
                if ($datafirstUCL->alpc > $ucl) {
                    $lpcerror++;
                }
            }

            $data = [
                'UCL' => $lpcerror,
                'Result' => $ucl,
            ];
            return  $data;

        }


}