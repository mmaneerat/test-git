<?php
include 'functions.php';
/**
 * ajax 
 */
global $sapdb;

$timeid = (isset($_POST['timeid']) && !empty($_POST['timeid'])) ? trim($_POST['timeid']) : "";
$rtaid = (isset($_POST['rtaid']) && !empty($_POST['rtaid'])) ? trim($_POST['rtaid']) : "";

$sql = "SELECT * from t_name as t
INNER JOIN rank as r ON r.idrank = t.idcf001o 
WHERE time='{$timeid}' 
AND id_type='{$rtaid}'
AND id_status = '3'
AND t_no = '2'
GROUP BY fname,lname,thai_idno
ORDER BY r.idrank ASC";
$result = $sapdb->get_results($sql);
$count = $sapdb->num_rows;
$no = 1;

$response = "";

foreach ($result as $row) {
    
    $printrow = "<tr>";
    $printrow .= "<td class=\"text-center\">{$no}</td>";
    $printrow .= "<td>{$row->Srank} {$row->fname} {$row->lname}</td>";
    $printrow .= (empty($row->unit)) ? "<td> </td>" : "<td>{$row->unit}</td>";
    $printrow .= "</tr>";
    $no++; //ลำดับเลข
    $response .= $printrow;
}
// $response .= "</table></div>";
echo json_encode(["tbody" => $response, "count" => $count]);