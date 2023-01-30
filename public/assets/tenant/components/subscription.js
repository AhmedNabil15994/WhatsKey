$(function(){
	$('a.screen').on('click',function(e){
	    e.preventDefault();
	    e.stopPropagation();
	    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

	    var btn = KTUtil.getById("screenshot");
	   	KTUtil.btnWait(btn, "spinner spinner-right spinner-white pr-15", "Please wait");
	    $.ajax({
	        type: 'get',
	        url: myURL+'/screenshot',
	        data:{'_token': $('meta[name="csrf-token"]').attr('content'),},
	        success:function(data){
	            if(data.image){
	            	$('#full-width-modal .modal-body').append(data.image);
	            	$('#full-width-modal').modal('show');
	            	setTimeout(function() {
			            KTUtil.btnRelease(btn);
			        }, 1000);
	            }else{
	                errorNotification(data.status.message);
	                setTimeout(function() {
			            KTUtil.btnRelease(btn);
			        }, 1000);
	            }
	        },
	    });
	});

	$('select[name="contactsNameType"]').on('change',function(){
		let type = $(this).val();
		$.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
	    $.ajax({
	        type: 'post',
	        url: myURL+'/updateChannelSetting',
	        data:{
	        	'_token': $('meta[name="csrf-token"]').attr('content'),
	        	'setting': 'contactsNameType',
	        	'value' : type,
	        },
	        success:function(data){
	            if(data.status.status != 1){
	                errorNotification(data.status.message);	
	            }else{
	                successNotification(data.status.message);	
	            	window.location.reload();
	            }
	        },
	    });
	});

	$('[name="disableGroupsReply"]').on('change',function(){
		let type = $(this).is(":checked") ? 1 : 0;
		$.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
	    $.ajax({
	        type: 'post',
	        url: myURL+'/updateChannelSetting',
	        data:{
	        	'_token': $('meta[name="csrf-token"]').attr('content'),
	        	'setting': 'disableGroupsReply',
	        	'value' : type,
	        },
	        success:function(data){
	            if(data.status.status != 1){
	                errorNotification(data.status.message);	
	            }else{
	                successNotification(data.status.message);	
	            	window.location.reload();
	            }
	        },
	    });
	});

	$('[name="disableDialogsArchive"]').on('change',function(){
		let type = $(this).is(":checked") ? 1 : 0;
		$.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
	    $.ajax({
	        type: 'post',
	        url: myURL+'/updateChannelSetting',
	        data:{
	        	'_token': $('meta[name="csrf-token"]').attr('content'),
	        	'setting': 'disableDialogsArchive',
	        	'value' : type,
	        },
	        success:function(data){
	            if(data.status.status != 1){
	                errorNotification(data.status.message);	
	            }else{
	                successNotification(data.status.message);	
	            	window.location.reload();
	            }
	        },
	    });
	});

	$('[name="disableReceivingCalls"]').on('change',function(){
		let type = $(this).is(":checked") ? 1 : 0;
		$.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
	    $.ajax({
	        type: 'post',
	        url: myURL+'/updateChannelSetting',
	        data:{
	        	'_token': $('meta[name="csrf-token"]').attr('content'),
	        	'setting': 'disableReceivingCalls',
	        	'value' : type,
	        },
	        success:function(data){
	            if(data.status.status != 1){
	                errorNotification(data.status.message);	
	            }else{
	                successNotification(data.status.message);	
	            	// window.location.reload();
	            }
	        },
	    });
	});

	$('#full-width-modal').on('hidden.bs.modal', function () {
  		$('#full-width-modal .modal-body').empty();
	});
});	