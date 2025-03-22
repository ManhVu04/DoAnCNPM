<?php include_once 'app/views/shares/header.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Đăng ký tài khoản</h4>
                </div>
                <div class="card-body">
                    <?php if (isset($errors)): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach ($errors as $error): ?>
                                    <li><?php echo $error; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger">
                            <?php echo $error; ?>
                        </div>
                    <?php endif; ?>

                    <form action="/test/Auth/register" method="POST">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="first_name" class="form-label">Họ</label>
                                <input type="text" 
                                       class="form-control" 
                                       id="first_name" 
                                       name="first_name" 
                                       value="<?php echo $_POST['first_name'] ?? ''; ?>"
                                       required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="last_name" class="form-label">Tên</label>
                                <input type="text" 
                                       class="form-control" 
                                       id="last_name" 
                                       name="last_name" 
                                       value="<?php echo $_POST['last_name'] ?? ''; ?>"
                                       required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" 
                                   class="form-control" 
                                   id="email" 
                                   name="email" 
                                   value="<?php echo $_POST['email'] ?? ''; ?>"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Mật khẩu</label>
                            <input type="password" 
                                   class="form-control" 
                                   id="password" 
                                   name="password" 
                                   required>
                            <div class="form-text">Mật khẩu phải có ít nhất 6 ký tự</div>
                        </div>

                        <div class="mb-3">
                            <label for="phone_number" class="form-label">Số điện thoại</label>
                            <input type="tel" 
                                   class="form-control" 
                                   id="phone_number" 
                                   name="phone_number" 
                                   value="<?php echo $_POST['phone_number'] ?? ''; ?>"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Địa chỉ</label>
                            <textarea class="form-control" 
                                      id="address" 
                                      name="address" 
                                      rows="3"><?php echo $_POST['address'] ?? ''; ?></textarea>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-user-plus me-2"></i>Đăng ký
                            </button>
                            <a href="/test/Auth/login" class="btn btn-outline-secondary">
                                <i class="fas fa-sign-in-alt me-2"></i>Đã có tài khoản? Đăng nhập
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once 'app/views/shares/footer.php'; ?> 