<?
include_once("db/db.mysql.php");

$db = new DB();

if ($_COOKIE[turkmacanket.$pollid] == '')
{
	setcookie("nabizolcer$pollid", "$pollid", time() + (1000 * 60 * 60 * 24 * 7));
}
else
{
	return header("Location: anket_sonuc.php?pollid=$pollid");
}

$sql = "SELECT * FROM ob_anket WHERE id = '$pollid'";
$query = new DB_query($db, $sql);
$query->db_fetch_object();

$pollvotes = $query->obj->votes;
$pollquestion = $query->obj->question;
$pollname = $query->obj->name;
	
$numpollvotes = $pollvotes + 1;

unset($sql);
unset($query);

$sql = "SELECT * FROM ob_anketsec WHERE (id='$aid' AND pollid='$pollid')";
$query = new DB_query($db, $sql);
$query->db_fetch_object();

$choicevotes = $query->obj->votes;

$numchoicevotes = $choicevotes + 1;

unset($sql);
unset($query);

$sql = "UPDATE ob_anket SET votes='$numpollvotes' WHERE id='$pollid'";
$query = new DB_query($db, $sql);

unset($sql);
unset($query);

$sql = "UPDATE ob_anketsec SET votes='$numchoicevotes' WHERE (id='$aid' AND pollid='$pollid')";
$query = new DB_query($db, $sql);

unset($sql);
unset($query);

$title = "nabiz olcer fasilitesi";

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
echo "<td width=\"150\"><h2>nabiz olcer</h2></td>";
echo "<td valign=\"middle\" align=\"right\">";

menuyap("tekbuton", "kapa beni", "#", "window.close();");

echo "</td></tr></table>";
?>

<table bgcolor="#C26500" cellpadding="0" cellspacing="1" border="0" width="300">
<tr>
<td bgcolor="#FFB956" valign="top">

<table bgcolor="#FFB956" cellpadding="0" cellspacing="2" border="0" width="100%">

<center>
<br>
<b><?php echo $pollname; ?> icin nabzini olctum. daha nedir?</b><br><br>

<?php

menuyap("baslat");
menuyap("menu", "kapan", "#", "window.close();");
menuyap("menu", "sonuclari gorecem merak icindeyim", "anket_sonuc.php?pollid=$pollid");
menuyap("bitir");

?>

<br>
</center>

</table>

</td></tr>
</table>

</div>

</body>

</html>