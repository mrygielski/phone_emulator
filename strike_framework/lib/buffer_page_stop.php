<?php

    ini_set("zlib.output_compression","0");

    $kompresowac_strone=false;

    $min_gz_size = 1024; # minimalny rozmar do kompresji
    $contents = ob_get_contents();
    $contents = strtr($contents, "ąśźĄŚŹ", "ąśźĄŚŹ");  #win1250->iso
    $SizeUnCompressed=strlen($contents);
    ob_end_clean();

    if (!empty($_SERVER) && isset($_SERVER['HTTP_ACCEPT_ENCODING'])) {
       $ACCEPT_ENCODING=$_SERVER['HTTP_ACCEPT_ENCODING'];
    } else if (!empty($HTTP_SERVER_VARS) && isset($HTTP_SERVER_VARS['HTTP_ACCEPT_ENCODING'])) {
       $ACCEPT_ENCODING=$HTTP_SERVER_VARS['HTTP_ACCEPT_ENCODING'];
    } else $ACCEPT_ENCODING="";
    if (!empty($_SERVER) && isset($_SERVER['HTTP_IF_NONE_MATCH'])) {
       $HTTP_IF_NONE_MATCH=$_SERVER['HTTP_IF_NONE_MATCH'];
    } else if (!empty($HTTP_SERVER_VARS) && isset($HTTP_SERVER_VARS['HTTP_IF_NONE_MATCH'])) {
       $HTTP_IF_NONE_MATCH=$HTTP_SERVER_VARS['HTTP_IF_NONE_MATCH'];
    }

    if($kompresowac_strone&&!headers_sent()&&($SizeUnCompressed>$min_gz_size)&&extension_loaded("zlib")&&ereg("gzip, deflate",$ACCEPT_ENCODING)) {
      $ae = explode(',', str_replace(' ', '', $ACCEPT_ENCODING));
      $enc = false;
      if (in_array('gzip', $ae)) {
          $enc = 'gzip';
      } else if (in_array('x-gzip', $ae)) {
          $enc = 'x-gzip';
      }

      header('Content-Encoding: '.$enc);
      header('Vary: Accept-Encoding');
      $etag = '"'.md5($contents).'"';
      header('ETag: ' . $etag);
      if (isset($HTTP_IF_NONE_MATCH)) {
          $inm = explode(',', $HTTP_IF_NONE_MATCH);
          foreach ($inm as $i_ => $i) {
              if (trim($i) == $etag) {
                  header('HTTP/1.0 304 Not Modified');
                  break;
              }
          }
      }
      $contents = gzencode($contents);
      $SizeCompressed = strlen($contents);

      header('Content-Length: ' . $SizeCompressed);
      echo $contents;
    } else {
      echo $contents;
    }
    
?>