<?php

$path = "./";

include("db/db.mysql.php");

if ($baslik == "")
{
	$baslik = "bakiniz aparati";
	
	$sonuc = "<h2>bakiniz aparati</h2>";
	$sonuc .= "olmadi, olamadi, bakinizda bir hata var.";
}
else
{
	$baslik = temizlikciteyze($baslik);
	
	$sql = "select id from ob_blog where baslik = '$baslik'";
	$query = new DB_query($db, $sql);
	$varmi = $query->db_num_rows();
	
	if ($varmi > 0)
	{
		$query->db_fetch_object();
		
		$id = $query->obj->id;
	
		return header("location:daha.php?id=$id");
	}
	else
	{
		$arama = explode(" ", $baslik);
		
		foreach ($arama as $kelime)
		{
			if (strlen($kelime) >= "3")
			{
				$eklenti .= " baslik like '%$kelime%' or";
			}
		}

		$eklenti = substr($eklenti, 0, -3);

		$sql = "select id,baslik from ob_blog where".$eklenti." limit 10";
		$query = new DB_query($db, $sql);
		$ara = $query->db_num_rows();

		if ($ara > 0)
		{
			$goster = "oyle bi baslik yok arkadasim, kandirmislar seni. ama soyle biseyler var istersen, aha al.<br><br>";
		
			while ($query->db_fetch_object())
			{
				$araid = $query->obj->id;
				$arabaslik = $query->obj->baslik;
				
				$goster .= "<a href=\"#\" onClick=\"self.window.opener.location='yorumlar.php?id=$araid';window.close();\">$arabaslik</a><br>";
			}
		}
		else
		{
			$goster = "veritabanimda boyle bisey varsa iki gozum onume aksin, bulamadim.<br><br>";
		}
		
		unset($sql);
		unset($query);
		
		$sonuc = "<h2>$baslik</h2>";
		$sonuc .= $goster;
		$sonuc .= "<br>";
		$sonuc .= menuyap_return("baslat");
		$sonuc .= menuyap_return("menu", "ben yazacam bunu", "#", "self.window.opener.location='getur.php?baslik=$baslik';window.close();");
		$sonuc .= menuyap_return("menu", "kapa beni", "#", "window.close();");
		$sonuc .= menuyap_return("bitir");
	}
}

?>

<html>
<head>
<title>orangeblog :: <?php echo $baslik; ?></title>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=ISO-8859-9"> 
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1254">
<link rel="icon" href="<?php echo $path; ?>images/orange_icon.gif">
<script language="JavaScript" src="<?php echo $path; ?>kodaman.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $path."tema/".$tema; ?>.css">
</head>

<body>

<div id="content">

<?php

echo $sonuc;

?>

</div>

</body>
</html>