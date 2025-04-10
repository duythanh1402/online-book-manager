<div class="wrap">
    <h1>Thêm trang sách</h1>
    <?php if (isset($_GET['success'])): ?>
        <div class="notice notice-success"><p>Thêm trang thành công!</p></div>
    <?php endif; ?>
    <form method="post" enctype="multipart/form-data">
        <?php wp_nonce_field('obm_upload_pages'); ?>
        <p><label>Chọn sách:</label>
        <select name="book_id">
            <?php
            $books = get_posts(array('post_type' => 'book', 'numberposts' => -1));
            foreach ($books as $book) {
                echo '<option value="' . $book->ID . '">' . esc_html($book->post_title) . '</option>';
            }
            ?>
        </select></p>
        <p><label>Loại dữ liệu:</label>
        <select name="data_type">
            <option value="image">Hình ảnh</option>
            <option value="pdf_scan">PDF Scan</option>
        </select></p>
        <p><label>Tải file:</label><input type="file" name="page_files[]" multiple /></p>
        <p><input type="submit" name="obm_submit_pages" value="Thêm trang" class="button-primary" /></p>
    </form>
</div>