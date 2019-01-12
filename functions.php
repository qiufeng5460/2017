<?php
/**
 * Twenty Seventeen Child functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen_Child
 * @since 1.0
 */


/**
20190110: 当single页面选择前后post时用wx_link代替默认link. by zhong
 */
function twentyseventeen_child_next_link_change( $output, $format, $link, $post, $adjacent ) {
	
    
   if ( ! $post ) {
		$output = '';
	} else {
                $title_link=get_post_meta($post->ID,'wx_link',true);
                $title_link=$title_link ? $title_link:get_permalink($post);
		$title = $post->post_title;

		if ( empty( $post->post_title ) )
			$title = $previous ? __( 'Previous Post' ) : __( 'Next Post' );


		$title = apply_filters( 'the_title', $title, $post->ID );


		$rel =  'next';

		$string = '<a href="' . $title_link . '" rel="'.$rel.'">';
		$inlink = str_replace( '%title', $title, $link );

		$inlink = $string . $inlink . '</a>';

		$output = str_replace( '%link', $inlink, $format );
	}


    return $output;
}
add_filter( 'next_post_link', 'twentyseventeen_child_next_link_change' ,10,5);

function twentyseventeen_child_previous_link_change( $output, $format, $link, $post, $adjacent ) {
	
   
   if ( ! $post ) {
		$output = '';
	} else {
                $title_link=get_post_meta($post->ID,'wx_link',true);
                $title_link=$title_link ? $title_link:get_permalink($post);
		$title = $post->post_title;

		if ( empty( $post->post_title ) )
			$title = $previous ? __( 'Previous Post' ) : __( 'Next Post' );


		$title = apply_filters( 'the_title', $title, $post->ID );


		$rel =  'previous';

		$string = '<a href="' . $title_link . '" rel="'.$rel.'">';
		$inlink = str_replace( '%title', $title, $link );

		$inlink = $string . $inlink . '</a>';

		$output = str_replace( '%link', $inlink, $format );
	}


    return $output;
}
add_filter( 'previous_post_link', 'twentyseventeen_child_previous_link_change' ,10,5);