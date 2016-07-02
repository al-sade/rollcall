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


    <div class="container-fluid">

    <div class="container ddddd">

			<?php
				if(isset($_SESSION['lecturer'])){
					require_once('lecturer-courses.php');
				} else{
					require_once('student-courses.php');
				}
			?>

    </div>

</div>

<?php require_once('footer.php') ?>
<!--  -->
</body>
</html>
