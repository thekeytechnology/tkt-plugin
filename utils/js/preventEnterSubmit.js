jQuery(document).ready(function ($) {
    $(window).keydown(function (event) {
        if (event.keyCode === 13 && event.target.nodeName !== 'TEXTAREA' && !$(event.target).hasClass("tk-enable-enter-submit")) {
            event.preventDefault();
            return false;
        }
    });
});

jQuery(document).on('click', '.wpcf7-submit', function(e){
    if( jQuery('.ajax-loader').hasClass('is-active') ) {
        e.preventDefault();
        return false;
    }
});