$(function(){
	$(document).on('click','.emoji-icon',function(){
		$(this).parents('.textWrap').siblings('.textWrap').children('emoji-picker:not(.hidden)').addClass('hidden');
	    $(this).siblings('emoji-picker').toggleClass('hidden')
	})

	$('emoji-picker').unbind('emoji-click');
	$(document).on('emoji-click','emoji-picker',event => {
	    $(event.currentTarget).siblings('.form-control').val($(event.currentTarget).siblings('.form-control').val() + event.detail.unicode)
	})
})