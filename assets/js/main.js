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
            $('.menu-overlay').toggleClass('active');
        });
        
        // Close mobile menu when clicking outside
        $(document).on('click', function(event) {
            if ($(window).width() <= 768 && 
                !$('nav').has(event.target).length && 
                !$('.menu-toggle').is(event.target) &&
                $('nav').hasClass('active')) {
                $('nav').removeClass('active');
                $('.menu-overlay').removeClass('active');
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
        $('.theme-switcher').on('click', function() {
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
            $('.theme-switcher__icon').removeClass('fa-sun').addClass('fa-moon');
            $('.theme-switcher__thumb').css('left', '21px');
            
            // Save preference in both localStorage and cookie
            localStorage.setItem('darkMode', 'true');
            document.cookie = 'darkMode=true; path=/; max-age=31536000'; // 1 year expiry
        }
        
        function disableDarkMode() {
            $('html').removeClass('dark-theme');
            $('body').removeClass('dark-mode');
            $('.theme-switcher__icon').removeClass('fa-moon').addClass('fa-sun');
            $('.theme-switcher__thumb').css('left', '3px');
            
            // Save preference in both localStorage and cookie
            localStorage.setItem('darkMode', 'false');
            document.cookie = 'darkMode=false; path=/; max-age=31536000'; // 1 year expiry
        }
        
        // Mobile submenu toggles
        $('.main-navigation__menu li.menu-item-has-children > a').on('click', function(e) {
            if ($(window).width() <= 991) {
                e.preventDefault();
                $(this).parent().toggleClass('active');
                $(this).siblings('.sub-menu').slideToggle();
            }
        });
    });
    
})(jQuery);