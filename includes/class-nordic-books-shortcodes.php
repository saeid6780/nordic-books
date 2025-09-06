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
        ?>
        <div class="nordic-book-list-wrapper">
            <div class="nordic-books-wrapper" data-primary="<?php echo esc_attr( $atts['primary_color'] ); ?>" data-secondary="<?php echo esc_attr( $atts['secondary_color'] ); ?>">
                <div class="nordic-books-header">
                    <button class="nordic-add-book-btn" type="button"><?php echo esc_html__( 'Add Book', 'nordic-books' ); ?></button>
                </div>

                <table class="nordic-books-table">
                    <thead>
                    <tr>
                        <th><?php echo esc_html__( 'Title', 'nordic-books' ); ?></th>
                        <th><?php echo esc_html__( 'Author', 'nordic-books' ); ?></th>
                        <th><?php echo esc_html__( 'Year', 'nordic-books' ); ?></th>
                    </tr>
                    </thead>
                    <tbody id="nordic-books-tbody">
                    <?php if ( $rows ) : ?>
                        <?php foreach ( $rows as $r ) : ?>
                            <tr data-book-id="<?php echo esc_attr( $r->id ); ?>">
                                <td><?php echo esc_html( $r->title ); ?></td>
                                <td><?php echo esc_html( $r->author ); ?></td>
                                <td><?php echo esc_html( $r->published_year ); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr class="nordic-empty-row">
                            <td colspan="3"><?php echo esc_html__( 'No books found.', 'nordic-books' ); ?></td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Modal markup (hidden by default) -->
            <div id="nordic-book-modal" class="nordic-modal" aria-hidden="true" role="dialog" aria-label="<?php echo esc_attr__( 'Add Book', 'nordic-books' ); ?>">
                <div class="nordic-modal-content">
                    <button class="nordic-modal-close" aria-label="<?php echo esc_attr__( 'Close', 'nordic-books' ); ?>">&times;</button>
                    <h2><?php echo esc_html__( 'Add New Book', 'nordic-books' ); ?></h2>

                    <form id="nordic-add-book-form" method="post" novalidate>
                        <?php wp_nonce_field( 'nordic_add_book_action', 'nordic_add_book_nonce' ); ?>

                        <div class="nordic-field">
                            <label for="nordic-title"><?php echo esc_html__( 'Title', 'nordic-books' ); ?> *</label>
                            <input type="text" id="nordic-title" name="title" required maxlength="255" />
                        </div>

                        <div class="nordic-field">
                            <label for="nordic-author"><?php echo esc_html__( 'Author', 'nordic-books' ); ?> *</label>
                            <input type="text" id="nordic-author" name="author" required maxlength="255" />
                        </div>

                        <div class="nordic-field">
                            <label for="nordic-year"><?php echo esc_html__( 'Published Year', 'nordic-books' ); ?> *</label>
                            <input type="number" id="nordic-year" name="published_year" required min="0" max="9999" />
                        </div>

                        <div class="nordic-actions">
                            <button type="submit" class="nordic-submit-btn"><?php echo esc_html__( 'Save', 'nordic-books' ); ?></button>
                            <button type="button" class="nordic-cancel-btn"><?php echo esc_html__( 'Cancel', 'nordic-books' ); ?></button>
                        </div>

                        <div class="nordic-form-message" aria-live="polite"></div>
                    </form>

                </div>
            </div>
        </div>
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
            $errors[] = __( 'Title is required.', 'nordic-books' );
        }
        if ( '' === $author ) {
            $errors[] = __( 'Author is required.', 'nordic-books' );
        }
        if ( $year <= 0 ) {
            $errors[] = __( 'Published year must be a positive number.', 'nordic-books' );
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
            wp_send_json_error( array( 'message' => __( 'Database error while inserting book.', 'nordic-books' ) ) );
        }

        $insert_id = intval( $wpdb->insert_id );

        // build HTML row to return (escaped)
        $row_html  = '<tr data-book-id="' . esc_attr( $insert_id ) . '">';
        $row_html .= '<td>' . esc_html( $data['title'] ) . '</td>';
        $row_html .= '<td>' . esc_html( $data['author'] ) . '</td>';
        $row_html .= '<td>' . esc_html( $data['published_year'] ) . '</td>';
        $row_html .= '</tr>';

        wp_send_json_success( array(
            'message' => __( 'Book added successfully.', 'nordic-books' ),
            'row'     => $row_html,
            'id'      => $insert_id,
        ) );
    }

}
