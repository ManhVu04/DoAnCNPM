<?php require_once 'app/views/shares/header.php'; ?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h3 class="card-title text-center">Không tìm thấy ghế ngồi</h3>
                </div>
                <div class="card-body text-center">
                    <div class="alert alert-warning mb-4">
                        <h4><i class="fas fa-exclamation-triangle me-2"></i> Không có vé nào cho suất chiếu này!</h4>
                        <p class="mb-0">Hệ thống chưa tạo vé cho suất chiếu <strong><?= $showtime['movie_title'] ?></strong> 
                        vào ngày <strong><?= date('d/m/Y', strtotime($showtime['show_date'])) ?></strong> 
                        lúc <strong><?= $showtime['show_time'] ?></strong>.</p>
                    </div>
                    
                    <?php if ($isAdmin): ?>
                        <div class="mb-4">
                            <p>Với tư cách quản trị viên, bạn có thể tạo vé cho suất chiếu này:</p>
                            <a href="/test/Booking/generateTickets/<?= $showtime['showtime_id'] ?>" class="btn btn-primary btn-lg">
                                <i class="fas fa-ticket-alt me-2"></i> Tạo vé cho suất chiếu
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="mb-4">
                            <p>Vui lòng liên hệ với quản trị viên để tạo vé cho suất chiếu này.</p>
                            <a href="/test/Movies/index" class="btn btn-primary">
                                <i class="fas fa-film me-2"></i> Quay lại danh sách phim
                            </a>
                        </div>
                    <?php endif; ?>
                    
                    <div class="mt-4 card bg-light p-3">
                        <h5>Các thông tin về suất chiếu:</h5>
                        <table class="table table-striped">
                            <tr>
                                <th>Phim:</th>
                                <td><?= $showtime['movie_title'] ?></td>
                            </tr>
                            <tr>
                                <th>Ngày chiếu:</th>
                                <td><?= date('d/m/Y', strtotime($showtime['show_date'])) ?></td>
                            </tr>
                            <tr>
                                <th>Giờ chiếu:</th>
                                <td><?= $showtime['show_time'] ?></td>
                            </tr>
                            <tr>
                                <th>Phòng chiếu:</th>
                                <td><?= $showtime['screen_number'] ?> - <?= $showtime['theater_name'] ?></td>
                            </tr>
                            <tr>
                                <th>Giá vé:</th>
                                <td><?= number_format($showtime['ticket_price'], 0, ',', '.') ?> VNĐ</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-6">
                            <a href="/test/Movies/detail/<?= $showtime['movie_id'] ?>" class="btn btn-secondary w-100">
                                <i class="fas fa-info-circle me-2"></i> Chi tiết phim
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="/test/Movies/index" class="btn btn-outline-primary w-100">
                                <i class="fas fa-home me-2"></i> Về trang chủ
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'app/views/shares/footer.php'; ?> 