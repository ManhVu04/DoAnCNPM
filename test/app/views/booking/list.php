<?php 
include_once 'app/views/shares/header.php';

// Kiểm tra nếu không có thông tin người dùng thì chuyển hướng đến trang đăng nhập
if (!SessionHelper::isLoggedIn()) {
    header('Location: /test/User/login');
    exit();
}
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0"><i class="fas fa-history me-2"></i>Lịch sử đặt vé</h3>
                </div>
                <div class="card-body">
                    <?php if (empty($bookings)): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>Bạn chưa có lịch sử đặt vé nào.
                        </div>
                        <div class="text-center mt-3">
                            <a href="/test/Movie/list" class="btn btn-primary">
                                <i class="fas fa-film me-2"></i>Xem danh sách phim
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive booking-history-table">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Mã đặt vé</th>
                                        <th>Phim</th>
                                        <th>Rạp</th>
                                        <th>Ngày chiếu</th>
                                        <th>Giờ chiếu</th>
                                        <th>Số ghế</th>
                                        <th>Tổng tiền</th>
                                        <th>Trạng thái</th>
                                        <th>Ngày đặt</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($bookings as $booking): ?>
                                        <tr>
                                            <td><?php echo (int)$booking['booking_id']; ?></td>
                                            <td><?php echo htmlspecialchars($booking['movie_title']); ?></td>
                                            <td><?php echo htmlspecialchars($booking['theater_name']); ?></td>
                                            <td><?php echo date('d/m/Y', strtotime($booking['show_date'])); ?></td>
                                            <td><?php echo date('H:i', strtotime($booking['show_time'])); ?></td>
                                            <td class="text-center"><?php echo (int)$booking['ticket_count']; ?></td>
                                            <td class="price-column"><?php echo number_format($booking['total_amount'], 0, ',', '.'); ?> đ</td>
                                            <td>
                                                <span class="badge bg-<?php echo $booking['payment_status'] === 'paid' ? 'success' : 'warning'; ?>">
                                                    <?php echo $booking['payment_status'] === 'paid' ? 'Đã thanh toán' : 'Chờ thanh toán'; ?>
                                                </span>
                                            </td>
                                            <td><?php echo date('d/m/Y H:i', strtotime($booking['booking_date'])); ?></td>
                                            <td class="actions-column">
                                                <a href="/test/Booking/detail/<?php echo (int)$booking['booking_id']; ?>" class="btn btn-sm btn-info text-white">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <?php if (strtotime($booking['show_date'] . ' ' . $booking['show_time']) > time() && $booking['payment_status'] !== 'paid'): ?>
                                                    <a href="/test/Booking/cancel/<?php echo (int)$booking['booking_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn hủy đặt vé này không?');">
                                                        <i class="fas fa-times"></i>
                                                    </a>
                                                <?php endif; ?>
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
    </div>
</div>

<style>
/* CSS cho trang lịch sử đặt vé */
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

.booking-history-table {
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

.actions-column {
    white-space: nowrap;
}

.badge {
    font-weight: 500;
    padding: 6px 10px;
}
</style>

<?php include_once 'app/views/shares/footer.php'; ?> 