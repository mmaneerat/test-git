<?php
global $sapdb;

$alert = (isset($_GET['alert']) && !empty($_GET['alert'])) ? trim($_GET['alert']) : "";
?>

<!-- Mobile version -->
<!-- <header class="masthead"> -->
<div class="container position-relative d-block d-md-none">
    <div class="row justify-content-center">
        <div class="col-md-12 col-lg-12">
            <div class="text-center text-dark">
                <!-- Page heading-->
                <form class="form-subscribe needs-validation" name="frmAdd" action="./includes/control.php" method="POST"
                novalidate>
                    <!-- Idcard input-->
                    <div class="row mt-5 pt-5">
                        <div class="col-12">
                            <input class="form-control" id="pid" name="idcard" type="number"
                                placeholder="กรอกเลขประจำตัวประชาชน" pattern="(0-9){13}"
                                onKeyPress="if(this.value.length==13) return false;" autocomplete="off" required />
                                <div class="invalid-feedback">
                                    กรุณากรอกเลขบัตรประชาชน
                                </div>
                        </div>
                        <div class="col">
                            <input type="hidden" name="action" value="id">
                            <button class="btn btn-primary text-center mt-4 mb-4" id="Button" type="submit">
                                ค้นหา
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- </header> -->

<!-- Desktop version -->
<header class="masthead">
    <div class="container position-relative d-none d-md-block">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="text-center text-dark">
                    <!-- Page heading-->
                    <h2 class="mb-5">ค้นหาประวัติการขอเปลี่ยนชื่อตัว-ชื่อสกุล</h2>
                    <form class="form-subscribe needs-validation" name="frmAdd" action="./includes/control.php"
                        method="POST" novalidate>
                        <!-- idcard input-->
                        <div class="row justify-content-center">
                            <div class="col-md-6">
                                <input class="form-control form-control-lg" id="pid" name="idcard" type="number"
                                    placeholder="เลขประจำตัวประชาชน" pattern="(0-9){13}"
                                    onKeyPress="if(this.value.length==13) return false;" autocomplete="off" required />
                                <div class="invalid-feedback">
                                    กรุณากรอกเลขบัตรประชาชน
                                </div>
                            </div>
                            <div class="col-auto">
                                <input type="hidden" name="action" value="id">
                                <button class="btn btn-primary btn-lg text-center mb-4" type="submit">
                                    ค้นหา
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
<input type="hidden" id="alert" value="<?php echo $alert;?>">

<div class="d-block d-md-none">
    <blockquote class="blockquote text-center">
        <h6 class="text-muted mt-2" style="font-size: 14px;">
            หากมีข้อสงสัย โทร.ทบ. <a href="tel:022978101" rel="noopener">022978101</a> โทร 98101 หรือ 97598
        </h6>
        <h6 class="text-muted" style="font-size: 14px;">แผนกประวัติประจำการ กองประวัติและบำเหน็จบำนาญ กรมสารบรรณทหารบก
        </h6>
    </blockquote>
</div>
</div>
<footer class="d-none d-md-block fixed-bottom">
    <div class="bg-light">
        <blockquote class="blockquote text-center">
            <h6 class="text-muted mt-2">
                หากมีข้อสงสัย โทร.ทบ. <a href="tel:022978101" rel="noopener">022978101</a> โทร 98101 หรือ 97598
                แผนกประวัติประจำการ กองประวัติและบำเหน็จบำนาญ กรมสารบรรณทหารบก
            </h6>
            <h6 class="text-muted">- Powered by maneerat -</h6>
        </blockquote>
    </div>
</footer>
