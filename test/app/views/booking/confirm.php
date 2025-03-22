<?php 
include_once 'app/views/shares/header.php';

// Kiểm tra xem có dữ liệu showtime không
if (empty($showtime) || empty($selectedTickets)) {
    header('Location: /test/Movie/list');
    exit();
}
?>

<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex align-items-center">
                <h2 class="mb-0">Xác nhận đặt vé: <?php echo htmlspecialchars($showtime['movie_title']); ?></h2>
            </div>
            <p class="text-muted mt-2">
                <i class="fas fa-calendar-alt me-2"></i><?php echo date('d/m/Y', strtotime($showtime['show_date'])); ?> 
                <i class="fas fa-clock ms-3 me-2"></i><?php echo date('H:i', strtotime($showtime['show_time'])); ?>
                <i class="fas fa-building ms-3 me-2"></i><?php echo htmlspecialchars($showtime['theater_name']); ?>
                <i class="fas fa-tv ms-3 me-2"></i>Phòng số <?php echo (int)$showtime['screen_number']; ?>
            </p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-ticket-alt me-2"></i>Chi tiết vé</h5>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Ghế</th>
                                <th class="text-end">Giá vé</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($selectedTickets as $ticket): ?>
                                <tr>
                                    <td>
                                        <strong><?php echo htmlspecialchars($ticket['seat_number']); ?></strong>
                                    </td>
                                    <td class="text-end">
                                        <?php echo number_format($ticket['price'], 0, ',', '.'); ?> đ
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Tổng cộng</th>
                                <th class="text-end"><?php echo number_format($totalAmount, 0, ',', '.'); ?> đ</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Lưu ý</h5>
                </div>
                <div class="card-body">
                    <ul class="mb-0">
                        <li>Vé đã mua không thể hoàn tiền.</li>
                        <li>Vui lòng đến trước giờ chiếu 15-30 phút để nhận vé.</li>
                        <li>Mang theo CCCD/CMND khi đến nhận vé.</li>
                        <li>Việc đặt vé hoàn tất sau khi thanh toán thành công.</li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-credit-card me-2"></i>Thanh toán</h5>
                </div>
                <div class="card-body">
                    <form action="/test/Booking/process" method="POST">
                        <input type="hidden" name="showtime_id" value="<?php echo (int)$showtime['showtime_id']; ?>">
                        <input type="hidden" name="total_amount" value="<?php echo (float)$totalAmount; ?>">
                        
                        <?php foreach ($selectedTickets as $ticket): ?>
                            <input type="hidden" name="ticket_ids[]" value="<?php echo (int)$ticket['ticket_id']; ?>">
                        <?php endforeach; ?>
                        
                        <div class="mb-3">
                            <h5>Tổng tiền thanh toán</h5>
                            <h3 class="text-success"><?php echo number_format($totalAmount, 0, ',', '.'); ?> đ</h3>
                        </div>
                        
                        <div class="mb-3">
                            <h6>Phương thức thanh toán</h6>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="payment_cash" value="cash" checked>
                                <label class="form-check-label" for="payment_cash">
                                    <i class="fas fa-money-bill me-2"></i>Thanh toán khi nhận vé
                                </label>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <a href="/test/Booking/seats/<?php echo (int)$showtime['showtime_id']; ?>" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Quay lại
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-check-circle me-2"></i>Xác nhận đặt vé
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once 'app/views/shares/footer.php'; ?> 