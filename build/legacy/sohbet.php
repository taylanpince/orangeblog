<?php

include ("db/db.mysql.php");

function word_wrap($mesaj, $maxlen="25", $cut="<br>")
{
	$mesaj = str_replace("\r\n", "", $mesaj);
	$mesaj = str_replace("\n", "", $mesaj);
	$mesaj = str_replace("\r", "", $mesaj);
	$mesaj = str_replace(" [", "[", $mesaj);
	
	$mesajlen = strlen($mesaj);
	$beginline = 0;
	$lastspace = 0;
	$segment = 0;
	$tag = 0;
	$i = 0;
	
	while ($i <= $mesajlen)
	{
		if ($mesaj[$i] == "[")
		{
			$tag++;
		}
		elseif ($mesaj[$i] == "]")
		{
			if ($tag > 0)
			{
				$tag--;
			}
		}
		elseif ($tag == 0)
		{
			$segment++;
		
			if ($segment > $maxlen)
			{
				if ($mesaj[$i] == " ")
				{
					$mesaj = substr($mesaj,0,$i).$cut.substr($mesaj,$i+1,$mesajlen-1);
					$i = $i + 4;
					$mesajlen = strlen($mesaj);
					$segment = 0;
					$beginline = $i;
					$lastspace = $i;
				}
				else
				{
					if ($beginline == $lastspace)
					{
						$i = $beginline + $maxlen - 1;
						$mesaj = substr($mesaj,0,$i+1).$cut.substr($mesaj,$i+1,$mesajlen-1);
					}
					else
					{
						$i = $lastspace;
						$mesaj = substr($mesaj,0,$i).$cut.substr($mesaj,$i+1,$mesajlen-1);
					}
		
					$i = $i + 4;
					$mesajlen = strlen($mesaj);
					$segment = 0;
					$beginline = $i;
					$lastspace = $i;
				}
			}
			else
			{
				if ($mesaj[$i] == " ")






				{
					$lastspace = $i;
				}
			}
		}
		
		$i++;
	}
	
	$mesaj = str_replace("[", " [", $mesaj);
	
	$mesaj = preg_replace("/\[url=http(s?):\/\/(.*?)<br>(.*?)\](.*?)\[\/url\]/i","[url=http\\1://\\2\\3]\\4[/url]",$mesaj);
	$mesaj = preg_replace("/\[url=www.(.*?)<br>(.*?)\](.*?)\[\/url\]/i","[url=\http://www.\\1\\2]\\3[/url]",$mesaj);
	$mesaj = preg_replace("/\[url=http(s?):\/\/(.*?)<br>(.*?)\](.*?)\[\/url\]/i","[url=http\\1://\\2\\3]\\4[/url]",$mesaj);
	$mesaj = preg_replace("/\[url=www.(.*?)<br>(.*?)\](.*?)\[\/url\]/i","[url=\http://www.\\1\\2]\\3[/url]",$mesaj);
	$mesaj = preg_replace("/\[url=http(s?):\/\/(.*?)<br>(.*?)\](.*?)\[\/url\]/i","[url=http\\1://\\2\\3]\\4[/url]",$mesaj);
	$mesaj = preg_replace("/\[url=www.(.*?)<br>(.*?)\](.*?)\[\/url\]/i","[url=\http://www.\\1\\2]\\3[/url]",$mesaj);
	$mesaj = preg_replace("/\[email=(.*?)<br>(.*?)\](.*?)\[\/email\]/i","[email=\\1\\2]\\3[/email]",$mesaj);
	$mesaj = preg_replace("/\[url=(.*?)<br>(.*?)\](.*?)\[\/url\]/i","[url=\\1\\2]\\3[/url]",$mesaj);
	
	return $mesaj;
}

$kaynak = "sohbet.xml";

$TARIH = "";
$ICERIK = "";
$KULLANICI = "";
$KULID = "";

$aktif = "";
$guncel = "";
$sonuc = array();

function startElement($parser,$name,$attr)
{
	$GLOBALS['aktif'] = $name;
}

function endElement($parser,$name)
{
	if ($GLOBALS['aktif'] != "MESAJLAR" || $GLOBALS['aktif'] != "GUNCELLEME")
	{
		$elements = array('TARIH','ICERIK','KULLANICI','KULID');
		
		if (strcmp($name,"MESAJ") == 0)
		{
			foreach ($elements as $element)
			{
				$temp[$element] = $GLOBALS[$element];							
			}
			
			$GLOBALS['sonuc'][] = $temp;
			$GLOBALS['TARIH'] = "";
			$GLOBALS['ICERIK'] = "";
			$GLOBALS['KULLANICI'] = "";
			$GLOBALS['KULID'] = "";
		}
	}
}

function characterData($parser, $data)
{
	if ($GLOBALS['aktif'] == "GUNCELLEME")
	{
		$GLOBALS['guncel'] .= $data;
	}
	elseif ($GLOBALS['aktif'] != "MESAJLAR")
	{
		$elements = array('TARIH','ICERIK','KULLANICI','KULID');
		
		foreach ($elements as $element)
		{
			if ($GLOBALS['aktif'] == $element)
			{
				$GLOBALS[$element] .= $data;
			}
		}
	}
}

function mesajGoster()
{
	global $kaynak, $sonuc;

	$xml_parser=xml_parser_create();
	
	xml_set_element_handler($xml_parser,"startElement","endElement");
	xml_set_character_data_handler($xml_parser,"characterData");
	
	if (!($fp=fopen($kaynak,"r")))
	{
		die("Kaynak dosyasÄ± aÃ§Ä±lamÄ±yor. LÃ¼tfen daha sonra yeniden deneyin.");
	}
	
	while (($data=fread($fp,4096)))
	{	
		if (!xml_parse($xml_parser,$data,feof($fp)))
		{
			die(sprintf("XML hatasÄ±: satÄ±r %d sÃ¼tun %d ", xml_get_current_line_number($xml_parser), xml_get_current_column_number($xml_parser)));
		}
	}
	
	fclose($fp);
	xml_parser_free($xml_parser);
	
	return $sonuc;
}

function mesajEkle($icerik,$kullanici,$kulid)
{
	global $kaynak, $simdi;
	
	if (!($fp=fopen($kaynak, "r+")))
	{
		die("Kaynak dosyasÄ± aÃ§Ä±lamÄ±yor. LÃ¼tfen daha sonra yeniden deneyin.");
	}
	
	fseek($fp, "-11", SEEK_END);
	
	$ek = "<MESAJ>\r\n";
	$ek .= "<TARIH>$simdi</TARIH>\r\n";
	$ek .= "<ICERIK>" . $icerik . "</ICERIK>\r\n";
	$ek .= "<KULLANICI>$kullanici</KULLANICI>\r\n";
	$ek .= "<KULID>$kulid</KULID>\r\n";
	$ek .= "</MESAJ>\r\n";
	$ek .= "</MESAJLAR>";
	
	fwrite($fp, $ek);
	fclose($fp);
}

function mesajSil($tip="0")
{
	global $kaynak;

	if (!($fp=fopen($kaynak, "r+")))
	{
		die("Kaynak dosyasÄ± aÃ§Ä±lamÄ±yor. LÃ¼tfen daha sonra yeniden deneyin.");
	}
	
	rewind($fp);
	ftruncate($fp, 0);
	
	if ($tip == "0")
	{
		$header = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\r\n<MESAJLAR>\r\n</MESAJLAR>";
	
		fwrite($fp, $header);
	}

	fclose($fp);
}

function mesajGuncelle()
{
	global $kaynak, $simdi;
	
	$mesajlar = mesajGoster();
	mesajSil("1");
	
	$uc_gun = 3 * 24 * 60 * 60;
	$eski = $simdi - $uc_gun;
	
	$dosya = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\r\n";
	$dosya .= "<MESAJLAR>\r\n";
	$dosya .= "<GUNCELLEME>$simdi</GUNCELLEME>\r\n";
	
	foreach ($mesajlar as $arr)
	{
		$tarih = $arr['TARIH'];
		$tarih = str_replace("\n", "", $tarih);
		
		if ($tarih >= $eski)
		{
			$icerik = $arr['ICERIK'];
			$kullanici = $arr['KULLANICI'];
			$kulid = $arr['KULID'];
			
			$icerik = str_replace("\n", "", $icerik);
			$icerik = str_replace("&", "&amp;", $icerik);
			$icerik = str_replace("<", "&lt;", $icerik);
			$icerik = str_replace(">", "&gt;", $icerik);
			$kullanici = str_replace("\n", "", $kullanici);
			$kulid = str_replace("\n", "", $kulid);
			
			$dosya .= "<MESAJ>\r\n";
			$dosya .= "<TARIH>$tarih</TARIH>\r\n";
			$dosya .= "<ICERIK>$icerik</ICERIK>\r\n";
			$dosya .= "<KULLANICI>$kullanici</KULLANICI>\r\n";
			$dosya .= "<KULID>$kulid</KULID>\r\n";
			$dosya .= "</MESAJ>\r\n";
		}
	}
	
	$dosya .= "</MESAJLAR>";
	
	if (!($fp=fopen($kaynak, "r+")))
	{
		die("Kaynak dosyasÄ± aÃ§Ä±lamÄ±yor. LÃ¼tfen daha sonra yeniden deneyin.");
	}
	
	fwrite($fp, $dosya);
	fclose($fp);
}

if ($nedir == "")
{
	if ($arsiv=="1")
	{
		$nedir = "arsiv";
	}
	else
	{
		$nedir = "liste";
	}
}
elseif ($nedir == "ekle")
{
	if (isset($HTTP_COOKIE_VARS['member_code']))
	{
		if ($icerik=="")
		{
			if ($arsiv=="1")
			{
				$nedir = "arsiv";
			}
			else
			{
				$nedir = "liste";
			}
		}
		else
		{
			$icerik = sohbet_encode($icerik);
			$icerik = stripslashes($icerik);
			$icerik = str_replace("&", "&amp;", $icerik);
			$icerik = str_replace("<", "&lt;", $icerik);
			$icerik = str_replace(">", "&gt;", $icerik);
			
			mesajEkle($icerik, $kullanici, $kulid);
			
			if ($arsiv=="1")
			{
				$nedir = "arsiv";
			}
			else
			{
				$nedir = "liste";
			}
			
			$yaziyisil = "1";
		}
	}
}
elseif ($nedir == "sil")
{
	$db = new DB();

	$kulkod = $HTTP_COOKIE_VARS['member_code'];
		
	$sql = "select statu from ek_uyeler where kulkod = '$kulkod'";
	$query = new DB_query($db, $sql);
	$query->db_fetch_object();
	
	$loginyazarstatu = $query->obj->statu;
	
	unset($sql);
	unset($query);

	if ($loginyazarstatu >= "8")
	{
		mesajSil();
	}
}
elseif ($nedir == "guncelle")
{
	mesajGuncelle();
	
	return header("location:sohbet.php");
}

$tablo = mesajGoster();

$dun = $simdi - (24 * 60 * 60);

if ($guncel <= $dun)
{
	return header("location:sohbet.php?nedir=guncelle");
}

$alter01 = "sohbetaciksira";
$alter02 = "sohbetkoyusira";
$row_count = 0;

if ($nedir=="liste")
{
	$liste = array_slice($tablo, -40);
	
	foreach ($liste as $arr)
	{
		$zaman = $arr['TARIH'];
		$icerik = $arr['ICERIK'];
		$kullanici = $arr['KULLANICI'];
		
		if ($sohbetpencere != "1")
		{
			$icerik = word_wrap($icerik);
		}
		
		$icerik = decode($icerik, "1");
		
		$row_colour = ($row_count % 2) ? $alter01 : $alter02;
		
		$kullanici = str_replace("\n", "", $kullanici);

		$tarih = date("G:i", $zaman);
	
		$goster .= "<tr><td class=\"$row_colour\"><b>$kullanici ($tarih):</b><br>$icerik</td></tr>";
		
		$row_count++;
	}
}
elseif ($nedir=="arsiv")
{
	if ($arazaman=="birgun")
	{
		$arazaman = 24 * 60 * 60;
	}
	elseif ($arazaman=="ikigun")
	{
		$arazaman = 2 * 24 * 60 * 60;
	}
	elseif ($arazaman=="ucgun")
	{
		$arazaman = 3 * 24 * 60 * 60;
	}
	elseif ($arazaman=="")
	{
		$arazaman = 24 * 60 * 60;
	}
	else
	{
		$arazaman = $arazaman * 60 * 60;
	}
	
	$eskizaman = $simdi - $arazaman;
	
	foreach ($tablo as $arr)
	{
		$zaman = $arr['TARIH'];
		
		if ($zaman >= $eskizaman)
		{
			$icerik = $arr['ICERIK'];
			$kullanici = $arr['KULLANICI'];
			$kulid = $arr['KULID'];
	
			$icerik = decode($icerik, "1");
			$tarih = date("d.m.Y | G:i", $zaman);
		
			$row_colour = ($row_count % 2) ? $alter01 : $alter02;
			
			$kulid = str_replace("\n", "", $kulid);
			$kullanici = str_replace("\n", "", $kullanici);
			
			$goster .= "<tr><td class=\"$row_colour\"><b><a href=\"#\" onClick=\"javascript:return pencere('mesajgonder.php?kime=$kulid','mesaj',600,475,50,50);\">$kullanici</a> ($tarih):</b><br>$icerik</td></tr>";
			
			$row_count++;
		}
	}
}

?>

<html>
<head>

<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8"> 

<link href="tema/<?php echo $tema; ?>_sohbet.css" rel="stylesheet" type="text/css">

<?php

if ($sohbetpencere=="1")
{
	echo "<META HTTP-EQUIV=\"refresh\" CONTENT=\"120;URL=sohbet.php?sohbetpencere=1\">";
}
else
{
	echo "<META HTTP-EQUIV=\"refresh\" CONTENT=\"120;URL=sohbet.php\">";
}

echo "<script language=\"javascript\">";

if ($yaziyisil=="1")
{
	echo "
	onload = function()
	{
		// correct for scrollbar in IE in iframe
		if (navigator.userAgent.toLowerCase().indexOf('msie') != -1)
		{
			document.body.style.width = (document.body.clientWidth - 20) + 'px';
		}
		
		// scroll to bottom
		window.scrollTo(0, 1000000);
		
		// delete text field value
		self.parent.document.sohbet.icerik.value = '';
	}";
}
elseif ($arsiv=="1")
{
	echo "
	onload = function()
	{
		// correct for scrollbar in IE in iframe
		if (navigator.userAgent.toLowerCase().indexOf('msie') != -1)
		{
			document.body.style.width = (document.body.clientWidth - 20) + 'px';
		}
	}";
}
else
{
	echo "
	onload = function()
	{
		// correct for scrollbar in IE in iframe
		if (navigator.userAgent.toLowerCase().indexOf('msie') != -1)
		{
			document.body.style.width = (document.body.clientWidth - 20) + 'px';
		}
		
		// scroll to bottom
		window.scrollTo(0, 1000000);
	}";
}

?>

function pencere(u,t,w,h,x,y)
{
	if(!w)w=320;
	if(!h)h=200;
	if(!x)x=(screen.width-w)/2;
	if(!y)y=(screen.height-h)/2;
	if(!t)t='yeni';
  	win = window.open(u,t,"width="+w+",height="+h+",top="+x+",left="+y+",directories=0,location=0,menubar=0,scrollbars=1,status=1,toolbar=0,resizable=1");
  	win.focus();
  	return false;
}

</script>

</head>
<body>

<?php

echo "<table width=\"100%\" align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"2\">";

echo $goster;

?>

</table>

</body>
</html>