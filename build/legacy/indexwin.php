<?php

$path = "./";

include("db/db.mysql.php");

$title = "bahar temizligi";

include("left.inc.php");

echo "<div align=\"center\"><img src=\"".$path."tema/".$tema."_title.jpg\" width=\"400\" border=\"0\"></div>";

if ($basla == "")
{
	$basla = "0";
}

$limit = "8";

$sql = "select id from ob_blog";
$query = new DB_query($db, $sql);

$total = $query->db_num_rows();
$son = $total - $limit;
$sonraki = $basla + $limit;
$topsayfa = ceil($total/$limit);
$cursayfa = ceil($sonraki/$limit);

if ($cursayfa == "2")
{
	$onceki = "0";
}
else
{
	$onceki = $basla - $limit;
}

$gezbar = "<table width=\"100%\" align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td align=\"right\">";

if ($onceki >= 0)
{
	$gezbar .= "<a href=\"index.php?basla=0\">ilk sayfa</a> | ";
	$gezbar .= "<a href=\"index.php?basla=$onceki\">onceki sayfa</a> | ";
}

if ($sonraki < $total)
{
	$gezbar .= "<a href=\"index.php?basla=$sonraki\">sonraki sayfa</a> | ";
	$gezbar .= "<a href=\"index.php?basla=$son\">son sayfa</a> | ";
}

$gezbar .= "sayfa $cursayfa / $topsayfa";
$gezbar .= "</td></tr></table>";

echo $gezbar;

$sql = "select * from ob_blog order by tarih desc limit $basla,$limit";
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
	
	$icerik = decode($icerik);
	$daha = decode($daha);
	
	$eskigun = $gun;
	$gun = date("d.m.Y", $tarih);
	$tarih = date("d.m.Y | G:i", $tarih);
	
	if ($gun != $eskigun)
	{
		echo "<h2>$gun</h2>";
	}
	
	$s = "select isim from ob_uyeler where id = '$yazarid'";
	$q = new DB_query($db, $s);
	$q->db_fetch_object();
	
	$yazarisim = $q->obj->isim;
	
	unset($s);
	unset($q);
	
	$s = "select id from ob_yorumlar where blogid = '$id'";
	$q = new DB_query($db, $s);
	$yorumsayisi = $q->db_num_rows();
	
	unset($s);
	unset($q);
	
	$s = "select isim from ob_kategori where id = '$kategori'";
	$q = new DB_query($db, $s);
	$q->db_fetch_object();
	
	$kategori = $q->obj->isim;
	
	unset($s);
	unset($q);
	
	echo "<div class=\"weblog\" onmouseover=\"document.getElementById('$id').style.visibility='visible'\" onmouseout=\"document.getElementById('$id').style.visibility='hidden'\">";
	echo "<h3>$baslik</h3>";
	echo "<p>$icerik";
	
	if ($daha <> "")
	{
		echo "<br><br><a href=\"#\" onClick=\"return pencere('".$path."daha.php?id=877_0_1_0_M','bkz',500,350,50,50)\">daha daha...</a>";
	}
	
	echo "</p>";
	
	echo "<div class=\"author\">$yazarisim | $tarih | $kategori";
	
	if ($yorumsayisi > 0)
	{
		echo " | <a href=\"".$path."yorumlar.php?id=$id\">yorumlari oku</a> [$yorumsayisi]";
	}
	else
	{
		echo " | <a href=\"#\" onClick=\"return pencere('".$path."yorum.php?id=$id','yorum',350,325,50,50)\">yorum yaziver</a>";
	}
	
	echo "</div>";

	if ($loginyazarid == "")
	{
		echo "<nobr><div id=\"$id\" style=\"visibility:hidden\">";
		echo "<div class=\"kumanda\" style=\"width:45px\"><a href=\"#\" class=\"kumanda\" onClick=\"return pencere('".$path."yazarhakkinda.php?id=$yazarid','info',350,400,50,50)\">o kim?</a></div>";
		echo "<div class=\"kumanda\" style=\"width:40px\"><a href=\"#\" class=\"kumanda\" onClick=\"return pencere('".$path."mesajgonder.php?id=$id&include=$baslik','mesaj',400,360,50,50)\">mesaj</a></div>";
		
		if ($loginyazarid == $yazarid || $loginyazarstatu == "9")
		{
			echo "<div class=\"kumanda\" style=\"width:50px\"><a href=\"#\" class=\"kumanda\" onClick=\"return pencere('".$path."degistir.php?id=$id','yeni',725,550,50,50)\">degistir</a></div>";
			echo "<div class=\"kumanda\" style=\"width:25px\"><a href=\"#\" class=\"kumanda\" onClick=\"return pencere('".$path."sil.php?id=$id','sil',250,200,50,50)\">sil</a></div>";
		}
	
		echo "<div class=\"kumanda\" style=\"width:55px\"><a href=\"#\" class=\"kumanda\" onClick=\"return pencere('".$path."esdost.php?id=$id','esdost',375,400,50,50)\">haber et</a></div>";
		
		if ($loginyazarid <> $yazarid)
		{
			echo "<div class=\"kumanda\" style=\"width:50px\"><a href=\"#\" class=\"kumanda\" onClick=\"return pencere('".$path."oyoy.php?postid=$id&vote=1&mid=$loginyazarkod','oyoy',250,150,50,50)\">pek hos</a></div><div class=\"kumanda\" style=\"width:50px\"><a href=\"#\" class=\"kumanda\" onClick=\"return pencere('".$path."oyoy.php?postid=$id&vote=0&mid=$loginyazarkod','oyoy',250,150,50,50)\">igrencsaan</a></div>";
		}
		
		echo "</div></nobr>";
	}

	echo "</div>";
}

echo $gezbar;

include("right.inc.php");
?>

