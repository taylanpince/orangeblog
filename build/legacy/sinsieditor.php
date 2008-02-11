<?php

$path = "./";

include("db/db.mysql.php");

if ($loginyazarstatu != "9")
{
	return header("location:index.php");
}

if ($nedir == "")
{
	$title = "sinsi editor";

	include("left.inc.php");
	
	if ($hata == "1")
	{
		$hata = "<div class=\"hata\">alanlari bos birakma be guzelim, fitik ettiniz beni ya.<br><br></div>";
	}
	else
	{
		$hata = "";
	}

	echo "<div align=\"center\">";
	menuyap("baslat");
	menuyap("menu", "uyeler", "admin.php");
	menuyap("menu", "nabiz olcer", "nabizolcer.php");
	menuyap("menu", "sinsi editor", "sinsieditor.php");
	menuyap("bitir");
	echo "</div>";
	
	echo "<h2>admin atraksiyonlari :: sinsi editor</h2>$hata";

	echo "<form method=\"post\" action=\"sinsieditor.php?nedir=ekle\" name=\"sinsiyaz\">";
	echo "<input type=\"hidden\" name=\"bkz\" id=\"bkz\" value=\"0\"><input type=\"hidden\" name=\"gbkz\" id=\"gbkz\" value=\"0\"><input type=\"hidden\" name=\"tez\" id=\"tez\" value=\"0\"><input type=\"hidden\" name=\"url\" id=\"url\" value=\"0\">";

	menuyap("baslat");
	menuyap("menu", "bkz", "#", "kodekle('[bkz]','[/bkz]','sinsi','bkz');");
	menuyap("menu", "gbkz", "#", "kodekle('[gbkz]','[/gbkz]','sinsi','gbkz');");
	menuyap("menu", "tez", "#", "kodekle('[tez]','[/tez]','sinsi','tez');");
	menuyap("menu", "url", "#", "kodekle('[url]','[/url]','sinsi','url');");
	menuyap("bitir");
	
	echo "<br><h4>sinsi haber</h4>";
	echo "<textarea name=\"sinsi\" id=\"sinsi\" cols=\"50\" rows=\"10\"></textarea><br>";

	menuyap("tekbuton", "tutmayin beni", "#", "document.sinsiyaz.submit();");

	echo "</form>";
	
	include("right.inc.php");
}
elseif ($nedir == "ekle")
{
	if ($sinsi == "")
	{
		return header("location:sinsieditor.php?hata=1");
	}
	
	$sinsi = encode($sinsi);
	
	$sql = "insert into ob_sinsi (olay,tarih) values ('$sinsi','$simdi')";
	$query = new DB_query($db, $sql);

	unset($sql);
	unset($query);
	
	$sql = "select id from ob_uyeler";
	$query = new DB_query($db, $sql);
	
	while ($query->db_fetch_object())
	{
		$yazarid = $query->obj->id;
	
		$s = "update ob_uyeler set sinsi = '0' where id = '$yazarid'";
		$q = new DB_query($db, $s);
		
		unset($s);
		unset($q);
	}

	unset($sql);
	unset($query);
	
	return header("location:index.php");
}

?>