<?php

$path = "./";

include("db/db.mysql.php");

$title = "kim kim?";

?>

<html>
<head>
<title>orangeblog :: <?php echo $title; ?></title>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=ISO-8859-9"> 
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1254">
<link rel="icon" href="<?php echo $path; ?>images/orange_icon.gif">
<script language="JavaScript" src="<?php echo $path; ?>kodaman.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $path."tema/".$tema; ?>.css">
</head>

<body>

<div align="center">

<?php

echo "<table border=\"0\" width=\"300\" cellpadding=\"0\" cellspacing=\"0\"><tr>";
echo "<td width=\"150\"><h2>kim kim?</h2></td>";
echo "<td valign=\"middle\" align=\"right\">";

menuyap("tekbuton", "kapa beni", "#", "window.close();");

echo "</td></tr></table>";
?>

<table bgcolor="#C26500" cellpadding="0" cellspacing="1" border="0" width="300">
<tr>
<td bgcolor="#FFB956" valign="top">

<table bgcolor="#FFB956" cellpadding="0" cellspacing="2" border="0" width="100%">

<!-- tum uyelerin listesi -->

<?php

$alter01 = "aciksira";
$alter02 = "koyusira";
$row_count = 0;

$sql = "select id,isim,songiris from ob_uyeler order by id asc";
$query = new DB_query($db, $sql);

while ($query->db_fetch_object())
{
	$id = $query->obj->id;
	$isim = $query->obj->isim;
	$songiris = $query->obj->songiris;
	
	$guncel = time() - (15 * 60);
	
	if ($songiris >= $guncel)
	{
		$durum = "online";
	}
	else
	{
		$durum = "offline";
	}
	
	$row_colour = ($row_count % 2) ? $alter01 : $alter02;

	echo "<tr onmouseover=\"document.getElementById('$id').style.visibility='visible'\" onmouseout=\"document.getElementById('$id').style.visibility='hidden'\">";
	echo "<td class=\"$row_colour\"><a href=\"yazarhakkinda.php?id=$id&uyeler=1\">$isim</a></td><td width=\"20\" class=\"$durum\">";
	echo "</td><td id=\"$id\" style=\"visibility:hidden\" width=\"30\">";
	menuyap("tekbuton", "mesaj", "#", "return pencere('".$path."mesajgonder.php?kime=$id','mesaj',400,510,50,50)");
	echo "</td></tr>";

	$row_count++;
}

unset($sql);
unset($query);

?>

</table>

</td></tr>
</table>

</div>

</body>

</html>