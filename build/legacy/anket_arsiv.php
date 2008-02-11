<?php

$path = "./";
$title = "nabiz olcer arsivi";

include ("db/db.mysql.php");
include ("left.inc.php");

$simplepollurl = simplepollurl;

if ($basla=="")
{
	$basla = "0";
}

$limit = "5";

$sql = "select id from ob_anket";
$query = new DB_query($db, $sql);
$total = $query->db_num_rows();

unset($sql);
unset($query);

$son = $total - $limit;
$sonraki = $basla + $limit;
$onceki = $basla - $limit;
$topsayfa = ceil($total/$limit);
$cursayfa = ceil($sonraki/$limit);

if ($cursayfa == "2")
{
	$onceki = "0";
}
else
{
	$onceki = $basla - $limit;
}

$gezbar = "<table width=\"100%\" align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td align=\"right\">";

if ($onceki >= 0)
{
	$gezbar .= "<a href=\"anket_arsiv.php?basla=0\">ilk sayfa</a> | ";
	$gezbar .= "<a href=\"anket_arsiv.php?basla=$onceki\">onceki sayfa</a> | ";
}

if ($sonraki < $total)
{
	$gezbar .= "<a href=\"anket_arsiv.php?basla=$sonraki\">sonraki sayfa</a> | ";
	$gezbar .= "<a href=\"anket_arsiv.php?basla=$son\">son sayfa</a> | ";
}

$gezbar .= "sayfa $cursayfa / $topsayfa";
$gezbar .= "</td></tr></table>";

echo $gezbar;
echo "<br>";

$sql = "select * from ob_anket order by id desc limit $basla,$limit";
$query = new DB_query($db, $sql);

while ($query->db_fetch_object())
{
	$pollid = $query->obj->id;
	$numpollvotes = $query->obj->votes;
	$pollquestion = $query->obj->question;
	$pollname = $query->obj->name;
	
	echo "<br><div class=\"weblog\">";

	$s = "SELECT * FROM ob_anketsec WHERE pollid = '$pollid' order by votes desc";
	$q = new DB_query($db, $s);
	
	while ($q->db_fetch_object())
	{
		$votes = $q->obj->votes;
		$choice = $q->obj->choice;
	
		$width = ($votes/$numpollvotes)*330;
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
		
		$arpollresult .= "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\">";
		$arpollresult .= "<tr><td width=\"79%\">$choice</td>";
		$arpollresult .= "<td width=\"21%\" align=\"center\">$votes</td></tr>";
		$arpollresult .= "<tr><td><table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
		$arpollresult .= "<tr><td width=\"10\"><img src=\"".$simplepollurl."/trans.gif\" width=\"1\" height=\"1\"></td>";
		$arpollresult .= "<td class=\"anketseysi\"><img src=\"".$simplepollurl."/trans.gif\" width=\"".$width."\" height=\"8\"></td>";
		$arpollresult .= "</tr></table></td>";
		$arpollresult .= "<td align=\"center\">%".$percentage."</td></tr>";
		$arpollresult .= "<tr><td><img src=\"".$simplepollurl."/trans.gif\" width=\"8\" height=\"8\"></td>";
		$arpollresult .= "<td align=\"center\"><img src=\"".$simplepollurl."/trans.gif\" width=\"1\" height=\"1\"></td>";
		$arpollresult .= "</tr></table>";
	}
	
	unset($s);
	unset($q);
	
	eval("dooutput(\"".gettemplate(templatedir,"arsiv")."\");");
	
	$arpollresult = "";
	
	echo "</div>";
}

unset($sql);
unset($query);

echo $gezbar;

include ("right.inc.php");

?>