$(function(){
	window.livewire.emit('loadDialogs')
	$('#kt_chat_aside .scroll').on('scroll',function(){
		if(this.scrollTop == (this.scrollHeight - this.offsetHeight)) {
	      	$('.spinContainer').removeClass('hidden')
			setTimeout(function(){
	    		window.livewire.emit('loadMore')
			},500)
	    }
	});

	Livewire.on('incomingCall', call => {
		$('#callModal .modal-title').html(call.title)
		$('#callModal img').attr('src',call.image)
		$('#callModal .callText').html(call.text)
		$('#callModal .callName').html(call.name)
		$('#callModal .rejectCall').attr('data-id',call.callId);
        $('#callModal').modal('show');
    });

	$(document).on('click','#callModal .rejectCall',function(e){
		e.preventDefault();
		let callId = $(this).data('id');
		window.livewire.emitTo('conversation','rejectCall',callId)
	})

    Livewire.on('closeCall', chat => {
        $('#callModal').modal('hide');
       	errorNotification('Call Has been ended !!!')
    });

	document.querySelector('emoji-picker').database.close()
	Livewire.on('conversationOpened', chat => {
    	$('#kt_scrollDown').click()
    	$('[data-toggle="tooltip"]').tooltip()
    	$('.sendMsg textarea').focus()
    });

	$('#kt_scrollDown').on('click',function(){
    	$(this).addClass('hidden')
    	$('.scroll-pulls').scrollTop(1000000);
    	document.querySelector('emoji-picker').database.close()
	});

    $('.scroll-pulls').on('scroll',function(){
		if(this.scrollTop == 0) {
			$('.spinMsgContainer').removeClass('hidden')
			setTimeout(function(){
	    		window.livewire.emit('loadMoreMsgs')
			},500)
	    }

	    if($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight){
    		$('#kt_scrollDown').addClass('hidden')
	    }else{
    		$('#kt_scrollDown').removeClass('hidden')
	    }
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

    window.livewire.on('updatePresence',data=>{
    	if(data['presence'] != ''){
    		$('.chatItem[data-id="'+data['chatId']+'"]').find('.chatMsg').hide();
	    	$('.chatItem[data-id="'+data['chatId']+'"]').find('.chatPresence').html(data['presence']);
	    	$('.chatItem[data-id="'+data['chatId']+'"]').find('.chatPresence').show();
    	}else{
	    	$('.chatItem[data-id="'+data['chatId']+'"]').find('.chatPresence').html('');
	    	$('.chatItem[data-id="'+data['chatId']+'"]').find('.chatPresence').hide();
    		$('.chatItem[data-id="'+data['chatId']+'"]').find('.chatMsg').show();
    	}
    })

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
    	new Audio("assets/tenant/swiftly.mp3").play();
    });

    Livewire.on('focusInput', chat => {
    	$('.sendMsg textarea').focus()
    });

    Livewire.on('refreshDesign', chat => {
		document.querySelector('emoji-picker').database.close()
    	$('[data-toggle="tooltip"').tooltip();
    	$('[data-toggle="select2"]').select2()
		var avatar5 = new KTImageInput('kt_image_5');
		const demo = document.querySelector('.scroll-pulld');
		if(demo){
			const ps = new PerfectScrollbar(demo);
			ps.update()
		}
		if($('.sendMsg textarea').length){
    		$('.sendMsg textarea').focus()
    		$('#kt_scrollDown').click()
		}
    });        

    Livewire.on('errorMsg', error => {
    	errorNotification(error)
    });

    $(document).on('keydown','.sendMsg textarea', function(e) {
		if (e.keyCode == 13) {
			e.preventDefault();

			var btn = KTUtil.getById("kt_btn_1");
		   	KTUtil.btnWait(btn, "spinner spinner-right spinner-white pr-15", "Please wait");
	        setTimeout(function() {
	            KTUtil.btnRelease(btn);
	        }, 1000);

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

    // $('.card-body').on('click',function(){
    //     $('emoji-picker').addClass('hidden')
    //     document.querySelector('emoji-picker').database.close()
    // })

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

    $(document).on('click','.contactDetails',function(e){
    	e.preventDefault()
    	$('.contactInfo').show(250)
    })

    $(document).on('click','.contactInfo .close',function(e){
    	e.preventDefault()
    	$('.contactInfo').hide(250)
    })

    $(document).on('click','.emoji-icon',function(){
    	$(this).siblings('emoji-picker').toggleClass('hidden')
    })

	$('.contactInfo emoji-picker').unbind('emoji-click');
	$(document).on('emoji-click','.contactInfo emoji-picker',event => {
		$(event.currentTarget).siblings('.form-control').val($(event.currentTarget).siblings('.form-control').val() + event.detail.unicode)
	})

	$(document).on('click','.updateDetails',function(e){
		e.preventDefault();
		var formdata = new FormData();
		if($('input[name="profile_avatar"]').length){
			let background = $('input[name="profile_avatar"]')[0].files[0]
			let disable_read = $('input[name="disable_read"]').is(":checked") ? 1 : 0;
			formdata.append('mods', JSON.stringify($('.contactInfo select[name="mods[]"]').val()));
	        formdata.append('disable_read',disable_read)
	        formdata.append('background', background);
		}
		
        formdata.append('name', $('.contactInfo input[name="name"]').val());
        formdata.append('email', $('.contactInfo input[name="email"]').val());
        formdata.append('city', $('.contactInfo input[name="city"]').val());
        formdata.append('country', $('.contactInfo input[name="country"]').val());
        formdata.append('notes', $('.contactInfo textarea[name="notes"]').val());
        
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: 'POST',
            url: '/livechat/updateContact',
            data: formdata,
            processData: false,
            contentType: false,
            success: function (data) {
               	if(data.status.status == 1){
	                successNotification(data.status.message);
	            }else{
	                errorNotification(data.status.message);
	            } 
            },                         
        });
	})

	$(document).on('change','select[name="participants_type"]',function(){
		$(this).parents('.form-group').siblings('[data-id="'+$(this).val()+'"]').show(250).siblings('.form-group[data-id]').hide(250)
	})
        	// window.livewire.emitTo('chat-actions','newMessage',msg,numbers,JSON.stringify(phones))

	$(document).on('click','.addParticipants',function(){
		let type = $('select[name="participants_type"]').val();
    	let phones = [];
		if(type == 1){
			phones = $('select[name="participantsPhone[]"]').val();
		}else{
			phones = $('textarea[name="participants"]').val()
		}
        window.livewire.emitTo('contact-details','addGroupParticipants',type,JSON.stringify(phones))
	})

	new ClipboardJS('[data-clipboard=true]').on('success', function(e) {
	   e.clearSelection();
	});

	$(document).on('click','.updateSettings',function(){
		let send_messages = $('select[name="send_messages"]').val();
		let edit_info = $('select[name="edit_info"]').val();
		let groupDescription = $('textarea[name="groupDescription"]').val();
		let groupName = $('textarea[name="groupName"]').val();
        window.livewire.emitTo('contact-details','updateGroupSettings',groupName,send_messages,edit_info,groupDescription)
	});

})