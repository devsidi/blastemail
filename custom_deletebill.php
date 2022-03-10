<?php

  include_once 'config.php';
  $membership_no=$_GET['d_no'];
  $email=$_GET['email'];
  $query = "DELETE FROM custom_member_details where d_no = $membership_no AND status='Pending'";
  $result = $con->query($query);

  echo '<script type="text/javascript">'; 
  echo 'alert("Record Deleted Successfully!");'; 
  echo 'window.location.href = "custom_pendingemail.php";';
  echo '</script>'; 
  ?> 
