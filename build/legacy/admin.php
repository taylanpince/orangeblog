<?php

$path = "./";

include("db/db.mysql.php");

if ($loginyazarstatu != "9")
{
	return header("location:index.php");
}

$title = "admin atraksiyonlari";

include ("left.inc.php");

echo "<div align=\"center\">";
menuyap("baslat");
menuyap("menu", "uyeler", "admin.php");
menuyap("menu", "nabiz olcer", "nabizolcer.php");
menuyap("menu", "sinsi editor", "sinsieditor.php");
menuyap("bitir");
echo "</div>";

echo "<h2>admin atraksiyonlari :: uyeler</h2>";

$alter01 = "aciksira";
$alter02 = "koyusira";
$row_count = 0;

$sql = "select * from ob_uyeler order by id asc";
$query = new DB_query($db, $sql);

echo "<table border=\"0\" cellpadding=\"2\" cellspacing=\"4\" width=\"100%\">";
echo "<tr class=\"koyusira\"><td align=\"center\">isim</td><td align=\"center\">email</td><td align=\"center\">statu</td><td align=\"center\">degistir</td></tr>";

while ($query->db_fetch_object())
{
	$id = $query->obj->id;
	$isim = $query->obj->isim;
	$email = $query->obj->email;
	$statu = $query->obj->statu;
	
	$row_colour = ($row_count % 2) ? $alter01 : $alter02;

	echo "<tr class=\"$row_colour\"><td><a href=\"#\" onClick=\"return pencere('yazarhakkinda.php?id=$id','info',375,400,50,50);\">$isim</a></td>";
	echo "<td><a href=\"mailto:$email\">$email</a></td><td>$statu</td><td><a href=\"uyedegistir.php?id=$id\">degistir</a></td></tr>";

	$row_count++;
}

unset($sql);
unset($query);

echo "</table>";

include ("right.inc.php");

?>