<?php

  /*

      Name: Strike Framework
      Class: templates.php

      Version: 1.0 (08.08.2013)
      Author: 2DEV, Michał‚ Rygielski
      
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

  class strike_TEMPLATES extends strike_HTML {

    var $templates = array();
    var $variables = null;
	

    function loadTemplate($path) {

	    $getFileName = basename($path);
		$this->templates[$getFileName] = file_get_contents($path, true);
		
    }

    function set($name, $variables = array()) {

	    $this->variables = $variables;
	  
    } 


	
	function parser($input) {
 
 
//$regex = '#\{if\:@?"?(.*?)"?\}(.*){/if\}#i'; 
$regex = '#\{if\:@?"?(.*?)"?\}(.*){/if\}#i'; 
 
$string = $input;
  
 $v = $this->variables;
 
 
    $input = preg_replace_callback(
                        $regex,
                        function($matches) use ($v) {
						      if(is_array($matches)) {
	
  
        $block = explode('=',$matches[1]); if(empty($block[1])) $block[1] = true;
		

		
 
        $condition = explode(':',str_replace('!','',$block[0])); 
 
        $outcome = explode('{else}',$matches[2]);
        		 
		foreach ($v as $key => $value) {
	 
        $replacement = $value; for($i = 1; $i < count($condition); $i++) $replacement = $replacement[$condition[$i]];
		if ($block[0] == $key)
       if(!strpos($block[0],'!') ? $replacement == $block[1] : $replacement != $block[1])
            $matches = $outcome[0];
        else 
            {$matches = !empty($outcome[1]) ? $outcome[1] : '';

			}
		
		}	
			
    }
	return $matches;
						},
                        $string);
    

	 	foreach ($this->variables as $var => $value) {
		
			$input = str_replace("{@".$var."}", $value, $input);
			 
			
		}	
		
 
	
return $input;
	
	}	
	
 
   
    function display($name = "") {

		parent::$html_content .= $this->parser($this->templates[$name]);
	  
    }   
	 	
    
  }

?>