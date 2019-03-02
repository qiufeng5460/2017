<?php
/**
 * The template for displaying archive pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 * @version 1.0
 */

get_header(); ?>

<div class="wrap">

	<?php if ( have_posts() ) : ?>
		<header class="page-header">
			<?php
				the_archive_title( '<h1 class="page-title">', '</h1>' );
				the_archive_description( '<div class="taxonomy-description">', '</div>' );
			?>
		</header><!-- .page-header -->
	<?php endif; ?>

        <!-- 20190124:add left menu.by zhong-->
	<aside class="site-left-side">

		<?php if ( has_nav_menu( 'left' ) ) : ?>
			<div id="left-menu" class="navigation-left">

					<?php get_template_part( 'template-parts/navigation/navigation', 'left' ); ?>

			</div><!-- .navigation-left -->
		<?php endif; ?>

	</aside><!-- site-left-side -->
                
	<div id="primary" class="content-area">
            <div class="breadcrumbs_nav">
                <h2 class="breadcrumbs_title"><?php echo twentyseventeen_child_get_current_menu_title();  ?></h2>
                <p class="breadcrumbs_path"><?php twentyseventeen_child_breadcrumbs(); ?></p>
            </div>
		<main id="main" class="site-main" role="main">

		<?php
		if ( have_posts() ) : ?>

               <!--20190223:先显示置顶post-->    
                <?php
                  query_posts(array(
                            "category__in" => array(get_query_var("cat")),
                             "post__in" => get_option("sticky_posts"),
                                  )
                            );
                  while(have_posts()) : the_post();

                        get_template_part( 'template-parts/post/content', 'title' );

                 endwhile;
                 wp_reset_query();
               ?>                 
                 <!--20190223:先显示置顶post--> 
                                   
			<?php       
                        /*20190223:再显示非置顶post*/
                            query_posts(array(
        "category__in" => array(get_query_var("cat")),
        "post__not_in" => get_option("sticky_posts"),
        )
    );
                        
                      	/* Start the Loop */
			while ( have_posts() ) : the_post();

				/*
				 * Include the Post-Format-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
				 */
			//20181228:only display title.by zhong
                        //get_template_part( 'template-parts/post/content', get_post_format() );
                        get_template_part( 'template-parts/post/content', 'title' );

			endwhile;
                            wp_reset_query();
                            
                    

			the_posts_pagination( array(
				'prev_text' => twentyseventeen_get_svg( array( 'icon' => 'arrow-left' ) ) . '<span class="screen-reader-text">' . __( 'Previous page', 'twentyseventeen' ) . '</span>',
				'next_text' => '<span class="screen-reader-text">' . __( 'Next page', 'twentyseventeen' ) . '</span>' . twentyseventeen_get_svg( array( 'icon' => 'arrow-right' ) ),
				'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'twentyseventeen' ) . ' </span>',
			) );

		else :

			get_template_part( 'template-parts/post/content', 'none' );

		endif; ?>

		</main><!-- #main -->
	</div><!-- #primary -->
	<?php get_sidebar(); ?>
</div><!-- .wrap -->

<?php get_footer();
