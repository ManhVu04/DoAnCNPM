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
                <div class="table-responsive admin-table">
                    <table class="table">
                        <thead>
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
</style>

<?php include_once 'app/views/shares/footer.php'; ?> 