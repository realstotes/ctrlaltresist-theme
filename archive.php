<?php
/**
 * The template for displaying archive pages
 */

get_header();
?>

<main class="main-content container">
    <div class="articles-section">
        <header class="archive-header">
            <?php
            the_archive_title('<h1 class="archive-title">', '</h1>');
            the_archive_description('<div class="archive-description">', '</div>');
            ?>
        </header>
        
        <div class="news-grid">
            <?php if (have_posts()) : ?>
                <?php while (have_posts()) : the_post(); ?>
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
            <?php else : ?>
                <p><?php esc_html_e('No posts found.', 'ctrlaltresist'); ?></p>
            <?php endif; ?>
        </div>
        
        <div class="pagination">
            <?php the_posts_pagination(array(
                'mid_size'  => 2,
                'prev_text' => __('Previous', 'ctrlaltresist'),
                'next_text' => __('Next', 'ctrlaltresist'),
            )); ?>
        </div>
    </div>
    
    <?php get_sidebar(); ?>
</main>

<?php get_footer(); ?>