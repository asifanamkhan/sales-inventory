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
        if($route == 'supplier-ledger-pdf'){
            return 'SUPPLIER LEDGER';
        }
        if($route == 'customer-ledger-pdf'){
            return 'CUSTOMER LEDGER';
        }
        if($route == 'product-list-reports'){
            return 'PRODUCTS LIST';
        }
        if($route == 'product-purchase-report-pdf'){
            return 'PRODUCT PURCHASE';
        }
        if($route == 'product-purchase-return-report-pdf'){
            return 'PRODUCT PR RETURN';
        }
        if($route == 'product-stock-report-pdf'){
            return 'PRODUCT STOCK REPORT';
        }
        if($route == 'product-stock-out-report-pdf'){
            return 'PRODUCT STOCK OUT REPORT';
        }
        if($route == 'product-damage-report-pdf'){
            return 'PRODUCT DAMAGE REPORT';
        }
        if($route == 'product-expire-report-pdf'){
            return 'PRODUCT EXPIRE REPORT';
        }
        if($route == 'product-sale-report-pdf'){
            return 'PRODUCT SALE REPORT';
        }
    }


}