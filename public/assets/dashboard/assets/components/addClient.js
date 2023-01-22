$(function(){

	$('select[name="duration_type"]').on('change',function(){
		if($(this).val() == 1){
			$('.mainCol input[type="checkbox"].yearly').attr('disabled',true);
			$('.mainCol input[type="checkbox"].yearly').prop('checked',false);
		}else{
			$('.mainCol input[type="checkbox"].yearly').attr('disabled',false);
		}
	});

	$('.mainCol input[type="checkbox"]').on('change',function(e){
		e.stopPropagation();
		e.preventDefault();
		if($(this).is(':checked')){
			if($(this).attr('class') == 'monthly'){
				if($(this).parents('.mainCol').find('input[type="checkbox"].yearly').is(':checked')){
					$(this).parents('.mainCol').find('input[type="checkbox"].yearly').prop('checked',false);
				}
			}else{
				if($(this).parents('.mainCol').find('input[type="checkbox"].monthly').is(':checked')){
					$(this).parents('.mainCol').find('input[type="checkbox"].monthly').prop('checked',false);
				}
			}
		}
		if(!$(this).hasClass('old')){
			$(this).parents('form').submit();
		}
	});

	$('#transferDaysModal .btn-success').on('click',function(e){
	    e.preventDefault();
	    e.stopPropagation();
	    var days = $('#transferDaysModal input[name="days"]').val();
	    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
	    $.ajax({
	        type: 'POST',
	        url: myURL+'/transferDays',
	        data:{
	            '_token': $('meta[name="csrf-token"]').attr('content'),
	            'days': days,
	        },
	        success:function(data){
	            if(data.status.status == 1){
	                successNotification(data.status.message);
	                $('#transferDaysModal').modal('hide');
	                setTimeout(function(){
	                	window.location.reload();
	                },1500)
	            }else{
	                errorNotification(data.status.message);
	            }
	        },
	    });
	});

	$(document).on('click','#compensationModal .btn-success',function(e){
	    e.preventDefault();
	    e.stopPropagation();
	    var data = [];	

	    $.each($('#compensationModal table tbody tr'),function(index,item){
	    	data.push({
	    		id: $(item).data('cols'),
	    		type: $(item).data('type'),
	    		start_date: $(item).find($('input[name="start_date"]')).val(),
	    		end_date: $(item).find($('input[name="end_date"]')).val(),
	    	});
	    })

	    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
	    $.ajax({
	        type: 'POST',
	        url: myURL+'/compensation',
	        data:{
	            '_token': $('meta[name="csrf-token"]').attr('content'),
	            'data': data,
	        },
	        success:function(data){
	            if(data.status.status == 1){
	                successNotification(data.status.message);
	                $('#compensationModal').modal('hide');
	                setTimeout(function(){
	                	window.location.reload();
	                },1500)
	            }else{
	                errorNotification(data.status.message);
	            }
	        },
	    });
	});

	$('.datepickerInput').datepicker({
        dateFormat: 'yy-mm-dd',
    });  


	$("input[name='emergency_tel']").intlTelInput({
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

	$(".updateSettings").on('click',function(e) {
		e.preventDefault();
        e.stopPropagation();
	    if($("input[name='emergency_tel']").length){
            var phone = $("input[name='emergency_tel']").intlTelInput("getNumber");
            if (!$("input[name='emergency_tel']").intlTelInput("isValidNumber")) {
                if (lang == "en") {
                    errorNotification("This Phone Number Isn't Valid!");
                } else {
                    errorNotification("هذا رقم الجوال غير موجود");
                }
            }else{
                $('input[name="emergency_number"]').val(phone);
                $(this).parent('form').submit();
            }
	    }
	});


	$(".updatePersonalInfo").on('click',function(e) {
		e.preventDefault();
        e.stopPropagation();
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
                $(this).parent('form').submit();
            }
	    }
	});

	$('#addonsModal').on('click','.addUserAddon',function(e){
		e.preventDefault();
		e.stopPropagation();
		var addon_id = $('#addonsModal select[name="addon_id"] option:selected').val()
		var status = $('#addonsModal select[name="status"] option:selected').val()
		var duration_type = $('#addonsModal select[name="duration_type"] option:selected').val()
		var start_date = $('#addonsModal input[name="start_date"]').val()
		var end_date = $('#addonsModal input[name="end_date"]').val()
		var item_id = $(this).attr('data-id');
		$.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
	    $.ajax({
	        type: 'POST',
	        url: myURL+'/updateUserAddons',
	        data:{
	            '_token': $('meta[name="csrf-token"]').attr('content'),
	            'item_id': item_id,
	            'addon_id': addon_id,
	            'status': status,
	            'start_date': start_date,
	            'duration_type': duration_type,
	            'end_date': end_date,
	        },
	        success:function(data){
	            if(data.status.status == 1){
	                successNotification(data.status.message);
	                $('#addonsModal').modal('hide');
	                setTimeout(function(){
	                	window.location.reload();
	                },1500)
	            }else{
	                errorNotification(data.status.message);
	            }
	        },
	    });
	});

	$('#extraQuotasModal').on('click','.addUserExtraQuota',function(e){
		e.preventDefault();
		e.stopPropagation();
		var extra_quota_id = $('#extraQuotasModal select[name="extra_quota_id"] option:selected').val()
		var status = $('#extraQuotasModal select[name="status"] option:selected').val()
		var duration_type = $('#extraQuotasModal select[name="duration_type"] option:selected').val()
		var start_date = $('#extraQuotasModal input[name="start_date"]').val()
		var end_date = $('#extraQuotasModal input[name="end_date"]').val()
		var item_id = $(this).attr('data-id');
		$.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
	    $.ajax({
	        type: 'POST',
	        url: myURL+'/updateUserExtraQuotas',
	        data:{
	            '_token': $('meta[name="csrf-token"]').attr('content'),
	            'item_id': item_id,
	            'extra_quota_id': extra_quota_id,
	            'status': status,
	            'start_date': start_date,
	            'end_date': end_date,
	            'duration_type': duration_type,
	        },
	        success:function(data){
	            if(data.status.status == 1){
	                successNotification(data.status.message);
	                $('#extraQuotasModal').modal('hide');
	                setTimeout(function(){
	                	window.location.reload();
	                },1500)
	            }else{
	                errorNotification(data.status.message);
	            }
	        },
	    });
	});

	$('.updateUserAddon').on('click',function(e){
		let id = $(this).data('id');
		let duration_type = $(this).data('duration');
		let status = $(this).data('status');
		let addon_id = $(this).data('addon');
		let start_date = $(this).parents('tr').children('td.start_date').text();
		let end_date = $(this).parents('tr').children('td.end_date').text();
		$('#addonsModal .addUserAddon').attr('data-id',id);
		$('#addonsModal select[name="addon_id"]').val(addon_id).trigger('change')
		$('#addonsModal select[name="duration_type"]').val(duration_type).trigger('change')
		$('#addonsModal select[name="status"]').val(status).trigger('change')
		$('#addonsModal input[name="start_date"]').val(start_date)
		$('#addonsModal input[name="end_date"]').val(end_date)
		$('#addonsModal').modal('show');
	});

	$('.updateUserExtraQuota').on('click',function(e){
		let id = $(this).data('id');
		let duration_type = $(this).data('duration');
		let status = $(this).data('status');
		let extra_quota_id = $(this).data('addon');
		let start_date = $(this).parents('tr').children('td.start_date').text();
		let end_date = $(this).parents('tr').children('td.end_date').text();
		$('#extraQuotasModal .addUserExtraQuota').attr('data-id',id);
		$('#extraQuotasModal select[name="extra_quota_id"]').val(extra_quota_id).trigger('change')
		$('#extraQuotasModal select[name="duration_type"]').val(duration_type).trigger('change')
		$('#extraQuotasModal select[name="status"]').val(status).trigger('change')
		$('#extraQuotasModal input[name="start_date"]').val(start_date)
		$('#extraQuotasModal input[name="end_date"]').val(end_date)
		$('#extraQuotasModal').modal('show');
	});
	
});