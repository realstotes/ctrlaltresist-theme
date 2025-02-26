/**
 * CtrlAltResist Theme Main JavaScript
 */

(function($) {
    'use strict';
    
    // Document ready function
    $(document).ready(function() {
        // Mobile menu toggle
        $('.menu-toggle').on('click', function() {
            $('nav').toggleClass('active');
        });
        
        // Close mobile menu when clicking outside
        $(document).on('click', function(event) {
            if ($(window).width() <= 768 && 
                !$('nav').has(event.target).length && 
                !$('.menu-toggle').is(event.target) &&
                $('nav').hasClass('active')) {
                $('nav').removeClass('active');
            }
        });
        
        // Smooth scroll for anchor links
        $('a[href^="#"]').on('click', function(event) {
            var target = $(this.getAttribute('href'));
            if (target.length) {
                event.preventDefault();
                $('html, body').stop().animate({
                    scrollTop: target.offset().top - 100
                }, 800);
            }
        });
    });
    
})(jQuery);

/**
 * Dark Mode Toggle JavaScript - Add to main.js
 */

(function($) {
    'use strict';
    
    // Document ready function
    $(document).ready(function() {
        // Check for saved dark mode preference
        const savedMode = localStorage.getItem('darkMode');
        if (savedMode === 'true') {
            $('body').addClass('dark-mode');
            $('.dark-mode-toggle .toggle-icon').removeClass('fa-sun').addClass('fa-moon');
        }
        
        // Dark mode toggle
        $('.dark-mode-toggle').on('click', function() {
            $('body').toggleClass('dark-mode');
            
            // Update icon
            if ($('body').hasClass('dark-mode')) {
                $('.dark-mode-toggle .toggle-icon').removeClass('fa-sun').addClass('fa-moon');
                localStorage.setItem('darkMode', 'true');
            } else {
                $('.dark-mode-toggle .toggle-icon').removeClass('fa-moon').addClass('fa-sun');
                localStorage.setItem('darkMode', 'false');
            }
        });
        
        // Mobile menu toggle
        $('.menu-toggle').on('click', function() {
            $('nav').toggleClass('active');
        });
        
        // Close mobile menu when clicking outside
        $(document).on('click', function(event) {
            if ($(window).width() <= 768 && 
                !$('nav').has(event.target).length && 
                !$('.menu-toggle').is(event.target) &&
                $('nav').hasClass('active')) {
                $('nav').removeClass('active');
            }
        });
        
        // Smooth scroll for anchor links
        $('a[href^="#"]').on('click', function(event) {
            var target = $(this.getAttribute('href'));
            if (target.length) {
                event.preventDefault();
                $('html, body').stop().animate({
                    scrollTop: target.offset().top - 100
                }, 800);
            }
        });
    });
    
})(jQuery);