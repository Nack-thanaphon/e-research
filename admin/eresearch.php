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
	echo "<Script language=\"javascript\">window.location=\"login.php\"</script>";
	exit();
}
require_once "../include/chkuserlevel.php";

$admin = $_SESSION['admin'];
$userlevel = $_SESSION['userlevel'];

$_menu_id = 2;

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
$c_search_section = 0;
if(isset($_POST["f_section_id"])){
	$c_search_section = $_POST["f_section_id"];
}
if(isset($_GET["f_section_id"])){
	$c_search_section = $_GET["f_section_id"];
}
$c_search_faculty = 0;
if(isset($_POST["f_faculty_id"])){
	$c_search_faculty = $_POST["f_faculty_id"];
}
if(isset($_GET["f_faculty_id"])){
	$c_search_faculty = $_GET["f_faculty_id"];
}
$c_search_year = "";
if(isset($_POST["f_ed_year"])){
	$c_search_year = $_POST["f_ed_year"];
}
if(isset($_GET["f_ed_year"])){
	$c_search_year = $_GET["f_ed_year"];
}

if(isset($_GET["c_id"])){	$c_id = $_GET["c_id"];} else {if(isset($_POST["c_id"])){$c_id = $_POST["c_id"];}}

include("../include/config_db.php");

function _GetMaxAllowedUploadSize(){
    $Sizes1 = ini_get('upload_max_filesize');
    $Sizes2 = ini_get('post_max_size');
    return min($Sizes1,$Sizes2);
}
$_max_file_uploads = ini_get('max_file_uploads');
$_max_file_size = _GetMaxAllowedUploadSize();
if( isset($_POST["chk_edit"]) ){$chk_edit = $_POST["chk_edit"];} else {$chk_edit="";}

if($chk_edit=="1")
{	

	$s_ed_name_th = $_POST["s_ed_name_th"];
	$s_ed_name_en = $_POST["s_ed_name_en"];
	$s_campus_id = 1;
	$s_faculty_id = $_POST["s_faculty_id"];
	$s_section_id = $_POST["s_section_id"];
	$s_ed_year = $_POST["s_ed_year"];
	$s_ed_detail = $_POST["s_ed_detail"];
	$s_research_type_id = $_POST["s_research_type_id"];
	$s_ed_capital = $_POST["s_ed_capital"];

	$sql = "select * from `ers_document` where (`id`='".$c_id."')";
	$dbquery = $mysqli->query($sql) or die("Can't send query !!");
	$num_rows = $dbquery->num_rows;
	$dbquery->free();
	if($num_rows > 0) {
		$sql = "update `ers_document` set `ed_name_th`='$s_ed_name_th',`ed_name_en`='$s_ed_name_en',`campus_id`='$s_campus_id',`faculty_id`='$s_faculty_id',`section_id`='$s_section_id',`ed_detail`='$s_ed_detail',`ed_year`='$s_ed_year',`research_type_id`='$s_research_type_id',`ed_capital`='$s_ed_capital',`update_date`=now(),`update_user`='$admin' where (`id`='".$c_id."')";
		$dbquery = $mysqli->query($sql) or die("ไม่สามารถบันทึกข้อมูลได้ !1");
	}else {
		if(!empty($s_ed_name_th)){
			$sql = "insert into `ers_document` (`ed_name_th`,`ed_name_en`,`campus_id`,`faculty_id`,`section_id`,`ed_detail`,`ed_year`,`research_type_id`,`ed_capital`,`insert_date`,`update_date`,`update_user`) values ('$s_ed_name_th','$s_ed_name_en','$s_campus_id','$s_faculty_id','$s_section_id','$s_ed_detail','$s_ed_year','$s_research_type_id','$s_ed_capital',now(),now(),'$admin')";
			$dbquery = $mysqli->query($sql) or die("ไม่สามารถบันทึกข้อมูลได้ !2");
			$c_id = $mysqli->insert_id;
		}
	}
	if($c_id>0){
		for($i=1;$i<=5;$i++){
			if(isset($_POST["chk_doc$i"])){
				$s_edf_filename = $_POST["s_edf_filename$i"];
				$s_edf_filename2 = urldecode( $s_edf_filename);
				if(!empty($s_edf_filename)){
					   $sql = "select * from `ers_document_files` where (`document_id`='".$c_id."') and (`edf_item`='".$i."')";
					   $dbquery = $mysqli->query($sql) ;
					   $num_rows = $dbquery->num_rows;
					   if($num_rows > 0) {
						   $sql_file = "update `ers_document_files` set `edf_filename`='$s_edf_filename2',`edf_link`='1',`update_date`=now(),`update_user`='$admin' where (`document_id`='".$c_id."') and (`edf_item`='".$i."')";
					   } else {
							$sql_file="INSERT INTO `ers_document_files` (`document_id`,`edf_item`,`edf_filename`,`edf_link`,`update_date`,`update_user`) VALUES ('$c_id','$i','$s_edf_filename2','1',now(),'$admin')";
					   }
					   //echo '<br> sql_file = '.$sql_file;
					   $dbquery = $mysqli->query($sql_file);
				} else {
					$sql_file = "update `ers_document_files` set `edf_filename`='',`edf_link`='0',`update_date`=now(),`update_user`='$admin' where (`document_id`='".$c_id."') and (`edf_item`='".$i."')";
					 $dbquery = $mysqli->query($sql_file);
				}
			} else {
				if($_FILES["file$i"]["name"] != "")
				{
					$sur_num_text = strrchr($_FILES["file$i"]["name"], ".");
					$sur_num = strlen($sur_num_text);

					$file_name_sur = htmlspecialchars($_FILES["file$i"]["name"]);	
					$file_name = substr($file_name_sur,0,strlen($file_name_sur) - $sur_num);
					$file_name =str_replace(Array("\n", "\r", "\n\r",'+','-','%','@', '\'', '"', ',' , ';', '&lt;', '&gt;',' '), '_', $file_name);//preg_replace('/[^a-zA-Z0-9_ -]/s','',$file_name);
					if( mb_strlen($file_name,'UTF-8') > 100 ){
						$file_name = utf8_substr($file_name,0,100);
					}

					$sur1 = strrchr($_FILES["file$i"]["name"], "."); //ตัดนามสกุลไฟล์เก็บไว
					$sur1 = strtolower($sur1);
					$name = $file_name."_".(Date("dmY_His").$sur1);
					//$filename_upload = iconv("tis-620","utf-8",$name);
					$filename_upload = $name;

					if(move_uploaded_file($_FILES["file$i"]["tmp_name"],"../files/".$filename_upload))
					{
						
						   //echo "<br>Copy/Upload Complete<br>";
						   $sql = "select * from `ers_document_files` where (`document_id`='".$c_id."') and (`edf_item`='".$i."')";
						   $dbquery = $mysqli->query($sql) ;
						   $num_rows = $dbquery->num_rows;
						   if($num_rows > 0) {
							   $sql_file = "update `ers_document_files` set `edf_filename`='$name',`edf_link`='0',`update_date`=now(),`update_user`='$admin' where (`document_id`='".$c_id."') and (`edf_item`='".$i."')";
						   } else {
								$sql_file="INSERT INTO `ers_document_files` (`document_id`,`edf_item`,`edf_filename`,`edf_link`,`update_date`,`update_user`) VALUES ('$c_id','$i','$name','0',now(),'$admin')";
						   }
						   //echo '<br> sql_file = '.$sql_file;
						   //include("../include/close_db.php");
						   //exit();
						   $dbquery = $mysqli->query($sql_file);
					}
				}
			}
		}//for
		$sql = "select * from `ers_document_files` where (`document_id`='".$c_id."') order by `id`ASC";
		$dbquery_pdf = $mysqli->query($sql);
		$num_rows_files_pdf = $dbquery_pdf->num_rows;
		$dbquery_pdf->free();
		$num_rows_files_pdf += 1; 
		for ($i = 0; $i < count($_FILES['pdf_files']['name']); $i++) {
			$ext = explode('.', basename($_FILES['pdf_files']['name'][$i])); //explode file name from dot(.) 
			$file_extension = strtolower(end($ext)); //store extensions in the variable
			$file_name = trim($_FILES['pdf_files']['name'][$i]);
			$file_name = substr($file_name,0,strlen($file_name)-4);
			if( mb_strlen($file_name,'UTF-8') > 100 ){
				$file_name = utf8_substr($file_name,0,100);
			}
			$name = $file_name."_".(Date("dmY_His").".".$file_extension);
			$filename_upload = $name;
			//echo $filename_upload." AAA<br>";
			//echo $_FILES['pdf_files']['name'][$i]." BBBB<br>";
			$j = $i + $num_rows_files_pdf;
			if(move_uploaded_file($_FILES['pdf_files']['tmp_name'][$i],"../files/".$filename_upload)){
			   $sql = "select * from `ers_document_files` where (`document_id`='".$c_id."') and (`edf_item`='".$j."')";
			   $dbquery = $mysqli->query($sql) ;
			   $num_rows = $dbquery->num_rows;
			   if($num_rows > 0) {
				   $sql_file = "update `ers_document_files` set `edf_filename`='$name',`edf_link`='0',`update_date`=now(),`update_user`='$admin' where (`document_id`='".$c_id."') and (`edf_item`='".$j."')";
			   } else {
					$sql_file="INSERT INTO `ers_document_files` (`document_id`,`edf_item`,`edf_filename`,`edf_link`,`update_date`,`update_user`) VALUES ('$c_id','$j','$name','0',now(),'$admin')";
			   }
			   $dbquery = $mysqli->query($sql_file);
			}
		}//for
	}
	$c_id = "";
}

$c_date = explode("-",date("Y-m-d"));
$c_year = $c_date[0];
$c_ed_name_th = '';
$c_ed_name_en = '';
$c_section_id = 0;
$c_faculty_id = 0;
$c_ed_detail ="";
$c_ed_year = $c_year;
$c_research_type_id = 0;
$c_ed_capital = "";

$c_edf_id1 = 0;$c_edf_filename1 = '';$c_edf_link1 = 0;
$c_edf_id2 = 0;$c_edf_filename2 = '';$c_edf_link2 = 0;
$c_edf_id3 = 0;$c_edf_filename3 = '';$c_edf_link3 = 0;
$c_edf_id4 = 0;$c_edf_filename4 = '';$c_edf_link4 = 0;
$c_edf_id5 = 0;$c_edf_filename5 = '';$c_edf_link5 = 0;
$num_rows_files = 0;
if(isset($c_id)){
	if($c_id!=''){
		$sql = "select * from `ers_document` where (`id`='".$c_id."')";
		$dbquery = $mysqli->query($sql);
		$tRows = $dbquery->num_rows;
		if($tRows>0){
			$row = $dbquery->fetch_assoc();
			$c_ed_name_th = $row["ed_name_th"];
			$c_ed_name_en = $row["ed_name_en"];
			$c_section_id = $row["section_id"];
			$c_faculty_id = $row["faculty_id"];
			$c_ed_detail = $row["ed_detail"];
			if(!empty($row["ed_year"])){
				$c_ed_year = $row["ed_year"];
			}
			$c_research_type_id = $row["research_type_id"];
			$c_ed_capital = $row["ed_capital"];
		}
		$dbquery->free();
		$sql = "select * from `ers_document_files` where (`document_id`='".$c_id."') order by `id`ASC";
		$dbquery_pdf = $mysqli->query($sql);
		$num_rows_files = $dbquery_pdf->num_rows;
		if($num_rows_files>0)
		{
			$item = 1;
			While($row_pdf= $dbquery_pdf->fetch_assoc()){
				if($item == 1){
					$c_edf_id1 = $row_pdf["id"];$c_edf_filename1 = $row_pdf["edf_filename"];$c_edf_link1 = $row_pdf["edf_link"];
				}
				if($item == 2){
					$c_edf_id2 = $row_pdf["id"];$c_edf_filename2 = $row_pdf["edf_filename"];$c_edf_link2 = $row_pdf["edf_link"];
				}
				if($item == 3){
					$c_edf_id3 = $row_pdf["id"];$c_edf_filename3 = $row_pdf["edf_filename"];$c_edf_link3 = $row_pdf["edf_link"];
				}
				if($item == 4){
					$c_edf_id4 = $row_pdf["id"];$c_edf_filename4 = $row_pdf["edf_filename"];$c_edf_link4 = $row_pdf["edf_link"];
				}
				if($item == 5){
					$c_edf_id5 = $row_pdf["id"];$c_edf_filename5 = $row_pdf["edf_filename"];$c_edf_link5 = $row_pdf["edf_link"];
					break;
				}
				$item = $item + 1;
			}			
		}
	}
}
$_max_file_uploads_pdf = $_max_file_uploads - $num_rows_files;
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
	<script type="text/javascript" src="ajax.js"></script>
	<script type="text/javascript" src="ajax_content.js"></script>
	<style>
	.col-lg-12 ,.col-lg-8 ,.col-lg-4 { margin:0;padding:0; }
	.col-md-12 ,.col-md-8 ,.col-md-4 { margin:0;padding:0; }
	.col-sm-12 ,.col-sm-8 ,.col-sm-4 { margin:0;padding:0; }
	</style>
	<SCRIPT LANGUAGE="JavaScript">
	function c_check(){
		if(document.getElementById('s_ed_name_th').value == "")
		{
			alert("'ชื่อผลงาน(ไทย)' จำเป็นต้องมีข้อมูล");
			document.getElementById('s_ed_name_th').select();
			return false;
		}
		var selectfile = document.getElementById('pdf_files');
		/*if(selectfile.value==""){
			alert ("กรุณาเลือกไฟล์ด้วยค่ะ");
			return false;
		}*/
		if(selectfile.files.length > <?= $_max_file_uploads_pdf;?>){
			alert ("กรุณาเลือกไฟล์ pdf ไม่เกิน <?= $_max_file_uploads_pdf;?> ไฟล์ ค่ะ");
			selectfile.value="";
			return false;
		}
	}
	function c_check2(){
		/*if(document.getElementById('c_code_1').value == "")
		{
			alert("กรุณาใส่ข้อมูลที่ต้องการค้นหา");
			document.getElementById('c_code_1').select();
			return false;
		}*/
	}
	function confirmDelete(span_id,id_order,filename,content_id,div_id,inputid) {
	  if (confirm("ยืนยันการลบข้อมูล "+filename)) {
		ajax_loadContent(span_id,'delfiles.php',id_order,filename,content_id);
		document.getElementById(inputid).value = '';
		document.getElementById(div_id).style.display = 'none';
	  }
	}
	function check_doc(chkid,inputid,fileid){
		if(document.getElementById(chkid).checked == true)
		{
			document.getElementById(inputid).removeAttribute('readonly');
			document.getElementById(inputid).style.color = '#000000';
			document.getElementById(inputid).style.display = 'block';
			document.getElementById(fileid).value = '';
			document.getElementById(fileid).style.display = 'none';
			document.getElementById(inputid).focus();
		} else {
			document.getElementById(inputid).setAttribute('readonly', true);
			document.getElementById(inputid).style.color = '#999999';
			document.getElementById(inputid).style.display = 'none';
			document.getElementById(fileid).style.display = 'block';
		}
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
		   <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="border-bottom:1px solid #000099;font-size:18px;color:#000099;"><span class="glyphicon glyphicon-bullhorn"></span>&nbsp;บันทึกข้อมูลผลงานวิจัย</div>
		   <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >&nbsp;</div>
		</div><!-- /.col-lg-12 col-md-12 col-sm-12 col-xs-12 -->
	</div><!-- /.row -->

	<div class="row">
		<div style="padding-top:20px;">

		  <form enctype="multipart/form-data" name="form1"  method="post" action="eresearch.php" onSubmit="return c_check();" role="form">

			  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding:3px;">
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">ชื่อผลงาน(ไทย)&nbsp;:&nbsp;</div>
				<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8"><input type="text" name="s_ed_name_th" id="s_ed_name_th" maxlength="255" class="form-control input_width3"  value="<?= $c_ed_name_th;?>"></div>
			  </div>
			  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding:3px;">
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">ชื่อผลงาน(อังกฤษ)&nbsp;:&nbsp;</div>
				<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8"><input type="text" name="s_ed_name_en" id="s_ed_name_en" maxlength="255" class="form-control input_width3"  value="<?= $c_ed_name_en;?>"></div>
			  </div>
			  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding:3px;">
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">ภาควิชา/ฝ่าย&nbsp;:&nbsp;</div>
				<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
					<?php
					$sql_d = "select * from `ers_section` where 1 ";
					$dbquery_d = $mysqli->query($sql_d);
					$nRows_d = $dbquery_d->num_rows;
					if($nRows_d>0){
					?>
						<select name="s_section_id" id="s_section_id" style="width:200px;border-radius:5px;border:1px solid #cccccc;padding:3px;">
							<option value="0">เลือกภาควิชา/ฝ่าย</option>
							<?php while ($row_d = $dbquery_d->fetch_assoc()) { ?>
								<option value="<?php echo $row_d['id']; ?>" <?php if($c_section_id==$row_d['id']) echo "selected"?> >&nbsp;&nbsp;- <?php echo $row_d['es_name']; ?></option>
							<?php } //while ?>
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
					if($nRows_d>0){
					?>
						<select name="s_faculty_id" id="s_faculty_id" style="width:200px;border-radius:5px;border:1px solid #cccccc;padding:3px;">
							<option value="0">เลือกส่วนงาน</option>
							<?php while ($row_d = $dbquery_d->fetch_assoc()) { ?>
								<option value="<?php echo $row_d['id']; ?>" <?php if($c_faculty_id==$row_d['id']) echo "selected"?> >&nbsp;&nbsp;- <?php echo $row_d['ef_name']; ?></option>
							<?php } //while ?>
						</select>
					<?php } ?>
				</div>
			  </div>
			  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding:3px;">
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">รายละเอียดผลงาน&nbsp;:&nbsp;</div>
				<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8"><textarea name="s_ed_detail" id="s_ed_detail" rows="5" class="form-control input_width3" style="border-radius:5px;border:1px solid #ccc;"><?php echo $c_ed_detail; ?></textarea></div>
			  </div>			 
			  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding:3px;">
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">ปีงบประมาณ&nbsp;:&nbsp;</div>
				<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
					<select name="s_ed_year" id="s_ed_year" style="width:70px;border-radius:5px;border:1px solid #cccccc;">
					<option value="0">ปี</option>
					<?php
					$start_y=(date("Y")-40); 
					$end_y=(date("Y")+2);
					for($yy=$end_y; $yy>=$start_y; $yy--){
						if($c_ed_year==$yy) $selected_y="selected='selected'";
						else  $selected_y="";
					?>
						<option value="<?= $yy;?>" <?= $selected_y;?>>
					      <?= $yy+543;?>
						</option>
					<?php } ?>
				  </select>
				</div>
			  </div>
			  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding:3px;">
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">ประเภทของเงินอุดหนุนงานวิจัย&nbsp;:&nbsp;</div>
				<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
					<?php
					$sql_d = "select * from `ers_research_type` where 1 ";
					$dbquery_d = $mysqli->query($sql_d);
					$nRows_d = $dbquery_d->num_rows;
					if($nRows_d>0){
					?>
						<select name="s_research_type_id" id="s_research_type_id" style="width:200px;border-radius:5px;border:1px solid #cccccc;padding:3px;">
							<option value="0">เลือกประเภท</option>
							<?php while ($row_d = $dbquery_d->fetch_assoc()) { ?>
								<option value="<?php echo $row_d['id']; ?>" <?php if($c_research_type_id==$row_d['id']) echo "selected"?> >&nbsp;&nbsp;- <?php echo $row_d['et_name']; ?></option>
							<?php } //while ?>
						</select>
					<?php } ?>
				</div>
			  </div>
			  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding:3px;">
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">แหล่งทุน&nbsp;:&nbsp;</div>
				<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8"><input type="text" name="s_ed_capital" id="s_ed_capital" maxlength="120" class="form-control input_width3"  value="<?= $c_ed_capital;?>"></div>
			  </div>

			  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding:3px;">
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">เอกสาร 1&nbsp;:&nbsp;</div>
				<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
					<input type="checkbox" name="chk_doc1" id="chk_doc1" value="1" onclick="check_doc('chk_doc1','s_edf_filename1','file1')" <?php if($c_edf_link1==1){echo "checked";}?>>&nbsp;ระบุที่อยู่&nbsp;
					<?php if($c_edf_link1==1){?>
						<input type="text" name="s_edf_filename1" id="s_edf_filename1" class="form-control input_width3"  value="<?= $c_edf_filename1;?>" placeholder="ระบุที่อยู่ไฟล์เอกสาร">
						<input name="file1" type="file" id="file1" style="margin-top:3px;max-width:250px;display:none;" accept="application/pdf">
					<?php} else {?>
						<input type="text" name="s_edf_filename1" id="s_edf_filename1" class="form-control input_width3"  value="<?= $c_edf_filename1;?>" readonly="true" placeholder="ระบุที่อยู่ไฟล์เอกสาร" style="color:#999999;display:none;">
					<?php
						if($c_edf_filename1!=""){ 
							echo "<div id=\"view1\"><a href=\"../files/".$c_edf_filename1."\" target=\"_blank\"><span id=\"dfile1\">".$c_edf_filename1."</span></a>&nbsp;";
							echo "<input name=\"view1\" type=\"button\" onclick=\"javascript:confirmDelete('dfile1','".$c_edf_id1."','".$c_edf_filename1."','1','view1','s_edf_filename1')\" value=\" ลบไฟล์นี้ \" style=\"border-radius:5px;height:22px;color:red;\"></div>";
						}
					?>
						<input name="file1" type="file" id="file1" style="margin-top:3px;max-width:250px;" accept="application/pdf">
					<?php } ?>
				</div>
			  </div>
			  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding:3px;">
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">เอกสาร 2&nbsp;:&nbsp;</div>
				<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
					<input type="checkbox" name="chk_doc2" id="chk_doc2" value="1" onclick="check_doc('chk_doc2','s_edf_filename2','file2')" <?php if($c_edf_link2==1){echo "checked";}?>>&nbsp;ระบุที่อยู่&nbsp;
					<?php if($c_edf_link2==1){?>
						<input type="text" name="s_edf_filename2" id="s_edf_filename2" class="form-control input_width3"  value="<?= $c_edf_filename2;?>" placeholder="ระบุที่อยู่ไฟล์เอกสาร">
						<input name="file2" type="file" id="file2" style="margin-top:3px;max-width:250px;display:none;" accept="application/pdf">
					<?php} else {?>
						<input type="text" name="s_edf_filename2" id="s_edf_filename2" class="form-control input_width3"  value="<?= $c_edf_filename2;?>" readonly="true" placeholder="ระบุที่อยู่ไฟล์เอกสาร" style="color:#999999;display:none;">
					<?php
						if($c_edf_filename2!=""){ 
							echo "<div id=\"view2\"><a href=\"../files/".$c_edf_filename2."\" target=\"_blank\"><span id=\"dfile2\">".$c_edf_filename2."</span></a>&nbsp;";
							echo "<input name=\"view2\" type=\"button\" onclick=\"javascript:confirmDelete('dfile2','".$c_edf_id2."','".$c_edf_filename2."','1','view2','s_edf_filename2')\" value=\" ลบไฟล์นี้ \" style=\"border-radius:5px;height:22px;color:red;\"></div>";
						}
					?>
						<input name="file2" type="file" id="file2" style="margin-top:3px;max-width:250px;"  accept="application/pdf">
					<?php } ?>
				</div>
			  </div>
			  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding:3px;">
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">เอกสาร 3&nbsp;:&nbsp;</div>
				<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
					<input type="checkbox" name="chk_doc3" id="chk_doc3" value="1" onclick="check_doc('chk_doc3','s_edf_filename3','file3')" <?php if($c_edf_link3==1){echo "checked";}?>>&nbsp;ระบุที่อยู่&nbsp;
					<?php if($c_edf_link3==1){?>
						<input type="text" name="s_edf_filename3" id="s_edf_filename3" class="form-control input_width3"  value="<?= $c_edf_filename3;?>" placeholder="ระบุที่อยู่ไฟล์เอกสาร">
						<input name="file3" type="file" id="file3" style="margin-top:3px;max-width:250px;display:none;" accept="application/pdf">
					<?php} else {?>
						<input type="text" name="s_edf_filename3" id="s_edf_filename3" class="form-control input_width3"  value="<?= $c_edf_filename3;?>" readonly="true" placeholder="ระบุที่อยู่ไฟล์เอกสาร" style="color:#999999;display:none;">
					<?php
						if($c_edf_filename3!=""){ 
							echo "<div id=\"view3\"><a href=\"../files/".$c_edf_filename3."\" target=\"_blank\"><span id=\"dfile3\">".$c_edf_filename3."</span></a>&nbsp;";
							echo "<input name=\"view3\" type=\"button\" onclick=\"javascript:confirmDelete('dfile3','".$c_edf_id3."','".$c_edf_filename3."','1','view3','s_edf_filename3')\" value=\" ลบไฟล์นี้ \" style=\"border-radius:5px;height:22px;color:red;\"></div>";
						}
					?>
						<input name="file3" type="file" id="file3" style="margin-top:3px;max-width:250px;" accept="application/pdf">
					<?php } ?>
				</div>
			  </div>
			  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding:3px;">
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">เอกสาร 4&nbsp;:&nbsp;</div>
				<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
					<input type="checkbox" name="chk_doc4" id="chk_doc4" value="1" onclick="check_doc('chk_doc4','s_edf_filename4','file4')" <?php if($c_edf_link4==1){echo "checked";}?>>&nbsp;ระบุที่อยู่&nbsp;
					<?php if($c_edf_link4==1){?>
						<input type="text" name="s_edf_filename4" id="s_edf_filename4" class="form-control input_width3"  value="<?= $c_edf_filename4;?>" placeholder="ระบุที่อยู่ไฟล์เอกสาร">
						<input name="file4" type="file" id="file4" style="margin-top:3px;max-width:250px;display:none;" accept="application/pdf">
					<?php} else {?>
						<input type="text" name="s_edf_filename4" id="s_edf_filename4" class="form-control input_width3"  value="<?= $c_edf_filename4;?>" readonly="true" placeholder="ระบุที่อยู่ไฟล์เอกสาร" style="color:#999999;display:none;">
					<?php
						if($c_edf_filename4!=""){ 
							echo "<div id=\"view4\"><a href=\"../files/".$c_edf_filename4."\" target=\"_blank\"><span id=\"dfile4\">".$c_edf_filename4."</span></a>&nbsp;";
							echo "<input name=\"view4\" type=\"button\" onclick=\"javascript:confirmDelete('dfile4','".$c_edf_id4."','".$c_edf_filename4."','1','view4','s_edf_filename4')\" value=\" ลบไฟล์นี้ \" style=\"border-radius:5px;height:22px;color:red;\"></div>";
						}
					?>
						<input name="file4" type="file" id="file4" style="margin-top:3px;max-width:250px;" accept="application/pdf">
					<?php } ?>
				</div>
			  </div>
			  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding:3px;">
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">เอกสาร 5&nbsp;:&nbsp;</div>
				<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
					<input type="checkbox" name="chk_doc5" id="chk_doc5" value="1" onclick="check_doc('chk_doc5','s_edf_filename5','file5')" <?php if($c_edf_link5==1){echo "checked";}?>>&nbsp;ระบุที่อยู่&nbsp;
					<?php if($c_edf_link5==1){?>
						<input type="text" name="s_edf_filename5" id="s_edf_filename5" class="form-control input_width3"  value="<?= $c_edf_filename5;?>" placeholder="ระบุที่อยู่ไฟล์เอกสาร">
						<input name="file5" type="file" id="file5" style="margin-top:3px;max-width:250px;display:none;" accept="application/pdf">
					<?php} else {?>
						<input type="text" name="s_edf_filename5" id="s_edf_filename5" class="form-control input_width3"  value="<?= $c_edf_filename5;?>" readonly="true" placeholder="ระบุที่อยู่ไฟล์เอกสาร" style="color:#999999;display:none;">
					<?php
						if($c_edf_filename5!=""){ 
							echo "<div id=\"view5\"><a href=\"../files/".$c_edf_filename5."\" target=\"_blank\"><span id=\"dfile5\">".$c_edf_filename5."</span></a>&nbsp;";
							echo "<input name=\"view5\" type=\"button\" onclick=\"javascript:confirmDelete('dfile5','".$c_edf_id5."','".$c_edf_filename5."','1','view5','s_edf_filename5')\" value=\" ลบไฟล์นี้ \" style=\"border-radius:5px;height:22px;color:red;\"></div>";
						}
					?>
						<input name="file5" type="file" id="file5" style="margin-top:3px;max-width:250px;" accept="application/pdf">
					<?php } ?>
				</div>
			  </div>
			   <?php
			  if(($num_rows_files > 5) && isset($dbquery_pdf)){
					$item = 6;
					While($row_pdf = $dbquery_pdf->fetch_assoc()){
						$c_edf_id = $row_pdf["id"];
						$c_edf_filename = $row_pdf["edf_filename"];
						$c_edf_link = $row_pdf["edf_link"];
						if(($c_edf_filename) != ""){ 
							?>
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding:3px;">
							<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">เอกสาร <?= $item;?>&nbsp;:&nbsp;</div>
							<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
							<?php
							echo "<div id=\"view$item\"><a href=\"../files/".$c_edf_filename."\" target=\"_blank\"><span id=\"dfile$item\">".$c_edf_filename."</span></a>&nbsp;";
							echo "<input name=\"view$item\" type=\"button\" onclick=\"javascript:confirmDelete('dfile$item','".$c_edf_id."','".$c_edf_filename."','1','view$item','s_edf_filename$item')\" value=\" ลบไฟล์นี้ \" style=\"border-radius:5px;height:22px;color:red;\"></div>";
							echo "<input name=\"file$item\" type=\"file\" id=\"file$item\" style=\"margin-top:3px;max-width:250px;\" accept=\"application/pdf\">";
							?>
							</div>
							</div>
							<?php
						}
						$item += 1;
					}
			  } else {
				  $num_rows_files = 5;
			  }
			  ?>
			  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding:3px;">
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">เอกสาร <?= $num_rows_files+1;?>-<?= $_max_file_uploads;?> (เลือกเป็นกลุ่ม)&nbsp;:&nbsp;</div>
				<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
					<input type="file" title="เลือกไฟล์ที่จะอัพโหลด" accept="application/pdf" multiple="1" name="pdf_files[]" size="0" id="pdf_files" onchange="checkmaxpdf()">
				</div>
			  </div>
			  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" style="color:#ff0000;">** เลือกไฟล์ครั้งล่ะรวมกันไม่เกิน <?= $_max_file_uploads;?> ไฟล์ ขนาดของไฟล์รวมกันไม่เกิน <?= $_max_file_size;?> **</div>

			  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding:3px;">&nbsp;</div>

		      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" style="padding:3px;">
					<input type="hidden" name="c_id" value="<?php if(isset($c_id)){ echo $c_id;}else{ echo '';}?>">
   					<input type="hidden" name="chk_edit" value="1"> 
					<input type="submit" name="Submit" value=" บันทึก " class="btn btn-warning" style="width:100px;font-size:18px;">&nbsp;
			  </div>

			  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding:3px;">&nbsp;</div>
			  
		  </form>

		</div>
	</div><!-- /.row -->
</div><!-- /.container -->

<div class="container-fluid">
	<div class="row"><a name="top_page"></a>

		  <hr align="center" width="100%" noshade size="1">
		  <form name="form2" method="post" action="eresearch.php#top_page" onSubmit="return c_check2();" role="form">
		   <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
				  <?php
				  if(isset($_POST["c_code_1"]) ){$c_code_1 = $_POST["c_code_1"];	}else{$c_code_1 = "";}
				  if(trim($c_code_1)!=""){
					  $_SESSION["u_code_1"] = $c_code_1;
				  } else {
					  if(isset($_POST["c_code_1"])){$_SESSION["u_code_1"]="";}
				  }
				  ?>
				  <span class="sbreak1">
				  <select name="f_faculty_id" id="f_faculty_id" style="width:180px;border-radius:5px;height:35px;border:1px solid #ccc;padding:5px; margin-top:3px;">
					<option value="0">ส่วนงาน</option>
					<?php
					$sql_d = "select * from `ers_faculty` where 1 ";
					$dbquery_d = $mysqli->query($sql_d);
					$nRows_d = $dbquery_d->num_rows;
					if($nRows_d>0){
					?>
						<?php while ($row_d = $dbquery_d->fetch_assoc()) { ?>
							<option value="<?php echo $row_d['id']; ?>" <?php if($c_search_faculty==$row_d['id']) echo "selected"?> >&nbsp;&nbsp;- <?php echo $row_d['ef_name']; ?></option>
						<?php } //while
					}?>
				  </select>&nbsp;
				  <select name="f_section_id" id="f_section_id" style="width:150px;border-radius:5px;height:35px;border:1px solid #ccc;padding:5px; margin-top:3px;">
					<option value="0">ภาควิชา/ฝ่าย</option>
					<?php
					$sql_d = "select * from `ers_section` where 1 ";
					$dbquery_d = $mysqli->query($sql_d);
					$nRows_d = $dbquery_d->num_rows;
					if($nRows_d>0){
					?>
						<?php while ($row_d = $dbquery_d->fetch_assoc()) { ?>
							<option value="<?php echo $row_d['id']; ?>" <?php if($c_search_section==$row_d['id']) echo "selected"?> >&nbsp;&nbsp;- <?php echo $row_d['es_name']; ?></option>
						<?php } //while
					}?>
				  </select>&nbsp;
				  <select name="f_ed_year" id="f_ed_year" style="width:110px;border-radius:5px;height:35px;border:1px solid #ccc;padding:5px; margin-top:3px;">
					<option value="0">ปีงบประมาณ</option>
					<?php
					$start_y=(date("Y")-40); 
					$end_y=(date("Y")+2);
					for($yy=$end_y; $yy>=$start_y; $yy--){
						if($c_search_year==$yy) $selected_y="selected='selected'";
						else  $selected_y="";
					?>
						<option value="<?= $yy;?>" <?= $selected_y;?>>
						  <?= $yy+543;?>
						</option>
					<?php } ?>
				  </select>&nbsp;
				  </span>
				  <span class="sbreak2">
				  <input type="text" name="c_code_1" id="c_code_1" class="searchbox-control" maxlength="30"placeholder="ข้อความที่ต้องการค้นหา" value="<?= $_SESSION["u_code_1"];?>">&nbsp;
				  <input type="submit" name="Submit" id="Submit" value="ค้นหา" class="btn btn-success" style="width:70px; margin-top:0;">&nbsp;<input type="button" name="Clear" id="Clear" value="ยกเลิก" class="btn btn-info" style="width:70px;margin-top:0;" onclick="window.location='eresearch.php?iRegister=1';"></span>

		  </div>
		  <!--<input type="hidden" name="c_search_section" id="c_search_section" value="<?php// if($c_search_section){ echo $c_search_section;}else{ echo '';}?>">
		  <input type="hidden" name="c_search_faculty" id="c_search_faculty" value="<?php// if($c_search_faculty){ echo $c_search_faculty;}else{ echo '';}?>">-->
		  </form>
		 
		  <div class="clearfix"></div>
		  <br>

		  <?php
			  if(isset($_GET["sh_order"])){	$sh_order = $_GET["sh_order"];} else {$sh_order=0;}
			  if(!isset($_GET["Page"])){
				if($sh_order==1){$sh_order=0;}else{$sh_order=1;}
			  }
			  if($sh_order==1){ $asort="<span class='glyphicon glyphicon-arrow-up' style='font-size:8px;'></span>"; } else { $asort="<span class='glyphicon glyphicon-arrow-down' style='font-size:8px;'></span>"; }
			  if(isset($_GET["sd"])){$sd = $_GET["sd"];} else { $sd = 0;}
			  $a1sort = $a2sort = $a3sort = $a4sort = $a5sort = $a6sort = $a7sort = "<span class='glyphicon glyphicon-sort' style='font-size:8px;'></span>";
			  switch ($sd) {
					case '1': $a1sort = $asort; break;
					case '2': $a2sort = $asort; break;
					case '3': $a3sort = $asort; break;
					case '4': $a4sort = $asort; break;
					case '5': $a5sort = $asort; break;
					case '6': $a6sort = $asort; break;
					case '7': $a7sort = $asort; break;
				}
			?>
		  
		  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" style="background-color:#<?php echo __EC_BGSHOW__;?>;color:#<?php echo __EC_FONTSHOW__;?>;"><h4>แสดงข้อมูลผลงานวิจัย</h4></div>

		  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="table-responsive">
			<table class="table table-striped">
              <thead>
                <tr style="background-color:#f7f7f7">
				  <th>&nbsp;</th>
                  <th>&nbsp;</th>
				  <th>&nbsp;</th>
                  <th style="vertical-align:middle;text-align:center;"><a href="eresearch.php?sd=1&sh_order=<?= $sh_order;?>#top_page" target="_parent" style="white-space: nowrap;">ID <?= $a1sort;?></a></th>
                  <th style="vertical-align:middle;text-align:left;"><a href="eresearch.php?sd=2&sh_order=<?= $sh_order;?>#top_page" target="_parent" style="white-space: nowrap;">ชื่อผลงานวิจัย <?= $a2sort;?></a></th>
				  <th style="vertical-align:middle;text-align:center;"><a href="eresearch.php?sd=4&sh_order=<?= $sh_order;?>#top_page" target="_parent" style="white-space: nowrap;">ภาควิชา/ฝ่าย <?= $a4sort;?></a></th>
				  <th style="vertical-align:middle;text-align:center;"><a href="eresearch.php?sd=5&sh_order=<?= $sh_order;?>#top_page" target="_parent" style="white-space: nowrap;">ส่วนงาน <?= $a5sort;?></a></th>
				  <th style="vertical-align:middle;text-align:center;"><a href="eresearch.php?sd=6&sh_order=<?= $sh_order;?>#top_page" target="_parent" style="white-space: nowrap;">ปีงบประมาณ <?= $a6sort;?></a></th>
				  <th style="vertical-align:middle;text-align:left;">รายละเอียด</th>
				  <th style="vertical-align:middle;text-align:center;">ไฟล์</th>
				  <th style="vertical-align:middle;text-align:center;">อ่าน</th>
                </tr>
              </thead>
              <tbody>
		  
			<?php 

			$sql = "select * From `ers_document` where 1 ";
			if(isset($_SESSION["u_code_1"]) and !empty($_SESSION["u_code_1"]))
			{
				 $u_code_1 = $_SESSION["u_code_1"];
				 $sql .= "and ((`id` = '$u_code_1') or ((`ed_year`+543) = '$u_code_1') or (`ed_name_th` like '%$u_code_1%') or (`ed_name_en` like '%$u_code_1%') or (`ed_detail` like '%$u_code_1%')) ";
			}
			if($c_search_section>0) 
			{
				 $sql .= "and (`section_id` = '".$c_search_section."') ";
			}
			if($c_search_faculty>0) 
			{
				 $sql .= "and (`faculty_id` = '".$c_search_faculty."') ";
			}
			if(!empty($c_search_year))
			{
				 $sql .= "and (`ed_year` = '".$c_search_year."') ";
			}
			if(isset($_GET["sd"])){$sd = trim($_GET["sd"]);} else {	$sd = 0;}
			switch ($sd) {
				case '1': $sql .= "Order by `id` "; break;
				case '2': $sql .= "Order by `ed_name_th` "; break;
				case '3': $sql .= "Order by `ed_name_en` "; break;
				case '4': $sql .= "Order by `section_id` "; break;
				case '5': $sql .= "Order by `faculty_id` "; break;
				case '6': $sql .= "Order by `ed_year` "; break;
				default : $sql .= "Order by `id` ";
			}
			if($sh_order==1){$sql .= "DESC ";} else {$sql .= "ASC ";}
			$ch = array("2","3","4","5","6");
			if(in_array($sd,$ch)){$sql .= ",`id` Desc ";}
			$res = $mysqli->query($sql);
			$totalRows = $res->num_rows;

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

				while($result = $res->fetch_assoc()){
					$jk++;
					$c_id = $result["id"];
					$c_ed_name_th = $result["ed_name_th"];
					$c_ed_name_th2 = $c_ed_name_th;
					$thlen = mb_strlen($c_ed_name_th,'UTF-8');
					$thlen2 = 70;
					if($thlen > $thlen2) {
						$c_ed_name_th2 = utf8_substr($c_ed_name_th,0,$thlen2)."..";
					}
					$c_ed_name_en = $result["ed_name_en"];
					$c_ed_name_en2 = $c_ed_name_en;
					$enlen = mb_strlen($c_ed_name_en,'UTF-8');
					$enlen2 = 50;
					if($enlen > $enlen2) {
						$c_ed_name_en2 = utf8_substr($c_ed_name_en,0,$enlen2)."..";
					}
					$code_1 = $c_ed_name_th2;
					$c_section_id = $result["section_id"];
					$c_faculty_id = $result["faculty_id"];
					$c_ed_year = $result["ed_year"]+543;
					$c_ed_detail = $result["ed_detail"];
					$c_ed_detail2 = $c_ed_detail;
					$ttlen = mb_strlen($c_ed_detail,'UTF-8');
					$ttlen2 = 60;
					if($ttlen > $ttlen2) {
						$c_ed_detail2 = utf8_substr($c_ed_detail,0,$ttlen2)."..";
					}
					$c_ed_counter = $result["ed_counter"];

					$sql_d = "select * from `ers_section` where `id`='".$c_section_id."' ";
					$dbquery_d = $mysqli->query($sql_d);
					$nRows_d = $dbquery_d->num_rows;
					$c_es_name = "";
					if($nRows_d>0){
						$result_d = $dbquery_d->fetch_assoc();
						$c_es_name = $result_d["es_name"];
						$c_es_name2 = $c_es_name;
						$eslen = mb_strlen($c_es_name,'UTF-8');
						$eslen2 = 25;
						if($eslen > $eslen2) {
							$c_es_name2 = utf8_substr($c_es_name,0,$eslen2)."..";
						}
					}
					$sql_d = "select * from `ers_faculty` where `id`='".$c_faculty_id."' ";
					$dbquery_d = $mysqli->query($sql_d);
					$nRows_d = $dbquery_d->num_rows;
					$c_ef_name = "";
					if($nRows_d>0){
						$result_d = $dbquery_d->fetch_assoc();
						$c_ef_name = $result_d["ef_name"];
						$c_ef_name2 = $c_ef_name;
						$eflen = mb_strlen($c_ef_name,'UTF-8');
						$eflen2 = 25;
						if($eflen > $eflen2) {
							$c_ef_name2 = utf8_substr($c_ef_name,0,$eflen2)."..";
						}
					}

					$c_edf_id1 = 0;$c_edf_filename1 = '';$c_edf_counter1 = 0;$c_edf_link1 = 0;
					$c_edf_id2 = 0;$c_edf_filename2 = '';$c_edf_counter2 = 0;$c_edf_link2 = 0;
					$c_edf_id3 = 0;$c_edf_filename3 = '';$c_edf_counter3 = 0;$c_edf_link3 = 0;
					$c_edf_id4 = 0;$c_edf_filename4 = '';$c_edf_counter4 = 0;$c_edf_link4 = 0;
					$c_edf_id5 = 0;$c_edf_filename5 = '';$c_edf_counter5 = 0;$c_edf_link5 = 0;
					$sql = "select * from `ers_document_files` where (`document_id`='".$c_id."') order by `id`ASC";
					$dbquery = $mysqli->query($sql);
					$num_rows = $dbquery->num_rows;
					if($num_rows>0)
					{
						$item = 1;
						While($row= $dbquery->fetch_assoc()){
							if($item == 1){
								$c_edf_id1 = $row["id"];$c_edf_filename1 = $row["edf_filename"];$c_edf_link1 = $row["edf_link"];
								$c_edf_counter1 = $row["edf_counter_open_member"]  + $row["edf_counter_download_member"];
							}
							if($item == 2){
								$c_edf_id2 = $row["id"];$c_edf_filename2 = $row["edf_filename"];$c_edf_link2 = $row["edf_link"];
								$c_edf_counter2 = $row["edf_counter_open_member"]  + $row["edf_counter_download_member"];
							}
							if($item == 3){
								$c_edf_id3 = $row["id"];$c_edf_filename3 = $row["edf_filename"];$c_edf_link3 = $row["edf_link"];
								$c_edf_counter3 = $row["edf_counter_open_member"]  + $row["edf_counter_download_member"];
							}
							if($item == 4){
								$c_edf_id4 = $row["id"];$c_edf_filename4 = $row["edf_filename"];$c_edf_link4 = $row["edf_link"];
								$c_edf_counter4 = $row["edf_counter_open_member"]  + $row["edf_counter_download_member"];
							}
							if($item == 5){
								$c_edf_id5 = $row["id"];$c_edf_filename5 = $row["edf_filename"];$c_edf_link5 = $row["edf_link"];
								$c_edf_counter5 = $row["edf_counter_open_member"]  + $row["edf_counter_download_member"];
							}
							$item = $item + 1;
						}			
						$dbquery->free();
					}

					$bcolor = "#ffffff";
					if(($jk %2)==0){
						$bcolor = "rgba(236, 240, 241, 0.8)";
					}

				?>

					<tr>
					<td style="text-align:center;width:120px;min-width:120px;">
						<?php
						echo "<a href='javascript:void(0)' onclick='openWindow($c_id)' style='color:#996600;font-size:16px;' title='เพิ่มนักวิจัย'><span class='glyphicon glyphicon-user'></span>&nbsp;<span style='font-size:14px;'>เพิ่มนักวิจัย</span></a>";
						?>
					</td>
					<td style="text-align:center;width:100px;min-width:100px;">
						<?php
						echo "<a href='eresearch.php?c_id=$c_id' style='color:green;font-size:16px;' title='แก้ไขผลงานวิจัย'><span class='glyphicon glyphicon-edit'></span>&nbsp;<span style='font-size:14px;'>แก้ไข</span></a>";
						?>
					</td>
					<td  style="text-align:center;width:100px;min-width:100px;">
						<?php
						echo "<a href='del_data.php?c_id=$c_id&chk_p=7&code_1=$code_1' style='color:red;font-size:16px;' title='ลบผลงานวิจัย'><span class='glyphicon glyphicon-trash'></span>&nbsp;<span style='font-size:14px;'>ลบ</span></a>";
						?>
					</td>
					<td style="text-align:center;padding-left:0;padding-right:0;"><?= $c_id;?>
					</td>
					<td style="text-align:left;padding-left:0;padding-right:0;">
						<?php
						if($thlen > $thlen2) {
							echo "<a href=\"javascript:void(0)\" title='".$c_ed_name_th."' style=\"color:#000;\">".$c_ed_name_th2."</a>";
						} else {
							echo $c_ed_name_th;
						}
						?>
					</td>
					<td style="text-align:center;padding-left:0;padding-right:0;">
						<?php
						if(!empty($c_es_name))
						{
							if($eslen > $eslen2) {
								echo "<a href=\"javascript:void(0)\" title='".$c_es_name."' style=\"color:#000;\">".$c_es_name2."</a>";
							} else {
								echo $c_es_name;
							}
						}
						?>
					</td>
					<td style="text-align:center;padding-left:0;padding-right:0;">
						<?php
						if(!empty($c_ef_name))
						{
							if($eflen > $eflen2) {
								echo "<a href=\"javascript:void(0)\" title='".$c_ef_name."' style=\"color:#000;\">".$c_ef_name2."</a>";
							} else {
								echo $c_ef_name;
							}
						}
						?>
					</td>
					<td style="text-align:center;padding-left:0;padding-right:0;min-width:80px;">
						<?= $c_ed_year;?>
					</td>
					<td style="text-align:left;padding-left:0;padding-right:0;min-width:180px;">
						<?php
						if($ttlen > $ttlen2) {
							echo "<a href=\"javascript:void(0)\" title='".$c_ed_detail."' style=\"color:#000;\">".$c_ed_detail2."</a>";
						} else {
							echo $c_ed_detail2;
						}
						?>
					</td>
					<td style="text-align:left;padding-left:0;padding-right:0;min-width:50px;">
						<?php
							echo "<div style='white-space: nowrap;'>";
							$link_path = "../files/";
							if($c_edf_filename1!=""){ 
								if($c_edf_link1=='1'){
									$link_path = "";
								}
								echo "<span id=\"view1\"><a href=\"".$link_path.$c_edf_filename1."\" target=\"_blank\" title=\"".$c_edf_filename1."\"><span class=\"glyphicon glyphicon-paperclip\"></span></a><a href=\"javascript:void(0)\" onclick=\"return false;\" title='ดาวน์โหลดแล้ว $c_edf_counter1' style='color:#000000;text-decoration: none;'>[$c_edf_counter1]</a></span>&nbsp;&nbsp;";	
							}
							$link_path = "../files/";
							if($c_edf_filename2!=""){ 
								if($c_edf_link2=='1'){
									$link_path = "";
								} 
								echo "<span id=\"view2\"><a href=\"".$link_path.$c_edf_filename2."\" target=\"_blank\" title=\"".$c_edf_filename2."\"><span class=\"glyphicon glyphicon-paperclip\"></span></a><a href=\"javascript:void(0)\" title='ดาวน์โหลดแล้ว $c_edf_counter2' style='color:#000000;text-decoration: none;'>[$c_edf_counter2]</a></span>&nbsp;&nbsp;";
							}
							$link_path = "../files/";
							if($c_edf_filename3!=""){ 
								if($c_edf_link3=='1'){
									$link_path = "";
								}
								echo "<span id=\"view3\"><a href=\"".$link_path.$c_edf_filename3."\" target=\"_blank\" title=\"".$c_edf_filename3."\"><span class=\"glyphicon glyphicon-paperclip\"></span></a><a href=\"javascript:void(0)\" title='ดาวน์โหลดแล้ว $c_edf_counter3' style='color:#000000;text-decoration: none;'>[$c_edf_counter3]</a></span>&nbsp;&nbsp;";
							}
							$link_path = "../files/";
							if($c_edf_filename4!=""){ 
								if($c_edf_link4=='1'){
									$link_path = "";
								}
								echo "<span id=\"view4\"><a href=\"".$link_path.$c_edf_filename4."\" target=\"_blank\" title=\"".$c_edf_filename4."\"><span class=\"glyphicon glyphicon-paperclip\"></span></a><a href=\"javascript:void(0)\" title='ดาวน์โหลดแล้ว $c_edf_counter4' style='color:#000000;text-decoration: none;'>[$c_edf_counter4]</a></span>&nbsp;&nbsp;";
							}
							$link_path = "../files/";
							if($c_edf_filename5!=""){ 
								if($c_edf_link5=='1'){
									$link_path = "";
								}
								echo "<span id=\"view5\"><a href=\"".$link_path.$c_edf_filename5."\" target=\"_blank\" title=\"".$c_edf_filename5."\"><span class=\"glyphicon glyphicon-paperclip\"></span></a><a href=\"javascript:void(0)\" title='ดาวน์โหลดแล้ว $c_edf_counter5' style='color:#000000;text-decoration: none;'>[$c_edf_counter5]</a></span>";
							}
							echo "</div>";
						?>
					</td>
					<td style="text-align:center;padding-left:0;padding-right:0;min-width:30px;">
						<?php echo number_format($c_ed_counter)."\n";?>
					</td>
					</tr>

				<?php
				}//while
				$res->free();
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
					$pages->url_next = $_SERVER["PHP_SELF"]."?&f_section_id=$c_search_section&f_faculty_id=$c_search_faculty&f_ed_year=$c_search_year&sd=$sd&sh_order=$sh_order&Page=";
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
function openWindow(idp) {
	var url='eresearcher.php?doc_id='+idp;
	/*var w = 900;
	var h = 800;
	var left = Number((screen.width/2)-(w/2));
	var tops = Number((screen.height/2)-(h/2));*/
	var sw=document.body.clientWidth,sh=screen.height,sw2=0,sw3=0,sh2=0;
	sw = (sw * 80) / 100;
	sh = (sh * 80) / 100;
	if(screen.height < 769){
		sh2 = 680;
		if(document.body.clientWidth < 767){sh2 = 400;}
		if(document.body.clientWidth < 991){sh2 = 500;}
	} else {
		sh2 = 830;
		if(document.body.clientWidth < 1300){sh2 = 700;}
	}
	if(document.body.clientWidth > 1367){
		sw2 = 1300;
	} else {
		sw2 = sw;
	}
	sw3 = sw2 + 10;

	var myleft=(document.body.clientWidth)?(document.body.clientWidth-sw3)/2:100;   
	var mytop=(screen.height)?(screen.height-sh2)/2:100; 
	var sct = $(window).scrollTop(); 
	var left = (myleft) + "px";
	var tops = (sct + 25) + "px";
	var win=window.open(url,'staff','toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,,width='+sw2+', height='+sh+', top='+tops+', left='+left);
	win.focus();
}
function checkmaxpdf(){
		var selectfile = document.getElementById('pdf_files');
		if(selectfile.files.length > <?= $_max_file_uploads_pdf;?>){
			alert ("กรุณาเลือกไฟล์ pdf ไม่เกิน <?= $_max_file_uploads_pdf;?> ไฟล์ ค่ะ");
			selectfile.value="";
			return false;
		}
	}
function FocusOnInput() {
	  var element = document.getElementById('s_ed_name_th');
	  element.focus();
	  setTimeout(function () { element.focus(); }, 1);
}
FocusOnInput();
</script>