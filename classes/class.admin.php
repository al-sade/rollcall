<?php
require_once(__DIR__.'/../dbconfig.php');
require_once(__DIR__.'/../config.php');
require_once(__DIR__.'/../classes/class.user.php');

class ADMIN extends USER
{
  public function doLogin($admin_id, $pass)
  {
    try
    {
      $stmt = $this->conn->prepare("SELECT * FROM admins WHERE admin_id=:admin_id");
      $stmt->execute(array(':admin_id'=>$admin_id));
      $adminRow=$stmt->fetch(PDO::FETCH_ASSOC);
      if($stmt->rowCount() == 1)
      {
        if(password_verify($pass, $adminRow['pass']))
        {
          $_SESSION['user_session'] = $adminRow['admin_id'];
          return true;
        }
        else
        {
          return false;
        }
      }
    }
    catch(PDOException $e)
    {
      echo $e->getMessage();
    }
  }

  public function register($id_number,$first_name,$last_name,$email,$bday,$department,$pass)
	{
		try
		{
      $lecturer = 1;
			$new_password = password_hash($pass, PASSWORD_DEFAULT); //default php const algo

			$stmt = $this->conn->prepare("INSERT INTO users (user_id, id_number, first_name, last_name, email, bday, begin_studying, department, pass, joining_date, lecturer)
      VALUES (
        NULL,
        :id_number,
        :first_name,
        :last_name,
        :email,
        :bday,
        NULL,
        :department,
        :pass,
        CURRENT_TIMESTAMP,
        :lecturer);
        ");
			$stmt->bindparam(":id_number", $id_number);
			$stmt->bindparam(":first_name", $first_name);
			$stmt->bindparam(":last_name", $last_name);
			$stmt->bindparam(":email", $email);
			$stmt->bindparam(":bday", $bday);
			$stmt->bindparam(":department", $department);
      $stmt->bindparam(":pass", $new_password);
      $stmt->bindparam(":lecturer", $lecturer);
			$stmt->execute();
			return $stmt;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
  public function initAdmin(){

  }

  public function getDepartments(){
      $stmt = $this->conn->prepare("SELECT name FROM departments");
      $stmt-> execute();
      $result=$stmt->fetchall(PDO::FETCH_ASSOC);
      return $result;
  }

  public function getStudents($department){
    $stmt = $this->conn->prepare("SELECT id_number, first_name, last_name, email, bday, begin_studying, department FROM users
    WHERE department = :department AND lecturer = 0");
    $stmt-> execute(array(':department' => $department));
    $result=$stmt->fetchall(PDO::FETCH_ASSOC);
    return $result;
  }

  public function getLecturers($department){
    $stmt = $this->conn->prepare("SELECT id_number, first_name, last_name, email, bday, department FROM users
    WHERE department = :department AND lecturer = 1");
    $stmt-> execute(array(':department' => $department));
    $result=$stmt->fetchall(PDO::FETCH_ASSOC);
    return $result;
  }

  public function getCameras(){
    $stmt = $this->conn->prepare("SELECT cameras.camera, cameras.day_of_week, cameras.course_id, cameras.open_time,cameras.close_time, courses.course_name
      FROM cameras
      INNER JOIN courses ON cameras.course_id = courses.course_id
      ORDER BY day_of_week ASC
    ");
    $stmt-> execute(array(':department' => $department));
    $result=$stmt->fetchall(PDO::FETCH_ASSOC);
    return $result;
  }
}

?>
