jQuery( document ).ready( function() {
    jQuery('a[href^="tel:"]').click(function() {
        __gaTracker && __gaTracker('send', 'event', 'Conversion', 'Anruf');
        ga && ga('send', 'event', 'Conversion', 'Anruf');
    });
});