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
            
            <?php if(isset($_SESSION['success_message'])): ?>
                <div class="alert alert-success">
                    <?php echo $_SESSION['success_message']; ?>
                    <?php unset($_SESSION['success_message']); ?>
                </div>
            <?php endif; ?>
            
            <?php if(empty($screens)): ?>
                <div class="alert alert-info">Không có phòng chiếu nào.</div>
            <?php else: ?>
                <div class="table-responsive admin-table">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Rạp</th>
                                <th>Phòng số</th>
                                <th>Loại phòng</th>
                                <th>Sức chứa</th>
                                <th>Bố trí ghế</th>
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
                                    <td>
                                        <?php if(isset($screen['rows']) && isset($screen['seats_per_row'])): ?>
                                            <?php echo (int)$screen['rows']; ?> hàng × <?php echo (int)$screen['seats_per_row']; ?> ghế
                                        <?php else: ?>
                                            <span class="fst-italic">Chưa thiết lập</span>
                                        <?php endif; ?>
                                    </td>
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

<style>
/* CSS cho trang quản lý admin */
.card {
    background-color: var(--card-bg-color) !important;
    border-color: var(--border-color) !important;
}

.card-header {
    background-color: var(--accent-color) !important;
    border-bottom: none;
}

.card-body {
    color: white;
}

.alert-info {
    background-color: rgba(52, 152, 219, 0.2);
    border-color: rgba(52, 152, 219, 0.3);
    color: white;
}

.alert-success {
    background-color: rgba(46, 204, 113, 0.2);
    border-color: rgba(46, 204, 113, 0.3);
    color: white;
}

.admin-table {
    border-radius: 8px;
    overflow: hidden;
}

.table {
    color: white !important;
    margin-bottom: 0;
}

.table thead th {
    background-color: rgba(52, 152, 219, 0.2) !important;
    color: white !important;
    border-color: rgba(52, 152, 219, 0.2);
    font-weight: 600;
    padding: 12px;
    text-align: left;
}

.table tbody td {
    background-color: rgba(24, 37, 56, 0.7) !important;
    color: white !important;
    border-color: rgba(52, 152, 219, 0.1);
    padding: 12px;
    vertical-align: middle;
}

.table tbody tr:nth-child(odd) td {
    background-color: rgba(24, 37, 56, 0.7) !important;
}

.table tbody tr:nth-child(even) td {
    background-color: rgba(24, 37, 56, 0.9) !important;
}

.table tbody tr:hover td {
    background-color: rgba(52, 152, 219, 0.2) !important;
}

.btn-info, .btn-warning, .btn-danger, .btn-success {
    font-weight: 500;
}

.fst-italic {
    color: rgba(255, 255, 255, 0.6);
}
</style>

<?php include_once 'app/views/shares/footer.php'; ?> 