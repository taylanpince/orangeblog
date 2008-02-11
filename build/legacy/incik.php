<?php

$path = "./";

include("db/db.mysql.php");

$title = "incik cincik arsivi";

include("left.inc.php");

echo "<h2>incik cincik arsivi</h2>";

if ($basla == "")
{
	$basla = "0";
}

$limit = "10";

$sql = "select id from ob_incik";
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
	$gezbar .= "<a href=\"incik.php?basla=0\">ilk sayfa</a> | ";
	$gezbar .= "<a href=\"incik.php?basla=$onceki\">onceki sayfa</a> | ";
}

if ($sonraki < $total)
{
	$gezbar .= "<a href=\"incik.php?basla=$sonraki\">sonraki sayfa</a> | ";
	$gezbar .= "<a href=\"incik.php?basla=$son\">son sayfa</a> | ";
}

$gezbar .= "sayfa $cursayfa / $topsayfa ($total)";
$gezbar .= "</td></tr></table>";

echo $gezbar;

$sql = "select * from ob_incik order by id desc limit $basla,$limit";
$query = new DB_query($db, $sql);

while ($query->db_fetch_object())
{
	$id = $query->obj->id;
	$baslik = $query->obj->baslik;
	$icerik = $query->obj->icerik;
	$yazarid = $query->obj->yazarid;
	
	$s = "select isim from ob_uyeler where id = '$yazarid'";
	$q = new DB_query($db, $s);
	$q->db_fetch_object();
	
	$isim = $q->obj->isim;
	
	unset($s);
	unset($q);
	
	$icerik = decode($icerik);
	
	if ($yazarid == $loginyazarid || $loginyazarstatu == "9")
	{
		echo "<div class=\"weblog\" onmouseover=\"document.getElementById('$id').style.visibility='visible'\" onmouseout=\"document.getElementById('$id').style.visibility='hidden'\">";
	}
	else
	{
		echo "<div class=\"weblog\">";
	}
	
	echo "<h3>$baslik</h3>";
	echo "<p>$icerik</p>";
	echo "<div class=\"author\">$isim</div>";

	if ($yazarid == $loginyazarid || $loginyazarstatu == "9")
	{
		echo "<div id=\"$id\" style=\"visibility:hidden\">";
		menuyap("baslat");
		menuyap("menu", "sil gitsin", "#", "if(confirm('silmek istediginden emin misin?')){window.location='incikyaz.php?nedir=sil&id=$id';}");
		menuyap("menu", "degistirecem", "incikyaz.php?nedir=degistir&id=$id");
		menuyap("bitir");
		echo "</div>";
	}

	echo "</div>";
}

echo $gezbar;

include("right.inc.php");

?>