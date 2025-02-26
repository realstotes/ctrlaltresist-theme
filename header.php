<?php
/**
 * The header for our theme
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

    <!-- Enhanced Header with top bar -->
    <div class="top-bar">
        <div class="container">
            <div class="top-bar-content">
                <div class="trending-topics">
                    <span class="trending-label"><i class="fas fa-bolt"></i> TRENDING:</span>
                    <div class="trending-items">
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
                                echo '<a href="' . esc_url(get_permalink()) . '">' . get_the_title() . '</a>';
                            endwhile;
                        else :
                            // Fallback to recent posts if no trending
                            $recent_posts = wp_get_recent_posts(array('numberposts' => 3, 'post_status' => 'publish'));
                            foreach($recent_posts as $post) {
                                echo '<a href="' . esc_url(get_permalink($post['ID'])) . '">' . $post['post_title'] . '</a>';
                            }
                        endif;
                        wp_reset_postdata();
                        ?>
                    </div>
                </div>
                <div class="top-social">
                    <a href="#" target="_blank"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" target="_blank"><i class="fab fa-twitter"></i></a>
                    <a href="#" target="_blank"><i class="fab fa-instagram"></i></a>
                    <a href="#" target="_blank"><i class="fab fa-youtube"></i></a>
                    <!-- Dark Mode Toggle -->
                    <div class="dark-mode-toggle">
                        <i class="toggle-icon fas fa-sun"></i>
                        <span class="toggle-track">
                            <span class="toggle-thumb"></span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main header with navigation -->
    <header>
        <div class="container header-container">
            <div class="logo">
                <?php
                if (has_custom_logo()) {
                    the_custom_logo();
                } else {
                    echo '<a href="' . esc_url(home_url('/')) . '" rel="home">';
                    echo '<div class="logo-text"><span>Ctrl</span>Alt<span>Resist</span></div>';
                    echo '</a>';
                }
                ?>
            </div>
            <div class="header-right">
                <div class="header-search">
                    <?php get_search_form(); ?>
                </div>
                <nav>
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'primary',
                        'menu_id'        => 'primary-menu',
                        'container'      => false,
                        'menu_class'     => '',
                        'fallback_cb'    => false,
                    ));
                    ?>
                </nav>
                <div class="menu-toggle">☰</div>
            </div>
        </div>
    </header>

    <?php if (is_front_page() && !is_paged()) : ?>
    <!-- Enhanced Featured news section - Only on homepage -->
    <section class="featured-news">
        <div class="container">
            <h2 class="featured-title"><i class="fas fa-fire"></i> Breaking News</h2>
            <div class="featured-grid">
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
                            <div class="main-article">
                                <?php if (has_post_thumbnail()) : ?>
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail('large'); ?>
                                    </a>
                                <?php else : ?>
                                    <a href="<?php the_permalink(); ?>">
                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/placeholder-large.jpg" alt="<?php the_title_attribute(); ?>">
                                    </a>
                                <?php endif; ?>
                                <div class="main-article-content">
                                    <span class="category-tag"><?php the_category(', '); ?></span>
                                    <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                                    <p><?php echo wp_trim_words(get_the_excerpt(), 20, '...'); ?></p>
                                    <span class="timestamp"><i class="far fa-clock"></i> <?php echo human_time_diff(get_the_time('U'), current_time('timestamp')) . ' ago'; ?></span>
                                </div>
                            </div>
                            <div class="side-articles">
                        <?php else : ?>
                            <!-- Side articles -->
                            <div class="side-article">
                                <?php if (has_post_thumbnail()) : ?>
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail('thumbnail'); ?>
                                    </a>
                                <?php else : ?>
                                    <a href="<?php the_permalink(); ?>">
                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/placeholder-small.jpg" alt="<?php the_title_attribute(); ?>">
                                    </a>
                                <?php endif; ?>
                                <div class="side-article-content">
                                    <span class="category-tag"><?php the_category(', '); ?></span>
                                    <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                    <span class="timestamp"><i class="far fa-clock"></i> <?php echo human_time_diff(get_the_time('U'), current_time('timestamp')) . ' ago'; ?></span>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endwhile; ?>
                    
                    <?php if ($post_count > 1) : ?>
                        </div><!-- close side-articles -->
                    <?php endif; ?>
                    
                <?php else : ?>
                    <div class="featured-empty">
                        <div class="empty-message">
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