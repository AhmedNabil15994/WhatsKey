/**
 * Comments Js
 */


function deleteComment($id) {
    Swal.fire({
        title: title,
        text: deleteText,
        type: "warning",
        showCancelButton: true,
        confirmButtonText: confirmButton,
        confirmButtonClass: 'btn btn-success mt-2',
        cancelButtonText: cancelButton,
        cancelButtonClass: 'btn btn-danger ml-2 mt-2',
        closeOnConfirm: false,
        buttonsStyling:!1
    }).then(function(result){
        if (result.value) {
            Swal.fire(success1, success2, "success");
            $.get(myURL+'/removeComment/' + $id,function(data) {
                if (data.status.original.status.status == 1) {
                    successNotification(data.status.original.status.message);
                    location.reload()
                } else {
                    errorNotification(data.status.original.status.message);
                }
            });
        } else if (result.dismiss === "cancel") {
            Swal.fire(
                cancel1,
                cancel2,
                "error"
            )
        }
    });
}


$(document).on('click','.actions.reply',function(e){
    e.preventDefault();
    e.stopPropagation();
    var comment_id = $(this).attr('data-area');
    $('html, body').animate({
        scrollTop: $("#kt_forms_widget_11_input").offset().top
    }, 350);
    $('.newComm').attr('data-area',comment_id);
});

$('.newComm').on('click',function(e){
    e.preventDefault();
    e.stopPropagation();
    sendComment($(this).attr('data-area'));
});

function sendComment(reply){
    var url = window.location.href;
    if(url.indexOf("#") != -1){
        url = url.replace('#','');
    }
    var myURL = url+'/addComment';
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
    $.ajax({
        type:'post',
        url: myURL,
        data:{
            '_token': $('meta[name="csrf-token"]').attr('content'),
            'comment': $('textarea.comment').val(),
            'reply': reply,
        },
        success:function(data){
            if(data.status.status == 1){
                successNotification(data.status.message);
                location.reload();
            }else{
                errorNotification(data.status.message);
            }
        },
    });
}


$('.attach').on('click',function(e){
    e.preventDefault();
    e.stopPropagation();
    $('#commentFile').trigger('click');
});

Dropzone.options.myAwesomeDropzone = false;
Dropzone.autoDiscover = false;
$('#commentFile').dropzone({
    url: myURL + "/uploadCommentFile", // Set the url for your upload script location
    paramName: "attachs", // The name that will be used to transfer the file
    maxFiles: 1,
    maxFilesize: 10, // MB
    addRemoveLinks: true,
    accept: function(file, done) {
        if (file.name == "justinbieber.jpg") {
            done("Naha, you don't.");
        } else {
            done();
        }
    },
    success:function(file,data){
        if(data){
            if(data.status.status != 1){
                errorNotification(data.status.message);
            }
        }
    },
});  