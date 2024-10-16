<?php 
session_start();
Header("Content-Type: text/html; charset=UTF-8");
require_once "../include/config.php";
if (!defined('_web_path')) {
	exit();
}
require_once "../include/convars.php";
if ( !isset($_SESSION["admin"]) || !isset($_SESSION["userlevel"]) || ($_SESSION["admin"]=="") )
{
	session_unset();session_destroy();
	echo "<br><br><center><a href='login.php'><span style='font-size:16px;font-weight:bold;color:#ff0000;'>Session Expired กรุณาเข้าระบบใหม่เพื่อใช้งาน</span></a></center>";
	//echo "<script>alert('Session Expired กรุณาเข้าระบบใหม่เพื่อใช้งาน');parent.location='login.php';</script>";
	?>
	<script>
	alert("Session Expired กรุณาเข้าระบบใหม่");
	//window.onunload = refreshParent;
   // function refreshParent() {
   //     window.opener.location.reload();
   // }
	//window.close();
	parent.location.href = "login.php";
	</script>
	<?php
	exit();
}

if(isset($_GET["m"])){
	$c_researcher_id = $_GET["m"];
} else {
	die();exit();
}
require_once "../include/convars.php";
require_once("../include/config_db.php");

$sql = "select * from `ers_researcher` where (`id`='".$c_researcher_id."') ";
$dbquery = $mysqli->query($sql) or die("Can't send query!");
$tRows = $dbquery->num_rows;
if($tRows > 0){
	$row = $dbquery->fetch_assoc();
	$c_ec_name_th = $row["ec_firstname_th"];
	$c_ec_name_en = $row["ec_firstname_en"];
	if(!empty($row['ec_title_th'])){
		$c_ec_name_th = $row['ec_title_th'].$c_ec_name_th;
	}
	if(!empty($row['ec_lastname_th'])){
		$c_ec_name_th .= " ".$row['ec_lastname_th'];
	}
	if(!empty($row['ec_title_en'])){
		$c_ec_name_en = $row['ec_title_en'].$c_ec_name_en;
	}
	if(!empty($row['ec_lastname_en'])){
		$c_ec_name_en .= " ".$row['ec_lastname_en'];
	}
	$c_ec_idcard = $row['ec_idcard'];
	$c_section_id = $row["section_id"];
	$c_faculty_id = $row["faculty_id"];
	$c_academic_position_id = $row["academic_position_id"];
	$c_ec_phone = $row["ec_phone"];
	$c_ec_mobile = $row["ec_mobile"];
	$c_ec_fax = $row["ec_fax"];
	$c_ec_email = $row["ec_email"];
	$c_ec_highest = $row["ec_highest"];
	$c_ec_educationrecord = $row["ec_educationrecord"];
	$c_ec_workhistory = $row["ec_workhistory"];
	$c_ec_specialization = $row["ec_specialization"];
	$c_ec_experience = $row["ec_experience"];
	$c_ec_photopath = $row["ec_photopath"];
	
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
	$sql_d = "select * from `ers_academic_position` where `id`='".$c_academic_position_id."' ";
	$dbquery_d = $mysqli->query($sql_d);
	$nRows_d = $dbquery_d->num_rows;
	$c_ea_name = "";
	if($nRows_d>0){
		$result_d = $dbquery_d->fetch_assoc();
		$c_ea_name = $result_d["ea_name"];
	}	
} else {include("../include/close_db.php"); echo "<div style='text-align:center;padding-top:50px;color:#ff0000;font-size:16px;'><strong>...ไม่พบข้อมูล...</strong></div>"; die();}
//$mysqli->query("update `ers_researcher` set `ec_counter`=`ec_counter`+1 where `id` ='".$c_researcher_id."'");

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

<body role="document" style="background: url('../images/<?php if(defined('__EC_PICHOME__')){echo __EC_PICHOME__;}?>') no-repeat; background-attachment:fixed; background-size:contain;height:100%;width:100%;background-position: left center;">

<div class="container bgw1">
	<div class="row bgw2">

		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" style="margin-bottom:15px;background-color:#<?php echo __EC_BGSHOW__;?>;color:#<?php echo __EC_FONTSHOW__;?>;font-weight: bold;"><h4><?php if(defined('__EC_NAME__')){echo "นักวิจัย";} else echo "ระบบคลังข้อมูลงานวิจัย";?></h4></div>

		<!--<div class="col-lg-10 col-md-10 col-sm-10 col-xs-12 col-lg-offset-1 col-md-offset-1 col-sm-offset-1" style="padding-top:20px;padding-bottom:20px;">-->
			<?php if(!empty($c_ec_photopath)){ $images = "../photo/".$c_ec_photopath; ?>
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" style="padding:3px;padding-bottom:10px;">
				<div class="col-lg-3 col-md-3 col-sm-3 col hidden-xs" style="font-weight: bold;">&nbsp;</div>
				<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12 palignl">
					<?php echo "<img src=\"$images\" id=\"photoImage1\" class=\"img-thumbnail-noborder\" styly=\"margin-bottom:10px;\">";?>
				</div>
			</div>
			<?php } ?>
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-5 text-right" style="font-weight: bold;">ชื่อ-นามสกุล(ท.)&nbsp;:&nbsp;</div>
				<div class="col-lg-9 col-md-9 col-sm-9 col-xs-7 text-left"><?= $c_ec_name_th;?></div>
			</div>
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-5 text-right" style="font-weight: bold;">ชื่อ-นามสกุล(อ.)&nbsp;:&nbsp;</div>
				<div class="col-lg-9 col-md-9 col-sm-9 col-xs-7text-left"><?= $c_ec_name_en;?></div>
			</div>
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-5 text-right" style="font-weight: bold;">เลขบัตรประชาชน&nbsp;:&nbsp;</div>
				<div class="col-lg-9 col-md-9 col-sm-9 col-xs-7 text-left"><?= $c_ec_idcard;?></div>
		    </div>
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-5 text-right" style="font-weight: bold;">ส่วนงาน&nbsp;:&nbsp;</div>
				<div class="col-lg-9 col-md-9 col-sm-9 col-xs-7 text-left"><?= $cc_ef_name;?></div>
			</div>
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-5 text-right" style="font-weight: bold;">ภาควิชา/ฝ่าย&nbsp;:&nbsp;</div>
				<div class="col-lg-9 col-md-9 col-sm-9 col-xs-7 text-left"><?= $c_es_name;?></div>
			</div>
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-5 text-right" style="font-weight: bold;">ตำแหน่งทางวิชาการ&nbsp;:&nbsp;</div>
				<div class="col-lg-9 col-md-9 col-sm-9 col-xs-7 text-left"><?= $c_ea_name;?></div>
			</div>
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-5 text-right" style="font-weight: bold;">โทรศัพท์ที่ทำงาน&nbsp;:&nbsp;</div>
				<div class="col-lg-9 col-md-9 col-sm-9 col-xs-7 text-left"><?= $c_ec_phone;?></div>
			</div>
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-5 text-right" style="font-weight: bold;">โทรศัพท์มือถือ&nbsp;:&nbsp;</div>
				<div class="col-lg-9 col-md-9 col-sm-9 col-xs-7 text-left"><?= $c_ec_mobile;?></div>
			</div>
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-5 text-right" style="font-weight: bold;">โทรสาร&nbsp;:&nbsp;</div>
				<div class="col-lg-9 col-md-9 col-sm-9 col-xs-7 text-left"><?= $c_ec_fax;?></div>
			</div>
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-5 text-right" style="font-weight: bold;">อีเมล&nbsp;:&nbsp;</div>
				<div class="col-lg-9 col-md-9 col-sm-9 col-xs-7 text-left"><?= $c_ec_email;?></div>
			</div>
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-5 text-right" style="font-weight: bold;">วุฒิสูงสุด&nbsp;:&nbsp;</div>
				<div class="col-lg-9 col-md-9 col-sm-9 col-xs-7 text-left"><?= $c_ec_highest;?></div>
			</div>
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-5 text-right" style="font-weight: bold;">ประวัติการศึกษา&nbsp;:&nbsp;</div>
				<div class="col-lg-9 col-md-9 col-sm-9 col-xs-7 text-left"><div style="max-width: 95%"><?= $c_ec_educationrecord;?></div></div>
			</div>
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-5 text-right" style="font-weight: bold;">ประวัติการทำงาน&nbsp;:&nbsp;</div>
				<div class="col-lg-9 col-md-9 col-sm-9 col-xs-7 text-left"><div style="max-width: 95%"><?= $c_ec_workhistory;?></div></div>
			</div>
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-5 text-right" style="font-weight: bold;">เชี่ยวชาญในสาขาวิชา&nbsp;:&nbsp;</div>
				<div class="col-lg-9 col-md-9 col-sm-9 col-xs-7 text-left"><div style="max-width: 95%"><?= $c_ec_specialization;?></div></div>
			</div>
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-5 text-right" style="font-weight: bold;">ประสบการณ์พิเศษ&nbsp;:&nbsp;</div>
				<div class="col-lg-9 col-md-9 col-sm-9 col-xs-7 text-left"><div style="max-width: 95%"><?= $c_ec_experience;?></div></div>
			</div>

			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">&nbsp;</div>

	</div>

	<div class="row bgw2">

			<?php
			$sql = "SELECT m . * , d . * FROM ers_document_researcher m JOIN ers_document d ON m.document_id = d.id WHERE (m.researcher_id = $c_researcher_id) ";
			$sql .= "Order by d.ed_year DESC ";

			$res = $mysqli->query($sql);
			$totalRows = $res->num_rows;
			?>

			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="text-center hdetail"><h4>โครงการวิจัย (<?php echo number_format($totalRows);?> โครงการ)</h4></div></div>

			<div class="table-responsive hdetailtb">
			<table class="table table-striped table-bordered sticky-header" style="margin:0;padding:0;">
				<thead>
				<tr style="background-color:#f7f7f7">
				  <th width="5%" style="text-align:center;">ลำดับ</th>
				  <th width="75%" style="text-align:left;">ชื่องานวิจัย</th>
				  <th width="20%" style="text-align:center;">ตำแหน่งในโครงการ</th>
				</tr>
				</thead>
				<tbody>
					<?php
					if($totalRows!="0")
					{
						$jk=0;
						$c_ed_year = "";
						while($result = $res->fetch_assoc()){
							$jk++;
							$c_document_id = $result["document_id"];
							$c_researcher_id = $result["researcher_id"];
							if($c_ed_year != $result["ed_year"]) {
								$jk=1;
								$c_ed_year = $result["ed_year"];
								$c_year = $c_ed_year+543;
								echo "<tr style='background-color:#aaaaaa;'><td colspan='3'><span style='color:#ff0099;'>ปีงบประมาณ $c_year</font></td></tr>";
							}							
							$c_project_position_id = $result["project_position_id"];
							$c_ed_name_th = $result["ed_name_th"];
							$c_ed_name_en = $result["ed_name_en"];
							$c_section_id = $result["section_id"];
							$c_faculty_id = $result["faculty_id"];
							$c_ed_detail = $result["ed_detail"];
							$c_research_type_id = $result["research_type_id"];
							$c_ed_capital = $result["ed_capital"];
							$dbquery->free();
							$sql_d = "select * from `ers_research_type` where `id`='".$c_research_type_id."' ";
							$dbquery_d = $mysqli->query($sql_d);
							$nRows_d = $dbquery_d->num_rows;
							$c_et_name = "";
							if($nRows_d>0){
								$result_d = $dbquery_d->fetch_assoc();
								$c_et_name = $result_d["et_name"];
							}
							$sql_d = "select * from `ers_project_position` where `id`='".$c_project_position_id."' ";
							$dbquery_d = $mysqli->query($sql_d);
							$nRows_d = $dbquery_d->num_rows;
							$c_position_name = "";
							if($nRows_d>0){
								$result_d = $dbquery_d->fetch_assoc();
								$c_position_name =  $result_d['ep_name'];
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
							$bcolor = "rgba(252, 245, 220, 0.8)";
							if(($jk %2)==0){
								$bcolor = "rgba(236, 240, 241, 0.8)";
							}

							echo "<tr style='-moz-opacity: 0.99;-khtml-opacity: 0.99;opacity: 0.99;background-color:$bcolor'>\r\n";
							echo "	<td style='text-align:center;'>$jk</td>";
							echo "	<td style='text-align:left;'>";
							echo "		<div><span style='font-weight: bold;'>ชื่อผลงาน&nbsp;:&nbsp;</span><span>$c_ed_name_th</span></div>";
							echo "		<div><span>$c_ed_name_en</span></div>";
							echo "		<div><span style='font-weight: bold;'>ประเภทของเงินอุดหนุนงานวิจัย&nbsp;:&nbsp;</span><span>$c_et_name</span></div>";
							echo "		<div><span style='font-weight: bold;'>แหล่งทุน&nbsp;:&nbsp;</span><span>$c_ed_capital</span></div>";
							echo "		<div><span style='font-weight: bold;'>ส่วนงาน&nbsp;:&nbsp;</span><span>$c_es_name</span></div>";
							echo "		<div><span style='font-weight: bold;'>ภาควิชา/ฝ่าย&nbsp;:&nbsp;</span><span>$c_ef_name</span></div>";
							echo "		<div><span style='font-weight: bold;'>รายละเอียด&nbsp;:&nbsp;</span><span>$c_ed_detail</span></div>";
							echo "	</td>";
							echo "	<td style='text-align:center;'>$c_position_name</td>\r\n";
							echo "</tr>\r\n";
		
						}//while
					} else {
						echo "<tr><td colspan=\"3\" style=\"text-align:center;color:#ff0000;\"><h5>...ไม่พบข้อมูล...</h5></td></tr>";
					}
					?>
				</tbody>
			</table>
			</div><!-- /.table-responsive -->

	</div>
</div>

</body>
</html>
<?php include("../include/close_db.php");?>