<?php

$path = "./";

include("db/db.mysql.php");

$title = "istatistik ortamlari";

include("left.inc.php");

echo "<h2>istatistik ortamlari</h2>";

if ($nedir == "" || $nedir == "genel")
{
	echo "<select name=\"istatistik\" class=\"pulldown\" onChange=\"window.location='istatistik.php?nedir='+this.value\"><option value=\"genel\" selected>genel istatistikler</option><option value=\"enentri\">en cok entri girenler</option><option value=\"enyorumcu\">en yorumcu yazarlar</option><option value=\"sevilenentri\">en sevilen entriler</option><option value=\"igrencentri\">en igraanc entriler</option><option value=\"nacizaneyorum\">en nacizane yorumlar</option><option value=\"garibanyorum\">en gariban yorumlar</option><option value=\"enyorumlu\">en bi yorumlu entriler</option></select><br>";

	echo "<h3>genel istatistikler</h3>";

	$sql = "select id from ob_blog";
	$query = new DB_query($db, $sql);
	$toplamentri = $query->db_num_rows();
	
	unset($sql);
	unset($query);
	
	$sql = "select id from ob_yorumlar";
	$query = new DB_query($db, $sql);
	$toplamyorum = $query->db_num_rows();
	
	unset($sql);
	unset($query);
	
	$sql = "select id from ob_uyeler";
	$query = new DB_query($db, $sql);
	$toplamuye = $query->db_num_rows();
	
	unset($sql);
	unset($query);
	
	$toplamyazi = $toplamentri + $toplamyorum;
	
	echo "toplam entri sayisi: <b>$toplamentri</b><br><br>";
	echo "toplam yorum sayisi: <b>$toplamyorum</b><br><br>";
	echo "butun yazilarin toplami: <b>$toplamyazi</b><br><br>";
	echo "orangeblog yazarlari: <b>$toplamuye</b>";
}
elseif ($nedir == "enentri")
{
	echo "<select name=\"istatistik\" class=\"pulldown\" onChange=\"window.location='istatistik.php?nedir='+this.value\"><option value=\"genel\">genel istatistikler</option><option value=\"enentri\" selected>en cok entri girenler</option><option value=\"enyorumcu\">en yorumcu yazarlar</option><option value=\"sevilenentri\">en sevilen entriler</option><option value=\"igrencentri\">en igraanc entriler</option><option value=\"nacizaneyorum\">en nacizane yorumlar</option><option value=\"garibanyorum\">en gariban yorumlar</option><option value=\"enyorumlu\">en bi yorumlu entriler</option></select><br>";

	echo "<h3>en cok entri girenler</h3>";
	
	$sql = "select id,isim,toplamentri from ob_uyeler order by toplamentri desc limit 5";
	$query = new DB_query($db, $sql);
	
	while ($query->db_fetch_object())
	{
		$id = $query->obj->id;
		$isim = $query->obj->isim;
		$toplam = $query->obj->toplamentri;
	
		echo "<a href=\"#\" onClick=\"return pencere('mesajgonder.php?kime=$id','mesaj',400,510,50,50);\">$isim</a> (<b>$toplam</b>)<br><br>";
	}
	
	unset($sql);
	unset($query);
}
elseif ($nedir == "enyorumcu")
{
	echo "<select name=\"istatistik\" class=\"pulldown\" onChange=\"window.location='istatistik.php?nedir='+this.value\"><option value=\"genel\">genel istatistikler</option><option value=\"enentri\">en cok entri girenler</option><option value=\"enyorumcu\" selected>en yorumcu yazarlar</option><option value=\"sevilenentri\">en sevilen entriler</option><option value=\"igrencentri\">en igraanc entriler</option><option value=\"nacizaneyorum\">en nacizane yorumlar</option><option value=\"garibanyorum\">en gariban yorumlar</option><option value=\"enyorumlu\">en bi yorumlu entriler</option></select><br>";

	echo "<h3>en yorumcu yazarlar (erman toroglu modu)</h3>";
	
	$sql = "select id,isim,toplamyorum from ob_uyeler order by toplamyorum desc limit 5";
	$query = new DB_query($db, $sql);
	
	while ($query->db_fetch_object())
	{
		$id = $query->obj->id;
		$isim = $query->obj->isim;
		$toplam = $query->obj->toplamyorum;
	
		echo "<a href=\"#\" onClick=\"return pencere('mesajgonder.php?kime=$id','mesaj',400,510,50,50);\">$isim</a> (<b>$toplam</b>)<br><br>";
	}
	
	unset($sql);
	unset($query);
}
elseif ($nedir == "sevilenentri")
{
	echo "<select name=\"istatistik\" class=\"pulldown\" onChange=\"window.location='istatistik.php?nedir='+this.value\"><option value=\"genel\">genel istatistikler</option><option value=\"enentri\">en cok entri girenler</option><option value=\"enyorumcu\">en yorumcu yazarlar</option><option value=\"sevilenentri\" selected>en sevilen entriler</option><option value=\"igrencentri\">en igraanc entriler</option><option value=\"nacizaneyorum\">en nacizane yorumlar</option><option value=\"garibanyorum\">en gariban yorumlar</option><option value=\"enyorumlu\">en bi yorumlu entriler</option></select><br>";

	echo "<h3>en sevilen entriler</h3>";

	$sql = "select id,baslik,yazarid from ob_blog order by oy desc limit 15";
	$query = new DB_query($db, $sql);
	
	while ($query->db_fetch_object())
	{
		$id = $query->obj->id;
		$baslik = $query->obj->baslik;
		$yazarid = $query->obj->yazarid;
	
		$s = "select isim from ob_uyeler where id = '$yazarid'";
		$q = new DB_query($db, $s);
		$q->db_fetch_object();
		
		$isim = $q->obj->isim;
		
		unset($s);
		unset($q);
		
		echo "<a href=\"yorumlar.php?id=$id\">$baslik</a> (<a href=\"#\" onClick=\"return pencere('mesajgonder.php?kime=$yazarid','mesaj',400,510,50,50);\">$isim</a>)<br><br>";
	}
}
elseif ($nedir == "igrencentri")
{
	echo "<select name=\"istatistik\" class=\"pulldown\" onChange=\"window.location='istatistik.php?nedir='+this.value\"><option value=\"genel\">genel istatistikler</option><option value=\"enentri\">en cok entri girenler</option><option value=\"enyorumcu\">en yorumcu yazarlar</option><option value=\"sevilenentri\">en sevilen entriler</option><option value=\"igrencentri\" selected>en igraanc entriler</option><option value=\"nacizaneyorum\">en nacizane yorumlar</option><option value=\"garibanyorum\">en gariban yorumlar</option><option value=\"enyorumlu\">en bi yorumlu entriler</option></select><br>";

	echo "<h3>en igraanc entriler</h3>";

	$sql = "select id,baslik,yazarid from ob_blog order by oy asc limit 15";
	$query = new DB_query($db, $sql);
	
	while ($query->db_fetch_object())
	{
		$id = $query->obj->id;
		$baslik = $query->obj->baslik;
		$yazarid = $query->obj->yazarid;
	
		$s = "select isim from ob_uyeler where id = '$yazarid'";
		$q = new DB_query($db, $s);
		$q->db_fetch_object();
		
		$isim = $q->obj->isim;
		
		unset($s);
		unset($q);
		
		echo "<a href=\"yorumlar.php?id=$id\">$baslik</a> (<a href=\"#\" onClick=\"return pencere('mesajgonder.php?kime=$yazarid','mesaj',400,510,50,50);\">$isim</a>)<br><br>";
	}
}
elseif ($nedir == "nacizaneyorum")
{
	echo "<select name=\"istatistik\" class=\"pulldown\" onChange=\"window.location='istatistik.php?nedir='+this.value\"><option value=\"genel\">genel istatistikler</option><option value=\"enentri\">en cok entri girenler</option><option value=\"enyorumcu\">en yorumcu yazarlar</option><option value=\"sevilenentri\">en sevilen entriler</option><option value=\"igrencentri\">en igraanc entriler</option><option value=\"nacizaneyorum\" selected>en nacizane yorumlar</option><option value=\"garibanyorum\">en gariban yorumlar</option><option value=\"enyorumlu\">en bi yorumlu entriler</option></select><br>";

	echo "<h3>en \"nacizane\" yorumlar</h3>";

	$sql = "select id,blogid,yazarid from ob_yorumlar order by oy desc limit 15";
	$query = new DB_query($db, $sql);
	
	while ($query->db_fetch_object())
	{
		$id = $query->obj->id;
		$blogid = $query->obj->blogid;
		$yazarid = $query->obj->yazarid;
	
		$s = "select isim from ob_uyeler where id = '$yazarid'";
		$q = new DB_query($db, $s);
		$q->db_fetch_object();
		
		$isim = $q->obj->isim;
		
		unset($s);
		unset($q);
		
		$s = "select baslik from ob_blog where id = '$blogid'";
		$q = new DB_query($db, $s);
		$q->db_fetch_object();
		
		$baslik = $q->obj->baslik;
		
		unset($s);
		unset($q);
		
		echo "<a href=\"#\" onClick=\"return pencere('mesajgonder.php?kime=$yazarid','mesaj',400,510,50,50);\">$isim</a> :: <a href=\"yorumlar.php?id=$blogid#$id\">$baslik</a><br><br>";
	}
}
elseif ($nedir == "garibanyorum")
{
	echo "<select name=\"istatistik\" class=\"pulldown\" onChange=\"window.location='istatistik.php?nedir='+this.value\"><option value=\"genel\">genel istatistikler</option><option value=\"enentri\">en cok entri girenler</option><option value=\"enyorumcu\">en yorumcu yazarlar</option><option value=\"sevilenentri\">en sevilen entriler</option><option value=\"igrencentri\">en igraanc entriler</option><option value=\"nacizaneyorum\">en nacizane yorumlar</option><option value=\"garibanyorum\" selected>en gariban yorumlar</option><option value=\"enyorumlu\">en bi yorumlu entriler</option></select><br>";

	echo "<h3>en gariban yorumlar</h3>";

	$sql = "select id,blogid,yazarid from ob_yorumlar order by oy asc limit 15";
	$query = new DB_query($db, $sql);
	
	while ($query->db_fetch_object())
	{
		$id = $query->obj->id;
		$blogid = $query->obj->blogid;
		$yazarid = $query->obj->yazarid;
	
		$s = "select isim from ob_uyeler where id = '$yazarid'";
		$q = new DB_query($db, $s);
		$q->db_fetch_object();
		
		$isim = $q->obj->isim;
		
		unset($s);
		unset($q);
		
		$s = "select baslik from ob_blog where id = '$blogid'";
		$q = new DB_query($db, $s);
		$q->db_fetch_object();
		
		$baslik = $q->obj->baslik;
		
		unset($s);
		unset($q);
		
		echo "<a href=\"#\" onClick=\"return pencere('mesajgonder.php?kime=$yazarid','mesaj',400,510,50,50);\">$isim</a> :: <a href=\"yorumlar.php?id=$blogid#$id\">$baslik</a><br><br>";
	}
}
elseif ($nedir == "enyorumlu")
{
	echo "<select name=\"istatistik\" class=\"pulldown\" onChange=\"window.location='istatistik.php?nedir='+this.value\"><option value=\"genel\">genel istatistikler</option><option value=\"enentri\">en cok entri girenler</option><option value=\"enyorumcu\">en yorumcu yazarlar</option><option value=\"sevilenentri\">en sevilen entriler</option><option value=\"igrencentri\">en igraanc entriler</option><option value=\"nacizaneyorum\">en nacizane yorumlar</option><option value=\"garibanyorum\">en gariban yorumlar</option><option value=\"enyorumlu\" selected>en bi yorumlu entriler</option></select><br>";

	echo "<h3>en bi yorumlu entriler</h3>";

	$sql = "select id,baslik,toplamyorum from ob_blog order by toplamyorum desc limit 15";
	$query = new DB_query($db, $sql);
	
	while ($query->db_fetch_object())
	{
		$id = $query->obj->id;
		$baslik = $query->obj->baslik;
		$toplam = $query->obj->toplamyorum;
	
		echo "<a href=\"yorumlar.php?id=$id\">$baslik</a> ($toplam)<br><br>";
	}
	
	unset($sql);
	unset($query);
}

include("right.inc.php");

?>