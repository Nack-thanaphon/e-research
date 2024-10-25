<?php

// function sslHttp()
// {
// 	$httpURL = 'http';
// 	if (isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] != 'off')) {
// 		$httpURL .= "s";
// 	}
// 	$httpURL .= "://";
// 	return $httpURL;
// }
// $_https = sslHttp();
$wRoot = $_SERVER["SERVER_NAME"] . "/~scia/e-research";
$sRoot = $_SERVER["DOCUMENT_ROOT"] . "/e-research";
define('_web_path', '/~scia/e-research');
define('_home_path', '/~scia/e-research');
define('SECRET_KEY', 'buu_@_e-research');

function dateThai_edo($strDate) {
    $strYear = date("Y",strtotime($strDate))+543;
    $strMonth= date("n",strtotime($strDate));
    $strDay= date("j",strtotime($strDate));
    $strMonthCut = Array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
    $strMonthThai=$strMonthCut[$strMonth];
    return "$strDay $strMonthThai $strYear";
}