<?php
class error { 
   var $error; 

   function error() { 
         // dont let PHP know of our error handler yet 
   } 

   function handler($errno, $errstr, $errfile, $errline) { 

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
            case E_RECOVERABLE_ERROR:   $title = "Recoverable Error";          break;
            default:                    $title = "Unknown error (".$errno.")"; break;
        }

        echo "<div style='font-family: courier; font-size: 11px; font-weight: bold; padding: 0px; margin: 7px 2px 2px 2px; color: #d25252'>".$title."</div>";
        echo "<div style='border: 1px solid #d25252; padding: 2px; margin: 2px; background: #fae2e2; font-family: courier; font-size: 11px; color: #d25252'>";

        $text.= date("d.m.Y H:i:s").", <i>$errstr</i> in <b>$errfile</b> on line <b>$errline</b>";

        if (function_exists('debug_backtrace')) {

            $backtrace = debug_backtrace();
            array_shift($backtrace);
            foreach($backtrace as $i=>$l){
                $text.= "<br><br>[$i] in function <b>{$l['class']}{$l['type']}{$l['function']}</b>";
                if($l['file']) $text.= " in <b>{$l['file']}</b>";
                if($l['line']) $text.= " on line <b>{$l['line']}</b>";
            }
        }
         echo $text;
        echo "</div>";
   }

   function setText($text) { 
         $this->error = $text; 
   } 

   function setIni() { 
       set_error_handler(array($this, 'handler')); 
   } 
} 



// prints 'Error! <br>!!' 
?>