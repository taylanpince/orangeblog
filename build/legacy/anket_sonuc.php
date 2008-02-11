<?
include_once("db/db.mysql.php");

$simplepollurl = simplepollurl;

$sql = "SELECT * FROM ob_anket WHERE id = '$pollid'";
$query = new DB_query($db, $sql);
$query->db_fetch_object();

$numpollvotes = $query->obj->votes;
$pollquestion = $query->obj->question;
$pollname = $query->obj->name;

unset($sql);
unset($query);

if ($numpollvotes == "0")
{
	$numpollvotes = "1";
}

$sql = "SELECT * FROM ob_anketsec WHERE pollid = '$pollid' order by votes desc";
$query = new DB_query($db, $sql);

while ($query->db_fetch_object())
{
	$votes = $query->obj->votes;
	$choice = $query->obj->choice;

	$width = ($votes/$numpollvotes)*200;
	$percentage = ($votes/$numpollvotes)*100;
	$point = explode(".",$percentage);
	$units = $point[0];
	$decimals = $point[1];
	$count = count($point);
	
	if ($count > "1")
	{
		$len = strlen ($decimals);
		$decimals = substr_replace($decimals, '', 1, $len);
		$percentage = "$units.$decimals";
	}
	
	$pollresult .= "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\">";
	$pollresult .= "<tr><td width=\"79%\">$choice</td>";
	$pollresult .= "<td width=\"21%\" align=\"center\">$votes</td></tr>";
	$pollresult .= "<tr><td><table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
	$pollresult .= "<tr><td width=\"10\"><img src=\"".$simplepollurl."/trans.gif\" width=\"1\" height=\"1\"></td>";
	$pollresult .= "<td class=\"anketseysi\"><img src=\"".$simplepollurl."/trans.gif\" width=\"".$width."\" height=\"8\"></td>";
	$pollresult .= "</tr></table></td>";
	$pollresult .= "<td align=\"center\">%".$percentage."</td></tr>";
	$pollresult .= "<tr><td><img src=\"".$simplepollurl."/trans.gif\" width=\"8\" height=\"8\"></td>";
	$pollresult .= "<td align=\"center\"><img src=\"".$simplepollurl."/trans.gif\" width=\"1\" height=\"1\"></td>";
	$pollresult .= "</tr></table>";
}

unset($sql);
unset($query);

$title = "nabiz olcer sonuclari";

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
echo "<td width=\"150\"><h2>nabiz olcer sonuclari</h2></td>";
echo "<td valign=\"middle\" align=\"right\">";

menuyap("tekbuton", "kapa beni", "#", "window.close();");

echo "</td></tr></table>";
?>

<table bgcolor="#C26500" cellpadding="0" cellspacing="1" border="0" width="300">
<tr>
<td bgcolor="#FFB956" valign="top">

<table bgcolor="#FFB956" cellpadding="0" cellspacing="2" border="0" width="100%">

<table width="300" align="center" border="0" cellpadding="0" cellspacing="4">
<tr>
<td class="yaziaciksira">
<table width="100%" border="0" cellspacing="0" cellpadding="4">
<tr>
<td align="left"><div class="nmText"><b><?php echo $pollquestion; ?></b></div></td>
</tr>
<tr>
<td>
<table width="100%" border="0" cellspacing="0" cellpadding="2">
<tr>
<td width="80%"><div class="nmText">toplam <?php echo $numpollvotes; ?> oy</div></td>
<td width="21%" align="center"><div class="nmText">oylar</div></td>
</tr>
</table>
</td>
</tr>
<tr>
<td>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="3%">&nbsp;</td>
<td width="97%">
<div class="nmText">
<?php echo $pollresult; ?>
</div></td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>
</table>

</table>

</td></tr>
</table>

</div>

</body>

</html>