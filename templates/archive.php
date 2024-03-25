<?php
  if ( wp_is_block_theme() ) {
    $header = do_blocks('<!-- wp:template-part {"slug":"header"} /-->');
    $footer = do_blocks('<!-- wp:template-part {"slug":"footer"} /-->');
    include LCS_SNIPPETS_PATH . 'templates/header.php';
    echo $header;
  } else {
    get_header();
  }

  $term = get_queried_object();
  $args = array(
    'post_type'			  => 'lcs-snippets',
    'order'				    => 'DESC',
    'posts_per_page'	=> 15,
    'paged'				    => (get_query_var('paged')) ? get_query_var('paged') : 1,
  );

  if(is_author()){
    $author = get_user_by( 'slug', get_query_var( 'author_name' ) );
    $args['author'] = $author->ID;
  }

  if(isset($term->term_id) && !empty($term->term_id)){
    $args['tax_query'] = array(
      array (
        'taxonomy' => $term->taxonomy,
        'field'    => 'slug',
        'terms'    => $term->name,
      )
    );
  }

  if(isset($_GET['s'])){
    $search_result = $_GET['s'] ? : '';
    $args['s'] = $search_result;
  }
  $snippets = new WP_Query( $args );
  $total_pages = $snippets->max_num_pages;
?>

<div class="lcs-archive-wrap">
  <div id="lcs-breadcrumbs-wrapper">
      <?php lcs_breadcrumbs(); ?>
  </div>
  <div id="lcs-snippets-search-form">
    <form role="search" method="get" class="search-form">
      <input type="search" class="search-field" placeholder="Search for:" value="<?php echo isset($_GET['s']) ? $_GET['s'] : ''; ?>" name="s" />
      <input type="submit" class="search-submit" value="Search Snippet" />
    </form>
  </div>
</div>
<div id="lcs-snippets-wrapper">
    <div class="snippet-container">
      <?php if ( $snippets->have_posts() ):
        while ( $snippets->have_posts() ) : $snippets->the_post(); ?>
          <?php $tags = get_the_terms(get_the_ID(), 'lcs_snippet_tag'); ?>
          <?php include LCS_SNIPPETS_PATH . 'templates/archive-single.php'; ?>
        <?php endwhile; ?>
        
        <?php if( $total_pages > 1 ): ?>
          <div class="snippet-page-buttons">
          <?php
            $big = 999999999;
            echo paginate_links( array(
            'base' => str_replace( $big, '%#%', get_pagenum_link( $big ) ),
            'format' => '?paged=%#%',
            'current' => max( 1, get_query_var('paged') ),
            'total' => $total_pages,
            'prev_text' => '&laquo;',
            'next_text' => '&raquo;'
            ) );
          ?>
          </div>
        <?php endif; ?>
        <?php else: ?>
          <div class="nf-msg"><?php esc_html_e('Sorry, No snippoets were found.', 'lcs-snippets' ); ?></div>
      <?php endif; ?>
    </div>
</div>

<?php 
  if( wp_is_block_theme() ) {
    echo $footer;
  } else {
    get_footer();
  }
?>