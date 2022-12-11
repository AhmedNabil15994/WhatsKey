$("input[name='emergency_number']").intlTelInput({
    initialCountry: "auto",
    geoIpLookup: function(success, failure) {
        $.get("https://ipinfo.io", function() {}, "jsonp").always(function(
            resp
        ) {
            var countryCode = resp && resp.country ? resp.country : "sa";
            success(countryCode);
        });
    },
    preferredCountries: ["sa", "ae", "bh", "kw", "om", "eg"]
});