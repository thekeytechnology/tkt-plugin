jQuery(document).ready( function ($) {

    function tkGetCookieValue(name) {
        var nameEQ = name + "=";
        var cookies = document.cookie.split(";");
        for(var i = 0; i < cookies.length; i++) {
            var temp = cookies[i];
            temp = temp.trim();
            if (temp.indexOf(nameEQ) == 0) {
                return temp.substring(nameEQ.length, temp.length);
            }
        }
        return null;
    }

    function tkSetOptOutCookie(type) {
        var cookieValue = tkGetCookieValue("tk-tracking-opt-out");
        if (null !== cookieValue) {
            if (!cookieValue.includes(type)) {
                cookieValue += "," + type;
            }
        } else {
            cookieValue = type;
        }
        document.cookie = "tk-tracking-opt-out=" + cookieValue + "; expires=Wed, 01 Jan 2200 23:59:59 UTC; path=/; domain=." + window.location.hostname;
    }

    $(".tk-ga-tracking-opt-out-link").on("click", function () {
        tkSetOptOutCookie("ga");
        alert("Google Analytics Opt-Out Cookie gesetzt");
        return false;
    });

    $(".tk-fb-tracking-opt-out-link").on("click", function () {
        tkSetOptOutCookie("fb");
        alert("Facebook Pixel Opt-Out Cookie gesetzt");
        return false;
    });

    $(".tk-gtm-tracking-opt-out-link").on("click", function () {
        tkSetOptOutCookie("gtm");
        alert("Google Tag Manager Opt-Out Cookie gesetzt");
        return false;
    });
});