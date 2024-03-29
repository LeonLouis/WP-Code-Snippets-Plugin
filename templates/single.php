<?php

  if ( wp_is_block_theme() ) {
    $header = do_blocks('<!-- wp:template-part {"slug":"header"} /-->');
    $footer = do_blocks('<!-- wp:template-part {"slug":"footer"} /-->');
    wp_head();
    echo $header;
  } else {
    get_header();
  }

  $tags = get_the_terms(get_the_ID(), 'lcs_snippet_tag');
  $code = get_post_meta(get_the_ID(), 'lcs_code_value', true);
?>

<div class="snippet-container">
    <?php if ( have_posts() ) {
      while ( have_posts() ) : the_post(); ?>
        <div class="main-snippet">
        <?php if( !empty(lcs_is_show_breadcrumb()) ): ?>
          <div id="lcs-breadcrumbs-wrapper">
              <?php lcs_breadcrumbs(); ?>
          </div>
        <?php endif; ?>
          <div class="snippet-wrap">
            <div class="snippet-title">
              <h1><?php the_title(); ?></h1>
            </div>
            <div class="snippet-content">
              <div class="snippet-author">by <a href="<?php echo get_author_posts_url($post->post_author); ?>"><?php the_author_meta('display_name'); ?></a>, <?php the_date(); ?></div>
              <?php if(!empty($tags)): ?>
                <div class="snippet-tags">
                  <?php foreach($tags as $tag){ ?>
                      <a href="<?php echo get_term_link($tag->term_id, $tag->taxonomy); ?>">#<?php echo $tag->name;?></a>
                  <?php } ?>
                </div>
              <?php endif; ?>
              <div id="main-content">
                <?php the_content(); ?>
                <?php if(!empty($code)): ?>
                  <pre>
                    <code><?php echo $code; ?></code>
                  </pre>
                  <script type="text/javascript">
                    hljs.highlightAll();
                  </script>
                <?php endif; ?>
              </div>
            </div>
          </div>
          <?php comments_template(); ?>
        </div>
    <?php 
      endwhile;
      if ( is_active_sidebar( 'lcs-customs-sidebar-main' ) ) {
      ?>
        <div class="lcs-sidebar">
          <?php dynamic_sidebar('lcs-customs-sidebar-main'); ?>
        </div>
    <?php
      }
    }
    ?>
</div>

<?php 
  if( wp_is_block_theme() ) {
    echo $footer;
  } else {
    get_footer();
  }
?>
