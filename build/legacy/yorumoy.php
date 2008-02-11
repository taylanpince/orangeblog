<?php

$path = "./";

include("db/db.mysql.php");

if ($id == "" || $vote == "" || $loginyazarid == "")
{
	return header("location:oysonuc.php?nedir=1");
}

$sql = "select * from ob_yorumoy where yorumid = '$id' and yazarid = '$loginyazarid'";
$query = new DB_query($db, $sql);
$onceyorum = $query->db_num_rows();

unset($sql);
unset($query);

if ($onceyorum >= "1")
{
	return header("location:oysonuc.php?nedir=4");
}

$sql = "select oy from ob_yorumlar where id = '$id'";
$query = new DB_query($db, $sql);
$query->db_fetch_object();

$eskioy = $query->obj->oy;

unset($sql);
unset($query);

if ($vote == "1")
{
	$yenioy = $eskioy + 1;
}
elseif ($vote == "0")
{
	$yenioy = $eskioy - 1;
}
else
{
	$yenioy = $eskioy;
}

$sql = "update ob_yorumlar set oy = '$yenioy' where id = '$id'";
$query = new DB_query($db, $sql);

unset($sql);
unset($query);

$sql = "insert into ob_yorumoy (yorumid,yazarid) values ('$id','$loginyazarid')";
$query = new DB_query($db, $sql);

unset($sql);
unset($query);

return header("location:oysonuc.php?nedir=5");

?>