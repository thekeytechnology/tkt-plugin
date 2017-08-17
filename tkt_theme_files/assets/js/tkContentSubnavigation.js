jQuery(document).ready(function ($) {
    var sidebar = $('.page-template-template-tk-content .sidebar .widget_text');

    var adminBar = $("#wpadminbar");
    var headerWrapper = $("#Header_wrapper");

    var topBar = $("#Top_bar");
    var actionBar = $("#Action_bar");
    var footer = $("#Footer");
    var hasStickyHeader = false;

    var dynamicBarHeights = adminBar.height();
    if ($(".header-fixed").length){ //if a different theme header style with different stickiness classes/etc is ever used, adjust this
        dynamicBarHeights += (topBar.height()+actionBar.height());
        hasStickyHeader = true;
    }

    var marginToTopBars = 20;
    var marginToFooter = 20;

    if (sidebar.length) {

        var originalPosition = sidebar.offset().top;

        $(window).scroll(function () {
            var viewportWidth = $(window).width();
            var scrollPosition = $(window).scrollTop();

            var screenBigEnough = viewportWidth > 800;
            var scrolledToPosition = hasStickyHeader ? ((scrollPosition + dynamicBarHeights + marginToTopBars) >= originalPosition) : ( (scrollPosition >= headerWrapper.height()) );

            if (scrolledToPosition && screenBigEnough) {

                if ( scrollPosition + sidebar.height() + dynamicBarHeights + marginToTopBars + marginToFooter > footer.offset().top ) {
                    sidebar.css('position', 'absolute');
                    sidebar.css('min-width', '382.250px');
                    sidebar.css('top', 'inherit');
                    sidebar.css('bottom', marginToFooter + 'px');
                } else {
                    sidebar.css('position', 'fixed');
                    sidebar.css('min-width', '382.250px');
                    sidebar.css('top', (dynamicBarHeights + marginToTopBars) + 'px');
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

    var offset = (dynamicBarHeights + marginToTopBars);

    onlyMainNavigationButtons.each(function () {
        var hashTarget = $(this).attr("href").split("#")[1];
        $("#" + hashTarget).each(function () {
            new Waypoint({
                element: this,
                handler: waypointHandler,
                offset: 0//offset//'75px'
            })
        })
    });


    //add offsets to link targets so the headings don't get overlapped by sticky header elements
    this.styleSheets[this.styleSheets.length-1].insertRule('h2:before {content: ""; display: block; visibility: hidden; padding-top: '+offset+'px; margin-top: -'+offset+'px }',0);

});