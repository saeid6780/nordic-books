<?php

/**
 * Register all plugin shortcodes
 *
 * @link       https://linkedin.com/in/saeid-sadigh-zadeh-8861688a
 * @since      1.0.0
 *
 * @package    Nordic_Books
 * @subpackage Nordic_Books/includes
 */

/**
 * Register all plugin shortcodes
 *
 * @package    Nordic_Books
 * @subpackage Nordic_Books/includes
 * @author     saeid6780 <saeid6780sz@gmail.com>
 */
class Nordic_Books_Shortcodes {

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

    protected $wpdb;

    private $book_table;

    /**
     * Initialize the collections used to maintain the actions and filters.
     *
     * @since    1.0.0
     */

    public function __construct( $plugin_name, $version ) {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->book_table = $wpdb->prefix . 'books';
        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }

    /**
     * Render shortcode [book_list]
     * attributes:
     *  - primary_color (optional)
     *  - secondary_color (optional)
     */
    public function render_book_list_shortcode( $atts ) {

        // defaults
        $atts = shortcode_atts( array(
            'primary_color'   => '#0d6efd', // default bootstrap blue
            'secondary_color' => '#f8f9fa', // light grey
        ), $atts, 'book_list' );
        // enqueue assets
        $public_class = new Nordic_Books_Public( $this->plugin_name, $this->version );
        $public_class->enqueue_styles( );
        $public_class->enqueue_scripts( $atts );

        // fetch books
        $rows = $this->wpdb->get_results( $this->wpdb->prepare( "SELECT id, title, author, published_year FROM {$this->book_table} ORDER BY id DESC LIMIT %d", 1000 ) ); // limit for safety

        ob_start();

        include $this->get_template_part( 'nordic-books-list-shortcode-display' );
        ?>
        
        <?php

        return ob_get_clean();
    }

    public function ajax_add_book() {
        // Accept both logged-in and guest via wp_ajax_nopriv
        check_ajax_referer( 'nordic_add_book_action', 'nonce' );

        // get POST data
        $title = isset( $_POST['title'] ) ? sanitize_text_field( wp_unslash( $_POST['title'] ) ) : '';
        $author = isset( $_POST['author'] ) ? sanitize_text_field( wp_unslash( $_POST['author'] ) ) : '';
        $year = isset( $_POST['published_year'] ) ? intval( $_POST['published_year'] ) : 0;

        // basic validation
        $errors = array();
        if ( '' === $title ) {
            $errors[] = __( 'Title is required.', NORDIC_BOOK_SLUG );
        }
        if ( '' === $author ) {
            $errors[] = __( 'Author is required.', NORDIC_BOOK_SLUG );
        }
        if ( $year <= 0 ) {
            $errors[] = __( 'Published year must be a positive number.', NORDIC_BOOK_SLUG );
        }

        if ( ! empty( $errors ) ) {
            wp_send_json_error( array( 'message' => implode( ' ', $errors ) ) );
        }

        global $wpdb;

        $data = array(
            'title'          => $title,
            'author'         => $author,
            'published_year' => $year,
        );

        $format = array( '%s', '%s', '%d' );

        $inserted = $wpdb->insert( $this->book_table, $data, $format );

        if ( false === $inserted ) {
            wp_send_json_error( array( 'message' => __( 'Database error while inserting book.', NORDIC_BOOK_SLUG ) ) );
        }

        $insert_id = intval( $wpdb->insert_id );

        // build HTML row to return (escaped)
        $row_html  = '<tr data-book-id="' . esc_attr( $insert_id ) . '">';
        $row_html .= '<td>' . esc_html( $data['title'] ) . '</td>';
        $row_html .= '<td>' . esc_html( $data['author'] ) . '</td>';
        $row_html .= '<td>' . esc_html( $data['published_year'] ) . '</td>';
        $row_html .= '</tr>';

        wp_send_json_success( array(
            'message' => __( 'Book added successfully.', NORDIC_BOOK_SLUG ),
            'row'     => $row_html,
            'id'      => $insert_id,
        ) );
    }

    public function get_template_part ( $template )
    {
        $located       = '';
        $template_slug = rtrim( $template, '.php' );
        $template      = $template_slug . '.php';

        if ( $template )
        {
            if ( file_exists( get_stylesheet_directory() . '/dotline-appointment/' . $template ) )
                $located = get_stylesheet_directory() . '/dotline-appointment/' . $template;
            else if ( file_exists( get_template_directory() . '/dotline-appointment/' . $template ) )
                $located = get_template_directory() . '/dotline-appointment/' . $template;
            else if ( file_exists( NORDIC_BOOK_TEMPLATE . 'public/' . $template ) )
                $located = NORDIC_BOOK_TEMPLATE . 'public/' . $template;
        }

        return $located;
    }

}
