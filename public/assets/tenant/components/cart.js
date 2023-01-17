$(function(){

	// $(document).on('change', '.labelUpload input[type="file"]', function() {
	//     var log = $(this).val().split('\\').pop();
	//     var allData = getData();
	//     data = allData[0];
	//     totals = allData[1];

	//     var formData = new FormData();
	//     formData.append('transfer_image', $(this)[0].files[0]);

	//     formData.append('fileName', log);
	//     formData.append('total', $('b.total').html());
	//     formData.append('name', $('input[name="name"]').val());
	//     formData.append('company_name', $('input[name="company_name"]').val());
	//     formData.append('address', $('input[name="address"]').val());
	//     formData.append('address2', $('input[name="address2"]').val());
	//     formData.append('country', $('select[name="country"] option:selected').val());
	//     formData.append('region', $('select[name="region"] option:selected').val());
	//     formData.append('city', $('input[name="city"]').val());
	//     formData.append('postal_code', $('input[name="postal_code"]').val());
	//     formData.append('tax_id', $('input[name="tax_id"]').val());
	//     formData.append('invoice_id', $('input[name="invoice_id"]').val());

	//     formData.append('data', JSON.stringify(data));
	//     formData.append('totals', JSON.stringify(totals));

	//     $.ajax({
	//         type:'POST',
	//         url: '/checkout/bankTransfer',
	//         data:formData,
	//         cache:false,
	//         contentType: false,
	//         processData: false,
	//         success:function(data){
	//             setTimeout(function(){
	//                 window.location.href = data.data;
	//             }, 2500);
	//         },
	//         error: function(data){
	//             errorNotification(data.status.message);
	//             // location.reload();
	//         }
	//     });

	// });

	$('.addCoupon').on('click',function(e){
	    e.preventDefault();
	    e.stopPropagation();

	    var couponVal  = $(this).parents('.coupon').find('input[type="text"]').val();
	    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
	    $.ajax({
	        type: 'POST',
	        url: window.location.pathname+'/coupon',
	        data:{
	            '_token': $('meta[name="csrf-token"]').attr('content'),
	            'coupon': couponVal,
	            'invoice_id': $('input[name="invoice_id"]').val()
	        },
	        success:function(data){
	            if(data.status.status == 1){
	                successNotification(data.status.message);
	                var oldTotal = $('span.total').text();
	                var oldGrandTotal = $('span.grandTotal').text();
	                var oldTax = $('span.tax').text();
	                var oldDiscount = $('span.discount').text();

	                var discount_type = data.data.discount_type;
	                var discount_value = data.data.discount_value;

	                
	                var discount = discount_type == 1 ? discount_value : (discount_value*grandTotal)/100;
	                var taxDiscount = discount - ((discount * 100) / 115).toFixed(2);

	                var newDiscount = (parseFloat(oldDiscount)  +  parseFloat(discount) ).toFixed(2);
	                var newTax = (parseFloat(oldTax)  -  parseFloat(taxDiscount)).toFixed(2);
	                var newGrandTotal = (parseFloat(oldGrandTotal)  -  parseFloat(discount) + parseFloat(taxDiscount)).toFixed(2);
	                var newTotal = (parseFloat(newGrandTotal)  + parseFloat(newTax)).toFixed(2);
	                //Calc New Prices
	                $('span.discount').text(newDiscount);
	                $('span.grandTotal').text(newGrandTotal); 
	                $('span.tax').text(newTax); 
	                $('span.total').text(newTotal); 
	                $('.addCoupon').attr('disabled',true);
	            }else{
	                errorNotification(data.status.message);
	            }
	        },
	    });
	});

	$('.btnNext:not(.btnPrev):not(.finish)').on('click',function(e){
        e.preventDefault();
        e.stopPropagation();
        // nextStep($(this));
        var id = $(this).parents('#kt_form').find('.myStep.active').attr('id');
        fireAjaxRequest(id,$(this));
    });

	function fireAjaxRequest(id,elem){
        var tabsLength = $('#kt_form .myStep').length;
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
        if(id == 'step2'){ // Second Step
            $.ajax({
                type: 'POST',
                url: '/profile/postPaymentInfo',
                data:{
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'country': $('input[name="country"]').val(),
                    'region': $('input[name="region"]').val(),
                    'city': $('input[name="city"]').val(),
                    'address': $('input[name="address"]').val(),
                    'address2': $('input[name="address2"]').val(),
                    'postal_code': $('input[name="postal_code"]').val(),
                    'tax_id': $('input[name="tax_id"]').val(),
                },
                success:function(data){
                    if(data.status.status == 1){
                        // successNotification(data.status.message);
                        nextStep(elem);
                    }else{
                        errorNotification(data.status.message);
                    }
                },
            });
        }else{
            nextStep(elem);
        }
    }

    function nextStep(elem){
        var currentStepDiv = elem.parents('#kt_form').find('.myStep.active');
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
    }

    function prevStep(elem) {
        var currentStepDiv = elem.parents('#kt_form').find('.myStep.active');
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

	const Tus = Uppy.Tus;
    const ProgressBar = Uppy.ProgressBar;
	const StatusBar = Uppy.StatusBar;
	const FileInput = Uppy.FileInput;
	const Informer = Uppy.Informer;

    var id = '#kt_uppy_3';
	var uppyDrag = Uppy.Core({
		autoProceed: true,
		restrictions: {
			maxFileSize: 10000000, // 1mb
			maxNumberOfFiles: 1,
			minNumberOfFiles: 1,
			allowedFileTypes: ['image/*', 'video/*','document/*','application/*']
		}
	});

	uppyDrag.use(Uppy.DragDrop, { target: id + ' .uppy-drag' });
	uppyDrag.use(ProgressBar, {
		target: id + ' .uppy-progress',
		hideUploadButton: false,
		hideAfterFinish: false
	});
	uppyDrag.use(Informer, { target: id + ' .uppy-informer'  });
	uppyDrag.use(Tus, { endpoint: 'https://master.tus.io/files/' });

	uppyDrag.on('complete', function(file) {
		var imagePreview = "";
		$.each(file.successful, function(index, value){
			var imageType = /image/;
			var thumbnail = "";
			if (imageType.test(value.type)){
				thumbnail = '<div class="uppy-thumbnail"><img src="'+value.uploadURL+'"/></div>';
			}
			var sizeLabel = "bytes";
			var filesize = value.size;
			if (filesize > 1024){
				filesize = filesize / 1024;
				sizeLabel = "kb";
				if(filesize > 1024){
					filesize = filesize / 1024;
					sizeLabel = "MB";
				}
			}
			imagePreview += '<div class="uppy-thumbnail-container" data-id="'+value.id+'">'+thumbnail+' <span class="uppy-thumbnail-label">'+value.name+' ('+ Math.round(filesize, 2) +' '+sizeLabel+')</span><span data-id="'+value.id+'" class="uppy-remove-thumbnail"><i class="flaticon2-cancel-music"></i></span></div>';
		});

		$(id + ' .uppy-thumbnails').append(imagePreview);
		const dataTransfer = new DataTransfer();
		dataTransfer.items.add(file.successful[0].data);
		document.querySelector('[name="transfer_image"]').files = dataTransfer.files;
	});

	$(document).on('click', id + ' .uppy-thumbnails .uppy-remove-thumbnail', function(){
		var imageId = $(this).attr('data-id');
		uppyDrag.removeFile(imageId);
		$(id + ' .uppy-thumbnail-container[data-id="'+imageId+'"').remove();
	});
})