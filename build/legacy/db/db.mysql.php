<?php

class DB 
{
	var $Hostname  = "localhost";
	var $Username  = "orangeblog";
	var $Password  = "o8FblEyCmN0d";
	var $Database  = "orangeblog";
	var $Connect;
    var $DBselect  = 1;
    var $ShowError = 0;
    var $Selected;
	

	function DB($host="", $user="", $pass="", $db="", $dbselect="")
	{
		global $hostname, $dbusername, $dbpassword, $dbname, $conntype, $debug;
	
		if ($host <> "" || $hostname <> "")
		{
			$host <> "" ? $this->Hostname = $host : $this->Hostname = $hostname;
		}
		if ($user <> "" || $dbusername <> "")
		{
			$user <> "" ? $this->Username = $user : $this->Username = $dbusername;
		}	
		if ($pass <> "" || $dbpassword <> "")
		{
			$pass <> "" ? $this->Password = $pass : $this->Password = $dbpassword;
		}	
		if ($db <> ""	|| $dbname <> "")
		{
			$db <> "" ? $this->Database = $db : $this->Database = $dbname;
		}
		if ($dbselect	<> "") $this->DBselect  = $dbselect;
		if ($debug		<> "") $this->ShowError = $debug;
			
			
		if ($conntype ==0)
		{
			$this->Connect = @mysql_connect($this->Hostname,$this->Username,$this->Password);
			mysql_query("SET NAMES 'utf8'", $this->Connect);
        } 
        else 
        {
			$this->Connect = @mysql_pconnect($this->Hostname,$this->Username,$this->Password);
			mysql_query("SET NAMES 'utf8'", $this->Connect);
        }
        
        
		if (!$this->Connect)
		{
		    if ($this->ShowError == 1)
		    {
			    return $this->db_error("Database connection failed. Please check your config settings.");
		    }
		    return false;
		}
		
		
		if ($this->DBselect == 1)
		{	
    		if (!@mysql_select_db($this->Database,$this->Connect))
    		{
        		if ($this->ShowError == 1)
    		    {
                    return $this->db_error("MySQL Error: ",$this->Connect);
    		    }
    		    return false;
    		}
    		
		    return $this->Selected = $this->Database;	
		}
	}
    
    
    
    function db_select()
    { 
		if (!@mysql_select_db($this->Database,$this->Connect))
		{
            if ($this->ShowError == 1)
    		{
    		    return $this->db_error("MySQL Error: ",$this->Connect);
    		}
    		return false;
		} 
		
		return $this->Selected = $this->Database;	
    }
    
    
    
    function db_create()
    {
        if (!@mysql_create_db($this->Database))
    	{
            if ($this->ShowError == 1)
            {
                return $this->db_error("Unable to create the database specified in your config file.");
            }
            return false;
        } 
        
        return true;
    }



	function db_close()
	{
		mysql_close($this->Connect);
	} 
    
    
    
	function db_error($message,$id="") 
	{			
		if ($id) 
		{ 
			$message .= mysql_errno($id) . "&nbsp;&nbsp;" . mysql_error($id); 
		}
		echo "<br />" . $message . "<br /><br />";
	}
	
}


class DB_query
{
	var $query = 0;
	var $row = array();
	var $obj;
	var $res;
	var $numrows;

    var $rows   = 0;
    var $errors = 0;
    var $result = false;
   
    
	function DB_query($db, $sql)
	{
	    global $debug;
	    
	    if ($debug <> "") $this->errors = $debug;
  
	    if ($db->Connect <> false)
	    {
    		if (!$this->query = @mysql_query($sql))
    		{
    		    if ($this->errors == 1)
    		    {
    			    return $this->db_query_error("MySQL Error: ",$db->Connect);
    		    }
    		    
			 return $this->result;
    		}
	    }

        return $this->result = true;
	}
    
    
	function db_fetch_row()
	{
		return $this->row = @mysql_fetch_row($this->query);
	}
    
    	
	function db_fetch_array()
	{
		return $this->row = @mysql_fetch_array($this->query);
	}


	function db_fetch_object()
	{
		return $this->obj = @mysql_fetch_object($this->query);
	}


	function db_num_rows()
	{
		return $this->numrows = @mysql_num_rows($this->query);
	}

    
	function db_result($x=0,$y="")
	{
		return $this->res = @mysql_result($this->query, $x, $y);
	}
    
    
	function db_free_result()
	{		
        @mysql_free_result($this->query);
	} 
    
        
	function db_query_error($message,$id="") 
	{
		if ($id) 
		{ 
			$message .= mysql_errno($id) . "&nbsp;&nbsp;" . mysql_error($id); 
		}
		echo "<br />" . $message . "<br /><br />";		
	}
}
// END

//	---------------------------------------------
//		URL ENCODE
//	---------------------------------------------

function encode($str) 
{
    $str = " " . $str . " ";
        
    $str = str_replace("target=\"_blank\"","",$str);    
    $str = str_replace("target=\"_top\"","",$str);    

 	// Encode URLs - Step 1 - links with quotes
	$str = preg_replace("/<a\s*href\s*=\s*\"\s*(.*?)\"\s*\>(.*?)<\/a>/si","[url=\\1]\\2[/url]",$str);
	
 	// Encode URLs - Step 2 - links with no quotes
	$str = preg_replace("/<a\s*href\s*=\s*\s*(.*?)\s*\>(.*?)<\/a>/si","[url=\\1]\\2[/url]",$str);

    // URLs with extra junk in them
    $str = preg_replace("/<a href=\"(.*^\$!)\"\>(.*^\$!)<\/a>/i","[url=\\1]\\2[/url]",$str);
    
    // Clear period from end of URLs
    $str = preg_replace("/(\s)((http(s?):\/\/)|(www\.)+)([\w\.]+)([\/\w+\.]+)([\S*]+)([.]+)(\s)/i","\\1\\2\\6\\7\\8\\10", $str);
    
    // Format stand-alone URLs (HTTP)
    $str = preg_replace("/(\s)((http(s?):\/\/)|(www\.)+)([\w\.]+)([\/\w+\.]+)([\S*]+)/i","\\1[url=http\\4://\\5\\6\\7\\8]\\2\\6\\7\\8[/url]", $str);
    
    // Format stand-alone URLs (FTP)
    $str = preg_replace("/(\s)((ftp(s?):\/\/)|(ftp\.)+)([\w\.]+)([\/\w+\.]+)([\S*]+)/i","\\1[url=ftp\\4://\\5\\6\\7\\8]\\2\\6\\7\\8[/url]", $str);
    
    // Clear period from end of email addresses
    $str = preg_replace("/(\s)([a-zA-Z0-9_\.\-]+)@([a-zA-Z0-9\-]+)\.([a-zA-Z0-9\-\.]*)([.]+)(\s)/i","\\1\\2@\\3.\\4\\6",$str);
    
    // Format stand-along email address
    $str = preg_replace("/(\s)([a-zA-Z0-9_\.\-]+)@([a-zA-Z0-9\-]+)\.([a-zA-Z0-9\-\.]*)/i","\\1[email=\\2@\\3.\\4]\\2@\\3.\\4[/email]",$str);
        
    $str = substr($str,1);
    $str = substr($str, 0, -1);

	return $str;
}
// END

//	---------------------------------------------
//		SOHBET ENCODE
//	---------------------------------------------

function sohbet_encode($str) 
{
    $str = " " . $str . " ";
        
    $str = str_replace("target=\"_blank\"","",$str);    
    $str = str_replace("target=\"_top\"","",$str);    

 	// Encode URLs - Step 1 - links with quotes
	$str = preg_replace("/<a\s*href\s*=\s*\"\s*(.*?)\"\s*\>(.*?)<\/a>/si","[url=\\1]URL[/url]",$str);
	
 	// Encode URLs - Step 2 - links with no quotes
	$str = preg_replace("/<a\s*href\s*=\s*\s*(.*?)\s*\>(.*?)<\/a>/si","[url=\\1]URL[/url]",$str);

    // URLs with extra junk in them
    $str = preg_replace("/<a href=\"(.*^\$!)\"\>(.*^\$!)<\/a>/i","[url=\\1]URL[/url]",$str);
    
    // Clear period from end of URLs
    $str = preg_replace("/(\s)((http(s?):\/\/)|(www\.)+)([\w\.]+)([\/\w+\.]+)([\S*]+)([.]+)(\s)/i","\\1\\2\\6\\7\\8\\10", $str);
    
    // Format stand-alone URLs
    $str = preg_replace("/(\s)((http(s?):\/\/)|(www\.)+)([\w\.]+)([\/\w+\.]+)([\S*]+)/i","\\1[url=http\\4://\\5\\6\\7\\8]URL[/url]", $str);
    
    // Format stand-alone URLs
    $str = preg_replace("/(\s)((ftp(s?):\/\/)|(ftp\.)+)([\w\.]+)([\/\w+\.]+)([\S*]+)/i","\\1[url=ftp\\4://\\5\\6\\7\\8]URL[/url]", $str);
    
    // Clear period from end of email addresses
    $str = preg_replace("/(\s)([a-zA-Z0-9_\.\-]+)@([a-zA-Z0-9\-]+)\.([a-zA-Z0-9\-\.]*)([.]+)(\s)/i","\\1\\2@\\3.\\4\\6",$str);
    
    // Format stand-along email address
    $str = preg_replace("/(\s)([a-zA-Z0-9_\.\-]+)@([a-zA-Z0-9\-]+)\.([a-zA-Z0-9\-\.]*)/i","\\1[email=\\2@\\3.\\4]E-Posta[/email]",$str);
        
    $str = substr($str,1);
    $str = substr($str, 0, -1);

	return $str;
}
// END


//	---------------------------------------------
//		SMILEY ENCODE
//	---------------------------------------------

function smiley($msg,$tema="orange") 
{
	$msg = str_replace("o:-)", "<IMG src=\"./tema/".$tema."_smiley/angel.gif\">", $msg);
	$msg = str_replace("o:)", "<IMG src=\"./tema/".$tema."_smiley/angel.gif\">", $msg);
	$msg = str_replace("O:-)", "<IMG src=\"./tema/".$tema."_smiley/angel.gif\">", $msg);
	$msg = str_replace("O:)", "<IMG src=\"./tema/".$tema."_smiley/angel.gif\">", $msg);
	$msg = str_replace(":-))))", "<IMG src=\"./tema/".$tema."_smiley/smile.gif\">", $msg);
	$msg = str_replace(":-)))", "<IMG src=\"./tema/".$tema."_smiley/smile.gif\">", $msg);
	$msg = str_replace(":-))", "<IMG src=\"./tema/".$tema."_smiley/smile.gif\">", $msg);
	$msg = str_replace(":-)", "<IMG src=\"./tema/".$tema."_smiley/smile.gif\">", $msg);
	$msg = str_replace(":))))", "<IMG src=\"./tema/".$tema."_smiley/smile.gif\">", $msg);
	$msg = str_replace(":)))", "<IMG src=\"./tema/".$tema."_smiley/smile.gif\">", $msg);
	$msg = str_replace(":))", "<IMG src=\"./tema/".$tema."_smiley/smile.gif\">", $msg);
	$msg = str_replace(":)", "<IMG src=\"./tema/".$tema."_smiley/smile.gif\">", $msg);
	$msg = str_replace("((((:", "<IMG src=\"./tema/".$tema."_smiley/smile.gif\">", $msg);
	$msg = str_replace("(((:", "<IMG src=\"./tema/".$tema."_smiley/smile.gif\">", $msg);
	$msg = str_replace("((:", "<IMG src=\"./tema/".$tema."_smiley/smile.gif\">", $msg);
	$msg = str_replace("(:", "<IMG src=\"./tema/".$tema."_smiley/smile.gif\">", $msg);
	$msg = str_replace("((((-:", "<IMG src=\"./tema/".$tema."_smiley/smile.gif\">", $msg);
	$msg = str_replace("(((-:", "<IMG src=\"./tema/".$tema."_smiley/smile.gif\">", $msg);
	$msg = str_replace("((-:", "<IMG src=\"./tema/".$tema."_smiley/smile.gif\">", $msg);
	$msg = str_replace("(-:", "<IMG src=\"./tema/".$tema."_smiley/smile.gif\">", $msg);
	$msg = str_replace(":-((((", "<IMG src=\"./tema/".$tema."_smiley/frown.gif\">", $msg);
	$msg = str_replace(":-(((", "<IMG src=\"./tema/".$tema."_smiley/frown.gif\">", $msg);
	$msg = str_replace(":-((", "<IMG src=\"./tema/".$tema."_smiley/frown.gif\">", $msg);
	$msg = str_replace(":-(", "<IMG src=\"./tema/".$tema."_smiley/frown.gif\">", $msg);
	$msg = str_replace(":((((", "<IMG src=\"./tema/".$tema."_smiley/frown.gif\">", $msg);
	$msg = str_replace(":(((", "<IMG src=\"./tema/".$tema."_smiley/frown.gif\">", $msg);
	$msg = str_replace(":((", "<IMG src=\"./tema/".$tema."_smiley/frown.gif\">", $msg);
	$msg = str_replace(":(", "<IMG src=\"./tema/".$tema."_smiley/frown.gif\">", $msg);
	$msg = str_replace(":-\\", "<IMG src=\"./tema/".$tema."_smiley/unsure.gif\">", $msg);
	$msg = str_replace(":\\", "<IMG src=\"./tema/".$tema."_smiley/unsure.gif\">", $msg);
	$msg = str_replace(":-p", "<IMG src=\"./tema/".$tema."_smiley/tongue.gif\">", $msg);
	$msg = str_replace(":p", "<IMG src=\"./tema/".$tema."_smiley/tongue.gif\">", $msg);
	$msg = str_replace(":-P", "<IMG src=\"./tema/".$tema."_smiley/tongue.gif\">", $msg);
	$msg = str_replace(":P", "<IMG src=\"./tema/".$tema."_smiley/tongue.gif\">", $msg);
	$msg = str_replace(";-))))", "<IMG src=\"./tema/".$tema."_smiley/wink.gif\">", $msg);
	$msg = str_replace(";-)))", "<IMG src=\"./tema/".$tema."_smiley/wink.gif\">", $msg);
	$msg = str_replace(";-))", "<IMG src=\"./tema/".$tema."_smiley/wink.gif\">", $msg);
	$msg = str_replace(";-)", "<IMG src=\"./tema/".$tema."_smiley/wink.gif\">", $msg);
	$msg = str_replace(";))))", "<IMG src=\"./tema/".$tema."_smiley/wink.gif\">", $msg);
	$msg = str_replace(";)))", "<IMG src=\"./tema/".$tema."_smiley/wink.gif\">", $msg);
	$msg = str_replace(";))", "<IMG src=\"./tema/".$tema."_smiley/wink.gif\">", $msg);
	$msg = str_replace(";)", "<IMG src=\"./tema/".$tema."_smiley/wink.gif\">", $msg);
	$msg = str_replace(":-*", "<IMG src=\"./tema/".$tema."_smiley/kiss.gif\">", $msg);
	$msg = str_replace(":*", "<IMG src=\"./tema/".$tema."_smiley/kiss.gif\">", $msg);
	$msg = str_replace(":-*", "<IMG src=\"./tema/".$tema."_smiley/kiss.gif\">", $msg);
	$msg = str_replace(":*", "<IMG src=\"./tema/".$tema."_smiley/kiss.gif\">", $msg);
	$msg = str_replace(":-!", "<IMG src=\"./tema/".$tema."_smiley/foot.gif\">", $msg);
	$msg = str_replace(":!", "<IMG src=\"./tema/".$tema."_smiley/foot.gif\">", $msg);
	$msg = str_replace(":'(", "<IMG src=\"./tema/".$tema."_smiley/cry.gif\">", $msg);
	$msg = str_replace(">:-o", "<IMG src=\"./tema/".$tema."_smiley/yell.gif\">", $msg);
	$msg = str_replace(">:o", "<IMG src=\"./tema/".$tema."_smiley/yell.gif\">", $msg);
	$msg = str_replace(">:-O", "<IMG src=\"./tema/".$tema."_smiley/yell.gif\">", $msg);
	$msg = str_replace(">:O", "<IMG src=\"./tema/".$tema."_smiley/yell.gif\">", $msg);
	$msg = str_replace(":-o", "<IMG src=\"./tema/".$tema."_smiley/surprise.gif\">", $msg);
	$msg = str_replace(":o", "<IMG src=\"./tema/".$tema."_smiley/surprise.gif\">", $msg);
	$msg = str_replace(":-O", "<IMG src=\"./tema/".$tema."_smiley/surprise.gif\">", $msg);
	$msg = str_replace(":O", "<IMG src=\"./tema/".$tema."_smiley/surprise.gif\">", $msg);
	$msg = str_replace(":-$", "<IMG src=\"./tema/".$tema."_smiley/money.gif\">", $msg);
	$msg = str_replace(":$", "<IMG src=\"./tema/".$tema."_smiley/money.gif\">", $msg);
	$msg = str_replace(":-X", "<IMG src=\"./tema/".$tema."_smiley/lipssealed.gif\">", $msg);
	$msg = str_replace(":X", "<IMG src=\"./tema/".$tema."_smiley/lipssealed.gif\">", $msg);
	$msg = str_replace(":-x", "<IMG src=\"./tema/".$tema."_smiley/lipssealed.gif\">", $msg);
	$msg = str_replace(":x", "<IMG src=\"./tema/".$tema."_smiley/lipssealed.gif\">", $msg);
	$msg = str_replace("8-)", "<IMG src=\"./tema/".$tema."_smiley/shades.gif\">", $msg);
	$msg = str_replace(":-d", "<IMG src=\"./tema/".$tema."_smiley/grin.gif\">", $msg);
	$msg = str_replace(":d", "<IMG src=\"./tema/".$tema."_smiley/grin.gif\">", $msg);
	$msg = str_replace(":-D", "<IMG src=\"./tema/".$tema."_smiley/grin.gif\">", $msg);
	$msg = str_replace(":D", "<IMG src=\"./tema/".$tema."_smiley/grin.gif\">", $msg);

	return $msg;
}
// END


//	---------------------------------------------
//		HTML DECODE
//	---------------------------------------------

function decode($str,$sohbet="0",$tema="orange") 
{
    $pop = "target=\"_blank\"";
    
    $str = smiley($str, $tema);
    
    // [b] and [/b]
    $str = preg_replace("/\[b\](.*?)\[\/b\]/si","<b>\\1</b>",$str);

    // [strong] and [/strong]
    $str = preg_replace("/\[strong\](.*?)\[\/strong\]/si","<strong>\\1</strong>",$str);

    // [em] and [/em]
    $str = preg_replace("/\[em\](.*?)\[\/em\]/si","<em>\\1</em>",$str);
    
    // [i] and [/i]
    $str = preg_replace("/\[i\](.*?)\[\/i\]/si","<i>\\1</i>",$str);
    
    // [u] and [/u]
    $str = preg_replace("/\[u\](.*?)\[\/u\]/si","<u>\\1</u>",$str);
    
    // [renk=blue] and [/renk]
    $str = preg_replace("/\[renk=(.*?)\](.*?)\[\/renk\]/si","<font color=\"\\1\">\\2</font>",$str);
    
    // [boyut=4] and [/boyut]
    $str = preg_replace("/\[boyut=(.*?)\](.*?)\[\/boyut\]/si","<font size=\"\\1\">\\2</font>",$str);
    
    // [img] and [/img]
    $str = preg_replace("/\[img\](.*?)\[\/img\]/i","<img src=\\1>",$str);
    
	if ($sohbet=="0")
	{
		// [url]http://www.somesite.com[/url]
		$str = preg_replace("/\[url\]http(s?):\/\/(.*?)\[\/url\]/i","<a href=\"http\\1://\\2\" $pop>\\2</a>",$str);
		
		// [url=http://www.somesite.com]somesite[/url]
		$str = preg_replace("/\[url=http(s?):\/\/(.*?)\](.*?)\[\/url\]/i","<a href=\"http\\1://\\2\" $pop>\\3</a>",$str);
		
		// [url]www.somesite.com[/url]
		$str = preg_replace("/\[url\]www.(.*?)\[\/url\]/i","<a href=\"http://www.\\1\" $pop>\\1</a>",$str);
		
		// [url=www.somesite.com]somesite[/url]
		$str = preg_replace("/\[url=www.(.*?)\](.*?)\[\/url\]/i","<a href=\"http://www.\\1\" $pop>\\2</a>",$str);
	
		// [url=%%dir[1]%%somefile.pdf]close[/url]
		$str = preg_replace("/\[url=(.*?)%%dir\[(.*?)\]%%(.*?)\](.*?)\[\/url\]/i","<a href=\"\\1%%dir[\\2]%%\\3\">\\4</a>",$str);    
		
		// [url=javascript:window.close()]close[/url] this one replaces oddball submissions
		$str = preg_replace("/\[url=(.*?)\](.*?)\[\/url\]/i","<a href=\"\\1\">\\2</a>",$str);
		
		// [email]joe@xyz.com[/email]
		$str = preg_replace("/\[email\](.*?)\[\/email\]/i","<a href=\"mailto:\\1\">\\1</a>",$str);
		
		// [email=your@yourstie]email[/email]
		$str = preg_replace("/\[email=(.*?)\](.*?)\[\/email\]/i","<a href=\"mailto:\\1\">\\2</a>",$str);
	}
	else
	{
		// [url]http://www.somesite.com[/url]
		$str = preg_replace("/\[url\]http(s?):\/\/(.*?)\[\/url\]/i","<a href=\"http\\1://\\2\" $pop class=\"sohbet\">\\2</a>",$str);
		
		// [url=http://www.somesite.com]somesite[/url]
		$str = preg_replace("/\[url=http(s?):\/\/(.*?)\](.*?)\[\/url\]/i","<a href=\"http\\1://\\2\" $pop class=\"sohbet\">\\3</a>",$str);
		
		// [url]www.somesite.com[/url]
		$str = preg_replace("/\[url\]www.(.*?)\[\/url\]/i","<a href=\"http://www.\\1\" $pop class=\"sohbet\">\\1</a>",$str);
	
		// [url=www.somesite.com]somesite[/url]
		$str = preg_replace("/\[url=www.(.*?)\](.*?)\[\/url\]/i","<a href=\"http://www.\\1\" $pop class=\"sohbet\">\\2</a>",$str);

		// [url=%%dir[1]%%somefile.pdf]close[/url]
		$str = preg_replace("/\[url=(.*?)%%dir\[(.*?)\]%%(.*?)\](.*?)\[\/url\]/i","<a href=\"\\1%%dir[\\2]%%\\3\" class=\"sohbet\">\\4</a>",$str);    
		
		// [url=javascript:window.close()]close[/url] this one replaces oddball submissions
		$str = preg_replace("/\[url=(.*?)\](.*?)\[\/url\]/i","<a href=\"\\1\" class=\"sohbet\">\\2</a>",$str);
		
		// [email]joe@xyz.com[/email]
		$str = preg_replace("/\[email\](.*?)\[\/email\]/i","<a href=\"mailto:\\1\" class=\"sohbet\">\\1</a>",$str);
		
		// [email=your@yourstie]email[/email]
		$str = preg_replace("/\[email=(.*?)\](.*?)\[\/email\]/i","<a href=\"mailto:\\1\" class=\"sohbet\">\\2</a>",$str);
	}
	
	// (gbkz: baslik)
	$str = preg_replace("/\(gbkz: (.*?)\)/i","<a href=\"#\" onClick=\"return pencere('bakiniz.php?baslik=\\1','bkz',500,350,50,50)\">\\1</a>",$str);
	
	// (bkz: baslik)
	$str = preg_replace("/\(bkz: (.*?)\)/i","(bkz: <a href=\"#\" onClick=\"return pencere('bakiniz.php?baslik=\\1','bkz',500,350,50,50)\">\\1</a>)",$str);
	
	// (tbkz: baslik)
	$str = preg_replace("/\(tbkz: (.*?)\)/i","(tbkz: <a href=\"getur.php?baslik=\\1\">\\1</a>)",$str);
	
	// [gbkz]baslik[/gbkz]
	$str = preg_replace("/\[gbkz\](.*?)\[\/gbkz\]/i","<a href=\"#\" onClick=\"return pencere('bakiniz.php?baslik=\\1','bkz',500,350,50,50)\">\\1</a>",$str);
	
	// [bkz]baslik[/bkz]
	$str = preg_replace("/\[bkz\](.*?)\[\/bkz\]/i","(bkz: <a href=\"#\" onClick=\"return pencere('bakiniz.php?baslik=\\1','bkz',500,350,50,50)\">\\1</a>)",$str);
	
	// [tez]baslik[/tez]
	$str = preg_replace("/\[tez\](.*?)\[\/tez\]/i","(bkz: <a href=\"getur.php?baslik=\\1\">\\1</a>)",$str);
	
	// [g]baslik[/g]
	$str = preg_replace("/\[g\](.*?)\[\/g\]/i","<a href=\"#\" onClick=\"return pencere('bakiniz.php?baslik=\\1','bkz',500,350,50,50)\">\\1</a>",$str);
	
	// [k]baslik[/k]
	$str = preg_replace("/\[k\](.*?)\[\/k\]/i","(bkz: <a href=\"#\" onClick=\"return pencere('bakiniz.php?baslik=\\1','bkz',500,350,50,50)\">\\1</a>)",$str);
	
	// [t]baslik[/t]
	$str = preg_replace("/\[t\](.*?)\[\/t\]/i","(bkz: <a href=\"getur.php?baslik=\\1\">\\1</a>)",$str);
	
	$str = nl2br($str);

	return $str;
}
// END

//	---------------------------------------------
//		ANKET KONFIGURASYONU
//	---------------------------------------------

$anketpath = $path."anket/";

define(templatedir, $anketpath);
define(simplepollurl, "http://blog.orangeslices.net/");

function dooutput($template)
{
	echo $template;
}

function storeoutput($template)
{
	global $admincontent;
	
	$admincontent .= $template;
}

function gettemplate($templatedir,$template,$endung="html")
{	
	//echo "templatedir = ".$templatedir;
	//echo "<br>Template= ".$template;
	return str_replace("\"","\\\"",implode("",file($templatedir.$template.".".$endung)));	
}

// END

//	---------------------------------------------
//		ZIYARETCI SAYACI
//	---------------------------------------------

function ZiyaretciSay()
{
	$db = new DB();
	
	$query = new DB_query($db, "select id from ob_okur");     
	$toplam = $query->db_num_rows();
	unset($query);
	
	if ($toplam < 0)
	{
		$toplam = "0";
	}
	
	return $toplam;
}
// END

//	---------------------------------------------
//		MENU YARATICI
//	---------------------------------------------

function menuyap($tip, $yazi="", $link="", $onclick="", $class="kumanda")
{
	if ($link == "#")
	{
		$link = $onclick;
	}
	else
	{
		$link = "window.location='$link';";
	}

	if ($tip == "baslat")
	{
		echo "<table border=\"0\" cellpadding=\"4\" cellspacing=\"2\"><tr>";
	}
	elseif ($tip == "satirbaslat")
	{
		echo "<tr>";
	}
	elseif ($tip == "satirbitir")
	{
		echo "</tr>";
	}
	elseif ($tip == "bitir")
	{
		echo "</tr></table>";
	}
	elseif ($tip == "tekbuton")
	{
		echo "<table border=\"0\" cellpadding=\"4\" cellspacing=\"2\"><tr>";
		echo "<td class=\"$class\" onclick=\"$link\" onmouseover=\"this.className='".$class."hover';\" onmouseout=\"this.className='$class';\"><nobr>$yazi</nobr></td>";
		echo "</tr></table>";
	}
	elseif ($tip == "menu")
	{
		echo "<td class=\"$class\" onclick=\"$link\" onmouseover=\"this.className='".$class."hover';\" onmouseout=\"this.className='$class';\"><nobr>$yazi</nobr></td>";
	}
	elseif ($tip == "bosbuton")
	{
		echo "<td class=\"$class\"><nobr>$yazi</nobr></td>";
	}
	elseif ($tip == "bos")
	{
		echo "<td></td>";
	}
}
// END

//	---------------------------------------------
//		MENU YARATICI (RETURN FORMATI)
//	---------------------------------------------

function menuyap_return($tip, $yazi="", $link="", $onclick="", $class="kumanda")
{
	if ($link == "#")
	{
		$link = $onclick;
	}
	else
	{
		$link = "window.location='$link';";
	}

	if ($tip == "baslat")
	{
		$sonuc = "<table border=\"0\" cellpadding=\"4\" cellspacing=\"2\"><tr>";
	}
	elseif ($tip == "satirbaslat")
	{
		$sonuc = "<tr>";
	}
	elseif ($tip == "satirbitir")
	{
		$sonuc = "</tr>";
	}
	elseif ($tip == "bitir")
	{
		$sonuc = "</tr></table>";
	}
	elseif ($tip == "tekbuton")
	{
		$sonuc = "<table border=\"0\" cellpadding=\"4\" cellspacing=\"2\"><tr>";
		$sonuc .= "<td class=\"$class\" onclick=\"$link\" onmouseover=\"this.className='".$class."hover';\" onmouseout=\"this.className='$class';\"><nobr>$yazi</nobr></td>";
		$sonuc .= "</tr></table>";
	}
	elseif ($tip == "menu")
	{
		$sonuc = "<td class=\"$class\" onclick=\"$link\" onmouseover=\"this.className='".$class."hover';\" onmouseout=\"this.className='$class';\"><nobr>$yazi</nobr></td>";
	}
	
	return $sonuc;
}
// END

//	---------------------------------------------
//		TEMIZLIKCI TEYZE
//	---------------------------------------------

function temizlikciteyze($str)
{
	$str = strtolower($str);
	
	$str = str_replace("ı", "i", $str);
	$str = str_replace("ğ", "g", $str);
	$str = str_replace("ş", "s", $str);
	$str = str_replace("ü", "u", $str);
	$str = str_replace("ö", "o", $str);
	$str = str_replace("ç", "c", $str);
	$str = str_replace("'", " ", $str);
	$str = str_replace("\"", "", $str);
	$str = str_replace(",", "", $str);
	$str = str_replace(".", " ", $str);
	
	return $str;
}
// END

//	---------------------------------------------
//		KALENDER
//	---------------------------------------------

function kalender($tarih="")
{
	if ($tarih == "")
	{
		$tarih = time();
	}
	
	$gun = date("j", $tarih);
	$aygun = date("t", $tarih);
	$aysayi = date("n", $tarih);
	
	$ay = date("F", $tarih);
	$yil = date("Y", $tarih);
	
	if ($ay == "January")
	{
		$ay = "ocak";
	}
	elseif ($ay == "February")
	{
		$ay = "subat";
	}
	elseif ($ay == "March")
	{
		$ay = "mart";
	}
	elseif ($ay == "April")
	{
		$ay = "nisan";
	}
	elseif ($ay == "May")
	{
		$ay = "mayis";
	}
	elseif ($ay == "June")
	{
		$ay = "haziran";
	}
	elseif ($ay == "July")
	{
		$ay = "temmuz";
	}
	elseif ($ay == "August")
	{
		$ay = "agustos";
	}
	elseif ($ay == "September")
	{
		$ay = "eylul";
	}
	elseif ($ay == "October")
	{
		$ay = "ekim";
	}
	elseif ($ay == "November")
	{
		$ay = "kasim";
	}
	elseif ($ay == "December")
	{
		$ay = "aralik";
	}
	
	echo "<table border=\"0\" cellpadding=\"2\" cellspacing=\"2\" width=\"170\"><tr>";
	
	$sonrakiay = $aysayi + 1;
	
	if ($sonrakiay == "13")
	{
		$sonrakiay = "1";
		$sonrakiyil = $yil + 1;
	}
	else
	{
		$sonrakiyil = $yil;
	}
	
	$oncekiay = $aysayi - 1;
	
	if ($oncekiay == "0")
	{
		$oncekiay = "12";
		$oncekiyil = $yil - 1;
	}
	else
	{
		$oncekiyil = $yil;
	}
	
	$sonrakitarih = mktime(0, 0, 0, $sonrakiay, 1, $sonrakiyil);
	$oncekitarih = mktime(0, 0, 0, $oncekiay, 1, $oncekiyil);
	$oncekiaygunsayisi = date("t", $oncekitarih);
	$oncekitarih = mktime(23, 59, 59, $oncekiay, $oncekiaygunsayisi, $oncekiyil);
	
	$db = new DB();
			
	$sql = "select id from ob_blog where tarih <= '$oncekitarih'";
	$query = new DB_query($db, $sql);
	$oncekitoplam = $query->db_num_rows();
	
	unset($sql);
	unset($query);
	
	if ($oncekitoplam > 0)
	{
		echo "<td class=\"kalenderkoyu\" align=\"center\"><a href=\"kalender.php?nedirtarih=$oncekitarih\"><</a></td>";
	}
	else
	{
		echo "<td class=\"kalenderkoyu\" align=\"center\"></td>";
	}
	
	echo "<td class=\"kalenderkoyu\" colspan=\"5\" align=\"center\">$ay $yil</td>";
	
	$sql = "select id from ob_blog where tarih >= '$sonrakitarih'";
	$query = new DB_query($db, $sql);
	$sonrakitoplam = $query->db_num_rows();
	
	unset($sql);
	unset($query);
	
	if ($sonrakitoplam > 0)
	{
		echo "<td class=\"kalenderkoyu\" align=\"center\"><a href=\"kalender.php?nedirtarih=$sonrakitarih\">></a></td>";
	}
	else
	{
		echo "<td class=\"kalenderkoyu\" align=\"center\"></td>";
	}
	
	$r = "0";
	
	for ($g = "1"; $g <= $aygun; $g++)
	{
		if ($r == "0")
		{
			echo "<tr>";
		}
		
		$linktarih = mktime(0, 0, 0, $aysayi, $g, $yil);
		$bitirtarih = mktime(23, 59, 59, $aysayi, $g, $yil);
		
		if ($gun == $g)
		{
			echo "<td class=\"kalenderkoyu\" align=\"center\"><b>$g</b></td>";
		}
		else
		{
			$sql = "select id from ob_blog where (tarih >= '$linktarih' and tarih <= '$bitirtarih')";
			$query = new DB_query($db, $sql);
			$toplam = $query->db_num_rows();
			
			unset($sql);
			unset($query);
			
			if ($toplam > 0)
			{
				echo "<td class=\"kalenderacik\" align=\"center\"><a href=\"kalender.php?nedirtarih=$linktarih\">$g</a></td>";
			}
			else
			{
				echo "<td class=\"kalenderacik\" align=\"center\">$g</td>";
			}
		}
		
		if ($r == "6")
		{
			echo "</tr>";
			
			$r = "0";
		}
		else
		{
			$r++;
		}
	}
	
	echo "</table>";
}
// END

//	---------------------------------------------
//		GUN CEVIR
//	---------------------------------------------

function guncevir($tarih)
{
	$gun = date("l", $tarih);
	
	if ($gun == "Monday")
	{
		$gun = "pazartesi";
	}
	elseif ($gun == "Tuesday")
	{
		$gun = "sali";
	}
	elseif ($gun == "Wednesday")
	{
		$gun = "carsamba";
	}
	elseif ($gun == "Thursday")
	{
		$gun = "persembe";
	}
	elseif ($gun == "Friday")
	{
		$gun = "cuma";
	}
	elseif ($gun == "Saturday")
	{
		$gun = "cumartesi";
	}
	elseif ($gun == "Sunday")
	{
		$gun = "pazar";
	}
	
	return $gun;
}
// END

//	---------------------------------------------
//		GIRIS ISLEMLERI
//	---------------------------------------------

$db = new DB();
$simdi = time();

if (isset($HTTP_COOKIE_VARS['member_code'])) 
{
    $loginyazarkod = $HTTP_COOKIE_VARS['member_code'];

    $sql = "select id,isim,tema,statu,durum,toplamyorum,toplamentri,songiris,sonyorumoku from ob_uyeler where kulkod = '$loginyazarkod'";
	$query = new DB_query($db, $sql);
	$kayitliyazar = $query->db_num_rows();
	$query->db_fetch_object();
	
	if ($kayitliyazar == "0")
	{
		return header("location:giris.php?nedir=cik");
	}
	
	$loginyazarid = $query->obj->id;
	$loginyazarisim = $query->obj->isim;
	$loginyazartema = $query->obj->tema;
	$loginyazarstatu = $query->obj->statu;
	$loginyazardurum = $query->obj->durum;
	$loginyazarentri = $query->obj->toplamentri;
	$loginyazaryorum = $query->obj->toplamyorum;
	$loginyazarsongiris = $query->obj->songiris;
	$loginyazaryorumoku = $query->obj->sonyorumoku;
	
	unset($sql);
	unset($query);
	
	if ($loginyazarstatu == "2")
	{
		return header("location:giris.php?nedir=cik");
	}
		
	$sql = "select kod from ob_temalar where id = '$loginyazartema'";
	$query = new DB_query($db, $sql);
	$query->db_fetch_object();
	
	$tema = $query->obj->kod;
	
	unset($sql);
	unset($query);
	
	if ($loginyazaryorumoku == "0")
	{
		$addsql = ", sonyorumoku = '$simdi'";
	}
	
	$sql = "update ob_uyeler set songiris = '$simdi'".$addsql." where id = '$loginyazarid'";
	$query = new DB_query($db, $sql);
	
	unset($sql);
	unset($query);
}
else
{
	$tema = "orange";
}

// END

?>