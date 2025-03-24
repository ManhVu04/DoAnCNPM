<?php 
include_once 'app/views/shares/header.php';

// Kiểm tra xem có dữ liệu phim không
if (empty($movie)) {
    header('Location: /test/Movie/list');
    exit();
}
?>

<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex align-items-center">
                <h2 class="mb-0">Chọn suất chiếu: <?php echo htmlspecialchars($movie['title']); ?></h2>
                <a href="/test/Movie/show/<?php echo (int)$movie['movie_id']; ?>" class="btn btn-outline-secondary ms-3">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
            </div>
        </div>
    </div>

    <?php if (empty($showtimes)): ?>
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>Hiện tại chưa có suất chiếu nào cho bộ phim này.
        </div>
    <?php else: ?>
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Danh sách suất chiếu</h5>
                    </div>
                    <div class="card-body">
                        <?php
                        // Nhóm lịch chiếu theo ngày
                        $showsByDate = [];
                        foreach ($showtimes as $showtime) {
                            $date = $showtime['show_date'];
                            if (!isset($showsByDate[$date])) {
                                $showsByDate[$date] = [];
                            }
                            $showsByDate[$date][] = $showtime;
                        }

                        // Hiển thị lịch chiếu theo ngày
                        foreach ($showsByDate as $date => $shows): 
                            $formattedDate = date('d/m/Y (l)', strtotime($date));
                        ?>
                            <h5 class="mt-3"><?php echo $formattedDate; ?></h5>
                            <div class="card mb-4">
                                <div class="card-body">
                                    <div class="row">
                                        <?php foreach ($shows as $show): ?>
                                            <div class="col-md-4 mb-3">
                                                <div class="card h-100 showtime-card">
                                                    <div class="card-body">
                                                        <h6 class="card-title">
                                                            <i class="fas fa-clock me-2"></i><?php echo date('H:i', strtotime($show['show_time'])); ?>
                                                        </h6>
                                                        <p class="card-text">
                                                            <small>
                                                                <i class="fas fa-building me-1"></i> <?php echo htmlspecialchars($show['theater_name']); ?><br>
                                                                <i class="fas fa-tv me-1"></i> Phòng số <?php echo (int)$show['screen_number']; ?>
                                                            </small>
                                                        </p>
                                                        <a href="/test/Booking/seats/<?php echo (int)$show['showtime_id']; ?>" class="btn btn-sm btn-success w-100">
                                                            <i class="fas fa-ticket-alt me-1"></i> Chọn ghế
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<style>
.showtime-card {
    transition: transform 0.3s;
    border: 1px solid rgba(52, 152, 219, 0.3);
    background-color: rgba(24, 37, 56, 0.8);
}

.showtime-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    border-color: var(--accent-color);
}

.showtime-card .card-title {
    color: white;
}

.showtime-card .card-text {
    color: white;
}

.showtime-card .card-text small {
    color: rgba(255, 255, 255, 0.8);
}

.card-header.bg-primary {
    background-color: var(--accent-color) !important;
}

h5.mt-3 {
    color: white;
    border-left: 4px solid var(--accent-color);
    padding-left: 10px;
    margin-top: 20px !important;
    margin-bottom: 15px;
}

.card-body {
    background-color: var(--card-bg-color);
}
</style>

<?php include_once 'app/views/shares/footer.php'; ?>