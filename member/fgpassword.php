<?php
session_start();
Header("Content-Type: text/html; charset=UTF-8");
require_once "../include/config.php";
require_once "../include/convars.php";
if (!defined('_web_path')) {
	exit();
}

if(isset($_POST["c_uid"])) {
	$c_uid = $_POST["c_uid"];
	$c_uid = trim($c_uid);
} else {$c_uid = "";}

include("../include/config_db.php");

if(isset($_POST["fgpw"]) && ($_POST["fgpw"]=="1")) {
	if(empty($c_uid))
	{
		include("../include/close_db.php");
		?>
		<script language=JavaScript type='text/javaScript'>
		alert('ข้อมูลไม่ถูกต้อง');
		window.location="http://scia.chanthaburi.buu.ac.th/e-research/member/fgpassword.php";
		</script>
		<?php
		exit();
	}else
	{		
		$sql = "select `id`,`em_username`,`em_email` from `ers_member` where (`em_username`='$c_uid') or (`em_email`='$c_uid') ";
		$dbquery = $mysqli->query($link,$sql);
		$num_rows = $dbquery->num_rows;
		if($num_rows <= 0)
		{
			$dbquery->close();
			unset($dbquery);
			include("../include/close_db.php");
			?>
				<script language=JavaScript type='text/javaScript'>
				alert('ชื่อล็อกอิน หรือ อีเมลนี้ ไม่มีในระบบ');
				window.location="http://scia.chanthaburi.buu.ac.th/e-research/member/fgpassword.php";
				</script>
			<?php
			exit();
		}else
		{
			date_default_timezone_set('Asia/Bangkok');
			function random_password($len)
			{
				srand((double)microtime()*10000000);
				$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz123456789";
				$ret_str = "";
				$num = strlen($chars);
				for($i = 0; $i < $len; $i++)
				{
					$ret_str.= $chars[rand()%$num];
					$ret_str.=""; 
				}
				return $ret_str; 
			}		

			$row= $dbquery->fetch_assoc();
			$a_id = $row['id'];
			$a_uid = $row["em_username"];
			$a_email = trim($row["em_email"]);
			//$a_em_password = $row["em_password"];

			if(empty($a_email)){
				include("../include/close_db.php");
				?>
				<script language=JavaScript type='text/javaScript'>
				alert('ไม่สามารถสร้างรหัสผ่านใหม่ได้ เนื่องจากอีเมลของท่านไม่มีอยู่ในระบบ');
				window.location="http://scia.chanthaburi.buu.ac.th/e-research/member/login.php";
				</script>
				<?php
				exit();
			}
				
			$a_new_password = random_password(8);
			$a_password = md5($a_new_password.SECRET_KEY);
			$sql_u = "update `ers_member` set `em_password`='$a_password',`update_date`=now() where (`em_username` = '$a_uid') ";
			$dbquery_u = $mysqli->query($link,$sql_u);

			$subject = "รหัสผ่านใหม่สมาชิก E-Research";
			$message = "
			<html>
			<head>
			<meta http-equiv='Content-Type' content='text/html; charset=tis-620'>
			<title>รหัสผ่านใหม่สมาชิก E-Research</title>
			</head>
			<body>
			คุณสามารถเข้าระบบโดยใช้รหัสผ่านใหม่ข้างล่างนี้<br /><br />
			ชื่อล็อกอิน: $a_uid<br /><br />
			รหัสผ่าน: $a_new_password<br /><br />
			<a href='http://scia.chanthaburi.buu.ac.th/e-research/member/login.php'>เข้าระบบ</a><br /><br />
			สมาชิกสามารถแก้ไขข้อมูลส่วนตัวหรือเปลี่ยนรหัสผ่านได้ที่หน้าแก้ไขข้อมูล แต่ถ้าคุณพบปัญหาใดๆกรุณาติดต่อทีมงาน<br /><br />
			- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -<br /><br />
			ขอบคุณ,<br /> 
			ทีมงาน E-Research<br /><br />
			</body>
			</html>";

			require("phpmailer5/class.phpmailer.php");

			$mail = new PHPMailer();  
			$mail->Mailer = "smtp";
			$mail->IsSMTP(); // telling the class to use SMTP
			$mail->CharSet = "utf-8";  // ในส่วนนี้ ถ้าระบบเราใช้ tis-620 หรือ windows-874 สามารถแก้ไขเปลี่ยนได้                         
			$mail->Host = "smtp.gmail.com"; //  mail server ของเรา
			$mail->SMTPAuth = true;     //  เลือกการใช้งานส่งเมล์ แบบ SMTP
			$mail->Username = "scia.eresearch@gmail.com";   //  account e-mail ของเราที่ต้องการจะส่ง
			$mail->Password = "q6wm88yk";  //  รหัสผ่าน e-mail ของเราที่ต้องการจะส่ง
			$mail->SMTPSecure = "tls";                 // sets the prefix to the servier
			$mail->Port = 587; 

			$mail->From = "scia.chanthaburi.buu.ac.th/e-research";  //  account e-mail ของเราที่ใช้ในการส่งอีเมล
			$mail->FromName = "E-Research scia.chanthaburi.buu.ac.th"; //  ชื่อผู้ส่งที่แสดง เมื่อผู้รับได้รับเมล์ของเรา
			$mail->AddAddress($a_email);            // Email ปลายทางที่เราต้องการส่ง(ไม่ต้องแก้ไข)
			//$mail->AddCC($email_cc1);            // CC Email ปลายทางที่เราต้องการส่ง(ไม่ต้องแก้ไข)
			//$mail->AddCC($email_cc2);            // CC Email ปลายทางที่เราต้องการส่ง(ไม่ต้องแก้ไข)
			//$mail->AddBCC($email_bcc1);            // BCC Email ปลายทางที่เราต้องการส่ง(ไม่ต้องแก้ไข)
			//$mail->AddBCC($email_bcc2);            // BCC Email ปลายทางที่เราต้องการส่ง(ไม่ต้องแก้ไข)
			$mail->IsHTML(true);                  // ถ้า E-mail นี้ มีข้อความในการส่งเป็น tag html ต้องแก้ไข เป็น true
			$mail->Subject = $subject;        // หัวข้อที่จะส่ง(ไม่ต้องแก้ไข)
			$mail->Body = $message;                   // ข้อความ ที่จะส่ง(ไม่ต้องแก้ไข)
			$mail->set('X-Priority', '1'); 
			$result = $mail->send();
			$dbquery->close();
			unset($dbquery);
			include("../include/close_db.php");
			if($result){
				?>
				<script language=JavaScript type='text/javaScript'>
				alert('รีเซ็ตรหัสผ่านใหม่สำเร็จ ตรวจสอบรหัสผ่านใหม่ได้ที่อีเมลของท่าน');
				window.location="http://scia.chanthaburi.buu.ac.th/e-research/member/login.php";
				</script>
				<?php
				exit();
			} else {
				echo "<div style='text-align:center;padding-top:80px;font-size:16px;'>ไม่สามารถส่งรหัสผ่านใหม่ไปที่ ".$a_email."</div>";
				echo "<div style='text-align:center;padding-top:10px;font-size:16px;'>กรุณาติดต่อเจ้าหน้าที่ E-Research</div>";
				exit();
			}
		}
	}
}
include("../include/close_db.php");
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="KeyWords" content="">
<meta name="Description" content="">
<meta name="ROBOTS" content="index, follow">
<title>ระบบคลังข้อมูลงานวิจัย <?php if(defined('__EC_NAME__')){echo __EC_NAME__;}?></title>
<link href="../images/<?php if(defined('__EC_FAVICON__')){echo __EC_FAVICON_ICO__;}?>" rel="icon" type="image/ico">
<link href="../images/<?php if(defined('__EC_FAVICON__')){echo __EC_FAVICON__;}?>" rel="icon" type="image/png" sizes="32x32">
<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css?v=<?php echo filemtime('../bootstrap/css/bootstrap.min.css');?>">
<script src="../js/jquery.min.js"></script>
<script src="../bootstrap/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="../admin/style_admin.css?v=<?php echo filemtime('../admin/style_admin.css');?>">
<link href="https://fonts.googleapis.com/css?family=Prompt" rel="stylesheet">
</head>
<body role="document" style="background: url('../images/<?php if(defined('__EC_PICHOME__')){echo __EC_PICHOME__;}?>') no-repeat;background-attachment:fixed; background-size: 60% auto;height:auto;width:auto;background-position: center;">
<div class="container-fluid" style="margin:0;padding:0;">
	<?php require_once "../headerpb.php"; ?>
</div>
<div class="container-fluid" style="width:100vw; height:100vh;-moz-opacity: 0.97;-khtml-opacity: 0.97;opacity: 0.97; background-color: #ffffff;">

	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="height:90px;margin:0;padding:0;">&nbsp;
		</div><!-- /.col-lg-12 col-md-12 col-sm-12 col-xs-12 -->
	</div><!-- /.row -->

	<div class="row">
        <div class="col-lg-4 col-md-6 col-sm-8 col-xs-12 col-lg-offset-4 col-md-offset-3 col-sm-offset-2 text-center">

			<div class="panel panel-warning" style="border-width: 10px 20px 20px 20px;border-radius: 10px;background-color: #faebcc;">	
				<div class="panel-heading" style="text-align:center;">
					<h3 class="panel-title"><strong>ลืมรหัสผ่าน</strong></h3>
				</div>					
				<div class="panel-body" style="margin:0;padding:0;">

					<div style="padding:40px;">
						<form id="form1" name="form1" method="post" action="fgpassword.php" onSubmit="return checkSubmit();">
							<div class="col-xs-12 col-sm-12" style="text-align:center;">&nbsp;</div>
							<div class="col-xs-12 col-sm-12" style="text-align:center; z-index:1">
								<input name="c_uid" id="c_uid" type="text" maxlength="100" class="form-control" placeholder="ชื่อล็อกอิน หรือ อีเมล">
							</div>						  
							<div class="col-xs-12 col-sm-12">&nbsp;</div>
							<div class="col-xs-12 col-sm-12" style="text-align:center;"><font color="#FF0000">**&nbsp;</font><font  color="##FF0000"><b>รหัสผ่านใหม่จะถูกส่งไปยังอีเมลของท่าน ที่ได้ลงทะเบียนไว้ในระบบ<br> ถ้าสมาชิกขอซ้ำหลายครั้งติดๆกัน ขอให้ดูเวลาของอีเมลหลังสุดที่ได้รับ<br>และใช้รหัสผ่านครังหลังสุดที่ได้รับเท่านั้น</b></font><font color="#FF0000">&nbsp;**</font></div>
							<div class="col-xs-12 col-sm-12">&nbsp;</div>
							<div class="col-xs-12 col-sm-12" style="text-align:center;">
								<input type="hidden" name="fgpw" value="1">
								<input type="submit" name="Submit" value="ส่งข้อมูล">&nbsp;&nbsp;
							</div>
						</form>
					</div>

				</div>
			</div>

		</div><!-- /.col-lg-2 col-md-4 col-sm-6 col-xs-12 col-lg-offset-5 col-md-offset-4 col-sm-offset-3 text-center -->

	</div><!-- /.row -->

</div>

</body>
</html>
<script>
function checkSubmit(){
	if(document.getElementById('c_uid').value == "") {
		alert("กรุณาระบุ ชื่อล็อกอิน หรือ อีเมล");
		document.getElementById('c_uid').focus();
		return false;
	}
}
function FocusOnInput() {
  var element = document.getElementById('c_uid');
  element.focus();
  setTimeout(function () { element.focus(); }, 1);
}
FocusOnInput();
</script>