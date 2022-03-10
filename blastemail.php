<!Doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Blast Email</title>
</head>
<body class="bg-dark text-white">
<div class="container">
<?php include "header.php" ?>
  </div>
  <div class="container text-black" style="margin-top:50px">
    <div class="card">
  <div class="card-header">
    <h2>Blasting Email Pending</h2>
  </div>
  <div class="card-body">
    <h5 class="card-title">Total email need to be sent out! </h5>
    <?php  
    include 'config.php';
    foreach($con->query('SELECT COUNT(*) FROM member_details WHERE status = "Pending"') as $row) {
    ?>
    <h2><?php echo $row['COUNT(*)']; ?> Email.</h2>
    <?php
    }  
    ?> 
    <p class="card-text"><a href="pendingemail.php">Click to see detail of the listing.</a></p>
    <a href="sendemail.php" class="btn btn-success">Sent Pending Email</a>
  </div>
</div>
  </div>
</body>
</html> 