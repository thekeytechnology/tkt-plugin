jQuery(document).ready(function ($) {

    function tkGetUrlParameter(name) {
        name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
        var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
        var results = regex.exec(location.search);
        return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
    }


    function tkAddParameterToDataIfSet(param, data) {
        var tmp = tkGetUrlParameter(param);
        if (tmp.length) {
            data[param] = tmp;
        }
        return data;
    }


    function tkB64DecodeUnicode(str) {
        return decodeURIComponent(Array.prototype.map.call(window.atob(str), function (c) {
            return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2)
        }).join(''))
    }


    function tkGetCookieValue(name) {
        var nameEQ = name + "=";
        var cookies = document.cookie.split(";");
        for (var i = 0; i < cookies.length; i++) {
            var temp = cookies[i];
            temp = temp.trim();
            if (temp.indexOf(nameEQ) == 0) {
                return temp.substring(nameEQ.length, temp.length);
            }
        }
        return null;
    }

    function tkSetUPTInputValues(data) {
        $(".tk-upt-input").each(function () {
            $(this).val("");
        });
        var recognizedKeys = [
            "gclid",
            "utm_source",
            "utm_medium",
            "utm_campaign",
            "utm_term",
            "utm_content",
            "campaign",
            "referrer",
            "traffic-source",
            "traffic-source-id"
        ];
        for (var key in data) {
            if (recognizedKeys.includes(key)) {
                $(".tk-upt-" + key).each(function () {
                    $(this).val(data[key]);
                });
            }
        }
    }

    function tkApplySourceBasedReplacements(trafficSourceID) {
        $("[data-tk-sbr-" + trafficSourceID + "]").each(function () {
            if ($(this).is("[href]")) {
                $(this).attr("href", $(this).data("tk-href-sbr-" + trafficSourceID));
            }
            $(this).html($(this).data("tk-sbr-" + trafficSourceID))
        });
    }


    var tkUPTsetCookie = false;
    var tkUPTdata = {};

    tkUPTparam = tkGetUrlParameter("utm_source");
    if (tkUPTparam.length) {
        tkUPTdata["utm_source"] = tkUPTparam;
        tkUPTdata["traffic-source"] = tkUPTparam;
        tkUPTdata["traffic-source-id"] = tkUPTparam;
        tkUPTdata = tkAddParameterToDataIfSet("utm_medium", tkUPTdata);
        tkUPTdata = tkAddParameterToDataIfSet("utm_campaign", tkUPTdata);
        tkUPTdata = tkAddParameterToDataIfSet("utm_term", tkUPTdata);
        tkUPTdata = tkAddParameterToDataIfSet("utm_content", tkUPTdata);
        tkUPTdata["referrer"] = document.referrer;
        tkUPTsetCookie = true;
    }
    var tkUPTparam = tkGetUrlParameter("gclid");
    if (tkUPTparam.length) {
        tkUPTdata["gclid"] = tkUPTparam;

        if ("adwordsTrafficSourceName" in tkUPTOptions) {
            tkUPTdata["traffic-source"] = tkUPTOptions["adwordsTrafficSourceName"];
        } else {
            tkUPTdata["traffic-source"] = "Adwords";
        }
        tkUPTdata["traffic-source-id"] = "google-ads";
        tkUPTdata["referrer"] = document.referrer;
        tkUPTsetCookie = true;
    }

    if (!tkUPTsetCookie) {
        if (document.referrer.indexOf(window.location.hostname) === -1) {
            if (document.referrer.match(/\.google\./gi)) {
                tkUPTdata["traffic-source"] = "organisch";
                tkUPTdata["traffic-source-id"] = "organic";
                tkUPTdata["referrer"] = document.referrer;
            } else if (document.referrer.match(/\.bing\./gi)) {
                tkUPTdata["traffic-source"] = "organisch (bing)";
                tkUPTdata["traffic-source-id"] = "organic-bing";
                tkUPTdata["referrer"] = document.referrer;
            } else if ((undefined !== document.referrer) && ("" !== document.referrer)) {
                tkUPTdata["traffic-source"] = "referral";
                tkUPTdata["traffic-source-id"] = "referral";
                tkUPTdata["referrer"] = document.referrer;
            } else {
                tkUPTdata["traffic-source"] = "direkt";
                tkUPTdata["traffic-source-id"] = "direct";
            }
            tkUPTsetCookie = true;
        }
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
        tkApplySourceBasedReplacements(tkUPTdata["traffic-source-id"]);
    } else {
        var data = tkGetCookieValue("tk-upt");
        if (null !== data) {
            data = JSON.parse(data);
            tkSetUPTInputValues(data);
            tkApplySourceBasedReplacements(data["traffic-source-id"]);
        }
    }
});