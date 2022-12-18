<div class="sendMsg">
    <div class="w-100">
        <textarea class="sendTextArea form-control" rows="2" placeholder="Type a message"></textarea>
        <input type="hidden" wire:model="msgType" value="{{$msgType}}">
        <div class="d-flex align-items-center justify-content-between mt-5">
            <div class="mr-3" style="position: relative;">
                <a href="#" class="btn btn-clean btn-icon btn-md mr-1">
                    <i class="flaticon2-photograph icon-lg"></i>
                </a>
                <a href="#" class="btn btn-clean btn-icon btn-md">
                    <i class="flaticon2-photo-camera icon-lg"></i>
                </a>
                <a href="#" class="btn btn-clean btn-icon btn-md emoji">
                    <i class="far fa-smile icon-lg"></i>
                </a>
                <emoji-picker class="hidden"></emoji-picker>
            </div>
            <div>
                <button type="button" class="btn btn-primary btn-md text-uppercase font-weight-bold chat-send py-2 px-6" wire:click="sendMsg">Send</button>
            </div>
        </div>
    </div>
</div>