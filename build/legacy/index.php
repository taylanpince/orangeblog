<?php

$path = "./";

include("db/db.mysql.php");

$title = "anakin türküsü";

include("left.inc.php");

echo "<div align=\"center\"><img src=\"".$path."tema/".$tema."_title.jpg\" width=\"400\" border=\"0\"></div>";

if ($loginyazarstatu == "1")
{
	echo "<div class=\"weblog\">";
	echo "$loginyazarisim, orangeblog'a hosgeldin. yeni uye oldugun icin henuz entri ve yorum yazma yetkin yok, eger hemen entri yazmaya baslamak istiyosan asagidaki butona tiklayarak kendini tanitan ilk entrini bloga girebilirsin. oyle abartmana gerek yok, sadece buraya katilan herkesi tanimak istiyoruz, orangeblog birbirini taniyan insanlarin eglenmek icin dusuncelerini paylastiklari bir olusum. entrin onaylandiktan sonra tum entri ve yorum girme yetkilerine sahip olaacaksin. yeniden hosgeldin.<br><br>";
	echo "<div align=\"center\">";
	menuyap("tekbuton", "yazacam bre! kendimi tanitacam.", "getur.php?baslik=$loginyazarisim");
	echo "</div></div><br>";
}

if ($basla == "")
{
	$basla = "0";
}

$limit = "10";

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

$gezbar .= "sayfa $cursayfa / $topsayfa ($total)";
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
	$yorumsayisi = $query->obj->toplamyorum;
	$filmbu = $query->obj->filmbu;
	$yildiz = $query->obj->yildiz;
	$filmoy = $query->obj->filmoy;
	
	$icerik = decode($icerik);
	$daha = decode($daha);
	
	$eskigun = $gun;
	$gun = date("d.m.Y", $tarih);
	$gunisim = guncevir($tarih);
	$tarih = date("d.m.Y | G:i", $tarih);
	
	if ($gun != $eskigun)
	{
		echo "<h2>$gun, $gunisim</h2>";
	}
	
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
	
	if ($filmbu == "1")
	{
		$yildiz = round(($yildiz / $filmoy), 2);
		$yildizgenislik = floor($yildiz * 25);
		$yildizListe = array("öeh", "sicirik", "ehhh...", "orta karar", "iyiydi be", "yalanasi");
		
		echo "<div style=\"float: left; display: inline; background: url('./tema/".$tema."_star.jpg') top left; width: ".$yildizgenislik."px; height: 25px; margin: 0px 0px 5px 0px;\">&nbsp;</div>";
		echo "<div style=\"float: left; display: inline; width: 250px; height: 25px; text-align: left; margin: 0px 0px 0px 10px; padding: 5px 0px 0px 0px;\">";
		echo "$yildiz yildiz (".$yildizListe[round($yildiz)].") | $filmoy oy";
		echo "</div>";
	}
	
	echo "<div class=\"author\" style=\"clear: both;\">$yazarisim | $tarih | $kategori";
	
	if ($yorumsayisi > 0)
	{
		echo " | <a href=\"".$path."yorumlar.php?id=$id#yorumlar\">yorumlari oku</a> [$yorumsayisi]";
	}
	elseif (isset($HTTP_COOKIE_VARS['member_code']) && $loginyazarstatu >= "2")
	{
		echo " | <a href=\"yorumlar.php?id=$id\">yorum yaziver</a>";
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
		
		if ($filmbu == "1")
		{
			menuyap("menu", "imdb", "#", "window.open('http://us.imdb.com/find?q=$baslik')");
		}
		
		menuyap("bitir");
		echo "</div>";
	}

	echo "</div>";
}

echo $gezbar;

include("right.inc.php");

?>