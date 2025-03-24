    </div><?php // Kết thúc phần nội dung chính (.content-wrapper) ?>
    
    <footer class="footer-bg text-center text-lg-start mt-4">
        <div class="container p-4">
            <div class="row">
                <!-- Cột thông tin -->
                <div class="col-lg-4 col-md-12 mb-4">
                    <h5 class="text-uppercase">
                        <i class="fas fa-film me-2 text-primary"></i>Cinema Booking
                    </h5>
                    <p class="text-light mt-3">
                        Hệ thống đặt vé xem phim trực tuyến với giao diện hiện đại, dễ sử dụng và đa dạng lựa chọn phim hay.
                    </p>
                    <div class="mt-4">
                        <a href="#" class="social-icon me-3"><i class="fab fa-facebook-f fa-lg"></i></a>
                        <a href="#" class="social-icon me-3"><i class="fab fa-twitter fa-lg"></i></a>
                        <a href="#" class="social-icon me-3"><i class="fab fa-instagram fa-lg"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-youtube fa-lg"></i></a>
                    </div>
                </div>
                
                <!-- Cột liên kết -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <h5 class="text-uppercase">Liên kết nhanh</h5>
                    <ul class="list-unstyled mb-0 mt-3">
                        <li class="mb-2">
                            <a href="/test/Movie/list" class="footer-link">
                                <i class="fas fa-chevron-right me-2 small"></i>Danh sách phim
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="/test/Movie/hot" class="footer-link">
                                <i class="fas fa-chevron-right me-2 small"></i>Phim hot
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="/test/Movie/coming" class="footer-link">
                                <i class="fas fa-chevron-right me-2 small"></i>Phim sắp chiếu
                            </a>
                        </li>
                        <li>
                            <a href="/test/Auth/register" class="footer-link">
                                <i class="fas fa-chevron-right me-2 small"></i>Đăng ký thành viên
                            </a>
                        </li>
                    </ul>
                </div>
                
                <!-- Cột liên hệ -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <h5 class="text-uppercase">Liên hệ</h5>
                    <ul class="list-unstyled mb-0 mt-3">
                        <li class="mb-2">
                            <i class="fas fa-map-marker-alt me-2 text-primary"></i>123 Nguyễn Văn Linh, Q.7, TP.HCM
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-envelope me-2 text-primary"></i>contact@movieticket.vn
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-phone me-2 text-primary"></i>+84 123 456 789
                        </li>
                        <li>
                            <i class="fas fa-clock me-2 text-primary"></i>8:00 - 22:00, Thứ 2 - Chủ Nhật
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- Dòng bản quyền -->
        <div class="text-center p-3 copyright-section">
            <div class="container d-flex justify-content-between align-items-center flex-wrap">
                <span>© 2024 Cinema Booking. All rights reserved.</span>
                <div>
                    <a href="#" class="text-light me-3 small">Điều khoản sử dụng</a>
                    <a href="#" class="text-light me-3 small">Chính sách bảo mật</a>
                    <a href="#" class="text-light small">Trợ giúp</a>
                </div>
            </div>
        </div>
    </footer>

    <style>
        .footer-bg {
            background: linear-gradient(135deg, #0c1119 0%, #121d2f 100%);
            color: white;
            box-shadow: 0 -5px 15px rgba(0,0,0,0.1);
        }

        .footer-link {
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-block;
            font-weight: 500;
        }

        .footer-link:hover {
            color: white;
            transform: translateX(5px);
            text-decoration: underline;
        }

        .social-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.15);
            color: white;
            transition: all 0.3s ease;
        }

        .social-icon:hover {
            background-color: var(--accent-color);
            color: white;
            transform: translateY(-3px);
        }

        .copyright-section {
            background: rgba(0,0,0,0.3);
            border-top: 1px solid rgba(255,255,255,0.1);
        }

        .footer-bg h5 {
            font-weight: 700;
            margin-bottom: 1.5rem;
            position: relative;
            display: inline-block;
            padding-bottom: 8px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }
        
        .footer-bg h5:after {
            content: '';
            position: absolute;
            width: 50%;
            height: 2px;
            background-color: var(--accent-color);
            bottom: 0;
            left: 0;
        }
        
        .text-primary {
            color: var(--accent-color) !important;
        }

        .footer-bg ul li {
            margin-bottom: 10px;
        }

        .footer-bg .text-light {
            color: white !important;
        }
    </style>

    <script>
        // Add active class to current nav item
        document.addEventListener('DOMContentLoaded', function() {
            const currentLocation = location.pathname;
            const menuItems = document.querySelectorAll('.nav-link');

            menuItems.forEach(item => {
                if(currentLocation.includes(item.getAttribute('href'))) {
                    item.classList.add('active');
                }
            });
        });
    </script>
</body>
</html>