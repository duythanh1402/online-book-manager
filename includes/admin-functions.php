<?php
// Thêm menu admin
function obm_admin_menu() {
    add_submenu_page('edit.php?post_type=book', 'Add Book Pages', 'Add Pages', 'manage_options', 'obm-add-pages', 'obm_add_pages_callback');
}
add_action('admin_menu', 'obm_admin_menu');

// Callback hiển thị form thêm trang sách
function obm_add_pages_callback() {
    include plugin_dir_path(__FILE__) . '../templates/admin-book-form.php';
}

// Xử lý form tải trang sách
function obm_handle_page_upload() {
    if (isset($_POST['obm_submit_pages']) && check_admin_referer('obm_upload_pages')) {
        global $wpdb;
        $book_id = intval($_POST['book_id']);
        $data_type = sanitize_text_field($_POST['data_type']);
        $files = $_FILES['page_files'];

        foreach ($files['name'] as $key => $name) {
            if ($files['error'][$key] == 0) {
                $file = array(
                    'name' => $files['name'][$key],
                    'type' => $files['type'][$key],
                    'tmp_name' => $files['tmp_name'][$key],
                    'error' => $files['error'][$key],
                    'size' => $files['size'][$key]
                );
                
                $upload = wp_handle_upload($file, array('test_form' => false));
                if (!isset($upload['error'])) {
                    $page_image_url = $upload['url'];
                    $page_content = '';

                    // Xử lý OCR nếu là hình ảnh hoặc PDF scan
                    if ($data_type == 'image' || $data_type == 'pdf_scan') {
                        $page_content = obm_ocr_page($upload['file']);
                    }

                    // Lưu vào bảng wp_book_pages
                    $page_number = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}book_pages WHERE book_id = $book_id") + 1;
                    $wpdb->insert(
                        $wpdb->prefix . 'book_pages',
                        array(
                            'book_id' => $book_id,
                            'page_number' => $page_number,
                            'page_image_url' => $page_image_url,
                            'page_content' => $page_content
                        )
                    );
                }
            }
        }
        wp_redirect(admin_url('edit.php?post_type=book&page=obm-add-pages&success=1'));
        exit;
    }
}
add_action('admin_init', 'obm_handle_page_upload');