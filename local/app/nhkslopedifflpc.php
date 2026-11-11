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

class nhkslopedifflpc
{
  
    static function lpc($idcreatelab)
    { 
   
          $oqalot = DB::table('nt_oqa_lab')
          ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
          ->where('nt_oqa_lab.id_createlab',$idcreatelab)
          ->first();
  
          $oqadate = DB::table('nt_data_lpc')
          ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_lpc.id_createlab')
          ->where('nt_data_lpc.id_createlab',$idcreatelab)->where('nt_data_lpc.lpc_st',1)
          ->first();
   
  
          $a1 = ($oqalot !== null)
              ? DB::table('nt_model')->where('id_model', $oqalot->id_model)->first()
              : null;

          if($oqalot !== null && $a1 !== null){
  
              $id_model = $oqalot->id_model;
  
              $datestart = date("Y-m-d", strtotime("2022-12-01"));
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
                  $fallbackDate = date('Y-m-d H:i:s');
                  $yeardown = date('Y-m-d 00:00:00', strtotime($fallbackDate. ' - 1 year'));
                  $dateend = $fallbackDate;
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
                  try {
                      if (!file_exists($file)) {
                          throw new \Exception('Template file not found: '.$file);
                      }

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
          
                      $readfile =  storage_path('NHK-File/report/calculation/'.$namefile.'.xlsx'  );
                      $spreadSheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($readfile);
  
                      $n10 = $spreadSheet->getActiveSheet()->getCell('N10')->getCalculatedValue();
                      $n22 = $spreadSheet->getActiveSheet()->getCell('N22')->getCalculatedValue();
  
                      // ป้องกัน number_format() error กรณี cell คำนวณได้ค่าที่ไม่ใช่ตัวเลข
                      // เช่น error string ของ Excel formula ("#DIV/0!", "#N/A" เป็นต้น)
                      $excel = [
                          'SLOPE' => is_numeric($n10) ? number_format($n10, 3, '.', '') : "",
                          'DIFF' => is_numeric($n22) ? number_format($n22, 3, '.', '') : ""
                      ];
                  } catch (\Exception $e) {
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
          }else{
              $excel = [
                  'SLOPE' => "",
                  'DIFF' => ""
              ];
          }
  
          return $excel;
    }


}