<?php
use Ghunti\HighchartsPHP\Highchart;
use Ghunti\HighchartsPHP\HighchartJsExpr;

$dowMap = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
$lecture_courses = $auth_user->getLecturerCourses($user_id);

$lecture_students = array(); // array{[course name] => array[students list](student_id, name)}
$course_chart = array(); //contain the charts

//Highchart() for each course
foreach($lecture_courses as $course){


 //store course detials
 $course_id = $course['course_id'];
 $course_name = $course['course_name'];
 $course_day = $course['day_of_week'];

 $numOfLectures = $auth_user->countCourseDays($course_day);

 //get all students for each course
 //and store in [course_name] => array of student
 $lecture_students[$course_name]  = $auth_user->getCourseStudents($course_id, $course_day);
 $students_list = array(); //chart categories as students names
 $presence = array();
 $absence = array();

 foreach($lecture_students[$course_name] as $student){
   $student_id = $student['student'];

   $name = $student['first_name']." ".$student['last_name'];
   array_push($students_list, $name);
   array_push($presence, count($auth_user->getUserCoursePresence($student_id, $course_id)));
   array_push($absence, $numOfLectures - count($auth_user->getUserCoursePresence($student_id, $course_id)));

 }

 $course_chart[$course_name] = new Highchart();
 $course_chart[$course_name]->chart->renderTo = "container_".$course_id;
 $course_chart[$course_name]->chart->type = "column";
 $course_chart[$course_name]->title->text = $course_name;
 $course_chart[$course_name]->xAxis->categories = $students_list; //students list
 $course_chart[$course_name]->yAxis->min = 0;
 $course_chart[$course_name]->yAxis->title->text = "Day Number";
 $course_chart[$course_name]->tooltip->formatter = new HighchartJsExpr("function() {
     return '' + this.series.name +': '+ this.y +'';}");
 $course_chart[$course_name]->legend->backgroundColor = "#F1F9F9";
 $course_chart[$course_name]->chart->backgroundColor = "#F1F9F9";
 $course_chart[$course_name]->legend->reversed = 1;
 $course_chart[$course_name]->plotOptions->series->stacking = "normal";
 $course_chart[$course_name]->series[] = array(
     'name' => "Absent",
     'data' => $absence,
     'color' => "#434348"
 );

 $course_chart[$course_name]->series[] = array(
     'name' => "Present",
     'data' => $presence
 );
}

?>
