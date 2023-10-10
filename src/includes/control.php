<?php
include 'functions.php';
/**
 * Controls 
 */
global $sapdb;

debug_forminfo();

$sso_user = (!empty($_SESSION['ws16user'])) ? $_SESSION['ws16user'] : null;

$id = (isset($_POST['idcard']) && !empty($_POST['idcard'])) ? trim($_POST['idcard']) : "";
$id_encode = base64_encode($id);

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

        case 'id':

            $sql = $sapdb->prepare(
                "SELECT idt_name FROM t_name WHERE thai_idno = '%s'",
                $id
            );
            $response = $sapdb->get_results($sql);
            $total = $sapdb->num_rows;

            if(!empty($total)) {
                header("Location: ../data.php?idcard=$id_encode");
                break;
            }else{
                header("Location: ../index.php?alert=error");
                break;
            }

        default:
            header("Location: ../index.php");
            die();
    }
}