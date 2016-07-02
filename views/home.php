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

<?php require_once('head.php');?>
<?php require_once('nav.php');?>

<div class="clearfix"></div>

<div class="container-fluid">

    <div class="container">

    	<h1>Welcome To Rollcall</h1>
			<h2>Registration Management Tool</h2>

    </div>

</div>

<?php require_once('footer.php') ?>

</body>
</html>
