<?php

namespace App\Service;

class ReportTypeCheck
{
    public static function route($route){
        if($route == 'customer-info-reports'){
            return 'CUSTOMER INFO';
        }
        if($route == 'supplier-info-reports'){
            return 'SUPPLIER INFO';
        }
    }


}