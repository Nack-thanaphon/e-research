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
            <a href="./" title=""><img src="<?=_web_path;?>/images/<?php if(defined('__EC_LOGO__')){echo __EC_LOGO__;}?>"  alt="ระบบคลังข้อมูลงานวิจัย"   style="padding-left:5px;padding-right:5px;max-height:50px"></a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <?php if(!isset($_SESSION["memberid"])) { ?>
                    <?php if($_menu_id == 1) { ?>
                        <li class="active"><a href="<?=_web_path;?>/" style="background:#dddcdc;font-size:16px;color:#ff0000;">&nbsp;<span class="glyphicon glyphicon-home" style="color:#00ccff;"></span>&nbsp;&nbsp;</a></li>
                    <?php } else { ?>
                        <li><a href="<?=_web_path;?>/" style="font-size:16px;">&nbsp;<span class="glyphicon glyphicon-home" style="color:#ffce00;"></span>&nbsp;&nbsp;</a></li>
                    <?php } ?>
                <?php } else { ?>
                    <?php if($_menu_id == 1) { ?>
                        <li class="active"><a href="<?=_web_path;?>/member/" style="background:#dddcdc;font-size:16px;color:#ff0000;">&nbsp;<span class="glyphicon glyphicon-home" style="color:#00ccff;"></span>&nbsp;หน้าหลัก&nbsp;</a></li>
                    <?php } else { ?>
                        <li><a href="<?=_web_path;?>/member/" style="font-size:16px;">&nbsp;<span class="glyphicon glyphicon-home" style="color:#ffce00;"></span>&nbsp;หน้าหลัก&nbsp;</a></li>
                    <?php } ?>
                <?php } ?>
                <?php if($_menu_id == 2) { ?>
                    <li class="active"><a href="<?=_web_path;?>/" style="background:#dddcdc;color:#ff0000;font-size:16px;">&nbsp;<span class="glyphicon glyphicon-search" style="color:#000000;"></span>&nbsp;ผลงานวิจัย&nbsp;&nbsp;</a></li>
                <?php } else { ?>
                    <li><a href="<?=_web_path;?>" style="font-size:16px;">&nbsp;<span class="glyphicon glyphicon-search" style="color:#000000;"></span>&nbsp;ผลงานวิจัย&nbsp;&nbsp;</a></li>
                <?php } ?>
                <?php if($_menu_id == 3) { ?>
                    <li class="active"><a href="<?=_web_path;?>//ers-researcherpb.php" style="background:#dddcdc;color:#ff0000;font-size:16px;">&nbsp;<span class="glyphicon glyphicon-user" style="color:#000000;"></span>&nbsp;นักวิจัย&nbsp;&nbsp;</a></li>
                <?php } else { ?>
                    <li><a href="<?=_web_path;?>//ers-researcherpb.php" style="font-size:16px;">&nbsp;<span class="glyphicon glyphicon-user" style="color:#000000;"></span>&nbsp;นักวิจัย&nbsp;&nbsp;</a></li>
                <?php } ?>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <?php if(!isset($_SESSION["memberid"])) { ?>
                    <?php if($_menu_id == 5) { ?>
                        <li class="active"><a href="<?=_web_path;?>/member/" style="background:#dddcdc;color:#ff0000;font-size:16px;">&nbsp;<span class="glyphicon glyphicon-save-file" style="color:#ff0000;"></span>&nbsp;ลงทะเบียน&nbsp;</a></li>
                    <?php } else { ?>
                        <li><a href="<?=_web_path;?>/member/" style="font-size:16px;color:#ff0000;">&nbsp;<span class="glyphicon glyphicon-save-file" style="color:#ff0000;"></span>&nbsp;ลงทะเบียน&nbsp;</a></li>
                    <?php } ?>
                <?php } else { ?>
                    <?php if($_menu_id == 5) { ?>
                        <li class="active"><a href="<?=_web_path;?>/member/logout.php" style="background:#ffce00;color:#ff0000;font-size:16px;">&nbsp;<span class="glyphicon glyphicon-ok-sign" style="color:#00cc00;"></span>&nbsp;ออกจากระบบ&nbsp;<font color="#000000">[</font><?=$_SESSION["username"];?><font color="#000000">]</font>&nbsp;</a></li>
                    <?php } else { ?>
                        <li><a href="<?=_web_path;?>/member/logout.php" style="font-size:16px;color:#ff0000;">&nbsp;<span class="glyphicon glyphicon-ok-sign" style="color:#00cc00;"></span>&nbsp;ออกจากระบบ&nbsp;<font color="#000000">[</font><?=$_SESSION["username"];?><font color="#000000">]</font>&nbsp;</a></li>
                    <?php } ?>
                <?php } ?>
            </ul>
        </div><!--/.nav-collapse -->
    </div><!--/.container-fluid -->
</nav>
<script>
var sw = screen.width;
if(sw < 768) {
    document.getElementById("fmnavbar").className = "navbar navbar-default navbar-fixed-top";
    /*document.getElementById("headernd_mobile").style.display = "";*/
}
$(document).ready(function() {
    $('ul.nav li.dropdown').hover(function() {
        $(this).find('.dropdown-menu').stop(true, true).delay(200).fadeIn(200);
    }, function() {
        $(this).find('.dropdown-menu').stop(true, true).delay(200).fadeOut(200);
    });
});
$(window).resize(function() { 
    var windowWidth = $(window).width();
    if(windowWidth < 768) {
        document.getElementById("fmnavbar").className = "navbar navbar-default navbar-fixed-top";
        /*document.getElementById("headernd_mobile").style.display = "";*/
    } else {
        document.getElementById("fmnavbar").className = "navbar navbar-default";
        /*document.getElementById("headernd_mobile").style.display = "none";*/
    }
});
</script>