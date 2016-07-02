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

<?php require_once('head.php');?>
<?php require_once('nav.php');?>

<!-- if lecturer -->
<?php if($is_lecturer){ foreach($lecture_courses as $course){ ?>

<div class="clearfix"></div>
<div class="container-fluid">
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
	<div class="container-fluid">
	    <div class="container">
				<?php	$course_chart->printScripts();	?>
				<div id="container"></div>
	    </div>
	</div>
	<script type="text/javascript"><?php echo $course_chart->render("chart1"); ?></script>

<?php } ?>
<!-- End of student -->
<?php require_once('footer.php') ?>
</body>
</html>
