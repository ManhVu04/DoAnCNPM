<?php
include_once 'app/views/shares/header.php';
SessionHelper::requireLogin();

// Kiểm tra nếu không tìm thấy thông tin người dùng
if (empty($customer)) {
    header('Location: /test/Auth/login');
    exit();
}
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h3 class="mb-0"><i class="fas fa-user-circle me-2"></i>Thông tin cá nhân</h3>
                    <a href="/test/Movie/list" class="btn btn-light">
                        <i class="fas fa-home me-1"></i>Trang chủ
                    </a>
                </div>
                <div class="card-body">
                    <?php if(isset($error)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?php echo $error; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    
                    <?php if(isset($success)): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?php echo $success; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    
                    <form action="/test/Auth/profile" method="POST">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="first_name" class="form-label">Họ <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="first_name" name="first_name" 
                                       value="<?php echo htmlspecialchars($customer['first_name']); ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="last_name" class="form-label">Tên <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="last_name" name="last_name" 
                                       value="<?php echo htmlspecialchars($customer['last_name']); ?>" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" 
                                   value="<?php echo htmlspecialchars($customer['email']); ?>" readonly>
                            <div class="form-text">Email không thể thay đổi.</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="phone_number" class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="phone_number" name="phone_number" 
                                   value="<?php echo htmlspecialchars($customer['phone_number']); ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="address" class="form-label">Địa chỉ</label>
                            <textarea class="form-control" id="address" name="address" rows="3"><?php echo htmlspecialchars($customer['address']); ?></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Vai trò</label>
                            <input type="text" class="form-control" 
                                   value="<?php echo $customer['role'] === 'admin' ? 'Quản trị viên' : 'Khách hàng'; ?>" readonly>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="/test/Booking/index" class="btn btn-info text-white me-2">
                                <i class="fas fa-ticket-alt me-1"></i>Lịch sử đặt vé
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Cập nhật thông tin
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="card shadow mt-4">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><i class="fas fa-key me-2"></i>Thay đổi mật khẩu</h5>
                </div>
                <div class="card-body">
                    <p>Tính năng thay đổi mật khẩu hiện chưa được hỗ trợ. Vui lòng liên hệ quản trị viên nếu bạn cần đặt lại mật khẩu.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once 'app/views/shares/footer.php'; ?> 