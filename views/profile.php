<?php

	require_once("../session.php");

	if(isset($_SESSION['lecturer'])){
		require_once("init-lecturer.php");
		$auth_user = new LECTURER();
	}else{
		require_once("init-user.php");;
		$auth_user = new USER();
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../vendor/twbs/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet" media="screen">
<script type="text/javascript" src="../components/jquery/jquery.min.js"></script>
<link href="../vendor/twbs/bootstrap/dist/css/bootstrap-theme.min.css" rel="stylesheet" media="screen">
<link rel="stylesheet" href="../style.css" type="text/css"  />
<title>welcome - <?php print($userRow['email']); ?></title>

</head>

<body>


<?php require_once('header.php');?>

	<div class="clearfix"></div>

    <div class="container-fluid" style="margin-top:80px;">

    <div class="container">
			<div class="details">
				<ul>
	    	<?php
			   $row = '<li><img src="/rollcall/uploads/images/users/'.$userRow['id_number'].'.png"</li>';
				 $row .= '<li>First Name: '.$userRow['first_name'].'</li>';
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
			</div>

			<div id="scehdule">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th>Course</th>
							<th>Day</th>
							<th>Start</th>
							<th>End</th>
						</tr>
					</thead>
						<tbody>
							<?php
							$schedule = ($auth_user->getSchedule($user_id));
							foreach ($schedule as $class) {
								$output = "<tr><td>".$class['course_name']."</td>";
								$output .= "<td>".$class['day_of_week']."</td>";
								$output .= "<td>".$class['start']."</td>";
								$output .= "<td>".$class['end']."</td></tr>";
								echo $output;
							}
							?>
						</tbody>
			</div>


    </div>

</div>

<script src="../vendor/twbs/bootstrap/dist/js/bootstrap.min.js"></script>

</body>
</html>
