<?php 
include_once 'app/views/shares/header.php';

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
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <h3 class="mb-0"><i class="fas fa-film me-2"></i>Chi tiết rạp chiếu phim</h3>
                    <div>
                        <a href="/test/Theater/index" class="btn btn-light me-2">
                            <i class="fas fa-arrow-left me-1"></i>Quay lại
                        </a>
                        <?php if(SessionHelper::isAdmin()): ?>
                            <a href="/test/Theater/edit/<?php echo $theater['theater_id']; ?>" class="btn btn-warning text-white">
                                <i class="fas fa-edit me-1"></i>Sửa
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered">
                                <tr>
                                    <th class="bg-light" style="width: 30%;">ID rạp</th>
                                    <td><?php echo (int)$theater['theater_id']; ?></td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Tên rạp</th>
                                    <td><?php echo htmlspecialchars($theater['name']); ?></td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Địa chỉ</th>
                                    <td><?php echo htmlspecialchars($theater['address']); ?></td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Thành phố</th>
                                    <td><?php echo htmlspecialchars($theater['city']); ?></td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Tỉnh/Thành</th>
                                    <td><?php echo htmlspecialchars($theater['state']); ?></td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Mã bưu điện</th>
                                    <td><?php echo htmlspecialchars($theater['zip_code']); ?></td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Số điện thoại</th>
                                    <td><?php echo htmlspecialchars($theater['phone_number']); ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <?php if(SessionHelper::isAdmin()): ?>
                    <div class="mt-4">
                        <h4 class="mb-3">Quản lý phòng chiếu của rạp này</h4>
                        <a href="/test/Screen/add" class="btn btn-success">
                            <i class="fas fa-plus me-1"></i>Thêm phòng chiếu mới
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once 'app/views/shares/footer.php'; ?> 