<?php
session_start();
Header("Content-Type: text/html; charset=UTF-8");
require_once "../include/config.php";
require_once "../include/convars.php";
if (!defined('_web_path')) {
	exit();
}
if (!isset($_SESSION["admin"]) || !isset($_SESSION["userlevel"]) || ($_SESSION["admin"] == "")) {
	session_unset();
	session_destroy();
	echo "<Script language=\"javascript\">window.location=\"login.php\"</script>";
	exit();
}
require_once "../include/chkuserlevel.php";

$admin = $_SESSION['admin'];
$userlevel = $_SESSION['userlevel'];

$_menu_id = 2;

if (isset($_GET["iRegister"])) {
	if ($_GET["iRegister"] == "1") {
		unset($_SESSION["u_code_1"]);
	}
}
if (isset($_POST["iRegister"])) {
	if ($_POST["iRegister"] == "1") {
		unset($_SESSION["u_code_1"]);
	}
}

$c_search_section = 0;
if (isset($_POST["f_section_id"])) {
	$c_search_section = $_POST["f_section_id"];
}
if (isset($_GET["f_section_id"])) {
	$c_search_section = $_GET["f_section_id"];
}
$c_search_faculty = 0;
if (isset($_POST["f_faculty_id"])) {
	$c_search_faculty = $_POST["f_faculty_id"];
}
if (isset($_GET["f_faculty_id"])) {
	$c_search_faculty = $_GET["f_faculty_id"];
}

if (isset($_GET["c_id"])) {
	$c_id = $_GET["c_id"];
} else {
	if (isset($_POST["c_id"])) {
		$c_id = $_POST["c_id"];
	}
}

include("../include/config_db.php");

if (isset($_POST["chk_edit"])) {
	$chk_edit = $_POST["chk_edit"];
} else {
	$chk_edit = "";
}

if ($chk_edit == "1") {
	function random_password($len)
	{
		srand((float)microtime() * 10000000);
		$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
		$ret_str = "";
		$num = strlen($chars);
		for ($i = 0; $i < $len; $i++) {
			$ret_str .= $chars[rand() % $num];
			$ret_str .= "";
		}
		return $ret_str;
	}

	$s_title_th_v = $_POST["s_title_th_v"];
	$s_title_th = "";
	if ($s_title_th_v == "1") {
		$s_title_th = "นาย";
	}
	if ($s_title_th_v == "2") {
		$s_title_th = "นาง";
	}
	if ($s_title_th_v == "3") {
		$s_title_th = "นางสาว";
	}
	if ($s_title_th_v == "4") {
		$s_title_th = $_POST["s_title_th_other"];
	}
	$s_firstname_th = $_POST["s_firstname_th"];
	$s_lastname_th = $_POST["s_lastname_th"];

	$s_title_en_v = $_POST["s_title_en_v"];
	$s_title_en = "";
	if ($s_title_en_v == "1") {
		$s_title_en = "Mr.";
	}
	if ($s_title_en_v == "2") {
		$s_title_en = "Mrs.";
	}
	if ($s_title_en_v == "3") {
		$s_title_en = "Miss";
	}
	if ($s_title_en_v == "4") {
		$s_title_en = $_POST["s_title_en_other"];
	}
	$s_firstname_en = $_POST["s_firstname_en"];
	$s_lastname_en = $_POST["s_lastname_en"];
	$s_idcard = $_POST["s_idcard"];
	$s_researcher_position_id = $_POST["s_researcher_position_id"];
	$s_section_id = $_POST["s_section_id"];
	$s_faculty_id = $_POST["s_faculty_id"];
	$s_campus_id = 1;
	$s_academic_position_id = $_POST["s_academic_position_id"];
	$s_phone = $_POST["s_phone"];
	$s_mobile = $_POST["s_mobile"];
	$s_fax = $_POST["s_fax"];
	$s_email = $_POST["s_email"];
	$s_highest = $_POST["s_highest"];
	$s_educationrecord = $_POST["s_educationrecord"];
	$s_workhistory = $_POST["s_workhistory"];
	$s_specialization = $_POST["s_specialization"];
	$s_experience = $_POST["s_experience"];

	$sql_d = "select * From `ers_researcher_position` where (`id`='" . $s_researcher_position_id . "')";
	$dbquery_d = $mysqli->query($sql_d);
	$nRows_d = $dbquery_d->num_rows;
	$s_status = 0;
	if ($nRows_d > 0) {
		$result_d = $dbquery_d->fetch_assoc();
		$s_status = $result_d['et_status'];
	}

	$now1 = date("Ymd");
	$passw1 = random_password(7);
	$path1 = "../photo/";
	$file1 = stripcslashes($_FILES['file1']['tmp_name']);
	$file_name1 = basename($_FILES['file1']['name']);
	$filenewcon1 = strstr($file_name1, '.');
	$filename1 = mb_substr($file_name1, 0, strlen($file_name1) - 4);

	if (!empty($file1)) {
		if (mb_strlen($filename1, 'UTF-8') > 10) {
			$filename31 = trim(mb_substr($filename1, 0, 10));
		}
		$filename1 = $filename1 . '_';
		$filesize1 = $_FILES['file3']['size'];
		$real_file = $file1;
		list($width, $height, $type,) = getimagesize($real_file);

		$new_type = "";
		if ($_FILES['file1']['type'] == "image/gif") {
			$new_type = "IMG_GIF";
		}
		if ($_FILES['file1']['type'] == "image/png") {
			$new_type = "IMG_PNG";
		}
		if ($_FILES['file1']['type'] == "image/pjpeg" || $_FILES['file1']['type'] == "image/jpeg") {
			$new_type = "IMG_JPG";
		}

		if ($height > 240) {
			// สร้างภาพ favicon สูงไม่เกิน 240px
			$new_height = 240;
			$new_width = round(($new_height / $height)  *  $width);

			$new_file = "$path1/$filename1$passw1$now1$filenewcon1";

			switch ($new_type) {
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

			switch ($new_type) {
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
			@chmod($new_file, 0744);
		} else {
			$new_file = "$path1/$filename1$passw1$now1$filenewcon1";
			move_uploaded_file($file1, $new_file);
			@chmod($new_file, 0744);
		}
		$sql = "select * from `ers_researcher` where (`id`='" . $c_id . "')";
		$dbquery = $mysqli->query($sql) or die("Can't send query !!");
		$num_rows = $dbquery->num_rows;
		$dbquery->close();
		if ($num_rows > 0) {
			$sql = "update `ers_researcher` set `ec_title_th`='$s_title_th',`ec_firstname_th`='$s_firstname_th',`ec_lastname_th`='$s_lastname_th',`ec_title_en`='$s_title_en',`ec_firstname_en`='$s_firstname_en',`ec_lastname_en`='$s_lastname_en',`ec_idcard`='$s_idcard',`researcher_position_id`='$s_researcher_position_id',`researcher_position_status`='$s_status',`section_id`='$s_section_id',`faculty_id`='$s_faculty_id',`campus_id`='$s_campus_id',`academic_position_id`='$s_academic_position_id',`ec_phone`='$s_phone',`ec_mobile`='$s_mobile',`ec_fax`='$s_fax',`ec_email`='$s_email',`ec_highest`='$s_highest',`ec_educationrecord`='$s_educationrecord',`ec_workhistory`='$s_workhistory',`ec_specialization`='$s_specialization',`ec_experience`='$s_experience',`ec_photopath`='$filename1$passw1$now1$filenewcon1',`update_date`=now(),`update_user`='$admin' where (`id`='" . $c_id . "')";
			$dbquery = $mysqli->query($sql) or die("ไม่สามารถบันทึกข้อมูลได้ !1");
		} else {
			if ($s_firstname_th != '') {
				$sql = "insert into `ers_researcher` (`ec_title_th`,`ec_firstname_th`,`ec_lastname_th`,`ec_title_en`,`ec_firstname_en`,`ec_lastname_en`,`ec_idcard`,`researcher_position_id`,`researcher_position_status`,`section_id`,`faculty_id`,`campus_id`,`academic_position_id`,`ec_phone`,`ec_mobile`,`ec_fax`,`ec_email`,`ec_highest`,`ec_educationrecord`,`ec_workhistory`,`ec_specialization`,`ec_experience`,`ec_photopath`,`update_date`,`update_user`) values ('$s_title_th','$s_firstname_th','$s_lastname_th','$s_title_en','$s_firstname_en','$s_lastname_en','$s_idcard','$s_researcher_position_id','$s_status','$s_section_id','$s_faculty_id','$s_campus_id','$s_academic_position_id','$s_phone','$s_mobile','$s_fax','$s_email','$s_highest','$s_educationrecord','$s_workhistory','$s_specialization','$s_experience','$filename1$passw1$now1$filenewcon1',now(),'$admin')";
				$dbquery = $mysqli->query($sql) or die("ไม่สามารถบันทึกข้อมูลได้ !2");
			}
		}
	} else {
		$sql = "select * from `ers_researcher` where (`id`='" . $c_id . "')";
		$dbquery = $mysqli->query($sql) or die("Can't send query !!");
		$num_rows = $dbquery->num_rows;
		$dbquery->close();
		if ($num_rows > 0) {
			$sql = "update `ers_researcher` set `ec_title_th`='$s_title_th',`ec_firstname_th`='$s_firstname_th',`ec_lastname_th`='$s_lastname_th',`ec_title_en`='$s_title_en',`ec_firstname_en`='$s_firstname_en',`ec_lastname_en`='$s_lastname_en',`ec_idcard`='$s_idcard',`researcher_position_id`='$s_researcher_position_id',`researcher_position_status`='$s_status',`section_id`='$s_section_id',`faculty_id`='$s_faculty_id',`campus_id`='$s_campus_id',`academic_position_id`='$s_academic_position_id',`ec_phone`='$s_phone',`ec_mobile`='$s_mobile',`ec_fax`='$s_fax',`ec_email`='$s_email',`ec_highest`='$s_highest',`ec_educationrecord`='$s_educationrecord',`ec_workhistory`='$s_workhistory',`ec_specialization`='$s_specialization',`ec_experience`='$s_experience',`update_date`=now(),`update_user`='$admin' where (`id`='" . $c_id . "')";
			$dbquery = $mysqli->query($sql) or die("ไม่สามารถบันทึกข้อมูลได้ !1");
		} else {
			if ($s_firstname_th != '') {
				$sql = "insert into `ers_researcher` (`ec_title_th`,`ec_firstname_th`,`ec_lastname_th`,`ec_title_en`,`ec_firstname_en`,`ec_lastname_en`,`ec_idcard`,`researcher_position_id`,`researcher_position_status`,`section_id`,`faculty_id`,`campus_id`,`academic_position_id`,`ec_phone`,`ec_mobile`,`ec_fax`,`ec_email`,`ec_highest`,`ec_educationrecord`,`ec_workhistory`,`ec_specialization`,`ec_experience`,`update_date`,`update_user`) values ('$s_title_th','$s_firstname_th','$s_lastname_th','$s_title_en','$s_firstname_en','$s_lastname_en','$s_idcard','$s_researcher_position_id','$s_status','$s_section_id','$s_faculty_id','$s_campus_id','$s_academic_position_id','$s_phone','$s_mobile','$s_fax','$s_email','$s_highest','$s_educationrecord','$s_workhistory','$s_specialization','$s_experience',now(),'$admin')";
				$dbquery = $mysqli->query($sql) or die("ไม่สามารถบันทึกข้อมูลได้ !2");
			}
		}
	}

	$c_id = "";
}

$c_title_th = '';
$c_title_th_v = '';
$c_firstname_th = '';
$c_lastname_th = '';
$c_title_en_v = '';
$c_firstname_en = '';
$c_lastname_en = '';
$c_idcard = '';
$c_researcher_position_id = '';
$c_section_id = 0;
$c_faculty_id = 0;
$c_campus_id = 0;
$c_academic_position_id = 0;
$c_phone = '';
$c_mobile = '';
$c_fax = '';
$c_email = '';
$c_highest = '';
$c_educationrecord = '';
$c_workhistory = '';
$c_specialization = '';
$c_experience = '';
$c_photopath = '';

if (isset($c_id)) {
	if ($c_id != '') {
		$sql = "select * from `ers_researcher` where (`id`='$c_id')";
		$dbquery = $mysqli->query($sql) or die("Can't send query!");
		$tRows = $dbquery->num_rows;
		if ($tRows > 0) {
			$row = $dbquery->fetch_assoc();
			$c_title_th = $row["ec_title_th"];
			if ($c_title_th == "นาย") {
				$c_title_th_v = '1';
			} elseif ($c_title_th == "นาง") {
				$c_title_th_v = '2';
			} elseif ($c_title_th == "นางสาว") {
				$c_title_th_v = '3';
			} elseif (!empty($c_title_th)) {
				$c_title_th_v = '4';
			}
			$c_firstname_th = $row["ec_firstname_th"];
			$c_lastname_th = $row["ec_lastname_th"];
			$c_title_en = $row["ec_title_en"];
			if ($c_title_en == "Mr.") {
				$c_title_en_v = '1';
			} elseif ($c_title_en == "Mrs.") {
				$c_title_en_v = '2';
			} elseif ($c_title_en == "Miss") {
				$c_title_en_v = '3';
			} elseif (!empty($c_title_en)) {
				$c_title_en_v = '4';
			}
			$c_firstname_en = $row["ec_firstname_en"];
			$c_lastname_en = $row["ec_lastname_en"];
			$c_idcard = $row["ec_idcard"];
			$c_researcher_position_id = $row["researcher_position_id"];
			$c_section_id = $row["section_id"];;
			$c_faculty_id = $row["faculty_id"];;
			$c_campus_id = $row["campus_id"];;
			$c_academic_position_id = $row["academic_position_id"];;
			$c_phone = $row["ec_phone"];
			$c_mobile = $row["ec_mobile"];
			$c_fax = $row["ec_fax"];
			$c_email = $row["ec_email"];
			$c_highest = $row["ec_highest"];
			$c_educationrecord = $row["ec_educationrecord"];
			$c_workhistory = $row["ec_workhistory"];
			$c_specialization = $row["ec_specialization"];
			$c_experience = $row["ec_experience"];
			$c_photopath = $row["ec_photopath"];
		}
		$dbquery->free();
		unset($dbquery);
	}
}
//echo $c_title_th_v." AAAAAA<br>";
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
	<title>ระบบคลังข้อมูลงานวิจัย <?php if (defined('__EC_NAME__')) {
										echo __EC_NAME__;
									} ?></title>
	<link href="../images/<?php if (defined('__EC_FAVICON__')) {
								echo __EC_FAVICON_ICO__;
							} ?>" rel="icon" type="image/ico">
	<link href="../images/<?php if (defined('__EC_FAVICON__')) {
								echo __EC_FAVICON__;
							} ?>" rel="icon" type="image/png" sizes="32x32">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css?v=<?php echo filemtime('../bootstrap/css/bootstrap.min.css'); ?>">
	<script src="../js/jquery.min.js"></script>
	<script src="../bootstrap/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="./style_admin.css?v=<?php echo filemtime('./style_admin.css'); ?>">
	<link href="https://fonts.googleapis.com/css?family=Prompt" rel="stylesheet">
	<style>
		.col-lg-12,
		.col-lg-8,
		.col-lg-6,
		.col-lg-4 {
			margin: 0;
			padding: 0;
		}

		.col-md-12,
		.col-md-8,
		.col-md-6,
		.col-md-4 {
			margin: 0;
			padding: 0;
		}

		.col-sm-12,
		.col-sm-8,
		.col-sm-6,
		.col-sm-4 {
			margin: 0;
			padding: 0;
		}
	</style>
	<script LANGUAGE="JavaScript">
		function c_check() {
			if (document.getElementById('s_firstname_th').value == "") {
				alert("'ชื่อ(ไทย)' จำเป็นต้องมีข้อมูล");
				document.getElementById('s_firstname_th').focus();
				return false;
			}
		}

		function c_check2() {
			/*if(document.getElementById('c_code_1').value == "")
			{
				alert("กรุณาระบุข้อความที่ต้องการค้นหา");
				document.getElementById('c_code_1').focus();
				return false;
			}*/
		}

		function chkother(v) {
			if (v == "4") {
				document.getElementById('s_title_th_other').style.display = "block";
				document.getElementById('s_title_th_other').focus();
			} else {
				document.getElementById('s_title_th_other').style.display = "none";
			}
		}

		function chkothereng(v) {
			if (v == "4") {
				document.getElementById('s_title_en_other').style.display = "block";
				document.getElementById('s_title_en_other').focus();
			} else {
				document.getElementById('s_title_en_other').style.display = "none";
			}
		}

		function loadPicture(pid, dimg, fid) {
			var files = fid.files;
			for (var i = 0; i < files.length; i++) {
				var file = files[i];
				var imageType = /image.*/;
				if (!file.type.match(imageType)) {
					continue;
				}
				var img = document.getElementById(pid);
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
	</script>
	<script type="text/javascript" src="ajax.js"></script>
	<script type="text/javascript" src="ajax_content.js"></script>
	<script LANGUAGE="JavaScript">
		function confirmDelete(span_id, id_order, filename, content_id, div_id) {
			if (confirm("ยืนยันการลบรูป " + filename)) {
				ajax_loadContent(span_id, 'delphoto.php', id_order, filename, content_id);
				document.getElementById(div_id).style.display = 'none';
			}
		}
	</script>
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
				<div class="col-sm-12 col-xs-12" style="border-bottom:1px solid #cc9900;font-size:18px;color:#cc9900;"><span class="glyphicon glyphicon-user"></span>&nbsp;บันทึกข้อมูลนักวิจัย</div>
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">&nbsp;</div>
			</div>
		</div>

		<div class="row">

			<div style="padding-top:10px;">

				<form enctype="multipart/form-data" name="form1" method="post" action="researcher.php" onSubmit="return c_check();" role="form">

					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">

						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding:3px;">
							<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">คำนำหน้าชื่อ(ไทย)&nbsp;:&nbsp;</div>
							<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
								<input name="s_title_th_v" type="radio" value="1" <?php if ($c_title_th_v == "1") {
																						echo "checked";
																					} ?> onclick="chkother('1')">&nbsp;นาย&nbsp;
								<input name="s_title_th_v" type="radio" value="2" <?php if ($c_title_th_v == "2") {
																						echo "checked";
																					} ?> onclick="chkother('2')">&nbsp;นาง&nbsp;
								<input name="s_title_th_v" type="radio" value="3" <?php if ($c_title_th_v == "3") {
																						echo "checked";
																					} ?> onclick="chkother('3')">&nbsp;นางสาว&nbsp;
								<input name="s_title_th_v" type="radio" value="4" <?php if ($c_title_th_v == "4") {
																						echo "checked";
																					} ?> onclick="chkother('4')">&nbsp;อื่นๆ
								<?php if ($c_title_th_v == "4") { ?>
									<input type="text" name="s_title_th_other" id="s_title_th_other" maxlength="30" class="form-control input_width2" value="<?= $c_title_th; ?>" placeholder="คำนำหน้าชื่อ(ภาษาไทย)">
								<?php } else { ?>
									<input type="text" name="s_title_th_other" id="s_title_th_other" maxlength="30" class="form-control input_width2" value="<?= $c_title_th; ?>" style="display:none;" placeholder="คำนำหน้าชื่อ(ภาษาไทย)">
								<?php } ?>
							</div>
						</div>
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding:3px;">
							<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">ชื่อ(ไทย)&nbsp;:&nbsp;</div>
							<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8"><input type="text" name="s_firstname_th" id="s_firstname_th" maxlength="120" class="form-control input_width" value="<?= $c_firstname_th; ?>"></div>
						</div>
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding:3px;">
							<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">นามสกุล(ไทย)&nbsp;:&nbsp;</div>
							<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8"><input type="text" name="s_lastname_th" id="s_lastname_th" maxlength="120" class="form-control input_width" value="<?= $c_lastname_th; ?>"></div>
						</div>
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding:3px;">
							<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">คำนำหน้าชื่อ(อังกฤษ)&nbsp;:&nbsp;</div>
							<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
								<input name="s_title_en_v" type="radio" value="1" <?php if ($c_title_en_v == "1") {
																						echo "checked";
																					} ?> onclick="chkothereng('1')">&nbsp;Mr.&nbsp;
								<input name="s_title_en_v" type="radio" value="2" <?php if ($c_title_en_v == "2") {
																						echo "checked";
																					} ?> onclick="chkothereng('2')">&nbsp;Mrs.&nbsp;
								<input name="s_title_en_v" type="radio" value="3" <?php if ($c_title_en_v == "3") {
																						echo "checked";
																					} ?> onclick="chkothereng('3')">&nbsp;Miss&nbsp;
								<input name="s_title_en_v" type="radio" value="4" <?php if ($c_title_en_v == "4") {
																						echo "checked";
																					} ?> onclick="chkothereng('4')">&nbsp;อื่นๆ
								<?php if ($c_title_en_v == "4") { ?>
									<input type="text" name="s_title_en_other" id="s_title_en_other" maxlength="30" class="form-control input_width2" value="<?= $c_title_en; ?>" placeholder="คำนำหน้าชื่อ(อังกฤษ)">
								<?php } else { ?>
									<input type="text" name="s_title_en_other" id="s_title_en_other" maxlength="30" class="form-control input_width2" value="<?= $c_title_en; ?>" style="display:none;" placeholder="คำนำหน้าชื่อ(อังกฤษ)">
								<?php } ?>
							</div>
						</div>
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding:3px;">
							<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">ชื่อ(อังกฤษ)&nbsp;:&nbsp;</div>
							<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8"><input type="text" name="s_firstname_en" id="s_firstname_en" maxlength="120" class="form-control input_width" value="<?= $c_firstname_en; ?>"></div>
						</div>
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding:3px;">
							<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">นามสกุล(อังกฤษ)&nbsp;:&nbsp;</div>
							<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8"><input type="text" name="s_lastname_en" id="s_lastname_en" maxlength="120" class="form-control input_width" value="<?= $c_lastname_en; ?>"></div>
						</div>
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding:3px;">
							<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">เลขบัตรประชาชน&nbsp;:&nbsp;</div>
							<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8"><input type="text" name="s_idcard" id="s_idcard" maxlength="20" class="form-control input_width2" value="<?= $c_idcard; ?>"></div>
						</div>
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding:3px;">
							<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">สถานภาพของนักวิจัย&nbsp;:&nbsp;</div>
							<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
								<?php
								$sql_d = "select * from `ers_researcher_position` where 1 ";
								$dbquery_d = $mysqli->query($sql_d);
								$nRows_d = $dbquery_d->num_rows;
								if ($nRows_d > 0) {
								?>
									<select name="s_researcher_position_id" id="s_researcher_position_id" style="width:200px;border-radius:5px;border:1px solid #cccccc;padding:3px;">
										<option value="0">เลือกสถานภาพของนักวิจัย</option>
										<?php while ($row_d = $dbquery_d->fetch_assoc()) {
											$c_status_name = "";
											if ($row_d['et_status'] == 1) {
												$c_status_name = "นักวิจัยภายใน : " . $row_d['et_name'];
											}
											if ($row_d['et_status'] == 2) {
												$c_status_name = "นักวิจัยภายนอก : " . $row_d['et_name'];
											}
										?>
											<option value="<?php echo $row_d['id']; ?>" <?php if ($c_researcher_position_id == $row_d['id']) echo "selected" ?>>&nbsp;&nbsp;- <?php echo $c_status_name; ?></option>
										<?php } //while 
										?>
									</select>
								<?php } ?>
							</div>
						</div>
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding:3px;">
							<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">ภาควิชา/ฝ่าย&nbsp;:&nbsp;</div>
							<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
								<?php
								$sql_d = "select * from `ers_section` where 1 ";
								$dbquery_d = $mysqli->query($sql_d);
								$nRows_d = $dbquery_d->num_rows;
								if ($nRows_d > 0) {
								?>
									<select name="s_section_id" id="s_section_id" style="width:200px;border-radius:5px;border:1px solid #cccccc;padding:3px;">
										<option value="0">เลือกภาควิชา/ฝ่าย</option>
										<?php while ($row_d = $dbquery_d->fetch_assoc()) { ?>
											<option value="<?php echo $row_d['id']; ?>" <?php if ($c_section_id == $row_d['id']) echo "selected" ?>>&nbsp;&nbsp;- <?php echo $row_d['es_name']; ?></option>
										<?php } //while 
										?>
									</select>
								<?php } ?>
							</div>
						</div>
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding:3px;">
							<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">ส่วนงาน&nbsp;:&nbsp;</div>
							<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
								<?php
								$sql_d = "select * from `ers_faculty` where 1 ";
								$dbquery_d = $mysqli->query($sql_d);
								$nRows_d = $dbquery_d->num_rows;
								if ($nRows_d > 0) {
								?>
									<select name="s_faculty_id" id="s_faculty_id" style="width:200px;border-radius:5px;border:1px solid #cccccc;padding:3px;">
										<option value="0">เลือกส่วนงาน</option>
										<?php while ($row_d = $dbquery_d->fetch_assoc()) { ?>
											<option value="<?php echo $row_d['id']; ?>" <?php if ($c_faculty_id == $row_d['id']) echo "selected" ?>>&nbsp;&nbsp;- <?php echo $row_d['ef_name']; ?></option>
										<?php } //while 
										?>
									</select>
								<?php } ?>
							</div>
						</div>
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding:3px;">
							<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">ตำแหน่งทางวิชาการ&nbsp;:&nbsp;</div>
							<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
								<?php
								$sql_d = "select * from `ers_academic_position` where 1 ";
								$dbquery_d = $mysqli->query($sql_d);
								$nRows_d = $dbquery_d->num_rows;
								if ($nRows_d > 0) {
								?>
									<select name="s_academic_position_id" id="s_academic_position_id" style="width:200px;border-radius:5px;border:1px solid #cccccc;padding:3px;">
										<option value="0">เลือกตำแหน่งทางวิชาการ</option>
										<?php while ($row_d = $dbquery_d->fetch_assoc()) { ?>
											<option value="<?php echo $row_d['id']; ?>" <?php if ($c_academic_position_id == $row_d['id']) echo "selected" ?>>&nbsp;&nbsp;- <?php echo $row_d['ea_name']; ?></option>
										<?php } //while 
										?>
									</select>
								<?php } ?>
							</div>
						</div>
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding:3px;">
							<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">โทรศัพท์ที่ทำงาน&nbsp;:&nbsp;</div>
							<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8"><input type="text" name="s_phone" id="s_phone" maxlength="50" class="form-control input_width2" value="<?= $c_phone; ?>"></div>
						</div>
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding:3px;">
							<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">โทรศัพท์มือถือ&nbsp;:&nbsp;</div>
							<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8"><input type="text" name="s_mobile" id="s_mobile" maxlength="50" class="form-control input_width2" value="<?= $c_mobile; ?>"></div>
						</div>

					</div><!-- /.col-lg-6 col-md-6 col-sm-6 col-xs-12 -->
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">

						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding:3px;">
							<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">โทรสาร&nbsp;:&nbsp;</div>
							<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8"><input type="text" name="s_fax" id="s_fax" maxlength="50" class="form-control input_width2" value="<?= $c_fax; ?>"></div>
						</div>
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding:3px;">
							<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">อีเมล&nbsp;:&nbsp;</div>
							<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8"><input type="text" name="s_email" id="s_email" maxlength="120" class="form-control input_width" value="<?= $c_email; ?>"></div>
						</div>
						<div class="col-lg-12 col-md-12 col-sm-12" style="padding:3px;">
							<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">วุฒิสูงสุด&nbsp;:&nbsp;</div>
							<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8"><input type="text" name="s_highest" id="s_highest" maxlength="120" class="form-control input_width" value="<?= $c_highest; ?>"></div>
						</div>
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding:3px;">
							<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">ประวัติการศึกษา&nbsp;:&nbsp;</div>
							<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8"><textarea name="s_educationrecord" id="s_educationrecord" cols="60" rows="5" class="form-control" style="border-radius:5px;border:1px solid #ccc;"><?php echo $c_educationrecord; ?></textarea></div>
						</div>
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding:3px;">
							<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">ประวัติการทำงาน&nbsp;:&nbsp;</div>
							<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8"><textarea name="s_workhistory" id="s_workhistory" cols="60" rows="5" class="form-control" style="border-radius:5px;border:1px solid #ccc;"><?php echo $c_workhistory; ?></textarea></div>
						</div>
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding:3px;">
							<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">เชี่ยวชาญในสาขาวิชา&nbsp;:&nbsp;</div>
							<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8"><input type="text" name="s_specialization" id="s_specialization" maxlength="255" class="form-control input_width" value="<?= $c_specialization; ?>"></div>
						</div>
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding:3px;">
							<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">ประสบการณ์พิเศษ&nbsp;:&nbsp;</div>
							<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8"><input type="text" name="s_experience" id="s_experience" maxlength="255" class="form-control input_width" value="<?= $c_experience; ?>"></div>
						</div>

					</div><!-- /.col-lg-6 col-md-6 col-sm-6 col-xs-12 -->

					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" style="padding:3px;">
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 palignr">&nbsp;:&nbsp;รูปนักวิจัย(180x240px)&nbsp;:&nbsp;</div>
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 palignl"><input type="file" name="file1" id="file1" size="30" onchange="loadPicture('photoImage1','img1',this);" accept=".gif, .png, .jpg, .jpeg"></span></div>
						<!--<div>&nbsp;:&nbsp;รูป(180x240 px)&nbsp;:&nbsp;</div>-->
					</div>
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
						<?php
						if (!empty($c_photopath)) {
							$images = "../photo/" . $c_photopath;
							echo "<span id=\"dfile1\"><div id=\"img1\"><div><img src=\"$images\" id=\"photoImage1\" class=\"img-thumbnail-noborder\"></div>";
							echo "<div style='padding-top:3px;'><input name=\"view1\" type=\"button\" onclick=\"javascript:confirmDelete('dfile1','" . $c_id . "','" . $c_photopath . "','1','view1')\" value=\" ลบรูป\" style=\"border-radius:5px;height:22px;color:red;\"></div></div></span>";
						} else {
							$images = "../images/16x9.png";
							echo "<div id=\"img1\" style=\"display:none;\"><img src=\"$images\" id=\"photoImage1\" class=\"img-thumbnail-noborder\"></div>";
						}
						?>
					</div>
					<!--<div style="margin-left:calc(50vw - (50vw / 2))"><input type="file" name="file1" id="file1" size="30" onchange="loadPicture('photoImage1','img1',this);" style="margin-top:3px;"></div>-->

					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">&nbsp;</div>

					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
						<input type="hidden" name="c_id" value="<?php if (isset($c_id)) {
																	echo $c_id;
																} else {
																	echo '';
																} ?>">
						<input type="hidden" name="chk_edit" value="1">
						<input type="submit" name="Submit" value=" บันทึก " class="btn btn-warning" style="width:100px;font-size:18px;">&nbsp;
					</div>

					<div class="ccol-lg-12 col-md-12 col-sm-12 col-xs-12">&nbsp;</div>

				</form>

			</div>

		</div><!-- /.row -->

		<div class="row">

			<hr align="center" width="100%" noshade size="1">
			<form name="form2" method="post" action="researcher.php#top_page" onSubmit="return c_check2();" role="form">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
					<div style="text-align:center;">
						<span class="sbreak1">
							<select name="f_section_id" id="f_section_id" style="width:155px;border-radius:5px;height:35px;border:1px solid #ccc;padding:5px; margin-top:3px;">
								<option value="0">ภาควิชา/ฝ่าย</option>
								<?php
								$sql_d = "select * from `ers_section` where 1 ";
								$dbquery_d = $mysqli->query($sql_d);
								$nRows_d = $dbquery_d->num_rows;
								if ($nRows_d > 0) {
								?>
									<?php while ($row_d = $dbquery_d->fetch_assoc()) { ?>
										<option value="<?php echo $row_d['id']; ?>" <?php if ($c_search_section == $row_d['id']) echo "selected" ?>>&nbsp;&nbsp;- <?php echo $row_d['es_name']; ?></option>
								<?php } //while
								} ?>
							</select>&nbsp;
							<select name="f_faculty_id" id="f_faculty_id" style="width:110px;border-radius:5px;height:35px;border:1px solid #ccc;padding:5px; margin-top:3px;">
								<option value="0">ส่วนงาน</option>
								<?php
								$sql_d = "select * from `ers_faculty` where 1 ";
								$dbquery_d = $mysqli->query($sql_d);
								$nRows_d = $dbquery_d->num_rows;
								if ($nRows_d > 0) {
								?>
									<?php while ($row_d = $dbquery_d->fetch_assoc()) { ?>
										<option value="<?php echo $row_d['id']; ?>" <?php if ($c_search_faculty == $row_d['id']) echo "selected" ?>>&nbsp;&nbsp;- <?php echo $row_d['ef_name']; ?></option>
								<?php } //while
								} ?>
							</select>&nbsp;

							<?php
							if (isset($_POST["c_code_1"])) {
								$c_code_1 = $_POST["c_code_1"];
							} else {
								$c_code_1 = "";
							}
							if (trim($c_code_1) != "") {
								$_SESSION["u_code_1"] = $c_code_1;
							} else {
								if (isset($_POST["c_code_1"])) {
									$_SESSION["u_code_1"] = "";
								}
							}
							?>
						</span>
						<span class="sbreak2">ค้นหาข้อมูล : <input type="text" name="c_code_1" id="c_code_1" class="searchbox-control" maxlength="30" placeholder="ข้อความที่ต้องการค้นหา">&nbsp;<input type="submit" name="Submit" id="Submit" value="ค้นหา" class="btn btn-success" style="width:70px;padding-left:3px;">&nbsp;<input type="button" name="Clear" id="Clear" value="ยกเลิก" class="btn btn-info" style="width:70px;margin-top:0;" onclick="window.location='researcher.php?iRegister=1';"></span>
					</div>
				</div>
			</form>

			<div class="clearfix"></div>
			<br>

			<?php
			if (isset($_GET["sh_order"])) {
				$sh_order = $_GET["sh_order"];
			} else {
				$sh_order = 0;
			}
			if (!isset($_GET["Page"])) {
				if ($sh_order == 1) {
					$sh_order = 0;
				} else {
					$sh_order = 1;
				}
			}
			if ($sh_order == 1) {
				$asort = "<span class='glyphicon glyphicon-arrow-up' style='font-size:8px;'></span>";
			} else {
				$asort = "<span class='glyphicon glyphicon-arrow-down' style='font-size:8px;'></span>";
			}
			if (isset($_GET["sd"])) {
				$sd = $_GET["sd"];
			} else {
				$sd = 0;
			}
			$a1sort = $a2sort = $a3sort = $a4sort = $a5sort = $a6sort = $a7sort = $a8sort = "<span class='glyphicon glyphicon-sort' style='font-size:8px;'></span>";
			switch ($sd) {
				case '1':
					$a1sort = $asort;
					break;
				case '2':
					$a2sort = $asort;
					break;
				case '3':
					$a3sort = $asort;
					break;
				case '4':
					$a4sort = $asort;
					break;
				case '5':
					$a5sort = $asort;
					break;
				case '6':
					$a6sort = $asort;
					break;
				case '7':
					$a7sort = $asort;
					break;
				case '8':
					$a8sort = $asort;
					break;
			}
			?>

			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" style="background-color:#<?php echo __EC_BGSHOW__; ?>;color:#<?php echo __EC_FONTSHOW__; ?>;border-radius: 5px 5px 0px 0px;">
				<h4>แสดงข้อมูลชื่อนักวิจัย</h4><a name="top_page"></a>
			</div>

			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="table-responsive">
					<table class="table table-striped">
						<thead>
							<tr style="background-color:#f7f7f7">
								<th>&nbsp;</th>
								<th>&nbsp;</th>
								<th style="text-align:center;"><a href="researcher.php?sd=1&sh_order=<?= $sh_order; ?>#top_page" target="_parent">ID <?= $a1sort; ?></a></th>
								<th><a href="researcher.php?sd=2&sh_order=<?= $sh_order; ?>#top_page" target="_parent">ชื่อ-นามสกุล <?= $a2sort; ?></a></th>
								<th><a href="researcher.php?sd=3&sh_order=<?= $sh_order; ?>#top_page" target="_parent">โทรศัพท์ที่ทำงาน <?= $a3sort; ?></a></th>
								<th><a href="researcher.php?sd=4&sh_order=<?= $sh_order; ?>#top_page" target="_parent">โทรศัพท์มือถือ <?= $a4sort; ?></a></th>
								<th><a href="researcher.php?sd=6&sh_order=<?= $sh_order; ?>#top_page" target="_parent">ภาควิชา/ฝ่าย <?= $a6sort; ?></a></th>
								<th><a href="researcher.php?sd=7&sh_order=<?= $sh_order; ?>#top_page" target="_parent">ส่วนงาน <?= $a7sort; ?></a></th>
								<th><a href="researcher.php?sd=8&sh_order=<?= $sh_order; ?>#top_page" target="_parent">สถานภาพ <?= $a8sort; ?></a></th>
							</tr>
						</thead>
						<tbody>

							<?php

							$sql = "select * From `ers_researcher` where 1 ";
							if (isset($_SESSION["u_code_1"])) {
								$u_code_1 = $_SESSION["u_code_1"];
								$sql .= "and ((`id` = '$u_code_1') or (`ec_title_th` like '%$u_code_1%') or (`ec_firstname_th` like '%$u_code_1%') or (`ec_lastname_th` like '%$u_code_1%') or (`ec_title_en` like '%$u_code_1%') or (`ec_firstname_en` like '%$u_code_1%') or (`ec_lastname_en` like '%$u_code_1%')or (`ec_phone` like '%$u_code_1%') or (`ec_mobile` like '%$u_code_1%') or (`ec_fax` like '%$u_code_1%') or (`ec_email` like '%$u_code_1%') or (`ec_highest` like '%$u_code_1%') or (`ec_educationrecord` like '%$u_code_1%') or (`ec_workhistory` like '%$u_code_1%') or (`ec_specialization` like '%$u_code_1%') or (`ec_specialization` like '%$u_code_1%')) ";
							}
							if ($c_search_section > 0) {
								$sql .= "and (`section_id` = '" . $c_search_section . "') ";
							}
							if ($c_search_faculty > 0) {
								$sql .= "and (`faculty_id` = '" . $c_search_faculty . "') ";
							}
							if (isset($_GET["sd"])) {
								$sd = trim($_GET["sd"]);
							} else {
								$sd = 0;
							}
							switch ($sd) {
								case '1':
									$sql .= "Order by `id` ";
									break;
								case '2':
									$sql .= "Order by `ec_firstname_th` ";
									break;
								case '3':
									$sql .= "Order by `ec_phone` ";
									break;
								case '4':
									$sql .= "Order by `ec_mobile` ";
									break;
								case '5':
									$sql .= "Order by `ec_email` ";
									break;
								case '6':
									$sql .= "Order by `section_id` ";
									break;
								case '7':
									$sql .= "Order by `faculty_id` ";
									break;
								case '8':
									$sql .= "Order by `researcher_position_id` ";
									break;
								default:
									$sql .= "Order by `id` ";
							}
							if ($sh_order == 1) {
								$sql .= "DESC ";
							} else {
								$sql .= "ASC ";
							}
							$res = $mysqli->query($sql);
							$totalRows = $res->num_rows;

							$Per_Page = 20;
							$Page = $_GET["Page"];
							if (!$_GET["Page"]) {
								$Page = 1;
							}

							$Page_Start = (($Per_Page * $Page) - $Per_Page);
							if ($totalRows <= $Per_Page) {
								$Num_Pages = 1;
							} else if (($totalRows % $Per_Page) == 0) {
								$Num_Pages = ($totalRows / $Per_Page);
							} else {
								$Num_Pages = ($totalRows / $Per_Page) + 1;
								$Num_Pages = (int)$Num_Pages;
							}
							if (!($Page_Start)) {
								$Page_Start = 0;
							}

							$sql .= " LIMIT $Page_Start,$Per_Page";
							$res = $mysqli->query($sql);

							if ($totalRows != "0") {

								if ($Page == 1) {
									$jk = 0;
								} else {
									$a = $Page * $Per_Page;
									$jk = $a - $Per_Page;
								}

								while ($result = $res->fetch_assoc()) {
									$jk++;
									$c_id = $result["id"];
									$c_name = $result["ec_firstname_th"];
									if (!empty($result["ec_title_th"])) {
										$c_name = $result["ec_title_th"] . $c_name;
									}
									if (!empty($result["ec_lastname_th"])) {
										$c_name .= " " . $result["ec_lastname_th"];
									}
									$c_phone = $result["ec_phone"];
									$c_mobile = $result["ec_mobile"];
									$c_email = $result["ec_email"];
									$c_section_id = $result["section_id"];
									$c_faculty_id = $result["faculty_id"];
									$c_researcher_position_id = $result["researcher_position_id"];
									$sql_d = "select * From `ers_researcher_position` where (`id`='" . $c_researcher_position_id . "')";
									$dbquery_d = $mysqli->query($sql_d);
									$nRows_d = $dbquery_d->num_rows;
									$s_status_name = "";
									$c_status = 0;
									if ($nRows_d > 0) {
										$result_d = $dbquery_d->fetch_assoc();
										$s_status_name = $result_d['et_name'];
										$c_status = $result_d["et_status"];
									}
									$c_status_name = "";
									if ($c_status == 1) {
										$c_status_name = "นักวิจัยภายใน : " . $s_status_name;
									}
									if ($c_status == 2) {
										$c_status_name = "นักวิจัยภายนอก : " . $s_status_name;
									}

									$sql_d = "select * from `ers_section` where `id`='" . $c_section_id . "' ";
									$dbquery_d = $mysqli->query($sql_d);
									$nRows_d = $dbquery_d->num_rows;
									$c_section_name = "";
									if ($nRows_d > 0) {
										$result_d = $dbquery_d->fetch_assoc();
										$c_section_name = $result_d["es_name"];
									}
									$sql_d = "select * from `ers_faculty` where `id`='" . $c_faculty_id . "' ";
									$dbquery_d = $mysqli->query($sql_d);
									$nRows_d = $dbquery_d->num_rows;
									$c_faculty_name = "";
									if ($nRows_d > 0) {
										$result_d = $dbquery_d->fetch_assoc();
										$c_faculty_name = $result_d["ef_name"];
									}

									$bcolor = "#ffffff";
									if (($jk % 2) == 0) {
										$bcolor = "rgba(236, 240, 241, 0.8)";
									}
									$code_1 = $c_name;

							?>

									<tr style="background-color:<?= $bcolor; ?>">
										<td style="text-align:center;width:100px;min-width:100px;">
											<?php
											echo "<a href='researcher.php?c_id=$c_id' style='color:green;font-size:16px;' title='แก้ไข'><span class='glyphicon glyphicon-edit'></span>&nbsp;<span style='font-size:14px;'>แก้ไข</span></a>";
											?>
										</td>
										<td style="text-align:center;width:100px;min-width:100px;">
											<?php
											echo "<a href='del_data.php?c_id=$c_id&chk_p=6&code_1=$code_1' style='color:red;font-size:16px;' title='ลบ'><span class='glyphicon glyphicon-trash'></span>&nbsp;<span style='font-size:14px;'>ลบ</span></a>";
											?>
										</td>
										<td style="text-align:center;"><?= $c_id; ?></td>
										<td><?= $c_name; ?></td>
										<td><?= $c_phone; ?></td>
										<td><?= $c_mobile; ?></td>
										<td><?= $c_section_name; ?></td>
										<td><?= $c_faculty_name; ?></td>
										<td><?= $c_status_name; ?></td>
									</tr>

							<?php
								} //while
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
				<div style="font-size:14px;">หน้า :
					<?php
					$pages = new Paginator;
					$pages->items_total = $totalRows;
					$pages->mid_range = 7;
					$pages->current_page = $Page;
					$pages->default_ipp = $Per_Page;
					$pages->url_next = $_SERVER["PHP_SELF"] . "?&f_section_id=$c_search_section&f_faculty_id=$c_search_faculty&sd=$sd&sh_order=$sh_order&Page=";
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

<script>
	var sw = screen.width;
	if (sw < 768) {
		document.getElementById("fmnavbar").className = "navbar navbar-default navbar-fixed-top";
		document.getElementById("headernd_mobile").style.display = "";
	}
	$(window).resize(function() {
		var windowWidth = $(window).width();
		if (windowWidth < 768) {
			document.getElementById("fmnavbar").className = "navbar navbar-default navbar-fixed-top";
			document.getElementById("headernd_mobile").style.display = "";
		} else {
			document.getElementById("fmnavbar").className = "navbar navbar-default";
			document.getElementById("headernd_mobile").style.display = "none";
		}
	});
	document.getElementById('s_firstname_th').focus();
</script>