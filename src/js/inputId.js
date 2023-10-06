
jQuery(function ($) {

// Input Personal ID --index.php
function checkID(id) {
  if (id.length != 13) return false;
  var i = 0
  var sum = 0
  for (i = 0, sum = 0; i < 12; i++)
    sum += parseFloat(id.charAt(i)) * (13 - i);
  if ((11 - (sum % 11)) % 10 != parseFloat(id.charAt(12))) return false;
  return true;
}

function checkForm(event) {
  if (!checkID(document.frmAdd.idcard.value)) {
    document.getElementById("Button").disabled = true;
    document.getElementById("error13").hidden = false;
    document.getElementById("error13").innerHTML = "เลขบัตรประชาชนไม่ถูกต้อง";
  } else {
    document.getElementById("Button").disabled = false;
    document.getElementById("error13").hidden = true;
  }
}
$("#pid").on("change", checkForm);
});




   
