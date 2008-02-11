</div>

<div id="menuright">

<div class="menuitems">
<h4>zirtapoz</h4><br>

<?php

$sql = "select * from ob_zirtapoz order by rand() limit 1";
$query = new DB_query($db, $sql);
$query->db_fetch_object();

$zirid = $query->obj->blogid;
$resim	= $query->obj->resim;

echo "<a href=\"".$path."yorumlar.php?id=$zirid\"><img src=\"".$path."zirtapoz/$resim\" border=\"1\" width=\"150\" height=\"100\"></a><br><br>";

unset($sql);
unset($query);

$sql = "select * from ob_vecize order by rand() limit 1";
$query = new DB_query($db, $sql);
$query->db_fetch_object();

$vecize = $query->obj->vecize;
$kisi = $query->obj->kisi;

echo "<div class=\"vecize\">\"$vecize\" -$kisi</div>";

unset($sql);
unset($query);

echo "<br>";

$sql = "select id from ob_zirtapoz where tarih > '$loginyazarsongiris'";
$query = new DB_query($db, $sql);
$yenizirtapoz = $query->db_num_rows();

$yenizirtapoz = ($yenizirtapoz > 0) ? " ($yenizirtapoz)" : "";

menuyap("tekbuton", "zirtapoz arsivi".$yenizirtapoz, $path."zirtarsiv.php");

?>

</div>

<?php

if (isset($HTTP_COOKIE_VARS['member_code']) && $loginyazarstatu >= "2")
{
	echo "<div class=\"menuitems\">";
	echo "<h4>kumandator</h4><br>";
	echo "<form method=\"post\" name=\"getur\" action=\"".$path."getur.php\">";
	echo "<h4>entri basligi</h4>";
	echo "<input type=\"text\" name=\"baslik\" size=\"20\" maxlength=\"50\" tabindex=\"1\">";
	
	menuyap("tekbuton", "yazacam / getür", "#", "document.getur.submit();");
	menuyap("tekbuton", "yuksek arama kurulu", "#", "if(document.getElementById('yuksekarama').style.display=='inline'){document.getElementById('yuksekarama').style.display='none';}else{document.getElementById('yuksekarama').style.display='inline';}");
	menuyap("tekbuton", "kalender", "#", "if(document.getElementById('kalender').style.display=='inline'){document.getElementById('kalender').style.display='none';}else{document.getElementById('kalender').style.display='inline';}");

	if ($loginyazarid <> "")
	{
		$sql = "select id from ob_google where date > '$loginyazarsongiris'";
		$query = new DB_query($db, $sql);
		$googletoplam = $query->db_num_rows();
		
		unset($sql);
		unset($query);
		
		if ($googletoplam > 0)
		{
			$blografyaDurum = " ($googletoplam)";
		}
		
		menuyap("tekbuton", "blografya".$blografyaDurum, $path."google.php");

		$sql = "select id from ob_yorumlar where tarih > '$loginyazaryorumoku' and yazarid <> '$loginyazarid'";
		$query = new DB_query($db, $sql);
		$tazeyorumtoplam = $query->db_num_rows();
		
		unset($sql);
		unset($query);
		
		if ($tazeyorumtoplam > 0)
		{
			menuYap("tekbuton", "taze yorumlar ($tazeyorumtoplam)", $path."sonyorumlar.php");
		}
	}

	echo "</form>";
	
	if ($title == "yuksek arama kurulu")
	{
		echo "<div id=\"yuksekarama\" style=\"display:inline\">";
	}
	else
	{
		echo "<div id=\"yuksekarama\" style=\"display:none\">";
	}
	
	echo "<form method=\"post\" name=\"yuksekaramakurulu\" action=\"".$path."arama.php\">";
	echo "<h5>neyi ariyoruz?</h5>";
	echo "<input type=\"text\" name=\"aranacak\" size=\"20\" maxlength=\"50\"><br><br>";
	echo "<h5>yazar</h5>";
	echo "<input type=\"text\" name=\"yazar\" size=\"20\" maxlength=\"50\"><br><br>";
	echo "<h5>nerede ariyoruz?</h5>";
	echo "<select name=\"nerede\" class=\"pulldown\" onChange=\"if(this.value=='yorumlar'){document.yuksekaramakurulu.bolum.disabled='yes';}else{document.yuksekaramakurulu.bolum.disabled='';}\">";
	echo "<option value=\"blog\">entriler</option>";
	echo "<option value=\"yorumlar\">yorumlar</option>";
	echo "</select><br>";
	echo "<select name=\"bolum\" class=\"pulldown\">";
	echo "<option value=\"baslik\">baslik</option>";
	echo "<option value=\"entri\">entri</option>";
	echo "<option value=\"hepsi\">hepsi</option>";
	echo "</select><br><br>";
	echo "<input type=\"checkbox\" name=\"ayri\" value=\"1\"> kelimeleri ayristir<br><br>";
	
	menuyap("tekbuton", "getir bobi", "#", "if(document.yuksekaramakurulu.aranacak.value=='' && document.yuksekaramakurulu.yazar.value==''){alert('hadi len ordan!');}else{document.yuksekaramakurulu.submit();}");
	
	echo "</form></div>";
	
	if ($nedirtarih == "")
	{
		echo "<div id=\"kalender\" style=\"display:none\">";
	}
	else
	{
		echo "<div id=\"kalender\" style=\"display:inline\">";
	}
	
	kalender($nedirtarih);
	
	echo "</div>";
	echo "</div>";
}

?>


<div class="menuitems">
<!-- geyik saldirisi -->

<?php

if (isset($HTTP_COOKIE_VARS['member_code']) && $loginyazarstatu >= "1")
{
	echo "<h4>geyiklerin saldirisi</h4><br>";
	echo "<iframe class=\"sohbetKutu\" id=\"sohbetYazar\" name=\"sohbetYazar\" style=\"width: 175px; height: 60px;\" src=\"bagli.php\"></iframe><br>";
	echo "<iframe class=\"sohbetKutu\" id=\"sohbetKutu\" name=\"sohbetKutu\" style=\"width: 175px; height: 200px;\" src=\"sohbet.php\"></iframe><br>";
	echo "<form name=\"sohbet\" target=\"sohbetKutu\" action=\"sohbet.php?nedir=ekle\" method=\"post\">";
	echo "<input type=\"text\" onFocus=\"this.value='';\" name=\"icerik\" size=\"20\"><br>";
	echo "<input type=\"hidden\" name=\"kullanici\" value=\"$loginyazarisim\"><input type=\"hidden\" name=\"kulid\" value=\"$loginyazarid\"><input type=\"hidden\" name=\"guncelno\" value=\"0\">";

	menuyap("baslat");
	menuyap("menu", "yaz!", "#", "document.sohbet.submit();");
	menuyap("menu", "guncelle", "#", "if(document.sohbet.guncelno.value=='0'){document.getElementById('sohbetKutu').src='sohbet.php?nedir=liste';document.sohbet.guncelno.value='1';}else{document.getElementById('sohbetKutu').src='sohbet.php';document.sohbet.guncelno.value='0';}");
	menuyap("bitir");

	if ($loginyazardurum == "0")
	{
		echo "<div id=\"durumalan\">";
		menuyap("tekbuton", "durum: hiperaktif", "#", "document.getElementById('sohbetYazar').src='bagli.php?durum=1';durumDegistir('1');", "online");
		echo "</div>";
	}
	else
	{
		echo "<div id=\"durumalan\">";
		menuyap("tekbuton", "durum: depresif", "#", "document.getElementById('sohbetYazar').src='bagli.php?durum=0';durumDegistir('0');", "offline");
		echo "</div>";
	}
	
	//menuyap("tekbuton", "geyik penceresi", "#", "return sohbetPencere('".$path."sohbetpencere.php','sohbet',280,620,50,50)");
	//menuyap("tekbuton", "geyik arsivleri", $path."sohbetarsiv.php");
	
	echo "</form>";
}
else
{
	echo "<h4>kimdir nedir?</h4><br>";
	echo "<iframe class=\"sohbetKutu\" id=\"sohbetYazar\" name=\"sohbetYazar\" style=\"width: 175px; height: 60px;\" src=\"bagli.php\"></iframe><br>";
}

?>

</div>

<div class="menuitems">
<h4>haftanin incigi cincigi</h4><br>
<!-- incik cincik -->

<?php

$sql = "select * from ob_incik order by id desc limit 2";
$query = new DB_query($db, $sql);

while ($query->db_fetch_object())
{
	$baslik = $query->obj->baslik;
	$icerik	= $query->obj->icerik;
	$yazarid = $query->obj->yazarid;
	
	$s = "select isim from ob_uyeler where id = '$yazarid'";
	$q = new DB_query($db, $s);
	$q->db_fetch_object();
	
	$isim = $q->obj->isim;
	
	unset($s);
	unset($q);

	echo "<h4>$baslik</h4>$icerik<br><br><div align=\"left\">$isim</div><br>";
}

unset($sql);
unset($query);

menuyap("tekbuton", "incik cincik arsivi", $path."incik.php");

?>

</div>

<div class="menuitems">
<!-- nabiz olcer -->

<?php

$sql = "select id from ob_anket order by id desc limit 1";
$query = new DB_query($db, $sql);
$query->db_fetch_object();

$pollid = $query->obj->id;

unset($sql);
unset($query);

include("anket.php");

menuyap("tekbuton", "nabiz olcer arsivi", $path."anket_arsiv.php");

?>

</div>

<div class="menuitems">
<h4>fakat?</h4>
<br />
blog'dur bu evet, weblog, ortaya karisik yazariz buraya. her aklimizdan gecen olmasa da, en ilginclerinin burada bulunacagi kesindir, hayaller, ruyalar, gorusler, gorusemeyenler, elestiriler ve avuntular, hayatimizdan kesitler, bir portakalin dilimleri gibi, leziz ve bol vitaminli. once okuyunuz, sonra cigneyerek yutunuz, budur.
<br />
</div>

<div class="menuitems">
<h4>nasil?</h4>
<br />
oturulup gunlerce kod yazilir, usenmeden fasiliteler eklenir, ortaya yeni fikirler atilir, yapilir da yapilir. blogun ilk versiyonu apple emac uzerinde hazirlanmisti, ancak son versiyonu powerbook g4 uzerinde tamamlandi. hepsinin yeri ayridir tabi.
<br />
</div>
</div>

</body>
</html>