<?php

namespace App\Http\Controllers;

use TCPDF;

class CustomPDF extends TCPDF
{
    // Page footer
    public function Footer()
    {
        // Set position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $pageText = 'Page ' . $this->getAliasNumPage() . ' of ' . $this->getAliasNbPages();
        // Print the page number
        $this->Cell(0, 10, $pageText, 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}