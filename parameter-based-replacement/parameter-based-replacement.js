
jQuery(document).ready(function ($) {
    if (typeof pbr_list !== 'undefined') {
        pbr_list.forEach(function(pbr_replace_pair){

            var pageURL = decodeURIComponent(window.location.search.substring(1));
            var queryParams = pageURL.split('&');
            if (queryParams[pbr_replace_pair['parameter']] == pbr_replace_pair['value']) {
                var regex = new RegExp(pbr_replace_pair['search'],'g');

                document.body.innerHTML = document.body.innerHTML.replace(regex, pbr_replace_pair['replace']);
            }
        });
    }
});
