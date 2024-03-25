<div class="main-snippet">
  <div class="snippet-wrap">
    <div class="snippet-title">
      <a href="<?php the_permalink(); ?>"><h1><?php the_title(); ?></h1></a>
    </div>
    <div class="snippet-content">
    <div class="snippet-author">by <a href="<?php echo get_author_posts_url($post->post_author); ?>"><?php the_author_meta('display_name'); ?></a>, <?php  echo get_the_date(); ?></div>
    <?php if(!empty($tags)): ?>
      <div class="snippet-tags">
        <?php foreach($tags as $tag){ ?>
          <a href="<?php echo get_term_link($tag->term_id, $tag->taxonomy); ?>">#<?php echo $tag->name;?></a>
        <?php } ?>
      </div>
    <?php endif; ?>
    <div class="snippet-shortdesc">
      <?php echo wp_trim_words(get_the_excerpt(), 20, '...'); ?>
    </div>
    </div>
  </div>
</div>