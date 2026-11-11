<?php

namespace App\Http\Controllers;
use Datatables;
use DB;
use Illuminate\Http\Request;
use Response;
use File;
use Excel;
use Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Session;
use slope;
use PDF;
use Dompdf\Dompdf;

use PhpOffice\PhpSpreadsheet\IOFactory; 
use PhpOffice\PhpSpreadsheet\Spreadsheet; 
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class CalculationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    private function calcStatDateRange($data)
    {
        static $cache = [];
        $key = $data->id_model.'|'.$data->startdate.'|'.$data->enddate.'|'.$data->number;
        if (array_key_exists($key, $cache)) {
            return $cache[$key];
        }

        if ($data->number == 0) {
            $firstdate = $data->startdate;
            $lastdate = $data->enddate;
        } else {
            $datachkf = DB::table('nt_model')
                ->join('nt_oqa_lab', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                ->where('nt_oqa_lab.save_date', '>=', $data->startdate)
                ->where('nt_oqa_lab.save_date', '<=', $data->enddate)
                ->where('nt_model.id_model', '=', $data->id_model)
                ->select('nt_oqa_lab.save_date as save_datest')
                ->orderBy('nt_oqa_lab.save_date', 'DESC')->first();

            $firstdate = $datachkf
                ? date('Y-m-d 00:00:00', strtotime($datachkf->save_datest.' -'.$data->number.' day'))
                : '';

            $datachkl = DB::table('nt_model')
                ->join('nt_oqa_lab', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                ->where('nt_oqa_lab.save_date', '>=', $data->startdate)
                ->where('nt_oqa_lab.save_date', '<=', $data->enddate)
                ->where('nt_model.id_model', '=', $data->id_model)
                ->select('nt_oqa_lab.save_date as save_datest')
                ->orderBy('nt_oqa_lab.save_date', 'DESC')->first();

            $lastdate = $datachkl ? $datachkl->save_datest : '';
        }

        return $cache[$key] = [$firstdate, $lastdate];
    }
    
    private function calcLpcStats($data)
    {
        static $cache = [];
        [$firstdate, $lastdate] = $this->calcStatDateRange($data);
        $key = $data->id_model.'|'.$firstdate.'|'.$lastdate;
        if (array_key_exists($key, $cache)) {
            return $cache[$key];
        }

        $row = DB::table('nt_data_lpc')
            ->join('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_data_lpc.id_createlab')
            ->where('nt_oqa_lab.save_date', '>=', $firstdate)
            ->where('nt_oqa_lab.save_date', '<=', $lastdate)
            ->where('nt_oqa_lab.id_model', $data->id_model)
            ->selectRaw('AVG(nt_data_lpc.lpc_data) as avg_val, MAX(nt_data_lpc.lpc_data) as max_val, MIN(nt_data_lpc.lpc_data) as min_val, STDDEV_SAMP(nt_data_lpc.lpc_data) as std_val')
            ->first();

        return $cache[$key] = $row;
    }
    
    private function calcLatestSaveDate($data)
    {
        static $cache = [];
        $key = $data->id_model.'|'.$data->enddate;
        if (array_key_exists($key, $cache)) {
            return $cache[$key];
        }

        $datachkl = DB::table('nt_model')
            ->join('nt_oqa_lab', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
            ->where('nt_oqa_lab.save_date', '<=', $data->enddate)
            ->where('nt_model.id_model', '=', $data->id_model)
            ->select('nt_oqa_lab.save_date as save_datest')
            ->orderBy('nt_oqa_lab.save_date', 'DESC')
            ->first();

        $lastdate = $datachkl ? date('Y-m-d 00:00:00', strtotime($datachkl->save_datest)) : '';

        return $cache[$key] = $lastdate;
    }

    public function datatablecalculation(Request $request) 
    {
        if (empty($request->date_start) && empty($request->date_end)) {
            // ไม่เรียก ->get() ที่นี่ — ส่ง Query Builder ดิบเข้า Datatables::of()
            // เพื่อให้ Yajra ใช้ QueryDataTable engine (LIMIT/OFFSET ระดับ SQL ก่อนค่อยรัน addColumn)
            // แทนที่จะโหลดทั้งตารางเข้า Collection แล้วรัน addColumn กับทุกแถวก่อนตัดหน้า
            $datalist = DB::table('nt_model')->where('status_model', 1);
            $sQuery = Datatables::of($datalist)
                ->addIndexColumn()
                ->addColumn('lpccount', function ($data) { return null; })
                ->addColumn('semcount', function ($data) { return null; })
                ->addColumn('iccount', function ($data) { return null; })
                ->addColumn('gcmscount', function ($data) { return null; })
                ->addColumn('ftircount', function ($data) { return null; })
                ->addColumn('countdata', function ($data) { return 1; })
                ->addColumn('lastdate', function ($data) { return ''; })
                ->addColumn('firstdate', function ($data) { return ''; })
                ->addColumn('mean', function ($data) { return null; })
                ->addColumn('max', function ($data) { return null; })
                ->addColumn('min', function ($data) { return null; })
                ->addColumn('std', function ($data) { return null; })
                ->addColumn('mean3', function ($data) { return null; })
                ->addColumn('mean4', function ($data) { return null; })
                ->addColumn('mean4_5', function ($data) { return null; })
                ->addColumn('mean6', function ($data) { return null; });
        } elseif (!empty($request->date_end) && !empty($request->number)) {

            $datestart = date('Y-m-d 00:00:00', strtotime('2019-01-01'));
            $dateend = date('Y-m-d 00:00:00', strtotime($request->date_end.' + 1 day'));
            $start = "'$datestart'";
            $end = "'$dateend'";
            $number = $request->number;
            // ไม่เรียก ->get() — เหตุผลเดียวกับด้านบน ให้ Yajra จำกัดจำนวนแถวที่ระดับ SQL ก่อน
            $datalist = DB::table('nt_model')
                ->where('status_model', 1)
                ->select('nt_model.*', DB::raw(''.$start.' as startdate'), DB::raw(''.$end.' as enddate'), DB::raw(''.$number.' as number'));

            $sQuery = Datatables::of($datalist)
                ->addIndexColumn()
                ->addColumn('lpccount', function ($data) {
                    if ($data->number == 0) {
                        $firstdate = $data->startdate;
                        $lastdate = $data->enddate;
                    } else {
                        $get_datestart = DB::table('nt_data_lpc')
                            ->join('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_data_lpc.id_createlab')
                            ->where('nt_oqa_lab.save_date', '<=', $data->enddate)
                            ->where('nt_oqa_lab.id_model', $data->id_model)
                            ->orderBy('nt_oqa_lab.save_date', 'ASC')
                            ->skip(0)->take($data->number)
                            ->first();

                        $firstdate = $get_datestart ? date('Y-m-d 00:00:00', strtotime($get_datestart->save_date)) : '';
                        $lastdate = $this->calcLatestSaveDate($data);
                    }

                    $lpccount = DB::table('nt_data_lpc')
                        ->join('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_data_lpc.id_createlab')
                        ->whereBetween('nt_oqa_lab.save_date', [$firstdate, $lastdate])
                        ->where('nt_oqa_lab.id_model', $data->id_model)
                        ->skip(0)->take($data->number)
                        ->get();

                    return count($lpccount);
                })
                ->addColumn('semcount', function ($data) {
                    if ($data->number == 0) {
                        $firstdate = $data->startdate;
                        $lastdate = $data->enddate;
                    } else {
                        $get_datestart = DB::table('nt_data_sem')
                            ->join('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_data_sem.id_createlab')
                            ->where('nt_oqa_lab.save_date', '<=', $data->enddate)
                            ->where('nt_oqa_lab.id_model', $data->id_model)
                            ->orderBy('nt_oqa_lab.save_date', 'ASC')
                            ->skip(0)->take($data->number)
                            ->first();

                        $firstdate = $get_datestart ? date('Y-m-d 00:00:00', strtotime($get_datestart->save_date)) : '';
                        $lastdate = $this->calcLatestSaveDate($data);
                    }

                    $semcount = DB::table('nt_data_sem')
                        ->join('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_data_sem.id_createlab')
                        ->whereBetween('nt_oqa_lab.save_date', [$firstdate, $lastdate])
                        ->where('nt_oqa_lab.id_model', $data->id_model)
                        ->skip(0)->take($data->number)
                        ->get();

                    return count($semcount);
                })
                ->addColumn('iccount', function ($data) {
                    if ($data->number == 0) {
                        $firstdate = $data->startdate;
                        $lastdate = $data->enddate;
                    } else {
                        $get_datestart = DB::table('nt_data_ic')
                            ->join('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_data_ic.id_createlab')
                            ->where('nt_oqa_lab.save_date', '<=', $data->enddate)
                            ->where('nt_oqa_lab.id_model', $data->id_model)
                            ->orderBy('nt_oqa_lab.save_date', 'ASC')
                            ->skip(0)->take($data->number)
                            ->first();

                        $firstdate = $get_datestart ? date('Y-m-d 00:00:00', strtotime($get_datestart->save_date)) : '';
                        $lastdate = $this->calcLatestSaveDate($data);
                    }

                    $iccount = DB::table('nt_data_ic')
                        ->join('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_data_ic.id_createlab')
                        ->whereBetween('nt_oqa_lab.save_date', [$firstdate, $lastdate])
                        ->where('nt_oqa_lab.id_model', $data->id_model)
                        ->skip(0)->take($data->number)
                        ->get();

                    return count($iccount);
                })
                ->addColumn('gcmscount', function ($data) {
                    if ($data->number == 0) {
                        $firstdate = $data->startdate;
                        $lastdate = $data->enddate;
                    } else {
                        $get_datestart = DB::table('nt_data_gcms')
                            ->join('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_data_gcms.id_createlab')
                            ->where('nt_oqa_lab.save_date', '<=', $data->enddate)
                            ->where('nt_oqa_lab.id_model', $data->id_model)
                            ->orderBy('nt_oqa_lab.save_date', 'ASC')
                            ->skip(0)->take($data->number)
                            ->first();

                        $firstdate = $get_datestart ? date('Y-m-d 00:00:00', strtotime($get_datestart->save_date)) : '';
                        $lastdate = $this->calcLatestSaveDate($data);
                    }

                    $gcmscount = DB::table('nt_data_gcms')
                        ->join('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_data_gcms.id_createlab')
                        ->whereBetween('nt_oqa_lab.save_date', [$firstdate, $lastdate])
                        ->where('nt_oqa_lab.id_model', $data->id_model)
                        ->skip(0)->take($data->number)
                        ->get();

                    return count($gcmscount);
                })
                ->addColumn('ftircount', function ($data) {
                    if ($data->number == 0) {
                        $firstdate = $data->startdate;
                        $lastdate = $data->enddate;
                    } else {
                        $get_datestart = DB::table('nt_datacf_ftir')
                            ->join('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_datacf_ftir.id_createlab')
                            ->where('nt_oqa_lab.save_date', '<=', $data->enddate)
                            ->where('nt_oqa_lab.id_model', $data->id_model)
                            ->orderBy('nt_oqa_lab.save_date', 'ASC')
                            ->skip(0)->take($data->number)
                            ->first();

                        $firstdate = $get_datestart ? date('Y-m-d 00:00:00', strtotime($get_datestart->save_date)) : '';
                        $lastdate = $this->calcLatestSaveDate($data);
                    }

                    $ftircount = DB::table('nt_datacf_ftir')
                        ->join('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_datacf_ftir.id_createlab')
                        ->whereBetween('nt_oqa_lab.save_date', [$firstdate, $lastdate])
                        ->where('nt_oqa_lab.ftir_confirm_st', 1)
                        ->where('nt_oqa_lab.id_model', $data->id_model)
                        ->skip(0)->take($data->number)
                        ->get();

                    return count($ftircount);
                })
                ->addColumn('countdata', function ($data) { return 1; })
                ->addColumn('lastdate', function ($data) {
                    if ($data->number == 0) {
                        return date('Y-m-d 00:00:00', strtotime($data->enddate.'-1 day'));
                    }
                    [, $lastdate] = $this->calcStatDateRange($data);
                    return $lastdate;
                })
                ->addColumn('firstdate', function ($data) {
                    if ($data->number == 0) {
                        return $data->startdate;
                    }
                    [$firstdate] = $this->calcStatDateRange($data);
                    return $firstdate;
                })
                ->addColumn('mean', function ($data) {
                    $stats = $this->calcLpcStats($data);
                    return number_format($stats->avg_val, 2, '.', '');
                })
                ->addColumn('max', function ($data) {
                    $stats = $this->calcLpcStats($data);
                    return number_format($stats->max_val, 2, '.', '');
                })
                ->addColumn('min', function ($data) {
                    $stats = $this->calcLpcStats($data);
                    return number_format($stats->min_val, 2, '.', '');
                })
                ->addColumn('std', function ($data) {
                    $stats = $this->calcLpcStats($data);
                    return number_format($stats->std_val, 2, '.', '');
                })
                ->addColumn('mean3', function ($data) {
                    $stats = $this->calcLpcStats($data);
                    return number_format($stats->avg_val - (3 * $stats->std_val), 2, '.', '');
                })
                ->addColumn('mean4', function ($data) {
                    $stats = $this->calcLpcStats($data);
                    return number_format($stats->avg_val - (4 * $stats->std_val), 2, '.', '');
                })
                ->addColumn('mean4_5', function ($data) {
                    $stats = $this->calcLpcStats($data);
                    return number_format($stats->avg_val - (4.5 * $stats->std_val), 2, '.', '');
                })
                ->addColumn('mean6', function ($data) {
                    $stats = $this->calcLpcStats($data);
                    return number_format($stats->avg_val - (6 * $stats->std_val), 2, '.', '');
                });

        } else {

            $datestart = date('Y-m-d H:i:s', strtotime($request->date_start));
            $dateend = date('Y-m-d 00:00:00', strtotime($request->date_end.' + 1 day'));
            $start = "'$datestart'";
            $end = "'$dateend'";
            $number = "'$request->number'";
            // ไม่เรียก ->get() — เหตุผลเดียวกัน
            $datalist = DB::table('nt_model')
                ->where('status_model', 1)
                ->select('nt_model.*', DB::raw(''.$start.' as startdate'), DB::raw(''.$end.' as enddate'), DB::raw(''.$number.' as number'));

            $sQuery = Datatables::of($datalist)
                ->addIndexColumn()
                ->addColumn('lpccount', function ($data) {
                    [$firstdate, $lastdate] = $this->calcStatDateRange($data);
                    return DB::table('nt_data_lpc')
                        ->join('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_data_lpc.id_createlab')
                        ->where('nt_oqa_lab.save_date', '>=', $firstdate)
                        ->where('nt_oqa_lab.save_date', '<=', $lastdate)
                        ->where('nt_oqa_lab.id_model', $data->id_model)
                        ->count();
                })
                ->addColumn('semcount', function ($data) {
                    [$firstdate, $lastdate] = $this->calcStatDateRange($data);
                    return DB::table('nt_data_sem')
                        ->join('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_data_sem.id_createlab')
                        ->where('nt_oqa_lab.save_date', '>=', $firstdate)
                        ->where('nt_oqa_lab.save_date', '<=', $lastdate)
                        ->where('nt_oqa_lab.id_model', $data->id_model)
                        ->count();
                })
                ->addColumn('iccount', function ($data) {
                    [$firstdate, $lastdate] = $this->calcStatDateRange($data);
                    return DB::table('nt_data_ic')
                        ->join('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_data_ic.id_createlab')
                        ->where('nt_oqa_lab.save_date', '>=', $firstdate)
                        ->where('nt_oqa_lab.save_date', '<=', $lastdate)
                        ->where('nt_oqa_lab.id_model', $data->id_model)
                        ->count();
                })
                ->addColumn('gcmscount', function ($data) {
                    [$firstdate, $lastdate] = $this->calcStatDateRange($data);
                    return DB::table('nt_data_gcms')
                        ->join('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_data_gcms.id_createlab')
                        ->where('nt_oqa_lab.save_date', '>=', $firstdate)
                        ->where('nt_oqa_lab.save_date', '<=', $lastdate)
                        ->where('nt_oqa_lab.id_model', $data->id_model)
                        ->count();
                })
                ->addColumn('ftircount', function ($data) {
                    [$firstdate, $lastdate] = $this->calcStatDateRange($data);
                    return DB::table('nt_datacf_ftir')
                        ->join('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_datacf_ftir.id_createlab')
                        ->where('nt_oqa_lab.save_date', '>=', $firstdate)
                        ->where('nt_oqa_lab.save_date', '<=', $lastdate)
                        ->where('nt_oqa_lab.ftir_confirm_st', 1)
                        ->where('nt_oqa_lab.id_model', $data->id_model)
                        ->count();
                })
                ->addColumn('countdata', function ($data) { return 2; })
                ->addColumn('lastdate', function ($data) {
                    if ($data->number == 0) {
                        return date('Y-m-d 00:00:00', strtotime($data->enddate.'-1 day'));
                    }
                    [, $lastdate] = $this->calcStatDateRange($data);
                    return $lastdate;
                })
                ->addColumn('firstdate', function ($data) {
                    if ($data->number == 0) {
                        return $data->startdate;
                    }
                    [$firstdate] = $this->calcStatDateRange($data);
                    return $firstdate;
                })
                ->addColumn('mean', function ($data) {
                    $stats = $this->calcLpcStats($data);
                    return number_format($stats->avg_val, 2, '.', '');
                })
                ->addColumn('max', function ($data) {
                    $stats = $this->calcLpcStats($data);
                    return number_format($stats->max_val, 2, '.', '');
                })
                ->addColumn('min', function ($data) {
                    $stats = $this->calcLpcStats($data);
                    return number_format($stats->min_val, 2, '.', '');
                })
                ->addColumn('std', function ($data) {
                    $stats = $this->calcLpcStats($data);
                    return number_format($stats->std_val, 2, '.', '');
                })
                ->addColumn('mean3', function ($data) {
                    $stats = $this->calcLpcStats($data);
                    return number_format($stats->avg_val - (3 * $stats->std_val), 2, '.', '');
                })
                ->addColumn('mean4', function ($data) {
                    $stats = $this->calcLpcStats($data);
                    return number_format($stats->avg_val - (4 * $stats->std_val), 2, '.', '');
                })
                ->addColumn('mean4_5', function ($data) {
                    $stats = $this->calcLpcStats($data);
                    return number_format($stats->avg_val - (4.5 * $stats->std_val), 2, '.', '');
                })
                ->addColumn('mean6', function ($data) {
                    $stats = $this->calcLpcStats($data);
                    return number_format($stats->avg_val - (6 * $stats->std_val), 2, '.', '');
                });
        }

        return $sQuery->escapeColumns([])->make(true);
    }

    public function lpccalculationexport(Request $request)	
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
                    $modelid1 = $model1->id_model;
                    $modelid2 = $model2->id_model;
                    $datestart1 = date('Y-m-d 00:00:00', strtotime($request->input('datestart'.$model1->id_model)));
                    $datestart2 = date('Y-m-d 00:00:00', strtotime($request->input('datestart'.$model2->id_model)));

                    // $dateend1 = date('Y-m-d 00:00:00', strtotime($request->input('dateend'.$model1->id_model). ' + 1 day'));
                    // $dateend2 = date('Y-m-d 00:00:00', strtotime($request->input('dateend'.$model2->id_model). ' + 1 day'));
                    $dateend1 = date('Y-m-d 00:00:00', strtotime($request->input('dateend'.$model1->id_model)));
                    $dateend2 = date('Y-m-d 00:00:00', strtotime($request->input('dateend'.$model2->id_model)));

                    $dataexport = [];
                    $newdata =  array (
                        'modelid' => $modelid1,
                        'datestart' => $datestart1,
                        'dateend' => $dateend1,
                    );
                    array_push($dataexport,$newdata);
                    $newdata =  array (
                        'modelid' => $modelid2,
                        'datestart' => $datestart2,
                        'dateend' => $dateend2,
                    );
                    array_push($dataexport,$newdata);
                
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
        $namefile = 'Calculation_LPC_'.date("YmdHis");
  
        $file = storage_path('NHK-File/template/report/template_calculation2.xlsx'); // for test on orange host

        Excel::load($file, function ($doc)use($dataexport,$request) {

            $i=5;
            $chklpccount = 0;
            $chksemcount = 0;
            $chiccount = 0;
            $chkgcmscount = 0;
            $chkftircount = 0;
            foreach ($dataexport as $item) {  

                $model = DB::table('nt_model')
                ->join('nt_model_spec','nt_model_spec.id_model','=','nt_model.id_model')
                ->where('nt_model.id_model', $item['modelid'])
                ->where('nt_model_spec.id_model_type', 1)
                ->first();

                $nt_model_spec = DB::table('nt_model_spec')
                ->join('nt_model_spec_type', 'nt_model_spec.id_model_type', '=', 'nt_model_spec_type.id_model_type')
                ->where('nt_model_spec.id_model', $item['modelid'])
                ->where('nt_model_spec.id_model_type', 1)
                ->first();

                $lpccount = DB::table('nt_data_lpc')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_lpc.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])
                ->count();
 
                $chklpccount = $chklpccount + $lpccount;

                $maxdata = DB::table('nt_data_lpc')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_lpc.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->max('lpc_data');
                $max = number_format($maxdata, 2, '.', '');
               
                $mindata = DB::table('nt_data_lpc')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_lpc.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->min('lpc_data');
                $min = number_format($mindata, 2, '.', '');

                $meandata = DB::table('nt_data_lpc')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_lpc.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->avg('lpc_data');
                $mean = number_format($meandata, 2, '.', '');
  
                $stddata = DB::table('nt_data_lpc')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_lpc.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$item['datestart'])
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid']);
                $stdget = $stddata->select(DB::raw('STDDEV_SAMP(nt_data_lpc.lpc_data) as std'))->first();
                $std = number_format($stdget->std, 2, '.', '');   
                $mean6data = $meandata + (6*$stdget->std);
                $mean6 = number_format($mean6data, 2, '.', '');
 

                //---------- Use Count data --------------

                $nowstartdate = $item['datestart'];

                if($request->numcountdata > 0){

                    $getlpc = DB::table('nt_data_lpc')
                    ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_lpc.id_createlab')
                    ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                    ->where('nt_oqa_lab.id_model',$item['modelid'])
                    ->orderBy('nt_oqa_lab.save_date','DESC')
                    ->take($request->numcountdata)
                    ->get();

                    foreach ($getlpc as $value) {
                        $nowstartdate = $value->save_date;
                    }

                    $lpccount = count($getlpc);
                    $chklpccount = $chklpccount + $lpccount;

                    $maxdata = DB::table('nt_data_lpc')
                    ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_lpc.id_createlab')
                    ->where('nt_oqa_lab.save_date','>=',$nowstartdate)
                    ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                    ->where('nt_oqa_lab.id_model',$item['modelid'])
                    ->max('lpc_data');
                    $max = number_format($maxdata, 2, '.', '');

                    $mindata = DB::table('nt_data_lpc')
                    ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_lpc.id_createlab')
                    ->where('nt_oqa_lab.save_date','>=',$nowstartdate)
                    ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                    ->where('nt_oqa_lab.id_model',$item['modelid']) 
                    ->min('lpc_data');
                    $min = number_format($mindata, 2, '.', '');

                    $meandata = DB::table('nt_data_lpc')
                    ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_lpc.id_createlab')
                    ->where('nt_oqa_lab.save_date','>=',$nowstartdate)
                    ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                    ->where('nt_oqa_lab.id_model',$item['modelid']) 
                    ->avg('lpc_data');
                    $mean = number_format($meandata, 2, '.', '');
      
                    $stddata = DB::table('nt_data_lpc')
                    ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_lpc.id_createlab')
                    ->where('nt_oqa_lab.save_date','>=',$nowstartdate)
                    ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                    ->where('nt_oqa_lab.id_model',$item['modelid']);
                    $stdget = $stddata->select(DB::raw('STDDEV_SAMP(nt_data_lpc.lpc_data) as std'))->first();
                    $std = number_format($stdget->std, 2, '.', '');   
                    $mean6data = $meandata + (6*$stdget->std);
                    $mean6 = number_format($mean6data, 2, '.', '');
                }
                //---------- Use Count data --------------

                $getfirstdate = DB::table('nt_data_lpc')
                ->select('nt_oqa_lab.save_date')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_lpc.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdate)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])
                ->orderBy('nt_oqa_lab.save_date','ASC')
                ->first(); 
                $getlastdate = DB::table('nt_data_lpc')
                ->select('nt_oqa_lab.save_date')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_lpc.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdate)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])
                ->orderBy('nt_oqa_lab.save_date','DESC')
                ->first();  

                $sheet = $doc->setActiveSheetIndex(0);

                $sheet->setCellValue('A'.$i,$model->name_model);
                $sheet->setCellValue('B'.$i,$model->part);
                $sheet->setCellValue('C'.$i,$model->part_number);
                $sheet->setCellValue('D'.$i,$lpccount);
                $sheet->setCellValue('E'.$i,$max);
                $sheet->setCellValue('F'.$i,$min);
                $sheet->setCellValue('G'.$i,$mean);
                $sheet->setCellValue('H'.$i,$std);
                $sheet->setCellValue('I'.$i, date('Y-m-d', strtotime($getfirstdate->save_date)));
                $sheet->setCellValue('J'.$i, date('Y-m-d', strtotime($getlastdate->save_date)));
                // $sheet->setCellValue('P'.$i,$mean6);
                $sheet->setCellValue('P'.$i, $nt_model_spec->alpc);


                $i++;
                
            }
            $doc->setActiveSheetIndexByName('SEM C 1');
            $sheetsem1 = $doc->getActiveSheetIndex();
            $doc->removeSheetByIndex($sheetsem1);

            $doc->setActiveSheetIndexByName('SEM C 2');
            $sheetsem2 = $doc->getActiveSheetIndex();
            $doc->removeSheetByIndex($sheetsem2);

            $doc->setActiveSheetIndexByName('SEM C 3');
            $sheetsem3 = $doc->getActiveSheetIndex();
            $doc->removeSheetByIndex($sheetsem3);

            $doc->setActiveSheetIndexByName('SEM C 3.1');
            $sheetsem4 = $doc->getActiveSheetIndex();
            $doc->removeSheetByIndex($sheetsem4);

            $doc->setActiveSheetIndexByName('SEM C 3.2');
            $sheetsem5 = $doc->getActiveSheetIndex();
            $doc->removeSheetByIndex($sheetsem5);

            $doc->setActiveSheetIndexByName('SEM C 3.3');
            $sheetsem6 = $doc->getActiveSheetIndex();
            $doc->removeSheetByIndex($sheetsem6);

            $doc->setActiveSheetIndexByName('SEM C 4');
            $sheetsem7 = $doc->getActiveSheetIndex();
            $doc->removeSheetByIndex($sheetsem7);

            $doc->setActiveSheetIndexByName('SEM C 5');
            $sheetsem8 = $doc->getActiveSheetIndex();
            $doc->removeSheetByIndex($sheetsem8);

            $doc->setActiveSheetIndexByName('SEM C 5.1');
            $sheetsem9 = $doc->getActiveSheetIndex();
            $doc->removeSheetByIndex($sheetsem9);

            $doc->setActiveSheetIndexByName('SEM C 6');
            $sheetsem10 = $doc->getActiveSheetIndex();
            $doc->removeSheetByIndex($sheetsem10);

            $doc->setActiveSheetIndexByName('Anion');
            $sheetic11 = $doc->getActiveSheetIndex();
            $doc->removeSheetByIndex($sheetic11);

            $doc->setActiveSheetIndexByName('Sulphate');
            $sheetic12 = $doc->getActiveSheetIndex();
            $doc->removeSheetByIndex($sheetic12);

            $doc->setActiveSheetIndexByName('Cation');
            $sheetic13 = $doc->getActiveSheetIndex();
            $doc->removeSheetByIndex($sheetic13);
     
            $doc->setActiveSheetIndexByName('Outgassing');
            $sheetgcms14 = $doc->getActiveSheetIndex();
            $doc->removeSheetByIndex($sheetgcms14);

            $doc->setActiveSheetIndexByName('Low MW');
            $sheetgcms15 = $doc->getActiveSheetIndex();
            $doc->removeSheetByIndex($sheetgcms15);

            $doc->setActiveSheetIndexByName('High MW');
            $sheetgcms16 = $doc->getActiveSheetIndex();
            $doc->removeSheetByIndex($sheetgcms16);

            $doc->setActiveSheetIndexByName('Smear');
            $sheetgcms17 = $doc->getActiveSheetIndex();
            $doc->removeSheetByIndex($sheetgcms17);
         
            $doc->setActiveSheetIndexByName('FTIR');
            $sheetftir18 = $doc->getActiveSheetIndex(0);
            $doc->removeSheetByIndex($sheetftir18);


        })->setFilename($namefile)->store('xlsx', storage_path('NHK-File/report/calculation'));

        return response()->json(['success' =>  1,'filename' => $namefile.'.xlsx']);
    }

    public function semcalculationexport(Request $request)	
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
                    $modelid1 = $model1->id_model;
                    $modelid2 = $model2->id_model;
                    $datestart1 = date('Y-m-d 00:00:00', strtotime($request->input('datestart'.$model1->id_model)));
                    $datestart2= date('Y-m-d 00:00:00', strtotime($request->input('datestart'.$model2->id_model)));
                    $dateend1 = date('Y-m-d 00:00:00', strtotime($request->input('dateend'.$model1->id_model). ' + 1 day'));
                    $dateend2 = date('Y-m-d 00:00:00', strtotime($request->input('dateend'.$model2->id_model). ' + 1 day'));
                    $dataexport = [];
                    $newdata =  array (
                        'modelid' => $modelid1,
                        'datestart' => $datestart1,
                        'dateend' => $dateend1,
                    );
                    array_push($dataexport,$newdata);
                    $newdata =  array (
                        'modelid' => $modelid2,
                        'datestart' => $datestart2,
                        'dateend' => $dateend2,
                    );
                    array_push($dataexport,$newdata);
                
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
        $namefile = 'Calculation_SEM_'.date("YmdHis");

        $file = storage_path('NHK-File/template/report/template_calculation2.xlsx'); // for test on orange host

        Excel::load($file, function ($doc)use($dataexport,$request) {

            $doc->setActiveSheetIndexByName('ALPC');
            $sheetlpc0 = $doc->getActiveSheetIndex();
            $doc->removeSheetByIndex($sheetlpc0);
           
            $doc->setActiveSheetIndexByName('Anion');
            $sheetic11 = $doc->getActiveSheetIndex();
            $doc->removeSheetByIndex($sheetic11);

            $doc->setActiveSheetIndexByName('Sulphate');
            $sheetic12 = $doc->getActiveSheetIndex();
            $doc->removeSheetByIndex($sheetic12);

            $doc->setActiveSheetIndexByName('Cation');
            $sheetic13 = $doc->getActiveSheetIndex();
            $doc->removeSheetByIndex($sheetic13);
          
            $doc->setActiveSheetIndexByName('Outgassing');
            $sheetgcms14 = $doc->getActiveSheetIndex();
            $doc->removeSheetByIndex($sheetgcms14);

            $doc->setActiveSheetIndexByName('Low MW');
            $sheetgcms15 = $doc->getActiveSheetIndex();
            $doc->removeSheetByIndex($sheetgcms15);

            $doc->setActiveSheetIndexByName('High MW');
            $sheetgcms16 = $doc->getActiveSheetIndex();
            $doc->removeSheetByIndex($sheetgcms16);

            $doc->setActiveSheetIndexByName('Smear');
            $sheetgcms17 = $doc->getActiveSheetIndex();
            $doc->removeSheetByIndex($sheetgcms17);
           
            $doc->setActiveSheetIndexByName('FTIR');
            $sheetftir18 = $doc->getActiveSheetIndex();
            $doc->removeSheetByIndex($sheetftir18);
           
            $i=5;
            $chklpccount = 0;
            $chksemcount = 0;
            $chkiccount = 0;
            $chkgcmscount = 0;
            $chkftircount = 0;
            foreach ($dataexport as $item) { 

                $model = DB::table('nt_model')
                ->join('nt_model_spec','nt_model_spec.id_model','=','nt_model.id_model')
                ->where('nt_model.id_model', $item['modelid'])
                ->where('nt_model_spec.id_model_type', 1)
                ->first();

                $nt_model_spec = DB::table('nt_model_spec')
                ->join('nt_model_spec_type', 'nt_model_spec.id_model_type', '=', 'nt_model_spec_type.id_model_type')
                ->where('nt_model_spec.id_model', $item['modelid'])
                ->where('nt_model_spec.id_model_type', 1)
                ->first();

                $nowstartdate = $item['datestart'];
                //---------- Use Count data --------------

                if($request->numcountdata > 0){
                    $getsem = DB::table('nt_data_sem')
                    ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                    ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                    ->where('nt_oqa_lab.id_model',$item['modelid'])
                    ->orderBy('nt_oqa_lab.save_date','DESC')
                    ->take($request->numcountdata)
                    ->get();

                    foreach ($getsem as $value) {
                        $nowstartdate = $value->save_date;
                    }
                }

                //---------- Use Count data --------------

                
                $count = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdate)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->count();

                $chksemcount = $chksemcount + $count;
                $maxdata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdate)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->max('sem_class1');
                $max = number_format($maxdata, 2, '.', '');
               
                $mindata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdate)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->min('sem_class1');
                $min = number_format($mindata, 2, '.', '');

                $meandata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdate)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->avg('sem_class1');
                $mean = number_format($meandata, 2, '.', '');
   
                $stddata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdate)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid']);
                $stdget = $stddata->select(DB::raw('STDDEV_SAMP(nt_data_sem.sem_class1) as std'))->first();
                $std = number_format($stdget->std, 2, '.', '');   
                $mean6data = $meandata + (6*$stdget->std);
                $mean6 = number_format($mean6data, 2, '.', '');

                $getfirstdate = DB::table('nt_data_sem')
                ->select('nt_oqa_lab.save_date')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdate)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])
                ->orderBy('nt_oqa_lab.save_date','ASC')
                ->first(); 
                $getlastdate = DB::table('nt_data_sem')
                ->select('nt_oqa_lab.save_date')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdate)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])
                ->orderBy('nt_oqa_lab.save_date','DESC')
                ->first();  
  
                $sheet = $doc->setActiveSheetIndexByName('SEM C 1');

                $sheet->setCellValue('A'.$i,$model->name_model);
                $sheet->setCellValue('B'.$i,$model->part);
                $sheet->setCellValue('C'.$i,$model->part_number);
                $sheet->setCellValue('D'.$i,$count);
                $sheet->setCellValue('E'.$i,$max);
                $sheet->setCellValue('F'.$i,$min);
                $sheet->setCellValue('G'.$i,$mean);
                $sheet->setCellValue('H'.$i,$std);
                $sheet->setCellValue('I'.$i, date('Y-m-d', strtotime($getfirstdate->save_date)));
                $sheet->setCellValue('J'.$i, date('Y-m-d', strtotime($getlastdate->save_date)));
                // $sheet->setCellValue('P'.$i,$mean6);
                $sheet->setCellValue('P'.$i,$nt_model_spec->class1); 

                
                $maxdata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdate)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->max('sem_class2');
                $max = number_format($maxdata, 2, '.', '');
               
                $mindata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdate)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->min('sem_class2');
                $min = number_format($mindata, 2, '.', '');

                $meandata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdate)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->avg('sem_class2');
                $mean = number_format($meandata, 2, '.', '');
   
                $stddata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdate)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid']);
                $stdget = $stddata->select(DB::raw('STDDEV_SAMP(nt_data_sem.sem_class2) as std'))->first();
                $std = number_format($stdget->std, 2, '.', '');   
                $mean6data = $meandata + (6*$stdget->std);
                $mean6 = number_format($mean6data, 2, '.', '');

                // $sheet = $doc->setActiveSheetIndex(2);
                $sheet = $doc->setActiveSheetIndexByName('SEM C 2');

                $sheet->setCellValue('A'.$i,$model->name_model);
                $sheet->setCellValue('B'.$i,$model->part);
                $sheet->setCellValue('C'.$i,$model->part_number);
                $sheet->setCellValue('D'.$i,$count);
                $sheet->setCellValue('E'.$i,$max);
                $sheet->setCellValue('F'.$i,$min);
                $sheet->setCellValue('G'.$i,$mean);
                $sheet->setCellValue('H'.$i,$std);
                $sheet->setCellValue('I'.$i, date('Y-m-d', strtotime($getfirstdate->save_date)));
                $sheet->setCellValue('J'.$i, date('Y-m-d', strtotime($getlastdate->save_date)));
                // $sheet->setCellValue('P'.$i,$mean6);
                $sheet->setCellValue('P'.$i,$nt_model_spec->class2); 

                
                $maxdata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdate)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->max('sem_class3');
                $max = number_format($maxdata, 2, '.', '');
               
                $mindata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdate)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->min('sem_class3');
                $min = number_format($mindata, 2, '.', '');

                $meandata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdate)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->avg('sem_class3');
                $mean = number_format($meandata, 2, '.', '');
   
                $stddata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdate)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid']);
                $stdget = $stddata->select(DB::raw('STDDEV_SAMP(nt_data_sem.sem_class3) as std'))->first();
                $std = number_format($stdget->std, 2, '.', '');   
                $mean6data = $meandata + (6*$stdget->std);
                $mean6 = number_format($mean6data, 2, '.', '');

                // $sheet = $doc->setActiveSheetIndex(3);
                $sheet = $doc->setActiveSheetIndexByName('SEM C 3');

                $sheet->setCellValue('A'.$i,$model->name_model);
                $sheet->setCellValue('B'.$i,$model->part);
                $sheet->setCellValue('C'.$i,$model->part_number);
                $sheet->setCellValue('D'.$i,$count);
                $sheet->setCellValue('E'.$i,$max);
                $sheet->setCellValue('F'.$i,$min);
                $sheet->setCellValue('G'.$i,$mean);
                $sheet->setCellValue('H'.$i,$std);
                $sheet->setCellValue('I'.$i, date('Y-m-d', strtotime($getfirstdate->save_date)));
                $sheet->setCellValue('J'.$i, date('Y-m-d', strtotime($getlastdate->save_date)));
                // $sheet->setCellValue('P'.$i,$mean6);
                $sheet->setCellValue('P'.$i,$nt_model_spec->class3);

                
                $maxdata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdate)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->max('sem_class3_1');
                $max = number_format($maxdata, 2, '.', '');
               
                $mindata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdate)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->min('sem_class3_1');
                $min = number_format($mindata, 2, '.', '');

                $meandata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdate)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->avg('sem_class3_1');
                $mean = number_format($meandata, 2, '.', '');
   
                $stddata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdate)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid']);
                $stdget = $stddata->select(DB::raw('STDDEV_SAMP(nt_data_sem.sem_class3_1) as std'))->first();
                $std = number_format($stdget->std, 2, '.', '');   
                $mean6data = $meandata + (6*$stdget->std);
                $mean6 = number_format($mean6data, 2, '.', '');

                // $sheet = $doc->setActiveSheetIndex(4);
                $sheet = $doc->setActiveSheetIndexByName('SEM C 3.1');

                $sheet->setCellValue('A'.$i,$model->name_model);
                $sheet->setCellValue('B'.$i,$model->part);
                $sheet->setCellValue('C'.$i,$model->part_number);
                $sheet->setCellValue('D'.$i,$count);
                $sheet->setCellValue('E'.$i,$max);
                $sheet->setCellValue('F'.$i,$min);
                $sheet->setCellValue('G'.$i,$mean);
                $sheet->setCellValue('H'.$i,$std);
                $sheet->setCellValue('I'.$i, date('Y-m-d', strtotime($getfirstdate->save_date)));
                $sheet->setCellValue('J'.$i, date('Y-m-d', strtotime($getlastdate->save_date)));
                // $sheet->setCellValue('P'.$i,$mean6);
                $sheet->setCellValue('P'.$i,$nt_model_spec->class3_1);

                
                $maxdata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdate)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->max('sem_class3_2');
                $max = number_format($maxdata, 2, '.', '');
               
                $mindata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdate)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->min('sem_class3_2');
                $min = number_format($mindata, 2, '.', '');

                $meandata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdate)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->avg('sem_class3_2');
                $mean = number_format($meandata, 2, '.', '');
   
                $stddata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdate)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid']);
                $stdget = $stddata->select(DB::raw('STDDEV_SAMP(nt_data_sem.sem_class3_2) as std'))->first();
                $std = number_format($stdget->std, 2, '.', '');   
                $mean6data = $meandata + (6*$stdget->std);
                $mean6 = number_format($mean6data, 2, '.', '');

                // $sheet = $doc->setActiveSheetIndex(5);
                $sheet = $doc->setActiveSheetIndexByName('SEM C 3.2');

                $sheet->setCellValue('A'.$i,$model->name_model);
                $sheet->setCellValue('B'.$i,$model->part);
                $sheet->setCellValue('C'.$i,$model->part_number);
                $sheet->setCellValue('D'.$i,$count);
                $sheet->setCellValue('E'.$i,$max);
                $sheet->setCellValue('F'.$i,$min);
                $sheet->setCellValue('G'.$i,$mean);
                $sheet->setCellValue('H'.$i,$std);
                $sheet->setCellValue('I'.$i, date('Y-m-d', strtotime($getfirstdate->save_date)));
                $sheet->setCellValue('J'.$i, date('Y-m-d', strtotime($getlastdate->save_date)));
                // $sheet->setCellValue('P'.$i,$mean6);
                $sheet->setCellValue('P'.$i,$nt_model_spec->class3_2);

                
                $maxdata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdate)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->max('sem_class3_3');
                $max = number_format($maxdata, 2, '.', '');
               
                $mindata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdate)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->min('sem_class3_3');
                $min = number_format($mindata, 2, '.', '');

                $meandata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdate)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->avg('sem_class3_3');
                $mean = number_format($meandata, 2, '.', '');
   
                $stddata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdate)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid']);
                $stdget = $stddata->select(DB::raw('STDDEV_SAMP(nt_data_sem.sem_class3_3) as std'))->first();
                $std = number_format($stdget->std, 2, '.', '');   
                $mean6data = $meandata + (6*$stdget->std);
                $mean6 = number_format($mean6data, 2, '.', '');

                // $sheet = $doc->setActiveSheetIndex(6);
                $sheet = $doc->setActiveSheetIndexByName('SEM C 3.3');

                $sheet->setCellValue('A'.$i,$model->name_model);
                $sheet->setCellValue('B'.$i,$model->part);
                $sheet->setCellValue('C'.$i,$model->part_number);
                $sheet->setCellValue('D'.$i,$count);
                $sheet->setCellValue('E'.$i,$max);
                $sheet->setCellValue('F'.$i,$min);
                $sheet->setCellValue('G'.$i,$mean);
                $sheet->setCellValue('H'.$i,$std);
                $sheet->setCellValue('I'.$i, date('Y-m-d', strtotime($getfirstdate->save_date)));
                $sheet->setCellValue('J'.$i, date('Y-m-d', strtotime($getlastdate->save_date)));
                // $sheet->setCellValue('P'.$i,$mean6);
                $sheet->setCellValue('P'.$i,$nt_model_spec->class3_3);

                
                $maxdata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdate)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->max('sem_class4');
                $max = number_format($maxdata, 2, '.', '');
               
                $mindata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdate)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->min('sem_class4');
                $min = number_format($mindata, 2, '.', '');

                $meandata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdate)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->avg('sem_class4');
                $mean = number_format($meandata, 2, '.', '');
   
                $stddata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdate)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid']);
                $stdget = $stddata->select(DB::raw('STDDEV_SAMP(nt_data_sem.sem_class4) as std'))->first();
                $std = number_format($stdget->std, 2, '.', '');   
                $mean6data = $meandata + (6*$stdget->std);
                $mean6 = number_format($mean6data, 2, '.', '');

                // $sheet = $doc->setActiveSheetIndex(7);
                $sheet = $doc->setActiveSheetIndexByName('SEM C 4');

                $sheet->setCellValue('A'.$i,$model->name_model);
                $sheet->setCellValue('B'.$i,$model->part);
                $sheet->setCellValue('C'.$i,$model->part_number);
                $sheet->setCellValue('D'.$i,$count);
                $sheet->setCellValue('E'.$i,$max);
                $sheet->setCellValue('F'.$i,$min);
                $sheet->setCellValue('G'.$i,$mean);
                $sheet->setCellValue('H'.$i,$std);
                $sheet->setCellValue('I'.$i, date('Y-m-d', strtotime($getfirstdate->save_date)));
                $sheet->setCellValue('J'.$i, date('Y-m-d', strtotime($getlastdate->save_date)));
                // $sheet->setCellValue('P'.$i,$mean6);
                $sheet->setCellValue('P'.$i,$nt_model_spec->class4);

                
                $maxdata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdate)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->max('sem_class5');
                $max = number_format($maxdata, 2, '.', '');
               
                $mindata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdate)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->min('sem_class5');
                $min = number_format($mindata, 2, '.', '');

                $meandata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdate)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->avg('sem_class5');
                $mean = number_format($meandata, 2, '.', '');
   
                $stddata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdate)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid']);
                $stdget = $stddata->select(DB::raw('STDDEV_SAMP(nt_data_sem.sem_class5) as std'))->first();
                $std = number_format($stdget->std, 2, '.', '');   
                $mean6data = $meandata + (6*$stdget->std);
                $mean6 = number_format($mean6data, 2, '.', '');

                // $sheet = $doc->setActiveSheetIndex(8);
                $sheet = $doc->setActiveSheetIndexByName('SEM C 5');

                $sheet->setCellValue('A'.$i,$model->name_model);
                $sheet->setCellValue('B'.$i,$model->part);
                $sheet->setCellValue('C'.$i,$model->part_number);
                $sheet->setCellValue('D'.$i,$count);
                $sheet->setCellValue('E'.$i,$max);
                $sheet->setCellValue('F'.$i,$min);
                $sheet->setCellValue('G'.$i,$mean);
                $sheet->setCellValue('H'.$i,$std);
               $sheet->setCellValue('I'.$i, date('Y-m-d', strtotime($getfirstdate->save_date)));
                $sheet->setCellValue('J'.$i, date('Y-m-d', strtotime($getlastdate->save_date)));
                // $sheet->setCellValue('P'.$i,$mean6);
                $sheet->setCellValue('P'.$i,$nt_model_spec->class5);


                $maxdata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdate)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->max('sem_class5_1');
                $max = number_format($maxdata, 2, '.', '');
               
                $mindata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdate)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->min('sem_class5_1');
                $min = number_format($mindata, 2, '.', '');

                $meandata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdate)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->avg('sem_class5_1');
                $mean = number_format($meandata, 2, '.', '');
   
                $stddata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdate)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid']);
                $stdget = $stddata->select(DB::raw('STDDEV_SAMP(nt_data_sem.sem_class5_1) as std'))->first();
                $std = number_format($stdget->std, 2, '.', '');   
                $mean6data = $meandata + (6*$stdget->std);
                $mean6 = number_format($mean6data, 2, '.', '');

                // $sheet = $doc->setActiveSheetIndex(9);
                $sheet = $doc->setActiveSheetIndexByName('SEM C 5.1');

                $sheet->setCellValue('A'.$i,$model->name_model);
                $sheet->setCellValue('B'.$i,$model->part);
                $sheet->setCellValue('C'.$i,$model->part_number);
                $sheet->setCellValue('D'.$i,$count);
                $sheet->setCellValue('E'.$i,$max);
                $sheet->setCellValue('F'.$i,$min);
                $sheet->setCellValue('G'.$i,$mean);
                $sheet->setCellValue('H'.$i,$std);
                $sheet->setCellValue('I'.$i, date('Y-m-d', strtotime($getfirstdate->save_date)));
                $sheet->setCellValue('J'.$i, date('Y-m-d', strtotime($getlastdate->save_date)));
                // $sheet->setCellValue('P'.$i,$mean6);
                $sheet->setCellValue('P'.$i,$nt_model_spec->class5_1);
                
                $maxdata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdate)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->max('sem_class6');
                $max = number_format($maxdata, 2, '.', '');
               
                $mindata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdate)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->min('sem_class6');
                $min = number_format($mindata, 2, '.', '');

                $meandata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdate)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->avg('sem_class6');
                $mean = number_format($meandata, 2, '.', '');
   
                $stddata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdate)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid']);
                $stdget = $stddata->select(DB::raw('STDDEV_SAMP(nt_data_sem.sem_class6) as std'))->first();
                $std = number_format($stdget->std, 2, '.', '');   
                $mean6data = $meandata + (6*$stdget->std);
                $mean6 = number_format($mean6data, 2, '.', '');

                // $sheet = $doc->setActiveSheetIndex(10);
                $sheet = $doc->setActiveSheetIndexByName('SEM C 6');

                $sheet->setCellValue('A'.$i,$model->name_model);
                $sheet->setCellValue('B'.$i,$model->part);
                $sheet->setCellValue('C'.$i,$model->part_number);
                $sheet->setCellValue('D'.$i,$count);
                $sheet->setCellValue('E'.$i,$max);
                $sheet->setCellValue('F'.$i,$min);
                $sheet->setCellValue('G'.$i,$mean);
                $sheet->setCellValue('H'.$i,$std);
                $sheet->setCellValue('I'.$i, date('Y-m-d', strtotime($getfirstdate->save_date)));
                $sheet->setCellValue('J'.$i, date('Y-m-d', strtotime($getlastdate->save_date)));
                // $sheet->setCellValue('P'.$i,$mean6);
                $sheet->setCellValue('P'.$i,$nt_model_spec->class6);

                $i++;
                
            }
            
        })->setFilename($namefile)->store('xlsx', storage_path('NHK-File/report/calculation'));

        return response()->json(['success' =>  1,'filename' => $namefile.'.xlsx']);

    }

    public function iccalculationexport(Request $request)	
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
                    $modelid1 = $model1->id_model;
                    $modelid2 = $model2->id_model;
                    $datestart1 = date('Y-m-d 00:00:00', strtotime($request->input('datestart'.$model1->id_model)));
                    $datestart2= date('Y-m-d 00:00:00', strtotime($request->input('datestart'.$model2->id_model)));
                    // $dateend1 = date('Y-m-d 00:00:00', strtotime($request->input('dateend'.$model1->id_model). ' + 1 day'));
                    // $dateend2 = date('Y-m-d 00:00:00', strtotime($request->input('dateend'.$model2->id_model). ' + 1 day'));
                    $dateend1 = date('Y-m-d 00:00:00', strtotime($request->input('dateend'.$model1->id_model)));
                    $dateend2 = date('Y-m-d 00:00:00', strtotime($request->input('dateend'.$model2->id_model)));
                    $dataexport = [];
                    $newdata =  array (
                        'modelid' => $modelid1,
                        'datestart' => $datestart1,
                        'dateend' => $dateend1,
                    );
                    array_push($dataexport,$newdata);
                    $newdata =  array (
                        'modelid' => $modelid2,
                        'datestart' => $datestart2,
                        'dateend' => $dateend2,
                    );
                    array_push($dataexport,$newdata);
                
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
        $namefile = 'Calculation_IC_'.date("YmdHis");
        $file = storage_path('NHK-File/template/report/template_calculation2.xlsx');

        Excel::load($file, function ($doc)use($dataexport,$request) {
           
            $i=5;
            $chklpccount = 0;
            $chksemcount = 0;
            $chkiccount = 0;
            $chkgcmscount = 0;
            $chkftircount = 0;
            foreach ($dataexport as $item) {  

                $model = DB::table('nt_model')
                ->join('nt_model_spec','nt_model_spec.id_model','=','nt_model.id_model')
                ->where('nt_model.id_model', $item['modelid'])
                ->where('nt_model_spec.id_model_type', 1)
                ->first();

                $nowstartdate = $item['datestart'];
                //---------- Use Count data --------------
                if($request->numcountdata > 0){
                    $getic = DB::table('nt_data_ic')
                    ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ic.id_createlab')
                    ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                    ->where('nt_oqa_lab.id_model',$item['modelid'])
                    ->orderBy('nt_oqa_lab.save_date','DESC')
                    ->take($request->numcountdata)
                    ->get();

                    foreach ($getic as $value) {
                        $nowstartdate = $value->save_date;
                    }
                }
                //---------- Use Count data --------------

                
                $count = DB::table('nt_datacf_ic')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_datacf_ic.id_createlab')
                ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdate)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])
                ->where('nt_oqa_lab.ic_confirm_st',1)->count();

                $chkiccount = $chkiccount + $count;

                $maxdata = DB::table('nt_datacf_ic')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_datacf_ic.id_createlab')
                ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdate)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])
                ->where('nt_oqa_lab.ic_confirm_st',1)->max('ic_anion_up');

                $max = number_format($maxdata, 5, '.', '');

                $mindata = DB::table('nt_datacf_ic')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_datacf_ic.id_createlab')
                ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdate)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])
                ->where('nt_oqa_lab.ic_confirm_st',1)->min('ic_anion_up');

                $min = number_format($mindata, 5, '.', '');

                $meandata = DB::table('nt_datacf_ic')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_datacf_ic.id_createlab')
                ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdate)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])
                ->where('nt_oqa_lab.ic_confirm_st',1)->avg('ic_anion_up');

                $mean = number_format($meandata, 5, '.', '');

                $stddata = DB::table('nt_datacf_ic')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_datacf_ic.id_createlab')
                ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdate)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])
                ->where('nt_oqa_lab.ic_confirm_st',1);

                $stdget = $stddata->select(DB::raw('STDDEV_SAMP(nt_datacf_ic.ic_anion_up) as std'))->first();
                $std = number_format( $stdget->std, 5, '.', '');  

                $mean6data = $meandata + (6*$stdget->std);
                $mean6 = number_format($mean6data, 5, '.', '');


                $getfirstdate = DB::table('nt_datacf_ic')
                ->select('nt_oqa_lab.save_date')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_datacf_ic.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdate)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])
                ->orderBy('nt_oqa_lab.save_date','ASC')
                ->first(); 
                $getlastdate = DB::table('nt_datacf_ic')
                ->select('nt_oqa_lab.save_date')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_datacf_ic.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdate)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])
                ->orderBy('nt_oqa_lab.save_date','DESC')
                ->first();  
 

                $datacal_ic = $this->getValue_calculation_ic($item['modelid'],$nowstartdate,$item['dateend']);
                // $sheet = $doc->setActiveSheetIndex(11);
                $sheet = $doc->setActiveSheetIndexByName('Anion');

                $sheet->setCellValue('A'.$i,$model->name_model);
                $sheet->setCellValue('B'.$i,$model->part);
                $sheet->setCellValue('C'.$i,$model->part_number);
                // $sheet->setCellValue('D'.$i,$count);
                // $sheet->setCellValue('E'.$i,$max);
                // $sheet->setCellValue('F'.$i,$min);
                // $sheet->setCellValue('G'.$i,$mean);
                // $sheet->setCellValue('H'.$i,$std);
                $sheet->setCellValue('D'.$i,$datacal_ic['count']);
                $sheet->setCellValue('E'.$i,$datacal_ic['max']);
                $sheet->setCellValue('F'.$i,$datacal_ic['min']);
                $sheet->setCellValue('G'.$i,$datacal_ic['mean']);
                $sheet->setCellValue('H'.$i,$datacal_ic['sigma']);
                $sheet->setCellValue('I'.$i, date('Y-m-d', strtotime($getfirstdate->save_date)));
                $sheet->setCellValue('J'.$i, date('Y-m-d', strtotime($getlastdate->save_date)));
                $sheet->setCellValue('P'.$i,$mean6);

          
                $maxdata = DB::table('nt_data_ic')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ic.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdate)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->max('ic_sulphate');
                $max = number_format($maxdata, 5, '.', '');
              
                $mindata = DB::table('nt_data_ic')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ic.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdate)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->min('ic_sulphate');
                $min =number_format($mindata, 5, '.', '');

                $meandata = DB::table('nt_data_ic')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ic.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdate)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->avg('ic_sulphate');
                $mean = number_format($meandata, 5, '.', '');
 
                $stddata = DB::table('nt_data_ic')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ic.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdate)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid']);
                $stdget = $stddata->select(DB::raw('STDDEV_SAMP(nt_data_ic.ic_sulphate) as std'))->first();
                $std = number_format($stdget->std, 5, '.', '');   
                $mean6data = $meandata + (6*$stdget->std);
                $mean6 = number_format($mean6data, 5, '.', '');

                // $sheet = $doc->setActiveSheetIndex(12);
                $sheet = $doc->setActiveSheetIndexByName('Sulphate');

                $sheet->setCellValue('A'.$i,$model->name_model);
                $sheet->setCellValue('B'.$i,$model->part);
                $sheet->setCellValue('C'.$i,$model->part_number);
                $sheet->setCellValue('D'.$i,$count);
                $sheet->setCellValue('E'.$i,$max);
                $sheet->setCellValue('F'.$i,$min);
                $sheet->setCellValue('G'.$i,$mean);
                $sheet->setCellValue('H'.$i,$std);
                $sheet->setCellValue('I'.$i, date('Y-m-d', strtotime($getfirstdate->save_date)));
                $sheet->setCellValue('J'.$i, date('Y-m-d', strtotime($getlastdate->save_date)));
                $sheet->setCellValue('P'.$i,$mean6);


                
                $maxdata = DB::table('nt_data_ic')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ic.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdate)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->max('ic_cation_up');
                $max = number_format($maxdata, 5, '.', '');
                $mindata = DB::table('nt_data_ic')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ic.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdate)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->min('ic_cation_up');
                $min = number_format($mindata, 5, '.', '');

                $meandata = DB::table('nt_data_ic')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ic.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdate)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->avg('ic_cation_up');
                $mean = number_format($meandata, 5, '.', '');
   
                $stddata = DB::table('nt_data_ic')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ic.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdate)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid']);
                $stdget = $stddata->select(DB::raw('STDDEV_SAMP(nt_data_ic.ic_cation_up) as std'))->first();
                $std = number_format($stdget->std, 5, '.', '');  
                $mean6data = $meandata + (6*$stdget->std);
                $mean6 = number_format($mean6data, 5, '.', '');

                // $sheet = $doc->setActiveSheetIndex(13);
                $sheet = $doc->setActiveSheetIndexByName('Cation');

                $sheet->setCellValue('A'.$i,$model->name_model);
                $sheet->setCellValue('B'.$i,$model->part);
                $sheet->setCellValue('C'.$i,$model->part_number);
                $sheet->setCellValue('D'.$i,$count);
                $sheet->setCellValue('E'.$i,$max);
                $sheet->setCellValue('F'.$i,$min);
                $sheet->setCellValue('G'.$i,$mean);
                $sheet->setCellValue('H'.$i,$std);
                $sheet->setCellValue('I'.$i, date('Y-m-d', strtotime($getfirstdate->save_date)));
                $sheet->setCellValue('J'.$i, date('Y-m-d', strtotime($getlastdate->save_date)));
                $sheet->setCellValue('P'.$i,$mean6);

                $i++;
                
            }
           
            $doc->setActiveSheetIndexByName('ALPC');
            $sheetlpc0 = $doc->getActiveSheetIndex();
            $doc->removeSheetByIndex($sheetlpc0);
       
            $doc->setActiveSheetIndexByName('SEM C 1');
            $sheetsem1 = $doc->getActiveSheetIndex();
            $doc->removeSheetByIndex($sheetsem1);

            $doc->setActiveSheetIndexByName('SEM C 2');
            $sheetsem2 = $doc->getActiveSheetIndex();
            $doc->removeSheetByIndex($sheetsem2);

            $doc->setActiveSheetIndexByName('SEM C 3');
            $sheetsem3 = $doc->getActiveSheetIndex();
            $doc->removeSheetByIndex($sheetsem3);

            $doc->setActiveSheetIndexByName('SEM C 3.1');
            $sheetsem4 = $doc->getActiveSheetIndex();
            $doc->removeSheetByIndex($sheetsem4);

            $doc->setActiveSheetIndexByName('SEM C 3.2');
            $sheetsem5 = $doc->getActiveSheetIndex();
            $doc->removeSheetByIndex($sheetsem5);

            $doc->setActiveSheetIndexByName('SEM C 3.3');
            $sheetsem6 = $doc->getActiveSheetIndex();
            $doc->removeSheetByIndex($sheetsem6);

            $doc->setActiveSheetIndexByName('SEM C 4');
            $sheetsem7 = $doc->getActiveSheetIndex();
            $doc->removeSheetByIndex($sheetsem7);

            $doc->setActiveSheetIndexByName('SEM C 5');
            $sheetsem8 = $doc->getActiveSheetIndex();
            $doc->removeSheetByIndex($sheetsem8);

            $doc->setActiveSheetIndexByName('SEM C 5.1');
            $sheetsem9 = $doc->getActiveSheetIndex();
            $doc->removeSheetByIndex($sheetsem9);

            $doc->setActiveSheetIndexByName('SEM C 6');
            $sheetsem10 = $doc->getActiveSheetIndex();
            $doc->removeSheetByIndex($sheetsem10);
           
          
            $doc->setActiveSheetIndexByName('Outgassing');
            $sheetgcms14 = $doc->getActiveSheetIndex();
            $doc->removeSheetByIndex($sheetgcms14);

            $doc->setActiveSheetIndexByName('Low MW');
            $sheetgcms15 = $doc->getActiveSheetIndex();
            $doc->removeSheetByIndex($sheetgcms15);

            $doc->setActiveSheetIndexByName('High MW');
            $sheetgcms16 = $doc->getActiveSheetIndex();
            $doc->removeSheetByIndex($sheetgcms16);

            $doc->setActiveSheetIndexByName('Smear');
            $sheetgcms17 = $doc->getActiveSheetIndex();
            $doc->removeSheetByIndex($sheetgcms17);
          
            $doc->setActiveSheetIndexByName('FTIR');
            $sheetftir18 = $doc->getActiveSheetIndex(0);
            $doc->removeSheetByIndex($sheetftir18);
            

        })->setFilename($namefile)->store('xlsx', storage_path('NHK-File/report/calculation'));

        return response()->json(['success' =>  1,'filename' => $namefile.'.xlsx']);


    }

    public function gcmscalculationexport(Request $request)	
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
                    $modelid1 = $model1->id_model;
                    $modelid2 = $model2->id_model;
                    $datestart1 = date('Y-m-d 00:00:00', strtotime($request->input('datestart'.$model1->id_model)));
                    $datestart2= date('Y-m-d 00:00:00', strtotime($request->input('datestart'.$model2->id_model)));
                    $dateend1 = date('Y-m-d 00:00:00', strtotime($request->input('dateend'.$model1->id_model). ' + 1 day'));
                    $dateend2 = date('Y-m-d 00:00:00', strtotime($request->input('dateend'.$model2->id_model). ' + 1 day'));
                    $dataexport = [];
                    $newdata =  array (
                        'modelid' => $modelid1,
                        'datestart' => $datestart1,
                        'dateend' => $dateend1,
                    );
                    array_push($dataexport,$newdata);
                    $newdata =  array (
                        'modelid' => $modelid2,
                        'datestart' => $datestart2,
                        'dateend' => $dateend2,
                    );
                    array_push($dataexport,$newdata);
                
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
        $namefile = 'Calculation_GCMS_'.date("YmdHis");
        // $file = storage_path('NHK-File/template/report/template_calculation.xlsx');

        $file = storage_path('NHK-File/template/report/template_calculation2.xlsx');
   
        Excel::load($file, function ($doc)use($dataexport,$request) {
           
            $i=5;
            $chklpccount = 0;
            $chksemcount = 0;
            $chkiccount = 0;
            $chkgcmscount = 0;
            $chkftircount = 0;
            foreach ($dataexport as $item) {  

                $model = DB::table('nt_model')
                ->join('nt_model_spec','nt_model_spec.id_model','=','nt_model.id_model')
                ->where('nt_model.id_model', $item['modelid'])
                ->where('nt_model_spec.id_model_type', 1)
                ->first();

                $nt_model_spec = DB::table('nt_model_spec')
                ->join('nt_model_spec_type', 'nt_model_spec.id_model_type', '=', 'nt_model_spec_type.id_model_type')
                ->where('nt_model_spec.id_model', $item['modelid'])
                ->where('nt_model_spec.id_model_type', 1)
                ->first();

                $nowstartdate = $item['datestart'];
                //---------- Use Count data --------------
                if($request->numcountdata > 0){
                    $getic = DB::table('nt_data_gcms')
                    ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
                    ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                    ->where('nt_oqa_lab.id_model',$item['modelid'])
                    ->orderBy('nt_oqa_lab.save_date','DESC')
                    ->take($request->numcountdata)
                    ->get();

                    foreach ($getic as $value) {
                        $nowstartdate = $value->save_date;
                    }
                }
                //---------- Use Count data --------------
                
                
                $count = DB::table('nt_data_gcms')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdate)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->count();
                $chkgcmscount = $chkgcmscount + $count;
                $maxdata = DB::table('nt_data_gcms')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdate)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->max('gcms_outgassing');
                $max = number_format($maxdata, 2, '.', '');
               
                $mindata = DB::table('nt_data_gcms')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdate)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->min('gcms_outgassing');
                $min = number_format($mindata, 2, '.', '');

                $meandata = DB::table('nt_data_gcms')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdate)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->avg('gcms_outgassing');
                $mean = number_format($meandata, 2, '.', '');
   
                $stddata = DB::table('nt_data_gcms')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdate)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid']);
                $stdget = $stddata->select(DB::raw('STDDEV_SAMP(nt_data_gcms.gcms_outgassing) as std'))->first();
                $std = number_format($stdget->std, 2, '.', '');   
                $mean6data = $meandata + (6*$stdget->std);
                $mean6 = number_format($mean6data, 2, '.', '');

                $getfirstdate = DB::table('nt_data_gcms')
                ->select('nt_oqa_lab.save_date')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdate)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])
                ->orderBy('nt_oqa_lab.save_date','ASC')
                ->first(); 
                $getlastdate = DB::table('nt_data_gcms')
                ->select('nt_oqa_lab.save_date')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdate)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])
                ->orderBy('nt_oqa_lab.save_date','DESC')
                ->first();  
 

                $sheet = $doc->setActiveSheetIndex(14);

                $sheet->setCellValue('A'.$i,$model->name_model);
                $sheet->setCellValue('B'.$i,$model->part);
                $sheet->setCellValue('C'.$i,$model->part_number);
                $sheet->setCellValue('D'.$i,$count);
                $sheet->setCellValue('E'.$i,$max);
                $sheet->setCellValue('F'.$i,$min);
                $sheet->setCellValue('G'.$i,$mean);
                $sheet->setCellValue('H'.$i,$std);
                $sheet->setCellValue('I'.$i, date('Y-m-d', strtotime($getfirstdate->save_date)));
                $sheet->setCellValue('J'.$i, date('Y-m-d', strtotime($getlastdate->save_date)));
                // $sheet->setCellValue('P'.$i,$mean6);
                $sheet->setCellValue('P'.$i, $nt_model_spec->outgassing );

                
                $maxdata = DB::table('nt_data_gcms')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdate)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->max('gcms_low_mw');
                $max = number_format($maxdata, 2, '.', '');
               
                $mindata = DB::table('nt_data_gcms')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdate)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->min('gcms_low_mw');
                $min = number_format($mindata, 2, '.', '');

                $meandata = DB::table('nt_data_gcms')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdate)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->avg('gcms_low_mw');
                $mean = number_format($meandata, 2, '.', '');
   
                $stddata = DB::table('nt_data_gcms')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdate)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid']);
                $stdget = $stddata->select(DB::raw('STDDEV_SAMP(nt_data_gcms.gcms_low_mw) as std'))->first();
                $std = number_format($stdget->std, 2, '.', '');   
                $mean6data = $meandata + (6*$stdget->std);
                $mean6 = number_format($mean6data, 2, '.', '');

                $sheet = $doc->setActiveSheetIndex(15);

                $sheet->setCellValue('A'.$i,$model->name_model);
                $sheet->setCellValue('B'.$i,$model->part);
                $sheet->setCellValue('C'.$i,$model->part_number);
                $sheet->setCellValue('D'.$i,$count);
                $sheet->setCellValue('E'.$i,$max);
                $sheet->setCellValue('F'.$i,$min);
                $sheet->setCellValue('G'.$i,$mean);
                $sheet->setCellValue('H'.$i,$std);
                $sheet->setCellValue('I'.$i, date('Y-m-d', strtotime($getfirstdate->save_date)));
                $sheet->setCellValue('J'.$i, date('Y-m-d', strtotime($getlastdate->save_date)));
                // $sheet->setCellValue('P'.$i,$mean6);
                $sheet->setCellValue('P'.$i, $nt_model_spec->low_MW );

                
                $maxdata = DB::table('nt_data_gcms')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdate)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->max('gcms_high_mw');
                $max = number_format($maxdata, 2, '.', '');
               
                $mindata = DB::table('nt_data_gcms')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdate)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->min('gcms_high_mw');
                $min = number_format($mindata, 2, '.', '');

                $meandata = DB::table('nt_data_gcms')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdate)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->avg('gcms_high_mw');
                $mean = number_format($meandata, 2, '.', '');
   
                $stddata = DB::table('nt_data_gcms')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdate)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid']);
                $stdget = $stddata->select(DB::raw('STDDEV_SAMP(nt_data_gcms.gcms_high_mw) as std'))->first();
                $std = number_format($stdget->std, 2, '.', '');   
                $mean6data = $meandata + (6*$stdget->std);
                $mean6 = number_format($mean6data, 2, '.', '');

                $sheet = $doc->setActiveSheetIndex(16);

                $sheet->setCellValue('A'.$i,$model->name_model);
                $sheet->setCellValue('B'.$i,$model->part);
                $sheet->setCellValue('C'.$i,$model->part_number);
                $sheet->setCellValue('D'.$i,$count);
                $sheet->setCellValue('E'.$i,$max);
                $sheet->setCellValue('F'.$i,$min);
                $sheet->setCellValue('G'.$i,$mean);
                $sheet->setCellValue('H'.$i,$std);
                $sheet->setCellValue('I'.$i, date('Y-m-d', strtotime($getfirstdate->save_date)));
                $sheet->setCellValue('J'.$i, date('Y-m-d', strtotime($getlastdate->save_date)));
                // $sheet->setCellValue('P'.$i,$mean6);
                $sheet->setCellValue('P'.$i, $nt_model_spec->high_MW );

                
                $maxdata = DB::table('nt_data_gcms')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdate)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->max('gcms_smear');
                $max = number_format($maxdata, 2, '.', '');
               
                $mindata = DB::table('nt_data_gcms')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdate)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->min('gcms_smear');
                $min = number_format($mindata, 2, '.', '');

                $meandata = DB::table('nt_data_gcms')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdate)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->avg('gcms_smear');
                $mean = number_format($meandata, 2, '.', '');
   
                $stddata = DB::table('nt_data_gcms')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdate)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid']);
                $stdget = $stddata->select(DB::raw('STDDEV_SAMP(nt_data_gcms.gcms_smear) as std'))->first();
                $std = number_format($stdget->std, 2, '.', '');   
                $mean6data = $meandata + (6*$stdget->std);
                $mean6 = number_format($mean6data, 2, '.', '');

                $sheet = $doc->setActiveSheetIndex(17);

                $sheet->setCellValue('A'.$i,$model->name_model);
                $sheet->setCellValue('B'.$i,$model->part);
                $sheet->setCellValue('C'.$i,$model->part_number);
                $sheet->setCellValue('D'.$i,$count);
                $sheet->setCellValue('E'.$i,$max);
                $sheet->setCellValue('F'.$i,$min);
                $sheet->setCellValue('G'.$i,$mean);
                $sheet->setCellValue('H'.$i,$std);
                $sheet->setCellValue('I'.$i, date('Y-m-d', strtotime($getfirstdate->save_date)));
                $sheet->setCellValue('J'.$i, date('Y-m-d', strtotime($getlastdate->save_date)));
                // $sheet->setCellValue('P'.$i,$mean6);
                $sheet->setCellValue('P'.$i, $nt_model_spec->alkylacrylate );

                $i++;
                
            }
            
            $doc->setActiveSheetIndexByName('ALPC');
            $sheetlpc0 = $doc->getActiveSheetIndex();
            $doc->removeSheetByIndex($sheetlpc0);
          
            $doc->setActiveSheetIndexByName('SEM C 1');
            $sheetsem1 = $doc->getActiveSheetIndex();
            $doc->removeSheetByIndex($sheetsem1);

            $doc->setActiveSheetIndexByName('SEM C 2');
            $sheetsem2 = $doc->getActiveSheetIndex();
            $doc->removeSheetByIndex($sheetsem2);

            $doc->setActiveSheetIndexByName('SEM C 3');
            $sheetsem3 = $doc->getActiveSheetIndex();
            $doc->removeSheetByIndex($sheetsem3);

            $doc->setActiveSheetIndexByName('SEM C 3.1');
            $sheetsem4 = $doc->getActiveSheetIndex();
            $doc->removeSheetByIndex($sheetsem4);

            $doc->setActiveSheetIndexByName('SEM C 3.2');
            $sheetsem5 = $doc->getActiveSheetIndex();
            $doc->removeSheetByIndex($sheetsem5);

            $doc->setActiveSheetIndexByName('SEM C 3.3');
            $sheetsem6 = $doc->getActiveSheetIndex();
            $doc->removeSheetByIndex($sheetsem6);

            $doc->setActiveSheetIndexByName('SEM C 4');
            $sheetsem7 = $doc->getActiveSheetIndex();
            $doc->removeSheetByIndex($sheetsem7);

            $doc->setActiveSheetIndexByName('SEM C 5');
            $sheetsem8 = $doc->getActiveSheetIndex();
            $doc->removeSheetByIndex($sheetsem8);

            $doc->setActiveSheetIndexByName('SEM C 5.1');
            $sheetsem9 = $doc->getActiveSheetIndex();
            $doc->removeSheetByIndex($sheetsem9);

            $doc->setActiveSheetIndexByName('SEM C 6');
            $sheetsem10 = $doc->getActiveSheetIndex();
            $doc->removeSheetByIndex($sheetsem10);
           
            $doc->setActiveSheetIndexByName('Anion');
            $sheetic11 = $doc->getActiveSheetIndex();
            $doc->removeSheetByIndex($sheetic11);

            $doc->setActiveSheetIndexByName('Sulphate');
            $sheetic12 = $doc->getActiveSheetIndex();
            $doc->removeSheetByIndex($sheetic12);

            $doc->setActiveSheetIndexByName('Cation');
            $sheetic13 = $doc->getActiveSheetIndex();
            $doc->removeSheetByIndex($sheetic13);
          
   
            $doc->setActiveSheetIndexByName('FTIR');
            $sheetftir18 = $doc->getActiveSheetIndex(0);
            $doc->removeSheetByIndex($sheetftir18);
            

        })->setFilename($namefile)->store('xlsx', storage_path('NHK-File/report/calculation'));
               
        return response()->json(['success' =>  1,'filename' => $namefile.'.xlsx']);


    }

    public function ftircalculationexport(Request $request)	
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
                    $modelid1 = $model1->id_model;
                    $modelid2 = $model2->id_model;
                    $datestart1 = date('Y-m-d 00:00:00', strtotime($request->input('datestart'.$model1->id_model)));
                    $datestart2= date('Y-m-d 00:00:00', strtotime($request->input('datestart'.$model2->id_model)));
                    $dateend1 = date('Y-m-d 00:00:00', strtotime($request->input('dateend'.$model1->id_model). ' + 1 day'));
                    $dateend2 = date('Y-m-d 00:00:00', strtotime($request->input('dateend'.$model2->id_model). ' + 1 day'));
                    $dataexport = [];
                    $newdata =  array (
                        'modelid' => $modelid1,
                        'datestart' => $datestart1,
                        'dateend' => $dateend1,
                    );
                    array_push($dataexport,$newdata);
                    $newdata =  array (
                        'modelid' => $modelid2,
                        'datestart' => $datestart2,
                        'dateend' => $dateend2,
                    );
                    array_push($dataexport,$newdata);
                
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
        $namefile = 'Calculation_FTIR_'.date("YmdHis");
        // $file = storage_path('NHK-File/template/report/template_calculation.xlsx');

        $file = storage_path('NHK-File/template/report/template_calculation2.xlsx');

        Excel::load($file, function ($doc)use($dataexport,$request) {
           
            $i=5;
            $chklpccount = 0;
            $chksemcount = 0;
            $chkiccount = 0;
            $chkgcmscount = 0;
            $chkftircount = 0;
            foreach ($dataexport as $item) {  

                $model = DB::table('nt_model')
                ->join('nt_model_spec','nt_model_spec.id_model','=','nt_model.id_model')
                ->where('nt_model.id_model', $item['modelid'])
                ->where('nt_model_spec.id_model_type', 1)
                ->first();

                $nt_model_spec = DB::table('nt_model_spec')
                ->join('nt_model_spec_type', 'nt_model_spec.id_model_type', '=', 'nt_model_spec_type.id_model_type')
                ->where('nt_model_spec.id_model', $item['modelid'])
                ->where('nt_model_spec.id_model_type', 1)
                ->first();

                $nowstartdate = $item['datestart'];
                //---------- Use Count data --------------
                if($request->numcountdata > 0){
                    $getic = DB::table('nt_data_ftir')
                    ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ftir.id_createlab')
                    ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                    ->where('nt_oqa_lab.id_model',$item['modelid'])
                    ->orderBy('nt_oqa_lab.save_date','DESC')
                    ->take($request->numcountdata)
                    ->get();

                    foreach ($getic as $value) {
                        $nowstartdate = $value->save_date;
                    }
                }
                //---------- Use Count data --------------
                
                $count = DB::table('nt_data_ftir')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ftir.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdate)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->count();
                
                $chkftircount = $chkftircount + $count;
                $maxdata = DB::table('nt_data_ftir')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ftir.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdate)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->max('ftir_silicone');
                $max = number_format($maxdata, 2, '.', '');
               
                $mindata = DB::table('nt_data_ftir')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ftir.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdate)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->min('ftir_silicone');
                $min = number_format($mindata, 2, '.', '');

                $meandata = DB::table('nt_data_ftir')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ftir.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdate)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->avg('ftir_silicone');
                $mean = number_format($meandata, 2, '.', '');
   
                $stddata = DB::table('nt_data_ftir')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ftir.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdate)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid']);
                $stdget = $stddata->select(DB::raw('STDDEV_SAMP(nt_data_ftir.ftir_silicone) as std'))->first();
                $std = number_format($stdget->std, 2, '.', '');   
                $mean6data = $meandata + (6*$stdget->std);
                $mean6 = number_format($mean6data, 2, '.', '');

                $getfirstdate = DB::table('nt_data_ftir')
                ->select('nt_oqa_lab.save_date')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ftir.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdate)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])
                ->orderBy('nt_oqa_lab.save_date','ASC')
                ->first(); 
                $getlastdate = DB::table('nt_data_ftir')
                ->select('nt_oqa_lab.save_date')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ftir.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdate)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])
                ->orderBy('nt_oqa_lab.save_date','DESC')
                ->first();  
   
                $sheet = $doc->setActiveSheetIndexByName('FTIR');

                $sheet->setCellValue('A'.$i,$model->name_model);
                $sheet->setCellValue('B'.$i,$model->part);
                $sheet->setCellValue('C'.$i,$model->part_number);
                $sheet->setCellValue('D'.$i,$count);
                $sheet->setCellValue('E'.$i,$max);
                $sheet->setCellValue('F'.$i,$min);
                $sheet->setCellValue('G'.$i,$mean);
                $sheet->setCellValue('H'.$i,$std);
                $sheet->setCellValue('I'.$i, date('Y-m-d', strtotime($getfirstdate->save_date)));
                $sheet->setCellValue('J'.$i, date('Y-m-d', strtotime($getlastdate->save_date)));
                // $sheet->setCellValue('P'.$i,$mean6);
                $sheet->setCellValue('P'.$i,$nt_model_spec->ftir);

                $i++;
                
            }
            
            
            $doc->setActiveSheetIndexByName('ALPC');
            $sheetlpc0 = $doc->getActiveSheetIndex();
            $doc->removeSheetByIndex($sheetlpc0);
       
           $doc->setActiveSheetIndexByName('SEM C 1');
           $sheetsem1 = $doc->getActiveSheetIndex();
           $doc->removeSheetByIndex($sheetsem1);

           $doc->setActiveSheetIndexByName('SEM C 2');
           $sheetsem2 = $doc->getActiveSheetIndex();
           $doc->removeSheetByIndex($sheetsem2);

           $doc->setActiveSheetIndexByName('SEM C 3');
           $sheetsem3 = $doc->getActiveSheetIndex();
           $doc->removeSheetByIndex($sheetsem3);

           $doc->setActiveSheetIndexByName('SEM C 3.1');
           $sheetsem4 = $doc->getActiveSheetIndex();
           $doc->removeSheetByIndex($sheetsem4);

           $doc->setActiveSheetIndexByName('SEM C 3.2');
           $sheetsem5 = $doc->getActiveSheetIndex();
           $doc->removeSheetByIndex($sheetsem5);

           $doc->setActiveSheetIndexByName('SEM C 3.3');
           $sheetsem6 = $doc->getActiveSheetIndex();
           $doc->removeSheetByIndex($sheetsem6);

           $doc->setActiveSheetIndexByName('SEM C 4');
           $sheetsem7 = $doc->getActiveSheetIndex();
           $doc->removeSheetByIndex($sheetsem7);

           $doc->setActiveSheetIndexByName('SEM C 5');
           $sheetsem8 = $doc->getActiveSheetIndex();
           $doc->removeSheetByIndex($sheetsem8);

           $doc->setActiveSheetIndexByName('SEM C 5.1');
           $sheetsem9 = $doc->getActiveSheetIndex();
           $doc->removeSheetByIndex($sheetsem9);

           $doc->setActiveSheetIndexByName('SEM C 6');
           $sheetsem10 = $doc->getActiveSheetIndex();
           $doc->removeSheetByIndex($sheetsem10);
         
           $doc->setActiveSheetIndexByName('Anion');
           $sheetic11 = $doc->getActiveSheetIndex();
           $doc->removeSheetByIndex($sheetic11);

           $doc->setActiveSheetIndexByName('Sulphate');
           $sheetic12 = $doc->getActiveSheetIndex();
           $doc->removeSheetByIndex($sheetic12);

           $doc->setActiveSheetIndexByName('Cation');
           $sheetic13 = $doc->getActiveSheetIndex();
           $doc->removeSheetByIndex($sheetic13);
          
           $doc->setActiveSheetIndexByName('Outgassing');
           $sheetgcms14 = $doc->getActiveSheetIndex();
           $doc->removeSheetByIndex($sheetgcms14);

           $doc->setActiveSheetIndexByName('Low MW');
           $sheetgcms15 = $doc->getActiveSheetIndex();
           $doc->removeSheetByIndex($sheetgcms15);

           $doc->setActiveSheetIndexByName('High MW');
           $sheetgcms16 = $doc->getActiveSheetIndex();
           $doc->removeSheetByIndex($sheetgcms16);

           $doc->setActiveSheetIndexByName('Smear');
           $sheetgcms17 = $doc->getActiveSheetIndex();
           $doc->removeSheetByIndex($sheetgcms17);

        })->setFilename($namefile)->store('xlsx', storage_path('NHK-File/report/calculation'));

        return response()->json(['success' =>  1,'filename' => $namefile.'.xlsx']);


    }

    public function calculationexport(Request $request)	
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
                    $modelid1 = $model1->id_model;
                    $modelid2 = $model2->id_model;
                    $datestart1 = date('Y-m-d 00:00:00', strtotime($request->input('datestart'.$model1->id_model)));
                    $datestart2= date('Y-m-d 00:00:00', strtotime($request->input('datestart'.$model2->id_model)));
                    // $dateend1 = date('Y-m-d 00:00:00', strtotime($request->input('dateend'.$model1->id_model). ' + 1 day'));
                    // $dateend2 = date('Y-m-d 00:00:00', strtotime($request->input('dateend'.$model2->id_model). ' + 1 day'));
                    $dateend1 = date('Y-m-d 00:00:00', strtotime($request->input('dateend'.$model1->id_model)));
                    $dateend2 = date('Y-m-d 00:00:00', strtotime($request->input('dateend'.$model2->id_model)));
                    $dataexport = [];
                    $newdata =  array (
                        'modelid' => $modelid1,
                        'datestart' => $datestart1,
                        'dateend' => $dateend1,
                    );
                    array_push($dataexport,$newdata);
                    $newdata =  array (
                        'modelid' => $modelid2,
                        'datestart' => $datestart2,
                        'dateend' => $dateend2,
                    );
                    array_push($dataexport,$newdata);
                
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
        $namefile = 'Calculation_ALL_tester'.date("YmdHis");
        // $file = storage_path('NHK-File/template/report/template_calculation.xlsx');

        $file = storage_path('NHK-File/template/report/template_calculation2.xlsx');
       
        Excel::load($file, function ($doc)use($dataexport,$request) {
           
            $i=5;
            $chklpccount = 0;
            $chksemcount = 0;
            $chkiccount = 0;
            $chkgcmscount = 0;
            $chkftircount = 0;
            foreach ($dataexport as $item) {  

                $model = DB::table('nt_model')
                ->join('nt_model_spec','nt_model_spec.id_model','=','nt_model.id_model')
                ->where('nt_model.id_model', $item['modelid'])
                ->where('nt_model_spec.id_model_type', 1)
                ->first();

                $nt_model_spec = DB::table('nt_model_spec')
                ->join('nt_model_spec_type', 'nt_model_spec.id_model_type', '=', 'nt_model_spec_type.id_model_type')
                ->where('nt_model_spec.id_model', $item['modelid'])
                ->where('nt_model_spec.id_model_type', 1)
                ->first(); 

                $nowstartdatelpc = $item['datestart'];
                if($request->numcountdata > 0){
                    $getlpc= DB::table('nt_data_lpc')
                    ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_lpc.id_createlab')
                    ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                    ->where('nt_oqa_lab.id_model',$item['modelid'])
                    ->orderBy('nt_oqa_lab.save_date','DESC')
                    ->take($request->numcountdata)
                    ->get();

                    foreach ($getlpc as $value) {
                        $nowstartdatelpc = $value->save_date;
                    }
                }

                $lpccount = DB::table('nt_data_lpc')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_lpc.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdatelpc)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->count();
                $chklpccount = $chklpccount +$lpccount;
                $maxdata = DB::table('nt_data_lpc')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_lpc.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdatelpc)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->max('lpc_data');
                $max = number_format($maxdata, 2, '.', '');
               
                $mindata = DB::table('nt_data_lpc')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_lpc.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdatelpc)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->min('lpc_data');
                $min = number_format($mindata, 2, '.', '');

                $meandata = DB::table('nt_data_lpc')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_lpc.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdatelpc)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->avg('lpc_data');
                $mean = number_format($meandata, 2, '.', '');
   
                $stddata = DB::table('nt_data_lpc')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_lpc.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdatelpc)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid']);
                $stdget = $stddata->select(DB::raw('STDDEV_SAMP(nt_data_lpc.lpc_data) as std'))->first();
                $std = number_format($stdget->std, 2, '.', '');   
                $mean6data = $meandata + (6*$stdget->std);
                $mean6 = number_format($mean6data, 2, '.', '');
                

                $getfirstdate = DB::table('nt_data_lpc')
                ->select('nt_oqa_lab.save_date')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_lpc.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdatelpc)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])
                ->orderBy('nt_oqa_lab.save_date','ASC')
                ->first(); 
                $getlastdate = DB::table('nt_data_lpc')
                ->select('nt_oqa_lab.save_date')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_lpc.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdatelpc)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])
                ->orderBy('nt_oqa_lab.save_date','DESC')
                ->first();  

                // $sheet = $spreadsheet->setActiveSheetIndex(0);
                $sheet = $doc->setActiveSheetIndexByName('ALPC');

                $sheet->setCellValue('A'.$i,$model->name_model);
                $sheet->setCellValue('B'.$i,$model->part);
                $sheet->setCellValue('C'.$i,$model->part_number);
                $sheet->setCellValue('D'.$i,$lpccount);
                $sheet->setCellValue('E'.$i,$max);
                $sheet->setCellValue('F'.$i,$min);
                $sheet->setCellValue('G'.$i,$mean);
                $sheet->setCellValue('H'.$i,$std); 
                if(!empty($getfirstdate)){
                    $sheet->setCellValue('I'.$i, date('Y-m-d', strtotime($getfirstdate->save_date)));
                }else{
                    $sheet->setCellValue('I'.$i,$nowstartdatelpc);
                }
                if(!empty($getlastdate)){
                    $sheet->setCellValue('J'.$i, date('Y-m-d', strtotime($getlastdate->save_date)));
                }else{
                    $sheet->setCellValue('J'.$i,$item['dateend']);
                }
                // $sheet->setCellValue('P'.$i,$mean6);
                $sheet->setCellValue('P'.$i,$nt_model_spec->alpc);


                $nowstartdatesem = $item['datestart'];
                if($request->numcountdata > 0){
                    $getic = DB::table('nt_data_sem')
                    ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                    ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                    ->where('nt_oqa_lab.id_model',$item['modelid'])
                    ->orderBy('nt_oqa_lab.save_date','DESC')
                    ->take($request->numcountdata)
                    ->get();

                    foreach ($getic as $value) {
                        $nowstartdatesem = $value->save_date;
                    }
                }

                $count = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdatesem)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->count();
                $chksemcount = $chksemcount + $count;
                $maxdata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdatesem)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->max('sem_class1');
                $max = number_format($maxdata, 2, '.', '');
               
                $mindata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdatesem)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->min('sem_class1');
                $min = number_format($mindata, 2, '.', '');

                $meandata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdatesem)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->avg('sem_class1');
                $mean = number_format($meandata, 2, '.', '');
   
                $stddata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdatesem)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid']);
                $stdget = $stddata->select(DB::raw('STDDEV_SAMP(nt_data_sem.sem_class1) as std'))->first();
                $std = number_format($stdget->std, 2, '.', '');   
                $mean6data = $meandata + (6*$stdget->std);
                $mean6 = number_format($mean6data, 2, '.', '');

                $getfirstdate = DB::table('nt_data_sem')
                ->select('nt_oqa_lab.save_date')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdatesem)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])
                ->orderBy('nt_oqa_lab.save_date','ASC')
                ->first(); 
                $getlastdate = DB::table('nt_data_sem')
                ->select('nt_oqa_lab.save_date')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdatesem)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])
                ->orderBy('nt_oqa_lab.save_date','DESC')
                ->first();  

                // $sheet = $doc->setActiveSheetIndex(1);
                $sheet = $doc->setActiveSheetIndexByName('SEM C 1');
 
                $sheet->setCellValue('A'.$i,$model->name_model);
                $sheet->setCellValue('B'.$i,$model->part);
                $sheet->setCellValue('C'.$i,$model->part_number);
                $sheet->setCellValue('D'.$i,$count);
                $sheet->setCellValue('E'.$i,$max);
                $sheet->setCellValue('F'.$i,$min);
                $sheet->setCellValue('G'.$i,$mean);
                $sheet->setCellValue('H'.$i,$std);
                if(!empty($getfirstdate)){
                    $sheet->setCellValue('I'.$i, date('Y-m-d', strtotime($getfirstdate->save_date)));
                }else{
                    $sheet->setCellValue('I'.$i,$nowstartdatesem);
                }
                if(!empty($getlastdate)){
                    $sheet->setCellValue('J'.$i, date('Y-m-d', strtotime($getlastdate->save_date)));
                }else{
                    $sheet->setCellValue('J'.$i,$item['dateend']);
                }
                // $sheet->setCellValue('P'.$i,$mean6);
                $sheet->setCellValue('P'.$i,$nt_model_spec->class1);

                
                $maxdata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdatesem)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->max('sem_class2');
                $max = number_format($maxdata, 2, '.', '');
               
                $mindata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdatesem)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->min('sem_class2');
                $min = number_format($mindata, 2, '.', '');

                $meandata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdatesem)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->avg('sem_class2');
                $mean = number_format($meandata, 2, '.', '');
   
                $stddata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdatesem)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid']);
                $stdget = $stddata->select(DB::raw('STDDEV_SAMP(nt_data_sem.sem_class2) as std'))->first();
                $std = number_format($stdget->std, 2, '.', '');   
                $mean6data = $meandata + (6*$stdget->std);
                $mean6 = number_format($mean6data, 2, '.', '');
  
                // $sheet = $doc->setActiveSheetIndex(2);
                $sheet = $doc->setActiveSheetIndexByName('SEM C 2');

                $sheet->setCellValue('A'.$i,$model->name_model);
                $sheet->setCellValue('B'.$i,$model->part);
                $sheet->setCellValue('C'.$i,$model->part_number);
                $sheet->setCellValue('D'.$i,$count);
                $sheet->setCellValue('E'.$i,$max);
                $sheet->setCellValue('F'.$i,$min);
                $sheet->setCellValue('G'.$i,$mean);
                $sheet->setCellValue('H'.$i,$std);
                if(!empty($getfirstdate)){
                    $sheet->setCellValue('I'.$i, date('Y-m-d', strtotime($getfirstdate->save_date)));
                }else{
                    $sheet->setCellValue('I'.$i,$nowstartdatesem);
                }
                if(!empty($getlastdate)){
                    $sheet->setCellValue('J'.$i, date('Y-m-d', strtotime($getlastdate->save_date)));
                }else{
                    $sheet->setCellValue('J'.$i,$item['dateend']);
                }
                // $sheet->setCellValue('P'.$i,$mean6);
                $sheet->setCellValue('P'.$i,$nt_model_spec->class2);
               
                $maxdata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdatesem)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->max('sem_class3');
                $max = number_format($maxdata, 2, '.', '');
               
                $mindata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdatesem)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->min('sem_class3');
                $min = number_format($mindata, 2, '.', '');

                $meandata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdatesem)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->avg('sem_class3');
                $mean = number_format($meandata, 2, '.', '');
   
                $stddata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdatesem)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid']);
                $stdget = $stddata->select(DB::raw('STDDEV_SAMP(nt_data_sem.sem_class3) as std'))->first();
                $std = number_format($stdget->std, 2, '.', '');   
                $mean6data = $meandata + (6*$stdget->std);
                $mean6 = number_format($mean6data, 2, '.', '');

                // $sheet = $doc->setActiveSheetIndex(3);
                $sheet = $doc->setActiveSheetIndexByName('SEM C 3');

                $sheet->setCellValue('A'.$i,$model->name_model);
                $sheet->setCellValue('B'.$i,$model->part);
                $sheet->setCellValue('C'.$i,$model->part_number);
                $sheet->setCellValue('D'.$i,$count);
                $sheet->setCellValue('E'.$i,$max);
                $sheet->setCellValue('F'.$i,$min);
                $sheet->setCellValue('G'.$i,$mean);
                $sheet->setCellValue('H'.$i,$std);
                if(!empty($getfirstdate)){
                    $sheet->setCellValue('I'.$i, date('Y-m-d', strtotime($getfirstdate->save_date)));
                }else{
                    $sheet->setCellValue('I'.$i,$nowstartdatesem);
                }
                if(!empty($getlastdate)){
                    $sheet->setCellValue('J'.$i, date('Y-m-d', strtotime($getlastdate->save_date)));
                }else{
                    $sheet->setCellValue('J'.$i,$item['dateend']);
                }
                // $sheet->setCellValue('P'.$i,$mean6);
                $sheet->setCellValue('P'.$i,$nt_model_spec->class3);

                
                $maxdata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdatesem)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->max('sem_class3_1');
                $max = number_format($maxdata, 2, '.', '');
               
                $mindata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdatesem)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->min('sem_class3_1');
                $min = number_format($mindata, 2, '.', '');

                $meandata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdatesem)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->avg('sem_class3_1');
                $mean = number_format($meandata, 2, '.', '');
   
                $stddata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdatesem)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid']);
                $stdget = $stddata->select(DB::raw('STDDEV_SAMP(nt_data_sem.sem_class3_1) as std'))->first();
                $std = number_format($stdget->std, 2, '.', '');   
                $mean6data = $meandata + (6*$stdget->std);
                $mean6 = number_format($mean6data, 2, '.', '');

                // $sheet = $doc->setActiveSheetIndex(4);
                $sheet = $doc->setActiveSheetIndexByName('SEM C 3.1');

                $sheet->setCellValue('A'.$i,$model->name_model);
                $sheet->setCellValue('B'.$i,$model->part);
                $sheet->setCellValue('C'.$i,$model->part_number);
                $sheet->setCellValue('D'.$i,$count);
                $sheet->setCellValue('E'.$i,$max);
                $sheet->setCellValue('F'.$i,$min);
                $sheet->setCellValue('G'.$i,$mean);
                $sheet->setCellValue('H'.$i,$std); 
                if(!empty($getfirstdate)){
                    $sheet->setCellValue('I'.$i, date('Y-m-d', strtotime($getfirstdate->save_date)));
                }else{
                    $sheet->setCellValue('I'.$i,$nowstartdatesem);
                }
                if(!empty($getlastdate)){
                    $sheet->setCellValue('J'.$i, date('Y-m-d', strtotime($getlastdate->save_date)));
                }else{
                    $sheet->setCellValue('J'.$i,$item['dateend']);
                }
                // $sheet->setCellValue('P'.$i,$mean6);
                $sheet->setCellValue('P'.$i,$nt_model_spec->class3_1);

                
                $maxdata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdatesem)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->max('sem_class3_2');
                $max = number_format($maxdata, 2, '.', '');
               
                $mindata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdatesem)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->min('sem_class3_2');
                $min = number_format($mindata, 2, '.', '');

                $meandata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdatesem)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->avg('sem_class3_2');
                $mean = number_format($meandata, 2, '.', '');
   
                $stddata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdatesem)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid']);
                $stdget = $stddata->select(DB::raw('STDDEV_SAMP(nt_data_sem.sem_class3_2) as std'))->first();
                $std = number_format($stdget->std, 2, '.', '');   
                $mean6data = $meandata + (6*$stdget->std);
                $mean6 = number_format($mean6data, 2, '.', '');

                // $sheet = $doc->setActiveSheetIndex(5);
                $sheet = $doc->setActiveSheetIndexByName('SEM C 3.2');

                $sheet->setCellValue('A'.$i,$model->name_model);
                $sheet->setCellValue('B'.$i,$model->part);
                $sheet->setCellValue('C'.$i,$model->part_number);
                $sheet->setCellValue('D'.$i,$count);
                $sheet->setCellValue('E'.$i,$max);
                $sheet->setCellValue('F'.$i,$min);
                $sheet->setCellValue('G'.$i,$mean);
                $sheet->setCellValue('H'.$i,$std); 
                if(!empty($getfirstdate)){
                    $sheet->setCellValue('I'.$i, date('Y-m-d', strtotime($getfirstdate->save_date)));
                }else{
                    $sheet->setCellValue('I'.$i,$nowstartdatesem);
                }
                if(!empty($getlastdate)){
                    $sheet->setCellValue('J'.$i, date('Y-m-d', strtotime($getlastdate->save_date)));
                }else{
                    $sheet->setCellValue('J'.$i,$item['dateend']);
                }
                // $sheet->setCellValue('P'.$i,$mean6);
                $sheet->setCellValue('P'.$i,$nt_model_spec->class3_2);

                
                $maxdata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdatesem)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->max('sem_class3_3');
                $max = number_format($maxdata, 2, '.', '');
               
                $mindata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdatesem)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->min('sem_class3_3');
                $min = number_format($mindata, 2, '.', '');

                $meandata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdatesem)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->avg('sem_class3_3');
                $mean = number_format($meandata, 2, '.', '');
   
                $stddata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdatesem)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid']);
                $stdget = $stddata->select(DB::raw('STDDEV_SAMP(nt_data_sem.sem_class3_3) as std'))->first();
                $std = number_format($stdget->std, 2, '.', '');   
                $mean6data = $meandata + (6*$stdget->std);
                $mean6 = number_format($mean6data, 2, '.', '');

                // $sheet = $doc->setActiveSheetIndex(6);
                $sheet = $doc->setActiveSheetIndexByName('SEM C 3.3');

                $sheet->setCellValue('A'.$i,$model->name_model);
                $sheet->setCellValue('B'.$i,$model->part);
                $sheet->setCellValue('C'.$i,$model->part_number);
                $sheet->setCellValue('D'.$i,$count);
                $sheet->setCellValue('E'.$i,$max);
                $sheet->setCellValue('F'.$i,$min);
                $sheet->setCellValue('G'.$i,$mean);
                $sheet->setCellValue('H'.$i,$std); 
                if(!empty($getfirstdate)){
                    $sheet->setCellValue('I'.$i, date('Y-m-d', strtotime($getfirstdate->save_date)));
                }else{
                    $sheet->setCellValue('I'.$i,$nowstartdatesem);
                }
                if(!empty($getlastdate)){
                    $sheet->setCellValue('J'.$i, date('Y-m-d', strtotime($getlastdate->save_date)));
                }else{
                    $sheet->setCellValue('J'.$i,$item['dateend']);
                }
                // $sheet->setCellValue('P'.$i,$mean6);
                $sheet->setCellValue('P'.$i,$nt_model_spec->class3_3);

                
                $maxdata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdatesem)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->max('sem_class4');
                $max = number_format($maxdata, 2, '.', '');
               
                $mindata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdatesem)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->min('sem_class4');
                $min = number_format($mindata, 2, '.', '');

                $meandata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdatesem)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->avg('sem_class4');
                $mean = number_format($meandata, 2, '.', '');
   
                $stddata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdatesem)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid']);
                $stdget = $stddata->select(DB::raw('STDDEV_SAMP(nt_data_sem.sem_class4) as std'))->first();
                $std = number_format($stdget->std, 2, '.', '');   
                $mean6data = $meandata + (6*$stdget->std);
                $mean6 = number_format($mean6data, 2, '.', '');

                // $sheet = $doc->setActiveSheetIndex(7);
                $sheet = $doc->setActiveSheetIndexByName('SEM C 4');

                $sheet->setCellValue('A'.$i,$model->name_model);
                $sheet->setCellValue('B'.$i,$model->part);
                $sheet->setCellValue('C'.$i,$model->part_number);
                $sheet->setCellValue('D'.$i,$count);
                $sheet->setCellValue('E'.$i,$max);
                $sheet->setCellValue('F'.$i,$min);
                $sheet->setCellValue('G'.$i,$mean);
                $sheet->setCellValue('H'.$i,$std); 
                if(!empty($getfirstdate)){
                    $sheet->setCellValue('I'.$i, date('Y-m-d', strtotime($getfirstdate->save_date)));
                }else{
                    $sheet->setCellValue('I'.$i,$nowstartdatesem);
                }
                if(!empty($getlastdate)){
                    $sheet->setCellValue('J'.$i, date('Y-m-d', strtotime($getlastdate->save_date)));
                }else{
                    $sheet->setCellValue('J'.$i,$item['dateend']);
                }
                // $sheet->setCellValue('P'.$i,$mean6);
                $sheet->setCellValue('P'.$i,$nt_model_spec->class4);

                
                $maxdata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdatesem)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->max('sem_class5');
                $max = number_format($maxdata, 2, '.', '');
               
                $mindata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdatesem)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->min('sem_class5');
                $min = number_format($mindata, 2, '.', '');

                $meandata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdatesem)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->avg('sem_class5');
                $mean = number_format($meandata, 2, '.', '');
   
                $stddata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdatesem)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid']);
                $stdget = $stddata->select(DB::raw('STDDEV_SAMP(nt_data_sem.sem_class5) as std'))->first();
                $std = number_format($stdget->std, 2, '.', '');   
                $mean6data = $meandata + (6*$stdget->std);
                $mean6 = number_format($mean6data, 2, '.', '');

                // $sheet = $doc->setActiveSheetIndex(8);
                $sheet = $doc->setActiveSheetIndexByName('SEM C 5');

                $sheet->setCellValue('A'.$i,$model->name_model);
                $sheet->setCellValue('B'.$i,$model->part);
                $sheet->setCellValue('C'.$i,$model->part_number);
                $sheet->setCellValue('D'.$i,$count);
                $sheet->setCellValue('E'.$i,$max);
                $sheet->setCellValue('F'.$i,$min);
                $sheet->setCellValue('G'.$i,$mean);
                $sheet->setCellValue('H'.$i,$std); 
                if(!empty($getfirstdate)){
                    $sheet->setCellValue('I'.$i, date('Y-m-d', strtotime($getfirstdate->save_date)));
                }else{
                    $sheet->setCellValue('I'.$i,$nowstartdatesem);
                }
                if(!empty($getlastdate)){
                    $sheet->setCellValue('J'.$i, date('Y-m-d', strtotime($getlastdate->save_date)));
                }else{
                    $sheet->setCellValue('J'.$i,$item['dateend']);
                }
                // $sheet->setCellValue('P'.$i,$mean6);
                $sheet->setCellValue('P'.$i,$nt_model_spec->class5);


                $maxdata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdatesem)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->max('sem_class5_1');
                $max = number_format($maxdata, 2, '.', '');
               
                $mindata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdatesem)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->min('sem_class5_1');
                $min = number_format($mindata, 2, '.', '');

                $meandata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdatesem)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->avg('sem_class5_1');
                $mean = number_format($meandata, 2, '.', '');
   
                $stddata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdatesem)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid']);
                $stdget = $stddata->select(DB::raw('STDDEV_SAMP(nt_data_sem.sem_class5_1) as std'))->first();
                $std = number_format($stdget->std, 2, '.', '');   
                $mean6data = $meandata + (6*$stdget->std);
                $mean6 = number_format($mean6data, 2, '.', '');

                // $sheet = $doc->setActiveSheetIndex(9);
                $sheet = $doc->setActiveSheetIndexByName('SEM C 5.1');

                $sheet->setCellValue('A'.$i,$model->name_model);
                $sheet->setCellValue('B'.$i,$model->part);
                $sheet->setCellValue('C'.$i,$model->part_number);
                $sheet->setCellValue('D'.$i,$count);
                $sheet->setCellValue('E'.$i,$max);
                $sheet->setCellValue('F'.$i,$min);
                $sheet->setCellValue('G'.$i,$mean);
                $sheet->setCellValue('H'.$i,$std); 
                if(!empty($getfirstdate)){
                    $sheet->setCellValue('I'.$i, date('Y-m-d', strtotime($getfirstdate->save_date)));
                }else{
                    $sheet->setCellValue('I'.$i,$nowstartdatesem);
                }
                if(!empty($getlastdate)){
                    $sheet->setCellValue('J'.$i, date('Y-m-d', strtotime($getlastdate->save_date)));
                }else{
                    $sheet->setCellValue('J'.$i,$item['dateend']);
                }
                // $sheet->setCellValue('P'.$i,$mean6);
                $sheet->setCellValue('P'.$i,$nt_model_spec->class5_1);

                
                $maxdata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdatesem)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->max('sem_class6');
                $max = number_format($maxdata, 2, '.', '');
               
                $mindata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdatesem)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->min('sem_class6');
                $min = number_format($mindata, 2, '.', '');

                $meandata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdatesem)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->avg('sem_class6');
                $mean = number_format($meandata, 2, '.', '');
   
                $stddata = DB::table('nt_data_sem')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdatesem)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid']);
                $stdget = $stddata->select(DB::raw('STDDEV_SAMP(nt_data_sem.sem_class6) as std'))->first();
                $std = number_format($stdget->std, 2, '.', '');   
                $mean6data = $meandata + (6*$stdget->std);
                $mean6 = number_format($mean6data, 2, '.', '');

                // $sheet = $doc->setActiveSheetIndex(10);
                $sheet = $doc->setActiveSheetIndexByName('SEM C 6');

                $sheet->setCellValue('A'.$i,$model->name_model);
                $sheet->setCellValue('B'.$i,$model->part);
                $sheet->setCellValue('C'.$i,$model->part_number);
                $sheet->setCellValue('D'.$i,$count);
                $sheet->setCellValue('E'.$i,$max);
                $sheet->setCellValue('F'.$i,$min);
                $sheet->setCellValue('G'.$i,$mean);
                $sheet->setCellValue('H'.$i,$std); 
                if(!empty($getfirstdate)){
                    $sheet->setCellValue('I'.$i, date('Y-m-d', strtotime($getfirstdate->save_date)));
                }else{
                    $sheet->setCellValue('I'.$i,$nowstartdatesem);
                }
                if(!empty($getlastdate)){
                    $sheet->setCellValue('J'.$i, date('Y-m-d', strtotime($getlastdate->save_date)));
                }else{
                    $sheet->setCellValue('J'.$i,$item['dateend']);
                }
                // $sheet->setCellValue('P'.$i,$mean6);
                $sheet->setCellValue('P'.$i,$nt_model_spec->class6);

                $nowstartdateic = $item['datestart'];
                if($request->numcountdata > 0){
                    $getic = DB::table('nt_data_ic')
                    ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ic.id_createlab')
                    ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                    ->where('nt_oqa_lab.id_model',$item['modelid'])
                    ->orderBy('nt_oqa_lab.save_date','DESC')
                    ->take($request->numcountdata)
                    ->get();

                    foreach ($getic as $value) {
                        $nowstartdateic = $value->save_date;
                    }
                }

                $count = DB::table('nt_data_ic')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ic.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdateic)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->count();
                $chkiccount = $chkiccount + $count;
                $maxdata = DB::table('nt_data_ic')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ic.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdateic)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->max('ic_anion_up');
                $max = number_format($maxdata, 5, '.', '');
               
                $mindata = DB::table('nt_data_ic')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ic.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdateic)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->min('ic_anion_up');
                $min = number_format($mindata, 5, '.', '');

                $meandata = DB::table('nt_data_ic')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ic.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdateic)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->avg('ic_anion_up');
                $mean = number_format($meandata, 5, '.', '');
 
                $stddata = DB::table('nt_data_ic')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ic.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdateic)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid']);
                $stdget = $stddata->select(DB::raw('STDDEV_SAMP(nt_data_ic.ic_anion_up) as std'))->first();
                $std = number_format($stdget->std, 5, '.', '');   
                $mean6data = $meandata + (6*$stdget->std);
                $mean6 = number_format($mean6data, 5, '.', '');

                $getfirstdate = DB::table('nt_data_ic')
                ->select('nt_oqa_lab.save_date')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ic.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdateic)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])
                ->orderBy('nt_oqa_lab.save_date','ASC')
                ->first(); 
                $getlastdate = DB::table('nt_data_ic')
                ->select('nt_oqa_lab.save_date')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ic.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdateic)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])
                ->orderBy('nt_oqa_lab.save_date','DESC')
                ->first();  

                $datacal_ic = $this->getValue_calculation_ic($item['modelid'],$nowstartdateic,$item['dateend']);

                // $sheet = $doc->setActiveSheetIndex(11);
                $sheet = $doc->setActiveSheetIndexByName('Anion');

                $sheet->setCellValue('A'.$i,$model->name_model);
                $sheet->setCellValue('B'.$i,$model->part);
                $sheet->setCellValue('C'.$i,$model->part_number);
                // $sheet->setCellValue('D'.$i,$count);
                // $sheet->setCellValue('E'.$i,$max);
                // $sheet->setCellValue('F'.$i,$min);
                // $sheet->setCellValue('G'.$i,$mean);
                // $sheet->setCellValue('H'.$i,$std);
                $sheet->setCellValue('D'.$i,$datacal_ic['count']);
                $sheet->setCellValue('E'.$i,$datacal_ic['max']);
                $sheet->setCellValue('F'.$i,$datacal_ic['min']);
                $sheet->setCellValue('G'.$i,$datacal_ic['mean']);
                $sheet->setCellValue('H'.$i,$datacal_ic['sigma']);
                
                if(!empty($getfirstdate)){
                    $sheet->setCellValue('I'.$i, date('Y-m-d', strtotime($getfirstdate->save_date)));
                }else{
                    $sheet->setCellValue('I'.$i,$nowstartdateic);
                }
                if(!empty($getlastdate)){
                    $sheet->setCellValue('J'.$i, date('Y-m-d', strtotime($getlastdate->save_date)));
                }else{
                    $sheet->setCellValue('J'.$i,$item['dateend']);
                }

                // $sheet->setCellValue('P'.$i,$mean6); 
                $sheet->setCellValue('P'.$i,$nt_model_spec->anion);
        
                $maxdata = DB::table('nt_data_ic')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ic.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdateic)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->max('ic_sulphate');
                $max = number_format($maxdata, 5, '.', '');
               
                $mindata = DB::table('nt_data_ic')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ic.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdateic)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->min('ic_sulphate');
                $min = number_format($mindata, 5, '.', '');

                $meandata = DB::table('nt_data_ic')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ic.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdateic)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->avg('ic_sulphate');
                $mean = number_format($meandata, 5, '.', '');
   
                $stddata = DB::table('nt_data_ic')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ic.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdateic)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid']);
                $stdget = $stddata->select(DB::raw('STDDEV_SAMP(nt_data_ic.ic_sulphate) as std'))->first();
                $std = number_format($stdget->std, 5, '.', '');   
                $mean6data = $meandata + (6*$stdget->std);
                $mean6 = number_format($mean6data, 5, '.', '');

                // $sheet = $doc->setActiveSheetIndex(12);
                $sheet = $doc->setActiveSheetIndexByName('Sulphate');

                $sheet->setCellValue('A'.$i,$model->name_model);
                $sheet->setCellValue('B'.$i,$model->part);
                $sheet->setCellValue('C'.$i,$model->part_number);
                $sheet->setCellValue('D'.$i,$count);
                $sheet->setCellValue('E'.$i,$max);
                $sheet->setCellValue('F'.$i,$min);
                $sheet->setCellValue('G'.$i,$mean);
                $sheet->setCellValue('H'.$i,$std); 
                if(!empty($getfirstdate)){
                    $sheet->setCellValue('I'.$i, date('Y-m-d', strtotime($getfirstdate->save_date)));
                }else{
                    $sheet->setCellValue('I'.$i,$nowstartdateic);
                }
                if(!empty($getlastdate)){
                    $sheet->setCellValue('J'.$i, date('Y-m-d', strtotime($getlastdate->save_date)));
                }else{
                    $sheet->setCellValue('J'.$i,$item['dateend']);
                }
                // $sheet->setCellValue('P'.$i,$mean6);
                $sheet->setCellValue('P'.$i,$nt_model_spec->sulphate);

                
                $maxdata = DB::table('nt_data_ic')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ic.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdateic)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->max('ic_cation_up');
                $max = number_format($maxdata, 5, '.', '');
               
                $mindata = DB::table('nt_data_ic')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ic.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdateic)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->min('ic_cation_up');
                $min = number_format($mindata, 5, '.', '');

                $meandata = DB::table('nt_data_ic')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ic.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdateic)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->avg('ic_cation_up');
                $mean = number_format($meandata, 5, '.', '');
   
                $stddata = DB::table('nt_data_ic')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ic.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdateic)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid']);
                $stdget = $stddata->select(DB::raw('STDDEV_SAMP(nt_data_ic.ic_cation_up) as std'))->first();
                $std = number_format($stdget->std, 5, '.', '');   
                $mean6data = $meandata + (6*$stdget->std);
                $mean6 = number_format($mean6data, 5, '.', '');

                // $sheet = $doc->setActiveSheetIndex(13);
                $sheet = $doc->setActiveSheetIndexByName('Cation');

                $sheet->setCellValue('A'.$i,$model->name_model);
                $sheet->setCellValue('B'.$i,$model->part);
                $sheet->setCellValue('C'.$i,$model->part_number);
                $sheet->setCellValue('D'.$i,$count);
                $sheet->setCellValue('E'.$i,$max);
                $sheet->setCellValue('F'.$i,$min);
                $sheet->setCellValue('G'.$i,$mean);
                $sheet->setCellValue('H'.$i,$std); 
                if(!empty($getfirstdate)){
                    $sheet->setCellValue('I'.$i, date('Y-m-d', strtotime($getfirstdate->save_date)));
                }else{
                    $sheet->setCellValue('I'.$i,$nowstartdateic);
                }
                if(!empty($getlastdate)){
                    $sheet->setCellValue('J'.$i, date('Y-m-d', strtotime($getlastdate->save_date)));
                }else{
                    $sheet->setCellValue('J'.$i,$item['dateend']);
                }
                // $sheet->setCellValue('P'.$i,$mean6);
                $sheet->setCellValue('P'.$i,$nt_model_spec->cation);


                $nowstartdategcms = $item['datestart'];
                if($request->numcountdata > 0){
                    $getgcms = DB::table('nt_data_gcms')
                    ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
                    ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                    ->where('nt_oqa_lab.id_model',$item['modelid'])
                    ->orderBy('nt_oqa_lab.save_date','DESC')
                    ->take($request->numcountdata)
                    ->get();

                    foreach ($getgcms as $value) {
                        $nowstartdategcms = $value->save_date;
                    }
                }

                $count = DB::table('nt_data_gcms')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdategcms)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->count();
                $chkgcmscount = $chkgcmscount + $count;
                $maxdata = DB::table('nt_data_gcms')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdategcms)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->max('gcms_outgassing');
                $max = number_format($maxdata, 2, '.', '');
               
                $mindata = DB::table('nt_data_gcms')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdategcms)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->min('gcms_outgassing');
                $min = number_format($mindata, 2, '.', '');

                $meandata = DB::table('nt_data_gcms')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdategcms)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->avg('gcms_outgassing');
                $mean = number_format($meandata, 2, '.', '');
   
                $stddata = DB::table('nt_data_gcms')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdategcms)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid']);
                $stdget = $stddata->select(DB::raw('STDDEV_SAMP(nt_data_gcms.gcms_outgassing) as std'))->first();
                $std = number_format($stdget->std, 2, '.', '');   
                $mean6data = $meandata + (6*$stdget->std);
                $mean6 = number_format($mean6data, 2, '.', '');

                $getfirstdate = DB::table('nt_data_gcms')
                ->select('nt_oqa_lab.save_date')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdategcms)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])
                ->orderBy('nt_oqa_lab.save_date','ASC')
                ->first(); 
                $getlastdate = DB::table('nt_data_gcms')
                ->select('nt_oqa_lab.save_date')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdategcms)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])
                ->orderBy('nt_oqa_lab.save_date','DESC')
                ->first();   

                // $sheet = $doc->setActiveSheetIndex(14);
                $sheet = $doc->setActiveSheetIndexByName('Outgassing');

                $sheet->setCellValue('A'.$i,$model->name_model);
                $sheet->setCellValue('B'.$i,$model->part);
                $sheet->setCellValue('C'.$i,$model->part_number);
                $sheet->setCellValue('D'.$i,$count);
                $sheet->setCellValue('E'.$i,$max);
                $sheet->setCellValue('F'.$i,$min);
                $sheet->setCellValue('G'.$i,$mean);
                $sheet->setCellValue('H'.$i,$std); 
                if(!empty($getfirstdate)){
                    $sheet->setCellValue('I'.$i, date('Y-m-d', strtotime($getfirstdate->save_date)));
                }else{
                    $sheet->setCellValue('I'.$i,$nowstartdategcms);
                }
                if(!empty($getlastdate)){
                    $sheet->setCellValue('J'.$i, date('Y-m-d', strtotime($getlastdate->save_date)));
                }else{
                    $sheet->setCellValue('J'.$i,$item['dateend']);
                }
                // $sheet->setCellValue('P'.$i,$mean6);
                $sheet->setCellValue('P'.$i,$nt_model_spec->outgassing);

                $maxdata = DB::table('nt_data_gcms')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdategcms)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->max('gcms_low_mw');
                $max = number_format($maxdata, 2, '.', '');
               
                $mindata = DB::table('nt_data_gcms')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdategcms)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->min('gcms_low_mw');
                $min = number_format($mindata, 2, '.', '');

                $meandata = DB::table('nt_data_gcms')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdategcms)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->avg('gcms_low_mw');
                $mean = number_format($meandata, 2, '.', '');
   
                $stddata = DB::table('nt_data_gcms')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdategcms)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid']);
                $stdget = $stddata->select(DB::raw('STDDEV_SAMP(nt_data_gcms.gcms_low_mw) as std'))->first();
                $std = number_format($stdget->std, 2, '.', '');   
                $mean6data = $meandata + (6*$stdget->std);
                $mean6 = number_format($mean6data, 2, '.', '');

                // $sheet = $doc->setActiveSheetIndex(15);
                $sheet = $doc->setActiveSheetIndexByName('Low MW');

                $sheet->setCellValue('A'.$i,$model->name_model);
                $sheet->setCellValue('B'.$i,$model->part);
                $sheet->setCellValue('C'.$i,$model->part_number);
                $sheet->setCellValue('D'.$i,$count);
                $sheet->setCellValue('E'.$i,$max);
                $sheet->setCellValue('F'.$i,$min);
                $sheet->setCellValue('G'.$i,$mean);
                $sheet->setCellValue('H'.$i,$std); 
                if(!empty($getfirstdate)){
                    $sheet->setCellValue('I'.$i, date('Y-m-d', strtotime($getfirstdate->save_date)));
                }else{
                    $sheet->setCellValue('I'.$i,$nowstartdategcms);
                }
                if(!empty($getlastdate)){
                    $sheet->setCellValue('J'.$i, date('Y-m-d', strtotime($getlastdate->save_date)));
                }else{
                    $sheet->setCellValue('J'.$i,$item['dateend']);
                }
                // $sheet->setCellValue('P'.$i,$mean6);
                $sheet->setCellValue('P'.$i,$nt_model_spec->low_MW);

                
                $maxdata = DB::table('nt_data_gcms')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdategcms)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->max('gcms_high_mw');
                $max = number_format($maxdata, 2, '.', '');
               
                $mindata = DB::table('nt_data_gcms')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdategcms)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->min('gcms_high_mw');
                $min = number_format($mindata, 2, '.', '');

                $meandata = DB::table('nt_data_gcms')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdategcms)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->avg('gcms_high_mw');
                $mean = number_format($meandata, 2, '.', '');
   
                $stddata = DB::table('nt_data_gcms')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdategcms)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid']);
                $stdget = $stddata->select(DB::raw('STDDEV_SAMP(nt_data_gcms.gcms_high_mw) as std'))->first();
                $std = number_format($stdget->std, 2, '.', '');   
                $mean6data = $meandata + (6*$stdget->std);
                $mean6 = number_format($mean6data, 2, '.', '');

                // $sheet = $doc->setActiveSheetIndex(16);
                $sheet = $doc->setActiveSheetIndexByName('High MW');

                $sheet->setCellValue('A'.$i,$model->name_model);
                $sheet->setCellValue('B'.$i,$model->part);
                $sheet->setCellValue('C'.$i,$model->part_number);
                $sheet->setCellValue('D'.$i,$count);
                $sheet->setCellValue('E'.$i,$max);
                $sheet->setCellValue('F'.$i,$min);
                $sheet->setCellValue('G'.$i,$mean);
                $sheet->setCellValue('H'.$i,$std);
                if(!empty($getfirstdate)){
                    $sheet->setCellValue('I'.$i, date('Y-m-d', strtotime($getfirstdate->save_date)));
                }else{
                    $sheet->setCellValue('I'.$i,$nowstartdategcms);
                }
                if(!empty($getlastdate)){
                    $sheet->setCellValue('J'.$i, date('Y-m-d', strtotime($getlastdate->save_date)));
                }else{
                    $sheet->setCellValue('J'.$i,$item['dateend']);
                }
                // $sheet->setCellValue('P'.$i,$mean6);
                $sheet->setCellValue('P'.$i,$nt_model_spec->high_MW);

                
                $maxdata = DB::table('nt_data_gcms')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdategcms)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->max('gcms_smear');
                $max = number_format($maxdata, 2, '.', '');
               
                $mindata = DB::table('nt_data_gcms')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdategcms)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->min('gcms_smear');
                $min = number_format($mindata, 2, '.', '');

                $meandata = DB::table('nt_data_gcms')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdategcms)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->avg('gcms_smear');
                $mean = number_format($meandata, 2, '.', '');
   
                $stddata = DB::table('nt_data_gcms')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdategcms)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid']);
                $stdget = $stddata->select(DB::raw('STDDEV_SAMP(nt_data_gcms.gcms_smear) as std'))->first();
                $std = number_format($stdget->std, 2, '.', '');   
                $mean6data = $meandata + (6*$stdget->std);
                $mean6 = number_format($mean6data, 2, '.', '');

                // $sheet = $doc->setActiveSheetIndex(17);
                $sheet = $doc->setActiveSheetIndexByName('Smear');

                $sheet->setCellValue('A'.$i,$model->name_model);
                $sheet->setCellValue('B'.$i,$model->part);
                $sheet->setCellValue('C'.$i,$model->part_number);
                $sheet->setCellValue('D'.$i,$count);
                $sheet->setCellValue('E'.$i,$max);
                $sheet->setCellValue('F'.$i,$min);
                $sheet->setCellValue('G'.$i,$mean);
                $sheet->setCellValue('H'.$i,$std);
                if(!empty($getfirstdate)){
                    $sheet->setCellValue('I'.$i, date('Y-m-d', strtotime($getfirstdate->save_date)));
                }else{
                    $sheet->setCellValue('I'.$i,$nowstartdategcms);
                }
                if(!empty($getlastdate)){
                    $sheet->setCellValue('J'.$i, date('Y-m-d', strtotime($getlastdate->save_date)));
                }else{
                    $sheet->setCellValue('J'.$i,$item['dateend']);
                }
                // $sheet->setCellValue('P'.$i,$mean6);
                $sheet->setCellValue('P'.$i,$nt_model_spec->alkylacrylate);

                $nowstartdateftir = $item['datestart'];
                if($request->numcountdata > 0){
                    $getftir = DB::table('nt_data_ftir')
                    ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ftir.id_createlab')
                    ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                    ->where('nt_oqa_lab.id_model',$item['modelid'])
                    ->orderBy('nt_oqa_lab.save_date','DESC')
                    ->take($request->numcountdata)
                    ->get();

                    foreach ($getftir as $value) {
                        $nowstartdateftir = $value->save_date;
                    }
                }

                $count = DB::table('nt_data_ftir')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ftir.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdateftir)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->count();
                
                $chkftircount = $chkftircount + $count;
                $maxdata = DB::table('nt_data_ftir')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ftir.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdateftir)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->max('ftir_silicone');
                $max = number_format($maxdata, 2, '.', '');
               
                $mindata = DB::table('nt_data_ftir')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ftir.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdateftir)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->min('ftir_silicone');
                $min = number_format($mindata, 2, '.', '');

                $meandata = DB::table('nt_data_ftir')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ftir.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdateftir)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])->avg('ftir_silicone');
                $mean = number_format($meandata, 2, '.', '');
   
                $stddata = DB::table('nt_data_ftir')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ftir.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdateftir)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid']);
                $stdget = $stddata->select(DB::raw('STDDEV_SAMP(nt_data_ftir.ftir_silicone) as std'))->first();
                $std = number_format($stdget->std, 2, '.', '');   
                $mean6data = $meandata + (6*$stdget->std);
                $mean6 = number_format($mean6data, 2, '.', '');

                $getfirstdate = DB::table('nt_data_ftir')
                ->select('nt_oqa_lab.save_date')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ftir.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdateftir)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])
                ->orderBy('nt_oqa_lab.save_date','ASC')
                ->first(); 
                $getlastdate = DB::table('nt_data_ftir')
                ->select('nt_oqa_lab.save_date')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ftir.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$nowstartdateftir)
                ->where('nt_oqa_lab.save_date','<=',$item['dateend'])
                ->where('nt_oqa_lab.id_model',$item['modelid'])
                ->orderBy('nt_oqa_lab.save_date','DESC')
                ->first(); 

                $sheet = $doc->setActiveSheetIndexByName('FTIR');

                $sheet->setCellValue('A'.$i,$model->name_model);
                $sheet->setCellValue('B'.$i,$model->part);
                $sheet->setCellValue('C'.$i,$model->part_number);
                $sheet->setCellValue('D'.$i,$count);
                $sheet->setCellValue('E'.$i,$max);
                $sheet->setCellValue('F'.$i,$min);
                $sheet->setCellValue('G'.$i,$mean);
                $sheet->setCellValue('H'.$i,$std);
                
                if(!empty($getfirstdate)){
                    $sheet->setCellValue('I'.$i, date('Y-m-d', strtotime($getfirstdate->save_date)));
                }else{
                    $sheet->setCellValue('I'.$i,$nowstartdateftir);
                }
                if(!empty($getlastdate)){
                    $sheet->setCellValue('J'.$i, date('Y-m-d', strtotime($getlastdate->save_date)));
                }else{
                    $sheet->setCellValue('J'.$i,$item['dateend']);
                }

                $sheet->setCellValue('P'.$i,$nt_model_spec->ftir);


                $i++;
                
            }

        })->setFilename($namefile)->store('xlsx', storage_path('NHK-File/report/calculation'));

        return response()->json(['success' =>  1,'filename' => $namefile.'.xlsx']);


    }
    public function showcalculation(Request $request)	
    {
      
        $dateend_new = date('Y-m-d 00:00:00', strtotime($request->dateend. ' + 1 day'));
        $lpccount = DB::table('nt_data_lpc')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_lpc.id_createlab')
        ->where('nt_oqa_lab.save_date','<=',$dateend_new)
        ->where('nt_oqa_lab.save_date','>=',$request->datestart)
        ->where('nt_oqa_lab.id_model',$request->id)->count();
        $semcount = DB::table('nt_data_sem')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_sem.id_createlab')
        ->where('nt_oqa_lab.save_date','<=',$dateend_new)
        ->where('nt_oqa_lab.save_date','>=',$request->datestart)
        ->where('nt_oqa_lab.id_model',$request->id)->count();
        $iccount = DB::table('nt_data_ic')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ic.id_createlab')
        ->where('nt_oqa_lab.save_date','<=',$dateend_new)
        ->where('nt_oqa_lab.save_date','>=',$request->datestart)
        ->where('nt_oqa_lab.id_model',$request->id)->count();
        $gcmscount = DB::table('nt_data_gcms')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_gcms.id_createlab')
        ->where('nt_oqa_lab.save_date','<=',$dateend_new)
        ->where('nt_oqa_lab.save_date','>=',$request->datestart)
        ->where('nt_oqa_lab.id_model',$request->id)->count();
        $ftircount = DB::table('nt_data_ftir')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_ftir.id_createlab')
        ->where('nt_oqa_lab.save_date','<=',$dateend_new)
        ->where('nt_oqa_lab.save_date','>=',$request->datestart)
        ->where('nt_oqa_lab.id_model',$request->id)->count();
        $meandata = DB::table('nt_data_lpc')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_lpc.id_createlab')
        ->where('nt_oqa_lab.save_date','<=',$dateend_new)
        ->where('nt_oqa_lab.save_date','>=',$request->datestart)
        ->where('nt_oqa_lab.id_model',$request->id)->avg('lpc_data');
        $mean = number_format($meandata, 2, '.', '');
        $maxdata = DB::table('nt_data_lpc')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_lpc.id_createlab')
        ->where('nt_oqa_lab.save_date','<=',$dateend_new)
        ->where('nt_oqa_lab.save_date','>=',$request->datestart)
        ->where('nt_oqa_lab.id_model',$request->id)->max('lpc_data');
        $max = number_format($maxdata, 2, '.', '');
        $mindata = DB::table('nt_data_lpc')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_lpc.id_createlab')
        ->where('nt_oqa_lab.save_date','<=',$dateend_new)
        ->where('nt_oqa_lab.save_date','>=',$request->datestart)
        ->where('nt_oqa_lab.id_model',$request->id)->min('lpc_data');
        $min = number_format($mindata, 2, '.', '');
        $stddata = DB::table('nt_data_lpc')
        ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_data_lpc.id_createlab')
        ->where('nt_oqa_lab.save_date','<=',$dateend_new)
        ->where('nt_oqa_lab.save_date','>=',$request->datestart)
        ->where('nt_oqa_lab.id_model',$request->id);
        $stdget = $stddata->select(DB::raw('STDDEV_SAMP(nt_data_lpc.lpc_data) as std'))->first();
        $std = number_format($stdget->std, 2, '.', '');
        $mean3data = $meandata + (3 * $stdget->std);
        $mean3 = number_format($mean3data, 2, '.', '');
        $mean4data = $meandata + (4*$stdget->std);
        $mean4 = number_format($mean4data, 2, '.', '');
        $mean4_5data = $meandata + (4.5*$stdget->std);
        $mean4_5 = number_format($mean4_5data, 2, '.', '');
        $mean6data = $meandata + (6*$stdget->std);
        $mean6 = number_format($mean6data, 2, '.', '');
        return response()->json([
            'success' =>  1,
            'lpccount'=>$lpccount,
            'semcount'=>$semcount,
            'iccount'=>$iccount,
            'gcmscount'=>$gcmscount,
            'ftircount'=>$ftircount,
            'id_model'=>$request->id,
            'mean'=>$mean,
            'max'=>$max,
            'min'=>$min,
            'std'=>$std,
            'mean3'=>$mean3,
            'mean4'=>$mean4,
            'mean4_5'=>$mean4_5,
            'mean6'=>$mean6,
        ]);
    }

    public function getValue_calculation_ic($model1,$startdate1,$enddate1) 
    {
            
            ini_set('memory_limit', '-1');
            set_time_limit(5000);
        
            $a1 = DB::table('nt_model')->where('id_model', $model1)->first();
            $text1 = $a1->name_model;
            $chktext1 = mb_substr($text1,0,-3);
            $model1 = $a1;

            $datestart1 = date('Y-m-d 00:00:00', strtotime($startdate1));
            $dateend1 = date('Y-m-d 00:00:00', strtotime($enddate1));

            if($model1->part == "UP"){

                $datalabic = DB::table('nt_datacf_ic')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_datacf_ic.id_createlab')
                ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$datestart1)
                ->where('nt_oqa_lab.save_date','<=',$dateend1)
                ->where('nt_oqa_lab.id_model',$model1->id_model)
                ->where('nt_oqa_lab.ic_confirm_st',1)
                ->orderBy('nt_oqa_lab.save_date', 'asc')->get();

                $specification = DB::table('nt_model_spec')->where('id_model', $model1->id_model)->where('id_model_type', 1)->first();
                $triggerlimit = DB::table('nt_model_spec')->where('id_model', $model1->id_model)->where('id_model_type', 2)->first();
                $ucl = DB::table('nt_model_spec')->where('id_model', $model1->id_model)->where('id_model_type', 3)->first();
                $lcl = DB::table('nt_model_spec')->where('id_model', $model1->id_model)->where('id_model_type', 4)->first();
                $cl = DB::table('nt_model_spec')->where('id_model', $model1->id_model)->where('id_model_type', 5)->first();
            }

            if($model1->part == "DN"){

                $datalabicdw = DB::table('nt_datacf_ic')
                ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_datacf_ic.id_createlab')
                ->join('nt_oqa_file','nt_oqa_lab.id_createlab','=','nt_oqa_file.id_createlab')
                ->where('nt_oqa_lab.save_date','>=',$datestart1)
                ->where('nt_oqa_lab.save_date','<=',$dateend1)
                ->where('nt_oqa_lab.id_model',$model1->id_model)
                ->where('nt_oqa_lab.ic_confirm_st',1)
                ->orderBy('nt_oqa_lab.save_date', 'asc')->get(); 

                $specification1 = DB::table('nt_model_spec')->where('id_model', $model1->id_model)->where('id_model_type', 1)->first();
                $triggerlimit1 = DB::table('nt_model_spec')->where('id_model', $model1->id_model)->where('id_model_type', 2)->first();
                $ucl1 = DB::table('nt_model_spec')->where('id_model', $model1->id_model)->where('id_model_type', 3)->first();
                $lcl1 = DB::table('nt_model_spec')->where('id_model', $model1->id_model)->where('id_model_type', 4)->first();
                $cl1 = DB::table('nt_model_spec')->where('id_model', $model1->id_model)->where('id_model_type', 5)->first();
            }
            
            $filetime = date('YmdHis');
            $spacename = str_replace(' ', '', $chktext1);
            $namefile = 'getvalue_Excel_Raw_'.$spacename;

            $checkfile =  storage_path('NHK-File/report/calculation/'.$namefile.'.xlsx'  );
            if(file_exists($checkfile)){
                @unlink($checkfile);
            }

            $file = storage_path('NHK-File/template/report/test_data_calculation.xlsx');
            $reader = IOFactory::createReader('Xlsx');
            $spreadsheet = $reader->load($file); 
            
                $sheetanion = $spreadsheet->setActiveSheetIndex(0);
                
                if($model1->part == "UP"){
                    $i = 3;
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
     
                        $i++;
                    }
                }

                if($model1->part == "DN"){
                    $i = 3;
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
     
                        $i++;
                    } 
                } 
             
            $chk = 1; 

            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $writer->save(storage_path('NHK-File/report/calculation/'.$namefile.'.xlsx'));

            //-------- get value -----------

            $getfile = storage_path('NHK-File/report/calculation/'.$namefile.'.xlsx');
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($getfile);
            $sheet = $spreadsheet->setActiveSheetIndex(0);

            if($model1->part == "UP"){

                $count = $sheet->getCell('B3')->getCalculatedValue();
                $max = $sheet->getCell('B4')->getCalculatedValue();
                $min = $sheet->getCell('B5')->getCalculatedValue();
                $mean = $sheet->getCell('B6')->getCalculatedValue();
                $sigma = $sheet->getCell('B7')->getCalculatedValue();
                $mean3m = $sheet->getCell('B8')->getCalculatedValue();
                $mean3 = $sheet->getCell('B9')->getCalculatedValue();
                $mean4 = $sheet->getCell('B10')->getCalculatedValue();
                $mean4_5 = $sheet->getCell('B11')->getCalculatedValue();
                $mean6 = $sheet->getCell('B12')->getCalculatedValue();

                $max = floatval($max);
                $min = floatval($min);
                $mean = floatval($mean);
                $sigma = floatval($sigma);
                $mean3m = floatval($mean3m);
                $mean3 = floatval($mean3);
                $mean4 = floatval($mean4);
                $mean4_5 = floatval($mean4_5);
                $mean6 = floatval($mean6);

                $max = number_format($max, 5, '.', '');
                $min = number_format($min, 5, '.', '');
                $mean = number_format($mean, 5, '.', '');
                $sigma = number_format($sigma, 5, '.', '');
                $mean3m = number_format($mean3m, 5, '.', '');
                $mean3 = number_format($mean3, 5, '.', '');
                $mean4 = number_format($mean4, 5, '.', '');
                $mean4_5 = number_format($mean4_5, 5, '.', '');
                $mean6 = number_format($mean6, 5, '.', '');

            }else{

                $count = $sheet->getCell('C3')->getCalculatedValue();
                $max = $sheet->getCell('C4')->getCalculatedValue();
                $min = $sheet->getCell('C5')->getCalculatedValue();
                $mean = $sheet->getCell('C6')->getCalculatedValue();
                $sigma = $sheet->getCell('C7')->getCalculatedValue();
                $mean3m = $sheet->getCell('C8')->getCalculatedValue();
                $mean3 = $sheet->getCell('C9')->getCalculatedValue();
                $mean4 = $sheet->getCell('C10')->getCalculatedValue();
                $mean4_5 = $sheet->getCell('C11')->getCalculatedValue();
                $mean6 = $sheet->getCell('C12')->getCalculatedValue();

                $max = floatval($max);
                $min = floatval($min);
                $mean = floatval($mean);
                $sigma = floatval($sigma);
                $mean3m = floatval($mean3m);
                $mean3 = floatval($mean3);
                $mean4 = floatval($mean4);
                $mean4_5 = floatval($mean4_5);
                $mean6 = floatval($mean6);

                $max = number_format($max, 5, '.', '');
                $min = number_format($min, 5, '.', '');
                $mean = number_format($mean, 5, '.', '');
                $sigma = number_format($sigma, 5, '.', '');
                $mean3m = number_format($mean3m, 5, '.', '');
                $mean3 = number_format($mean3, 5, '.', '');
                $mean4 = number_format($mean4, 5, '.', '');
                $mean4_5 = number_format($mean4_5, 5, '.', '');
                $mean6 = number_format($mean6, 5, '.', '');

            }

            $data = [
                'count' => $count,
                'max' => $max,
                'min' => $min,
                'mean' => $mean,
                'sigma' => $sigma,
                'mean3m' => $mean3m,
                'mean3' => $mean3,
                'mean4' => $mean4,
                'mean4_5' => $mean4_5,
                'mean6' => $mean6,

            ];

            return $data;

    }  
    
    //  -------------- Combine -------------- 

    public function datatablecombine(Request $request) 
    { 
        
        if(!empty($request->date_start) && empty(!$request->date_end))
        {

            $datestart = date('Y-m-d 00:00:00', strtotime($request->date_start));
            $dateend = date('Y-m-d 00:00:00', strtotime($request->date_end));
            $start = "'$datestart'";
            $end = "'$dateend'";
            $number = ($request->number)? $request->number : 0;

            $firstget = DB::table('nt_oqa_lab')
            ->wherebetween('nt_oqa_lab.save_date', [$datestart, $dateend]) 
            ->get();
            $id_model = [];
            foreach ($firstget as $key => $_first) {
                $id_model[] = $_first->id_model;
            }
            $check_id_model = array_unique( $id_model );
            
            $datalist = DB::table('nt_model')
            ->select('drive_name', 'model_type', DB::raw('MAX(id_model) as id_model'), DB::raw(''.$start .' as startdate'),DB::raw(''.$end .' as enddate'),DB::raw(''.$number .' as number'))
            ->where('status_model', 1)
            ->where('drive_name', '!=', '-')
            ->whereIn('id_model', $check_id_model)
            ->groupBy('drive_name', 'model_type')
            ->orderBy('drive_name', 'asc')
            ->orderBy('model_type', 'asc')
            ->get();

            $sQuery  = Datatables::of($datalist)
                ->addIndexColumn()
                ->addColumn('indexs',function($data){

                    $model_type = "SGT_TNHK";
                    if($data->model_type != "SGT_TNHK"){
                        $model_type = "SGT_Oversea";
                    }
                    
                    $results = DB::table('nt_oqa_lab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.drive_name', $data->drive_name)
                    ->where('nt_model.model_type', $model_type)
                    ->where('nt_oqa_lab.save_date', '<=', $data->enddate)
                    ->orderby('nt_oqa_lab.save_date', 'DESC')
                    ->get(['nt_oqa_lab.save_date as first_date', 'nt_model.name_model', 'nt_oqa_lab.name_lab', 'nt_model.model_type', 'nt_model.drive_name']);

                    $sortedResults = $results->sortBy('model_type')->values();
                    $earliestDate = $sortedResults->last()->model_type;

                    if($earliestDate == "SGT_TNHK"){
                        $name = "TNHK";
                    }else{
                        $name = "JNHK";
                    }

                    return $data->drive_name.'_'.$name;

                })
                ->addColumn('site',function($data){
                    if($data->model_type == "SGT_TNHK"){
                        $name = "TNHK";
                    }else{
                        $name = "JNHK";
                    }
                    return $name;
                })
                ->addColumn('model',function($data){
                    $nameandpart = [];
                    $model_type = "SGT_TNHK";
                    if($data->model_type != "SGT_TNHK"){
                        $model_type = "SGT_Oversea";
                    }
                     
                    $getlast = DB::table('nt_oqa_lab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.drive_name', $data->drive_name)
                    ->where('nt_model.model_type', $model_type)
                    ->whereBetween('nt_oqa_lab.save_date', [$data->startdate,$data->enddate])
                    ->orderBy('nt_oqa_lab.save_date', 'DESC')
                    ->get();

                    $idlab = [];
                    foreach ($getlast as $key => $value) {
                        $idlab[] = $value->id_createlab;
                    }

                    $results = DB::table('nt_oqa_lab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->whereIN('nt_oqa_lab.id_createlab', $idlab)
                    ->get();

                    foreach ($results as $key => $value) {
                        $nameandpart[] = $value->name_model;
                    }   
                    $namemodel = array_unique($nameandpart);
                    
                    $textname = '';
                    if(count($namemodel) > 0){
                        foreach ($namemodel as $key => $value) {
                            $textname .= "<p>".$value."</p>";
                        }
                    }
                    return $textname;
                })
                ->addColumn('partnumber',function($data){

                    $model_type = "SGT_TNHK";
                    if($data->model_type != "SGT_TNHK"){
                        $model_type = "SGT_Oversea";
                    }

                    $nameandpart = [];
                    $getlast = DB::table('nt_oqa_lab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.drive_name', $data->drive_name)
                    ->where('nt_model.model_type', $model_type)
                    ->whereBetween('nt_oqa_lab.save_date', [$data->startdate,$data->enddate])
                    ->orderBy('nt_oqa_lab.save_date', 'DESC')
                    ->get();

                    $idlab = [];
                    foreach ($getlast as $key => $value) {
                        $idlab[] = $value->id_createlab;
                    }

                    $results = DB::table('nt_oqa_lab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->whereIN('nt_oqa_lab.id_createlab', $idlab)
                    ->get();
                    
                    foreach ($results as $key => $value) {
                        $nameandpart[] = $value->part_number;
                    }   
                    $namemodel = array_unique($nameandpart);
                    
                    $textname = '';
                    if(count($namemodel) > 0){
                        foreach ($namemodel as $key => $value) {
                            $textname .= "<p>".$value."</p>";
                        }
                    }
                    return $textname;
                    
                })
                ->addColumn('countdata',function($data){
                    $results = DB::table('nt_oqa_lab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.drive_name', $data->drive_name)
                    ->where('nt_oqa_lab.save_date', '<=', $data->enddate)
                    ->orderby('nt_oqa_lab.save_date', 'DESC')
                    ->get(['nt_oqa_lab.save_date as first_date', 'nt_model.name_model', 'nt_oqa_lab.name_lab', 'nt_model.model_type', 'nt_model.drive_name']);
                    
                    return count($results);
                })
                ->addColumn('firstdate',function($data){    
                    
                    $model_type = "SGT_TNHK";
                    if($data->model_type != "SGT_TNHK"){
                        $model_type = "SGT_Oversea";
                    }

                    $getlast = DB::table('nt_oqa_lab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.drive_name', $data->drive_name)
                    ->where('nt_model.model_type', $model_type)
                    ->whereBetween('nt_oqa_lab.save_date', [$data->startdate,$data->enddate])
                    ->orderBy('nt_oqa_lab.save_date', 'DESC')
                    ->get();

                    $idlab = [];
                    foreach ($getlast as $key => $value) {
                        $idlab[] = $value->id_createlab;
                    }

                    $dates = DB::table('nt_oqa_lab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->whereIN('nt_oqa_lab.id_createlab', $idlab)
                    ->selectRaw('MIN(nt_oqa_lab.save_date) as earliest_date, MAX(nt_oqa_lab.save_date) as latest_date')
                    ->first();
                     
                    $start = $dates->earliest_date; // วันที่เก่าสุด
                    return date('Y-m-d', strtotime($start));

                })
                ->addColumn('lastdate',function($data){  
                     
                    $model_type = "SGT_TNHK";
                    if($data->model_type != "SGT_TNHK"){
                        $model_type = "SGT_Oversea";
                    }

                    $getlast = DB::table('nt_oqa_lab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.drive_name', $data->drive_name)
                    ->where('nt_model.model_type', $model_type)
                    ->whereBetween('nt_oqa_lab.save_date', [$data->startdate,$data->enddate])
                    ->orderBy('nt_oqa_lab.save_date', 'DESC')
                    ->get();

                    $idlab = [];
                    foreach ($getlast as $key => $value) {
                        $idlab[] = $value->id_createlab;
                    }

                    $dates = DB::table('nt_oqa_lab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->whereIN('nt_oqa_lab.id_createlab', $idlab)
                    ->selectRaw('MIN(nt_oqa_lab.save_date) as earliest_date, MAX(nt_oqa_lab.save_date) as latest_date')
                    ->first();
                     
                    $end = $dates->latest_date;   // วันที่ใหม่สุด
                    return date('Y-m-d', strtotime($end));

                })
                ->addColumn('lpccount',function($data){
                   
                    $model_type = "SGT_TNHK";
                    if($data->model_type != "SGT_TNHK"){
                        $model_type = "SGT_Oversea";
                    }
                     
                    $getlast = DB::table('nt_data_lpc')
                    ->join('nt_oqa_lab', 'nt_data_lpc.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.drive_name', $data->drive_name)
                    ->where('nt_model.model_type', $model_type)
                    ->whereBetween('nt_oqa_lab.save_date', [$data->startdate,$data->enddate])
                    ->orderBy('nt_oqa_lab.save_date', 'DESC')
                    ->get();

                    $idlab = [];
                    foreach ($getlast as $key => $value) {
                        $idlab[] = $value->id_createlab;
                    }

                    $dates = DB::table('nt_data_lpc')
                    ->join('nt_oqa_lab', 'nt_data_lpc.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->whereIN('nt_oqa_lab.id_createlab', $idlab)
                    ->selectRaw('MIN(nt_oqa_lab.save_date) as earliest_date, MAX(nt_oqa_lab.save_date) as latest_date')
                    ->first();

                    $start = $dates->earliest_date; // วันที่เก่าสุด
                    $end = $dates->latest_date;   // วันที่ใหม่สุด
                   
                    $lastdata = DB::table('nt_data_lpc')
                    ->join('nt_oqa_lab', 'nt_data_lpc.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.drive_name', $data->drive_name)
                    ->where('nt_model.model_type', $model_type)
                    ->wherebetween('nt_oqa_lab.save_date', [$start,$end]) 
                    ->orderBy('nt_oqa_lab.save_date', 'DESC')
                    ->count();

                    return @$lastdata;

                })
                ->addColumn('semcount',function($data){

                    $model_type = "SGT_TNHK";
                    if($data->model_type != "SGT_TNHK"){
                        $model_type = "SGT_Oversea";
                    }
                     
                    $getlast = DB::table('nt_data_sem')
                    ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.drive_name', $data->drive_name)
                    ->where('nt_model.model_type', $model_type)
                    ->whereBetween('nt_oqa_lab.save_date', [$data->startdate,$data->enddate])
                    ->orderBy('nt_oqa_lab.save_date', 'DESC')
                    ->get();

                    $idlab = [];
                    foreach ($getlast as $key => $value) {
                        $idlab[] = $value->id_createlab;
                    }

                    $dates = DB::table('nt_data_sem')
                    ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->whereIN('nt_oqa_lab.id_createlab', $idlab)
                    ->selectRaw('MIN(nt_oqa_lab.save_date) as earliest_date, MAX(nt_oqa_lab.save_date) as latest_date')
                    ->first();

                    $start = $dates->earliest_date; // วันที่เก่าสุด
                    $end = $dates->latest_date;   // วันที่ใหม่สุด
                   
                    $lastdata = DB::table('nt_data_sem')
                    ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.drive_name', $data->drive_name)
                    ->where('nt_model.model_type', $model_type)
                    ->wherebetween('nt_oqa_lab.save_date', [$start,$end]) 
                    ->orderBy('nt_oqa_lab.save_date', 'DESC')
                    ->count();

                    return @$lastdata;

                })->addColumn('iccount',function($data){
                    
                    $model_type = "SGT_TNHK";
                    if($data->model_type != "SGT_TNHK"){
                        $model_type = "SGT_Oversea";
                    }
                     
                    $getlast = DB::table('nt_data_ic')
                    ->join('nt_oqa_lab', 'nt_data_ic.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.drive_name', $data->drive_name)
                    ->where('nt_model.model_type', $model_type)
                    ->whereBetween('nt_oqa_lab.save_date', [$data->startdate,$data->enddate])
                    ->orderBy('nt_oqa_lab.save_date', 'DESC')
                    ->get();

                    $idlab = [];
                    foreach ($getlast as $key => $value) {
                        $idlab[] = $value->id_createlab;
                    }

                    $dates = DB::table('nt_data_ic')
                    ->join('nt_oqa_lab', 'nt_data_ic.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->whereIN('nt_oqa_lab.id_createlab', $idlab)
                    ->selectRaw('MIN(nt_oqa_lab.save_date) as earliest_date, MAX(nt_oqa_lab.save_date) as latest_date')
                    ->first();

                    $start = $dates->earliest_date; // วันที่เก่าสุด
                    $end = $dates->latest_date;   // วันที่ใหม่สุด
                   
                    $lastdata = DB::table('nt_data_ic')
                    ->join('nt_oqa_lab', 'nt_data_ic.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.drive_name', $data->drive_name)
                    ->where('nt_model.model_type', $model_type)
                    ->wherebetween('nt_oqa_lab.save_date', [$start,$end]) 
                    ->orderBy('nt_oqa_lab.save_date', 'DESC')
                    ->count();

                    return @$lastdata;
                   
                })->addColumn('gcmscount',function($data){
                    
                    $model_type = "SGT_TNHK";
                    if($data->model_type != "SGT_TNHK"){
                        $model_type = "SGT_Oversea";
                    }

                    $getlast = DB::table('nt_data_gcms')
                    ->join('nt_oqa_lab', 'nt_data_gcms.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.drive_name', $data->drive_name)
                    ->where('nt_model.model_type', $model_type)
                    ->whereBetween('nt_oqa_lab.save_date', [$data->startdate,$data->enddate])
                    ->orderBy('nt_oqa_lab.save_date', 'DESC')
                    ->get();

                    $idlab = [];
                    foreach ($getlast as $key => $value) {
                        $idlab[] = $value->id_createlab;
                    }

                    $dates = DB::table('nt_data_gcms')
                    ->join('nt_oqa_lab', 'nt_data_gcms.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->whereIN('nt_oqa_lab.id_createlab', $idlab)
                    ->selectRaw('MIN(nt_oqa_lab.save_date) as earliest_date, MAX(nt_oqa_lab.save_date) as latest_date')
                    ->first();

                    $start = $dates->earliest_date; // วันที่เก่าสุด
                    $end = $dates->latest_date;   // วันที่ใหม่สุด
                   
                    $lastdata = DB::table('nt_data_gcms')
                    ->join('nt_oqa_lab', 'nt_data_gcms.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.drive_name', $data->drive_name)
                    ->where('nt_model.model_type', $model_type)
                    ->wherebetween('nt_oqa_lab.save_date', [$start,$end]) 
                    ->orderBy('nt_oqa_lab.save_date', 'DESC')
                    ->count();

                    return @$lastdata;

                })->addColumn('ftircount',function($data){
                    
                    $model_type = "SGT_TNHK";
                    if($data->model_type != "SGT_TNHK"){
                        $model_type = "SGT_Oversea";
                    }

                    $getlast = DB::table('nt_data_ftir')
                    ->join('nt_oqa_lab', 'nt_data_ftir.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.drive_name', $data->drive_name)
                    ->where('nt_model.model_type', $model_type)
                    ->whereBetween('nt_oqa_lab.save_date', [$data->startdate,$data->enddate])
                    ->orderBy('nt_oqa_lab.save_date', 'DESC')
                    ->get();

                    $idlab = [];
                    foreach ($getlast as $key => $value) {
                        $idlab[] = $value->id_createlab;
                    }

                    $dates = DB::table('nt_data_ftir')
                    ->join('nt_oqa_lab', 'nt_data_ftir.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->whereIN('nt_oqa_lab.id_createlab', $idlab)
                    ->selectRaw('MIN(nt_oqa_lab.save_date) as earliest_date, MAX(nt_oqa_lab.save_date) as latest_date')
                    ->first();

                    $start = $dates->earliest_date; // วันที่เก่าสุด
                    $end = $dates->latest_date;   // วันที่ใหม่สุด
                   
                    $lastdata = DB::table('nt_data_ftir')
                    ->join('nt_oqa_lab', 'nt_data_ftir.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.drive_name', $data->drive_name)
                    ->where('nt_model.model_type', $model_type)
                    ->wherebetween('nt_oqa_lab.save_date', [$start,$end]) 
                    ->orderBy('nt_oqa_lab.save_date', 'DESC')
                    ->count();

                    return @$lastdata;

                })
                ->addColumn('lpccount_idlab',function($data){
                    $model_type = "SGT_TNHK";
                    if($data->model_type != "SGT_TNHK"){
                        $model_type = "SGT_Oversea";
                    }

                    $getlast = DB::table('nt_data_lpc')
                    ->join('nt_oqa_lab', 'nt_data_lpc.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.drive_name', $data->drive_name)
                    ->where('nt_model.model_type', $model_type)
                    ->whereBetween('nt_oqa_lab.save_date', [$data->startdate,$data->enddate])
                    ->orderBy('nt_oqa_lab.save_date', 'DESC')
                    ->get();
                   
                    if ($getlast->isNotEmpty()) {

                        $idlab = [];
                        foreach ($getlast as $key => $value) {
                            $idlab[] = $value->id_createlab;
                        }
                        $dates = DB::table('nt_data_lpc')
                        ->join('nt_oqa_lab', 'nt_data_lpc.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->whereIN('nt_oqa_lab.id_createlab', $idlab)
                        ->selectRaw('MIN(nt_oqa_lab.save_date) as earliest_date, MAX(nt_oqa_lab.save_date) as latest_date')
                        ->first();

                        $start = $dates->earliest_date; // วันที่เก่าสุด
                        $end = $dates->latest_date;   // วันที่ใหม่สุด
                    
                        $lastdata = DB::table('nt_data_lpc')
                        ->join('nt_oqa_lab', 'nt_data_lpc.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.drive_name', $data->drive_name)
                        ->where('nt_model.model_type', $model_type)
                        ->wherebetween('nt_oqa_lab.save_date', [$start,$end]) 
                        ->orderBy('nt_oqa_lab.save_date', 'DESC')
                        ->get();
                         
                        foreach ($lastdata as $key => $value) {
                            $arr[] = $value->id_createlab;
                        }
                        $result1 = array_unique( $arr );
                        return implode(",",$result1);
                    }

                    return null;

                })
                ->addColumn('semcount_idlab',function($data){
                    $model_type = "SGT_TNHK";
                    if($data->model_type != "SGT_TNHK"){
                        $model_type = "SGT_Oversea";
                    }

                    $getlast = DB::table('nt_data_sem')
                    ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.drive_name', $data->drive_name)
                    ->where('nt_model.model_type', $model_type)
                    ->whereBetween('nt_oqa_lab.save_date', [$data->startdate,$data->enddate])
                    ->orderBy('nt_oqa_lab.save_date', 'DESC')
                    ->get();
                   
                    if ($getlast->isNotEmpty()) {

                        $idlab = [];
                        foreach ($getlast as $key => $value) {
                            $idlab[] = $value->id_createlab;
                        }
                        $dates = DB::table('nt_data_sem')
                        ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->whereIN('nt_oqa_lab.id_createlab', $idlab)
                        ->selectRaw('MIN(nt_oqa_lab.save_date) as earliest_date, MAX(nt_oqa_lab.save_date) as latest_date')
                        ->first();

                        $start = $dates->earliest_date; // วันที่เก่าสุด
                        $end = $dates->latest_date;   // วันที่ใหม่สุด
                    
                        $lastdata = DB::table('nt_data_sem')
                        ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.drive_name', $data->drive_name)
                        ->where('nt_model.model_type', $model_type)
                        ->wherebetween('nt_oqa_lab.save_date', [$start,$end]) 
                        ->orderBy('nt_oqa_lab.save_date', 'DESC')
                        ->get();
                         
                        foreach ($lastdata as $key => $value) {
                            $arr[] = $value->id_createlab;
                        }
                        $result1 = array_unique( $arr );
                        return implode(",",$result1);
                    } 
                    return null; 
                })
                ->addColumn('iccount_idlab',function($data){
                    $model_type = "SGT_TNHK";
                    if($data->model_type != "SGT_TNHK"){
                        $model_type = "SGT_Oversea";
                    }

                    $getlast = DB::table('nt_data_ic')
                    ->join('nt_oqa_lab', 'nt_data_ic.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.drive_name', $data->drive_name)
                    ->where('nt_model.model_type', $model_type)
                    ->whereBetween('nt_oqa_lab.save_date', [$data->startdate,$data->enddate])
                    ->orderBy('nt_oqa_lab.save_date', 'DESC')
                    ->get();
                   
                    if ($getlast->isNotEmpty()) {

                        $idlab = [];
                        foreach ($getlast as $key => $value) {
                            $idlab[] = $value->id_createlab;
                        }
                        $dates = DB::table('nt_data_ic')
                        ->join('nt_oqa_lab', 'nt_data_ic.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->whereIN('nt_oqa_lab.id_createlab', $idlab)
                        ->selectRaw('MIN(nt_oqa_lab.save_date) as earliest_date, MAX(nt_oqa_lab.save_date) as latest_date')
                        ->first();

                        $start = $dates->earliest_date; // วันที่เก่าสุด
                        $end = $dates->latest_date;   // วันที่ใหม่สุด
                    
                        $lastdata = DB::table('nt_data_ic')
                        ->join('nt_oqa_lab', 'nt_data_ic.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.drive_name', $data->drive_name)
                        ->where('nt_model.model_type', $model_type)
                        ->wherebetween('nt_oqa_lab.save_date', [$start,$end]) 
                        ->orderBy('nt_oqa_lab.save_date', 'DESC')
                        ->get();
                         
                        foreach ($lastdata as $key => $value) {
                            $arr[] = $value->id_createlab;
                        }
                        $result1 = array_unique( $arr );
                        return implode(",",$result1);
                    } 
                    return null;

                })
                ->addColumn('gcmscount_idlab',function($data){
                    $model_type = "SGT_TNHK";
                    if($data->model_type != "SGT_TNHK"){
                        $model_type = "SGT_Oversea";
                    }

                    $getlast = DB::table('nt_data_gcms')
                    ->join('nt_oqa_lab', 'nt_data_gcms.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.drive_name', $data->drive_name)
                    ->where('nt_model.model_type', $model_type)
                    ->whereBetween('nt_oqa_lab.save_date', [$data->startdate,$data->enddate])
                    ->orderBy('nt_oqa_lab.save_date', 'DESC')
                    ->get();
                   
                    if ($getlast->isNotEmpty()) {

                        $idlab = [];
                        foreach ($getlast as $key => $value) {
                            $idlab[] = $value->id_createlab;
                        }
                        $dates = DB::table('nt_data_gcms')
                        ->join('nt_oqa_lab', 'nt_data_gcms.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->whereIN('nt_oqa_lab.id_createlab', $idlab)
                        ->selectRaw('MIN(nt_oqa_lab.save_date) as earliest_date, MAX(nt_oqa_lab.save_date) as latest_date')
                        ->first();

                        $start = $dates->earliest_date; // วันที่เก่าสุด
                        $end = $dates->latest_date;   // วันที่ใหม่สุด
                    
                        $lastdata = DB::table('nt_data_gcms')
                        ->join('nt_oqa_lab', 'nt_data_gcms.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.drive_name', $data->drive_name)
                        ->where('nt_model.model_type', $model_type)
                        ->wherebetween('nt_oqa_lab.save_date', [$start,$end]) 
                        ->orderBy('nt_oqa_lab.save_date', 'DESC')
                        ->get();
                         
                        foreach ($lastdata as $key => $value) {
                            $arr[] = $value->id_createlab;
                        }
                        $result1 = array_unique( $arr );
                        return implode(",",$result1);
                    } 
                    return null;

                })
                ->addColumn('ftircount_idlab',function($data){
                    $model_type = "SGT_TNHK";
                    if($data->model_type != "SGT_TNHK"){
                        $model_type = "SGT_Oversea";
                    }

                    $getlast = DB::table('nt_data_ftir')
                    ->join('nt_oqa_lab', 'nt_data_ftir.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.drive_name', $data->drive_name)
                    ->where('nt_model.model_type', $model_type)
                    ->whereBetween('nt_oqa_lab.save_date', [$data->startdate,$data->enddate])
                    ->orderBy('nt_oqa_lab.save_date', 'DESC')
                    ->get();
                   
                    if ($getlast->isNotEmpty()) {

                        $idlab = [];
                        foreach ($getlast as $key => $value) {
                            $idlab[] = $value->id_createlab;
                        }
                        $dates = DB::table('nt_data_ftir')
                        ->join('nt_oqa_lab', 'nt_data_ftir.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->whereIN('nt_oqa_lab.id_createlab', $idlab)
                        ->selectRaw('MIN(nt_oqa_lab.save_date) as earliest_date, MAX(nt_oqa_lab.save_date) as latest_date')
                        ->first();

                        $start = $dates->earliest_date; // วันที่เก่าสุด
                        $end = $dates->latest_date;   // วันที่ใหม่สุด
                    
                        $lastdata = DB::table('nt_data_ftir')
                        ->join('nt_oqa_lab', 'nt_data_ftir.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.drive_name', $data->drive_name)
                        ->where('nt_model.model_type', $model_type)
                        ->wherebetween('nt_oqa_lab.save_date', [$start,$end]) 
                        ->orderBy('nt_oqa_lab.save_date', 'DESC')
                        ->get();
                         
                        foreach ($lastdata as $key => $value) {
                            $arr[] = $value->id_createlab;
                        }
                        $result1 = array_unique( $arr );
                        return implode(",",$result1);
                    } 
                    return null;

                });
                

            return $sQuery->escapeColumns([])->make(true);

        }elseif(!empty($request->number) && empty(!$request->date_end)){

            $dateforchecklog = date('Y-m-d 00:00:00', strtotime('2022-07-23'));
            $start = "'$dateforchecklog'";

            $dateend = date('Y-m-d 23:59:00', strtotime($request->date_end));
            $end = "'$dateend'";

            $number = ($request->number)? $request->number : 0;

            $firstget = DB::table('nt_oqa_lab')
            ->where('save_date', '>', '2022-07-23 00:00:00')
            ->get();
            $id_model = [];
            foreach ($firstget as $key => $_first) {
                $id_model[] = $_first->id_model;
            }
            $check_id_model = array_unique( $id_model );
            
            $datalist = DB::table('nt_model')
            ->select('drive_name', 'model_type', DB::raw('MAX(id_model) as id_model'), DB::raw(''.$start .' as startdate'),DB::raw(''.$end .' as enddate'),DB::raw(''.$number .' as number'))
            ->where('status_model', 1)
            ->where('drive_name', '!=', '-')
            ->whereIn('id_model', $check_id_model)
            ->groupBy('drive_name', 'model_type')
            ->orderBy('drive_name', 'asc')
            ->orderBy('model_type', 'asc')
            ->get();
            $sQuery  = Datatables::of($datalist)
                ->addIndexColumn()
                ->addColumn('indexs',function($data){
                    
                    $results = DB::table('nt_oqa_lab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.drive_name', $data->drive_name)
                    ->where('nt_oqa_lab.save_date', '<=', $data->enddate)
                    ->orderby('nt_oqa_lab.save_date', 'DESC')
                    ->limit($data->number) 
                    ->get(['nt_oqa_lab.save_date as first_date', 'nt_model.name_model', 'nt_oqa_lab.name_lab', 'nt_model.model_type', 'nt_model.drive_name']);

                    $sortedResults = $results->sortBy('model_type')->values();
                    $earliestDate = $sortedResults->last()->model_type;

                    if($earliestDate == "SGT_TNHK"){
                        $name = "TNHK";
                    }else{
                        $name = "JNHK";
                    }

                    return $data->drive_name.'_'.$name;

                })
                ->addColumn('site',function($data){
                    if($data->model_type == "SGT_TNHK"){
                        $name = "TNHK";
                    }else{
                        $name = "JNHK";
                    }
                    return $name;
                })
                ->addColumn('model',function($data){
                    
                    $nameandpart = [];
                    $model_type = "SGT_TNHK";
                    if($data->model_type != "SGT_TNHK"){
                        $model_type = "SGT_Oversea";
                    }
                     
                    $getlast = DB::table('nt_oqa_lab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.drive_name', $data->drive_name)
                    ->where('nt_model.model_type', $model_type)
                    ->where('nt_oqa_lab.save_date', '<=', $data->enddate)
                    ->limit($data->number)
                    ->orderBy('nt_oqa_lab.save_date', 'DESC')
                    ->get();

                    $idlab = [];
                    foreach ($getlast as $key => $value) {
                        $idlab[] = $value->id_createlab;
                    }

                    $results = DB::table('nt_oqa_lab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->whereIN('nt_oqa_lab.id_createlab', $idlab)
                    ->get();

                    foreach ($results as $key => $value) {
                        $nameandpart[] = $value->name_model;
                    }   
                    $namemodel = array_unique($nameandpart);
                    
                    $textname = '';
                    if(count($namemodel) > 0){
                        foreach ($namemodel as $key => $value) {
                            $textname .= "<p>".$value."</p>";
                        }
                    }
                    return $textname;

                })
                ->addColumn('partnumber',function($data){
                    $model_type = "SGT_TNHK";
                    if($data->model_type != "SGT_TNHK"){
                        $model_type = "SGT_Oversea";
                    }
                    $nameandpart = [];
                    $getlast = DB::table('nt_oqa_lab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.drive_name', $data->drive_name)
                    ->where('nt_model.model_type', $model_type)
                    ->where('nt_oqa_lab.save_date', '<=', $data->enddate)
                    ->limit($data->number)
                    ->orderBy('nt_oqa_lab.save_date', 'DESC')
                    ->get();

                    $idlab = [];
                    foreach ($getlast as $key => $value) {
                        $idlab[] = $value->id_createlab;
                    }

                    $results = DB::table('nt_oqa_lab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->whereIN('nt_oqa_lab.id_createlab', $idlab)
                    ->get();
                    
                    foreach ($results as $key => $value) {
                        $nameandpart[] = $value->part_number;
                    }   
                    $namemodel = array_unique($nameandpart);
                    
                    $textname = '';
                    if(count($namemodel) > 0){
                        foreach ($namemodel as $key => $value) {
                            $textname .= "<p>".$value."</p>";
                        }
                    }
                    return $textname;
                    
                })
                ->addColumn('countdata',function($data){
                    $results = DB::table('nt_oqa_lab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.drive_name', $data->drive_name)
                    ->where('nt_oqa_lab.save_date', '<=', $data->enddate)
                    ->orderby('nt_oqa_lab.save_date', 'DESC')
                    ->limit($data->number) 
                    ->get(['nt_oqa_lab.save_date as first_date', 'nt_model.name_model', 'nt_oqa_lab.name_lab', 'nt_model.model_type', 'nt_model.drive_name']);
                    
                    return count($results);
                })
                ->addColumn('firstdate',function($data){    
                    
                    $model_type = "SGT_TNHK";
                    if($data->model_type != "SGT_TNHK"){
                        $model_type = "SGT_Oversea";
                    }

                    $getlast = DB::table('nt_oqa_lab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.drive_name', $data->drive_name)
                    ->where('nt_model.model_type', $model_type)
                    ->where('nt_oqa_lab.save_date', '<=', $data->enddate)
                    ->limit($data->number)
                    ->orderBy('nt_oqa_lab.save_date', 'DESC')
                    ->get();

                    $idlab = [];
                    foreach ($getlast as $key => $value) {
                        $idlab[] = $value->id_createlab;
                    }

                    $dates = DB::table('nt_oqa_lab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->whereIN('nt_oqa_lab.id_createlab', $idlab)
                    ->selectRaw('MIN(nt_oqa_lab.save_date) as earliest_date, MAX(nt_oqa_lab.save_date) as latest_date')
                    ->first();
                     
                    $start = $dates->earliest_date; // วันที่เก่าสุด
                    return date('Y-m-d', strtotime($start));

                })
                ->addColumn('lastdate',function($data){  
                     
                    $model_type = "SGT_TNHK";
                    if($data->model_type != "SGT_TNHK"){
                        $model_type = "SGT_Oversea";
                    }
                    
                    $getlast = DB::table('nt_oqa_lab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.drive_name', $data->drive_name)
                    ->where('nt_model.model_type', $model_type)
                    ->where('nt_oqa_lab.save_date', '<=', $data->enddate)
                    ->limit($data->number)
                    ->orderBy('nt_oqa_lab.save_date', 'DESC')
                    ->get();

                    $idlab = [];
                    foreach ($getlast as $key => $value) {
                        $idlab[] = $value->id_createlab;
                    }

                    $dates = DB::table('nt_oqa_lab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->whereIN('nt_oqa_lab.id_createlab', $idlab)
                    ->selectRaw('MIN(nt_oqa_lab.save_date) as earliest_date, MAX(nt_oqa_lab.save_date) as latest_date')
                    ->first();
                     
                    $end = $dates->latest_date;   // วันที่ใหม่สุด
                    return date('Y-m-d', strtotime($end));

                })
                ->addColumn('lpccount',function($data){
                    $model_type = "SGT_TNHK";
                    if($data->model_type != "SGT_TNHK"){
                        $model_type = "SGT_Oversea";
                    }
                     
                    $getlast = DB::table('nt_data_lpc')
                    ->join('nt_oqa_lab', 'nt_data_lpc.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.drive_name', $data->drive_name)
                    ->where('nt_model.model_type', $model_type)
                    ->where('nt_oqa_lab.save_date', '<=', $data->enddate)
                    ->limit($data->number)
                    ->orderBy('nt_oqa_lab.save_date', 'DESC')
                    ->get();

                    $idlab = [];
                    foreach ($getlast as $key => $value) {
                        $idlab[] = $value->id_createlab;
                    }

                    $dates = DB::table('nt_data_lpc')
                    ->join('nt_oqa_lab', 'nt_data_lpc.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->whereIN('nt_oqa_lab.id_createlab', $idlab)
                    ->selectRaw('MIN(nt_oqa_lab.save_date) as earliest_date, MAX(nt_oqa_lab.save_date) as latest_date')
                    ->first();

                    $start = $dates->earliest_date; // วันที่เก่าสุด
                    $end = $dates->latest_date;   // วันที่ใหม่สุด
                   
                    $lastdata = DB::table('nt_data_lpc')
                    ->join('nt_oqa_lab', 'nt_data_lpc.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.drive_name', $data->drive_name)
                    ->where('nt_model.model_type', $model_type)
                    ->wherebetween('nt_oqa_lab.save_date', [$start,$end]) 
                    ->orderBy('nt_oqa_lab.save_date', 'DESC')
                    ->count();

                    return @$lastdata;

                })
                ->addColumn('semcount',function($data){

                    $model_type = "SGT_TNHK";
                    if($data->model_type != "SGT_TNHK"){
                        $model_type = "SGT_Oversea";
                    }

                    $getlast = DB::table('nt_data_sem')
                    ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.drive_name', $data->drive_name)
                    ->where('nt_model.model_type', $model_type)
                    ->where('nt_oqa_lab.save_date', '<=', $data->enddate)
                    ->limit($data->number)
                    ->orderBy('nt_oqa_lab.save_date', 'DESC')
                    ->get();
                   
                    if ($getlast->isNotEmpty()) {

                        $idlab = [];
                        foreach ($getlast as $key => $value) {
                            $idlab[] = $value->id_createlab;
                        }
                        $dates = DB::table('nt_data_sem')
                        ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->whereIN('nt_oqa_lab.id_createlab', $idlab)
                        ->selectRaw('MIN(nt_oqa_lab.save_date) as earliest_date, MAX(nt_oqa_lab.save_date) as latest_date')
                        ->first();

                        $start = $dates->earliest_date; // วันที่เก่าสุด
                        $end = $dates->latest_date;   // วันที่ใหม่สุด
                    
                        $lastdata = DB::table('nt_data_sem')
                        ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.drive_name', $data->drive_name)
                        ->where('nt_model.model_type', $model_type)
                        ->wherebetween('nt_oqa_lab.save_date', [$start,$end]) 
                        ->orderBy('nt_oqa_lab.save_date', 'DESC')
                        ->count();
                        
                        return @$lastdata;

                    }else{ 
                        return '-'; 
                    } 

                })->addColumn('iccount',function($data){
                    
                    $model_type = "SGT_TNHK";
                    if($data->model_type != "SGT_TNHK"){
                        $model_type = "SGT_Oversea";
                    }

                    $getlast = DB::table('nt_data_ic')
                    ->join('nt_oqa_lab', 'nt_data_ic.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.drive_name', $data->drive_name)
                    ->where('nt_model.model_type', $model_type)
                    ->where('nt_oqa_lab.save_date', '<=', $data->enddate)
                    ->limit($data->number)
                    ->orderBy('nt_oqa_lab.save_date', 'DESC')
                    ->get();
                   
                    if ($getlast->isNotEmpty()) {

                        $idlab = [];
                        foreach ($getlast as $key => $value) {
                            $idlab[] = $value->id_createlab;
                        }
                        $dates = DB::table('nt_data_ic')
                        ->join('nt_oqa_lab', 'nt_data_ic.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->whereIN('nt_oqa_lab.id_createlab', $idlab)
                        ->selectRaw('MIN(nt_oqa_lab.save_date) as earliest_date, MAX(nt_oqa_lab.save_date) as latest_date')
                        ->first();

                        $start = $dates->earliest_date; // วันที่เก่าสุด
                        $end = $dates->latest_date;   // วันที่ใหม่สุด
                    
                        $lastdata = DB::table('nt_data_ic')
                        ->join('nt_oqa_lab', 'nt_data_ic.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.drive_name', $data->drive_name)
                        ->where('nt_model.model_type', $model_type)
                        ->wherebetween('nt_oqa_lab.save_date', [$start,$end]) 
                        ->orderBy('nt_oqa_lab.save_date', 'DESC')
                        ->count();
                        
                        return @$lastdata;

                    }else{ 
                        return '-'; 
                    } 

                })->addColumn('gcmscount',function($data){
                    
                    $model_type = "SGT_TNHK";
                    if($data->model_type != "SGT_TNHK"){
                        $model_type = "SGT_Oversea";
                    }
                        
                    $getlast = DB::table('nt_data_gcms')
                    ->join('nt_oqa_lab', 'nt_data_gcms.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.drive_name', $data->drive_name)
                    ->where('nt_model.model_type', $model_type)
                    ->where('nt_oqa_lab.save_date', '<=', $data->enddate)
                    ->limit($data->number)
                    ->orderBy('nt_oqa_lab.save_date', 'DESC')
                    ->get();
                   
                    if ($getlast->isNotEmpty()) {

                        $idlab = [];
                        foreach ($getlast as $key => $value) {
                            $idlab[] = $value->id_createlab;
                        }
                        $dates = DB::table('nt_data_gcms')
                        ->join('nt_oqa_lab', 'nt_data_gcms.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->whereIN('nt_oqa_lab.id_createlab', $idlab)
                        ->selectRaw('MIN(nt_oqa_lab.save_date) as earliest_date, MAX(nt_oqa_lab.save_date) as latest_date')
                        ->first();

                        $start = $dates->earliest_date; // วันที่เก่าสุด
                        $end = $dates->latest_date;   // วันที่ใหม่สุด
                    
                        $lastdata = DB::table('nt_data_gcms')
                        ->join('nt_oqa_lab', 'nt_data_gcms.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.drive_name', $data->drive_name)
                        ->where('nt_model.model_type', $model_type)
                        ->wherebetween('nt_oqa_lab.save_date', [$start,$end]) 
                        ->orderBy('nt_oqa_lab.save_date', 'DESC')
                        ->count();
                        
                        return @$lastdata;

                    }else{ 
                        return '-'; 
                    } 

                })->addColumn('ftircount',function($data){
                    
                    $model_type = "SGT_TNHK";
                    if($data->model_type != "SGT_TNHK"){
                        $model_type = "SGT_Oversea";
                    }

                    $getlast = DB::table('nt_data_ftir')
                    ->join('nt_oqa_lab', 'nt_data_ftir.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.drive_name', $data->drive_name)
                    ->where('nt_model.model_type', $model_type)
                    ->where('nt_oqa_lab.save_date', '<=', $data->enddate)
                    ->limit($data->number)
                    ->orderBy('nt_oqa_lab.save_date', 'DESC')
                    ->get();
                   
                    if ($getlast->isNotEmpty()) {

                        $idlab = [];
                        foreach ($getlast as $key => $value) {
                            $idlab[] = $value->id_createlab;
                        }
                        $dates = DB::table('nt_data_ftir')
                        ->join('nt_oqa_lab', 'nt_data_ftir.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->whereIN('nt_oqa_lab.id_createlab', $idlab)
                        ->selectRaw('MIN(nt_oqa_lab.save_date) as earliest_date, MAX(nt_oqa_lab.save_date) as latest_date')
                        ->first();

                        $start = $dates->earliest_date; // วันที่เก่าสุด
                        $end = $dates->latest_date;   // วันที่ใหม่สุด
                    
                        $lastdata = DB::table('nt_data_ftir')
                        ->join('nt_oqa_lab', 'nt_data_ftir.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.drive_name', $data->drive_name)
                        ->where('nt_model.model_type', $model_type)
                        ->wherebetween('nt_oqa_lab.save_date', [$start,$end]) 
                        ->orderBy('nt_oqa_lab.save_date', 'DESC')
                        ->count();
                        
                        return @$lastdata;

                    }else{ 
                        return '-'; 
                    } 

                })
                ->addColumn('lpccount_idlab',function($data){
                      
                    $model_type = "SGT_TNHK";
                    if($data->model_type != "SGT_TNHK"){
                        $model_type = "SGT_Oversea";
                    }

                    $getlast = DB::table('nt_data_lpc')
                    ->join('nt_oqa_lab', 'nt_data_lpc.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.drive_name', $data->drive_name)
                    ->where('nt_model.model_type', $model_type)
                    ->where('nt_oqa_lab.save_date', '<=', $data->enddate)
                    ->limit($data->number)
                    ->orderBy('nt_oqa_lab.save_date', 'DESC')
                    ->get();
                   
                    if ($getlast->isNotEmpty()) {

                        $idlab = [];
                        foreach ($getlast as $key => $value) {
                            $idlab[] = $value->id_createlab;
                        }
                        $dates = DB::table('nt_data_lpc')
                        ->join('nt_oqa_lab', 'nt_data_lpc.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->whereIN('nt_oqa_lab.id_createlab', $idlab)
                        ->selectRaw('MIN(nt_oqa_lab.save_date) as earliest_date, MAX(nt_oqa_lab.save_date) as latest_date')
                        ->first();

                        $start = $dates->earliest_date; // วันที่เก่าสุด
                        $end = $dates->latest_date;   // วันที่ใหม่สุด
                    
                        $lastdata = DB::table('nt_data_lpc')
                        ->join('nt_oqa_lab', 'nt_data_lpc.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.drive_name', $data->drive_name)
                        ->where('nt_model.model_type', $model_type)
                        ->wherebetween('nt_oqa_lab.save_date', [$start,$end]) 
                        ->orderBy('nt_oqa_lab.save_date', 'DESC')
                        ->get();
                         
                        $arr = [];
                        foreach ($lastdata as $key => $value) {
                            $arr[] = $value->id_createlab;
                        }
                        $result1 = array_unique( $arr );
                        return implode(",",$result1);
                    }
                    return null;

                })
                ->addColumn('semcount_idlab',function($data){
                    $model_type = "SGT_TNHK";
                    if($data->model_type != "SGT_TNHK"){
                        $model_type = "SGT_Oversea";
                    }

                    $getlast = DB::table('nt_data_sem')
                    ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.drive_name', $data->drive_name)
                    ->where('nt_model.model_type', $model_type)
                    ->where('nt_oqa_lab.save_date', '<=', $data->enddate)
                    ->limit($data->number)
                    ->orderBy('nt_oqa_lab.save_date', 'DESC')
                    ->get();
                   
                    if ($getlast->isNotEmpty()) {

                        $idlab = [];
                        foreach ($getlast as $key => $value) {
                            $idlab[] = $value->id_createlab;
                        }
                        $dates = DB::table('nt_data_sem')
                        ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->whereIN('nt_oqa_lab.id_createlab', $idlab)
                        ->selectRaw('MIN(nt_oqa_lab.save_date) as earliest_date, MAX(nt_oqa_lab.save_date) as latest_date')
                        ->first();

                        $start = $dates->earliest_date; // วันที่เก่าสุด
                        $end = $dates->latest_date;   // วันที่ใหม่สุด
                    
                        $lastdata = DB::table('nt_data_sem')
                        ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.drive_name', $data->drive_name)
                        ->where('nt_model.model_type', $model_type)
                        ->wherebetween('nt_oqa_lab.save_date', [$start,$end]) 
                        ->orderBy('nt_oqa_lab.save_date', 'DESC')
                        ->get();
                         
                        $arr = [];
                        foreach ($lastdata as $key => $value) {
                            $arr[] = $value->id_createlab;
                        }
                        $result1 = array_unique( $arr );
                        return implode(",",$result1);
                    }
                    return null;

                })
                ->addColumn('iccount_idlab',function($data){
                    $model_type = "SGT_TNHK";
                    if($data->model_type != "SGT_TNHK"){
                        $model_type = "SGT_Oversea";
                    }

                    $getlast = DB::table('nt_data_ic')
                    ->join('nt_oqa_lab', 'nt_data_ic.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.drive_name', $data->drive_name)
                    ->where('nt_model.model_type', $model_type)
                    ->where('nt_oqa_lab.save_date', '<=', $data->enddate)
                    ->limit($data->number)
                    ->orderBy('nt_oqa_lab.save_date', 'DESC')
                    ->get();
                   
                    if ($getlast->isNotEmpty()) {

                        $idlab = [];
                        foreach ($getlast as $key => $value) {
                            $idlab[] = $value->id_createlab;
                        }
                        $dates = DB::table('nt_data_ic')
                        ->join('nt_oqa_lab', 'nt_data_ic.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->whereIN('nt_oqa_lab.id_createlab', $idlab)
                        ->selectRaw('MIN(nt_oqa_lab.save_date) as earliest_date, MAX(nt_oqa_lab.save_date) as latest_date')
                        ->first();

                        $start = $dates->earliest_date; // วันที่เก่าสุด
                        $end = $dates->latest_date;   // วันที่ใหม่สุด
                    
                        $lastdata = DB::table('nt_data_ic')
                        ->join('nt_oqa_lab', 'nt_data_ic.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.drive_name', $data->drive_name)
                        ->where('nt_model.model_type', $model_type)
                        ->wherebetween('nt_oqa_lab.save_date', [$start,$end]) 
                        ->orderBy('nt_oqa_lab.save_date', 'DESC')
                        ->get();
                         
                        $arr = [];
                        foreach ($lastdata as $key => $value) {
                            $arr[] = $value->id_createlab;
                        }
                        $result1 = array_unique( $arr );
                        return implode(",",$result1);
                    }
                    return null;

                })
                ->addColumn('gcmscount_idlab',function($data){
                    $model_type = "SGT_TNHK";
                    if($data->model_type != "SGT_TNHK"){
                        $model_type = "SGT_Oversea";
                    }
                     
                    $getlast = DB::table('nt_data_gcms')
                    ->join('nt_oqa_lab', 'nt_data_gcms.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.drive_name', $data->drive_name)
                    ->where('nt_model.model_type', $model_type)
                    ->where('nt_oqa_lab.save_date', '<=', $data->enddate)
                    ->limit($data->number)
                    ->orderBy('nt_oqa_lab.save_date', 'DESC')
                    ->get();
                   
                    if ($getlast->isNotEmpty()) {

                        $idlab = [];
                        foreach ($getlast as $key => $value) {
                            $idlab[] = $value->id_createlab;
                        }
                        $dates = DB::table('nt_data_gcms')
                        ->join('nt_oqa_lab', 'nt_data_gcms.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->whereIN('nt_oqa_lab.id_createlab', $idlab)
                        ->selectRaw('MIN(nt_oqa_lab.save_date) as earliest_date, MAX(nt_oqa_lab.save_date) as latest_date')
                        ->first();

                        $start = $dates->earliest_date; // วันที่เก่าสุด
                        $end = $dates->latest_date;   // วันที่ใหม่สุด
                    
                        $lastdata = DB::table('nt_data_gcms')
                        ->join('nt_oqa_lab', 'nt_data_gcms.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.drive_name', $data->drive_name)
                        ->where('nt_model.model_type', $model_type)
                        ->wherebetween('nt_oqa_lab.save_date', [$start,$end]) 
                        ->orderBy('nt_oqa_lab.save_date', 'DESC')
                        ->get();
                         
                        $arr = [];
                        foreach ($lastdata as $key => $value) {
                            $arr[] = $value->id_createlab;
                        }
                        $result1 = array_unique( $arr );
                        return implode(",",$result1);
                    }
                    return null;

                })
                ->addColumn('ftircount_idlab',function($data){
                    $model_type = "SGT_TNHK";
                    if($data->model_type != "SGT_TNHK"){
                        $model_type = "SGT_Oversea";
                    }
                     
                    $getlast = DB::table('nt_data_ftir')
                    ->join('nt_oqa_lab', 'nt_data_ftir.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.drive_name', $data->drive_name)
                    ->where('nt_model.model_type', $model_type)
                    ->where('nt_oqa_lab.save_date', '<=', $data->enddate)
                    ->limit($data->number)
                    ->orderBy('nt_oqa_lab.save_date', 'DESC')
                    ->get();
                   
                    if ($getlast->isNotEmpty()) {

                        $idlab = [];
                        foreach ($getlast as $key => $value) {
                            $idlab[] = $value->id_createlab;
                        }
                        $dates = DB::table('nt_data_ftir')
                        ->join('nt_oqa_lab', 'nt_data_ftir.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->whereIN('nt_oqa_lab.id_createlab', $idlab)
                        ->selectRaw('MIN(nt_oqa_lab.save_date) as earliest_date, MAX(nt_oqa_lab.save_date) as latest_date')
                        ->first();

                        $start = $dates->earliest_date; // วันที่เก่าสุด
                        $end = $dates->latest_date;   // วันที่ใหม่สุด
                    
                        $lastdata = DB::table('nt_data_ftir')
                        ->join('nt_oqa_lab', 'nt_data_ftir.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.drive_name', $data->drive_name)
                        ->where('nt_model.model_type', $model_type)
                        ->wherebetween('nt_oqa_lab.save_date', [$start,$end]) 
                        ->orderBy('nt_oqa_lab.save_date', 'DESC')
                        ->get();
                         
                        $arr = [];
                        foreach ($lastdata as $key => $value) {
                            $arr[] = $value->id_createlab;
                        }
                        $result1 = array_unique( $arr );
                        return implode(",",$result1);
                    }

                    return null;

                });

            return $sQuery->escapeColumns([])->make(true);

        }else{
            $datalist = [];
            $sQuery  = Datatables::of($datalist);
            return $sQuery->escapeColumns([])->make(true);

        } 
    }

    public function showcombinedata(Request $request)
    {

        $full = explode("_", $request->id);
        $drive_name = $full[0];
        if($full[1] == "TNHK"){
            $model_type = "SGT_TNHK";
        }else{
            $model_type = "SGT_Oversea";
        }

        $datestart = date('Y-m-d 00:00:00', strtotime($request->datestart));
        $dateend = date('Y-m-d 00:00:00', strtotime($request->dateend));

        $results = DB::table('nt_model')
        ->join('nt_oqa_lab','nt_oqa_lab.id_model','=','nt_model.id_model')
        ->select('nt_model.id_model','nt_model.model_type','nt_model.drive_name','nt_model.name_model','nt_model.part_number','nt_oqa_lab.id_model','nt_oqa_lab.save_date')
        ->where('nt_model.model_type','=',$model_type)
        ->where('nt_model.drive_name','=',$drive_name)
        ->whereBetween('nt_oqa_lab.save_date', [$datestart, $dateend])
        ->get();

        $id_model = [];
        foreach ($results as $key => $value) {
            $id_model[] = $value->id_model;
        }
        
        $lab = DB::table('nt_oqa_lab')
        ->select('nt_oqa_lab.id_createlab', 'nt_oqa_lab.save_date', 'nt_oqa_lab.id_model')
        ->where('nt_oqa_lab.save_date','<=',$dateend)
        ->whereIN('nt_oqa_lab.id_model',$id_model)
        ->whereBetween('nt_oqa_lab.save_date',  [$datestart, $dateend])
        ->orderBy('nt_oqa_lab.save_date', 'DESC')
        ->get();

        $id_lab = [];
        foreach ($lab as $key => $value) {
            $id_lab[] = $value->id_createlab;
        }
        $countlpc = DB::table('nt_data_lpc')
        ->whereIN('id_createlab',$id_lab)
        ->count();
        $countsem = DB::table('nt_data_sem')
        ->whereIN('id_createlab',$id_lab)
        ->count();
        $countic = DB::table('nt_data_ic')
        ->whereIN('id_createlab',$id_lab)
        ->count();
        $countgcms = DB::table('nt_data_gcms')
        ->whereIN('id_createlab',$id_lab)
        ->count();
        $countftir = DB::table('nt_data_ftir')
        ->whereIN('id_createlab',$id_lab)
        ->count();
        $countsemtest = DB::table('nt_data_semtest')
        ->whereIN('id_createlab',$id_lab)
        ->count();

        return response()->json([
            'lpccount'=>$countlpc,
            'semcount'=>$countsem,
            'iccount'=>$countic,
            'gcmscount'=>$countgcms,
            'ftircount'=>$countftir,
        ]);

    }

    public function lpccombineexport(Request $request)
    {

        $datestart = date('Y-m-d 00:00:00', strtotime($request->start_date));
        $dateend = date('Y-m-d 00:00:00', strtotime($request->end_date));
        $number = $request->numbercount;

        if(!empty($request->id)){
            $dataexport = [];
            foreach ($request->id as $key => $id_model) {

                $id_model = $request->id[$key];
                $id_lab_lpc = explode(",",$request->idlab_lpc[$id_model]);  

                $main = DB::table('nt_model')->where('id_model', $id_model)->first();
                $rest = substr($main->name_model, 0, -2);

                if($main->model_type == "SGT_TNHK"){
                    $Site = "TNHK";
                }else{
                    $Site = "JNHK";
                }

                $text_date_start = 'datestart'.$main->drive_name.'_'.$Site;
                $datestart = $request->$text_date_start;

                $text_date_end = 'dateend'.$main->drive_name.'_'.$Site;
                $dateend = $request->$text_date_end;

                $text_number_lpc = 'lpcount'.$main->drive_name.'_'.$Site;
                $number_lpc = $request->$text_number_lpc;

                $data_lpc_get = DB::table('nt_data_lpc')
                ->join('nt_oqa_lab', 'nt_data_lpc.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                ->whereIn('nt_oqa_lab.id_createlab', $id_lab_lpc)
                ->where('nt_oqa_lab.save_date', '<=', $dateend)
                ->orderBy('nt_oqa_lab.save_date', 'DESC')
                ->select('nt_model.id_model', DB::raw('COUNT(nt_oqa_lab.id_createlab) as total_createlab'))
                ->groupBy('nt_model.id_model')
                ->get();

                if (count($id_lab_lpc) > 0) {
                    foreach ($data_lpc_get as $key => $value) {
                        
                        $count = DB::table('nt_data_lpc')
                        ->join('nt_oqa_lab', 'nt_data_lpc.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $value->id_model)
                        ->whereIn('nt_oqa_lab.id_createlab', $id_lab_lpc)
                        ->orderBy('nt_oqa_lab.save_date', 'DESC')
                        ->count();

                        $dates = DB::table('nt_data_lpc')
                        ->join('nt_oqa_lab', 'nt_data_lpc.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $value->id_model)
                        ->whereIn('nt_oqa_lab.id_createlab', $id_lab_lpc)
                        ->selectRaw('MIN(nt_oqa_lab.save_date) as earliest_date, MAX(nt_oqa_lab.save_date) as latest_date')
                        ->first();

                        $newdata =  array (
                            'id_model' => $value->id_model,
                            'id_lab' => $id_lab_lpc,
                            'count' => $number,
                            'datestart' => $dates->earliest_date, 
                            'dateend' => $dates->latest_date,   
                        );
                        array_push($dataexport,$newdata);
                        
                    }
                } 
            }
        
            $namemodel = array_values(array_unique($dataexport, SORT_REGULAR));
            $namefile = 'Combine_LPC_'.date("YmdHis");
            $file = storage_path('NHK-File/template/report/template_calculation2.xlsx'); // for test on orange host

            Excel::load($file, function ($doc)use($dataexport,$datestart, $dateend) {

                // $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
                // $doc = $reader->load($file);

                $i = 5;
                foreach ($dataexport as $val) {

                    $model = DB::table('nt_model')
                    ->join('nt_model_spec','nt_model_spec.id_model','=','nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->where('nt_model_spec.id_model_type', 1)
                    ->first();

                    $nt_model_spec = DB::table('nt_model_spec')
                    ->join('nt_model_spec_type', 'nt_model_spec.id_model_type', '=', 'nt_model_spec_type.id_model_type')
                    ->where('nt_model_spec.id_model', $val['id_model'])
                    ->where('nt_model_spec.id_model_type', 1)
                    ->first(); 

                    $lpccount = DB::table('nt_data_lpc')
                    ->select('nt_oqa_lab.id_createlab', 'nt_oqa_lab.save_date', 'nt_oqa_lab.id_model')
                    ->join('nt_oqa_lab', 'nt_data_lpc.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                    ->count();

                    $maxdata = DB::table('nt_data_lpc')
                    ->join('nt_oqa_lab', 'nt_data_lpc.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                    ->max('lpc_data');
                    $max = number_format($maxdata, 2, '.', '');

                    $mindata =  DB::table('nt_data_lpc')
                    ->join('nt_oqa_lab', 'nt_data_lpc.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                    ->min('lpc_data');
                    $min = number_format($mindata, 2, '.', '');

                    $meandata =  DB::table('nt_data_lpc')
                    ->join('nt_oqa_lab', 'nt_data_lpc.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                    ->avg('lpc_data');
                    $mean = number_format($meandata, 2, '.', '');
        
                    $stdget = DB::table('nt_data_lpc')
                    ->select(DB::raw('STDDEV_SAMP(nt_data_lpc.lpc_data) as std'))
                    ->join('nt_oqa_lab', 'nt_data_lpc.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                    ->first();
                    $std = number_format($stdget->std, 2, '.', '');   
                    $mean6data = $meandata + (6*$stdget->std);
                    $mean6 = number_format($mean6data, 2, '.', '');

                    $dates = DB::table('nt_data_lpc')
                    ->join('nt_oqa_lab', 'nt_data_lpc.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                    ->selectRaw('MIN(nt_oqa_lab.save_date) as earliest_date, MAX(nt_oqa_lab.save_date) as latest_date')
                    ->first();

                    $firstdate = $dates->earliest_date;
                    $lastdate = $dates->latest_date;

                    $sheet = $doc->setActiveSheetIndexByName('ALPC');
                    $sheet->setCellValue('A'.$i,$model->name_model);
                    $sheet->setCellValue('B'.$i,$model->part);
                    $sheet->setCellValue('C'.$i,$model->part_number);
                    $sheet->setCellValue('D'.$i,$lpccount);
                    $sheet->setCellValue('E'.$i,$max);
                    $sheet->setCellValue('F'.$i,$min);
                    $sheet->setCellValue('G'.$i,$mean);
                    $sheet->setCellValue('H'.$i,$std); 
                    $sheet->setCellValue('I'.$i, date('Y-m-d', strtotime($firstdate)));
                    $sheet->setCellValue('J'.$i, date('Y-m-d', strtotime($lastdate)));
                    $sheet->setCellValue('P'.$i,$nt_model_spec->alpc);
    
                    $i++;

                }

                $doc->setActiveSheetIndexByName('SEM C 1');
                $sheetsem1 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetsem1);

                $doc->setActiveSheetIndexByName('SEM C 2');
                $sheetsem2 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetsem2);

                $doc->setActiveSheetIndexByName('SEM C 3');
                $sheetsem3 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetsem3);

                $doc->setActiveSheetIndexByName('SEM C 3.1');
                $sheetsem4 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetsem4);

                $doc->setActiveSheetIndexByName('SEM C 3.2');
                $sheetsem5 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetsem5);

                $doc->setActiveSheetIndexByName('SEM C 3.3');
                $sheetsem6 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetsem6);

                $doc->setActiveSheetIndexByName('SEM C 4');
                $sheetsem7 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetsem7);

                $doc->setActiveSheetIndexByName('SEM C 5');
                $sheetsem8 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetsem8);

                $doc->setActiveSheetIndexByName('SEM C 5.1');
                $sheetsem9 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetsem9);

                $doc->setActiveSheetIndexByName('SEM C 6');
                $sheetsem10 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetsem10);

                $doc->setActiveSheetIndexByName('Anion');
                $sheetic11 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetic11);

                $doc->setActiveSheetIndexByName('Sulphate');
                $sheetic12 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetic12);

                $doc->setActiveSheetIndexByName('Cation');
                $sheetic13 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetic13);
        
                $doc->setActiveSheetIndexByName('Outgassing');
                $sheetgcms14 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetgcms14);

                $doc->setActiveSheetIndexByName('Low MW');
                $sheetgcms15 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetgcms15);

                $doc->setActiveSheetIndexByName('High MW');
                $sheetgcms16 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetgcms16);

                $doc->setActiveSheetIndexByName('Smear');
                $sheetgcms17 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetgcms17);
            
                $doc->setActiveSheetIndexByName('FTIR');
                $sheetftir18 = $doc->getActiveSheetIndex(0);
                $doc->removeSheetByIndex($sheetftir18);

                // $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($doc, "Xlsx");
                // $writer->save(storage_path('NHK-File/report/calculation/'.$namefile.'.xlsx'));

            })->setFilename($namefile)->store('xlsx', storage_path('NHK-File/report/calculation'));
            
            return response()->json(['success' =>  1,'filename' => $namefile.'.xlsx']);
        }else{
            return response()->json(['success' =>  2]);
        }

    }

    public function semcombineexport(Request $request)
    {

        $datestart = date('Y-m-d 00:00:00', strtotime($request->start_date));
        $dateend = date('Y-m-d 00:00:00', strtotime($request->end_date));
        $number = $request->numbercount;

        if(!empty($request->id)){
            $dataexport = [];
            foreach ($request->id as $key => $id_model) {

                $id_model = $request->id[$key];
                $id_lab_lpc = explode(",",$request->idlab_sem[$id_model]);  

                $main = DB::table('nt_model')->where('id_model', $id_model)->first();
                $rest = substr($main->name_model, 0, -2);

                if($main->model_type == "SGT_TNHK"){
                    $Site = "TNHK";
                }else{
                    $Site = "JNHK";
                }

                $text_date_start = 'datestart'.$main->drive_name.'_'.$Site;
                $datestart = $request->$text_date_start;

                $text_date_end = 'dateend'.$main->drive_name.'_'.$Site;
                $dateend = $request->$text_date_end;

                $text_number_lpc = 'semcount'.$main->drive_name.'_'.$Site;
                $number_lpc = $request->$text_number_lpc;

                $data_lpc_get = DB::table('nt_data_sem')
                ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                ->whereIn('nt_oqa_lab.id_createlab', $id_lab_lpc)
                ->where('nt_oqa_lab.save_date', '<=', $dateend)
                ->orderBy('nt_oqa_lab.save_date', 'DESC')
                ->select('nt_model.id_model', DB::raw('COUNT(nt_oqa_lab.id_createlab) as total_createlab'))
                ->groupBy('nt_model.id_model')
                ->get();

                if (count($id_lab_lpc) > 0) {
                    foreach ($data_lpc_get as $key => $value) {
                        
                        $count = DB::table('nt_data_sem')
                        ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $value->id_model)
                        ->whereIn('nt_oqa_lab.id_createlab', $id_lab_lpc)
                        ->orderBy('nt_oqa_lab.save_date', 'DESC')
                        ->count();

                        $dates = DB::table('nt_data_sem')
                        ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $value->id_model)
                        ->whereIn('nt_oqa_lab.id_createlab', $id_lab_lpc)
                        ->selectRaw('MIN(nt_oqa_lab.save_date) as earliest_date, MAX(nt_oqa_lab.save_date) as latest_date')
                        ->first();

                        $newdata =  array (
                            'id_model' => $value->id_model,
                            'id_lab' => $id_lab_lpc,
                            'count' => $number,
                            'datestart' => $dates->earliest_date, 
                            'dateend' => $dates->latest_date,   
                        );
                        array_push($dataexport,$newdata);
                        
                    }
                } 
            }
            
            $namemodel = array_values(array_unique($dataexport, SORT_REGULAR));
            $namefile = 'Combine_SEM_'.date("YmdHis");
            $file = storage_path('NHK-File/template/report/template_calculation2.xlsx'); // for test on orange host

            Excel::load($file, function ($doc)use($dataexport,$datestart, $dateend) {

                // $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
                // $doc = $reader->load($file);

                $doc->setActiveSheetIndexByName('ALPC');
                $sheetlpc0 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetlpc0);
            
                $doc->setActiveSheetIndexByName('Anion');
                $sheetic11 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetic11);

                $doc->setActiveSheetIndexByName('Sulphate');
                $sheetic12 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetic12);

                $doc->setActiveSheetIndexByName('Cation');
                $sheetic13 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetic13);
            
                $doc->setActiveSheetIndexByName('Outgassing');
                $sheetgcms14 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetgcms14);

                $doc->setActiveSheetIndexByName('Low MW');
                $sheetgcms15 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetgcms15);

                $doc->setActiveSheetIndexByName('High MW');
                $sheetgcms16 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetgcms16);

                $doc->setActiveSheetIndexByName('Smear');
                $sheetgcms17 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetgcms17);
            
                $doc->setActiveSheetIndexByName('FTIR');
                $sheetftir18 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetftir18);
                
                $i=5;
                $chklpccount = 0;
                $chksemcount = 0;
                $chiccount = 0;
                $chkgcmscount = 0;
                $chkftircount = 0;
    
                foreach ($dataexport as $val) {

                    $model = DB::table('nt_model')
                    ->join('nt_model_spec','nt_model_spec.id_model','=','nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->where('nt_model_spec.id_model_type', 1)
                    ->first();

                    $nt_model_spec = DB::table('nt_model_spec')
                    ->join('nt_model_spec_type', 'nt_model_spec.id_model_type', '=', 'nt_model_spec_type.id_model_type')
                    ->where('nt_model_spec.id_model', $val['id_model'])
                    ->where('nt_model_spec.id_model_type', 1)
                    ->first(); 

                    $lpccount = DB::table('nt_data_sem')
                    ->select('nt_oqa_lab.id_createlab', 'nt_oqa_lab.save_date', 'nt_oqa_lab.id_model')
                    ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                    ->count();

                    $maxdata = DB::table('nt_data_sem')
                    ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                    ->max('sem_class1');
                    $max = number_format($maxdata, 2, '.', '');

                    $mindata =  DB::table('nt_data_sem')
                    ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                    ->min('sem_class1');
                    $min = number_format($mindata, 2, '.', '');

                    $meandata =  DB::table('nt_data_sem')
                    ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                    ->avg('sem_class1');
                    $mean = number_format($meandata, 2, '.', '');
        
                    $stdget = DB::table('nt_data_sem')
                    ->select(DB::raw('STDDEV_SAMP(nt_data_sem.sem_class1) as std'))
                    ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                    ->first();
                    $std = number_format($stdget->std, 2, '.', '');   
                    $mean6data = $meandata + (6*$stdget->std);
                    $mean6 = number_format($mean6data, 2, '.', '');

                    $dates = DB::table('nt_data_sem')
                    ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                    ->selectRaw('MIN(nt_oqa_lab.save_date) as earliest_date, MAX(nt_oqa_lab.save_date) as latest_date')
                    ->first();

                    $firstdate = $dates->earliest_date;
                    $lastdate = $dates->latest_date;
    
                    $sheet1 = $doc->setActiveSheetIndexByName('SEM C 1');
                    $sheet1->setCellValue('A'.$i,$model->name_model);
                    $sheet1->setCellValue('B'.$i,$model->part);
                    $sheet1->setCellValue('C'.$i,$model->part_number);
                    $sheet1->setCellValue('D'.$i,$lpccount);
                    $sheet1->setCellValue('E'.$i,$max);
                    $sheet1->setCellValue('F'.$i,$min);
                    $sheet1->setCellValue('G'.$i,$mean);
                    $sheet1->setCellValue('H'.$i,$std);
                    $sheet1->setCellValue('I'.$i, date('Y-m-d', strtotime($firstdate)));
                    $sheet1->setCellValue('J'.$i, date('Y-m-d', strtotime($lastdate)));
                    $sheet1->setCellValue('P'.$i,$mean6);
                    $sheet1->setCellValue('P'.$i, $nt_model_spec->class1);

                    $maxdata = DB::table('nt_data_sem')
                    ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                    ->max('sem_class2');
                    $max = number_format($maxdata, 2, '.', '');

                    $mindata = DB::table('nt_data_sem')
                    ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                    ->min('sem_class2');
                    $min = number_format($mindata, 2, '.', '');

                    $meandata = DB::table('nt_data_sem')
                    ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                    ->avg('sem_class2');
                    $mean = number_format($meandata, 2, '.', '');
        
                    $stdget = DB::table('nt_data_sem')
                    ->select(DB::raw('STDDEV_SAMP(nt_data_sem.sem_class2) as std'))
                    ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                    ->first();
                    $std = number_format($stdget->std, 2, '.', '');   
                    $mean6data = $meandata + (6*$stdget->std);
                    $mean6 = number_format($mean6data, 2, '.', ''); 

                    $sheet2 = $doc->setActiveSheetIndexByName('SEM C 2');
                    $sheet2->setCellValue('A'.$i,$model->name_model);
                    $sheet2->setCellValue('B'.$i,$model->part);
                    $sheet2->setCellValue('C'.$i,$model->part_number);
                    $sheet2->setCellValue('D'.$i,$lpccount);
                    $sheet2->setCellValue('E'.$i,$max);
                    $sheet2->setCellValue('F'.$i,$min);
                    $sheet2->setCellValue('G'.$i,$mean);
                    $sheet2->setCellValue('H'.$i,$std);
                    $sheet2->setCellValue('I'.$i, date('Y-m-d', strtotime($firstdate)));
                    $sheet2->setCellValue('J'.$i, date('Y-m-d', strtotime($lastdate)));
                    $sheet2->setCellValue('P'.$i,$mean6);
                    $sheet2->setCellValue('P'.$i, $nt_model_spec->class2);

                    $maxdata = DB::table('nt_data_sem')
                    ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                    ->max('sem_class3');
                    $max = number_format($maxdata, 2, '.', '');

                    $mindata = DB::table('nt_data_sem')
                    ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                    ->min('sem_class3');
                    $min = number_format($mindata, 2, '.', '');

                    $meandata = DB::table('nt_data_sem')
                    ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                    ->avg('sem_class3');
                    $mean = number_format($meandata, 2, '.', '');
        
                    $stdget = DB::table('nt_data_sem')
                    ->select(DB::raw('STDDEV_SAMP(nt_data_sem.sem_class3) as std'))
                    ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                    ->first();
                    $std = number_format($stdget->std, 2, '.', '');   
                    $mean6data = $meandata + (6*$stdget->std);
                    $mean6 = number_format($mean6data, 2, '.', ''); 
    
                    $sheet3 = $doc->setActiveSheetIndexByName('SEM C 3');
                    $sheet3->setCellValue('A'.$i,$model->name_model);
                    $sheet3->setCellValue('B'.$i,$model->part);
                    $sheet3->setCellValue('C'.$i,$model->part_number);
                    $sheet3->setCellValue('D'.$i,$lpccount);
                    $sheet3->setCellValue('E'.$i,$max);
                    $sheet3->setCellValue('F'.$i,$min);
                    $sheet3->setCellValue('G'.$i,$mean);
                    $sheet3->setCellValue('H'.$i,$std);
                    $sheet3->setCellValue('I'.$i, date('Y-m-d', strtotime($firstdate)));
                    $sheet3->setCellValue('J'.$i, date('Y-m-d', strtotime($lastdate)));
                    $sheet3->setCellValue('P'.$i,$mean6);
                    $sheet3->setCellValue('P'.$i, $nt_model_spec->class2);
                
                    $maxdata = DB::table('nt_data_sem')
                    ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                    ->max('sem_class3_1');
                    $max = number_format($maxdata, 2, '.', '');

                    $mindata = DB::table('nt_data_sem')
                    ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                    ->min('sem_class3_1');
                    $min = number_format($mindata, 2, '.', '');

                    $meandata = DB::table('nt_data_sem')
                    ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                    ->avg('sem_class3_1');
                    $mean = number_format($meandata, 2, '.', '');
        
                    $stdget = DB::table('nt_data_sem')
                    ->select(DB::raw('STDDEV_SAMP(nt_data_sem.sem_class3_1) as std'))
                    ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                    ->first();
                    $std = number_format($stdget->std, 2, '.', '');   
                    $mean6data = $meandata + (6*$stdget->std);
                    $mean6 = number_format($mean6data, 2, '.', ''); 

                    $sheet3_1 = $doc->setActiveSheetIndexByName('SEM C 3.1');
                    $sheet3_1->setCellValue('A'.$i,$model->name_model);
                    $sheet3_1->setCellValue('B'.$i,$model->part);
                    $sheet3_1->setCellValue('C'.$i,$model->part_number);
                    $sheet3_1->setCellValue('D'.$i,$lpccount);
                    $sheet3_1->setCellValue('E'.$i,$max);
                    $sheet3_1->setCellValue('F'.$i,$min);
                    $sheet3_1->setCellValue('G'.$i,$mean);
                    $sheet3_1->setCellValue('H'.$i,$std);
                    $sheet3_1->setCellValue('I'.$i, date('Y-m-d', strtotime($firstdate)));
                    $sheet3_1->setCellValue('J'.$i, date('Y-m-d', strtotime($lastdate)));
                    $sheet3_1->setCellValue('P'.$i,$mean6);
                    $sheet3_1->setCellValue('P'.$i, $nt_model_spec->class3_1);

                    $maxdata = DB::table('nt_data_sem')
                    ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                    ->max('sem_class3_2');
                    $max = number_format($maxdata, 2, '.', '');

                    $mindata = DB::table('nt_data_sem')
                    ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                    ->min('sem_class3_2');
                    $min = number_format($mindata, 2, '.', '');

                    $meandata = DB::table('nt_data_sem')
                    ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                    ->avg('sem_class3_2');
                    $mean = number_format($meandata, 2, '.', '');
        
                    $stdget = DB::table('nt_data_sem')
                    ->select(DB::raw('STDDEV_SAMP(nt_data_sem.sem_class3_2) as std'))
                    ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                    ->first();
                    $std = number_format($stdget->std, 2, '.', '');   
                    $mean6data = $meandata + (6*$stdget->std);
                    $mean6 = number_format($mean6data, 2, '.', ''); 

                    $sheet3_2 = $doc->setActiveSheetIndexByName('SEM C 3.2');
                    $sheet3_2->setCellValue('A'.$i,$model->name_model);
                    $sheet3_2->setCellValue('B'.$i,$model->part);
                    $sheet3_2->setCellValue('C'.$i,$model->part_number);
                    $sheet3_2->setCellValue('D'.$i,$lpccount);
                    $sheet3_2->setCellValue('E'.$i,$max);
                    $sheet3_2->setCellValue('F'.$i,$min);
                    $sheet3_2->setCellValue('G'.$i,$mean);
                    $sheet3_2->setCellValue('H'.$i,$std);
                    $sheet3_2->setCellValue('I'.$i, date('Y-m-d', strtotime($firstdate)));
                    $sheet3_2->setCellValue('J'.$i, date('Y-m-d', strtotime($lastdate)));
                    $sheet3_2->setCellValue('P'.$i,$mean6);
                    $sheet3_2->setCellValue('P'.$i, $nt_model_spec->class3_2);

                    $maxdata = DB::table('nt_data_sem')
                    ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                    ->max('sem_class3_3');
                    $max = number_format($maxdata, 2, '.', '');

                    $mindata = DB::table('nt_data_sem')
                    ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                    ->min('sem_class3_3');
                    $min = number_format($mindata, 2, '.', '');

                    $meandata = DB::table('nt_data_sem')
                    ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                    ->avg('sem_class3_3');
                    $mean = number_format($meandata, 2, '.', '');
        
                    $stdget = DB::table('nt_data_sem')
                    ->select(DB::raw('STDDEV_SAMP(nt_data_sem.sem_class3_3) as std'))
                    ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                    ->first();
                    $std = number_format($stdget->std, 2, '.', '');   
                    $mean6data = $meandata + (6*$stdget->std);
                    $mean6 = number_format($mean6data, 2, '.', ''); 

                    $sheet3_3 = $doc->setActiveSheetIndexByName('SEM C 3.3');
                    $sheet3_3->setCellValue('A'.$i,$model->name_model);
                    $sheet3_3->setCellValue('B'.$i,$model->part);
                    $sheet3_3->setCellValue('C'.$i,$model->part_number);
                    $sheet3_3->setCellValue('D'.$i,$lpccount);
                    $sheet3_3->setCellValue('E'.$i,$max);
                    $sheet3_3->setCellValue('F'.$i,$min);
                    $sheet3_3->setCellValue('G'.$i,$mean);
                    $sheet3_3->setCellValue('H'.$i,$std);
                    $sheet3_3->setCellValue('I'.$i, date('Y-m-d', strtotime($firstdate)));
                    $sheet3_3->setCellValue('J'.$i, date('Y-m-d', strtotime($lastdate)));
                    $sheet3_3->setCellValue('P'.$i,$mean6);
                    $sheet3_3->setCellValue('P'.$i, $nt_model_spec->class3_3);

                    $maxdata = DB::table('nt_data_sem')
                    ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                    ->max('sem_class4');
                    $max = number_format($maxdata, 2, '.', '');
                    
                    $mindata = DB::table('nt_data_sem')
                    ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                    ->min('sem_class4');
                    $min = number_format($mindata, 2, '.', '');
                    
                    $meandata = DB::table('nt_data_sem')
                    ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                    ->avg('sem_class4');
                    $mean = number_format($meandata, 2, '.', '');
        
                    $stdget = DB::table('nt_data_sem')
                    ->select(DB::raw('STDDEV_SAMP(nt_data_sem.sem_class4) as std'))
                    ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                    ->first();
                    $std = number_format($stdget->std, 2, '.', '');   
                    $mean6data = $meandata + (6*$stdget->std);
                    $mean6 = number_format($mean6data, 2, '.', ''); 

                    $sheet4 = $doc->setActiveSheetIndexByName('SEM C 4');
                    $sheet4->setCellValue('A'.$i,$model->name_model);
                    $sheet4->setCellValue('B'.$i,$model->part);
                    $sheet4->setCellValue('C'.$i,$model->part_number);
                    $sheet4->setCellValue('D'.$i,$lpccount);
                    $sheet4->setCellValue('E'.$i,$max);
                    $sheet4->setCellValue('F'.$i,$min);
                    $sheet4->setCellValue('G'.$i,$mean);
                    $sheet4->setCellValue('H'.$i,$std);
                    $sheet4->setCellValue('I'.$i, date('Y-m-d', strtotime($firstdate)));
                    $sheet4->setCellValue('J'.$i, date('Y-m-d', strtotime($lastdate)));
                    $sheet4->setCellValue('P'.$i,$mean6);
                    $sheet4->setCellValue('P'.$i, $nt_model_spec->class4);

                    $maxdata = DB::table('nt_data_sem')
                    ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                    ->max('sem_class5');
                    $max = number_format($maxdata, 2, '.', '');

                    $mindata = DB::table('nt_data_sem')
                    ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                    ->min('sem_class5');
                    $min = number_format($mindata, 2, '.', '');

                    $meandata = DB::table('nt_data_sem')
                    ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                    ->avg('sem_class5');
                    $mean = number_format($meandata, 2, '.', '');
        
                    $stdget = DB::table('nt_data_sem')
                    ->select(DB::raw('STDDEV_SAMP(nt_data_sem.sem_class5) as std'))
                    ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                    ->first();
                    $std = number_format($stdget->std, 2, '.', '');   
                    $mean6data = $meandata + (6*$stdget->std);
                    $mean6 = number_format($mean6data, 2, '.', '');  

                    $sheet5 = $doc->setActiveSheetIndexByName('SEM C 5');
                    $sheet5->setCellValue('A'.$i,$model->name_model);
                    $sheet5->setCellValue('B'.$i,$model->part);
                    $sheet5->setCellValue('C'.$i,$model->part_number);
                    $sheet5->setCellValue('D'.$i,$lpccount);
                    $sheet5->setCellValue('E'.$i,$max);
                    $sheet5->setCellValue('F'.$i,$min);
                    $sheet5->setCellValue('G'.$i,$mean);
                    $sheet5->setCellValue('H'.$i,$std);
                    $sheet5->setCellValue('I'.$i, date('Y-m-d', strtotime($firstdate)));
                    $sheet5->setCellValue('J'.$i, date('Y-m-d', strtotime($lastdate)));
                    $sheet5->setCellValue('P'.$i,$mean6);
                    $sheet5->setCellValue('P'.$i, $nt_model_spec->class5);

                    $maxdata = DB::table('nt_data_sem')
                    ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                    ->max('sem_class5_1');
                    $max = number_format($maxdata, 2, '.', '');

                    $mindata = DB::table('nt_data_sem')
                    ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                    ->min('sem_class5_1');
                    $min = number_format($mindata, 2, '.', '');

                    $meandata = DB::table('nt_data_sem')
                    ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                    ->avg('sem_class5_1');
                    $mean = number_format($meandata, 2, '.', '');
        
                    $stdget = DB::table('nt_data_sem')
                    ->select(DB::raw('STDDEV_SAMP(nt_data_sem.sem_class5_1) as std'))
                    ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                    ->first();
                    $std = number_format($stdget->std, 2, '.', '');   
                    $mean6data = $meandata + (6*$stdget->std);
                    $mean6 = number_format($mean6data, 2, '.', ''); 

                    $sheet5_1 = $doc->setActiveSheetIndexByName('SEM C 5.1');
                    $sheet5_1->setCellValue('A'.$i,$model->name_model);
                    $sheet5_1->setCellValue('B'.$i,$model->part);
                    $sheet5_1->setCellValue('C'.$i,$model->part_number);
                    $sheet5_1->setCellValue('D'.$i,$lpccount);
                    $sheet5_1->setCellValue('E'.$i,$max);
                    $sheet5_1->setCellValue('F'.$i,$min);
                    $sheet5_1->setCellValue('G'.$i,$mean);
                    $sheet5_1->setCellValue('H'.$i,$std);
                    $sheet5_1->setCellValue('I'.$i, date('Y-m-d', strtotime($firstdate)));
                    $sheet5_1->setCellValue('J'.$i, date('Y-m-d', strtotime($lastdate)));
                    $sheet5_1->setCellValue('P'.$i,$mean6);
                    $sheet5_1->setCellValue('P'.$i, $nt_model_spec->class5_1);

                    $maxdata = DB::table('nt_data_sem')
                    ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                    ->max('sem_class6');
                    $max = number_format($maxdata, 2, '.', '');

                    $mindata = DB::table('nt_data_sem')
                    ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                    ->min('sem_class6');
                    $min = number_format($mindata, 2, '.', '');

                    $meandata = DB::table('nt_data_sem')
                    ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                    ->avg('sem_class6');
                    $mean = number_format($meandata, 2, '.', '');
        
                    $stdget = DB::table('nt_data_sem')
                    ->select(DB::raw('STDDEV_SAMP(nt_data_sem.sem_class6) as std'))
                    ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                    ->first();
                    $std = number_format($stdget->std, 2, '.', '');   
                    $mean6data = $meandata + (6*$stdget->std);
                    $mean6 = number_format($mean6data, 2, '.', ''); 

                    $sheet6 = $doc->setActiveSheetIndexByName('SEM C 6');
                    $sheet6->setCellValue('A'.$i,$model->name_model);
                    $sheet6->setCellValue('B'.$i,$model->part);
                    $sheet6->setCellValue('C'.$i,$model->part_number);
                    $sheet6->setCellValue('D'.$i,$lpccount);
                    $sheet6->setCellValue('E'.$i,$max);
                    $sheet6->setCellValue('F'.$i,$min);
                    $sheet6->setCellValue('G'.$i,$mean);
                    $sheet6->setCellValue('H'.$i,$std);
                    $sheet6->setCellValue('I'.$i, date('Y-m-d', strtotime($firstdate)));
                    $sheet6->setCellValue('J'.$i, date('Y-m-d', strtotime($lastdate)));
                    $sheet6->setCellValue('P'.$i,$mean6);
                    $sheet6->setCellValue('P'.$i, $nt_model_spec->class6);

                    $i++;

                }
                
                // $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($doc, "Xlsx");
                // $writer->save(storage_path('NHK-File/report/calculation/'.$namefile.'.xlsx'));

            })->setFilename($namefile)->store('xlsx', storage_path('NHK-File/report/calculation'));
            
            return response()->json(['success' =>  1,'filename' => $namefile.'.xlsx']);
        }else{
            return response()->json(['success' =>  2]);
        }

    }

    public function iccombineexport(Request $request)
    {

        $datestart = date('Y-m-d 00:00:00', strtotime($request->start_date));
        $dateend = date('Y-m-d 00:00:00', strtotime($request->end_date));
        $number = $request->numbercount;

        if(!empty($request->id)){
            $dataexport = [];
            foreach ($request->id as $key => $id_model) {

                $id_model = $request->id[$key];
                $id_lab_ic = explode(",",$request->idlab_ic[$id_model]);  

                $main = DB::table('nt_model')->where('id_model', $id_model)->first();
                $rest = substr($main->name_model, 0, -2);

                if($main->model_type == "SGT_TNHK"){
                    $Site = "TNHK";
                }else{
                    $Site = "JNHK";
                }

                $text_date_start = 'datestart'.$main->drive_name.'_'.$Site;
                $datestart = $request->$text_date_start;

                $text_date_end = 'dateend'.$main->drive_name.'_'.$Site;
                $dateend = $request->$text_date_end;

                $text_number_lpc = 'iccount'.$main->drive_name.'_'.$Site;
                $number_lpc = $request->$text_number_lpc;

                $data_lpc_get = DB::table('nt_data_ic')
                ->join('nt_oqa_lab', 'nt_data_ic.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                ->whereIn('nt_oqa_lab.id_createlab', $id_lab_ic)
                ->where('nt_oqa_lab.save_date', '<=', $dateend)
                ->orderBy('nt_oqa_lab.save_date', 'DESC')
                ->select('nt_model.id_model', DB::raw('COUNT(nt_oqa_lab.id_createlab) as total_createlab'))
                ->groupBy('nt_model.id_model')
                ->get();

                if (count($id_lab_ic) > 0) {
                    foreach ($data_lpc_get as $key => $value) {
                        
                        $count = DB::table('nt_data_ic')
                        ->join('nt_oqa_lab', 'nt_data_ic.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $value->id_model)
                        ->whereIn('nt_oqa_lab.id_createlab', $id_lab_ic)
                        ->orderBy('nt_oqa_lab.save_date', 'DESC')
                        ->count();

                        $dates = DB::table('nt_data_ic')
                        ->join('nt_oqa_lab', 'nt_data_ic.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $value->id_model)
                        ->whereIn('nt_oqa_lab.id_createlab', $id_lab_ic)
                        ->selectRaw('MIN(nt_oqa_lab.save_date) as earliest_date, MAX(nt_oqa_lab.save_date) as latest_date')
                        ->first();

                        $newdata =  array (
                            'id_model' => $value->id_model,
                            'id_lab' => $id_lab_ic,
                            'count' => $number,
                            'datestart' => $dates->earliest_date, 
                            'dateend' => $dates->latest_date,   
                        );
                        array_push($dataexport,$newdata);
                        
                    }
                } 
            }

            $namemodel = array_values(array_unique($dataexport, SORT_REGULAR));
            $namefile = 'Combine_IC_'.date("YmdHis");
            $file = storage_path('NHK-File/template/report/template_calculation2.xlsx'); // for test on orange host

            Excel::load($file, function ($doc)use($dataexport,$datestart, $dateend) {

                // $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
                // $doc = $reader->load($file);

                $i = 5;
                foreach ($dataexport as $val) {

                    $model = DB::table('nt_model')
                    ->join('nt_model_spec','nt_model_spec.id_model','=','nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->where('nt_model_spec.id_model_type', 1)
                    ->first();

                    $nt_model_spec = DB::table('nt_model_spec')
                    ->join('nt_model_spec_type', 'nt_model_spec.id_model_type', '=', 'nt_model_spec_type.id_model_type')
                    ->where('nt_model_spec.id_model', $val['id_model'])
                    ->where('nt_model_spec.id_model_type', 1)
                    ->first(); 

                    $lpccount = DB::table('nt_data_ic')
                    ->select('nt_oqa_lab.id_createlab', 'nt_oqa_lab.save_date', 'nt_oqa_lab.id_model')
                    ->join('nt_oqa_lab', 'nt_data_ic.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                    ->count();

                    $maxdata = DB::table('nt_data_ic')
                    ->join('nt_oqa_lab', 'nt_data_ic.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                    ->max('ic_anion_up');
                    $max = number_format($maxdata, 2, '.', '');

                    $mindata =  DB::table('nt_data_ic')
                    ->join('nt_oqa_lab', 'nt_data_ic.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                    ->min('ic_anion_up');
                    $min = number_format($mindata, 2, '.', '');

                    $meandata =  DB::table('nt_data_ic')
                    ->join('nt_oqa_lab', 'nt_data_ic.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                    ->avg('ic_anion_up');
                    $mean = number_format($meandata, 2, '.', '');
        
                    $stdget = DB::table('nt_data_ic')
                    ->select(DB::raw('STDDEV_SAMP(nt_data_ic.ic_anion_up) as std'))
                    ->join('nt_oqa_lab', 'nt_data_ic.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                    ->first();
                    $std = number_format($stdget->std, 2, '.', '');   
                    $mean6data = $meandata + (6*$stdget->std);
                    $mean6 = number_format($mean6data, 2, '.', '');

                    $dates = DB::table('nt_data_ic')
                    ->join('nt_oqa_lab', 'nt_data_ic.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                    ->selectRaw('MIN(nt_oqa_lab.save_date) as earliest_date, MAX(nt_oqa_lab.save_date) as latest_date')
                    ->first();

                    $firstdate = $dates->earliest_date;
                    $lastdate = $dates->latest_date;

                    $datacal_ic = $this->getValue_calculation_ic($val['id_model'],$firstdate,$lastdate);

                    $sheet = $doc->setActiveSheetIndex(11);
                    $sheet->setCellValue('A'.$i,$model->name_model);
                    $sheet->setCellValue('B'.$i,$model->part);
                    $sheet->setCellValue('C'.$i,$model->part_number);
                    $sheet->setCellValue('D'.$i,$lpccount);
                    $sheet->setCellValue('E'.$i,$max);
                    $sheet->setCellValue('F'.$i,$min);
                    $sheet->setCellValue('G'.$i,$mean);
                    $sheet->setCellValue('H'.$i,$datacal_ic['sigma']);
                    $sheet->setCellValue('I'.$i, date('Y-m-d', strtotime($firstdate)));
                    $sheet->setCellValue('J'.$i, date('Y-m-d', strtotime($lastdate)));
                    $sheet->setCellValue('P'.$i,$mean6);

                    $maxdata = DB::table('nt_data_ic')
                    ->join('nt_oqa_lab', 'nt_data_ic.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                    ->max('ic_sulphate');
                    $max = number_format($maxdata, 2, '.', '');

                    $mindata = DB::table('nt_data_ic')
                    ->join('nt_oqa_lab', 'nt_data_ic.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                    ->min('ic_sulphate');
                    $min = number_format($mindata, 2, '.', '');

                    $meandata = DB::table('nt_data_ic')
                    ->join('nt_oqa_lab', 'nt_data_ic.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                    ->avg('ic_sulphate');
                    $mean = number_format($meandata, 2, '.', '');
        
                    $stdget = DB::table('nt_data_ic')
                    ->select(DB::raw('STDDEV_SAMP(nt_data_ic.ic_sulphate) as std'))
                    ->join('nt_oqa_lab', 'nt_data_ic.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                    ->first();
                    $std = number_format($stdget->std, 2, '.', '');   
                    $mean6data = $meandata + (6*$stdget->std);
                    $mean6 = number_format($mean6data, 2, '.', ''); 

                    $sheet = $doc->setActiveSheetIndex(12); 
                    $sheet->setCellValue('A'.$i,$model->name_model);
                    $sheet->setCellValue('B'.$i,$model->part);
                    $sheet->setCellValue('C'.$i,$model->part_number);
                    $sheet->setCellValue('D'.$i,$lpccount);
                    $sheet->setCellValue('E'.$i,$max);
                    $sheet->setCellValue('F'.$i,$min);
                    $sheet->setCellValue('G'.$i,$mean);
                    $sheet->setCellValue('H'.$i,$std);
                    $sheet->setCellValue('I'.$i, date('Y-m-d', strtotime($firstdate)));
                    $sheet->setCellValue('I'.$i, date('Y-m-d', strtotime($firstdate)));
                    $sheet->setCellValue('P'.$i,$mean6);

                    $maxdata = DB::table('nt_data_ic')
                    ->join('nt_oqa_lab', 'nt_data_ic.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                    ->max('ic_cation_up');
                    $max = number_format($maxdata, 2, '.', '');

                    $mindata = DB::table('nt_data_ic')
                    ->join('nt_oqa_lab', 'nt_data_ic.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                    ->min('ic_cation_up');
                    $min = number_format($mindata, 2, '.', '');

                    $meandata = DB::table('nt_data_ic')
                    ->join('nt_oqa_lab', 'nt_data_ic.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                    ->avg('ic_cation_up');
                    $mean = number_format($meandata, 2, '.', '');
        
                    $stdget = DB::table('nt_data_ic')
                    ->select(DB::raw('STDDEV_SAMP(nt_data_ic.ic_cation_up) as std'))
                    ->join('nt_oqa_lab', 'nt_data_ic.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                    ->first();
                    $std = number_format($stdget->std, 2, '.', '');   
                    $mean6data = $meandata + (6*$stdget->std);
                    $mean6 = number_format($mean6data, 2, '.', ''); 

                    $sheet = $doc->setActiveSheetIndex(13); 
                    $sheet->setCellValue('A'.$i,$model->name_model);
                    $sheet->setCellValue('B'.$i,$model->part);
                    $sheet->setCellValue('C'.$i,$model->part_number);
                    $sheet->setCellValue('D'.$i,$lpccount);
                    $sheet->setCellValue('E'.$i,$max);
                    $sheet->setCellValue('F'.$i,$min);
                    $sheet->setCellValue('G'.$i,$mean);
                    $sheet->setCellValue('H'.$i,$std);
                    $sheet->setCellValue('I'.$i, date('Y-m-d', strtotime($firstdate)));
                    $sheet->setCellValue('I'.$i, date('Y-m-d', strtotime($firstdate)));
                    $sheet->setCellValue('P'.$i,$mean6);


                    $i++;

                }

                $doc->setActiveSheetIndexByName('ALPC');
                $sheetlpc0 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetlpc0);
        
                $doc->setActiveSheetIndexByName('SEM C 1');
                $sheetsem1 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetsem1);

                $doc->setActiveSheetIndexByName('SEM C 2');
                $sheetsem2 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetsem2);

                $doc->setActiveSheetIndexByName('SEM C 3');
                $sheetsem3 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetsem3);

                $doc->setActiveSheetIndexByName('SEM C 3.1');
                $sheetsem4 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetsem4);

                $doc->setActiveSheetIndexByName('SEM C 3.2');
                $sheetsem5 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetsem5);

                $doc->setActiveSheetIndexByName('SEM C 3.3');
                $sheetsem6 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetsem6);

                $doc->setActiveSheetIndexByName('SEM C 4');
                $sheetsem7 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetsem7);

                $doc->setActiveSheetIndexByName('SEM C 5');
                $sheetsem8 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetsem8);

                $doc->setActiveSheetIndexByName('SEM C 5.1');
                $sheetsem9 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetsem9);

                $doc->setActiveSheetIndexByName('SEM C 6');
                $sheetsem10 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetsem10);
            
            
                $doc->setActiveSheetIndexByName('Outgassing');
                $sheetgcms14 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetgcms14);

                $doc->setActiveSheetIndexByName('Low MW');
                $sheetgcms15 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetgcms15);

                $doc->setActiveSheetIndexByName('High MW');
                $sheetgcms16 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetgcms16);

                $doc->setActiveSheetIndexByName('Smear');
                $sheetgcms17 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetgcms17);
            
                $doc->setActiveSheetIndexByName('FTIR');
                $sheetftir18 = $doc->getActiveSheetIndex(0);
                $doc->removeSheetByIndex($sheetftir18);

                // $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($doc, "Xlsx");
                // $writer->save(storage_path('NHK-File/report/calculation/'.$namefile.'.xlsx'));

            })->setFilename($namefile)->store('xlsx', storage_path('NHK-File/report/calculation'));
            
            return response()->json(['success' =>  1,'filename' => $namefile.'.xlsx']);
        }else{
            return response()->json(['success' =>  2]);
        }

    }

    public function gcmscombinesexport(Request $request)
    {
  
        $datestart = date('Y-m-d 00:00:00', strtotime($request->start_date));
        $dateend = date('Y-m-d 00:00:00', strtotime($request->end_date));
        $number = $request->numbercount;

        if(!empty($request->id)){
            $dataexport = [];
            foreach ($request->id as $key => $id_model) {

                $id_model = $request->id[$key];
                $id_lab_gcms = explode(",",$request->idlab_gcms[$id_model]);  

                $main = DB::table('nt_model')->where('id_model', $id_model)->first();
                $rest = substr($main->name_model, 0, -2);

                if($main->model_type == "SGT_TNHK"){
                    $Site = "TNHK";
                }else{
                    $Site = "JNHK";
                }

                $text_date_start = 'datestart'.$main->drive_name.'_'.$Site;
                $datestart = $request->$text_date_start;

                $text_date_end = 'dateend'.$main->drive_name.'_'.$Site;
                $dateend = $request->$text_date_end;

                $text_number_lpc = 'iccount'.$main->drive_name.'_'.$Site;
                $number_lpc = $request->$text_number_lpc;

                $data_lpc_get = DB::table('nt_data_gcms')
                ->join('nt_oqa_lab', 'nt_data_gcms.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                ->whereIn('nt_oqa_lab.id_createlab', $id_lab_gcms)
                ->where('nt_oqa_lab.save_date', '<=', $dateend)
                ->orderBy('nt_oqa_lab.save_date', 'DESC')
                ->select('nt_model.id_model', DB::raw('COUNT(nt_oqa_lab.id_createlab) as total_createlab'))
                ->groupBy('nt_model.id_model')
                ->get();

                if (count($id_lab_gcms) > 0) {
                    foreach ($data_lpc_get as $key => $value) {
                        
                        $count = DB::table('nt_data_gcms')
                        ->join('nt_oqa_lab', 'nt_data_gcms.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $value->id_model)
                        ->whereIn('nt_oqa_lab.id_createlab', $id_lab_gcms)
                        ->orderBy('nt_oqa_lab.save_date', 'DESC')
                        ->count();

                        $dates = DB::table('nt_data_gcms')
                        ->join('nt_oqa_lab', 'nt_data_gcms.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $value->id_model)
                        ->whereIn('nt_oqa_lab.id_createlab', $id_lab_gcms)
                        ->selectRaw('MIN(nt_oqa_lab.save_date) as earliest_date, MAX(nt_oqa_lab.save_date) as latest_date')
                        ->first();

                        $newdata =  array (
                            'id_model' => $value->id_model,
                            'id_lab' => $id_lab_gcms,
                            'count' => $number,
                            'datestart' => $dates->earliest_date, 
                            'dateend' => $dates->latest_date,   
                        );
                        array_push($dataexport,$newdata);
                        
                    }
                } 
            }
            
            $namemodel = array_values(array_unique($dataexport, SORT_REGULAR));
            $namefile = 'Combine_GCMS_'.date("YmdHis");
            $file = storage_path('NHK-File/template/report/template_calculation2.xlsx'); // for test on orange host

            Excel::load($file, function ($doc)use($dataexport,$datestart, $dateend) {
    
                $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
                $doc = $reader->load($file);

                $i=5;
                $chklpccount = 0;
                $chksemcount = 0;
                $chiccount = 0;
                $chkgcmscount = 0;
                $chkftircount = 0;
            
                foreach ($dataexport as $val) {

                    $model = DB::table('nt_model')
                    ->join('nt_model_spec','nt_model_spec.id_model','=','nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->where('nt_model_spec.id_model_type', 1)
                    ->first();

                    $nt_model_spec = DB::table('nt_model_spec')
                    ->join('nt_model_spec_type', 'nt_model_spec.id_model_type', '=', 'nt_model_spec_type.id_model_type')
                    ->where('nt_model_spec.id_model', $val['id_model'])
                    ->where('nt_model_spec.id_model_type', 1)
                    ->first(); 

                    $lpccount = DB::table('nt_data_gcms')
                    ->select('nt_oqa_lab.id_createlab', 'nt_oqa_lab.save_date', 'nt_oqa_lab.id_model')
                    ->join('nt_oqa_lab', 'nt_data_gcms.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                    ->count();

                    $maxdata = DB::table('nt_data_gcms')
                    ->join('nt_oqa_lab', 'nt_data_gcms.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                    ->max('gcms_outgassing');
                    $max = number_format($maxdata, 2, '.', '');

                    $mindata =  DB::table('nt_data_gcms')
                    ->join('nt_oqa_lab', 'nt_data_gcms.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                    ->min('gcms_outgassing');
                    $min = number_format($mindata, 2, '.', '');

                    $meandata =  DB::table('nt_data_gcms')
                    ->join('nt_oqa_lab', 'nt_data_gcms.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                    ->avg('gcms_outgassing');
                    $mean = number_format($meandata, 2, '.', '');
        
                    $stdget = DB::table('nt_data_gcms')
                    ->select(DB::raw('STDDEV_SAMP(nt_data_gcms.gcms_outgassing) as std'))
                    ->join('nt_oqa_lab', 'nt_data_gcms.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                    ->first();
                    $std = number_format($stdget->std, 2, '.', '');   
                    $mean6data = $meandata + (6*$stdget->std);
                    $mean6 = number_format($mean6data, 2, '.', '');

                    $dates = DB::table('nt_data_gcms')
                    ->join('nt_oqa_lab', 'nt_data_gcms.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                    ->selectRaw('MIN(nt_oqa_lab.save_date) as earliest_date, MAX(nt_oqa_lab.save_date) as latest_date')
                    ->first();

                    $firstdate = $dates->earliest_date;
                    $lastdate = $dates->latest_date;

                    $sheet = $doc->setActiveSheetIndex(14);
                    $sheet->setCellValue('A'.$i,$model->name_model);
                    $sheet->setCellValue('B'.$i,$model->part);
                    $sheet->setCellValue('C'.$i,$model->part_number);
                    $sheet->setCellValue('D'.$i,$lpccount);
                    $sheet->setCellValue('E'.$i,$max);
                    $sheet->setCellValue('F'.$i,$min);
                    $sheet->setCellValue('G'.$i,$mean);
                    $sheet->setCellValue('H'.$i,$std);
                    $sheet->setCellValue('I'.$i, date('Y-m-d', strtotime($firstdate)));
                    $sheet->setCellValue('J'.$i, date('Y-m-d', strtotime($lastdate)));
                    $sheet->setCellValue('P'.$i, $nt_model_spec->outgassing );

                    $maxdata = DB::table('nt_data_gcms')
                    ->join('nt_oqa_lab', 'nt_data_gcms.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                    ->max('gcms_low_mw');
                    $max = number_format($maxdata, 2, '.', '');

                    $mindata =  DB::table('nt_data_gcms')
                    ->join('nt_oqa_lab', 'nt_data_gcms.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                    ->min('gcms_low_mw');
                    $min = number_format($mindata, 2, '.', '');

                    $meandata =  DB::table('nt_data_gcms')
                    ->join('nt_oqa_lab', 'nt_data_gcms.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                    ->avg('gcms_low_mw');
                    $mean = number_format($meandata, 2, '.', '');
        
                    $stdget = DB::table('nt_data_gcms')
                    ->select(DB::raw('STDDEV_SAMP(nt_data_gcms.gcms_low_mw) as std'))
                    ->join('nt_oqa_lab', 'nt_data_gcms.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                    ->first();
                    $std = number_format($stdget->std, 2, '.', '');   
                    $mean6data = $meandata + (6*$stdget->std);
                    $mean6 = number_format($mean6data, 2, '.', '');

                    $dates = DB::table('nt_data_gcms')
                    ->join('nt_oqa_lab', 'nt_data_gcms.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                    ->selectRaw('MIN(nt_oqa_lab.save_date) as earliest_date, MAX(nt_oqa_lab.save_date) as latest_date')
                    ->first();

                    $firstdate = $dates->earliest_date;
                    $lastdate = $dates->latest_date;

                    $sheet = $doc->setActiveSheetIndex(15);
                    $sheet->setCellValue('A'.$i,$model->name_model);
                    $sheet->setCellValue('B'.$i,$model->part);
                    $sheet->setCellValue('C'.$i,$model->part_number);
                    $sheet->setCellValue('D'.$i,$lpccount);
                    $sheet->setCellValue('E'.$i,$max);
                    $sheet->setCellValue('F'.$i,$min);
                    $sheet->setCellValue('G'.$i,$mean);
                    $sheet->setCellValue('H'.$i,$std);
                    $sheet->setCellValue('I'.$i, date('Y-m-d', strtotime($firstdate)));
                    $sheet->setCellValue('J'.$i, date('Y-m-d', strtotime($lastdate)));
                    $sheet->setCellValue('P'.$i, $nt_model_spec->low_MW );

                    $maxdata = DB::table('nt_data_gcms')
                    ->join('nt_oqa_lab', 'nt_data_gcms.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                    ->max('gcms_high_mw');
                    $max = number_format($maxdata, 2, '.', '');

                    $mindata =  DB::table('nt_data_gcms')
                    ->join('nt_oqa_lab', 'nt_data_gcms.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                    ->min('gcms_high_mw');
                    $min = number_format($mindata, 2, '.', '');

                    $meandata =  DB::table('nt_data_gcms')
                    ->join('nt_oqa_lab', 'nt_data_gcms.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                    ->avg('gcms_high_mw');
                    $mean = number_format($meandata, 2, '.', '');
        
                    $stdget = DB::table('nt_data_gcms')
                    ->select(DB::raw('STDDEV_SAMP(nt_data_gcms.gcms_high_mw) as std'))
                    ->join('nt_oqa_lab', 'nt_data_gcms.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                    ->first();
                    $std = number_format($stdget->std, 2, '.', '');   
                    $mean6data = $meandata + (6*$stdget->std);
                    $mean6 = number_format($mean6data, 2, '.', '');

                    $dates = DB::table('nt_data_gcms')
                    ->join('nt_oqa_lab', 'nt_data_gcms.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                    ->selectRaw('MIN(nt_oqa_lab.save_date) as earliest_date, MAX(nt_oqa_lab.save_date) as latest_date')
                    ->first();

                    $firstdate = $dates->earliest_date;
                    $lastdate = $dates->latest_date;

                    $sheet = $doc->setActiveSheetIndex(16);
                    $sheet->setCellValue('A'.$i,$model->name_model);
                    $sheet->setCellValue('B'.$i,$model->part);
                    $sheet->setCellValue('C'.$i,$model->part_number);
                    $sheet->setCellValue('D'.$i,$lpccount);
                    $sheet->setCellValue('E'.$i,$max);
                    $sheet->setCellValue('F'.$i,$min);
                    $sheet->setCellValue('G'.$i,$mean);
                    $sheet->setCellValue('H'.$i,$std);
                    $sheet->setCellValue('I'.$i, date('Y-m-d', strtotime($firstdate)));
                    $sheet->setCellValue('J'.$i, date('Y-m-d', strtotime($lastdate)));
                    $sheet->setCellValue('P'.$i, $nt_model_spec->high_MW );

                    $maxdata = DB::table('nt_data_gcms')
                    ->join('nt_oqa_lab', 'nt_data_gcms.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                    ->max('gcms_smear');
                    $max = number_format($maxdata, 2, '.', '');

                    $mindata =  DB::table('nt_data_gcms')
                    ->join('nt_oqa_lab', 'nt_data_gcms.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                    ->min('gcms_smear');
                    $min = number_format($mindata, 2, '.', '');

                    $meandata =  DB::table('nt_data_gcms')
                    ->join('nt_oqa_lab', 'nt_data_gcms.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                    ->avg('gcms_smear');
                    $mean = number_format($meandata, 2, '.', '');
        
                    $stdget = DB::table('nt_data_gcms')
                    ->select(DB::raw('STDDEV_SAMP(nt_data_gcms.gcms_smear) as std'))
                    ->join('nt_oqa_lab', 'nt_data_gcms.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                    ->first();
                    $std = number_format($stdget->std, 2, '.', '');   
                    $mean6data = $meandata + (6*$stdget->std);
                    $mean6 = number_format($mean6data, 2, '.', '');

                    $dates = DB::table('nt_data_gcms')
                    ->join('nt_oqa_lab', 'nt_data_gcms.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                    ->selectRaw('MIN(nt_oqa_lab.save_date) as earliest_date, MAX(nt_oqa_lab.save_date) as latest_date')
                    ->first();

                    $firstdate = $dates->earliest_date;
                    $lastdate = $dates->latest_date;

                    $sheet = $doc->setActiveSheetIndex(17);
                    $sheet->setCellValue('A'.$i,$model->name_model);
                    $sheet->setCellValue('B'.$i,$model->part);
                    $sheet->setCellValue('C'.$i,$model->part_number);
                    $sheet->setCellValue('D'.$i,$lpccount);
                    $sheet->setCellValue('E'.$i,$max);
                    $sheet->setCellValue('F'.$i,$min);
                    $sheet->setCellValue('G'.$i,$mean);
                    $sheet->setCellValue('H'.$i,$std);
                    $sheet->setCellValue('I'.$i, date('Y-m-d', strtotime($firstdate)));
                    $sheet->setCellValue('J'.$i, date('Y-m-d', strtotime($lastdate)));
                    $sheet->setCellValue('P'.$i, $nt_model_spec->alkylacrylate );

                    $i++;

                }
                
                $doc->setActiveSheetIndexByName('ALPC');
                $sheetlpc0 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetlpc0);
            
                $doc->setActiveSheetIndexByName('SEM C 1');
                $sheetsem1 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetsem1);

                $doc->setActiveSheetIndexByName('SEM C 2');
                $sheetsem2 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetsem2);

                $doc->setActiveSheetIndexByName('SEM C 3');
                $sheetsem3 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetsem3);

                $doc->setActiveSheetIndexByName('SEM C 3.1');
                $sheetsem4 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetsem4);

                $doc->setActiveSheetIndexByName('SEM C 3.2');
                $sheetsem5 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetsem5);

                $doc->setActiveSheetIndexByName('SEM C 3.3');
                $sheetsem6 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetsem6);

                $doc->setActiveSheetIndexByName('SEM C 4');
                $sheetsem7 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetsem7);

                $doc->setActiveSheetIndexByName('SEM C 5');
                $sheetsem8 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetsem8);

                $doc->setActiveSheetIndexByName('SEM C 5.1');
                $sheetsem9 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetsem9);

                $doc->setActiveSheetIndexByName('SEM C 6');
                $sheetsem10 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetsem10);
            
                $doc->setActiveSheetIndexByName('Anion');
                $sheetic11 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetic11);

                $doc->setActiveSheetIndexByName('Sulphate');
                $sheetic12 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetic12);

                $doc->setActiveSheetIndexByName('Cation');
                $sheetic13 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetic13);
            
    
                $doc->setActiveSheetIndexByName('FTIR');
                $sheetftir18 = $doc->getActiveSheetIndex(0);
                $doc->removeSheetByIndex($sheetftir18);

                // $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($doc, "Xlsx");
                // $writer->save(storage_path('NHK-File/report/calculation/'.$namefile.'.xlsx'));

            })->setFilename($namefile)->store('xlsx', storage_path('NHK-File/report/calculation'));
            
            return response()->json(['success' =>  1,'filename' => $namefile.'.xlsx']);
        }else{
            return response()->json(['success' =>  2]);
        }

    }

    public function ftircombinesexport(Request $request)
    {

        $datestart = date('Y-m-d 00:00:00', strtotime($request->start_date));
        $dateend = date('Y-m-d 00:00:00', strtotime($request->end_date));
        $number = $request->numbercount;

        if(!empty($request->id)){
            $dataexport = [];
            foreach ($request->id as $key => $id_model) {

                $id_model = $request->id[$key];
                $id_lab_ftir = explode(",",$request->idlab_ftir[$id_model]);  

                $main = DB::table('nt_model')->where('id_model', $id_model)->first();
                $rest = substr($main->name_model, 0, -2);

                if($main->model_type == "SGT_TNHK"){
                    $Site = "TNHK";
                }else{
                    $Site = "JNHK";
                }

                $text_date_start = 'datestart'.$main->drive_name.'_'.$Site;
                $datestart = $request->$text_date_start;

                $text_date_end = 'dateend'.$main->drive_name.'_'.$Site;
                $dateend = $request->$text_date_end;

                $text_number_lpc = 'ftircount'.$main->drive_name.'_'.$Site;
                $number_lpc = $request->$text_number_lpc;

                $data_lpc_get = DB::table('nt_data_ftir')
                ->join('nt_oqa_lab', 'nt_data_ftir.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                ->whereIn('nt_oqa_lab.id_createlab', $id_lab_ftir)
                ->where('nt_oqa_lab.save_date', '<=', $dateend)
                ->orderBy('nt_oqa_lab.save_date', 'DESC')
                ->select('nt_model.id_model', DB::raw('COUNT(nt_oqa_lab.id_createlab) as total_createlab'))
                ->groupBy('nt_model.id_model')
                ->get();

                if (count($id_lab_ftir) > 0) {
                    foreach ($data_lpc_get as $key => $value) {
                        
                        $count = DB::table('nt_data_ftir')
                        ->join('nt_oqa_lab', 'nt_data_ftir.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $value->id_model)
                        ->whereIn('nt_oqa_lab.id_createlab', $id_lab_ftir)
                        ->orderBy('nt_oqa_lab.save_date', 'DESC')
                        ->count();

                        $dates = DB::table('nt_data_ftir')
                        ->join('nt_oqa_lab', 'nt_data_ftir.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $value->id_model)
                        ->whereIn('nt_oqa_lab.id_createlab', $id_lab_ftir)
                        ->selectRaw('MIN(nt_oqa_lab.save_date) as earliest_date, MAX(nt_oqa_lab.save_date) as latest_date')
                        ->first();

                        $newdata =  array (
                            'id_model' => $value->id_model,
                            'id_lab' => $id_lab_ftir,
                            'count' => $number,
                            'datestart' => $dates->earliest_date, 
                            'dateend' => $dates->latest_date,   
                        );
                        array_push($dataexport,$newdata);
                        
                    }
                } 
            }
            
            $namemodel = array_values(array_unique($dataexport, SORT_REGULAR));
            $namefile = 'Combine_FTIR_'.date("YmdHis");
            $file = storage_path('NHK-File/template/report/template_calculation2.xlsx'); // for test on orange host

            Excel::load($file, function ($doc)use($namemodel,$datestart, $dateend) {

                // $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
                // $doc = $reader->load($file);

                $i=5;
                $chklpccount = 0;
                $chksemcount = 0;
                $chiccount = 0;
                $chkgcmscount = 0;
                $chkftircount = 0;

                foreach ($dataexport as $val) {

                    $model = DB::table('nt_model')
                    ->join('nt_model_spec','nt_model_spec.id_model','=','nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->where('nt_model_spec.id_model_type', 1)
                    ->first();

                    $nt_model_spec = DB::table('nt_model_spec')
                    ->join('nt_model_spec_type', 'nt_model_spec.id_model_type', '=', 'nt_model_spec_type.id_model_type')
                    ->where('nt_model_spec.id_model', $val['id_model'])
                    ->where('nt_model_spec.id_model_type', 1)
                    ->first(); 

                    $lpccount = DB::table('nt_data_ftir')
                    ->select('nt_oqa_lab.id_createlab', 'nt_oqa_lab.save_date', 'nt_oqa_lab.id_model')
                    ->join('nt_oqa_lab', 'nt_data_ftir.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                    ->count();

                    $maxdata = DB::table('nt_data_ftir')
                    ->join('nt_oqa_lab', 'nt_data_ftir.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                    ->max('ftir_silicone');
                    $max = number_format($maxdata, 2, '.', '');

                    $mindata =  DB::table('nt_data_ftir')
                    ->join('nt_oqa_lab', 'nt_data_ftir.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                    ->min('ftir_silicone');
                    $min = number_format($mindata, 2, '.', '');

                    $meandata =  DB::table('nt_data_ftir')
                    ->join('nt_oqa_lab', 'nt_data_ftir.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                    ->avg('ftir_silicone');
                    $mean = number_format($meandata, 2, '.', '');
        
                    $stdget = DB::table('nt_data_ftir')
                    ->select(DB::raw('STDDEV_SAMP(nt_data_ftir.ftir_silicone) as std'))
                    ->join('nt_oqa_lab', 'nt_data_ftir.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                    ->first();
                    $std = number_format($stdget->std, 2, '.', '');   
                    $mean6data = $meandata + (6*$stdget->std);
                    $mean6 = number_format($mean6data, 2, '.', '');

                    $dates = DB::table('nt_data_ftir')
                    ->join('nt_oqa_lab', 'nt_data_ftir.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->where('nt_model.id_model', $val['id_model'])
                    ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                    ->selectRaw('MIN(nt_oqa_lab.save_date) as earliest_date, MAX(nt_oqa_lab.save_date) as latest_date')
                    ->first();

                    $firstdate = $dates->earliest_date;
                    $lastdate = $dates->latest_date;

                    $sheet = $doc->setActiveSheetIndexByName('FTIR');
                    $sheet->setCellValue('A'.$i,$model->name_model);
                    $sheet->setCellValue('B'.$i,$model->part);
                    $sheet->setCellValue('C'.$i,$model->part_number);
                    $sheet->setCellValue('D'.$i,$lpccount);
                    $sheet->setCellValue('E'.$i,$max);
                    $sheet->setCellValue('F'.$i,$min);
                    $sheet->setCellValue('G'.$i,$mean);
                    $sheet->setCellValue('H'.$i,$std);
                    $sheet->setCellValue('I'.$i, date('Y-m-d', strtotime($firstdate)));
                    $sheet->setCellValue('J'.$i, date('Y-m-d', strtotime($lastdate)));
                    $sheet->setCellValue('P'.$i, $nt_model_spec->ftir );
    
                    $i++;

                }

                $doc->setActiveSheetIndexByName('ALPC');
                $sheetlpc0 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetlpc0);
        
                $doc->setActiveSheetIndexByName('SEM C 1');
                $sheetsem1 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetsem1);

                $doc->setActiveSheetIndexByName('SEM C 2');
                $sheetsem2 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetsem2);

                $doc->setActiveSheetIndexByName('SEM C 3');
                $sheetsem3 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetsem3);

                $doc->setActiveSheetIndexByName('SEM C 3.1');
                $sheetsem4 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetsem4);

                $doc->setActiveSheetIndexByName('SEM C 3.2');
                $sheetsem5 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetsem5);

                $doc->setActiveSheetIndexByName('SEM C 3.3');
                $sheetsem6 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetsem6);

                $doc->setActiveSheetIndexByName('SEM C 4');
                $sheetsem7 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetsem7);

                $doc->setActiveSheetIndexByName('SEM C 5');
                $sheetsem8 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetsem8);

                $doc->setActiveSheetIndexByName('SEM C 5.1');
                $sheetsem9 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetsem9);

                $doc->setActiveSheetIndexByName('SEM C 6');
                $sheetsem10 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetsem10);
                
                $doc->setActiveSheetIndexByName('Anion');
                $sheetic11 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetic11);

                $doc->setActiveSheetIndexByName('Sulphate');
                $sheetic12 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetic12);

                $doc->setActiveSheetIndexByName('Cation');
                $sheetic13 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetic13);
                
                $doc->setActiveSheetIndexByName('Outgassing');
                $sheetgcms14 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetgcms14);

                $doc->setActiveSheetIndexByName('Low MW');
                $sheetgcms15 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetgcms15);

                $doc->setActiveSheetIndexByName('High MW');
                $sheetgcms16 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetgcms16);

                $doc->setActiveSheetIndexByName('Smear');
                $sheetgcms17 = $doc->getActiveSheetIndex();
                $doc->removeSheetByIndex($sheetgcms17);

                // $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($doc, "Xlsx");
                // $writer->save(storage_path('NHK-File/report/calculation/'.$namefile.'.xlsx'));

            })->setFilename($namefile)->store('xlsx', storage_path('NHK-File/report/calculation'));

            return response()->json(['success' =>  1,'filename' => $namefile.'.xlsx']);
        }else{
            return response()->json(['success' =>  2]);
        }
        
    }
 
    public function combinesexportall(Request $request) 
    { 
        
        if(isset($request->id)){
            if(empty($request->start_date) && empty($request->end_date) && empty($request->numbercount))
            {
                return response()->json(['success' =>  2]);
            }else{
                
                $datestart = date('Y-m-d 00:00:00', strtotime($request->start_date));
                $dateend = date('Y-m-d 00:00:00', strtotime($request->end_date));
                $number = $request->numbercount;

                $dataexport_lpc = [];
                $dataexport_sem = [];
                $dataexport_ic = [];
                $dataexport_gcms = [];
                $dataexport_ftir = [];

                foreach ($request->id as $key => $id_model) {

                    $id_model = $request->id[$key];
                    $id_lab_lpc = explode(",",$request->idlab_lpc[$id_model]);  

                    $main = DB::table('nt_model')->where('id_model', $id_model)->first();
                    $rest = substr($main->name_model, 0, -2);

                    if($main->model_type == "SGT_TNHK"){
                        $Site = "TNHK";
                    }else{
                        $Site = "JNHK";
                    }

                    $text_date_start = 'datestart'.$main->drive_name.'_'.$Site;
                    $datestart = $request->$text_date_start;

                    $text_date_end = 'dateend'.$main->drive_name.'_'.$Site;
                    $dateend = $request->$text_date_end;

                    $text_number_lpc = 'lpcount'.$main->drive_name.'_'.$Site;
                    $number_lpc = $request->$text_number_lpc;

                    $data_lpc_get = DB::table('nt_data_lpc')
                    ->join('nt_oqa_lab', 'nt_data_lpc.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->whereIn('nt_oqa_lab.id_createlab', $id_lab_lpc)
                    ->where('nt_oqa_lab.save_date', '<=', $dateend)
                    ->orderBy('nt_oqa_lab.save_date', 'DESC')
                    ->select('nt_model.id_model', DB::raw('COUNT(nt_oqa_lab.id_createlab) as total_createlab'))
                    ->groupBy('nt_model.id_model')
                    ->get();

                    if (count($id_lab_lpc) > 0) {
                        foreach ($data_lpc_get as $key => $value) {
                            
                            $count = DB::table('nt_data_lpc')
                            ->join('nt_oqa_lab', 'nt_data_lpc.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                            ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                            ->where('nt_model.id_model', $value->id_model)
                            ->whereIn('nt_oqa_lab.id_createlab', $id_lab_lpc)
                            ->orderBy('nt_oqa_lab.save_date', 'DESC')
                            ->count();

                            $dates = DB::table('nt_data_lpc')
                            ->join('nt_oqa_lab', 'nt_data_lpc.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                            ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                            ->where('nt_model.id_model', $value->id_model)
                            ->whereIn('nt_oqa_lab.id_createlab', $id_lab_lpc)
                            ->selectRaw('MIN(nt_oqa_lab.save_date) as earliest_date, MAX(nt_oqa_lab.save_date) as latest_date')
                            ->first();

                            $newdata =  array (
                                'id_model' => $value->id_model,
                                'id_lab' => $id_lab_lpc,
                                'count' => $number,
                                'datestart' => $dates->earliest_date, 
                                'dateend' => $dates->latest_date,   
                            );
                            array_push($dataexport_lpc,$newdata);
                            
                        }
                    } 
                }

                foreach ($request->id as $key => $id_model) {

                    $id_model = $request->id[$key];
                    $id_lab_lpc = explode(",",$request->idlab_sem[$id_model]);  
        
                    $main = DB::table('nt_model')->where('id_model', $id_model)->first();
                    $rest = substr($main->name_model, 0, -2);
        
                    if($main->model_type == "SGT_TNHK"){
                        $Site = "TNHK";
                    }else{
                        $Site = "JNHK";
                    }
        
                    $text_date_start = 'datestart'.$main->drive_name.'_'.$Site;
                    $datestart = $request->$text_date_start;
        
                    $text_date_end = 'dateend'.$main->drive_name.'_'.$Site;
                    $dateend = $request->$text_date_end;
        
                    $text_number_lpc = 'semcount'.$main->drive_name.'_'.$Site;
                    $number_lpc = $request->$text_number_lpc;
        
                    $data_lpc_get = DB::table('nt_data_sem')
                    ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->whereIn('nt_oqa_lab.id_createlab', $id_lab_lpc)
                    ->where('nt_oqa_lab.save_date', '<=', $dateend)
                    ->orderBy('nt_oqa_lab.save_date', 'DESC')
                    ->select('nt_model.id_model', DB::raw('COUNT(nt_oqa_lab.id_createlab) as total_createlab'))
                    ->groupBy('nt_model.id_model')
                    ->get();
        
                    if (count($id_lab_lpc) > 0) {
                        foreach ($data_lpc_get as $key => $value) {
                            
                            $count = DB::table('nt_data_sem')
                            ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                            ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                            ->where('nt_model.id_model', $value->id_model)
                            ->whereIn('nt_oqa_lab.id_createlab', $id_lab_lpc)
                            ->orderBy('nt_oqa_lab.save_date', 'DESC')
                            ->count();
        
                            $dates = DB::table('nt_data_sem')
                            ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                            ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                            ->where('nt_model.id_model', $value->id_model)
                            ->whereIn('nt_oqa_lab.id_createlab', $id_lab_lpc)
                            ->selectRaw('MIN(nt_oqa_lab.save_date) as earliest_date, MAX(nt_oqa_lab.save_date) as latest_date')
                            ->first();
        
                            $newdata =  array (
                                'id_model' => $value->id_model,
                                'id_lab' => $id_lab_lpc,
                                'count' => $number,
                                'datestart' => $dates->earliest_date, 
                                'dateend' => $dates->latest_date,   
                            );
                            array_push($dataexport_sem,$newdata);
                            
                        }
                    } 
                }

                foreach ($request->id as $key => $id_model) {

                    $id_model = $request->id[$key];
                    $id_lab_ic = explode(",",$request->idlab_ic[$id_model]);  
        
                    $main = DB::table('nt_model')->where('id_model', $id_model)->first();
                    $rest = substr($main->name_model, 0, -2);
        
                    if($main->model_type == "SGT_TNHK"){
                        $Site = "TNHK";
                    }else{
                        $Site = "JNHK";
                    }
        
                    $text_date_start = 'datestart'.$main->drive_name.'_'.$Site;
                    $datestart = $request->$text_date_start;
        
                    $text_date_end = 'dateend'.$main->drive_name.'_'.$Site;
                    $dateend = $request->$text_date_end;
        
                    $text_number_lpc = 'iccount'.$main->drive_name.'_'.$Site;
                    $number_lpc = $request->$text_number_lpc;
        
                    $data_lpc_get = DB::table('nt_data_ic')
                    ->join('nt_oqa_lab', 'nt_data_ic.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->whereIn('nt_oqa_lab.id_createlab', $id_lab_ic)
                    ->where('nt_oqa_lab.save_date', '<=', $dateend)
                    ->orderBy('nt_oqa_lab.save_date', 'DESC')
                    ->select('nt_model.id_model', DB::raw('COUNT(nt_oqa_lab.id_createlab) as total_createlab'))
                    ->groupBy('nt_model.id_model')
                    ->get();
        
                    if (count($id_lab_ic) > 0) {
                        foreach ($data_lpc_get as $key => $value) {
                            
                            $count = DB::table('nt_data_ic')
                            ->join('nt_oqa_lab', 'nt_data_ic.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                            ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                            ->where('nt_model.id_model', $value->id_model)
                            ->whereIn('nt_oqa_lab.id_createlab', $id_lab_ic)
                            ->orderBy('nt_oqa_lab.save_date', 'DESC')
                            ->count();
        
                            $dates = DB::table('nt_data_ic')
                            ->join('nt_oqa_lab', 'nt_data_ic.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                            ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                            ->where('nt_model.id_model', $value->id_model)
                            ->whereIn('nt_oqa_lab.id_createlab', $id_lab_ic)
                            ->selectRaw('MIN(nt_oqa_lab.save_date) as earliest_date, MAX(nt_oqa_lab.save_date) as latest_date')
                            ->first();
        
                            $newdata =  array (
                                'id_model' => $value->id_model,
                                'id_lab' => $id_lab_ic,
                                'count' => $number,
                                'datestart' => $dates->earliest_date, 
                                'dateend' => $dates->latest_date,   
                            );
                            array_push($dataexport_ic,$newdata);
                            
                        }
                    } 
                }

                foreach ($request->id as $key => $id_model) {

                    $id_model = $request->id[$key];
                    $id_lab_gcms = explode(",",$request->idlab_gcms[$id_model]);  
        
                    $main = DB::table('nt_model')->where('id_model', $id_model)->first();
                    $rest = substr($main->name_model, 0, -2);
        
                    if($main->model_type == "SGT_TNHK"){
                        $Site = "TNHK";
                    }else{
                        $Site = "JNHK";
                    }
        
                    $text_date_start = 'datestart'.$main->drive_name.'_'.$Site;
                    $datestart = $request->$text_date_start;
        
                    $text_date_end = 'dateend'.$main->drive_name.'_'.$Site;
                    $dateend = $request->$text_date_end;
        
                    $text_number_lpc = 'iccount'.$main->drive_name.'_'.$Site;
                    $number_lpc = $request->$text_number_lpc;
        
                    $data_lpc_get = DB::table('nt_data_gcms')
                    ->join('nt_oqa_lab', 'nt_data_gcms.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->whereIn('nt_oqa_lab.id_createlab', $id_lab_gcms)
                    ->where('nt_oqa_lab.save_date', '<=', $dateend)
                    ->orderBy('nt_oqa_lab.save_date', 'DESC')
                    ->select('nt_model.id_model', DB::raw('COUNT(nt_oqa_lab.id_createlab) as total_createlab'))
                    ->groupBy('nt_model.id_model')
                    ->get();
        
                    if (count($id_lab_gcms) > 0) {
                        foreach ($data_lpc_get as $key => $value) {
                            
                            $count = DB::table('nt_data_gcms')
                            ->join('nt_oqa_lab', 'nt_data_gcms.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                            ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                            ->where('nt_model.id_model', $value->id_model)
                            ->whereIn('nt_oqa_lab.id_createlab', $id_lab_gcms)
                            ->orderBy('nt_oqa_lab.save_date', 'DESC')
                            ->count();
        
                            $dates = DB::table('nt_data_gcms')
                            ->join('nt_oqa_lab', 'nt_data_gcms.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                            ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                            ->where('nt_model.id_model', $value->id_model)
                            ->whereIn('nt_oqa_lab.id_createlab', $id_lab_gcms)
                            ->selectRaw('MIN(nt_oqa_lab.save_date) as earliest_date, MAX(nt_oqa_lab.save_date) as latest_date')
                            ->first();
        
                            $newdata =  array (
                                'id_model' => $value->id_model,
                                'id_lab' => $id_lab_gcms,
                                'count' => $number,
                                'datestart' => $dates->earliest_date, 
                                'dateend' => $dates->latest_date,   
                            );
                            array_push($dataexport_gcms,$newdata);
                            
                        }
                    } 
                }

                foreach ($request->id as $key => $id_model) {

                    $id_model = $request->id[$key];
                    $id_lab_ftir = explode(",",$request->idlab_ftir[$id_model]);  
        
                    $main = DB::table('nt_model')->where('id_model', $id_model)->first();
                    $rest = substr($main->name_model, 0, -2);
        
                    if($main->model_type == "SGT_TNHK"){
                        $Site = "TNHK";
                    }else{
                        $Site = "JNHK";
                    }
        
                    $text_date_start = 'datestart'.$main->drive_name.'_'.$Site;
                    $datestart = $request->$text_date_start;
        
                    $text_date_end = 'dateend'.$main->drive_name.'_'.$Site;
                    $dateend = $request->$text_date_end;
        
                    $text_number_lpc = 'ftircount'.$main->drive_name.'_'.$Site;
                    $number_lpc = $request->$text_number_lpc;
        
                    $data_lpc_get = DB::table('nt_data_ftir')
                    ->join('nt_oqa_lab', 'nt_data_ftir.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                    ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                    ->whereIn('nt_oqa_lab.id_createlab', $id_lab_ftir)
                    ->where('nt_oqa_lab.save_date', '<=', $dateend)
                    ->orderBy('nt_oqa_lab.save_date', 'DESC')
                    ->select('nt_model.id_model', DB::raw('COUNT(nt_oqa_lab.id_createlab) as total_createlab'))
                    ->groupBy('nt_model.id_model')
                    ->get();
        
                    if (count($id_lab_ftir) > 0) {
                        foreach ($data_lpc_get as $key => $value) {
                            
                            $count = DB::table('nt_data_ftir')
                            ->join('nt_oqa_lab', 'nt_data_ftir.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                            ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                            ->where('nt_model.id_model', $value->id_model)
                            ->whereIn('nt_oqa_lab.id_createlab', $id_lab_ftir)
                            ->orderBy('nt_oqa_lab.save_date', 'DESC')
                            ->count();
        
                            $dates = DB::table('nt_data_ftir')
                            ->join('nt_oqa_lab', 'nt_data_ftir.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                            ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                            ->where('nt_model.id_model', $value->id_model)
                            ->whereIn('nt_oqa_lab.id_createlab', $id_lab_ftir)
                            ->selectRaw('MIN(nt_oqa_lab.save_date) as earliest_date, MAX(nt_oqa_lab.save_date) as latest_date')
                            ->first();
        
                            $newdata =  array (
                                'id_model' => $value->id_model,
                                'id_lab' => $id_lab_ftir,
                                'count' => $number,
                                'datestart' => $dates->earliest_date, 
                                'dateend' => $dates->latest_date,   
                            );
                            array_push($dataexport_ftir,$newdata);
                            
                        }
                    } 
                }

                $namefile = 'Combine_All_Tester_'.date("YmdHis");
                $file = storage_path('NHK-File/template/report/template_calculation2.xlsx'); // for test on orange host
                
                Excel::load($file, function ($doc)use($dataexport_lpc,$dataexport_sem,$dataexport_ic,$dataexport_gcms,$dataexport_ftir,$datestart, $dateend) {

                    // $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
                    // $doc = $reader->load($file);
                    
                    $i = 5;
                    foreach ($dataexport_lpc as $val) {

                        $sheet = $doc->setActiveSheetIndexByName('ALPC');

                        $model = DB::table('nt_model')
                        ->join('nt_model_spec','nt_model_spec.id_model','=','nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->where('nt_model_spec.id_model_type', 1)
                        ->first();

                        $nt_model_spec = DB::table('nt_model_spec')
                        ->join('nt_model_spec_type', 'nt_model_spec.id_model_type', '=', 'nt_model_spec_type.id_model_type')
                        ->where('nt_model_spec.id_model', $val['id_model'])
                        ->where('nt_model_spec.id_model_type', 1)
                        ->first(); 

                        $lpccount = DB::table('nt_data_lpc')
                        ->select('nt_oqa_lab.id_createlab', 'nt_oqa_lab.save_date', 'nt_oqa_lab.id_model')
                        ->join('nt_oqa_lab', 'nt_data_lpc.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                        ->count();

                        $maxdata = DB::table('nt_data_lpc')
                        ->join('nt_oqa_lab', 'nt_data_lpc.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                        ->max('lpc_data');
                        $max = number_format($maxdata, 2, '.', '');

                        $mindata =  DB::table('nt_data_lpc')
                        ->join('nt_oqa_lab', 'nt_data_lpc.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                        ->min('lpc_data');
                        $min = number_format($mindata, 2, '.', '');

                        $meandata =  DB::table('nt_data_lpc')
                        ->join('nt_oqa_lab', 'nt_data_lpc.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                        ->avg('lpc_data');
                        $mean = number_format($meandata, 2, '.', '');
            
                        $stdget = DB::table('nt_data_lpc')
                        ->select(DB::raw('STDDEV_SAMP(nt_data_lpc.lpc_data) as std'))
                        ->join('nt_oqa_lab', 'nt_data_lpc.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                        ->first();
                        $std = number_format($stdget->std, 2, '.', '');   
                        $mean6data = $meandata + (6*$stdget->std);
                        $mean6 = number_format($mean6data, 2, '.', '');

                        $dates = DB::table('nt_data_lpc')
                        ->join('nt_oqa_lab', 'nt_data_lpc.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                        ->selectRaw('MIN(nt_oqa_lab.save_date) as earliest_date, MAX(nt_oqa_lab.save_date) as latest_date')
                        ->first();

                        $firstdate = $dates->earliest_date;
                        $lastdate = $dates->latest_date;

                        $sheet = $doc->setActiveSheetIndexByName('ALPC');
                        $sheet->setCellValue('A'.$i,$model->name_model);
                        $sheet->setCellValue('B'.$i,$model->part);
                        $sheet->setCellValue('C'.$i,$model->part_number);
                        $sheet->setCellValue('D'.$i,$lpccount);
                        $sheet->setCellValue('E'.$i,$max);
                        $sheet->setCellValue('F'.$i,$min);
                        $sheet->setCellValue('G'.$i,$mean);
                        $sheet->setCellValue('H'.$i,$std); 
                        $sheet->setCellValue('I'.$i, date('Y-m-d', strtotime($firstdate)));
                        $sheet->setCellValue('J'.$i, date('Y-m-d', strtotime($lastdate)));
                        $sheet->setCellValue('P'.$i,$nt_model_spec->alpc);
                        $i++;

                    }

                    $i = 5;
                    foreach ($dataexport_sem as $val) {

                        $model = DB::table('nt_model')
                        ->join('nt_model_spec','nt_model_spec.id_model','=','nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->where('nt_model_spec.id_model_type', 1)
                        ->first();

                        $nt_model_spec = DB::table('nt_model_spec')
                        ->join('nt_model_spec_type', 'nt_model_spec.id_model_type', '=', 'nt_model_spec_type.id_model_type')
                        ->where('nt_model_spec.id_model', $val['id_model'])
                        ->where('nt_model_spec.id_model_type', 1)
                        ->first(); 

                        $lpccount = DB::table('nt_data_sem')
                        ->select('nt_oqa_lab.id_createlab', 'nt_oqa_lab.save_date', 'nt_oqa_lab.id_model')
                        ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                        ->count();

                        $maxdata = DB::table('nt_data_sem')
                        ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                        ->max('sem_class1');
                        $max = number_format($maxdata, 2, '.', '');

                        $mindata =  DB::table('nt_data_sem')
                        ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                        ->min('sem_class1');
                        $min = number_format($mindata, 2, '.', '');

                        $meandata =  DB::table('nt_data_sem')
                        ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                        ->avg('sem_class1');
                        $mean = number_format($meandata, 2, '.', '');
            
                        $stdget = DB::table('nt_data_sem')
                        ->select(DB::raw('STDDEV_SAMP(nt_data_sem.sem_class1) as std'))
                        ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                        ->first();
                        $std = number_format($stdget->std, 2, '.', '');   
                        $mean6data = $meandata + (6*$stdget->std);
                        $mean6 = number_format($mean6data, 2, '.', '');

                        $dates = DB::table('nt_data_sem')
                        ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                        ->selectRaw('MIN(nt_oqa_lab.save_date) as earliest_date, MAX(nt_oqa_lab.save_date) as latest_date')
                        ->first();

                        $firstdate = $dates->earliest_date;
                        $lastdate = $dates->latest_date;
        
                        $sheet1 = $doc->setActiveSheetIndexByName('SEM C 1');
                        $sheet1->setCellValue('A'.$i,$model->name_model);
                        $sheet1->setCellValue('B'.$i,$model->part);
                        $sheet1->setCellValue('C'.$i,$model->part_number);
                        $sheet1->setCellValue('D'.$i,$lpccount);
                        $sheet1->setCellValue('E'.$i,$max);
                        $sheet1->setCellValue('F'.$i,$min);
                        $sheet1->setCellValue('G'.$i,$mean);
                        $sheet1->setCellValue('H'.$i,$std);
                        $sheet1->setCellValue('I'.$i, date('Y-m-d', strtotime($firstdate)));
                        $sheet1->setCellValue('J'.$i, date('Y-m-d', strtotime($lastdate)));
                        $sheet1->setCellValue('P'.$i,$mean6);
                        $sheet1->setCellValue('P'.$i, $nt_model_spec->class1);

                        $maxdata = DB::table('nt_data_sem')
                        ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                        ->max('sem_class2');
                        $max = number_format($maxdata, 2, '.', '');

                        $mindata = DB::table('nt_data_sem')
                        ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                        ->min('sem_class2');
                        $min = number_format($mindata, 2, '.', '');

                        $meandata = DB::table('nt_data_sem')
                        ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                        ->avg('sem_class2');
                        $mean = number_format($meandata, 2, '.', '');
            
                        $stdget = DB::table('nt_data_sem')
                        ->select(DB::raw('STDDEV_SAMP(nt_data_sem.sem_class2) as std'))
                        ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                        ->first();
                        $std = number_format($stdget->std, 2, '.', '');   
                        $mean6data = $meandata + (6*$stdget->std);
                        $mean6 = number_format($mean6data, 2, '.', ''); 

                        $sheet2 = $doc->setActiveSheetIndexByName('SEM C 2');
                        $sheet2->setCellValue('A'.$i,$model->name_model);
                        $sheet2->setCellValue('B'.$i,$model->part);
                        $sheet2->setCellValue('C'.$i,$model->part_number);
                        $sheet2->setCellValue('D'.$i,$lpccount);
                        $sheet2->setCellValue('E'.$i,$max);
                        $sheet2->setCellValue('F'.$i,$min);
                        $sheet2->setCellValue('G'.$i,$mean);
                        $sheet2->setCellValue('H'.$i,$std);
                        $sheet2->setCellValue('I'.$i, date('Y-m-d', strtotime($firstdate)));
                        $sheet2->setCellValue('J'.$i, date('Y-m-d', strtotime($lastdate)));
                        $sheet2->setCellValue('P'.$i,$mean6);
                        $sheet2->setCellValue('P'.$i, $nt_model_spec->class2);

                        $maxdata = DB::table('nt_data_sem')
                        ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                        ->max('sem_class3');
                        $max = number_format($maxdata, 2, '.', '');

                        $mindata = DB::table('nt_data_sem')
                        ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                        ->min('sem_class3');
                        $min = number_format($mindata, 2, '.', '');

                        $meandata = DB::table('nt_data_sem')
                        ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                        ->avg('sem_class3');
                        $mean = number_format($meandata, 2, '.', '');
            
                        $stdget = DB::table('nt_data_sem')
                        ->select(DB::raw('STDDEV_SAMP(nt_data_sem.sem_class3) as std'))
                        ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                        ->first();
                        $std = number_format($stdget->std, 2, '.', '');   
                        $mean6data = $meandata + (6*$stdget->std);
                        $mean6 = number_format($mean6data, 2, '.', ''); 
        
                        $sheet3 = $doc->setActiveSheetIndexByName('SEM C 3');
                        $sheet3->setCellValue('A'.$i,$model->name_model);
                        $sheet3->setCellValue('B'.$i,$model->part);
                        $sheet3->setCellValue('C'.$i,$model->part_number);
                        $sheet3->setCellValue('D'.$i,$lpccount);
                        $sheet3->setCellValue('E'.$i,$max);
                        $sheet3->setCellValue('F'.$i,$min);
                        $sheet3->setCellValue('G'.$i,$mean);
                        $sheet3->setCellValue('H'.$i,$std);
                        $sheet3->setCellValue('I'.$i, date('Y-m-d', strtotime($firstdate)));
                        $sheet3->setCellValue('J'.$i, date('Y-m-d', strtotime($lastdate)));
                        $sheet3->setCellValue('P'.$i,$mean6);
                        $sheet3->setCellValue('P'.$i, $nt_model_spec->class2);
                    
                        $maxdata = DB::table('nt_data_sem')
                        ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                        ->max('sem_class3_1');
                        $max = number_format($maxdata, 2, '.', '');

                        $mindata = DB::table('nt_data_sem')
                        ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                        ->min('sem_class3_1');
                        $min = number_format($mindata, 2, '.', '');

                        $meandata = DB::table('nt_data_sem')
                        ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                        ->avg('sem_class3_1');
                        $mean = number_format($meandata, 2, '.', '');
            
                        $stdget = DB::table('nt_data_sem')
                        ->select(DB::raw('STDDEV_SAMP(nt_data_sem.sem_class3_1) as std'))
                        ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                        ->first();
                        $std = number_format($stdget->std, 2, '.', '');   
                        $mean6data = $meandata + (6*$stdget->std);
                        $mean6 = number_format($mean6data, 2, '.', ''); 

                        $sheet3_1 = $doc->setActiveSheetIndexByName('SEM C 3.1');
                        $sheet3_1->setCellValue('A'.$i,$model->name_model);
                        $sheet3_1->setCellValue('B'.$i,$model->part);
                        $sheet3_1->setCellValue('C'.$i,$model->part_number);
                        $sheet3_1->setCellValue('D'.$i,$lpccount);
                        $sheet3_1->setCellValue('E'.$i,$max);
                        $sheet3_1->setCellValue('F'.$i,$min);
                        $sheet3_1->setCellValue('G'.$i,$mean);
                        $sheet3_1->setCellValue('H'.$i,$std);
                        $sheet3_1->setCellValue('I'.$i, date('Y-m-d', strtotime($firstdate)));
                        $sheet3_1->setCellValue('J'.$i, date('Y-m-d', strtotime($lastdate)));
                        $sheet3_1->setCellValue('P'.$i,$mean6);
                        $sheet3_1->setCellValue('P'.$i, $nt_model_spec->class3_1);

                        $maxdata = DB::table('nt_data_sem')
                        ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                        ->max('sem_class3_2');
                        $max = number_format($maxdata, 2, '.', '');

                        $mindata = DB::table('nt_data_sem')
                        ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                        ->min('sem_class3_2');
                        $min = number_format($mindata, 2, '.', '');

                        $meandata = DB::table('nt_data_sem')
                        ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                        ->avg('sem_class3_2');
                        $mean = number_format($meandata, 2, '.', '');
            
                        $stdget = DB::table('nt_data_sem')
                        ->select(DB::raw('STDDEV_SAMP(nt_data_sem.sem_class3_2) as std'))
                        ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                        ->first();
                        $std = number_format($stdget->std, 2, '.', '');   
                        $mean6data = $meandata + (6*$stdget->std);
                        $mean6 = number_format($mean6data, 2, '.', ''); 

                        $sheet3_2 = $doc->setActiveSheetIndexByName('SEM C 3.2');
                        $sheet3_2->setCellValue('A'.$i,$model->name_model);
                        $sheet3_2->setCellValue('B'.$i,$model->part);
                        $sheet3_2->setCellValue('C'.$i,$model->part_number);
                        $sheet3_2->setCellValue('D'.$i,$lpccount);
                        $sheet3_2->setCellValue('E'.$i,$max);
                        $sheet3_2->setCellValue('F'.$i,$min);
                        $sheet3_2->setCellValue('G'.$i,$mean);
                        $sheet3_2->setCellValue('H'.$i,$std);
                        $sheet3_2->setCellValue('I'.$i, date('Y-m-d', strtotime($firstdate)));
                        $sheet3_2->setCellValue('J'.$i, date('Y-m-d', strtotime($lastdate)));
                        $sheet3_2->setCellValue('P'.$i,$mean6);
                        $sheet3_2->setCellValue('P'.$i, $nt_model_spec->class3_2);

                        $maxdata = DB::table('nt_data_sem')
                        ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                        ->max('sem_class3_3');
                        $max = number_format($maxdata, 2, '.', '');

                        $mindata = DB::table('nt_data_sem')
                        ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                        ->min('sem_class3_3');
                        $min = number_format($mindata, 2, '.', '');

                        $meandata = DB::table('nt_data_sem')
                        ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                        ->avg('sem_class3_3');
                        $mean = number_format($meandata, 2, '.', '');
            
                        $stdget = DB::table('nt_data_sem')
                        ->select(DB::raw('STDDEV_SAMP(nt_data_sem.sem_class3_3) as std'))
                        ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                        ->first();
                        $std = number_format($stdget->std, 2, '.', '');   
                        $mean6data = $meandata + (6*$stdget->std);
                        $mean6 = number_format($mean6data, 2, '.', ''); 

                        $sheet3_3 = $doc->setActiveSheetIndexByName('SEM C 3.3');
                        $sheet3_3->setCellValue('A'.$i,$model->name_model);
                        $sheet3_3->setCellValue('B'.$i,$model->part);
                        $sheet3_3->setCellValue('C'.$i,$model->part_number);
                        $sheet3_3->setCellValue('D'.$i,$lpccount);
                        $sheet3_3->setCellValue('E'.$i,$max);
                        $sheet3_3->setCellValue('F'.$i,$min);
                        $sheet3_3->setCellValue('G'.$i,$mean);
                        $sheet3_3->setCellValue('H'.$i,$std);
                        $sheet3_3->setCellValue('I'.$i, date('Y-m-d', strtotime($firstdate)));
                        $sheet3_3->setCellValue('J'.$i, date('Y-m-d', strtotime($lastdate)));
                        $sheet3_3->setCellValue('P'.$i,$mean6);
                        $sheet3_3->setCellValue('P'.$i, $nt_model_spec->class3_3);

                        $maxdata = DB::table('nt_data_sem')
                        ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                        ->max('sem_class4');
                        $max = number_format($maxdata, 2, '.', '');
                        
                        $mindata = DB::table('nt_data_sem')
                        ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                        ->min('sem_class4');
                        $min = number_format($mindata, 2, '.', '');
                        
                        $meandata = DB::table('nt_data_sem')
                        ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                        ->avg('sem_class4');
                        $mean = number_format($meandata, 2, '.', '');
            
                        $stdget = DB::table('nt_data_sem')
                        ->select(DB::raw('STDDEV_SAMP(nt_data_sem.sem_class4) as std'))
                        ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                        ->first();
                        $std = number_format($stdget->std, 2, '.', '');   
                        $mean6data = $meandata + (6*$stdget->std);
                        $mean6 = number_format($mean6data, 2, '.', ''); 

                        $sheet4 = $doc->setActiveSheetIndexByName('SEM C 4');
                        $sheet4->setCellValue('A'.$i,$model->name_model);
                        $sheet4->setCellValue('B'.$i,$model->part);
                        $sheet4->setCellValue('C'.$i,$model->part_number);
                        $sheet4->setCellValue('D'.$i,$lpccount);
                        $sheet4->setCellValue('E'.$i,$max);
                        $sheet4->setCellValue('F'.$i,$min);
                        $sheet4->setCellValue('G'.$i,$mean);
                        $sheet4->setCellValue('H'.$i,$std);
                        $sheet4->setCellValue('I'.$i, date('Y-m-d', strtotime($firstdate)));
                        $sheet4->setCellValue('J'.$i, date('Y-m-d', strtotime($lastdate)));
                        $sheet4->setCellValue('P'.$i,$mean6);
                        $sheet4->setCellValue('P'.$i, $nt_model_spec->class4);

                        $maxdata = DB::table('nt_data_sem')
                        ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                        ->max('sem_class5');
                        $max = number_format($maxdata, 2, '.', '');

                        $mindata = DB::table('nt_data_sem')
                        ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                        ->min('sem_class5');
                        $min = number_format($mindata, 2, '.', '');

                        $meandata = DB::table('nt_data_sem')
                        ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                        ->avg('sem_class5');
                        $mean = number_format($meandata, 2, '.', '');
            
                        $stdget = DB::table('nt_data_sem')
                        ->select(DB::raw('STDDEV_SAMP(nt_data_sem.sem_class5) as std'))
                        ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                        ->first();
                        $std = number_format($stdget->std, 2, '.', '');   
                        $mean6data = $meandata + (6*$stdget->std);
                        $mean6 = number_format($mean6data, 2, '.', '');  

                        $sheet5 = $doc->setActiveSheetIndexByName('SEM C 5');
                        $sheet5->setCellValue('A'.$i,$model->name_model);
                        $sheet5->setCellValue('B'.$i,$model->part);
                        $sheet5->setCellValue('C'.$i,$model->part_number);
                        $sheet5->setCellValue('D'.$i,$lpccount);
                        $sheet5->setCellValue('E'.$i,$max);
                        $sheet5->setCellValue('F'.$i,$min);
                        $sheet5->setCellValue('G'.$i,$mean);
                        $sheet5->setCellValue('H'.$i,$std);
                        $sheet5->setCellValue('I'.$i, date('Y-m-d', strtotime($firstdate)));
                        $sheet5->setCellValue('J'.$i, date('Y-m-d', strtotime($lastdate)));
                        $sheet5->setCellValue('P'.$i,$mean6);
                        $sheet5->setCellValue('P'.$i, $nt_model_spec->class5);

                        $maxdata = DB::table('nt_data_sem')
                        ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                        ->max('sem_class5_1');
                        $max = number_format($maxdata, 2, '.', '');

                        $mindata = DB::table('nt_data_sem')
                        ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                        ->min('sem_class5_1');
                        $min = number_format($mindata, 2, '.', '');

                        $meandata = DB::table('nt_data_sem')
                        ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                        ->avg('sem_class5_1');
                        $mean = number_format($meandata, 2, '.', '');
            
                        $stdget = DB::table('nt_data_sem')
                        ->select(DB::raw('STDDEV_SAMP(nt_data_sem.sem_class5_1) as std'))
                        ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                        ->first();
                        $std = number_format($stdget->std, 2, '.', '');   
                        $mean6data = $meandata + (6*$stdget->std);
                        $mean6 = number_format($mean6data, 2, '.', ''); 

                        $sheet5_1 = $doc->setActiveSheetIndexByName('SEM C 5.1');
                        $sheet5_1->setCellValue('A'.$i,$model->name_model);
                        $sheet5_1->setCellValue('B'.$i,$model->part);
                        $sheet5_1->setCellValue('C'.$i,$model->part_number);
                        $sheet5_1->setCellValue('D'.$i,$lpccount);
                        $sheet5_1->setCellValue('E'.$i,$max);
                        $sheet5_1->setCellValue('F'.$i,$min);
                        $sheet5_1->setCellValue('G'.$i,$mean);
                        $sheet5_1->setCellValue('H'.$i,$std);
                        $sheet5_1->setCellValue('I'.$i, date('Y-m-d', strtotime($firstdate)));
                        $sheet5_1->setCellValue('J'.$i, date('Y-m-d', strtotime($lastdate)));
                        $sheet5_1->setCellValue('P'.$i,$mean6);
                        $sheet5_1->setCellValue('P'.$i, $nt_model_spec->class5_1);

                        $maxdata = DB::table('nt_data_sem')
                        ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                        ->max('sem_class6');
                        $max = number_format($maxdata, 2, '.', '');

                        $mindata = DB::table('nt_data_sem')
                        ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                        ->min('sem_class6');
                        $min = number_format($mindata, 2, '.', '');

                        $meandata = DB::table('nt_data_sem')
                        ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                        ->avg('sem_class6');
                        $mean = number_format($meandata, 2, '.', '');
            
                        $stdget = DB::table('nt_data_sem')
                        ->select(DB::raw('STDDEV_SAMP(nt_data_sem.sem_class6) as std'))
                        ->join('nt_oqa_lab', 'nt_data_sem.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                        ->first();
                        $std = number_format($stdget->std, 2, '.', '');   
                        $mean6data = $meandata + (6*$stdget->std);
                        $mean6 = number_format($mean6data, 2, '.', ''); 

                        $sheet6 = $doc->setActiveSheetIndexByName('SEM C 6');
                        $sheet6->setCellValue('A'.$i,$model->name_model);
                        $sheet6->setCellValue('B'.$i,$model->part);
                        $sheet6->setCellValue('C'.$i,$model->part_number);
                        $sheet6->setCellValue('D'.$i,$lpccount);
                        $sheet6->setCellValue('E'.$i,$max);
                        $sheet6->setCellValue('F'.$i,$min);
                        $sheet6->setCellValue('G'.$i,$mean);
                        $sheet6->setCellValue('H'.$i,$std);
                        $sheet6->setCellValue('I'.$i, date('Y-m-d', strtotime($firstdate)));
                        $sheet6->setCellValue('J'.$i, date('Y-m-d', strtotime($lastdate)));
                        $sheet6->setCellValue('P'.$i,$mean6);
                        $sheet6->setCellValue('P'.$i, $nt_model_spec->class6);

                        $i++;

                    }

                    $i = 5;
                    foreach ($dataexport_ic as $val) {

                        $model = DB::table('nt_model')
                        ->join('nt_model_spec','nt_model_spec.id_model','=','nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->where('nt_model_spec.id_model_type', 1)
                        ->first();

                        $nt_model_spec = DB::table('nt_model_spec')
                        ->join('nt_model_spec_type', 'nt_model_spec.id_model_type', '=', 'nt_model_spec_type.id_model_type')
                        ->where('nt_model_spec.id_model', $val['id_model'])
                        ->where('nt_model_spec.id_model_type', 1)
                        ->first(); 

                        $lpccount = DB::table('nt_data_ic')
                        ->select('nt_oqa_lab.id_createlab', 'nt_oqa_lab.save_date', 'nt_oqa_lab.id_model')
                        ->join('nt_oqa_lab', 'nt_data_ic.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                        ->count();

                        $maxdata = DB::table('nt_data_ic')
                        ->join('nt_oqa_lab', 'nt_data_ic.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                        ->max('ic_anion_up');
                        $max = number_format($maxdata, 2, '.', '');

                        $mindata =  DB::table('nt_data_ic')
                        ->join('nt_oqa_lab', 'nt_data_ic.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                        ->min('ic_anion_up');
                        $min = number_format($mindata, 2, '.', '');

                        $meandata =  DB::table('nt_data_ic')
                        ->join('nt_oqa_lab', 'nt_data_ic.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                        ->avg('ic_anion_up');
                        $mean = number_format($meandata, 2, '.', '');
            
                        $stdget = DB::table('nt_data_ic')
                        ->select(DB::raw('STDDEV_SAMP(nt_data_ic.ic_anion_up) as std'))
                        ->join('nt_oqa_lab', 'nt_data_ic.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                        ->first();
                        $std = number_format($stdget->std, 2, '.', '');   
                        $mean6data = $meandata + (6*$stdget->std);
                        $mean6 = number_format($mean6data, 2, '.', '');

                        $dates = DB::table('nt_data_ic')
                        ->join('nt_oqa_lab', 'nt_data_ic.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                        ->selectRaw('MIN(nt_oqa_lab.save_date) as earliest_date, MAX(nt_oqa_lab.save_date) as latest_date')
                        ->first();

                        $firstdate = $dates->earliest_date;
                        $lastdate = $dates->latest_date;

                        $datacal_ic = $this->getValue_calculation_ic($val['id_model'],$firstdate,$lastdate);

                        $sheet = $doc->setActiveSheetIndex(11);
                        $sheet->setCellValue('A'.$i,$model->name_model);
                        $sheet->setCellValue('B'.$i,$model->part);
                        $sheet->setCellValue('C'.$i,$model->part_number);
                        $sheet->setCellValue('D'.$i,$lpccount);
                        $sheet->setCellValue('E'.$i,$max);
                        $sheet->setCellValue('F'.$i,$min);
                        $sheet->setCellValue('G'.$i,$mean);
                        $sheet->setCellValue('H'.$i,$datacal_ic['sigma']);
                        $sheet->setCellValue('I'.$i, date('Y-m-d', strtotime($firstdate)));
                        $sheet->setCellValue('J'.$i, date('Y-m-d', strtotime($lastdate)));
                        $sheet->setCellValue('P'.$i,$mean6);

                        $maxdata = DB::table('nt_data_ic')
                        ->join('nt_oqa_lab', 'nt_data_ic.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                        ->max('ic_sulphate');
                        $max = number_format($maxdata, 2, '.', '');

                        $mindata = DB::table('nt_data_ic')
                        ->join('nt_oqa_lab', 'nt_data_ic.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                        ->min('ic_sulphate');
                        $min = number_format($mindata, 2, '.', '');

                        $meandata = DB::table('nt_data_ic')
                        ->join('nt_oqa_lab', 'nt_data_ic.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                        ->avg('ic_sulphate');
                        $mean = number_format($meandata, 2, '.', '');
            
                        $stdget = DB::table('nt_data_ic')
                        ->select(DB::raw('STDDEV_SAMP(nt_data_ic.ic_sulphate) as std'))
                        ->join('nt_oqa_lab', 'nt_data_ic.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                        ->first();
                        $std = number_format($stdget->std, 2, '.', '');   
                        $mean6data = $meandata + (6*$stdget->std);
                        $mean6 = number_format($mean6data, 2, '.', ''); 

                        $sheet = $doc->setActiveSheetIndex(12); 
                        $sheet->setCellValue('A'.$i,$model->name_model);
                        $sheet->setCellValue('B'.$i,$model->part);
                        $sheet->setCellValue('C'.$i,$model->part_number);
                        $sheet->setCellValue('D'.$i,$lpccount);
                        $sheet->setCellValue('E'.$i,$max);
                        $sheet->setCellValue('F'.$i,$min);
                        $sheet->setCellValue('G'.$i,$mean);
                        $sheet->setCellValue('H'.$i,$std);
                        $sheet->setCellValue('I'.$i, date('Y-m-d', strtotime($firstdate)));
                        $sheet->setCellValue('I'.$i, date('Y-m-d', strtotime($firstdate)));
                        $sheet->setCellValue('P'.$i,$mean6);

                        $maxdata = DB::table('nt_data_ic')
                        ->join('nt_oqa_lab', 'nt_data_ic.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                        ->max('ic_cation_up');
                        $max = number_format($maxdata, 2, '.', '');

                        $mindata = DB::table('nt_data_ic')
                        ->join('nt_oqa_lab', 'nt_data_ic.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                        ->min('ic_cation_up');
                        $min = number_format($mindata, 2, '.', '');

                        $meandata = DB::table('nt_data_ic')
                        ->join('nt_oqa_lab', 'nt_data_ic.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                        ->avg('ic_cation_up');
                        $mean = number_format($meandata, 2, '.', '');
            
                        $stdget = DB::table('nt_data_ic')
                        ->select(DB::raw('STDDEV_SAMP(nt_data_ic.ic_cation_up) as std'))
                        ->join('nt_oqa_lab', 'nt_data_ic.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                        ->first();
                        $std = number_format($stdget->std, 2, '.', '');   
                        $mean6data = $meandata + (6*$stdget->std);
                        $mean6 = number_format($mean6data, 2, '.', ''); 

                        $sheet = $doc->setActiveSheetIndex(13); 
                        $sheet->setCellValue('A'.$i,$model->name_model);
                        $sheet->setCellValue('B'.$i,$model->part);
                        $sheet->setCellValue('C'.$i,$model->part_number);
                        $sheet->setCellValue('D'.$i,$lpccount);
                        $sheet->setCellValue('E'.$i,$max);
                        $sheet->setCellValue('F'.$i,$min);
                        $sheet->setCellValue('G'.$i,$mean);
                        $sheet->setCellValue('H'.$i,$std);
                        $sheet->setCellValue('I'.$i, date('Y-m-d', strtotime($firstdate)));
                        $sheet->setCellValue('I'.$i, date('Y-m-d', strtotime($firstdate)));
                        $sheet->setCellValue('P'.$i,$mean6);


                        $i++;

                    }

                    $i = 5;
                    foreach ($dataexport_gcms as $val) {

                        $model = DB::table('nt_model')
                        ->join('nt_model_spec','nt_model_spec.id_model','=','nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->where('nt_model_spec.id_model_type', 1)
                        ->first();

                        $nt_model_spec = DB::table('nt_model_spec')
                        ->join('nt_model_spec_type', 'nt_model_spec.id_model_type', '=', 'nt_model_spec_type.id_model_type')
                        ->where('nt_model_spec.id_model', $val['id_model'])
                        ->where('nt_model_spec.id_model_type', 1)
                        ->first(); 

                        $lpccount = DB::table('nt_data_gcms')
                        ->select('nt_oqa_lab.id_createlab', 'nt_oqa_lab.save_date', 'nt_oqa_lab.id_model')
                        ->join('nt_oqa_lab', 'nt_data_gcms.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                        ->count();

                        $maxdata = DB::table('nt_data_gcms')
                        ->join('nt_oqa_lab', 'nt_data_gcms.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                        ->max('gcms_outgassing');
                        $max = number_format($maxdata, 2, '.', '');

                        $mindata =  DB::table('nt_data_gcms')
                        ->join('nt_oqa_lab', 'nt_data_gcms.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                        ->min('gcms_outgassing');
                        $min = number_format($mindata, 2, '.', '');

                        $meandata =  DB::table('nt_data_gcms')
                        ->join('nt_oqa_lab', 'nt_data_gcms.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                        ->avg('gcms_outgassing');
                        $mean = number_format($meandata, 2, '.', '');
            
                        $stdget = DB::table('nt_data_gcms')
                        ->select(DB::raw('STDDEV_SAMP(nt_data_gcms.gcms_outgassing) as std'))
                        ->join('nt_oqa_lab', 'nt_data_gcms.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                        ->first();
                        $std = number_format($stdget->std, 2, '.', '');   
                        $mean6data = $meandata + (6*$stdget->std);
                        $mean6 = number_format($mean6data, 2, '.', '');

                        $dates = DB::table('nt_data_gcms')
                        ->join('nt_oqa_lab', 'nt_data_gcms.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                        ->selectRaw('MIN(nt_oqa_lab.save_date) as earliest_date, MAX(nt_oqa_lab.save_date) as latest_date')
                        ->first();

                        $firstdate = $dates->earliest_date;
                        $lastdate = $dates->latest_date;

                        $sheet = $doc->setActiveSheetIndex(14);
                        $sheet->setCellValue('A'.$i,$model->name_model);
                        $sheet->setCellValue('B'.$i,$model->part);
                        $sheet->setCellValue('C'.$i,$model->part_number);
                        $sheet->setCellValue('D'.$i,$lpccount);
                        $sheet->setCellValue('E'.$i,$max);
                        $sheet->setCellValue('F'.$i,$min);
                        $sheet->setCellValue('G'.$i,$mean);
                        $sheet->setCellValue('H'.$i,$std);
                        $sheet->setCellValue('I'.$i, date('Y-m-d', strtotime($firstdate)));
                        $sheet->setCellValue('J'.$i, date('Y-m-d', strtotime($lastdate)));
                        $sheet->setCellValue('P'.$i, $nt_model_spec->outgassing );

                        $maxdata = DB::table('nt_data_gcms')
                        ->join('nt_oqa_lab', 'nt_data_gcms.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                        ->max('gcms_low_mw');
                        $max = number_format($maxdata, 2, '.', '');

                        $mindata =  DB::table('nt_data_gcms')
                        ->join('nt_oqa_lab', 'nt_data_gcms.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                        ->min('gcms_low_mw');
                        $min = number_format($mindata, 2, '.', '');

                        $meandata =  DB::table('nt_data_gcms')
                        ->join('nt_oqa_lab', 'nt_data_gcms.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                        ->avg('gcms_low_mw');
                        $mean = number_format($meandata, 2, '.', '');
            
                        $stdget = DB::table('nt_data_gcms')
                        ->select(DB::raw('STDDEV_SAMP(nt_data_gcms.gcms_low_mw) as std'))
                        ->join('nt_oqa_lab', 'nt_data_gcms.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                        ->first();
                        $std = number_format($stdget->std, 2, '.', '');   
                        $mean6data = $meandata + (6*$stdget->std);
                        $mean6 = number_format($mean6data, 2, '.', '');

                        $dates = DB::table('nt_data_gcms')
                        ->join('nt_oqa_lab', 'nt_data_gcms.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                        ->selectRaw('MIN(nt_oqa_lab.save_date) as earliest_date, MAX(nt_oqa_lab.save_date) as latest_date')
                        ->first();

                        $firstdate = $dates->earliest_date;
                        $lastdate = $dates->latest_date;

                        $sheet = $doc->setActiveSheetIndex(15);
                        $sheet->setCellValue('A'.$i,$model->name_model);
                        $sheet->setCellValue('B'.$i,$model->part);
                        $sheet->setCellValue('C'.$i,$model->part_number);
                        $sheet->setCellValue('D'.$i,$lpccount);
                        $sheet->setCellValue('E'.$i,$max);
                        $sheet->setCellValue('F'.$i,$min);
                        $sheet->setCellValue('G'.$i,$mean);
                        $sheet->setCellValue('H'.$i,$std);
                        $sheet->setCellValue('I'.$i, date('Y-m-d', strtotime($firstdate)));
                        $sheet->setCellValue('J'.$i, date('Y-m-d', strtotime($lastdate)));
                        $sheet->setCellValue('P'.$i, $nt_model_spec->low_MW );

                        $maxdata = DB::table('nt_data_gcms')
                        ->join('nt_oqa_lab', 'nt_data_gcms.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                        ->max('gcms_high_mw');
                        $max = number_format($maxdata, 2, '.', '');

                        $mindata =  DB::table('nt_data_gcms')
                        ->join('nt_oqa_lab', 'nt_data_gcms.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                        ->min('gcms_high_mw');
                        $min = number_format($mindata, 2, '.', '');

                        $meandata =  DB::table('nt_data_gcms')
                        ->join('nt_oqa_lab', 'nt_data_gcms.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                        ->avg('gcms_high_mw');
                        $mean = number_format($meandata, 2, '.', '');
            
                        $stdget = DB::table('nt_data_gcms')
                        ->select(DB::raw('STDDEV_SAMP(nt_data_gcms.gcms_high_mw) as std'))
                        ->join('nt_oqa_lab', 'nt_data_gcms.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                        ->first();
                        $std = number_format($stdget->std, 2, '.', '');   
                        $mean6data = $meandata + (6*$stdget->std);
                        $mean6 = number_format($mean6data, 2, '.', '');

                        $dates = DB::table('nt_data_gcms')
                        ->join('nt_oqa_lab', 'nt_data_gcms.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                        ->selectRaw('MIN(nt_oqa_lab.save_date) as earliest_date, MAX(nt_oqa_lab.save_date) as latest_date')
                        ->first();

                        $firstdate = $dates->earliest_date;
                        $lastdate = $dates->latest_date;

                        $sheet = $doc->setActiveSheetIndex(16);
                        $sheet->setCellValue('A'.$i,$model->name_model);
                        $sheet->setCellValue('B'.$i,$model->part);
                        $sheet->setCellValue('C'.$i,$model->part_number);
                        $sheet->setCellValue('D'.$i,$lpccount);
                        $sheet->setCellValue('E'.$i,$max);
                        $sheet->setCellValue('F'.$i,$min);
                        $sheet->setCellValue('G'.$i,$mean);
                        $sheet->setCellValue('H'.$i,$std);
                        $sheet->setCellValue('I'.$i, date('Y-m-d', strtotime($firstdate)));
                        $sheet->setCellValue('J'.$i, date('Y-m-d', strtotime($lastdate)));
                        $sheet->setCellValue('P'.$i, $nt_model_spec->high_MW );

                        $maxdata = DB::table('nt_data_gcms')
                        ->join('nt_oqa_lab', 'nt_data_gcms.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                        ->max('gcms_smear');
                        $max = number_format($maxdata, 2, '.', '');

                        $mindata =  DB::table('nt_data_gcms')
                        ->join('nt_oqa_lab', 'nt_data_gcms.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                        ->min('gcms_smear');
                        $min = number_format($mindata, 2, '.', '');

                        $meandata =  DB::table('nt_data_gcms')
                        ->join('nt_oqa_lab', 'nt_data_gcms.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                        ->avg('gcms_smear');
                        $mean = number_format($meandata, 2, '.', '');
            
                        $stdget = DB::table('nt_data_gcms')
                        ->select(DB::raw('STDDEV_SAMP(nt_data_gcms.gcms_smear) as std'))
                        ->join('nt_oqa_lab', 'nt_data_gcms.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                        ->first();
                        $std = number_format($stdget->std, 2, '.', '');   
                        $mean6data = $meandata + (6*$stdget->std);
                        $mean6 = number_format($mean6data, 2, '.', '');

                        $dates = DB::table('nt_data_gcms')
                        ->join('nt_oqa_lab', 'nt_data_gcms.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                        ->selectRaw('MIN(nt_oqa_lab.save_date) as earliest_date, MAX(nt_oqa_lab.save_date) as latest_date')
                        ->first();

                        $firstdate = $dates->earliest_date;
                        $lastdate = $dates->latest_date;

                        $sheet = $doc->setActiveSheetIndex(17);
                        $sheet->setCellValue('A'.$i,$model->name_model);
                        $sheet->setCellValue('B'.$i,$model->part);
                        $sheet->setCellValue('C'.$i,$model->part_number);
                        $sheet->setCellValue('D'.$i,$lpccount);
                        $sheet->setCellValue('E'.$i,$max);
                        $sheet->setCellValue('F'.$i,$min);
                        $sheet->setCellValue('G'.$i,$mean);
                        $sheet->setCellValue('H'.$i,$std);
                        $sheet->setCellValue('I'.$i, date('Y-m-d', strtotime($firstdate)));
                        $sheet->setCellValue('J'.$i, date('Y-m-d', strtotime($lastdate)));
                        $sheet->setCellValue('P'.$i, $nt_model_spec->alkylacrylate );

                        $i++;

                    }

                    $i = 5;
                    foreach ($dataexport_ftir as $val) {

                        $model = DB::table('nt_model')
                        ->join('nt_model_spec','nt_model_spec.id_model','=','nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->where('nt_model_spec.id_model_type', 1)
                        ->first();

                        $nt_model_spec = DB::table('nt_model_spec')
                        ->join('nt_model_spec_type', 'nt_model_spec.id_model_type', '=', 'nt_model_spec_type.id_model_type')
                        ->where('nt_model_spec.id_model', $val['id_model'])
                        ->where('nt_model_spec.id_model_type', 1)
                        ->first(); 

                        $lpccount = DB::table('nt_data_ftir')
                        ->select('nt_oqa_lab.id_createlab', 'nt_oqa_lab.save_date', 'nt_oqa_lab.id_model')
                        ->join('nt_oqa_lab', 'nt_data_ftir.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                        ->count();

                        $maxdata = DB::table('nt_data_ftir')
                        ->join('nt_oqa_lab', 'nt_data_ftir.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                        ->max('ftir_silicone');
                        $max = number_format($maxdata, 2, '.', '');

                        $mindata =  DB::table('nt_data_ftir')
                        ->join('nt_oqa_lab', 'nt_data_ftir.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                        ->min('ftir_silicone');
                        $min = number_format($mindata, 2, '.', '');

                        $meandata =  DB::table('nt_data_ftir')
                        ->join('nt_oqa_lab', 'nt_data_ftir.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                        ->avg('ftir_silicone');
                        $mean = number_format($meandata, 2, '.', '');
            
                        $stdget = DB::table('nt_data_ftir')
                        ->select(DB::raw('STDDEV_SAMP(nt_data_ftir.ftir_silicone) as std'))
                        ->join('nt_oqa_lab', 'nt_data_ftir.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                        ->first();
                        $std = number_format($stdget->std, 2, '.', '');   
                        $mean6data = $meandata + (6*$stdget->std);
                        $mean6 = number_format($mean6data, 2, '.', '');

                        $dates = DB::table('nt_data_ftir')
                        ->join('nt_oqa_lab', 'nt_data_ftir.id_createlab', '=', 'nt_oqa_lab.id_createlab')
                        ->join('nt_model', 'nt_oqa_lab.id_model', '=', 'nt_model.id_model')
                        ->where('nt_model.id_model', $val['id_model'])
                        ->whereIn('nt_oqa_lab.id_createlab', $val['id_lab'])
                        ->selectRaw('MIN(nt_oqa_lab.save_date) as earliest_date, MAX(nt_oqa_lab.save_date) as latest_date')
                        ->first();

                        $firstdate = $dates->earliest_date;
                        $lastdate = $dates->latest_date;

                        $sheet = $doc->setActiveSheetIndexByName('FTIR');
                        $sheet->setCellValue('A'.$i,$model->name_model);
                        $sheet->setCellValue('B'.$i,$model->part);
                        $sheet->setCellValue('C'.$i,$model->part_number);
                        $sheet->setCellValue('D'.$i,$lpccount);
                        $sheet->setCellValue('E'.$i,$max);
                        $sheet->setCellValue('F'.$i,$min);
                        $sheet->setCellValue('G'.$i,$mean);
                        $sheet->setCellValue('H'.$i,$std);
                        $sheet->setCellValue('I'.$i, date('Y-m-d', strtotime($firstdate)));
                        $sheet->setCellValue('J'.$i, date('Y-m-d', strtotime($lastdate)));
                        $sheet->setCellValue('P'.$i, $nt_model_spec->ftir );
        
                        $i++;

                    }

                    // $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($doc, "Xlsx");
                    // $writer->save(storage_path('NHK-File/report/calculation/'.$namefile.'.xlsx'));
                })->setFilename($namefile)->store('xlsx', storage_path('NHK-File/report/calculation'));

                return response()->json(['success' =>  1,'filename' => $namefile.'.xlsx']);
            }
        }

        return response()->json(['success' =>  2]);


    }
    
    //  -------------- CHECK AVE -------------- 

    public function check_value_avc_lpc()
    {

        $checklpc = DB::Table('nt_data_lpc')->whereNotNull('lpc1')->whereNull('lpc_data')->get();
        foreach ($checklpc as $key => $value) {
            
            $dataf = DB::table('nt_datacf_lpc')->where('id_createlab', $value->id_createlab)->first();
            if(!empty($dataf)){
                $save['lpc_data'] = $dataf->lpc_data;
                DB::table('nt_data_lpc')->where('data_lpc_id', $value->data_lpc_id)->update($save);
            }

        }

    }
    

}
