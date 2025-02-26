<?php
/**
 * The template for displaying single posts
 */

get_header();
?>

<main>
    <div class="container article-container">
        <article class="article-content">
            <?php while (have_posts()) : the_post(); ?>
                <header class="article-header">
                    <span class="category-tag"><?php the_category(', '); ?></span>
                    <h1 class="article-title"><?php the_title(); ?></h1>
                    <div class="article-meta">
                        <div class="author-info">
                            <?php echo get_avatar(get_the_author_meta('ID'), 40, '', '', array('class' => 'author-avatar')); ?>
                            <div>
                                <div class="author-name">By <?php the_author(); ?></div>
                                <div class="author-title"><?php the_author_meta('description'); ?></div>
                            </div>
                        </div>
                        <div class="publish-date">
                            Published: <?php the_time('F j, Y'); ?> 
                            <?php if (get_the_modified_time() != get_the_time()) : ?>
                                | Updated: <?php the_modified_time('F j, Y'); ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </header>
                
                <?php if (has_post_thumbnail()) : ?>
                    <?php the_post_thumbnail('large', array('class' => 'featured-image')); ?>
                <?php endif; ?>
                
                <div class="article-body">
                    <?php the_content(); ?>
                </div>
                
                <div class="article-tags">
                    <?php the_tags('<span class="tag">', '</span><span class="tag">', '</span>'); ?>
                </div>
                
                <div class="share-buttons">
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php the_permalink(); ?>" class="share-button facebook" target="_blank">Share on Facebook</a>
                    <a href="https://twitter.com/intent/tweet?url=<?php the_permalink(); ?>&text=<?php the_title(); ?>" class="share-button twitter" target="_blank">Share on Twitter</a>
                    <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php the_permalink(); ?>&title=<?php the_title(); ?>" class="share-button linkedin" target="_blank">Share on LinkedIn</a>
                    <a href="mailto:?subject=<?php the_title(); ?>&body=Check out this article: <?php the_permalink(); ?>" class="share-button email">Email</a>
                </div>
                
                <div class="author-bio">
                    <div class="bio-header">
                        <?php echo get_avatar(get_the_author_meta('ID'), 60, '', '', array('class' => 'bio-avatar')); ?>
                        <div>
                            <h3 class="bio-name"><?php the_author(); ?></h3>
                            <div class="bio-title"><?php the_author_meta('description'); ?></div>
                        </div>
                    </div>
                    <p><?php echo get_the_author_meta('description'); ?></p>
                </div>
                
                <section class="related-articles">
                    <h2 class="related-title">Related Articles</h2>
                    <div class="related-grid">
                        <?php
                        // Get the current post category IDs
                        $categories = wp_get_post_categories(get_the_ID());
                        
                        // Query for related posts
                        $related_query = new WP_Query(array(
                            'category__in'   => $categories,
                            'post__not_in'   => array(get_the_ID()),
                            'posts_per_page' => 3,
                            'orderby'        => 'rand',
                        ));
                        
                        if ($related_query->have_posts()) :
                            while ($related_query->have_posts()) : $related_query->the_post();
                        ?>
                            <div class="related-card">
                                <?php if (has_post_thumbnail()) : ?>
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail('medium'); ?>
                                    </a>
                                <?php else : ?>
                                    <a href="<?php the_permalink(); ?>">
                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/placeholder-medium.jpg" alt="<?php the_title_attribute(); ?>">
                                    </a>
                                <?php endif; ?>
                                <div class="related-card-content">
                                    <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                    <span class="timestamp"><?php echo human_time_diff(get_the_time('U'), current_time('timestamp')) . ' ago'; ?></span>
                                </div>
                            </div>
                        <?php
                            endwhile;
                        else :
                            echo '<p>' . esc_html__('No related articles found.', 'ctrlaltresist') . '</p>';
                        endif;
                        
                        wp_reset_postdata();
                        ?>
                    </div>
                </section>
                
                <section class="comments-section">
                    <?php 
                    // If comments are open or there are at least one comment
                    if (comments_open() || get_comments_number()) :
                        comments_template();
                    endif;
                    ?>
                </section>
            <?php endwhile; ?>
        </article>
        
        <?php get_sidebar(); ?>
    </div>
</main>

<?php get_footer(); ?>