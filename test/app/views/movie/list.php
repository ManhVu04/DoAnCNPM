<?php 
require_once 'app/helpers/SessionHelper.php';
include_once 'app/views/shares/header.php'; 
?>

<div class="container mt-4">
    <!-- Hero Banner -->
    <div class="movie-banner mb-5 text-center text-md-start">
        <div class="row align-items-center">
            <div class="col-md-7">
                <h1 class="display-4 fw-bold mb-3">Khám phá thế giới điện ảnh</h1>
                <p class="lead mb-4">Đặt vé ngay hôm nay để thưởng thức những bộ phim bom tấn mới nhất với chất lượng hình ảnh và âm thanh tuyệt vời.</p>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="#latest-movies" class="btn btn-primary btn-lg">
                        <i class="fas fa-ticket-alt me-2"></i>Đặt vé ngay
                    </a>
                    <a href="#" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-info-circle me-2"></i>Tìm hiểu thêm
                    </a>
                </div>
            </div>
            <div class="col-md-5 d-none d-md-block">
                <img src="/test/uploads/posters/1-43.jpg" alt="Cinema" class="img-fluid hover-zoom">
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="movie-filters mb-4 p-3 rounded">
        <div class="row g-2">
            <div class="col-md-3 col-sm-6">
                <select class="form-select bg-dark text-white border-dark">
                    <option selected>Thể loại phim</option>
                    <option>Hành động</option>
                    <option>Tình cảm</option>
                    <option>Khoa học viễn tưởng</option>
                    <option>Hoạt hình</option>
                </select>
            </div>
            <div class="col-md-3 col-sm-6">
                <select class="form-select bg-dark text-white border-dark">
                    <option selected>Quốc gia</option>
                    <option>Việt Nam</option>
                    <option>Mỹ</option>
                    <option>Hàn Quốc</option>
                    <option>Trung Quốc</option>
                </select>
            </div>
            <div class="col-md-3 col-sm-6">
                <select class="form-select bg-dark text-white border-dark">
                    <option selected>Năm phát hành</option>
                    <option>2024</option>
                    <option>2023</option>
                    <option>2022</option>
                    <option>2021</option>
                </select>
            </div>
            <div class="col-md-3 col-sm-6">
                <button class="btn btn-primary w-100">
                    <i class="fas fa-search me-2"></i>Tìm kiếm
                </button>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="movie-section" id="latest-movies">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="section-title">Phim mới nhất</h2>
            <?php if (SessionHelper::isAdmin()): ?>
                <a href="/test/Movie/add" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Thêm phim mới
                </a>
            <?php endif; ?>
        </div>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <?php if (empty($movies)): ?>
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>Chưa có phim nào trong danh sách.
            </div>
        <?php else: ?>
            <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4">
                <?php foreach ($movies as $movie): ?>
                    <div class="col">
                        <div class="movie-card card h-100 hover-card">
                            <?php
                            $posterUrl = '';
                            if (!empty($movie['poster_url'])) {
                                if (strpos($movie['poster_url'], '/test/') === 0) {
                                    $posterUrl = $movie['poster_url'];
                                } else {
                                    $posterUrl = '/test/' . $movie['poster_url'];
                                }
                            } else {
                                $posterUrl = '/test/assets/images/no-poster.jpg';
                            }
                            ?>
                            <div class="movie-poster-container">
                                <img src="<?php echo htmlspecialchars($posterUrl); ?>" 
                                    class="card-img-top movie-poster" 
                                    alt="<?php echo htmlspecialchars($movie['title']); ?>"
                                    onerror="this.onerror=null; this.src='/test/assets/images/no-poster.jpg';">
                                <div class="movie-overlay">
                                    <a href="/test/Movie/show/<?php echo (int)$movie['movie_id']; ?>" class="btn btn-primary btn-sm">
                                        <i class="fas fa-info-circle me-1"></i>Chi tiết
                                    </a>
                                    <a href="/test/Booking/showtime/<?php echo (int)$movie['movie_id']; ?>" class="btn btn-success btn-sm">
                                        <i class="fas fa-ticket-alt me-1"></i>Đặt vé
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title movie-title">
                                    <?php echo htmlspecialchars($movie['title'] ?? 'Không có tiêu đề'); ?>
                                </h5>
                                <div class="movie-info">
                                    <span class="movie-duration">
                                        <i class="fas fa-clock me-1"></i>
                                        <?php echo !empty($movie['duration']) ? htmlspecialchars($movie['duration']) . ' phút' : 'Chưa cập nhật'; ?>
                                    </span>
                                    <span class="movie-genre">
                                        <i class="fas fa-film me-1"></i>
                                        <?php echo !empty($movie['genre']) ? htmlspecialchars($movie['genre']) : 'Chưa phân loại'; ?>
                                    </span>
                                    <span class="movie-date">
                                        <i class="fas fa-calendar-alt me-1"></i>
                                        <?php 
                                        if (!empty($movie['release_date'])) {
                                            try {
                                                echo date('d/m/Y', strtotime($movie['release_date']));
                                            } catch (Exception $e) {
                                                echo 'Ngày không hợp lệ';
                                            }
                                        } else {
                                            echo 'Chưa cập nhật';
                                        }
                                        ?>
                                    </span>
                                </div>
                                <p class="card-text movie-description text-truncate">
                                    <?php echo !empty($movie['description']) ? htmlspecialchars($movie['description']) : 'Chưa có mô tả'; ?>
                                </p>
                            </div>
                            <?php if (SessionHelper::isAdmin()): ?>
                                <div class="card-footer bg-transparent">
                                    <div class="d-flex gap-2">
                                        <a href="/test/Movie/edit/<?php echo (int)$movie['movie_id']; ?>" 
                                           class="btn btn-sm btn-outline-primary flex-grow-1">
                                            <i class="fas fa-edit me-1"></i>Sửa
                                        </a>
                                        <a href="/test/Movie/delete/<?php echo (int)$movie['movie_id']; ?>" 
                                           class="btn btn-sm btn-outline-danger flex-grow-1"
                                           onclick="return confirm('Bạn có chắc chắn muốn xóa phim này?');">
                                            <i class="fas fa-trash-alt me-1"></i>Xóa
                                        </a>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
    /* Banner styling */
    .movie-banner {
        padding: 3rem 0;
        background: linear-gradient(135deg, rgba(29, 53, 87, 0.9) 0%, rgba(20, 33, 61, 0.9) 100%);
        border-radius: 10px;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        padding: 3rem;
    }
    
    .movie-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image: url('/test/assets/images/pattern.svg');
        opacity: 0.1;
        z-index: 0;
    }
    
    .movie-banner > .row {
        position: relative;
        z-index: 1;
    }
    
    .movie-banner h1 {
        color: white;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5); /* Thêm đổ bóng cho tiêu đề */
    }
    
    .movie-banner p {
        color: rgba(255, 255, 255, 0.9); /* Tăng độ hiển thị của đoạn văn */
    }
    
    /* Section styling */
    .section-title {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        position: relative;
        display: inline-block;
        padding-left: 15px;
        color: white; /* Đảm bảo màu tiêu đề là trắng */
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3); /* Thêm đổ bóng */
    }
    
    .section-title::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 5px;
        background-color: var(--accent-color);
        border-radius: 10px;
    }
    
    /* Movie card styling */
    .movie-card {
        overflow: hidden;
        transition: all 0.3s ease;
        border: none;
    }
    
    .movie-poster-container {
        position: relative;
        overflow: hidden;
        height: 350px;
    }
    
    .movie-poster {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    
    .movie-card:hover .movie-poster {
        transform: scale(1.05);
    }
    
    .movie-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(0deg, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0) 100%);
        padding: 20px;
        display: flex;
        justify-content: center;
        gap: 10px;
        opacity: 0;
        transform: translateY(20px);
        transition: all 0.3s ease;
    }
    
    .movie-card:hover .movie-overlay {
        opacity: 1;
        transform: translateY(0);
    }
    
    .movie-title {
        font-weight: 700; /* Tăng độ đậm */
        margin-bottom: 10px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        color: white; /* Đảm bảo màu tiêu đề phim là trắng */
    }
    
    .movie-info {
        display: flex;
        flex-direction: column;
        gap: 5px;
        margin-bottom: 10px;
        font-size: 0.9rem;
        color: white; /* Thay đổi từ rgba sang white */
    }
    
    .movie-info i {
        color: var(--accent-color);
        width: 20px;
        text-align: center;
    }
    
    .movie-description {
        font-size: 0.9rem;
        color: rgba(255, 255, 255, 0.9); /* Tăng độ hiển thị */
    }
    
    /* Filter section */
    .movie-filters {
        background-color: var(--card-bg-color);
    }
    
    /* Hover card effect */
    .hover-card {
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3);
    }

    /* Cải thiện độ hiển thị của alert */
    .alert {
        border: none;
        border-radius: 8px;
        font-weight: 500;
    }
    
    /* Định dạng cho card footer */
    .card-footer {
        border-top: 1px solid rgba(255, 255, 255, 0.1);
    }

    /* Styling cho trang chi tiết suất chiếu */
    .showtime-title {
        color: white;
        font-weight: 700;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    }
    
    .showtime-list .btn {
        font-weight: 500;
        letter-spacing: 0.5px;
    }
    
    /* Làm rõ nút chọn ghế */
    .btn-success {
        background-color: #2ecc71;
        border-color: #2ecc71;
        font-weight: 500;
    }
    
    .btn-success:hover {
        background-color: #27ae60;
        border-color: #27ae60;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(46, 204, 113, 0.4);
    }
    
    /* Tăng độ tương phản cho tiêu đề ngày */
    .showtime-date {
        color: white;
        font-weight: 600;
        font-size: 1.25rem;
        margin: 1.5rem 0 1rem 0;
        padding-left: 10px;
        border-left: 4px solid var(--accent-color);
    }
    
    /* Phần video trailer */
    .video-container {
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
    }
    
    /* Mô tả phim */
    .movie-description-title {
        color: white;
        font-weight: 600;
        margin-bottom: 1rem;
        position: relative;
        display: inline-block;
        padding-left: 15px;
    }
    
    .movie-description-title::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 4px;
        background-color: var(--accent-color);
        border-radius: 10px;
    }
    
    /* Quay lại */
    .btn-back {
        color: white;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .btn-back:hover {
        color: var(--accent-color);
        transform: translateX(-5px);
    }
</style>

<?php include_once 'app/views/shares/footer.php'; ?>