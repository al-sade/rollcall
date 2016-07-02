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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../vendor/twbs/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet" media="screen">
<link href="../vendor/twbs/bootstrap/dist/css/bootstrap-theme.min.css" rel="stylesheet" media="screen">
<script type="text/javascript" src="../components/jquery/jquery.min.js"></script>
<link rel="stylesheet" href="../style.css" type="text/css"  />
<title>welcome - <?php print($userRow['email']); ?></title>
<!-- Calendar Section -->
<link href='../js/calendar/fullcalendar.css' rel='stylesheet' />
<link href='../js/calendar/fullcalendar.print.css' rel='stylesheet' media='print' />
<script src='../js/calendar/lib/moment.min.js'></script>
<script src='../js/calendar/fullcalendar.min.js'></script>
<script type="text/javascript">

var presenceJson = JSON.parse('<?php echo json_encode($auth_user->getPresence($user_id)); ?>');
var absenceJson = JSON.parse('<?php echo json_encode($auth_user->getAbsence($user_id)); ?>');

//delete student key
for(var i = 0; i < presenceJson.length; i++) {
    delete presenceJson[i]['student'];
}

var presence = [];
var absence = [];

for (var key in presenceJson) {
    if (presenceJson.hasOwnProperty(key)) {
        presence.push({
            'title': presenceJson[key].course_name,
            'start': presenceJson[key].date,


        });
    }
}

//just fix this  -> push all absence dates to proper array
for (var key in absenceJson) {
    for (var i = 0; i < absenceJson[key].length; i++) {
        absence.push({
            'title': key,
            'start': absenceJson[key][i],
						color: '#257e4a'
        });
}
}

var attendanceSummery = presence.concat(absence);
	jQuery(document).ready(function() {
		jQuery('#calendar').fullCalendar({
			defaultDate: Date(),
			editable: false,
			eventLimit: true, // allow "more" link when too many events
			events: attendanceSummery
		});
	});

</script>

<!-- End of calendar section -->
</head>

<body>

<?php require_once('nav.php');?>

<div class="clearfix"></div>

<div class="container-fluid">
    <div class="container">
      <div id='calendar'></div>
    </div>
</div>

<!-- <?php require_once('footer.php') ?> -->

</body>
</html>
