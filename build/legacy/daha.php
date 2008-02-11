<?php

$path = "./";

include("db/db.mysql.php");

if ($id == "")
{
	return header("location:bakiniz.php");
}

$sql = "select * from ob_blog where id = '$id'";
$query = new DB_query($db, $sql);
$query->db_fetch_object();

$id = $query->obj->id;
$yazarid = $query->obj->yazarid;
$tarih = $query->obj->tarih;
$baslik = $query->obj->baslik;
$icerik = $query->obj->icerik;
$daha = $query->obj->daha;
$kategori = $query->obj->kategori;
$filmbu = $query->obj->filmbu;
$yildiz = $query->obj->yildiz;
$filmoy = $query->obj->filmoy;

unset($sql);
unset($query);

?>

<html>
<head>
<title>orangeblog :: <?php echo $baslik; ?></title>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8" />
<link rel="icon" href="<?php echo $path; ?>images/orange_icon.gif">
<script language="JavaScript" src="<?php echo $path; ?>kodaman.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $path."tema/".$tema; ?>.css">
</head>

<body>

<div id="content">

<?php

$tarih = date("d.m.Y | G:i", $tarih);
$icerik = decode($icerik);
$daha = decode($daha);

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

echo "<div class=\"weblog\" onmouseover=\"document.getElementById('$id').style.visibility='visible'\" onmouseout=\"document.getElementById('$id').style.visibility='hidden'\">";

echo "<table border=\"0\" width=\"99%\" cellpadding=\"0\" cellspacing=\"0\"><tr>";
echo "<td><h2>$baslik</h2></td>";
echo "<td width=\"100\" valign=\"middle\" align=\"right\">";

menuyap("baslat");
menuyap("menu", "hepsini goster", "#", "self.window.opener.location='yorumlar.php?id=$id';window.close();");
menuyap("menu", "kapa beni", "#", "window.close();");
menuyap("bitir");

echo "</td></tr></table>";

echo "<p>$icerik</p>";

if ($daha <> "")
{
	echo "<p>$daha</p>";
}

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

echo "</div>";

if ($loginyazarid <> "")
{
	echo "<div id=\"$id\" style=\"visibility:hidden\">";
	menuyap("baslat");
	menuyap("menu", "o kim?", "#", "return pencere('".$path."yazarhakkinda.php?id=$yazarid','info',350,400,50,50)");
	menuyap("menu", "mesaj", "#", "return pencere('".$path."mesajgonder.php?id=$id&include=$baslik','mesaj',400,360,50,50)");
	
	if ($loginyazarid == $yazarid || $loginyazarstatu == "9")
	{
		menuyap("menu", "degistir", "#", "return pencere('".$path."degistir.php?id=$id','yeni',725,550,50,50)");
		menuyap("menu", "sil", "#", "return pencere('".$path."sil.php?id=$id','sil',250,200,50,50)");
	}
	
	menuyap("menu", "haber et", "#", "return pencere('".$path."esdost.php?id=$id','esdost',375,400,50,50)");
	
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

?>

</div>

</body>
</html>