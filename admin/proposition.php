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
if(isset($_GET["c_id"])){	$c_id = $_GET["c_id"];} else {if(isset($_POST["c_id"])){$c_id = $_POST["c_id"];}}

include("../include/config_db.php");


if( isset($_POST["chk_edit"]) ){$chk_edit = $_POST["chk_edit"];} else {$chk_edit="";}

if($chk_edit=="1")
{	

	$s_ep_name = $_POST["s_ep_name"];

	$sql = "select `ep_name` from `ers_project_position` where (`ep_name`='$s_ep_name') ";
	$dbquery = $mysqli->query($link,$sql) or die("Can't send query !!");
	$num_rows = $dbquery->num_rows;
	$dbquery->close();
	if($num_rows > 0) {
		include("../include/close_db.php");
		?>
		<Script language="javascript">
			alert("ตำแหน่งในโครงการ : <?= $s_ep_name;?> มีอยู่ในระบบ ไม่สามารถบันทึกซ้ำได้");
			window.location="proposition.php?iRegister=1";
		</script>
		<?php
		die();
	}

	$sql = "select * from `ers_project_position` where (`id`='".$c_id."')";
	$dbquery = $mysqli->query($link,$sql) or die("Can't send query !!");
	$num_rows = $dbquery->num_rows;
	$dbquery->close();
	if($num_rows > 0) {
		$sql = "update `ers_project_position` set `ep_name`='$s_ep_name',`update_date`=now(),`update_user`='$admin' where (`id`='".$c_id."')";
		$dbquery = $mysqli->query($link,$sql) or die("ไม่สามารถบันทึกข้อมูลได้ !1");
	}else {
		if($s_ep_name != ''){
			$sql = "insert into `ers_project_position` (`ep_name`,`update_date`,`update_user`) values ('$s_ep_name',now(),'$admin')";
			$dbquery = $mysqli->query($link,$sql) or die("ไม่สามารถบันทึกข้อมูลได้ !2");
		}
	}
	$c_id = "";
}

$c_ep_name = '';

if(isset($c_id)){
	if($c_id!=''){
		$sql = "select * from `ers_project_position` where (`id`='$c_id')";
		$dbquery = $mysqli->query($link,$sql) or die("Can't send query!");
		$tRows = $dbquery->num_rows;
		if($tRows>0){
			$row = $dbquery->fetch_assoc();
			$c_ep_name = $row["ep_name"];
		}
		$dbquery->free();
		unset($dbquery);
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
    <title>ระบบคลังข้อมูลงานวิจัย <?php if(defined('__EC_NAME__')){echo __EC_NAME__;}?></title>
	<link href="../images/<?php if(defined('__EC_FAVICON__')){echo __EC_FAVICON_ICO__;}?>" rel="icon" type="image/ico">
	<link href="../images/<?php if(defined('__EC_FAVICON__')){echo __EC_FAVICON__;}?>" rel="icon" type="image/png" sizes="32x32">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css?v=<?php echo filemtime('../bootstrap/css/bootstrap.min.css');?>">
    <script src="../js/jquery.min.js"></script>
    <script src="../bootstrap/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="./style_admin.css?v=<?php echo filemtime('./style_admin.css');?>">
	<!-- Link Swiper's CSS -->
    <link rel="stylesheet" href="../swiper/dist/css/swiper.min.css">
	<link href="https://fonts.googleapis.com/css?family=Prompt" rel="stylesheet">
	<style>
	.col-lg-12 ,.col-lg-7 ,.col-lg-5 { margin:0;padding:0; }
	.col-md-12 ,.col-md-7 ,.col-md-5 { margin:0;padding:0; }
	.col-sm-12 ,.col-sm-7 ,.col-sm-5 { margin:0;padding:0; }
	</style>
<SCRIPT LANGUAGE="JavaScript">
function c_check(){
	if(document.getElementById('s_ep_name').value == "")
	{
		alert("'ชื่อ ตำแหน่งในโครงการ' จำเป็นต้องมีข้อมูล");
		document.getElementById('s_ep_name').focus();
		return false;
	}
}
function c_check2(){
	if(document.getElementById('c_code_1').value == "")
	{
		alert("กรุณาระบุข้อความที่ต้องการค้นหา");
		document.getElementById('c_code_1').focus();
		return false;
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
		   <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="border-bottom:1px solid #009900;font-size:18px;color:#009900;"><span class="glyphicon glyphicon-plus"></span>&nbsp; ตำแหน่งในโครงการ</div>
		   <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >&nbsp;</div>
		</div><!-- /.col-lg-12 col-md-12 col-sm-12 col-xs-12 -->
	</div><!-- /.row -->

	<div class="row">
		<div class="col-lg-6 col-md- 8 col-sm-10 col-xs-12 col-lg-offset-3 col-md-offset-2 col-sm-offset-1" style="padding-top:20px;padding-bottom:20px;">

		  <form name="form1"  method="post" action="proposition.php" onSubmit="return c_check();" role="form">
			  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5 text-right">ชื่อ ตำแหน่งในโครงการ&nbsp;:&nbsp;</div>
				<div class="col-lg-7 col-md-7 col-sm-7 col-xs-7"><input type="text" name="s_ep_name" id="s_ep_name" maxlength="120" class="form-control input_width"  value="<?= $c_ep_name;?>"></div>
			  </div>
			  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >&nbsp;</div>
		      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
					<input type="hidden" name="c_id" value="<?php if(isset($c_id)){ echo $c_id;}else{ echo '';}?>">
   					<input type="hidden" name="chk_edit" value="1"> 
					<input type="submit" name="Submit" value=" บันทึก " class="btn btn-warning" style="width:100px;font-size:18px;">&nbsp;
			  </div>
			  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >&nbsp;</div>			  
		  </form>

		</div><!-- /.col-lg-6 col-md- 8 col-sm-10 col-xs-12 col-lg-offset-3 col-md-offset-2 col-sm-offset-1 -->
	</div><!-- /.row -->

	<div class="row">

		<hr align="center" width="100%" noshade size="1">
		  <form name="form2" method="post" action="proposition.php#top_page" onSubmit="return c_check2();" role="form">
		  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
			  <div style="text-align:center;">
				<span class="sbreak2">ค้นหา : <input type="text" name="c_code_1" id="c_code_1" class="searchbox-control" maxlength="30" placeholder="ข้อความที่ต้องการค้นหา">&nbsp;<input type="submit" name="Submit" id="Submit" value="ค้นหา" class="btn btn-success" style="width:70px;padding-left:3px;">&nbsp;<input type="button" name="Clear" id="Clear" value="ยกเลิก" class="btn btn-info" style="width:70px;margin-top:0;" onclick="window.location='proposition.php?iRegister=1';"></span>
			  </div>
		  </div>
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
			  $a1sort = $a2sort = "<span class='glyphicon glyphicon-sort' style='font-size:8px;'></span>";
			  switch ($sd) {
					case '1': $a1sort = $asort; break;
					case '2': $a2sort = $asort; break;
				}
			?>
		  
		  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" style="background-color:#<?php echo __EC_BGSHOW__;?>;color:#<?php echo __EC_FONTSHOW__;?>;border-radius: 5px 5px 0px 0px;"><h4>แสดงข้อมูล ตำแหน่งในโครงการ</h4><a name="top_page"></a></div>

		  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="table-responsive">
			<table class="table table-striped">
              <thead>
                <tr style="background-color:#f7f7f7">
                  <th>&nbsp;</th>
				  <th>&nbsp;</th>
                  <th style="text-align:center;"><a href="proposition.php?sd=1&sh_order=<?= $sh_order;?>#top_page" target="_parent">ID <?= $a1sort;?></a></th>
                  <th><a href="proposition.php?sd=2&sh_order=<?= $sh_order;?>#top_page" target="_parent">ชื่อ ตำแหน่งในโครงการ <?= $a2sort;?></a></th>
                </tr>
              </thead>
              <tbody>
		  
			<?php 

			$sql = "select * From `ers_project_position` where 1 ";
			if(isset($_POST["c_code_1"])){$c_code_1 = $_POST["c_code_1"];	}else{$c_code_1 = "";}
			if(trim($c_code_1)!=""){
				$_SESSION["u_code_1"] = $c_code_1;
			}
			if(isset($_SESSION["u_code_1"]))
			{
				 $u_code_1 = $_SESSION["u_code_1"];
				 $sql .= "and ((`id` = '$u_code_1') or (`ep_name` like '%$u_code_1%')) ";
			}
			if(isset($_GET["sd"])){$sd = trim($_GET["sd"]);} else {	$sd = 0;}
			switch ($sd) {
				case '1': $sql .= "Order by `id` "; break;
				case '2': $sql .= "Order by `ep_name` "; break;
				default : $sql .= "Order by `id` ";
			}
			if($sh_order==1){$sql .= "DESC ";} else {$sql .= "ASC ";}
			$res = $mysqli->query($link,$sql);
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
			$res = $mysqli->query($link,$sql);

			if($totalRows!="0"){

				if($Page==1){$jk=0;}
				else{
					$a=$Page * $Per_Page;
					$jk=$a-$Per_Page;
				}

				while($result = $res->fetch_assoc()){
					$jk++;
					$c_id = $result["id"];
					$c_name = $result["ep_name"];

					$bcolor = "#ffffff";
					if(($jk %2)==0){
						$bcolor = "rgba(236, 240, 241, 0.8)";
					}
					$code_1 = $c_name;

				?>

					<tr style="background-color:<?= $bcolor;?>">
					<td style="text-align:center;width:100px;min-width:100px;">
					<?php
					echo "<a href='proposition.php?c_id=$c_id' style='color:green;font-size:16px;' title='แก้ไข'><span class='glyphicon glyphicon-edit'></span>&nbsp;<span style='font-size:14px;'>แก้ไข</span></a>";
					?>
					</td>
					<td style="text-align:center;width:100px;min-width:100px;">
					<?php
					echo "<a href='del_data.php?c_id=$c_id&chk_p=5&code_1=$code_1' style='color:red;font-size:16px;' title='ลบ'><span class='glyphicon glyphicon-trash'></span>&nbsp;<span style='font-size:14px;'>ลบ</span></a>";
					?>
					</td>
					<td style="text-align:center;"><?= $c_id;?></td>
					<td><?= $c_name;?></td>
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
			<div style="font-size:14px;">หน้า :
				<?php
					$pages = new Paginator;
					$pages->items_total = $totalRows;
					$pages->mid_range = 7;
					$pages->current_page = $Page;
					$pages->default_ipp = $Per_Page;
					$pages->url_next = $_SERVER["PHP_SELF"]."?&sd=$sd&sh_order=$sh_order&Page=";
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
document.getElementById('s_ep_name').focus();
</script>