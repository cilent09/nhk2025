<?php
	namespace App\Http\Controllers;
	use Illuminate\Http\Request;
	use PhpOffice\PhpSpreadsheet\Spreadsheet;
	use PhpOffice\PhpSpreadsheet\IOFactory;
	use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    use Symfony\Component\HttpFoundation\StreamedResponse;

    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Facades\Auth;
    use PhpOffice\PhpSpreadsheet\Reader\Xlsx as XlsxReader; 
    use PhpOffice\PhpSpreadsheet\Style\Color;

	use DB;
	use File;
	use PhpOffice\PhpSpreadsheet\Chart\Chart;
	use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
	use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
	use PhpOffice\PhpSpreadsheet\Chart\Layout;
	use PhpOffice\PhpSpreadsheet\Chart\Legend;
	use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
	use PhpOffice\PhpSpreadsheet\Chart\Title;
	use PhpOffice\PhpSpreadsheet\Chart\Axis;
	use PhpOffice\PhpSpreadsheet\Chart\GridLines;
	use PhpOffice\PhpSpreadsheet\Cell\Cell;
	use PhpOffice\PhpSpreadsheet\Style\Alignment;
	use PhpOffice\PhpSpreadsheet\Style\Border;
	use PhpOffice\PhpSpreadsheet\Style\Fill;
    use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
    use Session;
    use Datatables;
    use Hash;
    use slope;
    use PDF;
    use Dompdf\Dompdf;

    use Excel;
    use DateTime;

class SCCController extends Controller
{
     
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function scc_add_file(Request $request)
    {

        try {
            $request->validate([
                'scc_file' => 'required|file|mimes:xlsx,xls,csv|max:10240', // 10MB max
            ]);

            // ดึงชื่อไฟล์เดิมจาก DB
            $oldFile = DB::table('nt_model')
                ->where('id_model', $request->id_model_scc)
                ->value('scc_template_file');

            // ถ้ามีไฟล์เดิมให้ลบ
            if ($oldFile) {
                $oldPath = storage_path('app/public/SCC_template/' . $oldFile);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            // อัปโหลดไฟล์ใหม่
            $file = $request->file('scc_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/SCC_template', $filename);

            // อัปเดตชื่อไฟล์ใหม่ใน DB
            DB::table('nt_model')->where('id_model', $request->id_model_scc)->update([
                'scc_template_file' => $filename
            ]);

            return response()->json(['success' => 1]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()]);
        }

    }

    public function uploadWDSCC($id)
    {
        $oqafile = DB::table('nt_oqa_file')->where('id_createlab',$id)->first();
        $oqalab = DB::table('nt_oqa_lab')->where('id_createlab',$id)->first();
        return view('OQA/uploadSCC', ['oqafile' =>$oqafile,'oqalab' =>$oqalab]);
    }

    public function uplaodSCCSubmit(Request $request)
    {

        try {

            $request->validate([
                'customFile' => 'required|file', // 10MB max
            ]);

            // ดึงชื่อไฟล์เดิมจาก DB
            $oldFile = DB::table('nt_oqa_lab')
                ->where('id_createlab', $request->idfile)
                ->value('scc_file_customer');

            // ถ้ามีไฟล์เดิมให้ลบ
            if ($oldFile) {
                $oldPath = storage_path('NHK-File/report/scc/' . $oldFile);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            // อัปโหลดไฟล์ใหม่
            $file = $request->file('customFile');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = storage_path('NHK-File/report/scc');
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            $file->move($path, $filename);

            // อัปเดตชื่อไฟล์ใหม่ใน DB
            DB::table('nt_oqa_lab')->where('id_createlab', $request->idfile)->update([
                'scc_file_customer' => $filename
            ]);

            $file = storage_path('NHK-File/report/scc/'.$filename);
            $reader = new XlsxReader();
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($file);
            $worksheet = $spreadsheet->getSheetByName('SCC Format');
            
            $fy = $worksheet->getCell("A6")->getCalculatedValue();
            $ww = $worksheet->getCell("B6")->getCalculatedValue();
            $description = $worksheet->getCell("C6")->getCalculatedValue();
            $value1 = $worksheet->getCell("D6")->getCalculatedValue();
            $value2 = $worksheet->getCell("E6")->getCalculatedValue();
            $value3 = $worksheet->getCell("F6")->getCalculatedValue();
            $value4 = $worksheet->getCell("G6")->getCalculatedValue();
            $value5 = $worksheet->getCell("H6")->getCalculatedValue();
            $type = $worksheet->getCell("I6")->getCalculatedValue();

            $value1 = slope::nhk_formatnumber((is_numeric($value1) ? $value1 : 0), 2);
            $value2 = slope::nhk_formatnumber((is_numeric($value2) ? $value2 : 0), 2);
            $value3 = slope::nhk_formatnumber((is_numeric($value3) ? $value3 : 0), 2);
            $value4 = slope::nhk_formatnumber((is_numeric($value4) ? $value4 : 0), 2);
            $value5 = slope::nhk_formatnumber((is_numeric($value5) ? $value5 : 0), 2);

            $scc_data= [
                'options' => 'customer',
                'rw_fy' => $fy,
                'rw_ww' => $ww,
                'rw_detail' => $description,
                'rw_value1' => $value1,
                'rw_value2' => $value2,
                'rw_value3' => $value3,
                'rw_value4' => $value4,
                'rw_value5' => $value5,
                'rw_type' => $type,
                'id_createlab' => $request->idfile,
            ];
            $ckscc2 = DB::table('nt_oqa_lab_scc')->where('id_createlab', $request->idfile)->where('options','customer')->first();
            DB::table('nt_oqa_lab_scc')->where('id_lab_scc', $ckscc2->id_lab_scc)->update($scc_data);  

            return response()->json(['success' => 1]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()]);
        }

    }

    public function createSCCOQA($id, $date)
    {

            ini_set('memory_limit', '-1');
            ini_set('max_execution_time', '600');
            set_time_limit(20000); 

            $oqafile = DB::table('nt_oqa_file')
            ->join('nt_oqa_lab', 'nt_oqa_lab.id_createlab', '=', 'nt_oqa_file.id_createlab')
            ->join('nt_model', 'nt_model.id_model', '=', 'nt_oqa_lab.id_model')
            ->where('nt_oqa_file.id_createlab', $id)
            ->first();

            $filePath = storage_path('app/public/OQA/LPC/' . $oqafile->file_lpc);
            if (empty($oqafile->file_lpc) || !file_exists($filePath)) {
                return response()->json(['success' => 0, 'message' => 'ไม่พบไฟล์ Excel ที่ต้องการประมวลผล'], 404);
            }

            Excel::load($filePath, function($doc)use($id,$date) {
                $sheet = $doc->setActiveSheetIndexByName('SCC Format');

                $cell=$sheet->getCell('A6')->getCalculatedValue();
                if($cell != ""){
                    $scc_data = [
                        'rw_fy' => $sheet->getCell('A6')->getCalculatedValue(),
                        'rw_ww' => $sheet->getCell('B6')->getCalculatedValue(),
                        'rw_detail' => $sheet->getCell('C6')->getCalculatedValue(),
                        'rw_value1' => slope::nhk_formatnumber($sheet->getCell('D6')->getCalculatedValue() ?: 0, 2),
                        'rw_value2' => slope::nhk_formatnumber($sheet->getCell('E6')->getCalculatedValue() ?: 0, 2),
                        'rw_value3' => slope::nhk_formatnumber($sheet->getCell('F6')->getCalculatedValue() ?: 0, 2),
                        'rw_value4' => slope::nhk_formatnumber($sheet->getCell('G6')->getCalculatedValue() ?: 0, 2),
                        'rw_value5' => slope::nhk_formatnumber($sheet->getCell('H6')->getCalculatedValue() ?: 0, 2),
                        'rw_type' => $sheet->getCell('I6')->getCalculatedValue(),
                        'date' => $date,
                    ];
                    // สำหรับ 'raw': ถ้ามีจะอัปเดต ถ้าไม่มีจะสร้างใหม่
                    DB::table('nt_oqa_lab_scc')->updateOrInsert(
                        ['id_createlab' => $id, 'options' => 'raw'],
                        $scc_data
                    );
                    // สำหรับ 'customer': ถ้ายังไม่มีข้อมูลจะสร้างใหม่เท่านั้น
                    DB::table('nt_oqa_lab_scc')->updateOrInsert(
                        ['id_createlab' => $id, 'options' => 'customer'],
                        $scc_data
                    );
                }
            });

    }   

    // -------------- BEGIN RAW --------------

    public function raw()
    { 
        return view('scc/raw');
    }

    public function datatableWD(Request $request)	
    { 
        if(empty($request->date_start) && empty($request->date_end))
        {
            $datalist = DB::table('nt_model')->where('status_model', 1)->where('nt_model.model_type','LIKE','WD_%')->get();
            $sQuery	= Datatables::of($datalist)
            ->addIndexColumn()
            ->addColumn('lpccount',function($data){            
                $lpccount = null;
                return $lpccount;
             })
             ->addColumn('semcount',function($data){            
                $semcount = null;
                return $semcount;
             })
             ->addColumn('iccount',function($data){            
                $iccount = null;
                return $iccount;
             })
             ->addColumn('gcmscount',function($data){            
                $gcmscount = null;
                return $gcmscount;
             })
             ->addColumn('ftircount',function($data){            
                $ftircount = null;
                return $ftircount;
             })
            ->addColumn('countdata',function($data){      
                 $chk =1;
                 return $chk;
              })
              ->addColumn('lastdate',function($data){            
                 $value = '';
                 return $value;
              })
              ->addColumn('firstdate',function($data){            
                $value = '';
                return $value;
             });
        }
        else
        {
           $datestart = date('Y-m-d H:i:s', strtotime($request->date_start));
           $dateend = date('Y-m-d 00:00:00', strtotime($request->date_end."+1 day")); 
           $start = "'$datestart'";
           $end = "'$dateend'";
           $number = "'$request->number'";
           $datalist = DB::table('nt_model')
           ->where('status_model', 1)
           ->where('nt_model.model_type','LIKE','WD_%')
           ->select('nt_model.*',DB::raw(''.$start .' as startdate'),DB::raw(''.$end .' as enddate'),DB::raw(''.$number .' as number'))->get();
           $sQuery	= Datatables::of($datalist)
                ->addIndexColumn()
                ->addColumn('lpccount',function($data){
                    
                    if($data->number == 0)
                    {           
                        $firstdate = $data->startdate;
                        $lastdate = $data->enddate;
                    }
                    else
                    {          
                    $datachkf = DB::table('nt_model')
                    ->join('nt_oqa_lab','nt_oqa_lab.id_model','=','nt_model.id_model')
                    ->where('nt_oqa_lab.save_date','>=',$data->startdate)
                    ->where('nt_oqa_lab.save_date','<=',$data->enddate)
                    ->where('nt_model.id_model','=',$data->id_model)
                    ->where('nt_model.model_type','LIKE','WD_%')
                    ->where('nt_oqa_lab.lpc_confirm_st',1)
                    ->select('nt_oqa_lab.save_date as save_datest')
                    ->orderBy('nt_oqa_lab.save_date', 'DESC')->first();
                    if($datachkf == null)
                    {
                        $firstdate = '';
                    }
                    else
                    {
                        $firstdate = $dateend = date('Y-m-d 00:00:00', strtotime($datachkf->save_datest. ' -'.$data->number.' day')); 
                    }

                    $datachkl = DB::table('nt_model')
                    ->join('nt_oqa_lab','nt_oqa_lab.id_model','=','nt_model.id_model')
                    ->where('nt_oqa_lab.save_date','>=',$data->startdate)
                    ->where('nt_oqa_lab.save_date','<=',$data->enddate)
                    ->where('nt_model.id_model','=',$data->id_model)
                    ->where('nt_model.model_type','LIKE','WD_%')
                    ->where('nt_oqa_lab.lpc_confirm_st',1)
                    ->select('nt_oqa_lab.save_date as save_datest')
                    ->orderBy('nt_oqa_lab.save_date', 'DESC')->first();
                    if($datachkl == null)
                        {
                            $lastdate = '';
                        }
                        else
                        {
                            $lastdate = $datachkl->save_datest;
                        }
                    }
                    $lpccount = DB::table('nt_datacf_lpc')
                    ->join('nt_oqa_lab','nt_oqa_lab.id_createlab','=','nt_datacf_lpc.id_createlab')
                    ->where('nt_oqa_lab.save_date','>=',$firstdate)
                    ->where('nt_oqa_lab.save_date','<=',$lastdate)
                    ->where('nt_oqa_lab.lpc_confirm_st',1)
                    ->where('nt_oqa_lab.id_model',$data->id_model)->count();
                    
                    return $lpccount;

                 })
                 ->addColumn('countdata',function($data){            
                    $chk =2;
                    return $chk;
                  })
                 ->addColumn('lastdate',function($data){ 
                    if($data->number == 0)
                    {           
                        $value = date('Y-m-d 00:00:00', strtotime($data->enddate."-1 day"));
                    }
                    else
                    {
                        $datachk = DB::table('nt_model')
                        ->join('nt_oqa_lab','nt_oqa_lab.id_model','=','nt_model.id_model')
                        ->where('nt_oqa_lab.save_date','>=',$data->startdate)
                        ->where('nt_oqa_lab.save_date','<=',$data->enddate)
                        ->where('nt_model.id_model','=',$data->id_model)
                        ->where('nt_oqa_lab.lpc_confirm_st',1)
                        ->where('nt_model.model_type','LIKE','WD_%')
                        ->select('nt_oqa_lab.save_date as save_datest')
                        ->orderBy('nt_oqa_lab.save_date', 'DESC')->first();
                         if($datachk == null)
                         {
                             $value = '';
                         }
                         else
                         {
                             $value = $datachk->save_datest;
                         }
                    }
                        return $value;
                    })
                    ->addColumn('firstdate',function($data){  
                        if($data->number == 0)
                        {           
                            $value = $data->startdate;
                            
                        }
                        else
                        {          
                            $datachk = DB::table('nt_model')
                            ->join('nt_oqa_lab','nt_oqa_lab.id_model','=','nt_model.id_model')
                            ->where('nt_oqa_lab.save_date','>=',$data->startdate)
                            ->where('nt_oqa_lab.save_date','<=',$data->enddate)
                            ->where('nt_model.id_model','=',$data->id_model)
                            ->where('nt_oqa_lab.lpc_confirm_st',1)
                            ->where('nt_model.model_type','LIKE','WD_%')
                            ->select('nt_oqa_lab.save_date as save_datest')
                            ->orderBy('nt_oqa_lab.save_date', 'DESC')->first();
                            if($datachk == null)
                            {
                                $value = '';
                            }
                            else
                            {
                                $value = $dateend = date('Y-m-d 00:00:00', strtotime($datachk->save_datest. ' -'.$data->number.' day')); 
                            }
                        }
                        return $value;
                    });

            } 
            return $sQuery->escapeColumns([])->make(true);
    }

    public function report_scc_raw(Request $request)
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '600');
        set_time_limit(20000);
        
        try {
            if (!isset($request->id) || count($request->id) !== 2) {
                return response()->json(['success' => isset($request->id) ? 2 : 3]);
            }

            $models = DB::table('nt_model')->whereIn('id_model', $request->id)->get()->keyBy('id_model');

            if ($models->count() !== 2) {
                return response()->json(['success' => 2]); 
            }

            $a1 = $models[$request->id[0]];
            $a2 = $models[$request->id[1]];

            if (!$a1 || !$a2 || mb_substr($a1->name_model, 0, -3) !== mb_substr($a2->name_model, 0, -3)) {
                return response()->json(['success' => 2]);
            }

            $model1 = ($a1->part == "UP") ? $a1 : $a2; // UP model
            $model2 = ($a2->part == "DN") ? $a2 : $a1; // DN model

            $dateend1 = \Carbon\Carbon::parse($request->input('dateend' . $model1->id_model))->endOfDay();
            $dateend2 = \Carbon\Carbon::parse($request->input('dateend' . $model2->id_model))->endOfDay();

            $newNameModel = substr($a1->name_model, 0, -3);
            $newname = "Raw_report_of_".$newNameModel."_SCC_For_Suspension";
            $templateFile = storage_path('app/public/SCC_template/' . $a1->scc_template_file);

            Excel::load($templateFile, function ($doc) use ($request, $model1, $dateend1, $model2, $dateend2) {
                
                $sheet = $doc->setActiveSheetIndexByName('US LPC X Bar');
                $row_number = 21; 

                DB::table('nt_oqa_lab')
                    ->join('nt_oqa_lab_scc', 'nt_oqa_lab.id_createlab', '=', 'nt_oqa_lab_scc.id_createlab')
                    ->where('nt_oqa_lab.lpc_confirm_st', 1)
                    ->where('nt_oqa_lab_scc.options', 'raw')
                    ->whereNotNull('nt_oqa_lab_scc.date')
                    ->where(function ($query) use ($request, $model1, $dateend1, $model2, $dateend2) {
                        // เงื่อนไขสำหรับ Model 1 (UP)
                        $query->where(function ($q) use ($request, $model1, $dateend1) {
                            $q->where('nt_oqa_lab.id_model', $model1->id_model)
                            ->whereBetween('nt_oqa_lab.save_date', [$request->input('datestart' . $model1->id_model), $dateend1]);
                        })
                        // เงื่อนไขสำหรับ Model 2 (DN)
                        ->orWhere(function ($q) use ($request, $model2, $dateend2) {
                            $q->where('nt_oqa_lab.id_model', $model2->id_model)
                            ->whereBetween('nt_oqa_lab.save_date', [$request->input('datestart' . $model2->id_model), $dateend2]);
                        });
                    })
                    ->select(
                        'nt_oqa_lab_scc.date',
                        'nt_oqa_lab_scc.rw_fy', 'nt_oqa_lab_scc.rw_ww', 'nt_oqa_lab_scc.rw_detail',
                        'nt_oqa_lab_scc.rw_value1', 'nt_oqa_lab_scc.rw_value2', 'nt_oqa_lab_scc.rw_value3',
                        'nt_oqa_lab_scc.rw_value4', 'nt_oqa_lab_scc.rw_value5', 'nt_oqa_lab_scc.rw_type',
                        'nt_oqa_lab.partnumber_wd_scc', 'nt_oqa_lab.lotnumber_lab'
                    )
                    ->orderBy('nt_oqa_lab_scc.rw_ww', 'asc')
                    ->chunk(1000, function ($records) use ($sheet, &$row_number) {
                        
                        foreach ($records as $record) {
                            $recordArray = (array) $record;

                            $dateValue = $recordArray['date'];
                            $pnValue = $recordArray['partnumber_wd_scc'];
                            $lotnumber = $recordArray['lotnumber_lab'];
                            unset($recordArray['date']);
                            unset($recordArray['lotnumber_lab']);

                            $sheet->fromArray($recordArray, null, 'C' . $row_number, false, false);
                            if ($dateValue) {
                                $sheet->setCellValue('XFC' . $row_number, \Carbon\Carbon::parse($dateValue)->format('Y-m-d'));
                            } else {
                                $sheet->setCellValue('XFC' . $row_number, null);
                            }

                            if ($pnValue) {
                                $sheet->setCellValue('XFD' . $row_number, $pnValue);
                            } else {
                                $sheet->setCellValue('XFD' . $row_number, null);
                            }

                            $resultlotnumber = explode('_', $lotnumber)[0];
                            $sheet->setCellValue('E' . $row_number, $resultlotnumber);


                            $row_number++; 
                        }

                    }); // สิ้นสุด chunk()

            })->setFilename($newname)->store('xlsx', storage_path('NHK-File/report/scc/'));

            return response()->json(['success' => 1, 'filename' => $newname . '.xlsx']);

        } catch (\Exception $e) {
            return response()->json(['error' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()], 500);
        }
    }
    
    // -------------- END RAW --------------

    // -------------- BEGIN CUSTOMER --------------

    public function customer()
    { 
        return view('scc/customer');
    }

    public function get_scc_file_template(Request $request)
    {
        
        $ntmodel = DB::table('nt_model')->select('id_model','scc_template_file')->where('id_model', $request->id)->first();
        $file = storage_path('app/public/SCC_template/' . $ntmodel->scc_template_file);
        
        $file = $ntmodel->scc_template_file;
        
        return response()->json(['success' => 1, 'file' => $file]);

    }

    public function report_scc_customer(Request $request)
    {

        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '600');
        set_time_limit(20000); 

        try {

            if (!isset($request->id) || count($request->id) !== 2) {
                return response()->json(['success' => isset($request->id) ? 2 : 3]);
            }

            $a1 = DB::table('nt_model')->where('id_model', $request->id[0])->first();
            $a2 = DB::table('nt_model')->where('id_model', $request->id[1])->first();

            if (!$a1 || !$a2 || mb_substr($a1->name_model, 0, -3) !== mb_substr($a2->name_model, 0, -3)) {
                return response()->json(['success' => 2]);
            }

            $model1 = ($a1->part == "UP") ? $a1 : $a2; // UP model
            $model2 = ($a2->part == "DN") ? $a2 : $a1; // DN model

            $dateend1 = \Carbon\Carbon::parse($request->input('dateend' . $model1->id_model))->endOfDay();
            $dateend2 = \Carbon\Carbon::parse($request->input('dateend' . $model2->id_model))->endOfDay();

            $newNameModel = substr($a1->name_model, 0, -3);
            $newname = "Customer_report_of_".$newNameModel."_SCC_For_Suspension";
            $templateFile = storage_path('app/public/SCC_template/' . $a1->scc_template_file);

            // --- เริ่มการปรับปรุงโค้ด ---

            Excel::load($templateFile, function ($doc) use ($request, $model1, $dateend1, $model2, $dateend2) {
                
                $sheet = $doc->setActiveSheetIndexByName('US LPC X Bar');
                $row_number = 21; // แถวเริ่มต้น

                // 1. สร้าง Query แบบพื้นฐานที่ใช้ร่วมกัน
                $baseQuery = function () {
                    return DB::table('nt_oqa_lab')
                        ->join('nt_oqa_lab_scc', 'nt_oqa_lab.id_createlab', '=', 'nt_oqa_lab_scc.id_createlab')
                        ->where('nt_oqa_lab.lpc_confirm_st', 1)
                        ->where('nt_oqa_lab_scc.options', 'customer');
                };

                // 2. สร้าง Select list (ต้องเพิ่ม save_date เพื่อใช้จัดเรียงหลัง UNION)
                $selectColumns = [
                    'nt_oqa_lab_scc.date',
                    'nt_oqa_lab_scc.rw_fy', 'nt_oqa_lab_scc.rw_ww', 'nt_oqa_lab_scc.rw_detail',
                    'nt_oqa_lab_scc.rw_value1', 'nt_oqa_lab_scc.rw_value2', 'nt_oqa_lab_scc.rw_value3',
                    'nt_oqa_lab_scc.rw_value4', 'nt_oqa_lab_scc.rw_value5', 'nt_oqa_lab_scc.rw_type',
                    'nt_oqa_lab.save_date', 'nt_oqa_lab.partnumber_wd_scc', 'nt_oqa_lab.lotnumber_lab'
                ];

                // 3. สร้าง Query_1 (UP)
                $query1 = $baseQuery()
                    ->where('nt_oqa_lab.id_model', $model1->id_model)
                    ->whereBetween('nt_oqa_lab.save_date', [$request->input('datestart' . $model1->id_model), $dateend1])
                    ->select($selectColumns);

                // 4. สร้าง Query_2 (DN)
                $query2 = $baseQuery()
                    ->where('nt_oqa_lab.id_model', $model2->id_model)
                    ->whereBetween('nt_oqa_lab.save_date', [$request->input('datestart' . $model2->id_model), $dateend2])
                    ->select($selectColumns);

                // 5. รวม Query ด้วย UNION ALL และจัดเรียง
                $finalQuery = $query1->unionAll($query2)->orderBy('save_date', 'asc');

                // 6. ใช้ chunk() เพื่อทยอยดึงข้อมูลและเขียนลง Excel
                $finalQuery->chunk(1000, function ($records) use ($sheet, &$row_number) {
                    
                    foreach ($records as $record) {
                        $recordArray = (array) $record;

                        $dateValue = $recordArray['date'];
                        $pnValue = $recordArray['partnumber_wd_scc'];
                        $lotnumber = $recordArray['lotnumber_lab'];
                        unset($recordArray['date']);
                        unset($recordArray['save_date']);
                        unset($recordArray['partnumber_wd_scc']);
                        unset($recordArray['lotnumber_lab']);

                        // เขียนข้อมูลลง sheet
                        $sheet->fromArray($recordArray, null, 'C'.$row_number, false, false);
                        $sheet->setCellValue('XFC'.$row_number, \Carbon\Carbon::parse($dateValue)->format('Y-m-d'));

                        if ($pnValue) {
                            $sheet->setCellValue('XFD' . $row_number, $pnValue);
                        } else {
                            $sheet->setCellValue('XFD' . $row_number, null);
                        }

                        $resultlotnumber = explode('_', $lotnumber)[0];
                        $sheet->setCellValue('E' . $row_number, $resultlotnumber);
                        
                        $row_number++; // เลื่อนไปยังแถวถัดไป
                    }

                }); // จบการทำงานของ chunk

            })->setFilename($newname)->store('xlsx', storage_path('NHK-File/report/scc/'));

            // --- จบการปรับปรุงโค้ด ---

            return response()->json(['success' => 1, 'filename' => $newname . '.xlsx']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()], 500);
        }
    }
    
    
    // -------------- END CUSTOMER --------------

    
}
