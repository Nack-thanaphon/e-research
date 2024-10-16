<?php
session_start();
Header("Content-Type: text/html; charset=UTF-8");
require_once "../include/config.php";
require_once "../include/convars.php";
if (!defined('_web_path')) {
	exit;
}
if ( !isset($_SESSION["admin"]) || !isset($_SESSION["userlevel"]) )
{
	echo "<Script language=\"javascript\">window.location=\"login.php\"</script>";
	exit;
}
if ($_SESSION["admin"] != "admin" )
{
	echo "<Script language=\"javascript\">window.location=\"index.php\"</script>";
	exit;
}

require_once "../include/chkuserlevel.php";

$admin = $_SESSION['admin'];
$userlevel = $_SESSION['userlevel'];

$_menu_id = 5;

include("../include/config_db.php");

if( isset($_POST["chk_add"]) ){$chk_add = $_POST["chk_add"];} else {$chk_add="";}

if($chk_add=="1"){
	//เพิ่มข้อมูล	
	$u_name = $_POST["u_name"];
	if($userlevel ==1){
		$u_name = trim($u_name);
		if(Empty($u_name) or !isset($u_name) or ($u_name=="")){
			include("../include/close_db.php");
			echo "ERROR  empty value <p><a href='users.php'>Back to Main</a></p>"; die;
		}
		$sql = "select `user_name` from `ers_admin` where (`user_name`='$u_name') ";
		$dbquery = $mysqli->query($sql) or die("Can't send query !!");
		$num_rows = $dbquery->num_rows;
		$dbquery->close();
		if($num_rows > 0) {
			include("../include/close_db.php");
			//echo "<br />";
			//echo "<h3><p align=\"center\"><font color='#FFFFFF'>'User name' นี้ มีอยู่แล้ว ไม่สามารถเพิ่มได้</font></p></h3><br />";
			//echo "<p align=\"center\"><a href=\"users.php\">กลับหน้าหลัก</a></p><br />";
			?>
			<Script language="javascript">
				alert("User name : <?= $u_name;?> มีอยู่ในระบบแล้ว ไม่สามารถเพิ่มได้");
				window.location="users.php";
			</script>
			<?php
			$chk_add=0;
			die;
		}else
		{
			$pass = md5($_POST["u_password"].SECRET_KEY);
			$now = date("Y-m-d H:i:s",time());
			$u_level = $_POST["u_level"];
			$u_firstname = $_POST["u_firstname"];
			$u_lastname = $_POST["u_lastname"];
			$u_company = $_POST["u_company"];
			$u_address1 = $_POST["u_address1"];
			$u_address2 = $_POST["u_address2"];
			$u_phone = $_POST["u_phone"];

			$sql = "insert into `ers_admin` (`id`,`user_name`,`user_password`,`user_level`,`user_firstname`,`user_lastname`,`user_company`,`user_address1`,`user_address2`,`user_phone`,`udate`,`user_update`) values ('','$u_name','$pass','$u_level','$u_firstname','$u_lastname','$u_company','$u_address1','$u_address2','$u_phone','$now','$admin')";
			$dbquery = $mysqli->query($sql) or die("Can't send query !");
			$chk_add=0;
		}
	}
	else{
		include("../include/close_db.php");
		?>
		<Script language="javascript">
			alert('ไม่สามารถเพิ่มผู้ใช้งานได้ กรุณาติดต่อผู้ดูแลระบบ');
			window.location="index.php";
		</script>
		<?php
		echo "<Script language=\"javascript\">window.location=\"index.php\"</script>";
		die();exit();
	}
}
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
    <title>ระบบสารบรรณอิเล็กทรอนิกส์ <?php if(defined('__EC_NAME__')){echo __EC_NAME__;}?></title>
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
	if(document.getElementById("u_password").value == "")
		{
			alert("กรุณากรอก 'รหัสผ่าน' ด้วยค่ะ");		
			document.getElementById("u_password").focus();	
			return false;
		}
	if(document.getElementById("u_password").value != document.getElementById("c_password").value)
		{
			alert("รหัสผ่าน - ยืนยันรหัสผ่าน ไม่เหมือนกันค่ะ กรุณากรอกใหม่");		
			document.getElementById("c_password").focus();	
			return false;
		}
	/*if(document.getElementById("u_firstname").value == "")
		{
			alert("กรุณากรอก 'ชื่อ' ด้วยค่ะ ?");		
			document.getElementById("u_firstname").focus();	
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
		   <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="border-bottom:1px solid #ff9900;font-size:18px;color:#ff9900;"><span class="glyphicon glyphicon-lock"></span>&nbsp;ตั้งค่าผู้ใช้งาน [ คุณเข้าระบบด้วย <?= $admin;?> ]</div>
		   <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >&nbsp;</div>
		</div><!-- /.col-lg-12 col-md-12 col-sm-12 col-xs-12 -->
	</div><!-- /.row -->

	<div class="row">
		<div class="col-lg-6 col-md- 8 col-sm-10 col-xs-12 col-lg-offset-3 col-md-offset-2 col-sm-offset-1" style="padding-top:20px;padding-bottom:20px;">
			<form name="form1" method="post" action="users.php" onSubmit="return check();" role="form">
				<div class="col-sm-4 col-xs-4 text-right" style="padding:3px;">ชื่อ Login&nbsp;:&nbsp;</div>
				<div class="col-sm-8 col-xs-8" style="padding:3px;"><input type="text" name="u_name" id="u_name" maxlength="30" class="form-control input_width" autocomplete="off"></div>

				<div class="col-sm-4 col-xs-4 text-right" style="padding:3px;">รหัสผ่าน&nbsp;:&nbsp;</div>
				<div class="col-sm-8 col-xs-8 text-left" style="padding:3px;"><input type="password" name="u_password" id="u_password" maxlength="70" class="form-control3 input_width" autocomplete="new-password" required="" autocomplete="current-password"><i class="fa fa-eye" id="togglePassword" style="margin-left: -30px; cursor: pointer;"></i></div>

				<div class="col-sm-4 col-xs-4 text-right" style="padding:3px;">ยืนยันรหัสผ่าน&nbsp;:&nbsp;</div>
				<div class="col-sm-8 col-xs-8 text-left" style="padding:3px;"><input type="password" name="c_password" id="c_password" maxlength="70" class="form-control input_width" autocomplete="new-password"></div>

				<div class="col-sm-4 col-xs-4 text-right" style="padding:3px;">ชื่อ&nbsp;:&nbsp;</div>
				<div class="col-sm-8 col-xs-8" style="padding:3px;"><input type="text" name="u_firstname" id="u_firstname" maxlength="120" class="form-control input_width"></div>
	
				<div class="col-sm-4 col-xs-4 text-right" style="padding:3px;">นามสกุล&nbsp;:&nbsp;</div>
				<div class="col-sm-8 col-xs-8" style="padding:3px;"><input type="text" name="u_lastname" id="u_lastname" maxlength="120" class="form-control input_width"></div>

				<div class="col-sm-4 col-xs-4 text-right" style="padding:3px;">หน่วยงาน&nbsp;:&nbsp;</div>
				<div class="col-sm-8 col-xs-8" style="padding:3px;"><input type="text" name="u_company" id="u_company" maxlength="120" class="form-control input_width"></div>

				<div class="col-sm-4 col-xs-4 text-right" style="padding:3px;">เบอร์โทร&nbsp;:&nbsp;</div>
				<div class="col-sm-8 col-xs-8" style="padding:3px;"><input type="text" name="u_phone" id="u_phone" maxlength="50" class="form-control input_width"></div>

				<div class="col-sm-4 col-xs-4 text-right" style="padding:3px;">ที่อยู่ 1&nbsp;:&nbsp;</div>
				<div class="col-sm-8 col-xs-8" style="padding:3px;"><input type="text" name="u_address1" id="u_address1" maxlength="255" class="form-control input_width"></div>

				<div class="col-sm-4 col-xs-4 text-right" style="padding:3px;">ที่อยู่ 2&nbsp;:&nbsp;</div>
				<div class="col-sm-8 col-xs-8" style="padding:3px;"><input type="text" name="u_address2" id="u_address2" maxlength="255" class="form-control input_width"></div>	

		      <div class="col-sm-12 col-xs-12" style="padding:3px;text-align:center;">
				<input type="hidden" name="chk_add" value="1"> 
				<input name="u_level" type="hidden" value="1">
				<input type="submit" name="Submit" value=" บันทึก " class="btn btn-warning" style="width:100px;font-size:18px;">&nbsp;
		      </div> 

		  </form>
		</div><!-- /.col-lg-12 col-md-12 col-sm-12 col-xs-12 -->
	</div><!-- /.row -->

	<div class="row">
		<!--<hr align="center" width="100%" noshade size="1">-->

		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" style="background-color:#<?php echo __EC_BGSHOW__;?>;color:#<?php echo __EC_FONTSHOW__;?>;border-radius: 5px 5px 0px 0px;"><h4>แสดงข้อมูลผู้ดูแลระบบ</h4></div>

		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="table-responsive">
			<table class="table table-striped">
              <thead>
                <tr style="background-color:#f7f7f7">
                  <th>&nbsp;</th>
				  <th>&nbsp;</th>
				  <th style="text-align:center;">User ID</th>
				  <th>ชื่อ Login</th>
                  <th>ชื่อ - นามสกุล</th>
				  <th>เบอร์โทร</th>
				  <th>หน่วยงาน</th>
                </tr>
              </thead>
              <tbody>

				 <?php 
				
				if($_SESSION["admin"]!="admin"){
					$sql = "select * From `ers_admin` where (`user_name` = '$admin') ";
				} else {
					$sql = "select * From `ers_admin` where (`id` > 0) ";
					$sql .= "Order by `id` Desc ";
				}

				$Per_Page = 20;
				$Page = $_GET["Page"];
				if(!$_GET["Page"]){$Page=1;}

				$Page_Start = (($Per_Page*$Page)-$Per_Page);
				if($totalRows<=$Per_Page)	{$Num_Pages =1;}
				else if(($totalRows % $Per_Page)==0)
				{
					$Num_Pages =($totalRows/$Per_Page) ;
				}
				else
				{
					$Num_Pages =($totalRows/$Per_Page)+1;
					$Num_Pages = (int)$Num_Pages;
				}
				if(!($Page_Start)){ $Page_Start = 0;}

				$sql .= " LIMIT $Page_Start,$Per_Page";
				$res = $mysqli->query($sql);

				if($totalRows!="0"){

					if($Page==1){$jk=0;}
					else{
						$a=$Page * $Per_Page;
						$jk=$a-$Per_Page;
					}
					if($_SESSION["admin"]!="admin"){
						$row = $res->fetch_assoc();
					}

					while($row = $res->fetch_assoc()){
						$jk++;
						$code_id = $row["id"];
						$code_1 = $row["user_name"];
						$code_2 = $row["user_firstname"];
						$code_3 = $row["user_lastname"];
						$name = $code_2." ".$code_3;
						$code_4 = $row["user_level"];
						$code_5 = $row["user_phone"];
						$code_6 = $row["user_company"];

						$bcolor = "#ffffff";
						if(($jk %2)==0){
							$bcolor = "rgba(236, 240, 241, 0.8)";
						}

						?>

						<tr style="background-color:<?= $bcolor;?>">
						<td style="text-align:center;width:100px;min-width:100px;">
						<?php
						echo "<a href='edit_user.php?code_id=$code_id&code_1=$code_1' style='color:green;font-size:20px;' title='แก้ไข'><span class='glyphicon glyphicon-edit'></span>&nbsp;<span style='font-size:14px;'>แก้ไข</span></a>";
						?>
						</td>
						<td style="text-align:center;width:100px;min-width:100px;">
						<?php
						echo "<a href='del_data.php?c_id=$code_id&chk_p=1&code_1=$code_1' style='color:red;font-size:20px;' title='ลบ'><span class='glyphicon glyphicon-trash'></span>&nbsp;<span style='font-size:14px;'>ลบ</span></a>";
						?>
						</td>
						<td style="text-align:center;">
						<?=  $code_id;?>
						</td>
						<td>
						<?=  $code_1;?>
						</td>
						<td>
						<?=  $name;?>
						</td>
						<td>
						<?=  $code_5;?>
						</td>
						<td>
						<?=  $code_6;?>
						</td>
						</tr>

				<?php  
					}//while
				} //$totalRows 
				?>
			  </tbody>
		    </table>
			</div><!-- /.table-responsive -->
		</div><!-- /.col-lg-12 col-md-12 col-sm-12 col-xs-12 -->
	</div><!-- /.row -->
   
	<div class="row">			  	
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-left:5px;padding-right:5px;">
			<!--<div><hr align="center" width="100%" noshade size="1"></div>-->
			<div style="font-size:14px;">
				<?php
					if($Page>1){echo "หน้า : ";}
					$pages = new Paginator;
					$pages->items_total = $totalRows;
					$pages->mid_range = 7;
					$pages->current_page = $Page;
					$pages->default_ipp = $Per_Page;
					$pages->url_next = $_SERVER["PHP_SELF"]."?&Page=";
					$pages->paginate();
					echo $pages->display_pages();
					unset($Paginator);
				?>
			</div>		
	    </div>

	    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div align="center"><?php require_once("./footer.php") ?></div>
	    </div>

	</div><!-- /.row -->

</div><!-- /.container -->

</body>
</html>
<?php include("../include/close_db.php"); ?>
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
const password = document.querySelector('#u_password');
togglePassword.addEventListener('click', function (e) {
    // toggle the type attribute
    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
    password.setAttribute('type', type);
    // toggle the eye slash icon
    this.classList.toggle('fa-eye-slash');
});
document.getElementById("u_name").focus();
</script>