<?php
session_start();
require_once('class.user.php');
$user = new USER();

if($user->is_loggedin()!="")
{
	$user->redirect('home.php');
}

if(isset($_POST['btn-signup']))
{
	$id_number = strip_tags($_POST['id_number']);
	$first_name = strip_tags($_POST['first_name']);
	$last_name = strip_tags($_POST['last_name']);
	$email = strip_tags($_POST['email']);
	$bday = strip_tags($_POST['bday']);
	$begin_studying = strip_tags($_POST['begin_studying']);
	$department = strip_tags($_POST['department']);
	$pass = strip_tags($_POST['pass']);





		try
		{
			$stmt = $user->runQuery("SELECT id_number, email FROM users WHERE id_number=:id_number OR email=:email");
			$stmt->execute(array(':id_number'=>$id_number, ':email'=>$email));
			$row=$stmt->fetch(PDO::FETCH_ASSOC);

			if($row['id_number']==$id_number) {
				$error[] = "sorry username already taken !";
			}
			else if($row['email']==$email) {
				$error[] = "sorry email id already taken !";
			}
			else
			{
				if($user->register($id_number,$first_name,$last_name,$email,$bday,$begin_studying,$department,$pass)){

					$user->redirect('sign-up.php?joined');
				}
			}
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}

}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>RollCall : Sign up</title>
<script src="vendor/components/jquery/jquery.min.js"></script>
<link href="vendor/twbs/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet" media="screen">
<link href="vendor/twbs/bootstrap/dist/css/bootstrap-theme.min.css" rel="stylesheet" media="screen">
<link rel="stylesheet" href="style.css" type="text/css"  />
</head>
<body>

<div class="signin-form">

<div class="container">

        <form method="post" class="form-signin">
            <h2 class="form-signin-heading">Sign up.</h2><hr />
            <?php
			if(isset($error))
			{
			 	foreach($error as $error)
			 	{
					 ?>
                     <div class="alert alert-danger">
                        <i class="glyphicon glyphicon-warning-sign"></i> &nbsp; <?php echo $error; ?>
                     </div>
                     <?php
				}
			}
			else if(isset($_GET['joined']))
			{

				 ?>
                 <div class="alert alert-info">
                      <i class="glyphicon glyphicon-log-in"></i> &nbsp; Successfully registered <a href='index.php'>login</a> here
                 </div>
                 <?php
			}
			?>
						<div class="form-group">
						<input type="number" id="user_id" class="form-control" name="id_number" placeholder="Enter ID Number" value="<?php if(isset($error)){echo $id_number;}?>" required/>
						</div>
            <div class="form-group">
            <input type="text" class="form-control" name="first_name" placeholder="Enter First Name" value="<?php if(isset($error)){echo $first_name;}?>" required/>
            </div>
						<div class="form-group">
						<input type="text" class="form-control" name="last_name" placeholder="Enter Last Name" value="<?php if(isset($error)){echo $last_name;}?>" required/>
						</div>
						<div class="form-group">
						<input type="email" class="form-control" name="email" placeholder="Enter E-Mail ID" value="<?php if(isset($error)){echo $email;}?>" required/>
						</div>
						<div class="form-group">
						<input type="text" class="form-control" name="bday" placeholder="Date Of Birth" value="<?php if(isset($error)){echo $date_of_birth;}?>" onfocus="(this.type='date')" required/>
						</div>
						<div class="form-group">
						<input type="text" class="form-control" name="begin_studying" placeholder="Beginning Of Studying" value="<?php if(isset($error)){echo $date_of_birth;}?>" onfocus="(this.type='date')" required/>
						</div>
						<div class="form-group">
							<select name="department" class="form-control" required>
								<option value="" disabled selected>Select Department</option>
								<option value="Software Engineering">Software Engineering</option>
								<option value="Electrical Engineering">Electrical Engineering</option>
								<option value="Biomedical Engineering">Biomedical Engineering</option>
								<option value="Architecture">Architecture</option>
								<option value="History">History</option>
								<option value="Economics">Economics</option>
							</select>
						</div>
            <div class="form-group">
            	<input minlength=8 type="password" class="form-control" name="pass" placeholder="Enter Password" required/>
            </div>

						<div class="form-group">
						<div id="camera">
							<div class="center clear">
								<video id="video" class="picCapture" autoplay></video>
								<button id="snap" onclick="return false;">Take Picture</button>
								<canvas id="canvas" class="picCapture"></canvas>
							</div>
						</div>
					</div>

						<div class="form-group">
            	<button id="form-submit" type="submit" class="btn btn-primary" name="btn-signup">
                	<i class="glyphicon glyphicon-open-file"></i>&nbsp;SIGN UP
                </button>
            </div>

            <label>have an account ! <a href="index.php">Sign In</a></label>
        </form>

       </div>
</div>

</div>
<script src="js/catch-pic.js"></script>
</body>
</html>
