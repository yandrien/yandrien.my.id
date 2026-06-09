<?php
session_start();
/////////////////////////login proses.
if (isset($_POST['login*251201#Disciple16validation'])) {
    
    include "dbon.php";
   
    // Get data from FORM
    $uname = $_POST['uname'];
    $psw = hash('sha256', $_POST['psw']);
           try {
            $stmt = $konek->prepare('SELECT * FROM user WHERE user = :uname && password = :psw');
            $stmt->execute(array(':uname' => $uname, ':psw' => $psw));
            $datal = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($datal == false) {                
		 header('Location: login.php?error=data_tidak_ditemukan!'); exit();
            } else {
                if ($psw == $datal['password']) {
                     $_SESSION['#160187sesionaktif'] = $datal['password'];
		    setcookie('user', //$datal['email'], time() + (86400 * 30), "/"); // 86400 = 1 day
		    
		    $datal['email'], time() + (60*60*24*1), "/"); //expired dalam 60 detik x 60 menit x 24 jam x 1 hari
		    //nama cookie user, nilainya adalah data email,
                    if($datal['type'] != 'admin') {
                        $_SESSION['unameuserkasir'] = $datal['user'];
                    $_SESSION['pswuserkasir'] = $datal['password'];
			header('Location: penjualan.php?kasir='.$datal["user"]); exit(); }
			else {
			    $_SESSION['unamesuperuser16'] = $datal['user'];
			header('Location: index.php');
		    exit(); }
                } else {
                    $errMsg = 'Password tidak cocok.';
		     header('Location: login.php?error=passowrd_salah!');
			exit();
                }
            }
        }
        catch(PDOException $e) {
            $errMsg = $e->getMessage();
    header('location: login.php?try=on');
    exit();
}

 }else {
    header('location: login.php');
    exit();
}

?>