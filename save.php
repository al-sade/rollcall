<?php
require_once("session.php");
require_once("config.php");
require_once("classes/class.user.php");

$auth_user = new USER();

$post_data = $_POST['pngUrl'];
$user_id = $_POST['id'];
$pic_id = $_POST['pic_id']; // get the picture id from the catch-pic.js script


if (!empty($post_data)){
list($type, $post_data) = explode(';', $post_data);
list(, $post_data)      = explode(',', $post_data);
$post_data = base64_decode($post_data);

$img_name = $user_id."-a".$pic_id.".png";
$img_path = IMG_PATH.$img_name;

file_put_contents($img_path, $post_data);
}

//send image to kairos
// $auth_user->kairosEnroll($user_id, $pic_id);

//This logic can be used to save base64_encode vevrsion of the image!
// if (!empty($post_data)) {
//     $dir = '/var/www/rollcall/images/users';
//     $file = $user_id."_a_".$pic_id; // here we gve file name - you want to change it!
//     $filename = dirname(__FILE__)."/images/users/".$file.'.txt	';
//     $handle = fopen($filename, "w");
//     fwrite($handle, $post_data);
//     fclose($handle);
//     echo $file;
// }
?>