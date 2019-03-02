<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
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
    
        <!-- 20190114:add left menu.by zhong-->
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
                     
			while ( have_posts() ) : the_post();
                        
                       
				get_template_part( 'template-parts/page/content', 'page' );
                        
                               
				// If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;

			endwhile; // End of the loop.

			?>

		</main><!-- #main -->
	</div><!-- #primary -->
</div><!-- .wrap -->

<?php get_footer();
