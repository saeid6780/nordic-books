<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://linkedin.com/in/saeid-sadigh-zadeh-8861688a
 * @since      1.0.0
 *
 * @package    Nordic_Books
 * @subpackage Nordic_Books/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Nordic_Books
 * @subpackage Nordic_Books/public
 * @author     saeid6780 <saeid6780sz@gmail.com>
 */
class Nordic_Books_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Nordic_Books_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Nordic_Books_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name . '-style', plugin_dir_url( __FILE__ ) . 'css/nordic-books-public.css', array(), $this->version, 'all' );

    }

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts( $atts = [] ) {
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Nordic_Books_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Nordic_Books_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_register_script( $this->plugin_name . '-script', plugin_dir_url( __FILE__ ) . 'js/nordic-books-public.js', array( 'jquery' ), $this->version, true );

        $localize = array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce'    => wp_create_nonce( 'nordic_add_book_action' ),
            'strings'  => array(
                'saving'        => __( 'Saving...', 'nordic-books' ),
                'saved'         => __( 'Book added successfully.', 'nordic-books' ),
                'error'         => __( 'An error occurred. Please try again.', 'nordic-books' ),
                'validation'    => __( 'Please fill all required fields correctly.', 'nordic-books' ),
            ),
            'primary_color'   => $atts['primary_color'],
            'secondary_color' => $atts['secondary_color'],
        );

        wp_localize_script( 'nordic-books-script', 'NordicBooks', $localize );
        wp_enqueue_script( $this->plugin_name . '-script' );

	}

}
