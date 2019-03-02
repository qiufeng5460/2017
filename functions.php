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

/*
 * 20190113:register left side menu. by zhong
*/
function twentyseventeen_child_register_left_side_menu($starter_content){
   
    	// register left menu
	register_nav_menus( array(		
		'left' => __( 'Left Menu', 'twentyseventeen_child' ),
	) );
    return $starter_content;
}
add_filter( 'twentyseventeen_starter_content', 'twentyseventeen_child_register_left_side_menu');

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

/*
 * 20190124:make left side menu. by zhong
 * 根据top menu被选择的item生成left menu
 * 20190129：把菜单的title单独处理
<nav class="left-navigation" > 
<div class="left_menu_title">走进七幼<"/div">   
<ul class="navbg1">
    <li class="left_current_item">园长寄语</li>
    <li>办园理念</li>
    <li>美丽七幼</li>
    <li>师资力量</li>
</ul>
</nav><!-- left-navigation -->
*/
function twentyseventeen_child_make_left_side_menu(){
        
        $parent_id=''; 
        $link='';
        $title='';
    
        $theme_location='top';
        $locations = get_nav_menu_locations();
        $menu = get_term( $locations[$theme_location], 'nav_menu' );
        $menu_items = wp_get_nav_menu_items($menu->term_id);
        
        $parent_id=$GLOBALS['current_menu_item']->menu_item_parent;
 
        //20190124:先将父菜单添加到第一行 by zhong
        //20190129:单独处理menu title
        $menu_list='<div class="left_menu_title">'."\n";    
        
        foreach($menu_items as $menu_item){
            
            if($parent_id==$menu_item->ID){
                $link=$menu_item->url;
                $title=$menu_item->title;
                
                $menu_list.=$title.'</div>'."\n";
            
                break;
            }
        }
        
        //20190124：随后依次添加子菜单
        $menu_list.='<ul class="navbg1">'."\n";
        
        foreach($menu_items as $menu_item){
            if($parent_id==$menu_item->menu_item_parent){
                $link=$menu_item->url;
                $title=$menu_item->title;
                
                //20190131:add .class for current item

                if($menu_item->ID==$GLOBALS['current_menu_item']->ID){
                   $menu_list.='<li class="left_current_item">'."\n";
                }
                else{
                   $menu_list.='<li>'."\n";
                }
                $menu_list.='<a href="'.$link.'">'.$title.'</a>' ."\n";
                $menu_list .= '</li>' ."\n";
                
            }
        }
        
        $menu_list .= '</ul>' ."\n";
        echo $menu_list;
}

/*
 * 20190124:wp_nav_menu_objects的filter中menu_item->current生效,获取当前menu_item存储于全局变量 by zhong
*/
function twentyseventeen_child_find_current_menu($sorted_menu_items){


       foreach($sorted_menu_items as $menu_item){

            if($menu_item->current){

                $GLOBALS['current_menu_item'] = $menu_item;
                break;
            }
        }
        //20190126:对于arhive list中的post link,选择某一个post时，没有current menu item为true,此时以current_item_parent判断. by zhong
        if(!isset($GLOBALS['current_menu_item'])){
            foreach($sorted_menu_items as $menu_item){

                if($menu_item->current_item_parent){

                    $GLOBALS['current_menu_item'] = $menu_item;
                    break;
                }
            }
        }
        
        //error_log(var_export($GLOBALS['current_menu_item'],true));
    return $sorted_menu_items;
}
add_filter( 'wp_nav_menu_objects', 'twentyseventeen_child_find_current_menu');

/*20190217:获取当前菜单的title*/
function twentyseventeen_child_get_current_menu_title(){
    
    return $GLOBALS['current_menu_item']->title;
}

/**
 * 20190203：add breadcrumbs navi for 2017 child theme 
 *
 */
function twentyseventeen_child_breadcrumbs(){
    $delimiter=' » ';
    $before='<span class="current_crumb">';
    $after='</span>';
    
        $theme_location='top';
        $locations = get_nav_menu_locations();
        $menu = get_term( $locations[$theme_location], 'nav_menu' );
        $menu_items = wp_get_nav_menu_items($menu->term_id);
        
        $parent_id=$GLOBALS['current_menu_item']->menu_item_parent;
    
        foreach($menu_items as $menu_item){
            
            if($parent_id==$menu_item->ID){
                $parent_title=$menu_item->title;
                break;
            }
        }
        $menu_location=$before;
        $menu_location .=__('Current');
        $menu_location .="：   ";
        $menu_location .=__('Home');
        $menu_location .=$delimiter;
        $menu_location .=$parent_title;        
        $menu_location .=$delimiter;
        $menu_location .=$GLOBALS['current_menu_item']->title;
        $menu_location .=$after;
        
        echo $menu_location;
        //echo $before . '当前位置：   ' . '首页' . ' ' . $delimiter . ' '. $parent_title . ' ' . $delimiter . ' ' . $GLOBALS['current_menu_item']->title . $after;
    
}
/**
 * 20190203：WordPress 添加面包屑导航,仅仅作为参考 
 * https://www.wpdaxue.com/wordpress-add-a-breadcrumb.html
 */
 
function cmp_breadcrumbs() {
	$delimiter = '»'; // 分隔符
	$before = '<span class="current">'; // 在当前链接前插入
	$after = '</span>'; // 在当前链接后插入
	if ( !is_home() && !is_front_page() || is_paged() ) {

		global $post;
		if ( is_category() ) { // 分类 存档
			global $wp_query;
			$cat_obj = $wp_query->get_queried_object();
			$thisCat = $cat_obj->term_id;
			$thisCat = get_category($thisCat);
			$parentCat = get_category($thisCat->parent);
			if ($thisCat->parent != 0){
				$cat_code = get_category_parents($parentCat, TRUE, ' ' . $delimiter . ' ');
				echo $cat_code = str_replace ('<a','<a itemprop="breadcrumb"', $cat_code );
			}
			echo $before . '' . single_cat_title('', false) . '' . $after;
		}    
		elseif ( is_single() && !is_attachment() ) { // 文章
			    // 文章 post
				$cat = get_the_category(); 
                                $cat = $cat[0];
				$cat_code = get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
				echo $cat_code = str_replace ('<a','<a itemprop="breadcrumb"', $cat_code );
				echo $before . get_the_title() . $after;
			
		} 
		elseif ( is_page() && !$post->post_parent ) { // 页面
                   
			echo $before . get_the_title() . $after;
		} 
		elseif ( is_page() && $post->post_parent ) { // 父级页面
			$parent_id  = $post->post_parent;
			$breadcrumbs = array();
			while ($parent_id) {
				$page = get_page($parent_id);
				$breadcrumbs[] = '<a itemprop="breadcrumb" href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
				$parent_id  = $page->post_parent;
			}
			$breadcrumbs = array_reverse($breadcrumbs);
			foreach ($breadcrumbs as $crumb) echo $crumb . ' ' . $delimiter . ' ';
			echo $before . get_the_title() . $after;
		}   
		
		echo '</div>';
	}
}
