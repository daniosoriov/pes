<?php
/**
 * @package pes
 */
?>
<?php
$post_id = get_the_ID();
$i       = 0;
?>
<!-- Header tag fields for PES -->
<section class="pes-section">
    <div class="pes-tags">
		<?php $terms = get_the_terms( $post_id, 'leader' ); ?>
		<?php if ( $terms && ! is_wp_error( $terms ) ): ?>
            <p class="pes-tags-section">
				<?php the_terms( $post_id, 'leader', 'Leaders: ', ', ' );
				$i ++; ?>
            </p>
		<?php endif; ?>
		<?php $terms = get_the_terms( $post_id, 'topic' ); ?>
		<?php if ( $terms && ! is_wp_error( $terms ) ): ?>
            <p class="pes-tags-section">
				<?php the_terms( $post_id, 'topic', 'Topics: ', ', ' );
				$i ++; ?>
            </p>
		<?php endif; ?>
		<?php $terms = get_the_terms( $post_id, 'country' ); ?>
		<?php if ( $terms && ! is_wp_error( $terms ) ): ?>
            <p class="pes-tags-section">
				<?php the_terms( $post_id, 'country', 'Countries: ', ', ' );
				$i ++; ?>
            </p>
		<?php endif; ?>
		<?php if ( $i == 0 ): ?>
            <p>No tags yet</p>
		<?php endif; ?>
    </div>
</section>
<!-- End of header tag fields for PES -->