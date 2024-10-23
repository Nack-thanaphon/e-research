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
// $wRoot = $_https . $_SERVER["SERVER_NAME"] . "/~scia/e-research";
// $sRoot = $_SERVER["DOCUMENT_ROOT"] . "/e-research";
define('_web_path', $wRoot, true);
define('_home_path', $sRoot, true);
define('SECRET_KEY', 'buu_@_e-research', true);
