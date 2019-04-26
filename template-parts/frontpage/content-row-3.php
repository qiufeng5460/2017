<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
            <!--20190406:在首页添加老师照片轮播行-->
            <div class="the3rdrow">
                <div class="title">幼师风采</div>
                <?php
                     //20190425:放弃单独page的方法，将每个老师照片生成单独post，然后分类为slug：teacher_introduce
                     $cat=get_category_by_slug('teacher_introduce');
                     //20190406:首页添加老师照片轮播
                      //global $post; // Modify the global post object before setting up post data.
	              //$teacher_id = 228;
		      //if($post = get_post( $teacher_id )){
		      //   setup_postdata( $post );
                ?>
                <a href="<?php echo get_category_link($cat->term_id); ?>">
                   <!--展示窗口-->
                   <div class="show_box">
                   <!--轮播图图片-->
                       <ul class="pic_box" id="pic_box">
                          <!--20190417:将li class改为slides的目的是为了和advertisement保持一致，以便在function中统一实现-->
                            <?php twentyseventeen_child_get_attachment_in_post('teacher_slides');?>   
                        </ul>
	
	                <div id="arr">
                           <span id="left"> <</span>
                           <span id="right">></span>
	                </div>
                    </div>
                </a>
                
                <?php         
		       //  wp_reset_postdata();
	            //  }
                ?> 

            </div>
