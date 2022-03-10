<?php
  session_start();

  if (isset($_SESSION['id'])) {
      header("Location:profile.php");
  }

  // Include database connectivity
    
  include_once('config.php');
  
  if (isset($_POST['submit'])) {

      $errorMsg = "";

      $email    = mysqli_real_escape_string($con, $_POST['email']);
      $password = mysqli_real_escape_string($con, $_POST['password']); 
      
  if (!empty($email) || !empty($password)) {
        $query  = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($con, $query);
        if(mysqli_num_rows($result) == 1){
          while ($row = mysqli_fetch_assoc($result)) {
            if (password_verify($password, $row['password'])) {
                $_SESSION['id'] = $row['id'];
                $_SESSION['username'] = $row['username'];
                header("Location:home.php");
            }else{
                $errorMsg = "Email or Password is invalid";
            }    
          }
        }else{
          $errorMsg = "No user found on this email";
        } 
    }else{
      $errorMsg = "Email and Password is required";
    }
  }

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Blast Portal</title>
  <meta name="keywords" >
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head> 
<body> 
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item active" aria-current="page"></li>
  </ol>
</nav>
<div class="container">
<div class="card text-center">
  <div class="card-header">
    <h5 class="card-title"><b>Blast Email UI</b></h5>
  </div>
  <div class="card-body">
    <h5 class="card-title">Blasting Portal</h5>
    <div class="mt-2 col-md-12">
</div>
    <p class="card-text">Welcome to Blast Email UI</p>
    <div class="row">
    <div class="container px-4">
  <div class="row gx-5">
    <div class="col">
     <div><img src="img/logo.png" class="img-thumbnail" alt="..." height="250" width="250"> 
    </div>
    </div>
    <div class="col">
      <div class="p-3 border bg-light">
      <form action="" method="post"> 
    <div class="form-group">
      <label for="name">Username:</label>
      <input type="text" class="form-control" id="email" placeholder="Enter email" name="email" required>
    </div> 
    <div class="form-group">
      <label for="pwd">Password:</label>
      <input type="password" class="form-control" id="password" placeholder="Enter password" name="password" required>
    </div>
    <input type="submit" name="submit" class="btn btn-primary" value="Login">
  </form>
    </div>
  </div>
</div>
</div>
    </div> 
  </div>
  <div class="card-footer text-muted">
  <p>If you haven't account <a href="#">Login</a></p><br><p>Dev@md.S <?php echo date("Y"); ?> Personal Project</p>

  </div>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
</body>
</html>