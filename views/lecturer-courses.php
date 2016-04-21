<?php
$output = '<h2>My Courses</h2>';
$output .= '<table class="table">';
$output .=  '<thead>';
$output .=  '<tr>';
$output .=  '<th>Course</th>';
$output .=  '<th>Day</th>';
$output .=  '<th>Time</th>';
$output .=  '</tr>';
$output .=  '</thead>';
$output .=  '<tbody>';

echo $output;

    $courses = $auth_user->getLecturerCourses($userRow["user_id"]);
    foreach($courses as $course){
      
      $row = '<tr>';
      $row .= '<td><a href="course.php?cid='.$course["course_id"].'">'.$course["course_name"].'</a></td>';
      $row .= '<td>'.$auth_user->getDay($course["day_of_week"]).'</td>';
      $row .= '<td>'.$course["start"].' - '.$course["end"].'</td>';
      $row .= '<tr>';
      echo $row;
    }

$output .= '</tbody>';
$output .= '</table>';

?>