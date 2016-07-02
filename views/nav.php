
<nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <div id="logo-container">
          <img src="../uploads/images/rollcall_logo.png">
          </div>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li><a href="home.php">Home</a></li>
            <li><a href="profile.php">Profile</a></li>
            <li><a href="statistics.php">Statistics</a></li>
            <li><a href="my-courses.php">My Courses</a></li>
            <?php if(!isset($_SESSION['lecturer'])){ ?>   <li><a href="calendar.php">My Calendar</a></li> <?php }?>
            <li><a href="notifications.php">Notifications<span id="notifications" class="glyphicon glyphicon-bell"></span></a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right">

            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
			  <span class="glyphicon glyphicon-user"></span>&nbsp;Hi <?php echo $userRow['first_name']." ".$userRow['last_name']; ?>&nbsp;<span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="profile.php"><span class="glyphicon glyphicon-user"></span>&nbsp;View Profile</a></li>
                <li><a href="../logout.php?logout=true"><span class="glyphicon glyphicon-log-out"></span>&nbsp;Sign Out</a></li>
              </ul>
            </li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>

    <script type="text/javascript">
    document.getElementById('notifications').innerHTML = <?php $val = sizeof($auth_user->getAppeals($user_id)); if($val){echo $val;} ?>;
    </script>
