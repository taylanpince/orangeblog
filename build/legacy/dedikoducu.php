<?php

$path = "./";

include("db/db.mysql.php");

$s = "select dedikodu from ob_uyeler where id = '$loginyazarid'";
$q = new DB_query($db, $s);
$q->db_fetch_object();

$dedikoducu = $q->obj->dedikodu;

unset($s);
unset($q);

if ($dedikoducu != "1")
{
	return header("location:index.php");
}

if ($nedir == "")
{
	$title = "dedikoducu";

	include("left.inc.php");
	
	if ($hata == "1")
	{
		$hata = "<div class=\"hata\">alanlari bos birakma be guzelim, fitik ettiniz beni ya.<br><br></div>";
	}
	elseif ($hata == "2")
	{
		$hata = "<div class=\"hata\">bu dedikoduyu degistirme yetkin yok senin, yok oyle.<br><br></div>";
	}
	elseif ($hata == "3")
	{
		$hata = "<div class=\"hata\">hangi dedikoduyu degistiricen ki? hata yaptin biyerlerde sen.<br><br></div>";
	}
	else
	{
		$hata = "";
	}
	
	echo "<h2>dedikoducu</h2>$hata";

	echo "<form method=\"post\" action=\"dedikoducu.php?nedir=ekle\" name=\"dedikoduyaz\">";
	echo "<input type=\"hidden\" name=\"bkz\" id=\"bkz\" value=\"0\"><input type=\"hidden\" name=\"gbkz\" id=\"gbkz\" value=\"0\"><input type=\"hidden\" name=\"tez\" id=\"tez\" value=\"0\"><input type=\"hidden\" name=\"url\" id=\"url\" value=\"0\">";

	menuyap("baslat");
	menuyap("menu", "bkz", "#", "kodekle('[bkz]','[/bkz]','dedikodu','bkz');");
	menuyap("menu", "gbkz", "#", "kodekle('[gbkz]','[/gbkz]','dedikodu','gbkz');");
	menuyap("menu", "tez", "#", "kodekle('[tez]','[/tez]','dedikodu','tez');");
	menuyap("menu", "url", "#", "kodekle('[url]','[/url]','dedikodu','url');");
	menuyap("bitir");
	
	echo "<br><h4>dedikodu basligi</h4>";
	echo "<input type=\"text\" name=\"baslik\" size=\"30\" maxlength=\"50\"><br><br>";
	echo "<h4>dedikodu da buraya</h4>";
	echo "<textarea name=\"dedikodu\" id=\"dedikodu\" cols=\"50\" rows=\"10\"></textarea><br>";

	menuyap("tekbuton", "cok pis dedikodu yaptim", "#", "document.dedikoduyaz.submit();");

	echo "</form>";
	
	include("right.inc.php");
}
elseif ($nedir == "ekle")
{
	if ($baslik == "" || $dedikodu == "")
	{
		return header("location:dedikoducu.php?hata=1");
	}

	$dedikodu = encode($dedikodu);
	
	$sql = "insert into ob_dedikodu (yazarid,baslik,icerik) values ('$loginyazarid','$baslik','$dedikodu')";
	$query = new DB_query($db, $sql);

	unset($sql);
	unset($query);
	
	return header("location:index.php");
}
elseif ($nedir == "degistir")
{
	if ($id == "")
	{
		return header("location:dedikoducu.php?hata=3");
	}

	$sql = "select * from ob_dedikodu where id = '$id'";
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
		return header("location:dedikoducu.php?hata=2");
	}

	$title = "dedikoducu";

	include("left.inc.php");
	
	if ($hata == "1")
	{
		$hata = "<div class=\"hata\">alanlari bos birakma be guzelim, fitik ettiniz beni ya.<br><br></div>";
	}
	else
	{
		$hata = "";
	}
	
	echo "<h2>dedikoducu :: degistir</h2>$hata";

	echo "<form method=\"post\" action=\"dedikoducu.php?nedir=guncelle\" name=\"dedikoduyaz\">";
	echo "<input type=\"hidden\" name=\"id\" value=\"$id\">";
	echo "<input type=\"hidden\" name=\"bkz\" id=\"bkz\" value=\"0\"><input type=\"hidden\" name=\"gbkz\" id=\"gbkz\" value=\"0\"><input type=\"hidden\" name=\"tez\" id=\"tez\" value=\"0\"><input type=\"hidden\" name=\"url\" id=\"url\" value=\"0\">";

	menuyap("baslat");
	menuyap("menu", "bkz", "#", "kodekle('[bkz]','[/bkz]','dedikodu','bkz');");
	menuyap("menu", "gbkz", "#", "kodekle('[gbkz]','[/gbkz]','dedikodu','gbkz');");
	menuyap("menu", "tez", "#", "kodekle('[tez]','[/tez]','dedikodu','tez');");
	menuyap("menu", "url", "#", "kodekle('[url]','[/url]','dedikodu','url');");
	menuyap("bitir");
	
	echo "<br><h4>dedikodu basligi</h4>";
	echo "<input type=\"text\" name=\"baslik\" size=\"30\" maxlength=\"50\" value=\"$baslik\"><br><br>";
	echo "<h4>dedikodu da buraya</h4>";
	echo "<textarea name=\"dedikodu\" id=\"dedikodu\" cols=\"50\" rows=\"10\">$icerik</textarea><br>";

	menuyap("tekbuton", "cok pis dedikodu yaptim", "#", "document.dedikoduyaz.submit();");

	echo "</form>";
	
	include("right.inc.php");
}
elseif ($nedir == "guncelle")
{
	if ($id == "")
	{
		return header("location:index.php");
	}
	elseif ($baslik == "" || $dedikodu == "")
	{
		return header("location:dedikoducu.php?nedir=degistir&hata=1&id=$id");
	}

	$dedikodu = encode($dedikodu);
	
	$sql = "update ob_dedikodu set baslik = '$baslik', icerik = '$dedikodu' where id = '$id'";
	$query = new DB_query($db, $sql);

	unset($sql);
	unset($query);
	
	return header("location:dedikodu.php");
}
elseif ($nedir == "sil")
{
	if ($id == "")
	{
		return header("location:dedikoducu.php?hata=3");
	}

	$sql = "select yazarid from ob_dedikodu where id = '$id'";
	$query = new DB_query($db, $sql);
	$query->db_fetch_object();

	$yazarid = $query->obj->yazarid;
	
	unset($sql);
	unset($query);
	
	if ($yazarid != $loginyazarid && $loginyazarstatu < "9")
	{
		return header("location:dedikoducu.php?hata=2");
	}
	
	$sql = "delete from ob_dedikodu where id = '$id'";
	$query = new DB_query($db, $sql);

	unset($sql);
	unset($query);
	
	return header("location:dedikodu.php");
}

?>