<?php
	require_once("../session.php");
	require '../vendor/autoload.php';
  require_once($_SERVER['DOCUMENT_ROOT'].'/rollcall/classes/class.admin.php');

  $auth_user = new ADMIN();
  $admin_id = $_SESSION['user_session'];

  $stmt = $auth_user->runQuery("SELECT * FROM admins WHERE admin_id=:admin_id");
  $stmt->execute(array(":admin_id"=>$admin_id));
  $userRow=$stmt->fetch(PDO::FETCH_ASSOC);

	$departments = $auth_user->getDepartments();

	if(isset($_POST['btn-execute']))
	{
		$department = strip_tags($_POST['department']);
		$students = $auth_user->getStudents($department);
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

<?php require_once('admin-header.php');?>

<div class="clearfix"></div>

<div class="container-fluid" style="margin-top:80px;">

    <div class="container">

			<h2>List Students</h2>
			<form method="post" id="admin-filter">

			<div class="form-group">
				<label>Departments</label>
				<select class="form-control" name="department" id="doi">
					<?php
						echo '<option value="" disabled selected>Select Department</option>';
					  foreach ($departments as $department){
							echo '<option value="'.$department['name'].'">'.$department['name'].'</option>';
						}
					 ?>
				</select>
			</div>

			<hr />

			<div class="form-group">
					<button type="submit" name="btn-execute" class="btn btn-default">
								<i class="glyphicon glyphicon-log-in"></i> &nbsp; GO
					</button>
			</div>
			<br />
		</form>

    </div>

		<div class="container">
			<table class="table table-bordered">
				<thead>
					<tr>
						<th>ID Number</th>
						<th>Name</th>
						<th>eMail</th>
						<th>Date Of Birth</th>
						<th>Begin Studying</th>
						<th>Department</th>
					</tr>
				</thead>
				<tbody>
			<?php
			if(isset($students)){
				foreach($students as $student){
					$output = '<tr>';
					$output .= '<td>'.$student['id_number'].'</td>';
					$output .= '<td>'.$student['first_name']." ".$student['last_name'].'</td>';
					$output .= '<td>'.$student['email'].'</td>';
					$output .= '<td>'.$student['bday'].'</td>';
					$output .= '<td>'.$student['begin_studying'].'</td>';
					$output .= '<td>'.$student['department'].'</td>';
					$output .= '</tr>';
					echo $output;
				}
			}
			?>
		</tbody>
		</table>
		</div>

</div>

<script src="../vendor/twbs/bootstrap/dist/js/bootstrap.min.js"></script>

</body>
</html>
