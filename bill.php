<?php
  // require('../emailshah/pdf/dash.php');
  require('../blastemail/pdf/fpdf.php');
  include_once 'config.php';
  $membership_no=$_GET['membership_no'];
  $query = "SELECT * FROM member_details where status='Pending' AND membership_no = $membership_no";
  $result = $con->query($query);

  while ($row = $result->fetch_assoc()) { 
    $mber_no = $row['membership_no'];
    $name = $row['name'];
    $email = $row['email'];
    $staffno = $row['staff_no'];
    $email = $row['icno'];
    $dno = $row['d_no'];
    $entryfee = $row['entry_fee'];
    $fee = $row['fee'];
    $lifetimefee = $row['lifetime_fee'];
    $donation = $row['donation'];
    $others = $row['others']; //add total variable for all the fee
    $orgDate = $row['date_receipt'];
    $newDate = date("d-m-Y", strtotime($orgDate)); 
    $year = $row['year'];
    $total = $entryfee + $fee + $lifetimefee + $donation + $others;
    if($total == '40'){
      $word='Empat Puluh Ringgit Malaysia Sahaja';
    }elseif ($total == '70'){
      $word='Tujuh Puluh Ringgit Malaysia Sahaja';
    }elseif($total == '300'){
      $word='Tiga Ratus Ringgit Malaysia Sahaja';
    }elseif($total == '500'){
      $word='Lima Ratus Ringgit Malaysia Sahaja';
    }elseif($total == '610'){
      $word='Enam Ratus dan Sepuluh Ringgit Malaysia Sahaja';
    }else{
      $word = '-';
    }
  class PDF extends FPDF
{   
    // Page footer
    function Footer()
    {
      // Position at 1.5 cm from bottom
      $this->SetY(-15);
          
      // Set font-family and font-size of footer.
      $this->SetFont('Arial', 'UI', 10);
      $this->Cell(0, 10, '***This is a computer-generated receipt (Unofficial Receipt). No signature is required.*** ', 0, 0, 'C');
      $this->SetFont('Arial', 'I', 8);
      // set page number
      $this->Cell(0, 10, 'Page ' . $this->PageNo() .
            '/{nb}', 0, 0, 'C');
    }
}
 
        // Create new object.
        $pdf = new PDF();
        $pdf->AliasNbPages();
          
        // Add new pages
        $pdf->AddPage();
        $logo = "mna_logo.jpg";
        // var_dump("here" .$logo);
        $pdf->Image($logo, 90, 3, 25);
        $pdf->SetFont('Times','',14); 
        
        // Set font-family and font-size
        $pdf->SetFont('Times','B',20); 
        // Move to the right
        $pdf->Cell(80);
        $pdf->Cell(0,6,"",0,1,'R');
        $pdf->Cell(190,-5, 'D No :'.$dno, 0, 2, 'R'); 
        $pdf->Cell(0,0,"",0,1,'R'); 
        // Set the title of pages.
        $pdf->Cell(190, 50, 'PERSATUAN JURURAWAT MALAYSIA', 0, 2, 'C');
        $pdf->SetFont('Times','',12); 
        $pdf->Cell(190, -35, '(Malaysia Nurses Association)', 0, 2, 'C');
        $pdf->SetFont('Times','B',12); 
        $pdf->Cell(190, 50, 'No. Pendaftaran: 502', 0, 2, 'C');
        $pdf->SetFont('Times','IU',12); 
        $pdf->Cell(190, -35, 'RESIT TIDAK RASMI', 0, 2, 'C');
        // Break line with given space
        $pdf->Ln(5);   

// Set font-family and font-size.
$pdf->SetFont('Times','',12); 
$pdf->Cell(30, 60, 'No Ahli : ', 0, 0, 'L');
$pdf->SetFont('Times','BU',12); 
$pdf->Cell(-10, 60,$mber_no, 0, 0, 'C');
$pdf->SetFont('Times','',12); 
$pdf->Cell( 120, 60, 'Tarikh :', 0, 0, 'R' );  
$pdf->SetFont('Times','BU',12); 
$pdf->Cell(25, 60,$newDate, 0, 0, 'R' );  
// $pdf->Cell(10,10,"",0,1,'L');
$pdf->SetFont('Times','I',12); 
$pdf->Cell(-132, 70, 'TERIMA daripada', 0, 0, 'R');
$pdf->SetFont('Times','BU',12); 
$pdf->Cell(93, 70,$name, 10, 0, 'L'); 
$pdf->Cell(0,6,"",0,1,'L');
$pdf->SetFont('Times','I',12); 
$pdf->Cell(30,68, 'jumlah ringgit ', 0, 0, 'L');
if($word == '-'){
$pdf->SetFont('Times','',12); 
$pdf->Cell(10,68,'-', 0, 0, 'L');
}else{
$pdf->SetFont('Times','BU',12); 
$pdf->Cell(10,68,$word, 0, 0, 'L');
}
$pdf->Cell(0,6,"",0,1,'L');
$pdf->SetFont('Times','I',12); 
$pdf->Cell(30,66, 'dan sen -', 0, 0, 'L');
$pdf->Cell(0,6,"",0,1,'L');
$pdf->Cell(30,66, 'Bayaran bagi;', 0, 0, 'L');
$pdf->SetFont('Times','',12); 
$pdf->Cell(20,66, 'Yuran masuk', 0, 2, 'C');
if($entryfee === '0.00'){
  $pdf->SetFont('Times','',12); 
  $pdf->Cell(60,-66, '-', 0, 2, 'C');
}else{
$pdf->SetFont('Times','BU',12); 
$pdf->Cell(63,-66, 'RM '.$entryfee, 0, 2, 'C');
}
$pdf->Cell(0,6,"",0,1,'L'); 
$pdf->SetFont('Times','',12); 
$pdf->Cell(68,66, 'Yuran', 0, 2, 'C');
if($fee === '0.00'){
  $pdf->SetFont('Times','',12); 
$pdf->Cell(95,-66,'-', 0, 0, 'C');
}else{
$pdf->SetFont('Times','BU',12); 
$pdf->Cell(98,-66,'RM '.$fee, 0, 0, 'C');
}
$pdf->SetFont('Times','',12); 
$pdf->Cell(158,-66,'Bagi Tahun ', 0, 0, 'L');
$pdf->Cell(0,6,"",0,1,'L');
if(!$year){
  $pdf->SetFont('Times','',12); 
$pdf->Cell(128,-78,'-', 0, 0, 'R');
}else{
$pdf->SetFont('Times','BU',12); 
$pdf->Cell(140,-78,$year, 0, 0, 'R');
}
$pdf->Cell(0,6,"",0,1,'L');
$pdf->SetFont('Times','',12); 
$pdf->Cell(103,-78, 'Yuran Ahli Seumur Hidup', 0, 2, 'C');
$pdf->Cell(0,6,"",0,1,'L');
if($lifetimefee === '0.00'){
  $pdf->SetFont('Times','',12); 
$pdf->Cell(165,66, '-', 0, 2, 'C');
}else{
$pdf->SetFont('Times','BU',12); 
$pdf->Cell(170,66, 'RM '.$lifetimefee, 0, 2, 'C');
}
$pdf->Cell(0,6,"",0,1,'L');
$pdf->SetFont('Times','',12); 
$pdf->Cell(70,-67, 'Derma', 0, 2, 'C');
$pdf->Cell(0,6,"",0,1,'L');
if($donation === '0.00'){
  $pdf->SetFont('Times','',12);
$pdf->Cell(102,55, '-', 0, 2, 'C');
}else{
$pdf->SetFont('Times','BU',12);
$pdf->Cell(102,55, 'RM '.$donation, 0, 2, 'C');
}
$pdf->Cell(0,6,"",0,1,'L');
$pdf->SetFont('Times','',12); 
$pdf->Cell(74,-56, 'Lain-lain', 0, 2, 'C');
$pdf->Cell(0,6,"",0,1,'L');
if($others === '0.00'){
  $pdf->SetFont('Times','',12);
  $pdf->Cell(110,44, '-', 0, 2, 'C');
}else{
$pdf->SetFont('Times','BU',12);
$pdf->Cell(110,44, 'RM '.$others, 0, 2, 'C');
}
$pdf->Cell(0,6,"",0,1,'L');
if(!$total){
  $pdf->SetFont('Times','B',12); 
$pdf->Cell(0,-33,'Jumlah RM -', 0, 2, 'L');
}else{
$pdf->SetFont('Times','B',12); 
$pdf->Cell(0,-33, 'Jumlah Bayaran : RM '.$total.'.00', 0, 2, 'L');
}
$pdf->Cell(0,6,"",0,1,'L'); 
$pdf->SetFont('Times','B',12); 
$pdf->Cell(0,33, 'Cash/Cheque No : -', 0, 2, 'L');
$pdf->Cell(0,-22, 'Bank : -', 0, 2, 'L');
// $pdf->Ln(5);   
$pdf->SetFont('Times','I',12); 
$pdf->Cell(0,60,"",0,1,'L');
// $pdf->Cell(160,-12, 'Bendahari', 0, 2, 'R');
// $pdf->Cell(0,10,"",0,1,'L');
// $pdf->Cell(178,20, 'Persatuan Jururawat Malaysia', 0, 2, 'R');
// $pdf->Cell(80,95,"",0,1,'L');
// $pdf->Cell(160,-10, 'Cawangan', 0, 2, 'R');
// $pdf->Cell(0,6,"",0,1,'L');
// $pdf->Cell(180,18, 'Pusat Perubatan Universiti Malaya', 0, 2, 'R');

// $pdf->Cell(0,-10, '***This is a computer-generated receipt (Unofficial Receipt). No signature is required.***', 0, 0, 'C');
$pdf->Output();
}
  ?> 