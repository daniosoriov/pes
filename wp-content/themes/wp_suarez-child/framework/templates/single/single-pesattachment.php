<?php
/**
 * @package pes
 */
?>
<?php global $smof_data; ?>
<?php
$attachment_id = get_the_ID();
$attachment    = get_post( $attachment_id );
$metadata      = wp_get_attachment_metadata( $attachment_id );
$large_image   = wp_get_attachment_image_src( $attachment_id, 'large' );
$uploads       = wp_upload_dir();

$caption = get_the_excerpt();
if ( ! $caption ) {
	$caption = ( $metadata['image_meta']['caption'] ) ? $metadata['image_meta']['caption'] : '<em>No caption</em>';
}

// Calculate dimension, size and download URL.
$filepath = $uploads['basedir'] . '/' . $metadata['file'];
$filebase = dirname( $filepath );
$web_size = pes_formatBytes( filesize( $filebase . '/' . $metadata['sizes']['large']['file'] ), 0 );
$hr_size  = pes_formatBytes( filesize( $filepath ), 0 );

$web_dimension = $metadata['sizes']['large']['width'] . ' x ' . $metadata['sizes']['large']['height'];
$hr_dimension  = $metadata['width'] . ' x ' . $metadata['height'];

$web_url = pes_download_attachment_url( $attachment_id, 'web' );
$hr_url  = pes_download_attachment_url( $attachment_id );

$parent         = get_post( $attachment->post_parent );
$parent_gallery = array();
$parent_gallery = get_field( 'pes_gallery', $attachment->post_parent );
$total_images   = count( $parent_gallery );
$i              = 0;
$prev_img       = $next_img = 0;
$prev_img_url   = $next_img_url = '';
$image_number   = 1;
foreach ( $parent_gallery as $image ) {
	if ( $image['id'] == $attachment_id ) {
		$image_number = $i + 1;
		$prev_img     = $i - 1;
		$next_img     = $i + 1;
	}
	$i ++;
}

if ( is_array( $parent_gallery ) && array_key_exists( $prev_img, $parent_gallery ) ) {
	$prev_img_url = '<a id="previous-url" href="' . get_attachment_link( $parent_gallery[ $prev_img ]['id'] ) . '">&laquo; Previous</a>';
}
if ( is_array( $parent_gallery ) && array_key_exists( $next_img, $parent_gallery ) ) {
	$next_img_url = '<a id="next-url" href="' . get_attachment_link( $parent_gallery[ $next_img ]['id'] ) . '">Next &raquo;</a>';
}
$both_images = $prev_img_url && $next_img_url;
$prev_next   = $prev_img_url . ( ( $both_images ) ? ' | ' : ' ' ) . $next_img_url;

// For logged in users.
$user_ID          = get_current_user_id();
$login_attributes = array(
	'profile_link' => false,
	'template'     => 'default',
	'registration' => true,
	'redirect'     => false,
	'remember'     => true
);

?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'single-post-inner' ); ?>>
    <div class="cs-blog cs-blog-item">
        <div class="cs-blog-content">
            <div class="pes-image-header">
				<?php if ( $both_images ) ?>
                    <span id="allow-arrows"></span>
				<?php ?>
                <div class="pes-image-header-container"><a
                            href="<?php echo get_post_permalink( $attachment->post_parent ); ?>"><?php echo '&laquo; Back to ' . get_the_title( $attachment->post_parent ) . ' gallery' ?></a>
                </div>
                <div class="pes-image-header-container">
                    <span><?php echo $prev_next; ?><a id="hideShowDetails"
                                                      href="javascript:void(0)">Less info</a></span><br/>
                    <span class="smaller">Use arrows ( &larr; or &rarr; ) on your keyboard to navigate the gallery</span>
                </div>
            </div>
            <div class="pes-image-content">
                <div class="pes-image col-xs-12 col-sm-12 col-md-9">
                    <img class="large-attachment" src="<?php echo $large_image[0] ?>"/>
                </div>
                <div class="pes-image-data col-xs-12 col-sm-12 col-md-3">
                    <div class="img-det">
                        <p><?php echo "Image {$image_number} of {$total_images}"; ?></p>
                        <p><span class="photo-title"><?php echo the_title(); ?></span></p>
						<?php if ( $caption ): ?>
                            <p><?php echo $caption; ?></p>
						<?php endif; ?>
						<?php if ( $metadata['image_meta']['created_timestamp'] ): ?>
                            <p><?php echo date( 'F d, Y H:i', $metadata['image_meta']['created_timestamp'] ); ?></p>
						<?php endif; ?>

                    </div>

                    <div class="img-download">
						<?php if ( ! $user_ID ): ?>
                            <div class="tdyml">
                                <p><strong>To download this photo you must be logged in.</strong></p>
                            </div>
							<?php if ( function_exists( 'login_with_ajax' ) ) {
								login_with_ajax( $login_attributes );
							} ?>
						<?php else: ?>
                            <div class="size-btn">
                                <div id="img-box" class="btn-container" href="javascript:void(0)"
                                     data-size="<?php echo $web_size ?>"
                                     data-dimension="<?php echo $web_dimension ?>"
                                     data-id="<?php echo $web_url ?>">
                                    WEB
                                </div>
                                <div id="img-box" class="btn-container pressed" href="javascript:void(0)"
                                     data-size="<?php echo $hr_size ?>"
                                     data-dimension="<?php echo $hr_dimension ?>"
                                     data-id="<?php echo $hr_url ?>">
                                    Hi-res
                                </div>
                            </div>
                            <div id="img-attr" class="img-attr"><?php echo $hr_dimension . ' / ' . $hr_size ?></div>
                            <button type="button" id="img-download-btn" class="pes-btn"
                                    onClick="window.location.href='<?= $hr_url ?>'"><i class="fa fa-download"></i>Download
                                this photo
                            </button>
						<?php endif; ?>
                    </div>
                </div>
                <div class="clearfix visible-md visible-lg"></div>
            </div>

			<?php get_template_part( 'framework/templates/single/pes', 'tag' ); ?>

        </div><!-- .entry-content -->
    </div>
</article><!-- #post-## -->