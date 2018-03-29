<?php

  /*

      Name: Strike Framework
      Class: error.php

      Version: 1.0 (29.08.2010)
      Author: Arat Media (www.aratmedia.pl), Michał Rygielski

      Official website: http://labs.aratmedia.pl/strike/wiki/

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

  class strike_ERROR {

    var $create_log_list = true;
    var $localhost = "";
    var $user = "";
    var $pass = "";
    var $base = "";
    var $base_con = false;
    var $global_Show = true;

    function error_saveLog($value) {

     $this->create_log_list = $value;

    }

    function error_show($value) {

     $this->global_Show = $value;

    }

    function error_setLogBase($h, $b, $p, $u) {

        if ($this->create_log_list) {

          if (!$con = @mysql_connect($h, $u, $p)) {

            $this->addError("Error MySQL: user <b>".$u."</b> error connecting to host <b>".$h."</b><br>", "Warning [error.php]");
   	     
		 }

          if (!@mysql_select_db($b, $con)) { $this->addError("Error MySQL: can not connect to database <b>".$b."</b><br>", "Warning [error.php]"); } else {

            $this->base_con = true;

            $this->localhost = $h;
            $this->user = $u;
            $this->pass = $p;
            $this->base = $b;

          }

        }

    }

    function createErrorListTable() {

      $mysql_error = new strike_MYSQL($this->errorList);
      $mysql_error->initMySQL(SQL_HOST, SQL_BASE, SQL_PASS, SQL_USER);
      $mysql_error->query("CREATE TABLE `framework_errors` (`id` int(32) unsigned NOT NULL auto_increment,`error` text,`date` varchar(30) default NULL,`ip` varchar(20) default NULL,PRIMARY KEY  (`id`));");

    }
function aa() { echo "DUPA";} 
    function addError($value, $title, $show = true) {

		    // if ($this->global_Show)
         if ($show) {
          // if ($title != "") echo "<div style='text-align: left; font-family: courier; font-size: 11px; font-weight: bold; padding: 0px; margin: 7px 2px 2px 2px; color: #fe6d10'>".$title."</div>";
          // echo "<div style='text-align: left; border: 1px solid #fe6d10; padding: 2px; margin: 2px; background: #fee9dc; font-family: courier; font-size: 11px; color: #fe6d10'>".date("Y-m-d H:i:s").", ".$value."</div>";
         }
		 //echo "TEST";
		 
         //if ($this->base_con) 
		 {
      //   if ($this->create_log_list) {
 
           $mysql_error = new strike_MYSQL($this->errorList);
           $mysql_error->initMySQL(SQL_HOST, SQL_BASE, SQL_PASS, SQL_USER);
           if ($mysql_error->isTableExist("framework_errors")) {

             $mysql_error->query("INSERT INTO framework_errors SET error='[".mysql_real_escape_string($title)."] - ".mysql_real_escape_string($value)."', date='".date("Y-m-d H:i:s")."', ip='".getenv("REMOTE_ADDR")."'");

           } else {

             $this->createErrorListTable();
             $mysql_error->query("INSERT INTO framework_errors SET error='[".mysql_real_escape_string($title)."] - ".mysql_real_escape_string($value)."', date='".date("Y-m-d H:i:s")."', ip='".mysql_real_escape_string(getenv("REMOTE_ADDR"))."'");

           }
		   
		   }

      //   }

    }

    function strike_error_handler($errno, $errstr, $errfile, $errline) {

        $text = "";

        $errno = $errno & error_reporting();
        if($errno == 0) return;
        if(!defined('E_STRICT'))            define('E_STRICT', 2048);
        if(!defined('E_RECOVERABLE_ERROR')) define('E_RECOVERABLE_ERROR', 4096);

        switch ($errno) {
            case E_ERROR:               $title = "Error";                      break;
            case E_WARNING:             $title = "Warning";                    break;
            case E_PARSE:               $title = "Parse Error";                break;
            case E_NOTICE:              $title = "Notice";                     break;
            case E_CORE_ERROR:          $title = "Core Error";                 break;
            case E_CORE_WARNING:        $title = "Core Warning";               break;
            case E_COMPILE_ERROR:       $title = "Compile Error";              break;
            case E_COMPILE_WARNING:     $title = "Compile Warning";            break;
            case E_USER_ERROR:          $title = "User Error";                 break;
            case E_USER_WARNING:        $title = "User Warning";               break;
            case E_USER_NOTICE:         $title = "User Notice";                break;
            case E_STRICT:              $title = "Strict Notice";              break;
            case E_DEPRECATED:          $title = "Deprecated";                 break;
            case E_RECOVERABLE_ERROR:   $title = "Recoverable Error";          break;
            default:                    $title = "Unknown error (".$errno.")"; break;
        }

        
        
				if ($this->global_Show) {
        
	 
		
			//	  echo "<div style='text-align: left; font-family: courier; font-size: 11px; font-weight: bold; padding: 0px; margin: 7px 2px 2px 2px; color: #d25252'>".$title."</div>";
       //   echo "<div style='text-align: left; border: 1px solid #d25252; padding: 2px; margin: 2px; background: #fae2e2; font-family: courier; font-size: 11px; color: #d25252'>";
        
				}
        $text.= date("Y-m-d H:i:s").", <i>$errstr</i> in <b>$errfile</b> on line <b>$errline</b>";

        if (function_exists('debug_backtrace')) {

            $backtrace = debug_backtrace();
            array_shift($backtrace);
            foreach ($backtrace as $i=>$l) {

                $text.= "<br>[$i] in function <b>{$l['function']}</b>";
                if($l['file']) $text.= " in <b>{$l['file']}</b>";
                if($l['line']) $text.= " on line <b>{$l['line']}</b>";
            }
        }
        if ($this->global_Show) {
				 
					  //echo $text;
					  $this->addError($text, $title); 
					
					//echo "</div>";

				}

        if ($this->base_con)
         if ($this->create_log_list) {

           $mysql = new strike_MYSQL($this->errorList);
           $mysql->initMySQL($this->localhost,$this->user,$this->pass,$this->base);
           if ($mysql->isTableExist("framework_errors")) {

             $mysql->query("INSERT INTO framework_errors SET error='[".$title."] - ".mysql_real_escape_string($text)."', date='".date("Y-m-d H:i:s")."'");

           } else {

             $this->createErrorListTable();
             $mysql->query("INSERT INTO framework_errors SET error='[".$title."] - ".mysql_real_escape_string($text)."', date='".date("Y-m-d H:i:s")."'");
           
           }
       
         }

    }

    function error_control() {

      if ($this->global_Show) error_reporting(E_ALL);

      set_error_handler(array(&$this,'strike_error_handler'));
      @ob_start("fatal_error_handler");

    }

  }

?>