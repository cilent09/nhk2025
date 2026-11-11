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
use App\Mail\Mailnhk;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function mailconfirm($data)
    {

        $subject = $data['subject'];
        $body =  "<html><head><body>
                <b>".$subject."</b><br>
                Attention all,<br><br>
                <b style='font-size: xx-large; font-family: fantasy; color: #20b820;'>[NEW]</b>
                <br><br>
                New cleanliness result has arrived as detailed below.
                <br>
                <table border=1>
                    <tr>
                        <th>Item</th>
                        <th>Detail</th>
                    </tr>
                    <tr>
                        <td>Tester</td>
                        <td>" . $data['testter'] . "</td>
                    </tr>
                    <tr>
                        <td>Model</td>
                        <td>" . $data['model'] . "</td>
                    </tr>
                    <tr>
                        <td>Lot number</td>
                        <td>" . $data['lot'] . "</td>
                    </tr>
                    <tr>
                        <td>Upload date</td>
                        <td>" . $data['date'] . "</td>
                    </tr>
                </table>
                <br>
                <b style='color: blue;'>The Lab operator has uploaded and confirmed the measurement results.</b>
                <br><br>
                If you have any questions or concerns, please feel free to contact our Lab Engineer. <br>
                *** This is an automatically generated email, please do not reply ***
                <br><br>
                Best Regards,<br>
                Cleanliness lab<br>
                QA Section<br>
                Disk Drive Suspension Division<br>
                NHK SPRING (THAILAND) CO.,LTD.
                </body>
                </html>
                <br><br><br>
            ";

        $info  = [
            'body' => $body,
            'subject' => $subject,
            // 'to' => $toemail,
        ];
        $test = Mail::send(['html' => 'mail'], $info, function ($message) use ($info) {
            // $message->to(['weerawan.iea@nhkspg.co.th','dataqa.dds@nhkspg.co.th','ekkaparp.jun@nhkspg.co.th','numfon.sin@nhkspg.co.th','Surasak.hoe@nhkspg.co.th','narupon@nhkspg.co.th','piyachat.mab@nhkspg.co.th','sarocha.man@nhkspg.co.th'])->subject($info['subject']);
            $message->to(['cleanliness.lab@nhkspg.co.th',
            'weerawan.iea@nhkspg.co.th',
            'surasak.hoe@nhkspg.co.th',
            'ekkaparp.jun@nhkspg.co.th',
            'piyachat.mab@nhkspg.co.th',
            'sarocha.man@nhkspg.co.th',
            'narupon@nhkspg.co.th',
            'numfon.sin@nhkspg.co.th',
            'dataqa.dds@nhkspg.co.th',
            ])->subject($info['subject']);
			$message->from('cleanliness.dds@nhkspg.co.th');
        });

    }

     public function mailalarm($data)
    {

        $subject = "[CAS program]_Cleanliness data NG!";
        $body =  "<html><head><body style='font-family: Tahoma;'>
        <b>".$subject."</b><br>
        Attention all,<br><br>
        <b style='font-size: xx-large; font-family: fantasy; color: red;'>[1st failed the test]</b>
        <br><br>
        The measurement results did <b style=' color: red; '>not pass!!!</b> as detailed below.
        <br>
        <table border=1>
            <tr>
                <th>Item</th>
                <th>Detail</th>
            </tr>
            <tr>
                <td>Tester</td>
                <td>" . $data['testter'] . "</td>
            </tr>
            <tr>
                <td>Model</td>
                <td>" . $data['model'] . "</td>
            </tr>
            <tr>
                <td>Lot number</td>
                <td>" . $data['lot'] . "</td>
            </tr>
            <tr>
                <td>Upload date</td>
                <td>" . $data['date'] . "</td>
            </tr>
            <tr>
                <td>Result</td>
                <td>" . @$data['result'] . "</td>
            </tr>
            <tr>
                <td>UCL</td>
                <td>" . @$data['UCL'] . "</td>
            </tr>
            <tr>
                <td rowspan='2'>Alarm detail</td>
                <td style=' color: red; font-weight: 600;'>" . $data['alarm'] . " point </td>
            </tr>
            <tr>
                <td style='font-weight: 600;'><a href='http://10.6.100.17/cas_program/output/viewdata/" . $data['idqla'] . "'> >>ลิงค์ Alarm point<< </a></td>
            </tr>
        </table>
        <br><br>
        If you have any questions or concerns, please feel free to contact our Lab Engineer.<br>
        *** This is an automatically generated email, please do not reply ***
        <br><br>
        Best Regards,<br>
        Cleanliness lab<br>
        QA Section<br>
        Disk Drive Suspension Division<br>
        NHK SPRING (THAILAND) CO.,LTD.
        </body>
        </html>
        <br><br><br>";


        $info  = [
            'body' => $body,
            'subject' => $subject,
        ];

        $test =   Mail::send(['html' => 'mail'], $info, function ($message) use ($info) {
            $message->to(['cleanliness.lab@nhkspg.co.th',
                'weerawan.iea@nhkspg.co.th',
                'surasak.hoe@nhkspg.co.th',
                'ekkaparp.jun@nhkspg.co.th',
                'piyachat.mab@nhkspg.co.th',
                'sarocha.man@nhkspg.co.th',
                'narupon@nhkspg.co.th',
                'numfon.sin@nhkspg.co.th',
                'dataqa.dds@nhkspg.co.th',
                'kittana.jar@nhkspg.co.th',
                'montri.khe@nhkspg.co.th',
                'noppadon@nhkspg.co.th',
                'patavee.sak@nhkspg.co.th',
                'patchara@nhkspg.co.th',
                'premsak.kwa@nhkspg.co.th',
                'rapeepat.thi@nhkspg.co.th',
                'thanakan.pha@nhkspg.co.th',
                'thananuch@nhkspg.co.th',
                'thanawut.rak@nhkspg.co.th',
                'tharapong.sat@nhkspg.co.th',
            ])->subject($info['subject']);
            $message->from('cleanliness.dds@nhkspg.co.th');
        });

    }

    public function mailalarmstaff($data)
    {
        $check = new TestController();
        $val_check =  $check->chk_alertbeforeemail($data['testter'],$data['lot']);

        $subject = "[CAS program]_Cleanliness data NG! (failed the test)";
        $body =  "<html><head><body style='font-family: Tahoma;'>
        <b>".$subject."</b><br>
        Attention all,<br><br>
        <br>
        The measurement results did <b style=' color: red; '>not pass </b> as detailed below.
        <br>
        <table border=1>
            <tr>
                <th>Item</th>
                <th>Detail</th>
            </tr>
            <tr>
                <td>Tester</td>
                <td>" . $data['testter'] . "</td>
            </tr>
            <tr>
                <td>Model</td>
                <td>" . $data['model'] . "</td>
            </tr>
            <tr>
                <td>Lot number</td>
                <td>" . $data['lot'] . "</td>
            </tr>
            <tr>
                <td>Upload date</td>
                <td>" . $data['date'] . "</td>
            </tr>
            <tr>
                <td>Result</td>
                <td>" . @$val_check['data'] . "</td>
            </tr>
            <tr>
                <td>UCL</td>
                <td>" . @$val_check['UCL'] . "</td>
            </tr>
            <tr>
                <td rowspan='2'>Alarm detail</td>
                <td style=' color: red; font-weight: 600;'>" . $data['alarm'] . " point </td>
            </tr>
            <tr>
                <td style='font-weight: 600;'><a href='http://10.6.100.17/cas_program/output/viewdata/" . $data['idqla'] . "'> >>ลิงค์ Alarm point<< </a></td>
            </tr>
        </table>
        <br><br>
        If you have any questions or concerns, please feel free to contact our Lab Engineer.<br>
        *** This is an automatically generated email, please do not reply ***
        <br><br>
        Best Regards,<br>
        Cleanliness lab<br>
        QA Section<br>
        Disk Drive Suspension Division<br>
        NHK SPRING (THAILAND) CO.,LTD.
        </body>
        </html>
        <br><br><br>";

        $info  = [
            'body' => $body,
            'subject' => $subject,
        ];
        $test = Mail::send(['html' => 'mail'], $info, function ($message) use ($info) {
            $message->to(['cleanliness.lab@nhkspg.co.th',
                'weerawan.iea@nhkspg.co.th',
                'surasak.hoe@nhkspg.co.th',
                'ekkaparp.jun@nhkspg.co.th',
                'piyachat.mab@nhkspg.co.th',
                'sarocha.man@nhkspg.co.th',
                'narupon@nhkspg.co.th',
                'numfon.sin@nhkspg.co.th',
                'dataqa.dds@nhkspg.co.th',
                'kittana.jar@nhkspg.co.th',
                'montri.khe@nhkspg.co.th',
                'noppadon@nhkspg.co.th',
                'patavee.sak@nhkspg.co.th',
                'patchara@nhkspg.co.th',
                'premsak.kwa@nhkspg.co.th',
                'rapeepat.thi@nhkspg.co.th',
                'thanakan.pha@nhkspg.co.th',
                'thananuch@nhkspg.co.th',
                'thanawut.rak@nhkspg.co.th',
                'tharapong.sat@nhkspg.co.th',
            ])
            ->subject($info['subject']);
            $message->from('cleanliness.dds@nhkspg.co.th');
        });

    }

    public function mailalarmsemtest($data)
    {
        $subject = "[CAS program]_Cleanliness data NG!";
        $body =  "<html><head><body style='font-family: Tahoma;'>
        <b>".$subject."</b><br>
        Attention all,<br><br>
        <b style='font-size: xx-large; font-family: fantasy; color: red;'>[1st failed the test]</b>
        <br><br>
        The measurement results did <b style=' color: red; '>not pass!!!</b> as detailed below.
        <br>
        <table border=1>
            <tr>
                <th>Item</th>
                <th>Detail</th>
            </tr>
            <tr>
                <td>Tester</td>
                <td>" . $data['testter'] . "</td>
            </tr>
            <tr>
                <td>Model</td>
                <td>" . $data['model'] . "</td>
            </tr>
            <tr>
                <td>Lot number</td>
                <td>" . $data['lot'] . "</td>
            </tr>
            <tr>
                <td>Upload date</td>
                <td>" . $data['date'] . "</td>
            </tr>
            <tr>
                <td>Result</td>
                <td>" . @$data['result'] . "</td>
            </tr>
            <tr>
                <td>UCL</td>
                <td>" . @$data['UCL'] . "</td>
            </tr>
            <tr>
                <td rowspan='2'>Alarm detail</td>
                <td style=' color: red; font-weight: 600;'>" . $data['alarm'] . " point </td>
            </tr>
            <tr>
                <td style='font-weight: 600;'><a href='http://10.6.100.17/cas_program/output/viewdata/" . $data['idqla'] . "'> >>ลิงค์ Alarm point<< </a></td>
            </tr>
        </table>
        <br><br>
        If you have any questions or concerns, please feel free to contact our Lab Engineer.<br>
        *** This is an automatically generated email, please do not reply ***
        <br><br>
        Best Regards,<br>
        Cleanliness lab<br>
        QA Section<br>
        Disk Drive Suspension Division<br>
        NHK SPRING (THAILAND) CO.,LTD.
        </body>
        </html>
        <br><br><br>
        ";

        $info  = [
            'body' => $body,
            'subject' => $subject,
            // 'to' => $toemail,
        ];

        $test = Mail::send(['html' => 'mail'], $info, function ($message) use ($info) {
             $message->to(['cleanliness.dds@nhkspg.co.th',
            'weerawan.iea@nhkspg.co.th',
            'dataqa.dds@nhkspg.co.th',
            'ekkaparp.jun@nhkspg.co.th',
            'surasak.hoe@nhkspg.co.th',
            'chanon.oum@nhkspg.co.th',
            'kittana.jar@nhkspg.co.th',
            'montri.khe@nhkspg.co.th',
            'narupon@nhkspg.co.th',
            'noppadon@nhkspg.co.th',
            'numfon.sin@nhkspg.co.th',
            'patavee.sak@nhkspg.co.th',
            'patchara@nhkspg.co.th',
            'piyachat.mab@nhkspg.co.th',
            'rapeepat.thi@nhkspg.co.th',
            'sarocha.man@nhkspg.co.th',
            'sawanya.kho@nhkspg.co.th',
            'thanakan.pha@nhkspg.co.th',
            'thananuch@nhkspg.co.th',
            'thanawut.rak@nhkspg.co.th',
            'tharapong.sat@nhkspg.co.th'
            ])
            ->subject($info['subject']);
            $message->from('cleanliness.dds@nhkspg.co.th');
        });

        // $test = Mail::send(['html' => 'mail'], $info, function ($message) use ($info) {
        //     $message->to(['weerawan.iea@nhkspg.co.th',
        //     'dataqa.dds@nhkspg.co.th',
        //     'ekkaparp.jun@nhkspg.co.th',
        //     'numfon.sin@nhkspg.co.th',
        //     'Surasak.hoe@nhkspg.co.th',
        //     'narupon@nhkspg.co.th',
        //     'piyachat.mab@nhkspg.co.th',
        //     'sarocha.man@nhkspg.co.th',
        //     'kittana.jar@nhkspg.co.th',
        //     'thananuch@nhkspg.co.th',
        //     'montri.khe@nhkspg.co.th',
        //     'rapeepat.thi@nhkspg.co.th',
        //     'noppadon@nhkspg.co.th',
        //     'thanawut.rak@nhkspg.co.th',
        //     'patavee.sak@nhkspg.co.th',
        //     'tharapong.sat@nhkspg.co.th',
        //     'phasit@nhkspg.co.th',
        //     'thanakan.pha@nhkspg.co.th',
        //     'chanon.oum@nhkspg.co.th',
        //     'sawanya.kho@nhkspg.co.th',
        //     'wachera.rit@nhkspg.co.th',
        //     'warut.chu@nhkspg.co.th',
        //     'artitayaporn.von@nhkspg.co.th',
        //     'prakaydaow.wan@nhkspg.co.th',
        //     'patchara@nhkspg.co.th',
        //     'suthatip.wai@nhkspg.co.th',
        //     'suttipong.sut@nhkspg.co.th',
        //     'arunkamon.pon@nhkspg.co.th',
        //     'runya.jir@nhkspg.co.th',
        //     'pattarawarin.kho@nhkspg.co.th',
        //     'nattaporn.rat@nhkspg.co.th',
        //     'supanida.sud@nhkspg.co.th',
        //     'natthanicha.cha@nhkspg.co.th',
        //     'pornphimol.sri@nhkspg.co.th',
        //     'sirirat.pai@nhkspg.co.th',
        //     'weerapan.nan@nhkspg.co.th',
        //     'artite.kum@nhkspg.co.th',
        //     'sake.cha@nhkspg.co.th',
        //     'naiyanun.kha@nhkspg.co.th',
        //     'chutinan.tho@nhkspg.co.th',
        //     'y.kanokporn@nhkspg.co.th',
        //     'yatika.boo@nhkspg.co.th',
        //     'saravut.thi@nhkspg.co.th',
        //     'narongsak.pan@nhkspg.co.th',
        //     'anake.yoo@nhkspg.co.th',
        //     'tanapong.ker@nhkspg.co.th',
        //     'prin.put@nhkspg.co.th',
        //     'prawit.pra@nhkspg.co.th',
        //     'parsit.pou@nhkspg.co.th',
        //     'suradet.mee@nhkspg.co.th',
        //     'parada.sil@nhkspg.co.th',
        //     'jeerapun.pec@nhkspg.co.th',
        //     'tanet.kha@nhkspg.co.th',
        //     'sanmuang.nua@nhkspg.co.th',
        //     'itsara.sit@nhkspg.co.th',
        //     'rati.lam@nhkspg.co.th',
        //     'gun.pec@nhkspg.co.th',
        //     'thiparpa.sue@nhkspg.co.th',
        //     'kittamet.lum@nhkspg.co.th',
        //     'yoottapop.pal@nhkspg.co.th',
        //     'krisada.kra@nhkspg.co.th',
        //     'natthaphong.thi@nhkspg.co.th',
        //     'nattapon.chu@nhkspg.co.th',
        //     'pongsatorn.thi@nhkspg.co.th',
        //     'wanwisa.own@nhkspg.co.th',
        //     'kasira.int@nhkspg.co.th',
        //     'tharachit.tha@nhkspg.co.th',
        //     'akkarapong.the@nhkspg.co.th',
        //     'eakarat.won@nhkspg.co.th',
        //     'pongsakorn.pen@nhkspg.co.th',
        //     'l.weerachat@nhkspg.co.th',
        //     'amnat.bua@nhkspg.co.th',
        //     'aphisak.nia@nhkspg.co.th',
        //     'lertkanok.moo@nhkspg.co.th',
        //     'eakeyuth.som@nhkspg.co.th',
        //     'paiwan.eak@nhkspg.co.th',
        //     'jakravoot.suw@nhkspg.co.th',
        //     'sarawut.p@nhkspg.co.th',
        //     'chon.sak@nhkspg.co.th',
        //     'pongthep.sae@nhkspg.co.th',
        //     'kiattisak.att@nhkspg.co.th',
        //     'nattapong@nhkspg.co.th',
        //     'saksit.int@nhkspg.co.th',
        //     'k.weerasak@nhkspg.co.th',
        //     'thushakorn@nhkspg.co.th',
        //     'korakot@nhkspg.co.th',
        //     'sawittree.sae@nhkspg.co.th',
        //     'weerapon@nhkspg.co.th',
        //     's.chatchai@nhkspg.co.th',
        //     'peerayoot.poo@nhkspg.co.th',
        //     'ittiput.kea@nhkspg.co.th',
        //     'vasuthon.nor@nhkspg.co.th',
        //     'chaiwat.nar@nhkspg.co.th',
        //     'sarayut.ter@nhkspg.co.th',
        //     'natthasak.pie@nhkspg.co.th',
        //     'weerachon.phu@nhkspg.co.th',
        //     ])
        //     ->cc(['sittichai.cho@nhkspg.co.th',
        //     'sukanya.aru@nhkspg.co.th',
        //     'paiwan.eak@nhkspg.co.th',
        //     'nattapong@nhkspg.co.th',
        //     'tortrakul@nhkspg.co.th',
        //     'y.kanokporn@nhkspg.co.th',
        //     'anake.yoo@nhkspg.co.th',
        //     'jeerapun.pec@nhkspg.co.th',
        //     'prakaydaow.wan@nhkspg.co.th',
        //     'wichai@nhkspg.co.th',
        //     'weerapon@nhkspg.co.th',
        //     's.chatchai@nhkspg.co.th',
        //     'mongkol@nhkspg.co.th',
        //     'ittidach_k@nhkspg.co.th',
        //     'assanai@nhkspg.co.th',
        //     'phasit@nhkspg.co.th',
        //     'woravit@nhkspg.co.th',
        //     'nantarat@nhkspg.co.th',
        //     'patchara@nhkspg.co.th',
        //     'k.nakajima@nhkspg.co.th',
        //     'h.kashima@nhkspg.co.th',
        //     'h.kobayashi@nhkspg.co.th',
        //     'k.sugiyama@nhkspg.co.th',
        //     ])
        //     ->subject($info['subject']);
        //     $message->from('cleanliness.dds@nhkspg.co.th');
        // });
    }

    public function maileditdata($data)
    {

        $subject = "[CAS program]_Staff already REVIEW data";
        // $body =  "<html><head><body>
        // Dear all,<br><br><br><br>
        // Staff ทำการ <b><span style='color: #0000CD'> review data </span></b> ที่มีการ alarm แล้ว ตามรายละเอียดด้านล่าง<br>
        // Tester : " . $data['testter'] . "<br>
        // Model : " . $data['model'] . "<br>
        // Lot number : <b>" . $data['lot'] . "</b><br>
        // Review date  : " . $data['date'] . "<br>
        // <br><br>
        // <br><br>
        // Best Regards,<br>
        // Cleanliness lab<br>
        // QA Section<br>
        // Disk Drive Suspension Division<br>
        // NHK SPRING (THAILAND) CO.,LTD.<br><b>
        // </body>
        // </html>
        // <br><br><br>
        // ";

        $body =  "<html><head><body style='font-family: Tahoma;'>
        <b>".$subject."</b><br>
        Attention all,<br><br>
        <b style='font-size: xx-large; font-family: fantasy; color: blue;'>[Review]</b>
        <br><br>
        Staff reviews the data that has been alarmed as detail below.
        <br>
        <table border=1>
            <tr>
                <th>Item</th>
                <th>Detail</th>
            </tr>
            <tr>
                <td>Tester</td>
                <td>" . $data['testter'] . "</td>
            </tr>
            <tr>
                <td>Model</td>
                <td>" . $data['model'] . "</td>
            </tr>
            <tr>
                <td>Lot number</td>
                <td>" . $data['lot'] . "</td>
            </tr>
            <tr>
                <td>Upload date</td>
                <td>" . $data['date'] . "</td>
            </tr>
        </table>
        <br><br>
        If you have any questions or concerns, please feel free to contact our Lab Engineer. <br>
        *** This is an automatically generated email, please do not reply ***
        <br><br>
        Best Regards,<br>
        Cleanliness lab<br>
        QA Section<br>
        Disk Drive Suspension Division<br>
        NHK SPRING (THAILAND) CO.,LTD.
        </body>
        </html>
        <br><br><br>
        ";

        $info  = [
            'body' => $body,
            'subject' => $subject,
            // 'to' => $toemail,
        ];
        $test =   Mail::send(['html' => 'mail'], $info, function ($message) use ($info) {
            $message->to(['dataqa.dds@nhkspg.co.th','numfon.sin@nhkspg.co.th','Surasak.hoe@nhkspg.co.th','narupon@nhkspg.co.th','piyachat.mab@nhkspg.co.th','sarocha.man@nhkspg.co.th','weerawan.iea@nhkspg.co.th','ekkaparp.jun@nhkspg.co.th'])->subject($info['subject']);
            $message->from('cleanliness.dds@nhkspg.co.th');
        });
    }

    public function mailccp($data)
    {
        $subject = "[CAS program]_Document team already EXPORT CCP FILE";
        // $body =  "<html><head><body>
        // Dear all,<br><br><br><br>
        // Document team ทำการ <b><span style='color: #29c23a'>  export CCP file </span></b> เรียบร้อยแล้ว ตามรายละเอียดด้านล่าง<br>
        // Tester : " . $data['testter'] . "<br>
        // Model : " . $data['model'] . "<br>
        // Lot number : <b>" . $data['lot'] . "</b><br>
        // Export date : " . $data['date'] . "<br>
        // <br><br>
        // <br><br>
        // Best Regards,<br>
        // Cleanliness lab<br>
        // QA Section<br>
        // Disk Drive Suspension Division<br>
        // NHK SPRING (THAILAND) CO.,LTD.<br><b>
        // </body>
        // </html>
        // <br><br><br>
        // ";

        $body =  "<html><head><body style='font-family: Tahoma;'>
        <b>".$subject."</b><br>
        Attention all,<br><br>
        <b style='font-size: xx-large; font-family: fantasy; color: purple;'>[CCP]</b>
        <br><br>
        The document operator has prepared the <code style=' color: purple; font-weight: 600; '>CCP</code> file as detailed below.
        <br>
        <table border=1>
            <tr>
                <th>Item</th>
                <th>Detail</th>
            </tr>
            <tr>
                <td>Tester</td>
                <td>" . $data['testter'] . "</td>
            </tr>
            <tr>
                <td>Model</td>
                <td>" . $data['model'] . "</td>
            </tr>
            <tr>
                <td>Lot number</td>
                <td>" . $data['lot'] . "</td>
            </tr>
            <tr>
                <td>Upload date</td>
                <td>" . $data['date'] . "</td>
            </tr>
        </table>
        <br><br>
        If you have any questions or concerns, please feel free to contact our Lab Engineer. <br>
        *** This is an automatically generated email, please do not reply ***
        <br><br>
        Best Regards,<br>
        Cleanliness lab<br>
        QA Section<br>
        Disk Drive Suspension Division<br>
        NHK SPRING (THAILAND) CO.,LTD.
        </body>
        </html>
        <br><br><br>
        ";

        $info  = [
            'body' => $body,
            'subject' => $subject,
        ];
        $test =   Mail::send(['html' => 'mail'], $info, function ($message) use ($info) {
            $message->to(['cleanliness.lab@nhkspg.co.th',
            'weerawan.iea@nhkspg.co.th',
            'surasak.hoe@nhkspg.co.th',
            'ekkaparp.jun@nhkspg.co.th',
            'piyachat.mab@nhkspg.co.th',
            'sarocha.man@nhkspg.co.th',
            'narupon@nhkspg.co.th',
            'numfon.sin@nhkspg.co.th',
            'dataqa.dds@nhkspg.co.th',
            ])->subject($info['subject']);
            $message->from('cleanliness.dds@nhkspg.co.th');
        });
    }

    static function mailmonitoring()
    {

        $subject = "[CAS program]_Monitoring of tester";
        $message =  "<html><head><body style='font-family: Tahoma;'>
        Dear all,<br><br>
        <p>สรุปผล Monitoring of tester ที่ On-going ตามรายละเอียดด้านล่าง</p>
        </body>
        </html>
        ";
        $message .= "<br> Review ผล monitoring :<a href='http://10.6.100.17/cas_program/output/monitoring'> >> ลิงค์ Monitoring << </a><br><br>";

        $arrset = [];
        $infodata = DB::table('nt_monitor_info')
        ->join('nt_monitor_set', 'nt_monitor_info.id_mns', '=', 'nt_monitor_set.id_mns')
        ->join('nt_monitor_unit', 'nt_monitor_set.id_mnu', '=', 'nt_monitor_unit.id_mnu')
        ->where('nt_monitor_info.due_date', 'like', date('Y-m').'%')
        ->whereNull('nt_monitor_info.recoredtime')
        ->orderby('nt_monitor_info.recoredtime','DESC')
        ->get();

        foreach ($infodata as $key => $value) {
            $arrset[] = $value->id_mns;
        }
        $result = array_unique($arrset);
        $getdata = DB::table('nt_monitor_set')
        ->join('nt_monitor_unit', 'nt_monitor_set.id_mnu', '=', 'nt_monitor_unit.id_mnu')
        ->whereIn('nt_monitor_set.id_mns',$result)
        ->get();

        $message .= "<table style=' table-layout: fixed; border-collapse: collapse; border: 1px solid #070707; '>";
        $message .= "<tr style='border: 1px solid;'>";
        $message .= "<th style='border: 1px solid;'>Tester</th>";
        $message .= "<th style='border: 1px solid;'>Topic</th>";
        $message .= "</tr>";

        foreach ($getdata as $key => $value) {

            $message .= "<tr style='border: 1px solid; text-align: center;'>";
            $message .= "<td style='border: 1px solid;'>".$value->name_tester."</td>";
            $message .= "<td style='border: 1px solid;'>".$value->topic."</td>";
            $message .= "</tr>";
        }

        $message .= "</table>";

        $today = date('Y-m-d');
        $timetoday = date('H:i');
        $targettime = date('09:00');
        $targetDate1 = date('Y-m-10');
        $targetDate2 = date('Y-m-15');

        if (($today == $targetDate1) || ($today == $targetDate2)) {

            if($timetoday >= $targettime){

                if(DB::table('nt_monitor_log_mail')->where('date','LIKE',$today)->count() == 0){

                    $data = [
                        'date' => $today
                    ];
                    DB::table('nt_monitor_log_mail')->insert($data);

                    $info  = [
                        'body' => $message,
                        'subject' => $subject,
                    ];
                    $test =   Mail::send(['html' => 'mail'], $info, function ($message) use ($info) {
                        $message->to(['cleanliness.lab@nhkspg.co.th',
                        'weerawan.iea@nhkspg.co.th',
                        'surasak.hoe@nhkspg.co.th',
                        'ekkaparp.jun@nhkspg.co.th',
                        'piyachat.mab@nhkspg.co.th',
                        'sarocha.man@nhkspg.co.th',
                        'narupon@nhkspg.co.th',
                        'numfon.sin@nhkspg.co.th',
                        'dataqa.dds@nhkspg.co.th',
                        ])->subject($info['subject']);
                        $message->from('cleanliness.dds@nhkspg.co.th');
                    });

                }
            }

        }


    }

    public function mailconfirmlpcWD($data)
    {
        $subject = "[CAS program]_You have a new cleanliness results_WD";
        $body =  "<html><head><body style='font-family: Tahoma;'>
            <b>".$subject."</b><br>
            Attention all,<br><br>
            <b style='font-size: xx-large; font-family: fantasy; color: #20b820;'>[NEW]</b>
            <br><br>
            New cleanliness result has arrived as detailed below.
            <br>
            <table border=1>
                <tr>
                    <th>Item</th>
                    <th>Detail</th>
                </tr>
                <tr>
                    <td>Tester</td>
                    <td>" . $data['testter'] . "</td>
                </tr>
                <tr>
                    <td>Model</td>
                    <td>" . $data['model'] . "</td>
                </tr>
                <tr>
                    <td>Lot number</td>
                    <td>" . $data['lot'] . "</td>
                </tr>
                <tr>
                    <td>Upload date</td>
                    <td>" . $data['date'] . "</td>
                </tr>
            </table>
            <br>
            <b style='color: blue;'>The Lab operator has uploaded and confirmed the measurement results.</b>
            <br><br>
            If you have any questions or concerns, please feel free to contact our Lab Engineer. <br>
            *** This is an automatically generated email, please do not reply ***
            <br><br>
            Best Regards,<br>
            Cleanliness lab<br>
            QA Section<br>
            Disk Drive Suspension Division<br>
            NHK SPRING (THAILAND) CO.,LTD.
            </body>
            </html>
            <br><br><br>";

            $info  = [
                'body' => $body,
                'subject' => $subject,
            ];
            $test = Mail::send(['html' => 'mail'], $info, function ($message) use ($info) {
                $message->to(['cleanliness.lab@nhkspg.co.th',
                    'weerawan.iea@nhkspg.co.th',
                    'surasak.hoe@nhkspg.co.th',
                    'ekkaparp.jun@nhkspg.co.th',
                    'piyachat.mab@nhkspg.co.th',
                    'sarocha.man@nhkspg.co.th',
                    'narupon@nhkspg.co.th',
                    'numfon.sin@nhkspg.co.th',
                    'dataqa.dds@nhkspg.co.th',
                ])
                ->subject($info['subject']);
                $message->from('cleanliness.dds@nhkspg.co.th');
            });
    }

    public function mailconfirmlpcAML($data)
    {
        // AMLLPC
        $files = $data['file'];
        $getfile = storage_path('NHK-File/OQA/JNHK/LPC/'.$files);

        // <tr>
        //  <td>AML Link file </td>
        //  <td><a href='http://10.6.100.17/cas_program/local/storage/NHK-File/OQA/JNHK/LPC/" . $files . "'>>>Click Dowload<<</a></td>
        //  </tr>

        // $subject = "[CAS program]_Submit already EXPORT AML FILE";
        $subject = "[CAS program]_Return AML file already updated";
        $body =  "<html><head><body style='font-family: Tahoma;'>
        <p style=' font-size: x-large; font-family: fantasy; color: #1f9eff;'>[Return AML file]</p>
        <p>Return AML file already updated as detail below.</p><br>
        <table border='1' style='border-collapse: collapse;'>
            <tr>
                <td style=' width: 140px; text-align: -webkit-center; '><b>Item</b></td>
                <td style=' width: 200px; text-align: -webkit-center; '><b>Detail</b></td>
            </tr>
            <tr>
                <td>Tester</td>
                <td>" . $data['testter'] . "</td>
            </tr>
            <tr>
                <td>Model</td>
                <td>" . $data['model'] . "</td>
            </tr>
            <tr>
                <td>Lot number</td>
                <td>" . $data['lot'] . "</td>
            </tr>
            <tr>
                <td>Upload date </td>
                <td>" . $data['date'] . "</td>
            </tr>
        </table>
        <br>
        <p>If you have any questions or concerns, please feel free to contact our Lab Engineer.<br>
        *** This is an automatically generated email, please do not reply ***</p>
        <br>
        Best Regards,<br>
        Cleanliness lab<br>
        QA Section<br>
        Disk Drive Suspension Division<br>
        NHK SPRING (THAILAND) CO.,LTD.<br><b>
        </body>
        </html>
        <br><br><br>";

        $info  = [
            'body' => $body,
            'subject' => $subject,
        ];

        $test = Mail::send(['html' => 'mail'], $info, function ($message) use ($info) {
            $message->to(['cleanliness.lab@nhkspg.co.th',
            'weerawan.iea@nhkspg.co.th',
            'surasak.hoe@nhkspg.co.th',
            'ekkaparp.jun@nhkspg.co.th',
            'piyachat.mab@nhkspg.co.th',
            'sarocha.man@nhkspg.co.th',
            'narupon@nhkspg.co.th',
            'numfon.sin@nhkspg.co.th',
            'dataqa.dds@nhkspg.co.th',
            ])->subject($info['subject']);
            $message->from('cleanliness.dds@nhkspg.co.th');
        });

    }

    public function mailconfirmgcmsAML($data)
    {

        // AMLGCMS
        $files = $data['file'];
        $zipfiles = $data['zip'];

        // <tr>
        //     <td>AML Link file </td>
        //     <td><a href='http://10.6.100.17/cas_program/local/storage/NHK-File/OQA/JNHK/LPC/" . $files . "'>>>Click Dowload<<</a></td>
        // </tr>
        // <tr>
        //     <td>Zip file</td>
        //     <td><a href='http://10.6.100.17/cas_program/local/storage/NHK-File/OQA/JNHK/LPC/" . $zipfiles . "'>>>Click Dowload<<</a></td>
        // </tr>
        // <p>The document operator has prepared the <b style='color: #1f9eff;'>AML</b> file as detailed below.</p><br>

            $subject = "[CAS program]_Return AML file already updated";
            $body =  "<html><head><body style='font-family: Tahoma;'>
            <p style=' font-size: x-large; font-family: fantasy; color: #1f9eff;'>[Return AML file]</p>
            <p>Return AML file already updated as detail below.</p><br>
            <table border='1' style='border-collapse: collapse;'>
                <tr>
                    <td style=' width: 140px; text-align: -webkit-center; '><b>Item</b></td>
                    <td style=' width: 200px; text-align: -webkit-center; '><b>Detail</b></td>
                </tr>
                <tr>
                    <td>Tester</td>
                    <td>" . $data['testter'] . "</td>
                </tr>
                <tr>
                    <td>Model</td>
                    <td>" . $data['model'] . "</td>
                </tr>
                <tr>
                    <td>Lot number</td>
                    <td>" . $data['lot'] . "</td>
                </tr>
                <tr>
                    <td>Upload date </td>
                    <td>" . $data['date'] . "</td>
                </tr>
            </table>
            <br>
            <p>If you have any questions or concerns, please feel free to contact our Lab Engineer.<br>
            *** This is an automatically generated email, please do not reply ***</p>
            <br>
            Best Regards,<br>
            Cleanliness lab<br>
            QA Section<br>
            Disk Drive Suspension Division<br>
            NHK SPRING (THAILAND) CO.,LTD.<br><b>
            </body>
            </html>
            <br><br><br>";

            $info  = [
                'body' => $body,
                'subject' => $subject,
            ];

            $test = Mail::send(['html' => 'mail'], $info, function ($message) use ($info) {
                $message->to(['weerawan.iea@nhkspg.co.th','dataqa.dds@nhkspg.co.th','ekkaparp.jun@nhkspg.co.th','numfon.sin@nhkspg.co.th','Surasak.hoe@nhkspg.co.th','narupon@nhkspg.co.th','piyachat.mab@nhkspg.co.th','sarocha.man@nhkspg.co.th'])->subject($info['subject']);
                $message->from('cleanliness.dds@nhkspg.co.th');
            });

    }


    public function mailUploadJNHK($data)
    {

        $subject = "[CAS program]_Return JNHK file already updated";
        $body =  "<html><head><body style='font-family: Tahoma;'>
        <p style=' font-size: x-large; font-family: fantasy; color: #1f9eff;'>[Return AML file]</p>
        <p>Return AML file already updated as detail below.</p><br>
        <table border='1' style='border-collapse: collapse;'>
            <tr>
                <td style=' width: 140px; text-align: -webkit-center; '><b>Item</b></td>
                <td style=' width: 200px; text-align: -webkit-center; '><b>Detail</b></td>
            </tr>
            <tr>
                <td>Tester</td>
                <td>" . $data['testter'] . "</td>
            </tr>
            <tr>
                <td>Model</td>
                <td>" . $data['model'] . "</td>
            </tr>
            <tr>
                <td>Lot number</td>
                <td>" . $data['lot'] . "</td>
            </tr>
            <tr>
                <td>Upload date </td>
                <td>" . $data['date'] . "</td>
            </tr>
        </table>
        <br>
        <p>If you have any questions or concerns, please feel free to contact our Lab Engineer.<br>
        *** This is an automatically generated email, please do not reply ***</p>
        <br>
        Best Regards,<br>
        Cleanliness lab<br>
        QA Section<br>
        Disk Drive Suspension Division<br>
        NHK SPRING (THAILAND) CO.,LTD.<br><b>
        </body>
        </html>
        <br><br><br>";

        $info  = [
            'body' => $body,
            'subject' => $subject,
        ];

        // $test = Mail::send(['html' => 'mail'], $info, function ($message) use ($info) {
        //     $message->to(['weerawan.iea@nhkspg.co.th','dataqa.dds@nhkspg.co.th','ekkaparp.jun@nhkspg.co.th','numfon.sin@nhkspg.co.th','Surasak.hoe@nhkspg.co.th','narupon@nhkspg.co.th','piyachat.mab@nhkspg.co.th','sarocha.man@nhkspg.co.th'])->subject($info['subject']);
        //     $message->from('cleanliness.dds@nhkspg.co.th');
        // });

        $test = Mail::send(['html' => 'mail'], $info, function ($message) use ($info) {
            $message->to(['sarocha.man@nhkspg.co.th','piyachat.mab@nhkspg.co.th','narupon@nhkspg.co.th','numfon.sin@nhkspg.co.th'])->subject($info['subject']);
            $message->from('cleanliness.dds@nhkspg.co.th');
        });



    }





}
