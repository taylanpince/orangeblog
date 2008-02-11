<?php

$path = "./";

include ("db/db.mysql.php");

if ($kod == "")
{
	return header("location:giris.php?nedir=form&hata=7");
}

$db = new DB();

$sql = "select id from ob_uyeler where kulkod = '$kod'";
$query = new DB_query($db, $sql);
$total = $query->db_num_rows();
$query->db_fetch_object();

$id = $query->obj->id;

unset($sql);
unset($query);

if ($total == "1")
{
	$sql = "update ek_uyeler set statu = '1' where id = '$id'";
	$query = new DB_query($db, $sql);
	
	return header("location:giris.php?nedir=form&hata=6");
}
else
{
	return header("location:giris.php?nedir=form&hata=7");
}

?>