<?php
include_once 'app/views/shares/header.php';
SessionHelper::requireAdmin();
?>

<div class="container mt-4">
    <div class="row mb-3">
        <div class="col-md-8">
            <h2><i class="fas fa-calendar-alt me-2"></i>Quản lý lịch chiếu</h2>
        </div>
        <div class="col-md-4 text-end">
            <a href="/test/Showtime/create" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Thêm lịch chiếu mới
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
            <?php if (empty($showtimes)): ?>
                <div class="alert alert-info mb-0">
                    Chưa có lịch chiếu nào. Hãy tạo lịch chiếu mới.
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Phim</th>
                                <th>Rạp</th>
                                <th>Phòng chiếu</th>
                                <th>Ngày chiếu</th>
                                <th>Giờ chiếu</th>
                                <th>Giá vé</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($showtimes as $showtime): ?>
                                <tr>
                                    <td><?php echo $showtime['showtime_id']; ?></td>
                                    <td><?php echo htmlspecialchars($showtime['movie_title']); ?></td>
                                    <td><?php echo htmlspecialchars($showtime['theater_name']); ?></td>
                                    <td><?php echo $showtime['screen_number']; ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($showtime['show_date'])); ?></td>
                                    <td><?php echo date('H:i', strtotime($showtime['show_time'])); ?></td>
                                    <td><?php echo number_format($showtime['ticket_price'], 0, ',', '.'); ?> đ</td>
                                    <td class="text-center">
                                        <a href="/test/Showtime/edit/<?php echo $showtime['showtime_id']; ?>" class="btn btn-sm btn-warning me-1">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="/test/Showtime/delete/<?php echo $showtime['showtime_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa lịch chiếu này không?');">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include_once 'app/views/shares/footer.php'; ?> 