<?php

	require_once("../session.php");
	require '../vendor/autoload.php';

	if(isset($_SESSION['lecturer'])){
		require_once("init-lecturer.php");
	}else{
		require_once("init-user.php");
	}

	$user_id = $_SESSION['user_session'];
	$appeals = $auth_user->getAppeals($user_id);

	if(isset($_POST['approve']) || isset($_POST['decline'])){
		$response = $_POST['response'];
		if(isset($_POST['approve'])) {
			$appeal_id = $_POST['approve'];
			$status = 1;
		} else{
				$status = 0;
				$appeal_id = $_POST['decline'];
			}
		try{
			$auth_user->appealReply($appeal_id, $response, $status);
		}
		catch(Exception $e){
			echo 'Message: ' .$e->getMessage();
		}
	}
?>

<?php require_once('head.php');?>
<?php require_once('nav.php');?>

    <div class="clearfix"></div>


<div class="container-fluid">

    <div class="container spacer">
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

<?php require_once('footer.php') ?>
<script type="text/javascript" src="../js/handle-appeal.js"></script>

</body>
</html>
