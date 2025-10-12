</main>
    <footer>
        <p>&copy; <?php echo date('Y'); ?> Hệ thống Đặt vé Xem phim. All rights reserved.</p>
    </footer>
    <!-- Swiper JS -->
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <!-- Project JS -->
    <script src="<?php echo BASE_URL; ?>assets/js/script.js"></script>
    <script>
        // Initialize basic swipers if present
        document.addEventListener('DOMContentLoaded', function(){
            if (typeof Swiper !== 'undefined') {
                var topSwiper = new Swiper('.top-swiper', {
                    loop: true,
                    slidesPerView: 5,
                    spaceBetween: 20,
                    autoplay: { delay: 3000 },
                    breakpoints: {
                        320: { slidesPerView: 1 },
                        576: { slidesPerView: 2 },
                        768: { slidesPerView: 3 },
                        992: { slidesPerView: 4 },
                        1200: { slidesPerView: 5 }
                    },
                    pagination: { el: '.swiper-pagination', clickable: true }
                });

                var listSwiper = new Swiper('.list-swiper', {
                    loop: false,
                    slidesPerView: 5,
                    spaceBetween: 16,
                    breakpoints: {
                        320: { slidesPerView: 1.2 },
                        576: { slidesPerView: 2.2 },
                        768: { slidesPerView: 3 },
                        992: { slidesPerView: 4 },
                        1200: { slidesPerView: 5 }
                    }
                });
            }
        });
    </script>
</body>
</html>