import Swal from "sweetalert2";

jQuery(function ($) {
  const alert = $("#alert").val();

  if (alert == "error") {
    Swal.fire({
        title: "ไม่พบข้อมูล!",
        text: "Something went wrong!",
        icon: "warning",
        confirmButtonText: "ตกลง",
        confirmButtonColor: "#3399ff",
        timer: 8000,
        timerProgressBar: true,
        didOpen: (toast) => {
          toast.addEventListener("mouseenter", Swal.stopTimer);
          toast.addEventListener("mouseleave", Swal.resumeTimer);
        },
      })
      .then(function () {
        window.location = "./index.php";
      });
  }
});
