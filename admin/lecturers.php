<?php
  require_once '../session.php';
  require '../vendor/autoload.php';
  require_once $_SERVER['DOCUMENT_ROOT'].'/rollcall/classes/class.admin.php';

  $auth_user = new ADMIN();
  $admin_id = $_SESSION['user_session'];

  $stmt = $auth_user->runQuery('SELECT * FROM admins WHERE admin_id=:admin_id');
  $stmt->execute(array(':admin_id' => $admin_id));
  $userRow = $stmt->fetch(PDO::FETCH_ASSOC);

    $departments = $auth_user->getDepartments();

    if (isset($_POST['btn-execute'])) {
        $department = strip_tags($_POST['department']);
        $lecturers = $auth_user->getLecturers($department);
    }
    if (isset($_POST['btn-register'])) {
        $id_number = strip_tags($_POST['id_number']);
        $first_name = strip_tags($_POST['first_name']);
        $last_name = strip_tags($_POST['last_name']);
        $email = strip_tags($_POST['email']);
        $bday = strip_tags($_POST['bday']);
        $department = strip_tags($_POST['department']);
        $pass = strip_tags($_POST['pass']);

        try {
            $stmt = $auth_user->runQuery('SELECT id_number, email FROM users WHERE id_number=:id_number OR email=:email');
            $stmt->execute(array(':id_number' => $id_number, ':email' => $email));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row['id_number'] == $id_number) {
                $error[] = 'sorry lecturer id already in the system !';
            } elseif ($row['email'] == $email) {
                $error[] = 'sorry lecturer email already in the system !';
            } else {
                if ($auth_user->register($id_number, $first_name, $last_name, $email, $bday, $department, $pass)) {
                    echo 'Registered Successfully';
                }
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
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
<link rel="stylesheet" href="../style.css" type="text/css"  />
<title>rollcall - Admin Area</title>
</head>

<body>

<?php require_once 'admin-header.php';?>

<div class="clearfix"></div>
<div class="container-fluid" style="margin-top:80px;">
    <div class="container">
				<h2>List Lecturers</h2>
				<form method="post" id="admin-filter">
					<div class="form-group">
						<label>Departments</label>
						<select class="form-control" name="department" id="doi">
							<?php
		            echo '<option value="" disabled selected>Select Department</option>';
		            foreach ($departments as $department) {
		            echo '<option value="'.$department['name'].'">'.$department['name'].'</option>';
		            }
		          ?>
						</select>
					</div>
					<hr/>
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
						<th>Department</th>
					</tr>
				</thead>
				<tbody>
					<?php
			      if (isset($lecturers)) {
			          foreach ($lecturers as $lecturer) {
			              $output = '<tr>';
			              $output .= '<td>'.$lecturer['id_number'].'</td>';
			              $output .= '<td>'.$lecturer['first_name'].' '.$lecturer['last_name'].'</td>';
			              $output .= '<td>'.$lecturer['email'].'</td>';
			              $output .= '<td>'.$lecturer['bday'].'</td>';
			              $output .= '<td>'.$lecturer['department'].'</td>';
			              $output .= '</tr>';
			              echo $output;
			          }
			      }
		      ?>
				</tbody>
		</table>
		</div>

		<div class="container">
			<h2>Register Lecturer</h2>
			<form method="post" class="form-signin">
					<?php
            if (isset($error)) {
                foreach ($error as $error) {
          ?>
					 <div class="alert alert-danger">
							<i class="glyphicon glyphicon-warning-sign"></i> &nbsp; <?php echo $error;?>
							 </div>
							 <?php
            	}
            } elseif (isset($_GET['joined'])) {
                ?>
							 <div class="alert alert-info">
									<i class="glyphicon glyphicon-log-in"></i> &nbsp; Successfully registered
							 </div>
							 <?php } ?>

					<div class="form-group">
						<input type="number" id="user_id" class="form-control" name="id_number" placeholder="Enter ID Number" value="<?php if (isset($error)) {echo $id_number;}?>" required/>
					</div>
					<div class="form-group">
						<input type="text" class="form-control" name="first_name" placeholder="Enter First Name" value="<?php if (isset($error)) {echo $first_name;}?>" required/>
					</div>
					<div class="form-group">
						<input type="text" class="form-control" name="last_name" placeholder="Enter Last Name" value="<?php if (isset($error)) {echo $last_name;}?>" required/>
					</div>
					<div class="form-group">
						<input type="email" class="form-control" name="email" placeholder="Enter E-Mail ID" value="<?php if (isset($error)) {echo $email;}?>" required/>
					</div>
					<div class="form-group">
						<input type="text" class="form-control" name="bday" placeholder="Date Of Birth" value="<?php if (isset($error)) {echo $date_of_birth;}?>" onfocus="(this.type='date')" required/>
					</div>
					<div class="form-group">
						<select class="form-control" name="department" id="doi">
							<?php
	              echo '<option value="" disabled selected>Select Department</option>';
	              foreach ($departments as $department) {
	                  echo '<option value="'.$department['name'].'">'.$department['name'].'</option>';
	              }
	            ?>
						</select>
					</div>
					<div class="form-group">
						<input minlength=8 type="password" class="form-control" name="pass" placeholder="Enter Password" required/>
					</div>

					<div class="form-group">
						<button type="submit" name="btn-register" class="btn btn-default">
									<i class="glyphicon glyphicon-log-in"></i> &nbsp; GO
						</button>
					</div>
			</form>
		</div>
</div>

<script src="../vendor/twbs/bootstrap/dist/js/bootstrap.min.js"></script>

</body>
</html>
