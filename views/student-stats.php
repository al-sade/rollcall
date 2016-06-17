<?php
use Ghunti\HighchartsPHP\Highchart;
use Ghunti\HighchartsPHP\HighchartJsExpr;

$dowMap = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
$student_id = $userRow['user_id'];
$student_name = $userRow['first_name']." ".$userRow['last_name'];
$courses = $auth_user->getCourses($userRow['user_id']);
$courses_list = array();
$presence = array();
$appealed = array();
$absence = array();
foreach($courses as $course){
  array_push($courses_list, $course['course_name']);

  //store course detials
  $course_id = $course['course_id'];
  $course_name = $course['course_name'];
  $course_day = $course['day_of_week'];

  $numOfLectures = $auth_user->countCourseDays($course_day);
  $numOfAttendedByAppeal = count($auth_user->getAttendedByAppeal($student_id, $course_id));
  $numOfPresence = count($auth_user->getUserCoursePresence($student_id, $course_id)) + $numOfAttendedByAppeal;
  $numOfAppealed = count($auth_user->getAcceptedAppeals($student_id, $course_id));

  array_push($presence, $numOfPresence);
  array_push($appealed, $numOfAppealed);
  array_push($absence, $numOfLectures - $numOfPresence - $numOfAppealed);

}

 $course_chart = new Highchart();
 $course_chart->chart->renderTo = "container";
 $course_chart->chart->type = "column";
 $course_chart->title->text = "Courses";
 $course_chart->xAxis->categories = array_values($courses_list);
 $course_chart->yAxis->min = 0;
 $course_chart->yAxis->title->text = "Day Number";
 $course_chart->tooltip->formatter = new HighchartJsExpr("function() {
     return '' + this.series.name +': '+ this.y +'';}");
 $course_chart->legend->backgroundColor = "#F1F9F9";
 $course_chart->chart->backgroundColor = "#F1F9F9";
 $course_chart->legend->reversed = 1;
 $course_chart->plotOptions->series->stacking = "normal";
 $course_chart->series[] = array(
     'name' => "Absent",
     'data' => $absence,
     'color' => "#434348"
 );
 $course_chart->series[] = array(
     'name' => "Appealed",
     'data' => $appealed,
     'color' => "#90ED7D"
 );
 $course_chart->series[] = array(
     'name' => "Present",
     'data' => $presence
 );


 ?>
