require('./bootstrap');
let domain = window.location.host.split('.')[1] ? window.location.host.split('.')[0] : false;

window.Echo.channel(domain+'-NewIncomingMessage').listen('IncomingMessage', (data) => {
	window.Livewire.emitTo('conversation','newIncomingMsg', data);
})

window.Echo.channel(domain+'-UpdateMessageStatus').listen('MessageStatus', (data) => {
	window.Livewire.emitTo('conversation','changeMessageStatus', data);
})

window.Echo.channel(domain+'-UpdateDialogStatus').listen('DialogUpdateStatus', (data) => {
	window.Livewire.emitTo('chats','changeDialogStatus', data);
})

window.Echo.channel(domain+'-UpdateDialogPresence').listen('DialogPresenceStatus', (data) => {
	window.Livewire.emitTo('chats','updateDialogPresence', data);
})

window.Echo.channel(domain+'-UpdateDialog').listen('DialogUpdate', (data) => {
	window.Livewire.emitTo('conversation','updateChat', data['chatId']);
})

window.Echo.channel(domain+'-DeleteDialog').listen('DialogDelete', (data) => {
	window.Livewire.emitTo('chats','chatsChanges', data['data']['chatId'],true);
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