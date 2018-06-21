jQuery(document).ready(function ($) {

    function tkGetUrlParameter (name)
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


    function tkB64DecodeUnicode (str)
    {
        return decodeURIComponent(Array.prototype.map.call(window.atob(str), function(c) {
            return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2)
        }).join(''))
    }


    function tkGetCookieValue (name)
    {
        var nameEQ = name + "=";
        var cookies = document.cookie.split(";");
        for(var i = 0; i < cookies.length; i++) {
            var temp = cookies[i];
            temp = temp.trim();
            if (temp.indexOf(nameEQ) == 0) {
                return temp.substring(nameEQ.length, temp.length);
            }
        }
        return null;
    }

    function tkSetUPTInputValues (data)
    {
        $(".tk-upt-input").each(function () {
            $(this).val("");
        });
        var recognizedParams = [
            "gclid",
            "utm_source",
            "utm_medium",
            "utm_campaign",
            "utm_term",
            "utm_content",
            "campaign"
        ];
        var regex = new RegExp(/[^a-zA-Z0-9äÄöÖüÜß\-_\s]/g);
        for (var key in data) {
            if (recognizedParams.includes(key)) {
                if (key === "gclid") {
                    $(".tk-upt-traffic-source").each(function () {
                        $(this).val("Adwords");
                    });
                    $(".tk-upt-gclid").each(function () {
                        $(this).val(data[key].replace(regex, ""));
                    });
                } else if (key === "utm_source") {
                    $(".tk-upt-traffic-source").each(function () {
                        $(this).val(data[key].replace(regex, ""));
                    });
                    $(".tk-upt-utm_source").each(function () {
                        $(this).val(data[key].replace(regex, ""));
                    });
                } else {
                    $(".tk-upt-"+key).each(function () {
                        $(this).val(data[key].replace(regex, ""));
                    });
                }
            }
        }
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

        tkSetUPTInputValues(tkUPTdata);
    } else {
        var data = tkGetCookieValue("tk-upt");
        if (null !== data) {
            tkSetUPTInputValues(JSON.parse(data));
        }
    }
});