<?php 
require_once 'app/helpers/SessionHelper.php';
include_once 'app/views/shares/header.php'; 
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Danh sách phim</h4>
                    <?php if (SessionHelper::isAdmin()): ?>
                        <a href="/test/Movie/add" class="btn btn-light">
                            <i class="fas fa-plus me-2"></i>Thêm phim mới
                        </a>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger">
                            <?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>

                    <?php if (empty($movies)): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>Chưa có phim nào trong danh sách.
                        </div>
                    <?php else: ?>
                        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                            <?php foreach ($movies as $movie): ?>
                                <div class="col">
                                    <div class="card h-100">
                                        <?php
                                        $posterUrl = '';
                                        if (!empty($movie['poster_url'])) {
                                            // Kiểm tra xem poster_url có bắt đầu bằng /test/ không
                                            if (strpos($movie['poster_url'], '/test/') === 0) {
                                                $posterUrl = $movie['poster_url'];
                                            } else {
                                                $posterUrl = '/test/' . $movie['poster_url'];
                                            }
                                        } else {
                                            $posterUrl = '/test/assets/images/no-poster.jpg';
                                        }
                                        ?>
                                        <div class="position-relative" style="height: 300px;">
                                            <img src="<?php echo htmlspecialchars($posterUrl); ?>" 
                                                class="card-img-top h-100 w-100" 
                                                alt="<?php echo htmlspecialchars($movie['title']); ?>"
                                                style="object-fit: cover;"
                                                onerror="this.onerror=null; this.src='/test/assets/images/no-poster.jpg';">
                                        </div>
                                        <div class="card-body">
                                            <h5 class="card-title text-truncate">
                                                <?php echo htmlspecialchars($movie['title'] ?? 'Không có tiêu đề'); ?>
                                            </h5>
                                            <p class="card-text mb-1">
                                                <small class="text-muted">
                                                    <i class="fas fa-clock me-1"></i>
                                                    <?php echo !empty($movie['duration']) ? htmlspecialchars($movie['duration']) . ' phút' : 'Chưa cập nhật'; ?>
                                                </small>
                                            </p>
                                            <p class="card-text mb-1">
                                                <small class="text-muted">
                                                    <i class="fas fa-film me-1"></i>
                                                    <?php echo !empty($movie['genre']) ? htmlspecialchars($movie['genre']) : 'Chưa phân loại'; ?>
                                                </small>
                                            </p>
                                            <p class="card-text">
                                                <small class="text-muted">
                                                    <i class="fas fa-calendar-alt me-1"></i>
                                                    <?php 
                                                    if (!empty($movie['release_date'])) {
                                                        try {
                                                            echo date('d/m/Y', strtotime($movie['release_date']));
                                                        } catch (Exception $e) {
                                                            echo 'Ngày không hợp lệ';
                                                        }
                                                    } else {
                                                        echo 'Chưa cập nhật';
                                                    }
                                                    ?>
                                                </small>
                                            </p>
                                            <p class="card-text text-truncate">
                                                <?php echo !empty($movie['description']) ? htmlspecialchars($movie['description']) : 'Chưa có mô tả'; ?>
                                            </p>
                                        </div>
                                        <div class="card-footer bg-transparent border-top-0">
                                            <div class="d-flex gap-2">
                                                <a href="/test/Movie/show/<?php echo (int)$movie['movie_id']; ?>" 
                                                   class="btn btn-info text-white flex-grow-1">
                                                    <i class="fas fa-info-circle me-1"></i>Chi tiết
                                                </a>
                                                <?php if (SessionHelper::isAdmin()): ?>
                                                    <a href="/test/Movie/edit/<?php echo (int)$movie['movie_id']; ?>" 
                                                       class="btn btn-primary flex-grow-1">
                                                        <i class="fas fa-edit me-1"></i>Sửa
                                                    </a>
                                                    <a href="/test/Movie/delete/<?php echo (int)$movie['movie_id']; ?>" 
                                                       class="btn btn-danger flex-grow-1"
                                                       onclick="return confirm('Bạn có chắc chắn muốn xóa phim này?');">
                                                        <i class="fas fa-trash-alt me-1"></i>Xóa
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once 'app/views/shares/footer.php'; ?>