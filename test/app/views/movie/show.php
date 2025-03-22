<?php 
include_once 'app/views/shares/header.php';

// Kiểm tra xem có dữ liệu phim không
if (empty($movie)) {
    header('Location: /test/Movie/list');
    exit();
}
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-4">
            <?php
            $posterUrl = '';
            if (!empty($movie['poster_url'])) {
                if (strpos($movie['poster_url'], '/test/') === 0) {
                    $posterUrl = $movie['poster_url'];
                } else {
                    $posterUrl = '/test/' . $movie['poster_url'];
                }
            } else {
                $posterUrl = '/test/assets/images/no-poster.jpg';
            }
            ?>
            <img src="<?php echo htmlspecialchars($posterUrl); ?>" 
                 class="img-fluid rounded shadow" 
                 alt="<?php echo htmlspecialchars($movie['title']); ?>"
                 style="width: 100%; max-height: 500px; object-fit: cover;"
                 onerror="this.onerror=null; this.src='/test/assets/images/no-poster.jpg';">
        </div>
        <div class="col-md-8">
            <div class="card shadow h-100">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title mb-0"><?php echo htmlspecialchars($movie['title']); ?></h3>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong><i class="fas fa-film me-2"></i>Thể loại:</strong> 
                                <?php echo !empty($movie['genre']) ? htmlspecialchars($movie['genre']) : 'Chưa phân loại'; ?>
                            </p>
                            <p><strong><i class="fas fa-clock me-2"></i>Thời lượng:</strong> 
                                <?php echo !empty($movie['duration']) ? htmlspecialchars($movie['duration']) . ' phút' : 'Chưa cập nhật'; ?>
                            </p>
                            <p><strong><i class="fas fa-calendar-alt me-2"></i>Ngày công chiếu:</strong> 
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
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p><strong><i class="fas fa-user-tie me-2"></i>Đạo diễn:</strong> 
                                <?php echo !empty($movie['director']) ? htmlspecialchars($movie['director']) : 'Chưa cập nhật'; ?>
                            </p>
                            <p><strong><i class="fas fa-users me-2"></i>Diễn viên:</strong> 
                                <?php echo !empty($movie['actors']) ? htmlspecialchars($movie['actors']) : 'Chưa cập nhật'; ?>
                            </p>
                        </div>
                    </div>

                    <h5 class="border-bottom pb-2 mb-3"><i class="fas fa-align-left me-2"></i>Mô tả phim</h5>
                    <div class="mb-4">
                        <?php if (!empty($movie['description'])): ?>
                            <p><?php echo nl2br(htmlspecialchars($movie['description'])); ?></p>
                        <?php else: ?>
                            <p class="text-muted">Chưa có mô tả cho phim này.</p>
                        <?php endif; ?>
                    </div>

                    <?php if (!empty($movie['trailer_url'])): ?>
                        <h5 class="border-bottom pb-2 mb-3"><i class="fas fa-play-circle me-2"></i>Trailer</h5>
                        <div class="ratio ratio-16x9 mb-4">
                            <?php
                            $videoId = '';
                            $trailerUrl = $movie['trailer_url'];
                            
                            // Lấy video ID từ YouTube URL
                            if (preg_match('/youtube\.com\/watch\?v=([^&]+)/', $trailerUrl, $matches)) {
                                $videoId = $matches[1];
                            } elseif (preg_match('/youtu\.be\/([^&]+)/', $trailerUrl, $matches)) {
                                $videoId = $matches[1];
                            }
                            
                            if (!empty($videoId)) {
                                echo '<iframe src="https://www.youtube.com/embed/' . htmlspecialchars($videoId) . '" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
                            } else {
                                echo '<div class="alert alert-warning">Không thể tải trailer.</div>';
                            }
                            ?>
                        </div>
                    <?php endif; ?>

                    <div class="d-flex gap-2 mt-4">
                        <a href="/test/Movie/list" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Quay lại
                        </a>
                        <?php if (SessionHelper::isLoggedIn()): ?>
                            <a href="/test/Booking/showtime/<?php echo (int)$movie['movie_id']; ?>" class="btn btn-success">
                                <i class="fas fa-ticket-alt me-2"></i>Mua vé
                            </a>
                        <?php endif; ?>
                        <?php if (SessionHelper::isAdmin()): ?>
                            <a href="/test/Movie/edit/<?php echo (int)$movie['movie_id']; ?>" class="btn btn-primary">
                                <i class="fas fa-edit me-2"></i>Sửa thông tin
                            </a>
                            <a href="/test/Movie/delete/<?php echo (int)$movie['movie_id']; ?>" 
                               class="btn btn-danger"
                               onclick="return confirm('Bạn có chắc chắn muốn xóa phim này?');">
                                <i class="fas fa-trash-alt me-2"></i>Xóa phim
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once 'app/views/shares/footer.php'; ?> 