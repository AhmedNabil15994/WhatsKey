$(function() {
    var lang = $("html").attr("lang");

    $("#telephone").intlTelInput({
        initialCountry: "auto",
        utilsScript: "assets/plugins/intlUtils.js",
        geoIpLookup: function(success, failure) {
            $.get("https://ipinfo.io", function() {}, "jsonp").always(function(
                resp
            ) {
                var countryCode = resp && resp.country ? resp.country : "sa";
                success(countryCode);
            });
        },
        preferredCountries: ["sa","eg", "ae", "bh", "kw", "om"]
    });

    $("form").submit(function() {
        
        var phone = $("#telephone").intlTelInput("getNumber");

        if (!$("#telephone").intlTelInput("isValidNumber")) {
            if (lang == "en") {
                errorNotification("This Phone Number Isn't Valid!");
            } else {
                errorNotification("هذا رقم الجوال غير موجود");
            }
        }

        if (phone) {
            $('input[name="phone"]').val(phone);
            $("form").submit();
            return true;
        }
        return false;
    });
});
