<?php
require_once('dbconfig.php');
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

	public function getAbsence($user_id)
	{
		$stmt = $this->conn->prepare("SELECT
		absence.student, absence.course, absence.date, courses.course_name
		FROM absence
		INNER JOIN courses ON absence.course = courses.course_id
 		WHERE student =:student_id ");
		$stmt->execute(array(':student_id'=>$user_id));
		$userRow=$stmt->fetchall(PDO::FETCH_ASSOC);
		return $userRow;
	}

	public function kairosEnroll($path, $user_id)
{
	// The data to send to the API
$postData = array(
		"image" => 'images',
    'subject_id' => '3008042332',
    'gallery_name' => 'presidents',
    'selector' => 'SETPOSE',
    'symmetricFill' => 'true'
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
					'app_id: 40a56f65',
					'app_key: 3998c75b7404a3e22b0d52ab05727cc5'
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
		echo $responseData['published'];

		}
}
?>
