require('./bootstrap');
let domain = 'newdomain1';

window.Echo.channel(domain+'-NewIncomingMessage').listen('IncomingMessage', (data) => {
	window.Livewire.emitTo('conversation','newIncomingMsg', data);
})

window.Echo.channel(domain+'-UpdateMessageStatus').listen('MessageStatus', (data) => {
	window.Livewire.emitTo('conversation','changeMessageStatus', data);
})

window.Echo.channel(domain+'-UpdateDialogStatus').listen('DialogUpdateStatus', (data) => {
	window.Livewire.emitTo('chats','changeDialogStatus', data);
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