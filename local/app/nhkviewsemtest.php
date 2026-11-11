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

class nhkviewsemtest
{
      static function semtest($mailidcreatelab,$mailidmodel)
      {
        $oqalot = DB::table('nt_data_semtest')
        ->where('id_createlab',$mailidcreatelab)
        ->first();
        $ck = $oqalot->semtest_data;
       // $ck = 10;
        if($ck == 1)
        {
          $returndata =0;
        }
        else
        {
          $returndata =1;
        }
        return  $returndata;
      }


}
