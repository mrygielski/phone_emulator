<?php

  /*

      Name: Strike Framework
      Class: mysql.php

      Version: 1.0 (22.08.2010)
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

  class strike_MYSQL extends strike_ERROR {

    var $query;
    var $result;
    var $q;
    var $con = 0;
    var $status;
    var $system_base = "";
    var $system_pass = "";
    var $system_user = "";

    function initMySQL($host = "", $base = "", $pass = "", $user = "") {

        $this->con = @mysql_connect($host, $user, $pass, false, MYSQL_CLIENT_COMPRESS);
        $this->system_base = $base;
        $this->system_pass = $pass;
        $this->system_user = $user;

        if (!$this->con) {
        //  $this->addError("Error MySQL: user <b>".$user."</b> error connecting to host <b>".$host."</b><br>", "Framework error [mysql.php]", false);
        }
        if (!@mysql_select_db($base, $this->con)) {

	//  $this->addError("Error MySQL: can not connect to database <b>".$base."</b><br>", "Framework error [mysql.php]", false);
          $status = false;

        } else {
		
		  $status = true;
		  mysql_query("SET NAMES utf8");
		
		}
		

        return $status;

    }

    private function other_errors() {

       if (strlen(@mysql_errno($this->con)) > 0) {

         $error_sql = "<br><br>";
         $error_sql .= "#".mysql_errno($this->con)." - ".mysql_error($this->con);

         return $error_sql;

       } else return false;

    }

    function select($value) {

      $this->query($value);
	  //$this->fetch_assoc();

    }
    
    function free_memory() {

      if (@mysql_free_result($this->result)) return true; else return false;
	  //$this->fetch_assoc();

    }
    
    function query($value) {

       $this->query = $value;
       $this->result = @mysql_query($value, $this->con);

       if (!$this->result) $this->addError("Error MySQL: Query error <b>".$value."</b>".$this->other_errors(), "Framework error [mysql.php]"); else return true;

    }

    function num_rows() {

      $data = @mysql_num_rows($this->result);

      if (count($data) >= 0) return $data; else $this->addError("Error MySQL: Number lines, query error <b>".$this->query."</b>".$this->other_errors(), "Framework error [mysql.php]");

    }

    function fetch_assoc() {

      $data = @mysql_fetch_assoc($this->result);
      if (count($data) > 0) return $this->q = $data; else $this->addError("Error MySQL: Error reading data, query error <b>".$this->query."</b>".$this->other_errors(), "Warning [mysql.php]");

    }

    function fetch_array() {

      $data = @mysql_fetch_array($this->result);

      //mysql_free_result($this->result);
      if (count($data) > 0) return $this->q = $data; else $this->addError("Error MySQL: Error reading data, query error <b>".$this->query."</b>".$this->other_errors(), "Warning [mysql.php]");

    }

    function fetch_row() {

      $data = @mysql_fetch_row($this->result);
      if (count($data) > 0) return $this->q = $data; else $this->addError("Error MySQL: Error reading data, query error <b>".$this->query."</b>".$this->other_errors(), "Warning [mysql.php]");

    }

    function getData($value) {

      return $this->q[$value];

    }

    function isTableExist($value) {

       //$this->result = @mysql_query("SELECT * FROM ".$value);
       //$this->result = @mysql_query("SHOW TABLES LIKE `".$value."`");
       //if (!$this->result) return false; else 
	   return true;

    }

    function closeMySQL($refresh_vars = true) {

      if ($refresh_vars) {

        $this->query = "";
        $this->result = "";
        $this->q = "";
        $this->con = 0;
        $this->status = "";
        $this->system_base = "";

      }

      return @mysql_close($this->con);

    }


  }

?>