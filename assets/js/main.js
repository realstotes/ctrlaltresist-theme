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
        // Check for saved dark mode preference (first from localStorage, fallback to cookie)
        const savedMode = localStorage.getItem('darkMode');
        const cookieMode = document.cookie.replace(/(?:(?:^|.*;\s*)darkMode\s*\=\s*([^;]*).*$)|^.*$/, "$1");
        
        // Synchronize cookie and localStorage
        if (savedMode === 'true' || cookieMode === 'true') {
            enableDarkMode();
        } else {
            disableDarkMode();
        }
        
        // Dark mode toggle
        $('.dark-mode-toggle').on('click', function() {
            if ($('html').hasClass('dark-theme')) {
                disableDarkMode();
            } else {
                enableDarkMode();
            }
        });
        
        // Helper functions for dark mode
        function enableDarkMode() {
            $('html').addClass('dark-theme');
            $('body').addClass('dark-mode');
            $('.dark-mode-toggle .toggle-icon').removeClass('fa-sun').addClass('fa-moon');
            $('.dark-mode-toggle .toggle-thumb').css('left', '25px');
            
            // Save preference in both localStorage and cookie
            localStorage.setItem('darkMode', 'true');
            document.cookie = 'darkMode=true; path=/; max-age=31536000'; // 1 year expiry
        }
        
        function disableDarkMode() {
            $('html').removeClass('dark-theme');
            $('body').removeClass('dark-mode');
            $('.dark-mode-toggle .toggle-icon').removeClass('fa-moon').addClass('fa-sun');
            $('.dark-mode-toggle .toggle-thumb').css('left', '3px');
            
            // Save preference in both localStorage and cookie
            localStorage.setItem('darkMode', 'false');
            document.cookie = 'darkMode=false; path=/; max-age=31536000'; // 1 year expiry
        }
    });
    
})(jQuery);