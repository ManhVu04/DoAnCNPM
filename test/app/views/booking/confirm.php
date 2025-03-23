<?php 
include_once 'app/views/shares/header.php';

// Kiểm tra xem có dữ liệu không
if (empty($showtime) || empty($selectedTickets)) {
    header('Location: /test/Movie/list');
    exit();
}

// Tính tổng số vé và ghép chuỗi ID vé
$totalTickets = count($selectedTickets);
$ticketIdsString = implode(',', array_column($selectedTickets, 'ticket_id'));
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="d-flex align-items-center justify-content-between">
                <h2 class="mb-0">Xác nhận đặt vé</h2>
                <a href="/test/Booking/seats/<?php echo (int)$showtime['showtime_id']; ?>" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Chọn lại ghế
                </a>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-8">
            <!-- Thông tin phim và suất chiếu -->
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-film me-2"></i>Thông tin suất chiếu</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <img src="<?php echo htmlspecialchars($showtime['poster'] ?? '/test/public/images/movie-placeholder.jpg'); ?>" alt="<?php echo htmlspecialchars($showtime['movie_title']); ?>" class="img-fluid rounded">
                        </div>
                        <div class="col-md-8">
                            <h4><?php echo htmlspecialchars($showtime['movie_title']); ?></h4>
                            <div class="movie-info">
                                <p><i class="fas fa-calendar-alt me-2"></i>Ngày chiếu: <strong><?php echo date('d/m/Y', strtotime($showtime['show_date'])); ?></strong></p>
                                <p><i class="fas fa-clock me-2"></i>Giờ chiếu: <strong><?php echo date('H:i', strtotime($showtime['show_time'])); ?></strong></p>
                                <p><i class="fas fa-building me-2"></i>Rạp: <strong><?php echo htmlspecialchars($showtime['theater_name']); ?></strong></p>
                                <p><i class="fas fa-tv me-2"></i>Phòng chiếu: <strong><?php echo htmlspecialchars($showtime['screen_number']); ?></strong></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Thông tin vé đã chọn -->
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-ticket-alt me-2"></i>Vé đã chọn</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Số ghế</th>
                                    <th>Loại ghế</th>
                                    <th class="text-end">Giá vé</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($selectedTickets as $ticket): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($ticket['seat_number']); ?></strong></td>
                                    <td>Ghế thường</td>
                                    <td class="text-end"><?php echo number_format($ticket['price'], 0, ',', '.'); ?> đ</td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="2">Tổng cộng (<?php echo $totalTickets; ?> vé)</th>
                                    <th class="text-end"><?php echo number_format($totalAmount, 0, ',', '.'); ?> đ</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <!-- Form thanh toán -->
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-credit-card me-2"></i>Thanh toán</h5>
                </div>
                <div class="card-body">
                    <form action="/test/Booking/process" method="POST">
                        <div class="form-group">
                            <input type="hidden" name="showtime_id" value="<?php echo $showtime['showtime_id']; ?>">
                            <input type="hidden" name="total_amount" value="<?php echo $totalAmount; ?>">
                            <?php 
                            // Sử dụng mảng số nguyên, không chuỗi
                            $ticketIdsArray = array_map('intval', array_column($selectedTickets, 'ticket_id'));
                            // Lọc các ID = 0 hoặc không hợp lệ
                            $ticketIdsArray = array_filter($ticketIdsArray, function($id) { return $id > 0; });
                            if (empty($ticketIdsArray)) {
                                echo '<div class="alert alert-danger">Không có vé hợp lệ nào được chọn.</div>';
                            } else {
                                $ticketIdsStr = implode(',', $ticketIdsArray);
                                echo '<input type="hidden" name="ticket_ids" value="' . htmlspecialchars($ticketIdsStr) . '">';
                            }
                            ?>
                            <h5>Phương thức thanh toán:</h5>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="payment_counter" value="at_counter" checked>
                                <label class="form-check-label" for="payment_counter">
                                    Thanh toán tại quầy
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="payment_credit" value="credit_card">
                                <label class="form-check-label" for="payment_credit">
                                    Thanh toán bằng thẻ tín dụng
                                </label>
                            </div>
                        </div>
                        
                        <div id="creditCardInfo" class="payment-info d-none">
                            <div class="mb-3">
                                <label for="card_number" class="form-label">Số thẻ</label>
                                <input type="text" id="card_number" class="form-control" placeholder="1234 5678 9012 3456">
                            </div>
                            <div class="row">
                                <div class="col-6 mb-3">
                                    <label for="card_expiry" class="form-label">Ngày hết hạn</label>
                                    <input type="text" id="card_expiry" class="form-control" placeholder="MM/YY">
                                </div>
                                <div class="col-6 mb-3">
                                    <label for="card_cvv" class="form-label">CVV</label>
                                    <input type="text" id="card_cvv" class="form-control" placeholder="123">
                                </div>
                            </div>
                        </div>
                        
                        <div id="momoInfo" class="payment-info d-none">
                            <div class="mb-3">
                                <label for="phone_number" class="form-label">Số điện thoại MoMo</label>
                                <input type="text" id="phone_number" class="form-control" placeholder="0912345678">
                            </div>
                        </div>
                        
                        <div id="zaloInfo" class="payment-info d-none">
                            <div class="mb-3">
                                <label for="zalo_id" class="form-label">ZaloPay ID</label>
                                <input type="text" id="zalo_id" class="form-control" placeholder="ID ZaloPay của bạn">
                            </div>
                        </div>
                        
                        <div class="payment-summary mt-3 p-3 bg-light rounded">
                            <div class="d-flex justify-content-between">
                                <span class="fw-bold">Tổng thanh toán:</span>
                                <span class="text-success fw-bold"><?php echo number_format($totalAmount, 0, ',', '.'); ?> đ</span>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-success w-100 mt-3">
                            <i class="fas fa-check-circle me-2"></i>Hoàn tất đặt vé
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Thông tin lưu ý -->
            <div class="card shadow mt-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Lưu ý</h5>
                </div>
                <div class="card-body">
                    <ul class="mb-0 ps-3">
                        <li>Vé đã mua không thể đổi hoặc hoàn lại.</li>
                        <li>Vui lòng đến trước giờ chiếu 15-30 phút.</li>
                        <li>Mang theo mã đặt vé hoặc CMND/CCCD để nhận vé.</li>
                        <li>Trẻ em dưới 13 tuổi không được xem phim sau 22:00.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentMethod = document.getElementById('payment_method');
    const creditCardInfo = document.getElementById('creditCardInfo');
    const momoInfo = document.getElementById('momoInfo');
    const zaloInfo = document.getElementById('zaloInfo');
    
    paymentMethod.addEventListener('change', function() {
        // Ẩn tất cả các form thanh toán
        document.querySelectorAll('.payment-info').forEach(el => el.classList.add('d-none'));
        
        // Hiển thị form tương ứng với phương thức đã chọn
        switch(this.value) {
            case 'credit_card':
                creditCardInfo.classList.remove('d-none');
                break;
            case 'momo':
                momoInfo.classList.remove('d-none');
                break;
            case 'zalopay':
                zaloInfo.classList.remove('d-none');
                break;
        }
    });
});
</script>

<?php include_once 'app/views/shares/footer.php'; ?> 