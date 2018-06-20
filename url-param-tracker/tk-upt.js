jQuery(document).ready(function ($) {

    function tkGetUrlParameter(name)
    {
        name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
        var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
        var results = regex.exec(location.search);
        return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
    }


    function tkAddParameterToDataIfSet (param, data)
    {
        var tmp = tkGetUrlParameter(param);
        if (tmp.length) {
            data[param] = tmp;
        }
        return data;
    }


    function tkB64DecodeUnicode(str) {
        return decodeURIComponent(Array.prototype.map.call(window.atob(str), function(c) {
            return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2)
        }).join(''))
    }


    var tkUPTsetCookie = false;
    var tkUPTdata = {};

    var tkUPTparam = tkGetUrlParameter("gclid");
    if (tkUPTparam.length) {
        tkUPTdata["gclid"] = tkUPTparam;
        tkUPTsetCookie = true;
    }
    tkUPTparam = tkGetUrlParameter("utm_source");
    if (tkUPTparam.length) {
        tkUPTdata["utm_source"] = tkUPTparam;
        tkUPTdata = tkAddParameterToDataIfSet("utm_medium", tkUPTdata);
        tkUPTdata = tkAddParameterToDataIfSet("utm_campaign", tkUPTdata);
        tkUPTdata = tkAddParameterToDataIfSet("utm_term", tkUPTdata);
        tkUPTdata = tkAddParameterToDataIfSet("utm_content", tkUPTdata);
        tkUPTsetCookie = true;
    }
    if (tkUPTsetCookie) {
        tkUPTparam = tkGetUrlParameter("campaign");
        if (tkUPTparam.length) {
            tkUPTdata["campaign"] = tkB64DecodeUnicode(tkUPTparam);
        }
        var date = new Date();
        date.setTime(date.getTime() + 1000 * 86400 * 30);
        var expires = date.toUTCString();
        document.cookie = "tk-upt=" + JSON.stringify(tkUPTdata) + "; expires=" + expires + "; path=/; domain=." + window.location.hostname;

        $(".tk-upt-input").each(function () {
            $(this).val("");
        });

        var regex = new RegExp(/[^a-zA-Z0-9äÄöÖüÜß\-_\s]/g);
        for (var key in tkUPTdata) {
            if (key === "gclid") {
                $("#tk-upt-traffic-source").val("Adwords");
                $("#tk-upt-gclid").val(tkUPTdata[key].replace(regex, ""));
            } else if (key === "utm_source") {
                $("#tk-upt-traffic-source").val(tkUPTdata[key].replace(regex, ""));
                $("#tk-upt-utm_source").val(tkUPTdata[key].replace(regex, ""));
            } else {
                $("#tk-upt-"+key).val(tkUPTdata[key].replace(regex, ""));
            }
        }
    }
});