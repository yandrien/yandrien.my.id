<?php
//require_once 'tutup_errorreport.php';

session_start();
include "dbkon.php";

//batasi fitur selain admin
if(isset($usertype)){
    if($usertype == "kasir"){
        $limitfitur = "on";
    }
}

//pertahankan view foto
if(isset($_GET['photo'])){ $photo="&photo=on"; }else { $photo=""; }

//pertahankan row tabel untuk difokuskan after update/delete
if(isset($_GET['row'])){ $row="&row=".$_GET['row']; }else { $row=""; }

//operasi input dan update data
if(isset($_GET['operasi'])!='')
{
$imgholderror = $_POST['imgholderror'];
$kode=$_POST['kode'];
$nama=$_POST['nama'];
$daftar=$_POST['daftar'];
$stok=$_POST['stok'];
$instok=$_POST['instok'];
if(empty($stok)){ $stok = 0; $instok = 0;}
$update=$_POST['tgl'];
$serial=$_POST['serial'];
$pricedot=$_POST['price'];
$price=str_replace(".", "", $pricedot);
$disc=$_POST['disc'];
$imagep = $_FILES['foto']['name'];
$image = $_FILES['foto']['tmp_name']; 
if(!empty($image)){ $foto = file_get_contents($image); } else { $foto=""; }
$images = $_FILES['foto']['size'];
$lower=strtolower($nama);//hanya mencoba karna sempat pencarian membedakan hruf kecil besar
//input data
if(isset($_GET['input'])=='save')
{
	//hold view tombol act or not
 	if(isset($_GET['delokorupok17912457381x9djghzmtqhjrlckawutso16'])) {
	 $act="&delokorupok17912457381x9djghzmtqhjrlckawutso16=".$_GET['delokorupok17912457381x9djghzmtqhjrlckawutso16']; }
	else { $act=""; }
	
	$hold1="errorfailedduplicateddata=needtorevitiondatainput";
	$address="location:index.php?".$hold1.$photo.$act."&kode=".$kode."&nama=".$nama."&daftar=".$daftar."&stok=".$stok."&instok=".$instok."&tgl=".$update."&serial=".$serial."&prices=".$pricedot."&disc=".$disc;	

	//verifikasi ukuran foto <=64KB
	if($images>64000){ 
	header($address."&info=Ukuran foto tidak boleh lebih dari 64KB !");
	exit();
	}

	//cek kode, harus unik
	$data=$konek->prepare("select * from buah where kode='$kode'");
	$data->execute();
	$cek=$data->rowCount();
	if($cek>0)
	{
	// echo "<script class='modal'>alert('Gagal, data ini sudah ada di dalam database!');history.go(-1);</script>";

	header($address."&focus=kode&info=Kode_".$kode." sudah ada di dalam database!");
	exit();

	}
	
	//cek nama , harus unik
	$data=$konek->prepare("select * from buah where nama='$nama'");
	$data->execute();
	$cek=$data->rowCount();
	if($cek>0)
	{
	header($address."&focus=nama&info=Nama_".$nama." sudah ada di dalam database!");
	exit();
	}

	if($kode!="" && $nama!="")
	{
	try{
	
	$insert=$konek->prepare("insert into buah(kode, nama, tgl_daftar, stok, instok, update_stok, foto, sn, harga_jual, diskon) values(:kode, :nama, :daftar, :stok, :instok, :update, :foto, :sn, :harga, :diskon)");
	$array=array(':kode'=>$kode, ':nama'=>$nama, ':daftar'=>$daftar, ':stok'=>$stok, ':instok'=>$instok, ':update'=>$update, 
	':foto'=>$foto,':sn'=>$serial,':harga'=>$price,':diskon'=>$disc);
	$insert->execute($array);

//input data ke tabel pembelian
	if($instok !=""){
	$insert=$konek->prepare("insert into masuk (kode, nama, instok, update_stok, sn) values (:kode, :nama, :instok, :update, :sn)");
	$array=array(':kode'=>$kode, ':nama'=>$nama, ':instok'=>$instok, ':update'=>$update,':sn'=>$serial); 	
	$insert->execute($array);
	}
	header("location:index.php?modeadd=hold1".$photo.$act);
	exit();
	}
	catch(PDOExeption $e){ echo "gagal menyimpan data".$e->getMessage(); }
	}
	
}

//update data
elseif(isset($_GET['update'])=='save')
{
	if(isset($_POST['hal']))
	{
	$halaman="&hal=".$_POST['hal']."&ira=".$_POST['ira']."&mx=".$_POST['mx'];
	} else { $halaman=""; }

	$id=$_POST['id'];
	$stokold=$_POST['stokold'];
	
	//pertahankan view price
	if(isset($_GET['saveprice'])){ $saveprice = "&saveprice=viewprice"; }
	else{ $saveprice = ""; }

	$address="location:index.php?$cari&$sohal&delokorupok17912457381x9djghzmtqhjrlckawutso16=okprocesssuccess67830123440".$halaman.$photo.$row.$saveprice;
	$dataup="&oldid=".$id."&stokold=".$stokold."&kode=".$kode."&nama=".$nama."&daftar=".$daftar."&stok=".$stok."&instok=".$instok."&tgl=".$update."&imgholderror=".$imgholderror."&serial=".$serial."&prices=".$pricedot."&disc=".$disc."&errorfailedduplicateddataupdate=needtorevitiondataupdate";
	
	//verifikasi ukuran foto <=64KB
	if($images>64000){ 
	header($address."&duplicated=Ukuran foto tidak boleh lebih dari 64KB !");
	exit();
	}
	
	//cek kode , harus unik
	$data=$konek->prepare("select * from buah where kode<>'$id' and kode='$kode'");
	$data->execute();
	$cek=$data->rowCount();
	if($cek>0)
	{
	header($address.$dataup."&duplicated=Kode_".$kode." sudah ada di dalam database!&focus=kode");
	exit();
	}

	//cek nama , harus unik
	$data=$konek->prepare("select * from buah where kode<>'$id' and nama='$nama'");
	$data->execute();
	$cek=$data->rowCount();
	if($cek>0)
	{
	header($address.$dataup."&duplicated=Nama_".$nama." sudah ada di dalam database!&focus=nama");
	exit();
	}

	try{
	
	$updatedata=$konek->prepare("update buah set kode=:kode, nama=:nama, tgl_daftar=:daftar, sn=:sn, harga_jual=:harga, diskon=:diskon where kode='$id'");
	$array=array(":kode"=>$kode, ":nama"=>$nama, ":daftar"=>$daftar, ":sn"=>$serial, ":harga"=>$price, ":diskon"=>$disc);
	$updatedata->execute($array);

	$updatedata=$konek->prepare("update masuk set kode=:kode, nama=:nama where kode='$id'");
	$array=array(":kode"=>$kode, ":nama"=>$nama);
	$updatedata->execute($array);

	if($stok!=""){
	$updatedata=$konek->prepare("update buah set stok=:stok, instok=:instokk, update_stok=:update where kode='$id'");
	$array=array(":stok"=>$stok, ":instokk"=>$instok, ":update"=>$update);
	$updatedata->execute($array); 

//input data ke tabel pembelian
	$insert=$konek->prepare("insert into masuk (kode, nama, instok, update_stok, sn) values (:kd, :nm, :kt, :us, :sn)");
	$array=array(':kd'=>$kode, ':nm'=>$nama, ':kt'=>$instok, ':us'=>$update, ':sn'=>$serial); 	
	$insert->execute($array);
	}

	
	if(!empty($foto)){
	$imgup=$konek->prepare("update buah set foto=:foto where kode='$id'");
	$imgup->bindparam(':foto', $foto);
	$imgup->execute(); }

	}catch(PDOExeption $e){ echo "gagal mengupdate data".$e->getMessage(); }
	
	header($address);
	exit();

}
header("location:index.php");
exit();
}

//update stok dan delete data
if(isset($_GET['operasiringanactionupstokordelete']))
{
if(isset($_GET['hal'])){ //seting halaman 
$halaman="&hal=".$_GET['hal']."&ira=".$_GET['ira']."&mx=".$_GET['mx']; }else{ $halaman=""; }

//alamat dipakai bersama
$address="location:index.php?$cari&$sohal&delokorupok17912457381x9djghzmtqhjrlckawutso16=okprocesssuccess67830123440".$halaman.$row.$photo;
$address2="location:index.php?delokorupok17912457381x9djghzmtqhjrlckawutso16=okprocesssuccess67830123440";

//update stok
if(isset($_GET['updatestok'])=='save')
{
$instok=$_GET['instok'];
if(filter_var($instok, FILTER_VALIDATE_INT) !== false){ //diproses jika instok berupa angka + dan -

$kode=$_GET['kode'];
$nama=$_GET['nama'];
$tgl=$_GET['tgl'];
$serial=$_GET['sn'];
try{
	$data=$konek->prepare("select stok from buah where kode='$kode'");
	$data->execute();
	$hasil=$data->fetch();
	$stok = $hasil['stok'] + $instok;

	//update stok, tanggal, dan barang masuk  
	$update=$konek->prepare("update buah set stok=:addstok, instok=:in, update_stok=:tgl where kode='$kode'");
	$array=array(':addstok'=>$stok, ':in'=>$instok, ':tgl'=>$tgl);
	$update->execute($array);

	//input data ke tabel pembelian
	$insert=$konek->prepare("insert into masuk (kode, nama, instok, update_stok, sn) values (:kd, :nm, :kt, :us, :sn)");
	$array=array(':kd'=>$kode, ':nm'=>$nama, ':kt'=>$instok, ':us'=>$tgl, ':sn'=>$serial); 	
	$insert->execute($array);

	}catch(PDOExeption $e){ echo "gagal mengupdate data".$e->getMessage(); }
	
	header($address."&radio=on".$halaman);
	exit();
}
}

//proses delete data
if(isset($_GET['del'])=='on' && isset($_GET['id'])!="")
{
$id=$_GET['id'];

if(isset($_GET['detail']))
{
$rowd=$_GET['rowd'];
try{
	$delete=$konek->prepare("delete from masuk where id=:id");
	$delete->execute(array(':id'=>$id));	
}
catch(PDOExeption $e) { echo "gagal  hapus".$e->getMessage(); }
header($address."&rowd=".$rowd.$halutama);
exit();
}
else{

try{
//if(isset($_GET['delalldata'])){
//$delete=$konek->prepare("TRUNCATE TABLE buah");
//$delete->execute();
//header($address2);
//exit();
//} else {
	$delete=$konek->prepare("delete from buah where kode=:kode");
	$delete->execute(array(':kode'=>$id));
	//}	
}
catch(PDOExeption $e) { echo "gagal  hapus".$e->getMessage(); }
if($hal==$total_pages && $n==1 && $hal>1) { $hal="&hal=".$hal-1; } //jika data terakhir yg dihapus
header($address.$hal);
exit();
}

}

if(isset($_GET['price']))
{
    try{
    $kode=$_GET['kode'];
    $price=$_GET['price'];
    $disc=$_GET['disc'];
    $update=$konek->prepare("update buah set harga_jual=:price, diskon=:disc where kode='$kode'");
	$array=array(':price'=>$price, ':disc'=>$disc);
	$update->execute($array);
	}catch(PDOExeption $e){ echo "gagal mengupdate data".$e->getMessage(); }
	
	header($address."&saveprice=done".$halaman);
	exit();
}
}
?>

<!DOCTYPE html>
<html>

<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>crud php html</title>
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
</head>
<style type="text/css">
        @import url('stylecrudbuah.css');
</style>
<body>
<?php
if(isset($_GET['printon_newwindow']))
{
include("print.php");
}
else 
{
	if(isset($_GET['modeadd'])=='hold1')//form tetap terbuka setelah pengisian & scrool halaman agar data terlihat 
	{  ?>
	<script>
	window.onload=function blok(){
	//window.scrollTo(0, 120);
	document.getElementById("row1").scrollIntoView({behaviour:'smooth'});
	tambah(); }
	</script>
<?php	} ?>

<div id="id01" class="modal">
<form class="modal-content animate" method="post" id="formd" enctype="multipart/form-data">
<?php //sisip halaman agar tetap pada halaman setelah diupdate
if(isset($_GET['hal'])){ ?>
<input type="hidden" value="<?=$_GET['hal']?>" name="hal">
<input type="hidden" value="<?=$_GET['ira']?>" name="ira">
<input type="hidden" value="<?=$_GET['mx']?>" name="mx">
<?php } ?> 
<div class="container">
<table width='100%' border="0">
<tr>
<td align='right' bgcolor="white" width="20%">&nbsp;</td>
<td bgcolor="white"><center style="font-size:150%; color:#c2c2c2; font-family:verdana;"><span id="titleop"></span></center></td>
<td align='right' bgcolor="white" width="20%"><span onclick="document.getElementById('id01').style.display='none'" class="close" title="Close">&times;</span>
</td>
</tr>
</table>
<input type="hidden" name="imgholderror" id="img">
<input type="hidden" name="id" id="oldid">
<input type="hidden" name="stokold" id="stokoldid">
<div class="subcontainer">
    <div class="classadd">
    <span>Kode</span>
    <input type="text" placeholder="kode " name="kode" required id="fkode">
    </div>
    <div class="classadd">
    <span>Nama</span>
    <input type="text" placeholder="nama " name="nama" required id="fnama" >
    </div>
    <div class="classadd">
    <span>Tgl.Daftar</span>
    <input type="date" name="daftar" required id="fdaftar" class="tgl" style="width:150px;">
    </div>
    <div class="classadd">
        <span>Stok</span>
        <input type="text" name="stok" readonly style="width:30%; text-align:center;" id="fstoknew">
        <input type="text" inputmode="numeric" placeholder="stok in" name="instok" id="finstok" oninput="kalkulasistok()" style="width:30%; text-align:center;" readonly>
        <input type="checkbox" name="includestok" id="centangstok" onclick="onstokup(this)">
    </div>
    <div class="classadd">
        <span>Serial Num.</span>
        <textarea id="sn" name="serial" rows="4" placeholder="Jika SN lebih dari satu pisahkan dengan koma (,) : xxxx,xxxx,xxxx,...dst (optional)" oninput="inputsn()"></textarea>
    </div>
            <span id="warningsn" class="tab warna"></span>
        <div class="classadd">
            <span>Harga_Jual</span>
            <input type="text" inputmode="numeric" name="price" id="fprice" style="width:150px;" onfocus="formatharga('fprice')" oninput="formatharga('fprice')">
            <span style="display:flex; align-items: center;">-<input type="text" inputmode="numeric" name="disc" id="fdisc" style="width:50px;" value="0" oninput="justnumber('fdisc')" placeholder="diskon">&nbsp;%</span>
        </div>
        <div class="classadd">
            <span>Tgl.Update</span>
            <input type="date" name="tgl" id="fupdate" class="tgl" style="width:150px;">
        </div>
        <div class="classadd">
            <span>Foto</span>
            <input type="file" oninput="pic.src=window.URL.createObjectURL(this.files[0])" name="foto" id="foto-input">
        </div>
        <div class="classadd">
            <span>&nbsp;</span>
        <img id="pic" accept="image/png, image/jpeg" width="80px" height="100px">
        </div>
</div>
<br /><br />
<div>
<center>
<button type="submit" class="signupbtn">Save</button>
<button type="button" onclick="cancel()" class="cancelbtn">Cancel</button>
</center>
</div>
</div>
</form>
</div>

<div id="id02" class="modal">
<div class="modal-contentprint animate">
<div class="containerp">
<input type="radio" name="print" onclick="custompage('cp')" id="cp" checked>Current page<br />
<input type="radio" id="all" name="print" onclick="custompage('a')">All<br />
<input type="radio" id="custom" name="print" onclick="custompage('c')">
<span onclick="custompage('pilihcustom')">Custom</br />
<input type="text" inputmode="numeric" id="halaman" placeholder="contoh: 1-10" oninput="checkprintex()">
</span>
<br /><span id="warningprint" class="warna"></span> <br /></br />
</div>
<center>
<button type="submit" class="signupbtn" onclick="printex()">Print</button><button type="button" onclick="cancelprint()" class="cancelbtn">Cancel</button>
</center><br />
</div>
</div>

<div class="topnav topnavx">

<div class="leftn">N=<?=$nt?></div>
<div class="framesearch" id="fsearch">
<div class="left">
<input type="text" name="cari" placeholder="Cari..." title="cari" <?php if($cari!=''){ ?> value="<?=$_GET['cari']?>"<?php }?> id="datacari" style="width: 100%; flex-grow: 1; border:none; background:none;" >
</div>
<div class="left">
<input type="submit" value="" class="icon" title="cari" onclick="caridata()" id="submitcari" >
</div>

<?php
if(isset($_GET['cari'])=='on'){ ?>
<div class="left">
<a title="Tutup pencarian" style="text-decoration:none;" onclick="tutupcari()"><span class="close">&times;&nbsp;</span></a>
</div> <?php } ?>
</div>

<div class="left offmobile">
<img src="print2.png" style="height:28px; float:left; width:53px; cursor:pointer;" onclick="printchoice()" title="Print to new tab">
</div>

<div class="rightgrup">
<?php if(isset($_GET['sortir'])){ 
  $urutan=$_GET['sortir']; ?>
<div class="asdes">
      <span class="huruf">A</span>
      <span class="huruf">Z</span>
</div>
<?php
if($urutan=="asc"){ ?>
      <span class="panah">&#8595;</span>

    <?php }else { ?>
      <span class="panah">&#8593;</span>
<?php }
} ?>

<button class="buttonadd" onclick="sortir()" id="tombolsortir" title="Urutkan Nama Barang!">Sortir</button>

<?php
if(!isset($limitfitur)){ ?>
<button id="badd" onclick="tambah()" title="Tambah data" class="buttonadd offmobile">&#43;</button>
<?php } ?>

<button class="buttonadd offmobile" onclick="sales()" id="sales">Toko</button>

<button class="buttonadd offmobile" onclick="exitpage()">Logout</button>
</div>

<div class="rightmenu visible">
<div class="dropdown">
<button onclick="menu()" class="rightmenux" id="menuhp">
<hr class="menuline"><hr class="menuline"><hr class="menuline">
</button>
<div id="myDropdown" class="dropdown-content">
    <a href="javascript:void(0)" onclick="exitpage()">Keluar</a>
    <a href="javascript:void(0)" onclick="printchoice()">Print</a>
    <?php
    if(!isset($limitfitur)){ ?>
    <a href="javascript:void(0)" onclick="callingtambah()" id="mbadd">Tambah Data</a>
    <?php } ?>
    <a href="javascript:void(0)" onclick="sales()">Toko</a>
    </div>
</div>  
</div>

</div>

<p align="center" class="title">
<?php if(isset($_GET['detail'])){ $c=$_GET['row']; ?>
<button onclick="kembalitabel('<?=$c?>')" class="a previous round">
<span id="panahdouble">&#9664;&#9664;</span></button>
<b><?=$_GET['kode']?>/&nbsp;<?=$_GET['nama']?></b><?php } else { ?>Daftar Barang <?php  } ?>
</p>

<div class="topnav">
<div class="leftphoto" <?php if(isset($_GET['detail'])) {?> style="display: none;" <?php } ?>>
<?php if(isset($_GET['photo'])){ ?>
<span onclick="showhidephoto()">
<i class="arrowgreen" id="arrgp"></i><i class="arrow" id="arrp"></i>
</span>
<span id="shp" class="showhide">Hide-photo</span>
<?php } 
else { ?>
<span onclick="showhidephoto()">
<i class="hidearrg" id="arrgp"></i><i class="hidearr" id="arrp"></i>
</span>
<span id="shp" class="showhide">Show-photo</span>
<?php } ?>
</div>

<div class="rightphoto">
<?php if(isset($_GET['delokorupok17912457381x9djghzmtqhjrlckawutso16'])){ ?>
<span id="sh" class="showhide">Hide-action</span>
<span onclick="showhide()">
<i class="hidearr" id="arr"></i><i class="hidearrg" id="arrg"></i>
</span> <?php } 
else { 
if(!isset($limitfitur)){ ?>
<span id="sh" class="showhide">Show-action</span><span id="bsh" onclick="showhide()">
<i class="arrow" id="arr"></i><i class="arrowgreen" id="arrg"></i></span> <?php } } ?>
</div>
</div>

<div class="frametableheader no-fouc" id="headertbl">
<table id="datatable2" class="table" cellpadding="3" width="100%" border="0">
<tr style="height:30px;">
<th bgColor="#f2f2f2" onclick="sortTablenum()" style="cursor: pointer; width:9.9%;" title="Click untuk sortir nomor!" align="center" id="kolom0">
No
</th>
<th bgColor="#f2f2f2" align="center" width="40.35%">
<span id="optbp" style="color:transparent;<?php if(isset($_GET['photo'])){ ?>display:'';<?php } else {?> display:none;<?php }?>">.</span>

<?php if(isset($_GET['detail']))
{ ?>Tanggal Masuk<?php } else{ ?>Nama<?php } ?>

</th>
<th bgColor="#f2f2f2" align="center">

<?php if(isset($_GET['detail'])){ ?>Kuantitas<?php } else { ?>

<div style="display: flex; align-items: center;">
  <input type="checkbox" onclick="titlesortir(this)" role="switch" name="sortistok" id="sortirstokid" style="margin: 0; vertical-align: middle;">
  <button style="width:50px; height: 30px; background-color:transparent; color:black; font-weight:bold; pointer-events:none;" id="stok" onclick="viewstok()">Stok</button>
  <button style="width:50px; height: 30px; background-color:#c2c2c2; font-weight:bold; margin-left: 5px;" id="price" onclick="viewprice()">Harga</button>
</div>

<?php } ?>

<span id="optb" style="color:transparent; <?php if(isset($_GET['delokorupok17912457381x9djghzmtqhjrlckawutso16'])){ ?>display:'';<?php } else {?>display:none;<?php }?>">.</span>
</th> 
</tr>
</table>
</div>

<div class="frametable no-fouc" id="frtable">
<table id="datatable" class="table" cellpadding="3" width="100%">
<?php
$i=$from+1;
$j=1;
while($hasil=$data->fetch())
{
if($i<=$n+$from)
{
?>
<tr id="row<?=$i?>"
<?php if(isset($_GET['row'])){
$rows=$_GET['row'];
if(isset($_GET['rowd'])) { $rows=$_GET['rowd']; }
if($rows > $nt){ $rows = $n+$from; } //jika data terkahir yg dihapus, turunkan rows
if($rows==$i){?> onclick="clearselect('<?=$rows?>')" <?php } } ?> >
<td align="center" id="kolom10" width="10%">
<?=$i?>
</td>

<?php
$f0=$hasil['id']; $f1=$hasil['kode']; $f2=$hasil['nama']; $f5=$hasil['instok'];
$f6=$hasil['update_stok']; $tglu=date_create($f6); $format_tglu=date_format($tglu,'d/m/Y');
$sn=$hasil['sn'];

if(!isset($_GET['detail'])){
$f3=$hasil['tgl_daftar']; $tgl=date_create($f3); $format_tgl=date_format($tgl,'d/m/Y');
$f4=$hasil['stok'];
$f7=$hasil['foto'];
$f8=$hasil['harga_jual'];
$f9=$hasil['diskon'];    
} ?>
<td align="center" width="41%">
<?php if(isset($_GET['detail'])){ echo $format_tglu; }
else { ?>

<p><img <?php if(!empty($f7)) { ?> src="data:image/jpg;base64,<?=base64_encode($f7)?>" width="50" height="65" <?php } ?> id="img<?=$i?>" style="float:left;<?php if(isset($_GET['photo'])){ ?>display:'';<?php } else {?> display:none;<?php }?>" onclick="zoomimg('img<?=$i?>')" />
</p>
<span class="tdmerger"><?=ucwords($f2)?></span><br />
<span class="tdmerger3"><?=$f1?></span>
<span class="tdmerger3">-<?=$format_tgl?></span>
<?php } ?>

</td>
<td style="text-align:left;" id="tdstok<?=$i?>">

<?php if(isset($_GET['detail'])){ echo $f5."<br />SN: ".$sn; }
else { ?>
<p class="stok" id="indikator<?=$i?>">
<b><span id="indi<?=$i?>" class="stok2"><?=$f4?></span></b>

<span id="jual<?=$i?>" style="display:none; ">
<span id="harga<?=$i?>"><?=$f8?></span>
<?php if($f9!=0){ echo "<br /><br />&nbsp;diskon&nbsp;".$f9."%"; } ?>
</span>
<span id="editharga<?=$i?>" style="display:none; ">
<input type="text" inputmode="numeric" id="price<?=$i?>" style="text-align:center; width:100px;" value="<?=$f8?>" onfocus="formatharga('price<?=$i?>')" oninput="formatharga('price<?=$i?>')">&nbsp;disk:<input type="text" inputmode="numeric" name="diskon<?=$i?>" id="disc<?=$i?>" oninput="justnumber('disc<?=$i?>')" style="text-align:center; width:50px;" value="<?=$f9?>" >%
</span>

&nbsp;<input type="radio" name="updatestok" onchange="checkedradio(this,<?=$i?>);" id="stok<?=$i?>" style="<?php if(isset($_GET['delokorupok17912457381x9djghzmtqhjrlckawutso16'])){ ?>display:''; <?php } else { ?>display:none;<?php }?>float:none;" >
</p>
<p align="center" class="masuk">
<input type="text" placeholder="stok" name="brgmasuk" id="instok<?=$i?>" inputmode="numeric" style="display:none; text-align:center; width:115px; float:inherit;" disabled>
<input type="date" name="tanggalupdate" id="uptgl<?=$i?>" style="display:none; float:none; width:115px;" disabled>
</p>
<button disabled onclick="detailinstok('<?=$f1?>','<?=$f2?>','<?=$i?>')" id="stokins<?=$i?>" style="pointer-events:none;" class="detail" >
<span><?=$f5?></span>-<?=$format_tglu?>
</button>
<span id="seri<?=$i?>" style="display:none;" class="detail"><br />SN: <?=$sn?></span>
<?php } ?> 

</td>
<?php if(!isset($limitfitur)){ ?>
<td width="14.5%" id="ub<?=$i?>" align="center" style="<?php if(isset($_GET['delokorupok17912457381x9djghzmtqhjrlckawutso16'])){ ?>display:'';<?php } else {?>display:none;<?php }?>" >

<input type="button" value="Edit" class="button2"
onclick="ubah('<?=$f1?>', '<?=$f2?>', '<?=$f3?>', '<?=$f4?>', '<?=$f5?>', '<?=$f6?>','img<?=$i?>','<?=$f8?>','<?=$f9?>','<?=$i?>','<?=$sn?>')" id="vubah<?=$i?>" style="float:none;" >
<input type="button" value="Del" class="button1" onclick="hapus('<?=$f1?>','<?=$f2?>','<?=$i?>','<?=$f0?>', 'godel')" id="vhapus<?=$i?>" style="float:none;">
</td>
<?php } ?>

</tr>
<?php
}
$i++;
$j++;
} ?>
</table>
</div>

<div>
<hr />
<?php
include ("paging.php");
?>
</div>

<div class="footer" id="foot">Sistem Informasi Data Barang<br />
created by:yandrienlw-2025-email:ri3nlw@yahoo.com<br />
Phone/Wa:08180534365
</div>
<br /><br /><br /><br />

<!-- modal untuk confirm hapus -->
<div class="modalconfirm" id="confirmdel">
    <div class="modalconfirm-content">
  <svg width="48" height="48" viewBox="0 0 24 24" fill="#ff9800">
      <!--<path d="M12 2C6.47 2 2 6.47 2 12s4.47 10 10 10 10-4.47 10-10S17.53 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-1-13h2v6h-2zm0 8h2v2h-2z" /> -->
      
      <path d="M12 2C6.47 2 2 6.47 2 12s4.47 10 10 10 10-4.47 10-10S17.53 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>

      </svg><br /><br />
        <span id="ketdel"></span>
        <br /><br /><br />
        <button id="nodel" class="cancelbtn">Batal</button>
        &nbsp;&nbsp;
        <button id="yesdel" class="signupbtn">Oke</button>
    </div>
</div>

<script>

<?php //jika terjadi duplikasi data input
if(isset($_GET['errorfailedduplicateddata']))
{ ?>
alert("<?=$_GET['info']?>");
var instok = "<?=$_GET['instok']?>";
var focus = "<?=$_GET['focus']?>";
var p = document.getElementById("optbp");
var q = document.getElementById("optb");
if(p.style.display===""){ photo="&photo=on"; } else { photo=""; }
if(q.style.display===""){ act="&delokorupok17912457381x9djghzmtqhjrlckawutso16=okprocesssuccess67830123440"; } else{ act=""; }

document.getElementById("formd").action="index.php?operasi=on&input=save"+photo+act;
document.getElementById("fkode").value = "<?=$_GET['kode']?>";
document.getElementById("fnama").value = "<?=$_GET['nama']?>";
document.getElementById("fdaftar").value = "<?=$_GET['daftar']?>";
document.getElementById("fstoknew").placeholder = "0";
if(instok != "") {
document.getElementById("centangstok").checked=true;
document.getElementById("finstok").removeAttribute("readonly");
document.getElementById("finstok").value = instok;
document.getElementById("fstoknew").value = instok;
}
document.getElementById("fupdate").value = "<?=$_GET['tgl']?>";
document.getElementById("titleop").innerHTML="Input Data";
document.getElementById("sn").value = "<?=$_GET['serial']?>";
document.getElementById("fprice").value = "<?=$_GET['prices']?>";
document.getElementById("fdisc").value = "<?=$_GET['disc']?>";
document.getElementById('id01').style.display='block';
if(focus == "kode") { document.getElementById("fkode").focus(); }
if(focus == "nama") { document.getElementById("fnama").focus(); }
<?php }

//jika terjadi duplikasi data update
if(isset($_GET['errorfailedduplicateddataupdate']))
{ 
//pertahankan view price
if(isset($_GET['saveprice'])){ $saveprice = "&saveprice=viewprice";}
else { $saveprice = ""; }
?>
//jika terjadi duplikasi data update
alert("<?=$_GET['duplicated']?>");
var instok = "<?=$_GET['instok']?>";
var focus = "<?=$_GET['focus']?>";
var p = document.getElementById("optbp");
if(p.style.display===""){ photo="&photo=on"; } else { photo=""; }

document.getElementById("formd").action="index.php?<?=$cari?>&<?=$sohal?>&operasi=on&update=save&row=<?=$_GET['row']?>"+photo+"<?=$saveprice?>";
document.getElementById("img").value = "<?=$_GET['imgholderror']?>";
document.getElementById("oldid").value = "<?=$_GET['oldid']?>";
document.getElementById("fkode").value = "<?=$_GET['kode']?>";
document.getElementById("fnama").value = "<?=$_GET['nama']?>";
document.getElementById("fdaftar").value = "<?=$_GET['daftar']?>";
document.getElementById("stokoldid").value = "<?=$_GET['stokold']?>";
document.getElementById("fstoknew").placeholder = "<?=$_GET['stokold']?>";
if(instok != "") {
document.getElementById("centangstok").checked=true;
document.getElementById("finstok").removeAttribute("readonly");
document.getElementById("fstoknew").value = "<?=$_GET['stok']?>";
document.getElementById("finstok").value = instok;
}
document.getElementById("fupdate").value = "<?=$_GET['tgl']?>";
document.getElementById("pic").src = document.getElementById("<?=$_GET['imgholderror']?>").src;
document.getElementById("sn").value = "<?=$_GET['serial']?>";
document.getElementById("fprice").value = "<?=$_GET['prices']?>";
document.getElementById("fdisc").value = "<?=$_GET['disc']?>";
document.getElementById("titleop").innerHTML="Revisi Perubahan Data";
document.getElementById('id01').style.display='block';
if(focus == "kode") { document.getElementById("fkode").focus(); }
if(focus == "nama") { document.getElementById("fnama").focus(); }

<?php } ?>


//listens for focus on textbox
document.getElementById('datacari').addEventListener("focus", changeDivColor);

//this is fired when the textbox is focused
function changeDivColor(){
  document.getElementById('fsearch').style.borderColor = "#04AA6D";
}

//listens for blur on textbox
 document.getElementById('datacari').addEventListener("blur", revertDivColor);

//this is fired when the textbox is no longer focused
function revertDivColor(){
  document.getElementById('fsearch').style.borderColor = null;
}


<?php

//detail masukkan barang
if(isset($_GET['detail'])) { 

if(isset($_GET['rowd'])){ //rowd adalah baris datamasuk instok yg dihapus ?>
var a=<?=$_GET['rowd']?>; 
var b=<?=$nt?>;
if(a > b){ a = b; }
document.getElementById("row"+a).scrollIntoView({behaviour:'smooth'});
document.getElementById("row"+a).style.background="rgba(255,200,0,0.2)";
<?php } ?>

/*matikan tombol edit ketika dalam detail masuk*/
var q = document.getElementById("optb");
if(q.style.display==="")
{
for(j=<?=$from?>+1;j<=<?=$n+$from?>; j++)
	{
	document.getElementById("vubah"+[j]).disabled=true;
	document.getElementById("vubah"+[j]).style.background="#f3f3f3";
	document.getElementById("vubah"+[j]).style["pointer-events"]="none";
	}
}
<?php }

//data utama
if(!isset($_GET['detail'])) { ?>

/*aktifkan hover detail setelah sortir atau aksi*/
var y = window.matchMedia("(max-width: 800px)"); //pencocokan untuk layar HP
var q = document.getElementById("optb");
if(q.style.display==="")
{
for(j=<?=$from?>+1;j<=<?=$n+$from?>; j++)
	{
	document.getElementById("stokins"+j).disabled=false;
	document.getElementById("stokins"+j).classList.add("detailhover");
	document.getElementById("stokins"+j).style["pointer-events"]="auto";
	if (y.matches) { // If media query matches
	document.getElementById("stokins"+[j]).classList.add("detailunderline");
	}
	}
}

//pertahankan checkbox sortir dan titile tombol sortir ketika disortir
<?php if(isset($_GET['sortirstok'])){ ?>
document.getElementById("sortirstokid").checked=true;
document.getElementById("tombolsortir").title="Urutkan Stok !";
<?php } ?>

//indikator level stok
for (i=<?=$from?>+1; i<=<?=$n+$from?>; i++)
{
var stok = document.getElementById("indi"+i);
var n = stok.innerHTML;
if(n < 10) {    
	stok.classList.add("indikatorstok");
}
if(n >= 10 && n <= 20) {    
	stok.classList.add("indikatorstok2");
}
}
<?php 

//tampilkan field harga setelah perubahan harga atau paging halaman
if(isset($_GET['price']) || isset($_GET['saveprice'])) { ?>
viewprice();
<?php } ?>

//format harga barang
for(i=<?=$from?>+1;i<=<?=$n+$from?>; i++)
{
let harga = document.getElementById("harga"+i).innerHTML;
if(harga.length > 3){ 
var j, k, l, m;
var n = harga.length - 1;
var sisa = n % 3;
var titik = n / 3;
if(titik < 2 || titik >= 4){ //ribuan dan triliunan
j=harga.slice(0,sisa+1); k=harga.slice(sisa+1);
let hasil = [j, k];
document.getElementById("harga"+i).innerHTML = hasil.join(".");
}
else if(titik < 3){ //jutaan
j=harga.slice(0,sisa+1); k=harga.slice(sisa+1,sisa+4); l=harga.slice(sisa+4,sisa+7);
let hasil = [j, k, l];
document.getElementById("harga"+i).innerHTML = hasil.join(".");
}
else if(titik < 4){ //miliyaran
j=harga.slice(0,sisa+1); k=harga.slice(sisa+1,sisa+4); l=harga.slice(sisa+4,sisa+7); m=harga.slice(sisa+7,sisa+10);
let hasil = [j, k, l, m];
document.getElementById("harga"+i).innerHTML = hasil.join(".");
} 
}
}

<?php
if(isset($_GET['row']) && !isset($_GET['tombolon']) && !isset($_GET['pagereplace']))
{ ?>
/*select row data dan fokus ke data yang dieksekusi*/
var s=<?=$_GET['row']?>;
var n=<?=$nt?>;
if(s > n){ s = n; }

document.getElementById("row"+s).scrollIntoView({behaviour:'smooth'});
document.getElementById("row"+s).style.background="rgba(255,200,0,0.2)";

<?php
}
}


//fokus paging halaman
if(isset($_GET['pagereplace'])) {?> document.getElementById("page<?=$hal?>").scrollIntoView({behaviour:'smooth'}); <?php }

//fokus tombol shortir
if(isset($_GET['tombolon'])) {?> window.scrollTo(0, 0);
<?php } ?>




/*clear fokus dengan klik di baris tabel */
function clearselect(e){ //clear selector row
document.getElementById("row"+e).style.background=null;
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
    document.getElementById("myDropdown").classList.toggle("show", false);
}});

// Close the dropdown if the user clicks outside of it
/*window.onclick = function(event) {
  if (!event.target.matches('.rightmenux')) {
    var dropdowns = document.getElementsByClassName("dropdown-content");
    var i;
    for (i = 0; i < dropdowns.length; i++) {
      var openDropdown = dropdowns[i];
      if (openDropdown.classList.contains('show')) {
        openDropdown.classList.remove('show');
      }
    }
  }
} */

function cancel() {
document.getElementById('id01').style.display='none'
document.getElementById('formd').reset();
document.getElementById('pic').src = '';
document.getElementById("centangstok").checked=false;
document.getElementById("finstok").setAttribute("readonly",true);
document.getElementById("sn").value = "";
document.getElementById("sn").maxLength = "10000";
document.getElementById("warningsn").innerHTML="";
}

function callingtambah(){
    //for mode hp, menu diperintah click lg shg tertutup
document.getElementById("menuhp").click();
tambah(); 
}

function tambah() {
var p = document.getElementById("optbp");
var q = document.getElementById("optb");
if(p.style.display===""){ photo="&photo=on"; } else { photo=""; }
if(q.style.display===""){ act="&delokorupok17912457381x9djghzmtqhjrlckawutso16=okprocesssuccess67830123440"; } else{ act=""; }

 document.getElementById("formd").action="index.php?operasi=on&input=save"+photo+act;
 document.getElementById("titleop").innerHTML="Input Data";
 document.getElementById('id01').style.display='block';
 document.getElementById("fstoknew").placeholder = "0";
 document.getElementById("fdaftar").valueAsDate = new Date();
 document.getElementById("fupdate").valueAsDate = new Date();
}

function ubah(f1, f2, f3, f4, f5, f6, f7, f8, f9, i, sn) {
var p = document.getElementById("optbp");
if(p.style.display===""){ photo="&photo=on"; } else { photo=""; }

var radio = document.getElementById("stok"+i);
var price = document.getElementById("price").style.background;
if(radio.checked == true )
{
	if(price != "transparent"){

	var addstok = document.getElementById("instok"+i).value;
	var tgl = document.getElementById("uptgl"+i).value;
	var afterup = document.getElementById("instok"+i);
	if(afterup.disabled == true) //after update to revisi
	{
	document.getElementById("uptgl"+i).valueAsDate = new Date();
	document.getElementById("uptgl"+i).style.display="";
	document.getElementById("uptgl"+i).disabled=false;
	document.getElementById("instok"+i).style.display="";
	document.getElementById("instok"+i).disabled=false;
	document.getElementById("instok"+i).focus();
	}	
	if(addstok!="" && !isNaN(addstok)) //go to update stok if berupa angka
	{
	window.location.replace("index.php?<?=$cari?>&<?=$sohal?>&<?=$haljs?>&operasiringanactionupstokordelete=onfire&updatestok=save&row="+i+"&kode="+f1+"&nama="+f2+"&instok="+addstok+"&tgl="+tgl+"&sn="+sn+photo); return true;
	}
	else { document.getElementById("instok"+i).focus(); return false; }
	
	}
	else{
	var harga = document.getElementById("price"+i).value;
	var diskon = document.getElementById("disc"+i).value;
	harga = harga.replace(/[.]/g, ''); //hilangkan titik untuk nilai sebenarnya
	if(!isNaN(harga)){
	    if(!isNaN(diskon)){
	window.location.replace("index.php?<?=$cari?>&<?=$sohal?>&<?=$haljs?>&operasiringanactionupstokordelete=onfire&row="+i+"&kode="+f1+"&nama="+f2+"&price="+harga+"&disc="+diskon+photo); return true;
	    }else {
	        document.getElementById("disc"+i).focus();
	    return false;
	    }
	}else {
	    document.getElementById("price"+i).focus();
	    return false;
	}
	}
}

//jika price view, pertahankan
var saveprice = document.getElementById("price").style.color;
if(saveprice == "black"){ saveprice = "&saveprice=saveprice"; } else{ saveprice = ""; }

//lanjut ke form perubahan
document.getElementById("formd").action="index.php?<?=$cari?>&<?=$sohal?>&operasi=on&update=save&row="+i+photo+saveprice;
document.getElementById("img").value=f7;
document.getElementById("oldid").value=f1;
document.getElementById("fkode").value=f1;
document.getElementById("fnama").value=f2;
document.getElementById("fdaftar").value=f3;
document.getElementById("stokoldid").value=f4;
document.getElementById("fstoknew").placeholder=f4;
document.getElementById("fupdate").value=f6;
document.getElementById("pic").src=document.getElementById(f7).src;
document.getElementById("sn").value=sn;
document.getElementById("fprice").value=f8;
document.getElementById("fdisc").value=f9;
document.getElementById("titleop").innerHTML="Perubahan Data";
document.getElementById('id01').style.display='block';
}


//filter untuk lewatkan bulat dan desimal saja
function justnumber(d){
    var disc = document.getElementById(d).value;
    var cek = disc.slice(-1);
    if(isNaN(disc) && cek !== '.'){
        document.getElementById(d).value = 0;
        return false;
    }
    if(disc.length > 1 && disc[0] === '0' && disc[1] !== '.'){
        document.getElementById(d).value = disc.slice(1);
    }
}

function formatharga(h){ 
let harga = document.getElementById(h).value;

harga = harga.replace(/\D/g, ''); //hilangkan titik dan karakter lain selain angka zaja

if(harga.length > 3){ 
var j, k, l, m;
var n = harga.length - 1;
var sisa = n % 3;
var titik = n / 3;
if(titik < 2 || titik >= 4){ //ribuan dan triliunan
j=harga.slice(0,sisa+1); k=harga.slice(sisa+1);
let hasil = [j, k];
document.getElementById(h).value = hasil.join(".");
}
else if(titik < 3){ //jutaan
j=harga.slice(0,sisa+1); k=harga.slice(sisa+1,sisa+4); l=harga.slice(sisa+4,sisa+7);
let hasil = [j, k, l];
document.getElementById(h).value = hasil.join(".");
}
else if(titik < 4){ //miliyaran
j=harga.slice(0,sisa+1); k=harga.slice(sisa+1,sisa+4); l=harga.slice(sisa+4,sisa+7); m=harga.slice(sisa+7,sisa+10);
let hasil = [j, k, l, m];
document.getElementById(h).value = hasil.join(".");
} 
}else{ 
    document.getElementById(h).value = harga; //antisipasi jika bukan angka maka masukkan ulang angka sj
    return;
}
}

function checkedradio(radio,i)
{
var price = document.getElementById("price").style.background;
if(radio.checked == true){
document.getElementById("vhapus"+i).value="X";
document.getElementById("vhapus"+i).title="Akhiri tindakan!";
document.getElementById("vubah"+i).disabled=false;
document.getElementById("vhapus"+i).disabled=false; 
document.getElementById("vubah"+i).style.background=null;
document.getElementById("vhapus"+i).style.background=null;

if(price != "transparent"){
document.getElementById("price").disabled=true;
document.getElementById("uptgl"+i).valueAsDate = new Date();
document.getElementById("uptgl"+i).style.display="";
document.getElementById("uptgl"+i).disabled=false;
document.getElementById("instok"+i).style.display="";
document.getElementById("instok"+i).disabled=false;
document.getElementById("seri"+i).style.display="";

document.getElementById("vubah"+i).value="Add";
document.getElementById("vubah"+i).title="Tambah Stok";
document.getElementById("instok"+i).focus();
}
else{
document.getElementById("stok").disabled=true;
document.getElementById("vubah"+i).value="Save";
document.getElementById("vubah"+i).title="Simpan perubahan";

document.getElementById("jual"+i).style.display="none";
document.getElementById("editharga"+i).style.display="";
document.getElementById("price"+i).focus();
}
}

if(price != "transparent"){
for(j=<?=$from?>+1;j<=<?=$n+$from?>; j++)
	{
	if(j!=i){
	document.getElementById("uptgl"+j).style.display="none";
	document.getElementById("uptgl"+j).disabled=true;
	document.getElementById("instok"+j).style.display="none";
	document.getElementById("instok"+j).disabled=true;


	document.getElementById("vubah"+j).value="Edit";
	document.getElementById("vhapus"+j).value="Del";
	document.getElementById("vubah"+j).disabled=true;
	document.getElementById("vhapus"+j).disabled=true;
	document.getElementById("vubah"+j).style.background="#f3f3f3";
	document.getElementById("vhapus"+j).style.background="#f3f3f3";
	}
	}
}
else{
for(j=<?=$from?>+1;j<=<?=$n+$from?>; j++)
	{
	if(j!=i){
	document.getElementById("vubah"+j).value="Edit";
	document.getElementById("vhapus"+j).value="Del";
	document.getElementById("vubah"+j).disabled=true;
	document.getElementById("vhapus"+j).disabled=true;
	document.getElementById("vubah"+j).style.background="#f3f3f3";
	document.getElementById("vhapus"+j).style.background="#f3f3f3";
	
	document.getElementById("jual"+j).style.display="";
	document.getElementById("editharga"+j).style.display="none";
	
	}
}
}
}

//bisa input angka saja dan - pada box instok row
function angkasaja(instok){
let minus = document.getElementById(instok).value;
let instokangka = parseInt(document.getElementById(instok).value);
if(!isNaN(instokangka) || minus == "-") {
if(minus == "-"){ document.getElementById(instok).value = minus; }
else { document.getElementById(instok).value = instokangka; }
return;
}else{ 
document.getElementById(instok).value = "";
return;
}
}


//tampilkan detail pemasukan/update barang
function detailinstok(f1,f2,c){ 
var sortirstok = document.getElementById("sortirstokid");
var p = document.getElementById("optbp");
var q = document.getElementById("optb");
if(p.style.display===""){ photo="&photo=on"; } else { photo=""; }
if(q.style.display===""){ act="&delokorupok17912457381x9djghzmtqhjrlckawutso16=okprocesssuccess67830123440"; } else{ act=""; return false; }

//jika sedang sortir stok beri ket.
if(sortirstok.checked == true){ var even = "&whileonsortirstok=entering"; }
else { var even = ""; }

window.location.replace("index.php?<?=$cari?>&<?=$sohal?>&<?=$haljs?>&openmasuk=on&switchingtable=instokopenmasuk16&kode="+f1+"&nama="+f2+"&detail=openonekodeshowon87&row="+c+photo+act+even);
}

function kembalitabel (c)
{
var p = document.getElementById("optbp");
var q = document.getElementById("optb");
if(p.style.display===""){ photo="&photo=on"; } else { photo=""; }
if(q.style.display===""){ act="&delokorupok17912457381x9djghzmtqhjrlckawutso16=okprocesssuccess67830123440"; } else{ act=""; }

window.location.replace("index.php?<?=$cari?>&<?=$sohal?>&hal=<?=$hal2?>&ira=<?=$ira2?>&mx=<?=$mx2?>&row="+c+photo+act);

}

function hapus(f1, f2, c, id, apa) { 
var xz = 0;
let vhp = document.getElementById("vhapus"+c).value;

if(vhp === "X") { 
    var price = document.getElementById("price").style.background;

if(price != "transparent"){ 
	document.getElementById("price").disabled=false;
	document.getElementById("uptgl"+c).disabled=true;
	document.getElementById("uptgl"+c).style.display="none";
	document.getElementById("instok"+c).disabled=true;
	document.getElementById("instok"+c).style.display="none";
	document.getElementById("seri"+c).style.display="none";
	}
	else{
	document.getElementById("stok").disabled=false; 
	document.getElementById("editharga"+c).style.display="none";
	document.getElementById("price"+c).value = document.getElementById("price"+c).defaultValue;
	document.getElementById("disc"+c).value = document.getElementById("disc"+c).defaultValue;
	
	document.getElementById("jual"+c).style.display="";
	}
	
	document.getElementById("stok"+c).checked=false;
	document.getElementById("vhapus"+c).value="Del";
	document.getElementById("vubah"+c).value="Edit";

	for(i=<?=$from?>+1;i<=<?=$n+$from?>; i++)
	{ 
	if(i!=c){ 
	document.getElementById("vhapus"+i).style.background=null;
	document.getElementById("vubah"+i).style.background=null;
	document.getElementById("vhapus"+i).disabled=false;
	document.getElementById("vubah"+i).disabled=false;
	}
	}

return true;
}

/*if(confirm("Data baris ke-"+c+" akan dihapus!")==true)
{ */

if(apa === 'oke'){
        var p = document.getElementById("optbp");
if(p.style.display===""){ photo="&photo=on"; } else { photo=""; }

<?php
if(isset($_GET['detail'])){ ?>
window.location.replace("index.php?<?=$cari?>&<?=$sohal?>&<?=$haljs?>&operasiringanactionupstokordelete=onfire&del=on"+photo+"<?=$row?>&<?=$halutama?>&id="+id+"&rowd="+c); 

<?php } else { ?>
window.location.replace("index.php?<?=$cari?>&<?=$sohal?>&<?=$haljs?>&operasiringanactionupstokordelete=onfire&del=on&id="+f1+photo+"&row="+c); return true;
<?php } ?>

    }
    else if(apa === 'batal'){
        document.getElementById("confirmdel").style.display = "none";
        return;
    }
    else{
    
    document.getElementById("ketdel").innerHTML = "Data No."+c+" akan dihapus!";
    
    //pasang attribut onclick pada btn confirmasi
    document.getElementById("nodel").onclick = function(){
        hapus(f1, f2, c, id, 'batal'); };
    document.getElementById("yesdel").onclick = function(){
        hapus(f1, f2, c, id, 'oke'); };
    
    document.getElementById("confirmdel").style.display = "block";
	}
	


}

function showhidephoto() {
var x = document.getElementById("optbp");
if(x.style.display==="none")
{
	const element = document.getElementById("arrgp");
	element.classList.add("arrowgreen");
	element.classList.remove("hidearrg");
	
	const element2 = document.getElementById("arrp");
	element2.classList.add("arrow");
	element2.classList.remove("hidearr");

	document.getElementById("shp").innerHTML="Hide-photo ";
	document.getElementById("optbp").style.display="";
	for(i=<?=$from?>+1;i<=<?=$n+$from?>; i++)
	{
	document.getElementById("img"+i).style.display="";
	}
}
else{
	const element = document.getElementById("arrgp");
	element.classList.add("hidearrg");
	element.classList.remove("arrowgreen");
	
	const element2 = document.getElementById("arrp");
	element2.classList.add("hidearr");
	element2.classList.remove("arrow");

	document.getElementById("shp").innerHTML="Show-photo";
	document.getElementById("optbp").style.display="none";
	for(i=<?=$from?>+1; i<=<?=$n+$from?>; i++)
	{
	document.getElementById("img"+i).style.display="none";
	}
}
}

function showhide() {
var x = document.getElementById("optb");
var y = window.matchMedia("(max-width: 800px)") //pencocokan untuk layar HP

if(x.style.display==="none")
{ 

	const element = document.getElementById("arrg");
	element.classList.remove("arrowgreen");
	element.classList.add("hidearrg");
	
	const element2 = document.getElementById("arr");
	element2.classList.remove("arrow");
	element2.classList.add("hidearr");
	document.getElementById("sh").innerHTML="Hide-action ";
	document.getElementById("optb").style.display="";
		
	for(i=<?=$from+1?>;i<=<?=$n+$from?>; i++)
	{
	
	<?php if(!isset($_GET['detail'])){ ?>
	document.getElementById("stokins"+i).disabled=false;
	document.getElementById("stokins"+i).classList.add("detailhover");
	document.getElementById("stokins"+i).style["pointer-events"]="auto";
	document.getElementById("stok"+i).style.display="";
	if (y.matches) { // If media query matches
	document.getElementById("stokins"+i).classList.add("detailunderline");
	}
	<?php } ?>
	document.getElementById("ub"+i).style.display=""; 
	}
}
else{ 
	const element = document.getElementById("arrg");
	element.classList.remove("hidearrg");
	element.classList.add("arrowgreen");
	
	const element2 = document.getElementById("arr");
	element2.classList.remove("hidearr");
	element2.classList.add("arrow");

	document.getElementById("sh").innerHTML="Show-action";
	document.getElementById("optb").style.display="none";
	
	for(i=<?=$from+1?>;i<=<?=$n+$from?>; i++)
	{
	<?php if(!isset($_GET['detail'])){ ?>
	document.getElementById("stokins"+i).disabled=true;
	document.getElementById("stokins"+i).style["pointer-events"]="none";
	document.getElementById("stok"+i).style.display="none";
	if (y.matches) { // If media query matches
	document.getElementById("stokins"+i).classList.remove("detailunderline");
	}
	<?php } ?>
	document.getElementById("ub"+i).style.display="none";
	}
}
}

//switching show stok and price
function viewprice(){

document.getElementById("price").style.background="transparent";
document.getElementById("price").style.color="black";

document.getElementById("sortirstokid").style.display="none";
document.getElementById("stok").style.background="#c2c2c2";
document.getElementById("stok").style.color="white";

document.getElementById("price").style["pointer-events"]="none";
document.getElementById("stok").style["pointer-events"]="";
for(i=<?=$from+1?>;i<=<?=$n+$from?>; i++)
	{	
document.getElementById("indi"+i).style.display="none";
document.getElementById("jual"+i).style.display="";
document.getElementById("stokins"+i).style.display="none";
document.getElementById("tdstok"+i).style.textAlign = "right";
	}
}
function viewstok(){

document.getElementById("price").style.background="#c2c2c2";
document.getElementById("price").style.color="white";
document.getElementById("sortirstokid").style.display="";
document.getElementById("stok").style.background="transparent";
document.getElementById("stok").style.color="black";

document.getElementById("price").style["pointer-events"]="";
document.getElementById("stok").style["pointer-events"]="none";
for(i=<?=$from+1?>;i<=<?=$n+$from?>; i++)
	{
document.getElementById("indi"+i).style.display="";
document.getElementById("jual"+i).style.display="none";
document.getElementById("stokins"+i).style.display="";
document.getElementById("tdstok"+i).style.textAlign = "left";
	}
}

// untuk paging
function paging(nx, ira, mx) {
<?php if(!isset($_GET['detail'])){ ?> //hold view price hamya utk tabel utama
var price = document.getElementById("price").style.color; 
if(price == "black"){ price = "&price=onview"; } else{ price = ""; } 
<?php } else { ?> var price = ""; <?php } ?>

var p = document.getElementById("optbp");
if(p.style.display===""){ photo="photo=on&"; } else { photo=""; }
var q = document.getElementById("optb");
if(q.style.display===""){ act="delokorupok17912457381x9djghzmtqhjrlckawutso16=okprocesssuccess67830123440&"; } else{ act=""; }


window.location.replace("index.php?"+photo+act+"<?=$halutama?><?=$cari?>&pagereplace=onpages&<?=$sohal?><?=$row?>&hal="+nx+"&ira="+ira+"&mx="+mx+price);
}

function exitpage(){
window.location.replace("login.php?logout=on");
}

function sales()
{
//for mode hp, menu diperintah click lg shg tertutup
document.getElementById("menuhp").click();
window.open("penjualan.php?kasir=<?=$user?>", "_parent");
}

function custompage(custom)
{
var hal = document.getElementById("halaman");
if(custom == "pilihcustom"){
    document.getElementById("custom").checked = true;
    hal.focus();
    return;
}

if(custom == "c" ) {
hal.focus();
} else { 
hal.value="";
}
}

function printchoice()
{
//for mode hp, menu diperintah click lg shg tertutup
document.getElementById("menuhp").click();

document.getElementById('id02').style.display="block";
}

function checkprintex(){
var warn = document.getElementById("warningprint"); 
var ex = document.getElementById("halaman"); 
var n = ex.value.split("-");
if(n[0] % 1 !== 0) { ex.value=""; }
if(n[0] > <?=$total_pages?> || n[1] > <?=$total_pages?>) {
ex.style.borderColor="red"; warn.innerHTML="maksimal <?=$total_pages?> halaman !";  ex.focus(); return false; 
}
else { ex.style.borderColor="";
warn.innerHTML="";
return true;  }
}

function printex()
{
var h = document.getElementById("halaman"); 
var warn = document.getElementById("warningprint").innerHTML;
var n = h.value.split("-");

if(warn !="") { h.focus(); return false; } 
var all = document.getElementById("all");
var custom = document.getElementById("custom");
if(all.checked == true) { var ex = "all"; }
else if(custom.checked == true) {

var ex = h.value;
if(!isNaN(ex)) { //jika kosong atau hanya hal awal
if(ex === ""){ ex = "<?=$hal?>-<?=$hal?>"; }else { ex = ex+"-"+ex; }
}
}
else { var ex = ""; }

var addr = window.location.href;
if(addr.includes("index.php?")) { 
window.open(addr+"&printon_newwindow=cetaksaja12345&page="+ex, "_blank");
} else { window.open("index.php?printon_newwindow=cetaksaja12345&page="+ex, "_blank"); }
document.getElementById("halaman").value="";
document.getElementById("halaman").disabled=true;
document.getElementById("cp").checked=true;
document.getElementById("id02").style.display="none";

}

function cancelprint() {
document.getElementById('id02').style.display='none'
}

//sortir nama/stok dari tabel database
function sortir(){
//hold viewprice
<?php if(!isset($_GET['detail'])){ ?> //hold view price hamya utk tabel utama
var price = document.getElementById("price").style.color; 
if(price == "black"){ price = "&price=onview"; } else{ price = ""; } 
<?php } else { ?> var price = ""; <?php } ?>
//hold photo
var p = document.getElementById("optbp");
var q = document.getElementById("optb");
if(p.style.display===""){ photo="&photo=on"; } else { photo=""; }
if(q.style.display===""){ act="&delokorupok17912457381x9djghzmtqhjrlckawutso16=okprocesssuccess67830123440"; } else{ act=""; }

<?php
if(isset($_GET['detail']))//sortir detail masukan barang
{
?>
window.location.replace("<?=$sortir?>&tombolon=shorting&switchingtable=instokopenmasuk16&kode=<?=$kode?>&nama=<?=$_GET['nama']?><?=$row?>&openmasuk=on&detail=openonekodeshowon87"+photo+act+"&<?=$haljs?>");
<?php
} 
else { ?>
checkbox = document.getElementById("sortirstokid");
if(checkbox.checked == true)
{ 
window.location.replace("<?=$sortir?>&tombolon=shorting&sortirstok=onexcecsortingstok16"+photo+act+"&<?=$haljs?><?=$row?>");
} else {
window.location.replace("<?=$sortir?>&tombolon=shorting"+photo+act+price+"&<?=$haljs?><?=$row?>");
}
<?php } ?>
}

//keterangan pada tombol sortir
function titlesortir(checkbox)
{ 
var keterangan = document.getElementById("tombolsortir");
if(checkbox.checked == true)
{	
	keterangan.setAttribute("title","Urutkan Stok !");
}
else {	keterangan.setAttribute("title","Urutkan Nama barang!"); }
}

//sortir urutan nomor asc (just user page)
function sortTablenum() { 
  var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
  table = document.getElementById("datatable");

  switching = true;
  //atur default ascending:
  dir = "asc"; 
  /*Make a loop that will continue until
  no switching has been done:*/
  while (switching) { 
    //start by saying: no switching is done:
    switching = false;
    rows = table.rows;

    /*Loop through all table rows (except the
    first, which contains table headers):*/
    for (i = 1; i < (rows.length); i++) { 

      //start by saying there should be no switching:
      shouldSwitch = false;
      /*Get the two elements you want to compare,
      one from current row and one from the next:*/
      x = rows[i].getElementsByTagName("TD")[0];
      y = rows[i + 1].getElementsByTagName("TD")[0];
      //check if the two rows should switch place:
      if (dir == "asc") { 
      	if (Number(x.innerHTML) > Number(y.innerHTML)) {
        //if so, mark as a switch and break the loop:
        shouldSwitch = true;
        break;
      	}
      }
      else if (dir == "desc") {
	if (Number(x.innerHTML) < Number(y.innerHTML)) {
        //if so, mark as a switch and break the loop:
        shouldSwitch = true;
        break;
      	}
      }

    }
    if (shouldSwitch) {
      /*If a switch has been marked, make the switch
      and mark that a switch has been done:*/
      rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
      switching = true;
      //Each time a switch is done, increase this count by 1:
      switchcount ++; 
    }else {
      /*If no switching has been done AND the direction is "asc",
      set the direction to "desc" and run the while loop again.*/
      if (switchcount == 0 && dir == "asc") {
        dir = "desc";
        switching = true;
      }
    }

  }
}


//sortir view data text table per halaman html (bukan dari database)
function sortTabletext(n) {
  var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
  table = document.getElementById("datatable");
  switching = true;
  //atur default ascending:
  dir = "asc"; 
  /*Make a loop that will continue until
  no switching has been done:*/
  while (switching) {
    //start by saying: no switching is done:
    switching = false;
    rows = table.rows;
    /*Loop through all table rows (except the
    first, which contains table headers):*/
    for (i = 1; i < (rows.length - 1); i++) {
      //start by saying there should be no switching:
      shouldSwitch = false;
      /*Get the two elements you want to compare,
      one from current row and one from the next:*/
      x = rows[i].getElementsByTagName("TD")[n];
      y = rows[i + 1].getElementsByTagName("TD")[n];
      /*check if the two rows should switch place,
      based on the direction, asc or desc:*/
      if (dir == "asc") {
        if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
          //if so, mark as a switch and break the loop:
          shouldSwitch= true;
          break;
        }
      } else if (dir == "desc") {
        if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
          //if so, mark as a switch and break the loop:
          shouldSwitch = true;
          break;
        }
      }
    }
    if (shouldSwitch) {
      /*If a switch has been marked, make the switch
      and mark that a switch has been done:*/
      rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
      switching = true;
      //Each time a switch is done, increase this count by 1:
      switchcount ++;      
    } else {
      /*If no switching has been done AND the direction is "asc",
      set the direction to "desc" and run the while loop again.*/
      if (switchcount == 0 && dir == "asc") {
        dir = "desc";
        switching = true;
      }
    }
  }
}

/* perintah click dengan keyboard enter */
var input = document.getElementById("datacari");
input.addEventListener("keypress", function(event) {
	if (event.key === "Enter") {
	event.preventDefault();
	document.getElementById("submitcari").click();
	}
});

function kalkulasistok()
{
let stokold = parseInt(document.getElementById("fstoknew").placeholder);
let instok = parseInt(document.getElementById("finstok").value);
if(isNaN(stokold)) { stokold = 0; }
stok = stokold + instok;
if(isNaN(stok)) { 
	document.getElementById("fstoknew").value = ""; 
	document.getElementById("finstok").value = "";
	return false; }
else { 
document.getElementById("finstok").value = instok;
document.getElementById("fstoknew").value = stok; }
}

//update stok box
function onstokup(checkbox)
{
if(checkbox.checked == true)
{
document.getElementById("finstok").removeAttribute("readonly");
document.getElementById("finstok").focus();
}
else { 
document.getElementById("finstok").value="";
document.getElementById("fstoknew").value="";
document.getElementById("finstok").setAttribute("readonly",true); }
}

function inputsn()
{
var serial = document.getElementById("sn").value;
var stokold = document.getElementById("fstoknew").placeholder;
var stoknew = document.getElementById("fstoknew").value; 
var koma = ",";
var j = 1;
for(i=1; i <= serial.length; i++)
{
if(serial[i] === koma) { j++; }
} 
if(j > stokold && j > stoknew){
if(parseInt(stokold) < 1 && stoknew === "") {
document.getElementById("sn").value = "";
document.getElementById("warningsn").innerHTML="Stok masih kosong !";  return; 
}else {
document.getElementById("warningsn").innerHTML=""; 
document.getElementById("warningsn").innerHTML="Jumlah SN sudah memenuhi Stok !";
document.getElementById("sn").maxLength = serial.length-1;
return;
} } 
else { document.getElementById("sn").maxLength = "10000";
document.getElementById("warningsn").innerHTML="";  return; }
}


/* pencarian data tanpa form dengan mempertahankan nilai userpage seperti foto dan tombol aksi */
function caridata(){
var data=document.getElementById("datacari").value;
if(data==="") { document.getElementById("datacari").focus(); return false; }
//hold viewprice
var price = document.getElementById("price").style.color;
if(price == "black"){ price = "&price=onview"; } else{ price = ""; }
//hold photo
var p = document.getElementById("optbp");
var q = document.getElementById("optb");
if(p.style.display===""){ photo="&photo=on"; } else { photo=""; }
if(q.style.display===""){ act="&delokorupok17912457381x9djghzmtqhjrlckawutso16=okprocesssuccess67830123440"; } else{ act=""; }

window.location.replace("index.php?cari="+data+photo+act+price);
}
function tutupcari(){
//hold viewprice
var price = document.getElementById("price").style.color;
if(price == "black"){ price = "&price=onview"; } else{ price = ""; }
//hold photo
var p = document.getElementById("optbp");
var q = document.getElementById("optb");
if(p.style.display===""){ photo="&photo=on"; } else { photo=""; }
if(q.style.display===""){ act="&delokorupok17912457381x9djghzmtqhjrlckawutso16=okprocesssuccess67830123440"; } else{ act=""; }
window.location.replace("index.php?"+photo+act+price);
}

//zoom img
function zoomimg(id) {
  // ambil foto dari sumbernya
  const img = document.getElementById(id);
  const src = img.src;

  //buat modal
  const modal = document.createElement('div');
  modal.style.position = 'fixed';
  modal.style.top = '0';
  modal.style.left = '0';
  modal.style.width = '100%';
  modal.style.height = '100%';
  modal.style.background = 'rgba(0,0,0,0.5)';
  modal.style.display = 'flex';
  modal.style.justifyContent = 'center';
  modal.style.alignItems = 'center';

  //buat container untuk foto
  const container = document.createElement('div');
  container.style.position = 'relative';
  container.style.display = 'flex';
  container.style.flexDirection = 'column';
  container.style.alignItems = 'flex-end';
  container.style.maxWidth = '90vw';
  container.style.maxHeight = '90vh';
  container.style.overflow = 'auto';

  //buat tombol X di atas foto
  const closeBtn = document.createElement('div');
  closeBtn.style.display = 'block';
  closeBtn.style.width = '30px';
  closeBtn.style.height = '30px';
  closeBtn.style.borderRadius = '50%';
  closeBtn.style.display = 'flex';
  closeBtn.style.justifyContent = 'center';
  closeBtn.style.top = '0';
  closeBtn.style.marginTop = '15px';
  closeBtn.style.right = '10px';
  
  closeBtn.style.alignItems = 'center';
  closeBtn.style.cursor = 'pointer';

  const spanX = document.createElement('span');
  spanX.innerHTML = 'X';
  spanX.classList.add('close');
  closeBtn.appendChild(spanX);

  //pindahkan foto ke variabel baru
  const imgZoom = document.createElement('img');
  imgZoom.src = src;
  imgZoom.style.maxWidth = '100%';
  imgZoom.style.maxHeight = '90%';

  //tautkan tombol X dan foto ke container
  container.appendChild(closeBtn);
  container.appendChild(imgZoom);

  //tautkan container ke modal
  modal.appendChild(container);

  //hapus modal ketika klik X
  closeBtn.addEventListener('click', function(){
    modal.remove();
  });

  //hapus modal ketika diclick
  modal.addEventListener('click', function(event){
    if(event.target === this){
      modal.remove();
    }
  });

  //tampilkan modal berisi foto pada halaman body
  document.body.appendChild(modal);
}

/*tampilkan tabel data brg setelah semua kode dirunning*/
document.getElementById('headertbl').style.display = "block";
document.getElementById('frtable').style.display = "block";

/* https://www.w3schools.com/howto/tryit.asp?filename=tryhow_js_trigger_button_enter referensi:https://javascript.info/ 

keycode keyboard js : https://keyjs.dev/
*/
</script>
<?php } ?>
</body>
</html>
