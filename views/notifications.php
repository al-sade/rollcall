<?php

	require_once("../session.php");

	if(isset($_SESSION['lecturer'])){
		require_once("init-lecturer.php");
	}else{
		require_once("init-user.php");
	}
	require '../vendor/autoload.php';


	$user_id = $_SESSION['user_session'];
	$appeals = $auth_user->getAppeals($user_id);

	// $stmt = $auth_user->runQuery("SELECT * FROM users WHERE user_id=:user_id");
	// $stmt->execute(array(":user_id"=>$user_id));
	//
	// $userRow=$stmt->fetch(PDO::FETCH_ASSOC);


	if(isset($_POST['approve']) || isset($_POST['decline'])){ //best practice??
		$response = $_POST['message'];
		if(isset($_POST['approve'])) {$status = 1;} else{$status = 0;} ;
		$appeal_id = '110';

		try{
			$auth_user->appealReply($appeal_id, $response, $status);
		}
		catch(Exception $e){
			echo 'Message: ' .$e->getMessage();
		}
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../vendor/twbs/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet" media="screen">
<link href="../vendor/twbs/bootstrap/dist/css/bootstrap-theme.min.css" rel="stylesheet" media="screen">
<script type="text/javascript" src="../components/jquery/jquery.min.js"></script>
<script type="text/javascript" src="../js/handle-appeal.js"></script>
<link rel="stylesheet" href="../style.css" type="text/css"  />
<title>welcome - <?php print($userRow['email']); ?></title>
</head>

<body>

<?php require_once('header.php');?>

    <div class="clearfix"></div>


<div class="container-fluid" style="margin-top:80px;">

    <div class="container">
			<h2>Notifications</h2>

			<?php
				if(isset($_SESSION['lecturer'])){
					require_once('lecturer-notifications.php');
				} else{
					require_once('student-notifications.php');
				}
			?>


				<div class="appeal-rep">

				</div>
    </div>

</div>

<script src="../vendor/twbs/bootstrap/dist/js/bootstrap.min.js"></script>

</body>
</html>
