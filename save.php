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
// $auth_user->kairosEnroll($user_id, $pic_id); DEPRECATED
$auth_user->ftp_update($img_name, $user_id ,$img_path);

?>
