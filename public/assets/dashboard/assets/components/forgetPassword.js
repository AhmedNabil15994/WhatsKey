$(function(){

    var lang = $('html').attr('lang');
    
    $("#telephone").intlTelInput({
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

      $("#getResetPassword").submit(function() {

        var phone =  $("#telephone").intlTelInput("getNumber");

        if (!$("#telephone").intlTelInput("isValidNumber")) {
            if(lang == 'en'){
                errorNotification("This Phone Number Isn't Valid!");
            }else{
                errorNotification("هذا رقم الجوال غير موجود");
            }
        }

        if(phone){
            $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
            $.ajax({
                type: 'POST',
                url: '/resetPassword',
                data:{
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'phone': phone,
                },
                success:function(data){
                    if(data.status.status == 1){
                        successNotification(data.status.message);
                        $('.codes').removeClass('hidden');
                        $('button.loginBut').addClass('check');
                        $('button.loginBut.check').removeClass('loginBut');
                    }else{
                        errorNotification(data.status.message);
                    }
                },
            });
        }
    return false;
    });

      $("#getResetPassword").submit(function() {
     
        var code = $('input[name="code"]').val();        
        if(code){
            $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
            $.ajax({
                type: 'POST',
                url: '/checkResetPassword',
                data:{
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'code': code,
                },
                success:function(data){
                    if(data.status.status == 1){
                        window.location.href = "/changePassword";
                    }else{
                        errorNotification(data.status.message);
                    }
                },
            });
        }
        return false;
    });

});