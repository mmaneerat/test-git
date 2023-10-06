jQuery(function ($) {
  // copylink -- search_notify / search_hnotify
  $(".copy_text").on("click", function (e) {
    e.preventDefault();
    var copyText = $(this).attr("href");
    var url = window.location.origin;
    var name = window.location.pathname.split("/")[1];
    var copyButton = document.getElementById("copy");

    document.addEventListener(
      "copy",
      function (e) {
        e.clipboardData.setData("text/plain", copyText);
        e.preventDefault();
      },
      true
    );

    document.execCommand("copy");
    copyButton.addEventListener("click", (e) => copyText);
  });

  //   checkbox All -- n_addnotify.php
  $("#select_all").on("click", function () {
    if (this.checked) {
      $(".checkbox").each(function () {
        this.checked = true;
      });
    } else {
      $(".checkbox").each(function () {
        this.checked = false;
      });
    }
  });
  $(".checkbox").on("click", function () {
    if ($(".checkbox:checked").length == $(".checkbox").length) {
      $("#select_all").prop("checked", true);
    } else {
      $("#select_all").prop("checked", false);
    }
  });
});
