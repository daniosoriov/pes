<?php
/**
 * @package pes
 */
?>
<?php global $smof_data; ?>
<?php 
$post_id = get_the_ID();
$post = get_post($post_id);
//echo '<pre>VIDEO POST '.print_r($post,1).'</pre>';

?>
<article id="post-<?php the_ID(); ?>" <?php post_class('single-post-inner'); ?>>
  <div class="cs-blog cs-blog-item">
    <div class="cs-blog-content">
      <div class="embed-container">
        <?php the_field('pes_video_embed'); ?>
      </div>
      <?php get_template_part('framework/templates/single/pes', 'header'); ?>
      <?php get_template_part('framework/templates/single/pes', 'tag'); ?>
    </div><!-- .entry-content -->
  </div>
</article><!-- #post-## -->