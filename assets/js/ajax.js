/**
 * Stories AJAX Requests JavaScript
 * Handles dynamic content loading via WordPress AJAX API.
 */

(function($) {
    'use strict';

    window.StoriesAJAX = {
        /**
         * Filter stories dynamically via AJAX
         *
         * @param {string} postType - Post type slug to fetch
         * @param {number} page - Page number to retrieve
         * @param {function} callback - Function called with AJAX response
         */
        filterStories: function(postType, page, callback) {
            if (typeof storiesAjax === 'undefined') {
                console.error('storiesAjax is not defined');
                return;
            }

            $.ajax({
                url: storiesAjax.ajax_url,
                type: 'POST',
                data: {
                    action: 'stories_filter_posts',
                    nonce: storiesAjax.nonce,
                    post_type: postType || 'post',
                    page: page || 1
                },
                success: function(response) {
                    if (response.success && typeof callback === 'function') {
                        callback(response.data);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Stories AJAX Error:', error);
                }
            });
        }
    };

})(jQuery);
