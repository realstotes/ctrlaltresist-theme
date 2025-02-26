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
        
        // Enhanced Dark Mode Toggle implementation
        // Check for saved dark mode preference
        const savedMode = localStorage.getItem('darkMode');
        if (savedMode === 'true') {
            $('html').addClass('dark-theme');
            $('body').addClass('dark-mode');
            $('.dark-mode-toggle .toggle-icon').removeClass('fa-sun').addClass('fa-moon');
            $('.dark-mode-toggle .toggle-thumb').css('left', '25px');
        }
        
        // Dark mode toggle
        $('.dark-mode-toggle').on('click', function() {
            $('html').toggleClass('dark-theme');
            $('body').toggleClass('dark-mode');
            
            // Update icon and toggle position
            if ($('html').hasClass('dark-theme')) {
                $('.dark-mode-toggle .toggle-icon').removeClass('fa-sun').addClass('fa-moon');
                $('.dark-mode-toggle .toggle-thumb').css('left', '25px');
                localStorage.setItem('darkMode', 'true');
            } else {
                $('.dark-mode-toggle .toggle-icon').removeClass('fa-moon').addClass('fa-sun');
                $('.dark-mode-toggle .toggle-thumb').css('left', '3px');
                localStorage.setItem('darkMode', 'false');
            }
        });
    });
    
})(jQuery);