<?php

$path = "./";

include("db/db.mysql.php");

if ($id == "")
{
	return header("location:bulusma.php");
}

if ($nedir == "cik")
{
	$s = "select katilimcilar from ob_bulusma where id = '$id'";
	$q = new DB_query($db, $s);
	$q->db_fetch_object();
	
	$katilimcilar = $q->obj->katilimcilar;
	
	unset($s);
	unset($q);
	
	$katilim = explode(".", $katilimcilar);
	
	foreach ($katilim as $value)
	{
		$s = "select isim from ob_uyeler where id = '$value'";
		$q = new DB_query($db, $s);
		$q->db_fetch_object();
		
		$katilisim = $q->obj->isim;
		
		unset($s);
		unset($q);
		
		if ($value != "$loginyazarid")
		{
			$guncelkatilim .= $value.".";
		}
	}
	
	$guncelkatilim = substr($guncelkatilim, 0, -1);
	
	$s = "update ob_bulusma set katilimcilar = '$guncelkatilim' where id = '$id'";
	$q = new DB_query($db, $s);
	
	unset($s);
	unset($q);
}
elseif ($nedir == "katil")
{
	$s = "select katilimcilar from ob_bulusma where id = '$id'";
	$q = new DB_query($db, $s);
	$q->db_fetch_object();
	
	$katilimcilar = $q->obj->katilimcilar;
	
	unset($s);
	unset($q);
	
	if ($katilimcilar == "")
	{
		$guncelkatilim = $loginyazarid;
	}
	else
	{
		$guncelkatilim = $katilimcilar.".".$loginyazarid;
	}
	
	$s = "update ob_bulusma set katilimcilar = '$guncelkatilim' where id = '$id'";
	$q = new DB_query($db, $s);
	
	unset($s);
	unset($q);
}

return header("location:bulusyorum.php?id=$id");

?>