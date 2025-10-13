// Wait for the DOM to be fully loaded before initializing Swiper
document.addEventListener('DOMContentLoaded', function () {

    // Initialize the top carousel
    const topSwiper = new Swiper('.top-swiper', {
        // Optional parameters
        loop: true,
        autoplay: {
            delay: 4000,
            disableOnInteraction: false,
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        // Effect for a nicer transition
        effect: 'fade',
        fadeEffect: {
            crossFade: true
        },
    });

    // Initialize the movie list slider
    const listSwiper = new Swiper('.list-swiper', {
        // How many slides to show
        slidesPerView: 'auto', // Let CSS or slide width determine the count
        // Space between slides
        spaceBetween: 15,
        // Responsive breakpoints
        breakpoints: {
            // when window width is >= 320px
            320: {
                slidesPerView: 2,
                spaceBetween: 10
            },
            // when window width is >= 640px
            640: {
                slidesPerView: 3,
                spaceBetween: 15
            },
            // when window width is >= 1024px
            1024: {
                slidesPerView: 5,
                spaceBetween: 15
            },
             // when window width is >= 1200px
            1200: {
                slidesPerView: 6,
                spaceBetween: 15
            }
        }
    });

});

// === LOGIC CHO DROPDOWN CHỌN THÀNH PHỐ ===
document.addEventListener('DOMContentLoaded', function () {
    const citySelectorWrapper = document.querySelector('.city-selector-wrapper');

    // Kiểm tra xem phần tử có tồn tại không trước khi thêm sự kiện
    if (citySelectorWrapper) {
        const cityButton = citySelectorWrapper.querySelector('.city-selector-button');

        cityButton.addEventListener('click', function (event) {
            // Ngăn sự kiện click lan ra ngoài
            event.stopPropagation(); 
            // Thêm/xóa class 'active' để điều khiển việc ẩn/hiện bằng CSS
            citySelectorWrapper.classList.toggle('active');
        });

        // Thêm sự kiện để đóng dropdown khi bấm ra ngoài
        document.addEventListener('click', function () {
            if (citySelectorWrapper.classList.contains('active')) {
                citySelectorWrapper.classList.remove('active');
            }
        });
    }
});