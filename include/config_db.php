<?php
$hostname = "localhost";
$user = 'aree';
$passwd = 'Ak@072039';
$dbname = "buu_chanthaburi";

// Enable error reporting for mysqli
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$mysqli = new mysqli($hostname, $user, $passwd, $dbname);
$link = $mysqli->set_charset('utf8');

$sql = "SELECT * FROM `ers_configs` WHERE `id` = ?";
$stmt = $mysqli->prepare($sql);
$id = 1;
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$tRows = $result->num_rows;

if ($tRows > 0) {
    $row = $result->fetch_assoc();
    define('__EC_NAME__', $row["ec_name"]);
    define('__EC_FAVICON_ICO__', $row["ec_favicon_ico"]);
    define('__EC_FAVICON__', $row["ec_favicon"]);
    define('__EC_LOGO__', $row["ec_logo"]);
    define('__EC_PICHOME__', $row["ec_pichome"]);
    define('__EC_MANUAL_ADMIN__', $row["ec_manual_admin"]);
    define('__EC_MANUAL_MEMBER__', $row["ec_manual_member"]);
    define('__EC_MANUAL_GUEST__', $row["ec_manual_guest"]);
    define('__EC_COPYRIGHT__', $row["ec_copyright"]);
    define('__EC_BGSHOW__', !empty($row["ec_bgshow"]) ? $row["ec_bgshow"] : 'cc0066');
    define('__EC_FONTSHOW__', !empty($row["ec_fontshow"]) ? $row["ec_fontshow"] : 'ffffff');
}

$result->free();
$stmt->close();
$mysqli->close();
