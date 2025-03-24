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
        /* Global styling */
        :root {
            --main-bg-color: #0e1521;
            --main-text-color: #ffffff;
            --accent-color: #3498db;
            --secondary-color: #2ecc71;
            --danger-color: #e74c3c;
            --card-bg-color: #1a2332;
            --hover-color: #1f2b3d;
            --border-color: rgba(255, 255, 255, 0.1);
            --text-muted: rgba(255, 255, 255, 0.85);
        }
        
        html, body {
            height: 100%;
            margin: 0;
            background-color: var(--main-bg-color);
            color: var(--main-text-color);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
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
        
        /* Header and navigation */
        .navbar {
            background: linear-gradient(90deg, #0c1119 0%, #121d2f 100%) !important;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            padding: 0.8rem 1rem;
        }
        
        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: #fff;
            text-shadow: 0 0 10px rgba(52, 152, 219, 0.7);
        }
        
        .navbar-brand i {
            color: var(--accent-color);
        }
        
        .nav-link {
            color: rgba(255, 255, 255, 0.85) !important;
            margin: 0 5px;
            padding: 8px 15px !important;
            border-radius: 4px;
            transition: all 0.3s ease;
        }
        
        .nav-link:hover {
            color: #fff !important;
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .nav-link.active {
            color: #fff !important;
            background-color: var(--accent-color);
        }
        
        /* Cards and containers */
        .card {
            background-color: var(--card-bg-color);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.4);
        }
        
        /* Form elements */
        .form-control {
            background-color: rgba(255, 255, 255, 0.08) !important;
            border: 1px solid rgba(255, 255, 255, 0.15);
            color: white !important;
            border-radius: 5px;
        }
        
        .form-control:focus {
            background-color: rgba(255, 255, 255, 0.12) !important;
            border-color: var(--accent-color);
            box-shadow: 0 0 0 0.25rem rgba(52, 152, 219, 0.25);
            color: white !important;
        }
        
        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }
        
        .btn-primary {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background-color: #2980b9;
            border-color: #2980b9;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(52, 152, 219, 0.4);
        }
        
        .btn-danger {
            background-color: var(--danger-color);
            border-color: var(--danger-color);
            transition: all 0.3s ease;
        }
        
        .btn-danger:hover {
            background-color: #c0392b;
            border-color: #c0392b;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(231, 76, 60, 0.4);
        }
        
        /* Typography */
        h1, h2, h3, h4, h5, h6 {
            color: white;
            font-weight: 600;
        }
        
        a {
            color: var(--accent-color);
            text-decoration: none;
            transition: color 0.3s ease;
            font-weight: 500;
        }
        
        a:hover {
            color: #2980b9;
            text-decoration: underline;
        }
        
        /* Dropdown menus */
        .dropdown-menu {
            background-color: var(--card-bg-color);
            border: 1px solid var(--border-color);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }
        
        .dropdown-item {
            color: white;
            transition: all 0.2s ease;
        }
        
        .dropdown-item:hover {
            background-color: var(--hover-color);
            color: white;
        }
        
        .dropdown-divider {
            border-color: var(--border-color);
        }
        
        /* Movie list styling */
        .movie-card {
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        
        .movie-poster {
            height: 300px;
            object-fit: cover;
            border-radius: 8px 8px 0 0;
        }
        
        .movie-title {
            font-weight: 600;
            margin-top: 10px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        /* Alerts and notices */
        .alert {
            border: none;
            border-radius: 8px;
        }
        
        /* Making form labels white */
        .form-label {
            color: white;
            font-weight: 500;
        }
        
        /* Custom hover effects */
        .hover-zoom {
            transition: transform 0.3s ease;
        }
        
        .hover-zoom:hover {
            transform: scale(1.03);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark mb-4">
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