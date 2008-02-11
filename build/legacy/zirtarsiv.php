<?php

$path = "./";

include("db/db.mysql.php");

$title = "zirtapoz arsivi";

include("left.inc.php");

echo "<h2>zirtapoz arsivi</h2>";

if ($basla == "") $basla = 0;

$limit = 10;

$sql = "select id from ob_zirtapoz";
$query = new DB_query($db, $sql);

$total = $query->db_num_rows();

unset($sql);
unset($query);

$son = $total - $limit;
$sonraki = $basla + $limit;
$topsayfa = ceil($total / $limit);
$cursayfa = ceil($sonraki / $limit);

$onceki = ($cursayfa == 2) ? 0 : $basla - $limit;

$gezbar = "<table width=\"100%\" align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"clear: both;\"><tr><td align=\"right\">";

if ($onceki >= 0)
{
	$gezbar .= "<a href=\"zirtarsiv.php?basla=0\">ilk sayfa</a> | ";
	$gezbar .= "<a href=\"zirtarsiv.php?basla=$onceki\">onceki sayfa</a> | ";
}

if ($sonraki < $total)
{
	$gezbar .= "<a href=\"zirtarsiv.php?basla=$sonraki\">sonraki sayfa</a> | ";
	$gezbar .= "<a href=\"zirtarsiv.php?basla=$son\">son sayfa</a> | ";
}

$gezbar .= "sayfa $cursayfa / $topsayfa ($total)";
$gezbar .= "</td></tr></table>";

echo $gezbar;

echo "<div style=\"width: 400px; text-align: center;\">";

$sql = "select ob_zirtapoz.resim,ob_blog.baslik,ob_blog.id from ob_zirtapoz,ob_blog where ob_zirtapoz.blogid = ob_blog.id order by ob_zirtapoz.id desc limit $basla,$limit";
$query = new DB_query($db, $sql);

while ($query->db_fetch_object())
{
	$id = $query->obj->id;
	$baslik = $query->obj->baslik;
	$resim = $query->obj->resim;
	
	echo "<div style=\"float: left; display: inline; width: 200px; height: 175px; text-align: center; margin: 0px;\">";
	echo "<h3>$baslik</h3>";
	echo "<a href=\"".$path."yorumlar.php?id=$id\"><img src=\"".$path."zirtapoz/".$resim."\" border=\"1\" width=\"150\" height=\"100\"></a>";
	echo "</div>";
}

echo "</div>";

include("right.inc.php");

?>