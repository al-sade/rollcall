<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/rollcall/dbconfig.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/rollcall/config.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/rollcall/classes/class.user.php');

class LECTURER extends USER
{
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
  $stmt = $this->conn->prepare("SELECT appeals.appeal_id, appeals.course_id, appeals.student_id,appeals.student_id, appeals.content, appeals.submit_date, appeals.read, appeals.approved,users.first_name, users.last_name,courses.course_name
  FROM appeals
  INNER JOIN users ON appeals.student_id = users.user_id
  INNER JOIN courses ON appeals.course_id = courses.course_id
  WHERE appeals.lecturer_id = :lecturer_id AND appeals.read != 1");
  $stmt->execute(array(':lecturer_id' => $user_id));
  $result = $stmt->fetchall(PDO::FETCH_ASSOC);

  return $result;
}

public function appealReply($appeal_id, $response, $status){
  $stmt = $this->conn->prepare("UPDATE appeals
    SET `read` = 1, `response` = :response, `approved` = :approved
    WHERE `appeal_id` = :appeal_id");
  $stmt->execute(array(':response' => $response, ':approved' => $status, ':appeal_id' => $appeal_id));

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
