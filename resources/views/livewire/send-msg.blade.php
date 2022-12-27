<div>
    <form class="w-100 sendMsg" enctype="mutlipart/form-data" wire:submit.prevent="fileUpload" >
        <textarea class="sendTextArea form-control" rows="2" wire:keydown.enter="fileUpload" placeholder="Type a message"></textarea>

        <input type="hidden" wire:model="msgType" value="{{$msgType}}" />
        <input type="hidden" name="replyMsgId" wire:model="replyMsgId" value="{{$replyMsgId}}" />

        <input type="file" class="hidden msgFile" wire:model="file" accept=".png,.jpg,.jpeg,.gif,.bmp,.txt,.pdf,.xlsx,.wav,.mp3,.mp4,.m3u,.aac,.vorbis,.flac,.alac,.aiff,.dsd,.ogg,.oga,.ppt,.rar,.zip,.ptt" />
        
        <div class="d-flex align-items-center justify-content-between mt-5">
            <div class="mr-3 row m-0" style="position: relative;">
                <div class="d-inline-block">
                    <a href="#" class="btn btn-clean btn-icon btn-lg mr-1 attachment float-left">
                        <i class="la la-paperclip icon-2x"></i>
                    </a>
                    <a href="#" class="btn btn-clean btn-icon btn-lg emoji float-left">
                        <i class="far fa-smile icon-xl"></i>
                    </a>
                    <emoji-picker class="hidden" locale="en" data-source="{{asset('assets/tenant/js/data.json')}}"></emoji-picker>
                    <button class="btn btn-icon btn-clean btn-lg recordButton float-left" type="button" data-toggle="tooltip" data-original-title="Record"><i class="la la-microphone icon-2x"></i></button>
                    <div class="recordAnimation hidden float-left">
                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>  
                    <button class="btn btn-icon btn-clean btn-lg hidden pauseButton float-left" type="button" data-toggle="tooltip" data-original-title="Pause"><i class="la la-pause-circle icon-2x"></i></button>
                    <button class="btn btn-icon btn-clean btn-lg hidden stopButton float-left" type="button" data-toggle="tooltip" data-original-title="Stop"><i class="la la-stop-circle icon-2x"></i></button>
                    <div class="clearfix"></div>
                </div>
                <div class="d-inline-block">

                    <a href="#" class="btn btn-clean btn-lg mr-1 quickReply"  onclick="Livewire.emit('showQuickReplyModal')">
                        <i class="la la-reply icon-2x"></i> {{trans('main.quickReplies')}}
                    </a>

                    <a href="#" class="btn btn-clean btn-lg mr-1 templates"  onclick="Livewire.emit('showTemplateModal')">
                        <i class="la la-layer-group icon-2x"></i> {{trans('main.templates')}}
                    </a>

                    <a href="#" class="btn btn-clean btn-lg mr-1 contacts"  onclick="Livewire.emit('showContactModal')">
                        <i class="la la-users icon-2x"></i> {{trans('main.contacts')}}
                    </a>

                    <a href="#" class="btn btn-clean btn-lg mr-1 location"  onclick="Livewire.emit('showMapModal')">
                        <i class="la la-map-marker icon-2x"></i> {{trans('main.location')}}
                    </a>
                </div>
            </div>
            <div>
                <button type="submit" id="kt_btn_1" class="btn btn-primary btn-md text-uppercase font-weight-bold chat-send py-2 px-6">{{trans('main.send')}}</button>
            </div>
        </div>
    </form>
    <script>

        $(function(){

            $(document).on('click','.attachment',function(e){
                $('.msgFile')[0].click()
            });

            $(document).on('change','.msgFile',function(e){
                let file = $(this)[0].files[0]
                var sizeLabel = "bytes";
                var filesize = file.size;
                if (filesize > 1024){
                    filesize = filesize / 1024;
                    sizeLabel = "kb";
                    if(filesize > 1024){
                        filesize = filesize / 1024;
                        sizeLabel = "MB";
                    }
                }
                let fileName = file.name+' ('+ Math.round(filesize, 2) +' '+sizeLabel+')';
                $('.uppy-thumbnail-container .uppy-thumbnail-label').text(fileName)
                $('.uppy-thumbnail').empty();
                if(file.type.includes('image')){
                    var url = URL.createObjectURL(file);
                    $('.uppy-thumbnail').append('<img width="50" height="50" src="'+url+'">');
                }else if(file.type.includes('video')){
                    $('.uppy-thumbnail').append('<i class="la la-play icon-xl mt-4"></i>');
                }else if(file.type.includes('audio')){
                    $('.uppy-thumbnail').append('<i class="la la-headphones icon-xl mt-4"></i>');
                }else if(file.type.includes('application') || file.type.includes('text')){
                    $('.uppy-thumbnail').append('<i class="la la-file icon-xl mt-4"></i>');
                }
                $('.uppy-thumbnails.hidden').removeClass('hidden')
                $("html, body").animate({ scrollTop: $(document).height() }, 1000);
            });

            $(document).on('click','.uppy-remove-thumbnail',function(){
                $('.uppy-thumbnails').addClass('hidden')
                $('.msgFile').val('')
                window.livewire.emitTo('send-msg','removeFile')
            })

            var btn = KTUtil.getById("kt_btn_1");
            KTUtil.addEvent(btn, "click", function() {
                KTUtil.btnWait(btn, "spinner spinner-right spinner-white pr-15", "Please wait");

                setTimeout(function() {
                    KTUtil.btnRelease(btn);
                }, 1000);
            });

            $('.emoji').on('click',function(e){
                e.preventDefault();
                $(this).siblings('emoji-picker').toggleClass('hidden')
            });

            $('.sendMsg emoji-picker').unbind('emoji-click');
            $('.sendMsg emoji-picker').on('emoji-click',event => $('.sendTextArea').val($('.sendTextArea').val() + event.detail.unicode))

            $('.sendTextArea,a.btn-clean:not(.emoji),.btn-primary,.card-body').on('click',function(){
                if(!$('.sendMsg emoji-picker').hasClass('hidden')){
                    $('.sendMsg emoji-picker').addClass('hidden')
                    document.querySelector('emoji-picker').database.close()
                }
            })

            $('.emojiItem').on('click',function(e){
                e.preventDefault();
                if($(this).parents('.messageItem').hasClass('emojied')){
                    $(this).siblings('emoji-picker').remove()
                    $(this).parents('.messageItem').removeClass('emojied')
                }else{
                    $(this).parent('.msgEmoji').append('<emoji-picker></emoji-picker>');
                    $(this).parents('.messageItem').addClass('emojied')
                }
               
            });

            $('.messageItem emoji-picker').unbind('emoji-click');
            $(document).on('emoji-click','.messageItem emoji-picker',event => {
                let emoji = event.detail.unicode
                let msgId = $('.messageItem.emojied').attr('id');
                $('.messageItem.emojied .emojiItem').click();
                window.livewire.emitTo('send-msg','reactionMessage',msgId,emoji)
            })

            URL = window.URL || window.webkitURL;
            var gumStream;
            var rec;
            var input;
            var AudioContext = window.AudioContext || window.webkitAudioContext;
            var audioContext = new AudioContext;
            var constraints = {
                audio: true,
                video: false
            } 

            function startRecording() { 
                if($('.pauseButton').hasClass('hidden')){
                    navigator.mediaDevices.getUserMedia(constraints).then(function(stream) {
                        gumStream = stream;
                        input = audioContext.createMediaStreamSource(stream);
                        rec = new Recorder(input, {
                            numChannels: 1
                        }) 
                        rec.record()
                    }).catch(function(err) {});
                    $('.pauseButton,.stopButton,.recordAnimation').toggleClass('hidden')
                }else{
                    rec.stop();
                }
            }

            function pauseRecording() {
                if (rec.recording) {
                    rec.stop();
                    $('.pauseButton').html('<i class="la la-play-circle icon-lg"></i>');
                    $('.pauseButton').data('original-title','Resume');
                } else {
                    rec.record()
                    $('.pauseButton').html('<i class="la la-pause-circle icon-lg"></i>');
                    $('.pauseButton').data('original-title','Pause');
                }
            }

            function stopRecording() {
                $('.pauseButton').html('<i class="la la-pause-circle icon-lg"></i>');
                $('.pauseButton').data('original-title','Pause');
                rec.stop();
                gumStream.getAudioTracks()[0].stop();
                rec.exportWAV(createDownloadLink);
                $('.pauseButton,.stopButton,.recordAnimation').addClass('hidden')
            }

            function createDownloadLink(blob) {
                // var url = URL.createObjectURL(blob);
                var formdata = new FormData();
                formdata.append('audio-blob', blob);

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: 'POST',
                    url: '/livechat/upload',
                    data: formdata,
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        window.livewire.emit('uploadBlob');
                        $('.sendMsg .btn-primary').click()
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) { 
                    }                              
                });
            }

            $('.recordButton').on('click',function(){
                startRecording();
            });

            $('.pauseButton').on('click',function(){
                pauseRecording();
            });

            $('.stopButton').on('click',function(){
                stopRecording();
            });

            $('.replyItem').on('click',function(e){
                let chatName = $(this).data('name');
                let msgId = $(this).data('id');
                let msgText = $(this).parents('.messageItem').find('div.replyBody').html()
                $('#kt_scrollDown').click()
                $('.msgReplyHeader').data('id',msgId)
                $('.msgReplyHeader .replyName').html(chatName)
                $('.msgReplyHeader .replyBody').html(msgText)
                $('input[name="replyMsgId"]').val(msgId)
                $('.msgReplyHeader').slideDown(500);
                $('.sendMsg textarea').focus()
            });

            $('.closeReplyHeader').on('click',function(){
                $('input[name="replyMsgId"]').val(0)
                $('.msgReplyHeader').slideUp(500);
            })

            $('.forwardItem').on('click',function(){
                let msgId = $(this).data('id');
                $('input[name="replyMsgId"]').val(msgId)
                $('#forwardModal select').val('')
                $('select[data-toggle="select2"]').select2()
                $('#forwardModal').modal('show');
            });

            $('.forwardMsg').on('click',function(e){
                e.preventDefault()
                var contactId=  $('#forwardModal select option:selected').val()
                window.livewire.emitTo('send-msg','forwardMsg',contactId,$('input[name="replyMsgId"]').val())
                $('#forwardModal').modal('hide');
            });

            $('.deleteForMeItem').on('click',function(){
                let msgId = $(this).data('id');
                window.livewire.emitTo('send-msg','deleteForMeMsg',msgId)
            });

            $('.deleteForAllItem').on('click',function(){
                let msgId = $(this).data('id');
                window.livewire.emitTo('send-msg','deleteForAllMsg',msgId)
            });

            $('.starItem').on('click',function(){
                let msgId = $(this).data('id');
                window.livewire.emitTo('send-msg','starMsg',msgId)
            });

            $('.labelItem').on('click',function(){
                let msgId = $(this).data('id');
                let labelText = $(this).data('labels');
                labelText = labelText.indexOf(',') > 0 ? labelText.replace(/,\s*$/, "") : labelText
                let labels = labelText != '' ? JSON.parse("[" + labelText + "]") : '';
                $('#labelsModal select').val(labels)
                $('#labelsModal .selectLabels').attr('data-id',msgId);
                $('#labelsModal .selectLabels').attr('data-type',1);
                $('select[data-toggle="select2"]').select2()
                $('#labelsModal').modal('show');
            });
        })
    </script>
</div>