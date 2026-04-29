<?php
session_start();
include "dbon.php";

if (!isset($_SESSION['#160187sesionaktif']) || !isset($_COOKIE['user'])) {
    header('location: login.php');
    exit();
}

if (isset($_GET['kasir'])) {
    $kasir = $_GET['kasir'];
    $transaksi = "open";
} else {
    $kasir = "error";
    header('location: login.php');
    exit();
}
if (isset($_GET['pembeli'])) {
    $pembeli = $_GET['pembeli'];
} else {
    $pembeli = "umum";
}
if (isset($_GET['bayar'])) {
    $bayar = $_GET['bayar'];
} else {
    $bayar = "kredit";
}


if (isset($_GET['kode'])) {
    $kode = $_GET['kode'];
} else {
    $kode = "";
}

if (isset($_GET['kuantitas'])) {
    $kuantitas = $_GET['kuantitas'];
} else {
    $kuantitas = 1;
}

if (isset($_GET['inv'])) {
    $inv = $_GET['inv'];
} else {
    $inv = "#";
}

if (!isset($_GET['row'])) {
    $rowid = "";
}

if (isset($_GET['delcodeid'])) {
    $id = $_GET['delcodeid'];
    $del = $konek->prepare("delete from penjualan where id=:idel");
    $del->execute(array(':idel' => $id));
}

if (isset($_GET['endbuying'])) {
    $inv = $_GET['inv'];
    $del = $konek->prepare("delete from penjualan where invinvoice =:inv");
    $del->execute(array(':inv' => $inv));
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
        $update = $konek->prepare("update invoice set invoice=:inv, status=:satu");
        $array = array(':inv' => $inv, ':satu' => 1);
        $update->execute($array);
    }

    $select = "select * from buah where kode='$kode'";
    $data = $konek->prepare("$select");
    $data->execute();
    $cek = $data->rowCount();
    if ($cek > 0) {
        $hasil = $data->fetch();
        $nama = $hasil['nama'];
        $harga = $hasil['harga_jual'];
        $diskon = $hasil['diskon'];
        $subharga = ($harga - ($harga * $diskon * 0.01)) * $kuantitas;

        if (isset($_GET['upcodeid'])) {
            $id = $_GET['upcodeid'];

            $rowid = "&row=" . $_GET['row'];
            //update penjualan
            $update = $konek->prepare("update penjualan set kode=:kode, nama=:nama, harga=:harga, diskon=:diskon, kuantitas=:kuan, subtharga=:subtharga where id = '$id'");
            $array = array(':kode' => $kode, ':nama' => $nama, ':harga' => $harga, ':diskon' => $diskon, ':kuan' => $kuantitas, ':subtharga' => $subharga);
            $update->execute($array);
        } else {
            //input penjualan
            $insert = $konek->prepare("insert into penjualan (kode, nama, harga, diskon, kuantitas, subtharga, invoice) values (:kode, :nama, :harga, :diskon, :kuan, :subtharga, :inv)");
            $array = array(':kode' => $kode, ':nama' => $nama, ':harga' => $harga, ':diskon' => $diskon, ':kuan' => $kuantitas, ':subtharga' => $subharga, ':inv' => $inv);
            $insert->execute($array);
        }
    }

    header("location:penjualan.php?kasir=$kasir&inv=$inv" . $rowid);
    exit();
}

//input payment
if (isset($_GET['paymenton'])) {
    $sel = "select * from payment where invoice='$inv'";
    $pay = $konek->prepare("$sel");
    $pay->execute();
    $npay = $pay->rowCount();
    if (empty($npay)) {
        $tharga = $_GET['tharga'];
        $datetime = $_GET['datetime'];
        $ket = $_GET['ket'];
        $insert = $konek->prepare("insert into payment (invoice, total_harga, bayar, tanggal, pembeli, keterangan, kasir) values (:inv, :tharga, :bayar, :tgl, :pembeli, :ket, :kasir)");
        $array = array(':inv' => $inv, ':tharga' => $tharga, ':bayar' => $bayar, ':tgl' => $datetime, ':pembeli' => $pembeli, ':ket' => $ket, ':kasir' => $kasir);
        $insert->execute($array);

        $update = $konek->prepare("update invoice set invoice=:inv, status=:satu");
        $array = array(':inv' => 0, ':satu' => 0);
        $update->execute($array);
    }
    $transaksi = "closed";
}


$select = "select * from penjualan where invoice='$inv'";
$data = $konek->prepare("$select");
$data->execute();
$n = $data->rowCount();
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
    <div class="topnav">
        <div class="sale2">
            <span class="sale3" id="dt"></span>
            <input type="hidden" name="datetime" id="gate"><br />
            <span class="sale1">Kasir</span><input type="text" style="width:150px;" value="<?= $kasir ?>" readonly><br />
            <span class="sale1">Pembeli</span><input type="text" id="buyer" style="width:150px; margin-top:2px;" value="Umum">
        </div>

        <div class="sale2">
	<span class="sale1">Kode Produk</span><input type="text" name="kode" id="code" style="width:200px;" value="<?= $kode ?>"><br />
            <span class="sale1">Qty</span><input type="text" name="kuantitas" id="kuan" style="width:200px;" value="<?= $kuantitas ?>"><br />
            <span class="sale1"></span><button id="addbuy" style="width:63px; margin-top:2px;" onclick="pushdata()"><span id="go">Push</span></button>
            <button style="width:63px; margin-top:2px; display:none;" onclick="cancel(this.id)" class="cancelbtn" id="cancel">Cancel</button>
        </div>

        <div class="sale4">
            <span class="sale1">Invoice: <?=$inv?></span> <br />
            <span class="sale1 bigfon" id="big"></span>
            <br />
<input type="text" id="inputcash" class="bigfon" style="display: none; width:200px;">
<br />
            <button id="paymenton" style="width:63px;" onclick="paymenton()"><span id="pay">BAYAR</span></button><button style="width:63px; margin-top:2px; display:none; float:right;" onclick="cancel(this.id)" class="cancelbtn" id="cancelpay">Cancel</button>
<br />
<span id="radio">
<input type="radio" name="bayar" id="cash" checked>Cash&nbsp;
<input type="radio" name="bayar" id="credit">Credit&nbsp;
<input type="radio" name="bayar" id="tf">Transfer 
</span>
        </div>
    </div>

    <br />

    <div class="topnav">
        <table border="1" class="table" cellpadding="3" width="100%">
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Barang</th>
                <th>Harga</th>
                <th>Diskon</th>
                <th>Qty</th>
                <th>Sub-total Harga</th>
                <th>Act</th>

            </tr>
            <?php
            $tharga = 0;
            $i = 1;
            while ($hasil = $data->fetch()) {
                if ($i <= $n) {
            ?>
                    <tr id="row<?= $i ?>">
                        <td><?= $i ?></td>
                        <td><?= $hasil['kode'] ?></td>
                        <td><?= $hasil['nama'] ?></td>
                        <td><?php $harga = $hasil['harga']; ?>
                            <span id="harga<?=$i?>"><?=$harga?></span></td>
                        <td><?php echo $hasil['diskon'] . "&nbsp;%"; ?></td>
                        <td><?= $hasil['kuantitas'] ?></td>
                        <td>
			<?php $subharga=$hasil['subtharga']; ?>
			<span id="subtotalrow<?=$i?>"><?=$subharga?></span>
			</td>
                        <td><input type="button" id="up<?= $i ?>" value="Up" onclick="edit('<?= $hasil['id'] ?>','<?= $hasil['kode'] ?>','<?= $hasil['kuantitas'] ?>','<?= $i ?>')"><input type="button" value="Del" onclick="delelete('<?= $hasil['id'] ?>', '<?= $i ?>')"></td>
                    </tr>
            <?php }
                $tharga = $tharga + $subharga;
                $i++;
            } ?>
            <tr>
                <td colspan="6"><b>Total Harga (Rp)</b></td>
                <td><b><span id="tharga"><?=$tharga?></span></b></td>
            </tr>
        </table>
    </div>

</body>

</html>
<script>
    document.getElementById("code").focus();
    <?php
    if ($tharga == 0) { ?> document.getElementById("paymenton").style.display = "none";
    <?php }

    if ($transaksi == "closed") { ?>
        document.getElementById("addbuy").disabled = true;
        document.getElementById("addbuy").style.background = "#c2c2c2";
        document.getElementById("addbuy").style["pointer-events"] = "none";
        document.getElementById("pay").innerHTML = "DONE";
        document.getElementById("paymenton").focus();
    <?php }

    if (isset($_GET['row'])) { ?>
        document.getElementById("<?= $_GET['row'] ?>").style.background = "rgba(255,200,0,0.2)";

        window.onclick = function() {
            document.getElementById("<?=$_GET['row']?>").style.background = null;
        }
    <?php } ?>

//format harga, subtotal dan total harga barang
for(i=1;i<=<?=$n?>; i++)
{
let harga = document.getElementById("harga"+i).innerHTML;
formatharga(harga, "harga");
}

for(i=1;i<=<?=$n?>; i++)
{
let subtotal = document.getElementById("subtotalrow"+i).innerHTML;
formatharga(subtotal, "subtotalrow");
}

let tharga = document.getElementById("tharga").innerHTML;
var i = "";
formatharga(tharga, "tharga");
formatharga(tharga, "big");

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
}
}

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
        clockElement.textContent = dateTimeString;
        document.getElementById("gate").value = dateTimeString;
    }

    // Update the date and time every second (1000 milliseconds).
    setInterval(updateDateTime, 1000);

    // Initial update.
    updateDateTime();



    function paymenton() {
        let paym = document.getElementById("pay").innerHTML;
	let cash = document.getElementById("cash");
	let credit = document.getElementById("credit");
	let tf = document.getElementById("tf");
	let incash = document.getElementById("inputcash");
	if(cash.checked){ 
	if(incash.style.display == "none"){
	incash.style.display = "";
	document.getElementById("cancelpay").style.display ="";	
	incash.focus();
	return;
	}else{ incash = incash.value; exec(incash, "lunas"); }
	}
	else if(credit.cheked){
	exec(0, "kredit");
	return;
	}
	else if(tf.cheked){
	document.getElementById("rekening").style.display="";
	return;
	}

        if (paym == "DONE") {
            window.location.replace("penjualan.php?kasir=<?= $kasir ?>");
        }
    }

    function exec(money, ket){
	 var beli = document.getElementById("buyer").value;
            window.location.replace("penjualan.php?kasir=<?= $kasir ?>&inv=<?= $inv ?>&bayar=cash&pembeli=" + beli + "&datetime=0&tharga=<?= $tharga ?>&money="+money+"&ket="+ket+"&paymenton=execute");
	}

    function pushdata() {
        var cod = document.getElementById("code").value;
        var qty = document.getElementById("kuan").value;
        if (cod == "") {
            document.getElementById("code").focus();
            return;
        }

        if (document.getElementById("go").innerHTML == "Save") {
            var id = document.getElementById("addbuy").value;
            var row = document.getElementById("cancel").value;
            var extra = "&upcodeid=" + id + "&row=" + row;

        } else {
            var extra = "";
        }

        window.location.replace("penjualan.php?kasir=<?= $kasir ?>&kode=" + cod + "&kuantitas=" + qty + "&push=on" + extra);
    }

    function delelete(id, i) {
        if (confirm("Data nomor: " + i + " akan dihapus!") == true) {
            window.location.replace(window.location.href + "&delcodeid=" + id);
        } else {
            return;
        }
    }

    function edit(id, kode, q, i) {
        document.getElementById("code").value = kode;
        document.getElementById("kuan").value = q;
        document.getElementById("go").innerHTML = "Save";
        document.getElementById("addbuy").value = id;
        document.getElementById("cancel").style.display = "";
        document.getElementById("cancel").value = "row" + i;
        document.getElementById("row" + i).style.background = "rgba(255,200,0,0.2)";
        document.getElementById("kuan").focus();
    }

    function cancel(id) { 
	if(id == "cancelpay")
	{
	 if (confirm("Seluruh data pembelian barang akan dihapus!") == true) {
            window.location.replace(window.location.href + "&endbuying=<?=$inv?>");
        } else { return; }
	
	}
	else{ 

	let incash = document.getElementById("inputcash");

        var row = document.getElementById("cancel").value;
        document.getElementById("code").value = "";
        document.getElementById("code").focus();
        document.getElementById("kuan").value = 1;
        document.getElementById("go").innerHTML = "Push";
        document.getElementById("cancel").style.display = "none";
        document.getElementById(row).style.background = null;
    }
}
</script>