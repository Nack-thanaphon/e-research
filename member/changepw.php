<?php
session_start();
Header("Content-Type: text/html; charset=UTF-8");
require_once "../include/config.php";
if (!defined('_web_path')) {
	exit();
}

if (!isset($_SESSION["username"]) || !isset($_SESSION["membername"]) || !isset($_SESSION["memberid"]))
{
	echo "<meta http-equiv='refresh' content='0;URL=login.php'>";
	exit();
}

if(isset($_SESSION["memberid"])){$c_id = $_SESSION["memberid"];}

include("../include/config_db.php");

if(isset($_SESSION['username'])){$username = $_SESSION['username'];}
if(isset($_POST['u_password'])){$u_password= $_POST['u_password'];}
if(isset($_POST['u_password']))
{
	$pass5 = md5($u_password.SECRET_KEY);
	if(empty($u_password) or !isset($u_password)){
		include("../include/close_db.php");
		?>
		<SCRIPT LANGUAGE="JavaScript">
		alert("รหัสผ่าน ไม่ถูกต้องกรุณาตรวจสอบ");
	    window.history.back();
		</SCRIPT> 
		<?php
		//echo "<Script language=\"javascript\">window.location=\""._web_path."\"</script>";
		//echo "<p><h4>รหัสผ่าน ไม่ถูกต้องกรุณาตรวจสอบ</h4></a>&nbsp;<a href='changepw.php' style='color:#ff0000;'><h3>เปลี่ยนรหัสผ่าน</h3></a></p>"; 
		die();
	}

	$s_oldpassword = $_POST["oldpassword"];
	$s_ip = $_SERVER["REMOTE_ADDR"];

	$sql = "select * from `ers_member` where (`id`='".$c_id."')";
	$dbquery = $mysqli->query($link,$sql) or die("Can't send query !!");
	$num_rows = $dbquery->num_rows;
	$dbquery->free();
	if($num_rows > 0) {
		$sql = "update `ers_member` set `em_password`='$pass5',`em_oldpassword`='$s_oldpassword',`em_ip`='$s_ip',`update_date`=now(),`update_user`='".$_SESSION["username"]."' where (`id`='".$c_id."')";
		$dbquery = $mysqli->query($link,$sql) or die("ไม่สามารถบันทึกข้อมูลได้ !1");
	}
	include("../include/close_db.php");

	if (isset($_SESSION["username"]) && isset($_SESSION["membername"]) && isset($_SESSION["memberid"]))
	{
		$_SESSION["memberpass"] = $u_password;
		echo "<meta http-equiv='refresh' content='0;URL=index.php'>";
	} else {
		echo "<meta http-equiv='refresh' content='0;URL=login.php'>";
	}

	die();exit();
}

$c_username = '';
$c_password = '';
$c_name = "";
if(isset($c_id)){
	if(!empty($c_id)){
		$sql = "select * from `ers_member` where (`id`='$c_id')";
		$dbquery = $mysqli->query($link,$sql) or die("Can't send query!");
		$tRows = $dbquery->num_rows;
		if($tRows>0){
			$row = $dbquery->fetch_assoc();
			$c_username = $row["em_username"];
			$c_password = $row["em_password"];
			$c_name = $row["em_title"];
			$c_name .= $row["em_firstname"];
			$c_name .= " ".$row["em_lastname"];
		}
		$dbquery->free();
	} 
}
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
	<?php require_once "../headerpb.php"; ?>
</div>
<div class="container" style="width:100vw; height:100vh;-moz-opacity: 0.97;-khtml-opacity: 0.97;opacity: 0.97; background-color: #ffffff;">
	
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="height:10px;">&nbsp;
		</div><!-- /.col-lg-12 col-md-12 col-sm-12 col-xs-12 -->
	</div><!-- /.row -->

	<div class="row">
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 col-lg-offset-4 col-md-offset-4 col-sm-offset-3 text-center">

			<div class="panel panel-primary" style="box-shadow: 5px 10px #7c7c7c;border-width: 0 5px 10px 5px;border-radius: 10px;background-color: #ffce00;">
				<div class="panel-heading">
					<h3 class="panel-title"><strong>เปลี่ยนรหัสผ่าน</strong></h3>			   
				</div>
				<div class="panel-body text-center" style="border-radius: 10px;border: 1px solid #ffce00;background-color: #ffffff;">

					<form method="post" role="form" action="changepw.php" onSubmit="return checkSubmit();">

					<div class="col-xs-12 col-sm-12">ชื่อ login : <?= $c_username;?></div>
					<div class="col-xs-12 col-sm-12">ชื่อ-นามสกุล : <?= $c_name;?></div>

					<div class="col-xs-5 col-sm-3 text-right" style="padding: 3px 5px 3px 0px;"><font color="#FF0000">*</font>&nbsp;รหัสผ่าน&nbsp;</div>
					<div class="col-xs-7 col-sm-9 text-left" style="padding: 3px 5px 3px 0px;"><input name="u_password" id="u_password" type="password" maxlength="30" class="form-control3" placeholder="Password" required="" autocomplete="current-password"><i class="fa fa-eye" id="togglePassword" style="margin-left: -30px; cursor: pointer;"></i></div>

					<div class="col-xs-5 col-sm-3 text-right" style="padding: 3px 5px 3px 0px;"><font color="#FF0000">*</font>&nbsp;ยืนยัน รหัสผ่าน&nbsp;</div>
					<div class="col-xs-7 col-sm-9 text-left" style="padding: 3px 5px 3px 0px;"><input name="a_password" id="a_password" type="password" maxlength="30" class="form-control" placeholder="Confirm password"></div>

					<div class="col-xs-12 col-sm-12">&nbsp;</div>
					<div class="col-xs-12 col-sm-12" style="text-align:center;padding: 3px 5px 3px 0px;">
						<input name="Submit" type="submit" value="ส่งข้อมูล" class="btn btn-info" style="width:100px;">
						<input type="hidden" name="oldpassword" value="<?php if(isset($c_password)){ echo $c_password;}else{ echo '';}?>">
						<!--<input name="&nbsp;Reset&nbsp;" type="reset" value="Reset" class="btn btn-warning">-->
					</div>
					<div class="col-xs-12 col-sm-12">&nbsp;</div>
					</form>

				</div>
			</div>

		</div><!-- /.col-lg-2 col-md-4 col-sm-6 col-xs-12 col-lg-offset-5 col-md-offset-4 col-sm-offset-3 text-center -->

		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div align="center"><?php require_once("../admin/footer.php") ?></div>
	    </div>

	</div><!-- /.row -->
</div>
</body>
</html>
<?php

include("../include/close_db.php"); 

?>

<script language="javascript">
function checkSubmit(){
	if(document.getElementById('u_password').value == "") 
	{
		alert("'รหัสผ่าน' จำเป็นต้องมีข้อมูล !");	
		document.getElementById('u_password').focus();
		return false;
	}
	var valun = document.getElementById('u_password').value;
	var stl = valun.length;
	if((stl < 6) || (stl > 50)){
		alert("รหัสผ่าน ต้องอยู่ระหว่าง 6 ถึง 30 ตัวอักษร");
		document.getElementById('u_password').focus();
		return false;
	}
	if(document.getElementById('u_password').value != document.getElementById('a_password').value)
	{
		alert("รหัสผ่าน-ยืนยันรหัสผ่าน ไม่เหมือนกัน ? กรุณาตรวจสอบ");		
		document.getElementById("a_password").value = '';
		document.getElementById("a_password").select();	
		return false;
	}
}
var sw = screen.width;
if(sw < 768)
{
	document.getElementById("headernd_mobile").style.display = "";
}
function FocusOnInput() {
	  var element = document.getElementById('firstname');
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
