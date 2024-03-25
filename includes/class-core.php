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
      $color_scheme = !empty(get_option('lcs_color_scheme', null )) ? get_option('lcs_color_scheme', null ) : '#0697f2';
      $show_breadcrumb = !empty(get_option('lcs_show_breadcrumb', null )) ? 'checked' : null;
      $show_snippets = !empty(get_option('lcs_num_show_snippets', null )) ? get_option('lcs_num_show_snippets', null ) : '15';

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
        if ($query->is_author()) {
          $query->set('post_type', array('post', 'lcs-snippets'));
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
        <div class="snippet-code">
          <pre>
            <code class="language-<?php echo $a['language']; ?>"><?php echo $content; ?></code>
          </pre>
        </div>
        <script type="text/javascript">
          hljs.highlightAll();
        </script>
      <?php
      return ob_get_clean();
    }
  }

endif;

new LCS_CORE;
