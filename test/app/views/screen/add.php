<?php 
include_once 'app/views/shares/header.php';
SessionHelper::requireAdmin();
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h3 class="mb-0"><i class="fas fa-tv me-2"></i>Thêm phòng chiếu mới</h3>
                    <a href="/test/Screen/index" class="btn btn-light">
                        <i class="fas fa-arrow-left me-1"></i>Quay lại
                    </a>
                </div>
                <div class="card-body">
                    <?php if(isset($error)): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>
                    
                    <?php if(isset($errors) && !empty($errors)): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach($errors as $err): ?>
                                    <li><?php echo $err; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    
                    <?php if(isset($_SESSION['success_message'])): ?>
                        <div class="alert alert-success">
                            <?php echo $_SESSION['success_message']; ?>
                            <?php unset($_SESSION['success_message']); ?>
                        </div>
                    <?php endif; ?>
                    
                    <form action="/test/Screen/add" method="POST">
                        <div class="mb-3">
                            <label for="theater_id" class="form-label">Rạp chiếu phim <span class="text-danger">*</span></label>
                            <select name="theater_id" id="theater_id" class="form-select" required>
                                <option value="">-- Chọn rạp --</option>
                                <?php foreach($theaters as $theater): ?>
                                    <option value="<?php echo $theater['theater_id']; ?>" <?php echo (isset($_POST['theater_id']) && $_POST['theater_id'] == $theater['theater_id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($theater['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="screen_number" class="form-label">Số phòng chiếu <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="screen_number" name="screen_number" 
                                   value="<?php echo isset($_POST['screen_number']) ? htmlspecialchars($_POST['screen_number']) : ''; ?>" required>
                            <div class="form-text">Nhập số phòng chiếu (ví dụ: 1, 2, 3...)</div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="rows" class="form-label">Số hàng <span class="text-danger">*</span></label>
                                <input type="number" min="1" max="26" class="form-control" id="rows" name="rows" 
                                      value="<?php echo isset($_POST['rows']) ? (int)$_POST['rows'] : 8; ?>">
                                <div class="form-text">Số hàng ghế (tối đa 26 hàng - A đến Z)</div>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="seats_per_row" class="form-label">Số ghế mỗi hàng <span class="text-danger">*</span></label>
                                <input type="number" min="4" class="form-control" id="seats_per_row" name="seats_per_row" 
                                      value="<?php echo isset($_POST['seats_per_row']) ? (int)$_POST['seats_per_row'] : 10; ?>">
                                <div class="form-text">Số ghế mỗi hàng (ít nhất 4 ghế)</div>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="capacity" class="form-label">Tổng số ghế <span class="text-danger">*</span></label>
                                <input type="number" min="1" class="form-control" id="capacity" name="capacity" 
                                       value="<?php echo isset($_POST['capacity']) ? (int)$_POST['capacity'] : 80; ?>" readonly>
                                <div class="form-text">Được tính tự động = Số hàng × Số ghế mỗi hàng</div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="screen_type" class="form-label">Loại phòng chiếu</label>
                            <select class="form-select" id="screen_type" name="screen_type">
                                <option value="Standard" <?php echo (isset($_POST['screen_type']) && $_POST['screen_type'] == 'Standard') ? 'selected' : ''; ?>>Thường</option>
                                <option value="IMAX" <?php echo (isset($_POST['screen_type']) && $_POST['screen_type'] == 'IMAX') ? 'selected' : ''; ?>>IMAX</option>
                                <option value="3D" <?php echo (isset($_POST['screen_type']) && $_POST['screen_type'] == '3D') ? 'selected' : ''; ?>>3D</option>
                                <option value="VIP" <?php echo (isset($_POST['screen_type']) && $_POST['screen_type'] == 'VIP') ? 'selected' : ''; ?>>VIP</option>
                            </select>
                        </div>
                        
                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Lưu phòng chiếu
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* CSS dành riêng cho trang thêm phòng chiếu */
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
    
    .form-label {
        color: white;
        font-weight: 500;
    }
    
    .text-danger {
        color: #ff6b6b !important;
    }
    
    .form-text {
        color: rgba(255, 255, 255, 0.7) !important;
    }
    
    .form-control, .form-select {
        background-color: rgba(255, 255, 255, 0.08) !important;
        border: 1px solid rgba(255, 255, 255, 0.15);
        color: white !important;
    }
    
    .form-control:focus, .form-select:focus {
        background-color: rgba(255, 255, 255, 0.12) !important;
        border-color: var(--accent-color);
        box-shadow: 0 0 0 0.25rem rgba(52, 152, 219, 0.25);
    }
    
    .form-control::placeholder {
        color: rgba(255, 255, 255, 0.5);
    }
    
    .form-select option {
        background-color: var(--card-bg-color);
        color: white;
    }
    
    /* Tùy chỉnh alert */
    .alert-danger {
        background-color: rgba(231, 76, 60, 0.2);
        color: #e74c3c;
        border: 1px solid rgba(231, 76, 60, 0.3);
    }
    
    .alert-success {
        background-color: rgba(46, 204, 113, 0.2);
        color: #2ecc71;
        border: 1px solid rgba(46, 204, 113, 0.3);
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const rowsInput = document.getElementById('rows');
    const seatsPerRowInput = document.getElementById('seats_per_row');
    const capacityInput = document.getElementById('capacity');
    
    // Hàm tính toán tổng số ghế
    function calculateCapacity() {
        const rows = parseInt(rowsInput.value) || 0;
        const seatsPerRow = parseInt(seatsPerRowInput.value) || 0;
        capacityInput.value = rows * seatsPerRow;
    }
    
    // Thêm sự kiện cho các trường nhập liệu
    rowsInput.addEventListener('input', calculateCapacity);
    seatsPerRowInput.addEventListener('input', calculateCapacity);
    
    // Tính toán ban đầu
    calculateCapacity();
});
</script>

<?php include_once 'app/views/shares/footer.php'; ?> 