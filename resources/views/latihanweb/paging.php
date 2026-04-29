<?php
//mx adalah titik akhir tombol hal (banyak tombol per hal)
//ira adalah titik awal dari tombiol per hal
//hal adalah halaman/tombol yang dipilih


$tampil=4; //berapa tombol yg ingin ditampil


//jika ada halaman yang diakses
if(isset($_GET['ira']) and isset($_GET['mx']))
{ 
	$ira=$_GET['ira']; $mx=$_GET['mx'];
	
}
//jika tidak ada halaman yang dipilih
else{ $ira=1; $mx=$tampil; } 

if(isset($_GET['openmasuk'])) { $ira=1; $mx=$tampil; } //set default jika bukan tabel masuk

//ketika memilih tombol terakhir grup (naikkan grup)
if($hal == $mx && $hal < $total_pages){ $ira=$mx-1; $mx=$mx+$tampil-2; }

//ketika memilih tombol awal tiap grup (turunkan grup)
if($hal == $ira && $hal > 1){ $mx=$ira+1; $ira=$ira-$tampil+2; }

//jika tombol yang ditentukan ($tampil) lebih besar dari total tombol
if($mx > $total_pages){ $ira=($total_pages-$tampil)+1; $mx=$total_pages; }

//jika tombol yang ditentukan ($tampil) lebih kecil dari total tombol
if($ira < 1){ $ira=1; $mx=$tampil; }

//jika tampil lebih besar dari total pages
if($tampil > $total_pages) { $mx=$total_pages; }

/* bangun jumlah hiperlink halaman*/
if($nt>$max_results)
{
echo "<center>";

/* bangun Previous link */
if($hal > 1)
{
$prev = ($hal - 1); ?>
<span onclick="paging('<?=$prev?>','<?=$ira?>','<?=$mx?>')"><i class="hidearr"></i><i class="hidearrg"></i></span>
<?php }
else { ?>
<span><i class="hidearr offpointer"></i><i class="hidearrg offpointer"></i></span>
<?php }

for($ir = $ira; $ir <=$mx; $ir++)
{
if($hal<=$total_pages)
{
if(($hal) == $ir)
{ ?>
<input type="button" value="<?=$ir?>" style="background:transparent; color: #a2a2a2; width:25px; border-color:#04AA6D;" disabled id="page<?=$hal?>">
<?php }
else
{ ?>
<input type="button" value="<?=$ir?>" class="button2" onclick="paging('<?=$ir?>','<?=$ira?>','<?=$mx?>')" style="width:25px;">
<?php }
}
}
/* bangun Next link */
if($hal < $total_pages)
{
$next = ($hal + 1); ?>
<span onclick="paging('<?=$next?>','<?=$ira?>','<?=$mx?>')"><i class="arrowgreen"></i><i class="arrow"></i></span>
<?php }
else { ?>
<span onclick="paging('<?=$next?>','<?=$ira?>','<?=$mx?>')"><i class="arrowgreen offpointer"></i><i class="arrow offpointer"></i></span>
<?php }

echo "</center>";
}

$haljs="hal=$hal&ira=$ira&mx=$mx"; //nilai halaman utk java

?>