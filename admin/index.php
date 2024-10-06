<?php
session_start();
Header("Content-Type: text/html; charset=UTF-8");
require_once "../include/config.php";
if (!defined('_web_path')) {
	exit;
}
if (!isset($_SESSION["admin"]) || !isset($_SESSION["userlevel"]))
{
  echo "<Script language=\"javascript\">window.location=\"login.php\"</script>";
  exit;
}
$admin = $_SESSION['admin'];
$userlevel = $_SESSION['userlevel'];
require_once "../include/config_db.php";
if (!defined('__EC_NAME__')) {
	include("../include/close_db.php");
	echo "<meta http-equiv='refresh' content='0;URL=configs_edocument.php?iRegister=1'>";
	die();
}
 $_menu_id = 1;
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
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
</style>
</head>
<body role="document">

<a href="#0" class="cd-top">Top</a>

<!-- cd-top JS -->
<script src="../js/main.js"></script>
<?include("../include/config_db.php");?>

<div class="container-fluid" style="margin:0;padding:0;">
	<? require_once "./header.php"; ?>
</div>
<div class="container">

	<div class="row">
        <div class="col-sm-12 col-xs-12" style="text-align:center;padding-top:100px;">

			<div><img src="../images/<?if(defined('__EC_PICHOME__')){echo __EC_PICHOME__;}?>" class="img-thumbnail-noborder"></div>

		</div><!-- /.col-sm-12 col-xs-12 -->
	</div><!-- /.row -->

	<? require_once("footer.php") ?>

</div>
<?include("../include/close_db.php");?>
</body>
</html>
