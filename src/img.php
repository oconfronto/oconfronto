<?php 
declare(strict_types=1);

header('Content-Type: image/png');

   //Input file 
   $file = "test.gif"; 
   $img = ImageCreateFromGIF($file); 
   //Dimensions 
   $width = imagesx($img); 
   $height = imagesy($img); 
   $max_width = 300; 
   $max_height = 300; 
   $percentage = 1; 
   //Image scaling calculations 
   if ( $width > $max_width ) {  
      $percentage = ($height / ($width / $max_width)) > $max_height ? 
           $height / $max_height : 
           $width / $max_width; 
   } 
   elseif ( $height > $max_height) { 
      $percentage = ($width / ($height / $max_height)) > $max_width ?  
           $width / $max_width : 
           $height / $max_height; 
   }
    
   $new_width = $width / $percentage; 
   $new_height = $height / $percentage; 
   //scaled image 
   $out = imagecreatetruecolor($new_width, $new_height); 
   imagecopyresampled($out, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height); 
   //output image 
   imagepng($out); 
?> 
