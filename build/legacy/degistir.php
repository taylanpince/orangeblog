<?php

$path = "./";

include("db/db.mysql.php");

if ($nedir == "")
{
	if ($id == "")
	{
		return header("location:index.php");
	}
	
	$sql = "select * from ob_blog where id = '$id'";
	$query = new DB_query($db, $sql);
	$query->db_fetch_object();
	
	$id = $query->obj->id;
	$yazarid = $query->obj->yazarid;
	$baslik = $query->obj->baslik;
	$entri = $query->obj->icerik;
	$daha = $query->obj->daha;
	$kategori = $query->obj->kategori;
	$haberet = $query->obj->haberet;
	$filmbu = $query->obj->filmbu;

	
	unset($sql);
	unset($query);
	
	if ($yazarid != $loginyazarid && $loginyazarstatu < "7")
	{
		return header("location:index.php");
	}

	$title = $baslik;
	
	include("left.inc.php");
	
	if ($hata == "1")
	{
		$hata = "<div class=\"hata\">alanlari bos birakma be guzelim, fitik ettiniz beni ya.<br><br></div>";
	}
	else
	{
		$hata = "";
	}
	
	echo "<h2>$baslik</h2>$hata";
	echo "<form method=\"post\" action=\"degistir.php\" name=\"ekle\">";
	echo "<input type=\"hidden\" name=\"id\" value=\"$id\"><input type=\"hidden\" name=\"baslik\" value=\"$baslik\">";
	echo "<input type=\"hidden\" name=\"bkz\" id=\"bkz\" value=\"0\"><input type=\"hidden\" name=\"gbkz\" id=\"gbkz\" value=\"0\"><input type=\"hidden\" name=\"tez\" id=\"tez\" value=\"0\"><input type=\"hidden\" name=\"url\" id=\"url\" value=\"0\">";

	menuyap("baslat");
	menuyap("menu", "bkz", "#", "kodekle('[bkz]','[/bkz]',alan,'bkz');");
	menuyap("menu", "gbkz", "#", "kodekle('[gbkz]','[/gbkz]',alan,'gbkz');");
	menuyap("menu", "tez", "#", "kodekle('[tez]','[/tez]',alan,'tez');");
	menuyap("menu", "url", "#", "kodekle('[url]','[/url]',alan,'url');");
	menuyap("menu", "resim ekle", "#", "return pencere('dosyagonder.php','dosya',350,400,50,50);");
	menuyap("bitir");
	
	echo "<br><h4>entri</h4>";
	echo "<textarea name=\"entri\" id=\"entri\" onFocus=\"alan=this.name;\" cols=\"50\" rows=\"10\">$entri</textarea><br>";
	echo "<div id=\"dahabaslik\" unselectable=\"on\" style=\"cursor:hand\" onclick=\"if(dahadaha.style.display=='inline'){dahadaha.style.display='none';dahabaslik.innerHTML='<h4>[+] daha daha</h4>';}else{dahadaha.style.display='inline';dahabaslik.innerHTML='<h4>[-] daha daha</h4>';}\"><h4>[+] daha daha</h4></div>";
	
	if ($daha <> "")
	{
		echo "<div id=\"dahadaha\" style=\"display:inline\">";
	}
	else
	{
		echo "<div id=\"dahadaha\" style=\"display:none\">";
	}
	
	echo "<textarea name=\"daha\" id=\"daha\" onFocus=\"alan=this.name;\" cols=\"50\" rows=\"10\">$daha</textarea><br></div>";
	
	menuyap("baslat");
	echo "<td><h4>kategori nedir?</h4>";
	
	$sql = "select isim from ob_kategori where id = '$kategori'";
	$query = new DB_query($db, $sql);
	$query->db_fetch_object();
	
	$kateisim = $query->obj->isim;

	unset($sql);
	unset($query);
	
	echo "<select name=\"kategori\" class=\"pulldown\" onChange=\"if(this.value=='9'){document.getElementById('filmbu').style.display='inline';}else{document.getElementById('filmbu').style.display='none';document.getElementById('elestirmen').style.display='none';}\">";
	echo "<option value=\"$kategori\">$kateisim</option>";
	echo "<option value=\"\">--------</option>";
	
	$sql = "select * from ob_kategori order by isim asc";
	$query = new DB_query($db, $sql);
	
	while ($query->db_fetch_object())
	{
		$kateid = $query->obj->id;
		$kateisim = $query->obj->isim;
	
		echo "<option value=\"$kateid\">$kateisim</option>";
	}
	
	unset($sql);
	unset($query);

	echo "</select></td>";
	echo "<td width=\"15\"></td>";
	
	if ($haberet == "1")
	{
		$haberet = " checked";
	}
	
	if ($kategori == "9")
	{
		$filmgoster = "inline";
	}
	else
	{
		$filmgoster = "none";
	}
	
	if ($filmbu == "1")
	{
		$filmcheck = "checked";
	}
	
	echo "<td valign=\"top\"><input type=\"checkbox\" name=\"haberet\" value=\"1\"".$haberet."> yorumlari mesajla haber et bana<br>";
	echo "<div id=\"filmbu\" style=\"display:".$filmgoster."\"><input type=\"checkbox\" name=\"filmbu\" value=\"1\" onClick=\"if(this.checked==true){document.getElementById('elestirmen').style.display='inline';}else{document.getElementById('elestirmen').style.display='none';}\"".$filmcheck."> film bu</div></td>";
	menuyap("bitir");
	
	echo "<div id=\"elestirmen\" style=\"display:".$filmgoster."\"><h4>elestirmen modu</h4>";
	echo "<select name=\"yildiz\" class=\"pulldown\">";
	echo "<option value=\"1\">1 yildiz</option>";
	echo "<option value=\"2\">2 yildiz</option>";
	echo "<option value=\"3\">3 yildiz</option>";
	echo "<option value=\"4\">4 yildiz</option>";
	echo "<option value=\"5\">5 yildiz</option>";
	echo "</select></div><br>";

	echo "<input type=\"hidden\" name=\"nedir\" value=\"\"><br>";

	menuyap("baslat");
	menuyap("menu", "hakkin budur", "#", "document.ekle.nedir.value='ekle';document.ekle.submit();");
	menuyap("menu", "hele bi goster bakalim", "#", "document.ekle.nedir.value='goster';document.ekle.submit();");
	menuyap("bitir");

	echo "</form>";
	
	include("right.inc.php");
}
elseif ($nedir == "goster")
{
	if ($id == "")
	{
		return header("location:index.php");
	}
	elseif ($entri == "")
	{
		return header("location:getur.php?hata=1&id=$id");
	}

	$baslik = stripslashes($baslik);
	$entri = stripslashes($entri);
	$daha = stripslashes($daha);
	
	$entri = str_replace("\"", "'", $entri);
	$daha = str_replace("\"", "'", $daha);

	$title = $baslik;
	
	include("left.inc.php");
	
	echo "<h2>$baslik</h2>";
	echo "<form method=\"post\" action=\"degistir.php\" name=\"ekle\">";
	echo "<input type=\"hidden\" name=\"id\" value=\"$id\">";
	echo "<input type=\"hidden\" name=\"baslik\" value=\"$baslik\">";
	echo "<input type=\"hidden\" name=\"entri\" value=\"$entri\">";
	echo "<input type=\"hidden\" name=\"daha\" value=\"$daha\">";
	echo "<input type=\"hidden\" name=\"kategori\" value=\"$kategori\">";
	echo "<input type=\"hidden\" name=\"haberet\" value=\"$haberet\">";
	echo "<input type=\"hidden\" name=\"filmbu\" value=\"$filmbu\">";
	echo "<input type=\"hidden\" name=\"yildiz\" value=\"$yildiz\">";
	
	$entri = encode($entri);
	$entri = decode($entri);
	
	echo "<h4>entri</h4>";
	echo "$entri<br><br>";
	
	if ($daha <> "")
	{
		$daha = encode($daha);
		$daha = decode($daha);
	
		echo "<h4>daha daha</h4>";
		echo "$daha<br><br>";
	}
	
	echo "<h4>kategori</h4>";
	
	$sql = "select isim from ob_kategori where id = '$kategori'";
	$query = new DB_query($db, $sql);
	$query->db_fetch_object();
	
	$kategori = $query->obj->isim;

	unset($sql);
	unset($query);
	
	echo "$kategori<br><br>";
	
	if ($filmbu == "1")
	{
		echo "<h4>elestirmen modu</h4>";
		echo "$yildiz yildiz<br><br>";
	}
	
	if ($haberet == "1")
	{
		echo "yorumlar haber edilecek.<br>";
	}
	
	echo "<input type=\"hidden\" name=\"nedir\" value=\"\"><br>";
	
	menuyap("baslat");
	menuyap("menu", "olmus bu", "#", "document.ekle.nedir.value='ekle';document.ekle.submit();");
	menuyap("menu", "begenmedim geri donelim gayri", "#", "document.ekle.nedir.value='degistir';document.ekle.submit();");
	menuyap("bitir");
	
	echo "</form>";
	
	include("right.inc.php");
}
elseif ($nedir == "degistir")
{
	$baslik = stripslashes($baslik);
	$entri = stripslashes($entri);
	$daha = stripslashes($daha);

	$title = $baslik;
	
	include("left.inc.php");
	
	echo "<h2>$baslik</h2>";
	echo "<form method=\"post\" action=\"degistir.php\" name=\"ekle\">";
	echo "<input type=\"hidden\" name=\"id\" value=\"$id\"><input type=\"hidden\" name=\"baslik\" value=\"$baslik\">";
	echo "<input type=\"hidden\" name=\"bkz\" id=\"bkz\" value=\"0\"><input type=\"hidden\" name=\"gbkz\" id=\"gbkz\" value=\"0\"><input type=\"hidden\" name=\"tez\" id=\"tez\" value=\"0\"><input type=\"hidden\" name=\"url\" id=\"url\" value=\"0\">";

	menuyap("baslat");
	menuyap("menu", "bkz", "#", "kodekle('[bkz]','[/bkz]',alan,'bkz');");
	menuyap("menu", "gbkz", "#", "kodekle('[gbkz]','[/gbkz]',alan,'gbkz');");
	menuyap("menu", "tez", "#", "kodekle('[tez]','[/tez]',alan,'tez');");
	menuyap("menu", "url", "#", "kodekle('[url]','[/url]',alan,'url');");
	menuyap("menu", "resim ekle", "#", "return pencere('dosyaekle.php','dosya',350,400,50,50);");
	menuyap("bitir");
	
	echo "<br><h4>entri</h4>";
	echo "<textarea name=\"entri\" id=\"entri\" onFocus=\"alan=this.name;\" cols=\"50\" rows=\"10\">$entri</textarea><br>";
	echo "<div id=\"dahabaslik\" unselectable=\"on\" style=\"cursor:hand\" onclick=\"if(dahadaha.style.display=='inline'){dahadaha.style.display='none';dahabaslik.innerHTML='<h4>[+] daha daha</h4>';}else{dahadaha.style.display='inline';dahabaslik.innerHTML='<h4>[-] daha daha</h4>';}\"><h4>[+] daha daha</h4></div>";
	
	if ($daha <> "")
	{
		echo "<div id=\"dahadaha\" style=\"display:inline\">";
	}
	else
	{
		echo "<div id=\"dahadaha\" style=\"display:none\">";
	}
	
	echo "<textarea name=\"daha\" id=\"daha\" onFocus=\"alan=this.name;\" cols=\"50\" rows=\"10\">$daha</textarea><br></div>";
	
	menuyap("baslat");
	echo "<td><h4>kategori nedir?</h4>";
	
	$sql = "select isim from ob_kategori where id = '$kategori'";
	$query = new DB_query($db, $sql);
	$query->db_fetch_object();
	
	$kateisim = $query->obj->isim;

	unset($sql);
	unset($query);
	
	echo "<select name=\"kategori\" class=\"pulldown\" onChange=\"if(this.value=='9'){document.getElementById('filmbu').style.display='inline';}else{document.getElementById('filmbu').style.display='none';document.getElementById('elestirmen').style.display='none';}\">";
	echo "<option value=\"$kategori\">$kateisim</option>";
	echo "<option value=\"\">--------</option>";
	
	$sql = "select * from ob_kategori order by isim asc";
	$query = new DB_query($db, $sql);
	
	while ($query->db_fetch_object())
	{
		$kateid = $query->obj->id;
		$kateisim = $query->obj->isim;
	
		echo "<option value=\"$kateid\">$kateisim</option>";
	}
	
	unset($sql);
	unset($query);

	echo "</select></td>";
	echo "<td width=\"15\"></td>";
	
	if ($haberet == "1")
	{
		$haberet = " checked";
	}
	
	if ($filmbu == "1")
	{
		$filmcheck = " checked";
		$filmgoster = "inline";
	}
	else
	{
		$filmgoster = "none";
	}
	
	echo "<td valign=\"top\"><input type=\"checkbox\" name=\"haberet\" value=\"1\"".$haberet."> yorumlari mesajla haber et bana<br>";
	echo "<div id=\"filmbu\" style=\"display:".$filmgoster."\"><input type=\"checkbox\" name=\"filmbu\" value=\"1\" onClick=\"if(this.checked==true){document.getElementById('elestirmen').style.display='inline';}else{document.getElementById('elestirmen').style.display='none';}\"".$filmcheck."> film bu</div></td>";
	menuyap("bitir");
	
	echo "<div id=\"elestirmen\" style=\"display:".$filmgoster."\"><h4>elestirmen modu</h4>";
	echo "<select name=\"yildiz\" class=\"pulldown\">";
	echo "<option value=\"1\">1 yildiz</option>";
	echo "<option value=\"2\">2 yildiz</option>";
	echo "<option value=\"3\">3 yildiz</option>";
	echo "<option value=\"4\">4 yildiz</option>";
	echo "<option value=\"5\">5 yildiz</option>";
	echo "</select></div><br>";

	echo "<input type=\"hidden\" name=\"nedir\" value=\"\"><br>";

	menuyap("baslat");
	menuyap("menu", "hakkin budur", "#", "document.ekle.nedir.value='ekle';document.ekle.submit();");
	menuyap("menu", "hele bi goster bakalim", "#", "document.ekle.nedir.value='goster';document.ekle.submit();");
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
	elseif ($entri == "" || $kategori == "")
	{
		return header("location:degistir.php?hata=1&id=$id");
	}

	$entri = encode($entri);
	$daha = encode($daha);
	
	if ($haberet == "1")
	{
		$haberet = "1";
	}
	else
	{
		$haberet = "0";
	}
	
	if ($filmbu == "1")
	{
		$filmbu = "1";
	
		$sql = "select yildiz,filmoy from ob_blog where id = '$id'";
		$query = new DB_query($db, $sql);
		$query->db_fetch_object();
		
		$eskiyildiz = $query->obj->yildiz;
		$filmoy = $query->obj->filmoy;
	
		unset($sql);
		unset($query);
		
		$filmoy = $filmoy + 1;
		$yildiz = $eskiyildiz + $yildiz;
	}
	else
	{
		$filmbu = "0";
		$yildiz = "0";
		$filmoy = "0";
	}
	
	$sql = "update ob_blog set icerik = '$entri', daha = '$daha', kategori = '$kategori', haberet = '$haberet', filmbu = '$filmbu', yildiz = '$yildiz', filmoy = '$filmoy' where id = '$id'";
	$query = new DB_query($db, $sql);

	unset($sql);
	unset($query);
	
	return header("location:yorumlar.php?id=$id");
}

?>