<?php 
include_once 'app/views/shares/header.php';
SessionHelper::requireAdmin();
?>

<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h3 class="mb-0"><i class="fas fa-film me-2"></i>Quản lý rạp chiếu phim</h3>
            <a href="/test/Theater/add" class="btn btn-success">
                <i class="fas fa-plus me-1"></i>Thêm rạp chiếu mới
            </a>
        </div>
        <div class="card-body">
            <?php if(isset($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if(empty($theaters)): ?>
                <div class="alert alert-info">Không có rạp chiếu phim nào.</div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Tên rạp</th>
                                <th>Địa chỉ</th>
                                <th>Thành phố</th>
                                <th>Số điện thoại</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($theaters as $theater): ?>
                                <tr>
                                    <td><?php echo $theater['theater_id']; ?></td>
                                    <td><?php echo htmlspecialchars($theater['name']); ?></td>
                                    <td><?php echo htmlspecialchars($theater['address']); ?></td>
                                    <td><?php echo htmlspecialchars($theater['city']); ?></td>
                                    <td><?php echo htmlspecialchars($theater['phone_number']); ?></td>
                                    <td class="text-center">
                                        <a href="/test/Theater/show/<?php echo $theater['theater_id']; ?>" class="btn btn-sm btn-info text-white me-1">
                                            <i class="fas fa-eye"></i> Xem
                                        </a>
                                        <a href="/test/Theater/edit/<?php echo $theater['theater_id']; ?>" class="btn btn-sm btn-warning text-white me-1">
                                            <i class="fas fa-edit"></i> Sửa
                                        </a>
                                        <a href="/test/Theater/delete/<?php echo $theater['theater_id']; ?>" class="btn btn-sm btn-danger" 
                                           onclick="return confirm('Bạn có chắc chắn muốn xóa rạp chiếu phim này?');">
                                            <i class="fas fa-trash"></i> Xóa
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include_once 'app/views/shares/footer.php'; ?> 