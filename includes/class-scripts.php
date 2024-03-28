<?php
if ( ! defined( 'ABSPATH' ) ) {	exit; }

if ( ! class_exists( 'LCS_SCRIPTS', false ) ) :

  class LCS_SCRIPTS {
    function __construct() {
      add_action( 'wp_enqueue_scripts', array( $this, 'lcs_enqueue_scripts' ) );
      add_action( 'admin_enqueue_scripts', array( $this, 'lcs_admin_scripts' ) );
    }

    public function lcs_enqueue_scripts() {
      if(!is_singular('lcs-snippets') && !is_post_type_archive('lcs-snippets') && !is_tax(array('lcs_snippet_tag','lcs_snippet_category')) && !is_author()){
        return false;
      }
    
        // Enqueue Styles
        wp_enqueue_style( 'highlight-style', LCS_SNIPPETS_URL.'assets/css/atom-one-dark.min.css');
        wp_enqueue_style( 'lcs-snippets-style', LCS_SNIPPETS_URL.'assets/css/style.css', array(), '1.0');
    
        // Enqueue Scripts
        wp_enqueue_script( 'highlight-scripts', LCS_SNIPPETS_URL.'assets/js/highlight.min.js');
    }

    public function lcs_admin_scripts() {
      $my_current_screen = get_current_screen();
      if($my_current_screen->id !== 'lcs-snippets_page_lcs-snippets-settings') {
        return false;
      }

      wp_enqueue_style( 'wp-color-picker' );
      wp_enqueue_script( 'lcs-admin-script', LCS_SNIPPETS_URL.'assets/js/admin-settings.js', array( 'wp-color-picker' ), false, true );
    }
  }

endif;

new LCS_SCRIPTS;
