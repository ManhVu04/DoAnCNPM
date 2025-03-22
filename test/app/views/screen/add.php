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
                        
                        <div class="mb-3">
                            <label for="capacity" class="form-label">Sức chứa (số ghế) <span class="text-danger">*</span></label>
                            <input type="number" min="1" class="form-control" id="capacity" name="capacity" 
                                   value="<?php echo isset($_POST['capacity']) ? (int)$_POST['capacity'] : ''; ?>" required>
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

<?php include_once 'app/views/shares/footer.php'; ?> 