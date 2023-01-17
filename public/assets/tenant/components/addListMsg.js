$(function(){

	$('select[name="sections"]').on('change',function(){
        var sections = $(this).val();
		if( sections && sections > 0 && sections <= 10){
			var oldItems = $('.secs .form-group.mains.lists').length;
			var result = sections-oldItems;
			if(result > 0){
				for (var i = 0; i < result; i++) {
					appendSections(i+1+oldItems);
				}
			}else if(result<0){
				result = Math.abs(result);
				for (var i = 0; i < result; i++) {
					$('.secs').children('.form-group.mains.lists').last().remove()
				}
			}	
		}
    });

    $(document).on('change','select.options',function(){
        var options = $(this).val();
		if( options && options > 0 && options <= 10){
			var oldItems = $(this).parents('.optionsRow').siblings('.items').length;
			var result = options-oldItems;
			if(result > 0){
				for (var i = 0; i < result; i++) {
					appendOptions(i+1+oldItems , $(this).parents('.form-group.mains.lists').index()+1 , $(this).parents('.form-group.mains.lists'));
				}
			}else if(result<0){
				result = Math.abs(result);
				for (var i = 0; i < result; i++) {
					$(this).parents('.optionsRow').siblings('.items').last().remove()
				}
			}
			
		}
    });
	
	var lang = $('html').attr('lang');
	var text = 'Title';
	var urlButton = "URL Button";
	var callButton = "Call Button";
	var normalButton = "Call Button";
	var msgContent = "Message Content";
	var newReply = "New Reply";
	var botMsg = "Bot Message";
	var choose = "Choose";
	var url = "URL ( https )";
	var contact = "Contact Mobile";
	var desc = "Description";
	var options = "Options";
	if(lang == 'ar'){
		text = 'العنوان';
		urlButton = 'زر برابط';
		callButton = "زر اتصال";
		normalButton = "زر عادي";
		msgContent = "نص الرسالة";
		choose = "حدد اختيارك";
		newReply = 'رسالة جديدة';
		botMsg = "رسالة بوت";
		url = "الرابط ( https )";
		contact = "جهة اتصال";
		desc = "الوصف";
		options = "عدد الاختيارات";
	}

	function appendSections(itemIndex) {
		var buttonsData = "Section "+itemIndex+" Data ";
		var itemData = "Item "+1+" Data ";
		var emojiSrc = $('emoji-picker').attr('data-source');
		if(lang == 'ar'){
			buttonsData = "بيانات القسم "+itemIndex;
			itemData = "بيانات العنصر "+1;
		}					
		var myString =  '<div class="form-group mains lists">'+
							'<label>'+buttonsData+'</label>'+
	       					'<div class="optionsRow">'+
	       						'<div class="row">'+
	       							'<div class="col-md-6">'+
		       							'<div class="form-group textWrap">'+
	                                        '<label>'+text+'</label>'+
	       									'<input class="form-control" type="text" name="title_'+itemIndex+'" value="" placeholder="'+text+'">'+
	                                    	'<i class="la la-smile icon-xl emoji-icon"></i>'+
											'<emoji-picker class="hidden" locale="en" data-source="'+emojiSrc+'"></emoji-picker>'+
	                                    '</div>'+
	       							'</div>'+
	       							'<div class="col-md-6">'+
		       							'<div class="form-group">'+
		       								'<label>'+options+'</label>'+
		       								'<select data-toggle="select2" class="options form-control" name="options_'+itemIndex+'">'+
		       									'<option value="1">1</option>'+
		       									'<option value="2">2</option>'+
		       									'<option value="3">3</option>'+
		       									'<option value="4">4</option>'+
		       									'<option value="5">5</option>'+
		       									'<option value="6">6</option>'+
		       									'<option value="7">7</option>'+
		       									'<option value="8">8</option>'+
		       									'<option value="9">9</option>'+
		       									'<option value="10">10</option>'+
		       								'</select>'+
	       								'</div>'+
	       							'</div>'+
	       						'</div>'+
	       					'</div>'+
	       					

	       					'<div class="clearfix"></div>'+
	       					'<div class="row items item_'+itemIndex+'_1">' +
					            '<label class="w-100" style="padding: 15px;">'+itemData+' :</label>'+
					            '<div class="row repy w-100">'+
					            	'<input type="hidden" name="btn_msg_type_'+itemIndex+'_1" value="">'+
					            	'<div class="col-md-3">'+
					            		'<div class="form-group textWrap">'+
					            			'<input class="form-control" type="text" name="item_title_'+itemIndex+'_1" placeholder="'+text+'">'+
		                					'<i class="la la-smile icon-xl emoji-icon"></i>'+
											'<emoji-picker class="hidden" locale="en" data-source="'+emojiSrc+'"></emoji-picker>'+
					            		'</div>'+
	                				'</div>'+
					            	'<div class="col-md-3">'+
					            		'<div class="form-group textWrap">'+
		                					'<textarea class="form-control" name="item_description_'+itemIndex+'_1" placeholder="'+desc+'" maxlength="140"></textarea>'+
		                					'<i class="la la-smile icon-xl emoji-icon"></i>'+
											'<emoji-picker class="hidden" locale="en" data-source="'+emojiSrc+'"></emoji-picker>'+
		                				'</div>'+
		                			'</div>'+
					            	'<div class="col-md-3">'+
		                				'<select data-toggle="select2" class="reply_types form-control" name="item_reply_type_'+itemIndex+'_1">'+
		                					'<option value="1" selected>'+newReply+'</option>'+
		                					'<option value="2">'+botMsg+'</option>'+
		                				'</select>'+
	                				'</div>'+
					            	'<div class="col-md-3">'+
					            		'<div class="form-group textWrap textReply">'+
		                					'<textarea name="btn_reply_'+itemIndex+'_1" class="replyText form-control" placeholder="'+msgContent+'" maxlength="140"></textarea>'+
	                						'<i class="la la-smile icon-xl emoji-icon"></i>'+
											'<emoji-picker class="hidden" locale="en" data-source="'+emojiSrc+'"></emoji-picker>'+
	                					'</div>'+
						            	'<select data-toggle="" class="form-control dets hidden" name="btn_msg_'+itemIndex+'_1">'+
						            		'<option value="" selected>'+choose+'</option>'+
						            		$('select[name="bots"]').html()+
				 	 	 	            '</select>'+
	                				'</div>'+
					            '</div>'+
	        				'</div><hr>'+
        				'</div>';

		$('.secs').append(myString);
		$('.secs .row select[data-toggle="select2"]').select2();
	}

	function appendOptions(itemIndex,parentIndex,parentItem) {
		var itemData = "Item "+itemIndex+" Data ";
		var emojiSrc = $('emoji-picker').attr('data-source');
		if(lang == 'ar'){
			itemData = "بيانات العنصر "+itemIndex;
		}					
		var myString =  '<div class="row items item_'+parentIndex+'_'+itemIndex+'">' +
				            '<label class="w-100" style="padding: 15px;">'+itemData+' :</label>'+
				            '<div class="row repy w-100">'+
				            	'<input type="hidden" name="btn_msg_type_'+parentIndex+'_'+itemIndex+'" value="">'+
				            	'<div class="col-md-3">'+
				            		'<div class="form-group textWrap">'+
				            			'<input class="form-control" type="text" name="item_title_'+parentIndex+'_'+itemIndex+'" placeholder="'+text+'">'+
                						'<i class="la la-smile icon-xl emoji-icon"></i>'+
										'<emoji-picker class="hidden" locale="en" data-source="'+emojiSrc+'"></emoji-picker>'+
                					'</div>'+
                				'</div>'+
				            	'<div class="col-md-3">'+
				            		'<div class="form-group textWrap">'+
                						'<textarea class="form-control" name="item_description_'+parentIndex+'_'+itemIndex+'" placeholder="'+desc+'" maxlength="140"></textarea>'+
                						'<i class="la la-smile icon-xl emoji-icon"></i>'+
										'<emoji-picker class="hidden" locale="en" data-source="'+emojiSrc+'"></emoji-picker>'+
                					'</div>'+
                				'</div>'+
				            	'<div class="col-md-3">'+
	                				'<select data-toggle="select2" class="reply_types form-control" name="item_reply_type_'+parentIndex+'_'+itemIndex+'">'+
	                					'<option value="1" selected>'+newReply+'</option>'+
	                					'<option value="2">'+botMsg+'</option>'+
	                				'</select>'+
                				'</div>'+
				            	'<div class="col-md-3">'+
				            		'<div class="form-group textWrap textReply">'+
	                					'<textarea name="btn_reply_'+parentIndex+'_'+itemIndex+'" class="replyText form-control" placeholder="'+msgContent+'" maxlength="140"></textarea>'+
                						'<i class="la la-smile icon-xl emoji-icon"></i>'+
										'<emoji-picker class="hidden" locale="en" data-source="'+emojiSrc+'"></emoji-picker>'+
                					'</div>'+
					            	'<select data-toggle="" class="form-control dets hidden" name="btn_msg_'+parentIndex+'_'+itemIndex+'">'+
					            		'<option value="" selected>'+choose+'</option>'+
					            		$('select[name="bots"]').html()+
			 	 	 	            '</select>'+
                				'</div>'+
				            '</div>'+
        				'</div><hr>';

		parentItem.append(myString);
		$('.secs .row select[data-toggle="select2"]').select2();
	}

	$(document).on('change','.items select.reply_types',function(){
		var itemValue = $(this).val();
		if(itemValue == 1){
			$(this).parents('.items').find('.repy').children('.col-md-3').find('.form-group.textWrap.textReply').removeClass('hidden');
			$(this).parents('.items').find('.repy').children('.col-md-3').find('select').not('.reply_types').select2('destroy');
			$(this).parents('.items').find('.repy').children('.col-md-3').find('select').not('.reply_types').addClass('hidden');
		}else if(itemValue == 2){
			$(this).parents('.items').find('.repy').children('.col-md-3').find('.form-group.textWrap.textReply').addClass('hidden');
			$(this).parents('.items').find('.repy').children('.col-md-3').find('select').removeClass('hidden');
			$(this).parents('.items').find('.repy').children('.col-md-3').find('select').select2();
		}
		$(this).parents('.repy').find($('input[type="hidden"]')).val(itemValue-1);
	});

	$(document).on('change','.mains.lists select.dets',function(){
		var itemValue = $(this).children("option:selected").data('type');
		if(itemValue){
			$(this).parents('.repy').find($('input[type="hidden"]')).val(itemValue);
		}
	});

})