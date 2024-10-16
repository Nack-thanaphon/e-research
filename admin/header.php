<?php
$sql = "select m.*,d.id from `ers_member_request` m JOIN `ers_member` d ON m.member_id=d.id where (`er_request_cancel`='0') and (`er_answer`='0') and (`document_id`>'0') and (d.id > '0')";
$dbquery = $mysqli->query($link,$sql) or die("Can't send query!");
$tRows_n = $dbquery->num_rows;
$sql = "select m.*,d.id from `ers_member_request_nodoc` m JOIN `ers_member` d ON m.member_id=d.id where (`er_request_cancel`='0') and (`er_answer`='0') and (d.id > '0')";
$dbquery = $mysqli->query($link,$sql) or die("Can't send query!");
$tRows_nodoc = $dbquery->num_rows;
$tRows_n2 = $tRows_n;
if($tRows_nodoc > 0){
	$tRows_n2 += $tRows_nodoc;
}
?>
<div id="headernd_mobile" style="padding-top:50px;display:none">&nbsp;</div>
 <nav class="navbar navbar-default" id="fmnavbar">
        <div class="container-fluid">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a href="./" title=""><img src="../images/<?php if(defined('__EC_LOGO__')){echo __EC_LOGO__;}?>"  alt="ระบบคลังข้อมูลงานวิจัย"   style="padding-left:5px;padding-right:5px;max-height:50px"></a>
          </div>
          <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
              <?php if($_menu_id==1){?>
					<li class="active"><a href="./" style="background:#dddcdc;font-size:16px;"><font style="color:#ff0000;font-size:16px;">&nbsp;<span class="glyphicon glyphicon-home" style="color:#00ccff;"></span>&nbsp;Home&nbsp;</a></font></li>
				<?php}else{?>
					<li><a href="./" style="font-size:16px;">&nbsp;<span class="glyphicon glyphicon-home" style="color:#ffce00;"></span>&nbsp;Home&nbsp;</a></li>
				<?php}?>
				<li class="dropdown">
					<?php if($_menu_id==2){?>
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" style="background:#dddcdc;"><font style="color:#ff0000;font-size:16px;">&nbsp;<span class="glyphicon glyphicon-th" style="color:#003399;"></span>&nbsp;บันทึกข้อมูล&nbsp;&nbsp;<span class="caret"></span></font></a>
					<?php}else{?>
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" style="font-size:16px;">&nbsp;<span class="glyphicon glyphicon-th" style="color:#003399;"></span>&nbsp;บันทึกข้อมูล&nbsp;<span class="caret"></span></a>
					<?php}?>
					<ul class="dropdown-menu">	
						<li><a href="section.php?iRegister=1" style="font-size:16px;">&nbsp;<span class="glyphicon glyphicon-plus" style="color:#9900cc;"></span>&nbsp;&nbsp;<span style="color:inherit;">ภาควิชา/ฝ่าย</span></a></li>
						<li><a href="faculty.php?iRegister=1" style="font-size:16px;">&nbsp;<span class="glyphicon glyphicon-plus" style="color:#ff0000;"></span>&nbsp;&nbsp;<span style="color:inherit;">ส่วนงาน/คณะ</span></a></li>
						<li><a href="resposition.php?iRegister=1" style="font-size:16px;">&nbsp;<span class="glyphicon glyphicon-plus" style="color:#ff9933;"></span>&nbsp;&nbsp;<span style="color:inherit;">สถานภาพของนักวิจัย</span></a></li>
						<li><a href="acaposition.php?iRegister=1" style="font-size:16px;">&nbsp;<span class="glyphicon glyphicon-plus" style="color:#00cc00;"></span>&nbsp;&nbsp;<span style="color:inherit;">ตำแหน่งทางวิชาการ</span></a></li>
						<li><a href="proposition.php?iRegister=1" style="font-size:16px;">&nbsp;<span class="glyphicon glyphicon-plus" style="color:#009900;"></span>&nbsp;&nbsp;<span style="color:inherit;">ตำแหน่งในโครงการ</span></a></li>
						<li><a href="research_type.php?iRegister=1" style="font-size:16px;">&nbsp;<span class="glyphicon glyphicon-plus" style="color:#003300;"></span>&nbsp;&nbsp;<span style="color:inherit;">ประเภทของเงินอุดหนุนงานวิจัย</span></a></li>
						<li class="divider"></li>
						<li style="background-color:#ffffcc;"><a href="researcher.php?iRegister=1" style="font-size:16px;">&nbsp;<span class="glyphicon glyphicon-user" style="color:#cc9900;"></span>&nbsp;&nbsp;<span style="color:inherit;">บันทึกข้อมูลนักวิจัย</span></a></li>
						<li style="background-color:#ffffcc;"><a href="eresearch.php?iRegister=1" style="font-size:16px;">&nbsp;<span class="glyphicon glyphicon-bullhorn" style="color:#000099;"></span>&nbsp;&nbsp;<span style="color:inherit;">บันทึกข้อมูลผลงานวิจัย</span></a></li>
						<li style="background-color:#ffffcc;"><a href="members_approve.php?iRegister=1" style="font-size:16px;">&nbsp;<span class="glyphicon glyphicon-user" style="color:#ff66cc;"></span>&nbsp;อนุมัติการร้องขอเอกสารผลงานวิจัย(ในระบบ)</a></li>
						<li style="background-color:#ffffcc;"><a href="members_nodoc.php?iRegister=1" style="font-size:16px;">&nbsp;<span class="glyphicon glyphicon-user" style="color:#ff9900;"></span>&nbsp;ตอบกลับการร้องของานวิจัย(นอกระบบ)</a></li>
						<li class="divider"></li>
					</ul>
				</li>
				<li class="dropdown">
					<?php if($_menu_id==3){?>
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" style="background:#dddcdc;"><font style="color:#ff0000;font-size:16px;">&nbsp;<span class="glyphicon glyphicon-search" style="color:#ff0099;"></span>&nbsp;ค้นหา&nbsp;<span class="caret"></span></font></a>
					<?php}else{?>
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" style="font-size:16px;">&nbsp;<span class="glyphicon glyphicon-search" style="color:#ff0099;"></span>&nbsp;ค้นหา&nbsp;<span class="caret"></span></a>
					<?php}?>
					<ul class="dropdown-menu">						
						<li><a href="ers-document.php?iRegister=1" style="font-size:16px;">&nbsp;<span class="glyphicon glyphicon-list" style="color:#cc0066;"></span>&nbsp;ผลงานวิจัย</a></li>
						<li><a href="ers-researcher.php?iRegister=1" style="font-size:16px;">&nbsp;<span class="glyphicon glyphicon-user" style="color:#ff3399;"></span>&nbsp;นักวิจัย</a></li>
						<li><a href="members.php?iRegister=1" style="font-size:16px;">&nbsp;<span class="glyphicon glyphicon-user" style="color:#ff66cc;"></span>&nbsp;<span style="color:<?php if($userlevel >= 2){echo "#cccccc";}else{echo "inherit";}?>">สมาชิกที่ลงทะเบียน</span></a></li>
						<li class="divider"></li>
					</ul>
				</li>
            </ul>
            <ul class="nav navbar-nav navbar-right" style="margin-right:10px;">
              <li>
					<?php if($tRows_n>0){?><a href="members_approve.php?iRegister=1" title="ขอเอกสารที่รอตรวจสอบ ในระบบ <?= number_format($tRows_n);?> รายการ <?php if($tRows_nodoc>0){?> นอกระบบ <?= number_format($tRows_nodoc);?> รายการ<?php}?>"><span class="blink" style="border: 1px solid #ff0099;border-radius:50%;padding:1px;color:#ff0099;">&nbsp;<?= number_format($tRows_n2);?>&nbsp;</span></a><?php} else {?>
					<?php if($tRows_nodoc>0){?><a href="members_nodoc.php?iRegister=1" title="ขอเอกสารที่รอตรวจสอบ นอกระบบ <?= number_format($tRows_nodoc);?> รายการ"><span class="blink" style="border: 1px solid #ff0099;border-radius:50%;padding:1px;color:#ff0099;">&nbsp;<?= number_format($tRows_nodoc);?>&nbsp;</span></a><?php}}?>
			  </li>
			  <li class="dropdown">
					<?php if($_menu_id==5){?>
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" style="background:#dddcdc;"><font style="color:#ff0000;font-size:16px;"><span class="glyphicon glyphicon-wrench" style="color:#000000;"></span>&nbsp;ควบคุมระบบ&nbsp;<span class="caret"></span></font></a>
					<?php}else{?>
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><font style="font-size:16px;"><span class="glyphicon glyphicon-wrench" style="color:#000000;"></span>&nbsp;ควบคุมระบบ&nbsp;<span class="caret"></span></font></a>
					<?php}?>
					<ul class="dropdown-menu">	
						<li><a href="configs_eresearch.php?iRegister=1" style="font-size:16px;">&nbsp;<span class="glyphicon glyphicon-cog" style="color:<?php if($admin != 'admin'){echo "#cccccc";}else{echo "#000000";}?>;"></span>&nbsp;<span style="color:<?php if($admin != 'admin'){echo "#cccccc";}else{echo "inherit";}?>">ตั้งค่าระบบ</span>&nbsp;</a></li>		
						<?php
						if($admin != 'admin'){
							$sql = "select id,user_name from ers_admin where (user_name='".$admin."') ";
							$dbquery_admin = $mysqli->query($link,$sql);
							$num_rows_admin = $dbquery_admin->num_rows;
							$code_admin = $admin;
							if($num_rows_admin > 0) {
								$row_admin = $dbquery_admin->fetch_assoc();
								$code_id = $row_admin["id"];
								?>
								<li><a href="edit_user.php?code_id=<?= $code_id;?>&code_1=<?= $code_admin;?>" style="font-size:16px;">&nbsp;<span class="glyphicon glyphicon-lock" style="color:#ff9900;"></span>&nbsp;ผู้ดูแลระบบ</a></li>
								<?php
								$dbquery_admin->close();
							}
						} else {
						?>
							<li><a href="users.php?iRegister=1" style="font-size:16px;">&nbsp;<span class="glyphicon glyphicon-lock" style="color:#ff9900;"></span>&nbsp;<span style="color:<?php if($userlevel >= 2){echo "#cccccc";}else{echo "inherit";}?>">ผู้ดูแลระบบ</span></a></li>
						<?php}?>
						<li><a href="logout.php" style="font-size:16px;">&nbsp;<span class="glyphicon glyphicon-off" style="color:#ff0066;"></span>&nbsp;ออกจากระบบ&nbsp;[<?php if(isset($_SESSION['admin'])){echo $_SESSION['admin'];}?>]</a></li>
						 <li class="divider"></li>
					</ul>
				</li>
            </ul>
          </div><!--/.nav-collapse -->
        </div><!--/.container-fluid -->
 </nav>
 <script>
var sw = screen.width;
if(sw < 768)
{
	document.getElementById("fmnavbar").className = "navbar navbar-default navbar-fixed-top";
	/*document.getElementById("headernd_mobile").style.display = "";*/
}
$(document).ready(function(){
    $('ul.nav li.dropdown').hover(function() {
      $(this).find('.dropdown-menu').stop(true, true).delay(200).fadeIn(200);
    }, function() {
      $(this).find('.dropdown-menu').stop(true, true).delay(200).fadeOut(200);
    });
});
$(window).resize(function() { 
	var windowWidth = $(window).width();
	if(windowWidth < 768){
		document.getElementById("fmnavbar").className = "navbar navbar-default navbar-fixed-top";
		/*document.getElementById("headernd_mobile").style.display = "";*/
	}
	else {
		document.getElementById("fmnavbar").className = "navbar navbar-default";
		/*document.getElementById("headernd_mobile").style.display = "none";*/
	}
});
</script>