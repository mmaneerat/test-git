<?php
global $sapdb;

$id = (isset($_GET['idcard']) && !empty($_GET['idcard'])) ? trim($_GET['idcard']) : "";
$pid = base64_decode($id);

$record_show = 2;

$page = (isset($_GET['page'])) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $record_show;


// ********************************* total page ******************************* //

    $sql_total = "SELECT *, n.uri as nuri, n.time_notify as tn ,t.idt_name as idt
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
    ORDER BY tadd ASC
                ";
    $total_query = $sapdb->get_results($sql_total, ARRAY_A);
    $total_row = $sapdb->num_rows;
    $total_page = ceil($total_row / $record_show);

// ****************************** Query data ******************************* //
    $sql = "SELECT *, n.uri as nuri, n.time_notify as tn ,t.idt_name as idt
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
    ORDER BY tadd ASC
                    ";
    $result = $sapdb->get_results($sql, ARRAY_A);
    $total = $sapdb->num_rows;

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
            $pid = $query["thai_idno"];
            $dataname = ($query['change_name']);
            $name = explode('|', $dataname);
            $name2 = explode(',', $name[1]);
            $ttime = strtotime($query['since']);
            $req_date = date('d', $ttime);
            $req_month = $submonths[date('m', $ttime)];
            $req_year = date('Y', $ttime);
            ?>
            <!-- Mobile version -->
            <div class="col-sm-5 d-block d-md-none">
                <div class="card shadow p-3 mb-4 bg-body-tertiary rounded">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-5 col-sm-10">
                                <h5>
                                    เปลี่ยนครั้งที่
                                    <?php echo $no++; ?>
                                </h5>
                            </div>
                            <div class="col-md-5">
                                <p>
                                    <span
                                        class="badge text-dark <?php echo "bg-{$status_bs_class[$query["id_status"]]}"; ?>">
                                        <?php echo $query["list_status"]; ?>
                                    </span>
                                </p>
                            </div>

                            <table class="table table-borderless border">
                                <thead class="table-light">
                                    <tr>
                                        <th>
                                            <h6>ชื่อ-สกุลเดิม :</h6>
                                        </th>
                                        <td>
                                            <h6>
                                                <?php echo "{$query["fname"]}"; ?>&nbsp;
                                                <?php echo "{$query["lname"]}"; ?>
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
                                                <?php echo (!empty($req_month) ? $req_month : "-"); ?>
                                                <?php echo (!empty($req_year) ? $req_year : "-"); ?>
                                            </h6>
                                        </td>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <a href="#" class="btn btn-primary text-center">Go somewhere</a>
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
                                    <?php echo $no++; ?>
                                </h5>
                            </div>
                            <div class="col-md-5">
                                <p>
                                    <span
                                        class="badge text-dark <?php echo "bg-{$status_bs_class[$query["id_status"]]}"; ?>">
                                        <?php echo $query["list_status"]; ?>
                                    </span>
                                </p>
                            </div>
                            <div class="col-md-5 text-end">
                                <p class="card-title">
                                    ชื่อ-สกุลเดิม :
                                </p>
                            </div>
                            <div class="col-md-5">
                                <p class="card-text">
                                    <?php echo "{$query["fname"]}"; ?>&nbsp;
                                    <?php echo "{$query["lname"]}"; ?>
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
                                        <?php echo "{$query["lname"]}"; ?>
                                    </p>
                                </div>
                            <?php } elseif ($query['change_opt'] == "ชื่อสกุล") { ?>
                                <div class="col-md-5">
                                    <p class="card-text">
                                        <?php echo "{$query["fname"]}"; ?>&nbsp;
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
                                    <?php echo "{$query["change_opt"]}"; ?>
                                </p>
                            </div>
                            <div class="col-md-5 text-end">
                                <p class="card-title">
                                    วันที่ขอเปลี่ยน :
                                </p>
                            </div>
                            <div class="col-md-5">
                                <p class="card-text">
                                    <?php echo "{$req_date}"; ?>
                                    <?php echo "{$req_month}"; ?>
                                    <?php echo "{$req_year}"; ?>
                                </p>
                            </div>
                        </div>
                        <a href="#" class="btn btn-primary text-center">Go somewhere</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Pagination -->
    <?php if ($total > 0): ?>
        <nav aria-label="Page-navigation mb-4">
            <ul class="pagination justify-content-center">
                <!--First-->
                <li class="page-item <?php echo ($total_row < $record_show) ? "disabled" : ""; ?>">
                    <a class='page-link' href="<?php echo "data.php?page=1&idcard={$pid}" ?>" tabindex='-1'
                        aria-disabled='true'>First</a>
                </li>
                <!-- แบ่งหน้า -->
                <?php for ($i = 1; $i <= $total_page; $i++): ?>
                    <?php if ($page <= 2): ?>
                        <?php if ($i <= 5): ?>
                            <li class="page-item <?php echo $page == $i ? 'active' : '' ?>">
                                <a class="page-link" href="<?php echo "data.php?page={$i}&idcard={$pid}" ?>">
                                    <?php echo "{$i}"; ?>
                                </a>
                            </li>
                        <?php endif; ?>
                    <?php elseif ($page > 2): ?>
                        <?php if ($i <= $page + 2 && $i >= $page - 2): ?>
                            <li class="page-item <?php echo $page == $i ? 'active' : '' ?>">
                                <a class="page-link" href="<?php echo "data.php?page={$i}&idcard={$pid}" ?>">
                                    <?php echo "{$i}"; ?>
                                </a>
                            </li>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endfor; ?>
                <!-- LAST -->
                <li class="page-item <?php echo ($total_row < $record_show || $page == $total_page) ? "disabled" : ""; ?>">
                    <a class='page-link' href='<?php echo "data.php?page={$total_page}&idcard={$pid}" ?>' tabindex='-1'
                        aria-disabled='true'>Last</a>
                </li>
            </ul>
        </nav>
    <?php endif; ?>
    <br><br>