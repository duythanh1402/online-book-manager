<?php
global $wpdb;
$book_id = intval($_GET['book_id']);
$pages = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}book_pages WHERE book_id = %d ORDER BY page_number", $book_id));
?>
<div class="obm-book-pages">
    <h2><?php echo get_the_title($book_id); ?></h2>
    <?php foreach ($pages as $page): ?>
        <div class="obm-page">
            <img src="<?php echo esc_url($page->page_image_url); ?>" alt="Page <?php echo $page->page_number; ?>">
            <p><?php echo esc_html($page->page_content); ?></p>
        </div>
    <?php endforeach; ?>
</div>