<?php

require_once 'vendor/autoload.php'; // Adjust path to your autoload.php

// Path to JSON file
$jsonFile = 'students.json';

// Output file path
$outputPath = 'moi.pdf';

if (!file_exists($jsonFile)) {
    die("students.json file not found.");
}

$jsonData = file_get_contents($jsonFile);
$students = json_decode($jsonData, true);

if ($students === null) {
    die("Invalid JSON data.");
}
$degree_month_year="August, 2025";
$cgpa_7th_sem="3.63";
$issue_date=date("d M Y");


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

$mpdf->SetTitle('All MOI - Barisal Engineering College');

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
            <td align="center" colspan="1">

            </td>
            <td align="left" colspan="3" style="font-size:20px">
                Department of Civil Engineering<br>Barisal Engineering College<br>Barisal-8200, Bangladesh
            </td>
        </tr>
        <tr><td colspan="7"><hr></td></tr>
        <tr>
            <td align="left" colspan="4">
                <strong>Ref No: BEC/CE/MOI/' . $batch . '/' . $reg_no . '</strong>
            </td>
            <td align="right" colspan="3">
                <strong>Date: ' . date('d M Y') . '</strong>
            </td>
        </tr>
        <tr>
            <td colspan="7" align="center" style="padding-top:50px">
                <u style="font-size:35px">PROVISIONAL CERTIFICATE</u>
            </td>
        </tr>
        <tr>
            <td colspan="7" style="text-align: justify; padding-top: 30px; font-size: 20px; line-height: 1.8;">
                <p style="margin-bottom: 15px;">
                    Subject to the approval of the University authority, <strong>' . htmlspecialchars($student['Name']) . '</strong>, Registration No. 
                    <strong>' . htmlspecialchars($reg_no) . '</strong>, is declared to have fulfilled all the requirements of the degree of
                <span style="text-align:center; font-weight:bold; font-size:22px; margin-bottom:15px;">
                    BACHELOR OF SCIENCE IN CIVIL ENGINEERING</span>
                </p>
                <p style="margin-bottom: 15px;">
                    with Major in <strong>Geotechnical Engineering</strong> in <strong></strong>. 
                    His/Her Cumulative Grade Point Average (CGPA) is <strong></strong> on a scale of 4.00.
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
        $pdf->Image('du_bec.png', 77, 12, 40); // x, y, width
        // $pdf->Image('du_bec.png', 77, 12, 50); // x, y, width height

        // $pdf->Image('Dhaka_University_logo.png', 120, 12, 40); // x, y, width
    // }
}

// Output modified PDF
// $pdf->Output('F', 'output.pdf');

        // $file_path=$i.".jpg";
$file_path_pdf="m.pdf";
$pdf->Output($file_path_pdf,"I");
    // unlink($file_path);

    /*<td colspan="7" style="text-align: justify; padding-top: 30px; font-size: 20px; line-height: 1.8;">
    <p style="margin-bottom: 15px;">
        Subject to the approval of the University authority, 
        <strong><?php echo htmlspecialchars($student['Name']); ?></strong>, Registration No. 
        <strong><?php echo htmlspecialchars($reg_no); ?></strong>, 
        is declared to have fulfilled all the requirements of the degree of
    </p>
    <p style="text-align:center; font-weight:bold; font-size:22px; margin-bottom:15px;">
        BACHELOR OF SCIENCE IN TEXTILE ENGINEERING
    </p>
    <p style="margin-bottom: 15px;">
        with Major in <strong>Apparel Manufacturing</strong> in 
        <strong><?php echo htmlspecialchars($degree_session); ?></strong>. 
        His/Her Cumulative Grade Point Average (CGPA) is 
        <strong><?php echo htmlspecialchars($cgpa); ?></strong> on a scale of 4.00.
    </p>
</td>
*/

?>
