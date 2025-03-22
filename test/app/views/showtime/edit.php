<?php
include_once 'app/views/shares/header.php';
SessionHelper::requireAdmin();
?>

<div class="container mt-4">
    <div class="row mb-3">
        <div class="col-md-8">
            <h2><i class="fas fa-edit me-2"></i>Chỉnh sửa lịch chiếu</h2>
        </div>
        <div class="col-md-4 text-end">
            <a href="/test/Showtime/index" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Quay lại danh sách
            </a>
        </div>
    </div>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo $error; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow">
        <div class="card-body">
            <form action="/test/Showtime/update/<?php echo $showtime['showtime_id']; ?>" method="POST">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="movie_id" class="form-label">Phim</label>
                        <select class="form-select" id="movie_id" name="movie_id" required>
                            <option value="">Chọn phim</option>
                            <?php foreach ($movies as $movie): ?>
                                <option value="<?php echo $movie['movie_id']; ?>" <?php echo ($movie['movie_id'] == $showtime['movie_id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($movie['title']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="screen_id" class="form-label">Phòng chiếu</label>
                        <select class="form-select" id="screen_id" name="screen_id" required>
                            <option value="">Chọn phòng chiếu</option>
                            <?php foreach ($screens as $screen): ?>
                                <option value="<?php echo $screen['screen_id']; ?>" <?php echo ($screen['screen_id'] == $showtime['screen_id']) ? 'selected' : ''; ?>>
                                    Rạp: <?php echo htmlspecialchars($screen['theater_name']); ?> - 
                                    Phòng: <?php echo $screen['screen_number']; ?> 
                                    (<?php echo $screen['capacity']; ?> ghế)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="show_date" class="form-label">Ngày chiếu</label>
                        <input type="date" class="form-control" id="show_date" name="show_date" required value="<?php echo $showtime['show_date']; ?>">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="show_time" class="form-label">Giờ chiếu</label>
                        <input type="time" class="form-control" id="show_time" name="show_time" required value="<?php echo date('H:i', strtotime($showtime['show_time'])); ?>">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="ticket_price" class="form-label">Giá vé (VNĐ)</label>
                        <input type="number" class="form-control" id="ticket_price" name="ticket_price" required min="0" step="1000" value="<?php echo $showtime['ticket_price']; ?>">
                    </div>
                </div>
                
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>Lưu ý: Chỉnh sửa lịch chiếu có thể ảnh hưởng đến các vé đã được đặt.
                </div>
                
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="/test/Showtime/index" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-2"></i>Hủy
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Cập nhật lịch chiếu
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once 'app/views/shares/footer.php'; ?> 