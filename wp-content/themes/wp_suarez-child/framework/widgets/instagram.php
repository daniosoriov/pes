<?php

class CS_Instagram_Widget extends WP_Widget {

	function CS_Instagram_Widget() {
		parent::__construct( 'cs_instagram_widget', // Base ID
			__( 'CS Instagram', THEMENAME ), // Name
			array( 'description' => __( 'CS Instagram Widget', THEMENAME ), ) // Args
		);
	}

	function widget( $args, $instance ) {
		extract( $args );
		$title       = empty( $instance['title'] ) ? '' : apply_filters( 'widget_title', $instance['title'] );
		$username    = empty( $instance['username'] ) ? '' : $instance['username'];
		$api         = empty( $instance['api'] ) ? '' : $instance['api'];
		$limit       = empty( $instance['number'] ) ? 9 : $instance['number'];
		$columns     = empty( $instance['columns'] ) ? 3 : $instance['columns'];
		$size        = empty( $instance['size'] ) ? 'thumbnail' : $instance['size'];
		$target      = empty( $instance['target'] ) ? '_self' : $instance['target'];
		$link        = empty( $instance['link'] ) ? '' : $instance['link'];
		$extra_class = empty( $instance['extra_class'] ) ? '' : $instance['extra_class'];
		switch ( $columns ) {
			case 1:
				$span = "col-xs-12 col-sm-12 col-md-12 col-lg-12";
				break;
			case 2:
				$span = "col-xs-6 col-sm-6 col-md-6 col-lg-6";
				break;
			case 3:
				$span = "col-xs-4 col-sm-4 col-md-4 col-lg-4";
				break;
			case 4:
				$span = "col-xs-3 col-sm-3 col-md-3 col-lg-3";
				break;
			default:
				$span = "col-xs-4 col-sm-4 col-md-4 col-lg-4";
		}
		echo '' . $before_widget;

		if ( ! empty( $title ) ) {
			echo '' . $before_title . $title . $after_title;
		}
		if ( $username != '' ) {

			$media_array = $this->scrape_instagram( $username, $api, $limit );

			if ( is_wp_error( $media_array ) ) {

				echo '' . $media_array->get_error_message();

			} else {

				// filter for images only?
				if ( $images_only = apply_filters( 'cs_images_only', false ) ) {
					$media_array = array_filter( $media_array, array( $this, 'images_only' ) );
				}

				?>
                <div class="cs-instagram-pics clearfix <?php echo esc_attr( $extra_class ); ?>"><?php
				foreach ( $media_array as $item ) {
					echo '<div class="instagram-item ' . $span . '"><a href="' . esc_url( $item['link'] ) . '" target="' . esc_attr( $target ) . '"><img src="' . esc_url( $item[ $size ]['url'] ) . '"  alt="' . esc_attr( $item['description'] ) . '" title="' . esc_attr( $item['description'] ) . '" style="width:100%; max-width:100%;"/></a></div>';
				}
				?></div><?php
			}
		} else {
			_e( 'You need enter instagram username.', THEMENAME );
		}
		if ( $link != '' ) {
			?><p class="clear"><a href="//instagram.com/<?php echo trim( $username ); ?>" rel="me"
                                  target="<?php echo esc_attr( $target ); ?>"><?php echo esc_attr( $link ); ?></a>
            </p><?php
		}
		echo '' . $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance                = $old_instance;
		$instance['title']       = strip_tags( $new_instance['title'] );
		$instance['username']    = trim( strip_tags( $new_instance['username'] ) );
		$instance['api']         = trim( strip_tags( $new_instance['api'] ) );
		$instance['number']      = ! absint( $new_instance['number'] ) ? 9 : $new_instance['number'];
		$instance['columns']     = ! absint( $new_instance['columns'] ) ? 3 : $new_instance['columns'];
		$instance['size']        = ( ( $new_instance['size'] == 'thumbnail' || $new_instance['size'] == 'large' ) ? $new_instance['size'] : 'thumbnail' );
		$instance['target']      = ( ( $new_instance['target'] == '_self' || $new_instance['target'] == '_blank' ) ? $new_instance['target'] : '_self' );
		$instance['link']        = strip_tags( $new_instance['link'] );
		$instance['extra_class'] = $new_instance['extra_class'];

		return $instance;
	}

	function form( $instance ) {
		$instance    = wp_parse_args( (array) $instance, array(
			'title'    => __( 'Instagram', THEMENAME ),
			'username' => '',
			'api'      => '',
			'link'     => __( 'Follow Us', THEMENAME ),
			'number'   => 9,
			'columns'  => 3,
			'size'     => 'thumbnail',
			'target'   => '_self'
		) );
		$title       = esc_attr( $instance['title'] );
		$username    = esc_attr( $instance['username'] );
		$api         = esc_attr( $instance['api'] );
		$number      = absint( $instance['number'] );
		$columns     = absint( $instance['columns'] );
		$size        = esc_attr( $instance['size'] );
		$target      = esc_attr( $instance['target'] );
		$link        = esc_attr( $instance['link'] );
		$extra_class = isset( $instance['extra_class'] ) ? esc_attr( $instance['extra_class'] ) : '';
		?>
        <p><label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title', THEMENAME ); ?>:
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text"
                       value="<?php echo esc_attr( $title ); ?>"/></label></p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'username' ) ); ?>"><?php _e( 'User ID', THEMENAME ); ?>
                : <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'username' ) ); ?>"
                         name="<?php echo esc_attr( $this->get_field_name( 'username' ) ); ?>" type="text"
                         value="<?php echo esc_attr( $username ); ?>"/></label></p>
        <p><label for="<?php echo esc_attr( $this->get_field_id( 'api' ) ); ?>"><?php _e( 'Client ID', THEMENAME ); ?>:
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'api' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'api' ) ); ?>" type="text"
                       value="<?php echo esc_attr( $api ); ?>"/></label></p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php _e( 'Number of photos', THEMENAME ); ?>
                : <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"
                         name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="text"
                         value="<?php echo esc_attr( $number ); ?>"/></label></p>
        <p><label for="<?php echo esc_attr( $this->get_field_id( 'columns' ) ); ?>"><?php _e( 'Columns', THEMENAME ); ?>
                : <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'columns' ) ); ?>"
                         name="<?php echo esc_attr( $this->get_field_name( 'columns' ) ); ?>" type="text"
                         value="<?php echo esc_attr( $columns ); ?>"/></label></p>
        <p><label for="<?php echo esc_attr( $this->get_field_id( 'size' ) ); ?>"><?php _e( 'Photo size', THEMENAME ); ?>
                :</label>
            <select id="<?php echo esc_attr( $this->get_field_id( 'size' ) ); ?>"
                    name="<?php echo esc_attr( $this->get_field_name( 'size' ) ); ?>" class="widefat">
                <option value="thumbnail" <?php selected( 'thumbnail', $size ) ?>><?php _e( 'Thumbnail', THEMENAME ); ?></option>
                <option value="large" <?php selected( 'large', $size ) ?>><?php _e( 'Large', THEMENAME ); ?></option>
            </select>
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'target' ) ); ?>"><?php _e( 'Open links in', THEMENAME ); ?>
                :</label>
            <select id="<?php echo esc_attr( $this->get_field_id( 'target' ) ); ?>"
                    name="<?php echo esc_attr( $this->get_field_name( 'target' ) ); ?>" class="widefat">
                <option value="_self" <?php selected( '_self', $target ) ?>><?php _e( 'Current window (_self)', THEMENAME ); ?></option>
                <option value="_blank" <?php selected( '_blank', $target ) ?>><?php _e( 'New window (_blank)', THEMENAME ); ?></option>
            </select>
        </p>
        <p><label for="<?php echo esc_attr( $this->get_field_id( 'link' ) ); ?>"><?php _e( 'Link text', THEMENAME ); ?>:
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'link' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'link' ) ); ?>" type="text"
                       value="<?php echo esc_attr( $link ); ?>"/></label></p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'extra_class' ) ); ?>">Extra Class:</label>
            <input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id( 'extra_class' ) ); ?>"
                   name="<?php echo esc_attr( $this->get_field_name( 'extra_class' ) ); ?>"
                   value="<?php echo esc_attr( $extra_class ); ?>"/>
        </p>
		<?php

	}

	function scrape_instagram( $username, $api, $slice = 9 ) {
		if ( false === ( $instagram = get_transient( 'instagram-media-' . sanitize_title_with_dashes( $username ) ) ) ) {
			if ( empty( $api ) ) {
				return new WP_Error( 'site_down', __( 'You need enter client api.', THEMENAME ) );
			}
			$id     = $this->getInstaID( $username, $api );
			$remote = wp_remote_get( "https://api.instagram.com/v1/users/" . trim( $id ) . "/media/recent/?client_id=" . $api . "&count=" . $slice, true );
			if ( is_wp_error( $remote ) ) {
				return new WP_Error( 'site_down', __( 'Unable to communicate with Instagram.', THEMENAME ) );
			}

			if ( 200 != wp_remote_retrieve_response_code( $remote ) ) {
				return new WP_Error( 'invalid_response', __( 'Instagram did not return a 200.', THEMENAME ) );
			}
			$insta_array = json_decode( $remote['body'], true );

			if ( ! $insta_array ) {
				return new WP_Error( 'bad_json', __( 'Instagram has returned invalid data.', THEMENAME ) );
			}

			$images = $insta_array['data'];

			$instagram = array();
			foreach ( $images as $image ) {

				if ( $image['user']['username'] == $username ) {

					$image['link']                          = preg_replace( "/^http:/i", "", $image['link'] );
					$image['images']['thumbnail']           = preg_replace( "/^http:/i", "", $image['images']['thumbnail'] );
					$image['images']['standard_resolution'] = preg_replace( "/^http:/i", "", $image['images']['standard_resolution'] );

					$instagram[] = array(
						'description' => $image['caption']['text'],
						'link'        => $image['link'],
						'time'        => $image['created_time'],
						'comments'    => $image['comments']['count'],
						'likes'       => $image['likes']['count'],
						'thumbnail'   => $image['images']['thumbnail'],
						'large'       => $image['images']['standard_resolution'],
						'type'        => $image['type']
					);
				}
			}
			$instagram = base64_encode( serialize( $instagram ) );
			set_transient( 'instagram-media-' . sanitize_title_with_dashes( $username ), $instagram, apply_filters( 'cs_instagram_cache_time', 300 ) );
		}

		$instagram = unserialize( base64_decode( $instagram ) );

		return array_slice( $instagram, 0, $slice );
	}

	function images_only( $media_item ) {

		if ( $media_item['type'] == 'image' ) {
			return true;
		}

		return false;
	}

	function getInstaID( $username, $client_id ) {

		$username = strtolower( $username ); // sanitization
		$url      = "https://api.instagram.com/v1/users/search?q=" . $username . "&client_id=" . $client_id;
		$remote   = wp_remote_get( $url, true );
		if ( is_wp_error( $remote ) ) {
			return new WP_Error( 'site_down', __( 'Unable to communicate with Instagram.', THEMENAME ) );
		}

		if ( 200 != wp_remote_retrieve_response_code( $remote ) ) {
			return new WP_Error( 'invalid_response', __( 'Instagram did not return a 200.', THEMENAME ) );
		}
		$json = json_decode( $remote['body'] );

		foreach ( $json->data as $user ) {
			if ( $user->username == $username ) {
				return $user->id;
			}
		}

		return '00000000'; // return this if nothing is found
	}

}

/**
 * Class CS_Social_Widget
 */

function register_instagram_widget() {
	register_widget( 'CS_instagram_Widget' );
}

add_action( 'widgets_init', 'register_instagram_widget' );
?>