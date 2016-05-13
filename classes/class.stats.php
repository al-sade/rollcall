<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/rollcall/dbconfig.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/rollcall/config.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/rollcall/classes/class.user.php');

class STATS extends USER
{
  private $user_id;

	public function __construct()
	{
  }

  // public function createAttendanceTable($user_id){
  //   $stmt = $this->conn->prepare("SELECT * FROM presence Where student = :user_id");
  //   $stmt->execute(array(':user_id' => $user_id));
  //   $result = $stmt->fetchall(PDO::FETCH_ASSOC);
  //   return $result;
  //
  // }
}
