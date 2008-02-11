<?php

$path = "./";

include ("db/db.mysql.php");

if (isset($HTTP_COOKIE_VARS['member_code']))
{
	if ($nedir=="")
	{
		if ($baslik <> "")
		{
			$baslik = stripslashes($baslik);
		
			if ($sil == "1")
			{
				$mesajyaz = "\[bkz\]$baslik\[/bkz\] baslikli entrimi sil ey yuce admin kisisi!";
				$mesajyaz = stripslashes($mesajyaz);
			}
			else
			{
				$mesajyaz = "\[bkz\]$baslik\[/bkz\]";
				$mesajyaz = stripslashes($mesajyaz);
			}
		}
		else
		{
			$mesajyaz = "";
		}
	
		$s = "select isim from ob_uyeler where id = '$kime'";
		$q = new DB_query($db, $s);
		$q->db_fetch_object();
		
		$isim = $q->obj->isim;
		
		unset($s);
		unset($q);
		
		$goster .="<form method=\"post\" action=\"mesajgonder.php?nedir=gonder\" id=\"mesajgonder\" name=\"mesajgonder\">";
		
		if ($hata=="1")
		{
			$goster .="<div class=\"hata\">butun alanlari doldur, ugrastirma beni.</div>";
		}
		
		$goster .= "<input type=\"hidden\" name=\"kimden\" value=\"$loginyazarid\"><input type=\"hidden\" name=\"ori\" value=\"$ori\">";
		$goster .= "<h4>kime?</h4>";
		$goster .= "<input type=\"text\" name=\"kimeisim\" size=\"25\" class=\"pulldown\" onKeyUp=\"javascript:obj1.bldUpdate();\" value=\"$isim\"><br>";
		$goster .= "gonderilecek arkadasin adinin ilk harflerini<br>yazarsan asagida cikacaktir zaten, oradan seciver.<br><br>";
		$goster .= "<select name=\"kime\" class=\"pulldown\" size=\"5\" onChange=\"javascript:document.mesajgonder.kimeisim.value=this.options[selectedIndex].text;document.mesajgonder.mesaj.focus();\">";
		
		$s = "select id,isim from ob_uyeler order by isim asc";
		$q = new DB_query($db, $s);
		
		while ($q->db_fetch_object())
		{
			$yazarid = $q->obj->id;
			$yazarisim = $q->obj->isim;
			
			$goster .= "<option value=\"$yazarid\">$yazarisim</option>";
		}
		
		unset($s);
		unset($q);
		
		$goster .= "</select>";
		$goster .= "<br><br><input type=\"hidden\" name=\"bkz\" id=\"bkz\" value=\"0\"><input type=\"hidden\" name=\"gbkz\" id=\"gbkz\" value=\"0\"><input type=\"hidden\" name=\"tez\" id=\"tez\" value=\"0\"><input type=\"hidden\" name=\"url\" id=\"url\" value=\"0\">";
		
		$goster .= menuyap_return("baslat");
		$goster .= menuyap_return("menu", "bkz", "#", "kodekle('[bkz]','[/bkz]','mesaj','bkz');");
		$goster .= menuyap_return("menu", "gbkz", "#", "kodekle('[gbkz]','[/gbkz]','mesaj','gbkz');");
		$goster .= menuyap_return("menu", "tez", "#", "kodekle('[tez]','[/tez]','mesaj','tez');");
		$goster .= menuyap_return("menu", "url", "#", "kodekle('[url]','[/url]','mesaj','url');");
		$goster .= menuyap_return("bitir");
		
		$goster .= "<br><h4>mesaj</h4><textarea name=\"mesaj\" id=\"mesaj\" cols=\"35\" rows=\"10\">$mesajyaz</textarea>";
		
		$goster .= menuyap_return("tekbuton", "yolla gitsin", "#", "document.mesajgonder.submit();");
		
		$goster .= "</form>";
	}
	elseif ($nedir=="gonder")
	{
		if ($mesaj == "" || $kimden == "")
		{
			return header("location:mesajgonder.php?hata=1");
		}
		
		if ($kime=="" && $kimeisim<>"")
		{
			$sql = "select id from ob_uyeler where isim = '$kimeisim'";
			$query = new DB_query($db, $sql);
			$uyan_uye = $query->db_num_rows();
			
			unset($sql);
			unset($query);
			
			if ($uyan_uye=="1")
			{
				$sql = "select id from ob_uyeler where isim = '$kimeisim'";
				$query = new DB_query($db, $sql);
				$query->db_fetch_object();
				
				$kime = $query->obj->id;
				
				unset($sql);
				unset($query);
			}
			else
			{
				return header("location:mesajgonder.php?hata=1");
			}
		}
		elseif ($kime=="" && $kimeisim=="")
		{
			return header("location:mesajgonder.php?hata=1");
		}
		
		$sql = "select id from ob_mesajlar where kime = '$kime' and (durum = 'r' or durum = 'u')";
		$query = new DB_query($db, $sql);
		$total_r = $query->db_num_rows();
		
		unset($sql);
		unset($query);
		
		if ($total_r >= "20")
		{
			$sql = "select id from ob_mesajlar where kime = '$kime' and (durum = 'r' or durum = 'u') order by id asc limit 1";
			$query = new DB_query($db, $sql);
			$query->db_fetch_object();
			
			$lastid = $query->obj->id;
			
			unset($sql);
			unset($query);
		
			$s = "delete from ob_mesajlar where id = '$lastid'";
			$q = new DB_query($db, $s);
			
			unset($s);
			unset($q);
		}
		
		$mesaj = encode($mesaj);
		
		$sql = "insert into ob_mesajlar (kimden, kime, tarih, mesaj, durum) values ('$kimden', '$kime', '$simdi', '$mesaj', 'u')";
		$query = new DB_query($db, $sql);
		
		unset($sql);
		unset($query);
		unset($lastid);
		
		$sql = "select id from ob_mesajlar where kimden = '$kimden' and durum = 's'";
		$query = new DB_query($db, $sql);
		$total_s = $query->db_num_rows();
		
		unset($sql);
		unset($query);
		
		if ($total_s >= "20")
		{
			$sql = "select id from ob_mesajlar where kimden = '$kimden' and durum = 's' order by id asc limit 1";
			$query = new DB_query($db, $sql);
			$query->db_fetch_object();
			
			$lastid = $query->obj->id;
			
			unset($sql);
			unset($query);
		
			$s = "delete from ob_mesajlar where id = '$lastid'";
			$q = new DB_query($db, $s);
			
			unset($s);
			unset($q);
		}
		
		$sql = "insert into ob_mesajlar (kimden, kime, tarih, mesaj, durum) values ('$kimden', '$kime', '$simdi', '$mesaj', 's')";
		$query = new DB_query($db, $sql);
		
		unset($sql);
		unset($query);
		
		$s = "select isim from ob_uyeler where id = '$kime'";
		$q = new DB_query($db, $s);
		$q->db_fetch_object();
		
		$isim = $q->obj->isim;
		
		unset($s);
		unset($q);
		
		$mesaj = stripslashes($mesaj);
		$mesaj = decode($mesaj);
		
		$goster .="<div class=\"tamam\">mesaji yolladim, aninda goruntu.</div><br>";
		$goster .="<h4>kime</h4>$isim<br>";
		$goster .="<h4>mesaj</h4>$mesaj<br>";
	}
}
else
{
	$goster .="<div class=\"hata\">bu alana girebilmek icin orangeblog uyesi olmaniz gerekiyor, evet oyle.</div>";
}

$title = "mesaj gonder";

?>

<html>
<head>
<title>orangeblog :: <?php echo $title; ?></title>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=ISO-8859-9"> 
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1254">
<link rel="icon" href="<?php echo $path; ?>images/orange_icon.gif">
<script language="JavaScript" src="<?php echo $path; ?>kodaman.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $path."tema/".$tema; ?>.css">
</head>

<body onLoad="javascript:setUp();obj1.bldUpdate();document.mesajgonder.mesaj.focus();">

<?php

echo "<table border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\"><tr>";
echo "<td width=\"150\"><h2>mesaj gonder</h2></td>";
echo "<td valign=\"middle\" align=\"right\">";

menuyap("tekbuton", "kapa beni", "#", "window.close();");

echo "</td></tr></table>";
?>

<table bgcolor="#C26500" cellpadding="0" cellspacing="1" border="0" width="100%">
<tr><td bgcolor="#FFB956" valign="top">
<table bgcolor="#FFB956" cellpadding="4" cellspacing="8" border="0" width="100%">
<tr><td>

<?php

echo $goster;

?>

</td></tr></table>
</td></tr></table>

</body>
</html>