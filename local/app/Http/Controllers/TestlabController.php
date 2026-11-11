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
use App\Mail\Mailnhk;
use nhkviewlpc;
use nhkviewsem;
use nhkviewic;
use nhkviewgcms;
use nhkviewftir;
use nhkviewsemtest;
use nhkmail;

use Illuminate\Support\Facades\Mail; 


class TestlabController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function addlpcfile(Request $request)
    { 
        $chk =  DB::table('nt_oqa_lab')
        ->join('nt_oqa_file', 'nt_oqa_lab.id_createlab', '=', 'nt_oqa_file.id_createlab')
        ->join('nt_data_lpc', 'nt_data_lpc.id_createlab', '=', 'nt_oqa_lab.id_createlab')
        ->join('nt_model', 'nt_model.id_model', '=', 'nt_oqa_lab.id_model')
        ->where('nt_oqa_lab.lpc_confirm_st',1)
        ->where('nt_data_lpc.lpc1',null)->get();
        dd($chk);

        $oqafile1 =  DB::table('nt_oqa_lab')
        ->join('nt_oqa_file', 'nt_oqa_lab.id_createlab', '=', 'nt_oqa_file.id_createlab')
        ->join('nt_data_lpc', 'nt_data_lpc.id_createlab', '=', 'nt_oqa_lab.id_createlab')
        ->join('nt_model', 'nt_model.id_model', '=', 'nt_oqa_lab.id_model')
        ->where('nt_oqa_lab.lpc_confirm_st',1)
        ->where('nt_data_lpc.lpc1',null)->limit(150)->get();
       // dd($oqafile1);
        foreach ($oqafile1 as $item) {
            $oqafile =  DB::table('nt_oqa_file')
            ->join('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_oqa_file.id_createlab')
            ->join('nt_model', 'nt_model.id_model', '=', 'nt_oqa_lab.id_model')
            ->where('nt_oqa_file.id_createlab',$item->id_createlab)->first();
            $file = storage_path('app/public/OQA/LPC/'.$oqafile->file_lpc);
            //dd($file);
            $cell = '';
            $chk = '';
            $id =$item->id_createlab;
            Excel::load($file, function($doc)use($id,&$chk) {
                
            $sheet = $doc->setActiveSheetIndex(0);
            $ftimechk = $sheet->getCell('R4')->getOldCalculatedValue();
            $ftime = date("Y/m/d H:i:s", strtotime($ftimechk));
            $cell=$sheet->getCell('O19')->getCalculatedValue();
            //dd($sheet->getCell('O16')->getCalculatedValue());
                if($cell != "")
                {
                    $cell1=$sheet->getCell('O16')->getCalculatedValue();
                    $cell2=$sheet->getCell('O17')->getCalculatedValue();
                    $cell3=$sheet->getCell('O18')->getCalculatedValue();
                    
                    $oqalpc = [
                    'lpc1' => $cell1,
                    'lpc2' => $cell2,
                    'lpc3' => $cell3,
                    'lpc_datesave' => date('Y-m-d H:i:s'),
                    'lpc_st' => 1,             
                ];
                
                DB::table('nt_data_lpc')->where('id_createlab', $id)->update($oqalpc);
                DB::table('nt_datacf_lpc')->where('id_createlab', $id)->update($oqalpc);
                }
                else
                {
                }   
            });
        }
        dd('ok');
        return response()->json(['success' =>  $chk,'id'=> $request->input('id')]);
    }
     
    
}