<?php

  /*

      Name: Strike Framework
      Version: 1.8 (06.02.2011)
      Author: Michał Rygielski
      
      Official website: http://labs.2dev.pl/StrikeFramework

	  History:
	  
	   (2013.08.09)
	   - replace $this->html_source and $this->html_content to self::$html_source and self::$html_content
	   - conversion $html_source and $html_content to static variable
	  
      Compatible: PHP 4.X, PHP 5.X
                  MySQL 3.X

      Licence:
  
      Strike Framework is licence under a Creative Commons Attribution-NonCommercial 2.5 (http://creativecommons.org/licences/by-nc/2.5/)
  
      You are free:
        - to copy, distribute, display, and perform the work
        - to make derivative works
  
      Under the following conditions:
        - Attributio. You must attribute the work in the manner specified by the author
        - Noncommercial. You may not use this work for commercial purposes

      For any reuse of distribution, you must make clear to others the license therms of this work.
      Any of these conditions can be waived if get permission from the copyright holder.
  
      Your fair use and other rights are in no way affected by the above.
  
  */

  include("def/content_types.php");
  include("def/doctypes.php");
  include("def/numeric.php");
  include("def/jsfile.php");
  include("def/validate.php");
	
  class strike_HTML extends strike_ERROR {

    var $doctypes = array('<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Strict doctype//EN">',
                          '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">',
                          '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">',
                          '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">',
                          '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">',
                          '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">',
                          '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">',
                          '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">');
  
    var $content_types = array('Windows-1256' /*Arabic (Windows)*/, 'Windows-1257' /*Baltic (Windows)*/, 'Windows-1250' /*Central European (Windows)*/,
                             'Windows-1251' /*Cyrillic (Windows)*/, 'Windows-1253' /*Greek (Windows)*/, 'Windows-1255' /*Hebrew (Windows)*/,
                             'TIS-620' /*Thai (Windows)*/, 'Windows-1254' /*Turkish (Windows)*/, 'Windows-1258' /*Vietnamese (Windows)*/,
                             'Windows-1252' /*Western European (Windows)*/,

                             'ISO-8859-6' /*Arabic (ISO)*/, 'ISO-8859-4' /*Baltic (ISO)*/, 'ISO-8859-2' /*Central European (ISO)*/,
                             'ISO-8859-5' /*Cyrillic (ISO)*/, 'ISO-8859-13' /*Estonian (ISO)*/, 'ISO-8859-7' /*Greek (ISO)*/,
                             'ISO-8859-8-l' /*Hebrew (ISO-Logical)*/, 'ISO-8859-8' /*Hebrew (ISO-Visual)*/, 'ISO-8859-15' /*Latin 9 (ISO)*/,
                             'ISO-8859-9' /*Turkish (ISO)*/, 'ISO-8859-1' /*Western European (ISO)*/,

                             'GB18030' /*Chinese Simplified (GB18030)*/, 'GB2312' /*Chinese Simplified (GB2312)*/, 'HZ' /*Chinese Simplified (HZ)*/,
                             'Big5' /*Chinese Traditional (Big5)*/, 'Shift_JIS' /*Japanese (Shift-JIS)*/, 'EUC-JP' /*Japanese (EUC)*/,
                             'EUC-KR' /*Korean*/, 'UTF-8' /*Unicode (UTF-8)*/);


    static $html_source = null;
    static $html_content = null;
    var $autoBODY = true;
    var $compressHTML = false;

    var $doctype;
    var $description;
    var $keywords;
    var $content_type;
    var $title;
    var $emulateIE7;
    var $icon;
    var $author;
    var $autoProtected = false;

    var $css = array();
    var $style = array();
    var $js = array();
    var $js_body = array();
    var $head_other = array();
    var $swf = array();
    var $swf_flash_num = 0;
		var $templates = array();
		var $replaces = array();

    var $local;
    var $bodyID = "";
    var $addToBegin = "";

    var $variables = array();

    function strike_HTML() {
                        
      $this->protectedVariable();

      self::$html_source = "";

    }

    function crossGuardVariable($variable, $name = "", $return = false) {

        if ($this->autoProtected) {

           if ((preg_match("/(<script.*>)(.*)(<\/script>)/imxsU", $variable))
            or (preg_match("/(<object.*>)(.*)(<\/object>)/imxsU", $variable))
            or (preg_match("/(<javascript.*>)(.*)(<\/javascript>)/imxsU", $variable))
            or (preg_match("/(<iframe.*>)(.*)(<\/iframe>)/imxsU", $variable))
            or (preg_match("/(<applet.*>)(.*)(<\/applet>)/imxsU", $variable))
            or (preg_match("/(<meta.*>)(.*)(<\/meta>)/imxsU", $variable))
            or (preg_match("/(<style.*>)(.*)(<\/style>)/imxsU", $variable))
            or (preg_match("/(<form.*>)(.*)(<\/form>)/imxsU", $variable))) {

            if (!$return) {
               $this->addError("Error variable: invalid value <b>".$name."</b><br>", "Framework error", true);
               return "ERROR [".$name."]";
            } else {
              $this->addError("Error variable: invalid value <b>".$name."</b><br>", "Framework error", false);
              return true;
            }

          } else if (!$return) return $variable; else return false;

        } else return $variable;

    }
    
    function autoProtected($value) {
      
      $this->autoProtected = $value;

    }
    
    function autoBODY($value) {

      $this->autoBODY = $value;

    }
    
    function compressHTML($value) { 
     
      $this->compressHTML = $value; 

    }
    
    function convertToCoding($string, $type = "iso-8859-2") {
        $x2utf = array(
            "\xc3\xb3" => "\xc3\xb3", "\xc5\xba" => "\xc5\xbc", "\xc5\xbc" => "\xc5\xba",
            "\xc5\xb9" => "\xc5\xbb", "\xc5\xbb" => "\xc5\xb9",
            "\xb1" => "\xc4\x85", "\xa1" => "\xc4\x84", "\xe6" => "\xc4\x87", "\xc6" => "\xc4\x86",
            "\xea" => "\xc4\x99", "\xca" => "\xc4\x98", "\xb3" => "\xc5\x82", "\xa3" => "\xc5\x81",
            "\xf3" => "\xc3\xb3", "\xd3" => "\xc3\x93", "\xb6" => "\xc5\x9b", "\xa6" => "\xc5\x9a",
            "\xbc" => "\xc5\xbc", "\xac" => "\xc5\xbb", "\xbf" => "\xc5\xba", "\xaf" => "\xc5\xb9",
            "\xf1" => "\xc5\x84", "\xd1" => "\xc5\x83",
            "\xb9" => "\xc4\x85", "\xa5" => "\xc4\x84", "\xe6" => "\xc4\x87", "\xc6" => "\xc4\x86",
            "\xea" => "\xc4\x99", "\xca" => "\xc4\x98", "\xb3" => "\xc5\x82", "\xa3" => "\xc5\x81",
            "\xf3" => "\xc3\xb3", "\xd3" => "\xc3\x93", "\x9c" => "\xc5\x9b", "\x8c" => "\xc5\x9a",
            "\x9f" => "\xc5\xbc", "\x8f" => "\xc5\xbb", "\xbf" => "\xc5\xba", "\xaf" => "\xc5\xb9",
            "\xf1" => "\xc5\x84", "\xd1" => "\xc5\x83" );
        $utfflip = array("\xc5\xbc" => "\xc5\xba", "\xc5\xbb" => "\xc5\xb9",
            "\xc5\xba" => "\xc5\xbc", "\xc5\xb9" => "\xc5\xbb");
        $win2utf = array(
            "\xb9" => "\xc4\x85", "\xa5" => "\xc4\x84", "\xe6" => "\xc4\x87", "\xc6" => "\xc4\x86",
            "\xea" => "\xc4\x99", "\xca" => "\xc4\x98", "\xb3" => "\xc5\x82", "\xa3" => "\xc5\x81",
            "\xf3" => "\xc3\xb3", "\xd3" => "\xc3\x93", "\x9c" => "\xc5\x9b", "\x8c" => "\xc5\x9a",
            "\x9f" => "\xc5\xbc", "\x8f" => "\xc5\xbb", "\xbf" => "\xc5\xba", "\xaf" => "\xc5\xb9",
            "\xf1" => "\xc5\x84", "\xd1" => "\xc5\x83" );
        $iso2utf = array(
            "\xb1" => "\xc4\x85", "\xa1" => "\xc4\x84", "\xe6" => "\xc4\x87", "\xc6" => "\xc4\x86",
            "\xea" => "\xc4\x99", "\xca" => "\xc4\x98", "\xb3" => "\xc5\x82", "\xa3" => "\xc5\x81",
            "\xf3" => "\xc3\xb3", "\xd3" => "\xc3\x93", "\xb6" => "\xc5\x9b", "\xa6" => "\xc5\x9a",
            "\xbc" => "\xc5\xbc", "\xac" => "\xc5\xbb", "\xbf" => "\xc5\xba", "\xaf" => "\xc5\xb9",
            "\xf1" => "\xc5\x84", "\xd1" => "\xc5\x83" );
        $u2plk = array(
            "\xc4\x85" => "a", "\xc4\x84" => "A", "\xc4\x87" => "c", "\xc4\x86" => "C",
            "\xc4\x99" => "e", "\xc4\x98" => "E", "\xc5\x82" => "l", "\xc5\x81" => "L",
            "\xc3\xb3" => "o", "\xc3\x93" => "O", "\xc5\x9b" => "s", "\xc5\x9a" => "S",
            "\xc5\xbc" => "z", "\xc5\xbb" => "Z", "\xc5\xba" => "z", "\xc5\xb9" => "Z",
            "\xc5\x84" => "n", "\xc5\x83" => "N" );
        $string = strtr($string, $x2utf);
        switch (strtolower($type))    {
            case 'utf-8':            return strtr($string, $utfflip);
            case 'iso-8859-2':        return strtr($string, array_flip($iso2utf));
            case 'windows-1250':    return strtr($string, array_flip($win2utf));
            default:                return strtr($string, $u2plk);
        }
    }

    function gpc_extract($param_name, $array, &$target, &$param) {
 
         if (!is_array($array)) return false;
         reset($array);
 
         while (list($key, $value) = each($array)) {
 
             if (is_array($value)) {
 
                 $_tmp=$param_name;
                 if (!$param_name) $param_name=$key;
                    else $param_name.="[".$key."]";
 
                 $this->gpc_extract($param_name, $value, $target[$key], $param);
                 $param_name=$_tmp;
 
             } else {

                 if (get_magic_quotes_gpc()) $value=stripslashes($value);
                 $target[$key] = $this->crossGuardVariable($value, $key);
                 $this->variables[$key] = $target[$key];
                 if ($param_name) $key="[".$key."]";
                 $param.="&".$param_name.$key."=".$value;
 
             }

         }
 
         reset($array);
         return true;

    }

    function protectedVariable() {

       if (!defined('myQUERYVARS')) {
             define('myQUERYVARS', "check", TRUE);
         $FetchedQueryString="";

         $i="";
         if (!empty($_GET)) {
            $this->gpc_extract($i, $_GET, $GLOBALS, $FetchedQueryString);
         } else if (!empty($HTTP_GET_VARS)) {
            $this->gpc_extract($i, $HTTP_GET_VARS, $GLOBALS, $FetchedQueryString);
         }

         $i="";
         if (!empty($_POST)) {
            $this->gpc_extract($i, $_POST, $GLOBALS, $FetchedQueryString);
         } else if (!empty($HTTP_POST_VARS)) {
            $this->gpc_extract($i, $HTTP_POST_VARS, $GLOBALS, $FetchedQueryString);
         }

         $i="";
         if (!empty($_COOKIE)) {
            $this->gpc_extract($i, $_COOKIE, $GLOBALS, $FetchedQueryString);
         } else if (!empty($HTTP_COOKIE_VARS)) {
            $this->gpc_extract($i, $HTTP_COOKIE_VARS, $GLOBALS, $FetchedQueryString);
         }

         $i="";
         if (!empty($_SESSION)) {
            $this->gpc_extract($i, $_SESSION, $GLOBALS, $FetchedQueryString);
         } else if (!empty($HTTP_SESSION_VARS)) {
            $this->gpc_extract($i, $HTTP_SESSION_VARS, $GLOBALS, $FetchedQueryString);
         }

         if (!empty($_FILES)) {
             while (list($name, $value) = each($_FILES)) {
                 $$name = $value['tmp_name'];
                 ${$name . '_name'} = $value['name'];
             }
         } else if (!empty($HTTP_POST_FILES)) {
             while (list($name, $value) = each($HTTP_POST_FILES)) {
                 $$name = $value['tmp_name'];
                 ${$name . '_name'} = $value['name'];
             }
         }

         if (!empty($_SERVER)) {
             if (isset($_SERVER['PHP_SELF'])) {
                 $PHP_SELF = $_SERVER['PHP_SELF'];
             }
             if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
                 $HTTP_ACCEPT_LANGUAGE = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
             }
             if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
                 $HTTP_AUTHORIZATION = $_SERVER['HTTP_AUTHORIZATION'];
             }
         } else if (!empty($HTTP_SERVER_VARS)) {
             if (isset($HTTP_SERVER_VARS['PHP_SELF'])) {
                 $PHP_SELF = $HTTP_SERVER_VARS['PHP_SELF'];
             }
             if (isset($HTTP_SERVER_VARS['HTTP_ACCEPT_LANGUAGE'])) {
                 $HTTP_ACCEPT_LANGUAGE = $HTTP_SERVER_VARS['HTTP_ACCEPT_LANGUAGE'];
             }
             if (isset($HTTP_SERVER_VARS['HTTP_AUTHORIZATION'])) {
                 $HTTP_AUTHORIZATION = $HTTP_SERVER_VARS['HTTP_AUTHORIZATION'];
             }
         }

         if ($FetchedQueryString) $FetchedQueryString="?".substr($FetchedQueryString,1);

       }

    }

    function addStyleCSS($style) {

      $this->style[] = $style;

    }

    function addCSSfile($filename) {

      $this->css[] = $filename;

    }

    function issetCSSfile($filename) {
     
      $key = array_search($filename, $this->css);

      if (strlen($key) == 0) return false; else return true;

    }

    function deleteCSSfile($filename) {

      $key = array_search($filename, $this->css);

      if (strlen($key) == 0) $this->addError("Error CSS: can not delete, a file <b>".$filename."</b> does not exist", "Framework error"); else unset($this->css[$key]);
                         
    }
    
    function addJScode($value, $body = FALSE) {

      if (!$body) {
        if (!$this->compressHTML) self::$html_content.= "<script type=\"text/javascript\">".$this->slib_compress_script($value)."</script>";
        else self::$html_content.= "<script type=\"text/javascript\">".$value."</script>";
      } else $this->js_body[] = $value;

    }

    function addJSfile($filename, $body = NORMAL) {

      if ($body == NORMAL) $this->js[] = $filename; else
      if ($body == HEAD) $this->js_body[] = file_get_contents($filename); else
      if ($body == BODY) self::$html_content.= "<script type=\"text/javascript\" src=\"".$filename."\"></script>";

    }

    function issetJSfile($filename) {

      $key = array_search($filename, $this->js);

      if (strlen($key) == 0) return false; else return true;

    }

    function deleteJSfile($filename) {

      $key = array_search($filename, $this->js);
      if (strlen($key) == 0) $this->addError("Error JavaScript: can not delete, a file <b>".$filename."</b> does not exist", "Framework error"); else unset($this->js[$key]);

    }

    function addOtherHeadSource($value) {

      $this->head_other[] = $value;

    }

    function addSWFfile($filename, $width, $height, $background = "opaque", $menu = false, $options = "", $flashvars = "") {

      $this->swf[] = array($filename, $width, $height, $background, $menu, $options, $flashvars);

    }
    
    function getSWFid($filename) {

      if (count($this->swf) > 0) {

        $i = 0;
        foreach($this->swf as $value) {

          $i++;

          if ($value[0] == $filename) {

            return "flashFile".$i;

          }

        }
        
      } $this->addError("Error SWF: file not found", "Framework error");

    }
		
	function addTemplatePattern($preg, $function_name) {

      $this->templates[] = array("preg" => $preg, "name" => $function_name);

    }
    
    function returnPatternConversion($source, $onlySource = false) {
    
        if (count($this->templates) > 0) {

            for ($i = 0; $i < count($this->templates); $i++) {

              $source = @preg_replace_callback($this->templates[$i]["preg"], $this->templates[$i]["name"], $source);

            }
        }
		
		if (!$onlySource) {
if (count($this->replaces) > 0) {
          for ($i = 0; $i < count($this->replaces); $i++) {
					 
						self::$html_source = strip_tags(str_replace($this->replaces[$i]["in"], $this->replaces[$i]["out"], self::$html_source));

					}  
}                   
} 
    
        return $source;
    
    }
    
    
    function clearTagsPatternConversion($source) {
    
        if (count($this->templates) > 0) {

            for ($i = 0; $i < count($this->templates); $i++) {

              $source = strip_tags(@preg_replace_callback($this->templates[$i]["preg"], $this->templates[$i]["name"], $source));

            }

        }
        if (count($this->replaces) > 0) {
          for ($i = 0; $i < count($this->replaces); $i++) {
					 
						self::$html_source = strip_tags(str_replace($this->replaces[$i]["in"], $this->replaces[$i]["out"], self::$html_source));

					}
       }
        return $source;
    
    }
    
    function cryptKey($str, $ky='') {
    
        if (empty($ky)) $ky = "!@#$%^&*()";  
        $ky = str_replace(chr(32), '', $ky);  
        if(strlen($ky) < 8) $ky = $ky.str_repeat("0", (8-strlen($ky)));  
        $kl = (strlen($ky) < 32) ? strlen($ky) : 32;
        $k = array();
        for ($i = 0; $i < $kl; $i++) {
            $k[$i] = ord($ky[$i])&0x1F;
        }

        $ln = strlen($str);
        $j = 0;
        for ($i = 0; $i < strlen($str); $i++) {
            $e = ord($str[$i]);
            $str[$i] = ($e&0xE0) ? chr($e^$k[$j]) : chr($e);
            $j++;
            $j = ($j == $kl) ? 0 : $j;
        }
        
        return $str;
        
    }
		
		function HTMLreplace($input, $output) {
		
		  $this->replaces[] = array("in" => $input, "out" => $output);
		
		}

    function setDoctype($num = HTML_TRANSITIONAL) {

      $this->doctype = $this->doctypes[$num];

    }

    function addV($name, $value) {

      $this->variables[$name] = $value;

    }

    function getV($name, $type = NULL) {

      $getVariable = "";

      if ($this->variables[$name]) {

        $getVariable = $this->crossGuardVariable($this->variables[$name], $name);

        if ($type == NUMERIC) $getVariable = intval($getVariable);
        if ($type == STRINGS) $getVariable = addslashes($getVariable);

        return $getVariable;

      } else {

         $this->addError("Error variable: variable <b>".$name."</b> does not exist<br>", "Framework error");

      }

    }

    function setDescription($value) {

      $this->description = "<meta name=\"description\" content=\"".$value."\">\n";

    }

    function setContentType($value = ISO_CENTRAL_EUROPEAN) {

      $this->content_type = "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=".$this->content_types[$value]."\">\n";

    }

    function setKeywords($value) {

      $this->keywords = "<meta name=\"keywords\" content=\"".$value."\">\n";

    }

    function setTitle($title) {

      $this->title = $title;

    }

    function emulateIE7($value) {

      if ($value) $this->emulateIE7 = "<meta http-equiv=\"X-UA-Compatible\" content=\"IE=EmulateIE7\">\n"; else $this->emulateIE7 = "";

    }
    
    function setIcon($value) {

      if ($value) $this->icon = "<link rel=\"shortcut icon\" href=\"".$value."\" type=\"image/x-icon\">\n"; else $this->icon = "";

    }

    function setAuthor($value) {

      $this->author = "<meta name=\"author\" content=\"".$value."\">\n";

    }
    
    function addToBegin($content) {
    
      $this->addToBegin = $content;
    
    }    
    
    function setBeginID($id) {
    
      $this->bodyID = $id;
    
    }

    function begin() {
 
      if ($this->autoBODY) $this->begin = "<body>\n"; else $this->begin = "";

    }

    function end() {

      if ($this->autoBODY) $this->end = "\n</body>\n</html>"; else $this->end = "";

    }

    function write($value) {

      self::$html_content.= $value;

    }

    function replace($what, $source) {

      self::$html_content = str_replace($what, $source, self::$html_content);

    }
    
    // pobiera maksymalna wartosc w tablicy wielowymiarowej
    function arrayMaxKey($array, $key) {
        if (!is_array($array) || count($array) == 0) return false;
        $max = $array[0][$key];
        foreach($array as $a) {
            if($a[$key] > $max) {
                $max = $a[$key];
            }
        }
        return $max;
    }
    
    //sortuje tablice po kluczu
    function arraySortKey(&$array, $key) {
    $sorter=array();
    $ret=array();
    reset($array);
    foreach ($array as $ii => $va) {
        $sorter[$ii]=$va[$key];
    }
    asort($sorter);
    foreach ($sorter as $ii => $va) {
        $ret[$ii]=$array[$ii];
    }
    $array=$ret;
    }

    
    function diff_date($date1, $date2) {

      $d1 = explode("-", $date1);
      $y1 = $d1[0];
      $m1 = $d1[1];
      $d1 = $d1[2];
      
      $d2 = explode("-", $date2);
      $y2 = $d2[0];
      $m2 = $d2[1];
      $d2 = $d2[2];
      
      $date1_set = mktime(0,0,0, $m1, $d1, $y1);
      $date2_set = mktime(0,0,0, $m2, $d2, $y2);
      
      return(round(($date2_set-$date1_set)/(60*60*24)));

    }
	
	function arrayDate($date, $type = "YYYY-MM-DD") {
	
	  if ($type == "YYYY-MM-DD") {
	  
	    $array = substr($date, 0, 10);
	    $array = explode("-", $array);
		
		$array = array("year" => $array[0], "month" => $array[1], "day" => $array[2]);
		
		return $array;
	  
	  }
	
	}

    function easyURL($value) {

       $_value=stripslashes($value);
  
       $_value=strtr($_value,"ąćęłńóśźżĄĆĘŁŃÓŚŹŻąśźĄŚŹ","acelnoszzACELNOSZZaszASZ");
       $_value=strtolower($_value);
  
       $_value=eregi_replace("(\&[a-z]{1,}\;)","",$_value);
  
       $_value_final="";
       for ($i=0;$i<strlen($_value);$i++) {
           if (ereg("[0-9a-z\-]",$_value[$i])) {
              $_value_final.=$_value[$i];
           } else {
              $_value_final.=" ";
           }
       }
  
       $_value_final=str_replace(" ","-",$_value_final);
  
       $_value_final=ereg_replace("(\-){1,}","-",$_value_final);
       $_value_final=ereg_replace("^(\-){1}","",$_value_final);
       $_value_final=ereg_replace("(\-){1}$","",$_value_final);
  
       $url=strip_tags($_value_final);
  
       return $url;

    }

    function link2web($value) {
 /*
       $_value=stripslashes($value);

       $_value=strtr($_value,"ąćęłńóśźżĄĆĘŁŃÓŚŹŻąśźĄŚŹ","acelnoszzACELNOSZZaszASZ");
       $_value=strtolower($_value);

       $_value=strtr($_value,";:~!.,[]{}()*&^%$#@=+/|","```````````````````````");

       $_value=str_replace("`","",$_value);
       $_value_final=str_replace(" ","-",$_value);

       $url=strip_tags($_value_final);
       */
	   
       $_value=stripslashes($value);
  
//       $_value=strtr($_value,"ąćęłńóśźżĄĆĘŁŃÓŚŹŻąśźĄŚŹ","acelnoszzACELNOSZZaszASZ");
        $_value=str_replace(array("ą","ć","ę","ł","ń","ó","ś","ź","ż","Ą","Ć","Ę","Ł","Ń","Ó","Ś","Ź","Ż","ą","ś","ź","Ą","Ś","Ź"),
                            array("a","c","e","l","n","o","s","z","z","A","C","E","L","N","O","S","Z","Z","a","s","z","A","S","Z"),$_value);
   
  $_value=strtolower($_value);
  
       $_value=preg_replace("(\&[a-z]{1,}\;)","",$_value);
 
       $_value_final="";
       for ($i=0;$i<strlen($_value);$i++) {
           if (preg_match("/[0-9a-z\-]/",$_value[$i])) {
              $_value_final.=$_value[$i];
           } else {
              $_value_final.=" ";
           }
       }
  
       $_value_final=str_replace(" ","-",$_value_final);
 
       $_value_final=preg_replace("/(\-){1,}/","-",$_value_final);
       $_value_final=preg_replace("/^(\-){1}/","",$_value_final);
       $_value_final=preg_replace("/(\-){1}$/","",$_value_final);
  
       $url=strip_tags($_value_final);	   
	  
       return $_value_final;

    }
	
	function yearsOld($birthday) { /* type = DATATIME */
	
			if (($birthday = strtotime($birthday)) === false)	{
					return false;
			}
			for ($i = 0; strtotime("-$i year") > $birthday; ++$i);
			
			return $i - 1;
			
	}
	
	function splitString($tab, $space = 1) {
	
	  if ($space <= 0) $space = 1;

		$array_colors = array();
		$j = 0;
		$tmp = "";
		for ($i = 0; $i < strlen($tab); $i++) {

		 $j++;
		 $tmp .= $tab[$i];
		 if ($j == $space) {
		
			 $array_colors[] = $tmp;
			 $j = 0;
			 $tmp = "";
		
		 }
		
		}
		
		return $array_colors;

	}

    function md5_hash_encode($source) {
  
      $base_string = md5($source); // kodowanie stringa standardowym MD5()

      // dzielenie stringa na 3 kawałki
      $begin = substr($base_string, 0, 4);
      $in = substr($base_string, 4,20);
      $end = substr($base_string, 20, strlen($base_string));
  
      $base_string = $begin.rand(0,8).$in.rand(0,8).$end; // dodawanie dodatkowego hasha
      $base_string = strrev($base_string); // odwracanie kolejności znaków w stringu
  
      return $base_string;
  
    }
  
    function md5_hash_decode($source) {
  
      $base_string = strrev($source); // odwracanie kolejności znaków w stringu

      // pobieranie prawid-owych elementów stringa
      $begin = substr($base_string, 0, 4);
      $in = substr($base_string, 5, 20);
      $end = substr($base_string, 30, strlen($base_string));
  
      $base_string = $begin.$in.$end;
  
      return $base_string;
  
    }

    function base64_hash_encode($source, $base = false) {

       if ($base) $base_string = base64_encode($source); else $base_string = $source;

       if (strlen($source) > 24) {
         // dzielenie stringa na 3 kawałki
         $begin = substr($base_string, 0, 4);
         $in = substr($base_string, 4,20);
         $end = substr($base_string, 24, strlen($base_string));

         $base_string = $begin.rand(0, 8).$in.rand(0, 8).$end; // dodawanie dodatkowego hasha
       }

       $base_string = strrev($base_string); // odwracanie kolejności znaków w stringu

       for ($i = 0; $i < strlen($base_string); $i++) $base_string[$i] = chr(ord($base_string[$i]) + ($i * 3));

       // zamiana treści na HEX
       $encode_result = "";
       foreach(str_split($base_string) as $c) $encode_result .= sprintf("%02X", ord($c));

       return strtolower($encode_result);

    }

    function base64_hash_decode($source, $base = false) {

      // odwracanie treści z HEX
      $source_decode = "";
      foreach(explode("\n", trim(chunk_split(strtoupper($source), 2))) as $h) $source_decode .= chr(hexdec($h));

      for ($i = 0; $i < strlen($source_decode); $i++) $source_decode[$i] = chr(ord($source_decode[$i]) - ($i * 3));

      $base_string = strrev($source_decode); // odwracanie kolejności znaków w stringu

       if (strlen($base_string) > 24) {
         // pobieranie prawidłowych elementów stringa
         $begin = substr($base_string, 0, 4);
         $in = substr($base_string, 5, 20);
         $end = substr($base_string, 26, strlen($base_string));

         $base_string = $begin.$in.$end;
       }

       if ($base) $base_string = base64_decode($base_string);

      return $base_string;

    }
		
    function search_in_array_multiple($needle, $haystack) {  
            $found = FALSE; 
             
            foreach ($haystack as $value) 
            { 
                    if ((is_array($value) && $this->search_in_array_multiple($needle, $value)) || $value == $needle) 
                    { 
                            $found = TRUE; 
                            break; 
                    } 
            } 
             
            return $found; 
    } 
    
    function array_delete_elements($array = null, $valueToDelete = null) {
       
        if (!isset($array)) {
          return null;
        }
      
        $resultList = array();
        foreach ($array as $entryKey => $entryValue) {
          if (is_array($entryValue)) {
           
            $resultList[$entryKey] = array_delete_elements($entryValue, $valueToDelete);
          } else {
            if ($entryValue != $valueToDelete) {
              $resultList[$entryKey] = $entryValue;
            }
          }
        }
        return $resultList;
      } 
      
function remove_key_array($array, $str){

for ($i = 0; $i < count($array); $i++) {

  if ($array[$i] == $str) unset($array[$i]);

}
return $array;
}

    function cutString($word, $max) {
      $result = "";
      for ($i = 0; $i < strlen($word); $i++) {
        $result.=$word[$i];
        if ($i > $max-1) { $result .= "..."; break; }
      }
      return $result;
    }
    
    function removeBR($text) {
       return strtr($text, array("\r\n" => ' ', "\r" => ' ', "\n" => ' '));
    }

    function validateVariable($variable, $type = 0) {

      if ($type == 0) {
       
         return $variable;

      }

      if ($type == EMAIL) {
       
         if (preg_match('/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)+$/', $variable)) return true; else return false;

      }
      
      if ($type == PHONE) {
       
         $phone = preg_replace("/[^0-9\(\)]/","", $variable);
         
         if (!preg_match("/[0-9]{3}/", $phone) && !preg_match("/\([0-9]{2}\)[0-9]{7}/", $phone) && !preg_match("/\([0-9]{2}\)[0-9]{9}/", $phone)) return false; else return true;

      }
      
      if ($type == POSTCODE_PL) {

         if (!preg_match("/[0-9]{2}\-[0-9]{3}/", $variable)) return false; else return true;

      }

      if ($type == POSTCODE_EN) {

         if (!preg_match("/[0-9]{5}/", $variable)) return false; else return true;

      }
      
      if ($type == REGON) {
        
        if (strlen($variable) == 9) {

          // wagi stosowane dla REGONów 9-znakowych
          $weights = array(8, 9, 2, 3, 4, 5, 6, 7);

        } elseif (strlen($variable) == 14) {

          // wagi stosowane dla REGONów 14-znakowych
          $weights = array(2, 4, 8, 5, 0, 9, 7, 3, 6, 1, 2, 4, 8);

        } else return false;

        $sum = 0;
        for($i = 0; $i < count($weights); $i++) {

          $sum += $weights[$i] * $variable[$i];
  
        }

        $int = $sum % 11;            
        $checksum = ($int == 10) ? 0 : $int;

        if($checksum == $value[count($weights)]) return true; else return false;

      }

      if ($type == PESEL) {

        if (!preg_match('/^[0-9]{11}$/',$variable)) return false;

        // wagi pierwszych dziesięciu cyfr
        $weights = array(1, 3, 7, 9, 1, 3, 7, 9, 1, 3);

        $sum = 0;

        for ($i = 0; $i < 10; $i++) {

          $sum += $weights[$i] * $variable[$i];

        }

        $int = 10 - $sum % 10;
        $checksum = ($int == 10) ? 0 : $int;

        if ($checksum == $variable[10]) return true; else return false;

      }

      if ($type == WWW_URL) {

        if (preg_match('/^(http|https|ftp)://([A-Z0-9][A-Z0-9_-]*(?:.[A-Z0-9][A-Z0-9_-]*)+):?(d+)?/?/i', $variable)) return true; else return false;

      }
      
      if ($type == NIP) {
        
        if (strlen($variable) != 10) return false;

        $weights = array(6, 5, 7, 2, 3, 4, 5, 6, 7);
        $sum = 0;
        for ($i = 0; $i < 9; $i++){
            $sum += $weights[$i] * $variable[$i];
        }
        $tmp = $sum % 11;
        
        $nb = ($tmp == 10)? 0 : $tmp ;
        if ($nb == $variable[9]) return true; else return false;

      }

    }
    function slib_compress_script( $buffer ) {

  // JavaScript compressor by John Elliot <jj5@jj5.net>

  $replace = array(
 //   '#\'([^\n\']*?)/\*([^\n\']*)\'#' => "'\1/'+\'\'+'*\2'", // remove comments from ' strings
//    '#\"([^\n\"]*?)/\*([^\n\"]*)\"#' => '"\1/"+\'\'+"*\2"', // remove comments from " strings
    '#/\*.*?\*/#s'            => "",      // strip C style comments
    '#[\r\n]+#'               => "\n",    // remove blank lines and \r's
    '#\n([ \t]*//.*?\n)*#s'   => "\n",    // strip line comments (whole line only)
    '#([^\\])//([^\'"\n]*)\n#s' => "\\1\n",
                                          // strip line comments
                                          // (that aren't possibly in strings or regex's)
    '#\n\s+#'                 => "\n",    // strip excess whitespace
    '#\s+\n#'                 => "\n",    // strip excess whitespace
    '#(//[^\n]*\n)#s'         => "\\1\n", // extra line feed after any comments left
                                          // (important given later replacements)
    '#/([\'"])\+\'\'\+([\'"])\*#' => "/*" // restore comments in strings
  );

  $search = array_keys( $replace );
  $script = preg_replace( $search, $replace, $buffer );

  $replace = array(
    "&&\n" => "&&",
    "||\n" => "||",
    "(\n"  => "(",
    ")\n"  => ")",
    "[\n"  => "[",
    "]\n"  => "]",
    "+\n"  => "+",
    ",\n"  => ",",
    "?\n"  => "?",
    ":\n"  => ":",
    ";\n"  => ";",
    "{\n"  => "{",
//  "}\n"  => "}", (because I forget to put semicolons after function assignments)
    "\n]"  => "]",
    "\n)"  => ")",
    "\n}"  => "}",
    "\n\n" => "\n"
  );

  $search = array_keys( $replace );
  $script = str_replace( $search, $replace, $script );

  return trim( $script );

}
function compress_javascript2($js_code,$special_chars=false,$remove_new_lines=false) {

//
// Arrays
//
//array with pattern
	$pattern=array(
		//remove carriage return
		"/\x0D/",
		//remove vertical tab
		"/\x0B/",
		//Replace tabulators to one space
		"/\x09{1,}/si",
		//Replace more then one spaces to once
		"/\x20{2,}/");
		//Remove JS comments and HTML
		//'/((?:\/\*(?:[^*]|(?:\*+[^*\/]))*\*+\/)|(?:\/\/.*))/','/<!--.*?-->/si',
//	);
/*
	if($special_chars==true) {
	//Special JavaScript characters after and before the spaces are removed
		//$char_js='(|)|=|;|:|?|\'|"|+|-|\*|\/|%|!|<|>|&|\|[|]|{|}';
		//remove spaces with $char_js, after and before
		$pattern[]="/([{$char_js}]+)\x20/";
		$pattern[]="/\x20([{$char_js}]+)/";
	} */
	if($remove_new_lines==true) {
		$pattern[]="/\x0A/";
	}
	//array with replacement
	$replacement=array(
		//remove carriage return
		'',
		//remove vertical tab
		'',
		//Replace tabulators to one space
		"\x20",
		//Replace more then one spaces to once
		"\x20",
		//Remove JS comments and HTML
		'','',
	);
	if($special_chars==true) {
	//remove spaces with $char_js, after and before
		$replacement[]='$1';
		$replacement[]='$1';
	}
	if($remove_new_lines==true) {
		$replacement[]='';
	}
    //$js_code=preg_replace('@//.*@','',$js_code);//delete comments
//$js_code = $this->slib_compress_script($js_code);
	$start=strlen($js_code);
	//Compress JS with regular expressions
	$replace=preg_replace($pattern,$replacement,$js_code,-1);
	// strlen() after compress
	$final=strlen($replace);
	//counts the difference in characters
	$exhed=$start-$final;
	//counts the difference in percentages
	$compression=round(($exhed)/$start*100,2);
	//return $replace."\n//Before compress $start bytes; After compress: $final bytes; $exhed ({$compression}%)";
	return $replace;
}    


function compress_javascript($js_code,$special_chars=false,$remove_new_lines=false) {
  return $this->slib_compress_script($js_code);
}
		
    function generateHTML($return = false) {
 
      $tmp_head = "";
        
      if ($this->doctype) $tmp_head .= $this->doctype."\n";
      if ($this->autoBODY) $tmp_head .= "<html>\n";
      if ($this->autoBODY) $tmp_head .= "<head>\n";
      if ($this->title) $tmp_head .= "<title>".$this->title."</title>\n";
      if ($this->description) $tmp_head .= $this->description;
      if ($this->keywords) $tmp_head .= $this->keywords;
      if ($this->author) $tmp_head .= $this->author;
			
	    if (count($this->head_other) > 0) {

        foreach($this->head_other as $value) {

           $tmp_head .= $value."\n";

        }

      }

      if ($this->content_type) $tmp_head .= $this->content_type;
      if ($this->emulateIE7) $tmp_head .= $this->emulateIE7;
      if ($this->icon) $tmp_head .= $this->icon;

      if (count($this->css) > 0) {

	    $this->css = array_unique($this->css); // usuwanie podwojnych wpisow
        foreach($this->css as $value) {

            $tmp_head .= "<link href=\"".$value."\" rel=\"stylesheet\" type=\"text/css\" media=\"all\">\n";

        }

      }
      if (count($this->js) > 0) {
	  
	    $this->js = array_unique($this->js); // usuwanie podwojnych wpisow
        foreach($this->js as $value) {

           $tmp_head .= "<script type=\"text/javascript\" src=\"".$value."\"></script>\n";

        }

      }

      if (!$this->compressHTML) self::$html_source .= $tmp_head;
      
      
      
      if (count($this->js_body) > 0) {
      
//        $this->js_body = preg_replace('/\s+  /', '', $this->js_body);
      //  $this->js_body = compress_javascript($this->js_body,1,1);
        self::$html_source .= "<script type=\"text/javascript\">\n";
        $tmp_js = "";
        foreach($this->js_body as $value) {

           $tmp_js .= $value."\n";

        }
        //dobre
        $tmp_js = $this->compress_javascript($tmp_js,1,1);
        self::$html_source .= $tmp_js."\n";
        self::$html_source .= "</script>\n";

      }
      if (count($this->style) > 0) {

        self::$html_source .= "<style type=\"text/css\">\n";
        foreach($this->style as $value) {

            self::$html_source .= $value."\n";

        }
        self::$html_source .= "</style>\n";

      }

      if ($this->autoBODY) self::$html_source .= "</head>\n";

      if ($this->autoBODY) {
        if ($this->begin) {
          if ($this->bodyID != "") self::$html_source .= "<body id='".$this->bodyID."'>\n";  else self::$html_source .= $this->begin;
        }
      }
      if ($this->addToBegin != "") self::$html_source .= $this->addToBegin;
      if (self::$html_content) self::$html_source .= self::$html_content;

      if (count($this->swf) > 0) {

        self::$html_source.= "<script type=\"text/javascript\">\n";
        foreach($this->swf as $value) {

          $this->swf_flash_num++;

          self::$html_source.= "var so = new SWFObject(\"".$value[0]."\", \"flashFile".$this->swf_flash_num."\", \"".$value[1]."\", \"".$value[2]."\", \"8.0.0.0\", \"\", true);\n";
          if ($value[4]) self::$html_source.= "so.addParam(\"menu\", \"true\");\n"; else self::$html_source.= "so.addParam(\"menu\", \"false\");\n";
          self::$html_source.= "so.addParam(\"wmode\", \"".$value[3]."\");\n";
          if (isset($value[4]) && strlen($value[4]) > 0) self::$html_source.= "so.addParam(\"flashvars\", \"".$value[4]."\");\n";
          self::$html_source.= "so.write(\"flashFile".$this->swf_flash_num."\");\n";

        }
        self::$html_source.= "</script>\n";

      }

      if ($this->autoBODY) if ($this->end) self::$html_source .= $this->end;

      $temp = self::$html_source;

//      if ($this->compressHTML) self::$html_source = str_replace("\n", "", self::$html_source);
      
      //dobre
      //if ($this->compressHTML) self::$html_source = $this->compress_javascript(self::$html_source, 1, 1);

			 if (count($this->templates) > 0) {

			    for ($i = 0; $i < count($this->templates); $i++) {
					 
						self::$html_source = @preg_replace_callback($this->templates[$i]["preg"], $this->templates[$i]["name"], self::$html_source);

					}

			}		
			
			if (count($this->replaces) > 0) {
		
          for ($i = 0; $i < count($this->replaces); $i++) {
					 
						self::$html_source = str_replace($this->replaces[$i]["in"], $this->replaces[$i]["out"], self::$html_source);

					}
		
		  }
		
      if (!$return) {
        if ($this->compressHTML) echo $tmp_head;
        echo self::$html_source; 
      } else return self::$html_source; 

    }  
    

  }
	
?>