<?php

  //lecturer response for appeal form
  $form = '<div class="form-response"><form class="form-signin" method="post">';
  $form .= '<div class="form-group">';
  $form .= '<label for="response">Message:</label>';
  $form .= '<textarea class="form-control" name="response" rows="5" required></textarea>';
  $form .= '</div>';





  foreach ($appeals as $appeal) {
    $attached_file = $appeal["file_dir"];
    $output = '<div class="appeal">';
    $output .= '<h3 >Course: '.$appeal['course_name'].'</h3>';
    $output .= '<h3>Student: '.$appeal['first_name']." ".$appeal['last_name'].'</h3>';
    $output .= '<h3>Submit Date: '.$appeal['submit_date'].'</h3>';
    $output .= '<h3>Date Of Issue: '.$appeal['date_of_issue'].'</h3>';
    $output .= '<h3>Message: '.$appeal['content'].'</h3>';
    $output .= '<h3>File: <div style="height: 100px; width: 100px; background-size: cover;background-image: url('.$attached_file.');"></div></h3>';
    $output .= $form;
    $output .= '<button type="submit" name="approve" value="'.$appeal['appeal_id'].'" class="btn btn-default">Approve</button>';
    $output .= '<button type="submit" name="decline" value="'.$appeal['appeal_id'].'" class="btn btn-default">Decline</button>';
    $output .= '</form></div>';
    $output .= '</div>';
    echo $output;
  }
  ?>
