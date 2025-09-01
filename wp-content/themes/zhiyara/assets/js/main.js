jQuery(document).ready(function($) {
    'use strict';
    
    // Filter functionality
    $('#apply-filters').on('click', function() {
        filterRestaurants();
    });
    
    $('#clear-filters').on('click', function() {
        clearFilters();
    });
    
    // Auto-filter on select change
    $('.filter-control select').on('change', function() {
        filterRestaurants();
    });
    
    function filterRestaurants() {
        var category = $('#category-filter').val();
        var cuisine = $('#cuisine-filter').val();
        var location = $('#location-filter').val();
        var stars = $('#stars-filter').val();
        var sort = $('#sort-filter').val();
        
        // Show loading
        $('#loading').show();
        $('#restaurants-grid').addClass('loading');
        
        $.ajax({
            url: zhiyara_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'filter_restaurants',
                category: category,
                cuisine: cuisine,
                location: location,
                stars: stars,
                sort: sort,
                nonce: zhiyara_ajax.nonce
            },
            success: function(response) {
                $('#restaurants-grid').html(response);
                $('#loading').hide();
                $('#restaurants-grid').removeClass('loading');
                
                // Update URL without page reload
                updateURL(category, cuisine, location, stars, sort);
            },
            error: function() {
                $('#loading').hide();
                $('#restaurants-grid').removeClass('loading');
                $('#restaurants-grid').html('<p class="no-restaurants">خطا در بارگذاری رستوران‌ها. لطفاً دوباره تلاش کنید.</p>');
            }
        });
    }
    
    function clearFilters() {
        $('#category-filter').val('');
        $('#cuisine-filter').val('');
        $('#location-filter').val('');
        $('#stars-filter').val('');
        $('#sort-filter').val('date');
        
        filterRestaurants();
    }
    
    function updateURL(category, cuisine, location, stars, sort) {
        var params = new URLSearchParams();
        
        if (category) params.set('category', category);
        if (cuisine) params.set('cuisine', cuisine);
        if (location) params.set('location', location);
        if (stars) params.set('stars', stars);
        if (sort && sort !== 'date') params.set('sort', sort);
        
        var newURL = window.location.pathname;
        if (params.toString()) {
            newURL += '?' + params.toString();
        }
        
        history.replaceState(null, '', newURL);
    }
    
    // Initialize filters from URL parameters
    function initializeFiltersFromURL() {
        var params = new URLSearchParams(window.location.search);
        
        if (params.get('category')) $('#category-filter').val(params.get('category'));
        if (params.get('cuisine')) $('#cuisine-filter').val(params.get('cuisine'));
        if (params.get('location')) $('#location-filter').val(params.get('location'));
        if (params.get('stars')) $('#stars-filter').val(params.get('stars'));
        if (params.get('sort')) $('#sort-filter').val(params.get('sort'));
    }
    
    // Initialize on page load
    initializeFiltersFromURL();
    
    // Smooth scrolling for anchor links
    $('a[href^="#"]').on('click', function(e) {
        e.preventDefault();
        
        var target = $(this.getAttribute('href'));
        if (target.length) {
            $('html, body').animate({
                scrollTop: target.offset().top - 100
            }, 800);
        }
    });
    
    // Restaurant card hover effects
    $('.restaurant-card').hover(
        function() {
            $(this).addClass('hovered');
        },
        function() {
            $(this).removeClass('hovered');
        }
    );
    
    // Category card click tracking
    $('.category-card').on('click', function() {
        var categoryName = $(this).find('h3').text();
        
        // Track category click (you can integrate with analytics here)
        if (typeof gtag !== 'undefined') {
            gtag('event', 'category_click', {
                'category_name': categoryName
            });
        }
    });
    
    // Search form enhancement
    $('.search-form').on('submit', function(e) {
        var searchTerm = $(this).find('.search-field').val().trim();
        
        if (searchTerm === '') {
            e.preventDefault();
            $(this).find('.search-field').focus();
            return false;
        }
        
        // Track search (you can integrate with analytics here)
        if (typeof gtag !== 'undefined') {
            gtag('event', 'search', {
                'search_term': searchTerm
            });
        }
    });
    
    // Mobile menu toggle (if needed)
    $('.mobile-menu-toggle').on('click', function() {
        $('.main-nav').toggleClass('mobile-open');
        $(this).toggleClass('active');
    });
    
    // Lazy loading for images (basic implementation)
    function lazyLoadImages() {
        var images = $('img[data-src]');
        
        images.each(function() {
            var img = $(this);
            var src = img.attr('data-src');
            
            if (isElementInViewport(img[0])) {
                img.attr('src', src);
                img.removeAttr('data-src');
                img.addClass('loaded');
            }
        });
    }
    
    function isElementInViewport(el) {
        var rect = el.getBoundingClientRect();
        return (
            rect.top >= 0 &&
            rect.left >= 0 &&
            rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
            rect.right <= (window.innerWidth || document.documentElement.clientWidth)
        );
    }
    
    // Initialize lazy loading
    $(window).on('scroll resize', lazyLoadImages);
    lazyLoadImages(); // Initial load
    
    // Back to top button
    var backToTop = $('<button class="back-to-top" title="بازگشت به بالا">↑</button>');
    $('body').append(backToTop);
    
    $(window).on('scroll', function() {
        if ($(this).scrollTop() > 300) {
            backToTop.addClass('visible');
        } else {
            backToTop.removeClass('visible');
        }
    });
    
    backToTop.on('click', function() {
        $('html, body').animate({
            scrollTop: 0
        }, 600);
    });
    
    // Restaurant rating interaction
    $('.restaurant-stars').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        // You can add rating functionality here
        var restaurantId = $(this).closest('.restaurant-card').data('restaurant-id');
        console.log('Rating clicked for restaurant:', restaurantId);
    });
    
    // Print functionality
    $('.print-restaurant').on('click', function() {
        window.print();
    });
    
    // Share functionality
    $('.share-restaurant').on('click', function() {
        if (navigator.share) {
            navigator.share({
                title: document.title,
                url: window.location.href
            });
        } else {
            // Fallback: copy to clipboard
            navigator.clipboard.writeText(window.location.href).then(function() {
                alert('لینک کپی شد!');
            });
        }
    });
});

// Add CSS for back to top button and other dynamic elements
jQuery(document).ready(function($) {
    var dynamicCSS = `
        <style>
        .back-to-top {
            position: fixed;
            bottom: 20px;
            left: 20px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            font-size: 20px;
            cursor: pointer;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            z-index: 1000;
        }
        
        .back-to-top.visible {
            opacity: 1;
            visibility: visible;
        }
        
        .back-to-top:hover {
            background: #5a67d8;
            transform: translateY(-2px);
        }
        
        .restaurant-card.hovered {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.15);
        }
        
        .loading {
            opacity: 0.6;
            pointer-events: none;
        }
        
        .mobile-menu-toggle {
            display: none;
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
        }
        
        @media (max-width: 768px) {
            .mobile-menu-toggle {
                display: block;
            }
            
            .main-nav {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: rgba(102, 126, 234, 0.95);
                padding: 1rem;
            }
            
            .main-nav.mobile-open {
                display: block;
            }
            
            .main-nav ul {
                flex-direction: column;
                gap: 1rem;
            }
        }
        
        img.loaded {
            opacity: 1;
            transition: opacity 0.3s ease;
        }
        
        img[data-src] {
            opacity: 0;
        }
        </style>
    `;
    
    $('head').append(dynamicCSS);
});
