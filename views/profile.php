<?php
require_once '../session.php';

if (isset($_SESSION['lecturer'])) {
    require_once 'init-lecturer.php';
    $auth_user = new LECTURER();
} else {
    require_once 'init-user.php';
    $auth_user = new USER();
}

$user_id = $_SESSION['user_session'];
$courses = $auth_user->getCourses($user_id);
$courses = array_combine(array_column($courses, 'course_name'), $courses);

?>

<?php require_once 'head.php';?>
<?php require_once 'nav.php';?>

	<div class="clearfix"></div>
    <div class="container-fluid">
	    <div class="container">
				<div class="details">
					<ul>
			    	<?php
	           $row = '<li><img src="../uploads/images/users/'.$userRow['id_number'].'-a0.png"</li>';
	           $row .= '<li>Name: '.$userRow['first_name'].' '.$userRow['last_name'].'</li>';
	           $row .= '<li>Age: ';
	           $bday = new DateTime($userRow['bday']);
	           $today = new DateTime('today');
	           $row .= $bday->diff($today)->y;
	           $row .= '</li>';
	           $row .= '<li>Department: '.$userRow['department'].'</li>';
	           $row .= '<li>eMail: '.$userRow['email'].'</li>';
	           $row .= '<li>Phone: 0'.$userRow['phone'].'</li>';
	           $row .= '<li>Year Of Study: ';
	           $begin_studying = new DateTime($userRow['begin_studying']);
	           $row .= $begin_studying->diff($today)->y;
	           $row    .= '</li>';
	           echo $row;
	          ?>
					</ul>
				</div>
				<div id="scehdule">
					<table class="table table-bordered">
						<thead>
							<tr>
								<th>Course</th>
								<th>Day</th>
								<th>Start</th>
								<th>End</th>
							</tr>
						</thead>
							<tbody>
								<?php
                $schedule = ($auth_user->getSchedule($user_id));
                foreach ($schedule as $class) {
                    if (isset($class['course'])) {
                        $output = '<tr><td><a href="course.php?cid='.$class['course'].'">'.$class['course_name'].'</a></td>';
                    } else {
                        $output = '<tr><td><a href="course.php?cid='.$class['course_id'].'">'.$class['course_name'].'</a></td>';
                    }
                    $output .= '<td>'.$auth_user->getDay($class['day_of_week']).'</td>';
                    $output .= '<td>'.$class['start'].'</td>';
                    $output .= '<td>'.$class['end'].'</td></tr>';
                    echo $output;
                }
                ?>
							</tbody>
						</table>
				</div>
	    </div>
</div>

<?php require_once('footer.php') ?>

</body>
</html>
