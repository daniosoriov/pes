<?php
/**
 * Search & Filter Pro
 *
 * Sample Results Template
 *
 * @package   Search_Filter
 * @author    Ross Morsali
 * @link      http://www.designsandcode.com/
 * @copyright 2015 Designs & Code
 *
 * Note: these templates are not full page templates, rather
 * just an encaspulation of the your results loop which should
 * be inserted in to other pages by using a shortcode - think
 * of it as a template part
 *
 * This template is an absolute base example showing you what
 * you can do, for more customisation see the WordPress docs
 * and using template tags -
 *
 * http://codex.wordpress.org/Template_Tags
 *
 */

if ( $query->have_posts() ) { ?>
    <p>Found <?php echo $query->found_posts; ?> results</p>
    <div class="search-pagination top">
		<?php
		if ( function_exists( 'wp_pagenavi' ) ) {
			wp_pagenavi( array( 'query' => $query ) );
		}
		?>
    </div>

	<?php
	while ( $query->have_posts() ) {
		$query->the_post();
		get_template_part( 'framework/templates/blog/pes', 'archive' );
	}
	?>

    <div class="search-pagination bottom">
		<?php
		if ( function_exists( 'wp_pagenavi' ) ) {
			wp_pagenavi( array( 'query' => $query ) );
		}
		?>
    </div>
	<?php
} else {
	echo "No Results Found";
}

//echo '<pre>QUERY: '.print_r($query,1).'</pre>';

?>