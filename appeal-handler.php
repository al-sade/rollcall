<?php
require_once("classes/class.user.php");

$auth_user = new USER();

$appeal_id = $_POST['appealId'];

if($auth_user->appealIsRead($appeal_id)){
  return  true;
};
?>
