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
 * 20190305:adds support for excerpts in pages
*/
function twentyseventeen_child_init() {
     add_post_type_support('page', 'excerpt');
}
add_action('init', 'twentyseventeen_child_init');


/**
 * 20190316:Enqueue scripts.
 */
function twentyseventeen_child_scripts() {

    //首页幻灯片
    wp_enqueue_script( 'plugslides', get_theme_file_uri( '/assets/js/plug.slides2.2.js' ), array( 'jquery' ), '2.1.2', true );
    //首页老师照片播放
    wp_enqueue_script( 'jzoro', get_theme_file_uri( '/assets/js/jzoro2.js' ), array( 'jquery' ), '2.1.2', true );
}
add_action( 'wp_enqueue_scripts', 'twentyseventeen_child_scripts' );

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

/**
 * 20190413：在frontpage添加分类列表
 */
 
function twentyseventeen_child_postlist_frontpage($cat_slug='') {
        if($cat_slug){
        $cat=get_category_by_slug($cat_slug);
        if(!$cat){
            echo "It is wrong for category slug!";
        }
        else{
        //20190413:display cat name for post list in frontpage 
        $cat_frontpage = '<div class="cat_name_frontpage">';
        $cat_frontpage.= '<p>'.get_cat_name($cat->term_id).'</p>';
        $cat_frontpage.= '<a href="'.get_category_link($cat->term_id).'">更多>>></a>';
        $cat_frontpage.= '</div>'; 
        
        //20190413:display post list in frontpage
        //20190321:在首页加postlist，and是交集，in是合集
        //$args = array('category__in' =>array(3,4), 'showposts' => 5 );
        //20190411:第二行改成两列
        $args = array('cat' =>$cat->term_id, 'showposts' => 5 );
        query_posts($args);
        while (have_posts()) : the_post();
        $cat_frontpage.= '<div class="one_post"> ';
        //20190414:check wx_link firstly, if not, open default link.
        $title_link=get_post_meta(get_the_id(),'wx_link',true);
        $title_link=$title_link ? $title_link:get_permalink();
        
        $cat_frontpage.= '<li class="title"><a target="_blank" href="'.esc_url( $title_link ).'">'.wp_trim_words(get_the_title(),18).'</a></li>';
        $cat_frontpage.= '<li class="date">'.get_the_date().'</li>';
        $cat_frontpage.= '</div>';
        endwhile; 
        wp_reset_query();
        echo $cat_frontpage;
        }
        }
}

/**
 * 20190416：WordPress中给媒体文件添加分类和标签的PHP功能实现,
 * Media Library Categories这个插件同样可以达到目的
 */
 
function twentyseventeen_child_add_categories_tags_to_attachments(){
    register_taxonomy_for_object_type('category','attachment');
    register_taxonomy_for_object_type('post_tag','attachment');   
}
add_action('init','twentyseventeen_child_add_categories_tags_to_attachments');   

/**
 * 20190417：将附件（image）关联到一个页面，然后循环获取该页面的附件用以显示，比如在首页实现幻灯片等
 * 将附件分类后没有办法从分类直接获取附件？
 * 因为在plug.slides2.2.js中使用了slides的li，所以在function中固定li的class为sildes
 */
 
function twentyseventeen_child_get_attachment_in_post($post_slug=''){
     
    $image_slides='';
    //20190417:根据slug获取指定post的所有image附件
    if($post_slug){
       $media = get_attached_media( 'image', get_page_by_path($post_slug) );
       if(!$media)
       {echo "this is wrong post slug"; }
    }
    else{
        return;
    }
    
    foreach($media as $v){
       //20190417:根据attachment_id获取附件地址       
       $image_attributes = wp_get_attachment_image_src( $v->ID,'full' ); // 返回一个数组
       if( $image_attributes ) {

          $image_slides.='<li class="slides"><img src="'.$image_attributes[0].'"/></li>';
        } 
    }
    echo $image_slides;
}

/**
 * 20190417：在首页以polaroid效果显示社团分类链接
 */
function twentyseventeen_child_cat_in_polaroid($cat_slug='',$image_id=0){
                        
        
        $cat=get_category_by_slug($cat_slug);
        $image_attributes = wp_get_attachment_image_src( $image_id,'full' );                
        
        $cat_polaroid='<div class="polaroid">';
        $cat_polaroid.='<a href="'.get_category_link($cat->term_id).'">';
        $cat_polaroid.='<img src="'.$image_attributes[0].'"/>';         
        $cat_polaroid.='<p class="caption">'.get_cat_name($cat->term_id).'</p>';
        $cat_polaroid.='</a></div>';
        echo $cat_polaroid;
}

/**
 * 20190421:Display a front page section.
 *
 * 
 */
function twentyseventeen_child_frontpage_section() {
       $theme_row_content=get_theme_mod('row_content');
       foreach($theme_row_content as $k=>$v){
           var_dump($k);
           var_dump($v);
       }

}

/**
 * 20190421:Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function twentyseventeen_child_setup() {
    		// Set the front page row content name(content+key.php) and whether display the fixed background image 
		$theme_row_content = array(
			'row-1' => false,
			'row-2' => true,
			'row-3' => false,
			'row-4' => true,
                        'row-5' => false,
		);
                set_theme_mod('row_content',$theme_row_content);
                 //   $num_sections = count($theme_row_content);
               //error_log(var_export($num_sections,true)); 
}
add_action( 'after_setup_theme', 'twentyseventeen_child_setup' );

/**
 * 20190424
 * WordPress有原生的文章置顶功能，不过只支持在首页让置顶文章在顶部显示，
 * 其他如分类页、标签页、作者页和日期页等存档页面，就没法让置顶文章在顶部显示了，只能按默认的顺序显示。
 * 有很多网友早前向我问过怎么解决这样的问题，当时查阅了一些资料没有解决就被搁置了。
 * 现在参考wp-includes/query.php中首页置顶的代码，稍微修改一下，可以让分类页、标签页、
 * 作者页和日期页等存档页面也能像首页一样在顶部显示其范围内的置顶文章。
 * 把下面的代码放到当前主题下的functions.php中就可以了：
 * 原文出处：露兜博客 https://www.ludou.org/wordpress-sticky-posts-in-archive.html
 */

function putStickyOnTop($posts) {
   if(is_home() || !is_main_query() || !is_archive())
    return $posts;
    
  global $wp_query;

  // 获取所有置顶文章
  $sticky_posts = get_option('sticky_posts');


  if ( $wp_query->query_vars['paged'] <= 1 && !empty($sticky_posts) && is_array($sticky_posts) && !get_query_var('ignore_sticky_posts') ) {
    $stickies1 = get_posts( array( 'post__in' => $sticky_posts ) );
    foreach ( $stickies1 as $sticky_post1 ) {
      // 判断当前是否分类页 
      if($wp_query->is_category == 1 && !has_category($wp_query->query_vars['cat'], $sticky_post1->ID)) {
        // 去除不属于本分类的置顶文章
        $offset1 = array_search($sticky_post1->ID, $sticky_posts);
        unset( $sticky_posts[$offset1] );
      }
      if($wp_query->is_tag == 1 && !has_tag($wp_query->query_vars['tag'], $sticky_post1->ID)) {
        // 去除不属于本标签的文章
        $offset1 = array_search($sticky_post1->ID, $sticky_posts);
        unset( $sticky_posts[$offset1] );
      }
      if($wp_query->is_year == 1 && date_i18n('Y', strtotime($sticky_post1->post_date))!=$wp_query->query['m']) {
        // 去除不属于本年份的文章
        $offset1 = array_search($sticky_post1->ID, $sticky_posts);
        unset( $sticky_posts[$offset1] );
      }
      if($wp_query->is_month == 1 && date_i18n('Ym', strtotime($sticky_post1->post_date))!=$wp_query->query['m']) {
        // 去除不属于本月份的文章
        $offset1 = array_search($sticky_post1->ID, $sticky_posts);
        unset( $sticky_posts[$offset1] );
      }
      if($wp_query->is_day == 1 && date_i18n('Ymd', strtotime($sticky_post1->post_date))!=$wp_query->query['m']) {
        // 去除不属于本日期的文章
        $offset1 = array_search($sticky_post1->ID, $sticky_posts);
        unset( $sticky_posts[$offset1] );
      }
      if($wp_query->is_author == 1 && $sticky_post1->post_author != $wp_query->query_vars['author']) {
        // 去除不属于本作者的文章
        $offset1 = array_search($sticky_post1->ID, $sticky_posts);
        unset( $sticky_posts[$offset1] );
      }
    }

    $num_posts = count($posts);

    $sticky_offset = 0;
    // Loop over posts and relocate stickies to the front.
    for ( $i = 0; $i < $num_posts; $i++ ) {
      if ( in_array($posts[$i]->ID, $sticky_posts) ) {
        $sticky_post = $posts[$i];
        // Remove sticky from current position
        array_splice($posts, $i, 1);
        // Move to front, after other stickies
        array_splice($posts, $sticky_offset, 0, array($sticky_post));
        // Increment the sticky offset. The next sticky will be placed at this offset.
        $sticky_offset++;
        // Remove post from sticky posts array
        $offset = array_search($sticky_post->ID, $sticky_posts);
        unset( $sticky_posts[$offset] );
      }
    }

    // If any posts have been excluded specifically, Ignore those that are sticky.
    if ( !empty($sticky_posts) && !empty($wp_query->query_vars['post__not_in'] ) ){
       $sticky_posts = array_diff($sticky_posts, $wp_query->query_vars['post__not_in']);
    }

    // Fetch sticky posts that weren't in the query results
    if ( !empty($sticky_posts) ) {
      $stickies = get_posts( array(
        'post__in' => $sticky_posts,
        'post_type' => $wp_query->query_vars['post_type'],
        'post_status' => 'publish',
        'nopaging' => true
      ) );

      foreach ( $stickies as $sticky_post ) {
        array_splice( $posts, $sticky_offset, 0, array( $sticky_post ) );
        $sticky_offset++;
      }
    }
  }
  
  return $posts;
}
add_filter('the_posts',  'putStickyOnTop' );