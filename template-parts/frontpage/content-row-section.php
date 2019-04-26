<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>

<div class="frontpage-row">
       <?php
       $theme_row_content=get_theme_mod('row_content');
       foreach($theme_row_content as $k=>$v){
       ?>
        <div class="row">
        <?php          
           if($v){
               if($k=='row-2'){
                  $thumbnail = wp_get_attachment_image_src( 491, 'twentyseventeen-featured-image' );
               }
               else if($k=='row-4'){
                  $thumbnail = wp_get_attachment_image_src( 487, 'twentyseventeen-featured-image' );
               }
               ?>
                <div class="row-image" style="background-image: url(<?php echo esc_url( $thumbnail[0] ); ?>)"></div>
       <?php
           }
            get_template_part( 'template-parts/frontpage/content', $k );
        ?>
          </div>
       <?php   
       }
       ?>
</div>
