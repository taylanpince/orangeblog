<?php

$path = "./";

include("db/db.mysql.php");

if ($nedir == "")
{
	if ($id == "")
	{
		return header("location:bulusma.php");
	}
	
	$sql = "select * from ob_bulusma where id = '$id'";
	$query = new DB_query($db, $sql);
	$query->db_fetch_object();
	
	$id = $query->obj->id;
	$yazarid = $query->obj->yazarid;
	$olay = $query->obj->olay;
	$aciklama = $query->obj->aciklama;
	$mekan = $query->obj->mekan;
	$zaman = $query->obj->zaman;
	$haberet = $query->obj->haberet;
	
	unset($sql);
	unset($query);
	
	if ($yazarid != $loginyazarid && $loginyazarstatu < "7")
	{
		return header("location:bulusma.php");
	}

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
	echo "<form method=\"post\" action=\"bulusmadegistir.php?nedir=ekle\" name=\"ekle\">";
	echo "<input type=\"hidden\" name=\"id\" value=\"$id\">";
	echo "<input type=\"hidden\" name=\"bkz\" id=\"bkz\" value=\"0\"><input type=\"hidden\" name=\"gbkz\" id=\"gbkz\" value=\"0\"><input type=\"hidden\" name=\"tez\" id=\"tez\" value=\"0\"><input type=\"hidden\" name=\"url\" id=\"url\" value=\"0\">";
	
	if ($zaman == 0)
	{
		$zaman = time();
		$belirsiz = " checked";
	}
	else
	{
		$belirsiz = "";
	}
	
	$bugun = date("j", $zaman);
	$buay = date("m", $zaman);
	$buyil = date("Y", $zaman);
	$busaat = date("G", $zaman);
	$budakika = date("i", $zaman);
	
	echo "<h4>olay nedir?</h4>";
	echo "<input type=\"text\" name=\"olay\" size=\"30\" maxlength=\"50\" value=\"$olay\"><br><br>";
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
	
	for ($i = 2004; $i <= 2006; $i++)
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
	echo "<input type=\"checkbox\" name=\"belirsiz\" value=\"1\"".$belirsiz."> belirsizlikler icerisindeyim<br><br>";
	
	echo "<h4>mekan</h4>";
	echo "<input type=\"text\" name=\"mekan\" size=\"30\" maxlength=\"150\" value=\"$mekan\"><br><br>";
	
	echo "<h4>aciklama</h4>";
	
	menuyap("baslat");
	menuyap("menu", "bkz", "#", "kodekle('[bkz]','[/bkz]','aciklama','bkz');");
	menuyap("menu", "gbkz", "#", "kodekle('[gbkz]','[/gbkz]','aciklama','gbkz');");
	menuyap("menu", "tez", "#", "kodekle('[tez]','[/tez]','aciklama','tez');");
	menuyap("menu", "url", "#", "kodekle('[url]','[/url]','aciklama','url');");
	menuyap("bitir");
	
	echo "<textarea name=\"aciklama\" id=\"aciklama\" cols=\"50\" rows=\"10\">$aciklama</textarea><br>";
	
	if ($haberet == "1")
	{
		$haberet = "selected";
	}
	else
	{
		$haberet = "";
	}
	
	echo "<input type=\"checkbox\" name=\"haberet\" value=\"1\"".$haberet."> yorumlari mesajla haber et bana<br><br>";

	menuyap("baslat");
	menuyap("menu", "guncelledim hocam", "#", "document.ekle.submit();");
	menuyap("bitir");

	echo "</form>";
	
	include("right.inc.php");
}
elseif ($nedir == "ekle")
{
	if ($id == "")
	{
		return header("location:bulusma.php");
	}
	elseif ($olay == "" || $mekan == "" || $aciklama == "")
	{
		return header("location:bulusmadegistir.php?hata=1&id=$id");
	}

	$aciklama = encode($aciklama);
	
	if ($belirsiz == "1")
	{
		$zaman = "0";
	}
	else
	{
		$zaman = mktime($saat, $dakika, 0, $ay, $gun, $yil);
	}
	
	if ($haberet == "1")
	{
		$haberet = "1";
	}
	else
	{
		$haberet = "0";
	}
	
	$sql = "update ob_bulusma set aciklama = '$aciklama', olay = '$olay', mekan = '$mekan', zaman = '$zaman' where id = '$id'";
	$query = new DB_query($db, $sql);

	unset($sql);
	unset($query);
	
	return header("location:bulusyorum.php?id=$id");
}

?>