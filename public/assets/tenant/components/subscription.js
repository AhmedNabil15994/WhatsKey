$(function(){
	$('a.screen').on('click',function(e){
	    e.preventDefault();
	    e.stopPropagation();
	    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
	    $.ajax({
	        type: 'get',
	        url: myURL+'/screenshot',
	        data:{'_token': $('meta[name="csrf-token"]').attr('content'),},
	        success:function(data){
	            if(data.image){
	            	$('#full-width-modal .modal-body').append(data.image);
	            	$('#full-width-modal').modal('show');
	            }else{
	                errorNotification(data.status.message);
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