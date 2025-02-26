/**
 * CtrlAltResist Admin JavaScript
 * Create a folder called 'js' in your 'assets' directory and place this file there
 */

(function($) {
    'use strict';
    
    $(document).ready(function() {
        /**
         * Quick toggle for featured articles in admin list
         */
        $('.featured-toggle').on('click', function(e) {
            e.preventDefault();
            
            var $button = $(this);
            var postId = $button.data('post-id');
            var isFeatured = $button.data('featured') === 1;
            
            $button.prop('disabled', true);
            
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'toggle_featured_article',
                    post_id: postId,
                    featured: !isFeatured,
                    security: ctrlaltresistAdmin.nonce
                },
                success: function(response) {
                    if (response.success) {
                        $button.data('featured', isFeatured ? 0 : 1);
                        
                        if (isFeatured) {
                            $button.html('<i class="dashicons dashicons-star-empty featured-icon inactive"></i>');
                            $button.attr('title', 'Mark as Featured');
                        } else {
                            $button.html('<i class="dashicons dashicons-star-filled featured-icon"></i>');
                            $button.attr('title', 'Remove from Featured');
                        }
                    }
                },
                complete: function() {
                    $button.prop('disabled', false);
                }
            });
        });
        
        /**
         * Enhance featured article meta box in post editor
         */
        $('#ctrlaltresist_featured_article input[type="checkbox"]').on('change', function() {
            var $checkbox = $(this);
            var $metaBox = $('#ctrlaltresist_featured_article');
            
            if ($checkbox.is(':checked')) {
                $metaBox.addClass('featured-active');
            } else {
                $metaBox.removeClass('featured-active');
            }
        });
        
        /**
         * Initialize featured state on load
         */
        if ($('#ctrlaltresist_featured_article input[type="checkbox"]').is(':checked')) {
            $('#ctrlaltresist_featured_article').addClass('featured-active');
        }
        
        /**
         * Enhance image selection UI
         */
        $('.ctrlaltresist-image-selector').each(function() {
            var $container = $(this);
            var $imagePreview = $container.find('.image-preview');
            var $imageInput = $container.find('.image-id');
            var $uploadButton = $container.find('.upload-button');
            var $removeButton = $container.find('.remove-button');
            
            $uploadButton.on('click', function(e) {
                e.preventDefault();
                
                var frame = wp.media({
                    title: 'Select or Upload an Image',
                    button: { text: 'Use this image' },
                    multiple: false
                });
                
                frame.on('select', function() {
                    var attachment = frame.state().get('selection').first().toJSON();
                    $imagePreview.html('<img src="' + attachment.url + '" alt="">');
                    $imageInput.val(attachment.id);
                    $removeButton.show();
                });
                
                frame.open();
            });
            
            $removeButton.on('click', function(e) {
                e.preventDefault();
                $imagePreview.html('');
                $imageInput.val('');
                $removeButton.hide();
            });
        });
        
        /**
         * Help tabs
         */
        $('.ctrlaltresist-help-tab-link').on('click', function(e) {
            e.preventDefault();
            
            var $link = $(this);
            var tabId = $link.data('tab');
            
            $('.ctrlaltresist-help-tab-link').removeClass('active');
            $('.ctrlaltresist-help-tab').removeClass('active');
            
            $link.addClass('active');
            $('#' + tabId).addClass('active');
        });
    });
    
})(jQuery);