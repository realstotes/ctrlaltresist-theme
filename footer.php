<?php
/**
 * The template for displaying the footer
 */
?>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-grid">
                <div class="footer-column">
                    <h3>Categories</h3>
                    <ul>
                        <?php
                        $categories = get_categories(array(
                            'orderby' => 'name',
                            'order'   => 'ASC',
                            'number'  => 6
                        ));
                        
                        foreach ($categories as $category) {
                            echo '<li><a href="' . esc_url(get_category_link($category->term_id)) . '">' . esc_html($category->name) . '</a></li>';
                        }
                        ?>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>About Us</h3>
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'footer',
                        'menu_id'        => 'footer-about-menu',
                        'container'      => false,
                        'fallback_cb'    => false,
                    ));
                    ?>
                </div>
                <div class="footer-column">
                    <h3>Legal</h3>
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'footer-legal',
                        'menu_id'        => 'footer-legal-menu',
                        'container'      => false,
                        'fallback_cb'    => false,
                    ));
                    ?>
                </div>
                <div class="footer-column">
                    <h3>Connect</h3>
                    <ul class="social-links">
                        <li><a href="#" target="_blank"><i class="fab fa-facebook"></i> Facebook</a></li>
                        <li><a href="#" target="_blank"><i class="fab fa-twitter"></i> Twitter</a></li>
                        <li><a href="#" target="_blank"><i class="fab fa-instagram"></i> Instagram</a></li>
                        <li><a href="#" target="_blank"><i class="fab fa-linkedin"></i> LinkedIn</a></li>
                        <li><a href="<?php echo esc_url(get_feed_link()); ?>"><i class="fas fa-rss"></i> RSS Feed</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <?php wp_footer(); ?>
    <script>
        // Simple mobile menu toggle
        document.addEventListener('DOMContentLoaded', function() {
            const menuToggle = document.querySelector('.menu-toggle');
            const nav = document.querySelector('nav');
            
            menuToggle.addEventListener('click', function() {
                nav.classList.toggle('active');
            });
        });
    </script>
</body>
</html>