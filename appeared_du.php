<?php

require_once 'vendor/autoload.php'; // Adjust path to your autoload.php

// Path to JSON file
$jsonFile = 'data/students.json';

// Output file path
$outputPath = 'testimonial.pdf';

if (!file_exists($jsonFile)) {
    die("students.json file not found.");
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

$mpdf->SetTitle('All Testimonials - Barisal Engineering College');

foreach ($students as $index => $student) {
    $batch = str_pad($student['Batch'], 2, "0", STR_PAD_LEFT);
    $reg_no = $student['Reg No'];
    $session = $student['Session'];

    $gender_text1="He";
    $gender_text2="his";
    $gender_text3="him";
    $gender=$student['Gender'];
    if($gender=="Female"){
        $gender_text1="She";
        $gender_text2="her";
        $gender_text3="her";
    }
    
    $html = '
    <table width="100%">
        <tr>
            <td align="left" colspan="3" style="font-size:20px">
                <span style="font-size:25px; font-family: Siyam Rupali;">সিভিল ইঞ্জিনিয়ারিং বিভাগ</span><br>
                বরিশাল ইঞ্জিনিয়ারিং কলেজ<br>বরিশাল - ৮২০০, বাংলাদেশ
            </td>
            <td align="center">
            </td>
            <td align="left" colspan="3" style="font-size:20px">
                Department of Civil Engineering<br>Barisal Engineering College<br>Barisal-8200, Bangladesh
            </td>
        </tr>
        <tr><td colspan="7"><hr></td></tr>
        <tr>
            <td align="left" colspan="4">
                <strong>Ref No: BEC/CE/Appeared/' . $batch . '/' . $reg_no . '</strong>
            </td>
            <td align="right" colspan="3">
                <strong>Date: ' . date('d M Y') . '</strong>
            </td>
        </tr>
        <tr>
            <td colspan="7" align="center" style="padding-top:50px">
                <u style="font-size:35px">TO WHOM IT MAY CONCERN</u>
            </td>
        </tr>
        <tr>
            <td colspan="7" style="text-align: justify; padding-top: 30px; font-size: 20px; line-height: 1.8;">
                <p style="margin-bottom: 15px;">
                    This is to certify that <strong>' . htmlspecialchars($student['Name']) . '</strong>, bearing Reg. No. <strong>' . htmlspecialchars($reg_no) . '</strong>, 
                    Session <strong>' . htmlspecialchars($session) . '</strong>, has appeared in final examination to obtain the <strong>B.Sc. in Engineering</strong> Degree from the Department of Civil Engineering, Barisal Engineering College, Barisal under the affiliation of  <strong>University of Dhaka </strong>. The result will be published soon.
                </p>
                <p style="font-size: 20px;">
                    ' . $gender_text1 . ' bears good moral character and has not taken part in any activity subversive to the state
                    or discipline.
                </p>
                <p style="font-size: 20px;">
                    I wish ' . $gender_text3 . ' every success in ' . $gender_text2 . ' future endeavors.
                </p>
            </td>
        </tr>
        <tr>
            <td style="padding-top:50px;font-size:20px">
                Yours Sincerely,
            </td>
        </tr>
        <tr>
            <td align="left" colspan="5" style="padding-top:90px">
                <span style="font-style:30px">
                    <br>
                    <span style="font-size:20px;text-align:center">
                        Fatema Ferdoush Keya <br>
                        Head of the Department <br>
                        Department of Civil Engineering<br>
                        Barisal Engineering College <br>Barisal, Bangladesh.<br>
                        Cell: +8801521451895<br>
                        Email: keya.duengg@bec.edu.bd
                    </span>
                    <br>
                </span>
            </td>
        </tr>
    </table>
    ';

    // Add page break except before first page
    if ($index > 0) {
        $mpdf->AddPage();
    }

    $mpdf->WriteHTML($html);
}

$mpdf->Output($outputPath, "F");

// adding logo

require_once('fpdf.php');
require_once('fpdi/src/autoload.php');

use setasign\Fpdi\Fpdi;

$pdf = new FPDI();

// Path to existing PDF
$pageCount = $pdf->setSourceFile('testimonial.pdf');

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
        $pdf->Image('logo/du_bec.png', 77, 12, 45); // x, y, width height
        // $pdf->Image('Dhaka_University_logo.png', 120, 12, 40); // x, y, width
    // }
}

// Output modified PDF
// $pdf->Output('F', 'output.pdf');

// $file_path=$i.".jpg";
$file_path_pdf="Appeared.pdf";
$pdf->Output($file_path_pdf,"I");
unlink($outputPath);


?>
