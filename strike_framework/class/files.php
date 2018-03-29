<?php

  /*

      Name: Strike Framework
      Class: files.php

      Version: 1.0 (24.10.2010)
      Author: Arat Media (www.aratmedia.pl), MichaĹ‚ Rygielski
      
      Official website: http://labs.aratmedia.pl/strike/wiki/

      Compatible: PHP 4.X, PHP 5.X

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

  class strike_FILES extends strike_ERROR {

    var $files = array();

    function add_file($filename, $source = "") {

		  $this->files[] = array('name' => $filename, 'extension' => substr(strrchr($filename, '.'), 1), 'source' => $source);

    }

    function get_all_data() {

      return $this->files;

    }

    function get_file_extension($number, $lower = true) {

      if ($lower) return strtolower($this->files[$number]['extension']);
      else return $this->files[$number]['extension'];

    }
    
    function get_file_source($number) {

      return $this->files[$number]['source'];

    }
    
    function get_file_name($number, $lower = true) {

      if ($lower) return strtolower($this->files[$number]['name']);
      else return $this->files[$number]['name'];

    }
    
    function upload_file($number, $path) {
//echo "<br><br>---<br>".$this->files[$number]['source']."<br><br>---<br>";
      if (move_uploaded_file($this->files[$number]['source'], $path)) return true; else return false;

    }
		
		function file_size_info($size, $accuracy = 0) {
			
			$units = array(' B', ' KB', ' MB', ' GB', ' TB');
			for ($i = 0; $size >= 1024 && $i < 4; $i++) $size /= 1024;
			return round($size, $accuracy).$units[$i];
	 
		}
    
  }

?>