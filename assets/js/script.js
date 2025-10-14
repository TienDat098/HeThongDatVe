
document.addEventListener('DOMContentLoaded', function () {

    
    if (typeof Swiper !== 'undefined') {
        const topSwiper = new Swiper('.top-swiper', {
            loop: true,
            autoplay: {
                delay: 4000,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            effect: 'fade',
            fadeEffect: {
                crossFade: true
            },
        });

        const listSwiper = new Swiper('.list-swiper', {
            slidesPerView: 'auto',
            spaceBetween: 15,
            breakpoints: {
                320: { slidesPerView: 2, spaceBetween: 10 },
                640: { slidesPerView: 3, spaceBetween: 15 },
                1024: { slidesPerView: 5, spaceBetween: 15 },
                1200: { slidesPerView: 6, spaceBetween: 15 }
            }
        });
    }

    const citySelectorWrapper = document.querySelector('.city-selector-wrapper');

    if (citySelectorWrapper) {
        const cityButton = citySelectorWrapper.querySelector('.city-selector-button');

        cityButton.addEventListener('click', function (event) {
            event.stopPropagation();
            citySelectorWrapper.classList.toggle('active');
        });

        document.addEventListener('click', function () {
            if (citySelectorWrapper.classList.contains('active')) {
                citySelectorWrapper.classList.remove('active');
            }
        });
    }

    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('thanh_pho')) {
        const rapMenu = document.querySelector('.main-nav .mega-menu');
        if (rapMenu) {
            rapMenu.classList.add('is-open');
            rapMenu.addEventListener('mouseleave', function() {
                rapMenu.classList.remove('is-open');
            });
        }
    }

});