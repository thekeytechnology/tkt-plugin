jQuery( document ).ready( function() {

    var category = tkSignUpConversionTrackingParameters["category"];
    var action = tkSignUpConversionTrackingParameters["action"];
    var label = tkSignUpConversionTrackingParameters["label"];
    var value = parseInt(tkSignUpConversionTrackingParameters["value"]);

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
});
