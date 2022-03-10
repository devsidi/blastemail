<?php 
  require('../blastemail/pdf/fpdf.php');
  include_once 'config.php';
   
  // Import PHPMailer classes into the global namespace 
  use PHPMailer\PHPMailer\PHPMailer; 
  use PHPMailer\PHPMailer\Exception;
  require 'PHPMailer/Exception.php'; 
  require 'PHPMailer/PHPMailer.php'; 
  require 'PHPMailer/SMTP.php';

  class PDF extends FPDF
  {   
      // Page footer
      function Footer()
      {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
            
        // Set font-family and font-size of footer.
        $this->SetFont('Arial', 'I', 8);
          
        // set page number
        $this->Cell(0, 10, 'Page ' . $this->PageNo() .
              '/{nb}', 0, 0, 'C');
      }
  }
  // select all the data limit by 30 list
  $query = "SELECT * FROM member_details where status='Pending'  order by id ASC limit 30";
  $result = $con->query($query); 

  $files = [];
            
  while ($row = $result->fetch_assoc()) {
     
      $rows[] = $row;
      $dno = $row['d_no'];
      $orgDate = $row['date_receipt'];
      $newDate = date("d-m-Y", strtotime($orgDate)); 

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
        $pdf->Cell(190,-5, 'D No :'.$row['d_no'], 0, 2, 'R'); 
        $pdf->Cell(0,0,"",0,1,'R'); 

        // Set the title of pages.
        $pdf->Cell(190, 50, 'PERSATUAN JURURAWAT MALAYSIA', 0, 2, 'C');
        $pdf->SetFont('Times','',12); 
        $pdf->Cell(190, -35, '(Malaysia Nurses Association)', 0, 2, 'C');
        $pdf->SetFont('Times','B',12); 
        $pdf->Cell(190, 50, 'No. Pendaftaran: 502', 0, 2, 'C');
        $pdf->SetFont('Times','IU',12); 
        $pdf->Cell(190, -35, 'RESIT RASMI', 0, 2, 'C');
        // Break line with given space
        $pdf->Ln(5);   

      // Set font-family and font-size.
      $pdf->SetFont('Times','',12); 
      $pdf->Cell(20, 60, 'No Ahli : '.$row['membership_no'], 0, 0, 'L');
      $pdf->Cell( 150, 60, 'Tarikh :'.$newDate, 0, 0, 'R' );  
      $pdf->Cell(0,6,"",0,1,'L');
      $pdf->Cell(0, 60, 'TERIMA daripada '.$row['name'], 0, 12, 'L'); 
      $pdf->Cell(0,6,"",0,1,'L');
      $pdf->Cell(30,-60, 'jumlah ringgit ', 0, 0, 'L');
      $pdf->Cell(0,6,"",0,1,'L');
      $pdf->Cell(30,-60, 'dan sen ', 0, 0, 'L');
      $pdf->Cell(0,6,"",0,1,'L');
      $pdf->Cell(0,-60, 'Bayaran bagi;  Yuran masuk RM '.$row['entry_fee'], 0, 2, 'L');
      $pdf->Cell(0,6,"",0,1,'L');
      $pdf->Cell(0,60, '                         Yuran RM '.$row['fee'], 0, 2, 'L');
      $pdf->Cell(0,6,"",0,1,'L');
      $pdf->Cell(0,-60, '                         Yuran Ahli Seumur Hidup RM '.$row['lifetime_fee'], 0, 2, 'L');
      $pdf->Cell(0,6,"",0,1,'L');
      $pdf->Cell(0,60, '                         Derma RM '.$row['donation'], 0, 2, 'L');
      $pdf->Cell(0,6,"",0,1,'L');
      $pdf->Cell(0,-60, '                         Lain-lain RM '.$row['others'], 0, 2, 'L');
      $pdf->Cell(0,6,"",0,1,'L');
      $pdf->Cell(0,70, 'RM ', 0, 2, 'L');
      $pdf->Cell(0,6,"",0,1,'L');
      $pdf->Cell(0,-70, 'Cash/Cheque No : ', 0, 2, 'L');
      $pdf->Cell(0,6,"",0,1,'L');
      $pdf->Cell(0,70, 'Bank : ', 0, 2, 'L');
      $pdf->Cell(0,6,"",0,1,'L');
      $pdf->Cell(165,-50, 'Bendahari', 0, 2, 'R');
      $pdf->Cell(0,-60,"",0,1,'L');
      $pdf->Cell(180,190, 'Persatuan Jururawat Malaysia', 0, 2, 'R');
      $pdf->Cell(80,-95,"",0,1,'L');
      $pdf->Cell(160,-35, 'Cawangan', 0, 2, 'R');
      $pdf->Cell(0,6,"",0,1,'L');
      $pdf->Cell(180,120, 'Pusat Perubatan Universiti Malaya', 0, 2, 'R');

      $data['email']=$row['email'];
      $data['status']=$row['status'];
      $data['name']=$row['name'];
      $data['dno']=$dno;
      $data['pdf']=$pdf;

      array_push($files,$data);
        }
        
        $i;
        $i=0;

        foreach($files as $file){
          if($i<count($files)){
              $new_i = sendEmail($files,$i); 
              $i=$new_i;
          }
        }
      
      function sendEmail($files,$i){
        $mail = new PHPMailer;
        $mail->isSMTP();                      // Set mailer to use SMTP 
        $mail->Host = 'smtp.gmail.com';       // Specify main and backup SMTP servers 
        $mail->SMTPAuth = true;               // Enable SMTP authentication 
        $mail->Username = 'md.saidi019@gmail.com';   // SMTP username 
        $mail->Password = '0195352675';   // SMTP password 
        $mail->SMTPSecure = 'tls';            // Enable TLS encryption, `ssl` also accepted 
        $mail->Port = 587;                    // TCP port to connect to 
        
        // Sender info 
        $mail->setFrom('md.saidi019@gmail.com', 'Receipt Payment'); 
        $mail->addReplyTo('md.saidi019@gmail.com', 'Receipt Payment'); 
        
        // Add a recipient 
        $mail->addAddress($files[$i]['email']); 
        
        //$mail->addCC('cc@example.com'); 
        //$mail->addBCC('bcc@example.com'); 
        
        // Set email format to HTML 
        $mail->isHTML(true); 
        // $body = $file;

        // Mail subject 
        $mail->Subject = 'Persatuan Jururawat Malaysia'; 
        
        // Mail body content 
        $bodyContent = '<h3>Persatuan Jururawat Malaysia</h3>'; 
        $bodyContent .= '<p>Dear  '.$files[$i]['name'].'</p>'; 
        $bodyContent .= '<p></p>'; 
        $bodyContent .= '<p>Payment receipt hereby attached for your perusual.</p>'; 
        $bodyContent .= '<p></p>'; 
        $bodyContent .= '<p></p>'; 
        $bodyContent .= '<p>Thank You.</p>'; 
        $bodyContent .= '<p></p>'; 
        $bodyContent .= '<p></p>'; 
        $bodyContent .= '<p></p>'; 
        $bodyContent .= '<span>***This is an auto generated email. Please do not reply to this email.***</span>'; 
        $mail->Body    = $bodyContent;  
        // $mail->AddAttachment("/emailshah/files/testfile.pdf");      // attachment
        $mail->addStringAttachment($files[$i]['pdf']->Output("S",'receipt_no_'.$files[$i]['dno'].'.pdf'), 'receipt_no_'.$files[$i]['dno'].'.pdf', $encoding = 'base64', $type = 'application/pdf');
        
        // Send email 
        if(!$mail->send()) { 
          echo 'Message could not be sent. Mailer Error: '.$mail->ErrorInfo; 
          } else { 
          // echo 'Message has been sent.';
          $status = $files[$i]['dno'];
          include 'config.php'; 
          $query1 = "UPDATE member_details set status='Done' where d_no=$status";
          $result1 = $con->query($query1);
          }  
          $i+=1;
          return $i;
      }

?> 