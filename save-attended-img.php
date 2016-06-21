<?php
require_once("session.php");
require_once("config.php");
require_once("classes/class.user.php");

$auth_user = new USER();

$post_data = $_POST['URL'];
copy($post_data, __DIR__.'/uploads/appeals/file.jpg');



?>
