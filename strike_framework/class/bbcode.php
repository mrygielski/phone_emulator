<?php

  /*

      Name: Strike Framework
      Class: bbcode.php

      Version: 1.0 (11.02.2010)
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
  
  function convert_for_html_code($matches) {
  
    $regex[0] = "<";
    $regex[1] = ">";
    $replace[0] = "&lt;";
    $replace[1] = "&gt;";
    ksort($regex);
    ksort($replace);
    $treated = str_replace($regex, $replace, $matches[1]);
    $output = '<u><b>Kod:</b></u><pre class=code>' . $treated . '</pre>';
  
    return $output;
  
  }
  
  function convert_for_html_quote($matches) {
  
    $regex[0] = "<";
    $regex[1] = ">";
    $replace[0] = "<";
    $replace[1] = ">";
    ksort($regex);
    ksort($replace);
    $treated = str_replace($regex, $replace, $matches[1]);
    $output = '<u><b>Cytat:</b></u><div class=code2>' . $treated . '</div>';
    
    return $output; 
  
  }
  
  function br2nl($string) {
  
    return preg_replace('/\<br(\s*)?\/?\>/i', "\n", $string);
  
  }

  class strike_BBCODE {
  
    function code_box($text) {
  
        $output = "<div class=\"code\">\\1</div>";
  
        return $output;

    }
  
    function quote($text)
    {
        $output = "<blockquote><h6>Cytat:</h6>\\1</blockquote>";
  
    return $output;
  
    }
  
    function htmlout($text) {
  
        return $text;
  
    }
  
    function parse($text) {
  
        $text = " " . $text;
        if (! (strpos($text, "[") && strpos($text, "]")) ) {
  
            return $text;
        
        } else {
  
            $text = $this->htmlout($text);

           /*  $text = preg_replace("/\n",'<br>', $text); */

            $text = preg_replace("/\\[b\\](.+?)\[\/b\]/is",'<b>\1</b>', $text);

            $text = preg_replace("/\\[i\\](.+?)\[\/i\]/is",'<i>\1</i>', $text);
            $text = preg_replace("/\\[u\\](.+?)\[\/u\]/is",'<u>\1</u>', $text);
            $text = preg_replace("/\[s\](.+?)\[\/s\]/is",'<s>\1</s>', $text);
  
  //            $text = preg_replace("/\[code\](.+?)\[\/code\]/is","".$this->code_box('\\1')."", $text);
       //     $text = preg_replace("/\[quote\](.+?)\[\/quote\]/is","".$this->quote('\\1')."", $text);
  
            //$text = eregi_replace("\\[img]([^\\[]*)\\[/img\\]","<img src=\"\\1\">",$text);
            $text = preg_replace("#\[img\](.*?)\[/img\]#si",'<img src="\\1" alt="" border="0" />',$text);
//             $text = eregi_replace("\\[size([^\\[]*)\\]([^\\[]*)\\[/size\\]","<font size=\"\\1px\">\\2</font>",$text);
//             $text = eregi_replace("\\[color=([^\\[]*)\\]([^\\[]*)\\[/color\\]","<font color=\"\\1\">\\2</font>",$text);

            $text = preg_replace("#\[url\](http.*?)\[/url\]#si", "<a href=\"\\1\" class=\"content_link\" target=\"_self\">\\1</a>", $text);
            $text = preg_replace("#\[url=(http.*?)\](.*?)\[/url\]#si", "<a href=\"\\1\" class=\"content_link\" target=\"_self\">\\2</a>", $text);
            $text = preg_replace("#\[url\](.*?)\[/url\]#si", "<a href=\"http://\\1\" class=\"content_link\" target=\"_self\">\\1</a>", $text);
            $text = preg_replace("#\[url=(.*?)\](.*?)\[/url\]#si", "<a href=\"http://\\1\" class=\"content_link\" target=\"_self\">\\2</a>", $text);

            $text = preg_replace("/\[list\](.+?)\[\/list\]/is", '<ul>$1</ul>', $text);
            $text = str_replace("[*]", "<li>", $text);
            $text = str_replace("[/*]", "</li>", $text);


            $text = preg_replace_callback("/\[code\](.+?)\[\/code\]/s", "convert_for_html_code", $text);
            $text = preg_replace_callback("/\[quote\](.+?)\[\/quote\]/s", "convert_for_html_quote", $text);
            $text = preg_replace("#\[code=(http://)?(.*?)\](.*?)\[/code]#si", "<div class=\"XcodeKodT\">KOD<small> (\\2)</small></div><div class=\"XcodeKodM\"><pre>\\3</pre></div>", $text);
            $text = preg_replace('#\[youtube](.*?)\[/youtube]#is', '<center><object width="425" height="355"><param name="movie" value="http://www.youtube.com/v/\1&hl=en"></param><param name="wmode" value="transparent"></param><embed src="http://www.youtube.com/v/\1&hl=en" type="application/x-shockwave-flash" wmode="transparent" width="425" height="355"></embed></object></center>', $text);

          //  $text = str_replace("\n", "DD", $text);

        } 
        
        return nl2br($text);
    
    }
}

?>