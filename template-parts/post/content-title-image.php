<?php

/* 
 * 20190425：师资力量cat显示
 */

?>

<?php

        $title_link=get_post_meta(get_the_id(),'wx_link',true);
        $title_link=$title_link ? $title_link:get_permalink(); 
        
?>
<figure class="teacher_show">
    <a href="<?php echo $title_link ?>" target="_blank">
        <?php the_post_thumbnail('full');?>
        <?php the_title( '<figcaption class="teacher_name">','</figcaption>' ) ?>
    </a>
</figure>