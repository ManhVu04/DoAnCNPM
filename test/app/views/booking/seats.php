<?php 
include_once 'app/views/shares/header.php';

// Kiểm tra xem có dữ liệu suất chiếu không
if (empty($showtime)) {
    header('Location: /test/Movie/list');
    exit();
}

// Debug - Kiểm tra biến tickets
$ticketCount = is_array($tickets) ? count($tickets) : 0;

// Kiểm tra và hiển thị thông báo
$errorMessage = $_SESSION['error_message'] ?? '';
$successMessage = $_SESSION['success_message'] ?? '';

// Xóa thông báo sau khi đã lấy
if (isset($_SESSION['error_message'])) unset($_SESSION['error_message']);
if (isset($_SESSION['success_message'])) unset($_SESSION['success_message']);
?>

<div class="container mt-4">
    <?php if (!empty($errorMessage)): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i><?php echo htmlspecialchars($errorMessage); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>
    
    <?php if (!empty($successMessage)): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i><?php echo htmlspecialchars($successMessage); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex align-items-center">
                <h2 class="mb-0">Chọn ghế: <?php echo htmlspecialchars($showtime['movie_title']); ?></h2>
                <a href="/test/Booking/showtime/<?php echo (int)$showtime['movie_id']; ?>" class="btn btn-outline-secondary ms-3">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
            </div>
            <p class="text-muted mt-2 text-white">
                <i class="fas fa-calendar-alt me-2"></i><?php echo date('d/m/Y', strtotime($showtime['show_date'])); ?> 
                <i class="fas fa-clock ms-3 me-2"></i><?php echo date('H:i', strtotime($showtime['show_time'])); ?>
                <i class="fas fa-building ms-3 me-2"></i><?php echo htmlspecialchars($showtime['theater_name']); ?>
                <i class="fas fa-tv ms-3 me-2"></i>Phòng số <?php echo (int)$showtime['screen_number']; ?>
            </p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-couch me-2"></i>Sơ đồ ghế ngồi</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <h6 class="screen-label p-2 rounded">MÀN HÌNH</h6>
                    </div>
                    
                    <!-- Debug - Hiển thị thông tin về số lượng vé -->
                    <?php if ($ticketCount == 0): ?>
                    <div class="alert alert-danger">
                        <h5><i class="fas fa-exclamation-triangle me-2"></i>Không tìm thấy ghế nào!</h5>
                        <p>Không có ghế nào được tìm thấy cho suất chiếu này. Có thể vé chưa được tạo cho suất chiếu này.</p>
                        
                        <?php if (SessionHelper::isAdmin()): ?>
                        <div class="mt-3">
                            <form action="/test/Booking/generateTickets" method="POST">
                                <input type="hidden" name="showtime_id" value="<?php echo (int)$showtime['showtime_id']; ?>">
                                <input type="hidden" name="screen_id" value="<?php echo (int)$showtime['screen_id']; ?>">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-ticket-alt me-2"></i>Tạo vé cho suất chiếu này
                                </button>
                            </form>
                        </div>
                        <?php else: ?>
                        <p>Vui lòng liên hệ quản trị viên để được hỗ trợ.</p>
                        <?php endif; ?>
                        
                        <a href="/test/Movie/list" class="btn btn-outline-primary mt-2">
                            <i class="fas fa-film me-2"></i>Quay lại danh sách phim
                        </a>
                    </div>
                    <?php else: ?>
                    <div class="alert alert-info mb-4">
                        <p class="mb-0"><i class="fas fa-info-circle me-2"></i>Tìm thấy <?php echo $ticketCount; ?> ghế cho suất chiếu này.</p>
                    </div>
                    <?php endif; ?>
                    
                    <div id="alertMessage" class="alert alert-warning d-none" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <span id="alertText">Bạn chỉ có thể chọn tối đa 8 ghế cho mỗi lần đặt vé.</span>
                    </div>
                    
                    <?php if ($ticketCount > 0): ?>
                    <form action="/test/Booking/confirm" method="POST" id="seatSelectionForm">
                        <input type="hidden" name="showtime_id" value="<?php echo (int)$showtime['showtime_id']; ?>">
                        
                        <div class="seat-container">
                            <?php 
                            // Nhóm ghế theo hàng (A, B, C...)
                            $seats = [];
                            foreach ($tickets as $ticket) {
                                $row = substr($ticket['seat_number'], 0, 1);
                                if (!isset($seats[$row])) {
                                    $seats[$row] = [];
                                }
                                $seats[$row][] = $ticket;
                            }
                            
                            // Debug - In ra số lượng hàng ghế
                            if (count($seats) == 0) {
                                echo '<div class="alert alert-warning">Không có hàng ghế nào được tìm thấy!</div>';
                            }
                            
                            // Hiển thị ghế theo hàng
                            foreach ($seats as $row => $rowSeats): 
                            ?>
                                <div class="seat-row mb-2">
                                    <div class="row-label"><?php echo $row; ?></div>
                                    <?php foreach ($rowSeats as $seat): ?>
                                        <div class="seat <?php echo $seat['status'] === 'booked' ? 'booked' : ''; ?>">
                                            <?php if ($seat['status'] === 'available'): ?>
                                                <input type="checkbox" name="ticket_ids[]" value="<?php echo (int)$seat['ticket_id']; ?>" id="seat-<?php echo (int)$seat['ticket_id']; ?>" class="seat-checkbox">
                                                <label for="seat-<?php echo (int)$seat['ticket_id']; ?>" class="seat-label" data-seat="<?php echo htmlspecialchars($seat['seat_number']); ?>" data-price="<?php echo (int)$seat['price']; ?>" title="<?php echo htmlspecialchars($seat['seat_number']) . ' - ' . number_format($seat['price'], 0, ',', '.') . 'đ'; ?>">
                                                    <?php echo substr($seat['seat_number'], 1); ?>
                                                </label>
                                            <?php else: ?>
                                                <span class="booked-seat" title="Đã đặt"><?php echo substr($seat['seat_number'], 1); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <div class="seat-legend mt-4">
                            <div class="d-flex justify-content-center">
                                <div class="legend-item">
                                    <div class="seat-example available"></div>
                                    <span>Ghế trống</span>
                                </div>
                                <div class="legend-item">
                                    <div class="seat-example selected"></div>
                                    <span>Ghế đã chọn</span>
                                </div>
                                <div class="legend-item">
                                    <div class="seat-example booked"></div>
                                    <span>Ghế đã đặt</span>
                                </div>
                            </div>
                        </div>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-shopping-cart me-2"></i>Thông tin đặt vé</h5>
                </div>
                <div class="card-body">
                    <?php if ($ticketCount > 0): ?>
                    <div class="ticket-info mb-3">
                        <h6><i class="fas fa-info-circle me-2"></i>Thông tin vé:</h6>
                        <p class="mb-1">Giá vé: <span class="fw-bold"><?php echo number_format($showtime['ticket_price'], 0, ',', '.'); ?> đ</span></p>
                        <p class="mb-1">Số ghế tối đa: <span class="fw-bold">8 ghế</span></p>
                        <p class="mb-0 text-muted small text-white">Lưu ý: Vé đã mua không thể hoàn lại.</p>
                    </div>

                    <div class="selected-seats mb-3">
                        <h6><i class="fas fa-chair me-2"></i>Ghế đã chọn: <span class="text-primary" id="seatCount">(0)</span></h6>
                        <div id="selectedSeatsInfo">
                            <p class="text-muted text-white">Chưa có ghế nào được chọn</p>
                        </div>
                    </div>
                    
                    <div class="price-summary mt-3 p-3 bg-light rounded">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Số lượng ghế:</span>
                            <span id="totalSeats">0</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="fw-bold">Tổng tiền:</span>
                            <span class="text-success fw-bold" id="totalPrice">0 đ</span>
                        </div>
                    </div>
                    
                    <button type="submit" form="seatSelectionForm" class="btn btn-success w-100 mt-3" id="continueButton" disabled>
                        <i class="fas fa-shopping-cart me-2"></i>Tiến hành đặt vé
                    </button>
                    <?php else: ?>
                    <div class="alert alert-warning">
                        <p>Không thể đặt vé cho suất chiếu này vì chưa có ghế nào được tạo.</p>
                        <p>Vui lòng chọn suất chiếu khác hoặc liên hệ quản trị viên.</p>
                    </div>
                    
                    <a href="/test/Booking/showtime/<?php echo (int)$showtime['movie_id']; ?>" class="btn btn-primary w-100">
                        <i class="fas fa-calendar-alt me-2"></i>Xem các suất chiếu khác
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.screen-label {
    background: linear-gradient(to bottom, #c7c7c7, #e6e6e6);
    width: 80%;
    margin: 0 auto;
    border-radius: 5px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    color: #333;
}

.seat-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin: 20px 0;
}

.seat-row {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
}

.row-label {
    width: 30px;
    text-align: center;
    font-weight: bold;
    color: #0d6efd;
}

.seat {
    width: 35px;
    height: 35px;
    margin: 0 5px;
    text-align: center;
    line-height: 35px;
    position: relative;
}

.seat-checkbox {
    display: none;
}

.seat-label {
    display: block;
    width: 100%;
    height: 100%;
    background-color: #e3f2fd;
    border: 1px solid #0d6efd;
    border-radius: 5px;
    cursor: pointer;
    transition: all 0.3s;
    color: #0d6efd;
    line-height: 35px;
    font-size: 14px;
    z-index: 1;
    position: relative;
}

.seat-label:hover {
    background-color: #cce5ff;
    transform: scale(1.05);
}

.seat-checkbox:checked + .seat-label {
    background-color: #28a745;
    border-color: #28a745;
    color: white;
    animation: seatPulse 0.5s;
}

@keyframes seatPulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}

.booked-seat {
    display: block;
    width: 100%;
    height: 100%;
    background-color: #f8d7da;
    border: 1px solid #dc3545;
    border-radius: 5px;
    color: #dc3545;
    cursor: not-allowed;
}

.seat-legend {
    margin-top: 20px;
    color: white;
}

.legend-item {
    display: flex;
    align-items: center;
    margin: 0 15px;
}

.seat-example {
    width: 20px;
    height: 20px;
    border-radius: 3px;
    margin-right: 5px;
}

.seat-example.available {
    background-color: #e3f2fd;
    border: 1px solid #0d6efd;
}

.seat-example.selected {
    background-color: #28a745;
    border: 1px solid #28a745;
}

.seat-example.booked {
    background-color: #f8d7da;
    border: 1px solid #dc3545;
}

.legend-item span {
    color: white;
    margin-left: 5px;
}

.text-muted {
    color: white !important;
}

.price-summary {
    border-top: 1px dashed #dee2e6;
    padding-top: 15px;
    background-color: rgba(24, 37, 56, 0.8) !important;
    color: white !important;
}

.price-summary span {
    color: white !important;
}

.price-summary .text-success {
    color: var(--accent-color) !important;
}

.ticket-info h6 {
    color: white;
}

.ticket-info p {
    color: white !important;
}

.ticket-info .fw-bold {
    color: var(--accent-color);
}

.selected-seats h6 {
    color: white;
}

.selected-seats p {
    color: white !important;
}

.selected-seats #seatCount {
    color: var(--accent-color);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Script loaded...');

    const seatCheckboxes = document.querySelectorAll('.seat-checkbox');
    const seatLabels = document.querySelectorAll('.seat-label');
    const selectedSeatsInfo = document.getElementById('selectedSeatsInfo');
    const totalPriceElement = document.getElementById('totalPrice');
    const totalSeatsElement = document.getElementById('totalSeats');
    const seatCountElement = document.getElementById('seatCount');
    const continueButton = document.getElementById('continueButton');
    const alertMessage = document.getElementById('alertMessage');
    const alertText = document.getElementById('alertText');
    const MAX_SEATS = 8; // Số lượng ghế tối đa cho phép
    
    console.log('Found ' + seatCheckboxes.length + ' checkboxes');
    console.log('Found ' + seatLabels.length + ' labels');
    
    // Thêm sự kiện change cho checkboxes
    seatCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            console.log('Checkbox changed: ' + this.id + ', checked: ' + this.checked);
            updateSelectedSeats();
        });
    });
    
    // Sửa xử lý sự kiện click cho labels
    seatLabels.forEach(label => {
        label.addEventListener('click', function(e) {
            console.log('Label clicked: ' + this.getAttribute('for'));
            
            // Lấy checkbox tương ứng với label
            const checkboxId = this.getAttribute('for');
            const checkbox = document.getElementById(checkboxId);
            
            if (checkbox) {
                // Kiểm tra số lượng ghế đã chọn
                const currentSelectedCount = document.querySelectorAll('.seat-checkbox:checked').length;
                
                // Nếu checkbox chưa được chọn và đã đạt số lượng tối đa
                if (!checkbox.checked && currentSelectedCount >= MAX_SEATS) {
                    // Ngăn chặn việc chọn thêm
                    e.preventDefault();
                    e.stopPropagation();
                    
                    // Hiển thị thông báo cảnh báo
                    alertText.textContent = `Bạn chỉ có thể chọn tối đa ${MAX_SEATS} ghế cho mỗi lần đặt vé.`;
                    alertMessage.classList.remove('d-none');
                    setTimeout(() => {
                        alertMessage.classList.add('d-none');
                    }, 3000);
                    
                    return false;
                }
                
                // KHÔNG CẦN toggle checkbox vì sự kiện click trên label tự động toggle checkbox
                // Chỉ cần cập nhật giao diện
                setTimeout(updateSelectedSeats, 10);
            }
        });
    });
    
    // Bổ sung hàm kiểm tra và xử lý trực tiếp trên checkbox
    function directCheckboxToggle(checkboxId) {
        const checkbox = document.getElementById(checkboxId);
        if (checkbox) {
            const currentSelectedCount = document.querySelectorAll('.seat-checkbox:checked').length;
            
            // Nếu đã đạt số lượng tối đa và đang cố gắng chọn thêm
            if (currentSelectedCount >= MAX_SEATS && !checkbox.checked) {
                alertText.textContent = `Bạn chỉ có thể chọn tối đa ${MAX_SEATS} ghế cho mỗi lần đặt vé.`;
                alertMessage.classList.remove('d-none');
                setTimeout(() => {
                    alertMessage.classList.add('d-none');
                }, 3000);
                
                return false;
            }
            
            checkbox.checked = !checkbox.checked;
            updateSelectedSeats();
            return true;
        }
        return false;
    }
    
    // Thêm sự kiện click trực tiếp cho seat (vùng chứa cả checkbox và label)
    document.querySelectorAll('.seat').forEach(seat => {
        if (!seat.classList.contains('booked')) {
            seat.addEventListener('click', function(e) {
                // Nếu click trực tiếp vào seat (không phải label hay checkbox)
                if (e.target === this) {
                    const checkbox = this.querySelector('.seat-checkbox');
                    if (checkbox) {
                        const currentSelectedCount = document.querySelectorAll('.seat-checkbox:checked').length;
                        
                        // Nếu đã đạt số lượng tối đa và đang cố gắng chọn thêm
                        if (currentSelectedCount >= MAX_SEATS && !checkbox.checked) {
                            alertText.textContent = `Bạn chỉ có thể chọn tối đa ${MAX_SEATS} ghế cho mỗi lần đặt vé.`;
                            alertMessage.classList.remove('d-none');
                            setTimeout(() => {
                                alertMessage.classList.add('d-none');
                            }, 3000);
                            
                            return false;
                        }
                        
                        checkbox.checked = !checkbox.checked;
                        e.preventDefault();
                        updateSelectedSeats();
                    }
                }
            });
        }
    });
    
    // Cập nhật khi trang được tải
    updateSelectedSeats();
    
    function updateSelectedSeats() {
        console.log('Updating selected seats...');
        
        const selectedSeats = [];
        let totalPrice = 0;
        
        // Kiểm tra tất cả checkbox đã chọn
        const checkedSeats = document.querySelectorAll('.seat-checkbox:checked');
        const seatCount = checkedSeats.length;
        
        console.log(seatCount + ' seats selected');
        
        // Lấy thông tin từ mỗi ghế đã chọn
        checkedSeats.forEach(checkbox => {
            const seatId = checkbox.value;
            const seatLabel = document.querySelector(`label[for="seat-${seatId}"]`);
            
            if (seatLabel) {
                const seatNumber = seatLabel.getAttribute('data-seat');
                const seatPrice = parseInt(seatLabel.getAttribute('data-price'), 10);
                
                selectedSeats.push({
                    id: seatId,
                    number: seatNumber,
                    price: seatPrice
                });
                
                totalPrice += seatPrice;
            }
        });
        
        // Cập nhật thông tin ghế đã chọn trên giao diện
        if (selectedSeats.length > 0) {
            let seatsHtml = '<ul class="list-group">';
            selectedSeats.forEach(seat => {
                seatsHtml += `<li class="list-group-item d-flex justify-content-between align-items-center">
                                Ghế ${seat.number}
                                <span class="badge bg-primary rounded-pill">${numberFormat(seat.price)} đ</span>
                              </li>`;
            });
            seatsHtml += '</ul>';
            selectedSeatsInfo.innerHTML = seatsHtml;
            continueButton.disabled = false;
        } else {
            selectedSeatsInfo.innerHTML = '<p class="text-muted">Chưa có ghế nào được chọn</p>';
            continueButton.disabled = true;
        }
        
        // Cập nhật số lượng ghế và tổng tiền
        seatCountElement.textContent = `(${seatCount})`;
        totalSeatsElement.textContent = seatCount;
        totalPriceElement.innerText = numberFormat(totalPrice) + ' đ';
    }
    
    function numberFormat(number) {
        return new Intl.NumberFormat('vi-VN').format(number);
    }
});
</script>

<?php include_once 'app/views/shares/footer.php'; ?> 