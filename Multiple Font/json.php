<?php
// Path to the existing image
$image_path = 'main.jpg';

// Load the existing image
$image = imagecreatefromjpeg($image_path);

// Get the width and height of the image
$width = imagesx($image);
$height = imagesy($image);

// Set text colors
$text_color = imagecolorallocate($image, 0, 0, 0); // black
$highlight_color = imagecolorallocate($image, 255, 0, 0); // red

// Path to the font files
$font_path_regular = "fonts/Vivaldi.ttf";
$font_path_bold = "fonts/BELL.TTF";
// JSON data
$json_data = '{
    "text": "This is a sample text to demonstrate text alignment in an image using PHP. Some words should be highlighted to show the capability of using multiple fonts.",
    "highlight_words": ["sample", "highlighted", "multiple fonts"]
}';

// Decode JSON data
$data = json_decode($json_data, true);
$text = $data['text'];
$highlight_words = $data['highlight_words'];

// Font size
$font_size = 60;

// Add text with different alignments including justified
// add_wrapped_text_to_image($image, $text, $font_path_regular, $font_path_bold, $font_size, $text_color, $highlight_color, $highlight_words, $width, 'left', 50);
// add_wrapped_text_to_image($image, $text, $font_path_regular, $font_path_bold, $font_size, $text_color, $highlight_color, $highlight_words, $width, 'center', 150);
// add_wrapped_text_to_image($image, $text, $font_path_regular, $font_path_bold, $font_size, $text_color, $highlight_color, $highlight_words, $width, 'right', 250);
add_wrapped_text_to_image($image, $text, $font_path_regular, $font_path_bold, $font_size, $text_color, $highlight_color, $highlight_words, $width, 'justify', 350);

// Output the image
header('Content-Type: image/png');
imagepng($image);
imagedestroy($image);

function add_wrapped_text_to_image($image, $text, $font_path_regular, $font_path_bold, $font_size, $color, $highlight_color, $highlight_words, $image_width, $alignment, $y) {
    $words = explode(' ', $text);
    $line = '';
    $line_height = $font_size + 10;
    $current_y = $y;

    foreach ($words as $word) {
        $box = imagettfbbox($font_size, 0, $font_path_regular, $line . ' ' . $word);
        $line_width = $box[2] - $box[0];
        
        if ($line_width > $image_width - 20) { // 20px padding from both sides
            draw_text_line($image, $line, $font_path_regular, $font_path_bold, $font_size, $color, $highlight_color, $highlight_words, $image_width, $alignment, $current_y, false);
            $line = $word;
            $current_y += $line_height;
        } else {
            $line .= ' ' . $word;
        }
    }
    // Draw the last line, treating it differently for justified alignment
    draw_text_line($image, $line, $font_path_regular, $font_path_bold, $font_size, $color, $highlight_color, $highlight_words, $image_width, $alignment, $current_y, true);
}

function draw_text_line($image, $line, $font_path_regular, $font_path_bold, $font_size, $color, $highlight_color, $highlight_words, $image_width, $alignment, $y, $is_last_line) {
    $words = explode(' ', trim($line));
    $total_words = count($words);
    $space_width = 0;

    if ($alignment == 'justify' && !$is_last_line && $total_words > 1) {
        $box = imagettfbbox($font_size, 0, $font_path_regular, trim($line));
        $text_width = $box[2] - $box[0];
        $space_width = ($image_width - 20 - $text_width) / ($total_words - 1); // Distribute extra space between words
    }

    $x = 00; // Default for left alignment
    if ($alignment == 'center' || ($alignment == 'justify' && $is_last_line)) {
        $box = imagettfbbox($font_size, 0, $font_path_regular, $line);
        $text_width = $box[2] - $box[0];
        $x = ($image_width - $text_width) / 2;
    } else if ($alignment == 'right') {
        $box = imagettfbbox($font_size, 0, $font_path_regular, $line);
        $text_width = $box[2] - $box[0];
        $x = $image_width - $text_width - 10; // 10px padding from right
    }

    foreach ($words as $word) {
        $is_highlighted = in_array(trim($word), $highlight_words);
        $current_font_path = $is_highlighted ? $font_path_bold : $font_path_regular;
        $current_color = $is_highlighted ? $highlight_color : $color;

        // Draw the word
        imagettftext($image, $font_size, 0, $x, $y, $current_color, $current_font_path, $word);

        // Move x position for the next word
        $box = imagettfbbox($font_size, 0, $current_font_path, $word);
        $word_width = $box[2] - $box[0];
        $x += $word_width + 5 + $space_width; // 5px space between words, add extra space for justify
    }
}
?>
