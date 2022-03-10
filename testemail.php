<?php
    // Import PHPMailer classes into the global namespace 
    use PHPMailer\PHPMailer\PHPMailer; 
    use PHPMailer\PHPMailer\Exception; 
    require 'PHPMailer/Exception.php'; 
    require 'PHPMailer/PHPMailer.php'; 
    require 'PHPMailer/SMTP.php'; 

    $mail = new PHPMailer; 
    require('../blastemail/pdf/fpdf.php');
    include_once 'config.php';
    //$membership_no=$_GET['membership_no'];
    //$membership_no='123';

    $query = "SELECT * FROM member_details where status='Pending'";
    $result = $con->query($query); 
    $rows = array();
    while($row = $result->fetch_array())
    $rows[] = $row;
    foreach($rows as $row){  
    $dno = $row['d_no'];
    $orgDate = $row['date_receipt'];
    $newDate = date("d-m-Y", strtotime($orgDate));  
    
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

    // Create new object.
    $pdf = new PDF();
    $pdf->AliasNbPages();
        
    // Add new pages
    $pdf->AddPage();
    $pdf->Image('mna_logo.jpg', 95, 8, 20);
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
    $pdf->Cell(190, -35, 'RESIT RASMI', 0, 2, 'C');

    // Break line with given space
    $pdf->Ln(5);   
    
    $pdf->SetFont('Times','',12); 
    $pdf->Cell(30, 60, 'No Ahli : '.$row['membership_no'], 0, 0, 'L');
    $pdf->Cell( 150, 60, 'Tarikh :'.$newDate, 0, 0, 'R' );  
    $pdf->Cell(0,6,"",0,1,'L');
    $name =$row['name'];
    $pdf->Cell(0, 60, 'TERIMA daripada '.$name, 0, 12, 'L'); 
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
    $pdf->Cell(180,-50, 'Tandatangan Bendahari', 0, 2, 'R');
    $pdf->Cell(0,-60,"",0,1,'L');
    // $pdf->Cell(180,190, '***This is an auto generated email. Please do not reply to this email.***', 0, 2, 'C');
    $pdf->Output();
    
    $mail->isSMTP();                      // Set mailer to use SMTP 
    $mail->Host = 'smtp.gmail.com';       // Specify main and backup SMTP servers 
    $mail->SMTPAuth = true;               // Enable SMTP authentication 
    $mail->Username = 'md.saidi019@gmail.com';   // SMTP username 
    $mail->Password = '0195352675';   // SMTP password 
    $mail->SMTPSecure = 'tls';            // Enable TLS encryption, `ssl` also accepted 
    $mail->Port = 587;                    // TCP port to connect to 
    
    // Sender info 
    $mail->setFrom($row['email'], 'Receipt Payment'); 
    $mail->addReplyTo('md.saidi019@gmail.com', 'Receipt Payment'); 
    
    // Add a recipient 
    // $mail->addAddress('md.saidi019@gmail.com'); 
    
    //$mail->addCC('cc@example.com'); 
    //$mail->addBCC('bcc@example.com'); 
    
    // Set email format to HTML 
    $mail->isHTML(true); 
    $body = $file;
    // Mail subject 
    $mail->Subject = 'Persatuan Jururawat Malaysia'; 
    
    // Mail body content 
    $bodyContent = '<h3>Persatuan Jururawat Malaysia</h3>'; 
    $bodyContent .= '<p>Dear  '.$row["name"].'</p>'; 
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
    $mail->addStringAttachment($pdf->Output("S",'receiptDetails.pdf'), 'receiptDetails.pdf', $encoding = 'base64', $type = 'application/pdf');

    // Send email 
    if(!$mail->send()) { 
        echo 'Message could not be sent. Mailer Error: '.$mail->ErrorInfo; 
        } else { 
        echo 'Message has been sent.'; 
        } 
    }  



  ?> 