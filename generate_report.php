<?php
require('fpdf.php');
require('MyDB.php');
$db = new MyDB();
class PDF extends FPDF
{
    // Load Cairo font
    function LoadCairoFont()
    {
        $this->AddFont('Cairo', '', 'Cairo-Regular.php','./font');
        // Add other styles as needed (bold, italic, etc.)
    }

    // Simple table with custom font
    function BasicTable($header, $data)
    {
        // Load Cairo font
        $this->LoadCairoFont();

        // Set font
        $this->SetFont('Cairo', '', 12);

        // Header
        foreach($header as $col)
            $this->Cell(40,7,$col,1);
        $this->Ln();

        // Data
        foreach($data as $row)
        {
            foreach($row as $col)
                $this->Cell(40,6,$col,1);
            $this->Ln();
        }
    }
}

// Your database query to get data
$Students = $db->getApprovedAttendances();

// Header for the table
$header = array('Status', 'Student', 'Teacher', 'Date', 'Enter', 'Exit', 'Duration', 'Price');

// Generate PDF
$pdf = new PDF();
$pdf->AddPage();
$pdf->BasicTable($header, $Students);
$pdf->Output();
?>
