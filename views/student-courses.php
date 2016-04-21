<?php
$output = '<h2>My Courses</h2>';
$output .= '<table class="table">';
$output .=  '<thead>';
$output .=  '<tr>';
$output .=  '<th>Course</th>';
$output .=  '<th>Lecturer</th>';
$output .=  '<th>Email</th>';
$output .=  '</tr>';
$output .=  '</thead>';
$output .=  '<tbody>';

echo $output;

    $courses = $auth_user->getCourses($userRow["user_id"]);
    foreach($courses as $course){
      $row = '<tr>';
      $row .= '<td><a href="course.php?cid='.$course["course_id"].'">'.$course["course_name"].'</a></td>';
      $row .= '<td>'.$course["first_name"]." ".$course["last_name"].'</td>';
      $row .= '<td>'.$course["email"].'</td>';
      $row .= '<tr>';
      echo $row;
    }
    
$output .= '</tbody>';
$output .= '</table>';

?>
