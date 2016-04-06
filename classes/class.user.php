<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/rollcall/dbconfig.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/rollcall/config.php');
class USER
{
	private $conn;
	public function __construct()
	{
		$database = new Database();
		$db = $database->dbConnection();
		$this->conn = $db;
    }
	public function runQuery($sql)
	{
		$stmt = $this->conn->prepare($sql);
		return $stmt;
	}
	public function register($id_number,$first_name,$last_name,$email,$bday,$begin_studying,$department,$pass)
	{
		try
		{
			$new_password = password_hash($pass, PASSWORD_DEFAULT);
			$stmt = $this->conn->prepare("INSERT INTO users(id_number,first_name,last_name,email,bday,begin_studying,department,pass)
		                                               VALUES(:id_number,:first_name,:last_name,:email,:bday,:begin_studying,:department,:pass)");
			$stmt->bindparam(":id_number", $id_number);
			$stmt->bindparam(":first_name", $first_name);
			$stmt->bindparam(":last_name", $last_name);
			$stmt->bindparam(":email", $email);
			$stmt->bindparam(":bday", $bday);
			$stmt->bindparam(":begin_studying", $begin_studying);
			$stmt->bindparam(":department", $department);
			$stmt->bindparam(":pass", $new_password);
			$stmt->execute();
			return $stmt;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
	public function doLogin($id_number,$email,$pass)
	{
		try
		{
			$stmt = $this->conn->prepare("SELECT * FROM users WHERE id_number=:id_number OR email=:email ");
			$stmt->execute(array(':id_number'=>$id_number, ':email'=>$email));
			$userRow=$stmt->fetch(PDO::FETCH_ASSOC);
			if($stmt->rowCount() == 1)
			{
				if(password_verify($pass, $userRow['pass']))
				{
					$_SESSION['user_session'] = $userRow['user_id'];
					$_SESSION['admin'] = $userRow['admin'];
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
	public function is_loggedin()
	{
		if(isset($_SESSION['user_session']))
		{
			return true;
		}
	}
	public function redirect($url)
	{
		header("Location: $url");
	}
	public function doLogout()
	{
		session_destroy();
		unset($_SESSION['user_session']);
		return true;
	}
	public function getCourses($user_id)
	{
		$stmt = $this->conn->prepare("SELECT courses.course_name, lecturers.first_name, lecturers.last_name,lecturers.email
		FROM students_courses
		INNER JOIN courses ON students_courses.course = courses.course_id
		INNER JOIN lecturers ON courses.lecturer = lecturers.id
		WHERE student =:student_id ");
		$stmt->execute(array(':student_id'=>$user_id));
		$userRow=$stmt->fetchall(PDO::FETCH_ASSOC);
		return $userRow;
	}

	public function getPresence($user_id)
	{
		$stmt = $this->conn->prepare("SELECT
		presence.student, presence.course, presence.date, courses.course_name
		FROM presence
		INNER JOIN courses ON presence.course = courses.course_id
 		WHERE student =:student_id ");
		$stmt->execute(array(':student_id'=>$user_id));
		$userRow=$stmt->fetchall(PDO::FETCH_ASSOC);
		return $userRow;
	}

	public function kairosEnroll($user_id, $pic_id)
{
	$subject_id = $user_id."-a".$pic_id;
	// The data to send to the API
$postData = array(
	 "image" => "http://104.131.0.21/rollcall/images/users/".$subject_id.".png",
	 "subject_id" => $user_id.'-a'.$pic_id,
	 "gallery_name" => KAIROS_GALLERY,
	 "selector" => "SETPOSE",
	 "symmetricFill" => "true"
);

		$url = "https://api.kairos.com/enroll";
    $curl = curl_init($url);

		// Setup cURL
		$ch = curl_init($url);
		curl_setopt_array($ch, array(
		    CURLOPT_POST => TRUE,
		    CURLOPT_RETURNTRANSFER => TRUE,
		    CURLOPT_HTTPHEADER => array(
					'Content-Type:  application/json',
					'app_id:'.APP_ID,
					'app_key:'.APP_KEY
		    ),
		    CURLOPT_POSTFIELDS => json_encode($postData)
		));

		// Send the request
		$response = curl_exec($ch);

		// Check for errors
		if($response === FALSE){
		    die(curl_error($ch));
		}

		// Decode the response
		$responseData = json_decode($response, TRUE);

		// Print the date from the response
		// echo $responseData['published'];

		}
}
?>
