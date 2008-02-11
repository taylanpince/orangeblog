<?php

$path = "./";

include("db/db.mysql.php");

if ($loginyazarid == "")
{
	return header("location:giris.php?hata=5&hedef=bulusmaekle.php");
}

if ($nedir == "")
{
	$title = "bulusma aparati";

	include("left.inc.php");
	
	echo "<div align=\"center\">";
	menuyap("baslat");
	menuyap("menu", "yaklasan bulusmalar", "bulusma.php");
	menuyap("menu", "bulusma arsivi", "bulusma.php?nedir=arsiv");
	menuyap("menu", "organize edecem!", "bulusmaekle.php");
	menuyap("bitir");
	echo "</div>";
	
	if ($hata == "1")
	{
		$hata = "<div class=\"hata\">alanlari bos birakma be guzelim, fitik ettiniz beni ya.<br><br></div>";
	}
	else
	{
		$hata = "";
	}
	
	echo "<h2>bulusma aparati :: organize et</h2>";
	
	echo $hata;
	echo "<form method=\"post\" action=\"bulusmaekle.php?nedir=ekle\" name=\"ekle\">";
	echo "<input type=\"hidden\" name=\"bkz\" id=\"bkz\" value=\"0\"><input type=\"hidden\" name=\"gbkz\" id=\"gbkz\" value=\"0\"><input type=\"hidden\" name=\"tez\" id=\"tez\" value=\"0\"><input type=\"hidden\" name=\"url\" id=\"url\" value=\"0\">";
	
	$bugun = date("j", $simdi);
	$buay = date("m", $simdi);
	$buyil = date("Y", $simdi);
	$busaat = date("G", $simdi);
	$budakika = date("i", $simdi);
	
	echo "<h4>olay nedir?</h4>";
	echo "<input type=\"text\" name=\"olay\" size=\"30\" maxlength=\"50\"><br><br>";
	echo "<h4>ne zaman olacak?</h4>";
	echo "tarih: <select name=\"gun\" class=\"pulldown\">";
	
	for ($i = 1; $i <= 31; $i++)
	{
		if ($i == $bugun)
		{
			echo "<option value=\"$i\" selected>$i</option>";
		}
		else
		{
			echo "<option value=\"$i\">$i</option>";
		}
	}
	
	echo "</select> <select name=\"ay\" class=\"pulldown\">";
	
	for ($i = 1; $i <= 12; $i++)
	{
		if ($i == $buay)
		{
			echo "<option value=\"$i\" selected>$i</option>";
		}
		else
		{
			echo "<option value=\"$i\">$i</option>";
		}
	}
	
	echo "</select> <select name=\"yil\" class=\"pulldown\">";
	
	for ($i = $buyil; $i <= ($buyil + 1); $i++)
	{
		if ($i == $buyil)
		{
			echo "<option value=\"$i\" selected>$i</option>";
		}
		else
		{
			echo "<option value=\"$i\">$i</option>";
		}
	}
	
	echo "</select> saat: <select name=\"saat\" class=\"pulldown\">";
	
	for ($i = 1; $i <= 23; $i++)
	{
		if ($i == $busaat)
		{
			echo "<option value=\"$i\" selected>$i</option>";
		}
		else
		{
			echo "<option value=\"$i\">$i</option>";
		}
	}
	
	echo "</select> <select name=\"dakika\" class=\"pulldown\">";
	
	for ($i = 0; $i <= 59; $i++)
	{
		if ($i == $budakika)
		{
			echo "<option value=\"$i\" selected>$i</option>";
		}
		else
		{
			echo "<option value=\"$i\">$i</option>";
		}
	}
	
	echo "</select><br>";
	echo "<input type=\"checkbox\" name=\"belirsiz\" value=\"1\"> belirsizlikler icerisindeyim<br><br>";
	
	echo "<h4>mekan</h4>";
	echo "<input type=\"text\" name=\"mekan\" size=\"30\" maxlength=\"150\"><br><br>";
	
	echo "<h4>aciklama</h4>";
	
	menuyap("baslat");
	menuyap("menu", "bkz", "#", "kodekle('[bkz]','[/bkz]','aciklama','bkz');");
	menuyap("menu", "gbkz", "#", "kodekle('[gbkz]','[/gbkz]','aciklama','gbkz');");
	menuyap("menu", "tez", "#", "kodekle('[tez]','[/tez]','aciklama','tez');");
	menuyap("menu", "url", "#", "kodekle('[url]','[/url]','aciklama','url');");
	menuyap("bitir");
	
	echo "<textarea name=\"aciklama\" id=\"aciklama\" cols=\"50\" rows=\"10\"></textarea><br>";
	
	echo "<input type=\"checkbox\" name=\"haberet\" value=\"1\"> yorumlari mesajla haber et bana<br><br>";

	menuyap("baslat");
	menuyap("menu", "yaptim bitirdim", "#", "document.ekle.submit();");
	menuyap("bitir");

	echo "</form>";
	
	include("right.inc.php");
}
elseif ($nedir == "ekle")
{
	if ($olay == "" || $mekan == "" || $aciklama == "")
	{
		return header("location:bulusmaekle.php?hata=1");
	}
	
	if ($belirsiz == "1")
	{
		$zaman = "0";
	}
	else
	{
		$zaman = mktime($saat, $dakika, 0, $ay, $gun, $yil);
	}
	
	$aciklama = encode($aciklama);
	
	if ($haberet == "1")
	{
		$haberet = "1";
	}
	else
	{
		$haberet = "0";
	}
	
	$sql = "insert into ob_bulusma (yazarid,tarih,zaman,olay,aciklama,mekan,haberet,katilimcilar) values ('$loginyazarid','$simdi','$zaman','$olay','$aciklama','$mekan','$haberet','$loginyazarid')";
	$query = new DB_query($db, $sql);

	unset($sql);
	unset($query);
	
	return header("location:bulusma.php");
}

?>