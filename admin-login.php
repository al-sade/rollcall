<?php
session_start();
require_once("classes/class.admin.php");

$login = new ADMIN();

if($login->is_loggedin()!="")
{
	$login->redirect('admin/dashboard.php');
}

if(isset($_POST['btn-login']))
{
	$admin_id = strip_tags($_POST['admin_id']);
	$pass = strip_tags($_POST['password']);

	if($login->doLogin($admin_id,$pass))
	{
		$login->redirect('admin/dashboard.php');
	}
	else
	{
		$error = "Wrong Details or Not Allowed";
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>RollCall : Login</title>
<link href="vendor/twbs/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet" media="screen">
<link href="vendor/twbs/bootstrap/dist/css/bootstrap-theme.min.css" rel="stylesheet" media="screen">
<script type="text/javascript" src="vendor/components/jquery/jquery.min.js"></script>
<link rel="stylesheet" href="style.css" type="text/css"  />
</head>
<body>

<div class="signin-form">
	<div class="container">
       <form class="form-signin" method="post" id="login-form">
        <h2 class="form-signin-heading">Rollcall - Admin Section</h2><hr />
        <div id="error">
        <?php
			if(isset($error))
			{
				?>
                <div class="alert alert-danger">
                   <i class="glyphicon glyphicon-warning-sign"></i> &nbsp; <?php echo $error; ?> !
                </div>
                <?php
			}
		?>
        </div>

				<div class="form-group">
				<input type="number" id="admin_id" class="form-control" name="admin_id" placeholder="Admin ID" value="<?php if(isset($error)){echo $admin_id;}?>" required/>
				</div>

				<div class="form-group">
        <input type="password" class="form-control" name="password" placeholder="Admin Password" />
        </div>

     	<hr />

        <div class="form-group">
            <button type="submit" name="btn-login" class="btn btn-default">
                	<i class="glyphicon glyphicon-log-in"></i> &nbsp; SIGN IN
            </button>
        </div>
      </form>
    </div>
</div>

</body>
</html>
s
