function tkGfConversionTracking() {



    var category = tkGfConversionTrackingParameters["category"];
    var action = tkGfConversionTrackingParameters["action"];
    var label = tkGfConversionTrackingParameters["label"];
    var value = parseInt(tkGfConversionTrackingParameters["value"]);

    var fieldsObject = {};

    for (var i = 0; i < tkGfConversionTrackingInputs.length; i++) {

        if ('tk-conversion-category' === tkGfConversionTrackingInputs[i].name){
            category = tkGfConversionTrackingInputs[i].value;
        }

        if ('tk-conversion-action' === tkGfConversionTrackingInputs[i].name) {
            action = tkGfConversionTrackingInputs[i].value;
        }

        if ('tk-conversion-label' === tkGfConversionTrackingInputs[i].name){
            label = tkGfConversionTrackingInputs[i].value;
        }

        if ('tk-conversion-value' === tkGfConversionTrackingInputs[i].name){
            value = parseInt(tkGfConversionTrackingInputs[i].value);
        }
    }

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
    console.log(fieldsObject);

}

/* So it only runs once per page load */
var tkGfConversionTracked = tkGfConversionTracked ? tkGfConversionTracked : false;
if (tkGfConversionTracked) {
    tkGfConversionTracking();
    tkGfConversionTracked = true;
}


