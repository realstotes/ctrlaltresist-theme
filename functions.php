<?php
/**
 * CtrlAltResist Theme functions and definitions
 *
 * This file contains the main theme setup functions, widget registrations,
 * custom meta boxes, and various helper functions for your theme.
 *
 * @package CtrlAltResist
 */

/**
 * Theme setup function - Registers theme features and support
 */
function ctrlaltresist_setup() {
    // Add default posts and comments RSS feed links to head
    add_theme_support('automatic-feed-links');

    // Let WordPress manage the document title
    add_theme_support('title-tag');

    // Enable support for Post Thumbnails on posts and pages
    add_theme_support('post-thumbnails');

    // Set default featured image sizes
    set_post_thumbnail_size(350, 233, true);
    add_image_size('ctrlaltresist-large', 1200, 630, true);
    add_image_size('ctrlaltresist-medium', 600, 400, true);
    add_image_size('ctrlaltresist-small', 300, 200, true);

    // Register navigation menus
    register_nav_menus(array(
        'primary' => esc_html__('Primary Menu', 'ctrlaltresist'),
        'footer' => esc_html__('Footer Menu', 'ctrlaltresist'),
        'footer-legal' => esc_html__('Footer Legal Menu', 'ctrlaltresist'),
        'social' => esc_html__('Social Menu', 'ctrlaltresist'),
    ));

    // Add theme support for Custom Logo
    add_theme_support('custom-logo', array(
        'height'      => 250,
        'width'       => 250,
        'flex-width'  => true,
        'flex-height' => true,
    ));
    
    // Add support for editor styles
    add_theme_support('editor-styles');
    
    // Add support for responsive embeds
    add_theme_support('responsive-embeds');
    
    // Add support for HTML5 features
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ));
}
// Hook the setup function to run when the theme is initialized
add_action('after_setup_theme', 'ctrlaltresist_setup');

/**
 * Register widget areas for the theme
 */
function ctrlaltresist_widgets_init() {
    register_sidebar(array(
        'name'          => esc_html__('Sidebar', 'ctrlaltresist'),
        'id'            => 'sidebar-1',
        'description'   => esc_html__('Add widgets here to appear in your sidebar.', 'ctrlaltresist'),
        'before_widget' => '<div class="sidebar-widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
}
add_action('widgets_init', 'ctrlaltresist_widgets_init');

/**
 * Enqueue scripts and styles for the frontend
 */
function ctrlaltresist_scripts() {
    // Enqueue main stylesheet
    wp_enqueue_style('ctrlaltresist-style', get_stylesheet_uri(), array(), '1.0.0');
    
    // Enqueue main CSS file
    wp_enqueue_style('ctrlaltresist-main', get_template_directory_uri() . '/assets/css/main.css', array(), '1.0.0');
    
    // Enqueue Font Awesome
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css', array(), '6.0.0');
    
    // Enqueue main JavaScript file
    wp_enqueue_script('ctrlaltresist-main', get_template_directory_uri() . '/assets/js/main.js', array('jquery'), '1.0.0', true);
}
add_action('wp_enqueue_scripts', 'ctrlaltresist_scripts');

/**
 * Enqueue custom admin styles and scripts
 */
function ctrlaltresist_admin_scripts() {
    // Admin styles
    wp_enqueue_style('ctrlaltresist-admin-style', get_template_directory_uri() . '/assets/css/admin.css', array(), '1.0.0');
    
    // Admin script for custom meta boxes
    wp_enqueue_script('ctrlaltresist-admin-script', get_template_directory_uri() . '/assets/js/admin.js', array('jquery'), '1.0.0', true);
}
add_action('admin_enqueue_scripts', 'ctrlaltresist_admin_scripts');

/**
 * Add meta box for featured article selection
 */
function ctrlaltresist_add_meta_boxes() {
    add_meta_box(
        'ctrlaltresist_featured_article',
        __('Featured Article Settings', 'ctrlaltresist'),
        'ctrlaltresist_featured_article_callback',
        'post',
        'side',
        'high'
    );
}
add_action('add_meta_boxes', 'ctrlaltresist_add_meta_boxes');

/**
 * Meta box callback function
 */
function ctrlaltresist_featured_article_callback($post) {
    // Add a nonce field for security
    wp_nonce_field('ctrlaltresist_featured_article_nonce', 'ctrlaltresist_featured_article_nonce');
    
    // Get the current value
    $value = get_post_meta($post->ID, 'featured_article', true);
    
    // Output the field
    ?>
    <p>
        <label>
            <input type="checkbox" name="featured_article" <?php checked($value, true); ?> />
            <?php _e('Mark as Featured Article', 'ctrlaltresist'); ?>
        </label>
    </p>
    <p class="description">
        <?php _e('Featured articles appear in the Breaking News section on the homepage.', 'ctrlaltresist'); ?>
    </p>
    <?php
}

/**
 * Save meta box data
 */
function ctrlaltresist_save_meta_boxes($post_id) {
    // Check if nonce is set
    if (!isset($_POST['ctrlaltresist_featured_article_nonce'])) {
        return;
    }
    
    // Verify the nonce
    if (!wp_verify_nonce($_POST['ctrlaltresist_featured_article_nonce'], 'ctrlaltresist_featured_article_nonce')) {
        return;
    }
    
    // If this is an autosave, don't do anything
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    // Check permissions
    if ('post' === $_POST['post_type']) {
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
    }
    
    // Save the featured article setting
    $featured = isset($_POST['featured_article']) ? true : false;
    update_post_meta($post_id, 'featured_article', $featured);
}
add_action('save_post', 'ctrlaltresist_save_meta_boxes');

/**
 * Add post view counter functionality
 */
function ctrlaltresist_set_post_views($post_id) {
    $count_key = 'post_views_count';
    $count = get_post_meta($post_id, $count_key, true);
    
    if ($count == '') {
        $count = 0;
        delete_post_meta($post_id, $count_key);
        add_post_meta($post_id, $count_key, '0');
    } else {
        $count++;
        update_post_meta($post_id, $count_key, $count);
    }
}

/**
 * Track post views on single post pages
 */
function ctrlaltresist_track_post_views() {
    if (is_single()) {
        ctrlaltresist_set_post_views(get_the_ID());
    }
}
add_action('wp_head', 'ctrlaltresist_track_post_views');

/**
 * Add image placeholder function
 * Use: ctrlaltresist_placeholder_image('medium')
 */
function ctrlaltresist_placeholder_image($size = 'thumbnail') {
    $placeholder_images = array(
        'placeholder.jpg',
        'placeholder-2.jpg',
        'placeholder-3.jpg',
        'placeholder-4.jpg',
    );
    
    $random_image = $placeholder_images[array_rand($placeholder_images)];
    return get_template_directory_uri() . '/assets/images/' . $random_image;
}

/**
 * Add social sharing function
 */
function ctrlaltresist_social_sharing() {
    if (is_single()) {
        $share_url = urlencode(get_permalink());
        $share_title = urlencode(get_the_title());
        
        echo '<div class="share-buttons">';
        echo '<span class="share-title"><i class="fas fa-share-alt"></i> Share:</span>';
        echo '<a href="https://www.facebook.com/sharer/sharer.php?u=' . $share_url . '" class="share-button facebook" target="_blank"><i class="fab fa-facebook-f"></i> Facebook</a>';
        echo '<a href="https://twitter.com/intent/tweet?url=' . $share_url . '&text=' . $share_title . '" class="share-button twitter" target="_blank"><i class="fab fa-twitter"></i> Twitter</a>';
        echo '<a href="https://www.linkedin.com/shareArticle?mini=true&url=' . $share_url . '&title=' . $share_title . '" class="share-button linkedin" target="_blank"><i class="fab fa-linkedin-in"></i> LinkedIn</a>';
        echo '<a href="mailto:?subject=' . $share_title . '&body=Check out this article: ' . $share_url . '" class="share-button email"><i class="fas fa-envelope"></i> Email</a>';
        echo '</div>';
    }
}

/**
 * Add reading time calculation
 */
function ctrlaltresist_reading_time() {
    $content = get_post_field('post_content', get_the_ID());
    $word_count = str_word_count(strip_tags($content));
    $reading_time = ceil($word_count / 200); // Assuming average reading speed of 200 words per minute
    
    if ($reading_time < 1) {
        $reading_time = 1;
    }
    
    echo '<span class="reading-time"><i class="far fa-clock"></i> ' . $reading_time . ' min read</span>';
}

/**
 * Add custom body classes
 */
function ctrlaltresist_body_classes($classes) {
    // Add a class if it is the front page
    if (is_front_page()) {
        $classes[] = 'front-page';
    }
    
    // Add class based on whether sidebar is active
    if (is_active_sidebar('sidebar-1')) {
        $classes[] = 'has-sidebar';
    } else {
        $classes[] = 'no-sidebar';
    }
    
    // Add class for single post
    if (is_single()) {
        $classes[] = 'single-post-view';
    }
    
    return $classes;
}
add_filter('body_class', 'ctrlaltresist_body_classes');

/**
 * Add Customizer options
 */
function ctrlaltresist_customize_register($wp_customize) {
    // Add a section for Theme Options
    $wp_customize->add_section('ctrlaltresist_theme_options', array(
        'title'    => __('CtrlAltResist Theme Options', 'ctrlaltresist'),
        'priority' => 130,
    ));
    
    // Add setting for accent color
    $wp_customize->add_setting('ctrlaltresist_accent_color', array(
        'default'           => '#e63946',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'ctrlaltresist_accent_color', array(
        'label'    => __('Accent Color', 'ctrlaltresist'),
        'section'  => 'ctrlaltresist_theme_options',
        'settings' => 'ctrlaltresist_accent_color',
    )));
    
    // Add setting for trending tags
    $wp_customize->add_setting('ctrlaltresist_trending_tags', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('ctrlaltresist_trending_tags', array(
        'label'       => __('Trending Tags (comma separated)', 'ctrlaltresist'),
        'description' => __('Enter tags that will be shown in the trending bar', 'ctrlaltresist'),
        'section'     => 'ctrlaltresist_theme_options',
        'type'        => 'text',
    ));
    
    // Add setting for social media URLs
    $social_platforms = array(
        'facebook'  => __('Facebook URL', 'ctrlaltresist'),
        'twitter'   => __('Twitter URL', 'ctrlaltresist'),
        'instagram' => __('Instagram URL', 'ctrlaltresist'),
        'youtube'   => __('YouTube URL', 'ctrlaltresist'),
        'linkedin'  => __('LinkedIn URL', 'ctrlaltresist'),
    );
    
    foreach ($social_platforms as $platform => $label) {
        $wp_customize->add_setting('ctrlaltresist_' . $platform . '_url', array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        ));
        
        $wp_customize->add_control('ctrlaltresist_' . $platform . '_url', array(
            'label'   => $label,
            'section' => 'ctrlaltresist_theme_options',
            'type'    => 'url',
        ));
    }
}
add_action('customize_register', 'ctrlaltresist_customize_register');

/**
 * Output custom CSS from Customizer settings
 */
function ctrlaltresist_customizer_css() {
    $accent_color = get_theme_mod('ctrlaltresist_accent_color', '#e63946');
    
    ?>
    <style type="text/css">
        :root {
            --primary-color: <?php echo esc_attr($accent_color); ?>;
        }
        
        .category-tag, 
        .pagination .page-numbers.current,
        .share-button.facebook:hover,
        .share-button.twitter:hover,
        .share-button.linkedin:hover,
        .share-button.email:hover,
        .submit-comment:hover,
        .form-submit .submit:hover,
        .newsletter-form button:hover,
        .tag:hover,
        nav ul li a:hover, 
        #primary-menu li a:hover,
        .current-menu-item a,
        .admin-link:hover {
            background-color: <?php echo esc_attr($accent_color); ?>;
        }
        
        a, 
        .logo span,
        .trending-label,
        .read-more,
        .comment-action:hover,
        .comment-reply-link:hover {
            color: <?php echo esc_attr($accent_color); ?>;
        }
        
        .featured-title,
        .article-body h2:after,
        .widget-title,
        .footer-column h3,
        .related-title,
        .comments-title {
            border-color: <?php echo esc_attr($accent_color); ?>;
        }
        
        .read-more:after {
            background-color: <?php echo esc_attr($accent_color); ?>;
        }
    </style>
    <?php
}
add_action('wp_head', 'ctrlaltresist_customizer_css');

/**
 * Custom template tags
 */

// Display categories with icons
function ctrlaltresist_category_list() {
    $categories = get_the_category();
    if (!empty($categories)) {
        echo '<span class="category-list">';
        foreach ($categories as $category) {
            echo '<a href="' . esc_url(get_category_link($category->term_id)) . '" class="category-tag">';
            // You can add category-specific icons here
            echo esc_html($category->name);
            echo '</a> ';
        }
        echo '</span>';
    }
}

// Display post date in a nice format
function ctrlaltresist_posted_on() {
    $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
    if (get_the_time('U') !== get_the_modified_time('U')) {
        $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
    }

    $time_string = sprintf(
        $time_string,
        esc_attr(get_the_date(DATE_W3C)),
        esc_html(get_the_date()),
        esc_attr(get_the_modified_date(DATE_W3C)),
        esc_html(get_the_modified_date())
    );

    echo '<span class="posted-on"><i class="far fa-calendar-alt"></i> ' . $time_string . '</span>';
}

// Function to get trending posts
function ctrlaltresist_get_trending_posts($count = 3) {
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => $count,
        'meta_key' => 'post_views_count',
        'orderby' => 'meta_value_num',
        'order' => 'DESC',
        'post_status' => 'publish',
    );
    return new WP_Query($args);
}

// Function to get featured posts
function ctrlaltresist_get_featured_posts($count = 4) {
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => $count,
        'meta_key' => 'featured_article',
        'meta_value' => true,
        'post_status' => 'publish',
    );
    return new WP_Query($args);
}

// Function to get related posts
function ctrlaltresist_get_related_posts($post_id, $count = 3) {
    $categories = wp_get_post_categories($post_id);
    if (empty($categories)) {
        return new WP_Query();
    }
    
    $args = array(
        'category__in' => $categories,
        'post__not_in' => array($post_id),
        'posts_per_page' => $count,
        'orderby' => 'rand',
        'post_status' => 'publish',
    );
    return new WP_Query($args);
}