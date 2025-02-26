<?php
/**
 * The header for our theme
 */
// Enhanced dark mode detection
$dark_mode = isset($_COOKIE['darkMode']) && $_COOKIE['darkMode'] === 'true' ? 'dark-theme' : '';
$dark_mode_body = isset($_COOKIE['darkMode']) && $_COOKIE['darkMode'] === 'true' ? 'dark-mode' : '';
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="<?php echo esc_attr($dark_mode); ?>">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>

<body <?php body_class($dark_mode_body); ?>>
<?php wp_body_open(); ?>

    <!-- Enhanced Header with top bar -->
    <div class="site-header__top-bar">
        <div class="container">
            <div class="site-header__top-content">
                <div class="trending">
                    <span class="trending__label"><i class="fas fa-bolt"></i> TRENDING:</span>
                    <div class="trending__items-wrapper">
                        <?php
                        // Get recent popular posts
                        $trending_query = new WP_Query(array(
                            'posts_per_page' => 3,
                            'meta_key' => 'post_views_count',
                            'orderby' => 'meta_value_num',
                            'order' => 'DESC',
                        ));
                        
                        if ($trending_query->have_posts()) :
                            while ($trending_query->have_posts()) : $trending_query->the_post();
                                echo '<a href="' . esc_url(get_permalink()) . '" class="trending__item">' . get_the_title() . '</a>';
                            endwhile;
                        else :
                            // Fallback to recent posts if no trending
                            $recent_posts = wp_get_recent_posts(array('numberposts' => 3, 'post_status' => 'publish'));
                            foreach($recent_posts as $post) {
                                echo '<a href="' . esc_url(get_permalink($post['ID'])) . '" class="trending__item">' . $post['post_title'] . '</a>';
                            }
                        endif;
                        wp_reset_postdata();
                        ?>
                    </div>
                </div>
                <div class="social-bar">
                    <a href="#" target="_blank" class="social-bar__link social-bar__link--facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" target="_blank" class="social-bar__link social-bar__link--twitter"><i class="fab fa-twitter"></i></a>
                    <a href="#" target="_blank" class="social-bar__link social-bar__link--instagram"><i class="fab fa-instagram"></i></a>
                    <a href="#" target="_blank" class="social-bar__link social-bar__link--youtube"><i class="fab fa-youtube"></i></a>
                    <!-- Dark Mode Toggle -->
                    <div class="theme-switcher">
                        <i class="theme-switcher__icon fas fa-sun"></i>
                        <span class="theme-switcher__track">
                            <span class="theme-switcher__thumb"></span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main header with navigation -->
    <header class="site-header">
        <div class="container">
            <div class="site-header__inner">
                <div class="site-header__branding">
                    <?php
                    if (has_custom_logo()) {
                        the_custom_logo();
                    } else {
                        echo '<a href="' . esc_url(home_url('/')) . '" class="site-header__logo-link" rel="home">';
                        echo '<div class="site-header__logo-text"><span>Ctrl</span>Alt<span>Resist</span></div>';
                        echo '</a>';
                    }
                    ?>
                </div>
                <div class="site-header__navigation-container">
                    <div class="site-header__search">
                        <?php get_search_form(); ?>
                    </div>
                    <nav class="main-navigation">
                        <?php
                        wp_nav_menu(array(
                            'theme_location' => 'primary',
                            'menu_id'        => 'primary-menu',
                            'container'      => false,
                            'menu_class'     => 'main-navigation__menu',
                            'fallback_cb'    => false,
                        ));
                        ?>
                    </nav>
                    <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">☰</button>
                </div>
            </div>
        </div>
    </header>

    <?php if (is_front_page() && !is_paged()) : ?>
    <!-- Enhanced Featured news section - Only on homepage -->
    <section class="featured">
        <div class="container">
            <h2 class="featured__title"><i class="fas fa-fire"></i> Breaking News</h2>
            <div class="featured__grid">
                <?php
                // Query for featured posts
                $featured_query = new WP_Query(array(
                    'posts_per_page' => 4,
                    'meta_key'       => 'featured_article',
                    'meta_value'     => true,
                ));
                
                if ($featured_query->have_posts()) :
                    $post_count = 0;
                    while ($featured_query->have_posts()) : $featured_query->the_post();
                        $post_count++;
                        
                        if ($post_count === 1) :
                            // Display main featured article
                            ?>
                            <div class="featured__main">
                                <?php if (has_post_thumbnail()) : ?>
                                    <a href="<?php the_permalink(); ?>" class="featured__main-image-link">
                                        <?php the_post_thumbnail('large', array('class' => 'featured__main-image')); ?>
                                    </a>
                                <?php else : ?>
                                    <a href="<?php the_permalink(); ?>" class="featured__main-image-link">
                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/placeholder-large.jpg" alt="<?php the_title_attribute(); ?>" class="featured__main-image">
                                    </a>
                                <?php endif; ?>
                                <div class="featured__main-content">
                                    <span class="category-tag"><?php the_category(', '); ?></span>
                                    <h2 class="featured__main-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                                    <p class="featured__main-excerpt"><?php echo wp_trim_words(get_the_excerpt(), 20, '...'); ?></p>
                                    <span class="featured__timestamp"><i class="far fa-clock"></i> <?php echo human_time_diff(get_the_time('U'), current_time('timestamp')) . ' ago'; ?></span>
                                </div>
                            </div>
                            <div class="featured__secondary">
                        <?php else : ?>
                            <!-- Side articles -->
                            <div class="featured__item">
                                <?php if (has_post_thumbnail()) : ?>
                                    <a href="<?php the_permalink(); ?>" class="featured__item-image-link">
                                        <?php the_post_thumbnail('thumbnail', array('class' => 'featured__item-image')); ?>
                                    </a>
                                <?php else : ?>
                                    <a href="<?php the_permalink(); ?>" class="featured__item-image-link">
                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/placeholder-small.jpg" alt="<?php the_title_attribute(); ?>" class="featured__item-image">
                                    </a>
                                <?php endif; ?>
                                <div class="featured__item-content">
                                    <span class="category-tag"><?php the_category(', '); ?></span>
                                    <h3 class="featured__item-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                    <span class="featured__timestamp"><i class="far fa-clock"></i> <?php echo human_time_diff(get_the_time('U'), current_time('timestamp')) . ' ago'; ?></span>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endwhile; ?>
                    
                    <?php if ($post_count > 1) : ?>
                        </div><!-- close featured__secondary -->
                    <?php endif; ?>
                    
                <?php else : ?>
                    <div class="featured__empty">
                        <div class="featured__empty-message">
                            <i class="fas fa-newspaper"></i>
                            <h3><?php esc_html_e('No featured posts found.', 'ctrlaltresist'); ?></h3>
                            <p><?php esc_html_e('Mark posts as featured to display them here.', 'ctrlaltresist'); ?></p>
                            <a href="<?php echo admin_url('edit.php'); ?>" class="admin-link"><?php esc_html_e('Manage Posts', 'ctrlaltresist'); ?></a>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php wp_reset_postdata(); ?>
            </div>
        </div>
    </section>
    <?php endif; ?>