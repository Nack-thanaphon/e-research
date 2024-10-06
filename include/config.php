<?

function sslHttp() {
	$httpURL = 'http';
	if( isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] != 'off') ) {$httpURL .= "s";}
	$httpURL .= "://";
	return $httpURL;
}
$_https = sslHttp();
$wRoot = $_https.$_SERVER["SERVER_NAME"]."/e-research";
$sRoot = $_SERVER["DOCUMENT_ROOT"]."/e-research";
define('_web_path', $wRoot);
define('_home_path', $sRoot);
define('SECRET_KEY', 'buu_@_e-research');
?>