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
use Carbon\Carbon;
use slope;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;


class QueueController extends Controller
{

    function sendCreate($id_queue)
    {
        $queuing = DB::table('nt_queuing')->where('id',$id_queue)->first();
        $tester = DB::table('nt_queuing_tester')->where('id_queue',$id_queue)->get();
        $accept = DB::table('nt_queuing_accept')->where('id_queue',$id_queue)->first();

        $array_tester = [];
        foreach ($tester as $key => $value) {
            $array_tester[] = $value->tester;
        }

        $subject = "Queuing Information (New request) ";
        $body =  "<html><head><body>
                Attention all,<br><br>
                <b style='font-size: xx-large; font-family: fantasy; color: #20b820;'>[New request]</b>
                <br><br>
                <b>".$subject."</b>
                <br>
                <table border=1> 
                    <tr>
                        <th>Item</th>
                        <th>Detail</th>
                    </tr>
                    <tr>
                        <td>Job no</td>
                        <td>" . $queuing->jobno . "</td>
                    </tr>
                    <tr>
                        <td>Job Name</td>
                        <td>" . $queuing->title . "</td>
                    </tr>
                    <tr>
                        <td>Model</td>
                        <td>" . $queuing->model . "</td>
                    </tr>
                    <tr>
                        <td>Machine</td>
                        <td>" . implode(",", $array_tester) . "</td>
                    </tr>
                    <tr>
                        <td>Request Date</td>
                        <td>" . date('d-M-Y', strtotime($queuing->requestdate)). "</td>
                    </tr>
                    <tr>
                        <td>Due date</td>
                        <td>" . date('d-M-Y', strtotime($queuing->duedate)) . "</td>
                    </tr>
                </table>
                <p>If you have any questions or concerns, please feel free to contact our Lab Engineer.</p>
                <p style='color: red;'>*** This is an automatically generated email, please do not reply ***</p>
                <br>
                <br> 
                Best Regards,<br>
                Cleanliness lab<br>
                QA Section<br>
                Disk Drive Suspension Division<br>
                NHK SPRING (THAILAND) CO.,LTD.
                </body>
                </html>
                <br><br><br> "; 

        $info  = [
            'body' => $body,
            'subject' => $subject,
        ];

        if(!empty($queuing->email_requester)){
            if (!empty($queuing->email_requester)) {
                $email = trim($queuing->email_requester);
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    Mail::html($body, function ($message) use ($subject, $email) {
                        $message->to(['piyachat.mab@nhkspg.co.th','sarocha.man@nhkspg.co.th','narupon@nhkspg.co.th','numfon.sin@nhkspg.co.th'])
                        ->subject($subject);
                    });
                } else {
                    \Log::warning("Invalid email for queue ID {$id_queue}: {$email}");
                }
            } else {
                \Log::warning("Missing email for queue ID {$id_queue}");
            }
        }
    }

    function sendRequest($id_queue)
    {
        $queuing = DB::table('nt_queuing')->where('id',$id_queue)->first();
        $tester = DB::table('nt_queuing_tester')->where('id_queue',$id_queue)->get();
        $accept = DB::table('nt_queuing_accept')->where('id_queue',$id_queue)->first();

        $array_tester = [];
        foreach ($tester as $key => $value) {
            $array_tester[] = $value->tester;
        }

        $subject = "Queuing Information (Acceptance) ";
        $body =  "<html><head><body>
                Attention all,<br><br>
                <b style='font-size: xx-large; font-family: fantasy; color: #20b820;'>[ACCEPTANCE]</b>
                <br><br>
                <b>".$subject."</b>
                <br>
                <table border=1> 
                    <tr>
                        <th>Item</th>
                        <th>Detail</th>
                    </tr>
                    <tr>
                        <td>Job no</td>
                        <td>" . $queuing->jobno . "</td>
                    </tr>
                    <tr>
                        <td>Job Name</td>
                        <td>" . $queuing->title . "</td>
                    </tr>
                    <tr>
                        <td>Model</td>
                        <td>" . $queuing->model . "</td>
                    </tr>
                    <tr>
                        <td>Machine</td>
                        <td>" . implode(",", $array_tester) . "</td>
                    </tr>
                    <tr>
                        <td>Request Date</td>
                        <td>" . date('d-M-Y', strtotime($queuing->requestdate)). "</td>
                    </tr>
                    <tr>
                        <td>Estimate date</td>
                        <td>" . date('d-M-Y', strtotime($accept->accept_summarytime)) . "</td>
                    </tr>
                    <tr>
                        <td>Requester</td>
                        <td>" . $queuing->requester . "</td>
                    </tr>
                    
                </table>
                <p>If you have any questions or concerns, please feel free to contact our Lab Engineer.</p>
                <p style='color: red;'>*** This is an automatically generated email, please do not reply ***</p>
                <br>
                <br>
                Best Regards,<br>
                Cleanliness lab<br>
                QA Section<br>
                Disk Drive Suspension Division<br>
                NHK SPRING (THAILAND) CO.,LTD.
                </body>
                </html>
                <br><br><br> "; 

        $info  = [
            'body' => $body,
            'subject' => $subject,
        ];

        if(!empty($queuing->email_requester)){
            if (!empty($queuing->email_requester)) {
                $email = trim($queuing->email_requester);
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    Mail::html($body, function ($message) use ($subject, $email) {
                        $message->to([$email])
                        ->cc(['piyachat.mab@nhkspg.co.th','sarocha.man@nhkspg.co.th','narupon@nhkspg.co.th','numfon.sin@nhkspg.co.th','cleanliness.lab@nhkspg.co.th','weerawan.iea@nhkspg.co.th','surasak.hoe@nhkspg.co.th'])
                        ->subject($subject);
                    });
                } else {
                    \Log::warning("Invalid email for queue ID {$id_queue}: {$email}");
                }
            } else {
                \Log::warning("Missing email for queue ID {$id_queue}");
            }
        }
    }
 
    function sendSummary($id_tester)
    {

        $tester = DB::table('nt_queuing_tester')->where('id_tester',$id_tester)->first();
        $queuing = DB::table('nt_queuing')->where('id',$tester->id_queue)->first();

        $subject = "Queuing Information ( Summary )_". $tester->tester;
        $body =  "<html><head><body> 
                Attention all,<br><br>
                <b style='font-size: xx-large; font-family: fantasy; color: #F97A00;'>[COMPLETE]</b>
                <br><br>
                <table border=1> 
                    <tr>
                        <th>Item</th>
                        <th>Detail</th>
                    </tr>
                    <tr>
                        <td>Job no</td>
                        <td>" . $queuing->jobno . "</td>
                    </tr>
                    <tr>
                        <td>Job Name</td>
                        <td>" . $queuing->title . "</td>
                    </tr>
                    <tr>
                        <td>Model</td>
                        <td>" . $queuing->model . "</td>
                    </tr>
                    <tr>
                        <td>Machine</td>
                        <td>" . $tester->tester . "</td>
                    </tr>
                    <tr>
                        <td>Requester</td>
                        <td>" . $queuing->requester . "</td>
                    </tr>
                    
                </table>
                <p>If you have any questions or concerns, please feel free to contact our Lab Engineer.</p>
                <p style='color: red;'>*** This is an automatically generated email, please do not reply ***</p>
                <br>
                <br> 
                Best Regards,<br>
                Cleanliness lab<br>
                QA Section<br>
                Disk Drive Suspension Division<br>
                NHK SPRING (THAILAND) CO.,LTD.
                </body>
                </html>
                <br><br><br> "; 

        $info  = [
            'body' => $body,
            'subject' => $subject,
        ];

        if(!empty($queuing->email_requester)){
            if (!empty($queuing->email_requester)) {
                $email = trim($queuing->email_requester);
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    Mail::html($body, function ($message) use ($subject, $email) {
                        $message->to($email)
                        ->cc(['piyachat.mab@nhkspg.co.th','sarocha.man@nhkspg.co.th','narupon@nhkspg.co.th','numfon.sin@nhkspg.co.th','cleanliness.lab@nhkspg.co.th','weerawan.iea@nhkspg.co.th','surasak.hoe@nhkspg.co.th'])
                        ->subject($subject);
                    });
                } else {
                    \Log::warning("Invalid email for queue ID {$id_queue}: {$email}");
                }
            } else {
                \Log::warning("Missing email for queue ID {$id_queue}");
            }
        }

    }

    function sendCancel($data)
    {

        $subject = "Queuing Information (Reject)";
        $body =  "<html><head><body> 
                Attention all,<br><br>
                <b style='font-size: xx-large; font-family: fantasy; color: #f90000ff;'>[Reject]</b>
                <br>
                <p>Queuing Information (Reject)</p>
                <br>
                <table border=1> 
                    <tr>
                        <th>Item</th>
                        <th>Detail</th>
                    </tr>
                    <tr>
                        <td>Job no</td>
                        <td>" . $data['jobno']. "</td>
                    </tr>
                    <tr>
                        <td>Job Name</td>
                        <td>" . $data['title'] . "</td>
                    </tr>
                    <tr>
                        <td>Model</td>
                        <td>" . $data['model'] . "</td>
                    </tr>
                    <tr>
                        <td>Machine</td>
                        <td>" . $data['tester'] . "</td>
                    </tr>
                    <tr>
                        <td>Requester</td>
                        <td>" . $data['requester'] . "</td>
                    </tr>
                    <tr>
                        <td>Due date</td>
                        <td>" . date('d-M-Y', strtotime($data['duedate'])) . "</td>
                    </tr>
                    
                </table>
                <p>If you have any questions or concerns, please feel free to contact our Lab Engineer.</p>
                <p style='color: red;'>*** This is an automatically generated email, please do not reply ***</p>
                <br>
                <br> 
                Best Regards,<br>
                Cleanliness lab<br>
                QA Section<br>
                Disk Drive Suspension Division<br>
                NHK SPRING (THAILAND) CO.,LTD.
                </body>
                </html>
                <br><br><br> "; 

        $info  = [
            'body' => $body,
            'subject' => $subject,
        ];

        
        $queuing = DB::table('nt_queuing')->where('jobno',$data['jobno'])->first();

        if(!empty($queuing->email_requester)){
            if (!empty($queuing->email_requester)) {
                $email = trim($queuing->email_requester);
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    Mail::html($body, function ($message) use ($subject, $email) {
                        $message->to($email)
                        ->cc(['piyachat.mab@nhkspg.co.th','sarocha.man@nhkspg.co.th','narupon@nhkspg.co.th','numfon.sin@nhkspg.co.th','cleanliness.lab@nhkspg.co.th','weerawan.iea@nhkspg.co.th','surasak.hoe@nhkspg.co.th'])
                        ->subject($subject);
                    });
                } else {
                    // \Log::warning("Invalid email for queue ID {$id_queue}: {$email}");
                }
            } else {
                // \Log::warning("Missing email for queue ID {$id_queue}");
            }
        }

    }

    public function index()
    {    
        $datalist = []; 
        $today = Carbon::today()->toDateString();
        $tomorrow = Carbon::tomorrow()->toDateString();

        $sql_1 = DB::table('nt_queuing')
        ->join('nt_queuing_accept', 'nt_queuing_accept.id_queue', '=', 'nt_queuing.id')
        ->join('nt_queuing_tester', 'nt_queuing_tester.id_queue', '=', 'nt_queuing.id')
        ->where('nt_queuing.queue', '!=', 0)
        ->where('nt_queuing.status', 1)
        ->whereBetween('nt_queuing_accept.accept_measurementtime', [$today, $tomorrow])
        ->orderBy('nt_queuing_accept.accept_measurementtime', 'ASC')
        ->get();

        $sql_2 = DB::table('nt_queuing')
        ->join('nt_queuing_accept', 'nt_queuing_accept.id_queue', '=', 'nt_queuing.id')
        ->join('nt_queuing_tester', 'nt_queuing_tester.id_queue', '=', 'nt_queuing.id')
        ->where('nt_queuing.queue', '!=', 0)
        ->where('nt_queuing.status', 1)
        ->whereNotNull('nt_queuing_accept.accept_summarytime')
        ->whereBetween('nt_queuing_accept.accept_summarytime', [$today, $tomorrow])
        ->orderBy('nt_queuing_accept.accept_summarytime', 'ASC')
        ->get();


        $datalist = [];

        // จัดการข้อมูล measurementtime
        foreach ($sql_1 as $value) {
            $key = $value->jobno . '_' . $value->tester;

            $datalist[$key] = [
                'no' => $value->id_tester,
                'machine' => $value->tester,
                'jobno' => $value->jobno,
                'model' => $value->model,
                'title' => $value->title,
                'measurementtime' => $value->accept_measurementtime,
                'summarytime' => null,
                'requester' => $value->requester,
                'st' => 1,
                'red' => 1,
                'blue' => 0,
            ];
        }

        // จัดการข้อมูล summarytime
        foreach ($sql_2 as $value) {
            $key = $value->jobno . '_' . $value->tester;

            if (isset($datalist[$key])) {
                // ถ้ามี key นี้แล้ว (จาก measurementtime) ให้ update
                $datalist[$key]['summarytime'] = $value->accept_summarytime;
                $datalist[$key]['st'] = 3; // มีทั้ง measurement และ summary
                $datalist[$key]['red'] = 1;
                $datalist[$key]['blue'] = 1;
            } else {
                // ยังไม่มี key นี้ ให้เพิ่มใหม่
                $datalist[$key] = [
                    'no' => $value->id_tester,
                    'machine' => $value->tester,
                    'jobno' => $value->jobno,
                    'model' => $value->model,
                    'title' => $value->title,
                    'measurementtime' => null,
                    'summarytime' => $value->accept_summarytime,
                    'requester' => $value->requester,
                    'st' => 2,
                    'red' => 0,
                    'blue' => 1,
                ];
            }
        }

        $datalist = array_values($datalist);

        // 🔽 เรียงลำดับ $datalist ตาม measurementtime (เก่า → ใหม่)
        usort($datalist, function ($a, $b) {
            return strtotime($a['measurementtime']) <=> strtotime($b['measurementtime']);
        });

        $data = [
            'queuelist' => $datalist
        ];

        if(Auth::user()->status == 1){
            return view('queue/index', $data); 
        }elseif (Auth::user()->status == 4 || Auth::user()->status == 3) {
            return view('queue/requester', $data); 
        }elseif (Auth::user()->status == 5 || Auth::user()->status == 2) {
            return view('queue/operator', $data); 
        }else{
            return redirect()->route('home');
        }

    }

    public function add()
    { 

        $option_sem = DB::table('nt_testeroption')->where('type', 1)->get();
        $option_ftir = DB::table('nt_testeroption')->where('type', 2)->get();

        $data = [
            'option_sem' => $option_sem,
            'option_ftir' => $option_ftir
        ];
        return view('queue/add', $data); 
    }

    public function createQueue(Request $request)
    {

        $date_validator = [];
        $err_validator = [];

        foreach ($request->all() as  $key => $item) {
            if ( $key != "parttype" && $key != "returnsemple_text" && $key != "model" && $key != "customer" && $key != "ftir_option_1" && $key != "ftir_option_2" && $key != "sem_option_1" && $key != "sem_option_2" && $key != "sem_option_3" && $key != "gcms_option" && $key != "gcms_aml_type" && $key != "gcms_smple_type_desc" && $key != "return_recipient" && $key != "return_recipient_number" ) {   
                $date_validator[$key] = 'required';
                $date_validator[$key . ".*"] = 'required';

                $err_validator[$key . ".*.required"] = 'กรุณากรอกข้อมูล';
                $err_validator[$key . '.' . 'required'] = 'กรุณากรอกข้อมูล';
            }
        }

        $validator = Validator::make(
            $request->all(),
            $date_validator,
            $err_validator
        );

        if (!$validator->fails()) { 

            try {

                // Create Job no
                $lastJob = DB::table('nt_queuing')->orderBy('id', 'desc')->first();
                if ($lastJob && isset($lastJob->jobno)) {
                    // แยก prefix และเลข
                    $lastNumber = (int) str_replace('S', '', $lastJob->jobno);
                    $newNumber = $lastNumber + 1;
                } else {
                    $newNumber = 1;
                }

                // สร้าง Job ID ใหม่ เช่น S00000001
                $newJobId = 'S' . str_pad($newNumber, 7, '0', STR_PAD_LEFT);

                $chk = DB::table('nt_queuing')->where('jobno', $newJobId)->get();
                if(count($chk) == 0){

                    if($request->file('pictures') !== null){
                        $file = $request->file('pictures');
                        $extension = $file->getClientOriginalExtension();

                        $destinationPath = storage_path('images/Queue');
                        $fileName = 'picture_'. time() .''. uniqid() . '.' . $extension;
                        // ย้ายไฟล์ไปยังโฟลเดอร์ที่กำหนด
                        $file->move($destinationPath, $fileName);
                        $filePath = $fileName;
                    }

                    $id = DB::table('nt_queuing')->insertGetId([
                        'title' => $request->title,
                        'jobno' => $newJobId,
                        'queue' => 0,
                        'model' => $request->model,
                        'customer' => $request->customer,
                        'requestdate' => $request->requestdate,
                        'sample_send_date' => $request->sample_send_date,
                        'duedate' => $request->duedate,
                        'detail' => $request->detail,
                        'parttype' => $request->parttype,
                        'qty' => $request->qty,
                        'returnsemple_text' => $request->returnsemple_text,
                        'returnsemple_st' => $request->returnsemple_st,
                        'pictures' => @$filePath, 
                        'requester' => $request->requester,
                        'email_requester' => $request->email_requester,
                        'return_recipient' => $request->return_recipient,
                        'return_recipient_number' => $request->return_recipient_number,
                        'status' => 0,
                        'created_at' => date('Y-m-d H:i:s'),
                    ]);


                    if($id){

                        if(!empty($request->tester)){
                            foreach ($request->tester as $key => $tester) {

                                if($tester == "SEM/EDX Tester"){
                                    $arrtester = [
                                        'id_queue' => $id,
                                        'tester' => $tester,
                                        'sem_option_1' => $request->sem_option_1,
                                        'sem_option_2' => $request->sem_option_2,
                                        'sem_option_3' => $request->sem_option_3,
                                        'created_at' => date('Y-m-d H:i:s'),
                                    ];
                                    DB::table('nt_queuing_tester')->insert($arrtester);
                                }elseif ($tester == "FT-IR tester") {
                                    $arrtester = [
                                        'id_queue' => $id,
                                        'tester' => $tester,
                                        'ftir_option_1' => $request->ftir_option_1,
                                        'ftir_option_2' => $request->ftir_option_2,
                                        'created_at' => date('Y-m-d H:i:s'),
                                    ];
                                    DB::table('nt_queuing_tester')->insert($arrtester);
                                }elseif ($tester == "LPC tester") {
                                    $arrtester = [
                                        'id_queue' => $id,
                                        'tester' => $tester,
                                        'created_at' => date('Y-m-d H:i:s'),
                                    ];
                                    DB::table('nt_queuing_tester')->insert($arrtester);
                                }elseif ($tester == "GC-MS Tester") {
                                    $arrtester = [
                                        'id_queue' => $id,
                                        'tester' => $tester,
                                        'gcms_option' => $request->gcms_option,
                                        'gcms_aml_type' => $request->gcms_aml_type,
                                        'gcms_smple_type_desc' => $request->gcms_smple_type_desc,
                                        'created_at' => date('Y-m-d H:i:s'),
                                    ];
                                    DB::table('nt_queuing_tester')->insert($arrtester);
                                }elseif ($tester == "IC Tester") {
                                    $arrtester = [
                                        'id_queue' => $id,
                                        'tester' => $tester,
                                        'created_at' => date('Y-m-d H:i:s'),
                                    ];
                                    DB::table('nt_queuing_tester')->insert($arrtester);
                                }elseif ($tester == "DSC Tester") {
                                    $arrtester = [
                                        'id_queue' => $id,
                                        'tester' => $tester,
                                        'created_at' => date('Y-m-d H:i:s'),
                                    ];
                                    DB::table('nt_queuing_tester')->insert($arrtester);
                                }elseif ($tester == "Weight Tester") {
                                    $arrtester = [
                                        'id_queue' => $id,
                                        'tester' => $tester,
                                        'created_at' => date('Y-m-d H:i:s'),
                                    ];
                                    DB::table('nt_queuing_tester')->insert($arrtester);
                                }elseif ($tester == "Fallen Dust Tester") {
                                    $arrtester = [
                                        'id_queue' => $id,
                                        'tester' => $tester,
                                        'created_at' => date('Y-m-d H:i:s'),
                                    ];
                                    DB::table('nt_queuing_tester')->insert($arrtester);
                                }
                                elseif ($tester == "GC-MS Tester") {
                                    $arrtester = [
                                        'id_queue' => $id,
                                        'tester' => $tester,
                                        'gcms_option' => @$request->gcms_option,
                                        'gcms_aml_type' => @$request->gcms_aml_type,
                                        'gcms_smple_type_desc' => @$request->gcms_smple_type_desc,
                                        'created_at' => date('Y-m-d H:i:s'),
                                    ];
                                    DB::table('nt_queuing_tester')->insert($arrtester);
                                }
                                elseif ($tester == "Smoke Tester") {
                                    $arrtester = [
                                        'id_queue' => $id,
                                        'tester' => $tester,
                                        'created_at' => date('Y-m-d H:i:s'),
                                    ];
                                    DB::table('nt_queuing_tester')->insert($arrtester);
                                }
                            }
                        } 

                        @$this->sendCreate($id);
                        return response()->json(['success' => 1]);

                    }else{

                        return response()->json(['error' => "Operation failed. Please contact the staff."]);

                    }

                }else{

                    return response()->json(['error' => "Duplicate JOB NO found in the system"]);

                }

            } catch (Exception $e) {

                $errorlog = [
                    'error_log' => $e->getMessage(),
                    'error_line' => $e->getLine(),
                    'error_url' => url()->current(),
                ];

                $log_id = DB::table('log_backend')->insertGetId($errorlog);

                return response()->json(['error' => "กรุณาแจ้งรหัส Code : $log_id ให้ทางผู้พัฒนาโปรแกรม "]);


            }

        } 
 
        return response()->json(['error' => $validator->errors()]);

    }
  
    public function datatablesqueue(Request $request)
    {
        
        $max_queue = DB::table('nt_queuing')->select('queue')->orderBy('queue', 'DESC')->first();
        $max_queue = $max_queue->queue;
        
        $recordsToUpdate = DB::table('nt_queuing') 
            ->where('nt_queuing.status', 1)
            ->where('nt_queuing.sample_send_date', '<', now())
            ->orderBy('nt_queuing.id', 'asc') 
            ->get();

        if ($recordsToUpdate->count() > 0) {
            foreach ($recordsToUpdate as $record) {
                $max_queue++;
               
                $all_test = DB::table('nt_queuing_tester') 
                ->where('id_queue', $record->id)
                ->count();
                $start_test = DB::table('nt_queuing_tester') 
                ->where('id_queue', $record->id)
                ->where('measure_status','>=', 1)
                ->count(); 

                if($start_test == 0){
                    DB::table('nt_queuing')
                        ->where('id', $record->id)
                        ->update(['queue' => $max_queue]);
                }
            }
        }

        if (!empty($request->tester)) {
 
            $datalist = DB::table('nt_queuing_tester')
                ->join('nt_queuing', 'nt_queuing.id', '=', 'nt_queuing_tester.id_queue')
                ->select(
                    'nt_queuing_tester.*',
                    'nt_queuing.id',
                    'nt_queuing.queue',
                    'nt_queuing.title',
                    'nt_queuing.requester',
                    'nt_queuing.duedate',
                    'nt_queuing.jobno',
                    'nt_queuing.status',
                    'nt_queuing.sample_send_date',
                    'nt_queuing.created_at as queue_created_at'
                );

            if (!empty($request->tester)) {
                $datalist->where('nt_queuing_tester.tester', 'LIKE', $request->tester . '%');
            }
            if (!empty($request->title)) {
                $datalist->where('nt_queuing.title', 'LIKE', $request->title . '%');
            }
            if (!empty($request->requester)) {
                $datalist->where('nt_queuing.requester', 'LIKE', '%' . $request->requester . '%');
            }
            if (!empty($request->duedate)) {
                $datalist->where('nt_queuing.duedate', $request->duedate);
            }

            // $datalist->orderBy('nt_queuing.duedate', 'ASC') 
            // ->orderBy('nt_queuing_tester.measure_status', 'ASC')
            // ->orderBy('nt_queuing_tester.summary_status', 'ASC')
            // ->get();
             $datalist->orderByRaw("
                CASE
                    WHEN nt_queuing_tester.measure_status = 0 OR nt_queuing_tester.summary_status = 0 THEN 1
                    WHEN nt_queuing_tester.measure_status = 1 OR nt_queuing_tester.summary_status = 1 THEN 2
                    WHEN nt_queuing_tester.measure_status = 3 AND nt_queuing_tester.summary_status < 3 THEN 3
                    WHEN nt_queuing_tester.measure_status = 3 AND nt_queuing_tester.summary_status = 3 THEN 4
                    ELSE 4
                END
            ")
            ->orderBy('nt_queuing.duedate', 'ASC')
            ->get();

            $sQuery = Datatables::of($datalist)
                ->addIndexColumn()
                ->addColumn('queue', function ($data) {
                    return $data->queue;
                })
                ->addColumn('machine', function ($data) {
                    return $data->tester;
                })
                ->addColumn('jobno', function ($data) {
                    return $data->jobno;
                })
                ->addColumn('title', function ($data) {
                    return $data->title;
                })
                ->addColumn('duedate', function ($data) {
                    return date('d-M-y', strtotime($data->duedate));
                })
                ->addColumn('requester', function ($data) {
                    return $data->requester;
                })
                ->addColumn('status', function ($data) {
                    return $data->status;
                })
                ->addColumn('measurement', function ($data) {
                    if ($data->measure_status == 2) {
                        $st = 'running';
                    } elseif ($data->measure_status == 3) {
                        $st = 'complate';
                    } else {
                        $st = 'not start';
                    }
                    return $st;
                })
                ->addColumn('summary', function ($data) {
                    if ($data->summary_status == 2) {
                        $st = 'running';
                    } elseif ($data->summary_status == 3) {
                        $st = 'complate';
                    } else {
                        $st = 'not start';
                    }
                    return $st;
                });

            return $sQuery->escapeColumns([])->make(true);
        } else {
            $datalist = [];
            return DataTables::of(collect($datalist))
                ->escapeColumns([])
                ->make(true);
        }
    }

    public function edit($id)
    {

        $option_sem = DB::table('nt_testeroption')->where('type', 1)->get();
        $option_ftir = DB::table('nt_testeroption')->where('type', 2)->get();

        $queuing = DB::table('nt_queuing')->where('id',$id)->first();
        $tester = DB::table('nt_queuing_tester')->where('id_queue',$id)->get();

        $array_tester = [];
        foreach ($tester as $key => $value) {
            $array_tester[] = $value->tester;
        }

        $data = [
            'queuing' => $queuing,
            'tester' => $tester,
            'array_tester' => $array_tester,
            'option_sem' => $option_sem,
            'option_ftir' => $option_ftir,
        ];
        return view('queue/edit', $data); 

    }

    public function view($id)
    {

        $option_sem = DB::table('nt_testeroption')->where('type', 1)->get();
        $option_ftir = DB::table('nt_testeroption')->where('type', 2)->get();

        $queuing = DB::table('nt_queuing')->where('id',$id)->first();
        $tester = DB::table('nt_queuing_tester')->where('id_queue',$id)->get();

        $accept = DB::table('nt_queuing_accept')->where('id_queue', $queuing->id)->first();
        $sample = DB::table('nt_queuing_sample')->where('id_queue', $queuing->id)->first();


        $array_tester = [];
        foreach ($tester as $key => $value) {
            $array_tester[] = $value->tester;
        }

        $data = [
            'queuing' => $queuing,
            'tester' => $tester,
            'array_tester' => $array_tester, 

            'accept' => $accept, 
            'sample' => $sample, 

            'option_sem' => $option_sem,
            'option_ftir' => $option_ftir
        ];
        return view('queue/view', $data); 

    }

    public function updateQueue(Request $request)
    {
        $date_validator = [];
        $err_validator = [];

        foreach ($request->all() as  $key => $item) {
            if ( $key != "parttype" && $key != "returnsemple_text" && $key != "model" && $key != "customer" && $key != "ftir_option_1" && $key != "ftir_option_2" && $key != "sem_option_1" && $key != "sem_option_2" && $key != "sem_option_3" && $key != "gcms_option" && $key != "gcms_aml_type" && $key != "gcms_smple_type_desc" && $key != "return_recipient" && $key != "return_recipient_number" ) {   
                $date_validator[$key] = 'required';
                $date_validator[$key . ".*"] = 'required';

                $err_validator[$key . ".*.required"] = 'กรุณากรอกข้อมูล';
                $err_validator[$key . '.' . 'required'] = 'กรุณากรอกข้อมูล';
            }
        }

        $validator = Validator::make(
            $request->all(),
            $date_validator,
            $err_validator
        );

        if (!$validator->fails()) { 

            try {

                $queuing = DB::table('nt_queuing')->where('id', $request->id)->first();
                 
                if(!empty($queuing)){

                    if($request->file('pictures') !== null){

                        if(!empty($queuing->pictures)){
                            $OldfilePath = storage_path('images/Queue/'.$queuing->pictures);
                            if (file_exists($OldfilePath)) {
                                unlink($OldfilePath);
                            }
                        }

                        $file = $request->file('pictures');
                        $extension = $file->getClientOriginalExtension();

                        $destinationPath = storage_path('images/Queue');
                        $fileName = 'picture_'. time() .''. uniqid() . '.' . $extension;
                        // ย้ายไฟล์ไปยังโฟลเดอร์ที่กำหนด
                        $file->move($destinationPath, $fileName);
                        $filePath = $fileName;

                        DB::table('nt_queuing')->where('id', $request->id)->update(['pictures' => $filePath]);

                    }

                    $update = [
                        'title' => $request->title,
                        'model' => $request->model,
                        'customer' => $request->customer,
                        'requestdate' => $request->requestdate,
                        'sample_send_date' => $request->sample_send_date,
                        'duedate' => $request->duedate,
                        'detail' => $request->detail,
                        'parttype' => $request->parttype,
                        'qty' => $request->qty,
                        'returnsemple_text' => $request->returnsemple_text,
                        'returnsemple_st' => $request->returnsemple_st,
                        'requester' => $request->requester,
                        'email_requester' => $request->email_requester,
                        'return_recipient' => $request->return_recipient,
                        'return_recipient_number' => $request->return_recipient_number,
                    ];
                    DB::table('nt_queuing')->where('id', $request->id)->update($update);

                    if($request->id){

                        if(!empty($request->tester)){
                            foreach ($request->tester as $key => $tester) {

                                $test = DB::table('nt_queuing_tester')->where('id_queue', $request->id)->where('tester', $tester)->first();
                                if($tester == "SEM/EDX Tester"){
                                    $arrtester = [
                                        'id_queue' => $request->id,
                                        'tester' => $tester,
                                        'sem_option_1' => $request->sem_option_1,
                                        'sem_option_2' => $request->sem_option_2,
                                        'sem_option_3' => $request->sem_option_3,
                                    ];
                                    DB::table('nt_queuing_tester')->where('id_tester', $test->id_tester)->update($arrtester);
                                }elseif ($tester == "FT-IR tester") {
                                    $arrtester = [
                                        'id_queue' => $request->id,
                                        'tester' => $tester,
                                        'ftir_option_1' => $request->ftir_option_1,
                                        'ftir_option_2' => $request->ftir_option_2,
                                    ];
                                    DB::table('nt_queuing_tester')->where('id_tester', $test->id_tester)->update($arrtester);
                                }elseif ($tester == "LPC tester") {
                                    $arrtester = [
                                        'id_queue' => $request->id,
                                        'tester' => $tester,
                                    ];
                                    DB::table('nt_queuing_tester')->where('id_tester', $test->id_tester)->update($arrtester);
                                }elseif ($tester == "GC-MS Tester") {
                                    $arrtester = [
                                        'id_queue' => $request->id,
                                        'tester' => $tester,
                                        'gcms_option' => $request->gcms_option,
                                        'gcms_aml_type' => $request->gcms_aml_type,
                                        'gcms_smple_type_desc' => $request->gcms_smple_type_desc,
                                    ];
                                    DB::table('nt_queuing_tester')->where('id_tester', $test->id_tester)->update($arrtester);
                                }elseif ($tester == "IC Tester") {
                                    $arrtester = [
                                        'id_queue' => $request->id,
                                        'tester' => $tester,
                                    ];
                                    DB::table('nt_queuing_tester')->where('id_tester', $test->id_tester)->update($arrtester);
                                }elseif ($tester == "DSC Tester") {
                                    $arrtester = [
                                        'id_queue' => $request->id,
                                        'tester' => $tester,
                                    ];
                                    DB::table('nt_queuing_tester')->where('id_tester', $test->id_tester)->update($arrtester);
                                }elseif ($tester == "Weight Tester") {
                                    $arrtester = [
                                        'id_queue' => $request->id,
                                        'tester' => $tester,
                                    ];
                                    DB::table('nt_queuing_tester')->where('id_tester', $test->id_tester)->update($arrtester);
                                }elseif ($tester == "Fallen Dust Tester") {
                                    $arrtester = [
                                        'id_queue' => $request->id,
                                        'tester' => $tester,
                                    ];
                                    DB::table('nt_queuing_tester')->where('id_tester', $test->id_tester)->update($arrtester);
                                }elseif ($tester == "GC-MS Tester") {
                                    $arrtester = [
                                        'id_queue' => $request->id,
                                        'tester' => $tester,
                                        'gcms_option' => @$request->gcms_option,
                                        'gcms_aml_type' => @$request->gcms_aml_type,
                                        'gcms_smple_type_desc' => @$request->gcms_smple_type_desc,
                                        'created_at' => date('Y-m-d H:i:s'),
                                    ];
                                    DB::table('nt_queuing_tester')->where('id_tester', $test->id_tester)->update($arrtester);
                                }elseif ($tester == "Smoke Tester") {
                                    $arrtester = [
                                        'id_queue' => $request->id,
                                        'tester' => $tester,
                                    ];
                                    DB::table('nt_queuing_tester')->where('id_tester', $test->id_tester)->update($arrtester);
                                }
                            }
                        }

                        return response()->json(['success' => 1]);

                    }else{

                        return response()->json(['error' => "Operation failed. Please contact the staff."]);

                    }

                }else{

                    return response()->json(['error' => "Operation failed. Please contact the staff."]);

                }

            } catch (Exception $e) {

                $errorlog = [
                    'error_log' => $e->getMessage(),
                    'error_line' => $e->getLine(),
                    'error_url' => url()->current(),
                ];

                $log_id = DB::table('log_backend')->insertGetId($errorlog);

                return response()->json(['error' => "กรุณาแจ้งรหัส Code : $log_id ให้ทางผู้พัฒนาโปรแกรม "]);


            }

        } 

        return response()->json(['error' => $validator->errors()]);
        
    }

    public function deletequeue(Request $request)
    {

        $chk = DB::table('nt_queuing')->where('id', $request->id)->first();
        if($chk){

            $tester = DB::table('nt_queuing_tester')->where('id_queue', $request->id)->get();
            $testers = [];
            foreach ($tester as $key => $value) {
                $testers[] = ucwords($value->tester);
            }
            $data = [
                'jobno' => $chk->jobno,
                'title' => $chk->title,
                'model' => $chk->model,
                'tester' => implode(", ", $testers),
                'requester' => $chk->requester,
                'duedate' => $chk->duedate,
            ];
            $this->sendCancel($data);

            DB::table('nt_queuing')->where('id', $request->id)->delete();
            DB::table('nt_queuing_tester')->where('id_queue', $request->id)->delete();

            return response()->json(['success' => 1]);

        }
        
        return response()->json(['success' => 2]);


    }

    public function cancelqueue(Request $request)
    {
       
        $data = [
            'status' => 4
        ];

        DB::table('nt_queuing')->where('id', $request->id)->update($data); 
        
        if($request->id){

            $tester = DB::table('nt_queuing_tester')->where('id_queue', $request->id)->get();
            $testers = [];
            foreach ($tester as $key => $value) {
                $testers[] = ucwords($value->tester);
            }

            $chk = DB::table('nt_queuing')->where('id', $request->id)->first();
            $datadt = [
                'jobno' => $chk->jobno,
                'title' => $chk->title,
                'model' => $chk->model,
                'tester' => implode(", ", $testers),
                'requester' => $chk->requester,
                'duedate' => $chk->duedate,
            ];
            @$this->sendCancel($datadt);
        }

        return response()->json(['success' => 1]);


    }

    //-------- Acceptance --------
    public function acceptanceQueue(Request $request)
    {
  
        try {

            $chk = DB::table('nt_queuing')->where('id', $request->edit_id)->first();

            if(!empty($chk)){

                $queuing = DB::table('nt_queuing')->orderBy('queue', 'DESC')->first();
                $newqueuing = intval($queuing->queue)+1;

                $queuing = DB::table('nt_queuing')->where('id', $request->edit_id)->first();

                $accept = [
                    'id_queue' => $queuing->id,
                    'accept_measurementtime' => $request->measurementtime,
                    'accept_summarytime' => $request->summarytime,
                    'accept_operator' => $request->operator,
                    'fasttask' => 0,
                    'accept_date' => date('Y-m-d H:i:s'),
                    'created_at' => date('Y-m-d H:i:s'),
                ];
                
                DB::table('nt_queuing_accept')->insert($accept);

                if($newqueuing == 1){

                    $update = [
                        'status' => 1,
                        'duedate' => $request->summarytime,
                        'queue' => $newqueuing
                    ];
                    DB::table('nt_queuing')->where('id', $request->edit_id)->update($update);

                }else{

                    if($request->edit_tag == 1){

                        $fifo = DB::table('nt_queuing')->where('queue','!=',0)->where('duedate','<=', date('Y-m-d',strtotime($chk->duedate)) )->orderBy('queue', 'DESC')->first();


                        if(!empty($fifo)){
                            $newqueuing2 = intval($fifo->queue);
                        }else{
                            $newqueuing2 = 1;
                        }

                        $checkfifo = DB::table('nt_queuing')->where('queue','!=',0)->where('queue', '>=', $newqueuing2)->orderBy('queue', 'ASC')->get();
                        if(count($checkfifo) > 0){

                            $nextqueuing = $newqueuing2+1;
                            foreach ($checkfifo as $key => $value) {
                                $newq = [
                                    'queue' => $nextqueuing,
                                    'duedate' => $request->summarytime, 
                                ];
                                DB::table('nt_queuing')->where('id', $value->id)->update($newq);

                                $nextqueuing++;
                            }

                            $update = [
                                'status' => 1,
                                'queue' => $newqueuing2,
                                'duedate' => $request->summarytime, 
                            ];
                            DB::table('nt_queuing')->where('id', $request->edit_id)->update($update);

                        }else{
                            $update = [
                                'status' => 1,
                                'queue' => $newqueuing2,
                                'duedate' => $request->summarytime, 
                            ];
                            DB::table('nt_queuing')->where('id', $request->edit_id)->update($update);
                        }

                    }else{

                        $update = [
                            'status' => 1,
                            'queue' => $newqueuing,
                            'duedate' => $request->summarytime, 
                        ];
                        DB::table('nt_queuing')->where('id', $request->edit_id)->update($update);
    
                    }
                }

                $this->sendRequest($queuing->id);
                return response()->json(['success' => 1]);

            }else{

                return response()->json(['error' => "พบเลขที่ JOB NO ซ้ำในระบบ"]);

            }

        } catch (Exception $e) {

            $errorlog = [
                'error_log' => $e->getMessage(),
                'error_line' => $e->getLine(),
                'error_url' => url()->current(),
            ];

            $log_id = DB::table('log_backend')->insertGetId($errorlog);

            return response()->json(['error' => "กรุณาแจ้งรหัส Code : $log_id ให้ทางผู้พัฒนาโปรแกรม "]);


        }

    }

    public function sempleQueue(Request $request)
    {

        try {

            $chk = DB::table('nt_queuing')->where('id', $request->edit_id2)->first();

            if(!empty($chk)){

                $samples = [
                    'id_queue' => $request->edit_id2,
                    'sample_date' => $request->sample_date,
                    'sample_operator' => $request->sample_operator, 
                    'sample_create' => date('Y-m-d H:i:s'),
                    'created_at' => date('Y-m-d H:i:s'),
                ];
                DB::table('nt_queuing_sample')->insert($samples);

                $alltester = DB::table('nt_queuing_tester')->where('id_queue', $request->edit_id2)->get();
                foreach ($alltester as $key => $value) {
                    $sub = [
                        'measure_status' => 1
                    ];
                    DB::table('nt_queuing_tester')->where('id_tester', $value->id_tester)->update($sub);
                }

                return response()->json(['success' => 1]);

            }else{

                return response()->json(['error' => "ไม่พบข้อมูลในระบบ กรุณาแจ้งทางผู้พัฒนาโปรแกรม"]);

            }

        } catch (Exception $e) {

            $errorlog = [
                'error_log' => $e->getMessage(),
                'error_line' => $e->getLine(),
                'error_url' => url()->current(),
            ];

            $log_id = DB::table('log_backend')->insertGetId($errorlog);

            return response()->json(['error' => "กรุณาแจ้งรหัส Code : $log_id ให้ทางผู้พัฒนาโปรแกรม "]);


        }

    }
  
    public function queue_edit_deadline(Request $request)
    {
 
        try {

            $thisfifo = DB::table('nt_queuing')->where('id', $request->edit_id4)->first();

            $array=[];
            $getallfifo = DB::table('nt_queuing')->where('queue','>',$thisfifo->queue)->where('queue','!=',0)->orderBy('queue', 'ASC')->get();
            foreach ($getallfifo as $key => $value) {
                $array[] = $value->id;
            }
            $i = intval($thisfifo->queue);
            foreach ($array as $item) {
                $updateA = [
                    'queue' => $i
                ];
                DB::table('nt_queuing')->where('id', $item)->update($updateA);
                $i++;
            }
            $countfifo = DB::table('nt_queuing')->where('queue','!=',0)->count();
            $newfifo = intval($countfifo);
            $update = [
                'duedate' => $request->edit_deadline,
                'oldduedate' => $thisfifo->duedate,
                'queue' => $newfifo
            ];
            DB::table('nt_queuing')->where('id', $request->edit_id4)->update($update);

            return response()->json(['success' => 1]);

        } catch (Exception $e) {

            $errorlog = [
                'error_log' => $e->getMessage(),
                'error_line' => $e->getLine(),
                'error_url' => url()->current(),
            ];

            $log_id = DB::table('log_backend')->insertGetId($errorlog);

            return response()->json(['error' => "กรุณาแจ้งรหัส Code : $log_id ให้ทางผู้พัฒนาโปรแกรม "]);

        }            
    } 

    //------- Measurement ----------

    public function save_measure(Request $request)
    {

        try {
            $chk = DB::table('nt_queuing_tester')->where('id_tester', $request->edit_id5)->first();

            if(!empty($chk)){

                $measure = [
                    'id_tester' => $request->edit_id5,
                    'measure_date' => $request->measurement_date,
                    'measure_operator' => $request->measurement_operator, 
                    'measure_link' => $request->measure_link, 
                    'created_at' => date('Y-m-d H:i:s'),
                ];
                DB::table('nt_queuing_measure')->insert($measure);
                DB::table('nt_queuing_tester')->where('id_tester', $request->edit_id5)->update(['measure_status' => 2,'summary_status' => 1]);

                return response()->json(['success' => 1]);

            }else{

                return response()->json(['error' => "ไม่พบข้อมูลในระบบ กรุณาแจ้งทางผู้พัฒนาโปรแกรม"]);

            }

        } catch (Exception $e) {

            $errorlog = [
                'error_log' => $e->getMessage(),
                'error_line' => $e->getLine(),
                'error_url' => url()->current(),
            ];

            $log_id = DB::table('log_backend')->insertGetId($errorlog);

            return response()->json(['error' => "กรุณาแจ้งรหัส Code : $log_id ให้ทางผู้พัฒนาโปรแกรม "]);


        }

    }

    public function save_measure_complete(Request $request)
    {

        try {

            $chk = DB::table('nt_queuing_tester')->where('id_tester', $request->edit_id6)->first();

            if(!empty($chk)){

                $measure = [
                    'measure_complete' => date('Y-m-d H:i:s', strtotime($request->measure_complete)),
                ];
                DB::table('nt_queuing_measure')->where('id_tester', $request->edit_id6)->update($measure);
                DB::table('nt_queuing_tester')->where('id_tester', $request->edit_id6)->update(['measure_status' => 3,'summary_status' => 2]);

                return response()->json(['success' => 1]);

            }else{

                return response()->json(['error' => "ไม่พบข้อมูลในระบบ กรุณาแจ้งทางผู้พัฒนาโปรแกรม"]);

            }

        } catch (Exception $e) {

            $errorlog = [
                'error_log' => $e->getMessage(),
                'error_line' => $e->getLine(),
                'error_url' => url()->current(),
            ];

            $log_id = DB::table('log_backend')->insertGetId($errorlog);

            return response()->json(['error' => "กรุณาแจ้งรหัส Code : $log_id ให้ทางผู้พัฒนาโปรแกรม "]);


        }

    }

    public function queue_view(Request $request)
    {

        $datalist = DB::table('nt_queuing')->where('id', $request->id)->first();
        echo json_encode($datalist); 

    }
  
    //---------- Sumarry -----------
    function select_info_queue(Request $request)
    {
        $tester = DB::table('nt_queuing_tester')->where('id_tester', $request->id)->first();
        $queuing = DB::table('nt_queuing')->where('id', $tester->id_queue)->first();
        $accept = DB::table('nt_queuing_accept')->where('id_queue', $tester->id_queue)->first();
        $sample = DB::table('nt_queuing_sample')->where('id_queue', $tester->id_queue)->first();
        $measure = DB::table('nt_queuing_measure')->where('id_tester', $request->id)->first();

        $data = [
            'requestdate' => date('d/m/Y', strtotime($queuing->requestdate)),
            'accept_time' => date('d/m/Y H:i', strtotime($accept->accept_date)),
            'measure_time' => date('d/m/Y H:i', strtotime($measure->measure_complete)),
        ];

        echo json_encode($data); 

    }

    public function save_summary(Request $request)
    { 

        try {

            $chk = DB::table('nt_queuing_tester')->where('id_tester', $request->edit_id7)->first();

            if(!empty($chk)){

                $summary = [
                    'id_tester' => $request->edit_id7,
                    'summary_date' => date('Y-m-d H:i:s', strtotime($request->summary_date)),
                    'created_at' => date('Y-m-d H:i:s'),
                ];
                DB::table('nt_queuing_summary')->insert($summary);
                DB::table('nt_queuing_tester')->where('id_tester', $request->edit_id7)->update(['summary_status' => 3]);

                DB::table('nt_queuing')->where('id', $chk->id_queue)->update(['status' => 2]);

                // Verify if the number of tested testers matches the total testers 
                $count = DB::table('nt_queuing_tester')->where('id_queue', $chk->id_queue)->count();
                $countcheck = DB::table('nt_queuing_tester')->where('id_queue', $chk->id_queue)->where('summary_status',3)->count();
                if($count == $countcheck){
                    DB::table('nt_queuing')->where('id', $chk->id_queue)->update(['status' => 3]);
                }

                @$this->sendSummary($request->edit_id7);

                return response()->json(['success' => 1]);

            }else{

                return response()->json(['error' => "ไม่พบข้อมูลในระบบ กรุณาแจ้งทางผู้พัฒนาโปรแกรม"]);

            }

        } catch (Exception $e) {

            $errorlog = [
                'error_log' => $e->getMessage(),
                'error_line' => $e->getLine(),
                'error_url' => url()->current(),
            ];

            $log_id = DB::table('log_backend')->insertGetId($errorlog);

            return response()->json(['error' => "กรุณาแจ้งรหัส Code : $log_id ให้ทางผู้พัฒนาโปรแกรม "]);


        }

    }

    public function queue_search_top(Request $request)
    {

        $startdate = date('Y-m-d 00:00:00', strtotime($request->startdate));
        $enddate = date('Y-m-d 23:59:00', strtotime($request->enddate));

        $sem_count_0 = DB::table('nt_queuing_tester')->where('tester', 'LIKE','SEM/EDX Tester')->where('measure_status','<', 2)->whereBetween('created_at', [$startdate, $enddate])->count();
        $sem_count_1 = DB::table('nt_queuing_tester')->where('tester', 'LIKE','SEM/EDX Tester')->where('summary_status', 2)->whereBetween('created_at', [$startdate, $enddate])->count();
        $sem_count_2 = DB::table('nt_queuing_tester')->where('tester', 'LIKE','SEM/EDX Tester')->where('summary_status', 3)->whereBetween('created_at', [$startdate, $enddate])->count();

        $ftir_count_0 = DB::table('nt_queuing_tester')->where('tester', 'LIKE','FT-IR tester')->where('measure_status','<', 2)->whereBetween('created_at', [$startdate, $enddate])->count();
        $ftir_count_1 = DB::table('nt_queuing_tester')->where('tester', 'LIKE','FT-IR tester')->where('summary_status', 2)->whereBetween('created_at', [$startdate, $enddate])->count();
        $ftir_count_2 = DB::table('nt_queuing_tester')->where('tester', 'LIKE','FT-IR tester')->where('summary_status', 3)->whereBetween('created_at', [$startdate, $enddate])->count();

        $lpc_count_0 = DB::table('nt_queuing_tester')->where('tester', 'LIKE','LPC tester')->where('measure_status','<', 2)->whereBetween('created_at', [$startdate, $enddate])->count();
        $lpc_count_1 = DB::table('nt_queuing_tester')->where('tester', 'LIKE','LPC tester')->where('summary_status', 2)->whereBetween('created_at', [$startdate, $enddate])->count();
        $lpc_count_2 = DB::table('nt_queuing_tester')->where('tester', 'LIKE','LPC tester')->where('summary_status', 3)->whereBetween('created_at', [$startdate, $enddate])->count();

        $gcms_count_0 = DB::table('nt_queuing_tester')->where('tester', 'LIKE','GC-MS Tester')->where('measure_status','<', 2)->whereBetween('created_at', [$startdate, $enddate])->count();
        $gcms_count_1 = DB::table('nt_queuing_tester')->where('tester', 'LIKE','GC-MS Tester')->where('summary_status', 2)->whereBetween('created_at', [$startdate, $enddate])->count();
        $gcms_count_2 = DB::table('nt_queuing_tester')->where('tester', 'LIKE','GC-MS Tester')->where('summary_status', 3)->whereBetween('created_at', [$startdate, $enddate])->count();

        $ic_count_0 = DB::table('nt_queuing_tester')->where('tester', 'LIKE','IC Tester')->where('measure_status','<', 2)->whereBetween('created_at', [$startdate, $enddate])->count();
        $ic_count_1 = DB::table('nt_queuing_tester')->where('tester', 'LIKE','IC Tester')->where('summary_status', 2)->whereBetween('created_at', [$startdate, $enddate])->count();
        $ic_count_2 = DB::table('nt_queuing_tester')->where('tester', 'LIKE','IC Tester')->where('summary_status', 3)->whereBetween('created_at', [$startdate, $enddate])->count();

        $dsc_count_0 = DB::table('nt_queuing_tester')->where('tester', 'LIKE','DSC Tester')->where('measure_status','<', 2)->whereBetween('created_at', [$startdate, $enddate])->count();
        $dsc_count_1 = DB::table('nt_queuing_tester')->where('tester', 'LIKE','DSC Tester')->where('summary_status', 2)->whereBetween('created_at', [$startdate, $enddate])->count();
        $dsc_count_2 = DB::table('nt_queuing_tester')->where('tester', 'LIKE','DSC Tester')->where('summary_status', 3)->whereBetween('created_at', [$startdate, $enddate])->count();

        $weight_count_0 = DB::table('nt_queuing_tester')->where('tester', 'LIKE','Weight Tester')->where('measure_status','<', 2)->whereBetween('created_at', [$startdate, $enddate])->count();
        $weight_count_1 = DB::table('nt_queuing_tester')->where('tester', 'LIKE','Weight Tester')->where('summary_status', 2)->whereBetween('created_at', [$startdate, $enddate])->count();
        $weight_count_2 = DB::table('nt_queuing_tester')->where('tester', 'LIKE','Weight Tester')->where('summary_status', 3)->whereBetween('created_at', [$startdate, $enddate])->count();

        $dust_count_0 = DB::table('nt_queuing_tester')->where('tester', 'LIKE','Fallen Dust Tester')->where('measure_status','<', 2)->whereBetween('created_at', [$startdate, $enddate])->count();
        $dust_count_1 = DB::table('nt_queuing_tester')->where('tester', 'LIKE','Fallen Dust Tester')->where('summary_status', 2)->whereBetween('created_at', [$startdate, $enddate])->count();
        $dust_count_2 = DB::table('nt_queuing_tester')->where('tester', 'LIKE','Fallen Dust Tester')->where('summary_status', 3)->whereBetween('created_at', [$startdate, $enddate])->count();

        $smoke_count_0 = DB::table('nt_queuing_tester')->where('tester', 'LIKE','Smoke Tester')->where('measure_status','<', 2)->whereBetween('created_at', [$startdate, $enddate])->count();
        $smoke_count_1 = DB::table('nt_queuing_tester')->where('tester', 'LIKE','Smoke Tester')->where('summary_status', 2)->whereBetween('created_at', [$startdate, $enddate])->count();
        $smoke_count_2 = DB::table('nt_queuing_tester')->where('tester', 'LIKE','Smoke Tester')->where('summary_status', 3)->whereBetween('created_at', [$startdate, $enddate])->count();

        $data = array(
            "sem_count_0" => ($sem_count_0 > 0)? $sem_count_0 : '-',
            "sem_count_1" => ($sem_count_1 > 0)? $sem_count_1 : '-',
            "sem_count_2" => ($sem_count_2 > 0)? $sem_count_2 : '-',

            "ftir_count_0" => ($ftir_count_0 > 0)? $ftir_count_0 : '-',
            "ftir_count_1" => ($ftir_count_1 > 0)? $ftir_count_1 : '-',
            "ftir_count_2" => ($ftir_count_2 > 0)? $ftir_count_2 : '-',

            "lpc_count_0" => ($lpc_count_0 > 0)? $lpc_count_0 : '-',
            "lpc_count_1" => ($lpc_count_1 > 0)? $lpc_count_1 : '-',
            "lpc_count_2" => ($lpc_count_2 > 0)? $lpc_count_2 : '-',

            "gcms_count_0" => ($gcms_count_0 > 0)? $gcms_count_0 : '-',
            "gcms_count_1" => ($gcms_count_1 > 0)? $gcms_count_1 : '-',
            "gcms_count_2" => ($gcms_count_2 > 0)? $gcms_count_2 : '-',

            "ic_count_0" => ($ic_count_0 > 0)? $ic_count_0 : '-',
            "ic_count_1" => ($ic_count_1 > 0)? $ic_count_1 : '-',
            "ic_count_2" => ($ic_count_2 > 0)? $ic_count_2 : '-',

            "dsc_count_0" => ($dsc_count_0 > 0)? $dsc_count_0 : '-',
            "dsc_count_1" => ($dsc_count_1 > 0)? $dsc_count_1 : '-',
            "dsc_count_2" => ($dsc_count_2 > 0)? $dsc_count_2 : '-',

            "weight_count_0" => ($weight_count_0 > 0)? $weight_count_0 : '-',
            "weight_count_1" => ($weight_count_1 > 0)? $weight_count_1 : '-',
            "weight_count_2" => ($weight_count_2 > 0)? $weight_count_2 : '-',

            "dust_count_0" => ($dust_count_0 > 0)? $dust_count_0 : '-',
            "dust_count_1" => ($dust_count_1 > 0)? $dust_count_1 : '-',
            "dust_count_2" => ($dust_count_2 > 0)? $dust_count_2 : '-',

            "smoke_count_0" => ($smoke_count_0 > 0)? $smoke_count_0 : '-',
            "smoke_count_1" => ($smoke_count_1 > 0)? $smoke_count_1 : '-',
            "smoke_count_2" => ($smoke_count_2 > 0)? $smoke_count_2 : '-',

        );
        echo json_encode($data);


    }
    

}
