<?php

/*
 * Put <input type="hidden" name="tk-conversion-action" value="name_of_conversion"/> into forms where conversion tracking is supposed to occur.
 * */

function tkCF7ConversionTracking()
{
    ?>
    <script type="text/javascript">
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
    </script>
    <?php
}

function tkInstallCF7ConversionTracking()
{
    add_action('wp_head', 'tkCF7ConversionTracking');
}


//experimental
function tkCallConversionTracking()
{
    ?>
    <script type="text/javascript">
        jQuery( document ).ready( function() {
            jQuery('a[href^="tel:"]').click(function() {
                __gaTracker && __gaTracker('send', 'event', 'Conversion', 'Anruf');
                ga && ga('send', 'event', 'Conversion', 'Anruf');
            });
        });
    </script>
    <?php
}

function tkInstallCallConversionTracking()
{
    add_action('wp_head', 'tkCallConversionTracking');
}