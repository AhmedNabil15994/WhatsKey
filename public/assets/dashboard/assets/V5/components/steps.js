$(function(){
    $('.btnNext:not(.btnPrev):not(.finish)').on('click',function(e){
        e.preventDefault();
        e.stopPropagation();
        // nextStep($(this));
        var id = $(this).parents('#kt_form').find('.pb-5.active').attr('id');
        fireAjaxRequest(id,$(this));
    });

    function fireAjaxRequest(id,elem){
        var tabsLength = $('#kt_form .pb-5').length;
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
        if(id == 'step1'){ // First Step
            var oldName = $('input[name="oldName"]').val();
            var newName = $('input[name="channelName"]').val();
            if(oldName != newName){
                $.ajax({
                    type: 'POST',
                    url: '/QR/updateName',
                    data:{
                        '_token': $('meta[name="csrf-token"]').attr('content'),
                        'name': newName,
                    },
                    success:function(data){
                        if(data.status.status == 1){
                            successNotification(data.status.message);
                            nextStep(elem);
                        }else{
                            errorNotification(data.status.message);
                        }
                    },
                });
            }else{
                nextStep(elem);
            }
        }else{
            nextStep(elem);
        }
    }

    function nextStep(elem){
        var currentStepDiv = elem.parents('#kt_form').find('.pb-5.active');
        var selectedIndex = parseInt(currentStepDiv.attr('id').replace('step',''));
        currentStepDiv.removeClass('active');   
        currentStepDiv.removeAttr('data-wizard-state');   
        if(selectedIndex == 1 || selectedIndex == 2){
            $('.btnPrev').attr('data-wizard-type','current')
        }else{
            $('.btnPrev').attr('data-wizard-type','action-prev')
        }

        if(selectedIndex == 2){
            $('.finish').attr('data-wizard-type','current')
            $('.btnNext',).hide()
        }else{
            $('.finish').attr('data-wizard-type','action-submit')
            $('.btnNext').show()
        }

        $('.wizard-step[data-wizard-state="current"]').attr('data-wizard-state','').next('.wizard-step').attr('data-wizard-state','current')
        $('#step'+(selectedIndex+1) ).addClass('active');
        $('#step'+(selectedIndex+1) ).attr('data-wizard-state','current');
        if($('img.qrImage').length){
            if($('img.qrImage').data('area') == 1 && $('#step2').hasClass('active')){
                nextStep($('.btnNext'));
            }
        }
    }

    function prevStep(elem) {
        var currentStepDiv = elem.parents('#kt_form').find('.pb-5.active');
        var selectedIndex = parseInt(currentStepDiv.attr('id').replace('step',''));
        currentStepDiv.removeClass('active');   
        currentStepDiv.removeAttr('data-wizard-state');   
        if(selectedIndex == 3){
            $('.btnPrev').attr('data-wizard-type','current')
        }else{
            $('.btnPrev').attr('data-wizard-type','action-prev')
        }

        if(selectedIndex == 3 || selectedIndex == 2){
            $('.finish').attr('data-wizard-type','action-submit')
            $('.btnNext',).show()
        }else{
            $('.finish').attr('data-wizard-type','current')
            $('.btnNext').hide()
        }

        $('.wizard-step[data-wizard-state="current"]').attr('data-wizard-state','').next('.wizard-step').attr('data-wizard-state','current')
        $('#step'+(selectedIndex-1) ).addClass('active');
        $('#step'+(selectedIndex-1) ).attr('data-wizard-state','current');
    }

    $('.btnPrev').on('click',function(e){
        e.preventDefault();
        e.stopPropagation();
        prevStep($(this));
    });

    $('.finish').on('click',function(e){
        e.preventDefault();
        e.stopPropagation();
        window.location.href = "/dashboard";
    });

});
