<?php
//error_reporting(E_ALL);
//var_dump($_SERVER);
$post_data = $_POST['pngUrl'];
$user_id = $_POST['id'];

if (!empty($post_data)){
list($type, $post_data) = explode(';', $post_data);
list(, $post_data)      = explode(',', $post_data);
$post_data = base64_decode($post_data);

file_put_contents('images/users/'.$user_id.'.png', $post_data);
}

//This logic can be used to save base64_encode vevrsion of the image!
// if (!empty($post_data)) {
//     $dir = '/var/www/rollcall/images/users';
//     $file = $user_id;
//     $filename = dirname(__FILE__)."/images/users/".$file.'.txt	';
//     $handle = fopen($filename, "w");
//     fwrite($handle, $post_data);
//     fclose($handle);
//     echo $file;
// }
?>
