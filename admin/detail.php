<? 
session_start();
Header("Content-Type: text/html; charset=UTF-8");
require_once "../include/config.php";
if (!defined('_web_path')) {
	exit();
}

if ( !isset($_SESSION["admin"]) || !isset($_SESSION["userlevel"]) || ($_SESSION["admin"]=="") )
{
	session_unset();session_destroy();
	echo "<br><br><center><a href='login.php'><span style='font-size:16px;font-weight:bold;color:#ff0000;'>Session Expired กรุณาเข้าระบบใหม่เพื่อใช้งาน</span></a></center>";
	//echo "<script>alert('Session Expired กรุณาเข้าระบบใหม่เพื่อใช้งาน');parent.location='login.php';</script>";
	?>
	<script>
	alert("Session Expired กรุณาเข้าระบบใหม่");
	//window.onunload = refreshParent;
    //function refreshParent() {
    //    window.opener.location.reload();
    //}
	//window.close();
	parent.location.href = "login.php";
	</script>
	<?
	exit();
}

if(isset($_GET["m"])){
	$c_document_id = $_GET["m"];
} else {
	die();exit();
}
require_once "../include/convars.php";
require_once("../include/config_db.php");

$sql = "select * from `ers_document` where (`id`='".$c_document_id."') ";
$dbquery = $mysqli->query($sql) or die("Can't send query!");
$tRows = $dbquery->num_rows;
if($tRows > 0){
	$row = $dbquery->fetch_assoc();
	$c_ed_name_th = $row["ed_name_th"];
	$c_ed_name_en = $row["ed_name_en"];
	$c_section_id = $row["section_id"];
	$c_faculty_id = $row["faculty_id"];
	$c_ed_detail = $row["ed_detail"];
	if(!empty($row["ed_year"])){
		$c_ed_year = $row["ed_year"]+543;
	}
	$c_research_type_id = $row["research_type_id"];
	$c_ed_capital = $row["ed_capital"];
	$dbquery->free();
	$sql_d = "select * from `ers_research_type` where `id`='".$c_research_type_id."' ";
	$dbquery_d = $mysqli->query($sql_d);
	$nRows_d = $dbquery_d->num_rows;
	$c_et_name = "";
	if($nRows_d>0){
		$result_d = $dbquery_d->fetch_assoc();
		$c_et_name = $result_d["et_name"];
	}
	$sql_d = "select * from `ers_section` where `id`='".$c_section_id."' ";
	$dbquery_d = $mysqli->query($sql_d);
	$nRows_d = $dbquery_d->num_rows;
	$c_es_name = "";
	if($nRows_d>0){
		$result_d = $dbquery_d->fetch_assoc();
		$c_es_name = $result_d["es_name"];
	}
	$sql_d = "select * from `ers_faculty` where `id`='".$c_faculty_id."' ";
	$dbquery_d = $mysqli->query($sql_d);
	$nRows_d = $dbquery_d->num_rows;
	$c_ef_name = "";
	if($nRows_d>0){
		$result_d = $dbquery_d->fetch_assoc();
		$c_ef_name = $result_d["ef_name"];
	}
} else {include("../include/close_db.php"); echo "<div style='text-align:center;padding-top:50px;color:#ff0000;font-size:16px;'><strong>...ไม่พบข้อมูล...</strong></div>"; die();}
//$mysqli->query("update `ers_document` set `ed_counter`=`ed_counter`+1 where `id` ='".$c_document_id."'");

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
     <title>ระบบคลังข้อมูลงานวิจัย <?if(defined('__EC_NAME__')){echo __EC_NAME__;}?></title>
	<link href="../images/<?if(defined('__EC_FAVICON__')){echo __EC_FAVICON_ICO__;}?>" rel="icon" type="image/ico">
	<link href="../images/<?if(defined('__EC_FAVICON__')){echo __EC_FAVICON__;}?>" rel="icon" type="image/png" sizes="32x32">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css?v=<?php echo filemtime('../bootstrap/css/bootstrap.min.css');?>">
    <script src="../js/jquery.min.js"></script>
    <script src="../bootstrap/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="./style_admin.css?v=<?php echo filemtime('./style_admin.css');?>">
	<link href="https://fonts.googleapis.com/css?family=Prompt" rel="stylesheet">
	<style>
	body{
		font-size:16px;
	}
	@media (max-width: 767px) {
		body{
			font-size:13px;
		}
	}
	.col-lg-12 ,.col-lg-9 ,.col-lg-3 { margin:0;padding:0; }
	.col-md-12 ,.col-md-9 ,.col-md-3 { margin:0;padding:0; }
	.col-sm-12 ,.col-sm-9 ,.col-sm-3 { margin:0;padding:0; }
	</style>
	<script>
	/*
	$(window).resize(function() { 
		var windowWidth = $(window).width();
		if(windowWidth < 768){
			document.body.style = 'font-size:13px';
		} else {
			document.body.style = 'font-size:16px';
		}
	}); */
	</script>
</head>

<body role="document" style="background: url('../images/<?if(defined('__EC_PICHOME__')){echo __EC_PICHOME__;}?>') no-repeat; background-attachment:fixed; background-size:contain;height:100%;width:100%;background-position: left center;">

<div class="container bgw1">
	<div class="row bgw2">

		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" style="margin-bottom:30px;background-color:#<?echo __EC_BGSHOW__;?>;color:#<?echo __EC_FONTSHOW__;?>;font-weight: bold;"><h4><?if(defined('__EC_NAME__')){echo "ผลงานวิจัย";} else echo "ระบบคลังข้อมูลงานวิจัย";?></h4></div>

		<!--<div class="col-lg-10 col-md-10 col-sm-10 col-xs-12 col-lg-offset-1 col-md-offset-1 col-sm-offset-1" style="padding-top:20px;padding-bottom:20px;">-->

			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-4 text-right" style="font-weight: bold;">ชื่อผลงาน(ท.)&nbsp;:&nbsp;</div>
				<div class="col-lg-9 col-md-9 col-sm-9 col-xs-8"><?=$c_ed_name_th;?></div>
			</div>
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-4 text-right" style="font-weight: bold;">ชื่อผลงาน(อ.)&nbsp;:&nbsp;</div>
				<div class="col-lg-9 col-md-9 col-sm-9 col-xs-8"><?=$c_ed_name_en;?></div>
			</div>
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-4 text-right" style="font-weight: bold;">ส่วนงาน&nbsp;:&nbsp;</div>
				<div class="col-lg-9 col-md-9 col-sm-9 col-xs-8"><?=$c_es_name." ".$c_ef_name;?></div>
			</div>
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-4 text-right" style="font-weight: bold;">ปีงบประมาณ&nbsp;:&nbsp;</div>
				<div class="col-lg-9 col-md-9 col-sm-9 col-xs-8"><?=$c_ed_year;?></div>
			</div>
			<?
			if($c_ed_detail!=""){ 
			?>
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-4 text-right" style="font-weight: bold;">รายละเอียด&nbsp;:&nbsp;</div>
				<div class="col-lg-9 col-md-9 col-sm-9 col-xs-8"><div style="max-width: 95%"><?=$c_ed_detail;?></div></div>
			</div>
			<?
			}
			if($c_et_name!=""){ 
			?>
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-4 text-right" style="font-weight: bold;">ประเภทของเงินอุดหนุนงานวิจัย&nbsp;:&nbsp;</div>
				<div class="col-lg-9 col-md-9 col-sm-9 col-xs-8"><?=$c_et_name;?></div>
			</div>
			<?
			}
			if($c_ed_capital!=""){ 
			?>
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-4 text-right" style="font-weight: bold;">แหล่งทุน&nbsp;:&nbsp;</div>
				<div class="col-lg-9 col-md-9 col-sm-9 col-xs-8"><?=$c_ed_capital;?></div>
			</div>
			<?
			}
			$sql = "select * from `ers_document_files` where (`document_id`='".$c_document_id."') order by `id`ASC";
			$dbquery = $mysqli->query($sql);
			$num_rows = $dbquery->num_rows;
			if($num_rows>0)
			{
				$item = 0;
				While($row_d= $dbquery->fetch_assoc()){
					$item += 1;
					$c_edf_id = $row_d["id"];
					$c_edf_filename = $row_d["edf_filename"];
					$c_edf_link = $row_d["edf_link"];
					$c_edf_counter_read = $row_d["edf_counter_open"];
					$c_edf_counter_download =$row_d["edf_counter_download"];
					$c_edf_counter_download_member =$row_d["edf_counter_download_member"];
					if($c_edf_filename!=""){ 
						if($item==1){
						?>
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
							<hr align="center" width="95%" noshade size="1" color="#cccccc">
						</div>
						<?}?>
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-4 text-right" style="font-weight: bold;white-space: nowrap;">เอกสาร <?=$item;?>&nbsp;:&nbsp;</div>
							<div class="col-lg-9 col-md-9 col-sm-9 col-xs-8">
							<?
							echo "<div><span class='glyphicon glyphicon-file' style='color:#000000'></span>&nbsp;".$c_edf_filename."<br><a href='../include/filedownload.php?m=".$c_edf_id."&t=1' target='_blank' style='white-space: nowrap;'><span class='glyphicon glyphicon-folder-open'></span>&nbsp;&nbsp;เปิดอ่าน</a>&nbsp;&nbsp;<a href='../include/filedownload.php?m=".$c_edf_id."' target='_blank' style='white-space: nowrap;'><span class='glyphicon glyphicon-download-alt'></span>&nbsp;ดาวน์โหลด</a>&nbsp;[".number_format($c_edf_counter_download_member)."]</div>";
							?>
							</div>
						</div>
					<?
					}
				}
			}
			?>

			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">&nbsp;</div>


	</div>

	<div class="row bgw2">

			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="text-center hdetail"><h4>นักวิจัย</h4></div></div>

			<div class="table-responsive hdetailtb">
			<table class="table table-striped table-bordered sticky-header" style="margin:0;padding:0;">
				<thead>
				<tr style="background-color:#f7f7f7">
				  <th width="60%">ชื่อ-นามสกุล</a></th>
				  <th width="30%">ตำแหน่งในโครงการ</th>
				  <th width="10%" style="text-align:center;">สัดส่วน</a></th>
				</tr>
				</thead>
				<tbody>
					<?
					$sql = "SELECT m . * , d . * FROM ers_document_researcher m JOIN ers_researcher d ON m.researcher_id = d.id WHERE (m.document_id = $c_document_id) AND (d.researcher_position_status =1) ";
					$sql .= "Order by d.id ASC ";

					$res = $mysqli->query($sql);
					$totalRows = $res->num_rows;

					if($totalRows!="0")
					{
						$jk=0;
						while($result = $res->fetch_assoc()){
							$jk++;
							$c_id = $result["id"];
							$c_researcher_id = $result["researcher_id"];
							$c_project_position_id = $result["project_position_id"];
							$c_ratio = $result["ratio"];

							$sql_d = "select * from `ers_researcher` where `id`='".$c_researcher_id."' ";
							$dbquery_d = $mysqli->query($sql_d);
							$nRows_d = $dbquery_d->num_rows;
							$c_name = "";
							if($nRows_d>0){
								$result_d = $dbquery_d->fetch_assoc();
								if(!empty($result_d['ec_title_th'])){
									$c_name = $result_d['ec_title_th'];
								}
								$c_name .= $result_d['ec_firstname_th'];
								if(!empty($result_d['ec_lastname_th'])){
									$c_name .= " ".$result_d['ec_lastname_th'];
								}
							}
							$sql_d = "select * from `ers_project_position` where `id`='".$c_project_position_id."' ";
							$dbquery_d = $mysqli->query($sql_d);
							$nRows_d = $dbquery_d->num_rows;
							$c_position_name = "";
							if($nRows_d>0){
								$result_d = $dbquery_d->fetch_assoc();
								$c_position_name =  $result_d['ep_name'];
							}
							$bcolor = "rgba(252, 245, 220, 0.8)";
							if(($jk %2)==0){
								$bcolor = "rgba(236, 240, 241, 0.8)";
							}
				
							echo "<tr style='-moz-opacity: 0.99;-khtml-opacity: 0.99;opacity: 0.99;background-color: $bcolor'>\r\n";
							echo "	<td>$c_name</td>\r\n";
							echo "	<td>$c_position_name</td>\r\n";
							echo "	<td style='text-align:center;'>$c_ratio</td>\r\n";
							echo "</tr>\r\n";

						}//whiel
					} else {
						echo "<tr><td colspan=\"3\" style=\"text-align:center;color:#ff0000;\"><h5>...ไม่พบข้อมูล...</h5></td></tr>";
					}
					?>
				</tbody>
			</table>
			</div><!-- /.table-responsive -->

			<div>&nbsp;</div>

	</div>

	<div class="row bgw2">

			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="text-center hdetail"><h4>นักวิจัยภายนอก</h4></div></div>	

			<div class="table-responsive hdetailtb">
			<table class="table table-striped table-bordered sticky-header" style="margin:0;padding:0;">
				<thead>
				<tr style="background-color:#f7f7f7">
				  <th width="60%">ชื่อ-นามสกุล</a></th>
				  <th width="30%">ตำแหน่งในโครงการ</th>
				  <th width="10%" style="text-align:center;">สัดส่วน</a></th>
				</tr>
				</thead>
				<tbody>
					<?
					$sql = "SELECT m . * , d . * FROM ers_document_researcher m JOIN ers_researcher d ON m.researcher_id = d.id WHERE (m.document_id = $c_document_id) AND (d.researcher_position_status =2) ";
					$sql .= "Order by d.id DESC ";

					$res = $mysqli->query($sql);
					$totalRows = $res->num_rows;

					if($totalRows!="0")
					{
						$jk=0;
						while($result = $res->fetch_assoc()){
							$jk++;
							$c_id = $result["id"];
							$c_researcher_id = $result["researcher_id"];
							$c_project_position_id = $result["project_position_id"];
							$c_ratio = $result["ratio"];

							$sql_d = "select * from `ers_researcher` where `id`='".$c_researcher_id."' ";
							$dbquery_d = $mysqli->query($sql_d);
							$nRows_d = $dbquery_d->num_rows;
							$c_name = "";
							if($nRows_d>0){
								$result_d = $dbquery_d->fetch_assoc();
								if(!empty($result_d['ec_title_th'])){
									$c_name = $result_d['ec_title_th'];
								}
								$c_name .= $result_d['ec_firstname_th'];
								if(!empty($result_d['ec_lastname_th'])){
									$c_name .= " ".$result_d['ec_lastname_th'];
								}
							}
							$sql_d = "select * from `ers_project_position` where `id`='".$c_project_position_id."' ";
							$dbquery_d = $mysqli->query($sql_d);
							$nRows_d = $dbquery_d->num_rows;
							$c_position_name = "";
							if($nRows_d>0){
								$result_d = $dbquery_d->fetch_assoc();
								$c_position_name =  $result_d['ep_name'];
							}
							$bcolor = "rgba(252, 245, 220, 0.8)";
							if(($jk %2)==0){
								$bcolor = "rgba(236, 240, 241, 0.8)";
							}

							echo "<tr style='background-color: $bcolor'>\r\n";
							echo "	<td>$c_name</td>\r\n";
							echo "	<td>$c_position_name</td>\r\n";
							echo "	<td style='text-align:center;'>$c_ratio</td>\r\n";
							echo "</tr>\r\n";

						}//while
					} else {
						echo "<tr><td colspan=\"3\" style=\"text-align:center;color:#ff0000;\"><h5>...ไม่พบข้อมูล...</h5></td></tr>";
					}
					?>
				</tbody>
			</table>
			</div><!-- /.table-responsive -->

			<div>&nbsp;</div>

	</div>
</div>
</body>
</html>
<?include("../include/close_db.php");?>
<script>
function openfiles(url){
	window.location=url;
}
</script>