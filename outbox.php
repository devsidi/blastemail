<?php
// Turn off error reporting
error_reporting(0);
// Load the database configuration file
include_once 'config.php';
?>
<!Doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Out Email</title>
</head>
<body class="container-fluid bg-dark text-white">
<div class="">
<?php include "header.php" ?>
  </div>
  <div class="text-black" style="margin-top:50px">
    <div class="card">
  
  <div class="card-body">
 
    <div class="card-header">
    <h3>List Email OutBox</h3>
  </div>
<table class="table table-striped table-bordered">
        <thead class="thead-dark">
            <tr>
                <th>#No</th>
                <th>Membership Number</th>
                <th>Name</th>
                <th>Email</th>
                <th>Staff No</th>
                <th>IC Number</th> 
                <th>Receipt Number</th> 
                <th>Yuran Masuk</th>
                <th>Yuran</th>
                <th>Yuran Seumur Hidup</th>
                <th>Derma</th>
                <th>Lain-Lain</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php
        // Get member rows
        $count = 1;
        $result = $con->query("SELECT * FROM member_details where status='Done' ORDER BY id DESC LIMIT 500");
        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
        ?>
            <tr>
                <td><?php echo $count++; ?></td>
                <td><?php echo $row['membership_no']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['staff_no']; ?></td>
                <td><?php echo $row['icno']; ?></td> 
                <td><?php echo $row['d_no']; ?></td> 
                <td>RM <?php echo $row['entry_fee']; ?></td>
                <td>RM <?php echo $row['fee']; ?></td>
                <td>RM <?php echo $row['lifetime_fee']; ?></td>
                <td>RM <?php echo $row['donation']; ?></td>
                <td>RM <?php echo $row['others']; ?></td>
                <td><?php echo $row['status']; ?></td>
                <td><button type="button" class="btn btn-warning"><a href="bill_done.php?membership_no=<?php echo $row['membership_no']; ?>">View Receipt</a></button></td>
            </tr>
        <?php } }else{ ?>
            <tr><td colspan="14">No member(s) found...</td></tr>
        <?php } ?>
        </tbody>
    </table>
</div>
  </div>
</div>
  </div>
</body>
</html> 