        document.addEventListener('DOMContentLoaded', function () {
            // Initialize all product sliders
            const productSliders = document.querySelectorAll('.products-slider');

            productSliders.forEach(slider => {
                const container = slider.querySelector('.slider-container');
                const prevBtn = slider.querySelector('.slider-prev');
                const nextBtn = slider.querySelector('.slider-next');
                const productCards = slider.querySelectorAll('.product-card');

                if (productCards.length === 0) return;

                const cardWidth = productCards[0].offsetWidth + 20; // card width + gap
                const visibleCards = Math.floor(container.clientWidth / cardWidth);
                let scrollPosition = 0;
                const maxScroll = container.scrollWidth - container.clientWidth;

                // Update button states (now using display instead of just "disabled")
                function updateButtons() {
                    if (scrollPosition <= 0) {
                        prevBtn.style.display = "none";
                    } else {
                        prevBtn.style.display = "";
                    }
                    if (scrollPosition >= maxScroll - 1) { // -1 to fix floating point errors
                        nextBtn.style.display = "none";
                    } else {
                        nextBtn.style.display = "";
                    }
                }

                // Scroll to position
                function scrollTo(position) {
                    scrollPosition = position;
                    container.scrollTo({
                        left: scrollPosition,
                        behavior: 'smooth'
                    });
                    updateButtons();
                }

                // Previous button click
                prevBtn.addEventListener('click', () => {
                    if (scrollPosition <= 0) return;
                    const newPosition = Math.max(scrollPosition - (cardWidth * visibleCards), 0);
                    scrollTo(newPosition);
                });

                // Next button click
                nextBtn.addEventListener('click', () => {
                    if (scrollPosition >= maxScroll) return;
                    const newPosition = Math.min(scrollPosition + (cardWidth * visibleCards), maxScroll);
                    scrollTo(newPosition);
                });

                // Handle scroll events
                container.addEventListener('scroll', () => {
                    scrollPosition = container.scrollLeft;
                    updateButtons();
                });

                // Initialize
                updateButtons();

                // Handle window resize
                let resizeTimer;
                window.addEventListener('resize', () => {
                    clearTimeout(resizeTimer);
                    resizeTimer = setTimeout(() => {
                        scrollPosition = container.scrollLeft;
                        updateButtons();
                    }, 250);
                });
            });

            // Auto-hide buttons when not hovering
            const sliders = document.querySelectorAll('.products-slider');
            sliders.forEach(slider => {
                let hoverTimer;

                slider.addEventListener('mouseenter', () => {
                    clearTimeout(hoverTimer);
                    const buttons = slider.querySelectorAll('.slider-nav-btn');
                    buttons.forEach(btn => {
                        btn.style.opacity = '1';
                        btn.style.visibility = 'visible';
                    });
                });

                slider.addEventListener('mouseleave', () => {
                    const buttons = slider.querySelectorAll('.slider-nav-btn:not(:hover)');
                    hoverTimer = setTimeout(() => {
                        buttons.forEach(btn => {
                            btn.style.opacity = '0';
                            btn.style.visibility = 'hidden';
                        });
                    }, 500);
                });
            });

            // News slider with auto-scroll
            const newsSlider = document.querySelector('.news-slider');
            const newsPrevBtn = document.querySelector('.news-slider-prev');
            const newsNextBtn = document.querySelector('.news-slider-next');
            const newsSlides = document.querySelectorAll('.news-slide');
            let currentNewsIndex = 0;
            const slideCount = newsSlides.length;
            let autoScrollInterval;

            function updateNewsSlider() {
                const slideWidth = newsSlides[0].offsetWidth;
                newsSlider.scrollTo({
                    left: currentNewsIndex * slideWidth,
                    behavior: 'smooth'
                });

                newsPrevBtn.style.display = currentNewsIndex === 0 ? 'none' : 'flex';
                newsNextBtn.style.display = currentNewsIndex >= slideCount - 1 ? 'none' : 'flex';
            }

            function nextSlide() {
                if (currentNewsIndex < slideCount - 1) {
                    currentNewsIndex++;
                } else {
                    currentNewsIndex = 0;
                }
                updateNewsSlider();
            }

            // Auto-scroll every 10 seconds
            function startAutoScroll() {
                autoScrollInterval = setInterval(nextSlide, 10000);
            }

            function stopAutoScroll() {
                clearInterval(autoScrollInterval);
            }

            newsPrevBtn.addEventListener('click', () => {
                stopAutoScroll();
                if (currentNewsIndex > 0) {
                    currentNewsIndex--;
                    updateNewsSlider();
                }
                startAutoScroll();
            });

            newsNextBtn.addEventListener('click', () => {
                stopAutoScroll();
                if (currentNewsIndex < slideCount - 1) {
                    currentNewsIndex++;
                    updateNewsSlider();
                }
                startAutoScroll();
            });

            // Initialize
            updateNewsSlider();
            startAutoScroll();

            // Pause auto-scroll on hover
            newsSlider.addEventListener('mouseenter', stopAutoScroll);
            newsSlider.addEventListener('mouseleave', startAutoScroll);

            // Handle window resize
            window.addEventListener('resize', updateNewsSlider);
        });

        document.addEventListener('DOMContentLoaded', function () {
    // News slider with auto-scroll
    const newsSlider = document.querySelector('.news-slider');
    const newsPrevBtn = document.querySelector('.news-slider-prev');
    const newsNextBtn = document.querySelector('.news-slider-next');
    const newsSlides = document.querySelectorAll('.news-slide');
    let currentNewsIndex = 0;
    const slideCount = newsSlides.length;
    let autoScrollInterval;

    if (!newsSlider || !newsPrevBtn || !newsNextBtn || slideCount === 0) return; // Check DOM

    function updateNewsSlider() {
        const slideWidth = newsSlides[0].offsetWidth;
        newsSlider.scrollTo({
            left: currentNewsIndex * slideWidth,
            behavior: 'smooth'
        });

        // Show/hide prev/next buttons
        newsPrevBtn.style.display = currentNewsIndex === 0 ? 'none' : 'flex';
        newsNextBtn.style.display = currentNewsIndex >= slideCount - 1 ? 'none' : 'flex';
    }

    function nextSlide() {
        if (currentNewsIndex < slideCount - 1) {
            currentNewsIndex++;
        }
        updateNewsSlider();
    }

    function prevSlide() {
        if (currentNewsIndex > 0) {
            currentNewsIndex--;
        }
        updateNewsSlider();
    }

    // Auto-scroll every 10 seconds
    function startAutoScroll() {
        autoScrollInterval = setInterval(() => {
            if(currentNewsIndex < slideCount - 1) {
                currentNewsIndex++;
            } else {
                currentNewsIndex = 0;
            }
            updateNewsSlider();
        }, 10000);
    }

    function stopAutoScroll() {
        clearInterval(autoScrollInterval);
    }

    newsPrevBtn.addEventListener('click', () => {
        stopAutoScroll();
        prevSlide();
        startAutoScroll();
    });

    newsNextBtn.addEventListener('click', () => {
        stopAutoScroll();
        nextSlide();
        startAutoScroll();
    });

    // Initialize
    updateNewsSlider();
    startAutoScroll();

    // Pause auto-scroll on hover
    newsSlider.addEventListener('mouseenter', stopAutoScroll);
    newsSlider.addEventListener('mouseleave', startAutoScroll);

    // Handle window resize
    window.addEventListener('resize', updateNewsSlider);
});

$(document).ready(function () {
    // Khi bấm nút "Xem thêm"
    $(document).on('click', '#btn-load-more', function (e) {
        e.preventDefault();
        let nextPage = $(this).data('next-page');
        let url = "{{ route('products.loadMore') }}?page=" + nextPage;
        let filterData = $('#filter-form').serialize(); // nếu có form filter

        $.ajax({
            url: url,
            type: "GET",
            data: filterData, // gửi cả filter nếu có
            beforeSend: function () {
                $('#btn-load-more').prop('disabled', true).text('Đang tải...');
            },
            success: function (response) {
                // Thêm sản phẩm mới vào cuối list
                $('.product-list-container').append($(response).find('.product-card'));
                // Kiểm tra còn nút "Xem thêm" không thì thay thế lại
                let newBtn = $(response).find('#btn-load-more');
                if (newBtn.length) {
                    $('#btn-load-more').replaceWith(newBtn);
                } else {
                    $('#btn-load-more').remove();
                }
            },
            complete: function () {
                $('#btn-load-more').prop('disabled', false).text('Xem thêm');
            }
        });
    });

    // Khi thay đổi filter (nếu có)
    $('#filter-form input, #filter-form select').change(function () {
        fetchProducts();
    });

    function fetchProducts() {
        let url = "{{ route('products.loadMore') }}";
        let filterData = $('#filter-form').serialize();
        $.ajax({
            url: url,
            type: "GET",
            data: filterData,
            beforeSend: function () {
                $('.product-list-container').html('<p class="text-center">Đang tải...</p>');
            },
            success: function (response) {
                $('#product-list-container').html(response);
            }
        });
    }
});