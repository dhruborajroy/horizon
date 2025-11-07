
<?php


require('fpdf.php');
$pdf=new FPDF();
$pdf->SetAuthor("Dhrubo Raj Roy-http://TheDhrubo.xyz");


$php_array = json_decode(file_get_contents('data/horizon.json'), true);


$count=count($php_array);
// for ($i=0; $i < $count; $i++) {
for ($i=1; $i < 20; $i++) {
    $image=imagecreatefromjpeg("background_pics/eee_moi-page-001.jpg");
    $color=imagecolorallocate($image,142,98,38);
    
    // make_text_middle((($php_array[$i]['Name'])),$image,$color,"fonts/times.ttf",0,50,200);
    // make_text_middle(ucwords(strtolower($php_array[$i]['Name'])),$image,$color,"fonts/cac_champagne.ttf",0,50,200);
    // make_text_middle(ucwords(strtolower("Reg. No: ".$php_array[$i]['Reg No'])),$image,$color,"fonts/ovo.ttf",0,150,70);
    // make_text_middle(ucwords(strtolower("Department: ").$php_array[$i]['Department']),$image,$color,"fonts/ovo.ttf",0,250,70);


    //Designation 
    $color=imagecolorallocate($image,0 ,0,0);
    
    // Text parts
    // $text1 = "He/She served as a ";
    // $text2 = $php_array[$i]['Post (Horizons Club )'];
    // $text3 = " of the ";
    // $text4 = $php_array[$i]['Committee'];
    // $text3 = " of the Horizon - Civil Engineers Forum from 29 December, 2022 to 29 May, 2024."; // use | for newline


    // $text1 = "He/She served as a [CS]".$php_array[$i]['Post (Horizons Club )']."[/CS] of the  [CS]".$php_array[$i]['Committee']."[/CS] Team of Horizon - Civil Engineers Forum from 29 December, 2022 to 29 May, 2024.";
    // $committee=$php_array[$i]['Committee'];
    // if($committee=="Central Committee"){
    //     $text1 = "has served as the [CS]".$php_array[$i]['Post (Horizons Club )']."[/CS] of Horizon - Civil engineers forum during the session 2024–2025 with dedication and responsibility";
    // }elseif($committee==""){
    //     $text1 = "has served as the [CS]".$php_array[$i]['Post (Horizons Club )']."[/CS] of Horizon - Civil engineers forum during the session 2024–2025 with dedication and responsibility";
    // }else{
    //     $text1 = "has served as the [CS]".$php_array[$i]['Post (Horizons Club )']."[/CS] of the [CS]".$php_array[$i]['Committee']."[/CS] Sub-Committee of Horizon Civil engineers forum during the session 2024–2025 with dedication and responsibility";
    // }
    $text1 = "This is to certify that MD. ANAWAR IBRAHIM, bearing Registration No. 3098, Session 2019-20, has successfully completed B.Sc. in Electrical and Electronic Engineering from Barishal Engineering College under the affiliation of University of Dhaka. | The duration of this course was Four (04) Years.
| English was the medium of instruction for all courses and examinations of this program.";

    $text2="";
    $text3 = ""; // use | for newline

make_certificate_line_multiline($text1, $text2, $text3, $image, $color, 1600, "justify");

    

    $file=" ".$i." ".'_'.$i;
    $file_path=$i.".jpg";
    $file_path_pdf=$php_array[$i]['Name'].".pdf";
    imagejpeg($image,$file_path);
    imagedestroy($image);   
    //Page start 
    $pdf->AddPage('P','A4');
    $pdf->Image($file_path, 0, 0, 210, 297);  // Width = 210mm, Height = 297mm
    //Page Ended
    unlink($file_path);
}
$pdf->SetTitle("Certificate");
$pdf->Output($file_path_pdf,"I");
unlink($file_path);
?>