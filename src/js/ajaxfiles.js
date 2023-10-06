import * as bootstrap from "bootstrap";

jQuery(function ($) {
  
  // ajax -- p_addnotify
  $(".userinfo").on("click", (event) => {
    // console.log(event);
    // var temp = event.currentTarget;

    // console.log($(temp).data());
    // console.log($(this));

    var timeid = $(event.currentTarget).data("id");
    var rtaid = $(event.currentTarget).data("rta");

    // AJAX request
    $.ajax({
      url: "includes/ajaxfile.php",
      type: "post",
      dataType: 'json',
      data: {
        timeid: timeid,
        rtaid: rtaid,
      },
      complete: function (response) {
        // console.log("1111111");
        // console.log(response.responseText);
        // console.log(response.responseJSON);

        var resjson = response.responseJSON;
        // console.log(resjson['tbody']);
        $("#empModal .modal-body .table-res").html(resjson['tbody']);
        $("#empModal .modal-body .table-rescount").html(resjson['count']);
        new bootstrap.Modal("#empModal").show();
      },
      error: function (response) {
        console.log("errrr");
        console.log(response);
      }
    });
  });
});
