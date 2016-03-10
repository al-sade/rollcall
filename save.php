<?php
//error_reporting(E_ALL);
//var_dump($_SERVER);
$post_data = $_POST['pngUrl'];
$user_id = $_POST['id'];
$data = 'data:image/png;base64,AAAFBfj42Pj4';

list($type, $data) = explode(';', $data);
list(, $data)      = explode(',', $data);
$data = base64_decode($data);

file_put_contents('/tmp/image.png', $data);

if (!empty($post_data)) {
    $dir = '/var/www/rollcall/images/users';
    $file = $user_id;
    $filename = dirname(__FILE__)."/images/users/".$file.'.txt	';
    $handle = fopen($filename, "w");
    fwrite($handle, $post_data);
    fclose($handle);
    echo $file;
}
?>
