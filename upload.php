<?php
// Turn off error reporting
error_reporting(0);
// Load the database configuration file
include_once 'config.php';
 
if(isset($_POST['submit'])){
    
    // Allowed mime types
    $csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');
    
    // Validate whether selected file is a CSV file
    if(!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'], $csvMimes)){
        
        // If the file is uploaded
        if(is_uploaded_file($_FILES['file']['tmp_name'])){
            
            // Open uploaded CSV file with read-only mode
            $csvFile = fopen($_FILES['file']['tmp_name'], 'r');
            
            // Skip the first line
            fgetcsv($csvFile);
            
            // Parse data from CSV file line by line
            while(($line = fgetcsv($csvFile)) !== FALSE){
                // Get row data
                $membership_no   = $line[0];
                $name  = $line[1];
                $email  = $line[2];
                $staff_no = $line[3];
                $unit_ward = $line[4];
                $icno = $line[5]; 
                $dno = $line[6]; 
                $entry_fee =$line[7];
                $fee =$line[8];
                $lifetime_fee =$line[9];
                $donation =$line[10];
                $others =$line[11]; 
                $year =$line[12];
                // Check whether member already exists in the database with the same membership number
                $prevQuery = "SELECT membership_no FROM member_details WHERE membership_no = '".$line[0]."' AND status = 'Pending'";
                $prevResult = $con->query($prevQuery);
                $count =  mysqli_num_rows($prevResult);

                if($count > 0){
                    // Update member data in the database
                    $con->query("UPDATE member_details SET name = '".$name."', email = '".$email."', staff_no = '".$staff_no."', unit_ward = '".$unit_ward."', icno = '".$icno."',
                    d_no = '".$dno."',entry_fee = '".$entry_fee."',fee = '".$fee."',lifetime_fee = '".$lifetime_fee."',donation = '".$donation."',others = '".$others."',year = '".$year."',created_at = now(),date_receipt = now(),
                     status = 'Pending' WHERE membership_no = '".$membership_no."'");
                }else{
                    // Insert member data in the database
                    $con->query("INSERT INTO member_details (membership_no,name, email, staff_no,unit_ward,icno,d_no,entry_fee,fee,lifetime_fee,donation,others,year,date_receipt, created_at, status) 
                    VALUES ('".$membership_no."', '".$name."', '".$email."', '".$staff_no."', '".$unit_ward."', '".$icno."','".$dno."', '".$entry_fee."', '".$fee."', '".$lifetime_fee."',
                     '".$donation."', '".$others."', '".$year."', NOW(), NOW(), 'Pending')");
                }
            }
            
            // Close opened CSV file
            fclose($csvFile);
            
            $qstring = 'success upload';
        }else{
            $qstring = 'Error uploading';
        }
    }else{
        $qstring = 'invalid_file!';
    }
}
 
?>
<body class="container-fluid bg-dark text-white">
  <div class="">
<?php include "header.php" ?> 
<div class=" text-black" style="margin-top:50px">
    <div class="card">
  <div class="card-header">
    <h2>Upload Excel</h2>
  </div>
  <div class="card-body">
    <form enctype="multipart/form-data" method="post" role="form">
    <div class="form-group">
        <label for="exampleInputFile">File Upload</label>
        <input type="file" class="btn-link" name="file" id="file" size="150">
        <p class="help-block">Only Type .CSV File Import.</p>
    </div>
    <button type="submit" class="btn btn-warning" name="submit" value="submit">Upload</button>
</form> 
<!-- Data list table --> 

<div class="card-header">
    <h3>List Pending send Email</h3>
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
            </tr>
        </thead>
        <tbody>
        <?php
        // Get member rows
        $count = 1;
        $result = $con->query("SELECT * FROM member_details where status='Pending' ORDER BY id ASC");
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
</div>
</body>