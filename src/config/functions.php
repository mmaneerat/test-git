<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', '1');
date_default_timezone_set("Asia/Bangkok");

function debug_forminfo()
{
    echo "<pre>";
    var_dump($_POST);
    var_dump($_GET);
    var_dump($_SESSION);
    echo "</pre><hr/>";
}

function define_dbconnect()
{
    define('DB_USER', "<%= DB_USER %>");
    define('DB_PASSWORD', "<%= DB_PASSWORD %>");
    define('DB_NAME', "<%= DB_NAME %>");
    define('DB_SSO', "<%= DB_SSO %>");
    define('DB_HOST', "<%= DB_HOST %>");
}

function create_dbconnect($dbhost = DB_HOST)
{
    global $sapdb;
    if (isset($sapdb)) {
        return;
    }

    require_once 'class-sap-db.php';

    $dbuser = defined('DB_USER') ? DB_USER : '';
    $dbpassword = defined('DB_PASSWORD') ? DB_PASSWORD : '';
    $dbname = defined('DB_NAME') ? DB_NAME : '';
    // $dbhost = defined('DB_HOST') ? DB_HOST : '';

    $sapdb = new sapdb($dbuser, $dbpassword, $dbname, $dbhost);
}


function load_model($class_name)
{
  $path_to_file = "../models/" . $class_name . ".php";
  if (file_exists($path_to_file)) {
    require $path_to_file;
  }
}

spl_autoload_register("load_model");

$months_th =
    [
        "01" => "มกราคม",
        "02" => "กุมภาพันธ์",
        "03" => "มีนาคม",
        "04" => "เมษายน",
        "05" => "พฤษภาคม",
        "06" => "มิถุนายน",
        "07" => "กรกฎาคม",
        "08" => "สิงหาคม",
        "09" => "กันยายน",
        "10" => "ตุลาคม",
        "11" => "พฤศจิกายน",
        "12" => "ธันวาคม"
    ];

$submonths =
    [
        "01" => "ม.ค.",
        "02" => "ก.พ.",
        "03" => "มี.ค.",
        "04" => "เม.ษ.",
        "05" => "พ.ค.",
        "06" => "มิ.ย.",
        "07" => "ก.ค.",
        "08" => "ส.ค.",
        "09" => "ก.ย.",
        "10" => "ต.ค.",
        "11" => "พ.ย.",
        "12" => "ธ.ค."
    ];


$typename = [
  1 => "สัญญาบัตร",
  2 => "ต่ำกว่าสัญญาบัตร"
];

$filetype = [
  1 => "rn-forest-green", 
  2 => "warning"
];

$notify = [
  1 => 'ประกาศเปลี่ยนชื่อตัว-ชื่อสกุล', 
  2 => 'คำสั่งเปลี่ยนชื่อตัว-ชื่อสกุล'
];

$status_bs_class = [
  0 => "danger",
  1 => "warning",
  2 => "rn-neon-blue",
  3 => "rn-green-light",
  4 => "success",
  5 => "danger"
];


// @NOTE Bypass Session
$_SESSION["ws16user"] = "1429900280771";
// $_SESSION["ws16user"] = "";
// $_SESSION["ws16group"] = "testws16group";
$_SESSION["ws16group"] = "1";
// $_SESSION["ws16group"] = "1";
// $_SESSION["ws16nameg"] = "testws16nameg";
define_dbconnect();
create_dbconnect();

?>