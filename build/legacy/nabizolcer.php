<?php

$path = "./";

include("db/db.mysql.php");

if ($loginyazarstatu != "9")
{
	return header("location:index.php");
}

$title = "nabiz olcer";

include ("left.inc.php");

echo "<div align=\"center\">";
menuyap("baslat");
menuyap("menu", "uyeler", "admin.php");
menuyap("menu", "nabiz olcer", "nabizolcer.php");
menuyap("menu", "sinsi editor", "sinsieditor.php");
menuyap("bitir");
echo "</div>";

echo "<h2>admin atraksiyonlari :: nabiz olcer</h2>";

$simplepollurl = simplepollurl;

$subaction   = $_REQUEST['subaction'];
$action      = $_REQUEST['action'];
$name        = $_REQUEST['name'];
$question    = $_REQUEST['question'];
$pollid      = $_REQUEST['pollid'];
$choice      = $_REQUEST['choice'];

switch($subaction)
{
	case "add":
	$sql = "INSERT INTO ob_anket VALUES (NULL,'$name','$question','0')";
	$query = new DB_query($db, $sql);
	break;
	case "modify":
	$sql = "UPDATE ob_anket SET name = '$name', question = '$question' WHERE id = '$pollid'";
	$query = new DB_query($db, $sql);
	break;
	case "addchoice":
	$sql = "INSERT INTO ob_anketsec VALUES (NULL,'$pollid','$choice','0')";
	$query = new DB_query($db, $sql);
	break;
}

switch($action)
{
	case "modify":

	$sql = "SELECT * FROM ob_anket WHERE id = '$pollid'";
	$query = new DB_query($db, $sql);
	$query->db_fetch_object();
	
	$pollid = $query->obj->id;
	$question = $query->obj->question;
	$name = $query->obj->name;
	$bgcolor = $query->obj->bgcolor;

	eval("storeoutput(\"".gettemplate(templatedir,"admin_input")."\");");

	break;
	case "delete":
	$sql = "DELETE FROM ob_anket WHERE id = '$pollid' LIMIT 1";
	$query = new DB_query($db, $sql);
	break;
	case "addchoice":
	$sql = "SELECT * FROM ob_anket WHERE id = '$pollid'";
	$query = new DB_query($db, $sql);
	$query->db_fetch_object();
	
	$question = $query->obj->question;

	eval("storeoutput(\"".gettemplate(templatedir,"admin_choice")."\");");

	break;
	case "add":
	eval("storeoutput(\"".gettemplate(templatedir,"admin_input")."\");");
	break;
	default:
	
	if ($basla=="")
	{
		$basla = "0";
	}
	
	$alter01 = "yaziaciksira";
	$alter02 = "yazikoyusira";
	$row_count = 0;

	$sql = "SELECT * FROM ob_anket ORDER BY id DESC LIMIT $basla,5";
	$query = new DB_query($db, $sql);
	
	while ($query->db_fetch_object())
	{
		$pollid = $query->obj->id;
		$pollquestion = $query->obj->question;
		$pollname = $query->obj->name;
		$bgcolor = $query->obj->bgcolor;
		
		$row_colour = ($row_count % 2) ? $alter01 : $alter02;

		$s = "SELECT * FROM ob_anketsec WHERE pollid = '$pollid' ORDER BY id";
		$q = new DB_query($db, $s);
		$numanswers = $q->db_num_rows();
		
		$pollanswers = "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\">";

		if ($numanswers >= "1")
		{
			while ($q->db_fetch_object())
			{
				$cid = $q->obj->id;
				$choice = $q->obj->choice;
			
				$pollanswers .= "<tr>";
				
				$pollanswers .= "<td width=\"5\" align=\"center\"><input type=\"radio\" name=\"aid\" value=\"$cid\" onClick=\"document.poll".$pollid.".haid.value='".$cid."';\"></td>";
				$pollanswers .= "<td align=\"left\">$choice</td>";
				
				$pollanswers .= "</tr>";
			}
		}
		else
		{
			$pollanswers .= "<tr><td align=\"center\">Bu anket için seçenek girilmemiş.</td></tr>";
		}

		$pollanswers .= "</table>";
		
		eval("storeoutput(\"".gettemplate(templatedir,"poll")."\");");
		
		$row_count++;
	}

	break;
}

eval("dooutput(\"".gettemplate(templatedir,"admin_poll")."\");");

include ("right.inc.php");

?>