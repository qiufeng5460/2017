<?php
/**
 * The front page template file
 *
 * If the user has selected a static page for their homepage, this is what will
 * appear.
 * Learn more: https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 * @version 1.0
 */

get_header(); ?>

<div class="wrap">

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">

        <?php get_template_part( 'template-parts/frontpage/content', 'row-section' ); ?>
         
                        <?php
                     //20190426:首页添加float window
                      global $post; // Modify the global post object before setting up post data.
	              $post_id = 532;
		      if($post = get_post( $post_id )){
		         setup_postdata( $post );
                        $title_link=get_post_meta($post_id,'wx_link',true);
                        $title_link=$title_link ? $title_link:get_permalink();
                     ?>
     <div id="floadAD" class="floadAd">
        <a class="close" href="javascript:void();" >×关闭</a>
        <a class="item" href="<?php echo $title_link ?>" target="_blank">
        <?php the_title();?>    
        </a>
    </div>
    <?php
        wp_reset_postdata();
        }
    ?>
	</main><!-- #main -->
</div><!-- #primary -->

</div><!-- .wrap -->

<?php get_footer();
