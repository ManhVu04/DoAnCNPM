<?php 
include_once 'app/views/shares/header.php';

// Kiểm tra xem có dữ liệu đặt vé không
if (empty($booking) || empty($tickets)) {
    header('Location: /test/Booking/index');
    exit();
}

// Kiểm tra nếu không phải user đã đặt vé và không phải admin thì chuyển hướng
if ($booking['customer_id'] != SessionHelper::getUserId() && !SessionHelper::isAdmin()) {
    header('Location: /test/Booking/index');
    exit();
}
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <h3 class="mb-0"><i class="fas fa-ticket-alt me-2"></i>Chi tiết đặt vé</h3>
                    <a href="/test/Booking/index" class="btn btn-sm btn-light">
                        <i class="fas fa-arrow-left me-1"></i>Quay lại
                    </a>
                </div>
                <div class="card-body">
                    <div class="booking-status mb-4 text-center">
                        <span class="badge bg-<?php echo $booking['payment_status'] === 'paid' ? 'success' : 'warning'; ?> p-2 fs-6">
                            <?php if ($booking['payment_status'] === 'paid'): ?>
                                <i class="fas fa-check-circle me-1"></i>Đã thanh toán
                            <?php else: ?>
                                <i class="fas fa-clock me-1"></i>Chờ thanh toán
                            <?php endif; ?>
                        </span>
                        <p class="ticket-code mt-2">Mã đặt vé: <strong><?php echo (int)$booking['booking_id']; ?></strong></p>
                        <p class="booking-date">Ngày đặt: <strong><?php echo date('d/m/Y H:i', strtotime($booking['booking_date'])); ?></strong></p>
                    </div>
                    
                    <div class="movie-info mb-4">
                        <h5 class="border-bottom pb-2"><i class="fas fa-film me-2"></i>Thông tin phim</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Phim:</strong> <?php echo htmlspecialchars($booking['movie_title']); ?></p>
                                <p><strong>Rạp:</strong> <?php echo htmlspecialchars($booking['theater_name']); ?></p>
                                <p><strong>Phòng chiếu:</strong> <?php echo (int)$booking['screen_number']; ?></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Ngày chiếu:</strong> <?php echo date('d/m/Y', strtotime($booking['show_date'])); ?></p>
                                <p><strong>Giờ chiếu:</strong> <?php echo date('H:i', strtotime($booking['show_time'])); ?></p>
                                <p><strong>Phương thức thanh toán:</strong> <?php echo htmlspecialchars($booking['payment_method']); ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="ticket-info mb-4">
                        <h5 class="border-bottom pb-2"><i class="fas fa-chair me-2"></i>Thông tin ghế</h5>
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Ghế</th>
                                            <th class="text-end">Giá vé</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($tickets as $ticket): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($ticket['seat_number']); ?></td>
                                                <td class="text-end"><?php echo number_format($ticket['price'], 0, ',', '.'); ?> đ</td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Tổng cộng</th>
                                            <th class="text-end"><?php echo number_format($booking['total_amount'], 0, ',', '.'); ?> đ</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <div class="actions mt-4">
                        <div class="d-flex justify-content-between">
                            <?php if (strtotime($booking['show_date'] . ' ' . $booking['show_time']) > time()): ?>
                                <?php if ($booking['payment_status'] !== 'paid'): ?>
                                    <a href="/test/Booking/pay/<?php echo (int)$booking['booking_id']; ?>" class="btn btn-success">
                                        <i class="fas fa-credit-card me-2"></i>Thanh toán ngay
                                    </a>
                                    <a href="/test/Booking/cancel/<?php echo (int)$booking['booking_id']; ?>" class="btn btn-danger" onclick="return confirm('Bạn có chắc chắn muốn hủy đặt vé này không?');">
                                        <i class="fas fa-times me-2"></i>Hủy đặt vé
                                    </a>
                                <?php else: ?>
                                    <a href="#" class="btn btn-secondary disabled">
                                        <i class="fas fa-check-circle me-2"></i>Đã thanh toán
                                    </a>
                                    <?php if (SessionHelper::isAdmin()): ?>
                                        <a href="/test/Booking/cancel/<?php echo (int)$booking['booking_id']; ?>" class="btn btn-danger" onclick="return confirm('BẠN LÀ ADMIN: Bạn có chắc chắn muốn hủy đặt vé này không?');">
                                            <i class="fas fa-times me-2"></i>Hủy đặt vé (Admin)
                                        </a>
                                    <?php endif; ?>
                                <?php endif; ?>
                            <?php else: ?>
                                <div class="alert alert-info w-100 mb-0">
                                    <i class="fas fa-info-circle me-2"></i>Suất chiếu đã diễn ra. Không thể thực hiện thao tác.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* CSS cho trang chi tiết đặt vé */
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

.booking-status .badge {
    font-size: 1rem;
    padding: 8px 15px;
}

.ticket-code, .booking-date {
    color: white !important;
    margin-bottom: 0.5rem;
}

.ticket-code strong, .booking-date strong {
    color: var(--accent-color);
}

.border-bottom {
    border-color: var(--border-color) !important;
}

.movie-info p, .ticket-info p {
    color: white;
    margin-bottom: 0.5rem;
}

.movie-info strong, .ticket-info strong {
    color: var(--accent-color);
}

.movie-info i, .ticket-info i {
    color: var(--accent-color);
}

.table {
    color: white !important;
    background-color: rgba(24, 37, 56, 0.8) !important;
    border-radius: 6px;
    overflow: hidden;
    margin-bottom: 0;
}

.table thead th {
    color: white !important;
    border-color: rgba(52, 152, 219, 0.2);
    background-color: rgba(52, 152, 219, 0.2);
    font-weight: 600;
    padding: 12px;
}

.table tbody td {
    color: white !important;
    border-color: rgba(52, 152, 219, 0.1);
    padding: 12px;
    vertical-align: middle;
    background-color: rgba(24, 37, 56, 0.7) !important; 
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

/* Ghế */
.table tbody td:first-child {
    color: white !important;
    font-weight: 600;
    background-color: rgba(24, 37, 56, 0.9) !important;
}

/* Giá vé */
.table tbody td.text-end {
    color: #3498db !important;
    font-weight: 500;
}

.table tfoot tr {
    background-color: var(--accent-color) !important;
}

.table tfoot th {
    color: white !important;
    background-color: rgba(52, 152, 219, 0.3) !important;
    font-weight: 600;
    padding: 12px;
}

.table tfoot th.text-end {
    color: white !important;
    font-weight: 700;
    font-size: 1.1em;
}

h3, h4, h5 {
    color: white;
}

.actions .btn {
    padding: 8px 16px;
}

.alert-info {
    background-color: rgba(52, 152, 219, 0.2);
    border-color: rgba(52, 152, 219, 0.3);
    color: white;
}
</style>

<?php include_once 'app/views/shares/footer.php'; ?> 