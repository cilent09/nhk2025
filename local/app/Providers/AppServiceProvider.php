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
