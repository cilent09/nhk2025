<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class slope
{
      static function linearreg_slope( $valuesIn, $period )
        {
        $valuesOut = array();

        $startIdx = 0;
        $endIdx = count($valuesIn) - 1;

        $sumX = $period * ( $period - 1 ) * 0.5;
        $sumXSqr = $period * ( $period - 1 ) * ( 2 * $period - 1 ) / 6;
        $divisor = $sumX * $sumX - $period * $sumXSqr;

        for ( $today = $startIdx, $outIdx = 0; $today <= $endIdx; $today++, $outIdx++ )
        {
        $sumXY = 0;
        $sumY = 0;
        if ( $today >= $period - 1 ) {
            for( $aux = $period; $aux-- != 0; )
            {
            $sumY += $tempValue = $valuesIn[$today - $aux];
            $sumXY += (double)$aux * $tempValue;
            }
            $valuesOut[$outIdx] = ( $period * $sumXY - $sumX * $sumY) / $divisor;
        }
        }
        if($valuesOut[6] < 0)
        {
             $returndata = abs($valuesOut[6]);
        }
        else if($valuesOut[6] > 0)
        {
             $returndata = $valuesOut[6] * -1;
        }
        else
        {
          $returndata = $valuesOut[6];
        }
        return $returndata;
        }


        static function nhk_formatnumber($value,$decimal)
        {

             if(is_numeric($value) == true)
             {
               $data = number_format($value, $decimal, '.', '');     
             }
             else
             {
               $data = $value;
                    
             }
             return $data;
        }

}
