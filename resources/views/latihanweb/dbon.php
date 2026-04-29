<?php
//procedural :$konek=mysqli_connect("localhost", "root", "","ci4tutorial");
//insert INTO user (user, password, emailvalues('user',sha2('pass',256),'aku@gmail.com');
//PDO (PHP Data Object)

//cegah penyusupan
if (session_status() === PHP_SESSION_NONE) {
    header('location: login.php?try=on');
    exit();
}


try {
	$konek=new PDO("mysql:host=127.0.0.1;dbname=ci4tutorial","root","");
	$konek->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e) {
 echo "Database off!"; 
echo $e->getMessage(); 
}
?>