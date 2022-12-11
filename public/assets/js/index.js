$(function(){

	$('.period').on('click',function(){
		var aHref = $(this).parents('.package').find('.mediaBtn').attr('href');
		if($(this).hasClass('monthly')){
			$(this).parents('.package').find('.price span.value').text($(this).parents('.package').find('.price').data('monthly'));
			$(this).parents('.package').find('.mediaBtn').attr('href',aHref.replace('&duration=2','&duration=1'));
		}else{
			$(this).parents('.package').find('.price span.value').text($(this).parents('.package').find('.price').data('annual'));
			$(this).parents('.package').find('.mediaBtn').attr('href',aHref.replace('&duration=1','&duration=2'));
		}
		$(this).parents('.package').find('.period').removeClass('active');
		$(this).addClass('active');
	});

	$('.package .more span').on('click',function(){
		if($(this).hasClass('mor')){
			$(this).addClass('hidden');
			$(this).parents('.package').find('.more span.les').removeClass('hidden');
			$(this).parents('.package').removeClass('closed');
		}else{
			$(this).addClass('hidden');
			$(this).parents('.package').find('.more span.mor').removeClass('hidden');
			$(this).parents('.package').addClass('closed');
		}
	});

	$(document).on('click','i.fa-eye',function(){
		$(this).parents('.form-group').find('input[type="password"]').attr('type','text');
		$(this).removeClass('fa-eye').addClass('fa-eye-slash');
	});

	$(document).on('click','i.fa-eye-slash',function(){
		$(this).parents('.form-group').find('input[type="text"]').attr('type','password');
		$(this).removeClass('fa-eye-slash').addClass('fa-eye');
	});

	$(document).on('click','i.fa-plus',function(){
		$(this).parents('.card').find('.collapse').slideDown(250);
		$(this).removeClass('fa-plus').addClass('fa-minus');
		$(this).css('color','#007672');
		$(this).parents('.card').find('.card-title').css('color','#007672');
	});

	$(document).on('click','i.fa-minus',function(){
		$(this).parents('.card').find('.collapse').slideUp(250);
		$(this).removeClass('fa-minus').addClass('fa-plus');
		$(this).css('color','#000');
		$(this).parents('.card').find('.card-title').css('color','#000');
	});
})	