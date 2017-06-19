jQuery(document).ready(function ($) {
    var sticky = $('.page-template-template-content .sidebar .widget_text');

    var adminBar = $("#wpadminbar");
    var topBar = $("#Top_bar");
    var footer = $("#Footer");

    if (sticky.length) {
        var originalPosition = sticky.offset().top;
        var footerTop = footer.offset().top;

        $(window).scroll(function () {
            var viewportWidth = $(window).width();
            var dynamicBarHeights = adminBar.height() + topBar.height();
            var marginToTopBars = 20;
            var scrollPosition = $(window).scrollTop() + dynamicBarHeights + marginToTopBars;

            var screenBigEnough = viewportWidth > 800;
            var scrolledToPosition = scrollPosition >= originalPosition;

            if (scrolledToPosition && screenBigEnough) {

                if (scrollPosition + marginToTopBars + sticky.height() > footerTop) {
                    sticky.css('position', 'absolute');
                    sticky.css('min-width', '382.250px');
                    sticky.css('top', 'inherit');
                    sticky.css('bottom', '20px');
                } else {
                    sticky.css('position', 'fixed');
                    sticky.css('min-width', '382.250px');
                    sticky.css('top', (dynamicBarHeights + marginToTopBars) + 'px');
                    sticky.css('bottom', 'inherit');
                }

            } else {
                sticky.css('position', 'relative');
                if (!screenBigEnough) {
                    sticky.css('min-width', 'inherit');
                }
                sticky.css('top', 0);
            }
        });
    }

    var lastVisitedWaypoint = null;
    var waypointStack = [];

    var waypointHandler = function (direction) {
        var waypointToSet = null;

        var currentWaypointId = this.element.id;

        if (direction === "down") {
            waypointStack.push(currentWaypointId);
            waypointToSet = currentWaypointId;
        } else {
            waypointStack.pop();
            waypointToSet = waypointStack[waypointStack.length - 1];
        }

        lastVisitedWaypoint = waypointToSet;

        // if (waypointToSet != null) {
        //     history.replaceState(null, null, '#' + waypointToSet);
        // } else {
        //     history.replaceState(null, null, "#");
        // }
        $("ol.tk-content-subnavigation li a").each(function () {
            var thisElement = $(this);
            thisElement.removeClass("tk-current-article-position");

            if (waypointToSet !== null && thisElement.attr("href") === "#" + waypointToSet) {
                thisElement.addClass("tk-current-article-position");
            }
        })

    };

    var onlyMainNavigationButtons = $("ol.tk-content-subnavigation li a");
    onlyMainNavigationButtons.each(function () {
        var hashTarget = $(this).attr("href").split("#")[1];
        $("#" + hashTarget).each(function () {
            new Waypoint({
                element: this,
                handler: waypointHandler,
                offset: '75px'
            })
        })
    });
});