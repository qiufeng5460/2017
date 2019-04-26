<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>

            <div class="the1strow">
                <div class="the1stcol_inthe1strow">
                    <?php
                     //20190321:首页添加园长寄语
                      global $post; // Modify the global post object before setting up post data.
	              $headmaster_massge_id = 34;
		      if($post = get_post( $headmaster_massge_id )){
		         setup_postdata( $post );
                         the_post_thumbnail('full');
                         
		         the_excerpt();
  
                         echo '<div class="a_wrap"><a href="'.esc_url( get_permalink()).'">更多>>>></a></div>';
                         
		         wp_reset_postdata();
	              }
                    ?>
                </div>
                <div class="the2ndcol_inthe1strow">
                   <!--20190321:add slides in frontpage-->
                    <div id="advertisement" class="slides">
	                <ul class="slides" style="overflow:hidden;">
                            <?php twentyseventeen_child_get_attachment_in_post('main_slides');?>  
	                </ul>   
	            </div>
                </div>
               
            </div>
