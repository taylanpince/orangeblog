<?php

$path = "./";

include("db/db.mysql.php");

if ($id == "")
{
	return header("location:bulusma.php");
}

$sql = "select bulusid,yazarid from ob_bulusyorum where id = '$id'";
$query = new DB_query($db, $sql);
$query->db_fetch_object();

$yazarid = $query->obj->yazarid;
$bulusid = $query->obj->bulusid;

unset($sql);
unset($query);

if ($yazarid != $loginyazarid && $loginyazarstatu < "7")
{
	return header("location:bulusyorum.php?id=$bulusid#yorumlar");
}

$sql = "delete from ob_bulusyorum where id = '$id'";
$query = new DB_query($db, $sql);

unset($sql);
unset($query);

return header("location:bulusyorum.php?id=$bulusid");

?>