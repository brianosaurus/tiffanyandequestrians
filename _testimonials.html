<?php
require_once('wp-load.php');
$testimonials = get_tiffany_testimonials();
?>
<!-- Testimonials Section -->
<section class="py-12 md:py-20 bg-brand-gray">
    <div class="container mx-auto px-6 lg:px-8 text-center">
        <h2 class="text-4xl md:text-5xl font-marcellus text-gray-800 mb-2 text-shadow">Testimonials</h2>
        <div class="w-20 h-1 bg-brand-teal mx-auto mb-12"></div>
        
        <?php if (!empty($testimonials)): ?>
            <div class="relative">
                <!-- Previous Button -->
                <button class="absolute left-0 top-1/2 transform -translate-y-1/2 -translate-x-4 z-10 bg-white rounded-full p-2 shadow-lg text-gray-600 hover:text-gray-800 focus:outline-none hidden md:block testimonial-prev">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>

                <!-- Testimonials Container -->
                <div class="overflow-hidden">
                    <div class="testimonials-slider flex transition-transform duration-300 ease-in-out">
                        <?php foreach ($testimonials as $testimonial): ?>
                            <div class="flex-none w-full md:w-1/2 lg:w-1/3 px-4">
                                <div class="bg-white p-8 rounded-lg shadow-lg h-full">
                                    <div class="flex justify-center mb-4 text-yellow-400">
                                        <?php
                                        // Output stars based on rating
                                        $rating = intval($testimonial['rating']);
                                        for ($i = 1; $i <= 5; $i++):
                                            if ($i <= $rating):
                                        ?>
                                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                        <?php else: ?>
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                                        <?php endif;
                                        endfor;
                                        ?>
                                    </div>
                                    <p class="text-gray-600 italic">"<?php echo esc_html($testimonial['text']); ?>"</p>
                                    <p class="font-marcellus text-lg text-gray-800 font-semibold mt-6">- <?php echo esc_html($testimonial['author']); ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Next Button -->
                <button class="absolute right-0 top-1/2 transform -translate-y-1/2 translate-x-4 z-10 bg-white rounded-full p-2 shadow-lg text-gray-600 hover:text-gray-800 focus:outline-none hidden md:block testimonial-next">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>

                <!-- Dots Navigation -->
                <div class="flex justify-center mt-8 gap-2 testimonial-dots">
                    <?php 
                    $totalSlides = ceil(count($testimonials) / 3);
                    for ($i = 0; $i < $totalSlides; $i++): 
                    ?>
                        <button class="w-2 h-2 rounded-full bg-gray-300 hover:bg-gray-400 focus:outline-none <?php echo $i === 0 ? 'bg-gray-600' : ''; ?>" 
                                data-slide="<?php echo $i; ?>">
                        </button>
                    <?php endfor; ?>
                </div>
            </div>
        <?php else: ?>
            <div class="text-center text-gray-600 italic py-8">
                No testimonials available yet.
            </div>
        <?php endif; ?>
    </div>
</section>

<style>
.testimonials-slider {
    -webkit-overflow-scrolling: touch;
    scroll-behavior: smooth;
}

@media (max-width: 768px) {
    .testimonials-slider {
        scroll-snap-type: x mandatory;
    }
    .testimonials-slider > div {
        scroll-snap-align: start;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const slider = document.querySelector('.testimonials-slider');
    const prevButton = document.querySelector('.testimonial-prev');
    const nextButton = document.querySelector('.testimonial-next');
    const dots = document.querySelectorAll('.testimonial-dots button');
    let currentSlide = 0;
    const slidesPerView = window.innerWidth >= 1024 ? 3 : window.innerWidth >= 768 ? 2 : 1;
    const totalSlides = Math.ceil(<?php echo count($testimonials); ?> / slidesPerView);

    function updateSliderPosition() {
        const slideWidth = slider.clientWidth / slidesPerView;
        slider.style.transform = `translateX(-${currentSlide * slideWidth}px)`;
        
        // Update dots
        dots.forEach((dot, index) => {
            dot.classList.toggle('bg-gray-600', index === currentSlide);
            dot.classList.toggle('bg-gray-300', index !== currentSlide);
        });

        // Show/hide navigation buttons
        if (prevButton && nextButton) {
            prevButton.style.display = currentSlide === 0 ? 'none' : 'block';
            nextButton.style.display = currentSlide === totalSlides - 1 ? 'none' : 'block';
        }
    }

    function goToSlide(slideIndex) {
        currentSlide = Math.max(0, Math.min(slideIndex, totalSlides - 1));
        updateSliderPosition();
    }

    if (prevButton) {
        prevButton.addEventListener('click', () => {
            goToSlide(currentSlide - 1);
        });
    }

    if (nextButton) {
        nextButton.addEventListener('click', () => {
            goToSlide(currentSlide + 1);
        });
    }

    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            goToSlide(index);
        });
    });

    // Touch/swipe support for mobile
    let touchStartX = 0;
    let touchEndX = 0;

    slider.addEventListener('touchstart', e => {
        touchStartX = e.changedTouches[0].screenX;
    }, { passive: true });

    slider.addEventListener('touchend', e => {
        touchEndX = e.changedTouches[0].screenX;
        const diff = touchStartX - touchEndX;
        
        if (Math.abs(diff) > 50) { // Minimum swipe distance
            if (diff > 0 && currentSlide < totalSlides - 1) {
                goToSlide(currentSlide + 1);
            } else if (diff < 0 && currentSlide > 0) {
                goToSlide(currentSlide - 1);
            }
        }
    }, { passive: true });

    // Handle window resize
    let resizeTimer;
    window.addEventListener('resize', () => {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(() => {
            const newSlidesPerView = window.innerWidth >= 1024 ? 3 : window.innerWidth >= 768 ? 2 : 1;
            if (newSlidesPerView !== slidesPerView) {
                location.reload(); // Refresh to recalculate layout
            }
        }, 250);
    });

    // Initialize
    updateSliderPosition();
});
</script>