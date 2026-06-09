<?php
include("validasi.php");

if(!isset($_GET['hal'])){ //tetapkan halaman default
$hal = 1;
}
else { //tangkap halaman yang dipilih
$hal = $_GET['hal'];
}

//pilhan tabel
if(isset($_GET['switchingtable']))
{
if(isset($_GET['openmasuk'])) { //tangkap halaman tabel asal
$hal2=$hal; $ira2=$_GET['ira']; $mx2=$_GET['mx'];
$hal=1; } //ubah hal ke default utk tabel detail
else { $hal2=$_GET['hal2']; $ira2=$_GET['ira2']; $mx2=$_GET['mx2']; }

if(isset($_GET['detail'])){
$kode=$_GET['kode'];
$nama=$_GET['nama'];
$table="masuk where kode='$kode'"; //pilih tabel masuk

$halutama="&switchingtable=instokopenmasuk16&detail=openonekodeshowon87&kode=$kode&nama=$nama&detail=openonekodeshowon87&hal2=".$hal2."&ira2=".$ira2."&mx2=".$mx2; //pertahankan halaman asal
}

} else { $table="buah"; $halutama = ""; $hal2=""; $ira2=""; $mx2=""; } //tabel utama


//tetapkan banyaknya row data yang diambil dari tabel db
$max_results = 10;  

//custom halaman print
if(isset($_GET['page'])){
$p=$_GET['page']; 
if($p!="all" && $p!="") { 
$pg = explode("-", $p);
if(Is_numeric($pg[1]) && Is_numeric($pg[0]) && $pg[1] > $pg[0]) {
$selisih = $pg[1] - $pg[0];
}else { $selisih = 0; }

$hal=$pg[0]; //set nila- awal printing hal
$maxs = $max_results * ($selisih+1); //set banyaknya row yg diambil
}else { $maxs =""; }
}else { $maxs =""; }

//rumus titik row tabel yang diambil
$from = (($hal * $max_results) - $max_results);

if($maxs!="") { $max_results = $maxs; } //set nilai max printing hal

///// akses database /////
include "dbon.php";

$dt=$konek->prepare("select * from $table");
$data=$konek->prepare("select*from $table order by id desc limit $from, $max_results");

if(isset($_GET['sortir']))
{
$index=$_GET['sortir'];
if(isset($_GET['sortirstok']) && !isset($_GET['whileonsortirstok'])) { $field="stok"; }
elseif(isset($_GET['detail'])){ $field="instok"; }
else { $field="nama"; }
$data=$konek->prepare("select*from $table order by $field $index limit $from, $max_results");
}

//hold nilai pecarian dari tabel utama agar bisa dikembalikan dari show tabel instok
if(isset($_GET['detail']) && isset($_GET['cari'])){ 
$cari='cari='.$_GET['cari']; } else { $cari=""; }

//pencarian hanya untuk tabel utama bukan untuk tabel detail instok
if(!isset($_GET['detail']))
{
if(isset($_GET['cari'])!="")
{
	$cari=$_GET['cari']; //nilai dari box pencarian
	$select="select * from $table where kode like '%$cari%' or nama like '%$cari%'";
	$data=$konek->prepare("$select limit $from, $max_results");
	$dt=$konek->prepare("$select");
	if(isset($_GET['sortir'])) //jika ada sortir dalam pencarian
	{
	if(isset($_GET['sortirstok'])) { $field="stok";}else { $field="nama"; }
	$data=$konek->prepare("$select order by $field $index limit $from, $max_results");
	$dt=$konek->prepare("$select order by $field $index");
	}
	$cari='cari='.$cari;	
}
else { $cari=""; }
}

//setelah cari bisa disortir datanya 

$dt->execute();
$nt=$dt->rowCount(); //n row all
$data->execute();
$n=$data->rowCount(); //n row view data

//rumus total tombol hal yang dibentuk (paging)
$total_pages = ceil($nt / $max_results);

//untuk directing address sorting data asc desc
if(isset($_GET['sortir']))
{
$sortir=$_GET['sortir'];
if(isset($_GET['sortirstok'])){
$sortirfield="sortirstok=onexecsortingstok16&"; }else { $sortirfield=""; }
if($sortir=='asc'){
$sortir='index.php?sortir=desc&'.$cari; $sohal=$sortirfield.'sortir=asc'; }
if($sortir=='desc'){
$sortir='index.php?'.$cari; $sohal=$sortirfield.'sortir=desc'; }
}
else{
$sortir='index.php?sortir=asc&'.$cari; $sohal=''; } //sohal sebagai mode sortir
?>
