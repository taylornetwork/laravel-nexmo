window.Pusher = require('pusher-js');

window.pusherInstance = new Pusher('your-pusher-app-key', {
    cluster: 'us2',
    forceTLS: true,
    encrypted: true,
});

import VJsoneditor from 'v-jsoneditor'

Vue.use(VJsoneditor)

Vue.component('ivr-builder', require('./components/Ivr/IvrBuilder').default)