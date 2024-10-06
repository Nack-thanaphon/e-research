<?php

/*****  Send Email *****/
       date_default_timezone_set('Asia/Bangkok');
       require "PHPMailerAutoload.php";
// ส่งเมลหา user
       $mail_to_user = new PHPMailer;
       $mail_to_user->Host = 'smtp.office365.com';    // Specify main and backup SMTP servers 
       //$mail_to_user->Host = 'ssl://smtp.googlemail.com';
	   $mail_to_user->SMTPAuth = true;    // Enable SMTP authentication
       $mail_to_user->SMTPDebug = 0;
	   $mail_to_user->Username = 'scia@buu.ac.th';                 // SMTP username
	   $mail_to_user->Password = 'pqdb73nd';
	   $mail_to_user->SMTPSecure = 'tls';                      // Enable TLS encryption, `ssl` also accepted
       $mail_to_user->Port = 587;
	//   $mail_to_user->Port = 465; google
       $mail_to_user->setFrom("scia@buu.ac.th");
       $mail_to_user->addAddress("anusornb@go.buu.ac.th"); 
       $mail_to_user->Subject = 'ทดสอบการส่งเมลจากเว็บ scia e-research';
       $mail_to_user->Body    = 'ทดสอบการส่งเมลจากเว็บ scia e-research';
       if($mail_to_user->send()){
			echo "ส่งผ่าน";
	   } else {
			echo "ส่งไม่ผ่าน";
	   }
?>