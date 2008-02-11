<?php

$path = "./";

include ("db/db.mysql.php");

if ($nedir=="")
{
	$nedir = "form";
}

if ($nedir=="form")
{
	$title = "antre";

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
		$hata = "<br><div class=\"tamam\">sifren emayil adresine gonderildi beyim.<br><br></div>";
	}
	elseif ($hata=="4")
	{
		$hata = "<br><div class=\"hata\">bu bolume girebilmek icin uye olman gerekiyor once, yok oyle yagma.<br><br></div>";
	
		if ($hedef=="")
		{
			$hedef = "index.php";
		}
	}
	elseif ($hata=="5")
	{
		$hata = "<br><div class=\"hata\">uyeligin onaylanmamis henuz.<br><br></div>";
	}
	elseif ($hata=="6")
	{
		$hata = "<br><div class=\"tamam\">emayil adresin onaylandi, kullanici ismi ve sifreni girerek orangeblog ortamlarina dalabilirsin.<br><br></div>";
	}
	elseif ($hata=="7")
	{
		$hata = "<br><div class=\"hata\">girdigin kullanici kodu hatali, bir yerlerde bir hata yaptin. emayil adresine gelen mesaji dikkatle oku, dogru baglantiya tikla, adam ol.<br><br></div>";
	}
	else
	{
		$hata = "";
	}
	
	echo "<h2>antre</h2>$hata";
	echo "<form method=\"post\" action=\"giris.php?nedir=giris\" name=\"giris\">";
	
	if ($hedef<>"")
	{
		echo "<input type=\"hidden\" name=\"hedef\" value=\"$hedef\">";
	}
	
	echo "<h4>kullanici ismi</h4><input type=\"text\" name=\"kullanici\" size=\"35\" maxlength=\"50\"><br><br>";
	echo "<h4>sifre</h4><input type=\"password\" name=\"sifre\" size=\"35\" maxlength=\"50\"><br><br>";
	menuyap("baslat");
	menuyap("menu", "ortamlara akalim haci", "#", "document.giris.submit();");
	menuyap("menu", "unutkan bir karakterim var", "giris.php?nedir=unutkanform");
	menuyap("bitir");
	echo "</form>";

	include ("right.inc.php");
}
elseif ($nedir=="giris")
{
	if ($kullanici=="" || $sifre=="")
	{
		return header("location:giris.php?nedir=form&hata=1");
	}
	
	$sql = "select kulkod,isim,statu from ob_uyeler where kulisim = '$kullanici' and sifre = '".md5($sifre)."'";
	$query = new DB_query($db, $sql);
	$total = $query->db_num_rows();
	$query->db_fetch_object();
	
	$kulkod = $query->obj->kulkod;
	$isim = $query->obj->isim;
	$statu = $query->obj->statu;
	
	unset($sql);
	unset($query);
	
	if ($total == "1")
	{
		if ($statu >= "1")
		{
			setcookie("member_name","$isim",time()+24*3600*365,"/");
			setcookie("member_code","$kulkod",time()+24*3600*365,"/");
		
			if ($hedef == "")
			{
				return header("location:index.php");
			}
			else
			{	
				return header("location:$hedef");
			}
		}
		else
		{
			return header("location:giris.php?nedir=form&hata=5");
		}
	}
	else
	{
		return header("location:giris.php?nedir=form&hata=2");
	}
}
if ($nedir=="unutkanform")
{
	$title = "alzheimer modu";
	
	include ("left.inc.php");
	
	if ($hata=="1")
	{
		$hata = "<br><div class=\"hata\">girdiginiz emayil veritabaninda yok, ne is?</div>";
	}
	else
	{
		$hata = "";
	}
	
	echo "<h2>alzheimer modu</h2>$hata";
	echo "<form method=\"post\" action=\"giris.php?nedir=unutkan\" name=\"unutkan\">";
	echo "kayit olurken kullandiginiz emayil adresini girin, sifrenizi adresinize gondericem ben.<br><br>";
	echo "<h4>emayil adresi</h4><input type=\"text\" name=\"email\" size=\"35\" maxlength=\"50\"><br><br>";
	menuyap("tekbuton", "tamamdir abi", "#", "document.unutkan.submit();");
	echo "</form>";

	include ("right.inc.php");
}
elseif ($nedir=="unutkan")
{
	if ($email=="")
	{
		return header("location:giris.php?nedir=unutkanform&hata=1");
	}
	
	$sql = "select id,kulisim,sifre,isim from ob_uyeler where email = '$email'";
	$query = new DB_query($db, $sql);
	$total = $query->db_num_rows();
	$query->db_fetch_object();
	
	$kulid = $query->obj->id;
	$kullanici = $query->obj->kulisim;
	$sifre = $query->obj->sifre;
	$isim = $query->obj->isim;
	
	unset($sql);
	unset($query);
	
	$len= "10";
	$genepool = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $sifre = "";

    for ($i=0; $i < $len; $i++) 
    {    
        mt_srand((double) microtime() * 1000000);
        $random = (mt_rand(0,strlen($genepool))); 
        $sifre .= substr($genepool, $random,1); 
    }

	$sql = "update ob_uyeler set sifre = '".md5($sifre)."' where id = '$kulid'";
	$query = new DB_query($db, $sql);
	
	unset($sql);
	unset($query);
	
	if ($total=="1")
	{
		$msg = "$isim, \r\n";
		$msg .= "al bakalim yeni sifren ve kullanici ismin asagida, ugrastirma beni boyle seylerle bi daha. sifreni ince ayar bolumunden istedigin bir seye ayarlayabilirsin. \r\n\r\n";
		$msg .= "kullanici adi: $kullanici \r\n";
		$msg .= "sifre: $sifre\r\n\r\n";
		$msg .= "orangeblog ihtiyar heyeti \r\n";
		$msg .= "http://blog.orangeslices.net";
		
		$sub = "orangeblog sifreniz";
		
		mail($email, $sub, $msg, "From: taylan@orangeslices.net");
		
		return header("location:giris.php?nedir=form&hata=3");
	}
	else
	{
		return header("location:giris.php?nedir=unutkanform&hata=1");
	}
}
elseif ($nedir=="cik")
{
	setcookie("member_name","",time()-60,"/");
	setcookie("member_code","",time()-60,"/");
	
	return header("location:index.php");
}

?>