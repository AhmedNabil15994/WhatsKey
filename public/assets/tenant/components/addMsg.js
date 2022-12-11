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

	var lang = $('html').attr('lang');
	if(lang == 'en'){
		var myNow = 'Now';
		var groupVar = 'Group';
		var senderVar = 'Sender';
		var messageVar = 'Message';
		var countVar = 'Messages Count';
		var sendTimeVar = 'Sending Time';
		var sendVar = 'Send';
		var titleVar = "Confirm sending Message(s)";
		var backVar = "Back";
		var contactsVar = 'Contacts';
		var newContactsVar = 'New Contacts';
	}else{
		var myNow = 'الآن';
		var groupVar = 'المجموعة';
		var senderVar = 'الراسل';
		var messageVar = 'الرسالة';
		var countVar = 'عدد الرسائل';
		var sendTimeVar = 'تاريخ الراسل';
		var sendVar = 'ارسال';
		var titleVar = "تأكيد ارسال الرسائل";
		var backVar = "الرجوع";
		var contactsVar = 'جهات الارسال';
		var newContactsVar = 'جهات ارسال جديدة';
	}

	$('select[name="message_type"]').on('change',function(){
		var dateID = $(this).val();
		$('input[name="reply"]').val('');
		$('.reply[data-id="'+dateID+'"].hidden').removeClass('hidden');
		$('.reply[data-id="'+dateID+'"]').siblings('.reply').addClass('hidden');
	});

	$('input[name="sending"]').on('change',function(){
		$("input[name='date']").toggleClass('hidden');
	});
	$('#kt_datetimepicker_5').datetimepicker({
		format:'YYYY-MM-DD HH:mm'
	});

	$(document).on('click','.alert-modal .btn-info',function(e){
 		e.preventDefault();
 		$('.alert-modal').modal('hide');
 		setTimeout(function(){
	 		$('#tipsModal').modal('show');
 		},500);
 	});


	$("button[type='submit']").click(function(e){
		e.preventDefault();
		e.stopPropagation();
		
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
                $('.groupMsgForm').submit();
            }
	    }else if($('#telephone2').length && !$('input[name="phone2"]').val() && !$('div.reply[data-id="11"]').hasClass('hidden')){
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
                $('.groupMsgForm').submit();
            }
	    }else{
            $('.groupMsgForm').submit();
	    }
	});

})