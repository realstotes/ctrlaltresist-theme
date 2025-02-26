<?php
/**
 * The template for displaying comments
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password,
 * return early without loading the comments.
 */
if (post_password_required()) {
    return;
}
?>

<div id="comments" class="comments-area">
    <?php
    // You can start customizing the comments here
    if (have_comments()) :
        ?>
        <h2 class="comments-title">
            <?php
            $comment_count = get_comments_number();
            if ('1' === $comment_count) {
                printf(
                    /* translators: 1: title. */
                    esc_html__('One comment on &ldquo;%1$s&rdquo;', 'ctrlaltresist'),
                    '<span>' . wp_kses_post(get_the_title()) . '</span>'
                );
            } else {
                printf( 
                    /* translators: 1: comment count number, 2: title. */
                    esc_html(_nx('%1$s comment on &ldquo;%2$s&rdquo;', '%1$s comments on &ldquo;%2$s&rdquo;', $comment_count, 'comments title', 'ctrlaltresist')),
                    number_format_i18n($comment_count),
                    '<span>' . wp_kses_post(get_the_title()) . '</span>'
                );
            }
            ?>
        </h2>

        <ol class="comments-list">
            <?php
            wp_list_comments(array(
                'style'       => 'ol',
                'short_ping'  => true,
                'avatar_size' => 60,
            ));
            ?>
        </ol>

        <?php
        the_comments_navigation();

        // If comments are closed and there are comments, display a message
        if (!comments_open()) :
            ?>
            <p class="no-comments"><?php esc_html_e('Comments are closed.', 'ctrlaltresist'); ?></p>
            <?php
        endif;

    endif; // Check for have_comments().

    // Custom comment form
    $commenter = wp_get_current_commenter();
    $req = get_option('require_name_email');
    $html_req = ($req ? " required='required'" : '');

    $fields = array(
        'author' => sprintf(
            '<div class="comment-form-author"><label for="author">%s%s</label><input id="author" name="author" type="text" value="%s" size="30" maxlength="245"%s /></div>',
            __('Name', 'ctrlaltresist'),
            ($req ? ' <span class="required">*</span>' : ''),
            esc_attr($commenter['comment_author']),
            $html_req
        ),
        'email'  => sprintf(
            '<div class="comment-form-email"><label for="email">%s%s</label><input id="email" name="email" type="email" value="%s" size="30" maxlength="100" aria-describedby="email-notes"%s /></div>',
            __('Email', 'ctrlaltresist'),
            ($req ? ' <span class="required">*</span>' : ''),
            esc_attr($commenter['comment_author_email']),
            $html_req
        ),
        'url'    => sprintf(
            '<div class="comment-form-url"><label for="url">%s</label><input id="url" name="url" type="url" value="%s" size="30" maxlength="200" /></div>',
            __('Website', 'ctrlaltresist'),
            esc_attr($commenter['comment_author_url'])
        ),
    );

    $comments_args = array(
        'fields'        => $fields,
        'comment_field' => '<div class="comment-form-comment"><textarea id="comment" name="comment" placeholder="' . _x('Join the discussion...', 'comment form placeholder', 'ctrlaltresist') . '" cols="45" rows="8" required="required"></textarea></div>',
        'class_submit'  => 'submit-comment',
        'title_reply'   => __('Leave a Comment', 'ctrlaltresist'),
    );

    comment_form($comments_args);
    ?>
</div>