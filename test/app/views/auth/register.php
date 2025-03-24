<?php include_once 'app/views/shares/header.php'; ?>

<style>
    body {
        background-color: #0e1521 !important;
        color: #fff;
    }
    .card {
        background-color: #0e1521;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
    .form-control, .input-group-text {
        background-color: #f8f9fa !important;
    }
    .btn-primary {
        background-color: #0d6efd;
        border: 1px solid #0d6efd;
        border-radius: 4px;
    }
    .btn-danger {
        background-color: #dc3545;
        border: 1px solid #dc3545;
        border-radius: 4px;
    }
    .text-primary {
        color: #fff !important;
    }
    .form-check-label, .form-label {
        color: #fff;
    }
    a {
        color: #0d6efd;
    }
    .auth-links a {
        color: #0d6efd;
        margin: 0 10px;
    }
</style>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            <div class="card border-0 shadow-lg rounded-3 overflow-hidden">
                <div class="card-body p-4 p-md-5">
                    <h2 class="fw-bold mb-4 text-center text-primary">Đăng ký tài khoản</h2>

                    <?php if (isset($errors)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                <?php foreach ($errors as $error): ?>
                                    <li><?php echo $error; ?></li>
                                <?php endforeach; ?>
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?php echo $error; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <form action="/test/Auth/register" method="POST">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="first_name" class="form-label fw-semibold">Họ</label>
                                <input type="text" 
                                    class="form-control" 
                                    id="first_name" 
                                    name="first_name" 
                                    placeholder="Nhập họ của bạn"
                                    value="<?php echo $_POST['first_name'] ?? ''; ?>"
                                    required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="last_name" class="form-label fw-semibold">Tên</label>
                                <input type="text" 
                                    class="form-control" 
                                    id="last_name" 
                                    name="last_name" 
                                    placeholder="Nhập tên của bạn"
                                    value="<?php echo $_POST['last_name'] ?? ''; ?>"
                                    required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Email</label>
                            <input type="email" 
                                class="form-control" 
                                id="email" 
                                name="email" 
                                placeholder="Nhập email của bạn"
                                value="<?php echo $_POST['email'] ?? ''; ?>"
                                required>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold">Mật khẩu</label>
                            <input type="password" 
                                class="form-control" 
                                id="password" 
                                name="password" 
                                placeholder="Tạo mật khẩu mới"
                                required>
                            <div class="form-text"><i class="fas fa-info-circle me-1"></i>Mật khẩu phải có ít nhất 6 ký tự</div>
                        </div>

                        <div class="mb-3">
                            <label for="phone_number" class="form-label fw-semibold">Số điện thoại</label>
                            <input type="tel" 
                                class="form-control" 
                                id="phone_number" 
                                name="phone_number" 
                                placeholder="Nhập số điện thoại của bạn"
                                value="<?php echo $_POST['phone_number'] ?? ''; ?>"
                                required>
                        </div>

                        <div class="mb-4">
                            <label for="address" class="form-label fw-semibold">Địa chỉ</label>
                            <textarea class="form-control" 
                                id="address" 
                                name="address" 
                                placeholder="Nhập địa chỉ của bạn"
                                rows="2"><?php echo $_POST['address'] ?? ''; ?></textarea>
                        </div>

                        <div class="d-grid mb-4">
                            <button type="submit" class="btn btn-danger py-2 fw-semibold">
                                Đăng ký
                            </button>
                        </div>
                        <div class="text-center auth-links">
                            <a href="/test/Auth/login" class="text-decoration-none">Đăng nhập</a> · 
                            <a href="#" class="text-decoration-none">Quên mật khẩu</a> · 
                            <a href="#" class="text-decoration-none">Gửi lại email xác nhận</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once 'app/views/shares/footer.php'; ?> 