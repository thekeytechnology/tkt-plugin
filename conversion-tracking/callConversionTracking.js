jQuery( document ).ready( function() {
    jQuery('a[href^="tel:"]').click(function() {

        var category = tkCallConversionTrackingParameters["category"];
        var action = tkCallConversionTrackingParameters["action"];
        var label = tkCallConversionTrackingParameters["label"];
        var value = parseInt(tkCallConversionTrackingParameters["value"]);

        var fieldsObject = {};

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
        if (typeof dataLayer !== "undefined") {
            dataLayer.push({
                "event": "tk-site-event",
                "eventCategory": category,
                "eventAction": action,
                "eventLabel": label,
                "eventValue": value
            })
        }
    });
});