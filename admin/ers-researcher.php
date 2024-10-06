<?
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
$admin = $_SESSION['admin'];
$userlevel = $_SESSION['userlevel'];

$_menu_id = 3;

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
$c_search_position = 0;
if(isset($_POST["f_researcher_position_id"])){
	$c_search_position = $_POST["f_researcher_position_id"];
}
if(isset($_GET["f_researcher_position_id"])){
	$c_search_position = $_GET["f_researcher_position_id"];
}
//if(isset($_GET["c_id"])){	$c_id = $_GET["c_id"];} else {if(isset($_POST["c_id"])){$c_id = $_POST["c_id"];}}

include("../include/config_db.php");
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
	<link href="https://fonts.googleapis.com/css?family=Prompt" rel="stylesheet">
	<link rel="stylesheet" href="../admin/style_admin.css?v=<?php echo filemtime('../admin/style_admin.css');?>">
	<SCRIPT LANGUAGE="JavaScript">
	function c_check2(){
		/*if(document.getElementById('c_code_1').value == "")
		{
			alert("กรุณาใส่ข้อมูลที่ต้องการค้นหา");
			document.getElementById('c_code_1').select();
			return false;
		}*/
	}
	function show_detail(mid, oid){
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

		myleft=(document.body.clientWidth)?(document.body.clientWidth-sw3)/2:100;   
		mytop=(screen.height)?(screen.height-sh2)/2:100; 

		$('#detailoverlay').fadeIn();	
		
		data = "<div style=\"border-radius:50%;padding: 3px; position: absolute; margin-left: "+ sw3 +"px; background: #111;\" onmouseover=\"changebckr();\" onmouseout=\"changebckb();\" id=\"btnclose\"><img OnClick=\"hide_detail();\" style=\"cursor: pointer;width:20px;height:auto;\" src=\"../images/close.png\" alt=\"close\"></div><iframe id=\"ersshowifm\" frameBorder=0 width=\"" + sw2 + "\" height=\"" + sh + "\" src='detailrs.php?m=" + mid + "&t=" + oid +"' style=\"z-ndex:9999;border-radius:5px;\">not supported</iframe>" + "<scr" + "ipt>$(document).keyup(function(e) { if (e.keyCode == 27) { hide_detail() } });  </scr" + "ipt>" + "<scr" + "ipt>$('#detailoverlay').click(function() {{ hide_detail(); } }); </scr" + "ipt>";
		var sct = $(window).scrollTop(); /*document.body.scrollTop;*/
		document.getElementById('ersshow').style.left = (myleft) + "px";
		document.getElementById('ersshow').style.top = (sct + 25) + "px";
		
		$('#ersshow').html(data).show();	
				
	}

	$(window).resize(function() { 
		var windowWidth = $(window).width();
		var pw1 = (windowWidth * 80) / 100;
		var pw2 = 0;
		if(windowWidth > 1367){
			pw2 = 1300;
		} else {
			pw2 = pw1;
		}
		pw3 = pw2 + 10;
		var pleft = (windowWidth)?(windowWidth-pw3)/2:100;
		if(windowWidth < 768){
			document.getElementById('ersshow').style.left = '20px';
		} else {
			document.getElementById('ersshow').style.left = (pleft) + "px";
		}
	}); 
	
	$(window).scroll(function() { 
		var sct = $(window).scrollTop(); 
		document.getElementById('ersshow').style.top = (sct + 25) + "px";
		if(sct>100){
			document.getElementById('ontop1').classList.add("ontop1");
			document.getElementById('ontop2').classList.add("ontop2");
			document.getElementById('ontop3').classList.add("ontop3");
			document.getElementById('ontop4').classList.add("ontop4");
			document.getElementById('ontop5').classList.add("ontop5");
			document.getElementById('ontop6').classList.add("ontop6");
			document.getElementById('ontop7').classList.add("ontop7");
			document.getElementById('ontop8').classList.add("ontop8");
			document.getElementById('ontop9').classList.add("ontop9");
		} else {
			document.getElementById('ontop1').classList.remove("ontop1");
			document.getElementById('ontop2').classList.remove("ontop2");
			document.getElementById('ontop3').classList.remove("ontop3");
			document.getElementById('ontop4').classList.remove("ontop4");
			document.getElementById('ontop5').classList.remove("ontop5");
			document.getElementById('ontop6').classList.remove("ontop6");
			document.getElementById('ontop7').classList.remove("ontop7");
			document.getElementById('ontop8').classList.remove("ontop8");
			document.getElementById('ontop9').classList.remove("ontop9");
		}
	}); 
	
	function hide_detail(){

		$('document').unbind('keyup');
		$('#detailoverlay').unbind('click');
			
		$('#ersshow').fadeOut();
		$('#detailoverlay').fadeOut();
		$('#ersshow').html('');
		
	}

	function changebckr(){
		document.getElementById('btnclose').style.backgroundColor = "#ff0000";
	}
	function changebckb(){
		document.getElementById('btnclose').style.backgroundColor = "#000";
	}
	</SCRIPT>
	<style>
	.footrow{display:none;}
	#detailoverlay{
		position:fixed;
		width:100%;
		height:100%;
		top:0;
		left:0;
		opacity:0.6;
		display: none;
		background-color: #ccc;
		z-index: 9998;
	}
	#ersshow{
		border: solid 1px #616161;
		border-radius: 5px;
		position: absolute;
		background-color: #fff;
		z-index: 9999;
		display: none;
		box-shadow: 0 0 5px #000;
	}	
	@media (min-width: 768px) {
		/*body {
			padding-top:50px;
		}*/
		table.floatThead-table {
			border-top: none;
			border-bottom: none;
		}
		th {
		  position: sticky;
		  top: 50px;
		  background: #e5e5e5;
		}
	}
	.hSelect {
		padding-top:10px;
		padding-bottom:10px;
	}
	.ontop1 {z-index: 991;}
	.ontop2 {z-index: 992;}
	.ontop3 {z-index: 993;}
	.ontop4 {z-index: 994;}
	.ontop5 {z-index: 995;}
	.ontop6 {z-index: 996;}
	.ontop7 {z-index: 997;}
	.ontop8 {z-index: 998;}
	.ontop9 {z-index: 999;}
	.col-lg-12 ,.col-lg-9 ,.col-lg-3 { margin:0;padding:0; }
	.col-md-12 ,.col-md-9 ,.col-md-3 { margin:0;padding:0; }
	.col-sm-12 ,.col-sm-9 ,.col-sm-3 { margin:0;padding:0; }
	</style>
</head>
<body role="document">
<a name="detailtop"></a>
<div id="detailoverlay"></div>
<a href="#0" class="cd-top">Top</a>
<!-- cd-top JS -->
<script src="../js/main.js"></script>

<div class="container-fluid">
	<? require_once "header.php"; ?>
	<div class="row" style="padding-top:50px;padding-bottom:10px;">			  
		<div id="hSelect" class="hSelect">

			<form name="form2" method="post" action="ers-researcher.php" onSubmit="return c_check2();" role="form">
			    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
					  <?
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
						<?
						$sql_d = "select * from `ers_faculty` where 1 ";
						$dbquery_d = $mysqli->query($sql_d);
						$nRows_d = $dbquery_d->num_rows;
						if($nRows_d>0){
						?>
							<? while ($row_d = $dbquery_d->fetch_assoc()) { ?>
								<option value="<? echo $row_d['id']; ?>" <? if($c_search_faculty==$row_d['id']) echo "selected"?> >&nbsp;&nbsp;- <? echo $row_d['ef_name']; ?></option>
							<? } //while
						}?>
					  </select>&nbsp;
					  <select name="f_section_id" id="f_section_id" style="width:150px;border-radius:5px;height:35px;border:1px solid #ccc;padding:5px; margin-top:3px;">
						<option value="0">ภาควิชา/ฝ่าย</option>
						<?
						$sql_d = "select * from `ers_section` where 1 ";
						$dbquery_d = $mysqli->query($sql_d);
						$nRows_d = $dbquery_d->num_rows;
						if($nRows_d>0){
						?>
							<? while ($row_d = $dbquery_d->fetch_assoc()) { ?>
								<option value="<? echo $row_d['id']; ?>" <? if($c_search_section==$row_d['id']) echo "selected"?> >&nbsp;&nbsp;- <? echo $row_d['es_name']; ?></option>
							<? } //while
						}?>
					  </select>&nbsp;
					  <select name="f_researcher_position_id" id="f_researcher_position_id" style="width:110px;border-radius:5px;height:35px;border:1px solid #ccc;padding:5px; margin-top:3px;">
						<option value="0">สถานภาพ</option>
						<?
						$sql_d = "select * from `ers_researcher_position` where 1 ";
						$dbquery_d = $mysqli->query($sql_d);
						$nRows_d = $dbquery_d->num_rows;
						if($nRows_d>0){
						?>
							<? 
							while ($row_d = $dbquery_d->fetch_assoc()) { 
								if($row_d['et_status']==1){
									$c_status_name = "นักวิจัยภายใน : ".$row_d['et_name'];
								} else {
									$c_status_name = "นักวิจัยภายนอก : ".$row_d['et_name'];
								}
							?>
								<option value="<? echo $row_d['id']; ?>" <? if($c_search_position==$row_d['id']) echo "selected"?> >&nbsp;&nbsp;- <? echo $c_status_name; ?></option>
							<? } //while
						}?>
					  </select>&nbsp;
					  </span>
					  <span class="sbreak2">
					  <input type="text" name="c_code_1" id="c_code_1" class="searchbox-control" maxlength="30"placeholder="ข้อความที่ต้องการค้นหา" value="<?=$_SESSION["u_code_1"];?>">&nbsp;
					  <input type="submit" name="Submit" id="Submit" value="ค้นหา" class="btn btn-success" style="width:70px; margin-top:0;">&nbsp;<input type="button" name="Clear" id="Clear" value="ยกเลิก" class="btn btn-info" style="width:70px;margin-top:0;" onclick="window.location='ers-researcher.php?iRegister=1';"></span>
			    </div>
			</form>

		</div>
	</div>
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" style="background-color:#<?echo __EC_BGSHOW__;?>;color:#<?echo __EC_FONTSHOW__;?>;"><h4 class="sub-header">นักวิจัย</h4></div>
		 <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">

			<?
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

			<div id="tblResponsive">

			<table class="table table-striped table-bordered sticky-header" id="table-1" style="background: url('../images/<?php if(defined('__EC_PICHOME__')){echo __EC_PICHOME__;}?>') repeat-y; background-attachment:fixed; background-size:contain;height:100%;width:100%;">
				<thead>
					<tr id="ontop1" style="background-color:#e5e5e5">
						  <th style="vertical-align:middle;text-align:center;" id="ontop2"><a href="ers-researcher.php?sd=1&sh_order=<?=$sh_order;?>" target="_parent" style="white-space: nowrap;">ID <?=$a1sort;?></a></th>
						  <th style="vertical-align:middle;text-align:center;"  id="ontop3"><a href="ers-researcher.php?sd=2&sh_order=<?=$sh_order;?>" target="_parent" style="white-space: nowrap;">ชื่อ-นามสกุล(ท.) <?=$a2sort;?></a></th>
						  <th style="vertical-align:middle;text-align:center;"  id="ontop4"><a href="ers-researcher.php?sd=3&sh_order=<?=$sh_order;?>" target="_parent" style="white-space: nowrap;">ชื่อ-นามสกุล(อ.) <?=$a3sort;?></a></th>
						  <th style="vertical-align:middle;text-align:center;" id="ontop5"><a href="ers-researcher.php?sd=4&sh_order=<?=$sh_order;?>" target="_parent" style="white-space: nowrap;">ภาควิชา/ฝ่าย <?=$a4sort;?></a></th>
						  <th style="vertical-align:middle;text-align:center;" id="ontop6"><a href="ers-researcher.php?sd=5&sh_order=<?=$sh_order;?>" target="_parent" style="white-space: nowrap;">ส่วนงาน <?=$a5sort;?></a></th>
						  <th style="vertical-align:middle;text-align:center;" id="ontop7">ความเชี่ยวชาญในสาขาวิชา</th>
						  <th style="vertical-align:middle;text-align:center;" id="ontop8"><a href="ers-researcher.php?sd=6&sh_order=<?=$sh_order;?>" target="_parent" style="white-space: nowrap;">สถานภาพ <?=$a6sort;?></a></th>
						  <th style="vertical-align:middle;text-align:center;" id="ontop9">อ่าน</th>
					</tr>
				</thead>
				<tbody class="bgw">
				<?
				$sql = "select * From `ers_researcher` where 1 ";
				if(isset($_SESSION["u_code_1"]) and !empty($_SESSION["u_code_1"]))
				{
					  $u_code_1 = $_SESSION["u_code_1"];
					  $u_code_1 = trim($u_code_1);
					  $sql .= "and ((`id` = '$u_code_1')";
					  if(strpos( $u_code_1," ")>=1){
						  $u_code_array = explode(" ",$u_code_1);
						  $u_search = $u_code_array[0];
						  $sql .= " or (`ec_title_th` like '%$u_search%') or (`ec_firstname_th` like '%$u_search%') or (`ec_lastname_th` like '%$u_search%') or (`ec_title_en` like '%$u_search%') or (`ec_firstname_en` like '%$u_search%') or (`ec_lastname_en` like '%$u_search%')or (`ec_phone` like '%$u_search%') or (`ec_mobile` like '%$u_search%') or (`ec_fax` like '%$u_search%') or (`ec_email` like '%$u_search%') or (`ec_highest` like '%$u_search%') or (`ec_educationrecord` like '%$u_search%') or (`ec_workhistory` like '%$u_search%') or (`ec_specialization` like '%$u_search%') or (`ec_specialization` like '%$u_search%')";
						  for($i = 1, $size = count($u_code_array); $i < $size; ++$i) {
							   $u_search = $u_code_array[$i];
							   $sql .= ") and ((`ec_title_th` like '%$u_search%') or (`ec_firstname_th` like '%$u_search%') or (`ec_lastname_th` like '%$u_search%') or (`ec_title_en` like '%$u_search%') or (`ec_firstname_en` like '%$u_search%') or (`ec_lastname_en` like '%$u_search%')or (`ec_phone` like '%$u_search%') or (`ec_mobile` like '%$u_search%') or (`ec_fax` like '%$u_search%') or (`ec_email` like '%$u_search%') or (`ec_highest` like '%$u_search%') or (`ec_educationrecord` like '%$u_search%') or (`ec_workhistory` like '%$u_search%') or (`ec_specialization` like '%$u_search%') or (`ec_specialization` like '%$u_search%')";
						  }
					  } else {
						  $sql .= " or (`ec_title_th` like '%$u_code_1%') or (`ec_firstname_th` like '%$u_code_1%') or (`ec_lastname_th` like '%$u_code_1%') or (`ec_title_en` like '%$u_code_1%') or (`ec_firstname_en` like '%$u_code_1%') or (`ec_lastname_en` like '%$u_code_1%')or (`ec_phone` like '%$u_code_1%') or (`ec_mobile` like '%$u_code_1%') or (`ec_fax` like '%$u_code_1%') or (`ec_email` like '%$u_code_1%') or (`ec_highest` like '%$u_code_1%') or (`ec_educationrecord` like '%$u_code_1%') or (`ec_workhistory` like '%$u_code_1%') or (`ec_specialization` like '%$u_code_1%') or (`ec_specialization` like '%$u_code_1%')";
					  }
					  $sql .= ") ";
					  //$sql .= "and ((`id` = '$u_code_1') or (`ec_title_th` like '%$u_code_1%') or (`ec_firstname_th` like '%$u_code_1%') or (`ec_lastname_th` like '%$u_code_1%') or (`ec_title_en` like '%$u_code_1%') or (`ec_firstname_en` like '%$u_code_1%') or (`ec_lastname_en` like '%$u_code_1%')or (`ec_phone` like '%$u_code_1%') or (`ec_mobile` like '%$u_code_1%') or (`ec_fax` like '%$u_code_1%') or (`ec_email` like '%$u_code_1%') or (`ec_highest` like '%$u_code_1%') or (`ec_educationrecord` like '%$u_code_1%') or (`ec_workhistory` like '%$u_code_1%') or (`ec_specialization` like '%$u_code_1%') or (`ec_specialization` like '%$u_code_1%')) ";
				}
				if($c_search_section>0) 
				{
					 $sql .= "and (`section_id` = '".$c_search_section."') ";
				}
				if($c_search_faculty>0) 
				{
					 $sql .= "and (`faculty_id` = '".$c_search_faculty."') ";
				}
				if($c_search_position>0) 
				{
					 $sql .= "and (`researcher_position_id` = '".$c_search_position."') ";
				}
				if(isset($_GET["sd"])){$sd = $_GET["sd"];} else {	$sd = 0;}
				switch ($sd) {
					case '1': $sql .= "Order by `id` "; break;
					case '2': $sql .= "Order by `ec_firstname_th` "; break;
					case '3': $sql .= "Order by `ec_firstname_en` "; break;
					case '4': $sql .= "Order by `section_id` "; break;
					case '5': $sql .= "Order by `faculty_id` "; break;
					case '6': $sql .= "Order by `researcher_position_id` "; break;
					default : $sql .= "Order by `id` ";
				}
				if($sh_order==1){$sql .= "DESC ";} else {$sql .= "ASC ";}
				$ch = array("2","3","4","5","6","7");
				if(in_array($sd,$ch)){$sql .= ",`id` Desc ";}
				$res = $mysqli->query($sql);
				$totalRows = $res->num_rows;

				if(isset($_POST["Per_Page"])) {
					$Per_Page = $_POST["Per_Page"];
				} else { $Per_Page = $_GET["Per_Page"];}
				if(!isset($Per_Page) or empty($Per_Page)) { $Per_Page = 18; }
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
						$c_ec_name_th = $result["ec_firstname_th"];
						$c_ec_name_en = $result["ec_firstname_en"];
						if(!empty($result['ec_title_th'])){
							$c_ec_name_th = $result['ec_title_th'].$c_ec_name_th;
						}
						if(!empty($result['ec_lastname_th'])){
							$c_ec_name_th .= " ".$result['ec_lastname_th'];
						}
						if(!empty($result['ec_title_en'])){
							$c_ec_name_en = $result['ec_title_en'].$c_ec_name_en;
						}
						if(!empty($result['ec_lastname_en'])){
							$c_ec_name_en .= " ".$result['ec_lastname_en'];
						}
						$c_section_id = $result["section_id"];
						$c_faculty_id = $result["faculty_id"];
						$c_researcher_position_id = $result["researcher_position_id"];
						$sql_d = "select * From `ers_researcher_position` where (`id`='".$c_researcher_position_id."')";
						$dbquery_d = $mysqli->query($sql_d);
						$nRows_d = $dbquery_d->num_rows;
						$s_status_name = "";
						$c_status = 0;
						if($nRows_d>0){
							$result_d = $dbquery_d->fetch_assoc();
							$s_status_name = $result_d['et_name'];
							$c_status = $result_d["et_status"];
						}
						$c_status_name = "";
						if($c_status==1){
							$c_status_name = "นักวิจัยภายใน : ".$s_status_name;
						} 
						if($c_status==2){
							$c_status_name = "นักวิจัยภายนอก : ".$s_status_name;
						}
						$c_ec_specialization = $result["ec_specialization"];
						$c_ec_counter = $result["ec_counter"];

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

						$bcolor = "rgba(255, 255, 255, 0.8)";
						if(($jk %2)==0){
							$bcolor = "rgba(236, 240, 241, 0.8)";
						}

					?>
					<tr>
						<td style="text-align:center;padding-left:5px;padding-right:5px;">&nbsp;<?=$c_id;?>&nbsp;</td>
						<td style="text-align:left;padding-left:5px;padding-right:5px;">&nbsp;<?echo "<a href=\"javascript:void(0)\" onclick=\"show_detail(".$c_id.", '1');\" title=''>".$c_ec_name_th."</a>";?>&nbsp;</td>
						<td style="text-align:left;padding-left:5px;padding-right:5px;">&nbsp;<?echo "<a href=\"javascript:void(0)\" onclick=\"show_detail(".$c_id.", '1');\" title=''>".$c_ec_name_en."</a>";?>&nbsp;</td>
						<td style="text-align:center;padding-left:5px;padding-right:5px;">&nbsp;<?=$c_es_name;?>&nbsp;</td>
						<td style="text-align:center;padding-left:5px;padding-right:5px;">&nbsp;<?=$c_ef_name;?>&nbsp;</td>
						<td style="text-align:left;padding-left:5px;padding-right:5px;min-width:80px;">&nbsp;<?=$c_ec_specialization;?>&nbsp;</td>
						<td style="text-align:left;padding-left:5px;padding-right:5px;min-width:80px;">&nbsp;<?=$c_status_name;?>&nbsp;</td>
						<td style="text-align:center;padding-left:5px;padding-right:5px;min-width:30px;">&nbsp;<?echo number_format($c_ec_counter);?>&nbsp;</td>
					</tr>
					<?
					}//while
					$res->free();
				} else {
					echo "<tr class='text-center' style='background-color:#ffffff'><td colspan='8'><br><span style='color:#ff0000;font-size:18px;'>..ไม่พบข้อมูล..</span></td></tr>";
				}
				?>					
				</tbody>
			</table>
			</div>

		 </div>
	</div>
   
	<div class="row" style="margin:0;padding:0;">			  	
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="col-lg-9 col-md-9 col-sm-9 col-xs-7" style="font-size:14px;">หน้า :
				<?
					$pages = new Paginator;
					$pages->items_total = $totalRows;
					$pages->mid_range = 7;
					$pages->current_page = $Page;
					$pages->default_ipp = $Per_Page;
					$pages->url_next = $_SERVER["PHP_SELF"]."?&f_section_id=$c_search_section&f_faculty_id=$c_search_faculty&f_researcher_position_id=$c_search_position&sd=$sd&sh_order=$sh_order&Page=";
					$pages->paginate();
					echo $pages->display_pages();
					unset($Paginator);
				?>
			</div>
			<div class="col-lg-3 col-md-3 col-sm-3 col-xs-5 text-right">
				<form name="form3" method="post" action="ers-researcher.php?sd=<?=$sd;?>&sh_order=<?=$sh_order;?>&Page=1" role="form">
					<select name="Per_Page" id="Per_Page" style="width:130px;border-radius:5px;border:1px solid #<?echo __EC_BGSHOW__;?>;padding:5px;" onchange="document.form3.submit()">
					<option value="18" <?php if($Per_Page=='18'){echo "selected";}?>>18 รายการ/หน้า</option>
					<option value="30"<?php if($Per_Page=='30'){echo "selected";}?>>30 รายการ/หน้า</option>
					<option value="60"<?php if($Per_Page=='60'){echo "selected";}?>>60 รายการ/หน้า</option>
					<option value="90"<?php if($Per_Page=='90'){echo "selected";}?>>90 รายการ/หน้า</option>
					</select>
					<input type="hidden" name="f_section_id" id="f_section_id" value="<? if($c_search_section){ echo $c_search_section;}else{ echo '';}?>">
					<input type="hidden" name="f_faculty_id" id="f_faculty_id" value="<? if($c_search_faculty){ echo $c_search_faculty;}else{ echo '';}?>">
					<input type="hidden" name="f_researcher_position_id" id="f_researcher_position_id" value="<? if($c_search_position){ echo $c_search_position;}else{ echo '';}?>">
				</form>
			</div>
		</div>

		 <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div align="center" style="padding-bottom:<?php if($Per_Page=='18'){echo "10px";}else{echo "80px";}?>;"><? require_once "../admin/footer.php"; ?></div>
	    </div>
	</div><!-- /.row -->

</div>
<div id="ersshow"></div>
</body>
</html>
<? include("../include/close_db.php"); ?>
<script>
document.getElementById("fmnavbar").className = "navbar navbar-default navbar-fixed-top";
if( document.body.clientWidth > 767) {
	document.getElementById('tblResponsive').classList.remove("table-responsive");
	/*$(document).ready(function(){
	  $(".sticky-header").floatThead({top:50});
	});*/
} else {
	document.getElementById('tblResponsive').classList.add("table-responsive");
	document.getElementById('table-1').classList.remove("sticky-header");
}
document.getElementById('c_code_1').focus();
</script>