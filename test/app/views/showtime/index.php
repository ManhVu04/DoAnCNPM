<?php
include_once 'app/views/shares/header.php';
SessionHelper::requireAdmin();
?>

<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h3 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Quản lý lịch chiếu</h3>
            <a href="/test/Showtime/create" class="btn btn-success">
                <i class="fas fa-plus me-2"></i>Thêm lịch chiếu mới
            </a>
        </div>

        <div class="card-body">
            <?php if (isset($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo $error; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if (empty($showtimes)): ?>
                <div class="alert alert-info mb-0">
                    Chưa có lịch chiếu nào. Hãy tạo lịch chiếu mới.
                </div>
            <?php else: ?>
                <div class="table-responsive admin-table">
                    <table class="table">
                        <thead>
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
                                    <td class="price-column"><?php echo number_format($showtime['ticket_price'], 0, ',', '.'); ?> đ</td>
                                    <td class="text-center">
                                        <a href="/test/Showtime/edit/<?php echo $showtime['showtime_id']; ?>" class="btn btn-sm btn-warning text-white me-1">
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

<style>
/* CSS cho trang quản lý admin */
.card {
    background-color: var(--card-bg-color) !important;
    border-color: var(--border-color) !important;
}

.card-header {
    background-color: var(--accent-color) !important;
    border-bottom: none;
}

.card-body {
    color: white;
}

.alert-info {
    background-color: rgba(52, 152, 219, 0.2);
    border-color: rgba(52, 152, 219, 0.3);
    color: white;
}

.alert-danger {
    background-color: rgba(231, 76, 60, 0.2);
    border-color: rgba(231, 76, 60, 0.3);
    color: white;
}

.admin-table {
    border-radius: 8px;
    overflow: hidden;
}

.table {
    color: white !important;
    margin-bottom: 0;
}

.table thead th {
    background-color: rgba(52, 152, 219, 0.2) !important;
    color: white !important;
    border-color: rgba(52, 152, 219, 0.2);
    font-weight: 600;
    padding: 12px;
    text-align: left;
}

.table tbody td {
    background-color: rgba(24, 37, 56, 0.7) !important;
    color: white !important;
    border-color: rgba(52, 152, 219, 0.1);
    padding: 12px;
    vertical-align: middle;
}

.table tbody tr:nth-child(odd) td {
    background-color: rgba(24, 37, 56, 0.7) !important;
}

.table tbody tr:nth-child(even) td {
    background-color: rgba(24, 37, 56, 0.9) !important;
}

.table tbody tr:hover td {
    background-color: rgba(52, 152, 219, 0.2) !important;
}

.price-column {
    color: #3498db !important;
    font-weight: 500;
}

.btn-info, .btn-warning, .btn-danger, .btn-success {
    font-weight: 500;
}

.btn-close {
    filter: brightness(200%);
}
</style>

<?php include_once 'app/views/shares/footer.php'; ?> 