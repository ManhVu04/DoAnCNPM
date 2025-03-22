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
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-light">
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
                                            <td><?php echo (int)$booking['ticket_count']; ?></td>
                                            <td><?php echo number_format($booking['total_amount'], 0, ',', '.'); ?> đ</td>
                                            <td>
                                                <span class="badge bg-<?php echo $booking['payment_status'] === 'paid' ? 'success' : 'warning'; ?>">
                                                    <?php echo $booking['payment_status'] === 'paid' ? 'Đã thanh toán' : 'Chờ thanh toán'; ?>
                                                </span>
                                            </td>
                                            <td><?php echo date('d/m/Y H:i', strtotime($booking['booking_date'])); ?></td>
                                            <td>
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

<?php include_once 'app/views/shares/footer.php'; ?> 