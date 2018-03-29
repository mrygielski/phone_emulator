<?php

  /*

      Name: Strike Framework
      Class: files.php

      Version: 1.0 (24.10.2010)
      Author: Arat Media (www.aratmedia.pl), Michał‚ Rygielski
      
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

  class strike_IMAGES extends strike_FILES {

    var $image;
    var $image_tmp;
    var $type;
      
    // effects
    var $effect_value = 0;
    var $blur = false;
    var $strokeColor = false;
    var $maskRevert = false;
    var $maskColor = false;
    var $shadow = false;

    function add_image($filename, $type = "jpg") {

      $this->type = $type;

			if (file_exists($filename)) {
        
				if ($type == "jpg") $this->image = imagecreatefromjpeg($filename);
                if ($type == "png") $this->image = imagecreatefrompng($filename);
                if ($type == "gif") $this->image = imagecreatefromgif($filename);
                
				if ($type == "jpg") $this->image_tmp = imagecreatefromjpeg($filename);
                if ($type == "png") $this->image_tmp = imagecreatefrompng($filename);
                if ($type == "gif") $this->image_tmp = imagecreatefromgif($filename);
		
			}
			
    }
    
    function add_image_binary($content, $type = "jpg") {
    
      $this->type = $type;
      
      $content = base64_decode($content);

      if ($type == "jpg") $this->image = imagecreatefromstring($content);
      if ($type == "png") $this->image = imagecreatefromstring($content);
      if ($type == "gif") $this->image = imagecreatefromstring($content);

      if ($type == "jpg") $this->image_tmp = imagecreatefromstring($content);
      if ($type == "png") $this->image_tmp = imagecreatefromstring($content);
      if ($type == "gif") $this->image_tmp = imagecreatefromstring($content);
			
    }
    
    function image_binary($binary, $mime) {  
      //$contents = file_get_contents($file);
      //$base64   = base64_encode($contents); 
      return ('data:' . $mime . ';base64,' . $binary);
    }

    function x() {
 
      
      return imagesx($this->image);

    }

    function y() {

      return imagesy($this->image);

    }
    
    function rgb_to_array($rgb) {
        $a[0] = ($rgb >> 16) & 0xFF;
        $a[1] = ($rgb >> 8) & 0xFF;
        $a[2] = $rgb & 0xFF;

        return $a;
    }        

    function effect($type, $value = 0) {
    
      if ($type == "blur") { $this->blur = true; $this->effect_value = $value; }
      if ($type == "strokeColor") { $this->strokeColor = true; $this->effect_value = $value; }
      if ($type == "maskRevert") { $this->maskRevert = true; $this->effect_value = $value; }
      if ($type == "maskColor") { $this->maskColor = true; $this->effect_value = $value; }
      if ($type == "shadow") { $this->shadow = true; $this->effect_value = $value; }
    
    } 
 
    // value = array
    // array($border, $direction, $blur_size, array($r, $g, $b))
    // direction: center
    function effectShadow($src, $value) {

        $scale = $value[0];
        $direction = $value[1];
        $blur_size = $value[2];
        $colors = $value[3];
        $centerX = 0;
        $centerY = 0;
        $width_mini = imagesx($src);
        $height_mini = imagesy($src);
        $width_orig = imagesx($src);
        $height_orig = imagesy($src);
    
 
      $img = imagecreatetruecolor($width_orig, $height_orig);

        imagesavealpha($img, true);
        imagealphablending($img, false);
        
        // kolorowy i rozmyty podklad cienia
        for ($x = 0; $x < imagesx($src); $x++) {
        for ($y = 0; $y < imagesy($src); $y++) {
                $mask_pix = imagecolorat($src,$x,$y);
                $mask_pix_color = imagecolorsforindex($src, $mask_pix);
                if ($mask_pix_color['alpha'] < 127) {
                    $src_pix = imagecolorat($src,$x,$y);
                    $src_pix_array = imagecolorsforindex($src, $src_pix);
               
                    $color = imagecolorallocate($src, $colors[0], $colors[1], $colors[2]);
                    imagesetpixel($src, $x, $y, $color);   
                
                }
            }
        } 
 
 /*
        for ($i = -2; $i < 2; $i++)  
        for ($j = -2; $j < 2; $j++) {
                
         imagecopyresized($img, $src, $i*($scale/2), $j*($scale/2), 0, 0, $width_orig, $height_orig, $width_orig, $height_orig);
         imagesavealpha($img, true); 
         imagealphablending($img, true);  
  
        } 
 */
 
  for ($i = 0; $i < 3; $i++)  
        for ($j = 0; $j < 3; $j++) {
                
         imagecopyresized($img, $src, ($i*($scale/2))-$scale/2, ($j*($scale/2))-$scale/2, 0, 0, $width_orig, $height_orig, $width_orig, $height_orig);
         imagesavealpha($img, true); 
         imagealphablending($img, true);  
  
        } 
         imagesavealpha($img, true); 
         
        
            
         
         
         
       // imagealphablending($img, true);        
        //  imagecopyresized($img, $src, -5, -5, 0, 0, $width_orig, $height_orig, $width_orig, $height_orig);
     
        /*
        imagealphablending($img, false);        
          imagecopyresized($img, $src, 5, -5, 0, 0, $width_orig, $height_orig, $width_orig, $height_orig);
  imagealphablending($img, true);    
        */
        
        
        // Set the border and fill colors
 
              
       
     //   $img_blur = $this->effectBlur($img, $blur_size); 
       //  imagesavealpha($img_blur, true); 
       //imagealphablending($img_blur, false);   
        
      // if ($direction == "center") imagecopyresized($img, $img_blur, $scale/4, $scale/4, 0, 0, $width_orig, $height_orig, $width_orig, $height_orig);
       
      //  imagesavealpha($img_blur, true); 
       //imagealphablending($img_blur, false);  
  //imagealphablending($img, false);          
  //imagealphablending($img_blur, false);    
        // nakladanie oryginalu
       // imagealphablending($img, true);    

       


       
        imagecopyresized($img, $this->image, 0, 0, 0, 0, $width_orig, $height_orig, imagesx($this->image), imagesy($this->image));
        //imagecopy($img, $this->image, 110, 110, $width_orig, $height_orig, imagesx($this->image), imagesy($this->image));
        //imagealphablending($img, false);   
 
       
  
  $img_display = imagecreatetruecolor($width_orig, $height_orig);
  $color = imagecolorallocatealpha($img_display,0x00,0x00,0x00,127);
imagefill($img_display, 0, 0, $color); 
 

       imagesavealpha($img_display, true); 
       imagealphablending($img_display, false);   

        imagecopyresized($img_display, $img, (-$scale/2), (-$scale/2), 0, 0, $width_orig, $height_orig, $width_orig-$scale, $height_orig-$scale);
          
 
 
      return $img_display;
    
    }
 
    function effectBlur($srcimg, $blur) {
    $dstimg = $srcimg;
 

//$gaussian = array(array(1.0, 2.0, 1.0), array(2.0, 4.0, 2.0), array(1.0, 2.0, 1.0));
//imageconvolution($dstimg, $gaussian, 16, 0);
  
  /*
$sharpenMatrix = array
            (
                array(-1.2, -1, -1.2),
                array(-1, 20, -1),
                array(-1.2, -1, -1.2)
            );

            // calculate the sharpen divisor
            $divisor = array_sum(array_map('array_sum', $sharpenMatrix));           

            $offset = 0;
           
            // apply the matrix
            imageconvolution($dstimg, $sharpenMatrix, $divisor, $offset); 
*/
/*
$sharpenMatrix = array
            (
                array(-1.2, -1, -1.2),
                array(-1, 20, -1),
                array(-1.2, -1, -1.2)
            );
$matrix = array
(
    array(-1, -1, -1),
    array(-1, 16, -1),
    array(-1, -1, -1),
);

$divisor = array_sum(array_map('array_sum', $matrix)); // 8
 
            $offset = 0;
imageconvolution($dstimg, $sharpenMatrix, $divisor, $offset); 
*/

  
  
       /* $blur = max(0,min(1,$blur));
   
        $srcw = imagesx($srcimg);
        $srch = imagesy($srcimg);
       
        $dstimg = imagecreatetruecolor($srcw,$srch);
  
        $f1a = $blur;
        $f1b = 1.0 - $blur;

   
        $cr = 0; $cg = 0; $cb = 0;
        $nr = 0; $ng = 0; $nb = 0;

        $rgb = imagecolorat($srcimg,0,0);
        $or = ($rgb >> 16) & 0xFF;
        $og = ($rgb >> 8) & 0xFF;
        $ob = ($rgb) & 0xFF;

        //-------------------------------------------------
        // first line is a special case
        //-------------------------------------------------
        $x = $srcw;
        $y = $srch-1;
        while ($x--)
        {
        
            //horizontal blurren
            $rgb = imagecolorat($srcimg,$x,$y);
            
            $cr = ($rgb >> 16) & 0xFF;
            $cg = ($rgb >> 8) & 0xFF;
            $cb = ($rgb) & 0xFF;
   
            $nr = ($cr * $f1a) + ($or * $f1b);
            $ng = ($cg * $f1a) + ($og * $f1b);
            $nb = ($cb * $f1a) + ($ob * $f1b);   

            $or = $nr;
            $og = $ng;
            $ob = $nb;
           
            imagesetpixel($dstimg,$x,$y,($nr << 16) | ($ng << 8) | ($nb));
        }  
        //-------------------------------------------------

        //-------------------------------------------------
        // now process the entire picture
        //-------------------------------------------------
        $y = $srch-1;
        while ($y--)
        {

            $rgb = imagecolorat($srcimg,0,$y);
            $or = ($rgb >> 16) & 0xFF;
            $og = ($rgb >> 8) & 0xFF;
            $ob = ($rgb) & 0xFF;

            $x = $srcw;
            while ($x--)
            {
                //horizontal
                $rgb = imagecolorat($srcimg,$x, $y);
                $cr = ($rgb >> 16) & 0xFF;
                $cg = ($rgb >> 8) & 0xFF;
                $cb = ($rgb) & 0xFF;
               
                $nr = ($cr * $f1a) + ($or * $f1b);
                $ng = ($cg * $f1a) + ($og * $f1b);
                $nb = ($cb * $f1a) + ($ob * $f1b);   
   
                $or = $nr;
                $og = $ng;
                $ob = $nb;
               
               
                //vertical
                $rgb = imagecolorat($dstimg,$x,$y+1);
                $vr = ($rgb >> 16) & 0xFF;
                $vg = ($rgb >> 8) & 0xFF;
                $vb = ($rgb) & 0xFF;
               
                $nr = ($nr * $f1a) + ($vr * $f1b);
                $ng = ($ng * $f1a) + ($vg * $f1b);
                $nb = ($nb * $f1a) + ($vb * $f1b);   
   
                $vr = $nr;
                $vg = $ng;
                $vb = $nb;
                
                imagesetpixel($dstimg,$x,$y,($nr << 16) | ($ng << 8) | ($nb));
                
                
            } 
       
        }*/
        //-------------------------------------------------
        return $dstimg;       

    }    
    
    function image_rotate($degrees, $fileout, $quality = 100) {
    
      $rotate = imagerotate($this->image, $degrees, 0);
    
	   
      if ($this->type == "jpg") imagejpeg($rotate, $fileout, $quality);
      if ($this->type == "png") imagepng($rotate, $fileout, 0); //$quality);
      if ($this->type == "gif") imagegif($rotate, $fileout);
			 
			    
    
    }
    
    function image_scale($fileout, $widthMAX, $heightMAX, $edge = false, $box = false, $bgHex = "000000", $quality = 100) {
  
      $width_mini = $widthMAX;
      $height_mini = $heightMAX;
    
      $width_orig  = imagesx($this->image);
      $height_orig = imagesy($this->image);
      
 
      
 //   $copp = $this->effectShadow($copp, $this->effect_value);
   
    if ($this->shadow) $this->image_tmp = $this->effectShadow($this->image_tmp, $this->effect_value);
   
   if ($width_orig >= $width_mini || $height_orig >= $height_mini) {

        $ratio_orig = $width_orig/$height_orig;
      
        if ($height_mini/$width_mini > $ratio_orig) {
           $width_mini = $height_mini*$ratio_orig;
        } else {
           $height_mini = $width_mini/$ratio_orig;
        }

      } else {
			
        // jesli obrazek jest mniejszy niz ustawione wymiary to nic nie rob 
			  if (!$edge) {
          
				  $width_mini = $width_orig;
                  $height_mini = $height_orig;
			  
				} else {
				// powieksz mniejszy obrazek do wymiarow krawedzi				
								
					$ratio_orig = $width_orig/$height_orig;
				
					if ($height_mini/$width_mini > $ratio_orig) {
						 $width_mini = $height_mini*$ratio_orig;
					} else {
						 $height_mini = $width_mini/$ratio_orig;
					}
				
				}
				
      }
      
	    if ($box) {
			  $img_mini = imagecreatetruecolor($widthMAX, $heightMAX);
			  imagefilledrectangle($img_mini, 0, 0, $widthMAX, $heightMAX, "0x".$bgHex);
			} else $img_mini = imagecreatetruecolor($width_mini, $height_mini);
			
			if ($box) { 
				$centerX = ($widthMAX - $width_mini) / 2;
				$centerY = ($heightMAX - $height_mini) / 2; 
			} else {
			  $centerX = 0;
				$centerY = 0;
			}
       if ($this->type == "png") {     
         imagealphablending($img_mini, false);
         imagesavealpha($img_mini, true);
      }
     
  
      
      if ($this->shadow) {
      
        if (function_exists("ImageCopyResampled")) imagecopyresampled($img_mini, $this->image_tmp, $centerX, $centerY, 0, 0, $width_mini, $height_mini, $width_orig, $height_orig);
        else imagecopyresized($img_mini, $this->image_tmp, $centerX, $centerY, 0, 0, $width_mini, $height_mini, $width_orig, $height_orig);

      } else {
  
        if (function_exists("ImageCopyResampled")) imagecopyresampled($img_mini, $this->image, $centerX, $centerY, 0, 0, $width_mini, $height_mini, $width_orig, $height_orig);
        else imagecopyresized($img_mini, $this->image, $centerX, $centerY, 0, 0, $width_mini, $height_mini, $width_orig, $height_orig);
    
      }
             
      if ($this->blur) $img_mini = $this->effectBlur($img_mini, $this->effect_value); 
     // if ($this->shadow) $img_mini = $this->effectShadow($img_mini, $this->effect_value);
      if ($this->maskColor) {

        for ($x = 0; $x < imagesx($img_mini); $x++) {
            for ($y = 0; $y < imagesy($img_mini); $y++) {
                $mask_pix = imagecolorat($img_mini,$x,$y);
                $mask_pix_color = imagecolorsforindex($img_mini, $mask_pix);
                if ($mask_pix_color['alpha'] < 127) {
                    $src_pix = imagecolorat($img_mini,$x,$y);
                    $src_pix_array = imagecolorsforindex($img_mini, $src_pix);
               
                    $color = imagecolorallocate($img_mini, $this->effect_value[0], $this->effect_value[1], $this->effect_value[2]);
                    imagesetpixel($img_mini, $x, $y, $color);   
                
                }
            }
        } 
 
      } 
      if ($this->maskRevert) {
 
        for ($x = 0; $x < imagesx($img_mini); $x++) {
            for ($y = 0; $y < imagesy($img_mini); $y++) {
                $mask_pix = imagecolorat($img_mini,$x,$y);
                $mask_pix_color = imagecolorsforindex($img_mini, $mask_pix);
                //if ($mask_pix_color['alpha'] < 127) {
                    $src_pix = imagecolorat($img_mini,$x,$y);
                    $src_pix_array = imagecolorsforindex($img_mini, $src_pix);
                    imagesetpixel($img_mini, $x, $y, imagecolorallocatealpha($img_mini, $this->effect_value[0], $this->effect_value[1], $this->effect_value[2], 127 - $mask_pix_color['alpha']));
               // }
            }
        }
        
      }
      if ($this->strokeColor) {
 
        for ($x = 0; $x < imagesx($img_mini); $x++) {
            for ($y = 0; $y < imagesy($img_mini); $y++) {
                $mask_pix = imagecolorat($img_mini,$x,$y);
                $mask_pix_color = imagecolorsforindex($img_mini, $mask_pix);
                //if ($mask_pix_color['alpha'] < 127) {
                    $src_pix = imagecolorat($img_mini,$x,$y);
                    $src_pix_array = imagecolorsforindex($img_mini, $src_pix);
                    imagesetpixel($img_mini, $x, $y, imagecolorallocatealpha($img_mini, 0, 0, 0, 127 - $mask_pix_color['alpha']));
               // }
            }
        }
        
      }
             
      if ($this->type == "jpg") imagejpeg($img_mini, $fileout, $quality);
      if ($this->type == "png") imagepng($img_mini, $fileout, 0);
      if ($this->type == "gif") imagegif($img_mini, $fileout, $quality);

      imagedestroy($img_mini);

      return true;
    
    }
    
    function image_scale_crop($fileout, $widthMAX, $heightMAX, $quality = 100) {
    
      $width_mini = $widthMAX;
      $height_mini = $heightMAX;
  
      $width_orig  = imagesx($this->image);
      $height_orig = imagesy($this->image);

      $centerX = 0;
      $centerY = 0;

			$ratio_orig = $width_orig / $height_orig;
	
			if ($width_mini/$height_mini < $ratio_orig) {
				 $width_mini = $height_mini*$ratio_orig;
			} else {
				 $height_mini = $width_mini / $ratio_orig;
			}			
		
		 
    
      $img_mini = imagecreatetruecolor($widthMAX, $heightMAX);
    
      if (function_exists("ImageCopyResampled")) imagecopyresampled($img_mini, $this->image, $centerX, $centerY, 0, 0, $width_mini, $height_mini, $width_orig, $height_orig);
      else imagecopyresized($img_mini, $this->image, 0, 0, 0, 0, $width_mini, $height_mini, $width_orig, $height_orig);
  
           
			/*	$img_mini = imagecreatetruecolor(100, 100);

// Make the background white
imagefilledrectangle($img_mini, 0, 0, 99, 99, 0xFFFFFF);

// Draw a text string on the image
imagestring($img_mini, 3, 40, 20, 'GD Library', 0xFFBA00);	
 */
	
	   
      if ($this->type == "jpg") imagejpeg($img_mini, $fileout, $quality);
      if ($this->type == "png") imagepng($img_mini, $fileout, $quality);
      if ($this->type == "gif") imagegif($img_mini, $fileout);
			 
			
			
			
		//	imagejpeg($img_mini, $fileout, $quality);
			
      imagedestroy($img_mini);

      return true;

    }
    
    function image_destroy() {

      imagedestroy($this->image);

    }

  }

?>