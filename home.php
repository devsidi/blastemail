<!Doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>User Profile Page</title>
</head>
<body class="bg-dark text-white">
<div class="container">
<?php include "header.php" ?>
  </div>
  <div class="container text-black" style="margin-top:50px">
    <div class="card">
  <div class="card-header">
    <h2>Dashboard</h2>
  </div>
  <div class="card-body">
    <h5 class="card-title">Welcome <?php echo ucwords($_SESSION['username']); ?></h5>
    <p class="card-text">CSV file format for upload Data, Kindly download.</p>
    <a href="/blastemail/csv/upload.csv" download class="btn btn-primary">Download Files</a>
  </div>
</div>
  <div class="card-body">  
  </div>
<div class="card">
  <div class="card-header">
    <h2>Custom Function</h2>
  </div>
  <div class="card-body">
    <h5 class="card-title">Custom function only!</h5>
    <p class="card-text">Click on the button bellow</p>
    <a href="upload_custom.php" class="btn btn-primary">Custom Upload Excel</a>
<a href="custom_blastemail.php" class="btn btn-primary">Custom Blast Email</a>
<a href="custom_outbox.php" class="btn btn-primary">Custom Outbox Email</a>
  </div>
</div>
  </div>
</body>
</html> 