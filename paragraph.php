<?php

require('fpdf.php');
$pdf = new FPDF();
$pdf->SetAuthor("Dhrubo Raj Roy-http://TheDhrubo.xyz");

$php_array = json_decode(file_get_contents('horizon.json'), true);
$count = count($php_array);

for ($i = 0; $i < 2; $i++) {
// for ($i = 0; $i < $count; $i++) {
    $image = imagecreatefromjpeg("horizon.jpg");
    $color = imagecolorallocate($image, 142, 98, 38);

    // Name
    make_text_middle(ucwords(strtolower($php_array[$i]['Name'])), $image, $color, "fonts/cac_champagne.ttf", 0, 50, 200);

    // Designation
    $text1 = "He/She served as a ";
    $text2 = $php_array[$i]['Post (Horizons Club )'];
    $text3 = " of Horizon club from 29 December, 2022 to 29 May, 2024.";
    make_certificate_line_multiline($text1, $text2, $text3, $image, $color, 1500);

    // Paragraph (Lorem Ipsum)
    $paragraph = "What is Lorem Ipsum?\nLorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.";

    $maxWidth = imagesx($image) - 500; // Dynamic max width
    make_paragraph_text_dynamic($paragraph, $image, $color, "fonts/ovo.ttf", 240, 800, $maxWidth); 

    $maxWidth = imagesx($image) - 600;
    make_paragraph_text_justify($paragraph, $image, $color, "fonts/ovo.ttf", 240, 800, $maxWidth);

    // Save image temporarily
    $file_path = $i . ".jpg";
    $file_path_pdf = $php_array[$i]['Name'] . ".pdf";
    imagejpeg($image, $file_path);
    imagedestroy($image);   

    // Add image to PDF
    $pdf->AddPage('L', 'A4');
    $pdf->Image($file_path, 0, 0, 300);

    unlink($file_path); // Remove temp image
}

$pdf->SetTitle("Certificate");
$pdf->Output($file_path_pdf, "I");


?>
