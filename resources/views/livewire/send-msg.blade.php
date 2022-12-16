<div class="sendMsg">
    <div class="w-100">
        <textarea class="form-control border-0 p-0" rows="2" placeholder="Type a message" wire:keydown.enter="sendMsg" wire:model="msgBody"></textarea>
        <div class="d-flex align-items-center justify-content-between mt-5">
            <div class="mr-3">
                <a href="#" class="btn btn-clean btn-icon btn-md mr-1">
                    <i class="flaticon2-photograph icon-lg"></i>
                </a>
                <a href="#" class="btn btn-clean btn-icon btn-md">
                    <i class="flaticon2-photo-camera icon-lg"></i>
                </a>
            </div>
            <div>
                <button type="button" class="btn btn-primary btn-md text-uppercase font-weight-bold chat-send py-2 px-6">Send</button>
            </div>
        </div>
    </div>
</div>