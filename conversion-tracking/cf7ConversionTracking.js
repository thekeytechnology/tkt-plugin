document.addEventListener('wpcf7mailsent', function (event) {
    var inputs = event.detail.inputs;

    for (var i = 0; i < inputs.length; i++) {
        if ('tk-conversion-action' === inputs[i].name) {
            __gaTracker && __gaTracker('send', 'event', 'Conversion', inputs[i].value);
            ga && ga('send', 'event', 'Conversion', inputs[i].value);
            fbq && fbq('trackCustom', 'Conversion', {type: inputs[i].value});
        }
    }
}, false);