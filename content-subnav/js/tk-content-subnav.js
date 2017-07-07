jQuery(document).ready(function ($) {
    var sidebar = $('.page-template-template-tk-content .sidebar .widget_text');

    var topBar = $("#Top_bar");
    var actionBar = $("#Action_bar");
    var footer = $("#Footer");

    var bodyElement = $("body");
    var isStickyHeader = bodyElement.hasClass("sticky-header") || bodyElement.hasClass("header-fixed");

    var marginToTopBars = 20;
    var marginToFooter = 20;

    if (sidebar.length) {
        var originalPosition = sidebar.offset().top;

        $(window).scroll(function () {

            var viewportWidth = $(window).width();
            var scrollPosition = $(window).scrollTop();

            var screenBigEnough = viewportWidth > 800;

            var dynamicBarHeights = 0;
            var scrolledToPosition = 0;
            if (isStickyHeader) {
                dynamicBarHeights = topBar.height() + actionBar.height() + marginToTopBars;
                scrolledToPosition = scrollPosition + dynamicBarHeights > originalPosition;
            } else {
                dynamicBarHeights = 35;
                scrolledToPosition = scrollPosition - 35 > originalPosition;
            }

            if (scrolledToPosition && screenBigEnough) {

                if (scrollPosition + sidebar.height() + dynamicBarHeights + marginToFooter > footer.offset().top) {
                    sidebar.css('position', 'absolute');
                    sidebar.css('min-width', '382.250px');
                    sidebar.css('top', 'inherit');
                    sidebar.css('bottom', marginToFooter + 'px');
                } else {
                    sidebar.css('position', 'fixed');
                    sidebar.css('min-width', '382.250px');
                    sidebar.css('top', (dynamicBarHeights) + 'px');
                    sidebar.css('bottom', 'inherit');
                }

            } else {
                sidebar.css('position', 'relative');
                if (!screenBigEnough) {
                    sidebar.css('min-width', 'inherit');
                }
                sidebar.css('top', 0);
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

    //add offsets to link targets so the headings don't get overlapped by sticky header elements
    // this.styleSheets[this.styleSheets.length-1].insertRule('h2:before {content: ""; display: block; visibility: hidden; padding-top: '+offset+'px; margin-top: -'+offset+'px }',0);

});