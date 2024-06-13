import './bootstrap';
import './data.js'
import './cookiecheck.js'
import './shoppingcart.js'

import { createApp } from 'vue';
const vm = createApp({
    data() {
        return { msg: 'a!' }
    }
}).mount('#app');

