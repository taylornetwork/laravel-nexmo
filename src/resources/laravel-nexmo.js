
/*
    This block of code is the same as the commented block in the app's bootstrap.js so it can be
    commented out if it's uncommented in bootstrap.js
 */
import Echo from 'laravel-echo';

window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    encrypted: true
});
/*********************************************************************************/

window.Vue = require('vue');

import VueEcho from 'vue-echo';

Vue.use(VueEcho, Echo);

Vue.component('compose-sms', require('./components/ComposeSms.vue').default);
Vue.component('messenger', require('./components/Messenger.vue').default);

