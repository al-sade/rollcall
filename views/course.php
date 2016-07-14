<?php
	require_once("../session.php");

	$user_id = $_SESSION['user_session'];
	$course_id = $_GET['cid'];

	if(isset($_SESSION['lecturer'])){
		$is_lecturer = 1;
		require_once("init-lecturer.php");
		$auth_user = new LECTURER();
		$course_data = $auth_user->getCourse($course_id);
		$students_list = $auth_user->getCourseStudents($course_data[0]['course_id'], $course_data[0]['day_of_week']);

		$presence_arr = array();
		$course_presence = $auth_user->getCoursePresence($course_id);
		foreach($course_presence as $key => $value){ //for each appearance of student id in presence table
			if(!isset($presence_arr[$value['student']])){
					$presence_arr[$value['student']] = array();
 			}
			array_push($presence_arr[$value['student']],$value); //need to fix to concatination
		}

	}else{
		$is_lecturer = 0;
		require_once("init-user.php");
		$auth_user = new USER();

		$course_data = $auth_user->getCourse($user_id,$course_id);
		$lecturer_id = $course_data[0]['user_id'];
		$presence = $auth_user->getUserCoursePresence($user_id,$course_id);

		//File Handling

		if(isset($_FILES["fileToUpload"])){
			$appeals_dir = "/rollcall/uploads/appeals/";
			$target_dir = $_SERVER['DOCUMENT_ROOT'].$appeals_dir;
			$target_file = $target_dir.basename($_FILES["fileToUpload"]["name"]);
			$uploadOk = 1;
			$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

			if(isset($_POST['submit']) && !empty($_FILES["fileToUpload"]["tmp_name"]))
			{
				// Check if image file is a actual image or fake image
				$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
			 if($check !== false) {
					//  echo "File is an image - " . $check["mime"] . ".";

					 $uploadOk = 1;
			 } else {
					//  echo "File is not an image.";
					 $uploadOk = 0;
			 }
			 // Check if file already exists
			if (file_exists($target_file)) {
					// echo "Sorry, file already exists.";
					$uploadOk = 0;
			}
			// Check file size
			if ($_FILES["fileToUpload"]["size"] > 500000) {
					// echo "Sorry, your file is too large.";
					$uploadOk = 0;
			}
			// Allow certain file formats
			if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
			&& $imageFileType != "gif" && $imageFileType != "pdf" ) {
					// TODO: PRINT error of allowed file types
					$uploadOk = 0;
			}
			// Check if $uploadOk is set to 0 by an error

			if ($uploadOk == 0) {
					// echo "Sorry, your file was not uploaded.";
			// if everything is ok, try to upload file
			}else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
		}
	}
			 //End of checking
			 $message = strip_tags($_POST['message']);
			 $cause = strip_tags($_POST['cause']);
			 $date_of_issue = strip_tags($_POST['date_of_issue']);
			 try{
				$submit_result = $auth_user->submitAppeal($user_id, $course_id, $lecturer_id ,$cause ,$date_of_issue, $message, $appeals_dir.basename($_FILES["fileToUpload"]["name"]), $file = NULL);
			 }
			 catch(Exception $e) {
				 echo 'Message: ' .$e->getMessage();
			 }
			}
	}

	$dowMap = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');

	if(isset($_POST['set_day_limit']))
	{
		$n_day_limt = strip_tags($_POST['day_limit']);
		$auth_user->setCourseDayLimit($course_id, $n_day_limt);
	}
?>

<?php require_once('head.php');?>
<?php require_once('nav.php');?>


<div class="container-fluid">
    <div class="container">
      <div class="row course-info">
      	<h1><?php echo($course_data[0]['course_name']); ?></h1>
	      <ul>
					<!-- If student -->

	        <?php if(!$is_lecturer){
						echo '<li>Lecturer: '.$course_data[0]['first_name'].' '.$course_data[0]['last_name'].'</li>';
						}?>
					<li>Day: <?php $day = $course_data[0]['day_of_week']; echo $dowMap[$day-1]; ?></li>
	        <li>Start: <?php echo $course_data[0]['start'];?></li>
					<li>End: <?php echo $course_data[0]['end'];?></li>
	      </ul>
				<h4>Semester Dates: <?php echo SEMESTER_START.' &#8725; '.SEMESTER_END ?></h4>
				<h3>Day Limit: <?php echo $course_data[0]['day_limit'];?></h3>
				<div id="absence-ctr"></div>
				<?php if($is_lecturer){ ?>
				<form method="post" id="day_limit">
					<div class="form-group">
					<select class="form-control" name="day_limit">
						<?php
							echo '<option value="" disabled selected>Change Day Limit</option>';
							for ($i=0; $i <= 10; $i++) {
								echo '<option value="'.$i.'">'.$i.'</option>';
							}
						?>
					</select>
				</div>
				<div class="form-group">
					<button id="form-submit" type="submit" class="btn btn-primary" name="set_day_limit">
							<i class="glyphicon glyphicon-ok"></i>&nbsp;Change
					</button>
				</div>
				</form>
				<?php } ?>
    	</div>
    </div>

		<!-- If lecturer -->

		<?php	if($is_lecturer){ ?>
					<div class="container">
						<div class="row camera-info">


									<?php
									$camera = $auth_user->getCourseCamera($course_data[0]['course_id'],$course_data[0]['day_of_week'],
								  $course_data[0]['start'],$course_data[0]['end']);
							    echo '<h2>Camera: '.$camera[0]['camera'].'</h2>';

									// echo '<ul>';
									// echo '<li>Camera: '.$camera[0]['camera'].'</li>';
									// echo '<li>Day: '.$camera[0]['day_of_week'].'</li>';
									// echo '<li>Start: '.$camera[0]['open_time'].'</li>';
									// echo '<li>End: '.$camera[0]['close_time'].'</li>';
									// echo '</ul>';
									?>

						</div>
					</div>

		<?php		} ?>

		<div class="container">
      <div class="row attendance-table">
        <h2>Attendance</h2>

				  <!-- create presence table -->
					<?php
					$day = $dowMap[$course_data[0]['day_of_week']-1];
					$date = date('Y-m-d', strtotime("next ".$day, strtotime(SEMESTER_START)));


					$table = '<table class="table table-bordered">';
					$table .= '<thead>';
					if($is_lecturer){
						$table .= '<th>Student</th>';
					}

					$date_arr = array(); //course dates between SEMESTER_START to SEMESTER_END
					$num_of_days = 0;
					//<th> for each date
					while (strtotime($date) <= strtotime(SEMESTER_END)) {
					$date_arr[$date] = 0;
					$th = explode('-',$date)[1]." &#8725; ".explode('-',$date)[2];
					$table .= "<th>".$th."</th>";
					$date = date ("Y-m-d", strtotime("+7 day", strtotime($date)));
					}
					$num_of_days = sizeof($date_arr);
					$table .= "<th>pct.</th>";

					/*
					*	if session user is STUDENT
					*/

					if(!$is_lecturer){
						$appealsUpdate = $auth_user->getAppealsStatus($course_id, $user_id);
						$attended = 0;
						// print_r(array_keys($date_arr));
						foreach ($presence as $key => $value) {
						$date = explode(" ",$value['date']);

						$date_arr[$date[0]] = 1;
						}
						$table .= '</thead>';
						$table .= '<tbody id="attendance-tbody"><tr>';

						for($i = 0 ; $i < sizeof($appealsUpdate); $i++) {
							$issue_date = $appealsUpdate[$i]['date_of_issue'];
							if(array_key_exists($issue_date,$date_arr)){
							$date_arr[$issue_date] = $appealsUpdate[$i]['status'];
							}
						}

						$absence_ctr = 0;
						foreach ($date_arr as $date => $presence) {
							if(array_key_exists($date,$date_arr) && $date <= date("Y-m-d")){
							if ($presence == 0) {
								 $absence_ctr++;
								 $table .= '<td><span class="glyphicon glyphicon-remove"></span></td>';
							}
							elseif ($presence == 1 || $presence == 2) {
								 $table .= '<td><span class="glyphicon glyphicon-ok"></span></td>';
							}
							elseif ($presence == 3) {
								 $table .= '<td><span class="glyphicon glyphicon-bed"></span></td>';
							}
							elseif ($presence == 4) {
								 $table .= '<td><span class="glyphicon glyphicon-flag"></span></td>';
							}
							elseif ($presence == 5) {
								 $table .= '<td><span class="glyphicon glyphicon-plus"></span></td>';
							}
							}else{
							$table .= "<td></td>";
							$num_of_days--;
						}
						if($presence == 1){
							$attended ++;
							}
						}
						$prec = round(($attended * 100) / $num_of_days);
						$table .= '<td>'.$prec.'%</td>';
						$table .= '</tr></body></table>';
					}

					/*
					*	if session user is LECTURER
					*/

					else{
						$table .= '<tbody id="attendance-tbody"><tr>';

						$table .= '<tbody>';

						foreach ($students_list as $key => $student) {
							$student_id = $student['student'];
							$appealsUpdate = $auth_user->getAppealsStatus($course_id, $student_id);
							if(isset($presence_arr[$student_id])){
								$student_presence_arr = $presence_arr[$student_id]; //array of registration dates for each student
							}else{
								$student_presence_arr = array();
							}
							$student_name = $student["first_name"]." ".$student["last_name"];
							//init each user attendance table row
							foreach ($student_presence_arr as $key => $attended) { //loop over each date student attended and set to 1 = attended
							$date = strtok($attended['date'], " ");
							$date_arr[$date] = 1;
							for($i = 0 ; $i < sizeof($appealsUpdate); $i++) {
								$issue_date = $appealsUpdate[$i]['date_of_issue'];
								if(array_key_exists($issue_date,$date_arr)){
										$date_arr[$appealsUpdate[$i]['date_of_issue']] = $appealsUpdate[$i]['status'];
									}
								}

							}

						$table .= '<tr><td>'.$student_name.'</td>';

						$attended = 0;
						foreach ($date_arr as $date => $status) {
							if($date <= date("Y-m-d")){
							if ($status == 0) {
								 $table .= '<td><span class="glyphicon glyphicon-remove"></span></td>';
							}
							elseif ($status == 1 || $status == 2) {
								 $table .= '<td><span class="glyphicon glyphicon-ok"></span></td>';
							}
						  elseif ($status == 3) {
								 $table .= '<td><span class="glyphicon glyphicon-bed"></span></td>';
							}
							elseif ($status == 4) {
								 $table .= '<td><span class="glyphicon glyphicon-flag"></span></td>';
							}
							elseif ($status == 5) {
								 $table .= '<td><span class="glyphicon glyphicon-plus"></span></td>';
							}
							}
							else{
							$table .= "<td></td>";
							$num_of_days--;
							}
							if($status){
							$attended ++;
								}

							$date_arr[$date] = 0	;
							}

						$prec = round(($attended * 100) / $num_of_days);
						$table .= '<td>'.$prec.'%</td>';
						$table .= '</tr>';


						}
						$table .= '</body></table>';
					}
					echo $table;
					?>
					<!-- end of presence table -->

      </div>
    </div>

		<!-- submit appeal -->

    <div class="container">
      <div class="row appeal-row">
				<?php if(!$is_lecturer){ ?>
        <h2>Submit Appeal</h2>
        <form id="appeal-form" method="post" class="form-signin" enctype="multipart/form-data">
					<div class="form-group">
						<label>Date Of Issue:  <?php if(isset($submit_result)) {echo "Appeal Submited!";} ?></label>
			      <select class="form-control" name="date_of_issue" id="doi">
							<?php foreach ($date_arr as $date => $status){
								if($status == 0 && $date < date("Y-m-d")) { echo '<option>'.$date.'</option>';}
							}
							?>
			      </select>
					</div>
					<div class="form-group">
						<label>Cause:</label>
						<select id="cause-dd" name="cause" class="form-control" required>
							<option value="" disabled selected>Select Cause</option>
							<option value="2">Attended</option>
							<option value="3">Sickness</option>
							<option value="4">Reserve Duty</option>
							<option value="5">Other</option>
						</select>
					</div>
          <div class="form-group">
            <label for="comment">Message:</label>
            <textarea class="form-control" name="message" rows="5" required></textarea>
          </div>
					<div class="form-group">
            <label class="btn btn-primary" for="appeal-file">
              <input type="file" name="fileToUpload" id="fileToUpload">
              </label>
          </div>
					<div class="form-group">
          	<button type="submit" class="btn btn-primary" value="Upload Image" name="submit">
              	<i class="glyphicon glyphicon-open-file"></i>&nbsp;Submit
              </button>
          </div>

        </form>
				<?php }?>

				<!-- End of submit appeal -->

      </div>
    </div>

		<!-- End of submit appeal -->

</div>
<div class="clearfix"></div>

<?php require_once('footer.php') ?>
<script type="text/javascript">absecneAsterisk(<?php echo $absence_ctr?>,<?php echo $course_data[0]['day_limit']?>)</script>
</body>
</html>
