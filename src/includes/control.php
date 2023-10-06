<?php
include 'functions.php';
/**
 * Controls 
 */
global $sapdb;

debug_forminfo();

$sso_user = (!empty($_SESSION['ws16user'])) ? $_SESSION['ws16user'] : null;

$idt_name = (isset($_POST['id']) && !empty($_POST['id'])) ? trim($_POST['id']) : "";
$fname = (isset($_POST['fname']) && !empty($_POST['fname'])) ? trim($_POST['fname']) : "";
$lname = (isset($_POST['lname']) && !empty($_POST['lname'])) ? trim($_POST['lname']) : "";
$url_encode = urlencode($url);

if (isset($_POST) && !empty($_POST)) {
    $formAction = $_POST['action'];
    $tm = time();
    $time = date("Y-m-d H:i:s");

    // query & filter setup
    $fwd = [
        'p' => (isset($_GET['p']) && !empty($_GET['p'])) ? trim($_GET['p']) : null,
        'q' => (isset($_GET['q']) && !empty($_GET['q'])) ? trim($_GET['q']) : null
    ];
    $fwd_uri = http_build_query($fwd, null, '&');

    echo "<a href='../index.php?{$fwd_uri}'>[index] back for debug usage</a><br/>";
    echo "<a href='../index.php?{$fwd_uri}'>[ordersstock] back for debug usage</a><br/>";
    echo "<hr/><br/>";

    // $_POST filter


    switch ($formAction) {
       
        // user/front/view/add_data/add_name.php
        case 'editdata_user':

            $sql = $sapdb->prepare(
                "SELECT uri FROM filename_case WHERE idt_name = '$idt_name'"
            );
            $response = $sapdb->get_results($sql, ARRAY_A);
            $total = $sapdb->num_rows;

            header("Location: ../pages_alert.php?act=eu");
            break;

        default:
            header("Location: ../index.php");
            die();
    }
}