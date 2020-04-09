window.Pusher = require('pusher-js');

window.pusherInstance = new Pusher('pusher-app-key', {
    cluster: 'us2',
    forceTLS: true,
    encrypted: true,
});

Vue.component('compose-sms', require('./components/ComposeSms.vue').default);
Vue.component('messenger', require('./components/Messenger.vue').default);

