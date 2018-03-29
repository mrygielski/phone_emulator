<?php

  header("Cache-Control: no-cache, must-revalidate"); // wymusza kasowanie pamieci podrecznej (potrzebne do przeladowywania obrazkow w AJAX)

function gen_www()
{
   $time = explode(" ", microtime());
   $usec = (double)$time[0];
   $sec = (double)$time[1];
   return $sec + $usec;
}
$start = gen_www();


 include("strike_framework/lib/buffer_page_start.php");
 require_once("strike_framework/class/bbcode.php");
 require_once("strike_framework/class/error.php");
 require_once("strike_framework/class/files.php");
 require_once("strike_framework/class/images.php");

 require_once ("strike_framework/strike.php");
 
	 
 
 if ($_SERVER['HTTP_HOST'] == "localhost") $localhost = true; else $localhost = false;
 if ($localhost) $LIVEPATH = "http://localhost/phone_emulator/"; else $LIVEPATH = "http://someurl/";
 

 $html = new strike_HTML();
 
 $html->autoProtected(true); // autoochrona zmiennych
  
 $html->setDoctype(HTML_TRANSITIONAL); // standard HTML
 $html->setTitle("PHONE EMULATOR");
 $html->setDescription("");
 $html->setKeywords("");
 $html->setAuthor("Copyright 2013-".date("Y"));
 $html->setContentType(UTF8); // kodowanie strony
 $html->emulateIE7(false);  // standardowo jest FALSE z automatu, konwersja strony do standardu IE 7.0
 $html->setIcon($LIVEPATH."icon.ico");
 $html->addOtherHeadSource('<meta name="Generator" content="Strike Framework 1.8">');
 $html->addOtherHeadSource("<meta http-equiv=\"cache-control\" content=\"no-cache\">");
 $html->addOtherHeadSource("<meta http-equiv=\"pragma\" content=\"no-cache\">");
 $html->addOtherHeadSource("<meta name=\"copyright\" content=\"© 2013-".date("Y")." regi\">");


 $html->addJSfile($LIVEPATH."javascript/jquery.min.js");   
 $html->addStyleCss("
 html {
    overflow: auto;
    overflow-y: hidden;
}
body {
    margin: 0px;
  padding: 0px;
    overflow-x: hidden;
	text-align: center;
}


::-webkit-scrollbar {
    width: 12px;
}
 

::-webkit-scrollbar-track {
    -webkit-box-shadow: inset 0 0 6px rgba(150,150,150,0.3); 
    -webkit-border-radius: 10px;
    border-radius: 10px;
}
 

::-webkit-scrollbar-thumb {
    -webkit-border-radius: 10px;
    border-radius: 10px;
    background: rgba(150,150,150,0.8); 
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.5); 
}
::-webkit-scrollbar-thumb:window-inactive {
	background: rgba(150,150,150,0.4); 
}
}");  
 $html->addJScode('
 
    $(document).ready(function() {
 
	 $("#arrow").click(function() {
	 
	   $("#phone").attr("src", $("#getURL").val());

     });	 
	
  });
 
',true);  
 $html->addOtherHeadSource('<meta name="viewport" content="user-scalable=no">');
 
 $html->begin(); 
 
   $defaultURL = "http://wykop.pl";
 
   if (!isset($tab)) $tab = "horizontal";
   
   if ($tab=="horizontal") $html->write("<a href='?tab=horizontal'><img src='v2.jpg' width='46' height='80' border='0' style='position: absolute; left: 15px; bottom: 15px'></a>"); else $html->write("<a href='?tab=horizontal'><img src='v1.jpg' width='46' height='80' border='0' style='position: absolute; left: 15px; bottom: 15px'></a>");
   if ($tab=="vertical") $html->write("<a href='?tab=vertical'><img src='h2.jpg' width='80' height='46' border='0' style='position: absolute; left: 70px; bottom: 15px'></a>"); else $html->write("<a href='?tab=vertical'><img src='h1.jpg' width='80' height='46' border='0' style='position: absolute; left: 70px; bottom: 15px'></a>"); 
   
   
  if ($tab!="horizontal") {
 
   $html->write("<div style='margin: auto; margin-top: 15px; text-align: left; background:url(phone2.jpg) no-repeat;width:800px;height:457px'>");
   
     $html->write("<div id='boxscroll' style='width: 595px; height: 325px; position: absolute; margin-left: 71px; margin-top: 50px'>");
	 
	   $html->write("<div style='width: 595px; height: 35px; background: #1c1c1c; position: absolute; z-index: 10; margin-top: 325px'><div style='width: 21px; height: 21px; cursor:pointer;background:url(arrow.png) no-repeat;position:absolute;margin-left:567px;margin-top: 7px' id='arrow'></div><input type='text' id='getURL' value='".$defaultURL."' style='background: #fff; position: absolute; border: 0px; width: 549px; height: 18px; padding: 3px; margin-left: 5px; margin-top: 5px;'></div>");
	 
	   $html->write("<iframe id='phone' src='".$defaultURL."' style='height: 325px; width: 595px; position: absolute' frameborder='0'></iframe>");
	 
	 $html->write("</div>");
   
   $html->write("</div>");
   
   
   } else {

   $html->write("<div style='margin: auto; margin-top: 15px; text-align: left; background:url(phone.jpg) no-repeat;width:457px;height:800px'>");
   
     $html->write("<div id='boxscroll' style='width: 360px; height: 561px; position: absolute; margin-left: 47px; margin-top: 71px'>");
	 
	   $html->write("<div style='width: 360px; height: 35px; background: #1c1c1c; position: absolute; z-index: 10; margin-top: 561px'><div style='width: 21px; height: 21px; cursor:pointer;background:url(arrow.png) no-repeat;position:absolute;margin-left:332px;margin-top: 7px' id='arrow'></div><input type='text' id='getURL' value='".$defaultURL."' style='background: #fff; position: absolute; border: 0px; width: 314px; height: 18px; padding: 3px; margin-left: 5px; margin-top: 5px;'></div>");
	 
	   $html->write("<iframe id='phone' src='".$defaultURL."' style='width: 360px; height: 561px; position: absolute' frameborder='0'></iframe>");
	 
	 $html->write("</div>");
   
   $html->write("</div>"); 
   
   }
   
   
 
 $html->end(); 

 $html->compressHTML(true);
 $html->generateHTML(); 

 include("strike_framework/lib/buffer_page_stop.php");

?>