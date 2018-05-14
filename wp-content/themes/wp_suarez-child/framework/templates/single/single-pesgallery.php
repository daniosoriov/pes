<?php
/**
 * @package pes
 */
?>
<?php global $smof_data; ?>
<?php 
$post_id = get_the_ID();
$images = get_field('pes_gallery');
$user_ID = get_current_user_id();
$download = (get_query_var('gallery_action', '') == 'download') ? TRUE : FALSE;

$gallery_show = get_query_var('gallery_show', 24);
$number_by_page = ($gallery_show != 'all') ? $gallery_show : 24;
$paged = absint(get_query_var('gallery_page', 1));
if (filter_var($paged, FILTER_VALIDATE_INT) === false) {
  $paged = 1;
}
$total_images = count($images);

//$post = get_post($post_id);
//echo '<pre>'.print_r($images,1).'</pre>';

$total_pages = floor($total_images / $number_by_page) + (($total_images % $number_by_page) ? 1 : 0);
$paged_images = array(0 => $images);
if ($total_pages > 1 && $gallery_show != 'all') {
  $paged_images = array_chunk($images, $number_by_page);
}

$login_attributes = array(
  'profile_link' => false,
  'template' => 'default',
  'registration' => true,
  'redirect' => false,
  'remember' => true
);

?>
<article id="post-<?php the_ID(); ?>" <?php post_class('single-post-inner'); ?>>
  <div class="cs-blog cs-blog-item pes-gallery-item">
    <div class="cs-blog-content">
      
      <?php if (!$download): ?>
      <?php get_template_part('framework/templates/single/pes', 'header'); ?>
      <section class="pes-section pes-gallery-section">
        <?php
        $count = 0;
        if ($images): ?>
        <div class="gallery-pagination top">
          <?php if ($total_pages > 1): ?>
          <div class="gallery-show">
            View by page:&nbsp;
            <?php echo (($gallery_show == $number_by_page) ? $number_by_page : '<a href="'. get_permalink() .'?gallery_show='. $number_by_page .'">'. $number_by_page .'</a>') ?>
            &nbsp;|&nbsp;
            <?php echo (($gallery_show == 'all') ? 'All' : '<a href="'. get_permalink() .'?gallery_show=all">All</a>') ?>
          </div>
          <?php endif; ?>
          <?php if ($gallery_show != 'all'): ?>
            <?php echo pes_get_gallery_pagination($total_images, $paged, $number_by_page); ?>
          <?php endif; ?>
        </div>
        <div class="item-wrapper">
          <?php foreach ($paged_images[$paged - 1] as $image): ?>
          <div class="item-capsule-wrapper">
            <a href="<?php echo get_attachment_link($image['id']); ?>" 
               class="item-link" 
               title="<?php echo $image['title'] .' - '. $image['caption']; ?>" >
              <div id="<?php echo $image['id'] ?>" 
                   class="item-capsule"
                   alt="<?php echo $image['alt']; ?>"
                   style="background-image:url(<?php echo $image['sizes']['large']; ?>)">
              </div>
              <div class="item-desc">
                <span class="item-title"><?php echo $image['title']; ?></span>
                <?php if ($image['caption']): ?>
                <span class="item-caption"><?php echo shorten_string($image['caption'], 13) ?></span>
                <?php endif; ?>
              </div>
            </a>
          </div>
        <?php endforeach; ?>
        </div>
        <div class="gallery-pagination bottom">
          <?php if ($total_pages > 1): ?>
          <div class="gallery-show">
            View by page:&nbsp;
            <?php echo (($gallery_show == $number_by_page) ? $number_by_page : '<a href="'. get_permalink() .'?gallery_show='. $number_by_page .'">'. $number_by_page .'</a>') ?>
            &nbsp;|&nbsp;
            <?php echo (($gallery_show == 'all') ? 'All' : '<a href="'. get_permalink() .'?gallery_show=all">All</a>') ?>
          </div>
          <?php endif; ?>
          <?php if ($gallery_show != 'all'): ?>
            <?php echo pes_get_gallery_pagination($total_images, $paged, $number_by_page); ?>
          <?php endif; ?>
        </div>
        <?php endif; ?>
      </section>
      
      <?php get_template_part('framework/templates/single/pes', 'tag'); ?>
      
      <?php 
      else:
        if (!$user_ID):?>
          <div class="pes-gallery-login">
            <div class="tdyml">
              <p><strong>To select and download photos from this gallery you must be logged in.</strong></p>
            </div>
            <?php if (function_exists('login_with_ajax')) login_with_ajax($login_attributes); ?>
          </div>
        <?php else: ?>
        <?php get_template_part('framework/templates/single/pes', 'header'); ?>
        <div>
          <p><span id="totalSelected">0</span> of <?php echo count($images) ?> photos selected.</p>
          <section class="pes-section pes-gallery-section-download">
            <?php if ($images): ?>
            <div class="item-wrapper">
              <span id="allow-downloads"></span>
              <?php foreach ($images as $image): ?>
              <div class="item-capsule-wrapper">
                <div id="<?php echo $image['id'] ?>" 
                     class="item-capsule download" 
                     title="<?php echo $image['title'] .' - '. $image['caption']; ?>" 
                     alt="<?php echo $image['alt']; ?>"
                     style="background-image:url(<?php echo $image['sizes']['large']; ?>)">
                  <i class="fa fa-check-square"></i>
                </div>
              </div>
              <?php endforeach; ?>
            </div>
            <?php endif; ?>
          </section>
        </div>
        <?php endif; ?>
      <?php endif; ?>

    </div><!-- .entry-content -->
  </div>
</article><!-- #post-## -->