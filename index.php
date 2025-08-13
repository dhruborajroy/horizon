
<?php


require('fpdf.php');
$pdf=new FPDF();
$pdf->SetAuthor("Dhrubo Raj Roy-http://TheDhrubo.xyz");


$php_array = json_decode(file_get_contents('horizon.json'), true);


$count=count($php_array);
for ($i=0; $i < $count; $i++) {
// for ($i=12; $i < 14; $i++) {
    $image=imagecreatefromjpeg("horizon.jpg");
    $color=imagecolorallocate($image,142,98,38);
    
    make_text_middle(ucwords(strtolower($php_array[$i]['Name'])),$image,$color,"fonts/cac_champagne.ttf",0,50,200);


    //Designation 
    $color=imagecolorallocate($image,142,98,38);
    
    // Text parts
    $text1 = "He/She served as a ";
    $text2 = $php_array[$i]['Post (Horizons Club )'];
    $text3 = " of Horizon club from 29 December, 2022 to 29 May, 2024."; // use | for newline

    make_certificate_line_multiline($text1,$text2,$text3,$image, $color,1500);

    

    $file=" ".$i." ".'_'.$i;
    $file_path=$i.".jpg";
    $file_path_pdf=$php_array[$i]['Name'].".pdf";
    imagejpeg($image,$file_path);
    imagedestroy($image);   
    //Page start 
    $pdf->AddPage('L','A4');
    $pdf->Image($file_path,0,0,300);
    //Page Ended
    unlink($file_path);
}
$pdf->SetTitle("Certificate");
$pdf->Output($file_path_pdf,"I");
unlink($file_path);
?>