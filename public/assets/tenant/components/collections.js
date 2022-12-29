$(function(){
	$(document).on('change','#contactsModal select[name="participants_type"]',function(){
		$(this).parents('.form-group').siblings('[data-id="'+$(this).val()+'"]').show(250).siblings('.form-group[data-id]').hide(250)
	});

	$(document).on('click','#contactsModal .selectProductContacts',function(e){
		e.preventDefault();
		let type = $('#contactsModal select[name="participants_type"]').val();
    	let phones = [];
		if(type == 1){
			phones = $('#contactsModal select[name="participantsPhone[]"]').val();
		}else{
			phones = $('#contactsModal textarea[name="participants"]').val()
		}

		$.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: 'POST',
            url: '/collections/sendCatalog',
            data: {
            	'_token':  $('meta[name="csrf-token"]').attr('content'),
            	'phones': phones,
            	'type': type,
            },
            success: function (data) {
               	if(data.status.status == 1){
	                successNotification(data.status.message);
	                $('#contactsModal').modal('hide')
	            }else{
	                errorNotification(data.status.message);
	            } 
            },                         
        });
	})
});