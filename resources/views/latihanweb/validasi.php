<?php
if(isset($_SESSION['unamesuperuser16'])){
    $user = $_SESSION['unamesuperuser16'];
    $psw = $_SESSION['#160187sesionaktif'];
}
if(isset($_SESSION['unameuserkasir']) && isset($_SESSION['pswuserkasir'])){
    $user = $_SESSION['unameuserkasir'];
    $psw = $_SESSION['pswuserkasir'];
}

if(isset($user) && isset($psw)){
//validasi
include "dbon.php";
           try {
            $stmt = $konek->prepare('SELECT * FROM user WHERE user = :uname && password = :psw');
            $stmt->execute(array(':uname' => $user, ':psw' => $psw));
            $datal = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($datal == false) {                
		 header('Location: login.php?logout=on'); exit();
            }
            $usertype = $datal['type'];
            }
            catch(PDOException $e) {
            $errMsg = $e->getMessage();
            header('location: login.php?logout=on');
                    exit();
        }
}else {
    header('location: login.php?logout=on');
    exit();
}
?>