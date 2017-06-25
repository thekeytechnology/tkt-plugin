<?php

function tk_cf7_conversion_tracking()
{
    ?>
    <script type="text/javascript">
        document.addEventListener('wpcf7submit', function (event) {
            var inputs = event.detail.inputs;

            for (var i = 0; i < inputs.length; i++) {
                if ('tk-conversion-action' === inputs[i].name) {
                    __gaTracker && __gaTracker('send', 'event', 'Conversion', input[i].value);
                }

            }
        }, false);
    </script>
    <?php
}

function tkInstallCF7ConversionTracking($forms)
{
    add_action('wp_head', 'tk_cf7_conversion_tracking');
}