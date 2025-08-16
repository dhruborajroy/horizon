<?php
require_once 'vendor/autoload.php'; // Adjust path to your autoload.php

// Path to JSON file
$jsonFile = 'data/students.json';

// Output file path
$outputPath = 'ALL_TESTIMONIALS.pdf';

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

    $html = '
    <table width="100%">
        <tr>
            <td align="left" colspan="3" style="font-size:20px">
                <span style="font-size:25px; font-family: Siyam Rupali;">সিভিল ইঞ্জিনিয়ারিং বিভাগ</span><br>
                বরিশাল ইঞ্জিনিয়ারিং কলেজ<br>বরিশাল - ৮২০০, বাংলাদেশ
            </td>
            <td align="center">
                <img src="logo/bec.png" width="100" height="100" />
            </td>
            <td align="left" colspan="3" style="font-size:19px">
                Department of Civil Engineering<br>Barisal Engineering College<br>Barisal-8200, Bangladesh
            </td>
        </tr>
        <tr><td colspan="7"><hr></td></tr>
        <tr>
            <td align="left" colspan="4">
                <strong>Ref No: BEC/CE/TESTIMONIAL/' . $batch . '/' . $reg_no . '</strong>
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
                <p style="padding-top:100px">
                    This is to certify that <strong>' . $student['Name'] . '</strong>, bearing Reg No.
                    <strong>' . htmlspecialchars($reg_no) . '</strong>, Session <strong>' . htmlspecialchars($session) . '</strong>,
                    was a student of Barishal Engineering College, Barishal, at the Department of Civil Engineering affiliated with the University of Dhaka from February 2021 to September 2025.
                </p><br>
                <p style="font-size: 20px;">
                    He/She bears a good moral character. He did not take part in any activity subversive to the state
                    or discipline.
                </p><br>
                <p style="font-size: 20px;">
                    I wish every success in his life.
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
                        Head of the Department &amp; Lecturer <br>
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
$mpdf->Output($outputPath, "I");

// echo "✅ All testimonials saved to: $outputPath";
?>
