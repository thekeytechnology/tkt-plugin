jQuery(document).ready( function ($) {

    $(".tk-ga-tracking-opt-out-link").on("click", function () {
        document.cookie = tkOptOutCookieNames["GA"] + '=true; expires=Wed, 01 Jan 2200 23:59:59 UTC; path=/';
        alert("Google Analytics Opt-Out Cookie gesetzt");
        return false;
    });

    $(".tk-fb-tracking-opt-out-link").on("click", function () {
        document.cookie = tkOptOutCookieNames["FB"] + '=true; expires=Wed, 01 Jan 2200 23:59:59 UTC; path=/';
        alert("Facebook Pixel Opt-Out Cookie gesetzt");
        return false;
    });
});