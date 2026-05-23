$(document).ready(function() {
    var $carousel = $('#heroCarousel');
    var $track = $carousel.find('.carousel-track');
    var $slides = $carousel.find('.carousel-slide');
    var $dots = $carousel.find('.carousel-dot');
    var $progress = $carousel.find('.carousel-progress');
    var $prevBtn = $carousel.find('.carousel-btn-prev');
    var $nextBtn = $carousel.find('.carousel-btn-next');
    var currentIndex = 0;
    var totalSlides = $slides.length;
    var autoPlayInterval = 5000;
    var timer = null;
    var isAnimating = false;
    var startX = 0;
    var endX = 0;

    function goToSlide(index) {
        if (isAnimating) return;
        isAnimating = true;

        if (index < 0) index = totalSlides - 1;
        if (index >= totalSlides) index = 0;

        $slides.removeClass('active');
        $dots.removeClass('active');

        $track.css('transform', 'translateX(-' + (index * 100) + '%)');

        currentIndex = index;

        setTimeout(function() {
            $slides.eq(currentIndex).addClass('active');
            $dots.eq(currentIndex).addClass('active');
            isAnimating = false;
        }, 100);

        resetProgress();
    }

    function nextSlide() {
        goToSlide(currentIndex + 1);
    }

    function prevSlide() {
        goToSlide(currentIndex - 1);
    }

    function resetProgress() {
        $progress.stop(true).css('width', '0%');
        $progress.animate({ width: '100%' }, autoPlayInterval, 'linear');
    }

    function startAutoPlay() {
        stopAutoPlay();
        timer = setInterval(function() {
            nextSlide();
        }, autoPlayInterval);
        resetProgress();
    }

    function stopAutoPlay() {
        if (timer) {
            clearInterval(timer);
            timer = null;
        }
        $progress.stop(true);
    }

    $nextBtn.on('click', function() {
        nextSlide();
        startAutoPlay();
    });

    $prevBtn.on('click', function() {
        prevSlide();
        startAutoPlay();
    });

    $dots.each(function(i) {
        $(this).on('click', function() {
            if (i !== currentIndex) {
                goToSlide(i);
                startAutoPlay();
            }
        });
    });

    $carousel.on('mouseenter', function() {
        stopAutoPlay();
    });

    $carousel.on('mouseleave', function() {
        startAutoPlay();
    });

    $carousel.on('touchstart', function(e) {
        startX = e.originalEvent.touches[0].clientX;
        stopAutoPlay();
    });

    $carousel.on('touchmove', function(e) {
        endX = e.originalEvent.touches[0].clientX;
    });

    $carousel.on('touchend', function() {
        var diff = startX - endX;
        if (Math.abs(diff) > 50) {
            if (diff > 0) {
                nextSlide();
            } else {
                prevSlide();
            }
        }
        startAutoPlay();
    });

    $(document).on('keydown', function(e) {
        if (e.key === 'ArrowRight') {
            nextSlide();
            startAutoPlay();
        } else if (e.key === 'ArrowLeft') {
            prevSlide();
            startAutoPlay();
        }
    });

    $slides.eq(0).addClass('active');
    $dots.eq(0).addClass('active');
    startAutoPlay();
});
