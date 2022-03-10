<?php

  include_once 'config.php';
  $membership_no=$_GET['membership_no'];
  $email=$_GET['email'];
  $query = "DELETE FROM member_details where membership_no = $membership_no AND status='Pending'";
  $result = $con->query($query);

  echo '<script type="text/javascript">'; 
  echo 'alert("Record Deleted Successfully!");'; 
  echo 'window.location.href = "pendingemail.php";';
  echo '</script>'; 
  ?> 
