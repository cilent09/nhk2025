<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Datatables;
use DB;
use Response;
use File;
use Excel;
use Hash;
use slope;
use PDF;
use Dompdf\Dompdf;
use Session;
use nhkviewlpc;
use nhkviewsem;
use nhkviewic;
use nhkviewgcms;
use nhkviewftir;
use nhkmail;
use DateTime;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\IOFactory; 
use PhpOffice\PhpSpreadsheet\Spreadsheet; 
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;

// วาดกราฟ
// เรียกใช้งาน class ที่ใช้ในการสร้างแผนภูมิร่วมกับ JpGraph 
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Layout;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;

class OutputController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function results()
    { 
        if(Auth::user()->status != 3){
            return view('output/results');
        }else{
            return view('output/results_mfg');
        }
    }
    public function raw()
    { 
        return view('output/raw');
    }
    public function calculation()
    { 
        return view('output/calculation');
    }
    public function viewccp()
    { 
        return view('output/viewccp');
    }
    public function customer()
    { 
        return view('output/customer');
    }
    public function elementsem()
    {
        return view('output/elementsem');
    }
    public function outputgraphpdf(Request $request)
    { 
        $oqa = DB::table('nt_oqa_lab')
        ->where('id_model',$request->input('modelid'))
        ->get();
        $oqalot = DB::table('nt_oqa_lab')
        ->where('id_createlab',$request->input('idcreatelab'))
        ->first();
        $oqadate = DB::table('nt_data_lpc')
        ->where('id_createlab',$request->input('idcreatelab'))->where('lpc_st',1)
        ->first();
        $oqadatesem = DB::table('nt_data_sem')
        ->where('id_createlab',$request->input('idcreatelab'))->where('sem_st',1)
        ->first();
        $oqadateic = DB::table('nt_data_ic')
        ->where('id_createlab',$request->input('idcreatelab'))->where('ic_st',1)
        ->first();
        $oqadategcms = DB::table('nt_data_gcms')
        ->where('id_createlab',$request->input('idcreatelab'))->where('gcms_st',1)
        ->first();
        $oqadateftir = DB::table('nt_data_ftir')
        ->where('id_createlab',$request->input('idcreatelab'))->where('ftir_st',1)
        ->first();
        $modelname = DB::table('nt_model')
        ->where('id_model',$request->input('modelid'))
        ->first();
        $modelparameter = DB::table('nt_model_parameter')
        ->where('id_model',$request->input('id'))
        ->where('id_model_p',1)
        ->first();
        $oqa = DB::table('nt_model')
        ->where('id_model',$request->input('id'))
        ->first();
        $model = DB::table('nt_model_spec')
        ->join('nt_model_spec_type','nt_model_spec_type.id_model_type','=','nt_model_spec.id_model_type')
        ->where('nt_model_spec.id_model',$request->input('id'))
        ->get();
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
        //-------------------------------------lpc----------------------------------------//
        $datalab = DB::table('nt_data_lpc')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_lpc.id_createlab')
        ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
        ->where('nt_data_lpc.lpc_datesave','<=',$oqadate->lpc_datesave)
        ->where('nt_oqa_lab.id_model',$request->input('id'))
        ->limit(9)->orderBy('nt_data_lpc.lpc_datesave', 'desc')->get();
        $datalaball = DB::table('nt_data_lpc')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_lpc.id_createlab')
        ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
        ->where('nt_data_lpc.lpc_datesave','>=',$request->input('datestart'))
        ->where('nt_data_lpc.lpc_datesave','<=',$request->input('dateend'))
        ->where('nt_oqa_lab.id_model',$request->input('modelid'))
        ->orderBy('nt_oqa_lab.save_date', 'desc')->get();
        $datalabvs3 = DB::table('nt_data_lpc')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_lpc.id_createlab')
        ->where('nt_data_lpc.lpc_datesave','<=',$oqadate->lpc_datesave)
        ->where('nt_oqa_lab.id_model',$request->input('id'))
        ->limit(6)->orderBy('nt_data_lpc.lpc_datesave', 'desc')->get();
        $sortdatalab = $datalabvs3->sortBy('lpc_datesave'); 
        $higher = 0;
        $below = 0;
        $countdatalab = count($datalab);
        $countsortdatalab = count($sortdatalab);
        if($countdatalab >= 9){
            foreach ( $datalab as $item){ 
                if ($cl->alpc < $item->lpc_data) {
                    $higher++;          
                } 
                else if ($cl->alpc > $item->lpc_data) {
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
        if($countsortdatalab >= 6){
            $datalablpc = $datalabvs3[0]->lpc_data;
            foreach ( $datalabvs3 as $item){ 
                $iss++;
                if( $iss <= 5)
                {
                if ($datalablpc < $datalabvs3[$iss]->lpc_data) {
                    $highervs3++;
                    $datalablpc = $item->lpc_data;
                } 
                else if ($datalablpc >  $datalabvs3[$iss]->lpc_data) {
                    $belowvs3++;
                    $datalablpc = $item->lpc_data;
                } 
                else {
                    $highervs3--;
                    $belowvs3--;
                }
                }
            }
        }
        //-------------------------------------end lpc-------------------------------------//
        return response()->json([
            'success' => 1,
            'oqa' =>$oqa, 
            'oqalot' =>$oqalot, 
            'model' =>$model,
            'modelname' =>$modelname,   
            'datalab' => $datalab,
            'datalaball' => $datalaball,
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
            ]);
    }

    private function getLpcCountDateRange($data)
    {
        if ($data->number == 0) {
            return [$data->startdate, $data->enddate];
        }
    
        $number = (int) $data->number;
    
        $datachk = DB::table('nt_model')
            ->join('nt_oqa_lab', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
            ->where('nt_oqa_lab.save_date', '<=', $data->enddate)
            ->where('nt_model.id_model', '=', $data->id_model)
            ->where('nt_oqa_lab.lpc_confirm_st', 1)
            ->select('nt_oqa_lab.save_date as save_datest')
            ->orderBy('nt_oqa_lab.save_date', 'DESC')
            ->skip($number - 1)
            ->take(1)
            ->first();
    
        if ($datachk == null) {
            // ข้อมูลไม่ครบตามจำนวนที่ต้องการ -> เอาเท่าที่มี (record แรกสุด)
            $datachkFallback = DB::table('nt_model')
                ->join('nt_oqa_lab', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                ->where('nt_oqa_lab.save_date', '<=', $data->enddate)
                ->where('nt_model.id_model', '=', $data->id_model)
                ->where('nt_oqa_lab.lpc_confirm_st', 1)
                ->select('nt_oqa_lab.save_date as save_datest')
                ->orderBy('nt_oqa_lab.save_date', 'ASC')
                ->first();
    
            $firstdate = $datachkFallback ? $datachkFallback->save_datest : '';
        } else {
            $firstdate = $datachk->save_datest;
        }
    
        $lastdate = $data->enddate;
    
        return [$firstdate, $lastdate];
    }
 
    public function datatable(Request $request)
    {
        if (empty($request->date_start) && empty($request->date_end)) {
            $datalist = DB::table('nt_model')->where('status_model', 1)->where('nt_model.model_type', 'LIKE', 'SGT_%')->get();
            $sQuery = Datatables::of($datalist)
                ->addIndexColumn()
                ->addColumn('lpccount', function ($data) {
                    return null;
                })
                ->addColumn('semcount', function ($data) {
                    return null;
                })
                ->addColumn('iccount', function ($data) {
                    return null;
                })
                ->addColumn('gcmscount', function ($data) {
                    return null;
                })
                ->addColumn('ftircount', function ($data) {
                    return null;
                })
                ->addColumn('countdata', function ($data) {
                    return 1;
                })
                ->addColumn('lastdate', function ($data) {
                    return '';
                })
                ->addColumn('firstdate', function ($data) {
                    return '';
                });
        } else {
            $datestart = date('Y-m-d H:i:s', strtotime($request->date_start));
            $dateend = date('Y-m-d 00:00:00', strtotime($request->date_end . "+1 day"));
            $start = "'$datestart'";
            $end = "'$dateend'";
            $number = "'$request->number'";
    
            $datalist = DB::table('nt_model')
                ->where('status_model', 1)
                ->where('nt_model.model_type', 'LIKE', 'SGT_%')
                ->select('nt_model.*', DB::raw('' . $start . ' as startdate'), DB::raw('' . $end . ' as enddate'), DB::raw('' . $number . ' as number'))
                ->get();
    
            $sQuery = Datatables::of($datalist)
                ->addIndexColumn()
                ->addColumn('lpccount', function ($data) {
                    [$firstdate, $lastdate] = $this->getLpcCountDateRange($data);
    
                    return DB::table('nt_datacf_lpc')
                        ->join('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_datacf_lpc.id_createlab')
                        ->where('nt_oqa_lab.save_date', '>=', $firstdate)
                        ->where('nt_oqa_lab.save_date', '<=', $lastdate)
                        ->where('nt_oqa_lab.lpc_confirm_st', 1)
                        ->where('nt_oqa_lab.id_model', $data->id_model)
                        ->count();
                })
                ->addColumn('semcount', function ($data) {
                    [$firstdate, $lastdate] = $this->getLpcCountDateRange($data);
    
                    return DB::table('nt_datacf_sem')
                        ->join('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_datacf_sem.id_createlab')
                        ->where('nt_oqa_lab.save_date', '>=', $firstdate)
                        ->where('nt_oqa_lab.save_date', '<=', $lastdate)
                        ->where('nt_oqa_lab.sem_confirm_st', 1)
                        ->where('nt_oqa_lab.id_model', $data->id_model)
                        ->count();
                })
                ->addColumn('iccount', function ($data) {
                    [$firstdate, $lastdate] = $this->getLpcCountDateRange($data);
    
                    return DB::table('nt_datacf_ic')
                        ->join('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_datacf_ic.id_createlab')
                        ->where('nt_oqa_lab.save_date', '>=', $firstdate)
                        ->where('nt_oqa_lab.save_date', '<=', $lastdate)
                        ->where('nt_oqa_lab.ic_confirm_st', 1)
                        ->where('nt_oqa_lab.id_model', $data->id_model)
                        ->count();
                })
                ->addColumn('gcmscount', function ($data) {
                    [$firstdate, $lastdate] = $this->getLpcCountDateRange($data);
    
                    return DB::table('nt_datacf_gcms')
                        ->join('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_datacf_gcms.id_createlab')
                        ->where('nt_oqa_lab.save_date', '>=', $firstdate)
                        ->where('nt_oqa_lab.save_date', '<=', $lastdate)
                        ->where('nt_oqa_lab.gcms_confirm_st', 1)
                        ->where('nt_oqa_lab.id_model', $data->id_model)
                        ->count();
                })
                ->addColumn('ftircount', function ($data) {
                    [$firstdate, $lastdate] = $this->getLpcCountDateRange($data);
    
                    return DB::table('nt_datacf_ftir')
                        ->join('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_datacf_ftir.id_createlab')
                        ->where('nt_oqa_lab.save_date', '>=', $firstdate)
                        ->where('nt_oqa_lab.save_date', '<=', $lastdate)
                        ->where('nt_oqa_lab.ftir_confirm_st', 1)
                        ->where('nt_oqa_lab.id_model', $data->id_model)
                        ->count();
                })
                ->addColumn('countdata', function ($data) {
                    return 2;
                })
                ->addColumn('lastdate', function ($data) {
                    [$firstdate, $lastdate] = $this->getLpcCountDateRange($data);
    
                    return $lastdate;
                })
                ->addColumn('firstdate', function ($data) {
                    [$firstdate, $lastdate] = $this->getLpcCountDateRange($data);
    
                    return $firstdate;
                });
        }
    
        return $sQuery->escapeColumns([])->make(true);
    }

    public function datatablecustomer(Request $request)
    {
        if (empty($request->date_start) && empty($request->date_end)) {
            $datalist = DB::table('nt_model')->where('status_model', 1)->where('model_type', 'LIKE', 'SGT_%')->get();
            $sQuery = Datatables::of($datalist)
                ->addIndexColumn()
                ->addColumn('lpccount', function ($data) {
                    return null;
                })
                ->addColumn('semcount', function ($data) {
                    return null;
                })
                ->addColumn('iccount', function ($data) {
                    return null;
                })
                ->addColumn('gcmscount', function ($data) {
                    return null;
                })
                ->addColumn('ftircount', function ($data) {
                    return null;
                })
                ->addColumn('countdata', function ($data) {
                    return 1;
                })
                ->addColumn('lastdate', function ($data) {
                    return '';
                })
                ->addColumn('firstdate', function ($data) {
                    return '';
                });
        } else {
            $datestart = date('Y-m-d H:i:s', strtotime($request->date_start));
            $dateend = date('Y-m-d 00:00:00', strtotime($request->date_end . "+1 day"));
            $start = "'$datestart'";
            $end = "'$dateend'";
            $number = "'$request->number'";
    
            $datalist = DB::table('nt_model')
                ->where('status_model', 1)
                ->where('nt_model.model_type', 'LIKE', 'SGT_%')
                ->select('nt_model.*', DB::raw('' . $start . ' as startdate'), DB::raw('' . $end . ' as enddate'), DB::raw('' . $number . ' as number'))
                ->get();
    
            $sQuery = Datatables::of($datalist)
                ->addIndexColumn()
                ->addColumn('lpccount', function ($data) {
                    [$firstdate, $lastdate] = $this->getLpcCountDateRange($data);
    
                    return DB::table('nt_data_lpc')
                        ->join('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_data_lpc.id_createlab')
                        ->where('nt_oqa_lab.save_date', '>=', $firstdate)
                        ->where('nt_oqa_lab.save_date', '<=', $lastdate)
                        ->where('nt_oqa_lab.lpc_confirm_st', 1)
                        ->where('nt_oqa_lab.id_model', $data->id_model)
                        ->count();
                })
                ->addColumn('semcount', function ($data) {
                    [$firstdate, $lastdate] = $this->getLpcCountDateRange($data);
    
                    return DB::table('nt_data_sem')
                        ->join('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_data_sem.id_createlab')
                        ->where('nt_oqa_lab.save_date', '>=', $firstdate)
                        ->where('nt_oqa_lab.save_date', '<=', $lastdate)
                        ->where('nt_oqa_lab.sem_confirm_st', 1)
                        ->where('nt_oqa_lab.id_model', $data->id_model)
                        ->count();
                })
                ->addColumn('iccount', function ($data) {
                    [$firstdate, $lastdate] = $this->getLpcCountDateRange($data);
    
                    return DB::table('nt_data_ic')
                        ->join('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_data_ic.id_createlab')
                        ->where('nt_oqa_lab.save_date', '>=', $firstdate)
                        ->where('nt_oqa_lab.save_date', '<=', $lastdate)
                        ->where('nt_oqa_lab.ic_confirm_st', 1)
                        ->where('nt_oqa_lab.id_model', $data->id_model)
                        ->count();
                })
                ->addColumn('gcmscount', function ($data) {
                    [$firstdate, $lastdate] = $this->getLpcCountDateRange($data);
    
                    return DB::table('nt_data_gcms')
                        ->join('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_data_gcms.id_createlab')
                        ->where('nt_oqa_lab.save_date', '>=', $firstdate)
                        ->where('nt_oqa_lab.save_date', '<=', $lastdate)
                        ->where('nt_oqa_lab.gcms_confirm_st', 1)
                        ->where('nt_oqa_lab.id_model', $data->id_model)
                        ->count();
                })
                ->addColumn('ftircount', function ($data) {
                    [$firstdate, $lastdate] = $this->getLpcCountDateRange($data);
    
                    return DB::table('nt_data_ftir')
                        ->join('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_data_ftir.id_createlab')
                        ->where('nt_oqa_lab.save_date', '>=', $firstdate)
                        ->where('nt_oqa_lab.save_date', '<=', $lastdate)
                        ->where('nt_oqa_lab.ftir_confirm_st', 1)
                        ->where('nt_oqa_lab.id_model', $data->id_model)
                        ->count();
                })
                ->addColumn('countdata', function ($data) {
                    return 2;
                })
                ->addColumn('lastdate', function ($data) {
                    [$firstdate, $lastdate] = $this->getLpcCountDateRange($data);
    
                    return $lastdate;
                })
                ->addColumn('firstdate', function ($data) {
                    [$firstdate, $lastdate] = $this->getLpcCountDateRange($data);
    
                    return $firstdate;
                });
        }
    
        return $sQuery->escapeColumns([])->make(true);
    }
  
    public function editdatalpc($id,$chk,$showeditalam)	
    {
        
        $data_lpc = DB::table('nt_data_lpc')->where('id_createlab', $id)->first();
        $oqa_lab = DB::table('nt_oqa_lab')
        ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
        ->join('nt_model','nt_model.id_model','=','nt_oqa_lab.id_model')
        ->where('nt_oqa_lab.id_createlab', $id)
        ->first();
        $oqa_labfc = DB::table('nt_datacf_lpc')
        ->where('id_createlab', $id)
        ->first();
        
        $spec = DB::table('nt_model_spec')
        ->join('nt_model','nt_model.id_model','=','nt_model_spec.id_model')
        ->where('nt_model_spec.id_model', $oqa_lab->id_model)
        ->where('nt_model_spec.id_model_type', 1)
        ->first();
        $ucl = DB::table('nt_model_spec')
        ->where('id_model', $oqa_lab->id_model)
        ->where('id_model_type', 3)
        ->first();
        return view('output/editdatalpc', [
            'data_lpc' => $data_lpc,
            'oqa_lab' => $oqa_lab,
            'spec' => $spec,
            'ucl' => $ucl,
            'oqa_labfc'=>$oqa_labfc,
            'chk' => $chk,
            'showeditalam' => $showeditalam,
            ]);
    }

    public function getcolumnelementsem()
    {
        $file = storage_path('NHK-File/template/report/SEMElement_template.xlsx');
        $excel = [];
        $columnname = [];
        $classname = [];
        Excel::load($file, function($reader) use (&$excel) {
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

        $x = 21;
        for ($i=26; $i < 93 ; $i++) {
                        // บรรทัดที่  x-1 | ลำดับคอลัมน์ที่ int -1
            $columnname[$x] = $excel[$i-1][1];
            $x++;
        }
        return $columnname;

    }

    public function getclasselementsem()
    {
        $file = storage_path('NHK-File/template/report/SEMElement_template.xlsx');
        $excel = [];
        $columnname = [];
        $classname = [];
        Excel::load($file, function($reader) use (&$excel) {
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

        $x = 21;
        for ($i=26; $i < 93 ; $i++) {
                // บรรทัดที่  x-1 | ลำดับคอลัมน์ที่ int -1
            $value = $excel[$i-1][0];
            $pieces = explode(" ", $value);
            $classname[$x] = $pieces[0]." ".@$pieces[1];

            $x++;
        }
        return $classname;

    }
     
    public function editdatasem($id,$chk,$showeditalam)	
    {
        $data_sem = DB::table('nt_data_sem')->where('id_createlab', $id)->first();
        $oqa_lab = DB::table('nt_oqa_lab')
        ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
        ->join('nt_model','nt_model.id_model','=','nt_oqa_lab.id_model')
        ->where('nt_oqa_lab.id_createlab', $id)
        ->first();
        $oqa_labfc = DB::table('nt_datacf_sem')
        ->where('id_createlab', $id)
        ->first();
        
        $spec = DB::table('nt_model_spec')
        ->join('nt_model','nt_model.id_model','=','nt_model_spec.id_model')
        ->where('nt_model_spec.id_model', $oqa_lab->id_model)
        ->where('nt_model_spec.id_model_type', 1)
        ->first();
        $ucl = DB::table('nt_model_spec')
        ->where('id_model', $oqa_lab->id_model)
        ->where('id_model_type', 3)
        ->first();

        $columnname_element = $this->getcolumnelementsem();
        $classname_element = $this->getclasselementsem();

        $value_element = [];
        $value_element[21] = $data_sem->I21;
        $value_element[22] = $data_sem->I22;
        $value_element[23] = $data_sem->I23;
        $value_element[24] = $data_sem->I24;
        $value_element[25] = $data_sem->I25;
        $value_element[26] = $data_sem->I26;
        $value_element[27] = $data_sem->I27;
        $value_element[28] = $data_sem->I28;
        $value_element[29] = $data_sem->I29;
        $value_element[30] = $data_sem->I30;
        $value_element[31] = $data_sem->I31;
        $value_element[32] = $data_sem->I32;
        $value_element[33] = $data_sem->I33;
        $value_element[34] = $data_sem->I34;
        $value_element[35] = $data_sem->I35;
        $value_element[36] = $data_sem->I36;
        $value_element[37] = $data_sem->I37;
        $value_element[38] = $data_sem->I38;
        $value_element[39] = $data_sem->I39;
        $value_element[40] = $data_sem->I40;
        $value_element[41] = $data_sem->I41;
        $value_element[42] = $data_sem->I42;
        $value_element[43] = $data_sem->I43;
        $value_element[44] = $data_sem->I44;
        $value_element[45] = $data_sem->I45;
        $value_element[46] = $data_sem->I46;
        $value_element[47] = $data_sem->I47;
        $value_element[48] = $data_sem->I48;
        $value_element[49] = $data_sem->I49;
        $value_element[50] = $data_sem->I50;
        $value_element[51] = $data_sem->I51;
        $value_element[52] = $data_sem->I52;
        $value_element[53] = $data_sem->I53;
        $value_element[54] = $data_sem->I54;
        $value_element[55] = $data_sem->I55;
        $value_element[56] = $data_sem->I56;
        $value_element[57] = $data_sem->I57;
        $value_element[58] = $data_sem->I58;
        $value_element[59] = $data_sem->I59;
        $value_element[60] = $data_sem->I60;
        $value_element[61] = $data_sem->I61;
        $value_element[62] = $data_sem->I62;
        $value_element[63] = $data_sem->I63;
        $value_element[64] = $data_sem->I64;
        $value_element[65] = $data_sem->I65;
        $value_element[66] = $data_sem->I66;
        $value_element[67] = $data_sem->I67;
        $value_element[68] = $data_sem->I68;
        $value_element[69] = $data_sem->I69;
        $value_element[70] = $data_sem->I70;
        $value_element[71] = $data_sem->I71;
        $value_element[72] = $data_sem->I72;
        $value_element[73] = $data_sem->I73;
        $value_element[74] = $data_sem->I74;
        $value_element[75] = $data_sem->I75;
        $value_element[76] = $data_sem->I76;
        $value_element[77] = $data_sem->I77;
        $value_element[78] = $data_sem->I78;
        $value_element[79] = $data_sem->I79;
        $value_element[80] = $data_sem->I80;
        $value_element[81] = $data_sem->I81;
        $value_element[82] = $data_sem->I82;
        $value_element[83] = $data_sem->I83;
        $value_element[84] = $data_sem->I84;
        $value_element[85] = $data_sem->I85;
        $value_element[86] = $data_sem->I86;
        $value_element[87] = $data_sem->I87;


        $value_last_element = [];
        $value_last_element[21] = $data_sem->last_I21;
        $value_last_element[22] = $data_sem->last_I22;
        $value_last_element[23] = $data_sem->last_I23;
        $value_last_element[24] = $data_sem->last_I24;
        $value_last_element[25] = $data_sem->last_I25;
        $value_last_element[26] = $data_sem->last_I26;
        $value_last_element[27] = $data_sem->last_I27;
        $value_last_element[28] = $data_sem->last_I28;
        $value_last_element[29] = $data_sem->last_I29;
        $value_last_element[30] = $data_sem->last_I30;
        $value_last_element[31] = $data_sem->last_I31;
        $value_last_element[32] = $data_sem->last_I32;
        $value_last_element[33] = $data_sem->last_I33;
        $value_last_element[34] = $data_sem->last_I34;
        $value_last_element[35] = $data_sem->last_I35;
        $value_last_element[36] = $data_sem->last_I36;
        $value_last_element[37] = $data_sem->last_I37;
        $value_last_element[38] = $data_sem->last_I38;
        $value_last_element[39] = $data_sem->last_I39;
        $value_last_element[40] = $data_sem->last_I40;
        $value_last_element[41] = $data_sem->last_I41;
        $value_last_element[42] = $data_sem->last_I42;
        $value_last_element[43] = $data_sem->last_I43;
        $value_last_element[44] = $data_sem->last_I44;
        $value_last_element[45] = $data_sem->last_I45;
        $value_last_element[46] = $data_sem->last_I46;
        $value_last_element[47] = $data_sem->last_I47;
        $value_last_element[48] = $data_sem->last_I48;
        $value_last_element[49] = $data_sem->last_I49;
        $value_last_element[50] = $data_sem->last_I50;
        $value_last_element[51] = $data_sem->last_I51;
        $value_last_element[52] = $data_sem->last_I52;
        $value_last_element[53] = $data_sem->last_I53;
        $value_last_element[54] = $data_sem->last_I54;
        $value_last_element[55] = $data_sem->last_I55;
        $value_last_element[56] = $data_sem->last_I56;
        $value_last_element[57] = $data_sem->last_I57;
        $value_last_element[58] = $data_sem->last_I58;
        $value_last_element[59] = $data_sem->last_I59;
        $value_last_element[60] = $data_sem->last_I60;
        $value_last_element[61] = $data_sem->last_I61;
        $value_last_element[62] = $data_sem->last_I62;
        $value_last_element[63] = $data_sem->last_I63;
        $value_last_element[64] = $data_sem->last_I64;
        $value_last_element[65] = $data_sem->last_I65;
        $value_last_element[66] = $data_sem->last_I66;
        $value_last_element[67] = $data_sem->last_I67;
        $value_last_element[68] = $data_sem->last_I68;
        $value_last_element[69] = $data_sem->last_I69;
        $value_last_element[70] = $data_sem->last_I70;
        $value_last_element[71] = $data_sem->last_I71;
        $value_last_element[72] = $data_sem->last_I72;
        $value_last_element[73] = $data_sem->last_I73;
        $value_last_element[74] = $data_sem->last_I74;
        $value_last_element[75] = $data_sem->last_I75;
        $value_last_element[76] = $data_sem->last_I76;
        $value_last_element[77] = $data_sem->last_I77;
        $value_last_element[78] = $data_sem->last_I78;
        $value_last_element[79] = $data_sem->last_I79;
        $value_last_element[80] = $data_sem->last_I80;
        $value_last_element[81] = $data_sem->last_I81;
        $value_last_element[82] = $data_sem->last_I82;
        $value_last_element[83] = $data_sem->last_I83;
        $value_last_element[84] = $data_sem->last_I84;
        $value_last_element[85] = $data_sem->last_I85;
        $value_last_element[86] = $data_sem->last_I86;
        $value_last_element[87] = $data_sem->last_I87;


        return view('output/editdatasem', [
            'data_sem' => $data_sem,
            'oqa_lab' => $oqa_lab,
            'spec' => $spec,
            'ucl' => $ucl,
            'oqa_labfc'=>$oqa_labfc,
            'chk' => $chk,
            'showeditalam' => $showeditalam,

            'classname_element' => $classname_element,
            'columnname_element' => $columnname_element,
            'value_element' => $value_element,
            'value_last_element' => $value_last_element,
        ]);
    }

    public function editdataic($id,$chk,$showeditalam)	
    { 
        //dd(0);
        $data_ic = DB::table('nt_data_ic')->where('id_createlab', $id)->first();
        $oqa_lab = DB::table('nt_oqa_lab')
        ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
        ->join('nt_model','nt_model.id_model','=','nt_oqa_lab.id_model')
        ->where('nt_oqa_lab.id_createlab', $id)
        ->first();
        $oqa_labfc = DB::table('nt_datacf_ic')
        ->where('id_createlab', $id)
        ->first();
        
        $spec = DB::table('nt_model_spec')
        ->join('nt_model','nt_model.id_model','=','nt_model_spec.id_model')
        ->where('nt_model_spec.id_model', $oqa_lab->id_model)
        ->where('nt_model_spec.id_model_type', 1)
        ->first();
        $ucl = DB::table('nt_model_spec')
        ->where('id_model', $oqa_lab->id_model)
        ->where('id_model_type', 3)
        ->first();
        return view('output/editdataic', [
            'data_ic' => $data_ic,
            'oqa_lab' => $oqa_lab,
            'spec' => $spec,
            'ucl' => $ucl,
            'oqa_labfc'=>$oqa_labfc,
            'chk' => $chk,
            'showeditalam' => $showeditalam,
            ]);
    }
    public function editdatagcms($id,$chk,$showeditalam)	
    {
        $data_gcms = DB::table('nt_data_gcms')->where('id_createlab', $id)->first();
        $oqa_lab = DB::table('nt_oqa_lab')
        ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
        ->join('nt_model','nt_model.id_model','=','nt_oqa_lab.id_model')
        ->where('nt_oqa_lab.id_createlab', $id)
        ->first();
        $oqa_labfc = DB::table('nt_datacf_gcms')
        ->where('id_createlab', $id)
        ->first();
        
        $spec = DB::table('nt_model_spec')
        ->join('nt_model','nt_model.id_model','=','nt_model_spec.id_model')
        ->where('nt_model_spec.id_model', $oqa_lab->id_model)
        ->where('nt_model_spec.id_model_type', 1)
        ->first();
        $ucl = DB::table('nt_model_spec')
        ->where('id_model', $oqa_lab->id_model)
        ->where('id_model_type', 3)
        ->first();
        return view('output/editdatagcms', [
            'data_gcms' => $data_gcms,
            'oqa_lab' => $oqa_lab,
            'spec' => $spec,
            'ucl' => $ucl,
            'oqa_labfc'=>$oqa_labfc,
            'chk' => $chk,
            'showeditalam' => $showeditalam,
            ]);
    }
    public function editdataftir($id,$chk,$showeditalam)	
    {
        $data_ftir = DB::table('nt_data_ftir')->where('id_createlab', $id)->first();
        $oqa_lab = DB::table('nt_oqa_lab')
        ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
        ->join('nt_model','nt_model.id_model','=','nt_oqa_lab.id_model')
        ->where('nt_oqa_lab.id_createlab', $id)
        ->first();
        $oqa_labfc = DB::table('nt_datacf_ftir')
        ->where('id_createlab', $id)
        ->first();
        
        $spec = DB::table('nt_model_spec')
        ->join('nt_model','nt_model.id_model','=','nt_model_spec.id_model')
        ->where('nt_model_spec.id_model', $oqa_lab->id_model)
        ->where('nt_model_spec.id_model_type', 1)
        ->first();
        $ucl = DB::table('nt_model_spec')
        ->where('id_model', $oqa_lab->id_model)
        ->where('id_model_type', 3)
        ->first();

        return view('output/editdataftir', [
            'data_ftir' => $data_ftir,
            'oqa_lab' => $oqa_lab,
            'spec' => $spec,
            'ucl' => $ucl,
            'oqa_labfc'=>$oqa_labfc,
            'chk' => $chk,
            'showeditalam' => $showeditalam,
            ]);
    }
    public function editdatasemtest($id)	
    {
        $data_semtest = DB::table('nt_data_semtest')->where('id_createlab', $id)->first();
        $oqa_lab = DB::table('nt_oqa_lab')
        ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
        ->join('nt_model','nt_model.id_model','=','nt_oqa_lab.id_model')
        ->where('nt_oqa_lab.id_createlab', $id)
        ->first();
        $oqa_labfc = DB::table('nt_datacf_semtest')
        ->where('id_createlab', $id)
        ->first();
        
        $spec = DB::table('nt_model_spec')
        ->join('nt_model','nt_model.id_model','=','nt_model_spec.id_model')
        ->where('nt_model_spec.id_model', $oqa_lab->id_model)
        ->where('nt_model_spec.id_model_type', 1)
        ->first();
        $ucl = DB::table('nt_model_spec')
        ->where('id_model', $oqa_lab->id_model)
        ->where('id_model_type', 3)
        ->first();

        return view('output/editdatasemtest', [
            'data_semtest' => $data_semtest,
            'oqa_lab' => $oqa_lab,
            'spec' => $spec,
            'ucl' => $ucl,
            'oqa_labfc'=>$oqa_labfc,
            'chk' => 1,
            'showeditalam' => 0,
            ]);
    }
    public function editdatalpcsave(Request $request)	
    {
       $lastdata =  DB::table('nt_data_lpc')->where('id_createlab', $request->input('id_oqa'))->first();
       
        $data = [
            'lpc_data' => $request->input('data_lpc'),
            'last_lpc_data' => $lastdata->lpc_data,
            'lpc1' => $request->input('data_lpc1'),
            'lpc2' => $request->input('data_lpc2'),
            'lpc3' => $request->input('data_lpc3'),
            'last_lpc1' => $lastdata->lpc1,
            'last_lpc2' => $lastdata->lpc2,
            'last_lpc3' => $lastdata->lpc3,
        ];
        
        DB::table('nt_data_lpc')->where('id_createlab', $request->input('id_oqa'))->update($data);

        $oqafile =  DB::table('nt_oqa_file')
       ->join('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_oqa_file.id_createlab')
       ->join('nt_model', 'nt_model.id_model', '=', 'nt_oqa_lab.id_model')
       ->where('nt_oqa_file.id_createlab',$request->input('id_oqa'))->first();
        $datamail = [
            'testter'=>"LPC",
            'model'=>$oqafile->name_model,
            'lot'=>$oqafile->lotnumber_lab,
            'date'=>date('d/m/Y H:i:s'),
        ];
        $mail = new MailController();
        $mail->maileditdata($datamail);
        //    nhkmail::maileditdata($datamail);
        return response()->json(['success' => 1,'id' => $request->input('id_oqa'),'chk'=>$request->input('chk')]);
    }
    public function editdatasemsave(Request $request)	
    {
        $data = [
            'last_sem_class1' => $request->input('sem_class1'),
            'last_sem_class2' => $request->input('sem_class2'),
            'last_sem_class3' => $request->input('sem_class3'),
            'last_sem_class3_1' => $request->input('sem_class3_1'),
            'last_sem_class3_2' => $request->input('sem_class3_2'),
            'last_sem_class3_3' => $request->input('sem_class3_3'),  
            'last_sem_class4' => $request->input('sem_class4'),  
            'last_sem_class5' => $request->input('sem_class5'),  
            'last_sem_class5_1' => $request->input('sem_class5_1'),  
            'last_sem_class6' => $request->input('sem_class6'),     
        ];
        DB::table('nt_data_sem')->where('id_createlab', $request->input('id_oqa'))->update($data);

        $elementupdate = [];
        for ($i=21; $i < 108 ; $i++) {
            $elementupdate['last_I'.$i] = $request->input('I'.$i);
        }
        DB::table('nt_data_sem')->where('id_createlab', $request->input('id_oqa'))->update($elementupdate);
        
        $oqafile =  DB::table('nt_oqa_file')
        ->join('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_oqa_file.id_createlab')
        ->join('nt_model', 'nt_model.id_model', '=', 'nt_oqa_lab.id_model')
        ->where('nt_oqa_file.id_createlab',$request->input('id_oqa'))->first();
         $datamail = [
             'testter'=>"SEM",
             'model'=>$oqafile->name_model,
             'lot'=>$oqafile->lotnumber_lab,
             'date'=>date('d/m/Y H:i:s'),
         ];
         $mail = new MailController();
         $mail->maileditdata($datamail);
        //  nhkmail::maileditdata($datamail); 

        return response()->json(['success' => 1]);
    }

    public function editdataicsave(Request $request)	
    {
        $data = [
            'ic_anion_fluoride' => $request->input('ic_anion_fluoride'),
            'ic_anion_chloride' => $request->input('ic_anion_chloride'),
            'ic_anion_nitrile' => $request->input('ic_anion_nitrile'),
            'ic_anion_bromid' => $request->input('ic_anion_bromid'),
            'ic_anion_nitrate' => $request->input('ic_anion_nitrate'),
            'ic_anion_sulphate' => $request->input('ic_anion_sulphate'),  
            'ic_anion_phosphate' => $request->input('ic_anion_phosphate'), 
            'ic_anion_up' => $request->input('ic_anion_up'), 
            'ic_sulphate' => $request->input('ic_sulphate'),  
            'ic_cation_lithium' => $request->input('ic_cation_lithium'),  
            'ic_cation_sodium' => $request->input('ic_cation_sodium'),        
            'ic_cation_ammonium' => $request->input('ic_cation_ammonium'),  
            'ic_cation_potassium' => $request->input('ic_cation_potassium'),  
            'ic_cation_magnesium' => $request->input('ic_cation_magnesium'),  
            'ic_cation_calcium' => $request->input('ic_cation_calcium'), 
            'ic_cation_up' => $request->input('ic_cation_up'), 
        ];
        DB::table('nt_data_ic')->where('id_createlab', $request->input('id_oqa'))->update($data);
        $oqafile =  DB::table('nt_oqa_file')
        ->join('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_oqa_file.id_createlab')
        ->join('nt_model', 'nt_model.id_model', '=', 'nt_oqa_lab.id_model')
        ->where('nt_oqa_file.id_createlab',$request->input('id_oqa'))->first();
         $datamail = [
             'testter'=>"IC",
             'model'=>$oqafile->name_model,
             'lot'=>$oqafile->lotnumber_lab,
             'date'=>date('d/m/Y H:i:s'),
         ];
         $mail = new MailController();
         $mail->maileditdata($datamail);
        //  nhkmail::maileditdata($datamail); 
        return response()->json(['success' => 1]);
    }
    public function editdatagcmssave(Request $request)	
    {
        $data = [
            'gcms_smear' => $request->input('gcms_smear'),
            'gcms_outgassing' => $request->input('gcms_outgassing'),
            'gcms_low_mw' => $request->input('gcms_low_mw'),
            'gcms_high_mw' => $request->input('gcms_high_mw'),       
        ];
        DB::table('nt_data_gcms')->where('id_createlab', $request->input('id_oqa'))->update($data);
        $oqafile =  DB::table('nt_oqa_file')
        ->join('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_oqa_file.id_createlab')
        ->join('nt_model', 'nt_model.id_model', '=', 'nt_oqa_lab.id_model')
        ->where('nt_oqa_file.id_createlab',$request->input('id_oqa'))->first();
         $datamail = [
             'testter'=>"GCMS",
             'model'=>$oqafile->name_model,
             'lot'=>$oqafile->lotnumber_lab,
             'date'=>date('d/m/Y H:i:s'),
         ];
         $mail = new MailController();
         $mail->maileditdata($datamail);
        //  nhkmail::maileditdata($datamail);
        return response()->json(['success' => 1]);
    }
    public function editdataftirsave(Request $request)	
    {
        $data = [
            'ftir_silicone' => $request->input('ftir_silicone'),
        ];
        DB::table('nt_data_ftir')->where('id_createlab', $request->input('id_oqa'))->update($data);
        $oqafile =  DB::table('nt_oqa_file')
        ->join('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_oqa_file.id_createlab')
        ->join('nt_model', 'nt_model.id_model', '=', 'nt_oqa_lab.id_model')
        ->where('nt_oqa_file.id_createlab',$request->input('id_oqa'))->first();
         $datamail = [
             'testter'=>"FTIR",
             'model'=>$oqafile->name_model,
             'lot'=>$oqafile->lotnumber_lab,
             'date'=>date('d/m/Y H:i:s'),
         ];
         $mail = new MailController();
         $mail->maileditdata($datamail);
        //  nhkmail::maileditdata($datamail); 
        return response()->json(['success' => 1,'id' => $request->input('id_oqa')]);
    }

    public function editdatasemtestsave(Request $request)	
    {
        $lastdata =  DB::table('nt_data_semtest')->where('id_createlab', $request->input('id_oqa'))->first();
        $data = [
            'semtest_data' => $request->input('semtest_silicone'),
            'last_semtest_data' => $lastdata->semtest_data,
        ];
        DB::table('nt_data_semtest')->where('id_createlab', $request->input('id_oqa'))->update($data);
        $oqafile =  DB::table('nt_oqa_file')
        ->join('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_oqa_file.id_createlab')
        ->join('nt_model', 'nt_model.id_model', '=', 'nt_oqa_lab.id_model')
        ->where('nt_oqa_file.id_createlab',$request->input('id_oqa'))->first();
         $datamail = [
             'testter'=>"SEM (Ghost)",
             'model'=>$oqafile->name_model,
             'lot'=>$oqafile->lotnumber_lab,
             'date'=>date('d/m/Y H:i:s'),
         ];
         //dd($datamail);
         $mail = new MailController();
         $mail->maileditdata($datamail);
        // nhkmail::maileditdata($datamail); 
        return response()->json(['success' => 1,'id' => $request->input('id_oqa')]);
    }
    public function viewdata($id)	
    {
        $data = DB::table('nt_oqa_lab')
        ->join('nt_model','nt_model.id_model','=','nt_oqa_lab.id_model')
        ->where('id_createlab', $id)->first();
        if (!$data) {
            // แจ้งเตือน และ redirect กลับ
            return redirect()->back()->with('error', 'ไม่พบข้อมูล');
        }

        return view('output/view', [ 
            'id' => $data->id_model,
            'idcreatelab' => $data->id_createlab,
            'laball' => $data,
        ]);
    }

    public function viewdataall($id)	
    {
       
        $data = DB::table('nt_oqa_lab')
        ->join('nt_model','nt_model.id_model','=','nt_oqa_lab.id_model')
        ->where('nt_model.id_model', $id)->orderby('nt_oqa_lab.id_createlab','desc')->first();
        if(empty($data))
        {
            $model = DB::table('nt_model')->where('id_model', $id)->first();
            return view('support/warningview', [
                'name_model' =>$model->name_model,
            ]);
        }
        else
        {

            if($id == 380 || $id == 234){
                return view('output/viewcustomer2', [
                    'id' =>$data->id_model,
                    'idcreatelab' =>$data->id_createlab,
                    'laball' =>$data,
                ]);
            }else{
                return view('output/viewcustomer', [
                    'id' =>$data->id_model,
                    'idcreatelab' =>$data->id_createlab,
                    'laball' =>$data,
                ]);
            }

        }
        
    }

    public function viewdatarawall($id)	
    {
        $data = DB::table('nt_oqa_lab')
        ->join('nt_model','nt_model.id_model','=','nt_oqa_lab.id_model')
        ->where('nt_model.id_model', $id)->orderby('nt_oqa_lab.id_createlab','desc')->first();
        if(empty($data))
        {
            $model = DB::table('nt_model')->where('id_model', $id)->first();
            return view('support/warningview', [
                'name_model' =>$model->name_model,
            ]);
        }
        else
        {
        return view('output/viewraw', [
            'id' =>$data->id_model,
            'idcreatelab' =>$data->id_createlab,
            'laball' =>$data,
        ]);
        }
    }


    public function export_pdf()	
    {
       $pdf = PDF::loadView('output/export_pdf',[])->setPaper('a5', 'landscape');
       return  @$pdf->stream();
    }
   
    public function raw_csvexcel(Request $request)	
    { 
            $dataexport = [];
            $lotuparray= [];
            $lotdwarray= [];
    
            $lotsemuparray= [];
            $lotsemdwarray= [];
            
            $loticuparray= [];
            $loticdwarray= [];
    
            $lotgcmsuparray= [];
            $lotgcmsdwarray= [];
    
            $lotftiruparray= [];
            $lotftirdwarray= [];
            ini_set('memory_limit', '-1');
           if(isset($request->id))
           {
               if(count($request->id) == 2)
                {
                $a1 = DB::table('nt_model')->where('id_model', $request->id[0])->first();
                $a2 = DB::table('nt_model')->where('id_model', $request->id[1])->first();
    
                $text1 = $a1->name_model;
                $text2 = $a2->name_model;
                $chktext1 = mb_substr($text1,0,-3);
                $chktext2 = mb_substr($text2,0,-3);
                    if($chktext1 == $chktext2)
                    {
                        if($a1->part == "UP")
                        {
                            $model1 = $a1;
                        }
                        else
                        {
                            $model1 = $a2;
                        }
                        if($a2->part == "DN")
                        {
                            $model2 = $a2;
                        }
                        else
                        {
                            $model2 = $a1;
                        }
                        $dateend1 = date('Y-m-d 00:00:00', strtotime($request->input('dateend'.$model1->id_model). ' + 1 day'));
                        $dateend2 = date('Y-m-d 00:00:00', strtotime($request->input('dateend'.$model2->id_model). ' + 1 day'));
                        $datalup = DB::table('nt_datacf_lpc')
                        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_datacf_lpc.id_createlab')
                        ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                        ->where('nt_oqa_lab.save_date','>=',$request->input('datestart'.$model1->id_model))
                        ->where('nt_oqa_lab.save_date','<=',$dateend1)
                        ->where('nt_oqa_lab.id_model',$model1->id_model)
                        ->where('nt_oqa_lab.lpc_confirm_st',1)
                        ->orderBy('nt_oqa_lab.save_date', 'asc')->get();
    
                        $dataldw = DB::table('nt_datacf_lpc')
                        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_datacf_lpc.id_createlab')
                        ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                        ->where('nt_oqa_lab.save_date','>=',$request->input('datestart'.$model2->id_model))
                        ->where('nt_oqa_lab.save_date','<=',$dateend2)
                        ->where('nt_oqa_lab.id_model',$model2->id_model)
                        ->where('nt_oqa_lab.lpc_confirm_st',1)
                        ->orderBy('nt_oqa_lab.save_date', 'asc')->get();
                        foreach ($datalup as $v) { 
                            $sublot = substr($v->lotnumber_lab,1,2);   
                            $newdata =  array (
                             'lot' =>  $sublot, 
                            );
                             array_push($lotuparray,$sublot);
                          } 
                         $datalotup = array_unique($lotuparray);
                 
                         foreach ($dataldw as $v) { 
                             $sublot = substr($v->lotnumber_lab,1,2);   
                             $newdata =  array (
                              'lot' =>  $sublot, 
                             );
                              array_push($lotdwarray,$sublot);
                           } 
                          $datalotdw = array_unique($lotdwarray);
    
    
                          $datalsemup = DB::table('nt_datacf_sem')
                          ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_datacf_sem.id_createlab')
                          ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                          ->where('nt_oqa_lab.save_date','>=',$request->input('datestart'.$model1->id_model))
                          ->where('nt_oqa_lab.save_date','<=',$dateend1)
                          ->where('nt_oqa_lab.id_model',$model1->id_model)
                          ->where('nt_oqa_lab.sem_confirm_st',1)
                          ->orderBy('nt_oqa_lab.save_date', 'asc')->get();
                          $datalsemdw = DB::table('nt_datacf_sem')
                          ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_datacf_sem.id_createlab')
                          ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                          ->where('nt_oqa_lab.save_date','>=',$request->input('datestart'.$model2->id_model))
                          ->where('nt_oqa_lab.save_date','<=',$dateend2)
                          ->where('nt_oqa_lab.id_model',$model2->id_model)
                          ->where('nt_oqa_lab.sem_confirm_st',1)
                          ->orderBy('nt_oqa_lab.save_date', 'asc')->get();
                          foreach ($datalsemup as $v) { 
                              $sublot = substr($v->lotnumber_lab,1,2);   
                              $newdata =  array (
                               'lot' =>  $sublot, 
                              );
                               array_push($lotsemuparray,$sublot);
                            } 
                           $datalotsemup = array_unique($lotsemuparray);
                   
                           foreach ($datalsemdw as $v) { 
                               $sublot = substr($v->lotnumber_lab,1,2);   
                               $newdata =  array (
                                'lot' =>  $sublot, 
                               );
                                array_push($lotsemdwarray,$sublot);
                             } 
                            $datalotsemdw = array_unique($lotsemdwarray);
    
    
                            $datalicup = DB::table('nt_datacf_ic')
                            ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_datacf_ic.id_createlab')
                            ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                            ->where('nt_oqa_lab.save_date','>=',$request->input('datestart'.$model1->id_model))
                            ->where('nt_oqa_lab.save_date','<=',$dateend1)
                            ->where('nt_oqa_lab.id_model',$model1->id_model)
                            ->where('nt_oqa_lab.ic_confirm_st',1)
                            ->orderBy('nt_oqa_lab.save_date', 'asc')->get();
                            $datalicdw = DB::table('nt_datacf_ic')
                            ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_datacf_ic.id_createlab')
                            ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                            ->where('nt_oqa_lab.save_date','>=',$request->input('datestart'.$model2->id_model))
                            ->where('nt_oqa_lab.save_date','<=',$dateend2)
                            ->where('nt_oqa_lab.id_model',$model2->id_model)
                            ->where('nt_oqa_lab.ic_confirm_st',1)
                            ->orderBy('nt_oqa_lab.save_date', 'asc')->get();
                            foreach ($datalicup as $v) { 
                                $sublot = substr($v->lotnumber_lab,1,2);   
                                $newdata =  array (
                                 'lot' =>  $sublot, 
                                );
                                 array_push($loticuparray,$sublot);
                              } 
                             $dataloticup = array_unique($loticuparray);
                     
                             foreach ($datalicdw as $v) { 
                                 $sublot = substr($v->lotnumber_lab,1,2);   
                                 $newdata =  array (
                                  'lot' =>  $sublot, 
                                 );
                                  array_push($loticdwarray,$sublot);
                               } 
                              $dataloticdw = array_unique($loticdwarray);
    
                            $datalgcmsup = DB::table('nt_datacf_gcms')
                            ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_datacf_gcms.id_createlab')
                            ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                            ->where('nt_oqa_lab.save_date','>=',$request->input('datestart'.$model1->id_model))
                            ->where('nt_oqa_lab.save_date','<=',$dateend1)
                            ->where('nt_oqa_lab.id_model',$model1->id_model)
                            ->where('nt_oqa_lab.gcms_confirm_st',1)
                            ->orderBy('nt_oqa_lab.save_date', 'asc')->get();
                            $datalgcmsdw = DB::table('nt_datacf_gcms')
                            ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_datacf_gcms.id_createlab')
                            ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                            ->where('nt_oqa_lab.save_date','>=',$request->input('datestart'.$model2->id_model))
                            ->where('nt_oqa_lab.save_date','<=',$dateend2)
                            ->where('nt_oqa_lab.id_model',$model2->id_model)
                            ->where('nt_oqa_lab.gcms_confirm_st',1)
                            ->orderBy('nt_oqa_lab.save_date', 'asc')->get();
                            foreach ($datalgcmsup as $v) { 
                                $sublot = substr($v->lotnumber_lab,1,2);   
                                $newdata =  array (
                                 'lot' =>  $sublot, 
                                );
                                 array_push($lotgcmsuparray,$sublot);
                              } 
                             $datalotgcmsup = array_unique($lotgcmsuparray);
                     
                             foreach ($datalgcmsdw as $v) { 
                                 $sublot = substr($v->lotnumber_lab,1,2);   
                                 $newdata =  array (
                                  'lot' =>  $sublot, 
                                 );
                                  array_push($lotgcmsdwarray,$sublot);
                               } 
                              $datalotgcmsdw = array_unique($lotgcmsdwarray);
    
                            $datalftirup = DB::table('nt_datacf_ftir')
                            ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_datacf_ftir.id_createlab')
                            ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                            ->where('nt_oqa_lab.save_date','>=',$request->input('datestart'.$model1->id_model))
                            ->where('nt_oqa_lab.save_date','<=',$dateend1)
                            ->where('nt_oqa_lab.id_model',$model1->id_model)
                            ->where('nt_oqa_lab.ftir_confirm_st',1)
                            ->orderBy('nt_oqa_lab.save_date', 'asc')->get();
                            $datalftirdw = DB::table('nt_datacf_ftir')
                            ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_datacf_ftir.id_createlab')
                            ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                            ->where('nt_oqa_lab.save_date','>=',$request->input('datestart'.$model2->id_model))
                            ->where('nt_oqa_lab.save_date','<=',$dateend2)
                            ->where('nt_oqa_lab.id_model',$model2->id_model)
                            ->where('nt_oqa_lab.ftir_confirm_st',1)
                            ->orderBy('nt_oqa_lab.save_date', 'asc')->get();
                            foreach ($datalftirup as $v) { 
                                $sublot = substr($v->lotnumber_lab,1,2);   
                                $newdata =  array (
                                 'lot' =>  $sublot, 
                                );
                                 array_push($lotftiruparray,$sublot);
                              } 
                             $datalotftirup = array_unique($lotftiruparray);
                     
                             foreach ($datalftirdw as $v) { 
                                 $sublot = substr($v->lotnumber_lab,1,2);   
                                 $newdata =  array (
                                  'lot' =>  $sublot, 
                                 );
                                  array_push($lotftirdwarray,$sublot);
                               } 
                              $datalotftirdw = array_unique($lotftirdwarray);
                    }
                    else
                    {
                    return response()->json(['success' => 2]);
                    }
                }
                else
                {
                return response()->json(['success' => 3]);
                }
            }
           else
           {
           return response()->json(['success' => 4]);
           }
            foreach ($request->id as $item) {
                $model = DB::table('nt_model')->where('id_model', $item)->first();
                $datestart = $request->input('datestart'.$item);
                $dateend = $request->input('dateend'.$item);
                $lpccount = $request->input('lpccount'.$item);
                $semcount = $request->input('semcount'.$item);
                $iccount = $request->input('iccount'.$item);
                $gcmscount = $request->input('gcmscount'.$item);
                $ftircount = $request->input('ftircount'.$item);
                $newdata =  array (
                    'id' => $item,
                    'datestart' => $datestart,
                    'dateend' => $dateend,
                    'lpccount' => $lpccount,
                    'semcount' => $semcount,
                    'iccount' => $iccount,
                    'gcmscount' => $gcmscount,
                    'ftircount' => $ftircount,
                    'model' => $model,   
                );
                array_push($dataexport,$newdata);
            }
            $spec = DB::table('nt_model_spec')->where('id_model', $model1->id_model)->where('id_model_type', 3)->first();
            $tl = DB::table('nt_model_spec')->where('id_model', $model1->id_model)->where('id_model_type', 2)->first();
            $ucl = DB::table('nt_model_spec')->where('id_model', $model1->id_model)->where('id_model_type', 1)->first();
            $spec1 = DB::table('nt_model_spec')->where('id_model', $model2->id_model)->where('id_model_type', 3)->first();
            $tl1 = DB::table('nt_model_spec')->where('id_model', $model2->id_model)->where('id_model_type', 2)->first();
            $ucl1 = DB::table('nt_model_spec')->where('id_model', $model2->id_model)->where('id_model_type', 1)->first();
            $file = storage_path('NHK-File/template/report/csv_file.xlsx');
            $columnlotup= ['C','D','E','F','G','H'];
            $columnlotdw= ['P','Q','R','S','T','U'];
            $spacename = str_replace(' ', '', $chktext1);
            $namefile = 'CSV_Raw_'.$spacename.'_'.date("YmdHis");
            Excel::load($file, function ($doc)use($model1,$model2,$datalup,$dataldw,$datalsemup,$datalsemdw,$datalicup,$datalicdw,$datalgcmsup,$datalgcmsdw,$datalftirup,$datalftirdw,$spec,$tl,$ucl,$spec1,$tl1,$ucl1,$datalotup,$datalotdw,$datalotsemup,$datalotsemdw,$dataloticup,$dataloticdw,$datalotgcmsup,$datalotgcmsdw,$datalotftirup,$datalotftirdw,$columnlotup,$columnlotdw) {
                /////////////////// LPC ////////////////////////////////
               
    
                $sheet = $doc->setActiveSheetIndex(0);
         
    
                $ilot = 0;
                $columnadd =[];
                $row = array();
                $ilotdw = 0;
                $columnadddw =[];
                $rowdw = array();
                foreach ($datalotup as $lot) {    
                  $sheet->setCellValue($columnlotup[$ilot].'6',$lot);
                  $newdata =  array (
                    'column' =>  $columnlotup[$ilot], 
                    'lot' =>  $lot,
                   );
                   array_push($columnadd,$newdata);
                   $row[$lot] = $columnlotup[$ilot];
                   $ilot++;
                }
                
                foreach ($datalotdw as $lot) {    
                    $sheet->setCellValue($columnlotdw[$ilotdw].'6',$lot);
                    $newdata =  array (
                      'column' =>  $columnlotdw[$ilotdw], 
                      'lot' =>  $lot,
                     );
                     array_push($columnadddw,$newdata);
                     $rowdw[$lot] = $columnlotdw[$ilotdw];
                     $ilotdw++;
                  }
                $i = 7;
                $sheet->setCellValue('B1',$model1->name_model);
                $sheet->setCellValue('P1',$model2->name_model);
                foreach ($datalup as $item) {  
                    $stdate = date('Y-m-d', strtotime($item->save_date));
                    $datafirst = DB::table('nt_model_history')
                    ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model_history.id_model', $item->id_model)
                    ->where('nt_model_history.date_start','<=',$stdate)
                    ->where('nt_model_history.date_end','>=',$stdate)
                    ->get();
                    if($datafirst->count() == 0)
                    {
                     $data1 = DB::table('nt_model_history')
                     ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                     ->where('nt_model_history.id_model', $item->id_model)
                     ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                     ->first();
                     
                     $datafirst = DB::table('nt_model_history')
                     ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                     ->where('nt_model_history.id_model', $item->id_model)
                     ->where('nt_model_history.date_start','<=',$data1->date_start)  
                     ->where('nt_model_history.date_end','>=',$data1->date_start)
                     ->get(); 
                    }
                   $sublot = substr($item->lotnumber_lab,1,2);
                   $sheet->setCellValue('B'.$i,date('d-M-y',strtotime($item->save_date)));
                   $sheet->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->alpc,0));
                   $sheet->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[0]->alpc,0));
                   $sheet->setCellValue('K'.$i,slope::nhk_formatnumber($ucl->alpc,0));
                   $sheet->setCellValue('L'.$i,$item->lotnumber_lab);
                   
                   $sheet->setCellValue($row[$sublot].$i,slope::nhk_formatnumber($item->lpc_data,0));
                   $i++;
                }
                $v = 7;
                foreach ($dataldw as $item) {   
                    $stdate = date('Y-m-d', strtotime($item->save_date));
                    $datafirst = DB::table('nt_model_history')
                    ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model_history.id_model', $item->id_model)
                    ->where('nt_model_history.date_start','<=',$stdate)
                    ->where('nt_model_history.date_end','>=',$stdate)
                    ->get(); 
                    if($datafirst->count() == 0)
                    {
                     $data1 = DB::table('nt_model_history')
                     ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                     ->where('nt_model_history.id_model', $item->id_model)
                     ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                     ->first();
                     
                     $datafirst = DB::table('nt_model_history')
                     ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                     ->where('nt_model_history.id_model', $item->id_model)
                     ->where('nt_model_history.date_start','<=',$data1->date_start)  
                     ->where('nt_model_history.date_end','>=',$data1->date_start)
                     ->get(); 
                    }    
                    $sublot = substr($item->lotnumber_lab,1,2);
                    $sheet->setCellValue('O'.$v,date('d-M-y',strtotime($item->save_date)));
                    $sheet->setCellValue('V'.$v,slope::nhk_formatnumber($datafirst[1]->alpc,0));
                    $sheet->setCellValue('W'.$v,slope::nhk_formatnumber($datafirst[0]->alpc,0));  
                    $sheet->setCellValue('X'.$v,slope::nhk_formatnumber($ucl1->alpc,0));
                    $sheet->setCellValue('Y'.$v,slope::nhk_formatnumber($item->lotnumber_lab,0));   
                    $sheet->setCellValue($rowdw[$sublot].$v,slope::nhk_formatnumber($item->lpc_data,0));
                    $v++;
                 }
                 /////////////////// END LPC ////////////////////////////////
    
                 ///////////////////  SEM CLASS 1  ////////////////////////////////
                 $sheetc1 = $doc->setActiveSheetIndex(1);
                 $ilot = 0;
                 $columnadd =[];
                 $row = array();
                 $ilotdw = 0;
                 $columnadddw =[];
                 $rowdw = array();
                 foreach ($datalotsemup as $lot) {    
                   $sheetc1->setCellValue($columnlotup[$ilot].'6',$lot);
                   $newdata =  array (
                     'column' =>  $columnlotup[$ilot], 
                     'lot' =>  $lot,
                    );
                    array_push($columnadd,$newdata);
                    $row[$lot] = $columnlotup[$ilot];
                    $ilot++;
                 }
                 
                 foreach ($datalotsemdw as $lot) {    
                     $sheetc1->setCellValue($columnlotdw[$ilotdw].'6',$lot);
                     $newdata =  array (
                       'column' =>  $columnlotdw[$ilotdw], 
                       'lot' =>  $lot,
                      );
                      array_push($columnadddw,$newdata);
                      $rowdw[$lot] = $columnlotdw[$ilotdw];
                      $ilotdw++;
                   }
                 $i = 7;
                 $sheetc1->setCellValue('B1',$model1->name_model);
                 $sheetc1->setCellValue('P1',$model2->name_model);
                 foreach ($datalsemup as $item) {  
                    $stdate = date('Y-m-d', strtotime($item->save_date));
                    $datafirst = DB::table('nt_model_history')
                    ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model_history.id_model', $item->id_model)
                    ->where('nt_model_history.date_start','<=',$stdate)
                    ->where('nt_model_history.date_end','>=',$stdate)
                    ->get();
                    if($datafirst->count() == 0)
                    {
                     $data1 = DB::table('nt_model_history')
                     ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                     ->where('nt_model_history.id_model', $item->id_model)
                     ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                     ->first();
                     
                     $datafirst = DB::table('nt_model_history')
                     ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                     ->where('nt_model_history.id_model', $item->id_model)
                     ->where('nt_model_history.date_start','<=',$data1->date_start)  
                     ->where('nt_model_history.date_end','>=',$data1->date_start)
                     ->get(); 
                    }
                    $sublot = substr($item->lotnumber_lab,1,2);
                    $sheetc1->setCellValue('B'.$i,date('d-M-y',strtotime($item->save_date)));
                    $sheetc1->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->class1,1));
                    $sheetc1->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[0]->class1,1));
                    $sheetc1->setCellValue('K'.$i,slope::nhk_formatnumber($ucl->class1,1));
                    $sheetc1->setCellValue('L'.$i,$item->lotnumber_lab);
                    
                    $sheetc1->setCellValue($row[$sublot].$i,slope::nhk_formatnumber($item->sem_class1,1));
                    $i++;
                 }
                 $v = 7;
                 foreach ($datalsemdw as $item) {        
                    $stdate = date('Y-m-d', strtotime($item->save_date));
                    $datafirst = DB::table('nt_model_history')
                    ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model_history.id_model', $item->id_model)
                    ->where('nt_model_history.date_start','<=',$stdate)
                    ->where('nt_model_history.date_end','>=',$stdate)
                    ->get();
                    if($datafirst->count() == 0)
                    {
                     $data1 = DB::table('nt_model_history')
                     ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                     ->where('nt_model_history.id_model', $item->id_model)
                     ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                     ->first();
                     
                     $datafirst = DB::table('nt_model_history')
                     ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                     ->where('nt_model_history.id_model', $item->id_model)
                     ->where('nt_model_history.date_start','<=',$data1->date_start)  
                     ->where('nt_model_history.date_end','>=',$data1->date_start)
                     ->get(); 
                    }
                     $sublot = substr($item->lotnumber_lab,1,2);
                     $sheetc1->setCellValue('O'.$v,date('d-M-y',strtotime($item->save_date)));
                     $sheetc1->setCellValue('V'.$v,slope::nhk_formatnumber($datafirst[1]->class1,1));
                     $sheetc1->setCellValue('W'.$v,slope::nhk_formatnumber($datafirst[0]->class1,1));  
                     $sheetc1->setCellValue('X'.$v,slope::nhk_formatnumber($ucl1->class1,1));
                     $sheetc1->setCellValue('Y'.$v,$item->lotnumber_lab);   
                     $sheetc1->setCellValue($rowdw[$sublot].$v,slope::nhk_formatnumber($item->sem_class1,1));
                     $v++;
                  }
                  ///////////////////  END SEM CLASS 1  ////////////////////////////////
    
                ///////////////////  SEM CLASS 2  ////////////////////////////////
                $sheetc2 = $doc->setActiveSheetIndex(2);
                 $ilot = 0;
                 $columnadd =[];
                 $row = array();
                 $ilotdw = 0;
                 $columnadddw =[];
                 $rowdw = array();
                 foreach ($datalotsemup as $lot) {    
                   $sheetc2->setCellValue($columnlotup[$ilot].'6',$lot);
                   $newdata =  array (
                     'column' =>  $columnlotup[$ilot], 
                     'lot' =>  $lot,
                    );
                    array_push($columnadd,$newdata);
                    $row[$lot] = $columnlotup[$ilot];
                    $ilot++;
                 }
              
                 foreach ($datalotsemdw as $lot) {    
                     $sheetc2->setCellValue($columnlotdw[$ilotdw].'6',$lot);
                     $newdata =  array (
                       'column' =>  $columnlotdw[$ilotdw], 
                       'lot' =>  $lot,
                      );
                      array_push($columnadddw,$newdata);
                      $rowdw[$lot] = $columnlotdw[$ilotdw];
                      $ilotdw++;
                   }
                 $i = 7;
                 $sheetc2->setCellValue('B1',$model1->name_model);
                 $sheetc2->setCellValue('P1',$model2->name_model);
                 foreach ($datalsemup as $item) {  
                    $stdate = date('Y-m-d', strtotime($item->save_date));
                    $datafirst = DB::table('nt_model_history')
                    ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model_history.id_model', $item->id_model)
                    ->where('nt_model_history.date_start','<=',$stdate)
                    ->where('nt_model_history.date_end','>=',$stdate)
                    ->get();
                    if($datafirst->count() == 0)
                    {
                     $data1 = DB::table('nt_model_history')
                     ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                     ->where('nt_model_history.id_model', $item->id_model)
                     ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                     ->first();
                     
                     $datafirst = DB::table('nt_model_history')
                     ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                     ->where('nt_model_history.id_model', $item->id_model)
                     ->where('nt_model_history.date_start','<=',$data1->date_start)  
                     ->where('nt_model_history.date_end','>=',$data1->date_start)
                     ->get(); 
                    }
                    $sublot = substr($item->lotnumber_lab,1,2);
                    $sheetc2->setCellValue('B'.$i,date('d-M-y',strtotime($item->save_date)));
                    $sheetc2->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->class2,1));
                    $sheetc2->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[0]->class2,1));
                    $sheetc2->setCellValue('K'.$i,slope::nhk_formatnumber($ucl->class2,1));
                    $sheetc2->setCellValue('L'.$i,$item->lotnumber_lab);
                    
                    $sheetc2->setCellValue($row[$sublot].$i,slope::nhk_formatnumber($item->sem_class2,1));
                    $i++;
                 }
                 $v = 7;
                 foreach ($datalsemdw as $item) {        
                    $stdate = date('Y-m-d', strtotime($item->save_date));
                    $datafirst = DB::table('nt_model_history')
                    ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model_history.id_model', $item->id_model)
                    ->where('nt_model_history.date_start','<=',$stdate)
                    ->where('nt_model_history.date_end','>=',$stdate)
                    ->get();
                    if($datafirst->count() == 0)
                    {
                     $data1 = DB::table('nt_model_history')
                     ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                     ->where('nt_model_history.id_model', $item->id_model)
                     ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                     ->first();
                     
                     $datafirst = DB::table('nt_model_history')
                     ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                     ->where('nt_model_history.id_model', $item->id_model)
                     ->where('nt_model_history.date_start','<=',$data1->date_start)  
                     ->where('nt_model_history.date_end','>=',$data1->date_start)
                     ->get(); 
                    }
                     $sublot = substr($item->lotnumber_lab,1,2);
                     $sheetc2->setCellValue('O'.$v,date('d-M-y',strtotime($item->save_date)));
                     $sheetc2->setCellValue('V'.$v,slope::nhk_formatnumber($datafirst[1]->class2,1));
                     $sheetc2->setCellValue('W'.$v,slope::nhk_formatnumber($datafirst[0]->class2,1));  
                     $sheetc2->setCellValue('X'.$v,slope::nhk_formatnumber($ucl1->class2,1));
                     $sheetc2->setCellValue('Y'.$v,$item->lotnumber_lab);   
                     $sheetc2->setCellValue($rowdw[$sublot].$v,slope::nhk_formatnumber($item->sem_class2,1));
                     $v++;
                  }
                  ///////////////////  END SEM CLASS 2  ////////////////////////////////
    
                      ///////////////////  SEM CLASS 3  ////////////////////////////////
                $sheetc3 = $doc->setActiveSheetIndex(3);
                $ilot = 0;
                $columnadd =[];
                $row = array();
                $ilotdw = 0;
                $columnadddw =[];
                $rowdw = array();
                foreach ($datalotsemup as $lot) {    
                  $sheetc3->setCellValue($columnlotup[$ilot].'6',$lot);
                  $newdata =  array (
                    'column' =>  $columnlotup[$ilot], 
                    'lot' =>  $lot,
                   );
                   array_push($columnadd,$newdata);
                   $row[$lot] = $columnlotup[$ilot];
                   $ilot++;
                }
                
                foreach ($datalotsemdw as $lot) {    
                    $sheetc3->setCellValue($columnlotdw[$ilotdw].'6',$lot);
                    $newdata =  array (
                      'column' =>  $columnlotdw[$ilotdw], 
                      'lot' =>  $lot,
                     );
                     array_push($columnadddw,$newdata);
                     $rowdw[$lot] = $columnlotdw[$ilotdw];
                     $ilotdw++;
                  }
                $i = 7;
                $sheetc3->setCellValue('B1',$model1->name_model);
                $sheetc3->setCellValue('P1',$model2->name_model);
                foreach ($datalsemup as $item) {  
                    $stdate = date('Y-m-d', strtotime($item->save_date));
                    $datafirst = DB::table('nt_model_history')
                    ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model_history.id_model', $item->id_model)
                    ->where('nt_model_history.date_start','<=',$stdate)
                    ->where('nt_model_history.date_end','>=',$stdate)
                    ->get();
                    if($datafirst->count() == 0)
                    {
                     $data1 = DB::table('nt_model_history')
                     ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                     ->where('nt_model_history.id_model', $item->id_model)
                     ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                     ->first();
                     
                     $datafirst = DB::table('nt_model_history')
                     ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                     ->where('nt_model_history.id_model', $item->id_model)
                     ->where('nt_model_history.date_start','<=',$data1->date_start)  
                     ->where('nt_model_history.date_end','>=',$data1->date_start)
                     ->get(); 
                    }
                   $sublot = substr($item->lotnumber_lab,1,2);
                   $sheetc3->setCellValue('B'.$i,date('d-M-y',strtotime($item->save_date)));
                   $sheetc3->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->class3,1));
                   $sheetc3->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[0]->class3,1));
                   $sheetc3->setCellValue('K'.$i,slope::nhk_formatnumber($ucl->class3,1));
                   $sheetc3->setCellValue('L'.$i,$item->lotnumber_lab);
                   
                   $sheetc3->setCellValue($row[$sublot].$i,slope::nhk_formatnumber($item->sem_class3,1));
                   $i++;
                }
                $v = 7;
                foreach ($datalsemdw as $item) {   
                    $stdate = date('Y-m-d', strtotime($item->save_date));
                    $datafirst = DB::table('nt_model_history')
                    ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model_history.id_model', $item->id_model)
                    ->where('nt_model_history.date_start','<=',$stdate)
                    ->where('nt_model_history.date_end','>=',$stdate)
                    ->get();     
                    if($datafirst->count() == 0)
                    {
                     $data1 = DB::table('nt_model_history')
                     ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                     ->where('nt_model_history.id_model', $item->id_model)
                     ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                     ->first();
                     
                     $datafirst = DB::table('nt_model_history')
                     ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                     ->where('nt_model_history.id_model', $item->id_model)
                     ->where('nt_model_history.date_start','<=',$data1->date_start)  
                     ->where('nt_model_history.date_end','>=',$data1->date_start)
                     ->get(); 
                    }
                    $sublot = substr($item->lotnumber_lab,1,2);
                    $sheetc3->setCellValue('O'.$v,date('d-M-y',strtotime($item->save_date)));
                    $sheetc3->setCellValue('V'.$v,slope::nhk_formatnumber($datafirst[1]->class3,1));
                    $sheetc3->setCellValue('W'.$v,slope::nhk_formatnumber($datafirst[0]->class3,1));  
                    $sheetc3->setCellValue('X'.$v,slope::nhk_formatnumber($ucl1->class3,1));
                    $sheetc3->setCellValue('Y'.$v,$item->lotnumber_lab);   
                    $sheetc3->setCellValue($rowdw[$sublot].$v,slope::nhk_formatnumber($item->sem_class3,1));
                    $v++;
                 }
                 ///////////////////  END SEM CLASS 3  ////////////////////////////////
    
                ///////////////////  SEM CLASS 3.1  ////////////////////////////////
                $sheetc3_1 = $doc->setActiveSheetIndex(4);
                $ilot = 0;
                $columnadd =[];
                $row = array();
                $ilotdw = 0;
                $columnadddw =[];
                $rowdw = array();
                foreach ($datalotsemup as $lot) {    
                  $sheetc3_1->setCellValue($columnlotup[$ilot].'6',$lot);
                  $newdata =  array (
                    'column' =>  $columnlotup[$ilot], 
                    'lot' =>  $lot,
                   );
                   array_push($columnadd,$newdata);
                   $row[$lot] = $columnlotup[$ilot];
                   $ilot++;
                }
                
                foreach ($datalotsemdw as $lot) {    
                    $sheetc3_1->setCellValue($columnlotdw[$ilotdw].'6',$lot);
                    $newdata =  array (
                      'column' =>  $columnlotdw[$ilotdw], 
                      'lot' =>  $lot,
                     );
                     array_push($columnadddw,$newdata);
                     $rowdw[$lot] = $columnlotdw[$ilotdw];
                     $ilotdw++;
                  }
                $i = 7;
                $sheetc3_1->setCellValue('B1',$model1->name_model);
                $sheetc3_1->setCellValue('P1',$model2->name_model);
                foreach ($datalsemup as $item) {  
                    $stdate = date('Y-m-d', strtotime($item->save_date));
                    $datafirst = DB::table('nt_model_history')
                    ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model_history.id_model', $item->id_model)
                    ->where('nt_model_history.date_start','<=',$stdate)
                    ->where('nt_model_history.date_end','>=',$stdate)
                    ->get();
                    if($datafirst->count() == 0)
                    {
                     $data1 = DB::table('nt_model_history')
                     ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                     ->where('nt_model_history.id_model', $item->id_model)
                     ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                     ->first();
                     
                     $datafirst = DB::table('nt_model_history')
                     ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                     ->where('nt_model_history.id_model', $item->id_model)
                     ->where('nt_model_history.date_start','<=',$data1->date_start)  
                     ->where('nt_model_history.date_end','>=',$data1->date_start)
                     ->get(); 
                    }
                   $sublot = substr($item->lotnumber_lab,1,2);
                   $sheetc3_1->setCellValue('B'.$i,date('d-M-y',strtotime($item->save_date)));
                   $sheetc3_1->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->class3_1,1));
                   $sheetc3_1->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[0]->class3_1,1));
                   $sheetc3_1->setCellValue('K'.$i,slope::nhk_formatnumber($ucl->class3_1,1));
                   $sheetc3_1->setCellValue('L'.$i,$item->lotnumber_lab);
                   
                   $sheetc3_1->setCellValue($row[$sublot].$i,slope::nhk_formatnumber($item->sem_class3_1,1));
                   $i++;
                }
                $v = 7;
                foreach ($datalsemdw as $item) {  
                    $stdate = date('Y-m-d', strtotime($item->save_date));
                    $datafirst = DB::table('nt_model_history')
                    ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model_history.id_model', $item->id_model)
                    ->where('nt_model_history.date_start','<=',$stdate)
                    ->where('nt_model_history.date_end','>=',$stdate)
                    ->get();      
                    if($datafirst->count() == 0)
                    {
                     $data1 = DB::table('nt_model_history')
                     ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                     ->where('nt_model_history.id_model', $item->id_model)
                     ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                     ->first();
                     
                     $datafirst = DB::table('nt_model_history')
                     ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                     ->where('nt_model_history.id_model', $item->id_model)
                     ->where('nt_model_history.date_start','<=',$data1->date_start)  
                     ->where('nt_model_history.date_end','>=',$data1->date_start)
                     ->get(); 
                    }
                    $sublot = substr($item->lotnumber_lab,1,2);
                    $sheetc3_1->setCellValue('O'.$v,date('d-M-y',strtotime($item->save_date)));
                    $sheetc3_1->setCellValue('V'.$v,slope::nhk_formatnumber($datafirst[1]->class3_1,1));
                    $sheetc3_1->setCellValue('W'.$v,slope::nhk_formatnumber($datafirst[0]->class3_1,1));  
                    $sheetc3_1->setCellValue('X'.$v,slope::nhk_formatnumber($ucl1->class3_1,1));
                    $sheetc3_1->setCellValue('Y'.$v,$item->lotnumber_lab);   
                    $sheetc3_1->setCellValue($rowdw[$sublot].$v,slope::nhk_formatnumber($item->sem_class3_1,1));
                    $v++;
                 }
                 ///////////////////  END SEM CLASS 3.1  ////////////////////////////////
               ///////////////////  SEM CLASS 3.2  ////////////////////////////////
               $sheetc3_2 = $doc->setActiveSheetIndex(5);
               $ilot = 0;
               $columnadd =[];
               $row = array();
               $ilotdw = 0;
               $columnadddw =[];
               $rowdw = array();
               foreach ($datalotsemup as $lot) {    
                 $sheetc3_2->setCellValue($columnlotup[$ilot].'6',$lot);
                 $newdata =  array (
                   'column' =>  $columnlotup[$ilot], 
                   'lot' =>  $lot,
                  );
                  array_push($columnadd,$newdata);
                  $row[$lot] = $columnlotup[$ilot];
                  $ilot++;
               }
               
               foreach ($datalotsemdw as $lot) {    
                   $sheetc3_2->setCellValue($columnlotdw[$ilotdw].'6',$lot);
                   $newdata =  array (
                     'column' =>  $columnlotdw[$ilotdw], 
                     'lot' =>  $lot,
                    );
                    array_push($columnadddw,$newdata);
                    $rowdw[$lot] = $columnlotdw[$ilotdw];
                    $ilotdw++;
                 }
               $i = 7;
               $sheetc3_2->setCellValue('B1',$model1->name_model);
               $sheetc3_2->setCellValue('P1',$model2->name_model);
               foreach ($datalsemup as $item) {  
                $stdate = date('Y-m-d', strtotime($item->save_date));
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$stdate)
                ->where('nt_model_history.date_end','>=',$stdate)
                ->get();
                if($datafirst->count() == 0)
                {
                 $data1 = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                 ->first();
                 
                 $datafirst = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','<=',$data1->date_start)  
                 ->where('nt_model_history.date_end','>=',$data1->date_start)
                 ->get(); 
                }
                  $sublot = substr($item->lotnumber_lab,1,2);
                  $sheetc3_2->setCellValue('B'.$i,date('d-M-y',strtotime($item->save_date)));
                  $sheetc3_2->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->class3_2,1));
                  $sheetc3_2->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[0]->class3_2,1));
                  $sheetc3_2->setCellValue('K'.$i,slope::nhk_formatnumber($ucl->class3_2,1));
                  $sheetc3_2->setCellValue('L'.$i,$item->lotnumber_lab);
                  
                  $sheetc3_2->setCellValue($row[$sublot].$i,slope::nhk_formatnumber($item->sem_class3_2,1));
                  $i++;
               }
               $v = 7;
               foreach ($datalsemdw as $item) {  
                $stdate = date('Y-m-d', strtotime($item->save_date));
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$stdate)
                ->where('nt_model_history.date_end','>=',$stdate)
                ->get();    
                if($datafirst->count() == 0)
                {
                 $data1 = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                 ->first();
                 
                 $datafirst = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','<=',$data1->date_start)  
                 ->where('nt_model_history.date_end','>=',$data1->date_start)
                 ->get(); 
                }  
                   $sublot = substr($item->lotnumber_lab,1,2);
                   $sheetc3_2->setCellValue('O'.$v,date('d-M-y',strtotime($item->save_date)));
                   $sheetc3_2->setCellValue('V'.$v,slope::nhk_formatnumber($datafirst[1]->class3_2,1));
                   $sheetc3_2->setCellValue('W'.$v,slope::nhk_formatnumber($datafirst[0]->class3_2,1));  
                   $sheetc3_2->setCellValue('X'.$v,slope::nhk_formatnumber($ucl1->class3_2,1));
                   $sheetc3_2->setCellValue('Y'.$v,$item->lotnumber_lab);   
                   $sheetc3_2->setCellValue($rowdw[$sublot].$v,slope::nhk_formatnumber($item->sem_class3_2,1));
                   $v++;
                }
                ///////////////////  END SEM CLASS 3.2  ////////////////////////////////
    
                   ///////////////////  SEM CLASS 3.3  ////////////////////////////////
               $sheetc3_3 = $doc->setActiveSheetIndex(6);
               $ilot = 0;
               $columnadd =[];
               $row = array();
               $ilotdw = 0;
               $columnadddw =[];
               $rowdw = array();
               foreach ($datalotsemup as $lot) {    
                 $sheetc3_3->setCellValue($columnlotup[$ilot].'6',$lot);
                 $newdata =  array (
                   'column' =>  $columnlotup[$ilot], 
                   'lot' =>  $lot,
                  );
                  array_push($columnadd,$newdata);
                  $row[$lot] = $columnlotup[$ilot];
                  $ilot++;
               }
               
               foreach ($datalotsemdw as $lot) {    
                   $sheetc3_3->setCellValue($columnlotdw[$ilotdw].'6',$lot);
                   $newdata =  array (
                     'column' =>  $columnlotdw[$ilotdw], 
                     'lot' =>  $lot,
                    );
                    array_push($columnadddw,$newdata);
                    $rowdw[$lot] = $columnlotdw[$ilotdw];
                    $ilotdw++;
                 }
               $i = 7;
               $sheetc3_3->setCellValue('B1',$model1->name_model);
               $sheetc3_3->setCellValue('P1',$model2->name_model);
               foreach ($datalsemup as $item) {  
                $stdate = date('Y-m-d', strtotime($item->save_date));
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$stdate)
                ->where('nt_model_history.date_end','>=',$stdate)
                ->get();
                if($datafirst->count() == 0)
                {
                 $data1 = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                 ->first();
                 
                 $datafirst = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','<=',$data1->date_start)  
                 ->where('nt_model_history.date_end','>=',$data1->date_start)
                 ->get(); 
                }
                  $sublot = substr($item->lotnumber_lab,1,2);
                  $sheetc3_3->setCellValue('B'.$i,date('d-M-y',strtotime($item->save_date)));
                  $sheetc3_3->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->class3_3,1));
                  $sheetc3_3->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[0]->class3_3,1));
                  $sheetc3_3->setCellValue('K'.$i,slope::nhk_formatnumber($ucl->class3_3,1));
                  $sheetc3_3->setCellValue('L'.$i,$item->lotnumber_lab);
                  $sheetc3_3->setCellValue($row[$sublot].$i,slope::nhk_formatnumber($item->sem_class3_3,1));
                  $i++;
               }
               $v = 7;
               foreach ($datalsemdw as $item) {        
                $stdate = date('Y-m-d', strtotime($item->save_date));
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$stdate)
                ->where('nt_model_history.date_end','>=',$stdate)
                ->get();
                if($datafirst->count() == 0)
                {
                 $data1 = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                 ->first();
                 
                 $datafirst = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','<=',$data1->date_start)  
                 ->where('nt_model_history.date_end','>=',$data1->date_start)
                 ->get(); 
                }
                   $sublot = substr($item->lotnumber_lab,1,2);
                   $sheetc3_3->setCellValue('O'.$v,date('d-M-y',strtotime($item->save_date)));
                   $sheetc3_3->setCellValue('V'.$v,slope::nhk_formatnumber($datafirst[1]->class3_3,1));
                   $sheetc3_3->setCellValue('W'.$v,slope::nhk_formatnumber($datafirst[0]->class3_3,1));  
                   $sheetc3_3->setCellValue('X'.$v,slope::nhk_formatnumber($ucl1->class3_3,1));
                   $sheetc3_3->setCellValue('Y'.$v,$item->lotnumber_lab);   
                   $sheetc3_3->setCellValue($rowdw[$sublot].$v,slope::nhk_formatnumber($item->sem_class3_3,1));
                   $v++;
                }
                ///////////////////  END SEM CLASS 3.3  ////////////////////////////////
    
                ///////////////////  SEM CLASS 4  ////////////////////////////////
               $sheetc4 = $doc->setActiveSheetIndex(7);
               $ilot = 0;
               $columnadd =[];
               $row = array();
               $ilotdw = 0;
               $columnadddw =[];
               $rowdw = array();
               foreach ($datalotsemup as $lot) {    
                 $sheetc4->setCellValue($columnlotup[$ilot].'6',$lot);
                 $newdata =  array (
                   'column' =>  $columnlotup[$ilot], 
                   'lot' =>  $lot,
                  );
                  array_push($columnadd,$newdata);
                  $row[$lot] = $columnlotup[$ilot];
                  $ilot++;
               }
               
               foreach ($datalotsemdw as $lot) {    
                   $sheetc4->setCellValue($columnlotdw[$ilotdw].'6',$lot);
                   $newdata =  array (
                     'column' =>  $columnlotdw[$ilotdw], 
                     'lot' =>  $lot,
                    );
                    array_push($columnadddw,$newdata);
                    $rowdw[$lot] = $columnlotdw[$ilotdw];
                    $ilotdw++;
                 }
               $i = 7;
               $sheetc4->setCellValue('B1',$model1->name_model);
               $sheetc4->setCellValue('P1',$model2->name_model);
               foreach ($datalsemup as $item) {  
                $stdate = date('Y-m-d', strtotime($item->save_date));
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$stdate)
                ->where('nt_model_history.date_end','>=',$stdate)
                ->get();
                if($datafirst->count() == 0)
                {
                 $data1 = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                 ->first();
                 
                 $datafirst = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','<=',$data1->date_start)  
                 ->where('nt_model_history.date_end','>=',$data1->date_start)
                 ->get(); 
                }
                  $sublot = substr($item->lotnumber_lab,1,2);
                  $sheetc4->setCellValue('B'.$i,date('d-M-y',strtotime($item->save_date)));
                  $sheetc4->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->class4,1));
                  $sheetc4->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[0]->class4,1));
                  $sheetc4->setCellValue('K'.$i,slope::nhk_formatnumber($ucl->class4,1));
                  $sheetc4->setCellValue('L'.$i,$item->lotnumber_lab);
                  $sheetc4->setCellValue($row[$sublot].$i,slope::nhk_formatnumber($item->sem_class4,1));
                  $i++;
               }
               $v = 7;
               foreach ($datalsemdw as $item) {        
                $stdate = date('Y-m-d', strtotime($item->save_date));
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$stdate)
                ->where('nt_model_history.date_end','>=',$stdate)
                ->get();
                if($datafirst->count() == 0)
                {
                 $data1 = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                 ->first();
                 
                 $datafirst = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','<=',$data1->date_start)  
                 ->where('nt_model_history.date_end','>=',$data1->date_start)
                 ->get(); 
                }
                   $sublot = substr($item->lotnumber_lab,1,2);
                   $sheetc4->setCellValue('O'.$v,date('d-M-y',strtotime($item->save_date)));
                   $sheetc4->setCellValue('V'.$v,slope::nhk_formatnumber($datafirst[1]->class4,1));
                   $sheetc4->setCellValue('W'.$v,slope::nhk_formatnumber($datafirst[0]->class4,1));  
                   $sheetc4->setCellValue('X'.$v,slope::nhk_formatnumber($ucl1->class4,1));
                   $sheetc4->setCellValue('Y'.$v,$item->lotnumber_lab);   
                   $sheetc4->setCellValue($rowdw[$sublot].$v,slope::nhk_formatnumber($item->sem_class4,1));
                   $v++;
                }
                ///////////////////  END SEM CLASS 4  ////////////////////////////////
    
                ///////////////////  SEM CLASS 5  ////////////////////////////////
               $sheetc5 = $doc->setActiveSheetIndex(8);
               $ilot = 0;
               $columnadd =[];
               $row = array();
               $ilotdw = 0;
               $columnadddw =[];
               $rowdw = array();
               foreach ($datalotsemup as $lot) {    
                 $sheetc5->setCellValue($columnlotup[$ilot].'6',$lot);
                 $newdata =  array (
                   'column' =>  $columnlotup[$ilot], 
                   'lot' =>  $lot,
                  );
                  array_push($columnadd,$newdata);
                  $row[$lot] = $columnlotup[$ilot];
                  $ilot++;
               }
               
               foreach ($datalotsemdw as $lot) {    
                   $sheetc5->setCellValue($columnlotdw[$ilotdw].'6',$lot);
                   $newdata =  array (
                     'column' =>  $columnlotdw[$ilotdw], 
                     'lot' =>  $lot,
                    );
                    array_push($columnadddw,$newdata);
                    $rowdw[$lot] = $columnlotdw[$ilotdw];
                    $ilotdw++;
                 }
               $i = 7;
               $sheetc5->setCellValue('B1',$model1->name_model);
               $sheetc5->setCellValue('P1',$model2->name_model);
               foreach ($datalsemup as $item) {  
                $stdate = date('Y-m-d', strtotime($item->save_date));
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$stdate)
                ->where('nt_model_history.date_end','>=',$stdate)
                ->get();
                if($datafirst->count() == 0)
                {
                 $data1 = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                 ->first();
                 
                 $datafirst = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','<=',$data1->date_start)  
                 ->where('nt_model_history.date_end','>=',$data1->date_start)
                 ->get(); 
                }
                  $sublot = substr($item->lotnumber_lab,1,2);
                  $sheetc5->setCellValue('B'.$i,date('d-M-y',strtotime($item->save_date)));
                  $sheetc5->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->class5,1));
                  $sheetc5->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[0]->class5,1));
                  $sheetc5->setCellValue('K'.$i,slope::nhk_formatnumber($ucl->class5,1));
                  $sheetc5->setCellValue('L'.$i,$item->lotnumber_lab);
                  $sheetc5->setCellValue($row[$sublot].$i,slope::nhk_formatnumber($item->sem_class5,1));
                  $i++;
               }
               $v = 7;
               foreach ($datalsemdw as $item) { 
                $stdate = date('Y-m-d', strtotime($item->save_date));
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$stdate)
                ->where('nt_model_history.date_end','>=',$stdate)
                ->get();  
                if($datafirst->count() == 0)
                {
                 $data1 = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                 ->first();
                 
                 $datafirst = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','<=',$data1->date_start)  
                 ->where('nt_model_history.date_end','>=',$data1->date_start)
                 ->get(); 
                }     
                   $sublot = substr($item->lotnumber_lab,1,2);
                   $sheetc5->setCellValue('O'.$v,date('d-M-y',strtotime($item->save_date)));
                   $sheetc5->setCellValue('V'.$v,slope::nhk_formatnumber($datafirst[1]->class5,1));
                   $sheetc5->setCellValue('W'.$v,slope::nhk_formatnumber($datafirst[0]->class5,1));  
                   $sheetc5->setCellValue('X'.$v,slope::nhk_formatnumber($ucl1->class5,1));
                   $sheetc5->setCellValue('Y'.$v,$item->lotnumber_lab);   
                   $sheetc5->setCellValue($rowdw[$sublot].$v,slope::nhk_formatnumber($item->sem_class5,1));
                   $v++;
                }
                ///////////////////  END SEM CLASS 5  ////////////////////////////////
    
                
                ///////////////////  SEM CLASS 5.1  ////////////////////////////////
               $sheetc5_1 = $doc->setActiveSheetIndex(9);
               $ilot = 0;
               $columnadd =[];
               $row = array();
               $ilotdw = 0;
               $columnadddw =[];
               $rowdw = array();
               foreach ($datalotsemup as $lot) {    
                 $sheetc5_1->setCellValue($columnlotup[$ilot].'6',$lot);
                 $newdata =  array (
                   'column' =>  $columnlotup[$ilot], 
                   'lot' =>  $lot,
                  );
                  array_push($columnadd,$newdata);
                  $row[$lot] = $columnlotup[$ilot];
                  $ilot++;
               }
               
               foreach ($datalotsemdw as $lot) {    
                   $sheetc5_1->setCellValue($columnlotdw[$ilotdw].'6',$lot);
                   $newdata =  array (
                     'column' =>  $columnlotdw[$ilotdw], 
                     'lot' =>  $lot,
                    );
                    array_push($columnadddw,$newdata);
                    $rowdw[$lot] = $columnlotdw[$ilotdw];
                    $ilotdw++;
                 }
               $i = 7;
               $sheetc5_1->setCellValue('B1',$model1->name_model);
               $sheetc5_1->setCellValue('P1',$model2->name_model);
               foreach ($datalsemup as $item) {  
                $stdate = date('Y-m-d', strtotime($item->save_date));
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$stdate)
                ->where('nt_model_history.date_end','>=',$stdate)
                ->get();
                if($datafirst->count() == 0)
                {
                 $data1 = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                 ->first();
                 
                 $datafirst = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','<=',$data1->date_start)  
                 ->where('nt_model_history.date_end','>=',$data1->date_start)
                 ->get(); 
                }
                  $sublot = substr($item->lotnumber_lab,1,2);
                  $sheetc5_1->setCellValue('B'.$i,date('d-M-y',strtotime($item->save_date)));
                  $sheetc5_1->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->class5_1,1));
                  $sheetc5_1->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[0]->class5_1,1));
                  $sheetc5_1->setCellValue('K'.$i,slope::nhk_formatnumber($ucl->class5_1,1));
                  $sheetc5_1->setCellValue('L'.$i,$item->lotnumber_lab);
                  $sheetc5_1->setCellValue($row[$sublot].$i,slope::nhk_formatnumber($item->sem_class5_1,1));
                  $i++;
               }
               $v = 7;
               foreach ($datalsemdw as $item) {  
                $stdate = date('Y-m-d', strtotime($item->save_date));
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$stdate)
                ->where('nt_model_history.date_end','>=',$stdate)
                ->get();      
                if($datafirst->count() == 0)
                {
                 $data1 = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                 ->first();
                 
                 $datafirst = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','<=',$data1->date_start)  
                 ->where('nt_model_history.date_end','>=',$data1->date_start)
                 ->get(); 
                }
                   $sublot = substr($item->lotnumber_lab,1,2);
                   $sheetc5_1->setCellValue('O'.$v,date('d-M-y',strtotime($item->save_date)));
                   $sheetc5_1->setCellValue('V'.$v,slope::nhk_formatnumber($datafirst[1]->class5_1,1));
                   $sheetc5_1->setCellValue('W'.$v,slope::nhk_formatnumber($datafirst[0]->class5_1,1));  
                   $sheetc5_1->setCellValue('X'.$v,slope::nhk_formatnumber($ucl1->class5_1,1));
                   $sheetc5_1->setCellValue('Y'.$v,$item->lotnumber_lab);   
                   $sheetc5_1->setCellValue($rowdw[$sublot].$v,slope::nhk_formatnumber($item->sem_class5_1,1));
                   $v++;
                }
                ///////////////////  END SEM CLASS 5.1  ////////////////////////////////
    
                   
                ///////////////////  SEM CLASS 6  ////////////////////////////////
               $sheetc6 = $doc->setActiveSheetIndex(10);
               $ilot = 0;
               $columnadd =[];
               $row = array();
               $ilotdw = 0;
               $columnadddw =[];
               $rowdw = array();
               foreach ($datalotsemup as $lot) {    
                 $sheetc6->setCellValue($columnlotup[$ilot].'6',$lot);
                 $newdata =  array (
                   'column' =>  $columnlotup[$ilot], 
                   'lot' =>  $lot,
                  );
                  array_push($columnadd,$newdata);
                  $row[$lot] = $columnlotup[$ilot];
                  $ilot++;
               }
               
               foreach ($datalotsemdw as $lot) {    
                   $sheetc6->setCellValue($columnlotdw[$ilotdw].'6',$lot);
                   $newdata =  array (
                     'column' =>  $columnlotdw[$ilotdw], 
                     'lot' =>  $lot,
                    );
                    array_push($columnadddw,$newdata);
                    $rowdw[$lot] = $columnlotdw[$ilotdw];
                    $ilotdw++;
                 }
               $i = 7;
               $sheetc6->setCellValue('B1',$model1->name_model);
               $sheetc6->setCellValue('P1',$model2->name_model);
               foreach ($datalsemup as $item) {  
                $stdate = date('Y-m-d', strtotime($item->save_date));
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$stdate)
                ->where('nt_model_history.date_end','>=',$stdate)
                ->get();
                if($datafirst->count() == 0)
                {
                 $data1 = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                 ->first();
                 
                 $datafirst = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','<=',$data1->date_start)  
                 ->where('nt_model_history.date_end','>=',$data1->date_start)
                 ->get(); 
                }
                  $sublot = substr($item->lotnumber_lab,1,2);
                  $sheetc6->setCellValue('B'.$i,date('d-M-y',strtotime($item->save_date)));
                  $sheetc6->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->class6,1));
                  $sheetc6->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[0]->class6,1));
                  $sheetc6->setCellValue('K'.$i,slope::nhk_formatnumber($ucl->class6,1));
                  $sheetc6->setCellValue('L'.$i,$item->lotnumber_lab);
                  $sheetc6->setCellValue($row[$sublot].$i,slope::nhk_formatnumber($item->sem_class6,1));
                  $i++;
               }
               $v = 7;
               foreach ($datalsemdw as $item) {        
                $stdate = date('Y-m-d', strtotime($item->save_date));
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$stdate)
                ->where('nt_model_history.date_end','>=',$stdate)
                ->get();
                if($datafirst->count() == 0)
                {
                 $data1 = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                 ->first();
                 
                 $datafirst = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','<=',$data1->date_start)  
                 ->where('nt_model_history.date_end','>=',$data1->date_start)
                 ->get(); 
                }
                   $sublot = substr($item->lotnumber_lab,1,2);
                   $sheetc6->setCellValue('O'.$v,date('d-M-y',strtotime($item->save_date)));
                   $sheetc6->setCellValue('V'.$v,slope::nhk_formatnumber($datafirst[1]->class6,1));
                   $sheetc6->setCellValue('W'.$v,slope::nhk_formatnumber($datafirst[0]->class6,1));  
                   $sheetc6->setCellValue('X'.$v,slope::nhk_formatnumber($ucl1->class6,1));
                   $sheetc6->setCellValue('Y'.$v,$item->lotnumber_lab);   
                   $sheetc6->setCellValue($rowdw[$sublot].$v,slope::nhk_formatnumber($item->sem_class6,1));
                   $v++;
                }
                ///////////////////  END SEM CLASS 6  ////////////////////////////////
    
    
                ///////////////////  ic  anion  ////////////////////////////////
               $sheetanion = $doc->setActiveSheetIndex(11);
               $ilot = 0;
               $columnadd =[];
               $row = array();
               $ilotdw = 0;
               $columnadddw =[];
               $rowdw = array();
               foreach ($dataloticup as $lot) {    
                 $sheetanion->setCellValue($columnlotup[$ilot].'6',$lot);
                 $newdata =  array (
                   'column' =>  $columnlotup[$ilot], 
                   'lot' =>  $lot,
                  );
                  array_push($columnadd,$newdata);
                  $row[$lot] = $columnlotup[$ilot];
                  $ilot++;
               }
               
               foreach ($dataloticdw as $lot) {    
                   $sheetanion->setCellValue($columnlotdw[$ilotdw].'6',$lot);
                   $newdata =  array (
                     'column' =>  $columnlotdw[$ilotdw], 
                     'lot' =>  $lot,
                    );
                    array_push($columnadddw,$newdata);
                    $rowdw[$lot] = $columnlotdw[$ilotdw];
                    $ilotdw++;
                 }
               $i = 7;
               $sheetanion->setCellValue('B1',$model1->name_model);
               $sheetanion->setCellValue('P1',$model2->name_model);
               foreach ($datalicup as $item) {  
                $stdate = date('Y-m-d', strtotime($item->save_date));
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$stdate)
                ->where('nt_model_history.date_end','>=',$stdate)
                ->get();
                if($datafirst->count() == 0)
                {
                 $data1 = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                 ->first();
                 
                 $datafirst = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','<=',$data1->date_start)  
                 ->where('nt_model_history.date_end','>=',$data1->date_start)
                 ->get(); 
                }
                  $sublot = substr($item->lotnumber_lab,1,2);
                  $sheetanion->setCellValue('B'.$i,date('d-M-y',strtotime($item->save_date)));
                  $sheetanion->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->anion,5));
                  $sheetanion->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[0]->anion,5));
                  $sheetanion->setCellValue('K'.$i,slope::nhk_formatnumber($ucl->anion,5));
                  $sheetanion->setCellValue('L'.$i,$item->lotnumber_lab);
                  $sheetanion->setCellValue($row[$sublot].$i,slope::nhk_formatnumber($item->ic_anion_up,5));
                  $i++;
               }
               $v = 7;
               foreach ($datalicdw as $item) { 
                $stdate = date('Y-m-d', strtotime($item->save_date));
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$stdate)
                ->where('nt_model_history.date_end','>=',$stdate)
                ->get();
                if($datafirst->count() == 0)
                {
                 $data1 = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                 ->first();
                 
                 $datafirst = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','<=',$data1->date_start)  
                 ->where('nt_model_history.date_end','>=',$data1->date_start)
                 ->get(); 
                }       
                   $sublot = substr($item->lotnumber_lab,1,2);
                   $sheetanion->setCellValue('O'.$v,date('d-M-y',strtotime($item->save_date)));
                   $sheetanion->setCellValue('V'.$v,slope::nhk_formatnumber($datafirst[1]->anion,5));
                   $sheetanion->setCellValue('W'.$v,slope::nhk_formatnumber($datafirst[0]->anion,5));  
                   $sheetanion->setCellValue('X'.$v,slope::nhk_formatnumber($ucl1->anion,5));
                   $sheetanion->setCellValue('Y'.$v,$item->lotnumber_lab);   
                   $sheetanion->setCellValue($rowdw[$sublot].$v,slope::nhk_formatnumber($item->ic_anion_up,5));
                   $v++;
                }
                ///////////////////  END ic anion  ////////////////////////////////
    
                ///////////////////  ic  sulphate  ////////////////////////////////
               $sheetsulphate = $doc->setActiveSheetIndex(12);
               $ilot = 0;
               $columnadd =[];
               $row = array();
               $ilotdw = 0;
               $columnadddw =[];
               $rowdw = array();
               foreach ($dataloticup as $lot) {    
                 $sheetsulphate->setCellValue($columnlotup[$ilot].'6',$lot);
                 $newdata =  array (
                   'column' =>  $columnlotup[$ilot], 
                   'lot' =>  $lot,
                  );
                  array_push($columnadd,$newdata);
                  $row[$lot] = $columnlotup[$ilot];
                  $ilot++;
               }
               
               foreach ($dataloticdw as $lot) {    
                   $sheetsulphate->setCellValue($columnlotdw[$ilotdw].'6',$lot);
                   $newdata =  array (
                     'column' =>  $columnlotdw[$ilotdw], 
                     'lot' =>  $lot,
                    );
                    array_push($columnadddw,$newdata);
                    $rowdw[$lot] = $columnlotdw[$ilotdw];
                    $ilotdw++;
                 }
               $i = 7;
               $sheetsulphate->setCellValue('B1',$model1->name_model);
               $sheetsulphate->setCellValue('P1',$model2->name_model);
               foreach ($datalicup as $item) {  
                $stdate = date('Y-m-d', strtotime($item->save_date));
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$stdate)
                ->where('nt_model_history.date_end','>=',$stdate)
                ->get();
                if($datafirst->count() == 0)
                {
                 $data1 = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                 ->first();
                 
                 $datafirst = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','<=',$data1->date_start)  
                 ->where('nt_model_history.date_end','>=',$data1->date_start)
                 ->get(); 
                }
                  $sublot = substr($item->lotnumber_lab,1,2);
                  $sheetsulphate->setCellValue('B'.$i,date('d-M-y',strtotime($item->save_date)));
                  $sheetsulphate->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->sulphate,5));
                  $sheetsulphate->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[0]->sulphate,5));
                  $sheetsulphate->setCellValue('K'.$i,slope::nhk_formatnumber($ucl->sulphate,5));
                  $sheetsulphate->setCellValue('L'.$i,$item->lotnumber_lab);
                  $sheetsulphate->setCellValue($row[$sublot].$i,slope::nhk_formatnumber($item->ic_sulphate,5));
                  $i++;
               }
               $v = 7;
               foreach ($datalicdw as $item) {  
                $stdate = date('Y-m-d', strtotime($item->save_date));
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$stdate)
                ->where('nt_model_history.date_end','>=',$stdate)
                ->get();  
                if($datafirst->count() == 0)
                {
                 $data1 = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                 ->first();
                 
                 $datafirst = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','<=',$data1->date_start)  
                 ->where('nt_model_history.date_end','>=',$data1->date_start)
                 ->get(); 
                }    
                   $sublot = substr($item->lotnumber_lab,1,2);
                   $sheetsulphate->setCellValue('O'.$v,date('d-M-y',strtotime($item->save_date)));
                   $sheetsulphate->setCellValue('V'.$v,slope::nhk_formatnumber($datafirst[1]->sulphate,5));
                   $sheetsulphate->setCellValue('W'.$v,slope::nhk_formatnumber($datafirst[0]->sulphate,5));  
                   $sheetsulphate->setCellValue('X'.$v,slope::nhk_formatnumber($ucl1->sulphate,5));
                   $sheetsulphate->setCellValue('Y'.$v,$item->lotnumber_lab);   
                   $sheetsulphate->setCellValue($rowdw[$sublot].$v,slope::nhk_formatnumber($item->ic_sulphate,5));
                   $v++;
                }
                ///////////////////  END ic sulphate  ////////////////////////////////
    
                            ///////////////////  ic  cation  ////////////////////////////////
               $sheetcation = $doc->setActiveSheetIndex(13);
               $ilot = 0;
               $columnadd =[];
               $row = array();
               $ilotdw = 0;
               $columnadddw =[];
               $rowdw = array();
               foreach ($dataloticup as $lot) {    
                 $sheetcation->setCellValue($columnlotup[$ilot].'6',$lot);
                 $newdata =  array (
                   'column' =>  $columnlotup[$ilot], 
                   'lot' =>  $lot,
                  );
                  array_push($columnadd,$newdata);
                  $row[$lot] = $columnlotup[$ilot];
                  $ilot++;
               }
               
               foreach ($dataloticdw as $lot) {    
                   $sheetcation->setCellValue($columnlotdw[$ilotdw].'6',$lot);
                   $newdata =  array (
                     'column' =>  $columnlotdw[$ilotdw], 
                     'lot' =>  $lot,
                    );
                    array_push($columnadddw,$newdata);
                    $rowdw[$lot] = $columnlotdw[$ilotdw];
                    $ilotdw++;
                 }
               $i = 7;
               $sheetcation->setCellValue('B1',$model1->name_model);
               $sheetcation->setCellValue('P1',$model2->name_model);
               foreach ($datalicup as $item) {  
                $stdate = date('Y-m-d', strtotime($item->save_date));
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$stdate)
                ->where('nt_model_history.date_end','>=',$stdate)
                ->get();
                if($datafirst->count() == 0)
                {
                 $data1 = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                 ->first();
                 
                 $datafirst = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','<=',$data1->date_start)  
                 ->where('nt_model_history.date_end','>=',$data1->date_start)
                 ->get(); 
                }
                  $sublot = substr($item->lotnumber_lab,1,2);
                  $sheetcation->setCellValue('B'.$i,date('d-M-y',strtotime($item->save_date)));
                  $sheetcation->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->cation,5));
                  $sheetcation->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[0]->cation,5));
                  $sheetcation->setCellValue('K'.$i,slope::nhk_formatnumber($ucl->cation,5));
                  $sheetcation->setCellValue('L'.$i,$item->lotnumber_lab);
                  $sheetcation->setCellValue($row[$sublot].$i,slope::nhk_formatnumber($item->ic_cation_up,5));
                  $i++;
               }
               $v = 7;
               foreach ($datalicdw as $item) { 
                $stdate = date('Y-m-d', strtotime($item->save_date));
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$stdate)
                ->where('nt_model_history.date_end','>=',$stdate)
                ->get();   
                if($datafirst->count() == 0)
                {
                 $data1 = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                 ->first();
                 
                 $datafirst = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','<=',$data1->date_start)  
                 ->where('nt_model_history.date_end','>=',$data1->date_start)
                 ->get(); 
                }    
                   $sublot = substr($item->lotnumber_lab,1,2);
                   $sheetcation->setCellValue('O'.$v,date('d-M-y',strtotime($item->save_date)));
                   $sheetcation->setCellValue('V'.$v,slope::nhk_formatnumber($datafirst[1]->cation,5));
                   $sheetcation->setCellValue('W'.$v,slope::nhk_formatnumber($datafirst[0]->cation,5));  
                   $sheetcation->setCellValue('X'.$v,slope::nhk_formatnumber($ucl1->cation,5));
                   $sheetcation->setCellValue('Y'.$v,$item->lotnumber_lab);   
                   $sheetcation->setCellValue($rowdw[$sublot].$v,slope::nhk_formatnumber($item->ic_cation_up,5));
                   $v++;
                }
                ///////////////////  END ic cation  ////////////////////////////////
    
                    ///////////////////  gcms1   ////////////////////////////////
               $sheetgcms1 = $doc->setActiveSheetIndex(14);
               $ilot = 0;
               $columnadd =[];
               $row = array();
               $ilotdw = 0;
               $columnadddw =[];
               $rowdw = array();
               foreach ($datalotgcmsup as $lot) {    
                 $sheetgcms1->setCellValue($columnlotup[$ilot].'6',$lot);
                 $newdata =  array (
                   'column' =>  $columnlotup[$ilot], 
                   'lot' =>  $lot,
                  );
                  array_push($columnadd,$newdata);
                  $row[$lot] = $columnlotup[$ilot];
                  $ilot++;
               }
               
               foreach ($datalotgcmsdw as $lot) {    
                   $sheetgcms1->setCellValue($columnlotdw[$ilotdw].'6',$lot);
                   $newdata =  array (
                     'column' =>  $columnlotdw[$ilotdw], 
                     'lot' =>  $lot,
                    );
                    array_push($columnadddw,$newdata);
                    $rowdw[$lot] = $columnlotdw[$ilotdw];
                    $ilotdw++;
                 }
               $i = 7;
               $sheetgcms1->setCellValue('B1',$model1->name_model);
               $sheetgcms1->setCellValue('P1',$model2->name_model);
               foreach ($datalgcmsup as $item) {  
                $stdate = date('Y-m-d', strtotime($item->save_date));
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$stdate)
                ->where('nt_model_history.date_end','>=',$stdate)
                ->get();
                if($datafirst->count() == 0)
                {
                 $data1 = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                 ->first();
                 
                 $datafirst = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','<=',$data1->date_start)  
                 ->where('nt_model_history.date_end','>=',$data1->date_start)
                 ->get(); 
                }
                  $sublot = substr($item->lotnumber_lab,1,2);
                  $sheetgcms1->setCellValue('B'.$i,date('d-M-y',strtotime($item->save_date)));
                  $sheetgcms1->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->outgassing,0));
                  $sheetgcms1->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[0]->outgassing,0));
                  $sheetgcms1->setCellValue('K'.$i,slope::nhk_formatnumber($ucl->outgassing,0));
                  $sheetgcms1->setCellValue('L'.$i,$item->lotnumber_lab);
                  $sheetgcms1->setCellValue($row[$sublot].$i,slope::nhk_formatnumber($item->gcms_outgassing,0));
                  $i++;
               }
               $v = 7;
               foreach ($datalgcmsdw as $item) { 
                $stdate = date('Y-m-d', strtotime($item->save_date));
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$stdate)
                ->where('nt_model_history.date_end','>=',$stdate)
                ->get();
                if($datafirst->count() == 0)
                {
                 $data1 = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                 ->first();
                 
                 $datafirst = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','<=',$data1->date_start)  
                 ->where('nt_model_history.date_end','>=',$data1->date_start)
                 ->get(); 
                }       
                   $sublot = substr($item->lotnumber_lab,1,2);
                   $sheetgcms1->setCellValue('O'.$v,date('d-M-y',strtotime($item->save_date)));
                   $sheetgcms1->setCellValue('V'.$v,slope::nhk_formatnumber($datafirst[1]->outgassing,0));
                   $sheetgcms1->setCellValue('W'.$v,slope::nhk_formatnumber($datafirst[0]->outgassing,0));  
                   $sheetgcms1->setCellValue('X'.$v,slope::nhk_formatnumber($ucl1->outgassing,0));
                   $sheetgcms1->setCellValue('Y'.$v,$item->lotnumber_lab);   
                   $sheetgcms1->setCellValue($rowdw[$sublot].$v,slope::nhk_formatnumber($item->gcms_outgassing,0));
                   $v++;
                }
                ///////////////////  END gcms1  ////////////////////////////////
    
                        ///////////////////  gcms2   ////////////////////////////////
               $sheetgcms2 = $doc->setActiveSheetIndex(15);
               $ilot = 0;
               $columnadd =[];
               $row = array();
               $ilotdw = 0;
               $columnadddw =[];
               $rowdw = array();
               foreach ($datalotgcmsup as $lot) {    
                 $sheetgcms2->setCellValue($columnlotup[$ilot].'6',$lot);
                 $newdata =  array (
                   'column' =>  $columnlotup[$ilot], 
                   'lot' =>  $lot,
                  );
                  array_push($columnadd,$newdata);
                  $row[$lot] = $columnlotup[$ilot];
                  $ilot++;
               }
               
               foreach ($datalotgcmsdw as $lot) {    
                   $sheetgcms2->setCellValue($columnlotdw[$ilotdw].'6',$lot);
                   $newdata =  array (
                     'column' =>  $columnlotdw[$ilotdw], 
                     'lot' =>  $lot,
                    );
                    array_push($columnadddw,$newdata);
                    $rowdw[$lot] = $columnlotdw[$ilotdw];
                    $ilotdw++;
                 }
               $i = 7;
               $sheetgcms2->setCellValue('B1',$model1->name_model);
               $sheetgcms2->setCellValue('P1',$model2->name_model);
               foreach ($datalgcmsup as $item) {  
                $stdate = date('Y-m-d', strtotime($item->save_date));
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$stdate)
                ->where('nt_model_history.date_end','>=',$stdate)
                ->get();
                if($datafirst->count() == 0)
                {
                 $data1 = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                 ->first();
                 
                 $datafirst = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','<=',$data1->date_start)  
                 ->where('nt_model_history.date_end','>=',$data1->date_start)
                 ->get(); 
                }
                  $sublot = substr($item->lotnumber_lab,1,2);
                  $sheetgcms2->setCellValue('B'.$i,date('d-M-y',strtotime($item->save_date)));
                  $sheetgcms2->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->low_MW,0));
                  $sheetgcms2->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[0]->low_MW,0));
                  $sheetgcms2->setCellValue('K'.$i,slope::nhk_formatnumber($ucl->low_MW,0));
                  $sheetgcms2->setCellValue('L'.$i,$item->lotnumber_lab);
                  $sheetgcms2->setCellValue($row[$sublot].$i,slope::nhk_formatnumber($item->gcms_low_mw,0));
                  $i++;
               }
               $v = 7;
               foreach ($datalgcmsdw as $item) {
                $stdate = date('Y-m-d', strtotime($item->save_date));
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$stdate)
                ->where('nt_model_history.date_end','>=',$stdate)
                ->get();     
                if($datafirst->count() == 0)
                {
                 $data1 = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                 ->first();
                 
                 $datafirst = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','<=',$data1->date_start)  
                 ->where('nt_model_history.date_end','>=',$data1->date_start)
                 ->get(); 
                }  
                   $sublot = substr($item->lotnumber_lab,1,2);
                   $sheetgcms2->setCellValue('O'.$v,date('d-M-y',strtotime($item->save_date)));
                   $sheetgcms2->setCellValue('V'.$v,slope::nhk_formatnumber($datafirst[1]->low_MW,0));
                   $sheetgcms2->setCellValue('W'.$v,slope::nhk_formatnumber($datafirst[0]->low_MW,0));  
                   $sheetgcms2->setCellValue('X'.$v,slope::nhk_formatnumber($ucl1->low_MW,0));
                   $sheetgcms2->setCellValue('Y'.$v,$item->lotnumber_lab);   
                   $sheetgcms2->setCellValue($rowdw[$sublot].$v,slope::nhk_formatnumber($item->gcms_low_mw,0));
                   $v++;
                }
                ///////////////////  END gcms2  ////////////////////////////////
    
                
             ///////////////////  gcms3   ////////////////////////////////
               $sheetgcms3 = $doc->setActiveSheetIndex(16);
               $ilot = 0;
               $columnadd =[];
               $row = array();
               $ilotdw = 0;
               $columnadddw =[];
               $rowdw = array();
               foreach ($datalotgcmsup as $lot) {    
                 $sheetgcms3->setCellValue($columnlotup[$ilot].'6',$lot);
                 $newdata =  array (
                   'column' =>  $columnlotup[$ilot], 
                   'lot' =>  $lot,
                  );
                  array_push($columnadd,$newdata);
                  $row[$lot] = $columnlotup[$ilot];
                  $ilot++;
               }
               
               foreach ($datalotgcmsdw as $lot) {    
                   $sheetgcms3->setCellValue($columnlotdw[$ilotdw].'6',$lot);
                   $newdata =  array (
                     'column' =>  $columnlotdw[$ilotdw], 
                     'lot' =>  $lot,
                    );
                    array_push($columnadddw,$newdata);
                    $rowdw[$lot] = $columnlotdw[$ilotdw];
                    $ilotdw++;
                 }
               $i = 7;
               $sheetgcms3->setCellValue('B1',$model1->name_model);
               $sheetgcms3->setCellValue('P1',$model2->name_model);
               foreach ($datalgcmsup as $item) {  
                $stdate = date('Y-m-d', strtotime($item->save_date));
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$stdate)
                ->where('nt_model_history.date_end','>=',$stdate)
                ->get();
                if($datafirst->count() == 0)
                {
                 $data1 = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                 ->first();
                 
                 $datafirst = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','<=',$data1->date_start)  
                 ->where('nt_model_history.date_end','>=',$data1->date_start)
                 ->get(); 
                }
                  $sublot = substr($item->lotnumber_lab,1,2);
                  $sheetgcms3->setCellValue('B'.$i,date('d-M-y',strtotime($item->save_date)));
                  $sheetgcms3->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->high_MW,0));
                  $sheetgcms3->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[0]->high_MW,0));
                  $sheetgcms3->setCellValue('K'.$i,slope::nhk_formatnumber($ucl->high_MW,0));
                  $sheetgcms3->setCellValue('L'.$i,$item->lotnumber_lab);
                  $sheetgcms3->setCellValue($row[$sublot].$i,slope::nhk_formatnumber($item->gcms_high_mw,0));
                  $i++;
               }
               $v = 7;
               foreach ($datalgcmsdw as $item) {    
                $stdate = date('Y-m-d', strtotime($item->save_date));
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$stdate)
                ->where('nt_model_history.date_end','>=',$stdate)
                ->get();   
                if($datafirst->count() == 0)
                {
                 $data1 = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                 ->first();
                 
                 $datafirst = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','<=',$data1->date_start)  
                 ->where('nt_model_history.date_end','>=',$data1->date_start)
                 ->get(); 
                } 
                   $sublot = substr($item->lotnumber_lab,1,2);
                   $sheetgcms3->setCellValue('O'.$v,date('d-M-y',strtotime($item->save_date)));
                   $sheetgcms3->setCellValue('V'.$v,slope::nhk_formatnumber($datafirst[1]->high_MW,0));
                   $sheetgcms3->setCellValue('W'.$v,slope::nhk_formatnumber($datafirst[0]->high_MW,0));  
                   $sheetgcms3->setCellValue('X'.$v,slope::nhk_formatnumber($ucl1->high_MW,0));
                   $sheetgcms3->setCellValue('Y'.$v,$item->lotnumber_lab);   
                   $sheetgcms3->setCellValue($rowdw[$sublot].$v,slope::nhk_formatnumber($item->gcms_high_mw,0));
                   $v++;
                }
                ///////////////////  END gcms3  ////////////////////////////////
    
                            
             ///////////////////  gcms4   ////////////////////////////////
               $sheetgcms4 = $doc->setActiveSheetIndex(17);
               $ilot = 0;
               $columnadd =[];
               $row = array();
               $ilotdw = 0;
               $columnadddw =[];
               $rowdw = array();
               foreach ($datalotgcmsup as $lot) {    
                 $sheetgcms4->setCellValue($columnlotup[$ilot].'6',$lot);
                 $newdata =  array (
                   'column' =>  $columnlotup[$ilot], 
                   'lot' =>  $lot,
                  );
                  array_push($columnadd,$newdata);
                  $row[$lot] = $columnlotup[$ilot];
                  $ilot++;
               }
               
               foreach ($datalotgcmsdw as $lot) {    
                   $sheetgcms4->setCellValue($columnlotdw[$ilotdw].'6',$lot);
                   $newdata =  array (
                     'column' =>  $columnlotdw[$ilotdw], 
                     'lot' =>  $lot,
                    );
                    array_push($columnadddw,$newdata);
                    $rowdw[$lot] = $columnlotdw[$ilotdw];
                    $ilotdw++;
                 }
               $i = 7;
               $sheetgcms4->setCellValue('B1',$model1->name_model);
               $sheetgcms4->setCellValue('P1',$model2->name_model);
               foreach ($datalgcmsup as $item) {  
                $stdate = date('Y-m-d', strtotime($item->save_date));
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$stdate)
                ->where('nt_model_history.date_end','>=',$stdate)
                ->get();
                if($datafirst->count() == 0)
                {
                 $data1 = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                 ->first();
                 
                 $datafirst = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','<=',$data1->date_start)  
                 ->where('nt_model_history.date_end','>=',$data1->date_start)
                 ->get(); 
                }
                  $sublot = substr($item->lotnumber_lab,1,2);
                  $sheetgcms4->setCellValue('B'.$i,date('d-M-y',strtotime($item->save_date)));
                  $sheetgcms4->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->alkylacrylate,0));
                  $sheetgcms4->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[0]->alkylacrylate,0));
                  $sheetgcms4->setCellValue('K'.$i,slope::nhk_formatnumber($ucl->alkylacrylate,0));
                  $sheetgcms4->setCellValue('L'.$i,$item->lotnumber_lab);
                  $sheetgcms4->setCellValue($row[$sublot].$i,slope::nhk_formatnumber($item->gcms_smear,0));
                  $i++;
               }
               $v = 7;
               foreach ($datalgcmsdw as $item) {     
                $stdate = date('Y-m-d', strtotime($item->save_date));
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$stdate)
                ->where('nt_model_history.date_end','>=',$stdate)
                ->get(); 
                if($datafirst->count() == 0)
                {
                 $data1 = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                 ->first();
                 
                 $datafirst = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','<=',$data1->date_start)  
                 ->where('nt_model_history.date_end','>=',$data1->date_start)
                 ->get(); 
                } 
                   $sublot = substr($item->lotnumber_lab,1,2);
                   $sheetgcms4->setCellValue('O'.$v,date('d-M-y',strtotime($item->save_date)));
                   $sheetgcms4->setCellValue('V'.$v,slope::nhk_formatnumber($datafirst[1]->alkylacrylate,0));
                   $sheetgcms4->setCellValue('W'.$v,slope::nhk_formatnumber($datafirst[0]->alkylacrylate,0));  
                   $sheetgcms4->setCellValue('X'.$v,slope::nhk_formatnumber($ucl1->alkylacrylate,0));
                   $sheetgcms4->setCellValue('Y'.$v,$item->lotnumber_lab);   
                   $sheetgcms4->setCellValue($rowdw[$sublot].$v,slope::nhk_formatnumber($item->gcms_smear,0));
                   $v++;
                }
                ///////////////////  END gcms4  ////////////////////////////////
    
                         ///////////////////  FTIR   ////////////////////////////////
               $sheetftir = $doc->setActiveSheetIndex(18);
               $ilot = 0;
               $columnadd =[];
               $row = array();
               $ilotdw = 0;
               $columnadddw =[];
               $rowdw = array();
               foreach ($datalotftirup as $lot) {    
                 $sheetftir->setCellValue($columnlotup[$ilot].'6',$lot);
                 $newdata =  array (
                   'column' =>  $columnlotup[$ilot], 
                   'lot' =>  $lot,
                  );
                  array_push($columnadd,$newdata);
                  $row[$lot] = $columnlotup[$ilot];
                  $ilot++;
               }
               
               foreach ($datalotftirdw as $lot) {    
                   $sheetftir->setCellValue($columnlotdw[$ilotdw].'6',$lot);
                   $newdata =  array (
                     'column' =>  $columnlotdw[$ilotdw], 
                     'lot' =>  $lot,
                    );
                    array_push($columnadddw,$newdata);
                    $rowdw[$lot] = $columnlotdw[$ilotdw];
                    $ilotdw++;
                 }
               $i = 7;
               $sheetftir->setCellValue('B1',$model1->name_model);
               $sheetftir->setCellValue('P1',$model2->name_model);
               foreach ($datalftirup as $item) {  
                $stdate = date('Y-m-d', strtotime($item->save_date));
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$stdate)
                ->where('nt_model_history.date_end','>=',$stdate)
                ->get();
                if($datafirst->count() == 0)
                {
                 $data1 = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                 ->first();
                 
                 $datafirst = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','<=',$data1->date_start)  
                 ->where('nt_model_history.date_end','>=',$data1->date_start)
                 ->get(); 
                }
                  $sublot = substr($item->lotnumber_lab,1,2);
                  $sheetftir->setCellValue('B'.$i,date('d-M-y',strtotime($item->save_date)));
                  $sheetftir->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->ftir,1));
                  $sheetftir->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[0]->ftir,1));
                  $sheetftir->setCellValue('K'.$i,slope::nhk_formatnumber($ucl->ftir,1));
                  $sheetftir->setCellValue('L'.$i,$item->lotnumber_lab);
                  $sheetftir->setCellValue($row[$sublot].$i,slope::nhk_formatnumber($item->ftir_silicone,1));
                  $i++;
               }
               $v = 7;
               foreach ($datalftirdw as $item) { 
                $stdate = date('Y-m-d', strtotime($item->save_date));
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$stdate)
                ->where('nt_model_history.date_end','>=',$stdate)
                ->get();
                if($datafirst->count() == 0)
                {
                 $data1 = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                 ->first();
                 
                 $datafirst = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','<=',$data1->date_start)  
                 ->where('nt_model_history.date_end','>=',$data1->date_start)
                 ->get(); 
                }       
                   $sublot = substr($item->lotnumber_lab,1,2);
                   $sheetftir->setCellValue('O'.$v,date('d-M-y',strtotime($item->save_date)));
                   $sheetftir->setCellValue('V'.$v,slope::nhk_formatnumber($datafirst[1]->ftir,1));
                   $sheetftir->setCellValue('W'.$v,slope::nhk_formatnumber($datafirst[0]->ftir,1));  
                   $sheetftir->setCellValue('X'.$v,slope::nhk_formatnumber($ucl->ftir,1));
                   $sheetftir->setCellValue('Y'.$v,$item->lotnumber_lab);   
                   $sheetftir->setCellValue($rowdw[$sublot].$v,slope::nhk_formatnumber($item->ftir_silicone,1));
                   $v++;
                }
                ///////////////////  END FTIR  ////////////////////////////////
                if(count($datalup) == 0 || count($dataldw) ==0)
                {
                $doc->setActiveSheetIndexByName('LPC');
                $sheetlpc0 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetlpc0);
                }
                if(count($datalsemup) == 0 || count($datalsemdw) ==0)
                {
                    $doc->setActiveSheetIndexByName('C.1');
                    $sheetsem1 = $doc->getActiveSheetIndex();
                    $doc->removeSheetByIndex($sheetsem1);
    
                    $doc->setActiveSheetIndexByName('C.2');
                    $sheetsem2 = $doc->getActiveSheetIndex();
                    $doc->removeSheetByIndex($sheetsem2);
    
                    $doc->setActiveSheetIndexByName('C.3');
                    $sheetsem3 = $doc->getActiveSheetIndex();
                    $doc->removeSheetByIndex($sheetsem3);
    
                    $doc->setActiveSheetIndexByName('C.3.1');
                    $sheetsem4 = $doc->getActiveSheetIndex();
                    $doc->removeSheetByIndex($sheetsem4);
    
                    $doc->setActiveSheetIndexByName('C.3.2');
                    $sheetsem5 = $doc->getActiveSheetIndex();
                    $doc->removeSheetByIndex($sheetsem5);
    
                    $doc->setActiveSheetIndexByName('C.3.3');
                    $sheetsem6 = $doc->getActiveSheetIndex();
                    $doc->removeSheetByIndex($sheetsem6);
    
                    $doc->setActiveSheetIndexByName('C.4');
                    $sheetsem7 = $doc->getActiveSheetIndex();
                    $doc->removeSheetByIndex($sheetsem7);
    
                    $doc->setActiveSheetIndexByName('C.5');
                    $sheetsem8 = $doc->getActiveSheetIndex();
                    $doc->removeSheetByIndex($sheetsem8);
    
                    $doc->setActiveSheetIndexByName('C.5.1');
                    $sheetsem9 = $doc->getActiveSheetIndex();
                    $doc->removeSheetByIndex($sheetsem9);
    
                    $doc->setActiveSheetIndexByName('C.6');
                    $sheetsem10 = $doc->getActiveSheetIndex();
                    $doc->removeSheetByIndex($sheetsem10);
                }
                if(count($datalicup) == 0 || count($datalicup) ==0)
                {
                    $doc->setActiveSheetIndexByName('anion');
                    $sheetic11 = $doc->getActiveSheetIndex();
                    $doc->removeSheetByIndex($sheetic11);
    
                    $doc->setActiveSheetIndexByName('sulphate');
                    $sheetic12 = $doc->getActiveSheetIndex();
                    $doc->removeSheetByIndex($sheetic12);
    
                    $doc->setActiveSheetIndexByName('cation');
                    $sheetic13 = $doc->getActiveSheetIndex();
                    $doc->removeSheetByIndex($sheetic13);
                }
                if(count($datalgcmsup) == 0 || count($datalgcmsup) ==0)
                {
                    $doc->setActiveSheetIndexByName('Outgassing');
                    $sheetgcms14 = $doc->getActiveSheetIndex();
                    $doc->removeSheetByIndex($sheetgcms14);
    
                    $doc->setActiveSheetIndexByName('Total Low MW');
                    $sheetgcms15 = $doc->getActiveSheetIndex();
                    $doc->removeSheetByIndex($sheetgcms15);
    
                    $doc->setActiveSheetIndexByName('Total High MW');
                    $sheetgcms16 = $doc->getActiveSheetIndex();
                    $doc->removeSheetByIndex($sheetgcms16);
    
                    $doc->setActiveSheetIndexByName('Smear forming compounds');
                    $sheetgcms17 = $doc->getActiveSheetIndex();
                    $doc->removeSheetByIndex($sheetgcms17);
                }
                if(count($datalftirup) == 0 || count($datalftirdw) ==0)
                {
                    $doc->setActiveSheetIndexByName('FTIR');
                $sheetftir18 = $doc->getActiveSheetIndex(0);
                $doc->removeSheetByIndex($sheetftir18);
                }
               })->setFilename($namefile)->store('xlsx', storage_path('NHK-File/report/CSV'));
            return response()->json(['success' =>  1,'filename' => $namefile.'.xlsx']);
        }


        public function customer_csvexcel(Request $request)	
        { 
            $dataexport = [];
            $lotuparray= [];
            $lotdwarray= [];
    
            $lotsemuparray= [];
            $lotsemdwarray= [];
            
            $loticuparray= [];
            $loticdwarray= [];
    
            $lotgcmsuparray= [];
            $lotgcmsdwarray= [];
    
            $lotftiruparray= [];
            $lotftirdwarray= [];
            ini_set('memory_limit', '-1');
           if(isset($request->id))
           {
               if(count($request->id) == 2)
                {
                $a1 = DB::table('nt_model')->where('id_model', $request->id[0])->first();
                $a2 = DB::table('nt_model')->where('id_model', $request->id[1])->first();
    
                $text1 = $a1->name_model;
                $text2 = $a2->name_model;
                $chktext1 = mb_substr($text1,0,-3);
                $chktext2 = mb_substr($text2,0,-3);
                    if($chktext1 == $chktext2)
                    {
                        if($a1->part == "UP")
                        {
                            $model1 = $a1;
                        }
                        else
                        {
                            $model1 = $a2;
                        }
                        if($a2->part == "DN")
                        {
                            $model2 = $a2;
                        }
                        else
                        {
                            $model2 = $a1;
                        }
                        $dateend1 = date('Y-m-d 00:00:00', strtotime($request->input('dateend'.$model1->id_model). ' + 1 day'));
                        $dateend2 = date('Y-m-d 00:00:00', strtotime($request->input('dateend'.$model2->id_model). ' + 1 day'));
                        $datalup = DB::table('nt_data_lpc')
                        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_lpc.id_createlab')
                        ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                        ->where('nt_oqa_lab.save_date','>=',$request->input('datestart'.$model1->id_model))
                        ->where('nt_oqa_lab.save_date','<=',$dateend1)
                        ->where('nt_oqa_lab.id_model',$model1->id_model)
                        ->where('nt_oqa_lab.lpc_confirm_st',1)
                        ->orderBy('nt_oqa_lab.save_date', 'asc')->get();
    
                        $dataldw = DB::table('nt_data_lpc')
                        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_lpc.id_createlab')
                        ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                        ->where('nt_oqa_lab.save_date','>=',$request->input('datestart'.$model2->id_model))
                        ->where('nt_oqa_lab.save_date','<=',$dateend2)
                        ->where('nt_oqa_lab.id_model',$model2->id_model)
                        ->where('nt_oqa_lab.lpc_confirm_st',1)
                        ->orderBy('nt_oqa_lab.save_date', 'asc')->get();
                        foreach ($datalup as $v) { 
                            $sublot = substr($v->lotnumber_lab,1,2);   
                            $newdata =  array (
                             'lot' =>  $sublot, 
                            );
                             array_push($lotuparray,$sublot);
                          } 
                         $datalotup = array_unique($lotuparray);
                 
                         foreach ($dataldw as $v) { 
                             $sublot = substr($v->lotnumber_lab,1,2);   
                             $newdata =  array (
                              'lot' =>  $sublot, 
                             );
                              array_push($lotdwarray,$sublot);
                           } 
                          $datalotdw = array_unique($lotdwarray);
    
    
                          $datalsemup = DB::table('nt_data_sem')
                          ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                          ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                          ->where('nt_oqa_lab.save_date','>=',$request->input('datestart'.$model1->id_model))
                          ->where('nt_oqa_lab.save_date','<=',$dateend1)
                          ->where('nt_oqa_lab.id_model',$model1->id_model)
                          ->where('nt_oqa_lab.sem_confirm_st',1)
                          ->orderBy('nt_oqa_lab.save_date', 'asc')->get();
                          $datalsemdw = DB::table('nt_data_sem')
                          ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                          ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                          ->where('nt_oqa_lab.save_date','>=',$request->input('datestart'.$model2->id_model))
                          ->where('nt_oqa_lab.save_date','<=',$dateend2)
                          ->where('nt_oqa_lab.id_model',$model2->id_model)
                          ->where('nt_oqa_lab.sem_confirm_st',1)
                          ->orderBy('nt_oqa_lab.save_date', 'asc')->get();
                          foreach ($datalsemup as $v) { 
                              $sublot = substr($v->lotnumber_lab,1,2);   
                              $newdata =  array (
                               'lot' =>  $sublot, 
                              );
                               array_push($lotsemuparray,$sublot);
                            } 
                           $datalotsemup = array_unique($lotsemuparray);
                   
                           foreach ($datalsemdw as $v) { 
                               $sublot = substr($v->lotnumber_lab,1,2);   
                               $newdata =  array (
                                'lot' =>  $sublot, 
                               );
                                array_push($lotsemdwarray,$sublot);
                             } 
                            $datalotsemdw = array_unique($lotsemdwarray);
    
    
                            $datalicup = DB::table('nt_data_ic')
                            ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ic.id_createlab')
                            ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                            ->where('nt_oqa_lab.save_date','>=',$request->input('datestart'.$model1->id_model))
                            ->where('nt_oqa_lab.save_date','<=',$dateend1)
                            ->where('nt_oqa_lab.id_model',$model1->id_model)
                            ->where('nt_oqa_lab.ic_confirm_st',1)
                            ->orderBy('nt_oqa_lab.save_date', 'asc')->get();
                            $datalicdw = DB::table('nt_data_ic')
                            ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ic.id_createlab')
                            ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                            ->where('nt_oqa_lab.save_date','>=',$request->input('datestart'.$model2->id_model))
                            ->where('nt_oqa_lab.save_date','<=',$dateend2)
                            ->where('nt_oqa_lab.id_model',$model2->id_model)
                            ->where('nt_oqa_lab.ic_confirm_st',1)
                            ->orderBy('nt_oqa_lab.save_date', 'asc')->get();
                            foreach ($datalicup as $v) { 
                                $sublot = substr($v->lotnumber_lab,1,2);   
                                $newdata =  array (
                                 'lot' =>  $sublot, 
                                );
                                 array_push($loticuparray,$sublot);
                              } 
                             $dataloticup = array_unique($loticuparray);
                     
                             foreach ($datalicdw as $v) { 
                                 $sublot = substr($v->lotnumber_lab,1,2);   
                                 $newdata =  array (
                                  'lot' =>  $sublot, 
                                 );
                                  array_push($loticdwarray,$sublot);
                               } 
                              $dataloticdw = array_unique($loticdwarray);
    
                            $datalgcmsup = DB::table('nt_data_gcms')
                            ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
                            ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                            ->where('nt_oqa_lab.save_date','>=',$request->input('datestart'.$model1->id_model))
                            ->where('nt_oqa_lab.save_date','<=',$dateend1)
                            ->where('nt_oqa_lab.id_model',$model1->id_model)
                            ->where('nt_oqa_lab.gcms_confirm_st',1)
                            ->orderBy('nt_oqa_lab.save_date', 'asc')->get();
                            $datalgcmsdw = DB::table('nt_data_gcms')
                            ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
                            ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                            ->where('nt_oqa_lab.save_date','>=',$request->input('datestart'.$model2->id_model))
                            ->where('nt_oqa_lab.save_date','<=',$dateend2)
                            ->where('nt_oqa_lab.id_model',$model2->id_model)
                            ->where('nt_oqa_lab.gcms_confirm_st',1)
                            ->orderBy('nt_oqa_lab.save_date', 'asc')->get();
                            foreach ($datalgcmsup as $v) { 
                                $sublot = substr($v->lotnumber_lab,1,2);   
                                $newdata =  array (
                                 'lot' =>  $sublot, 
                                );
                                 array_push($lotgcmsuparray,$sublot);
                              } 
                             $datalotgcmsup = array_unique($lotgcmsuparray);
                     
                             foreach ($datalgcmsdw as $v) { 
                                 $sublot = substr($v->lotnumber_lab,1,2);   
                                 $newdata =  array (
                                  'lot' =>  $sublot, 
                                 );
                                  array_push($lotgcmsdwarray,$sublot);
                               } 
                              $datalotgcmsdw = array_unique($lotgcmsdwarray);
    
                            $datalftirup = DB::table('nt_data_ftir')
                            ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ftir.id_createlab')
                            ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                            ->where('nt_oqa_lab.save_date','>=',$request->input('datestart'.$model1->id_model))
                            ->where('nt_oqa_lab.save_date','<=',$dateend1)
                            ->where('nt_oqa_lab.id_model',$model1->id_model)
                            ->where('nt_oqa_lab.ftir_confirm_st',1)
                            ->orderBy('nt_oqa_lab.save_date', 'asc')->get();
                            $datalftirdw = DB::table('nt_data_ftir')
                            ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ftir.id_createlab')
                            ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                            ->where('nt_oqa_lab.save_date','>=',$request->input('datestart'.$model2->id_model))
                            ->where('nt_oqa_lab.save_date','<=',$dateend2)
                            ->where('nt_oqa_lab.id_model',$model2->id_model)
                            ->where('nt_oqa_lab.ftir_confirm_st',1)
                            ->orderBy('nt_oqa_lab.save_date', 'asc')->get();
                            foreach ($datalftirup as $v) { 
                                $sublot = substr($v->lotnumber_lab,1,2);   
                                $newdata =  array (
                                 'lot' =>  $sublot, 
                                );
                                 array_push($lotftiruparray,$sublot);
                              } 
                             $datalotftirup = array_unique($lotftiruparray);
                     
                             foreach ($datalftirdw as $v) { 
                                 $sublot = substr($v->lotnumber_lab,1,2);   
                                 $newdata =  array (
                                  'lot' =>  $sublot, 
                                 );
                                  array_push($lotftirdwarray,$sublot);
                               } 
                              $datalotftirdw = array_unique($lotftirdwarray);
                    }
                    else
                    {
                    return response()->json(['success' => 2]);
                    }
                }
                else
                {
                return response()->json(['success' => 3]);
                }
            }
           else
           {
           return response()->json(['success' => 4]);
           }
            foreach ($request->id as $item) {
                $model = DB::table('nt_model')->where('id_model', $item)->first();
                $datestart = $request->input('datestart'.$item);
                $dateend = $request->input('dateend'.$item);
                $lpccount = $request->input('lpccount'.$item);
                $semcount = $request->input('semcount'.$item);
                $iccount = $request->input('iccount'.$item);
                $gcmscount = $request->input('gcmscount'.$item);
                $ftircount = $request->input('ftircount'.$item);
                $newdata =  array (
                    'id' => $item,
                    'datestart' => $datestart,
                    'dateend' => $dateend,
                    'lpccount' => $lpccount,
                    'semcount' => $semcount,
                    'iccount' => $iccount,
                    'gcmscount' => $gcmscount,
                    'ftircount' => $ftircount,
                    'model' => $model,   
                );
                array_push($dataexport,$newdata);
            }
            $spec = DB::table('nt_model_spec')->where('id_model', $model1->id_model)->where('id_model_type', 3)->first();
            $tl = DB::table('nt_model_spec')->where('id_model', $model1->id_model)->where('id_model_type', 2)->first();
            $ucl = DB::table('nt_model_spec')->where('id_model', $model1->id_model)->where('id_model_type', 1)->first();
            $spec1 = DB::table('nt_model_spec')->where('id_model', $model2->id_model)->where('id_model_type', 3)->first();
            $tl1 = DB::table('nt_model_spec')->where('id_model', $model2->id_model)->where('id_model_type', 2)->first();
            $ucl1 = DB::table('nt_model_spec')->where('id_model', $model2->id_model)->where('id_model_type', 1)->first();
            $file = storage_path('NHK-File/template/report/csv_file.xlsx');
            $columnlotup= ['C','D','E','F','G','H'];
            $columnlotdw= ['P','Q','R','S','T','U'];
            $spacename = str_replace(' ', '', $chktext1);
            $namefile = 'CSV_Customer_'.$spacename.'_'.date("YmdHis");
            Excel::load($file, function ($doc)use($model1,$model2,$datalup,$dataldw,$datalsemup,$datalsemdw,$datalicup,$datalicdw,$datalgcmsup,$datalgcmsdw,$datalftirup,$datalftirdw,$spec,$tl,$ucl,$spec1,$tl1,$ucl1,$datalotup,$datalotdw,$datalotsemup,$datalotsemdw,$dataloticup,$dataloticdw,$datalotgcmsup,$datalotgcmsdw,$datalotftirup,$datalotftirdw,$columnlotup,$columnlotdw) {
                /////////////////// LPC ////////////////////////////////
               
    
                $sheet = $doc->setActiveSheetIndex(0);
         
    
                $ilot = 0;
                $columnadd =[];
                $row = array();
                $ilotdw = 0;
                $columnadddw =[];
                $rowdw = array();
                foreach ($datalotup as $lot) {    
                  $sheet->setCellValue($columnlotup[$ilot].'6',$lot);
                  $newdata =  array (
                    'column' =>  $columnlotup[$ilot], 
                    'lot' =>  $lot,
                   );
                   array_push($columnadd,$newdata);
                   $row[$lot] = $columnlotup[$ilot];
                   $ilot++;
                }
                
                foreach ($datalotdw as $lot) {    
                    $sheet->setCellValue($columnlotdw[$ilotdw].'6',$lot);
                    $newdata =  array (
                      'column' =>  $columnlotdw[$ilotdw], 
                      'lot' =>  $lot,
                     );
                     array_push($columnadddw,$newdata);
                     $rowdw[$lot] = $columnlotdw[$ilotdw];
                     $ilotdw++;
                  }
                $i = 7;
                $sheet->setCellValue('B1',$model1->name_model);
                $sheet->setCellValue('P1',$model2->name_model);
                foreach ($datalup as $item) {  
                    $stdate = date('Y-m-d', strtotime($item->save_date));
                    $datafirst = DB::table('nt_model_history')
                    ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model_history.id_model', $item->id_model)
                    ->where('nt_model_history.date_start','<=',$stdate)
                    ->where('nt_model_history.date_end','>=',$stdate)
                    ->get();
                    if($datafirst->count() == 0)
                    {
                     $data1 = DB::table('nt_model_history')
                     ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                     ->where('nt_model_history.id_model', $item->id_model)
                     ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                     ->first();
                     
                     $datafirst = DB::table('nt_model_history')
                     ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                     ->where('nt_model_history.id_model', $item->id_model)
                     ->where('nt_model_history.date_start','<=',$data1->date_start)  
                     ->where('nt_model_history.date_end','>=',$data1->date_start)
                     ->get(); 
                    }
                   $sublot = substr($item->lotnumber_lab,1,2);
                   $sheet->setCellValue('B'.$i,date('d-M-y',strtotime($item->save_date)));
                   $sheet->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->alpc,0));
                   $sheet->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[0]->alpc,0));
                   $sheet->setCellValue('K'.$i,slope::nhk_formatnumber($ucl->alpc,0));
                   $sheet->setCellValue('L'.$i,$item->lotnumber_lab);
                   
                   $sheet->setCellValue($row[$sublot].$i,slope::nhk_formatnumber($item->lpc_data,0));
                   $i++;
                }
                $v = 7;
                foreach ($dataldw as $item) {   
                    $stdate = date('Y-m-d', strtotime($item->save_date));
                    $datafirst = DB::table('nt_model_history')
                    ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model_history.id_model', $item->id_model)
                    ->where('nt_model_history.date_start','<=',$stdate)
                    ->where('nt_model_history.date_end','>=',$stdate)
                    ->get();
                    if($datafirst->count() == 0)
                    {
                     $data1 = DB::table('nt_model_history')
                     ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                     ->where('nt_model_history.id_model', $item->id_model)
                     ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                     ->first();
                     
                     $datafirst = DB::table('nt_model_history')
                     ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                     ->where('nt_model_history.id_model', $item->id_model)
                     ->where('nt_model_history.date_start','<=',$data1->date_start)  
                     ->where('nt_model_history.date_end','>=',$data1->date_start)
                     ->get(); 
                    }     
                    $sublot = substr($item->lotnumber_lab,1,2);
                    $sheet->setCellValue('O'.$v,date('d-M-y',strtotime($item->save_date)));
                    $sheet->setCellValue('V'.$v,slope::nhk_formatnumber($datafirst[1]->alpc,0));
                    $sheet->setCellValue('W'.$v,slope::nhk_formatnumber($datafirst[0]->alpc,0));  
                    $sheet->setCellValue('X'.$v,slope::nhk_formatnumber($ucl1->alpc,0));
                    $sheet->setCellValue('Y'.$v,slope::nhk_formatnumber($item->lotnumber_lab,0));   
                    $sheet->setCellValue($rowdw[$sublot].$v,slope::nhk_formatnumber($item->lpc_data,0));
                    $v++;
                 }
                 /////////////////// END LPC ////////////////////////////////
    
                 ///////////////////  SEM CLASS 1  ////////////////////////////////
                 $sheetc1 = $doc->setActiveSheetIndex(1);
                 $ilot = 0;
                 $columnadd =[];
                 $row = array();
                 $ilotdw = 0;
                 $columnadddw =[];
                 $rowdw = array();
                 foreach ($datalotsemup as $lot) {    
                   $sheetc1->setCellValue($columnlotup[$ilot].'6',$lot);
                   $newdata =  array (
                     'column' =>  $columnlotup[$ilot], 
                     'lot' =>  $lot,
                    );
                    array_push($columnadd,$newdata);
                    $row[$lot] = $columnlotup[$ilot];
                    $ilot++;
                 }
                 
                 foreach ($datalotsemdw as $lot) {    
                     $sheetc1->setCellValue($columnlotdw[$ilotdw].'6',$lot);
                     $newdata =  array (
                       'column' =>  $columnlotdw[$ilotdw], 
                       'lot' =>  $lot,
                      );
                      array_push($columnadddw,$newdata);
                      $rowdw[$lot] = $columnlotdw[$ilotdw];
                      $ilotdw++;
                   }
                 $i = 7;
                 $sheetc1->setCellValue('B1',$model1->name_model);
                 $sheetc1->setCellValue('P1',$model2->name_model);
                 foreach ($datalsemup as $item) {  
                    $stdate = date('Y-m-d', strtotime($item->save_date));
                    $datafirst = DB::table('nt_model_history')
                    ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model_history.id_model', $item->id_model)
                    ->where('nt_model_history.date_start','<=',$stdate)
                    ->where('nt_model_history.date_end','>=',$stdate)
                    ->get();
                    if($datafirst->count() == 0)
                    {
                     $data1 = DB::table('nt_model_history')
                     ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                     ->where('nt_model_history.id_model', $item->id_model)
                     ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                     ->first();
                     
                     $datafirst = DB::table('nt_model_history')
                     ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                     ->where('nt_model_history.id_model', $item->id_model)
                     ->where('nt_model_history.date_start','<=',$data1->date_start)  
                     ->where('nt_model_history.date_end','>=',$data1->date_start)
                     ->get(); 
                    }
                    $sublot = substr($item->lotnumber_lab,1,2);
                    $sheetc1->setCellValue('B'.$i,date('d-M-y',strtotime($item->save_date)));
                    $sheetc1->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->class1,1));
                    $sheetc1->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[0]->class1,1));
                    $sheetc1->setCellValue('K'.$i,slope::nhk_formatnumber($ucl->class1,1));
                    $sheetc1->setCellValue('L'.$i,$item->lotnumber_lab);
                    
                    $sheetc1->setCellValue($row[$sublot].$i,slope::nhk_formatnumber($item->sem_class1,1));
                    $i++;
                 }
                 $v = 7;
                 foreach ($datalsemdw as $item) {        
                    $stdate = date('Y-m-d', strtotime($item->save_date));
                    $datafirst = DB::table('nt_model_history')
                    ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model_history.id_model', $item->id_model)
                    ->where('nt_model_history.date_start','<=',$stdate)
                    ->where('nt_model_history.date_end','>=',$stdate)
                    ->get();
                    if($datafirst->count() == 0)
                    {
                     $data1 = DB::table('nt_model_history')
                     ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                     ->where('nt_model_history.id_model', $item->id_model)
                     ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                     ->first();
                     
                     $datafirst = DB::table('nt_model_history')
                     ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                     ->where('nt_model_history.id_model', $item->id_model)
                     ->where('nt_model_history.date_start','<=',$data1->date_start)  
                     ->where('nt_model_history.date_end','>=',$data1->date_start)
                     ->get(); 
                    }
                    $sublot = substr($item->lotnumber_lab,1,2);
                    $sheetc1->setCellValue('O'.$v,date('d-M-y',strtotime($item->save_date)));
                    $sheetc1->setCellValue('V'.$v,slope::nhk_formatnumber($datafirst[1]->class1,1));
                    $sheetc1->setCellValue('W'.$v,slope::nhk_formatnumber($datafirst[0]->class1,1));  
                    $sheetc1->setCellValue('X'.$v,slope::nhk_formatnumber($ucl1->class1,1));
                    $sheetc1->setCellValue('Y'.$v,$item->lotnumber_lab);   
                    if($item->last_sem_class1 >= 0){
                        $sheetc1->setCellValue($rowdw[$sublot].$v,slope::nhk_formatnumber($item->last_sem_class1,1));
                    }else{
                        $sheetc1->setCellValue($rowdw[$sublot].$v,slope::nhk_formatnumber($item->sem_class1,1));
                    }
                    $v++;
                  }
                  ///////////////////  END SEM CLASS 1  ////////////////////////////////
    
                ///////////////////  SEM CLASS 2  ////////////////////////////////
                $sheetc2 = $doc->setActiveSheetIndex(2);
                 $ilot = 0;
                 $columnadd =[];
                 $row = array();
                 $ilotdw = 0;
                 $columnadddw =[];
                 $rowdw = array();
                 foreach ($datalotsemup as $lot) {    
                   $sheetc2->setCellValue($columnlotup[$ilot].'6',$lot);
                   $newdata =  array (
                     'column' =>  $columnlotup[$ilot], 
                     'lot' =>  $lot,
                    );
                    array_push($columnadd,$newdata);
                    $row[$lot] = $columnlotup[$ilot];
                    $ilot++;
                 }
                 
                 foreach ($datalotsemdw as $lot) {    
                     $sheetc2->setCellValue($columnlotdw[$ilotdw].'6',$lot);
                     $newdata =  array (
                       'column' =>  $columnlotdw[$ilotdw], 
                       'lot' =>  $lot,
                      );
                      array_push($columnadddw,$newdata);
                      $rowdw[$lot] = $columnlotdw[$ilotdw];
                      $ilotdw++;
                   }
                 $i = 7;
                 $sheetc2->setCellValue('B1',$model1->name_model);
                 $sheetc2->setCellValue('P1',$model2->name_model);
                 foreach ($datalsemup as $item) {  
                    $stdate = date('Y-m-d', strtotime($item->save_date));
                    $datafirst = DB::table('nt_model_history')
                    ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model_history.id_model', $item->id_model)
                    ->where('nt_model_history.date_start','<=',$stdate)
                    ->where('nt_model_history.date_end','>=',$stdate)
                    ->get();
                    if($datafirst->count() == 0)
                    {
                     $data1 = DB::table('nt_model_history')
                     ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                     ->where('nt_model_history.id_model', $item->id_model)
                     ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                     ->first();
                     
                     $datafirst = DB::table('nt_model_history')
                     ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                     ->where('nt_model_history.id_model', $item->id_model)
                     ->where('nt_model_history.date_start','<=',$data1->date_start)  
                     ->where('nt_model_history.date_end','>=',$data1->date_start)
                     ->get(); 
                    }
                    $sublot = substr($item->lotnumber_lab,1,2);
                    $sheetc2->setCellValue('B'.$i,date('d-M-y',strtotime($item->save_date)));
                    $sheetc2->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->class2,1));
                    $sheetc2->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[0]->class2,1));
                    $sheetc2->setCellValue('K'.$i,slope::nhk_formatnumber($ucl->class2,1));
                    $sheetc2->setCellValue('L'.$i,$item->lotnumber_lab);
                    if($item->last_sem_class2 >= 0){
                        $sheetc2->setCellValue($row[$sublot].$i,slope::nhk_formatnumber($item->last_sem_class2,1));
                    }else{
                        $sheetc2->setCellValue($row[$sublot].$i,slope::nhk_formatnumber($item->sem_class2,1));
                    }

                    $i++;
                 }
                 $v = 7;
                 foreach ($datalsemdw as $item) {        
                    $stdate = date('Y-m-d', strtotime($item->save_date));
                    $datafirst = DB::table('nt_model_history')
                    ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model_history.id_model', $item->id_model)
                    ->where('nt_model_history.date_start','<=',$stdate)
                    ->where('nt_model_history.date_end','>=',$stdate)
                    ->get();
                    if($datafirst->count() == 0)
                    {
                     $data1 = DB::table('nt_model_history')
                     ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                     ->where('nt_model_history.id_model', $item->id_model)
                     ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                     ->first();
                     
                     $datafirst = DB::table('nt_model_history')
                     ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                     ->where('nt_model_history.id_model', $item->id_model)
                     ->where('nt_model_history.date_start','<=',$data1->date_start)  
                     ->where('nt_model_history.date_end','>=',$data1->date_start)
                     ->get(); 
                    }
                     $sublot = substr($item->lotnumber_lab,1,2);
                     $sheetc2->setCellValue('O'.$v,date('d-M-y',strtotime($item->save_date)));
                     $sheetc2->setCellValue('V'.$v,slope::nhk_formatnumber($datafirst[1]->class2,1));
                     $sheetc2->setCellValue('W'.$v,slope::nhk_formatnumber($datafirst[0]->class2,1));  
                     $sheetc2->setCellValue('X'.$v,slope::nhk_formatnumber($ucl1->class2,1));
                     $sheetc2->setCellValue('Y'.$v,$item->lotnumber_lab);   

                     if($item->last_sem_class2 >= 0){
                        $sheetc2->setCellValue($rowdw[$sublot].$v,slope::nhk_formatnumber($item->last_sem_class2,1));
                     }else{
                        $sheetc2->setCellValue($rowdw[$sublot].$v,slope::nhk_formatnumber($item->sem_class2,1));
                     }

                     $v++;
                  }
                  ///////////////////  END SEM CLASS 2  ////////////////////////////////
    
                      ///////////////////  SEM CLASS 3  ////////////////////////////////
                $sheetc3 = $doc->setActiveSheetIndex(3);
                $ilot = 0;
                $columnadd =[];
                $row = array();
                $ilotdw = 0;
                $columnadddw =[];
                $rowdw = array();
                foreach ($datalotsemup as $lot) {    
                  $sheetc3->setCellValue($columnlotup[$ilot].'6',$lot);
                  $newdata =  array (
                    'column' =>  $columnlotup[$ilot], 
                    'lot' =>  $lot,
                   );
                   array_push($columnadd,$newdata);
                   $row[$lot] = $columnlotup[$ilot];
                   $ilot++;
                }
                
                foreach ($datalotsemdw as $lot) {    
                    $sheetc3->setCellValue($columnlotdw[$ilotdw].'6',$lot);
                    $newdata =  array (
                      'column' =>  $columnlotdw[$ilotdw], 
                      'lot' =>  $lot,
                     );
                     array_push($columnadddw,$newdata);
                     $rowdw[$lot] = $columnlotdw[$ilotdw];
                     $ilotdw++;
                  }
                $i = 7;
                $sheetc3->setCellValue('B1',$model1->name_model);
                $sheetc3->setCellValue('P1',$model2->name_model);
                foreach ($datalsemup as $item) {  
                    $stdate = date('Y-m-d', strtotime($item->save_date));
                    $datafirst = DB::table('nt_model_history')
                    ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model_history.id_model', $item->id_model)
                    ->where('nt_model_history.date_start','<=',$stdate)
                    ->where('nt_model_history.date_end','>=',$stdate)
                    ->get();
                    if($datafirst->count() == 0)
                    {
                     $data1 = DB::table('nt_model_history')
                     ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                     ->where('nt_model_history.id_model', $item->id_model)
                     ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                     ->first();
                     
                     $datafirst = DB::table('nt_model_history')
                     ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                     ->where('nt_model_history.id_model', $item->id_model)
                     ->where('nt_model_history.date_start','<=',$data1->date_start)  
                     ->where('nt_model_history.date_end','>=',$data1->date_start)
                     ->get(); 
                    }
                   $sublot = substr($item->lotnumber_lab,1,2);
                   $sheetc3->setCellValue('B'.$i,date('d-M-y',strtotime($item->save_date)));
                   $sheetc3->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->class3,1));
                   $sheetc3->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[0]->class3,1));
                   $sheetc3->setCellValue('K'.$i,slope::nhk_formatnumber($ucl->class3,1));
                   $sheetc3->setCellValue('L'.$i,$item->lotnumber_lab);
                   if($item->last_sem_class3 >= 0){
                    $sheetc3->setCellValue($row[$sublot].$i,slope::nhk_formatnumber($item->last_sem_class3,1));
                   }else{
                    $sheetc3->setCellValue($row[$sublot].$i,slope::nhk_formatnumber($item->sem_class3,1));
                   }

                   $i++;
                }
                $v = 7;
                foreach ($datalsemdw as $item) {   
                    $stdate = date('Y-m-d', strtotime($item->save_date));
                    $datafirst = DB::table('nt_model_history')
                    ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model_history.id_model', $item->id_model)
                    ->where('nt_model_history.date_start','<=',$stdate)
                    ->where('nt_model_history.date_end','>=',$stdate)
                    ->get(); 
                    if($datafirst->count() == 0)
                    {
                     $data1 = DB::table('nt_model_history')
                     ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                     ->where('nt_model_history.id_model', $item->id_model)
                     ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                     ->first();
                     
                     $datafirst = DB::table('nt_model_history')
                     ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                     ->where('nt_model_history.id_model', $item->id_model)
                     ->where('nt_model_history.date_start','<=',$data1->date_start)  
                     ->where('nt_model_history.date_end','>=',$data1->date_start)
                     ->get(); 
                    }    
                    $sublot = substr($item->lotnumber_lab,1,2);
                    $sheetc3->setCellValue('O'.$v,date('d-M-y',strtotime($item->save_date)));
                    $sheetc3->setCellValue('V'.$v,slope::nhk_formatnumber($datafirst[1]->class3,1));
                    $sheetc3->setCellValue('W'.$v,slope::nhk_formatnumber($datafirst[0]->class3,1));  
                    $sheetc3->setCellValue('X'.$v,slope::nhk_formatnumber($ucl1->class3,1));
                    $sheetc3->setCellValue('Y'.$v,$item->lotnumber_lab);  
                    if($item->last_sem_class3 >= 0){ 
                        $sheetc3->setCellValue($rowdw[$sublot].$v,slope::nhk_formatnumber($item->last_sem_class3,1));
                    }else{
                        $sheetc3->setCellValue($rowdw[$sublot].$v,slope::nhk_formatnumber($item->sem_class3,1));
                    }
                    $v++;
                 }
                 ///////////////////  END SEM CLASS 3  ////////////////////////////////
    
                ///////////////////  SEM CLASS 3.1  ////////////////////////////////
                $sheetc3_1 = $doc->setActiveSheetIndex(4);
                $ilot = 0;
                $columnadd =[];
                $row = array();
                $ilotdw = 0;
                $columnadddw =[];
                $rowdw = array();
                foreach ($datalotsemup as $lot) {    
                  $sheetc3_1->setCellValue($columnlotup[$ilot].'6',$lot);
                  $newdata =  array (
                    'column' =>  $columnlotup[$ilot], 
                    'lot' =>  $lot,
                   );
                   array_push($columnadd,$newdata);
                   $row[$lot] = $columnlotup[$ilot];
                   $ilot++;
                }
                
                foreach ($datalotsemdw as $lot) {    
                    $sheetc3_1->setCellValue($columnlotdw[$ilotdw].'6',$lot);
                    $newdata =  array (
                      'column' =>  $columnlotdw[$ilotdw], 
                      'lot' =>  $lot,
                     );
                     array_push($columnadddw,$newdata);
                     $rowdw[$lot] = $columnlotdw[$ilotdw];
                     $ilotdw++;
                  }
                $i = 7;
                $sheetc3_1->setCellValue('B1',$model1->name_model);
                $sheetc3_1->setCellValue('P1',$model2->name_model);
                foreach ($datalsemup as $item) {  
                    $stdate = date('Y-m-d', strtotime($item->save_date));
                    $datafirst = DB::table('nt_model_history')
                    ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model_history.id_model', $item->id_model)
                    ->where('nt_model_history.date_start','<=',$stdate)
                    ->where('nt_model_history.date_end','>=',$stdate)
                    ->get();
                    if($datafirst->count() == 0)
                    {
                     $data1 = DB::table('nt_model_history')
                     ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                     ->where('nt_model_history.id_model', $item->id_model)
                     ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                     ->first();
                     
                     $datafirst = DB::table('nt_model_history')
                     ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                     ->where('nt_model_history.id_model', $item->id_model)
                     ->where('nt_model_history.date_start','<=',$data1->date_start)  
                     ->where('nt_model_history.date_end','>=',$data1->date_start)
                     ->get(); 
                    }
                   $sublot = substr($item->lotnumber_lab,1,2);
                   $sheetc3_1->setCellValue('B'.$i,date('d-M-y',strtotime($item->save_date)));
                   $sheetc3_1->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->class3_1,1));
                   $sheetc3_1->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[0]->class3_1,1));
                   $sheetc3_1->setCellValue('K'.$i,slope::nhk_formatnumber($ucl->class3_1,1));
                   $sheetc3_1->setCellValue('L'.$i,$item->lotnumber_lab);
                   if($item->last_sem_class3_1 >= 0){
                    $sheetc3_1->setCellValue($row[$sublot].$i,slope::nhk_formatnumber($item->last_sem_class3_1,1));
                   }else {
                    $sheetc3_1->setCellValue($row[$sublot].$i,slope::nhk_formatnumber($item->sem_class3_1,1));
                   }

                   $i++;
                }
                $v = 7;
                foreach ($datalsemdw as $item) {  
                    $stdate = date('Y-m-d', strtotime($item->save_date));
                    $datafirst = DB::table('nt_model_history')
                    ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model_history.id_model', $item->id_model)
                    ->where('nt_model_history.date_start','<=',$stdate)
                    ->where('nt_model_history.date_end','>=',$stdate)
                    ->get(); 
                    if($datafirst->count() == 0)
                    {
                     $data1 = DB::table('nt_model_history')
                     ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                     ->where('nt_model_history.id_model', $item->id_model)
                     ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                     ->first();
                     
                     $datafirst = DB::table('nt_model_history')
                     ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                     ->where('nt_model_history.id_model', $item->id_model)
                     ->where('nt_model_history.date_start','<=',$data1->date_start)  
                     ->where('nt_model_history.date_end','>=',$data1->date_start)
                     ->get(); 
                    }     
                    $sublot = substr($item->lotnumber_lab,1,2);
                    $sheetc3_1->setCellValue('O'.$v,date('d-M-y',strtotime($item->save_date)));
                    $sheetc3_1->setCellValue('V'.$v,slope::nhk_formatnumber($datafirst[1]->class3_1,1));
                    $sheetc3_1->setCellValue('W'.$v,slope::nhk_formatnumber($datafirst[0]->class3_1,1));  
                    $sheetc3_1->setCellValue('X'.$v,slope::nhk_formatnumber($ucl1->class3_1,1));
                    $sheetc3_1->setCellValue('Y'.$v,$item->lotnumber_lab);   
                    if($item->last_sem_class3_1 >= 0){
                        $sheetc3_1->setCellValue($rowdw[$sublot].$v,slope::nhk_formatnumber($item->last_sem_class3_1,1));
                    }else{
                        $sheetc3_1->setCellValue($rowdw[$sublot].$v,slope::nhk_formatnumber($item->sem_class3_1,1));
                    }
                    $v++;
                 }
                 ///////////////////  END SEM CLASS 3.1  ////////////////////////////////
               ///////////////////  SEM CLASS 3.2  ////////////////////////////////
               $sheetc3_2 = $doc->setActiveSheetIndex(5);
               $ilot = 0;
               $columnadd =[];
               $row = array();
               $ilotdw = 0;
               $columnadddw =[];
               $rowdw = array();
               foreach ($datalotsemup as $lot) {    
                 $sheetc3_2->setCellValue($columnlotup[$ilot].'6',$lot);
                 $newdata =  array (
                   'column' =>  $columnlotup[$ilot], 
                   'lot' =>  $lot,
                  );
                  array_push($columnadd,$newdata);
                  $row[$lot] = $columnlotup[$ilot];
                  $ilot++;
               }
               
               foreach ($datalotsemdw as $lot) {    
                   $sheetc3_2->setCellValue($columnlotdw[$ilotdw].'6',$lot);
                   $newdata =  array (
                     'column' =>  $columnlotdw[$ilotdw], 
                     'lot' =>  $lot,
                    );
                    array_push($columnadddw,$newdata);
                    $rowdw[$lot] = $columnlotdw[$ilotdw];
                    $ilotdw++;
                 }
               $i = 7;
               $sheetc3_2->setCellValue('B1',$model1->name_model);
               $sheetc3_2->setCellValue('P1',$model2->name_model);
               foreach ($datalsemup as $item) {  
                $stdate = date('Y-m-d', strtotime($item->save_date));
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$stdate)
                ->where('nt_model_history.date_end','>=',$stdate)
                ->get();
                if($datafirst->count() == 0)
                {
                 $data1 = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                 ->first();
                 
                 $datafirst = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','<=',$data1->date_start)  
                 ->where('nt_model_history.date_end','>=',$data1->date_start)
                 ->get(); 
                }
                  $sublot = substr($item->lotnumber_lab,1,2);
                  $sheetc3_2->setCellValue('B'.$i,date('d-M-y',strtotime($item->save_date)));
                  $sheetc3_2->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->class3_2,1));
                  $sheetc3_2->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[0]->class3_2,1));
                  $sheetc3_2->setCellValue('K'.$i,slope::nhk_formatnumber($ucl->class3_2,1));
                  $sheetc3_2->setCellValue('L'.$i,$item->lotnumber_lab);
                  if($item->last_sem_class3_2 >= 0){
                    $sheetc3_2->setCellValue($row[$sublot].$i,slope::nhk_formatnumber($item->last_sem_class3_2,1));
                  }else{
                    $sheetc3_2->setCellValue($row[$sublot].$i,slope::nhk_formatnumber($item->sem_class3_2,1));
                  }
                  $i++;
               }
               $v = 7;
               foreach ($datalsemdw as $item) {  
                $stdate = date('Y-m-d', strtotime($item->save_date));
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$stdate)
                ->where('nt_model_history.date_end','>=',$stdate)
                ->get();  
                if($datafirst->count() == 0)
                {
                 $data1 = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                 ->first();
                 
                 $datafirst = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','<=',$data1->date_start)  
                 ->where('nt_model_history.date_end','>=',$data1->date_start)
                 ->get(); 
                }    
                   $sublot = substr($item->lotnumber_lab,1,2);
                   $sheetc3_2->setCellValue('O'.$v,date('d-M-y',strtotime($item->save_date)));
                   $sheetc3_2->setCellValue('V'.$v,slope::nhk_formatnumber($datafirst[1]->class3_2,1));
                   $sheetc3_2->setCellValue('W'.$v,slope::nhk_formatnumber($datafirst[0]->class3_2,1));  
                   $sheetc3_2->setCellValue('X'.$v,slope::nhk_formatnumber($ucl1->class3_2,1));
                   $sheetc3_2->setCellValue('Y'.$v,$item->lotnumber_lab);   
                   if($item->last_sem_class3_2 >= 0){
                    $sheetc3_2->setCellValue($rowdw[$sublot].$v,slope::nhk_formatnumber($item->last_sem_class3_2,1));
                   }else{
                    $sheetc3_2->setCellValue($rowdw[$sublot].$v,slope::nhk_formatnumber($item->sem_class3_2,1));
                   }
                   $v++;
                }
                ///////////////////  END SEM CLASS 3.2  ////////////////////////////////
    
                   ///////////////////  SEM CLASS 3.3  ////////////////////////////////
               $sheetc3_3 = $doc->setActiveSheetIndex(6);
               $ilot = 0;
               $columnadd =[];
               $row = array();
               $ilotdw = 0;
               $columnadddw =[];
               $rowdw = array();
               foreach ($datalotsemup as $lot) {    
                 $sheetc3_3->setCellValue($columnlotup[$ilot].'6',$lot);
                 $newdata =  array (
                   'column' =>  $columnlotup[$ilot], 
                   'lot' =>  $lot,
                  );
                  array_push($columnadd,$newdata);
                  $row[$lot] = $columnlotup[$ilot];
                  $ilot++;
               }
               
               foreach ($datalotsemdw as $lot) {    
                   $sheetc3_3->setCellValue($columnlotdw[$ilotdw].'6',$lot);
                   $newdata =  array (
                     'column' =>  $columnlotdw[$ilotdw], 
                     'lot' =>  $lot,
                    );
                    array_push($columnadddw,$newdata);
                    $rowdw[$lot] = $columnlotdw[$ilotdw];
                    $ilotdw++;
                 }
               $i = 7;
               $sheetc3_3->setCellValue('B1',$model1->name_model);
               $sheetc3_3->setCellValue('P1',$model2->name_model);
               foreach ($datalsemup as $item) {  
                $stdate = date('Y-m-d', strtotime($item->save_date));
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$stdate)
                ->where('nt_model_history.date_end','>=',$stdate)
                ->get();
                if($datafirst->count() == 0)
                {
                 $data1 = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                 ->first();
                 
                 $datafirst = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','<=',$data1->date_start)  
                 ->where('nt_model_history.date_end','>=',$data1->date_start)
                 ->get(); 
                }
                  $sublot = substr($item->lotnumber_lab,1,2);
                  $sheetc3_3->setCellValue('B'.$i,date('d-M-y',strtotime($item->save_date)));
                  $sheetc3_3->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->class3_3,1));
                  $sheetc3_3->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[0]->class3_3,1));
                  $sheetc3_3->setCellValue('K'.$i,slope::nhk_formatnumber($ucl->class3_3,1));
                  $sheetc3_3->setCellValue('L'.$i,$item->lotnumber_lab);
                  if($item->last_sem_class3_3 >= 0){
                    $sheetc3_3->setCellValue($row[$sublot].$i,slope::nhk_formatnumber($item->last_sem_class3_3,1));
                  }else{
                    $sheetc3_3->setCellValue($row[$sublot].$i,slope::nhk_formatnumber($item->sem_class3_3,1));
                  }
                  $i++;
               }
               $v = 7;
               foreach ($datalsemdw as $item) {        
                $stdate = date('Y-m-d', strtotime($item->save_date));
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$stdate)
                ->where('nt_model_history.date_end','>=',$stdate)
                ->get();
                if($datafirst->count() == 0)
                {
                 $data1 = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                 ->first();
                 
                 $datafirst = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','<=',$data1->date_start)  
                 ->where('nt_model_history.date_end','>=',$data1->date_start)
                 ->get(); 
                }
                   $sublot = substr($item->lotnumber_lab,1,2);
                   $sheetc3_3->setCellValue('O'.$v,date('d-M-y',strtotime($item->save_date)));
                   $sheetc3_3->setCellValue('V'.$v,slope::nhk_formatnumber($datafirst[1]->class3_3,1));
                   $sheetc3_3->setCellValue('W'.$v,slope::nhk_formatnumber($datafirst[0]->class3_3,1));  
                   $sheetc3_3->setCellValue('X'.$v,slope::nhk_formatnumber($ucl1->class3_3,1));
                   $sheetc3_3->setCellValue('Y'.$v,$item->lotnumber_lab);   
                    if($item->last_sem_class3_3 >= 0){
                        $sheetc3_3->setCellValue($rowdw[$sublot].$v,slope::nhk_formatnumber($item->last_sem_class3_3,1));
                    }else{
                        $sheetc3_3->setCellValue($rowdw[$sublot].$v,slope::nhk_formatnumber($item->sem_class3_3,1));
                    }
                   $v++;
                }
                ///////////////////  END SEM CLASS 3.3  ////////////////////////////////
    
                ///////////////////  SEM CLASS 4  ////////////////////////////////
               $sheetc4 = $doc->setActiveSheetIndex(7);
               $ilot = 0;
               $columnadd =[];
               $row = array();
               $ilotdw = 0;
               $columnadddw =[];
               $rowdw = array();
               foreach ($datalotsemup as $lot) {    
                 $sheetc4->setCellValue($columnlotup[$ilot].'6',$lot);
                 $newdata =  array (
                   'column' =>  $columnlotup[$ilot], 
                   'lot' =>  $lot,
                  );
                  array_push($columnadd,$newdata);
                  $row[$lot] = $columnlotup[$ilot];
                  $ilot++;
               }
               
               foreach ($datalotsemdw as $lot) {    
                   $sheetc4->setCellValue($columnlotdw[$ilotdw].'6',$lot);
                   $newdata =  array (
                     'column' =>  $columnlotdw[$ilotdw], 
                     'lot' =>  $lot,
                    );
                    array_push($columnadddw,$newdata);
                    $rowdw[$lot] = $columnlotdw[$ilotdw];
                    $ilotdw++;
                 }
               $i = 7;
               $sheetc4->setCellValue('B1',$model1->name_model);
               $sheetc4->setCellValue('P1',$model2->name_model);
               foreach ($datalsemup as $item) {  
                $stdate = date('Y-m-d', strtotime($item->save_date));
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$stdate)
                ->where('nt_model_history.date_end','>=',$stdate)
                ->get();
                if($datafirst->count() == 0)
                {
                 $data1 = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                 ->first();
                 
                 $datafirst = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','<=',$data1->date_start)  
                 ->where('nt_model_history.date_end','>=',$data1->date_start)
                 ->get(); 
                }
                  $sublot = substr($item->lotnumber_lab,1,2);
                  $sheetc4->setCellValue('B'.$i,date('d-M-y',strtotime($item->save_date)));
                  $sheetc4->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->class4,1));
                  $sheetc4->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[0]->class4,1));
                  $sheetc4->setCellValue('K'.$i,slope::nhk_formatnumber($ucl->class4,1));
                  $sheetc4->setCellValue('L'.$i,$item->lotnumber_lab);
                  if($item->last_sem_class4 >= 0){
                    $sheetc4->setCellValue($row[$sublot].$i,slope::nhk_formatnumber($item->last_sem_class4,1));
                  }else{
                    $sheetc4->setCellValue($row[$sublot].$i,slope::nhk_formatnumber($item->sem_class4,1));
                  }
                  $i++;
               }
               $v = 7;
               foreach ($datalsemdw as $item) {        
                $stdate = date('Y-m-d', strtotime($item->save_date));
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$stdate)
                ->where('nt_model_history.date_end','>=',$stdate)
                ->get();
                if($datafirst->count() == 0)
                {
                 $data1 = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                 ->first();
                 
                 $datafirst = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','<=',$data1->date_start)  
                 ->where('nt_model_history.date_end','>=',$data1->date_start)
                 ->get(); 
                }
                   $sublot = substr($item->lotnumber_lab,1,2);
                   $sheetc4->setCellValue('O'.$v,date('d-M-y',strtotime($item->save_date)));
                   $sheetc4->setCellValue('V'.$v,slope::nhk_formatnumber($datafirst[1]->class4,1));
                   $sheetc4->setCellValue('W'.$v,slope::nhk_formatnumber($datafirst[0]->class4,1));  
                   $sheetc4->setCellValue('X'.$v,slope::nhk_formatnumber($ucl1->class4,1));
                   $sheetc4->setCellValue('Y'.$v,$item->lotnumber_lab);   
                   if($item->last_sem_class4 >= 0){
                    $sheetc4->setCellValue($rowdw[$sublot].$v,slope::nhk_formatnumber($item->last_sem_class4,1));
                   }else{
                    $sheetc4->setCellValue($rowdw[$sublot].$v,slope::nhk_formatnumber($item->sem_class4,1));
                   }
                   $v++;
                }
                ///////////////////  END SEM CLASS 4  ////////////////////////////////
    
                ///////////////////  SEM CLASS 5  ////////////////////////////////
               $sheetc5 = $doc->setActiveSheetIndex(8);
               $ilot = 0;
               $columnadd =[];
               $row = array();
               $ilotdw = 0;
               $columnadddw =[];
               $rowdw = array();
               foreach ($datalotsemup as $lot) {    
                 $sheetc5->setCellValue($columnlotup[$ilot].'6',$lot);
                 $newdata =  array (
                   'column' =>  $columnlotup[$ilot], 
                   'lot' =>  $lot,
                  );
                  array_push($columnadd,$newdata);
                  $row[$lot] = $columnlotup[$ilot];
                  $ilot++;
               }
               
               foreach ($datalotsemdw as $lot) {    
                   $sheetc5->setCellValue($columnlotdw[$ilotdw].'6',$lot);
                   $newdata =  array (
                     'column' =>  $columnlotdw[$ilotdw], 
                     'lot' =>  $lot,
                    );
                    array_push($columnadddw,$newdata);
                    $rowdw[$lot] = $columnlotdw[$ilotdw];
                    $ilotdw++;
                 }
               $i = 7;
               $sheetc5->setCellValue('B1',$model1->name_model);
               $sheetc5->setCellValue('P1',$model2->name_model);
               foreach ($datalsemup as $item) {  
                $stdate = date('Y-m-d', strtotime($item->save_date));
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$stdate)
                ->where('nt_model_history.date_end','>=',$stdate)
                ->get();
                if($datafirst->count() == 0)
                {
                 $data1 = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                 ->first();
                 
                 $datafirst = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','<=',$data1->date_start)  
                 ->where('nt_model_history.date_end','>=',$data1->date_start)
                 ->get(); 
                }
                  $sublot = substr($item->lotnumber_lab,1,2);
                  $sheetc5->setCellValue('B'.$i,date('d-M-y',strtotime($item->save_date)));
                  $sheetc5->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->class5,1));
                  $sheetc5->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[0]->class5,1));
                  $sheetc5->setCellValue('K'.$i,slope::nhk_formatnumber($ucl->class5,1));
                  $sheetc5->setCellValue('L'.$i,$item->lotnumber_lab);
                if($item->last_sem_class5 >= 0){
                  $sheetc5->setCellValue($row[$sublot].$i,slope::nhk_formatnumber($item->last_sem_class5,1));
                }else{
                  $sheetc5->setCellValue($row[$sublot].$i,slope::nhk_formatnumber($item->sem_class5,1));
                }
                  $i++;
               }
               $v = 7;
               foreach ($datalsemdw as $item) { 
                $stdate = date('Y-m-d', strtotime($item->save_date));
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$stdate)
                ->where('nt_model_history.date_end','>=',$stdate)
                ->get();  
                if($datafirst->count() == 0)
                {
                 $data1 = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                 ->first();
                 
                 $datafirst = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','<=',$data1->date_start)  
                 ->where('nt_model_history.date_end','>=',$data1->date_start)
                 ->get(); 
                }     
                   $sublot = substr($item->lotnumber_lab,1,2);
                   $sheetc5->setCellValue('O'.$v,date('d-M-y',strtotime($item->save_date)));
                   $sheetc5->setCellValue('V'.$v,slope::nhk_formatnumber($datafirst[1]->class5,1));
                   $sheetc5->setCellValue('W'.$v,slope::nhk_formatnumber($datafirst[0]->class5,1));  
                   $sheetc5->setCellValue('X'.$v,slope::nhk_formatnumber($ucl1->class5,1));
                   $sheetc5->setCellValue('Y'.$v,$item->lotnumber_lab);   
                   if($item->last_sem_class5 >= 0){
                    $sheetc5->setCellValue($rowdw[$sublot].$v,slope::nhk_formatnumber($item->last_sem_class5,1));
                   }else{
                    $sheetc5->setCellValue($rowdw[$sublot].$v,slope::nhk_formatnumber($item->sem_class5,1));
                   }
                   $v++;
                }
                ///////////////////  END SEM CLASS 5  ////////////////////////////////
    
                
                ///////////////////  SEM CLASS 5.1  ////////////////////////////////
               $sheetc5_1 = $doc->setActiveSheetIndex(9);
               $ilot = 0;
               $columnadd =[];
               $row = array();
               $ilotdw = 0;
               $columnadddw =[];
               $rowdw = array();
               foreach ($datalotsemup as $lot) {    
                 $sheetc5_1->setCellValue($columnlotup[$ilot].'6',$lot);
                 $newdata =  array (
                   'column' =>  $columnlotup[$ilot], 
                   'lot' =>  $lot,
                  );
                  array_push($columnadd,$newdata);
                  $row[$lot] = $columnlotup[$ilot];
                  $ilot++;
               }
               
               foreach ($datalotsemdw as $lot) {    
                   $sheetc5_1->setCellValue($columnlotdw[$ilotdw].'6',$lot);
                   $newdata =  array (
                     'column' =>  $columnlotdw[$ilotdw], 
                     'lot' =>  $lot,
                    );
                    array_push($columnadddw,$newdata);
                    $rowdw[$lot] = $columnlotdw[$ilotdw];
                    $ilotdw++;
                 }
               $i = 7;
               $sheetc5_1->setCellValue('B1',$model1->name_model);
               $sheetc5_1->setCellValue('P1',$model2->name_model);
               foreach ($datalsemup as $item) {  
                $stdate = date('Y-m-d', strtotime($item->save_date));
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$stdate)
                ->where('nt_model_history.date_end','>=',$stdate)
                ->get();
                if($datafirst->count() == 0)
                {
                 $data1 = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                 ->first();
                 
                 $datafirst = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','<=',$data1->date_start)  
                 ->where('nt_model_history.date_end','>=',$data1->date_start)
                 ->get(); 
                }
                  $sublot = substr($item->lotnumber_lab,1,2);
                  $sheetc5_1->setCellValue('B'.$i,date('d-M-y',strtotime($item->save_date)));
                  $sheetc5_1->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->class5_1,1));
                  $sheetc5_1->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[0]->class5_1,1));
                  $sheetc5_1->setCellValue('K'.$i,slope::nhk_formatnumber($ucl->class5_1,1));
                  $sheetc5_1->setCellValue('L'.$i,$item->lotnumber_lab);
                  if($item->last_sem_class5_1 >= 0){
                    $sheetc5_1->setCellValue($row[$sublot].$i,slope::nhk_formatnumber($item->last_sem_class5_1,1));
                  }else{
                    $sheetc5_1->setCellValue($row[$sublot].$i,slope::nhk_formatnumber($item->sem_class5_1,1));
                  }
                  $i++;
               }
               $v = 7;
               foreach ($datalsemdw as $item) {  
                $stdate = date('Y-m-d', strtotime($item->save_date));
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$stdate)
                ->where('nt_model_history.date_end','>=',$stdate)
                ->get();  
                if($datafirst->count() == 0)
                {
                 $data1 = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                 ->first();
                 
                 $datafirst = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','<=',$data1->date_start)  
                 ->where('nt_model_history.date_end','>=',$data1->date_start)
                 ->get(); 
                }    
                    $sublot = substr($item->lotnumber_lab,1,2);
                    $sheetc5_1->setCellValue('O'.$v,date('d-M-y',strtotime($item->save_date)));
                    $sheetc5_1->setCellValue('V'.$v,slope::nhk_formatnumber($datafirst[1]->class5_1,1));
                    $sheetc5_1->setCellValue('W'.$v,slope::nhk_formatnumber($datafirst[0]->class5_1,1));  
                    $sheetc5_1->setCellValue('X'.$v,slope::nhk_formatnumber($ucl1->class5_1,1));
                    $sheetc5_1->setCellValue('Y'.$v,$item->lotnumber_lab);   
                    if($item->last_sem_class5_1 >= 0){
                        $sheetc5_1->setCellValue($rowdw[$sublot].$v,slope::nhk_formatnumber($item->last_sem_class5_1,1));
                    }else{
                        $sheetc5_1->setCellValue($rowdw[$sublot].$v,slope::nhk_formatnumber($item->sem_class5_1,1));
                    }
                   $v++;
                }
                ///////////////////  END SEM CLASS 5.1  ////////////////////////////////
    
                   
                ///////////////////  SEM CLASS 6  ////////////////////////////////
               $sheetc6 = $doc->setActiveSheetIndex(10);
               $ilot = 0;
               $columnadd =[];
               $row = array();
               $ilotdw = 0;
               $columnadddw =[];
               $rowdw = array();
               foreach ($datalotsemup as $lot) {    
                 $sheetc6->setCellValue($columnlotup[$ilot].'6',$lot);
                 $newdata =  array (
                   'column' =>  $columnlotup[$ilot], 
                   'lot' =>  $lot,
                  );
                  array_push($columnadd,$newdata);
                  $row[$lot] = $columnlotup[$ilot];
                  $ilot++;
               }
               
               foreach ($datalotsemdw as $lot) {    
                   $sheetc6->setCellValue($columnlotdw[$ilotdw].'6',$lot);
                   $newdata =  array (
                     'column' =>  $columnlotdw[$ilotdw], 
                     'lot' =>  $lot,
                    );
                    array_push($columnadddw,$newdata);
                    $rowdw[$lot] = $columnlotdw[$ilotdw];
                    $ilotdw++;
                 }
               $i = 7;
               $sheetc6->setCellValue('B1',$model1->name_model);
               $sheetc6->setCellValue('P1',$model2->name_model);
               foreach ($datalsemup as $item) {  
                $stdate = date('Y-m-d', strtotime($item->save_date));
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$stdate)
                ->where('nt_model_history.date_end','>=',$stdate)
                ->get();
                if($datafirst->count() == 0)
                {
                 $data1 = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                 ->first();
                 
                 $datafirst = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','<=',$data1->date_start)  
                 ->where('nt_model_history.date_end','>=',$data1->date_start)
                 ->get(); 
                }
                  $sublot = substr($item->lotnumber_lab,1,2);
                  $sheetc6->setCellValue('B'.$i,date('d-M-y',strtotime($item->save_date)));
                  $sheetc6->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->class6,1));
                  $sheetc6->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[0]->class6,1));
                  $sheetc6->setCellValue('K'.$i,slope::nhk_formatnumber($ucl->class6,1));
                  $sheetc6->setCellValue('L'.$i,$item->lotnumber_lab);
                  if( $item->last_sem_class6 >= 0){
                    $sheetc6->setCellValue($row[$sublot].$i,slope::nhk_formatnumber($item->last_sem_class6,1));
                  }else{
                    $sheetc6->setCellValue($row[$sublot].$i,slope::nhk_formatnumber($item->sem_class6,1));
                  }
                  $i++;
               }
               $v = 7;
               foreach ($datalsemdw as $item) {        
                $stdate = date('Y-m-d', strtotime($item->save_date));
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$stdate)
                ->where('nt_model_history.date_end','>=',$stdate)
                ->get();
                if($datafirst->count() == 0)
                {
                 $data1 = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                 ->first();
                 
                 $datafirst = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','<=',$data1->date_start)  
                 ->where('nt_model_history.date_end','>=',$data1->date_start)
                 ->get(); 
                }
                   $sublot = substr($item->lotnumber_lab,1,2);
                   $sheetc6->setCellValue('O'.$v,date('d-M-y',strtotime($item->save_date)));
                   $sheetc6->setCellValue('V'.$v,slope::nhk_formatnumber($datafirst[1]->class6,1));
                   $sheetc6->setCellValue('W'.$v,slope::nhk_formatnumber($datafirst[0]->class6,1));  
                   $sheetc6->setCellValue('X'.$v,slope::nhk_formatnumber($ucl1->class6,1));
                   $sheetc6->setCellValue('Y'.$v,$item->lotnumber_lab);   
                   if($item->last_sem_class6 >= 0){
                    $sheetc6->setCellValue($rowdw[$sublot].$v,slope::nhk_formatnumber($item->last_sem_class6,1));
                   }else{
                    $sheetc6->setCellValue($rowdw[$sublot].$v,slope::nhk_formatnumber($item->sem_class6,1));
                   }
                   $v++;
                }
                ///////////////////  END SEM CLASS 6  ////////////////////////////////
    
    
                ///////////////////  ic  anion  ////////////////////////////////
               $sheetanion = $doc->setActiveSheetIndex(11);
               $ilot = 0;
               $columnadd =[];
               $row = array();
               $ilotdw = 0;
               $columnadddw =[];
               $rowdw = array();
               foreach ($dataloticup as $lot) {    
                 $sheetanion->setCellValue($columnlotup[$ilot].'6',$lot);
                 $newdata =  array (
                   'column' =>  $columnlotup[$ilot], 
                   'lot' =>  $lot,
                  );
                  array_push($columnadd,$newdata);
                  $row[$lot] = $columnlotup[$ilot];
                  $ilot++;
               }
               
               foreach ($dataloticdw as $lot) {    
                   $sheetanion->setCellValue($columnlotdw[$ilotdw].'6',$lot);
                   $newdata =  array (
                     'column' =>  $columnlotdw[$ilotdw], 
                     'lot' =>  $lot,
                    );
                    array_push($columnadddw,$newdata);
                    $rowdw[$lot] = $columnlotdw[$ilotdw];
                    $ilotdw++;
                 }
               $i = 7;
               $sheetanion->setCellValue('B1',$model1->name_model);
               $sheetanion->setCellValue('P1',$model2->name_model);
               foreach ($datalicup as $item) {  
                $stdate = date('Y-m-d', strtotime($item->save_date));
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$stdate)
                ->where('nt_model_history.date_end','>=',$stdate)
                ->get();
                if($datafirst->count() == 0)
                {
                 $data1 = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                 ->first();
                 
                 $datafirst = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','<=',$data1->date_start)  
                 ->where('nt_model_history.date_end','>=',$data1->date_start)
                 ->get(); 
                }
                  $sublot = substr($item->lotnumber_lab,1,2);
                  $sheetanion->setCellValue('B'.$i,date('d-M-y',strtotime($item->save_date)));
                  $sheetanion->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->anion,5));
                  $sheetanion->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[0]->anion,5));
                  $sheetanion->setCellValue('K'.$i,slope::nhk_formatnumber($ucl->anion,5));
                  $sheetanion->setCellValue('L'.$i,$item->lotnumber_lab);
                  $sheetanion->setCellValue($row[$sublot].$i,slope::nhk_formatnumber($item->ic_anion_up,5));
                  $i++;
               }
               $v = 7;
               foreach ($datalicdw as $item) { 
                $stdate = date('Y-m-d', strtotime($item->save_date));
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$stdate)
                ->where('nt_model_history.date_end','>=',$stdate)
                ->get();  
                if($datafirst->count() == 0)
                {
                 $data1 = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                 ->first();
                 
                 $datafirst = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','<=',$data1->date_start)  
                 ->where('nt_model_history.date_end','>=',$data1->date_start)
                 ->get(); 
                }     
                   $sublot = substr($item->lotnumber_lab,1,2);
                   $sheetanion->setCellValue('O'.$v,date('d-M-y',strtotime($item->save_date)));
                   $sheetanion->setCellValue('V'.$v,slope::nhk_formatnumber($datafirst[1]->anion,5));
                   $sheetanion->setCellValue('W'.$v,slope::nhk_formatnumber($datafirst[0]->anion,5));  
                   $sheetanion->setCellValue('X'.$v,slope::nhk_formatnumber($ucl1->anion,5));
                   $sheetanion->setCellValue('Y'.$v,$item->lotnumber_lab);   
                   $sheetanion->setCellValue($rowdw[$sublot].$v,slope::nhk_formatnumber($item->ic_anion_up,5));
                   $v++;
                }
                ///////////////////  END ic anion  ////////////////////////////////
    
                ///////////////////  ic  sulphate  ////////////////////////////////
               $sheetsulphate = $doc->setActiveSheetIndex(12);
               $ilot = 0;
               $columnadd =[];
               $row = array();
               $ilotdw = 0;
               $columnadddw =[];
               $rowdw = array();
               foreach ($dataloticup as $lot) {    
                 $sheetsulphate->setCellValue($columnlotup[$ilot].'6',$lot);
                 $newdata =  array (
                   'column' =>  $columnlotup[$ilot], 
                   'lot' =>  $lot,
                  );
                  array_push($columnadd,$newdata);
                  $row[$lot] = $columnlotup[$ilot];
                  $ilot++;
               }
               
               foreach ($dataloticdw as $lot) {    
                   $sheetsulphate->setCellValue($columnlotdw[$ilotdw].'6',$lot);
                   $newdata =  array (
                     'column' =>  $columnlotdw[$ilotdw], 
                     'lot' =>  $lot,
                    );
                    array_push($columnadddw,$newdata);
                    $rowdw[$lot] = $columnlotdw[$ilotdw];
                    $ilotdw++;
                 }
               $i = 7;
               $sheetsulphate->setCellValue('B1',$model1->name_model);
               $sheetsulphate->setCellValue('P1',$model2->name_model);
               foreach ($datalicup as $item) {  
                $stdate = date('Y-m-d', strtotime($item->save_date));
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$stdate)
                ->where('nt_model_history.date_end','>=',$stdate)
                ->get();
                if($datafirst->count() == 0)
                {
                 $data1 = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                 ->first();
                 
                 $datafirst = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','<=',$data1->date_start)  
                 ->where('nt_model_history.date_end','>=',$data1->date_start)
                 ->get(); 
                }
                  $sublot = substr($item->lotnumber_lab,1,2);
                  $sheetsulphate->setCellValue('B'.$i,date('d-M-y',strtotime($item->save_date)));
                  $sheetsulphate->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->sulphate,5));
                  $sheetsulphate->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[0]->sulphate,5));
                  $sheetsulphate->setCellValue('K'.$i,slope::nhk_formatnumber($ucl->sulphate,5));
                  $sheetsulphate->setCellValue('L'.$i,$item->lotnumber_lab);
                  $sheetsulphate->setCellValue($row[$sublot].$i,slope::nhk_formatnumber($item->ic_sulphate,5));
                  $i++;
               }
               $v = 7;
               foreach ($datalicdw as $item) {  
                $stdate = date('Y-m-d', strtotime($item->save_date));
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$stdate)
                ->where('nt_model_history.date_end','>=',$stdate)
                ->get(); 
                if($datafirst->count() == 0)
                {
                 $data1 = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                 ->first();
                 
                 $datafirst = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','<=',$data1->date_start)  
                 ->where('nt_model_history.date_end','>=',$data1->date_start)
                 ->get(); 
                }     
                   $sublot = substr($item->lotnumber_lab,1,2);
                   $sheetsulphate->setCellValue('O'.$v,date('d-M-y',strtotime($item->save_date)));
                   $sheetsulphate->setCellValue('V'.$v,slope::nhk_formatnumber($datafirst[1]->sulphate,5));
                   $sheetsulphate->setCellValue('W'.$v,slope::nhk_formatnumber($datafirst[0]->sulphate,5));  
                   $sheetsulphate->setCellValue('X'.$v,slope::nhk_formatnumber($ucl1->sulphate,5));
                   $sheetsulphate->setCellValue('Y'.$v,$item->lotnumber_lab);   
                   $sheetsulphate->setCellValue($rowdw[$sublot].$v,slope::nhk_formatnumber($item->ic_sulphate,5));
                   $v++;
                }
                ///////////////////  END ic sulphate  ////////////////////////////////
    
                            ///////////////////  ic  cation  ////////////////////////////////
               $sheetcation = $doc->setActiveSheetIndex(13);
               $ilot = 0;
               $columnadd =[];
               $row = array();
               $ilotdw = 0;
               $columnadddw =[];
               $rowdw = array();
               foreach ($dataloticup as $lot) {    
                 $sheetcation->setCellValue($columnlotup[$ilot].'6',$lot);
                 $newdata =  array (
                   'column' =>  $columnlotup[$ilot], 
                   'lot' =>  $lot,
                  );
                  array_push($columnadd,$newdata);
                  $row[$lot] = $columnlotup[$ilot];
                  $ilot++;
               }
               
               foreach ($dataloticdw as $lot) {    
                   $sheetcation->setCellValue($columnlotdw[$ilotdw].'6',$lot);
                   $newdata =  array (
                     'column' =>  $columnlotdw[$ilotdw], 
                     'lot' =>  $lot,
                    );
                    array_push($columnadddw,$newdata);
                    $rowdw[$lot] = $columnlotdw[$ilotdw];
                    $ilotdw++;
                 }
               $i = 7;
               $sheetcation->setCellValue('B1',$model1->name_model);
               $sheetcation->setCellValue('P1',$model2->name_model);
               foreach ($datalicup as $item) {  
                $stdate = date('Y-m-d', strtotime($item->save_date));
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$stdate)
                ->where('nt_model_history.date_end','>=',$stdate)
                ->get();
                if($datafirst->count() == 0)
                {
                 $data1 = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                 ->first();
                 
                 $datafirst = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','<=',$data1->date_start)  
                 ->where('nt_model_history.date_end','>=',$data1->date_start)
                 ->get(); 
                }
                  $sublot = substr($item->lotnumber_lab,1,2);
                  $sheetcation->setCellValue('B'.$i,date('d-M-y',strtotime($item->save_date)));
                  $sheetcation->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->cation,5));
                  $sheetcation->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[0]->cation,5));
                  $sheetcation->setCellValue('K'.$i,slope::nhk_formatnumber($ucl->cation,5));
                  $sheetcation->setCellValue('L'.$i,$item->lotnumber_lab);
                  $sheetcation->setCellValue($row[$sublot].$i,slope::nhk_formatnumber($item->ic_cation_up,5));
                  $i++;
               }
               $v = 7;
               foreach ($datalicdw as $item) { 
                $stdate = date('Y-m-d', strtotime($item->save_date));
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$stdate)
                ->where('nt_model_history.date_end','>=',$stdate)
                ->get(); 
                if($datafirst->count() == 0)
                {
                 $data1 = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                 ->first();
                 
                 $datafirst = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','<=',$data1->date_start)  
                 ->where('nt_model_history.date_end','>=',$data1->date_start)
                 ->get(); 
                }   

                   $sublot = substr($item->lotnumber_lab,1,2);
                   $sheetcation->setCellValue('O'.$v,date('d-M-y',strtotime($item->save_date)));
                   $sheetcation->setCellValue('V'.$v,slope::nhk_formatnumber($datafirst[1]->cation,5));
                   $sheetcation->setCellValue('W'.$v,slope::nhk_formatnumber($datafirst[0]->cation,5));  
                   $sheetcation->setCellValue('X'.$v,slope::nhk_formatnumber($ucl1->cation,5));
                   $sheetcation->setCellValue('Y'.$v,$item->lotnumber_lab);   
                   $sheetcation->setCellValue($rowdw[$sublot].$v,slope::nhk_formatnumber($item->ic_cation_up,5));
                   $v++;
                }
                ///////////////////  END ic cation  ////////////////////////////////
    
                    ///////////////////  gcms1   ////////////////////////////////
               $sheetgcms1 = $doc->setActiveSheetIndex(14);
               $ilot = 0;
               $columnadd =[];
               $row = array();
               $ilotdw = 0;
               $columnadddw =[];
               $rowdw = array();
               foreach ($datalotgcmsup as $lot) {    
                 $sheetgcms1->setCellValue($columnlotup[$ilot].'6',$lot);
                 $newdata =  array (
                   'column' =>  $columnlotup[$ilot], 
                   'lot' =>  $lot,
                  );
                  array_push($columnadd,$newdata);
                  $row[$lot] = $columnlotup[$ilot];
                  $ilot++;
               }
               
               foreach ($datalotgcmsdw as $lot) {    
                   $sheetgcms1->setCellValue($columnlotdw[$ilotdw].'6',$lot);
                   $newdata =  array (
                     'column' =>  $columnlotdw[$ilotdw], 
                     'lot' =>  $lot,
                    );
                    array_push($columnadddw,$newdata);
                    $rowdw[$lot] = $columnlotdw[$ilotdw];
                    $ilotdw++;
                 }
               $i = 7;
               $sheetgcms1->setCellValue('B1',$model1->name_model);
               $sheetgcms1->setCellValue('P1',$model2->name_model);
               foreach ($datalgcmsup as $item) {  
                $stdate = date('Y-m-d', strtotime($item->save_date));
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$stdate)
                ->where('nt_model_history.date_end','>=',$stdate)
                ->get();
                if($datafirst->count() == 0)
                {
                 $data1 = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                 ->first();
                 
                 $datafirst = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','<=',$data1->date_start)  
                 ->where('nt_model_history.date_end','>=',$data1->date_start)
                 ->get(); 
                }
                  $sublot = substr($item->lotnumber_lab,1,2);
                  $sheetgcms1->setCellValue('B'.$i,date('d-M-y',strtotime($item->save_date)));
                  $sheetgcms1->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->outgassing,0));
                  $sheetgcms1->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[0]->outgassing,0));
                  $sheetgcms1->setCellValue('K'.$i,slope::nhk_formatnumber($ucl->outgassing,0));
                  $sheetgcms1->setCellValue('L'.$i,$item->lotnumber_lab);
                  $sheetgcms1->setCellValue($row[$sublot].$i,slope::nhk_formatnumber($item->gcms_outgassing,0));
                  $i++;
               }
               $v = 7;
               foreach ($datalgcmsdw as $item) { 
                $stdate = date('Y-m-d', strtotime($item->save_date));
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$stdate)
                ->where('nt_model_history.date_end','>=',$stdate)
                ->get(); 
                if($datafirst->count() == 0)
                {
                 $data1 = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                 ->first();
                 
                 $datafirst = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','<=',$data1->date_start)  
                 ->where('nt_model_history.date_end','>=',$data1->date_start)
                 ->get(); 
                }      
                   $sublot = substr($item->lotnumber_lab,1,2);
                   $sheetgcms1->setCellValue('O'.$v,date('d-M-y',strtotime($item->save_date)));
                   $sheetgcms1->setCellValue('V'.$v,slope::nhk_formatnumber($datafirst[1]->outgassing,0));
                   $sheetgcms1->setCellValue('W'.$v,slope::nhk_formatnumber($datafirst[0]->outgassing,0));  
                   $sheetgcms1->setCellValue('X'.$v,slope::nhk_formatnumber($ucl1->outgassing,0));
                   $sheetgcms1->setCellValue('Y'.$v,$item->lotnumber_lab);   
                   $sheetgcms1->setCellValue($rowdw[$sublot].$v,slope::nhk_formatnumber($item->gcms_outgassing,0));
                   $v++;
                }
                ///////////////////  END gcms1  ////////////////////////////////
    
                        ///////////////////  gcms2   ////////////////////////////////
               $sheetgcms2 = $doc->setActiveSheetIndex(15);
               $ilot = 0;
               $columnadd =[];
               $row = array();
               $ilotdw = 0;
               $columnadddw =[];
               $rowdw = array();
               foreach ($datalotgcmsup as $lot) {    
                 $sheetgcms2->setCellValue($columnlotup[$ilot].'6',$lot);
                 $newdata =  array (
                   'column' =>  $columnlotup[$ilot], 
                   'lot' =>  $lot,
                  );
                  array_push($columnadd,$newdata);
                  $row[$lot] = $columnlotup[$ilot];
                  $ilot++;
               }
               
               foreach ($datalotgcmsdw as $lot) {    
                   $sheetgcms2->setCellValue($columnlotdw[$ilotdw].'6',$lot);
                   $newdata =  array (
                     'column' =>  $columnlotdw[$ilotdw], 
                     'lot' =>  $lot,
                    );
                    array_push($columnadddw,$newdata);
                    $rowdw[$lot] = $columnlotdw[$ilotdw];
                    $ilotdw++;
                 }
               $i = 7;
               $sheetgcms2->setCellValue('B1',$model1->name_model);
               $sheetgcms2->setCellValue('P1',$model2->name_model);
               foreach ($datalgcmsup as $item) {  
                $stdate = date('Y-m-d', strtotime($item->save_date));
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$stdate)
                ->where('nt_model_history.date_end','>=',$stdate)
                ->get();
                if($datafirst->count() == 0)
                {
                 $data1 = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                 ->first();
                 
                 $datafirst = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','<=',$data1->date_start)  
                 ->where('nt_model_history.date_end','>=',$data1->date_start)
                 ->get(); 
                }
                  $sublot = substr($item->lotnumber_lab,1,2);
                  $sheetgcms2->setCellValue('B'.$i,date('d-M-y',strtotime($item->save_date)));
                  $sheetgcms2->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->low_MW,0));
                  $sheetgcms2->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[0]->low_MW,0));
                  $sheetgcms2->setCellValue('K'.$i,slope::nhk_formatnumber($ucl->low_MW,0));
                  $sheetgcms2->setCellValue('L'.$i,$item->lotnumber_lab);
                  $sheetgcms2->setCellValue($row[$sublot].$i,slope::nhk_formatnumber($item->gcms_low_mw,0));
                  $i++;
               }
               $v = 7;
               foreach ($datalgcmsdw as $item) {
                $stdate = date('Y-m-d', strtotime($item->save_date));
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$stdate)
                ->where('nt_model_history.date_end','>=',$stdate)
                ->get();     
                if($datafirst->count() == 0)
                {
                 $data1 = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                 ->first();
                 
                 $datafirst = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','<=',$data1->date_start)  
                 ->where('nt_model_history.date_end','>=',$data1->date_start)
                 ->get(); 
                }  
                   $sublot = substr($item->lotnumber_lab,1,2);
                   $sheetgcms2->setCellValue('O'.$v,date('d-M-y',strtotime($item->save_date)));
                   $sheetgcms2->setCellValue('V'.$v,slope::nhk_formatnumber($datafirst[1]->low_MW,0));
                   $sheetgcms2->setCellValue('W'.$v,slope::nhk_formatnumber($datafirst[0]->low_MW,0));  
                   $sheetgcms2->setCellValue('X'.$v,slope::nhk_formatnumber($ucl1->low_MW,0));
                   $sheetgcms2->setCellValue('Y'.$v,$item->lotnumber_lab);   
                   $sheetgcms2->setCellValue($rowdw[$sublot].$v,slope::nhk_formatnumber($item->gcms_low_mw,0));
                   $v++;
                }
                ///////////////////  END gcms2  ////////////////////////////////
    
                
             ///////////////////  gcms3   ////////////////////////////////
               $sheetgcms3 = $doc->setActiveSheetIndex(16);
               $ilot = 0;
               $columnadd =[];
               $row = array();
               $ilotdw = 0;
               $columnadddw =[];
               $rowdw = array();
               foreach ($datalotgcmsup as $lot) {    
                 $sheetgcms3->setCellValue($columnlotup[$ilot].'6',$lot);
                 $newdata =  array (
                   'column' =>  $columnlotup[$ilot], 
                   'lot' =>  $lot,
                  );
                  array_push($columnadd,$newdata);
                  $row[$lot] = $columnlotup[$ilot];
                  $ilot++;
               }
               
               foreach ($datalotgcmsdw as $lot) {    
                   $sheetgcms3->setCellValue($columnlotdw[$ilotdw].'6',$lot);
                   $newdata =  array (
                     'column' =>  $columnlotdw[$ilotdw], 
                     'lot' =>  $lot,
                    );
                    array_push($columnadddw,$newdata);
                    $rowdw[$lot] = $columnlotdw[$ilotdw];
                    $ilotdw++;
                 }
               $i = 7;
               $sheetgcms3->setCellValue('B1',$model1->name_model);
               $sheetgcms3->setCellValue('P1',$model2->name_model);
               foreach ($datalgcmsup as $item) {  
                $stdate = date('Y-m-d', strtotime($item->save_date));
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$stdate)
                ->where('nt_model_history.date_end','>=',$stdate)
                ->get();
                if($datafirst->count() == 0)
                {
                 $data1 = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                 ->first();
                 
                 $datafirst = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','<=',$data1->date_start)  
                 ->where('nt_model_history.date_end','>=',$data1->date_start)
                 ->get(); 
                }
                  $sublot = substr($item->lotnumber_lab,1,2);
                  $sheetgcms3->setCellValue('B'.$i,date('d-M-y',strtotime($item->save_date)));
                  $sheetgcms3->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->high_MW,0));
                  $sheetgcms3->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[0]->high_MW,0));
                  $sheetgcms3->setCellValue('K'.$i,slope::nhk_formatnumber($ucl->high_MW,0));
                  $sheetgcms3->setCellValue('L'.$i,$item->lotnumber_lab);
                  $sheetgcms3->setCellValue($row[$sublot].$i,slope::nhk_formatnumber($item->gcms_high_mw,0));
                  $i++;
               }
               $v = 7;
               foreach ($datalgcmsdw as $item) {    
                $stdate = date('Y-m-d', strtotime($item->save_date));
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$stdate)
                ->where('nt_model_history.date_end','>=',$stdate)
                ->get(); 
                if($datafirst->count() == 0)
                {
                 $data1 = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                 ->first();
                 
                 $datafirst = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','<=',$data1->date_start)  
                 ->where('nt_model_history.date_end','>=',$data1->date_start)
                 ->get(); 
                }   
                   $sublot = substr($item->lotnumber_lab,1,2);
                   $sheetgcms3->setCellValue('O'.$v,date('d-M-y',strtotime($item->save_date)));
                   $sheetgcms3->setCellValue('V'.$v,slope::nhk_formatnumber($datafirst[1]->high_MW,0));
                   $sheetgcms3->setCellValue('W'.$v,slope::nhk_formatnumber($datafirst[0]->high_MW,0));  
                   $sheetgcms3->setCellValue('X'.$v,slope::nhk_formatnumber($ucl1->high_MW,0));
                   $sheetgcms3->setCellValue('Y'.$v,$item->lotnumber_lab);   
                   $sheetgcms3->setCellValue($rowdw[$sublot].$v,slope::nhk_formatnumber($item->gcms_high_mw,0));
                   $v++;
                }
                ///////////////////  END gcms3  ////////////////////////////////
    
                            
             ///////////////////  gcms4   ////////////////////////////////
               $sheetgcms4 = $doc->setActiveSheetIndex(17);
               $ilot = 0;
               $columnadd =[];
               $row = array();
               $ilotdw = 0;
               $columnadddw =[];
               $rowdw = array();
               foreach ($datalotgcmsup as $lot) {    
                 $sheetgcms4->setCellValue($columnlotup[$ilot].'6',$lot);
                 $newdata =  array (
                   'column' =>  $columnlotup[$ilot], 
                   'lot' =>  $lot,
                  );
                  array_push($columnadd,$newdata);
                  $row[$lot] = $columnlotup[$ilot];
                  $ilot++;
               }
               foreach ($datalotgcmsdw as $lot) {    
                   $sheetgcms4->setCellValue($columnlotdw[$ilotdw].'6',$lot);
                   $newdata =  array (
                     'column' =>  $columnlotdw[$ilotdw], 
                     'lot' =>  $lot,
                    );
                    array_push($columnadddw,$newdata);
                    $rowdw[$lot] = $columnlotdw[$ilotdw];
                    $ilotdw++;
                 }
               $i = 7;
               $sheetgcms4->setCellValue('B1',$model1->name_model);
               $sheetgcms4->setCellValue('P1',$model2->name_model);
               foreach ($datalgcmsup as $item) {  
                $stdate = date('Y-m-d', strtotime($item->save_date));
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$stdate)
                ->where('nt_model_history.date_end','>=',$stdate)
                ->get();
                if($datafirst->count() == 0)
                {
                 $data1 = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                 ->first();
                 
                 $datafirst = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','<=',$data1->date_start)  
                 ->where('nt_model_history.date_end','>=',$data1->date_start)
                 ->get(); 
                }
                  $sublot = substr($item->lotnumber_lab,1,2);
                  $sheetgcms4->setCellValue('B'.$i,date('d-M-y',strtotime($item->save_date)));
                  $sheetgcms4->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->alkylacrylate,0));
                  $sheetgcms4->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[0]->alkylacrylate,0));
                  $sheetgcms4->setCellValue('K'.$i,slope::nhk_formatnumber($ucl->alkylacrylate,0));
                  $sheetgcms4->setCellValue('L'.$i,$item->lotnumber_lab);
                  $sheetgcms4->setCellValue($row[$sublot].$i,slope::nhk_formatnumber($item->gcms_smear,0));
                  $i++;
               }
               $v = 7;
               foreach ($datalgcmsdw as $item) {     
                $stdate = date('Y-m-d', strtotime($item->save_date));
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$stdate)
                ->where('nt_model_history.date_end','>=',$stdate)
                ->get();   
                if($datafirst->count() == 0)
                {
                 $data1 = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                 ->first();
                 
                 $datafirst = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','<=',$data1->date_start)  
                 ->where('nt_model_history.date_end','>=',$data1->date_start)
                 ->get(); 
                }
                   $sublot = substr($item->lotnumber_lab,1,2);
                   $sheetgcms4->setCellValue('O'.$v,date('d-M-y',strtotime($item->save_date)));
                   $sheetgcms4->setCellValue('V'.$v,slope::nhk_formatnumber($datafirst[1]->alkylacrylate,0));
                   $sheetgcms4->setCellValue('W'.$v,slope::nhk_formatnumber($datafirst[0]->alkylacrylate,0));  
                   $sheetgcms4->setCellValue('X'.$v,slope::nhk_formatnumber($ucl1->alkylacrylate,0));
                   $sheetgcms4->setCellValue('Y'.$v,$item->lotnumber_lab);   
                   $sheetgcms4->setCellValue($rowdw[$sublot].$v,slope::nhk_formatnumber($item->gcms_smear,0));
                   $v++;
                }
                ///////////////////  END gcms4  ////////////////////////////////

                ///////////////////  FTIR   ////////////////////////////////
               $sheetftir = $doc->setActiveSheetIndex(18);
               $ilot = 0;
               $columnadd =[];
               $row = array();
               $ilotdw = 0;
               $columnadddw =[];
               $rowdw = array();
               foreach ($datalotftirup as $lot) {    
                 $sheetftir->setCellValue($columnlotup[$ilot].'6',$lot);
                 $newdata =  array (
                   'column' =>  $columnlotup[$ilot], 
                   'lot' =>  $lot,
                  );
                  array_push($columnadd,$newdata);
                  $row[$lot] = $columnlotup[$ilot];
                  $ilot++;
               }
               
               foreach ($datalotftirdw as $lot) {    
                   $sheetftir->setCellValue($columnlotdw[$ilotdw].'6',$lot);
                   $newdata =  array (
                     'column' =>  $columnlotdw[$ilotdw], 
                     'lot' =>  $lot,
                    );
                    array_push($columnadddw,$newdata);
                    $rowdw[$lot] = $columnlotdw[$ilotdw];
                    $ilotdw++;
                 }
               $i = 7;
               $sheetftir->setCellValue('B1',$model1->name_model);
               $sheetftir->setCellValue('P1',$model2->name_model);
               foreach ($datalftirup as $item) {  
                $stdate = date('Y-m-d', strtotime($item->save_date));
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$stdate)
                ->where('nt_model_history.date_end','>=',$stdate)
                ->get();
                if($datafirst->count() == 0)
                {
                 $data1 = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                 ->first();
                 
                 $datafirst = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','<=',$data1->date_start)  
                 ->where('nt_model_history.date_end','>=',$data1->date_start)
                 ->get(); 
                }
                  $sublot = substr($item->lotnumber_lab,1,2);
                  $sheetftir->setCellValue('B'.$i,date('d-M-y',strtotime($item->save_date)));
                  $sheetftir->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->ftir,1));
                  $sheetftir->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[0]->ftir,1));
                  $sheetftir->setCellValue('K'.$i,slope::nhk_formatnumber($ucl->ftir,1));
                  $sheetftir->setCellValue('L'.$i,$item->lotnumber_lab);
                  $sheetftir->setCellValue($row[$sublot].$i,slope::nhk_formatnumber($item->ftir_silicone,1));
                  $i++;
               }
               $v = 7;
               foreach ($datalftirdw as $item) { 
                $stdate = date('Y-m-d', strtotime($item->save_date));
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$stdate)
                ->where('nt_model_history.date_end','>=',$stdate)
                ->get();
                if($datafirst->count() == 0)
                {
                 $data1 = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                 ->first();
                 
                 $datafirst = DB::table('nt_model_history')
                 ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                 ->where('nt_model_history.id_model', $item->id_model)
                 ->where('nt_model_history.date_start','<=',$data1->date_start)  
                 ->where('nt_model_history.date_end','>=',$data1->date_start)
                 ->get(); 
                }      
                   $sublot = substr($item->lotnumber_lab,1,2);
                   $sheetftir->setCellValue('O'.$v,date('d-M-y',strtotime($item->save_date)));
                   $sheetftir->setCellValue('V'.$v,slope::nhk_formatnumber($datafirst[1]->ftir,1));
                   $sheetftir->setCellValue('W'.$v,slope::nhk_formatnumber($datafirst[0]->ftir,1));  
                   $sheetftir->setCellValue('X'.$v,slope::nhk_formatnumber($ucl->ftir,1));
                   $sheetftir->setCellValue('Y'.$v,$item->lotnumber_lab);   
                   $sheetftir->setCellValue($rowdw[$sublot].$v,slope::nhk_formatnumber($item->ftir_silicone,1));
                   $v++;
                }
                ///////////////////  END FTIR  ////////////////////////////////
                if(count($datalup) == 0 || count($dataldw) ==0)
                {
                $doc->setActiveSheetIndexByName('LPC');
                $sheetlpc0 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetlpc0);
                }
                if(count($datalsemup) == 0 || count($datalsemdw) ==0)
                {
                    $doc->setActiveSheetIndexByName('C.1');
                    $sheetsem1 = $doc->getActiveSheetIndex();
                    $doc->removeSheetByIndex($sheetsem1);
    
                    $doc->setActiveSheetIndexByName('C.2');
                    $sheetsem2 = $doc->getActiveSheetIndex();
                    $doc->removeSheetByIndex($sheetsem2);
    
                    $doc->setActiveSheetIndexByName('C.3');
                    $sheetsem3 = $doc->getActiveSheetIndex();
                    $doc->removeSheetByIndex($sheetsem3);
    
                    $doc->setActiveSheetIndexByName('C.3.1');
                    $sheetsem4 = $doc->getActiveSheetIndex();
                    $doc->removeSheetByIndex($sheetsem4);
    
                    $doc->setActiveSheetIndexByName('C.3.2');
                    $sheetsem5 = $doc->getActiveSheetIndex();
                    $doc->removeSheetByIndex($sheetsem5);
    
                    $doc->setActiveSheetIndexByName('C.3.3');
                    $sheetsem6 = $doc->getActiveSheetIndex();
                    $doc->removeSheetByIndex($sheetsem6);
    
                    $doc->setActiveSheetIndexByName('C.4');
                    $sheetsem7 = $doc->getActiveSheetIndex();
                    $doc->removeSheetByIndex($sheetsem7);
    
                    $doc->setActiveSheetIndexByName('C.5');
                    $sheetsem8 = $doc->getActiveSheetIndex();
                    $doc->removeSheetByIndex($sheetsem8);
    
                    $doc->setActiveSheetIndexByName('C.5.1');
                    $sheetsem9 = $doc->getActiveSheetIndex();
                    $doc->removeSheetByIndex($sheetsem9);
    
                    $doc->setActiveSheetIndexByName('C.6');
                    $sheetsem10 = $doc->getActiveSheetIndex();
                    $doc->removeSheetByIndex($sheetsem10);
                }
                if(count($datalicup) == 0 || count($datalicup) ==0)
                {
                    $doc->setActiveSheetIndexByName('anion');
                    $sheetic11 = $doc->getActiveSheetIndex();
                    $doc->removeSheetByIndex($sheetic11);
    
                    $doc->setActiveSheetIndexByName('sulphate');
                    $sheetic12 = $doc->getActiveSheetIndex();
                    $doc->removeSheetByIndex($sheetic12);
    
                    $doc->setActiveSheetIndexByName('cation');
                    $sheetic13 = $doc->getActiveSheetIndex();
                    $doc->removeSheetByIndex($sheetic13);
                }
                if(count($datalgcmsup) == 0 || count($datalgcmsup) ==0)
                {
                    $doc->setActiveSheetIndexByName('Outgassing');
                    $sheetgcms14 = $doc->getActiveSheetIndex();
                    $doc->removeSheetByIndex($sheetgcms14);
    
                    $doc->setActiveSheetIndexByName('Total Low MW');
                    $sheetgcms15 = $doc->getActiveSheetIndex();
                    $doc->removeSheetByIndex($sheetgcms15);
    
                    $doc->setActiveSheetIndexByName('Total High MW');
                    $sheetgcms16 = $doc->getActiveSheetIndex();
                    $doc->removeSheetByIndex($sheetgcms16);
    
                    $doc->setActiveSheetIndexByName('Smear forming compounds');
                    $sheetgcms17 = $doc->getActiveSheetIndex();
                    $doc->removeSheetByIndex($sheetgcms17);
                }
                if(count($datalftirup) == 0 || count($datalftirdw) ==0)
                {
                    $doc->setActiveSheetIndexByName('FTIR');
                $sheetftir18 = $doc->getActiveSheetIndex(0);
                $doc->removeSheetByIndex($sheetftir18);
                }
               })->setFilename($namefile)->store('xlsx', storage_path('NHK-File/report/CSV'));
            return response()->json(['success' =>  1,'filename' => $namefile.'.xlsx']);
        }
    


    public function raw_exportpdf(Request $request)	
    {  
        $row = array();
        $dataexport = [];
        $data = DB::table('nt_oqa_lab')
        ->join('nt_model','nt_model.id_model','=','nt_oqa_lab.id_model')
        ->where('nt_model.id_model', $request->id[0])->first();
        foreach ($request->id as $item) {        
            $datestart = $request->input('datestart'.$item);
            $dateend = $request->input('dateend'.$item);
            $lpccount = $request->input('lpccount'.$item);
            $semcount = $request->input('semcount'.$item);
            $iccount = $request->input('iccount'.$item);
            $gcmscount = $request->input('gcmscount'.$item);
            $ftircount = $request->input('ftircount'.$item);
            $newdata =  array (
                'id' => $item,
                'datestart' => $datestart,
                'dateend' => $dateend,
                'lpccount' => $lpccount,
                'semcount' => $semcount,
                'iccount' => $iccount,
                'gcmscount' => $gcmscount,
                'ftircount' => $ftircount,
            );
            array_push($dataexport,$newdata);
        }
        $date_expire  = $request->input('textdate');    
        $datedm = date("d-m", strtotime($date_expire));
        $datey =  date("Y", strtotime($date_expire));
        $dateexport = $datedm.' ,'.$datey; 
        if(empty($request->input('spec')))
        {
           $spec = 0;
        }
        else
        {
            $spec = 1;
        }
        if(empty($request->input('tl')))
        {
            $tl = 0;
        }
        else
        {
            $tl = 1;
        }

        if(empty($request->input('ucl')))
        {
            $ucl = 0;
        }
        else
        {
            $ucl = 1;
        }
        if(empty($request->input('lcl')))
        {
            $lcl = 0;
        }
        else
        {
            $lcl = 1;
        }
        if(empty($request->input('cl')))
        {
            $cl = 0;
        }
        else
        {
            $cl = 1;
        }
        if(empty($request->input('is')))
        {
            $is = 0;
        }
        else
        {
            $is = 1;
        }
        
        return view('output/raw_exportpdf', [
            'id' =>$data->id_model,
            'idcreatelab' =>$data->id_createlab,
            'namemodel' =>$data->name_model,             
            'datenow' =>date("YmdHis"),
            'laball' =>$data,
            'textdate' =>$dateexport,
            'textname' =>$request->input('textname'),
            'textnamepproval' =>$request->input('textnamepproval'),
            'arrayexport' =>$dataexport,
            'spec' =>$spec,
            'tl' =>$tl,
            'ucl' =>$ucl,
            'lcl' =>$lcl,
            'cl' =>$cl,
            'is' =>$is,
            'dataexport' => json_encode($dataexport)
        ]);
    } 
    public function addsavehistory(Request $request)	
    {
        $datahistory = [
        'id_createlab' => $request->input('idcreatelab'),
        'id_typedata' => $request->input('idhistory'),
        'history_detail' => $request->input('detail'),
        'history_date' => $request->input('date'),
        ];  
        DB::table('nt_history_output')->insert($datahistory);
        $data = DB::table('nt_history_output')
        ->where('id_createlab', $request->input('idcreatelab'))
        ->where('id_typedata', $request->input('idhistory'))
        ->orderBy('id_history','desc')->take(5)->get();
        return response()->json(['success' =>  1,'history'=>$data]);
    }
    public function addsavehistoryshow(Request $request)	
    {
        $model = DB::table('nt_oqa_lab')
        ->join('nt_model','nt_oqa_lab.id_model','=','nt_model.id_model')
        ->where('nt_oqa_lab.id_createlab', $request->input('idcreatelab'))->first();
        if($request->input('chk') == 1)
        {
           $data = DB::table('nt_history_output')
           ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_history_output.id_createlab')
           ->join('nt_model','nt_oqa_lab.id_model','=','nt_model.id_model')
           ->where('nt_model.id_model', $model->id_model)
           ->where('nt_model.part_number', $model->part_number)
           ->where('id_typedata', $request->input('idhistory'))
           ->orderBy('id_history','desc')->take(5)->get();

      
        }
        else
        {
            $data = DB::table('nt_history_output')
            ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_history_output.id_createlab')
            ->join('nt_model','nt_oqa_lab.id_model','=','nt_model.id_model')
            ->where('nt_model.id_model', $model->id_model)
            ->where('nt_model.part_number', $model->part_number)
            ->where('nt_history_output.id_typedata', $request->input('idhistory'))
            ->orderBy('nt_history_output.id_history','desc')->get();
        }
        return response()->json(['success' =>  1,'history'=>$data]);
    }
    public function pdfhistoryshow(Request $request)	
    {
        $model = DB::table('nt_oqa_lab')
        ->join('nt_model','nt_oqa_lab.id_model','=','nt_model.id_model')
        ->where('nt_model.id_model', $request->input('modelid'))->first();
        $data = DB::table('nt_history_output')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_history_output.id_createlab')
        ->join('nt_model','nt_oqa_lab.id_model','=','nt_model.id_model')
        ->where('nt_model.id_model', $model->id_model)
        ->where('nt_model.part_number', $model->part_number)
        ->where('id_typedata', $request->input('id_typedata'))
        ->orderBy('id_history','desc')->take(5)->get();
        return response()->json(['success' =>  1,'history'=>$data]);
    }
    public function deletehistory(Request $request)	
    {
        DB::table('nt_history_output')->where('id_history', $request->input('id'))->delete();
        $data = DB::table('nt_history_output')
        ->where('id_createlab', $request->input('idcreatelab'))
        ->where('id_typedata', 1)
        ->orderBy('id_history','desc')->take(5)->get();
        return response()->json(['success' =>  1,'history'=>$data]);
    }
    public function showcountdata(Request $request)	
    {
        $dateend_new = date('Y-m-d 00:00:00', strtotime($request->dateend. ' + 1 day'));
        //$dateend_new = date('Y-m-d 00:00:00', strtotime($request->dateend));
        //dd($request->datestart.' --> '.$dateend_new);
        $lpccount = DB::table('nt_datacf_lpc')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_datacf_lpc.id_createlab')
        ->where('nt_oqa_lab.save_date','<=',$dateend_new)
        ->where('nt_oqa_lab.save_date','>=',$request->datestart)
        ->where('nt_oqa_lab.lpc_confirm_st',1)
        ->where('nt_datacf_lpc.lpc_data','!=',null)
        ->where('nt_oqa_lab.id_model',$request->id)->count();  

        $semcount = DB::table('nt_datacf_sem')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_datacf_sem.id_createlab')
        ->where('nt_oqa_lab.save_date','<=',$dateend_new)
        ->where('nt_oqa_lab.save_date','>=',$request->datestart)
        ->where('nt_oqa_lab.sem_confirm_st',1)
        ->where('nt_oqa_lab.id_model',$request->id)->count();
        $iccount = DB::table('nt_datacf_ic')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_datacf_ic.id_createlab')
        ->where('nt_oqa_lab.save_date','<=',$dateend_new)
        ->where('nt_oqa_lab.save_date','>=',$request->datestart)
        ->where('nt_oqa_lab.ic_confirm_st',1)
        ->where('nt_oqa_lab.id_model',$request->id)->count();
        $gcmscount = DB::table('nt_datacf_gcms')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_datacf_gcms.id_createlab')
        ->where('nt_oqa_lab.save_date','<=',$dateend_new)
        ->where('nt_oqa_lab.save_date','>=',$request->datestart)
        ->where('nt_oqa_lab.gcms_confirm_st',1)
        ->where('nt_oqa_lab.id_model',$request->id)->count();
        $ftircount = DB::table('nt_datacf_ftir')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_datacf_ftir.id_createlab')
        ->where('nt_oqa_lab.save_date','<=',$dateend_new)
        ->where('nt_oqa_lab.save_date','>=',$request->datestart)
        ->where('nt_oqa_lab.ftir_confirm_st',1)
        ->where('nt_oqa_lab.id_model',$request->id)->count();
        return response()->json([
            'success' =>  1,
            'lpccount'=>$lpccount,
            'semcount'=>$semcount,
            'iccount'=>$iccount,
            'gcmscount'=>$gcmscount,
            'ftircount'=>$ftircount,
            'id_model'=>$request->id,
        ]);
    }
    public function showcountdatacustomer(Request $request)	
    {
        $dateend_new = date('Y-m-d 00:00:00', strtotime($request->dateend. ' + 1 day'));
        //$dateend_new = date('Y-m-d 00:00:00', strtotime($request->dateend));
        $lpccount = DB::table('nt_data_lpc')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_lpc.id_createlab')
        ->where('nt_oqa_lab.save_date','<=',$dateend_new)
        ->where('nt_oqa_lab.save_date','>=',$request->datestart)
        ->where('nt_oqa_lab.lpc_confirm_st',1)
        ->where('nt_data_lpc.lpc_data','!=',null)
        ->where('nt_oqa_lab.id_model',$request->id)->count();  

        $semcount = DB::table('nt_data_sem')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
        ->where('nt_oqa_lab.save_date','<=',$dateend_new)
        ->where('nt_oqa_lab.save_date','>=',$request->datestart)
        ->where('nt_oqa_lab.sem_confirm_st',1)
        ->where('nt_oqa_lab.id_model',$request->id)->count();
        $iccount = DB::table('nt_data_ic')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ic.id_createlab')
        ->where('nt_oqa_lab.save_date','<=',$dateend_new)
        ->where('nt_oqa_lab.save_date','>=',$request->datestart)
        ->where('nt_oqa_lab.ic_confirm_st',1)
        ->where('nt_oqa_lab.id_model',$request->id)->count();
        $gcmscount = DB::table('nt_data_gcms')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
        ->where('nt_oqa_lab.save_date','<=',$dateend_new)
        ->where('nt_oqa_lab.save_date','>=',$request->datestart)
        ->where('nt_oqa_lab.gcms_confirm_st',1)
        ->where('nt_oqa_lab.id_model',$request->id)->count();
        $ftircount = DB::table('nt_data_ftir')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ftir.id_createlab')
        ->where('nt_oqa_lab.save_date','<=',$dateend_new)
        ->where('nt_oqa_lab.save_date','>=',$request->datestart)
        ->where('nt_oqa_lab.ftir_confirm_st',1)
        ->where('nt_oqa_lab.id_model',$request->id)->count();
        return response()->json([
            'success' =>  1,
            'lpccount'=>$lpccount,
            'semcount'=>$semcount,
            'iccount'=>$iccount,
            'gcmscount'=>$gcmscount,
            'ftircount'=>$ftircount,
            'id_model'=>$request->id,
        ]);
    }
  
    public function rawexportexcel(Request $request)	
    {

        ini_set('memory_limit', '-1');
        $chkexport = [];
        if(isset($request->id))
        {
            if(count($request->id) == 2)
            {
            $a1 = DB::table('nt_model')->where('id_model', $request->id[0])->first();
            $a2 = DB::table('nt_model')->where('id_model', $request->id[1])->first();
            $text1 = $a1->name_model;
            $text2 = $a2->name_model;
            $chktext1 = mb_substr($text1,0,-3);
            $chktext2 = mb_substr($text2,0,-3);
                if($chktext1 == $chktext2)
                {
                    if($a1->part == "UP")
                    {
                        $model1 = $a1;
                    }
                    else
                    {
                        $model1 = $a2;
                    }
                    if($a2->part == "DN")
                    {
                        $model2 = $a2;
                    }
                    else
                    {
                        $model2 = $a1;
                    }
                    $dateend1 = date('Y-m-d 00:00:00', strtotime($request->input('dateend'.$model1->id_model). ' + 1 day'));
                    $dateend2 = date('Y-m-d 00:00:00', strtotime($request->input('dateend'.$model2->id_model). ' + 1 day'));
                    $datalablpc = DB::table('nt_datacf_lpc')
                    ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_datacf_lpc.id_createlab')
                    ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                    ->where('nt_oqa_lab.save_date','>=',$request->input('datestart'.$model1->id_model))
                    ->where('nt_oqa_lab.save_date','<=',$dateend1)
                    ->where('nt_oqa_lab.id_model',$model1->id_model)
                    ->where('nt_oqa_lab.lpc_confirm_st',1)
                    ->orderBy('nt_oqa_lab.save_date', 'asc')->get();

                    $datalablpcdw = DB::table('nt_datacf_lpc')
                    ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_datacf_lpc.id_createlab')
                    ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                    ->where('nt_oqa_lab.save_date','>=',$request->input('datestart'.$model2->id_model))
                    ->where('nt_oqa_lab.save_date','<=',$dateend2)
                    ->where('nt_oqa_lab.id_model',$model2->id_model)
                    ->where('nt_oqa_lab.lpc_confirm_st',1)
                    ->orderBy('nt_oqa_lab.save_date', 'asc')->get();
                
                    $datalabsem = DB::table('nt_datacf_sem')
                    ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_datacf_sem.id_createlab')
                    ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                    ->where('nt_oqa_lab.save_date','>=',$request->input('datestart'.$model1->id_model))
                    ->where('nt_oqa_lab.save_date','<=',$dateend1)
                    ->where('nt_oqa_lab.id_model',$model1->id_model)
                    ->where('nt_oqa_lab.sem_confirm_st',1)
                    ->orderBy('nt_oqa_lab.save_date', 'asc')->get();
                    $datalabsemdw = DB::table('nt_datacf_sem')
                    ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_datacf_sem.id_createlab')
                    ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                    ->where('nt_oqa_lab.save_date','>=',$request->input('datestart'.$model1->id_model))
                    ->where('nt_oqa_lab.save_date','<=',$dateend2)
                    ->where('nt_oqa_lab.id_model',$model2->id_model)
                    ->where('nt_oqa_lab.sem_confirm_st',1)
                    ->orderBy('nt_oqa_lab.save_date', 'asc')->get();
        
                    $datalabic = DB::table('nt_datacf_ic')
                    ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_datacf_ic.id_createlab')
                    ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                    ->where('nt_oqa_lab.save_date','>=',$request->input('datestart'.$model1->id_model))
                    ->where('nt_oqa_lab.save_date','<=',$dateend1)
                    ->where('nt_oqa_lab.id_model',$model1->id_model)
                    ->where('nt_oqa_lab.ic_confirm_st',1)
                    ->orderBy('nt_oqa_lab.save_date', 'asc')->get();
                    

                    $datalabicdw = DB::table('nt_datacf_ic')
                    ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_datacf_ic.id_createlab')
                    ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                    ->where('nt_oqa_lab.save_date','>=',$request->input('datestart'.$model2->id_model))
                    ->where('nt_oqa_lab.save_date','<=',$dateend2)
                    ->where('nt_oqa_lab.id_model',$model2->id_model)
                    ->where('nt_oqa_lab.ic_confirm_st',1)
                    ->orderBy('nt_oqa_lab.save_date', 'asc')->get();
                
                    $datalabgcms = DB::table('nt_datacf_gcms')
                    ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_datacf_gcms.id_createlab')
                    ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                    ->leftjoin('nt_outgassing_gcms','nt_outgassing_gcms.id_createlab','=','nt_oqa_lab.id_createlab')
                    ->where('nt_oqa_lab.save_date','>=',$request->input('datestart'.$model1->id_model))
                    ->where('nt_oqa_lab.save_date','<=',$dateend1)
                    ->where('nt_oqa_lab.id_model',$model1->id_model)
                    ->where('nt_oqa_lab.gcms_confirm_st',1)
                    ->orderBy('nt_oqa_lab.save_date', 'asc')->get();
                    $datalabgcmsdw = DB::table('nt_datacf_gcms')
                    ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_datacf_gcms.id_createlab')
                    ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                    ->leftjoin('nt_outgassing_gcms','nt_outgassing_gcms.id_createlab','=','nt_oqa_lab.id_createlab')
                    ->where('nt_oqa_lab.save_date','>=',$request->input('datestart'.$model2->id_model))
                    ->where('nt_oqa_lab.save_date','<=',$dateend2)
                    ->where('nt_oqa_lab.id_model',$model2->id_model)
                    ->where('nt_oqa_lab.gcms_confirm_st',1)
                    ->orderBy('nt_oqa_lab.save_date', 'asc')->get();
                
                    $datalabftir = DB::table('nt_datacf_ftir')
                    ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_datacf_ftir.id_createlab')
                    ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                    ->where('nt_oqa_lab.save_date','>=',$request->input('datestart'.$model1->id_model))
                    ->where('nt_oqa_lab.save_date','<=',$dateend1)
                    ->where('nt_oqa_lab.id_model',$model1->id_model)
                    ->where('nt_oqa_lab.ftir_confirm_st',1)
                    ->orderBy('nt_oqa_lab.save_date', 'asc')->get();
                    $datalabftirdw = DB::table('nt_datacf_ftir')
                    ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_datacf_ftir.id_createlab')
                    ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                    ->where('nt_oqa_lab.save_date','>=',$request->input('datestart'.$model2->id_model))
                    ->where('nt_oqa_lab.save_date','<=',$dateend2)
                    ->where('nt_oqa_lab.id_model',$model2->id_model)
                    ->where('nt_oqa_lab.ftir_confirm_st',1)
                    ->orderBy('nt_oqa_lab.save_date', 'asc')->get();


                }
                else
                {
                return response()->json(['success' => 2]);
                }
            }
            else
            {
            return response()->json(['success' => 3]);
            }
        }
        else
        {
        return response()->json(['success' => 4]);
        }
    
    
        $specification = DB::table('nt_model_spec')->where('id_model', $model1->id_model)->where('id_model_type', 1)->first();
        $triggerlimit = DB::table('nt_model_spec')->where('id_model', $model1->id_model)->where('id_model_type', 2)->first();
        $ucl = DB::table('nt_model_spec')->where('id_model', $model1->id_model)->where('id_model_type', 3)->first();
        $lcl = DB::table('nt_model_spec')->where('id_model', $model1->id_model)->where('id_model_type', 4)->first();
        $cl = DB::table('nt_model_spec')->where('id_model', $model1->id_model)->where('id_model_type', 5)->first();

        $specification1 = DB::table('nt_model_spec')->where('id_model', $model2->id_model)->where('id_model_type', 1)->first();
        $triggerlimit1 = DB::table('nt_model_spec')->where('id_model', $model2->id_model)->where('id_model_type', 2)->first();
        $ucl1 = DB::table('nt_model_spec')->where('id_model', $model2->id_model)->where('id_model_type', 3)->first();
        $lcl1 = DB::table('nt_model_spec')->where('id_model', $model2->id_model)->where('id_model_type', 4)->first();
        $cl1 = DB::table('nt_model_spec')->where('id_model', $model2->id_model)->where('id_model_type', 5)->first();
        $file = storage_path('NHK-File/template/report/Report_Excel_file.xlsx');
        $filetime = date('YmdHis');
        //$namefile = 'graphexcel'.$filetime;
        $spacename = str_replace(' ', '', $chktext1);
        $namefile = 'Excel_Raw_'.$spacename.'_'.date("YmdHis");
        
        Excel::load($file, function ($doc)use($specification,$triggerlimit,$ucl,$lcl,$cl,$cl1,$specification1,$triggerlimit1,$ucl1,$lcl1,$datalablpc,$datalabsem,$datalabic,$datalabgcms,$datalabftir,$datalablpcdw,$datalabsemdw,$datalabicdw,$datalabgcmsdw,$datalabftirdw) {
            $sheet = $doc->setActiveSheetIndex(0);
            $sheetlpc = $doc->setActiveSheetIndex(1);
            $sheetclass1 = $doc->setActiveSheetIndex(2);
            $sheetclass2 = $doc->setActiveSheetIndex(3);
            $sheetclass3 = $doc->setActiveSheetIndex(4);
            $sheetclass3_1 = $doc->setActiveSheetIndex(5);
            $sheetclass3_2 = $doc->setActiveSheetIndex(6);
            $sheetclass3_3 = $doc->setActiveSheetIndex(7);
            $sheetclass4 = $doc->setActiveSheetIndex(8);
            $sheetclass5 = $doc->setActiveSheetIndex(9);
            $sheetclass5_1 = $doc->setActiveSheetIndex(10);
            $sheetclass6 = $doc->setActiveSheetIndex(11);
            $sheetanion = $doc->setActiveSheetIndex(12);
            $sheetsulfate = $doc->setActiveSheetIndex(13);
            $sheetcation = $doc->setActiveSheetIndex(14);
            $sheetog = $doc->setActiveSheetIndex(15);
            $sheetlm = $doc->setActiveSheetIndex(16);
            $sheethm = $doc->setActiveSheetIndex(17);
            $sheetsm = $doc->setActiveSheetIndex(18);
            $sheetftir = $doc->setActiveSheetIndex(19);
            $i = 34;
            
            foreach ($datalablpc as $item) {     
            $starttime = date('YmdHis',strtotime($item->save_date));
            $ftime = date('YmdHis',strtotime($item->ftime_lpc));
            $stdate = date('Y-m-d', strtotime($item->save_date));
            
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$stdate)
                ->where('nt_model_history.date_end','>=',$stdate)
                ->get();
                if($datafirst->count() == 0)
                {
                $data1 = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                ->first();
                
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$data1->date_start)  
                ->where('nt_model_history.date_end','>=',$data1->date_start)
                ->get(); 
                }
                
            $sheetlpc->setCellValue('F'.$i,date('d-M-y',strtotime($item->save_date)));
            $sheetlpc->setCellValue('G'.$i,slope::nhk_formatnumber($item->lpc1, 0));
            $sheetlpc->setCellValue('H'.$i,slope::nhk_formatnumber($item->lpc2, 0));
            $sheetlpc->setCellValue('I'.$i,slope::nhk_formatnumber($item->lpc3, 0));
            $sheetlpc->setCellValue('J'.$i,slope::nhk_formatnumber($item->lpc_data, 0));
            $sheetlpc->setCellValue('K'.$i,$item->lotnumber_lab);
            $sheetlpc->setCellValue('L'.$i,slope::nhk_formatnumber($datafirst[1]->alpc, 0));
            $sheetlpc->setCellValue('M'.$i,slope::nhk_formatnumber($datafirst[2]->alpc, 0));
            $sheetlpc->setCellValue('N'.$i,slope::nhk_formatnumber($datafirst[0]->alpc, 0));
            $sheetlpc->setCellValue('O'.$i,slope::nhk_formatnumber($specification->alpc, 0));
            $sheetlpc->setCellValue('P'.$i,$starttime);
            $sheetlpc->setCellValue('Q'.$i,$ftime);
            $sheetlpc->setCellValue('AF'.$i,$datafirst[3]->alpc);
            
            $i++;
            }
            $i = 34;
            
            foreach ($datalablpcdw as $item) {     
                $starttime = date('YmdHis',strtotime($item->save_date));
                $ftime = date('YmdHis',strtotime($item->ftime_lpc));
                $stdate = date('Y-m-d', strtotime($item->save_date));
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$stdate)
                ->where('nt_model_history.date_end','>=',$stdate)
                ->get();
                if($datafirst->count() == 0)
                {
                $data1 = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                ->first();
                
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$data1->date_start)  
                ->where('nt_model_history.date_end','>=',$data1->date_start)
                ->get(); 
                }
                $sheetlpc->setCellValue('S'.$i,date('d-M-y',strtotime($item->save_date)));
                $sheetlpc->setCellValue('T'.$i,slope::nhk_formatnumber($item->lpc1, 0));
                $sheetlpc->setCellValue('U'.$i,slope::nhk_formatnumber($item->lpc2, 0));
                $sheetlpc->setCellValue('V'.$i,slope::nhk_formatnumber($item->lpc3, 0));
                $sheetlpc->setCellValue('W'.$i,slope::nhk_formatnumber($item->lpc_data, 0));
                $sheetlpc->setCellValue('X'.$i,$item->lotnumber_lab);
                $sheetlpc->setCellValue('Y'.$i,slope::nhk_formatnumber($datafirst[1]->alpc, 0));
                $sheetlpc->setCellValue('Z'.$i,slope::nhk_formatnumber($datafirst[2]->alpc, 0));
                $sheetlpc->setCellValue('AA'.$i,slope::nhk_formatnumber($datafirst[0]->alpc, 0));
                $sheetlpc->setCellValue('AB'.$i,slope::nhk_formatnumber($specification1->alpc, 0));
                $sheetlpc->setCellValue('AC'.$i,$starttime);
                $sheetlpc->setCellValue('AD'.$i,$ftime);
                $sheetlpc->setCellValue('AG'.$i,$datafirst[3]->alpc);
                $i++;
            }
            
            $i = 34;
            foreach ($datalabsem as $item) {     
                $starttime = date('YmdHis',strtotime($item->save_date));
                $ftime = date('YmdHis',strtotime($item->ftime_sem));
                $stdate = date('Y-m-d', strtotime($item->save_date));
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$stdate)
                ->where('nt_model_history.date_end','>=',$stdate)
                ->get();
                if($datafirst->count() == 0)
                {
                $data1 = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                ->first();
                
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$data1->date_start)  
                ->where('nt_model_history.date_end','>=',$data1->date_start)
                ->get(); 
                }
                $sheetclass1->setCellValue('F'.$i,date('d-M-y',strtotime($item->save_date)));
                $sheetclass1->setCellValue('G'.$i,slope::nhk_formatnumber($item->sem_class1, 1));
                $sheetclass1->setCellValue('H'.$i,$item->lotnumber_lab);
                $sheetclass1->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->class1, 1));
                $sheetclass1->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[2]->class1, 1));
                $sheetclass1->setCellValue('K'.$i,slope::nhk_formatnumber($datafirst[0]->class1, 1));
                $sheetclass1->setCellValue('L'.$i,slope::nhk_formatnumber($specification->class1, 1));
                $sheetclass1->setCellValue('M'.$i,$starttime);
                $sheetclass1->setCellValue('N'.$i,$ftime);
                $sheetclass1->setCellValue('Z'.$i,$datafirst[3]->class1);

                $sheetclass2->setCellValue('F'.$i,date('d-M-y',strtotime($item->save_date)));
                $sheetclass2->setCellValue('G'.$i,slope::nhk_formatnumber($item->sem_class2, 1));
                $sheetclass2->setCellValue('H'.$i,$item->lotnumber_lab);
                $sheetclass2->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->class2, 1));
                $sheetclass2->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[2]->class2, 1));
                $sheetclass2->setCellValue('K'.$i,slope::nhk_formatnumber($datafirst[0]->class2, 1));
                $sheetclass2->setCellValue('L'.$i,slope::nhk_formatnumber($specification->class2, 1));
                $sheetclass2->setCellValue('M'.$i,$starttime);
                $sheetclass2->setCellValue('N'.$i,$ftime);
                $sheetclass2->setCellValue('Z'.$i,$datafirst[3]->class2);

                $sheetclass3->setCellValue('F'.$i,date('d-M-y',strtotime($item->save_date)));
                $sheetclass3->setCellValue('G'.$i,slope::nhk_formatnumber($item->sem_class3, 1));
                $sheetclass3->setCellValue('H'.$i,$item->lotnumber_lab);
                $sheetclass3->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->class3, 1));
                $sheetclass3->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[2]->class3, 1));
                $sheetclass3->setCellValue('K'.$i,slope::nhk_formatnumber($datafirst[0]->class3, 1));
                $sheetclass3->setCellValue('L'.$i,slope::nhk_formatnumber($specification->class3, 1));
                $sheetclass3->setCellValue('M'.$i,$starttime);
                $sheetclass3->setCellValue('N'.$i,$ftime);
                $sheetclass3->setCellValue('Z'.$i,$datafirst[3]->class3);

                $sheetclass3_1->setCellValue('F'.$i,date('d-M-y',strtotime($item->save_date)));
                $sheetclass3_1->setCellValue('G'.$i,slope::nhk_formatnumber($item->sem_class3_1, 1));
                $sheetclass3_1->setCellValue('H'.$i,$item->lotnumber_lab);
                $sheetclass3_1->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->class3_1, 1));
                $sheetclass3_1->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[2]->class3_1, 1));
                $sheetclass3_1->setCellValue('K'.$i,slope::nhk_formatnumber($datafirst[0]->class3_1, 1));
                $sheetclass3_1->setCellValue('L'.$i,slope::nhk_formatnumber($specification->class3_1, 1));
                $sheetclass3_1->setCellValue('M'.$i,$starttime);
                $sheetclass3_1->setCellValue('N'.$i,$ftime);
                $sheetclass3_1->setCellValue('Z'.$i,$datafirst[3]->class3_1);

                $sheetclass3_2->setCellValue('F'.$i,date('d-M-y',strtotime($item->save_date)));
                $sheetclass3_2->setCellValue('G'.$i,slope::nhk_formatnumber($item->sem_class3_2, 1));
                $sheetclass3_2->setCellValue('H'.$i,$item->lotnumber_lab);
                $sheetclass3_2->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->class3_2, 1));
                $sheetclass3_2->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[2]->class3_2, 1));
                $sheetclass3_2->setCellValue('K'.$i,slope::nhk_formatnumber($datafirst[0]->class3_2, 1));
                $sheetclass3_2->setCellValue('L'.$i,slope::nhk_formatnumber($specification->class3_2, 1));
                $sheetclass3_2->setCellValue('M'.$i,$starttime);
                $sheetclass3_2->setCellValue('N'.$i,$ftime);
                $sheetclass3_2->setCellValue('Z'.$i,$datafirst[3]->class3_2);

                $sheetclass3_3->setCellValue('F'.$i,date('d-M-y',strtotime($item->save_date)));
                $sheetclass3_3->setCellValue('G'.$i,slope::nhk_formatnumber($item->sem_class3_3, 1));
                $sheetclass3_3->setCellValue('H'.$i,$item->lotnumber_lab);
                $sheetclass3_3->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->class3_3, 1));
                $sheetclass3_3->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[2]->class3_3, 1));
                $sheetclass3_3->setCellValue('K'.$i,slope::nhk_formatnumber($datafirst[0]->class3_3, 1));
                $sheetclass3_3->setCellValue('L'.$i,slope::nhk_formatnumber($specification->class3_3, 1));
                $sheetclass3_3->setCellValue('M'.$i,$starttime);
                $sheetclass3_3->setCellValue('N'.$i,$ftime);
                $sheetclass3_3->setCellValue('Z'.$i,$datafirst[3]->class3_3);

                $sheetclass4->setCellValue('F'.$i,date('d-M-y',strtotime($item->save_date)));
                $sheetclass4->setCellValue('G'.$i,slope::nhk_formatnumber($item->sem_class4, 1));
                $sheetclass4->setCellValue('H'.$i,$item->lotnumber_lab);
                $sheetclass4->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->class4, 1));
                $sheetclass4->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[2]->class4, 1));
                $sheetclass4->setCellValue('K'.$i,slope::nhk_formatnumber($datafirst[0]->class4, 1));
                $sheetclass4->setCellValue('L'.$i,slope::nhk_formatnumber($specification->class4, 1));
                $sheetclass4->setCellValue('M'.$i,$starttime);
                $sheetclass4->setCellValue('N'.$i,$ftime);
                $sheetclass4->setCellValue('Z'.$i,$datafirst[3]->class4);

                $sheetclass5->setCellValue('F'.$i,date('d-M-y',strtotime($item->save_date)));
                $sheetclass5->setCellValue('G'.$i,slope::nhk_formatnumber($item->sem_class5, 1));
                $sheetclass5->setCellValue('H'.$i,$item->lotnumber_lab);
                $sheetclass5->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->class5, 1));
                $sheetclass5->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[2]->class5, 1));
                $sheetclass5->setCellValue('K'.$i,slope::nhk_formatnumber($datafirst[0]->class5, 1));
                $sheetclass5->setCellValue('L'.$i,slope::nhk_formatnumber($specification->class5, 1));
                $sheetclass5->setCellValue('M'.$i,$starttime);
                $sheetclass5->setCellValue('N'.$i,$ftime);
                $sheetclass5->setCellValue('Z'.$i,$datafirst[3]->class5);

                $sheetclass5_1->setCellValue('F'.$i,date('d-M-y',strtotime($item->save_date)));
                $sheetclass5_1->setCellValue('G'.$i,slope::nhk_formatnumber($item->sem_class5_1, 1));
                $sheetclass5_1->setCellValue('H'.$i,$item->lotnumber_lab);
                $sheetclass5_1->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->class5_1, 1));
                $sheetclass5_1->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[2]->class5_1, 1));
                $sheetclass5_1->setCellValue('K'.$i,slope::nhk_formatnumber($datafirst[0]->class5_1, 1));
                $sheetclass5_1->setCellValue('L'.$i,slope::nhk_formatnumber($specification->class5_1, 1));
                $sheetclass5_1->setCellValue('M'.$i,$starttime);
                $sheetclass5_1->setCellValue('N'.$i,$ftime);
                $sheetclass5_1->setCellValue('Z'.$i,$datafirst[3]->class5_1);

                $sheetclass6->setCellValue('F'.$i,date('d-M-y',strtotime($item->save_date)));
                $sheetclass6->setCellValue('G'.$i,slope::nhk_formatnumber($item->sem_class6, 1));
                $sheetclass6->setCellValue('H'.$i,$item->lotnumber_lab);
                $sheetclass6->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->class6, 1));
                $sheetclass6->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[2]->class6, 1));
                $sheetclass6->setCellValue('K'.$i,slope::nhk_formatnumber($datafirst[0]->class6, 1));
                $sheetclass6->setCellValue('L'.$i,slope::nhk_formatnumber($specification->class6, 1));
                $sheetclass6->setCellValue('M'.$i,$starttime);
                $sheetclass6->setCellValue('N'.$i,$ftime);
                $sheetclass6->setCellValue('Z'.$i,$datafirst[3]->class6);

                $i++;
            }
            $i = 34;
            foreach ($datalabsemdw as $item) {     
                $starttime = date('YmdHis',strtotime($item->save_date));
                $ftime = date('YmdHis',strtotime($item->ftime_sem));
                $stdate = date('Y-m-d', strtotime($item->save_date));
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$stdate)
                ->where('nt_model_history.date_end','>=',$stdate)
                ->get();
                if($datafirst->count() == 0)
                {
                $data1 = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                ->first();
                
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$data1->date_start)  
                ->where('nt_model_history.date_end','>=',$data1->date_start)
                ->get(); 
                }
                $sheetclass1->setCellValue('P'.$i,date('d-M-y',strtotime($item->save_date)));
                $sheetclass1->setCellValue('Q'.$i,slope::nhk_formatnumber($item->sem_class1, 1));
                $sheetclass1->setCellValue('R'.$i,$item->lotnumber_lab);
                $sheetclass1->setCellValue('S'.$i,slope::nhk_formatnumber($datafirst[1]->class1, 1));
                $sheetclass1->setCellValue('T'.$i,slope::nhk_formatnumber($datafirst[2]->class1, 1));
                $sheetclass1->setCellValue('U'.$i,slope::nhk_formatnumber($datafirst[0]->class1, 1));
                $sheetclass1->setCellValue('V'.$i,slope::nhk_formatnumber($specification1->class1, 1));
                $sheetclass1->setCellValue('W'.$i,$starttime);
                $sheetclass1->setCellValue('X'.$i,$ftime);
                $sheetclass1->setCellValue('AA'.$i,$datafirst[3]->class1);

                $sheetclass2->setCellValue('P'.$i,date('d-M-y',strtotime($item->save_date)));
                $sheetclass2->setCellValue('Q'.$i,slope::nhk_formatnumber($item->sem_class2, 1));
                $sheetclass2->setCellValue('R'.$i,$item->lotnumber_lab);
                $sheetclass2->setCellValue('S'.$i,slope::nhk_formatnumber($datafirst[1]->class2, 1));
                $sheetclass2->setCellValue('T'.$i,slope::nhk_formatnumber($datafirst[2]->class2, 1));
                $sheetclass2->setCellValue('U'.$i,slope::nhk_formatnumber($datafirst[0]->class2, 1));
                $sheetclass2->setCellValue('V'.$i,slope::nhk_formatnumber($specification1->class2, 1));
                $sheetclass2->setCellValue('W'.$i,$starttime);
                $sheetclass2->setCellValue('X'.$i,$ftime);
                $sheetclass2->setCellValue('AA'.$i,$datafirst[3]->class2);

                $sheetclass3->setCellValue('P'.$i,date('d-M-y',strtotime($item->save_date)));
                $sheetclass3->setCellValue('Q'.$i,slope::nhk_formatnumber($item->sem_class3, 1));
                $sheetclass3->setCellValue('R'.$i,$item->lotnumber_lab);
                $sheetclass3->setCellValue('S'.$i,slope::nhk_formatnumber($datafirst[1]->class3, 1));
                $sheetclass3->setCellValue('T'.$i,slope::nhk_formatnumber($datafirst[2]->class3, 1));
                $sheetclass3->setCellValue('U'.$i,slope::nhk_formatnumber($datafirst[0]->class3, 1));
                $sheetclass3->setCellValue('V'.$i,slope::nhk_formatnumber($specification1->class3, 1));
                $sheetclass3->setCellValue('W'.$i,$starttime);
                $sheetclass3->setCellValue('X'.$i,$ftime);
                $sheetclass3->setCellValue('AA'.$i,$datafirst[3]->class3);

                $sheetclass3_1->setCellValue('P'.$i,date('d-M-y',strtotime($item->save_date)));
                $sheetclass3_1->setCellValue('Q'.$i,slope::nhk_formatnumber($item->sem_class3_1, 1));
                $sheetclass3_1->setCellValue('R'.$i,$item->lotnumber_lab);
                $sheetclass3_1->setCellValue('S'.$i,slope::nhk_formatnumber($datafirst[1]->class3_1, 1));
                $sheetclass3_1->setCellValue('T'.$i,slope::nhk_formatnumber($datafirst[2]->class3_1, 1));
                $sheetclass3_1->setCellValue('U'.$i,slope::nhk_formatnumber($datafirst[0]->class3_1, 1));
                $sheetclass3_1->setCellValue('V'.$i,slope::nhk_formatnumber($specification1->class3_1, 1));
                $sheetclass3_1->setCellValue('W'.$i,$starttime);
                $sheetclass3_1->setCellValue('X'.$i,$ftime);
                $sheetclass3_1->setCellValue('AA'.$i,$datafirst[3]->class3_1);

                $sheetclass3_2->setCellValue('P'.$i,date('d-M-y',strtotime($item->save_date)));
                $sheetclass3_2->setCellValue('Q'.$i,slope::nhk_formatnumber($item->sem_class3_2, 1));
                $sheetclass3_2->setCellValue('R'.$i,$item->lotnumber_lab);
                $sheetclass3_2->setCellValue('S'.$i,slope::nhk_formatnumber($datafirst[1]->class3_2, 1));
                $sheetclass3_2->setCellValue('T'.$i,slope::nhk_formatnumber($datafirst[2]->class3_2, 1));
                $sheetclass3_2->setCellValue('U'.$i,slope::nhk_formatnumber($datafirst[0]->class3_2, 1));
                $sheetclass3_2->setCellValue('V'.$i,slope::nhk_formatnumber($specification1->class3_2, 1));
                $sheetclass3_2->setCellValue('W'.$i,$starttime);
                $sheetclass3_2->setCellValue('X'.$i,$ftime);
                $sheetclass3_2->setCellValue('AA'.$i,$datafirst[3]->class3_2);

                $sheetclass3_3->setCellValue('P'.$i,date('d-M-y',strtotime($item->save_date)));
                $sheetclass3_3->setCellValue('Q'.$i,slope::nhk_formatnumber($item->sem_class3_3, 1));
                $sheetclass3_3->setCellValue('R'.$i,$item->lotnumber_lab);
                $sheetclass3_3->setCellValue('S'.$i,slope::nhk_formatnumber($datafirst[1]->class3_3, 1));
                $sheetclass3_3->setCellValue('T'.$i,slope::nhk_formatnumber($datafirst[2]->class3_3, 1));
                $sheetclass3_3->setCellValue('U'.$i,slope::nhk_formatnumber($datafirst[0]->class3_3, 1));
                $sheetclass3_3->setCellValue('V'.$i,slope::nhk_formatnumber($specification1->class3_3, 1));
                $sheetclass3_3->setCellValue('W'.$i,$starttime);
                $sheetclass3_3->setCellValue('X'.$i,$ftime);
                $sheetclass3_3->setCellValue('AA'.$i,$datafirst[3]->class3_3);

                $sheetclass4->setCellValue('P'.$i,date('d-M-y',strtotime($item->save_date)));
                $sheetclass4->setCellValue('Q'.$i,slope::nhk_formatnumber($item->sem_class4, 1));
                $sheetclass4->setCellValue('R'.$i,$item->lotnumber_lab);
                $sheetclass4->setCellValue('S'.$i,slope::nhk_formatnumber($datafirst[1]->class4, 1));
                $sheetclass4->setCellValue('T'.$i,slope::nhk_formatnumber($datafirst[2]->class4, 1));
                $sheetclass4->setCellValue('U'.$i,slope::nhk_formatnumber($datafirst[0]->class4, 1));
                $sheetclass4->setCellValue('V'.$i,slope::nhk_formatnumber($specification1->class4, 1));
                $sheetclass4->setCellValue('W'.$i,$starttime);
                $sheetclass4->setCellValue('X'.$i,$ftime);
                $sheetclass4->setCellValue('AA'.$i,$datafirst[3]->class4);

                $sheetclass5->setCellValue('P'.$i,date('d-M-y',strtotime($item->save_date)));
                $sheetclass5->setCellValue('Q'.$i,slope::nhk_formatnumber($item->sem_class5, 1));
                $sheetclass5->setCellValue('R'.$i,$item->lotnumber_lab);
                $sheetclass5->setCellValue('S'.$i,slope::nhk_formatnumber($datafirst[1]->class5, 1));
                $sheetclass5->setCellValue('T'.$i,slope::nhk_formatnumber($datafirst[2]->class5, 1));
                $sheetclass5->setCellValue('U'.$i,slope::nhk_formatnumber($datafirst[0]->class5, 1));
                $sheetclass5->setCellValue('V'.$i,slope::nhk_formatnumber($specification1->class5, 1));
                $sheetclass5->setCellValue('W'.$i,$starttime);
                $sheetclass5->setCellValue('X'.$i,$ftime);
                $sheetclass5->setCellValue('AA'.$i,$datafirst[3]->class5);

                $sheetclass5_1->setCellValue('P'.$i,date('d-M-y',strtotime($item->save_date)));
                $sheetclass5_1->setCellValue('Q'.$i,slope::nhk_formatnumber($item->sem_class5_1, 1));
                $sheetclass5_1->setCellValue('R'.$i,$item->lotnumber_lab);
                $sheetclass5_1->setCellValue('S'.$i,slope::nhk_formatnumber($datafirst[1]->class5_1, 1));
                $sheetclass5_1->setCellValue('T'.$i,slope::nhk_formatnumber($datafirst[2]->class5_1, 1));
                $sheetclass5_1->setCellValue('U'.$i,slope::nhk_formatnumber($datafirst[0]->class5_1, 1));
                $sheetclass5_1->setCellValue('V'.$i,slope::nhk_formatnumber($specification1->class5_1, 1));
                $sheetclass5_1->setCellValue('W'.$i,$starttime);
                $sheetclass5_1->setCellValue('X'.$i,$ftime);
                $sheetclass5_1->setCellValue('AA'.$i,$datafirst[3]->class5_1);

                $sheetclass6->setCellValue('P'.$i,date('d-M-y',strtotime($item->save_date)));
                $sheetclass6->setCellValue('Q'.$i,slope::nhk_formatnumber($item->sem_class6, 1));
                $sheetclass6->setCellValue('R'.$i,$item->lotnumber_lab);
                $sheetclass6->setCellValue('S'.$i,slope::nhk_formatnumber($datafirst[1]->class6, 1));
                $sheetclass6->setCellValue('T'.$i,slope::nhk_formatnumber($datafirst[2]->class6, 1));
                $sheetclass6->setCellValue('U'.$i,slope::nhk_formatnumber($datafirst[0]->class6, 1));
                $sheetclass6->setCellValue('V'.$i,slope::nhk_formatnumber($specification1->class6, 1));
                $sheetclass6->setCellValue('W'.$i,$starttime);
                $sheetclass6->setCellValue('X'.$i,$ftime);
                $sheetclass6->setCellValue('AA'.$i,$datafirst[3]->class6);

                $i++;
            }
            $i = 34;
            foreach ($datalabic as $item) {     
                $starttime = date('YmdHis',strtotime($item->save_date));
                $ftime = date('YmdHis',strtotime($item->ftime_ic));
                $stdate = date('Y-m-d', strtotime($item->save_date));
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$stdate)
                ->where('nt_model_history.date_end','>=',$stdate)
                ->get();
                if($datafirst->count() == 0)
                {
                $data1 = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                ->first();
                
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$data1->date_start)  
                ->where('nt_model_history.date_end','>=',$data1->date_start)
                ->get(); 
                }
                $sheetanion->setCellValue('F'.$i,date('d-M-y',strtotime($item->save_date)));
                $sheetanion->setCellValue('G'.$i,slope::nhk_formatnumber($item->ic_anion_up, 5));
                $sheetanion->setCellValue('H'.$i,$item->lotnumber_lab);
                $sheetanion->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->anion, 5));
                $sheetanion->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[2]->anion, 5));
                $sheetanion->setCellValue('K'.$i,slope::nhk_formatnumber($datafirst[0]->anion, 5));
                $sheetanion->setCellValue('L'.$i,slope::nhk_formatnumber($specification->anion, 5));
                $sheetanion->setCellValue('M'.$i,$starttime);
                $sheetanion->setCellValue('N'.$i,$ftime);
                $sheetanion->setCellValue('O'.$i,slope::nhk_formatnumber($item->ic_anion_fluoride, 5));
                $sheetanion->setCellValue('P'.$i,slope::nhk_formatnumber($item->ic_anion_chloride, 5));
                $sheetanion->setCellValue('Q'.$i,slope::nhk_formatnumber($item->ic_anion_nitrile, 5));
                $sheetanion->setCellValue('R'.$i,slope::nhk_formatnumber($item->ic_anion_bromid, 5));
                $sheetanion->setCellValue('S'.$i,slope::nhk_formatnumber($item->ic_anion_nitrate, 5));
                $sheetanion->setCellValue('T'.$i,slope::nhk_formatnumber($item->ic_anion_sulphate, 5));
                $sheetanion->setCellValue('U'.$i,slope::nhk_formatnumber($item->ic_anion_phosphate, 5));
                $sheetanion->setCellValue('AN'.$i,$datafirst[3]->anion);

                $sheetsulfate->setCellValue('F'.$i,date('d-M-y',strtotime($item->save_date)));
                $sheetsulfate->setCellValue('G'.$i,slope::nhk_formatnumber($item->ic_sulphate, 5));
                $sheetsulfate->setCellValue('H'.$i,$item->lotnumber_lab);
                $sheetsulfate->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->sulphate, 5));
                $sheetsulfate->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[2]->sulphate, 5));
                $sheetsulfate->setCellValue('K'.$i,slope::nhk_formatnumber($datafirst[0]->sulphate, 5));
                $sheetsulfate->setCellValue('L'.$i,slope::nhk_formatnumber($specification->sulphate, 5));
                $sheetsulfate->setCellValue('M'.$i,$starttime);
                $sheetsulfate->setCellValue('N'.$i,$ftime);
                $sheetsulfate->setCellValue('Z'.$i,$datafirst[3]->sulphate);

                $sheetcation->setCellValue('F'.$i,date('d-M-y',strtotime($item->save_date)));
                $sheetcation->setCellValue('G'.$i,slope::nhk_formatnumber($item->ic_cation_up, 5));
                $sheetcation->setCellValue('H'.$i,$item->lotnumber_lab);
                $sheetcation->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->cation, 5));
                $sheetcation->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[2]->cation, 5));
                $sheetcation->setCellValue('K'.$i,slope::nhk_formatnumber($datafirst[0]->cation, 5));
                $sheetcation->setCellValue('L'.$i,slope::nhk_formatnumber($specification->cation, 5));
                $sheetcation->setCellValue('M'.$i,$starttime);
                $sheetcation->setCellValue('n'.$i,$starttime);
                $sheetcation->setCellValue('O'.$i,slope::nhk_formatnumber($item->ic_cation_sodium, 5));
                $sheetcation->setCellValue('P'.$i,slope::nhk_formatnumber($item->ic_cation_ammonium, 5));
                $sheetcation->setCellValue('Q'.$i,slope::nhk_formatnumber($item->ic_cation_potassium, 5));
                $sheetcation->setCellValue('R'.$i,slope::nhk_formatnumber($item->ic_cation_magnesium, 5));
                $sheetcation->setCellValue('S'.$i,slope::nhk_formatnumber($item->ic_cation_calcium, 5));
                $sheetcation->setCellValue('AN'.$i,$datafirst[3]->cation);
                $i++;
            }
            $i = 34;
            foreach ($datalabicdw as $item) { 
                $starttime = date('YmdHis',strtotime($item->save_date));
                $ftime = date('YmdHis',strtotime($item->ftime_ic));
                $stdate = date('Y-m-d', strtotime($item->save_date));
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$stdate)
                ->where('nt_model_history.date_end','>=',$stdate)
                ->get();
                if($datafirst->count() == 0)
                {
                $data1 = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                ->first();
                
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$data1->date_start)  
                ->where('nt_model_history.date_end','>=',$data1->date_start)
                ->get(); 
                }
                $sheetanion->setCellValue('W'.$i,date('d-M-y',strtotime($item->save_date)));
                $sheetanion->setCellValue('X'.$i,slope::nhk_formatnumber($item->ic_anion_up, 5));
                $sheetanion->setCellValue('Y'.$i,$item->lotnumber_lab);
                $sheetanion->setCellValue('Z'.$i,slope::nhk_formatnumber($datafirst[1]->anion, 5));
                $sheetanion->setCellValue('AA'.$i,slope::nhk_formatnumber($datafirst[2]->anion, 5));
                $sheetanion->setCellValue('AB'.$i,slope::nhk_formatnumber($datafirst[0]->anion, 5));
                $sheetanion->setCellValue('AC'.$i,slope::nhk_formatnumber($specification1->anion, 5));
                $sheetanion->setCellValue('AD'.$i,$starttime);
                $sheetanion->setCellValue('AE'.$i,$ftime);
                $sheetanion->setCellValue('AF'.$i,slope::nhk_formatnumber($item->ic_anion_fluoride, 5));
                $sheetanion->setCellValue('AG'.$i,slope::nhk_formatnumber($item->ic_anion_chloride, 5));
                $sheetanion->setCellValue('AH'.$i,slope::nhk_formatnumber($item->ic_anion_nitrile, 5));
                $sheetanion->setCellValue('AI'.$i,slope::nhk_formatnumber($item->ic_anion_bromid, 5));
                $sheetanion->setCellValue('AJ'.$i,slope::nhk_formatnumber($item->ic_anion_nitrate, 5));
                $sheetanion->setCellValue('AK'.$i,slope::nhk_formatnumber($item->ic_anion_sulphate, 5));
                $sheetanion->setCellValue('AL'.$i,slope::nhk_formatnumber($item->ic_anion_phosphate, 5));
                $sheetanion->setCellValue('AO'.$i,$datafirst[3]->anion);

                $sheetsulfate->setCellValue('P'.$i,date('d-M-y',strtotime($item->save_date)));
                $sheetsulfate->setCellValue('Q'.$i,slope::nhk_formatnumber($item->ic_sulphate, 5));
                $sheetsulfate->setCellValue('R'.$i,$item->lotnumber_lab);
                $sheetsulfate->setCellValue('S'.$i,slope::nhk_formatnumber($datafirst[1]->sulphate, 5));
                $sheetsulfate->setCellValue('T'.$i,slope::nhk_formatnumber($datafirst[2]->sulphate, 5));
                $sheetsulfate->setCellValue('U'.$i,slope::nhk_formatnumber($datafirst[0]->sulphate, 5));
                $sheetsulfate->setCellValue('V'.$i,slope::nhk_formatnumber($specification1->sulphate, 5));
                $sheetsulfate->setCellValue('W'.$i,$starttime);
                $sheetsulfate->setCellValue('X'.$i,$ftime);
                $sheetsulfate->setCellValue('AA'.$i,$datafirst[3]->sulphate);


                $sheetcation->setCellValue('W'.$i,date('d-M-y',strtotime($item->save_date)));
                $sheetcation->setCellValue('X'.$i,slope::nhk_formatnumber($item->ic_sulphate, 5));
                $sheetcation->setCellValue('Y'.$i,$item->lotnumber_lab);
                $sheetcation->setCellValue('Z'.$i,slope::nhk_formatnumber($datafirst[1]->cation, 5));
                $sheetcation->setCellValue('AA'.$i,slope::nhk_formatnumber($datafirst[2]->cation, 5));
                $sheetcation->setCellValue('AB'.$i,slope::nhk_formatnumber($datafirst[0]->cation, 5));
                $sheetcation->setCellValue('AC'.$i,slope::nhk_formatnumber($specification1->cation, 5));
                $sheetcation->setCellValue('AD'.$i,$starttime);
                $sheetcation->setCellValue('AE'.$i,$starttime);
                $sheetcation->setCellValue('AF'.$i,slope::nhk_formatnumber($item->ic_cation_sodium, 5));
                $sheetcation->setCellValue('AG'.$i,slope::nhk_formatnumber($item->ic_cation_ammonium, 5));
                $sheetcation->setCellValue('AH'.$i,slope::nhk_formatnumber($item->ic_cation_potassium, 5));
                $sheetcation->setCellValue('AI'.$i,slope::nhk_formatnumber($item->ic_cation_magnesium, 5));
                $sheetcation->setCellValue('AJ'.$i,slope::nhk_formatnumber($item->ic_cation_calcium, 5));
                $sheetcation->setCellValue('AO'.$i,$datafirst[3]->cation);
                $i++;
            }
            $i = 34;
            foreach ($datalabgcms as $item) {     
                $starttime = date('YmdHis',strtotime($item->save_date));
                $ftime = date('YmdHis',strtotime($item->ftime_gcms));
                $stdate = date('Y-m-d', strtotime($item->save_date));
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$stdate)
                ->where('nt_model_history.date_end','>=',$stdate)
                ->get();
                if($datafirst->count() == 0)
                {
                $data1 = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                ->first();
                
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$data1->date_start)  
                ->where('nt_model_history.date_end','>=',$data1->date_start)
                ->get(); 
                }
                $sheetog->setCellValue('F'.$i,date('d-M-y',strtotime($item->save_date)));
                $sheetog->setCellValue('G'.$i,slope::nhk_formatnumber($item->gcms_outgassing, 5));
                $sheetog->setCellValue('H'.$i,$item->lotnumber_lab);
                $sheetog->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->outgassing, 5));
                $sheetog->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[2]->outgassing, 5));
                $sheetog->setCellValue('K'.$i,slope::nhk_formatnumber($datafirst[0]->outgassing, 5));
                $sheetog->setCellValue('L'.$i,slope::nhk_formatnumber($specification->outgassing, 5));
                $sheetog->setCellValue('M'.$i,$starttime);
                $sheetog->setCellValue('N'.$i,$ftime);
                $sheetog->setCellValue('O'.$i,slope::nhk_formatnumber($item->c1, 5));
                $sheetog->setCellValue('P'.$i,slope::nhk_formatnumber($item->c2, 5));
                $sheetog->setCellValue('Q'.$i,slope::nhk_formatnumber($item->c3, 5));
                $sheetog->setCellValue('R'.$i,slope::nhk_formatnumber($item->c4, 5));
                $sheetog->setCellValue('S'.$i,slope::nhk_formatnumber($item->c5, 5));
                $sheetog->setCellValue('T'.$i,slope::nhk_formatnumber($item->c5_1, 5));
                $sheetog->setCellValue('U'.$i,slope::nhk_formatnumber($item->c5_2, 5));
                $sheetog->setCellValue('V'.$i,slope::nhk_formatnumber($item->c5_3, 5));
                $sheetog->setCellValue('W'.$i,slope::nhk_formatnumber($item->c6, 5));
                $sheetog->setCellValue('X'.$i,slope::nhk_formatnumber($item->c6_1, 5));
                $sheetog->setCellValue('Y'.$i,slope::nhk_formatnumber($item->c6_2, 5));
                $sheetog->setCellValue('Z'.$i,slope::nhk_formatnumber($item->c7, 5));
                $sheetog->setCellValue('AA'.$i,slope::nhk_formatnumber($item->c8, 5));
                $sheetog->setCellValue('AB'.$i,slope::nhk_formatnumber($item->c8_1, 5));
                $sheetog->setCellValue('AC'.$i,slope::nhk_formatnumber($item->c8_2, 5));
                $sheetog->setCellValue('AD'.$i,slope::nhk_formatnumber($item->c9, 5));
                $sheetog->setCellValue('AE'.$i,slope::nhk_formatnumber($item->c9_1, 5));
                $sheetog->setCellValue('AF'.$i,slope::nhk_formatnumber($item->c9_2, 5));
                $sheetog->setCellValue('AG'.$i,slope::nhk_formatnumber($item->c10, 5));
                $sheetog->setCellValue('AH'.$i,slope::nhk_formatnumber($item->c11, 5));
                $sheetog->setCellValue('AI'.$i,slope::nhk_formatnumber($item->c11_1, 5));
                $sheetog->setCellValue('AJ'.$i,slope::nhk_formatnumber($item->c11_2, 5));
                $sheetog->setCellValue('AK'.$i,slope::nhk_formatnumber($item->c12, 5));
                $sheetog->setCellValue('AL'.$i,slope::nhk_formatnumber($item->c13, 5));
                $sheetog->setCellValue('AM'.$i,slope::nhk_formatnumber($item->c14, 5));
                $sheetog->setCellValue('AN'.$i,slope::nhk_formatnumber($item->c15, 5));
                $sheetog->setCellValue('AO'.$i,slope::nhk_formatnumber($item->c15_1, 5));
                $sheetog->setCellValue('AP'.$i,slope::nhk_formatnumber($item->c5_2, 5));
                $sheetog->setCellValue('AQ'.$i,slope::nhk_formatnumber($item->c16, 5));
                $sheetog->setCellValue('AR'.$i,slope::nhk_formatnumber($item->c17, 5));
                $sheetog->setCellValue('AS'.$i,slope::nhk_formatnumber($item->c18, 5));
                $sheetog->setCellValue('AT'.$i,slope::nhk_formatnumber($item->c19, 5));
                $sheetog->setCellValue('AU'.$i,slope::nhk_formatnumber($item->c19_1, 5));
                $sheetog->setCellValue('AV'.$i,slope::nhk_formatnumber($item->c19_2, 5));
                $sheetog->setCellValue('AW'.$i,slope::nhk_formatnumber($item->c19_3, 5));
                $sheetog->setCellValue('AX'.$i,slope::nhk_formatnumber($item->c20, 5));
                $sheetog->setCellValue('AY'.$i,slope::nhk_formatnumber($item->c20_1, 5));
                $sheetog->setCellValue('AZ'.$i,slope::nhk_formatnumber($item->c21, 5));
                $sheetog->setCellValue('BA'.$i,slope::nhk_formatnumber($item->c21_1, 5));
                $sheetog->setCellValue('BB'.$i,slope::nhk_formatnumber($item->c22, 5));
                $sheetog->setCellValue('BC'.$i,slope::nhk_formatnumber($item->c22_1, 5));
                $sheetog->setCellValue('BD'.$i,slope::nhk_formatnumber($item->c22_2, 5));
                $sheetog->setCellValue('BE'.$i,slope::nhk_formatnumber($item->c23, 5));
                $sheetog->setCellValue('BF'.$i,slope::nhk_formatnumber($item->c23_1, 5));
                $sheetog->setCellValue('BG'.$i,slope::nhk_formatnumber($item->c24, 5));
                $sheetog->setCellValue('BH'.$i,slope::nhk_formatnumber($item->c24_1, 5));
                $sheetog->setCellValue('BI'.$i,slope::nhk_formatnumber($item->c24_2, 5));
                $sheetog->setCellValue('BJ'.$i,slope::nhk_formatnumber($item->c25, 5));
                $sheetog->setCellValue('BK'.$i,slope::nhk_formatnumber($item->c26, 5));
                $sheetog->setCellValue('BL'.$i,slope::nhk_formatnumber($item->c27, 5));
                $sheetog->setCellValue('BM'.$i,slope::nhk_formatnumber($item->c28, 5));
                $sheetog->setCellValue('BN'.$i,slope::nhk_formatnumber($item->c29, 5));
                $sheetog->setCellValue('BO'.$i,slope::nhk_formatnumber($item->c30, 5));
                $sheetog->setCellValue('BP'.$i,slope::nhk_formatnumber($item->c31, 5));
                $sheetog->setCellValue('BQ'.$i,slope::nhk_formatnumber($item->c32, 5));
                $sheetog->setCellValue('BR'.$i,slope::nhk_formatnumber($item->c33, 5));
                $sheetog->setCellValue('EH'.$i,$datafirst[3]->outgassing);

                $sheetlm->setCellValue('F'.$i,date('d-M-y',strtotime($item->save_date)));
                $sheetlm->setCellValue('G'.$i,slope::nhk_formatnumber($item->gcms_low_mw, 0));
                $sheetlm->setCellValue('H'.$i,$item->lotnumber_lab);
                $sheetlm->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->low_MW, 0));
                $sheetlm->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[2]->low_MW, 0));
                $sheetlm->setCellValue('K'.$i,slope::nhk_formatnumber($datafirst[0]->low_MW, 0));
                $sheetlm->setCellValue('L'.$i,slope::nhk_formatnumber($specification->low_MW, 0));
                $sheetlm->setCellValue('M'.$i,$starttime);
                $sheetlm->setCellValue('N'.$i,$ftime);
                $sheetlm->setCellValue('Z'.$i,$datafirst[3]->low_MW);

                $sheethm->setCellValue('F'.$i,date('d-M-y',strtotime($item->save_date)));
                $sheethm->setCellValue('G'.$i,slope::nhk_formatnumber($item->gcms_high_mw, 0));
                $sheethm->setCellValue('H'.$i,$item->lotnumber_lab);
                $sheethm->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->high_MW, 0));
                $sheethm->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[2]->high_MW, 0));
                $sheethm->setCellValue('K'.$i,slope::nhk_formatnumber($datafirst[0]->high_MW, 0));
                $sheethm->setCellValue('L'.$i,slope::nhk_formatnumber($specification->high_MW, 0));
                $sheethm->setCellValue('M'.$i,$starttime);
                $sheethm->setCellValue('N'.$i,$ftime);
                $sheethm->setCellValue('Z'.$i,$datafirst[3]->high_MW);
        
                $sheetsm->setCellValue('F'.$i,date('d-M-y',strtotime($item->save_date)));
                $sheetsm->setCellValue('G'.$i,slope::nhk_formatnumber($item->gcms_smear, 0));
                $sheetsm->setCellValue('H'.$i,$item->lotnumber_lab);
                $sheetsm->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->alkylacrylate, 0));
                $sheetsm->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[2]->alkylacrylate, 0));
                $sheetsm->setCellValue('K'.$i,slope::nhk_formatnumber($datafirst[0]->alkylacrylate, 0));
                $sheetsm->setCellValue('L'.$i,slope::nhk_formatnumber($specification->alkylacrylate, 0));
                $sheetsm->setCellValue('M'.$i,$starttime);
                $sheetsm->setCellValue('N'.$i,$ftime);
                $sheetsm->setCellValue('Z'.$i,$datafirst[3]->alkylacrylate);
                $i++;
            }
            $i = 34;
            foreach ($datalabgcmsdw as $item) { 
                $starttime = date('YmdHis',strtotime($item->save_date));
                $ftime = date('YmdHis',strtotime($item->ftime_gcms));
                $stdate = date('Y-m-d', strtotime($item->save_date));
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$stdate)
                ->where('nt_model_history.date_end','>=',$stdate)
                ->get();
                if($datafirst->count() == 0)
                {
                $data1 = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                ->first();
                
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$data1->date_start)  
                ->where('nt_model_history.date_end','>=',$data1->date_start)
                ->get(); 
                }
                $sheetog->setCellValue('BT'.$i,date('d-M-y',strtotime($item->save_date)));
                $sheetog->setCellValue('BU'.$i,slope::nhk_formatnumber($item->gcms_outgassing, 0));
                $sheetog->setCellValue('BV'.$i,$item->lotnumber_lab);
                $sheetog->setCellValue('BW'.$i,slope::nhk_formatnumber($datafirst[1]->outgassing, 0));
                $sheetog->setCellValue('BX'.$i,slope::nhk_formatnumber($datafirst[2]->outgassing, 0));
                $sheetog->setCellValue('BY'.$i,slope::nhk_formatnumber($datafirst[0]->outgassing, 0));
                $sheetog->setCellValue('BZ'.$i,slope::nhk_formatnumber($specification1->outgassing, 0));
                $sheetog->setCellValue('CA'.$i,$starttime);
                $sheetog->setCellValue('CB'.$i,$ftime);
                $sheetog->setCellValue('CC'.$i,slope::nhk_formatnumber($item->c1, 0));
                $sheetog->setCellValue('CD'.$i,slope::nhk_formatnumber($item->c2, 0));
                $sheetog->setCellValue('CE'.$i,slope::nhk_formatnumber($item->c3, 0));
                $sheetog->setCellValue('CF'.$i,slope::nhk_formatnumber($item->c4, 0));
                $sheetog->setCellValue('CG'.$i,slope::nhk_formatnumber($item->c5, 0));
                $sheetog->setCellValue('CH'.$i,slope::nhk_formatnumber($item->c5_1, 0));
                $sheetog->setCellValue('CI'.$i,slope::nhk_formatnumber($item->c5_2, 0));
                $sheetog->setCellValue('CJ'.$i,slope::nhk_formatnumber($item->c5_3, 0));
                $sheetog->setCellValue('CK'.$i,slope::nhk_formatnumber($item->c6, 0));
                $sheetog->setCellValue('CL'.$i,slope::nhk_formatnumber($item->c6_1, 0));
                $sheetog->setCellValue('CM'.$i,slope::nhk_formatnumber($item->c6_2, 0));
                $sheetog->setCellValue('CN'.$i,slope::nhk_formatnumber($item->c7, 0));
                $sheetog->setCellValue('CO'.$i,slope::nhk_formatnumber($item->c8, 0));
                $sheetog->setCellValue('CP'.$i,slope::nhk_formatnumber($item->c8_1, 0));
                $sheetog->setCellValue('CQ'.$i,slope::nhk_formatnumber($item->c8_2, 0));
                $sheetog->setCellValue('CR'.$i,slope::nhk_formatnumber($item->c9, 0));
                $sheetog->setCellValue('CS'.$i,slope::nhk_formatnumber($item->c9_1, 0));
                $sheetog->setCellValue('CT'.$i,slope::nhk_formatnumber($item->c9_2, 0));
                $sheetog->setCellValue('CU'.$i,slope::nhk_formatnumber($item->c10, 0));
                $sheetog->setCellValue('CV'.$i,slope::nhk_formatnumber($item->c11, 0));
                $sheetog->setCellValue('CW'.$i,slope::nhk_formatnumber($item->c11_1, 0));
                $sheetog->setCellValue('CX'.$i,slope::nhk_formatnumber($item->c11_2, 0));
                $sheetog->setCellValue('CY'.$i,slope::nhk_formatnumber($item->c12, 0));
                $sheetog->setCellValue('CZ'.$i,slope::nhk_formatnumber($item->c13, 0));
                $sheetog->setCellValue('DA'.$i,slope::nhk_formatnumber($item->c14, 0));
                $sheetog->setCellValue('DB'.$i,slope::nhk_formatnumber($item->c15, 0));
                $sheetog->setCellValue('DC'.$i,slope::nhk_formatnumber($item->c15_1, 0));
                $sheetog->setCellValue('DD'.$i,slope::nhk_formatnumber($item->c5_2, 0));
                $sheetog->setCellValue('DE'.$i,slope::nhk_formatnumber($item->c16, 0));
                $sheetog->setCellValue('DF'.$i,slope::nhk_formatnumber($item->c17, 0));
                $sheetog->setCellValue('DG'.$i,slope::nhk_formatnumber($item->c18, 0));
                $sheetog->setCellValue('DH'.$i,slope::nhk_formatnumber($item->c19, 0));
                $sheetog->setCellValue('DI'.$i,slope::nhk_formatnumber($item->c19_1, 0));
                $sheetog->setCellValue('DJ'.$i,slope::nhk_formatnumber($item->c19_2, 0));
                $sheetog->setCellValue('DK'.$i,slope::nhk_formatnumber($item->c19_3, 0));
                $sheetog->setCellValue('DL'.$i,slope::nhk_formatnumber($item->c20, 0));
                $sheetog->setCellValue('DM'.$i,slope::nhk_formatnumber($item->c20_1, 0));
                $sheetog->setCellValue('DN'.$i,slope::nhk_formatnumber($item->c21, 0));
                $sheetog->setCellValue('DO'.$i,slope::nhk_formatnumber($item->c21_1, 0));
                $sheetog->setCellValue('DP'.$i,slope::nhk_formatnumber($item->c22, 0));
                $sheetog->setCellValue('DQ'.$i,slope::nhk_formatnumber($item->c22_1, 0));
                $sheetog->setCellValue('DR'.$i,slope::nhk_formatnumber($item->c22_2, 0));
                $sheetog->setCellValue('DS'.$i,slope::nhk_formatnumber($item->c23, 0));
                $sheetog->setCellValue('DT'.$i,slope::nhk_formatnumber($item->c23_1, 0));
                $sheetog->setCellValue('DU'.$i,slope::nhk_formatnumber($item->c24, 0));
                $sheetog->setCellValue('DV'.$i,slope::nhk_formatnumber($item->c24_1, 0));
                $sheetog->setCellValue('DW'.$i,slope::nhk_formatnumber($item->c24_2, 0));
                $sheetog->setCellValue('DX'.$i,slope::nhk_formatnumber($item->c25, 0));
                $sheetog->setCellValue('DY'.$i,slope::nhk_formatnumber($item->c26, 0));
                $sheetog->setCellValue('DZ'.$i,slope::nhk_formatnumber($item->c27, 0));
                $sheetog->setCellValue('EA'.$i,slope::nhk_formatnumber($item->c28, 0));
                $sheetog->setCellValue('EB'.$i,slope::nhk_formatnumber($item->c29, 0));
                $sheetog->setCellValue('EC'.$i,slope::nhk_formatnumber($item->c30, 0));
                $sheetog->setCellValue('ED'.$i,slope::nhk_formatnumber($item->c31, 0));
                $sheetog->setCellValue('EE'.$i,slope::nhk_formatnumber($item->c32, 0));
                $sheetog->setCellValue('EF'.$i,slope::nhk_formatnumber($item->c33, 0));
                $sheetog->setCellValue('EI'.$i,$datafirst[3]->outgassing);

                $sheetlm->setCellValue('P'.$i,date('d-M-y',strtotime($item->save_date)));
                $sheetlm->setCellValue('Q'.$i,slope::nhk_formatnumber($item->gcms_low_mw, 0));
                $sheetlm->setCellValue('R'.$i,$item->lotnumber_lab);
                $sheetlm->setCellValue('S'.$i,slope::nhk_formatnumber($datafirst[1]->low_MW, 0));
                $sheetlm->setCellValue('T'.$i,slope::nhk_formatnumber($datafirst[2]->low_MW, 0));
                $sheetlm->setCellValue('U'.$i,slope::nhk_formatnumber($datafirst[0]->low_MW, 0));
                $sheetlm->setCellValue('V'.$i,slope::nhk_formatnumber($specification1->low_MW, 0));
                $sheetlm->setCellValue('W'.$i,$starttime);
                $sheetlm->setCellValue('X'.$i,$ftime);
                $sheetlm->setCellValue('AA'.$i,$datafirst[3]->low_MW);

                $sheethm->setCellValue('P'.$i,date('d-M-y',strtotime($item->save_date)));
                $sheethm->setCellValue('Q'.$i,slope::nhk_formatnumber($item->gcms_high_mw, 0));
                $sheethm->setCellValue('R'.$i,$item->lotnumber_lab);
                $sheethm->setCellValue('S'.$i,slope::nhk_formatnumber($datafirst[1]->high_MW, 0));
                $sheethm->setCellValue('T'.$i,slope::nhk_formatnumber($datafirst[2]->high_MW, 0));
                $sheethm->setCellValue('U'.$i,slope::nhk_formatnumber($datafirst[0]->high_MW, 0));
                $sheethm->setCellValue('V'.$i,slope::nhk_formatnumber($specification1->high_MW, 0));
                $sheethm->setCellValue('W'.$i,$starttime);
                $sheethm->setCellValue('X'.$i,$ftime);
                $sheethm->setCellValue('AA'.$i,$datafirst[3]->high_MW);

                $sheetsm->setCellValue('P'.$i,date('d-M-y',strtotime($item->save_date)));
                $sheetsm->setCellValue('Q'.$i,slope::nhk_formatnumber($item->gcms_smear, 0));
                $sheetsm->setCellValue('R'.$i,$item->lotnumber_lab);
                $sheetsm->setCellValue('S'.$i,slope::nhk_formatnumber($datafirst[1]->alkylacrylate, 0));
                $sheetsm->setCellValue('T'.$i,slope::nhk_formatnumber($datafirst[2]->alkylacrylate, 0));
                $sheetsm->setCellValue('U'.$i,slope::nhk_formatnumber($datafirst[0]->alkylacrylate, 0));
                $sheetsm->setCellValue('V'.$i,slope::nhk_formatnumber($specification1->alkylacrylate, 0));
                $sheetsm->setCellValue('W'.$i,$starttime);
                $sheetsm->setCellValue('X'.$i,$ftime);
                $sheetsm->setCellValue('AA'.$i,$datafirst[3]->alkylacrylate);
                $i++;
            }
            $i = 34;
            foreach ($datalabftir as $item) {     
                $starttime = date('YmdHis',strtotime($item->save_date));
                $ftime = date('YmdHis',strtotime($item->ftime_ftir));
                $stdate = date('Y-m-d', strtotime($item->save_date));
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$stdate)
                ->where('nt_model_history.date_end','>=',$stdate)
                ->get();
                if($datafirst->count() == 0)
                {
                $data1 = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                ->first();
                
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$data1->date_start)  
                ->where('nt_model_history.date_end','>=',$data1->date_start)
                ->get(); 
                }
                $sheetftir->setCellValue('F'.$i,date('d-M-y',strtotime($item->save_date)));
                $sheetftir->setCellValue('G'.$i,slope::nhk_formatnumber($item->ftir_silicone, 1));
                $sheetftir->setCellValue('H'.$i,$item->lotnumber_lab);
                $sheetftir->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->ftir, 1));
                $sheetftir->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[2]->ftir, 1));
                $sheetftir->setCellValue('K'.$i,slope::nhk_formatnumber($datafirst[0]->ftir, 1));
                $sheetftir->setCellValue('L'.$i,slope::nhk_formatnumber($specification->ftir, 1));
                $sheetftir->setCellValue('M'.$i,$starttime);
                $sheetftir->setCellValue('N'.$i,$ftime);
                $sheetftir->setCellValue('Z'.$i,$datafirst[3]->ftir);
                $i++;
            }
            $i = 34;
            foreach ($datalabftirdw as $item) {     
                $starttime = date('YmdHis',strtotime($item->save_date));
                $ftime = date('YmdHis',strtotime($item->ftime_ftir));
                $stdate = date('Y-m-d', strtotime($item->save_date));
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$stdate)
                ->where('nt_model_history.date_end','>=',$stdate)
                ->get();
                if($datafirst->count() == 0)
                {
                $data1 = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                ->first();
                
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$data1->date_start)  
                ->where('nt_model_history.date_end','>=',$data1->date_start)
                ->get(); 
                }
                $sheetftir->setCellValue('P'.$i,date('d-M-y',strtotime($item->save_date)));
                $sheetftir->setCellValue('Q'.$i,slope::nhk_formatnumber($item->ftir_silicone, 1));
                $sheetftir->setCellValue('R'.$i,$item->lotnumber_lab);
                $sheetftir->setCellValue('S'.$i,slope::nhk_formatnumber($datafirst[1]->ftir, 1));
                $sheetftir->setCellValue('T'.$i,slope::nhk_formatnumber($datafirst[2]->ftir, 1));
                $sheetftir->setCellValue('U'.$i,slope::nhk_formatnumber($datafirst[0]->ftir, 1));
                $sheetftir->setCellValue('V'.$i,slope::nhk_formatnumber($specification1->ftir, 1));
                $sheetftir->setCellValue('W'.$i,$starttime);
                $sheetftir->setCellValue('X'.$i,$ftime);
                $sheetftir->setCellValue('AA'.$i,$datafirst[3]->ftir);
                $i++;
            }
        })->setFilename($namefile)->store('xlsx', storage_path('NHK-File/report/graphexcel'));
        $chk = 1; 
        return response()->json(['success' =>  $chk,'filename' => $namefile.'.xlsx' ]);
    }

    public function customerexportexcel(Request $request)	
    {

        ini_set('memory_limit', '-1');
            $chkexport = [];
            if(isset($request->id))
            {
                if(count($request->id) == 2)
                {
                $a1 = DB::table('nt_model')->where('id_model', $request->id[0])->first();
                $a2 = DB::table('nt_model')->where('id_model', $request->id[1])->first();
                $text1 = $a1->name_model;
                $text2 = $a2->name_model;
                $chktext1 = mb_substr($text1,0,-3);
                $chktext2 = mb_substr($text2,0,-3);
                    if($chktext1 == $chktext2)
                    {
                        if($a1->part == "UP")
                        {
                            $model1 = $a1;
                        }
                        else
                        {
                            $model1 = $a2;
                        }
                        if($a2->part == "DN")
                        {
                            $model2 = $a2;
                        }
                        else
                        {
                            $model2 = $a1;
                        }
                        $dateend1 = date('Y-m-d 00:00:00', strtotime($request->input('dateend'.$model1->id_model). ' + 1 day'));
                        $dateend2 = date('Y-m-d 00:00:00', strtotime($request->input('dateend'.$model2->id_model). ' + 1 day'));
                        $datalablpc = DB::table('nt_data_lpc')
                        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_lpc.id_createlab')
                        ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                        ->where('nt_oqa_lab.save_date','>=',$request->input('datestart'.$model1->id_model))
                        ->where('nt_oqa_lab.save_date','<=',$dateend1)
                        ->where('nt_oqa_lab.id_model',$model1->id_model)
                        ->where('nt_oqa_lab.lpc_confirm_st',1)
                        ->orderBy('nt_oqa_lab.save_date', 'asc')->get();

                        $datalablpcdw = DB::table('nt_data_lpc')
                        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_lpc.id_createlab')
                        ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                        ->where('nt_oqa_lab.save_date','>=',$request->input('datestart'.$model2->id_model))
                        ->where('nt_oqa_lab.save_date','<=',$dateend2)
                        ->where('nt_oqa_lab.id_model',$model2->id_model)
                        ->where('nt_oqa_lab.lpc_confirm_st',1)
                        ->orderBy('nt_oqa_lab.save_date', 'asc')->get();
                    
                        $datalabsem = DB::table('nt_data_sem')
                        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                        ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                        ->where('nt_oqa_lab.save_date','>=',$request->input('datestart'.$model1->id_model))
                        ->where('nt_oqa_lab.save_date','<=',$dateend1)
                        ->where('nt_oqa_lab.id_model',$model1->id_model)
                        ->where('nt_oqa_lab.sem_confirm_st',1)
                        ->orderBy('nt_oqa_lab.save_date', 'asc')->get();
                        $datalabsemdw = DB::table('nt_data_sem')
                        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                        ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                        ->where('nt_oqa_lab.save_date','>=',$request->input('datestart'.$model1->id_model))
                        ->where('nt_oqa_lab.save_date','<=',$dateend2)
                        ->where('nt_oqa_lab.id_model',$model2->id_model)
                        ->where('nt_oqa_lab.sem_confirm_st',1)
                        ->orderBy('nt_oqa_lab.save_date', 'asc')->get();
            
                        $datalabic = DB::table('nt_data_ic')
                        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ic.id_createlab')
                        ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                        ->where('nt_oqa_lab.save_date','>=',$request->input('datestart'.$model1->id_model))
                        ->where('nt_oqa_lab.save_date','<=',$dateend1)
                        ->where('nt_oqa_lab.id_model',$model1->id_model)
                        ->where('nt_oqa_lab.ic_confirm_st',1)
                        ->orderBy('nt_oqa_lab.save_date', 'asc')->get();
                        

                        $datalabicdw = DB::table('nt_data_ic')
                        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ic.id_createlab')
                        ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                        ->where('nt_oqa_lab.save_date','>=',$request->input('datestart'.$model2->id_model))
                        ->where('nt_oqa_lab.save_date','<=',$dateend2)
                        ->where('nt_oqa_lab.id_model',$model2->id_model)
                        ->where('nt_oqa_lab.ic_confirm_st',1)
                        ->orderBy('nt_oqa_lab.save_date', 'asc')->get();
                    
                        $datalabgcms = DB::table('nt_data_gcms')
                        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
                        ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                        ->leftjoin('nt_outgassing_gcms','nt_outgassing_gcms.id_createlab','=','nt_oqa_lab.id_createlab')
                        ->where('nt_oqa_lab.save_date','>=',$request->input('datestart'.$model1->id_model))
                        ->where('nt_oqa_lab.save_date','<=',$dateend1)
                        ->where('nt_oqa_lab.id_model',$model1->id_model)
                        ->where('nt_oqa_lab.gcms_confirm_st',1)
                        ->orderBy('nt_oqa_lab.save_date', 'asc')->get();
                        $datalabgcmsdw = DB::table('nt_data_gcms')
                        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
                        ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                        ->leftjoin('nt_outgassing_gcms','nt_outgassing_gcms.id_createlab','=','nt_oqa_lab.id_createlab')
                        ->where('nt_oqa_lab.save_date','>=',$request->input('datestart'.$model2->id_model))
                        ->where('nt_oqa_lab.save_date','<=',$dateend2)
                        ->where('nt_oqa_lab.id_model',$model2->id_model)
                        ->where('nt_oqa_lab.gcms_confirm_st',1)
                        ->orderBy('nt_oqa_lab.save_date', 'asc')->get();
                        
                        $datalabftir = DB::table('nt_data_ftir')
                        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ftir.id_createlab')
                        ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                        ->where('nt_oqa_lab.save_date','>=',$request->input('datestart'.$model1->id_model))
                        ->where('nt_oqa_lab.save_date','<=',$dateend1)
                        ->where('nt_oqa_lab.id_model',$model1->id_model)
                        ->where('nt_oqa_lab.ftir_confirm_st',1)
                        ->orderBy('nt_oqa_lab.save_date', 'asc')->get();
                        $datalabftirdw = DB::table('nt_data_ftir')
                        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ftir.id_createlab')
                        ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                        ->where('nt_oqa_lab.save_date','>=',$request->input('datestart'.$model2->id_model))
                        ->where('nt_oqa_lab.save_date','<=',$dateend2)
                        ->where('nt_oqa_lab.id_model',$model2->id_model)
                        ->where('nt_oqa_lab.ftir_confirm_st',1)
                        ->orderBy('nt_oqa_lab.save_date', 'asc')->get();


                    }
                    else
                    {
                    return response()->json(['success' => 2]);
                    }
                }
                else
                {
                return response()->json(['success' => 3]);
                }
            }
            else
            {
            return response()->json(['success' => 4]);
            }
        
        
            $specification = DB::table('nt_model_spec')->where('id_model', $model1->id_model)->where('id_model_type', 1)->first();
            $triggerlimit = DB::table('nt_model_spec')->where('id_model', $model1->id_model)->where('id_model_type', 2)->first();
            $ucl = DB::table('nt_model_spec')->where('id_model', $model1->id_model)->where('id_model_type', 3)->first();
            $lcl = DB::table('nt_model_spec')->where('id_model', $model1->id_model)->where('id_model_type', 4)->first();
            $cl = DB::table('nt_model_spec')->where('id_model', $model1->id_model)->where('id_model_type', 5)->first();

            $specification1 = DB::table('nt_model_spec')->where('id_model', $model2->id_model)->where('id_model_type', 1)->first();
            $triggerlimit1 = DB::table('nt_model_spec')->where('id_model', $model2->id_model)->where('id_model_type', 2)->first();
            $ucl1 = DB::table('nt_model_spec')->where('id_model', $model2->id_model)->where('id_model_type', 3)->first();
            $lcl1 = DB::table('nt_model_spec')->where('id_model', $model2->id_model)->where('id_model_type', 4)->first();
            $cl1 = DB::table('nt_model_spec')->where('id_model', $model2->id_model)->where('id_model_type', 5)->first();

            $file = storage_path('NHK-File/template/report/Report_Excel_file.xlsx');
            $filetime = date('YmdHis');
            //$namefile = 'graphexcel'.$filetime;
            $spacename = str_replace(' ', '', $chktext1);
            $namefile = 'Excel_Customer_'.$spacename.'_'.date("YmdHis");
        
            Excel::load($file, function ($doc)use($specification,$triggerlimit,$ucl,$lcl,$cl,$cl1,$specification1,$triggerlimit1,$ucl1,$lcl1,$datalablpc,$datalabsem,$datalabic,$datalabgcms,$datalabftir,$datalablpcdw,$datalabsemdw,$datalabicdw,$datalabgcmsdw,$datalabftirdw) {
            $sheet = $doc->setActiveSheetIndex(0);
            $sheetlpc = $doc->setActiveSheetIndex(1);
            $sheetclass1 = $doc->setActiveSheetIndex(2);
            $sheetclass2 = $doc->setActiveSheetIndex(3);
            $sheetclass3 = $doc->setActiveSheetIndex(4);
            $sheetclass3_1 = $doc->setActiveSheetIndex(5);
            $sheetclass3_2 = $doc->setActiveSheetIndex(6);
            $sheetclass3_3 = $doc->setActiveSheetIndex(7);
            $sheetclass4 = $doc->setActiveSheetIndex(8);
            $sheetclass5 = $doc->setActiveSheetIndex(9);
            $sheetclass5_1 = $doc->setActiveSheetIndex(10);
            $sheetclass6 = $doc->setActiveSheetIndex(11);
            $sheetanion = $doc->setActiveSheetIndex(12);
            $sheetsulfate = $doc->setActiveSheetIndex(13);
            $sheetcation = $doc->setActiveSheetIndex(14);
            $sheetog = $doc->setActiveSheetIndex(15);
            $sheetlm = $doc->setActiveSheetIndex(16);
            $sheethm = $doc->setActiveSheetIndex(17);
            $sheetsm = $doc->setActiveSheetIndex(18);
            $sheetftir = $doc->setActiveSheetIndex(19);
            $i = 34;
            
            foreach ($datalablpc as $item) {     
            $starttime = date('YmdHis',strtotime($item->save_date));
            $ftime = date('YmdHis',strtotime($item->ftime_lpc));
            $stdate = date('Y-m-d', strtotime($item->save_date));
            
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$stdate)
                ->where('nt_model_history.date_end','>=',$stdate)
                ->get();
                if($datafirst->count() == 0)
                {
                $data1 = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                ->first();
                
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$data1->date_start)  
                ->where('nt_model_history.date_end','>=',$data1->date_start)
                ->get(); 
                }
                
            $sheetlpc->setCellValue('F'.$i,date('d-M-y',strtotime($item->save_date)));
            $sheetlpc->setCellValue('G'.$i,slope::nhk_formatnumber($item->lpc1, 0));
            $sheetlpc->setCellValue('H'.$i,slope::nhk_formatnumber($item->lpc2, 0));
            $sheetlpc->setCellValue('I'.$i,slope::nhk_formatnumber($item->lpc3, 0));
            $sheetlpc->setCellValue('J'.$i,slope::nhk_formatnumber($item->lpc_data, 0));
            $sheetlpc->setCellValue('K'.$i,$item->lotnumber_lab);
            $sheetlpc->setCellValue('L'.$i,slope::nhk_formatnumber($datafirst[1]->alpc, 0));
            $sheetlpc->setCellValue('M'.$i,slope::nhk_formatnumber($datafirst[2]->alpc, 0));
            $sheetlpc->setCellValue('N'.$i,slope::nhk_formatnumber($datafirst[0]->alpc, 0));
            $sheetlpc->setCellValue('O'.$i,slope::nhk_formatnumber($specification->alpc, 0));
            $sheetlpc->setCellValue('P'.$i,$starttime);
            $sheetlpc->setCellValue('Q'.$i,$ftime);
            $sheetlpc->setCellValue('AF'.$i,$datafirst[3]->alpc);
            
            $i++;
            }
            $i = 34;
            
            foreach ($datalablpcdw as $item) {     
                $starttime = date('YmdHis',strtotime($item->save_date));
                $ftime = date('YmdHis',strtotime($item->ftime_lpc));
                $stdate = date('Y-m-d', strtotime($item->save_date));
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$stdate)
                ->where('nt_model_history.date_end','>=',$stdate)
                ->get();
                if($datafirst->count() == 0)
                {
                $data1 = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                ->first();
                
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$data1->date_start)  
                ->where('nt_model_history.date_end','>=',$data1->date_start)
                ->get(); 
                }
                $sheetlpc->setCellValue('S'.$i,date('d-M-y',strtotime($item->save_date)));
                $sheetlpc->setCellValue('T'.$i,slope::nhk_formatnumber($item->lpc1, 0));
                $sheetlpc->setCellValue('U'.$i,slope::nhk_formatnumber($item->lpc2, 0));
                $sheetlpc->setCellValue('V'.$i,slope::nhk_formatnumber($item->lpc3, 0));
                $sheetlpc->setCellValue('W'.$i,slope::nhk_formatnumber($item->lpc_data, 0));
                $sheetlpc->setCellValue('X'.$i,$item->lotnumber_lab);
                $sheetlpc->setCellValue('Y'.$i,slope::nhk_formatnumber($datafirst[1]->alpc, 0));
                $sheetlpc->setCellValue('Z'.$i,slope::nhk_formatnumber($datafirst[2]->alpc, 0));
                $sheetlpc->setCellValue('AA'.$i,slope::nhk_formatnumber($datafirst[0]->alpc, 0));
                $sheetlpc->setCellValue('AB'.$i,slope::nhk_formatnumber($specification1->alpc, 0));
                $sheetlpc->setCellValue('AC'.$i,$starttime);
                $sheetlpc->setCellValue('AD'.$i,$ftime);
                $sheetlpc->setCellValue('AG'.$i,$datafirst[3]->alpc);
                $i++;
            }
            
            $i = 34;
            foreach ($datalabsem as $item) {     
                $starttime = date('YmdHis',strtotime($item->save_date));
                $ftime = date('YmdHis',strtotime($item->ftime_sem));
                $stdate = date('Y-m-d', strtotime($item->save_date));
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$stdate)
                ->where('nt_model_history.date_end','>=',$stdate)
                ->get();
                if($datafirst->count() == 0)
                {
                $data1 = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                ->first();
                
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$data1->date_start)  
                ->where('nt_model_history.date_end','>=',$data1->date_start)
                ->get(); 
                }
                $sheetclass1->setCellValue('F'.$i,date('d-M-y',strtotime($item->save_date)));
                $sheetclass1->setCellValue('G'.$i,slope::nhk_formatnumber($item->sem_class1, 1));
                $sheetclass1->setCellValue('H'.$i,$item->lotnumber_lab);
                $sheetclass1->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->class1, 1));
                $sheetclass1->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[2]->class1, 1));
                $sheetclass1->setCellValue('K'.$i,slope::nhk_formatnumber($datafirst[0]->class1, 1));
                $sheetclass1->setCellValue('L'.$i,slope::nhk_formatnumber($specification->class1, 1));
                $sheetclass1->setCellValue('M'.$i,$starttime);
                $sheetclass1->setCellValue('N'.$i,$ftime);
                $sheetclass1->setCellValue('Z'.$i,$datafirst[3]->class1);

                $sheetclass2->setCellValue('F'.$i,date('d-M-y',strtotime($item->save_date)));
                $sheetclass2->setCellValue('G'.$i,slope::nhk_formatnumber($item->sem_class2, 1));
                $sheetclass2->setCellValue('H'.$i,$item->lotnumber_lab);
                $sheetclass2->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->class2, 1));
                $sheetclass2->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[2]->class2, 1));
                $sheetclass2->setCellValue('K'.$i,slope::nhk_formatnumber($datafirst[0]->class2, 1));
                $sheetclass2->setCellValue('L'.$i,slope::nhk_formatnumber($specification->class2, 1));
                $sheetclass2->setCellValue('M'.$i,$starttime);
                $sheetclass2->setCellValue('N'.$i,$ftime);
                $sheetclass2->setCellValue('Z'.$i,$datafirst[3]->class2);

                $sheetclass3->setCellValue('F'.$i,date('d-M-y',strtotime($item->save_date)));
                $sheetclass3->setCellValue('G'.$i,slope::nhk_formatnumber($item->sem_class3, 1));
                $sheetclass3->setCellValue('H'.$i,$item->lotnumber_lab);
                $sheetclass3->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->class3, 1));
                $sheetclass3->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[2]->class3, 1));
                $sheetclass3->setCellValue('K'.$i,slope::nhk_formatnumber($datafirst[0]->class3, 1));
                $sheetclass3->setCellValue('L'.$i,slope::nhk_formatnumber($specification->class3, 1));
                $sheetclass3->setCellValue('M'.$i,$starttime);
                $sheetclass3->setCellValue('N'.$i,$ftime);
                $sheetclass3->setCellValue('Z'.$i,$datafirst[3]->class3);

                $sheetclass3_1->setCellValue('F'.$i,date('d-M-y',strtotime($item->save_date)));
                $sheetclass3_1->setCellValue('G'.$i,slope::nhk_formatnumber($item->sem_class3_1, 1));
                $sheetclass3_1->setCellValue('H'.$i,$item->lotnumber_lab);
                $sheetclass3_1->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->class3_1, 1));
                $sheetclass3_1->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[2]->class3_1, 1));
                $sheetclass3_1->setCellValue('K'.$i,slope::nhk_formatnumber($datafirst[0]->class3_1, 1));
                $sheetclass3_1->setCellValue('L'.$i,slope::nhk_formatnumber($specification->class3_1, 1));
                $sheetclass3_1->setCellValue('M'.$i,$starttime);
                $sheetclass3_1->setCellValue('N'.$i,$ftime);
                $sheetclass3_1->setCellValue('Z'.$i,$datafirst[3]->class3_1);

                $sheetclass3_2->setCellValue('F'.$i,date('d-M-y',strtotime($item->save_date)));
                $sheetclass3_2->setCellValue('G'.$i,slope::nhk_formatnumber($item->sem_class3_2, 1));
                $sheetclass3_2->setCellValue('H'.$i,$item->lotnumber_lab);
                $sheetclass3_2->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->class3_2, 1));
                $sheetclass3_2->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[2]->class3_2, 1));
                $sheetclass3_2->setCellValue('K'.$i,slope::nhk_formatnumber($datafirst[0]->class3_2, 1));
                $sheetclass3_2->setCellValue('L'.$i,slope::nhk_formatnumber($specification->class3_2, 1));
                $sheetclass3_2->setCellValue('M'.$i,$starttime);
                $sheetclass3_2->setCellValue('N'.$i,$ftime);
                $sheetclass3_2->setCellValue('Z'.$i,$datafirst[3]->class3_2);

                $sheetclass3_3->setCellValue('F'.$i,date('d-M-y',strtotime($item->save_date)));
                $sheetclass3_3->setCellValue('G'.$i,slope::nhk_formatnumber($item->sem_class3_3, 1));
                $sheetclass3_3->setCellValue('H'.$i,$item->lotnumber_lab);
                $sheetclass3_3->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->class3_3, 1));
                $sheetclass3_3->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[2]->class3_3, 1));
                $sheetclass3_3->setCellValue('K'.$i,slope::nhk_formatnumber($datafirst[0]->class3_3, 1));
                $sheetclass3_3->setCellValue('L'.$i,slope::nhk_formatnumber($specification->class3_3, 1));
                $sheetclass3_3->setCellValue('M'.$i,$starttime);
                $sheetclass3_3->setCellValue('N'.$i,$ftime);
                $sheetclass3_3->setCellValue('Z'.$i,$datafirst[3]->class3_3);

                $sheetclass4->setCellValue('F'.$i,date('d-M-y',strtotime($item->save_date)));
                $sheetclass4->setCellValue('G'.$i,slope::nhk_formatnumber($item->sem_class4, 1));
                $sheetclass4->setCellValue('H'.$i,$item->lotnumber_lab);
                $sheetclass4->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->class4, 1));
                $sheetclass4->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[2]->class4, 1));
                $sheetclass4->setCellValue('K'.$i,slope::nhk_formatnumber($datafirst[0]->class4, 1));
                $sheetclass4->setCellValue('L'.$i,slope::nhk_formatnumber($specification->class4, 1));
                $sheetclass4->setCellValue('M'.$i,$starttime);
                $sheetclass4->setCellValue('N'.$i,$ftime);
                $sheetclass4->setCellValue('Z'.$i,$datafirst[3]->class4);

                $sheetclass5->setCellValue('F'.$i,date('d-M-y',strtotime($item->save_date)));
                $sheetclass5->setCellValue('G'.$i,slope::nhk_formatnumber($item->sem_class5, 1));
                $sheetclass5->setCellValue('H'.$i,$item->lotnumber_lab);
                $sheetclass5->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->class5, 1));
                $sheetclass5->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[2]->class5, 1));
                $sheetclass5->setCellValue('K'.$i,slope::nhk_formatnumber($datafirst[0]->class5, 1));
                $sheetclass5->setCellValue('L'.$i,slope::nhk_formatnumber($specification->class5, 1));
                $sheetclass5->setCellValue('M'.$i,$starttime);
                $sheetclass5->setCellValue('N'.$i,$ftime);
                $sheetclass5->setCellValue('Z'.$i,$datafirst[3]->class5);

                $sheetclass5_1->setCellValue('F'.$i,date('d-M-y',strtotime($item->save_date)));
                $sheetclass5_1->setCellValue('G'.$i,slope::nhk_formatnumber($item->sem_class5_1, 1));
                $sheetclass5_1->setCellValue('H'.$i,$item->lotnumber_lab);
                $sheetclass5_1->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->class5_1, 1));
                $sheetclass5_1->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[2]->class5_1, 1));
                $sheetclass5_1->setCellValue('K'.$i,slope::nhk_formatnumber($datafirst[0]->class5_1, 1));
                $sheetclass5_1->setCellValue('L'.$i,slope::nhk_formatnumber($specification->class5_1, 1));
                $sheetclass5_1->setCellValue('M'.$i,$starttime);
                $sheetclass5_1->setCellValue('N'.$i,$ftime);
                $sheetclass5_1->setCellValue('Z'.$i,$datafirst[3]->class5_1);

                $sheetclass6->setCellValue('F'.$i,date('d-M-y',strtotime($item->save_date)));
                $sheetclass6->setCellValue('G'.$i,slope::nhk_formatnumber($item->sem_class6, 1));
                $sheetclass6->setCellValue('H'.$i,$item->lotnumber_lab);
                $sheetclass6->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->class6, 1));
                $sheetclass6->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[2]->class6, 1));
                $sheetclass6->setCellValue('K'.$i,slope::nhk_formatnumber($datafirst[0]->class6, 1));
                $sheetclass6->setCellValue('L'.$i,slope::nhk_formatnumber($specification->class6, 1));
                $sheetclass6->setCellValue('M'.$i,$starttime);
                $sheetclass6->setCellValue('N'.$i,$ftime);
                $sheetclass6->setCellValue('Z'.$i,$datafirst[3]->class6);

                $i++;
            }
            $i = 34;
            foreach ($datalabsemdw as $item) {     
                $starttime = date('YmdHis',strtotime($item->save_date));
                $ftime = date('YmdHis',strtotime($item->ftime_sem));
                $stdate = date('Y-m-d', strtotime($item->save_date));
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$stdate)
                ->where('nt_model_history.date_end','>=',$stdate)
                ->get();
                if($datafirst->count() == 0)
                {
                $data1 = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                ->first();
                
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$data1->date_start)  
                ->where('nt_model_history.date_end','>=',$data1->date_start)
                ->get(); 
                }
                $sheetclass1->setCellValue('P'.$i,date('d-M-y',strtotime($item->save_date)));
                if($item->last_sem_class1 !== null){
                    $sheetclass1->setCellValue('Q'.$i,slope::nhk_formatnumber($item->last_sem_class1, 1));
                }else{
                    $sheetclass1->setCellValue('Q'.$i,slope::nhk_formatnumber($item->sem_class1, 1));
                }
                $sheetclass1->setCellValue('R'.$i,$item->lotnumber_lab);
                $sheetclass1->setCellValue('S'.$i,slope::nhk_formatnumber($datafirst[1]->class1, 1));
                $sheetclass1->setCellValue('T'.$i,slope::nhk_formatnumber($datafirst[2]->class1, 1));
                $sheetclass1->setCellValue('U'.$i,slope::nhk_formatnumber($datafirst[0]->class1, 1));
                $sheetclass1->setCellValue('V'.$i,slope::nhk_formatnumber($specification1->class1, 1));
                $sheetclass1->setCellValue('W'.$i,$starttime);
                $sheetclass1->setCellValue('X'.$i,$ftime);
                $sheetclass1->setCellValue('AA'.$i,$datafirst[3]->class1);

                $sheetclass2->setCellValue('P'.$i,date('d-M-y',strtotime($item->save_date)));
                if($item->last_sem_class2 !== null){
                    $sheetclass2->setCellValue('Q'.$i,slope::nhk_formatnumber($item->last_sem_class2, 1));
                }else{
                    $sheetclass2->setCellValue('Q'.$i,slope::nhk_formatnumber($item->sem_class2, 1));
                }
                $sheetclass2->setCellValue('R'.$i,$item->lotnumber_lab);
                $sheetclass2->setCellValue('S'.$i,slope::nhk_formatnumber($datafirst[1]->class2, 1));
                $sheetclass2->setCellValue('T'.$i,slope::nhk_formatnumber($datafirst[2]->class2, 1));
                $sheetclass2->setCellValue('U'.$i,slope::nhk_formatnumber($datafirst[0]->class2, 1));
                $sheetclass2->setCellValue('V'.$i,slope::nhk_formatnumber($specification1->class2, 1));
                $sheetclass2->setCellValue('W'.$i,$starttime);
                $sheetclass2->setCellValue('X'.$i,$ftime);
                $sheetclass2->setCellValue('AA'.$i,$datafirst[3]->class2);

                $sheetclass3->setCellValue('P'.$i,date('d-M-y',strtotime($item->save_date)));
                if($item->last_sem_class3!== null){
                    $sheetclass3->setCellValue('Q'.$i,slope::nhk_formatnumber($item->last_sem_class3, 1));
                }else{
                    $sheetclass3->setCellValue('Q'.$i,slope::nhk_formatnumber($item->sem_class3, 1));
                }

                $sheetclass3->setCellValue('R'.$i,$item->lotnumber_lab);
                $sheetclass3->setCellValue('S'.$i,slope::nhk_formatnumber($datafirst[1]->class3, 1));
                $sheetclass3->setCellValue('T'.$i,slope::nhk_formatnumber($datafirst[2]->class3, 1));
                $sheetclass3->setCellValue('U'.$i,slope::nhk_formatnumber($datafirst[0]->class3, 1));
                $sheetclass3->setCellValue('V'.$i,slope::nhk_formatnumber($specification1->class3, 1));
                $sheetclass3->setCellValue('W'.$i,$starttime);
                $sheetclass3->setCellValue('X'.$i,$ftime);
                $sheetclass3->setCellValue('AA'.$i,$datafirst[3]->class3);

                $sheetclass3_1->setCellValue('P'.$i,date('d-M-y',strtotime($item->save_date)));
                if($item->last_sem_class3_1 !== null){
                    $sheetclass3_1->setCellValue('Q'.$i,slope::nhk_formatnumber($item->last_sem_class3_1, 1));
                }else{
                    $sheetclass3_1->setCellValue('Q'.$i,slope::nhk_formatnumber($item->sem_class3_1, 1));
                }

                $sheetclass3_1->setCellValue('R'.$i,$item->lotnumber_lab);
                $sheetclass3_1->setCellValue('S'.$i,slope::nhk_formatnumber($datafirst[1]->class3_1, 1));
                $sheetclass3_1->setCellValue('T'.$i,slope::nhk_formatnumber($datafirst[2]->class3_1, 1));
                $sheetclass3_1->setCellValue('U'.$i,slope::nhk_formatnumber($datafirst[0]->class3_1, 1));
                $sheetclass3_1->setCellValue('V'.$i,slope::nhk_formatnumber($specification1->class3_1, 1));
                $sheetclass3_1->setCellValue('W'.$i,$starttime);
                $sheetclass3_1->setCellValue('X'.$i,$ftime);
                $sheetclass3_1->setCellValue('AA'.$i,$datafirst[3]->class3_1);

                $sheetclass3_2->setCellValue('P'.$i,date('d-M-y',strtotime($item->save_date)));
                if($item->last_sem_class3_2 !== null){
                    $sheetclass3_2->setCellValue('Q'.$i,slope::nhk_formatnumber($item->last_sem_class3_2, 1));
                }else{
                    $sheetclass3_2->setCellValue('Q'.$i,slope::nhk_formatnumber($item->sem_class3_2, 1));
                }
                $sheetclass3_2->setCellValue('R'.$i,$item->lotnumber_lab);
                $sheetclass3_2->setCellValue('S'.$i,slope::nhk_formatnumber($datafirst[1]->class3_2, 1));
                $sheetclass3_2->setCellValue('T'.$i,slope::nhk_formatnumber($datafirst[2]->class3_2, 1));
                $sheetclass3_2->setCellValue('U'.$i,slope::nhk_formatnumber($datafirst[0]->class3_2, 1));
                $sheetclass3_2->setCellValue('V'.$i,slope::nhk_formatnumber($specification1->class3_2, 1));
                $sheetclass3_2->setCellValue('W'.$i,$starttime);
                $sheetclass3_2->setCellValue('X'.$i,$ftime);
                $sheetclass3_2->setCellValue('AA'.$i,$datafirst[3]->class3_2);

                $sheetclass3_3->setCellValue('P'.$i,date('d-M-y',strtotime($item->save_date)));
                if($item->last_sem_class3_3 !== null){
                    $sheetclass3_3->setCellValue('Q'.$i,slope::nhk_formatnumber($item->last_sem_class3_3, 1));
                }else{
                    $sheetclass3_3->setCellValue('Q'.$i,slope::nhk_formatnumber($item->sem_class3_3, 1));
                }
                $sheetclass3_3->setCellValue('R'.$i,$item->lotnumber_lab);
                $sheetclass3_3->setCellValue('S'.$i,slope::nhk_formatnumber($datafirst[1]->class3_3, 1));
                $sheetclass3_3->setCellValue('T'.$i,slope::nhk_formatnumber($datafirst[2]->class3_3, 1));
                $sheetclass3_3->setCellValue('U'.$i,slope::nhk_formatnumber($datafirst[0]->class3_3, 1));
                $sheetclass3_3->setCellValue('V'.$i,slope::nhk_formatnumber($specification1->class3_3, 1));
                $sheetclass3_3->setCellValue('W'.$i,$starttime);
                $sheetclass3_3->setCellValue('X'.$i,$ftime);
                $sheetclass3_3->setCellValue('AA'.$i,$datafirst[3]->class3_3);

                $sheetclass4->setCellValue('P'.$i,date('d-M-y',strtotime($item->save_date)));
                if($item->last_sem_class4 !== null){
                    $sheetclass4->setCellValue('Q'.$i,slope::nhk_formatnumber($item->last_sem_class4, 1));
                }else{
                    $sheetclass4->setCellValue('Q'.$i,slope::nhk_formatnumber($item->sem_class4, 1));
                }
                $sheetclass4->setCellValue('R'.$i,$item->lotnumber_lab);
                $sheetclass4->setCellValue('S'.$i,slope::nhk_formatnumber($datafirst[1]->class4, 1));
                $sheetclass4->setCellValue('T'.$i,slope::nhk_formatnumber($datafirst[2]->class4, 1));
                $sheetclass4->setCellValue('U'.$i,slope::nhk_formatnumber($datafirst[0]->class4, 1));
                $sheetclass4->setCellValue('V'.$i,slope::nhk_formatnumber($specification1->class4, 1));
                $sheetclass4->setCellValue('W'.$i,$starttime);
                $sheetclass4->setCellValue('X'.$i,$ftime);
                $sheetclass4->setCellValue('AA'.$i,$datafirst[3]->class4);

                $sheetclass5->setCellValue('P'.$i,date('d-M-y',strtotime($item->save_date)));
                if($item->last_sem_class5 !== null){
                    $sheetclass5->setCellValue('Q'.$i,slope::nhk_formatnumber($item->last_sem_class5, 1));
                }else{
                    $sheetclass5->setCellValue('Q'.$i,slope::nhk_formatnumber($item->sem_class5, 1));
                }
                $sheetclass5->setCellValue('R'.$i,$item->lotnumber_lab);
                $sheetclass5->setCellValue('S'.$i,slope::nhk_formatnumber($datafirst[1]->class5, 1));
                $sheetclass5->setCellValue('T'.$i,slope::nhk_formatnumber($datafirst[2]->class5, 1));
                $sheetclass5->setCellValue('U'.$i,slope::nhk_formatnumber($datafirst[0]->class5, 1));
                $sheetclass5->setCellValue('V'.$i,slope::nhk_formatnumber($specification1->class5, 1));
                $sheetclass5->setCellValue('W'.$i,$starttime);
                $sheetclass5->setCellValue('X'.$i,$ftime);
                $sheetclass5->setCellValue('AA'.$i,$datafirst[3]->class5);

                $sheetclass5_1->setCellValue('P'.$i,date('d-M-y',strtotime($item->save_date)));
                if($item->last_sem_class5_1 !== null){
                    $sheetclass5_1->setCellValue('Q'.$i,slope::nhk_formatnumber($item->last_sem_class5_1, 1));
                }else{
                    $sheetclass5_1->setCellValue('Q'.$i,slope::nhk_formatnumber($item->sem_class5_1, 1));
                }
                $sheetclass5_1->setCellValue('R'.$i,$item->lotnumber_lab);
                $sheetclass5_1->setCellValue('S'.$i,slope::nhk_formatnumber($datafirst[1]->class5_1, 1));
                $sheetclass5_1->setCellValue('T'.$i,slope::nhk_formatnumber($datafirst[2]->class5_1, 1));
                $sheetclass5_1->setCellValue('U'.$i,slope::nhk_formatnumber($datafirst[0]->class5_1, 1));
                $sheetclass5_1->setCellValue('V'.$i,slope::nhk_formatnumber($specification1->class5_1, 1));
                $sheetclass5_1->setCellValue('W'.$i,$starttime);
                $sheetclass5_1->setCellValue('X'.$i,$ftime);
                $sheetclass5_1->setCellValue('AA'.$i,$datafirst[3]->class5_1);

                $sheetclass6->setCellValue('P'.$i,date('d-M-y',strtotime($item->save_date)));
                if($item->last_sem_class6 !== null){
                    $sheetclass6->setCellValue('Q'.$i,slope::nhk_formatnumber($item->last_sem_class6, 1));
                }else{
                    $sheetclass6->setCellValue('Q'.$i,slope::nhk_formatnumber($item->sem_class6, 1));
                }
                $sheetclass6->setCellValue('R'.$i,$item->lotnumber_lab);
                $sheetclass6->setCellValue('S'.$i,slope::nhk_formatnumber($datafirst[1]->class6, 1));
                $sheetclass6->setCellValue('T'.$i,slope::nhk_formatnumber($datafirst[2]->class6, 1));
                $sheetclass6->setCellValue('U'.$i,slope::nhk_formatnumber($datafirst[0]->class6, 1));
                $sheetclass6->setCellValue('V'.$i,slope::nhk_formatnumber($specification1->class6, 1));
                $sheetclass6->setCellValue('W'.$i,$starttime);
                $sheetclass6->setCellValue('X'.$i,$ftime);
                $sheetclass6->setCellValue('AA'.$i,$datafirst[3]->class6);

                $i++;
            }
            $i = 34;
            foreach ($datalabic as $item) {     
                $starttime = date('YmdHis',strtotime($item->save_date));
                $ftime = date('YmdHis',strtotime($item->ftime_ic));
                $stdate = date('Y-m-d', strtotime($item->save_date));
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$stdate)
                ->where('nt_model_history.date_end','>=',$stdate)
                ->get();
                if($datafirst->count() == 0)
                {
                $data1 = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                ->first();
                
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$data1->date_start)  
                ->where('nt_model_history.date_end','>=',$data1->date_start)
                ->get(); 
                }
                $sheetanion->setCellValue('F'.$i,date('d-M-y',strtotime($item->save_date)));
                $sheetanion->setCellValue('G'.$i,slope::nhk_formatnumber($item->ic_anion_up, 5));
                $sheetanion->setCellValue('H'.$i,$item->lotnumber_lab);
                $sheetanion->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->anion, 5));
                $sheetanion->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[2]->anion, 5));
                $sheetanion->setCellValue('K'.$i,slope::nhk_formatnumber($datafirst[0]->anion, 5));
                $sheetanion->setCellValue('L'.$i,slope::nhk_formatnumber($specification->anion, 5));
                $sheetanion->setCellValue('M'.$i,$starttime);
                $sheetanion->setCellValue('N'.$i,$ftime);
                $sheetanion->setCellValue('O'.$i,slope::nhk_formatnumber($item->ic_anion_fluoride, 5));
                $sheetanion->setCellValue('P'.$i,slope::nhk_formatnumber($item->ic_anion_chloride, 5));
                $sheetanion->setCellValue('Q'.$i,slope::nhk_formatnumber($item->ic_anion_nitrile, 5));
                $sheetanion->setCellValue('R'.$i,slope::nhk_formatnumber($item->ic_anion_bromid, 5));
                $sheetanion->setCellValue('S'.$i,slope::nhk_formatnumber($item->ic_anion_nitrate, 5));
                $sheetanion->setCellValue('T'.$i,slope::nhk_formatnumber($item->ic_anion_sulphate, 5));
                $sheetanion->setCellValue('U'.$i,slope::nhk_formatnumber($item->ic_anion_phosphate, 5));
                $sheetanion->setCellValue('AN'.$i,$datafirst[3]->anion);

                $sheetsulfate->setCellValue('F'.$i,date('d-M-y',strtotime($item->save_date)));
                $sheetsulfate->setCellValue('G'.$i,slope::nhk_formatnumber($item->ic_sulphate, 5));
                $sheetsulfate->setCellValue('H'.$i,$item->lotnumber_lab);
                $sheetsulfate->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->sulphate, 5));
                $sheetsulfate->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[2]->sulphate, 5));
                $sheetsulfate->setCellValue('K'.$i,slope::nhk_formatnumber($datafirst[0]->sulphate, 5));
                $sheetsulfate->setCellValue('L'.$i,slope::nhk_formatnumber($specification->sulphate, 5));
                $sheetsulfate->setCellValue('M'.$i,$starttime);
                $sheetsulfate->setCellValue('N'.$i,$ftime);
                $sheetsulfate->setCellValue('Z'.$i,$datafirst[3]->sulphate);

                $sheetcation->setCellValue('F'.$i,date('d-M-y',strtotime($item->save_date)));
                $sheetcation->setCellValue('G'.$i,slope::nhk_formatnumber($item->ic_cation_up, 5));
                $sheetcation->setCellValue('H'.$i,$item->lotnumber_lab);
                $sheetcation->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->cation, 5));
                $sheetcation->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[2]->cation, 5));
                $sheetcation->setCellValue('K'.$i,slope::nhk_formatnumber($datafirst[0]->cation, 5));
                $sheetcation->setCellValue('L'.$i,slope::nhk_formatnumber($specification->cation, 5));
                $sheetcation->setCellValue('M'.$i,$starttime);
                $sheetcation->setCellValue('n'.$i,$starttime);
                $sheetcation->setCellValue('O'.$i,slope::nhk_formatnumber($item->ic_cation_sodium, 5));
                $sheetcation->setCellValue('P'.$i,slope::nhk_formatnumber($item->ic_cation_ammonium, 5));
                $sheetcation->setCellValue('Q'.$i,slope::nhk_formatnumber($item->ic_cation_potassium, 5));
                $sheetcation->setCellValue('R'.$i,slope::nhk_formatnumber($item->ic_cation_magnesium, 5));
                $sheetcation->setCellValue('S'.$i,slope::nhk_formatnumber($item->ic_cation_calcium, 5));
                $sheetcation->setCellValue('AN'.$i,$datafirst[3]->cation);
                $i++;
            }
            $i = 34;
            foreach ($datalabicdw as $item) { 
                $starttime = date('YmdHis',strtotime($item->save_date));
                $ftime = date('YmdHis',strtotime($item->ftime_ic));
                $stdate = date('Y-m-d', strtotime($item->save_date));
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$stdate)
                ->where('nt_model_history.date_end','>=',$stdate)
                ->get();
                if($datafirst->count() == 0)
                {
                $data1 = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                ->first();
                
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$data1->date_start)  
                ->where('nt_model_history.date_end','>=',$data1->date_start)
                ->get(); 
                }
                $sheetanion->setCellValue('W'.$i,date('d-M-y',strtotime($item->save_date)));
                $sheetanion->setCellValue('X'.$i,slope::nhk_formatnumber($item->ic_anion_up, 5));
                $sheetanion->setCellValue('Y'.$i,$item->lotnumber_lab);
                $sheetanion->setCellValue('Z'.$i,slope::nhk_formatnumber($datafirst[1]->anion, 5));
                $sheetanion->setCellValue('AA'.$i,slope::nhk_formatnumber($datafirst[2]->anion, 5));
                $sheetanion->setCellValue('AB'.$i,slope::nhk_formatnumber($datafirst[0]->anion, 5));
                $sheetanion->setCellValue('AC'.$i,slope::nhk_formatnumber($specification1->anion, 5));
                $sheetanion->setCellValue('AD'.$i,$starttime);
                $sheetanion->setCellValue('AE'.$i,$ftime);
                $sheetanion->setCellValue('AF'.$i,slope::nhk_formatnumber($item->ic_anion_fluoride, 5));
                $sheetanion->setCellValue('AG'.$i,slope::nhk_formatnumber($item->ic_anion_chloride, 5));
                $sheetanion->setCellValue('AH'.$i,slope::nhk_formatnumber($item->ic_anion_nitrile, 5));
                $sheetanion->setCellValue('AI'.$i,slope::nhk_formatnumber($item->ic_anion_bromid, 5));
                $sheetanion->setCellValue('AJ'.$i,slope::nhk_formatnumber($item->ic_anion_nitrate, 5));
                $sheetanion->setCellValue('AK'.$i,slope::nhk_formatnumber($item->ic_anion_sulphate, 5));
                $sheetanion->setCellValue('AL'.$i,slope::nhk_formatnumber($item->ic_anion_phosphate, 5));
                $sheetanion->setCellValue('AO'.$i,$datafirst[3]->anion);

                $sheetsulfate->setCellValue('P'.$i,date('d-M-y',strtotime($item->save_date)));
                $sheetsulfate->setCellValue('Q'.$i,slope::nhk_formatnumber($item->ic_sulphate, 5));
                $sheetsulfate->setCellValue('R'.$i,$item->lotnumber_lab);
                $sheetsulfate->setCellValue('S'.$i,slope::nhk_formatnumber($datafirst[1]->sulphate, 5));
                $sheetsulfate->setCellValue('T'.$i,slope::nhk_formatnumber($datafirst[2]->sulphate, 5));
                $sheetsulfate->setCellValue('U'.$i,slope::nhk_formatnumber($datafirst[0]->sulphate, 5));
                $sheetsulfate->setCellValue('V'.$i,slope::nhk_formatnumber($specification1->sulphate, 5));
                $sheetsulfate->setCellValue('W'.$i,$starttime);
                $sheetsulfate->setCellValue('X'.$i,$ftime);
                $sheetsulfate->setCellValue('AA'.$i,$datafirst[3]->sulphate);


                $sheetcation->setCellValue('W'.$i,date('d-M-y',strtotime($item->save_date)));
                $sheetcation->setCellValue('X'.$i,slope::nhk_formatnumber($item->ic_sulphate, 5));
                $sheetcation->setCellValue('Y'.$i,$item->lotnumber_lab);
                $sheetcation->setCellValue('Z'.$i,slope::nhk_formatnumber($datafirst[1]->cation, 5));
                $sheetcation->setCellValue('AA'.$i,slope::nhk_formatnumber($datafirst[2]->cation, 5));
                $sheetcation->setCellValue('AB'.$i,slope::nhk_formatnumber($datafirst[0]->cation, 5));
                $sheetcation->setCellValue('AC'.$i,slope::nhk_formatnumber($specification1->cation, 5));
                $sheetcation->setCellValue('AD'.$i,$starttime);
                $sheetcation->setCellValue('AE'.$i,$starttime);
                $sheetcation->setCellValue('AF'.$i,slope::nhk_formatnumber($item->ic_cation_sodium, 5));
                $sheetcation->setCellValue('AG'.$i,slope::nhk_formatnumber($item->ic_cation_ammonium, 5));
                $sheetcation->setCellValue('AH'.$i,slope::nhk_formatnumber($item->ic_cation_potassium, 5));
                $sheetcation->setCellValue('AI'.$i,slope::nhk_formatnumber($item->ic_cation_magnesium, 5));
                $sheetcation->setCellValue('AJ'.$i,slope::nhk_formatnumber($item->ic_cation_calcium, 5));
                $sheetcation->setCellValue('AO'.$i,$datafirst[3]->cation);
                $i++;
            }
            $i = 34;
            foreach ($datalabgcms as $item) {     
                $starttime = date('YmdHis',strtotime($item->save_date));
                $ftime = date('YmdHis',strtotime($item->ftime_gcms));
                $stdate = date('Y-m-d', strtotime($item->save_date));
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$stdate)
                ->where('nt_model_history.date_end','>=',$stdate)
                ->get();
                if($datafirst->count() == 0)
                {
                $data1 = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                ->first();
                
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$data1->date_start)  
                ->where('nt_model_history.date_end','>=',$data1->date_start)
                ->get(); 
                }
                $sheetog->setCellValue('F'.$i,date('d-M-y',strtotime($item->gcms_startime)));
                $sheetog->setCellValue('G'.$i,slope::nhk_formatnumber($item->gcms_outgassing, 5));
                $sheetog->setCellValue('H'.$i,$item->lotnumber_lab);
                $sheetog->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->outgassing, 5));
                $sheetog->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[2]->outgassing, 5));
                $sheetog->setCellValue('K'.$i,slope::nhk_formatnumber($datafirst[0]->outgassing, 5));
                $sheetog->setCellValue('L'.$i,slope::nhk_formatnumber($specification->outgassing, 5));
                $sheetog->setCellValue('M'.$i,$starttime);
                $sheetog->setCellValue('N'.$i,$ftime);
                $sheetog->setCellValue('O'.$i,slope::nhk_formatnumber($item->c1, 5));
                $sheetog->setCellValue('P'.$i,slope::nhk_formatnumber($item->c2, 5));
                $sheetog->setCellValue('Q'.$i,slope::nhk_formatnumber($item->c3, 5));
                $sheetog->setCellValue('R'.$i,slope::nhk_formatnumber($item->c4, 5));
                $sheetog->setCellValue('S'.$i,slope::nhk_formatnumber($item->c5, 5));
                $sheetog->setCellValue('T'.$i,slope::nhk_formatnumber($item->c5_1, 5));
                $sheetog->setCellValue('U'.$i,slope::nhk_formatnumber($item->c5_2, 5));
                $sheetog->setCellValue('V'.$i,slope::nhk_formatnumber($item->c5_3, 5));
                $sheetog->setCellValue('W'.$i,slope::nhk_formatnumber($item->c6, 5));
                $sheetog->setCellValue('X'.$i,slope::nhk_formatnumber($item->c6_1, 5));
                $sheetog->setCellValue('Y'.$i,slope::nhk_formatnumber($item->c6_2, 5));
                $sheetog->setCellValue('Z'.$i,slope::nhk_formatnumber($item->c7, 5));
                $sheetog->setCellValue('AA'.$i,slope::nhk_formatnumber($item->c8, 5));
                $sheetog->setCellValue('AB'.$i,slope::nhk_formatnumber($item->c8_1, 5));
                $sheetog->setCellValue('AC'.$i,slope::nhk_formatnumber($item->c8_2, 5));
                $sheetog->setCellValue('AD'.$i,slope::nhk_formatnumber($item->c9, 5));
                $sheetog->setCellValue('AE'.$i,slope::nhk_formatnumber($item->c9_1, 5));
                $sheetog->setCellValue('AF'.$i,slope::nhk_formatnumber($item->c9_2, 5));
                $sheetog->setCellValue('AG'.$i,slope::nhk_formatnumber($item->c10, 5));
                $sheetog->setCellValue('AH'.$i,slope::nhk_formatnumber($item->c11, 5));
                $sheetog->setCellValue('AI'.$i,slope::nhk_formatnumber($item->c11_1, 5));
                $sheetog->setCellValue('AJ'.$i,slope::nhk_formatnumber($item->c11_2, 5));
                $sheetog->setCellValue('AK'.$i,slope::nhk_formatnumber($item->c12, 5));
                $sheetog->setCellValue('AL'.$i,slope::nhk_formatnumber($item->c13, 5));
                $sheetog->setCellValue('AM'.$i,slope::nhk_formatnumber($item->c14, 5));
                $sheetog->setCellValue('AN'.$i,slope::nhk_formatnumber($item->c15, 5));
                $sheetog->setCellValue('AO'.$i,slope::nhk_formatnumber($item->c15_1, 5));
                $sheetog->setCellValue('AP'.$i,slope::nhk_formatnumber($item->c5_2, 5));
                $sheetog->setCellValue('AQ'.$i,slope::nhk_formatnumber($item->c16, 5));
                $sheetog->setCellValue('AR'.$i,slope::nhk_formatnumber($item->c17, 5));
                $sheetog->setCellValue('AS'.$i,slope::nhk_formatnumber($item->c18, 5));
                $sheetog->setCellValue('AT'.$i,slope::nhk_formatnumber($item->c19, 5));
                $sheetog->setCellValue('AU'.$i,slope::nhk_formatnumber($item->c19_1, 5));
                $sheetog->setCellValue('AV'.$i,slope::nhk_formatnumber($item->c19_2, 5));
                $sheetog->setCellValue('AW'.$i,slope::nhk_formatnumber($item->c19_3, 5));
                $sheetog->setCellValue('AX'.$i,slope::nhk_formatnumber($item->c20, 5));
                $sheetog->setCellValue('AY'.$i,slope::nhk_formatnumber($item->c20_1, 5));
                $sheetog->setCellValue('AZ'.$i,slope::nhk_formatnumber($item->c21, 5));
                $sheetog->setCellValue('BA'.$i,slope::nhk_formatnumber($item->c21_1, 5));
                $sheetog->setCellValue('BB'.$i,slope::nhk_formatnumber($item->c22, 5));
                $sheetog->setCellValue('BC'.$i,slope::nhk_formatnumber($item->c22_1, 5));
                $sheetog->setCellValue('BD'.$i,slope::nhk_formatnumber($item->c22_2, 5));
                $sheetog->setCellValue('BE'.$i,slope::nhk_formatnumber($item->c23, 5));
                $sheetog->setCellValue('BF'.$i,slope::nhk_formatnumber($item->c23_1, 5));
                $sheetog->setCellValue('BG'.$i,slope::nhk_formatnumber($item->c24, 5));
                $sheetog->setCellValue('BH'.$i,slope::nhk_formatnumber($item->c24_1, 5));
                $sheetog->setCellValue('BI'.$i,slope::nhk_formatnumber($item->c24_2, 5));
                $sheetog->setCellValue('BJ'.$i,slope::nhk_formatnumber($item->c25, 5));
                $sheetog->setCellValue('BK'.$i,slope::nhk_formatnumber($item->c26, 5));
                $sheetog->setCellValue('BL'.$i,slope::nhk_formatnumber($item->c27, 5));
                $sheetog->setCellValue('BM'.$i,slope::nhk_formatnumber($item->c28, 5));
                $sheetog->setCellValue('BN'.$i,slope::nhk_formatnumber($item->c29, 5));
                $sheetog->setCellValue('BO'.$i,slope::nhk_formatnumber($item->c30, 5));
                $sheetog->setCellValue('BP'.$i,slope::nhk_formatnumber($item->c31, 5));
                $sheetog->setCellValue('BQ'.$i,slope::nhk_formatnumber($item->c32, 5));
                $sheetog->setCellValue('BR'.$i,slope::nhk_formatnumber($item->c33, 5));
                $sheetog->setCellValue('EH'.$i,$datafirst[3]->outgassing);

                $sheetlm->setCellValue('F'.$i,date('d-M-y',strtotime($item->gcms_startime)));
                $sheetlm->setCellValue('G'.$i,slope::nhk_formatnumber($item->gcms_low_mw, 0));
                $sheetlm->setCellValue('H'.$i,$item->lotnumber_lab);
                $sheetlm->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->low_MW, 0));
                $sheetlm->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[2]->low_MW, 0));
                $sheetlm->setCellValue('K'.$i,slope::nhk_formatnumber($datafirst[0]->low_MW, 0));
                $sheetlm->setCellValue('L'.$i,slope::nhk_formatnumber($specification->low_MW, 0));
                $sheetlm->setCellValue('M'.$i,$starttime);
                $sheetlm->setCellValue('N'.$i,$ftime);
                $sheetlm->setCellValue('Z'.$i,$datafirst[3]->low_MW);

                $sheethm->setCellValue('F'.$i,date('d-M-y',strtotime($item->gcms_startime)));
                $sheethm->setCellValue('G'.$i,slope::nhk_formatnumber($item->gcms_high_mw, 0));
                $sheethm->setCellValue('H'.$i,$item->lotnumber_lab);
                $sheethm->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->high_MW, 0));
                $sheethm->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[2]->high_MW, 0));
                $sheethm->setCellValue('K'.$i,slope::nhk_formatnumber($datafirst[0]->high_MW, 0));
                $sheethm->setCellValue('L'.$i,slope::nhk_formatnumber($specification->high_MW, 0));
                $sheethm->setCellValue('M'.$i,$starttime);
                $sheethm->setCellValue('N'.$i,$ftime);
                $sheethm->setCellValue('Z'.$i,$datafirst[3]->high_MW);
        
                $sheetsm->setCellValue('F'.$i,date('d-M-y',strtotime($item->gcms_startime)));
                $sheetsm->setCellValue('G'.$i,slope::nhk_formatnumber($item->gcms_smear, 0));
                $sheetsm->setCellValue('H'.$i,$item->lotnumber_lab);
                $sheetsm->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->alkylacrylate, 0));
                $sheetsm->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[2]->alkylacrylate, 0));
                $sheetsm->setCellValue('K'.$i,slope::nhk_formatnumber($datafirst[0]->alkylacrylate, 0));
                $sheetsm->setCellValue('L'.$i,slope::nhk_formatnumber($specification->alkylacrylate, 0));
                $sheetsm->setCellValue('M'.$i,$starttime);
                $sheetsm->setCellValue('N'.$i,$ftime);
                $sheetsm->setCellValue('Z'.$i,$datafirst[3]->alkylacrylate);
                $i++;
            }
            $i = 34;
            foreach ($datalabgcmsdw as $item) { 
                $starttime = date('YmdHis',strtotime($item->save_date));
                $ftime = date('YmdHis',strtotime($item->ftime_gcms));
                $stdate = date('Y-m-d', strtotime($item->save_date));
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$stdate)
                ->where('nt_model_history.date_end','>=',$stdate)
                ->get();
                if($datafirst->count() == 0)
                {
                $data1 = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                ->first();
                
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$data1->date_start)  
                ->where('nt_model_history.date_end','>=',$data1->date_start)
                ->get(); 
                }
                $sheetog->setCellValue('BT'.$i,date('d-M-y',strtotime($item->gcms_startime)));
                $sheetog->setCellValue('BU'.$i,slope::nhk_formatnumber($item->gcms_outgassing, 0));
                $sheetog->setCellValue('BV'.$i,$item->lotnumber_lab);
                $sheetog->setCellValue('BW'.$i,slope::nhk_formatnumber($datafirst[1]->outgassing, 0));
                $sheetog->setCellValue('BX'.$i,slope::nhk_formatnumber($datafirst[2]->outgassing, 0));
                $sheetog->setCellValue('BY'.$i,slope::nhk_formatnumber($datafirst[0]->outgassing, 0));
                $sheetog->setCellValue('BZ'.$i,slope::nhk_formatnumber($specification1->outgassing, 0));
                $sheetog->setCellValue('CA'.$i,$starttime);
                $sheetog->setCellValue('CB'.$i,$ftime);
                $sheetog->setCellValue('CC'.$i,slope::nhk_formatnumber($item->c1, 0));
                $sheetog->setCellValue('CD'.$i,slope::nhk_formatnumber($item->c2, 0));
                $sheetog->setCellValue('CE'.$i,slope::nhk_formatnumber($item->c3, 0));
                $sheetog->setCellValue('CF'.$i,slope::nhk_formatnumber($item->c4, 0));
                $sheetog->setCellValue('CG'.$i,slope::nhk_formatnumber($item->c5, 0));
                $sheetog->setCellValue('CH'.$i,slope::nhk_formatnumber($item->c5_1, 0));
                $sheetog->setCellValue('CI'.$i,slope::nhk_formatnumber($item->c5_2, 0));
                $sheetog->setCellValue('CJ'.$i,slope::nhk_formatnumber($item->c5_3, 0));
                $sheetog->setCellValue('CK'.$i,slope::nhk_formatnumber($item->c6, 0));
                $sheetog->setCellValue('CL'.$i,slope::nhk_formatnumber($item->c6_1, 0));
                $sheetog->setCellValue('CM'.$i,slope::nhk_formatnumber($item->c6_2, 0));
                $sheetog->setCellValue('CN'.$i,slope::nhk_formatnumber($item->c7, 0));
                $sheetog->setCellValue('CO'.$i,slope::nhk_formatnumber($item->c8, 0));
                $sheetog->setCellValue('CP'.$i,slope::nhk_formatnumber($item->c8_1, 0));
                $sheetog->setCellValue('CQ'.$i,slope::nhk_formatnumber($item->c8_2, 0));
                $sheetog->setCellValue('CR'.$i,slope::nhk_formatnumber($item->c9, 0));
                $sheetog->setCellValue('CS'.$i,slope::nhk_formatnumber($item->c9_1, 0));
                $sheetog->setCellValue('CT'.$i,slope::nhk_formatnumber($item->c9_2, 0));
                $sheetog->setCellValue('CU'.$i,slope::nhk_formatnumber($item->c10, 0));
                $sheetog->setCellValue('CV'.$i,slope::nhk_formatnumber($item->c11, 0));
                $sheetog->setCellValue('CW'.$i,slope::nhk_formatnumber($item->c11_1, 0));
                $sheetog->setCellValue('CX'.$i,slope::nhk_formatnumber($item->c11_2, 0));
                $sheetog->setCellValue('CY'.$i,slope::nhk_formatnumber($item->c12, 0));
                $sheetog->setCellValue('CZ'.$i,slope::nhk_formatnumber($item->c13, 0));
                $sheetog->setCellValue('DA'.$i,slope::nhk_formatnumber($item->c14, 0));
                $sheetog->setCellValue('DB'.$i,slope::nhk_formatnumber($item->c15, 0));
                $sheetog->setCellValue('DC'.$i,slope::nhk_formatnumber($item->c15_1, 0));
                $sheetog->setCellValue('DD'.$i,slope::nhk_formatnumber($item->c5_2, 0));
                $sheetog->setCellValue('DE'.$i,slope::nhk_formatnumber($item->c16, 0));
                $sheetog->setCellValue('DF'.$i,slope::nhk_formatnumber($item->c17, 0));
                $sheetog->setCellValue('DG'.$i,slope::nhk_formatnumber($item->c18, 0));
                $sheetog->setCellValue('DH'.$i,slope::nhk_formatnumber($item->c19, 0));
                $sheetog->setCellValue('DI'.$i,slope::nhk_formatnumber($item->c19_1, 0));
                $sheetog->setCellValue('DJ'.$i,slope::nhk_formatnumber($item->c19_2, 0));
                $sheetog->setCellValue('DK'.$i,slope::nhk_formatnumber($item->c19_3, 0));
                $sheetog->setCellValue('DL'.$i,slope::nhk_formatnumber($item->c20, 0));
                $sheetog->setCellValue('DM'.$i,slope::nhk_formatnumber($item->c20_1, 0));
                $sheetog->setCellValue('DN'.$i,slope::nhk_formatnumber($item->c21, 0));
                $sheetog->setCellValue('DO'.$i,slope::nhk_formatnumber($item->c21_1, 0));
                $sheetog->setCellValue('DP'.$i,slope::nhk_formatnumber($item->c22, 0));
                $sheetog->setCellValue('DQ'.$i,slope::nhk_formatnumber($item->c22_1, 0));
                $sheetog->setCellValue('DR'.$i,slope::nhk_formatnumber($item->c22_2, 0));
                $sheetog->setCellValue('DS'.$i,slope::nhk_formatnumber($item->c23, 0));
                $sheetog->setCellValue('DT'.$i,slope::nhk_formatnumber($item->c23_1, 0));
                $sheetog->setCellValue('DU'.$i,slope::nhk_formatnumber($item->c24, 0));
                $sheetog->setCellValue('DV'.$i,slope::nhk_formatnumber($item->c24_1, 0));
                $sheetog->setCellValue('DW'.$i,slope::nhk_formatnumber($item->c24_2, 0));
                $sheetog->setCellValue('DX'.$i,slope::nhk_formatnumber($item->c25, 0));
                $sheetog->setCellValue('DY'.$i,slope::nhk_formatnumber($item->c26, 0));
                $sheetog->setCellValue('DZ'.$i,slope::nhk_formatnumber($item->c27, 0));
                $sheetog->setCellValue('EA'.$i,slope::nhk_formatnumber($item->c28, 0));
                $sheetog->setCellValue('EB'.$i,slope::nhk_formatnumber($item->c29, 0));
                $sheetog->setCellValue('EC'.$i,slope::nhk_formatnumber($item->c30, 0));
                $sheetog->setCellValue('ED'.$i,slope::nhk_formatnumber($item->c31, 0));
                $sheetog->setCellValue('EE'.$i,slope::nhk_formatnumber($item->c32, 0));
                $sheetog->setCellValue('EF'.$i,slope::nhk_formatnumber($item->c33, 0));
                $sheetog->setCellValue('EI'.$i,$datafirst[3]->outgassing);

                $sheetlm->setCellValue('P'.$i,date('d-M-y',strtotime($item->gcms_startime)));
                $sheetlm->setCellValue('Q'.$i,slope::nhk_formatnumber($item->gcms_low_mw, 0));
                $sheetlm->setCellValue('R'.$i,$item->lotnumber_lab);
                $sheetlm->setCellValue('S'.$i,slope::nhk_formatnumber($datafirst[1]->low_MW, 0));
                $sheetlm->setCellValue('T'.$i,slope::nhk_formatnumber($datafirst[2]->low_MW, 0));
                $sheetlm->setCellValue('U'.$i,slope::nhk_formatnumber($datafirst[0]->low_MW, 0));
                $sheetlm->setCellValue('V'.$i,slope::nhk_formatnumber($specification1->low_MW, 0));
                $sheetlm->setCellValue('W'.$i,$starttime);
                $sheetlm->setCellValue('X'.$i,$ftime);
                $sheetlm->setCellValue('AA'.$i,$datafirst[3]->low_MW);

                $sheethm->setCellValue('P'.$i,date('d-M-y',strtotime($item->gcms_startime)));
                $sheethm->setCellValue('Q'.$i,slope::nhk_formatnumber($item->gcms_high_mw, 0));
                $sheethm->setCellValue('R'.$i,$item->lotnumber_lab);
                $sheethm->setCellValue('S'.$i,slope::nhk_formatnumber($datafirst[1]->high_MW, 0));
                $sheethm->setCellValue('T'.$i,slope::nhk_formatnumber($datafirst[2]->high_MW, 0));
                $sheethm->setCellValue('U'.$i,slope::nhk_formatnumber($datafirst[0]->high_MW, 0));
                $sheethm->setCellValue('V'.$i,slope::nhk_formatnumber($specification1->high_MW, 0));
                $sheethm->setCellValue('W'.$i,$starttime);
                $sheethm->setCellValue('X'.$i,$ftime);
                $sheethm->setCellValue('AA'.$i,$datafirst[3]->high_MW);

                $sheetsm->setCellValue('P'.$i,date('d-M-y',strtotime($item->gcms_startime)));
                $sheetsm->setCellValue('Q'.$i,slope::nhk_formatnumber($item->gcms_smear, 0));
                $sheetsm->setCellValue('R'.$i,$item->lotnumber_lab);
                $sheetsm->setCellValue('S'.$i,slope::nhk_formatnumber($datafirst[1]->alkylacrylate, 0));
                $sheetsm->setCellValue('T'.$i,slope::nhk_formatnumber($datafirst[2]->alkylacrylate, 0));
                $sheetsm->setCellValue('U'.$i,slope::nhk_formatnumber($datafirst[0]->alkylacrylate, 0));
                $sheetsm->setCellValue('V'.$i,slope::nhk_formatnumber($specification1->alkylacrylate, 0));
                $sheetsm->setCellValue('W'.$i,$starttime);
                $sheetsm->setCellValue('X'.$i,$ftime);
                $sheetsm->setCellValue('AA'.$i,$datafirst[3]->alkylacrylate);
                $i++;
            }
            $i = 34;
            foreach ($datalabftir as $item) {     
                $starttime = date('YmdHis',strtotime($item->save_date));
                $ftime = date('YmdHis',strtotime($item->ftime_ftir));
                $stdate = date('Y-m-d', strtotime($item->save_date));
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$stdate)
                ->where('nt_model_history.date_end','>=',$stdate)
                ->get();
                if($datafirst->count() == 0)
                {
                $data1 = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                ->first();
                
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$data1->date_start)  
                ->where('nt_model_history.date_end','>=',$data1->date_start)
                ->get(); 
                }
                $sheetftir->setCellValue('F'.$i,date('d-M-y',strtotime($item->save_date)));
                $sheetftir->setCellValue('G'.$i,slope::nhk_formatnumber($item->ftir_silicone, 1));
                $sheetftir->setCellValue('H'.$i,$item->lotnumber_lab);
                $sheetftir->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->ftir, 1));
                $sheetftir->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[2]->ftir, 1));
                $sheetftir->setCellValue('K'.$i,slope::nhk_formatnumber($datafirst[0]->ftir, 1));
                $sheetftir->setCellValue('L'.$i,slope::nhk_formatnumber($specification->ftir, 1));
                $sheetftir->setCellValue('M'.$i,$starttime);
                $sheetftir->setCellValue('N'.$i,$ftime);
                $sheetftir->setCellValue('Z'.$i,$datafirst[3]->ftir);
                $i++;
            }
            $i = 34;
            foreach ($datalabftirdw as $item) {     
                $starttime = date('YmdHis',strtotime($item->save_date));
                $ftime = date('YmdHis',strtotime($item->ftime_ftir));
                $stdate = date('Y-m-d', strtotime($item->save_date));
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$stdate)
                ->where('nt_model_history.date_end','>=',$stdate)
                ->get();
                if($datafirst->count() == 0)
                {
                $data1 = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                ->first();
                
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$data1->date_start)  
                ->where('nt_model_history.date_end','>=',$data1->date_start)
                ->get(); 
                }
                $sheetftir->setCellValue('P'.$i,date('d-M-y',strtotime($item->save_date)));
                $sheetftir->setCellValue('Q'.$i,slope::nhk_formatnumber($item->ftir_silicone, 1));
                $sheetftir->setCellValue('R'.$i,$item->lotnumber_lab);
                $sheetftir->setCellValue('S'.$i,slope::nhk_formatnumber($datafirst[1]->ftir, 1));
                $sheetftir->setCellValue('T'.$i,slope::nhk_formatnumber($datafirst[2]->ftir, 1));
                $sheetftir->setCellValue('U'.$i,slope::nhk_formatnumber($datafirst[0]->ftir, 1));
                $sheetftir->setCellValue('V'.$i,slope::nhk_formatnumber($specification1->ftir, 1));
                $sheetftir->setCellValue('W'.$i,$starttime);
                $sheetftir->setCellValue('X'.$i,$ftime);
                $sheetftir->setCellValue('AA'.$i,$datafirst[3]->ftir);
                $i++;
            }
        })->setFilename($namefile)->store('xlsx', storage_path('NHK-File/report/graphexcel'));
        $chk = 1; 
        return response()->json(['success' =>  $chk,'filename' => $namefile.'.xlsx' ]);
    }

    public function customer_chkexportpdf(Request $request)	
    {   
        if(isset($request->id))
        {
            if(count($request->id) == 1 )
            {
             $chk = 1;
             $id = $request->id[0];
             
            }
            else
            {
                $chk = 2;
                $id = null;
            }
        }
        else
        {
            $chk = 3;
            $id = null;
        }
        return response()->json(['success' =>  $chk,'id'=>$id]);
    }
    
    public function customer_exportpdf(Request $request)	
    {  
        $row = array();
        $dataexport = [];
        $data = DB::table('nt_oqa_lab')
        ->join('nt_model','nt_model.id_model','=','nt_oqa_lab.id_model')
        ->where('nt_model.id_model', $request->id[0])->first();
        foreach ($request->id as $item) {        
            $datestart = $request->input('datestart'.$item);
            $dateend = $request->input('dateend'.$item);
            $lpccount = $request->input('lpccount'.$item);
            $semcount = $request->input('semcount'.$item);
            $iccount = $request->input('iccount'.$item);
            $gcmscount = $request->input('gcmscount'.$item);
            $ftircount = $request->input('ftircount'.$item);
            $newdata =  array (
                'id' => $item,
                'datestart' => $datestart,
                'dateend' => $dateend,
                'lpccount' => $lpccount,
                'semcount' => $semcount,
                'iccount' => $iccount,
                'gcmscount' => $gcmscount,
                'ftircount' => $ftircount,
            );
            array_push($dataexport,$newdata);
        }
        $date_expire  = $request->input('textdate');    
        $datedm = date("d-m", strtotime($date_expire));
        $datey =  date("Y", strtotime($date_expire));
        $dateexport = $datedm.' ,'.$datey; 
        if(empty($request->input('spec')))
        {
           $spec = 0;
        }
        else
        {
            $spec = 1;
        }
        if(empty($request->input('tl')))
        {
            $tl = 0;
        }
        else
        {
            $tl = 1;
        }

        if(empty($request->input('ucl')))
        {
            $ucl = 0;
        }
        else
        {
            $ucl = 1;
        }
        if(empty($request->input('lcl')))
        {
            $lcl = 0;
        }
        else
        {
            $lcl = 1;
        }
        if(empty($request->input('cl')))
        {
            $cl = 0;
        }
        else
        {
            $cl = 1;
        }
        if(empty($request->input('is')))
        {
            $is = 0;
        }
        else
        {
            $is = 1;
        }
        return view('output/customer_exportpdf', [
            'id' =>$data->id_model,
            'namemodel' =>$data->name_model,
            'datenow' =>date("YmdHis"),
            'idcreatelab' =>$data->id_createlab,
            'laball' =>$data,
            'textdate' =>$dateexport,
            'textname' =>$request->input('textname'),
            'textnamepproval' =>$request->input('textnamepproval'),
            'arrayexport' =>$dataexport,
            'spec' =>$spec,
            'tl' =>$tl,
            'ucl' =>$ucl,
            'lcl' =>$lcl,
            'cl' =>$cl,
            'is' =>$is,
            'dataexport' => json_encode($dataexport)
        ]);
    } 

    public function raw_chkexportpdf(Request $request)	
    {   
      
        if(isset($request->id))
        {
            if(count($request->id) == 1 )
            {
            $chk = 1;
            }
            else
            {
                $chk = 2;
            }
        }
        else
        {
            $chk = 3;
        }
        return response()->json(['success' =>  $chk]);
    }

     public function test__customerexportexcel(Request $request)	
    {

        ini_set('memory_limit', '-1');
            $chkexport = [];
            if(isset($request->id))
            {
                if(count($request->id) == 2)
                {
                    $a1 = DB::table('nt_model')->where('id_model', $request->id[0])->first();
                    $a2 = DB::table('nt_model')->where('id_model', $request->id[1])->first();
                    $text1 = $a1->name_model;
                    $text2 = $a2->name_model;
                    $chktext1 = mb_substr($text1,0,-3);
                    $chktext2 = mb_substr($text2,0,-3);
                    if($chktext1 == $chktext2)
                    {
                        if($a1->part == "UP")
                        {
                            $model1 = $a1;
                        }
                        else
                        {
                            $model1 = $a2;
                        }
                        if($a2->part == "DN")
                        {
                            $model2 = $a2;
                        }
                        else
                        {
                            $model2 = $a1;
                        }
                        $dateend1 = date('Y-m-d 00:00:00', strtotime($request->input('dateend'.$model1->id_model). ' + 1 day'));
                        $dateend2 = date('Y-m-d 00:00:00', strtotime($request->input('dateend'.$model2->id_model). ' + 1 day'));
                        $datalablpc = DB::table('nt_data_lpc')
                        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_lpc.id_createlab')
                        ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                        ->where('nt_oqa_lab.save_date','>=',$request->input('datestart'.$model1->id_model))
                        ->where('nt_oqa_lab.save_date','<=',$dateend1)
                        ->where('nt_oqa_lab.id_model',$model1->id_model)
                        ->where('nt_oqa_lab.lpc_confirm_st',1)
                        ->where('nt_oqa_lab.type_lab','LIKE','SGT_%')
                        ->orderBy('nt_oqa_lab.save_date', 'asc')->get();

                        $datalablpcdw = DB::table('nt_data_lpc')
                        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_lpc.id_createlab')
                        ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                        ->where('nt_oqa_lab.save_date','>=',$request->input('datestart'.$model2->id_model))
                        ->where('nt_oqa_lab.save_date','<=',$dateend2)
                        ->where('nt_oqa_lab.id_model',$model2->id_model)
                        ->where('nt_oqa_lab.lpc_confirm_st',1)
                        ->where('nt_oqa_lab.type_lab','LIKE','SGT_%')
                        ->orderBy('nt_oqa_lab.save_date', 'asc')->get();
                    
                        $datalabsem = DB::table('nt_data_sem')
                        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                        ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                        ->where('nt_oqa_lab.save_date','>=',$request->input('datestart'.$model1->id_model))
                        ->where('nt_oqa_lab.save_date','<=',$dateend1)
                        ->where('nt_oqa_lab.id_model',$model1->id_model)
                        ->where('nt_oqa_lab.sem_confirm_st',1)
                        ->where('nt_oqa_lab.type_lab','LIKE','SGT_%')
                        ->orderBy('nt_oqa_lab.save_date', 'asc')->get();

                        $datalabsemdw = DB::table('nt_data_sem')
                        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                        ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                        ->where('nt_oqa_lab.save_date','>=',$request->input('datestart'.$model1->id_model))
                        ->where('nt_oqa_lab.save_date','<=',$dateend2)
                        ->where('nt_oqa_lab.id_model',$model2->id_model)
                        ->where('nt_oqa_lab.sem_confirm_st',1)
                        ->where('nt_oqa_lab.type_lab','LIKE','SGT_%')
                        ->orderBy('nt_oqa_lab.save_date', 'asc')->get();
            
                        $datalabic = DB::table('nt_data_ic')
                        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ic.id_createlab')
                        ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                        ->where('nt_oqa_lab.save_date','>=',$request->input('datestart'.$model1->id_model))
                        ->where('nt_oqa_lab.save_date','<=',$dateend1)
                        ->where('nt_oqa_lab.id_model',$model1->id_model)
                        ->where('nt_oqa_lab.ic_confirm_st',1)
                        ->where('nt_oqa_lab.type_lab','LIKE','SGT_%')
                        ->orderBy('nt_oqa_lab.save_date', 'asc')->get();                        

                        $datalabicdw = DB::table('nt_data_ic')
                        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ic.id_createlab')
                        ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                        ->where('nt_oqa_lab.save_date','>=',$request->input('datestart'.$model2->id_model))
                        ->where('nt_oqa_lab.save_date','<=',$dateend2)
                        ->where('nt_oqa_lab.id_model',$model2->id_model)
                        ->where('nt_oqa_lab.ic_confirm_st',1)
                        ->where('nt_oqa_lab.type_lab','LIKE','SGT_%')
                        ->orderBy('nt_oqa_lab.save_date', 'asc')->get();
                    
                        $datalabgcms = DB::table('nt_data_gcms')
                        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
                        ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                        ->leftjoin('nt_outgassing_gcms','nt_outgassing_gcms.id_createlab','=','nt_oqa_lab.id_createlab')
                        ->where('nt_oqa_lab.save_date','>=',$request->input('datestart'.$model1->id_model))
                        ->where('nt_oqa_lab.save_date','<=',$dateend1)
                        ->where('nt_oqa_lab.id_model',$model1->id_model)
                        ->where('nt_oqa_lab.gcms_confirm_st',1)
                        ->where('nt_oqa_lab.type_lab','LIKE','SGT_%')
                        ->orderBy('nt_oqa_lab.save_date', 'asc')->get();

                        $datalabgcmsdw = DB::table('nt_data_gcms')
                        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
                        ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                        ->leftjoin('nt_outgassing_gcms','nt_outgassing_gcms.id_createlab','=','nt_oqa_lab.id_createlab')
                        ->where('nt_oqa_lab.save_date','>=',$request->input('datestart'.$model2->id_model))
                        ->where('nt_oqa_lab.save_date','<=',$dateend2)
                        ->where('nt_oqa_lab.id_model',$model2->id_model)
                        ->where('nt_oqa_lab.gcms_confirm_st',1)
                        ->where('nt_oqa_lab.type_lab','LIKE','SGT_%')
                        ->orderBy('nt_oqa_lab.save_date', 'asc')->get();
                        
                        $datalabftir = DB::table('nt_data_ftir')
                        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ftir.id_createlab')
                        ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                        ->where('nt_oqa_lab.save_date','>=',$request->input('datestart'.$model1->id_model))
                        ->where('nt_oqa_lab.save_date','<=',$dateend1)
                        ->where('nt_oqa_lab.id_model',$model1->id_model)
                        ->where('nt_oqa_lab.ftir_confirm_st',1)
                        ->where('nt_oqa_lab.type_lab','LIKE','SGT_%')
                        ->orderBy('nt_oqa_lab.save_date', 'asc')->get();

                        $datalabftirdw = DB::table('nt_data_ftir')
                        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ftir.id_createlab')
                        ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                        ->where('nt_oqa_lab.save_date','>=',$request->input('datestart'.$model2->id_model))
                        ->where('nt_oqa_lab.save_date','<=',$dateend2)
                        ->where('nt_oqa_lab.id_model',$model2->id_model)
                        ->where('nt_oqa_lab.ftir_confirm_st',1)
                        ->where('nt_oqa_lab.type_lab','LIKE','SGT_%')
                        ->orderBy('nt_oqa_lab.save_date', 'asc')->get();


                    }
                    else
                    {
                    return response()->json(['success' => 2]);
                    }
                }
                else
                {
                    return response()->json(['success' => 3]);
                }
            }
            else
            {
                return response()->json(['success' => 4]);
            }
        
        
            $specification = DB::table('nt_model_spec')->where('id_model', $model1->id_model)->where('id_model_type', 1)->first();
            $triggerlimit = DB::table('nt_model_spec')->where('id_model', $model1->id_model)->where('id_model_type', 2)->first();
            $ucl = DB::table('nt_model_spec')->where('id_model', $model1->id_model)->where('id_model_type', 3)->first();
            $lcl = DB::table('nt_model_spec')->where('id_model', $model1->id_model)->where('id_model_type', 4)->first();
            $cl = DB::table('nt_model_spec')->where('id_model', $model1->id_model)->where('id_model_type', 5)->first();

            $specification1 = DB::table('nt_model_spec')->where('id_model', $model2->id_model)->where('id_model_type', 1)->first();
            $triggerlimit1 = DB::table('nt_model_spec')->where('id_model', $model2->id_model)->where('id_model_type', 2)->first();
            $ucl1 = DB::table('nt_model_spec')->where('id_model', $model2->id_model)->where('id_model_type', 3)->first();
            $lcl1 = DB::table('nt_model_spec')->where('id_model', $model2->id_model)->where('id_model_type', 4)->first();
            $cl1 = DB::table('nt_model_spec')->where('id_model', $model2->id_model)->where('id_model_type', 5)->first();

            $file = storage_path('NHK-File/template/report/Excel_Raw_Customer_r05.xlsx');
            $filetime = date('YmdHis');
            //$namefile = 'graphexcel'.$filetime;
            $spacename = str_replace(' ', '', $chktext1);
            // $namefile = 'test_Excel_Customer_'.$spacename.'_'.date("YmdHis");
            $namefile = 'Excel_Customer_'.$spacename.'_'.date("YmdHis");
        
            Excel::load($file, function ($doc)use($specification,$triggerlimit,$ucl,$lcl,$cl,$cl1,$specification1,$triggerlimit1,$ucl1,$lcl1,$datalablpc,$datalabsem,$datalabic,$datalabgcms,$datalabftir,$datalablpcdw,$datalabsemdw,$datalabicdw,$datalabgcmsdw,$datalabftirdw) {
                $sheet = $doc->setActiveSheetIndex(0);
                $sheetlpc = $doc->setActiveSheetIndex(1);
                $sheetclass1 = $doc->setActiveSheetIndex(2);
                $sheetclass2 = $doc->setActiveSheetIndex(3);
                $sheetclass3 = $doc->setActiveSheetIndex(4);
                $sheetclass3_1 = $doc->setActiveSheetIndex(5);
                $sheetclass3_2 = $doc->setActiveSheetIndex(6);
                $sheetclass3_3 = $doc->setActiveSheetIndex(7);
                $sheetclass4 = $doc->setActiveSheetIndex(8);
                $sheetclass5 = $doc->setActiveSheetIndex(9);
                $sheetclass5_1 = $doc->setActiveSheetIndex(10);
                $sheetclass6 = $doc->setActiveSheetIndex(11);
                $sheetanion = $doc->setActiveSheetIndex(12);
                $sheetsulfate = $doc->setActiveSheetIndex(13);
                $sheetcation = $doc->setActiveSheetIndex(14);
                $sheetog = $doc->setActiveSheetIndex(15);
                $sheetlm = $doc->setActiveSheetIndex(16);
                $sheethm = $doc->setActiveSheetIndex(17);
                $sheetsm = $doc->setActiveSheetIndex(18);
                $sheetftir = $doc->setActiveSheetIndex(19);
                $i = 34;
                
                foreach ($datalablpc as $item) {    

                    $starttime = date('YmdHis',strtotime($item->save_date));
                    $ftime = date('YmdHis',strtotime($item->ftime_lpc));
                    $stdate = date('Y-m-d', strtotime($item->save_date));
                
                    $datafirst = DB::table('nt_model_history')
                    ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model_history.id_model', $item->id_model)
                    ->where('nt_model_history.date_start','<=',$stdate)
                    ->where('nt_model_history.date_end','>=',$stdate)
                    ->where('nt_model_history.chkstatus', 1)
                    ->get();

                    if($datafirst->count() == 0)
                    {

                        $data1 = DB::table('nt_model_history')
                        ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model_history.id_model', $item->id_model)
                        ->where('nt_model_history.date_start','>',$stdate)
                        ->orderby('nt_model_history.date_start','asc')
                        ->first();
                        
                        $datafirst = DB::table('nt_model_history')
                        ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model_history.id_model', $item->id_model)
                        ->where('nt_model_history.date_start','<=',$data1->date_start)  
                        ->where('nt_model_history.date_end','>=',$data1->date_start)
                        // ->where('nt_model_history.chkstatus', 1)
                        ->get(); 

                    }
                    
                    $sheetlpc->setCellValue('F'.$i,date('d-M-y',strtotime($item->tga)));
                    $sheetlpc->setCellValue('G'.$i,slope::nhk_formatnumber($item->lpc1, 0));
                    $sheetlpc->setCellValue('H'.$i,slope::nhk_formatnumber($item->lpc2, 0));
                    $sheetlpc->setCellValue('I'.$i,slope::nhk_formatnumber($item->lpc3, 0));
                    $sheetlpc->setCellValue('J'.$i,slope::nhk_formatnumber($item->lpc_data, 0));
                    $sheetlpc->setCellValue('K'.$i,$item->lotnumber_lab);
                    $sheetlpc->setCellValue('L'.$i,slope::nhk_formatnumber($datafirst[1]->alpc, 2));
                    $sheetlpc->setCellValue('M'.$i,slope::nhk_formatnumber($datafirst[2]->alpc, 0));
                    $sheetlpc->setCellValue('N'.$i,slope::nhk_formatnumber($datafirst[0]->alpc, 2));
                    $sheetlpc->setCellValue('O'.$i,slope::nhk_formatnumber($specification->alpc, 0));
                    $sheetlpc->setCellValue('P'.$i,$starttime);
                    $sheetlpc->setCellValue('Q'.$i,$ftime);
                    $sheetlpc->setCellValue('AF'.$i,$datafirst[3]->alpc);
                    
                    $i++;
                }
                $i = 34;
                
                foreach ($datalablpcdw as $item) {     
                    $starttime = date('YmdHis',strtotime($item->tga));
                    $ftime = date('YmdHis',strtotime($item->ftime_lpc));
                    $stdate = date('Y-m-d', strtotime($item->save_date));
                    $datafirst = DB::table('nt_model_history')
                    ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model_history.id_model', $item->id_model)
                    ->where('nt_model_history.date_start','<=',$stdate)
                    ->where('nt_model_history.date_end','>=',$stdate)
                    ->where('nt_model_history.chkstatus', 1)
                    ->get();

                    if($datafirst->count() == 0)
                    {
                        $data1 = DB::table('nt_model_history')
                        ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model_history.id_model', $item->id_model)
                        ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                        ->first();
                        
                        $datafirst = DB::table('nt_model_history')
                        ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model_history.id_model', $item->id_model)
                        ->where('nt_model_history.date_start','<=',$data1->date_start)  
                        ->where('nt_model_history.date_end','>=',$data1->date_start)
                        // ->where('nt_model_history.chkstatus', 1)
                        ->get(); 
                    }

                    $sheetlpc->setCellValue('S'.$i,date('d-M-y',strtotime($item->tga)));
                    $sheetlpc->setCellValue('T'.$i,slope::nhk_formatnumber($item->lpc1, 0));
                    $sheetlpc->setCellValue('U'.$i,slope::nhk_formatnumber($item->lpc2, 0));
                    $sheetlpc->setCellValue('V'.$i,slope::nhk_formatnumber($item->lpc3, 0));
                    $sheetlpc->setCellValue('W'.$i,slope::nhk_formatnumber($item->lpc_data, 0));
                    $sheetlpc->setCellValue('X'.$i,$item->lotnumber_lab);
                    $sheetlpc->setCellValue('Y'.$i,slope::nhk_formatnumber($datafirst[1]->alpc, 2));
                    $sheetlpc->setCellValue('Z'.$i,slope::nhk_formatnumber($datafirst[2]->alpc, 0));
                    $sheetlpc->setCellValue('AA'.$i,slope::nhk_formatnumber($datafirst[0]->alpc, 2));
                    $sheetlpc->setCellValue('AB'.$i,slope::nhk_formatnumber($specification1->alpc, 0));
                    $sheetlpc->setCellValue('AC'.$i,$starttime);
                    $sheetlpc->setCellValue('AD'.$i,$ftime);
                    $sheetlpc->setCellValue('AG'.$i,$datafirst[3]->alpc);
                    $i++;
                }
                
                $i = 34;
                foreach ($datalabsem as $item) {     
                    $starttime = date('YmdHis',strtotime($item->save_date));
                    $ftime = date('YmdHis',strtotime($item->ftime_sem));
                    $stdate = date('Y-m-d', strtotime($item->save_date));
                    $datafirst = DB::table('nt_model_history')
                    ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model_history.id_model', $item->id_model)
                    ->where('nt_model_history.date_start','<=',$stdate)
                    ->where('nt_model_history.date_end','>=',$stdate)
                    ->where('nt_model_history.chkstatus', 1)
                    ->get();
                    if($datafirst->count() == 0)
                    {
                        $data1 = DB::table('nt_model_history')
                        ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model_history.id_model', $item->id_model)
                        ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                        ->first();
                        
                        $datafirst = DB::table('nt_model_history')
                        ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model_history.id_model', $item->id_model)
                        ->where('nt_model_history.date_start','<=',$data1->date_start)  
                        ->where('nt_model_history.date_end','>=',$data1->date_start)
                        // ->where('nt_model_history.chkstatus', 1)
                        ->get(); 
                    }
                    $sheetclass1->setCellValue('F'.$i,date('d-M-y',strtotime($item->tga)));
                    $sheetclass1->setCellValue('G'.$i,slope::nhk_formatnumber($item->sem_class1, 1));
                    $sheetclass1->setCellValue('H'.$i,$item->lotnumber_lab);
                    $sheetclass1->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->class1, 1));
                    $sheetclass1->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[2]->class1, 1));
                    $sheetclass1->setCellValue('K'.$i,slope::nhk_formatnumber($datafirst[0]->class1, 1));
                    $sheetclass1->setCellValue('L'.$i,slope::nhk_formatnumber($specification->class1, 1));
                    $sheetclass1->setCellValue('M'.$i,$starttime);
                    $sheetclass1->setCellValue('N'.$i,$ftime);
                    $sheetclass1->setCellValue('Z'.$i,$datafirst[3]->class1);

                    $sheetclass2->setCellValue('F'.$i,date('d-M-y',strtotime($item->tga)));
                    $sheetclass2->setCellValue('G'.$i,slope::nhk_formatnumber($item->sem_class2, 1));
                    $sheetclass2->setCellValue('H'.$i,$item->lotnumber_lab);
                    $sheetclass2->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->class2, 1));
                    $sheetclass2->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[2]->class2, 1));
                    $sheetclass2->setCellValue('K'.$i,slope::nhk_formatnumber($datafirst[0]->class2, 1));
                    $sheetclass2->setCellValue('L'.$i,slope::nhk_formatnumber($specification->class2, 1));
                    $sheetclass2->setCellValue('M'.$i,$starttime);
                    $sheetclass2->setCellValue('N'.$i,$ftime);
                    $sheetclass2->setCellValue('Z'.$i,$datafirst[3]->class2);

                    $sheetclass3->setCellValue('F'.$i,date('d-M-y',strtotime($item->tga)));
                    $sheetclass3->setCellValue('G'.$i,slope::nhk_formatnumber($item->sem_class3, 1));
                    $sheetclass3->setCellValue('H'.$i,$item->lotnumber_lab);
                    $sheetclass3->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->class3, 1));
                    $sheetclass3->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[2]->class3, 1));
                    $sheetclass3->setCellValue('K'.$i,slope::nhk_formatnumber($datafirst[0]->class3, 1));
                    $sheetclass3->setCellValue('L'.$i,slope::nhk_formatnumber($specification->class3, 1));
                    $sheetclass3->setCellValue('M'.$i,$starttime);
                    $sheetclass3->setCellValue('N'.$i,$ftime);
                    $sheetclass3->setCellValue('Z'.$i,$datafirst[3]->class3);

                    $sheetclass3_1->setCellValue('F'.$i,date('d-M-y',strtotime($item->tga)));
                    $sheetclass3_1->setCellValue('G'.$i,slope::nhk_formatnumber($item->sem_class3_1, 1));
                    $sheetclass3_1->setCellValue('H'.$i,$item->lotnumber_lab);
                    $sheetclass3_1->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->class3_1, 1));
                    $sheetclass3_1->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[2]->class3_1, 1));
                    $sheetclass3_1->setCellValue('K'.$i,slope::nhk_formatnumber($datafirst[0]->class3_1, 1));
                    $sheetclass3_1->setCellValue('L'.$i,slope::nhk_formatnumber($specification->class3_1, 1));
                    $sheetclass3_1->setCellValue('M'.$i,$starttime);
                    $sheetclass3_1->setCellValue('N'.$i,$ftime);
                    $sheetclass3_1->setCellValue('Z'.$i,$datafirst[3]->class3_1);

                    $sheetclass3_2->setCellValue('F'.$i,date('d-M-y',strtotime($item->tga)));
                    $sheetclass3_2->setCellValue('G'.$i,slope::nhk_formatnumber($item->sem_class3_2, 1));
                    $sheetclass3_2->setCellValue('H'.$i,$item->lotnumber_lab);
                    $sheetclass3_2->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->class3_2, 1));
                    $sheetclass3_2->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[2]->class3_2, 1));
                    $sheetclass3_2->setCellValue('K'.$i,slope::nhk_formatnumber($datafirst[0]->class3_2, 1));
                    $sheetclass3_2->setCellValue('L'.$i,slope::nhk_formatnumber($specification->class3_2, 1));
                    $sheetclass3_2->setCellValue('M'.$i,$starttime);
                    $sheetclass3_2->setCellValue('N'.$i,$ftime);
                    $sheetclass3_2->setCellValue('Z'.$i,$datafirst[3]->class3_2);

                    $sheetclass3_3->setCellValue('F'.$i,date('d-M-y',strtotime($item->tga)));
                    $sheetclass3_3->setCellValue('G'.$i,slope::nhk_formatnumber($item->sem_class3_3, 1));
                    $sheetclass3_3->setCellValue('H'.$i,$item->lotnumber_lab);
                    $sheetclass3_3->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->class3_3, 1));
                    $sheetclass3_3->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[2]->class3_3, 1));
                    $sheetclass3_3->setCellValue('K'.$i,slope::nhk_formatnumber($datafirst[0]->class3_3, 1));
                    $sheetclass3_3->setCellValue('L'.$i,slope::nhk_formatnumber($specification->class3_3, 1));
                    $sheetclass3_3->setCellValue('M'.$i,$starttime);
                    $sheetclass3_3->setCellValue('N'.$i,$ftime);
                    $sheetclass3_3->setCellValue('Z'.$i,$datafirst[3]->class3_3);

                    $sheetclass4->setCellValue('F'.$i,date('d-M-y',strtotime($item->tga)));
                    $sheetclass4->setCellValue('G'.$i,slope::nhk_formatnumber($item->sem_class4, 1));
                    $sheetclass4->setCellValue('H'.$i,$item->lotnumber_lab);
                    $sheetclass4->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->class4, 1));
                    $sheetclass4->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[2]->class4, 1));
                    $sheetclass4->setCellValue('K'.$i,slope::nhk_formatnumber($datafirst[0]->class4, 1));
                    $sheetclass4->setCellValue('L'.$i,slope::nhk_formatnumber($specification->class4, 1));
                    $sheetclass4->setCellValue('M'.$i,$starttime);
                    $sheetclass4->setCellValue('N'.$i,$ftime);
                    $sheetclass4->setCellValue('Z'.$i,$datafirst[3]->class4);

                    $sheetclass5->setCellValue('F'.$i,date('d-M-y',strtotime($item->tga)));
                    if($item->last_sem_class5 !== null){
                        $sheetclass5->setCellValue('G'.$i,slope::nhk_formatnumber($item->last_sem_class5, 1));
                    }else{
                        $sheetclass5->setCellValue('G'.$i,slope::nhk_formatnumber($item->sem_class5, 1));
                    }
                    $sheetclass5->setCellValue('H'.$i,$item->lotnumber_lab);
                    $sheetclass5->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->class5, 1));
                    $sheetclass5->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[2]->class5, 1));
                    $sheetclass5->setCellValue('K'.$i,slope::nhk_formatnumber($datafirst[0]->class5, 1));
                    $sheetclass5->setCellValue('L'.$i,slope::nhk_formatnumber($specification->class5, 1));
                    $sheetclass5->setCellValue('M'.$i,$starttime);
                    $sheetclass5->setCellValue('N'.$i,$ftime);
                    $sheetclass5->setCellValue('Z'.$i,$datafirst[3]->class5);

                    $sheetclass5_1->setCellValue('F'.$i,date('d-M-y',strtotime($item->tga)));
                    $sheetclass5_1->setCellValue('G'.$i,slope::nhk_formatnumber($item->sem_class5_1, 1));
                    $sheetclass5_1->setCellValue('H'.$i,$item->lotnumber_lab);
                    $sheetclass5_1->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->class5_1, 1));
                    $sheetclass5_1->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[2]->class5_1, 1));
                    $sheetclass5_1->setCellValue('K'.$i,slope::nhk_formatnumber($datafirst[0]->class5_1, 1));
                    $sheetclass5_1->setCellValue('L'.$i,slope::nhk_formatnumber($specification->class5_1, 1));
                    $sheetclass5_1->setCellValue('M'.$i,$starttime);
                    $sheetclass5_1->setCellValue('N'.$i,$ftime);
                    $sheetclass5_1->setCellValue('Z'.$i,$datafirst[3]->class5_1);

                    $sheetclass6->setCellValue('F'.$i,date('d-M-y',strtotime($item->tga)));
                    $sheetclass6->setCellValue('G'.$i,slope::nhk_formatnumber($item->sem_class6, 1));
                    $sheetclass6->setCellValue('H'.$i,$item->lotnumber_lab);
                    $sheetclass6->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->class6, 1));
                    $sheetclass6->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[2]->class6, 1));
                    $sheetclass6->setCellValue('K'.$i,slope::nhk_formatnumber($datafirst[0]->class6, 1));
                    $sheetclass6->setCellValue('L'.$i,slope::nhk_formatnumber($specification->class6, 1));
                    $sheetclass6->setCellValue('M'.$i,$starttime);
                    $sheetclass6->setCellValue('N'.$i,$ftime);
                    $sheetclass6->setCellValue('Z'.$i,$datafirst[3]->class6);

                    $i++;
                }
                $i = 34;
                foreach ($datalabsemdw as $item) {     
                    $starttime = date('YmdHis',strtotime($item->save_date));
                    $ftime = date('YmdHis',strtotime($item->ftime_sem));
                    $stdate = date('Y-m-d', strtotime($item->save_date));
                    $datafirst = DB::table('nt_model_history')
                    ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model_history.id_model', $item->id_model)
                    ->where('nt_model_history.date_start','<=',$stdate)
                    ->where('nt_model_history.date_end','>=',$stdate)
                    ->where('nt_model_history.chkstatus', 1)
                    ->get();
                    if($datafirst->count() == 0)
                    {
                        $data1 = DB::table('nt_model_history')
                        ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model_history.id_model', $item->id_model)
                        ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                        ->first();
                        
                        $datafirst = DB::table('nt_model_history')
                        ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model_history.id_model', $item->id_model)
                        ->where('nt_model_history.date_start','<=',$data1->date_start)  
                        ->where('nt_model_history.date_end','>=',$data1->date_start)
                        // ->where('nt_model_history.chkstatus', 1)
                        ->get(); 
                    }
                    $sheetclass1->setCellValue('P'.$i,date('d-M-y',strtotime($item->tga)));
                    if($item->last_sem_class1 !== null){
                        $sheetclass1->setCellValue('Q'.$i,slope::nhk_formatnumber($item->last_sem_class1, 1));
                    }else{
                        $sheetclass1->setCellValue('Q'.$i,slope::nhk_formatnumber($item->sem_class1, 1));
                    }
                    $sheetclass1->setCellValue('R'.$i,$item->lotnumber_lab);
                    $sheetclass1->setCellValue('S'.$i,slope::nhk_formatnumber($datafirst[1]->class1, 1));
                    $sheetclass1->setCellValue('T'.$i,slope::nhk_formatnumber($datafirst[2]->class1, 1));
                    $sheetclass1->setCellValue('U'.$i,slope::nhk_formatnumber($datafirst[0]->class1, 1));
                    $sheetclass1->setCellValue('V'.$i,slope::nhk_formatnumber($specification1->class1, 1));
                    $sheetclass1->setCellValue('W'.$i,$starttime);
                    $sheetclass1->setCellValue('X'.$i,$ftime);
                    $sheetclass1->setCellValue('AA'.$i,$datafirst[3]->class1);

                    $sheetclass2->setCellValue('P'.$i,date('d-M-y',strtotime($item->tga)));
                    if($item->last_sem_class2 !== null){
                        $sheetclass2->setCellValue('Q'.$i,slope::nhk_formatnumber($item->last_sem_class2, 1));
                    }else{
                        $sheetclass2->setCellValue('Q'.$i,slope::nhk_formatnumber($item->sem_class2, 1));
                    }
                    $sheetclass2->setCellValue('R'.$i,$item->lotnumber_lab);
                    $sheetclass2->setCellValue('S'.$i,slope::nhk_formatnumber($datafirst[1]->class2, 1));
                    $sheetclass2->setCellValue('T'.$i,slope::nhk_formatnumber($datafirst[2]->class2, 1));
                    $sheetclass2->setCellValue('U'.$i,slope::nhk_formatnumber($datafirst[0]->class2, 1));
                    $sheetclass2->setCellValue('V'.$i,slope::nhk_formatnumber($specification1->class2, 1));
                    $sheetclass2->setCellValue('W'.$i,$starttime);
                    $sheetclass2->setCellValue('X'.$i,$ftime);
                    $sheetclass2->setCellValue('AA'.$i,$datafirst[3]->class2);

                    $sheetclass3->setCellValue('P'.$i,date('d-M-y',strtotime($item->tga)));
                    if($item->last_sem_class3!== null){
                        $sheetclass3->setCellValue('Q'.$i,slope::nhk_formatnumber($item->last_sem_class3, 1));
                    }else{
                        $sheetclass3->setCellValue('Q'.$i,slope::nhk_formatnumber($item->sem_class3, 1));
                    }

                    $sheetclass3->setCellValue('R'.$i,$item->lotnumber_lab);
                    $sheetclass3->setCellValue('S'.$i,slope::nhk_formatnumber($datafirst[1]->class3, 1));
                    $sheetclass3->setCellValue('T'.$i,slope::nhk_formatnumber($datafirst[2]->class3, 1));
                    $sheetclass3->setCellValue('U'.$i,slope::nhk_formatnumber($datafirst[0]->class3, 1));
                    $sheetclass3->setCellValue('V'.$i,slope::nhk_formatnumber($specification1->class3, 1));
                    $sheetclass3->setCellValue('W'.$i,$starttime);
                    $sheetclass3->setCellValue('X'.$i,$ftime);
                    $sheetclass3->setCellValue('AA'.$i,$datafirst[3]->class3);

                    $sheetclass3_1->setCellValue('P'.$i,date('d-M-y',strtotime($item->tga)));
                    if($item->last_sem_class3_1 !== null){
                        $sheetclass3_1->setCellValue('Q'.$i,slope::nhk_formatnumber($item->last_sem_class3_1, 1));
                    }else{
                        $sheetclass3_1->setCellValue('Q'.$i,slope::nhk_formatnumber($item->sem_class3_1, 1));
                    }

                    $sheetclass3_1->setCellValue('R'.$i,$item->lotnumber_lab);
                    $sheetclass3_1->setCellValue('S'.$i,slope::nhk_formatnumber($datafirst[1]->class3_1, 1));
                    $sheetclass3_1->setCellValue('T'.$i,slope::nhk_formatnumber($datafirst[2]->class3_1, 1));
                    $sheetclass3_1->setCellValue('U'.$i,slope::nhk_formatnumber($datafirst[0]->class3_1, 1));
                    $sheetclass3_1->setCellValue('V'.$i,slope::nhk_formatnumber($specification1->class3_1, 1));
                    $sheetclass3_1->setCellValue('W'.$i,$starttime);
                    $sheetclass3_1->setCellValue('X'.$i,$ftime);
                    $sheetclass3_1->setCellValue('AA'.$i,$datafirst[3]->class3_1);

                    $sheetclass3_2->setCellValue('P'.$i,date('d-M-y',strtotime($item->tga)));
                    if($item->last_sem_class3_2 !== null){
                        $sheetclass3_2->setCellValue('Q'.$i,slope::nhk_formatnumber($item->last_sem_class3_2, 1));
                    }else{
                        $sheetclass3_2->setCellValue('Q'.$i,slope::nhk_formatnumber($item->sem_class3_2, 1));
                    }
                    $sheetclass3_2->setCellValue('R'.$i,$item->lotnumber_lab);
                    $sheetclass3_2->setCellValue('S'.$i,slope::nhk_formatnumber($datafirst[1]->class3_2, 1));
                    $sheetclass3_2->setCellValue('T'.$i,slope::nhk_formatnumber($datafirst[2]->class3_2, 1));
                    $sheetclass3_2->setCellValue('U'.$i,slope::nhk_formatnumber($datafirst[0]->class3_2, 1));
                    $sheetclass3_2->setCellValue('V'.$i,slope::nhk_formatnumber($specification1->class3_2, 1));
                    $sheetclass3_2->setCellValue('W'.$i,$starttime);
                    $sheetclass3_2->setCellValue('X'.$i,$ftime);
                    $sheetclass3_2->setCellValue('AA'.$i,$datafirst[3]->class3_2);

                    $sheetclass3_3->setCellValue('P'.$i,date('d-M-y',strtotime($item->tga)));
                    if($item->last_sem_class3_3 !== null){
                        $sheetclass3_3->setCellValue('Q'.$i,slope::nhk_formatnumber($item->last_sem_class3_3, 1));
                    }else{
                        $sheetclass3_3->setCellValue('Q'.$i,slope::nhk_formatnumber($item->sem_class3_3, 1));
                    }
                    $sheetclass3_3->setCellValue('R'.$i,$item->lotnumber_lab);
                    $sheetclass3_3->setCellValue('S'.$i,slope::nhk_formatnumber($datafirst[1]->class3_3, 1));
                    $sheetclass3_3->setCellValue('T'.$i,slope::nhk_formatnumber($datafirst[2]->class3_3, 1));
                    $sheetclass3_3->setCellValue('U'.$i,slope::nhk_formatnumber($datafirst[0]->class3_3, 1));
                    $sheetclass3_3->setCellValue('V'.$i,slope::nhk_formatnumber($specification1->class3_3, 1));
                    $sheetclass3_3->setCellValue('W'.$i,$starttime);
                    $sheetclass3_3->setCellValue('X'.$i,$ftime);
                    $sheetclass3_3->setCellValue('AA'.$i,$datafirst[3]->class3_3);

                    $sheetclass4->setCellValue('P'.$i,date('d-M-y',strtotime($item->tga)));
                    if($item->last_sem_class4 !== null){
                        $sheetclass4->setCellValue('Q'.$i,slope::nhk_formatnumber($item->last_sem_class4, 1));
                    }else{
                        $sheetclass4->setCellValue('Q'.$i,slope::nhk_formatnumber($item->sem_class4, 1));
                    }
                    $sheetclass4->setCellValue('R'.$i,$item->lotnumber_lab);
                    $sheetclass4->setCellValue('S'.$i,slope::nhk_formatnumber($datafirst[1]->class4, 1));
                    $sheetclass4->setCellValue('T'.$i,slope::nhk_formatnumber($datafirst[2]->class4, 1));
                    $sheetclass4->setCellValue('U'.$i,slope::nhk_formatnumber($datafirst[0]->class4, 1));
                    $sheetclass4->setCellValue('V'.$i,slope::nhk_formatnumber($specification1->class4, 1));
                    $sheetclass4->setCellValue('W'.$i,$starttime);
                    $sheetclass4->setCellValue('X'.$i,$ftime);
                    $sheetclass4->setCellValue('AA'.$i,$datafirst[3]->class4);

                    $sheetclass5->setCellValue('P'.$i,date('d-M-y',strtotime($item->tga)));
                    if($item->last_sem_class5 !== null){
                        $sheetclass5->setCellValue('Q'.$i,slope::nhk_formatnumber($item->last_sem_class5, 1));
                    }else{
                        $sheetclass5->setCellValue('Q'.$i,slope::nhk_formatnumber($item->sem_class5, 1));
                    }
                    $sheetclass5->setCellValue('R'.$i,$item->lotnumber_lab);
                    $sheetclass5->setCellValue('S'.$i,slope::nhk_formatnumber($datafirst[1]->class5, 1));
                    $sheetclass5->setCellValue('T'.$i,slope::nhk_formatnumber($datafirst[2]->class5, 1));
                    $sheetclass5->setCellValue('U'.$i,slope::nhk_formatnumber($datafirst[0]->class5, 1));
                    $sheetclass5->setCellValue('V'.$i,slope::nhk_formatnumber($specification1->class5, 1));
                    $sheetclass5->setCellValue('W'.$i,$starttime);
                    $sheetclass5->setCellValue('X'.$i,$ftime);
                    $sheetclass5->setCellValue('AA'.$i,$datafirst[3]->class5);

                    $sheetclass5_1->setCellValue('P'.$i,date('d-M-y',strtotime($item->tga)));
                    if($item->last_sem_class5_1 !== null){
                        $sheetclass5_1->setCellValue('Q'.$i,slope::nhk_formatnumber($item->last_sem_class5_1, 1));
                    }else{
                        $sheetclass5_1->setCellValue('Q'.$i,slope::nhk_formatnumber($item->sem_class5_1, 1));
                    }
                    $sheetclass5_1->setCellValue('R'.$i,$item->lotnumber_lab);
                    $sheetclass5_1->setCellValue('S'.$i,slope::nhk_formatnumber($datafirst[1]->class5_1, 1));
                    $sheetclass5_1->setCellValue('T'.$i,slope::nhk_formatnumber($datafirst[2]->class5_1, 1));
                    $sheetclass5_1->setCellValue('U'.$i,slope::nhk_formatnumber($datafirst[0]->class5_1, 1));
                    $sheetclass5_1->setCellValue('V'.$i,slope::nhk_formatnumber($specification1->class5_1, 1));
                    $sheetclass5_1->setCellValue('W'.$i,$starttime);
                    $sheetclass5_1->setCellValue('X'.$i,$ftime);
                    $sheetclass5_1->setCellValue('AA'.$i,$datafirst[3]->class5_1);

                    $sheetclass6->setCellValue('P'.$i,date('d-M-y',strtotime($item->tga)));
                    if($item->last_sem_class6 !== null){
                        $sheetclass6->setCellValue('Q'.$i,slope::nhk_formatnumber($item->last_sem_class6, 1));
                    }else{
                        $sheetclass6->setCellValue('Q'.$i,slope::nhk_formatnumber($item->sem_class6, 1));
                    }
                    $sheetclass6->setCellValue('R'.$i,$item->lotnumber_lab);
                    $sheetclass6->setCellValue('S'.$i,slope::nhk_formatnumber($datafirst[1]->class6, 1));
                    $sheetclass6->setCellValue('T'.$i,slope::nhk_formatnumber($datafirst[2]->class6, 1));
                    $sheetclass6->setCellValue('U'.$i,slope::nhk_formatnumber($datafirst[0]->class6, 1));
                    $sheetclass6->setCellValue('V'.$i,slope::nhk_formatnumber($specification1->class6, 1));
                    $sheetclass6->setCellValue('W'.$i,$starttime);
                    $sheetclass6->setCellValue('X'.$i,$ftime);
                    $sheetclass6->setCellValue('AA'.$i,$datafirst[3]->class6);

                    $i++;
                }
                $i = 34;
                foreach ($datalabic as $item) {     
                    $starttime = date('YmdHis',strtotime($item->save_date));
                    $ftime = date('YmdHis',strtotime($item->ftime_ic));
                    $stdate = date('Y-m-d', strtotime($item->save_date));
                    $datafirst = DB::table('nt_model_history')
                    ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model_history.id_model', $item->id_model)
                    ->where('nt_model_history.date_start','<=',$stdate)
                    ->where('nt_model_history.date_end','>=',$stdate)
                    ->where('nt_model_history.chkstatus', 1)
                    ->get();
                    if($datafirst->count() == 0)
                    {
                        $data1 = DB::table('nt_model_history')
                        ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model_history.id_model', $item->id_model)
                        ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                        ->first();
                        
                        $datafirst = DB::table('nt_model_history')
                        ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model_history.id_model', $item->id_model)
                        ->where('nt_model_history.date_start','<=',$data1->date_start)  
                        ->where('nt_model_history.date_end','>=',$data1->date_start)
                        // ->where('nt_model_history.chkstatus', 1)
                        ->get(); 
                    }
                    $sheetanion->setCellValue('F'.$i,date('d-M-y',strtotime($item->tga)));
                    $sheetanion->setCellValue('G'.$i,slope::nhk_formatnumber($item->ic_anion_up, 5));  
                    $sheetanion->setCellValue('H'.$i,$item->lotnumber_lab);
                    $sheetanion->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->anion, 5));
                    $sheetanion->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[2]->anion, 5));
                    $sheetanion->setCellValue('K'.$i,slope::nhk_formatnumber($datafirst[0]->anion, 5));
                    $sheetanion->setCellValue('L'.$i,slope::nhk_formatnumber($specification->anion, 5));
                    $sheetanion->setCellValue('M'.$i,$starttime);
                    $sheetanion->setCellValue('N'.$i,$ftime);
                    $sheetanion->setCellValue('O'.$i,slope::nhk_formatnumber($item->ic_anion_fluoride, 5));
                    $sheetanion->setCellValue('P'.$i,slope::nhk_formatnumber($item->ic_anion_chloride, 5));
                    $sheetanion->setCellValue('Q'.$i,slope::nhk_formatnumber($item->ic_anion_nitrile, 5));
                    $sheetanion->setCellValue('R'.$i,slope::nhk_formatnumber($item->ic_anion_bromid, 5));
                    $sheetanion->setCellValue('S'.$i,slope::nhk_formatnumber($item->ic_anion_nitrate, 5));
                    $sheetanion->setCellValue('T'.$i,slope::nhk_formatnumber($item->ic_anion_sulphate, 5));
                    $sheetanion->setCellValue('U'.$i,slope::nhk_formatnumber($item->ic_anion_phosphate, 5));
                    $sheetanion->setCellValue('AN'.$i,$datafirst[3]->anion);

                    $sheetsulfate->setCellValue('F'.$i,date('d-M-y',strtotime($item->tga)));
                    $sheetsulfate->setCellValue('G'.$i,slope::nhk_formatnumber($item->ic_sulphate, 5));
                    $sheetsulfate->setCellValue('H'.$i,$item->lotnumber_lab);
                    $sheetsulfate->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->sulphate, 5));
                    $sheetsulfate->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[2]->sulphate, 5));
                    $sheetsulfate->setCellValue('K'.$i,slope::nhk_formatnumber($datafirst[0]->sulphate, 5));
                    $sheetsulfate->setCellValue('L'.$i,slope::nhk_formatnumber($specification->sulphate, 5));
                    $sheetsulfate->setCellValue('M'.$i,$starttime);
                    $sheetsulfate->setCellValue('N'.$i,$ftime);
                    $sheetsulfate->setCellValue('Z'.$i,$datafirst[3]->sulphate);

                    $sheetcation->setCellValue('F'.$i,date('d-M-y',strtotime($item->tga)));
                    $sheetcation->setCellValue('G'.$i,slope::nhk_formatnumber($item->ic_cation_up, 5)); ////
                    $sheetcation->setCellValue('H'.$i,$item->lotnumber_lab);
                    $sheetcation->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->cation, 5));
                    $sheetcation->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[2]->cation, 5));
                    $sheetcation->setCellValue('K'.$i,slope::nhk_formatnumber($datafirst[0]->cation, 5));
                    $sheetcation->setCellValue('L'.$i,slope::nhk_formatnumber($specification->cation, 5));
                    $sheetcation->setCellValue('M'.$i,$starttime);
                    $sheetcation->setCellValue('n'.$i,$starttime);
                    $sheetcation->setCellValue('O'.$i,slope::nhk_formatnumber($item->ic_cation_sodium, 5));
                    $sheetcation->setCellValue('P'.$i,slope::nhk_formatnumber($item->ic_cation_ammonium, 5));
                    $sheetcation->setCellValue('Q'.$i,slope::nhk_formatnumber($item->ic_cation_potassium, 5));
                    $sheetcation->setCellValue('R'.$i,slope::nhk_formatnumber($item->ic_cation_magnesium, 5));
                    $sheetcation->setCellValue('S'.$i,slope::nhk_formatnumber($item->ic_cation_calcium, 5));
                    $sheetcation->setCellValue('AN'.$i,$datafirst[3]->cation);
                    $i++;
                }
                $i = 34;
                foreach ($datalabicdw as $item) { 
                    $starttime = date('YmdHis',strtotime($item->save_date));
                    $ftime = date('YmdHis',strtotime($item->ftime_ic));
                    $stdate = date('Y-m-d', strtotime($item->save_date));
                    $datafirst = DB::table('nt_model_history')
                    ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model_history.id_model', $item->id_model)
                    ->where('nt_model_history.date_start','<=',$stdate)
                    ->where('nt_model_history.date_end','>=',$stdate)
                    ->where('nt_model_history.chkstatus', 1)
                    ->get();
                    if($datafirst->count() == 0)
                    {
                    $data1 = DB::table('nt_model_history')
                    ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model_history.id_model', $item->id_model)
                    ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                    ->first();
                    
                    $datafirst = DB::table('nt_model_history')
                    ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model_history.id_model', $item->id_model)
                    ->where('nt_model_history.date_start','<=',$data1->date_start)  
                    ->where('nt_model_history.date_end','>=',$data1->date_start)
                    // ->where('nt_model_history.chkstatus', 1)
                    ->get(); 
                    }
                    $sheetanion->setCellValue('W'.$i,date('d-M-y',strtotime($item->tga)));
                    $sheetanion->setCellValue('X'.$i,slope::nhk_formatnumber($item->ic_anion_up, 5));
                    $sheetanion->setCellValue('Y'.$i,$item->lotnumber_lab);
                    $sheetanion->setCellValue('Z'.$i,slope::nhk_formatnumber($datafirst[1]->anion, 5));
                    $sheetanion->setCellValue('AA'.$i,slope::nhk_formatnumber($datafirst[2]->anion, 5));
                    $sheetanion->setCellValue('AB'.$i,slope::nhk_formatnumber($datafirst[0]->anion, 5));
                    $sheetanion->setCellValue('AC'.$i,slope::nhk_formatnumber($specification1->anion, 5));
                    $sheetanion->setCellValue('AD'.$i,$starttime);
                    $sheetanion->setCellValue('AE'.$i,$ftime);
                    $sheetanion->setCellValue('AF'.$i,slope::nhk_formatnumber($item->ic_anion_fluoride, 5));
                    $sheetanion->setCellValue('AG'.$i,slope::nhk_formatnumber($item->ic_anion_chloride, 5));
                    $sheetanion->setCellValue('AH'.$i,slope::nhk_formatnumber($item->ic_anion_nitrile, 5));
                    $sheetanion->setCellValue('AI'.$i,slope::nhk_formatnumber($item->ic_anion_bromid, 5));
                    $sheetanion->setCellValue('AJ'.$i,slope::nhk_formatnumber($item->ic_anion_nitrate, 5));
                    $sheetanion->setCellValue('AK'.$i,slope::nhk_formatnumber($item->ic_anion_sulphate, 5));
                    $sheetanion->setCellValue('AL'.$i,slope::nhk_formatnumber($item->ic_anion_phosphate, 5));
                    $sheetanion->setCellValue('AO'.$i,$datafirst[3]->anion);

                    $sheetsulfate->setCellValue('P'.$i,date('d-M-y',strtotime($item->tga)));
                    $sheetsulfate->setCellValue('Q'.$i,slope::nhk_formatnumber($item->ic_sulphate, 5));
                    $sheetsulfate->setCellValue('R'.$i,$item->lotnumber_lab);
                    $sheetsulfate->setCellValue('S'.$i,slope::nhk_formatnumber($datafirst[1]->sulphate, 5));
                    $sheetsulfate->setCellValue('T'.$i,slope::nhk_formatnumber($datafirst[2]->sulphate, 5));
                    $sheetsulfate->setCellValue('U'.$i,slope::nhk_formatnumber($datafirst[0]->sulphate, 5));
                    $sheetsulfate->setCellValue('V'.$i,slope::nhk_formatnumber($specification1->sulphate, 5));
                    $sheetsulfate->setCellValue('W'.$i,$starttime);
                    $sheetsulfate->setCellValue('X'.$i,$ftime);
                    $sheetsulfate->setCellValue('AA'.$i,$datafirst[3]->sulphate);


                    $sheetcation->setCellValue('W'.$i,date('d-M-y',strtotime($item->tga)));
                    $sheetcation->setCellValue('X'.$i,slope::nhk_formatnumber($item->ic_cation_up, 5));////
                    $sheetcation->setCellValue('Y'.$i,$item->lotnumber_lab);
                    $sheetcation->setCellValue('Z'.$i,slope::nhk_formatnumber($datafirst[1]->cation, 5));
                    $sheetcation->setCellValue('AA'.$i,slope::nhk_formatnumber($datafirst[2]->cation, 5));
                    $sheetcation->setCellValue('AB'.$i,slope::nhk_formatnumber($datafirst[0]->cation, 5));
                    $sheetcation->setCellValue('AC'.$i,slope::nhk_formatnumber($specification1->cation, 5));
                    $sheetcation->setCellValue('AD'.$i,$starttime);
                    $sheetcation->setCellValue('AE'.$i,$starttime);
                    $sheetcation->setCellValue('AF'.$i,slope::nhk_formatnumber($item->ic_cation_sodium, 5));
                    $sheetcation->setCellValue('AG'.$i,slope::nhk_formatnumber($item->ic_cation_ammonium, 5));
                    $sheetcation->setCellValue('AH'.$i,slope::nhk_formatnumber($item->ic_cation_potassium, 5));
                    $sheetcation->setCellValue('AI'.$i,slope::nhk_formatnumber($item->ic_cation_magnesium, 5));
                    $sheetcation->setCellValue('AJ'.$i,slope::nhk_formatnumber($item->ic_cation_calcium, 5));
                    $sheetcation->setCellValue('AO'.$i,$datafirst[3]->cation);
                    $i++;
                }
                $i = 34;
                foreach ($datalabgcms as $item) {     
                    $starttime = date('YmdHis',strtotime($item->save_date));
                    $ftime = date('YmdHis',strtotime($item->ftime_gcms));
                    $stdate = date('Y-m-d', strtotime($item->save_date));
                    $datafirst = DB::table('nt_model_history')
                    ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model_history.id_model', $item->id_model)
                    ->where('nt_model_history.date_start','<=',$stdate)
                    ->where('nt_model_history.date_end','>=',$stdate)
                    ->where('nt_model_history.chkstatus', 1)
                    ->get();
                    if($datafirst->count() == 0)
                    {
                        $data1 = DB::table('nt_model_history')
                        ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model_history.id_model', $item->id_model)
                        ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                        ->first();
                        
                        $datafirst = DB::table('nt_model_history')
                        ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model_history.id_model', $item->id_model)
                        ->where('nt_model_history.date_start','<=',$data1->date_start)  
                        ->where('nt_model_history.date_end','>=',$data1->date_start)
                        // ->where('nt_model_history.chkstatus', 1)
                        ->get(); 
                    }
                    $sheetog->setCellValue('F'.$i,date('d-M-y',strtotime($item->tga)));
                    $sheetog->setCellValue('G'.$i,slope::nhk_formatnumber($item->gcms_outgassing, 3));
                    $sheetog->setCellValue('H'.$i,$item->lotnumber_lab);
                    $sheetog->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->outgassing, 0));
                    $sheetog->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[2]->outgassing, 0));
                    $sheetog->setCellValue('K'.$i,slope::nhk_formatnumber($datafirst[0]->outgassing, 0));
                    $sheetog->setCellValue('L'.$i,slope::nhk_formatnumber($specification->outgassing, 0));
                    $sheetog->setCellValue('M'.$i,$starttime);
                    $sheetog->setCellValue('N'.$i,$ftime);
                    $sheetog->setCellValue('O'.$i,slope::nhk_formatnumber($item->last_c1, 3));
                    $sheetog->setCellValue('P'.$i,slope::nhk_formatnumber($item->last_c2, 3));
                    $sheetog->setCellValue('Q'.$i,slope::nhk_formatnumber($item->last_c3, 3));
                    $sheetog->setCellValue('R'.$i,slope::nhk_formatnumber($item->last_c4, 3));
                    $sheetog->setCellValue('S'.$i,slope::nhk_formatnumber($item->last_c5, 3));
                    $sheetog->setCellValue('T'.$i,slope::nhk_formatnumber($item->last_c5_1, 3));
                    $sheetog->setCellValue('U'.$i,slope::nhk_formatnumber($item->last_c5_2, 3));
                    $sheetog->setCellValue('V'.$i,slope::nhk_formatnumber($item->last_c5_3, 3));
                    $sheetog->setCellValue('W'.$i,slope::nhk_formatnumber($item->last_c6, 3));
                    $sheetog->setCellValue('X'.$i,slope::nhk_formatnumber($item->last_c6_1, 3));
                    $sheetog->setCellValue('Y'.$i,slope::nhk_formatnumber($item->last_c6_2, 3));
                    $sheetog->setCellValue('Z'.$i,slope::nhk_formatnumber($item->last_c7, 3));
                    $sheetog->setCellValue('AA'.$i,slope::nhk_formatnumber($item->last_c8, 3));
                    $sheetog->setCellValue('AB'.$i,slope::nhk_formatnumber($item->last_c8_1, 3));
                    $sheetog->setCellValue('AC'.$i,slope::nhk_formatnumber($item->last_c8_2, 3));
                    $sheetog->setCellValue('AD'.$i,slope::nhk_formatnumber($item->last_c9, 3));
                    $sheetog->setCellValue('AE'.$i,slope::nhk_formatnumber($item->last_c9_1, 3));
                    $sheetog->setCellValue('AF'.$i,slope::nhk_formatnumber($item->last_c9_2, 3));
                    $sheetog->setCellValue('AG'.$i,slope::nhk_formatnumber($item->last_c10, 3));
                    $sheetog->setCellValue('AH'.$i,slope::nhk_formatnumber($item->last_c11, 3));
                    $sheetog->setCellValue('AI'.$i,slope::nhk_formatnumber($item->last_c11_1, 3));
                    $sheetog->setCellValue('AJ'.$i,slope::nhk_formatnumber($item->last_c11_2, 3));
                    $sheetog->setCellValue('AK'.$i,slope::nhk_formatnumber($item->last_c12, 3));
                    $sheetog->setCellValue('AL'.$i,slope::nhk_formatnumber($item->last_c13, 3));
                    $sheetog->setCellValue('AM'.$i,slope::nhk_formatnumber($item->last_c14, 3));
                    $sheetog->setCellValue('AN'.$i,slope::nhk_formatnumber($item->last_c15, 3));
                    $sheetog->setCellValue('AO'.$i,slope::nhk_formatnumber($item->last_c15_1, 3));
                    $sheetog->setCellValue('AP'.$i,slope::nhk_formatnumber($item->last_c5_2, 3));
                    $sheetog->setCellValue('AQ'.$i,slope::nhk_formatnumber($item->last_c16, 3));
                    $sheetog->setCellValue('AR'.$i,slope::nhk_formatnumber($item->last_c17, 3));
                    $sheetog->setCellValue('AS'.$i,slope::nhk_formatnumber($item->last_c18, 3));
                    $sheetog->setCellValue('AT'.$i,slope::nhk_formatnumber($item->last_c19, 3));
                    $sheetog->setCellValue('AU'.$i,slope::nhk_formatnumber($item->last_c19_1, 3));
                    $sheetog->setCellValue('AV'.$i,slope::nhk_formatnumber($item->last_c19_2, 3));
                    $sheetog->setCellValue('AW'.$i,slope::nhk_formatnumber($item->last_c19_3, 3));
                    $sheetog->setCellValue('AX'.$i,slope::nhk_formatnumber($item->last_c20, 3));
                    $sheetog->setCellValue('AY'.$i,slope::nhk_formatnumber($item->last_c20_1, 3));
                    $sheetog->setCellValue('AZ'.$i,slope::nhk_formatnumber($item->last_c21, 3));
                    $sheetog->setCellValue('BA'.$i,slope::nhk_formatnumber($item->last_c21_1, 3));
                    $sheetog->setCellValue('BB'.$i,slope::nhk_formatnumber($item->last_c22, 3));
                    $sheetog->setCellValue('BC'.$i,slope::nhk_formatnumber($item->last_c22_1, 3));
                    $sheetog->setCellValue('BD'.$i,slope::nhk_formatnumber($item->last_c22_2, 3));
                    $sheetog->setCellValue('BE'.$i,slope::nhk_formatnumber($item->last_c23, 3));
                    $sheetog->setCellValue('BF'.$i,slope::nhk_formatnumber($item->last_c23_1, 3));
                    $sheetog->setCellValue('BG'.$i,slope::nhk_formatnumber($item->last_c24, 3));
                    $sheetog->setCellValue('BH'.$i,slope::nhk_formatnumber($item->last_c24_1, 3));
                    $sheetog->setCellValue('BI'.$i,slope::nhk_formatnumber($item->last_c24_2, 3));
                    $sheetog->setCellValue('BJ'.$i,slope::nhk_formatnumber($item->last_c25, 3));
                    $sheetog->setCellValue('BK'.$i,slope::nhk_formatnumber($item->last_c26, 3));
                    $sheetog->setCellValue('BL'.$i,slope::nhk_formatnumber($item->last_c27, 3));
                    $sheetog->setCellValue('BM'.$i,slope::nhk_formatnumber($item->last_c28, 3));
                    $sheetog->setCellValue('BN'.$i,slope::nhk_formatnumber($item->last_c29, 3));
                    $sheetog->setCellValue('BO'.$i,slope::nhk_formatnumber($item->last_c30, 3));
                    $sheetog->setCellValue('BP'.$i,slope::nhk_formatnumber($item->last_c31, 3));
                    $sheetog->setCellValue('BQ'.$i,slope::nhk_formatnumber($item->last_c32, 3));
                    $sheetog->setCellValue('BR'.$i,slope::nhk_formatnumber($item->last_c33, 3));

                    $sheetog->setCellValue('BS'.$i,slope::nhk_formatnumber($item->last_c19_4, 3));
                    $sheetog->setCellValue('BT'.$i,slope::nhk_formatnumber($item->last_c19_5, 3));
                    $sheetog->setCellValue('BU'.$i,slope::nhk_formatnumber($item->last_c35_1, 3));
                    $sheetog->setCellValue('BV'.$i,slope::nhk_formatnumber($item->last_c35_2, 3));
                    $sheetog->setCellValue('BW'.$i,slope::nhk_formatnumber($item->spare_01, 3));
                    $sheetog->setCellValue('BX'.$i,slope::nhk_formatnumber($item->spare_02, 3));
                    $sheetog->setCellValue('BY'.$i,slope::nhk_formatnumber($item->spare_03, 3));
                    $sheetog->setCellValue('BZ'.$i,slope::nhk_formatnumber($item->spare_04, 3));
                    $sheetog->setCellValue('CA'.$i,slope::nhk_formatnumber($item->spare_05, 3));
                    $sheetog->setCellValue('CB'.$i,slope::nhk_formatnumber($item->spare_06, 3));
                    $sheetog->setCellValue('CC'.$i,slope::nhk_formatnumber($item->spare_07, 3));
                    $sheetog->setCellValue('CD'.$i,slope::nhk_formatnumber($item->spare_08, 3));
                    $sheetog->setCellValue('CE'.$i,slope::nhk_formatnumber($item->spare_09, 3));
                    $sheetog->setCellValue('CF'.$i,slope::nhk_formatnumber($item->spare_10, 3));
                    $sheetog->setCellValue('CG'.$i,slope::nhk_formatnumber($item->spare_11, 3));
                    $sheetog->setCellValue('CH'.$i,slope::nhk_formatnumber($item->spare_12, 3));
                    $sheetog->setCellValue('CI'.$i,slope::nhk_formatnumber($item->spare_13, 3));
                    $sheetog->setCellValue('CJ'.$i,slope::nhk_formatnumber($item->spare_14, 3));
                    $sheetog->setCellValue('CK'.$i,slope::nhk_formatnumber($item->spare_15, 3));
                    $sheetog->setCellValue('CL'.$i,slope::nhk_formatnumber($item->spare_16, 3));
                    $sheetog->setCellValue('CM'.$i,slope::nhk_formatnumber($item->spare_17, 3));
                    $sheetog->setCellValue('CN'.$i,slope::nhk_formatnumber($item->spare_18, 3));
                    $sheetog->setCellValue('CO'.$i,slope::nhk_formatnumber($item->spare_19, 3));
                    $sheetog->setCellValue('CP'.$i,slope::nhk_formatnumber($item->spare_20, 3));
                    $sheetog->setCellValue('CQ'.$i,slope::nhk_formatnumber($item->spare_21, 3));
                    $sheetog->setCellValue('CR'.$i,slope::nhk_formatnumber($item->spare_22, 3));
                    $sheetog->setCellValue('CS'.$i,slope::nhk_formatnumber($item->spare_23, 3));
                    $sheetog->setCellValue('CT'.$i,slope::nhk_formatnumber($item->spare_24, 3));
                    $sheetog->setCellValue('CU'.$i,slope::nhk_formatnumber($item->spare_25, 3));
                    $sheetog->setCellValue('CV'.$i,slope::nhk_formatnumber($item->spare_26, 3));
                    $sheetog->setCellValue('CW'.$i,slope::nhk_formatnumber($item->spare_27, 3));
                    $sheetog->setCellValue('CX'.$i,slope::nhk_formatnumber($item->spare_28, 3));
                    $sheetog->setCellValue('CY'.$i,slope::nhk_formatnumber($item->spare_29, 3));
                    $sheetog->setCellValue('CZ'.$i,slope::nhk_formatnumber($item->spare_30, 3));
                    $sheetog->setCellValue('DA'.$i,slope::nhk_formatnumber($item->spare_31, 3));
                    $sheetog->setCellValue('DB'.$i,slope::nhk_formatnumber($item->spare_32, 3));
                    $sheetog->setCellValue('DC'.$i,slope::nhk_formatnumber($item->spare_33, 3));
                    $sheetog->setCellValue('DD'.$i,slope::nhk_formatnumber($item->spare_34, 3));
                    $sheetog->setCellValue('DE'.$i,slope::nhk_formatnumber($item->spare_35, 3));
                    $sheetog->setCellValue('DF'.$i,slope::nhk_formatnumber($item->spare_36, 3));
                    $sheetog->setCellValue('DG'.$i,slope::nhk_formatnumber($item->spare_37, 3));

                    $sheetog->setCellValue('HL'.$i,$datafirst[3]->outgassing);
    

                    $sheetlm->setCellValue('F'.$i,date('d-M-y',strtotime($item->tga)));
                    $sheetlm->setCellValue('G'.$i,slope::nhk_formatnumber($item->gcms_low_mw, 3));
                    $sheetlm->setCellValue('H'.$i,$item->lotnumber_lab);
                    $sheetlm->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->low_MW, 0));
                    $sheetlm->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[2]->low_MW, 0));
                    $sheetlm->setCellValue('K'.$i,slope::nhk_formatnumber($datafirst[0]->low_MW, 0));
                    $sheetlm->setCellValue('L'.$i,slope::nhk_formatnumber($specification->low_MW, 0));
                    $sheetlm->setCellValue('M'.$i,$starttime);
                    $sheetlm->setCellValue('N'.$i,$ftime);
                    $sheetlm->setCellValue('Z'.$i,$datafirst[3]->low_MW);

                    $sheethm->setCellValue('F'.$i,date('d-M-y',strtotime($item->tga)));
                    $sheethm->setCellValue('G'.$i,slope::nhk_formatnumber($item->gcms_high_mw, 3));
                    $sheethm->setCellValue('H'.$i,$item->lotnumber_lab);
                    $sheethm->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->high_MW, 0));
                    $sheethm->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[2]->high_MW, 0));
                    $sheethm->setCellValue('K'.$i,slope::nhk_formatnumber($datafirst[0]->high_MW, 0));
                    $sheethm->setCellValue('L'.$i,slope::nhk_formatnumber($specification->high_MW, 0));
                    $sheethm->setCellValue('M'.$i,$starttime);
                    $sheethm->setCellValue('N'.$i,$ftime);
                    $sheethm->setCellValue('Z'.$i,$datafirst[3]->high_MW);
            
                    $sheetsm->setCellValue('F'.$i,date('d-M-y',strtotime($item->tga)));
                    $sheetsm->setCellValue('G'.$i,slope::nhk_formatnumber($item->gcms_smear, 3));
                    $sheetsm->setCellValue('H'.$i,$item->lotnumber_lab);
                    $sheetsm->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->alkylacrylate, 0));
                    $sheetsm->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[2]->alkylacrylate, 0));
                    $sheetsm->setCellValue('K'.$i,slope::nhk_formatnumber($datafirst[0]->alkylacrylate, 0));
                    $sheetsm->setCellValue('L'.$i,slope::nhk_formatnumber($specification->alkylacrylate, 0));
                    $sheetsm->setCellValue('M'.$i,$starttime);
                    $sheetsm->setCellValue('N'.$i,$ftime);
                    $sheetsm->setCellValue('Z'.$i,$datafirst[3]->alkylacrylate);
                    $i++;
                }
                $i = 34;
                foreach ($datalabgcmsdw as $item) { 
                    $starttime = date('YmdHis',strtotime($item->save_date));
                    $ftime = date('YmdHis',strtotime($item->ftime_gcms));
                    $stdate = date('Y-m-d', strtotime($item->save_date));
                    $datafirst = DB::table('nt_model_history')
                    ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model_history.id_model', $item->id_model)
                    ->where('nt_model_history.date_start','<=',$stdate)
                    ->where('nt_model_history.date_end','>=',$stdate)
                    ->where('nt_model_history.chkstatus', 1)
                    ->get();
                    if($datafirst->count() == 0)
                    {
                        $data1 = DB::table('nt_model_history')
                        ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model_history.id_model', $item->id_model)
                        ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                        ->first();
                        
                        $datafirst = DB::table('nt_model_history')
                        ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model_history.id_model', $item->id_model)
                        ->where('nt_model_history.date_start','<=',$data1->date_start)  
                        ->where('nt_model_history.date_end','>=',$data1->date_start)
                        // ->where('nt_model_history.chkstatus', 1)
                        ->get(); 
                    }
                    $sheetog->setCellValue('DI'.$i,date('d-M-y',strtotime($item->tga)));
                    $sheetog->setCellValue('DJ'.$i,slope::nhk_formatnumber($item->gcms_outgassing, 3));
                    $sheetog->setCellValue('DK'.$i,$item->lotnumber_lab);
                    $sheetog->setCellValue('DL'.$i,slope::nhk_formatnumber($datafirst[1]->outgassing, 0));
                    $sheetog->setCellValue('DM'.$i,slope::nhk_formatnumber($datafirst[2]->outgassing, 0));
                    $sheetog->setCellValue('DN'.$i,slope::nhk_formatnumber($datafirst[0]->outgassing, 0));
                    $sheetog->setCellValue('DO'.$i,slope::nhk_formatnumber($specification1->outgassing, 0));
                    $sheetog->setCellValue('DP'.$i,$starttime);
                    $sheetog->setCellValue('DQ'.$i,$ftime);
                    $sheetog->setCellValue('DR'.$i,slope::nhk_formatnumber($item->last_c1, 3));
                    $sheetog->setCellValue('DS'.$i,slope::nhk_formatnumber($item->last_c2, 3));
                    $sheetog->setCellValue('DT'.$i,slope::nhk_formatnumber($item->last_c3, 3));
                    $sheetog->setCellValue('DU'.$i,slope::nhk_formatnumber($item->last_c4, 3));
                    $sheetog->setCellValue('DV'.$i,slope::nhk_formatnumber($item->last_c5, 3));
                    $sheetog->setCellValue('DW'.$i,slope::nhk_formatnumber($item->last_c5_1, 3));
                    $sheetog->setCellValue('DX'.$i,slope::nhk_formatnumber($item->last_c5_2, 3));
                    $sheetog->setCellValue('DY'.$i,slope::nhk_formatnumber($item->last_c5_3, 3));
                    $sheetog->setCellValue('DZ'.$i,slope::nhk_formatnumber($item->last_c6, 3));
                    $sheetog->setCellValue('EA'.$i,slope::nhk_formatnumber($item->last_c6_1, 3));
                    $sheetog->setCellValue('EB'.$i,slope::nhk_formatnumber($item->last_c6_2, 3));
                    $sheetog->setCellValue('EC'.$i,slope::nhk_formatnumber($item->last_c7, 3));
                    $sheetog->setCellValue('ED'.$i,slope::nhk_formatnumber($item->last_c8, 3));
                    $sheetog->setCellValue('EE'.$i,slope::nhk_formatnumber($item->last_c8_1, 3));
                    $sheetog->setCellValue('EF'.$i,slope::nhk_formatnumber($item->last_c8_2, 3));
                    $sheetog->setCellValue('EG'.$i,slope::nhk_formatnumber($item->last_c9, 3));
                    $sheetog->setCellValue('EH'.$i,slope::nhk_formatnumber($item->last_c9_1, 3));
                    $sheetog->setCellValue('EI'.$i,slope::nhk_formatnumber($item->last_c9_2, 3));
                    $sheetog->setCellValue('EJ'.$i,slope::nhk_formatnumber($item->last_c10, 3));
                    $sheetog->setCellValue('EK'.$i,slope::nhk_formatnumber($item->last_c11, 3));
                    $sheetog->setCellValue('EL'.$i,slope::nhk_formatnumber($item->last_c11_1, 3));
                    $sheetog->setCellValue('EM'.$i,slope::nhk_formatnumber($item->last_c11_2, 3));
                    $sheetog->setCellValue('EN'.$i,slope::nhk_formatnumber($item->last_c12, 3));
                    $sheetog->setCellValue('EO'.$i,slope::nhk_formatnumber($item->last_c13, 3));
                    $sheetog->setCellValue('EP'.$i,slope::nhk_formatnumber($item->last_c14, 3));
                    $sheetog->setCellValue('EQ'.$i,slope::nhk_formatnumber($item->last_c15, 3));
                    $sheetog->setCellValue('ER'.$i,slope::nhk_formatnumber($item->last_c15_1, 3));
                    $sheetog->setCellValue('ES'.$i,slope::nhk_formatnumber($item->last_c5_2, 3));
                    $sheetog->setCellValue('ET'.$i,slope::nhk_formatnumber($item->last_c16, 3));
                    $sheetog->setCellValue('EU'.$i,slope::nhk_formatnumber($item->last_c17, 3));
                    $sheetog->setCellValue('EV'.$i,slope::nhk_formatnumber($item->last_c18, 3));
                    $sheetog->setCellValue('EW'.$i,slope::nhk_formatnumber($item->last_c19, 3));
                    $sheetog->setCellValue('EX'.$i,slope::nhk_formatnumber($item->last_c19_1, 3));
                    $sheetog->setCellValue('EY'.$i,slope::nhk_formatnumber($item->last_c19_2, 3));
                    $sheetog->setCellValue('EZ'.$i,slope::nhk_formatnumber($item->last_c19_3, 3));
                    $sheetog->setCellValue('FA'.$i,slope::nhk_formatnumber($item->last_c20, 3));
                    $sheetog->setCellValue('FB'.$i,slope::nhk_formatnumber($item->last_c20_1, 3));
                    $sheetog->setCellValue('FC'.$i,slope::nhk_formatnumber($item->last_c21, 3));
                    $sheetog->setCellValue('FD'.$i,slope::nhk_formatnumber($item->last_c21_1, 3));
                    $sheetog->setCellValue('FE'.$i,slope::nhk_formatnumber($item->last_c22, 3));
                    $sheetog->setCellValue('FF'.$i,slope::nhk_formatnumber($item->last_c22_1, 3));
                    $sheetog->setCellValue('FG'.$i,slope::nhk_formatnumber($item->last_c22_2, 3));
                    $sheetog->setCellValue('FH'.$i,slope::nhk_formatnumber($item->last_c23, 3));
                    $sheetog->setCellValue('FI'.$i,slope::nhk_formatnumber($item->last_c23_1, 3));
                    $sheetog->setCellValue('FJ'.$i,slope::nhk_formatnumber($item->last_c24, 3));
                    $sheetog->setCellValue('FK'.$i,slope::nhk_formatnumber($item->last_c24_1, 3));
                    $sheetog->setCellValue('FL'.$i,slope::nhk_formatnumber($item->last_c24_2, 3));
                    $sheetog->setCellValue('FM'.$i,slope::nhk_formatnumber($item->last_c25, 3));
                    $sheetog->setCellValue('FN'.$i,slope::nhk_formatnumber($item->last_c26, 3));
                    $sheetog->setCellValue('FO'.$i,slope::nhk_formatnumber($item->last_c27, 3));
                    $sheetog->setCellValue('FP'.$i,slope::nhk_formatnumber($item->last_c28, 3));
                    $sheetog->setCellValue('FQ'.$i,slope::nhk_formatnumber($item->last_c29, 3));
                    $sheetog->setCellValue('FR'.$i,slope::nhk_formatnumber($item->last_c30, 3));
                    $sheetog->setCellValue('FS'.$i,slope::nhk_formatnumber($item->last_c31, 3));
                    $sheetog->setCellValue('FT'.$i,slope::nhk_formatnumber($item->last_c32, 3));
                    $sheetog->setCellValue('FU'.$i,slope::nhk_formatnumber($item->last_c33, 3));

                    $sheetog->setCellValue('FV'.$i,slope::nhk_formatnumber($item->last_c19_4, 3));
                    $sheetog->setCellValue('FW'.$i,slope::nhk_formatnumber($item->last_c19_5, 3));
                    $sheetog->setCellValue('FX'.$i,slope::nhk_formatnumber($item->last_c35_1, 3));
                    $sheetog->setCellValue('FY'.$i,slope::nhk_formatnumber($item->last_c35_2, 3));
                    $sheetog->setCellValue('FZ'.$i,slope::nhk_formatnumber($item->spare_01, 3));
                    $sheetog->setCellValue('GA'.$i,slope::nhk_formatnumber($item->spare_02, 3));
                    $sheetog->setCellValue('GB'.$i,slope::nhk_formatnumber($item->spare_03, 3));
                    $sheetog->setCellValue('GC'.$i,slope::nhk_formatnumber($item->spare_04, 3));
                    $sheetog->setCellValue('GD'.$i,slope::nhk_formatnumber($item->spare_05, 3));
                    $sheetog->setCellValue('GE'.$i,slope::nhk_formatnumber($item->spare_06, 3));
                    $sheetog->setCellValue('GF'.$i,slope::nhk_formatnumber($item->spare_07, 3));
                    $sheetog->setCellValue('GG'.$i,slope::nhk_formatnumber($item->spare_08, 3));
                    $sheetog->setCellValue('GH'.$i,slope::nhk_formatnumber($item->spare_09, 3));
                    $sheetog->setCellValue('GI'.$i,slope::nhk_formatnumber($item->spare_10, 3));
                    $sheetog->setCellValue('GJ'.$i,slope::nhk_formatnumber($item->spare_11, 3));
                    $sheetog->setCellValue('GK'.$i,slope::nhk_formatnumber($item->spare_12, 3));
                    $sheetog->setCellValue('GL'.$i,slope::nhk_formatnumber($item->spare_13, 3));
                    $sheetog->setCellValue('GM'.$i,slope::nhk_formatnumber($item->spare_14, 3));
                    $sheetog->setCellValue('GN'.$i,slope::nhk_formatnumber($item->spare_15, 3));
                    $sheetog->setCellValue('GO'.$i,slope::nhk_formatnumber($item->spare_16, 3));
                    $sheetog->setCellValue('GP'.$i,slope::nhk_formatnumber($item->spare_17, 3));
                    $sheetog->setCellValue('GQ'.$i,slope::nhk_formatnumber($item->spare_18, 3));
                    $sheetog->setCellValue('GR'.$i,slope::nhk_formatnumber($item->spare_19, 3));
                    $sheetog->setCellValue('GS'.$i,slope::nhk_formatnumber($item->spare_20, 3));
                    $sheetog->setCellValue('GT'.$i,slope::nhk_formatnumber($item->spare_21, 3));
                    $sheetog->setCellValue('GU'.$i,slope::nhk_formatnumber($item->spare_22, 3));
                    $sheetog->setCellValue('GV'.$i,slope::nhk_formatnumber($item->spare_23, 3));
                    $sheetog->setCellValue('GW'.$i,slope::nhk_formatnumber($item->spare_24, 3));
                    $sheetog->setCellValue('GX'.$i,slope::nhk_formatnumber($item->spare_25, 3));
                    $sheetog->setCellValue('GY'.$i,slope::nhk_formatnumber($item->spare_26, 3));
                    $sheetog->setCellValue('GZ'.$i,slope::nhk_formatnumber($item->spare_27, 3));
                    $sheetog->setCellValue('HA'.$i,slope::nhk_formatnumber($item->spare_28, 3));
                    $sheetog->setCellValue('HB'.$i,slope::nhk_formatnumber($item->spare_29, 3));
                    $sheetog->setCellValue('HC'.$i,slope::nhk_formatnumber($item->spare_30, 3));
                    $sheetog->setCellValue('HD'.$i,slope::nhk_formatnumber($item->spare_31, 3));
                    $sheetog->setCellValue('HE'.$i,slope::nhk_formatnumber($item->spare_32, 3));
                    $sheetog->setCellValue('HF'.$i,slope::nhk_formatnumber($item->spare_33, 3));
                    $sheetog->setCellValue('HG'.$i,slope::nhk_formatnumber($item->spare_34, 3));
                    $sheetog->setCellValue('HH'.$i,slope::nhk_formatnumber($item->spare_35, 3));
                    $sheetog->setCellValue('HI'.$i,slope::nhk_formatnumber($item->spare_36, 3));
                    $sheetog->setCellValue('HJ'.$i,slope::nhk_formatnumber($item->spare_37, 3));

                    $sheetog->setCellValue('HM'.$i,$datafirst[3]->outgassing);

                    $sheetlm->setCellValue('P'.$i,date('d-M-y',strtotime($item->tga)));
                    $sheetlm->setCellValue('Q'.$i,slope::nhk_formatnumber($item->gcms_low_mw, 3));
                    $sheetlm->setCellValue('R'.$i,$item->lotnumber_lab);
                    $sheetlm->setCellValue('S'.$i,slope::nhk_formatnumber($datafirst[1]->low_MW, 0));
                    $sheetlm->setCellValue('T'.$i,slope::nhk_formatnumber($datafirst[2]->low_MW, 0));
                    $sheetlm->setCellValue('U'.$i,slope::nhk_formatnumber($datafirst[0]->low_MW, 0));
                    $sheetlm->setCellValue('V'.$i,slope::nhk_formatnumber($specification1->low_MW, 0));
                    $sheetlm->setCellValue('W'.$i,$starttime);
                    $sheetlm->setCellValue('X'.$i,$ftime);
                    $sheetlm->setCellValue('AA'.$i,$datafirst[3]->low_MW);

                    $sheethm->setCellValue('P'.$i,date('d-M-y',strtotime($item->tga)));
                    $sheethm->setCellValue('Q'.$i,slope::nhk_formatnumber($item->gcms_high_mw, 3));
                    $sheethm->setCellValue('R'.$i,$item->lotnumber_lab);
                    $sheethm->setCellValue('S'.$i,slope::nhk_formatnumber($datafirst[1]->high_MW, 0));
                    $sheethm->setCellValue('T'.$i,slope::nhk_formatnumber($datafirst[2]->high_MW, 0));
                    $sheethm->setCellValue('U'.$i,slope::nhk_formatnumber($datafirst[0]->high_MW, 0));
                    $sheethm->setCellValue('V'.$i,slope::nhk_formatnumber($specification1->high_MW, 0));
                    $sheethm->setCellValue('W'.$i,$starttime);
                    $sheethm->setCellValue('X'.$i,$ftime);
                    $sheethm->setCellValue('AA'.$i,$datafirst[3]->high_MW);

                    $sheetsm->setCellValue('P'.$i,date('d-M-y',strtotime($item->tga)));
                    $sheetsm->setCellValue('Q'.$i,slope::nhk_formatnumber($item->gcms_smear, 3));
                    $sheetsm->setCellValue('R'.$i,$item->lotnumber_lab);
                    $sheetsm->setCellValue('S'.$i,slope::nhk_formatnumber($datafirst[1]->alkylacrylate, 0));
                    $sheetsm->setCellValue('T'.$i,slope::nhk_formatnumber($datafirst[2]->alkylacrylate, 0));
                    $sheetsm->setCellValue('U'.$i,slope::nhk_formatnumber($datafirst[0]->alkylacrylate, 0));
                    $sheetsm->setCellValue('V'.$i,slope::nhk_formatnumber($specification1->alkylacrylate, 0));
                    $sheetsm->setCellValue('W'.$i,$starttime);
                    $sheetsm->setCellValue('X'.$i,$ftime);
                    $sheetsm->setCellValue('AA'.$i,$datafirst[3]->alkylacrylate);
                    $i++;
                }
                $i = 34;
                foreach ($datalabftir as $item) {     
                    $starttime = date('YmdHis',strtotime($item->save_date));
                    $ftime = date('YmdHis',strtotime($item->ftime_ftir));
                    $stdate = date('Y-m-d', strtotime($item->save_date));
                    $datafirst = DB::table('nt_model_history')
                    ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model_history.id_model', $item->id_model)
                    ->where('nt_model_history.date_start','<=',$stdate)
                    ->where('nt_model_history.date_end','>=',$stdate)
                    ->where('nt_model_history.chkstatus', 1)
                    ->get();
                    if($datafirst->count() == 0)
                    {
                        $data1 = DB::table('nt_model_history')
                        ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model_history.id_model', $item->id_model)
                        ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                        ->first();
                        
                        $datafirst = DB::table('nt_model_history')
                        ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model_history.id_model', $item->id_model)
                        ->where('nt_model_history.date_start','<=',$data1->date_start)  
                        ->where('nt_model_history.date_end','>=',$data1->date_start)
                        // ->where('nt_model_history.chkstatus', 1)
                        ->get(); 
                    }
                    $sheetftir->setCellValue('F'.$i,date('d-M-y',strtotime($item->tga)));
                    $sheetftir->setCellValue('G'.$i,slope::nhk_formatnumber($item->ftir_silicone, 1));
                    $sheetftir->setCellValue('H'.$i,$item->lotnumber_lab);
                    $sheetftir->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->ftir, 1));
                    $sheetftir->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[2]->ftir, 1));
                    $sheetftir->setCellValue('K'.$i,slope::nhk_formatnumber($datafirst[0]->ftir, 1));
                    $sheetftir->setCellValue('L'.$i,slope::nhk_formatnumber($specification->ftir, 1));
                    $sheetftir->setCellValue('M'.$i,$starttime);
                    $sheetftir->setCellValue('N'.$i,$ftime);
                    $sheetftir->setCellValue('Z'.$i,$datafirst[3]->ftir);
                    $i++;
                }
                $i = 34;
                foreach ($datalabftirdw as $item) {     
                    $starttime = date('YmdHis',strtotime($item->save_date));
                    $ftime = date('YmdHis',strtotime($item->ftime_ftir));
                    $stdate = date('Y-m-d', strtotime($item->save_date));
                    $datafirst = DB::table('nt_model_history')
                    ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model_history.id_model', $item->id_model)
                    ->where('nt_model_history.date_start','<=',$stdate)
                    ->where('nt_model_history.date_end','>=',$stdate)
                    ->where('nt_model_history.chkstatus', 1)
                    ->get();
                    if($datafirst->count() == 0)
                    {
                        $data1 = DB::table('nt_model_history')
                        ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model_history.id_model', $item->id_model)
                        ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                        ->first();
                        
                        $datafirst = DB::table('nt_model_history')
                        ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model_history.id_model', $item->id_model)
                        ->where('nt_model_history.date_start','<=',$data1->date_start)  
                        ->where('nt_model_history.date_end','>=',$data1->date_start)
                        // ->where('nt_model_history.chkstatus', 1)
                        ->get(); 
                    }
                    $sheetftir->setCellValue('P'.$i,date('d-M-y',strtotime($item->tga)));
                    $sheetftir->setCellValue('Q'.$i,slope::nhk_formatnumber($item->ftir_silicone, 1));
                    $sheetftir->setCellValue('R'.$i,$item->lotnumber_lab);
                    $sheetftir->setCellValue('S'.$i,slope::nhk_formatnumber($datafirst[1]->ftir, 1));
                    $sheetftir->setCellValue('T'.$i,slope::nhk_formatnumber($datafirst[2]->ftir, 1));
                    $sheetftir->setCellValue('U'.$i,slope::nhk_formatnumber($datafirst[0]->ftir, 1));
                    $sheetftir->setCellValue('V'.$i,slope::nhk_formatnumber($specification1->ftir, 1));
                    $sheetftir->setCellValue('W'.$i,$starttime);
                    $sheetftir->setCellValue('X'.$i,$ftime);
                    $sheetftir->setCellValue('AA'.$i,$datafirst[3]->ftir);
                    $i++;
                }
            })->setFilename($namefile)->store('xlsx', storage_path('NHK-File/report/graphexcel'));
        $chk = 1; 
        return response()->json(['success' =>  $chk,'filename' => $namefile.'.xlsx' ]);
    }    
    
    public function test__raw_exportexcel(Request $request)	
    {

        ini_set('memory_limit', '-1');
        $chkexport = [];
        if(isset($request->id))
        {
            if(count($request->id) == 2)
            {
            $a1 = DB::table('nt_model')->where('id_model', $request->id[0])->first();
            $a2 = DB::table('nt_model')->where('id_model', $request->id[1])->first();
            $text1 = $a1->name_model;
            $text2 = $a2->name_model;
            $chktext1 = mb_substr($text1,0,-3);
            $chktext2 = mb_substr($text2,0,-3);
                if($chktext1 == $chktext2)
                {
                    if($a1->part == "UP")
                    {
                        $model1 = $a1;
                    }
                    else
                    {
                        $model1 = $a2;
                    }
                    if($a2->part == "DN")
                    {
                        $model2 = $a2;
                    }
                    else
                    {
                        $model2 = $a1;
                    }
                    $dateend1 = date('Y-m-d 00:00:00', strtotime($request->input('dateend'.$model1->id_model). ' + 1 day'));
                    $dateend2 = date('Y-m-d 00:00:00', strtotime($request->input('dateend'.$model2->id_model). ' + 1 day'));
                    $datalablpc = DB::table('nt_datacf_lpc')
                    ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_datacf_lpc.id_createlab')
                    ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                    ->where('nt_oqa_lab.save_date','>=',$request->input('datestart'.$model1->id_model))
                    ->where('nt_oqa_lab.save_date','<=',$dateend1)
                    ->where('nt_oqa_lab.id_model',$model1->id_model)
                    ->where('nt_oqa_lab.lpc_confirm_st',1)
                    ->orderBy('nt_oqa_lab.save_date', 'asc')->get();

                    $datalablpcdw = DB::table('nt_datacf_lpc')
                    ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_datacf_lpc.id_createlab')
                    ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                    ->where('nt_oqa_lab.save_date','>=',$request->input('datestart'.$model2->id_model))
                    ->where('nt_oqa_lab.save_date','<=',$dateend2)
                    ->where('nt_oqa_lab.id_model',$model2->id_model)
                    ->where('nt_oqa_lab.lpc_confirm_st',1)
                    ->orderBy('nt_oqa_lab.save_date', 'asc')->get();
                
                    $datalabsem = DB::table('nt_datacf_sem')
                    ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_datacf_sem.id_createlab')
                    ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                    ->where('nt_oqa_lab.save_date','>=',$request->input('datestart'.$model1->id_model))
                    ->where('nt_oqa_lab.save_date','<=',$dateend1)
                    ->where('nt_oqa_lab.id_model',$model1->id_model)
                    ->where('nt_oqa_lab.sem_confirm_st',1)
                    ->orderBy('nt_oqa_lab.save_date', 'asc')->get();
                    $datalabsemdw = DB::table('nt_datacf_sem')
                    ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_datacf_sem.id_createlab')
                    ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                    ->where('nt_oqa_lab.save_date','>=',$request->input('datestart'.$model1->id_model))
                    ->where('nt_oqa_lab.save_date','<=',$dateend2)
                    ->where('nt_oqa_lab.id_model',$model2->id_model)
                    ->where('nt_oqa_lab.sem_confirm_st',1)
                    ->orderBy('nt_oqa_lab.save_date', 'asc')->get();
        
                    $datalabic = DB::table('nt_datacf_ic')
                    ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_datacf_ic.id_createlab')
                    ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                    ->where('nt_oqa_lab.save_date','>=',$request->input('datestart'.$model1->id_model))
                    ->where('nt_oqa_lab.save_date','<=',$dateend1)
                    ->where('nt_oqa_lab.id_model',$model1->id_model)
                    ->where('nt_oqa_lab.ic_confirm_st',1)
                    ->orderBy('nt_oqa_lab.save_date', 'asc')->get();
                    

                    $datalabicdw = DB::table('nt_datacf_ic')
                    ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_datacf_ic.id_createlab')
                    ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                    ->where('nt_oqa_lab.save_date','>=',$request->input('datestart'.$model2->id_model))
                    ->where('nt_oqa_lab.save_date','<=',$dateend2)
                    ->where('nt_oqa_lab.id_model',$model2->id_model)
                    ->where('nt_oqa_lab.ic_confirm_st',1)
                    ->orderBy('nt_oqa_lab.save_date', 'asc')->get();
                
                    $datalabgcms = DB::table('nt_datacf_gcms')
                    ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_datacf_gcms.id_createlab')
                    ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                    ->leftjoin('nt_outgassing_gcms','nt_outgassing_gcms.id_createlab','=','nt_oqa_lab.id_createlab')
                    ->where('nt_oqa_lab.save_date','>=',$request->input('datestart'.$model1->id_model))
                    ->where('nt_oqa_lab.save_date','<=',$dateend1)
                    ->where('nt_oqa_lab.id_model',$model1->id_model)
                    ->where('nt_oqa_lab.gcms_confirm_st',1)
                    ->orderBy('nt_oqa_lab.save_date', 'asc')->get();
                    $datalabgcmsdw = DB::table('nt_datacf_gcms')
                    ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_datacf_gcms.id_createlab')
                    ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                    ->leftjoin('nt_outgassing_gcms','nt_outgassing_gcms.id_createlab','=','nt_oqa_lab.id_createlab')
                    ->where('nt_oqa_lab.save_date','>=',$request->input('datestart'.$model2->id_model))
                    ->where('nt_oqa_lab.save_date','<=',$dateend2)
                    ->where('nt_oqa_lab.id_model',$model2->id_model)
                    ->where('nt_oqa_lab.gcms_confirm_st',1)
                    ->orderBy('nt_oqa_lab.save_date', 'asc')->get();
                
                    $datalabftir = DB::table('nt_datacf_ftir')
                    ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_datacf_ftir.id_createlab')
                    ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                    ->where('nt_oqa_lab.save_date','>=',$request->input('datestart'.$model1->id_model))
                    ->where('nt_oqa_lab.save_date','<=',$dateend1)
                    ->where('nt_oqa_lab.id_model',$model1->id_model)
                    ->where('nt_oqa_lab.ftir_confirm_st',1)
                    ->orderBy('nt_oqa_lab.save_date', 'asc')->get();
                    $datalabftirdw = DB::table('nt_datacf_ftir')
                    ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_datacf_ftir.id_createlab')
                    ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                    ->where('nt_oqa_lab.save_date','>=',$request->input('datestart'.$model2->id_model))
                    ->where('nt_oqa_lab.save_date','<=',$dateend2)
                    ->where('nt_oqa_lab.id_model',$model2->id_model)
                    ->where('nt_oqa_lab.ftir_confirm_st',1)
                    ->orderBy('nt_oqa_lab.save_date', 'asc')->get();

                }
                else
                {
                return response()->json(['success' => 2]);
                }
            }
            else
            {
            return response()->json(['success' => 3]);
            }
        }
        else
        {
        return response()->json(['success' => 4]);
        }
    
    
        $specification = DB::table('nt_model_spec')->where('id_model', $model1->id_model)->where('id_model_type', 1)->first();
        $triggerlimit = DB::table('nt_model_spec')->where('id_model', $model1->id_model)->where('id_model_type', 2)->first();
        $ucl = DB::table('nt_model_spec')->where('id_model', $model1->id_model)->where('id_model_type', 3)->first();
        $lcl = DB::table('nt_model_spec')->where('id_model', $model1->id_model)->where('id_model_type', 4)->first();
        $cl = DB::table('nt_model_spec')->where('id_model', $model1->id_model)->where('id_model_type', 5)->first();

        $specification1 = DB::table('nt_model_spec')->where('id_model', $model2->id_model)->where('id_model_type', 1)->first();
        $triggerlimit1 = DB::table('nt_model_spec')->where('id_model', $model2->id_model)->where('id_model_type', 2)->first();
        $ucl1 = DB::table('nt_model_spec')->where('id_model', $model2->id_model)->where('id_model_type', 3)->first();
        $lcl1 = DB::table('nt_model_spec')->where('id_model', $model2->id_model)->where('id_model_type', 4)->first();
        $cl1 = DB::table('nt_model_spec')->where('id_model', $model2->id_model)->where('id_model_type', 5)->first();
        $file = storage_path('NHK-File/template/report/Excel_Raw_Customer_r05.xlsx');
        $filetime = date('YmdHis');
        //$namefile = 'graphexcel'.$filetime;
        $spacename = str_replace(' ', '', $chktext1);
        // $namefile = 'test_Excel_Raw_'.$spacename.'_'.date("YmdHis");
        $namefile = 'Excel_Raw_'.$spacename.'_'.date("YmdHis");
        
        Excel::load($file, function ($doc)use($specification,$triggerlimit,$ucl,$lcl,$cl,$cl1,$specification1,$triggerlimit1,$ucl1,$lcl1,$datalablpc,$datalabsem,$datalabic,$datalabgcms,$datalabftir,$datalablpcdw,$datalabsemdw,$datalabicdw,$datalabgcmsdw,$datalabftirdw) {
            $sheet = $doc->setActiveSheetIndex(0);
            $sheetlpc = $doc->setActiveSheetIndex(1);
            $sheetclass1 = $doc->setActiveSheetIndex(2);
            $sheetclass2 = $doc->setActiveSheetIndex(3);
            $sheetclass3 = $doc->setActiveSheetIndex(4);
            $sheetclass3_1 = $doc->setActiveSheetIndex(5);
            $sheetclass3_2 = $doc->setActiveSheetIndex(6);
            $sheetclass3_3 = $doc->setActiveSheetIndex(7);
            $sheetclass4 = $doc->setActiveSheetIndex(8);
            $sheetclass5 = $doc->setActiveSheetIndex(9);
            $sheetclass5_1 = $doc->setActiveSheetIndex(10);
            $sheetclass6 = $doc->setActiveSheetIndex(11);
            $sheetanion = $doc->setActiveSheetIndex(12);
            $sheetsulfate = $doc->setActiveSheetIndex(13);
            $sheetcation = $doc->setActiveSheetIndex(14);
            $sheetog = $doc->setActiveSheetIndex(15);
            $sheetlm = $doc->setActiveSheetIndex(16);
            $sheethm = $doc->setActiveSheetIndex(17);
            $sheetsm = $doc->setActiveSheetIndex(18);
            $sheetftir = $doc->setActiveSheetIndex(19);
            $i = 34;
            
            foreach ($datalablpc as $item) {     
                $starttime = date('YmdHis',strtotime($item->save_date));
                $ftime = date('YmdHis',strtotime($item->ftime_lpc));
                $stdate = date('Y-m-d', strtotime($item->save_date));
                
                $datafirst = DB::table('nt_model_history')
                    ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model_history.id_model', $item->id_model)
                    ->where('nt_model_history.date_start','<=',$stdate)
                    ->where('nt_model_history.date_end','>=',$stdate)
                    ->get();
                if($datafirst->count() == 0)
                {
                    $data1 = DB::table('nt_model_history')
                    ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model_history.id_model', $item->id_model)
                    ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                    ->first();
                    
                    $datafirst = DB::table('nt_model_history')
                    ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model_history.id_model', $item->id_model)
                    ->where('nt_model_history.date_start','<=',$data1->date_start)  
                    ->where('nt_model_history.date_end','>=',$data1->date_start)
                    ->get(); 
                }
                    
                $sheetlpc->setCellValue('F'.$i, date('d-M-y', strtotime($item->tga ?: $item->save_date)));
                $sheetlpc->setCellValue('G'.$i,slope::nhk_formatnumber($item->lpc1, 0));
                $sheetlpc->setCellValue('H'.$i,slope::nhk_formatnumber($item->lpc2, 0));
                $sheetlpc->setCellValue('I'.$i,slope::nhk_formatnumber($item->lpc3, 0));
                $sheetlpc->setCellValue('J'.$i,slope::nhk_formatnumber($item->lpc_data, 0));
                $sheetlpc->setCellValue('K'.$i,$item->lotnumber_lab);
                $sheetlpc->setCellValue('L'.$i,slope::nhk_formatnumber($datafirst[1]->alpc, 0));
                $sheetlpc->setCellValue('M'.$i,slope::nhk_formatnumber($datafirst[2]->alpc, 0));
                $sheetlpc->setCellValue('N'.$i,slope::nhk_formatnumber($datafirst[0]->alpc, 0));
                $sheetlpc->setCellValue('O'.$i,slope::nhk_formatnumber($specification->alpc, 0));
                $sheetlpc->setCellValue('P'.$i,$starttime);
                $sheetlpc->setCellValue('Q'.$i,$ftime);
                $sheetlpc->setCellValue('AF'.$i,$datafirst[3]->alpc);
                
                $i++;
            }
            $i = 34; 
            foreach ($datalablpcdw as $item) {     
                $starttime = date('YmdHis',strtotime($item->save_date));
                $ftime = date('YmdHis',strtotime($item->ftime_lpc));
                $stdate = date('Y-m-d', strtotime($item->save_date));
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$stdate)
                ->where('nt_model_history.date_end','>=',$stdate)
                ->where('nt_model_history.chkstatus', 1)
                ->get();
                if($datafirst->count() == 0)
                {
                    $data1 = DB::table('nt_model_history')
                    ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model_history.id_model', $item->id_model)
                    ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                    ->first();
                    
                    $datafirst = DB::table('nt_model_history')
                    ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model_history.id_model', $item->id_model)
                    ->where('nt_model_history.date_start','<=',$data1->date_start)  
                    ->where('nt_model_history.date_end','>=',$data1->date_start)
                    ->get(); 
                }
                $sheetlpc->setCellValue('S'.$i, date('d-M-y', strtotime($item->tga ?: $item->save_date)));
                $sheetlpc->setCellValue('T'.$i,slope::nhk_formatnumber($item->lpc1, 0));
                $sheetlpc->setCellValue('U'.$i,slope::nhk_formatnumber($item->lpc2, 0));
                $sheetlpc->setCellValue('V'.$i,slope::nhk_formatnumber($item->lpc3, 0));
                $sheetlpc->setCellValue('W'.$i,slope::nhk_formatnumber($item->lpc_data, 0));
                $sheetlpc->setCellValue('X'.$i,$item->lotnumber_lab);
                $sheetlpc->setCellValue('Y'.$i,slope::nhk_formatnumber($datafirst[1]->alpc, 0));
                $sheetlpc->setCellValue('Z'.$i,slope::nhk_formatnumber($datafirst[2]->alpc, 0));
                $sheetlpc->setCellValue('AA'.$i,slope::nhk_formatnumber($datafirst[0]->alpc, 0));
                $sheetlpc->setCellValue('AB'.$i,slope::nhk_formatnumber($specification1->alpc, 0));
                $sheetlpc->setCellValue('AC'.$i,$starttime);
                $sheetlpc->setCellValue('AD'.$i,$ftime);
                $sheetlpc->setCellValue('AG'.$i,$datafirst[3]->alpc);
                $i++;
            }
            
            $i = 34;
            foreach ($datalabsem as $item) {     
                $starttime = date('YmdHis',strtotime($item->save_date));
                $ftime = date('YmdHis',strtotime($item->ftime_sem));
                $stdate = date('Y-m-d', strtotime($item->save_date));
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$stdate)
                ->where('nt_model_history.date_end','>=',$stdate)
                ->where('nt_model_history.chkstatus', 1)
                ->get();
                if($datafirst->count() == 0)
                {
                    $data1 = DB::table('nt_model_history')
                    ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model_history.id_model', $item->id_model)
                    ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                    ->first();
                    
                    $datafirst = DB::table('nt_model_history')
                    ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model_history.id_model', $item->id_model)
                    ->where('nt_model_history.date_start','<=',$data1->date_start)  
                    ->where('nt_model_history.date_end','>=',$data1->date_start)
                    ->get(); 
                }  
                $sheetclass1->setCellValue('F'.$i, date('d-M-y', strtotime($item->tga ?: $item->save_date)));
                $sheetclass1->setCellValue('G'.$i,slope::nhk_formatnumber($item->sem_class1, 1));
                $sheetclass1->setCellValue('H'.$i,$item->lotnumber_lab);
                $sheetclass1->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->class1, 1));
                $sheetclass1->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[2]->class1, 1));
                $sheetclass1->setCellValue('K'.$i,slope::nhk_formatnumber($datafirst[0]->class1, 1));
                $sheetclass1->setCellValue('L'.$i,slope::nhk_formatnumber($specification->class1, 1));
                $sheetclass1->setCellValue('M'.$i,$starttime);
                $sheetclass1->setCellValue('N'.$i,$ftime);
                $sheetclass1->setCellValue('Z'.$i,$datafirst[3]->class1);

                $sheetclass2->setCellValue('F'.$i,date('d-M-y',strtotime($item->save_date)));
                $sheetclass2->setCellValue('G'.$i,slope::nhk_formatnumber($item->sem_class2, 1));
                $sheetclass2->setCellValue('H'.$i,$item->lotnumber_lab);
                $sheetclass2->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->class2, 1));
                $sheetclass2->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[2]->class2, 1));
                $sheetclass2->setCellValue('K'.$i,slope::nhk_formatnumber($datafirst[0]->class2, 1));
                $sheetclass2->setCellValue('L'.$i,slope::nhk_formatnumber($specification->class2, 1));
                $sheetclass2->setCellValue('M'.$i,$starttime);
                $sheetclass2->setCellValue('N'.$i,$ftime);
                $sheetclass2->setCellValue('Z'.$i,$datafirst[3]->class2);

                $sheetclass3->setCellValue('F'.$i,date('d-M-y',strtotime($item->save_date)));
                $sheetclass3->setCellValue('G'.$i,slope::nhk_formatnumber($item->sem_class3, 1));
                $sheetclass3->setCellValue('H'.$i,$item->lotnumber_lab);
                $sheetclass3->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->class3, 1));
                $sheetclass3->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[2]->class3, 1));
                $sheetclass3->setCellValue('K'.$i,slope::nhk_formatnumber($datafirst[0]->class3, 1));
                $sheetclass3->setCellValue('L'.$i,slope::nhk_formatnumber($specification->class3, 1));
                $sheetclass3->setCellValue('M'.$i,$starttime);
                $sheetclass3->setCellValue('N'.$i,$ftime);
                $sheetclass3->setCellValue('Z'.$i,$datafirst[3]->class3);

                $sheetclass3_1->setCellValue('F'.$i,date('d-M-y',strtotime($item->save_date)));
                $sheetclass3_1->setCellValue('G'.$i,slope::nhk_formatnumber($item->sem_class3_1, 1));
                $sheetclass3_1->setCellValue('H'.$i,$item->lotnumber_lab);
                $sheetclass3_1->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->class3_1, 1));
                $sheetclass3_1->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[2]->class3_1, 1));
                $sheetclass3_1->setCellValue('K'.$i,slope::nhk_formatnumber($datafirst[0]->class3_1, 1));
                $sheetclass3_1->setCellValue('L'.$i,slope::nhk_formatnumber($specification->class3_1, 1));
                $sheetclass3_1->setCellValue('M'.$i,$starttime);
                $sheetclass3_1->setCellValue('N'.$i,$ftime);
                $sheetclass3_1->setCellValue('Z'.$i,$datafirst[3]->class3_1);

                $sheetclass3_2->setCellValue('F'.$i,date('d-M-y',strtotime($item->save_date)));
                $sheetclass3_2->setCellValue('G'.$i,slope::nhk_formatnumber($item->sem_class3_2, 1));
                $sheetclass3_2->setCellValue('H'.$i,$item->lotnumber_lab);
                $sheetclass3_2->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->class3_2, 1));
                $sheetclass3_2->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[2]->class3_2, 1));
                $sheetclass3_2->setCellValue('K'.$i,slope::nhk_formatnumber($datafirst[0]->class3_2, 1));
                $sheetclass3_2->setCellValue('L'.$i,slope::nhk_formatnumber($specification->class3_2, 1));
                $sheetclass3_2->setCellValue('M'.$i,$starttime);
                $sheetclass3_2->setCellValue('N'.$i,$ftime);
                $sheetclass3_2->setCellValue('Z'.$i,$datafirst[3]->class3_2);

                $sheetclass3_3->setCellValue('F'.$i,date('d-M-y',strtotime($item->save_date)));
                $sheetclass3_3->setCellValue('G'.$i,slope::nhk_formatnumber($item->sem_class3_3, 1));
                $sheetclass3_3->setCellValue('H'.$i,$item->lotnumber_lab);
                $sheetclass3_3->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->class3_3, 1));
                $sheetclass3_3->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[2]->class3_3, 1));
                $sheetclass3_3->setCellValue('K'.$i,slope::nhk_formatnumber($datafirst[0]->class3_3, 1));
                $sheetclass3_3->setCellValue('L'.$i,slope::nhk_formatnumber($specification->class3_3, 1));
                $sheetclass3_3->setCellValue('M'.$i,$starttime);
                $sheetclass3_3->setCellValue('N'.$i,$ftime);
                $sheetclass3_3->setCellValue('Z'.$i,$datafirst[3]->class3_3);

                $sheetclass4->setCellValue('F'.$i,date('d-M-y',strtotime($item->save_date)));
                $sheetclass4->setCellValue('G'.$i,slope::nhk_formatnumber($item->sem_class4, 1));
                $sheetclass4->setCellValue('H'.$i,$item->lotnumber_lab);
                $sheetclass4->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->class4, 1));
                $sheetclass4->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[2]->class4, 1));
                $sheetclass4->setCellValue('K'.$i,slope::nhk_formatnumber($datafirst[0]->class4, 1));
                $sheetclass4->setCellValue('L'.$i,slope::nhk_formatnumber($specification->class4, 1));
                $sheetclass4->setCellValue('M'.$i,$starttime);
                $sheetclass4->setCellValue('N'.$i,$ftime);
                $sheetclass4->setCellValue('Z'.$i,$datafirst[3]->class4);

                $sheetclass5->setCellValue('F'.$i,date('d-M-y',strtotime($item->save_date)));
                $sheetclass5->setCellValue('G'.$i,slope::nhk_formatnumber($item->sem_class5, 1));
                $sheetclass5->setCellValue('H'.$i,$item->lotnumber_lab);
                $sheetclass5->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->class5, 1));
                $sheetclass5->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[2]->class5, 1));
                $sheetclass5->setCellValue('K'.$i,slope::nhk_formatnumber($datafirst[0]->class5, 1));
                $sheetclass5->setCellValue('L'.$i,slope::nhk_formatnumber($specification->class5, 1));
                $sheetclass5->setCellValue('M'.$i,$starttime);
                $sheetclass5->setCellValue('N'.$i,$ftime);
                $sheetclass5->setCellValue('Z'.$i,$datafirst[3]->class5);

                $sheetclass5_1->setCellValue('F'.$i,date('d-M-y',strtotime($item->save_date)));
                $sheetclass5_1->setCellValue('G'.$i,slope::nhk_formatnumber($item->sem_class5_1, 1));
                $sheetclass5_1->setCellValue('H'.$i,$item->lotnumber_lab);
                $sheetclass5_1->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->class5_1, 1));
                $sheetclass5_1->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[2]->class5_1, 1));
                $sheetclass5_1->setCellValue('K'.$i,slope::nhk_formatnumber($datafirst[0]->class5_1, 1));
                $sheetclass5_1->setCellValue('L'.$i,slope::nhk_formatnumber($specification->class5_1, 1));
                $sheetclass5_1->setCellValue('M'.$i,$starttime);
                $sheetclass5_1->setCellValue('N'.$i,$ftime);
                $sheetclass5_1->setCellValue('Z'.$i,$datafirst[3]->class5_1);

                $sheetclass6->setCellValue('F'.$i,date('d-M-y',strtotime($item->save_date)));
                $sheetclass6->setCellValue('G'.$i,slope::nhk_formatnumber($item->sem_class6, 1));
                $sheetclass6->setCellValue('H'.$i,$item->lotnumber_lab);
                $sheetclass6->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->class6, 1));
                $sheetclass6->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[2]->class6, 1));
                $sheetclass6->setCellValue('K'.$i,slope::nhk_formatnumber($datafirst[0]->class6, 1));
                $sheetclass6->setCellValue('L'.$i,slope::nhk_formatnumber($specification->class6, 1));
                $sheetclass6->setCellValue('M'.$i,$starttime);
                $sheetclass6->setCellValue('N'.$i,$ftime);
                $sheetclass6->setCellValue('Z'.$i,$datafirst[3]->class6);

                $i++;
            }
            $i = 34;
            foreach ($datalabsemdw as $item) {     
                $starttime = date('YmdHis',strtotime($item->save_date));
                $ftime = date('YmdHis',strtotime($item->ftime_sem));
                $stdate = date('Y-m-d', strtotime($item->save_date));
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$stdate)
                ->where('nt_model_history.date_end','>=',$stdate)
                ->where('nt_model_history.chkstatus', 1)
                ->get();
                if($datafirst->count() == 0)
                {
                    $data1 = DB::table('nt_model_history')
                    ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model_history.id_model', $item->id_model)
                    ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                    ->first();
                    
                    $datafirst = DB::table('nt_model_history')
                    ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model_history.id_model', $item->id_model)
                    ->where('nt_model_history.date_start','<=',$data1->date_start)  
                    ->where('nt_model_history.date_end','>=',$data1->date_start)
                    ->get(); 
                }
                $sheetclass1->setCellValue('P'.$i,date('d-M-y', strtotime($item->tga ?: $item->save_date)));
                $sheetclass1->setCellValue('Q'.$i,slope::nhk_formatnumber($item->sem_class1, 1));
                $sheetclass1->setCellValue('R'.$i,$item->lotnumber_lab);
                $sheetclass1->setCellValue('S'.$i,slope::nhk_formatnumber($datafirst[1]->class1, 1));
                $sheetclass1->setCellValue('T'.$i,slope::nhk_formatnumber($datafirst[2]->class1, 1));
                $sheetclass1->setCellValue('U'.$i,slope::nhk_formatnumber($datafirst[0]->class1, 1));
                $sheetclass1->setCellValue('V'.$i,slope::nhk_formatnumber($specification1->class1, 1));
                $sheetclass1->setCellValue('W'.$i,$starttime);
                $sheetclass1->setCellValue('X'.$i,$ftime);
                $sheetclass1->setCellValue('AA'.$i,$datafirst[3]->class1);

                $sheetclass2->setCellValue('P'.$i,date('d-M-y',strtotime($item->save_date)));
                $sheetclass2->setCellValue('Q'.$i,slope::nhk_formatnumber($item->sem_class2, 1));
                $sheetclass2->setCellValue('R'.$i,$item->lotnumber_lab);
                $sheetclass2->setCellValue('S'.$i,slope::nhk_formatnumber($datafirst[1]->class2, 1));
                $sheetclass2->setCellValue('T'.$i,slope::nhk_formatnumber($datafirst[2]->class2, 1));
                $sheetclass2->setCellValue('U'.$i,slope::nhk_formatnumber($datafirst[0]->class2, 1));
                $sheetclass2->setCellValue('V'.$i,slope::nhk_formatnumber($specification1->class2, 1));
                $sheetclass2->setCellValue('W'.$i,$starttime);
                $sheetclass2->setCellValue('X'.$i,$ftime);
                $sheetclass2->setCellValue('AA'.$i,$datafirst[3]->class2);

                $sheetclass3->setCellValue('P'.$i,date('d-M-y',strtotime($item->save_date)));
                $sheetclass3->setCellValue('Q'.$i,slope::nhk_formatnumber($item->sem_class3, 1));
                $sheetclass3->setCellValue('R'.$i,$item->lotnumber_lab);
                $sheetclass3->setCellValue('S'.$i,slope::nhk_formatnumber($datafirst[1]->class3, 1));
                $sheetclass3->setCellValue('T'.$i,slope::nhk_formatnumber($datafirst[2]->class3, 1));
                $sheetclass3->setCellValue('U'.$i,slope::nhk_formatnumber($datafirst[0]->class3, 1));
                $sheetclass3->setCellValue('V'.$i,slope::nhk_formatnumber($specification1->class3, 1));
                $sheetclass3->setCellValue('W'.$i,$starttime);
                $sheetclass3->setCellValue('X'.$i,$ftime);
                $sheetclass3->setCellValue('AA'.$i,$datafirst[3]->class3);

                $sheetclass3_1->setCellValue('P'.$i,date('d-M-y',strtotime($item->save_date)));
                $sheetclass3_1->setCellValue('Q'.$i,slope::nhk_formatnumber($item->sem_class3_1, 1));
                $sheetclass3_1->setCellValue('R'.$i,$item->lotnumber_lab);
                $sheetclass3_1->setCellValue('S'.$i,slope::nhk_formatnumber($datafirst[1]->class3_1, 1));
                $sheetclass3_1->setCellValue('T'.$i,slope::nhk_formatnumber($datafirst[2]->class3_1, 1));
                $sheetclass3_1->setCellValue('U'.$i,slope::nhk_formatnumber($datafirst[0]->class3_1, 1));
                $sheetclass3_1->setCellValue('V'.$i,slope::nhk_formatnumber($specification1->class3_1, 1));
                $sheetclass3_1->setCellValue('W'.$i,$starttime);
                $sheetclass3_1->setCellValue('X'.$i,$ftime);
                $sheetclass3_1->setCellValue('AA'.$i,$datafirst[3]->class3_1);

                $sheetclass3_2->setCellValue('P'.$i,date('d-M-y',strtotime($item->save_date)));
                $sheetclass3_2->setCellValue('Q'.$i,slope::nhk_formatnumber($item->sem_class3_2, 1));
                $sheetclass3_2->setCellValue('R'.$i,$item->lotnumber_lab);
                $sheetclass3_2->setCellValue('S'.$i,slope::nhk_formatnumber($datafirst[1]->class3_2, 1));
                $sheetclass3_2->setCellValue('T'.$i,slope::nhk_formatnumber($datafirst[2]->class3_2, 1));
                $sheetclass3_2->setCellValue('U'.$i,slope::nhk_formatnumber($datafirst[0]->class3_2, 1));
                $sheetclass3_2->setCellValue('V'.$i,slope::nhk_formatnumber($specification1->class3_2, 1));
                $sheetclass3_2->setCellValue('W'.$i,$starttime);
                $sheetclass3_2->setCellValue('X'.$i,$ftime);
                $sheetclass3_2->setCellValue('AA'.$i,$datafirst[3]->class3_2);

                $sheetclass3_3->setCellValue('P'.$i,date('d-M-y',strtotime($item->save_date)));
                $sheetclass3_3->setCellValue('Q'.$i,slope::nhk_formatnumber($item->sem_class3_3, 1));
                $sheetclass3_3->setCellValue('R'.$i,$item->lotnumber_lab);
                $sheetclass3_3->setCellValue('S'.$i,slope::nhk_formatnumber($datafirst[1]->class3_3, 1));
                $sheetclass3_3->setCellValue('T'.$i,slope::nhk_formatnumber($datafirst[2]->class3_3, 1));
                $sheetclass3_3->setCellValue('U'.$i,slope::nhk_formatnumber($datafirst[0]->class3_3, 1));
                $sheetclass3_3->setCellValue('V'.$i,slope::nhk_formatnumber($specification1->class3_3, 1));
                $sheetclass3_3->setCellValue('W'.$i,$starttime);
                $sheetclass3_3->setCellValue('X'.$i,$ftime);
                $sheetclass3_3->setCellValue('AA'.$i,$datafirst[3]->class3_3);

                $sheetclass4->setCellValue('P'.$i,date('d-M-y',strtotime($item->save_date)));
                $sheetclass4->setCellValue('Q'.$i,slope::nhk_formatnumber($item->sem_class4, 1));
                $sheetclass4->setCellValue('R'.$i,$item->lotnumber_lab);
                $sheetclass4->setCellValue('S'.$i,slope::nhk_formatnumber($datafirst[1]->class4, 1));
                $sheetclass4->setCellValue('T'.$i,slope::nhk_formatnumber($datafirst[2]->class4, 1));
                $sheetclass4->setCellValue('U'.$i,slope::nhk_formatnumber($datafirst[0]->class4, 1));
                $sheetclass4->setCellValue('V'.$i,slope::nhk_formatnumber($specification1->class4, 1));
                $sheetclass4->setCellValue('W'.$i,$starttime);
                $sheetclass4->setCellValue('X'.$i,$ftime);
                $sheetclass4->setCellValue('AA'.$i,$datafirst[3]->class4);

                $sheetclass5->setCellValue('P'.$i,date('d-M-y',strtotime($item->save_date)));
                $sheetclass5->setCellValue('Q'.$i,slope::nhk_formatnumber($item->sem_class5, 1));
                $sheetclass5->setCellValue('R'.$i,$item->lotnumber_lab);
                $sheetclass5->setCellValue('S'.$i,slope::nhk_formatnumber($datafirst[1]->class5, 1));
                $sheetclass5->setCellValue('T'.$i,slope::nhk_formatnumber($datafirst[2]->class5, 1));
                $sheetclass5->setCellValue('U'.$i,slope::nhk_formatnumber($datafirst[0]->class5, 1));
                $sheetclass5->setCellValue('V'.$i,slope::nhk_formatnumber($specification1->class5, 1));
                $sheetclass5->setCellValue('W'.$i,$starttime);
                $sheetclass5->setCellValue('X'.$i,$ftime);
                $sheetclass5->setCellValue('AA'.$i,$datafirst[3]->class5);

                $sheetclass5_1->setCellValue('P'.$i,date('d-M-y',strtotime($item->save_date)));
                $sheetclass5_1->setCellValue('Q'.$i,slope::nhk_formatnumber($item->sem_class5_1, 1));
                $sheetclass5_1->setCellValue('R'.$i,$item->lotnumber_lab);
                $sheetclass5_1->setCellValue('S'.$i,slope::nhk_formatnumber($datafirst[1]->class5_1, 1));
                $sheetclass5_1->setCellValue('T'.$i,slope::nhk_formatnumber($datafirst[2]->class5_1, 1));
                $sheetclass5_1->setCellValue('U'.$i,slope::nhk_formatnumber($datafirst[0]->class5_1, 1));
                $sheetclass5_1->setCellValue('V'.$i,slope::nhk_formatnumber($specification1->class5_1, 1));
                $sheetclass5_1->setCellValue('W'.$i,$starttime);
                $sheetclass5_1->setCellValue('X'.$i,$ftime);
                $sheetclass5_1->setCellValue('AA'.$i,$datafirst[3]->class5_1);

                $sheetclass6->setCellValue('P'.$i,date('d-M-y',strtotime($item->save_date)));
                $sheetclass6->setCellValue('Q'.$i,slope::nhk_formatnumber($item->sem_class6, 1));
                $sheetclass6->setCellValue('R'.$i,$item->lotnumber_lab);
                $sheetclass6->setCellValue('S'.$i,slope::nhk_formatnumber($datafirst[1]->class6, 1));
                $sheetclass6->setCellValue('T'.$i,slope::nhk_formatnumber($datafirst[2]->class6, 1));
                $sheetclass6->setCellValue('U'.$i,slope::nhk_formatnumber($datafirst[0]->class6, 1));
                $sheetclass6->setCellValue('V'.$i,slope::nhk_formatnumber($specification1->class6, 1));
                $sheetclass6->setCellValue('W'.$i,$starttime);
                $sheetclass6->setCellValue('X'.$i,$ftime);
                $sheetclass6->setCellValue('AA'.$i,$datafirst[3]->class6);

                $i++;
            }

            $i = 34;
            foreach ($datalabic as $item) {     
                $starttime = date('YmdHis',strtotime($item->save_date));
                $ftime = date('YmdHis',strtotime($item->ftime_ic));
                $stdate = date('Y-m-d', strtotime($item->save_date));
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$stdate)
                ->where('nt_model_history.date_end','>=',$stdate)
                ->where('nt_model_history.chkstatus', 1)
                ->get();
                if($datafirst->count() == 0)
                {
                    $data1 = DB::table('nt_model_history')
                    ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model_history.id_model', $item->id_model)
                    ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                    ->first();
                    
                    $datafirst = DB::table('nt_model_history')
                    ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model_history.id_model', $item->id_model)
                    ->where('nt_model_history.date_start','<=',$data1->date_start)  
                    ->where('nt_model_history.date_end','>=',$data1->date_start)
                    ->get(); 
                } 
                $sheetanion->setCellValue('F'.$i,date('d-M-y', strtotime($item->tga ?: $item->save_date)));
                $sheetanion->setCellValue('G'.$i,slope::nhk_formatnumber($item->ic_anion_up, 5));
                $sheetanion->setCellValue('H'.$i,$item->lotnumber_lab);
                $sheetanion->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->anion, 5));
                $sheetanion->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[2]->anion, 5));
                $sheetanion->setCellValue('K'.$i,slope::nhk_formatnumber($datafirst[0]->anion, 5));
                $sheetanion->setCellValue('L'.$i,slope::nhk_formatnumber($specification->anion, 5));
                $sheetanion->setCellValue('M'.$i,$starttime);
                $sheetanion->setCellValue('N'.$i,$ftime);
                $sheetanion->setCellValue('O'.$i,slope::nhk_formatnumber($item->ic_anion_fluoride, 5));
                $sheetanion->setCellValue('P'.$i,slope::nhk_formatnumber($item->ic_anion_chloride, 5));
                $sheetanion->setCellValue('Q'.$i,slope::nhk_formatnumber($item->ic_anion_nitrile, 5));
                $sheetanion->setCellValue('R'.$i,slope::nhk_formatnumber($item->ic_anion_bromid, 5));
                $sheetanion->setCellValue('S'.$i,slope::nhk_formatnumber($item->ic_anion_nitrate, 5));
                $sheetanion->setCellValue('T'.$i,slope::nhk_formatnumber($item->ic_anion_sulphate, 5));
                $sheetanion->setCellValue('U'.$i,slope::nhk_formatnumber($item->ic_anion_phosphate, 5));
                $sheetanion->setCellValue('AN'.$i,$datafirst[3]->anion);

                $sheetsulfate->setCellValue('F'.$i,date('d-M-y', strtotime($item->tga ?: $item->save_date)));
                $sheetsulfate->setCellValue('G'.$i,slope::nhk_formatnumber($item->ic_sulphate, 5));
                $sheetsulfate->setCellValue('H'.$i,$item->lotnumber_lab);
                $sheetsulfate->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->sulphate, 5));
                $sheetsulfate->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[2]->sulphate, 5));
                $sheetsulfate->setCellValue('K'.$i,slope::nhk_formatnumber($datafirst[0]->sulphate, 5));
                $sheetsulfate->setCellValue('L'.$i,slope::nhk_formatnumber($specification->sulphate, 5));
                $sheetsulfate->setCellValue('M'.$i,$starttime);
                $sheetsulfate->setCellValue('N'.$i,$ftime);
                $sheetsulfate->setCellValue('Z'.$i,$datafirst[3]->sulphate);

                $sheetcation->setCellValue('F'.$i,date('d-M-y', strtotime($item->tga ?: $item->save_date)));
                $sheetcation->setCellValue('G'.$i,slope::nhk_formatnumber($item->ic_cation_up, 5));
                $sheetcation->setCellValue('H'.$i,$item->lotnumber_lab);
                $sheetcation->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->cation, 5));
                $sheetcation->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[2]->cation, 5));
                $sheetcation->setCellValue('K'.$i,slope::nhk_formatnumber($datafirst[0]->cation, 5));
                $sheetcation->setCellValue('L'.$i,slope::nhk_formatnumber($specification->cation, 5));
                $sheetcation->setCellValue('M'.$i,$starttime);
                $sheetcation->setCellValue('n'.$i,$starttime);
                $sheetcation->setCellValue('O'.$i,slope::nhk_formatnumber($item->ic_cation_sodium, 5));
                $sheetcation->setCellValue('P'.$i,slope::nhk_formatnumber($item->ic_cation_ammonium, 5));
                $sheetcation->setCellValue('Q'.$i,slope::nhk_formatnumber($item->ic_cation_potassium, 5));
                $sheetcation->setCellValue('R'.$i,slope::nhk_formatnumber($item->ic_cation_magnesium, 5));
                $sheetcation->setCellValue('S'.$i,slope::nhk_formatnumber($item->ic_cation_calcium, 5));
                $sheetcation->setCellValue('AN'.$i,$datafirst[3]->cation);
                $i++;
            }
            $i = 34;
            foreach ($datalabicdw as $item) { 
                $starttime = date('YmdHis',strtotime($item->save_date));
                $ftime = date('YmdHis',strtotime($item->ftime_ic));
                $stdate = date('Y-m-d', strtotime($item->save_date));
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$stdate)
                ->where('nt_model_history.date_end','>=',$stdate)
                ->where('nt_model_history.chkstatus', 1)
                ->get();
                if($datafirst->count() == 0)
                {
                    $data1 = DB::table('nt_model_history')
                    ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model_history.id_model', $item->id_model)
                    ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                    ->first();
                    
                    $datafirst = DB::table('nt_model_history')
                    ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model_history.id_model', $item->id_model)
                    ->where('nt_model_history.date_start','<=',$data1->date_start)  
                    ->where('nt_model_history.date_end','>=',$data1->date_start)
                    ->get(); 
                }
                $sheetanion->setCellValue('W'.$i,date('d-M-y', strtotime($item->tga ?: $item->save_date)));
                $sheetanion->setCellValue('X'.$i,slope::nhk_formatnumber($item->ic_anion_up, 5));
                $sheetanion->setCellValue('Y'.$i,$item->lotnumber_lab);
                $sheetanion->setCellValue('Z'.$i,slope::nhk_formatnumber($datafirst[1]->anion, 5));
                $sheetanion->setCellValue('AA'.$i,slope::nhk_formatnumber($datafirst[2]->anion, 5));
                $sheetanion->setCellValue('AB'.$i,slope::nhk_formatnumber($datafirst[0]->anion, 5));
                $sheetanion->setCellValue('AC'.$i,slope::nhk_formatnumber($specification1->anion, 5));
                $sheetanion->setCellValue('AD'.$i,$starttime);
                $sheetanion->setCellValue('AE'.$i,$ftime);
                $sheetanion->setCellValue('AF'.$i,slope::nhk_formatnumber($item->ic_anion_fluoride, 5));
                $sheetanion->setCellValue('AG'.$i,slope::nhk_formatnumber($item->ic_anion_chloride, 5));
                $sheetanion->setCellValue('AH'.$i,slope::nhk_formatnumber($item->ic_anion_nitrile, 5));
                $sheetanion->setCellValue('AI'.$i,slope::nhk_formatnumber($item->ic_anion_bromid, 5));
                $sheetanion->setCellValue('AJ'.$i,slope::nhk_formatnumber($item->ic_anion_nitrate, 5));
                $sheetanion->setCellValue('AK'.$i,slope::nhk_formatnumber($item->ic_anion_sulphate, 5));
                $sheetanion->setCellValue('AL'.$i,slope::nhk_formatnumber($item->ic_anion_phosphate, 5));
                $sheetanion->setCellValue('AO'.$i,$datafirst[3]->anion);

                $sheetsulfate->setCellValue('P'.$i,date('d-M-y', strtotime($item->tga ?: $item->save_date)));
                $sheetsulfate->setCellValue('Q'.$i,slope::nhk_formatnumber($item->ic_sulphate, 5));
                $sheetsulfate->setCellValue('R'.$i,$item->lotnumber_lab);
                $sheetsulfate->setCellValue('S'.$i,slope::nhk_formatnumber($datafirst[1]->sulphate, 5));
                $sheetsulfate->setCellValue('T'.$i,slope::nhk_formatnumber($datafirst[2]->sulphate, 5));
                $sheetsulfate->setCellValue('U'.$i,slope::nhk_formatnumber($datafirst[0]->sulphate, 5));
                $sheetsulfate->setCellValue('V'.$i,slope::nhk_formatnumber($specification1->sulphate, 5));
                $sheetsulfate->setCellValue('W'.$i,$starttime);
                $sheetsulfate->setCellValue('X'.$i,$ftime);
                $sheetsulfate->setCellValue('AA'.$i,$datafirst[3]->sulphate);


                $sheetcation->setCellValue('W'.$i,date('d-M-y', strtotime($item->tga ?: $item->save_date)));
                // $sheetcation->setCellValue('X'.$i,slope::nhk_formatnumber($item->ic_sulphate, 5));
                $sheetcation->setCellValue('X'.$i,slope::nhk_formatnumber($item->ic_cation_up, 5)); ////
                $sheetcation->setCellValue('Y'.$i,$item->lotnumber_lab);
                $sheetcation->setCellValue('Z'.$i,slope::nhk_formatnumber($datafirst[1]->cation, 5));
                $sheetcation->setCellValue('AA'.$i,slope::nhk_formatnumber($datafirst[2]->cation, 5));
                $sheetcation->setCellValue('AB'.$i,slope::nhk_formatnumber($datafirst[0]->cation, 5));
                $sheetcation->setCellValue('AC'.$i,slope::nhk_formatnumber($specification1->cation, 5));
                $sheetcation->setCellValue('AD'.$i,$starttime);
                $sheetcation->setCellValue('AE'.$i,$starttime);
                $sheetcation->setCellValue('AF'.$i,slope::nhk_formatnumber($item->ic_cation_sodium, 5));
                $sheetcation->setCellValue('AG'.$i,slope::nhk_formatnumber($item->ic_cation_ammonium, 5));
                $sheetcation->setCellValue('AH'.$i,slope::nhk_formatnumber($item->ic_cation_potassium, 5));
                $sheetcation->setCellValue('AI'.$i,slope::nhk_formatnumber($item->ic_cation_magnesium, 5));
                $sheetcation->setCellValue('AJ'.$i,slope::nhk_formatnumber($item->ic_cation_calcium, 5));
                $sheetcation->setCellValue('AO'.$i,$datafirst[3]->cation);
                $i++;
            }

            $i = 34;
            foreach ($datalabgcms as $item) {     
                $starttime = date('YmdHis',strtotime($item->save_date));
                $ftime = date('YmdHis',strtotime($item->ftime_gcms));
                $stdate = date('Y-m-d', strtotime($item->save_date));
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$stdate)
                ->where('nt_model_history.date_end','>=',$stdate)
                ->where('nt_model_history.chkstatus', 1)
                ->get();
                if($datafirst->count() == 0)
                {
                    $data1 = DB::table('nt_model_history')
                    ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model_history.id_model', $item->id_model)
                    ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                    ->first();
                    
                    $datafirst = DB::table('nt_model_history')
                    ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model_history.id_model', $item->id_model)
                    ->where('nt_model_history.date_start','<=',$data1->date_start)  
                    ->where('nt_model_history.date_end','>=',$data1->date_start)
                    ->get(); 
                }
                 
                $sheetog->setCellValue('F'.$i,date('d-M-y', strtotime($item->tga ?: $item->save_date)));
                $sheetog->setCellValue('G'.$i,slope::nhk_formatnumber($item->gcms_outgassing, 3));
                $sheetog->setCellValue('H'.$i,$item->lotnumber_lab);
                $sheetog->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->outgassing, 0));
                $sheetog->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[2]->outgassing, 0));
                $sheetog->setCellValue('K'.$i,slope::nhk_formatnumber($datafirst[0]->outgassing, 0));
                $sheetog->setCellValue('L'.$i,slope::nhk_formatnumber($specification->outgassing, 0));
                $sheetog->setCellValue('M'.$i,$starttime);
                $sheetog->setCellValue('N'.$i,$ftime);
                $sheetog->setCellValue('O'.$i,slope::nhk_formatnumber($item->c1, 3));
                $sheetog->setCellValue('P'.$i,slope::nhk_formatnumber($item->c2, 3));
                $sheetog->setCellValue('Q'.$i,slope::nhk_formatnumber($item->c3, 3));
                $sheetog->setCellValue('R'.$i,slope::nhk_formatnumber($item->c4, 3));
                $sheetog->setCellValue('S'.$i,slope::nhk_formatnumber($item->c5, 3));
                $sheetog->setCellValue('T'.$i,slope::nhk_formatnumber($item->c5_1, 3));
                $sheetog->setCellValue('U'.$i,slope::nhk_formatnumber($item->c5_2, 3));
                $sheetog->setCellValue('V'.$i,slope::nhk_formatnumber($item->c5_3, 3));
                $sheetog->setCellValue('W'.$i,slope::nhk_formatnumber($item->c6, 3));
                $sheetog->setCellValue('X'.$i,slope::nhk_formatnumber($item->c6_1, 3));
                $sheetog->setCellValue('Y'.$i,slope::nhk_formatnumber($item->c6_2, 3));
                $sheetog->setCellValue('Z'.$i,slope::nhk_formatnumber($item->c7, 3));
                $sheetog->setCellValue('AA'.$i,slope::nhk_formatnumber($item->c8, 3));
                $sheetog->setCellValue('AB'.$i,slope::nhk_formatnumber($item->c8_1, 3));
                $sheetog->setCellValue('AC'.$i,slope::nhk_formatnumber($item->c8_2, 3));
                $sheetog->setCellValue('AD'.$i,slope::nhk_formatnumber($item->c9, 3));
                $sheetog->setCellValue('AE'.$i,slope::nhk_formatnumber($item->c9_1, 3));
                $sheetog->setCellValue('AF'.$i,slope::nhk_formatnumber($item->c9_2, 3));
                $sheetog->setCellValue('AG'.$i,slope::nhk_formatnumber($item->c10, 3));
                $sheetog->setCellValue('AH'.$i,slope::nhk_formatnumber($item->c11, 3));
                $sheetog->setCellValue('AI'.$i,slope::nhk_formatnumber($item->c11_1, 3));
                $sheetog->setCellValue('AJ'.$i,slope::nhk_formatnumber($item->c11_2, 3));
                $sheetog->setCellValue('AK'.$i,slope::nhk_formatnumber($item->c12, 3));
                $sheetog->setCellValue('AL'.$i,slope::nhk_formatnumber($item->c13, 3));
                $sheetog->setCellValue('AM'.$i,slope::nhk_formatnumber($item->c14, 3));
                $sheetog->setCellValue('AN'.$i,slope::nhk_formatnumber($item->c15, 3));
                $sheetog->setCellValue('AO'.$i,slope::nhk_formatnumber($item->c15_1, 3));
                $sheetog->setCellValue('AP'.$i,slope::nhk_formatnumber($item->c5_2, 3));
                $sheetog->setCellValue('AQ'.$i,slope::nhk_formatnumber($item->c16, 3));
                $sheetog->setCellValue('AR'.$i,slope::nhk_formatnumber($item->c17, 3));
                $sheetog->setCellValue('AS'.$i,slope::nhk_formatnumber($item->c18, 3));
                $sheetog->setCellValue('AT'.$i,slope::nhk_formatnumber($item->c19, 3));
                $sheetog->setCellValue('AU'.$i,slope::nhk_formatnumber($item->c19_1, 3));
                $sheetog->setCellValue('AV'.$i,slope::nhk_formatnumber($item->c19_2, 3));
                $sheetog->setCellValue('AW'.$i,slope::nhk_formatnumber($item->c19_3, 3));
                $sheetog->setCellValue('AX'.$i,slope::nhk_formatnumber($item->c20, 3));
                $sheetog->setCellValue('AY'.$i,slope::nhk_formatnumber($item->c20_1, 3));
                $sheetog->setCellValue('AZ'.$i,slope::nhk_formatnumber($item->c21, 3));
                $sheetog->setCellValue('BA'.$i,slope::nhk_formatnumber($item->c21_1, 3));
                $sheetog->setCellValue('BB'.$i,slope::nhk_formatnumber($item->c22, 3));
                $sheetog->setCellValue('BC'.$i,slope::nhk_formatnumber($item->c22_1, 3));
                $sheetog->setCellValue('BD'.$i,slope::nhk_formatnumber($item->c22_2, 3));
                $sheetog->setCellValue('BE'.$i,slope::nhk_formatnumber($item->c23, 3));
                $sheetog->setCellValue('BF'.$i,slope::nhk_formatnumber($item->c23_1, 3));
                $sheetog->setCellValue('BG'.$i,slope::nhk_formatnumber($item->c24, 3));
                $sheetog->setCellValue('BH'.$i,slope::nhk_formatnumber($item->c24_1, 3));
                $sheetog->setCellValue('BI'.$i,slope::nhk_formatnumber($item->c24_2, 3));
                $sheetog->setCellValue('BJ'.$i,slope::nhk_formatnumber($item->c25, 3));
                $sheetog->setCellValue('BK'.$i,slope::nhk_formatnumber($item->c26, 3));
                $sheetog->setCellValue('BL'.$i,slope::nhk_formatnumber($item->c27, 3));
                $sheetog->setCellValue('BM'.$i,slope::nhk_formatnumber($item->c28, 3));
                $sheetog->setCellValue('BN'.$i,slope::nhk_formatnumber($item->c29, 3));
                $sheetog->setCellValue('BO'.$i,slope::nhk_formatnumber($item->c30, 3));
                $sheetog->setCellValue('BP'.$i,slope::nhk_formatnumber($item->c31, 3));
                $sheetog->setCellValue('BQ'.$i,slope::nhk_formatnumber($item->c32, 3));
                $sheetog->setCellValue('BR'.$i,slope::nhk_formatnumber($item->c33, 3));
               
                $sheetog->setCellValue('BS'.$i,slope::nhk_formatnumber($item->c19_4, 3));
                $sheetog->setCellValue('BT'.$i,slope::nhk_formatnumber($item->c19_5, 3));
                $sheetog->setCellValue('BU'.$i,slope::nhk_formatnumber($item->c35_1, 3));
                $sheetog->setCellValue('BV'.$i,slope::nhk_formatnumber($item->c35_2, 3));
                $sheetog->setCellValue('BW'.$i,slope::nhk_formatnumber($item->spare_01, 3));
                $sheetog->setCellValue('BX'.$i,slope::nhk_formatnumber($item->spare_02, 3));
                $sheetog->setCellValue('BY'.$i,slope::nhk_formatnumber($item->spare_03, 3));
                $sheetog->setCellValue('BZ'.$i,slope::nhk_formatnumber($item->spare_04, 3));
                $sheetog->setCellValue('CA'.$i,slope::nhk_formatnumber($item->spare_05, 3));
                $sheetog->setCellValue('CB'.$i,slope::nhk_formatnumber($item->spare_06, 3));
                $sheetog->setCellValue('CC'.$i,slope::nhk_formatnumber($item->spare_07, 3));
                $sheetog->setCellValue('CD'.$i,slope::nhk_formatnumber($item->spare_08, 3));
                $sheetog->setCellValue('CE'.$i,slope::nhk_formatnumber($item->spare_09, 3));
                $sheetog->setCellValue('CF'.$i,slope::nhk_formatnumber($item->spare_10, 3));
                $sheetog->setCellValue('CG'.$i,slope::nhk_formatnumber($item->spare_11, 3));
                $sheetog->setCellValue('CH'.$i,slope::nhk_formatnumber($item->spare_12, 3));
                $sheetog->setCellValue('CI'.$i,slope::nhk_formatnumber($item->spare_13, 3));
                $sheetog->setCellValue('CJ'.$i,slope::nhk_formatnumber($item->spare_14, 3));
                $sheetog->setCellValue('CK'.$i,slope::nhk_formatnumber($item->spare_15, 3));
                $sheetog->setCellValue('CL'.$i,slope::nhk_formatnumber($item->spare_16, 3));
                $sheetog->setCellValue('CM'.$i,slope::nhk_formatnumber($item->spare_17, 3));
                $sheetog->setCellValue('CN'.$i,slope::nhk_formatnumber($item->spare_18, 3));
                $sheetog->setCellValue('CO'.$i,slope::nhk_formatnumber($item->spare_19, 3));
                $sheetog->setCellValue('CP'.$i,slope::nhk_formatnumber($item->spare_20, 3));
                $sheetog->setCellValue('CQ'.$i,slope::nhk_formatnumber($item->spare_21, 3));
                $sheetog->setCellValue('CR'.$i,slope::nhk_formatnumber($item->spare_22, 3));
                $sheetog->setCellValue('CS'.$i,slope::nhk_formatnumber($item->spare_23, 3));
                $sheetog->setCellValue('CT'.$i,slope::nhk_formatnumber($item->spare_24, 3));
                $sheetog->setCellValue('CU'.$i,slope::nhk_formatnumber($item->spare_25, 3));
                $sheetog->setCellValue('CV'.$i,slope::nhk_formatnumber($item->spare_26, 3));
                $sheetog->setCellValue('CW'.$i,slope::nhk_formatnumber($item->spare_27, 3));
                $sheetog->setCellValue('CX'.$i,slope::nhk_formatnumber($item->spare_28, 3));
                $sheetog->setCellValue('CY'.$i,slope::nhk_formatnumber($item->spare_29, 3));
                $sheetog->setCellValue('CZ'.$i,slope::nhk_formatnumber($item->spare_30, 3));
                $sheetog->setCellValue('DA'.$i,slope::nhk_formatnumber($item->spare_31, 3));
                $sheetog->setCellValue('DB'.$i,slope::nhk_formatnumber($item->spare_32, 3));
                $sheetog->setCellValue('DC'.$i,slope::nhk_formatnumber($item->spare_33, 3));
                $sheetog->setCellValue('DD'.$i,slope::nhk_formatnumber($item->spare_34, 3));
                $sheetog->setCellValue('DE'.$i,slope::nhk_formatnumber($item->spare_35, 3));
                $sheetog->setCellValue('DF'.$i,slope::nhk_formatnumber($item->spare_36, 3));
                $sheetog->setCellValue('DG'.$i,slope::nhk_formatnumber($item->spare_37, 3));

                $sheetog->setCellValue('HL'.$i,$datafirst[3]->outgassing);

                $sheetlm->setCellValue('F'.$i,date('d-M-y', strtotime($item->tga ?: $item->save_date)));
                $sheetlm->setCellValue('G'.$i,slope::nhk_formatnumber($item->gcms_low_mw, 3));
                $sheetlm->setCellValue('H'.$i,$item->lotnumber_lab);
                $sheetlm->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->low_MW, 0));
                $sheetlm->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[2]->low_MW, 0));
                $sheetlm->setCellValue('K'.$i,slope::nhk_formatnumber($datafirst[0]->low_MW, 0));
                $sheetlm->setCellValue('L'.$i,slope::nhk_formatnumber($specification->low_MW, 0));
                $sheetlm->setCellValue('M'.$i,$starttime);
                $sheetlm->setCellValue('N'.$i,$ftime);
                $sheetlm->setCellValue('Z'.$i,$datafirst[3]->low_MW);

                $sheethm->setCellValue('F'.$i,date('d-M-y', strtotime($item->tga ?: $item->save_date)));
                $sheethm->setCellValue('G'.$i,slope::nhk_formatnumber($item->gcms_high_mw, 3));
                $sheethm->setCellValue('H'.$i,$item->lotnumber_lab);
                $sheethm->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->high_MW, 0));
                $sheethm->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[2]->high_MW, 0));
                $sheethm->setCellValue('K'.$i,slope::nhk_formatnumber($datafirst[0]->high_MW, 0));
                $sheethm->setCellValue('L'.$i,slope::nhk_formatnumber($specification->high_MW, 0));
                $sheethm->setCellValue('M'.$i,$starttime);
                $sheethm->setCellValue('N'.$i,$ftime);
                $sheethm->setCellValue('Z'.$i,$datafirst[3]->high_MW);
        
                $sheetsm->setCellValue('F'.$i,date('d-M-y', strtotime($item->tga ?: $item->save_date)));
                $sheetsm->setCellValue('G'.$i,slope::nhk_formatnumber($item->gcms_smear, 3));
                $sheetsm->setCellValue('H'.$i,$item->lotnumber_lab);
                $sheetsm->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->alkylacrylate, 0));
                $sheetsm->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[2]->alkylacrylate, 0));
                $sheetsm->setCellValue('K'.$i,slope::nhk_formatnumber($datafirst[0]->alkylacrylate, 0));
                $sheetsm->setCellValue('L'.$i,slope::nhk_formatnumber($specification->alkylacrylate, 0));
                $sheetsm->setCellValue('M'.$i,$starttime);
                $sheetsm->setCellValue('N'.$i,$ftime);
                $sheetsm->setCellValue('Z'.$i,$datafirst[3]->alkylacrylate);
                $i++;
            }
            $i = 34;
            foreach ($datalabgcmsdw as $item) { 
                $starttime = date('YmdHis',strtotime($item->save_date));
                $ftime = date('YmdHis',strtotime($item->ftime_gcms));
                $stdate = date('Y-m-d', strtotime($item->save_date));
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$stdate)
                ->where('nt_model_history.date_end','>=',$stdate)
                ->where('nt_model_history.chkstatus', 1)
                ->get();
                if($datafirst->count() == 0)
                {
                    $data1 = DB::table('nt_model_history')
                    ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model_history.id_model', $item->id_model)
                    ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                    ->first();
                    
                    $datafirst = DB::table('nt_model_history')
                    ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model_history.id_model', $item->id_model)
                    ->where('nt_model_history.date_start','<=',$data1->date_start)  
                    ->where('nt_model_history.date_end','>=',$data1->date_start)
                    ->get(); 
                }
                $sheetog->setCellValue('DI'.$i,date('d-M-y', strtotime($item->tga ?: $item->save_date)));
                $sheetog->setCellValue('DJ'.$i,slope::nhk_formatnumber($item->gcms_outgassing, 3));
                $sheetog->setCellValue('DK'.$i,$item->lotnumber_lab);
                $sheetog->setCellValue('DL'.$i,slope::nhk_formatnumber($datafirst[1]->outgassing, 0));
                $sheetog->setCellValue('DM'.$i,slope::nhk_formatnumber($datafirst[2]->outgassing, 0));
                $sheetog->setCellValue('DN'.$i,slope::nhk_formatnumber($datafirst[0]->outgassing, 0));
                $sheetog->setCellValue('DO'.$i,slope::nhk_formatnumber($specification1->outgassing, 0));
                $sheetog->setCellValue('DP'.$i,$starttime);
                $sheetog->setCellValue('DQ'.$i,$ftime);
                $sheetog->setCellValue('DR'.$i,slope::nhk_formatnumber($item->c1, 3));
                $sheetog->setCellValue('DS'.$i,slope::nhk_formatnumber($item->c2, 3));
                $sheetog->setCellValue('DT'.$i,slope::nhk_formatnumber($item->c3, 3));
                $sheetog->setCellValue('DU'.$i,slope::nhk_formatnumber($item->c4, 3));
                $sheetog->setCellValue('DV'.$i,slope::nhk_formatnumber($item->c5, 3));
                $sheetog->setCellValue('DW'.$i,slope::nhk_formatnumber($item->c5_1, 3));
                $sheetog->setCellValue('DX'.$i,slope::nhk_formatnumber($item->c5_2, 3));
                $sheetog->setCellValue('DY'.$i,slope::nhk_formatnumber($item->c5_3, 3));
                $sheetog->setCellValue('DZ'.$i,slope::nhk_formatnumber($item->c6, 3));
                $sheetog->setCellValue('EA'.$i,slope::nhk_formatnumber($item->c6_1, 3));
                $sheetog->setCellValue('EB'.$i,slope::nhk_formatnumber($item->c6_2, 3));
                $sheetog->setCellValue('EC'.$i,slope::nhk_formatnumber($item->c7, 3));
                $sheetog->setCellValue('ED'.$i,slope::nhk_formatnumber($item->c8, 3));
                $sheetog->setCellValue('EE'.$i,slope::nhk_formatnumber($item->c8_1, 3));
                $sheetog->setCellValue('EF'.$i,slope::nhk_formatnumber($item->c8_2, 3));
                $sheetog->setCellValue('EG'.$i,slope::nhk_formatnumber($item->c9, 3));
                $sheetog->setCellValue('EH'.$i,slope::nhk_formatnumber($item->c9_1, 3));
                $sheetog->setCellValue('EI'.$i,slope::nhk_formatnumber($item->c9_2, 3));
                $sheetog->setCellValue('EJ'.$i,slope::nhk_formatnumber($item->c10, 3));
                $sheetog->setCellValue('EK'.$i,slope::nhk_formatnumber($item->c11, 3));
                $sheetog->setCellValue('EL'.$i,slope::nhk_formatnumber($item->c11_1, 3));
                $sheetog->setCellValue('EM'.$i,slope::nhk_formatnumber($item->c11_2, 3));
                $sheetog->setCellValue('EN'.$i,slope::nhk_formatnumber($item->c12, 3));
                $sheetog->setCellValue('EO'.$i,slope::nhk_formatnumber($item->c13, 3));
                $sheetog->setCellValue('EP'.$i,slope::nhk_formatnumber($item->c14, 3));
                $sheetog->setCellValue('EQ'.$i,slope::nhk_formatnumber($item->c15, 3));
                $sheetog->setCellValue('ER'.$i,slope::nhk_formatnumber($item->c15_1, 3));
                $sheetog->setCellValue('ES'.$i,slope::nhk_formatnumber($item->c5_2, 3));
                $sheetog->setCellValue('ET'.$i,slope::nhk_formatnumber($item->c16, 3));
                $sheetog->setCellValue('EU'.$i,slope::nhk_formatnumber($item->c17, 3));
                $sheetog->setCellValue('EV'.$i,slope::nhk_formatnumber($item->c18, 3));
                $sheetog->setCellValue('EW'.$i,slope::nhk_formatnumber($item->c19, 3));
                $sheetog->setCellValue('EX'.$i,slope::nhk_formatnumber($item->c19_1, 3));
                $sheetog->setCellValue('EY'.$i,slope::nhk_formatnumber($item->c19_2, 3));
                $sheetog->setCellValue('EZ'.$i,slope::nhk_formatnumber($item->c19_3, 3));
                $sheetog->setCellValue('FA'.$i,slope::nhk_formatnumber($item->c20, 3));
                $sheetog->setCellValue('FB'.$i,slope::nhk_formatnumber($item->c20_1, 3));
                $sheetog->setCellValue('FC'.$i,slope::nhk_formatnumber($item->c21, 3));
                $sheetog->setCellValue('FD'.$i,slope::nhk_formatnumber($item->c21_1, 3));
                $sheetog->setCellValue('FE'.$i,slope::nhk_formatnumber($item->c22, 3));
                $sheetog->setCellValue('FF'.$i,slope::nhk_formatnumber($item->c22_1, 3));
                $sheetog->setCellValue('FG'.$i,slope::nhk_formatnumber($item->c22_2, 3));
                $sheetog->setCellValue('FH'.$i,slope::nhk_formatnumber($item->c23, 3));
                $sheetog->setCellValue('FI'.$i,slope::nhk_formatnumber($item->c23_1, 3));
                $sheetog->setCellValue('FJ'.$i,slope::nhk_formatnumber($item->c24, 3));
                $sheetog->setCellValue('FK'.$i,slope::nhk_formatnumber($item->c24_1, 3));
                $sheetog->setCellValue('FL'.$i,slope::nhk_formatnumber($item->c24_2, 3));
                $sheetog->setCellValue('FM'.$i,slope::nhk_formatnumber($item->c25, 3));
                $sheetog->setCellValue('FN'.$i,slope::nhk_formatnumber($item->c26, 3));
                $sheetog->setCellValue('FO'.$i,slope::nhk_formatnumber($item->c27, 3));
                $sheetog->setCellValue('FP'.$i,slope::nhk_formatnumber($item->c28, 3));
                $sheetog->setCellValue('FQ'.$i,slope::nhk_formatnumber($item->c29, 3));
                $sheetog->setCellValue('FR'.$i,slope::nhk_formatnumber($item->c30, 3));
                $sheetog->setCellValue('FS'.$i,slope::nhk_formatnumber($item->c31, 3));
                $sheetog->setCellValue('FT'.$i,slope::nhk_formatnumber($item->c32, 3));
                $sheetog->setCellValue('FU'.$i,slope::nhk_formatnumber($item->c33, 3));

                $sheetog->setCellValue('FV'.$i,slope::nhk_formatnumber($item->c19_4, 3));
                $sheetog->setCellValue('FW'.$i,slope::nhk_formatnumber($item->c19_5, 3));
                $sheetog->setCellValue('FX'.$i,slope::nhk_formatnumber($item->c35_1, 3));
                $sheetog->setCellValue('FY'.$i,slope::nhk_formatnumber($item->c35_2, 3));
                $sheetog->setCellValue('FZ'.$i,slope::nhk_formatnumber($item->spare_01, 3));
                $sheetog->setCellValue('GA'.$i,slope::nhk_formatnumber($item->spare_02, 3));
                $sheetog->setCellValue('GB'.$i,slope::nhk_formatnumber($item->spare_03, 3));
                $sheetog->setCellValue('GC'.$i,slope::nhk_formatnumber($item->spare_04, 3));
                $sheetog->setCellValue('GD'.$i,slope::nhk_formatnumber($item->spare_05, 3));
                $sheetog->setCellValue('GE'.$i,slope::nhk_formatnumber($item->spare_06, 3));
                $sheetog->setCellValue('GF'.$i,slope::nhk_formatnumber($item->spare_07, 3));
                $sheetog->setCellValue('GG'.$i,slope::nhk_formatnumber($item->spare_08, 3));
                $sheetog->setCellValue('GH'.$i,slope::nhk_formatnumber($item->spare_09, 3));
                $sheetog->setCellValue('GI'.$i,slope::nhk_formatnumber($item->spare_10, 3));
                $sheetog->setCellValue('GJ'.$i,slope::nhk_formatnumber($item->spare_11, 3));
                $sheetog->setCellValue('GK'.$i,slope::nhk_formatnumber($item->spare_12, 3));
                $sheetog->setCellValue('GL'.$i,slope::nhk_formatnumber($item->spare_13, 3));
                $sheetog->setCellValue('GM'.$i,slope::nhk_formatnumber($item->spare_14, 3));
                $sheetog->setCellValue('GN'.$i,slope::nhk_formatnumber($item->spare_15, 3));
                $sheetog->setCellValue('GO'.$i,slope::nhk_formatnumber($item->spare_16, 3));
                $sheetog->setCellValue('GP'.$i,slope::nhk_formatnumber($item->spare_17, 3));
                $sheetog->setCellValue('GQ'.$i,slope::nhk_formatnumber($item->spare_18, 3));
                $sheetog->setCellValue('GR'.$i,slope::nhk_formatnumber($item->spare_19, 3));
                $sheetog->setCellValue('GS'.$i,slope::nhk_formatnumber($item->spare_20, 3));
                $sheetog->setCellValue('GT'.$i,slope::nhk_formatnumber($item->spare_21, 3));
                $sheetog->setCellValue('GU'.$i,slope::nhk_formatnumber($item->spare_22, 3));
                $sheetog->setCellValue('GV'.$i,slope::nhk_formatnumber($item->spare_23, 3));
                $sheetog->setCellValue('GW'.$i,slope::nhk_formatnumber($item->spare_24, 3));
                $sheetog->setCellValue('GX'.$i,slope::nhk_formatnumber($item->spare_25, 3));
                $sheetog->setCellValue('GY'.$i,slope::nhk_formatnumber($item->spare_26, 3));
                $sheetog->setCellValue('GZ'.$i,slope::nhk_formatnumber($item->spare_27, 3));
                $sheetog->setCellValue('HA'.$i,slope::nhk_formatnumber($item->spare_28, 3));
                $sheetog->setCellValue('HB'.$i,slope::nhk_formatnumber($item->spare_29, 3));
                $sheetog->setCellValue('HC'.$i,slope::nhk_formatnumber($item->spare_30, 3));
                $sheetog->setCellValue('HD'.$i,slope::nhk_formatnumber($item->spare_31, 3));
                $sheetog->setCellValue('HE'.$i,slope::nhk_formatnumber($item->spare_32, 3));
                $sheetog->setCellValue('HF'.$i,slope::nhk_formatnumber($item->spare_33, 3));
                $sheetog->setCellValue('HG'.$i,slope::nhk_formatnumber($item->spare_34, 3));
                $sheetog->setCellValue('HH'.$i,slope::nhk_formatnumber($item->spare_35, 3));
                $sheetog->setCellValue('HI'.$i,slope::nhk_formatnumber($item->spare_36, 3));
                $sheetog->setCellValue('HJ'.$i,slope::nhk_formatnumber($item->spare_37, 3));

                $sheetog->setCellValue('HM'.$i,$datafirst[3]->outgassing);

                $sheetlm->setCellValue('P'.$i,date('d-M-y', strtotime($item->tga ?: $item->save_date)));
                $sheetlm->setCellValue('Q'.$i,slope::nhk_formatnumber($item->gcms_low_mw, 3));
                $sheetlm->setCellValue('R'.$i,$item->lotnumber_lab);
                $sheetlm->setCellValue('S'.$i,slope::nhk_formatnumber($datafirst[1]->low_MW, 0));
                $sheetlm->setCellValue('T'.$i,slope::nhk_formatnumber($datafirst[2]->low_MW, 0));
                $sheetlm->setCellValue('U'.$i,slope::nhk_formatnumber($datafirst[0]->low_MW, 0));
                $sheetlm->setCellValue('V'.$i,slope::nhk_formatnumber($specification1->low_MW, 0));
                $sheetlm->setCellValue('W'.$i,$starttime);
                $sheetlm->setCellValue('X'.$i,$ftime);
                $sheetlm->setCellValue('AA'.$i,$datafirst[3]->low_MW);

                $sheethm->setCellValue('P'.$i,date('d-M-y', strtotime($item->tga ?: $item->save_date)));
                $sheethm->setCellValue('Q'.$i,slope::nhk_formatnumber($item->gcms_high_mw, 3));
                $sheethm->setCellValue('R'.$i,$item->lotnumber_lab);
                $sheethm->setCellValue('S'.$i,slope::nhk_formatnumber($datafirst[1]->high_MW, 0));
                $sheethm->setCellValue('T'.$i,slope::nhk_formatnumber($datafirst[2]->high_MW, 0));
                $sheethm->setCellValue('U'.$i,slope::nhk_formatnumber($datafirst[0]->high_MW, 0));
                $sheethm->setCellValue('V'.$i,slope::nhk_formatnumber($specification1->high_MW, 0));
                $sheethm->setCellValue('W'.$i,$starttime);
                $sheethm->setCellValue('X'.$i,$ftime);
                $sheethm->setCellValue('AA'.$i,$datafirst[3]->high_MW);

                $sheetsm->setCellValue('P'.$i,date('d-M-y', strtotime($item->tga ?: $item->save_date)));
                $sheetsm->setCellValue('Q'.$i,slope::nhk_formatnumber($item->gcms_smear, 3));
                $sheetsm->setCellValue('R'.$i,$item->lotnumber_lab);
                $sheetsm->setCellValue('S'.$i,slope::nhk_formatnumber($datafirst[1]->alkylacrylate, 0));
                $sheetsm->setCellValue('T'.$i,slope::nhk_formatnumber($datafirst[2]->alkylacrylate, 0));
                $sheetsm->setCellValue('U'.$i,slope::nhk_formatnumber($datafirst[0]->alkylacrylate, 0));
                $sheetsm->setCellValue('V'.$i,slope::nhk_formatnumber($specification1->alkylacrylate, 0));
                $sheetsm->setCellValue('W'.$i,$starttime);
                $sheetsm->setCellValue('X'.$i,$ftime);
                $sheetsm->setCellValue('AA'.$i,$datafirst[3]->alkylacrylate);
                $i++;
            }
            $i = 34;
            foreach ($datalabftir as $item) {     
                $starttime = date('YmdHis',strtotime($item->save_date));
                $ftime = date('YmdHis',strtotime($item->ftime_ftir));
                $stdate = date('Y-m-d', strtotime($item->save_date));
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$stdate)
                ->where('nt_model_history.date_end','>=',$stdate)
                ->where('nt_model_history.chkstatus', 1)
                ->get();
                if($datafirst->count() == 0)
                {
                    $data1 = DB::table('nt_model_history')
                    ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model_history.id_model', $item->id_model)
                    ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                    ->first();
                    
                    $datafirst = DB::table('nt_model_history')
                    ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model_history.id_model', $item->id_model)
                    ->where('nt_model_history.date_start','<=',$data1->date_start)  
                    ->where('nt_model_history.date_end','>=',$data1->date_start)
                    ->get(); 
                } 
                $sheetftir->setCellValue('F'.$i,date('d-M-y', strtotime($item->tga ?: $item->save_date)));
                $sheetftir->setCellValue('G'.$i,slope::nhk_formatnumber($item->ftir_silicone, 1));
                $sheetftir->setCellValue('H'.$i,$item->lotnumber_lab);
                $sheetftir->setCellValue('I'.$i,slope::nhk_formatnumber($datafirst[1]->ftir, 1));
                $sheetftir->setCellValue('J'.$i,slope::nhk_formatnumber($datafirst[2]->ftir, 1));
                $sheetftir->setCellValue('K'.$i,slope::nhk_formatnumber($datafirst[0]->ftir, 1));
                $sheetftir->setCellValue('L'.$i,slope::nhk_formatnumber($specification->ftir, 1));
                $sheetftir->setCellValue('M'.$i,$starttime);
                $sheetftir->setCellValue('N'.$i,$ftime);
                $sheetftir->setCellValue('Z'.$i,$datafirst[3]->ftir);
                $i++;
            }
            $i = 34;
            foreach ($datalabftirdw as $item) {     
                $starttime = date('YmdHis',strtotime($item->save_date));
                $ftime = date('YmdHis',strtotime($item->ftime_ftir));
                $stdate = date('Y-m-d', strtotime($item->save_date));
                $datafirst = DB::table('nt_model_history')
                ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                ->where('nt_model_history.id_model', $item->id_model)
                ->where('nt_model_history.date_start','<=',$stdate)
                ->where('nt_model_history.date_end','>=',$stdate)
                ->where('nt_model_history.chkstatus', 1)
                ->get();
                if($datafirst->count() == 0)
                {
                    $data1 = DB::table('nt_model_history')
                    ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model_history.id_model', $item->id_model)
                    ->where('nt_model_history.date_start','>',$stdate)->orderby('nt_model_history.date_start','asc')
                    ->first();
                    
                    $datafirst = DB::table('nt_model_history')
                    ->join('nt_model', 'nt_model_history.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model_history.id_model', $item->id_model)
                    ->where('nt_model_history.date_start','<=',$data1->date_start)  
                    ->where('nt_model_history.date_end','>=',$data1->date_start)
                    ->get(); 
                }
                $sheetftir->setCellValue('P'.$i,date('d-M-y', strtotime($item->tga ?: $item->save_date)));
                $sheetftir->setCellValue('Q'.$i,slope::nhk_formatnumber($item->ftir_silicone, 1));
                $sheetftir->setCellValue('R'.$i,$item->lotnumber_lab);
                $sheetftir->setCellValue('S'.$i,slope::nhk_formatnumber($datafirst[1]->ftir, 1));
                $sheetftir->setCellValue('T'.$i,slope::nhk_formatnumber($datafirst[2]->ftir, 1));
                $sheetftir->setCellValue('U'.$i,slope::nhk_formatnumber($datafirst[0]->ftir, 1));
                $sheetftir->setCellValue('V'.$i,slope::nhk_formatnumber($specification1->ftir, 1));
                $sheetftir->setCellValue('W'.$i,$starttime);
                $sheetftir->setCellValue('X'.$i,$ftime);
                $sheetftir->setCellValue('AA'.$i,$datafirst[3]->ftir);
                $i++;
            }
        })->setFilename($namefile)->store('xlsx', storage_path('NHK-File/report/graphexcel'));
        $chk = 1; 
        return response()->json(['success' =>  $chk,'filename' => $namefile.'.xlsx' ]);
    }

    public function resultstest7()
    { 
        
        return view('output/result_test');
    }

    public function viewdatatest7($id)	
    {
        $data = DB::table('nt_oqa_lab')
        ->join('nt_model','nt_model.id_model','=','nt_oqa_lab.id_model')
        ->where('id_createlab', $id)->first();
        return view('output/view_test', [
            'id' =>$data->id_model,
            'idcreatelab' =>$data->id_createlab,
            'laball' =>$data,
        ]);
    }

    //-------------- begin gcms compound -------------- 

    public function elementgcms()  
    { 
        $array = [];
        $array2 = [];
        $gcms = DB::table('nt_compound_gcms')->get();
        foreach ($gcms as $key => $value) {
            $array[] = $value->id_createlab;
        }
        $result = array_unique( $array );
        $oqa_lab = DB::table('nt_oqa_lab')->whereIn('id_createlab', $result)->where('type_lab','LIKE','SGT_%')->get();
        foreach ($oqa_lab as $key => $value) {
            $array2[] = $value->id_model;
        }
        $result2 = array_unique( $array2 );
        
        $datalist = DB::table('nt_model')->whereIn('id_model', $result2)->where('model_type','LIKE','SGT_%')->get();
        $unit = DB::table('nt_compound_gcms_unit')->get();

        $data = [
            'datalist' => $datalist,
            'unit' => $unit
        ];

        return view('output/elementgcms', $data);
    }

    public function gcmspossible(Request $request) 
    {

            $info = DB::table('nt_compound_gcms_possible_unit')->where('id_cmp', $request->compound)->get();
            return response()->json([
                'success' => 1,
                'data' => $info,
            ]);

    }

    public function createCompoundgcms(Request $request) 
    {

            $name = $request->name_compound;
            $check = DB::table('nt_compound_gcms_unit')->where('compound_name','LIKE', $name)->first();
            
            if(!$check){
                $data = [
                    'compound_name' => $name,
                ];

                DB::table('nt_compound_gcms_unit')->insert($data);
                return response()->json(['success' =>  1]);
            }else{
                return response()->json(['success' =>  2]);
            } 
    }

    public function createPossible(Request $request) 
    {

            if(count($request->possible)>0){
                
                foreach ($request->possible as $key => $value) {
                    if($value != null){
                        $data = [
                            'id_cmp' => $request->select_compound,
                            'possible_name' => $value,
                        ];
                        DB::table('nt_compound_gcms_possible_unit')->insert($data);
                    }
                }

                return response()->json(['success' =>  1]);

            }else{

                return response()->json(['success' =>  2]);

            }

    }

    public function viewcompoundgcms(Request $request) 
    {
        $fgraph = [];
        $showtop = [];

        $lotnumber = $request->textlot;
        $datestart = $request->start_date;
        $dateend = $request->end_date;
        $model = $request->chkmodel;

        if(empty($lotnumber) && empty($datestart) && empty($dateend) && empty($model))
        {
            return response()->json(['success' =>  2]);
        }

        $datalist = DB::table('nt_oqa_lab')
        ->join('nt_compound_gcms','nt_oqa_lab.id_createlab','=','nt_compound_gcms.id_createlab')
        ->join('nt_compound_gcms_unit','nt_compound_gcms.id_cmp','=','nt_compound_gcms_unit.id_cmp');
        
        if(!empty($lotnumber))
        {
            $datalist = $datalist->where('nt_oqa_lab.lotnumber_lab','like',$lotnumber);
        }

        if(!empty($model)){
            $datalist = $datalist->where('nt_oqa_lab.id_model', $model);
        }

        if($datestart != null && $dateend != null){
            $start = date('Y-m-d 00:00:00',strtotime($datestart));
            $end = date('Y-m-d 23:59:00',strtotime($dateend));
            $datalist = $datalist->where('nt_oqa_lab.save_date','>=',$start)->where('nt_oqa_lab.save_date','<=',$end);
        }

        $datalist = $datalist->where('nt_oqa_lab.type_lab','LIKE','SGT_%')->get();

        if(count($datalist) > 0){

            $file = storage_path('NHK-File/template/report/topcompoundgcms.xlsx');
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
            $spreadsheet = $reader->load($file);
            $sheet = $spreadsheet->setActiveSheetIndex(0);

            $row = 2;
            foreach ($datalist as $key => $value) {
                $sheet->setCellValue('A'.$row, $value->id_cmp);
                $sheet->setCellValue('B'.$row, $value->qty);
                $row++;
            }
           
            $namefile = "compoundGCMS_" .  date('YmdHis');
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
            $writer->save(storage_path('NHK-File/report/compound/'.$namefile.'.xlsx'));
            
            //---------- get data --------------
            
            $file2 = storage_path('NHK-File/report/compound/'.$namefile.'.xlsx');
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file2);
            $worksheet = $spreadsheet->setActiveSheetIndex(0); 
        
            $topIndex1 = $worksheet->getCell('E2')->getOldCalculatedValue();
            $topIndex2 = $worksheet->getCell('E3')->getOldCalculatedValue();
            $topIndex3 = $worksheet->getCell('E4')->getOldCalculatedValue();
            $topIndex4 = $worksheet->getCell('E5')->getOldCalculatedValue();
            $topIndex5 = $worksheet->getCell('E6')->getOldCalculatedValue();

            $topValue1 = $worksheet->getCell('F2')->getOldCalculatedValue();
            $topValue2 = $worksheet->getCell('F3')->getOldCalculatedValue();
            $topValue3 = $worksheet->getCell('F4')->getOldCalculatedValue();
            $topValue4 = $worksheet->getCell('F5')->getOldCalculatedValue();
            $topValue5 = $worksheet->getCell('F6')->getOldCalculatedValue();

            $top1 = DB::table('nt_compound_gcms_unit')->where('id_cmp', intval($topIndex1))->first();
            $top2 = DB::table('nt_compound_gcms_unit')->where('id_cmp', intval($topIndex2))->first();
            $top3 = DB::table('nt_compound_gcms_unit')->where('id_cmp', intval($topIndex3))->first();
            $top4 = DB::table('nt_compound_gcms_unit')->where('id_cmp', intval($topIndex4))->first();
            $top5 = DB::table('nt_compound_gcms_unit')->where('id_cmp', intval($topIndex5))->first();

            $possibletop1 = DB::table('nt_compound_gcms_possible_unit')->where('id_cmp', intval($topIndex1))->get();
            $possibletop2 = DB::table('nt_compound_gcms_possible_unit')->where('id_cmp', intval($topIndex2))->get();
            $possibletop3 = DB::table('nt_compound_gcms_possible_unit')->where('id_cmp', intval($topIndex3))->get();
            $possibletop4 = DB::table('nt_compound_gcms_possible_unit')->where('id_cmp', intval($topIndex4))->get();
            $possibletop5 = DB::table('nt_compound_gcms_possible_unit')->where('id_cmp', intval($topIndex5))->get();

            $array_1 = []; $array_2 = []; $array_3 = []; $array_4 = []; $array_5 = [];
            foreach ($possibletop1 as $key => $value) {
                $array_1[] = $value->possible_name;
            }
            foreach ($possibletop2 as $key => $value) {
                $array_2[] = $value->possible_name;
            }
            foreach ($possibletop3 as $key => $value) {
                $array_3[] = $value->possible_name;
            }
            foreach ($possibletop4 as $key => $value) {
                $array_4[] = $value->possible_name;
            }
            foreach ($possibletop5 as $key => $value) {
                $array_5[] = $value->possible_name;
            }


            $showtop['name'][] = $top1->compound_name;
            $showtop['name'][] = $top2->compound_name;
            $showtop['name'][] = $top3->compound_name;
            $showtop['name'][] = $top4->compound_name;
            $showtop['name'][] = $top5->compound_name;

            $showtop['qty'][] = $topValue1;
            $showtop['qty'][] = $topValue2;
            $showtop['qty'][] = $topValue3;
            $showtop['qty'][] = $topValue4;
            $showtop['qty'][] = $topValue5;

            $showtop['possible'][] = @implode(",", $array_1);
            $showtop['possible'][] = @implode(",", $array_2);
            $showtop['possible'][] = @implode(",", $array_3);
            $showtop['possible'][] = @implode(",", $array_4);
            $showtop['possible'][] = @implode(",", $array_5);


            $data = [
                'showtop' => $showtop,
                'fgraph' => $fgraph,
            ];

            return response()->json(['success' =>  1, 'data' => $data]);

        }else{

            return response()->json(['success' =>  2]);

        }
         
    }


    public function deletepossible(Request $request)
    {
        $check = DB::table('nt_compound_gcms_possible_unit')->where('id_cms',$request->id)->first();
            
        if($check){
             
            DB::table('nt_compound_gcms_possible_unit')->where('id_cms',$request->id)->delete();

            return response()->json(['success' =>  1]);

        }else{

            return response()->json(['success' =>  2]);

        } 

    }


    public function viewdatapossible(Request $request)
    {
        $check = DB::table('nt_compound_gcms_possible_unit')->where('id_cms',$request->id)->first();
            
        if($check){
             
            return response()->json(['success' =>  1, 'possible' => $check]);

        }else{

            return response()->json(['success' =>  2]);

        } 

    }

    public function updatePossible(Request $request)
    {

        $check = DB::table('nt_compound_gcms_possible_unit')->where('id_cms',$request->edit_id_possible)->first();
        if($check){
             
            DB::table('nt_compound_gcms_possible_unit')->where('id_cms',$request->edit_id_possible)->update(['possible_name' => $request->edit_possible]);
            return response()->json(['success' =>  1]);

        }else{

            return response()->json(['success' =>  2]);

        } 


    }

    //-------------- end gcms compound -------------- 


    //-------------- begin Measurement L/T -------------- 
    
    public function measure() 
    { 

        $measure = DB::table('nt_measure')->get();
        $data = [
            'measure' => $measure
        ];
        return view('output/measure', $data);
    }

    public function viewmeasures(Request $request)  
    { 

        $data = DB::table('nt_measure_day')->where('id_tsetting', $request->id)->first();
        return response()->json(['success' =>  1,'measure_day'=> $data]);

    }

    public function updateMeasures(Request $request) 
    {
        
        $id = $request->id;
        $lpc = $request->lpc;
        $sem = $request->sem;
        $ic = $request->ic;
        $gcms = $request->gcms;
        $ftir = $request->ftir;
        $ghost = $request->ghost;

        $day = [
            'lpc' => $lpc,
            'sem' => $sem,
            'ic' => $ic,
            'gcms' => $gcms,
            'ftir' => $ftir,
            'ghost' => $ghost,
            'save_date' => date('Y-m-d H:i:s'),
        ];

        DB::table('nt_measure_day')->where('id_ucl', $id)->update($day);
        return response()->json(['success' =>  1]);

    }

    public function measureedit($id,$year,$status)  
    {

        $arrayweekend=[];

        $check = DB::table('nt_measure_holiday')->where('id_tsetting',$id)->where('year',$year)->get();
        if(count($check) == 0){

            $now01 = strtotime(date($year.'-01-01'));
            $end01 = strtotime(date($year.'-01-t'));
            // while (date("Y-m-d", $now01) != date("Y-m-d", $end01)) {
            while (date("Y-m-d", $now01) <= date("Y-m-d", $end01)) {
                $day_index = date("w", $now01);
                if ($day_index == 0 || $day_index == 6) {
                    $arrayweekend[1][] = date('Y-m-d', $now01);
                }
                $now01 = strtotime(date("Y-m-d", $now01) . "+1 day");
            }

            $now02 = strtotime(date($year.'-02-01'));
            $end02 = strtotime(date($year.'-02-t'));
            while (date("Y-m-d", $now02) <= date("Y-m-d", $end02)) {
                $day_index = date("w", $now02);
                if ($day_index == 0 || $day_index == 6) {
                    $arrayweekend[2][] = date('Y-m-d', $now02);
                }
                $now02 = strtotime(date("Y-m-d", $now02) . "+1 day");
            }

            $now03 = strtotime(date($year.'-03-01'));
            $end03 = strtotime(date($year.'-03-t'));
            while (date("Y-m-d", $now03) <= date("Y-m-d", $end03)) {
                $day_index = date("w", $now03);
                if ($day_index == 0 || $day_index == 6) {
                    $arrayweekend[3][] = date('Y-m-d', $now03);
                }  
                $now03 = strtotime(date("Y-m-d", $now03) . "+1 day");
            }

            $now04 = strtotime(date($year.'-04-01'));
            $end04 = strtotime(date($year.'-04-t'));
            while (date("Y-m-d", $now04) <= date("Y-m-d", $end04)) {
                $day_index = date("w", $now04);
                if ($day_index == 0 || $day_index == 6) {
                    $arrayweekend[4][] = date('Y-m-d', $now04);
                }
                $now04 = strtotime(date("Y-m-d", $now04) . "+1 day");
            }

            $now05 = strtotime(date($year.'-05-01'));
            $end05 = strtotime(date($year.'-05-t'));
            while (date("Y-m-d", $now05) <= date("Y-m-d", $end05)) {
                $day_index = date("w", $now05);
                if ($day_index == 0 || $day_index == 6) {
                    $arrayweekend[5][] = date('Y-m-d', $now05);
                }
                $now05 = strtotime(date("Y-m-d", $now05) . "+1 day");
            }

            $now06 = strtotime(date($year.'-06-01'));
            $end06 = strtotime(date($year.'-06-t'));
            while (date("Y-m-d", $now06) <= date("Y-m-d", $end06)) {
                $day_index = date("w", $now06);
                if ($day_index == 0 || $day_index == 6) {
                    $arrayweekend[6][] = date('Y-m-d', $now06);
                }
                $now06 = strtotime(date("Y-m-d", $now06) . "+1 day");
            }

            $now07 = strtotime(date($year.'-07-01'));
            $end07 = strtotime(date($year.'-07-t'));
            while (date("Y-m-d", $now07) <= date("Y-m-d", $end07)) {
                $day_index = date("w", $now07);
                if ($day_index == 0 || $day_index == 6) {
                    $arrayweekend[7][] = date('Y-m-d', $now07);
                }
                $now07 = strtotime(date("Y-m-d", $now07) . "+1 day");
            }

            $now08 = strtotime(date($year.'-08-01'));
            $end08 = strtotime(date($year.'-08-t'));
            while (date("Y-m-d", $now08) <= date("Y-m-d", $end08)) {
                $day_index = date("w", $now08);
                if ($day_index == 0 || $day_index == 6) {
                    $arrayweekend[8][] = date('Y-m-d', $now08);
                }
                $now08 = strtotime(date("Y-m-d", $now08) . "+1 day");
            }

            $now09 = strtotime(date($year.'-09-01'));
            $end09 = strtotime(date($year.'-09-t'));
            while (date("Y-m-d", $now09) <= date("Y-m-d", $end09)) {
                $day_index = date("w", $now09);
                if ($day_index == 0 || $day_index == 6) {
                    $arrayweekend[9][] = date('Y-m-d', $now09);
                }
                $now09 = strtotime(date("Y-m-d", $now09) . "+1 day");
            }

            $now10 = strtotime(date($year.'-10-01'));
            $end10 = strtotime(date($year.'-10-t'));
            while (date("Y-m-d", $now10) <= date("Y-m-d", $end10)) {
                $day_index = date("w", $now10);
                if ($day_index == 0 || $day_index == 6) {
                    $arrayweekend[10][] = date('Y-m-d', $now10);
                }
                $now10 = strtotime(date("Y-m-d", $now10) . "+1 day");
            }

            $now11 = strtotime(date($year.'-11-01'));
            $end11 = strtotime(date($year.'-11-t'));
            while (date("Y-m-d", $now11) <= date("Y-m-d", $end11)) {
                $day_index = date("w", $now11);
                if ($day_index == 0 || $day_index == 6) {
                    $arrayweekend[11][] = date('Y-m-d', $now11);
                }
                $now11 = strtotime(date("Y-m-d", $now11) . "+1 day");
            }

            $now12 = strtotime(date($year.'-12-01'));
            $end12 = strtotime(date($year.'-12-t'));
            while (date("Y-m-d", $now12) <= date("Y-m-d", $end12)) {
                $day_index = date("w", $now12);
                if ($day_index == 0 || $day_index == 6) {
                    $arrayweekend[12][] = date('Y-m-d', $now12);
                }
                $now12 = strtotime(date("Y-m-d", $now12) . "+1 day");
            }

        }else{

            $get01 = DB::table('nt_measure_holiday')->where('id_tsetting',$id)->where('year',$year)->where('month',1)->first();
            $get02 = DB::table('nt_measure_holiday')->where('id_tsetting',$id)->where('year',$year)->where('month',2)->first();
            $get03 = DB::table('nt_measure_holiday')->where('id_tsetting',$id)->where('year',$year)->where('month',3)->first();
            $get04 = DB::table('nt_measure_holiday')->where('id_tsetting',$id)->where('year',$year)->where('month',4)->first();
            $get05 = DB::table('nt_measure_holiday')->where('id_tsetting',$id)->where('year',$year)->where('month',5)->first();
            $get06 = DB::table('nt_measure_holiday')->where('id_tsetting',$id)->where('year',$year)->where('month',6)->first();
            $get07 = DB::table('nt_measure_holiday')->where('id_tsetting',$id)->where('year',$year)->where('month',7)->first();
            $get08 = DB::table('nt_measure_holiday')->where('id_tsetting',$id)->where('year',$year)->where('month',8)->first();
            $get09 = DB::table('nt_measure_holiday')->where('id_tsetting',$id)->where('year',$year)->where('month',9)->first();
            $get10 = DB::table('nt_measure_holiday')->where('id_tsetting',$id)->where('year',$year)->where('month',10)->first();
            $get11 = DB::table('nt_measure_holiday')->where('id_tsetting',$id)->where('year',$year)->where('month',11)->first();
            $get12 = DB::table('nt_measure_holiday')->where('id_tsetting',$id)->where('year',$year)->where('month',12)->first();

            $month1 = explode(',', $get01->textdate);
            $month2 = explode(',', $get02->textdate);
            $month3 = explode(',', $get03->textdate);
            $month4 = explode(',', $get04->textdate);
            $month5 = explode(',', $get05->textdate);
            $month6 = explode(',', $get06->textdate);
            $month7 = explode(',', $get07->textdate);
            $month8 = explode(',', $get08->textdate);
            $month9 = explode(',', $get09->textdate);
            $month10 = explode(',', $get10->textdate);
            $month11 = explode(',', $get11->textdate);
            $month12 = explode(',', $get12->textdate);

            foreach ($month1 as $key => $value) {
                $arrayweekend[1][] = date('Y-m-d', strtotime($value));
            }
            foreach ($month2 as $key => $value) {
                $arrayweekend[2][] = date('Y-m-d', strtotime($value));
            }
            foreach ($month3 as $key => $value) {
                $arrayweekend[3][] = date('Y-m-d', strtotime($value));
            }
            foreach ($month4 as $key => $value) {
                $arrayweekend[4][] = date('Y-m-d', strtotime($value));
            }
            foreach ($month5 as $key => $value) {
                $arrayweekend[5][] = date('Y-m-d', strtotime($value));
            }
            foreach ($month6 as $key => $value) {
                $arrayweekend[6][] = date('Y-m-d', strtotime($value));
            }
            foreach ($month7 as $key => $value) {
                $arrayweekend[7][] = date('Y-m-d', strtotime($value));
            }
            foreach ($month8 as $key => $value) {
                $arrayweekend[8][] = date('Y-m-d', strtotime($value));
            }
            foreach ($month9 as $key => $value) {
                $arrayweekend[9][] = date('Y-m-d', strtotime($value));
            }
            foreach ($month10 as $key => $value) {
                $arrayweekend[10][] = date('Y-m-d', strtotime($value));
            }
            foreach ($month11 as $key => $value) {
                $arrayweekend[11][] = date('Y-m-d', strtotime($value));
            }
            foreach ($month12 as $key => $value) {
                $arrayweekend[12][] = date('Y-m-d', strtotime($value));
            } 
        } 
        
        $data = [
            'status' => $status,
            'id_tsetting' => $id,
            'year' => $year,
            'sortyear' => date('y',strtotime($year.'-01-01')),
            'arrayweekend' => $arrayweekend,
        ]; 

        return view('output/measureedit', $data);
    }

    public function updateMeasuresedit(Request $request) 
    {

        $data01 = str_replace("[", "", $request->calendar01);
        $data01_1 = str_replace("]", "", $data01);
        $data01_2 = str_replace('"', "", $data01_1);
        $month01 = explode(",", $data01_2);
        foreach ($month01 as $key => $value) {
            $newmonth01[] = date('Y-m-d', strtotime($value));
        } 
 
        $data02 = str_replace("[", "", $request->calendar02);
        $data02_1 = str_replace("]", "", $data02);
        $data02_2 = str_replace('"', "", $data02_1);
        $month02 = explode(",", $data02_2);
        foreach ($month02 as $key => $value) {
            $newmonth02[] = date('Y-m-d', strtotime($value));
        }

        $data03 = str_replace("[", "", $request->calendar03);
        $data03_1 = str_replace("]", "", $data03);
        $data03_2 = str_replace('"', "", $data03_1);
        $month03 = explode(",", $data03_2);
        foreach ($month03 as $key => $value) {
            $newmonth03[] = date('Y-m-d', strtotime($value));
        }

        $data04 = str_replace("[", "", $request->calendar04);
        $data04_1 = str_replace("]", "", $data04);
        $data04_2 = str_replace('"', "", $data04_1);
        $month04 = explode(",", $data04_2);
        foreach ($month04 as $key => $value) {
            $newmonth04[] = date('Y-m-d', strtotime($value));
        }

        $data05 = str_replace("[", "", $request->calendar05);
        $data05_1 = str_replace("]", "", $data05);
        $data05_2 = str_replace('"', "", $data05_1);
        $month05 = explode(",", $data05_2);
        foreach ($month05 as $key => $value) {
            $newmonth05[] = date('Y-m-d', strtotime($value));
        }

        $data06 = str_replace("[", "", $request->calendar06);
        $data06_1 = str_replace("]", "", $data06);
        $data06_2 = str_replace('"', "", $data06_1);
        $month06 = explode(",", $data06_2);
        foreach ($month06 as $key => $value) {
            $newmonth06[] = date('Y-m-d', strtotime($value));
        }

        $data07 = str_replace("[", "", $request->calendar07);
        $data07_1 = str_replace("]", "", $data07);
        $data07_2 = str_replace('"', "", $data07_1);
        $month07 = explode(",", $data07_2);
        foreach ($month07 as $key => $value) {
            $newmonth07[] = date('Y-m-d', strtotime($value));
        }

        $data08 = str_replace("[", "", $request->calendar08);
        $data08_1 = str_replace("]", "", $data08);
        $data08_2 = str_replace('"', "", $data08_1);
        $month08 = explode(",", $data08_2);
        foreach ($month08 as $key => $value) {
            $newmonth08[] = date('Y-m-d', strtotime($value));
        }

        $data09 = str_replace("[", "", $request->calendar09);
        $data09_1 = str_replace("]", "", $data09);
        $data09_2 = str_replace('"', "", $data09_1);
        $month09 = explode(",", $data09_2);
        foreach ($month09 as $key => $value) {
            $newmonth09[] = date('Y-m-d', strtotime($value));
        }

        $data10 = str_replace("[", "", $request->calendar10);
        $data10_1 = str_replace("]", "", $data10);
        $data10_2 = str_replace('"', "", $data10_1);
        $month10 = explode(",", $data10_2);
        foreach ($month10 as $key => $value) {
            $newmonth10[] = date('Y-m-d', strtotime($value));
        }

        $data11 = str_replace("[", "", $request->calendar11);
        $data11_1 = str_replace("]", "", $data11);
        $data11_2 = str_replace('"', "", $data11_1);
        $month11 = explode(",", $data11_2);
        foreach ($month11 as $key => $value) {
            $newmonth11[] = date('Y-m-d', strtotime($value));
        }
 
        $data12 = str_replace("[", "", $request->calendar12);
        $data12_1 = str_replace("]", "", $data12);
        $data12_2 = str_replace('"', "", $data12_1);
        $month12 = explode(",", $data12_2);
        foreach ($month12 as $key => $value) {
            $newmonth12[] = date('Y-m-d', strtotime($value));
        }


        $newmonth01_1 = implode(",", $newmonth01);
        $parameter_1 = [
            'id_tsetting' => $request->id_tsetting,
            'year' => $request->post_year,
            'month' => 1,
            'textdate' => $newmonth01_1,
            'countday' => count($month01),
            'save_date' => date('Y-m-d H:i:s'),
        ]; 

        $newmonth02_1 = implode(",", $newmonth02);
        $parameter_2 = [
            'id_tsetting' => $request->id_tsetting,
            'year' => $request->post_year,
            'month' => 2,
            'textdate' => $newmonth02_1,
            'countday' => count($month02),
            'save_date' => date('Y-m-d H:i:s'),
        ]; 

        $newmonth03_1 = implode(",", $newmonth03);
        $parameter_3 = [
            'id_tsetting' => $request->id_tsetting,
            'year' => $request->post_year,
            'month' => 3,
            'textdate' => $newmonth03_1,
            'countday' => count($month03),
            'save_date' => date('Y-m-d H:i:s'),
        ]; 

        $newmonth04_1 = implode(",", $newmonth04);
        $parameter_4 = [
            'id_tsetting' => $request->id_tsetting,
            'year' => $request->post_year,
            'month' => 4,
            'textdate' => $newmonth04_1,
            'countday' => count($month04),
            'save_date' => date('Y-m-d H:i:s'),
        ]; 

        $newmonth05_1 = implode(",", $newmonth05);
        $parameter_5 = [
            'id_tsetting' => $request->id_tsetting,
            'year' => $request->post_year,
            'month' => 5,
            'textdate' => $newmonth05_1,
            'countday' => count($month05),
            'save_date' => date('Y-m-d H:i:s'),
        ]; 

        $newmonth06_1 = implode(",", $newmonth06);
        $parameter_6 = [
            'id_tsetting' => $request->id_tsetting,
            'year' => $request->post_year,
            'month' => 6,
            'textdate' => $newmonth06_1,
            'countday' => count($month06),
            'save_date' => date('Y-m-d H:i:s'),
        ]; 

        $newmonth07_1 = implode(",", $newmonth07);
        $parameter_7 = [
            'id_tsetting' => $request->id_tsetting,
            'year' => $request->post_year,
            'month' => 7,
            'textdate' => $newmonth07_1,
            'countday' => count($month07),
            'save_date' => date('Y-m-d H:i:s'),
        ]; 

        $newmonth08_1 = implode(",", $newmonth08);
        $parameter_8 = [
            'id_tsetting' => $request->id_tsetting,
            'year' => $request->post_year,
            'month' => 8,
            'textdate' => $newmonth08_1,
            'countday' => count($month08),
            'save_date' => date('Y-m-d H:i:s'),
        ]; 

        $newmonth09_1 = implode(",", $newmonth09);
        $parameter_9 = [
            'id_tsetting' => $request->id_tsetting,
            'year' => $request->post_year,
            'month' => 9,
            'textdate' => $newmonth09_1,
            'countday' => count($month09),
            'save_date' => date('Y-m-d H:i:s'),
        ]; 

        $newmonth10_1 = implode(",", $newmonth10);
        $parameter_10 = [
            'id_tsetting' => $request->id_tsetting,
            'year' => $request->post_year,
            'month' => 10,
            'textdate' => $newmonth10_1,
            'countday' => count($month10),
            'save_date' => date('Y-m-d H:i:s'),
        ];

        $newmonth11_1 = implode(",", $newmonth11);
        $parameter_11 = [
            'id_tsetting' => $request->id_tsetting,
            'year' => $request->post_year,
            'month' => 11,
            'textdate' => $newmonth11_1,
            'countday' => count($month11),
            'save_date' => date('Y-m-d H:i:s'),
        ];

        $newmonth12_1 = implode(",", $newmonth12);
        $parameter_12 = [
            'id_tsetting' => $request->id_tsetting,
            'year' => $request->post_year,
            'month' => 12,
            'textdate' => $newmonth12_1,
            'countday' => count($month10),
            'save_date' => date('Y-m-d H:i:s'),
        ];


        $get01 = DB::table('nt_measure_holiday')->where('id_tsetting',$request->id_tsetting)->where('year',$request->post_year)->where('month',1)->first();
        if(!$get01){
            DB::table('nt_measure_holiday')->insert($parameter_1);
        }else{
            DB::table('nt_measure_holiday')->where('id_holiday',$get01->id_holiday)->update($parameter_1);
        }

        $get02 = DB::table('nt_measure_holiday')->where('id_tsetting',$request->id_tsetting)->where('year',$request->post_year)->where('month',2)->first();
        if(!$get02){
            DB::table('nt_measure_holiday')->insert($parameter_2);
        }else{
            DB::table('nt_measure_holiday')->where('id_holiday',$get02->id_holiday)->update($parameter_2);
        }

        $get03 = DB::table('nt_measure_holiday')->where('id_tsetting',$request->id_tsetting)->where('year',$request->post_year)->where('month',3)->first();
        if(!$get03){
            DB::table('nt_measure_holiday')->insert($parameter_3);
        }else{
            DB::table('nt_measure_holiday')->where('id_holiday',$get03->id_holiday)->update($parameter_3);
        }

        $get04 = DB::table('nt_measure_holiday')->where('id_tsetting',$request->id_tsetting)->where('year',$request->post_year)->where('month',4)->first();
        if(!$get04){
            DB::table('nt_measure_holiday')->insert($parameter_4);
        }else{
            DB::table('nt_measure_holiday')->where('id_holiday',$get04->id_holiday)->update($parameter_4);
        }

        $get05 = DB::table('nt_measure_holiday')->where('id_tsetting',$request->id_tsetting)->where('year',$request->post_year)->where('month',5)->first();
        if(!$get05){
            DB::table('nt_measure_holiday')->insert($parameter_5);
        }else{
            DB::table('nt_measure_holiday')->where('id_holiday',$get05->id_holiday)->update($parameter_5);
        }

        $get06 = DB::table('nt_measure_holiday')->where('id_tsetting',$request->id_tsetting)->where('year',$request->post_year)->where('month',6)->first();
        if(!$get06){
            DB::table('nt_measure_holiday')->insert($parameter_6);
        }else{
            DB::table('nt_measure_holiday')->where('id_holiday',$get06->id_holiday)->update($parameter_6);
        }

        $get07 = DB::table('nt_measure_holiday')->where('id_tsetting',$request->id_tsetting)->where('year',$request->post_year)->where('month',7)->first();
        if(!$get07){
            DB::table('nt_measure_holiday')->insert($parameter_7);
        }else{
            DB::table('nt_measure_holiday')->where('id_holiday',$get07->id_holiday)->update($parameter_7);
        }

        $get08 = DB::table('nt_measure_holiday')->where('id_tsetting',$request->id_tsetting)->where('year',$request->post_year)->where('month',8)->first();
        if(!$get08){
            DB::table('nt_measure_holiday')->insert($parameter_8);
        }else{
            DB::table('nt_measure_holiday')->where('id_holiday',$get08->id_holiday)->update($parameter_8);
        }

        $get09 = DB::table('nt_measure_holiday')->where('id_tsetting',$request->id_tsetting)->where('year',$request->post_year)->where('month',9)->first();
        if(!$get09){
            DB::table('nt_measure_holiday')->insert($parameter_9);
        }else{
            DB::table('nt_measure_holiday')->where('id_holiday',$get09->id_holiday)->update($parameter_9);
        }

        $get10 = DB::table('nt_measure_holiday')->where('id_tsetting',$request->id_tsetting)->where('year',$request->post_year)->where('month',10)->first();
        if(!$get10){
            DB::table('nt_measure_holiday')->insert($parameter_10);
        }else{
            DB::table('nt_measure_holiday')->where('id_holiday',$get10->id_holiday)->update($parameter_10);
        }

        $get11 = DB::table('nt_measure_holiday')->where('id_tsetting',$request->id_tsetting)->where('year',$request->post_year)->where('month',11)->first();
        if(!$get11){
            DB::table('nt_measure_holiday')->insert($parameter_11);
        }else{
            DB::table('nt_measure_holiday')->where('id_holiday',$get11->id_holiday)->update($parameter_11);
        }

        $get12 = DB::table('nt_measure_holiday')->where('id_tsetting',$request->id_tsetting)->where('year',$request->post_year)->where('month',12)->first();
        if(!$get12){
            DB::table('nt_measure_holiday')->insert($parameter_12);
        }else{
            DB::table('nt_measure_holiday')->where('id_holiday',$get12->id_holiday)->update($parameter_12);
        }

        return response()->json(['success' => 1]);

        
    }

    public function reportmeasure() 
    {  
        return view('output/reportmeasure');
    }

    public function measuredatatablestest(Request $request) 
    { 


        $datestart = '';
        $dateend = ''; 
        $arrfirst = [];
        $itemlpc = [];
        $itemsem = [];
        $itemic = [];
        $itemgcms = [];
        $itemghost = [];
        $itemftir = [];

        $overlpc = 0;
        $oversem = 0;
        $overic = 0;
        $overgcms = 0;
        $overghost = 0;
        $overftir = 0;

        if(!empty($request->date_start) && !empty($request->date_end))
        {
                
                $datestart = date('Y-m-d 00:00:00', strtotime($request->date_start));
                $dateend = date('Y-m-d 23:59:00', strtotime($request->date_end));

                if(empty($request->textmodel) && empty($request->textlot))
                {

                    $oqa_file = DB::table('nt_oqa_file')
                    ->leftJoin('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_oqa_file.id_createlab')
                    ->leftJoin('nt_model', 'nt_model.id_model', '=', 'nt_oqa_lab.id_model')
                    ->where('nt_oqa_lab.save_date','>=',$datestart)->where('nt_oqa_lab.save_date','<=',$dateend)
                    ->select('nt_oqa_lab.*','nt_model.*','nt_oqa_file.*','nt_oqa_lab.id_createlab as id_createlabnew');

                }else{

                    $arrmodel = [];
                    if(!empty($request->textmodel))
                    {
                        $model = DB::table('nt_model')
                        ->where('nt_model.name_model','like','%'.$request->textmodel.'%')
                        ->get();
                        foreach ($model as $key => $value) {
                            $arrmodel[] = $value->id_model;
                        }
                    }
                    
                    $oqa_file = DB::table('nt_oqa_file')
                    ->leftJoin('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_oqa_file.id_createlab')
                    ->where('nt_oqa_lab.save_date','>=',$datestart)
                    ->where('nt_oqa_lab.save_date','<=',$dateend);

                    if(count($arrmodel) > 0)
                    {
                        $oqa_file->whereIn('nt_oqa_lab.id_model', $arrmodel);
                    }
     
                    if(!empty($request->textlot))
                    {
                        $oqa_file->where('nt_oqa_lab.lotnumber_lab','like','%'.$request->textlot.'%');
                    }
    
                    $oqa_file->where('nt_oqa_lab.type_lab','LIKE','SGT_%')->select('nt_oqa_lab.*','nt_oqa_file.*','nt_oqa_lab.id_createlab as id_createlabnew');

                }

                $oqa_file = $oqa_file->orderBy('nt_oqa_lab.save_date', 'ASC')->get();

                foreach ($oqa_file as $key => $value) {
                    $arrfirst[] = $value->file_id;
                }
 

                $UCLLPC = $this->checkUCL("LPC");
                $lpcget = DB::table('nt_oqa_file')
                    ->leftJoin('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_oqa_file.id_createlab')
                    ->whereIn('nt_oqa_file.file_id',$arrfirst)
                    ->where('nt_oqa_file.lpc_begin','!=', null)
                    ->where('nt_oqa_file.lpc_lasttime','!=', null)
                    ->orderBy('nt_oqa_file.lpc_begin', 'ASC')
                    ->limit(3000)
                    ->get();

                    foreach ($lpcget as $key => $value) {

                        $dateFrom = date('Y-m-d H:i:s', strtotime($value->lpc_begin));
                        $dateTo = date('Y-m-d H:i:s', strtotime($value->lpc_lasttime));

                        $countholidays = $this->checkNumHoliday(date('Y-m-d', strtotime($value->lpc_begin)),date('Y-m-d', strtotime($value->lpc_lasttime)));
                        $columnR = $this->timeDiffToDays($dateFrom,$dateTo);
                        $columnS = floatval($columnR) - floatval($countholidays);
                        $columnS = number_format($columnS,2,'.','');

                        if($columnS >= $UCLLPC){
                            $overlpc++;
                        } 
                        $itemlpc[] = $columnR;

                    }

                $UCLSEM = $this->checkUCL("SEM");
                $semget = DB::table('nt_oqa_file')
                    ->leftJoin('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_oqa_file.id_createlab')
                    ->whereIn('nt_oqa_file.file_id',$arrfirst)
                    ->where('nt_oqa_file.sem_begin','!=', null)
                    ->where('nt_oqa_file.sem_lasttime','!=', null)
                    ->orderBy('nt_oqa_file.sem_begin', 'ASC')
                    ->limit(3000)
                    ->get();

                    foreach ($semget as $key => $value) {

                        $dateFrom = date('Y-m-d H:i:s', strtotime($value->sem_begin));
                        $dateTo = date('Y-m-d H:i:s', strtotime($value->sem_lasttime));

                        $countholidays = $this->checkNumHoliday(date('Y-m-d', strtotime($value->sem_begin)),date('Y-m-d', strtotime($value->sem_lasttime)));
                        $columnR = $this->timeDiffToDays($dateFrom,$dateTo);
                        $columnS = floatval($columnR) - floatval($countholidays);
                        $columnS = number_format($columnS,2,'.','');

                        if($columnS >= $UCLSEM){
                            $oversem++;
                        } 
                        $itemsem[] = $columnR;

                    } 

                $UCLIC = $this->checkUCL("IC");
                $icget = DB::table('nt_oqa_file')
                    ->leftJoin('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_oqa_file.id_createlab')
                    ->whereIn('nt_oqa_file.file_id',$arrfirst)
                    ->where('nt_oqa_file.ic_begin','!=', null)
                    ->where('nt_oqa_file.ic_lasttime','!=', null)
                    ->orderBy('nt_oqa_file.ic_begin', 'ASC')
                    ->limit(3000)
                    ->get();

                    foreach ($icget as $key => $value) {

                        $dateFrom = date('Y-m-d H:i:s', strtotime($value->ic_begin));
                        $dateTo = date('Y-m-d H:i:s', strtotime($value->ic_lasttime));

                        $countholidays = $this->checkNumHoliday(date('Y-m-d', strtotime($value->ic_begin)),date('Y-m-d', strtotime($value->ic_lasttime)));
                        $columnR = $this->timeDiffToDays($dateFrom,$dateTo);
                        $columnS = floatval($columnR) - floatval($countholidays);
                        $columnS = number_format($columnS,2,'.','');

                        if($columnS >= $UCLIC){
                            $overic++;
                        } 
                        $itemic[] = $columnR;

                    }               

                $UCLGCMS = $this->checkUCL("GCMS");
                $gcmsget = DB::table('nt_oqa_file')
                    ->leftJoin('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_oqa_file.id_createlab')
                    ->whereIn('nt_oqa_file.file_id',$arrfirst)
                    ->where('nt_oqa_file.gcms_begin','!=', null)
                    ->where('nt_oqa_file.gcms_lasttime','!=', null)
                    ->orderBy('nt_oqa_file.gcms_begin', 'ASC')
                    ->limit(3000)
                    ->get();

                    foreach ($gcmsget as $key => $value) {

                        $dateFrom = date('Y-m-d H:i:s', strtotime($value->gcms_begin));
                        $dateTo = date('Y-m-d H:i:s', strtotime($value->gcms_lasttime));

                        $countholidays = $this->checkNumHoliday(date('Y-m-d', strtotime($value->gcms_begin)),date('Y-m-d', strtotime($value->gcms_lasttime)));
                        $columnR = $this->timeDiffToDays($dateFrom,$dateTo);
                        $columnS = floatval($columnR) - floatval($countholidays);
                        $columnS = number_format($columnS,2,'.','');

                        if($columnS >= $UCLGCMS){
                            $overgcms++;
                        } 
                        $itemgcms[] = $columnR;

                    }

                $UCLFTIR = $this->checkUCL("FTIR");
                $ftirget = DB::table('nt_oqa_file')
                    ->leftJoin('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_oqa_file.id_createlab')
                    ->whereIn('nt_oqa_file.file_id',$arrfirst)
                    ->where('nt_oqa_file.ftir_begin','!=', null)
                    ->where('nt_oqa_file.ftir_lasttime','!=', null)
                    ->orderBy('nt_oqa_file.ftir_begin', 'ASC')
                    ->limit(3000)
                    ->get();

                    foreach ($ftirget as $key => $value) {

                        $dateFrom = date('Y-m-d H:i:s', strtotime($value->ftir_begin));
                        $dateTo = date('Y-m-d H:i:s', strtotime($value->ftir_lasttime));

                        $countholidays = $this->checkNumHoliday(date('Y-m-d', strtotime($value->ftir_begin)),date('Y-m-d', strtotime($value->ftir_lasttime)));
                        $columnR = $this->timeDiffToDays($dateFrom,$dateTo);
                        $columnS = floatval($columnR) - floatval($countholidays);
                        $columnS = number_format($columnS,2,'.','');

                        if($columnS >= $UCLFTIR){
                            $overftir++;
                        } 
                        $itemftir[] = $columnR;

                    }

                $UCLGHOST = $this->checkUCL("GHOST");
                $ghostget = DB::table('nt_oqa_file')
                    ->leftJoin('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_oqa_file.id_createlab')
                    ->whereIn('nt_oqa_file.file_id',$arrfirst)
                    ->where('nt_oqa_file.semtest_begin','!=', null)
                    ->where('nt_oqa_file.semtest_lasttime','!=', null)
                    ->orderBy('nt_oqa_file.semtest_begin', 'ASC')
                    ->limit(3000)
                    ->get();

                    foreach ($ghostget as $key => $value) {

                        $dateFrom = date('Y-m-d H:i:s', strtotime($value->semtest_begin));
                        $dateTo = date('Y-m-d H:i:s', strtotime($value->semtest_lasttime));

                        $countholidays = $this->checkNumHoliday(date('Y-m-d', strtotime($value->semtest_begin)),date('Y-m-d', strtotime($value->semtest_lasttime)));
                        $columnR = $this->timeDiffToDays($dateFrom,$dateTo);
                        $columnS = floatval($columnR) - floatval($countholidays);
                        $columnS = number_format($columnS,2,'.','');

                        if($columnS >= $UCLGHOST){
                            $overghost++;
                        } 
                        $itemghost[] = $columnR;

                    }
        }

        $measurelist = DB::table('nt_measure');

        $sQuery = Datatables::of($measurelist)
        ->addIndexColumn()
        ->addColumn('ucl_lpc',function($data){
            return $this->checkUCL("LPC");
        })
        ->addColumn('ucl_sem',function($data){
            return $this->checkUCL("SEM");
        })
        ->addColumn('ucl_ic',function($data){
            return $this->checkUCL("IC");
        })
        ->addColumn('ucl_gcms',function($data){
            return $this->checkUCL("GCMS");
        })
        ->addColumn('ucl_ftir',function($data){
            return $this->checkUCL("FTIR");
        })
        ->addColumn('ucl_semtest',function($data){
            return $this->checkUCL("GHOST");
        })
        ->addColumn('nameitem',function($data){
            if($data->id_tsetting == 1){
                $name = 'Average Measurement L/T';
            }else{
                $name = 'Amount of Lot number <br> Measurement L/T over UCL';
            }
            return $name;
        })
        ->addColumn('avglpc',function($data) use ($itemlpc){
             
            return $this->calculateAverage($itemlpc);

        })
        ->addColumn('avgsem',function($data) use ($itemsem){
            
            return $this->calculateAverage($itemsem);

        })
        ->addColumn('avgic',function($data) use ($itemic){
            
            return $this->calculateAverage($itemic);

        })
        ->addColumn('avggcms',function($data) use ($itemgcms){
             
            return $this->calculateAverage($itemgcms);

        })
        ->addColumn('avgftir',function($data) use ($itemftir){ 

            return $this->calculateAverage($itemftir);

        })
        ->addColumn('avgghost',function($data) use ($itemghost){
            
            return $this->calculateAverage($itemghost);

        })
        ->addColumn('overlpc',function($data) use ($overlpc){
             
            return $overlpc;

        })
        ->addColumn('oversem',function($data) use ($oversem){
            
            return $oversem;

        })
        ->addColumn('overic',function($data) use ($overic){
            
            return $overic;

        })
        ->addColumn('overgcms',function($data) use ($overgcms){
             
            return $overgcms;

        })
        ->addColumn('overftir',function($data) use ($overftir){ 

            return $overftir;

        })
        ->addColumn('overghost',function($data) use ($overghost){
            
            return $overghost;

        });
        return $sQuery->escapeColumns([])->make(true);


    } 

    public function measuredatatables(Request $request) 
    { 

        $datestart = '';
        $dateend = ''; 
        $arrfirst = [];
        $itemlpc = [];
        $itemsem = [];
        $itemic = [];
        $itemgcms = [];
        $itemghost = [];
        $itemftir = [];

        $overlpc = 0;
        $oversem = 0;
        $overic = 0;
        $overgcms = 0;
        $overghost = 0;
        $overftir = 0;

        $arrid_createlab = [];
        $arrmodel = [];

        if(!empty($request->date_start) && !empty($request->date_end))
        {
            $datestart = date('Y-m-d 00:00:00', strtotime($request->date_start));
            $dateend = date('Y-m-d 23:59:00', strtotime($request->date_end));

            if(!empty($request->textlot))
            {
                $oqa_lab = DB::table('nt_oqa_lab')
                ->where('lotnumber_lab','like','%'.$request->textlot.'%')
                ->where('save_date','>=','2022-12-01')
                ->get();
                foreach ($oqa_lab as $key => $value) {
                    $arrid_createlab[] = $value->id_createlab;
                }

            }else{

                $arrmodel = [];
                if(!empty($request->textmodel))
                {
                    $model = DB::table('nt_model')
                    ->where('nt_model.name_model','like','%'.$request->textmodel.'%')
                    ->get();
                    foreach ($model as $key => $value) {
                        $arrmodel[] = $value->id_model;
                    } 

                    $oqa_lab = DB::table('nt_oqa_lab')
                    ->whereIn('id_model', $arrmodel)
                    ->where('save_date','>=','2022-12-01')
                    ->get();
                    foreach ($oqa_lab as $key => $value) {
                        $arrid_createlab[] = $value->id_createlab;
                    }

                }else{

                    $oqa_lab = DB::table('nt_oqa_lab')->where('save_date','>=','2022-12-01')->get();
                    foreach ($oqa_lab as $key => $value) {
                        $arrid_createlab[] = $value->id_createlab;
                    }
                }
            } 

            $UCLLPC = $this->checkUCL("LPC");
            $oqa_file_lpc = DB::table('nt_oqa_file')
            ->leftJoin('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_oqa_file.id_createlab')
            ->whereIn('nt_oqa_lab.id_createlab', $arrid_createlab)
            ->where('nt_oqa_file.lpc_begin','>=',$datestart)
            ->where('nt_oqa_file.lpc_lasttime','<=',$dateend)
            ->orderBy('nt_oqa_file.lpc_begin', 'ASC')
            ->limit(3000)
            ->get();
            foreach ($oqa_file_lpc as $key => $value) {

                $dateFrom = date('Y-m-d H:i:s', strtotime($value->lpc_begin));
                $dateTo = date('Y-m-d H:i:s', strtotime($value->lpc_lasttime));

                $countholidays = $this->checkNumHoliday(date('Y-m-d', strtotime($value->lpc_begin)),date('Y-m-d', strtotime($value->lpc_lasttime)));
                $columnR = $this->timeDiffToDays($dateFrom,$dateTo);
                $columnS = floatval($columnR) - floatval($countholidays);
                $columnS = number_format($columnS,2,'.','');

                if($columnS >= $UCLLPC){
                    $overlpc++;
                } 
                $itemlpc[] = $columnR;

            }

            $UCLSEM = $this->checkUCL("SEM");
            $oqa_file_sem = DB::table('nt_oqa_file')
            ->leftJoin('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_oqa_file.id_createlab')
            ->whereIn('nt_oqa_lab.id_createlab', $arrid_createlab)
            ->where('nt_oqa_file.sem_begin','>=',$datestart)
            ->where('nt_oqa_file.sem_lasttime','<=',$dateend)
            ->orderBy('nt_oqa_file.sem_begin', 'ASC')
            ->limit(3000)
            ->get();
            foreach ($oqa_file_sem as $key => $value) {

                $dateFrom = date('Y-m-d H:i:s', strtotime($value->sem_begin));
                $dateTo = date('Y-m-d H:i:s', strtotime($value->sem_lasttime));

                $countholidays = $this->checkNumHoliday(date('Y-m-d', strtotime($value->sem_begin)),date('Y-m-d', strtotime($value->sem_lasttime)));
                $columnR = $this->timeDiffToDays($dateFrom,$dateTo);
                $columnS = floatval($columnR) - floatval($countholidays);
                $columnS = number_format($columnS,2,'.','');

                if($columnS >= $UCLSEM){
                    $oversem++;
                } 
                $itemsem[] = $columnR;

            }

            $UCLIC = $this->checkUCL("IC");
            $oqa_file_ic = DB::table('nt_oqa_file')
            ->leftJoin('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_oqa_file.id_createlab')
            ->whereIn('nt_oqa_lab.id_createlab', $arrid_createlab)
            ->where('nt_oqa_file.ic_begin','>=',$datestart)
            ->where('nt_oqa_file.ic_lasttime','<=',$dateend)
            ->orderBy('nt_oqa_file.ic_begin', 'ASC')
            ->limit(3000)
            ->get();
            foreach ($oqa_file_ic as $key => $value) {

                $dateFrom = date('Y-m-d H:i:s', strtotime($value->ic_begin));
                $dateTo = date('Y-m-d H:i:s', strtotime($value->ic_lasttime));

                $countholidays = $this->checkNumHoliday(date('Y-m-d', strtotime($value->ic_begin)),date('Y-m-d', strtotime($value->ic_lasttime)));
                $columnR = $this->timeDiffToDays($dateFrom,$dateTo);
                $columnS = floatval($columnR) - floatval($countholidays);
                $columnS = number_format($columnS,2,'.','');

                if($columnS >= $UCLIC){
                    $overic++;
                } 
                $itemic[] = $columnR;

            }

            $UCLGCMS = $this->checkUCL("GCMS");
            $oqa_file_gcms = DB::table('nt_oqa_file')
            ->leftJoin('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_oqa_file.id_createlab')
            ->whereIn('nt_oqa_lab.id_createlab', $arrid_createlab)
            ->where('nt_oqa_file.gcms_begin','>=',$datestart)
            ->where('nt_oqa_file.gcms_lasttime','<=',$dateend)
            ->orderBy('nt_oqa_file.gcms_begin', 'ASC')
            ->limit(3000)
            ->get();
            foreach ($oqa_file_gcms as $key => $value) {

                $dateFrom = date('Y-m-d H:i:s', strtotime($value->gcms_begin));
                $dateTo = date('Y-m-d H:i:s', strtotime($value->gcms_lasttime));

                $countholidays = $this->checkNumHoliday(date('Y-m-d', strtotime($value->gcms_begin)),date('Y-m-d', strtotime($value->gcms_lasttime)));
                $columnR = $this->timeDiffToDays($dateFrom,$dateTo);
                $columnS = floatval($columnR) - floatval($countholidays);
                $columnS = number_format($columnS,2,'.','');

                if($columnS >= $UCLGCMS){
                    $overgcms++;
                } 
                $itemgcms[] = $columnR;

            }

            $UCLFTIR = $this->checkUCL("FTIR");
            $oqa_file_ftir = DB::table('nt_oqa_file')
            ->leftJoin('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_oqa_file.id_createlab')
            ->whereIn('nt_oqa_lab.id_createlab', $arrid_createlab)
            ->where('nt_oqa_file.ftir_begin','>=',$datestart)
            ->where('nt_oqa_file.ftir_lasttime','<=',$dateend)
            ->orderBy('nt_oqa_file.ftir_begin', 'ASC')
            ->limit(3000)
            ->get();
            foreach ($oqa_file_ftir as $key => $value) {

                $dateFrom = date('Y-m-d H:i:s', strtotime($value->ftir_begin));
                $dateTo = date('Y-m-d H:i:s', strtotime($value->ftir_lasttime));

                $countholidays = $this->checkNumHoliday(date('Y-m-d', strtotime($value->ftir_begin)),date('Y-m-d', strtotime($value->ftir_lasttime)));
                $columnR = $this->timeDiffToDays($dateFrom,$dateTo);
                $columnS = floatval($columnR) - floatval($countholidays);
                $columnS = number_format($columnS,2,'.','');

                if($columnS >= $UCLFTIR){
                    $overftir++;
                } 
                $itemftir[] = $columnR;

            }

            $UCLGHOST = $this->checkUCL("GHOST");
            $oqa_file_ghost = DB::table('nt_oqa_file')
            ->leftJoin('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_oqa_file.id_createlab')
            ->whereIn('nt_oqa_lab.id_createlab', $arrid_createlab)
            ->where('nt_oqa_file.semtest_begin','>=',$datestart)
            ->where('nt_oqa_file.semtest_lasttime','<=',$dateend)
            ->orderBy('nt_oqa_file.semtest_begin', 'ASC')
            ->limit(3000)
            ->get();
            foreach ($oqa_file_ghost as $key => $value) {

                $dateFrom = date('Y-m-d H:i:s', strtotime($value->semtest_begin));
                $dateTo = date('Y-m-d H:i:s', strtotime($value->semtest_lasttime));

                $countholidays = $this->checkNumHoliday(date('Y-m-d', strtotime($value->semtest_begin)),date('Y-m-d', strtotime($value->semtest_lasttime)));
                $columnR = $this->timeDiffToDays($dateFrom,$dateTo);
                $columnS = floatval($columnR) - floatval($countholidays);
                $columnS = number_format($columnS,2,'.','');

                if($columnS >= $UCLGHOST){
                    $overghost++;
                } 
                $itemghost[] = $columnR;

            }

            $measurelist = DB::table('nt_measure');

            $sQuery = Datatables::of($measurelist)
            ->addIndexColumn()
            ->addColumn('ucl_lpc',function($data){
                return $this->checkUCL("LPC");
            })
            ->addColumn('ucl_sem',function($data){
                return $this->checkUCL("SEM");
            })
            ->addColumn('ucl_ic',function($data){
                return $this->checkUCL("IC");
            })
            ->addColumn('ucl_gcms',function($data){
                return $this->checkUCL("GCMS");
            })
            ->addColumn('ucl_ftir',function($data){
                return $this->checkUCL("FTIR");
            })
            ->addColumn('ucl_semtest',function($data){
                return $this->checkUCL("GHOST");
            })
            ->addColumn('nameitem',function($data){
                if($data->id_tsetting == 1){
                    $name = 'Average Measurement L/T';
                }else{
                    $name = 'Amount of Lot number <br> Measurement L/T over UCL';
                }
                return $name;
            })
            ->addColumn('avglpc',function($data) use ($itemlpc){
                
                return $this->calculateAverage($itemlpc);

            })
            ->addColumn('avgsem',function($data) use ($itemsem){
                
                return $this->calculateAverage($itemsem);

            })
            ->addColumn('avgic',function($data) use ($itemic){
                
                return $this->calculateAverage($itemic);

            })
            ->addColumn('avggcms',function($data) use ($itemgcms){
                
                return $this->calculateAverage($itemgcms);

            })
            ->addColumn('avgftir',function($data) use ($itemftir){ 

                return $this->calculateAverage($itemftir);

            })
            ->addColumn('avgghost',function($data) use ($itemghost){
                
                return $this->calculateAverage($itemghost);

            })
            ->addColumn('overlpc',function($data) use ($overlpc){
                
                return $overlpc;

            })
            ->addColumn('oversem',function($data) use ($oversem){
                
                return $oversem;

            })
            ->addColumn('overic',function($data) use ($overic){
                
                return $overic;

            })
            ->addColumn('overgcms',function($data) use ($overgcms){
                
                return $overgcms;

            })
            ->addColumn('overftir',function($data) use ($overftir){ 

                return $overftir;

            })
            ->addColumn('overghost',function($data) use ($overghost){
                
                return $overghost;

            });
            return $sQuery->escapeColumns([])->make(true);                
  
        }

        $measurelist = DB::table('nt_measure');

        $sQuery = Datatables::of($measurelist)
        ->addIndexColumn()
        ->addColumn('ucl_lpc',function($data){
            return $this->checkUCL("LPC");
        })
        ->addColumn('ucl_sem',function($data){
            return $this->checkUCL("SEM");
        })
        ->addColumn('ucl_ic',function($data){
            return $this->checkUCL("IC");
        })
        ->addColumn('ucl_gcms',function($data){
            return $this->checkUCL("GCMS");
        })
        ->addColumn('ucl_ftir',function($data){
            return $this->checkUCL("FTIR");
        })
        ->addColumn('ucl_semtest',function($data){
            return $this->checkUCL("GHOST");
        })
        ->addColumn('nameitem',function($data){
            if($data->id_tsetting == 1){
                $name = 'Average Measurement L/T';
            }else{
                $name = 'Amount of Lot number <br> Measurement L/T over UCL';
            }
            return $name;
        })
        ->addColumn('avglpc',function($data) use ($itemlpc){
             
            return $this->calculateAverage($itemlpc);

        })
        ->addColumn('avgsem',function($data) use ($itemsem){
            
            return $this->calculateAverage($itemsem);

        })
        ->addColumn('avgic',function($data) use ($itemic){
            
            return $this->calculateAverage($itemic);

        })
        ->addColumn('avggcms',function($data) use ($itemgcms){
             
            return $this->calculateAverage($itemgcms);

        })
        ->addColumn('avgftir',function($data) use ($itemftir){ 

            return $this->calculateAverage($itemftir);

        })
        ->addColumn('avgghost',function($data) use ($itemghost){
            
            return $this->calculateAverage($itemghost);

        })
        ->addColumn('overlpc',function($data) use ($overlpc){
             
            return $overlpc;

        })
        ->addColumn('oversem',function($data) use ($oversem){
            
            return $oversem;

        })
        ->addColumn('overic',function($data) use ($overic){
            
            return $overic;

        })
        ->addColumn('overgcms',function($data) use ($overgcms){
             
            return $overgcms;

        })
        ->addColumn('overftir',function($data) use ($overftir){ 

            return $overftir;

        })
        ->addColumn('overghost',function($data) use ($overghost){
            
            return $overghost;

        });
        return $sQuery->escapeColumns([])->make(true);


    } 

    public function calculateAverage(array $numbers)
    {
        if (empty($numbers)) {
            return 0;
        }
        $sum = array_sum($numbers);
        $count = count($numbers);

        return number_format( ($sum / $count), 2, '.', '');
    }
    
    public function checkUCL($store)
    {

        $measure_day = DB::table('nt_measure_day')->where('id_ucl',1)->first();
        if ($store == "LPC") {
            return $measure_day->lpc;
        }elseif ($store == "SEM") {
            return $measure_day->sem;
        }elseif ($store == "GCMS") {
            return $measure_day->gcms;
        }elseif ($store == "FTIR") {
            return $measure_day->ftir;
        }elseif ($store == "GHOST") {
            return $measure_day->sem;
        }elseif ($store == "IC") {
            return $measure_day->ic;
        }

    }

    public function timeDiffToDays($startTime, $endTime) 
    {
        $startTimestamp = strtotime($startTime);
        $endTimestamp = strtotime($endTime);
        $timeDiffSeconds = $endTimestamp - $startTimestamp;
        $days = $timeDiffSeconds / 86400; // 86400 = จำนวนวินาทีใน 1 วัน
        return ($days > 0)? $days : 0;
    }


    public function checkNumHoliday($datestart,$dateend)
    {
            $arrholiday = [];
            $Holiday = DB::table('nt_measure_holiday')->select('textdate')->whereBetween('year',[date('Y', strtotime($datestart)),date('Y', strtotime($dateend))])->WhereNotNull('textdate')->get();
            foreach ($Holiday as $key => $value) {
                
                $arrnew = explode(",", $value->textdate);
                foreach ($arrnew as $item) {
                    $arrholiday[] = $item;
                }
            }

            $start = strtotime($datestart);
            $end =   strtotime($dateend);

            $count = 0;
            foreach($arrholiday AS $date) {
                $timestamp = strtotime($date);
                if($timestamp >= $start && $timestamp <= $end) {
                    $count++;
                }
            }

            return $count;

    }
 
    public function excel_measure(Request $request)
    {
            
            ini_set('memory_limit', '-1');
            set_time_limit(8000);

            $arrid_createlab = [];
            $arrmodel = [];

            $measure_day = DB::table('nt_measure_day')->where('id_ucl',1)->first();

            if(empty($request->datestart) && empty($request->dateend) && empty($request->model) && empty($request->lot))
            {
                $now = date("Y-m-d 00:00:00");
                $datestart = date('Y-m-d H:i:s', strtotime($now));
                $dateend = date('Y-m-d H:i:s', strtotime('+1 day', strtotime($now)));

                $oqa_lab = DB::table('nt_oqa_lab')->where('save_date','>=','2022-12-01')->get();
                foreach ($oqa_lab as $key => $value) {
                    $arrid_createlab[] = $value->id_createlab;
                }

            }else{

                $datestart = date('Y-m-d 00:00:00', strtotime($request->datestart));
                $dateend = date('Y-m-d 23:59:00', strtotime($request->dateend)); 

                if(!empty($request->lot))
                {
                    $oqa_lab = DB::table('nt_oqa_lab')
                    ->where('lotnumber_lab','like','%'.$request->lot.'%')
                    ->where('save_date','>=','2022-12-01')
                    ->get();
                    foreach ($oqa_lab as $key => $value) {
                        $arrid_createlab[] = $value->id_createlab;
                    }

                }else{

                    if(!empty($request->model))
                    {
                        $model = DB::table('nt_model')
                        ->where('nt_model.name_model','like','%'.$request->model.'%')
                        ->get();
                        foreach ($model as $key => $value) {
                            $arrmodel[] = $value->id_model;
                        } 

                        $oqa_lab = DB::table('nt_oqa_lab')
                        ->whereIn('id_model', $arrmodel)
                        ->where('save_date','>=','2022-12-01')
                        ->get();
                        foreach ($oqa_lab as $key => $value) {
                            $arrid_createlab[] = $value->id_createlab;
                        }

                    }else{

                        $oqa_lab = DB::table('nt_oqa_lab')->where('save_date','>=','2022-12-01')->get();
                        foreach ($oqa_lab as $key => $value) {
                            $arrid_createlab[] = $value->id_createlab;
                        }
                    }
                } 
            } 

            $namefile = 'MeasurementLT_'.date('YmdHis');
            $file = storage_path('NHK-File/template/report/Format_Measurement_v3.xlsx');
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
            $sheet = $spreadsheet->setActiveSheetIndexByName('LPC');
            $sheet2 = $spreadsheet->setActiveSheetIndexByName('SEM');
            $sheet3 = $spreadsheet->setActiveSheetIndexByName('IC');
            $sheet4 = $spreadsheet->setActiveSheetIndexByName('GCMS');
            $sheet5 = $spreadsheet->setActiveSheetIndexByName('GHOST');
            $sheet6 = $spreadsheet->setActiveSheetIndexByName('FTIR');

            //---------- BEGIN LPC ---------------

                $lpcget = DB::table('nt_oqa_file')
                ->leftJoin('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_oqa_file.id_createlab')
                ->whereIn('nt_oqa_lab.id_createlab', $arrid_createlab)
                ->where('nt_oqa_file.lpc_begin','>=',$datestart)
                ->where('nt_oqa_file.lpc_lasttime','<=',$dateend)
                ->orderBy('nt_oqa_file.lpc_begin', 'ASC')
                ->limit(3000)
                ->get();

                $row = 4;
                foreach ($lpcget as $key => $value) {

                    $no = $key+1;

                    $model = DB::table('nt_model')->where('id_model', $value->id_model)->first();
                    $lot = DB::table('nt_oqa_lab')->where('id_createlab', $value->id_createlab)->first();
                    $countholidays = $this->checkNumHoliday(date('Y-m-d', strtotime($value->lpc_begin)),date('Y-m-d', strtotime($value->lpc_lasttime)));
     
                    $sheet->setCellValue('C'.$row, $model->name_model);
                    $sheet->setCellValue('D'.$row, $lot->lotnumber_lab); 
                    $sheet->setCellValue('E'.$row, date('YmdHis', strtotime($value->lpc_begin)));
                    $sheet->setCellValue('F'.$row, date('YmdHis', strtotime($value->lpc_lasttime)));
                    $sheet->setCellValue('T'.$row, $measure_day->lpc);
                    $sheet->setCellValue('G'.$row, $countholidays);

                    $dateFrom = date('Y-m-d H:i:s', strtotime($value->lpc_begin));
                    $dateTo = date('Y-m-d H:i:s', strtotime($value->lpc_lasttime));
                    $sheet->setCellValue('R'.$row, $this->timeDiffToDays($dateFrom,$dateTo) );

                    $columnR = $this->timeDiffToDays($dateFrom,$dateTo);
                    $columnS = floatval($columnR) - floatval($countholidays);

                      
                    $row++; 
                }

                //----------- สร้างกราฟ LPC -------------

                $dataSeriesLabels = [
                    new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'LPC!$S$3', null, 1), 
                ];

                $xAxisTickValues = [
                    new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'LPC!$A$4:$A$'.($row-1), null, count($lpcget)), 
                ];

                $dataSeriesValues = [
                    new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'LPC!$S$4:$S$'.($row-1), null, count($lpcget)),
                ];


                $xAxisTickValues2 = [
                    new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'LPC!$T$4:$T$'.($row-1), null, count($lpcget)), 
                ];
                $dataSeriesLabels2 = [
                    new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'LPC!$T$3', null, 1), 
                ];
                $dataSeriesValues2 = [
                    new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'LPC!$T$4:$T$'.($row-1), null, count($lpcget)),
                ];

                // สร้างชุดข้อมูล ที่จะใช้สร้างกราฟเส้น
                $series2 = new DataSeries(
                    DataSeries::TYPE_LINECHART,  
                    null,  
                    range(0, count($dataSeriesValues2) - 1),  
                    $dataSeriesLabels2, // ชื่อกำกับชุดข้อมูล 
                    $xAxisTickValues2, // ข้อความสำหรับแกน x
                    $dataSeriesValues2  // ค่าข้อมูล
                );

                // สร้างชุดข้อมูล ที่จะใช้สร้างแผนภูมิ
                $series = new DataSeries(
                    DataSeries::TYPE_BARCHART, // ประเภทของแผนภูมิ
                    null, // การจัดกลุ่มข้อมูล 
                    range(0, count($dataSeriesValues) - 1), // ลำดับข้อมูล เรียงจาก array 0 - 2
                    $dataSeriesLabels, // ชื่อกำกับชุดข้อมูล 
                    $xAxisTickValues, // ข้อความสำหรับแกน x
                    $dataSeriesValues        // ค่าข้อมูล
                );

                $plotArea = new PlotArea(null, [$series,$series2]);
     
                $legend = new Legend(Legend::POSITION_TOP, null, false); // กำหนดตำแหน่งของคำอธิบายชุดข้อมูล
                $title = new Title('Measurement L/T of LPC'); // หัวข้อแผนภูมิ
                $xAxisLabel = new Title('Lot number sequence'); // ข้อความอธิบายกำกับแกน X // (ถ้ามี)
                $yAxisLabel = new Title('Measurement L/T (Days)'); // ข้อความอธิบายกำกับแกน Y
                 
                // สร้างแผนภูมิ โดยใช้ค่าต่างๆ จากตัวแปรที่กำหนด 
                $chart = new Chart(
                    'chart1', // กำหนดชื่อ
                    $title, // กำหนดชื่อเรื่องหรือหัวข้อกำกับ
                    $legend, // ตำแหน่งคำอธิบายของชุดข้อมูล 
                    $plotArea, // พิกัดพื้นที่ชุดข้อมูล
                    true, // กำหนดให้แสดงแผนภูมิ
                    DataSeries::EMPTY_AS_GAP, // กำหนดหากเป็นค่าว่าง ให้ใช้ค่านี้แทน ในที่นี้คือ 0
                    $xAxisLabel,  // ข้อความอธิบายกำกับแกน X ในที่นี้ไม่มีกำหนด
                    $yAxisLabel  // ข้อความอธิบายกำกับแกน Y
                );
                 
                // กำหนดพิกัดมุมบนซ้าย และมุมล่างขวา เป็นพื้นที่สำหรับแสดงแผนภูมิ
                if(count($lpcget) > 0){
                    $chart->setTopLeftPosition('V10');
                    $chart->setBottomRightPosition('AL33');
                    $sheet->addChart($chart);
                }

            //---------- ENG LPC ---------------

            //---------- BEGIN SEM ---------------

                $semget = DB::table('nt_oqa_file')
                ->leftJoin('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_oqa_file.id_createlab')
                ->whereIn('nt_oqa_lab.id_createlab', $arrid_createlab)
                ->where('nt_oqa_file.sem_begin','>=',$datestart)
                ->where('nt_oqa_file.sem_lasttime','<=',$dateend)
                ->orderBy('nt_oqa_file.sem_begin', 'ASC')
                ->limit(3000)
                ->get();

                $row = 4; 
                foreach ($semget as $key => $value) {

                    $no = $key+1;

                    $model = DB::table('nt_model')->where('id_model', $value->id_model)->first();
                    $lot = DB::table('nt_oqa_lab')->where('id_createlab', $value->id_createlab)->first();
                    $countholidays = $this->checkNumHoliday(date('Y-m-d', strtotime($value->sem_begin)),date('Y-m-d', strtotime($value->sem_lasttime)));

                    $sheet2->setCellValue('C'.$row, $model->name_model);
                    $sheet2->setCellValue('D'.$row, $lot->lotnumber_lab); 
                    $sheet2->setCellValue('E'.$row, date('YmdHis', strtotime($value->sem_begin)));
                    $sheet2->setCellValue('F'.$row, date('YmdHis', strtotime($value->sem_lasttime)));
                    $sheet2->setCellValue('T'.$row, $measure_day->sem);
                    $sheet2->setCellValue('G'.$row, $countholidays);

                    $dateFrom = date('Y-m-d H:i:s', strtotime($value->sem_begin));
                    $dateTo = date('Y-m-d H:i:s', strtotime($value->sem_lasttime));
                    $sheet2->setCellValue('R'.$row, $this->timeDiffToDays($dateFrom,$dateTo) );

                    $row++; 
                }

                //----------- สร้างกราฟ SEM -------------

                $dataSeriesLabels = [
                    new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'SEM!$S$3', null, 1), 
                ];

                $xAxisTickValues = [
                    new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'SEM!$A$4:$A$'.($row-1), null, count($semget)), 
                ];

                $dataSeriesValues = [
                    new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'SEM!$S$4:$S$'.($row-1), null, count($semget)),
                ];

                $xAxisTickValues2 = [
                    new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'SEM!$T$4:$T$'.($row-1), null, count($semget)), 
                ];
                $dataSeriesLabels2 = [
                    new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'SEM!$T$3', null, 1), 
                ];
                $dataSeriesValues2 = [
                    new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'SEM!$T$4:$T$'.($row-1), null, count($semget)),
                ];

                // สร้างชุดข้อมูล ที่จะใช้สร้างกราฟเส้น
                $series2 = new DataSeries(
                    DataSeries::TYPE_LINECHART,  
                    null,  
                    range(0, count($dataSeriesValues2) - 1),  
                    $dataSeriesLabels2, // ชื่อกำกับชุดข้อมูล 
                    $xAxisTickValues2, // ข้อความสำหรับแกน x
                    $dataSeriesValues2  // ค่าข้อมูล
                );

                // สร้างชุดข้อมูล ที่จะใช้สร้างแผนภูมิ
                $series = new DataSeries(
                    DataSeries::TYPE_BARCHART, // ประเภทของแผนภูมิ
                    null, // การจัดกลุ่มข้อมูล 
                    range(0, count($dataSeriesValues) - 1), // ลำดับข้อมูล เรียงจาก array 0 - 2
                    $dataSeriesLabels, // ชื่อกำกับชุดข้อมูล 
                    $xAxisTickValues, // ข้อความสำหรับแกน x
                    $dataSeriesValues        // ค่าข้อมูล
                );

                $plotArea = new PlotArea(null, [$series,$series2]);
     
                $legend = new Legend(Legend::POSITION_TOP, null, false); // กำหนดตำแหน่งของคำอธิบายชุดข้อมูล
                $title = new Title('Measurement L/T of SEM'); // หัวข้อแผนภูมิ
                $xAxisLabel = new Title('Lot number sequence'); // ข้อความอธิบายกำกับแกน X // (ถ้ามี)
                $yAxisLabel = new Title('Measurement L/T (Days)'); // ข้อความอธิบายกำกับแกน Y
                 
                // สร้างแผนภูมิ โดยใช้ค่าต่างๆ จากตัวแปรที่กำหนด 
                $chart2 = new Chart(
                    'chart1', // กำหนดชื่อ
                    $title, // กำหนดชื่อเรื่องหรือหัวข้อกำกับ
                    $legend, // ตำแหน่งคำอธิบายของชุดข้อมูล 
                    $plotArea, // พิกัดพื้นที่ชุดข้อมูล
                    true, // กำหนดให้แสดงแผนภูมิ
                    DataSeries::EMPTY_AS_GAP, // กำหนดหากเป็นค่าว่าง ให้ใช้ค่านี้แทน ในที่นี้คือ 0
                    $xAxisLabel,  // ข้อความอธิบายกำกับแกน X ในที่นี้ไม่มีกำหนด
                    $yAxisLabel  // ข้อความอธิบายกำกับแกน Y
                );
                
                if(count($semget) > 0){
                    // กำหนดพิกัดมุมบนซ้าย และมุมล่างขวา เป็นพื้นที่สำหรับแสดงแผนภูมิ
                    $chart2->setTopLeftPosition('V10');
                    $chart2->setBottomRightPosition('AL33');
                    $sheet2->addChart($chart2);
                }

            //---------- ENG SEM ---------------

            //---------- BEGIN IC ---------------

                $icget = DB::table('nt_oqa_file')
                ->leftJoin('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_oqa_file.id_createlab')
                ->whereIn('nt_oqa_lab.id_createlab', $arrid_createlab)
                ->where('nt_oqa_file.ic_begin','>=',$datestart)
                ->where('nt_oqa_file.ic_lasttime','<=',$dateend)
                ->orderBy('nt_oqa_file.ic_begin', 'ASC')
                ->limit(3000)
                ->get();

                $row = 4;
                foreach ($icget as $key => $value) {

                    $no = $key+1;

                    $model = DB::table('nt_model')->where('id_model', $value->id_model)->first();
                    $lot = DB::table('nt_oqa_lab')->where('id_createlab', $value->id_createlab)->first();
                    $countholidays = $this->checkNumHoliday(date('Y-m-d', strtotime($value->ic_begin)),date('Y-m-d', strtotime($value->ic_lasttime)));

                    $sheet3->setCellValue('C'.$row, $model->name_model);
                    $sheet3->setCellValue('D'.$row, $lot->lotnumber_lab); 
                    $sheet3->setCellValue('E'.$row, date('YmdHis', strtotime($value->ic_begin)));
                    $sheet3->setCellValue('F'.$row, date('YmdHis', strtotime($value->ic_lasttime)));
                    $sheet3->setCellValue('T'.$row, $measure_day->ic);
                    $sheet3->setCellValue('G'.$row, $countholidays);

                    $dateFrom = date('Y-m-d H:i:s', strtotime($value->ic_begin));
                    $dateTo = date('Y-m-d H:i:s', strtotime($value->ic_lasttime));
                    $sheet3->setCellValue('R'.$row, $this->timeDiffToDays($dateFrom,$dateTo) );

                    $row++; 
                }

                //----------- สร้างกราฟ IC -------------

                $dataSeriesLabels = [
                    new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'IC!$S$3', null, 1), 
                ];

                $xAxisTickValues = [
                    new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'IC!$A$4:$A$'.($row-1), null, count($icget)), 
                ];

                $dataSeriesValues = [
                    new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'IC!$S$4:$S$'.($row-1), null, count($icget)),
                ];

                $xAxisTickValues2 = [
                    new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'IC!$T$4:$T$'.($row-1), null, count($icget)), 
                ];
                $dataSeriesLabels2 = [
                    new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'IC!$T$3', null, 1), 
                ];
                $dataSeriesValues2 = [
                    new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'IC!$T$4:$T$'.($row-1), null, count($icget)),
                ];

                // สร้างชุดข้อมูล ที่จะใช้สร้างกราฟเส้น
                $series2 = new DataSeries(
                    DataSeries::TYPE_LINECHART,  
                    null,  
                    range(0, count($dataSeriesValues2) - 1),  
                    $dataSeriesLabels2, // ชื่อกำกับชุดข้อมูล 
                    $xAxisTickValues2, // ข้อความสำหรับแกน x
                    $dataSeriesValues2  // ค่าข้อมูล
                );

                // สร้างชุดข้อมูล ที่จะใช้สร้างแผนภูมิ
                $series = new DataSeries(
                    DataSeries::TYPE_BARCHART, // ประเภทของแผนภูมิ
                    null, // การจัดกลุ่มข้อมูล 
                    range(0, count($dataSeriesValues) - 1), // ลำดับข้อมูล เรียงจาก array 0 - 2
                    $dataSeriesLabels, // ชื่อกำกับชุดข้อมูล 
                    $xAxisTickValues, // ข้อความสำหรับแกน x
                    $dataSeriesValues        // ค่าข้อมูล
                );

                $plotArea = new PlotArea(null, [$series,$series2]);
     
                $legend = new Legend(Legend::POSITION_TOP, null, false); // กำหนดตำแหน่งของคำอธิบายชุดข้อมูล
                $title = new Title('Measurement L/T of IC'); // หัวข้อแผนภูมิ
                $xAxisLabel = new Title('Lot number sequence'); // ข้อความอธิบายกำกับแกน X // (ถ้ามี)
                $yAxisLabel = new Title('Measurement L/T (Days)'); // ข้อความอธิบายกำกับแกน Y
                 
                // สร้างแผนภูมิ โดยใช้ค่าต่างๆ จากตัวแปรที่กำหนด 
                $chart3 = new Chart(
                    'chart1', // กำหนดชื่อ
                    $title, // กำหนดชื่อเรื่องหรือหัวข้อกำกับ
                    $legend, // ตำแหน่งคำอธิบายของชุดข้อมูล 
                    $plotArea, // พิกัดพื้นที่ชุดข้อมูล
                    true, // กำหนดให้แสดงแผนภูมิ
                    DataSeries::EMPTY_AS_GAP, // กำหนดหากเป็นค่าว่าง ให้ใช้ค่านี้แทน ในที่นี้คือ 0
                    $xAxisLabel,  // ข้อความอธิบายกำกับแกน X ในที่นี้ไม่มีกำหนด
                    $yAxisLabel  // ข้อความอธิบายกำกับแกน Y
                );
                
                if(count($icget) > 0){
                    // กำหนดพิกัดมุมบนซ้าย และมุมล่างขวา เป็นพื้นที่สำหรับแสดงแผนภูมิ
                    $chart3->setTopLeftPosition('V10');
                    $chart3->setBottomRightPosition('AL33');
                    $sheet3->addChart($chart3);
                }

            //---------- ENG IC ---------------

            //---------- BEGIN GCMS ---------------

                $gcmsget = DB::table('nt_oqa_file')
                ->leftJoin('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_oqa_file.id_createlab')
                ->whereIn('nt_oqa_lab.id_createlab', $arrid_createlab)
                ->where('nt_oqa_file.gcms_begin','>=',$datestart)
                ->where('nt_oqa_file.gcms_lasttime','<=',$dateend)
                ->orderBy('nt_oqa_file.gcms_begin', 'ASC')
                ->limit(3000)
                ->get();

                $row = 4; 
                foreach ($gcmsget as $key => $value) {

                    $no = $key+1;

                    $model = DB::table('nt_model')->where('id_model', $value->id_model)->first();
                    $lot = DB::table('nt_oqa_lab')->where('id_createlab', $value->id_createlab)->first();
                    $countholidays = $this->checkNumHoliday(date('Y-m-d', strtotime($value->gcms_begin)),date('Y-m-d', strtotime($value->gcms_lasttime)));

                    $sheet4->setCellValue('C'.$row, $model->name_model);
                    $sheet4->setCellValue('D'.$row, $lot->lotnumber_lab); 
                    $sheet4->setCellValue('E'.$row, date('YmdHis', strtotime($value->gcms_begin)));
                    $sheet4->setCellValue('F'.$row, date('YmdHis', strtotime($value->gcms_lasttime)));
                    $sheet4->setCellValue('T'.$row, $measure_day->gcms);
                    $sheet4->setCellValue('G'.$row, $countholidays);

                    $dateFrom = date('Y-m-d H:i:s', strtotime($value->gcms_begin));
                    $dateTo = date('Y-m-d H:i:s', strtotime($value->gcms_lasttime));
                    $sheet4->setCellValue('R'.$row, $this->timeDiffToDays($dateFrom,$dateTo) );

                    $row++; 
                }

                //----------- สร้างกราฟ gcms -------------

                $dataSeriesLabels = [
                    new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'GCMS!$S$3', null, 1), 
                ];

                $xAxisTickValues = [
                    new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'GCMS!$A$4:$A$'.($row-1), null, count($gcmsget)), 
                ];

                $dataSeriesValues = [
                    new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'GCMS!$S$4:$S$'.($row-1), null, count($gcmsget)),
                ];

                $xAxisTickValues2 = [
                    new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'GCMS!$T$4:$T$'.($row-1), null, count($gcmsget)), 
                ];
                $dataSeriesLabels2 = [
                    new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'GCMS!$T$3', null, 1), 
                ];
                $dataSeriesValues2 = [
                    new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'GCMS!$T$4:$T$'.($row-1), null, count($gcmsget)),
                ];

                // สร้างชุดข้อมูล ที่จะใช้สร้างกราฟเส้น
                $series2 = new DataSeries(
                    DataSeries::TYPE_LINECHART,  
                    null,  
                    range(0, count($dataSeriesValues2) - 1),  
                    $dataSeriesLabels2, // ชื่อกำกับชุดข้อมูล 
                    $xAxisTickValues2, // ข้อความสำหรับแกน x
                    $dataSeriesValues2  // ค่าข้อมูล
                );

                // สร้างชุดข้อมูล ที่จะใช้สร้างแผนภูมิ
                $series = new DataSeries(
                    DataSeries::TYPE_BARCHART, // ประเภทของแผนภูมิ
                    null, // การจัดกลุ่มข้อมูล 
                    range(0, count($dataSeriesValues) - 1), // ลำดับข้อมูล เรียงจาก array 0 - 2
                    $dataSeriesLabels, // ชื่อกำกับชุดข้อมูล 
                    $xAxisTickValues, // ข้อความสำหรับแกน x
                    $dataSeriesValues        // ค่าข้อมูล
                );

                $plotArea = new PlotArea(null, [$series,$series2]);
     
                $legend = new Legend(Legend::POSITION_TOP, null, false); // กำหนดตำแหน่งของคำอธิบายชุดข้อมูล
                $title = new Title('Measurement L/T of GCMS'); // หัวข้อแผนภูมิ
                $xAxisLabel = new Title('Lot number sequence'); // ข้อความอธิบายกำกับแกน X // (ถ้ามี)
                $yAxisLabel = new Title('Measurement L/T (Days)'); // ข้อความอธิบายกำกับแกน Y
                 
                // สร้างแผนภูมิ โดยใช้ค่าต่างๆ จากตัวแปรที่กำหนด 
                $chart4 = new Chart(
                    'chart1', // กำหนดชื่อ
                    $title, // กำหนดชื่อเรื่องหรือหัวข้อกำกับ
                    $legend, // ตำแหน่งคำอธิบายของชุดข้อมูล 
                    $plotArea, // พิกัดพื้นที่ชุดข้อมูล
                    true, // กำหนดให้แสดงแผนภูมิ
                    DataSeries::EMPTY_AS_GAP, // กำหนดหากเป็นค่าว่าง ให้ใช้ค่านี้แทน ในที่นี้คือ 0
                    $xAxisLabel,  // ข้อความอธิบายกำกับแกน X ในที่นี้ไม่มีกำหนด
                    $yAxisLabel  // ข้อความอธิบายกำกับแกน Y
                );
                
                if(count($gcmsget) > 0){
                    // กำหนดพิกัดมุมบนซ้าย และมุมล่างขวา เป็นพื้นที่สำหรับแสดงแผนภูมิ
                    $chart4->setTopLeftPosition('V10');
                    $chart4->setBottomRightPosition('AL33');
                    $sheet4->addChart($chart4);
                }

            //---------- ENG GCMS ---------------

            //---------- BEGIN GHOST ---------------

                $ghostget = DB::table('nt_oqa_file')
                ->leftJoin('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_oqa_file.id_createlab')
                ->whereIn('nt_oqa_lab.id_createlab', $arrid_createlab)
                ->where('nt_oqa_file.semtest_begin','>=',$datestart)
                ->where('nt_oqa_file.semtest_lasttime','<=',$dateend)
                ->orderBy('nt_oqa_file.semtest_begin', 'ASC')
                ->limit(3000)
                ->get();

                $row = 4; 
                foreach ($ghostget as $key => $value) {

                    $no = $key+1;

                    $model = DB::table('nt_model')->where('id_model', $value->id_model)->first();
                    $lot = DB::table('nt_oqa_lab')->where('id_createlab', $value->id_createlab)->first();
                    $countholidays = $this->checkNumHoliday(date('Y-m-d', strtotime($value->semtest_begin)),date('Y-m-d', strtotime($value->semtest_lasttime)));

                    $sheet5->setCellValue('C'.$row, $model->name_model);
                    $sheet5->setCellValue('D'.$row, $lot->lotnumber_lab); 
                    $sheet5->setCellValue('E'.$row, date('YmdHis', strtotime($value->semtest_begin)));
                    $sheet5->setCellValue('F'.$row, date('YmdHis', strtotime($value->semtest_lasttime)));
                    $sheet5->setCellValue('T'.$row, $measure_day->ghost);
                    $sheet5->setCellValue('G'.$row, $countholidays);

                    $dateFrom = date('Y-m-d H:i:s', strtotime($value->semtest_begin));
                    $dateTo = date('Y-m-d H:i:s', strtotime($value->semtest_lasttime));
                    $sheet5->setCellValue('R'.$row, $this->timeDiffToDays($dateFrom,$dateTo) );

                    $row++; 
                }

                //----------- สร้างกราฟ GHOST -------------

                $dataSeriesLabels = [
                    new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'GHOST!$S$3', null, 1), 
                ];

                $xAxisTickValues = [
                    new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'GHOST!$A$4:$A$'.($row-1), null, count($ghostget)), 
                ];

                $dataSeriesValues = [
                    new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'GHOST!$S$4:$S$'.($row-1), null, count($ghostget)),
                ];

                $xAxisTickValues2 = [
                    new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'GHOST!$T$4:$T$'.($row-1), null, count($ghostget)), 
                ];
                $dataSeriesLabels2 = [
                    new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'GHOST!$T$3', null, 1), 
                ];
                $dataSeriesValues2 = [
                    new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'GHOST!$T$4:$T$'.($row-1), null, count($ghostget)),
                ];

                // สร้างชุดข้อมูล ที่จะใช้สร้างกราฟเส้น
                $series2 = new DataSeries(
                    DataSeries::TYPE_LINECHART,  
                    null,  
                    range(0, count($dataSeriesValues2) - 1),  
                    $dataSeriesLabels2, // ชื่อกำกับชุดข้อมูล 
                    $xAxisTickValues2, // ข้อความสำหรับแกน x
                    $dataSeriesValues2  // ค่าข้อมูล
                );

                // สร้างชุดข้อมูล ที่จะใช้สร้างแผนภูมิ
                $series = new DataSeries(
                    DataSeries::TYPE_BARCHART, // ประเภทของแผนภูมิ
                    null, // การจัดกลุ่มข้อมูล 
                    range(0, count($dataSeriesValues) - 1), // ลำดับข้อมูล เรียงจาก array 0 - 2
                    $dataSeriesLabels, // ชื่อกำกับชุดข้อมูล 
                    $xAxisTickValues, // ข้อความสำหรับแกน x
                    $dataSeriesValues        // ค่าข้อมูล
                );

                $plotArea = new PlotArea(null, [$series,$series2]);
     
                $legend = new Legend(Legend::POSITION_TOP, null, false); // กำหนดตำแหน่งของคำอธิบายชุดข้อมูล
                $title = new Title('Measurement L/T of GHOST'); // หัวข้อแผนภูมิ
                $xAxisLabel = new Title('Lot number sequence'); // ข้อความอธิบายกำกับแกน X // (ถ้ามี)
                $yAxisLabel = new Title('Measurement L/T (Days)'); // ข้อความอธิบายกำกับแกน Y
                 
                // สร้างแผนภูมิ โดยใช้ค่าต่างๆ จากตัวแปรที่กำหนด 
                $chart5 = new Chart(
                    'chart1', // กำหนดชื่อ
                    $title, // กำหนดชื่อเรื่องหรือหัวข้อกำกับ
                    $legend, // ตำแหน่งคำอธิบายของชุดข้อมูล 
                    $plotArea, // พิกัดพื้นที่ชุดข้อมูล
                    true, // กำหนดให้แสดงแผนภูมิ
                    DataSeries::EMPTY_AS_GAP, // กำหนดหากเป็นค่าว่าง ให้ใช้ค่านี้แทน ในที่นี้คือ 0
                    $xAxisLabel,  // ข้อความอธิบายกำกับแกน X ในที่นี้ไม่มีกำหนด
                    $yAxisLabel  // ข้อความอธิบายกำกับแกน Y
                );
                if(count($ghostget) > 0){    
                    // กำหนดพิกัดมุมบนซ้าย และมุมล่างขวา เป็นพื้นที่สำหรับแสดงแผนภูมิ
                    $chart5->setTopLeftPosition('V10');
                    $chart5->setBottomRightPosition('AL33');
                    $sheet5->addChart($chart5);
                }

            //---------- ENG GHOST ---------------

            //---------- BEGIN FTIR ---------------

                $ftirget = DB::table('nt_oqa_file')
                ->leftJoin('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_oqa_file.id_createlab')
                ->whereIn('nt_oqa_lab.id_createlab', $arrid_createlab)
                ->where('nt_oqa_file.ftir_begin','>=',$datestart)
                ->where('nt_oqa_file.ftir_lasttime','<=',$dateend)
                ->orderBy('nt_oqa_file.ftir_begin', 'ASC')
                ->limit(3000)
                ->get();

                $row = 4; 
                foreach ($ftirget as $key => $value) {

                    $no = $key+1;

                    $model = DB::table('nt_model')->where('id_model', $value->id_model)->first();
                    $lot = DB::table('nt_oqa_lab')->where('id_createlab', $value->id_createlab)->first();
                    $countholidays = $this->checkNumHoliday(date('Y-m-d', strtotime($value->ftir_begin)),date('Y-m-d', strtotime($value->ftir_lasttime)));

                    $sheet6->setCellValue('C'.$row, $model->name_model);
                    $sheet6->setCellValue('D'.$row, $lot->lotnumber_lab); 
                    $sheet6->setCellValue('E'.$row, date('YmdHis', strtotime($value->ftir_begin)));
                    $sheet6->setCellValue('F'.$row, date('YmdHis', strtotime($value->ftir_lasttime)));
                    $sheet6->setCellValue('T'.$row, $measure_day->ftir);
                    $sheet6->setCellValue('G'.$row, $countholidays);

                    $dateFrom = date('Y-m-d H:i:s', strtotime($value->ftir_begin));
                    $dateTo = date('Y-m-d H:i:s', strtotime($value->ftir_lasttime));
                    $sheet6->setCellValue('R'.$row, $this->timeDiffToDays($dateFrom,$dateTo) );

                    $row++; 
                }

                //----------- สร้างกราฟ FTIR -------------

                $dataSeriesLabels = [
                    new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'FTIR!$S$3', null, 1), 
                ];

                $xAxisTickValues = [
                    new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'FTIR!$A$4:$A$'.($row-1), null, count($ftirget)), 
                ];

                $dataSeriesValues = [
                    new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'FTIR!$S$4:$S$'.($row-1), null, count($ftirget)),
                ];


                $xAxisTickValues2 = [
                    new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'FTIR!$T$4:$T$'.($row-1), null, count($ftirget)), 
                ];
                $dataSeriesLabels2 = [
                    new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'FTIR!$T$3', null, 1), 
                ];
                $dataSeriesValues2 = [
                    new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'FTIR!$T$4:$T$'.($row-1), null, count($ftirget)),
                ];

                // สร้างชุดข้อมูล ที่จะใช้สร้างกราฟเส้น
                $series2 = new DataSeries(
                    DataSeries::TYPE_LINECHART,  
                    null,  
                    range(0, count($dataSeriesValues2) - 1),  
                    $dataSeriesLabels2, // ชื่อกำกับชุดข้อมูล 
                    $xAxisTickValues2, // ข้อความสำหรับแกน x
                    $dataSeriesValues2  // ค่าข้อมูล
                );

                // สร้างชุดข้อมูล ที่จะใช้สร้างแผนภูมิ
                $series = new DataSeries(
                    DataSeries::TYPE_BARCHART, // ประเภทของแผนภูมิ
                    null, // การจัดกลุ่มข้อมูล 
                    range(0, count($dataSeriesValues) - 1), // ลำดับข้อมูล เรียงจาก array 0 - 2
                    $dataSeriesLabels, // ชื่อกำกับชุดข้อมูล 
                    $xAxisTickValues, // ข้อความสำหรับแกน x
                    $dataSeriesValues        // ค่าข้อมูล
                );

                $plotArea = new PlotArea(null, [$series,$series2]);
     
                $legend = new Legend(Legend::POSITION_TOP, null, false); // กำหนดตำแหน่งของคำอธิบายชุดข้อมูล
                $title = new Title('Measurement L/T of FTIR'); // หัวข้อแผนภูมิ
                $xAxisLabel = new Title('Lot number sequence'); // ข้อความอธิบายกำกับแกน X // (ถ้ามี)
                $yAxisLabel = new Title('Measurement L/T (Days)'); // ข้อความอธิบายกำกับแกน Y
                 
                // สร้างแผนภูมิ โดยใช้ค่าต่างๆ จากตัวแปรที่กำหนด 
                $chart6 = new Chart(
                    'chart1', // กำหนดชื่อ
                    $title, // กำหนดชื่อเรื่องหรือหัวข้อกำกับ
                    $legend, // ตำแหน่งคำอธิบายของชุดข้อมูล 
                    $plotArea, // พิกัดพื้นที่ชุดข้อมูล
                    true, // กำหนดให้แสดงแผนภูมิ
                    DataSeries::EMPTY_AS_GAP, // กำหนดหากเป็นค่าว่าง ให้ใช้ค่านี้แทน ในที่นี้คือ 0
                    $xAxisLabel,  // ข้อความอธิบายกำกับแกน X ในที่นี้ไม่มีกำหนด
                    $yAxisLabel  // ข้อความอธิบายกำกับแกน Y
                );
                
                if(count($ftirget) > 0){  
                    // กำหนดพิกัดมุมบนซ้าย และมุมล่างขวา เป็นพื้นที่สำหรับแสดงแผนภูมิ
                    $chart6->setTopLeftPosition('V10');
                    $chart6->setBottomRightPosition('AL33');
                    $sheet6->addChart($chart6);
                }

            //---------- ENG FTIR ---------------

            //---------- BEGIN HOME ---------------
                //---------- BEGIN LPC ---------------

                    $sheet7 = $spreadsheet->setActiveSheetIndexByName('Conclude');
                    // $sheet7->setCellValue('AM27', $measure_day->lpc); //LPC
                    // $sheet7->setCellValue('AM30', $measure_day->sem); //SEM
                    // $sheet7->setCellValue('AM33', $measure_day->ic); //IC
                    // $sheet7->setCellValue('AM36', $measure_day->gcms); //GCMS
                    // $sheet7->setCellValue('AM39', $measure_day->ftir); //FTIR
                    // $sheet7->setCellValue('AM42', $measure_day->ghost); //GHOST
                      
                    //----------- สร้างกราฟ LPC -------------

                    $dataSeriesLabels = [
                        new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'LPC!$S$3', null, 1), 
                    ];
                    $xAxisTickValues = [
                        new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'LPC!$A$4:$A$'.(count($lpcget)+3), null, count($lpcget)), 
                    ];
                    $dataSeriesValues = [
                        new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'LPC!$S$4:$S$'.(count($lpcget)+3), null, count($lpcget)),
                    ];
                    

                    $xAxisTickValues2 = [
                        new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'LPC!$T$4:$T$'.(count($lpcget)+3), null, count($lpcget)), 
                    ];
                    $dataSeriesLabels2 = [
                        new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'LPC!$T$3', null, 1), 
                    ];
                    $dataSeriesValues2 = [
                        new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'LPC!$T$4:$T$'.(count($lpcget)+3), null, count($lpcget)),
                    ];

                    // สร้างชุดข้อมูล ที่จะใช้สร้างกราฟเส้น
                    $series2 = new DataSeries(
                        DataSeries::TYPE_LINECHART,  
                        null,  
                        range(0, count($dataSeriesValues2) - 1),  
                        $dataSeriesLabels2, // ชื่อกำกับชุดข้อมูล 
                        $xAxisTickValues2, // ข้อความสำหรับแกน x
                        $dataSeriesValues2  // ค่าข้อมูล
                    );

                    // สร้างชุดข้อมูล ที่จะใช้สร้างแผนภูมิ
                    $series = new DataSeries(
                        DataSeries::TYPE_BARCHART, // ประเภทของแผนภูมิ
                        null, // การจัดกลุ่มข้อมูล 
                        range(0, count($dataSeriesValues) - 1), // ลำดับข้อมูล เรียงจาก array 0 - 2
                        $dataSeriesLabels, // ชื่อกำกับชุดข้อมูล 
                        $xAxisTickValues, // ข้อความสำหรับแกน x
                        $dataSeriesValues        // ค่าข้อมูล
                    );

                    $plotArea = new PlotArea(null, [$series,$series2]);
         
                    $legend = new Legend(Legend::POSITION_TOP, null, false); // กำหนดตำแหน่งของคำอธิบายชุดข้อมูล
                    $title = new Title('Measurement L/T of LPC'); // หัวข้อแผนภูมิ
                    $xAxisLabel = new Title('Lot number sequence'); // ข้อความอธิบายกำกับแกน X // (ถ้ามี)
                    $yAxisLabel = new Title('Measurement L/T (Days)'); // ข้อความอธิบายกำกับแกน Y
                     
                    // สร้างแผนภูมิ โดยใช้ค่าต่างๆ จากตัวแปรที่กำหนด 
                    $chartH1 = new Chart(
                        'chart1', // กำหนดชื่อ
                        $title, // กำหนดชื่อเรื่องหรือหัวข้อกำกับ
                        $legend, // ตำแหน่งคำอธิบายของชุดข้อมูล 
                        $plotArea, // พิกัดพื้นที่ชุดข้อมูล
                        true, // กำหนดให้แสดงแผนภูมิ
                        DataSeries::EMPTY_AS_GAP, // กำหนดหากเป็นค่าว่าง ให้ใช้ค่านี้แทน ในที่นี้คือ 0
                        $xAxisLabel,  // ข้อความอธิบายกำกับแกน X ในที่นี้ไม่มีกำหนด
                        $yAxisLabel  // ข้อความอธิบายกำกับแกน Y
                    );
                     
                    // กำหนดพิกัดมุมบนซ้าย และมุมล่างขวา เป็นพื้นที่สำหรับแสดงแผนภูมิ
                    if(count($lpcget) > 0){  
                        $chartH1->setTopLeftPosition('A11');
                        $chartH1->setBottomRightPosition('G35');
                        $sheet7->addChart($chartH1);
                    }

                //---------- ENG LPC ---------------

                //---------- BEGIN SEM ---------------

                    //----------- สร้างกราฟ SEM -------------

                    $dataSeriesLabels = [
                        new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'SEM!$S$3', null, 1), 
                    ];
                    $xAxisTickValues = [
                        new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'SEM!$A$4:$A$'.(count($semget)+3), null, count($semget)), 
                    ];
                    $dataSeriesValues = [
                        new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'SEM!$S$4:$S$'.(count($semget)+3), null, count($semget)),
                    ];


                    $xAxisTickValues2 = [
                        new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'SEM!$T$4:$T$'.(count($semget)+3), null, count($semget)), 
                    ];
                    $dataSeriesLabels2 = [
                        new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'SEM!$T$3', null, 1), 
                    ];
                    $dataSeriesValues2 = [
                        new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'SEM!$T$4:$T$'.(count($semget)+3), null, count($semget)),
                    ];

                    // สร้างชุดข้อมูล ที่จะใช้สร้างกราฟเส้น
                    $series2 = new DataSeries(
                        DataSeries::TYPE_LINECHART,  
                        null,  
                        range(0, count($dataSeriesValues2) - 1),  
                        $dataSeriesLabels2, // ชื่อกำกับชุดข้อมูล 
                        $xAxisTickValues2, // ข้อความสำหรับแกน x
                        $dataSeriesValues2  // ค่าข้อมูล
                    );

                    // สร้างชุดข้อมูล ที่จะใช้สร้างแผนภูมิ
                    $series = new DataSeries(
                        DataSeries::TYPE_BARCHART, // ประเภทของแผนภูมิ
                        null, // การจัดกลุ่มข้อมูล 
                        range(0, count($dataSeriesValues) - 1), // ลำดับข้อมูล เรียงจาก array 0 - 2
                        $dataSeriesLabels, // ชื่อกำกับชุดข้อมูล 
                        $xAxisTickValues, // ข้อความสำหรับแกน x
                        $dataSeriesValues        // ค่าข้อมูล
                    );

                    $plotArea = new PlotArea(null, [$series,$series2]);
         
                    $legend = new Legend(Legend::POSITION_TOP, null, false); // กำหนดตำแหน่งของคำอธิบายชุดข้อมูล
                    $title = new Title('Measurement L/T of SEM'); // หัวข้อแผนภูมิ
                    $xAxisLabel = new Title('Lot number sequence'); // ข้อความอธิบายกำกับแกน X // (ถ้ามี)
                    $yAxisLabel = new Title('Measurement L/T (Days)'); // ข้อความอธิบายกำกับแกน Y
                     
                    // สร้างแผนภูมิ โดยใช้ค่าต่างๆ จากตัวแปรที่กำหนด 
                    $chartH2 = new Chart(
                        'chart1', // กำหนดชื่อ
                        $title, // กำหนดชื่อเรื่องหรือหัวข้อกำกับ
                        $legend, // ตำแหน่งคำอธิบายของชุดข้อมูล 
                        $plotArea, // พิกัดพื้นที่ชุดข้อมูล
                        true, // กำหนดให้แสดงแผนภูมิ
                        DataSeries::EMPTY_AS_GAP, // กำหนดหากเป็นค่าว่าง ให้ใช้ค่านี้แทน ในที่นี้คือ 0
                        $xAxisLabel,  // ข้อความอธิบายกำกับแกน X ในที่นี้ไม่มีกำหนด
                        $yAxisLabel  // ข้อความอธิบายกำกับแกน Y
                    );
                    
                    if(count($semget) > 0){  
                        // กำหนดพิกัดมุมบนซ้าย และมุมล่างขวา เป็นพื้นที่สำหรับแสดงแผนภูมิ
                        $chartH2->setTopLeftPosition('H11');
                        $chartH2->setBottomRightPosition('S35');
                        $sheet7->addChart($chartH2);
                    }

                //---------- ENG SEM ---------------

                //---------- BEGIN ic ---------------

                    //----------- สร้างกราฟ IC -------------

                    $dataSeriesLabels = [
                        new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'IC!$S$3', null, 1), 
                    ];
                    $xAxisTickValues = [
                        new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'IC!$A$4:$A$'.(count($icget)+3), null, count($icget)), 
                    ];
                    $dataSeriesValues = [
                        new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'IC!$S$4:$S$'.(count($icget)+3), null, count($icget)),
                    ];


                    $xAxisTickValues2 = [
                        new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'IC!$T$4:$T$'.(count($icget)+3), null, count($icget)), 
                    ];
                    $dataSeriesLabels2 = [
                        new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'IC!$T$3', null, 1), 
                    ];
                    $dataSeriesValues2 = [
                        new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'IC!$T$4:$T$'.(count($icget)+3), null, count($icget)),
                    ];

                    // สร้างชุดข้อมูล ที่จะใช้สร้างกราฟเส้น
                    $series2 = new DataSeries(
                        DataSeries::TYPE_LINECHART,  
                        null,  
                        range(0, count($dataSeriesValues2) - 1),  
                        $dataSeriesLabels2, // ชื่อกำกับชุดข้อมูล 
                        $xAxisTickValues2, // ข้อความสำหรับแกน x
                        $dataSeriesValues2  // ค่าข้อมูล
                    );

                    // สร้างชุดข้อมูล ที่จะใช้สร้างแผนภูมิ
                    $series = new DataSeries(
                        DataSeries::TYPE_BARCHART, // ประเภทของแผนภูมิ
                        null, // การจัดกลุ่มข้อมูล 
                        range(0, count($dataSeriesValues) - 1), // ลำดับข้อมูล เรียงจาก array 0 - 2
                        $dataSeriesLabels, // ชื่อกำกับชุดข้อมูล 
                        $xAxisTickValues, // ข้อความสำหรับแกน x
                        $dataSeriesValues        // ค่าข้อมูล
                    );

                    $plotArea = new PlotArea(null, [$series,$series2]);
         
                    $legend = new Legend(Legend::POSITION_TOP, null, false); // กำหนดตำแหน่งของคำอธิบายชุดข้อมูล
                    $title = new Title('Measurement L/T of IC'); // หัวข้อแผนภูมิ
                    $xAxisLabel = new Title('Lot number sequence'); // ข้อความอธิบายกำกับแกน X // (ถ้ามี)
                    $yAxisLabel = new Title('Measurement L/T (Days)'); // ข้อความอธิบายกำกับแกน Y
                     
                    // สร้างแผนภูมิ โดยใช้ค่าต่างๆ จากตัวแปรที่กำหนด 
                    $chartH3 = new Chart(
                        'chart1', // กำหนดชื่อ
                        $title, // กำหนดชื่อเรื่องหรือหัวข้อกำกับ
                        $legend, // ตำแหน่งคำอธิบายของชุดข้อมูล 
                        $plotArea, // พิกัดพื้นที่ชุดข้อมูล
                        true, // กำหนดให้แสดงแผนภูมิ
                        DataSeries::EMPTY_AS_GAP, // กำหนดหากเป็นค่าว่าง ให้ใช้ค่านี้แทน ในที่นี้คือ 0
                        $xAxisLabel,  // ข้อความอธิบายกำกับแกน X ในที่นี้ไม่มีกำหนด
                        $yAxisLabel  // ข้อความอธิบายกำกับแกน Y
                    );
                    
                    if(count($icget) > 0){  
                        // กำหนดพิกัดมุมบนซ้าย และมุมล่างขวา เป็นพื้นที่สำหรับแสดงแผนภูมิ
                        $chartH3->setTopLeftPosition('A37');
                        $chartH3->setBottomRightPosition('G61');
                        $sheet7->addChart($chartH3);
                    }

                //---------- ENG ic ---------------                

                //---------- BEGIN gcms ---------------
     
                    //----------- สร้างกราฟ gcms -------------

                    $dataSeriesLabels = [
                        new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'GCMS!$S$3', null, 1), 
                    ];
                    $xAxisTickValues = [
                        new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'GCMS!$A$4:$A$'.(count($gcmsget)+3), null, count($gcmsget)), 
                    ];
                    $dataSeriesValues = [
                        new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'GCMS!$S$4:$S$'.(count($gcmsget)+3), null, count($gcmsget)),
                    ];


                    $xAxisTickValues2 = [
                        new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'GCMS!$T$4:$T$'.(count($gcmsget)+3), null, count($gcmsget)), 
                    ];
                    $dataSeriesLabels2 = [
                        new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'GCMS!$T$3', null, 1), 
                    ];
                    $dataSeriesValues2 = [
                        new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'GCMS!$T$4:$T$'.(count($gcmsget)+3), null, count($gcmsget)),
                    ];

                    // สร้างชุดข้อมูล ที่จะใช้สร้างกราฟเส้น
                    $series2 = new DataSeries(
                        DataSeries::TYPE_LINECHART,  
                        null,  
                        range(0, count($dataSeriesValues2) - 1),  
                        $dataSeriesLabels2, // ชื่อกำกับชุดข้อมูล 
                        $xAxisTickValues2, // ข้อความสำหรับแกน x
                        $dataSeriesValues2  // ค่าข้อมูล
                    );

                    // สร้างชุดข้อมูล ที่จะใช้สร้างแผนภูมิ
                    $series = new DataSeries(
                        DataSeries::TYPE_BARCHART, // ประเภทของแผนภูมิ
                        null, // การจัดกลุ่มข้อมูล 
                        range(0, count($dataSeriesValues) - 1), // ลำดับข้อมูล เรียงจาก array 0 - 2
                        $dataSeriesLabels, // ชื่อกำกับชุดข้อมูล 
                        $xAxisTickValues, // ข้อความสำหรับแกน x
                        $dataSeriesValues        // ค่าข้อมูล
                    );

                    $plotArea = new PlotArea(null, [$series,$series2]);
         
                    $legend = new Legend(Legend::POSITION_TOP, null, false); // กำหนดตำแหน่งของคำอธิบายชุดข้อมูล
                    $title = new Title('Measurement L/T of GCMS'); // หัวข้อแผนภูมิ
                    $xAxisLabel = new Title('Lot number sequence'); // ข้อความอธิบายกำกับแกน X // (ถ้ามี)
                    $yAxisLabel = new Title('Measurement L/T (Days)'); // ข้อความอธิบายกำกับแกน Y
                     
                    // สร้างแผนภูมิ โดยใช้ค่าต่างๆ จากตัวแปรที่กำหนด 
                    $chartH4 = new Chart(
                        'chart1', // กำหนดชื่อ
                        $title, // กำหนดชื่อเรื่องหรือหัวข้อกำกับ
                        $legend, // ตำแหน่งคำอธิบายของชุดข้อมูล 
                        $plotArea, // พิกัดพื้นที่ชุดข้อมูล
                        true, // กำหนดให้แสดงแผนภูมิ
                        DataSeries::EMPTY_AS_GAP, // กำหนดหากเป็นค่าว่าง ให้ใช้ค่านี้แทน ในที่นี้คือ 0
                        $xAxisLabel,  // ข้อความอธิบายกำกับแกน X ในที่นี้ไม่มีกำหนด
                        $yAxisLabel  // ข้อความอธิบายกำกับแกน Y
                    );

                    if(count($gcmsget) > 0){  
                        // กำหนดพิกัดมุมบนซ้าย และมุมล่างขวา เป็นพื้นที่สำหรับแสดงแผนภูมิ
                        $chartH4->setTopLeftPosition('H37');
                        $chartH4->setBottomRightPosition('S61');
                        $sheet7->addChart($chartH4);
                    }

                //---------- ENG gcms ---------------  

                //---------- BEGIN ghost ---------------
     
                    //----------- สร้างกราฟ GHOST -------------

                    $dataSeriesLabels = [
                        new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'GHOST!$S$3', null, 1), 
                    ];
                    $xAxisTickValues = [
                        new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'GHOST!$A$4:$A$'.(count($ghostget)+3), null, count($ghostget)), 
                    ];
                    $dataSeriesValues = [
                        new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'GHOST!$S$4:$S$'.(count($ghostget)+3), null, count($ghostget)),
                    ];


                    $xAxisTickValues2 = [
                        new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'GHOST!$T$4:$T$'.(count($ghostget)+3), null, count($ghostget)), 
                    ];
                    $dataSeriesLabels2 = [
                        new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'GHOST!$T$3', null, 1), 
                    ];
                    $dataSeriesValues2 = [
                        new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'GHOST!$T$4:$T$'.(count($ghostget)+3), null, count($ghostget)),
                    ];

                    // สร้างชุดข้อมูล ที่จะใช้สร้างกราฟเส้น
                    $series2 = new DataSeries(
                        DataSeries::TYPE_LINECHART,  
                        null,  
                        range(0, count($dataSeriesValues2) - 1),  
                        $dataSeriesLabels2, // ชื่อกำกับชุดข้อมูล 
                        $xAxisTickValues2, // ข้อความสำหรับแกน x
                        $dataSeriesValues2  // ค่าข้อมูล
                    );

                    // สร้างชุดข้อมูล ที่จะใช้สร้างแผนภูมิ
                    $series = new DataSeries(
                        DataSeries::TYPE_BARCHART, // ประเภทของแผนภูมิ
                        null, // การจัดกลุ่มข้อมูล 
                        range(0, count($dataSeriesValues) - 1), // ลำดับข้อมูล เรียงจาก array 0 - 2
                        $dataSeriesLabels, // ชื่อกำกับชุดข้อมูล 
                        $xAxisTickValues, // ข้อความสำหรับแกน x
                        $dataSeriesValues        // ค่าข้อมูล
                    );

                    $plotArea = new PlotArea(null, [$series,$series2]);
         
                    $legend = new Legend(Legend::POSITION_TOP, null, false); // กำหนดตำแหน่งของคำอธิบายชุดข้อมูล
                    $title = new Title('Measurement L/T of GHOST'); // หัวข้อแผนภูมิ
                    $xAxisLabel = new Title('Lot number sequence'); // ข้อความอธิบายกำกับแกน X // (ถ้ามี)
                    $yAxisLabel = new Title('Measurement L/T (Days)'); // ข้อความอธิบายกำกับแกน Y
                     
                    // สร้างแผนภูมิ โดยใช้ค่าต่างๆ จากตัวแปรที่กำหนด 
                    $chartH5 = new Chart(
                        'chart1', // กำหนดชื่อ
                        $title, // กำหนดชื่อเรื่องหรือหัวข้อกำกับ
                        $legend, // ตำแหน่งคำอธิบายของชุดข้อมูล 
                        $plotArea, // พิกัดพื้นที่ชุดข้อมูล
                        true, // กำหนดให้แสดงแผนภูมิ
                        DataSeries::EMPTY_AS_GAP, // กำหนดหากเป็นค่าว่าง ให้ใช้ค่านี้แทน ในที่นี้คือ 0
                        $xAxisLabel,  // ข้อความอธิบายกำกับแกน X ในที่นี้ไม่มีกำหนด
                        $yAxisLabel  // ข้อความอธิบายกำกับแกน Y
                    );
                    
                    if(count($ghostget) > 0){  
                        // กำหนดพิกัดมุมบนซ้าย และมุมล่างขวา เป็นพื้นที่สำหรับแสดงแผนภูมิ
                        $chartH5->setTopLeftPosition('T11');
                        $chartH5->setBottomRightPosition('AJ35');
                        $sheet7->addChart($chartH5);
                    }

                //---------- ENG ghost ---------------  

                //---------- BEGIN FTIR ---------------
     
                    //----------- สร้างกราฟ FTIR -------------

                    $dataSeriesLabels = [
                        new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'FTIR!$S$3', null, 1), 
                    ];
                    $xAxisTickValues = [
                        new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'FTIR!$A$4:$A$'.(count($ftirget)+3), null, count($ftirget)), 
                    ];
                    $dataSeriesValues = [
                        new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'FTIR!$S$4:$S$'.(count($ftirget)+3), null, count($ftirget)),
                    ];


                    $xAxisTickValues2 = [
                        new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'FTIR!$T$4:$T$'.(count($ftirget)+3), null, count($ftirget)), 
                    ];
                    $dataSeriesLabels2 = [
                        new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'FTIR!$T$3', null, 1), 
                    ];
                    $dataSeriesValues2 = [
                        new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'FTIR!$T$4:$T$'.(count($ftirget)+3), null, count($ftirget)),
                    ];

                    // สร้างชุดข้อมูล ที่จะใช้สร้างกราฟเส้น
                    $series2 = new DataSeries(
                        DataSeries::TYPE_LINECHART,  
                        null,  
                        range(0, count($dataSeriesValues2) - 1),  
                        $dataSeriesLabels2, // ชื่อกำกับชุดข้อมูล 
                        $xAxisTickValues2, // ข้อความสำหรับแกน x
                        $dataSeriesValues2  // ค่าข้อมูล
                    );

                    // สร้างชุดข้อมูล ที่จะใช้สร้างแผนภูมิ
                    $series = new DataSeries(
                        DataSeries::TYPE_BARCHART, // ประเภทของแผนภูมิ
                        null, // การจัดกลุ่มข้อมูล 
                        range(0, count($dataSeriesValues) - 1), // ลำดับข้อมูล เรียงจาก array 0 - 2
                        $dataSeriesLabels, // ชื่อกำกับชุดข้อมูล 
                        $xAxisTickValues, // ข้อความสำหรับแกน x
                        $dataSeriesValues        // ค่าข้อมูล
                    );

                    $plotArea = new PlotArea(null, [$series,$series2]);
         
                    $legend = new Legend(Legend::POSITION_TOP, null, false); // กำหนดตำแหน่งของคำอธิบายชุดข้อมูล
                    $title = new Title('Measurement L/T of FTIR'); // หัวข้อแผนภูมิ
                    $xAxisLabel = new Title('Lot number sequence'); // ข้อความอธิบายกำกับแกน X // (ถ้ามี)
                    $yAxisLabel = new Title('Measurement L/T (Days)'); // ข้อความอธิบายกำกับแกน Y
                     
                    // สร้างแผนภูมิ โดยใช้ค่าต่างๆ จากตัวแปรที่กำหนด 
                    $chartH6 = new Chart(
                        'chart1', // กำหนดชื่อ
                        $title, // กำหนดชื่อเรื่องหรือหัวข้อกำกับ
                        $legend, // ตำแหน่งคำอธิบายของชุดข้อมูล 
                        $plotArea, // พิกัดพื้นที่ชุดข้อมูล
                        true, // กำหนดให้แสดงแผนภูมิ
                        DataSeries::EMPTY_AS_GAP, // กำหนดหากเป็นค่าว่าง ให้ใช้ค่านี้แทน ในที่นี้คือ 0
                        $xAxisLabel,  // ข้อความอธิบายกำกับแกน X ในที่นี้ไม่มีกำหนด
                        $yAxisLabel  // ข้อความอธิบายกำกับแกน Y
                    );
                    
                    if(count($ftirget) > 0){  
                        // กำหนดพิกัดมุมบนซ้าย และมุมล่างขวา เป็นพื้นที่สำหรับแสดงแผนภูมิ
                        $chartH6->setTopLeftPosition('T37');
                        $chartH6->setBottomRightPosition('AJ61');
                        $sheet7->addChart($chartH6);
                    }

                //---------- ENG FTIR ---------------  
            //---------- ENG HOME ---------------

            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $writer->setIncludeCharts(true); // กำหนดให้มี แผนภูมิในไฟล์ที่สร้าง
            $writer->save(storage_path('NHK-File/report/Measurement/'.$namefile.'.xlsx'));

            return response()->json(['success' =>  1,'filename'=>$namefile.'.xlsx']);

    }

    //-------------- end Measurement L/T -------------- 


    //-------------- begin Daily check -------------- 

    public function dailycheck() 
    {
        $data = DB::table('nt_dailycheck_set')->get();
        $nowdate = date('Y-m-d');

        foreach ($data as $key => $value) {

            $due_date1 = date('Y-m-d');
            $due_date2 = date('H:i:s', strtotime($value->due_date));
            $due_date = date('Y-m-d H:i:s', strtotime($due_date1.' '.$due_date2));

            if(DB::table('nt_dailycheck_info')->where('created_at', $nowdate)->where('id_daily', $value->id_daily)->count() == 0){
                $parametor = [
                    'id_daily' => $value->id_daily,
                    'due_date' => $due_date,
                    'created_at' => date('Y-m-d'),
                ];  
                DB::table('nt_dailycheck_info')->insert($parametor);
            }
        }

        $allJobday = DB::table('nt_dailycheck_info')->count();
        $doneJobs = DB::table('nt_dailycheck_info')->whereNotNull('recoredtime')->count();
        $ongoingJobs = DB::table('nt_dailycheck_info')->whereNull('recoredtime')->count();

        $progress = [];

        $unit = DB::table('nt_dailycheck_unit')->get();
        foreach ($unit as $key => $_unit) {
            $set = DB::table('nt_dailycheck_set')->where('id_dyunit', $_unit->id_dyunit)->first();
            if(!empty($set)){
                $allinfo = DB::table('nt_dailycheck_info')->where('id_daily', $set->id_daily)->count();
                $done = DB::table('nt_dailycheck_info')->where('id_daily', $set->id_daily)->whereNotNull('recoredtime')->count();

                $percent = ($done/$allinfo)*100;

                if($percent > 0){
                    $progress[] = [
                        'name' => $_unit->name_tester,
                        'percent' => intval($percent),
                    ];
                }
            }
        }

        $data = [
            'allJobday' => $allJobday,
            'doneJobs' => $doneJobs,
            'ongoingJobs' => $ongoingJobs,
            'progress' => $progress,
        ];

        return view('output/dailycheck',$data);

    }

    public function dailychecktable(Request $request) 
    {

        if(empty($request->date_start) && empty($request->date_end))
        {
            $data = DB::table('nt_dailycheck_info')
            ->where('nt_dailycheck_info.due_date', 'like', date('Y-m-d').'%')
            ->orderby('nt_dailycheck_info.due_date','DESC')
            ->get();

        }else {

            $datestart = date('Y-m-d 00:00:00', strtotime($request->date_start));
            $dateend = date('Y-m-d 23:59:00', strtotime($request->date_end));

            $data = DB::table('nt_dailycheck_info')
            ->where('nt_dailycheck_info.due_date','>=',$datestart)
            ->where('nt_dailycheck_info.due_date','<=',$dateend)
            ->orderby('nt_dailycheck_info.due_date','DESC')
            ->get();

        }    
        // return Datatables::of($data)->make(true);

        $sQuery = Datatables::of($data)
        ->addIndexColumn()
        ->addColumn('name_tester',function($data){ 
            $get = DB::table('nt_dailycheck_set')->join('nt_dailycheck_unit', 'nt_dailycheck_set.id_dyunit', '=', 'nt_dailycheck_unit.id_dyunit')
            ->where('nt_dailycheck_set.id_daily',$data->id_daily)->first();
            return  $get->name_tester;
        })
        ->addColumn('recoredtime',function($data){ 
            return  $data->recoredtime;
        })
        ->addColumn('operation',function($data){ 
            return  $data->operation;
        })
        ->addColumn('due_date',function($data){
            return  $data->due_date;
        });
        return $sQuery->escapeColumns([])->make(true);

    }

    public function dailycheckedit($id)  
    {

        $info = DB::table('nt_dailycheck_info')->where('id_dailyinfo', $id)->first();
        $set = DB::table('nt_dailycheck_set')->where('id_daily',$info->id_daily)->first();
        $unit = DB::table('nt_dailycheck_unit')->where('id_dyunit',$set->id_dyunit)->first();

        $data = [
            'unit' => $unit,
            'info' => $info,
            'set' => $set
        ];

        return view('output/dailycheckedit', $data);
    }

    public function dailycheckview($id)  
    {

        $info = DB::table('nt_dailycheck_info')->where('id_dailyinfo', $id)->first();
        $set = DB::table('nt_dailycheck_set')->where('id_daily',$info->id_daily)->first();
        $unit = DB::table('nt_dailycheck_unit')->where('id_dyunit',$set->id_dyunit)->first();

        $data = [
            'unit' => $unit,
            'info' => $info,
            'set' => $set
        ];

        return view('output/dailycheckview', $data);
    }

    public function updateDailycheck(Request $request)  
    {
        
        $get = DB::table('nt_dailycheck_info')->where('id_dailyinfo', $request->id_dailyinfo)->first();
        $count = $get->countqccheck;
        if(Auth::user()->status == 2){
            $count++;
        }

        $day = [
            'recoredtime' => date('Y-m-d H:i:s'),
            'results' => $request->txt_result,
            'operation' => $request->txt_operation,

            'problem' => $request->txt_problem,
            'rootcause' => $request->txt_rootcause,
            'action' => $request->txt_action,

            'problem_result' => $request->txt_problem_result,
            'rootcause_result' => $request->txt_rootcause_result,
            'action_result' => $request->txt_action_result,

            'countqccheck' => $count,

        ];

        DB::table('nt_dailycheck_info')->where('id_dailyinfo', $request->id_dailyinfo)->update($day);
        return response()->json(['success' =>  1]);

    }

    public function resetDailycheck(Request $request) 
    {
        
        if(count($request->id) > 0){

            foreach ($request->id as $key => $value) {
                if(DB::table('nt_dailycheck_info')->where('id_dailyinfo', $value)->count() == 1){
                    $getdata = DB::table('nt_dailycheck_info')->where('id_dailyinfo', $value)->first();
                    $id_daily = $getdata->id_daily;
                    DB::table('nt_dailycheck_info')->where('id_dailyinfo', $value)->delete();
                    DB::table('nt_dailycheck_info')->insert([ 'id_daily' => $id_daily ]);
                }
            }

            return response()->json(['success' =>  1]);

        }else{
            return response()->json(['success' =>  2]);
        }
    }


    public function reportdailycheck() 
    {
            return view('output/reportdailycheck');
    }

    public function dailydatatables(Request $request) 
    {

            if(!empty($request->date_start) && !empty($request->date_end))
            {
                $datestart = date('Y-m-d 00:00:00', strtotime($request->date_start));
                $dateend = date('Y-m-d 23:59:00', strtotime($request->date_end));
                $start = "'$datestart'";
                $end = "'$dateend'";

                $dataunit = DB::table('nt_dailycheck_unit')
                ->select('nt_dailycheck_unit.*',DB::raw(''.$start .' as startdate'),DB::raw(''.$end .' as enddate'))
                ->orderby('id_dyunit','ASC')
                ->get();

                $sQuery = Datatables::of($dataunit)
                ->addIndexColumn()
                ->addColumn('result',function($data){

                    $info = DB::table('nt_dailycheck_info')
                    ->join('nt_dailycheck_set', 'nt_dailycheck_set.id_daily', '=', 'nt_dailycheck_info.id_daily')
                    ->where('nt_dailycheck_set.due_date','>=',$data->startdate)
                    ->where('nt_dailycheck_set.due_date','<=',$data->enddate)
                    ->where('nt_dailycheck_set.id_dyunit', $data->id_dyunit)
                    ->get();

                    $notpass = 0;
                    foreach ($info as $value) {
                        if($value->results == "NOT_PASS"){
                            $notpass++;
                        }
                    }
                    
                    if(count($info)>0){
                        return ($notpass>0)? 'NOT_PASS' : 'PASS';
                    }else{
                        return null;
                    }

                })
                ->addColumn('date_start',function($data){
                    return  date('Y-m-d',strtotime($data->startdate));
                })
                ->addColumn('date_end',function($data){
                    return  date('Y-m-d',strtotime($data->enddate));
                });

            }else{  


                $dataunit = DB::table('nt_dailycheck_unit')->orderby('id_dyunit','ASC')->get();
                $sQuery = Datatables::of($dataunit)
                ->addIndexColumn()
                ->addColumn('result',function($data){
                    return null;
                })
                ->addColumn('date_start',function($data){
                    return null;
                })
                ->addColumn('date_end',function($data){
                    return null;
                });

            }

            return $sQuery->escapeColumns([])->make(true);

    }

    public function excel_dayilycheckall(Request $request) 
    {

            //------ style-------
                $styleRight = [
                    'alignment' => [ 
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ];

                $styleLeft = [
                    'alignment' => [ 
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ];

                $styleMiddle = [
                    'alignment' => [
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ];

                $FULLBORDER = array(
                    'borders' => array(
                        'inside' => array(
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ),
                        'outline' => array(
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ),

                    ),
                ); 

                // จัดรูปแบบส่วนของข้อมูล
                $styleArray_Data = [
                    'borders' => [ // กำหนดเส้นขอบ
                        'vertical' => [ // เส้นขอบแนวตั้งด้านใน
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                        'outline' => [ // เส้นขอบด้านนอก
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ],
                ];

            //------ style-------

            $namefile = 'dailycheck_all_'.date('YmdHis');

            if(!empty($request->date_start) && !empty($request->date_end))
            {
                $datestart = date('Y-m-d 00:00:00', strtotime($request->date_start));
                $dateend = date('Y-m-d 23:59:00', strtotime($request->date_end."+1 day"));

                $dataunit = DB::table('nt_dailycheck_unit')
                ->join('nt_dailycheck_set', 'nt_dailycheck_set.id_dyunit', '=', 'nt_dailycheck_unit.id_dyunit')
                ->where('nt_dailycheck_set.due_date','>=',$datestart)
                ->where('nt_dailycheck_set.due_date','<=',$dateend)
                ->get();

            }else{

                $dataunit = DB::table('nt_dailycheck_unit')
                ->join('nt_dailycheck_set', 'nt_dailycheck_set.id_dyunit', '=', 'nt_dailycheck_unit.id_dyunit')
                ->get();

            }

            // สร้าง Workbook ใหม่
            $spreadsheet = new Spreadsheet();

           foreach ($dataunit as $key => $value) {
               
                $sheet = $spreadsheet->createSheet();

                // ตั้งชื่อ Worksheet
                $sheet->setTitle($value->name_tester);

                $sheet->setCellValue('A1', 'Date');
                $sheet->setCellValue('B1', 'Due date');
                $sheet->setCellValue('C1', 'Record time');
                $sheet->setCellValue('D1', 'Operator');
                $sheet->setCellValue('E1', 'Status');
                $sheet->setCellValue('F1', 'Result');
                $sheet->setCellValue('G1', 'Problem');
                $sheet->setCellValue('H1', 'Root cause');
                $sheet->setCellValue('I1', 'Action');

                $info = DB::table('nt_dailycheck_info')
                ->where('id_daily', $value->id_daily)
                ->get();

                $row = 1;
                foreach ($info as $item) {

                    $row++;

                   
                    if($item->recoredtime != null){
                        $sheet->setCellValue('E'.$row, 'DONE');
                    }

                    $sheet->setCellValue('A'.$row, date('Y-m-d',strtotime($value->due_date)));
                    $sheet->setCellValue('B'.$row, date('Y-m-d H:i:s',strtotime($value->due_date)));
                    $sheet->setCellValue('C'.$row, date('Y-m-d H:i:s',strtotime($item->recoredtime)));
                    $sheet->setCellValue('D'.$row, $item->operation);
                    
                    $sheet->setCellValue('F'.$row, $item->results);
                    $sheet->setCellValue('G'.$row, $item->problem);
                    $sheet->setCellValue('H'.$row, $item->rootcause);
                    $sheet->setCellValue('I'.$row, $item->action);
                }

                $sheet->getStyle('A1:I'.$row)->applyFromArray($styleMiddle);
                $sheet->getStyle('A1:I'.$row)->applyFromArray($FULLBORDER);

                $sheet->getColumnDimension('A')->setWidth(17);
                $sheet->getColumnDimension('B')->setWidth(23);
                $sheet->getColumnDimension('C')->setWidth(23);
                $sheet->getColumnDimension('D')->setWidth(20);
                $sheet->getColumnDimension('E')->setWidth(20);
                $sheet->getColumnDimension('F')->setWidth(20);
                $sheet->getColumnDimension('G')->setWidth(20);
                $sheet->getColumnDimension('H')->setWidth(20);
                $sheet->getColumnDimension('I')->setWidth(20);


           }

           $spreadsheet->removeSheetByIndex(0);

           $writer = new Xlsx($spreadsheet);
           $writer->save(storage_path('NHK-File/report/dailycheck/'.$namefile.'.xlsx'));

            return response()->json(['success' =>  1,'filename' => $namefile.'.xlsx']);

    }

    public function excel_dayilycheck(Request $request)
    {

            //------ style-------
                $styleRight = [
                    'alignment' => [ 
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ];

                $styleLeft = [
                    'alignment' => [ 
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ];

                $styleMiddle = [
                    'alignment' => [
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ];

                $FULLBORDER = array(
                    'borders' => array(
                        'inside' => array(
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ),
                        'outline' => array(
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ),

                    ),
                ); 

                // จัดรูปแบบส่วนของข้อมูล
                $styleArray_Data = [
                    'borders' => [ // กำหนดเส้นขอบ
                        'vertical' => [ // เส้นขอบแนวตั้งด้านใน
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                        'outline' => [ // เส้นขอบด้านนอก
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ],
                ];

            //------ style-------


            $unit = DB::table('nt_dailycheck_unit')->where('id_dyunit',$request->tester)->first();
            $namefile = 'dailycheck_'.$unit->name_tester.'_'.date('YmdHis');

            if(!empty($request->date_start) && !empty($request->date_end))
            {
                $datestart = date('Y-m-d 00:00:00', strtotime($request->date_start));
                $dateend = date('Y-m-d 23:59:00', strtotime($request->date_end."+1 day"));

                $dataunit = DB::table('nt_dailycheck_unit')
                ->join('nt_dailycheck_set', 'nt_dailycheck_set.id_dyunit', '=', 'nt_dailycheck_unit.id_dyunit')
                ->where('nt_dailycheck_set.due_date','>=',$datestart)
                ->where('nt_dailycheck_set.due_date','<=',$dateend)
                ->where('nt_dailycheck_set.id_dyunit',$request->tester)
                ->get();

            }else{

                $dataunit = DB::table('nt_dailycheck_unit')
                ->join('nt_dailycheck_set', 'nt_dailycheck_set.id_dyunit', '=', 'nt_dailycheck_unit.id_dyunit')
                ->where('nt_dailycheck_set.id_dyunit',$request->tester)
                ->get();

            }

            // สร้าง Workbook ใหม่
            $spreadsheet = new Spreadsheet();

           foreach ($dataunit as $key => $value) {
               
                $sheet = $spreadsheet->createSheet();

                // ตั้งชื่อ Worksheet
                $sheet->setTitle($value->name_tester);

                $sheet->setCellValue('A1', 'Date');
                $sheet->setCellValue('B1', 'Due date');
                $sheet->setCellValue('C1', 'Record time');
                $sheet->setCellValue('D1', 'Operator');
                $sheet->setCellValue('E1', 'Status');
                $sheet->setCellValue('F1', 'Result');
                $sheet->setCellValue('G1', 'Problem');
                $sheet->setCellValue('H1', 'Root cause');
                $sheet->setCellValue('I1', 'Action');

                $info = DB::table('nt_dailycheck_info')
                ->where('id_daily', $value->id_daily)
                ->get();

                $row = 1;
                foreach ($info as $item) {

                    $row++;

                   
                    if($item->recoredtime != null){
                        $sheet->setCellValue('E'.$row, 'DONE');
                    }

                    $sheet->setCellValue('A'.$row, date('Y-m-d',strtotime($value->due_date)));
                    $sheet->setCellValue('B'.$row, date('Y-m-d H:i:s',strtotime($value->due_date)));
                    $sheet->setCellValue('C'.$row, date('Y-m-d H:i:s',strtotime($item->recoredtime)));
                    $sheet->setCellValue('D'.$row, $item->operation);
                    
                    $sheet->setCellValue('F'.$row, $item->results);
                    $sheet->setCellValue('G'.$row, $item->problem);
                    $sheet->setCellValue('H'.$row, $item->rootcause);
                    $sheet->setCellValue('I'.$row, $item->action);
                }

                $sheet->getStyle('A1:I'.$row)->applyFromArray($styleMiddle);
                $sheet->getStyle('A1:I'.$row)->applyFromArray($FULLBORDER);

                $sheet->getColumnDimension('A')->setWidth(17);
                $sheet->getColumnDimension('B')->setWidth(23);
                $sheet->getColumnDimension('C')->setWidth(23);
                $sheet->getColumnDimension('D')->setWidth(20);
                $sheet->getColumnDimension('E')->setWidth(20);
                $sheet->getColumnDimension('F')->setWidth(20);
                $sheet->getColumnDimension('G')->setWidth(20);
                $sheet->getColumnDimension('H')->setWidth(20);
                $sheet->getColumnDimension('I')->setWidth(20);

           }

           $spreadsheet->removeSheetByIndex(0);

           $writer = new Xlsx($spreadsheet);
           $writer->save(storage_path('NHK-File/report/dailycheck/'.$namefile.'.xlsx'));

            return response()->json(['success' =>  1,'filename' => $namefile.'.xlsx']);

    }


    public function table_dayilycheck(Request $request) 
    {


            $notpass = 0;
            if(!empty($request->datestart) && !empty($request->dateend))
            {
                $datestart = date('Y-m-d 00:00:00', strtotime($request->datestart));
                $dateend = date('Y-m-d 23:59:00', strtotime($request->dateend."+1 day"));
                
                $info = DB::table('nt_dailycheck_info')
                ->join('nt_dailycheck_set', 'nt_dailycheck_set.id_daily', '=', 'nt_dailycheck_info.id_daily')
                ->where('nt_dailycheck_set.due_date','>=',$datestart)
                ->where('nt_dailycheck_set.due_date','<=',$dateend)
                ->where('nt_dailycheck_set.id_dyunit', $request->tester)
                ->get();

            }else{

                $info = DB::table('nt_dailycheck_info')
                ->join('nt_dailycheck_set', 'nt_dailycheck_set.id_daily', '=', 'nt_dailycheck_info.id_daily')
                ->where('nt_dailycheck_set.id_dyunit', $request->tester)
                ->get();

            } 

                    
            if(count($info)>0){
                foreach ($info as $value) {
                    if($value->results == "NOT_PASS"){
                        $notpass++;
                    }
                }
                echo ($notpass>0)? 1 : 0;
            }else{
                echo -1;
            }


    } 

    //-------------- end Daily check -------------- 

    //-------- Begin Monitoring ------------- 
    public function oldmonitoring() 
    {

        $arraydate = [
            0 => "Monday",
            1 => "Tuesday",
            2 => "Wednesday",
            3 => "Thursday",
            4 => "Friday",
            5 => "Saturday", 
            6 => "Sunday",
        ];
        $keydatenow = array_search(date('l'), $arraydate);
        $nowdate = date('Y-m-d');

        $data = DB::table('nt_monitor_set')
        ->join('nt_monitor_unit', 'nt_monitor_unit.id_mnu', '=', 'nt_monitor_set.id_mnu')
        ->get();

        foreach ($data as $key => $value) {
            $repeatsday = explode( ',', $value->repeats_date );
            $contractDateBegin = date('Y-m-d', strtotime($value->start_date));
            $contractDateEnd = date('Y-m-d', strtotime($value->end_date));
                
            $due_date1 = date('Y-m-d');
            $due_date2 = date('H:i:s', strtotime($value->start_date));
            $due_date = date('Y-m-d H:i:s', strtotime($due_date1.' '.$due_date2));

            if (($nowdate >= $contractDateBegin) && ($nowdate <= $contractDateEnd)){
                if($repeatsday[$keydatenow] == 1){

                    if(DB::table('nt_monitor_info')->where('created_at', $nowdate)->where('id_mns', $value->id_mns)->count() == 0){
                        $parametor = [
                            'id_mns' => $value->id_mns,
                            'due_date' => $due_date,
                            'created_at' => date('Y-m-d'),
                        ];  
                        DB::table('nt_monitor_info')->insert($parametor);
                    }
                }
            } 
        }

        return view('output/monitoring');
    }

    public function monitoring() // NHK2 05/04/24 
    {
        
        $nowdate = date('Y-m-d 23:59:00');

        $data = DB::table('nt_monitor_set')
        ->join('nt_monitor_unit', 'nt_monitor_unit.id_mnu', '=', 'nt_monitor_set.id_mnu')
        ->get();

        foreach ($data as $key => $value) {

            $contractDateBegin = date('Y-m-d H:i:s', strtotime($value->start_date));
            $contractDateEnd = date('Y-m-d H:i:s', strtotime($value->end_date));

            $repeats_number = $value->repeats; 
            $repeats_unit = $value->repeats_unit; 

            if($repeats_unit == "weeks") {

                // $currentDate = $contractDateBegin;
                $currentDate = date('Y-m-d H:i:s', strtotime($contractDateBegin . '-'.$repeats_number.' weeks'));
                $endDate = $contractDateEnd;

                
                while ($currentDate <= $endDate ) {
                    
                    $currentDate = date('Y-m-d H:i:s', strtotime($currentDate . '+'.$repeats_number.' weeks'));
                    if($currentDate <= $endDate){  

                        // เพิ่ม n เดือน to $currentDate
                        // echo "ถึงกำหนด: " . $currentDate . "<br>";
                        if(DB::table('nt_monitor_info')->where('due_date', 'LIKE', date("Y-m-d", strtotime($currentDate)).'%')->where('id_mns', $value->id_mns)->count() == 0){
                            
                            $parametor = [
                                'id_mns' => $value->id_mns,
                                'due_date' => $currentDate,
                                'created_at' => date('Y-m-d'),
                            ];
                            DB::table('nt_monitor_info')->insert($parametor);
    
                        }
                    }
                }
                
            }

            if($repeats_unit == "months") {

                // $currentDate = $contractDateBegin;
                $currentDate = date('Y-m-d H:i:s', strtotime($contractDateBegin . '-'.$repeats_number.' months'));
                $endDate = $contractDateEnd;

                 
                while ($currentDate <= $endDate ) {
                    
                    $currentDate = date('Y-m-d H:i:s', strtotime($currentDate . '+'.$repeats_number.' months'));
                    if($currentDate <= $endDate){  

                        // เพิ่ม n เดือน to $currentDate
                        // echo "ถึงกำหนด: " . $currentDate . "<br>";
                        if(DB::table('nt_monitor_info')->where('due_date', 'LIKE', date("Y-m-d", strtotime($currentDate)).'%')->where('id_mns', $value->id_mns)->count() == 0){
                            
                            $parametor = [
                                'id_mns' => $value->id_mns,
                                'due_date' => $currentDate,
                                'created_at' => date('Y-m-d'),
                            ];
                            DB::table('nt_monitor_info')->insert($parametor);
    
                        }
                    }
                }
                
            }

            if($repeats_unit == "years"){

                $NewContractDateEnd = date('Y-m-d H:i:s', strtotime($contractDateEnd. ' +1 year')); 

                $diff = date_diff(date_create($contractDateBegin), date_create($NewContractDateEnd));
                $numdiff = $diff->format('%y');


                for ($i=0; $i < intval($numdiff); $i++) { 
                    
                    $tart_date = date('Y-m-d H:i:s', strtotime($contractDateBegin. ' +'.$i.' year')); 
                    if(date("Y-m-d", strtotime($nowdate)) >= date("Y-m-d", strtotime($tart_date)))
                    {
                        if(DB::table('nt_monitor_info')->where('due_date', $tart_date)->where('id_mns', $value->id_mns)->count() == 0){
                            
                            $parametor = [
                                'id_mns' => $value->id_mns,
                                'due_date' => $tart_date,
                                'created_at' => date('Y-m-d'),
                            ];
                            DB::table('nt_monitor_info')->insert($parametor);

                        }
                    }
                }
            }
            
            if($repeats_unit == "daily"){

               $NewContractDateEnd = date('Y-m-d H:i:s', strtotime($contractDateEnd. ' +1 day')); 

                $diff = date_diff(date_create($contractDateBegin), date_create($NewContractDateEnd));
                $numdiff = $diff->format('%a');  // แสดงผลลัพธ์เป็นจำนวนวัน

                for ($i=0; $i < intval($numdiff); $i++) { 
                    
                    $tart_date = date('Y-m-d H:i:s', strtotime($contractDateBegin. ' +'.$i.' day')); 
                    if(date("Y-m-d", strtotime($nowdate)) >= date("Y-m-d", strtotime($tart_date)))
                    {
                        if(DB::table('nt_monitor_info')->where('due_date', $tart_date)->where('id_mns', $value->id_mns)->count() == 0){
                            
                            $parametor = [
                                'id_mns' => $value->id_mns,
                                'due_date' => $tart_date,
                                'created_at' => date('Y-m-d'),
                            ];
                            DB::table('nt_monitor_info')->insert($parametor);

                        }
                    }
                }
            }

        }
        $allJobday = DB::table('nt_monitor_info')->count();
        $doneJobs = DB::table('nt_monitor_info')->whereNotNull('recoredtime')->count();
        $ongoingJobs = DB::table('nt_monitor_info')->whereNull('recoredtime')->count();

        $progress = [];

        $unit = DB::table('nt_monitor_unit')->get();
        foreach ($unit as $key => $_unit) {
            $set = DB::table('nt_monitor_set')->where('id_mnu', $_unit->id_mnu)->first();
            if(!empty($set)){
                $allinfo = DB::table('nt_monitor_info')->where('id_mns', $set->id_mns)->count();
                $done = DB::table('nt_monitor_info')->where('id_mns', $set->id_mns)->whereNotNull('recoredtime')->count();

                $percent = ($done/$allinfo)*100;

                if($percent > 0){
                    $progress[] = [
                        'name' => $_unit->name_tester,
                        'percent' => intval($percent),
                    ];
                }
            }
        }

        $data = [
            'allJobday' => $allJobday,
            'doneJobs' => $doneJobs,
            'ongoingJobs' => $ongoingJobs,
            'progress' => $progress,
        ];

        if(Auth::user()->status == 3){
            return view('output/monitoring', $data);
        }
        return view('output/monitoring', $data);
        
    }

    public function monitoringtable(Request $request) 
    {
        if(empty($request->date_start) && empty($request->date_end))
        {
            $start = date('Y-m-1');
            $end = date('Y-m-t');

            $data = DB::table('nt_monitor_set')
            ->join('nt_monitor_info', 'nt_monitor_info.id_mns', '=', 'nt_monitor_set.id_mns')
            ->join('nt_monitor_unit', 'nt_monitor_unit.id_mnu', '=', 'nt_monitor_set.id_mnu')
            ->whereBetween('nt_monitor_info.due_date', [$start, $end])
            ->orderby('nt_monitor_set.id_mns','ASC')
            ->get();

        }else{

            $start = date('Y-m-d', strtotime($request->date_start));
            $end = date('Y-m-d', strtotime($request->date_end. "+1 days"));

            $data = DB::table('nt_monitor_set')
            ->join('nt_monitor_info', 'nt_monitor_info.id_mns', '=', 'nt_monitor_set.id_mns')
            ->join('nt_monitor_unit', 'nt_monitor_unit.id_mnu', '=', 'nt_monitor_set.id_mnu')
            ->whereBetween('nt_monitor_info.due_date', [$start, $end])
            ->orderby('nt_monitor_set.id_mns','ASC')
            ->get();

        }
            return Datatables::of($data)->make(true);

    }

    public function monitoringedit($id)  
    {
 
            $info = DB::table('nt_monitor_info')->where('id_mnf', $id)->first();
            $set = DB::table('nt_monitor_set')->where('id_mns',$info->id_mns)->first();
            $unit = DB::table('nt_monitor_unit')->where('id_mnu',$set->id_mnu)->first();

            $data = [
                'unit' => $unit,
                'info' => $info,
                'set' => $set
            ];

            return view('output/monitoringedit', $data);
 
    }

    public function monitoringview($id)  
    {

            $info = DB::table('nt_monitor_info')->where('id_mnf', $id)->first();
            $set = DB::table('nt_monitor_set')->where('id_mns',$info->id_mns)->first();
            $unit = DB::table('nt_monitor_unit')->where('id_mnu',$set->id_mnu)->first();

            $data = [
                'unit' => $unit,
                'info' => $info,
                'set' => $set
            ];

            return view('output/monitoringview', $data);
 
    }

    public function updateMonitoring(Request $request)  
    {

        $get = DB::table('nt_monitor_info')->where('id_mnf', $request->id_mnf)->first();
        $count = $get->countqccheck;
        if(Auth::user()->status == 2){
            $count++;
        }

            $day = [
                'recoredtime' => date('Y-m-d H:i:s'),
                'results' => $request->txt_result,
                'operation' => $request->txt_operation,
                'problem' => $request->txt_problem,
                'rootcause' => $request->txt_rootcause,
                'action' => $request->txt_action,

                'problem_result' => $request->txt_problem_result,
                'rootcause_result' => $request->txt_rootcause_result,
                'action_result' => $request->txt_action_result,

                'countqccheck' => $count,
            ];

            DB::table('nt_monitor_info')->where('id_mnf', $request->id_mnf)->update($day);
            return response()->json(['success' =>  1]);

    }

    public function reportmonitor() 
    {
            return view('output/reportmonitor');
    }

    public function monitortables(Request $request) 
    {

            if(!empty($request->date_start) && !empty($request->date_end))
            {
                $datestart = date('Y-m-d 00:00:00', strtotime($request->date_start));
                $dateend = date('Y-m-d 23:59:00', strtotime($request->date_end));
                $start = "'$datestart'";
                $end = "'$dateend'";

                $dataunit = DB::table('nt_monitor_unit')
                ->select('nt_monitor_unit.*',DB::raw(''.$start .' as startdate'),DB::raw(''.$end .' as enddate'))
                ->orderby('id_mnu','ASC')
                ->get();

                $sQuery = Datatables::of($dataunit)
                ->addIndexColumn()
                ->addColumn('result',function($data){

                    $info = DB::table('nt_monitor_info')
                    ->join('nt_monitor_set', 'nt_monitor_set.id_mns', '=', 'nt_monitor_info.id_mns')
                    ->join('nt_monitor_unit', 'nt_monitor_unit.id_mnu', '=', 'nt_monitor_set.id_mnu')
                    ->where('nt_monitor_set.due_date','>=',$data->startdate)
                    ->where('nt_monitor_set.due_date','<=',$data->enddate)
                    ->where('nt_monitor_set.id_mnu', $data->id_mnu)
                    ->get();

                    $notpass = 0;
                    foreach ($info as $value) {
                        if($value->results == "NOT_PASS"){
                            $notpass++;
                        }
                    }
                    
                    if(count($info)>0){
                        return ($notpass>0)? 'NOT_PASS' : 'PASS';
                    }else{
                        return null;
                    }

                })
                ->addColumn('date_start',function($data){
                    return  date('Y-m-d',strtotime($data->startdate));
                })
                ->addColumn('date_end',function($data){
                    return  date('Y-m-d',strtotime($data->enddate));
                });

            }else{  


                $dataunit = DB::table('nt_monitor_unit')->orderby('id_mnu','ASC')->get();
                $sQuery = Datatables::of($dataunit)
                ->addIndexColumn()
                ->addColumn('result',function($data){
                    return null;
                })
                ->addColumn('date_start',function($data){
                    return null;
                })
                ->addColumn('date_end',function($data){
                    return null;
                });

            }

            return $sQuery->escapeColumns([])->make(true);

    }

    public function table_monitor(Request $request) 
    {
 
            $notpass = 0;
            if(!empty($request->datestart) && !empty($request->dateend))
            {
                $datestart = date('Y-m-d 00:00:00', strtotime($request->datestart));
                $dateend = date('Y-m-d 23:59:00', strtotime($request->dateend."+1 day"));
                
                $info = DB::table('nt_monitor_info')
                ->join('nt_monitor_set', 'nt_monitor_set.id_mns', '=', 'nt_monitor_info.id_mns')
                ->where('nt_monitor_set.due_date','>=',$datestart)
                ->where('nt_monitor_set.due_date','<=',$dateend)
                ->where('nt_monitor_set.id_mnu', $request->tester)
                ->get();

            }else{

                $info = DB::table('nt_monitor_info')
                ->join('nt_monitor_set', 'nt_monitor_set.id_mns', '=', 'nt_monitor_info.id_mns')
                ->where('nt_monitor_set.id_mnu', $request->tester)
                ->get();

            } 

                    
            if(count($info)>0){
                foreach ($info as $value) {
                    if($value->results == "NOT_PASS"){
                        $notpass++;
                    }
                }
                echo ($notpass>0)? 1 : 0;
            }else{
                echo -1;
            }


    }

    public function resetMonitoring(Request $request)  
    {
        
        if(count($request->id) > 0){

            foreach ($request->id as $key => $value) {
                if(DB::table('nt_monitor_info')->where('id_mnf', $value)->count() == 1){
                    @DB::table('nt_monitor_info')->where('id_mnf', $value)->delete();
                }
            }

            return response()->json(['success' =>  1]);

        }else{
            return response()->json(['success' =>  2]);
        }
    }

    public function excel_monitoring(Request $request) 
    {

            //------ style-------
                $styleRight = [
                    'alignment' => [ 
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ];

                $styleLeft = [
                    'alignment' => [ 
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ];

                $styleMiddle = [
                    'alignment' => [
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ];

                $FULLBORDER = array(
                    'borders' => array(
                        'inside' => array(
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ),
                        'outline' => array(
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ),

                    ),
                ); 

                // จัดรูปแบบส่วนของข้อมูล
                $styleArray_Data = [
                    'borders' => [ // กำหนดเส้นขอบ
                        'vertical' => [ // เส้นขอบแนวตั้งด้านใน
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                        'outline' => [ // เส้นขอบด้านนอก
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ],
                ];

            //------ style-------

            $namefile = 'Monitoring_'.date('YmdHis');
             
            // สร้าง Workbook ใหม่
            $spreadsheet = new Spreadsheet();

            $dataunit = DB::table('nt_monitor_unit')->where('id_mnu', $request->tester)->first();

                $sheet = $spreadsheet->createSheet();

                $sheet->setCellValue('A1', 'Date');
                $sheet->setCellValue('B1', 'Due date');
                $sheet->setCellValue('C1', 'Record time');
                $sheet->setCellValue('D1', 'Operator');
                $sheet->setCellValue('E1', 'Status');
                $sheet->setCellValue('F1', 'Result');
                $sheet->setCellValue('G1', 'Problem');
                $sheet->setCellValue('H1', 'Root cause');
                $sheet->setCellValue('I1', 'Action');

                // ตั้งชื่อ Worksheet
                $sheet->setTitle($dataunit->name_tester);

                if(!empty($request->date_start) && !empty($request->date_end))
                {
                    $datestart = date('Y-m-d 00:00:00', strtotime($request->date_start));
                    $dateend = date('Y-m-d 23:59:00', strtotime($request->date_end."+1 day"));

                    $info = DB::table('nt_monitor_info')
                    ->join('nt_monitor_set', 'nt_monitor_set.id_mns', '=', 'nt_monitor_info.id_mns')
                    ->join('nt_monitor_unit', 'nt_monitor_unit.id_mnu', '=', 'nt_monitor_set.id_mnu')
                    ->where('nt_monitor_set.due_date','>=',$datestart)
                    ->where('nt_monitor_set.due_date','<=',$dateend)
                    ->where('nt_monitor_unit.id_mnu', $dataunit->id_mnu)
                    ->get();

                }else{

                    $info = DB::table('nt_monitor_info')
                    ->join('nt_monitor_set', 'nt_monitor_set.id_mns', '=', 'nt_monitor_info.id_mns')
                    ->join('nt_monitor_unit', 'nt_monitor_unit.id_mnu', '=', 'nt_monitor_set.id_mnu')
                    ->where('nt_monitor_unit.id_mnu', $dataunit->id_mnu)
                    ->get();

                }

                $row = 1;
                foreach ($info as $item) {

                    $row++;
                    if($item->recoredtime != null){
                        $sheet->setCellValue('E'.$row, 'DONE');
                    }

                    $sheet->setCellValue('A'.$row, date('Y-m-d',strtotime($item->due_date)));
                    $sheet->setCellValue('B'.$row, date('Y-m-d H:i:s',strtotime($item->due_date)));
                    $sheet->setCellValue('C'.$row, date('Y-m-d H:i:s',strtotime($item->recoredtime)));
                    $sheet->setCellValue('D'.$row, $item->operation);
                    
                    $sheet->setCellValue('F'.$row, $item->results);
                    $sheet->setCellValue('G'.$row, $item->problem);
                    $sheet->setCellValue('H'.$row, $item->rootcause);
                    $sheet->setCellValue('I'.$row, $item->action);
                }         

                $sheet->getStyle('A1:I'.$row)->applyFromArray($styleMiddle);
                $sheet->getStyle('A1:I'.$row)->applyFromArray($FULLBORDER);
                $sheet->getStyle('A2:I'.$row)->getAlignment()->setWrapText(true);

                $sheet->getColumnDimension('A')->setWidth(17);
                $sheet->getColumnDimension('B')->setWidth(23);
                $sheet->getColumnDimension('C')->setWidth(23);
                $sheet->getColumnDimension('D')->setWidth(20);
                $sheet->getColumnDimension('E')->setWidth(20);
                $sheet->getColumnDimension('F')->setWidth(20);
                $sheet->getColumnDimension('G')->setWidth(20);
                $sheet->getColumnDimension('H')->setWidth(20);
                $sheet->getColumnDimension('I')->setWidth(20);   
    

            $spreadsheet->removeSheetByIndex(0);

            $writer = new Xlsx($spreadsheet);
            $writer->save(storage_path('NHK-File/report/Monitoring/'.$namefile.'.xlsx'));

            return response()->json(['success' =>  1,'filename' => $namefile.'.xlsx']);

    }

    public function excel_monitoringall(Request $request) 
    {

            //------ style-------
                $styleRight = [
                    'alignment' => [ 
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ];

                $styleLeft = [
                    'alignment' => [ 
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ];

                $styleMiddle = [
                    'alignment' => [
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ];

                $FULLBORDER = array(
                    'borders' => array(
                        'inside' => array(
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ),
                        'outline' => array(
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ),

                    ),
                ); 

                // จัดรูปแบบส่วนของข้อมูล
                $styleArray_Data = [
                    'borders' => [ // กำหนดเส้นขอบ
                        'vertical' => [ // เส้นขอบแนวตั้งด้านใน
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                        'outline' => [ // เส้นขอบด้านนอก
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ],
                ];

            //------ style-------

            $namefile = 'Monitoring_all_'.date('YmdHis');
             
            // สร้าง Workbook ใหม่
            $spreadsheet = new Spreadsheet();

            $dataunit = DB::table('nt_monitor_unit')->get();
            foreach ($dataunit as $key => $value) {

                $sheet = $spreadsheet->createSheet();

                $sheet->setCellValue('A1', 'Date');
                $sheet->setCellValue('B1', 'Due date');
                $sheet->setCellValue('C1', 'Record time');
                $sheet->setCellValue('D1', 'Operator');
                $sheet->setCellValue('E1', 'Status');
                $sheet->setCellValue('F1', 'Result');
                $sheet->setCellValue('G1', 'Problem');
                $sheet->setCellValue('H1', 'Root cause');
                $sheet->setCellValue('I1', 'Action');

                // ตั้งชื่อ Worksheet
                $sheet->setTitle($value->name_tester);

                if(!empty($request->date_start) && !empty($request->date_end))
                {
                    $datestart = date('Y-m-d 00:00:00', strtotime($request->date_start));
                    $dateend = date('Y-m-d 23:59:00', strtotime($request->date_end."+1 day"));

                    $info = DB::table('nt_monitor_info')
                    ->join('nt_monitor_set', 'nt_monitor_set.id_mns', '=', 'nt_monitor_info.id_mns')
                    ->join('nt_monitor_unit', 'nt_monitor_unit.id_mnu', '=', 'nt_monitor_set.id_mnu')
                    ->where('nt_monitor_set.due_date','>=',$datestart)
                    ->where('nt_monitor_set.due_date','<=',$dateend)
                    ->where('nt_monitor_unit.id_mnu', $value->id_mnu)
                    ->get();

                }else{

                    $info = DB::table('nt_monitor_info')
                    ->join('nt_monitor_set', 'nt_monitor_set.id_mns', '=', 'nt_monitor_info.id_mns')
                    ->join('nt_monitor_unit', 'nt_monitor_unit.id_mnu', '=', 'nt_monitor_set.id_mnu')
                    ->where('nt_monitor_unit.id_mnu', $value->id_mnu)
                    ->get();

                }

                $row = 1;
                foreach ($info as $item) {

                    $row++;
                    if($item->recoredtime != null){
                        $sheet->setCellValue('E'.$row, 'DONE');
                    }

                    $sheet->setCellValue('A'.$row, date('Y-m-d',strtotime($item->due_date)));
                    $sheet->setCellValue('B'.$row, date('Y-m-d H:i:s',strtotime($item->due_date)));
                    $sheet->setCellValue('C'.$row, date('Y-m-d H:i:s',strtotime($item->recoredtime)));
                    $sheet->setCellValue('D'.$row, $item->operation);
                    
                    $sheet->setCellValue('F'.$row, $item->results);
                    $sheet->setCellValue('G'.$row, $item->problem);
                    $sheet->setCellValue('H'.$row, $item->rootcause);
                    $sheet->setCellValue('I'.$row, $item->action);
                }         

                $sheet->getStyle('A1:I'.$row)->applyFromArray($styleMiddle);
                $sheet->getStyle('A1:I'.$row)->applyFromArray($FULLBORDER);
                $sheet->getStyle('A2:I'.$row)->getAlignment()->setWrapText(true);

                $sheet->getColumnDimension('A')->setWidth(17);
                $sheet->getColumnDimension('B')->setWidth(23);
                $sheet->getColumnDimension('C')->setWidth(23);
                $sheet->getColumnDimension('D')->setWidth(20);
                $sheet->getColumnDimension('E')->setWidth(20);
                $sheet->getColumnDimension('F')->setWidth(20);
                $sheet->getColumnDimension('G')->setWidth(20);
                $sheet->getColumnDimension('H')->setWidth(20);
                $sheet->getColumnDimension('I')->setWidth(20);   
    
            }

            $spreadsheet->removeSheetByIndex(0);

            $writer = new Xlsx($spreadsheet);
            $writer->save(storage_path('NHK-File/report/Monitoring/'.$namefile.'.xlsx'));

            return response()->json(['success' =>  1,'filename' => $namefile.'.xlsx']);

    }

    //-------- End Monitoring ------------- 

        // -------------- BEGIN 4.SGT AML return -------------------
    // 
    public function editdataamllpc($id)
    {
        $data_lpc = DB::table('nt_data_lpc')->where('id_createlab', $id)->first();
        $oqa_lab = DB::table('nt_oqa_lab')
        ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
        ->join('nt_model','nt_model.id_model','=','nt_oqa_lab.id_model')
        ->where('nt_oqa_lab.id_createlab', $id)
        ->first();
        $oqa_labfc = DB::table('nt_datacf_lpc')
        ->where('id_createlab', $id)
        ->first();
        
        $spec = DB::table('nt_model_spec')
        ->join('nt_model','nt_model.id_model','=','nt_model_spec.id_model')
        ->where('nt_model_spec.id_model', $oqa_lab->id_model)
        ->where('nt_model_spec.id_model_type', 1)
        ->first();
        $ucl = DB::table('nt_model_spec')
        ->where('id_model', $oqa_lab->id_model)
        ->where('id_model_type', 3)
        ->first();
        
        $aml_file = DB::table('nt_oqa_aml_file')->where('id_createlab', $id)->whereNotnull('file_lpc')->first();

        return view('output/editaamllpc', [
            'data_lpc' => $data_lpc,
            'oqa_lab' => $oqa_lab,
            'spec' => $spec,
            'ucl' => $ucl,
            'oqa_labfc' => $oqa_labfc,
            'aml_file' => $aml_file,
        ]);

    }

    public function uploade_aml_lpc(Request $request)
    {

        $name_aml_lpc = $request->aml_lpc;
        $file = $request->file('fileInput');
        $fileName = $file->getClientOriginalName(); // รับชื่อไฟล์ต้นฉบับ

        // function check ชื่อไฟล์
        $fullnamefile = explode("_",$fileName);

        $namefile_0 = $fullnamefile[0];
        $namefile_1 = @$fullnamefile[1];
        $namefile_2 = @$fullnamefile[2];
        $namefile_3 = @$fullnamefile[3];
        $namefile_4 = @$fullnamefile[4];
        $namefile_5 = @$fullnamefile[5];
        $namefile_6 = @$fullnamefile[6];
        $namefile_7 = @$fullnamefile[7];
        $namefile_8 = @$fullnamefile[8];
        $namefile_9 = @$fullnamefile[9];
        $namefile_10 = @$fullnamefile[10];

        if(count($fullnamefile) == 11){

            $cut_namefile0 = explode("-",$namefile_0);
            if($cut_namefile0[0] == "LPC")            // เช็คชั้นที่ 1
            {
                $chk_2 = $namefile_5.'_'.$namefile_6.'_'.$namefile_7.'_'.$namefile_8;
                if( $chk_2 == $name_aml_lpc)  // เช็คชั้นที่ 2
                {
                    $path = storage_path('NHK-File/OQA/AML/LPC/'); 
                    $file->move($path, $fileName);

                    $chk = DB::table('nt_oqa_aml_file')->where('id_createlab', $request->id_createlab)->first();

                    if(!empty($chk)){

                        $update_file = [
                            'file_lpc' => $fileName,
                            'lpc_update' => date('Y-m-d H:i:s')
                        ];
                        DB::table('nt_oqa_aml_file')->where('id_createlab', $request->id_createlab)->update($update_file);

                    }else{
                       
                        $update_file = [
                            'id_createlab' => $request->id_createlab,
                            'file_lpc' => $fileName,
                            'lpc_update' => date('Y-m-d H:i:s'),
                            'created_at' => date('Y-m-d H:i:s')
                        ];
                        DB::table('nt_oqa_aml_file')->insert($update_file);

                    }

                    $getfile = storage_path('NHK-File/OQA/AML/LPC/'.$fileName);
                    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($getfile);
                    $sheet = $spreadsheet->setActiveSheetIndex(0);

                    $LPC_Average = $sheet->getCell('V13')->getCalculatedValue();
                    $LPC_01 = $sheet->getCell('W13')->getCalculatedValue();
                    $LPC_02 = $sheet->getCell('X13')->getCalculatedValue();
                    $LPC_03 = $sheet->getCell('Y13')->getCalculatedValue();
 
                    $data = [
                        'lpc_data' => $LPC_Average,
                        'last_lpc1' => $LPC_01,
                        'last_lpc2' => $LPC_02,
                        'last_lpc3' => $LPC_03,
                    ];
                    $chck = DB::table('nt_data_lpc')->where('id_createlab', $request->id_createlab)->update($data);

                    $oqafile =  DB::table('nt_oqa_lab')->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')->where('nt_oqa_lab.id_createlab', $request->id_createlab)->first();
                        $datamail = [
                            'testter'=>"LPC",
                            'model'=> $oqafile->name_model,
                            'lot'=> $oqafile->lotnumber_lab,
                            'date'=>date('d/m/Y H:i:s'),
                        ];
                        $mail = new MailController();
                        @$mail->mailconfirmlpcAML($datamail);

                    return response()->json(['success' =>  1]);
                    
                }
            }
        }

        return response()->json(['success' =>  2]);

    }

    public function editdataamlgcms($id)
    {
        $data_gcms = DB::table('nt_data_gcms')->where('id_createlab', $id)->first();
        $oqa_lab = DB::table('nt_oqa_lab')
        ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
        ->join('nt_model','nt_model.id_model','=','nt_oqa_lab.id_model')
        ->where('nt_oqa_lab.id_createlab', $id)
        ->first();
        $oqa_labfc = DB::table('nt_datacf_gcms')
        ->where('id_createlab', $id)
        ->first();
        
        $spec = DB::table('nt_model_spec')
        ->join('nt_model','nt_model.id_model','=','nt_model_spec.id_model')
        ->where('nt_model_spec.id_model', $oqa_lab->id_model)
        ->where('nt_model_spec.id_model_type', 1)
        ->first();
        $ucl = DB::table('nt_model_spec')
        ->where('id_model', $oqa_lab->id_model)
        ->where('id_model_type', 3)
        ->first();
        
        $aml_file = DB::table('nt_oqa_aml_file')->where('id_createlab', $id)->whereNotnull('file_gcms')->first();

        return view('output/editaamlgcms', [
            'data_gcms' => $data_gcms,
            'oqa_lab' => $oqa_lab,
            'spec' => $spec,
            'ucl' => $ucl,
            'oqa_labfc' => $oqa_labfc,
            'aml_file' => $aml_file,
        ]);

    }

    public function uploade_aml_gcms(Request $request)
    {

        $name_aml_gcms = $request->aml_gcms;
        $file = $request->file('fileInput');
        $fileName = $file->getClientOriginalName(); // รับชื่อไฟล์ต้นฉบับ
        // function check ชื่อไฟล์
        $fullnamefile = explode("_",$fileName);

        $namefile_0 = $fullnamefile[0];
        $namefile_1 = @$fullnamefile[1];
        $namefile_2 = @$fullnamefile[2];
        $namefile_3 = @$fullnamefile[3];
        $namefile_4 = @$fullnamefile[4];
        $namefile_5 = @$fullnamefile[5];
        $namefile_6 = @$fullnamefile[6];
        $namefile_7 = @$fullnamefile[7];
        $namefile_8 = @$fullnamefile[8];
 
        if(count($fullnamefile) == 9){

            $cut_namefile0 = explode("-",$namefile_0);

            if($cut_namefile0[0] == "DHS")            // เช็คชั้นที่ 1
            {
                $chk_2 = $namefile_3.'_'.$namefile_4.'_'.$namefile_5.'_'.$namefile_6;

                if( $chk_2 == $name_aml_gcms)  // เช็คชั้นที่ 2
                {
                    $path = storage_path('NHK-File/OQA/AML/GCMS/'); 
                    $file->move($path, $fileName);
                    
                    if(file_exists(storage_path('NHK-File/OQA/AML/GCMS/'.$fileName))){
                         
                        $chk = DB::table('nt_oqa_aml_file')->where('id_createlab', $request->id_createlab)->first();
                        if(!empty($chk)){

                            $update_file = [
                                'file_gcms' => $fileName,
                                'gcms_update' => date('Y-m-d H:i:s')
                            ];
                            DB::table('nt_oqa_aml_file')->where('id_createlab', $request->id_createlab)->update($update_file);

                        }else{
                        
                            if(DB::table('nt_oqa_aml_file')->where('id_createlab', $request->id_createlab)->count() == 0){
                                
                                $update_file = [
                                    'id_createlab' => $request->id_createlab,
                                    'file_gcms' => $fileName,
                                    'gcms_update' => date('Y-m-d H:i:s'),
                                    'created_at' => date('Y-m-d H:i:s')
                                ];
                                DB::table('nt_oqa_aml_file')->insert($update_file);
                            
                            }else{

                                $update_file = [
                                    'file_gcms' => $fileName,
                                    'gcms_update' => date('Y-m-d H:i:s')
                                ];
                                DB::table('nt_oqa_aml_file')->where('id_createlab', $request->id_createlab)->update($update_file);

                            }

                        }

                        $getfile = storage_path('NHK-File/OQA/AML/GCMS/'.$fileName);
                        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($getfile);
                        $sheet = $spreadsheet->setActiveSheetIndex(0);

                        $RowComp5 = null;
                        $RowComp9 = null;
                        $RowComp11_1 = null;
                        $RowComp15_2 = null;
                        $RowComp16 = null;
                        $RowCompLow = null;
                        $RowCompHigh = null;
                        $RowOutgassing = null;

                        $RowComp1 = null;
                        
                        for ($row = 1; $row <= 70; $row++) {
                            $cellValue = trim($sheet->getCell('C' . $row)->getValue()); 

                            //----- GCMS Smear Trend Chart -----
                            if ($cellValue == "C5.0 Organic Acid") {
                                $RowComp5 = $row;
                            }
                            if ($cellValue == "C9.0 Amine") {
                                $RowComp9 = $row;
                            }
                            if ($cellValue == "C11.1 Butylated Hydroxy Toluene") {
                                $RowComp11_1 = $row;
                            }
                            if ($cellValue == "C15.2 Dimethoxy Acetophenone") {
                                $RowComp15_2 = $row;
                            }
                            if ($cellValue == "C16.0 Siloxane") {
                                $RowComp16 = $row;
                            }
                            if ($cellValue == "C25.0 Aromatic HC") {
                                $RowComp25 = $row;
                            }
                            //----- Low MW Compounds -----
                            if ($cellValue == "C34.0 Low MW Compounds") {
                                $RowCompLow = $row;
                            }
                            //----- High MW Compounds -----
                            if ($cellValue == "C35.2 High MW Compounds") {
                                $RowCompHigh = $row;
                            }
                            //----- Total Outgassing -----
                            if ($cellValue == "Total Outgassing") {
                                $RowOutgassing = $row;
                            }
 
                        } 
                        $valueComp5 = $sheet->getCell('D'.$RowComp5)->getCalculatedValue();
                        $valueComp9 = $sheet->getCell('D'.$RowComp9)->getCalculatedValue();
                        $valueComp11_1 = $sheet->getCell('D'.$RowComp11_1)->getCalculatedValue();
                        $valueComp15_2 = $sheet->getCell('D'.$RowComp15_2)->getCalculatedValue();
                        $valueComp16 = $sheet->getCell('D'.$RowComp16)->getCalculatedValue();
                        $valueComp25 = $sheet->getCell('D'.$RowComp25)->getCalculatedValue();
                        $smear = floatval($valueComp5)+floatval($valueComp9)+floatval($valueComp11_1)+floatval($valueComp15_2)+floatval($valueComp16)+floatval($valueComp25);

                        $valueLow = $sheet->getCell('D'.$RowCompLow)->getCalculatedValue();
                        $valueHigh = $sheet->getCell('D'.$RowCompHigh)->getCalculatedValue();
                        $valueOutgassing = $sheet->getCell('D'.$RowOutgassing)->getCalculatedValue();

                        $data = [
                            'last_gcms_smear' => floatval($smear),
                            'last_gcms_outgassing' => floatval($valueOutgassing),
                            'last_gcms_low_mw' => floatval($valueLow),
                            'last_gcms_high_mw' => floatval($valueHigh),    
                        ];
                        $chck = DB::table('nt_data_gcms')->where('id_createlab', $request->id_createlab)->update($data);  

                        $dataList = [];
                        for ($row = 3; $row <= 67; $row++) {

                            if ($row == 66) {
                                continue; 
                            }

                            $name = $sheet->getCell('C' . $row)->getValue();
                            $value = $sheet->getCell('D' . $row)->getValue();

                            if (!empty($name)) {
                                $cleanName = trim($name);
                                $dbColumn = null; 

                                if (preg_match('/^C(\d+)\.(\d+)/', $cleanName, $matches)) {
                                    $mainNum = $matches[1];
                                    $subNum  = $matches[2];

                                    if ($subNum == '0') {
                                        $dbColumn = 'last_c' . $mainNum;
                                    } else {
                                        $dbColumn = 'last_c' . $mainNum . '_' . $subNum;
                                    }
                                } 
                                elseif (stripos($cleanName, 'Total') !== false) {
                                    $dbColumn = 'last_total'; 
                                }

                                $dataList[] = [
                                    'row'       => $row,
                                    'name'      => $cleanName,
                                    'value'     => $value,
                                    'db_column' => $dbColumn
                                ];
                            }
                        }

                        $updateData = [];
                        foreach ($dataList as $item) {
                            if (!empty($item['db_column'])) {
                                $updateData[$item['db_column']] = $item['value'];
                            }
                        }
                        $tableColumns = Schema::getColumnListing('nt_outgassing_gcms');
                        $validUpdateData = [];
                        foreach ($updateData as $column => $value) {
                            if (in_array($column, $tableColumns)) {
                                $validUpdateData[$column] = $value;
                            } 
                        }
                        if (!empty($validUpdateData)) {
                            DB::table('nt_outgassing_gcms')
                                ->where('id_createlab', $request->id_createlab)
                                ->update($validUpdateData);
                        }

                        //----------------------------------------------------- 

                        $oqafile =  DB::table('nt_oqa_lab')->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')->where('nt_oqa_lab.id_createlab', $request->id_createlab)->first();
                        $datamail = [
                            'testter'=>"GCMS",
                            'model'=> $oqafile->name_model,
                            'lot'=> $oqafile->lotnumber_lab,
                            'date'=>date('d/m/Y H:i:s'),
                        ];
                        $mail = new MailController();
                        @$mail->mailconfirmgcmsAML($datamail);

                        return response()->json(['success' =>  1]); 
                    }
                    
                }
            }
        }

        return response()->json(['success' =>  2]);

    } 
    // --------------- END 4.SGT AML return --------------------

    // -------------- BEGIN 5.Daily check and monitoring graph -----------------
    public function dailycheck_sear_graph(Request $request)
    {

        if(empty($request->start_date) && empty($request->end_date))
        {
            $allJobday = 0;
            $doneJobs = 0;
            $ongoingJobs = 0;

            $dailycheck = DB::table('nt_dailycheck_info')->get();

            foreach ($dailycheck as $key => $value) {
                if(!empty($value->recoredtime)){
                    $doneJobs++;
                }else{
                    $ongoingJobs++;
                }
                $allJobday++;
            }

        }else {

            $allJobday = 0;
            $doneJobs = 0;
            $ongoingJobs = 0;

            $datestart = date('Y-m-d 00:00:00', strtotime($request->start_date));
            $dateend = date('Y-m-d 23:59:00', strtotime($request->end_date));

            $dailycheck = DB::table('nt_dailycheck_info')
            ->where('nt_dailycheck_info.due_date','>=',$datestart)
            ->where('nt_dailycheck_info.due_date','<=',$dateend)
            ->get();

            foreach ($dailycheck as $key => $value) {
                if(!empty($value->recoredtime)){
                    $doneJobs++;
                }else{
                    $ongoingJobs++;
                }
                $allJobday++;
            }
        }    
        $data = [$doneJobs,$ongoingJobs];
        $progress = [];

        $unit = DB::table('nt_dailycheck_unit')->get();
        foreach ($unit as $key => $_unit) {
            if(empty($request->start_date) && empty($request->end_date))
            {
                $set = DB::table('nt_dailycheck_set')->where('id_dyunit', $_unit->id_dyunit)->first();
                if(!empty($set)){

                    $allinfo = DB::table('nt_dailycheck_info')->where('id_daily', $set->id_daily)->count();
                    $done = DB::table('nt_dailycheck_info')->where('id_daily', $set->id_daily)->whereNotNull('recoredtime')->count();
                    
                    $percent = ($done/$allinfo)*100;

                    if($percent > 0){
                        $progress[] = [
                            'name' => $_unit->name_tester,
                            'percent' => intval($percent),
                        ];
                    }
                    
                }
            }else{

                $datestart = date('Y-m-d 00:00:00', strtotime($request->start_date));
                $dateend = date('Y-m-d 23:59:00', strtotime($request->end_date));

                $set = DB::table('nt_dailycheck_set')->where('id_dyunit', $_unit->id_dyunit)->first();
                if(!empty($set)){

                    $allinfo = DB::table('nt_dailycheck_info')->where('id_daily', $set->id_daily)->whereBetween('due_date', [$datestart, $dateend])->count();
                    $done = DB::table('nt_dailycheck_info')->where('id_daily', $set->id_daily)->whereBetween('due_date', [$datestart, $dateend])->whereNotNull('recoredtime')->count();
                    
                    $percent = ($done/$allinfo)*100;

                    if($percent > 0){
                        $progress[] = [
                            'name' => $_unit->name_tester,
                            'percent' => intval($percent),
                        ];
                    }
                    
                }

            }
        }

        return response()->json([
            'data' =>$data,
            'progress' =>$progress,
        ]);
    }

    public function monitoring_sear_graph(Request $request)
    {

        if(empty($request->start_date) && empty($request->end_date))
        {
            $allJobday = 0;
            $doneJobs = 0;
            $ongoingJobs = 0;

            $dailycheck = DB::table('nt_monitor_info')->get();

            foreach ($dailycheck as $key => $value) {
                if(!empty($value->recoredtime)){
                    $doneJobs++;
                }else{
                    $ongoingJobs++;
                }
                $allJobday++;
            }

        }else {

            $allJobday = 0;
            $doneJobs = 0;
            $ongoingJobs = 0;

            $datestart = date('Y-m-d 00:00:00', strtotime($request->start_date));
            $dateend = date('Y-m-d 23:59:00', strtotime($request->end_date));

            $dailycheck = DB::table('nt_monitor_info')
            ->whereBetween('due_date', [$datestart, $dateend])
            ->get();

            foreach ($dailycheck as $key => $value) {
                if(!empty($value->recoredtime)){
                    $doneJobs++;
                }else{
                    $ongoingJobs++;
                }
                $allJobday++;
            }
        }    
        $data = [$doneJobs,$ongoingJobs];

        $progress = [];

        $unit = DB::table('nt_monitor_unit')->get();
        foreach ($unit as $key => $_unit) {
            if(empty($request->start_date) && empty($request->end_date))
            {
                $set = DB::table('nt_monitor_set')->where('id_mnu', $_unit->id_mnu)->first();
                if(!empty($set)){

                    $allL = 0;
                    $doneL = 0;
                    foreach ($set as $key => $value) {

                        $allinfo = DB::table('nt_monitor_info')->where('id_mns', $value->id_mns)->count();
                        $done = DB::table('nt_monitor_info')->where('id_mns', $value->id_mns)->whereNotNull('recoredtime')->count();

                        $allL += $allinfo;
                        $doneL += $done;
                    }

                    if($doneL != 0){
                        $percent = ($doneL/$allL)*100;
                    }else{
                        $percent = 0;
                    }

                    if($allL > 0){
                        $progress[] = [
                            'name' => $_unit->name_tester,
                            'percent' => intval($percent),
                            'all' => intval($allL),
                            'done' => intval($doneL),
                        ]; 
                    }
                    
                }
            }else{

                $datestart = date('Y-m-d 00:00:00', strtotime($request->start_date));
                $dateend = date('Y-m-d 23:59:00', strtotime($request->end_date));

                $set = DB::table('nt_monitor_set')->where('id_mnu', $_unit->id_mnu)->get();
                if(!empty($set)){

                    $allL = 0;
                    $doneL = 0;
                    foreach ($set as $key => $value) {

                        $allinfo = DB::table('nt_monitor_info')->where('id_mns', $value->id_mns)->whereBetween('due_date', [$datestart, $dateend])->count();
                        $done = DB::table('nt_monitor_info')->where('id_mns', $value->id_mns)->whereBetween('due_date', [$datestart, $dateend])->whereNotNull('recoredtime')->count();

                        $allL += $allinfo;
                        $doneL += $done;
                    }
                }

                if($doneL != 0){
                    $percent = ($doneL/$allL)*100;
                }else{
                    $percent = 0;
                }

                if($allL > 0){
                    $progress[] = [
                        'name' => $_unit->name_tester,
                        'percent' => intval($percent),
                        'all' => intval($allL),
                        'done' => intval($doneL),
                    ]; 
                }

            }
        }

        return response()->json([
            'data' =>$data,
            'progress' =>$progress,
        ]);
    }

    
    // -------------- END 5.Daily check and monitoring graph -------------------

    public function resultsnewversion()
    {

        return view('output/resultsnewversion');

    }


    
}
