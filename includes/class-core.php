<?php
if ( ! defined( 'ABSPATH' ) ) {	exit; }

if ( ! class_exists( 'LCS_CORE', false ) ) :

  class LCS_CORE {
    function __construct() {
      $this->init_hooks();
    }

    private function init_hooks() {
      // Actions
      add_action( 'admin_menu', array($this, 'lcs_set_settings') );
      add_action( 'admin_init', array($this, 'lcs_register_settings' ) );
      add_action( 'wp_head', array($this, 'lcs_archive_metadesc') );
      add_action( 'pre_get_posts', array($this, 'lcs_author_query') );
      add_action( 'wp_head', array($this, 'lcs_dynamic_style') );
      add_action( 'add_meta_boxes', array( $this, 'lcs_meta_box' ) );
      add_action( 'save_post', array( $this, 'lcs_save_code' ) );

      // Filters
      add_filter( 'single_template', array($this, 'lcs_template') );
      add_filter( 'archive_template', array($this, 'lcs_template') );
      add_filter( 'search_template', array($this, 'lcs_template') );

      // Shortcode
      add_shortcode( 'lcs_code', array($this, 'lcs_code_sc') );
    }

    public function lcs_set_settings() {
      add_submenu_page(
        'edit.php?post_type=lcs-snippets',
        __( 'Snippets Settings', 'lcs-snippets' ),
        __( 'Settings', 'lcs-snippets' ),
        'manage_options',
        'lcs-snippets-settings',
        array($this, 'lcs_snippets_settings_page_callback')
      );
    }

    public function lcs_snippets_settings_page_callback() {
      include_once LCS_SNIPPETS_PATH . 'templates/admin-settings.php';
    }

    public function lcs_register_settings(){
      register_setting( 'lcs_settings_group', 'lcs_color_scheme' );
      register_setting( 'lcs_settings_group', 'lcs_show_breadcrumb' );
      register_setting( 'lcs_settings_group', 'lcs_num_show_snippets' );
    }

    public function lcs_archive_metadesc(){
      if( !is_archive() && !is_post_type_archive('lcs-snippets') ){
        return false;
      }
      echo '<meta name="description" content="'.get_bloginfo('name').' '.esc_html('code snippets that will improve your coding productivity.', 'lcs-snippets' ).'" />';
    }

    public function lcs_author_query($query) {
      if ( !is_admin() && $query->is_main_query() ) {
        if (is_post_type_archive('lcs-snippets') || is_tax(array('lcs_snippet_tag','lcs_snippet_category'))) {
          $query->set('posts_per_page', lcs_get_num_snippets());
        }

        if ($query->is_author()) {
          $query->set('post_type', array('post', 'lcs-snippets'));
          $query->set('posts_per_page', lcs_get_num_snippets());
        }
      }
    }

    public function lcs_template($template) {
      global $post;   
      if( $post && $post->post_type !=  'lcs-snippets'){
        return $template;
      }
      if( is_single() ){
        if ( file_exists( LCS_SNIPPETS_PATH . 'templates/single.php' ) ) {
          return LCS_SNIPPETS_PATH . 'templates/single.php';
        }
      }elseif( is_archive() || is_search()  ){
        if ( file_exists( LCS_SNIPPETS_PATH . 'templates/archive.php' ) ) {
          return LCS_SNIPPETS_PATH . 'templates/archive.php';
        }
      }
      return $template;
    }

    public function lcs_code_sc( $atts, $content = null ) {
      $a = shortcode_atts( array(
        'language' => 'php'
      ), $atts );
    
      ob_start();
      ?>
        <pre>
          <code class="language-<?php echo $a['language']; ?>"><?php echo $content; ?></code>
        </pre>
        <script type="text/javascript">
          hljs.highlightAll();
        </script>
      <?php
      return ob_get_clean();
    }

    public function lcs_dynamic_style() {
      if(!is_singular('lcs-snippets') && !is_post_type_archive('lcs-snippets') && !is_tax(array('lcs_snippet_tag','lcs_snippet_category')) && !is_author()){
        return;
      }
      ?>
        <style type="text/css">
          :root{
            --lcs-primary: <?php echo lcs_get_color_scheme(); ?>;
          }
        </style>
      <?php
    }

    public function lcs_meta_box( $post_type ) {
      $post_types = array( 'lcs-snippets' );

      if ( in_array( $post_type, $post_types ) ) {
        add_meta_box(
          'highlighter_textarea',
          __( 'LCS Code', 'textdomain' ),
          array( $this, 'render_meta_box_content' ),
          $post_type,
          'advanced',
          'high'
        );
      }
    }

    public function render_meta_box_content( $post ) {
      wp_nonce_field( 'lcs_textarea_code', 'lcs_textarea_code_nonce' );
      $code = get_post_meta( $post->ID, 'lcs_code_value', true );
      ?>
        <textarea cols="40" rows="10" name="lcs_code_value" id="lcs_code_value"><?php echo $code; ?></textarea>
        <em style="color:#b32d2e">Note: Only codes should be added here.</em>
      <?php
    }

    public function lcs_save_code( $post_id ) {
      if ( ! isset( $_POST['lcs_textarea_code_nonce'] ) ) {
        return $post_id;
      }
  
      $nonce = $_POST['lcs_textarea_code_nonce'];
      if ( ! wp_verify_nonce( $nonce, 'lcs_textarea_code' ) ) {
        return $post_id;
      }
  
      update_post_meta( $post_id, 'lcs_code_value', $_POST['lcs_code_value'] );
    }
  }

endif;

new LCS_CORE;
