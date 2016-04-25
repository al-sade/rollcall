<?php
  foreach ($appeals as $appeal) {
    $attached_file = $appeal["file_dir"];
    $appeal['approved'] == 1 ? $approved = "Approved" : $approved = "declined";
    $output = '<div id="an_'.$appeal['appeal_id'].'" class="appeal"><span class="glyphicon glyphicon-remove remove-appeal" onclick="removeAppeal('.$appeal['appeal_id'].')"></span>';
    $output .= '<h3 >Course: '.$appeal['course_name'].'</h3>';
    $output .= '<h3>Lecturer: '.$appeal['first_name']." ".$appeal['last_name'].'</h3>';
    $output .= '<h3>Submit Date: '.$appeal['submit_date'].'</h3>';
    $output .= '<h3>Message: '.$appeal['content'].'</h3>';
    $output .= '<h3>File: <div style="height: 100px; width: 100px; background-size: cover;background-image: url('.$attached_file.');"></div></h3>';
    if(isset($appeal['response'])){
      $output .= '<h3>Response: '.$appeal['response'].'</h3>';
    }
    $appeal['read'] == 0 ? $output .= '<h3>Status: pending</h3>' : $output .= '<h3>Status: '.$approved .'</h3>';
    $output .= '</div>';
    echo $output;
  }
  ?>
