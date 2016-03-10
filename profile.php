<?php

	require_once("session.php");

	require_once("class.user.php");
	$auth_user = new USER();


	$user_id = $_SESSION['user_session'];

	$stmt = $auth_user->runQuery("SELECT * FROM users WHERE user_id=:user_id");
	$stmt->execute(array(":user_id"=>$user_id));

	$userRow=$stmt->fetch(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
<link href="vendor/twbs/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet" media="screen">
<script type="text/javascript" src="components/jquery/jquery.min.js"></script>
<link href="vendor/twbs/bootstrap/dist/css/bootstrap-theme.min.css" rel="stylesheet" media="screen">
<link rel="stylesheet" href="style.css" type="text/css"  />
<title>welcome - <?php print($userRow['email']); ?></title>

<!-- Calendar Section -->
<link href='js/calendar/fullcalendar.css' rel='stylesheet' />
<link href='js/calendar/fullcalendar.print.css' rel='stylesheet' media='print' />
<script src='js/calendar/lib/moment.min.js'></script>
<script src='js/calendar/fullcalendar.min.js'></script>
<script>

	jQuery(document).ready(function() {

		jQuery('#calendar').fullCalendar({
			defaultDate: '2016-01-12',
			editable: true,
			eventLimit: true, // allow "more" link when too many events
			events: [
				{
					title: 'Physics',
					start: '2016-01-12T20:00:00'
				},
				{
					title: 'Math',
					start: '2016-01-10T10:00:00'
				},
				{
					title: 'History',
					start: '2016-01-13T07:00:00'
				}
			]
		});

	});

</script>

<!-- End of calendar section -->
</head>

<body>


<?php require_once('header.php');?>

	<div class="clearfix"></div>

    <div class="container-fluid" style="margin-top:80px;">

    <div class="container">

			<ul>
    	<?php
			 $row = '<li>First Name: '.$userRow['first_name'].'</li>';
			 $row .= '<li>Last Name: '.$userRow['last_name'].'</li>';
			 $row .= '<li>Age: ';
			 $bday = new DateTime($userRow['bday']);
			 $today   = new DateTime('today');
			 $row .= $bday->diff($today)->y;
			 $row .= '</li>';
			 $row .= '<li>Department: '.$userRow['department'].'</li>';
			 $row .= '<li>eMail: '.$userRow['email'].'</li>';
			 $row .= '<li>Year Of Study: ';
			 $begin_studying = new DateTime($userRow['begin_studying']);
			 $row .= $begin_studying->diff($today)->y;
			 $row	.= '</li>';
			 echo $row;
			?>
			</ul>

			<div id='calendar'></div>

    </div>

</div>




<script src="vendor/twbs/bootstrap/dist/js/bootstrap.min.js"></script>

</body>
</html>
