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
	//untuk server hp
	//$konek=new PDO("mysql:host=127.0.0.1;dbname=ci4tutorial","root","root");
	
	//untuk xamp pc
	//diubah utk cpanel 18/04/2026
	$konek=new PDO("mysql:host=127.0.0.1;dbname=yandrien_ci4tutorial","yandrien_webnative","iO6oHcf_dUJ=Ld[!");

	$konek->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e) {
 echo "Database off!"; 
echo $e->getMessage(); 
}
?>