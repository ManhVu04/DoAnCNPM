<?php 
include_once 'app/views/shares/header.php';

// Kiểm tra xem có dữ liệu đặt vé không
if (empty($booking) || empty($tickets)) {
    header('Location: /test/Movie/list');
    exit();
}
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow border-success">
                <div class="card-header bg-success text-white">
                    <h3 class="mb-0"><i class="fas fa-check-circle me-2"></i>Đặt vé thành công!</h3>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="success-animation">
                            <i class="fas fa-thumbs-up text-success"></i>
                        </div>
                        <h4>Cảm ơn bạn đã đặt vé!</h4>
                        <p class="text-muted">Mã đặt vé: <strong><?php echo (int)$booking['booking_id']; ?></strong></p>
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
                                <p><strong>Trạng thái thanh toán:</strong> 
                                    <span class="badge bg-<?php echo $booking['payment_status'] === 'paid' ? 'success' : 'warning'; ?>">
                                        <?php echo $booking['payment_status'] === 'paid' ? 'Đã thanh toán' : 'Chờ thanh toán'; ?>
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="ticket-info mb-4">
                        <h5 class="border-bottom pb-2"><i class="fas fa-ticket-alt me-2"></i>Thông tin vé</h5>
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
                    
                    <div class="note-info mb-4">
                        <h5 class="border-bottom pb-2"><i class="fas fa-info-circle me-2"></i>Hướng dẫn</h5>
                        <ol>
                            <li>Vui lòng đến quầy vé trước giờ chiếu 15-30 phút.</li>
                            <li>Xuất trình mã đặt vé và CCCD/CMND để nhận vé.</li>
                            <li>Thanh toán tại quầy nếu bạn chọn thanh toán khi nhận vé.</li>
                            <li>Nếu có bất kỳ thắc mắc, vui lòng liên hệ hotline: <strong>1900 xxxx</strong>.</li>
                        </ol>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="/test/Movie/list" class="btn btn-primary">
                            <i class="fas fa-film me-2"></i>Xem thêm phim khác
                        </a>
                        <a href="/test/Booking/index" class="btn btn-info text-white">
                            <i class="fas fa-list me-2"></i>Xem lịch sử đặt vé
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* CSS dành riêng cho trang đặt vé thành công */
    .card {
        background-color: var(--card-bg-color) !important;
        border-color: #2ecc71 !important;
    }
    
    .card-header {
        background-color: #2ecc71 !important;
        border-bottom: none;
    }
    
    .card-body {
        color: white;
    }
    
    .success-animation {
        margin: 20px 0;
    }
    
    .success-animation i {
        font-size: 80px;
        color: #2ecc71;
        animation: pulse 1.5s infinite;
    }
    
    .text-muted {
        color: rgba(255, 255, 255, 0.7) !important;
    }
    
    .text-muted strong {
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
    
    .table th {
        color: white !important;
        border-color: rgba(52, 152, 219, 0.2);
        background-color: rgba(52, 152, 219, 0.2);
        font-weight: 600;
        padding: 12px;
    }
    
    .table td {
        color: white !important;
        border-color: rgba(52, 152, 219, 0.1);
        padding: 12px;
        vertical-align: middle;
        background-color: rgba(24, 37, 56, 0.9) !important;
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
    
    .text-end {
        color: #3498db !important;
        font-weight: 500;
    }
    
    /* Đảm bảo số ghế hiển thị đúng màu */
    .table tbody td:first-child {
        font-weight: 600;
        color: white !important;
    }
    
    /* Đảm bảo giá vé hiển thị đúng màu */
    .table tbody td.text-end {
        color: #3498db !important;
    }
    
    .ticket-info h5 {
        margin-bottom: 15px;
    }
    
    .note-info ol {
        color: white;
        padding-left: 1.5rem;
    }
    
    .note-info ol li {
        margin-bottom: 0.5rem;
    }
    
    .note-info strong {
        color: var(--accent-color);
    }
    
    h3, h4, h5 {
        color: white;
    }
    
    .badge {
        font-weight: 500;
    }
    
    .btn-info {
        background-color: #3498db;
        border-color: #3498db;
    }
    
    .btn-info:hover {
        background-color: #2980b9;
        border-color: #2980b9;
    }
    
    @keyframes pulse {
        0% {
            transform: scale(0.9);
        }
        50% {
            transform: scale(1.1);
        }
        100% {
            transform: scale(0.9);
        }
    }
</style>

<?php include_once 'app/views/shares/footer.php'; ?> 