<?php
global $sapdb;

$sql_case = "SELECT * FROM `case`";
$queryc = $sapdb->get_results($sql_case, ARRAY_A);
$sql_rank = "SELECT * FROM `rank`";
$queryr = $sapdb->get_results($sql_rank, ARRAY_A);

//ดึงข้อมูลจากฐานข้อมูลบัตรมาช่วยกรอก
if (isset($_GET["id13"])) {
    $id = base64_decode($_GET["id13"]);
}

$sql = "SELECT a.*
FROM agdeptrt_adjgenius.dbsb AS a 
LEFT JOIN db_namechange_test.t_name AS t 
ON a.pid = t.dbsb_Id
WHERE a.pid = '{$id}'";
$query = $sapdb->get_results($sql, ARRAY_A);
?>

<!-- Page content -->
<form action="<?php echo "./includes/control.php"; ?>" name="frmAdd" method="post" autocomplete="off"
    enctype="multipart/form-data"><br>
    <div class="content">
        <div class="container-fluid"><br>
            <!-- Main component for a primary marketing message or call to action -->
            <?php foreach ($query as $result)
                ; ?>
            <div class="jum2">
                <h2 class="text-rn-midnight-blue"><i class="fas fa-pen-nib"></i> ยื่นคำขอข้อมูลขอเปลี่ยนชื่อตัว-ชื่อสกุล
                </h2><br>
                <hr>
                <div class="container text-dark ">
                    <fieldset class="border p-4 bg-light">
                        <legend class="text-danger"><i class="fas fa-user-edit"></i> ข้อมูลส่วนบุคคล</legend><br><br>
                        <div class="row mb-4">
                            <!-- Admins -->
                            <?php if (
                                $_SESSION["ws16group"] == "1"
                            ) { ?>
                                <div class="col-md-5">
                                    <div class="form-outline">
                                        <strong class="form-label">ชื่อ</strong>
                                        <input type="text" class="form-control" placeholder="ชื่อ" value="<?php echo isset(
                                            $result["firstName"]
                                            )
                                            ? $result["firstName"]
                                            : ""; ?>" name="fname" id="ofname" />
                                    </div>
                                </div>
                                <div class="col-1"></div>
                                <div class="col-md-5">
                                    <div class="form-outline">
                                        <strong class="form-label">นามสกุล</strong>
                                        <input type="text" class="form-control" placeholder="นามสกุล" value="<?php echo isset(
                                            $result["lastName"]
                                            )
                                            ? $result["lastName"]
                                            : ""; ?>" name="lname" id="olname" />
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-md-5">
                                    <div class="form-outline">
                                        <strong class="form-label">เลขบัตรประชาชน</strong>
                                        <input type="number" class="form-control" placeholder="เลขบัตรประชาชน 13 หลัก"
                                            value="<?php echo $id; ?>" name="thai_idno" pattern="(0-9){13}"
                                            onKeyPress="if(this.value.length==13) return false;" readonly />
                                    </div>
                                </div>
                                <div class="col-1"></div>

                                <div class="col-md-5">
                                    <div class="form-outline">
                                        <strong for="validationCustom01">เบอร์โทรติดต่อ</strong>
                                        <input type="number" class="form-control" placeholder="เบอร์โทรติดต่อ" value=""
                                            name="tel" pattern="(0-9){10}"
                                            onKeyPress="if(this.value.length==10) return false; ">
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-md-5">
                                    <div class="form-outline">
                                        <strong class="form-label">หน่วยงาน/สังกัด</strong>
                                        <input type="text" class="form-control" placeholder="หน่วยงาน/สังกัด" value="<?php echo isset(
                                            $result["unit"]
                                            )
                                            ? $result["unit"]
                                            : ""; ?>" name="unit" />
                                    </div>
                                </div>
                                <div class="col-1"></div>
                                <div class="col-md-5">
                                    <div class="form-outline">
                                        <strong class="form-label">ตำแหน่ง</strong>
                                        <input input type="text" class="form-control" placeholder="ตำแหน่ง" value="<?php echo isset(
                                            $result["position"]
                                            )
                                            ? $result["position"]
                                            : ""; ?>" name="position" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-outline">
                                        <strong class="form-label">เลขประจำตัวข้าราชการ</strong>
                                        <input input type="number" class="form-control"
                                            placeholder="เลขประจำตัวทหาร 10 หลัก" value="<?php echo "{$result["mid"]}"; ?>"
                                            name="rta_id" pattern="(0-9){10}"
                                            onKeyPress="if(this.value.length==10) return false;" />
                                    </div>
                                    <div class="row mb-2"></div>
                                    <a data-bs-toggle="modal" data-bs-target="#exampleModal">
                                        <h6 class="text-dark btn btn-warning btn-sm"><i
                                                class="far fa-question-circle text-dark"></i> ตัวอย่างเลขประจำตัวข้าราชการ
                                        </h6>
                                    </a>
                                    <!-- Modal -->
                                    <div class="modal fade" id="exampleModal" tabindex="-1"
                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title text-danger" id="exampleModalLabel">
                                                        ตัวอย่างเลขประจำตัวข้าราชการ</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body text-center">
                                                    <p>เลขที่หน้าบัตรประจำตัวข้าราชการ</p>
                                                    <img src="../img/id.jpg" width="280px" ;
                                                        alt="เลขที่ด้านหลังบัตรประจำตัว ทบ."><br>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-1"></div>
                                <div class="col-md-5 ">
                                    <strong for="validationCustom02">คำนำหน้า/ยศ</strong>
                                    <div class="form-outline">
                                        <div>
                                            <select class=" form-select mb-4" aria-label="Default select example" id="rank"
                                                name="rank" required>
                                                <option value="">เลือกยศ</option>
                                                <?php foreach ($queryr as $rank): ?>
                                                    <option value="<?php echo "{$rank["idrank"]}"; ?>"><?php echo "{$rank["Frank"]}"; ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                        </fieldset><br>
                        <fieldset class="border p-4 bg-light ">
                            <legend class="text-danger"><i class="fas fa-id-card-alt"></i> ข้อมูลเปลี่ยนชื่อ-สกุล</legend>
                            <div class="alert warning-alert align-items-center alert-dismissible fade show" id="htext"
                                role="alert">
                                <i class="fas fa-exclamation-triangle"
                                    style="font-size:25px"></i>&nbsp;ยื่นกรณีที่มีการขอเปลี่ยนชื่อตัว-ชื่อสกุล
                                ลงวันที่เดียวกัน
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-outline"><br>
                                        <strong for="validationCustom01">ประเภทคำขอ</strong>
                                        <div>
                                            <select class="form-select" aria-label="Default select example"
                                                name="change_opt" id="change_opt" required>
                                                <option value="">เลือกประเภทคำขอ</option>
                                                <option value="ชื่อตัว">ชื่อตัว</option>
                                                <option value="ชื่อกลาง">ชื่อกลาง</option>
                                                <option value="ชื่อสกุล">ชื่อสกุล</option>
                                                <option value="ชื่อและสกุล">ชื่อและสกุล</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-1"></div>
                                <div class="col-md-5">
                                    <div class="form-outline"><br>
                                        <strong for="validationCustom01">สาเหตุการยื่นคำขอ</strong>
                                        <div>
                                            <select class=" form-select mb-4" aria-label="Default select example"
                                                name="case" required>
                                                <option value="">เลือกสาเหตุการยื่นคำขอ</option>
                                                <?php foreach ($queryc as $case): ?>
                                                    <option value="<?php echo "{$case["idt_case"]}"; ?>"><?php echo "{$case["list_case"]}"; ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-md-5">
                                        <div class="form-outline" id="onlyname1">
                                            <strong class="form-label" for="validationCustom01">ข้อมูลเดิม</strong>
                                            <input type="text" class="form-control mt-2" placeholder="ข้อมูลเดิม" value=""
                                                id="name1" name="name1" required />
                                        </div>
                                        <div class="row" name="fullname" id="fullname1">
                                            <strong class="form-label" for="validationCustom01">ข้อมูลเดิม</strong>
                                            <div class="input-level mb-2">
                                                <input type="text" class="form-control mb-2" placeholder="ชื่อตัวเดิม"
                                                    value="" id="cfname1" name="cfname1" required />
                                                <input type="text" class="form-control" placeholder="ชื่อสกุลเดิม" value=""
                                                    id="clname1" name="clname1" required />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-1">
                                        <div class="arrow">
                                            <i class="fas fa-long-arrow-alt-right"></i>
                                            <div id="arrows">
                                                <i class="fas fa-long-arrow-alt-right"></i>
                                            </div>
                                        </div>
                                    </div>
                                    &nbsp;&nbsp;
                                    <div class="col-md-5">
                                        <div class="form-outline" id="onlyname2">
                                            <strong class="form-label" for="validationCustom01">ข้อมูลใหม่</strong>
                                            <input type="text" class="form-control mt-2" placeholder="ข้อมูลใหม่" value=""
                                                id="name2" name="name2" required />
                                        </div>
                                        <div class="row" name="fullname" id="fullname2">
                                            <strong class="form-label" for="validationCustom01">ข้อมูลใหม่</strong>
                                            <div class="input-level mb-2">
                                                <input type="text" class="form-control mb-2" placeholder="ชื่อตัว" value=""
                                                    id="cfname2" name="cfname2" required />
                                                <input type="text" class="form-control" placeholder="ชื่อสกุล" value=""
                                                    id="clname2" name="clname2" required />
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-5">
                                        <div class="form-outline">
                                            <strong for="validationCustom01">เลขที่ทะเบียน</strong>
                                            <input type="text" class="form-control" placeholder="เลขที่ทะเบียน" value=""
                                                name="moi_register">
                                        </div>
                                        <div class="row mb-2"></div>
                                        <a data-bs-toggle="modal" data-bs-target="#exampleModal1">
                                            <h6 class="text-dark btn btn-warning btn-sm"><i
                                                    class="far fa-question-circle text-dark"></i> ตัวอย่างเลขที่ทะเบียน</h6>
                                        </a>
                                        <!-- Modal -->
                                        <div class="modal fade" id="exampleModal1" tabindex="-1"
                                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title text-danger" id="exampleModalLabel">
                                                            ตัวอย่างเลขที่ทะเบียน</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body text-center">
                                                        <p>เลขที่ทะเบียน คือ เลขที่ อยู่บนมุมซ้ายของหนังสือสำคัญเปลี่ยนฯ</p>
                                                        <img src="../img/rename.jpg" width="280px" ; alt="เลขที่ทะเบียน">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-1"></div>&nbsp;&nbsp;
                                    <div class="col-md-5">
                                        <div class="form-outline">
                                            <strong for="inputdatepicker">ตั้งแต่วันที่</strong>
                                            <input type="text" id="datepicker" name="since" placeholder=""
                                                class="datepicker form-control">
                                        </div>
                                    </div>
                                </div>
                        </fieldset><br>
                        <fieldset class="border p-4 bg-light" id="files">
                            <legend class="text-danger"><i class="fas fa-file-upload"></i> ยื่นเอกสารประกอบคำขอ</legend>
                            <div class="row mb-4">
                                <div class="col-md-8" id="files"><br>
                                    <strong for="validationCustom01">เลือกไฟล์ : ใบเปลี่ยนชื่อตัว-ชื่อสกุล</strong>
                                    <input type="file" name="filename_case[]" id="filename_case01" class="form-control"
                                        title="ไฟล์ 1 ขนาดไฟล์ไม่เกิน <?php echo ini_get(
                                            "upload_max_filesize"
                                        ); ?>" multiple accept="application/pdf,/*image"><br>
                                </div>
                            </div>
                    </div>
                    </fieldset><br>
                    <!-- user -->
                <?php } else { ?>
                    <div class="col-md-5">
                        <div class="form-outline">
                            <strong class="form-label">ชื่อ</strong>
                            <input type="text" class="form-control" placeholder="ชื่อ" value="<?php echo isset($result["firstName"])
                                ? $result["firstName"]
                                : ""; ?>" required name="fname" id="ofname" />
                        </div>
                    </div>
                    <div class="col-1"></div>
                    <div class="col-md-5">
                        <div class="form-outline">
                            <strong class="form-label">นามสกุล</strong>
                            <input type="text" class="form-control" placeholder="นามสกุล" value="<?php echo isset($result["lastName"])
                                ? $result["lastName"]
                                : ""; ?>" required name="lname" id="olname" />
                        </div>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-md-5">
                        <div class="form-outline">
                            <strong class="form-label">เลขบัตรประชาชน</strong>
                            <input type="number" class="form-control" placeholder="เลขบัตรประชาชน 13 หลัก"
                                value="<?php echo $id; ?>" required name="thai_idno" pattern="(0-9){13}"
                                onKeyPress="if(this.value.length==13) return false;" readonly />
                        </div>
                    </div>
                    <div class="col-1"></div>
                    <div class="col-md-5">
                        <div class="form-outline">
                            <strong for="validationCustom01">เบอร์โทรติดต่อ</strong>
                            <input type="number" class="form-control" placeholder="เบอร์โทรติดต่อ" value="" required
                                name="tel" pattern="(0-9){10}" onKeyPress="if(this.value.length==10) return false; ">
                        </div>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-md-5">
                        <div class="form-outline">
                            <strong class="form-label">หน่วยงาน/สังกัด</strong>
                            <input type="text" class="form-control" placeholder="หน่วยงาน/สังกัด" value="<?php echo isset($result["unit"])
                                ? $result["unit"]
                                : ""; ?>" required name="unit" />
                        </div>
                    </div>
                    <div class="col-1"></div>
                    <div class="col-md-5">
                        <div class="form-outline">
                            <strong class="form-label">ตำแหน่ง</strong>
                            <input input type="text" class="form-control" placeholder="ตำแหน่ง" value="<?php echo isset($result["position"])
                                ? $result["position"]
                                : ""; ?>" required name="position" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-outline">
                            <strong class="form-label">เลขประจำตัวข้าราชการ</strong>
                            <input input type="number" class="form-control" placeholder="เลขประจำตัวทหาร 10 หลัก"
                                value="<?php echo "{$result["mid"]}"; ?>" required name="rta_id" pattern="(0-9){10}"
                                onKeyPress="if(this.value.length==10) return false;" />
                        </div>
                        <div class="row mb-2"></div>
                        <a data-bs-toggle="modal" data-bs-target="#exampleModal">
                            <h6 class="text-dark btn btn-warning btn-sm"><i class="far fa-question-circle text-dark"></i>
                                ตัวอย่างเลขประจำตัวข้าราชการ</h6>
                        </a>
                        <!-- Modal -->
                        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title text-danger" id="exampleModalLabel">
                                            ตัวอย่างเลขประจำตัวข้าราชการ</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body text-center">
                                        <p>เลขที่หน้าบัตรประจำตัวข้าราชการ</p>
                                        <img src="../img/id.jpg" width="280px" ; alt="เลขที่ด้านหลังบัตรประจำตัว ทบ."><br>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-1"></div>
                    <div class="col-md-5 ">
                        <strong for="validationCustom02">คำนำหน้า/ยศ</strong>
                        <div class="form-outline">
                            <div>
                                <select class=" form-select mb-4" aria-label="Default select example" id="rank" name="rank"
                                    required>
                                    <option value="">เลือกยศ</option>
                                    <?php foreach ($queryr as $rank): ?>
                                        <option value="<?php echo "{$rank["idrank"]}"; ?>"><?php echo "{$rank["Frank"]}"; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    </fieldset><br>
                    <fieldset class="border p-4 bg-light ">
                        <legend class="text-danger"><i class="fas fa-id-card-alt"></i> ข้อมูลเปลี่ยนชื่อ-สกุล</legend>
                        <div class="alert warning-alert align-items-center alert-dismissible fade show" id="htext"
                            role="alert">
                            <i class="fas fa-exclamation-triangle"
                                style="font-size:25px"></i>&nbsp;ยื่นกรณีที่มีการขอเปลี่ยนชื่อตัว-ชื่อสกุล ลงวันที่เดียวกัน
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-outline"><br>
                                    <strong for="validationCustom01">ประเภทคำขอ</strong>
                                    <div>
                                        <select class="form-select" aria-label="Default select example" name="change_opt"
                                            id="change_opt" required>
                                            <option value="">เลือกประเภทคำขอ</option>
                                            <option value="ชื่อตัว">ชื่อตัว</option>
                                            <option value="ชื่อกลาง">ชื่อกลาง</option>
                                            <option value="ชื่อสกุล">ชื่อสกุล</option>
                                            <option value="ชื่อและสกุล">ชื่อและสกุล</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-1"></div>
                            <div class="col-md-5">
                                <div class="form-outline"><br>
                                    <strong for="validationCustom01">สาเหตุการยื่นคำขอ</strong>
                                    <div>
                                        <select class=" form-select mb-4" aria-label="Default select example" name="case"
                                            required>
                                            <option value="">เลือกสาเหตุการยื่นคำขอ</option>
                                            <?php foreach ($queryc as $case): ?>
                                                <option value="<?php echo "{$case["idt_case"]}"; ?>"><?php echo "{$case["list_case"]}"; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-md-5">
                                    <div class="form-outline" id="onlyname1">
                                        <strong class="form-label" for="validationCustom01">ข้อมูลเดิม</strong>
                                        <input type="text" class="form-control mt-2" placeholder="ข้อมูลเดิม" value=""
                                            id="name1" name="name1" required />
                                    </div>
                                    <div class="row" name="fullname" id="fullname1">
                                        <strong class="form-label" for="validationCustom01">ข้อมูลเดิม</strong>
                                        <div class="input-level mb-2">
                                            <input type="text" class="form-control mb-2" placeholder="ชื่อตัวเดิม" value=""
                                                id="cfname1" name="cfname1" required />
                                            <input type="text" class="form-control" placeholder="ชื่อสกุลเดิม" value=""
                                                id="clname1" name="clname1" required />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-1">
                                    <div class="arrow">
                                        <i class="fas fa-long-arrow-alt-right"></i>
                                        <div id="arrows">
                                            <i class="fas fa-long-arrow-alt-right"></i>
                                        </div>
                                    </div>
                                </div>
                                &nbsp;&nbsp;
                                <div class="col-md-5">
                                    <div class="form-outline" id="onlyname2">
                                        <strong class="form-label" for="validationCustom01">ข้อมูลใหม่</strong>
                                        <input type="text" class="form-control mt-2" placeholder="ข้อมูลใหม่" value=""
                                            id="name2" name="name2" required />
                                    </div>
                                    <div class="row" name="fullname" id="fullname2">
                                        <strong class="form-label" for="validationCustom01">ข้อมูลใหม่</strong>
                                        <div class="input-level mb-2">
                                            <input type="text" class="form-control mb-2" placeholder="ชื่อตัว" value=""
                                                id="cfname2" name="cfname2" required />
                                            <input type="text" class="form-control" placeholder="ชื่อสกุล" value=""
                                                id="clname2" name="clname2" required />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-5">
                                    <div class="form-outline">
                                        <strong for="validationCustom01">เลขที่ทะเบียน</strong>
                                        <input type="text" class="form-control" placeholder="เลขที่ทะเบียน" value=""
                                            required name="moi_register">
                                    </div>
                                    <div class="row mb-2"></div>
                                    <a data-bs-toggle="modal" data-bs-target="#exampleModal1">
                                        <h6 class="text-dark btn btn-warning btn-sm"><i
                                                class="far fa-question-circle text-dark"></i> ตัวอย่างเลขที่ทะเบียน</h6>
                                    </a>
                                    <!-- Modal -->
                                    <div class="modal fade" id="exampleModal1" tabindex="-1"
                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title text-danger" id="exampleModalLabel">
                                                        ตัวอย่างเลขที่ทะเบียน</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body text-center">
                                                    <p>เลขที่ทะเบียน คือ เลขที่ อยู่บนมุมซ้ายของหนังสือสำคัญเปลี่ยนฯ</p>
                                                    <img src="../img/rename.jpg" width="280px" ; alt="เลขที่ทะเบียน">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-1"></div>&nbsp;&nbsp;
                                <div class="col-md-5">
                                    <div class="form-outline">
                                        <strong for="inputdatepicker">ตั้งแต่วันที่</strong>
                                        <input type="text" id="datepicker" name="since" placeholder=""
                                            class="datepicker form-control" required>
                                    </div>
                                </div>
                            </div>
                    </fieldset><br>
                    <fieldset class="border p-4 bg-light" id="files">
                        <legend class="text-danger"><i class="fas fa-file-upload"></i> ยื่นเอกสารประกอบคำขอ</legend>
                        <div class="row mb-4">
                            <div class="col-md-8" id="files"><br>
                                <strong for="validationCustom01">เลือกไฟล์ : ใบเปลี่ยนชื่อตัว-ชื่อสกุล</strong>
                                <input type="file" name="filename_case[]" id="filename_case01" class="form-control" title="ไฟล์ 1 ขนาดไฟล์ไม่เกิน <?php echo ini_get(
                                    "upload_max_filesize"
                                ); ?>" multiple accept="application/pdf,/*image" required><br>
                            </div>
                        </div>
                </div>
                </fieldset><br>
            <?php } ?>
            <input type="hidden" name="ip" value="<?php echo "{$_SERVER["REMOTE_ADDR"]}"; ?>" />
            <input type="hidden" name="uri" value="<?php echo "{$_SERVER["REQUEST_URI"]}"; ?>" />
            <input type="hidden" name="status" value="1">

            <div class="text-center mx-auto mb-4">
                <a href="index.php" class="btn btn-lg btn-danger">กลับหน้าแรก</a>
                <input type="hidden" name="action" value="add_name">
                <input type="submit" class="btn btn-lg btn-success" name="submit" value="บันทึก">
            </div><br>
        </div>
    </div>
</form>