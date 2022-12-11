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


	var modelProps = $('input[name="modelProps"]').val();
	var lang = $('html').attr('lang');
	if(lang == 'en'){
		var storeAs = 'Store As ';
		var choose = 'Choose';
	}else{
		var storeAs = 'حفظ ك ';
		var choose = 'حدد اختيارك';
	}
	modelProps = JSON.parse(modelProps);
	var options = '<option value="0">'+choose+'</option>';
	$.each(modelProps,function(index,item){
		options+='<option value="'+index+'">'+item+'</option>';
	});

    function handleFile(f) {
        var formData = new FormData();
        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
        formData.append('file', f);
        $.ajax({
            type:'POST',
            url: '/groupNumbers/addGroupNumbers/checkFile',
            data:formData,
            cache:false,
            contentType: false,
            processData: false,
            success:function(data){
                var oneItem = data.headers;
                var result = data.data;
                var cols = [];
                $('#colData').empty();
        
                $.each(oneItem,function(index,item){
                    if(index != '__rowNum__' && item != null){
                        cols.push(index);
                        var selectProps =   '<select data-toggle="select2" data-style="btn-outline-myPR">'+options+'</select>';
                        selectProps =   '<div class="form-group mb-2">'+
                                            '<label class="mb-0" style="width:unset;font-size:14px">'+storeAs+' :</label>'+
                                            selectProps+
                                        '</div>';
                        var colName =   '<div class="grItem border-0 mb-3">'+
                                            '<li data-cols="'+index+'">'+
                                                selectProps+
                                                '<label class="checkbox checkbox-outline checkbox-primary">'+
                                                    '<input type="checkbox" class="colsData" checked  value="'+index+'"/>'+
                                                    '<span></span>'+
                                                    item +
                                                '</label>'+
                                                '<hr>'+
                                            '</li>'+
                                        '</div>';
                        $('#colData').append(colName);
                        // $('#colData select').selectpicker();
                    }
                });
                $.each(result, function(index,item){
                    for (var i = 0; i < cols.length; i++) {
                        if(item[cols[i]] != null){
                            var newElement= '<label class="checkbox checkbox-outline vars checkbox-success">'+
                                                '<input type="checkbox" name="'+oneItem[i]+'[]" checked readonly value="'+item[cols[i]]+'"/>'+
                                                '<span></span>'+
                                                item[cols[i]] +
                                            '</label>'+
                                            '<hr>';
                            $('#colData li[data-cols="'+cols[i]+'"]').append(newElement);
                        }
                    }
                });
                $('select[data-toggle="select2"]').select2();
                $('input[name="files"]').val(data.files);
            },
            error: function(data){
                errorNotification(data.status.message);
            }
        });
    
    }

    $('.upload').on('click',function(){
        $('input[name="file"]')[0].click();
    });

    $('input[type="file"]').on('change',function(){
        var file = $(this)[0].files[0];
        handleFile(file);
    });

    $(document).on('change','#colData .selectpicker',function(){
        var value = $(this).val();
        $(this).parents('.form-group.row').siblings('.checkbox.vars').find('input[type="checkbox"]').attr('name',value+'[]');
    });

    $(document).on('change','input.colsData',function(){
        if($(this).is(':checked')){
            $(this).parent('.checkbox').siblings('.checkbox.vars').find('input[type="checkbox"]').prop('checked',true);
        }else{
            $(this).parent('.checkbox').siblings('.checkbox.vars').find('input[type="checkbox"]').prop('checked',false);
        }
    });


});
