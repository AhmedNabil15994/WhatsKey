$(function(){
	Dropzone.options.myAwesomeDropzone = false;
	Dropzone.autoDiscover = false;
	
	$("#telephone1,#telephone2").intlTelInput({
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

	$("form").submit(function(e) {
	    if($('#telephone1').length && !$('input[name="phone1"]').val() && !$('div.reply[data-id="9"]').hasClass('hidden')){
	        e.preventDefault();
            e.stopPropagation();
            var phone = $("#telephone1").intlTelInput("getNumber");
            if (!$("#telephone1").intlTelInput("isValidNumber")) {
                if (lang == "en") {
                    errorNotification("This Phone Number Isn't Valid!");
                } else {
                    errorNotification("هذا رقم الجوال غير موجود");
                }
            }else{
                $('input[name="phone1"]').val(phone);
                $(this).submit();
            }
	    }
	    if($('#telephone2').length && !$('input[name="phone2"]').val() && !$('div.reply[data-id="11"]').hasClass('hidden')){
	        e.preventDefault();
            e.stopPropagation();
            var phone = $("#telephone2").intlTelInput("getNumber");
            if (!$("#telephone2").intlTelInput("isValidNumber")) {
                if (lang == "en") {
                    errorNotification("This Phone Number Isn't Valid!");
                } else {
                    errorNotification("هذا رقم الجوال غير موجود");
                }
            }else{
                $('input[name="phone2"]').val(phone);
                $(this).submit();
            }
	    }
	});


	$.each($('.reply .kt_dropzone_bot_1'),function(index,item){
		var dateID = $(this).parents('.reply').data('id');
		$(item).dropzone({
		    url: myURL + "/uploadImage/"+dateID,
		    paramName: "file", // The name that will be used to transfer the file
		    maxFiles: 1,
		    maxFilesize: 10, // MB
		    addRemoveLinks: true,
		    // previewTemplate: $('#uploadPreviewTemplate').html(),
		    accept: function(file, done) {
		        if (file.name == "justinbieber.jpg") {
		            done("Naha, you don't.");
		        } else {
		            done();
		        }
		    },
		    success:function(file,data){
		        if(data){
		            if(data.status.status != 1){
		                errorNotification(data.status.message);
		            }
		        }
		    },
		});

	});

	$.each($('.reply .kt_dropzone_bot_2'),function(index,item){
		url = myURL.substr(0,myURL.lastIndexOf('/'))
		var dateID = $(this).parents('.reply').data('id');
		$(item).dropzone({
		    url: url + "/editImage/"+dateID,
		    paramName: "file", // The name that will be used to transfer the file
		    maxFiles: 1,
		    maxFilesize: 10, // MB
		    addRemoveLinks: true,
		    accept: function(file, done) {
		        if (file.name == "justinbieber.jpg") {
		            done("Naha, you don't.");
		        } else {
		            done();
		        }
		    },
		    success:function(file,data){
		        if(data){
		            if(data.status.status != 1){
		                errorNotification(data.status.message);
		            }
		        }
		    },
		});

	});


	$('a.DeletePhotoBot').on('click',function(e){
	    e.preventDefault();
	    e.stopPropagation();
	    var id = $(this).data('area');
	    var myType = $(this).data('type');
	    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
	    $.ajax({
	        type: 'POST',
	        url: myURL+'/deleteImage',
	        data:{
	            '_token': $('meta[name="csrf-token"]').attr('content'),
	            'id': id,
	            'type': myType,
	        },
	        success:function(data){
	            if(data.status.status == 1){
	                successNotification(data.status.message);
	                $('#my-preview').remove();
	            }else{
	                errorNotification(data.status.message);
	            }
	        },
	    });
	});

	$('select[name="reply_type"]').on('change',function(){
		var dateID = $(this).val();
		$('input[name="reply"]').val('');
		$('.reply[data-id="'+dateID+'"].hidden').removeClass('hidden');
		$('.reply[data-id="'+dateID+'"]').siblings('.reply').addClass('hidden');
	});

})