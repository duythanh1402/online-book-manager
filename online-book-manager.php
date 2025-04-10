<?php
/*
Plugin Name: Online Book Manager
Description: Quản lý sách online với trang sách và OCR.
Version: 1.0
Author: Your Name
*/

// Ngăn truy cập trực tiếp
if (!defined('ABSPATH')) {
    exit;
}

// Đăng ký Custom Post Type cho sách
function obm_register_book_post_type() {
    $args = array(
        'public' => true,
        'label'  => 'Books',
        'supports' => array('title', 'thumbnail'),
        'taxonomies' => array('category'),
        'menu_icon' => 'dashicons-book',
    );
    register_post_type('book', $args);
}
add_action('init', 'obm_register_book_post_type');

// Tạo bảng cơ sở dữ liệu khi kích hoạt plugin
function obm_create_database_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'book_pages';
    $charset_collate = $wpdb->get_charset_collate();

    // Kiểm tra xem bảng đã tồn tại chưa
    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
        $sql = "CREATE TABLE $table_name (
            id BIGINT(20) AUTO_INCREMENT PRIMARY KEY,
            book_id BIGINT(20) NOT NULL,
            page_number INT NOT NULL,
            page_image_url VARCHAR(255),
            page_content TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            INDEX book_id_idx (book_id)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}
register_activation_hook(__FILE__, 'obm_create_database_table');

// Không xóa bảng khi tắt plugin để giữ dữ liệu
// Nếu cần xóa, bạn có thể thêm hàm register_deactivation_hook().

// Tải các file phụ trợ
require_once plugin_dir_path(__FILE__) . 'includes/admin-functions.php';
require_once plugin_dir_path(__FILE__) . 'includes/frontend-functions.php';
require_once plugin_dir_path(__FILE__) . 'includes/ocr-functions.php';
echo 'test webhook';