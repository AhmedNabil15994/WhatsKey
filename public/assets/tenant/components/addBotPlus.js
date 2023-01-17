$(function(){
	
	var lang = $('html').attr('lang');
	var text = 'Text';
	var newReply = "New Reply";
	var botMsg = "Bot Message";
	var msgContent = "Message Content";
	var choose = "Choose";
	if(lang == 'ar'){
		text = 'النص';
		newReply = 'رسالة جديدة';
		botMsg = "رسالة بوت";
		msgContent = "نص الرسالة";
		choose = "حدد اختيارك";
	}

	$('select[name="buttons"]').on('change',function(e) {
		e.preventDefault();
		e.stopPropagation();

		var buttons = $(this).val();
		if( buttons && buttons > 0 && buttons <= 10){
			var oldItems = $('.buts .mains.buttons').length;
			var result = buttons-oldItems;
			if(result > 0){
				for (var i = 0; i < result; i++) {
					appendButtons(i+1+oldItems);
				}
			}else if(result<0){
				result = Math.abs(result);
				for (var i = 0; i < result; i++) {
					$('.buts').children('.mains.buttons').last().remove()
				}
			}
			
		}
	});

	$('select[name="title_type"]').on('change',function(e) {
		e.preventDefault();
		e.stopPropagation();

		var val = $(this).val();
		if(val == 1){
			$('.imageRow').addClass('hidden')
			$('.textRow').removeClass('hidden')
		}else{
			$('.textRow').addClass('hidden')
			$('.imageRow').removeClass('hidden')
		}	
	});

	$.each($('.kt_dropzone_1'),function(index,item){
		var dropz = $(item).dropzone({
		    url: myURL + "/uploadImage",
		    paramName: "file", // The name that will be used to transfer the file
		    maxFiles: 1,
		    // maxFilesize: 0.1, // MB
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
		    	var dropzone = this;
		        if(data){
		            if(data.status.status != 1){
		                errorNotification(data.status.message);
						dropzone.removeFile(file);		            
					}
		        }
		    },
		});

	});

	function appendButtons(itemIndex) {
		var emojiSrc = $('emoji-picker').attr('data-source');
		var buttonsData = "Button "+itemIndex+" Data ";
		if(lang == 'ar'){
			buttonsData = "بيانات الزر "+itemIndex;
		}
		var myString =  "<div class='form-group mains buttons'>"+
							"<label class='titleLabel'>"+buttonsData+":</label>"+
							"<div class='row'>"+
								"<div class='col-md-4'>" +
									"<div class='form-group textWrap'>"+
										"<input class='form-control' type='text' name='btn_text_"+itemIndex+"' placeholder='"+text+"'>"+
										'<i class="la la-smile icon-xl emoji-icon"></i>'+
										'<emoji-picker class="hidden" locale="en" data-source="'+emojiSrc+'"></emoji-picker>'+
									"</div>"+
								" </div>"+
								"<div class='col-md-4'>" +
									"<select data-toggle='select2' class='reply_types form-control' name='btn_reply_type_"+itemIndex+"'>"+
										"<option value='1' selected>"+newReply+"</option>"+
										"<option value='2'>"+botMsg+"</option>"+
									"</select>"+
								" </div>"+
								"<div class='col-md-4 repy'>" +
									"<div class='form-group textWrap'>"+
										"<textarea class='form-control' name='btn_reply_"+itemIndex+"' placeholder='"+msgContent+"'></textarea>"+
										'<i class="la la-smile icon-xl emoji-icon"></i>'+
										'<emoji-picker class="hidden" locale="en" data-source="'+emojiSrc+'"></emoji-picker>'+
									"</div>"+
									"<select class='hidden form-control dets' name='btn_msg_"+itemIndex+"'>"+
										"<option value='' selected>"+choose+"</optin>"+
										$('select[name="bots"]').html()+
									"</select>"+
									"<input type='hidden' name='btn_msg_type_"+itemIndex+"' value=''>"+
								" </div>"+
							"</div>"+
						"</div>";
		$('.buts').append(myString);
		$('.buts .form-group select[data-toggle="select2"]').select2();
	}

	$(document).on('change','.mains.buttons select.reply_types',function(){
		var itemValue = $(this).val();
		if(itemValue == 1){
			$(this).parents('.mains.buttons').find('.repy').children('.textWrap').removeClass('hidden');
			$(this).parents('.mains.buttons').find('.repy').children('select').select2('destroy');
			$(this).parents('.mains.buttons').find('.repy').children('select').addClass('hidden');
		}else if(itemValue == 2){
			$(this).parents('.mains.buttons').find('.repy').children('.textWrap').addClass('hidden');
			$(this).parents('.mains.buttons').find('.repy').children('select').removeClass('hidden');
			$(this).parents('.mains.buttons').find('.repy').children('select').select2();
		}
		$(this).parent('.col-md-4').siblings('.col-md-4.repy').find($('input[type="hidden"]')).val(itemValue-1);
	});

	$(document).on('change','.mains.buttons select.dets',function(){
		var itemValue = $(this).children("option:selected").data('type');
		if(itemValue){
			$(this).siblings("input[type='hidden']").val(itemValue);
		}
	});

})