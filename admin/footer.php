<footer id="footer">
  <div class="footer-social">
  <?php
  if(strpos($_SERVER["PHP_SELF"],"index")){
  if(isset($_SESSION['admin'])){
  ?>
	<div style="text-align:center;">&nbsp;<a href="<?= _web_path;?>/files/<?php if(defined('__EC_MANUAL_ADMIN__')){echo __EC_MANUAL_ADMIN__;}?>" title="" target="_blank"><font class="ftdownload"><?php if(defined('__EC_MANUAL_ADMIN__')){echo "ดาวน์โหลดคู่มือการใช้งานสำหรับผู้ดูแลระบบ";}?></font></a>&nbsp;</div>
  <?php}
  if(isset($_SESSION['username'])){
  ?>
	<div style="text-align:center;">&nbsp;<a href="<?= _web_path;?>/files/<?php if(defined('__EC_MANUAL_MEMBER__')){echo __EC_MANUAL_MEMBER__;}?>" title="" target="_blank"><font class="ftdownload"><?php if(defined('__EC_MANUAL_MEMBER__')){echo "ดาวน์โหลดคู่มือการใช้งานสำหรับสมาชิก";}?></font></a>&nbsp;</div>
  <?php} else {?>
	<div style="text-align:center;">&nbsp;<a href="<?= _web_path;?>/files/<?php if(defined('__EC_MANUAL_MEMBER__')){echo __EC_MANUAL_MEMBER__;}?>" title="" target="_blank"><font class="ftdownload"><?php if(defined('__EC_MANUAL_MEMBER__')){echo "ดาวน์โหลดคู่มือการใช้งานสำหรับสมาชิก";}?></font></a>&nbsp;</div>
  <?php}}?>
  </div>
  <div class="text-center" style="margin-top:20px;margin-bottom:30px;"><?php if(defined('__EC_COPYRIGHT__')){ echo __EC_COPYRIGHT__;}?></div>
</footer>
<?php include("../include/close_db.php"); ?>