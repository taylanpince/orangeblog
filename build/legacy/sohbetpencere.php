<?php

include ("db/db.mysql.php");

$tema = temaBelirle();

?>

<html>
<head>

<title>turkmac.com</title>

<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=ISO-8859-9"> 
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1254">

<meta name="Author" content="turkmac.com">
<meta name="Keywords" content="turkce, icerik, yazilim, yazılım, makale, türkçe, mac, macintosh, haber, bilkom, powerpc, PPC, g3, g4, apple, steve, jobs, imac, ibook, powerbook, newton, macos, macosx, haberler, panther, elmakurdu, elmasuyu, jaguar, 10.2, 10.2, 10.3, g5, g6" />
<meta name="description" content="Türkçe Macintosh Kaynağı">

<link rel="ICON" href="tema/<?php echo $tema; ?>/icon.ico" type="image/x-icon">
<link rel="SHORTCUT ICON" href="tema/<?php echo $tema; ?>/icon.ico" type="image/x-icon">

<link href="tema/<?php echo $tema; ?>.css" rel="stylesheet" type="text/css">

<script language="JavaScript">
<!--

function pencere(u,t,w,h,x,y)
{
	if(!w)w=320;
	if(!h)h=200;
	if(!x)x=(screen.width-w)/2;
	if(!y)y=(screen.height-h)/2;
	if(!t)t='yeni';
  	win = window.open(u,t,"width="+w+",height="+h+",top="+x+",left="+y+",directories=0,location=0,menubar=0,scrollbars=1,status=1,toolbar=0,resizable=1");
  	win.focus();
  	return false;
}

function durumDegistir(durum)
{
	if (durum=="1")
	{
		innerText = "<a href=\"bagli.php?durum=0\" onClick=\"durumDegistir('0');\" target=\"sohbetYazar\"><img src=\"tema/<?php echo $tema; ?>/durumoff.jpg\" border=\"0\"></a>";
	}
	else
	{
		innerText = "<a href=\"bagli.php?durum=1\" onClick=\"durumDegistir('1');\" target=\"sohbetYazar\"><img src=\"tema/<?php echo $tema; ?>/durumon.jpg\" border=\"0\"></a>";
	}

	document.getElementById('durumalan').innerHTML = innerText;
	self.opener.document.getElementById('durumalan').innerHTML = innerText;
}

//-->
</script>

</head>

<body>
<br>

<?php

if (isset($HTTP_COOKIE_VARS['turkmackulkod']))
{
	echo "<table width=\"95%\" align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">";
	echo "<tr><td align=\"left\" width=\"163\"><img src=\"tema/$tema/sohbetkosesi.jpg\" width=\"163\" border=\"0\">";
	echo "</td><td class=\"blokbaslik\">&nbsp</td></tr></table>";

	echo "<table width=\"95%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\"><tr>";
	echo "<tr><td width=\"160\" class=\"blokaciksira\" align=\"center\" colspan=\"2\">";
	echo "<iframe class=\"sohbetKutu\" id=\"sohbetYazar\" name=\"sohbetYazar\" style=\"width: 240px; height: 80px;\" src=\"bagli.php?sohbetpencere=1\"></iframe><br>";
	echo "<iframe class=\"sohbetKutu\" id=\"sohbetKutu\" name=\"sohbetKutu\" style=\"width: 240px; height: 350px;\" src=\"sohbet.php?sohbetpencere=1\"></iframe><br><br>";
	echo "<form name=\"sohbet\" target=\"sohbetKutu\" action=\"sohbet.php?nedir=ekle&sohbetpencere=1\" method=\"post\">";
	echo "<input type=\"text\" onFocus=\"this.value='';\" name=\"sohbetmesaj\" size=\"32\" class=\"sohbetKutu\"><br>";
	echo "<table width=\"75%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\"><tr>";
	echo "<td align=\"left\"><input name=\"sohbetGonder\" type=\"image\" src=\"tema/$tema/gonder.jpg\" width=\"81\" height=\"21\"></td>";
	echo "<td align=\"right\"><a href=\"sohbet.php?sohbetpencere=1\" target=\"sohbetKutu\"><img src=\"tema/$tema/guncellebut.jpg\" border=\"0\"></a></td>";
	echo "</tr></table></form></td></tr>";
	echo "<tr><td align=\"left\">";
	
	$db = new DB();

	$kulkod = $HTTP_COOKIE_VARS['turkmackulkod'];
	
	$sql = "select durum from ek_uyeler where kulkod = '$kulkod'";
	$query = new DB_query($db, $sql);
	$query->db_fetch_object();
	
	$loginyazardurum = $query->obj->durum;
	
	unset($sql);
	unset($query);
	
	if ($loginyazardurum == "0")
	{
		echo "<div id=\"durumalan\" class=\"sohbetdurum\"><a href=\"bagli.php?durum=1\" onClick=\"durumDegistir('1');\" target=\"sohbetYazar\"><img src=\"tema/$tema/durumon.jpg\" border=\"0\"></a></div>";
	}
	else
	{
		echo "<div id=\"durumalan\" class=\"sohbetdurum\"><a href=\"bagli.php?durum=0\" onClick=\"durumDegistir('0');\" target=\"sohbetYazar\"><img src=\"tema/$tema/durumoff.jpg\" border=\"0\"></a></div>";
	}

	echo "</td><td></td></tr>";
	echo "<tr><td align=\"left\"><a href=\"#\" onclick=\"self.window.opener.location='sohbetarsiv.php';\"><img src=\"tema/$tema/sohbetarsivibut.jpg\" border=\"0\"></a></td><td align=\"right\"><a href=\"#\" onclick=\"window.close();\"><img src=\"tema/$tema/pencereyikapat.jpg\" border=\"0\"></a></td></tr></table>";
}
else
{
	echo "<table width=\"95%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\"><tr>";
	echo "<td><img src=\"tema/$tema/sohbetkosesi.jpg\"></td></tr>";
	echo "<tr><td width=\"160\" class=\"blokaciksira\" align=\"center\">";
	echo "<div class=\"hata\">Bu bölüme girebilmek için turkmac.com üyesi olmanız gerekiyor.</div>";
	echo "</td></tr>";
	echo "<tr><td align=\"right\"><a href=\"#\" onclick=\"window.close();\"><img src=\"tema/$tema/pencereyikapat.jpg\" border=\"0\"></a></td></tr></table>";
}

?>

</body>
</html>