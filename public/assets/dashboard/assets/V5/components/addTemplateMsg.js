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

	$('select[name="buttons"]').on('change',function(e) {
		e.preventDefault();
		e.stopPropagation();

		var buttons = $(this).val();
		if( buttons && buttons > 0 && buttons <= 10){
			var oldItems = $('.buts .row.mains').length;
			var result = buttons-oldItems;
			if(result > 0){
				for (var i = 0; i < result; i++) {
					appendButtons(i+1+oldItems);
				}
			}else if(result<0){
				result = Math.abs(result);
				for (var i = 0; i < result; i++) {
					$('.buts').children('.row.mains').last().remove()
				}
			}
			
		}
	});

	function appendButtons(itemIndex) {
		var buttonsData = "Button "+itemIndex+" Data ";
		if(lang == 'ar'){
			buttonsData = "بيانات الزر "+itemIndex;
		}
		var myString =  "<div class='row mains'>"+
							"<div class='col-md-3'>"+
                                "<label class='titleLabel'>"+buttonsData+":</label>"+
							"</div>"+
							"<div class='col-md-9'>"+
								"<div class='row'>"+
									"<div class='col-md-3'>" +
										"<input type='text' name='btn_text_"+itemIndex+"' placeholder='"+text+"'>"+
									" </div>"+
									"<div class='col-md-3'>" +
										"<select data-toggle='select2' class='button_types' name='btn_type_"+itemIndex+"'>"+
											"<option value='1' selected>"+urlButton+"</option>"+
											"<option value='2'>"+callButton+"</option>"+
											"<option value='3'>"+normalButton+"</option>"+
										"</select>"+
									" </div>"+
									"<div class='col-md-6 repy'>" +
                                        "<input type='text' name='url_"+itemIndex+"' placeholder='"+url+"'>"+
                                        "<select data-toggle='' class='reply_types hidden' name='btn_reply_type_"+itemIndex+"'>"+
											"<option value='1' selected>"+newReply+"</option>"+
											"<option value='2'>"+botMsg+"</option>"+
										"</select>"+
										"<textarea name='btn_reply_"+itemIndex+"' class='hidden' placeholder='"+msgContent+"'></textarea>"+
										"<select class='hidden dets' name='btn_msg_"+itemIndex+"'>"+
											"<option value='' selected>"+choose+"</optin>"+
											$('select[name="bots"]').html()+
										"</select>"+
										"<input type='hidden' name='btn_msg_type_"+itemIndex+"' value=''>"+
									" </div>"+
								"</div>"+
							"</div>"+
						"</div>";
		$('.buts').append(myString);
		$('.buts .row select[data-toggle="select2"]').select2();
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
			$(this).parents('.mains').find('.repy').children('textarea').addClass('hidden');
			$(this).parents('.mains').find('.repy').children('input[type="text"]').removeClass('hidden').attr('placeholder',url);
			var oldAttr = $(this).parents('.mains').find('.repy').children('input[type="text"]').attr('name');
			var newAttr = oldAttr.replace('contact_','url_');
			$(this).parents('.mains').find('.repy').children('input[type="text"]').attr('name',newAttr);
			if($(this).parents('.mains').find('.repy').children('select.dets').data('select2')){
				$(this).parents('.mains').find('.repy').children('select.dets').select2('destroy');
			}
		}else if(itemValue == 2){
			$(this).parents('.mains').find('.repy').children('textarea').addClass('hidden');
			$(this).parents('.mains').find('.repy').children('input[type="text"]').removeClass('hidden').attr('placeholder',contact);
			var oldAttr = $(this).parents('.mains').find('.repy').children('input[type="text"]').attr('name');
			var newAttr = oldAttr.replace('url_','contact_');
			$(this).parents('.mains').find('.repy').children('input[type="text"]').attr('name',newAttr);
			if($(this).parents('.mains').find('.repy').children('select.dets').data('select2')){
				$(this).parents('.mains').find('.repy').children('select.dets').select2('destroy');
			}
		}else if(itemValue == 3){
			$(this).parents('.mains').find('.repy').children('input[type="text"]').addClass('hidden');
			$(this).parents('.mains').find('.repy').children('textarea').removeClass('hidden');
			$(this).parents('.mains').find('.repy').children('select').not('.dets').removeClass('hidden');
			$(this).parents('.mains').find('.repy').children('select').not('.dets').select2();
		}
		
		$(this).parent('.col-md-4').siblings('.col-md-4.repy').find($('input[type="hidden"]')).val(itemValue-1);
	});
	

	$(document).on('change','.mains select.reply_types',function(){
		var itemValue = $(this).val();
		if(itemValue == 1){
			$(this).parents('.mains').find('.repy').children('textarea').removeClass('hidden');
			$(this).parents('.mains').find('.repy').children('select').not('.reply_types').select2('destroy');
			$(this).parents('.mains').find('.repy').children('select').not('.reply_types').addClass('hidden');
		}else if(itemValue == 2){
			$(this).parents('.mains').find('.repy').children('textarea').addClass('hidden');
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