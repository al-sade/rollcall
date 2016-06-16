<?php
$image_location = '/rollcall/uploads/appeals/';

  if (empty($appeals)){
    $output = '<h3>No new notifications</h3>';
    echo $output;
  }else{
  foreach ($appeals as $appeal) {
    $attached_file = $appeal["file_dir"];
    $appeal['status'] == 1 ? $approved = "Approved" : $approved = "declined";
    $output = '<div id="an_'.$appeal['appeal_id'].'" class="appeal"><span class="glyphicon glyphicon-remove remove-appeal" onclick="removeAppeal('.$appeal['appeal_id'].')"></span>';
    $output .= '<div class="appeal-data">';
    $output .= '<span>Course: '.$appeal['course_name'].'</span>';
    $output .= '<span>Lecturer: '.$appeal['first_name']." ".$appeal['last_name'].'</span>';
    $output .= '<span>Submit Date: '.$appeal['submit_date'].'</span>';
    $output .= '<span>Date Of Issue: '.$appeal['date_of_issue'].'</span>';
    $output .= '<span>Message: '.$appeal['content'].'</span>';
    if(isset($appeal['response']))
      $output .= '<span>Response: '.$appeal['response'].'</span>';

    $appeal['read'] == 0 ? $output .= '<span>Status: pending</span></div>' : $output .= '<span>Status: '.$approved .'</span></div>';

    //appeal file
    if($attached_file !== $image_location)
      $output .= '<a href="'.$attached_file.'" download=""><div class="appeal-file" style="background-image: url('.$attached_file.');"></div></a>';
    $output .= '</div>';
      echo $output;
    }
  }
  ?>
