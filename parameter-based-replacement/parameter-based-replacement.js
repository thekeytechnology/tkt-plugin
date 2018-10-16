
jQuery(document).ready(function ($) {
    if (typeof pbr_list !== 'undefined') {
        pbr_list.forEach(function(pbr_replace_pair){

            var queryParams = decodeURIComponent(window.location.search.substring(1));
            queryParams = queryParams.split('&');

            /* map them from key=value to {key: value} */
            var queryParamsArray = {};
            queryParams.forEach(function(queryParamString) {
                var keyValuePair = queryParamString.split('=');
                queryParamsArray[keyValuePair[0]] = keyValuePair[1];
            });

            if (queryParamsArray[pbr_replace_pair['parameter']] == pbr_replace_pair['value']) {
                var regex = new RegExp(pbr_replace_pair['search'],'g');

                document.body.innerHTML = document.body.innerHTML.replace(regex, pbr_replace_pair['replace']);
            }
        });
    }
});
