<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PhimInn - Xem phim HD Online</title>
  <meta name="description" content="Trang web xem phim trực tuyến chất lượng cao">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    /* Base styles */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Inter', sans-serif;
      background-color: #0a1017;
      color: #ffffff;
    }

    /* ... (Toàn bộ nội dung file styles.css ở đây) ... */

    .group:hover .group-hover-scale-105 {
      transform: scale(1.05);
    }

    /* Focus effects */
    .focus-ring-gray-500:focus {
      --tw-ring-color: #6b7280;
      box-shadow: 0 0 0 2px var(--tw-ring-color);
    }

    /* Icons */
    .search-icon {
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%239ca3af' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Ccircle cx='11' cy='11' r='8'%3E%3C/circle%3E%3Cline x1='21' y1='21' x2='16.65' y2='16.65'%3E%3C/line%3E%3C/svg%3E");
    }
  </style>
</head>
<body>
  <div class="min-h-screen bg-dark text-white">
    <!-- Navigation Bar -->
    <header class="border-b border-gray-800 bg-dark">
      <div class="container mx-auto flex items-center justify-between px-4 py-3">
        <div class="flex items-center">
          <a href="/" class="mr-6">
            <h1 class="text-2xl font-cursive text-green-400">PhimInn</h1>
          </a>
          <!-- Các thành phần navigation khác -->
        </div>
      </div>
    </header>

    <!-- Filters -->
    <section class="border-b border-gray-800 py-6">
      <!-- Nội dung filters -->
    </section>

    <!-- Movie Recommendations -->
    <section class="py-8">
      <div class="container mx-auto px-4">
        <!-- Danh sách phim -->
      </div>
    </section>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Mobile menu toggle functionality could be added here
      
      // Hover effects for movie posters
      const movieItems = document.querySelectorAll('.group');
      
      movieItems.forEach(item => {
        const image = item.querySelector('img');
        
        item.addEventListener('mouseenter', () => {
          image.style.transform = 'scale(1.05)';
        });
        
        item.addEventListener('mouseleave', () => {
          image.style.transform = 'scale(1)';
        });
      });

      // Select dropdown functionality
      const selects = document.querySelectorAll('select');
      
      selects.forEach(select => {
        select.addEventListener('change', function() {
          console.log('Filter changed:', this.value);
        });
      });
    });
  </script>
</body>
</html>