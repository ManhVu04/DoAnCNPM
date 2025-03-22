<?php 
include_once 'app/views/shares/header.php';

// Kiểm tra xem có dữ liệu suất chiếu không
if (empty($showtime)) {
    header('Location: /test/Movie/list');
    exit();
}
?>

<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex align-items-center">
                <h2 class="mb-0">Chọn ghế: <?php echo htmlspecialchars($showtime['movie_title']); ?></h2>
                <a href="/test/Booking/showtime/<?php echo (int)$showtime['movie_id']; ?>" class="btn btn-outline-secondary ms-3">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
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
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-couch me-2"></i>Sơ đồ ghế ngồi</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <h6 class="screen-label p-2 rounded">MÀN HÌNH</h6>
                    </div>
                    
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
                            
                            // Hiển thị ghế theo hàng
                            foreach ($seats as $row => $rowSeats): 
                            ?>
                                <div class="seat-row mb-2">
                                    <div class="row-label"><?php echo $row; ?></div>
                                    <?php foreach ($rowSeats as $seat): ?>
                                        <div class="seat <?php echo $seat['status'] === 'booked' ? 'booked' : ''; ?>">
                                            <?php if ($seat['status'] === 'available'): ?>
                                                <input type="checkbox" name="ticket_ids[]" value="<?php echo (int)$seat['ticket_id']; ?>" id="seat-<?php echo (int)$seat['ticket_id']; ?>" class="seat-checkbox">
                                                <label for="seat-<?php echo (int)$seat['ticket_id']; ?>" class="seat-label" title="<?php echo htmlspecialchars($seat['seat_number']) . ' - ' . number_format($seat['price'], 0, ',', '.') . 'đ'; ?>">
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
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-shopping-cart me-2"></i>Thông tin đặt vé</h5>
                </div>
                <div class="card-body">
                    <div class="selected-seats mb-3">
                        <h6>Ghế đã chọn:</h6>
                        <div id="selectedSeatsInfo">
                            <p class="text-muted">Chưa có ghế nào được chọn</p>
                        </div>
                    </div>
                    
                    <div class="price-summary">
                        <h6>Tổng tiền:</h6>
                        <h4 class="text-success" id="totalPrice">0 đ</h4>
                    </div>
                    
                    <button type="submit" form="seatSelectionForm" class="btn btn-success w-100 mt-3" id="continueButton" disabled>
                        <i class="fas fa-arrow-right me-2"></i>Tiếp tục
                    </button>
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
}

.seat-checkbox:checked + .seat-label {
    background-color: #28a745;
    border-color: #28a745;
    color: white;
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
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const seatCheckboxes = document.querySelectorAll('.seat-checkbox');
    const selectedSeatsInfo = document.getElementById('selectedSeatsInfo');
    const totalPriceElement = document.getElementById('totalPrice');
    const continueButton = document.getElementById('continueButton');
    const seatPrices = {};
    
    // Lưu giá vé cho mỗi ghế
    <?php foreach ($tickets as $ticket): ?>
    seatPrices[<?php echo (int)$ticket['ticket_id']; ?>] = <?php echo (int)$ticket['price']; ?>;
    <?php endforeach; ?>
    
    // Xử lý sự kiện khi chọn/bỏ chọn ghế
    seatCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedSeats);
    });
    
    function updateSelectedSeats() {
        const selectedSeats = [];
        let totalPrice = 0;
        
        seatCheckboxes.forEach(checkbox => {
            if (checkbox.checked) {
                const seatId = checkbox.value;
                const seatLabel = document.querySelector(`label[for="seat-${seatId}"]`);
                const seatNumber = seatLabel.innerText;
                const rowLetter = seatLabel.closest('.seat-row').querySelector('.row-label').innerText;
                const seatPrice = seatPrices[seatId];
                
                selectedSeats.push({
                    id: seatId,
                    number: rowLetter + seatNumber,
                    price: seatPrice
                });
                
                totalPrice += seatPrice;
            }
        });
        
        // Cập nhật thông tin ghế đã chọn
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
        
        // Cập nhật tổng tiền
        totalPriceElement.innerText = numberFormat(totalPrice) + ' đ';
    }
    
    function numberFormat(number) {
        return new Intl.NumberFormat('vi-VN').format(number);
    }
});
</script>

<?php include_once 'app/views/shares/footer.php'; ?> 