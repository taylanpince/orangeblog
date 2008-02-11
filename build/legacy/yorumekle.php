<?php

$path = "./";

include("db/db.mysql.php");

if ($nedir == "goster")
{
	if ($blogid == "")
	{
		return header("location:index.php");
	}
	elseif ($yorum == "")
	{
		return header("location:yorumlar.php?id=$blogid&hata=1#yorumyaz");
	}

	$sql = "select baslik from ob_blog where id = '$blogid'";
	$query = new DB_query($db, $sql);
	$query->db_fetch_object();
	
	$baslik = $query->obj->baslik;
	
	unset($sql);
	unset($query);

	$yorum = stripslashes($yorum);

	$title = $baslik;
	
	include("left.inc.php");
	
	echo "<h2>$baslik</h2>";
	echo "<form method=\"post\" action=\"yorumekle.php\" name=\"yorumekle\">";
	echo "<input type=\"hidden\" name=\"blogid\" value=\"$blogid\">";
	echo "<input type=\"hidden\" name=\"yorum\" value=\"$yorum\">";
	echo "<input type=\"hidden\" name=\"haberet\" value=\"$haberet\">";
	echo "<input type=\"hidden\" name=\"filmbu\" value=\"$filmbu\">";
	echo "<input type=\"hidden\" name=\"yildiz\" value=\"$yildiz\">";
	echo "<input type=\"hidden\" name=\"noyildiz\" value=\"$noyildiz\">";
	
	$yorum = encode($yorum);
	$yorum = decode($yorum);
	
	echo "<h4>yorum</h4>";
	echo "$yorum<br><br>";
	
	if ($filmbu == "1" && $noyildiz != "1")
	{
		echo "<h4>elestirmen modu</h4>";
		echo "$yildiz yildiz<br><br>";
	}
	
	if ($haberet == "1")
	{
		echo "diger yorumlar haber edilecek.<br>";
	}
	
	echo "<input type=\"hidden\" name=\"nedir\" value=\"\"><br>";
	
	menuyap("baslat");
	menuyap("menu", "olmus bu", "#", "document.yorumekle.nedir.value='ekle';document.yorumekle.submit();");
	menuyap("menu", "begenmedim geri donelim gayri", "#", "document.yorumekle.nedir.value='degistir';document.yorumekle.submit();");
	menuyap("bitir");
	
	echo "</form>";
	
	include("right.inc.php");
}
elseif ($nedir == "degistir")
{
	$yorum = stripslashes($yorum);

	if ($blogid == "")
	{
		return header("location:index.php");
	}
	elseif ($yorum == "")
	{
		return header("location:yorumlar.php?id=$blogid&hata=1#yorumyaz");
	}

	$sql = "select baslik from ob_blog where id = '$blogid'";
	$query = new DB_query($db, $sql);
	$query->db_fetch_object();
	
	$baslik = $query->obj->baslik;
	
	unset($sql);
	unset($query);

	$yorum = stripslashes($yorum);

	$title = $baslik;
	
	include("left.inc.php");
	
	echo "<h2>$baslik</h2>";
	echo "<form method=\"post\" action=\"yorumekle.php\" name=\"yorumekle\">";
	echo "<input type=\"hidden\" name=\"blogid\" value=\"$blogid\"><input type=\"hidden\" name=\"bkz\" id=\"bkz\" value=\"0\"><input type=\"hidden\" name=\"gbkz\" id=\"gbkz\" value=\"0\"><input type=\"hidden\" name=\"tez\" id=\"tez\" value=\"0\"><input type=\"hidden\" name=\"url\" id=\"url\" value=\"0\">";
	
	menuyap("baslat");
	menuyap("menu", "bkz", "#", "kodekle('[bkz]','[/bkz]','yorum','bkz');");
	menuyap("menu", "gbkz", "#", "kodekle('[gbkz]','[/gbkz]','yorum','gbkz');");
	menuyap("menu", "tez", "#", "kodekle('[tez]','[/tez]','yorum','tez');");
	menuyap("menu", "url", "#", "kodekle('[url]','[/url]','yorum','url');");
	menuyap("bitir");
	
	echo "<h4>yorum</h4><textarea class=\"textarea\" id=\"yorum\" name=\"yorum\" rows=\"10\" cols=\"40\">$yorum</textarea><br>";
	
	if ($filmbu == "1")
	{
		if ($noyildiz == "1")
		{
			$noyildiz = " checked";
		}
		else
		{
			$noyildiz = "";
		}
	
		echo "<input type=\"hidden\" name=\"filmbu\" value=\"1\">";
		echo "<select name=\"yildiz\" class=\"pulldown\">";
		echo "<option value=\"$yildiz\" selected>$yildiz yildiz</option>";
		echo "<option value=\"\">--------</option>";
		echo "<option value=\"1\">1 yildiz</option>";
		echo "<option value=\"2\">2 yildiz</option>";
		echo "<option value=\"3\">3 yildiz</option>";
		echo "<option value=\"4\">4 yildiz</option>";
		echo "<option value=\"5\">5 yildiz</option>";
		echo "</select> veririm ben bu filme.<br>";
		echo "<input type=\"checkbox\" name=\"noyildiz\" value=\"1\"".$noyildiz."> oy vermeyeyim, henuz izlemedim ben (dogrucu davut modu)<br><br>";
	}
	
	if ($haberet == "1")
	{
		$haberet = " checked";
	}
	
	echo "<input type=\"checkbox\" name=\"haberet\" value=\"1\"".$haberet."> mesaj ilen cevaplari haber et bana<br><br>";
	echo "<input type=\"hidden\" name=\"nedir\" value=\"\">";
	
	menuyap("baslat");
	menuyap("menu", "budur", "#", "document.yorumekle.nedir.value='ekle';document.yorumekle.submit();");
	menuyap("menu", "hele bi goster bakalim", "#", "document.yorumekle.nedir.value='goster';document.yorumekle.submit();");
	menuyap("bitir");
	
	echo "</form>";
	
	include("right.inc.php");
}
elseif ($nedir == "ekle")
{
	if ($blogid == "")
	{
		return header("location:index.php");
	}
	elseif ($yorum == "")
	{
		return header("location:yorumlar.php?id=$blogid&hata=1#yorumyaz");
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
	
	$sql = "insert into ob_yorumlar (blogid,yazarid,tarih,yorum,oy,haberet) values ('$blogid','$loginyazarid','$simdi','$yorum','0','$haberet')";
	$query = new DB_query($db, $sql);

	unset($sql);
	unset($query);
	
	$sql = "select baslik,yazarid,haberet,toplamyorum,filmoy,yildiz from ob_blog where id = '$blogid'";
	$query = new DB_query($db, $sql);
	$query->db_fetch_object();
	
	$yazarid = $query->obj->yazarid;
	$haberet = $query->obj->haberet;
	$toplamyorum = $query->obj->toplamyorum;
	$baslik = $query->obj->baslik;
	$eskiyildiz = $query->obj->yildiz;
	$filmoy = $query->obj->filmoy;
	
	unset($sql);
	unset($query);
	
	$toplamyorum = $toplamyorum + 1;
	
	if ($filmbu == "1" && $noyildiz != "1")
	{
		$filmoy = $filmoy + 1;
		$yildiz = $eskiyildiz + $yildiz;
	
		$sql = "update ob_blog set toplamyorum = '$toplamyorum', yildiz = '$yildiz', filmoy = '$filmoy' where id = '$blogid'";
		$query = new DB_query($db, $sql);
	}
	else
	{
		$sql = "update ob_blog set toplamyorum = '$toplamyorum' where id = '$blogid'";
		$query = new DB_query($db, $sql);
	}
	
	unset($sql);
	unset($query);
	
	$loginyazaryorum = $loginyazaryorum + 1;
	
	$sql = "update ob_uyeler set toplamyorum = '$loginyazaryorum', sonyazi = '$simdi' where id = '$loginyazarid'";
	$query = new DB_query($db, $sql);
	
	unset($sql);
	unset($query);
	
	if ($haberet == "1" && $yazarid <> $loginyazarid)
	{
		$mesaj = "$baslik entrine yorum yazildi, okumak icin mutemadiyen <a href=\"yorumlar.php?id=$blogid\">tikla</a>.";
	
		$sql = "insert into ob_mesajlar (kimden, kime, tarih, mesaj, durum) values ('$loginyazarid', '$yazarid', '$simdi', '$mesaj', 'u')";
		$query = new DB_query($db, $sql);
	
		unset($sql);
		unset($query);
	}
	
	$sql = "select yazarid,haberet from ob_yorumlar where (blogid = '$blogid' and haberet = '1' and yazarid <> '$loginyazarid')";
	$query = new DB_query($db, $sql);
	
	while ($query->db_fetch_object())
	{
		$yazarid = $query->obj->yazarid;
		$haberet = $query->obj->haberet;
		
		if ($userarray[$yazarid] == "")
		{
			$userarray[$yazarid] = "1";
			
			$mesaj = "izlemeye aldigin $baslik entrisine yorum yazildi, okumak icin mutemadiyen <a href=\"yorumlar.php?id=$blogid\">tikla</a>.";
		
			$s = "insert into ob_mesajlar (kimden, kime, tarih, mesaj, durum) values ('$loginyazarid', '$yazarid', '$simdi', '$mesaj', 'u')";
			$q = new DB_query($db, $s);
		
			unset($s);
			unset($q);
		}
	}
	
	unset($sql);
	unset($query);
	
	return header("location:yorumlar.php?id=$blogid");
}

?>