$(function(){
	window.livewire.emit('loadDialogs')
	$('#kt_chat_aside .scroll').on('scroll',function(){
		if(this.scrollTop == (this.scrollHeight - this.offsetHeight)) {
	      	$('.spinContainer').removeClass('hidden')
	    	window.livewire.emit('loadMore')
	    }
	});

	Livewire.on('conversationOpened', chat => {
		document.querySelector('emoji-picker').database.close()
    	$('#kt_scrollDown').click()
    	$('[data-toggle="tooltip"]').tooltip()
    	$('.sendMsg textarea').focus()
    });

	$('#kt_scrollDown').on('click',function(){
    	$('#kt_scrollDown').addClass('hidden')
    	$('.scroll-pulls').scrollTop(1000000);
	});

    $('.scroll-pulls').on('scroll',function(){
		if(this.scrollTop == 0) {
	    	window.livewire.emit('loadMoreMsgs')
	    }
	    $('#kt_scrollDown').removeClass('hidden')
	});

    window.livewire.on('showModal', () => {
        $('#listSections').modal('show');
    });

    $(document).on('change','[name="radios"]',function(){
    	if($(this).is(':checked')){
    		$('p.score').html(0);
    		$('.progress-bar').data('aria-valuenow',0);
    		$('.progress-bar').css('width','0%');
    		$(this).parents('.float-left').siblings('div.float-right').children('p.score').html(1);
    		$(this).parents('.w-100').find('.progress-bar').data('aria-valuenow',100);
    		$(this).parents('.w-100').find('.progress-bar').css('width','100%');
    	}
    });

    window.livewire.on('showQuickReplyModal', () => {
    	$('#quickReply input[name="reply"]').prop('checked',false)
        $('#quickReply').modal('show');
    });

    $(document).on('click','.selectReply',function(e){
		e.preventDefault()
		var replyId=  $('#quickReply input[name="reply"]:checked').val()
		window.livewire.emitTo('send-msg','setReply',replyId,1,$('input[name="replyMsgId"]').val())
        $('#quickReply').modal('hide');
	});

	window.livewire.on('showTemplateModal', () => {
    	$('#templateModal input[name="template"]').prop('checked',false)
        $('#templateModal').modal('show');
    });

	$(document).on('click','.selectTemplate',function(e){
		e.preventDefault()
		var replyId=  $('#templateModal input[name="template"]:checked').val()
		window.livewire.emitTo('send-msg','setReply',replyId,2,$('input[name="replyMsgId"]').val())
        $('#templateModal').modal('hide');
	});

	window.livewire.on('showContactModal', () => {
    	$('#contactsModal select').val('')
		$('select[data-toggle="select2"]').select2()
        $('#contactsModal').modal('show');
    });

	$(document).on('click','.selectContact',function(e){
		e.preventDefault()
		var replyId=  $('#contactsModal select option:selected').val()
		window.livewire.emitTo('send-msg','setContact',replyId,$('input[name="replyMsgId"]').val())
        $('#contactsModal').modal('hide');
	});

	window.livewire.on('showMapModal', () => {
        $('#locationModal').modal('show');
	    $('#locationModal #somecomponent').locationpicker({
	        location: {
	            latitude:  21.5362381,
	            longitude: 39.1706268
	        },
	        onchanged: function (currentLocation, radius, isMarkerDropped) {
	            var addressComponents = $(this).locationpicker('map').location.addressComponents;
	            $('#locationModal .selectAddress').attr('data-lat',currentLocation.latitude);
	            $('#locationModal .selectAddress').attr('data-lng',currentLocation.longitude);
	        },
	    });
    });

	$(document).on('click','.selectAddress',function(e){
		e.preventDefault()
		window.livewire.emitTo('send-msg','setLocation', $('#locationModal .selectAddress').data('lat'), $('#locationModal .selectAddress').data('lng'),$('#locationModal input[name="address"]').val(),$('input[name="replyMsgId"]').val())
        $('#locationModal').modal('hide');
	});

	Livewire.on('setMessageText', chat => {
    	$('.sendMsg textarea').val(chat)
    });

	Livewire.on('setMessageContact', chat => {
    	$('.sendMsg textarea').val(chat)
		$('.sendMsg .btn-primary').trigger('click');
    });

	Livewire.on('setMessageLocation', chat => {
    	$('.sendMsg textarea').val(chat)
		$('.sendMsg .btn-primary').trigger('click');
    });

    Livewire.on('playAudio', chat => {
    	new Audio("{{ asset('assets/tenant/swiftly.mp3') }}").play();
    });

    Livewire.on('focusInput', chat => {
    	$('.sendMsg textarea').focus()
    });

    $(document).on('keydown','.sendMsg textarea', function(e) {
		if (e.keyCode == 13) {
			e.preventDefault();
			window.livewire.emitTo('send-msg','sendMsg',$(this).val(),$('.msgReplyHeader').data('id'))
			$(this).val(' ')
			$('.msgReplyHeader').data('id','0')
			$('input[name="replyMsgId"]').val('0')
			$('.msgReplyHeader').slideUp(500)
		}
	});

	$(document).on('click','.sendMsg .btn-primary', function(e) {
		let myEvent = $.Event('keydown');
		myEvent.keyCode = 13;
		$('.sendMsg textarea').trigger(myEvent)
	});				

	$(document).on('click','.replyMsg',function(e){
		let id = $(this).data('id');
    	$('.scroll-pulls').scrollTop($(this).parents('.messages').find('#'+id)[0].offsetTop);
	});

	$(document).on('click','.attachment',function(e){
		$('.msgFile')[0].click()
	});

	window.livewire.on('showMuteModal', ($id,$muted) => {
    	$('#muteModal input[name="duration"]').prop('checked',false)
    	if($muted == 1){
			window.livewire.emitTo('chats','muteChat', $id,0)
    	}else{
    		$('#muteModal .selectDuration').attr('data-id',$id);
        	$('#muteModal').modal('show');
    	}
    });

	$(document).on('click','.selectDuration',function(e){
		e.preventDefault()
		window.livewire.emitTo('chats','muteChat', $(this).data('id'),$('#muteModal input[name="duration"]').val())
        $('#muteModal').modal('hide');
	});

	$(document).on('click','.labelChat',function(){
        let msgId = $(this).data('id');
        let labelText = $(this).data('labels');
        labelText = labelText.indexOf(',') > 0 ? labelText.replace(/,\s*$/, "") : labelText
        let labels = labelText != '' ? JSON.parse("[" + labelText + "]") : '';
        $('#labelsModal select').val(labels)
    	$('#labelsModal .selectLabels').attr('data-id',msgId);
    	$('#labelsModal .selectLabels').attr('data-type',2);
        $('select[data-toggle="select2"]').select2()
        $('#labelsModal').modal('show');
    });

    $(document).on('click','.selectLabels',function(e){
        let msgId = $(this).data('id');
        let type = $(this).data('type');
        e.preventDefault()
        var labels=  $('#labelsModal select').val()
        if(type == 2){
            window.livewire.emitTo('chats','labelChat',msgId,labels)
        }else{
            window.livewire.emitTo('send-msg','labelMsg',msgId,labels)
        }
        $('#labelsModal').modal('hide');
    });

    $(document).on('click','.newGroupItem',function(e){
    	$('.allChats').hide(250);
    	$('.newMessage').hide(250);
    	$('.newGroup').show(250);
    })

    $(document).on('click','.closeNewGroup',function(e){
    	$('.newGroup').hide(250);
    	$('.newMessage').hide(250);
    	$('.allChats').show(250);
    	$('.newGroup input[name="groupName"]').val('')
    	$('.newGroup select[name="numbers"] option[value="1"]').prop('selected',true).trigger('change');
    	$('.newGroup select[name="contactsPhone[]"]').val('').trigger('change');
    	$('.newGroup textarea.form-control').val('')
    	$('.newGroup .form-group[data-id]').hide()
    })

    $(document).on('click','.addNewWAGroup',function(e){
    	let groupName = $('.newGroup input[name="groupName"]').val();
    	let numbers = $('.newGroup select[name="numbers"] option:selected').val();
    	let phones = [];
    	if(numbers == 1){
    		phones = ["0"];
    	}else if(numbers == 2){
    		phones = $('.newGroup select[name="contactsPhone[]"]').val()
    	}else if(numbers == 3){
    		phones = $('.newGroup textarea.form-control').val()
    	}
        window.livewire.emitTo('chat-actions','newGroup',groupName,numbers,JSON.stringify(phones))
    	$('.closeNewGroup').click();
    })

    $(document).on('change','.newGroup select[name="numbers"]',function(){
    	let dataId = $(this).val();
    	if(dataId == 1){
        	$('.newGroup .form-group[data-id]').hide()
    	}else{
        	$('.newGroup .form-group[data-id="'+dataId+'"]').show().siblings('.form-group[data-id]').hide()
    	}
    });

    $(document).on('click','.newMessageItem',function(e){
    	$('.allChats').hide(250);
    	$('.newGroup').hide(250);
    	$('.newMessage').show(250);
    })

    $(document).on('click','.closeNewMessage',function(e){
    	$('.newGroup').hide(250);
    	$('.newMessage').hide(250);
    	$('.allChats').show(250);
    	$('.newMessage [name="message"]').val('')
    	$('.newMessage [name="newPhones"]').val('')
    	$('.newMessage select[name="types"] option[value="1"]').prop('selected',true).trigger('change');
    	$('.newMessage select[name="newContacts[]"]').val('').trigger('change');
    	$('.newMessage select[name="message_type"]').val(1).trigger('change');
    	$('.newMessage .form-group[data-id]').hide()
    	$('.newMessage [data-select]').hide()
    	$('.newMessage [data-select="1"]').show()
    })
    
    $(document).on('change','.newMessage select[name="message_type"]',function(){
    	let dataId = $(this).val();
    	$('.newMessage [data-select="'+dataId+'"]').show().siblings('[data-select]').hide()
    });

    $(document).on('change','.newMessage select[name="types"]',function(){
    	let dataId = $(this).val();
    	if(dataId == ''){
        	$('.newMessage .form-group[data-id]').hide()
    	}else{
        	$('.newMessage .form-group[data-id="'+dataId+'"]').show().siblings('.form-group[data-id]').hide()
    	}
    });

    $(document).on('click','.newMsg-icon',function(){
    	$(this).siblings('emoji-picker').toggleClass('hidden')
    })

    $(document).on('click','.newGroup-icon',function(){
    	$(this).siblings('emoji-picker').toggleClass('hidden')
    })

    $('.newMessage emoji-picker').unbind('emoji-click');
    $('.newMessage emoji-picker').on('emoji-click',event => $('textarea[name="message"]').val($('textarea[name="message"]').val() + event.detail.unicode))

    $('.newGroup emoji-picker').unbind('emoji-click');
    $('.newGroup emoji-picker').on('emoji-click',event => $('input[name="groupName"]').val($('input[name="groupName"]').val() + event.detail.unicode))

    $('.card-body').on('click',function(){
        if(!$('.newMessage emoji-picker').hasClass('hidden')){
            $('.newMessage emoji-picker').addClass('hidden')
            document.querySelector('emoji-picker').database.close()
        }
        if(!$('.newGroup emoji-picker').hasClass('hidden')){
            $('.newGroup emoji-picker').addClass('hidden')
            document.querySelector('emoji-picker').database.close()
        }
    })

    $(document).on('click','.addNewMessage',function(e){
    	e.preventDefault()
    	let phones = [];
    	let numbers = 0;
    	let msg = $('.newMessage textarea[name="message"]').val();
    	if($('.newMessage select[name="message_type"]').val() == 1){
    		if($('#telephone').length && !$('input[name="phone"]').val()){
    			e.preventDefault();
	            e.stopPropagation();
	            var phone = $("#telephone").intlTelInput("getNumber");
	            if (!$("#telephone").intlTelInput("isValidNumber")) {
	                if (lang == "en") {
	                    errorNotification("This Phone Number Isn't Valid!");
	                } else {
	                    errorNotification("هذا رقم الجوال غير موجود");
	                }
	            }else{
	                $('input[name="phone"]').val(phone);
	                phone = phone.replace('+','')
	                phones = [phone];			                
	            }
    		}
	    }else{
	     	numbers = $('.newMessage select[name="types"] option:selected').val();
	    	if(numbers == 1){
    			phones = $('.newMessage select[name="newContacts[]"]').val()
        	}else if(numbers == 2){
        		phones = $('.newMessage textarea[name="newPhones"]').val()
        	}
	    }

	    if(phones && msg){
        	window.livewire.emitTo('chat-actions','newMessage',msg,numbers,JSON.stringify(phones))
        	$('.closeNewMessage').click()
	    }
    })
})