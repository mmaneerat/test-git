<?php
global $sapdb;

?>
        
        <!-- Masthead-->
        <!-- <header class="masthead"> -->
            <div class="container position-relative">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="text-center text-dark">
                            <!-- Page heading-->
                            <h2 class="mb-5">ค้นหาประวัติการขอเปลี่ยนชื่อตัว-ชื่อสกุล</h2>
                            <form class="form-subscribe" name="frmAdd" action="./data.php" method="GET" onSubmit="return checkForm(this)">
                                <!-- Email address input-->
                                <div class="row">
                                    <div class="col">
                                        <input class="form-control form-control-lg" id="pid" name="idcard" type="number" placeholder="เลขประจำตัวประชาชน" pattern="(0-9){13}"
                                    onKeyPress="if(this.value.length==13) return false;" autocomplete="off" required/>
                                    <p class="text-danger" id="error13"></p>
                                    </div>
                                    <div class="col-auto">
                                        <button class="btn btn-primary btn-lg" id="Button" type="submit" disabled>
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

    <div class="d-block d-md-none">
        <blockquote class="blockquote text-center">
            <h6 class="text-muted mt-2">
                หากมีข้อสงสัย โทร.ทบ. <a href="tel:022978101" rel="noopener">022978101</a> โทร 98101 หรือ 97598
            </h6>
            <h6 class="text-muted">แผนกประวัติประจำการ กองประวัติและบำเหน็จบำนาญ กรมสารบรรณทหารบก</h6>
        </blockquote>
    </div>
</div>
<footer class="d-none d-md-block fixed-bottom">
    <div class="bg-light">
        <blockquote class="blockquote text-center">
            <h6 class="text-muted mt-2">
                หากมีข้อสงสัย โทร.ทบ. <a href="tel:022978101" rel="noopener">022978101</a> โทร 98101 หรือ 97598
            </h6>
            <h6 class="text-muted">แผนกประวัติประจำการ กองประวัติและบำเหน็จบำนาญ กรมสารบรรณทหารบก</h6>
        </blockquote>
    </div>
</footer>