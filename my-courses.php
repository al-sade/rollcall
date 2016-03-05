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
<link href="vendor/twbs/bootstrap/dist/css/bootstrap-theme.min.css" rel="stylesheet" media="screen">
<script type="text/javascript" src="components/jquery/jquery.min.js"></script>
<link rel="stylesheet" href="style.css" type="text/css"  />
<title>welcome - <?php print($userRow['email']); ?></title>
</head>

<body>


<?php require_once('header.php');?>

	<div class="clearfix"></div>

    <div class="container-fluid" style="margin-top:80px;">

    <div class="container">

        <h2>My Courses</h2>
        <table class="table">
          <thead>
            <tr>
              <th>Course</th>
              <th>Lecturer</th>
              <th>Email</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $courses = $auth_user->getCourses($userRow["user_id"]);
            foreach($courses as $course){
              $row = '<tr>';
              $row .= '<td>'.$course["course_name"].'</td>';
              $row .= '<td>'.$course["first_name"]." ".$course["last_name"].'</td>';
              $row .= '<td>'.$course["email"].'</td>';
              $row .= '<tr>';
              echo $row;
            }
             ?>
          </tbody>
        </table>



    </div>

</div>




<script src="vendor/twbs/bootstrap/dist/js/bootstrap.min.js"></script>

</body>
</html>
