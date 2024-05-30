<?php
require('fpdf.php');

class PDF extends FPDF {
    function Header(){
        // Add header content here
        $this->SetFont('Arial','B',12);
        $this->Cell(0,10,'Attendance Report',0,1,'C');
        $this->Ln(10);
    }

    function Footer(){
        // Add footer content here
        $this->SetY(-15);
        $this->SetFont('Arial','I',8);
        $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
    }


    function Content($data){
        // Add content here
        $this->SetFont('Arial','',10);
        foreach($data as $row) {
            $this->Cell(40,10,$row['student_name'],1,0);
            $this->Cell(40,10,$row['teacher_name'],1,0);
            $this->Cell(40,10,$row['date'],1,1);
        }
    }
}

// Sample data
$data = array(
    array('student_name' => 'John Doe', 'teacher_name' => 'Jane Smith', 'date' => '2024-04-30'),
    array('student_name' => 'Alice', 'teacher_name' => 'Bob', 'date' => '2024-05-01')
);

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->Content($data);
$pdf->Output();
?>
