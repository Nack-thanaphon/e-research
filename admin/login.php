<?php
session_start();
Header("Content-Type: text/html; charset=UTF-8");
require_once "../include/config.php";
if (!defined('_web_path')) {
	exit();
}
include("../include/config_db.php");
if(isset($_POST['u_name'])){$u_name = $_POST['u_name'];}
if(isset($_POST['u_password'])){$u_password= $_POST['u_password'];}
if(isset($_POST['u_name']) && isset($_POST['u_password']))
{
 
  $pass5 = md5($u_password.SECRET_KEY);
  //echo $u_name."<br>";echo $pass5."<br>";die();exit();
  $u_name = trim($u_name);
  if(Empty($u_name) or !isset($u_name) or ($u_name=="")){
	//echo "<Script language=\"javascript\">window.location=\"$_WEB_PATH\"</script>";
	echo "ERROR  empty value <p><a href='login.php'>Back to Main</a></p>"; die();
  }
  $query_m = "select * from `ers_admin` where `user_name`='".$u_name."' and `user_password`='".$pass5."' ";
  $result_m = $mysqli->query($query_m);
  $num_rows = $result_m->num_rows;
  if ($num_rows > 0 )
  {
    $admin = $u_name;
    $_SESSION["admin"] = $u_name;
	$row = $result_m->fetch_assoc();
	$_SESSION["userlevel"] = $row["user_level"];
	$_SESSION["userid"] = $row["id"];
	if($_SESSION["userlevel"] > 2){
		unset($_SESSION["admin"]);
		unset($_SESSION["userlevel"]);
		unset($_SESSION["userid"]);
		include("../include/close_db.php");
		?>
		<Script language="javascript">
			alert('ไม่มีสิทธิ์ใช้งานในส่วนนี้');
			window.location="index.php";
		</script>
		<?php
		die();exit();
	}
	$u_ip = $_SERVER["REMOTE_ADDR"];
	//$now = date("Y-m-d H:i:s",time());
	$query_m = "insert into `ers_session` (`id`,`user_name`,`ip_address`,`log_time`,`log_status`) values ('','$admin','$u_ip',now(),'i') ";
	$result_d = $mysqli->query($query_m);
  } else {
	?>
	<Script language="javascript">
		alert("'ชื่อล็อกอิน หรือ รหัสผ่าน' ไม่ถูกต้อง");
		window.location="index.php";
	</script>
	<?php
	include("../include/close_db.php");
	die();exit();
  }
  $result_m->free();
}
include("../include/close_db.php");
//if (session_is_registered("admin") && session_is_registered("userlevel"))
if (isset($_SESSION["admin"]) && isset($_SESSION["userlevel"]) && isset($_SESSION["userid"]))
{
    echo "<Script language=\"javascript\">window.location=\"index.php\"</script>";
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
	<link rel="stylesheet" href="./style_admin.css?v=<?php echo filemtime('./style_admin.css');?>">
	<link href="https://fonts.googleapis.com/css?family=Prompt" rel="stylesheet">
	<link rel="stylesheet" href="../css/font-awesome.css">
	<script>
	function FocusOnInput() {
	  var element = document.getElementById('u_name');
	  element.focus();
	  setTimeout(function () { element.focus(); }, 1);
	}
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

<div class="container" style="width:100vw; height:100vh;-moz-opacity: 0.97;-khtml-opacity: 0.97;opacity: 0.97; background-color: #ffffff;">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="height:100px;">&nbsp;
		</div><!-- /.col-lg-12 col-md-12 col-sm-12 col-xs-12 -->
	</div><!-- /.row -->
	<div class="row">
        <div class="col-lg-4 col-md-6 col-sm-8 col-xs-12 col-lg-offset-4 col-md-offset-3 col-sm-offset-2 text-center">

			<div class="panel panel-primary" style="border-width: 10px 20px 20px 20px;border-radius: 10px;background-color: #ffce00;">

				<div class="panel-heading">
				  <h3 class="panel-title">ระบบคลังข้อมูลงานวิจัย</h3>
				</div>
				<div class="panel-body text-center" style="border-radius: 10px;border: 1px solid #ffce00;background-color: #ffffff;">
					<FORM name="login" action="login.php" method="post" onsubmit="return checkSubmit();" role="form">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding:3px;padding-top:30px;">
							<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4" style="text-align:right;">ชื่อล็อกอิน&nbsp;</div>
							<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8"><input type="text" name="u_name" id="u_name" maxlength="30" class="form-control" placeholder="Username" style="max-width:250px;"></div>
						</div>
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding:3px;">
							<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">รหัสผ่าน&nbsp;</div>
							<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8 text-left"><input type="password" name="u_password" id="u_password" maxlength="70" class="form-control3" placeholder="Password" style="max-width:250px;" required="" autocomplete="current-password"><i class="fa fa-eye" id="togglePassword" style="margin-left: -30px; cursor: pointer;"></i></div>
						</div>
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding:3px;text-align:center;padding-top:10px;padding-bottom:20px;">
							<input type="submit" name="submit" value="   เข้าระบบ   " style="border-radius:5px;background-color:#FFCE00;font-size:18px;padding:5px;border:1px solid #ffce00;">
						</div>
					</FORM>
				</div>

			</div>

		</div><!-- /.col-lg-4 col-md-6 col-sm-8 col-xs-12 col-lg-offset-4 col-md-offset-3 col-sm-offset-2 -->
	</div><!-- /.row -->
</div>
</body>
</html>
<?php
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
