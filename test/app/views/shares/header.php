<?php
require_once 'app/helpers/SessionHelper.php';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hệ thống đặt vé xem phim</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        html, body {
            height: 100%;
            margin: 0;
        }
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .content-wrapper {
            flex: 1 0 auto;
        }
        footer {
            flex-shrink: 0;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
        <div class="container">
            <a class="navbar-brand" href="/test/Movie/list">
                <i class="fas fa-film me-2"></i>Cinema Booking
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/test/Movie/list">
                            <i class="fas fa-list me-1"></i>Danh sách phim
                        </a>
                    </li>
                    <?php if (SessionHelper::isAdmin()): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/test/Movie/add">
                                <i class="fas fa-plus me-1"></i>Thêm phim mới
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
                <ul class="navbar-nav">
                    <?php if (SessionHelper::isLoggedIn()): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user me-1"></i>
                                <?php 
                                    $firstName = SessionHelper::get('first_name');
                                    $lastName = SessionHelper::get('last_name');
                                    echo htmlspecialchars($firstName . ' ' . $lastName);
                                ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="/test/Auth/profile">
                                        <i class="fas fa-user-circle me-1"></i>Thông tin cá nhân
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="/test/Booking/index">
                                        <i class="fas fa-ticket-alt me-1"></i>Lịch sử đặt vé
                                    </a>
                                </li>
                                <?php if (SessionHelper::isAdmin()): ?>
                                    <li>
                                        <a class="dropdown-item" href="/test/Movie/add">
                                            <i class="fas fa-plus me-1"></i>Thêm phim mới
                                        </a>
                                    </li>
                                <?php endif; ?>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-danger" href="/test/Auth/logout">
                                        <i class="fas fa-sign-out-alt me-1"></i>Đăng xuất
                                    </a>
                                </li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/test/Auth/login">
                                <i class="fas fa-sign-in-alt me-1"></i>Đăng nhập
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/test/Auth/register">
                                <i class="fas fa-user-plus me-1"></i>Đăng ký
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php if (SessionHelper::isAdmin()): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-cogs me-1"></i>Quản lý hệ thống
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="/test/Theater/index">
                                        <i class="fas fa-building me-1"></i>Quản lý rạp chiếu
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="/test/Screen/index">
                                        <i class="fas fa-tv me-1"></i>Quản lý phòng chiếu
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="/test/Showtime/index">
                                        <i class="fas fa-calendar-alt me-1"></i>Quản lý lịch chiếu
                                    </a>
                                </li>
                            </ul>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    
    <div class="content-wrapper"><?php // Bắt đầu phần nội dung chính ?>
</body>
</html>