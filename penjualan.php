<?php
try {
session_start();

//xxxxxxxxxxxxxxx verifikasi akses xxxxxxx
if (!isset($_SESSION['#160187sesionaktif']) && !isset($_COOKIE['user'])) {
    header('location: login.php');
    exit();
}


include("validasi.php");


if (isset($_GET['kasir'])) {
    $kasir = $_GET['kasir'];
    if($kasir == "admin" || $kasir == $user){
    $transaksi = "open";
    }else {
        header('location: login.php?logout=on');
        exit();
    }
} else {
    $kasir = "error";
    header('location: login.php?logout=on');
    exit();
}
//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx


if (isset($_GET['pembeli'])) {
    $pembeli = $_GET['pembeli'];
} else {
    $pembeli = "umum";
}

if (isset($_GET['kode'])) {
    $kode = $_GET['kode'];
} else {
    $kode = "";
}

if (isset($_GET['kuantitas'])) {
    $kuantitas = $_GET['kuantitas'];
    if(!ctype_digit($kuantitas)){
        $kuantitas = 1;
    }
} else {
    $kuantitas = 1;
}

if (isset($_GET['inv'])) {
    $inv = $_GET['inv'];
} else {
    $inv = "#";
    $data=$konek->prepare("select * from invoice");
	$data->execute();
	$hasil=$data->fetch();
	if($hasil['status'] == 1){
	    $inv=$hasil['invoice'];
	}
}

$dp = 0; // diset karna ada pemakaian variable pada js
$angsuran = 0;
$tanggal = 0;


if (!isset($_GET['row'])) {
    $rowid = "";
}

if (isset($_GET['delcodeid'])) {
    $id = $_GET['delcodeid'];
    $ind = $_GET['ind'];
	$code = $_GET['kod'];
	$kuan = $_GET['kuan'];

	//update kembalikan stok
	$dat=$konek->prepare("select stok from buah where kode='$code'");
	$dat->execute();
	$has=$dat->fetch();
	$sto = $has['stok'] + $kuan;
	$update=$konek->prepare("update buah set stok=:addstok where kode='$code'");
	$update->execute(array(':addstok'=>$sto));

//hapus data dr penjualan

$dat=$konek->prepare("SELECT * FROM penjualan WHERE id=:id");
	$dat->execute(array(':id'=>$id));
	$has=$dat->fetch();
	
	$kodee = explode(".",$has['kode']);
            $nama = explode(".",$has['nama']);
            $harga = explode(".",$has['harga']);
            $diskon = explode(",",$has['diskon']);
            $kuant = explode(".",$has['kuantitas']);
            $subharga = explode(".",$has['subtharga']);
            
            $naray=count($kodee)-1;
            
	for($inde=0; $inde<=$naray; $inde++){
	    if($inde!=$ind){
	        $newkode[]=$kodee[$inde];
	        $newnama[]=$nama[$inde];
	        $newharga[]=$harga[$inde];
	        $newdiskon[]=$diskon[$inde];
	        $newkuant[]=$kuant[$inde];
	        $newsubtharga[]=$subharga[$inde];
	    }
	}
	$araynewkode=implode('.', $newkode);
	$araynewnama=implode('.', $newnama);
	$araynewharga=implode('.', $newharga);
	$araynewdiskon=implode(',', $newdiskon);
	$araynewkuant=implode('.', $newkuant);
	$araynewsubtharga=implode('.', $newsubtharga);
	
	$updatenew=$konek->prepare("update penjualan set kode=:kode, nama=:nama, harga=:harga, diskon=:diskon, kuantitas=:kuantitas, subtharga=:subtharga where id=:idel");
	         $updatenew->execute(array(':kode' => $araynewkode, ':nama' => $araynewnama, ':harga' => $araynewharga, ':diskon' => $araynewdiskon, ':kuantitas' => $araynewkuant, ':subtharga' => $araynewsubtharga, ':idel'=>$id));
	         
	header("location:penjualan.php?pembeli=$pembeli&kasir=$kasir&inv=$inv");
    exit();
}

if (isset($_GET['endbuying'])) {
    $inv = $_GET['endbuying'];
	$data = $konek->prepare("select * from penjualan where invoice=:inv");
	$data->execute(array(':inv' => $inv));
	$hasil=$data->fetch();
	
	$code = explode(".",$hasil['kode']);
	$kuant = explode(".",$hasil['kuantitas']);
	$naray=count($code)-1;
	
	
	for($inde=0; $inde<=$naray; $inde++){

	//update kembalikan stok
	$dat=$konek->prepare("select stok from buah where kode='$code[$inde]'");
	$dat->execute();
	$has=$dat->fetch();
	$sto = $has['stok'] + $kuant[$inde];
	$update=$konek->prepare("update buah set stok=:addstok where kode='$code[$inde]'");
	$update->execute(array(':addstok'=>$sto));

	}

	
    $del = $konek->prepare("delete from penjualan where invoice =:inv");
    $del->execute(array(':inv' => $inv));
    
    header("location:penjualan.php?pembeli=$pembeli&kasir=$kasir&inv=$inv");
    exit();
}

if (isset($_GET['revisi'])) {
    $inv = $_GET['revisi'];
    $rbayar = $_GET['bayar'];
    if($rbayar == "Cash"){
        $tab = "payment";
    } else { $tab = "kredit"; }
    $del = $konek->prepare("delete from $tab where invoice =:inv");
    $del->execute(array(':inv' => $inv));
    
    $update = $konek->prepare("update invoice set status=:satu");
        $array = array(':satu' => 1);
        $update->execute($array);
        
        header("location:penjualan.php?pembeli=$pembeli&kasir=$kasir&inv=$inv");
        exit();
}

if (isset($_GET['push'])) {
    //set invoice
    $data = $konek->prepare("select * from invoice");
    $data->execute();
    $hasil = $data->fetch();
    $inv = $hasil['invoice'];
    $statusinv = $hasil['status'];
    if ($statusinv == 0) {
        $inv = $inv + 1;
        $update = $konek->prepare("update invoice set invoice=:inv,     status=:satu");
        $array = array(':inv' => $inv, ':satu' => 1);
        $update->execute($array);
    }

    $select = "select * from buah where kode='$kode'";
    $data = $konek->prepare("$select");
    $data->execute();
    $cek = $data->rowCount();
    if ($cek > 0) {
        $hasil = $data->fetch();
        
   if($kuantitas<=$hasil['stok'] && $kuantitas>0){
    //pengurangan stok
	$stok = $hasil['stok'] - $kuantitas;
	$update=$konek->prepare("update buah set stok=:addstok where kode='$kode'");
	$array=array(':addstok'=>$stok);
	$update->execute($array);
        
        $nama = $hasil['nama'];
        $harga = $hasil['harga_jual'];
        $diskon = $hasil['diskon'];
        $subharga = ($harga - ($harga * $diskon * 0.01)) * $kuantitas;

            //input penjualan
            $select = "select * from penjualan where invoice='$inv'";
            $data = $konek->prepare("$select");
            $data->execute();
            $cek = $data->rowCount();
            
            if (empty($cek)) {
            $insert = $konek->prepare("insert into penjualan (kode, nama, harga, diskon, kuantitas, subtharga, invoice) values (:kode, :nama, :harga, :diskon, :kuan, :subtharga, :inv)");
            $array = array(':kode' => $kode, ':nama' => $nama, ':harga' => $harga, ':diskon' => $diskon, ':kuan' => $kuantitas, ':subtharga' => $subharga, ':inv' => $inv);
            $insert->execute($array);
            }
            else { //update penjualan
            $hasil = $data->fetch();
            
            if (isset($_GET['upcodeid'])) { //ubah
                $index = $_GET['upcodeid'];
                
                $dkode = explode(".", $hasil['kode']);
                $dkode[$index] = $kode;
                $kode = implode('.',$dkode);
                
                $dnama = explode(".", $hasil['nama']);
                $dnama[$index] = $nama;
                $nama = implode('.',$dnama);
                
                $dharga = explode(".", $hasil['harga']);
                $dharga[$index] = $harga;
                $harga = implode('.',$dharga);
                
                $ddiskon = explode(",", $hasil['diskon']);
                $ddiskon[$index]= $diskon;
                $diskon =implode(',',$ddiskon);
                
                $dkuan = explode(".", $hasil['kuantitas']);
                $dkuan[$index]=$kuantitas;
                $kuantitas=implode('.',$dkuan);
                
                $dsub = explode(".", $hasil['subtharga']);
                $dsub[$index] = $subharga;
                $subharga=implode('.',$dsub);
                
            }
                else{ //tambah
                $kode = $hasil['kode'].".".$kode;
                $nama = $hasil['nama'].".".$nama;
                $harga = $hasil['harga'].".".$harga;
                $diskon = $hasil['diskon'].",".$diskon;
                $kuantitas = $hasil['kuantitas'].".".$kuantitas;
                $subharga = $hasil['subtharga'].".".$subharga;
                
                }
        $update = $konek->prepare("update penjualan set kode=:kode, nama=:nama, harga=:harga, diskon=:diskon, kuantitas=:kuan, subtharga=:subtharga where invoice = '$inv'");
            $array = array(':kode' => $kode, ':nama' => $nama, ':harga' => $harga, ':diskon' => $diskon, ':kuan' => $kuantitas, ':subtharga' => $subharga);
            $update->execute($array);
                
            }
            $error="";
    }
    else { $error="&error=OVER QUANTITY, STOK KODE BARANG ".$kode." = ".$hasil['stok']; }
    
    }
    else { $error="&error=KODE BARANG ".$kode." TIDAK ADA !"; }
    
    if(isset($_GET['row'])){
        $rowid = "&row=" . $_GET['row'];
    }
    else{ $rowid=""; }

   header("location:penjualan.php?pembeli=$pembeli&kasir=$kasir&inv=$inv" . $rowid.$error);
    exit();
}

$lastdate = date("Y-m-d"); //untuk warning telat bayar angsuran dan pembanding

//settings
if(isset($_POST['settingson'])){
    if($_POST['token'] == $_SESSION['token']){
    $newdp = $_POST['newdp'];
    $newbunga = $_POST['newbunga'];
    $newrek = $_POST['newrek'];
    
    $set = $konek->prepare("insert into settings (tglubah, dp, bunga, rek) values (:tglubah, :dp, :bunga, :rek)");
    $array = array(':tglubah' => $lastdate, ':dp' => $newdp, ':bunga' => $newbunga, ':rek' => $newrek);
    $set->execute($array);
    $settingdone = 1;
    unset($_SESSION['token']);
    
    //hapus data jika lebih dari 5 baris
    $delset = $konek->prepare("DELETE s1 FROM settings s1 LEFT JOIN (SELECT id FROM settings ORDER BY id DESC LIMIT 5) s2 ON s1.id = s2.id WHERE s2.id IS NULL");
    $delset->execute();
    }
}
$selset = $konek->prepare("select * from settings ORDER BY id DESC");
$selset->execute();
$allData = $selset->fetchAll(); //ambil seluruh baris data

if($allData){
    $newset = $allData[0]; //ambil data baris pertama sbg dt trbaru
    
    $newdp = $newset['dp'];
    $newbunga = $newset['bunga'];
    $newrek = $newset['rek'];
}else{
    $newdp = 2;
    $newbunga = 30;
    $newrek = "BCA 160187 an YANDRI";
}

//buat token acak untuk session settings shg bisa mencegah duplikasi data karena reload, juga utk mencegah serangan CSRF
$token = bin2hex(random_bytes(32));
$_SESSION['token'] = $token;

//input payment

if (isset($_GET['paymenton'])) {
    $bayar=$_GET['bayar'];
    if($bayar=="Cash" || $bayar=="Cash-Tf"){
    $sel = "select * from payment where invoice='$inv'";
    $pay = $konek->prepare("$sel");
    $pay->execute();
    $npay = $pay->rowCount();
        
        $tharga = $_GET['tharga'];
        $money = $_GET['money'];
        $tanggal = $_GET['datime'];
        $ket = $_GET['ket'];
        $sisamoney = $money - $tharga;
    if (empty($npay)) {
        $insert = $konek->prepare("insert into payment (invoice, total_harga, bayar, money, sisa, tanggal, pembeli, keterangan, kasir, lastdate) values (:inv, :tharga, :bayar, :money, :sisa, :tgl, :pembeli, :ket, :kasir, :lastdate)");
        $array = array(':inv' => $inv, ':tharga' => $tharga, ':bayar' => $bayar, ':money' => $money, ':sisa' => $sisamoney, ':tgl' => $tanggal, ':pembeli' => $pembeli, ':ket' => $ket, ':kasir' => $kasir, ':lastdate' => $lastdate);
        $insert->execute($array);

        $update = $konek->prepare("update invoice set status=:satu");
        $array = array(':satu' => 0);
        $update->execute($array);
    }
    }
    elseif($bayar=="Kredit" || $bayar=="Credit-Tf"){
        $money = $_GET['money'];
        $tanggal = $_GET['datime'];
        
        $pembayaran = $_GET['dpcicil']; //index pembayaran, apakah dp, angs 1, 2, dst.
        
    $sel = "select * from kredit where invoice='$inv'";
    $pay = $konek->prepare("$sel");
    $pay->execute();
    $hasil = $pay->fetch();
    $npay = $pay->rowCount();
        
    if (empty($npay)) {
        $cicilan=0;
        $tharga = $_GET['tharga'];
        $tenor = $_GET['tenor'];
        
        //default dp 30%, bunga 2%
        $dp = round($tharga*$newdp/100);
        $totalbunga = round(($tharga - $dp)*$tenor*$newbunga/100);
        
        $thargakredit = $tharga + $totalbunga;
        $totalangsuran = $tharga - $dp + $totalbunga;
    
        $angsuran = round($totalangsuran/$tenor);
        
        $sisamoney = $money - $dp;
        
        $ket = "belum lunas";
        
        $insert = $konek->prepare("insert into kredit (invoice, total_harga, bayar, total_harga_kredit, dp, tenor, angsuran, cicilan, money, sisa, tanggal, pembeli, keterangan, kasir, lastdate) values (:inv, :tharga, :bayar, :thargakredit, :dp, :tenor, :angsuran, :cicilan, :money, :sisa, :tgl, :pembeli, :ket, :kasir, :lastdate)");
        $array = array(':inv' => $inv, ':tharga' => $tharga, ':bayar' => $bayar, ':thargakredit' => $thargakredit, ':dp' => $dp, ':tenor' => $tenor, ':angsuran' => $angsuran, ':cicilan' => $cicilan, ':money' => $money, ':sisa' => $sisamoney, ':tgl' => $tanggal, ':pembeli' => $pembeli, ':ket' => $ket, ':kasir' => $kasir, ':lastdate' => $lastdate);
        $insert->execute($array);

        $update = $konek->prepare("update invoice set status=:satu");
        $array = array(':satu' => 0);
        $update->execute($array);
        
    }else{ //update kredit
            $tenor = $hasil['tenor'];
            $thargakredit = $hasil['total_harga_kredit'];
            $dp = $hasil['dp'];
            $angsuran = $hasil['angsuran'];
            $cicilan = $hasil['cicilan'];
            $ket = "belum lunas";
            $sisamoney = $money - $hasil['angsuran'];
            
            if($pembayaran > $hasil['cicilan']){ //hanya untuk sekali load page (cegah refresh page)
            $cicilan = $hasil['cicilan']+1;
            if($cicilan == $hasil['tenor']){ $ket = "lunas"; }
            
            $arbayar = $hasil['bayar'].".".$bayar;
            $armoney = $hasil['money'].".".$money;
            $arsisamoney = $hasil['sisa'].".".$sisamoney;
            $artanggal = $hasil['tanggal'].".".$tanggal;
            $arkasir = $hasil['kasir'].".".$kasir;
            
            $update = $konek->prepare("update kredit set bayar=:bayar, cicilan=:cicilan, money=:money, sisa=:sisa, tanggal=:tgl, keterangan=:ket, kasir=:kasir, lastdate=:lastdate where invoice = '$inv'");
            $array = array(':bayar' => $arbayar, ':cicilan' => $cicilan, ':money' => $armoney, ':sisa' => $arsisamoney, ':tgl' => $artanggal, ':ket' => $ket, ':kasir' => $arkasir, ':lastdate' => $lastdate);
            $update->execute($array);
            }
    
    //nilai variabel untuk switching cicilan
    $dtanggal = $hasil['tanggal'];
	$araytanggal = explode(".", $dtanggal);
	$dkasir = $hasil['kasir'];
	$araykasir = explode(".", $dkasir);
	$dbayar = $hasil['bayar'];
	$araybayar = explode(".", $dbayar);
 	$dmoney = $hasil['money'];
 	$araymoney = explode(".", $dmoney);
	$dsisamoney = $hasil['sisa'];
	$araysisamoney = explode(".", $dsisamoney);
    }
    
    //nilai variabel untuk cicilan sekarang
    $araytanggal[$cicilan]=$tanggal;
        $araykasir[$cicilan]=$kasir;
        $araybayar[$cicilan]=$bayar;
        $araymoney[$cicilan]=$money;
        $araysisamoney[$cicilan]=$sisamoney;
    }
	
    else {
       header("location:penjualan.php?pembeli=$pembeli&kasir=$kasir&inv=$inv&payretry=on"); 
    }
    $transaksi = "closed";
}

//pencarian data payment

$datasearch=1;

if(isset($_GET['cari'])){
$cari=$_GET['cari']; $inv = $cari;
$payment=$_GET['payment'];
if($payment=="Cash"){
$select = "select * from payment where invoice='$inv'";
    $data = $konek->prepare("$select");
    $data->execute();
    $cek = $data->rowCount();
    if ($cek > 0) {
        $hasil = $data->fetch();
        $tanggal = $hasil['tanggal'];
        $kasir = $hasil['kasir'];
        $pembeli = $hasil['pembeli'];
	$bayar = $hasil['bayar'];
 	$money = $hasil['money'];
	$sisamoney = $hasil['sisa'];
	
	$ket=$hasil['keterangan'];
	
	$transaksi = "closed";
	$opendatapenjualan="on";
	}else {
	    $datasearch=0;
	    $cari="";
	    $opendatapenjualan="off";
	}
}
else{
$select = "select * from kredit where invoice='$inv'";
    $data = $konek->prepare("$select");
    $data->execute();
    $cek = $data->rowCount();
    if ($cek > 0) {
        $hasil = $data->fetch();
        $dtanggal = $hasil['tanggal'];
        $araytanggal = explode(".", $dtanggal);
        $dkasir = $hasil['kasir'];
        $araykasir = explode(".", $dkasir);
        $pembeli = $hasil['pembeli'];
        $tenor = $hasil['tenor'];
        $thargakredit = $hasil['total_harga_kredit'];
        $dp = $hasil['dp'];
        $angsuran = $hasil['angsuran'];
        $cicilan = $hasil['cicilan'];
	$dbayar = $hasil['bayar'];
	$araybayar = explode(".", $dbayar);
 	$dmoney = $hasil['money'];
 	$araymoney = explode(".", $dmoney);
	$dsisamoney = $hasil['sisa'];
	$araysisamoney = explode(".", $dsisamoney);
	$ket=$hasil['keterangan'];
	
	$tanggal = $araytanggal[$cicilan];
	$kasir = $araykasir[$cicilan];
	$bayar = $araybayar[$cicilan];
	$money = $araymoney[$cicilan];
	$sisamoney = $araysisamoney[$cicilan];
	
	$transaksi = "closed";
	$opendatapenjualan="on";
	}
	else {
	    $datasearch=0;
	    $cari="";
	    $opendatapenjualan="off";
	}
}
}else { $cari="";
$opendatapenjualan="off"; }

if($datasearch!=1){
$inv="empty";
}


//notifikasi kredit

// utk paging ///////////////////////////

if(!isset($_GET['hal'])){ //tetapkan halaman default
$hal = 1;
}
else { //tangkap halaman yang dipilih
$hal = $_GET['hal'];
}

//tetapkan banyaknya row data yang diambil dari tabel db
$max_results = 10;

//rumus titik row tabel yang diambil
$from = (($hal * $max_results) - $max_results);

//jumlahkredit
$tabelkredit = "SELECT
  SUM(IF(keterangan = 'belum Lunas', 1, 0)) AS jumlah_kredit_belum_lunas,
  SUM(IF(keterangan = 'belum Lunas' AND TIMESTAMPDIFF(MONTH, lastdate, CURDATE()) > 1, 1, 0)) AS jumlah_kredit_macet
FROM 
  kredit";
$jumlahkredit = $konek->prepare("$tabelkredit");
$jumlahkredit->execute();
$datajumlahkredit = $jumlahkredit->fetch(PDO::FETCH_ASSOC);
$nt = $datajumlahkredit['jumlah_kredit_belum_lunas'];
$kreditmacet = $datajumlahkredit['jumlah_kredit_macet'];

//rumus total tombol hal yang dibentuk (paging)
$total_pages = ceil($nt / $max_results);


//tagihan per halaman
$selectkredit = "select * from kredit where keterangan = 'belum lunas' LIMIT $from, $max_results";
$belumlunas = $konek->prepare("$selectkredit");
$belumlunas->execute();
//$nb = $belumlunas->rowCount();


//rekapan transaksi*******************

//data cash hari ini
$selectcash = "select total_harga from payment where lastdate = '$lastdate'";
$datacash = $konek->prepare("$selectcash");
$datacash->execute();
$fetchdatacash = $datacash->fetchAll();
$ncasht = count($fetchdatacash);
$cashomset = array_sum(array_column($fetchdatacash, 'total_harga'));

//data kredit hari ini
$selectcredit = "select dp, angsuran, cicilan from kredit where lastdate = '$lastdate'";
$datacredit = $konek->prepare("$selectcredit");
$datacredit->execute();

$ncreditt = 0;
$ndpt = 0;
$nangsurant = 0;
$dpomset = 0;
$angsuranomset = 0;
while($hasildc = $datacredit->fetch()){
    if($hasildc['cicilan']<1){ //utk DP
        $ndpt++;
        $dpomset += $hasildc['dp'];
    }
    else{
        $nangsurant++;
        $angsuranomset += $hasildc['angsuran'];
    }
    $ncreditt++;
}
$creditomset = $dpomset + $angsuranomset;

//total
$ntransaksi = $ncasht + $ncreditt;
$omset = $cashomset + $creditomset;

//***********************************

//***rekapan harian to grafik***

$barray = array('Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');

if(isset($_GET['switchgrafik'])){
$bulanbar = $_GET['bulan']+1;
$bulanrekap = sprintf($bulanbar);
$tahunrekap = $_GET['tahun'];

$bulancredit = $barray[$_GET['bulan']];
$bulantahuncredit = $bulancredit." ".$tahunrekap;

}else{
$bulanrekap = "MONTH(CURRENT_DATE())";
$tahunrekap = "YEAR(CURRENT_DATE())";

$datecurrent = date("Y-m");
$pecahkan = explode("-", $datecurrent);
$bulantahuncredit = $barray[ (int) $pecahkan[1] - 1 ] . " " . $pecahkan[0];

// contoh Output $bulantahuncredit: Juli 2025, ini digunakan untuk mencari bulan dan tahun di dalan array tanggal pembayaran credit

}

$rekaphariancash = $konek->prepare("select SUM(total_harga) AS total_transaksi, lastdate, COUNT(*) AS n_transaksi from payment where MONTH(lastdate) = $bulanrekap AND YEAR(lastdate) = $tahunrekap GROUP BY lastdate ORDER BY lastdate ASC");
$rekaphariancash->execute();
$fetchrekaphariancash = $rekaphariancash->fetchAll(PDO::FETCH_ASSOC);
$nrh = count($fetchrekaphariancash);

//$bulantahun = date('F Y');
//$jumlahari = date('t');

//$tanggalarray = array();
$tanggalarraydb = array();
$total_transaksi = array();
$n_transaksi = array();

//for($h=1;$h<=$jumlahari;$h++){
    //$tanggalarray[]=$h;
    //$total_transaksi[$h]=0; }

if($nrh > 0){
    foreach ($fetchrekaphariancash as $rowrekap) {
        
        //ambil tgl
    $gettgl = (int) date('d', strtotime($rowrekap['lastdate']));
    $tanggalarraydb[] = $gettgl;
    
    //ambil pembayaran
    $total_transaksi[$gettgl] = $rowrekap['total_transaksi'];
    
    //ambil n transaksi per tgl
    $n_transaksi[$gettgl] = ($n_transaksi[$gettgl] ?? 0) + 1;
    }
}else{
    $tanggalarraydb[] = 0;
    $total_transaksi[] = 0;
}

//rekapan kredit to grafik
//n_tgl adalah banyaknya kemunculan bulan tahun tiap baris
$creditrekap = "SELECT 
                dp, angsuran, cicilan, tanggal, lastdate,
                ((LENGTH(tanggal) - LENGTH(REPLACE(tanggal, '$bulantahuncredit', ''))) / LENGTH('$bulantahuncredit')) AS n_tgl
                FROM kredit 
                WHERE tanggal LIKE '%$bulantahuncredit%'";

$datacreditrekap = $konek->prepare("$creditrekap");
$datacreditrekap->execute();
$fetchcredit = $datacreditrekap->fetchAll(PDO::FETCH_ASSOC);
$nrhc = count($fetchcredit);

$tanggal_bcredit = array();
$total_credit = array();
$n_credit = array();

if($nrhc >0){
foreach ($fetchcredit as $rowrekapc){

if($rowrekapc['cicilan'] > 0){
    
//jika pembayaran lebih dari sekali dalam sebulan
if($rowrekapc['n_tgl'] > 1){
$tglex = explode(".", $rowrekapc['tanggal']);
for($j=$rowrekapc['cicilan']; $j>=0; $j--) {
    
    //ambil tanggal dari data
    //strpos memeriksa data yang dicari ada di dalam tanggal
    if (strpos($tglex[$j], $bulantahuncredit) !== false) {
        
    //ambil tanggal setelah koma
    preg_match('/\d+/', substr($tglex[$j], strpos($tglex[$j], ',') + 1), $matches);
    $tanggal_bcredit[] = $matches[0];
    
    //ambil pembayarannya
    if($j > 0){ //nilai angsuran
    $total_credit[$matches[0]] = ($total_credit[$matches[0]] ?? 0) + $rowrekapc['angsuran'];
    //agar tidak error saat dimuat di halaman web, spt "Undefined array key 8...", karena tdk semua row itu punya tgl pembayaran yg sama. jd ?? 0 itu berarti jika index yg dimaksud tidak ada maka dibuat 0 shg bs ditambahkan dg angsuran.
    
    }else{//nilai dp
        $total_credit[$matches[0]] = ($total_credit[$matches[0]] ?? 0) + $rowrekapc['dp'];
    }
    //hitung banyak transaksi per tglnya
    $n_credit[$matches[0]] =   ($n_credit[$matches[0]] ?? 0) + 1;
    
    } //ambil tanggal bisa juga dengan cara parsing, ini biar lebih sederhana tapi bikin makan memori
    
}
    
}else{
    // jika hanya 1 tanggal maka tidak perlu diexplode

//contoh data yang diparsing untuk mengambil tanggalnya "Selasa, 17 Juni 2025, 12:23:49 PM";

$tanggal_array = explode(',', $rowrekapc['tanggal']);
$tanggal_bulan_tahun = trim($tanggal_array[1]);
$tanggal_bulan_tahun_array = explode(' ', $tanggal_bulan_tahun);
$tanggal_bcredit[] = $tanggal_bulan_tahun_array[0];

//ambil pembayarannya
$total_credit[$tanggal_bulan_tahun_array[0]] = ($total_credit[$tanggal_bulan_tahun_array[0]] ?? 0) + $rowrekapc['angsuran'];
    
$n_credit[$tanggal_bulan_tahun_array[0]] = ($n_credit[$tanggal_bulan_tahun_array[0]] ?? 0) + 1;
}
}else{
    //jika cicilan 0 berarti hanya 1 data, ambil tanggal dari lastdate saja
    $lastdatecredit = (int) date('d', strtotime($rowrekapc['lastdate']));
    $tanggal_bcredit[] = $lastdatecredit;
    
    //ambil pembayarannya
    $total_credit[$lastdatecredit] = ($total_credit[$lastdatecredit] ?? 0) + $rowrekapc['dp'];
    
    $n_credit[$lastdatecredit] = ($n_credit[$lastdatecredit] ?? 0) + 1;
}
    
}
}else{
    $tanggal_bcredit[] = 0;
    $total_credit[] = 0;
}

//gabung tanggal arraydbcas dan tanggal arraydbcredit sehingga bisa ditampilkan di grafix sumbu x scr berurutan
$tanggalarraydb = array_merge($tanggalarraydb, $tanggal_bcredit);
$tanggalarraydb = array_values(array_unique($tanggalarraydb));
sort($tanggalarraydb);

//data barang yang diorder
$select = "select * from penjualan where invoice='$inv' order by id desc";
$data = $konek->prepare("$select");
$data->execute();
$n = $data->rowCount();

?>

<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>crud php html</title>
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
<!--
<script src="https://cdn.jsdelivr.net/npm/chart.js">
</script> -->

</head>
<style type="text/css">
    @import url('stylecrudbuah.css');
</style>

<body>
<div class="topnav">
<div class="dropdown menupenjualan">
<button onclick="menu()" class="rightmenux" id="menuhp">
<hr class="menuline">
<hr class="menuline">
<hr class="menuline">
</button>
<div id="myDropdown" class="dropdown-content">
    <a href="javascript:void(0)" onclick="exitpage()">Keluar</a>
    <a href="javascript:void(0)" onclick="gudang()">Gudang</a>
    <a href="javascript:void(0)" onclick="opentabel('grafik')">Grafik</a>
    <a href="javascript:void(0)" onclick="opentabel('tabelrekapan')">Rekapan Hari Ini</a>
    <a href="javascript:void(0)" onclick="opentabel('tabeltagihan')" id="notifikasikredit">Tagihan Kredit</a>
    <a href="javascript:void(0)" onclick="opentabel('settings')" id="notifikasikredit">Settings</a>
    <br />
    <div class="framesearch" id="fsearch">
<div class="left">
<input type="text" name="cari" placeholder="Cari Invoice" class="warnaplaceholder" title="cari" <?php if(isset($_GET['cari'])){ ?> value="<?=$_GET['cari']?>"<?php }?> id="datacari" style="width:150px; border:none; background:none; color:white;" >
</div>
<div class="left">
<input type="submit" value="" class="icon" title="cari" onclick="caridata()" id="submitcari" >
</div>

<?php
if(isset($_GET['cari'])){ ?>
<div class="left">
<span title="Tutup pencarian" style="text-decoration:none;" onclick="tutupcari()"><span class="close">&times;&nbsp;</span></span>
</div> <?php } ?>

</div>
<br />
<div class="left">
    <input type="radio" id="invkredit" name="pilihinvoice" checked="checked" onchange="caridata()"><span class="white">Invoice Kredit</span>&nbsp;&nbsp;
    <input type="radio" id="invcash" <?php if(isset($_GET['payment'])){ if($_GET['payment'] == "Cash"){ ?>checked="checked" <?php }} ?> name="pilihinvoice" onchange="caridata()"><span class="white">Invoice Cash</span>
</div>
</div>
</div>
        <div class="sale2">
            TOKO MAHAL <br />
            <span class="sale3" id="dt"></span>
            <input type="hidden" name="datetime" id="gate">
            <input type="hidden" id="timenow" value="0">
            <br />
            <span class="sale1">Kasir</span><input type="text" style="width:200px;" value="<?= $kasir ?>" readonly>
            <span class="sale1 space">Pembeli</span><input type="text" id="buyer" style="width:200px;" value="<?=$pembeli?>">
        </div>

        <div class="sale2" id="boxitem">
	<span class="sale1">Kode Produk</span><input type="text" name="kode" id="code" style="width:200px;" value="<?= $kode ?>">
            <span class="sale1 space">Qty</span><input type="text" inputmode="numeric" name="kuantitas" id="kuan" style="width:200px;" value="<?= $kuantitas ?>" oninput="justnumber('kuan')">
            <br />
            <span class="sale1"></span><button id="addbuy" style="width:63px; margin-top:2px;" onclick="pushdata()"><span id="go">Push</span></button>
            <button style="width:63px; margin-top:2px; display:none;" onclick="canceledit(this.value)" class="cancelbtn" id="canceledit">Cancel</button>
        </div>

        <div class="sale4">
            <span class="sale1">Invoice: <?=$inv?></span> <br />
            <span class="sale1 bigfon" id="big"></span>
            <br />
<input type="text" inputmode="numeric" id="inputcash" class="bigfon" style="display: none; width:200px;" oninput="callformat()" onfocus="callformat()">
&nbsp;<span id="kembalisisa" class="merah"></span>
<span id="sisauang" class="merah"></span>
<span id="norek" class="mediumfon"></span>
<br />
            <button id="paymenton" style="width:63px;" onclick="paymenton('yes')"><span id="pay" >BAYAR</span></button>
            <?php
            //if(isset($_GET['paymenton']) && $bayar !="Cash" || $cari!="" && $payment!="Cash" && $opendatapenjualan=="on"){ 
            if($dp!=0){
                $cicil=$cicilan;
                if($cicilan==$tenor){
                    $newangsuran=$cicil;
                }else{ 
                    if($opendatapenjualan=="on"){
                    $cicil=$cicilan+1;
                    $newangsuran=$cicil;
                    }
                    else{
                        $newangsuran=$cicil+1;
                    }
                    }
           
            ?>
            <input type="hidden" id="buffer" value="<?=$cicil?>"><!--sbg pilihan yg berubah2 nilainya-->
            <input type="hidden" id="newangsuran" value="<?=$newangsuran?>"><!--angsuran tagihan-->
            <input type="hidden" id="cicilankredit" value="<?=$cicilan?>">
            &nbsp;
            <span id="cdp" class="pilihan silang" onclick="selectkreditbayar('0')" >DP</span>&nbsp;|&nbsp;Angsuran:&nbsp;
            <?php
            for($r=1;$r<=$tenor;$r++){ ?>
            <span id="cangsur<?=$r?>" class="pilihan <?php if($r<=$cicilan){ ?>silang <?php } ?>" onclick="selectkreditbayar('<?=$r?>')"><?=$r?></span>&nbsp;
            <?php } } ?>
            
<button style="width:63px; margin-top:2px; float:right;" onclick="cancel('cancelbutton')" class="cancelbtn" id="cancelpay">Cancel</button>
<br />
<span id="radio">
<input type="radio" name="bayar" id="cash" checked="checked" onchange="paymenton('no')">Cash&nbsp;
<input type="radio" name="bayar" id="credit" onchange="paymenton('no')"<?php if($dp!=0){ ?> checked="checked" <?php } ?> >Credit&nbsp;
<select name="tenor" id="tenor" style="display:none;" onChange="viewkredit(this.value)">
    <option value="0">Tenor</option>
    <option value="3">3 bln</option>
<option value="6">6 bln</option>
<option value="12">12 bln</option>
<option value="24">24 bln</option>
<option value="36">36 bln</option></select> 
</span>
<?php if($dp !=0){
    echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
} ?>
&nbsp;<span id="tfspan" >
<input type="checkbox" name="transfer" id="tf" onchange="paymenton('nocash')">Transfer
</span><br />
<span id="detailkredit" style="display:none;">
  <span id="inidp"></span><span id="dp"></span>
  <span id="iniangsuran"></span><span id="angsuran"></span>
  <span id="initotalangsuran"></span><span id="totalangsuran"></span>
  <span id="inibunga"></span><span id="totalbunga"></span>
  <span id="inihargakredit"></span><span id="thargakredit"></span>
  
    
</span>
        </div>
    </div>

    <br />
    <div class="topnav" id="listitem">
        <?php
        if(isset($_GET['error'])){
            echo "<font color='red'>".$_GET['error']."</font><br />";
        }
        ?>
        <table class="table" cellpadding="3" width="100%"><tr>
                <th>Kode/ <br />Barang</th>
                <th>Harga/<br />Diskon</th>
                <th>Qty</th>
                <th>Sub-total<br />Harga</th>
                <th class="tdact" <?php if($transaksi == "closed"){ ?>style="display:none;"<?php } ?> >Act</th>

            </tr>
            <?php
                
            $tharga = 0;
            $tkuan = 0;
            $i = 1;
            
            if($n>0){
            $hasil = $data->fetch();
            $id=$hasil['id'];
            $kode = explode(".",$hasil['kode']);
            $nama = explode(".",$hasil['nama']);
            $harga = explode(".",$hasil['harga']);
            $diskon = explode(",",$hasil['diskon']);
            $kuant = explode(".",$hasil['kuantitas']);
            $subharga = explode(".",$hasil['subtharga']);
            
            for($j=count($kode)-1; $j>=0; $j--) {
                
                $hargadiskon[$j]=$harga[$j]-($harga[$j]*$diskon[$j]/100);
                
            ?>
                    <tr id="row<?= $i ?>" onclick="clearbgrow('row<?= $i?>')">
                        <td><?=$kode[$j] ?><br /><?= $nama[$j] ?></td>
                        <td align="center"><div id="harga<?=$i?>" class="<?php if($diskon[$j] < 1){ ?>textalign <?php }else{ ?>strikethrough <?php } ?>"><?=$harga[$j]?></div><?php if($diskon[$j] > 0){ ?>
<div class="textalign">&nbsp;<?=$diskon[$j]?>&nbsp;%&nbsp;&nbsp;=&nbsp;<span id="hargadiskon<?=$i?>"><?=$hargadiskon[$j]?></span></div><?php } else { ?><span id="hargadiskon<?=$i?>"></span><?php } ?></td>
                        <td align="center"><div><?=$kuant[$j]?></div></td>
                        <td align="center">
			<div id="subtotalrow<?=$i?>" class="textalign"><?=$subharga[$j]?></div>
			</td>
                        <td <?php if($transaksi == "closed"){ ?>style="display:none;"<?php } ?> ><input type="button" id="up<?= $i ?>" value="Up" onclick="edit('<?= $j ?>','<?= $kode[$j] ?>','<?= $kuant[$j] ?>','row<?= $i ?>')"><input type="button" id="delitem<?=$i?>" value="Del" onclick="delelete('<?=$id ?>','<?= $j ?>', '<?=$kode[$j]?>','<?=$kuant[$j]?>','<?=$i?>', 'delitem<?=$i?>', 'godel')"></td>
                    </tr>
            <?php
                $tharga = $tharga + $subharga[$j];
		$tkuan = $tkuan + $kuant[$j];
                $i++;
            } }?>
            <tr>
                <td colspan="2"><b>Total</b></td>
		<td align="center"><b><?=$tkuan?></b></td>
                <td align="center"><b><div id="tharga" class="textalign"><?=$tharga?></div></b></td>
            </tr>
            <?php
            if(isset($_GET['paymenton']) || $cari!=""){
            if($bayar == "Kredit" || $bayar== "Credit-Tf"){ ?>
            <tr>
                <td colspan="3"><b>KREDIT-Tenor <?=$tenor?> Bulan </b></td>
                <td align="center"><b><div id="notathargakredit" class="textalign"><?=$thargakredit?></div></b></td>
            </tr>
            <tr>
                <td colspan="3"><b>DP</b></td>
                <td align="center"><b><div id="notadp" class="textalign"><?=$dp?></div></b></td>
            </tr>
            <tr>
                <td colspan="3"><b>Angsuran</b></td>
                <td align="center"><b><div id="notaangsuran" class="textalign"><?=$angsuran?></div></b></td>
            </tr>
            <tr>
                <td colspan="3"><b>Keterangan</b></td>
                <td align="center"><b><div id="statuskredit" class="textalign"><?=$ket?></div></b></td>
            </tr>
            <?php } ?>
            
            <tr id="pembayaranmoney">
                <td colspan="3" align="right"><b><span id="carabayar"><?=ucwords($bayar)?> </span></b>&nbsp;</td>
                <td align="center"><b><div id="notauangbayar" class="textalign"><?=$money?></div></b>
                <?php
                if($dp!=0){
                for($am=0;$am<=$cicilan;$am++){ ?>
                    <input type="hidden" id="abayar<?=$am?>" value="<?=$araybayar[$am]?>">
                    <input type="hidden" id="amoney<?=$am?>" value="<?=$araymoney[$am]?>">
                     <input type="hidden" id="asmoney<?=$am?>" value="<?=$araysisamoney[$am]?>">
                     
                                          <input type="hidden" id="araytanggal<?=$am?>" value="<?=$araytanggal[$am]?>">
                <?php }} ?>
                
                </td>
            </tr>
            <tr  <?php if($sisamoney == 0){?> style="display: none;"<?php } ?> id="sisapembayaran">
                <td colspan="3" align="right"><b>Sisa kembalian</b>&nbsp;</td>
                <td align="center"><b><div id="notauangsisa" class="textalign"><?=$sisamoney?></div></b></td>
            </tr>
            <?php } ?>
        </table>
    </div>
 



<div class="modal" id="print">
<div class="modal-content animate">
<br />
<div class="left">
<span id="printtoko"></span><br />
<span id="printalamat"></span><br />
<span id="printnohp"></span>
</div>
<div class="right">
<span id="printdatime"></span><br />
<span id="printkasir"></span><br />
<span id="printbuyer"></span><br />
<span id="printinv"></span>
</div>
<br />
<div id="printcontent"></div>
<center>
<button id="cetaknota" style="width:100px; margin-top:2px;margin-bottom:2px;" class="button2" onclick="finish()">TERIMAKASIH</button>
</center>

</div>
</div>

<!-- modal untuk confirm hapus -->
<div class="modalconfirm" id="confirmdel">
    <div class="modalconfirm-content">
  <svg width="48" height="48" viewBox="0 0 24 24" fill="#ff9800">
      <!--<path d="M12 2C6.47 2 2 6.47 2 12s4.47 10 10 10 10-4.47 10-10S17.53 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-1-13h2v6h-2zm0 8h2v2h-2z" /> -->
      
      <path d="M12 2C6.47 2 2 6.47 2 12s4.47 10 10 10 10-4.47 10-10S17.53 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>

      </svg><br /><br />
        <span id="ketdel"></span>
        <br /><br /><br />
        <button id="nodel" onclick="cancel('batal')" class="cancelbtn">Batal</button>
        &nbsp;&nbsp;
        <button id="yesdel" onclick="cancel('oke')" class="signupbtn">Oke</button>
    </div>
</div>

<!-- modal untuk alert -->
<div class="modalconfirm" id="confirmalert">
    <div class="modalconfirm-content">
  <svg width="48" height="48" viewBox="0 0 24 24" fill="#ff9800">
      <!--<path d="M12 2C6.47 2 2 6.47 2 12s4.47 10 10 10 10-4.47 10-10S17.53 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-1-13h2v6h-2zm0 8h2v2h-2z" /> -->
      
      <path d="M12 2C6.47 2 2 6.47 2 12s4.47 10 10 10 10-4.47 10-10S17.53 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>

      </svg><br /><br />
        <span id="ketalert"></span>
        <br /><br /><br />
        <button id="alertok" class="signupbtn">Oke</button>
    </div>
</div>

<div class="footer" id="foot">Sistem Informasi Data Barang<br />
created by:yandrienlw-2025-email:ri3nlw@yahoo.com<br />
Phone/Wa:08180534365
</div>
<br /><br /><br /><br />

<input type="hidden" id="modalon" value="off">
<div class="modal" id="tabeltagihan">
<div class="modal-content animate">
<table width='90%' border="0" align="center" class="headermodal">
<tr>
<th><div id="titletagihan">Tagihan Kredit</div>
<span onclick="tutuptabel('tabeltagihan')" class="close" title="Close">&times;</span>
</th>
</tr>
</table>

<table class="table" cellpadding="3" width="95%">
<tr>
<th rowspan="2">No</th>
<th rowspan="2">Invoice</th>
<th colspan="3">Riwayat Terakhir</th>
<th rowspan="2" colspan="2">Tunggakan (Bulan)</th>
</tr>
<tr>
<th>Tanggal</th>
<th>Cicilan</th>
<th>Tenor</th>
</tr>
<?php
$no = $from + 1;
while($datakredit = $belumlunas->fetch())
{
//ambil dan pisahkan tgl,bln,thn db
$lastdatepay = new DateTime($datakredit['lastdate']);
if($lastdatepay != ""){
$datepay = $lastdatepay->format("j");
$mounthpay = $lastdatepay->format("n");
$yearpay = $lastdatepay->format("Y");

//hitung bulan tagihannya
$yearcount = date("Y") - $yearpay;
if($yearcount > 0){
$mounthcount = (date("n") + 12) - $mounthpay;
}else {
$mounthcount = date("n") - $mounthpay;

}

?>
<tr>
<td align="center"><?=$no?></td>
<td align="center"><?=$datakredit['invoice']?></td>
<td align="center" style="white-space: nowrap;"><?=date('d-m-Y', strtotime($datakredit['lastdate']))?></td>
<td align="center"><?php if($datakredit['cicilan'] > 0){
echo $datakredit['cicilan']; } else{ echo "DP"; } ?></td>
<td align="center"><?=$datakredit['tenor']?></td>
<td align="center"><?=$mounthcount?></td>
<td class="
<?php
if($mounthcount <= 1){ //hijau
    echo "bhijau";
}
elseif($mounthcount == 2){ //kuning
    echo "bkuning";
}
else{ //merah
    echo "bmerah";
}?>">
</td>
</tr>
<?php
$no++;
} } ?>
</table><br />
<?php include("paging.php"); ?>
<br />
</div>
</div>

<!--bila menggunakan chart.js online
<div class="modal" id="grafik">
<div class="modal-content animate">
  <canvas id="myChart" width="400" height="400"> </canvas>
</div>
</div>
-->


<div class="modal" id="grafik">
<div class="modal-content animate wide">
<div class="close" id="closegrafik" onclick="tutuptabel('grafik')">&times;</div>
<?php
$max_cash = max($total_transaksi);
$max_credit = max($total_credit);
$tinggigrafik = max($max_cash, $max_credit);


//print_r($total_transaksi);
//print_r($total_credit);

//print_r($tanggalarraydb);
//print_r($n_transaksi);

if($nrh > 0 || $nrhc > 0){ ?>
<div class="scrollgragik">
<div class="grafik">
  <div class="sumbu-y">
    <?php for ($ig = $tinggigrafik; $ig >= 0; $ig -= $tinggigrafik / 5) { ?>
      <div class="label-y"><?php echo number_format($ig); ?></div>
    <?php } ?>
  </div>
  <div class="batang-container">
    <?php foreach ($tanggalarraydb as $tgldt) { 
    if($tgldt > 0){ ?>
    <div class="batang-group">
        <div class="batang-wrapper">
      <?php
      //data cash
      if(isset($total_transaksi[$tgldt]))
      { //cek tanggal dan data, jika tidak ada bisa dinolkan batangnya
      if ($total_transaksi[$tgldt] < 10000) { ?>
        <div class="batang bmerah" style="height: <?php echo $total_transaksi[$tgldt] / $tinggigrafik * 200; ?>px;">
        </div>
      <?php } elseif ($total_transaksi[$tgldt] >= 10000 && $total_transaksi[$tgldt] < 50000) { ?>
        <div class="batang bkuning" style="height: <?php echo $total_transaksi[$tgldt] / $tinggigrafik * 200; ?>px;">
        </div>
      <?php } elseif ($total_transaksi[$tgldt] >= 50000 && $total_transaksi[$tgldt] < 100000) { ?>
        <div class="batang bhijau" style="height: <?php echo $total_transaksi[$tgldt] / $tinggigrafik * 200; ?>px;">
        </div>
      <?php } else { ?>
        <div class="batang bbiru" style="height: <?php echo $total_transaksi[$tgldt] / $tinggigrafik * 200; ?>px;">
        </div>
        <?php } }
        
        //data kredit
        if(isset($total_credit[$tgldt])){
        if ($total_credit[$tgldt] < 10000) { ?>
        <div class="batang bmerah bergaris-wrapper" style="height: <?php echo $total_credit[$tgldt] / $tinggigrafik * 200; ?>px;">
            <div class="bergaris"></div>
        </div>
      <?php } elseif ($total_credit[$tgldt] >= 10000 && $total_credit[$tgldt] < 50000) { ?>
        <div class="batang bkuning bergaris-wrapper" style="height: <?php echo $total_credit[$tgldt] / $tinggigrafik * 200; ?>px;">
            <div class="bergaris"></div>
        </div>
      <?php } elseif ($total_credit[$tgldt] >= 50000 && $total_credit[$tgldt] < 100000) { ?>
        <div class="batang bhijau bergaris-wrapper" style="height: <?php echo $total_credit[$tgldt] / $tinggigrafik * 200; ?>px;">
            <div class="bergaris"></div>
        </div>
      <?php } else { ?>
        <div class="batang bbiru bergaris-wrapper" style="height: <?php echo $total_credit[$tgldt] / $tinggigrafik * 200; ?>px;">
            <div class="bergaris"></div>
        </div>
      <?php } } ?>
      
      </div>
      
      <div class="label-x"><?php echo $tgldt; ?></div>

  </div>
  
 <?php } } ?>
</div>

  </div>
</div>
<?php } ?>

<br />

<div class="wrapketbatang">
    <div class="batang bmerah ketbox">
        <span><</span><span>10K</span>
    </div>
     <div class="batang bkuning ketbox" style="width: 22px; height: 22px;">
        <span><</span><span>50K</span>
    </div>
     <div class="batang bhijau ketbox" style="width: 24px; height: 24px;">
        <span><</span><span>100K</span>
    </div>
     <div class="batang bbiru ketbox" style="width: 26px; height:26px;">
        <span>>=</span><span>100K</span>
    </div>
</div>

<br />
<div class="grafik">
    <center>
<span id="bulantahun"></span>&nbsp;-&nbsp;
<input type="hidden" id="hiddenbulan">
<input type="hidden" id="hiddentahun">

<span onclick="next('prev')" id="preview"><i class="hidearr"></i><i class="hidearrg"></i></span>
<a href="javascript:void(0)" onclick="next('reset')">-Reset-</a>
<span onclick="next('next')" id="next"><i class="arrowgreen"></i><i class="arrow"></i></span>
</center><br />
<table class="table" cellpadding="3" width="90%">
<tr>
<th rowspan="2">Tanggal</th>
<th colspan="2">Cash</th>

<th colspan="2" class="arsirtabel">Kredit</th>
<th rowspan="2">Total Transaksi</th>
</tr>
<tr>
<th>&Sigma;n</th><th>Transaksi</th>
<th class="arsirtabel">&Sigma;n</th><th class="arsirtabel">Transaksi</th>
</tr>
<?php
$omsetcashbulanan = 0;
$omsetcreditbulanan = 0;
$sumn_credit = 0;
foreach ($tanggalarraydb as $tgldt) {
if($tgldt > 0){ ?>
<tr><td align="center"><?=$tgldt?></td>
<td align="center">
    <?php
    if(isset($n_transaksi[$tgldt])){
    echo $n_transaksi[$tgldt]; 
    }else { echo "-"; } ?>
</td>
<td align="right">
    <?php
    if(isset($total_transaksi[$tgldt])){
    echo number_format($total_transaksi[$tgldt]);
    
    $valuecash = $total_transaksi[$tgldt];
    $omsetcashbulanan += $valuecash;
    
    }else{ echo "-"; $valuecash = 0; } ?>
</td>
<td align="center" class="arsirtabel">
    <?php
    if(isset($n_credit[$tgldt])){
    echo $n_credit[$tgldt];
    $sumn_credit += $n_credit[$tgldt];
    
    }else { echo "-"; } ?>
</td>
<td align="right" class="arsirtabel">
    <?php
    if(isset($total_credit[$tgldt])){
    echo number_format($total_credit[$tgldt]);
    
    $valuecredit = $total_credit[$tgldt];
    $omsetcreditbulanan += $valuecredit;
    
    }else{ echo "-"; $valuecredit = 0; }?>
</td>

<td align="right">
    <?php
    $omsetharian = $valuecash + $valuecredit;
    echo number_format($omsetharian);
    ?>
</td>

</tr>
<?php }} ?>

<tr>
    <th align="center">Jumlah</th>
    <th><?=$nrh?></th>
    <th align="right"><?=number_format($omsetcashbulanan)?></th>
    <th align="center" class="arsirtabel"><?=$sumn_credit?></th>
    <th align="right" class="arsirtabel"><?=number_format($omsetcreditbulanan)?></th>
    <th align="right"><?=number_format($omsetcashbulanan+$omsetcreditbulanan)?></th>
</tr>
</table>
</div>
</div>
</div>


<div class="modal" id="tabelrekapan">
<div class="modal-content animate">
<table width='90%' border="0" align="center" class="headermodal">
<tr>
<th bgcolor="white"><center style="font-size:120%; color:#c2c2c2; font-family:verdana;">Rekapan Hari Ini</center>
<span onclick="tutuptabel('tabelrekapan')" class="close" title="Close">&times;</span>
</th>
</tr>
</table>

<table class="table" cellpadding="3" width="95%">
<tr>
<th>N_Transaksi : <?=$ntransaksi?></th>
<th>OMSET : Rp <span id="omset"><?=$omset?></span></th>
</tr>
<tr>
    <td>
        Cash : <?=$ncasht?><br />
        Kredit : <?=$ncreditt?><br />
        &nbsp;&nbsp;•DP : <?=$ndpt?><br />
        &nbsp;&nbsp;•Angsuran : <?=$nangsurant?>
    </td>
    <td>
        Cash : Rp <span id="cashomset"><?=$cashomset?></span><br />
        Kredit : Rp <span id="creditomset"><?=$creditomset?></span><br />
        &nbsp;&nbsp;•DP : Rp <span id="dpomset"><?=$dpomset?></span><br />
        &nbsp;&nbsp;•Angsuran : Rp <span id="angsuranomset"><?=$angsuranomset?></span>
    </td>
</tr>
</table><br /><br />
</div>
</div>

<div class="modal" id="settings">
<div class="modal-content animate">
<table width='90%' border="0" align="center" class="headermodal">
<tr>
<th bgcolor="white"><center style="font-size:120%; color:#c2c2c2; font-family:verdana;">Settings</center>
<span onclick="tutuptabel('settings')" class="close" title="Close">&times;</span>
</th>
</tr>
</table>

<table border="0" class="table" cellpadding="3" width="95%">
<tr>
<td colspan="4">
<form method="post" onsubmit="return settings()" action="penjualan.php?kasir=<?=$kasir?>">
<div class="settingswrap">
<div class="settingsleft">
    DP %
    <input type="text" name="newdp" id="ubahdp" value="<?=$newdp?>" style="width:100px;">
    Bunga %
    <input type="text" name="newbunga" id="ubahbunga" value="<?=$newbunga?>" style="width:100px;">
</div>
<div class="settingsright">Nomor Rekening
<textarea name="newrek" id="ubahrek" rows="4"><?=$newrek?></textarea>
</div>
</div>
<br />
<input type="hidden" name="settingson" value="1">
<input type="hidden" name="token" value="<?=$token?>">
<div class="settingswrap">
<div class="settingsleft fullspce">
<span style="color:#c2c2c2; font-size:10px;">
Keterangan:<br />
DP <?=$newdp?>%, Bunga <?=$newbunga?>%
<br />
DP = total harga barang X <?=$newdp?>%
<br />
Total Bunga =
(Total harga barang - DP) X Tenor X <?=$newbunga?>%
 <br />       
Total harga kredit =
Total harga barang + Total Bunga
<br />
Total Angsuran =
Total harga barang - DP + Total Bunga
<br />
Angsuran = Total Angsuran / Tenor
</span>
</div>
<div class="settingsright overlay-right">
<button style="width:63px; margin-top:2px;">Save</button>
</div>
</div>
</form>
</td>
</tr>
</table>

<table class="table tdcenter" cellpadding="3" width="95%">
<tr>
    <td colspan="4" align="center">
Riwayat Perubahan
    </td>
</tr>
<tr>
<td>Tanggal</td><td>DP%</td><td>Bg%</td>
<td>Rekening</td></tr>
<?php
foreach ($allData as $viewset){ ?>
<tr>
<td><?=$viewset['tglubah']?></td>
<td><?=$viewset['dp']?></td>
<td><?=$viewset['bunga']?></td>
<td><?=$viewset['rek']?></td>
</tr>
<?php } ?>
</table><br /><br />
</div>
</div>

<script>

/* perintah click dengan keyboard enter */
var input = document.getElementById("datacari");
input.addEventListener("keypress", function(event) {
	if (event.key === "Enter") {
	event.preventDefault();
	document.getElementById("submitcari").click();
	}
});

//listens for focus on textbox
document.getElementById('datacari').addEventListener("focus", changeDivColor);

//this is fired when the textbox is focused
function changeDivColor(){
  document.getElementById('fsearch').style.borderColor = "white";
}

/*
//listens for blur on textbox
 document.getElementById('datacari').addEventListener("blur", revertDivColor);

//this is fired when the textbox is no longer focused
function revertDivColor(){
  document.getElementById('fsearch').style.borderColor = null;
}
*/

    document.getElementById("code").focus();
    
    //format harga rekapan
var i = "";
let omset = document.getElementById("omset").innerHTML;
let creditomset = document.getElementById("creditomset").innerHTML;
let cashomset = document.getElementById("cashomset").innerHTML;
let dpomset = document.getElementById("dpomset").innerHTML;
let angsuranomset = document.getElementById("angsuranomset").innerHTML;
formatharga(omset, "omset");
formatharga(creditomset, "creditomset");
formatharga(cashomset, "cashomset");
formatharga(dpomset, "dpomset");
formatharga(angsuranomset, "angsuranomset");

    <?php
    if($kreditmacet > 0){ ?>
    document.getElementById("notifikasikredit").innerHTML = "Tagihan Kredit (<?=$kreditmacet?> macet)";
    
    document.getElementById("titletagihan").innerHTML = "Tagihan Kredit (<?=$kreditmacet?> macet)";
    <?php }
    
    if ($tharga == 0) { ?> document.getElementById("paymenton").style.display = "none";
    document.getElementById("cancelpay").style.display ="none";
    document.getElementById("radio").style.display ="none";
    document.getElementById("tfspan").style.display = "none";
    //document.getElementById("fsearch").style.display = "";
    <?php }
    
    //jika ada pencarian atau pembayaran
    if($cari!="" && $opendatapenjualan=="on" || $transaksi=="closed") { 
    if($bayar == "Kredit" || $bayar == "Credit-Tf"){ ?>
    //pencarian credit
    document.getElementById("cash").disabled = true;
    document.getElementById("credit").checked = true;
    
    document.getElementById("radio").style.display = "none"; //sembunyikan menu pembayaran
    
    <?php if($bayar == "credit-tf"){ ?> document.getElementById("tf").checked = true; <?php } ?>
    
    <?php }else { ?>
    //pencarian cash
    document.getElementById("radio").style.display ="none";
    document.getElementById("tfspan").style.display = "none";
    
    //matikan realtime
    document.getElementById("timenow").value = 1;
    
    //tampilkan waktu db utk cash
        document.getElementById('dt').textContent = '<?=$tanggal?>';
        
        //sdgkan set time utk data kredit ada di fungsi closepay,openpay dan selectkreditbayar di bagian bawah script


    <?php } ?>
    //document.getElementById("addbuy").disabled = true;
    //document.getElementById("addbuy").style.background = "#c2c2c2";
    //document.getElementById("addbuy").style["pointer-events"] = "none";
    document.getElementById("buyer").setAttribute("readonly", true);
    document.getElementById("boxitem").style.display = "none";
    document.getElementById("cancelpay").style.display = "none";
    document.getElementById("pay").innerHTML = "DONE";
    <?php } 

    //jika pembayaran perdana selesai 
    if ($transaksi == "closed" && $opendatapenjualan == "off") { ?>
        //document.getElementById("addbuy").disabled = true;
        //document.getElementById("addbuy").style.background = "#c2c2c2";
        //document.getElementById("addbuy").style["pointer-events"] = "none";
        document.getElementById("pay").innerHTML = "DONE";
        document.getElementById("cancelpay").innerHTML = "REVISI";
        document.getElementById("cancelpay").style.display = "";
	document.getElementById("radio").style.display = "none";
	document.getElementById("tfspan").style.display = "none";
        document.getElementById("paymenton").focus();
    <?php }

    //jika ada perubahan item
    if (isset($_GET['row'])) { ?>
        /*select row data dan fokus ke data yang dieksekusi*/
var s = "<?=$_GET['row']?>"; 
var n = <?=$n?>;
if(s > n){ s = n; }
document.getElementById(s).scrollIntoView({behaviour:'smooth'});
document.getElementById(s).style.background="rgba(255,200,0,0.2)";

        
    <?php }
    
if($n>0){
?>
//format harga, subtotal dan total harga barang
for(i=1;i<=<?=count($kode)?>; i++)
{
let harga = document.getElementById("harga"+i).innerHTML;
let hargadiskon = document.getElementById("hargadiskon"+i).innerHTML;
let subtotal = document.getElementById("subtotalrow"+i).innerHTML;
formatharga(harga, "harga");
if(hargadiskon != '') { formatharga(hargadiskon, "hargadiskon"); }
formatharga(subtotal, "subtotalrow");
}
var i= "";
    let  tharga = document.getElementById("tharga").innerHTML;
    
    document.getElementById("big").innerHTML = tharga;
    let  big = document.getElementById("big").innerHTML;
    formatharga(big, "big");
    formatharga(tharga, "tharga");

<?php }

//set parameter payment
if((isset($_GET['bayar']) || $cari!="") && !isset($_GET['endbuying'])){ 
if(isset($_GET['bayar'])) { $bayar = $_GET['bayar']; }
if($bayar == "Cash-Tf"){ ?>
document.getElementById("norek").innerHTML = "<?=$newrek?>";
document.getElementById("cash").checked = true;
document.getElementById("tf").checked = true;
<?php } if($bayar == "Credit-Tf"){ ?>
document.getElementById("norek").innerHTML = "<?=$newrek?>";
document.getElementById("credit").checked = true;
document.getElementById("tf").checked = true;
<?php } if($bayar == "Cash"){ ?>
document.getElementById("cash").checked = true;
<?php }if($bayar == "Credit"){ ?>
document.getElementById("credit").checked = true;
<?php }
}

if(isset($_GET['paymenton']) || $cari!=""){ ?>
    var i= "";
    let  uangbayar = document.getElementById("notauangbayar").innerHTML;
    let  uangsisa = document.getElementById("notauangsisa").innerHTML;
    
    <?php
    if($bayar != "Cash" && $bayar != "Cash-Tf" ){ ?>
    let  hargacredit = document.getElementById("notathargakredit").innerHTML;
    let  hargadp = document.getElementById("notadp").innerHTML;
    let  hargaangsuran = document.getElementById("notaangsuran").innerHTML;
    formatharga(hargacredit, "notathargakredit");
    formatharga(hargadp, "notadp");
    formatharga(hargaangsuran, "notaangsuran");
    <?php } ?>
    
    formatharga(uangbayar, "notauangbayar");
    formatharga(uangsisa, "notauangsisa");
<?php } ?>

function formatharga(harga, id){
if(harga.length > 3){ 
var j, k, l, m;
var n = harga.length - 1;
var sisa = n % 3;
var titik = n / 3;
if(titik < 2 || titik >= 4){ //ribuan dan triliunan
j=harga.slice(0,sisa+1); k=harga.slice(sisa+1);
let hasil = [j, k];
document.getElementById(id+i).innerHTML = hasil.join(".");
}
else if(titik < 3){ //jutaan
j=harga.slice(0,sisa+1); k=harga.slice(sisa+1,sisa+4); l=harga.slice(sisa+4,sisa+7);
let hasil = [j, k, l];
document.getElementById(id+i).innerHTML = hasil.join(".");
}
else if(titik < 4){ //miliyaran
j=harga.slice(0,sisa+1); k=harga.slice(sisa+1,sisa+4); l=harga.slice(sisa+4,sisa+7); m=harga.slice(sisa+7,sisa+10);
let hasil = [j, k, l, m];
document.getElementById(id+i).innerHTML = hasil.join(".");
} 
}else {
    document.getElementById(id+i).innerHTML = harga;
}
}

function callformat(){ 
let harga = document.getElementById("inputcash").value;
let tharga = document.getElementById("big").innerHTML;

harga = harga.replace(/\D/g, ''); //ambil angka saja, titik dll dihilangkan

if(harga[0] === '0'){ //buang angka 0 di awal
    harga = harga.slice(1);
}

tharga = parseInt(tharga.replace(/[.]/g, ''));
if(harga > tharga){
var i = "";
var sisauang = harga - tharga;
document.getElementById("kembalisisa").innerHTML = "SISA : ";
document.getElementById("sisauang").innerHTML = sisauang;
sisauang = document.getElementById("sisauang").innerHTML;
formatharga(sisauang,"sisauang");
    
}else {
    document.getElementById("kembalisisa").innerHTML = "";
    document.getElementById("sisauang").innerHTML = ""; }

if(harga.length > 3){ 
var j, k, l, m;
var n = harga.length - 1;
var sisa = n % 3;
var titik = n / 3;
if(titik < 2 || titik >= 4){ //ribuan dan triliunan
j=harga.slice(0,sisa+1); k=harga.slice(sisa+1);
let hasil = [j, k];
document.getElementById("inputcash").value = hasil.join(".");
}
else if(titik < 3){ //jutaan
j=harga.slice(0,sisa+1); k=harga.slice(sisa+1,sisa+4); l=harga.slice(sisa+4,sisa+7);
let hasil = [j, k, l];
document.getElementById("inputcash").value = hasil.join(".");
}
else if(titik < 4){ //miliyaran
j=harga.slice(0,sisa+1); k=harga.slice(sisa+1,sisa+4); l=harga.slice(sisa+4,sisa+7); m=harga.slice(sisa+7,sisa+10);
let hasil = [j, k, l, m];
document.getElementById("inputcash").value = hasil.join(".");
} 
}
else{ 
    document.getElementById("inputcash").value = harga;
    return;
}


}

function clearbgrow(r) {
if(document.getElementById("canceledit").style.display == "none"){
            document.getElementById(r).style.background = null;
}
else { return; }
}


//cara lain mengubah format tanggal ke indonesia tanpa array ****

<?php
if(isset($bulanbar)){ //bulan tahun yg dipilih ?>
var bulan = <?=$bulanbar?> - 1;
var tahun = <?=$tahunrekap?>;
var tanggalnow = new Date(<?=$tahunrekap?>, bulan);

opentabel('grafik');

<?php }else{ //bulan tahun sekarang ?>
var tanggalnow = new Date();
var bulan = tanggalnow.getMonth();
var tahun = tanggalnow.getFullYear();
<?php } ?>

var formatIndonesia = new Intl.DateTimeFormat('id-ID', { month: 'long', year: 'numeric' }); //ubah ke format indonesia

var bulantahunSekarang = formatIndonesia.format(tanggalnow);

//tulis ke tabel grafik
document.getElementById("bulantahun").innerHTML = bulantahunSekarang;
document.getElementById("hiddenbulan").value = bulan;
document.getElementById("hiddentahun").value = tahun;
//-------------------------------------


    function updateDateTime() {
        const clockElement = document.getElementById('dt');
        const currentTime = new Date();



        // Define arrays for days of the week and months to format the day and month names.
        const daysOfWeek = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        const dayOfWeek = daysOfWeek[currentTime.getDay()];

        const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Augustus', 'September', 'Oktober', 'November', 'Desember'];

        const month = months[currentTime.getMonth()];

        const day = currentTime.getDate();
        const year = currentTime.getFullYear();

        // Calculate and format hours (in 12-hour format), minutes, seconds, and AM/PM.
        let hours = currentTime.getHours();
        const ampm = hours >= 12 ? 'PM' : 'AM';
        hours = hours % 12 || 12;
        const minutes = currentTime.getMinutes().toString().padStart(2, '0');
        const seconds = currentTime.getSeconds().toString().padStart(2, '0');

        // Construct the date and time string in the desired format.
        const dateTimeString = `${dayOfWeek}, ${day} ${month} ${year}, ${hours}:${minutes}:${seconds} ${ampm}`;
        
        //current time aktif jika tidak ada pencarian atau pembayaran
        let updatecurrentime = document.getElementById("timenow").value;
        if(updatecurrentime == 0){
            clockElement.textContent = dateTimeString;
        }
        document.getElementById("gate").value = dateTimeString;
    }
    
    
    // Update the date and time every second (1000 milliseconds).
    setInterval(updateDateTime, 1000);

    // Initial update.
    updateDateTime();
    


// Get the modal for print or searchbox or notifikasi kredit
var modalprint = document.getElementById('print');
var modalnotif = document.getElementById('tabeltagihan');
var modalrekapan = document.getElementById('tabelrekapan');
var modalgrafik = document.getElementById('grafik');
var modalsettings = document.getElementById('settings');



// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
if (event.target == modalprint) {
    modalprint.style.display = "none";
}

if (event.target == modalnotif) {
    tutuptabel('tabeltagihan');
}

if (event.target == modalrekapan) {
    tutuptabel('tabelrekapan');
}

if (event.target == modalgrafik) {
    tutuptabel('grafik');
}
if (event.target == modalsettings) {
    tutuptabel('settings');
}
}


function finish() {
window.location.replace("penjualan.php?kasir=<?= $kasir ?>");
}
    
    function paymenton(buttonpay){ 
    let paym = document.getElementById("pay").innerHTML;
    var datime = document.getElementById("gate").value;
    if (paym == "DONE") {
document.getElementById('printtoko').innerHTML = "Toko Mahal";
document.getElementById('printalamat').innerHTML = "Jl.Taranggaha, Kambaniru";
document.getElementById('printnohp').innerHTML = "HP/Wa : 0818053423654";
document.getElementById('printdatime').innerHTML = datime;
document.getElementById('printkasir').innerHTML = "Kasir : <?=$kasir?>";
document.getElementById('printbuyer').innerHTML = "Pembeli : <?=$pembeli?>";
document.getElementById('printinv').innerHTML = "Invoice : <?=$inv?>";
document.getElementById('printcontent').innerHTML = document.getElementById('listitem').innerHTML;
document.getElementById('print').style.display = "block";
window.print();
        }
    else {
    var cash = document.getElementById("cash");
	var credit = document.getElementById("credit");
	var tf = document.getElementById("tf");
	var norek = document.getElementById("norek");
	let incash = document.getElementById("inputcash");
	let tenor = document.getElementById("tenor");
	
	if(cash.checked == true){
	document.getElementById("detailkredit").style.display = "none";
        var payway = "Cash";
        if(tf.checked == true){ payway = "Cash-Tf"; }
        var status = "lunas";
        
        
        document.getElementById("big").innerHTML = <?=$tharga?>;
        let big = document.getElementById("big").innerHTML;
        formatharga(big, "big");
        
    }
	if(credit.checked == true){
        var payway = "Kredit";
        if(tf.checked == true){ payway = "Credit-Tf"; }
        tenor.style.display = "";
        if(buttonpay != 'nocash' && buttonpay != 'yes'){
        tenor.value = 0;
        tenor.focus(); }
        var status = "cicil";
        }
        else { tenor.style.display = "none"; }

    if(tf.checked == true){
	incash.style.display = "none"; 
	document.getElementById("kembalisisa").innerHTML = "";
	document.getElementById("sisauang").innerHTML = "";
	norek.innerHTML = "<?=$newrek?>";
	}else if(buttonpay != 'yes') {
        norek.innerHTML = "";
        incash.style.display = "";
        incash.focus();
	}
	
	//jika box cas off dan bukan tf
	if(incash.style.display == "none" && tf.checked == false){ 
	if(cash.checked == true || tenor.value > 0){
	if(buttonpay == 'yes'){ 
	incash.style.display = "";
	incash.focus(); }
	return;
	} else{ tenor.focus(); return; }
	}else { 
	    if(buttonpay == 'yes'){
	        <?php
	   if($dp == 0){ ?>
	  	if(credit.checked == true && tenor.value == 0){
	  	    tenor.focus(); return;
	  	}
	  	<?php } ?>
	  	
	    big = document.getElementById("big").innerHTML;
	    big = parseInt(big.replace(/[.]/g, ''));
            if(tf.checked == false) { //Jika bayar cas
            incashcontent = incash.value;
	    incashcontent = incashcontent.replace(/[.]/g, ''); //hilangkan titik untuk nilai sebenarnya
            } else { //jika TF
                incashcontent = big; 
                /*if(credit.checked == true){
                    if(tenor.value < 1){
                        tenor.focus(); return;
                    }
                }*/
            }
	    if(incashcontent < big){
	    incash.focus();
	        return;
	    }
	    exec(incashcontent, status, payway, datime); 
	    }
	    else { 
	    if(buttonpay == 'no'){
	    incash.style.display = "none";
	    document.getElementById("kembalisisa").innerHTML = "";
	    document.getElementById("sisauang").innerHTML = "";
	    }
	    return; }
	}
    }
    
    }

    function exec(money, ket, paymode, tgltime){
	 var beli = document.getElementById("buyer").value;
	 
	 if(credit.checked == true){
	     <?php
	     if($cari!=""){
	         $datacari="&payment=Kredit&cari=".$cari;
	     }else { $datacari=""; } ?>
	 <?php
	 if($dp!=0){ ?>
	 var dpcicil = document.getElementById("buffer").value; //nilai index pembayaran
	 <?php } else{ ?>
	 var dpcicil = 0;
	 <?php } ?>
	 
	 var tenor = document.getElementById("tenor").value;
	 tenor = "<?=$datacari?>&tenor="+tenor+"&dpcicil="+dpcicil;
	 
	 }else { var tenor =""; }
	 
	 window.location.replace("penjualan.php?kasir=<?= $kasir ?>&inv=<?= $inv ?>&bayar="+paymode+"&pembeli=" + beli + "&datime="+tgltime+"&tharga=<?= $tharga ?>&money="+money+"&ket="+ket+"&paymenton=execute"+tenor);
	}
	
	//detail kredit
	function viewkredit(tenor){
	if(tenor > 0){
	
	//cek nilai jk tdk ada nolkan
	var tharga = <?=$tharga?> || 0;
	var newdp = <?=$newdp?> || 0;
	var newbunga = <?=$newbunga?> || 0;
	
	var dp = Math.round(tharga*newdp/100 );
    var totalbunga = Math.round((tharga - dp)*tenor*newbunga/100);
    var thargakredit = tharga + totalbunga;
    var totalangsuran = tharga - dp + totalbunga;
    var angsuran = Math.round(totalangsuran/tenor);
    
    document.getElementById("big").innerHTML = dp;
    let  big = document.getElementById("big").innerHTML;
    formatharga(big, "big");
    
    document.getElementById("detailkredit").style.display = "";
    
    document.getElementById("inidp").innerHTML = "DP ";
    document.getElementById("dp").innerHTML = dp;
    let  hargadp = document.getElementById("dp").innerHTML;
    formatharga(hargadp, "dp");
    
    document.getElementById("inibunga").innerHTML = "| Bunga ";
    document.getElementById("totalbunga").innerHTML = totalbunga;
    let  bunga = document.getElementById("totalbunga").innerHTML;
    formatharga(bunga, "totalbunga");
    
    document.getElementById("inihargakredit").innerHTML = "| Harga Kredit ";
    document.getElementById("thargakredit").innerHTML = thargakredit;
    let  hargakredit = document.getElementById("thargakredit").innerHTML;
    formatharga(hargakredit, "thargakredit");
    
    document.getElementById("initotalangsuran").innerHTML = "| Total Angsuran ";
    document.getElementById("totalangsuran").innerHTML = totalangsuran;
    let  hargaangsuran = document.getElementById("totalangsuran").innerHTML;
    formatharga(hargaangsuran, "totalangsuran");
    
    document.getElementById("iniangsuran").innerHTML = "| Angsuran per Bulan ";
    document.getElementById("angsuran").innerHTML = angsuran;
    let  angsuranbulanan = document.getElementById("angsuran").innerHTML;
    formatharga(angsuranbulanan, "angsuran");
    
    }else { return false; }
	}

    function pushdata() {
        var cod = document.getElementById("code").value;
        var qty = document.getElementById("kuan").value;
        if (cod == "") {
            document.getElementById("code").focus();
            return;
        }
	if (qty < 1) {
            document.getElementById("kuan").focus();
            return;
        }	

        if (document.getElementById("go").innerHTML == "Save") {
            var id = document.getElementById("addbuy").value;
            var row = document.getElementById("canceledit").value;
            var extra = "&upcodeid=" + id + "&row=" + row;

        } else {
            var extra = "";
        }

        window.location.replace("penjualan.php?kasir=<?= $kasir ?>&kode=" + cod + "&kuantitas=" + qty + "&push=on" + extra);
    }

    function delelete(id, ind, kode, kuan, i, idname, apa) {
    
        /*if (confirm("Data nomor: " + i + " akan dihapus!") == true) {*/
        
    if(apa === 'oke'){
        window.location.replace(window.location.href + "&delcodeid=" + id+"&ind="+ind+"&kod="+kode+"&kuan="+kuan);
    }
    else if(apa === 'batal'){
        document.getElementById("confirmdel").style.display = "none";
        
    //kembalikan onclick confirmasi modal
    document.getElementById("nodel").onclick = function(){
        cancel('batal'); };
    document.getElementById("yesdel").onclick = function(){
        cancel('oke'); };
        
    document.getElementById(idname).style.background = null; 
    return;
    }
    else{
    document.getElementById(idname).style.background = "#f44336"; 
    document.getElementById("ketdel").innerHTML = "Data baris ke-" + i + " akan dihapus!";
    //ubah onclick confirmasi modal
    document.getElementById("nodel").onclick = function(){
        delelete(id, ind, kode, kuan, i, idname, 'batal'); };
    document.getElementById("yesdel").onclick = function(){
        delelete(id, ind, kode, kuan, i, idname, 'oke'); };
        
    document.getElementById("confirmdel").style.display = "block";
	}
    }

    function edit(id, kode, q, i) {
        var addsave = document.getElementById("addbuy");
        var canedit = document.getElementById("canceledit");
        
        document.getElementById("code").value = kode;
        document.getElementById("kuan").value = q;
        document.getElementById("go").innerHTML = "Save";
        
        addsave.value = id;
        canedit.style.display = "";
        
        document.getElementById("canceledit").value = i; 
        document.getElementById(i).style.background = "rgba(255,200,0,0.2)";
	num = i.replace('row',''); 
	for(a=1; a <= <?=$n?>; a++){
	if(a != num){
	document.getElementById("row"+a).style.background = null;
	} }
	
	// disableOnclickExceptCurrent
    var allbuttons = document.querySelectorAll('button, input[type="button"]');
    for (var d = 0; d < allbuttons.length; d++) {
        if (allbuttons[d] !== addsave && allbuttons[d] !== canedit) {
            allbuttons[d].disabled = true;
        }
    }
	
        document.getElementById("kuan").focus();
    }

    function cancel(apa) { 
        var cancel = document.getElementById("cancelpay").innerHTML;
	if(cancel == "Cancel")
	{
	/* if (confirm("Seluruh data pembelian barang akan dihapus!") == true) {
            window.location.replace(window.location.href + "&endbuying=<?=$inv?>");
        } else { return; }
        
    */
    if(apa === 'oke'){
        window.location.replace(window.location.href + "&endbuying=<?=$inv?>");
    }
    else if(apa === 'batal'){
        document.getElementById("confirmdel").style.display = "none";
        return;
    }
    else{
    
    document.getElementById("ketdel").innerHTML = "Seluruh data pembelian barang akan dihapus!";
    document.getElementById("confirmdel").style.display = "block";
	}
	}
	
	else{ 
	/*if (confirm("Pembayaran akan dihapus dan tidak dapat dikembalikan lagi!") == true) { */
	if(apa === 'oke'){
        <?php if($cari !=""){ ?> 
	    var pembelibrg = "&pembeli=<?=$pembeli?>";
	    <?php }else { ?>
	    var pembelibrg = ""; <?php } ?>
	    
        window.location.replace(window.location.href + "&revisi=<?=$inv?>"+pembelibrg);
	    
    }
    else if(apa === 'batal'){
        document.getElementById("confirmdel").style.display = "none";
        return;
    }else{
    
    document.getElementById("ketdel").innerHTML = "Pembayaran akan dihapus dan tidak dapat dikembalikan lagi!";
    document.getElementById("confirmdel").style.display = "block";
	}
    }
}


function canceledit(row){
document.getElementById("code").value = "";
document.getElementById("code").focus();
document.getElementById("kuan").value = 1;
document.getElementById("go").innerHTML = "Push";
document.getElementById("canceledit").style.display = "none";
document.getElementById(row).style.background = null;

// enableOnclick() {
    var allbuttons = document.querySelectorAll('button, input[type="button"]');
    for (var e = 0; e < allbuttons.length; e++) {
        allbuttons[e].disabled = false;
    }
}

//filter masukan selain angka
function justnumber(qty){
let disc = document.getElementById(qty).value;
    
    disc = disc.replace(/\D/g, '');
    
    if(disc[0] === '0'){ //hapus angka 0 di awal
        disc = disc.slice(1);
    }
    
    document.getElementById(qty).value = disc
    return;
}

/* perintah click dengan keyboard enter */
var input = document.getElementById("code");
var input2 = document.getElementById("kuan");
input.addEventListener("keypress", function(event) {
	if (event.key === "Enter") {
	event.preventDefault();
	document.getElementById("addbuy").click();
	}
});
input2.addEventListener("keypress", function(event) {
	if (event.key === "Enter") {
	event.preventDefault();
	document.getElementById("addbuy").click();
	}
});

/* pencarian data tanpa form dengan mempertahankan nilai userpage seperti foto dan tombol aksi */
function caridata(){
var data = document.getElementById("datacari").value;
var pilihinvoice = document.getElementById("invcash");
if(data == "") { document.getElementById("datacari").focus(); return false; }
if(pilihinvoice.checked == true){
    var bayaran = "&payment=Cash";
}else { var bayaran = "&payment=Kredit"; }

window.location.replace("penjualan.php?kasir=<?=$kasir?>&cari="+data+"&searching=on"+bayaran);
}
function tutupcari(){
window.location.replace("penjualan.php?kasir=<?= $kasir ?>&searching=closed");
}

//menu view
function menu() {
document.getElementById("myDropdown").classList.toggle("show");

var m = document.getElementById("menuhp");
m.classList.add("menux");
setTimeout(function (){ m.classList.remove("menux"); },500);
}

//klik dimana sj tutup menu

document.addEventListener("click", function(event){ if(!document.getElementById("myDropdown").contains(event.target) && event.target.id !== "menuhp"){
    if(document.getElementById("modalon").value == "off"){ //menu tdk tertutup jk modal trbuka
    
    document.getElementById("myDropdown").classList.toggle("show", false);

    }
    }});


function searchinv(){
    document.getElementById("menuhp").click();
    document.getElementById('fsearch').style.display = "block";
}

function selectkreditbayar(pilihan){
    let pilihdp = document.getElementById("cdp");
    let buffer = document.getElementById("buffer");
    let pilihangsurbuffer = document.getElementById("cangsur"+buffer.value);

    
    if(pilihan == '0'){ 
        pilihdp.classList.add("kreditselector");
        if(buffer.value != 0){
        pilihangsurbuffer.classList.remove("kreditselector");
        }

    //tampilkan waktu db
        document.getElementById('dt').textContent = document.getElementById("araytanggal"+pilihan).value;
        
        document.getElementById("big").innerHTML = <?=$dp?>;
        

    let bigangka = document.getElementById("big").innerHTML;
    formatharga(bigangka, "big");
    
    let amoney = document.getElementById("amoney"+pilihan).value;
    let asmoney = document.getElementById("asmoney"+pilihan).value;
    formatharga(amoney, "notauangbayar");
    
        //sisa pembayaran tampil jika ada
    if(asmoney > 0){
    formatharga(asmoney, "notauangsisa");
    document.getElementById("sisapembayaran").style.display = "";
    }else{
        document.getElementById("sisapembayaran").style.display = "none";
    }
    
    //metode bayar
    let abayar = document.getElementById("abayar"+pilihan).value;
    if(abayar === "Credit-Tf"){
    document.getElementById("carabayar").innerHTML = "Bayar DP-Tf";
    }else{
        document.getElementById("carabayar").innerHTML = "Bayar DP";
    }
    
    
    closepay();
    }
    else {
        let angsurcurrent = document.getElementById("newangsuran").value;
        if(pilihan > angsurcurrent){
        pilihan = angsurcurrent;
        }
    
    let pilihangsur = document.getElementById("cangsur"+pilihan);

    pilihdp.classList.remove("kreditselector"); //hpus clas dp
    
    if(buffer.value != 0){
    pilihangsurbuffer.classList.remove("kreditselector"); //hpus clas angsur lama
    }
    pilihangsur.classList.add("kreditselector");//tambah clas ke angsur yg dipilih
    
    buffer.value = pilihan;//ganti buffer dg pilihan baru
    
    let cicilankredit = document.getElementById("cicilankredit").value;
    
    if(pilihan < angsurcurrent || pilihan == cicilankredit){
    //tampilkan waktu db
        document.getElementById('dt').textContent = document.getElementById("araytanggal"+pilihan).value;
        document.getElementById("big").innerHTML = <?=$angsuran?>;
        

    let bigangka = document.getElementById("big").innerHTML;
    formatharga(bigangka, "big");
    
    let amoney = document.getElementById("amoney"+pilihan).value;
    let asmoney = document.getElementById("asmoney"+pilihan).value;
    formatharga(amoney, "notauangbayar")
    
    //sisa pembayaran tampil jika ada
    if(asmoney > 0){
    formatharga(asmoney, "notauangsisa");
    document.getElementById("sisapembayaran").style.display = "";
    }else{
        document.getElementById("sisapembayaran").style.display = "none";
    }

        //metode bayar
    let abayar = document.getElementById("abayar"+pilihan).value;
    if(abayar === "Credit-Tf"){
    document.getElementById("carabayar").innerHTML = "Bayar Angsuran "+pilihan+" -Tf";
    }else{
        document.getElementById("carabayar").innerHTML = "Bayar Angsuran "+pilihan;
    }
    
    closepay();
        
    }else {
        openpayangsuran();
    }
    
    }
    
}
<?php
// jika dp sudag dibyarkan maka lanjut ke bayar angsuran
if($dp != 0){ ?>
selectkreditbayar('<?=$cicil?>')
<?php } ?>

function openpayangsuran(){
    
document.getElementById("timenow").value = 0; //aktifkan currentime

document.getElementById("big").innerHTML = <?=$angsuran?>;
    let big = document.getElementById("big").innerHTML;
    formatharga(big, "big");
let tfcheck = document.getElementById("tf");
if(tf.checked == false){
document.getElementById("inputcash").style.display = "";
}
document.getElementById("tfspan").style.display = "";
document.getElementById("pay").innerHTML = "BAYAR";
document.getElementById("inputcash").focus();
document.getElementById("pembayaranmoney").style.display = "none";
document.getElementById("sisapembayaran").style.display = "none";


}

function closepay(){
document.getElementById("timenow").value = 1; //matikan realtime
document.getElementById("inputcash").style.display = "none";
document.getElementById("tfspan").style.display = "none";
document.getElementById("pembayaranmoney").style.display = "";


if(tf.checked == true){
    tf.checked = false;
    document.getElementById("norek").innerHTML = "";
}
document.getElementById("pay").innerHTML = "DONE";
}

function exitpage(){
window.location.replace("login.php?logout=on");
}

function gudang(){
window.location.replace("index.php?kasir=<?=$kasir?>");
}

function opentabel(open){
    if(!document.getElementById('myDropdown').classList.contains('show')){
    document.getElementById('myDropdown').classList.toggle('show', true);
    }
    
    document.getElementById(open).style.display = "block";
    document.getElementById("modalon").value = "on";
}

function tutuptabel(tutup){
document.getElementById(tutup).style.display = "none";
setTimeout(function(){
document.getElementById("modalon").value = "off";
}, 50);
}

/* bila menggunakan chart.js
  // kode untuk membuat grafik
  var ctx = document.getElementById('myChart').getContext('2d');
var chart = new Chart(ctx, {
  type: 'line',
  data: {
    labels: <?php //echo json_encode($tanggalarray); ?>,
    datasets: [{
      label: 'Total Transaksi',
      data: <?php //echo json_encode($total_transaksi); ?>,
      backgroundColor: 'rgba(255, 99, 132, 0.2)',
      borderColor: 'rgba(255, 99, 132, 1)',
      borderWidth: 1
    }]
  },
  options: {
    scales: {
      yAxes: [{
        ticks: {
          beginAtZero: true
        }
      }]
    }
  }
}); */

function next(switchh){
var bulan = document.getElementById("hiddenbulan").value;
var tahun = document.getElementById("hiddentahun").value;

if(switchh === "prev"){
    if (bulan === 0) {
      bulan = 11;
      tahun--;
    } else {
      bulan--;
    }
}else if(switchh === "next"){
    if (bulan === 11) {
      bulan = 0;
      tahun++;
    } else {
      bulan++;
    }
}else {
    if(bulan == new Date().getMonth() && tahun == new Date().getFullYear()){ //jng reset jika sama
        return;
    }
    bulan = new Date().getMonth();
    tahun = new Date().getFullYear();
}

window.location.replace("penjualan.php?kasir=<?=$kasir?>&switchgrafik=on&bulan="+bulan+"&tahun="+tahun);
}

//utk paging data kredit
function paging(nx, ira, mx) {
window.location.replace("penjualan.php?kasir=<?= $kasir ?>&hal="+nx+"&ira="+ira+"&mx="+mx);
}
<?php
if(isset($_GET['hal'])){
    echo "opentabel('tabeltagihan');";
}

//tampilkan settings setelah diupdate
if(isset($settingdone)){
    echo "opentabel('settings');";
}

?>

function settings(){
let ubahdp = document.getElementById("ubahdp").value;
let ubahbubga = document.getElementById("ubahbunga").value;
let ubahrek = document.getElementById("ubahrek").value;

if(ubahdp == "<?=$newdp?>" && ubahbubga == "<?=$newbunga?>" && ubahrek == "<?=$newrek?>"){
    document.getElementById("ketalert").innerHTML = "Tidak ada data yang diubah!";
    document.getElementById("confirmalert").style.display = "block";
    document.getElementById("alertok").onclick = function(){
        document.getElementById("confirmalert").style.display = "none";
    }
        return false;
    }
}
</script>
</body>
</html>
<?php
} catch (ErrorException $e) {
    //tanpilkasn pesan error yg lbh umum
    echo "TERJADI KESALAHAN!";
    echo "&#128532;";
    echo "<br><a href='penjualan.php?kasir=<?= $kasir ?>'>RESET Page</a>";
}
?>