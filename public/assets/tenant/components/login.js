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

    $("#loginForm:not(.checkByCodeForm)").submit(function() {
        var phone = $("#telephone").intlTelInput("getNumber");
        var password = $('input[name="password"]').val();
        if (!$("#telephone").intlTelInput("isValidNumber")) {
            if (lang == "en") {
                errorNotification("This Phone Number Isn't Valid!");
            } else {
                errorNotification("هذا رقم الجوال غير موجود");
            }
        }

        if (password && phone) {
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                }
            });
            if(!$('#loginForm').hasClass('checkByCodeForm')){
                $.ajax({
                    type: "POST",
                    url: "/login",
                    data: {
                        _token: $('meta[name="csrf-token"]').attr("content"),
                        password: password,
                        phone: phone
                    },
                    success: function(data) {
                        if (data.status.status == 1) {
                            successNotification(data.status.message);
                            if (data.status.code == 205) {
                                window.location.href = data.data;
                            } else {
                                $(".codes").removeClass("hidden");
                                $("button.loginBut").addClass("check");
                                $("#loginForm").addClass("checkByCodeForm");
                            }
                        } else {
                            errorNotification(data.status.message);
                        }
                    }
                });
            }else{
                var code = $('input[name="code"]').val();
                $.ajax({
                    type: "POST",
                    url: "/checkLoginCode",
                    data: {
                        _token: $('meta[name="csrf-token"]').attr("content"),
                        code: code
                    },
                    success: function(data) {
                        if (data.status.status == 1) {
                            successNotification(data.status.message);
                            window.location.href = data.data;
                        } else {
                            errorNotification(data.status.message);
                        }
                    }
                });
            }
            
        }
        return false;
    });
});
