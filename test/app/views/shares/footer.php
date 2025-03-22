    </div><?php // Kết thúc phần nội dung chính (.content-wrapper) ?>
    
    <footer class="footer-bg text-center text-lg-start mt-4">
        <div class="container p-4">
            <div class="row">
                <!-- Cột thông tin -->
                <div class="col-lg-6 col-md-12 mb-4">
                    <h5 class="text-uppercase text-white">Quản lý phim</h5>
                    <p class="text-light">
                        Hệ thống quản lý phim giúp bạn theo dõi và cập nhật thông tin phim dễ dàng.
                    </p>
                </div>
                <!-- Cột liên kết nhanh -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5 class="text-uppercase text-white">Liên kết nhanh</h5>
                    <ul class="list-unstyled mb-0">
                        <li><a href="/test/Movie" class="text-light hover-link">Danh sách phim</a></li>
                        <?php if (SessionHelper::isLoggedIn()): ?>
                            <li><a href="/test/Movie/add" class="text-light hover-link">Thêm phim mới</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
        <!-- Dòng bản quyền -->
        <div class="text-center p-3 copyright-section">
            © 2024 Quản lý phim. All rights reserved.
        </div>
    </footer>

    <style>
        .footer-bg {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            box-shadow: 0 -5px 15px rgba(0,0,0,0.1);
        }

        .hover-link {
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .hover-link:hover {
            color: #9eadce !important;
            padding-left: 10px;
        }

        .copyright-section {
            background: rgba(0,0,0,0.2);
            border-top: 1px solid rgba(255,255,255,0.1);
        }

        .footer-bg h5 {
            font-weight: 600;
            margin-bottom: 1.5rem;
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