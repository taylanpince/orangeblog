<?

$path = "./";

include ("db/db.mysql.php");

if (!isset($HTTP_COOKIE_VARS['member_code']))
{
	return header("location:giris.php?nedir=form&hata=4&hedef=mesajlar.php");
}

if ($nedir=="")
{
	$nedir = "normal";
}

if ($sira=="" || $sira=="desc")
{
	$sira = "desc";
	$yenisira = "asc";
}
else
{
	$sira = "asc";
	$yenisira = "desc";
}

if ($nedir=="normal")
{
	$ori = "r";
	
	$goster = "<h2>mesaj fasilitesi :: gelen kutusu</h2>";
	
	$sql = "select * from ob_mesajlar where kime = '$loginyazarid' and (durum = 'u' or durum = 'r') order by id $sira";
	$query = new DB_query($db, $sql);
	$toplam = $query->db_num_rows();
	
	if ($toplam >= "1")
	{
		while($query->db_fetch_object())
		{
			$id = $query->obj->id;
			$kimden = $query->obj->kimden;
			$zaman = $query->obj->tarih;
			$mesaj = $query->obj->mesaj;
			$durum = $query->obj->durum;
			$tarih = date("d.m.Y | G:i", $zaman);
			
			$s = "select isim from ob_uyeler where id = '$kimden'";
			$q = new DB_query($db, $s);
			$q->db_fetch_object();
			
			$isim = $q->obj->isim;
			
			unset($s);
			unset($q);
			
			if ($durum=="u")
			{
				$s = "update ob_mesajlar set durum = 'r' where id = '$id'";
				$q = new DB_query($db, $s);
				
				unset($s);
				unset($q);
			}
			
			$mesaj = decode($mesaj);
			
			$goster .= "<div class=\"weblog\" onmouseover=\"document.getElementById('$id').style.visibility='visible'\" onmouseout=\"document.getElementById('$id').style.visibility='hidden'\"><h4>$isim:</h4>$mesaj<br>";
			$goster .= "<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr>";
			$goster .= "<td style=\"visibility:hidden\" id=\"$id\">";
			
			$goster .= menuyap_return("baslat");
			$goster .= menuyap_return("menu", "sil gitsin", "#", "javascript:if(confirm('Bu mesajı silmek istediğinizden emin misiniz?')){location.href='mesajlar.php?nedir=sil&mesid=$id&ori=r'};return false;");
			$goster .= menuyap_return("menu", "arsivle", "mesajlar.php?nedir=arsivle&mesid=$id");
			$goster .= menuyap_return("menu", "cevapla", "#", "javascript:document.mesajgonder.kimeisim.value='$isim';obj1.bldUpdate();document.mesajgonder.mesaj.focus();");
			$goster .= menuyap_return("bitir");
			
			$goster .= "</td><td width=\"150\" align=\"right\">$tarih</td></tr></table></div>";
		}
	}
	else
	{
		$goster .= "<div class=\"weblog\">daha hic mesajin yokmus, yazik.</div>";
	}
}
elseif ($nedir=="arsiv")
{
	$ori = "a";
	
	$goster = "<h2>mesaj fasilitesi :: arsiv ortamlari</h2>";

	$sql = "select * from ob_mesajlar where (kime = '$loginyazarid' and durum = 'a') or (kimden = '$loginyazarid' and durum = 'b') order by id $sira";
	$query = new DB_query($db, $sql);
	$toplam = $query->db_num_rows();
	
	if ($toplam >= "1")
	{
		while ($query->db_fetch_object())
		{
			$id = $query->obj->id;
			$kime = $query->obj->kime;
			$kimden = $query->obj->kimden;
			$zaman = $query->obj->tarih;
			$mesaj = $query->obj->mesaj;
			$durum = $query->obj->durum;
			
			$tarih = date("d.m.Y | G:i", $zaman);
			
			$s = "select isim from ob_uyeler where id = '$kimden'";
			$q = new DB_query($db, $s);
			$q->db_fetch_object();
			
			$kimdensig = $q->obj->isim;
			
			unset($s);
			unset($q);
			
			$s = "select isim from ob_uyeler where id = '$kime'";
			$q = new DB_query($db, $s);
			$q->db_fetch_object();
			
			$kimesig = $q->obj->isim;
			
			unset($s);
			unset($q);
			
			$mesaj = decode($mesaj);
			
			$goster .= "<div class=\"weblog\" onmouseover=\"document.getElementById('$id').style.visibility='visible'\" onmouseout=\"document.getElementById('$id').style.visibility='hidden'\"><h4>$kimdensig -> $kimesig</h4>$mesaj<br>";
			$goster .= "<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr>";
			$goster .= "<td style=\"visibility:hidden\" id=\"$id\">";
			
			$goster .= menuyap_return("baslat");
			$goster .= menuyap_return("menu", "sil gitsin", "#", "javascript:if(confirm('Bu mesajı silmek istediğinizden emin misiniz?')){window.location.href='mesajlar.php?nedir=sil&mesid=$id&ori=a&sta=$durum';}return false;");
			
			if ($durum=="a")
			{
				$goster .= menuyap_return("menu", "cevapla", "#", "javascript:document.mesajgonder.kimeisim.value='$kimdensig';obj1.bldUpdate();document.mesajgonder.mesaj.focus();");
			}
			
			$goster .= menuyap_return("bitir");
			
			$goster .= "</td><td width=\"150\" align=\"right\">$tarih</td></tr></table></div>";
		}
	}
	else
	{
		$goster .= "<div class=\"weblog\">daha hic mesajin yokmus, yazik.</div>";
	}
}
elseif ($nedir=="giden")
{
	$ori = "s";
	
	$goster = "<h2>mesaj fasilitesi :: giden mesajlar</h2>";

	$alter01 = "yazikoyusira";
	$alter02 = "yaziaciksira";
	$row_count = 0;

	$sql = "select * from ob_mesajlar where kimden = '$loginyazarid' and durum = 's' order by id $sira";
	$query = new DB_query($db, $sql);
	$toplam = $query->db_num_rows();
	
	if ($toplam >= "1")
	{
		while($query->db_fetch_object())
		{
			$id = $query->obj->id;
			$kime = $query->obj->kime;
			$zaman = $query->obj->tarih;
			$mesaj = $query->obj->mesaj;
			
			$tarih = date("d.m.Y | G:i", $zaman);
			
			$s = "select isim from ob_uyeler where id = '$kime'";
			$q = new DB_query($db, $s);
			$q->db_fetch_object();
			
			$isim = $q->obj->isim;
			
			unset($s);
			unset($q);
			
			$mesaj = decode($mesaj);
			
			$goster .= "<div class=\"weblog\" onmouseover=\"document.getElementById('$id').style.visibility='visible'\" onmouseout=\"document.getElementById('$id').style.visibility='hidden'\"><h4>-> $isim:</h4>$mesaj<br>";
			$goster .= "<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr>";
			$goster .= "<td style=\"visibility:hidden\" id=\"$id\">";
			
			$goster .= menuyap_return("baslat");
			$goster .= menuyap_return("menu", "sil gitsin", "#", "javascript:if(confirm('Bu mesajı silmek istediğinizden emin misiniz?')){location.href='mesajlar.php?nedir=sil&mesid=$id&ori=s';}return false;");
			$goster .= menuyap_return("menu", "arsivle", "mesajlar.php?nedir=arsivle&mesid=$id&ori=s");
			$goster .= menuyap_return("bitir");

			$goster .= "</td><td width=\"150\" align=\"right\">$tarih</td></tr></table></div>";
		}
	}
	else
	{
		$goster .= "<tr><td class=\"yaziaciksira\">Şu anda giden mesajlar kutunuzda hiç mesaj yok.</td></tr>";
	}
}
elseif ($nedir=="arsivle")
{	
	if ($ori=="s")
	{
		$sta = "b";
	}
	else
	{
		$sta = "a";
	}
	
	$sql = "select id from ob_mesajlar where (kime = '$loginyazarid' and durum = 'a') or (kimden = '$loginyazarid' and durum = 'b')";
	$query = new DB_query($db, $sql);
	$total_a = $query->db_num_rows();
	
	unset($sql);
	unset($query);
	
	if ($total_a >= "40")
	{
		$sql = "select id from ob_mesajlar where (kime = '$loginyazarid' and durum = 'a') or (kimden = '$loginyazarid' and durum = 'b') order by id asc limit 1";
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
	
	if ($sta=="b")
	{
		$s = "update ob_mesajlar set durum = '$sta' where id = '$mesid' and kimden = '$loginyazarid'";
	}
	else
	{
		$s = "update ob_mesajlar set durum = '$sta' where id = '$mesid' and kime = '$loginyazarid'";
	}

	$q = new DB_query($db, $s);
	
	unset($s);
	unset($q);
	
	if ($ori=="a")
	{
		return header("location:mesajlar.php?nedir=arsiv");
	}
	elseif ($ori=="s")
	{
		return header("location:mesajlar.php?nedir=giden");
	}
	else
	{
		return header("location:mesajlar.php?nedir=normal");
	}
}
elseif ($nedir=="sil")
{
	if ($ori=="r")
	{
		$s = "delete from ob_mesajlar where kime = '$loginyazarid' and id = '$mesid'";
	}
	elseif ($ori=="s")
	{
		$s = "delete from ob_mesajlar where kimden = '$loginyazarid' and id = '$mesid'";
	}
	elseif ($ori=="a")
	{
		if ($sta=="a")
		{
			$s = "delete from ob_mesajlar where kime = '$loginyazarid' and id = '$mesid'";
		}
		elseif ($sta=="b")
		{
			$s = "delete from ob_mesajlar where kimden = '$loginyazarid' and id = '$mesid'";
		}
	}

	$q = new DB_query($db, $s);
	unset($s);
	unset($q);
	
	if ($ori=="a")
	{
		return header("location:mesajlar.php?nedir=arsiv");
	}
	elseif ($ori=="s")
	{
		return header("location:mesajlar.php?nedir=giden");
	}
	else
	{
		return header("location:mesajlar.php?nedir=normal");
	}
}
elseif ($nedir=="hepsinisil")
{
	if ($ori=="r")
	{
		$s = "delete from ob_mesajlar where kime = '$loginyazarid' and durum = 'r'";
	}
	elseif ($ori=="s")
	{
		$s = "delete from ob_mesajlar where kimden = '$loginyazarid' and durum = 's'";
	}
	elseif ($ori=="a")
	{
		$s = "delete from ob_mesajlar where (kime = '$loginyazarid' and durum = 'a') or (kimden = '$loginyazarid' and durum = 'b')";
	}
	
	$q = new DB_query($db, $s);
	unset($s);
	unset($q);
	
	if ($ori=="a")
	{
		return header("location:mesajlar.php?nedir=arsiv");
	}
	elseif ($ori=="s")
	{
		return header("location:mesajlar.php?nedir=giden");
	}
	else
	{
		return header("location:mesajlar.php?nedir=normal");
	}
}
elseif ($nedir=="gonder")
{
	if ($mesaj == "" || $kimden == "")
	{
		if ($ori=="a")
		{
			return header("location:mesajlar.php?nedir=arsiv");
		}
		elseif ($ori=="s")
		{
			return header("location:mesajlar.php?nedir=giden");
		}
		else
		{
			return header("location:mesajlar.php?nedir=normal");
		}
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
			if ($ori=="a")
			{
				return header("location:mesajlar.php?nedir=arsiv");
			}
			elseif ($ori=="s")
			{
				return header("location:mesajlar.php?nedir=giden");
			}
			else
			{
				return header("location:mesajlar.php?nedir=normal");
			}
		}
	}
	elseif ($kime=="" && $kimeisim=="")
	{
		if ($ori=="a")
		{
			return header("location:mesajlar.php?nedir=arsiv");
		}
		elseif ($ori=="s")
		{
			return header("location:mesajlar.php?nedir=giden");
		}
		else
		{
			return header("location:mesajlar.php?nedir=normal");
		}
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
	
	$mesaj = encode($mesaj);
	
	$sql = "insert into ob_mesajlar (kimden, kime, tarih, mesaj, durum) values ('$kimden', '$kime', '$simdi', '$mesaj', 's')";
	$query = new DB_query($db, $sql);
	
	unset($sql);
	unset($query);
	
	if ($ori=="a")
	{
		return header("location:mesajlar.php?nedir=arsiv");
	}
	elseif ($ori=="s")
	{
		return header("location:mesajlar.php?nedir=giden");
	}
	else
	{
		return header("location:mesajlar.php?nedir=normal");
	}
}

$mesajSayfa = "1";

$title = "mesaj fasilitesi";

include ("left.inc.php");

echo "<div align=\"center\">";

menuyap("baslat");
menuyap("menu", "gelen kutusu", "mesajlar.php?nedir=normal");
menuyap("menu", "giden mesajlar", "mesajlar.php?nedir=giden");
menuyap("menu", "arsiv ortamlari", "mesajlar.php?nedir=arsiv");
menuyap("bitir");

echo "</div>";

echo $goster;

if ($toplam >= "2")
{
	menuyap("baslat");
	menuyap("menu", "sil gitsin hepsini", "#", "javascript:if(confirm('emin misin bak siliyorum hepsini?')){window.location.href='mesajlar.php?nedir=hepsinisil&ori=$ori';return false;}");
	menuyap("menu", "terslen", "mesajlar.php?nedir=$nedir&sira=$yenisira");
	menuyap("bitir");
}

echo "<h2>mesaj gonder</h2>";

echo "<form method=\"post\" action=\"mesajlar.php?nedir=gonder\" id=\"mesajgonder\" name=\"mesajgonder\">";
echo "<input type=\"hidden\" name=\"kimden\" value=\"$loginyazarid\"><input type=\"hidden\" name=\"ori\" value=\"$ori\">";
echo "<h4>kime?</h4>";
echo "<input type=\"text\" name=\"kimeisim\" size=\"35\" class=\"pulldown\" onKeyUp=\"javascript:obj1.bldUpdate();\"><br>";
echo "gonderilecek arkadasin adinin ilk harflerini<br>yazarsan asagida cikacaktir zaten, oradan seciver.<br><br>";
echo "<select name=\"kime\" class=\"pulldown\" size=\"5\" onChange=\"javascript:document.mesajgonder.kimeisim.value=this.options[selectedIndex].text;document.mesajgonder.mesaj.focus();\">";

$s = "select id,isim from ob_uyeler order by isim asc";
$q = new DB_query($db, $s);

while ($q->db_fetch_object())
{
	$yazarid = $q->obj->id;
	$yazarisim = $q->obj->isim;
	
	echo "<option value=\"$yazarid\">$yazarisim</option>";
}

unset($s);
unset($q);

echo "</select>";
echo "<br><br><input type=\"hidden\" name=\"bkz\" id=\"bkz\" value=\"0\"><input type=\"hidden\" name=\"gbkz\" id=\"gbkz\" value=\"0\"><input type=\"hidden\" name=\"tez\" id=\"tez\" value=\"0\"><input type=\"hidden\" name=\"url\" id=\"url\" value=\"0\">";

menuyap("baslat");
menuyap("menu", "bkz", "#", "kodekle('[bkz]','[/bkz]','mesaj','bkz');");
menuyap("menu", "gbkz", "#", "kodekle('[gbkz]','[/gbkz]','mesaj','gbkz');");
menuyap("menu", "tez", "#", "kodekle('[tez]','[/tez]','mesaj','tez');");
menuyap("menu", "url", "#", "kodekle('[url]','[/url]','mesaj','url');");
menuyap("bitir");

echo "<br><h4>mesaj</h4>";
echo "<textarea name=\"mesaj\" id=\"mesaj\" cols=\"50\" rows=\"10\"></textarea><br><br>";

menuyap("tekbuton", "yolla gitsin", "#", "document.mesajgonder.submit();");

echo "</form>";

include ("right.inc.php");

?>