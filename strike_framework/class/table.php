<?php

  /*

      Name: Strike Framework
      Class: table.php

      Version: 1.0 (18.11.2010)
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
	
  class strike_TableList extends strike_ERROR {

	  private $padding = 0;
		private $margin = 0;
		
		private $rows = array();
		private $fields_style = array();	
		private $class_style = array();	
		private $numRow = -1;
        private $enableUL = false;
        private $liCLASS = "";
        private $liID = "";
	
		public function __construct($padding, $margin) {
		
			$this->padding = $padding;
			$this->margin = $margin;
		
		}	
		
    function setRowWidth($value) {

      $this->rows_style["width"] = $value;

    }    
		
    function newRow($value, $id = "", $style = "", $rowStyle = "", $class = "") {

		  $this->numRow++;

      $this->rows[] = array("value" => $value, "id" => $id, "style" => $style, "class" => $class, "rowStyle" => $rowStyle, "liCLASS" => $this->liCLASS, "liID" => $this->liID);
      $this->liID = "";

    }     
    
    function enableUL($param) {
    
      $this->enableUL = $param;
    
    }
    
    function setClassToLI($id) {
    
      $this->liCLASS = $id;
    
    }
    
    function setIdToLI($id) {
    
      $this->liID = $id;
    
    }
		
		function setField($style = "", $add = false, $col = 0, $row = "") {
		
		  if ($row == "") $row = $this->numRow;
			
		  $this->fields_style[$row][$col] = array("style" => $style, "add" => $add);
	 
		}
    /*
 		function setClass($class = "", $add = false, $col = 0, $row = "") {
		
		  if ($row == "") $row = $this->numRow;
			
		  $this->class_style[$row][$col] = array("class" => $class, "add" => $add);
	 
		} */
    
    function showTable() {
		
		  $r = "";
		
          $_liCLASS = "";
          $_liID = "";
        
          if ($this->enableUL) $r .= "<ul>";
        
		  for ($i = 0; $i < count($this->rows); $i++) {
		
                if ($this->rows[$i]["liCLASS"] != "") $_liCLASS = "class='".$this->rows[$i]["liCLASS"]."'"; else $_liCLASS = "";
                if ($this->rows[$i]["liID"] != "") $_liID = "id='".$this->rows[$i]["liID"]."'"; else $_liID = "";
        
                if ($this->enableUL) $r .= "<li ".$_liCLASS." ".$_liID.">";
        
				$r .= "<div ";

					if (!empty($this->rows[$i]["id"])) $r .= "id='".$this->rows[$i]["id"]."'";
				  if (!empty($this->rows[$i]["class"])) $r .= " class='".$this->rows[$i]["class"]."'";

				$r .= " style='display: table; ";

                                if (!empty($this->rows[$i]["rowStyle"])) $r .= $this->rows[$i]["rowStyle"];

                                $r .= "'>";

			  for ($j = 0; $j < count($this->rows[$i]["value"]); $j++) {
				//$r .= "<div style='float: left;>".$this->rows[$i]["value"][2]."</div>";

					$r .= "<div style='float: left;";

						 if (!empty($this->rows[$i]["style"])) {
						 
						   if (isset($this->fields_style[$i][$j]["add"]) && $this->fields_style[$i][$j]["add"]) {
							 
							   $r .= $this->rows[$i]["style"].";".$this->fields_style[$i][$j]["style"];
							 
							 } else { 
							 
							   if (isset($this->fields_style[$i][$j]["style"])) $r .= $this->fields_style[$i][$j]["style"].";"; else $r .= $this->rows[$i]["style"].";"; 
							 
							 }
             
						 }

						 if ($this->padding != 0) $r .= "padding: ".$this->padding."px;";
						 if ($this->margin != 0) $r .= "margin: ".$this->margin."px;";
						 if (!empty($this->rows_style["width"][$j])) $r .= "width: ".$this->rows_style["width"][$j]."px;";

					$r .= "'"; 
					
					  // if (!empty($this->rows[$i]["style"])) $r .= $this->rows[$i]["style"]."'"; 
						 
					
					$r .= ">".$this->rows[$i]["value"][$j]."</div>";
			 
				}
				$r .= "<div style='clear: both'></div>";
				
				$r .= "</div>";
                
                if ($this->enableUL) $r .= "</li>";
			
			}
            if ($this->enableUL) $r .= "</ul>"; 
			 
			return $r;
			
		}
					
  }

?>