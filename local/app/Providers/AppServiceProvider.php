<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // PHP 7.4+: ไลบรารีเก่า (phpoffice/phpexcel ที่มากับ maatwebsite/excel 2.1)
        // ใช้ syntax $str{0} ซึ่งเป็น deprecated และ Laravel 5.6 แปลง deprecation ทุกตัวเป็น ErrorException
        // จึงปิดการรายงาน E_DEPRECATED / E_USER_DEPRECATED ไม่ให้ทำให้ request ล้ม
        error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);

        // PHP 7.4+: PHPExcel เข้าถึง offset บนค่าที่ไม่ใช่ array/string (เช่น $int{0}, $null['t'])
        // ซึ่ง PHP 7.4 แจ้ง notice "Trying to access array offset on value of type ..." (PHP เก่าไม่แจ้ง และได้ค่า null เหมือนกัน)
        // Laravel แปลง notice เป็น ErrorException ทำให้ export Excel ล้ม (เช่น /test__raw_exportexcel)
        // จึงข้ามเฉพาะ notice ชนิดนี้ที่เกิดในไฟล์ของ vendor/phpoffice/phpexcel เท่านั้น error อื่นส่งต่อให้ Laravel ตามเดิม
        $previousHandler = set_error_handler(null);
        set_error_handler(function ($level, $message, $file = '', $line = 0, $context = []) use ($previousHandler) {
            if (($level & (E_NOTICE | E_WARNING))
                && strpos($message, 'Trying to access array offset on value of type') === 0
                && strpos(str_replace('\\', '/', $file), '/vendor/phpoffice/phpexcel/') !== false
            ) {
                return true;
            }

            return $previousHandler ? call_user_func($previousHandler, $level, $message, $file, $line, $context) : false;
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
