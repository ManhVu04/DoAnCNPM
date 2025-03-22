<?php 
include_once 'app/views/shares/header.php';

// Kiểm tra xem có dữ liệu phòng chiếu hay không
if (empty($screen)) {
    header('Location: /test/Screen/index');
    exit();
}
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <h3 class="mb-0"><i class="fas fa-tv me-2"></i>Chi tiết phòng chiếu</h3>
                    <div>
                        <a href="/test/Screen/index" class="btn btn-light me-2">
                            <i class="fas fa-arrow-left me-1"></i>Quay lại
                        </a>
                        <?php if(SessionHelper::isAdmin()): ?>
                            <a href="/test/Screen/edit/<?php echo $screen['screen_id']; ?>" class="btn btn-warning text-white">
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
                                    <th class="bg-light" style="width: 30%;">ID phòng chiếu</th>
                                    <td><?php echo (int)$screen['screen_id']; ?></td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Rạp chiếu phim</th>
                                    <td><?php echo htmlspecialchars($screen['theater_name']); ?></td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Số phòng chiếu</th>
                                    <td><?php echo htmlspecialchars($screen['screen_number']); ?></td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Loại phòng chiếu</th>
                                    <td><?php echo htmlspecialchars($screen['screen_type'] ?? 'Thường'); ?></td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Sức chứa</th>
                                    <td><?php echo (int)$screen['capacity']; ?> ghế</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once 'app/views/shares/footer.php'; ?> 