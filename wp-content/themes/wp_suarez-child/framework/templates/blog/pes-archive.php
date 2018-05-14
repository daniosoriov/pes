<?php
/**
 * @package pes
 */
$post_id      = get_the_ID();
$post         = get_post( $post_id );
$post_type    = ucwords( $post->post_type );
$excerpt      = '';
$metadata     = array();
$do_shortcode = '';
//echo '<pre>POST: '.print_r($post,1).'</pre>';

switch ( $post->post_type ) {
	case 'attachment':
		$post_type   = 'Photo';
		$image       = wp_get_attachment_image_src( $post_id, 'large' );
		$metadata    = wp_get_attachment_metadata( $post_id );
		$goto_button = 'Go to photo';
		$excerpt     = $post->post_excerpt;
		break;

	case 'gallery':
		$image       = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'large' );
		$goto_button = 'Go to gallery';
		$excerpt     = $post->post_content;
		break;

	case 'video':
		$do_shortcode = 'class="video"';
		$goto_button  = 'Go to video';
		$excerpt      = $post->post_content;
		break;
}
if ( $excerpt ) {
	$excerpt = shorten_string( $excerpt, 30 );
}
?>
<div class="search-wrapper">
    <h4 class="search-title">
        <a href="<?php the_permalink() ?>"
           class="search-title-url <?php echo $post->post_type ?>">
            <span class="search-<?php echo $post->post_type ?>"><?php echo $post_type ?></span> - <?php the_title() ?>
        </a>
    </h4>
    <div class="search-flex">
        <div class="search-capsule-wrapper">
			<?php if ( $post->post_type == 'attachment' || $post->post_type == 'gallery' ): ?>
                <a href="<?php the_permalink() ?>"
                   class="search-link"
                   title="<?php the_title() ?>">
                    <div class="search-capsule"
                         alt="<?php the_excerpt() ?>"
                         style="background-image:url(<?php echo $image[0] ?>)">
                    </div>
                </a>
			<?php elseif ( $post->post_type == 'video' ): ?>
                <div class="embed-container search">
					<?php the_field( 'pes_video_embed' ); ?>
                </div>
			<?php endif; ?>
        </div>
        <div class="search-capsule-wrapper details">
			<?php if ( $excerpt ): ?>
        <p><?php echo $excerpt ?><p>
				<?php endif; ?>
				<?php if ( isset( $metadata['image_meta']['created_timestamp'] ) ): ?>
            <p><?php echo 'Taken on ' . date( 'F d, Y H:i', $metadata['image_meta']['created_timestamp'] ); ?></p>
		<?php endif; ?>
			<?php if ( get_the_date() ): ?>
            <p><?php echo get_the_date(); ?><p>
				<?php endif; ?>
				<?php get_template_part( 'framework/templates/single/pes', 'tag' ); ?>
				<?php echo do_shortcode( "[vc_icon_download {$do_shortcode}]" ) ?>
                <a class="search-button" href="<?php the_permalink() ?>"><?php echo $goto_button ?></a>
        </div>
    </div>
</div>
<hr/>