$(function(){
   
    var lang = $('html').attr('lang');

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

    $('input[name="domain"]').on('keyup',function(){
        if(!$(this).val()){
            $(this).siblings('p').empty();
        }else{
            $(this).siblings('p').html($(this).val()+'.' + 'whatskey.net')
        }
    });

    $('input[name="domain"]').keypress(function(event){
        var ew = event.which;
        if(48 <= ew && ew <= 57)
            return true;
        if(65 <= ew && ew <= 90)
            return true;
        if(97 <= ew && ew <= 122)
            return true;
        return false;
    });

});