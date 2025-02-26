<?php
/**
 * The sidebar containing the main widget area
 */

if (!is_active_sidebar('sidebar-1')) {
    // No widgets added to sidebar, show default content
    ?>
    <aside class="sidebar">
        <div class="sidebar-widget">
            <h3 class="widget-title">Popular Articles</h3>
            <div class="popular-posts">
                <?php
                $popular_posts = new WP_Query(array(
                    'posts_per_page' => 3,
                    'meta_key'       => 'post_views_count',
                    'orderby'        => 'meta_value_num',
                    'order'          => 'DESC',
                ));
                
                if ($popular_posts->have_posts()) :
                    while ($popular_posts->have_posts()) : $popular_posts->the_post();
                ?>
                    <div class="popular-post">
                        <?php if (has_post_thumbnail()) : ?>
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail('thumbnail'); ?>
                            </a>
                        <?php else : ?>
                            <a href="<?php the_permalink(); ?>">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/placeholder-small.jpg" alt="<?php the_title_attribute(); ?>">
                            </a>
                        <?php endif; ?>
                        <div class="popular-post-content">
                            <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                            <span class="timestamp"><?php echo human_time_diff(get_the_time('U'), current_time('timestamp')) . ' ago'; ?></span>
                        </div>
                    </div>
                <?php
                    endwhile;
                    wp_reset_postdata();
                else :
                    echo '<p>' . esc_html__('No popular posts found.', 'ctrlaltresist') . '</p>';
                endif;
                ?>
            </div>
        </div>
        
        <div class="sidebar-widget">
            <h3 class="widget-title">Newsletter</h3>
            <p>Get the latest political news and analysis delivered to your inbox daily.</p>
            <form class="newsletter-form">
                <input type="text" placeholder="Your Name">
                <input type="email" placeholder="Your Email">
                <button>Subscribe</button>
            </form>
        </div>
        
        <div class="sidebar-widget">
            <h3 class="widget-title">Categories</h3>
            <ul class="categories-list">
                <?php
                $categories = get_categories(array(
                    'orderby'    => 'count',
                    'order'      => 'DESC',
                    'hide_empty' => 1,
                ));
                
                foreach ($categories as $category) {
                    printf(
                        '<li><a href="%s">%s <span class="count">%s</span></a></li>',
                        esc_url(get_category_link($category->term_id)),
                        esc_html($category->name),
                        esc_html($category->count)
                    );
                }
                ?>
            </ul>
        </div>
        
        <div class="sidebar-widget">
            <h3 class="widget-title">Advertisement</h3>
            <div class="ad-container">
                <p>ADVERTISEMENT</p>
                <div style="background-color: #333355; height: 250px; display: flex; align-items: center; justify-content: center;">
                    <p>Ad Space (300x250)</p>
                </div>
            </div>
        </div>
    </aside>
    <?php
    return;
}
?>

<aside class="sidebar">
    <?php dynamic_sidebar('sidebar-1'); ?>
</aside>