<?php 
$post_id = get_the_ID();
$post = get_post($post_id);
$user_ID = get_current_user_id();

$back = $number_images = '';
$download = FALSE;
$videos = array();

switch ($post->post_type) {
  case 'gallery':
    $back = '<a href="/photos">&laquo; Back to all photo galleries</a>';
    
    $previous = get_previous_post_link('%link', '&laquo; Previous photo gallery');
    $next = get_next_post_link('%link', 'Next photo gallery &raquo;');
    $previous_next = $previous .(($previous && $next) ? ' | ' : ''). $next;
    
    $images = get_field('pes_gallery');
    $number_images = ' {'. count($images) . ' Photos}';
    $download = (get_query_var('gallery_action', '') == 'download') ? TRUE : FALSE;
    $post_download_url = pes_download_gallery_url($post_id);
    break;
    
  case 'attachment':
    break;
    
  case 'video':
    $back = '<a href="/video">&laquo; Back to all videos</a>';
    
    $previous = get_previous_post_link('%link', '&laquo; Previous video');
    $next = get_next_post_link('%link', 'Next video &raquo;');
    $previous_next = $previous .(($previous && $next) ? ' | ' : ''). $next;
    
    // get iframe HTML
    $iframe = get_field('pes_video_embed');
    // use preg_match to find iframe src
    preg_match('/src="(.+?)"/', $iframe, $matches);
    $vimeo_id = basename($matches[1]);

    $videos = pes_vimeo_get_download_videos($vimeo_id);
    $videos = pes_vimeo_reorder_download_videos($videos);
    //echo '<pre>VIDEOS TO DOWNLOAD '.print_r($videos,1).'</pre>';
    break;
}

$login_attributes = array(
  'profile_link' => false,
  'template' => 'default',
  'registration' => true,
  'redirect' => false,
  'remember' => true
);

?>

<!-- Header fields for PES -->
<section class="pes-section">
  <div class="pes-header">
    <?php if (!$download): ?>
    <div class="pes-header-left">
      <!-- Back url -->
      <?php if ($back): ?>
      <p><?php echo $back ?></p>
      <?php endif; ?>

      <!-- Name of the meeting -->
      <?php if (get_field('pes_name_of_meeting')): ?>
      <p class="pes-header-p pes-name-of-meeting"><?php the_field('pes_name_of_meeting'); echo $number_images; ?></p>
      <?php endif; ?>

      <!-- Place/Location -->
      <?php if (get_field('pes_location')): ?>
      <p class="pes-header-p pes-location"><?php the_field('pes_location'); ?></p>
      <?php endif; ?>

      <!-- Date -->
      <?php if (get_field('pes_date')): ?>
      <p class="pes-header-p pes-date">
        <?php echo get_the_date(); ?>
      </p>
      <?php endif; ?>
      <div class="pes-description">
      <?php the_content(); ?>
      </div>
      
      <!-- Previous & next gallery -->
      <p><?php echo $previous_next ?></p>
    </div>

    <div class="pes-header-right">
      <?php if ($post->post_type == 'gallery'): ?>
      <form action="<?php echo get_permalink($post_id); ?>" method="get">
        <input type="hidden" name="gallery_action" value="download" />
        <div class="pes-download"><button type="submit" id="pes-download" class="pes-btn"><i class="fa fa-download"></i>Select &amp; download photos</button></div>
      </form>
      <?php endif; ?>

      <?php if ($post->post_type == 'video'): ?>
      <div class="video-download">
        <?php if (!$user_ID): ?>
        <div class="tdyml">
          <p><strong>To download this video you must be logged in.</strong></p>
        </div>
        <?php if (function_exists('login_with_ajax')) login_with_ajax($login_attributes); ?>
        <?php else: ?>
        <div class="size-btn">
          <?php foreach ($videos as $k => $download): ?>
          <div id="video-box" class="btn-container<?php if ($k == 0) echo ' pressed-video'; ?>" href="javascript:void(0)" 
               data-size="<?php echo pes_formatBytes($download['size']) ?>"
               data-dimension="<?php echo $download['width'] .' x '. $download['height'] ?>" 
               data-id="<?php echo $download['link'] ?>">
            <?php echo pes_vimeo_format_display($download); ?>
          </div>
          <?php endforeach; ?>
        </div>
        <div id="video-attr" class="video-attr"><?php echo $videos[0]['width'] .' x '. $videos[0]['height'] .' / '. pes_formatBytes($videos[0]['size']) ?></div>
        <button type="button" id="video-download-btn" class="pes-btn" onClick="window.location.href='<?php echo $videos[0]['link'] ?>'"><i class="fa fa-download"></i>Download this video</button>
        <?php endif; ?>
      </div>
      <?php endif; ?>
    </div>
    <?php else: ?>
    <?php if ($user_ID): ?>
    <div class="pes-header-left">
      <p><a href="<?php echo get_permalink($post_id); ?>">&laquo; Back to <?php the_title(); ?> gallery</a></p>
      <p>
        <a id="selectAllPhotos" href="javascript:void(0)"><?php echo 'Select all '. count($images) . ' photos'; ?></a> &#124;
        <a id="clearAllPhotos" href="javascript:void(0)">Clear selection</a>
      </p>
      <p><i class="fa fa-question-circle info-gallery"></i>Need help?</p>
      <div class="info-gallery-help help-hide">
        <div class="item-title">Steps:</div>
        <div><i class="fa fa-times-circle info-gallery-close"></i></div>
        <ul>
          <li>&#8226; Click on the photos you would like to download.</li>
          <li>&#8226; Select the format you would like to download: WEB or Hi-res.</li>
          <li>&#8226; Hit the download button on the right to start downloading.</li>
          <li>&#8226; Download will begin automatically.</li>
        </ul>
        <div class="item-title">Hints:</div>
        <ul>
          <li>&#8226; To select all pictures click: Select all photos.</li>
          <li>&#8226; To clear your selection click: Clear selection.</li>
        </ul>
      </div>
    </div>
    <div class="pes-header-right">
      <div class="pes-download">
        <form id="gallery-download" action="<?= $post_download_url ?>" method="post">
          <input type="hidden" name="download_url" value="<?= $post_download_url ?>" />
          <input type="hidden" name="photos" value="" />
          <div class="size-btn">
            <div id="img-box" class="btn-container" href="javascript:void(0)" data-size="web">
              WEB
            </div>
            <div id="img-box" class="btn-container pressed" href="javascript:void(0)" data-size="hr">
              Hi-res
            </div>
          </div>
          <button title="Select photos to download first" 
                  type="submit" 
                  id="pes-download" 
                  
                  class="pes-btn btn-disabled" 
                  disabled>
            <i class="fa fa-download"></i>Download selected photos
          </button>
        </form>
      </div>
    </div>
    <?php endif; ?>
    <?php endif; ?>
  </div>
</section>
<!-- End of header fields for PES -->