<?php
  require_once '../session.php';
  require '../vendor/autoload.php';
  require_once $_SERVER['DOCUMENT_ROOT'].'/rollcall/classes/class.admin.php';

  $auth_user = new ADMIN();
  $admin_id = $_SESSION['user_session'];

  $stmt = $auth_user->runQuery('SELECT * FROM admins WHERE admin_id=:admin_id');
  $stmt->execute(array(':admin_id' => $admin_id));
  $userRow = $stmt->fetch(PDO::FETCH_ASSOC);

  $cameras = $auth_user->getCameras();

  if (isset($_POST['btn-execute'])) {
      // $department = strip_tags($_POST['department']);
      // $students = $auth_user->getStudents($department);
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
<title>rollcall - Admin Area</title>
</head>

<body>

<?php require_once 'admin-header.php';?>

<div class="clearfix"></div>
<div class="container-fluid">
    <div class="container">
	    <h2>Manage Cameras</h2>
	    <table  class="table table-bordered">
	      <thead>
	        <tr>
	          <th>Camera Number</th>
	          <th>Day Of Week</th>
	          <th>Start</th>
	          <th>End</th>
	          <th>Course</th>
	        </tr>
	      </thead>
	      <tbody>
		    <?php
              foreach ($cameras as $camera) {
                  $output = '<tr>';
                  $output .= '<td class="cameras-table" contenteditable>'.$camera['camera'].'</td>';
                  $output .= '<td>'.$auth_user->getDay($camera['day_of_week']).'</td>';
                  $output .= '<td>'.$camera['open_time'].'</td>';
                  $output .= '<td>'.$camera['close_time'].'</td>';
                  $output .= '<td>'.$camera['course_name'].'</td>';
                  $output .= '</tr>';
                  echo $output;
              }
            ?>
		  </tbody>
		  </table>
    </div>
</div>

<?php require_once('footer.php') ?>

</body>
</html>
