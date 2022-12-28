$(function(){
	var lang = $('html').attr('lang');
	var myURL = window.location.href;
	if(myURL.indexOf("#") != -1){
	    myURL = myURL.replace('#','');
	}

	var table = $('#kt_datatable');
	var designElems = $('input[name="designElems"]').length ?  JSON.parse($('input[name="designElems"]').val()) : [];
	var tableData = designElems.tableData;
	var columnsDef = [];
	var columnsVar = [];
	var columnDefsVar = [];

	var urlParams;
	(window.onpopstate = function () {
	    var match,
	        pl     = /\+/g,  // Regex for replacing addition symbol with a space
	        search = /([^&=]+)=?([^&]*)/g,
	        decode = function (s) { return decodeURIComponent(s.replace(pl, " ")); },
	        query  = window.location.search.substring(1);

	    urlParams = {};
	    while (match = search.exec(query))
	       urlParams[decode(match[1])] = decode(match[2]);
	})();

	function getIndex(key,val) {
		var i = 0;
		var x ;
		$.each(tableData,function(index, el) {
			if(index === key && val === el){
				x = i;
			}else{
				i++;
			}
		});
		return x;
	}

	if(lang == 'en'){
		var showCols = "Show Columns <i class='fa fas fa-angle-down'></i>";
		var direction = 'ltr';
		var search = ' Search ';
		var info = 'Showing items from  _START_ to _END_ (total _TOTAL_ )';
		var lengthMenu = 'Showing _MENU_ items';
		var emptyTable = "No records found";
		var processing = "Processing";
		var infoEmpty = "No Results found";
		var rows1 = "You've choosed %d items";
		var rows2 = "You've choosed only one item";
		var prev = "<";
		var next = ">";
		var first = "First";
		var last = "Last";
		var editText = 'Edit';
		var copyText = 'Copy';
		var deleteText = 'Delete';
		var showText = 'View Contacts';
		var viewText = 'View';
		var exportText = 'Export Contacts';
		var actionsVar = 'Actions';
		var detailsText = 'Details';
		var enableText = 'Enable';
		var disableText = 'Disable';
		var refreshText = 'Refresh';
	}else{
		var showCols = " عرض الأعمدة <i class='fa fas fa-angle-down'></i>";
		var direction = 'rtl';
		var search = ' البحث: ';
		var viewText = 'عرض';
		var info = 'يتم العرض من  _START_ إلى _END_ (العدد الكلي للسجلات _TOTAL_ )';
		var lengthMenu = 'عرض _MENU_ سجلات';
		var emptyTable = "لا يوجد نتائج مسجلة";
		var processing = "جاري التحميل";
		var infoEmpty = "لا يوجد نتائج مسجلة";
		var rows1 = "لقد قمت باختيار %d عناصر";
		var rows2 = "لقد قمت باختيار عنصر واحد";
		var prev = "<";
		var next = ">";
		var first = "الاول";
		var last = "الاخير";
		var editText = 'تعديل';
		var copyText = 'تكرار';
		var deleteText = 'حذف';
		var showText = 'عرض الارقام';
		var exportText = 'استيراد جهات الارسال';
		var actionsVar = 'الاجراءات';
		var detailsText = 'التفاصيل';
		var enableText = 'تفعيل';
		var disableText = 'تعطيل';
		var refreshText = 'تحديث';
	}

	var iCounter = 1;
	$.each(tableData,function(index,item){
		if(index != 'actions'){
			columnsDef.push(index);
			if(item['type'] == 'date'){
				columnsVar.push({'data': index, 'type' : item['type'],});
			}else{
				columnsVar.push({'data': index,});
			}
			if(index == 'id'){
				columnDefsVar.push({
					'targets': 0,
					'title' : item['label'],
					'orderable':false,
					render: function(data, index) {
						return iCounter++;
					}
				});
			}else{
				columnDefsVar.push({
					'targets': getIndex(index,item),
					'title' : item['label'],
					'className': item['className'],
					render: function(data, type, full, meta) {
						var labelClass = '';
						if(getIndex(index,item) == 1){
							labelClass = full.labelClass;
						}
						if(index == 'statusIDText'){
							if(full.status == 1){
								labelClass = 'label badge label-success';
							}else{
								labelClass = 'label badge label-danger';
							}
						}
						if(index == 'statusText'){
							if(full.statusText == 'مسترجع' || full.statusText == 'ملغي' || full.statusText == 'تم الالغاء'){
								labelClass = 'label badge label-light-danger';
							}
							if(full.statusText == 'تم الشحن' || full.statusText == 'تم التنفيذ' || full.statusText == 'جديد' || full.statusText == 'ترحيب بالعميل'){
								labelClass = 'label badge label-light-success';
							}
							if(full.statusText == 'تم التوصيل' || full.statusText == 'قيد التنفيذ'){
								labelClass = 'label badge label-light-warning';
							}
							if(full.statusText == 'جاهز'){
								labelClass = 'label badge label-light-primary';
							}
							if(full.statusText == 'جاري التجهيز'){
								labelClass = 'label badge label-light-info';
							}
							if(full.statusText == 'بإنتظار الدفع' ){
								labelClass = 'label badge label-default';
							}
							if(full.statusText == 'جاري التوصيل' || full.statusText == 'جارى التوصيل' || full.statusText == 'بإنتظار المراجعة'){
								labelClass = 'label badge label-light-info';
							}
						}
						return '<a class="'+item['anchor-class']+' '+labelClass+'" data-col="'+item['data-col']+'" data-id="'+full.id+'">'+data+'</a>';
					},
				});
			}
		}else{
			columnDefsVar.push({
				targets: -1,
				title: actionsVar,
				orderable: false,
				render: function(data, type, full, meta) {
					var editButton = '';
					var copyButton = '';
					var showButton = '';
					var exportButton = '';
					var deleteButton = '';

					if($('input[name="data-area"]').val() == 1){
                            // <a class="dropdown-item" href="#"><i class="fe fe-plus mr-2"></i> Add</a>
						editButton = '<a data-toggle="tooltip" data-original-title="'+editText+'" href="/'+designElems.mainData.url+'/edit/'+data+'" class="action-icon btn btn-sm btn-clean btn-icon"> <i class="icon-xl la la-edit"></i></a>';
					}

					if($('input[name="data-tabs"]').length && $('input[name="data-tabs"]').val() == 1){
						copyButton = '<a data-toggle="tooltip" data-original-title="'+copyText+'" href="/'+designElems.mainData.url+'/copy/'+data+'" class="action-icon btn btn-sm btn-clean btn-icon"> <i class="icon-xl la la-copy"></i></a>';
						showButton = '<a data-toggle="tooltip" data-original-title="'+(full.status == 1 ? disableText : enableText)+'" href="/'+designElems.mainData.url+'/changeStatus/'+data+'" class="action-icon btn btn-sm btn-clean btn-icon"> <i class="icon-xl la '+(full.status == 1 ? 'la-toggle-on' : 'la-toggle-off')+'"></i></a>';
					}

					if(designElems.mainData.url == 'groupNumbers'){
						showButton = '<a data-toggle="tooltip" data-original-title="'+showText+'" href="/contacts?group_id='+full.id+'" class="action-icon btn btn-sm btn-clean btn-icon"> <i class="icon-xl la la-eye"></i></a>';
						if($('input[name="data-tests"]').length && $('input[name="data-tests"]').val() == 1){
							exportButton = '<a data-toggle="tooltip" data-original-title="'+exportText+'" href="/contacts/export/'+data+'" class="action-icon btn btn-sm btn-clean btn-icon "> <i class="icon-xl la la-cloud-download"></i></a>';
						}
					}

					if($('input[name="data-cols"]').val() == 1){
						deleteButton = '<a data-toggle="tooltip" data-original-title="'+deleteText+'" onclick="deleteItem('+data+')" class="action-icon btn btn-sm btn-clean btn-icon"> <i class="icon-xl la la-trash"></i></a>'
					}

					if(designElems.mainData.url == 'groupMsgs' && $('input[name="data-tab"]').val() == 1){
						showButton = '<a data-toggle="tooltip" data-original-title="'+detailsText+'" href="/groupMsgs/view/'+full.id+'" class="action-icon btn btn-sm btn-clean btn-icon"> <i class="icon-xl la la-eye"></i></a>';
						editButton = '';
						deleteButton = '';
					}

					if(designElems.mainData.url == 'orders' && $('input[name="data-tab"]').val() == 1){
						showButton = '<a data-toggle="tooltip" data-original-title="'+detailsText+'" href="/orders/view/'+full.id+'" class="action-icon btn btn-sm btn-clean btn-icon"> <i class="icon-xl la la-eye"></i></a>';
						editButton = '';
						deleteButton = '';
					}

					if(designElems.mainData.url == 'products' && $('input[name="data-tab"]').val() == 1){
						showButton = '<a data-toggle="tooltip" data-original-title="'+detailsText+'" href="/products/view/'+full.id+'" class="action-icon btn btn-sm btn-clean btn-icon"> <i class="icon-xl la la-eye"></i></a>';
					}

					if((designElems.mainData.url == 'transfers' || designElems.mainData.name == 'whatsapp-bankTransfers') && $('input[name="data-tab"]').val() == 1){
						showButton = '<a data-toggle="tooltip" data-original-title="'+detailsText+'" href="/'+designElems.mainData.url+'/view/'+full.id+'" class="action-icon btn btn-sm btn-clean btn-icon"> <i class="icon-xl la la-eye"></i></a>';
						editButton = '';
					}

					if((designElems.mainData.url == 'tickets' || designElems.mainData.url == 'clients' || designElems.mainData.url == 'invoices') && $('input[name="data-tab"]').val() == 1){
						showButton = '<a data-toggle="tooltip" data-original-title="'+viewText+'" href="/'+designElems.mainData.url+'/view/'+full.id+'" class="action-icon btn btn-sm btn-clean btn-icon"> <i class="icon-xl la la-eye"></i></a>';
					}
					if(designElems.mainData.url == 'tickets'){
						deleteButton = '<a data-toggle="tooltip" data-original-title="'+deleteText+'" onclick="deleteItem('+data+')" class="action-icon btn btn-sm btn-clean btn-icon"> <i class="icon-xl la la-trash"></i></a>'
					}
					if(designElems.mainData.url == 'tickets' && $('input[name="tenant"]').val()){
                    	editButton = '';
                    }

					return editButton + copyButton + showButton + exportButton + deleteButton;
				},
			});
		}
	});

	if(Object.keys(tableData)[Object.keys(tableData).length-1] == 'actions'){
		columnsVar.push({'data': 'id', 'responsivePriority': -1});
	}
	
	// begin first table
	var DataTable = table.DataTable({
		// DOM Layout settings
		dom:'Bfrtip',
		dom:
			"<'row mg-b-25'<'views'l><'listPDF'B><'searchTable'f>>" +
			"<'row'<'col-sm-12 'tr>>" +
			"<'row'<'col-xs-6 col-sm-6 col-md-6 'i><'col-xs-6 col-sm-6 col-md-6 'p>>", // read more: https://datatables.net/examples/basic_init/dom.html
        buttons: [
            {
                extend: 'colvis',
                columns: ':not(.noVis)',
                text: showCols,
            },
            {
             	extend: 'print',
             	customize: function (win) {
                   $(win.document.body).css('direction', direction);     
                },
					'exportOptions': {
			    	columns: ':not(:last-child)',
			  	},
         	},
         	{
             	extend: 'copy',
					'exportOptions': {
			    	columns: ':not(:last-child)',
			  	},
         	},
         	{
             	extend: 'excel',
					'exportOptions': {
			    	columns: ':not(:last-child)',
			  	},
         	},
         	{
             	extend: 'csv',
					'exportOptions': {
			    	columns: ':not(:last-child)',
			  	},
         	},
         	{
             	extend: 'pdf',
					'exportOptions': {
			    	columns: ':not(:last-child)',
			  	},
         	},
        ],
        oLanguage: {
			sSearch: search,
			sInfo: info,
			sLengthMenu: lengthMenu,
			sEmptyTable: emptyTable,
			sProcessing: processing,
			sInfoEmpty: infoEmpty,
			select:{
				rows: {
                	_: rows1,
                    0: "",
                    1: rows2
                }
			},
			oPaginate: {
		      	sPrevious: prev,
		      	sNext: next,
		      	sFirst: first,
		      	sLast: last,
		    },
		},
		drawCallback: function () {
			$('.page-item').addClass('pagination-rounded');
			$('a[data-toggle="tooltip"]').tooltip()
			if(designElems.mainData.url == 'msgsArchive'){
	        	var opts = '<option value="1000">1000</option><option value="50000">50000</option>';
	        	$('select[name="kt_datatable_length"]').append(opts);
	        }
		},
		responsive: false,
		searchDelay: 500,
		processing: true,
		serverSide: true,
		ajax: {
			url: '/'+designElems.mainData.url,
			type: 'GET',
			data:function(dtParms){
				iCounter =1;
				$.each($('.searchForm select'),function(index,item){
			       	dtParms[$(item).attr('name')] = $(item).val();
				});
				$.each($('.searchForm input.datetimepicker-input'),function(index,item){
			       	dtParms[$(item).attr('name')] = $(item).val();
				});
				$.each($('.searchForm input.datepicker'),function(index,item){
			       	dtParms[$(item).attr('name')] = $(item).val();
				});
				if(designElems.mainData.url == 'contacts'){
					$.each(urlParams,function(index,item){
						dtParms[index] = item;
					});
				}
		        dtParms.columnsDef = columnsDef;
		        return dtParms
		    }
		},
		columns: columnsVar,
		columnDefs: columnDefsVar,
	});

	if ($("#kt_search")[0]) {
	    $("#kt_search").on("click", function (t) {
	        t.preventDefault();
	        var e = {};
	        $(".datatable-input").each(function () {
	            var a = $(this).data("col-index");
	            e[a] ? e[a] += "|" + $(this).val() : e[a] = $(this).val();
	        }), $.each(e, function (t, e) {
	            DataTable.column(t).search(e || "", !1, !1);
	        }), DataTable.table().draw();
	    });
	}
	if ($("#kt_reset")[0]) {
	    $("#kt_reset").on("click", function (t) {
	        t.preventDefault(); 
	        $(".datatable-input").each(function () {
	            $(this).val(""); 
	            DataTable.column($(this).data("col-index")).search("", !1, !1);
	        });
	        $(".searchForm select").each(function () {
	            $(this).val(''); 
	            DataTable.column($(this).data("col-index")).search("", !1, !1);
		        $('.searchForm select').selectpicker('refresh')
	        });
	        DataTable.table().draw();
	    });
	}

    $('.navi-print').on('click',function(e){
	    e.preventDefault();
	    e.stopPropagation();
	    $('.buttons-print')[0].click();
	});

	$('.navi-copy').on('click',function(e){
	    e.preventDefault();
	    e.stopPropagation();
	    $('.buttons-copy')[0].click();
	});

	$('.navi-excel').on('click',function(e){
	    e.preventDefault();
	    e.stopPropagation();
	    $('.buttons-excel')[0].click();
	});

	$('.navi-csv').on('click',function(e){
	    e.preventDefault();
	    e.stopPropagation();
	    $('.buttons-csv')[0].click();
	});

	$('.navi-pdf').on('click',function(e){
	    e.preventDefault();
	    e.stopPropagation();
	    $('.buttons-pdf')[0].click();
	});

	$('.quickEdit').on('click',function(e){
	    e.preventDefault();
	    e.stopPropagation();

	    $(this).toggleClass('opened');
	    var myDataObjs = [];
	    var i = 190;
	    $(document).find('table tbody tr td.edits').each(function(index,item){
	        var oldText = '';
	        if($('.quickEdit').hasClass('opened')){
	            var myText = $(item).find('a.editable').text();
	            $(item).find('a.editable').hide();
	            var myElem = '<span qe="scope">'+
	                            '<span>'+
	                                '<input type="text" class="form-control" qe="input" value="'+myText+'"/>'+
	                            '</span>'+
	                        '</span>';
	            if($(this).hasClass('selects')){
	                var selectOptions = '';
	                var selectName = $(this).children('a.editable').data('col');
	                var elem = $("select[name='"+selectName+"'] option");
	                elem.each(function(){
	                    var selected = '';
	                    if($(this).text() == myText){
	                        selected = ' selected';
	                    }
	                    if($(this).val() >= 0){
	                        selectOptions+= '<option value="'+$(this).val()+'" '+selected+'>'+$(this).text()+'</option>';
	                    }
	                });
	                myElem = '<span qe="scope">'+
	                            '<span>'+
	                                '<select class="form-control">'+
	                                    selectOptions+
	                                '</select>'+
	                            '</span>'+
	                        '</span>';
	            }
	            if($(this).hasClass('dates')){
	                myElem = '<span qe="scope">'+
	                            '<span>'+
	                                '<input type="text" class="form-control datetimepicker-input" id="kt_datetimepicker_'+i+'" value="'+myText+'" data-toggle="datetimepicker" data-target="#kt_datetimepicker_'+i+'"'+
	                            '</span>'+
	                        '</span>';
	            }
	            if(!$(item).find('a.dis').length){
	                $(item).append(myElem);
	            }
	            oldText = myText;
	            i++;
	        }else{
	            var myText = '';
	            var newVal = 0;
	            if($(this).hasClass('selects')){
	                myText = $(item).find('select option:selected').text();
	                newVal = $(item).find('select option:selected').val();
	            }else{
	                myText = $(item).find('input.form-control').val();
	            }
	            $(item).children('span').remove();
	            oldText = $(item).find('a.editable').text();
	            $(item).find('a.editable').text(myText);
	            $(item).find('a.editable').show();

	            if(myText != oldText){
	                var myCol = $(item).find('a.editable').data('col');
	                if($(this).hasClass('selects')){
	                    var myValue = newVal;
	                }else{
	                    var myValue = myText;
	                }
	                var myId = $(item).find('a.editable').data('id');
	                myDataObjs.push([myId,myCol,myValue]);
	            }

	        }
	    });

	    $('td.dates span span input.datetimepicker-input').datepicker({
	        enableTime:!0,
	        dateFormat:"Y-m-d H:i:s",
	    });
	    
	    if(myDataObjs[0] != null){
	        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
	        $.ajax({
	            type: 'POST',
	            url: myURL+'/fastEdit',
	            data:{
	                '_token': $('meta[name="csrf-token"]').attr('content'),
	                'data': myDataObjs,
	            },
	            success:function(data){
	                if(data.status.status == 1){
	                    successNotification(data.status.message);
	                    $('#kt_datatable').DataTable().ajax.reload();
	                }else{
	                    errorNotification(data.status.message);
	                    $('#kt_datatable').DataTable().ajax.reload();
	                }
	            },
	        });
	    }
	});

	$('.search-mode').on('click',function(e){
	    e.preventDefault();
	    e.stopPropagation();
	    $('#AdvancedSearchHelp').modal('toggle');
	});

});