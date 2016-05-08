<?php
require_once("../session.php");
require_once("../vendor/autoload.php");
require_once("../classes/class.stats.php");


	require_once("../session.php");

	if(isset($_SESSION['lecturer'])){
		require_once("init-lecturer.php");
		$auth_user = new LECTURER();
		$is_lecturer = 1;
		require_once("lecturer-stats.php"); //new Highchart Object
	}else{
		require_once("init-user.php");
		$is_lecturer = 0;
		$auth_user = new USER();
		require_once("student-stats.php"); //new Highchart Object
	}

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

<!-- if lecturer -->
<?php if($is_lecturer){ foreach($lecture_courses as $course){ ?>

<div class="clearfix"></div>
<div class="container-fluid" style="margin-top:80px;">
    <div class="container">
			<?php	$course_chart[$course['course_name']]->printScripts(); ?>
			<div id="container_<?php echo $course['course_id'] ?>"></div>
    </div>
</div>
<script type="text/javascript"><?php echo $course_chart[$course['course_name']]->render("chart1"); ?></script>
<!-- End of lecturer -->

<!-- if student -->
<?php } } else { 	?>

	<div class="clearfix"></div>
	<div class="container-fluid" style="margin-top:80px;">
	    <div class="container">
				<?php	$course_chart->printScripts();	?>
				<div id="container"></div>
	    </div>
	</div>
	<script type="text/javascript"><?php echo $course_chart->render("chart1"); ?></script>

<?php } ?>
<!-- End of student -->

<script src="../vendor/twbs/bootstrap/dist/js/bootstrap.min.js"></script>
</body>
</html>
