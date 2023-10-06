<?php

include 'includes/functions.php';
$navmenu = [
    [
        "url" => "./index.php",
        "name" => "ตรวจสอบสถานะ",
    ],
    [
        "url" => "./search_notify.php",
        "icon" => "fas fa-search",
        "name" => "ค้นหาประกาศ",
    ],
    [
        "url" => "../guidebook/handbook_user.pdf",
        "name" => "คู่มือใช้งาน",
    ],
    [
        "url" => "./components/loginadd.php",
        "name" => "ออกจากระบบ",
    ]
];

if (true) {
    if (!empty($_SESSION["ws16group"]) && $_SESSION["ws16group"] > 0) {
        $navmenu = [
            $navmenu[0],
            [
                "url" => "./admin.php",
                "icon" => "fas fa-user",
                "name" => "Admin",
            ],
            $navmenu[1],
            $navmenu[2],
            $navmenu[3]
        ];
    }
}

$currentURI = implode("", array_slice(explode("/", $_SERVER["REQUEST_URI"]), -1, 1));
$currentURI = (strpos($currentURI, '?', 0) > 0) ? substr($currentURI, 0, strpos($currentURI, '?', 0)) : $currentURI;


$data = [
    "data.php"
];
$value = in_array($currentURI, $data) ? true : false;
?>

<!-- Navigation-->
<nav class="navbar navbar-dark bg-primary shadow-sm p-3 mb-5">
    <div class="container">
        <a class="navbar-brand" href="#!">
            ค้นหาประวัติเปลี่ยนชื่อตัว-ชื่อสกุล
        </a>
        <?php if ($value == true) { ?>
        <a class="btn btn-outline-light" href="index.php">
            ย้อนกลับ
        </a>
        <?php } ?>
    </div>
</nav>
</div>