<?php
include "dbkon.php"; ?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Cetak Data</title>
<link rel="shortcut icon" href="faviconp.ico" type="image/x-icon">
</head>
<style type="text/css">
        @import url('stylecrudbuah.css');
</style>
<body>
<p align="center">
<?php if(isset($_GET['detail'])){ ?>
<b>Kode Barang : <?=$_GET['kode']?><br />Nama Barang : <?=$_GET['nama']?></b><?php } else { ?>Daftar Barang <?php  } ?>
</p>
<table align="center" cellpadding="3" border="1" width="75%" style="border-collapse:collapse;">
<tr>
<th width="4%" style="height:30px">No</th><th ><?php if(isset($_GET['detail']))
{ ?>Tanggal Masuk<?php } else{ ?>Kode</th><th >Nama Barang
</th><th >Tanggal Daftar</th><th >Stok</th><?php } ?><th >SN</th><th  colspan="2" ><?php if(isset($_GET['detail']))
{ ?>Kuantitas<?php } else{ ?>Update Stok<?php } ?></th>
</tr>
<?php
if($p=="all"){$data=$dt; }
$i=1;
while($hasil=$data->fetch())
{
?>
<tr class="trblaster">
<td align="center"><?=$i?></td><td><?php if(isset($_GET['detail']))
{ ?><?=date_format(date_create($hasil['update_stok']),'d/m/Y')?><?php } else{ ?><?=$hasil['kode']?></td><td><?=ucwords($hasil['nama'])?></td><td><?=date_format(date_create($hasil['tgl_daftar']),'d/m/Y')?></td><td><?=$hasil['stok']?></td><?php } ?><td><?=$hasil['sn']?></td><td><?=$hasil['instok']?></td><?php if(!isset($_GET['detail']))
{ ?><td><?=date_format(date_create($hasil['update_stok']),'d/m/Y')?></td><?php } ?>
</tr>
<?php $i++; } ?>
</table>
