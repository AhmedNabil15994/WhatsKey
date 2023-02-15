$(function(){
		
	var lang = $('html').attr('lang');
	var text = 'Text';
	var urlButton = "URL Button";
	var callButton = "Call Button";
	var normalButton = "Call Button";
	var msgContent = "Message Content";
	var newReply = "New Reply";
	var botMsg = "Bot Message";
	var choose = "Choose";
	var url = "URL ( https )";
	var contact = "Contact Mobile";
	if(lang == 'ar'){
		text = 'النص';
		urlButton = 'زر برابط';
		callButton = "زر اتصال";
		normalButton = "زر عادي";
		msgContent = "نص الرسالة";
		choose = "حدد اختيارك";
		newReply = 'رسالة جديدة';
		botMsg = "رسالة بوت";
		url = "الرابط ( https )";
		contact = "جهة اتصال";
	}

	$('select.buttons').on('change',function(e) {
		e.preventDefault();
		e.stopPropagation();

		var buttons = $(this).val();
		if( buttons && buttons > 0 && buttons <= 10){
			var oldItems = $('.templates .templateMsgs.mains').length;
			var result = buttons-oldItems;
			if(result > 0){
				for (var i = 0; i < result; i++) {
					appendButtons(i+1+oldItems);
				}
			}else if(result<0){
				result = Math.abs(result);
				for (var i = 0; i < result; i++) {
					$('.templates').children('.templateMsgs.mains').last().remove()
				}
			}
			
		}
	});

	function appendButtons(itemIndex) {
		var emojiSrc = $('emoji-picker').attr('data-source');
		var buttonsData = "Button "+itemIndex+" Data ";
		if(lang == 'ar'){
			buttonsData = "بيانات الزر "+itemIndex;
		}
		var myString =  "<div class='form-group templateMsgs mains mb-0'>"+
                            "<label class='titleLabel'>"+buttonsData+":</label>"+
							"<div class='row'>"+
								"<div class='col-md-4'>" +
									'<div class="form-group textWrap">'+
										"<input type='text' name='btn_text_"+itemIndex+"' class='form-control' placeholder='"+text+"'>"+
										'<i class="la la-smile icon-xl emoji-icon"></i>'+
										'<emoji-picker class="hidden" locale="en" data-source="'+emojiSrc+'"></emoji-picker>'+
									" </div>"+
								" </div>"+
								
								"<div class='col-md-4'>" +
									"<select data-toggle='select2' class='button_types form-control' name='btn_type_"+itemIndex+"'>"+
										"<option value='1' selected>"+urlButton+"</option>"+
										"<option value='2'>"+callButton+"</option>"+
										"<option value='3'>"+normalButton+"</option>"+
									"</select>"+
								" </div>"+

								"<div class='col-md-4 repy'>" +
									'<div class="form-group textWrap input">'+
                                    	"<input type='text' class='form-control' name='url_"+itemIndex+"' placeholder='"+url+"'>"+
                                    	'<i class="la la-smile icon-xl emoji-icon"></i>'+
										'<emoji-picker class="hidden" locale="en" data-source="'+emojiSrc+'"></emoji-picker>'+
									" </div>"+

                                    "<select data-toggle='' class='reply_types form-control hidden' name='btn_reply_type_"+itemIndex+"'>"+
										"<option value='1' selected>"+newReply+"</option>"+
										"<option value='2'>"+botMsg+"</option>"+
									"</select>"+

									'<div class="form-group textWrap mt-3 textarea hidden">'+
										"<textarea name='btn_reply_"+itemIndex+"' class='form-control' placeholder='"+msgContent+"'></textarea>"+
										'<i class="la la-smile icon-xl emoji-icon"></i>'+
										'<emoji-picker class="hidden" locale="en" data-source="'+emojiSrc+'"></emoji-picker>'+
									" </div>"+

									"<select class='hidden dets form-control mt-3' name='btn_msg_"+itemIndex+"'>"+
										"<option value='' selected>"+choose+"</optin>"+
										$('select[name="bots"]').html()+
									"</select>"+
									"<input type='hidden' name='btn_msg_type_"+itemIndex+"' value=''>"+
								" </div>"+
							"</div>"+
						"</div>";
		$('.templates').append(myString);
		$('.templates .row select[data-toggle="select2"]').select2();
	}

	$(document).on('change','.mains select.button_types',function(){
		var itemValue = $(this).val();
		if(itemValue == 3){
			$(this).parents('.mains').find('.repy').children('select.reply_types').val(1);
		}

		if($(this).parents('.mains').find('.repy').children('select').hasClass('select2-hidden-accessible')){			
			$(this).parents('.mains').find('.repy').children('select').addClass('hidden');
			$(this).parents('.mains').find('.repy').children('select').not('.dets').select2('destroy');
		}

		
		if(itemValue == 1){
			$(this).parents('.mains').find('.repy').children('.textarea').addClass('hidden');
			$(this).parents('.mains').find('.repy').children('.input').removeClass('hidden').children('input[type="text"]').attr('placeholder',url);
			var oldAttr = $(this).parents('.mains').find('.repy').children('.input').children('input[type="text"]').attr('name');
			var newAttr = oldAttr.replace('contact_','url_');
			$(this).parents('.mains').find('.repy').children('.input').children('input[type="text"]').attr('name',newAttr);
			if($(this).parents('.mains').find('.repy').children('select.dets').data('select2')){
				$(this).parents('.mains').find('.repy').children('select.dets').select2('destroy');
			}
		}else if(itemValue == 2){
			$(this).parents('.mains').find('.repy').children('.textarea').addClass('hidden');
			$(this).parents('.mains').find('.repy').children('.input').removeClass('hidden').children('input[type="text"]').attr('placeholder',contact);
			var oldAttr = $(this).parents('.mains').find('.repy').children('.input').children('input[type="text"]').attr('name');
			var newAttr = oldAttr.replace('url_','contact_');
			$(this).parents('.mains').find('.repy').children('.input').children('input[type="text"]').attr('name',newAttr);
			if($(this).parents('.mains').find('.repy').children('select.dets').data('select2')){
				$(this).parents('.mains').find('.repy').children('select.dets').select2('destroy');
			}
		}else if(itemValue == 3){
			$(this).parents('.mains').find('.repy').children('.input').addClass('hidden');
			$(this).parents('.mains').find('.repy').children('.textarea').removeClass('hidden');
			$(this).parents('.mains').find('.repy').children('select').not('.dets').removeClass('hidden');
			$(this).parents('.mains').find('.repy').children('select').not('.dets').select2();
		}
		
		$(this).parent('.col-md-4').siblings('.col-md-4.repy').find($('input[type="hidden"]')).val(itemValue-1);
	});
	

	$(document).on('change','.mains select.reply_types',function(){
		var itemValue = $(this).val();
		if(itemValue == 1){
			$(this).parents('.mains').find('.repy').children('.textarea').removeClass('hidden');
			$(this).parents('.mains').find('.repy').children('select').not('.reply_types').select2('destroy');
			$(this).parents('.mains').find('.repy').children('select').not('.reply_types').addClass('hidden');
		}else if(itemValue == 2){
			$(this).parents('.mains').find('.repy').children('.textarea').addClass('hidden');
			$(this).parents('.mains').find('.repy').children('select').removeClass('hidden');
			$(this).parents('.mains').find('.repy').children('select').select2();
		}
		$(this).parent('.col-md-4').siblings('.col-md-4.repy').find($('input[type="hidden"]')).val(itemValue-1);
	});

	$(document).on('change','.mains select.dets',function(){
		var itemValue = $(this).children("option:selected").data('type');
		if(itemValue){
			$(this).siblings("input[type='hidden']").val(itemValue);
		}
	});

})