<?php

require_once 'vendor/autoload.php'; // Adjust path to your autoload.php

// Path to JSON file
$jsonFile = 'data/students_eee.json';

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
    // $batch = str_pad($student['Batch'], 2, "0", STR_PAD_LEFT);
    $batch="";
    $reg_no = $student['Reg No'];
    // $reg_no="";
    $session = $student['Session'];

    $gender_text1="He";
    $gender_text2="his";
    $gender_text3="him";
    $gender=$student['Gender'];
    $gender_tes="Son";
    if($gender=="Female"){
        $gender_text1="She";
        $gender_text2="her";
        $gender_text3="her";
        $gender_tes="Daughter";
    }
    
    $html = '
    <table width="100%">
        <tr>
            <td align="left" colspan="3" style="font-size:20px">
            <span style="font-size:23px; font-family: Siyam Rupali;">ইলেকট্রিক্যাল 
            </td>
            <td align="center" colspan="1">

            </td>
            <td align="left" colspan="3" style="font-size:20px">
                Department of Electrical 
            </td>
        </tr>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <tr>
            <td align="left" colspan="4" style="font-size:18px">
                Ref No: BEC/EEE/2025/4Y2S/T/……
            </td>
            <td align="right" colspan="3" style="font-size:18px">
                Date: 28-08-2025
            </td>
        </tr>
        <tr>
            <td colspan="7" align="center" style="padding-top:50px">
                <u style="font-size:24px"><strong>Testimonial</strong></u>
            </td>
        </tr>
        <tr>
            <td colspan="7" style="text-align: justify; padding-top: 30px; font-size: 20px; line-height: 1.8;">
                <p style="margin-bottom: 15px;">
                    This is to certify that <strong>' . $student['Name'] . ',</strong>  ' . $gender_tes. ' of <strong>' . $student['Father\'s Name'] . '</strong> and  <strong>' . $student['Mother\'s Name'] . '</strong>, Class Roll No:  <strong>' . $student['Student ID (19xxxxxxx)'] . '</strong>, 
                    Registration No: <strong>' . htmlspecialchars($reg_no) . '</strong>, Session: <strong>' . htmlspecialchars($session) . '</strong>, completed ' . $gender_text2 . ' 4 (four) years (8 semesters) 
                    Bachelor of Science in Electrical and Electronic Engineering from the department of Electrical 
                    and Electronic Engineering (EEE) of Barishal Engineering College under the University of Dhaka.
                    
                    
                </p>
                <p style="font-size: 20px;padding-top:20px">
                    During ' . $gender_text2 . ' stay in this college, there is nothing on record against ' . $gender_text2 . ' character on college discipline.
                    
                    
                </p>
                <p style="font-size: 20px;">
                    I wish ' . $gender_text2 . ' all the success in life.
                </p>
            </td>
        </tr>
        <tr>
            <td  align="left" style="padding-top:0px" colspan="7">
                <div align="right">
                    <span style="font-style:30px">
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                            <span style="font-size:20px">
                            <strong>Engr. Sabuj Ahmed</strong><br>
Head of the Department <br>
Department of Electrical and Electronic Engineering<br>
Barishal Engineering College<br>

    </span>
                        <br>
                    </span>
                </div>
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
        // $pdf->Image('logo/du_bec.png', 77, 12, 50); // x, y, width height
        $pdf->Image('logo/testimonial_eee_head.jpg', 0, 0, 210); // x, y, width

        // Calculate Y position for bottom of the page
        $pageHeight = $pdf->GetPageHeight();   // 297 for A4
        $imageHeight = 20;                     // height of your image in mm
        $y = $pageHeight - $imageHeight;       // position from top

        $pdf->Image('logo/testimonial_eee_bottom.jpg', 0, $y, 210, $imageHeight); // full width, bottom

        // $pdf->Image('Dhaka_University_logo.png', 120, 12, 40); // x, y, width
    // }
}

// Output modified PDF
// $pdf->Output('F', 'output.pdf');

// $file_path=$i.".jpg";
$file_path_pdf="Testimonial.pdf";
$pdf->Output($file_path_pdf,"I");
unlink($outputPath);

?>
