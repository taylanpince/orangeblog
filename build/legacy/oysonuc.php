<?php

$path = "./";

include("db/db.mysql.php");

$title = "oy sandigi";

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

echo "<table border=\"0\" width=\"200\" cellpadding=\"0\" cellspacing=\"0\"><tr>";
echo "<td width=\"150\"><h2>oy sandigi</h2></td>";
echo "<td valign=\"middle\" align=\"right\">";

menuyap("tekbuton", "kapa beni", "#", "window.close();");

echo "</td></tr></table>";

echo "<br><br>";

if ($nedir == "1")
{
	$goster = "bir yerlerde bir hata olmus, oyunu ekleyemedim. yeniden dene istersen.";
}
elseif ($nedir == "2")
{
	$goster = "sen daha once bu entriye oy vermissin bre gafil!";
}
elseif ($nedir == "3")
{
	$goster = "entri icin oyun kaydedildi, basin goge ermistir artik.";
}
elseif ($nedir == "4")
{
	$goster = "sen daha once bu yoruma oy vermissin bre gafil!";
}
elseif ($nedir == "5")
{
	$goster = "yorum icin oyun kaydedildi, hadi bakalim.";
}

echo $goster;

?>

</div>

</body>

</html>