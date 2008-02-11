<?php

$path = "./";

include("db/db.mysql.php");

if (!isset($HTTP_COOKIE_VARS['member_code']))
{
	return header("location:giris.php?nedir=form&hata=4&hedef=zirtapoz.php");
}

if ($nedir == "")
{
	$title = "zirtapoz editoru";

	include("left.inc.php");
	
	if ($hata == "1")
	{
		$hata = "<div class=\"hata\">alanlari bos birakma be guzelim, fitik ettiniz beni ya.<br><br></div>";
	}
	elseif ($hata == "2")
	{
		$hata = "<div class=\"hata\">entri basligini yanlis girdin, nasil becerdin ben de bilemiyorum?<br><br></div>";
	}
	elseif ($hata == "3")
	{
		$hata = "<div class=\"hata\">yuklemeye calistigin resim yanlis boyutlarda, 150*100 dedik, bu kadar basit. nedir anlamadigin cozemedim?<br><br></div>";
	}
	else
	{
		$hata = "";
	}
	
	if ($kata == "1")
	{
		$kata = "<div class=\"hata\">alanlari bos birakma be guzelim, fitik ettiniz beni ya.<br><br></div>";
	}
	else
	{
		$kata = "";
	}
	
	echo "<h2>zirtapoz editoru</h2>$hata";
	echo "<div class=\"weblog\"><form method=\"post\" action=\"zirtapoz.php?nedir=ekle\" name=\"zirtapoz\" enctype=\"multipart/form-data\">";
	echo "<h4>zirtapoz goruntusu (gif veya jpg, 150*100 piksel)</h4>";
	echo "<input type=\"file\" name=\"dosya\" size=\"20\"><br><br>";
	echo "<h4>baglanacak entrinin basligi (yanlis girme gozunu oyarim)</h4>";
	echo "<input type=\"text\" name=\"baslik\" size=\"30\" maxlength=\"50\"><br><br>";

	menuyap("tekbuton", "cilginlar gibiyim", "#", "document.zirtapoz.submit();");

	echo "</form></div>";
	
	echo "<h2>vecize editoru</h2>$kata";
	echo "<div class=\"weblog\"><form method=\"post\" action=\"zirtapoz.php?nedir=vecize\" name=\"vecize\">";
	echo "<h4>vecize</h4>";
	echo "<textarea name=\"vecize\" cols=\"40\" rows=\"5\"></textarea><br><br>";
	echo "<h4>kim demis?</h4>";
	echo "<input type=\"text\" name=\"kisi\" size=\"30\" maxlength=\"50\"><br><br>";

	menuyap("tekbuton", "ozluyum sozluyum", "#", "document.vecize.submit();");

	echo "</form></div>";
	
	include("right.inc.php");
}
elseif ($nedir == "ekle")
{
	if ($baslik == "" || $dosya == "")
	{
		return header("location:zirtapoz.php?hata=1");
	}
	
	$baslik = temizlikciteyze($baslik);
	
	$sql = "select id from ob_blog where baslik = '$baslik'";
	$query = new DB_query($db, $sql);
	$toplam = $query->db_num_rows();
	
	if ($toplam == "1")
	{
		$query->db_fetch_object();
		
		$id = $query->obj->id;
	}
	else
	{
		return header("location:zirtapoz.php?hata=2");
	}

	unset($sql);
	unset($query);

	$gecicidosya = $HTTP_POST_FILES["dosya"]["tmp_name"];
	$dosyaismi = $HTTP_POST_FILES["dosya"]["name"];
	$dosyabuyuklugu = $HTTP_POST_FILES["dosya"]["size"];
	$dosyaformati = $HTTP_POST_FILES["dosya"]["type"];
	
	$adres = "zirtapoz/".$dosyaismi;

	if (file_exists($adres))
	{
		$title = "zirtapoz editoru";

		include("left.inc.php");
	
		echo "<h2>zirtapoz editoru</h2>";
		
		echo "boyle bir dosya varmis zaten, sen en iyisi ismini degistir bunun.<br><br>";
		echo menuyap_return("tekbuton", "bühühühü", "#", "history.go(-1);");
		
		include("right.inc.php");
	}
	else
	{
		move_uploaded_file($gecicidosya, $adres);
		
		$genislik = "150";
		$yukseklik = "100";
			
		$resimbuyuklugu = getimagesize($adres);
		
		if ($resimbuyuklugu[0] != $genislik || $resimbuyuklugu[1] != $yukseklik)
		{
			unlink($adres);
			return header("location:zirtapoz.php?hata=3");
		}

		$sql = "insert into ob_zirtapoz (resim,blogid,tarih) values ('$dosyaismi','$id','$simdi')";
		$query = new DB_query($db, $sql);
	
		unset($sql);
		unset($query);
		
		$title = "zirtapoz editoru";

		include("left.inc.php");

		echo "<h2>zirtapoz editoru</h2>";
		echo "ekledim hocam, hadi bakalim hayirli olsun.<br><br>";
		echo "<h4>$baslik</h4>";
		echo "<img src=\"$adres\" border=\"1\">";
		
		include("right.inc.php");
	}
}
elseif ($nedir == "vecize")
{
	if ($vecize == "" || $kisi == "")
	{
		return header("location:zirtapoz.php?kata=1");
	}
	
	$sql = "insert into ob_vecize (vecize,kisi) values ('$vecize','$kisi')";
	$query = new DB_query($db, $sql);

	unset($sql);
	unset($query);
	
	$title = "zirtapoz editoru";

	include("left.inc.php");

	echo "<h2>zirtapoz editoru</h2>";
	echo "vecizen eklendi, basin goge ermistir artik.<br><br>";
	echo "<div class=\"vecize\">\"$vecize\" -$kisi</div>";
	
	include("right.inc.php");
}

?>