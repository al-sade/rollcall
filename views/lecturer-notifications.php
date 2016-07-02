<?php
$image_location = '/rollcall/uploads/appeals/';
$appealCauseArr = ['Attended', 'Sickness', 'Reserve Duty', 'other'];
  //lecturer response for appeal form
  $form = '<div class="form-response"><form class="" method="post">';
  $form .= '<div class="form-group form-el">';
  $form .= '<label for="response">Response:</label>';
  $form .= '<textarea class="form-control" name="response" rows="5"></textarea>';
  $form .= '</div>';




  if (empty($appeals)){
    $output = '<h3>No new notifications</h3>';
    echo $output;
  }else{
  foreach ($appeals as $appeal) {
    $attached_file = $appeal["file_dir"];
    $output = '<div id="an_'.$appeal['appeal_id'].'" class="appeal">';
    $output .= '<div class="appeal-data">';
    $output .= '<span>Course: '.$appeal['course_name'].'</span>';
    $output .= '<span>Student: '.$appeal['first_name']." ".$appeal['last_name'].'</span>';
    $output .= '<span>Submit Date: '.$appeal['submit_date'].'</span>';
    $output .= '<span>Date Of Issue: '.$appeal['date_of_issue'].'</span>';
    $output .= '<span>Cause: '.$appealCauseArr[$appeal['cause']].'</span>';
    $output .= '<span>Message: '.$appeal['content'].'</span>';
    if($attached_file !== $image_location)
      $output .= '<a href="'.$attached_file.'" download=""><div class="appeal-file" style="background-image: url('.$attached_file.');"></div></a>';
      $output .= $form;
    $output .= '<button type="submit" name="approve" value="'.$appeal['appeal_id'].'" class="btn btn-default" onclick="removeAppeal('.$appeal['appeal_id'].')">Approve</button>';
    $output .= '<button type="submit" name="decline" value="'.$appeal['appeal_id'].'" class="btn btn-default" onclick="removeAppeal('.$appeal['appeal_id'].')">Decline</button>';
    $output .= '</form></div>';
    $output .= '</div></div>';
    echo $output;
  }
}
  ?>
