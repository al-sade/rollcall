<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/rollcall/dbconfig.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/rollcall/config.php');
class USER
{
	protected $conn;
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
					if($userRow['lecturer'])
						$_SESSION['lecturer'] = $userRow['lecturer'];
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

	public function getCourse($user_id,$course_id){
		$stmt = $this->conn->prepare("SELECT courses.course_name,users.user_id, users.first_name, users.last_name,students_courses.day_of_week,students_courses.start,students_courses.end
		FROM courses
		INNER JOIN students_courses ON courses.course_id = students_courses.course AND students_courses.student = :user_id
		INNER JOIN users ON courses.lecturer_id = users.user_id
		WHERE course_id =:course_id ");
		$stmt->execute(array(':course_id'=>$course_id, ':user_id'=>$user_id));
		$userRow=$stmt->fetchall(PDO::FETCH_ASSOC);
		return $userRow;

	}

	public function getCourses($user_id)
	{
		$stmt = $this->conn->prepare("SELECT courses.course_name, users.first_name, users.last_name,users.email,courses.course_id
		FROM students_courses
		INNER JOIN courses ON students_courses.course = courses.course_id
		INNER JOIN users ON courses.lecturer_id = users.user_id
		WHERE student =:student_id ");
		$stmt->execute(array(':student_id'=>$user_id));
		$userRow=$stmt->fetchall(PDO::FETCH_ASSOC);
		return $userRow;
	}

	//get presence for a specific course
	public function getCoursePresence($user_id, $course_id){
		$stmt = $this->conn->prepare("SELECT *
		FROM presence
 		WHERE student =:student_id AND course = :course_id");
		$stmt->execute(array(':student_id'=>$user_id, ':course_id'=>$course_id));
		$userRow=$stmt->fetchall(PDO::FETCH_ASSOC);
		return $userRow;

	}

	//get presence for entire courses
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

	public function getSchedule($user_id)
	{
		$stmt = $this->conn->prepare("SELECT
		students_courses.course,students_courses.day_of_week,students_courses.start,students_courses.end,courses.course_name
		FROM students_courses
		INNER JOIN courses ON students_courses.course = courses.course_id
		WHERE student =:student_id ");
		$stmt->execute(array(':student_id'=>$user_id));

		$stmt->execute(array(':student_id'=>$user_id));
		$userRow=$stmt->fetchall(PDO::FETCH_ASSOC);
		return $userRow;
	}

	public function getCourseSchedule($user_id, $course_id)
	{
		$stmt = $this->conn->prepare("SELECT
		students_courses.course,students_courses.day_of_week,students_courses.start,students_courses.end,courses.course_name
		FROM students_courses
		INNER JOIN courses ON students_courses.course = courses.course_id
		WHERE student =:student_id AND course = :course_id");

		$stmt->execute(array(':student_id'=>$user_id, ':course_id'=>$course_id));
		$userRow=$stmt->fetchall(PDO::FETCH_ASSOC);
		return $userRow;
	}

	public function getAppeals($user_id){
	  $stmt = $this->conn->prepare("SELECT appeals.appeal_id, appeals.course_id, appeals.lecturer_id,appeals.student_id, appeals.content, appeals.submit_date,appeals.date_of_issue, appeals.read, appeals.file_dir, appeals.response, appeals.approved,users.first_name, users.last_name,courses.course_name
	  FROM appeals
	  INNER JOIN users ON appeals.lecturer_id = users.user_id
	  INNER JOIN courses ON appeals.course_id = courses.course_id
	  WHERE appeals.student_id = :student_id AND appeals.student_show = 1");
	  $stmt->execute(array(':student_id' => $user_id));
	  $result = $stmt->fetchall(PDO::FETCH_ASSOC);

	  return $result;
	}

	public function appealIsRead($appeal_id){
		$stmt = $this->conn->prepare("UPDATE appeals SET `student_show` = 0 WHERE `appeal_id` = :appeal_id");
		$stmt->execute(array(':appeal_id' => $appeal_id));
		// $result = $stmt->fetchall(PDO::FETCH_ASSOC);

		return $stmt;
	}

	public function submitAppeal($user_id, $course_id, $lecturer_id,$date_of_issue, $message, $target_file, $file = NULL){
		try
		{

			$stmt = $this->conn->prepare("INSERT INTO  appeals (
				`appeal_id` ,
				`course_id` ,
				`student_id` ,
				`lecturer_id` ,
				`content` ,
				`file_dir` ,
				`submit_date` ,
				`date_of_issue` ,
				`read`,
				`student_show`,
				`response`,
				`approved`
				)
				VALUES (
				NULL , :course_id, :student_id, :lecturer_id, :content, :target_file,
				CURRENT_TIMESTAMP ,:date_of_issue, '0', '1', NULL, '0'
				);");

			$stmt->bindparam(":course_id", $course_id);
			$stmt->bindparam(":student_id", $user_id);
			$stmt->bindparam(":lecturer_id", $lecturer_id);
			$stmt->bindparam(":content", $message);
			$stmt->bindparam(":target_file", $target_file);
			$stmt->bindparam(":date_of_issue", $date_of_issue);
			$stmt->execute();
			var_dump($stmt) ;
			return $stmt;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
	public function createAttendanceTable($user_id){
    $stmt = $this->conn->prepare("SELECT * FROM presence Where student = :user_id");
    $stmt->execute(array(':user_id' => $user_id));
    $result = $stmt->fetchall(PDO::FETCH_ASSOC);

    // var_dump($result);
    return $result;

  }
	public function getDay($day_num){
		$arr = array('Sunday', 'Monday', 'Tuesday', 'Wedensday', 'Thursday', 'Friday', 'Saturday');
		return $arr[$day_num];
	}
	public function kairosEnroll($user_id, $pic_id)
{
	$subject_id = $user_id."-a".$pic_id;
	// The data to send to the API
$postData = array(
	 "image" => "http://104.131.0.21/rollcall/uploads/images/users/".$subject_id.".png",
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
