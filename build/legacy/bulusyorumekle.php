<?php

$path = "./";

include("db/db.mysql.php");

if ($nedir == "ekle")
{
	if ($bulusid == "")
	{
		return header("location:bulusma.php");
	}
	elseif ($yorum == "")
	{
		return header("location:bulusyorum.php?id=$bulusid&hata=1#yorumyaz");
	}
	
	$yorum = encode($yorum);
	
	if ($haberet == "1")
	{
		$haberet = "1";
	}
	else
	{
		$haberet = "0";
	}
	
	$sql = "insert into ob_bulusyorum (bulusid,yazarid,tarih,yorum,haberet) values ('$bulusid','$loginyazarid','$simdi','$yorum','$haberet')";
	$query = new DB_query($db, $sql);

	unset($sql);
	unset($query);
	
	$sql = "select olay,yazarid,haberet from ob_bulusma where id = '$bulusid'";
	$query = new DB_query($db, $sql);
	$query->db_fetch_object();
	
	$yazarid = $query->obj->yazarid;
	$haberet = $query->obj->haberet;
	$baslik = $query->obj->baslik;
	
	unset($sql);
	unset($query);
	
	if ($haberet == "1" && $yazarid <> $loginyazarid)
	{
		$mesaj = "$olay bulusmasina yorum yazildi, okumak icin mutemadiyen <a href=\"bulusyorum.php?id=$bulusid\">tikla</a>.";
	
		$sql = "insert into ob_mesajlar (kimden, kime, tarih, mesaj, durum) values ('$loginyazarid', '$yazarid', '$simdi', '$mesaj', 'u')";
		$query = new DB_query($db, $sql);
	
		unset($sql);
		unset($query);
	}
	
	$sql = "select yazarid,haberet from ob_bulusyorum where (bulusid = '$bulusid' and haberet = '1' and yazarid <> '$loginyazarid')";
	$query = new DB_query($db, $sql);
	
	while ($query->db_fetch_object())
	{
		$yazarid = $query->obj->yazarid;
		$haberet = $query->obj->haberet;
		
		if ($userarray[$yazarid] == "")
		{
			$userarray[$yazarid] = "1";
			
			$mesaj = "izlemeye aldigin $olay bulusmasina yorum yazildi, okumak icin mutemadiyen <a href=\"bulusyorum.php?id=$bulusid\">tikla</a>.";
		
			$s = "insert into ob_mesajlar (kimden, kime, tarih, mesaj, durum) values ('$loginyazarid', '$yazarid', '$simdi', '$mesaj', 'u')";
			$q = new DB_query($db, $s);
		
			unset($s);
			unset($q);
		}
	}
	
	unset($sql);
	unset($query);
	
	return header("location:bulusyorum.php?id=$bulusid");
}

?>