<?php

include ("db/db.mysql.php");

if ($nedir=="")
{
	$nedir = "form";
}

if ($nedir=="form")
{
	$title = "kayit ortamlari";
	
	include ("left.inc.php");

	echo "<h2>kayit ortamlari</h2>";

	echo "orangeblog'a artik kayit almamaktayiz. aslinda icerisi oldukca bos, ancak biz bize olmanin daha uygun olacagina karar verdik. tabi 'beni de alin lan, ben de sizden biriyim' gibi bir ruh halindeyseniz bana <a href=\"mailto:taylan@orangeslices.net\">mail atarak</a> yalvarabilirsiniz. hadi bakalim.";

	include ("right.inc.php");
}
elseif ($nedir=="kayit")
{
	if ($kullanici=="" || $sifre=="" || $sifreiki=="" || $yazarisim=="" || $email=="")
	{
		return header("location:kayit.php?nedir=form&hata=1");
	}
	
	if ($sifre!=$sifreiki)
	{
		return header("location:kayit.php?nedir=form&hata=2");
	}

	if (!ereg("^[a-zA-Z0-9_\.\-\+]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$", $email))
	{
		return header("location:kayit.php?nedir=form&hata=3");
	}
	
	$sql = "select id from ob_uyeler where email = '$email'";
	$query = new DB_query($db, $sql);
	$totmail = $query->db_num_rows();
	
	if ($totmail=="1")
	{
		return header("location:kayit.php?nedir=form&hata=4");
	}
	
	unset($sql);
	unset($query);
	
	$sql = "select id from ob_uyeler where kulisim = '$kullanici'";

	$query = new DB_query($db, $sql);
	$totkul = $query->db_num_rows();
	
	if ($totkul=="1")
	{
		return header("location:kayit.php?nedir=form&hata=5");
	}

	unset($sql);
	unset($query);

	mt_srand((double) microtime() * 1000000);
	$kulkod = uniqid (mt_rand());
	
	$query = new DB_query($db, "select count(*) as count from ob_uyeler where kulkod = '$kulkod'");     
	$query->db_fetch_object();
	
	if ($query->obj->count > 0) 
	{
		sleep(1);
		$kulkod = uniqid (mt_rand());
	}
	
	unset($query);
	
	$sql = "insert into ob_uyeler (isim,kulisim,sifre,email,statu,kulkod,tema,songiris,katilimtarih) values ('$yazarisim','$kullanici','".md5($sifre)."','$email','0','$kulkod','$temaform','$simdi','$simdi')";
	$query = new DB_query($db, $sql);
	
	unset($query);
	unset($sql);
	
	$msg = "$yazarisim, \r\n";
	$msg .= "orangeblog ortamlarina hosgeldiniz efendim, ama oyle kayit olmakla olmuyor bu isler, oncelikle bu emayil adresinin dogrulugunu kanitlamaniz gerekmekte. \r\n";
	$msg .= "nasil yaparim dediginizi duyar gibiyim, hemencecik asagidaki baglanti seysine tiklayin o halledecek isinizi. \r\n\r\n";
	$msg .= "http://blog.orangeslices.net/onayla.php?kod=$kulkod \r\n\r\n";
	$msg .= "ola ki saklamak isterseniz, aha kullanici ismi ve sifreniz de asagida (bakiniz nasil da dusunceliyim modu). \r\n\r\n";
	$msg .= "kullanici adi: $kullanici \r\n";
	$msg .= "sifre: $sifre\r\n\r\n";
	$msg .= "orangeblog'da su anda yeni uye konumundasiniz, hemen entri girecem, yorum yazacam, ortamlara yilan gibi kivrilacam diyorsaniz oncelikle kendinizi tanitan bir aciklama yazisini bloga girmeniz gerekiyor. bu yazi yuce adminler tarafindan okunup onaylandiktan sonra entri ve yorum girmeye baslayabilirsiniz. \r\n\r\n";
	$msg .= "orangeblog ihtiyar heyeti \r\n";
	$msg .= "http://blog.orangeslices.net";
	
	$sub = "orangeblog uyelik hedesi";
	
	mail($email, $sub, $msg, "From: taylan@orangeslices.net");
	
	$sql = "select isim from ob_temalar where id = '$tema'";
	$query = new DB_query($db, $sql);
	$query->db_fetch_object();
	
	$temaisim = $query->obj->isim;
	
	unset($query);
	unset($sql);
	
	$title = "kayit ortamlari";
	
	include ("left.inc.php");
	
	echo "<h2>kayit ortamlari</h2>kayit islemini tamamladim, kayit formunda girdigin emayil adresine gelen mesaji oku, uyeligin onaylanacak.<br><br>";
	echo "<h4>kullanici adi</h4>$kullanici<br><br>";
	echo "<h4>sifre</h4>$sifre<br><br>";
	echo "<h4></h4><br><br>";
	echo "<h4>isim</h4>$yazarisim<br><br>";
	echo "<h4>emayil</h4>$email<br><br>";
	echo "<h4>goruntu stili</h4>$temaisim";
	
	include ("right.inc.php");
}
elseif ($nedir=="degistirform")
{
	if (isset($HTTP_COOKIE_VARS['member_code']))
	{
		$kulkod = ($HTTP_COOKIE_VARS['member_code']);
	}
	else
	{
		return header("location:giris.php?nedir=form&hata=4&hedef=index.php");
	}
	
	$title = "ince ayar fasilitesi";
	
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
	
	$sql = "select * from ob_uyeler where kulkod = '$kulkod'";
	$query = new DB_query($db, $sql);
	$query->db_fetch_object();
	
	$id = $query->obj->id;
	$yazarisim = $query->obj->isim;
	$kullanici = $query->obj->kulisim;
	$email = $query->obj->email;
	$temaid = $query->obj->tema;
	$dedikodu = $query->obj->dedikodu;
	$rumuz = $query->obj->rumuz;
	$dogumtarih = $query->obj->dogumtarih;
	
	unset($query);
	unset($sql);

	echo "<h2>ince ayar fasilitesi</h2>$hata";
	echo "<form method=\"post\" action=\"kayit.php?nedir=degistir\" name=\"degistir\"><input type=\"hidden\" name=\"id\" value=\"$id\">";
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
		echo "<h4>rumuz (dedikodu icin)</h4><input type=\"text\" name=\"rumuz\" size=\"35\" maxlength=\"100\" value=\"$rumuz\"><br><br>";
	else
		echo "<input type=\"hidden\" name=\"rumuz\" value=\"\"><br><br>";
	
	$dogumtarih_gun = ($dogumtarih <> "") ? date("j", $dogumtarih) : null;
	$dogumtarih_ay = ($dogumtarih <> "") ? date("m", $dogumtarih) : null;
	$dogumtarih_yil = ($dogumtarih <> "") ? date("Y", $dogumtarih) : null;
	
	echo "<h4>dogum tarihi</h4>";
	echo "<select name=\"dogumtarih_gun\" class=\"pulldown\">";
	
	if ($dogumtarih_gun == null) echo "<option value=\"0\" selected>--</option>";
	
	for ($i = 1; $i <= 31; $i++)
	{
		if ($i == $dogumtarih_gun)
			echo "<option value=\"$i\" selected>$i</option>";
		else
			echo "<option value=\"$i\">$i</option>";
	}
	
	echo "</select> ";
	echo "<select name=\"dogumtarih_ay\" class=\"pulldown\">";
	
	if ($dogumtarih_ay == null) echo "<option value=\"0\" selected>--</option>";
	
	for ($i = 1; $i <= 12; $i++)
	{
		if ($i == $dogumtarih_ay)
			echo "<option value=\"$i\" selected>$i</option>";
		else
			echo "<option value=\"$i\">$i</option>";
	}
	
	echo "</select> ";
	echo "<select name=\"dogumtarih_yil\" class=\"pulldown\">";
	
	if ($dogumtarih_yil == null) echo "<option value=\"0\" selected>----</option>";
	
	for ($i = (date("Y", time()) - 12); $i >= (date("Y", time()) - 60); $i--)
	{
		if ($i == $dogumtarih_yil)
			echo "<option value=\"$i\" selected>$i</option>";
		else
			echo "<option value=\"$i\">$i</option>";
	}
	
	echo "</select>";
	echo "<br><br>";
	
	menuyap("tekbuton", "ortamlara akalim haci", "#", "document.degistir.submit();");
	echo "</form>";
	
	include ("right.inc.php");
}
elseif ($nedir=="degistir")
{
	$db = new DB();
	
	if ($kullanici=="" || $yazarisim=="" || $email=="" || $temaform=="yok")
	{
		return header("location:kayit.php?nedir=degistirform&hata=1");
	}
	
	if ($sifre <> "")
	{
		$sifredegistir = "1";
		
		if ($sifre != $sifreiki)
		{
			return header("location:kayit.php?nedir=degistirform&hata=2");
		}
	}
	else
	{
		$sifredegistir = "0";
	}
	
	if (!ereg("^[a-zA-Z0-9_\.\-\+]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$", $email))
	{
		return header("location:kayit.php?nedir=degistirform&hata=3");
	}
	
	$sql = "select id from ob_uyeler where email = '$email'";
	$query = new DB_query($db, $sql);
	$totmail = $query->db_num_rows();
	
	if ($totmail=="2")
	{
		return header("location:kayit.php?nedir=degistirform&hata=4");
	}
	
	unset($sql);
	unset($query);
	
	$sql = "select id from ob_uyeler where kulisim = '$kullanici'";
	$query = new DB_query($db, $sql);
	$totkul = $query->db_num_rows();
	
	if ($totkul=="2")
	{
		return header("location:kayit.php?nedir=degistirform&hata=5");
	}
	
	unset($sql);
	unset($query);
	
	$dogumtarih = ($dogumtarih_gun > 0 && $dogumtarih_ay > 0 && $dogumtarih_yil > 0) ? "'".mktime(1, 1, 0, $dogumtarih_ay, $dogumtarih_gun, $dogumtarih_yil)."'" : "NULL";
	
	if ($sifredegistir=="1")
	{
		$sql = "update ob_uyeler set isim = '$yazarisim', kulisim = '$kullanici', sifre = '".md5($sifre)."', email = '$email', tema = '$temaform', rumuz = '$rumuz', dogumtarih = $dogumtarih where id = '$id'";
	}
	else
	{
		$sql = "update ob_uyeler set isim = '$yazarisim', kulisim = '$kullanici', email = '$email', tema = '$temaform', rumuz = '$rumuz', dogumtarih = $dogumtarih where id = '$id'";
		
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
	
	echo "<h2>ince ayar fasilitesi</h2>bilgiler guncellendi, hadi bakalim.<br><br>";
	echo "<h4>kullanici adi</h4>$kullanici<br><br>";
	echo "<h4>sifre</h4>$sifre<br><br>";
	echo "<h4>isim</h4>$yazarisim<br><br>";
	echo "<h4>emayil</h4>$email<br><br>";
	echo "<h4>goruntu stili</h4>$temaisim<br><br>";
	
	if ($dogumtarih != "NULL")
		echo "<h4>dogum tarihi</h4>".date("d.m.Y", substr($dogumtarih, 1, -1))."<br><br>";
	
	if ($rumuz <> "")
	{
		echo "<h4>rumuz</h4>$rumuz";
	}
	
	include ("right.inc.php");
}

?>