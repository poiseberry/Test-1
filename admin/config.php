<?php
session_start();
date_default_timezone_set('Asia/Kuala_Lumpur');
error_reporting(0);

//database config
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'ecogreen_vk');
define('DB_PASSWORD', 'Dunksme@1');
define('DB_NAME', 'ecogreen_website2016');

$sql_details = array(
    'user' => DB_USERNAME,
    'pass' => DB_PASSWORD,
    'db' => DB_NAME,
    'host' => DB_HOST
);

$tablePrefix = 'dc_';

$table['profile'] = $tablePrefix . 'profile';
$table['aboutus'] = $tablePrefix . 'aboutus';
$table['company'] = $tablePrefix . 'company';
$table['media'] = $tablePrefix . 'media';
$table['banner'] = $tablePrefix . 'banner';
$table['category'] = $tablePrefix . 'category';
$table['contact'] = $tablePrefix . 'contact';
$table['gallery'] = $tablePrefix . 'gallery';
$table['news'] = $tablePrefix . 'news';
$table['time'] = $tablePrefix . 'time';
$table['tracking'] = $tablePrefix . 'tracking';
$table['user'] = $tablePrefix . 'user';
$table['user_logs'] = $tablePrefix . 'user_logs';

//site config
$admin_site_title = "Admin - EcoGreen";
$be_path = "/beta/admin/";
$fe_path = "/beta/";
$site_error_url = "http://" . $_SERVER['HTTP_HOST'] . $fe_path . "404.php";
$site_http_url = "http://" . $_SERVER['HTTP_HOST'] . $fe_path;

$site_admin_email = 'vincentlee@decubic.com';
//$site_admin_email = 'rookie@decubic.com';

//include extra file
require_once('include/database/class.database.php');
require_once('include/PHPMailer-master/PHPMailerAutoload.php');
require_once('include/qrcode.php');
require_once('include/function.php');
require_once('include/language/en.php');

//function
function is_login()
{
    global $be_path;
    if (empty($_SESSION['user_id']) || empty($_SESSION['user_username'])) {
        echo '<script>window.location.href="http://' . $_SERVER['HTTP_HOST'] . $be_path . '"</script>';
        exit();
    }
}

function detect_language()
{
    $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
    switch ($lang) {
        case "zh":
            setcookie('language', 'zh', time() + 36000, '/', '.ecogreenholding.com');
            $_COOKIE['language'] = 'zh';
            require_once("include/language/zh.php");
            break;
        case "en":
            setcookie('language', 'en', time() + 36000, '/', '.ecogreenholding.com');
            $_COOKIE['language'] = 'en';
            require_once("include/language/en.php");
            break;
        default:
            setcookie('language', 'en', time() + 36000, '/', '.ecogreenholding.com');
            $_COOKIE['language'] = 'en';
            require_once("include/language/en.php");
            break;
    }
}

if (preg_match("/admin/", $_SERVER['PHP_SELF'])) {
    if (!preg_match("/login/", $_SERVER['PHP_SELF']) && !preg_match("/logout/", $_SERVER['PHP_SELF']) && !preg_match("/index/", $_SERVER['PHP_SELF'])) {
        is_login();
    }
}

if ($_GET['lang'] != "") {
    setcookie('language', $_GET['lang'], time() + 36000, '/', '.ecogreenholding.com');
    $_COOKIE['language'] = $_GET['lang'];
    require_once("include/language/" . $_GET['lang'] . ".php");
} else {
    if ($_COOKIE['language'] == "") {
        detect_language();
    } else {
        require_once("include/language/" . $_COOKIE['language'] . ".php");
    }
}

//misc config
$today = date("Y-m-d H:i:s");

$green = "style=\"color:#5cb85c\"";
$red = "style=\"color:#c9302c\"";
$blue = "style=\"color:#428bca\"";
$yellow = "style=\"color:#f0ad4e\"";

$active = "<td $green><b>Active</b></td>";
$inactive = "<td $red><b>Inactive</b></td>";

$return = $_GET['return'];
$type = $_GET['type'];

//session
$user_username = $_SESSION['user_username'];
$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['user_role'];
?>