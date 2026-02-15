/**
 * Homepage Interactive Features
 * Handles offers, vehicle categories, and features sections
 */

$(document).ready(function() {
    
    // ===== OFFER CARDS ANIMATION =====
    initializeOfferCards();
    
    // ===== VEHICLE CATEGORY ANIMATION =====
    initializeVehicleCategories();
    
    // ===== FEATURE CARDS ANIMATION =====
    initializeFeatureCards();
    
    // ===== SMOOTH SCROLL TO SECTIONS =====
    initializeSmoothScroll();
    
    // ===== BUTTON CLICK HANDLERS =====
    initializeButtonHandlers();
});

/**
 * Initialize Offer Cards with animations
 */
function initializeOfferCards() {
    $('.offer-card').each(function(index) {
        $(this).css('animation-delay', (index * 0.1) + 's');
    });
    
    // Add click handler for offer cards
    $('.offer-card').on('click', function() {
        window.location.href = 'booking.php';
    });
    
    // Hover effect for offer badges
    $('.offer-badge').on('mouseenter', function() {
        $(this).css({
            'transform': 'scale(1.05)',
            'transition': 'transform 0.2s ease'
        });
    }).on('mouseleave', function() {
        $(this).css('transform', 'scale(1)');
    });
}

/**
 * Initialize Vehicle Category Cards
 */
function initializeVehicleCategories() {
    $('.vehicle-category-card').each(function(index) {
        $(this).css('animation-delay', (index * 0.1) + 's');
    });
    
    // Add hover effects
    $('.vehicle-category-card').on('mouseenter', function() {
        $(this).addClass('shadow-lg');
    }).on('mouseleave', function() {
        $(this).removeClass('shadow-lg');
    });
    
    // Add click to see-more links
    $('.see-more-link').on('click', function(e) {
        e.preventDefault();
        // You can add category filtering here
        window.location.href = 'search.php';
    });
}

/**
 * Initialize Feature Cards
 */
function initializeFeatureCards() {
    $('.feature-benefit-card').each(function(index) {
        $(this).css('animation-delay', (index * 0.1) + 's');
    });
    
    // Button click handlers for feature cards
    $('.feature-benefit-card button').on('click', function() {
        var buttonText = $(this).text();
        
        if (buttonText.includes('Privilege')) {
            window.location.href = 'booking.php?type=privilege';
        } else if (buttonText.includes('Book')) {
            window.location.href = 'booking.php';
        } else if (buttonText.includes('Check')) {
            window.location.href = 'user-dashboard.php';
        }
    });
}

/**
 * Smooth scroll when clicking navigation links
 */
function initializeSmoothScroll() {
    $('a[href^="#"]').on('click', function(e) {
        e.preventDefault();
        
        var target = $(this).attr('href');
        if ($(target).length) {
            $('html, body').stop().animate({
                scrollTop: $(target).offset().top - 100
            }, 800);
        }
    });
}

/**
 * Initialize button click handlers
 */
function initializeButtonHandlers() {
    // Download App Button
    $('button:contains("Download Europcar APP")').on('click', function() {
        alert('Redirecting to app download page...');
        // window.location.href = 'https://www.europcar.com/en/apps';
    });
}

/**
 * Add intersection observer for scroll animations
 */
function initScrollAnimations() {
    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    $(entry.target).addClass('animate-in');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });
        
        $('.offer-card, .vehicle-category-card, .feature-benefit-card').each(function() {
            observer.observe(this);
        });
    }
}

// Call scroll animations after DOM is ready
$(document).ready(function() {
    setTimeout(initScrollAnimations, 100);
});

/**
 * Small hero car animation. Adds classes to trigger CSS keyframes.
 */
function initializeCarAnimation() {
    var $car = $('.animated-car');
    if (!$car.length) return;

    // Play initial drive-in after a short delay
    setTimeout(function() {
        $car.removeClass('animate-sweep').addClass('animate-drive');
        // remove animate-drive after animation ends so it can be retriggered
        setTimeout(function() { $car.removeClass('animate-drive'); }, 1100);
    }, 600);

    // Trigger a faster sweep when user clicks hero primary button
    $('.hero-section .btn-primary').on('click', function(e) {
        // allow click behavior (navigation) but trigger a visible sweep
        $car.removeClass('animate-drive').addClass('animate-sweep');
        setTimeout(function() { $car.removeClass('animate-sweep'); }, 1700);
    });
}

/**
 * Utility function to handle category filtering
 */
function filterByCategory(category) {
    // This can be extended to filter vehicles by category
    console.log('Filtering by category: ' + category);
    window.location.href = 'search.php?category=' + category;
}

/**
 * Quick booking via offer cards
 */
function quickBooking(offerType) {
    console.log('Quick booking for: ' + offerType);
    window.location.href = 'booking.php?offer=' + offerType;
}
