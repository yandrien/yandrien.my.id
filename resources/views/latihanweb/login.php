<?php
session_start();

if(isset($_GET['logout'])){ session_unset(); session_destroy(); setcookie("user", "", 0, "/"); }

?>
<!DOCTYPE html>
<html lang="en">

<head>
<title>crud php html</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta charset="utf-8">
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
</head>
<style type="text/css">
        @import url('stylecrudbuah.css');
body {
  height: auto;
  font-family: arial;
}
</style>
<body>

<div style="text-align: center; margin-top: 100px;">
<div style="display: inline-block; text-align: center; position: relative;">
  <fieldset style="border: 4px dotted #04AA6D; border-radius: 10px; padding: 10px; box-shadow: 0px 0px 10px rgba(0,0,0,0.2); width: 100%;">
    <p><h4>SISTEM INFORMASI DATABASE <br /> PERGUDANGAN DAN PENJUALAN</h4></p>
  </fieldset>
  <div style="position: absolute; bottom: -12px; left: 50%; transform: translateX(-50%); padding: 0 5px;">
        <button onclick="logingate('gudang')" style="width:auto; height: 30px; padding-left:10px; padding-right: 10px;">Login</button>
  </div>
</div>

<div class="footer" id="foot" style="padding: 150px 0 0 0;">Sistem Informasi Data Barang<br />
created by:yandrienlw-2025-email:ri3nlw@yahoo.com<br />
Phone/Wa:08180534365
</div>
<br /><br /><br /><br />
</div>

<div id="id02" class="modal2">
  
  <form class="modal-content2 animate" id="loginform" method="post">
    <div class="imgcontainer">
      <span onclick="document.getElementById('id02').style.display='none'" class="close2" title="Close Modal">&times;</span>
      <img src="img_avatar2.png" alt="Avatar" class="avatar">
    </div>

    <div class="container2">
<div class="framepassword" id="fuser">
      <input type="text" id="userlog" placeholder="Enter Username" name="uname" style="border:none; background:none; width:100%; text-align:center;" required>
      </div>
<div class="framepassword" id="fpass">
<input id="passw" type="password" placeholder="Enter Password" name="psw" style="border:none; background:none; width:100%; text-align:center;"  required>
</div>
      <input type="hidden" name="login*251201#Disciple16validation" value="on" >  
      <br />
      <button type="submit">Login</button>
      <label>
        <input type="checkbox" onchange="shpsw()">Show Password
      </label>
    </div>

    <div class="container2" style="background-color:#f1f1f1">
      <button onclick="document.getElementById('id02').style.display='none'" class="cancelbtn2">Cancel</button>
      <span class="psw">Forgot <a href="#">password?</a></span>
    </div>
  </form>
</div>

<script>
// Get the modal
var modal2 = document.getElementById('id02');

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal2) {
        modal2.style.display = "none";
    }
}


//listens for focus on textbox
document.getElementById('userlog').addEventListener("focus", changeDivColoruser);
document.getElementById('passw').addEventListener("focus", changeDivColorpass);


//this is fired when the textbox is focused

function changeDivColoruser(){
  document.getElementById('fuser').style.borderColor = "#04AA6D";
}
function changeDivColorpass(){
  document.getElementById('fpass').style.borderColor = "#04AA6D";
}

//listens for blur on textbox
document.getElementById('userlog').addEventListener("blur", revertDivColoruser);
 document.getElementById('passw').addEventListener("blur", revertDivColorpass);

//this is fired when the textbox is no longer focused
function revertDivColoruser(){
  document.getElementById('fuser').style.borderColor = null;
}
function revertDivColorpass(){
  document.getElementById('fpass').style.borderColor = null;
}

function shpsw() {
  var psw = document.getElementById("passw");
  if (psw.type === "password") {
    psw.type = "text";
  } else {
    psw.type = "password";
  }
}

function logingate(gate){
    document.getElementById("loginform").action = "login-proses.php";
    document.getElementById("id02").style.display = "block";
}
</script>

</body>
</html>