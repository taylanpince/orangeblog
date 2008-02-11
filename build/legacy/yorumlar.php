<?php

$path = "./";

include("db/db.mysql.php");

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

$title = $baslik;

include("left.inc.php");

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

$s = "select id,baslik from ob_blog where id > '$id' order by id asc limit 1";
$q = new DB_query($db, $s);
$q->db_fetch_object();

$sonraid = $q->obj->id;
$sonrabaslik = $q->obj->baslik;

unset($s);
unset($q);

$s = "select id,baslik from ob_blog where id < '$id' order by id desc limit 1";
$q = new DB_query($db, $s);
$q->db_fetch_object();

$onceid = $q->obj->id;
$oncebaslik = $q->obj->baslik;

unset($s);
unset($q);

echo "<div align=\"center\">";

if ($onceid <> "")
{
	echo "<a href=\"yorumlar.php?id=$onceid\">< $oncebaslik</a> | ";
}

echo "<a href=\"index.php\">antre</a>";

if ($sonraid <> "")
{
	echo " | <a href=\"yorumlar.php?id=$sonraid\">$sonrabaslik ></a>";
}

echo "</div><br>";

if (isset($HTTP_COOKIE_VARS['member_code']) && $loginyazarstatu >= "2")
{
	echo "<div class=\"weblog\" onmouseover=\"document.getElementById('$id').style.visibility='visible'\" onmouseout=\"document.getElementById('$id').style.visibility='hidden'\">";
}
else
{
	echo "<div class=\"weblog\">";
}

echo "<h2>$baslik</h2>";
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
		menuyap("menu", "igrencsaan", "#", "return pencere('".$path."oyoy.php?postid=$id&vote=0','oyoy',250,150,50,50)");
	}
	
	if ($filmbu == "1")
	{
		menuyap("menu", "imdb", "#", "window.open('http://us.imdb.com/find?q=$baslik')");
	}
	
	menuyap("bitir");
	echo "</div>";
}

echo "</div>";

$sql = "select * from ob_yorumlar where blogid = '$id' order by tarih asc";
$query = new DB_query($db, $sql);
$toplamyorum = $query->db_num_rows();

if ($toplamyorum > 0)
{
	echo "<a name=\"yorumlar\"></a><h3>yorumlar</h3>";
	
	while ($query->db_fetch_object())
	{
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

		if (isset($HTTP_COOKIE_VARS['member_code']) && $loginyazarstatu >= "2")
		{
			echo "<div class=\"weblog\" onmouseover=\"document.getElementById('C_$yorumid').style.visibility='visible'\" onmouseout=\"document.getElementById('C_$yorumid').style.visibility='hidden'\">";
		}
		else
		{
			echo "<div class=\"weblog\">";
		}
		
		echo "<a name=\"$yorumid\"></a><p>$yorum</p>";
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
}

unset($sql);
unset($query);

if (isset($HTTP_COOKIE_VARS['member_code']) && $loginyazarstatu >= "2")
{
	if ($hata == "1")
	{
		$hata = "<div class=\"hata\">alanlari bos birakma be guzelim, fitik ettiniz beni ya.<br><br></div>";
	}
	else
	{
		$hata = "";
	}
	
	echo "<a name=\"yorumyaz\"></a><h3>yorum yazasin</h3>$hata";
	echo "<form method=\"post\" action=\"yorumekle.php\" name=\"yorumekle\">";
	echo "<input type=\"hidden\" name=\"blogid\" value=\"$id\"><input type=\"hidden\" name=\"bkz\" id=\"bkz\" value=\"0\"><input type=\"hidden\" name=\"gbkz\" id=\"gbkz\" value=\"0\"><input type=\"hidden\" name=\"tez\" id=\"tez\" value=\"0\"><input type=\"hidden\" name=\"url\" id=\"url\" value=\"0\">";
	
	menuyap("baslat");
	menuyap("menu", "bkz", "#", "kodekle('[bkz]','[/bkz]','yorum','bkz');");
	menuyap("menu", "gbkz", "#", "kodekle('[gbkz]','[/gbkz]','yorum','gbkz');");
	menuyap("menu", "tez", "#", "kodekle('[tez]','[/tez]','yorum','tez');");
	menuyap("menu", "url", "#", "kodekle('[url]','[/url]','yorum','url');");
	menuyap("bitir");
	
	echo "<textarea class=\"textarea\" id=\"yorum\" name=\"yorum\" rows=\"10\" cols=\"40\"></textarea><br>";
	
	if ($filmbu == "1")
	{
		echo "<input type=\"hidden\" name=\"filmbu\" value=\"1\">";
		echo "<select name=\"yildiz\" class=\"pulldown\">";
		echo "<option value=\"1\">1 yildiz</option>";
		echo "<option value=\"2\">2 yildiz</option>";
		echo "<option value=\"3\" selected>3 yildiz</option>";
		echo "<option value=\"4\">4 yildiz</option>";
		echo "<option value=\"5\">5 yildiz</option>";
		echo "</select> veririm ben bu filme.<br>";
		echo "<input type=\"checkbox\" name=\"noyildiz\" value=\"1\"> oy vermeyeyim, henuz izlemedim ben (dogrucu davut modu)<br><br>";
	}
	
	echo "<input type=\"checkbox\" name=\"haberet\" value=\"1\"> mesaj ilen cevaplari haber et bana<br><br>";
	echo "<input type=\"hidden\" name=\"nedir\" value=\"\">";
	
	menuyap("baslat");
	menuyap("menu", "budur", "#", "document.yorumekle.nedir.value='ekle';document.yorumekle.submit();");
	menuyap("menu", "hele bi goster bakalim", "#", "document.yorumekle.nedir.value='goster';document.yorumekle.submit();");
	menuyap("bitir");
	
	echo "</form>";
}

include("right.inc.php");

?>