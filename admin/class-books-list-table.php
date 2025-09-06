<?php

if ( ! class_exists( 'WP_List_Table' ) )
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );

class Nordic_Book_List_Table extends \WP_List_Table {

    private $table;

    public function __construct() {
        parent::__construct([
            'singular' => 'book',
            'plural'   => 'books',
            'ajax'     => false,
        ]);

        global $wpdb;
        $this->table = $wpdb->prefix . 'books';
    }

    /**
     * Get table columns
     */
    public function get_columns() {
        return [
            'cb'                => '<input type="checkbox" />',
            'id'                => __( 'ID', NORDIC_BOOK_SLUG ),
            'title'             => __( 'Title', NORDIC_BOOK_SLUG ),
            'author'            => __( 'Author', NORDIC_BOOK_SLUG ),
            'published_year'    => __( 'Published Year', NORDIC_BOOK_SLUG ),
        ];
    }

    /**
     * Columns sortable
     */
    public function get_sortable_columns() {
        return [
            'id'              => ['id', true],
            'title'           => ['title', true],
            'author'          => ['status', false],
            'published_year'  => ['start_at', false],
        ];
    }

    /**
     * Bulk actions
     */
    public function get_bulk_actions() {
        return [
            'bulk_delete'   => __( 'Delete', NORDIC_BOOK_SLUG ),
        ];
    }

    /**
     * Process bulk actions
     */
    public function process_bulk_action() {
        global $wpdb;

        if ('bulk_delete' === $this->current_action() && !empty($_GET['book'])) {
            $ids = array_map('intval', $_GET['book']);
            foreach ($ids as $id) {
                $wpdb->delete($this->table, ['id' => $id]);
            }
            add_settings_error('v_books', '', 'Selected books deleted', 'updated');
        }

        if ('delete' === $this->current_action() && !empty($_GET['id'])) {
            error_log('to delete');
            $id = $_GET['id'];
            $wpdb->delete($this->table, ['id' => $id]);
            add_settings_error('v_books', '', 'Selected book deleted', 'updated');
        }

        if ( $this->current_action() ) {
            wp_redirect( add_query_arg( [
                'page'   => $_REQUEST['page'],
                'status_filter'    => isset($_REQUEST['status_filter']) ? sanitize_text_field($_REQUEST['status_filter']) : '',
                'status' => 'updated'
            ], admin_url('admin.php') ) );
            exit;
        }
    }

    /**
     * Checkbox column
     */
    public function column_cb($item) {
        return sprintf('<input type="checkbox" name="book[]" value="%d" />', $item->id);
    }

    /**
     * ID column with edit/delete links
     */
    public function column_id($item) {
        $delete_link = wp_nonce_url(admin_url('admin.php?page=nordic-book&action=delete&id=' . $item->id), 'v_book_delete_book');

        $actions = [
            'delete' => "<a href='{$delete_link}'>" .  __( 'Delete', NORDIC_BOOK_SLUG ) . "</a>"
        ];

        return sprintf('%1$s %2$s', $item->id, $this->row_actions($actions));
    }

    public function column_default( $item, $column_name ) {
        switch ( $column_name ) {
            case 'title':
                return esc_html( $item->title );
            case 'published_year':
                return esc_html( $item->published_year );
            case 'author':
                return esc_html( $item->author );
            default:
                return print_r( $item, true ); // fallback for debugging
        }

    }

    /**
     * Prepare items
     */
    public function prepare_items() {
        global $wpdb;

        $this->_column_headers = [ $this->get_columns(), [], $this->get_sortable_columns() ];

        $per_page = 10;
        $current_page = $this->get_pagenum();
        $search = (isset($_REQUEST['s']) ? sanitize_text_field($_REQUEST['s']) : '');

        $where = 'WHERE 1=1';
        if ($search) {
            $where .= $wpdb->prepare(" AND title LIKE %s OR author LIKE %s", '%' . $wpdb->esc_like($search) . '%', '%' . $wpdb->esc_like($search) . '%' );
        }

        $orderby = (!empty($_REQUEST['orderby'])) ? esc_sql($_REQUEST['orderby']) : 'id';
        $order   = (!empty($_REQUEST['order'])) ? esc_sql($_REQUEST['order']) : 'DESC';

        $total_items = $wpdb->get_var("SELECT COUNT(*) FROM $this->table $where");
        $this->set_pagination_args([
            'total_items' => $total_items,
            'per_page'    => $per_page
        ]);

        $offset = ($current_page - 1) * $per_page;

        $this->items = $wpdb->get_results("SELECT * FROM $this->table $where ORDER BY $orderby $order LIMIT $offset, $per_page");
        $this->process_bulk_action();
    }

}
