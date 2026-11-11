<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class nhkmail
{
      static function mailconfirm($data)
      {
        //dd($data);
        // "weerawan.iea@nhkspg.co.th,dataqa.dds@nhkspg.co.th, ekkaparp.jun@nhkspg.co.th,numfon.sin@nhkspg.co.th,Surasak.hoe@nhkspg.co.th,narupon@nhkspg.co.th,piyachat.mab@nhkspg.co.th,sarocha.man@nhkspg.co.th"
        $toemail = "cleanliness.lab@nhkspg.co.th,weerawan.iea@nhkspg.co.th,dataqa.dds@nhkspg.co.th, ekkaparp.jun@nhkspg.co.th,numfon.sin@nhkspg.co.th,Surasak.hoe@nhkspg.co.th,narupon@nhkspg.co.th,piyachat.mab@nhkspg.co.th,sarocha.man@nhkspg.co.th";
        //$toemail ="jakkeejansang0819@gmail.com";
        $subject = $data['subject'];    
        $body =  "<html><head><body>
        Dear all,<br><br><br><br>
        ตรวจพบ Cleanliness ตามรายละเอียดด้านล่าง<br>
        Tester : ".$data['testter']."<br>
        Model : ".$data['model']."<br>
        Lot number : <b>".$data['lot']."</b><br>
        Upload date : ".$data['date']."<br>
        <br><br>
        <b><p style='color: #0000CD'>LAB tester ได้ Update ข้อมูลและ confirm data เรียบร้อย</p> <br>
        <br><br>
        Best Regards,<br>
        Cleanliness lab<br>
        QA Section<br>
        Disk Drive Suspension Division<br>
        NHK SPRING (THAILAND) CO.,LTD.<br><b>
        </body>
        </html>
        <br><br><br>
        ";
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        if (mail($toemail, $subject, $body, $headers)) {
         
          $returndata = "success";
         
        } else {
         
          $returndata = "failed";
         
        }
        return $returndata;
        
      }
 
      static function mailalarm($data)
      {
        // "weerawan.iea@nhkspg.co.th,dataqa.dds@nhkspg.co.th,ekkaparp.jun@nhkspg.co.th,numfon.sin@nhkspg.co.th,Surasak.hoe@nhkspg.co.th,narupon@nhkspg.co.th,piyachat.mab@nhkspg.co.th,sarocha.man@nhkspg.co.th"
       $toemail = "cleanliness.lab@nhkspg.co.th,weerawan.iea@nhkspg.co.th,dataqa.dds@nhkspg.co.th,ekkaparp.jun@nhkspg.co.th,numfon.sin@nhkspg.co.th,Surasak.hoe@nhkspg.co.th,narupon@nhkspg.co.th,piyachat.mab@nhkspg.co.th,sarocha.man@nhkspg.co.th,kittana.jar@nhkspg.co.th";
        // $toemail ="jakkeejansang0819@gmail.com";
        $subject = "[CAS program]_Cleanliness data NG!";    
        $body =  "<html><head><body>
        Dear all,<br><br><br><br>
        ตรวจพบ Cleanliness <b><span style='color: red'>data NG! </span></b> ตามรายละเอียดด้านล่าง<br>
        Tester : ".$data['testter']."<br>
        Model : ".$data['model']."<br>
        Lot number : <b>".$data['lot']."</b><br>
        Upload date : ".$data['date']."<br>
        <p style='color: red'>Alarm detail : ".$data['alarm']." &nbsp;&nbsp;&nbsp;<a href='http://10.6.100.17/cas_program/output/viewdata/".$data['idqla']."'>>>ลิงค์ Alarm point<<</a></p>
        <br><br>
        <br><br>
        Best Regards,<br>
        Cleanliness lab<br>
        QA Section<br>
        Disk Drive Suspension Division<br>
        NHK SPRING (THAILAND) CO.,LTD.<br><b>
        </body>
        </html>
        <br><br><br>
        ";
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        if (mail($toemail, $subject, $body, $headers)) {
         
          $returndata = "success";
         
        } else {
         
          $returndata = "failed";
         
        }
        return $returndata;
      }

      static function mailalarmstaff($data)
      {
        // "weerawan.iea@nhkspg.co.th,dataqa.dds@nhkspg.co.th,ekkaparp.jun@nhkspg.co.th,numfon.sin@nhkspg.co.th,Surasak.hoe@nhkspg.co.th,narupon@nhkspg.co.th,piyachat.mab@nhkspg.co.th,sarocha.man@nhkspg.co.th"
        $toemail = "cleanliness.lab@nhkspg.co.th,weerawan.iea@nhkspg.co.th,dataqa.dds@nhkspg.co.th,ekkaparp.jun@nhkspg.co.th,numfon.sin@nhkspg.co.th,Surasak.hoe@nhkspg.co.th,narupon@nhkspg.co.th,piyachat.mab@nhkspg.co.th,sarocha.man@nhkspg.co.th,kittana.jar@nhkspg.co.th";
        //$toemail ="jakkee@ots.co.th";
        $subject = "[CAS program]_Cleanliness data NG! (AFTER RETEST)";    
        $body =  "<html><head><body>
        Dear all,<br><br><br><br>
        ตรวจพบ Cleanliness <b><span style='color: red'>data NG! หลังจาก retest  </span></b> ตามรายละเอียดด้านล่าง<br>
        Tester : ".$data['testter']."<br>
        Model : ".$data['model']."<br>
        Lot number : <b>".$data['lot']."</b><br>
        Upload date : ".$data['date']."<br>
        <p style='color: red'>Alarm detail : ".$data['alarm']." &nbsp;&nbsp;&nbsp;<a href='http://10.6.100.17/cas_program/output/viewdata/".$data['idqla']."'>>>ลิงค์ Alarm point<<</a></p>
        <br>
        <b><p style='color: #0000CD'> >> Staff review data ด้วยค่ะ << </p></b> <br>
        <br><br>
        Best Regards,<br>
        Cleanliness lab<br>
        QA Section<br>
        Disk Drive Suspension Division<br>
        NHK SPRING (THAILAND) CO.,LTD.<br><b>
        </body>
        </html>
        <br><br><br>
        ";
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        if (mail($toemail, $subject, $body, $headers)) {
         
          $returndata = "success";
         
        } else {
         
          $returndata = "failed";
         
        }
        return $returndata;
      }


      static function mailalarmsemtest($data)
      {
    
        // "weerawan.iea@nhkspg.co.th,dataqa.dds@nhkspg.co.th,ekkaparp.jun@nhkspg.co.th,numfon.sin@nhkspg.co.th,Surasak.hoe@nhkspg.co.th,narupon@nhkspg.co.th,piyachat.mab@nhkspg.co.th,sarocha.man@nhkspg.co.th"
        $toemail = "cleanliness.lab@nhkspg.co.th,weerawan.iea@nhkspg.co.th,dataqa.dds@nhkspg.co.th,ekkaparp.jun@nhkspg.co.th,numfon.sin@nhkspg.co.th,Surasak.hoe@nhkspg.co.th,narupon@nhkspg.co.th,piyachat.mab@nhkspg.co.th,sarocha.man@nhkspg.co.th";
        //$toemail ="jakkee@ots.co.th";
        $subject = "[CAS program]_Cleanliness data NG!
        ";    
        $body =  "<html><head><body>
        Dear all,<br><br><br><br>
        ตรวจพบ Cleanliness <b><span style='color: red'>data NG! </span></b> ตามรายละเอียดด้านล่าง<br>
        Tester : ".$data['testter']."<br>
        Model : ".$data['model']."<br>
        Lot number : <b>".$data['lot']."</b><br>
        Upload date : ".$data['date']."<br>
        <p style='color: red'>Alarm detail : ".$data['alarm']." &nbsp;&nbsp;&nbsp;<a href='http://10.6.100.17/cas_program/output/results'>>>ลิงค์ Alarm point<<</a></p>
        <br><br>
        <br><br>
        Best Regards,<br>
        Cleanliness lab<br>
        QA Section<br>
        Disk Drive Suspension Division<br>
        NHK SPRING (THAILAND) CO.,LTD.<br><b>
        </body>
        </html>
        <br><br><br>
        ";
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        if (mail($toemail, $subject, $body, $headers)) {
         
          $returndata = "success";
         
        } else {
         
          $returndata = "failed";
         
        }
        return $returndata;
      }

      static function mailalarmstaffsemtest($data)
      {
        
        // "weerawan.iea@nhkspg.co.th,dataqa.dds@nhkspg.co.th,ekkaparp.jun@nhkspg.co.th,numfon.sin@nhkspg.co.th,Surasak.hoe@nhkspg.co.th,narupon@nhkspg.co.th,piyachat.mab@nhkspg.co.th,sarocha.man@nhkspg.co.th"
        $toemail = "cleanliness.lab@nhkspg.co.th,weerawan.iea@nhkspg.co.th,dataqa.dds@nhkspg.co.th,ekkaparp.jun@nhkspg.co.th,numfon.sin@nhkspg.co.th,Surasak.hoe@nhkspg.co.th,narupon@nhkspg.co.th,piyachat.mab@nhkspg.co.th,sarocha.man@nhkspg.co.th";
        //$toemail ="jakkee@ots.co.th";
        $subject = "[CAS program]_Cleanliness data NG! (AFTER RETEST)";    
        $body =  "<html><head><body>
        Dear all,<br><br><br><br>
        ตรวจพบ Cleanliness <b><span style='color: red'>data NG! หลังจาก retest  </span></b> ตามรายละเอียดด้านล่าง<br>
        Tester : ".$data['testter']."<br>
        Model : ".$data['model']."<br>
        Lot number : <b>".$data['lot']."</b><br>
        Upload date : ".$data['date']."<br>
        <p style='color: red'>Alarm detail : ".$data['alarm']." &nbsp;&nbsp;&nbsp;<a href='http://10.6.100.17/cas_program/output/results'>>>ลิงค์ Alarm point<<</a></p>
        <br>
        <b><p style='color: #0000CD'> >> Staff review data ด้วยค่ะ << </p></b> <br>
        <br><br>
        Best Regards,<br>
        Cleanliness lab<br>
        QA Section<br>
        Disk Drive Suspension Division<br>
        NHK SPRING (THAILAND) CO.,LTD.<br><b>
        </body>
        </html>
        <br><br><br>
        ";
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        if (mail($toemail, $subject, $body, $headers)) {
         
          $returndata = "success";
         
        } else {
         
          $returndata = "failed";
         
        }
        return $returndata;
      }


      static function maileditdata($data)
      {
        // "dataqa.dds@nhkspg.co.th,Surasak.hoe@nhkspg.co.th,narupon@nhkspg.co.th,piyachat.mab@nhkspg.co.th,sarocha.man@nhkspg.co.th"
        $toemail = "dataqa.dds@nhkspg.co.th,Surasak.hoe@nhkspg.co.th,narupon@nhkspg.co.th,piyachat.mab@nhkspg.co.th,numfon.sin@nhkspg.co.th,sarocha.man@nhkspg.co.th";
       // $toemail ="jakkee@ots.co.th";
        $subject = "[CAS program]_Staff already REVIEW data";    
        $body =  "<html><head><body>
        Dear all,<br><br><br><br>
        Staff ทำการ <b><span style='color: #0000CD'> review data </span></b> ที่มีการ alarm แล้ว ตามรายละเอียดด้านล่าง<br>
        Tester : ".$data['testter']."<br>
        Model : ".$data['model']."<br>
        Lot number : <b>".$data['lot']."</b><br>
        Review date  : ".$data['date']."<br>
        <br><br>
        <br><br>
        Best Regards,<br>
        Cleanliness lab<br>
        QA Section<br>
        Disk Drive Suspension Division<br>
        NHK SPRING (THAILAND) CO.,LTD.<br><b>
        </body>
        </html>
        <br><br><br>
        ";
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        if (mail($toemail, $subject, $body, $headers)) {
         
          $returndata = "success";
         
        } else {
         
          $returndata = "failed";
         
        }
        return $returndata;
      }

      static function mailccp($data)
      {
        // "dataqa.dds@nhkspg.co.th,Surasak.hoe@nhkspg.co.th,narupon@nhkspg.co.th,piyachat.mab@nhkspg.co.th,sarocha.man@nhkspg.co.th"
        $toemail = "dataqa.dds@nhkspg.co.th,Surasak.hoe@nhkspg.co.th,narupon@nhkspg.co.th,piyachat.mab@nhkspg.co.th,numfon.sin@nhkspg.co.th,sarocha.man@nhkspg.co.th";
        //$toemail ="jakkee@ots.co.th";
        $subject = "[CAS program]_Document team already EXPORT CCP FILE";    
        $body =  "<html><head><body>
        Dear all,<br><br><br><br>
        Document team ทำการ <b><span style='color: #29c23a'>  export CCP file </span></b> เรียบร้อยแล้ว ตามรายละเอียดด้านล่าง<br>
        Tester : ".$data['testter']."<br>
        Model : ".$data['model']."<br>
        Lot number : <b>".$data['lot']."</b><br>
        Export date : ".$data['date']."<br>
        <br><br>
        <br><br>
        Best Regards,<br>
        Cleanliness lab<br>
        QA Section<br>
        Disk Drive Suspension Division<br>
        NHK SPRING (THAILAND) CO.,LTD.<br><b>
        </body>
        </html>
        <br><br><br>
        ";
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        if (mail($toemail, $subject, $body, $headers)) {
         
          $returndata = "success";
         
        } else {
         
          $returndata = "failed";
         
        }
        return $returndata;
      }
      





}
