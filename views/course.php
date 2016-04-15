<?php
	require_once("../session.php");
	require_once("../classes/class.user.php");
	require '../vendor/autoload.php';

	$auth_user = new USER();
	$user_id = $_SESSION['user_session'];

  $course_id = $_GET['cid'];
  $course_data = $auth_user->getCourse($user_id,$course_id);
  $dowMap = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');


	$stmt = $auth_user->runQuery("SELECT * FROM users WHERE user_id=:user_id");
	$stmt->execute(array(":user_id"=>$user_id));

	$userRow=$stmt->fetch(PDO::FETCH_ASSOC);

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

      <h1><?php echo($course_data[0]['course_name']); ?></h1>
      <ul>
        <li>Lecturer: <?php echo $course_data[0]['first_name'].$course_data[0]['last_name']; ?></li>
        <li>Day: <?php $day = $course_data[0]['day_of_week']; echo $dowMap[$day]; ?></li>
        <li>Start: <?php echo $course_data[0]['start'];?></li>
        <li>End: <?php echo $course_data[0]['end'];?></li>
      </ul>
    </div>

</div>

<script src="../vendor/twbs/bootstrap/dist/js/bootstrap.min.js"></script>

</body>
</html>
