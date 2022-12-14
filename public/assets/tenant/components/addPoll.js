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

	$('select[name="options"]').on('change',function(e) {
		e.preventDefault();
		e.stopPropagation();

		var buttons = $(this).val();
		if( buttons && buttons > 0 && buttons <= 10){
			var oldItems = $('.polls .mains').length;
			var result = buttons-oldItems;
			if(result > 0){
				for (var i = 0; i < result; i++) {
					appendButtons(i+1+oldItems);
				}
			}else if(result<0){
				result = Math.abs(result);
				for (var i = 0; i < result; i++) {
					$('.polls').children('.mains').last().remove()
				}
			}
			
		}
	});

	function appendButtons(itemIndex) {
		var buttonsData = "Button "+itemIndex+" Data ";
		if(lang == 'ar'){
			buttonsData = "بيانات الزر "+itemIndex;
		}
		var myString =  "<div class='form-group mains'>"+
							"<label class='titleLabel'>"+buttonsData+":</label>"+
							"<div class='row'>"+
								"<div class='col-md-4'>" +
									"<input class='form-control' type='text' name='poll_text_"+itemIndex+"' placeholder='"+text+"'>"+
								" </div>"+
								"<div class='col-md-4'>" +
									"<select data-toggle='select2' class='reply_types form-control' name='poll_reply_type_"+itemIndex+"'>"+
										"<option value='1' selected>"+newReply+"</option>"+
										"<option value='2'>"+botMsg+"</option>"+
									"</select>"+
								" </div>"+
								"<div class='col-md-4 repy'>" +
									"<textarea class='form-control' name='poll_reply_"+itemIndex+"' placeholder='"+msgContent+"'></textarea>"+
									"<select class='hidden form-control dets' name='poll_msg_"+itemIndex+"'>"+
										"<option value='' selected>"+choose+"</optin>"+
										$('select[name="bots"]').html()+
									"</select>"+
									"<input type='hidden' name='poll_msg_type_"+itemIndex+"' value=''>"+
								" </div>"+
							"</div>"+
						"</div>";
		$('.polls').append(myString);
		$('.polls .form-group select[data-toggle="select2"]').select2();
	}

	$(document).on('change','.mains select.reply_types',function(){
		var itemValue = $(this).val();
		if(itemValue == 1){
			$(this).parents('.mains').find('.repy').children('textarea').removeClass('hidden');
			$(this).parents('.mains').find('.repy').children('select').select2('destroy');
			$(this).parents('.mains').find('.repy').children('select').addClass('hidden');
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