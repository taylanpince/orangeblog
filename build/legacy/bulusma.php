<?php

$path = "./";

include("db/db.mysql.php");

$title = "bulusma aparati";

include("left.inc.php");

echo "<div align=\"center\">";
menuyap("baslat");
menuyap("menu", "yaklasan bulusmalar", "bulusma.php");
menuyap("menu", "bulusma arsivi", "bulusma.php?nedir=arsiv");
menuyap("menu", "organize edecem!", "bulusmaekle.php");
menuyap("bitir");
echo "</div>";

if ($nedir == "")
{
	echo "<h2>bulusma aparati :: yaklasan bulusmalar</h2>";
	
	echo "<table border=\"0\" width=\"100%\" cellpadding=\"2\" cellspacing=\"4\">";
	
	$alter01 = "koyusira";
	$alter02 = "aciksira";
	$row_count = 0;
	
	$sql = "select id,yazarid,zaman,olay from ob_bulusma where zaman >= '$simdi' or zaman = '0' order by zaman asc";
	$query = new DB_query($db, $sql);
	$toplam = $query->db_num_rows();
	
	if ($toplam > 0)
	{
		echo "<tr class=\"koyusira\"><td>olay</td><td width=\"100\">organizator</td><td width=\"75\">tarih</td></tr>";
		
		while ($query->db_fetch_object())
		{
			$id = $query->obj->id;
			$yazarid = $query->obj->yazarid;
			$zaman = $query->obj->zaman;
			$olay = $query->obj->olay;
	
			if ($zaman == 0)
			{
				$zaman = "belirsiz";
			}
			else
			{
				$zaman = date("d.m.Y", $zaman);
			}
			
			$s = "select isim from ob_uyeler where id = '$yazarid'";
			$q = new DB_query($db, $s);
			$q->db_fetch_object();
			
			$yazarisim = $q->obj->isim;
			
			unset($s);
			unset($q);
			
			$row_colour = ($row_count % 2) ? $alter01 : $alter02;
		
			echo "<tr class=\"$row_colour\"><td><a href=\"bulusyorum.php?id=$id\">$olay</a></td><td width=\"100\">$yazarisim</td><td width=\"75\">$zaman</td></tr>";
			
			$row_count++;
		}
	}
	else
	{
		echo "<tr class=\"aciksira\">gelecekte hic bulusma gozukmuyo be... cok fena cok, organize olun cabuk!<td></td></tr>";
	}
		
	echo "</table>";
}
elseif ($nedir == "arsiv")
{
	echo "<h2>bulusma aparati :: bulusma arsivi</h2>";

	if ($basla == "")
	{
		$basla = "0";
	}
	
	$limit = "10";
	
	$sql = "select id from ob_bulusma";
	$query = new DB_query($db, $sql);
	
	$total = $query->db_num_rows();
	$son = $total - $limit;
	$sonraki = $basla + $limit;
	$topsayfa = ceil($total/$limit);
	$cursayfa = ceil($sonraki/$limit);
	
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
		$gezbar .= "<a href=\"bulusma.php?nedir=arsiv&basla=0\">ilk sayfa</a> | ";
		$gezbar .= "<a href=\"bulusma.php?nedir=arsiv&basla=$onceki\">onceki sayfa</a> | ";
	}
	
	if ($sonraki < $total)
	{
		$gezbar .= "<a href=\"bulusma.php?nedir=arsiv&basla=$sonraki\">sonraki sayfa</a> | ";
		$gezbar .= "<a href=\"bulusma.php?nedir=arsiv&basla=$son\">son sayfa</a> | ";
	}
	
	$gezbar .= "sayfa $cursayfa / $topsayfa ($total)";
	$gezbar .= "</td></tr></table>";
	
	echo $gezbar;
	
	echo "<table border=\"0\" width=\"100%\" cellpadding=\"2\" cellspacing=\"4\">";
	echo "<tr class=\"koyusira\"><td>olay</td><td width=\"100\">organizator</td><td width=\"75\">tarih</td></tr>";
	
	$alter01 = "koyusira";
	$alter02 = "aciksira";
	$row_count = 0;
	
	$sql = "select id,yazarid,zaman,olay from ob_bulusma order by zaman desc limit $basla,$limit";
	$query = new DB_query($db, $sql);
	
	while ($query->db_fetch_object())
	{
		$id = $query->obj->id;
		$yazarid = $query->obj->yazarid;
		$zaman = $query->obj->zaman;
		$olay = $query->obj->olay;

		if ($zaman == 0)
		{
			$zaman = "belirsiz";
		}
		else
		{
			$zaman = date("d.m.Y", $zaman);
		}
		
		$s = "select isim from ob_uyeler where id = '$yazarid'";
		$q = new DB_query($db, $s);
		$q->db_fetch_object();
		
		$yazarisim = $q->obj->isim;
		
		unset($s);
		unset($q);
		
		$row_colour = ($row_count % 2) ? $alter01 : $alter02;
	
		echo "<tr class=\"$row_colour\"><td><a href=\"bulusyorum.php?id=$id\">$olay</a></td><td width=\"100\">$yazarisim</td><td width=\"75\">$zaman</td></tr>";
		
		$row_count++;
	}
	
	echo "</table>";
	
	echo $gezbar;
}

include("right.inc.php");

?>