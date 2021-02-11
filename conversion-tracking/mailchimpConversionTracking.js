var tkMcTracked = false;

jQuery( document ).ready( function($) {
    $('form#mc-embedded-subscribe-form').submit(function(event) {
        var inputs = $(this).find('input');

        var track = false;

        var category = "Conversion";
        var action = "";
        var label = "";
        var value = 0;

        var fieldsObject = {};

        for (var i = 0; i < inputs.length; i++) {

            if ('tk-conversion-category' === inputs[i].name){
                category = inputs[i].value;
            }

            if ('tk-conversion-action' === inputs[i].name) {
                action = inputs[i].value;
                track = true;
            }

            if ('tk-conversion-label' === inputs[i].name){
                label = inputs[i].value;
            }

            if ('tk-conversion-value' === inputs[i].name){
                value = parseInt(inputs[i].value);
            }
        }

        if(track && !tkMcTracked){
            fieldsObject["eventCategory"] = category;
            fieldsObject["eventAction"] = action;
            if(label.length > 0){
                fieldsObject["eventLabel"] = label;
            }
            if(value > 0){
                fieldsObject["eventValue"] = value;
            }
            if(typeof __gaTracker !== "undefined"){
                __gaTracker('send', 'event', fieldsObject);
            }
            if(typeof __ga !== "undefined"){
                __ga('send', 'event', fieldsObject);
            }
            if(typeof fbq !== "undefined"){
                fbq('trackCustom', 'Conversion', {type: action});
            }
            if (typeof gtag !== "undefined") {
                gtag('event', action, {
                    'event_category': category,
                    'event_label': label,
                    'value': value
                });
            } else if (typeof dataLayer !== "undefined") {
                dataLayer.push({
                    "event": "tk-site-event",
                    "eventCategory": category,
                    "eventAction": action,
                    "eventLabel": label,
                    "eventValue": value
                })
            }

            tkMcTracked = true;
        }
    });
});
