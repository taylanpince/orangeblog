<?php

$path = "./";

include("db/db.mysql.php");

$sql = "select * from ob_uyeler where id = '$id'";
$query = new DB_query($db, $sql);
$query->db_fetch_object();

$id = $query->obj->id;
$isim = $query->obj->isim;
$katilimtarih = $query->obj->katilimtarih;
$songiris = $query->obj->songiris;
$toplamentri = $query->obj->toplamentri;
$toplamyorum = $query->obj->toplamyorum;
$sonentri = $query->obj->sonyazi;
$dogumtarih = $query->obj->dogumtarih;

unset($sql);
unset($query);

$katilimtarih = date("j.m.Y", $katilimtarih);

if ($sonentri == "" || $sonentri == "0")
{
	$sonentri = "yok ki daha";
}
else
{
	$sonentri = date("j.m.Y", $sonentri);
}

?>

<html>
<head>
<title>orangeblog :: <?php echo $isim; ?></title>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">
<link rel="icon" href="<?php echo $path; ?>images/orange_icon.gif">
<script language="JavaScript" src="<?php echo $path; ?>kodaman.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $path."tema/".$tema; ?>.css">
</head>

<body>

<div align="center">

<?php

echo "<table border=\"0\" width=\"300\" cellpadding=\"0\" cellspacing=\"0\"><tr>";
echo "<td width=\"100\"><h2>$isim</h2></td>";
echo "<td valign=\"middle\" align=\"right\">";

menuyap("baslat");

menuyap("menu", "mesaj", "#", "return pencere('".$path."mesajgonder.php?kime=$id','mesaj',400,510,50,50)");

if ($uyeler == "1")
{
	menuyap("menu", "geri don", "uyeler.php");
}

menuyap("menu", "kapa beni", "#", "window.close();");
menuyap("bitir");

echo "</td></tr></table>";

?>

<table bgcolor="#C26500" cellpadding="0" cellspacing="1" border="0" width="300">
<tr>
<td bgcolor="#FFB956" valign="top">

<table bgcolor="#FFB956" cellpadding="2" cellspacing="0" border="0" width="300">
<tr>
<td valign="middle" bgcolor="#EE7C00">

<div class="secmece" unselectable="on" align="left" onclick="if(document.getElementById('alan1').style.display=='inline'){document.getElementById('alan1').style.display='none';}else{document.getElementById('alan1').style.display='inline';}">[+] profilden</div>

</td>
</tr>
</table>

<div style="display:inline" id="alan1" align="left">

<!-- profil olaylari -->

<?php

echo "<table border=\"0\" cellspacing=\"0\" cellpadding=\"3\">";
echo "<tr><td align=\"left\">entri sayisi</td>";
echo "<td>$toplamentri</td>";
echo "</tr><tr>";
echo "<td align=\"left\">yorum sayisi</td>";
echo "<td>$toplamyorum</td>";
echo "</tr>";

if ($dogumtarih <> "") {
	echo "<tr>";
	echo "<td align=\"left\">dogum tarihi</td>";
	echo "<td>".date("d.m.Y", $dogumtarih)."</td>";
	echo "</tr>";
}

echo "<tr>";
echo "<td align=\"left\">son entrisi</td>";
echo "<td>$sonentri</td>";
echo "</tr><tr>";
echo "<td align=\"left\">uyelik tarihi</td>";
echo "<td>$katilimtarih</td>";
echo "</tr>";
echo "</table>";

?>

</div>
<br>

<table bgcolor="#FFB956" cellpadding="2" cellspacing="0" border="0" width="300">
<tr>
<td valign="middle" bgcolor="#EE7C00">

<div class="secmece" unselectable="on" align="left" onclick="if(document.getElementById('alan2').style.display=='inline'){document.getElementById('alan2').style.display='none';}else{document.getElementById('alan2').style.display='inline';}">[+] en son yorumlari</div>

</td>
</tr>
</table>

<div style="display:none" id="alan2" align="left">

<table border="0" cellspacing="0" cellpadding="3">
<tr>
<td align="left">

<!-- son yorumlar -->

<?php

$sql = "select id,blogid from ob_yorumlar where yazarid = '$id' order by tarih desc limit 15";
$query = new DB_query($db, $sql);
$toplam = $query->db_num_rows();

if ($toplam > 0)
{
	while ($query->db_fetch_object())
	{
		$yorumid = $query->obj->id;
		$blogid = $query->obj->blogid;
	
		$s = "select baslik from ob_blog where id = '$blogid'";
		$q = new DB_query($db, $s);
		$q->db_fetch_object();
	
		$baslik = $q->obj->baslik;
		
		unset($s);
		unset($q);
		
		echo "<a href=\"#\" onClick=\"self.opener.window.location='".$path."yorumlar.php?id=$blogid#$yorumid';self.opener.window.focus();\">$baslik</a><br>";
	}
}
else
{
	echo "yok ki daha...";
}

unset($sql);
unset($query);

?>

</tr>
</td>
</table>

</div>

<br />

<table bgcolor="#FFB956" cellpadding="2" cellspacing="0" border="0" width="300">
<tr>
<td valign="middle" bgcolor="#EE7C00">

<div class="secmece" unselectable="on" align="left" onclick="if(document.getElementById('alan3').style.display=='inline'){document.getElementById('alan3').style.display='none';}else{document.getElementById('alan3').style.display='inline';}">[+] en son entrileri</div>

</td>
</tr>
</table>

<div style="display:none" id="alan3" align="left">

<table border="0" cellspacing="0" cellpadding="3">
<tr>
<td align="left">

<!-- son entriler -->

<?php

$sql = "select id,baslik from ob_blog where yazarid = '$id' order by tarih desc limit 15";
$query = new DB_query($db, $sql);
$toplam = $query->db_num_rows();

if ($toplam > 0)
{
	while ($query->db_fetch_object())
	{
		$blogid = $query->obj->id;
		$baslik = $query->obj->baslik;
		
		unset($s);
		unset($q);
		
		echo "<a href=\"#\" onClick=\"self.opener.window.location='".$path."yorumlar.php?id=$blogid';self.opener.window.focus();\">$baslik</a><br>";
	}
}
else
{
	echo "yok ki daha...";
}

unset($sql);
unset($query);

?>

</tr>
</td>
</table>

</div>

<br />

<table bgcolor="#FFB956" cellpadding="2" cellspacing="0" border="0" width="300">
<tr>
<td valign="middle" bgcolor="#EE7C00">

<div class="secmece" unselectable="on" align="left" onclick="if(document.getElementById('alan4').style.display=='inline'){document.getElementById('alan4').style.display='none';}else{document.getElementById('alan4').style.display='inline';}">[+] en bi sevilen entrileri</div>

</td>
</tr>
</table>

<div style="display:none" id="alan4" align="left">

<table border="0" cellspacing="0" cellpadding="3">
<tr>
<td align="left">

<!-- iyi entrileri -->

<?php

$sql = "select id,baslik from ob_blog where yazarid = '$id' and oy > 0 order by oy desc limit 10";
$query = new DB_query($db, $sql);
$toplam = $query->db_num_rows();

if ($toplam > 0)
{
	while ($query->db_fetch_object())
	{
		$blogid = $query->obj->id;
		$baslik = $query->obj->baslik;
		
		unset($s);
		unset($q);
		
		echo "<a href=\"#\" onClick=\"self.opener.window.location='".$path."yorumlar.php?id=$blogid';self.opener.window.focus();\">$baslik</a><br>";
	}
}
else
{
	echo "yok ki daha...";
}

unset($sql);
unset($query);

?>

</tr>
</td>
</table>

</div>

<br />

<table bgcolor="#FFB956" cellpadding="2" cellspacing="0" border="0" width="300">
<tr>
<td valign="middle" bgcolor="#EE7C00">

<div class="secmece" unselectable="on" align="left" onclick="if(document.getElementById('alan5').style.display=='inline'){document.getElementById('alan5').style.display='none';}else{document.getElementById('alan5').style.display='inline';}">[+] en igraanc entrileri</div>

</td>
</tr>
</table>

<div style="display:none" id="alan5" align="left">

<table border="0" cellspacing="0" cellpadding="3">
<tr>
<td align="left">

<!-- kotu entrileri -->

<?php

$sql = "select id,baslik from ob_blog where yazarid = '$id' and oy < 0 order by oy asc limit 10";
$query = new DB_query($db, $sql);
$toplam = $query->db_num_rows();

if ($toplam > 0)
{
	while ($query->db_fetch_object())
	{
		$blogid = $query->obj->id;
		$baslik = $query->obj->baslik;
		
		unset($s);
		unset($q);
		
		echo "<a href=\"#\" onClick=\"self.opener.window.location='".$path."yorumlar.php?id=$blogid';self.opener.window.focus();\">$baslik</a><br>";
	}
}
else
{
	echo "yok ki daha...";
}

unset($sql);
unset($query);

?>

</tr>
</td>
</table>

</div>

<br />

<table bgcolor="#FFB956" cellpadding="2" cellspacing="0" border="0" width="300">
<tr>
<td valign="middle" bgcolor="#EE7C00">

<div class="secmece" unselectable="on" align="left" onclick="if(document.getElementById('alan6').style.display=='inline'){document.getElementById('alan6').style.display='none';}else{document.getElementById('alan6').style.display='inline';}">[+] en nacizane yorumlari</div>

</td>
</tr>
</table>

<div style="display:none" id="alan6" align="left">

<table border="0" cellspacing="0" cellpadding="3">
<tr>
<td align="left">

<!-- iyi yorumlari -->

<?php

$sql = "select id,blogid from ob_yorumlar where yazarid = '$id' and oy > 0 order by oy desc limit 10";
$query = new DB_query($db, $sql);
$toplam = $query->db_num_rows();

if ($toplam > 0)
{
	while ($query->db_fetch_object())
	{
		$yorumid = $query->obj->id;
		$blogid = $query->obj->blogid;
	
		$s = "select baslik from ob_blog where id = '$blogid'";
		$q = new DB_query($db, $s);
		$q->db_fetch_object();
	
		$baslik = $q->obj->baslik;
		
		unset($s);
		unset($q);
		
		echo "<a href=\"#\" onClick=\"self.opener.window.location='".$path."yorumlar.php?id=$blogid#$yorumid';self.opener.window.focus();\">$baslik</a><br>";
	}
}
else
{
	echo "yok ki daha...";
}

unset($sql);
unset($query);

?>

</tr>
</td>
</table>

</div>

<br />

<table bgcolor="#FFB956" cellpadding="2" cellspacing="0" border="0" width="300">
<tr>
<td valign="middle" bgcolor="#EE7C00">

<div class="secmece" unselectable="on" align="left" onclick="if(document.getElementById('alan7').style.display=='inline'){document.getElementById('alan7').style.display='none';}else{document.getElementById('alan7').style.display='inline';}">[+] en gariban yorumlari</div>

</td>
</tr>
</table>

<div style="display:none" id="alan7" align="left">

<table border="0" cellspacing="0" cellpadding="3">
<tr>
<td align="left">

<!-- kotu yorumlari -->

<?php

$sql = "select id,blogid from ob_yorumlar where yazarid = '$id' and oy < 0 order by oy asc limit 10";
$query = new DB_query($db, $sql);
$toplam = $query->db_num_rows();

if ($toplam > 0)
{
	while ($query->db_fetch_object())
	{
		$yorumid = $query->obj->id;
		$blogid = $query->obj->blogid;
	
		$s = "select baslik from ob_blog where id = '$blogid'";
		$q = new DB_query($db, $s);
		$q->db_fetch_object();
	
		$baslik = $q->obj->baslik;
		
		unset($s);
		unset($q);
		
		echo "<a href=\"#\" onClick=\"self.opener.window.location='".$path."yorumlar.php?id=$blogid#$yorumid';self.opener.window.focus();\">$baslik</a><br>";
	}
}
else
{
	echo "yok ki daha...";
}

unset($sql);
unset($query);

?>

</tr>
</td>
</table>

</div>

</td>
</tr>

</table>

</div>

</body>

</html>