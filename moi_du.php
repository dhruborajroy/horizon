<?php

require_once 'vendor/autoload.php'; // Adjust path to your autoload.php

// Path to JSON file
$jsonFile = 'data/students.json';

// Output file path
$outputPath = 'moi.pdf';

if (!file_exists($jsonFile)) {
    die("data/students.json file not found.");
}

$jsonData = file_get_contents($jsonFile);
$students = json_decode($jsonData, true);

if ($students === null) {
    die("Invalid JSON data.");
}

// Create a single mPDF instance
$mpdf = new \Mpdf\Mpdf([
    'tempDir' => __DIR__ . '/custom',
    'default_font_size' => 12,
    'default_font' => 'FreeSerif',
    'margin_left' => 20,
    'margin_right' => 20,
    'margin_top' => 10,
    'margin_bottom' => 10,
]);

$mpdf->SetTitle('All MOI - Barishal Engineering College');

foreach ($students as $index => $student) {
    $i=01;
    $batch =03;//$batch = str_pad($student['Batch'], 2, "0", STR_PAD_LEFT);
    $reg_no = $student['Reg No'];
    $session = $student['Session'];
    $i=str_pad($i, 2, "0", STR_PAD_LEFT);
    $html = '
    <table width="100%">
        <tr>
            <td align="left" colspan="3" style="font-size:20px">
                <span style="font-size:25px; font-family: Siyam Rupali;">সিভিল ইঞ্জিনিয়ারিং বিভাগ</span><br>
                বরিশাল ইঞ্জিনিয়ারিং কলেজ<br>বরিশাল - ৮২০০, বাংলাদেশ
            </td>
            <td align="center" colspan="1">

            </td>
            <td align="left" colspan="3" style="font-size:20px">
                Department of Civil Engineering<br>Barishal Engineering College<br>Barishal-8200, Bangladesh
            </td>
        </tr>
        <tr><td colspan="7"><hr></td></tr>
        <tr> 
            <td align="left" colspan="4" style="padding-top:50px">
                <strong>Ref No: ' . $batch . '1920-MOI/' . $i . '</strong>
            </td>
            <td align="right" colspan="3" style="padding-top:50px">
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
                    Session <strong>' . htmlspecialchars($session) . '</strong>, was a student of Barishal Engineering College, a constituent engineering college of the <strong>University of Dhaka</strong>, 
                    in the Department of Electrical & Electronic Engineering.
                </p>
                <p>
                    The medium of instruction and examination in this college is <strong>English</strong>.
                </p>
            </td>
        </tr>
        <tr>
            <td  align="center" colspan="3"> 
            </td>
            <td  align="center" style="padding-top:100px" colspan="4">
                <div align="right">
                    <span style="font-style:30px">
                        <br>
                            <span style="font-size:20px">Issuing Authority</span>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        <hr>
                            <span style="font-size:20px">Head of The Department<br>
        Dept. of Electrical & Electronic Engineering<br>
    Barishal Engineering College, Barishal-8200
    </span>
                        <br>
                    </span>
                </div>
            </td>
        </tr>
    </table>
    ';

    $i++;
    // Add page break except before first page
    if ($index > 0) {
        $mpdf->AddPage();
    }

    
    $width_mm = 10 * 0.2646;  // ≈ 2.646 mm
    $height_mm = 10 * 0.2646; // ≈ 2.646 mm

    $mpdf->SetWatermarkImage('logo/du.png', 0.1, '', [$width_mm, $height_mm]);
    $mpdf->showWatermarkImage = true;

    $mpdf->WriteHTML($html);
}

// Output combined PDF to file
// $mpdf->Output($outputPath, \Mpdf\Output\Destination::FILE);
$mpdf->Output($outputPath, "F");


require_once('fpdf.php');
require_once('fpdi/src/autoload.php');

use setasign\Fpdi\Fpdi;

$pdf = new FPDI();

// Path to existing PDF
$pageCount = $pdf->setSourceFile($outputPath);

// Loop through all pages
for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
    $tplIdx = $pdf->importPage($pageNo);
    $size = $pdf->getTemplateSize($tplIdx);

    // Add page with same orientation and size
    $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);

    // Use the imported page as background
    $pdf->useTemplate($tplIdx);

    // Add image only on the first page (example)
    // if ($pageNo == 1) {
        $pdf->Image('logo/eee_moi-page-001.jpg', 0, 0, 210); // x, y, width
        // $pdf->Image('du_bec.png', 77, 12, 50); // x, y, width height

        // $pdf->Image('logo/du-bec.png', 30, 40, 150); 

        // $pdf->Image('Dhaka_University_logo.png', 120, 12, 40); // x, y, width
    // }
}

// Output modified PDF
// $pdf->Output('F', 'output.pdf');

        // $file_path=$i.".jpg";
$file_path_pdf="MOI.pdf";
$pdf->Output($file_path_pdf,"I");
unlink($outputPath);

?>
