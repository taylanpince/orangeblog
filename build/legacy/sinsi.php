<?php

$path = "./";

include("db/db.mysql.php");

$title = "sinsi popup huseyin";

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
<div id="sinsi">

<?php

if ($nedir == "normal" || $nedir == "")
{
	$sql = "update ob_uyeler set sinsi = '1' where id = '$loginyazarid'";
	$query = new DB_query($db, $sql);

	unset($sql);
	unset($query);

	echo "<table border=\"0\" width=\"275\" cellpadding=\"0\" cellspacing=\"0\"><tr>";
	echo "<td width=\"150\"><h2>sinsi huseyin</h2></td>";
	echo "<td valign=\"middle\" align=\"right\">";
	
	menuyap("baslat");
	menuyap("menu", "arsiv", "sinsi.php?nedir=arsiv");
	menuyap("menu", "kapa beni", "#", "window.close();");
	menuyap("bitir");
	
	echo "</td></tr></table>";
	
	$sql = "select * from ob_sinsi order by tarih desc limit 5";
	$query = new DB_query($db, $sql);
	
	while ($query->db_fetch_object())
	{
		$olay = $query->obj->olay;
		$tarih = $query->obj->tarih;
		
		$tarih = date("j.m.Y", $tarih);
		
		echo "<div class=\"menuitems\"><b>$tarih ::</b> $olay</div>";
	}

	unset($sql);
	unset($query);
}
elseif ($nedir == "arsiv")
{
	echo "<table border=\"0\" width=\"275\" cellpadding=\"0\" cellspacing=\"0\"><tr>";
	echo "<td width=\"150\"><h2>sinsi huseyin</h2></td>";
	echo "<td valign=\"middle\" align=\"right\">";
	
	menuyap("baslat");
	menuyap("menu", "taze", "sinsi.php?nedir=normal");
	menuyap("menu", "kapa beni", "#", "window.close();");
	menuyap("bitir");
	
	echo "</td></tr></table>";
	
	$sql = "select * from ob_sinsi order by tarih desc";
	$query = new DB_query($db, $sql);
	
	while ($query->db_fetch_object())
	{
		$olay = $query->obj->olay;
		$tarih = $query->obj->tarih;
		
		$tarih = date("j.m.Y", $tarih);
		
		echo "<div class=\"menuitems\"><b>$tarih ::</b> $olay</div>";
	}

	unset($sql);
	unset($query);
}

?>

</div>
</div>

</body>
</html>