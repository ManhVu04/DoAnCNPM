<?php 
include_once 'app/views/shares/header.php';
SessionHelper::requireAdmin();
?>

<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h3 class="mb-0"><i class="fas fa-tv me-2"></i>Quản lý phòng chiếu</h3>
            <a href="/test/Screen/add" class="btn btn-success">
                <i class="fas fa-plus me-1"></i>Thêm phòng chiếu mới
            </a>
        </div>
        <div class="card-body">
            <?php if(isset($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if(empty($screens)): ?>
                <div class="alert alert-info">Không có phòng chiếu nào.</div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Rạp</th>
                                <th>Phòng số</th>
                                <th>Loại phòng</th>
                                <th>Sức chứa</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($screens as $screen): ?>
                                <tr>
                                    <td><?php echo $screen['screen_id']; ?></td>
                                    <td><?php echo htmlspecialchars($screen['theater_name']); ?></td>
                                    <td><?php echo htmlspecialchars($screen['screen_number']); ?></td>
                                    <td><?php echo htmlspecialchars($screen['screen_type'] ?? 'Thường'); ?></td>
                                    <td><?php echo (int)$screen['capacity']; ?> ghế</td>
                                    <td class="text-center">
                                        <a href="/test/Screen/edit/<?php echo $screen['screen_id']; ?>" class="btn btn-sm btn-warning text-white me-1">
                                            <i class="fas fa-edit"></i> Sửa
                                        </a>
                                        <a href="/test/Screen/delete/<?php echo $screen['screen_id']; ?>" class="btn btn-sm btn-danger" 
                                           onclick="return confirm('Bạn có chắc chắn muốn xóa phòng chiếu này?');">
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