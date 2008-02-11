<?php

$path = "./";

include("db/db.mysql.php");

if ($id == "")
{
	return header("location:index.php");
}

if ($loginyazarstatu < "7")
{
	return header("location:index.php");
}

$sql = "select yazarid from ob_blog where id = '$id'";
$query = new DB_query($db, $sql);
$query->db_fetch_object();

$yazarid = $query->obj->yazarid;

unset($sql);
unset($query);

$sql = "delete from ob_blog where id = '$id'";
$query = new DB_query($db, $sql);

unset($sql);
unset($query);

$sql = "select toplamentri from ob_uyeler where id = '$yazarid'";
$query = new DB_query($db, $sql);
$query->db_fetch_object();

$yazarentri = $query->obj->toplamentri;

unset($sql);
unset($query);

$yazarentri = $yazarentri - 1;

$sql = "update ob_uyeler set toplamentri = '$yazarentri' where id = '$yazarid'";
$query = new DB_query($db, $sql);

unset($sql);
unset($query);

$sql = "select id,yazarid from ob_yorumlar where blogid = '$id'";
$query = new DB_query($db, $sql);
$yorumlartoplam = $query->db_num_rows();

if ($yorumlartoplam > 0)
{
	while ($query->db_fetch_object())
	{
		$yorumid = $query->obj->id;
		$yazarid = $query->obj->yazarid;
		
		$s = "delete from ob_yorumlar where id = '$yorumid'";
		$q = new DB_query($db, $s);
		
		unset($s);
		unset($q);
		
		$s = "select toplamyorum from ob_uyeler where id = '$yazarid'";
		$q = new DB_query($db, $s);
		$q->db_fetch_object();
		
		$yazaryorum = $q->obj->toplamyorum;
		
		unset($s);
		unset($q);
		
		$yazaryorum = $yazaryorum - 1;
		
		$s = "update ob_uyeler set toplamyorum = '$yazaryorum' where id = '$yazarid'";
		$q = new DB_query($db, $s);
		
		unset($s);
		unset($q);
	}
}

unset($sql);
unset($query);

return header("location:index.php");

?>