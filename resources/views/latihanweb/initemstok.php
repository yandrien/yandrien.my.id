<?php
session_start();

//procedural :$konek=mysqli_connect("localhost", "root", "","ci4tutorial");
//insert INTO user (user, password, emailvalues('user',sha2('pass',256),'aku@gmail.com');
//PDO (PHP Data Object)
try {
	$konek=new PDO("mysql:host=localhost;dbname=ci4tutorial","root","");
	$konek->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e) {
 echo "Database off!"; 
// echo $e->getMessage(); 
}

/////////////////////////login.
if (isset($_POST['login'])) {
   
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
                    $_SESSION['uname'] = $datal['user'];
                    $_SESSION['#160187sesionaktif'] = $datal['password'];
		    setcookie('user', $datal['email'], time() + (86400 * 30), "/"); // 86400 = 1 day
                    header('Location: index.php');
		    exit();
                } else {
                    $errMsg = 'Password tidak cocok.';
		     header('Location: login.php?error=passowrd_salah!');
			exit();
                }
            }
        }
        catch(PDOException $e) {
            $errMsg = $e->getMessage();
        }
  }
if (!isset($_SESSION['#160187sesionaktif']) || !isset($_COOKIE['user'])) {
    header('Location: login.php');
    exit();
}



///////////////////////////
if(!isset($_GET['hal'])){
$page = 1;
} else {
$page = $_GET['hal'];
}
$max_results = 20;  //total list
$from = (($page * $max_results) - $max_results);
if(isset($_GET['hal'])){$hal = $_GET['hal'];} //set default halaman
else{$hal = 1;}


$dt=$konek->prepare("select * from masuk");
$data=$konek->prepare("select*from mask order by id desc limit $from, $max_results");

if(isset($_GET['sortir']))
{
$index=$_GET['sortir'];
$data=$konek->prepare("select*from masuk order by tanggl_masuk $index limit $from, $max_results");
}

if(isset($_GET['cari'])!="")
{
	$cari=$_GET['cari']; //nilai dari box pencarian
	$data=$konek->prepare("select * from masuk where kode='$cari' or nama_buah='$cari' or bentuk='$cari' or warna='$cari' or kode like '%$cari%' or nama_buah like '%$cari%' or bentuk like '%$cari%' or warna like '%$cari%' limit $from, $max_results");
	$dt=$konek->prepare("select * from masuk where kode='$cari' or nama_buah='$cari' or bentuk='$cari' or warna='$cari' or kode like '%$cari%' or nama_buah like '%$cari%' or bentuk like '%$cari%' or warna like '%$cari%'");
	if(isset($_GET['sortir'])) //jika ada sortir dalam pencarian
	{
	$data=$konek->prepare("select * from masuk where kode='$cari' or nama_buah='$cari' or bentuk='$cari' or warna='$cari' or kode like '%$cari%' or nama_buah like '%$cari%' or bentuk like '%$cari%' or warna like '%$cari%' order by nama_buah $index limit $from, $max_results");
	
	$dt=$konek->prepare("select * from masuk where kode='$cari' or nama_buah='$cari' or bentuk='$cari' or warna='$cari' or kode like '%$cari%' or nama_buah like '%$cari%' or bentuk like '%$cari%' or warna like '%$cari%' order by nama_buah $index");
	
	}
	$cari='cari='.$cari;	
}
else { $cari=""; }

//setelah cari bisa disortir datanya 

$dt->execute();
$nt=$dt->rowCount(); //n row all
$data->execute();
$n=$data->rowCount(); //n row view data

//cek status view foto
if(isset($_GET['photo'])){ $photo=$_GET['photo']; } else { $photo=""; }

//untuk directing address sorting data asc desc
if(isset($_GET['sortir']))
{
	$sortir=$_GET['sortir'];
	if($sortir=='asc'){ $sortir='index.php?sortir=desc&'.$cari; $sohal='sortir=asc'; }
	if($sortir=='desc'){ $sortir='index.php?'.$cari; $sohal='sortir=desc'; }
}
else{
$sortir='index.php?sortir=asc&'.$cari; $sohal=''; } 

//sohal utk membawa sortir melalui index halaman di bawah

//hold view photo or not
if(isset($_GET['photo'])){ $photo="&photo=on"; }else { $photo="";}


if(isset($_GET['del'])=='on' && isset($_GET['id'])!="")
{
	$id=$_GET['id'];
	
	try{
	$delete=$konek->prepare("delete from masuk where kode=:kode");
	$delete->execute(array(':kode'=>$id));	
	}
	catch(PDOExeption $e) { echo "gagal  hapus".$e->getMessage(); }
	if(isset($_GET['hal'])){
	$hal=$_GET['hal'];
	$ira=$_GET['ira'];
	$mx=$_GET['mx']; 
	header("location:initemstok.php?$cari&$sohal&hal=$hal&ira=$ira&mx=$mx&delokorupok17912457381x9djghzmtqhjrlckawutso16=okprocesssuccess67830123440".$photo);
	exit(); }
	else{ header("location:index.php?$cari&$sohal&&delokorupok17912457381x9djghzmtqhjrlckawutso16=okprocesssuccess67830123440".$photo);
	exit(); }

}


if(isset($_GET['operasi'])!='')
{
$imgholderror = $_POST['imgholderror'];
$kode=$_POST['kode'];
$nama=$_POST['nama'];
$bentuk=$_POST['bentuk'];
$warna=$_POST['warna'];
$tanggalin=$_POST['tanggalin'];
$imagep = $_FILES['foto']['name'];
$image = $_FILES['foto']['tmp_name']; 
if(!empty($image)){ $foto = file_get_contents($image); } else { $foto=""; }
$images = $_FILES['foto']['size'];
$lower=strtolower($nama);//hanya mencoba karna sempat pencarian sempat membedakan hruf kecil besar

if(isset($_GET['input'])=='save')
{
	//hold view tombol act or not
 	if(isset($_GET['delokorupok17912457381x9djghzmtqhjrlckawutso16'])) {
	 $act="&delokorupok17912457381x9djghzmtqhjrlckawutso16=".$_GET['delokorupok17912457381x9djghzmtqhjrlckawutso16']; } else { $act=""; }
	
	//verifikasi ukuran foto <=64KB
	if($images>64000){ 
$hold1="errorfailedduplicateddata=needtorevitiondatainput";
	header("location:index.php?".$hold1.$photo.$act."&kode=".$kode."&nama=".$nama."&bentuk=".$bentuk."&warna=".$warna."&tanggalin=".$tanggalin."&info=Ukuran foto tidak boleh lebih dari 64KB !");
	exit();
	}

	//cek kode buah, harus unik
	$data=$konek->prepare("select * from buah where kode='$kode'");
	$data->execute();
	$cek=$data->rowCount();
	if($cek>0)
	{
	// echo "<script class='modal'>alert('Gagal, data ini sudah ada di dalam database!');history.go(-1);</script>";
	$hold1="errorfailedduplicateddata=needtorevitiondatainput";
	header("location:index.php?".$hold1.$photo.$act."&kode=".$kode."&nama=".$nama."&bentuk=".$bentuk."&warna=".$warna."&tanggalin=".$tanggalin."&info=Kode_".$kode." sudah ada di dalam database!");
	exit();

	}
	
	//cek nama buah, harus unik
	$data=$konek->prepare("select * from buah where nama_buah='$nama'");
	$data->execute();
	$cek=$data->rowCount();
	if($cek>0)
	{
	// echo "<script class='modal'>alert('Gagal, data ini sudah ada di dalam database!');history.go(-1);</script>";
	$hold1="errorfailedduplicateddata=needtorevitiondatainput";
	header("location:index.php?".$hold1.$photo.$act."&kode=".$kode."&nama=".$nama."&bentuk=".$bentuk."&warna=".$warna."&tanggalin=".$tanggalin."&info=Nama_".$nama." sudah ada di dalam database!");
	exit();

	}


	if($kode!="" && $nama!="")
	{
	try{
	
	$insert=$konek->prepare("insert into buah(kode,nama_buah,bentuk,warna,tanggal,foto) 	values(:kode,:nama,:bentuk,:warna, :tanggal, :foto)");
	$array=array(':kode'=>$kode, ':nama'=>$nama, ':bentuk'=>$bentuk, ':warna'=>$warna, ':tanggal'=>$tanggalin, 
	':foto'=>$foto);
	$insert->execute($array);
	header("location:index.php?modeadd=hold1".$photo.$act);
	exit();
	}
	catch(PDOExeption $e){ echo "gagal menyimpan data".$e->getMessage(); }
	}
	
}
elseif(isset($_GET['update'])=='save')
{	
	//verifikasi ukuran foto <=64KB
	if($images>64000){ 
	$hold1="&errorfailedduplicateddataupdate=needtorevitiondataupdate";
	header("location:index.php?$cari&$sohal&hal=$hal&ira=$ira&mx=$mx&delokorupok17912457381x9djghzmtqhjrlckawutso16=okprocesssuccess67830123440".$hold1.$photo.$act."&kode=".$kode."&nama=".$nama."&bentuk=".$bentuk."&warna=".$warna."&tanggalin=".$tanggalin."&imgholderror=".$imgholderror."&duplicated=Ukuran foto tidak boleh lebih dari 64KB !");
	exit();
	}

	$id=$_POST['id'];
	//cek kode buah, harus unik
	$data=$konek->prepare("select * from buah where kode<>'$id' and kode='$kode'");
	$data->execute();
	$cek=$data->rowCount();
	if($cek>0)
	{
	// echo "<script class='modal'>alert('Gagal, duplikasi data!');history.go(-1);</script>";
$hold1="&errorfailedduplicateddataupdate=needtorevitiondataupdate";
	header("location:index.php?$cari&$sohal&hal=$hal&ira=$ira&mx=$mx&delokorupok17912457381x9djghzmtqhjrlckawutso16=okprocesssuccess67830123440".$hold1.$photo.$act."&kode=".$kode."&nama=".$nama."&bentuk=".$bentuk."&warna=".$warna."&tanggalin=".$tanggalin."&imgholderror=".$imgholderror."&duplicated=Kode_".$kode." sudah ada di dalam database!");
	exit();
	}

	//cek nama buah, harus unik
	$data=$konek->prepare("select * from buah where kode<>'$id' and nama_buah='$nama'");
	$data->execute();
	$cek=$data->rowCount();
$cek=$data->rowCount();
	if($cek>0)
	{
	
	// echo "<script class='modal'>alert('Gagal, duplikasi data!');history.go(-1);</script>";
$hold1="&errorfailedduplicateddataupdate=needtorevitiondataupdate";
	header("location:index.php?$cari&$sohal&hal=$hal&ira=$ira&mx=$mx&delokorupok17912457381x9djghzmtqhjrlckawutso16=okprocesssuccess67830123440".$hold1.$photo.$act."&kode=".$kode."&nama=".$nama."&bentuk=".$bentuk."&warna=".$warna."&tanggalin=".$tanggalin."&imgholderror=".$imgholderror."&duplicated=Nama_".$nama." sudah ada di dalam database!");

	exit();
	}

	try{
	$update=$konek->prepare("update buah set kode=:kode, nama_buah=:nama,
	bentuk=:bentuk, warna=:warna, tanggal=:tanggal where kode='$id'");
	$array=array(':kode'=>$kode, ':nama'=>$nama, ':bentuk'=>$bentuk, ':warna'=>$warna, ':tanggal'=>$tanggalin);
	$update->execute($array);
	if(!empty($foto)){ $imgup=$konek->prepare("update buah set foto=:foto where kode='$id'");
	$imgup->bindparam(':foto', $foto); $imgup->execute(); }

	}catch(PDOExeption $e){ echo "gagal mengupdate data".$e->getMessage(); }

	if(isset($_POST['hal'])){
	$hal=$_POST['hal'];
	$ira=$_POST['ira'];
	$mx=$_POST['mx']; 
	header("location:index.php?$cari&$sohal&hal=$hal&ira=$ira&mx=$mx&delokorupok17912457381x9djghzmtqhjrlckawutso16=okprocesssuccess67830123440".$photo);
	exit(); }
	else{ header("location:index.php?$cari&$sohal&delokorupok17912457381x9djghzmtqhjrlckawutso16=okprocesssuccess67830123440".$photo);
	exit(); }

}
header("location:index.php");
exit();
}

?>
<!DOCTYPE html>
<html>

<head>
<meta name="viewport" content="width=device-width, initial-scale=0.75">
<title>crud php html</title>
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
</head>
<style type="text/css">
        @import url('stylecrudbuah.css');
</style>
<body>
<?php
if(isset($_GET['modeadd'])=='hold1')//form tetap terbuka setelah pengisian & scrool halaman agar data terlihat 
{  ?>
<script type="text/javascript">
window.onload=function blok(){
	//window.scrollTo(0, 120);
	tambah(); 
	}
</script>
<?php
} 
//jika terjadi duplikasi data input
if(isset($_GET['errorfailedduplicateddata']))
{ ?>
<script type="text/javascript">
window.onload=function blok(){
	revisitambah("<?=$_GET['kode']?>","<?=$_GET['nama']?>","<?=$_GET['bentuk']?>","<?=$_GET['warna']?>","<?=$_GET['tanggalin']?>","<?=$_GET['info']?>"); 

	}
</script>
<?php
}
//jika terjadi duplikasi data update
if(isset($_GET['errorfailedduplicateddataupdate']))
{ ?>
<script type="text/javascript">
window.onload=function blok(){
	revisiubah("<?=$_GET['kode']?>","<?=$_GET['nama']?>","<?=$_GET['bentuk']?>","<?=$_GET['warna']?>","<?=$_GET['tanggalin']?>","<?=$_GET['imgholderror']?>","<?=$_GET['duplicated']?>"); 

	}
</script>
<?php
}
//if(isset($_GET['pagereplace']))//atur scrool  ke navigasi halaman (dimatikan-pakai frametable scrollable css aj dulu)
//{  ?>
<script type="text/javascript">
/*window.onload=function blok(){
	window.scrollTo(0, 140);
	}*/
</script>
<?php //} ?>

<div id="id01" class="modal">
   <form class="modal-content animate" method="post" id="formd" enctype="multipart/form-data">

<?php 
//sisip halaman agar tetap pada halam setelah diupdate
if(isset($_GET['hal'])){ ?>
<input type="hidden" value="<?=$_GET['hal']?>" name="hal">
<input type="hidden" value="<?=$_GET['ira']?>" name="ira">
<input type="hidden" value="<?=$_GET['mx']?>" name="mx">

<?php } 
?> 
    <div class="container">
      <table width='100%' border="0">
<tr>
<td align='right' bgcolor="white" width="20%">&nbsp;</td>
<td bgcolor="white"><center style="font-size:150%; color:#c2c2c2; font-family:verdana;"><span id="titleop"></span></center></td>
<td align='right' bgcolor="white" width="20%"><span onclick="document.getElementById('id01').style.display='none'" class="close" title="Close">&times;</span>
</td></tr></table>
	<input type="hidden" name="imgholderror" id="img">
	<input type="hidden" name="id" id="oldid">
	<table width="85%" align="center"><tr><td width="20%">
     <label for="kode"><b>Kode</b></label></td>
      <td><input type="text" placeholder="kode buah" name="kode" required id="fkode"><br /></td></tr>

      <tr><td><label for="nama" width="20%"><b>Nama Buah</b></label></td>
      <td><input type="text" placeholder="nama buah" name="nama" required id="fnama"></td></tr>

      <tr><td><label for="bentuk" width="20%"><b>Bentuk</b></label></td>
      <td><input type="text" placeholder="bentuk buah" name="bentuk" required id="fbentuk"></td></tr>
      <tr><td><label for="warna" width="20%"><b>Warna</b></label></td>
      <td><input type="text" placeholder="warna buah" name="warna" required id="fwarna"></td></tr>
      <tr><td><label for="tanggal" width="20%"><b>Tanggal Masuk</b></label></td>
      <td><input type="date" name="tanggalin" required id="ftgl" class="tgl"></td></tr>
      <tr><td>Foto Buah</td>
      <td><input type="file" oninput="pic.src=window.URL.createObjectURL(this.files[0])" name="foto"><img id="pic" accept="image/png, image/jpeg" width="80" height="100"></td>
      </tr></table><br /><br />
      <div class="clearfix">
        <center> <button type="button" onclick="cancel()" class="cancelbtn">Cancel</button>
        <button type="submit" class="signupbtn">Save</button>
	</center>
      </div>
    </div>
  </form>
</div>

<div class="topnav">
<div class="leftn">N=<?=$nt?></div>
<div class="leftsearch">
<div class="left">
<input type="text" name="cari" placeholder="Cari..." title="cari" <?php if($cari!=''){ ?> value="<?=$_GET['cari']?>"<?php }?> id="datacari">
</div>
<div class="left">
<input type="submit" value="" class="icon" title="cari" onclick="caridata()" id="submitcari" >
</div>
</div>

<?php
if(isset($_GET['cari'])=='on'){ ?>
<div class="left">
<a title="Tutup pencarian" style="text-decoration:none;" onclick="tutupcari()"><span class="close">&times;</span></a>
</div> <?php } ?>

<div class="rightgrup">
<div class="rightp">
<img src="print2.png" style="height:28px; width:53px; cursor:pointer;" onclick="print()" title="Print to new tab">
</div>

<div class="right">
<button class="buttonadd" onclick="exitpage()">Logout</button>
</div>
<div class="right">
<button onclick="tambah()" title="Tambah data" class="buttonadd">&#43;</button>
</div>
<div class="right">
<?php if(isset($_GET['sortir'])){ $urutan=$_GET['sortir']; if($urutan=="asc"){ echo "<i>Ascending</i>"; }else { echo "<i>Descending</i>"; }} ?>&nbsp;
<button class="buttonadd" onclick="sortir()" title="Sortir nama buah seluruh data!">Sortir</button>
</div>
</div>
</div>
<br /><br />



<div class="title">Ini adalah table data buah:</div>
<div class="topnav">
<div class="left">
<?php if(isset($_GET['photo'])){ ?>
<span onclick="showhidephoto()">
<i class="arrowgreen" id="arrgp"></i><i class="arrow" id="arrp"></i>
</span>
<span id="shp">Hide-photo</span>
<?php } 
else { ?>
<span onclick="showhidephoto()">
<i class="hidearrg" id="arrgp"></i><i class="hidearr" id="arrp"></i>
</span>
<span id="shp">Show-photo</span>
<?php } ?>
</div>
<div class="right">
<?php if(isset($_GET['delokorupok17912457381x9djghzmtqhjrlckawutso16'])){ ?>
<span id="sh">Hide-action</span>
<span onclick="showhide()">
<i class="hidearrg" id="arrg"></i><i class="hidearr" id="arr"></i>
</span> <?php } 
else { ?>
<span id="sh">Show-action</span><span onclick="showhide()">
<i class="arrowgreen" id="arrg"></i><i class="arrow" id="arr"></i></span> <?php } ?>
</div>
</div>


<div class="frametable">
<table id="datatable" class="table" cellpadding="3" width="100%" border="1" align="center">
<tr style="height:30px;">
<th bgColor="#f2f2f2" width="3%" onclick="sortTablenum()" style="cursor: pointer;" title="Click untuk sortir nomor!">No</th>
<th bgColor="#f2f2f2" id="optbp" <?php if(isset($_GET['photo'])){ ?>style="display:'';"<?php } else {?> style="display:none;"<?php }?>>Foto Buah</th>
<th bgColor="#f2f2f2" width="20%">Kode</th><th bgColor="#f2f2f2" width="25%" onclick="sortTabletext(3)" style="cursor: pointer;" title="Click untuk sortir nama buah!">Nama Buah
</th><th bgColor="#f2f2f2" width="20%">Bentuk</th><th bgColor="#f2f2f2" width="20%">Warna</th><th bgColor="#f2f2f2" width="20%">Tanggal Masuk</th><th id="optb" <?php if(isset($_GET['delokorupok17912457381x9djghzmtqhjrlckawutso16'])){ ?>style="display:'';"<?php } else {?> style="display:none;"<?php }?> bgColor="#f2f2f2" colspan="2">Hapus/Ubah</th> 
</tr>
<?php
$i=$from+1;
$j=1;
while($hasil=$data->fetch())
{
if($i<=$n+$from)
{
?>
<tr>
<td width="3%"><?=$i?></td><td id="photo<?=$i?>" <?php if(isset($_GET['photo'])){ ?>style="display:'';"<?php } else {?> style="display:none;"<?php }?> ><?php $f6=$hasil[6]; ?> <img <?php if(!empty($f6)) { ?> src="data:image/jpg;base64,<?=base64_encode($f6)?>" width="70" height="80" <?php } ?> id="img<?=$i?>" /> </td><td><?php $f1=$hasil[1]; echo $f1; ?></td><td><?php $f2=$hasil[2]; echo ucwords($f2); ?></td><td><?php $f3=$hasil[3]; echo ucwords($f3); ?></td><td><?php $f4=$hasil[4]; echo ucwords($f4); ?></td><td><?php $f5=$hasil[5]; $tgl=date_create($f5); $format_tgl=date_format($tgl,'d/m/Y'); echo $format_tgl; ?></td><td id="hp<?=$j?>" <?php if(isset($_GET['delokorupok17912457381x9djghzmtqhjrlckawutso16'])){ ?>style="display:'';"<?php } else {?> style="display:none;"<?php }?> bgcolor="#f1f1f1"><input type="button" value="Hapus" class="button1" onclick="hapus('<?=$f1?>','<?=$f2?>')" ></td><td id="ub<?=$j?>" <?php if(isset($_GET['delokorupok17912457381x9djghzmtqhjrlckawutso16'])){ ?>style="display:'';"<?php } else {?> style="display:none;"<?php }?> bgcolor="#f1f1f1"><input type="button" value="Ubah" class="button2"
onclick="ubah('<?=$f1?>', '<?=$f2?>', '<?=$f3?>', '<?=$f4?>', '<?=$f5?>', 'img<?=$i?>')" ></td></tr>
<?php
}
$i++;
$j++;
} ?>
</table>
</div>
<br /><br />
<div id="badan2">
<?php
$total_pages = ceil($nt / $max_results);
if(isset($_GET['ira']) and isset($_GET['mx']))
{
	$ira=$_GET['ira'];
	$mx=$_GET['mx'];
}
else{$ira=1;$mx=11;} 

if($total_pages==11){$ira=1;$mx=11;}
if($total_pages==21){$ira=11;$mx=21;} 
if($total_pages==31){$ira=21;$mx=31;}

$dv=10;
if($hal==$mx and $hal!=$total_pages){$ira=$mx;$mx=$mx+$dv;}
if($hal<$ira and $ira!=1){
	$sis=$mx-$ira;
	if($sis==$dv){$mx=$mx-$dv;}else{$mx=$mx-$sis;}
	$ira=$mx-$dv;
}
if($mx>$total_pages){$sisa=$mx-$total_pages;$mx=$mx-$sisa;}
if($ira<=0){$ira=1;}

/* bangun jumlah hiperlink halaman*/
if($nt>$max_results)
{
echo "<center>Pilih Halaman<br />";


/* bangun Previous link */
if($hal > 1)
{
$prev = ($page - 1); ?>
<span onclick="next('<?=$prev?>','<?=$ira?>','<?=$mx?>')"><i class="hidearr"></i><i class="hidearrg"></i></span>
<?php }

for($ir = $ira; $ir <=$mx; $ir++)
{
if($hal<=$total_pages)
{
if(($hal) == $ir)
{
echo "&nbsp;$ir&nbsp;";
}
else
{ ?>
<input type="button" value="<?=$ir?>" class="button2" onclick="page('<?=$ir?>','<?=$ira?>','<?=$mx?>')">
<?php }
}
}
/* bangun Next link */
if($hal < $total_pages)
{
$next = ($page + 1); ?>
<span onclick="next('<?=$next?>','<?=$ira?>','<?=$mx?>')"><i class="arrowgreen"></i><i class="arrow"></i></span>
<?php }

echo "</center>";
}

 ?>
</div>

<script>
// Get the modal
//var modal = document.getElementById('id01');

// When the user clicks anywhere outside of the modal, close it
<!--window.onclick = function(event) {
//  if (event.target == modal) {
//    modal.style.display = "none";
 // }
//}
//
function cancel() {
document.getElementById('id01').style.display='none'
document.getElementById('formd').reset();
document.getElementById('pic').src = '';
}
function tambah() {
var p = document.getElementById("optbp");
var q = document.getElementById("optb");
if(p.style.display===""){ photo="&photo=on"; } else { photo=""; }
if(q.style.display===""){ act="&delokorupok17912457381x9djghzmtqhjrlckawutso16=okprocesssuccess67830123440"; } else{ act=""; }

 document.getElementById("formd").action="index.php?operasi=on&input=save"+photo+act;
 document.getElementById("titleop").innerHTML="Input Data";
 document.getElementById('id01').style.display='block';
}

function revisitambah(f1, f2, f3, f4, f5, f6) {
var p = document.getElementById("optbp");
var q = document.getElementById("optb");
if(p.style.display===""){ photo="&photo=on"; } else { photo=""; }
if(q.style.display===""){ act="&delokorupok17912457381x9djghzmtqhjrlckawutso16=okprocesssuccess67830123440"; } else{ act=""; }

document.getElementById("formd").action="index.php?operasi=on&input=save"+photo+act;
document.getElementById("fkode").value=f1;
document.getElementById("fnama").value=f2;
document.getElementById("fbentuk").value=f3;
document.getElementById("fwarna").value=f4;
document.getElementById("ftgl").value=f5;
document.getElementById("titleop").innerHTML="Input Data";
alert(f6);
document.getElementById('id01').style.display='block';
}


function ubah(f1, f2, f3, f4, f5, f6) {
var p = document.getElementById("optbp");
if(p.style.display===""){ photo="&photo=on"; } else { photo=""; }
document.getElementById("formd").action="index.php?<?=$cari?>&<?=$sohal?>&operasi=on&update=save"+photo;
document.getElementById("img").value=f6;
document.getElementById("oldid").value=f1;
document.getElementById("fkode").value=f1;
document.getElementById("fnama").value=f2;
document.getElementById("fbentuk").value=f3;
document.getElementById("fwarna").value=f4;
document.getElementById("ftgl").value=f5;
document.getElementById("pic").src=document.getElementById(f6).src;
document.getElementById("titleop").innerHTML="Perubahan Data";
document.getElementById('id01').style.display='block';
}

function revisiubah(f1, f2, f3, f4, f5, f6, f7)
{
var p = document.getElementById("optbp");
if(p.style.display===""){ photo="&photo=on"; } else { photo=""; }

document.getElementById("formd").action="index.php?<?=$cari?>&<?=$sohal?>&operasi=on&update=save"+photo;
document.getElementById("img").value=f6;
document.getElementById("oldid").value=f1;
document.getElementById("fkode").value=f1;
document.getElementById("fnama").value=f2;
document.getElementById("fbentuk").value=f3;
document.getElementById("fwarna").value=f4;
document.getElementById("ftgl").value=f5;
document.getElementById("pic").src=document.getElementById(f6).src;
document.getElementById("titleop").innerHTML="Perubahan Data";
alert(f7);
document.getElementById('id01').style.display='block';
}


function hapus(f1, f2) {
if(confirm("Data buah "+f2+" akan dihapus!")==true)
{ 
var p = document.getElementById("optbp");
if(p.style.display===""){ photo="&photo=on"; } else { photo=""; }

window.location.replace("index.php?<?=$cari?>&<?=$sohal?>&<?php if(isset($_GET['hal'])){?>hal=<?=$_GET['hal']?>&ira=<?=$_GET['ira']?>&mx=<?=$_GET['mx']?><?php }?>&del=on&id="+f1+photo);

}else { return; }
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
	for(i=1;i<=<?=$n?>; i++)
	{
	document.getElementById("photo"+[i]).style.display="";
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
	for(i=1;i<=<?=$n?>; i++)
	{
	document.getElementById("photo"+[i]).style.display="none";
	}
}
}

function showhide() {
var x = document.getElementById("optb");
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
	for(i=1;i<=<?=$n?>; i++)
	{
	document.getElementById("hp"+[i]).style.display="";
	document.getElementById("ub"+[i]).style.display="";
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
	for(i=1;i<=<?=$n?>; i++)
	{
	document.getElementById("hp"+[i]).style.display="none";
	document.getElementById("ub"+[i]).style.display="none";
	}
}
}
function prev(pv, ira, mx) {
var p = document.getElementById("optbp");
if(p.style.display===""){ photo="photo=on&"; } else { photo=""; }
window.location.replace("index.php?"+photo+"<?=$cari?>pagereplace=onpages&<?=$sohal?>&hal="+pv+"&ira="+ira+"&mx="+mx);
}
function page(pg, ira, mx) { 
window.location.replace("index.php?"+photo+"&<?=$cari?>pagereplace=onpages&<?=$sohal?>&hal="+pg+"&ira="+ira+"&mx="+mx);
}
function next(nx, ira, mx) {
window.location.replace("index.php?"+photo+"&<?=$cari?>pagereplace=onpages&<?=$sohal?>&hal="+nx+"&ira="+ira+"&mx="+mx);
}
function exitpage(){
window.location.replace("login.php?logout=on");
}
function print() {
window.open("print.php", "_blank");
}

//sortir nama buah dari tabel database
function sortir(){
var p = document.getElementById("optbp");
var q = document.getElementById("optb");
if(p.style.display===""){ photo="&photo=on"; } else { photo=""; }
if(q.style.display===""){ act="&delokorupok17912457381x9djghzmtqhjrlckawutso16=okprocesssuccess67830123440"; } else{ act=""; }
window.location.replace("<?=$sortir?>"+photo+act+"<?php if(isset($_GET['hal'])){?>&hal=<?=$_GET['hal']?>&ira=<?=$_GET['ira']?>&mx=<?=$_GET['mx']?><?php }?>");
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
    for (i = 1; i < (rows.length - 1); i++) {
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

/* pencarian data tanpa form dengan mempertahankan nilai userpage seperti foto dan tombol aksi */
function caridata(){
var data=document.getElementById("datacari").value;
if(data==="") { alert("Isilah data yang ingin cari!"); return false; }
var p = document.getElementById("optbp");
var q = document.getElementById("optb");
if(p.style.display===""){ photo="&photo=on"; } else { photo=""; }
if(q.style.display===""){ act="&delokorupok17912457381x9djghzmtqhjrlckawutso16=okprocesssuccess67830123440"; } else{ act=""; }

window.location.replace("index.php?cari="+data+photo+act);
}
function tutupcari(){
var p = document.getElementById("optbp");
var q = document.getElementById("optb");
if(p.style.display===""){ photo="&photo=on"; } else { photo=""; }
if(q.style.display===""){ act="&delokorupok17912457381x9djghzmtqhjrlckawutso16=okprocesssuccess67830123440"; } else{ act=""; }
window.location.replace("index.php?"+photo+act);
}
/* https://www.w3schools.com/howto/tryit.asp?filename=tryhow_js_trigger_button_enter referensi:https://javascript.info/ */
</script>
</body>
</html>
