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
        <div class="col-md-8 col-lg-6">
            <div class="card border-0 shadow-lg rounded-3 overflow-hidden">
                <div class="card-body p-4 p-md-5">
                    <h2 class="fw-bold mb-4 text-center text-primary">Đăng nhập</h2>
                    
                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?php 
                                echo $_SESSION['success'];
                                unset($_SESSION['success']);
                            ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?php echo $error; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <form action="/test/Auth/login" method="POST">
                        <div class="mb-4">
                            <label for="email" class="form-label fw-semibold">Email</label>
                            <input type="email" 
                                   class="form-control" 
                                   id="email" 
                                   name="email" 
                                   placeholder="Nhập email của bạn"
                                   required>
                        </div>
                        <div class="mb-4">
                            <div class="d-flex justify-content-between">
                                <label for="password" class="form-label fw-semibold">Mật khẩu</label>
                                <a href="#" class="text-decoration-none small">Quên mật khẩu?</a>
                            </div>
                            <input type="password" 
                                   class="form-control" 
                                   id="password" 
                                   name="password" 
                                   placeholder="Nhập mật khẩu của bạn"
                                   required>
                        </div>
                        <div class="mb-4 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">Ghi nhớ</label>
                        </div>
                        <div class="d-grid mb-4">
                            <button type="submit" class="btn btn-primary py-2 fw-semibold">
                                Đăng nhập
                            </button>
                        </div>
                        <div class="text-center auth-links">
                            <a href="/test/Auth/register" class="text-decoration-none">Đăng ký</a> · 
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