<?php

  $form = '<div class="form-response"><form class="form-signin" method="post">';
  $form .= '<div class="form-group">';
  $form .= '<label for="response">Message:</label>';
  $form .= '<textarea class="form-control" name="response" rows="5" required></textarea>';
  $form .= '</div>';
  $form .= '<button type="submit" name="approve" value="approve" class="btn btn-default">Approve</button>';
  $form .= '<button type="submit" name="decline" value="decline" class="btn btn-default">Decline</button>';
  $form .= '</form></div>';




  foreach ($appeals as $appeal) {
    $attached_file = $appeal["file_dir"];
    $output = '<div class="appeal">';
    $output .= '<h3 >Course: '.$appeal['course_name'].'</h3>';
    $output .= '<h3>Student: '.$appeal['first_name']." ".$appeal['last_name'].'</h3>';
    $output .= '<h3>Submit Date: '.$appeal['submit_date'].'</h3>';
    $output .= '<h3>Message: '.$appeal['content'].'</h3>';
    $output .= '<h3>File: <div style="height: 100px; width: 100px; background-size: cover;background-image: url('.$attached_file.');"></div></h3>';
    $output .= $form;
    $output .= '</div>';
    echo $output;
  }
  ?>
