<?php

$path = "./";

include("db/db.mysql.php");

if ($aranacak == "" && $yazar == "")
{
	return header("location:index.php");
}

if ($nerede == "blog")
{
	$arama = "select id,baslik,yazarid,tarih from ob_blog ";
}
elseif ($nerede == "yorumlar")
{
	$arama = "select id,blogid,yazarid,tarih from ob_yorumlar ";
}

if ($aranacak <> "" && $ayri == "1")
{
	$bularr = explode(" ", $aranacak);
	
	foreach ($bularr as $kelime)
	{
		if (strlen($kelime) >= "3")
		{
			if ($nerede == "blog")
			{
				if ($bolum == "baslik")
				{
					$eklenti .= "baslik like '%$kelime%' or ";
				}
				elseif ($bolum == "entri")
				{
					$eklenti .= "icerik like '%$kelime%' or daha like '%$kelime%' or ";
				}
				elseif ($bolum == "hepsi")
				{
					$eklenti .= "baslik like '%$kelime%' or icerik like '%$kelime%' or daha like '%$kelime%' or ";
				}
			}
			elseif ($nerede == "yorumlar")
			{
				$eklenti .= "yorum like '%$kelime%' or ";
			}
		}
	}

	$eklenti = substr($eklenti, 0, -4);
}
elseif ($aranacak <> "")
{
	if ($nerede == "blog")
	{
		if ($bolum == "baslik")
		{
			$eklenti = "baslik like '%$aranacak%'";
		}
		elseif ($bolum == "entri")
		{
			$eklenti = "icerik like '%$aranacak%' or daha like '%$aranacak%'";
		}
		elseif ($bolum == "hepsi")
		{
			$eklenti = "baslik like '%$aranacak%' or icerik like '%$aranacak%' or daha like '%$aranacak%'";
		}
	}
	elseif ($nerede == "yorumlar")
	{
		$eklenti = "yorum like '%$aranacak%'";
	}
}

if ($yazar <> "" && $aranacak <> "")
{
	$sql = "select id from ob_uyeler where isim = '$yazar'";
	$query = new DB_query($db, $sql);
	$yazartoplam = $query->db_num_rows();
	$query->db_fetch_object();
	
	$yazarid = $query->obj->id;
	
	unset($sql);
	unset($query);
	
	if ($yazartoplam == "1")
	{
		$arama .= "where yazarid = '$yazarid' and ($eklenti)";
	}
	else
	{
		$arama .= "where $eklenti";
	}
}
elseif ($aranacak <> "")
{
	$arama .= "where $eklenti";
}
elseif ($yazar <> "")
{
	$sql = "select id from ob_uyeler where isim = '$yazar'";
	$query = new DB_query($db, $sql);
	$yazartoplam = $query->db_num_rows();
	$query->db_fetch_object();
	
	$yazarid = $query->obj->id;
	
	unset($sql);
	unset($query);

	if ($yazartoplam == "1")
	{
		$arama .= "where yazarid = '$yazarid'";
	}
	else
	{
		return header("location:index.php");
	}
}

if ($basla == "")
{
	$basla = "0";
}

$limit = "25";
$tamarama = $arama." limit $basla,$limit";

$title = "yuksek arama kurulu";

include("left.inc.php");

echo "<h2>yuksek arama kurulu</h2>";

$query = new DB_query($db, $arama);
$total = $query->db_num_rows();

unset($query);

if ($total == "0")
{
	echo "aradim taradim bulamadim, yok boyle bisey.";
}
else
{
	$query = new DB_query($db, $arama);
	$total = $query->db_num_rows();
	$son = $total - $limit;
	$sonraki = $basla + $limit;
	$topsayfa = ceil($total/$limit);
	$cursayfa = ceil($sonraki/$limit);
	
	unset($query);
	
	if ($cursayfa == "2")
	{
		$onceki = "0";
	}
	else
	{
		$onceki = $basla - $limit;
	}
	
	$gezbar = "<table width=\"100%\" align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td align=\"right\">";
	
	if ($onceki >= 0)
	{
		$gezbar .= "<a href=\"arama.php?basla=0&aranacak=$aranacak&yazar=$yazar&nerede=$nerede&ayri=$ayri\">ilk sayfa</a> | ";
		$gezbar .= "<a href=\"arama.php?basla=$onceki&aranacak=$aranacak&yazar=$yazar&nerede=$nerede&ayri=$ayri\">onceki sayfa</a> | ";
	}
	
	if ($sonraki < $total)
	{
		$gezbar .= "<a href=\"arama.php?basla=$sonraki&aranacak=$aranacak&yazar=$yazar&nerede=$nerede&ayri=$ayri\">sonraki sayfa</a> | ";
		$gezbar .= "<a href=\"arama.php?basla=$son&aranacak=$aranacak&yazar=$yazar&nerede=$nerede&ayri=$ayri\">son sayfa</a> | ";
	}
	
	$gezbar .= "sayfa $cursayfa / $topsayfa ($total)";
	$gezbar .= "</td></tr></table>";
	
	echo $gezbar;
	
	$query = new DB_query($db, $tamarama);

	echo "<table width=\"100%\" border=\"0\" cellpadding=\"4\" cellspacing=\"8\">";

	if ($nerede == "blog")
	{
		echo "<tr class=\"koyusira\"><td>baslik</td><td>yazar</td><td>tarih</td></tr>";
	
		while ($query->db_fetch_object())
		{
			$id = $query->obj->id;
			$baslik = $query->obj->baslik;
			$yazarid = $query->obj->yazarid;
			$tarih = $query->obj->tarih;
			
			$tarih = date("j.m.Y", $tarih);
			
			$s = "select isim from ob_uyeler where id = '$yazarid'";
			$q = new DB_query($db, $s);
			$q->db_fetch_object();
			
			$yazarisim = $q->obj->isim;
			
			unset($s);
			unset($q);
			
			echo "<tr><td><a href=\"yorumlar.php?id=$id\">$baslik</a></td><td><a href=\"#\" onClick=\"return pencere('".$path."mesajgonder.php?kime=$yazarid','mesaj',400,510,50,50)\">$yazarisim</a></td><td>$tarih</td></tr>";
		}
	}
	elseif ($nerede == "yorumlar")
	{
		echo "<tr class=\"koyusira\"><td>baslik</td><td>yazar</td><td>tarih</td></tr>";
	
		while ($query->db_fetch_object())
		{
			$id = $query->obj->id;
			$blogid = $query->obj->blogid;
			$yazarid = $query->obj->yazarid;
			$tarih = $query->obj->tarih;
			
			$tarih = date("j.m.Y", $tarih);
			
			$s = "select baslik from ob_blog where id = '$blogid'";
			$q = new DB_query($db, $s);
			$q->db_fetch_object();
			
			$baslik = $q->obj->baslik;
			
			unset($s);
			unset($q);
			
			$s = "select isim from ob_uyeler where id = '$yazarid'";
			$q = new DB_query($db, $s);
			$q->db_fetch_object();
			
			$yazarisim = $q->obj->isim;
			
			unset($s);
			unset($q);
			
			echo "<tr><td><a href=\"yorumlar.php?id=$blogid#$id\">$baslik</a></td><td><a href=\"#\" onClick=\"return pencere('".$path."mesajgonder.php?kime=$yazarid','mesaj',400,510,50,50)\">$yazarisim</a></td><td>$tarih</td></tr>";
		}
	}
	
	echo "</table>";
	
	unset($query);

	echo $gezbar;
}

include("right.inc.php");

?>