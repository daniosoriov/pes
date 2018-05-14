<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// BEGIN ENQUEUE PARENT ACTION
// AUTO GENERATED - Do not modify or remove comment markers above or below:


if ( ! function_exists( 'chld_thm_cfg_parent_css' ) ):
	function chld_thm_cfg_parent_css() {
		wp_enqueue_style( 'chld_thm_cfg_parent', trailingslashit( get_template_directory_uri() ) . 'style.css' );
	}
endif;
add_action( 'wp_enqueue_scripts', 'chld_thm_cfg_parent_css' );

// END ENQUEUE PARENT ACTION


// Add extra files.
add_action( 'wp_enqueue_scripts', 'wp_suarez_child_add_stylesheet' );
function wp_suarez_child_add_stylesheet() {
	wp_enqueue_style( 'pes-style', get_stylesheet_directory_uri() . '/style.css', false, '1.0', 'all' );
}

add_action( 'wp_enqueue_scripts', 'wp_suarez_child_add_script' );
function wp_suarez_child_add_script() {
	wp_enqueue_script( "jquery-effects-core" );

	//  wp_register_script('pr_cycle_all',get_stylesheet_directory_uri().'/js/pr-slider.js');
	//  wp_register_script('pr_slider',get_stylesheet_directory_uri().'/js/jquery.cycle.all.min.js');

	//  wp_enqueue_script( 'pes-functions', get_stylesheet_directory_uri() . '/js/pes-functions.js', array('jquery') );
	wp_enqueue_script( 'pes-script', get_stylesheet_directory_uri() . '/js/pes-script.js', array( 'jquery' ) );
}


add_action( 'pre_get_posts', 'pes_alter_query' );
function pes_alter_query( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	//gets the global query var object
	global $wp_query;

	$page     = get_query_var( 'post_type', '' );
	$not_here = array( 'video', 'gallery', 'any' );

	// If we are checking an archive page for a taxonomy term then put all posts.
	if ( $wp_query->is_archive && ! in_array( $page, $not_here ) && ! isset( $wp_query->queried_object->ID ) ) {
		$query->set( 'post_type', 'any' );
	}
	//echo '<pre>QUERY '.print_r($wp_query,1).'</pre>';

	//we remove the actions hooked on the '__after_loop' (post navigation)
	remove_all_actions( '__after_loop' );
}

add_action( 'parse_query', 'pes_hijack_query' );
function pes_hijack_query() {
	global $wp_query;

	// When inside a custom taxonomy archive include attachments
	if ( is_tax( 'meeting' ) || is_tax( 'leader' ) || is_tax( 'topic' ) || is_tax( 'country' ) ) {
		//echo '<pre>QUERY '.print_r($wp_query,1).'</pre>';
		$wp_query->query_vars['post_type']   = array( 'attachment' );
		$wp_query->query_vars['post_status'] = array( null );

		return $wp_query;
	}
}


add_action( 'template_redirect', 'test_redirect' );
function test_redirect() {
	if ( $_SERVER['REQUEST_URI'] == '/downloads/hola.jpg' ) {
		//    header("Content-type: application/x-msdownload",true,200);
		//    header("Content-Disposition: attachment; filename=data.csv");
		//    header("Pragma: no-cache");
		//    header("Expires: 0");
		//    echo 'data';
		//    exit();


		$filename = 'Event-Photography-Brussels-Belgium-151111-6.jpg';
		$filepath = '/home/mediapesqe/www/pes/wp-content/uploads/2016/02/Event-Photography-Brussels-Belgium-151111-6.jpg';


		$filepath1 = '/home/mediapesqe/www/pes/wp-content/uploads/2016/02/Event-Photography-Brussels-Belgium-151111-6.jpg';
		$filepath2 = '/home/mediapesqe/www/pes/wp-content/uploads/2016/02/Event-Photography-Brussels-Belgium-151111-5.jpg';


		$files   = array( $filepath1, $filepath2 );
		$zipname = 'file.zip';
		$zip     = new ZipArchive;
		$zip->open( $zipname, ZipArchive::CREATE );
		$i = 1;
		foreach ( $files as $file ) {
			$zip->addFile( $file, 'file' . $i ++ );
		}
		$zip->close();

		header( 'Content-Type: application/zip', true, 200 );
		header( 'Content-disposition: attachment; filename=' . $zipname );
		header( 'Content-Length: ' . filesize( $zipname ) );
		readfile( $zipname );

		exit();


		//            if ( ini_get( 'zlib.output_compression' ) )
		//				ini_set( 'zlib.output_compression', 0 );
		//
		//		    // set needed headers
		//			header( 'Content-Type: application/download',true,200 );
		//			header( 'Content-Disposition: attachment; filename=' . rawurldecode( $filename ) );
		//			header( 'Content-Transfer-Encoding: binary' );
		//			header( 'Accept-Ranges: bytes' );
		//			header( 'Cache-control: private' );
		//			header( 'Pragma: private' );
		//			header( 'Expires: Mon, 26 Jul 1997 05:00:00 GMT' );
		//			header( 'Content-Length: ' . filesize( $filepath ) );
		//
		//    error_log('The filepath:'. $filepath);
		//    error_log('The filename:'. $filename);
		//
		//			// increase counter of downloads
		//			//update_post_meta( $attachment_id, '_da_downloads', (int) get_post_meta( $attachment_id, '_da_downloads', true ) + 1 );
		//
		//			// start printing file
		//			if ( $filepath = fopen( $filepath, 'rb' ) ) {
		//				while ( ! feof( $filepath ) && ( ! connection_aborted()) ) {
		//					echo fread( $filepath, 1048576 );
		//					flush();
		//				}
		//
		//				fclose( $filepath );
		//			} else
		//				return false;
		//
		//			exit;

	}
}



// http://wordpress.stackexchange.com/questions/3480/how-can-i-force-a-file-download-in-the-wordpress-backend
// Also use WP DEBUG... this can help.

// When the user is not logged in, we can use this http://stackoverflow.com/questions/18977439/wordpress-default-login-popup

