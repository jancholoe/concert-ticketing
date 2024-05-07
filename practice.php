<?php
require_once('tcpdf/tcpdf.php');

function createTicketLayout($pdf, $concertDetails, $seatType) {
    $pdf->SetFont('times', 'B', 12);
    $x = $pdf->GetX();
    $y = $pdf->GetY();
    $boxWidth = 150; // Adjust the width of the rectangle
    $pdf->Rect($x + (190 - $boxWidth) / 2, $y, $boxWidth, 80); // Center the box on the page and adjust the height

    // Center the text inside the box
    $pdf->SetXY($x + (190 - $boxWidth) / 2 + 5, $y + 5);

    // Move "KRK Tickets" text to the left side
    $pdf->SetXY($x + 5, $y + 5);

    $pdf->Cell(0, 8, 'KRK Tickets', 0, 1, 'C');
    $pdf->SetFont('times', '', 12); // Set font to regular
    $pdf->Cell(0, 8, '----------------------------------------------------------------------------------------------------------', 0, 1, 'C'); // Line
    $pdf->Cell(0, 10, $concertDetails['concert_name'], 0, 1, 'C');
    $pdf->SetFont('times', 'B', 12); // Set font back to bold
    $pdf->Cell(0, 8, 'Concert', 0, 1, 'C');
    $pdf->SetFont('times', '', 12); // Set font to regular
    $pdf->Cell(0, 8, '--------------------------------------', 0, 1, 'C'); // Line
    $pdf->Cell(0, 8, $concertDetails['schedule'] . '  ' . $concertDetails['time'], 0, 1, 'C');
    $pdf->Cell(0, 8, '--------------------------------------', 0, 1, 'C'); // Line
    $pdf->SetFont('times', 'B', 12); // Set font back to bold
    $pdf->Cell(0, 10, 'Seat Type: ' . $seatType, 0, 1, 'C');
}

// Create a PDF instance
$pdf = new TCPDF();

// Set font
$pdf->SetFont('times', 'B', 12);

// Fetch concert details (replace this with your actual data)
$concertDetails = array(
    'concert_name' => 'Sample Concert',
    'time' => '12:00 PM',
    'schedule' => '2023-01-01',
);

// Add VIP ticket page
$pdf->AddPage();
createTicketLayout($pdf, $concertDetails, 'VIP');

// Add Upper Box ticket page
$pdf->AddPage();
createTicketLayout($pdf, $concertDetails, 'Upper Box');

// Add Lower Box ticket page
$pdf->AddPage();
createTicketLayout($pdf, $concertDetails, 'Lower Box');

// Add General Admission ticket page
$pdf->AddPage();
createTicketLayout($pdf, $concertDetails, 'General Admission');

// Output the PDF to the browser
$pdf->Output('tickets.pdf', 'I');
?>
