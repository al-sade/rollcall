<?php
require_once(__DIR__.'/../dbconfig.php');
require_once(__DIR__.'/../config.php');
require_once(__DIR__.'/../classes/class.user.php');

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
