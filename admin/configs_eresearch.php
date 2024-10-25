<?php
session_start();
Header("Content-Type: text/html; charset=UTF-8");
require_once "../include/config.php";
require_once "../include/convars.php";
if (!defined('_web_path')) {
	exit;
}
if ( !isset($_SESSION["admin"]) || !isset($_SESSION["userlevel"]) || ($_SESSION["admin"]=="") )
{
	session_unset();session_destroy();
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

$c_id = 1;

include("../include/config_db.php");


if( isset($_POST["chk_edit"]) ){$chk_edit = $_POST["chk_edit"];} else {$chk_edit="";}

if($chk_edit=="1")
{	

	function random_password($len)
	{
		srand((double)microtime()*10000000);
		$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
		$ret_str = "";
		$num = strlen($chars);
		for($i = 0; $i < $len; $i++)
		{
			$ret_str.= $chars[rand()%$num];
			$ret_str.=""; 
		}
		return $ret_str; 
	}
	// echo random_password(8); 

	$c_ec_name = trim($_POST["s_ec_name"]);
	$c_ec_bgshow = trim($_POST["s_ec_bgshow"]);
	$c_ec_fontshow = trim($_POST["s_ec_fontshow"]);
	$c_ec_copyright = trim($_POST["s_ec_copyright"]);

	$sql = "select `id` from `ers_configs` where (`id`='".$c_id."')";
	$dbquery = $mysqli->query($sql) or die("Can't send query !3");
	$num_rows = $dbquery->num_rows;
	if($num_rows > 0) {
		if(!empty($c_ec_name)){
			$sql = "update `ers_configs` set `ec_name`='$c_ec_name',`ec_bgshow`='$c_ec_bgshow',`ec_fontshow`='$c_ec_fontshow',`ec_copyright`='$c_ec_copyright',`update_date`=now(),`update_user`='$admin' where (`id`='".$c_id."')";
			$dbquery = $mysqli->query($sql) or die("ไม่สามารถบันทึกข้อมูลได้ !A");
		}
	} else {
		$sql = "insert into `ers_configs` (`id`,`ec_name`,`ec_bgshow`,`ec_fontshow`,`ec_copyright`,`update_date`,`update_user`) values ('$c_id','$c_ec_name','$c_ec_bgshow','$c_ec_fontshow','$c_ec_copyright',now(),'$admin')";
		$dbquery = $mysqli->query($sql) or die("ไม่สามารถบันทึกข้อมูลได้ !B");
	}

	$passw3 = random_password(7);
	$path3="../images/"; 
	$file3 = stripcslashes($_FILES['file3']['tmp_name']);
	$file_name3 = basename($_FILES['file3']['name']);	
	$filenewcon3 = strstr($file_name3,'.');
	$filename3 = mb_substr($file_name3,0,strlen($file_name3)-4);
	
	$now3 = date("Ymd");

	if(!empty($file3))
	{
		if(mb_strlen($filename3,'UTF-8') > 10){
			$filename3 = trim(mb_substr($filename3,0,10));
		}
		$filename3 = $filename3.'_';
		$filesize3 =$_FILES['file3']['size'];
		$real_file =$file3;
		list($width, $height, $type, ) = getimagesize($real_file);

		$new_type= "";
		if($_FILES['file3']['type']=="image/gif"){ $new_type="IMG_GIF"; }
		if($_FILES['file3']['type']=="image/png"){ $new_type="IMG_PNG"; }
		if($_FILES['file3']['type']=="image/pjpeg" || $_FILES['file3']['type']=="image/jpeg" ){ $new_type="IMG_JPG"; }

		if($height > 32)
		{
			// สร้างภาพ favicon สูงไม่เกิน 16px
			$new_height = 32;
			$new_width = round(($new_height/$height)  *  $width);

			$new_file ="$path3/$filename3$passw3$now3$filenewcon3";

			switch ($new_type) 
			{
			case "IMG_GIF":
				$image = imagecreatefromgif($real_file);
				break;
			case "IMG_JPG":
				$image = imagecreatefromjpeg($real_file);
				break;
			case "IMG_PNG":
				$image = imagecreatefrompng($real_file);
				break;
			}
						
			$new_image = ImageCreateTrueColor($new_width, $new_height);
			ImageCopyResampled($new_image, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

			switch ($new_type)
			{
				case "IMG_GIF":
					imagegif($new_image, $new_file);
					break;
				case "IMG_JPG":
					imagejpeg($new_image, $new_file);
					break;
				case "IMG_PNG":
					imagepng($new_image, $new_file);
					break;
			}

			imagedestroy($image);
			imagedestroy($new_image);
			@chmod($new_file,0744);
		}
		else
		{
			$new_file ="$path3/$filename3$passw3$now3$filenewcon3";
			move_uploaded_file($file3,$new_file);
			@chmod($new_file,0744);
		}
		$sql = "update `ers_configs` set `ec_favicon`='$filename3$passw3$now3$filenewcon3',`update_date`=now(),`update_user`='$admin' where (`id`='$c_id')";
		$dbquery = $mysqli->query($sql) or die("Can't send query !1");
	}

	$passw4 = random_password(7);
	$path4="../images/"; 
	$file4 = stripcslashes($_FILES['file4']['tmp_name']);
	$file_name4 = basename($_FILES['file4']['name']);	
	$filenewcon4 = strstr($file_name4,'.');
	$filename4 = mb_substr($file_name4,0,strlen($file_name4)-4);
	
	$now4 = date("Ymd");

	if(!empty($file4))
	{
		if(mb_strlen($filename4,'UTF-8') > 10){
			$filename4 = trim(mb_substr($filename4,0,10));
		}
		$filename4 = $filename4.'_';
		$filesize4 =$_FILES['file4']['size'];
		
		$new_file ="$path4/$filename4$passw4$now4$filenewcon4";
		move_uploaded_file($file4,$new_file);
		@chmod($new_file,0744);

		$sql = "update `ers_configs` set `ec_favicon_ico`='$filename4$passw4$now4$filenewcon4',`update_date`=now(),`update_user`='$admin' where (`id`='$c_id')";
		$dbquery = $mysqli->query($sql) or die("Can't send query !2");
	}

	$passw1 = random_password(7);
	$path1="../images/"; 
	$file1 = stripcslashes($_FILES['file1']['tmp_name']);
	$file_name1=basename($_FILES['file1']['name']);	
	$filenewcon1 = strstr($file_name1,'.');
	$filename1 = mb_substr($file_name1,0,strlen($file_name1)-4);
	
	$now1 = date("Ymd");

	if(!empty($file1))
	{
		if(mb_strlen($filename1,'UTF-8') > 10){
			$filename1 = trim(mb_substr($filename1,0,10));
		}
		$filename1 = $filename1.'_';
		$filesize1 =$_FILES['file1']['size'];
		$real_file =$file1;
		list($width, $height, $type, ) = getimagesize($real_file);

		$new_type= "";
		if($_FILES['file1']['type']=="image/gif"){ $new_type="IMG_GIF"; }
		if($_FILES['file1']['type']=="image/png"){ $new_type="IMG_PNG"; }
		if($_FILES['file1']['type']=="image/pjpeg" || $_FILES['file1']['type']=="image/jpeg" ){ $new_type="IMG_JPG"; }

		if($height > 50)
		{
			// สร้างภาพ logo สูงไม่เกิน 50px
			$new_height = 50;
			$new_width = round(($new_height/$height)  *  $width);

			$new_file ="$path1/$filename1$passw1$now1$filenewcon1";

			switch ($new_type) 
			{
			case "IMG_GIF":
				$image = imagecreatefromgif($real_file);
				break;
			case "IMG_JPG":
				$image = imagecreatefromjpeg($real_file);
				break;
			case "IMG_PNG":
				$image = imagecreatefrompng($real_file);
				break;
			}
						
			$new_image = ImageCreateTrueColor($new_width, $new_height);
			ImageCopyResampled($new_image, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

			switch ($new_type)
			{
				case "IMG_GIF":
					imagegif($new_image, $new_file);
					break;
				case "IMG_JPG":
					imagejpeg($new_image, $new_file);
					break;
				case "IMG_PNG":
					imagepng($new_image, $new_file);
					break;
			}

			imagedestroy($image);
			imagedestroy($new_image);
			@chmod($new_file,0744);
		}
		else
		{
			$new_file = "$path1/$filename1$passw1$now1$filenewcon1";
			move_uploaded_file($file1,$new_file);
			@chmod($new_file,0744);
		}
		$sql = "update `ers_configs` set `ec_logo`='$filename1$passw1$now1$filenewcon1',`update_date`=now(),`update_user`='$admin' where (`id`='$c_id')";
		$dbquery = $mysqli->query($sql) or die("Can't send query !3");
	}

	$passw2 = random_password(7);
	$path2="../images/"; 
	$file2 = stripcslashes($_FILES['file2']['tmp_name']);
	$file_name2=basename($_FILES['file2']['name']);	
	$filenewcon2 = strstr($file_name2,'.');
	$filename2 = mb_substr($file_name2,0,strlen($file_name2)-4);

	$now2 = date("Ymd");

	if(!empty($file2))
	{
		if(mb_strlen($filename2,'UTF-8') > 10){
			$filename2 = trim(mb_substr($filename2,0,10));
		}
		$filename2 = $filename2.'_';
		$filesize2 =$_FILES['file2']['size'];
		$real_file =$file2;
		list($width, $height, $type, ) = getimagesize($real_file);

		$new_type= "";
		if($_FILES['file2']['type']=="image/gif"){ $new_type="IMG_GIF"; }
		if($_FILES['file2']['type']=="image/png"){ $new_type="IMG_PNG"; }
		if($_FILES['file2']['type']=="image/pjpeg" || $_FILES['file2']['type']=="image/jpeg" ){ $new_type="IMG_JPG"; }

		if($height>650)
		{
			// สร้างภาพ Home สูงไม่เกิน 650px
			$new_height = 650;
			$new_width = round(($new_height/$height)  *  $width);

			$new_file ="$path2/$filename2$passw2$now2$filenewcon2";

			switch ($new_type) 
			{
			case "IMG_GIF":
				$image = imagecreatefromgif($real_file);
				break;
			case "IMG_JPG":
				$image = imagecreatefromjpeg($real_file);
				break;
			case "IMG_PNG":
				$image = imagecreatefrompng($real_file);
				break;
			}
						
			$new_image = ImageCreateTrueColor($new_width, $new_height);
			ImageCopyResampled($new_image, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

			switch ($new_type)
			{
				case "IMG_GIF":
					imagegif($new_image,$new_file);
					break;
				case "IMG_JPG":
					imagejpeg($new_image,$new_file);
					break;
				case "IMG_PNG":
					imagepng($new_image,$new_file);
					break;
			}

			imagedestroy($image);
			imagedestroy($new_image);
			@chmod($new_file,0744);
		}
		else
		{
			$new_file = "$path2/$filename2$passw2$now2$filenewcon2";
			move_uploaded_file($file2,$new_file);
			@chmod($new_file,0744);
		}

		$sql = "update `ers_configs` set `ec_pichome`='$filename2$passw2$now2$filenewcon2',`update_date`=now(),`update_user`='$admin' where (`id`='$c_id')";
		$dbquery = $mysqli->query($sql) or die("Can't send query !4");

	}

	$upload_dir = '../files/';
	if($_FILES["file_at1"]["name"] != "")
	{
		$sur_num_text = strrchr($_FILES["file_at1"]["name"], ".");
		$sur_num = strlen($sur_num_text);

		$file_name_sur = htmlspecialchars($_FILES["file_at1"]["name"]);	
		$file_name = substr($file_name_sur,0,strlen($file_name_sur) - $sur_num);
		if( mb_strlen($file_name,'UTF-8') > 100 ){
			$file_name = mb_substr($file_name,0,100);
		}

		$sur1 = strrchr($_FILES["file_at1"]["name"], "."); //ตัดนามสกุลไฟล์เก็บไว
		$name = $file_name."_".(Date("dmy_His").$sur1);
		//$filename_upload = iconv("tis-620","utf-8",$name);
		$filename_upload = $name;

		$new_file = $upload_dir.$filename_upload;

		if(move_uploaded_file($_FILES["file_at1"]["tmp_name"],$new_file))
		{
			   @chmod($new_file,0744);
			   //echo "<br>Copy/Upload Complete<br>";
			   $sql_file = "update `ers_configs` set `ec_manual_admin`='$name',`update_date`=now(),`update_user`='$admin' where (`id`='$c_id')";
			   //echo '<br> sql_file = '.$sql_file;
			   $dbquery = $mysqli->query($sql_file);
		}
	}
	if($_FILES["file_at2"]["name"] != "")
	{
		$sur_num_text = strrchr($_FILES["file_at2"]["name"], ".");
		$sur_num = strlen($sur_num_text);

		$file_name_sur = htmlspecialchars($_FILES["file_at2"]["name"]);	
		$file_name = substr($file_name_sur,0,strlen($file_name_sur) - $sur_num);
		if( mb_strlen($file_name,'UTF-8') > 100 ){
			$file_name = mb_substr($file_name,0,100);
		}

		$sur1 = strrchr($_FILES["file_at2"]["name"], "."); //ตัดนามสกุลไฟล์เก็บไว
		$name = $file_name."_".(Date("dmy_His").$sur1);
		//$filename_upload = iconv("tis-620","utf-8",$name);
		$filename_upload = $name;

		$new_file = $upload_dir.$filename_upload;

		if(move_uploaded_file($_FILES["file_at2"]["tmp_name"],$new_file))
		{
			   @chmod($new_file,0744);
			   //echo "<br>Copy/Upload Complete<br>";
			   $sql_file = "update `ers_configs` set `ec_manual_member`='$name',`update_date`=now(),`update_user`='$admin' where (`id`='$c_id')";
			   //echo '<br> sql_file = '.$sql_file;
			   $dbquery = $mysqli->query($sql_file);
		}
	}
	/*
	if($_FILES["file_at3"]["name"] != "")
	{
		$sur_num_text = strrchr($_FILES["file_at3"]["name"], ".");
		$sur_num = strlen($sur_num_text);

		$file_name_sur = htmlspecialchars($_FILES["file_at3"]["name"]);	
		$file_name = substr($file_name_sur,0,strlen($file_name_sur) - $sur_num);
		if( mb_strlen($file_name,'UTF-8') > 100 ){
			$file_name = mb_substr($file_name,0,100);
		}

		$sur1 = strrchr($_FILES["file_at3"]["name"], "."); //ตัดนามสกุลไฟล์เก็บไว
		$name = $file_name."_".(Date("dmy_His").$sur1);
		//$filename_upload = iconv("tis-620","utf-8",$name);
		$filename_upload = $name;

		$new_file = $upload_dir.$filename_upload;

		if(move_uploaded_file($_FILES["file_at3"]["tmp_name"],$new_file))
		{
			   @chmod($new_file,0744);
			   //echo "<br>Copy/Upload Complete<br>";
			   $sql_file = "update `ers_configs` set `ec_manual_guest`='$name',`update_date`=now(),`update_user`='$admin' where (`id`='$c_id')";
			   //echo '<br> sql_file = '.$sql_file;
			   $dbquery = $mysqli->query($sql_file);
		}
	}*/
	echo "<meta http-equiv='refresh' content='0;URL=configs_eresearch.php'>";
	include("../include/close_db.php");
	die();
}

$c_ec_name = '';
$c_ec_favicon = '';
$c_ec_favicon_ico = '';
$c_ec_logo = '';
$c_ec_pichome = '';
$c_ec_manual_admin = '';
$c_ec_manual_member = '';
$c_ec_manual_guest = '';
$c_ec_bgshow = '';
$c_ec_fontshow = '';
$c_ec_copyright = '';

$sql = "select * from `ers_configs` where (`id`='".$c_id."')";
$dbquery = $mysqli->query($sql) or die("Can't send query!");
$tRows = $dbquery->num_rows;
if($tRows>0){
	$row = $dbquery->fetch_assoc();
	$c_ec_name = $row["ec_name"];
	$c_ec_favicon = $row["ec_favicon"];
	$c_ec_favicon_ico = $row["ec_favicon_ico"];
	$c_ec_logo = $row["ec_logo"];
	$c_ec_pichome = $row["ec_pichome"];
	$c_ec_manual_admin = $row["ec_manual_admin"];
	$c_ec_manual_member = $row["ec_manual_member"];
	$c_ec_manual_guest = $row["ec_manual_guest"];
	$c_ec_bgshow = $row["ec_bgshow"];
	$c_ec_fontshow = $row["ec_fontshow"];
	$c_ec_copyright = $row["ec_copyright"];
}
$dbquery->free();

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
	<link rel="stylesheet" href="../admin/style_admin.css?v=<?php echo filemtime('style_admin.css');?>">
	<!-- Link Swiper's CSS -->
    <link rel="stylesheet" href="../swiper/dist/css/swiper.min.css">
	<link href="https://fonts.googleapis.com/css?family=Prompt" rel="stylesheet">
	<style>
	</style>
<SCRIPT LANGUAGE="JavaScript">
function c_check(){
	if(document.getElementById('s_ec_name').value == "")
	{
		alert("'ชื่อหน่วยงาน' จำเป็นต้องมีข้อมูล");
		document.getElementById('s_ec_name').focus();
		return false;
	}
}
function loadPicture(pid,dimg,fid)
{
	var files = fid.files;
        for (var i = 0; i < files.length; i++) {           
            var file = files[i];
            var imageType = /image.*/;     
            if (!file.type.match(imageType)) {
                continue;
            }           
            var img=document.getElementById(pid);            
            img.file = file;    
            var reader = new FileReader();
            reader.onload = (function(aImg) { 
                return function(e) { 
                    aImg.src = e.target.result; 
					document.getElementById(dimg).style.display = "block";
                }; 
            })(img);
            reader.readAsDataURL(file);
        }
}
function edshowbg(){
	var bgcolor = document.getElementById('s_ec_bgshow').value;
	document.getElementById('edshow').style.backgroundColor = '#'+bgcolor;
}
function edshowfc(){
	var fccolor = document.getElementById('s_ec_fontshow').value;
	document.getElementById('edshow').style.color = '#'+fccolor;
}
</SCRIPT>
</head>
<body role="document" style="background: url('../images/<?php if(defined('__EC_PICHOME__')){echo __EC_PICHOME__;}?>')  no-repeat; background-attachment:fixed;  background-size:auto;height:auto;width:auto;background-position: center;">

<a href="#0" class="cd-top">Top</a>
<!-- cd-top JS -->
<script src="../js/main.js"></script>

<div class="container-fluid" style="background-color:#fff;margin:0;padding:0;">
	<?php require_once "./header.php"; ?>
</div>

<div class="container" style="-moz-opacity: 0.98;-khtml-opacity: 0.98;opacity: 0.98;background-color:#fff">
	<div class="row">
         <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 clearmp">
			
		    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="border-bottom:1px solid #000;font-size:18px;color:#000;"><span class="glyphicon glyphicon-cog"></span>&nbsp;ตั้งค่าระบบ</div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >&nbsp;</div>
		    <form name="form1" enctype="multipart/form-data" method="post" action="configs_eresearch.php" onSubmit="return c_check();" role="form">

			  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right" style="margin:0;padding:0;">ชื่อหน่วยงาน&nbsp;:&nbsp;</div>
				<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8" style="margin:0;padding:0;"><input type="text" name="s_ec_name" id="s_ec_name" maxlength="255" class="form-control" style="max-width:400px;" value="<?= $c_ec_name;?>"></div>
			  </div>
			  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><hr align="center" width="90%" noshade size="1"></div>
			  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 palignr" style="margin:0;padding:0;">&nbsp;:&nbsp;Shotcut icon(ico 32x32px)&nbsp;:&nbsp;</div>
				<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 palignl" style="margin:0;padding:0;">
					<?php
						if(!empty($c_ec_favicon_ico)){
							$images = "../images/".$c_ec_favicon_ico;
							echo "<div id=\"img4\"><img src=\"$images\" id=\"photoImage4\" class=\"img-thumbnail-noborder\"></div> ";
						}
						else{
							$images = "../images/16x9.png";
							echo "<div id=\"img4\" style=\"display:none;\"><img src=\"$images\" id=\"photoImage4\" class=\"img-thumbnail-noborder\"></div>";
						}
					?>
					<input type="file" name="file4" id="file4" size="30" onchange="loadPicture('photoImage4','img4',this);" style="margin-top:3px;" accept=".ico">
				</div>
			  </div>
			   <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">&nbsp;</div>
			  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 palignr" style="margin:0;padding:0;">&nbsp;:&nbsp;Shotcut icon(png 32x32px)&nbsp;:&nbsp;</div>
				<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 palignl" style="margin:0;padding:0;">
					<?php
						if(!empty($c_ec_favicon)){
							$images = "../images/".$c_ec_favicon;
							echo "<div id=\"img3\"><img src=\"$images\" id=\"photoImage3\" class=\"img-thumbnail-noborder\"></div> ";
						}
						else{
							$images = "../images/16x9.png";
							echo "<div id=\"img3\" style=\"display:none;\"><img src=\"$images\" id=\"photoImage3\" class=\"img-thumbnail-noborder\"></div>";
						}
					?>
					<input type="file" name="file3" id="file3" size="30" onchange="loadPicture('photoImage3','img3',this);" style="margin-top:3px;" accept=".png">
				</div>
			  </div>
			  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><hr align="center" width="90%" noshade size="1"></div>
			  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 palignr" style="margin:0;padding:0;">&nbsp;:&nbsp;โลโก้(png สูง 50px)&nbsp;:&nbsp;</div>
				<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 palignl" style="margin:0;padding:0;">
					<?php
						if(!empty($c_ec_logo)){
							$images = "../images/".$c_ec_logo;
							echo "<div id=\"img1\"><img src=\"$images\" id=\"photoImage1\" class=\"img-thumbnail-noborder\"></div> ";
						}
						else{
							$images = "../images/16x9.png";
							echo "<div id=\"img1\" style=\"display:none;\"><img src=\"$images\" id=\"photoImage1\" class=\"img-thumbnail-noborder\"></div>";
						}
					?>
					<input type="file" name="file1" id="file1" size="30" onchange="loadPicture('photoImage1','img1',this);" style="margin-top:3px;" accept=".png">
				</div>
			  </div>
			  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><hr align="center" width="90%" noshade size="1"></div>
			  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 palignr" style="margin:0;padding:0;">&nbsp;:&nbsp;รูปหน่วยงาน(jpg,png สูง 650px)&nbsp;:&nbsp;</div>
				<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 palignl" style="margin:0;padding:0;">
					<?php
						if(!empty($c_ec_pichome)){
							$images = "../images/".$c_ec_pichome;
							echo "<div id=\"img2\"><img src=\"$images\" id=\"photoImage2\" class=\"img-thumbnail-noborder\"></div> ";
						}
						else{
							$images = "../images/16x9.png";
							echo "<div id=\"img2\" style=\"display:none;\"><img src=\"$images\" id=\"photoImage2\" class=\"img-thumbnail-noborder\"></div>";
						}
					?>
					<input type="file" name="file2" id="file2" size="30" onchange="loadPicture('photoImage2','img2',this);" style="margin-top:3px;" accept=".gif, .png, .jpg, .jpeg">
				</div>
			  </div>
			  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><hr align="center" width="90%" noshade size="1"></div>
			  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 palignr" style="margin:0;padding:0;">&nbsp;:&nbsp;คู่มือการใช้งานของแอดมิน&nbsp;:&nbsp;</div>
				<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 palignl" style="margin:0;padding:0;">
					<?php
						if(!empty($c_ec_manual_admin)){ 
							echo "<div id=\"view_at1\"><a href=\"../files/".$c_ec_manual_admin."\" target=\"_blank\"><span id=\"dfile1\">".$c_ec_manual_admin."</span></a>&nbsp;:&nbsp;</div>";
						}
					?>
					<input type="file" name="file_at1" id="file_at1" size="30" style="margin-top:3px;" accept="application/pdf">
				</div>
			  </div>
			  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><hr align="center" width="90%" noshade size="1"></div>
			  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 palignr" style="margin:0;padding:0;">&nbsp;:&nbsp;คู่มือการใช้งานของสมาชิก&nbsp;:&nbsp;</div>
				<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 palignl" style="margin:0;padding:0;">
					<?php
						if(!empty($c_ec_manual_member)){ 
							echo "<div id=\"view_at1\"><a href=\"../files/".$c_ec_manual_member."\" target=\"_blank\"><span id=\"dfile1\">".$c_ec_manual_member."</span></a>&nbsp;:&nbsp;</div>";
						}
					?>
					<input type="file" name="file_at2" id="file_at2" size="30" style="margin-top:3px;" accept="application/pdf">
				</div>
			  </div>
			  <!--<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><hr align="center" width="90%" noshade size="1"></div>
			  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 palignr">&nbsp;:&nbsp;คู่มือการใช้งานของบุคคลทั่วไป&nbsp;:&nbsp;</div>
				<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 palignl">
					<?php
						//if(!empty($c_ec_manual_guest)){ 
						//	echo "<div id=\"view_at3\"><a href=\"../files/".$c_ec_manual_guest."\" target=\"_blank\"><span id=\"dfile1\">".$c_ec_manual_guest."</span></a>&nbsp;:&nbsp;</div>";
						//}
					?>
					<input type="file" name="file_at3" id="file_at3" size="30" style="margin-top:3px;" accept="application/pdf">
				</div>
			  </div>-->
			  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><hr align="center" width="90%" noshade size="1"></div>
			  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-top:3px;">
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-6 text-right" style="margin:0;padding:0;">สีพื้นหลังแถบแสดงข้อมูล&nbsp;:&nbsp;</div>
				<div class="col-lg-8 col-md-8 col-sm-8 col-xs-6" style="margin:0;padding:0;"><input type="text" name="s_ec_bgshow" id="s_ec_bgshow" maxlength="10" class="form-control" style="max-width:100px;" value="<?= $c_ec_bgshow;?>" onkeyup="edshowbg();"></div>
			  </div>
			  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-top:3px;">
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-6 text-right" style="margin:0;padding:0;">สีตัวอักษรแถบแสดงข้อมูล&nbsp;:&nbsp;</div>
				<div class="col-lg-8 col-md-8 col-sm-8 col-xs-6" style="margin:0;padding:0;"><input type="text" name="s_ec_fontshow" id="s_ec_fontshow" maxlength="10" class="form-control" style="max-width:100px;" value="<?= $c_ec_fontshow;?>" onkeyup="edshowfc();"></div>
			  </div>
			  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-top:3px;">
				<div class="col-lg-4 col-md-4 col-sm-4 hidden-xs text-right">&nbsp;</div>
				<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 text-left" style="margin:0;padding:0;"><div id="edshow" class="edshow" style="background-color:#<?php echo __EC_BGSHOW__;?>;color:#<?php echo __EC_FONTSHOW__;?>;">ระบบคลังข้อมูลงานวิจัย</div></div>
			  </div>
			  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><hr align="center" width="90%" noshade size="1"></div>
			  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-top:3px;">
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-6 text-right" style="margin:0;padding:0;">Copyright&nbsp;:&nbsp;</div>
				<div class="col-lg-8 col-md-8 col-sm-8 col-xs-6" style="margin:0;padding:0;"><input type="text" name="s_ec_copyright" id="s_ec_copyright" maxlength="255" class="form-control" style="max-width:400px;" value="<?= $c_ec_copyright;?>" onkeyup="edshowfc();"></div>
			  </div>
			  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><hr align="center" width="90%" noshade size="1"></div>

		      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
   					<input type="hidden" name="chk_edit" value="1"> 
					<input type="submit" name="Submit" value=" บันทึก " class="btn btn-warning" style="width:100px;font-size:18px;">&nbsp;
			  </div>

			  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">&nbsp;</div>
			  
		  </form>

	     </div><!-- /.col-sm-12 -->

		 <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div align="center"><?php require_once "./footer.php"; ?></div>
	     </div>
		
	</div><!-- /.row -->
</div>

</body>
</html>

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
document.getElementById('s_ec_name').focus();
</script>