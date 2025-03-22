<?php include_once 'app/views/shares/header.php'; ?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Thêm phim mới</h4>
                </div>
                <div class="card-body">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger">
                            <?php echo $error; ?>
                        </div>
                    <?php endif; ?>

                    <form action="/tets1/Movie/add" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="title" class="form-label">Tên phim <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control" 
                                   id="title" 
                                   name="title" 
                                   required 
                                   minlength="3" 
                                   maxlength="255"
                                   value="<?php echo isset($_POST['title']) ? htmlspecialchars($_POST['title']) : ''; ?>">
                        </div>

                        <div class="mb-3">
                            <label for="director" class="form-label">Đạo diễn</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="director" 
                                   name="director" 
                                   maxlength="255"
                                   value="<?php echo isset($_POST['director']) ? htmlspecialchars($_POST['director']) : ''; ?>">
                        </div>

                        <div class="mb-3">
                            <label for="actors" class="form-label">Diễn viên</label>
                            <textarea class="form-control" 
                                      id="actors" 
                                      name="actors" 
                                      rows="2"><?php echo isset($_POST['actors']) ? htmlspecialchars($_POST['actors']) : ''; ?></textarea>
                            <div class="form-text">Nhập tên các diễn viên, phân cách bằng dấu phẩy</div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="duration" class="form-label">Thời lượng (phút)</label>
                                <input type="number" 
                                       class="form-control" 
                                       id="duration" 
                                       name="duration" 
                                       min="1"
                                       value="<?php echo isset($_POST['duration']) ? intval($_POST['duration']) : ''; ?>">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="genre" class="form-label">Thể loại</label>
                                <select class="form-select" id="genre" name="genre">
                                    <option value="">Chọn thể loại</option>
                                    <option value="Hành động" <?php echo (isset($_POST['genre']) && $_POST['genre'] == 'Hành động') ? 'selected' : ''; ?>>Hành động</option>
                                    <option value="Phiêu lưu" <?php echo (isset($_POST['genre']) && $_POST['genre'] == 'Phiêu lưu') ? 'selected' : ''; ?>>Phiêu lưu</option>
                                    <option value="Hoạt hình" <?php echo (isset($_POST['genre']) && $_POST['genre'] == 'Hoạt hình') ? 'selected' : ''; ?>>Hoạt hình</option>
                                    <option value="Hài" <?php echo (isset($_POST['genre']) && $_POST['genre'] == 'Hài') ? 'selected' : ''; ?>>Hài</option>
                                    <option value="Tội phạm" <?php echo (isset($_POST['genre']) && $_POST['genre'] == 'Tội phạm') ? 'selected' : ''; ?>>Tội phạm</option>
                                    <option value="Tài liệu" <?php echo (isset($_POST['genre']) && $_POST['genre'] == 'Tài liệu') ? 'selected' : ''; ?>>Tài liệu</option>
                                    <option value="Chính kịch" <?php echo (isset($_POST['genre']) && $_POST['genre'] == 'Chính kịch') ? 'selected' : ''; ?>>Chính kịch</option>
                                    <option value="Gia đình" <?php echo (isset($_POST['genre']) && $_POST['genre'] == 'Gia đình') ? 'selected' : ''; ?>>Gia đình</option>
                                    <option value="Giả tưởng" <?php echo (isset($_POST['genre']) && $_POST['genre'] == 'Giả tưởng') ? 'selected' : ''; ?>>Giả tưởng</option>
                                    <option value="Lịch sử" <?php echo (isset($_POST['genre']) && $_POST['genre'] == 'Lịch sử') ? 'selected' : ''; ?>>Lịch sử</option>
                                    <option value="Kinh dị" <?php echo (isset($_POST['genre']) && $_POST['genre'] == 'Kinh dị') ? 'selected' : ''; ?>>Kinh dị</option>
                                    <option value="Nhạc" <?php echo (isset($_POST['genre']) && $_POST['genre'] == 'Nhạc') ? 'selected' : ''; ?>>Nhạc</option>
                                    <option value="Bí ẩn" <?php echo (isset($_POST['genre']) && $_POST['genre'] == 'Bí ẩn') ? 'selected' : ''; ?>>Bí ẩn</option>
                                    <option value="Lãng mạn" <?php echo (isset($_POST['genre']) && $_POST['genre'] == 'Lãng mạn') ? 'selected' : ''; ?>>Lãng mạn</option>
                                    <option value="Khoa học viễn tưởng" <?php echo (isset($_POST['genre']) && $_POST['genre'] == 'Khoa học viễn tưởng') ? 'selected' : ''; ?>>Khoa học viễn tưởng</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="release_date" class="form-label">Ngày công chiếu</label>
                            <input type="date" 
                                   class="form-control" 
                                   id="release_date" 
                                   name="release_date" 
                                   value="<?php echo isset($_POST['release_date']) ? htmlspecialchars($_POST['release_date']) : date('Y-m-d'); ?>">
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Mô tả</label>
                            <textarea class="form-control" 
                                      id="description" 
                                      name="description" 
                                      rows="4"><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="trailer_url" class="form-label">URL Trailer</label>
                            <input type="url" 
                                   class="form-control" 
                                   id="trailer_url" 
                                   name="trailer_url" 
                                   placeholder="Nhập URL trailer từ YouTube"
                                   value="<?php echo isset($_POST['trailer_url']) ? htmlspecialchars($_POST['trailer_url']) : ''; ?>">
                            <div class="form-text">Nhập URL trailer từ YouTube (ví dụ: https://www.youtube.com/watch?v=...)</div>
                        </div>

                        <div class="mb-3">
                            <label for="poster" class="form-label">Poster phim</label>
                            <input type="file" 
                                   class="form-control" 
                                   id="poster" 
                                   name="poster" 
                                   accept="image/*">
                            <div class="form-text">Chấp nhận các file ảnh (jpg, jpeg, png). Kích thước tối đa 5MB.</div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Thêm phim
                            </button>
                            <a href="/tets1/Movie/list" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Quay lại
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once 'app/views/shares/footer.php'; ?> 