<div>
    <form class="w-100 sendMsg" enctype="mutlipart/form-data" wire:submit.prevent="fileUpload" >
        <textarea class="sendTextArea form-control" rows="2" wire:keydown.enter="fileUpload" placeholder="Type a message"></textarea>

        <input type="hidden" wire:model="msgType" value="{{$msgType}}" />

        <input type="file" class="hidden msgFile" wire:model="file" accept=".png,.jpg,.jpeg,.gif,.bmp,.txt,.pdf,.xlsx,.wav,.mp3,.mp4,.m3u,.aac,.vorbis,.flac,.alac,.aiff,.dsd,.ogg,.oga,.ppt,.rar,.zip,.ptt" />
              
        <div class="d-flex align-items-center justify-content-between mt-5">
            <div class="mr-3" style="position: relative;">
                <div class="d-inline-block">
                    <a href="#" class="btn btn-clean btn-icon btn-lg mr-1 attachment">
                        <i class="la la-paperclip icon-2x"></i>
                    </a>
                    <a href="#" class="btn btn-clean btn-icon btn-lg emoji">
                        <i class="far fa-smile icon-xl"></i>
                    </a>
                    <emoji-picker class="hidden"></emoji-picker>
                    <button class="btn btn-icon btn-clean btn-lg recordButton" type="button" data-toggle="tooltip" data-original-title="Record"><i class="la la-microphone icon-2x"></i></button>
                    <button class="btn btn-icon btn-clean btn-lg hidden pauseButton" type="button" data-toggle="tooltip" data-original-title="Pause"><i class="la la-pause-circle icon-2x"></i></button>
                    <button class="btn btn-icon btn-clean btn-lg hidden stopButton" type="button" data-toggle="tooltip" data-original-title="Stop"><i class="la la-stop-circle icon-2x"></i></button>
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
                <button type="submit" class="btn btn-primary btn-md text-uppercase font-weight-bold chat-send py-2 px-6">{{trans('main.send')}}</button>
            </div>
        </div>
    </form>
    <script>

        $(function(){
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
                    $('.pauseButton,.stopButton').toggleClass('hidden')
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
                $('.pauseButton,.stopButton').addClass('hidden')
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

            $('.emoji').on('click',function(e){
                e.preventDefault();
                $('emoji-picker').toggleClass('hidden')
            });

            $('emoji-picker').unbind('emoji-click');
            $('emoji-picker').on('emoji-click',event => $('.sendTextArea').val($('.sendTextArea').val() + event.detail.unicode))

            $('.sendTextArea,a.btn-clean:not(.emoji),.btn-primary,.card-body').on('click',function(){
                if(!$('emoji-picker').hasClass('hidden')){
                    $('emoji-picker').addClass('hidden')
                    document.querySelector('emoji-picker').database.close()
                }
            })
        })
    </script>
</div>