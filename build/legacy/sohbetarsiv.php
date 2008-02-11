<?php

if (!isset($HTTP_COOKIE_VARS['turkmackulkod']))
{
	return header("location:giris.php?nedir=form&hata=4&hedef=makaleyaz.php");
}

include ("db/db.mysql.php");
include ("ust.inc.php");

echo "<table width=\"100%\" align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">";
echo "<tr><td align=\"left\" width=\"163\"><img src=\"tema/$tema/sohbetarsiv.jpg\" width=\"163\" border=\"0\">";
echo "</td><td class=\"blokbaslik\">&nbsp</td></tr></table><br>";

echo "<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\"><tr>";
echo "<tr><td width=\"100%\" class=\"blokaciksira\" align=\"left\">";
echo "<form name=\"sohbetarsiv\" target=\"sohbetarsivKutu\" action=\"sohbet.php?nedir=ekle&arsiv=1\" method=\"post\">";
echo "<select name=\"arazaman\" class=\"pulldown\" onChange=\"sohbetarsivKutu.location.href='sohbet.php?nedir=ekle&arsiv=1&arazaman='+this.value;\"><option value=\"1\">1 Saat</option><option value=\"2\">2 Saat</option><option value=\"3\">3 Saat</option><option value=\"4\">4 Saat</option><option value=\"5\">5 Saat</option><option value=\"6\">6 Saat</option><option value=\"7\">7 Saat</option><option value=\"8\">8 Saat</option><option value=\"9\">9 Saat</option><option value=\"10\">10 Saat</option><option value=\"11\">11 Saat</option><option value=\"12\">12 Saat</option><option value=\"birgun\" selected>1 Gün</option><option value=\"ikigun\">2 Gün</option><option value=\"ucgun\">3 Gün</option></select>";
echo " öncesinden başlayarak sohbet köşesi arşivini göster.<br><br>";
echo "<iframe class=\"sohbetKutu\" id=\"sohbetKutu\" name=\"sohbetarsivKutu\" style=\"width: 500px; height: 350px;\" src=\"sohbet.php?arsiv=1\"></iframe><br>";
echo "<br><input name=\"arsivGuncelle\" type=\"image\" src=\"tema/$tema/guncellebut.jpg\" width=\"81\" height=\"21\">";
echo "</form></td></tr></table>";

include ("alt.inc.php");

?>