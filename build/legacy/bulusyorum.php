<?php

$path = "./";

include("db/db.mysql.php");

if ($id == "")
{
	return header("location:bulusma.php");
}

$sql = "select * from ob_bulusma where id = '$id'";
$query = new DB_query($db, $sql);
$query->db_fetch_object();

$id = $query->obj->id;
$yazarid = $query->obj->yazarid;
$tarih = $query->obj->tarih;
$zaman = $query->obj->zaman;
$olay = $query->obj->olay;
$mekan = $query->obj->mekan;
$aciklama = $query->obj->aciklama;
$katilimcilar = $query->obj->katilimcilar;

unset($sql);
unset($query);

$title = "$olay";

include("left.inc.php");

echo "<div align=\"center\">";
menuyap("baslat");
menuyap("menu", "yaklasan bulusmalar", "bulusma.php");
menuyap("menu", "bulusma arsivi", "bulusma.php?nedir=arsiv");
menuyap("menu", "organize edecem!", "bulusmaekle.php");
menuyap("bitir");
echo "</div>";

$tarih = date("d.m.Y | G:i", $tarih);
$aciklama = decode($aciklama);

$s = "select isim from ob_uyeler where id = '$yazarid'";
$q = new DB_query($db, $s);
$q->db_fetch_object();

$yazarisim = $q->obj->isim;

unset($s);
unset($q);

echo "<h2>$olay</h2>";

echo "<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr>";
echo "<td width=\"125\" valign=\"top\" align=\"left\"><div class=\"bulusma\">";

if ($zaman == 0)
{
	echo "<b>zaman:</b> belirsiz<br>";
}
else
{
	$yapzaman = date("d.m.Y", $zaman);
	$gun = guncevir($zaman);
	$saat = date("G:i", $zaman);

	echo "<b>gun:</b> $yapzaman, $gun<br>";
	echo "<b>saat:</b> $saat<br>";
	
	if ($zaman > $simdi)
	{
		$kalangun = floor(($zaman - $simdi) / (60 * 60 * 24));
		
		if ($kalangun == "0")
		{
			$kalansaat = floor(($zaman - $simdi) / (60 * 60));
		
			echo "$kalansaat saat kalmis.<br>";
		}
		else
		{
			echo "$kalangun gun kalmis.<br>";
		}
	}
}

echo "<br><b>organizator:</b> <a href=\"#\" onClick=\"return pencere('".$path."mesajgonder.php?kime=$yazarid','mesaj',400,510,50,50)\">$yazarisim</a><br>";
echo "<b>mekan:</b> $mekan<br><br>";
echo "<b>katilanlar:</b><br>";

if ($katilimcilar == "")
{
	echo "henuz kimse yok ortalikta<br>";
}
else
{
	$katilim = explode(".", $katilimcilar);
	
	foreach ($katilim as $value)
	{
		$s = "select isim from ob_uyeler where id = '$value'";
		$q = new DB_query($db, $s);
		$q->db_fetch_object();
		
		$katilisim = $q->obj->isim;
		
		unset($s);
		unset($q);
		
		if ($value == "$loginyazarid")
		{
			$katil = "1";
		}
	
		echo "<a href=\"#\" onClick=\"return pencere('".$path."mesajgonder.php?kime=$value','mesaj',400,510,50,50)\">$katilisim</a><br>";
	}
}

echo "<br>";

if ($katil == "1")
{
	menuyap("tekbuton", "gelemiyorum ben", "bulusliste.php?nedir=cik&id=$id");
}
else
{
	menuyap("tekbuton", "ben de katilacam", "bulusliste.php?nedir=katil&id=$id");
}

echo "</div></td><td width=\"10\">&nbsp</td>";

if (isset($HTTP_COOKIE_VARS['member_code']) && $loginyazarstatu >= "2")
{
	echo "<td valign=\"top\"><div class=\"weblog\" onmouseover=\"document.getElementById('$id').style.visibility='visible'\" onmouseout=\"document.getElementById('$id').style.visibility='hidden'\">";
}
else
{
	echo "<td><div class=\"weblog\">";
}

echo "<p>$aciklama</p>";

echo "<div class=\"author\">$yazarisim | $tarih</div>";

if (isset($HTTP_COOKIE_VARS['member_code']) && $loginyazarstatu >= "2")
{
	echo "<div id=\"$id\" style=\"visibility:hidden\">";
	menuyap("baslat");
	menuyap("menu", "o kim?", "#", "return pencere('".$path."yazarhakkinda.php?id=$yazarid','info',375,400,50,50)");
	menuyap("menu", "mesaj", "#", "return pencere('".$path."mesajgonder.php?kime=$yazarid&baslik=$baslik','mesaj',400,510,50,50)");
	
	if ($loginyazarid == $yazarid || $loginyazarstatu == "9")
	{
		menuyap("menu", "degistir", "bulusmadegistir.php?id=$id");
		menuyap("menu", "sil", "#", "if(confirm('emin misin? yorumlar falan gidecek toptan.')){window.location='bulusmasil.php?id=$id';}");
	}
	
	menuyap("bitir");
	echo "</div>";
}

echo "</div>";

$sql = "select * from ob_bulusyorum where bulusid = '$id' order by tarih asc";
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
				menuyap("menu", "degistir", "bulusyorumdegistir.php?id=$yorumid");
				menuyap("menu", "sil", "#", "if(confirm('emin misin? geri donusu yok bu isin...')){window.location='bulusyorumsil.php?id=$yorumid';}");
			}
			
			menuyap("bitir");	
			echo "</div>";
		}
		
		echo "</div>";
	}
}

unset($sql);
unset($query);

echo "</td></tr></table>";

if (isset($HTTP_COOKIE_VARS['member_code']) && $loginyazarstatu >= "2")
{
	echo "<br>";

	if ($hata == "1")
	{
		$hata = "<div class=\"hata\">alanlari bos birakma be guzelim, fitik ettiniz beni ya.<br><br></div>";
	}
	else
	{
		$hata = "";
	}
	
	echo "<a name=\"yorumyaz\"></a><h3>yorum yazasin</h3>$hata";
	echo "<form method=\"post\" action=\"bulusyorumekle.php?nedir=ekle\" name=\"yorumekle\">";
	echo "<input type=\"hidden\" name=\"bulusid\" value=\"$id\"><input type=\"hidden\" name=\"bkz\" id=\"bkz\" value=\"0\"><input type=\"hidden\" name=\"gbkz\" id=\"gbkz\" value=\"0\"><input type=\"hidden\" name=\"tez\" id=\"tez\" value=\"0\"><input type=\"hidden\" name=\"url\" id=\"url\" value=\"0\">";
	
	menuyap("baslat");
	menuyap("menu", "bkz", "#", "kodekle('[bkz]','[/bkz]','yorum','bkz');");
	menuyap("menu", "gbkz", "#", "kodekle('[gbkz]','[/gbkz]','yorum','gbkz');");
	menuyap("menu", "tez", "#", "kodekle('[tez]','[/tez]','yorum','tez');");
	menuyap("menu", "url", "#", "kodekle('[url]','[/url]','yorum','url');");
	menuyap("bitir");
	
	echo "<textarea class=\"textarea\" id=\"yorum\" name=\"yorum\" rows=\"10\" cols=\"40\"></textarea><br>";
	echo "<input type=\"checkbox\" name=\"haberet\" value=\"1\"> mesaj ilen cevaplari haber et bana<br><br>";
	
	menuyap("baslat");
	menuyap("menu", "budur", "#", "document.yorumekle.submit();");
	menuyap("bitir");
	
	echo "</form>";
}

include("right.inc.php");

?>