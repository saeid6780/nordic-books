<div class="nordic-book-list-wrapper">
    <div class="nordic-books-wrapper" data-primary="<?php echo esc_attr( $atts['primary_color'] ); ?>" data-secondary="<?php echo esc_attr( $atts['secondary_color'] ); ?>">
        <div class="nordic-books-header">
            <button class="nordic-add-book-btn" type="button"><?php echo esc_html__( 'Add Book', NORDIC_BOOK_SLUG ); ?></button>
        </div>

        <table class="nordic-books-table">
            <thead>
            <tr>
                <th><?php echo esc_html__( 'Title', NORDIC_BOOK_SLUG ); ?></th>
                <th><?php echo esc_html__( 'Author', NORDIC_BOOK_SLUG ); ?></th>
                <th><?php echo esc_html__( 'Year', NORDIC_BOOK_SLUG ); ?></th>
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
                    <td colspan="3"><?php echo esc_html__( 'No books found.', NORDIC_BOOK_SLUG ); ?></td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal markup (hidden by default) -->
    <div id="nordic-book-modal" class="nordic-modal" aria-hidden="true" role="dialog" aria-label="<?php echo esc_attr__( 'Add Book', NORDIC_BOOK_SLUG ); ?>">
        <div class="nordic-modal-content">
            <button class="nordic-modal-close" aria-label="<?php echo esc_attr__( 'Close', NORDIC_BOOK_SLUG ); ?>">&times;</button>
            <h2><?php echo esc_html__( 'Add New Book', NORDIC_BOOK_SLUG ); ?></h2>

            <form id="nordic-add-book-form" method="post" novalidate>
                <?php wp_nonce_field( 'nordic_add_book_action', 'nordic_add_book_nonce' ); ?>

                <div class="nordic-field">
                    <label for="nordic-title"><?php echo esc_html__( 'Title', NORDIC_BOOK_SLUG ); ?> *</label>
                    <input type="text" id="nordic-title" name="title" required maxlength="255" />
                </div>

                <div class="nordic-field">
                    <label for="nordic-author"><?php echo esc_html__( 'Author', NORDIC_BOOK_SLUG ); ?> *</label>
                    <input type="text" id="nordic-author" name="author" required maxlength="255" />
                </div>

                <div class="nordic-field">
                    <label for="nordic-year"><?php echo esc_html__( 'Published Year', NORDIC_BOOK_SLUG ); ?> *</label>
                    <input type="number" id="nordic-year" name="published_year" required min="0" max="9999" />
                </div>

                <div class="nordic-actions">
                    <button type="submit" class="nordic-submit-btn"><?php echo esc_html__( 'Save', NORDIC_BOOK_SLUG ); ?></button>
                    <button type="button" class="nordic-cancel-btn"><?php echo esc_html__( 'Cancel', NORDIC_BOOK_SLUG ); ?></button>
                </div>

                <div class="nordic-form-message" aria-live="polite"></div>
            </form>

        </div>
    </div>
</div>