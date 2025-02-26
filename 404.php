<?php
/**
 * The template for displaying 404 pages (not found)
 */

get_header();
?>

<main class="main-content container">
    <section class="error-404 not-found">
        <header class="page-header">
            <h1 class="page-title"><?php esc_html_e('Oops! That page can&rsquo;t be found.', 'ctrlaltresist'); ?></h1>
        </header>

        <div class="page-content">
            <p><?php esc_html_e('It looks like nothing was found at this location. Maybe try searching for something else?', 'ctrlaltresist'); ?></p>

            <?php get_search_form(); ?>
            
            <div class="popular-content">
                <h2><?php esc_html_e('Popular Categories', 'ctrlaltresist'); ?></h2>
                <ul class="categories-list">
                    <?php
                    wp_list_categories(array(
                        'orderby'    => 'count',
                        'order'      => 'DESC',
                        'show_count' => 1,
                        'title_li'   => '',
                        'number'     => 10,
                    ));
                    ?>
                </ul>
                
                <h2><?php esc_html_e('Recent Articles', 'ctrlaltresist'); ?></h2>
                <div class="news-grid">
                    <?php
                    $recent_posts = new WP_Query(array(
                        'posts_per_page' => 4,
                        'post_status'    => 'publish',
                    ));
                    
                    if ($recent_posts->have_posts()) :
                        while ($recent_posts->have_posts()) : $recent_posts->the_post();
                    ?>
                        <div class="news-card">
                            <?php if (has_post_thumbnail()) : ?>
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('medium', array('class' => 'news-card-image')); ?>
                                </a>
                            <?php else : ?>
                                <a href="<?php the_permalink(); ?>">
                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/placeholder.jpg" alt="<?php the_title_attribute(); ?>" class="news-card-image">
                                </a>
                            <?php endif; ?>
                            <div class="news-card-content">
                                <span class="category-tag"><?php the_category(', '); ?></span>
                                <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                <p><?php the_excerpt(); ?></p>
                                <a href="<?php the_permalink(); ?>" class="read-more">Read More</a>
                            </div>
                        </div>
                    <?php
                        endwhile;
                        wp_reset_postdata();
                    endif;
                    ?>
                </div>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>