<?php

$path = "./";

include("db/db.mysql.php");

if ($id == "")
{
	return header("location:index.php");
}

$sql = "select id,blogid,yazarid from ob_yorumlar where id = '$id'";
$query = new DB_query($db, $sql);
$query->db_fetch_object();

$id = $query->obj->id;
$blogid = $query->obj->blogid;
$yazarid = $query->obj->yazarid;

unset($sql);
unset($query);

if ($yazarid != $loginyazarid && $loginyazarstatu < "7")
{
	return header("location:yorumlar.php?id=$blogid#yorumlar");
}

$sql = "delete from ob_yorumlar where id = '$id'";
$query = new DB_query($db, $sql);

unset($sql);
unset($query);

$sql = "select toplamyorum from ob_uyeler where id = '$yazarid'";
$query = new DB_query($db, $sql);
$query->db_fetch_object();

$yazaryorum = $query->obj->toplamyorum;

unset($sql);
unset($query);

$yazaryorum = $yazaryorum - 1;

$sql = "update ob_uyeler set toplamyorum = '$yazaryorum' where id = '$yazarid'";
$query = new DB_query($db, $sql);

unset($sql);
unset($query);

$sql = "select toplamyorum from ob_blog where id = '$blogid'";
$query = new DB_query($db, $sql);
$query->db_fetch_object();

$toplamyorum = $query->obj->toplamyorum;

unset($sql);
unset($query);

$toplamyorum = $toplamyorum - 1;

$sql = "update ob_blog set toplamyorum = '$toplamyorum' where id = '$blogid'";
$query = new DB_query($db, $sql);

unset($sql);
unset($query);

return header("location:yorumlar.php?id=$blogid");

?>