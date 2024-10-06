<?php

/*****  Send Email *****/
       date_default_timezone_set('Asia/Bangkok');
       require "PHPMailerAutoload.php";
// ส่งเมลหา user
       $mail_to_user = new PHPMailer;
       $mail_to_user->Host = 'ssl://smtp.googlemail.com';    // Specify main and backup SMTP servers 
       //$mail_to_user->Host = 'ssl://smtp.googlemail.com';
	   $mail_to_user->SMTPAuth = true;    // Enable SMTP authentication
	   $mail_to_user->CharSet = "utf-8";
       $mail_to_user->SMTPDebug = 0;
	   $mail_to_user->Username = 'scia@go.buu.ac.th';                 // SMTP username
	   $mail_to_user->Password = 'q6wm88yk';
	   $mail_to_user->SMTPSecure = 'ssl';                      // Enable TLS encryption, `ssl` also accepted
       $mail_to_user->Port = 465;
	//   $mail_to_user->Port = 465; google
       $mail_to_user->setFrom("scia@buu.ac.th");
       $mail_to_user->addAddress("aggasith.r@gmail.com"); 
       $mail_to_user->Subject = 'ทดสอบการส่งเมลจากเว็บ googlemail';
       $mail_to_user->Body    = 'ทดสอบการส่งเมลจากเว็บ';
       if($mail_to_user->send()){
			echo "ส่งผ่าน";
	   } else {
			echo "ส่งไม่ผ่าน";
	   }
?>