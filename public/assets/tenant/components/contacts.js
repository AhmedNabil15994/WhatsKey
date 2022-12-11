$(function(){
	
    $('select[name="group_id"]').on('change',function(){
        if($(this).val() == '@'){
            $('.new').removeClass('hidden');
            $('.new').slideDown(250);
        }else{
            $('.new').slideUp(250);
            $('.new').addClass('hidden');
        }
    });

    $('.new .addGR').on('click',function(e){
        e.preventDefault();
        e.stopPropagation();
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
        $.ajax({
            type: 'POST',
            url: '/groupNumbers/create',
            data:{
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'name_ar': $('.new input.name_ar').val(),
                'name_en': $('.new input.name_en').val(),
            },
            success:function(data){
                if(!data.title && data.status.status != 1){
                    errorNotification(data.status.message);
                }else{
                    $("select[name='group_id'] option:last").before('<option value="'+data.id+'" selected>'+data.name_en+'</option>');
                    $("select[name='group_id']").select2('destroy');
                    $("select[name='group_id']").select2();
                    $('.new input').val('');
                    $('.new').slideUp(250);
                }
            },
        });
    });

    $('.contacts.btnsTabs li').on('click',function(){
        $('input[name="vType"]').val($(this).data('contact') > 1 ? $(this).data('contact') : 2);
    });
});