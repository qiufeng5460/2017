<?php
/**
 * Template part for displaying posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 * @version 1.2
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php
	if ( is_sticky() && is_home() ) :
		echo twentyseventeen_get_svg( array( 'icon' => 'thumb-tack' ) );
	endif;
	?>
	<header class="entry-header">
		<?php
		if ( 'post' === get_post_type() ) {
			echo '<div class="entry-meta">';
				if ( is_single() ) {
					twentyseventeen_posted_on();
				} else {
                                        //20181229:将日期放在title右边，且在一行里。by zhong
					//echo twentyseventeen_time_link();
					//twentyseventeen_edit_link();
				};
			echo '</div><!-- .entry-meta -->';
		};

		if ( is_single() ) {
			the_title( '<h1 class="entry-title">', '</h1>' );
		} elseif ( is_front_page() && is_home() ) {
			the_title( '<h3 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' );
		} else {
                        //20190103:check wx_link firstly, if not, open default link.by zhong
                        $title_link=get_post_meta(get_the_id(),'wx_link',true);
                        $title_link=$title_link ? $title_link:get_permalink();
                        //20190101:add target="_blank" for open URL in new view. by zhong
                        the_title( '<p class="entry-title"><a target="_blank" href="' . esc_url( $title_link ) . '" rel="bookmark">', '</a></p>' );
                        //20181229:place date after the title ,and keep them in the one row. by zhong
                        //20190108:don't get_modify_date function because it output the modify date
                        echo '<p class="entry-date">';
                        echo get_the_date();
                        echo '</p>';           
         
                         //20190108:使用flex方式 by zhong                       
                        //20190103:清除浮动，title 左浮动，date右浮动. by zhong
                        //20190103:使用overflow:hidden方式清除浮动. by zhong
                        //echo '<div class="clear"></div>';
		}
		?>
	</header><!-- .entry-header -->

	<?php if ( '' !== get_the_post_thumbnail() && ! is_single() ) : ?>
		<div class="post-thumbnail">
			<a href="<?php the_permalink(); ?>">
				<?php the_post_thumbnail( 'twentyseventeen-featured-image' ); ?>
			</a>
		</div><!-- .post-thumbnail -->
	<?php endif; ?>

	<div class="entry-content">
		<?php
		/* translators: %s: Name of current post */
		//the_content( sprintf(
		//	__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'twentyseventeen' ),
		//	get_the_title()
		//) );
                
		wp_link_pages( array(
			'before'      => '<div class="page-links">' . __( 'Pages:', 'twentyseventeen' ),
			'after'       => '</div>',
			'link_before' => '<span class="page-number">',
			'link_after'  => '</span>',
		) );
		?>
	</div><!-- .entry-content -->

	<?php
	if ( is_single() ) {
		twentyseventeen_entry_footer();
	}
	?>

</article><!-- #post-## -->
