<?
session_start();
Header("Content-Type: text/html; charset=UTF-8");
require_once "../include/config.php";
if (!defined('_web_path')) {
	exit;
}
if ( !isset($_SESSION["admin"]) || !isset($_SESSION["userlevel"]) )
{
  echo "<Script language=\"javascript\">window.location=\"login.php\"</script>";
  exit;
}

if($_GET['chk_p']=="1"){
	 if($_GET["c_id"] == "1"){
		?>
		<Script language="javascript">
			alert('ไม่สามารถลบ "admin" ได้ กรุณาติดต่อผู้ดูแลระบบ');
			window.location="index.php";
		</script>
		<?
		echo "<Script language=\"javascript\">window.location=\"index.php\"</script>";
		exit;
	}
	if($_SESSION["userlevel"] != "1"){
		?>
		<Script language="javascript">
			alert('ไม่สามารถลบข้อมูลได้ กรุณาติดต่อผู้ดูแลระบบ');
			window.location="index.php";
		</script>
		<?
		echo "<Script language=\"javascript\">window.location=\"index.php\"</script>";
		exit;
	}
}
$_menu_id = 0;

if(isset($_GET['chk_p'])){
	$chk_p = $_GET['chk_p'];
} else { 
	if(isset($_POST['chk_p'])){
		$chk_p = $_POST['chk_p'];
	}
}

if(!isset($chk_p) || ($chk_p==0)){
	echo "..Error..";die();
}
if(isset($_POST['chk_del'])){$chk_del = $_POST['chk_del'];}else{$chk_del = "";}
if(isset($_GET['code_1'])){$code_1 = $_GET['code_1'];}else{$code_1 = $_POST['code_1'];}
$code_1 = htmlspecialchars($code_1);
if(isset($_GET['c_id'])){$c_id = $_GET['c_id'];}else{$c_id = $_POST['c_id'];}

include("../include/config_db.php");

if($chk_del==1){
	
	$f_name = "";
	switch ($chk_p) {
		case 1: $code_1 = trim($code_1);				
					$sql = "delete from `ers_admin` where (`id`='$c_id') and (`user_name`='$code_1')";
					$f_name = "ers_admin";
					//include("../include/close_db.php");
					//echo $sql; die();
					break;
		case 2:  $sql = "delete from `ers_section` where (`id`='$c_id') LIMIT 1";
					$f_name = "ers_section";
					break;
		case 3: $sql = "delete from `ers_faculty` where (`id`='$c_id') LIMIT 1";
				    $f_name = "ers_faculty";
					break;
		case 4: $sql = "delete from `ers_researcher_position` where (`id`='$c_id') LIMIT 1";
					$f_name = "ers_researcher_position";
					break;
		case 5: $sql = "delete from `ers_project_position` where (`id`='$c_id') LIMIT 1";
					$f_name = "ers_project_position";
					break;
		case 6: $sql = "delete from `ers_researcher` where (`id`='$c_id') LIMIT 1";
					$f_name = "ers_researcher";
					break;
		case 7: $sql = "delete from `ers_document` where (`id`='$c_id') LIMIT 1";
					$f_name = "ers_document";
					break;
		case 8: $sql = "delete from `ers_academic_position` where (`id`='$c_id') LIMIT 1";
					$f_name = "ers_academic_position";
					break;
		case 9: $sql = "delete from `ers_research_type` where (`id`='$c_id') LIMIT 1";
					$f_name = "ers_research_type";
					break;
	}

	$dbquery = $mysqli->query($sql) or die("Can't send query !");

	switch ($chk_p) {
		case 7: $sql = "select * from `ers_document_files` where (`document_id`='".$c_id."') LIMIT 1";
					$dbquery = $mysqli->query($sql);
					$num_rows = $dbquery->num_rows;
					if($num_rows>0)
					{
						while($result = $dbquery->fetch_assoc()){
							$c_filename = $result["edf_filename"];
							//$c_filename_iconv = iconv("tis-620","utf-8",$c_filename);
							$file_del = "../files/".$c_filename;
							if(@file_exists($file_del))
							{
								@chmod($file_del,0777);
								unlink($file_del);
							}
						}//while
					}
					$sql = "delete from `ers_document_files` where (`document_id`='".$c_id."')";
					$dbquery = $mysqli->query($sql);
					$sql = "delete from `ers_document_researcher` where (`document_id`='".$c_id."')";
					$dbquery = $mysqli->query($sql);
					$sql = "delete from `ers_member_request` where (`document_id`='".$c_id."')";
					$dbquery = $mysqli->query($sql);
					break;
	}

	$admin = $_SESSION["admin"];
	$s_caption_1 = $_POST['s_caption_1'];
	$s_description = $_POST['s_description'];
	$s_description = "Table : ".$f_name.",".$s_description;
	$u_ip = $_SERVER["REMOTE_ADDR"];
	$query_m = "insert into `ers_delete` (`user_name`,`ip_address`,`del_time`,`description`) values ('$admin','$u_ip',now(),'$s_description') ";
	$result_d = $mysqli->query($query_m);

	include("../include/close_db.php");

	echo "<br>";
	echo "<h3><p align=\"center\"><font color='#0000BB'>ลบข้อมูลเรียบร้อยแล้ว</font></p></h3><br>";

	switch ($chk_p) {
		case 1: echo "<p align=\"center\"><a href=\"users.php?iRegister=1\">กลับไปหน้า $s_caption_1</a></p><br>";echo "<meta http-equiv='refresh' content='1;URL=users.php?iRegister=1'>";
					   break;
		case 2: echo "<p align=\"center\"><a href=\"section.php?iRegister=1\">กลับไปหน้า $s_caption_1</a></p><br>";echo "<meta http-equiv='refresh' content='1;URL=section.php?iRegister=1'>";
					   break;
		case 3: echo "<p align=\"center\"><a href=\"faculty.php?iRegister=1\">กลับไปหน้า $s_caption_1</a></p><br>";echo "<meta http-equiv='refresh' content='1;URL=faculty.php?iRegister=1'>";
					   break;
		case 4: echo "<p align=\"center\"><a href=\"resposition.php?iRegister=1\">กลับไปหน้า $s_caption_1</a></p><br>";echo "<meta http-equiv='refresh' content='1;URL=resposition.php?iRegister=1'>";
					   break;
		case 5: echo "<p align=\"center\"><a href=\"proposition.php?iRegister=1\">กลับไปหน้า $s_caption_1</a></p><br>";echo "<meta http-equiv='refresh' content='1;URL=proposition.php?iRegister=1'>";
					   break;
		case 6: echo "<p align=\"center\"><a href=\"researcher.php?iRegister=1\">กลับไปหน้า $s_caption_1</a></p><br>";echo "<meta http-equiv='refresh' content='1;URL=researcher.php?iRegister=1'>";
					   break;
		case 7: echo "<p align=\"center\"><a href=\"eresearch.php?iRegister=1\">กลับไปหน้า $s_caption_1</a></p><br>";echo "<meta http-equiv='refresh' content='1;URL=eresearch.php?iRegister=1'>";
					   break;
		case 8: echo "<p align=\"center\"><a href=\"acaposition.php?iRegister=1\">กลับไปหน้า $s_caption_1</a></p><br>";echo "<meta http-equiv='refresh' content='1;URL=acaposition.php?iRegister=1'>";
					   break;
		case 9: echo "<p align=\"center\"><a href=\"research_type.php?iRegister=1\">กลับไปหน้า $s_caption_1</a></p><br>";echo "<meta http-equiv='refresh' content='1;URL=research_type.php?iRegister=1'>";
					   break;
	}
	
}else{
	if($_SESSION["userlevel"] != 1){
		if($chk_p == 1){
			include("../include/close_db.php");
			echo "<Script language=\"javascript\">window.location=\"index.php\"</script>";
		}
	}
	$s_name_1 = '';
	$s_caption_1 = '';
	switch ($chk_p) {
		case 1:	$s_caption_1 = 'ผู้ดูแลระบบ';
					$s_name_1 = 'ลบข้อมูล';
					break;
		case 2:	$s_caption_1 = 'ภาควิชา/ฝ่าย';
					$s_name_1 = 'ลบข้อมูล';
					break;
		case 3:	$s_caption_1 = 'ส่วนงาน/คณะ';
					$s_name_1 = 'ลบข้อมูล';
					break;
		case 4:	$s_caption_1 = 'ตำแหน่งของนักวิจัย';
					$s_name_1 = 'ลบข้อมูล';
					break;
		case 5:	$s_caption_1 = 'ตำแหน่งในโครงการ';
					$s_name_1 = 'ลบข้อมูล';
					break;
		case 6:	$s_caption_1 = 'ข้อมูลนักวิจัย';
					$s_name_1 = 'ลบข้อมูล';
					break;
		case 7:	$s_caption_1 = 'ข้อมูลผลงานวิจัย';
					$s_name_1 = 'ลบข้อมูล';
					break;
		case 8:	$s_caption_1 = 'ตำแหน่งทางวิชาการ';
					$s_name_1 = 'ลบข้อมูล';
					break;
		case 9:	$s_caption_1 = 'ประเภทของเงินอุดหนุนงานวิจัย';
					$s_name_1 = 'ลบข้อมูล';
					break;
	}
	$s_description = $s_caption_1." ," .$s_name_1." : ".$code_1." ,code id : ".$c_id." ,ID : ".$c_id." ,photo : ".$c_photo_path." ,chk code : ".$chk_p;
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

</head>
<body role="document">

<a href="#0" class="cd-top">Top</a>

<!-- cd-top JS -->
<script src="../js/main.js"></script>

<div class="container-fluid" style="margin:0;padding:0;">
	<? require_once "./header.php"; ?>
</div>
<div class="container">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 clearmp">

<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
<tr> 
	<td>
	<?
	switch ($chk_p) {
		case 1: echo "&nbsp;&nbsp;<a href=\"users.php?iRegister=1\"><span style='font-size:16px;'><< กลับไปหน้า $s_caption_1</span></a>";
					   break;
		case 2: echo "&nbsp;&nbsp;<a href=\"section.php?iRegister=1\"><span style='font-size:16px;'><< กลับไปหน้า $s_caption_1</span></a>";
					   break;
		case 3: echo "&nbsp;&nbsp;<a href=\"faculty.php?iRegister=1\"><span style='font-size:16px;'><< กลับไปหน้า $s_caption_1</span></a>";
					   break;
		case 4: echo "&nbsp;&nbsp;<a href=\"resposition.php?iRegister=1\"><span style='font-size:16px;'><< กลับไปหน้า $s_caption_1</span></a>";
					   break;
		case 5: echo "&nbsp;&nbsp;<a href=\"proposition.php?iRegister=1\"><span style='font-size:16px;'><< กลับไปหน้า $s_caption_1</span></a>";
					   break;
		case 6: echo "&nbsp;&nbsp;<a href=\"researcher.php?iRegister=1\"><span style='font-size:16px;'><< กลับไปหน้า $s_caption_1</span></a>";
					   break;
		case 7: echo "&nbsp;&nbsp;<a href=\"eresearch.php?iRegister=1\"><span style='font-size:16px;'><< กลับไปหน้า $s_caption_1</span></a>";
					   break;
		case 8: echo "&nbsp;&nbsp;<a href=\"acaposition.php?iRegister=1\"><span style='font-size:16px;'><< กลับไปหน้า $s_caption_1</span></a>";
					   break;
		case 9: echo "&nbsp;&nbsp;<a href=\"research_type.php?iRegister=1\"><span style='font-size:16px;'><< กลับไปหน้า $s_caption_1</span></a>";
					   break;
	}
	?>
	</td>
</tr>
</table>
<form name="form1" enctype="multipart/form-data" method="post" action="del_data.php">
     <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
          <tr> 
            <td align="center" valign="middle" width="100%"><div style="background-color:#ff9900;color:#fff;width:100%;padding:3px;"><h4>ลบข้อมูล&nbsp;<?=$s_caption_1; ?></h4></div></td>
          </tr>
		  <tr>
			<td>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td>&nbsp;</td>
				</tr>
				<tr style="color:#ff0000">
					<td width="100%" align="center"><h4><?=$s_name_1;?> :&nbsp;
					<?
					if(!empty($c_id) and ($c_id>0))
					{
						echo $code_1." ID : ".$c_id ; 
					}
					 ?></h4>
					 </td>
				</tr>
				<tr>
					<td>&nbsp;</td>
				</tr>
				<tr> 
					<td align="center">
						<input type="hidden" name="code_1" value="<?echo $code_1;?>"> 
						<input type="hidden" name="c_id" value="<?echo $c_id;?>"> 
						<input type="hidden" name="s_caption_1" value="<?echo $s_caption_1;?>"> 
   						<input type="hidden" name="chk_del" value="1">
						<input type="hidden" name="chk_p" value="<?echo $chk_p;?>">
						<input type="hidden" name="s_description" value="<?echo $s_description;?>"> 
						<input type="submit" name="Submit" value=" ลบ " class="btn btn-danger" style="width:100px;font-size:18px;"> 
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
				</tr>
			</table>
			</td>
		</tr>
	</table>
</form>

			</div><!-- /.col-sm-12 -->
	</div><!-- /.row -->

</div>
</body>
</html>
<?
} 
include("../include/close_db.php");
?>