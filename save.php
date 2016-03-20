<?php
require_once("session.php");

require_once("class.user.php");
$auth_user = new USER();

$post_data = $_POST['pngUrl'];
$user_id = $_POST['id'];
$pic_id = $_POST['pic_id']; // get the picture id from the catch-pic.js script


if (!empty($post_data)){
list($type, $post_data) = explode(';', $post_data);
list(, $post_data)      = explode(',', $post_data);
$post_data = base64_decode($post_data);

$path = "images/users/'.$user_id.'.png";
file_put_contents($path, $post_data);
}

$auth_user->kairosEnroll('images/users/212121.png',$user_id);

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
