<?php

$path = "./";

include("db/db.mysql.php");

if ($loginyazarstatu != "9")
{
	return header("location:index.php");
}

if ($nedir=="")
{
	$title = "admin atraksiyonlari";
	
	include ("left.inc.php");
	
	if ($hata=="1")
	{
		$hata = "<br><div class=\"hata\">olmadi arkadasim, hepsini dolduracan.<br><br></div>";
	}
	elseif ($hata=="2")
	{
		$hata = "<br><div class=\"hata\">bi sifreyi dogru giremedin ya, tebrikler.<br><br></div>";
	}
	elseif ($hata=="3")
	{
		$hata = "<br><div class=\"hata\">adam gibi emayil adresi gir, ugrastirma beni, zibidi!<br><br></div>";
	}
	elseif ($hata=="4")
	{
		$hata = "<br><div class=\"hata\">bu emayil adresi kayitli zaten, ne is?<br><br></div>";
	}
	elseif ($hata=="5")
	{
		$hata = "<br><div class=\"hata\">bu kullanici adini kapmislar coktan, baska bisey deniycen artik.<br><br></div>";
	}
	else
	{
		$hata = "";
	}
	
	$sql = "select * from ob_uyeler where id = '$id'";
	$query = new DB_query($db, $sql);
	$query->db_fetch_object();
	
	$id = $query->obj->id;
	$yazarisim = $query->obj->isim;
	$kullanici = $query->obj->kulisim;
	$email = $query->obj->email;
	$temaid = $query->obj->tema;
	$dedikodu = $query->obj->dedikodu;
	$rumuz = $query->obj->rumuz;
	$statu = $query->obj->statu;
	$incik = $query->obj->incik;
	
	unset($query);
	unset($sql);

	echo "<div align=\"center\">";
	menuyap("baslat");
	menuyap("menu", "uyeler", "admin.php");
	menuyap("menu", "nabiz olcer", "nabizolcer.php");
	menuyap("menu", "sinsi editor", "sinsieditor.php");
	menuyap("bitir");
	echo "</div>";
	
	echo "<h2>admin atraksiyonlari :: uyeler</h2>$hata";
	
	echo "<form method=\"post\" action=\"uyedegistir.php?nedir=degistir\" name=\"degistir\"><input type=\"hidden\" name=\"id\" value=\"$id\">";
	echo "<h4>kullanici adi</h4><input type=\"text\" name=\"kullanici\" size=\"35\" maxlength=\"50\" value=\"$kullanici\"><br><br>";
	echo "<h4>sifre</h4><input type=\"password\" name=\"sifre\" size=\"35\" maxlength=\"50\"><br><br>";
	echo "sifreyi degistirmeyeceksen hic dokunma buralara.<br><br>";
	echo "<h4>sifre (yeniden)</h4><input type=\"password\" name=\"sifreiki\" size=\"35\" maxlength=\"50\"><br><br>";
	echo "<h4>isim</h4><input type=\"text\" name=\"yazarisim\" size=\"35\" maxlength=\"250\" value=\"$yazarisim\"><br><br>";
	echo "<h4>emayil</h4><input type=\"text\" name=\"email\" size=\"35\" maxlength=\"100\" value=\"$email\"><br><br>";
	echo "<h4>goruntu stili</h4><select class=\"pulldown\" name=\"temaform\">";

	$sql = "select isim from ob_temalar where id = '$temaid'";
	$query = new DB_query($db, $sql);
	$query->db_fetch_object();
	
	$temaisim = $query->obj->isim;
	
	unset($query);
	unset($sql);
	
	echo "<option value=\"$temaid\" selected>$temaisim</option><option value=\"yok\">------</option>";
	
	$sql = "select id,isim from ob_temalar order by id";
	$query = new DB_query($db, $sql);
	
	while ($query->db_fetch_object())
	{
		$temaid = $query->obj->id;
		$temaisim = $query->obj->isim;
	
		echo "<option value=\"$temaid\">$temaisim</option>";
	}
	
	unset($query);
	unset($sql);
	
	echo "</select><br><br>";
	
	if ($dedikodu == "1")
	{
		echo "<input type=\"checkbox\" name=\"dedikodu\" value=\"1\" checked> dedikoducu bu yazar<br><br>";
		echo "<h4>rumuz (dedikodu icin)</h4><input type=\"text\" name=\"rumuz\" size=\"35\" maxlength=\"100\" value=\"$rumuz\"><br><br>";
	}
	else
	{
		echo "<input type=\"checkbox\" name=\"dedikodu\" value=\"1\"> dedikoducu bu yazar<br><br>";
		echo "<h4>rumuz (dedikodu icin)</h4><input type=\"text\" name=\"rumuz\" size=\"35\" maxlength=\"100\" value=\"$rumuz\"><br><br>";
	}
	
	if ($incik == "1")
	{
		echo "<input type=\"checkbox\" name=\"incik\" value=\"1\" checked> incik cincik yazari<br><br>";
	}
	else
	{
		echo "<input type=\"checkbox\" name=\"incik\" value=\"1\"> incik cincik yazari<br><br>";
	}
	
	echo "<input type=\"text\" name=\"statu\" value=\"$statu\" size=\"5\"><br><br>";
	
	menuyap("tekbuton", "ortamlara akalim haci", "#", "document.degistir.submit();");
	echo "</form>";
	
	include ("right.inc.php");
}
elseif ($nedir=="degistir")
{
	if ($kullanici=="" || $yazarisim=="" || $email=="" || $temaform=="yok")
	{
		return header("location:kayit.php?nedir=degistirform&hata=1");
	}
	
	if ($sifre <> "")
	{
		$sifredegistir = "1";
		
		if ($sifre != $sifreiki)
		{
			return header("location:uyedegistir.php?nedir=degistirform&hata=2");
		}
	}
	else
	{
		$sifredegistir = "0";
	}
	
	if (!ereg("^[a-zA-Z0-9_\.\-\+]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$", $email))
	{
		return header("location:uyedegistir.php?nedir=degistirform&hata=3");
	}
	
	$sql = "select id from ob_uyeler where email = '$email'";
	$query = new DB_query($db, $sql);
	$totmail = $query->db_num_rows();
	
	if ($totmail=="2")
	{
		return header("location:uyedegistir.php?nedir=degistirform&hata=4");
	}
	
	unset($sql);
	unset($query);
	
	$sql = "select id from ob_uyeler where kulisim = '$kullanici'";
	$query = new DB_query($db, $sql);
	$totkul = $query->db_num_rows();
	
	if ($totkul=="2")
	{
		return header("location:uyedegistir.php?nedir=degistirform&hata=5");
	}
	
	unset($sql);
	unset($query);
	
	if ($dedikodu == "1")
	{
		$dedikodu = "1";
	}
	else
	{
		$dedikodu = "0";
	}
	
	if ($incik == "1")
	{
		$incik = "1";
	}
	else
	{
		$incik = "0";
	}
	
	if ($sifredegistir=="1")
	{
		$sql = "update ob_uyeler set isim = '$yazarisim', kulisim = '$kullanici', sifre = '".md5($sifre)."', email = '$email', tema = '$temaform', rumuz = '$rumuz', dedikodu = '$dedikodu', incik = '$incik', statu = '$statu' where id = '$id'";
	}
	else
	{
		$sql = "update ob_uyeler set isim = '$yazarisim', kulisim = '$kullanici', email = '$email', tema = '$temaform', rumuz = '$rumuz', dedikodu = '$dedikodu', incik = '$incik', statu = '$statu' where id = '$id'";
		
		$sifre = "<i>degismedi</i>";
	}
	
	$query = new DB_query($db, $sql);
	
	unset($query);
	unset($sql);
	
	$title = "ince ayar fasilitesi";
	
	include ("left.inc.php");
	
	$sql = "select isim from ob_temalar where id = '$temaform'";
	$query = new DB_query($db, $sql);
	$query->db_fetch_object();
	
	$temaisim = $query->obj->isim;
	
	unset($query);
	unset($sql);
	
	echo "<div align=\"center\">";
	menuyap("baslat");
	menuyap("menu", "uyeler", "admin.php");
	menuyap("menu", "nabiz olcer", "nabizolcer.php");
	menuyap("menu", "sinsi editor", "sinsieditor.php");
	menuyap("bitir");
	echo "</div>";
	
	echo "<h2>admin atraksiyonlari :: uyeler</h2>bilgiler guncellendi, hadi bakalim.<br><br>";
	echo "<h4>kullanici adi</h4>$kullanici<br><br>";
	echo "<h4>sifre</h4>$sifre<br><br>";
	echo "<h4>isim</h4>$yazarisim<br><br>";
	echo "<h4>emayil</h4>$email<br><br>";
	echo "<h4>goruntu stili</h4>$temaisim<br><br>";
	echo "<h4>dedikoducu</h4>$dedikodu<br><br>";
	
	if ($rumuz <> "")
	{
		echo "<h4>rumuz</h4>$rumuz<br><br>";
	}
	
	echo "<h4>incik cincik</h4>$incik<br><br>";
	echo "<h4>statu</h4>$statu";
	
	include ("right.inc.php");
}

?>