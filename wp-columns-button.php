<?php
/**
 * Plugin Name: WP Columns Button
 * Plugin URI: http://horttcore.de/
 * Text Domain: wp-columns-button
 * Domain Path: /languages
 * Description: Adding a button in WordPress TinyMCE to split the content into columns
 * Author:      Ralf Hortt
 * Author URI:  http://horttcore.de/
 * Version:     0.1
 * License:     GPLv3
 */



/**
 * Security, checks if WordPress is running
 **/
if ( !function_exists( 'add_action' ) ) :
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
endif;



/**
* Plugin Class
*/
class WP_Columns_Button
{



	/**
	 * Constructor
	 *
	 * @author Ralf Hortt
	 **/
	public function __construct()
	{
		add_action( 'init', array( $this, 'add_editor_button' ) );
		add_filter( 'tiny_mce_before_init', array( $this, 'add_editor_style' ) );
		add_filter( 'the_content', array( $this, 'the_content' ) );

	}



	/**
	 *
	 * Add button
	 *
	 */
	public function add_editor_button() {
		// Don't bother doing this stuff if the current user lacks permissions
		if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) )
			return;

		// Add only in Rich Editor mode
		if ( get_user_option( 'rich_editing' ) == 'true' ) :
			add_filter( 'mce_external_plugins', array( $this, 'add_editor_plugin' ) );
			add_filter( 'mce_buttons', array( $this, 'register_button' ) );
		endif;
	}



	/**
	 *
	 * Load the TinyMCE plugin
	 *
	 * @access public
	 * @param array $plugins TinyMCE plugins
	 * @return array TinyMCE plugins
	 * @author Ralf Hortt
	 **/
	public function add_editor_plugin( $plugins ) {
	   $plugins['tinymce_column_button'] = plugins_url( 'javascript/editor-plugin.js', __FILE__ );
	   return $plugins;
	}



	/**
	 *
	 * Include the css file to style the graphic that replaces the shortcode
	 *
	 * @access public
	 * @param array $styles Styles to load
	 * @return array Styles to load
	 * @author Ralf Hortt
	 **/
	public function add_editor_style( $styles )
	{
		$styles['content_css'] .= "," . plugins_url( 'css/editor-style.css', __FILE__ );
		return $styles;
	}



	/**
	 *
	 * Register editor button
	 *
	 * @access public
	 * @param array $buttons Buttons
	 * @return array $buttons Buttons
	 * @author Ralf Hortt
	 **/
	public function register_button( $buttons ) {
		array_push($buttons, "|", "tinymce_column_button");
		return $buttons;
	}



	/**
	 * Split the sections
	 *
	 * @access public
	 * @param str $content Content
	 * @return str Content
	 * @author Ralf Hortt
	 **/
	public function the_content( $content )
	{
		global $post;

		if ( FALSE === strpos( $content, '[COLUMN]' ) ) :
			return $content;
		else :
			$content = explode( '[COLUMN]', $content );
			$columns = count( $content );
			$i = 1;

			$output = '<div class="columns-' . $columns . '">';
			$output .= '<div class="column column-' . $i .'">';

			foreach ( $content as $c ) :

				$output .= $c;
				$output .= '</div><!-- .column-' . $i . ' -->';
				$i++;

				if ( $i <= $columns)
					$output .= '<div class="column column-' . $i .'">';

			endforeach;

			$output .= '</div><!-- .columns-' . $columns . ' -->';

			$output = str_replace( '<p></p>', '', $output);
			return $output;
		endif;
	}



}
new WP_Columns_Button;