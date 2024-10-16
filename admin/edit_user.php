<?php
session_start();
Header("Content-Type: text/html; charset=UTF-8");
require_once "../include/config.php";
if (!defined('_web_path')) {
	exit();
}
if ( !isset($_SESSION["admin"]) || !isset($_SESSION["userlevel"]) )
{
  echo "<Script language=\"javascript\">window.location=\"login.php\"</script>";
  exit();
}

require_once "../include/chkuserlevel.php";

if(($_SESSION["admin"] != "admin") and ($_GET["c_id"]=="1")){
	?>
	<Script language="javascript">
		alert('ไม่สามารถแก้ไข "admin" ได้ กรุณาติดต่อผู้ดูแลระบบ');
		//window.location="index.php";
	</script>
	<?php
	echo "<Script language=\"javascript\">window.location=\"index.php\"</script>";
	exit();
}

if(isset($_GET["code_1"])){	$code_1 = $_GET["code_1"];}else{$code_1 = $_POST["code_1"];}
if(isset($_GET["code_id"])){$code_id = $_GET["code_id"];}else{$code_id = $_POST["code_id"];}

if($_SESSION["admin"] != "admin"){
	if($_SESSION["userlevel"] != "1"){
		if($_SESSION["admin"] != $code_1){
			//echo $_SESSION["admin"]." A<br>";
			//echo $code_1." B<br>";
		?>
		<Script language="javascript">
			alert('ไม่สามารถแก้ไขข้อมูลได้ กรุณาติดต่อผู้ดูแลระบบ');
			//window.location="index.php";
		</script>
		<?php
		echo "<Script language=\"javascript\">window.location=\"index.php\"</script>";
		exit();
		}
	}
}

$admin = $_SESSION['admin'];
$userlevel = $_SESSION['userlevel'];

$_menu_id = 5;
 
require_once("../include/config_db.php");

if( isset($_POST["chk_edit"]) ){$chk_edit = $_POST["chk_edit"];} else {$chk_edit="";}

if(($chk_edit=="1") and (trim($_POST["s_password"]) != "")){

	$u_name = $_POST["u_name"];
	$o_name = $_POST["o_name"];

	if($o_name != $u_name) {
		$sql = "select `user_name` from `ers_admin` where (`user_name`='$u_name') ";
		$dbquery = $mysqli->query($sql) or die("Can't send query !!");
		$num_rows = $dbquery->num_rows;
		$dbquery->close();
		if($num_rows > 0) {
			include("../include/close_db.php");
			//echo "<br />";
			//echo "<h3><p align=\"center\"><font color='#FFFFFF'>'User name : ".$u_name."' มีอยู่ในระบบแล้ว ไม่สามารถแก้ไขได้</font></p></h3><br />";
			//echo "<p align=\"center\"><a href=\"users.php\">กลับหน้าหลัก</a></p><br />";
			?>
			<Script language="javascript">
				alert("User name : <?= $u_name;?> มีอยู่ในระบบแล้ว ไม่สามารถแก้ไขได้");
				window.location="users.php";
			</script>
			<?php
			die();
		}
		if($admin != 'admin'){
			$_SESSION['admin'] = $u_name;	
		}
	} 

	$now = date("Y-m-d H:i:s",time());
	$pass = md5($_POST["s_password"].SECRET_KEY);
	$s_level = $_POST["s_level"];
	$s_firstname = $_POST["s_firstname"];
	$s_lastname = $_POST["s_lastname"];
	$s_company = $_POST["s_company"];
	$s_address1 = $_POST["s_address1"];
	$s_address2 = $_POST["s_address2"];
	$s_phone = $_POST["s_phone"];
	$sql = "update `ers_admin` set `user_name`='$u_name',`user_password`='$pass',`user_level`='$s_level',`user_firstname`='$s_firstname',`user_lastname`='$s_lastname',`user_company`='$s_company',`user_address1`='$s_address1',`user_address2`='$s_address2',`user_phone`='$s_phone',`udate`='$now' where (`id`='$code_id')";
	$dbquery = $mysqli->query($sql) or die("Can't send query !");
	 
	echo "<br>";
	//echo "<h3><p align=\"center\"><font color='#0000BB'>แก้ไขข้อมูลเรียบร้อยแล้ว</font></p></h3><br>";
	//echo "<p align=\"center\"><a href=\"users.php?iRegister=1\">กลับหน้าดูแลระบบ</a></p><br>";
	include("../include/close_db.php");
	?>
	<Script language="javascript">
		alert('แก้ไขข้อมูลสำเร็จ');
		//window.location="index.php";
	</script>
	<?php
	if($userlevel=='1'){
		echo "<meta http-equiv='refresh' content='0;URL=users.php?iRegister=1'>";
	} else {
		echo "<meta http-equiv='refresh' content='0;URL=index.php'>";
	}
	die();
	
} else {
	$sql = "select * from `ers_admin` where (`id`='$code_id')";
	$dbquery = $mysqli->query($sql) or die("Can't send query!");
	$num_rows = $dbquery->num_rows;
	if($num_rows > 0){
		$row = $dbquery->fetch_assoc();
		$c_name = $row["user_name"];
		$c_firstname = $row["user_firstname"];
		$c_lastname = $row["user_lastname"];
		$c_level = $row["user_level"];
		$c_company = $row["user_company"];
		$c_address1= $row["user_address1"];
		$c_address2 = $row["user_address2"];
		$c_phone = $row["user_phone"];
	}
	$dbquery->close();

?>
<!DOCTYPE html>
<html lang="th" class="no-js">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
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
	<style>
	.col-lg-12 ,.col-lg-8 ,.col-lg-4 { margin:0;padding:0; }
	.col-md-12 ,.col-md-8 ,.col-md-4 { margin:0;padding:0; }
	.col-sm-12 ,.col-sm-8 ,.col-sm-4 { margin:0;padding:0; }
	</style>
<SCRIPT LANGUAGE="JavaScript">
function check(){
	if(document.getElementById("u_name").value == "")
		{
			alert("กรุณากรอกชื่อ 'ชื่อ Login' ด้วยค่ะ");		
			document.getElementById("u_name").focus();	
			return false;
		}
	if(document.getElementById("s_password").value == "")
		{
			alert("กรุณากรอกรหัสผ่าน ด้วยค่ะ ?");		
			document.getElementById("s_password").select();	
			return false;
		}
	if(document.getElementById("s_password").value != document.getElementById("c_password").value)
		{
			alert("รหัสผ่าน-ยืนยันรหัสผ่าน ไม่เหมือนกันค่ะ กรุณากรอกใหม่");		
			document.getElementById("c_password").select();	
			return false;
		}
	/*if(document.getElementById("s_firstname").value == "")
		{
			alert("กรุณากรอกชื่อ ด้วยค่ะ ?");		
			document.getElementById("s_firstname").select();	
			return false;
		}*/
}
</SCRIPT>
</head>

<body role="document">

<a href="#0" class="cd-top">Top</a>

<!-- cd-top JS -->
<script src="../js/main.js"></script>

<div class="container-fluid" style="margin:0;padding:0;">
	<?php require_once "./header.php"; ?>
</div>
<div class="container">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 clearmp">
		  <div class="col-lg-12 col-md-12 col-sm-12" style="border-bottom:1px solid #89472d;font-size:18px;color:#89472d;"><span class="glyphicon glyphicon-lock"></span>&nbsp;แก้ไขผู้ใช้งาน [ คุณเข้าระบบด้วย <?= $admin;?> ]</div>
		  <div class="col-lg-12 col-md-12 col-sm-12" >&nbsp;</div>
	   </div>
	</div>
	<div class="row">
		<div class="col-lg-6 col-md- 8 col-sm-10 col-xs-12 col-lg-offset-3 col-md-offset-2 col-sm-offset-1" style="padding-top:20px;padding-bottom:20px;">
		  <form name="form1" method="post" action="edit_user.php" onSubmit="return check();" role="form">

			<div class="col-sm-4 col-xs-4 text-right" style="margin:0;padding:0;padding:3px;">ชื่อ Login&nbsp;:&nbsp;</div>
			<div class="col-sm-8 col-xs-8" style="margin:0;padding:0;padding:3px;">
			<?php if($admin == 'admin'){?>
				<?php if($c_name != 'admin'){?>
				<input type="text" name="u_name" id="u_name" maxlength="30" class="form-control input_width" autocomplete="off" value="<?php echo $c_name; ?>">
				<?php} else { echo $c_name;?>
				<input type="hidden" name="u_name" id="u_name" value="<?php echo $c_name;?>">
				<?php } ?>
			<?php} else { echo $c_name;?>
				<input type="hidden" name="u_name" id="u_name" value="<?php echo $c_name;?>">
			<?php } ?>
			</div>

			<div class="col-sm-4 col-xs-4 text-right" style="margin:0;padding:0;padding:3px;">รหัสผ่าน&nbsp;:&nbsp;</div>
			<div class="col-sm-8 col-xs-8 text-left" style="margin:0;padding:0;padding:3px;"><input type="password" name="s_password" id="s_password" maxlength="70" class="form-control3 input_width" required="" autocomplete="current-password"><i class="fa fa-eye" id="togglePassword" style="margin-left: -30px; cursor: pointer;"></i></div>

			<div class="col-sm-4 col-xs-4 text-right" style="margin:0;padding:0;padding:3px;">ยืนยันรหัสผ่าน&nbsp;:&nbsp;</div>
			<div class="col-sm-8 col-xs-8" style="margin:0;padding:0;padding:3px;"><input type="password" name="c_password" id="c_password" maxlength="70" class="form-control input_width"></div>

			<div class="col-sm-4 col-xs-4 text-right" style="margin:0;padding:0;padding:3px;">ชื่อ&nbsp;:&nbsp;</div>
			<div class="col-sm-8 col-xs-8" style="margin:0;padding:0;padding:3px;"><input type="text" name="s_firstname" id="s_firstname" maxlength="120" value="<?php echo $c_firstname;?>" class="form-control input_width"></div>

			<div class="col-sm-4 col-xs-4 text-right" style="margin:0;padding:0;padding:3px;">นามสกุล&nbsp;:&nbsp;</div>
			<div class="col-sm-8 col-xs-8" style="margin:0;padding:0;padding:3px;"><input type="text" name="s_lastname" id="s_lastname" maxlength="120" value="<?php echo $c_lastname; ?>" class="form-control input_width"></div>

			<div class="col-sm-4 col-xs-4 text-right" style="margin:0;padding:0;padding:3px;">หน่วยงาน&nbsp;:&nbsp;</div>
			<div class="col-sm-8 col-xs-8" style="margin:0;padding:0;padding:3px;"><input type="text" name="s_company" id="s_company" maxlength="120" value="<?php echo $c_company; ?>" class="form-control input_width"></div>

			<div class="col-sm-4 col-xs-4 text-right" style="margin:0;padding:0;padding:3px;">เบอร์โทร&nbsp;:&nbsp;</div>
			<div class="col-sm-8 col-xs-8" style="margin:0;padding:0;padding:3px;"><input type="text" name="s_phone" id="s_phone" maxlength="50" value="<?php echo $c_phone; ?>" class="form-control input_width"></div>

			<div class="col-sm-4 col-xs-4 text-right" style="margin:0;padding:0;padding:3px;">ที่อยู่ 1&nbsp;:&nbsp;</div>
			<div class="col-sm-8 col-xs-8" style="margin:0;padding:0;padding:3px;"><input type="text" name="s_address1" id="s_address1" maxlength="255" value="<?php echo $c_address1; ?>" class="form-control input_width"></div>

			<div class="col-sm-4 col-xs-4 text-right" style="margin:0;padding:0;padding:3px;">ที่อยู่ 2&nbsp;:&nbsp;</div>
			<div class="col-sm-8 col-xs-8" style="margin:0;padding:0;padding:3px;"><input type="text" name="s_address2" id="s_address2" maxlength="255" value="<?php echo $c_address2; ?>" class="form-control input_width"></div>	

			<div class="col-sm-12 col-xs-12" style="padding:3px;text-align:center;">
				<input type="hidden" name="code_id" value="<?php echo $code_id;?>">
				<input type="hidden" name="code_1" value="<?php echo $code_1;?>">
				<input type="hidden" name="o_name" value="<?php echo $c_name;?>">
				<input type="hidden" name="chk_edit" value="1"> 
				<input name="s_level" type="hidden" value="1">
				<input type="submit" name="Submit" value=" บันทึก " class="btn btn-warning" style="width:100px;font-size:18px;">&nbsp;
			</div>

		  </form>

		</div>
	</div>
	<div class="row">
		<div class="col-sm-12">
			<div align="center"><?php require_once("./footer.php") ?></div>
		 </div>
	</div><!-- /.row -->

</div>

</body>
</html>
<?php
}
include("../include/close_db.php"); 
?>
<script>
var sw = screen.width;
if(sw < 768)
{
	document.getElementById("fmnavbar").className = "navbar navbar-default navbar-fixed-top";
	document.getElementById("headernd_mobile").style.display = "";
}
$(window).resize(function() { 
	var windowWidth = $(window).width();
	if(windowWidth < 768){
		document.getElementById("fmnavbar").className = "navbar navbar-default navbar-fixed-top";
		document.getElementById("headernd_mobile").style.display = "";
	}
	else {
		document.getElementById("fmnavbar").className = "navbar navbar-default";
		document.getElementById("headernd_mobile").style.display = "none";
	}
});
const togglePassword = document.querySelector('#togglePassword');
const password = document.querySelector('#s_password');
togglePassword.addEventListener('click', function (e) {
    // toggle the type attribute
    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
    password.setAttribute('type', type);
    // toggle the eye slash icon
    this.classList.toggle('fa-eye-slash');
});
document.getElementById("s_password").select();
</script>
