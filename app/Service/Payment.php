<?php

namespace App\Service;

class Payment
{
    public static function PaymentCheck($prams){
        if($prams > 0){
            return 'DUE';
        }
        else{
            return 'PAID';
        }
    }
}
