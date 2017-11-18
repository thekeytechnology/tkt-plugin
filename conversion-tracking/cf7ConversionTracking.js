document.addEventListener('wpcf7mailsent', function (event) {
    var inputs = event.detail.inputs;

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

    if(track){
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
    }

}, false);