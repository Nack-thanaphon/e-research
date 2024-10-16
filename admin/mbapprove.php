<?php
session_start();
Header("Content-Type: text/html; charset=UTF-8");
require_once "../include/config.php";
require_once "../include/convars.php";
if (!defined('_web_path')) {
	exit();
}
if ( !isset($_SESSION["admin"]) || !isset($_SESSION["userlevel"]) || ($_SESSION["admin"]=="") )
{
	session_unset();session_destroy();
	echo "<div style='text-align:center;padding-top:30px;font-size:18px;'>Session Expired กรุณาเข้าระบบใหม่</div>";
	?>
	<script>
	alert("Session Expired กรุณาเข้าระบบใหม่");
	window.onunload = refreshParent;
    function refreshParent() {
        window.opener.location.reload();
    }
	window.close();
	</script>
	<?php
	exit();
 }

$admin = $_SESSION['admin'];
$userlevel = $_SESSION['userlevel'];

if(isset($_GET["iRegister"])){
	if($_GET["iRegister"] =="1"){
		unset($_SESSION["u_code_1"]);
	}
}
if(isset($_POST["iRegister"])){
	if($_POST["iRegister"] =="1"){
		unset($_SESSION["u_code_1"]);
	}
}

if(isset($_GET["req_id"])){	$req_id = $_GET["req_id"];} else {if(isset($_POST["req_id"])){$req_id = $_POST["req_id"];}}

include("../include/config_db.php");

if( isset($_POST["chk_edit"]) ){$chk_edit = $_POST["chk_edit"];} else {$chk_edit="";}

if($chk_edit=="1")
{
	$s_date = $_POST["s_year"]."-".$_POST["s_month"]."-".$_POST["s_day"];
	$s_er_answer_text = $_POST["s_er_answer_text"];
	$c_document_id = $_POST["document_id"];

	$s_approve = 0;	
	$sql = "select * from `ers_document_files` where (`document_id`='".$c_document_id."') order by `id`ASC";
	$dbquery = $mysqli->query($sql);
	$num_rows = $dbquery->num_rows;
	$dbquery->close();
	if($num_rows>0){
		$sql = "select * from `ers_member_request` where (`id`='".$req_id."')";
		$dbquery = $mysqli->query($sql) or die("Can't send query !!");
		$num_rows_d = $dbquery->num_rows;
		if($num_rows_d > 0) {
			$row = $dbquery->fetch_assoc();
			$c_member_id = $row["member_id"];
			$dbquery->close();
			for ($i = 1; $i <= $num_rows; $i++) {
				if( $_POST['chk'.$i] == '1' ) {
					$s_approve = 1;	
					$s_path_file = $_POST['path_file'.$i];
					$s_link = $_POST['s_link'.$i];
					$s_file_id = $_POST['s_file_id'.$i];
					$sql = "select * from `ers_member_files` where (`request_id`='".$req_id."') and (`emf_item`='$i')";
					$dbquery = $mysqli->query($sql);
					$num_rows_d = $dbquery->num_rows;
					$dbquery->free();
					if($num_rows_d > 0) {
						$sql_file = "update `ers_member_files` set `document_files_id`='$s_file_id',`emf_filename`='$s_path_file',`emf_link`='$s_link',`emf_approve`='1',`emf_expire_date`='$s_date' where (`request_id`='".$req_id."') and (`emf_item`='$i')";
						$dbquery = $mysqli->query($sql_file);
					} else {
						$sql_file="INSERT INTO `ers_member_files` (`request_id`,`emf_item`,`member_id`,`document_files_id`,`emf_filename`,`emf_link`,`emf_approve`,`emf_expire_date`,`update_date`,`update_user`) VALUES ('$req_id','$i','$c_member_id','$s_file_id','$s_path_file','$s_link','1','$s_date',now(),'$admin')";
						$dbquery = $mysqli->query($sql_file);
					}
				} else {
					$sql_file = "update `ers_member_files` set `emf_approve`='0',`emf_expire_date`='$s_date' where (`request_id`='".$req_id."') and (`emf_item`='$i')";
					$dbquery = $mysqli->query($sql_file);
				}
			}//for
		}
	
		if(isset($_POST["chk_disapproved"])){
			$s_chk_disapproved = $_POST["chk_disapproved"];
			if($s_chk_disapproved=='1'){
				$s_approve = 2;
			}
		}
		$sql = "update `ers_member_request` set `er_answer`='$s_approve',`er_answer_expire`='$s_date',`er_answer_text`='$s_er_answer_text',`er_answer_date`=now(),`er_answer_name`='$admin',`update_date`=now(),`update_user`='$admin' where (`id`='".$req_id."')";
		$dbquery = $mysqli->query($sql);

		include("../include/close_db.php");
		?>
		<script>
		alert("บันทึกการอนุมัติสำเร็จ");
		window.onunload = refreshParent;
		function refreshParent() {
			window.opener.location.reload();
		}
		window.close();
		</script>
	<?php
	}
	exit();
}

$c_document_id = 0;
$c_member_id = 0;
$c_er_request_text = '';
$c_er_request_date = '';
$c_er_answer = 0;
$c_er_answer_approve = '';
$c_date = explode("-",date("Y-m-d"));
$c_year = $c_date[0];
$c_month = $c_date[1];
$c_day = $c_date[2];
$c_er_answer_text = '';
$c_er_answer_date = '';
$c_er_answer_name = '';
$c_ed_name_th = '';
$c_name = '';
$c_username = '';
$c_institution = '';
$c_institution_type = 0;
$c_institution_other = '';
$c_phone = '';
$c_email = '';
$c_address = '';
$c_institution_name = "";

if(isset($req_id)){
	if(!empty($req_id)){
		$sql = "select * from `ers_member_request` where (`id`='$req_id')";
		$dbquery = $mysqli->query($sql) or die("Can't send query!");
		$tRows = $dbquery->num_rows;
		if($tRows>0){
			$row = $dbquery->fetch_assoc();
			$c_document_id = $row["document_id"];
			$c_member_id = $row["member_id"];
			$c_er_request_text = $row["er_request_text"];
			$c_er_request_date = dateThai_edo(substr($row["er_request_date"],0,10));
			$c_er_answer = $row["er_answer"];
			$c_er_answer_approve = "<span class='glyphicon glyphicon-remove' style='color:#ff0000;font-size:14px;'></span>";
			if($c_er_answer==1){
				$c_er_answer_approve = "<span class='glyphicon glyphicon-ok' style='color:#00cc00;font-size:14px;'></span>";
			}
			if(!empty($row["er_answer_expire"])) {
				$c_date = explode("-",substr($row["er_answer_expire"],0,10));
				$c_year = $c_date[0];
				$c_month = $c_date[1];
				$c_day = $c_date[2];
			}
			$c_er_answer_text = $row["er_answer_text"];
			if(!empty($row["er_answer_date"])) {
				$c_er_answer_date = dateThai_edo(substr($row["er_answer_date"],0,10));
			}
			$now_date = date('Y-m-d H:i:s');
			$now_timestamp = strtotime($now_date);
			if(!empty($row["er_answer_expire"])) {
				$expire_date = $row["er_answer_expire"];
				$expire_timestamp = strtotime($expire_date);
			} else {$expire_timestamp = 0;}
			$chk_expire = 1;
			if($now_timestamp > $expire_timestamp){
				$chk_expire = 0;
				$c_er_answer_approve = "<span class='glyphicon glyphicon-remove' style='color:#ff0000;font-size:14px;'></span>";
			}
			$c_er_answer_name = $row["er_answer_name"];
			$sql_d = "select * from `ers_document` where `id`='".$c_document_id."' ";
			$dbquery_d = $mysqli->query($sql_d);
			$nRows_d = $dbquery_d->num_rows;
			if($nRows_d>0){
				$result_d = $dbquery_d->fetch_assoc();
				$c_ed_name_th = $result_d["ed_name_th"];
			}
			$sql_d = "select * from `ers_member` where `id`='".$c_member_id."' ";
			$dbquery_d = $mysqli->query($sql_d);
			$nRows_d = $dbquery_d->num_rows;
			if($nRows_d>0){
				$result_d = $dbquery_d->fetch_assoc();
				$c_username = $result_d["em_username"];
				$c_name = $result_d["em_title"];
				$c_name .= $result_d["em_firstname"];
				$c_name .= " ".$result_d["em_lastname"];
				$c_name2 = $result_d["em_firstname"]." ".$result_d["em_lastname"];
				$c_institution = $result_d["em_institution"];
				$c_institution_type = $result_d["em_institution_type"];
				$c_institution_other = $result_d["em_institution_other"];
				$c_phone = $result_d["em_phone"];
				$c_email = $result_d["em_email"];
				$c_address = $result_d["em_address"];
				$c_institution_name = "";
				switch ($c_institution_type) {
					case '1': $c_institution_name = "หน่วยงานภาครัฐ/วิสาหกิจ"; break;
					case '2': $c_institution_name = "หน่วยงานภาคเอกชน"; break;
					case '3': $c_institution_name = "องค์กรอิสระ"; break;
					case '4': $c_institution_name = "สถาบันการศึกษาภาครัฐ"; break;
					case '5': $c_institution_name = "สถาบันการศึกษาเอกชน"; break;
					case '6': $c_institution_name = "ประชาชน"; break;
					case '7': $c_institution_name = $c_institution_other; break;
					default : $c_institution_name = "";
				}
			}
		} 
	}
} 
$mysqli->query("update `ers_member_request` set `er_request_read`='1',`er_request_counter`=`er_request_counter`+1 where `id` ='".$req_id."'");
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
    <title>ระบบคลังข้อมูลงานวิจัย <?php if(defined('__EC_NAME__')){echo __EC_NAME__;}?></title>
	<link href="../images/<?php if(defined('__EC_FAVICON__')){echo __FAVICON_ICO__;}?>" rel="icon" type="image/ico">
	<link href="../images/<?php if(defined('__EC_FAVICON__')){echo __EC_FAVICON__;}?>" rel="icon" type="image/png" sizes="32x32">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css?v=<?php echo filemtime('../bootstrap/css/bootstrap.min.css');?>">
    <script src="../js/jquery.min.js"></script>
    <script src="../bootstrap/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="./style_admin.css?v=<?php echo filemtime('./style_admin.css');?>">
	<link href="https://fonts.googleapis.com/css?family=Prompt" rel="stylesheet">
	<style>
	.col-lg-12 ,.col-lg-8 ,.col-lg-4 ,.col-lg-3 ,.col-lg-1 { margin:0;padding:0; }
	.col-md-12 ,.col-md-8 ,.col-md-4 ,.col-md-3 ,.col-md-1 { margin:0;padding:0; }
	.col-sm-12 ,.col-sm-8 ,.col-sm-4 ,.col-sm-3 ,.col-sm-1 { margin:0;padding:0; }
	</style>
</head>
<body role="document" style="background: url('../images/<?php if(defined('__EC_PICHOME__')){echo __EC_PICHOME__;}?>') no-repeat; background-attachment:fixed; background-size:contain;height:100%;width:100%;background-position: left center;">

<div class="container bgw1">

	<div class="row  bgw2">
		
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" style="margin-bottom:30px;background-color:#<?php echo __EC_BGSHOW__;?>;color:#<?php echo __EC_FONTSHOW__;?>;font-weight: bold;"><h4><?php if(defined('__EC_NAME__')){echo "การอนุมัติ การร้องขอเอกสารผลงานวิจัย";} else echo "ระบบคลังข้อมูลงานวิจัย";?></h4></div>

		<div style="padding-top:20px;">

		  <form name="form1"  method="post" action="mbapprove.php" role="form">

			  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding:3px;">
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right" style="">ชือสมาชิก&nbsp;:&nbsp;</div>
				<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8"><?php echo $c_name;?></div>
			  </div>
			  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding:3px;">
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">ชือ login&nbsp;:&nbsp;</div>
				<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8"><?php echo $c_username;?></div>
			  </div>
			  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding:3px;">
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">หน่วยงานที่สังกัด&nbsp;:&nbsp;</div>
				<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8"><?= $c_institution;?>&nbsp;<?= $c_institution_name;?></div>
			  </div>
			  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding:3px;">
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">โทรศัพท์&nbsp;:&nbsp;</div>
				<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8"><a href='tel:<?= $c_phone;?>' target="_blank" title='<?= $c_phone;?>'><?= $c_phone;?></a></div>
			  </div>
			  <?php
			    $subject_text = "ผลการร้องขอเอกสารผลงานวิจัย ".__EC_NAME__;
			    $encodedSubject = htmlspecialchars($subject_text);
			    $body_text = "สวัสดีคุณ ".$c_name2;
				if($c_er_answer==2){
					$body_text .= " ,ผลการอนุมัติ : ไม่อนุมัติ ";
				}
				if($c_er_answer==1){
					if($chk_expire==1){
						$body_text .= " ,ผลการอนุมัติ : อนุมัติ  ,ดาวน์โหลดเอกสารได้ที่ "._web_path."/member/";
					}
				}
			    $encodedBody = htmlspecialchars($body_text);
			  ?>
			  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding:3px;">
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">อีเมล&nbsp;:&nbsp;</div>
				<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8"><a href='mailto:<?= $c_email;?>?subject=<?= $encodedSubject;?>&body=<?= $encodedBody;?>' target='_blank' title="<?= $c_email;?>"><?= $c_email;?></a></div>
			  </div>
			  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding:3px;">
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">ที่อยู่&nbsp;:&nbsp;</div>
				<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8"><?= $c_address;?></div>
			  </div>
			  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding:3px;">
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">ผลงานวิจัย&nbsp;:&nbsp;</div>
				<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8"><?php echo $c_ed_name_th;?></div>
			  </div>
			  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding:3px;">
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">ข้อความร้องขอ&nbsp;:&nbsp;</div>
				<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8"><?php echo $c_er_request_text;?></div>
			  </div>
			  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding:3px;">
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">ข้อความตอบกลับ&nbsp;:&nbsp;</div>
				<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
				<textarea name="s_er_answer_text" id="s_er_answer_text" rows="5" class="form-control" style="border-radius:5px;border:1px solid #ccc;max-width:500px;" maxlength="500" placeholder="ระบุข้อความรายละเอียด ตอบกลับการขอเอกสารงานวิจัย"><?= $c_er_answer_text;?></textarea>
				</div>
			  </div>
			  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding:3px;">
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">วันที่หมดอายุ&nbsp;:&nbsp;</div>
				<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
				  <select name="s_day" id="s_day" style="width:50px;border-radius:5px;border:1px solid #cccccc;">
						<option value="0">วันที่</option>
						<?php
						for($i=1;$i<=31;$i++){
							if($c_day==$i) $selected="selected='selected'";
							else  $selected="";
						?>
							<option value="<?= $i;?>" <?= $selected; ?>>
							  <?= $i;?>
							</option>
						<?php } ?>
				  </select>
				  <select name="s_month" id="s_month" style="width:100px;border-radius:5px;border:1px solid #cccccc;">
					<option value="0">เดือน</option>
					<option value="01" <?php if($c_month=="01")echo "selected='selected'";?> >มกราคม</option>
					<option value="02" <?php if($c_month=="02")echo "selected='selected'";?>>กุมภาพันธ์</option>
					<option value="03" <?php if($c_month=="03")echo "selected='selected'";?>>มีนาคม</option>
					<option value="04" <?php if($c_month=="04")echo "selected='selected'";?>>เมษายน</option>
					<option value="05" <?php if($c_month=="05")echo "selected='selected'";?>>พฤษภาคม</option>
					<option value="06" <?php if($c_month=="06")echo "selected='selected'";?>>มิถุนายน</option>
					<option value="07" <?php if($c_month=="07")echo "selected='selected'";?>>กรกฎาคม</option>
					<option value="08" <?php if($c_month=="08")echo "selected='selected'";?>>สิงหาคม</option>
					<option value="09" <?php if($c_month=="09")echo "selected='selected'";?>>กันยายน</option>
					<option value="10" <?php if($c_month=="10")echo "selected='selected'";?>>ตุลาคม</option>
					<option value="11" <?php if($c_month=="11")echo "selected='selected'";?>>พฤศจิกายน</option>
					<option value="12" <?php if($c_month=="12")echo "selected='selected'";?>>ธันวาคม</option>
				  </select>
				  <select name="s_year" id="s_year" style="width:70px;border-radius:5px;border:1px solid #cccccc;">
					<option value="0">ปี</option>
					<?php
					$start_y=(date("Y")-5); 
					$end_y=(date("Y")+10);
					for($yy=$start_y; $yy<=$end_y; $yy++){
						if($c_year==$yy) $selected_y="selected='selected'";
						else  $selected_y="";
					?>
						<option value="<?= $yy;?>" <?= $selected_y;?>>
					      <?= $yy+543;?>
						</option>
					<?php } ?>
				  </select>
				</div>
			  </div>
			  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
				<hr align="center" width="95%" noshade size="1" color="#cccccc">
			  </div>
			  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
				<input type='checkbox' name='chk_disapproved' id='chk_disapproved' value='1' <?php if($c_er_answer=='2'){echo "checked";}?> onclick="chkdisapprove()">&nbsp;<span style="color:#ff0000;">ไม่อนุมัติ</span>
			  </div>
			  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
				<hr align="center" width="95%" noshade size="1" color="#cccccc">
			  </div>
			  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
				<input type='checkbox' name='chk_approved' id='chk_approved' value='1' <?php if($c_er_answer=='1'){echo "checked";}?> onclick="chkapprove()">&nbsp;<span style="color:#ff0000;">อนุมัติทั้งหมด</span>
			  </div>
			  <?php
				$sql = "select * from `ers_document_files` where (`document_id`='".$c_document_id."') order by `id`ASC";
				$dbquery = $mysqli->query($sql);
				$num_rows = $dbquery->num_rows;
				if($num_rows>0)
				{
					$item = 0;
					While($row_df= $dbquery->fetch_assoc()){
						$item += 1;
						$c_edf_id = $row_df["id"];
						$c_edf_filename = $row_df["edf_filename"];
						$c_edf_link = $row_df["edf_link"];
						$c_edf_counter_read = $row_df["edf_counter_open_member"];
						$c_edf_counter_download =$row_df["edf_counter_download_member"];
						$sql_d = "select * from `ers_member_files` where (`request_id`='".$req_id."') and (`emf_item`='".$item."')";
						$dbquery_d = $mysqli->query($sql_d);
						$num_rows_d = $dbquery_d->num_rows;
						$c_chk_approve = 0;
						if($num_rows_d > 0) {
							$result_d = $dbquery_d->fetch_assoc();
							$c_emf_approve = $result_d["emf_approve"];
							if($c_emf_approve=='1') {
								$c_chk_approve = 1;
							}
						}
						if($c_edf_filename !="" ){ 
							if($item==1){
							?>
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
								<hr align="center" width="95%" noshade size="1" color="#cccccc">
							</div>
							<?php } ?>
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right" style="font-weight: bold;white-space: nowrap;">เอกสาร <?= $item;?>&nbsp;:&nbsp;</div>
								<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
									<div id="view<?= $item;?>"><input type='checkbox' name='chk<?= $item;?>' id='chkapp<?= $item;?>' value='1' <?php if($c_chk_approve=='1'){echo "checked";}?>>&nbsp;<span style="color:#00cc00;">อนุมัติ</span>&nbsp;<span class='glyphicon glyphicon-file' style='color:#000000'></span>&nbsp;<?= $c_edf_filename;?>&nbsp;</div>
									<input type="hidden" name="path_file<?= $item;?>" value="<?php if(isset($c_edf_filename)){ echo $c_edf_filename;}else{ echo '';}?>">
									<input type="hidden" name="s_link<?= $item;?>" value="<?php if(isset($c_edf_link)){ echo $c_edf_link;}else{ echo '';}?>">
									<input type="hidden" name="s_file_id<?= $item;?>" value="<?php if(isset($c_edf_id)){ echo $c_edf_id;}else{ echo '';}?>">
								</div>
							</div>
							<?php
						}
					}//while
				}
			  ?>
			  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">&nbsp;</div>

		      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
					<input type="hidden" name="req_id" value="<?php if(isset($req_id)){ echo $req_id;}else{ echo '';}?>">
					<input type="hidden" name="document_id" value="<?php if(isset($c_document_id)){ echo $c_document_id;}else{ echo '';}?>">
   					<input type="hidden" name="chk_edit" value="1"> 
					<input type="submit" name="Submit" value=" บันทึกการอนุมัติ/ไม่อนุมัติ " class="btn btn-warning" style="width:200px;font-size:16px;">&nbsp;
			  </div>

			  <div class="ccol-lg-12 col-md-12 col-sm-12 col-xs-12">&nbsp;</div>

		  </form>

		</div>

	</div><!-- /.row -->

</div><!-- /.container -->

</body>
</html>

<script>
function chkapprove(){
	if(document.getElementById('chk_approved').checked == true)
	{
		document.getElementById('chk_disapproved').checked = false;
		document.getElementById('chkapp1').checked = true;
		document.getElementById('chkapp2').checked = true;
		document.getElementById('chkapp3').checked = true;
		document.getElementById('chkapp4').checked = true;
		document.getElementById('chkapp5').checked = true;
		document.getElementById('chkapp6').checked = true;
		document.getElementById('chkapp7').checked = true;
		document.getElementById('chkapp8').checked = true;
		document.getElementById('chkapp9').checked = true;
		document.getElementById('chkapp10').checked = true;
		document.getElementById('chkapp11').checked = true;
		document.getElementById('chkapp12').checked = true;
		document.getElementById('chkapp13').checked = true;
		document.getElementById('chkapp14').checked = true;
		document.getElementById('chkapp15').checked = true;
		document.getElementById('chkapp16').checked = true;
		document.getElementById('chkapp17').checked = true;
		document.getElementById('chkapp18').checked = true;
		document.getElementById('chkapp19').checked = true;
		document.getElementById('chkapp20').checked = true;
	} else {
		document.getElementById('chkapp1').checked = false;
		document.getElementById('chkapp2').checked = false;
		document.getElementById('chkapp3').checked = false;
		document.getElementById('chkapp4').checked = false;
		document.getElementById('chkapp5').checked = false;
		document.getElementById('chkapp6').checked = false;
		document.getElementById('chkapp7').checked = false;
		document.getElementById('chkapp8').checked = false;
		document.getElementById('chkapp9').checked = false;
		document.getElementById('chkapp10').checked = false;
		document.getElementById('chkapp11').checked = false;
		document.getElementById('chkapp12').checked = false;
		document.getElementById('chkapp13').checked = false;
		document.getElementById('chkapp14').checked = false;
		document.getElementById('chkapp15').checked = false;
		document.getElementById('chkapp16').checked = false;
		document.getElementById('chkapp17').checked = false;
		document.getElementById('chkapp18').checked = false;
		document.getElementById('chkapp19').checked = false;
		document.getElementById('chkapp20').checked = false;
	}
	return true;
}
function chkdisapprove(){
	if(document.getElementById('chk_disapproved').checked == true)
	{
		document.getElementById('chk_approved').checked = false
		document.getElementById('chkapp1').checked = false;
		document.getElementById('chkapp2').checked = false;
		document.getElementById('chkapp3').checked = false;
		document.getElementById('chkapp4').checked = false;
		document.getElementById('chkapp5').checked = false;
		document.getElementById('chkapp6').checked = false;
		document.getElementById('chkapp7').checked = false;
		document.getElementById('chkapp8').checked = false;
		document.getElementById('chkapp9').checked = false;
		document.getElementById('chkapp10').checked = false;
		document.getElementById('chkapp11').checked = false;
		document.getElementById('chkapp12').checked = false;
		document.getElementById('chkapp13').checked = false;
		document.getElementById('chkapp14').checked = false;
		document.getElementById('chkapp15').checked = false;
		document.getElementById('chkapp16').checked = false;
		document.getElementById('chkapp17').checked = false;
		document.getElementById('chkapp18').checked = false;
		document.getElementById('chkapp19').checked = false;
		document.getElementById('chkapp20').checked = false;
	}
	return true;
}
document.getElementById('s_er_answer_text').focus();
</script>