<?
include_once("db/db.mysql.php");

$sql = "SELECT * FROM ob_anket WHERE id = '$pollid'";
$query = new DB_query($db, $sql);
$query->db_fetch_object();

$pollid = $query->obj->id;
$numpollvotes = $query->obj->votes;
$pollquestion = $query->obj->question;
$pollname = $query->obj->name;
$pollbgcolor = $query->obj->bgcolor;

unset($sql);
unset($query);

if ($_COOKIE[nabizolcer.$pollid] == '')
{
	$sql = "SELECT * FROM ob_anketsec WHERE pollid = '$pollid' ORDER BY id";
	$query = new DB_query($db, $sql);
	$numanswers = $query->db_num_rows();
	
	$pollanswers = "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\">";
	
	if ($numanswers >= "1")
	{
		while ($query->db_fetch_object())
		{
			$id = $query->obj->id;
			$choice = $query->obj->choice;
			
			$pollanswers .= "<tr>";
			
			$pollanswers .= "<td width=\"5\" align=\"center\"><input type=\"radio\" name=\"aid\" value=\"$id\" onClick=\"document.poll".$pollid.".haid.value='".$id."';\"></td>";
			$pollanswers .= "<td align=\"left\">$choice</td>";
			
			$pollanswers .= "</tr>"; 
		}
	}
	else
	{
		$pollanswers = "<tr><td align=\"center\">Bu anket için seçenek girilmemiş.</td></tr>";
	}
	
	$pollanswers .= "</table>";
	
	echo "<script language=\"JavaScript\">
	
	function vote(pollid,choiceid)
	{
		var url = \"{$simplepollurl}anket_oyla.php?pollid=\"+pollid+\"&aid=\"+choiceid;
		
		return pencere(url,'oyla',350,475,50,50);
	}
	
	function viewresults(pollid)
	{
		var url = \"{$simplepollurl}anket_sonuc.php?pollid=\"+pollid;
		
		return pencere(url,'sonuclar',350,475,50,50);
	}
	
	</script>";

	echo "<table width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
	echo "<tr valign=\"top\"><td width=\"100%\" align=\"left\">";
	echo "<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">";
	echo "<form name=\"poll{$pollid}\"><input type=\"hidden\" name=\"haid\" value=\"\"><tr><td>";
	echo "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\"><tr>";
	echo "<td align=\"left\"><h4>nabiz olcer :: $pollname</h4><br></td></tr><tr><td>";
	echo $pollquestion;
	echo "<br><br></td></tr><tr><td>";
	echo $pollanswers;
	echo "<br></td></tr><tr><td align=\"left\">";
	
	menuyap("baslat");
	menuyap("menu", "aha!", "#", "vote('$pollid',document.poll{$pollid}.haid.value)");
	menuyap("menu", "sonuclar", "#", "viewresults('$pollid')");
	menuyap("bitir");
	
	echo "</td></tr></table></td></tr></form></table></td></tr></table>";
}
else
{
	$sql = "SELECT * FROM ob_anketsec WHERE pollid = '$pollid' order by votes desc";
	$query = new DB_query($db, $sql);
	
	while ($query->db_fetch_object())
	{
		$votes = $query->obj->votes;
		$choice = $query->obj->choice;
	
		$width = ($votes/$numpollvotes)*120;
		$percentage = ($votes/$numpollvotes)*100;
		$point = explode(".",$percentage);
		$units = $point[0];
		$decimals = $point[1];
		$count = count($point);
		
		if ($count > "1")
		{
			$len = strlen ($decimals);
			$decimals = substr_replace($decimals, '', 1, $len);
			$percentage = "$units.$decimals";
		}
		
		$pollresult .= "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\">";
		$pollresult .= "<tr><td width=\"79%\">$choice ($votes oy)</td>";
		$pollresult .= "<tr><td><table border=\"0\" cellspacing=\"0\" cellpadding=\"0\"><tr>";
		$pollresult .= "<td class=\"anketseysi\"><img src=\"".$simplepollurl."/trans.gif\" width=\"".$width."\" height=\"8\"></td>";
		$pollresult .= "</tr></table></td></tr></table>";
	}
	
	unset($sql);
	unset($query);
	
	eval("dooutput(\"".gettemplate(templatedir,"small_results")."\");");
}

?>