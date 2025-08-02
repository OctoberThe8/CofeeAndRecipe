<?php
function resizeImage($path, $width, $height) {
    // Get image details
    $info = getimagesize($path);
    if (!$info) return false;

    $mime = $info['mime'];

    // Create an image resource based on file type
    switch ($mime) {
        case 'image/jpeg': 
            $image = imagecreatefromjpeg($path); 
            break;
        case 'image/png': 
            $image = imagecreatefrompng($path); 
            break;
        default: 
            return false; // Unsupported format
    }

    // Resize the image to the specified width and height
    $resized = imagescale($image, $width, $height);

    // Capture the resized image output as a string
    ob_start();
    imagejpeg($resized);
    $data = ob_get_clean();

    // Return the image as a Base64 data URI
    return 'data:image/jpeg;base64,' . base64_encode($data);
}
?>
