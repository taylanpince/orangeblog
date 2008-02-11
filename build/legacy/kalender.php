<?php

$path = "./";

include("db/db.mysql.php");

$title = "kalender";

include("left.inc.php");

if ($nedirtarih == "")
{
	$nedirtarih = time();
}

$nedirgun = date("j", $nedirtarih);
$nediray = date("n", $nedirtarih);
$nediryil = date("Y", $nedirtarih);
$nediryaz = date("d.m.Y", $nedirtarih);
$nedirgunisim = guncevir($nedirtarih);

echo "<h2>kalender :: $nediryaz, $nedirgunisim</h2>";

$baslatarih = mktime(0, 0, 0, $nediray, $nedirgun, $nediryil);
$bitirtarih = mktime(23, 59, 59, $nediray, $nedirgun, $nediryil);

$sql = "select * from ob_blog where (tarih >= '$baslatarih' and tarih <= '$bitirtarih') order by tarih desc";
$query = new DB_query($db, $sql);

while ($query->db_fetch_object())
{
	$id = $query->obj->id;
	$yazarid = $query->obj->yazarid;
	$tarih = $query->obj->tarih;
	$baslik = $query->obj->baslik;
	$icerik = $query->obj->icerik;
	$daha = $query->obj->daha;
	$kategori = $query->obj->kategori;
	$yorumsayisi = $query->obj->toplamyorum;
	
	$icerik = decode($icerik);
	$daha = decode($daha);
	
	$tarih = date("d.m.Y | G:i", $tarih);
	
	$s = "select isim from ob_uyeler where id = '$yazarid'";
	$q = new DB_query($db, $s);
	$q->db_fetch_object();
	
	$yazarisim = $q->obj->isim;
	
	unset($s);
	unset($q);
	
	$s = "select isim from ob_kategori where id = '$kategori'";
	$q = new DB_query($db, $s);
	$q->db_fetch_object();
	
	$kategori = $q->obj->isim;
	
	unset($s);
	unset($q);
	
	if (isset($HTTP_COOKIE_VARS['member_code']) && $loginyazarstatu >= "2")
	{
		echo "<div class=\"weblog\" onmouseover=\"document.getElementById('$id').style.visibility='visible'\" onmouseout=\"document.getElementById('$id').style.visibility='hidden'\">";
	}
	else
	{
		echo "<div class=\"weblog\">";
	}
	
	echo "<h3>$baslik</h3>";
	echo "<p>$icerik";
	
	if ($daha <> "")
	{
		echo "<br><br><a href=\"#\" onClick=\"return pencere('".$path."daha.php?id=$id','bkz',500,350,50,50)\">daha daha...</a>";
	}
	
	echo "</p>";
	
	echo "<div class=\"author\">$yazarisim | $tarih | $kategori";
	
	if ($yorumsayisi > 0)
	{
		echo " | <a href=\"".$path."yorumlar.php?id=$id#yorumlar\">yorumlari oku</a> [$yorumsayisi]";
	}
	elseif (isset($HTTP_COOKIE_VARS['member_code']) && $loginyazarstatu >= "2")
	{
		echo " | <a href=\"yorumlar.php?id=$id#yorumyaz\">yorum yaziver</a>";
	}
	
	echo "</div>";

	if (isset($HTTP_COOKIE_VARS['member_code']) && $loginyazarstatu >= "2")
	{
		echo "<div id=\"$id\" style=\"visibility:hidden\">";
		menuyap("baslat");
		menuyap("menu", "o kim?", "#", "return pencere('".$path."yazarhakkinda.php?id=$yazarid','info',375,400,50,50)");
		menuyap("menu", "mesaj", "#", "return pencere('".$path."mesajgonder.php?kime=$yazarid&baslik=$baslik','mesaj',400,510,50,50)");
		
		if ($loginyazarid == $yazarid || $loginyazarstatu == "9")
		{
			menuyap("menu", "degistir", "degistir.php?id=$id");
		}
		
		if ($loginyazarstatu == "9")
		{
			menuyap("menu", "sil", "#", "if(confirm('emin misin? yorumlar falan gidecek toptan.')){window.location='sil.php?id=$id';}");
		}
		elseif ($loginyazarid == $yazarid)
		{
			menuyap("menu", "sil", "#", "return pencere('".$path."mesajgonder.php?kime=$yazarid&include=$baslik&sil=1','mesaj',400,510,50,50)");
		}
		
		if ($loginyazarid <> $yazarid)
		{
			menuyap("menu", "pek hos", "#", "return pencere('".$path."oyoy.php?id=$id&vote=1','oyoy',250,150,50,50)");
			menuyap("menu", "igrencsaan", "#", "return pencere('".$path."oyoy.php?id=$id&vote=0','oyoy',250,150,50,50)");
		}
		
		menuyap("bitir");
		echo "</div>";
	}

	echo "</div>";
}

include("right.inc.php");

?>