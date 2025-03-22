<?php 
include_once 'app/views/shares/header.php';
SessionHelper::requireAdmin();

// Kiểm tra xem có dữ liệu phim không
if (empty($movie)) {
    header('Location: /tets1/Movie/list');
    exit();
}
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Sửa thông tin phim</h4>
                </div>
                <div class="card-body">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger">
                            <?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>

                    <form action="/tets1/Movie/edit/<?php echo (int)$movie['movie_id']; ?>" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="title" class="form-label">Tên phim <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control" 
                                   id="title" 
                                   name="title" 
                                   required 
                                   minlength="3" 
                                   maxlength="255"
                                   value="<?php echo htmlspecialchars($movie['title']); ?>">
                        </div>

                        <div class="mb-3">
                            <label for="director" class="form-label">Đạo diễn</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="director" 
                                   name="director" 
                                   maxlength="255"
                                   value="<?php echo htmlspecialchars($movie['director'] ?? ''); ?>">
                        </div>

                        <div class="mb-3">
                            <label for="actors" class="form-label">Diễn viên</label>
                            <textarea class="form-control" 
                                      id="actors" 
                                      name="actors" 
                                      rows="2"><?php echo htmlspecialchars($movie['actors'] ?? ''); ?></textarea>
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
                                       value="<?php echo !empty($movie['duration']) ? (int)$movie['duration'] : ''; ?>">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="genre" class="form-label">Thể loại</label>
                                <select class="form-select" id="genre" name="genre">
                                    <option value="">Chọn thể loại</option>
                                    <?php
                                    $genres = ['Hành động', 'Phiêu lưu', 'Hoạt hình', 'Hài', 'Tội phạm', 
                                              'Tài liệu', 'Chính kịch', 'Gia đình', 'Giả tưởng', 'Lịch sử', 
                                              'Kinh dị', 'Nhạc', 'Bí ẩn', 'Lãng mạn', 'Khoa học viễn tưởng'];
                                    foreach ($genres as $genre) {
                                        $selected = (!empty($movie['genre']) && $movie['genre'] == $genre) ? 'selected' : '';
                                        echo '<option value="' . htmlspecialchars($genre) . '" ' . $selected . '>' . 
                                             htmlspecialchars($genre) . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="release_date" class="form-label">Ngày công chiếu</label>
                            <input type="date" 
                                   class="form-control" 
                                   id="release_date" 
                                   name="release_date" 
                                   value="<?php echo htmlspecialchars($movie['release_date'] ?? date('Y-m-d')); ?>">
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Mô tả</label>
                            <textarea class="form-control" 
                                      id="description" 
                                      name="description" 
                                      rows="4"><?php echo htmlspecialchars($movie['description'] ?? ''); ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="trailer_url" class="form-label">URL Trailer</label>
                            <input type="url" 
                                   class="form-control" 
                                   id="trailer_url" 
                                   name="trailer_url" 
                                   placeholder="Nhập URL trailer từ YouTube"
                                   value="<?php echo htmlspecialchars($movie['trailer_url'] ?? ''); ?>">
                            <div class="form-text">Nhập URL trailer từ YouTube (ví dụ: https://www.youtube.com/watch?v=...)</div>
                        </div>

                        <div class="mb-3">
                            <label for="poster" class="form-label">Poster phim</label>
                            
                            <?php if (!empty($movie['poster_url'])): ?>
                                <div class="mb-2">
                                    <img src="<?php echo strpos($movie['poster_url'], '/tets1/') === 0 ? 
                                                    htmlspecialchars($movie['poster_url']) : 
                                                    '/tets1/' . htmlspecialchars($movie['poster_url']); ?>" 
                                         class="img-thumbnail" 
                                         alt="Poster hiện tại"
                                         style="max-height: 200px; max-width: 100%;">
                                    <div class="form-text">Poster hiện tại</div>
                                </div>
                            <?php endif; ?>
                            
                            <input type="file" 
                                   class="form-control" 
                                   id="poster" 
                                   name="poster" 
                                   accept="image/*">
                            <div class="form-text">Chỉ tải lên ảnh mới nếu muốn thay đổi. Chấp nhận các file ảnh (jpg, jpeg, png).</div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Lưu thay đổi
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