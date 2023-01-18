$(function(){
    let priceText = 'Price';
    let sarText = 'S.R';
    if(lang == 'ar'){
        priceText = 'السعر';
        sarText = 'ر.س';
    }
    let selected = [];
    $('.period').on('click',function(){
        let periodItem = $(this); 
        $.each($('.package'),function(index,item){
            let aHref = $(item).find('.mediaBtn').attr('href');
            if(periodItem.hasClass('monthly')){
                $(item).find('.price').children('span.value').text($(item).find('.price').data('monthly'));
                $(item).find('.mediaBtn').attr('data-area',1);
            }else{
                $(item).find('.price').children('span.value').text($(item).find('.price').data('annual'));
                $(item).find('.mediaBtn').attr('data-area',2);
            }
        })
        $(this).addClass('active').parent('li').siblings('li').children('.period.active').removeClass('active');
    });

    $(document).on('click','.mediaBtn',function(){
        let duration = $(this).attr('data-area');
        let item_id = $(this).attr('data-tab');
        let item_title = $(this).parents('.package').find('.item_title').text();
        let item_price = $(this).parents('.package').find('.item_price').text();
        let itemString = '';
        let itemCount = parseInt($('span.itemCount').text());
        if(jQuery.inArray(item_id, selected) == -1){
            selected.push(item_id);
            itemString = '<div class="d-flex mb-3 cartItem" data-id="'+item_id+'" data-duration="'+duration+'">'+
                            '<div class="symbol symbol-50 symbol-2by3 flex-shrink-0 mr-4">'+
                                '<div class="d-flex flex-column">'+
                                    '<div class="symbol-label mb-3"></div>'+
                                '</div>'+
                            '</div>'+
                            '<div class="d-flex flex-column flex-grow-1 my-lg-0 my-2 pr-3">'+
                                '<a href="#" class="text-dark-75 font-weight-bolder text-hover-primary font-size-lg mb-2">'+item_title+'</a>'+
                                '<span class="text-muted font-weight-bold font-size-lg">'+
                                    priceText+': '+
                                    '<span class="text-dark-75 font-weight-bolder">'+
                                        '<span class="price">'+item_price +'</span>'+
                                        ' <sup>'+sarText+'</sup>'+
                                    '</span>'+
                                '</span>'+
                            '</div>'+
                            '<a href="#" class="btn btn-sm btn-icon btn-outline-danger deleteCartItem font-weight-bolder py-2 font-size-sm">'+
                                '<i class="la la-trash icon-xl"></i>'+
                            '</a>'+
                        '</div>';
            $('span.itemCount').text(itemCount+1)
            $('.cartItems').append(itemString)
            calcPrices(item_price,'add');
        }
    });

    $(document).on('click','.deleteCartItem',function(){
        let itemCount = parseInt($('span.itemCount').text());
        let removeItem = $(this).parents('.cartItem').attr('data-id');
        let price = $(this).parents('.cartItem').find('span.price').text();
        $(this).parents('.cartItem').remove();
        $('span.itemCount').text(itemCount-1)
        selected = jQuery.grep(selected, function(value) {
            return value != removeItem;
        });
        calcPrices(price,'remove');
    });

    function calcPrices(price,type){
        let tax = parseFloat($("span.tax").text());
        let grandTotal = parseFloat($("span.grandTotal").text());
        let total = parseFloat($("span.total").text());
        if(type == 'add'){
            total = parseFloat(parseFloat(price) + parseFloat(total));
            let newItemTax = parseFloat(parseFloat(price) - parseFloat(price * 100 / 115));
            let newGrand = parseFloat(parseFloat(price) - parseFloat(newItemTax));
            tax = parseFloat(parseFloat(tax) + parseFloat(newItemTax));
            grandTotal = parseFloat(parseFloat(grandTotal) + parseFloat(newGrand));
        }else{
            total = parseFloat(parseFloat(total) - parseFloat(price));
            let newItemTax = parseFloat(parseFloat(price) - parseFloat(price * 100 / 115));
            let newGrand = parseFloat(parseFloat(price) - parseFloat(newItemTax));
            tax = parseFloat(parseFloat(tax) - parseFloat(newItemTax));
            grandTotal = parseFloat(parseFloat(grandTotal) - parseFloat(newGrand));
        }
        $("span.total").text(Math.abs(total.toFixed(2)))
        $("span.grandTotal").text(Math.abs(grandTotal.toFixed(2)))
        $("span.tax").text(Math.abs(tax.toFixed(2)))
    }

    $(document).on('click','.checkout',function(e){
        e.preventDefault();
        e.stopPropagation();
        let addonData = [];
        if($('.cartItems .cartItem').length){
            $.each($('.cartItems .cartItem'),function(index,item){
                addonData.push({
                    addon_id: $(item).attr('data-id'),
                    duration: $(item).attr('data-duration'),
                })
            });
            $('input[name="addonData"]').val(JSON.stringify(addonData))
            $(this).parent('form').submit();
        }
        
    });
});