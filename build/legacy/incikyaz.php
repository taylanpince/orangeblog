<?php

$path = "./";

include("db/db.mysql.php");

$s = "select incik from ob_uyeler where id = '$loginyazarid'";
$q = new DB_query($db, $s);
$q->db_fetch_object();

$incikyetki = $q->obj->incik;

unset($s);
unset($q);

if ($incikyetki != "1")
{
	return header("location:index.php");
}

if ($nedir == "")
{
	$title = "incik cincik aparati";

	include("left.inc.php");
	
	if ($hata == "1")
	{
		$hata = "<div class=\"hata\">alanlari bos birakma be guzelim, fitik ettiniz beni ya.<br><br></div>";
	}
	elseif ($hata == "2")
	{
		$hata = "<div class=\"hata\">bu incigi degistirme yetkin yok senin, yok oyle.<br><br></div>";
	}
	elseif ($hata == "3")
	{
		$hata = "<div class=\"hata\">hangi incigi cincigi degistiricen ki? hata yaptin biyerlerde sen.<br><br></div>";
	}
	else
	{
		$hata = "";
	}
	
	echo "<h2>incik cincik aparati</h2>$hata";

	echo "<form method=\"post\" action=\"incikyaz.php?nedir=ekle\" name=\"incikyaz\">";
	echo "<input type=\"hidden\" name=\"bkz\" id=\"bkz\" value=\"0\"><input type=\"hidden\" name=\"gbkz\" id=\"gbkz\" value=\"0\"><input type=\"hidden\" name=\"tez\" id=\"tez\" value=\"0\"><input type=\"hidden\" name=\"url\" id=\"url\" value=\"0\">";

	menuyap("baslat");
	menuyap("menu", "bkz", "#", "kodekle('[bkz]','[/bkz]','incik','bkz');");
	menuyap("menu", "gbkz", "#", "kodekle('[gbkz]','[/gbkz]','incik','gbkz');");
	menuyap("menu", "tez", "#", "kodekle('[tez]','[/tez]','incik','tez');");
	menuyap("menu", "url", "#", "kodekle('[url]','[/url]','incik','url');");
	menuyap("bitir");
	
	echo "<br><h4>incik cincik basligi</h4>";
	echo "<input type=\"text\" name=\"baslik\" size=\"30\" maxlength=\"50\"><br><br>";
	echo "<h4>buraya dok icini</h4>";
	echo "<textarea name=\"incik\" id=\"incik\" cols=\"50\" rows=\"10\"></textarea><br>";

	menuyap("tekbuton", "cok fena gozlem yaptim", "#", "document.incikyaz.submit();");

	echo "</form>";
	
	include("right.inc.php");
}
elseif ($nedir == "ekle")
{
	if ($baslik == "" || $incik == "")
	{
		return header("location:incikyaz.php?hata=1");
	}

	$incik = encode($incik);
	
	$sql = "insert into ob_incik (yazarid,baslik,icerik) values ('$loginyazarid','$baslik','$incik')";
	$query = new DB_query($db, $sql);

	unset($sql);
	unset($query);
	
	return header("location:index.php");
}
elseif ($nedir == "degistir")
{
	if ($id == "")
	{
		return header("location:incikyaz.php?hata=3");
	}

	$sql = "select * from ob_incik where id = '$id'";
	$query = new DB_query($db, $sql);
	$query->db_fetch_object();

	$id = $query->obj->id;
	$baslik = $query->obj->baslik;
	$icerik = $query->obj->icerik;
	$yazarid = $query->obj->yazarid;
	
	unset($sql);
	unset($query);
	
	if ($yazarid != $loginyazarid && $loginyazarstatu < "9")
	{
		return header("location:incikyaz.php?hata=2");
	}

	$title = "incik cincik aparati";

	include("left.inc.php");
	
	if ($hata == "1")
	{
		$hata = "<div class=\"hata\">alanlari bos birakma be guzelim, fitik ettiniz beni ya.<br><br></div>";
	}
	else
	{
		$hata = "";
	}
	
	echo "<h2>incik cincik aparati :: degistir</h2>$hata";

	echo "<form method=\"post\" action=\"incikyaz.php?nedir=guncelle\" name=\"incikyaz\">";
	echo "<input type=\"hidden\" name=\"id\" value=\"$id\">";
	echo "<input type=\"hidden\" name=\"bkz\" id=\"bkz\" value=\"0\"><input type=\"hidden\" name=\"gbkz\" id=\"gbkz\" value=\"0\"><input type=\"hidden\" name=\"tez\" id=\"tez\" value=\"0\"><input type=\"hidden\" name=\"url\" id=\"url\" value=\"0\">";

	menuyap("baslat");
	menuyap("menu", "bkz", "#", "kodekle('[bkz]','[/bkz]','incik','bkz');");
	menuyap("menu", "gbkz", "#", "kodekle('[gbkz]','[/gbkz]','incik','gbkz');");
	menuyap("menu", "tez", "#", "kodekle('[tez]','[/tez]','incik','tez');");
	menuyap("menu", "url", "#", "kodekle('[url]','[/url]','incik','url');");
	menuyap("bitir");
	
	echo "<br><h4>incik cincik basligi</h4>";
	echo "<input type=\"text\" name=\"baslik\" size=\"30\" maxlength=\"50\" value=\"$baslik\"><br><br>";
	echo "<h4>buraya dok icini</h4>";
	echo "<textarea name=\"incik\" id=\"incik\" cols=\"50\" rows=\"10\">$icerik</textarea><br>";

	menuyap("tekbuton", "cok pis gozlem yaptim", "#", "document.incikyaz.submit();");

	echo "</form>";
	
	include("right.inc.php");
}
elseif ($nedir == "guncelle")
{
	if ($id == "")
	{
		return header("location:index.php");
	}
	elseif ($baslik == "" || $incik == "")
	{
		return header("location:incikyaz.php?nedir=degistir&hata=1&id=$id");
	}

	$incik = encode($incik);
	
	$sql = "update ob_incik set baslik = '$baslik', icerik = '$incik' where id = '$id'";
	$query = new DB_query($db, $sql);

	unset($sql);
	unset($query);
	
	return header("location:incik.php");
}
elseif ($nedir == "sil")
{
	if ($id == "")
	{
		return header("location:incikyaz.php?hata=3");
	}

	$sql = "select yazarid from ob_incik where id = '$id'";
	$query = new DB_query($db, $sql);
	$query->db_fetch_object();

	$yazarid = $query->obj->yazarid;
	
	unset($sql);
	unset($query);
	
	if ($yazarid != $loginyazarid && $loginyazarstatu < "9")
	{
		return header("location:incikyaz.php?hata=2");
	}
	
	$sql = "delete from ob_incik where id = '$id'";
	$query = new DB_query($db, $sql);

	unset($sql);
	unset($query);
	
	return header("location:incik.php");
}

?>