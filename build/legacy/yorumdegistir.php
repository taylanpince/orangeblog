<?php

$path = "./";

include("db/db.mysql.php");


if ($nedir == "")
{
	if ($id == "")
	{
		return header("location:index.php");
	}
	
	$sql = "select * from ob_yorumlar where id = '$id'";
	$query = new DB_query($db, $sql);
	$query->db_fetch_object();
	
	$id = $query->obj->id;
	$blogid = $query->obj->blogid;
	$yorum = $query->obj->yorum;
	$haberet = $query->obj->haberet;
	
	unset($sql);
	unset($query);

	$sql = "select baslik from ob_blog where id = '$blogid'";
	$query = new DB_query($db, $sql);
	$query->db_fetch_object();
	
	$baslik = $query->obj->baslik;
	
	unset($sql);
	unset($query);

	$title = "yorum degistir";
	
	include("left.inc.php");
	
	if ($hata == "1")
	{
		$hata = "<div class=\"hata\">alanlari bos birakma be guzelim, fitik ettiniz beni ya.<br><br></div>";
	}
	else
	{
		$hata = "";
	}
	
	echo "<h2>yorum degistir :: $baslik</h2>$hata";
	echo "<form method=\"post\" action=\"yorumdegistir.php?nedir=ekle\" name=\"yorumekle\">";
	echo "<input type=\"hidden\" name=\"id\" value=\"$id\">";
	echo "<input type=\"hidden\" name=\"bkz\" id=\"bkz\" value=\"0\"><input type=\"hidden\" name=\"gbkz\" id=\"gbkz\" value=\"0\"><input type=\"hidden\" name=\"tez\" id=\"tez\" value=\"0\"><input type=\"hidden\" name=\"url\" id=\"url\" value=\"0\">";
	
	menuyap("baslat");
	menuyap("menu", "bkz", "#", "kodekle('[bkz]','[/bkz]','yorum','bkz');");
	menuyap("menu", "gbkz", "#", "kodekle('[gbkz]','[/gbkz]','yorum','gbkz');");
	menuyap("menu", "tez", "#", "kodekle('[tez]','[/tez]','yorum','tez');");
	menuyap("menu", "url", "#", "kodekle('[url]','[/url]','yorum','url');");
	menuyap("bitir");
	
	echo "<h4>yorum</h4><textarea class=\"textarea\" id=\"yorum\" name=\"yorum\" rows=\"10\" cols=\"40\">$yorum</textarea><br>";
	
	if ($haberet == "1")
	{
		$haberet = " checked";
	}
	
	echo "<input type=\"checkbox\" name=\"haberet\" value=\"1\"".$haberet."> mesaj ilen cevaplari haber et bana<br><br>";
	
	menuyap("baslat");
	menuyap("menu", "simdi oldu", "#", "document.yorumekle.submit();");
	menuyap("bitir");
	
	echo "</form>";
	
	include("right.inc.php");
}
elseif ($nedir == "ekle")
{
	if ($id == "")
	{
		return header("location:index.php");
	}
	elseif ($yorum == "")
	{
		return header("location:yorumdegistir.php?id=$id&hata=1");
	}
	
	$yorum = encode($yorum);
	
	if ($haberet == "1")
	{
		$haberet = "1";
	}
	else
	{
		$haberet = "0";
	}
	
	$sql = "update ob_yorumlar set yorum = '$yorum', haberet = '$haberet' where id = '$id'";
	$query = new DB_query($db, $sql);

	unset($sql);
	unset($query);
	
	$sql = "select blogid from ob_yorumlar where id = '$id'";
	$query = new DB_query($db, $sql);
	$query->db_fetch_object();

	$blogid = $query->obj->blogid;
	
	unset($sql);
	unset($query);
	
	return header("location:yorumlar.php?id=$blogid#$id");
}

?>