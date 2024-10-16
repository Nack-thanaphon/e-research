<?php
if ($_SESSION["userlevel"] >= "2" )
{
	?>
	<Script language="javascript">
		alert('ไม่สามารถใช้งานในส่วนนี้ได้ กรุณาติดต่อผู้ดูแลระบบ');
		/*window.location="index.php";*/
	</script>
	<?php
	if(isset($_SERVER['HTTP_REFERER'])){
		$url = $_SERVER['HTTP_REFERER'];
		//if(defined("_web_path")){ $url=_web_path.$url;}
	} else {$url="index.php";}
	//echo $_SERVER['HTTP_REFERER']."<br>";
	//echo _web_path;
	echo "<meta http-equiv='refresh' content='0;URL=".$url."'>";
	die();exit();
}
?>