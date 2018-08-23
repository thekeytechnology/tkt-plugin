
jQuery(document).ready(function () {
    if (typeof pbr_list !== 'undefined') {
        pbr_list.forEach(function(pbr_replace_pair){


            var regex = new RegExp(pbr_replace_pair[0],'g');

            document.body.innerHTML = document.body.innerHTML.replace(regex, pbr_replace_pair[1]);
        })
    }
});
