import './bootstrap';
import './data.js'
import './cookiecheck.js'
import './shoppingcart.js'

import { createApp } from 'vue';
const vm = createApp({
    data() {
        return {
            msg: 'aaa!',
            newArticle_csrfToken: "",
            newArticle_name: "",
            newArticle_price: "",
            newArticle_description: "",
            newArticle_status: ""
        }
    },
    methods: {
        sendNewArticleInfo: function (){

        }
    }
}).mount('#app');
