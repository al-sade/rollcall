<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/rollcall/dbconfig.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/rollcall/config.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/rollcall/classes/class.user.php');
class ADMIN extends USER
{

public function isAdmin(){
  if (isset($_SESSION['admin'])) TRUE : FALSE ;
}
}

?>
