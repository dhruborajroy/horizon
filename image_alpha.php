<?php
require_once 'vendor/autoload.php'; // mPDF
require_once('fpdi/src/autoload.php');
require_once('fpdf_alpha.php'); // <-- use this for opacity control

use setasign\Fpdi\Fpdi;

// Paths
$jsonFile   = 'data/students.json';
$tempPDF    = 'temp_moi.pdf';
$finalPDF   = 'MOI.pdf';

// Load student data
if (!file_exists($jsonFile)) die("data/students.json file not found.");
$students = json_decode(file_get_contents($jsonFile), true);
if ($students === null) die("Invalid JSON data.");

// Create mPDF
$mpdf = new \Mpdf\Mpdf([
    'tempDir' => __DIR__ . '/custom',
    'default_font_size' => 12,
    'default_font' => 'FreeSerif',
    'margin_left' => 20,
    'margin_right' => 20,
    'margin_top' => 10,
    'margin_bottom' => 10,
]);
$mpdf->SetTitle('All MOI - Barisal Engineering College');

// Generate MOI pages
foreach ($students as $index => $student) {
    $batch = str_pad($student['Batch'], 2, "0", STR_PAD_LEFT);
    $reg_no = $student['Reg No'];
    $session = $student['Session'];

    $html = '
    <table width="100%">
        <tr>
            <td align="left" colspan="3" style="font-size:20px">
                <span style="font-size:25px; font-family: Siyam Rupali;">সিভিল ইঞ্জিনিয়ারিং বিভাগ</span><br>
                বরিশাল ইঞ্জিনিয়ারিং কলেজ<br>বরিশাল - ৮২০০, বাংলাদেশ
            </td>
            <td align="center" colspan="1"></td>
            <td align="left" colspan="3" style="font-size:20px">
                Department of Civil Engineering<br>Barisal Engineering College<br>Barisal-8200, Bangladesh
            </td>
        </tr>
        <tr><td colspan="7"><hr></td></tr>
        <tr> 
            <td align="left" colspan="4" style="padding-top:20px">
                <strong>Ref No: BEC/CE/MOI/' . $batch . '/' . $reg_no . '</strong>
            </td>
            <td align="right" colspan="3" style="padding-top:20px">
                <strong>Date: ' . date('d M Y') . '</strong>
            </td>
        </tr>
        <tr>
            <td colspan="7" align="center" style="padding-top:50px">
                <u style="font-size:35px">Certificate of Medium of Instruction</u>
            </td>
        </tr>
        <tr>
           <td colspan="7" style="text-align: justify; padding-top: 30px; font-size: 20px; line-height: 1.8;">
                <p style="margin-bottom: 15px;">
                    This is to certify that <strong>' . htmlspecialchars($student['Name']) . '</strong>, bearing Reg. No. <strong>' . htmlspecialchars($reg_no) . '</strong>, 
                    Session <strong>' . htmlspecialchars($session) . '</strong>, was a student of Barisal Engineering College, a constituent engineering college of the <strong>University of Dhaka</strong>, 
                    in the Department of Civil Engineering.
                </p>
                <p>
                    The medium of instruction and examination in this college is <strong>English</strong>.
                </p>
            </td>
        </tr>
        <tr>
            <td style="padding-top:50px;font-size:20px">Yours Sincerely,</td>
        </tr>
        <tr>
            <td align="left" colspan="5" style="padding-top:90px">
                <span style="font-size:20px;text-align:center">
                    Fatema Ferdoush Keya <br>
                    Head of the Department <br>
                    Department of Civil Engineering<br>
                    Barisal Engineering College <br>Barisal, Bangladesh.<br>
                </span>
            </td>
        </tr>
    </table>
    ';

    if ($index > 0) $mpdf->AddPage();
    $mpdf->WriteHTML($html);
}

// Save temporary PDF
$mpdf->Output($tempPDF, "F");

// Open with FPDI to add watermark
$pdf = new FPDF_Alpha();
$pageCount = $pdf->setSourceFile($tempPDF);

for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
    $tplIdx = $pdf->importPage($pageNo);
    $size   = $pdf->getTemplateSize($tplIdx);

    $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
    $pdf->useTemplate($tplIdx);

    // 🔹 Add semi-transparent image watermark
    $pdf->SetAlpha(0.15); // 15% opacity
    $pdf->Image('logo/du-bec.png', 30, 60, 150); // adjust x, y, width
    $pdf->SetAlpha(1); // reset opacity

    // Optional: add background image (page frame)
    $pdf->Image('logo/eee_moi-page-001.jpg', 0, 0, 210);
}

// Output final PDF
$pdf->Output($finalPDF, "I");

// Delete temp PDF
unlink($tempPDF);

?>
