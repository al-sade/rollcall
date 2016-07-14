<?php
require_once(__DIR__.'/../dbconfig.php');
require_once(__DIR__.'/../config.php');
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
	public function register($id_number,$first_name,$last_name,$email,$phone,$bday,$begin_studying,$department,$pass)
	{


		try
		{
			$new_password = password_hash($pass, PASSWORD_DEFAULT); //default php const algo
			$stmt = $this->conn->prepare("INSERT INTO users(id_number,first_name,last_name,email,phone,bday,begin_studying,department,pass)
		                                               VALUES(:id_number,:first_name,:last_name,:email,:phone,:bday,:begin_studying,:department,:pass)");
			$stmt->bindparam(":id_number", $id_number);
			$stmt->bindparam(":first_name", $first_name);
			$stmt->bindparam(":last_name", $last_name);
			$stmt->bindparam(":email", $email);
			$stmt->bindparam(":phone", $phone);
			$stmt->bindparam(":bday", $bday);
			$stmt->bindparam(":begin_studying", $begin_studying);
			$stmt->bindparam(":department", $department);
			$stmt->bindparam(":pass", $new_password);
			$stmt->execute();

			$course_id = "3333";
			$this->setStudentCourseAtt($id_number, $course_id);
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

	public function setStudentCourseAtt($user_id, $course_id){
		$stmt = $this->conn->prepare("INSERT INTO students_courses
		SET student = (SELECT user_id FROM users WHERE id_number = :id_number),
		course = :course_id,
		day_of_week = :day_of_week,
		start = :start,
		end = :end;
		");
		$stmt->execute(array(':course_id'=>$course_id, ':id_number'=>$user_id,':day_of_week'=>'5',':start'=>'11:00:00',':end'=>'23:00:00'));
		return $stmt;
	}

	public function getCourse($user_id,$course_id){
		$stmt = $this->conn->prepare("SELECT courses.course_name,courses.day_limit, users.us(SELECT student_id FROM users WHERE id_number = :id_number)er_id, users.first_name, users.last_name,students_courses.day_of_week,students_courses.start,students_courses.end
		FROM courses
		INNER JOIN students_courses ON courses.course_id = students_courses.course AND students_courses.student = :user_id
		INNER JOIN users ON courses.lecturer_id = users.user_id
		WHERE course_id =:course_id ");
		$stmt->execute(array(':course_id'=>$course_id, ':user_id'=>$user_id, ':id_number'=>$user_id));
		$userRow=$stmt->fetchall(PDO::FETCH_ASSOC);
		return $userRow;
	}

	public function getCourses($user_id)
	{
		$stmt = $this->conn->prepare("SELECT courses.course_name,courses.day_of_week,courses.start,courses.end,courses.day_limit, users.first_name, users.last_name,users.email,courses.course_id
		FROM students_courses
		INNER JOIN courses ON students_courses.course = courses.course_id
		INNER JOIN users ON courses.lecturer_id = users.user_id
		WHERE student =:student_id ");
		$stmt->execute(array(':student_id'=>$user_id));
		$userRow=$stmt->fetchall(PDO::FETCH_ASSOC);
		return $userRow;
	}

	//get presence for a specific course
	public function getUserCoursePresence($user_id, $course_id){
		$stmt = $this->conn->prepare('SELECT *
		FROM presence
 		WHERE student =:student_id
		AND course = :course_id
		AND date BETWEEN CAST("'.SEMESTER_START.'" AS DATE) AND NOW() + INTERVAL 1 DAY;');
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
		$result =$stmt->fetchall(PDO::FETCH_OBJ);
		return $result;
	}

	public function getAbsence($user_id)
	{
			$allCoursesDates = $this->getCoursesDates($user_id); //all dates of all courses

			$presence = $this->getPresence($user_id); // all dates user was presence
			$newPresence = array(); //array of key(course_name) => value(array of all dates user attended)
			$absence = array();

			foreach($presence as $key => $attendedDate){
				if(!isset($newPresence[$attendedDate->course_name])){
					$newPresence[$attendedDate->course_name] = array();
					$absence[$attendedDate->course_name] = array();
				}
				$strippedDate = explode(" ",$attendedDate->date);
				array_push($newPresence[$attendedDate->course_name], $strippedDate[0]);
			}
			foreach($allCoursesDates as $key => $dates){

				foreach($dates as $date){
				if(!isset($newPresence[$key]))
					$newPresence[$key] = array();
				if(!in_array($date,$newPresence[$key])){
					if(!isset($absence[$key]))
						$absence[$key] = array();
					array_push($absence[$key], $date);
					}
				}
			}

		return $absence;

	}

	public function getSchedule($user_id)
	{
		$stmt = $this->conn->prepare("SELECT
		students_courses.course,courses.course_name, courses.day_of_week,courses.start,courses.end
		FROM students_courses
		INNER JOIN courses ON students_courses.course = courses.course_id
		WHERE student =:student_id ");
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
	  $stmt = $this->conn->prepare("SELECT appeals.appeal_id, appeals.course_id, appeals.lecturer_id,appeals.student_id, appeals.content, appeals.submit_date,appeals.date_of_issue, appeals.read, appeals.file_dir, appeals.response, appeals.status,users.first_name, users.last_name,courses.course_name
	  FROM appeals
	  INNER JOIN users ON appeals.lecturer_id = users.user_id
	  INNER JOIN courses ON appeals.course_id = courses.course_id
	  WHERE appeals.student_id = :student_id AND appeals.student_show = 1");
	  $stmt->execute(array(':student_id' => $user_id));
	  $result = $stmt->fetchall(PDO::FETCH_ASSOC);

	  return $result;
	}

	public function getAcceptedAppeals($student_id, $course_id){
		$stmt = $this->conn->prepare("SELECT *
		FROM appeals
		WHERE student_id = :student_id
		AND course_id = :course_id
		AND status > 1
		GROUP BY date_of_issue");
		$stmt->execute(array(':student_id' => $student_id, ':course_id' => $course_id));
		$result = $stmt->fetchall(PDO::FETCH_ASSOC);

		return $result;
	}
	public function getAttendedByAppeal($student_id, $course_id){
		$stmt = $this->conn->prepare("SELECT *
		FROM appeals
		WHERE student_id = :student_id
		AND course_id = :course_id
		AND status = 1
		GROUP BY date_of_issue");
		$stmt->execute(array(':student_id' => $student_id, ':course_id' => $course_id));
		$result = $stmt->fetchall(PDO::FETCH_ASSOC);

		return $result;
	}

	public function getAppealsStatus($course_id, $student_id){
		$stmt = $this->conn->prepare("SELECT date_of_issue,status
		FROM appeals
		WHERE course_id =  :course_id
		AND student_id = :student_id
		AND status > 0");
		//0 and 1 are two default initializers, here we only update the newrer logic of appeals
		//including custom statuses as sick, late , miluim etc.
		$stmt->execute(array(':course_id' => $course_id, ':student_id' => $student_id));
		$result = $stmt->fetchall(PDO::FETCH_ASSOC);

		return $result;
	}

	public function appealIsRead($appeal_id){
		$stmt = $this->conn->prepare("UPDATE appeals SET `student_show` = 0 WHERE `appeal_id` = :appeal_id");
		$stmt->execute(array(':appeal_id' => $appeal_id));

		return $stmt;
	}

	public function submitAppeal($user_id, $course_id, $lecturer_id ,$cause ,$date_of_issue, $message, $target_file, $file = NULL){
		try
		{

			$stmt = $this->conn->prepare("INSERT INTO  appeals (
				`appeal_id` ,
				`course_id` ,
				`student_id` ,
				`lecturer_id` ,
				`cause` ,
				`content` ,
				`file_dir` ,
				`submit_date` ,
				`date_of_issue` ,
				`read`,
				`student_show`,
				`response`,
				`status`
				)
				VALUES (
				NULL , :course_id, :student_id, :lecturer_id, :cause, :content, :target_file,
				CURRENT_TIMESTAMP ,:date_of_issue, '0', '1', NULL, '0'
				);");

			$stmt->bindparam(":course_id", $course_id);
			$stmt->bindparam(":student_id", $user_id);
			$stmt->bindparam(":lecturer_id", $lecturer_id);
			$stmt->bindparam(":cause", $cause);
			$stmt->bindparam(":content", $message);
			$stmt->bindparam(":target_file", $target_file);
			$stmt->bindparam(":date_of_issue", $date_of_issue);
			$stmt->execute();

			return $stmt;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
	public function createAttendanceTable($user_id){
		$dowMap = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
		$att_arr = array();
		$course_day = array();

    $stmt = $this->conn->prepare("SELECT presence.course, presence.date, courses.course_name, courses.day_of_week
			FROM presence
			INNER JOIN courses ON presence.course = courses.course_id
			WHERE student = :user_id");
    $stmt->execute(array(':user_id' => $user_id));
    $result = $stmt->fetchall(PDO::FETCH_ASSOC);

		foreach ($result as $value) {
		  if (!array_key_exists($value['course_name'],$att_arr)){
		    $att_arr[$value['course_name']] ='';
		    $att_arr[$value['course_name']]++;
		  }else{
		    $att_arr[$value['course_name']]++;
		  }
		  if(!array_key_exists($value['course_name'], $course_day)){
		  $course_day[$value['course_name']] = $value['day_of_week']; //array of key = course name => value = day_of_week
		  }
		}


    return array($att_arr,$course_day);

  }

	public function getDay($day_num)
	{
		$arr = array('Sunday', 'Monday', 'Tuesday', 'Wedensday', 'Thursday', 'Friday', 'Saturday');
		return $arr[$day_num-1];
	}

  public function getCourseFirstDayInSemseter($day_of_week){
		$dowMap = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
		$day = $dowMap[$day_of_week-1];
		$date = date('Y-m-d', strtotime("next ".$day, strtotime(SEMESTER_START)));

		return $date;
	}

	public function getCoursesDates($user_id)
	{
		$courses = $this->getCourses($user_id);
		foreach($courses as $course){
			$dates[$course['course_name']] = array(); //dates of course in semseter
			$day = $course['day_of_week'];
			$date = $this->getCourseFirstDayInSemseter($day);

			while (strtotime($date) <= strtotime(SEMESTER_END)) {
				array_push($dates[$course['course_name']], $date);
				$date = date ("Y-m-d", strtotime("+7 day", strtotime($date)));
			}

		}
		try {
			if (isset($dates))
				return $dates;
		} catch (Exception $e) {
			echo $e->getMessage();
		}

	}

	public function countCourseDays($day){//$date is first day of course in semester and $day is day of week
	 	$ctr = 0;
		$date = $this->getCourseFirstDayInSemseter($day);
		while (strtotime($date) <= strtotime(SEMESTER_END)) {
			$ctr++;
			$date = date ("Y-m-d", strtotime("+7 day", strtotime($date)));
		}
		return $ctr;
	}

	public function ftp_update($img_name, $user_id, $img_path){
		// connect and login to FTP server
		$ftp_server = R_FTP;
		$ftp_conn = ftp_connect($ftp_server) or die("Could not connect to $ftp_server");
		$login = ftp_login($ftp_conn, R_USER, R_PASS);

		ftp_mkdir($ftp_conn, $user_id); //only for first image
		// upload file
		if (ftp_put($ftp_conn, $user_id."/".$img_name, $img_path, FTP_BINARY))

		  {
		  echo "Successfully uploaded $img_path.";
		  }
		else
		  {
		  echo "Error uploading $img_path.";
		  }

		// close connection
		ftp_close($ftp_conn);
	}

	// public function kairosEnroll($user_id, $pic_id)
	// {
	// $subject_id = $user_id."-a".$pic_id;
	// // The data to send to the API
	// $postData = array(
	// 	 "image" => "http://104.131.0.21/rollcall/uploads/images/users/".$subject_id.".png",
	// 	 "subject_id" => $user_id.'-a'.$pic_id,
	// 	 "gallery_name" => KAIROS_GALLERY,
	// 	 "selector" => "SETPOSE",
	// 	 "symmetricFill" => "true"
	// );
	//
	// 	$url = "https://api.kairos.com/enroll";
  //   $curl = curl_init($url);
	//
	// 	// Setup cURL
	// 	$ch = curl_init($url);
	// 	curl_setopt_array($ch, array(
	// 	    CURLOPT_POST => TRUE,
	// 	    CURLOPT_RETURNTRANSFER => TRUE,
	// 	    CURLOPT_HTTPHEADER => array(
	// 				'Content-Type:  application/json',
	// 				'app_id:'.APP_ID,
	// 				'app_key:'.APP_KEY
	// 	    ),
	// 	    CURLOPT_POSTFIELDS => json_encode($postData)
	// 	));
	//
	// 	// Send the request
	// 	$response = curl_exec($ch);
	//
	// 	// Check for errors
	// 	if($response === FALSE){
	// 	    die(curl_error($ch));
	// 	}
	//
	// 	// Decode the response
	// 	$responseData = json_decode($response, TRUE);
	//
	//
	// 	}

}
?>
