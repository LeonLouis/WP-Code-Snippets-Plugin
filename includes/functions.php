<?php
if ( ! defined( 'ABSPATH' ) ) {	exit; }

function lcs_set_options() {
  update_option('thread_comments_depth', 3);
  update_option('thread_comments', 1);
  update_option('page_comments', 1);
  update_option('comments_per_page', 3);
}

function lcs_breadcrumbs() {
  $post_type_label = 'Snippets';
  $post_type_slug = 'snippets';
  $post_type = get_post_type();

  if ( $post_type ) {
    $post_type_data = get_post_type_object( $post_type );
    $post_type_slug = $post_type_data->rewrite['slug'];
    $post_type_label = $post_type_data->label;
  }

  echo '<a href="'.home_url().'" rel="nofollow">Home</a>';
  echo "&nbsp;&nbsp;&#187;&nbsp;&nbsp;";
  echo '<a href="'.home_url().'/'.$post_type_slug.'" rel="nofollow">'.$post_type_label.'</a>';
  if (is_category() || is_single()) {
    the_category(' &bull; ');
    if (is_single()) {
      echo " &nbsp;&nbsp;&#187;&nbsp;&nbsp; ";
      the_title();
    }
  } elseif (is_page()) {
    echo the_title();
  } elseif (is_search()) {
    echo "&nbsp;&nbsp;&#187;&nbsp;&nbsp;Search Results for... ";
    echo '"<em>';
    echo the_search_query();
    echo '</em>"';
  } elseif (is_author()) {
    echo "&nbsp;&nbsp;&#187;&nbsp;&nbsp;Snippets by ";
    echo '<em>';
    echo get_the_author_meta('display_name');
    echo '</em>';
  }
}

add_filter('comments_template', 'snippet_comment');
function snippet_comment($template) {
  global $post;

  if ( $post->post_type == 'lcs-snippets' && is_single() ) {
    if ( file_exists( LCS_SNIPPETS_PATH . 'templates/comment.php' ) ) {
      return LCS_SNIPPETS_PATH . 'templates/comment.php';
    }
  }

  return $template;
}

add_filter( 'comments_open', 'lcs_comments_open', 10, 2 );
function lcs_comments_open( $open, $post_id ) {
  $post = get_post( $post_id );

  if ( $post->post_type == 'lcs-snippets' )
    $open = true;
    
  return $open;
}

add_action( 'widgets_init', 'lcs_custom_sidebar' );
function lcs_custom_sidebar() {
	register_sidebar( array(
		'name'          => __( 'Snippets Sidebar', 'lcs-snippets' ),
		'id'            => 'lcs-customs-sidebar-main',
		'description'   => __( 'Widgets in this area will be shown on single snippets page.', 'lcs-snippets' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="LCS Custom Sidebar">',
		'after_title'   => '</h2>',
	) );
}


