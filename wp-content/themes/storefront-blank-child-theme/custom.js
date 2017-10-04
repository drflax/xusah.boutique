;(function($) {

    "use strict";

    $(document).ready(function () {
        fixCommentsPagination();
        fixReviewLink();
    });

    function fixCommentsPagination() {
        $('.et-tabs #comments nav.woocommerce-pagination a.page-numbers').each(setLinkToTab);

        function setLinkToTab() {
            var $this = $(this);
            var href = $this.attr('href');

            if (href.indexOf('#comments')) {
                var index = $this.parents('.et-content-wrap > section').index();
                href = href.replace('#comments', '#et-tabs-' + index);
                $this.attr('href', href);
            }
        }
    }

    function fixReviewLink() {
        $('a.woocommerce-review-link').click(function (e) {
            e.preventDefault();
            getReviewsTab().click();
            return false;
        });

        function getReviewsTab() {
            var $el = $('li.reviews_tab');

            if ($el.length) {
                return $el;
            }
            return $('.et-tabs-nav li:last-child');
        }
    }

})(jQuery);