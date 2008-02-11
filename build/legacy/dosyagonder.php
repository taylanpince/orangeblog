<?php

include("db/db.mysql.php");

if ($nedir == "")
{
	$goster .= "<form method=\"post\" action=\"dosyagonder.php?nedir=yukle\" name=\"dosyagonder\" enctype=\"multipart/form-data\">";
	$goster .= "<h2>dosya gonder</h2><br>";
	$goster .= "<input type=\"file\" name=\"dosya\" size=\"20\"><br>";
	$goster .= "<input type=\"hidden\" name=\"klasor\" value=\"images\">";
	$goster .= "<h3>eklenecek alan</h3>";
	$goster .= "<select name=\"hedef\" class=\"pulldown\">";
	$goster .= "<option value=\"entri\" selected>entri</option>";
	$goster .= "<option value=\"daha\">daha daha</option>";
	$goster .= "</select><br><br>";
	$goster .= menuyap_return("tekbuton", "yolla gitsin", "#", "document.dosyagonder.submit();");
	$goster .= "</form>";
}
elseif ($nedir == "yukle")
{
	if ($zatenvar == "")
	{
		$gecicidosya = $HTTP_POST_FILES["dosya"]["tmp_name"];
		$dosyaismi = $HTTP_POST_FILES["dosya"]["name"];
		$dosyabuyuklugu = $HTTP_POST_FILES["dosya"]["size"];
		$dosyaformati = $HTTP_POST_FILES["dosya"]["type"];
	}

	$tamklasor = $klasor."/";
	$adres = $tamklasor.$dosyaismi;

	if (file_exists($adres) && $zatenvar == "")
	{
		$goster = "<form method=\"post\" action=\"dosyagonder.php?nedir=yukle&zatenvar=1\" name=\"dosyagonder\">";
		$goster .= "<input type=\"hidden\" name=\"klasor\" value=\"$klasor\">";
		$goster .= "<input type=\"hidden\" name=\"gecicidosya\" value=\"$gecicidosya\">";
		$goster .= "<input type=\"hidden\" name=\"dosyaismi\" value=\"$dosyaismi\">";
		$goster .= "<input type=\"hidden\" name=\"dosyaformati\" value=\"$dosyaformati\">";
		$goster .= "<input type=\"hidden\" name=\"dosyabuyuklugu\" value=\"$dosyabuyuklugu\">";
		$goster .= "<input type=\"hidden\" name=\"hedef\" value=\"$hedef\">";
		$goster .= "<h2>dosya gonder</h2><br>";
		$goster .= "<b>boyle bir dosya varmis zaten, sileyim mi eskisini?</b><br><br>";
		$goster .= menuyap_return("tekbuton", "sil gitsin", "#", "document.dosyagonder.submit();");
		$goster .= menuyap_return("tekbuton", "vazcaydim", "#", "history.go(-1);");
		$goster .= "</form>";
	}
	else
	{	
		move_uploaded_file($gecicidosya, $adres);
		
		$genislik = "400";
	
		$resimbuyuklugu = getimagesize($adres);
		
		if ($resimbuyuklugu[0] > $genislik)
		{
			if ($resimbuyuklugu[2] == "1")
			{
				$eskidosya = ImageCreateFromGIF($adres);
			}
			elseif ($resimbuyuklugu[2] == "2")
			{
				$eskidosya = ImageCreateFromJPEG($adres);
			}
			
			$yukseklik = floor(($resimbuyuklugu[1] * $genislik) / $resimbuyuklugu[0]);
			
			$yeniresim = ImageCreate($genislik, $yukseklik);
			ImageCopyResized($yeniresim, $eskidosya, 0, 0, 0, 0, $genislik, $yukseklik, ImageSX($eskidosya), ImageSY($eskidosya));

			if ($resimbuyuklugu[2] == "1")
			{
				ImagePNG($yeniresim, $adres);
			}
			elseif ($resimbuyuklugu[2] == "2")
			{
				ImageJPEG($yeniresim, $adres, 75);
			}

			ImageDestroy($yeniresim);
		}
		
		$resimbuyuklugu = getimagesize($adres);

		$ekle = "<img src=\"$adres\" width=\"$resimbuyuklugu[0]\" height=\"$resimbuyuklugu[1]\"><br>";
		
		$script = "<script language=\"JavaScript\">
		
		function kodEkleyen() 
		{
			var file = '$ekle';
		
			if (document.bilgiekle.hedef.value==\"entri\")
			{
				self.opener.document.ekle.entri.value += file +\" \";
			}
			else if (document.bilgiekle.hedef.value==\"daha\")
			{
				self.opener.document.ekle.daha.value += file +\" \";
			}
		}
		</script>";

		$goster .= "<h2>dosya gonder</h2><br>";
		$goster .= "<form name=\"bilgiekle\" method=\"post\">";
		$goster .= "<input type=\"hidden\" name=\"hedef\" value=\"$hedef\">";
		$goster .= "<img src=\"$adres\"><br><br>";
		$goster .= "dosyayi yukledim, forma eklemek icin asagiya tiklayiver.<br><br>";
		$goster .= menuyap_return("tekbuton", "ekle bakalim", "#", "kodEkleyen();window.close();");
		$goster .= "</form></td></tr></table>";
	}
}

$baslik = "dosya gonder";

?>

<html>
<head>
<title>orangeblog :: <?php echo $baslik; ?></title>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=ISO-8859-9"> 
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1254">
<link rel="icon" href="<?php echo $path; ?>images/orange_icon.gif">
<?php echo $script; ?>
<link rel="stylesheet" type="text/css" href="<?php echo $path."tema/".$tema; ?>.css">
</head>

<body>

<? echo $goster; ?>

</body>
</html>