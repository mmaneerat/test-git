<?php
global $sapdb;

$pid = (isset($_GET['idcard']) && !empty($_GET['idcard'])) ? trim($_GET['idcard']) : "";
$id = base64_decode($pid);

$record_show = 2;

$page = (isset($_GET['page'])) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $record_show;


// ********************************* total page ******************************* //

$sql_total = "SELECT *
    FROM t_name as t  
    LEFT JOIN `case` as c 
        ON t.idt_case = c.idt_case
    LEFT JOIN `rank` as r 
        ON r.idrank = t.idcf001o
    LEFT JOIN `notify` as n
        ON n.time = t.time
    LEFT JOIN status as s
        ON s.id_status = t.id_status
    WHERE thai_idno = '{$id}'
    AND t.id_status NOT IN (0,5)
    AND idt_name in 
            (select max(idt_name) 
            from t_name 
            group by thai_idno,change_name,change_opt,position,moi_register,since,idcf001o)
    ORDER BY tadd ASC
                ";
$total_query = $sapdb->get_results($sql_total, ARRAY_A);
$total_row = $sapdb->num_rows;
$total_page = ceil($total_row / $record_show);

// echo "<pre>";
// var_dump($total_row);
// var_dump($total_query);
// echo "</pre>";
// ****************************** Query data ******************************* //
$sql = "SELECT *
    FROM t_name as t  
    LEFT JOIN `case` as c 
        ON t.idt_case = c.idt_case
    LEFT JOIN `rank` as r 
        ON r.idrank = t.idcf001o
    LEFT JOIN `notify` as n
        ON n.time = t.time
    LEFT JOIN status as s
        ON s.id_status = t.id_status
    WHERE thai_idno = '{$id}'
    AND t.id_status NOT IN (0,5)
     AND idt_name in 
            (select max(idt_name) 
            from t_name 
            group by thai_idno,change_name,change_opt,position,moi_register,since,idcf001o)
    ORDER BY tadd ASC
                    ";
$result = $sapdb->get_results($sql, ARRAY_A);
$total = $sapdb->num_rows;

// Document


if (isset($page) == '') {
    $no = (($page) * $record_show) + 1;
} else {
    $no = (($page - 1) * $record_show) + 1;
}
?>
<!-- Page content -->
<div class="content">
    <div class="row">
        <h6 class="text-dark">เลขบัตรประจำตัวประชาชน :
            <?php echo "{$id}"; ?>
        </h6>
        <h6 class="text-dark mb-4">จำนวนการเปลี่ยนชื่อตัว-ชื่อสกุล
            <?php echo "$total"; ?> ครั้ง ดังนี้
        </h6>
        <?php if ($total > 0): ?>
            <?php foreach ($result as $query):
                $dataname = ($query['change_name']);
                $name = explode('|', $dataname);
                $name2 = explode(',', $name[1]);
                $ttime = strtotime($query['since']);
                $req_date = date('d', $ttime);
                $req_month = date('m', $ttime);
                $req_year = date('Y', $ttime);
                $url_decode = urldecode($query["uri"]);

                $textcolor = "";
                switch (intval($query["id_status"])) {
                    case 1:
                        $textcolor = "text-dark";
                        break;
                    case 2:
                        $textcolor = "text-white";
                        break;
                    case 3:
                        $textcolor = "text-dark";
                        break;
                    case 4:
                        $textcolor = "text-white";
                        break;
                }
                ?>
                <!-- Mobile version -->
                <div class="col-sm-5 d-block d-md-none">
                    <div class="card shadow p-3 mb-4 bg-body-tertiary rounded">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-5 col-sm-10">
                                    <h5>
                                        เปลี่ยนครั้งที่
                                        <?php echo $no; ?>
                                    </h5>
                                </div>
                                <div class="col-md-5">
                                    <p>
                                        <span
                                            class="badge <?php echo $textcolor; ?> <?php echo "bg-{$status_bs_class[$query["id_status"]]}"; ?>">
                                            <?php echo $query["list_status"]; ?>
                                        </span>
                                    </p>
                                </div>

                                <table class="table table-borderless border">
                                    <thead class="table-light">
                                        <tr>
                                            <th>
                                                <h6>คำนำหน้า :</h6>
                                            </th>
                                            <td>
                                                <h6>
                                                    <?php echo (!empty($query["Srank"])) ? $query["Srank"] : "-"; ?>
                                                </h6>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>
                                                <h6>ชื่อ-สกุลเดิม :</h6>
                                            </th>
                                            <td>
                                                <h6>
                                                    <?php echo (!empty($query["fname"])) ? $query["fname"] : "-"; ?>&nbsp;
                                                    <?php echo (!empty($query["lname"])) ? $query["lname"] : "-"; ?>
                                                </h6>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>
                                                <h6>ชื่อ-สกุลใหม่ :</h6>
                                            </th>
                                            <td>
                                                <?php if ($query['change_opt'] == "ชื่อตัว") { ?>
                                                    <div class="col-md-5">
                                                        <h6>
                                                            <?php echo "{$name[1]}"; ?>&nbsp;
                                                            <?php echo "{$query["lname"]}"; ?>
                                                        </h6>
                                                    </div>
                                                <?php } elseif ($query['change_opt'] == "ชื่อสกุล") { ?>
                                                    <div class="col-md-5">
                                                        <h6>
                                                            <?php echo "{$query["fname"]}"; ?>&nbsp;
                                                            <?php echo "{$name[1]}"; ?>
                                                        </h6>
                                                    </div>
                                                <?php } else {
                                                    ($query['change_opt'] == "ชื่อและสกุล") ?>
                                                    <div class="col-md-5">
                                                        <h6>
                                                            <?php echo "{$name2[0]}"; ?>&nbsp;
                                                            <?php echo "{$name[2]}"; ?>
                                                        </h6>
                                                    </div>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>
                                                <h6>สาเหตุการขอเปลี่ยน :</h6>
                                            </th>
                                            <td>
                                                <h6>
                                                    <?php echo (!empty($query["list_case"])) ? $query["list_case"] : "-"; ?>
                                                </h6>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">
                                                <h6>ประเภทขอเปลี่ยน : </h6>
                                            </th>
                                            <td>
                                                <h6>
                                                    <?php echo (!empty($query["change_opt"]) ? $query["change_opt"] : "-"); ?>
                                                </h6>
                                            </td>
                                        </tr>
                                    </thead>
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="row">
                                                <h6>วันที่ขอเปลี่ยน : </h6>
                                            </th>
                                            <td>
                                                <h6>
                                                    <?php echo (!empty($req_date) ? intval($req_date) : "-"); ?>
                                                    <?php echo (!empty($submonths["$req_month"]) ? $submonths["$req_month"] : "-"); ?>
                                                    <?php echo (!empty($req_year) ? $req_year : "-"); ?>
                                                </h6>
                                            </td>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                            <div class="text-end">
                                <a href="<?php echo $url_decode; ?>" class="btn btn-primary">รายละเอียดเพิ่มเติม</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Desktop version -->
                <div class="col-sm-5 d-none d-md-block">
                    <div class="card shadow p-3 mb-4 bg-body-tertiary rounded">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-5 col-sm-10">
                                    <h5>
                                        เปลี่ยนครั้งที่
                                        <?php echo $no; ?>
                                    </h5>
                                </div>
                                <div class="col-md-5">
                                    <p>
                                        <span
                                            class="badge <?php echo $textcolor; ?> <?php echo "bg-{$status_bs_class[$query["id_status"]]}"; ?>">
                                            <?php echo (!empty($query["list_status"])) ? $query["list_status"] : "-"; ?>
                                        </span>
                                    </p>
                                </div>
                                <div class="col-md-5 text-end">
                                    <p class="card-title">
                                        คำนำหน้า :
                                    </p>
                                </div>
                                <div class="col-md-5">
                                    <p class="card-text">
                                        <?php echo (!empty($query["Srank"])) ? $query["Srank"] : "-"; ?>
                                    </p>
                                </div>
                                <div class="col-md-5 text-end">
                                    <p class="card-title">
                                        ชื่อ-สกุลเดิม :
                                    </p>
                                </div>
                                <div class="col-md-5">
                                    <p class="card-text">
                                        <?php echo (!empty($query["fname"])) ? $query["fname"] : "-"; ?>&nbsp;
                                        <?php echo (!empty($query["lname"])) ? $query["lname"] : "-"; ?>
                                    </p>
                                </div>
                                <div class="col-md-5 text-end">
                                    <p class="card-title">
                                        ชื่อ-สกุลใหม่ :
                                    </p>
                                </div>
                                <?php if ($query['change_opt'] == "ชื่อตัว") { ?>
                                    <div class="col-md-5">
                                        <p class="card-text">
                                            <?php echo "{$name[1]}"; ?>&nbsp;
                                            <?php echo (!empty($query["lname"])) ? $query["lname"] : "-"; ?>
                                        </p>
                                    </div>
                                <?php } elseif ($query['change_opt'] == "ชื่อสกุล") { ?>
                                    <div class="col-md-5">
                                        <p class="card-text">
                                            <?php echo (!empty($query["fname"])) ? $query["fname"] : "-"; ?>&nbsp;
                                            <?php echo "{$name[1]}"; ?>
                                        </p>
                                    </div>
                                <?php } else {
                                    ($query['change_opt'] == "ชื่อและสกุล") ?>
                                    <div class="col-md-5">
                                        <p class="card-text">
                                            <?php echo "{$name2[0]}"; ?>&nbsp;
                                            <?php echo "{$name[2]}"; ?>
                                        </p>
                                    </div>
                                <?php } ?>
                                <div class="col-md-5 text-end">
                                    <p class="card-title">
                                        ประเภทขอเปลี่ยน :
                                    </p>
                                </div>
                                <div class="col-md-5 ">
                                    <p class="card-text">
                                        <?php echo (!empty($query["change_opt"])) ? $query["change_opt"] : "-"; ?>
                                    </p>
                                </div>
                                <div class="col-md-5 text-end">
                                    <p class="card-title">
                                        สาเหตุการขอเปลี่ยน :
                                    </p>
                                </div>
                                <div class="col-md-5 ">
                                    <p class="card-text">
                                        <?php echo (!empty($query["list_case"])) ? $query["list_case"] : "-"; ?>
                                    </p>
                                </div>
                                <div class="col-md-5 text-end">
                                    <p class="card-title">
                                        วันที่ขอเปลี่ยน :
                                    </p>
                                </div>
                                <div class="col-md-5">
                                    <p class="card-text">
                                        <?php echo (!empty($req_date) ? intval($req_date) : "-"); ?>
                                        <?php echo (!empty($submonths["$req_month"]) ? $submonths["$req_month"] : "-"); ?>
                                        <?php echo (!empty($req_year) ? $req_year : "-"); ?>
                                    </p>
                                </div>
                                <?php if ($query["id_status"] == 4) { ?>
                                    <div class="col-md-5 text-end">
                                        <p class="card-title">
                                        </p>
                                    </div>
                                    <div class="col-md-5">
                                        <a href="<?php echo $url_decode; ?>" class="btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#modalShowNotify<?php echo $query["id_notify"] ?>">รายละเอียดเพิ่มเติม</a>
                                    </div>
                                <?php } else { ?>
                                    <p class="card-title mb-4"></p>
                                <?php } ?>
                            </div>
                            <!-- modal -->
                            <div class="modal fade" id="modalShowNotify<?php echo $query["id_notify"] ?>" tabindex="-1"
                                aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-keyboard="true"
                                data-bs-backdrop="static">
                                <div class="modal-dialog modal-lg modal-fullscreen">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title text-dark" id="exampleModalLabel">ประกาศวันที่
                                                <?php echo intval($req_date); ?>
                                                เดือน
                                                <?php echo "$months_th[$req_month]"; ?>
                                                ปี
                                                <?php echo "{$req_year}"; ?>
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body bg-dark p-0">
                                            <iframe src="<?php echo $url_decode; ?>" width="100%" height="100%"></iframe>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php $no++; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Pagination -->
<?php if ($total > 0): ?>
    <nav aria-label="Page-navigation">
        <ul class="pagination justify-content-center mt-4">
            <!--First-->
            <li class="page-item <?php echo ($total_row < $record_show) ? "disabled" : ""; ?>">
                <a class='page-link' href='<?php echo "$_SERVER[SCRIPT_NAME]?page=1&idcard={$pid}" ?>' tabindex='-1'
                    aria-disabled='true'>First</a>
            </li>
            <!-- แบ่งหน้า -->
            <?php for ($i = 1; $i <= $total_page; $i++): ?>
                <?php if ($page <= 2): ?>
                    <?php if ($i <= 5): ?>
                        <li class="page-item <?php echo $page == $i ? 'active' : '' ?>">
                            <a class="page-link" href="<?php echo "$_SERVER[SCRIPT_NAME]?page={$i}&idcard={$pid}" ?>">
                                <?php echo "{$i}"; ?>
                            </a>
                        </li>
                    <?php endif; ?>
                <?php elseif ($page > 2): ?>
                    <?php if ($i <= $page + 2 && $i >= $page - 2): ?>
                        <li class="page-item <?php echo $page == $i ? 'active' : '' ?>">
                            <a class="page-link" href="<?php echo "$_SERVER[SCRIPT_NAME]?page={$i}&idcard={$pid}" ?>">
                                <?php echo "{$i}"; ?>
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endfor; ?>
            <!-- LAST -->
            <li class="page-item <?php echo ($total_row < $record_show || $page == $total_page) ? "disabled" : ""; ?>">
                <a class='page-link' href='<?php echo "$_SERVER[SCRIPT_NAME]?page={$total_page}&idcard={$pid}" ?>'
                    tabindex='-1' aria-disabled='true'>Last</a>
            </li>
        </ul>
    </nav>
<?php endif; ?>
<br><br>