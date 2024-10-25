<?php
$hostname = "localhost";
$user = 'aree';
$passwd = '';
$dbname = "buu_chanthaburi";

$mysqli = new mysqli($hostname, $user, $passwd, $dbname);
if ($mysqli->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}
$mysqli->set_charset('utf8');

$sql = "select * from `ers_configs` where (`id`='1')";
$dbquery = $mysqli->query($sql);
$tRows = $dbquery->num_rows;
if ($tRows > 0) {
    $row = $dbquery->fetch_assoc();
    define('__EC_NAME__', $row["ec_name"]);
    define('__EC_FAVICON_ICO__', $row["ec_favicon_ico"]);
    define('__EC_FAVICON__', $row["ec_favicon"]);
    define('__EC_LOGO__', $row["ec_logo"]);
    define('__EC_PICHOME__', $row["ec_pichome"]);
    define('__EC_MANUAL_ADMIN__', $row["ec_manual_admin"]);
    define('__EC_MANUAL_MEMBER__', $row["ec_manual_member"]);
    define('__EC_MANUAL_GUEST__', $row["ec_manual_guest"]);
    define('__EC_COPYRIGHT__', $row["ec_copyright"]);
    if (!empty($row["ec_bgshow"])) {
        define('__EC_BGSHOW__', $row["ec_bgshow"]);
    } else {
        define('__EC_BGSHOW__', 'cc0066');
    }
    if (!empty($row["ec_fontshow"])) {
        define('__EC_FONTSHOW__', $row["ec_fontshow"]);
    } else {
        define('__EC_FONTSHOW__', 'ffffff');
    }
}
$dbquery->free();
