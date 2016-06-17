<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/rollcall/dbconfig.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/rollcall/config.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/rollcall/classes/class.user.php');

class LECTURER extends USER
{

public function getCourse($course_id){
  $stmt = $this->conn->prepare("SELECT * FROM courses
  WHERE course_id =:course_id ");
  $stmt->execute(array(':course_id'=>$course_id));
  $userRow=$stmt->fetchall(PDO::FETCH_ASSOC);
  return $userRow;

}

public function getCourseCamera($course_id, $day_of_week, $open_time, $close_time){
  $stmt = $this->conn->prepare("SELECT * FROM cameras
  WHERE course_id =:course_id
  AND day_of_week = :day_of_week
  AND open_time = :open_time
  AND close_time = :close_time ");
  $stmt->execute(array(':course_id' => $course_id,
   ':day_of_week' => $day_of_week,
   ':open_time' => $open_time,
   ':close_time' => $close_time));
  $userRow=$stmt->fetchall(PDO::FETCH_ASSOC);

  return $userRow;
}

public function getCourseStudents($course_id, $day_of_week){
  $stmt = $this->conn->prepare("SELECT students_courses.student, users.first_name, users.last_name
  FROM students_courses
  INNER JOIN users ON students_courses.student = users.user_id
  WHERE course =:course_id AND day_of_week = :day_of_week");
  $stmt->execute(array(':course_id'=>$course_id, ':day_of_week'=>$day_of_week));
  $userRow=$stmt->fetchall(PDO::FETCH_ASSOC);

  return $userRow;
}
//get presence for a specific course
public function getCoursePresence($course_id){
  $stmt = $this->conn->prepare("SELECT presence.student, presence.date, users.first_name, users.last_name
  FROM presence
  INNER JOIN users ON presence.student = users.user_id
  WHERE course = :course_id");
  $stmt->execute(array(':course_id'=>$course_id));
  $userRow=$stmt->fetchall(PDO::FETCH_ASSOC);
  return $userRow;

}

public function getLecturerCourses($user_id){
  $stmt = $this->conn->prepare("SELECT * FROM courses Where lecturer_id = :lecturer_id");
  $stmt->execute(array(':lecturer_id' => $user_id));
  $result = $stmt->fetchall(PDO::FETCH_ASSOC);

  return $result;
}
public function getDb(){
  return $this->$database;
}
public function getAppeals($user_id){
  $stmt = $this->conn->prepare("SELECT appeals.appeal_id, appeals.course_id, appeals.student_id,appeals.student_id,
                                       appeals.content, appeals.submit_date,appeals.date_of_issue, appeals.read,appeals.file_dir,
                                       appeals.status, users.first_name, users.last_name,courses.course_name
  FROM appeals
  INNER JOIN users ON appeals.student_id = users.user_id
  INNER JOIN courses ON appeals.course_id = courses.course_id
  WHERE appeals.lecturer_id = :lecturer_id AND appeals.read != 1");
  $stmt->execute(array(':lecturer_id' => $user_id));
  $result = $stmt->fetchall(PDO::FETCH_ASSOC);

  return $result;
}

public function appealReply($appeal_id, $response, $status){
  if($status){
    $stmt = $this->conn->prepare("UPDATE appeals
        SET `read` = 1, `student_show` = 1, `response` = :response, `status` = `cause`
        WHERE `appeal_id` = :appeal_id");
  }else{
    $stmt = $this->conn->prepare("UPDATE appeals
        SET `read` = 1, `student_show` = 1, `response` = :response, `status` = 0
        WHERE `appeal_id` = :appeal_id");

  }
    $stmt->execute(array(':response' => $response, ':appeal_id' => $appeal_id));

  return $stmt;
}

public function approveAppeal($appeal_id){
  $stmt = $this->conn->prepare("INSERT INTO  presence
  SELECT student_id, course_id, date_of_issue
  FROM appeals
  WHERE appeal_id = :appeal_id");
  $stmt->execute(array(':appeal_id' => $appeal_id));

  return $stmt;
}

public function isAdmin(){
  if (isset($_SESSION['admin'])) TRUE : FALSE ;
}

public function getAdminCourses($admin_id){
  $stmt = $this->conn->prepare("SELECT courses.course_name, lecturers.first_name, lecturers.last_name,lecturers.email,courses.course_id
  FROM students_courses
  INNER JOIN courses ON students_courses.course = courses.course_id
  INNER JOIN lecturers ON courses.lecturer = lecturers.id
  WHERE student =:student_id ");
  $stmt->execute(array(':student_id'=>$admin_id));
  $adminRow=$stmt->fetchall(PDO::FETCH_ASSOC);

  return $adminRow;
}
}

?>
