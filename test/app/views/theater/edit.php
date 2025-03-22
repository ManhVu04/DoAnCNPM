<?php 
include_once 'app/views/shares/header.php';
SessionHelper::requireAdmin();

// Kiểm tra xem có dữ liệu rạp chiếu hay không
if (empty($theater)) {
    header('Location: /test/Theater/index');
    exit();
}
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-warning text-white d-flex justify-content-between align-items-center">
                    <h3 class="mb-0"><i class="fas fa-edit me-2"></i>Chỉnh sửa rạp chiếu phim</h3>
                    <a href="/test/Theater/index" class="btn btn-light">
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
                    
                    <form action="/test/Theater/edit/<?php echo $theater['theater_id']; ?>" method="POST">
                        <div class="mb-3">
                            <label for="name" class="form-label">Tên rạp chiếu phim <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" 
                                   value="<?php echo htmlspecialchars($theater['name']); ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="address" class="form-label">Địa chỉ <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="address" name="address" 
                                   value="<?php echo htmlspecialchars($theater['address']); ?>" required>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="city" class="form-label">Thành phố <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="city" name="city" 
                                       value="<?php echo htmlspecialchars($theater['city']); ?>" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="state" class="form-label">Tỉnh/Thành</label>
                                <input type="text" class="form-control" id="state" name="state" 
                                       value="<?php echo htmlspecialchars($theater['state']); ?>">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="zip_code" class="form-label">Mã bưu điện</label>
                                <input type="text" class="form-control" id="zip_code" name="zip_code" 
                                       value="<?php echo htmlspecialchars($theater['zip_code']); ?>">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="phone_number" class="form-label">Số điện thoại</label>
                                <input type="text" class="form-control" id="phone_number" name="phone_number" 
                                       value="<?php echo htmlspecialchars($theater['phone_number']); ?>">
                            </div>
                        </div>
                        
                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-warning text-white">
                                <i class="fas fa-save me-2"></i>Cập nhật rạp chiếu phim
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once 'app/views/shares/footer.php'; ?> 