<?php
/**
 * The template for displaying search results pages
 */

get_header();
?>

<main class="main-content container">
    <div class="search-results-section">
        <header class="page-header">
            <h1 class="page-title">
                <?php
                /* translators: %s: search query. */
                printf(esc_html__('Search Results for: %s', 'ctrlaltresist'), '<span>' . get_search_query() . '</span>');
                ?>
            </h1>
        </header>

        <?php if (have_posts()) : ?>
            <div class="news-grid">
                <?php
                /* Start the Loop */
                while (have_posts()) :
                    the_post();
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
                <?php endwhile; ?>
            </div>

            <div class="pagination">
                <?php the_posts_pagination(array(
                    'mid_size'  => 2,
                    'prev_text' => __('Previous', 'ctrlaltresist'),
                    'next_text' => __('Next', 'ctrlaltresist'),
                )); ?>
            </div>

        <?php else : ?>
            <div class="no-results">
                <h2><?php esc_html_e('No results found', 'ctrlaltresist'); ?></h2>
                <p><?php esc_html_e('Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'ctrlaltresist'); ?></p>
                <?php get_search_form(); ?>
            </div>
        <?php endif; ?>
    </div>
    
    <?php get_sidebar(); ?>
</main>

<?php get_footer(); ?>