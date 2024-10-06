<?php
session_start();
Header("Content-Type: text/html; charset=UTF-8");
require_once "../include/config.php";
if (!defined('_web_path')) {
	exit();
}
$_menu_id = 5;
include("../include/config_db.php");
if(isset($_GET["dcid"])){	$dcid = $_GET["dcid"];} else {if(isset($_POST["dcid"])){$dcid = $_POST["dcid"];}}
if(isset($_POST['u_name'])){$u_name = $_POST['u_name'];}
if(isset($_POST['u_password'])){$u_password= $_POST['u_password'];}
if(isset($_POST['u_name']) && isset($_POST['u_password']))
{
 
  $pass5 = md5($u_password.SECRET_KEY);
  //include("../include/close_db.php");
  //echo $u_name."<br>";echo $pass5."<br>";echo SECRET_KEY."<br>";echo $u_password."<br>";die();exit();
  $u_name = trim($u_name);
  if(Empty($u_name) or !isset($u_name) or ($u_name=="")){
	//echo "<Script language=\"javascript\">window.location=\"$_WEB_PATH\"</script>";
	echo "<meta http-equiv='refresh' content='0;URL=login.php?dcid=$dcid'>";
	echo "ERROR  empty value <p><a href='login.php?dcid=$dcid'>Back to Main</a></p>"; die();
  }
  $query_m = "select * from `ers_member` where `em_username`='".$u_name."' and `em_password`='".$pass5."' ";
  $result_m = $mysqli->query($query_m);
  $num_rows = $result_m->num_rows;
  if ($num_rows > 0 )
  {
    $username = $u_name;
    $_SESSION["username"] = $u_name;
	$row = $result_m->fetch_assoc();
	$_SESSION["membername"] = $row["em_firstname"];
	$_SESSION["memberid"] = $row["id"];
	$u_ip = $_SERVER["REMOTE_ADDR"];
	//$now = date("Y-m-d H:i:s",time());
	$query_m = "insert into `ers_session`  (`id`,`user_name`,`ip_address`,`log_time`,`log_status`,`user_type`) values ('','$username','$u_ip',now(),'i','1') ";
	$result_d = $mysqli->query($query_m);
  } else {
	?>
	<Script language="javascript">
		alert("'ชื่อล็อกอิน หรือ รหัสผ่าน' ไม่ถูกต้อง");
		window.location="index.php?dcid=<?= $dcid;?>";
	</script>
	<?
	include("../include/close_db.php");
	die();exit();
  }
  $result_m->free();
}
include("../include/close_db.php");
//if (session_is_registered("username") && session_is_registered("membername"))
//echo $_SESSION["username"]." AAAAA ".$_SESSION["membername"]." BBBBBB ".$_SESSION["memberid"]." CCCCC ";die();
if (isset($_SESSION["username"]) && isset($_SESSION["membername"]) && isset($_SESSION["memberid"]))
{
    echo "<Script language=\"javascript\">window.location=\"index.php?dcid=".$dcid."\"</script>";
	exit();
}else{
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
	<link rel="stylesheet" href="../css/font-awesome.css">
	<script>
	</script>
	<style>
	.panel-primary>.panel-heading {
		color: #000;
		background-color: #ffce00;
		border-color: #ffce00;
	}
	.panel-primary {
		border-color: #ffce00;
	}
	</style>
</head>
<body role="document" style="background: url('../images/<?php if(defined('__EC_PICHOME__')){echo __EC_PICHOME__;}?>') no-repeat;background-attachment:fixed; background-size: 60% auto;height:auto;width:auto;background-position: center;">

<div class="container-fluid" style="margin:0;padding:0;">
	<? require_once "../headerpb.php"; ?>
</div>
<div class="container" style="width:100vw; height:100vh;-moz-opacity: 0.95;-khtml-opacity: 0.95;opacity: 0.95; background-color: #ffffff;" style="margin:0;padding:0;">
	
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="height:90px;margin:0;padding:0;">&nbsp;
		</div><!-- /.col-lg-12 col-md-12 col-sm-12 col-xs-12 -->
	</div><!-- /.row -->

	<div class="row">
        <div class="col-lg-4 col-md-6 col-sm-8 col-xs-12 col-lg-offset-4 col-md-offset-3 col-sm-offset-2 text-center">

			<div class="panel panel-primary" style="border-width: 10px 20px 20px 20px;border-radius: 10px;background-color: #ffce00;">
				<div class="panel-heading">
					<h3 class="panel-title"><strong>Login</strong></h3>			   
				</div>
				<div class="panel-body text-center" style="border-radius: 10px;border: 1px solid #ffce00;background-color: #ffffff;">
					<div style="padding:40px;">
						<form method="post" role="form" action="login.php" onSubmit="return checkSubmit();">
							: ชื่อล็อกอิน :<br>
							<input type="text" name="u_name" id="u_name" maxlength="30" class="form-control" placeholder="Username" autocomplete="off" autofocus="" required=""  style="margin-bottom:5px;">
							: รหัสผ่าน :<br>
							<input type="password" name="u_password" id="u_password" maxlength="70" class="form-control2" placeholder="Password" required="" autocomplete="current-password"><i class="fa fa-eye" id="togglePassword" style="margin-left: -30px; cursor: pointer;"></i>
							<p style="text-align:right;width:100%">
								<a href="fgpassword.php">ลืมรหัสผ่าน</a>
							</p>
							<input type="hidden" name="dcid" value="<? if(isset($dcid)){ echo $dcid;}else{ echo '';}?>">
							<button class="btn btn-success" type="submit" style="display:block;width:100%;"> เข้าระบบ </button>
						</form>
						<div style="margin-top:10px;">
							<a href="register.php?dcid=<?= $dcid;?>" class="btn btn-primary" style="display:block;width:100%;">สมัครสมาชิก</a>
						</div>	
					</div>
				</div>
			</div>

		</div><!-- /.col-lg-2 col-md-4 col-sm-6 col-xs-12 col-lg-offset-5 col-md-offset-4 col-sm-offset-3 text-center -->
	</div><!-- /.row -->
</div>
</body>
</html>
<?
} //end else

include("../include/close_db.php"); 

?>

<script language="javascript">
function checkSubmit(){
	if(document.getElementById('u_name').value == "") {
		alert("กรุณาระบุชื่อล็อกอิน");
		document.getElementById('u_name').focus();
		return false;
	}
	if(document.getElementById('u_password').value == "") {
		alert("กรุณาระบุรหัสผ่าน");
		document.getElementById('u_password').focus();
		return false;
	}
}
function FocusOnInput() {
  var element = document.getElementById('u_name');
  element.focus();
  setTimeout(function () { element.focus(); }, 1);
}
const togglePassword = document.querySelector('#togglePassword');
const password = document.querySelector('#u_password');
togglePassword.addEventListener('click', function (e) {
    // toggle the type attribute
    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
    password.setAttribute('type', type);
    // toggle the eye slash icon
    this.classList.toggle('fa-eye-slash');
});
FocusOnInput();
</script>
