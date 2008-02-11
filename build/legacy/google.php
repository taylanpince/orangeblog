<?php

$path = "./";

include("db/db.mysql.php");

if (!isset($HTTP_COOKIE_VARS['member_code']))
{
	return header("location:giris.php?nedir=form&hata=4&hedef=google.php");
}

if ($nedir == "")
{
	$title = "blografya";
	$google = 1;

	include("left.inc.php");
	
	if ($hata == "1")
	{
		$hata = "<div class=\"hata\">alanlari bos birakma be guzelim, fitik ettiniz beni ya.<br><br></div>";
	}
	elseif ($hata == "2")
	{
		$hata = "<div class=\"hata\">bu koordinatlarda bir seyler var zaten, zorlama beni.<br><br></div>";
	}
	elseif ($hata == "3")
	{
		$hata = "<div class=\"tamam\">ahanda ekledim.<br><br></div>";
	}
	elseif ($hata == "4")
	{
		$hata = "<div class=\"tamam\">sildim gitti, gecmis olsun.<br><br></div>";
	}
	else
	{
		$hata = "";
	}
	
	echo "<h2>blografya</h2>$hata";
	echo "kullanim seysi: haritayi istedigin noktada ortala, isim ver, ekle, mutlu olacaksin.<br><br>";
	echo "<div class=\"weblog\"><form method=\"post\" action=\"google.php?nedir=ekle\" name=\"blografya\">";
	
?>

<div id="map" style="width: 420; height: 400px"></div>

<script type="text/javascript">
//<![CDATA[

var map = new GMap(document.getElementById("map"));
map.setMapType(G_SATELLITE_TYPE);

map.addControl(new GLargeMapControl ());
map.addControl(new GMapTypeControl());

GEvent.addListener(map, "moveend", function()
{
	var center = map.getCenterLatLng();
	document.getElementById("latitude").value = center.x;
	document.getElementById("longitude").value = center.y;
});

function createMarker(point, name)
{
	var marker = new GMarker(point);
	
	GEvent.addListener(marker, "click", function()
	{
		marker.openInfoWindowHtml(name);
	});
	
	return marker;
}

function gotoCoordinates(latitude, longitude, zoom)
{
	if (zoom == "" || zoom == null)
	{
		zoom = 2;
	}

	map.centerAndZoom(new GPoint(latitude, longitude), zoom);
}

<?php

	$sql = "select * from ob_google order by id asc";
	$query = new DB_query($db, $sql);
	$toplam = $query->db_num_rows();
	
	unset($sql);
	unset($query);
	
	if (($latitude == "" || $longitude == "") && $toplam > 0)
	{
		$sql = "select latitude,longitude from ob_google order by rand() limit 1";
		$query = new DB_query($db, $sql);
		$query->db_fetch_object();
		
		$latitude = $query->obj->latitude;
		$longitude = $query->obj->longitude;
		
		unset($sql);
		unset($query);
	}
	elseif ($latitude == "" || $longitude == "")
	{
		$latitude = 28.98;
		$longitude = 41.05;
	}
	
	if ($toplam > 0)
	{
		$pointArray = "var pointArray = [";
	
		$sql = "select * from ob_google order by id asc";
		$query = new DB_query($db, $sql);
	
		while ($query->db_fetch_object())
		{
			$lat = $query->obj->latitude;
			$lng = $query->obj->longitude;
			$nam = $query->obj->name;
			$usr = $query->obj->userid;
			
			$s = "select isim from ob_uyeler where id = '$usr'";
			$q = new DB_query($db, $s);
			$q->db_fetch_object();
			
			$usrnam = $q->obj->isim;
			
			unset($s);
			unset($q);
			
			echo "var point = new GPoint('$lat', '$lng');\r\n";
			echo "var marker = createMarker(point, '$nam ($usrnam)');\r\n";
			echo "map.addOverlay(marker);\r\n\r\n";
			
			$pointArray .= "[$lat, $lng], ";
		}
		
		unset($sql);
		unset($query);
		
		$pointArray = substr($pointArray, 0, -2);
		
		$pointArray .= "];\r\n\r\n";
		
		echo $pointArray;
	}
	
	unset($sql);
	unset($query);
	
	echo "gotoCoordinates('$latitude', '$longitude', '3');";

?>

//]]>
</script>

<br>
<input type="text" name="latitude" id="latitude" value="<?php echo $latitude ?>"> 
<input type="text" name="longitude" id="longitude" value="<?php echo $longitude ?>"> 
<input type="button" value="git be" onClick="gotoCoordinates(document.getElementById('latitude').value, document.getElementById('longitude').value);"><br>

<?php

	$sql = "select * from ob_google order by id asc";
	$query = new DB_query($db, $sql);
	$toplam = $query->db_num_rows();
	
	if ($toplam > 0)
	{
		echo "<select name=\"secmeceGoogle\" class=\"pulldown\" onChange=\"if(this.value!=''){gotoCoordinates(pointArray[this.value][0], pointArray[this.value][1], '1');}\">";
		echo "<option value=\"\">secmece bunlar</option>";
		
		$i = 0;
		
		while ($query->db_fetch_object())
		{
			$lat = $query->obj->latitude;
			$lng = $query->obj->longitude;
			$nam = $query->obj->name;
			
			echo "<option value=\"$i\"";
			
			if ($lat == $latitude && $lng == $longitude)
			{
				echo " selected";
			}
			
			echo ">$nam</option>";
			
			$i++;
		}
		
		echo "</select>";
	}
	
	unset($sql);
	unset($query);
	
	echo "</div>";
	
	echo "<h2>blografya editörü</h2>";
	echo "<div class=\"weblog\">";
	echo "<h4>ne isim verelim?</h4>";
	echo "<input type=\"text\" name=\"isim\" size=\"20\" maxlength=\"20\"><br><br>";

	menuyap("tekbuton", "ne senaylar gorduk biz be", "#", "document.blografya.submit();");

	echo "</div></form>";

	if ($loginyazarstatu >= 7)
	{
		$sql = "select * from ob_google order by name asc";
	}
	else
	{
		$sql = "select * from ob_google where userid = '$loginyazarid' order by name asc";
	}
	
	$query = new DB_query($db, $sql);
	$toplam = $query->db_num_rows();
	
	if ($toplam > 0)
	{
		echo "<form method=\"post\" action=\"google.php?nedir=sil\" name=\"silecek\">";
		echo "<h2>blografya silecegi</h2>";
		echo "<div class=\"weblog\">";
		echo "<select name=\"entri\" class=\"pulldown\">";
		
		while ($query->db_fetch_object())
		{
			$id = $query->obj->id;
			$nam = $query->obj->name;
			
			echo "<option value=\"$id\">$nam</option>";
		}
		
		echo "</select><br><br>";
	
		menuyap("tekbuton", "sil sen bunu", "#", "document.silecek.submit();");
	
		echo "</div></form>";
	}
	
	unset($sql);
	unset($query);
	
	include("right.inc.php");
}
elseif ($nedir == "ekle")
{
	if ($isim == "" || $latitude == "" || $longitude == "")
	{
		return header("location:google.php?hata=1");
	}
	
	$isim = temizlikciteyze($isim);
	
	$sql = "select id from ob_google where latitude = '$latitude' and longitude = '$longitude'";
	$query = new DB_query($db, $sql);
	$toplam = $query->db_num_rows();

	unset($sql);
	unset($query);
	
	if ($toplam > 0)
	{
		return header("location:google.php?hata=2");
	}

	$sql = "insert into ob_google (name,latitude,longitude,date,userid) values ('$isim','$latitude','$longitude','$simdi','$loginyazarid')";
	$query = new DB_query($db, $sql);
	
	unset($sql);
	unset($query);
	
	return header("location:google.php?hata=3&latitude=$latitude&longitude=$longitude");
}
elseif ($nedir == "sil")
{
	if ($entri == "")
	{
		return header("location:google.php?hata=1");
	}
	
	$sql = "select userid from ob_google where id = '$entri'";
	$query = new DB_query($db, $sql);
	$query->db_fetch_object();
	
	$userid = $query->obj->userid;
	
	unset($sql);
	unset($query);
	
	if ($userid <> $loginyazarid && $loginyazarstatu < 7)
	{
		return header("location:google.php?hata=1");
	}
	
	$sql = "delete from ob_google where id = '$entri'";
	$query = new DB_query($db, $sql);
	
	unset($sql);
	unset($query);
	
	return header("location:google.php?hata=4");
}

?>