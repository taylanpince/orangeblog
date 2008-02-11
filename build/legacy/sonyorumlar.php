<?php

$path = "./";

include("db/db.mysql.php");

include("left.inc.php");

$sql = "update ob_uyeler set sonyorumoku = '$simdi' where id = '$loginyazarid'";
$query = new DB_query($db, $sql);

unset($sql);
unset($query);

echo "<h2>taze yorumlar</h2>";

$sql = "select * from ob_yorumlar where tarih > '$loginyazaryorumoku' and yazarid <> '$loginyazarid' order by tarih asc";
$query = new DB_query($db, $sql);

while ($query->db_fetch_object())
{
	$blogid = $query->obj->blogid;
	$yorumid = $query->obj->id;
	$yazarid = $query->obj->yazarid;
	$tarih = $query->obj->tarih;
	$yorum = $query->obj->yorum;

	$tarih = date("d.m.Y | G:i", $tarih);
	$yorum = decode($yorum);

	$s = "select isim from ob_uyeler where id = '$yazarid'";
	$q = new DB_query($db, $s);
	$q->db_fetch_object();
	
	$yazarisim = $q->obj->isim;
	
	unset($s);
	unset($q);
	
	$s = "select baslik from ob_blog where id = '$blogid'";
	$q = new DB_query($db, $s);
	$q->db_fetch_object();
	
	$baslik = $q->obj->baslik;
	
	unset($s);
	unset($q);

	if (isset($HTTP_COOKIE_VARS['member_code']) && $loginyazarstatu >= "2")
	{
		echo "<div class=\"weblog\" onmouseover=\"document.getElementById('C_$yorumid').style.visibility='visible'\" onmouseout=\"document.getElementById('C_$yorumid').style.visibility='hidden'\">";
	}
	else
	{
		echo "<div class=\"weblog\">";
	}
	
	echo "<br><a href=\"yorumlar.php?id=$blogid\" class=\"baslik\">$baslik</a><br><p>$yorum</p>";
	echo "<div class=\"author\">$yazarisim | $tarih</div>";

	if (isset($HTTP_COOKIE_VARS['member_code']) && $loginyazarstatu >= "2")
	{
		echo "<div id=\"C_$yorumid\" style=\"visibility:hidden\">";
		menuyap("baslat");
		menuyap("menu", "o kim?", "#", "return pencere('".$path."yazarhakkinda.php?id=$yazarid','info',375,400,50,50)");
		menuyap("menu", "mesaj", "#", "return pencere('".$path."mesajgonder.php?kime=$yazarid','mesaj',400,510,50,50)");
		
		if ($loginyazarid == $yazarid || $loginyazarstatu == "9")
		{
			menuyap("menu", "degistir", "yorumdegistir.php?id=$yorumid");
			menuyap("menu", "sil", "#", "if(confirm('emin misin? geri donusu yok bu isin...')){window.location='yorumsil.php?id=$yorumid';}");
		}
		
		if ($loginyazarid <> $yazarid)
		{
			menuyap("menu", "pek hos", "#", "return pencere('".$path."yorumoy.php?id=$yorumid&vote=1','oyoy',250,150,50,50)");
			menuyap("menu", "igrencsaan", "#", "return pencere('".$path."yorumoy.php?id=$yorumid&vote=0','oyoy',250,150,50,50)");
		}
		
		menuyap("bitir");	
		echo "</div>";
	}
	
	echo "</div>";
}

unset($sql);
unset($query);

include("right.inc.php");

?>