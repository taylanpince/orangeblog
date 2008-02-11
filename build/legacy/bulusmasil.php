<?php

$path = "./";

include("db/db.mysql.php");

if ($id == "")
{
	return header("location:bulusma.php");
}

$sql = "select yazarid from ob_bulusma where id = '$id'";
$query = new DB_query($db, $sql);
$query->db_fetch_object();

$yazarid = $query->obj->yazarid;

unset($sql);
unset($query);

if ($yazarid != $loginyazarid && $loginyazarstatu < "7")
{
	return header("location:bulusmayorum.php?id=$id");
}

$sql = "delete from ob_bulusma where id = '$id'";
$query = new DB_query($db, $sql);

unset($sql);
unset($query);

$sql = "select id from ob_bulusyorum where bulusid = '$id'";
$query = new DB_query($db, $sql);
$yorumlartoplam = $query->db_num_rows();

if ($yorumlartoplam > 0)
{
	while ($query->db_fetch_object())
	{
		$yorumid = $query->obj->id;
		
		$s = "delete from ob_bulusyorum where id = '$yorumid'";
		$q = new DB_query($db, $s);
		
		unset($s);
		unset($q);
	}
}

unset($sql);
unset($query);

return header("location:bulusma.php");

?>