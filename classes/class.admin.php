<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/rollcall/dbconfig.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/rollcall/config.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/rollcall/classes/class.user.php');

class ADMIN extends USER
{

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
