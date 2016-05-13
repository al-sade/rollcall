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
		require_once("init-user.php");;
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
			}
		}
			 //End of checking
			 $message = strip_tags($_POST['message']);
			 $date_of_issue = strip_tags($_POST['date_of_issue']);
			 try{
				$submit_result = $auth_user->submitAppeal($user_id, $course_id, $lecturer_id,$date_of_issue, $message, $appeals_dir.basename($_FILES["fileToUpload"]["name"]), $file = NULL);
			 }
			 catch(Exception $e) {
				 echo 'Message: ' .$e->getMessage();
			 }
			}
	}

	$dowMap = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');


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

</head>

<body>

<?php require_once('header.php');?>

<div class="clearfix"></div>

<div class="container-fluid" style="margin-top:80px;">
    <div class="container">
      <div class="row">
      <h1><?php echo($course_data[0]['course_name']); ?></h1>
      <ul>
        <?php if(!$is_lecturer){
					echo '<li>Lecturer: '.$course_data[0]['first_name'].$course_data[0]['last_name'].'</li>';
					}?>
				<li>Day: <?php $day = $course_data[0]['day_of_week']; echo $dowMap[$day]; ?></li>
        <li>Start: <?php echo $course_data[0]['start'];?></li>
        <li>End: <?php echo $course_data[0]['end'];?></li>
      </ul>
    </div>
    </div>
    <div class="container">
      <div class="row">
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
					//<th> for each date
					while (strtotime($date) <= strtotime(SEMESTER_END)) {
					$date_arr[$date] = 0;
					$th = explode('-',$date)[1]."-".explode('-',$date)[2];
					$table .= "<th>".$th."</th>";
					$date = date ("Y-m-d", strtotime("+7 day", strtotime($date)));
					}

					if(!$is_lecturer){
						foreach ($presence as $key => $value) {
						$date = explode(" ",$value['date']);
						$date_arr[$date[0]] = 1;
						}
						$table .= '</thead>';
						$table .= '<tbody><tr>';
						foreach ($date_arr as $date => $presence) {
							if($date < date("Y-m-d")){
							$presence == 1 ? $table .= '<td><span class="glyphicon glyphicon-ok"></span></td>' : $table .= '<td><span class="glyphicon glyphicon-remove"></span></td>';
							}else{
							$table .= "<td></td>";
						}
						}
						$table .= '</tr></body></table>';


					}

					/*
					*	if session user is LECTURER
					*/

					else{
						$table .= '</thead>';
						$table .= '<tbody>';

						foreach ($students_list as $key => $student) {
							$student_id = $student['student'];
							$student_presence_arr = $presence_arr[$student_id]; //array of registration dates for each student
							$student_name = $student["first_name"]." ".$student["last_name"];
							//init each user attendance table row
							foreach ($student_presence_arr as $key=> $attended) { //loop over each date student attended and set to 1 = attended
							$date = strtok($attended['date'], " ");
							$date_arr[$date] = 1;
							}

						$table .= '<tr><td>'.$student_name.'</td>';
						foreach ($date_arr as $date => $status) {
							if($date < date("Y-m-d")){
							$status == 1 ? $table .= '<td><span class="glyphicon glyphicon-ok"></span></td>' : $table .= '<td><span class="glyphicon glyphicon-remove"></span></td>';
							}
							else{
							$table .= "<td></td>";
						}
						}
						$table .= '</tr>';
						}
						$table .= '</body></table>';
					}
					echo $table;
					?>
					<!-- end of rpesence table -->

      </div>
    </div>
    <div class="container">
      <div class="row">
				<?php if(!$is_lecturer){ ?>
        <h2>Submit Appeal</h2>
        <form method="post" class="form-signin" enctype="multipart/form-data">
					<div class="form-group">
						<label>Date Of Issue:  <?php if(isset($submit_result)) {echo "Appeal Submited!";} ?></label>
			      <select class="form-control" name="date_of_issue" id="doi">
							<?php foreach ($date_arr as $date => $status){
								if($status !== 1) { echo '<option>'.$date.'</option>';}
							}
							?>
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
      </div>
    </div>
</div>

<script src="../vendor/twbs/bootstrap/dist/js/bootstrap.min.js"></script>

</body>
</html>
