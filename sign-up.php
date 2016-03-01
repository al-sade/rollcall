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
	$pass = strip_tags($_POST['pass']);

	if($id_number=="")	{
		$error[] = "provide ID number !";
	}
	else if($first_name=="")	{
		$error[] = "provide first name !";
	}
	else if($last_name=="")	{
		$error[] = "provide last_name !";
	}
	else if($email=="")	{
		$error[] = "provide email id !";
	}
	else if(!filter_var($email, FILTER_VALIDATE_EMAIL))	{
	    $error[] = 'Please enter a valid email address !';
	}
	else if($pass=="")	{
		$error[] = "provide password !";
	}
	else if(strlen($pass) < 6){
		$error[] = "Password must be atleast 6 characters";
	}
	else
	{
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
				if($user->register($id_number,$first_name,$last_name,$email,$pass)){

					$user->redirect('sign-up.php?joined');
				}
			}
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Coding Cage : Sign up</title>
<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
<link href="bootstrap/css/bootstrap-theme.min.css" rel="stylesheet" media="screen">
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
						<input type="text" class="form-control" name="id_number" placeholder="Enter ID Number" value="<?php if(isset($error)){echo $id_number;}?>" />
						</div>
            <div class="form-group">
            <input type="text" class="form-control" name="first_name" placeholder="Enter First Name" value="<?php if(isset($error)){echo $first_name;}?>" />
            </div>
						<div class="form-group">
						<input type="text" class="form-control" name="last_name" placeholder="Enter Last Name" value="<?php if(isset($error)){echo $last_name;}?>" />
						</div>
						<div class="form-group">
						<input type="text" class="form-control" name="email" placeholder="Enter E-Mail ID" value="<?php if(isset($error)){echo $email;}?>" />
						</div>
            <div class="form-group">
            	<input type="password" class="form-control" name="pass" placeholder="Enter Password" />
            </div>
            <div class="clearfix"></div><hr />
						<div id="camera">
						<script>
						z={analyticsID:"UA-2087880-2",pluginPath:"libs/curl/src/curl/plugin/",domain:"davidwalsh.name",loadSidebar:!(-1!=navigator.userAgent.toLowerCase().indexOf("googlebot")),d:document,w:this},z.baseUrl=z.themePath+"/js/",location.hostname.indexOf(z.domain)<0&&(z.isDebug=1,z.analyticsID=0),z.moo=z.baseUrl+"mootools-yui-compressed.js";z.analyticsID&&function(){!function(e,a,n,t,c,s,i){e.GoogleAnalyticsObject=c,e[c]=e[c]||function(){(e[c].q=e[c].q||[]).push(arguments)},e[c].l=1*new Date,s=a.createElement(n),i=a.getElementsByTagName(n)[0],s.async=1,s.src=t,i.parentNode.insertBefore(s,i)}(z.w,z.d,"script","//www.google-analytics.com/analytics.js","ga"),ga("create",z.analyticsID,z.domain),ga("send","pageview"),ga("set","nonInteraction",!0)}();
						</script>

						<div class="center clear">
						<div class="demo-wrapper">


						<div id="promoNode"></div>

						<video id="video" width="320" height="240" autoplay></video>
						<button id="snap" class="sexyButton">Snap Photo</button>
						<canvas id="canvas" width="320" height="240"></canvas>

						</div>
						</div>
						</div>
						<div class="form-group">
            	<button type="submit" class="btn btn-primary" name="btn-signup">
                	<i class="glyphicon glyphicon-open-file"></i>&nbsp;SIGN UP
                </button>
            </div>
            <br />

            <label>have an account ! <a href="index.php">Sign In</a></label>
        </form>

       </div>
</div>

</div>
<script src="js/catch-pic.js"></script>
<script>
!function(e){var t=e.createElement("link"),s="setAttribute";t[s]("type","text/css"),t[s]("rel","stylesheet"),t[s]("href","//netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css"),e.body.appendChild(t)}(z.d);!function(e){var t=e.documentElement,n="fonts-loaded";if(-1==t.className.indexOf(n)){var s=e.createElement("link"),a="setAttribute";s.onload=function(){t.className+=" "+n},s[a]("type","text/css"),s[a]("rel","stylesheet"),s[a]("href",z.themePath+"/fonts.css"),e.body.appendChild(s)}}(z.d);
</script>
</body>
</html>
