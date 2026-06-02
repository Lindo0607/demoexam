$(document).ready(function() {
    let currentSlide = 0;
    let totalSlides = $('.slide').length;
    let slideInterval;
    
    // Создание точек
    const dotsContainer = $('.dots');
    for (let i = 0; i < totalSlides; i++) {
        dotsContainer.append(`<span class="dot" data-slide="${i}"></span>`);
    }
    
    function showSlide(index) {
        $('.slide').removeClass('active');
        $('.dot').removeClass('active');
        
        if (index >= totalSlides) currentSlide = 0;
        if (index < 0) currentSlide = totalSlides - 1;
        
        $('.slide').eq(currentSlide).addClass('active');
        $('.dot').eq(currentSlide).addClass('active');
    }
    
    function nextSlide() {
        currentSlide++;
        showSlide(currentSlide);
        resetInterval();
    }
    
    function prevSlide() {
        currentSlide--;
        showSlide(currentSlide);
        resetInterval();
    }
    
    function resetInterval() {
        clearInterval(slideInterval);
        slideInterval = setInterval(nextSlide, 3000);
    }
    
    // Event handlers
    $('.next').on('click', nextSlide);
    $('.prev').on('click', prevSlide);
    
    $(document).on('click', '.dot', function() {
        currentSlide = parseInt($(this).data('slide'));
        showSlide(currentSlide);
        resetInterval();
    });
    
    // Start slideshow
    showSlide(0);
    resetInterval();
});