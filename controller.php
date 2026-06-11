<?php
// Define routes
addRoute('GET', '/',            function(){ view('index'); });
addRoute('POST', '/save/',      function(){ save(); });
addRoute('POST', '/imagedata/', function(){ imagedata(); });

/** Endpoint. When the user clicks download, a copy of the image is saved to the server. Uploaded files are expected to be WEBP images. */
function save() 
{
    validateToken();

    //Incomming data is webp image data, we need to save it as a file on the server
    $fileTempName = $_FILES['data']['tmp_name'] ?? false;
    $error = $_FILES['data']['error'] ?? false;
    if(!$fileTempName || $error !== UPLOAD_ERR_OK)
        die;

    $datetime = date('Y-m-d_H-i-s');
    $filename = SAVE_IMAGE_DIR . $datetime . '_' . uniqid() . '.webp';
    move_uploaded_file($fileTempName, $filename);
}

/** Endpoint. Returns image data form a give image URL as a WEBP image */
function imagedata()
{
    validateToken();

    $url = $_POST['url'] ?? '';
    if(empty($url))
        die;

    $data = file_get_contents($url);
    if(empty($data))
        die;

    $image = imagecreatefromstring($data);
    if(!$image)    
        die;

    imagesavealpha($image, true);

    //Sends the image data as a WEBP image to the output directly
    header('Content-Type: image/webp');
    imagewebp($image, null, IMAGEDATA_WEBP_QUALITY);
    
    imagedestroy($image);
}