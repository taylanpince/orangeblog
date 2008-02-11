<?php

if ($title == "")
{
	$title = "anakin türküsü";
}

?>

<html>
<head>
<title>orangeblog :: <?php echo $title; ?></title>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8" />
<link rel="icon" href="<?php echo $path; ?>images/orange_icon.gif">
<script language="JavaScript" src="<?php echo $path; ?>kodaman.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $path."tema/".$tema; ?>.css">

<?php

if ($google == 1)
{
	echo "<script src=\"http://maps.google.com/maps?file=api&v=1&key=ABQIAAAAqNlir7gPYDjrsohIhgrUHxR81bL8Q8vpKByVw4_d_WJrYHxWUBT60HJxfRAQkbnyWvTdXFBn2kWdWQ\" type=\"text/javascript\"></script>";
}

?>

</head>

<?php

if ($mesajSayfa == "1")
{
	echo "<body onLoad=\"javascript:setUp()\">";
}
else
{
	echo "<body>";
}

?>

<a name="top"></a>

<div id="menu">
<div class="menuitems">
<a href="index.php"><img src="<?php echo $path."tema/".$tema; ?>.jpg" border="0"></a>
</div>

<?php

$sql = "select id,yazarid,zaman,olay from ob_bulusma where zaman >= '$simdi' or zaman = '0' order by zaman desc";
$query = new DB_query($db, $sql);
$bulustoplam = $query->db_num_rows();

unset($sql);
unset($query);

if ($bulustoplam > 0)
{
	echo "<div class=\"menuitems\">";
	echo "<h4>yaklasan bulusmalar</h4><br>";
	
	$sql = "select id,yazarid,zaman,olay from ob_bulusma where zaman >= '$simdi' order by zaman asc";
	$query = new DB_query($db, $sql);
	
	while ($query->db_fetch_object())
	{
		$menuid = $query->obj->id;
		$menuyazarid = $query->obj->yazarid;
		$menuzaman = $query->obj->zaman;
		$menuolay = $query->obj->olay;
		
		$menugun = guncevir($menuzaman);
		$menuzaman = date("d.m.Y", $menuzaman);
		$menuzaman = $menuzaman.", ".$menugun;
		
		$s = "select isim from ob_uyeler where id = '$yazarid'";
		$q = new DB_query($db, $s);
		$q->db_fetch_object();
		
		$menuyazarisim = $q->obj->isim;
		
		unset($s);
		unset($q);
		
		$s = "select id from ob_bulusyorum where tarih >= '$loginyazarsongiris' and bulusid = '$menuid'";
		$q = new DB_query($db, $s);
		$menutoplambulusyorum = $q->db_num_rows();
		
		unset($s);
		unset($q);
		
		if ($menutoplambulusyorum > 0 && $loginyazarid <> "")
		{
			echo "<a href=\"bulusyorum.php?id=$menuid\"><b>$menuolay</b></a><br>($menuzaman)<br><br>";
		}
		else
		{
			echo "<a href=\"bulusyorum.php?id=$menuid\">$menuolay</a><br>($menuzaman)<br><br>";
		}
	}
	
	unset($sql);
	unset($query);
	
	$sql = "select id,yazarid,zaman,olay from ob_bulusma where zaman = '0'";
	$query = new DB_query($db, $sql);
	
	while ($query->db_fetch_object())
	{
		$menuid = $query->obj->id;
		$menuyazarid = $query->obj->yazarid;
		$menuolay = $query->obj->olay;
		$menuzaman = "belirsiz";
		
		$s = "select isim from ob_uyeler where id = '$yazarid'";
		$q = new DB_query($db, $s);
		$q->db_fetch_object();
		
		$menuyazarisim = $q->obj->isim;
		
		unset($s);
		unset($q);
		
		$s = "select id from ob_bulusyorum where tarih >= '$loginyazarsongiris' and bulusid = '$menuid'";
		$q = new DB_query($db, $s);
		$menutoplambulusyorum = $q->db_num_rows();
		
		unset($s);
		unset($q);
		
		if ($menutoplambulusyorum > 0 && $loginyazarid <> "")
		{
			echo "<a href=\"bulusyorum.php?id=$menuid\"><b>$menuolay</b></a><br>($menuzaman)<br><br>";
		}
		else
		{
			echo "<a href=\"bulusyorum.php?id=$menuid\">$menuolay</a><br>($menuzaman)<br><br>";
		}
	}
	
	unset($sql);
	unset($query);

	echo "</div>";
}

?>

<div class="menuitems">
<h4>gunun menusu</h4><br>
<!-- gunun menusu -->

<?php

$saat = date("G", $simdi);
$dakika = date("i", $simdi);
$gecenzaman = ($saat * 60 * 60) + ($dakika * 60);
$bugun = $simdi - $gecenzaman;

$sql = "select id,yazarid,tarih,baslik from ob_blog where tarih >= '$bugun' order by tarih desc";
$query = new DB_query($db, $sql);
$total = $query->db_num_rows();

if ($total == 0)
{
	echo "daha yazmamis ki<br>kimse... bühühühü";
}
else
{
	while ($query->db_fetch_object())
	{
		$menublogid = $query->obj->id;
		$menubaslik = $query->obj->baslik;
		
		echo "<a href=\"".$path."yorumlar.php?id=$menublogid\">$menubaslik</a><br><br>";
	}
}

unset($sql);
unset($query);

?>

</div>

<div class="menuitems">
<h4>erman amca</h4><br>
<!-- son yorumlar -->

<?php

$sql = "select id,yazarid,blogid,tarih from ob_yorumlar order by tarih desc limit 10";
$query = new DB_query($db, $sql);

while ($query->db_fetch_object())
{
	$menuyorumid = $query->obj->id;
	$menublogid	= $query->obj->blogid;
	$menuyazarid = $query->obj->yazarid;
	$menuyorumtarih = $query->obj->tarih;
	
	$q = new DB_query($db, "select baslik from ob_blog where id = '$menublogid'");
	$q->db_fetch_object();
	
	$menubaslik = $q->obj->baslik;
	
	unset($q);
			
	$q = new DB_query($db, "select isim from ob_uyeler where id = '$menuyazarid'");
	$q->db_fetch_object();
	
	$menuyazarisim = $q->obj->isim;
	
	unset($q);
	
	if ($menuyorumtarih > $loginyazarsongiris && $loginyazarid <> "")
	{
		echo "<a href=\"".$path."yorumlar.php?id=$menublogid#$menuyorumid\"><b>$menuyazarisim :: $menubaslik</b></a><br><br>";
	}
	else
	{
		echo "<a href=\"".$path."yorumlar.php?id=$menublogid#$menuyorumid\">$menuyazarisim :: $menubaslik</a><br><br>";
	}
}

unset($sql);
unset($query);

?>

</div>

<div class="menuitems">
<h4>haftanin dedikodulari</h4><br>
<!-- son yorumlar -->

<?php

$sql = "select * from ob_dedikodu order by id desc limit 3";
$query = new DB_query($db, $sql);

while ($query->db_fetch_object())
{
	$menubaslik = $query->obj->baslik;
	$menuicerik = $query->obj->icerik;
	$menuyazarid = $query->obj->yazarid;
	
	$s = "select rumuz from ob_uyeler where id = '$menuyazarid'";
	$q = new DB_query($db, $s);
	$q->db_fetch_object();
	
	$menurumuz = $q->obj->rumuz;
	
	unset($s);
	unset($q);
	
	$menuicerik = decode($menuicerik);
	
	echo "<h4>$menubaslik</h4>$menuicerik<br><br><div align=\"right\">$menurumuz</div><br>";
}

unset($sql);
unset($query);

echo "<div align=\"right\">";
menuyap("tekbuton", "dedikodu arsivi", $path."dedikodu.php");
echo "</div>";

?>

</div>

<div class="menuitems">
<h4>kategoriler</h4><br>
<!-- kategoriler -->

<?php

$sql = "select * from ob_kategori order by isim asc";
$query = new DB_query($db, $sql);

while ($query->db_fetch_object())
{
	$menukateid = $query->obj->id;
	$menukateisim = $query->obj->isim;

	echo "<a href=\"kategori.php?id=$menukateid\">$menukateisim</a><br>";
}

unset($sql);
unset($query);

?>

</div>

</div>

<div id="content">
<br />

<div align="center">
<!-- uye fasiliteleri -->

<?php

if ($loginyazarid <> "")
{
	$sql = "select sinsi,dedikodu,incik from ob_uyeler where id = '$loginyazarid'";
	$query = new DB_query($db, $sql);
	$query->db_fetch_object();
	
	$sinsi = $query->obj->sinsi;
	$dedikodu = $query->obj->dedikodu;
	$incik = $query->obj->incik;
	
	unset($sql);
	unset($query);
	
	$sql = "select id from ob_mesajlar where kime = '$loginyazarid' and durum = 'u'";
	$query = new DB_query($db, $sql);
	$totmesaj = $query->db_num_rows();
	
	unset($sql);
	unset($query);
	
	if ($totmesaj == 0)
	{
		$totmesaj = "";
		$mesaj = "kumanda";
	}
	else
	{
		$totmesaj = " ($totmesaj)";
		$mesaj = "online";
	}
	
	if ($sinsi == 0)
	{
		$sinsiback = "online";
	}
	else
	{
		$sinsiback = "kumanda";
	}

	menuyap("baslat");
	menuyap("menu", " ".$loginyazarisim." ", "#", "return pencere('".$path."yazarhakkinda.php?id=$loginyazarid','info',350,400,50,50)");
	menuyap("menu", "cikacam", $path."giris.php?nedir=cik");
	menuyap("menu", "ince ayar", $path."kayit.php?nedir=degistirform");
	menuyap("menu", "kim kim?", "#", "return pencere('".$path."uyeler.php','info',375,400,50,50)");
	menuyap("menu", "en bi enler", $path."istatistik.php");
	echo "<div id=\"mesajkutusu\">";
	menuyap("menu", "mesaj$totmesaj", $path."mesajlar.php", "", $mesaj);
	echo "</div>";
	menuyap("satirbitir");
	menuyap("satirbaslat");
	
	if ($loginyazarstatu == "9")
	{
		menuyap("menu", "admin", $path."admin.php");
	}
	else
	{
		menuyap("bos");
	}
	
	if ($dedikodu == "1")
	{
		menuyap("menu", "dedikoducu", $path."dedikoducu.php");
	}
	else
	{
		menuyap("bos");
	}
	
	if ($incik == "1")
	{
		menuyap("menu", "incik cincik", $path."incikyaz.php");
	}
	else
	{
		menuyap("bos");
	}

	menuyap("menu", "bulusma", $path."bulusma.php");
	menuyap("menu", "zirtapoz", $path."zirtapoz.php");
	menuyap("menu", "sinsi", "#", "return pencere('".$path."sinsi.php?nedir=normal','sinsi',375,400,50,50)", $sinsiback);
	menuyap("bitir");
}
else
{
	menuyap("baslat");
	menuyap("bosbuton", "ziyaretci");
	menuyap("menu", "girecem", $path."giris.php");
	menuyap("menu", "ben de!", $path."kayit.php");
	menuyap("bitir");
}

?>

</div>
<br>