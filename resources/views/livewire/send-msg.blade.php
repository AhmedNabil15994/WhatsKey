<div class="sendMsg">
    <form class="w-100" enctype="mutlipart/form-data" wire:submit.prevent="fileUpload">
        <textarea class="sendTextArea form-control"  rows="2" placeholder="Type a message"></textarea>
        <input type="hidden" wire:model="msgType" value="{{$msgType}}">
        <input type="file" class="hidden" wire:model="file" accept=".png,.jpg,.jpeg,.gif,.bmp,.txt,.pdf,.xlsx,.wav,.mp3,.mp4,.m3u,.aac,.vorbis,.flac,.alac,.aiff,.dsd,.ogg,.oga,.ppt,.rar,.zip,.ptt">
        <div class="d-flex align-items-center justify-content-between mt-5">
            <div class="mr-3" style="position: relative;">
                <a href="#" class="btn btn-clean btn-icon btn-md mr-1 attachment">
                    <i class="la la-paperclip icon-lg"></i>
                </a>
                <a href="#" class="btn btn-clean btn-icon btn-md emoji">
                    <i class="far fa-smile icon-lg"></i>
                </a>
                <emoji-picker class="hidden"></emoji-picker>
            </div>
            <div>
                <button type="submit" class="btn btn-primary btn-md text-uppercase font-weight-bold chat-send py-2 px-6">Send</button>
            </div>
        </div>
    </form>
</div>


