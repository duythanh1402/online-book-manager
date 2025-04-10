<?php
// Shortcode hiển thị danh sách sách
function obm_books_shortcode() {
    ob_start();
    $books = get_posts(array('post_type' => 'book', 'numberposts' => -1));
    echo '<div class="obm-book-list">';
    foreach ($books as $book) {
        $thumbnail = get_the_post_thumbnail_url($book->ID, 'thumbnail');
        echo '<div class="obm-book"><a href="' . esc_url(add_query_arg('book_id', $book->ID)) . '">';
        echo '<img src="' . $thumbnail . '" alt="' . esc_attr($book->post_title) . '">';
        echo '<h3>' . esc_html($book->post_title) . '</h3></a></div>';
    }
    echo '</div>';

    // Hiển thị trang sách nếu có book_id
    if (isset($_GET['book_id'])) {
        include plugin_dir_path(__FILE__) . '../templates/frontend-book-display.php';
    }
    return ob_get_clean();
}
add_shortcode('online_books', 'obm_books_shortcode');

// Thêm CSS
function obm_enqueue_styles() {
    wp_enqueue_style('obm-style', plugins_url('/assets/style.css', dirname(__FILE__)));
}
add_action('wp_enqueue_scripts', 'obm_enqueue_styles');