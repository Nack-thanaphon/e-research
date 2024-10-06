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
	echo "<div style='text-align:center;padding-top:30px;font-size:18px;'>Session Expired กรุณาเข้าระบบใหม่</div>";
	//echo "<script>alert('Session Expired กรุณาเข้าระบบใหม่เพื่อใช้งาน');parent.location='login.php';</script>";
	?>
	<script>
	alert("Session Expired กรุณาเข้าระบบใหม่");
	window.onunload = refreshParent;
    function refreshParent() {
        window.opener.location.reload();
    }
	window.close();
	</script>
	<?
	exit();
 }
require_once "../include/chkuserlevel.php";

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
if(isset($_GET["doc_id"])){	$doc_id = $_GET["doc_id"];} else {if(isset($_POST["doc_id"])){$doc_id = $_POST["doc_id"];}}
if(isset($_GET["c_id"])){	$c_id = $_GET["c_id"];} else {if(isset($_POST["c_id"])){$c_id = $_POST["c_id"];}}

include("../include/config_db.php");

if( isset($_POST["chk_edit"]) ){$chk_edit = $_POST["chk_edit"];} else {$chk_edit="";}

if($chk_edit=="1")
{

	$s_researcher_id = $_POST["s_researcher_id"];
	$s_project_position_id = $_POST["s_project_position_id"];
	$s_ratio = $_POST["s_ratio"];
	
	$sql = "select * from `ers_document_researcher` where (`id`='".$c_id."')";
	$dbquery = $mysqli->query($sql) or die("Can't send query !!");
	$num_rows = $dbquery->num_rows;
	$dbquery->close();
	if($num_rows > 0) {
		$sql = "update `ers_document_researcher` set `researcher_id`='$s_researcher_id',`project_position_id`='$s_project_position_id',`ratio`='$s_ratio',`update_date`=now(),`update_user`='$admin' where (`id`='".$c_id."')";
		$dbquery = $mysqli->query($sql) or die("ไม่สามารถบันทึกข้อมูลได้ !A");
	}else {
		if($s_researcher_id > 0){
			$sql = "insert into `ers_document_researcher` (`document_id`,`researcher_id`,`project_position_id`,`ratio`,`update_date`,`update_user`) values ('$doc_id','$s_researcher_id','$s_project_position_id','$s_ratio',now(),'$admin')";
			$dbquery = $mysqli->query($sql) or die("ไม่สามารถบันทึกข้อมูลได้ !B");
		}
	}
	$sql = "update `ers_researcher` set `ec_latest_research`=now() where (`id`='".$s_researcher_id."')";
	$dbquery = $mysqli->query($sql);
	$c_id = "";
}

$c_ed_name_th = '';
if(isset($doc_id)){
	if($doc_id!=''){
		$sql = "select * from `ers_document` where (`id`='$doc_id')";
		$dbquery = $mysqli->query($sql) or die("Can't send query!");
		$tRows = $dbquery->num_rows;
		if($tRows>0){
			$row = $dbquery->fetch_assoc();
			$c_ed_name_th = $row["ed_name_th"];
		}
		$dbquery->free();
		unset($dbquery);
	} 
}

$c_researcher_id = 0;
$c_project_position_id = 0;
$c_ratio = "";
if(isset($c_id)){
	if($c_id!=''){
		$sql = "select * from `ers_document_researcher` where (`id`='$c_id')";
		$dbquery = $mysqli->query($sql) or die("Can't send query!");
		$tRows = $dbquery->num_rows;
		if($tRows>0){
			$row = $dbquery->fetch_assoc();
			$c_researcher_id = $row["researcher_id"];
			$c_project_position_id = $row["project_position_id"];
			$c_ratio = $row["ratio"];
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
    <title>ระบบคลังข้อมูลงานวิจัย <?if(defined('__EC_NAME__')){echo __EC_NAME__;}?></title>
	<link href="../images/<?if(defined('__EC_FAVICON__')){echo __EC_FAVICON_ICO__;}?>" rel="icon" type="image/ico">
	<link href="../images/<?if(defined('__EC_FAVICON__')){echo __EC_FAVICON__;}?>" rel="icon" type="image/png" sizes="32x32">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css?v=<?php echo filemtime('../bootstrap/css/bootstrap.min.css');?>">
    <script src="../js/jquery.min.js"></script>
    <script src="../bootstrap/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="./style_admin.css?v=<?php echo filemtime('./style_admin.css');?>">
	<link href="https://fonts.googleapis.com/css?family=Prompt" rel="stylesheet">
	<script src="./selectize.min.js"></script>
	<link rel="stylesheet" href="./selectize.bootstrap3.min.css">
	<style>
	.col-lg-12 ,.col-lg-7 ,.col-lg-5 { margin:0;padding:0; }
	.col-md-12 ,.col-md-7 ,.col-md-5 { margin:0;padding:0; }
	.col-sm-12 ,.col-sm-7 ,.col-sm-5 { margin:0;padding:0; }
	</style>
<script type="text/javascript" src="ajax.js"></script>
<script type="text/javascript" src="ajax_content.js"></script>
<script LANGUAGE="JavaScript">
function confirmDelete(span_id,id_order,filename,content_id) {
	  if (confirm("ยืนยันการลบ "+filename)) {
		ajax_loadContent(span_id,'deldocres.php',id_order,filename,content_id);
		window.location = "eresearcher.php?doc_id="+content_id;
	  }
	}
</script>
</head>
<body role="document">

<div class="container">

	<div class="row">

		<div style="padding-top:20px;">

		  <form name="form1"  method="post" action="eresearcher.php" role="form">

			  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding:3px;">
				<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5 text-right">ผลงานวิจัย&nbsp;:&nbsp;</div>
				<div class="col-lg-7 col-md-7 col-sm-7 col-xs-7"><?echo $c_ed_name_th." [ID:$doc_id]";?><input type="hidden" name="s_document_id" id="s_document_id" maxlength="120" class="form-control input_width"  value="<?=$doc_id;?>"></div>
			  </div>
			  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding:3px;">
				<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5 text-right">นักวิจัย&nbsp;:&nbsp;</div>
				<div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
					<?
					$sql_d = "select * from `ers_researcher` where 1 order by ec_firstname_th ASC ,ec_lastname_th ASC";
					$dbquery_d = $mysqli->query($sql_d);
					$nRows_d = $dbquery_d->num_rows;
					if($nRows_d>0){
					?>
						<!--<select name="s_researcher_id" id="s_researcher_id" style="width:200px;border-radius:5px;border:1px solid #cccccc;padding:3px;">-->
						<!--<option value="0">เลือกนักวิจัย</option>-->
						 <select name="s_researcher_id" id="s_researcher_id" placeholder="เลือกนักวิจัย..." style="width:200px;border-radius:5px;border:1px solid #cccccc;padding:3px;">
							<option value="">เลือกนักวิจัย...</option>
							<? 
							while ($row_d = $dbquery_d->fetch_assoc()) { 
								//$s_name = "";
								//if(!empty($row_d['ec_title_th'])){
								//	$s_name = $row_d['ec_title_th'];
								//}
								$s_name = $row_d['ec_firstname_th'];
								if(!empty($row_d['ec_lastname_th'])){
									$s_name .= " ".$row_d['ec_lastname_th'];
								}
							?>
								<option value="<? echo $row_d['id']; ?>" <? if($c_researcher_id==$row_d['id']) echo "selected";?>><? echo $s_name; ?></option>
							<? } //while ?>
						</select>
					<?}?>
				</div>
			  </div>			  
			  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding:3px;">
				<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5 text-right">ตำแหน่งในโครงการ&nbsp;:&nbsp;</div>
				<div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
					<?
					$sql_d = "select * from `ers_project_position` where 1 ";
					$dbquery_d = $mysqli->query($sql_d);
					$nRows_d = $dbquery_d->num_rows;
					if($nRows_d>0){
					?>
						<!--<select name="s_project_position_id" id="s_project_position_id" style="width:200px;border-radius:5px;border:1px solid #cccccc;padding:3px;">
							<option value="0">เลือกตำแหน่งในโครงการ</option>-->
							<select name="s_project_position_id" id="s_project_position_id" placeholder="เลือกตำแหน่งในโครงการ..." style="width:200px;border-radius:5px;border:1px solid #cccccc;padding:3px;">
							<option value="">เลือกตำแหน่งในโครงการ...</option>
							<? while ($row_d = $dbquery_d->fetch_assoc()) { ?>
								<option value="<? echo $row_d['id']; ?>" <? if($c_project_position_id==$row_d['id']) echo "selected";?>><? echo $row_d['ep_name']; ?></option>
							<? } //while ?>
						</select>
					<?}?>
				</div>
			  </div>
			  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding:3px;">
				<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5 text-right">สัดส่วนในโครงการ&nbsp;:&nbsp;</div>
				<div class="col-lg-7 col-md-7 col-sm-7 col-xs-7"><input type="text" name="s_ratio" id="s_ratio" maxlength="3" class="form-control" style="max-width:200px;" value="<? echo $c_ratio; ?>"></div>
			  </div>

			  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">&nbsp;</div>

		      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
					<input type="hidden" name="c_id" value="<? if(isset($c_id)){ echo $c_id;}else{ echo '';}?>">
					<input type="hidden" name="doc_id" value="<? if(isset($doc_id)){ echo $doc_id;}else{ echo '';}?>">
   					<input type="hidden" name="chk_edit" value="1"> 
					<input type="submit" name="Submit" value=" บันทึก " class="btn btn-warning" style="width:100px;font-size:18px;">&nbsp;
			  </div>

			  <div class="ccol-lg-12 col-md-12 col-sm-12 col-xs-12">&nbsp;</div>

		  </form>

		</div>

	</div><!-- /.row -->

	<div class="row">
		  
		  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" style="background-color:#<?echo __EC_BGSHOW__;?>;color:#<?echo __EC_FONTSHOW__;?>;border-radius: 5px 5px 0px 0px;"><h4>แสดงข้อมูลรายชื่อนักวิจัย</h4><a name="top_page"></a></div>
			
		  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="table-responsive">
			<table class="table table-striped">
              <thead>
                <tr style="background-color:#f7f7f7">
                  <th>&nbsp;</th>
				  <th>&nbsp;</th>
                  <th>ชื่อ-นามสกุล</a></th>
				  <th>ตำแหน่งในโครงการ</th>
				  <th>สัดส่วน</a></th>
                </tr>
              </thead>
              <tbody>

			 <? 

			$sql = "select * From `ers_document_researcher` where  (`document_id`='$doc_id') ";
			$sql .= "Order by `id` DESC";

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

					$bcolor = "#ffffff";
					if(($jk %2)==0){
						$bcolor = "rgba(236, 240, 241, 0.8)";
					}
					$code_1 = $c_name;

				?>

				<tr style="background-color:<?=$bcolor;?>">
				<td style="text-align:center;width:100px;min-width:100px;">
				<?
				echo "<a href='eresearcher.php?c_id=$c_id&doc_id=$doc_id' style='color:green;font-size:16px;' title='แก้ไข'><span class='glyphicon glyphicon-edit'></span>&nbsp;<span style='font-size:14px;'>แก้ไข</span></a>";
				?>
				</td>
				<td style="text-align:center;width:100px;min-width:100px;">
				<?
				echo "<span id=\"dfile1\"><a href='javascript:void(0)' onclick=\"javascript:confirmDelete('dfile1','".$c_id."','".$c_name."',".$doc_id.")\" style='color:red;font-size:16px;' title='ลบ'><span class='glyphicon glyphicon-trash'></span>&nbsp;<span style='font-size:14px;'>ลบ</span></a></span>";
				?>
				</td>
				<td><?=$c_name;?></td>
				<td><?=$c_position_name;?></td>
				<td><?=$c_ratio;?></td>
				</tr>

				<?
				}//while
			} //$totalRows 
			?>
			</tbody>
			</table>
			</div><!-- /.table-responsive -->
		</div><!-- /.col-lg-12 col-md-12 col-sm-12 col-xs-12 -->

	</div><!-- /.row -->

</div><!-- /.container -->

</body>
</html>
<? include("../include/close_db.php"); ?>
<script>
 $(document).ready(function () {
      $('select').selectize({
          sortField: 'text'
      });
  });
document.getElementById('s_researcher_id').focus();
</script>