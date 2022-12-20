<?php
/**
 * Will be included in the shariff.php.
 *
 * @package Shariff Wrapper
 */

/**
 * Class ShariffWidget
 */
class Shariff_Widget extends WP_Widget {
	/**
	 * Registers the widget with the WordPress Widget API.
	 */
	public static function register() {
		register_widget( __CLASS__ );
	}

	/**
	 * Shariff_Widget constructor.
	 */
	public function __construct() {
		// Add translations.
		if ( function_exists( 'load_plugin_textdomain' ) ) {
			load_plugin_textdomain( 'shariff' );
		}

		$widget_options = array(
			'classname'                   => 'Shariff',
			'description'                 => __( 'Add Shariff as configured on the plugin options page.', 'shariff' ),
			'customize_selective_refresh' => true,
		);

		$control_options = array();
		parent::__construct( 'Shariff', 'Shariff', $widget_options, $control_options );
	}

	/**
	 * Create the actual form.
	 *
	 * @param array $instance Current instance.
	 *
	 * @return void
	 */
	public function form( $instance ) {
		// Set widgets defaults.
		$instance = wp_parse_args(
			(array) $instance,
			array(
				'shariff-title' => '',
				'shariff-tag'   => '[shariff]',
			)
		);
		// Sets the title.
		echo '<p style="border-bottom: 1px solid #DFDFDF;"><strong>' . esc_html__( 'Title', 'shariff' ) . '</strong></p>';
		// Set the title.
		echo '<p><input id="' . esc_html( $this->get_field_id( 'shariff-title' ) ) . '" name="' . esc_html( $this->get_field_name( 'shariff-title' ) ) . '" type="text" size="45" value="' . esc_html( $instance['shariff-title'] ) . '" /> ' . esc_html__( '(optional)', 'shariff' ) . '</p>';
		// Sets the shorttag.
		echo '<p style="border-bottom: 1px solid #DFDFDF;"><strong>Shorttag</strong></p>';
		// Sets the shorttag.
		echo '<p><input id="' . esc_html( $this->get_field_id( 'shariff-tag' ) ) . '" name="' . esc_html( $this->get_field_name( 'shariff-tag' ) ) . '" type="text" value=\'' . esc_html( str_replace( '\'', '"', $instance['shariff-tag'] ) ) . '\' size="45" /> ' . esc_html__( '(optional)', 'shariff' ) . '</p>';
		echo '<p style="clear:both;"></p>';
	}

	/**
	 * Saves the widget configuration.
	 *
	 * @param array $new_instance The new instance.
	 * @param array $old_instance The old instance.
	 *
	 * @return array The updated instance.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		// Sets the widget conf defaults.
		$new_instance = wp_parse_args(
			(array) $new_instance,
			array(
				'shariff-title' => '',
				'shariff-tag'   => '[shariff]',
			)
		);

		// Checks the input values.
		$instance['shariff-title'] = (string) wp_strip_all_tags( $new_instance['shariff-title'] );
		$instance['shariff-tag']   = (string) wp_kses( $new_instance['shariff-tag'], $GLOBALS['allowed_tags'] );

		// Saves the config.
		return $instance;
	}

	/**
	 * Draws the widget.
	 *
	 * @param array $args Provided arguments.
	 * @param array $instance The current instance.
	 */
	public function widget( $args, $instance ) {
		// Get options.
		$shariff3uu = $GLOBALS['shariff3uu'];

		// Creates the container.
		$allowed_tags = wp_kses_allowed_html( 'post' );
		echo wp_kses( $args['before_widget'], $allowed_tags );

		// Prints the title of the widget, if provided.
		if ( empty( $instance['shariff-title'] ) ) {
			$title = '';
		} else {
			apply_filters( 'shariff_title', $instance['shariff-title'] );
			$title = $instance['shariff-title'];
		}
		if ( ! empty( $title ) ) {
			echo wp_kses( $args['before_title'] . $title . $args['after_title'], $GLOBALS['allowed_tags'] );
		}

		// Print the shorttag, but keep the original shorttag for further reference.
		$original_shorttag = $instance['shariff-tag'];

		// If nothing is configured, uses the global options from admin menu.
		if ( '[shariff]' === $instance['shariff-tag'] ) {
			$shorttag = '[shariff]';
		} else {
			$shorttag = $instance['shariff-tag'];
		}

		// Sets the url to the current page to prevent sharing the first or last post on pages with multiple posts.
		// For example the blog page. Of course only if no manual url is provided in the shorttag.
		$page_url = '';
		if ( strpos( $original_shorttag, ' url=' ) === false ) {
			$wpurl = get_bloginfo( 'wpurl' );
			$wpurl = str_replace( wp_make_link_relative( $wpurl ), '', $wpurl );
			if ( isset( $_SERVER['REQUEST_URI'] ) ) {
				$page_url = ' url="' . $wpurl . esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) . '"';
			} else {
				global $wp;
				$page_url = ' url="' . home_url( add_query_arg( array(), $wp->request ) ) . '"';
			}
		}

		// Same for title.
		$page_title = '';
		$wp_title   = '';
		if ( strpos( $original_shorttag, 'title=' ) === false ) {
			$wp_title = wp_get_document_title();
			// wp_title for all pages that have it.
			if ( ! empty( $wp_title ) ) {
				$page_title = $wp_title;
			} else {
				$page_title = get_bloginfo( 'name' );
			}
			// Replace brackets [ and ] with ( and ).
			$page_title = str_replace( '[', '(', $page_title );
			$page_title = str_replace( ']', ')', $page_title );
			$page_title = ' title="' . wp_strip_all_tags( html_entity_decode( $page_title, ENT_COMPAT, 'UTF-8' ) ) . '"';
		}

		// Same for media.
		$media = '';
		if ( array_key_exists( 'services', $shariff3uu ) && strstr( $shariff3uu['services'], 'pinterest' ) && ( strpos( $original_shorttag, 'media=' ) === false ) ) {
			if ( isset( $shariff3uu['default_pinterest'] ) ) {
				$media = ' media="' . $shariff3uu['default_pinterest'] . '"';
			}
		}

		// Builds the shorttag and adds the url, title and media if necessary as well as the widget attribute.
		$shorttag = substr( $shorttag, 0, -1 ) . $page_title . $page_url . $media . ' widget="1"]';

		// Processes the shortcode if it is not password protected or "disable on password protected posts" is not set.
		if ( 1 !== post_password_required( get_the_ID() ) || ( isset( $shariff3uu['disable_on_protected'] ) && 1 !== $shariff3uu['disable_on_protected'] ) ) {
			echo do_shortcode( $shorttag );
		}

		// Closes the Container.
		echo wp_kses( $args['after_widget'], $allowed_tags );
	} // End of widget.
} // End of class ShariffWidget.
add_action( 'widgets_init', array( 'Shariff_Widget', 'register' ) );
