<?php
require_once("../session.php");
require_once("../vendor/autoload.php");
	require_once("../classes/class.stats.php");

	use Ghunti\HighchartsPHP\Highchart;
	use Ghunti\HighchartsPHP\HighchartJsExpr;

	require_once("../session.php");

	if(isset($_SESSION['lecturer'])){
		require_once("init-lecturer.php");
		$auth_user = new LECTURER();
	}else{
		require_once("init-user.php");;
		$auth_user = new USER();
	}
	$attedance_sum = $auth_user->createAttendanceTable($userRow['user_id']);
	$att_arr = array();


	$dowMap = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
	$course_day = array();

	foreach ($attedance_sum as $value) {
		if (!array_key_exists($value['course_name'],$att_arr)){
			$att_arr[$value['course_name']] ='';
			$att_arr[$value['course_name']]++;
		}else{
			$att_arr[$value['course_name']]++;
		}
		if(!array_key_exists($value['course_name'], $course_day)){
		$course_day[$value['course_name']] = $value['day_of_week']; //array of key = course name value = day_of_week
		}
	}

	$courses = array_keys($att_arr);
	$present = array_values($att_arr);
	$date_arr = array(); //course dates between SEMESTER_START to SEMESTER_END

	foreach($courses as $course){
			$day = $dowMap[$course_day[$course]-1];
			$date = date('Y-m-d', strtotime("next ".$day, strtotime(SEMESTER_START))); //first lectur in semester

			while (strtotime($date) <= strtotime(SEMESTER_END)) {
				if(!array_key_exists($course, $date_arr)){
					$date_arr[$course] = '';
					$date_arr[$course]++;
				}else{
					$date_arr[$course]++;
				}
				$date = date ("Y-m-d", strtotime("+7 day", strtotime($date)));
			}
			$absent_arr[$course] = $date_arr[$course] - $att_arr[$course];
		}
		$absent_arr = array_values($absent_arr);
	$chart = new Highchart();
	$chart->chart->renderTo = "container";
	$chart->chart->type = "bar";
	$chart->title->text = "Class Attendance";
	$chart->xAxis->categories = $courses;
	$chart->yAxis->min = 0;
	$chart->yAxis->title->text = "Number of classes";
	$chart->tooltip->formatter = new HighchartJsExpr("function() {
			return '' + this.series.name +': '+ this.y +'';}");
			$chart->legend->backgroundColor = "#F1F9F9";
			$chart->chart->backgroundColor = "#F1F9F9";
	$chart->legend->reversed = 1;
	$chart->plotOptions->series->stacking = "normal";
	$chart->series[] = array(
	    'name' => "Absent",
	    'data' => $absent_arr
	);

	$chart->series[] = array(
	    'name' => "Present",
	    'data' => $present
	);

	?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../vendor/twbs/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet" media="screen">
<link href="../vendor/twbs/bootstrap/dist/css/bootstrap-theme.min.css" rel="stylesheet" media="screen">
<script type="text/javascript" src="../components/jquery/jquery.min.js"></script>
<link rel="stylesheet" href="../style.css" type="text/css"  />
<title>welcome - <?php print($userRow['email']); ?></title>
</head>

<body>

<?php require_once('header.php');?>

<div class="clearfix"></div>
<div class="container-fluid" style="margin-top:80px;">
    <div class="container">
			<?php
				$chart->printScripts();
			?>
			<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
    </div>
</div>

<script src="../vendor/twbs/bootstrap/dist/js/bootstrap.min.js"></script>
<div id="container"></div>

<script type="text/javascript"><?php echo $chart->render("chart1"); ?></script>
</body>
</html>
