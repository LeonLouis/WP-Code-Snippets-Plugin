<?php
if ( ! defined( 'ABSPATH' ) ) {	exit; }

if ( ! class_exists( 'LCS_POST_TYPE', false ) ) :

  class LCS_POST_TYPE {
    function __construct() {
      add_action( 'init', array( $this, 'lcs_create_snippets_cpt' ), 0 );
      add_action( 'init', array( $this, 'create_snippets_taxonomies' ), 0 );
      add_action( 'init', array( $this, 'create_snippet_tag_taxonomies' ), 0 );
    }

    // Register Custom Post Type
    public static function lcs_create_snippets_cpt() {

      $labels = array(
        'name'                      => _x( 'Snippets', 'Post Type General Name', 'lcs-snippets' ),
        'singular_name'             => _x( 'Snippet', 'Post Type Singular Name', 'lcs-snippets' ),
        'menu_name'                 => _x( 'Snippets', 'Admin Menu text', 'lcs-snippets' ),
        'name_admin_bar'            => _x( 'Snippets', 'Add New on Toolbar', 'lcs-snippets' ),
        'parent_item_colon'         => __( 'Parent Snippets:', 'lcs-snippets' ),
        'all_items'                 => __( 'All Snippets', 'lcs-snippets' ),
        'add_new_item'              => __( 'Add New Snippet', 'lcs-snippets' ),
        'add_new'                   => __( 'Add New', 'lcs-snippets' ),
        'new_item'                  => __( 'New Snippet', 'lcs-snippets' ),
        'edit_item'                 => __( 'Edit Snippet', 'lcs-snippets' ),
        'update_item'               => __( 'Update Snippet', 'lcs-snippets' ),
        'view_item'                 => __( 'View Snippet', 'lcs-snippets' ),
        'view_items'                => __( 'View Snippets', 'lcs-snippets' ),
        'search_items'              => __( 'Search Snippet', 'lcs-snippets' ),
        'not_found'                 => __( 'Not found', 'lcs-snippets' ),
        'not_found_in_trash'        => __( 'Not found in Trash', 'lcs-snippets' ),
      );
      $args = array(
        'description'        => __( 'Snippets', 'lcs-snippets' ),
        'labels'             => $labels,
        'menu_icon'          => 'dashicons-editor-code',
        'supports'           => array('title', 'editor', 'revisions', 'author', 'custom-fields', 'comments'),
        'taxonomies'         => array( 'snippet_category','snippet_tag' ),
        'public'             => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'menu_position'      => 5,
        'has_archive'        => true,
        'hierarchical'       => true,
        'publicly_queryable' => true,
        'rewrite'            => array( 'slug' => 'snippets' ),
        // 'show_in_rest'       => true,
        'capability_type'    => 'post',
      );

      register_post_type( 'lcs-snippets', $args );
    }

    // Register Taxonomy
    public static function create_snippets_taxonomies() {

      $labels = array(
        'name'              => _x( 'Snippet Categories', 'taxonomy general name', 'lcs-snippets' ),
        'singular_name'     => _x( 'Snippet Category', 'taxonomy singular name', 'lcs-snippets' ),
        'search_items'      => __( 'Search Snippet Categories', 'lcs-snippets' ),
        'all_items'         => __( 'All Snippet Categories', 'lcs-snippets' ),
        'parent_item'       => __( 'Parent Snippet Category', 'lcs-snippets' ),
        'parent_item_colon' => __( 'Parent Snippet Category:', 'lcs-snippets' ),
        'edit_item'         => __( 'Edit Snippet Category', 'lcs-snippets' ),
        'update_item'       => __( 'Update Snippet Category', 'lcs-snippets' ),
        'add_new_item'      => __( 'Add New Snippet Category', 'lcs-snippets' ),
        'new_item_name'     => __( 'New Snippet Category Name', 'lcs-snippets' ),
        'menu_name'         => __( 'Snippet Categories', 'lcs-snippets' ),
      );

      $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'snippet_cat' ),
      );

      register_taxonomy( 'lcs_snippet_category', array( 'lcs-snippets' ), $args );
    }

    // Register Tag
    public static function create_snippet_tag_taxonomies() {
      $labels = array(
        'name' 											 => _x( 'Snippet Tags', 'taxonomy general name', 'lcs-snippets' ),
        'singular_name' 						 => _x( 'Snippet Tag', 'taxonomy singular name', 'lcs-snippets' ),
        'search_items' 			 				 =>  __( 'Search Snippet Tags', 'lcs-snippets' ),
        'popular_items' 						 => __( 'Popular Snippet Tags', 'lcs-snippets' ),
        'all_items' 								 => __( 'All Snippet Tags', 'lcs-snippets' ),
        'edit_item' 								 => __( 'Edit Snippet Tag', 'lcs-snippets' ), 
        'update_item' 							 => __( 'Update Snippet Tag', 'lcs-snippets' ),
        'add_new_item' 							 => __( 'Add New Snippet Tag', 'lcs-snippets' ),
        'new_item_name' 						 => __( 'New Snippet Tag Name', 'lcs-snippets' ),
        'separate_items_with_commas' => __( 'Separate snippet tags with commas', 'lcs-snippets' ),
        'add_or_remove_items' 			 => __( 'Add or remove snippet tags', 'lcs-snippets' ),
        'choose_from_most_used' 		 => __( 'Choose from the most used snippet tags', 'lcs-snippets' ),
        'menu_name' 								 => __( 'Snippet Tags', 'lcs-snippets' ),
      ); 

      $args = array(
        'hierarchical' => false,
        'labels' => $labels,
        'show_ui' => true,
        'update_count_callback' => '_update_post_term_count',
        'query_var' => true,
        'rewrite' => array( 'slug' => 'snippet_tag' ),
      );

      register_taxonomy( 'lcs_snippet_tag', array( 'lcs-snippets' ), $args );
    }
  }

endif;

new LCS_POST_TYPE;
