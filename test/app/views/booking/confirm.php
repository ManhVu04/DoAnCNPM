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

<style>
    /* CSS dành riêng cho trang xác nhận đặt vé */
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
    
    /* Thông tin suất chiếu và Vé đã chọn */
    .movie-info p, .ticket-info p {
        color: white;
        margin-bottom: 0.5rem;
    }
    
    .movie-info strong, .ticket-info strong {
        color: var(--accent-color);
    }
    
    .movie-info i, .ticket-info i {
        color: var(--accent-color);
        width: 20px;
        text-align: center;
    }
    
    /* Bảng vé đã chọn */
    .table {
        color: white !important;
        background-color: rgba(24, 37, 56, 0.8) !important;
        margin-bottom: 0;
        border-collapse: collapse;
    }
    
    .table thead th {
        color: rgba(255, 255, 255, 0.9) !important;
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
        background-color: rgba(24, 37, 56, 0.5);
    }
    
    .table tbody tr:nth-child(even) td {
        background-color: rgba(24, 37, 56, 0.7);
    }
    
    .table tbody td strong {
        color: #3498db;
        font-weight: 600;
    }
    
    .table tfoot tr {
        background-color: rgba(24, 37, 56, 0.9) !important;
    }
    
    .table tfoot th {
        color: white !important;
        border-color: rgba(52, 152, 219, 0.2);
        font-weight: 600;
        padding: 12px;
    }
    
    .table tfoot th[colspan="2"] {
        color: #3498db !important;
        font-weight: 700;
    }
    
    .table tfoot .text-end {
        color: #3498db !important;
        font-weight: 700;
        font-size: 1.1em;
    }
    
    /* Hover effect cho bảng */
    .table tbody tr:hover td {
        background-color: rgba(52, 152, 219, 0.15);
        transition: background-color 0.2s ease;
    }
    
    /* Card header styles */
    .card-header.bg-primary {
        background-color: var(--accent-color) !important;
        border-bottom: none;
        padding: 15px 20px;
    }
    
    .card-header h5 {
        font-weight: 600;
        margin: 0;
    }
    
    .card-header i {
        color: rgba(255, 255, 255, 0.9);
    }
    
    /* Loại ghế và giá vé */
    .seat-type {
        color: rgba(255, 255, 255, 0.9);
    }
    
    .price {
        color: var(--accent-color);
        font-weight: 600;
    }
    
    /* Tổng cộng */
    .total-row {
        background-color: rgba(52, 152, 219, 0.1);
    }
    
    .total-amount {
        color: var(--accent-color) !important;
        font-size: 1.1em;
        font-weight: 700;
    }
    
    /* Card body padding */
    .card-body {
        padding: 20px;
    }
    
    /* Table responsive */
    .table-responsive {
        border-radius: 8px;
        overflow: hidden;
        background-color: rgba(24, 37, 56, 0.8);
        border: 1px solid rgba(52, 152, 219, 0.2);
    }
    
    .total-payment, .total-payment strong {
        color: white !important;
    }
    
    .payment-summary {
        background-color: var(--accent-color) !important;
        border-radius: 5px;
        padding: 12px !important;
    }
    
    .payment-summary span {
        color: white !important;
    }
    
    .payment-summary .text-success {
        color: white !important;
        font-weight: 700;
        font-size: 1.1em;
    }
</style>

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
                            <?php if (!empty($showtime['poster'])): ?>
                                <img src="<?php echo htmlspecialchars($showtime['poster']); ?>" alt="<?php echo htmlspecialchars($showtime['movie_title']); ?>" class="img-fluid rounded">
                                <!-- Debug poster path -->
                                <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                                <div class="small text-muted mt-1">Poster path: <?php echo htmlspecialchars($showtime['poster']); ?></div>
                                <?php endif; ?>
                            <?php else: ?>
                                <img src="/test/public/images/movie-placeholder.jpg" alt="<?php echo htmlspecialchars($showtime['movie_title']); ?>" class="img-fluid rounded">
                                <!-- Debug poster path -->
                                <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                                <div class="small text-warning mt-1">Poster không tìm thấy. Movie ID: <?php echo (int)$showtime['movie_id']; ?></div>
                                <?php endif; ?>
                            <?php endif; ?>
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