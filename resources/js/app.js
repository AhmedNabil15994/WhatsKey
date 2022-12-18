require('./bootstrap');
let domain = 'newdomain1';

window.Echo.channel(domain+'-NewIncomingMessage').listen('IncomingMessage', (data) => {
	window.Livewire.emitTo('conversation','newIncomingMsg', data);
    // console.log(data)
})

window.Echo.channel(domain+'-UpdateMessageStatus').listen('MessageStatus', (status) => {
	window.Livewire.emitTo('conversation','changeMessageStatus', status);
})

window.Echo.channel(domain+'-UpdateDialogStatus').listen('DialogUpdateStatus', (status) => {
	window.Livewire.emitTo('chats','changeDialogStatus', status);
})



//       testBroadUpdateChatReadStatus (domain) {
//         // Start socket.io listener
//           Echo.channel(domain+'-UpdateChatReadStatus')
//             .listen('ChatReadStatus', (data) => {
//               console.log(data)
//             })
//           // End socket.io listener
//       },
//       testBroadUpdateChatLabelStatus (domain) {
//         // Start socket.io listener
//           Echo.channel(domain+'-UpdateChatLabelStatus')
//             .listen('ChatLabelStatus', (data) => {
//               console.log(data)
//             })
//           // End socket.io listener
//       },