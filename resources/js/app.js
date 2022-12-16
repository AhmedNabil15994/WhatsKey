require('./bootstrap');
let domain = 'newdomain1';

window.Echo.channel(domain+'-NewIncomingMessage').listen('IncomingMessage', (data) => {
	window.Livewire.emitTo('conversation','newIncomingMsg', data);
    console.log(data)
})