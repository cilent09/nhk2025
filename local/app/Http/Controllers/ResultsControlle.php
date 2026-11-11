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
use nhkviewlpc;
use nhkviewsem;
use nhkviewic;
use nhkviewgcms;
use nhkviewftir;
use nhkmail;

use PhpOffice\PhpSpreadsheet\IOFactory; 

class ResultsControlle extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function ccpexcelic(Request $request)
    {  
        $datalaball = DB::table('nt_data_ic')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ic.id_createlab')
        ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
        ->join('nt_model','nt_model.id_model','=','nt_oqa_lab.id_model')
        ->where('nt_data_ic.id_createlab',$request->id)
        ->where('nt_oqa_lab.ic_confirm_st',1)->orderBy('nt_oqa_lab.save_date', 'desc')->get();
        $filetime = date('YmdHis');
        $namefile = 'CCP_'.$datalaball[0]->name_model.'_'.$datalaball[0]->lotnumber_lab.'_'.$filetime;
        $dataexport = 1;
        $file = storage_path('NHK-File/template/report/Template_ccpfile.xlsm');
        Excel::load($file, function ($doc)use($dataexport,$datalaball) {
                $sheet = $doc->setActiveSheetIndex(1);
                $i = 13;
                $row = 0;
                foreach ($datalaball as $item) {
                    $row++;
                    $ftime = date('n/j/Y H:i:s',strtotime($item->ftime_ic));
                    $startime = date('n/j/Y H:i:s',strtotime($item->ic_startime));
                    $sheet->setCellValue('B'.$i,$row);
                    $sheet->setCellValue('C'.$i,$ftime);
                    $sheet->setCellValue('D'.$i,$row);
                    $sheet->setCellValue('E'.$i,$item->part_number);
                    $sheet->setCellValue('F'.$i,$item->part_revision);
                    $sheet->setCellValue('G'.$i,$item->drawing_number);
                    $sheet->setCellValue('H'.$i,$item->drawing_revision);
                    $sheet->setCellValue('I'.$i,$item->vendor_id);
                    $sheet->setCellValue('J'.$i,$item->sub_supplier_name);
                    $sheet->setCellValue('K'.$i,$startime);
                    $sheet->setCellValue('M'.$i,$item->lotnumber_lab);   
                    $sheet->setCellValue('N'.$i,number_format($item->ic_parts_used, 0, '.', ''));   
                    $sheet->setCellValue('O'.$i,number_format($item->surface_area, 2, '.', ''));   
                    $sheet->setCellValue('P'.$i,number_format($item->ic_anion_up, 2, '.', ''));   
                    $sheet->setCellValue('Q'.$i,number_format($item->ic_anion_sulphate, 2, '.', ''));   
                    $i++;
                }
                   
           })->setFilename($namefile)->store('xlsm', storage_path('NHK-File/report/ccpfile'));
           $data = [
            'icstatus_done' => 1,
           ];
           DB::table('nt_oqa_lab')->where('id_createlab', $request->id)->update($data);
           $oqafile =  DB::table('nt_oqa_file')
           ->join('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_oqa_file.id_createlab')
           ->join('nt_model', 'nt_model.id_model', '=', 'nt_oqa_lab.id_model')
           ->where('nt_oqa_file.id_createlab',$request->id)->first();
           $datamail = [
            'testter'=>"IC",
            'model'=>$oqafile->name_model,
            'lot'=>$oqafile->lotnumber_lab,
            'date'=>date('d/m/Y H:i:s'),
        ];
        $mail = new MailController();
        $mail->mailccp($datamail);
        // nhkmail::mailccp($datamail);
        return response()->json(['success' =>  1, 'filename' => $namefile.'.xlsm']);
    }
    public function ccpexcelsem(Request $request)
    {  
        $datalaball = DB::table('nt_data_sem')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
        ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
        ->join('nt_model','nt_model.id_model','=','nt_oqa_lab.id_model')
        ->where('nt_data_sem.id_createlab',$request->id)
        ->where('nt_oqa_lab.sem_confirm_st',1)->orderBy('nt_oqa_lab.save_date', 'desc')->get();
        $filetime = date('YmdHis');
        $namefile = 'CCP_'.$datalaball[0]->name_model.'_'.$datalaball[0]->lotnumber_lab.'_'.$filetime;
        $dataexport = 1;
        $file = storage_path('NHK-File/template/report/Template_ccpfile.xlsm');
        Excel::load($file, function ($doc)use($dataexport,$datalaball) {
                $sheet = $doc->setActiveSheetIndex(1);
                $i = 13;
                $row = 0;
                foreach ($datalaball as $item) {
                    $row++;
                    $ftime = date('n/j/Y H:i:s',strtotime($item->ftime_sem));
                    $startime = date('n/j/Y H:i:s',strtotime($item->sem_startime));
                    
                    $sheet->setCellValue('B'.$i,$row);
                    $sheet->setCellValue('C'.$i,$ftime);
                    $sheet->setCellValue('D'.$i,$row);
                    $sheet->setCellValue('E'.$i,$item->part_number);
                    $sheet->setCellValue('F'.$i,$item->part_revision);
                    $sheet->setCellValue('G'.$i,$item->drawing_number);
                    $sheet->setCellValue('H'.$i,$item->drawing_revision);
                    $sheet->setCellValue('I'.$i,$item->vendor_id);
                    $sheet->setCellValue('J'.$i,$item->sub_supplier_name);
                    $sheet->setCellValue('K'.$i,$startime);
                    $sheet->setCellValue('M'.$i,$item->lotnumber_lab);
                    $sheet->setCellValue('AC'.$i,number_format($item->sem_parts_used, 0, '.', ''));
                    $sheet->setCellValue('AD'.$i,number_format($item->surface_area, 2, '.', ''));
                    $sheet->setCellValue('AE'.$i,number_format($item->sem_class3_2, 1, '.', ''));
                    $sheet->setCellValue('AF'.$i,number_format($item->sem_class3_3, 1, '.', ''));
                    $sheet->setCellValue('AG'.$i,number_format($item->sem_class1, 1, '.', ''));
                    $sheet->setCellValue('AH'.$i,number_format($item->sem_class3_1, 1, '.', ''));
                    $sheet->setCellValue('AI'.$i,number_format($item->sem_class3, 1, '.', ''));
                    $sheet->setCellValue('AJ'.$i,number_format($item->sem_class4, 1, '.', ''));
                    $sheet->setCellValue('AK'.$i,number_format($item->sem_class5, 1, '.', ''));
                    $sheet->setCellValue('AL'.$i,number_format($item->sem_class5_1, 1, '.', ''));
             
                    $i++;
                }
                   
           })->setFilename($namefile)->store('xlsm', storage_path('NHK-File/report/ccpfile'));
           $data = [
            'semstatus_done' => 1,
           ];
           DB::table('nt_oqa_lab')->where('id_createlab', $request->id)->update($data);
           $oqafile =  DB::table('nt_oqa_file')
           ->join('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_oqa_file.id_createlab')
           ->join('nt_model', 'nt_model.id_model', '=', 'nt_oqa_lab.id_model')
           ->where('nt_oqa_file.id_createlab',$request->id)->first();
           $datamail = [
            'testter'=>"SEM",
            'model'=>$oqafile->name_model,
            'lot'=>$oqafile->lotnumber_lab,
            'date'=>date('d/m/Y H:i:s'),
        ];
        $mail = new MailController();
        $mail->mailccp($datamail);
        // nhkmail::mailccp($datamail);
        return response()->json(['success' =>  1, 'filename' => $namefile.'.xlsm']);
    }
    public function ccpexcelgcms(Request $request)
    {  
        $datalaball = DB::table('nt_data_gcms')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
        ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
        ->join('nt_model','nt_model.id_model','=','nt_oqa_lab.id_model')
        ->leftjoin('nt_outgassing_gcms','nt_outgassing_gcms.id_createlab','=','nt_oqa_lab.id_createlab')
        ->where('nt_data_gcms.id_createlab',$request->id)
        ->where('nt_oqa_lab.gcms_confirm_st',1)->orderBy('nt_oqa_lab.save_date', 'desc')->get();
        $lot = DB::table('nt_oqa_lab')
        ->where('id_createlab',$request->id)->first();
        $filetime = date('YmdHis');
        $namefile = 'CCP_'.$datalaball[0]->name_model.'_'.$datalaball[0]->lotnumber_lab.'_'.$filetime;
        $dataexport = 1;
        $file = storage_path('NHK-File/template/report/Template_ccpfile.xlsm');
        Excel::load($file, function ($doc)use($dataexport,$datalaball) {
                $sheet = $doc->setActiveSheetIndex(1);
                $i = 13;
                $row = 0;
                foreach ($datalaball as $item) {
                    $row++;
                    $ftime = date('n/j/Y H:i:s',strtotime($item->ftime_gcms));
                    $startime = date('n/j/Y H:i:s',strtotime($item->gcms_startime));
                    $sheet->setCellValue('B'.$i,$row);
                    $sheet->setCellValue('C'.$i,$ftime);
                    $sheet->setCellValue('D'.$i,$row);
                    $sheet->setCellValue('E'.$i,$item->part_number);
                    $sheet->setCellValue('F'.$i,$item->part_revision);
                    $sheet->setCellValue('G'.$i,$item->drawing_number);
                    $sheet->setCellValue('H'.$i,$item->drawing_revision);
                    $sheet->setCellValue('I'.$i,$item->vendor_id);
                    $sheet->setCellValue('J'.$i,$item->sub_supplier_name);
                    $sheet->setCellValue('K'.$i,$startime);
                    $sheet->setCellValue('M'.$i,$item->lotnumber_lab);
                    $sheet->setCellValue('AM'.$i,number_format($item->gcms_parts_used, 0, '.', ''));
                    $sheet->setCellValue('AN'.$i,number_format($item->c1, 0, '.', ''));
                    $sheet->setCellValue('AO'.$i,number_format($item->c2, 0, '.', ''));
                    $sheet->setCellValue('AP'.$i,number_format($item->c3, 0, '.', ''));
                    $sheet->setCellValue('AQ'.$i,number_format($item->c4, 0, '.', ''));
                    $sheet->setCellValue('AR'.$i,number_format($item->c5, 0, '.', ''));
                    $sheet->setCellValue('AS'.$i,number_format($item->c5_1, 0, '.', ''));
                    $sheet->setCellValue('AT'.$i,number_format($item->c5_2, 0, '.', ''));
                    $sheet->setCellValue('AU'.$i,number_format($item->c5_3, 0, '.', ''));
                    $sheet->setCellValue('AV'.$i,number_format($item->c6, 0, '.', ''));
                    $sheet->setCellValue('AW'.$i,number_format($item->c6_1, 0, '.', ''));
                    $sheet->setCellValue('AX'.$i,number_format($item->c6_2, 0, '.', ''));
                    $sheet->setCellValue('AY'.$i,number_format($item->c7, 0, '.', ''));
                    $sheet->setCellValue('AZ'.$i,number_format($item->c8, 0, '.', ''));
                    $sheet->setCellValue('BA'.$i,number_format($item->c8_1, 0, '.', ''));
                    $sheet->setCellValue('BB'.$i,number_format($item->c8_2, 0, '.', ''));
                    $sheet->setCellValue('BC'.$i,number_format($item->c9, 0, '.', ''));
                    $sheet->setCellValue('BD'.$i,number_format($item->c9_1, 0, '.', ''));
                    $sheet->setCellValue('BE'.$i,number_format($item->c9_2, 0, '.', ''));
                    $sheet->setCellValue('BF'.$i,number_format($item->c10, 0, '.', ''));
                    $sheet->setCellValue('BG'.$i,number_format($item->c11, 0, '.', ''));
                    $sheet->setCellValue('BH'.$i,number_format($item->c11_1, 0, '.', ''));
                    $sheet->setCellValue('BI'.$i,number_format($item->c11_2, 0, '.', ''));
                    $sheet->setCellValue('BJ'.$i,number_format($item->c12, 0, '.', ''));
                    $sheet->setCellValue('BK'.$i,number_format($item->c13, 0, '.', ''));
                    $sheet->setCellValue('BL'.$i,number_format($item->c14, 0, '.', ''));
                    $sheet->setCellValue('BM'.$i,number_format($item->c15, 0, '.', ''));
                    $sheet->setCellValue('BN'.$i,number_format($item->c15_1, 0, '.', ''));
                    $sheet->setCellValue('BO'.$i,number_format($item->c5_2, 0, '.', ''));
                    $sheet->setCellValue('BP'.$i,number_format($item->c16, 0, '.', ''));
                    $sheet->setCellValue('BQ'.$i,number_format($item->c17, 0, '.', ''));
                    $sheet->setCellValue('BR'.$i,number_format($item->c18, 0, '.', ''));
                    $sheet->setCellValue('BS'.$i,number_format($item->c19, 0, '.', ''));
                    $sheet->setCellValue('BT'.$i,number_format($item->c19_1, 0, '.', ''));
                    $sheet->setCellValue('BU'.$i,number_format($item->c19_2, 0, '.', ''));
                    $sheet->setCellValue('BV'.$i,number_format($item->c19_3, 0, '.', ''));
                    $sheet->setCellValue('BW'.$i,number_format($item->c20, 0, '.', ''));
                    $sheet->setCellValue('BX'.$i,number_format($item->c20_1, 0, '.', ''));
                    $sheet->setCellValue('BY'.$i,number_format($item->c21, 0, '.', ''));
                    $sheet->setCellValue('BZ'.$i,number_format($item->c21_1, 0, '.', ''));
                    $sheet->setCellValue('CA'.$i,number_format($item->c22, 0, '.', ''));
                    $sheet->setCellValue('CB'.$i,number_format($item->c22_1, 0, '.', ''));
                    $sheet->setCellValue('CC'.$i,number_format($item->c22_2, 0, '.', ''));
                    $sheet->setCellValue('CD'.$i,number_format($item->c23, 0, '.', ''));
                    $sheet->setCellValue('CE'.$i,number_format($item->c23_1, 0, '.', ''));
                    $sheet->setCellValue('CF'.$i,number_format($item->c24, 0, '.', ''));
                    $sheet->setCellValue('CG'.$i,number_format($item->c24_1, 0, '.', ''));
                    $sheet->setCellValue('CH'.$i,number_format($item->c24_2, 0, '.', ''));
                    $sheet->setCellValue('CI'.$i,number_format($item->c25, 0, '.', ''));
                    $sheet->setCellValue('CJ'.$i,number_format($item->c26, 0, '.', ''));
                    $sheet->setCellValue('CK'.$i,number_format($item->c27, 0, '.', ''));
                    $sheet->setCellValue('CL'.$i,number_format($item->c28, 0, '.', ''));
                    $sheet->setCellValue('CM'.$i,number_format($item->c29, 0, '.', ''));
                    $sheet->setCellValue('CN'.$i,number_format($item->c30, 0, '.', ''));
                    $sheet->setCellValue('CO'.$i,number_format($item->c31, 0, '.', ''));
                    $sheet->setCellValue('CP'.$i,number_format($item->c32, 0, '.', ''));
                    $sheet->setCellValue('CQ'.$i,number_format($item->c33, 0, '.', ''));
                    $sheet->setCellValue('CR'.$i,number_format($item->total, 0, '.', ''));
                    $sheet->setCellValue('CS'.$i,number_format($item->c34, 0, '.', ''));
                    $sheet->setCellValue('CT'.$i,number_format($item->c35, 0, '.', ''));
                    $i++;
                }
        })->setFilename($namefile)->store('xlsm', storage_path('NHK-File/report/ccpfile'));
        
        $data = [
            'gcmsstatus_done' => 1,
        ];
        DB::table('nt_oqa_lab')->where('id_createlab', $request->id)->update($data);
        $oqafile =  DB::table('nt_oqa_file')
        ->join('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_oqa_file.id_createlab')
        ->join('nt_model', 'nt_model.id_model', '=', 'nt_oqa_lab.id_model')
        ->where('nt_oqa_file.id_createlab',$request->id)->first();
        
        $datamail = [
            'testter'=>"GCMS",
            'model'=>$oqafile->name_model,
            'lot'=>$oqafile->lotnumber_lab,
            'date'=>date('d/m/Y H:i:s'),
        ];

        $mail = new MailController();
        $mail->mailccp($datamail);
 
        return response()->json(['success' =>  1, 'filename' => $namefile.'.xlsm','lot' => $lot->lotnumber_lab]);
    }
    public function ccpexcelftir(Request $request)
    { 
        $oqadate = DB::table('nt_data_ftir')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ftir.id_createlab')
        ->where('nt_data_ftir.id_createlab',$request->id)->where('nt_data_ftir.ftir_st',1)
        ->first();
        $oqasum = DB::table('nt_model')
        ->where('id_model',$oqadate->id_model)
        ->first(); 
        $datalaball = DB::table('nt_data_ftir')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ftir.id_createlab')
        ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
        ->join('nt_model','nt_model.id_model','=','nt_oqa_lab.id_model')
        ->where('nt_data_ftir.id_createlab',$request->id)
        ->where('nt_oqa_lab.ftir_confirm_st',1)
        ->limit(1)->orderBy('nt_oqa_lab.save_date', 'desc')->get();
        $filetime = date('YmdHis');
        $namefile = 'CCP_'.$datalaball[0]->name_model.'_'.$datalaball[0]->lotnumber_lab.'_'.$filetime;
        $dataexport = 1;
        $file = storage_path('NHK-File/template/report/Template_ccpfile.xlsm');
        Excel::load($file, function ($doc)use($dataexport,$datalaball) {
                $sheet = $doc->setActiveSheetIndex(1);
                $i = 13;
                $row = 0;
                foreach ($datalaball as $item) {
                    $row++;
                    $ftime = date('n/j/Y H:i:s',strtotime($item->ftime_ftir));
                    $startime = date('n/j/Y H:i:s',strtotime($item->ftir_startime));
                    $sheet->setCellValue('B'.$i,$row);
                    $sheet->setCellValue('C'.$i,$ftime);
                    $sheet->setCellValue('D'.$i,$row);
                    $sheet->setCellValue('E'.$i,$item->part_number);
                    $sheet->setCellValue('F'.$i,$item->part_revision);
                    $sheet->setCellValue('G'.$i,$item->drawing_number);
                    $sheet->setCellValue('H'.$i,$item->drawing_revision);
                    $sheet->setCellValue('I'.$i,$item->vendor_id);
                    $sheet->setCellValue('J'.$i,$item->sub_supplier_name);
                    $sheet->setCellValue('K'.$i,$startime);
                    $sheet->setCellValue('M'.$i,$item->lotnumber_lab);
                    $sheet->setCellValue('R'.$i,number_format($item->ftir_parts_used, 0, '.', ''));
                    $sheet->setCellValue('S'.$i,number_format($item->surface_area, 2, '.', ''));
                    $sheet->setCellValue('T'.$i,number_format($item->ftir_silicone, 1, '.', ''));    
                    $i++;
                }    
           })->setFilename($namefile)->store('xlsm', storage_path('NHK-File/report/ccpfile'));
           $data = [
            'ftirstatus_done' => 1,
           ];
           DB::table('nt_oqa_lab')->where('id_createlab', $request->id)->update($data);
           
           $oqafile =  DB::table('nt_oqa_file')
           ->join('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_oqa_file.id_createlab')
           ->join('nt_model', 'nt_model.id_model', '=', 'nt_oqa_lab.id_model')
           ->where('nt_oqa_file.id_createlab',$request->id)->first();
           $datamail = [
            'testter'=>"FTIR",
            'model'=>$oqafile->name_model,
            'lot'=>$oqafile->lotnumber_lab,
            'date'=>date('d/m/Y H:i:s'),
        ];
        $mail = new MailController();
        $mail->mailccp($datamail);
        // nhkmail::mailccp($datamail);
        return response()->json(['success' =>  1, 'filename' => $namefile.'.xlsm']);
    }
    public function ccpfileexcel(Request $request)
    { 
        $oqadate = DB::table('nt_data_lpc')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_lpc.id_createlab')
        ->where('nt_data_lpc.id_createlab',$request->id)->where('nt_data_lpc.lpc_st',1)
        ->first();
        $oqasum = DB::table('nt_model')
        ->where('id_model',$oqadate->id_model)
        ->first(); 
        $datalaball = DB::table('nt_data_lpc')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_lpc.id_createlab')
        ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
        ->join('nt_model','nt_model.id_model','=','nt_oqa_lab.id_model')
        ->where('nt_data_lpc.id_createlab',$request->id)
        ->where('nt_oqa_lab.lpc_confirm_st',1)
        ->limit(1)->orderBy('nt_oqa_lab.save_date', 'desc')->get();
        $filetime = date('YmdHis');
        $namefile = 'CCP_'.$datalaball[0]->name_model.'_'.$datalaball[0]->lotnumber_lab.'_'.$filetime;
        $dataexport = 1;
        $file = storage_path('NHK-File/template/report/Template_ccpfile.xlsm');
        Excel::load($file, function ($doc)use($dataexport,$datalaball) {
                $sheet = $doc->setActiveSheetIndex(1);
                $i = 13;
                $row = 0;
                foreach ($datalaball as $item) {
                    $row++;
                    $ftime = date('n/j/Y H:i:s',strtotime($item->ftime_lpc));
                    $startime = date('n/j/Y H:i:s',strtotime($item->lpc_startime));
                    $sheet->setCellValue('B'.$i,$row);
                    $sheet->setCellValue('C'.$i,$ftime);
                    $sheet->setCellValue('D'.$i,$row);
                    $sheet->setCellValue('E'.$i,$item->part_number);
                    $sheet->setCellValue('F'.$i,$item->part_revision);
                    $sheet->setCellValue('G'.$i,$item->drawing_number);
                    $sheet->setCellValue('H'.$i,$item->drawing_revision);
                    $sheet->setCellValue('I'.$i,$item->vendor_id);
                    $sheet->setCellValue('J'.$i,$item->sub_supplier_name);
                    $sheet->setCellValue('K'.$i,$startime);
                    $sheet->setCellValue('M'.$i,$item->lotnumber_lab);
                    $sheet->setCellValue('U'.$i,number_format($item->alpc_parts_used, 0,'.',''));
                    $sheet->setCellValue('V'.$i,number_format($item->surface_area, 2,'.',''));
                    $sheet->setCellValue('W'.$i,number_format($item->lpc_data, 0,'.','')); 
                    $sheet->setCellValue('X'.$i,number_format($item->lpc1, 0,'.',''));
                    $sheet->setCellValue('Y'.$i,number_format($item->lpc2, 0,'.',''));
                    $sheet->setCellValue('Z'.$i,number_format($item->lpc3, 0,'.',''));
                 
                    $i++;
                }
                   
           })->setFilename($namefile)->store('xlsm', storage_path('NHK-File/report/ccpfile'));
           $data = [
            'lpcstatus_done' => 1,
           ];
           DB::table('nt_oqa_lab')->where('id_createlab', $request->id)->update($data);
          

           $oqafile =  DB::table('nt_oqa_file')
           ->join('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_oqa_file.id_createlab')
           ->join('nt_model', 'nt_model.id_model', '=', 'nt_oqa_lab.id_model')
           ->where('nt_oqa_file.id_createlab',$request->id)->first();
           $datamail = [
            'testter'=>"LPC",
            'model'=>$oqafile->name_model,
            'lot'=>$oqafile->lotnumber_lab,
            'date'=>date('d/m/Y H:i:s'),
        ];
        $mail = new MailController();
        $mail->mailccp($datamail);
        // nhkmail::mailccp($datamail);
        return response()->json(['success' =>  1, 'filename' => $namefile.'.xlsm']);
    }


    public function ccpfileexcelghost(Request $request)
    { 
        //dd(0);
        $oqadate = DB::table('nt_data_semtest')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_semtest.id_createlab')
        ->where('nt_data_semtest.id_createlab',$request->id)->where('nt_data_semtest.semtest_st',1)
        ->first();
        $oqasum = DB::table('nt_model')
        ->where('id_model',$oqadate->id_model)
        ->first(); 
        $datalaball = DB::table('nt_data_semtest')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_semtest.id_createlab')
        ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
        ->join('nt_model','nt_model.id_model','=','nt_oqa_lab.id_model')
        ->where('nt_data_semtest.id_createlab',$request->id)
        ->where('nt_oqa_lab.semtest_confirm_st',1)
        ->limit(1)->orderBy('nt_oqa_lab.save_date', 'desc')->get();
        $filetime = date('YmdHis');
        $namefile = 'CCP_'.$datalaball[0]->name_model.'_'.$datalaball[0]->lotnumber_lab.'_'.$filetime;
        $dataexport = 1;
        $file = storage_path('NHK-File/template/report/Template_ccpfile.xlsm');
        Excel::load($file, function ($doc)use($dataexport,$datalaball) {
                $sheet = $doc->setActiveSheetIndex(1);
                $i = 13;
                $row = 0;
                foreach ($datalaball as $item) {
                    $row++;
                    $ftime = date('n/j/Y H:i:s',strtotime($item->ftime_semtest));
                    $startime = date('n/j/Y H:i:s',strtotime($item->semtest_startime));
                    $sheet->setCellValue('B'.$i,$row);
                    $sheet->setCellValue('C'.$i,$ftime);
                    $sheet->setCellValue('D'.$i,$row);
                    $sheet->setCellValue('E'.$i,$item->part_number);
                    $sheet->setCellValue('F'.$i,$item->part_revision);
                    $sheet->setCellValue('G'.$i,$item->drawing_number);
                    $sheet->setCellValue('H'.$i,$item->drawing_revision);
                    $sheet->setCellValue('I'.$i,$item->vendor_id);
                    $sheet->setCellValue('J'.$i,$item->sub_supplier_name);
                    $sheet->setCellValue('K'.$i,$startime);
                    $sheet->setCellValue('M'.$i,$item->lotnumber_lab);
                    $sheet->setCellValue('DC'.$i,number_format($item->semtest_data, 0,'.',''));
                 
                    $i++;
                }
                   
           })->setFilename($namefile)->store('xlsm', storage_path('NHK-File/report/ccpfile'));
           $data = [
            'semteststatus_done' => 1,
           ];
           DB::table('nt_oqa_lab')->where('id_createlab', $request->id)->update($data);
          

           $oqafile =  DB::table('nt_oqa_file')
           ->join('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_oqa_file.id_createlab')
           ->join('nt_model', 'nt_model.id_model', '=', 'nt_oqa_lab.id_model')
           ->where('nt_oqa_file.id_createlab',$request->id)->first();
           $datamail = [
            'testter'=>"SEM (Ghost)",
            'model'=>$oqafile->name_model,
            'lot'=>$oqafile->lotnumber_lab,
            'date'=>date('d/m/Y H:i:s'),
        ];
        $mail = new MailController();
        $mail->mailccp($datamail);
        // nhkmail::mailccp($datamail);
        return response()->json(['success' =>  1, 'filename' => $namefile.'.xlsm']);
    }

    public function ccpfileexcelall(Request $request)
    { 
        
        $ccplpc= (isset($request->ccplpc))? $request->ccplpc : 0;
        $ccpsem= (isset($request->ccpsem))? $request->ccpsem : 0;
        $ccpic= (isset($request->ccpic))? $request->ccpic : 0;
        $ccpgcms= (isset($request->ccpgcms))? $request->ccpgcms : 0;
        $ccpftir= (isset($request->ccpftir))? $request->ccpftir : 0;
        $ccpsemtest= (isset($request->ccpsemtest))? $request->ccpsemtest : 0;

        $oqadate = DB::table('nt_data_lpc')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_lpc.id_createlab')
        ->where('nt_data_lpc.id_createlab',$request->id)->where('nt_data_lpc.lpc_st',1)
        ->first();
        if($oqadate == null)
        {
            $oqadate = DB::table('nt_data_sem')
            ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
            ->where('nt_data_sem.id_createlab',$request->id)->where('nt_data_sem.sem_st',1)
            ->first();
        }
        if($oqadate == null)
        {
            $oqadate = DB::table('nt_data_ic')
            ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ic.id_createlab')
            ->where('nt_data_ic.id_createlab',$request->id)->where('nt_data_ic.ic_st',1)
            ->first();
        }
        if($oqadate == null)
        {
            $oqadate = DB::table('nt_data_gcms')
            ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
            ->where('nt_data_gcms.id_createlab',$request->id)->where('nt_data_gcms.gcms_st',1)
            ->first();
        }
        if($oqadate == null)
        {
            $oqadate = DB::table('nt_data_ftir')
            ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ftir.id_createlab')
            ->where('nt_data_ftir.id_createlab',$request->id)->where('nt_data_ftir.ftir_st',1)
            ->first();
        }
        if($oqadate == null)
        {
            $oqadate = DB::table('nt_data_semtest')
            ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_semtest.id_createlab')
            ->where('nt_data_semtest.id_createlab',$request->id)->where('nt_data_semtest.semtest_st',1)
            ->first();
        }
        $oqasum = DB::table('nt_model')
        ->where('id_model',$oqadate->id_model)
        ->first(); 
        if($ccplpc == 1)
        {
                $datalablpcall = DB::table('nt_data_lpc')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_lpc.id_createlab')
                ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                ->join('nt_model','nt_model.id_model','=','nt_oqa_lab.id_model')
                ->where('nt_data_lpc.id_createlab',$request->id)
                ->where('nt_oqa_lab.lpc_confirm_st',1)
                ->limit(intval($oqasum->alpc_sum))->orderBy('nt_oqa_lab.save_date', 'desc')->first();
        }
        else
        {
            $datalablpcall = null;
        }
        if($ccpsem == 1)
        {
            $datalabsemall = DB::table('nt_data_sem')
            ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
            ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
            ->join('nt_model','nt_model.id_model','=','nt_oqa_lab.id_model')
            ->where('nt_data_sem.id_createlab',$request->id)
            ->where('nt_oqa_lab.sem_confirm_st',1)->orderBy('nt_oqa_lab.save_date', 'desc')->first();
        }
        else
        {
            $datalabsemall = null;
        }

        if($ccpic == 1)
        {
            $datalabicall = DB::table('nt_data_ic')
            ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ic.id_createlab')
            ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
            ->join('nt_model','nt_model.id_model','=','nt_oqa_lab.id_model')
            ->where('nt_data_ic.id_createlab',$request->id)
            ->where('nt_oqa_lab.ic_confirm_st',1)->orderBy('nt_oqa_lab.save_date', 'desc')->first();
        }
        else
        {
            $datalabicall = null;
        }
        if($ccpgcms == 1)
        {
        
            $datalabgcmsall = DB::table('nt_data_gcms')
            ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
            ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
            ->join('nt_model','nt_model.id_model','=','nt_oqa_lab.id_model')
            ->join('nt_outgassing_gcms','nt_outgassing_gcms.id_createlab','=','nt_oqa_lab.id_createlab')
            ->where('nt_data_gcms.id_createlab',$request->id)
            ->where('nt_oqa_lab.gcms_confirm_st',1)->orderBy('nt_oqa_lab.save_date', 'desc')->first();

        
        }
        else
        {
            $datalabgcmsall = null;
        }
        if($ccpftir == 1)
        {
            $datalabftirall = DB::table('nt_data_ftir')
            ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ftir.id_createlab')
            ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
            ->join('nt_model','nt_model.id_model','=','nt_oqa_lab.id_model')
            ->where('nt_data_ftir.id_createlab',$request->id)
            ->where('nt_oqa_lab.ftir_confirm_st',1)->orderBy('nt_oqa_lab.save_date', 'desc')->first();
        }
        else
        {
            $datalabftirall = null;
        }
        if($ccpsemtest == 1)
        {
            $datalabsemtestall = DB::table('nt_data_semtest')
            ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_semtest.id_createlab')
            ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
            ->join('nt_model','nt_model.id_model','=','nt_oqa_lab.id_model')
            ->where('nt_data_semtest.id_createlab',$request->id)
            ->where('nt_oqa_lab.semtest_confirm_st',1)->orderBy('nt_oqa_lab.save_date', 'desc')->first();
        }
        else
        {
            $datalabsemtestall = null;
        }
        //dd($datalabsemtestall->ftime_semtest);
        $filetime = date('YmdHis');
        $namefile = 'CCP_'.$oqasum->name_model.'_'.$oqadate->lotnumber_lab.'_'.$filetime;
        $dataexport = 1;
        $file = storage_path('NHK-File/template/report/Template_ccpfile.xlsm');
        Excel::load($file, function ($doc)use($dataexport,$datalablpcall,$datalabsemall,$datalabicall,$datalabgcmsall,$datalabftirall,$datalabsemtestall,$ccplpc,$ccpsem,$ccpic,$ccpgcms,$ccpftir,$ccpsemtest) {
            $sheet = $doc->setActiveSheetIndex(1);
            $i = 12;
            $row = 0;
                if($ccplpc == 1)
                {
                $i++;
                $row++;
                $ftime = date('n/j/Y H:i:s',strtotime($datalablpcall->ftime_lpc));
                $startime = date('n/j/Y H:i:s',strtotime($datalablpcall->lpc_startime));
                $sheet->setCellValue('B'.$i,$row);
                $sheet->setCellValue('C'.$i,$ftime);
                $sheet->setCellValue('D'.$i,$row);
                $sheet->setCellValue('E'.$i,$datalablpcall->part_number);
                $sheet->setCellValue('F'.$i,$datalablpcall->part_revision);
                $sheet->setCellValue('G'.$i,$datalablpcall->drawing_number);
                $sheet->setCellValue('H'.$i,$datalablpcall->drawing_revision);
                $sheet->setCellValue('I'.$i,$datalablpcall->vendor_id);
                $sheet->setCellValue('J'.$i,$datalablpcall->sub_supplier_name);
                $sheet->setCellValue('K'.$i,$startime);
                $sheet->setCellValue('M'.$i,$datalablpcall->lotnumber_lab);
                $sheet->setCellValue('U'.$i,number_format($datalablpcall->alpc_parts_used, 0,'.',''));
                $sheet->setCellValue('V'.$i,number_format($datalablpcall->surface_area, 2,'.',''));
                $sheet->setCellValue('W'.$i,number_format($datalablpcall->lpc_data, 0,'.','')); 
                $sheet->setCellValue('X'.$i,number_format($datalablpcall->lpc1, 0,'.',''));
                $sheet->setCellValue('Y'.$i,number_format($datalablpcall->lpc2, 0,'.',''));
                $sheet->setCellValue('Z'.$i,number_format($datalablpcall->lpc3, 0,'.',''));
                }
                if($ccpsem == 1)
                {
                $i++;
                $row++;
                $ftime = date('n/j/Y H:i:s',strtotime($datalabsemall->ftime_sem));
                $startime = date('n/j/Y H:i:s',strtotime($datalabsemall->sem_startime));
                
                $sheet->setCellValue('B'.$i,$row);
                $sheet->setCellValue('C'.$i,$ftime);
                $sheet->setCellValue('D'.$i,$row);
                $sheet->setCellValue('E'.$i,$datalabsemall->part_number);
                $sheet->setCellValue('F'.$i,$datalabsemall->part_revision);
                $sheet->setCellValue('G'.$i,$datalabsemall->drawing_number);
                $sheet->setCellValue('H'.$i,$datalabsemall->drawing_revision);
                $sheet->setCellValue('I'.$i,$datalabsemall->vendor_id);
                $sheet->setCellValue('J'.$i,$datalabsemall->sub_supplier_name);
                $sheet->setCellValue('K'.$i,$startime);
                $sheet->setCellValue('M'.$i,$datalabsemall->lotnumber_lab);
                $sheet->setCellValue('AC'.$i,number_format($datalabsemall->sem_parts_used, 0, '.', ''));
                $sheet->setCellValue('AD'.$i,number_format($datalabsemall->surface_area, 2, '.', ''));
                $sheet->setCellValue('AE'.$i,number_format($datalabsemall->sem_class3_2, 1, '.', ''));
                $sheet->setCellValue('AF'.$i,number_format($datalabsemall->sem_class3_3, 1, '.', ''));
                $sheet->setCellValue('AG'.$i,number_format($datalabsemall->sem_class1, 1, '.', ''));
                $sheet->setCellValue('AH'.$i,number_format($datalabsemall->sem_class3_1, 1, '.', ''));
                $sheet->setCellValue('AI'.$i,number_format($datalabsemall->sem_class3, 1, '.', ''));
                $sheet->setCellValue('AJ'.$i,number_format($datalabsemall->sem_class4, 1, '.', ''));
                $sheet->setCellValue('AK'.$i,number_format($datalabsemall->sem_class5, 1, '.', ''));
                $sheet->setCellValue('AL'.$i,number_format($datalabsemall->sem_class5_1, 1, '.', ''));
                } 
                if($ccpic == 1)
                {
                $i++;
                $row++;
                $ftime = date('n/j/Y H:i:s',strtotime($datalabicall->ftime_ic));
                $startime = date('n/j/Y H:i:s',strtotime($datalabicall->ftir_startime));
                $sheet->setCellValue('B'.$i,$row);
                $sheet->setCellValue('C'.$i,$ftime);
                $sheet->setCellValue('D'.$i,$row);
                $sheet->setCellValue('E'.$i,$datalabicall->part_number);
                $sheet->setCellValue('F'.$i,$datalabicall->part_revision);
                $sheet->setCellValue('G'.$i,$datalabicall->drawing_number);
                $sheet->setCellValue('H'.$i,$datalabicall->drawing_revision);
                $sheet->setCellValue('I'.$i,$datalabicall->vendor_id);
                $sheet->setCellValue('J'.$i,$datalabicall->sub_supplier_name);
                $sheet->setCellValue('K'.$i,$startime);
                $sheet->setCellValue('M'.$i,$datalabicall->lotnumber_lab);   
                $sheet->setCellValue('N'.$i,number_format($datalabicall->ic_parts_used, 0, '.', ''));   
                $sheet->setCellValue('O'.$i,number_format($datalabicall->surface_area, 2, '.', ''));   
                $sheet->setCellValue('P'.$i,number_format($datalabicall->ic_anion_up, 2, '.', ''));   
                $sheet->setCellValue('Q'.$i,number_format($datalabicall->ic_anion_sulphate, 2, '.', ''));   
                } 
                if($ccpgcms == 1)
                {
                $i++;
                $row++;
                $ftime = date('n/j/Y H:i:s',strtotime($datalabgcmsall->ftime_gcms));
                $startime = date('n/j/Y H:i:s',strtotime($datalabgcmsall->gcms_startime));
                $sheet->setCellValue('B'.$i,$row);
                $sheet->setCellValue('C'.$i,$ftime);
                $sheet->setCellValue('D'.$i,$row);
                $sheet->setCellValue('E'.$i,$datalabgcmsall->part_number);
                $sheet->setCellValue('F'.$i,$datalabgcmsall->part_revision);
                $sheet->setCellValue('G'.$i,$datalabgcmsall->drawing_number);
                $sheet->setCellValue('H'.$i,$datalabgcmsall->drawing_revision);
                $sheet->setCellValue('I'.$i,$datalabgcmsall->vendor_id);
                $sheet->setCellValue('J'.$i,$datalabgcmsall->sub_supplier_name);
                $sheet->setCellValue('K'.$i,$startime);
                $sheet->setCellValue('M'.$i,$datalabgcmsall->lotnumber_lab);
                $sheet->setCellValue('AM'.$i,number_format($datalabgcmsall->gcms_parts_used, 0, '.', ''));
                $sheet->setCellValue('AN'.$i,number_format($datalabgcmsall->c1, 0, '.', ''));
                $sheet->setCellValue('AO'.$i,number_format($datalabgcmsall->c2, 0, '.', ''));
                $sheet->setCellValue('AP'.$i,number_format($datalabgcmsall->c3, 0, '.', ''));
                $sheet->setCellValue('AQ'.$i,number_format($datalabgcmsall->c4, 0, '.', ''));
                $sheet->setCellValue('AR'.$i,number_format($datalabgcmsall->c5, 0, '.', ''));
                $sheet->setCellValue('AS'.$i,number_format($datalabgcmsall->c5_1, 0, '.', ''));
                $sheet->setCellValue('AT'.$i,number_format($datalabgcmsall->c5_2, 0, '.', ''));
                $sheet->setCellValue('AU'.$i,number_format($datalabgcmsall->c5_3, 0, '.', ''));
                $sheet->setCellValue('AV'.$i,number_format($datalabgcmsall->c6, 0, '.', ''));
                $sheet->setCellValue('AW'.$i,number_format($datalabgcmsall->c6_1, 0, '.', ''));
                $sheet->setCellValue('AX'.$i,number_format($datalabgcmsall->c6_2, 0, '.', ''));
                $sheet->setCellValue('AY'.$i,number_format($datalabgcmsall->c7, 0, '.', ''));
                $sheet->setCellValue('AZ'.$i,number_format($datalabgcmsall->c8, 0, '.', ''));
                $sheet->setCellValue('BA'.$i,number_format($datalabgcmsall->c8_1, 0, '.', ''));
                $sheet->setCellValue('BB'.$i,number_format($datalabgcmsall->c8_2, 0, '.', ''));
                $sheet->setCellValue('BC'.$i,number_format($datalabgcmsall->c9, 0, '.', ''));
                $sheet->setCellValue('BD'.$i,number_format($datalabgcmsall->c9_1, 0, '.', ''));
                $sheet->setCellValue('BE'.$i,number_format($datalabgcmsall->c9_2, 0, '.', ''));
                $sheet->setCellValue('BF'.$i,number_format($datalabgcmsall->c10, 0, '.', ''));
                $sheet->setCellValue('BG'.$i,number_format($datalabgcmsall->c11, 0, '.', ''));
                $sheet->setCellValue('BH'.$i,number_format($datalabgcmsall->c11_1, 0, '.', ''));
                $sheet->setCellValue('BI'.$i,number_format($datalabgcmsall->c11_2, 0, '.', ''));
                $sheet->setCellValue('BJ'.$i,number_format($datalabgcmsall->c12, 0, '.', ''));
                $sheet->setCellValue('BK'.$i,number_format($datalabgcmsall->c13, 0, '.', ''));
                $sheet->setCellValue('BL'.$i,number_format($datalabgcmsall->c14, 0, '.', ''));
                $sheet->setCellValue('BM'.$i,number_format($datalabgcmsall->c15, 0, '.', ''));
                $sheet->setCellValue('BN'.$i,number_format($datalabgcmsall->c15_1, 0, '.', ''));
                $sheet->setCellValue('BO'.$i,number_format($datalabgcmsall->c5_2, 0, '.', ''));
                $sheet->setCellValue('BP'.$i,number_format($datalabgcmsall->c16, 0, '.', ''));
                $sheet->setCellValue('BQ'.$i,number_format($datalabgcmsall->c17, 0, '.', ''));
                $sheet->setCellValue('BR'.$i,number_format($datalabgcmsall->c18, 0, '.', ''));
                $sheet->setCellValue('BS'.$i,number_format($datalabgcmsall->c19, 0, '.', ''));
                $sheet->setCellValue('BT'.$i,number_format($datalabgcmsall->c19_1, 0, '.', ''));
                $sheet->setCellValue('BU'.$i,number_format($datalabgcmsall->c19_2, 0, '.', ''));
                $sheet->setCellValue('BV'.$i,number_format($datalabgcmsall->c19_3, 0, '.', ''));
                $sheet->setCellValue('BW'.$i,number_format($datalabgcmsall->c20, 0, '.', ''));
                $sheet->setCellValue('BX'.$i,number_format($datalabgcmsall->c20_1, 0, '.', ''));
                $sheet->setCellValue('BY'.$i,number_format($datalabgcmsall->c21, 0, '.', ''));
                $sheet->setCellValue('BZ'.$i,number_format($datalabgcmsall->c21_1, 0, '.', ''));
                $sheet->setCellValue('CA'.$i,number_format($datalabgcmsall->c22, 0, '.', ''));
                $sheet->setCellValue('CB'.$i,number_format($datalabgcmsall->c22_1, 0, '.', ''));
                $sheet->setCellValue('CC'.$i,number_format($datalabgcmsall->c22_2, 0, '.', ''));
                $sheet->setCellValue('CD'.$i,number_format($datalabgcmsall->c23, 0, '.', ''));
                $sheet->setCellValue('CE'.$i,number_format($datalabgcmsall->c23_1, 0, '.', ''));
                $sheet->setCellValue('CF'.$i,number_format($datalabgcmsall->c24, 0, '.', ''));
                $sheet->setCellValue('CG'.$i,number_format($datalabgcmsall->c24_1, 0, '.', ''));
                $sheet->setCellValue('CH'.$i,number_format($datalabgcmsall->c24_2, 0, '.', ''));
                $sheet->setCellValue('CI'.$i,number_format($datalabgcmsall->c25, 0, '.', ''));
                $sheet->setCellValue('CJ'.$i,number_format($datalabgcmsall->c26, 0, '.', ''));
                $sheet->setCellValue('CK'.$i,number_format($datalabgcmsall->c27, 0, '.', ''));
                $sheet->setCellValue('CL'.$i,number_format($datalabgcmsall->c28, 0, '.', ''));
                $sheet->setCellValue('CM'.$i,number_format($datalabgcmsall->c29, 0, '.', ''));
                $sheet->setCellValue('CN'.$i,number_format($datalabgcmsall->c30, 0, '.', ''));
                $sheet->setCellValue('CO'.$i,number_format($datalabgcmsall->c31, 0, '.', ''));
                $sheet->setCellValue('CP'.$i,number_format($datalabgcmsall->c32, 0, '.', ''));
                $sheet->setCellValue('CQ'.$i,number_format($datalabgcmsall->c33, 0, '.', ''));
                $sheet->setCellValue('CR'.$i,number_format($datalabgcmsall->total, 0, '.', ''));
                $sheet->setCellValue('CS'.$i,number_format($datalabgcmsall->c34, 0, '.', ''));
                $sheet->setCellValue('CT'.$i,number_format($datalabgcmsall->c35, 0, '.', ''));

                } 
                if($ccpftir == 1)
                {
                $i++;
                $row++;
                $ftime = date('n/j/Y H:i:s',strtotime($datalabftirall->ftime_ftir));
                $startime = date('n/j/Y H:i:s',strtotime($datalabftirall->ftir_startime));
                $sheet->setCellValue('B'.$i,$row);
                $sheet->setCellValue('C'.$i,$ftime);
                $sheet->setCellValue('D'.$i,$row);
                $sheet->setCellValue('E'.$i,$datalabftirall->part_number);
                $sheet->setCellValue('F'.$i,$datalabftirall->part_revision);
                $sheet->setCellValue('G'.$i,$datalabftirall->drawing_number);
                $sheet->setCellValue('H'.$i,$datalabftirall->drawing_revision);
                $sheet->setCellValue('I'.$i,$datalabftirall->vendor_id);
                $sheet->setCellValue('J'.$i,$datalabftirall->sub_supplier_name);
                $sheet->setCellValue('K'.$i,$startime);
                $sheet->setCellValue('M'.$i,$datalabftirall->lotnumber_lab);
                $sheet->setCellValue('R'.$i,number_format($datalabftirall->ftir_parts_used, 0, '.', ''));
                $sheet->setCellValue('S'.$i,number_format($datalabftirall->surface_area, 2, '.', ''));
                $sheet->setCellValue('T'.$i,number_format($datalabftirall->ftir_silicone, 1, '.', ''));    
                } 
                if( $ccpsemtest == 1)
                {
                $i++;
                $row++;
                $ftime = date('n/j/Y H:i:s',strtotime($datalabsemtestall->ftime_semtest));
                $startime = date('n/j/Y H:i:s',strtotime($datalabsemtestall->semtest_startime));
                $sheet->setCellValue('B'.$i,$row);
                $sheet->setCellValue('C'.$i,$ftime);
                $sheet->setCellValue('D'.$i,$row);
                $sheet->setCellValue('E'.$i,$datalabsemtestall->part_number);
                $sheet->setCellValue('F'.$i,$datalabsemtestall->part_revision);
                $sheet->setCellValue('G'.$i,$datalabsemtestall->drawing_number);
                $sheet->setCellValue('H'.$i,$datalabsemtestall->drawing_revision);
                $sheet->setCellValue('I'.$i,$datalabsemtestall->vendor_id);
                $sheet->setCellValue('J'.$i,$datalabsemtestall->sub_supplier_name);
                $sheet->setCellValue('K'.$i,$startime);
                $sheet->setCellValue('M'.$i,$datalabsemtestall->lotnumber_lab);
                $sheet->setCellValue('DC'.$i,number_format($datalabsemtestall->semtest_data, 0,'.',''));   
                } 
               
           })->setFilename($namefile)->store('xlsm', storage_path('NHK-File/report/ccpfile'));
           $txtmaillpc ='';
           $txtmailsem ='';
           $txtmailic ='';
           $txtmailgcms ='';
           $txtmailftir ='';
           $txtmailsemtest ='';
            if($ccplpc == 1)
            {
                $txtmaillpc = 'LPC';
                $data = [
                    'lpcstatus_done' => 1,
                   ];
                   DB::table('nt_oqa_lab')->where('id_createlab', $request->id)->update($data);
            }
            if($ccpsem == 1)
            {
                $txtmailsem = 'SEM';
                $data = [
                    'semstatus_done' => 1,
                   ];
                   DB::table('nt_oqa_lab')->where('id_createlab', $request->id)->update($data);
            }
            if($ccpic == 1)
            {
                $txtmailic = 'IC';
                $data = [
                    'icstatus_done' => 1,
                   ];
                   DB::table('nt_oqa_lab')->where('id_createlab', $request->id)->update($data);
            }   
            if($ccpgcms == 1)
            { 
                $txtmailgcms = 'GCMS';
                $data = [
                    'gcmsstatus_done' => 1,
                   ];
                   DB::table('nt_oqa_lab')->where('id_createlab', $request->id)->update($data);
            }
            if($ccpftir == 1)
            {
                $txtmailftir = 'FTIR';
                $data = [
                    'ftirstatus_done' => 1,
                   ];
                   DB::table('nt_oqa_lab')->where('id_createlab', $request->id)->update($data);
            }
            if($ccpsemtest == 1)
            {
                $txtmailsemtest = 'SEM (Ghost)';
                $data = [
                    'semteststatus_done' => 1,
                   ];
                   DB::table('nt_oqa_lab')->where('id_createlab', $request->id)->update($data);
            }

            $oqafile =  DB::table('nt_oqa_file')
            ->join('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_oqa_file.id_createlab')
            ->join('nt_model', 'nt_model.id_model', '=', 'nt_oqa_lab.id_model')
            ->where('nt_oqa_file.id_createlab',$request->id)->first();
            $datamail = [
                'testter'=>  $txtmaillpc .' '. $txtmailsem .' '.$txtmailic .' '.$txtmailgcms .' '.$txtmailftir.' '.$txtmailsemtest,
                'model'=>$oqafile->name_model,
                'lot'=>$oqafile->lotnumber_lab,
                'date'=>date('d/m/Y H:i:s'),
            ]; 
         $mail = new MailController();
         $mail->mailccp($datamail);
        //  nhkmail::mailccp($datamail);
        return response()->json(['success' =>  1, 'filename' => $namefile.'.xlsm']);
    }
    public function viewpara(Request $request)
    {
            $data = DB::table('nt_oqa_lab')->where('id_createlab', $request->id)
            ->leftJoin('nt_model', 'nt_model.id_model', '=', 'nt_oqa_lab.id_model')
            ->leftJoin('nt_model_parameter', 'nt_model_parameter.id_model', '=', 'nt_model.id_model')
            ->where('id_model_p',1)
            ->first();
            return response()->json(['success' =>  1,'model'=> $data]);
    }

    public function datatable(Request $request)
    {
            if(empty($request->date_start) && empty($request->date_end) && empty($request->textmodel) && empty($request->textlot))
            {
                $now = date("Y-m-d 00:00:00");
                $start = date('Y-m-d H:i:s', strtotime($now));
                $end =date('Y-m-d H:i:s', strtotime('+1 day', strtotime($now)));
                $modellist = DB::table('nt_oqa_lab')
                ->leftJoin('nt_model', 'nt_model.id_model', '=', 'nt_oqa_lab.id_model')
                ->leftJoin('nt_data_lpc','nt_data_lpc.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                ->leftJoin('nt_data_sem','nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                ->leftJoin('nt_data_ic','nt_data_ic.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                ->leftJoin('nt_data_gcms','nt_data_gcms.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                ->leftJoin('nt_data_ftir','nt_data_ftir.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                ->where('nt_oqa_lab.type_lab','LIKE','SGT_%')
                ->where('nt_oqa_lab.save_date','>=',$start)->where('nt_oqa_lab.save_date','<=',$end)
                ->select('nt_oqa_lab.*','nt_model.*','nt_data_lpc.*','nt_data_sem.*','nt_data_ic.*','nt_data_gcms.*','nt_data_ftir.*','nt_oqa_lab.id_createlab as id_createlabnew')->where('nt_oqa_lab.type_lab','LIKE','SGT_%')->limit(10);
            }
            else
            {

                $start = date('Y-m-d', strtotime($request->date_start));
                $end = date('Y-m-d', strtotime($request->date_end. "+1 days"));
                $modellist = DB::table('nt_oqa_lab')
                ->leftJoin('nt_model','nt_model.id_model', '=', 'nt_oqa_lab.id_model')
                ->leftJoin('nt_data_lpc','nt_data_lpc.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                ->leftJoin('nt_data_sem','nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                ->leftJoin('nt_data_ic','nt_data_ic.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                ->leftJoin('nt_data_gcms','nt_data_gcms.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                ->leftJoin('nt_data_ftir','nt_data_ftir.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                ->where('nt_oqa_lab.type_lab','LIKE','SGT_%');
                if(!empty($request->date_start) && !empty($request->date_end))
                {
                    $modellist->where('nt_oqa_lab.save_date','>=',$start)->where('nt_oqa_lab.save_date','<=',$end);
                }
                if(!empty($request->textmodel))
                {
                    //dd($request->textmodel);
                    $modellist->where('nt_model.name_model','like','%'.$request->textmodel.'%');
                }
                if(!empty($request->textlot))
                {
                    $modellist->where('nt_oqa_lab.lotnumber_lab','like','%'.$request->textlot.'%');
                }
                $modellist->select('nt_oqa_lab.*','nt_model.*','nt_data_lpc.*','nt_data_sem.*','nt_data_ic.*','nt_data_gcms.*','nt_data_ftir.*','nt_oqa_lab.id_createlab as id_createlabnew');
                if(!empty($request->textlot))
                {
                    $modellist->offset(0)->limit(1);
                }

            }

            
            $modelSpecCache = [];
            $modelParaCache = [];

            $getModelSpec = function ($id_model, $id_model_type) use (&$modelSpecCache) {
                $key = $id_model . '_' . $id_model_type;
                if (!array_key_exists($key, $modelSpecCache)) {
                    $modelSpecCache[$key] = DB::table('nt_model_spec')
                        ->where('id_model', $id_model)
                        ->where('id_model_type', $id_model_type)
                        ->first();
                }
                return $modelSpecCache[$key];
            };

            $getModelPara = function ($id_model) use (&$modelParaCache) {
                if (!array_key_exists($id_model, $modelParaCache)) {
                    $modelParaCache[$id_model] = DB::table('nt_model_parameter')
                        ->where('id_model', $id_model)
                        ->where('id_model_p', 1)
                        ->first();
                }
                return $modelParaCache[$id_model];
            };

                // dd($modellist->get());
                $sQuery	= Datatables::of($modellist)
                ->addIndexColumn()
                ->addColumn('lpcucl',function($data) use ($getModelSpec){
                    $model_spec = $getModelSpec($data->id_model, 3);
                    if($data->lpc_data > $model_spec->alpc)
                    {
                        $chk = 1;
                    }
                    else
                    {
                        $chk = 0;
                    }
                    return $chk;
                })
                ->addColumn('chkparameterlpc',function($data) use ($getModelPara){
                    $model_para = $getModelPara($data->id_model);
                    return $model_para->lpc;
                   })
                   ->addColumn('chkparametersem',function($data) use ($getModelPara){
                    $model_para = $getModelPara($data->id_model);
                    return $model_para->sem;
                   })
                   ->addColumn('chkparameteric',function($data) use ($getModelPara){
                    $model_para = $getModelPara($data->id_model);
                    return $model_para->ic;
                   })
                   ->addColumn('chkparametergcms',function($data) use ($getModelPara){
                    $model_para = $getModelPara($data->id_model);
                    return $model_para->gcms;
                   })
                   ->addColumn('chkparameterftir',function($data) use ($getModelPara){
                    $model_para = $getModelPara($data->id_model);
                    return $model_para->ftir;
                   })
                ->addColumn('lpcitn',function($data) use ($getModelSpec){
                    $model_spec = $getModelSpec($data->id_model, 6);
                    if($data->lpc_data > $model_spec->alpc)
                    {
                         $chk = 1;
                    }
                    else
                    {
                        $chk = 0;
                    }
                       return $chk;
                   });
            return $sQuery->escapeColumns([])->make(true);
    }

    public function outputgraph(Request $request)
    {


        $oqa = DB::table('nt_oqa_lab')
        ->where('id_model',$request->input('id'))
        ->get();
        $oqalot = DB::table('nt_oqa_lab')
        ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
        ->where('nt_oqa_lab.type_lab','LIKE','SGT_%')
        ->where('nt_oqa_lab.id_createlab',$request->input('idcreatelab'))
        ->first();
        $oqadate = DB::table('nt_v_data_lpc')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_lpc.id_createlab')
        ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
        ->where('nt_oqa_lab.type_lab','LIKE','SGT_%')
        ->where('nt_v_data_lpc.id_createlab',$request->input('idcreatelab'))->where('nt_v_data_lpc.lpc_st',1)
        ->first(); 
        $oqadatesem = DB::table('nt_v_data_sem')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_sem.id_createlab')
        ->where('nt_oqa_lab.type_lab','LIKE','SGT_%')
        ->where('nt_v_data_sem.id_createlab',$request->input('idcreatelab'))->where('nt_v_data_sem.sem_st',1)
        ->first();
        
        $oqadateic = DB::table('nt_v_data_ic')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_ic.id_createlab')
        ->where('nt_oqa_lab.type_lab','LIKE','SGT_%')
        ->where('nt_v_data_ic.id_createlab',$request->input('idcreatelab'))->where('nt_v_data_ic.ic_st',1)
        ->first();

        $oqadategcms = DB::table('nt_v_data_gcms')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_gcms.id_createlab')
        ->where('nt_oqa_lab.type_lab','LIKE','SGT_%')
        ->where('nt_v_data_gcms.id_createlab',$request->input('idcreatelab'))->where('nt_v_data_gcms.gcms_st',1)
        ->first();
           
        $oqadateftir = DB::table('nt_v_data_ftir')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_v_data_ftir.id_createlab')
        ->where('nt_oqa_lab.type_lab','LIKE','SGT_%')
        ->where('nt_v_data_ftir.id_createlab',$request->input('idcreatelab'))->where('nt_v_data_ftir.ftir_st',1)
        ->first();

        $oqadatesemtest = DB::table('nt_data_semtest')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_semtest.id_createlab')
        ->where('nt_oqa_lab.type_lab','LIKE','SGT_%')
        ->where('nt_data_semtest.id_createlab',$request->input('idcreatelab'))->where('nt_data_semtest.semtest_st',1)
        ->first();
               
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
               //->where('nt_oqa_lab.save_date','<',$dateend)
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
                   $datalab = DB::table('nt_data_lpc')
                   ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_lpc.id_createlab')
                   ->join('nt_oqa_file','nt_oqa_file.id_createlab','=','nt_data_lpc.id_createlab')
                   //ma edit
                   // ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                   ->where('nt_oqa_lab.save_date','<=',$dateend)
                   ->where('nt_oqa_lab.id_model',$request->input('id'))
                   ->where('nt_oqa_lab.lpc_confirm_st',1)
                   ->where('nt_data_lpc.lpc_data','!=',null)
                   // ->limit($alarmtest2)->orderBy('nt_oqa_lab.save_date', 'desc')->get();
                   ->limit($alarmtest2)->orderBy('nt_data_lpc.id_createlab', 'desc')->get();

                   $datalaball = DB::table('nt_data_lpc')
                   ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_lpc.id_createlab')
                   ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                   ->where('nt_oqa_lab.save_date','<=',$dateend)
                   ->where('nt_oqa_lab.id_model',$request->input('id'))
                   ->where('nt_oqa_lab.lpc_confirm_st',1)
                   ->where('nt_data_lpc.lpc_data','!=',null);

                   if($request->chkpdf == 0 || $request->chkpdf == 2 )
                   {
                    $oqasum_alpc_sum = intval($oqasum->alpc_sum);
                    if($oqasum_alpc_sum == 0){
                        $oqasum_alpc_sum = 1;
                    }
                    //    $datalaball = $datalaball->limit(intval($oqasum->alpc_sum))->orderBy('nt_oqa_lab.save_date', 'desc')->get();
                       $datalaball = $datalaball->limit($oqasum_alpc_sum)->orderBy('nt_oqa_lab.save_date', 'desc')->get();
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
               
                   $datalabvs3 = DB::table('nt_data_lpc')
                   ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_lpc.id_createlab')
                   ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                   ->where('nt_oqa_lab.save_date','<=',$dateend)
                   ->where('nt_oqa_lab.save_date','>=',$yeardown)
                   ->where('nt_oqa_lab.id_model',$request->input('id'))
                   ->where('nt_oqa_lab.lpc_confirm_st',1)
                   ->where('nt_data_lpc.lpc_data','!=',null)
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

           $semdatalab = DB::table('nt_data_sem')
           ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
           ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
           ->where('nt_oqa_lab.save_date','<=',$dateend)
           ->where('nt_oqa_lab.id_model',$request->input('id'))
           ->where('nt_oqa_lab.sem_confirm_st',1)
           ->limit($alarmtest2)->orderBy('nt_oqa_lab.save_date', 'desc')->get();
           
           $semdatalaball = DB::table('nt_data_sem')
           ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
           ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
           ->where('nt_oqa_lab.save_date','<=',$dateend)
           ->where('nt_oqa_lab.id_model',$request->input('id'))
           ->where('nt_oqa_lab.sem_confirm_st',1);

           if($request->chkpdf == 0 || $request->chkpdf == 2)
           {
                $oqasum_sem_sum = intval($oqasum->sem_sum);
                if($oqasum_sem_sum == 0){
                    $oqasum_sem_sum = 1;
                }
                // //$datalaball = $datalaball->limit(intval($oqasum->alpc_sum))->orderBy('nt_oqa_lab.save_date', 'desc')->get();
                //$semdatalaball =  $semdatalaball->limit(intval($oqasum->sem_sum))->orderBy('nt_oqa_lab.save_date', 'desc')->get();
               $semdatalaball =  $semdatalaball->limit($oqasum_sem_sum)->orderBy('nt_oqa_lab.save_date', 'desc')->get();
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
           
           
           $semdatalabvs3 = DB::table('nt_data_sem')
           ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
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
                               if($item->last_sem_class1 != null){
                                   if ($datafirst->class1 < $item->last_sem_class1) {
                                       $sem1higher++;        
                                   }else if ($datafirst->class1 > $item->last_sem_class1) {
                                       $sem1below++;      
                                   }else {
                                       $sem1higher--;
                                       $sem1below--;
                                   }
                               }else{
                                   if ($datafirst->class1 < $item->sem_class1) {
                                       $sem1higher++;        
                                   }else if ($datafirst->class1 > $item->sem_class1) {
                                       $sem1below++;      
                                   }else {
                                       $sem1higher--;
                                       $sem1below--;
                                   }
                               }

                           }
                       }
                   $sem1highervs3 = 0;
                   $sem1belowvs3 = 0;
                   if($semdatalabvs3[0]->last_sem_class1 !=null){
                       $datalabsem1 = $semdatalabvs3[0]->last_sem_class1;
                   }else{
                       $datalabsem1 = $semdatalabvs3[0]->sem_class1;
                   }
                   $iss = 0;
                   if($countsortdatalab >= $alarmtest3y){         
                       foreach ( $semdatalabvs3 as $item){ 
                           $iss++;
                           if( $iss <= $alarmtest3)
                           {
                               if($semdatalabvs3[$iss]->last_sem_class1 != null){
                                   if ($datalabsem1 < $semdatalabvs3[$iss]->last_sem_class1) {
                                       $sem1highervs3++;
                                       $datalabsem1 = $semdatalabvs3[$iss]->last_sem_class1;
                                   } 
                                   else if ($datalabsem1 > $semdatalabvs3[$iss]->last_sem_class1) {
                                       $sem1belowvs3++;
                                       $datalabsem1 = $semdatalabvs3[$iss]->last_sem_class1;
                                   } 
                                   else {
                                       $sem1highervs3--;
                                       $sem1belowvs3--;
                                   }
                               }else{
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
                       if($item->last_sem_class2 != null){
                           if ($datafirst->class2 < $item->last_sem_class2) {
                               $sem2higher++;
                               
                           } 
                           else if ($datafirst->class2 > $item->last_sem_class2) {
                               $sem2below++;
                           
                           } 
                           else {
                               $sem2higher--;
                               $sem2below--;
                           }
                       }else{
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
               }
           $sem2highervs3 = 0;
           $sem2belowvs3 = 0;
           if($countsortdatalab >= $alarmtest3y){
               if($semdatalabvs3[0]->last_sem_class2 != null){
                   $datalabsem2 = $semdatalabvs3[0]->last_sem_class2;
               }else{
                   $datalabsem2 = $semdatalabvs3[0]->sem_class2;
               }
               $iss = 0;
                   foreach ( $semdatalabvs3 as $item){ 
                       $iss++;
                       if( $iss <= $alarmtest3)
                       {

                           if($semdatalabvs3[$iss]->last_sem_class2 != null){
                               if ($datalabsem2 < $semdatalabvs3[$iss]->last_sem_class2) {
                                   $sem2highervs3++;
                                   $datalabsem2 = $semdatalabvs3[$iss]->last_sem_class2;
                               } 
                               else if ($datalabsem2 > $semdatalabvs3[$iss]->last_sem_class2) {
                                   $sem2belowvs3++;
                                   $datalabsem2 = $semdatalabvs3[$iss]->last_sem_class2;
                               } 
                               else {
                                   $sem2highervs3--;
                                   $sem2belowvs3--;
                               }
                           }else{
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

                       if($item->last_sem_class3 != null){

                           if ($datafirst->class3 < $item->last_sem_class3) {
                               $sem3higher++;
                               
                           } 
                           else if ($datafirst->class3 > $item->last_sem_class3) {
                               $sem3below++;
                           
                           } 
                           else {
                               $sem3higher--;
                               $sem3below--;
                           }
                       
                       }else{
                           
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
               }
           $sem3highervs3 = 0;
           $sem3belowvs3 = 0;
           if($countsortdatalab >= $alarmtest3y){
                   if($semdatalabvs3[0]->last_sem_class3 != null){
                       $datalabsem3 = $semdatalabvs3[0]->last_sem_class3;
                   }else{
                       $datalabsem3 = $semdatalabvs3[0]->sem_class3;
                   }
                   $iss = 0;
                   foreach ( $semdatalabvs3 as $item){ 
                       $iss++;
                       if( $iss <= $alarmtest3)
                       {
                           if($semdatalabvs3[$iss]->last_sem_class3 != null){
                               if ($datalabsem3 < $semdatalabvs3[$iss]->last_sem_class3) {
                                   $sem3highervs3++;
                                   $datalabsem3 = $semdatalabvs3[$iss]->last_sem_class3;
                               } 
                               else if ($datalabsem3 > $semdatalabvs3[$iss]->last_sem_class3) {
                                   $sem3belowvs3++;
                                   $datalabsem3 = $semdatalabvs3[$iss]->last_sem_class3;
                               } 
                               else {
                                   $sem3highervs3--;
                                   $sem3belowvs3--;
                               }
                           }else{
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
                           if($item->last_sem_class3_1 != null){
                               if ($datafirst->class3_1 < $item->last_sem_class3_1) {
                                   $sem31higher++;
                                   
                               } 
                               else if ($datafirst->class3_1 > $item->last_sem_class3_1) {
                                   $sem31below++;
                               
                               } 
                               else {
                                   $sem31higher--;
                                   $sem31below--;
                               }
                           }else{
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
                   }

               $sem31highervs3 = 0;
               $sem31belowvs3 = 0;
               if($countsortdatalab >= $alarmtest3y){
                   if($semdatalabvs3[0]->last_sem_class3_1 != null){
                       $datalabsem31 = $semdatalabvs3[0]->last_sem_class3_1;
                   }else{
                       $datalabsem31 = $semdatalabvs3[0]->sem_class3_1;
                   }
                   $iss = 0;
                   foreach ( $semdatalabvs3 as $item){ 
                       $iss++;
                       if( $iss <= $alarmtest3)
                       {
                           if($semdatalabvs3[$iss]->last_sem_class3_1 != null){
                               if ($datalabsem31 < $semdatalabvs3[$iss]->last_sem_class3_1) {
                                   $sem31highervs3++;
                                   $datalabsem31 = $semdatalabvs3[$iss]->last_sem_class3_1;
                               } 
                               else if ($datalabsem31 > $semdatalabvs3[$iss]->last_sem_class3_1) {
                                   $sem31belowvs3++;
                                   $datalabsem31 = $semdatalabvs3[$iss]->last_sem_class3_1;
                               } 
                               else {
                                   $sem31highervs3--;
                                   $sem31belowvs3--;
                               }
                           }else{
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
                           if($item->last_sem_class3_2  != null){
                               if ($datafirst->class3_2 < $item->last_sem_class3_2) {
                                   $sem32higher++;
                                   
                               } 
                               else if ($datafirst->class3_2 > $item->last_sem_class3_2) {
                                   $sem32below++;
                               
                               } 
                               else {
                                   $sem32higher--;
                                   $sem32below--;
                               }
                           }else{
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
                   }
               $sem32highervs3 = 0;
               $sem32belowvs3 = 0;
               if($countsortdatalab >= $alarmtest3y){
                   if($semdatalabvs3[0]->last_sem_class3_2  != null){
                       $datalabsem32 = $semdatalabvs3[0]->last_sem_class3_2;
                   }else{
                       $datalabsem32 = $semdatalabvs3[0]->sem_class3_2;
                   }

                   $iss = 0;
                   foreach ( $semdatalabvs3 as $item){ 
                       $iss++;
                       if( $iss <= $alarmtest3)
                       {
                           if($semdatalabvs3[$iss]->last_sem_class3_2  != null){

                               if ($datalabsem32 < $semdatalabvs3[$iss]->last_sem_class3_2) {
                                   $sem32highervs3++;
                                   $datalabsem32 = $semdatalabvs3[$iss]->last_sem_class3_2;
                               } 
                               else if ($datalabsem32 > $semdatalabvs3[$iss]->last_sem_class3_2) {
                                   $sem32belowvs3++;
                                   $datalabsem32 = $semdatalabvs3[$iss]->last_sem_class3_2;
                               } 
                               else {
                                   $sem32highervs3--;
                                   $sem32belowvs3--;
                               }

                           }else{

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
                           if($item->last_sem_class3_3  != null){
                               if ($datafirst->class3_3 < $item->last_sem_class3_3) {
                                   $sem33higher++;
                                   
                               }else if ($datafirst->class3_3 > $item->last_sem_class3_3) {
                                   $sem33below++;
                               
                               }else {
                                   $sem33higher--;
                                   $sem33below--;
                               }
                           }else{
                               if ($datafirst->class3_3 < $item->sem_class3_3) {
                                   $sem33higher++;
                                   
                               }else if ($datafirst->class3_3 > $item->sem_class3_3) {
                                   $sem33below++;
                               
                               }else {
                                   $sem33higher--;
                                   $sem33below--;
                               }
                           }   
                       }
                   }

                   $sem33highervs3 = 0;
                   $sem33belowvs3 = 0;
                   if($countsortdatalab >= $alarmtest3y){
                       if($semdatalabvs3[0]->last_sem_class3_3  != null){
                           $datalabsem33 = $semdatalabvs3[0]->last_sem_class3_3;
                       }else{
                           $datalabsem33 = $semdatalabvs3[0]->sem_class3_3;
                       }
                       $iss = 0;
                       foreach ( $semdatalabvs3 as $item){ 
                       $iss++;
                       if( $iss <= $alarmtest3)
                       {
                           if($semdatalabvs3[$iss]->last_sem_class3_3  != null){
                               if ($datalabsem33 < $semdatalabvs3[$iss]->last_sem_class3_3) {
                                   $sem33highervs3++;
                                   $datalabsem33 = $semdatalabvs3[$iss]->last_sem_class3_3;
                               } 
                               else if ($datalabsem33 > $semdatalabvs3[$iss]->last_sem_class3_3) {
                                   $sem33belowvs3++;
                                   $datalabsem33 = $semdatalabvs3[$iss]->last_sem_class3_3;
                               } 
                               else {
                                   $sem33highervs3--;
                                   $sem33belowvs3--;
                               }
                           }else{
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
                               if($item->last_sem_class4  != null){
                                   if ($datafirst->class4 < $item->last_sem_class4) {
                                       $sem4higher++;
                                       
                                   } 
                                   else if ($datafirst->class4 > $item->last_sem_class4) {
                                       $sem4below++;
                                   
                                   } 
                                   else {
                                       $sem4higher--;
                                       $sem4below--;
                                   }
                               }else{
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
                       }


                   $sem4highervs3 = 0;
                   $sem4belowvs3 = 0;
                   if($countsortdatalab >= $alarmtest3y){
                       if($semdatalabvs3[0]->last_sem_class4  != null){
                           $datalabsem4 = $semdatalabvs3[0]->last_sem_class4;
                       }else{
                           $datalabsem4 = $semdatalabvs3[0]->sem_class4;
                       }

                       $iss = 0;
                       foreach ( $semdatalabvs3 as $item){ 
                           $iss++;
                           if( $iss <= $alarmtest3)
                           {
                               if($semdatalabvs3[$iss]->last_sem_class4  != null){
                                   if ($datalabsem4 < $semdatalabvs3[$iss]->last_sem_class4) {
                                       $sem4highervs3++;
                                       $datalabsem4 = $semdatalabvs3[$iss]->last_sem_class4;
                                   } 
                                   else if ($datalabsem4 > $semdatalabvs3[$iss]->last_sem_class4) {
                                       $sem4belowvs3++;
                                       $datalabsem4 = $semdatalabvs3[$iss]->last_sem_class4;
                                   } 
                                   else {
                                       $sem4highervs3--;
                                       $sem4belowvs3--;
                                   }
                               }else{
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
                               if($item->last_sem_class5  != null){
                                   if ($datafirst->class5 < $item->last_sem_class5) {
                                       $sem5higher++;
                                       
                                   } 
                                   else if ($datafirst->class5 > $item->last_sem_class5) {
                                       $sem5below++;
                                   
                                   } 
                                   else {
                                       $sem5higher--;
                                       $sem5below--;
                                   }
                               }else{
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
                       }
       

                   $sem5highervs3 = 0;
                   $sem5belowvs3 = 0;
                   if($countsortdatalab >= $alarmtest3y){
                   if($semdatalabvs3[0]->last_sem_class5  != null){
                       $datalabsem5 = $semdatalabvs3[0]->last_sem_class5;
                   }else{
                       $datalabsem5 = $semdatalabvs3[0]->sem_class5;
                   }
                   $iss = 0;
                   foreach ( $semdatalabvs3 as $item){ 
                       $iss++;
                       if( $iss <= $alarmtest3)
                       {
                           if($semdatalabvs3[$iss]->last_sem_class5  != null){
                           
                               if ($datalabsem5 < $semdatalabvs3[$iss]->last_sem_class5) {
                                   $sem5highervs3++;
                                   $datalabsem5 = $semdatalabvs3[$iss]->last_sem_class5;
                               } 
                               else if ($datalabsem5 > $semdatalabvs3[$iss]->last_sem_class5) {
                                   $sem5belowvs3++;
                                   $datalabsem5 = $semdatalabvs3[$iss]->last_sem_class5;
                               } 
                               else {
                                       $sem5highervs3--;
                                       $sem5belowvs3--;
                               }

                           }else{

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
                               if($item->last_sem_class5_1  != null){
                                   if ($datafirst->class5_1 < $item->last_sem_class5_1) {
                                       $sem51higher++;
                                       
                                   } 
                                   else if ($datafirst->class5_1 > $item->last_sem_class5_1) {
                                       $sem51below++;
                                   
                                   } 
                                   else {
                                       $sem51higher--;
                                       $sem51below--;
                                   }
                               }else{
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
                       }
           

                   $sem51highervs3 = 0;
                   $sem51belowvs3 = 0;
                   if($countsortdatalab >= $alarmtest3y){
                       if($semdatalabvs3[0]->last_sem_class5_1  != null){
                           $datalabsem51 = $semdatalabvs3[0]->last_sem_class5_1;
                       }else{
                           $datalabsem51 = $semdatalabvs3[0]->sem_class5_1;
                       }
                       $iss = 0;
                       foreach ( $semdatalabvs3 as $item){ 
                           $iss++;
                           if( $iss <= $alarmtest3)
                           {
                               if($semdatalabvs3[$iss]->last_sem_class5_1 != null){
                                   if ($datalabsem51 < $semdatalabvs3[$iss]->last_sem_class5_1) {
                                       $sem51highervs3++;
                                       $datalabsem51 = $semdatalabvs3[$iss]->last_sem_class5_1;
                                   } 
                                   else if ($datalabsem51 > $semdatalabvs3[$iss]->last_sem_class5_1) {
                                       $sem51belowvs3++;
                                       $datalabsem51 = $semdatalabvs3[$iss]->last_sem_class5_1;
                                   } 
                                   else {
                                       $sem51highervs3--;
                                       $sem51belowvs3--;
                                   }
                               }else{
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
                                   if($item->last_sem_class6  != null){
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
                           }
       
                       $sem6highervs3 = 0;
                       $sem6belowvs3 = 0;
                       if($countsortdatalab >= $alarmtest3y){
                           if($semdatalabvs3[0]->last_sem_class6  != null){
                               $datalabsem6 = $semdatalabvs3[0]->last_sem_class6;
                           }else{
                               $datalabsem6 = $semdatalabvs3[0]->sem_class6;
                           }
                           $iss = 0;
                           foreach ( $semdatalabvs3 as $item){ 
                               $iss++;
                               if( $iss <= $alarmtest3)
                               {
                                   if($semdatalabvs3[$iss]->last_sem_class6  != null){
                                       if ($datalabsem6 < $semdatalabvs3[$iss]->last_sem_class6) {
                                           $sem6highervs3++;
                                           $datalabsem6 = $semdatalabvs3[$iss]->last_sem_class6;
                                       } 
                                       else if ($datalabsem6 > $semdatalabvs3[$iss]->last_sem_class6) {
                                           $sem6belowvs3++;
                                           $datalabsem6 = $semdatalabvs3[$iss]->last_sem_class6;
                                       } 
                                       else {
                                           $sem6highervs3--;
                                           $sem6belowvs3--;
                                       }
                                   }else{
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
                       }
                       $chksemshow = 1;
                       //
                   
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
                   $icdatalab = DB::table('nt_data_ic')
                   ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ic.id_createlab')
                   ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                   ->where('nt_oqa_lab.save_date','<=',$dateend)
                   ->where('nt_oqa_lab.id_model',$request->input('id'))
                   ->where('nt_oqa_lab.ic_confirm_st',1)
                   ->limit($alarmtest2)->orderBy('nt_oqa_lab.save_date', 'desc')->get();

                   $icdatalaball = DB::table('nt_data_ic')
                   ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ic.id_createlab')
                   ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                   ->where('nt_oqa_lab.save_date','<=',$dateend)
                   ->where('nt_oqa_lab.id_model',$request->input('id'))
                   ->where('nt_oqa_lab.ic_confirm_st',1);
                   if($request->chkpdf == 0 || $request->chkpdf == 2)
                    {
                        $oqasum_ic_sum = intval($oqasum->ic_sum);
                        if($oqasum_ic_sum == 0){
                            $oqasum_ic_sum = 1;
                        }
                        // $icdatalaball =  $icdatalaball->limit(intval($oqasum->ic_sum))->orderBy('nt_oqa_lab.save_date', 'desc')->get();
                        $icdatalaball =  $icdatalaball->limit($oqasum_ic_sum)->orderBy('nt_oqa_lab.save_date', 'desc')->get();
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
                   $icdatalabvs3 = DB::table('nt_data_ic')
                   ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ic.id_createlab')
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
               $gcmsdatalab = DB::table('nt_data_gcms')
               ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
               ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
               ->where('nt_oqa_lab.save_date','<=',$dateend)
               ->where('nt_oqa_lab.id_model',$request->input('id'))
               ->where('nt_oqa_lab.gcms_confirm_st',1)
               ->limit($alarmtest2)->orderBy('nt_oqa_lab.save_date', 'desc')->get();
               $gcmsdatalaball = DB::table('nt_data_gcms')
               ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
               ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
               ->where('nt_oqa_lab.save_date','<=',$dateend)
               ->where('nt_oqa_lab.id_model',$request->input('id'))
               ->where('nt_oqa_lab.gcms_confirm_st',1);
               if($request->chkpdf == 0 || $request->chkpdf == 2)
               {
                    $oqasum_gcms_sum = intval($oqasum->gcms_sum);
                    if($oqasum_gcms_sum == 0){
                        $oqasum_gcms_sum = 1;
                    }
                   //$datalaball = $datalaball->limit(intval($oqasum->alpc_sum))->orderBy('nt_oqa_lab.save_date', 'desc')->get();
                   //$gcmsdatalaball =  $gcmsdatalaball->limit(intval($oqasum->gcms_sum))->orderBy('nt_oqa_lab.save_date', 'desc')->get();
                   $gcmsdatalaball =  $gcmsdatalaball->limit($oqasum_gcms_sum)->orderBy('nt_oqa_lab.save_date', 'desc')->get();
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
               
               $gcmsdatalabvs3 = DB::table('nt_data_gcms')
               ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
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
               //dd($gcmsdff->count());
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
           
               if($gcmsslope->count() < 11 && $gcmsslope->count() != 0)
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
               
                   // dd($gcmsdatalabvs3);
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
           // dd($datalablgcms.' < '.$gcmsdatalabvs3[$iss]->gcms_outgassing);
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
                   $dateend = date('Y-m-d 00:00:00', strtotime($request->dateend. ' + 1 day'));;
                   } 
               $ftirdatalab = DB::table('nt_data_ftir')
               ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ftir.id_createlab')
               ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
               ->where('nt_oqa_lab.save_date','<=',$dateend)
               ->where('nt_oqa_lab.id_model',$request->input('id'))
               ->where('nt_oqa_lab.ftir_confirm_st',1)
               ->limit($alarmtest2)->orderBy('nt_oqa_lab.save_date', 'desc')->get();

               $ftirdatalaball = DB::table('nt_data_ftir')
               ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ftir.id_createlab')
               ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
               ->where('nt_oqa_lab.save_date','<=',$dateend)
               ->where('nt_oqa_lab.id_model',$request->input('id'))
               ->where('nt_oqa_lab.ftir_confirm_st',1);
               if($request->chkpdf == 0 || $request->chkpdf == 2)
               {
                    // //$datalaball = $datalaball->limit(intval($oqasum->alpc_sum))->orderBy('nt_oqa_lab.save_date', 'desc')->get();
                    $oqasum_ftir_sum = intval($oqasum->ftir_sum);
                    if($oqasum_ftir_sum == 0){
                        $oqasum_ftir_sum = 1;
                    }
                //    $ftirdatalaball =  $ftirdatalaball->limit(intval($oqasum->ftir_sum))->orderBy('nt_oqa_lab.save_date', 'desc')->get();
                   $ftirdatalaball =  $ftirdatalaball->limit($oqasum_ftir_sum)->orderBy('nt_oqa_lab.save_date', 'desc')->get();
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
               $ftirdatalabvs3 = DB::table('nt_data_ftir')
               ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ftir.id_createlab')
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


               if($ftirslope->count() < 11 && $ftirslope->count() != 0)
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

               //--------------- check ratio send email *** ---------------
                $checkratio = new ResultsControlle();
                // $val_checkratio =  $checkratio->chkconfirmRatio($request->input('idcreatelab'));
                // $reviseTEST7 = $this->newoutputgraphratio5($request->input('id'),$request->input('idcreatelab'));
               
                $slopediff_sem3 = $this->slopediff_sem($request->input('idcreatelab'),3);
                $slopediff_sem6 = $this->slopediff_sem($request->input('idcreatelab'),6);
                $slopediff_sem5 = $this->slopediff_sem($request->input('idcreatelab'),5);
                $slopediff_sem4 = $this->slopediff_sem($request->input('idcreatelab'),4);
                $slopediff_sem3_1 = $this->slopediff_sem($request->input('idcreatelab'),3.1);
                $slopediff_Outgass = $this->slopediff_sem($request->input('idcreatelab'),"Outgass"); 
                $slopediff_lpc = $this->slopediff_lpc($request->input('idcreatelab')); 

                $amlfile = DB::table('nt_oqa_aml_file')->where('id_createlab',$request->input('idcreatelab'))->first();

                $oqalot = DB::table('nt_oqa_lab')
                ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                ->where('nt_oqa_lab.id_createlab',$request->input('idcreatelab'))
                ->first();

                return response()->json([
                //    'reviseTEST7' => $reviseTEST7,
                //    'alarmtest7' => $val_checkratio, 
                   'success' => 1,
                   'slopediff_sem3_SLOPE' => @$slopediff_sem3['SLOPE'],
                   'slopediff_sem3_DIFF' =>  @$slopediff_sem3['DIFF'],
                   'slopediff_sem5_SLOPE' => @$slopediff_sem5['SLOPE'], 
                   'slopediff_sem5_DIFF' => @$slopediff_sem5['DIFF'],
                   'slopediff_sem6_SLOPE' => @$slopediff_sem6['SLOPE'], 
                   'slopediff_sem6_DIFF' => @$slopediff_sem6['DIFF'],
                   'slopediff_sem4_SLOPE' => @$slopediff_sem4['SLOPE'],
                   'slopediff_sem4_DIFF' =>  @$slopediff_sem4['DIFF'],
                   'slopediff_sem3_1_SLOPE' => @$slopediff_sem3_1['SLOPE'],
                   'slopediff_Outgass_DIFF' =>  @$slopediff_Outgass['DIFF'],
                   'slopediff_Outgass_SLOPE' =>  @$slopediff_Outgass['SLOPE'],
                   'slopediff_lpc_SLOPE' =>  @$slopediff_lpc['SLOPE'],
                   'slopediff_lpc_DIFF' =>  @$slopediff_lpc['DIFF'],
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
                   'testesttest3' => $dataalarm->test3,
                   
               ]);
               
    } 

    public function ghostfileexcelall(Request $request)
    { 
        $oqadatesemtest = DB::table('nt_data_semtest')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_semtest.id_createlab')
        ->join('nt_model','nt_oqa_lab.id_model','=','nt_model.id_model')
        ->where('nt_oqa_lab.type_lab','LIKE','SGT_%')
        ->where('nt_data_semtest.id_createlab',$request->id)->first();
       // dd($oqadatesemtest);
        $filetime = date('YmdHis');
        $namefile = 'Ghost_'.$oqadatesemtest->name_model.'_'.$oqadatesemtest->lotnumber_lab.'_'.$filetime;
        $file = storage_path('NHK-File/template/report/ghosttest_report.xlsx');
        //dd(0);
        //$file = storage_path('NHK-File/template/report/Template_ccpfile.xlsm');
        Excel::load($file, function ($doc)use($oqadatesemtest) {
          //  dd(1);
            $sheet = $doc->setActiveSheetIndex(0);
            if($oqadatesemtest->semtest_data == 1 || $oqadatesemtest->semtest_data == 1.0)
            {
                $text = 'Not detected';
            }
            else
            {
                $text = 'Detect';
            }

            $sheet->setCellValue('A3',$oqadatesemtest->name_model);
            $sheet->setCellValue('B3',$oqadatesemtest->part);
            $sheet->setCellValue('C3',$oqadatesemtest->lotnumber_lab);
            $sheet->setCellValue('D3',$text);
            $sheet->setCellValue('E3',$oqadatesemtest->alkane);
            $sheet->setCellValue('F3',$oqadatesemtest->rhc);
            $sheet->setCellValue('G3',$oqadatesemtest->unknowns);
            $sheet->setCellValue('H3',$oqadatesemtest->low_mw);
            $sheet->setCellValue('I3',$oqadatesemtest->high_mw);
            $sheet->setCellValue('J3',$oqadatesemtest->dop1);
            $sheet->setCellValue('K3',$oqadatesemtest->dop2);
            $sheet->setCellValue('L3',$oqadatesemtest->total);
         
     

        })->setFilename($namefile)->store('xlsx', storage_path('NHK-File/report/ghostexport'));
        return response()->json(['success' =>  1, 'filename' => $namefile.'.xlsx']);
    }

    public function outputgraphratio(Request $request)	
    {

        function STDEV($my_arr)
        {
            $no_element = count($my_arr);
            $var = 0.0;
            $avg = array_sum($my_arr)/$no_element;
            foreach($my_arr as $i)
            {
                $var += pow(($i - $avg), 2);
            }
            return (float)sqrt($var/$no_element);
        }

        $graphdata = [];
        $graphdata2 = [];

        $id = $request->id;
        $id_createlab = $request->id_createlab;
        $a1 = DB::table('nt_model')->where('id_model', $id)->first();
        
        $graphdata['dataecorrelated'] = $a1->sem_dc;
        $graphdata['revision'] = $a1->sem_dr;
        $graphdata['partnumber'] = $a1->part_number;
        $graphdata['drivename'] = $a1->drive_name;
        $graphdata['modal'] = $a1->name_model;
        $graphdata['part'] = $a1->part;

        if($a1->part == "UP")
        {
            $datalabgcms = DB::table('nt_datacf_gcms')
            ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_datacf_gcms.id_createlab')
            ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
            ->leftjoin('nt_outgassing_gcms','nt_outgassing_gcms.id_createlab','=','nt_oqa_lab.id_createlab')
            ->where('nt_oqa_lab.id_model',$a1->id_model)
            // ->where('nt_oqa_lab.id_createlab', $id_createlab)
            ->where('nt_oqa_lab.gcms_confirm_st',1)
            ->where('nt_oqa_lab.type_lab','LIKE','SGT_%')
            ->orderBy('nt_oqa_lab.save_date', 'asc')->skip(0)->take(31)->get();
        }
        else
        {
            $datalabgcms = DB::table('nt_datacf_gcms')
            ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_datacf_gcms.id_createlab')
            ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
            ->leftjoin('nt_outgassing_gcms','nt_outgassing_gcms.id_createlab','=','nt_oqa_lab.id_createlab')
            ->where('nt_oqa_lab.id_model',$a1->id_model)
            // ->where('nt_oqa_lab.id_createlab', $id_createlab)
            ->where('nt_oqa_lab.gcms_confirm_st',1)
            ->where('nt_oqa_lab.type_lab','LIKE','SGT_%')
            ->orderBy('nt_oqa_lab.save_date', 'asc')->skip(0)->take(31)->get();
        }

        $p = 1;
        $lastww_ele = 0;
        $lastww_acid = 0;
        foreach ($datalabgcms as $value) {

            $date = new DateTime($value->save_date);
            $week = $date->format("W");

            $graphdata['ww'][] = 'WW'.$week;
            $graphdata['dd'][] =  $value->save_date;
            $graphdata['elemental'][] = intval(floatval($value->c1)+floatval($value->c2)+floatval($value->c3)+floatval($value->c4)+floatval($value->c8)+floatval($value->c9)+floatval($value->c10)+floatval($value->c16)+floatval($value->c17)+floatval($value->c28));
            $graphdata['acid'][] = intval(floatval($value->c5)+floatval($value->c6)+floatval($value->c12)+floatval($value->c13));
            $graphdata['oxygen'][] = intval(floatval($value->c6_1)+floatval($value->c18)+floatval($value->c21)+floatval($value->c22)+floatval($value->c23)+floatval($value->c24)+floatval($value->c29));
            $graphdata['aromatic'][] = intval(floatval($value->c11)+floatval($value->c14)+floatval($value->c15)+floatval($value->c18)+floatval($value->c20)+floatval($value->c25)+floatval($value->c26));
            $graphdata['aliphatic'][] = intval($value->c27);
            $graphdata['cn20'][] = intval($value->c34);
            $graphdata['cm20'][] = intval($value->c35);

            $graphdata['smear'][] = intval(floatval($value->c5)+floatval($value->c9)+floatval($value->c16)+floatval($value->c17)+floatval($value->c26)+floatval($value->c35));
            $graphdata['disck'][] = intval(floatval($value->c2)+floatval($value->c3)+floatval($value->c8)+floatval($value->c9)+floatval($value->c12)+floatval($value->c19)+floatval($value->c20)+floatval($value->c27)+floatval($value->c32)+floatval($value->c33));

            $graphdata['upper'][] = 3;
            $graphdata['lower'][] = -3;

            $lastww_ele = intval(floatval($value->c1)+floatval($value->c2)+floatval($value->c3)+floatval($value->c4)+floatval($value->c8)+floatval($value->c9)+floatval($value->c10)+floatval($value->c16)+floatval($value->c17)+floatval($value->c28));
            $lastww_acid = intval(floatval($value->c5)+floatval($value->c6)+floatval($value->c12)+floatval($value->c13));
            $lastww_oxygen = intval(floatval($value->c6_1)+floatval($value->c18)+floatval($value->c21)+floatval($value->c22)+floatval($value->c23)+floatval($value->c24)+floatval($value->c29));
            $lastww_aromatic = intval(floatval($value->c11)+floatval($value->c14)+floatval($value->c15)+floatval($value->c18)+floatval($value->c20)+floatval($value->c25)+floatval($value->c26)); 
            $lastww_aliphatic = intval($value->c27);
            $lastww_cn20 = intval($value->c34);
            $lastww_cm20 = intval($value->c35);
            $lastww_smear = intval(floatval($value->c5)+floatval($value->c9)+floatval($value->c16)+floatval($value->c17)+floatval($value->c26)+floatval($value->c35));
            $lastww_disck = intval(floatval($value->c2)+floatval($value->c3)+floatval($value->c8)+floatval($value->c9)+floatval($value->c12)+floatval($value->c19)+floatval($value->c20)+floatval($value->c27)+floatval($value->c32)+floatval($value->c33));


            $p++;
        }

        // -------- Calculate -------- 
        // Elemental (P, S, Si, N)
        $array1 = $graphdata['elemental'];
        sort($array1);
        $count = sizeof($array1);
        $index = floor($count/2);
        if (!$count) {
            $Line76 = 0;
        } elseif ($count & 1) {    // count is odd
            $Line76 = $array1[$index];
        } else {                   // count is even
            $Line76 = ($array1[$index-1] + $array1[$index]) / 2;
        }
        $Line87 = STDEV($array1);
        $Line98 = $lastww_ele-$Line76;
        $final_ele = $Line98/$Line87;
        $graphdata2['final_ele'] = number_format($final_ele,2);

        // Acid/Acrylate
        $array2 = $graphdata['acid'];
        sort($array2);
        $count2 = sizeof($array2);
        $index2 = floor($count2/2);
        if (!$count2) {
            $Line77 = 0;
        } elseif ($count2 & 1) {    // count is odd
            $Line77 = $array2[$index2];
        } else {                   // count is even
            $Line77 = ($array2[$index2-1] + $array2[$index2]) / 2;
        }
        $Line88 = STDEV($array2);
        $Line99 = $lastww_acid-$Line77;
        $final_acid = $Line99/$Line88;
        $graphdata2['final_acid'] = number_format($final_acid,2);

        // Oxygen Containing
        $array3 = $graphdata['oxygen'];
        sort($array3);
        $count3 = sizeof($array3);
        $index3 = floor($count3/2);
        if (!$count3) {
            $Line78 = 0;
        } elseif ($count3 & 1) {    // count is odd
            $Line78 = $array3[$index3];
        } else {                   // count is even
            $Line78 = ($array3[$index3-1] + $array3[$index3]) / 2;
        }
        $Line89 = STDEV($array3);
        $Line100 = $lastww_oxygen-$Line78;
        $final_oxygen = $Line100/$Line89;
        $graphdata2['final_oxygen'] = number_format($final_oxygen,2);

        // Aromatic
        $array4 = $graphdata['aromatic'];
        sort($array4);
        $count4 = sizeof($array4);
        $index4 = floor($count4/2);
        if (!$count4) {
            $Line79 = 0;
        } elseif ($count4 & 1) {    // count is odd
            $Line79 = $array4[$index4];
        } else {                   // count is even
            $Line79 = ($array4[$index4-1] + $array4[$index4]) / 2;
        }
        $Line90 = STDEV($array4);
        $Line101 = $lastww_aromatic-$Line79;
        $final_aromatic = $Line101/$Line90;
        $graphdata2['final_aromatic'] = number_format($final_aromatic,2);

        // Aliphatic
        $array5 = $graphdata['aliphatic'];
        sort($array5);
        $count5 = sizeof($array5);
        $index5 = floor($count5/2);
        if (!$count5) {
            $Line80 = 0;
        } elseif ($count5 & 1) {    // count is odd
            $Line80 = $array5[$index5];
        } else {                   // count is even
            $Line80 = ($array5[$index5-1] + $array5[$index5]) / 2;
        }
        $Line91 = STDEV($array5);
        $Line102 = $lastww_aliphatic-$Line80;
        $final_aliphatic = $Line102/$Line91;
        $graphdata2['final_aliphatic'] = number_format($final_aliphatic,2);

        // Total Low Molecular Weight Compound (C < 20)
        $array6 = $graphdata['cn20'];
        sort($array6);
        $count6 = sizeof($array6);
        $index6 = floor($count6/2);
        if (!$count6) {
            $Line81 = 0;
        } elseif ($count6 & 1) {    // count is odd
            $Line81 = $array6[$index6];
        } else {                   // count is even
            $Line81 = ($array6[$index6-1] + $array6[$index6]) / 2;
        }
        $Line92 = STDEV($array6);
        $Line103 = $lastww_cn20-$Line81;
        $final_cn20 = $Line103/$Line92;
        $graphdata2['final_cn20'] = number_format($final_cn20,2);

        // Total Low Molecular Weight Compound (C > 20)
        $array7 = $graphdata['cm20'];
        sort($array7);
        $count7 = sizeof($array7);
        $index7 = floor($count7/2);
        if (!$count7) {
            $Line82 = 0;
        } elseif ($count7 & 1) {    // count is odd
            $Line82 = $array7[$index7];
        } else {                   // count is even
            $Line82 = ($array7[$index7-1] + $array7[$index7]) / 2;
        }
        $Line93 = STDEV($array7);
        $Line104 = $lastww_cm20-$Line82;
        $final_cm20 = $Line104/$Line93;
        $graphdata2['final_cm20'] = number_format($final_cm20,2);        

        // Smear Formers
        $array8 = $graphdata['smear'];
        sort($array8);
        $count8 = sizeof($array8);
        $index8 = floor($count8/2);
        if (!$count8) {
            $Line83 = 0;
        } elseif ($count8 & 1) {    // count is odd
            $Line83 = $array8[$index8];
        } else {                   // count is even
            $Line83 = ($array8[$index8-1] + $array8[$index8]) / 2;
        }
        $Line94 = STDEV($array8);
        $Line105 = $lastww_smear-$Line83;
        $final_smear = $Line105/$Line94;
        $graphdata2['final_smear'] = number_format($final_smear,2);  

        // Disk Detectable
        $array9 = $graphdata['disck'];
        sort($array9);
        $count9 = sizeof($array9);
        $index9 = floor($count9/2);
        if (!$count9) {
            $Line84 = 0;
        } elseif ($count9 & 1) {    // count is odd
            $Line84 = $array9[$index9];
        } else {                   // count is even
            $Line84 = ($array9[$index9-1] + $array9[$index9]) / 2;
        }
        $Line95 = STDEV($array9);
        $Line106 = $lastww_disck-$Line84;
        $final_disck = $Line106/$Line95;
        $graphdata2['final_disck'] = number_format($final_disck,2); 

        // Health Score Percentile
        $graphdata2['percentile'] = 0.00; 


        return response()->json([
            'success' => 1,
            'datalab' =>$graphdata,
            'datalab2' =>$graphdata2 
        ]);

    }

    public function newccpfileexcel(Request $request)
    {

        function getLPC($id, $line){
            $getreport = DB::table('nt_oqa_file')->where('id_createlab',$id)->first();
            $readfilepc =  storage_path('app/public/OQA/LPC/'.$getreport->file_lpc);
            if($getreport->file_lpc){
                
                $excel = [];
                Excel::load($readfilepc, function($reader) use (&$excel) {
                    $objExcel = $reader->getExcel();
                    $sheet = $objExcel->getSheet(0);
                    $highestRow = $sheet->getHighestRow();
                    $highestColumn = $sheet->getHighestColumn();
                    for ($row = 1; $row <= $highestRow; $row++)
                    {
                        $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
                            NULL, TRUE, FALSE);
                        $excel[] = $rowData[0];
                    }
                });
                $lpcO19 = $excel[18][14];
                $lpcI23 = $excel[22][8];
                $lpcI25 = $excel[24][8];

                if($line == 'I25'){
                    return $lpcI25;
                }elseif($line == 'I23'){
                    return $lpcI23;
                }elseif($line == 'O19'){
                    return $lpcO19;
                }

            }else{
                return 0;
            }
        } 
 
        // $request->id = 10355;

        $oqadate = DB::table('nt_data_lpc')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_lpc.id_createlab')
        ->where('nt_data_lpc.id_createlab',$request->id)
        ->where('nt_oqa_lab.type_lab','LIKE','SGT_%')
        ->where('nt_data_lpc.lpc_st',1)
        ->first();
        $oqasum = DB::table('nt_model')
        ->where('id_model',$oqadate->id_model)
        ->first(); 
        $datalaball = DB::table('nt_data_lpc')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_lpc.id_createlab')
        ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
        ->join('nt_model','nt_model.id_model','=','nt_oqa_lab.id_model')
        ->where('nt_data_lpc.id_createlab',$request->id)
        ->where('nt_oqa_lab.lpc_confirm_st',1)
        ->where('nt_oqa_lab.type_lab','LIKE','SGT_%')
        ->limit(1)->orderBy('nt_oqa_lab.save_date', 'desc')->get();
        
        if($datalaball){

            $filetime = date('YmdHis');
            $namefile = 'test_CCP_'.$datalaball[0]->name_model.'_'.$datalaball[0]->lotnumber_lab.'_'.$filetime;
            // $namefile = 'CCP_'.$datalaball[0]->name_model.'_'.$datalaball[0]->lotnumber_lab.'_'.$filetime;
            $dataexport = 1;
            $file = storage_path('NHK-File/template/report/new_template_ccpfile.xlsx');

            Excel::load($file, function ($doc)use($dataexport,$datalaball) {
                $sheet = $doc->setActiveSheetIndex(1);

                $x = 1;
                $i = 13;
                $row = 0;
                foreach ($datalaball as $item) {
                    $row++;

                    $ftime = date('n/j/Y H:i:s',strtotime($item->ftime_lpc));
                    $startime = date('n/j/Y H:i:s',strtotime($item->lpc_startime));
                    
                    $sheet->setCellValue('B'.$i, $x);

                    $sheet->setCellValue('C'.$i, $item->vendor_id);

                    $sheet->setCellValue('D'.$i, $item->sub_supplier_name);

                    $sheet->setCellValue('E'.$i, $item->part_number);

                    $sheet->setCellValue('F'.$i, $item->part_revision);

                    $sheet->setCellValue('G'.$i, $item->drawing_number);

                    $sheet->setCellValue('H'.$i, $item->drawing_revision);

                    $sheet->setCellValue('I'.$i, $startime);

                    $sheet->setCellValue('K'.$i, $item->lotnumber_lab);

                    $sheet->setCellValue('L'.$i, $ftime);

                    $sheet->setCellValue('M'.$i, $row);

                    // $sheet->setCellValue('N'.$i, $item->ic_parts_used);

                    // $sheet->setCellValue('O'.$i, $item->surface_area);

                    // $sheet->setCellValue('P'.$i, getIC($item->id_createlab, 'I25'));

                    // $sheet->setCellValue('Q'.$i, getIC($item->id_createlab, 'I23'));

                    // $sheet->setCellValue('R'.$i, $item->ftir_parts_used);

                    // $sheet->setCellValue('S'.$i, $item->surface_area);

                    // $sheet->setCellValue('T'.$i, getFTIR($item->id_createlab, 'B42'));
                    
                    $sheet->setCellValue('U'.$i, $item->alpc_parts_used);

                    $sheet->setCellValue('V'.$i, $item->surface_area);
                    
                    $sheet->setCellValue('W'.$i, number_format(getLPC($item->id_createlab, 'O19'), 0,'.',''));

                    // $sheet->setCellValue('X'.$i, $item->alpc_parts_used);

                    // $sheet->setCellValue('Y'.$i, $item->sem_parts_used);

                    // $sheet->setCellValue('Z'.$i, getSEM($item->id_createlab, 'P6'));

                    // $sheet->setCellValue('AA'.$i, getSEM($item->id_createlab, 'P9'));

                    // $sheet->setCellValue('AB'.$i, getSEM($item->id_createlab, 'P10'));

                    // $sheet->setCellValue('AC'.$i, getSEM($item->id_createlab, 'P10'));

                    // $sheet->setCellValue('AD'.$i, getSEM($item->id_createlab, 'P11'));

                    // $sheet->setCellValue('AE'.$i, getSEM($item->id_createlab, 'P12'));

                    // $sheet->setCellValue('AF'.$i, getSEM($item->id_createlab, 'P13'));

                    // $sheet->setCellValue('AG'.$i, getSEM($item->id_createlab, 'P14'));

                    // $sheet->setCellValue('AH'.$i, $item->gcms_parts_used);
                    
                    // $sheet->setCellValue('AI'.$i, getGCMS($item->id_createlab, 'E17'));

                    // $sheet->setCellValue('AJ'.$i, getGCMS($item->id_createlab, 'E18'));

                    // $sheet->setCellValue('AK'.$i, getGCMS($item->id_createlab, 'E19'));

                    // $sheet->setCellValue('AL'.$i, getGCMS($item->id_createlab, 'E20'));

                    // $sheet->setCellValue('AM'.$i, getGCMS($item->id_createlab, 'E21'));

                    // $sheet->setCellValue('AN'.$i, getGCMS($item->id_createlab, 'E22'));

                    // $sheet->setCellValue('AO'.$i, getGCMS($item->id_createlab, 'E23'));

                    // $sheet->setCellValue('AP'.$i, getGCMS($item->id_createlab, 'E24'));

                    // $sheet->setCellValue('AQ'.$i, getGCMS($item->id_createlab, 'E25'));

                    // $sheet->setCellValue('AR'.$i, getGCMS($item->id_createlab, 'E26'));

                    // $sheet->setCellValue('AS'.$i, getGCMS($item->id_createlab, 'E27'));

                    // $sheet->setCellValue('AT'.$i, getGCMS($item->id_createlab, 'E28'));

                    // $sheet->setCellValue('AU'.$i, getGCMS($item->id_createlab, 'E29'));

                    // $sheet->setCellValue('AV'.$i, getGCMS($item->id_createlab, 'E30'));

                    // $sheet->setCellValue('AW'.$i, getGCMS($item->id_createlab, 'E31'));

                    // $sheet->setCellValue('AX'.$i, getGCMS($item->id_createlab, 'E32'));

                    // $sheet->setCellValue('AY'.$i, getGCMS($item->id_createlab, 'E33'));

                    // $sheet->setCellValue('AZ'.$i, getGCMS($item->id_createlab, 'E34'));

                    // $sheet->setCellValue('BA'.$i, getGCMS($item->id_createlab, 'E35'));

                    // $sheet->setCellValue('BB'.$i, getGCMS($item->id_createlab, 'E36'));

                    // $sheet->setCellValue('BC'.$i, getGCMS($item->id_createlab, '37'));

                    // $sheet->setCellValue('BD'.$i, getGCMS($item->id_createlab, 'E38'));

                    // $sheet->setCellValue('BE'.$i, getGCMS($item->id_createlab, 'E39'));

                    // $sheet->setCellValue('BF'.$i, getGCMS($item->id_createlab, 'E40'));

                    // $sheet->setCellValue('BG'.$i, getGCMS($item->id_createlab, 'E41'));

                    // $sheet->setCellValue('BH'.$i, getGCMS($item->id_createlab, 'E42'));

                    // $sheet->setCellValue('BI'.$i, getGCMS($item->id_createlab, 'E43'));

                    // $sheet->setCellValue('BJ'.$i, getGCMS($item->id_createlab, 'E44'));

                    // $sheet->setCellValue('BK'.$i, getGCMS($item->id_createlab, 'E45'));

                    // $sheet->setCellValue('BL'.$i, getGCMS($item->id_createlab, 'E46'));

                    // $sheet->setCellValue('BM'.$i, getGCMS($item->id_createlab, 'E47'));

                    // $sheet->setCellValue('BN'.$i, getGCMS($item->id_createlab, 'E48'));

                    // $sheet->setCellValue('BO'.$i, getGCMS($item->id_createlab, 'E49'));

                    // $sheet->setCellValue('BP'.$i, getGCMS($item->id_createlab, 'E50'));

                    // $sheet->setCellValue('BQ'.$i, getGCMS($item->id_createlab, 'E51'));

                    // $sheet->setCellValue('BR'.$i, getGCMS($item->id_createlab, 'E52'));

                    // $sheet->setCellValue('BS'.$i, getGCMS($item->id_createlab, 'E53'));

                    // $sheet->setCellValue('BT'.$i, getGCMS($item->id_createlab, 'E54'));

                    // $sheet->setCellValue('BU'.$i, getGCMS($item->id_createlab, 'E55'));

                    // $sheet->setCellValue('BV'.$i, getGCMS($item->id_createlab, 'E56'));

                    // $sheet->setCellValue('BW'.$i, getGCMS($item->id_createlab, 'E57'));

                    // $sheet->setCellValue('BX'.$i, getGCMS($item->id_createlab, 'E58'));

                    // $sheet->setCellValue('BY'.$i, getGCMS($item->id_createlab, 'E59'));

                    // $sheet->setCellValue('BZ'.$i, getGCMS($item->id_createlab, 'E60'));

                    // $sheet->setCellValue('CA'.$i, getGCMS($item->id_createlab, 'E61'));

                    // $sheet->setCellValue('CB'.$i, getGCMS($item->id_createlab, 'E62'));

                    // $sheet->setCellValue('CC'.$i, getGCMS($item->id_createlab, 'E63'));

                    // $sheet->setCellValue('CD'.$i, getGCMS($item->id_createlab, 'E64'));

                    // $sheet->setCellValue('CE'.$i, getGCMS($item->id_createlab, 'E65'));

                    // $sheet->setCellValue('CF'.$i, getGCMS($item->id_createlab, 'E66'));

                    // $sheet->setCellValue('CG'.$i, getGCMS($item->id_createlab, 'E67'));

                    // $sheet->setCellValue('CH'.$i, getGCMS($item->id_createlab, 'E68'));

                    // $sheet->setCellValue('CI'.$i, getGCMS($item->id_createlab, 'E69'));

                    // $sheet->setCellValue('CJ'.$i, getGCMS($item->id_createlab, 'E70'));

                    // $sheet->setCellValue('CK'.$i, getGCMS($item->id_createlab, 'E71'));

                    // $sheet->setCellValue('CL'.$i, getGCMS($item->id_createlab, 'E72'));

                    // $sheet->setCellValue('CM'.$i, getGCMS($item->id_createlab, 'E75'));

                    // $sheet->setCellValue('CN'.$i, getGCMS($item->id_createlab, 'E73'));

                    // $sheet->setCellValue('CO'.$i, getGCMS($item->id_createlab, 'E74'));

                    // $sheet->setCellValue('DB'.$i, getGHOST($item->id_createlab, 'H10'));
    
                    $i++;
                    $x++;

                }     

            })->setFilename($namefile)->store('xlsx', storage_path('NHK-File/report/ccpfile'));

            $data = [
                'lpcstatus_done' => 1,
            ];
            DB::table('nt_oqa_lab')->where('id_createlab', $request->id)->update($data);
            
            $oqafile =  DB::table('nt_oqa_file')
            ->join('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_oqa_file.id_createlab')
            ->join('nt_model', 'nt_model.id_model', '=', 'nt_oqa_lab.id_model')
            ->where('nt_oqa_file.id_createlab',$request->id)->first();

            $datamail = [
                'testter'=>"LPC",
                'model'=>$oqafile->name_model,
                'lot'=>$oqafile->lotnumber_lab,
                'date'=>date('d/m/Y H:i:s'),
            ];

            $mail = new MailController();
            $mail->mailccp($datamail); 

            return response()->json(['success' =>  1, 'filename' => $namefile.'.xlsx']);

        }else{
            return response()->json(['success' =>  2]);
        }    

    }

    public function newccpexcelsem(Request $request)
    {
 
        function getSEM($id, $line){
            $getreport = DB::table('nt_oqa_file')->where('id_createlab',$id)->first();
            $readfisem =  storage_path('app/public/OQA/SEM/'.$getreport->file_sem);
            
            if (file_exists($readfisem)) {
                
                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($readfisem);
                $sheet = $spreadsheet->setActiveSheetIndex(2);
                
                $getrow = $sheet->getCell($line)->getOldCalculatedValue();
                if(empty($getrow)){
                    $getrow = $sheet->getCell($line)->getCalculatedValue();
                }
    
                return $getrow;

            }else{
                return 0;
            }
        }
 
        $datalaball = DB::table('nt_data_sem')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
        ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
        ->join('nt_model','nt_model.id_model','=','nt_oqa_lab.id_model')
        ->where('nt_data_sem.id_createlab',$request->id)
        ->where('nt_oqa_lab.type_lab','LIKE','SGT_%')
        ->where('nt_oqa_lab.sem_confirm_st',1)->orderBy('nt_oqa_lab.save_date', 'desc')->get();
        $filetime = date('YmdHis');
        $dataexport = 1;
        $file = storage_path('NHK-File/template/report/new_template_ccpfile.xlsx');
         
        if($datalaball){

            $filetime = date('YmdHis');
            // $namefile = 'test_CCP_'.$datalaball[0]->name_model.'_'.$datalaball[0]->lotnumber_lab.'_'.$filetime;
            $namefile = 'CCP_'.$datalaball[0]->name_model.'_'.$datalaball[0]->lotnumber_lab.'_'.$filetime;
            $dataexport = 1;
            $file = storage_path('NHK-File/template/report/new_template_ccpfile.xlsx');

            Excel::load($file, function ($doc)use($dataexport,$datalaball) {
                $sheet = $doc->setActiveSheetIndex(1);

                $x = 1;
                $i = 13;
                $row = 0;
                foreach ($datalaball as $item) {
                    $row++; 

                    $ftime = date('n/j/Y H:i:s',strtotime($item->ftime_sem));
                    $startime = date('n/j/Y H:i:s',strtotime($item->sem_startime));
                    
                    $sheet->setCellValue('B'.$i, $x);

                    $sheet->setCellValue('C'.$i, $item->vendor_id);

                    $sheet->setCellValue('D'.$i, $item->sub_supplier_name);

                    $sheet->setCellValue('E'.$i, $item->part_number);

                    $sheet->setCellValue('F'.$i, $item->part_revision);

                    $sheet->setCellValue('G'.$i, $item->drawing_number);

                    $sheet->setCellValue('H'.$i, $item->drawing_revision);

                    $sheet->setCellValue('I'.$i, $startime);

                    $sheet->setCellValue('K'.$i, $item->lotnumber_lab);

                    $sheet->setCellValue('L'.$i, $ftime);

                    $sheet->setCellValue('M'.$i, $row); 

                    // $sheet->setCellValue('O'.$i, $item->surface_area); 

                    $sheet->setCellValue('X'.$i, $item->alpc_parts_used);

                    $sheet->setCellValue('Y'.$i, $item->surface_area);

                    $sheet->setCellValue('Z'.$i, ($item->last_sem_class1 !== null)? $item->last_sem_class1 : number_format(getSEM($item->id_createlab, 'P6'), 1,'.','') );

                    $sheet->setCellValue('AA'.$i, ($item->last_sem_class3 !== null)? $item->last_sem_class3 : number_format(getSEM($item->id_createlab, 'P8'), 1,'.','') ); // 3.0

                    $sheet->setCellValue('AB'.$i, ($item->last_sem_class3_1 !== null)? $item->last_sem_class3_1 : number_format(getSEM($item->id_createlab, 'P9'), 1,'.','') );  // 3.1

                    $sheet->setCellValue('AC'.$i, ($item->last_sem_class3_2 !== null)? $item->last_sem_class3_2 : number_format(getSEM($item->id_createlab, 'P10'), 1,'.','') ); // 3.2

                    $sheet->setCellValue('AD'.$i, ($item->last_sem_class3_3 !== null)? $item->last_sem_class3_3 : number_format(getSEM($item->id_createlab, 'P11'), 1,'.','') );

                    $sheet->setCellValue('AE'.$i, ($item->last_sem_class4 !== null)? $item->last_sem_class4 : number_format(getSEM($item->id_createlab, 'P12'), 1,'.','') );

                    $sheet->setCellValue('AF'.$i, ($item->last_sem_class5 !== null)? $item->last_sem_class5 : number_format(getSEM($item->id_createlab, 'P13'), 1,'.','') );

                    $sheet->setCellValue('AG'.$i, ($item->last_sem_class5_1 !== null)? $item->last_sem_class5_1 : number_format(getSEM($item->id_createlab, 'P14'), 1,'.','') );
                    
    
                    $i++;
                    $x++;

                }    

            })->setFilename($namefile)->store('xlsx', storage_path('NHK-File/report/ccpfile'));

            $data = [
                'semstatus_done' => 1,
            ];
            DB::table('nt_oqa_lab')->where('id_createlab', $request->id)->update($data);
            $oqafile =  DB::table('nt_oqa_file')
               ->join('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_oqa_file.id_createlab')
               ->join('nt_model', 'nt_model.id_model', '=', 'nt_oqa_lab.id_model')
               ->where('nt_oqa_file.id_createlab',$request->id)->first();
            
               $datamail = [
                'testter'=>"SEM",
                'model'=>$oqafile->name_model,
                'lot'=>$oqafile->lotnumber_lab,
                'date'=>date('d/m/Y H:i:s'),
            ];
            $mail = new MailController();
            $mail->mailccp($datamail);
 
            return response()->json(['success' =>  1, 'filename' => $namefile.'.xlsx']);

        }else{
            return response()->json(['success' =>  2]);
        }  

    }

    public function newccpexcelic(Request $request)
    {
        
        function getIC($id, $line){
            $getreport = DB::table('nt_oqa_file')->where('id_createlab',$id)->first();
            $readfiIC =  storage_path('app/public/OQA/IC/'.$getreport->file_ic);
           
            if (file_exists($readfiIC)) {
                
                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($readfiIC);
                $sheet = $spreadsheet->setActiveSheetIndex(0);
                
                $getrow = $sheet->getCell($line)->getOldCalculatedValue();
                if(empty($getrow)){
                    $getrow = $sheet->getCell($line)->getCalculatedValue();
                }
    
                return $getrow;    
            }else{
                return 0;
            }
        } 
        
        // $request->id = 9919;
        $datalaball = DB::table('nt_data_ic')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ic.id_createlab')
        ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
        ->join('nt_model','nt_model.id_model','=','nt_oqa_lab.id_model')
        ->where('nt_data_ic.id_createlab',$request->id)
        ->where('nt_oqa_lab.type_lab','LIKE','SGT_%')
        ->where('nt_oqa_lab.ic_confirm_st',1)->orderBy('nt_oqa_lab.save_date', 'desc')->get();
        
        $filetime = date('YmdHis');
        $dataexport = 1;
        $file = storage_path('NHK-File/template/report/new_template_ccpfile.xlsx');


        if($datalaball){

            $filetime = date('YmdHis');
            // $namefile = 'test_CCP_'.$datalaball[0]->name_model.'_'.$datalaball[0]->lotnumber_lab.'_'.$filetime;
            $namefile = 'CCP_'.$datalaball[0]->name_model.'_'.$datalaball[0]->lotnumber_lab.'_'.$filetime;
            $dataexport = 1;
            $file = storage_path('NHK-File/template/report/new_template_ccpfile.xlsx');

            Excel::load($file, function ($doc)use($dataexport,$datalaball) {
                $sheet = $doc->setActiveSheetIndex(1);

                $x = 1;
                $i = 13;
                $row = 0;
                foreach ($datalaball as $item) {
                    $row++; 

                    $ftime = date('n/j/Y H:i:s',strtotime($item->ftime_ic));
                    $startime = date('n/j/Y H:i:s',strtotime($item->ic_startime));
                    
                    $sheet->setCellValue('B'.$i, $x);

                    $sheet->setCellValue('C'.$i, $item->vendor_id);

                    $sheet->setCellValue('D'.$i, $item->sub_supplier_name);

                    $sheet->setCellValue('E'.$i, $item->part_number);

                    $sheet->setCellValue('F'.$i, $item->part_revision);

                    $sheet->setCellValue('G'.$i, $item->drawing_number);

                    $sheet->setCellValue('H'.$i, $item->drawing_revision);

                    $sheet->setCellValue('I'.$i, $startime);

                    $sheet->setCellValue('K'.$i, $item->lotnumber_lab);

                    $sheet->setCellValue('L'.$i, $ftime);

                    $sheet->setCellValue('M'.$i, $row);

                    $sheet->setCellValue('N'.$i, number_format($item->ic_parts_used));

                    $sheet->setCellValue('O'.$i, number_format($item->surface_area, 2,'.',''));

                    $sheet->setCellValue('P'.$i, number_format(getIC($item->id_createlab, 'I25'), 2,'.',''));

                    $sheet->setCellValue('Q'.$i, number_format(getIC($item->id_createlab, 'I23'), 2,'.',''));


                    $i++;
                    $x++;

                }    

            })->setFilename($namefile)->store('xlsx', storage_path('NHK-File/report/ccpfile'));

            $data = [
                'icstatus_done' => 1,
               ];
               DB::table('nt_oqa_lab')->where('id_createlab', $request->id)->update($data);
               $oqafile =  DB::table('nt_oqa_file')
               ->join('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_oqa_file.id_createlab')
               ->join('nt_model', 'nt_model.id_model', '=', 'nt_oqa_lab.id_model')
               ->where('nt_oqa_file.id_createlab',$request->id)->first();
               $datamail = [
                'testter'=>"IC",
                'model'=>$oqafile->name_model,
                'lot'=>$oqafile->lotnumber_lab,
                'date'=>date('d/m/Y H:i:s'),
            ];
            $mail = new MailController();
            $mail->mailccp($datamail);
 
            return response()->json(['success' =>  1, 'filename' => $namefile.'.xlsx']);

        }else{
            return response()->json(['success' =>  2]);
        }

    }

    public function newccpexcelgcms(Request $request)
    {
        function getGCMS($id, $line){
            $getreport = DB::table('nt_oqa_file')->where('id_createlab',$id)->first();
            $readfilegcms =  storage_path('app/public/OQA/GCMS/'.$getreport->file_gcms);
            if (file_exists($readfilegcms)) {
                
                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($readfilegcms);
                $sheet = $spreadsheet->setActiveSheetIndex(3);
                
                $getrow = $sheet->getCell($line)->getOldCalculatedValue();
                if(empty($getrow)){
                    $getrow = $sheet->getCell($line)->getCalculatedValue();
                }
    
                return $getrow;

            }else{
                return 0;
            }
        } 
        
        // $request->id = 9919;

        $datalaball = DB::table('nt_data_gcms')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
        ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
        ->join('nt_model','nt_model.id_model','=','nt_oqa_lab.id_model')
        ->leftjoin('nt_outgassing_gcms','nt_outgassing_gcms.id_createlab','=','nt_oqa_lab.id_createlab')
        ->where('nt_data_gcms.id_createlab',$request->id)
        ->where('nt_oqa_lab.type_lab','LIKE','SGT_%')
        ->where('nt_oqa_lab.gcms_confirm_st',1)->orderBy('nt_oqa_lab.save_date', 'desc')->get();
        $lot = DB::table('nt_oqa_lab')
        ->where('id_createlab',$request->id)->first();
        
        $filetime = date('YmdHis');
        $dataexport = 1;
        $file = storage_path('NHK-File/template/report/new_template_ccpfile.xlsx');

        if($datalaball){

            $filetime = date('YmdHis');
            // $namefile = 'test_CCP_'.$datalaball[0]->name_model.'_'.$datalaball[0]->lotnumber_lab.'_'.$filetime;
            $namefile = 'CCP_'.$datalaball[0]->name_model.'_'.$datalaball[0]->lotnumber_lab.'_'.$filetime;
            $dataexport = 1;
            $file = storage_path('NHK-File/template/report/new_template_ccpfile.xlsx');

            Excel::load($file, function ($doc)use($dataexport,$datalaball) {
                $sheet = $doc->setActiveSheetIndex(1);

                $x = 1;
                $i = 13;
                $row = 0;
                foreach ($datalaball as $item) {
                    $row++;

                    $ftime = date('n/j/Y H:i:s',strtotime($item->ftime_gcms));
                    $startime = date('n/j/Y H:i:s',strtotime($item->gcms_startime));
                    
                    $sheet->setCellValue('B'.$i, $x);

                    $sheet->setCellValue('C'.$i, $item->vendor_id);

                    $sheet->setCellValue('D'.$i, $item->sub_supplier_name);

                    $sheet->setCellValue('E'.$i, $item->part_number);

                    $sheet->setCellValue('F'.$i, $item->part_revision);

                    $sheet->setCellValue('G'.$i, $item->drawing_number);

                    $sheet->setCellValue('H'.$i, $item->drawing_revision);

                    $sheet->setCellValue('I'.$i, $startime);

                    $sheet->setCellValue('K'.$i, $item->lotnumber_lab);

                    $sheet->setCellValue('L'.$i, $ftime);

                    $sheet->setCellValue('M'.$i, $row);  

                    $sheet->setCellValue('AH'.$i, number_format(getGCMS($item->id_createlab, 'B4') )); 

                    $sheet->setCellValue('AI'.$i, number_format(getGCMS($item->id_createlab, 'E17') ));

                    $sheet->setCellValue('AJ'.$i, number_format(getGCMS($item->id_createlab, 'E18') ));

                    $sheet->setCellValue('AK'.$i, number_format(getGCMS($item->id_createlab, 'E19') ));

                    $sheet->setCellValue('AL'.$i, number_format(getGCMS($item->id_createlab, 'E20') ));

                    $sheet->setCellValue('AM'.$i, number_format(getGCMS($item->id_createlab, 'E21') ));

                    $sheet->setCellValue('AN'.$i, number_format(getGCMS($item->id_createlab, 'E22') ));

                    $sheet->setCellValue('AO'.$i, number_format(getGCMS($item->id_createlab, 'E23') ));

                    $sheet->setCellValue('AP'.$i, number_format(getGCMS($item->id_createlab, 'E24') ));

                    $sheet->setCellValue('AQ'.$i, number_format(getGCMS($item->id_createlab, 'E25') ));

                    $sheet->setCellValue('AR'.$i, number_format(getGCMS($item->id_createlab, 'E26') ));

                    $sheet->setCellValue('AS'.$i, number_format(getGCMS($item->id_createlab, 'E27') ));

                    $sheet->setCellValue('AT'.$i, number_format(getGCMS($item->id_createlab, 'E28') ));

                    $sheet->setCellValue('AU'.$i, number_format(getGCMS($item->id_createlab, 'E29') ));

                    $sheet->setCellValue('AV'.$i, number_format(getGCMS($item->id_createlab, 'E30') ));

                    $sheet->setCellValue('AW'.$i, number_format(getGCMS($item->id_createlab, 'E31') ));

                    $sheet->setCellValue('AX'.$i, number_format(getGCMS($item->id_createlab, 'E32') ));

                    $sheet->setCellValue('AY'.$i, number_format(getGCMS($item->id_createlab, 'E33') ));

                    $sheet->setCellValue('AZ'.$i, number_format(getGCMS($item->id_createlab, 'E34') ));

                    $sheet->setCellValue('BA'.$i, number_format(getGCMS($item->id_createlab, 'E35') ));

                    $sheet->setCellValue('BB'.$i, number_format(getGCMS($item->id_createlab, 'E36') ));

                    $sheet->setCellValue('BC'.$i, number_format(getGCMS($item->id_createlab, 'E37') ));

                    $sheet->setCellValue('BD'.$i, number_format(getGCMS($item->id_createlab, 'E38') ));

                    $sheet->setCellValue('BE'.$i, number_format(getGCMS($item->id_createlab, 'E39') ));

                    $sheet->setCellValue('BF'.$i, number_format(getGCMS($item->id_createlab, 'E40') ));

                    $sheet->setCellValue('BG'.$i, number_format(getGCMS($item->id_createlab, 'E41') ));

                    $sheet->setCellValue('BH'.$i, number_format(getGCMS($item->id_createlab, 'E42') ));

                    $sheet->setCellValue('BI'.$i, number_format(getGCMS($item->id_createlab, 'E43') ));

                    $sheet->setCellValue('BJ'.$i, number_format(getGCMS($item->id_createlab, 'E44') ));

                    $sheet->setCellValue('BK'.$i, number_format(getGCMS($item->id_createlab, 'E45') ));

                    $sheet->setCellValue('BL'.$i, number_format(getGCMS($item->id_createlab, 'E46') ));

                    $sheet->setCellValue('BM'.$i, number_format(getGCMS($item->id_createlab, 'E47') ));

                    $sheet->setCellValue('BN'.$i, number_format(getGCMS($item->id_createlab, 'E48') ));

                    $sheet->setCellValue('BO'.$i, number_format(getGCMS($item->id_createlab, 'E49') ));

                    $sheet->setCellValue('BP'.$i, number_format(getGCMS($item->id_createlab, 'E50') ));

                    $sheet->setCellValue('BQ'.$i, number_format(getGCMS($item->id_createlab, 'E51') ));

                    $sheet->setCellValue('BR'.$i, number_format(getGCMS($item->id_createlab, 'E52') ));

                    $sheet->setCellValue('BS'.$i, number_format(getGCMS($item->id_createlab, 'E53') ));

                    $sheet->setCellValue('BT'.$i, number_format(getGCMS($item->id_createlab, 'E54') ));

                    $sheet->setCellValue('BU'.$i, number_format(getGCMS($item->id_createlab, 'E55') ));

                    $sheet->setCellValue('BV'.$i, number_format(getGCMS($item->id_createlab, 'E56') ));

                    $sheet->setCellValue('BW'.$i, number_format(getGCMS($item->id_createlab, 'E57') ));

                    $sheet->setCellValue('BX'.$i, number_format(getGCMS($item->id_createlab, 'E58') ));

                    $sheet->setCellValue('BY'.$i, number_format(getGCMS($item->id_createlab, 'E59') ));

                    $sheet->setCellValue('BZ'.$i, number_format(getGCMS($item->id_createlab, 'E60') ));

                    $sheet->setCellValue('CA'.$i, number_format(getGCMS($item->id_createlab, 'E61') ));

                    $sheet->setCellValue('CB'.$i, number_format(getGCMS($item->id_createlab, 'E62') ));

                    $sheet->setCellValue('CC'.$i, number_format(getGCMS($item->id_createlab, 'E63') ));

                    $sheet->setCellValue('CD'.$i, number_format(getGCMS($item->id_createlab, 'E64') ));

                    $sheet->setCellValue('CE'.$i, number_format(getGCMS($item->id_createlab, 'E65') ));

                    $sheet->setCellValue('CF'.$i, number_format(getGCMS($item->id_createlab, 'E66') ));

                    $sheet->setCellValue('CG'.$i, number_format(getGCMS($item->id_createlab, 'E67') ));

                    $sheet->setCellValue('CH'.$i, number_format(getGCMS($item->id_createlab, 'E68') ));

                    $sheet->setCellValue('CI'.$i, number_format(getGCMS($item->id_createlab, 'E69') ));

                    $sheet->setCellValue('CJ'.$i, number_format(getGCMS($item->id_createlab, 'E70') ));

                    $sheet->setCellValue('CK'.$i, number_format(getGCMS($item->id_createlab, 'E71') ));

                    $sheet->setCellValue('CL'.$i, number_format(getGCMS($item->id_createlab, 'E72') ));

                    $sheet->setCellValue('CM'.$i, number_format(getGCMS($item->id_createlab, 'E75') ));

                    $sheet->setCellValue('CN'.$i, number_format(getGCMS($item->id_createlab, 'E73') ));

                    $sheet->setCellValue('CO'.$i, number_format(getGCMS($item->id_createlab, 'E74') ));

                    // $sheet->setCellValue('DB'.$i, getGHOST($item->id_createlab, 'H10'));
    
                    $i++;
                    $x++;

                }    

            })->setFilename($namefile)->store('xlsx', storage_path('NHK-File/report/ccpfile'));

            $data = [
                'gcmsstatus_done' => 1,
            ];
            DB::table('nt_oqa_lab')->where('id_createlab', $request->id)->update($data);
            $oqafile =  DB::table('nt_oqa_file')
            ->join('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_oqa_file.id_createlab')
            ->join('nt_model', 'nt_model.id_model', '=', 'nt_oqa_lab.id_model')
            ->where('nt_oqa_file.id_createlab',$request->id)->first();
               
            $datamail = [
                'testter'=>"GCMS",
                'model'=>$oqafile->name_model,
                'lot'=>$oqafile->lotnumber_lab,
                'date'=>date('d/m/Y H:i:s'),
            ];

            $mail->mailccp($datamail);
            nhkmail::mailccp($datamail);

            return response()->json(['success' =>  1, 'filename' => $namefile.'.xlsx']);

        }else{
            return response()->json(['success' =>  2]);
        } 
       
    }

    public function newccpexcelftir(Request $request)
    {
        ini_set('memory_limit', '-1');
        set_time_limit(5000); 

        $oqadate = DB::table('nt_data_ftir')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ftir.id_createlab')
        ->where('nt_oqa_lab.type_lab','LIKE','SGT_%')
        ->where('nt_data_ftir.id_createlab',$request->id)->where('nt_data_ftir.ftir_st',1)
        ->first();
        $oqasum = DB::table('nt_model')
        ->where('id_model',$oqadate->id_model)
        ->first(); 
        $datalaball = DB::table('nt_data_ftir')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ftir.id_createlab')
        ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
        ->join('nt_model','nt_model.id_model','=','nt_oqa_lab.id_model')
        ->where('nt_oqa_lab.type_lab','LIKE','SGT_%')
        ->where('nt_data_ftir.id_createlab',$request->id)
        ->where('nt_oqa_lab.ftir_confirm_st',1)
        ->limit(1)->orderBy('nt_oqa_lab.save_date', 'desc')->get();

        $getreport = DB::table('nt_oqa_file')->where('id_createlab', $request->id)->first();
        $readfileftir =  storage_path('app/public/OQA/FTIR/'.$getreport->file_ftir);
        if (file_exists($readfileftir)) {
                    
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($readfileftir);
            $sheet = $spreadsheet->setActiveSheetIndex(0);
            $B42 = $sheet->getCell('B42')->getOldCalculatedValue();
        }else{
            $B42 = 0;
        }

        $filetime = date('YmdHis');
        // $namefile = 'test_CCP_'.$datalaball[0]->name_model.'_'.$datalaball[0]->lotnumber_lab.'_'.$filetime;
        $namefile = 'CCP_'.$datalaball[0]->name_model.'_'.$datalaball[0]->lotnumber_lab.'_'.$filetime;
        $dataexport = 1;
        $file = storage_path('NHK-File/template/report/new_template_ccpfile.xlsx');
        
        if($datalaball){

            Excel::load($file, function ($doc)use($dataexport,$datalaball,$B42) {
                $sheet = $doc->setActiveSheetIndex(1);

                $x = 1;
                $i = 13;
                $row = 0;
                foreach ($datalaball as $item) {
                    $row++;

                    $ftime = date('n/j/Y H:i:s',strtotime($item->ftime_ftir));
                    $startime = date('n/j/Y H:i:s',strtotime($item->ftir_startime));
                    
                    $sheet->setCellValue('B'.$i, $x);

                    $sheet->setCellValue('C'.$i, $item->vendor_id);

                    $sheet->setCellValue('D'.$i, $item->sub_supplier_name);

                    $sheet->setCellValue('E'.$i, $item->part_number);

                    $sheet->setCellValue('F'.$i, $item->part_revision);

                    $sheet->setCellValue('G'.$i, $item->drawing_number);

                    $sheet->setCellValue('H'.$i, $item->drawing_revision);

                    $sheet->setCellValue('I'.$i, $startime);

                    $sheet->setCellValue('K'.$i, $item->lotnumber_lab);

                    $sheet->setCellValue('L'.$i, $ftime);

                    $sheet->setCellValue('M'.$i, $row); 

                    $sheet->setCellValue('R'.$i, $item->ftir_parts_used);

                    $sheet->setCellValue('S'.$i, $item->surface_area);

                    $sheet->setCellValue('T'.$i, number_format( $B42 , 1,'.','')); 

                    // $sheet->setCellValue('V'.$i, number_format($item->surface_area, 2,'.',''));

                    // $sheet->setCellValue('X'.$i, number_format($item->alpc_parts_used));

                    // $sheet->setCellValue('Y'.$i, number_format($item->sem_parts_used, 2,'.',''));
    
                    $i++;
                    $x++;

                }    

            })->setFilename($namefile)->store('xlsx', storage_path('NHK-File/report/ccpfile'));

            $data = [
                'ftirstatus_done' => 1,
               ];
               DB::table('nt_oqa_lab')->where('id_createlab', $request->id)->update($data);
               
               $oqafile =  DB::table('nt_oqa_file')
               ->join('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_oqa_file.id_createlab')
               ->join('nt_model', 'nt_model.id_model', '=', 'nt_oqa_lab.id_model')
               ->where('nt_oqa_file.id_createlab',$request->id)->first();
               $datamail = [
                'testter'=>"FTIR",
                'model'=>$oqafile->name_model,
                'lot'=>$oqafile->lotnumber_lab,
                'date'=>date('d/m/Y H:i:s'),
            ];
            $mail = new MailController();
            $mail->mailccp($datamail);
            return response()->json(['success' =>  1, 'filename' => $namefile.'.xlsx']);

        }else{
            return response()->json(['success' =>  2]);
        }

    }

    public function newccpfileexcelghost(Request $request)
    {
        
        function getGHOST($id, $line){
            $getreport = DB::table('nt_oqa_file')->where('id_createlab',$id)->first();
            $readfileghost =  storage_path('app/public/OQA/GhostTest/'.$getreport->file_semtest);
            
            if (file_exists($readfileghost)) {
                
                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($readfileghost);
                $sheet = $spreadsheet->setActiveSheetIndex(0);
                
                $getrow = $sheet->getCell($line)->getOldCalculatedValue();
                if(empty($getrow)){
                    $getrow = $sheet->getCell($line)->getCalculatedValue();
                }
    
                return $getrow;

            }else{
                return 0;
            }
        }

        
        $oqadate = DB::table('nt_data_semtest')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_semtest.id_createlab')
        ->where('nt_data_semtest.id_createlab',$request->id)
        ->where('nt_data_semtest.semtest_st',1)
        ->where('nt_oqa_lab.type_lab','LIKE','SGT_%')
        ->first();
        $oqasum = DB::table('nt_model')
        ->where('id_model',$oqadate->id_model)
        ->first(); 
        $datalaball = DB::table('nt_data_semtest')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_semtest.id_createlab')
        ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
        ->join('nt_model','nt_model.id_model','=','nt_oqa_lab.id_model')
        ->where('nt_data_semtest.id_createlab',$request->id)
        ->where('nt_oqa_lab.semtest_confirm_st',1)
        ->where('nt_oqa_lab.type_lab','LIKE','SGT_%')
        ->limit(1)->orderBy('nt_oqa_lab.save_date', 'desc')->get();

        $filetime = date('YmdHis');
        // $namefile = 'test_CCP_'.$datalaball[0]->name_model.'_'.$datalaball[0]->lotnumber_lab.'_'.$filetime;
        $namefile = 'CCP_'.$datalaball[0]->name_model.'_'.$datalaball[0]->lotnumber_lab.'_'.$filetime;
        $dataexport = 1;
        $file = storage_path('NHK-File/template/report/new_template_ccpfile.xlsx');

        if($datalaball){

            Excel::load($file, function ($doc)use($dataexport,$datalaball) {
                $sheet = $doc->setActiveSheetIndex(1);

                $x = 1;
                $i = 13;
                $row = 0;
                foreach ($datalaball as $item) {
                    $row++;

                    $ftime = date('n/j/Y H:i:s',strtotime($item->ftime_semtest));
                    $startime = date('n/j/Y H:i:s',strtotime($item->semtest_startime));
                    
                    $sheet->setCellValue('B'.$i, $x);

                    $sheet->setCellValue('C'.$i, $item->vendor_id);

                    $sheet->setCellValue('D'.$i, $item->sub_supplier_name);

                    $sheet->setCellValue('E'.$i, $item->part_number);

                    $sheet->setCellValue('F'.$i, $item->part_revision);

                    $sheet->setCellValue('G'.$i, $item->drawing_number);

                    $sheet->setCellValue('H'.$i, $item->drawing_revision);

                    $sheet->setCellValue('I'.$i, $startime);

                    $sheet->setCellValue('K'.$i, $item->lotnumber_lab);

                    $sheet->setCellValue('L'.$i, $ftime);

                    $sheet->setCellValue('M'.$i, $row);

                    $sheet->setCellValue('DB'.$i, number_format(getGHOST($item->id_createlab, 'H10'), 1,'.',''));
    
                    $i++;
                    $x++;

                }    

            })->setFilename($namefile)->store('xlsx', storage_path('NHK-File/report/ccpfile'));

            $data = [
                'semteststatus_done' => 1,
               ];
               DB::table('nt_oqa_lab')->where('id_createlab', $request->id)->update($data);
              
    
               $oqafile =  DB::table('nt_oqa_file')
               ->join('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_oqa_file.id_createlab')
               ->join('nt_model', 'nt_model.id_model', '=', 'nt_oqa_lab.id_model')
               ->where('nt_oqa_file.id_createlab',$request->id)->first();
               $datamail = [
                'testter'=>"SEM (Ghost)",
                'model'=>$oqafile->name_model,
                'lot'=>$oqafile->lotnumber_lab,
                'date'=>date('d/m/Y H:i:s'),
            ];
            $mail = new MailController();
            $mail->mailccp($datamail);

            return response()->json(['success' =>  1, 'filename' => $namefile.'.xlsx']);
            

        }else{
            return response()->json(['success' =>  2]);
        }
        
    }

    public function newccpfileexcelall(Request $request)
    {
        set_time_limit(5000); 
        
        function getLPC($id, $line){
            $getreport = DB::table('nt_oqa_file')->where('id_createlab',$id)->first();
            $readfilepc =  storage_path('app/public/OQA/LPC/'.$getreport->file_lpc);
           
            if (file_exists($readfilepc)) {
                
                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($readfilepc);
                $sheet = $spreadsheet->setActiveSheetIndex(0);
                
                $getrow = $sheet->getCell($line)->getOldCalculatedValue();
                if(empty($getrow)){
                    $getrow = $sheet->getCell($line)->getCalculatedValue();
                } 
    
                return $getrow;

            }else{
                return 0;
            }
        } 

        function getSEM($id, $line){
            $getreport = DB::table('nt_oqa_file')->where('id_createlab',$id)->first();
            $readfisem =  storage_path('app/public/OQA/SEM/'.$getreport->file_sem);
            
            if (file_exists($readfisem)) {
                
                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($readfisem);
                $sheet = $spreadsheet->setActiveSheetIndex(2);
                
                $getrow = $sheet->getCell($line)->getOldCalculatedValue();
                if(empty($getrow)){
                    $getrow = $sheet->getCell($line)->getCalculatedValue();
                }
                return $getrow;

            }else{
                return 0;
            }
        }

        function getIC($id, $line){
            $getreport = DB::table('nt_oqa_file')->where('id_createlab',$id)->first();
            $readfiIC =  storage_path('app/public/OQA/IC/'.$getreport->file_ic);
           
            if (file_exists($readfiIC)) {
                
                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($readfiIC);
                $sheet = $spreadsheet->setActiveSheetIndex(0);
                
                $getrow = $sheet->getCell($line)->getOldCalculatedValue();
                if(empty($getrow)){
                    $getrow = $sheet->getCell($line)->getCalculatedValue();
                }
                return $getrow;    
            }else{
                return 0;
            }
        }    
        
        function getGCMS($id, $line){
            $getreport = DB::table('nt_oqa_file')->where('id_createlab',$id)->first();
            $readfilegcms =  storage_path('app/public/OQA/GCMS/'.$getreport->file_gcms);
            if (file_exists($readfilegcms)) {
                
                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($readfilegcms);
                $sheet = $spreadsheet->setActiveSheetIndex(2);
                
                $getrow = $sheet->getCell($line)->getOldCalculatedValue();
                if(empty($getrow)){
                    $getrow = $sheet->getCell($line)->getCalculatedValue();
                }
                return $getrow;

            }else{
                return 0;
            }
        } 

        function getFTIR($id, $line){
            $getreport = DB::table('nt_oqa_file')->where('id_createlab', $id)->first();
            $readfileftir =  storage_path('app/public/OQA/FTIR/'.$getreport->file_ftir);
            if (file_exists($readfileftir)) {
                
                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($readfileftir);
                $sheet = $spreadsheet->setActiveSheetIndex(0);
                
                $getrow = $sheet->getCell($line)->getOldCalculatedValue();
                if(empty($getrow)){
                    $getrow = $sheet->getCell($line)->getCalculatedValue();
                }
                return $getrow;    

            }else{
                return 0;
            }
        }        

        function getGHOST($id, $line){
            $getreport = DB::table('nt_oqa_file')->where('id_createlab',$id)->first();
            $readfileghost =  storage_path('app/public/OQA/GhostTest/'.$getreport->file_semtest);
            
            if (file_exists($readfileghost)) {
                
                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($readfileghost);
                $sheet = $spreadsheet->setActiveSheetIndex(0);
                
                $getrow = $sheet->getCell($line)->getOldCalculatedValue();
                if(empty($getrow)){
                    $getrow = $sheet->getCell($line)->getCalculatedValue();
                }
                return $getrow;

            }else{
                return 0;
            }
        } 

        $sumccp = 0;
        $sumpassccp = 0;

        $ccplpc = $request->ccplpc;
        $ccpsem = $request->ccpsem;
        $ccpic = $request->ccpic;
        $ccpgcms = $request->ccpgcms;
        $ccpftir = $request->ccpftir;
        $ccpsemtest = $request->ccpsemtest;


        if(isset($ccplpc)){ $sumccp += 1; }
        if(isset($ccpsem)){ $sumccp += 1; }
        if(isset($ccpic)){ $sumccp += 1; }
        if(isset($ccpgcms)){ $sumccp += 1; }
        if(isset($ccpftir)){ $sumccp += 1; }
        if(isset($ccpsemtest)){ $sumccp += 1; }

        if(isset($ccplpc)){ 
            if($ccplpc == 1){
                $sumpassccp += 1;
            }
        }
        if(isset($ccpsem)){ 
            if($ccpsem == 1){
                $sumpassccp += 1;
            }
        }
        if(isset($ccpic)){ 
            if($ccpic == 1){
                $sumpassccp += 1;
            }
        }
        if(isset($ccpgcms)){ 
            if($ccpgcms == 1){
                $sumpassccp += 1;
            }
        }
        if(isset($ccpftir)){ 
            if($ccpftir == 1){
                $sumpassccp += 1;
            }
        }
        if(isset($ccpsemtest)){ 
            if($ccpsemtest == 1){
                $sumpassccp += 1;
            }
        }
        if($sumccp == $sumpassccp){
 
            $oqadate = DB::table('nt_data_lpc')
            ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_lpc.id_createlab')
            ->where('nt_oqa_lab.type_lab','LIKE','SGT_%')
            ->where('nt_data_lpc.id_createlab',$request->id)->where('nt_data_lpc.lpc_st',1)
            ->first();
        if($oqadate == null)
        {
            $oqadate = DB::table('nt_data_sem')
            ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
            ->where('nt_oqa_lab.type_lab','LIKE','SGT_%')
            ->where('nt_data_sem.id_createlab',$request->id)->where('nt_data_sem.sem_st',1)
            ->first();
        }
        if($oqadate == null)
        {
            $oqadate = DB::table('nt_data_ic')
            ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ic.id_createlab')
            ->where('nt_oqa_lab.type_lab','LIKE','SGT_%')
            ->where('nt_data_ic.id_createlab',$request->id)->where('nt_data_ic.ic_st',1)
            ->first();
        }
        if($oqadate == null)
        {
            $oqadate = DB::table('nt_data_gcms')
            ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
            ->where('nt_oqa_lab.type_lab','LIKE','SGT_%')
            ->where('nt_data_gcms.id_createlab',$request->id)->where('nt_data_gcms.gcms_st',1)
            ->first();
        }
        if($oqadate == null)
        {
            $oqadate = DB::table('nt_data_ftir')
            ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ftir.id_createlab')
            ->where('nt_oqa_lab.type_lab','LIKE','SGT_%')
            ->where('nt_data_ftir.id_createlab',$request->id)->where('nt_data_ftir.ftir_st',1)
            ->first();
        }
        if($oqadate == null)
        {
            $oqadate = DB::table('nt_data_semtest')
            ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_semtest.id_createlab')
            ->where('nt_oqa_lab.type_lab','LIKE','SGT_%')
            ->where('nt_data_semtest.id_createlab',$request->id)->where('nt_data_semtest.semtest_st',1)
            ->first();
        }

            $oqasum = DB::table('nt_model')
            ->where('id_model',$oqadate->id_model)
            ->first(); 
            if($ccplpc == 1)
            {
                $datalablpcall = DB::table('nt_data_lpc')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_lpc.id_createlab')
                ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                ->join('nt_model','nt_model.id_model','=','nt_oqa_lab.id_model')
                ->where('nt_data_lpc.id_createlab',$request->id)
                ->where('nt_oqa_lab.lpc_confirm_st',1)
                ->limit(intval($oqasum->alpc_sum))->orderBy('nt_oqa_lab.save_date', 'desc')->first();
            }
            else
            {
                $datalablpcall = null;
            }
            if($ccpsem == 1)
            {
                $datalabsemall = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                ->join('nt_model','nt_model.id_model','=','nt_oqa_lab.id_model')
                ->where('nt_data_sem.id_createlab',$request->id)
                ->where('nt_oqa_lab.sem_confirm_st',1)->orderBy('nt_oqa_lab.save_date', 'desc')->first();
            }
            else
            {
                $datalabsemall = null;
            }
            if($ccpic == 1)
            {
                $datalabicall = DB::table('nt_data_ic')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ic.id_createlab')
                ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                ->join('nt_model','nt_model.id_model','=','nt_oqa_lab.id_model')
                ->where('nt_data_ic.id_createlab',$request->id)
                ->where('nt_oqa_lab.ic_confirm_st',1)->orderBy('nt_oqa_lab.save_date', 'desc')->first();
            }
            else
            {
                $datalabicall = null;
            }
            if($ccpgcms == 1)
            {
            
                $datalabgcmsall = DB::table('nt_data_gcms')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
                ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                ->join('nt_model','nt_model.id_model','=','nt_oqa_lab.id_model')
                ->join('nt_outgassing_gcms','nt_outgassing_gcms.id_createlab','=','nt_oqa_lab.id_createlab')
                ->where('nt_data_gcms.id_createlab',$request->id)
                ->where('nt_oqa_lab.gcms_confirm_st',1)->orderBy('nt_oqa_lab.save_date', 'desc')->first();
            }
            else
            {
                $datalabgcmsall = null;
            }
            if($ccpftir == 1)
            {
                $datalabftirall = DB::table('nt_data_ftir')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ftir.id_createlab')
                ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                ->join('nt_model','nt_model.id_model','=','nt_oqa_lab.id_model')
                ->where('nt_data_ftir.id_createlab',$request->id)
                ->where('nt_oqa_lab.ftir_confirm_st',1)->orderBy('nt_oqa_lab.save_date', 'desc')->first();
            }
            else
            {
                $datalabftirall = null;
            }
            if($ccpsemtest == 1)
            {
                $datalabsemtestall = DB::table('nt_data_semtest')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_semtest.id_createlab')
                ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                ->join('nt_model','nt_model.id_model','=','nt_oqa_lab.id_model')
                ->where('nt_data_semtest.id_createlab',$request->id)
                ->where('nt_oqa_lab.semtest_confirm_st',1)->orderBy('nt_oqa_lab.save_date', 'desc')->first();
            }
            else
            {
                $datalabsemtestall = null;
            }

            $filetime = date('YmdHis');
            // $namefile = 'Test_CCP_'.$oqasum->name_model.'_'.$oqadate->lotnumber_lab.'_'.$filetime;
            $namefile = 'CCP_'.$oqasum->name_model.'_'.$oqadate->lotnumber_lab.'_'.$filetime;
            $dataexport = 1;
            $file = storage_path('NHK-File/template/report/new_template_ccpfile.xlsx');

            Excel::load($file, function ($doc)use($dataexport,$datalablpcall,$datalabsemall,$datalabicall,$datalabgcmsall,$datalabftirall,$datalabsemtestall,$ccplpc,$ccpsem,$ccpic,$ccpgcms,$ccpftir,$ccpsemtest){
                $sheet = $doc->setActiveSheetIndex(1);
                $i = 12;
                $row = 0;
                    if($ccplpc == 1)
                    {
                        $i++;
                        $row++;
                        $ftime = date('n/j/Y H:i:s',strtotime($datalablpcall->ftime_lpc));
                        $startime = date('n/j/Y H:i:s',strtotime($datalablpcall->lpc_startime));
    
                        $sheet->setCellValue('B'.$i, $row);
                        $sheet->setCellValue('C'.$i, $datalablpcall->vendor_id);
                        $sheet->setCellValue('D'.$i, $datalablpcall->sub_supplier_name);
                        $sheet->setCellValue('E'.$i, $datalablpcall->part_number);
                        $sheet->setCellValue('F'.$i, $datalablpcall->part_revision);
                        $sheet->setCellValue('G'.$i, $datalablpcall->drawing_number);
                        $sheet->setCellValue('H'.$i, $datalablpcall->drawing_revision);
                        $sheet->setCellValue('I'.$i, $startime);
                        $sheet->setCellValue('K'.$i, $datalablpcall->lotnumber_lab);
                        $sheet->setCellValue('L'.$i, $ftime);
                        $sheet->setCellValue('M'.$i, $row);
                        $sheet->setCellValue('O'.$i, number_format($datalablpcall->surface_area, 2,'.',''));
                        $sheet->setCellValue('U'.$i, number_format($datalablpcall->alpc_parts_used));
                        $sheet->setCellValue('V'.$i, number_format($datalablpcall->surface_area, 2,'.',''));                    
                        $sheet->setCellValue('W'.$i, number_format(getLPC($datalablpcall->id_createlab, 'O19'), 0,'.',''));
                        $sheet->setCellValue('X'.$i, number_format($datalablpcall->alpc_parts_use));
                        $sheet->setCellValue('Y'.$i, number_format($datalablpcall->sem_parts_used, 2,'.',''));

                    }
                    if($ccpsem == 1)
                    {
                        $i++;
                        $row++;
                        $ftime = date('n/j/Y H:i:s',strtotime($datalabsemall->ftime_sem));
                        $startime = date('n/j/Y H:i:s',strtotime($datalabsemall->sem_startime));                  
                    
                        $sheet->setCellValue('B'.$i, $row);
                        $sheet->setCellValue('C'.$i, $datalabsemall->vendor_id);
                        $sheet->setCellValue('D'.$i, $datalabsemall->sub_supplier_name);
                        $sheet->setCellValue('E'.$i, $datalabsemall->part_number);
                        $sheet->setCellValue('F'.$i, $datalabsemall->part_revision);
                        $sheet->setCellValue('G'.$i, $datalabsemall->drawing_number);
                        $sheet->setCellValue('H'.$i, $datalabsemall->drawing_revision);
                        $sheet->setCellValue('I'.$i, $startime);
                        $sheet->setCellValue('K'.$i, $datalabsemall->lotnumber_lab);
                        $sheet->setCellValue('L'.$i, $ftime);
                        $sheet->setCellValue('M'.$i, $row);
                        $sheet->setCellValue('X'.$i, number_format($datalabsemall->alpc_parts_used));
                        $sheet->setCellValue('Y'.$i, number_format($datalabsemall->sem_parts_used, 2,'.',''));
                        $sheet->setCellValue('Z'.$i, ($datalabsemall->last_sem_class1 >= 0)? number_format($datalabsemall->last_sem_class1, 1,'.','') : number_format(getSEM($datalabsemall->id_createlab, 'P6'), 1,'.','') );
                        $sheet->setCellValue('AA'.$i, ($datalabsemall->last_sem_class3 >= 0)? number_format($datalabsemall->last_sem_class3, 1,'.','') : number_format(getSEM($datalabsemall->id_createlab, 'P8'), 1,'.','') );
                        $sheet->setCellValue('AB'.$i, ($datalabsemall->last_sem_class3_1 >= 0)? number_format($datalabsemall->last_sem_class3_1, 1,'.','') : number_format(getSEM($datalabsemall->id_createlab, 'P9'), 1,'.','') );  
                        $sheet->setCellValue('AC'.$i, ($datalabsemall->last_sem_class3_2 >= 0)? number_format($datalabsemall->last_sem_class3_2, 1,'.','') : number_format(getSEM($datalabsemall->id_createlab, 'P10'), 1,'.','') ); 
                        $sheet->setCellValue('AD'.$i, ($datalabsemall->last_sem_class3_3 >= 0)? number_format($datalabsemall->last_sem_class3_3, 1,'.','') : number_format(getSEM($datalabsemall->id_createlab, 'P11'), 1,'.','') );
                        $sheet->setCellValue('AE'.$i, ($datalabsemall->last_sem_class4 >= 0)? number_format($datalabsemall->last_sem_class4, 1,'.','') : number_format(getSEM($datalabsemall->id_createlab, 'P12'), 1,'.','') );
                        $sheet->setCellValue('AF'.$i, ($datalabsemall->last_sem_class5 >= 0)? number_format($datalabsemall->sem_class5, 1,'.','') : number_format(getSEM($datalabsemall->id_createlab, 'P13'), 1,'.','') );
                        $sheet->setCellValue('AG'.$i, ($datalabsemall->last_sem_class5_1 >= 0)? number_format($datalabsemall->last_sem_class5_1, 1,'.','') : number_format(getSEM($datalabsemall->id_createlab, 'P14'), 1,'.','') );
                    } 
                    if($ccpic == 1)
                    {
                        $i++;
                        $row++;
                        $ftime = date('n/j/Y H:i:s',strtotime($datalabicall->ftime_ic));
                        $startime = date('n/j/Y H:i:s',strtotime($datalabicall->ftir_startime));

                        $sheet->setCellValue('B'.$i, $row);
                        $sheet->setCellValue('C'.$i, $datalabicall->vendor_id);
                        $sheet->setCellValue('D'.$i, $datalabicall->sub_supplier_name);
                        $sheet->setCellValue('E'.$i, $datalabicall->part_number);
                        $sheet->setCellValue('F'.$i, $datalabicall->part_revision);
                        $sheet->setCellValue('G'.$i, $datalabicall->drawing_number);
                        $sheet->setCellValue('H'.$i, $datalabicall->drawing_revision);
                        $sheet->setCellValue('O'.$i, $datalabicall->surface_area);
                        $sheet->setCellValue('I'.$i, $startime);
                        $sheet->setCellValue('K'.$i, $datalabicall->lotnumber_lab);
                        $sheet->setCellValue('L'.$i, $ftime);
                        $sheet->setCellValue('M'.$i, $row);
                        $sheet->setCellValue('N'.$i, number_format($datalabicall->ic_parts_used));
                        $sheet->setCellValue('O'.$i, number_format($datalabicall->surface_area, 2,'.',''));
                        $sheet->setCellValue('P'.$i, number_format(getIC($datalabicall->id_createlab, 'I25'), 2,'.',''));
                        $sheet->setCellValue('Q'.$i, number_format(getIC($datalabicall->id_createlab, 'I23'), 2,'.',''));
                    } 
                    if($ccpgcms == 1)
                    {
                        $i++;
                        $row++;
                        $ftime = date('n/j/Y H:i:s',strtotime($datalabgcmsall->ftime_gcms));
                        $startime = date('n/j/Y H:i:s',strtotime($datalabgcmsall->gcms_startime));

                        $sheet->setCellValue('B'.$i, $row);

                        $sheet->setCellValue('C'.$i, $datalabgcmsall->vendor_id);
                        $sheet->setCellValue('D'.$i, $datalabgcmsall->sub_supplier_name);
                        $sheet->setCellValue('E'.$i, $datalabgcmsall->part_number);
                        $sheet->setCellValue('F'.$i, $datalabgcmsall->part_revision);
                        $sheet->setCellValue('G'.$i, $datalabgcmsall->drawing_number);
                        $sheet->setCellValue('H'.$i, $datalabgcmsall->drawing_revision);
                        $sheet->setCellValue('I'.$i, $startime);
                        $sheet->setCellValue('K'.$i, $datalabgcmsall->lotnumber_lab);
                        $sheet->setCellValue('L'.$i, $ftime);
                        $sheet->setCellValue('M'.$i, $row); 
                        $sheet->setCellValue('O'.$i, number_format($datalabgcmsall->surface_area, 2,'.',''));
                        $sheet->setCellValue('AH'.$i, number_format(getGCMS($datalabgcmsall->id_createlab, 'B14')));
                        $sheet->setCellValue('AI'.$i, number_format(getGCMS($datalabgcmsall->id_createlab, 'E17')));
                        $sheet->setCellValue('AJ'.$i, number_format(getGCMS($datalabgcmsall->id_createlab, 'E18')));
                        $sheet->setCellValue('AK'.$i, number_format(getGCMS($datalabgcmsall->id_createlab, 'E19')));
                        $sheet->setCellValue('AL'.$i, number_format(getGCMS($datalabgcmsall->id_createlab, 'E20')));
                        $sheet->setCellValue('AM'.$i, number_format(getGCMS($datalabgcmsall->id_createlab, 'E21')));
                        $sheet->setCellValue('AN'.$i, number_format(getGCMS($datalabgcmsall->id_createlab, 'E22')));
                        $sheet->setCellValue('AO'.$i, number_format(getGCMS($datalabgcmsall->id_createlab, 'E23')));
                        $sheet->setCellValue('AP'.$i, number_format(getGCMS($datalabgcmsall->id_createlab, 'E24')));
                        $sheet->setCellValue('AQ'.$i, number_format(getGCMS($datalabgcmsall->id_createlab, 'E25')));
                        $sheet->setCellValue('AR'.$i, number_format(getGCMS($datalabgcmsall->id_createlab, 'E26')));
                        $sheet->setCellValue('AS'.$i, number_format(getGCMS($datalabgcmsall->id_createlab, 'E27')));
                        $sheet->setCellValue('AT'.$i, number_format(getGCMS($datalabgcmsall->id_createlab, 'E28')));
                        $sheet->setCellValue('AU'.$i, number_format(getGCMS($datalabgcmsall->id_createlab, 'E29')));
                        $sheet->setCellValue('AV'.$i, number_format(getGCMS($datalabgcmsall->id_createlab, 'E30')));
                        $sheet->setCellValue('AW'.$i, number_format(getGCMS($datalabgcmsall->id_createlab, 'E31')));
                        $sheet->setCellValue('AX'.$i, number_format(getGCMS($datalabgcmsall->id_createlab, 'E32')));
                        $sheet->setCellValue('AY'.$i, number_format(getGCMS($datalabgcmsall->id_createlab, 'E33')));
                        $sheet->setCellValue('AZ'.$i, number_format(getGCMS($datalabgcmsall->id_createlab, 'E34')));
                        $sheet->setCellValue('BA'.$i, number_format(getGCMS($datalabgcmsall->id_createlab, 'E35')));
                        $sheet->setCellValue('BB'.$i, number_format(getGCMS($datalabgcmsall->id_createlab, 'E36')));
                        $sheet->setCellValue('BC'.$i, number_format(getGCMS($datalabgcmsall->id_createlab, 'E37')));
                        $sheet->setCellValue('BD'.$i, number_format(getGCMS($datalabgcmsall->id_createlab, 'E38')));
                        $sheet->setCellValue('BE'.$i, number_format(getGCMS($datalabgcmsall->id_createlab, 'E39')));
                        $sheet->setCellValue('BF'.$i, number_format(getGCMS($datalabgcmsall->id_createlab, 'E40')));
                        $sheet->setCellValue('BG'.$i, number_format(getGCMS($datalabgcmsall->id_createlab, 'E41')));
                        $sheet->setCellValue('BH'.$i, number_format(getGCMS($datalabgcmsall->id_createlab, 'E42')));
                        $sheet->setCellValue('BI'.$i, number_format(getGCMS($datalabgcmsall->id_createlab, 'E43')));
                        $sheet->setCellValue('BJ'.$i, number_format(getGCMS($datalabgcmsall->id_createlab, 'E44')));
                        $sheet->setCellValue('BK'.$i, number_format(getGCMS($datalabgcmsall->id_createlab, 'E45')));
                        $sheet->setCellValue('BL'.$i, number_format(getGCMS($datalabgcmsall->id_createlab, 'E46')));
                        $sheet->setCellValue('BM'.$i, number_format(getGCMS($datalabgcmsall->id_createlab, 'E47')));
                        $sheet->setCellValue('BN'.$i, number_format(getGCMS($datalabgcmsall->id_createlab, 'E48')));
                        $sheet->setCellValue('BO'.$i, number_format(getGCMS($datalabgcmsall->id_createlab, 'E49')));
                        $sheet->setCellValue('BP'.$i, number_format(getGCMS($datalabgcmsall->id_createlab, 'E50')));
                        $sheet->setCellValue('BQ'.$i, number_format(getGCMS($datalabgcmsall->id_createlab, 'E51')));
                        $sheet->setCellValue('BR'.$i, number_format(getGCMS($datalabgcmsall->id_createlab, 'E52')));
                        $sheet->setCellValue('BS'.$i, number_format(getGCMS($datalabgcmsall->id_createlab, 'E53')));
                        $sheet->setCellValue('BT'.$i, number_format(getGCMS($datalabgcmsall->id_createlab, 'E54')));
                        $sheet->setCellValue('BU'.$i, number_format(getGCMS($datalabgcmsall->id_createlab, 'E55')));
                        $sheet->setCellValue('BV'.$i, number_format(getGCMS($datalabgcmsall->id_createlab, 'E56')));
                        $sheet->setCellValue('BW'.$i, number_format(getGCMS($datalabgcmsall->id_createlab, 'E57')));
                        $sheet->setCellValue('BX'.$i, number_format(getGCMS($datalabgcmsall->id_createlab, 'E58')));
                        $sheet->setCellValue('BY'.$i, number_format(getGCMS($datalabgcmsall->id_createlab, 'E59')));
                        $sheet->setCellValue('BZ'.$i, number_format(getGCMS($datalabgcmsall->id_createlab, 'E60')));
                        $sheet->setCellValue('CA'.$i, number_format(getGCMS($datalabgcmsall->id_createlab, 'E61')));
                        $sheet->setCellValue('CB'.$i, number_format(getGCMS($datalabgcmsall->id_createlab, 'E62')));
                        $sheet->setCellValue('CC'.$i, number_format(getGCMS($datalabgcmsall->id_createlab, 'E63')));
                        $sheet->setCellValue('CD'.$i, number_format(getGCMS($datalabgcmsall->id_createlab, 'E64')));
                        $sheet->setCellValue('CE'.$i, number_format(getGCMS($datalabgcmsall->id_createlab, 'E65')));
                        $sheet->setCellValue('CF'.$i, number_format(getGCMS($datalabgcmsall->id_createlab, 'E66')));
                        $sheet->setCellValue('CG'.$i, number_format(getGCMS($datalabgcmsall->id_createlab, 'E67')));
                        $sheet->setCellValue('CH'.$i, number_format(getGCMS($datalabgcmsall->id_createlab, 'E68')));
                        $sheet->setCellValue('CI'.$i, number_format(getGCMS($datalabgcmsall->id_createlab, 'E69')));
                        $sheet->setCellValue('CJ'.$i, number_format(getGCMS($datalabgcmsall->id_createlab, 'E70')));
                        $sheet->setCellValue('CK'.$i, number_format(getGCMS($datalabgcmsall->id_createlab, 'E71')));
                        $sheet->setCellValue('CL'.$i, number_format(getGCMS($datalabgcmsall->id_createlab, 'E72')));
                        $sheet->setCellValue('CM'.$i, number_format(getGCMS($datalabgcmsall->id_createlab, 'E75')));
                        $sheet->setCellValue('CN'.$i, number_format(getGCMS($datalabgcmsall->id_createlab, 'E73')));
                        $sheet->setCellValue('CO'.$i, number_format(getGCMS($datalabgcmsall->id_createlab, 'E74')));

                    } 
                    if($ccpftir == 1)
                    {
                        $i++;
                        $row++;
                        $ftime = date('n/j/Y H:i:s',strtotime($datalabftirall->ftime_ftir));
                        $startime = date('n/j/Y H:i:s',strtotime($datalabftirall->ftir_startime));

                        $sheet->setCellValue('B'.$i, $row);
                        $sheet->setCellValue('C'.$i, $datalabftirall->vendor_id);
                        $sheet->setCellValue('D'.$i, $datalabftirall->sub_supplier_name);
                        $sheet->setCellValue('E'.$i, $datalabftirall->part_number);
                        $sheet->setCellValue('F'.$i, $datalabftirall->part_revision);
                        $sheet->setCellValue('G'.$i, $datalabftirall->drawing_number);
                        $sheet->setCellValue('H'.$i, $datalabftirall->drawing_revision);
                        $sheet->setCellValue('I'.$i, $startime);
                        $sheet->setCellValue('K'.$i, $datalabftirall->lotnumber_lab);
                        $sheet->setCellValue('L'.$i, $ftime);
                        $sheet->setCellValue('M'.$i, $row);
                        $sheet->setCellValue('O'.$i, number_format($datalabftirall->surface_area, 2,'.',''));
                        $sheet->setCellValue('R'.$i, number_format($datalabftirall->ftir_parts_used));
                        $sheet->setCellValue('S'.$i, number_format($datalabftirall->surface_area, 2,'.',''));
                        $sheet->setCellValue('T'.$i, number_format(getFTIR($datalabftirall->id_createlab, 'B42'), 1,'.',''));
                    } 
                    if( $ccpsemtest == 1)
                    {
                        $i++;
                        $row++;
                        $ftime = date('n/j/Y H:i:s',strtotime($datalabsemtestall->ftime_semtest));
                        $startime = date('n/j/Y H:i:s',strtotime($datalabsemtestall->semtest_startime));

                        $sheet->setCellValue('B'.$i, $row);
                        $sheet->setCellValue('C'.$i, $datalabsemtestall->vendor_id);
                        $sheet->setCellValue('D'.$i, $datalabsemtestall->sub_supplier_name);
                        $sheet->setCellValue('E'.$i, $datalabsemtestall->part_number);
                        $sheet->setCellValue('F'.$i, $datalabsemtestall->part_revision);
                        $sheet->setCellValue('G'.$i, $datalabsemtestall->drawing_number);
                        $sheet->setCellValue('H'.$i, $datalabsemtestall->drawing_revision);
                        $sheet->setCellValue('I'.$i, $startime);
                        $sheet->setCellValue('K'.$i, $datalabsemtestall->lotnumber_lab);
                        $sheet->setCellValue('L'.$i, $ftime);
                        $sheet->setCellValue('M'.$i, $row);
                        $sheet->setCellValue('DB'.$i, number_format(getGHOST($datalabsemtestall->id_createlab, 'H10'), 1,'.',''));  

                    } 
                
            })->setFilename($namefile)->store('xlsx', storage_path('NHK-File/report/ccpfile'));

            $txtmaillpc ='';
            $txtmailsem ='';
            $txtmailic ='';
            $txtmailgcms ='';
            $txtmailftir ='';
            $txtmailsemtest ='';
            if($ccplpc == 1)
            {
                $txtmaillpc = 'LPC';
                $data = [
                    'lpcstatus_done' => 1,
                    ];
                    DB::table('nt_oqa_lab')->where('id_createlab', $request->id)->update($data);
            }
            if($ccpsem == 1)
            {
                $txtmailsem = 'SEM';
                $data = [
                    'semstatus_done' => 1,
                    ];
                    DB::table('nt_oqa_lab')->where('id_createlab', $request->id)->update($data);
            }
            if($ccpic == 1)
            {
                $txtmailic = 'IC';
                $data = [
                    'icstatus_done' => 1,
                    ];
                    DB::table('nt_oqa_lab')->where('id_createlab', $request->id)->update($data);
            }   
            if($ccpgcms == 1)
            { 
                $txtmailgcms = 'GCMS';
                $data = [
                    'gcmsstatus_done' => 1,
                    ];
                    DB::table('nt_oqa_lab')->where('id_createlab', $request->id)->update($data);
            }
            if($ccpftir == 1)
            {
                $txtmailftir = 'FTIR';
                $data = [
                    'ftirstatus_done' => 1,
                    ];
                    DB::table('nt_oqa_lab')->where('id_createlab', $request->id)->update($data);
            }
            if($ccpsemtest == 1)
            {
                $txtmailsemtest = 'SEM (Ghost)';
                $data = [
                    'semteststatus_done' => 1,
                    ];
                    DB::table('nt_oqa_lab')->where('id_createlab', $request->id)->update($data);
            }


            $oqafile =  DB::table('nt_oqa_file')
            ->join('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_oqa_file.id_createlab')
            ->join('nt_model', 'nt_model.id_model', '=', 'nt_oqa_lab.id_model')
            ->where('nt_oqa_file.id_createlab',$request->id)->first();

            $arrtestter[] = $txtmaillpc;
            $arrtestter[] = $txtmailsem;
            $arrtestter[] = $txtmailic;
            $arrtestter[] = $txtmailgcms;
            $arrtestter[] = $txtmailftir;
            $arrtestter[] = $txtmailsemtest;

            $filtered_array = array_filter($arrtestter, function($value) {
                return $value !== '';
            });

            $stringtestter = implode(', ', $filtered_array);

            $datamail = [
                'testter'=>  $stringtestter,
                'model'=>$oqafile->name_model,
                'lot'=>$oqafile->lotnumber_lab,
                'date'=>date('d/m/Y H:i:s'),
            ];
            $mail = new MailController();
            $mail->mailccp($datamail);
            return response()->json(['success' =>  1, 'filename' => $namefile.'.xlsx']);

        }else{
            return response()->json(['success' =>  2]);

        }

    }
    
    public function checkalert_sigmaratio($data)
    {
        function medians($array)
        {
            rsort($array);
        
            $size = count($array);
            $middle = count($array) / 2; 

            if ($size  % 2 != 0){
                $median = $array[$middle]; 
            }
            else{
                $median1 = $array[$middle]; 
                $median2 = $array[$middle-1]; 
                $median = ($median1 + $median2) / 2;
            }
            return $median;
        }

        function stdevs($data)
        {
            $n = count($data);
            $mean = array_sum($data) / $n;
            $distance_sum = 0;

            foreach ($data as $i) {
            $distance_sum += ($i - $mean) ** 2;
            }
            $distance_sum = number_format($distance_sum, 2,'.','');

            $variance = $distance_sum / $n;
            $variance = number_format($variance, 2,'.','');

            $std_deviation = sqrt($variance);
            $std_deviation = number_format($std_deviation, 2,'.','');

            return $std_deviation;
            // print "RESULT:";
            // print PHP_EOL;
            // print "Data set: " . implode(", ", $data);
            // print PHP_EOL;
            // print "Count: " . $n;
            // print PHP_EOL;
            // print "Sum: " . array_sum($data);
            // print PHP_EOL;
            // print "Mean: " . $mean;
            // print PHP_EOL;
            // print "Variance: " . $variance;
            // print PHP_EOL;
            // print "Standard Deviation: " . $std_deviation;
        }
 
        $alert = 0;
        $id_createlab = $data['id'];

        $getlab =  DB::table('nt_oqa_lab')->where('id_createlab', $id_createlab)->first();
        $id = $getlab->id_model;

        $nametype = "";

        $datestart = date("Y-m-d", strtotime("2022-12-01"));

        $datalabgcms = DB::table('nt_data_gcms')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
        ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
        ->join('nt_model','nt_model.id_model','=','nt_oqa_lab.id_model')
        ->leftjoin('nt_outgassing_gcms','nt_outgassing_gcms.id_createlab','=','nt_oqa_lab.id_createlab')
        ->where('nt_data_gcms.id_createlab', $id_createlab)
        ->where('nt_oqa_lab.save_date','>=', $datestart)
        ->where('nt_oqa_lab.gcms_confirm_st',1)
        ->where('nt_oqa_lab.type_lab','LIKE','SGT_%')
        ->orderBy('nt_oqa_lab.save_date', 'asc')->get();

        if(count($datalabgcms) > 12)
        {
                
            $z = 0;

            $data = []; 
            $elemental_pre = 0;
            $acid_pre = 0;
            $oxygen_pre = 0;
            $aromatic_pre = 0;
            $aliphati_pre = 0;
            $low_pre = 0;
            $higt_pre = 0;
            $smear_pre = 0;
            $disk_pre = 0;

            $mdearray_elemental = array();
            $mdearray_acid = array();
            $mdearray_oxygen = array();
            $mdearray_aromatic = array();
            $mdearray_aliphati = array();
            $mdearray_low = array();
            $mdearray_shigt = array();
            $mdearray_smear = array();
            $mdearray_disk = array();

            $mdearray_elemental_loop = array();
            $mdearray_acid_loop = array();
            $mdearray_oxygen_loop = array();
            $mdearray_aromatic_loop = array();
            $mdearray_aliphati_loop = array();
            $mdearray_low_loop = array();
            $mdearray_shigt_loop = array();
            $mdearray_smear_loop = array();
            $mdearray_disk_loop = array();
            
            $wwk_med_elemental = array();
            $wwk_med_acid = array();
            $wwk_med_oxygen = array();
            $wwk_med_aromatic = array();
            $wwk_med_aliphati = array();
            $wwk_med_low = array();
            $wwk_med_higt = array();
            $wwk_med_smear = array();
            $wwk_med_disk = array();

            $base_sigma_elemental = array();
            $base_sigma_acid = array();
            $base_sigma_oxygen = array();
            $base_sigma_aromatic = array();
            $base_sigma_aliphati = array();
            $base_sigma_low = array();
            $base_sigma_higt = array();
            $base_sigma_smear = array();
            $base_sigma_disk = array();

            foreach ($datalabgcms as $value) {

                $id_createlab = $value->id_createlab;

                $c1 = number_format($value->c1, 2,'.','');
                $c2 = number_format($value->c2, 2,'.','');
                $c3 = number_format($value->c3, 2,'.','');
                $c4 = number_format($value->c4, 2,'.','');
                $c5 = number_format($value->c5, 2,'.','');
                $c5_1 = number_format($value->c5_1, 2,'.','');
                $c5_2 = number_format($value->c5_2, 2,'.','');
                $c5_3 = number_format($value->c5_3, 2,'.','');
                $c6 = number_format($value->c6, 2,'.','');
                $c6_1 = number_format($value->c6_1, 2,'.','');
                $c6_2 = number_format($value->c6_2, 2,'.','');
                $c7 = number_format($value->c7, 2,'.','');
                $c8 = number_format($value->c8, 2,'.','');
                $c8_1 = number_format($value->c8_1, 2,'.','');
                $c8_2 = number_format($value->c8_2, 2,'.','');
                $c9 = number_format($value->c9, 2,'.','');
                $c9_1 = number_format($value->c9_1, 2,'.','');
                $c9_2 = number_format($value->c9_2, 2,'.','');
                $c10 = number_format($value->c10, 2,'.','');
                $c11 = number_format($value->c11, 2,'.','');
                $c11_1 = number_format($value->c11_1, 2,'.','');
                $c11_2 = number_format($value->c11_2, 2,'.','');
                $c12 = number_format($value->c12, 2,'.','');
                $c13 = number_format($value->c13, 2,'.','');
                $c14 = number_format($value->c14, 2,'.','');
                $c15 = number_format($value->c15, 2,'.','');
                $c15_1 = number_format($value->c15_1, 2,'.','');
                $c15_2 = number_format($value->c15_2, 2,'.','');
                $c16 = number_format($value->c16, 2,'.','');
                $c17 = number_format($value->c17, 2,'.','');
                $c18 = number_format($value->c18, 2,'.','');
                $c19 = number_format($value->c19, 2,'.','');
                $c19_1 = number_format($value->c19_1, 2,'.','');
                $c19_2 = number_format($value->c19_2, 2,'.','');
                $c19_3 = number_format($value->c19_3, 2,'.','');
                $c20 = number_format($value->c20, 2,'.','');
                $c20_1 = number_format($value->c20_1, 2,'.','');
                $c21 = number_format($value->c21, 2,'.','');
                $c21_1 = number_format($value->c21_1, 2,'.','');
                $c22 = number_format($value->c22, 2,'.','');
                $c22_1 = number_format($value->c22_1, 2,'.','');
                $c22_1 = number_format($value->c22_2, 2,'.','');
                $c23 = number_format($value->c23, 2,'.','');
                $c23_1 = number_format($value->c23_1, 2,'.','');
                $c24 = number_format($value->c24, 2,'.','');
                $c24_1 = number_format($value->c24_1, 2,'.','');
                $c24_2 = number_format($value->c24_2, 2,'.','');
                $c25 = number_format($value->c25, 2,'.','');
                $c26 = number_format($value->c26, 2,'.','');
                $c27 = number_format($value->c27, 2,'.','');
                $c28 = number_format($value->c28, 2,'.','');
                $c29 = number_format($value->c29, 2,'.','');
                $c30 = number_format($value->c30, 2,'.','');
                $c31 = number_format($value->c31, 2,'.','');
                $c32 = number_format($value->c32, 2,'.','');
                $c33 = number_format($value->c33, 2,'.','');
                $c34 = number_format($value->c34, 2,'.','');
                $c35 = number_format($value->c35, 2,'.','');
                $total = number_format($value->total, 2,'.','');

                //-------------- Stap 1 => Category Sum-----------------------------------------------

                $elemental_pre = number_format($c1+$c2+$c3+$c4+$c8+$c9+$c10+$c16+$c17+$c28, 2,'.','');
                $acid_pre = number_format($c5+$c6+$c12+$c13, 2,'.','');
                $oxygen_pre = number_format($c7+$c19+$c21+$c22+$c23+$c24+$c29, 2,'.','');
                $aromatic_pre = number_format($c11+$c14+$c15+$c18+$c20+$c25+$c26, 2,'.','');
                $aliphati_pre = number_format($c27, 2,'.','');
                // low molecular c34
                $low_pre = number_format($c34, 2,'.','');
                // higt molecular c35
                $higt_pre = number_format($c35, 2,'.','');
                $smear_pre = number_format($c5+$c9+$c16+$c17+$c26+$c35, 2,'.','');
                $disk_pre = number_format($c2+$c3+$c8+$c9+$c12+$c19+$c20+$c27+$c32+$c33, 2,'.','');

                //-------------- -----------------------------------------------------------------------

                $arr_date[] = date('d-M-y',strtotime($value->save_date));

                $mdearray_elemental[] = $elemental_pre;
                $mdearray_acid[] = $acid_pre;
                $mdearray_oxygen[] = $oxygen_pre;
                $mdearray_aromatic[] = $aromatic_pre;
                $mdearray_aliphati[] = $aliphati_pre;
                $mdearray_low[] = $low_pre;
                $mdearray_higt[] = $higt_pre;
                $mdearray_smear[] = $smear_pre;
                $mdearray_disk[] = $disk_pre; 


                $mdearray_elemental_loop_main[] = $elemental_pre;
                $mdearray_acid_loop_main[] = $acid_pre;
                $mdearray_oxygen_loop_main[] = $oxygen_pre;
                $mdearray_aromatic_loop_main[] = $aromatic_pre;
                $mdearray_aliphati_loop_main[] = $aliphati_pre;
                $mdearray_low_loop_main[] = $low_pre;
                $mdearray_higt_loop_main[] = $higt_pre;
                $mdearray_smear_loop_main[] = $smear_pre;
                $mdearray_disk_loop_main[] = $disk_pre; 

                $z++;
            }

            for($fix = 0; $fix < 12; $fix++) {
                $cal_score[] = 0;
            }

            for ($hsp=13; $hsp <= $z; $hsp++) { 
                
                $mdearray_elemental_loop = $mdearray_elemental_loop_main;
                $mdearray_acid_loop =  $mdearray_acid_loop_main;
                $mdearray_oxygen_loop =  $mdearray_oxygen_loop_main;
                $mdearray_aromatic_loop =  $mdearray_aromatic_loop_main;
                $mdearray_aliphati_loop =  $mdearray_aliphati_loop_main;
                $mdearray_low_loop =  $mdearray_low_loop_main;
                $mdearray_higt_loop =  $mdearray_higt_loop_main;
                $mdearray_smear_loop =  $mdearray_smear_loop_main;
                $mdearray_disk_loop =  $mdearray_disk_loop_main;
                
                $removedex = ($hsp-13);
                for ($p=$removedex; $p > 0; $p--) { 
                    $int2 = $p-1;
                    unset($mdearray_elemental_loop[$int2]);
                    unset($mdearray_acid_loop[$int2]);
                    unset($mdearray_oxygen_loop[$int2]);
                    unset($mdearray_aromatic_loop[$int2]);
                    unset($mdearray_aliphati_loop[$int2]);
                    unset($mdearray_low_loop[$int2]);
                    unset($mdearray_higt_loop[$int2]);
                    unset($mdearray_smear_loop[$int2]);
                    unset($mdearray_disk_loop[$int2]);

                }

                $mdearray_elemental_loop2 = [];
                $mdearray_acid_loop2 = [];
                $mdearray_oxygen_loop2 = [];
                $mdearray_aromatic_loop2 = [];
                $mdearray_aliphati_loop2 = [];
                $mdearray_low_loop2 = [];
                $mdearray_higt_loop2 = [];
                $mdearray_smear_loop2 = [];
                $mdearray_disk_loop2 = [];
                
                $cat = 0;
                foreach ($mdearray_elemental_loop as $value) {
                    if($cat < 13){
                        $mdearray_elemental_loop2[] = $value;
                    }
                    $cat++;
                } 

                $cac = 0;
                foreach ($mdearray_acid_loop as $value) {
                    if($cac < 13){
                        $mdearray_acid_loop2[] = $value;
                    }
                    $cac++;
                }

                $cox = 0;
                foreach ($mdearray_oxygen_loop as $value) {
                    if($cox < 13){
                        $mdearray_oxygen_loop2[] = $value;
                    }
                    $cox++;
                }
                
                $car = 0;
                foreach ($mdearray_aromatic_loop as $value) {
                    if($car < 13){
                        $mdearray_aromatic_loop2[] = $value;
                    }
                    $car++;
                }

                $cal = 0;
                foreach ($mdearray_aliphati_loop as $value) {
                    if($cal < 13){
                        $mdearray_aliphati_loop2[] = $value;
                    }
                    $cal++;
                }

                $clo = 0;
                foreach ($mdearray_low_loop as $value) {
                    if($clo < 13){
                        $mdearray_low_loop2[] = $value;
                    }
                    $clo++;
                }

                $chi = 0;
                foreach ($mdearray_higt_loop as $value) {
                    if($chi < 13){
                        $mdearray_higt_loop2[] = $value;
                    }
                    $chi++;
                }

                $csm = 0;
                foreach ($mdearray_smear_loop as $value) {
                    if($csm < 13){
                        $mdearray_smear_loop2[] = $value;
                    }
                    $csm++;
                }

                $cdi = 0;
                foreach ($mdearray_disk_loop as $value) {
                    if($cdi < 13){
                        $mdearray_disk_loop2[] = $value;
                    }
                    $cdi++;
                } 

                $mdearray_elemental_loop = [];
                $mdearray_acid_loop = [];
                $mdearray_oxygen_loop = [];
                $mdearray_aromatic_loop = [];
                $mdearray_aliphati_loop = [];
                $mdearray_low_loop = [];
                $mdearray_higt_loop = [];
                $mdearray_smear_loop = [];
                $mdearray_disk_loop = [];

                $mdearray_elemental_loop = $mdearray_elemental_loop2;
                $mdearray_acid_loop = $mdearray_acid_loop2;
                $mdearray_oxygen_loop = $mdearray_oxygen_loop2;
                $mdearray_aromatic_loop = $mdearray_aromatic_loop2;
                $mdearray_aliphati_loop = $mdearray_aliphati_loop2;
                $mdearray_low_loop = $mdearray_low_loop2;
                $mdearray_higt_loop = $mdearray_higt_loop2;
                $mdearray_smear_loop = $mdearray_smear_loop2;
                $mdearray_disk_loop = $mdearray_disk_loop2;
                
                $wwk_med_elemental =  medians($mdearray_elemental_loop);
                $wwk_med_acid =  medians($mdearray_acid_loop);
                $wwk_med_oxygen =  medians($mdearray_oxygen_loop);
                $wwk_med_aromatic =  medians($mdearray_aromatic_loop);
                $wwk_med_aliphati =  medians($mdearray_aliphati_loop);
                $wwk_med_low =  medians($mdearray_low_loop);
                $wwk_med_higt =  medians($mdearray_higt_loop);
                $wwk_med_smear =  medians($mdearray_smear_loop);
                $wwk_med_disk =  medians($mdearray_disk_loop);
                
                //----------------------------- 3 ---------------------------------------

                $base_sigma_elemental = stdevs($mdearray_elemental_loop);
                $base_sigma_acid = stdevs($mdearray_acid_loop);
                $base_sigma_oxygen = stdevs($mdearray_oxygen_loop);
                $base_sigma_aromatic = stdevs($mdearray_aromatic_loop);
                $base_sigma_aliphati = stdevs($mdearray_aliphati_loop);
                $base_sigma_low = stdevs($mdearray_low_loop);
                $base_sigma_higt = stdevs($mdearray_higt_loop);
                $base_sigma_smear = stdevs($mdearray_smear_loop);
                $base_sigma_disk = stdevs($mdearray_disk_loop);
                
                //----------------------------- 4 ---------------------------------------

                end($mdearray_elemental_loop); 
                $key = key($mdearray_elemental_loop);
                
                $Delta_elemental = $mdearray_elemental_loop[$key] - $wwk_med_elemental;
                $Delta_acid = $mdearray_acid_loop[$key] - $wwk_med_acid;
                $Delta_oxygen = $mdearray_oxygen_loop[$key] - $wwk_med_oxygen;
                $Delta_aromatic = $mdearray_aromatic_loop[$key] - $wwk_med_aromatic;
                $Delta_aliphati = $mdearray_aliphati_loop[$key] - $wwk_med_aliphati;
                $Delta_low = $mdearray_low_loop[$key] - $wwk_med_low;
                $Delta_higt = $mdearray_higt_loop[$key] - $wwk_med_higt;
                $Delta_smear = $mdearray_smear_loop[$key] - $wwk_med_smear;
                $Delta_disk = $mdearray_disk_loop[$key] - $wwk_med_disk;

                //----------------------------- 5 ---------------------------------------

                $sigma_elemental = $Delta_elemental / $base_sigma_elemental;
                $sigma_acid = $Delta_acid / $base_sigma_acid;
                $sigma_oxygen = $Delta_oxygen / $base_sigma_oxygen;
                $sigma_aromatic = $Delta_aromatic / $base_sigma_aromatic;
                $sigma_aliphati = $Delta_aliphati / $base_sigma_aliphati;
                $sigma_low = $Delta_low / $base_sigma_low;
                $sigma_higt = $Delta_higt / $base_sigma_higt;
                $sigma_smear = $Delta_smear / $base_sigma_smear;
                $sigma_disk = $Delta_disk / $base_sigma_disk;

                // -------------- check alert -------------------
                    
                if ($sigma_elemental > 3) { $alert++; }
                if ($sigma_acid > 3) { $alert++; }
                if ($sigma_oxygen > 3) { $alert++; }
                if ($sigma_aromatic > 3) { $alert++; }
                if ($sigma_aliphati > 3) { $alert++; }
                if ($sigma_low > 3) { $alert++; }
                if ($sigma_higt > 3) { $alert++; }
                if ($sigma_smear > 3) { $alert++; }
                if ($sigma_disk > 3) { $alert++; }
                
                if ($sigma_elemental < -3) { $alert++;   }
                if ($sigma_acid < -3) { $alert++; }
                if ($sigma_oxygen < -3) { $alert++; }
                if ($sigma_aromatic < -3) { $alert++; }
                if ($sigma_aliphati < -3) { $alert++; }
                if ($sigma_low < -3) { $alert++; }
                if ($sigma_higt < -3) { $alert++; }
                if ($sigma_smear < -3) { $alert++; }
                if ($sigma_disk < -3) { $alert++; }

                // ----------------------------------------------

            }
        }

        return $alert;
    }

    public function newoutputgraphratio2(Request $request)
    {  

        $graphdata = [];

        function medians($array)
        {
            rsort($array);
        
            $size = count($array);
            $middle = count($array) / 2; 

            if ($size  % 2 != 0){
                $median = $array[$middle]; 
            }
            else{
                $median1 = $array[$middle]; 
                $median2 = $array[$middle-1]; 
                $median = ($median1 + $median2) / 2;
            }
            return $median;
        }

        function stdevs($data)
        {
            $n = count($data);
            $mean = array_sum($data) / $n;
            $distance_sum = 0;

            foreach ($data as $i) {
            $distance_sum += ($i - $mean) ** 2;
            }
            $distance_sum = number_format($distance_sum, 2,'.','');

            $variance = $distance_sum / $n;
            $variance = number_format($variance, 2,'.','');

            $std_deviation = sqrt($variance);
            $std_deviation = number_format($std_deviation, 2,'.','');

            return $std_deviation; 
        }

        set_time_limit(1000);  

        $id = $request->id;
        $id_createlab = $request->id_createlab;

        $datestart = date("Y-m-d", strtotime("2022-12-01"));

        $a1 = DB::table('nt_model')->where('id_model', $id)->first();

        $datalabgcms = DB::table('nt_datacf_gcms')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_datacf_gcms.id_createlab')
        ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
        ->leftjoin('nt_outgassing_gcms','nt_outgassing_gcms.id_createlab','=','nt_oqa_lab.id_createlab')
        ->where('nt_oqa_lab.save_date','>=',$datestart)
        ->where('nt_oqa_lab.id_model',$a1->id_model)
        ->where('nt_oqa_lab.id_createlab','<=', $id_createlab)
        ->where('nt_oqa_lab.gcms_confirm_st',1)
        ->where('nt_oqa_lab.type_lab','LIKE','SGT_%')
        ->orderBy('nt_oqa_lab.save_date', 'asc')->get(); 

        $limitdate13 = count($datalabgcms)-14;
        $sum_warning = 0;
        $textname_warning = "";

        if(count($datalabgcms) > 12){ 

            $z = 0;

            $data = []; 
            $elemental_pre = 0;
            $acid_pre = 0;
            $oxygen_pre = 0;
            $aromatic_pre = 0;
            $aliphati_pre = 0;
            $low_pre = 0;
            $higt_pre = 0;
            $smear_pre = 0;
            $disk_pre = 0;

            $mdearray_elemental = array();
            $mdearray_acid = array();
            $mdearray_oxygen = array();
            $mdearray_aromatic = array();
            $mdearray_aliphati = array();
            $mdearray_low = array();
            $mdearray_shigt = array();
            $mdearray_smear = array();
            $mdearray_disk = array();

            $mdearray_elemental_loop = array();
            $mdearray_acid_loop = array();
            $mdearray_oxygen_loop = array();
            $mdearray_aromatic_loop = array();
            $mdearray_aliphati_loop = array();
            $mdearray_low_loop = array();
            $mdearray_shigt_loop = array();
            $mdearray_smear_loop = array();
            $mdearray_disk_loop = array();
            
            $wwk_med_elemental = array();
            $wwk_med_acid = array();
            $wwk_med_oxygen = array();
            $wwk_med_aromatic = array();
            $wwk_med_aliphati = array();
            $wwk_med_low = array();
            $wwk_med_higt = array();
            $wwk_med_smear = array();
            $wwk_med_disk = array();

            $base_sigma_elemental = array();
            $base_sigma_acid = array();
            $base_sigma_oxygen = array();
            $base_sigma_aromatic = array();
            $base_sigma_aliphati = array();
            $base_sigma_low = array();
            $base_sigma_higt = array();
            $base_sigma_smear = array();
            $base_sigma_disk = array();

            foreach ($datalabgcms as $value) {
    
                $id_createlab = $value->id_createlab;

                $c1 = number_format($value->c1, 2,'.','');
                $c2 = number_format($value->c2, 2,'.','');
                $c3 = number_format($value->c3, 2,'.','');
                $c4 = number_format($value->c4, 2,'.','');
                $c5 = number_format($value->c5, 2,'.','');
                $c5_1 = number_format($value->c5_1, 2,'.','');
                $c5_2 = number_format($value->c5_2, 2,'.','');
                $c5_3 = number_format($value->c5_3, 2,'.','');
                $c6 = number_format($value->c6, 2,'.','');
                $c6_1 = number_format($value->c6_1, 2,'.','');
                $c6_2 = number_format($value->c6_2, 2,'.','');
                $c7 = number_format($value->c7, 2,'.','');
                $c8 = number_format($value->c8, 2,'.','');
                $c8_1 = number_format($value->c8_1, 2,'.','');
                $c8_2 = number_format($value->c8_2, 2,'.','');
                $c9 = number_format($value->c9, 2,'.','');
                $c9_1 = number_format($value->c9_1, 2,'.','');
                $c9_2 = number_format($value->c9_2, 2,'.','');
                $c10 = number_format($value->c10, 2,'.','');
                $c11 = number_format($value->c11, 2,'.','');
                $c11_1 = number_format($value->c11_1, 2,'.','');
                $c11_2 = number_format($value->c11_2, 2,'.','');
                $c12 = number_format($value->c12, 2,'.','');
                $c13 = number_format($value->c13, 2,'.','');
                $c14 = number_format($value->c14, 2,'.','');
                $c15 = number_format($value->c15, 2,'.','');
                $c15_1 = number_format($value->c15_1, 2,'.','');
                $c15_2 = number_format($value->c15_2, 2,'.','');
                $c16 = number_format($value->c16, 2,'.','');
                $c17 = number_format($value->c17, 2,'.','');
                $c18 = number_format($value->c18, 2,'.','');
                $c19 = number_format($value->c19, 2,'.','');
                $c19_1 = number_format($value->c19_1, 2,'.','');
                $c19_2 = number_format($value->c19_2, 2,'.','');
                $c19_3 = number_format($value->c19_3, 2,'.','');
                $c20 = number_format($value->c20, 2,'.','');
                $c20_1 = number_format($value->c20_1, 2,'.','');
                $c21 = number_format($value->c21, 2,'.','');
                $c21_1 = number_format($value->c21_1, 2,'.','');
                $c22 = number_format($value->c22, 2,'.','');
                $c22_1 = number_format($value->c22_1, 2,'.','');
                $c22_1 = number_format($value->c22_2, 2,'.','');
                $c23 = number_format($value->c23, 2,'.','');
                $c23_1 = number_format($value->c23_1, 2,'.','');
                $c24 = number_format($value->c24, 2,'.','');
                $c24_1 = number_format($value->c24_1, 2,'.','');
                $c24_2 = number_format($value->c24_2, 2,'.','');
                $c25 = number_format($value->c25, 2,'.','');
                $c26 = number_format($value->c26, 2,'.','');
                $c27 = number_format($value->c27, 2,'.','');
                $c28 = number_format($value->c28, 2,'.','');
                $c29 = number_format($value->c29, 2,'.','');
                $c30 = number_format($value->c30, 2,'.','');
                $c31 = number_format($value->c31, 2,'.','');
                $c32 = number_format($value->c32, 2,'.','');
                $c33 = number_format($value->c33, 2,'.','');
                $c34 = number_format($value->c34, 2,'.','');
                $c35 = number_format($value->c35, 2,'.','');
                $total = number_format($value->total, 2,'.','');

                //-------------- Stap 1 => Category Sum-----------------------------------------------

                $elemental_pre = number_format($c1+$c2+$c3+$c4+$c8+$c9+$c10+$c16+$c17+$c28, 2,'.','');
                $acid_pre = number_format($c5+$c6+$c12+$c13, 2,'.','');
                $oxygen_pre = number_format($c7+$c19+$c21+$c22+$c23+$c24+$c29, 2,'.','');
                $aromatic_pre = number_format($c11+$c14+$c15+$c18+$c20+$c25+$c26, 2,'.','');
                $aliphati_pre = number_format($c27, 2,'.','');
                // low molecular c34
                $low_pre = number_format($c34, 2,'.','');
                // higt molecular c35
                $higt_pre = number_format($c35, 2,'.','');
                $smear_pre = number_format($c5+$c9+$c16+$c17+$c26+$c35, 2,'.','');
                $disk_pre = number_format($c2+$c3+$c8+$c9+$c12+$c19+$c20+$c27+$c32+$c33, 2,'.','');

                //-------------- -----------------------------------------------------------------------

                $arr_date[] = date('d-M-y',strtotime($value->save_date));

                $mdearray_elemental[] = $elemental_pre;
                $mdearray_acid[] = $acid_pre;
                $mdearray_oxygen[] = $oxygen_pre;
                $mdearray_aromatic[] = $aromatic_pre;
                $mdearray_aliphati[] = $aliphati_pre;
                $mdearray_low[] = $low_pre;
                $mdearray_higt[] = $higt_pre;
                $mdearray_smear[] = $smear_pre;
                $mdearray_disk[] = $disk_pre; 


                $mdearray_elemental_loop_main[] = $elemental_pre;
                $mdearray_acid_loop_main[] = $acid_pre;
                $mdearray_oxygen_loop_main[] = $oxygen_pre;
                $mdearray_aromatic_loop_main[] = $aromatic_pre;
                $mdearray_aliphati_loop_main[] = $aliphati_pre;
                $mdearray_low_loop_main[] = $low_pre;
                $mdearray_higt_loop_main[] = $higt_pre;
                $mdearray_smear_loop_main[] = $smear_pre;
                $mdearray_disk_loop_main[] = $disk_pre; 

                $z++;
            }

            //-------------- Stap 2 => 13 Week Median based on Category Sum ----------------------

            $removedex = ($z-13);
            for ($x=$removedex; $x > 0; $x--) { 
            $int = $x-1;
                unset($mdearray_elemental[$int]);
                unset($mdearray_acid[$int]);
                unset($mdearray_oxygen[$int]);
                unset($mdearray_aromatic[$int]);
                unset($mdearray_aliphati[$int]);
                unset($mdearray_low[$int]);
                unset($mdearray_higt[$int]);
                unset($mdearray_smear[$int]);
                unset($mdearray_disk[$int]);
            }
            $mdearray_elemental2 = [];
            $mdearray_acid2 = [];
            $mdearray_oxygen2 = [];
            $mdearray_aromatic2 = [];
            $mdearray_aliphati2 = [];
            $mdearray_low2 = [];
            $mdearray_higt2 = [];
            $mdearray_smear2 = [];
            $mdearray_disk2 = [];
            foreach ($mdearray_elemental as $value) {
                $mdearray_elemental2[] = $value; 
            }

            foreach ($mdearray_acid as $value) {
                $mdearray_acid2[] = $value;
            }

            foreach ($mdearray_oxygen as $value) {
                $mdearray_oxygen2[] = $value;
            }

            foreach ($mdearray_aromatic as $value) {
                $mdearray_aromatic2[] = $value;
            }

            foreach ($mdearray_aliphati as $value) {
                $mdearray_aliphati2[] = $value;
            }

            foreach ($mdearray_low as $value) {
                $mdearray_low2[] = $value;
            }

            foreach ($mdearray_higt as $value) {
                $mdearray_higt2[] = $value;
            }

            foreach ($mdearray_smear as $value) {
                $mdearray_smear2[] = $value;
            }

            foreach ($mdearray_disk as $value) {
                $mdearray_disk2[] = $value;
            }


            $mdearray_elemental = [];
            $mdearray_acid = [];
            $mdearray_oxygen = [];
            $mdearray_aromatic = [];
            $mdearray_aliphati = [];
            $mdearray_low = [];
            $mdearray_higt = [];
            $mdearray_smear = [];
            $mdearray_disk = [];

            $mdearray_elemental = $mdearray_elemental2;
            $mdearray_acid = $mdearray_acid2;
            $mdearray_oxygen = $mdearray_oxygen2;
            $mdearray_aromatic = $mdearray_aromatic2;
            $mdearray_aliphati = $mdearray_aliphati2;
            $mdearray_low = $mdearray_low2;
            $mdearray_higt = $mdearray_higt2;
            $mdearray_smear = $mdearray_smear2;
            $mdearray_disk = $mdearray_disk2;

            $wwk_med_elemental =  medians($mdearray_elemental);
            $wwk_med_acid =  medians($mdearray_acid);
            $wwk_med_oxygen =  medians($mdearray_oxygen);
            $wwk_med_aromatic =  medians($mdearray_aromatic);
            $wwk_med_aliphati =  medians($mdearray_aliphati);
            $wwk_med_low =  medians($mdearray_low);
            $wwk_med_higt =  medians($mdearray_higt);
            $wwk_med_smear =  medians($mdearray_smear);
            $wwk_med_disk =  medians($mdearray_disk);
            
            //-------------- Stap 3 => 13 Week Sigma based on Category Sum ----------------------

            $base_sigma_elemental = stdevs($mdearray_elemental);
            $base_sigma_acid = stdevs($mdearray_acid);
            $base_sigma_oxygen = stdevs($mdearray_oxygen);
            $base_sigma_aromatic = stdevs($mdearray_aromatic);
            $base_sigma_aliphati = stdevs($mdearray_aliphati);
            $base_sigma_low = stdevs($mdearray_low);
            $base_sigma_higt = stdevs($mdearray_higt);
            $base_sigma_smear = stdevs($mdearray_smear);
            $base_sigma_disk = stdevs($mdearray_disk);

            //--------------------- Stap 4 => Delta = Individual - Midian ----------------------

            end($mdearray_elemental); 
            $key = key($mdearray_elemental);

            $Delta_elemental = $mdearray_elemental[$key] - $wwk_med_elemental;
            $Delta_acid = $mdearray_acid[$key] - $wwk_med_acid;
            $Delta_oxygen = $mdearray_oxygen[$key] - $wwk_med_oxygen;
            $Delta_aromatic = $mdearray_aromatic[$key] - $wwk_med_aromatic;
            $Delta_aliphati = $mdearray_aliphati[$key] - $wwk_med_aliphati;
            $Delta_low = $mdearray_low[$key] - $wwk_med_low;
            $Delta_higt = $mdearray_higt[$key] - $wwk_med_higt;
            $Delta_smear = $mdearray_smear[$key] - $wwk_med_smear;
            $Delta_disk = $mdearray_disk[$key] - $wwk_med_disk;

            //--------------------- Stap 5 => Sigma Ratio = Delta/sigma --------------------------

            $sigma_elemental = number_format( ($Delta_elemental / $base_sigma_elemental), 2,'.','');
            $sigma_acid = number_format( ($Delta_acid / $base_sigma_acid), 2,'.','');
            $sigma_oxygen = number_format( ($Delta_oxygen / $base_sigma_oxygen), 2,'.','');
            $sigma_aromatic = number_format( ($Delta_aromatic / $base_sigma_aromatic), 2,'.','');
            $sigma_aliphati = number_format( ($Delta_aliphati / $base_sigma_aliphati), 2,'.','');
            $sigma_low = number_format( ($Delta_low / $base_sigma_low), 2,'.','');
            $sigma_higt = number_format( ($Delta_higt / $base_sigma_higt), 2,'.','');
            $sigma_smear = number_format( ($Delta_smear / $base_sigma_smear), 2,'.','');
            $sigma_disk = number_format( ($Delta_disk / $base_sigma_disk), 2,'.','');

            //------------------------------ Health Score Percentile ----------------------------

            $score[] = $sigma_elemental;
            $score[] = $sigma_acid;
            $score[] = $sigma_oxygen;
            $score[] = $sigma_aromatic;
            $score[] = $sigma_aliphati;
            $score[] = $sigma_low;
            $score[] = $sigma_higt;
            $score[] = $sigma_smear;
            $score[] = $sigma_disk;


            for($fix = 0; $fix < 12; $fix++) {
                $cal_score[] = 0;
            }

            for ($hsp=13; $hsp <= $z; $hsp++) { 
                
                $mdearray_elemental_loop = $mdearray_elemental_loop_main;
                $mdearray_acid_loop =  $mdearray_acid_loop_main;
                $mdearray_oxygen_loop =  $mdearray_oxygen_loop_main;
                $mdearray_aromatic_loop =  $mdearray_aromatic_loop_main;
                $mdearray_aliphati_loop =  $mdearray_aliphati_loop_main;
                $mdearray_low_loop =  $mdearray_low_loop_main;
                $mdearray_higt_loop =  $mdearray_higt_loop_main;
                $mdearray_smear_loop =  $mdearray_smear_loop_main;
                $mdearray_disk_loop =  $mdearray_disk_loop_main;
                
                $removedex = ($hsp-13);
                for ($p=$removedex; $p > 0; $p--) { 
                    $int2 = $p-1;
                    unset($mdearray_elemental_loop[$int2]);
                    unset($mdearray_acid_loop[$int2]);
                    unset($mdearray_oxygen_loop[$int2]);
                    unset($mdearray_aromatic_loop[$int2]);
                    unset($mdearray_aliphati_loop[$int2]);
                    unset($mdearray_low_loop[$int2]);
                    unset($mdearray_higt_loop[$int2]);
                    unset($mdearray_smear_loop[$int2]);
                    unset($mdearray_disk_loop[$int2]);

                }
    
                $mdearray_elemental_loop2 = [];
                $mdearray_acid_loop2 = [];
                $mdearray_oxygen_loop2 = [];
                $mdearray_aromatic_loop2 = [];
                $mdearray_aliphati_loop2 = [];
                $mdearray_low_loop2 = [];
                $mdearray_higt_loop2 = [];
                $mdearray_smear_loop2 = [];
                $mdearray_disk_loop2 = [];
                
                $cat = 0;
                foreach ($mdearray_elemental_loop as $value) {
                    if($cat < 13){
                        $mdearray_elemental_loop2[] = $value;
                    }
                    $cat++;
                } 

                $cac = 0;
                foreach ($mdearray_acid_loop as $value) {
                    if($cac < 13){
                        $mdearray_acid_loop2[] = $value;
                    }
                    $cac++;
                }

                $cox = 0;
                foreach ($mdearray_oxygen_loop as $value) {
                    if($cox < 13){
                        $mdearray_oxygen_loop2[] = $value;
                    }
                    $cox++;
                }
                
                $car = 0;
                foreach ($mdearray_aromatic_loop as $value) {
                    if($car < 13){
                        $mdearray_aromatic_loop2[] = $value;
                    }
                    $car++;
                }

                $cal = 0;
                foreach ($mdearray_aliphati_loop as $value) {
                    if($cal < 13){
                        $mdearray_aliphati_loop2[] = $value;
                    }
                    $cal++;
                }

                $clo = 0;
                foreach ($mdearray_low_loop as $value) {
                    if($clo < 13){
                        $mdearray_low_loop2[] = $value;
                    }
                    $clo++;
                }

                $chi = 0;
                foreach ($mdearray_higt_loop as $value) {
                    if($chi < 13){
                        $mdearray_higt_loop2[] = $value;
                    }
                    $chi++;
                }

                $csm = 0;
                foreach ($mdearray_smear_loop as $value) {
                    if($csm < 13){
                        $mdearray_smear_loop2[] = $value;
                    }
                    $csm++;
                }

                $cdi = 0;
                foreach ($mdearray_disk_loop as $value) {
                    if($cdi < 13){
                        $mdearray_disk_loop2[] = $value;
                    }
                    $cdi++;
                } 

                $mdearray_elemental_loop = [];
                $mdearray_acid_loop = [];
                $mdearray_oxygen_loop = [];
                $mdearray_aromatic_loop = [];
                $mdearray_aliphati_loop = [];
                $mdearray_low_loop = [];
                $mdearray_higt_loop = [];
                $mdearray_smear_loop = [];
                $mdearray_disk_loop = [];

                $mdearray_elemental_loop = $mdearray_elemental_loop2;
                $mdearray_acid_loop = $mdearray_acid_loop2;
                $mdearray_oxygen_loop = $mdearray_oxygen_loop2;
                $mdearray_aromatic_loop = $mdearray_aromatic_loop2;
                $mdearray_aliphati_loop = $mdearray_aliphati_loop2;
                $mdearray_low_loop = $mdearray_low_loop2;
                $mdearray_higt_loop = $mdearray_higt_loop2;
                $mdearray_smear_loop = $mdearray_smear_loop2;
                $mdearray_disk_loop = $mdearray_disk_loop2;
                
                $wwk_med_elemental =  medians($mdearray_elemental_loop);
                $wwk_med_acid =  medians($mdearray_acid_loop);
                $wwk_med_oxygen =  medians($mdearray_oxygen_loop);
                $wwk_med_aromatic =  medians($mdearray_aromatic_loop);
                $wwk_med_aliphati =  medians($mdearray_aliphati_loop);
                $wwk_med_low =  medians($mdearray_low_loop);
                $wwk_med_higt =  medians($mdearray_higt_loop);
                $wwk_med_smear =  medians($mdearray_smear_loop);
                $wwk_med_disk =  medians($mdearray_disk_loop);
                
                //----------------------------- 3 ---------------------------------------

                $base_sigma_elemental = stdevs($mdearray_elemental_loop);
                $base_sigma_acid = stdevs($mdearray_acid_loop);
                $base_sigma_oxygen = stdevs($mdearray_oxygen_loop);
                $base_sigma_aromatic = stdevs($mdearray_aromatic_loop);
                $base_sigma_aliphati = stdevs($mdearray_aliphati_loop);
                $base_sigma_low = stdevs($mdearray_low_loop);
                $base_sigma_higt = stdevs($mdearray_higt_loop);
                $base_sigma_smear = stdevs($mdearray_smear_loop);
                $base_sigma_disk = stdevs($mdearray_disk_loop);
                
                //----------------------------- 4 ---------------------------------------

                end($mdearray_elemental_loop); 
                $key = key($mdearray_elemental_loop);
                
                $Delta_elemental = $mdearray_elemental_loop[$key] - $wwk_med_elemental;
                $Delta_acid = $mdearray_acid_loop[$key] - $wwk_med_acid;
                $Delta_oxygen = $mdearray_oxygen_loop[$key] - $wwk_med_oxygen;
                $Delta_aromatic = $mdearray_aromatic_loop[$key] - $wwk_med_aromatic;
                $Delta_aliphati = $mdearray_aliphati_loop[$key] - $wwk_med_aliphati;
                $Delta_low = $mdearray_low_loop[$key] - $wwk_med_low;
                $Delta_higt = $mdearray_higt_loop[$key] - $wwk_med_higt;
                $Delta_smear = $mdearray_smear_loop[$key] - $wwk_med_smear;
                $Delta_disk = $mdearray_disk_loop[$key] - $wwk_med_disk;

                //----------------------------- 5 ---------------------------------------

                $sigma_elemental = $Delta_elemental / $base_sigma_elemental;
                $sigma_acid = $Delta_acid / $base_sigma_acid;
                $sigma_oxygen = $Delta_oxygen / $base_sigma_oxygen;
                $sigma_aromatic = $Delta_aromatic / $base_sigma_aromatic;
                $sigma_aliphati = $Delta_aliphati / $base_sigma_aliphati;
                $sigma_low = $Delta_low / $base_sigma_low;
                $sigma_higt = $Delta_higt / $base_sigma_higt;
                $sigma_smear = $Delta_smear / $base_sigma_smear;
                $sigma_disk = $Delta_disk / $base_sigma_disk;

                    // -------------- pot graph ---------------------
                    $graphdata['elemental'][] = number_format( ($sigma_elemental), 2,'.','');
                    $graphdata['acid'][] = number_format( ($sigma_acid), 2,'.','');
                    $graphdata['oxygen'][] = number_format( ($sigma_oxygen), 2,'.','');
                    $graphdata['aromatic'][] = number_format( ($sigma_aromatic), 2,'.','');
                    $graphdata['aliphati'][] = number_format( ($sigma_aliphati), 2,'.','');
                    $graphdata['low'][] = number_format( ($sigma_low), 2,'.','');
                    $graphdata['higt'][] = number_format( ($sigma_higt), 2,'.','');
                    $graphdata['smear'][] = number_format( ($sigma_smear), 2,'.','');
                    $graphdata['disk'][] = number_format( ($sigma_disk), 2,'.','');
                    
                    // ----------------------------------------------
    
                //------------------- Health Score Percentile ----------------------------
                
                $score_loop = floatval($sigma_elemental)+floatval($sigma_acid)+floatval($sigma_oxygen)+floatval($sigma_aromatic)+floatval($sigma_aliphati)+floatval($sigma_low)+floatval($sigma_higt)+floatval($sigma_smear)+floatval($sigma_disk);
                $score_loop = number_format( ($score_loop), 2,'.','');
                
                $cal_score[] = $score_loop;
            }
        
            $cal_score[] = floatval(array_sum($score));
            function get_percentile($percentile, $array) {
                sort($array);
                $index = ($percentile/100) * count($array);
                if (floor($index) == $index) {
                    $result = ($array[$index-1] + $array[$index])/2;
                }
                else {
                    $result = $array[floor($index)];
                }
                return $result;
            }
            $HSP =  get_percentile( floatval(array_sum($score)), $cal_score);

            end($graphdata['elemental']);         
            $key = key($graphdata['elemental']); 

            $data['sum_elemental'] = $graphdata['elemental'][$key];
            $data['sum_acid'] = $graphdata['acid'][$key];
            $data['sum_oxygen'] = $graphdata['oxygen'][$key];
            $data['sum_aromatic'] = $graphdata['aromatic'][$key];
            $data['sum_aliphatic'] = $graphdata['aliphati'][$key];
            $data['sum_low'] = $graphdata['low'][$key];
            $data['sum_higt'] = $graphdata['higt'][$key];
            $data['sum_smear'] = $graphdata['smear'][$key];
            $data['sum_disk'] = $graphdata['disk'][$key];
            $data['hsp'] = $HSP;
 
            if($graphdata['elemental'][$key] >= 3 || $graphdata['elemental'][$key] <= -3) {
                $textname_warning = "Elemental (P, S, Si, N)";
                $sum_warning++;
                $data['tr'] = 1; 
            }
            if($graphdata['acid'][$key] >= 3 || $graphdata['acid'][$key] <= -3) {
                $textname_warning = "Acid/Acrylate";
                $sum_warning++;
                $data['tr'] = 2; 
            }
            if($graphdata['oxygen'][$key] >= 3 || $graphdata['oxygen'][$key] <= -3) {
                $textname_warning = "Oxygen Containing";
                $sum_warning++;
                $data['tr'] = 3; 
            }
            if($graphdata['aromatic'][$key] >= 3 || $graphdata['aromatic'][$key] <= -3) {
                $textname_warning = "Aromatic";
                $sum_warning++;
                $data['tr'] = 4; 
            }
            if($graphdata['aliphati'][$key] >= 3 || $graphdata['aliphati'][$key] <= -3) {
                $textname_warning = "Aliphatic";
                $sum_warning++;
                $data['tr'] = 5; 
            }
            if($graphdata['low'][$key] >= 3 || $graphdata['low'][$key] <= -3) {
                $textname_warning = "Total Low Molecular Weight Compound (C < 20)";
                $sum_warning++;
                $data['tr'] = 6; 
            }
            if($graphdata['higt'][$key] >= 3 || $graphdata['higt'][$key] <= -3) {
                $textname_warning = "Total High Molecular Weight Compound (C ≥ 20)";
                $sum_warning++;
                $data['tr'] = 7; 
            }
            if($graphdata['smear'][$key] >= 3 || $graphdata['smear'][$key] <= -3) {
                $textname_warning = "Smear Formers";
                $sum_warning++;
                $data['tr'] = 8; 
            }
            if($graphdata['disk'][$key] >= 3 || $graphdata['disk'][$key] <= -3) {
                $textname_warning = "Disk Detectable";
                $sum_warning++;
                $data['tr'] = 9; 
            }

            $data['sum_warning'] = $sum_warning;
            $data['textname_warning'] = $textname_warning; 

        }else{

            $data['sum_elemental'] = 0;
            $data['sum_acid'] = 0;
            $data['sum_oxygen'] = 0;
            $data['sum_aromatic'] = 0;
            $data['sum_aliphatic'] = 0;
            $data['sum_low'] = 0;
            $data['sum_higt'] = 0;
            $data['sum_smear'] = 0;
            $data['sum_disk'] = 0;
    
            $data['hsp'] = 0;

            $data['sum_warning'] = $sum_warning;
            $data['textname_warning'] = $textname_warning;
            $data['tr'] = 0;
        }
        

        return response()->json([
            'success' => 1,
            'data' => $data,
            'countrow' => count($datalabgcms),
        ]);

    }

    public function newoutputgraphratio3(Request $request)
    {
        set_time_limit(1000); 
         
        // $id = 323;
        $id = $request->id; 
        $id_createlab = $request->idcreatelab;

        $datestart = date("Y-m-d", strtotime("2022-12-01"));
        $a1 = DB::table('nt_model')->where('id_model', $id)->first();
        $text1 = $a1->name_model;
        $chktext1 = mb_substr($text1,0,-3);

        $file = storage_path('NHK-File/template/report/calculate_graph_ratio.xlsx');
        $spacename = str_replace(' ', '', $chktext1);
        $spacename = $spacename.'_'.$a1->part;
        $namefile = 'forpotgraph_'.$spacename;
        // $namefile = 'forpotgraph_'.$spacename.'_'.date("YmdHis");

        $checkfile =  storage_path('NHK-File/report/graphexportratio/'.$namefile.'.xlsx'  );
        if(file_exists($checkfile)){
            @unlink($checkfile);
        }

        $datalabgcms = DB::table('nt_datacf_gcms')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_datacf_gcms.id_createlab')
        ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
        ->leftjoin('nt_outgassing_gcms','nt_outgassing_gcms.id_createlab','=','nt_oqa_lab.id_createlab')
        ->where('nt_oqa_lab.save_date','>=',$datestart)
        ->where('nt_oqa_lab.id_model',$a1->id_model)
        ->where('nt_oqa_lab.id_createlab','<=', $id_createlab)
        ->where('nt_oqa_lab.gcms_confirm_st',1)
        ->where('nt_oqa_lab.type_lab','LIKE','SGT_%')
        ->orderBy('nt_oqa_lab.save_date', 'asc')->get(); 

        $limitdate13 = count($datalabgcms)-14;
        $sum_warning = 0;
        $textname_warning = "GCMS Sigma ratio";
        $data = [];
        $z = 'B';

        if(count($datalabgcms) > 12){ 

            for ($rl=1; $rl <= count($datalabgcms); $rl++) { 
                $z++;
            }
            $Column = $z;

            Excel::load($file, function($doc)use($datalabgcms,$spacename) {
                $sheet = $doc->getActiveSheet();

                $i = 'C';
                $row = 3;
                foreach ($datalabgcms as $value) {
        
                    $id_createlab = $value->id_createlab;

                    $c1 = number_format($value->c1, 2,'.','');
                    $c2 = number_format($value->c2, 2,'.','');
                    $c3 = number_format($value->c3, 2,'.','');
                    $c4 = number_format($value->c4, 2,'.','');
                    $c5 = number_format($value->c5, 2,'.','');
                    $c5_1 = number_format($value->c5_1, 2,'.','');
                    $c5_2 = number_format($value->c5_2, 2,'.','');
                    $c5_3 = number_format($value->c5_3, 2,'.','');
                    $c6 = number_format($value->c6, 2,'.','');
                    $c6_1 = number_format($value->c6_1, 2,'.','');
                    $c6_2 = number_format($value->c6_2, 2,'.','');
                    $c7 = number_format($value->c7, 2,'.','');
                    $c8 = number_format($value->c8, 2,'.','');
                    $c8_1 = number_format($value->c8_1, 2,'.','');
                    $c8_2 = number_format($value->c8_2, 2,'.','');
                    $c9 = number_format($value->c9, 2,'.','');
                    $c9_1 = number_format($value->c9_1, 2,'.','');
                    $c9_2 = number_format($value->c9_2, 2,'.','');
                    $c10 = number_format($value->c10, 2,'.','');
                    $c11 = number_format($value->c11, 2,'.','');
                    $c11_1 = number_format($value->c11_1, 2,'.','');
                    $c11_2 = number_format($value->c11_2, 2,'.','');
                    $c12 = number_format($value->c12, 2,'.','');
                    $c13 = number_format($value->c13, 2,'.','');
                    $c14 = number_format($value->c14, 2,'.','');
                    $c15 = number_format($value->c15, 2,'.','');
                    $c15_1 = number_format($value->c15_1, 2,'.','');
                    $c15_2 = number_format($value->c15_2, 2,'.','');
                    $c16 = number_format($value->c16, 2,'.','');
                    $c17 = number_format($value->c17, 2,'.','');
                    $c18 = number_format($value->c18, 2,'.','');
                    $c19 = number_format($value->c19, 2,'.','');
                    $c19_1 = number_format($value->c19_1, 2,'.','');
                    $c19_2 = number_format($value->c19_2, 2,'.','');
                    $c19_3 = number_format($value->c19_3, 2,'.','');
                    $c20 = number_format($value->c20, 2,'.','');
                    $c20_1 = number_format($value->c20_1, 2,'.','');
                    $c21 = number_format($value->c21, 2,'.','');
                    $c21_1 = number_format($value->c21_1, 2,'.','');
                    $c22 = number_format($value->c22, 2,'.','');
                    $c22_1 = number_format($value->c22_1, 2,'.','');
                    $c22_1 = number_format($value->c22_2, 2,'.','');
                    $c23 = number_format($value->c23, 2,'.','');
                    $c23_1 = number_format($value->c23_1, 2,'.','');
                    $c24 = number_format($value->c24, 2,'.','');
                    $c24_1 = number_format($value->c24_1, 2,'.','');
                    $c24_2 = number_format($value->c24_2, 2,'.','');
                    $c25 = number_format($value->c25, 2,'.','');
                    $c26 = number_format($value->c26, 2,'.','');
                    $c27 = number_format($value->c27, 2,'.','');
                    $c28 = number_format($value->c28, 2,'.','');
                    $c29 = number_format($value->c29, 2,'.','');
                    $c30 = number_format($value->c30, 2,'.','');
                    $c31 = number_format($value->c31, 2,'.','');
                    $c32 = number_format($value->c32, 2,'.','');
                    $c33 = number_format($value->c33, 2,'.','');
                    $c34 = number_format($value->c34, 2,'.','');
                    $c35 = number_format($value->c35, 2,'.','');
                    $total = number_format($value->total, 2,'.','');

                    //-------------- Stap 1 => Category Sum-----------------------------------------------

                    $elemental_pre = number_format($c1+$c2+$c3+$c4+$c8+$c9+$c10+$c16+$c17+$c28, 2,'.','');
                    $acid_pre = number_format($c5+$c6+$c12+$c13, 2,'.','');
                    $oxygen_pre = number_format($c7+$c19+$c21+$c22+$c23+$c24+$c29, 2,'.','');
                    $aromatic_pre = number_format($c11+$c14+$c15+$c18+$c20+$c25+$c26, 2,'.','');
                    $aliphati_pre = number_format($c27, 2,'.','');
                    // low molecular c34
                    $low_pre = number_format($c34, 2,'.','');
                    // higt molecular c35
                    $higt_pre = number_format($c35, 2,'.','');
                    $smear_pre = number_format($c5+$c9+$c16+$c17+$c26+$c35, 2,'.','');
                    $disk_pre = number_format($c2+$c3+$c8+$c9+$c12+$c19+$c20+$c27+$c32+$c33, 2,'.','');

                    // $sheet->setCellValue($i.'46', date('Y-m-d',strtotime($value->save_date)).' '.$value->lotnumber_lab );
                    $sheet->setCellValue($i.'46', date('Y-m-d',strtotime($value->save_date)) );
                    $sheet->setCellValue($i.'3', $elemental_pre );
                    $sheet->setCellValue($i.'4', $acid_pre );
                    $sheet->setCellValue($i.'5', $oxygen_pre );
                    $sheet->setCellValue($i.'6', $aromatic_pre );
                    $sheet->setCellValue($i.'7', $aliphati_pre );
                    $sheet->setCellValue($i.'8', $low_pre );
                    $sheet->setCellValue($i.'9', $higt_pre );
                    $sheet->setCellValue($i.'10', $smear_pre );
                    $sheet->setCellValue($i.'11', $disk_pre );

                    $i++;
                    $row++;
                }

            })->setFilename($namefile)->store('xlsx', storage_path('NHK-File/report/graphexportratio'));

            $getfileratio =  storage_path('NHK-File/report/graphexportratio/'.$namefile.'.xlsx');

            $reader = IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($getfileratio);
            $import = [$spreadsheet->setActiveSheetIndex(1)->toArray(null, true, true, true)];
            $excel = $import[0];
                        // บรรทัดที่ | คอลัมน์ที่
            $elmental = floatval( $excel[47][$Column]);
            $acid = floatval( $excel[48][$Column]);
            $oxygen = floatval( $excel[49][$Column]);
            $aromatic = floatval( $excel[50][$Column]);
            $aliphatic = floatval( $excel[51][$Column]);
            $low = floatval( $excel[52][$Column]);
            $high = floatval( $excel[53][$Column]);
            $smear = floatval( $excel[54][$Column]);
            $disk = floatval( $excel[55][$Column]);

            $hsp = floatval( $excel[58][$Column]);

            if($elmental >= 3 || $elmental <= -3){
                $data['tr'] = 1; 
            }
            if($acid >= 3 || $acid <= -3){
                $data['tr'] = 2; 
            }
            if($oxygen >= 3 || $oxygen <= -3){
                $data['tr'] = 3; 
            }
            if($aromatic >= 3 || $aromatic <= -3){
                $data['tr'] = 4; 
            }
            if($aliphatic >= 3 || $aliphatic <= -3){
                $data['tr'] = 5; 
            }
            if($low >= 3 || $low <= -3){
                $data['tr'] = 6;
            }
            if($high >= 3 || $high <= -3){
                $data['tr'] = 7; 
            }
            if($smear >= 3 || $smear <= -3){
                $data['tr'] = 8; 
            }
            if($disk >= 3 || $disk <= -3){
                $data['tr'] = 9; 
            }
           
            $data['sum_elemental'] = $elmental;
            $data['sum_acid'] = $acid;
            $data['sum_oxygen'] = $oxygen;
            $data['sum_aromatic'] = $aromatic;
            $data['sum_aliphatic'] = $aliphatic;
            $data['sum_low'] = $low;
            $data['sum_higt'] = $high;
            $data['sum_smear'] = $smear;
            $data['sum_disk'] = $disk;
    
            $data['hsp'] = $hsp;
            $data['tr'] = 0;
 
            $data['textname_warning'] = $textname_warning;
            $data['file_name_ratio'] = $namefile.'.xlsx';
  

        }

        return response()->json([
            'dategcms' => $a1->gcms_dc,
            'success' => 1,
            'data' => $data,
            'countrow' => (count($datalabgcms) > 12)? count($datalabgcms) : 0,
        ]);
        
    }


    public function newoutputgraphratio4(Request $request)
    {

        $getfileratio =  storage_path('NHK-File/report/graphexportratio/'.$request->name_ratio);

        $id = $request->id;
        $id_createlab = $request->idcreatelab;
        $datestart = date("Y-m-d H:i:s", strtotime("2022-12-01"));
        $a1 = DB::table('nt_model')->where('id_model', $id)->first(); 

        $Column = 'C';
        $countrow = 1;

        $datalabgcms = DB::table('nt_datacf_gcms')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_datacf_gcms.id_createlab')
        ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
        ->leftjoin('nt_outgassing_gcms','nt_outgassing_gcms.id_createlab','=','nt_oqa_lab.id_createlab')
        ->where('nt_oqa_lab.id_model',$a1->id_model)
        ->where('nt_oqa_lab.save_date','>=',$datestart)
        ->where('nt_oqa_lab.id_createlab','<=', $id_createlab)
        ->where('nt_oqa_lab.gcms_confirm_st',1)
        ->where('nt_oqa_lab.type_lab','LIKE','SGT_%')
        ->orderBy('nt_oqa_lab.save_date', 'asc')->get();

        for ($i=0; $i < count($datalabgcms)-1 ; $i++) { 
            $Column++;
            $countrow++;
        }

        $reader = IOFactory::createReader('Xlsx');
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($getfileratio);
        $import = [$spreadsheet->setActiveSheetIndex(1)->toArray(null, true, true, true)];
        $excel = $import[0];
        
        $y = 0;
        $data = [];
        $x = 0;
        
        foreach ($excel as $keym => $value) {
            if($y > 44){
                foreach ($value as $key =>  $val) {
                    // if($key >= 'O' && $key < $Column)
                    if($key >= 'O' && $key <= $Column)
                    {
                        if($keym == 47){
                           $data['elemental1'][] = $val;
                        }elseif ($keym == 48) {
                           $data['acid1'][] = $val;
                        }elseif ($keym == 49) {
                           $data['oxygen1'][] = $val;
                        }elseif ($keym == 50) {
                           $data['aromatic1'][] = $val;
                        }elseif ($keym == 51) {
                           $data['aliphatic1'][] = $val;
                        }elseif ($keym == 52) {
                           $data['low1'][] = $val;
                        }elseif ($keym == 53) {
                           $data['higt1'][] = $val;
                        }elseif ($keym == 54) {
                           $data['smear1'][] = $val;
                        }elseif ($keym == 55) {
                           $data['disck1'][] = $val;
                        }elseif ($keym == 46) {
                           $data['date'][] = $val;
                        } 
                    }
                }
            }
            
            $y++;
        }

        if(empty($data))
        {   
            $Column = 'C';
            for ($i=0; $i < count($datalabgcms) ; $i++) { 
                $Column++;
            }
            $y = 0;
            $data = [];
            
            foreach ($excel as $keym => $value) {
                if($y > 44){
                    foreach ($value as $key =>  $val) {
                        // if($key >= 'O' && $key < $Column)
                        if($key >= 'O' || $key < $Column)
                        {
                            if($keym == 47){
                               $data['elemental1'][] = $val;
                            }elseif ($keym == 48) {
                               $data['acid1'][] = $val;
                            }elseif ($keym == 49) {
                               $data['oxygen1'][] = $val;
                            }elseif ($keym == 50) {
                               $data['aromatic1'][] = $val;
                            }elseif ($keym == 51) {
                               $data['aliphatic1'][] = $val;
                            }elseif ($keym == 52) {
                               $data['low1'][] = $val;
                            }elseif ($keym == 53) {
                               $data['higt1'][] = $val;
                            }elseif ($keym == 54) {
                               $data['smear1'][] = $val;
                            }elseif ($keym == 55) {
                               $data['disck1'][] = $val;
                            }elseif ($keym == 46) {
                                $data['date'][] = $val;
                            } 
                        }
                    }
                }
                
                $y++;
            }            
        } 


        for ($i=0; $i < count($data['date']) ; $i++) { 
            $data['upper'][] = 3;
            $data['lower'][] = -3;
        }

        $count = count($data['elemental1']);

        return response()->json([
            'success' => 1,
            'data' => $data,
            'count' => $count,
            'rowdata' => count($datalabgcms),
        ]);
     
    }

    public function chkconfirmRatio($id_createlab)
    {
        set_time_limit(1000); 
         
        $datestart = date("Y-m-d", strtotime("2022-12-01"));
        $getlab =  DB::table('nt_oqa_lab')->where('id_createlab', $id_createlab)->first();
        $id = $getlab->id_model;
        $a1 = DB::table('nt_model')->where('id_model', $id)->first();
        $text1 = $a1->name_model;
        $chktext1 = mb_substr($text1,0,-3);

        $file = storage_path('NHK-File/template/report/calculate_graph_ratio.xlsx');
        $spacename = str_replace(' ', '', $chktext1);
        $spacename = $spacename.'_'.$a1->part;
        $namefile = 'forpotgraph_'.$spacename.'_'.date("Ymd");
 
        $checkfile =  storage_path('NHK-File/report/graphexportratio/'.$namefile.'.xlsx'  );
        if(file_exists($checkfile)){
            @unlink($checkfile);
        }

        $datalabgcms = DB::table('nt_datacf_gcms')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_datacf_gcms.id_createlab')
        ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
        ->leftjoin('nt_outgassing_gcms','nt_outgassing_gcms.id_createlab','=','nt_oqa_lab.id_createlab')
        ->where('nt_oqa_lab.save_date','>=',$datestart)
        ->where('nt_oqa_lab.id_model',$a1->id_model)
        ->where('nt_oqa_lab.id_createlab','<=', $id_createlab)
        ->where('nt_oqa_lab.gcms_confirm_st',1)
        ->where('nt_oqa_lab.type_lab','LIKE','SGT_%')
        ->orderBy('nt_oqa_lab.save_date', 'asc')->get(); 

        $limitdate13 = count($datalabgcms)-14; 
        $textname_warning = "";
        $data = [];
        $z = 'B';
        $sum_warning = 0;

        if(count($datalabgcms) > 12){ 

            for ($rl=1; $rl <= count($datalabgcms); $rl++) { 
                $z++;
            }
            $Column = $z;

            Excel::load($file, function($doc)use($datalabgcms,$spacename) {
                $sheet = $doc->getActiveSheet();

                $i = 'C';
                $row = 3;
                foreach ($datalabgcms as $value) {
        
                    $id_createlab = $value->id_createlab;

                    $c1 = number_format($value->c1, 2,'.','');
                    $c2 = number_format($value->c2, 2,'.','');
                    $c3 = number_format($value->c3, 2,'.','');
                    $c4 = number_format($value->c4, 2,'.','');
                    $c5 = number_format($value->c5, 2,'.','');
                    $c5_1 = number_format($value->c5_1, 2,'.','');
                    $c5_2 = number_format($value->c5_2, 2,'.','');
                    $c5_3 = number_format($value->c5_3, 2,'.','');
                    $c6 = number_format($value->c6, 2,'.','');
                    $c6_1 = number_format($value->c6_1, 2,'.','');
                    $c6_2 = number_format($value->c6_2, 2,'.','');
                    $c7 = number_format($value->c7, 2,'.','');
                    $c8 = number_format($value->c8, 2,'.','');
                    $c8_1 = number_format($value->c8_1, 2,'.','');
                    $c8_2 = number_format($value->c8_2, 2,'.','');
                    $c9 = number_format($value->c9, 2,'.','');
                    $c9_1 = number_format($value->c9_1, 2,'.','');
                    $c9_2 = number_format($value->c9_2, 2,'.','');
                    $c10 = number_format($value->c10, 2,'.','');
                    $c11 = number_format($value->c11, 2,'.','');
                    $c11_1 = number_format($value->c11_1, 2,'.','');
                    $c11_2 = number_format($value->c11_2, 2,'.','');
                    $c12 = number_format($value->c12, 2,'.','');
                    $c13 = number_format($value->c13, 2,'.','');
                    $c14 = number_format($value->c14, 2,'.','');
                    $c15 = number_format($value->c15, 2,'.','');
                    $c15_1 = number_format($value->c15_1, 2,'.','');
                    $c15_2 = number_format($value->c15_2, 2,'.','');
                    $c16 = number_format($value->c16, 2,'.','');
                    $c17 = number_format($value->c17, 2,'.','');
                    $c18 = number_format($value->c18, 2,'.','');
                    $c19 = number_format($value->c19, 2,'.','');
                    $c19_1 = number_format($value->c19_1, 2,'.','');
                    $c19_2 = number_format($value->c19_2, 2,'.','');
                    $c19_3 = number_format($value->c19_3, 2,'.','');
                    $c20 = number_format($value->c20, 2,'.','');
                    $c20_1 = number_format($value->c20_1, 2,'.','');
                    $c21 = number_format($value->c21, 2,'.','');
                    $c21_1 = number_format($value->c21_1, 2,'.','');
                    $c22 = number_format($value->c22, 2,'.','');
                    $c22_1 = number_format($value->c22_1, 2,'.','');
                    $c22_1 = number_format($value->c22_2, 2,'.','');
                    $c23 = number_format($value->c23, 2,'.','');
                    $c23_1 = number_format($value->c23_1, 2,'.','');
                    $c24 = number_format($value->c24, 2,'.','');
                    $c24_1 = number_format($value->c24_1, 2,'.','');
                    $c24_2 = number_format($value->c24_2, 2,'.','');
                    $c25 = number_format($value->c25, 2,'.','');
                    $c26 = number_format($value->c26, 2,'.','');
                    $c27 = number_format($value->c27, 2,'.','');
                    $c28 = number_format($value->c28, 2,'.','');
                    $c29 = number_format($value->c29, 2,'.','');
                    $c30 = number_format($value->c30, 2,'.','');
                    $c31 = number_format($value->c31, 2,'.','');
                    $c32 = number_format($value->c32, 2,'.','');
                    $c33 = number_format($value->c33, 2,'.','');
                    $c34 = number_format($value->c34, 2,'.','');
                    $c35 = number_format($value->c35, 2,'.','');
                    $total = number_format($value->total, 2,'.','');

                    //-------------- Stap 1 => Category Sum-----------------------------------------------

                    $elemental_pre = number_format($c1+$c2+$c3+$c4+$c8+$c9+$c10+$c16+$c17+$c28, 2,'.','');
                    $acid_pre = number_format($c5+$c6+$c12+$c13, 2,'.','');
                    $oxygen_pre = number_format($c7+$c19+$c21+$c22+$c23+$c24+$c29, 2,'.','');
                    $aromatic_pre = number_format($c11+$c14+$c15+$c18+$c20+$c25+$c26, 2,'.','');
                    $aliphati_pre = number_format($c27, 2,'.','');
                    // low molecular c34
                    $low_pre = number_format($c34, 2,'.','');
                    // higt molecular c35
                    $higt_pre = number_format($c35, 2,'.','');
                    $smear_pre = number_format($c5+$c9+$c16+$c17+$c26+$c35, 2,'.','');
                    $disk_pre = number_format($c2+$c3+$c8+$c9+$c12+$c19+$c20+$c27+$c32+$c33, 2,'.','');

                    $sheet->setCellValue($i.'46', date('Y-m-d',strtotime($value->save_date)).' '.$value->lotnumber_lab );
                    $sheet->setCellValue($i.'3', $elemental_pre );
                    $sheet->setCellValue($i.'4', $acid_pre );
                    $sheet->setCellValue($i.'5', $oxygen_pre );
                    $sheet->setCellValue($i.'6', $aromatic_pre );
                    $sheet->setCellValue($i.'7', $aliphati_pre );
                    $sheet->setCellValue($i.'8', $low_pre );
                    $sheet->setCellValue($i.'9', $higt_pre );
                    $sheet->setCellValue($i.'10', $smear_pre );
                    $sheet->setCellValue($i.'11', $disk_pre );

                    $i++;
                    $row++;
                }

            })->setFilename($namefile)->store('xlsx', storage_path('NHK-File/report/graphexportratio'));

            $getfileratio =  storage_path('NHK-File/report/graphexportratio/'.$namefile.'.xlsx');

            $reader = IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($getfileratio);
            $import = [$spreadsheet->setActiveSheetIndex(1)->toArray(null, true, true, true)];
            $excel = $import[0];
                        // บรรทัดที่ | คอลัมน์ที่
            $elmental = floatval( $excel[47][$Column]);
            $acid = floatval( $excel[48][$Column]);
            $oxygen = floatval( $excel[49][$Column]);
            $aromatic = floatval( $excel[50][$Column]);
            $aliphatic = floatval( $excel[51][$Column]);
            $low = floatval( $excel[52][$Column]);
            $high = floatval( $excel[53][$Column]);
            $smear = floatval( $excel[54][$Column]);
            $disk = floatval( $excel[55][$Column]);

            $hsp = floatval( $excel[58][$Column]);

            if($elmental >= 3 || $elmental <= -3){
                $sum_warning++;
            }
            if($acid >= 3 || $acid <= -3){
                $sum_warning++;
            }
            if($oxygen >= 3 || $oxygen <= -3){
                $sum_warning++;
            }
            if($aromatic >= 3 || $aromatic <= -3){
                $sum_warning++;
            }
            if($aliphatic >= 3 || $aliphatic <= -3){
                $sum_warning++;
            }
            if($low >= 3 || $low <= -3){
                $sum_warning++;
            }
            if($high >= 3 || $high <= -3){
                $sum_warning++;
            }
            if($smear >= 3 || $smear <= -3){
                $sum_warning++;
            }
            if($disk >= 3 || $disk <= -3){
                $sum_warning++;
            }
        }

        return $sum_warning;
        
    }

    public function slopediff_sem($idcreatelab,$class_index)
    {
        
        set_time_limit(3000); 
        $classindex = $class_index;
 
        $oqalot = DB::table('nt_oqa_lab')
        ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
        ->where('nt_oqa_lab.id_createlab',$idcreatelab)
        ->where('nt_oqa_lab.type_lab','LIKE','SGT_%')
        ->first();

        $oqadate = DB::table('nt_data_sem')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
        ->where('nt_data_sem.id_createlab',$idcreatelab)
        ->where('nt_data_sem.sem_st',1)
        ->where('nt_oqa_lab.type_lab','LIKE','SGT_%')
        ->first();

        if($oqalot !== null){

            $id_model = $oqalot->id_model;

            $datestart = date("Y-m-d", strtotime("2022-12-01"));
            $a1 = DB::table('nt_model')->where('id_model', $id_model)->first();
            $text1 = $a1->name_model;
            $chktext1 = mb_substr($text1,0,-3);

            $file = storage_path('NHK-File/template/report/slopediff_template.xlsx');
            $filetime = date('YmdHis');
            
            $spacename = str_replace(' ', '', $chktext1);
            $namefile = 'slopediff_sem_'.$class_index.'_'.$text1;

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

            if ($class_index == "Outgass") {
                $oqadate = DB::table('nt_data_gcms')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
                ->where('nt_data_gcms.id_createlab',$idcreatelab)->where('nt_data_gcms.gcms_st',1)
                ->first();

                if($oqadate !== null){
                    $yeardown = date('Y-m-d 00:00:00', strtotime($oqadate->save_date. ' - 1 year'));
                    $dateend = $oqadate->save_date;
                }else{
                    $yeardown = date('Y-m-d 00:00:00', strtotime($a1->save_date. ' - 1 year'));
                    $dateend = $a1->save_date;
                }
            }

    
            $semdatalab = DB::table('nt_data_sem')
            ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
            ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
            ->where('nt_oqa_lab.save_date','<=',$dateend)
            ->where('nt_oqa_lab.id_model',$id_model)
            ->where('nt_oqa_lab.sem_confirm_st',1)
            ->orderBy('nt_oqa_lab.save_date', 'desc')->get();
        
            $semdatalaball = DB::table('nt_data_sem')
            ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
            ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
            ->where('nt_oqa_lab.save_date','<=',$dateend)
            ->where('nt_oqa_lab.id_model',$id_model)
            ->where('nt_oqa_lab.sem_confirm_st',1);

            if ($class_index == "Outgass") {
                $semdatalaball = DB::table('nt_data_gcms')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
                ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                ->where('nt_oqa_lab.save_date','<=',$dateend)
                ->where('nt_oqa_lab.id_model',$id_model)
                ->where('nt_oqa_lab.gcms_confirm_st',1);
            }

            $semdatalaball =  $semdatalaball->where('nt_oqa_lab.save_date','>=',$yeardown)->orderBy('nt_oqa_lab.save_date', 'desc')->get();
            
            if($classindex != 5){
                if(count($semdatalaball) >= 7){
                    Excel::load($file, function ($doc)use( $semdatalaball, $classindex ) {
                        $i = 2; 
                        $sum_f_agv = 0;
                        $count_f_agv = 0;
                        $agv = 0;
                        $sheet = $doc->setActiveSheetIndex(0);
                        foreach ($semdatalaball as $key => $value) {
                            
                            $sheet->setCellValue('A'.$i, $value->lotnumber_lab);
                            $sheet->setCellValue('B'.$i, date('d-m-y',strtotime($value->save_date)));
                            $sheet->setCellValue('C'.$i, "Class". $classindex);

                            if($classindex != "Outgass"){
                                if($classindex == 1){
                                    $sheet->setCellValue('D'.$i, ($value->last_sem_class1 !== null)? number_format($value->last_sem_class1,1) : number_format($value->sem_class1,1) );                    
                                }elseif( $classindex == 2){
                                    $sheet->setCellValue('D'.$i, ($value->last_sem_class2 !== null)? number_format($value->last_sem_class2,1) : number_format($value->sem_class2,1) );
                                }elseif( $classindex == 3){
                                    $sheet->setCellValue('D'.$i, ($value->last_sem_class3 !== null)? number_format($value->last_sem_class3,1) : number_format($value->sem_class3,1) );
                                }elseif( $classindex == 3.1){
                                    $sheet->setCellValue('D'.$i, ($value->last_sem_class3_1 !== null)? number_format($value->last_sem_class3_1,1) : number_format($value->sem_class3_1,1) );
                                }elseif( $classindex == 3.2){
                                    $sheet->setCellValue('D'.$i, ($value->last_sem_class3_2 !== null)? number_format($value->last_sem_class3_2,1) : number_format($value->sem_class3_2,1) );
                                }elseif( $classindex == 3.3){
                                    $sheet->setCellValue('D'.$i, ($value->last_sem_class3_3 !== null)? number_format($value->last_sem_class3_3,1) : number_format($value->sem_class3_3,1) );
                                }elseif( $classindex == 4){
                                    $sheet->setCellValue('D'.$i, ($value->last_sem_class4 !== null)? number_format($value->last_sem_class4,1) : number_format($value->sem_class4,1) );
                                }elseif( $classindex == 5){
                                    $sheet->setCellValue('D'.$i, ($value->last_sem_class5 !== null)? number_format($value->last_sem_class5,1) : number_format($value->sem_class5,1) );
                                }elseif( $classindex == 5.1){
                                    $sheet->setCellValue('D'.$i, ($value->last_sem_class5_1 !== null)? number_format($value->last_sem_class5_1,1) : number_format($value->sem_class5_1,1) );
                                }elseif( $classindex == 6){
                                    $sheet->setCellValue('D'.$i, ($value->last_sem_class6 !== null)? number_format($value->last_sem_class6,1) : number_format($value->sem_class6,1) );
                                }
                            }else{
                                $sheet->setCellValue('D'.$i, number_format($value->gcms_outgassing) );                    
                            }

                            $i++;
                        } ;

                    })->setFilename($namefile)->store('xlsx', storage_path('NHK-File/report/calculation'));
            
                    $excel = [];
                    $readfile =  storage_path('NHK-File/report/calculation/'.$namefile.'.xlsx'  );
                    $spreadSheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($readfile);
                    
                    if ($class_index == "Outgass" || $classindex == 3.3) {
                        $excel = [
                            'SLOPE' => $spreadSheet->getActiveSheet()->getCell('N14')->getCalculatedValue(),
                            'DIFF' => $spreadSheet->getActiveSheet()->getCell('N22')->getCalculatedValue()
                        ];
                    }else{
                        $excel = [
                            'SLOPE' => $spreadSheet->getActiveSheet()->getCell('N10')->getCalculatedValue(),
                            'DIFF' => $spreadSheet->getActiveSheet()->getCell('N22')->getCalculatedValue()
                        ];
                    }
                }else{
                    $excel = [
                        'SLOPE' => "",
                        'DIFF' => ""
                    ];
                }  
            }elseif($classindex == 5){
                Excel::load($file, function ($doc)use( $semdatalaball, $classindex ) {
                    $i = 2; 
                    $sum_f_agv = 0;
                    $count_f_agv = 0;
                    $agv = 0;
                    $sheet = $doc->setActiveSheetIndex(0);
                    foreach ($semdatalaball as $key => $value) {
                        
                        $sheet->setCellValue('A'.$i, $value->lotnumber_lab);
                        $sheet->setCellValue('B'.$i, date('d-m-y',strtotime($value->save_date)));
                        $sheet->setCellValue('C'.$i, "Class". $classindex);

                        $sheet->setCellValue('D'.$i, ($value->last_sem_class5 !== null)? number_format($value->last_sem_class5,1) : number_format($value->sem_class5,1) );

                        $i++;
                    } ;

                })->setFilename($namefile)->store('xlsx', storage_path('NHK-File/report/calculation'));
        
                $excel = [];
                $readfile =  storage_path('NHK-File/report/calculation/'.$namefile.'.xlsx'  );
                $spreadSheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($readfile);
                
                $getslope = $spreadSheet->getActiveSheet()->getCell('N14')->getCalculatedValue();
                $getdiff = $spreadSheet->getActiveSheet()->getCell('N22')->getCalculatedValue();

                if(count($semdatalaball) >= 7){
                    $getslope = $spreadSheet->getActiveSheet()->getCell('N14')->getCalculatedValue();
                }else{
                    if ($getslope == 0) {
                        $getslope = '-';
                    }
                }

                $excel = [
                    'SLOPE' => $getslope,
                    'DIFF' => $getdiff
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

    public function slopediff_lpc($idcreatelab)
    { 
        set_time_limit(3000); 
 
        $oqalot = DB::table('nt_oqa_lab')
        ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
        ->where('nt_oqa_lab.id_createlab',$idcreatelab)
        ->where('nt_oqa_lab.type_lab','LIKE','SGT_%')
        ->first();

        $oqadate = DB::table('nt_data_lpc')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_lpc.id_createlab')
        ->where('nt_data_lpc.id_createlab',$idcreatelab)
        ->where('nt_oqa_lab.type_lab','LIKE','SGT_%')
        ->where('nt_data_lpc.lpc_st',1)
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

                try {
                    $n10 = $spreadSheet->getActiveSheet()->getCell('N10')->getCalculatedValue();
                } catch (\PhpOffice\PhpSpreadsheet\Calculation\Exception $e) {
                    $n10 = 0;
                }

                try {
                    $n22 = $spreadSheet->getActiveSheet()->getCell('N22')->getCalculatedValue();
                } catch (\PhpOffice\PhpSpreadsheet\Calculation\Exception $e) {
                    $n22 = 0;
                }

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

    public function Testratio4()
    {

    }

    public function outputgraphtest7(Request $request)
    {

        $oqa = DB::table('nt_oqa_lab')
        ->where('id_model',$request->input('id'))
        ->get();
        $oqalot = DB::table('nt_oqa_lab')
        ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
        ->where('nt_oqa_lab.id_createlab',$request->input('idcreatelab'))
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

        $oqadatesemtest = DB::table('nt_data_semtest')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_semtest.id_createlab')
        ->where('nt_data_semtest.id_createlab',$request->input('idcreatelab'))->where('nt_data_semtest.semtest_st',1)
        ->first();
               
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
                    //->where('nt_oqa_lab.save_date','<',$dateend)
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
                   $datalab = DB::table('nt_data_lpc')
                   ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_lpc.id_createlab')
                   ->join('nt_oqa_file','nt_oqa_file.id_createlab','=','nt_data_lpc.id_createlab')
                   //ma edit
                   // ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                   ->where('nt_oqa_lab.save_date','<=',$dateend)
                   ->where('nt_oqa_lab.id_model',$request->input('id'))
                   ->where('nt_oqa_lab.lpc_confirm_st',1)
                   ->where('nt_data_lpc.lpc_data','!=',null)
                   // ->limit($alarmtest2)->orderBy('nt_oqa_lab.save_date', 'desc')->get();
                   ->limit($alarmtest2)->orderBy('nt_data_lpc.id_createlab', 'desc')->get();

                   $datalaball = DB::table('nt_data_lpc')
                   ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_lpc.id_createlab')
                   ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                   ->where('nt_oqa_lab.save_date','<=',$dateend)
                   ->where('nt_oqa_lab.id_model',$request->input('id'))
                   ->where('nt_oqa_lab.lpc_confirm_st',1)
                   ->where('nt_data_lpc.lpc_data','!=',null);

                   if($request->chkpdf == 0 || $request->chkpdf == 2 )
                   {
                    if(isset($oqasum->alpc_sum)){
                        $oqasum_alpc_sum = intval($oqasum->alpc_sum);
                        if($oqasum_alpc_sum == 0){
                            $oqasum_alpc_sum = 1;
                        }
                    }else{
                        $oqasum_alpc_sum = 1;
                    }
                    //    $datalaball = $datalaball->limit(intval($oqasum->alpc_sum))->orderBy('nt_oqa_lab.save_date', 'desc')->get();
                       $datalaball = $datalaball->limit($oqasum_alpc_sum)->orderBy('nt_oqa_lab.save_date', 'desc')->get();
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
               
                   $datalabvs3 = DB::table('nt_data_lpc')
                   ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_lpc.id_createlab')
                   ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                   ->where('nt_oqa_lab.save_date','<=',$dateend)
                   ->where('nt_oqa_lab.save_date','>=',$yeardown)
                   ->where('nt_oqa_lab.id_model',$request->input('id'))
                   ->where('nt_oqa_lab.lpc_confirm_st',1)
                   ->where('nt_data_lpc.lpc_data','!=',null)
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

                $semdatalab = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                ->where('nt_oqa_lab.save_date','<=',$dateend)
                ->where('nt_oqa_lab.id_model',$request->input('id'))
                ->where('nt_oqa_lab.sem_confirm_st',1)
                ->limit($alarmtest2)->orderBy('nt_oqa_lab.save_date', 'desc')->get();
                
                $semdatalaball = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                ->where('nt_oqa_lab.save_date','<=',$dateend)
                ->where('nt_oqa_lab.id_model',$request->input('id'))
                ->where('nt_oqa_lab.sem_confirm_st',1);

                if($request->chkpdf == 0 || $request->chkpdf == 2)
                {
                        $oqasum_sem_sum = intval($oqasum->sem_sum);
                        if($oqasum_sem_sum == 0){
                            $oqasum_sem_sum = 1;
                        }
                        // //$datalaball = $datalaball->limit(intval($oqasum->alpc_sum))->orderBy('nt_oqa_lab.save_date', 'desc')->get();
                        //$semdatalaball =  $semdatalaball->limit(intval($oqasum->sem_sum))->orderBy('nt_oqa_lab.save_date', 'desc')->get();
                    $semdatalaball =  $semdatalaball->limit($oqasum_sem_sum)->orderBy('nt_oqa_lab.save_date', 'desc')->get();
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
           
           
                $semdatalabvs3 = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
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

                // ---------------------------------------------------------------------------
                // PERFORMANCE FIX (no behavior change):
                // Every one of the 10 sem-class blocks below (class1, class2, class3,
                // class3_1, class3_2, class3_3, class4, class5, class5_1, class6) looped
                // over the SAME $semdatalab collection and ran the SAME nt_model_history
                // lookup per item (only the field read off $datafirst differs per class).
                // That's 10 identical DB round-trips per item. We compute it once here and
                // every semclassX loop below now just reads $semDataFirstCache[$idx].
                // ---------------------------------------------------------------------------
                $semDataFirstCache = [];
                foreach ($semdatalab as $idx => $item) {
                    $stdate = date('Y-m-d', strtotime($item->save_date));
                    $datafirst = DB::table('nt_model_history')
                        ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model_history.id_model', $item->id_model)
                        ->where('nt_model_history.date_start', '<=', $stdate)
                        ->where('nt_model_history.date_end', '>=', $stdate)
                        ->where('nt_model_history.id_model_type', '=', 5)
                        ->first();
                    if ($datafirst == null) {
                        $data1 = DB::table('nt_model_history')
                            ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                            ->where('nt_model_history.id_model', $item->id_model)
                            ->where('nt_model_history.date_start', '>', $stdate)
                            ->where('nt_model_history.id_model_type', '=', 5)
                            ->orderby('nt_model_history.date_start', 'asc')
                            ->first();

                        $datafirst = DB::table('nt_model_history')
                            ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                            ->where('nt_model_history.id_model', $item->id_model)
                            ->where('nt_model_history.date_start', '<=', $data1->date_start)
                            ->where('nt_model_history.date_end', '>=', $data1->date_start)
                            ->where('nt_model_history.id_model_type', '=', 5)
                            ->first();
                    }
                    $semDataFirstCache[$idx] = $datafirst;
                }
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
                       foreach ( $semdatalab as $idx => $item){ 
                           $datafirst = $semDataFirstCache[$idx];
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
                               if($item->last_sem_class1 != null){
                                   if ($datafirst->class1 < $item->last_sem_class1) {
                                       $sem1higher++;        
                                   }else if ($datafirst->class1 > $item->last_sem_class1) {
                                       $sem1below++;      
                                   }else {
                                       $sem1higher--;
                                       $sem1below--;
                                   }
                               }else{
                                   if ($datafirst->class1 < $item->sem_class1) {
                                       $sem1higher++;        
                                   }else if ($datafirst->class1 > $item->sem_class1) {
                                       $sem1below++;      
                                   }else {
                                       $sem1higher--;
                                       $sem1below--;
                                   }
                               }

                           }
                       }
                   $sem1highervs3 = 0;
                   $sem1belowvs3 = 0;
                   if($semdatalabvs3[0]->last_sem_class1 !=null){
                       $datalabsem1 = $semdatalabvs3[0]->last_sem_class1;
                   }else{
                       $datalabsem1 = $semdatalabvs3[0]->sem_class1;
                   }
                   $iss = 0;
                   if($countsortdatalab >= $alarmtest3y){         
                       foreach ( $semdatalabvs3 as $item){ 
                           $iss++;
                           if( $iss <= $alarmtest3)
                           {
                               if($semdatalabvs3[$iss]->last_sem_class1 != null){
                                   if ($datalabsem1 < $semdatalabvs3[$iss]->last_sem_class1) {
                                       $sem1highervs3++;
                                       $datalabsem1 = $semdatalabvs3[$iss]->last_sem_class1;
                                   } 
                                   else if ($datalabsem1 > $semdatalabvs3[$iss]->last_sem_class1) {
                                       $sem1belowvs3++;
                                       $datalabsem1 = $semdatalabvs3[$iss]->last_sem_class1;
                                   } 
                                   else {
                                       $sem1highervs3--;
                                       $sem1belowvs3--;
                                   }
                               }else{
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
               foreach ( $semdatalab as $idx => $item){
                   $datafirst = $semDataFirstCache[$idx];
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
                       if($item->last_sem_class2 != null){
                           if ($datafirst->class2 < $item->last_sem_class2) {
                               $sem2higher++;
                               
                           } 
                           else if ($datafirst->class2 > $item->last_sem_class2) {
                               $sem2below++;
                           
                           } 
                           else {
                               $sem2higher--;
                               $sem2below--;
                           }
                       }else{
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
               }
           $sem2highervs3 = 0;
           $sem2belowvs3 = 0;
           if($countsortdatalab >= $alarmtest3y){
               if($semdatalabvs3[0]->last_sem_class2 != null){
                   $datalabsem2 = $semdatalabvs3[0]->last_sem_class2;
               }else{
                   $datalabsem2 = $semdatalabvs3[0]->sem_class2;
               }
               $iss = 0;
                   foreach ( $semdatalabvs3 as $item){ 
                       $iss++;
                       if( $iss <= $alarmtest3)
                       {

                           if($semdatalabvs3[$iss]->last_sem_class2 != null){
                               if ($datalabsem2 < $semdatalabvs3[$iss]->last_sem_class2) {
                                   $sem2highervs3++;
                                   $datalabsem2 = $semdatalabvs3[$iss]->last_sem_class2;
                               } 
                               else if ($datalabsem2 > $semdatalabvs3[$iss]->last_sem_class2) {
                                   $sem2belowvs3++;
                                   $datalabsem2 = $semdatalabvs3[$iss]->last_sem_class2;
                               } 
                               else {
                                   $sem2highervs3--;
                                   $sem2belowvs3--;
                               }
                           }else{
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
               foreach ( $semdatalab as $idx => $item){ 
                   $datafirst = $semDataFirstCache[$idx];
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

                       if($item->last_sem_class3 != null){

                           if ($datafirst->class3 < $item->last_sem_class3) {
                               $sem3higher++;
                               
                           } 
                           else if ($datafirst->class3 > $item->last_sem_class3) {
                               $sem3below++;
                           
                           } 
                           else {
                               $sem3higher--;
                               $sem3below--;
                           }
                       
                       }else{
                           
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
               }
           $sem3highervs3 = 0;
           $sem3belowvs3 = 0;
           if($countsortdatalab >= $alarmtest3y){
                   if($semdatalabvs3[0]->last_sem_class3 != null){
                       $datalabsem3 = $semdatalabvs3[0]->last_sem_class3;
                   }else{
                       $datalabsem3 = $semdatalabvs3[0]->sem_class3;
                   }
                   $iss = 0;
                   foreach ( $semdatalabvs3 as $item){ 
                       $iss++;
                       if( $iss <= $alarmtest3)
                       {
                           if($semdatalabvs3[$iss]->last_sem_class3 != null){
                               if ($datalabsem3 < $semdatalabvs3[$iss]->last_sem_class3) {
                                   $sem3highervs3++;
                                   $datalabsem3 = $semdatalabvs3[$iss]->last_sem_class3;
                               } 
                               else if ($datalabsem3 > $semdatalabvs3[$iss]->last_sem_class3) {
                                   $sem3belowvs3++;
                                   $datalabsem3 = $semdatalabvs3[$iss]->last_sem_class3;
                               } 
                               else {
                                   $sem3highervs3--;
                                   $sem3belowvs3--;
                               }
                           }else{
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
                   foreach ( $semdatalab as $idx => $item){ 
                       $datafirst = $semDataFirstCache[$idx];
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
                           if($item->last_sem_class3_1 != null){
                               if ($datafirst->class3_1 < $item->last_sem_class3_1) {
                                   $sem31higher++;
                                   
                               } 
                               else if ($datafirst->class3_1 > $item->last_sem_class3_1) {
                                   $sem31below++;
                               
                               } 
                               else {
                                   $sem31higher--;
                                   $sem31below--;
                               }
                           }else{
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
                   }

               $sem31highervs3 = 0;
               $sem31belowvs3 = 0;
               if($countsortdatalab >= $alarmtest3y){
                   if($semdatalabvs3[0]->last_sem_class3_1 != null){
                       $datalabsem31 = $semdatalabvs3[0]->last_sem_class3_1;
                   }else{
                       $datalabsem31 = $semdatalabvs3[0]->sem_class3_1;
                   }
                   $iss = 0;
                   foreach ( $semdatalabvs3 as $item){ 
                       $iss++;
                       if( $iss <= $alarmtest3)
                       {
                           if($semdatalabvs3[$iss]->last_sem_class3_1 != null){
                               if ($datalabsem31 < $semdatalabvs3[$iss]->last_sem_class3_1) {
                                   $sem31highervs3++;
                                   $datalabsem31 = $semdatalabvs3[$iss]->last_sem_class3_1;
                               } 
                               else if ($datalabsem31 > $semdatalabvs3[$iss]->last_sem_class3_1) {
                                   $sem31belowvs3++;
                                   $datalabsem31 = $semdatalabvs3[$iss]->last_sem_class3_1;
                               } 
                               else {
                                   $sem31highervs3--;
                                   $sem31belowvs3--;
                               }
                           }else{
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
                   foreach ( $semdatalab as $idx => $item){ 
                       $datafirst = $semDataFirstCache[$idx];
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
                           if($item->last_sem_class3_2  != null){
                               if ($datafirst->class3_2 < $item->last_sem_class3_2) {
                                   $sem32higher++;
                                   
                               } 
                               else if ($datafirst->class3_2 > $item->last_sem_class3_2) {
                                   $sem32below++;
                               
                               } 
                               else {
                                   $sem32higher--;
                                   $sem32below--;
                               }
                           }else{
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
                   }
               $sem32highervs3 = 0;
               $sem32belowvs3 = 0;
               if($countsortdatalab >= $alarmtest3y){
                   if($semdatalabvs3[0]->last_sem_class3_2  != null){
                       $datalabsem32 = $semdatalabvs3[0]->last_sem_class3_2;
                   }else{
                       $datalabsem32 = $semdatalabvs3[0]->sem_class3_2;
                   }

                   $iss = 0;
                   foreach ( $semdatalabvs3 as $item){ 
                       $iss++;
                       if( $iss <= $alarmtest3)
                       {
                           if($semdatalabvs3[$iss]->last_sem_class3_2  != null){

                               if ($datalabsem32 < $semdatalabvs3[$iss]->last_sem_class3_2) {
                                   $sem32highervs3++;
                                   $datalabsem32 = $semdatalabvs3[$iss]->last_sem_class3_2;
                               } 
                               else if ($datalabsem32 > $semdatalabvs3[$iss]->last_sem_class3_2) {
                                   $sem32belowvs3++;
                                   $datalabsem32 = $semdatalabvs3[$iss]->last_sem_class3_2;
                               } 
                               else {
                                   $sem32highervs3--;
                                   $sem32belowvs3--;
                               }

                           }else{

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
                   foreach ( $semdatalab as $idx => $item){ 
                       $datafirst = $semDataFirstCache[$idx];
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
                           if($item->last_sem_class3_3  != null){
                               if ($datafirst->class3_3 < $item->last_sem_class3_3) {
                                   $sem33higher++;
                                   
                               }else if ($datafirst->class3_3 > $item->last_sem_class3_3) {
                                   $sem33below++;
                               
                               }else {
                                   $sem33higher--;
                                   $sem33below--;
                               }
                           }else{
                               if ($datafirst->class3_3 < $item->sem_class3_3) {
                                   $sem33higher++;
                                   
                               }else if ($datafirst->class3_3 > $item->sem_class3_3) {
                                   $sem33below++;
                               
                               }else {
                                   $sem33higher--;
                                   $sem33below--;
                               }
                           }   
                       }
                   }

                   $sem33highervs3 = 0;
                   $sem33belowvs3 = 0;
                   if($countsortdatalab >= $alarmtest3y){
                       if($semdatalabvs3[0]->last_sem_class3_3  != null){
                           $datalabsem33 = $semdatalabvs3[0]->last_sem_class3_3;
                       }else{
                           $datalabsem33 = $semdatalabvs3[0]->sem_class3_3;
                       }
                       $iss = 0;
                       foreach ( $semdatalabvs3 as $item){ 
                       $iss++;
                       if( $iss <= $alarmtest3)
                       {
                           if($semdatalabvs3[$iss]->last_sem_class3_3  != null){
                               if ($datalabsem33 < $semdatalabvs3[$iss]->last_sem_class3_3) {
                                   $sem33highervs3++;
                                   $datalabsem33 = $semdatalabvs3[$iss]->last_sem_class3_3;
                               } 
                               else if ($datalabsem33 > $semdatalabvs3[$iss]->last_sem_class3_3) {
                                   $sem33belowvs3++;
                                   $datalabsem33 = $semdatalabvs3[$iss]->last_sem_class3_3;
                               } 
                               else {
                                   $sem33highervs3--;
                                   $sem33belowvs3--;
                               }
                           }else{
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
                       foreach ( $semdatalab as $idx => $item){
                           $datafirst = $semDataFirstCache[$idx];
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
                               if($item->last_sem_class4  != null){
                                   if ($datafirst->class4 < $item->last_sem_class4) {
                                       $sem4higher++;
                                       
                                   } 
                                   else if ($datafirst->class4 > $item->last_sem_class4) {
                                       $sem4below++;
                                   
                                   } 
                                   else {
                                       $sem4higher--;
                                       $sem4below--;
                                   }
                               }else{
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
                       }


                   $sem4highervs3 = 0;
                   $sem4belowvs3 = 0;
                   if($countsortdatalab >= $alarmtest3y){
                       if($semdatalabvs3[0]->last_sem_class4  != null){
                           $datalabsem4 = $semdatalabvs3[0]->last_sem_class4;
                       }else{
                           $datalabsem4 = $semdatalabvs3[0]->sem_class4;
                       }

                       $iss = 0;
                       foreach ( $semdatalabvs3 as $item){ 
                           $iss++;
                           if( $iss <= $alarmtest3)
                           {
                               if($semdatalabvs3[$iss]->last_sem_class4  != null){
                                   if ($datalabsem4 < $semdatalabvs3[$iss]->last_sem_class4) {
                                       $sem4highervs3++;
                                       $datalabsem4 = $semdatalabvs3[$iss]->last_sem_class4;
                                   } 
                                   else if ($datalabsem4 > $semdatalabvs3[$iss]->last_sem_class4) {
                                       $sem4belowvs3++;
                                       $datalabsem4 = $semdatalabvs3[$iss]->last_sem_class4;
                                   } 
                                   else {
                                       $sem4highervs3--;
                                       $sem4belowvs3--;
                                   }
                               }else{
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
                       foreach ( $semdatalab as $idx => $item){ 
                           $datafirst = $semDataFirstCache[$idx];
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
                               if($item->last_sem_class5  != null){
                                   if ($datafirst->class5 < $item->last_sem_class5) {
                                       $sem5higher++;
                                       
                                   } 
                                   else if ($datafirst->class5 > $item->last_sem_class5) {
                                       $sem5below++;
                                   
                                   } 
                                   else {
                                       $sem5higher--;
                                       $sem5below--;
                                   }
                               }else{
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
                       }
       

                   $sem5highervs3 = 0;
                   $sem5belowvs3 = 0;
                   if($countsortdatalab >= $alarmtest3y){
                   if($semdatalabvs3[0]->last_sem_class5  != null){
                       $datalabsem5 = $semdatalabvs3[0]->last_sem_class5;
                   }else{
                       $datalabsem5 = $semdatalabvs3[0]->sem_class5;
                   }
                   $iss = 0;
                   foreach ( $semdatalabvs3 as $item){ 
                       $iss++;
                       if( $iss <= $alarmtest3)
                       {
                           if($semdatalabvs3[$iss]->last_sem_class5  != null){
                           
                               if ($datalabsem5 < $semdatalabvs3[$iss]->last_sem_class5) {
                                   $sem5highervs3++;
                                   $datalabsem5 = $semdatalabvs3[$iss]->last_sem_class5;
                               } 
                               else if ($datalabsem5 > $semdatalabvs3[$iss]->last_sem_class5) {
                                   $sem5belowvs3++;
                                   $datalabsem5 = $semdatalabvs3[$iss]->last_sem_class5;
                               } 
                               else {
                                       $sem5highervs3--;
                                       $sem5belowvs3--;
                               }

                           }else{

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
                       foreach ( $semdatalab as $idx => $item){ 
                           $datafirst = $semDataFirstCache[$idx];
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
                               if($item->last_sem_class5_1  != null){
                                   if ($datafirst->class5_1 < $item->last_sem_class5_1) {
                                       $sem51higher++;
                                       
                                   } 
                                   else if ($datafirst->class5_1 > $item->last_sem_class5_1) {
                                       $sem51below++;
                                   
                                   } 
                                   else {
                                       $sem51higher--;
                                       $sem51below--;
                                   }
                               }else{
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
                       }
           

                   $sem51highervs3 = 0;
                   $sem51belowvs3 = 0;
                   if($countsortdatalab >= $alarmtest3y){
                       if($semdatalabvs3[0]->last_sem_class5_1  != null){
                           $datalabsem51 = $semdatalabvs3[0]->last_sem_class5_1;
                       }else{
                           $datalabsem51 = $semdatalabvs3[0]->sem_class5_1;
                       }
                       $iss = 0;
                       foreach ( $semdatalabvs3 as $item){ 
                           $iss++;
                           if( $iss <= $alarmtest3)
                           {
                               if($semdatalabvs3[$iss]->last_sem_class5_1 != null){
                                   if ($datalabsem51 < $semdatalabvs3[$iss]->last_sem_class5_1) {
                                       $sem51highervs3++;
                                       $datalabsem51 = $semdatalabvs3[$iss]->last_sem_class5_1;
                                   } 
                                   else if ($datalabsem51 > $semdatalabvs3[$iss]->last_sem_class5_1) {
                                       $sem51belowvs3++;
                                       $datalabsem51 = $semdatalabvs3[$iss]->last_sem_class5_1;
                                   } 
                                   else {
                                       $sem51highervs3--;
                                       $sem51belowvs3--;
                                   }
                               }else{
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
                           foreach ( $semdatalab as $idx => $item){ 
                               $datafirst = $semDataFirstCache[$idx];
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
                                   if($item->last_sem_class6  != null){
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
                           }
       
                       $sem6highervs3 = 0;
                       $sem6belowvs3 = 0;
                       if($countsortdatalab >= $alarmtest3y){
                           if($semdatalabvs3[0]->last_sem_class6  != null){
                               $datalabsem6 = $semdatalabvs3[0]->last_sem_class6;
                           }else{
                               $datalabsem6 = $semdatalabvs3[0]->sem_class6;
                           }
                           $iss = 0;
                           foreach ( $semdatalabvs3 as $item){ 
                               $iss++;
                               if( $iss <= $alarmtest3)
                               {
                                   if($semdatalabvs3[$iss]->last_sem_class6  != null){
                                       if ($datalabsem6 < $semdatalabvs3[$iss]->last_sem_class6) {
                                           $sem6highervs3++;
                                           $datalabsem6 = $semdatalabvs3[$iss]->last_sem_class6;
                                       } 
                                       else if ($datalabsem6 > $semdatalabvs3[$iss]->last_sem_class6) {
                                           $sem6belowvs3++;
                                           $datalabsem6 = $semdatalabvs3[$iss]->last_sem_class6;
                                       } 
                                       else {
                                           $sem6highervs3--;
                                           $sem6belowvs3--;
                                       }
                                   }else{
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
                       }
                       $chksemshow = 1;
                       //
                   
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
                   $icdatalab = DB::table('nt_data_ic')
                   ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ic.id_createlab')
                   ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                   ->where('nt_oqa_lab.save_date','<=',$dateend)
                   ->where('nt_oqa_lab.id_model',$request->input('id'))
                   ->where('nt_oqa_lab.ic_confirm_st',1)
                   ->limit($alarmtest2)->orderBy('nt_oqa_lab.save_date', 'desc')->get();

                   $icdatalaball = DB::table('nt_data_ic')
                   ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ic.id_createlab')
                   ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                   ->where('nt_oqa_lab.save_date','<=',$dateend)
                   ->where('nt_oqa_lab.id_model',$request->input('id'))
                   ->where('nt_oqa_lab.ic_confirm_st',1);
                   if($request->chkpdf == 0 || $request->chkpdf == 2)
                    {
                        $oqasum_ic_sum = intval($oqasum->ic_sum);
                        if($oqasum_ic_sum == 0){
                            $oqasum_ic_sum = 1;
                        }
                        // $icdatalaball =  $icdatalaball->limit(intval($oqasum->ic_sum))->orderBy('nt_oqa_lab.save_date', 'desc')->get();
                        $icdatalaball =  $icdatalaball->limit($oqasum_ic_sum)->orderBy('nt_oqa_lab.save_date', 'desc')->get();
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
                   $icdatalabvs3 = DB::table('nt_data_ic')
                   ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ic.id_createlab')
                   ->where('nt_oqa_lab.save_date','<=',$dateend)
                   ->where('nt_oqa_lab.id_model',$request->input('id'))
                   ->where('nt_oqa_lab.ic_confirm_st',1)
                   ->limit($alarmtest3)->orderBy('nt_oqa_lab.save_date', 'desc')->get();
                   $counticsortdatalab = count($icdatalabvs3);

                   // ---------------------------------------------------------------------------
                   // PERFORMANCE FIX (no behavior change): the anion / sulphat / cation blocks
                   // below all loop over the SAME $icdatalab collection with the SAME
                   // nt_model_history lookup per item. Compute it once here.
                   // ---------------------------------------------------------------------------
                   $icDataFirstCache = [];
                   foreach ($icdatalab as $idx => $item) {
                       $stdate = date('Y-m-d', strtotime($item->save_date));
                       $datafirst = DB::table('nt_model_history')
                           ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                           ->where('nt_model_history.id_model', $item->id_model)
                           ->where('nt_model_history.date_start', '<=', $stdate)
                           ->where('nt_model_history.date_end', '>=', $stdate)
                           ->where('nt_model_history.id_model_type', '=', 5)
                           ->first();
                       if ($datafirst == null) {
                           $data1 = DB::table('nt_model_history')
                               ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                               ->where('nt_model_history.id_model', $item->id_model)
                               ->where('nt_model_history.date_start', '>', $stdate)
                               ->where('nt_model_history.id_model_type', '=', 5)
                               ->orderby('nt_model_history.date_start', 'asc')
                               ->first();

                           $datafirst = DB::table('nt_model_history')
                               ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                               ->where('nt_model_history.id_model', $item->id_model)
                               ->where('nt_model_history.date_start', '<=', $data1->date_start)
                               ->where('nt_model_history.date_end', '>=', $data1->date_start)
                               ->where('nt_model_history.id_model_type', '=', 5)
                               ->first();
                       }
                       $icDataFirstCache[$idx] = $datafirst;
                   }
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
       
                   foreach ( $icdatalab as $idx => $item){ 
                       $datafirst = $icDataFirstCache[$idx];
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
                   foreach ( $icdatalab as $idx => $item){ 
                       $datafirst = $icDataFirstCache[$idx];
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

                   foreach ( $icdatalab as $idx => $item){
                       $datafirst = $icDataFirstCache[$idx];
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
               $gcmsdatalab = DB::table('nt_data_gcms')
               ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
               ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
               ->where('nt_oqa_lab.save_date','<=',$dateend)
               ->where('nt_oqa_lab.id_model',$request->input('id'))
               ->where('nt_oqa_lab.gcms_confirm_st',1)
               ->limit($alarmtest2)->orderBy('nt_oqa_lab.save_date', 'desc')->get();
               $gcmsdatalaball = DB::table('nt_data_gcms')
               ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
               ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
               ->where('nt_oqa_lab.save_date','<=',$dateend)
               ->where('nt_oqa_lab.id_model',$request->input('id'))
               ->where('nt_oqa_lab.gcms_confirm_st',1);
               if($request->chkpdf == 0 || $request->chkpdf == 2)
               {
                    $oqasum_gcms_sum = intval($oqasum->gcms_sum);
                    if($oqasum_gcms_sum == 0){
                        $oqasum_gcms_sum = 1;
                    }
                   //$datalaball = $datalaball->limit(intval($oqasum->alpc_sum))->orderBy('nt_oqa_lab.save_date', 'desc')->get();
                   //$gcmsdatalaball =  $gcmsdatalaball->limit(intval($oqasum->gcms_sum))->orderBy('nt_oqa_lab.save_date', 'desc')->get();
                   $gcmsdatalaball =  $gcmsdatalaball->limit($oqasum_gcms_sum)->orderBy('nt_oqa_lab.save_date', 'desc')->get();
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
               
               $gcmsdatalabvs3 = DB::table('nt_data_gcms')
               ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
               ->where('nt_oqa_lab.save_date','<=',$dateend)
               ->where('nt_oqa_lab.id_model',$request->input('id'))
               ->where('nt_oqa_lab.gcms_confirm_st',1)
               ->limit($alarmtest3y)->orderBy('nt_oqa_lab.save_date', 'desc')->get();
               $countgcmssortdatalab = count($gcmsdatalabvs3);
               // $gcmsyeardown = date('Y-m-d 00:00:00', strtotime($dateend. ' - 1 year'));

               // ---------------------------------------------------------------------------
               // PERFORMANCE FIX (no behavior change): the outgassing / smear / low_mw /
               // high_mw blocks below all loop over the SAME $gcmsdatalab collection with
               // the SAME nt_model_history lookup per item. Compute it once here.
               // ---------------------------------------------------------------------------
               $gcmsDataFirstCache = [];
               foreach ($gcmsdatalab as $idx => $item) {
                   $stdate = date('Y-m-d', strtotime($item->save_date));
                   $datafirst = DB::table('nt_model_history')
                       ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                       ->where('nt_model_history.id_model', $item->id_model)
                       ->where('nt_model_history.date_start', '<=', $stdate)
                       ->where('nt_model_history.date_end', '>=', $stdate)
                       ->where('nt_model_history.id_model_type', '=', 5)
                       ->first();
                   if ($datafirst == null) {
                       $data1 = DB::table('nt_model_history')
                           ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                           ->where('nt_model_history.id_model', $item->id_model)
                           ->where('nt_model_history.date_start', '>', $stdate)
                           ->where('nt_model_history.id_model_type', '=', 5)
                           ->orderby('nt_model_history.date_start', 'asc')
                           ->first();

                       $datafirst = DB::table('nt_model_history')
                           ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                           ->where('nt_model_history.id_model', $item->id_model)
                           ->where('nt_model_history.date_start', '<=', $data1->date_start)
                           ->where('nt_model_history.date_end', '>=', $data1->date_start)
                           ->where('nt_model_history.id_model_type', '=', 5)
                           ->first();
                   }
                   $gcmsDataFirstCache[$idx] = $datafirst;
               }
           
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
               //dd($gcmsdff->count());
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
           
               if($gcmsslope->count() < 11 && $gcmsslope->count() != 0)
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
                   foreach ( $gcmsdatalab as $idx => $item){ 
                       $datafirst = $gcmsDataFirstCache[$idx];
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
               
                   // dd($gcmsdatalabvs3);
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
           // dd($datalablgcms.' < '.$gcmsdatalabvs3[$iss]->gcms_outgassing);
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
                   foreach ( $gcmsdatalab as $idx => $item){ 
                       $datafirst = $gcmsDataFirstCache[$idx];
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
                   foreach ( $gcmsdatalab as $idx => $item){ 
                       $datafirst = $gcmsDataFirstCache[$idx];
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
                   foreach ( $gcmsdatalab as $idx => $item){ 
                       $datafirst = $gcmsDataFirstCache[$idx];
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
                   $dateend = date('Y-m-d 00:00:00', strtotime($request->dateend. ' + 1 day'));;
                   } 
               $ftirdatalab = DB::table('nt_data_ftir')
               ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ftir.id_createlab')
               ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
               ->where('nt_oqa_lab.save_date','<=',$dateend)
               ->where('nt_oqa_lab.id_model',$request->input('id'))
               ->where('nt_oqa_lab.ftir_confirm_st',1)
               ->limit($alarmtest2)->orderBy('nt_oqa_lab.save_date', 'desc')->get();

               $ftirdatalaball = DB::table('nt_data_ftir')
               ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ftir.id_createlab')
               ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
               ->where('nt_oqa_lab.save_date','<=',$dateend)
               ->where('nt_oqa_lab.id_model',$request->input('id'))
               ->where('nt_oqa_lab.ftir_confirm_st',1);
               if($request->chkpdf == 0 || $request->chkpdf == 2)
               {
                    // //$datalaball = $datalaball->limit(intval($oqasum->alpc_sum))->orderBy('nt_oqa_lab.save_date', 'desc')->get();
                    $oqasum_ftir_sum = intval($oqasum->ftir_sum);
                    if($oqasum_ftir_sum == 0){
                        $oqasum_ftir_sum = 1;
                    }
                //    $ftirdatalaball =  $ftirdatalaball->limit(intval($oqasum->ftir_sum))->orderBy('nt_oqa_lab.save_date', 'desc')->get();
                   $ftirdatalaball =  $ftirdatalaball->limit($oqasum_ftir_sum)->orderBy('nt_oqa_lab.save_date', 'desc')->get();
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
               $ftirdatalabvs3 = DB::table('nt_data_ftir')
               ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ftir.id_createlab')
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


               if($ftirslope->count() < 11 && $ftirslope->count() != 0)
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

               //---------------- Check Revise TEST#7 ----------------

                $reviseTEST7 = $this->newoutputgraphratio5($request->input('id'),$request->input('idcreatelab'));

               //--------------- check ratio send email ---------------
                $checkratio = new ResultsControlle();
                $val_checkratio =  $checkratio->chkconfirmRatio($request->input('idcreatelab'));
               
                $slopediff_sem3 = $this->slopediff_sem($request->input('idcreatelab'),3);
                $slopediff_sem6 = $this->slopediff_sem($request->input('idcreatelab'),6);
                $slopediff_sem5 = $this->slopediff_sem($request->input('idcreatelab'),5);
                $slopediff_sem4 = $this->slopediff_sem($request->input('idcreatelab'),4);
                $slopediff_sem3_1 = $this->slopediff_sem($request->input('idcreatelab'),3.1);
                $slopediff_sem3_3 = $this->slopediff_sem($request->input('idcreatelab'),3.3);
                $slopediff_Outgass = $this->slopediff_sem($request->input('idcreatelab'),"Outgass"); 
                $slopediff_lpc = $this->slopediff_lpc($request->input('idcreatelab'));
                $amlfile = DB::table('nt_oqa_aml_file')->where('id_createlab',$request->input('idcreatelab'))->first();

                $oqalot = DB::table('nt_oqa_lab')
                ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                ->where('nt_oqa_lab.id_createlab',$request->input('idcreatelab'))
                ->first();

                return response()->json([
                   'success' => 1,
                   'slopediff_sem3_SLOPE' => @$slopediff_sem3['SLOPE'],
                   'slopediff_sem3_DIFF' =>  @$slopediff_sem3['DIFF'],
                   'slopediff_sem3_3_SLOPE' => @$slopediff_sem3_3['SLOPE'],
                   'slopediff_sem3_3_DIFF' =>  @$slopediff_sem3_3['DIFF'],
                   'slopediff_sem6_SLOPE' => @$slopediff_sem6['SLOPE'], 
                   'slopediff_sem6_DIFF' => @$slopediff_sem6['DIFF'],
                   'slopediff_sem4_SLOPE' => @$slopediff_sem4['SLOPE'],
                   'slopediff_sem4_DIFF' =>  @$slopediff_sem4['DIFF'],
                   'slopediff_sem5_SLOPE' => @$slopediff_sem5['SLOPE'],
                   'slopediff_sem5_DIFF' =>  @$slopediff_sem5['DIFF'],
                   'slopediff_sem3_1_SLOPE' => @$slopediff_sem3_1['SLOPE'],
                   'slopediff_Outgass_DIFF' =>  @$slopediff_Outgass['DIFF'],
                   'slopediff_Outgass_SLOPE' =>  @$slopediff_Outgass['SLOPE'],
                   'slopediff_lpc_SLOPE' =>  @$slopediff_lpc['SLOPE'],
                   'slopediff_lpc_DIFF' =>  @$slopediff_lpc['DIFF'],
                   'oqa' =>$oqa, 
                   'reviseTEST7' =>$reviseTEST7, 
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
                   'alarmtest7' => $val_checkratio, 
                   'testesttest3' => $dataalarm->test3,
                   'amlfile' => $amlfile,
               ]);
               
    }


    public function newoutputgraphratio5($id,$id_createlab) // Check Revise TEST#7 condition 7.2 and 7.3
    {
        ini_set('memory_limit', '-1');
        set_time_limit(1000); 

        $datalabgcms = [];
        $countline = 0;

        $datestart = date("Y-m-d", strtotime("2022-12-01"));
        $dateend =  date("Y-m-d");

        $a1 = DB::table('nt_model')->where('id_model', $id)->first();
        $text1 = $a1->name_model;
        $chktext1 = mb_substr($text1,0,-3);

        $file = storage_path('NHK-File/template/report/ratio_test7.xlsx');
        $filetime = date('YmdHis');
        $spacename = str_replace(' ', '', $chktext1);
        $spacename = $spacename.'_'.$a1->part;
        $namefile = 'reviseTEST7_'.$spacename.''.date('YmdHis');

        $checkfile =  storage_path('NHK-File/report/graphexportratio/'.$namefile.'.xlsx'  );
        if(file_exists($checkfile)){
            @unlink($checkfile);
        }
        $namefile2 = 'reviseTEST7_2_'.$spacename.''.date('YmdHis'); 
        $checkfile2 =  storage_path('NHK-File/report/graphexportratio/'.$namefile2.'.xlsx'  );
        if(file_exists($checkfile2)){
            @unlink($checkfile2);
        }

        if($a1->part == "UP")
        {
            $dateend1 = date('Y-m-d 00:00:00', strtotime($dateend. ' + 1 day'));
            $datalabgcms = DB::table('nt_datacf_gcms')
            ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_datacf_gcms.id_createlab')
            ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
            ->leftjoin('nt_outgassing_gcms','nt_outgassing_gcms.id_createlab','=','nt_oqa_lab.id_createlab')
            ->where('nt_oqa_lab.save_date','>=',$datestart)
            ->where('nt_oqa_lab.save_date','<=',$dateend1)
            ->where('nt_oqa_lab.id_model',$a1->id_model)
            ->where('nt_oqa_lab.gcms_confirm_st',1)
            ->where('nt_oqa_lab.type_lab','LIKE','SGT_%')
            ->orderBy('nt_oqa_lab.save_date', 'asc')->get();
        }
        else
        {
            $dateend1 = date('Y-m-d 00:00:00', strtotime($dateend. ' + 1 day'));             
            $datalabgcms = DB::table('nt_datacf_gcms')
            ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_datacf_gcms.id_createlab')
            ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
            ->leftjoin('nt_outgassing_gcms','nt_outgassing_gcms.id_createlab','=','nt_oqa_lab.id_createlab')
            ->where('nt_oqa_lab.save_date','>=',$datestart)
            ->where('nt_oqa_lab.save_date','<=',$dateend1)
            ->where('nt_oqa_lab.id_model',$a1->id_model)
            ->where('nt_oqa_lab.gcms_confirm_st',1)
            ->where('nt_oqa_lab.type_lab','LIKE','SGT_%')
            ->orderBy('nt_oqa_lab.save_date', 'asc')->get();
        }
        
        foreach ($datalabgcms as $count) {
            $countline++; 
        }

        $namecolum = 'B';
        foreach ($datalabgcms as $count) {
            $namecolum++;
            if($count->id_createlab == $id_createlab){
                break;
            }
        }

        $path = storage_path('NHK-File/report/graphexportratio');
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        Excel::load($file, function($doc)use($datalabgcms,$spacename) {
            $sheet = $doc->setActiveSheetIndex(0);
            $doc->getActiveSheet();
            $sheet->setCellValue('B1', $spacename);

            $i = 'C';
            $row = 3;
            foreach ($datalabgcms as $value) {

                $oqa_labfc = DB::table('nt_datacf_gcms')
                ->where('id_createlab', $value->id_createlab)
                ->first(); 

                $date = new DateTime($value->save_date);
                $week = $date->format("W");
                
                $c1 = number_format($value->c1, 2,'.','');
                $c2 = number_format($value->c2, 2,'.','');
                $c3 = number_format($value->c3, 2,'.','');
                $c4 = number_format($value->c4, 2,'.','');
                $c5 = number_format($value->c5, 2,'.','');
                $c5_1 = number_format($value->c5_1, 2,'.','');
                $c5_2 = number_format($value->c5_2, 2,'.','');
                $c5_3 = number_format($value->c5_3, 2,'.','');
                $c6 = number_format($value->c6, 2,'.','');
                $c6_1 = number_format($value->c6_1, 2,'.','');
                $c6_2 = number_format($value->c6_2, 2,'.','');
                $c7 = number_format($value->c7, 2,'.','');
                $c8 = number_format($value->c8, 2,'.','');
                $c8_1 = number_format($value->c8_1, 2,'.','');
                $c8_2 = number_format($value->c8_2, 2,'.','');
                $c9 = number_format($value->c9, 2,'.','');
                $c9_1 = number_format($value->c9_1, 2,'.','');
                $c9_2 = number_format($value->c9_2, 2,'.','');
                $c10 = number_format($value->c10, 2,'.','');
                $c11 = number_format($value->c11, 2,'.','');
                $c11_1 = number_format($value->c11_1, 2,'.','');
                $c11_2 = number_format($value->c11_2, 2,'.','');
                $c12 = number_format($value->c12, 2,'.','');
                $c13 = number_format($value->c13, 2,'.','');
                $c14 = number_format($value->c14, 2,'.','');
                $c15 = number_format($value->c15, 2,'.','');
                $c15_1 = number_format($value->c15_1, 2,'.','');
                $c15_2 = number_format($value->c15_2, 2,'.','');
                $c16 = number_format($value->c16, 2,'.','');
                $c17 = number_format($value->c17, 2,'.','');
                $c18 = number_format($value->c18, 2,'.','');
                $c19 = number_format($value->c19, 2,'.','');
                $c19_1 = number_format($value->c19_1, 2,'.','');
                $c19_2 = number_format($value->c19_2, 2,'.','');
                $c19_3 = number_format($value->c19_3, 2,'.','');
                $c20 = number_format($value->c20, 2,'.','');
                $c20_1 = number_format($value->c20_1, 2,'.','');
                $c21 = number_format($value->c21, 2,'.','');
                $c21_1 = number_format($value->c21_1, 2,'.','');
                $c22 = number_format($value->c22, 2,'.','');
                $c22_1 = number_format($value->c22_1, 2,'.','');
                $c22_2 = number_format($value->c22_2, 2,'.','');
                $c23 = number_format($value->c23, 2,'.','');
                $c23_1 = number_format($value->c23_1, 2,'.','');
                $c24 = number_format($value->c24, 2,'.','');
                $c24_1 = number_format($value->c24_1, 2,'.','');
                $c24_2 = number_format($value->c24_2, 2,'.','');
                $c25 = number_format($value->c25, 2,'.','');
                $c26 = number_format($value->c26, 2,'.','');
                $c27 = number_format($value->c27, 2,'.','');
                $c28 = number_format($value->c28, 2,'.','');
                $c29 = number_format($value->c29, 2,'.','');
                $c30 = number_format($value->c30, 2,'.','');
                $c31 = number_format($value->c31, 2,'.','');
                $c32 = number_format($value->c32, 2,'.','');
                $c33 = number_format($value->c33, 2,'.','');
                $c34 = number_format($value->c34, 2,'.','');
                $c35 = number_format($value->c35, 2,'.','');
                $total = number_format($value->total, 2,'.','');


                $sheet->setCellValue($i.'2', date('d-M-y',strtotime($value->save_date)));  
                $sheet->setCellValue($i.'3', $c1 );
                $sheet->setCellValue($i.'4', $c2 );
                $sheet->setCellValue($i.'5', $c3 );
                $sheet->setCellValue($i.'6', $c4 );
                $sheet->setCellValue($i.'7', $c5 );
                $sheet->setCellValue($i.'8', $c5_1 );
                $sheet->setCellValue($i.'9', $c5_2 );
                $sheet->setCellValue($i.'10', $c5_3 );
                $sheet->setCellValue($i.'11', $c6 );
                $sheet->setCellValue($i.'12', $c6_1 );
                $sheet->setCellValue($i.'13', $c6_2 );
                $sheet->setCellValue($i.'14', $c7 );
                $sheet->setCellValue($i.'15', $c8 );
                $sheet->setCellValue($i.'16', $c8_1 );
                $sheet->setCellValue($i.'17', $c8_2 );
                $sheet->setCellValue($i.'18', $c9 );
                $sheet->setCellValue($i.'19', $c9_1 );
                $sheet->setCellValue($i.'20', $c9_2 );
                $sheet->setCellValue($i.'21', $c10 );
                $sheet->setCellValue($i.'22', $c11 );
                $sheet->setCellValue($i.'23', $c11_1 );
                $sheet->setCellValue($i.'24', $c11_2 );
                $sheet->setCellValue($i.'25', $c12 );
                $sheet->setCellValue($i.'26', $c13 );
                $sheet->setCellValue($i.'27', $c14 );
                $sheet->setCellValue($i.'28', $c15 );
                $sheet->setCellValue($i.'29', $c15_1 );
                $sheet->setCellValue($i.'30', $c15_2 );
                $sheet->setCellValue($i.'31', $c16 );
                $sheet->setCellValue($i.'32', $c17 );
                $sheet->setCellValue($i.'33', $c18 );
                $sheet->setCellValue($i.'34', $c19 );
                $sheet->setCellValue($i.'35', $c19_1 );
                $sheet->setCellValue($i.'36', $c19_2 );
                $sheet->setCellValue($i.'37', $c19_3 );
                $sheet->setCellValue($i.'38', $c20 );
                $sheet->setCellValue($i.'39', $c20_1 );
                $sheet->setCellValue($i.'40', $c21 );
                $sheet->setCellValue($i.'41', $c21_1 );
                $sheet->setCellValue($i.'42', $c22 );
                $sheet->setCellValue($i.'43', $c22_1 );
                $sheet->setCellValue($i.'44', $c22_2 );
                $sheet->setCellValue($i.'45', $c23 );
                $sheet->setCellValue($i.'46', $c23_1 );
                $sheet->setCellValue($i.'47', $c24 );
                $sheet->setCellValue($i.'48', $c24_1 );
                $sheet->setCellValue($i.'49', $c24_2 );
                $sheet->setCellValue($i.'50', $c25 );
                $sheet->setCellValue($i.'51', $c26 );
                $sheet->setCellValue($i.'52', $c27 );
                $sheet->setCellValue($i.'53', $c28 );
                $sheet->setCellValue($i.'54', $c29 );
                $sheet->setCellValue($i.'55', $c30 );
                $sheet->setCellValue($i.'56', $c31 );
                $sheet->setCellValue($i.'57', $c32 );
                $sheet->setCellValue($i.'58', $c33 );
                $sheet->setCellValue($i.'59', $c34 );
                $sheet->setCellValue($i.'60', $c35 );
                $sheet->setCellValue($i.'61', $total );

                $sheet->setCellValue($i.'62', ($value->c19_4 !== null)? number_format($value->c19_4, 2,'.','') : '#N/A' );
                $sheet->setCellValue($i.'63', ($value->c19_5 !== null)? number_format($value->c19_5, 2,'.','') : '#N/A' );
                $sheet->setCellValue($i.'64', ($value->c35_1 !== null)? number_format($value->c35_1, 2,'.','') : '#N/A' );
                $sheet->setCellValue($i.'65', ($value->c35_2 !== null)? number_format($value->c35_2, 2,'.','') : '#N/A' );
                $sheet->setCellValue($i.'66', ($value->spare_01 !== null)? number_format($value->spare_01, 2,'.','') : '#N/A' );
                $sheet->setCellValue($i.'67', ($value->spare_02 !== null)? number_format($value->spare_02, 2,'.','') : '#N/A' );
                $sheet->setCellValue($i.'68', ($value->spare_03 !== null)? number_format($value->spare_03, 2,'.','') : '#N/A' );
                $sheet->setCellValue($i.'69', ($value->spare_04 !== null)? number_format($value->spare_04, 2,'.','') : '#N/A' );
                $sheet->setCellValue($i.'70', ($value->spare_05 !== null)? number_format($value->spare_05, 2,'.','') : '#N/A' );
                $sheet->setCellValue($i.'71', ($value->spare_06 !== null)? number_format($value->spare_06, 2,'.','') : '#N/A' );
                $sheet->setCellValue($i.'72', ($value->spare_07 !== null)? number_format($value->spare_07, 2,'.','') : '#N/A' );
                $sheet->setCellValue($i.'73', ($value->spare_08 !== null)? number_format($value->spare_08, 2,'.','') : '#N/A' );
                $sheet->setCellValue($i.'74', ($value->spare_09 !== null)? number_format($value->spare_09, 2,'.','') : '#N/A' );
                $sheet->setCellValue($i.'75', ($value->spare_10 !== null)? number_format($value->spare_10, 2,'.','') : '#N/A' ); 
                $sheet->setCellValue($i.'76', ($value->spare_11 !== null)? number_format($value->spare_11, 2,'.','') : '#N/A' ); 
                $sheet->setCellValue($i.'77', ($value->spare_12 !== null)? number_format($value->spare_12, 2,'.','') : '#N/A' ); 
                $sheet->setCellValue($i.'78', ($value->spare_13 !== null)? number_format($value->spare_13, 2,'.','') : '#N/A' ); 
                $sheet->setCellValue($i.'79', ($value->spare_14 !== null)? number_format($value->spare_14, 2,'.','') : '#N/A' ); 
                $sheet->setCellValue($i.'80', ($value->spare_15 !== null)? number_format($value->spare_15, 2,'.','') : '#N/A' ); 
                $sheet->setCellValue($i.'81', ($value->spare_16 !== null)? number_format($value->spare_16, 2,'.','') : '#N/A' ); 
                $sheet->setCellValue($i.'82', ($value->spare_17 !== null)? number_format($value->spare_17, 2,'.','') : '#N/A' ); 
                $sheet->setCellValue($i.'83', ($value->spare_18 !== null)? number_format($value->spare_18, 2,'.','') : '#N/A' ); 
                $sheet->setCellValue($i.'84', ($value->spare_19 !== null)? number_format($value->spare_19, 2,'.','') : '#N/A' ); 
                $sheet->setCellValue($i.'85', ($value->spare_20 !== null)? number_format($value->spare_20, 2,'.','') : '#N/A' ); 
                $sheet->setCellValue($i.'86', ($value->spare_21 !== null)? number_format($value->spare_21, 2,'.','') : '#N/A' ); 
                $sheet->setCellValue($i.'87', ($value->spare_22 !== null)? number_format($value->spare_22, 2,'.','') : '#N/A' ); 
                $sheet->setCellValue($i.'88', ($value->spare_23 !== null)? number_format($value->spare_23, 2,'.','') : '#N/A' ); 
                $sheet->setCellValue($i.'89', ($value->spare_24 !== null)? number_format($value->spare_24, 2,'.','') : '#N/A' ); 
                $sheet->setCellValue($i.'90', ($value->spare_25 !== null)? number_format($value->spare_25, 2,'.','') : '#N/A' ); 
                $sheet->setCellValue($i.'91', ($value->spare_26 !== null)? number_format($value->spare_26, 2,'.','') : '#N/A' ); 
                $sheet->setCellValue($i.'92', ($value->spare_27 !== null)? number_format($value->spare_27, 2,'.','') : '#N/A' ); 
                $sheet->setCellValue($i.'93', ($value->spare_28 !== null)? number_format($value->spare_28, 2,'.','') : '#N/A' ); 
                $sheet->setCellValue($i.'94', ($value->spare_29 !== null)? number_format($value->spare_29, 2,'.','') : '#N/A' ); 
                $sheet->setCellValue($i.'95', ($value->spare_30 !== null)? number_format($value->spare_30, 2,'.','') : '#N/A' ); 
                $sheet->setCellValue($i.'96', ($value->spare_31 !== null)? number_format($value->spare_31, 2,'.','') : '#N/A' ); 
                $sheet->setCellValue($i.'97', ($value->spare_32 !== null)? number_format($value->spare_32, 2,'.','') : '#N/A' ); 
                $sheet->setCellValue($i.'98', ($value->spare_33 !== null)? number_format($value->spare_33, 2,'.','') : '#N/A' ); 
                $sheet->setCellValue($i.'99', ($value->spare_34 !== null)? number_format($value->spare_34, 2,'.','') : '#N/A' ); 
                $sheet->setCellValue($i.'100', ($value->spare_35 !== null)? number_format($value->spare_35, 2,'.','') : '#N/A' ); 
                $sheet->setCellValue($i.'101', ($value->spare_36 !== null)? number_format($value->spare_36, 2,'.','') : '#N/A' ); 
                $sheet->setCellValue($i.'102', ($value->spare_37 !== null)? number_format($value->spare_37, 2,'.','') : '#N/A' );

                $i++;
                $row++;
            } 
            
        })->setFilename($namefile)->store('xlsx', storage_path('NHK-File/report/graphexportratio'));
         
        $getfileratio =  storage_path('NHK-File/report/graphexportratio/'.$namefile.'.xlsx'); 
        $reader = IOFactory::createReader('Xlsx');
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($getfileratio);
        $import = [$spreadsheet->setActiveSheetIndex(1)->toArray(null, true, true, true)];
        $excel = $import[0];
                    // บรรทัดที่ | คอลัมน์ที่
        //------------ Category Sum ------------
        $elmental = (isset($excel[105][$namecolum])) ? floatval( $excel[105][$namecolum]) : 0;
        $acid =  (isset($excel[106][$namecolum])) ? floatval( $excel[106][$namecolum]) : 0;
        $oxygen =  (isset($excel[107][$namecolum])) ? floatval( $excel[107][$namecolum]) : 0;
        $aromatic =  (isset($excel[108][$namecolum])) ? floatval( $excel[108][$namecolum]) : 0;
        $aliphatic =  (isset($excel[109][$namecolum])) ? floatval( $excel[109][$namecolum]) : 0;
        $low =  (isset($excel[110][$namecolum])) ? floatval( $excel[110][$namecolum]) : 0;
        $high =  (isset($excel[111][$namecolum])) ? floatval( $excel[111][$namecolum]) : 0;
        $smear =  (isset($excel[112][$namecolum])) ? floatval( $excel[112][$namecolum]) : 0;
        $disk =  (isset($excel[113][$namecolum])) ? floatval( $excel[113][$namecolum]) : 0;

        $elmental = number_format($elmental, 1, '.', '');
        $acid = number_format($acid, 1, '.', '');
        $oxygen = number_format($oxygen, 1, '.', '');
        $aromatic = number_format($aromatic, 1, '.', '');
        $aliphatic = number_format($aliphatic, 1, '.', '');
        $low = number_format($low, 1, '.', '');
        $high = number_format($high, 1, '.', '');
        $smear = number_format($smear, 1, '.', '');
        $disk = number_format($disk, 1, '.', '');

        //------------ Sigma Ratio = Delta/sigma ------------
        
        $rt_elmental = (isset($excel[149][$namecolum])) ? floatval( $excel[149][$namecolum]) : 0;
        $rt_acid = (isset($excel[150][$namecolum])) ? floatval( $excel[150][$namecolum]) : 0;
        $rt_oxygen = (isset($excel[151][$namecolum])) ? floatval( $excel[151][$namecolum]) : 0;
        $rt_aromatic = (isset($excel[152][$namecolum])) ? floatval( $excel[152][$namecolum]) : 0;
        $rt_aliphatic = (isset($excel[153][$namecolum])) ? floatval( $excel[153][$namecolum]) : 0;
        $rt_low = (isset($excel[154][$namecolum])) ? floatval( $excel[154][$namecolum]) : 0;
        $rt_high = (isset($excel[155][$namecolum])) ? floatval( $excel[155][$namecolum]) : 0;
        $rt_smear = (isset($excel[156][$namecolum])) ? floatval( $excel[156][$namecolum]) : 0;
        $rt_disk = (isset($excel[157][$namecolum])) ? floatval( $excel[157][$namecolum]) : 0;
        $rt_hsp = (isset($excel[160][$namecolum])) ? floatval( $excel[160][$namecolum]) : 0;
        
        $totaloutgassing = (isset($excel[61][$namecolum])) ? floatval( $excel[61][$namecolum]) : 0;

        $dataex = [
            'rt_elmental' => $rt_elmental,
            'rt_acid' => $rt_acid,
            'rt_oxygen' => $rt_oxygen,
            'rt_aromatic' => $rt_aromatic,
            'rt_aliphatic' => $rt_aliphatic,
            'rt_low' => $rt_low,
            'rt_high' => $rt_high,
            'rt_smear' => $rt_smear,
            'rt_disk' => $rt_disk,
            'rt_hsp' => $rt_hsp,
            'elmental' => $elmental,
            'acid' => $acid,
            'oxygen' => $oxygen,
            'aromatic' => $aromatic,
            'aliphatic' => $aliphatic,
            'low' => $low,
            'high' => $high,
            'smear' => $smear,
            'disk' => $disk,
            'totaloutgassing' => $totaloutgassing, 
        ];

        Excel::load($getfileratio, function($doc)use($dataex,$a1) {
            $sheet = $doc->setActiveSheetIndex(1);

            $sheet->setCellValue('C170', $dataex['rt_elmental'] );
            $sheet->setCellValue('C171', $dataex['rt_acid'] );
            $sheet->setCellValue('C172', $dataex['rt_oxygen'] );
            $sheet->setCellValue('C173', $dataex['rt_aromatic'] );
            $sheet->setCellValue('C174', $dataex['rt_aliphatic'] );
            $sheet->setCellValue('C175', $dataex['rt_low'] );
            $sheet->setCellValue('C176', $dataex['rt_high'] );
            $sheet->setCellValue('C177', $dataex['rt_smear'] );
            $sheet->setCellValue('C178', $dataex['rt_disk'] );

            $sheet->setCellValue('F170', $dataex['elmental'] );
            $sheet->setCellValue('F171', $dataex['acid'] );
            $sheet->setCellValue('F172', $dataex['oxygen'] );
            $sheet->setCellValue('F173', $dataex['aromatic'] );
            $sheet->setCellValue('F174', $dataex['aliphatic'] );
            $sheet->setCellValue('F175', $dataex['low'] );
            $sheet->setCellValue('F176', $dataex['high'] );
            $sheet->setCellValue('F177', $dataex['smear'] );
            $sheet->setCellValue('F178', $dataex['disk'] );

            $sheet->setCellValue('G170', $a1->gcms_mdl );
            $sheet->setCellValue('G171', $a1->gcms_mdl );
            $sheet->setCellValue('G172', $a1->gcms_mdl );
            $sheet->setCellValue('G173', $a1->gcms_mdl );
            $sheet->setCellValue('G174', $a1->gcms_mdl );
            $sheet->setCellValue('G175', $a1->gcms_mdl );
            $sheet->setCellValue('G176', $a1->gcms_mdl );
            $sheet->setCellValue('G177', $a1->gcms_mdl );
            $sheet->setCellValue('G178', $a1->gcms_mdl );

            $sheet->setCellValue('J170', $dataex['totaloutgassing'] );
            $sheet->setCellValue('J171', $dataex['totaloutgassing'] );
            $sheet->setCellValue('J172', $dataex['totaloutgassing'] );
            $sheet->setCellValue('J173', $dataex['totaloutgassing'] );
            $sheet->setCellValue('J174', $dataex['totaloutgassing'] );
            $sheet->setCellValue('J175', $dataex['totaloutgassing'] );
            $sheet->setCellValue('J176', $dataex['totaloutgassing'] );
            $sheet->setCellValue('J177', $dataex['totaloutgassing'] );
            $sheet->setCellValue('J178', $dataex['totaloutgassing'] );

            
        })->setFilename($namefile2)->store('xlsx', storage_path('NHK-File/report/graphexportratio'));
        
        $readfile =  storage_path('NHK-File/report/graphexportratio/'.$namefile2.'.xlsx'); 
        $spreadSheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($readfile);

        //------------ Sigma ratio ------------
        $ratio_elemental = $spreadSheet->getActiveSheet()->getCell('C170')->getCalculatedValue();
        $ratio_acid = $spreadSheet->getActiveSheet()->getCell('C171')->getCalculatedValue();
        $ratio_oxygen = $spreadSheet->getActiveSheet()->getCell('C172')->getCalculatedValue();
        $ratio_aromatic = $spreadSheet->getActiveSheet()->getCell('C173')->getCalculatedValue();
        $ratio_aliphatic = $spreadSheet->getActiveSheet()->getCell('C174')->getCalculatedValue();
        $ratio_low = $spreadSheet->getActiveSheet()->getCell('C175')->getCalculatedValue();
        $ratio_high = $spreadSheet->getActiveSheet()->getCell('C176')->getCalculatedValue();
        $ratio_smear = $spreadSheet->getActiveSheet()->getCell('C177')->getCalculatedValue();
        $ratio_disk = $spreadSheet->getActiveSheet()->getCell('C178')->getCalculatedValue();

        //------------ Category Sum ------------
        $sum_elemental = $spreadSheet->getActiveSheet()->getCell('F170')->getCalculatedValue();
        $sum_acid = $spreadSheet->getActiveSheet()->getCell('F171')->getCalculatedValue();
        $sum_oxygen = $spreadSheet->getActiveSheet()->getCell('F172')->getCalculatedValue();
        $sum_aromatic = $spreadSheet->getActiveSheet()->getCell('F173')->getCalculatedValue();
        $sum_aliphatic = $spreadSheet->getActiveSheet()->getCell('F174')->getCalculatedValue();
        $sum_low = $spreadSheet->getActiveSheet()->getCell('F175')->getCalculatedValue();
        $sum_high = $spreadSheet->getActiveSheet()->getCell('F176')->getCalculatedValue();
        $sum_smear = $spreadSheet->getActiveSheet()->getCell('F177')->getCalculatedValue();
        $sum_disk = $spreadSheet->getActiveSheet()->getCell('F178')->getCalculatedValue();

        //------------ Over 5% of Total outgassing ------------
        $over_elemental = $spreadSheet->getActiveSheet()->getCell('K170')->getCalculatedValue();
        $over_acid = $spreadSheet->getActiveSheet()->getCell('K171')->getCalculatedValue();
        $over_oxygen = $spreadSheet->getActiveSheet()->getCell('K172')->getCalculatedValue();
        $over_aromatic = $spreadSheet->getActiveSheet()->getCell('K173')->getCalculatedValue();
        $over_aliphatic = $spreadSheet->getActiveSheet()->getCell('K174')->getCalculatedValue();
        $over_low = $spreadSheet->getActiveSheet()->getCell('K175')->getCalculatedValue();
        $over_high = $spreadSheet->getActiveSheet()->getCell('K176')->getCalculatedValue();
        $over_smear = $spreadSheet->getActiveSheet()->getCell('K177')->getCalculatedValue();
        $over_disk = $spreadSheet->getActiveSheet()->getCell('K178')->getCalculatedValue();

        $over_elemental = round(floatval($over_elemental)*100);
        $over_acid = round(floatval($over_acid)*100);
        $over_oxygen = round(floatval($over_oxygen)*100);
        $over_aromatic = round(floatval($over_aromatic)*100);
        $over_aliphatic = round(floatval($over_aliphatic)*100);
        $over_low = round(floatval($over_low)*100);
        $over_high = round(floatval($over_high)*100);
        $over_smear = round(floatval($over_smear)*100);
        $over_disk = round(floatval($over_disk)*100);

        //------------ SUM ------------
        $elemental = $spreadSheet->getActiveSheet()->getCell('N170')->getCalculatedValue();
        $acid = $spreadSheet->getActiveSheet()->getCell('N171')->getCalculatedValue();
        $oxygen = $spreadSheet->getActiveSheet()->getCell('N172')->getCalculatedValue();
        $aromatic = $spreadSheet->getActiveSheet()->getCell('N173')->getCalculatedValue();
        $aliphatic = $spreadSheet->getActiveSheet()->getCell('N174')->getCalculatedValue();
        $low = $spreadSheet->getActiveSheet()->getCell('N175')->getCalculatedValue();
        $high = $spreadSheet->getActiveSheet()->getCell('N176')->getCalculatedValue();
        $smear = $spreadSheet->getActiveSheet()->getCell('N177')->getCalculatedValue();
        $disk = $spreadSheet->getActiveSheet()->getCell('N178')->getCalculatedValue();

        $data = [
            'sumalert_test7_elmental' => $elemental,
            'sumalert_test7_acid' => $acid,
            'sumalert_test7_oxygen' => $oxygen,
            'sumalert_test7_aromatic' => $aromatic,
            'sumalert_test7_low' => $low,
            'sumalert_test7_high' => $high,
            'sumalert_test7_smear' => $smear,
            'sumalert_test7_disk' => $disk,

            'ratio_elmental' => $ratio_elemental,
            'ratio_acid' => $ratio_acid,
            'ratio_oxygen' => $ratio_oxygen,
            'ratio_aromatic' => $ratio_aromatic,
            'ratio_aliphatic' => $ratio_aliphatic,
            'ratio_low' => $ratio_low,
            'ratio_high' => $ratio_high,
            'ratio_smear' => $ratio_smear,
            'ratio_disk' => $ratio_disk, 

            'sum_elmental' => $sum_elemental,
            'sum_acid' => $sum_acid,
            'sum_oxygen' => $sum_oxygen,
            'sum_aromatic' => $sum_aromatic,
            'sum_aliphatic' => $sum_aliphatic,
            'sum_low' => $sum_low,
            'sum_high' => $sum_high,
            'sum_smear' => $sum_smear,
            'sum_disk' => $sum_disk, 

            'over_elmental' => $over_elemental,
            'over_acid' => $over_acid,
            'over_oxygen' => $over_oxygen,
            'over_aromatic' => $over_aromatic,
            'over_aliphatic' => $over_aliphatic,
            'over_low' => $over_low,
            'over_high' => $over_high,
            'over_smear' => $over_smear,
            'over_disk' => $over_disk, 
        ];
        
        return $data;
 
    }

    public function confirmjnhklpc(Request $request)
    {
        if(DB::table('nt_oqa_aml_file')->where('id_createlab', $request->id)->count() != 0){
            $check = DB::table('nt_oqa_aml_file')->where('id_createlab', $request->id)->first();

            $oqa = DB::table('nt_oqa_lab')->where('id_createlab', $request->id)->first();
            $model = DB::table('nt_model')->where('id_model', $oqa->id_model)->first();
    
            $data = [
                'id' => $oqa->id_createlab,
                'testter' => 'LPC',
                'model' => $model->name_model,
                'lot' => $oqa->lotnumber_lab,
                'date' => $check->jnhk_lpc_update,
                'file' => $check->file_jnhk_lpc,
            ];
            $mail = new MailController();
            $mail->mailconfirmlpcAML($data);
    
            return response()->json(['amlfile' =>  $check]);
        }else{
            $data = [];
            return response()->json(['amlfile' =>  null]);
        }
    
    }
    
    public function confirmjnhkgcms(Request $request)
    {
        if(DB::table('nt_oqa_aml_file')->where('id_createlab', $request->id)->count() != 0){
            $check = DB::table('nt_oqa_aml_file')->where('id_createlab', $request->id)->first();

            $oqa = DB::table('nt_oqa_lab')->where('id_createlab', $request->id)->first();
            $model = DB::table('nt_model')->where('id_model', $oqa->id_model)->first();
    
            $data = [
                'id' => $oqa->id_createlab,
                'testter' => 'GCMS',
                'model' => $model->name_model,
                'lot' => $oqa->lotnumber_lab,
                'date' => $check->jnhk_gcms_update,
                'file' => $check->file_jnhk_gcms,
                'zip' => $check->gcms_zip_file,
            ];
            $mail = new MailController();
            $mail->mailconfirmgcmsAML($data);
    
            return response()->json(['amlfile' =>  $check]);
        }else{
            $data = [];
            return response()->json(['amlfile' =>  null]);
        }
    }

        
    
}
