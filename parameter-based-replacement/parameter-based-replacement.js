
jQuery(document).ready(function ($) {
    if (typeof pbr_list !== 'undefined') {

        pbr_list.forEach(function(pbr_replace_pair){


            var cookieValue = readCookie('tk-pbr_' + pbr_replace_pair['parameter']);
            var parameterExists = window.location.href.indexOf(pbr_replace_pair['parameter']) != -1;


            if (parameterExists || cookieValue) {
                replaceText('*', pbr_replace_pair['search'], pbr_replace_pair['replace'], 'g')
            }


            if (parameterExists) {
                createCookie('tk-pbr_' + pbr_replace_pair['parameter'], pbr_replace_pair['value'], 30)
            }


            /*
            For now we just check if the parameter exists, but ideally it would work like in this comment
            var queryParams = decodeURIComponent(window.location.search.substring(1));
            queryParams = queryParams.split('&');

            /* map them from key=value to {key: value}
            var queryParamsArray = {};
            queryParams.forEach(function(queryParamString) {

                if (queryParamString.includes('=')) {
                    var keyValuePair = queryParamString.split('=');
                    queryParamsArray[keyValuePair[0]] = keyValuePair[1];
                } else {
                    queryParamsArray[queryParamString] = "";
                }

            });


            if (queryParamsArray[pbr_replace_pair['parameter']] != undefined ) {
                if (queryParamsArray[pbr_replace_pair['parameter']] == pbr_replace_pair['value'] || pbr_replace_pair['value'] == "*") {
                    var regex = new RegExp(pbr_replace_pair['search'],'g');

                    document.body.innerHTML = document.body.innerHTML.replace(regex, pbr_replace_pair['replace']);
                }
            }*/

        });
    }


    /* ripped from https://www.quirksmode.org/js/cookies.html */
    function createCookie(name, value, days) {
        var expires;

        if (days) {
            var date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = "; expires=" + date.toGMTString();
        } else {
            expires = "";
        }
        document.cookie = encodeURIComponent(name) + "=" + encodeURIComponent(value) + expires + "; path=/";
    }

    function readCookie(name) {
        var nameEQ = encodeURIComponent(name) + "=";
        var ca = document.cookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) === ' ')
                c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) === 0)
                return decodeURIComponent(c.substring(nameEQ.length, c.length));
        }
        return null;
    }

    /* Taken from https://stackoverflow.com/questions/5558613/replace-words-in-the-body-text */
    function replaceText(selector, text, newText, flags) {
        var matcher = new RegExp(text, flags);
        $(selector).each(function () {
            var $this = $(this);

            if ($this.is('a')) {
                var href = $this.attr('href');
                if (href) {
                    $this.attr('href', href.replace(matcher, newText));
                }
            }

            if (!$this.children().length)
                $this.text($this.text().replace(matcher, newText));
        });
    }
});
