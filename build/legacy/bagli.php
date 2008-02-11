<?php

include ("db/db.mysql.php");

$path = "./";

$eski = $simdi - (10 * 60);

if ($sohbetpencere == "1")
{
	$sohbetpencere = "1";
}
else
{
	$sohbetpencere = "0";
}

$query = new DB_query($db, "delete from ob_okur where tarih <= '$eski'");
unset($query);

if (isset($HTTP_COOKIE_VARS['member_code']))
{	
	if ($durum <> "")
	{
		$sql = "update ob_uyeler set durum = '$durum' where id = '$loginyazarid'";
		$query = new DB_query($db, $sql);
		
		unset($sql);
		unset($query);
	}
	
	$sql = "select id from ob_mesajlar where kime = '$loginyazarid' and durum = 'u'";
	$query = new DB_query($db, $sql);
	$okunmamis = $query->db_num_rows();
	
	unset($sql);
	unset($query);
}
else
{
	if (isset($HTTP_COOKIE_VARS['orangeokur']))
	{
		$kulkod = $HTTP_COOKIE_VARS['orangeokur'];
		
		$query = new DB_query($db, "select tarih from ob_okur where kulkod = '$kulkod'");
		$query->db_fetch_object();
		
		$tarih = $query->obj->tarih;
		
		unset($query);
		
		$query = new DB_query($db, "update ob_okur set tarih = '$simdi' where kulkod = '$kulkod'");
		unset($query);
		
		setcookie("orangeokur","$kulkod",time()+60*10,"/");
	}
	else
	{
		mt_srand((double) microtime() * 1000000);
		$kulkod = uniqid (mt_rand());
		
		$query = new DB_query($db, "select count(*) as count from ob_okur where kulkod = '$kulkod'");
		$query->db_fetch_object();
		
		if ($query->obj->count > 0)
		{
			sleep(1);
			$kulkod = uniqid (mt_rand());
		}
		
		unset($query);
		
		$query = new DB_query($db, "insert into ob_okur (kulkod, tarih) values ('$kulkod', '$simdi')");
		unset($query);
		
		setcookie("orangeokur","$kulkod",time()+60*10,"/");
	}
}

$sql = "select id,isim,durum from ob_uyeler where songiris >= '$eski' order by songiris desc";
$query = new DB_query($db, $sql);
$sohbetyazarlar = $query->db_num_rows();

$ziyaretci = ZiyaretciSay();

if ($ziyaretci > 0)
{
	$goster = "<tr><td class=\"sohbetkoyusira\" colspan=\"2\"><b>bagli okurlar: $ziyaretci</b></td></tr>";
}

$goster .= "<tr><td class=\"sohbetaciksira\" colspan=\"2\"><b>bagli yazarlar: $sohbetyazarlar</b></td></tr>";

if (isset($HTTP_COOKIE_VARS['member_code']))
{
	$alter01 = "sohbetaciksira";
	$alter02 = "sohbetkoyusira";
	$row_count = 0;
	
	while ($query->db_fetch_object())
	{
		$id = $query->obj->id;
		$isim = $query->obj->isim;
		$durum = $query->obj->durum;
		
		$row_colour = ($row_count % 2) ? $alter01 : $alter02;
		
		if ($durum == "0")
		{
			$durum = "online";
		}
		else
		{
			$durum = "offline";
		}
		
		$goster .= "<tr><td class=\"$row_colour\"><a href=\"#\" onClick=\"return pencere('mesajgonder.php?kime=$id','mesaj',400,510,50,50)\">$isim</a></td><td class=\"$durum\" width=\"20\">&nbsp</td></tr>";
		
		$row_count++;
	}
}

unset($sql);
unset($query);

?>

<html>
<head>

<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">

<link href="tema/<?php echo $tema; ?>_sohbet.css" rel="stylesheet" type="text/css">

<META HTTP-EQUIV="refresh" CONTENT="120;URL=bagli.php?sohbetpencere=<?php echo $sohbetpencere; ?>">

<script language="javascript">

onload = function()
{
	if (navigator.userAgent.toLowerCase().indexOf('msie') != -1)
	{
		document.body.style.width = (document.body.clientWidth - 20) + 'px';
	}
}

function mesajDegis()
{
	innerText = "<td class=\"online\" onclick=\"window.location='mesajlar.php';\" onmouseover=\"this.className='onlinehaberhover';\" onmouseout=\"this.className='online';\"><nobr>mesaj (<?php echo $okunmamis; ?>)</nobr></td>";
}

function mesajPencereDegis()
{
	innerText = "<td class=\"online\" onclick=\"window.location='mesajlar.php';\" onmouseover=\"this.className='onlinehaberhover';\" onmouseout=\"this.className='online';\"><nobr>mesaj (<?php echo $okunmamis; ?>)</nobr></td>";

	self.parent.opener.document.getElementById('mesajkutusu').innerHTML = innerText;
}

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

</script>

</head>

<?php

if ($okunmamis > "0" && $sohbetpencere == "1")
{
	echo "<body onLoad=\"mesajPencereDegis();\">";
}
elseif ($okunmamis > "0" && $sohbetpencere == "0")
{
	echo "<body onLoad=\"mesajDegis();\">";
}
else
{
	echo "<body>";
}

?>

<table width="100%" align="center" border="0" cellpadding="0" cellspacing="2">

<?php

echo $goster;

?>

</table>

</body>
</html>