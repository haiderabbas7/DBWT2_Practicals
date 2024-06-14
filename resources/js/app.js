"use strict";

import './bootstrap';
import './data.js'
import './cookiecheck.js'
import './shoppingcart.js'

import { createApp } from 'vue';
const vm = createApp({
    data() {
        return {
            newArticle_csrfToken: '',
            newArticle_name: '',
            newArticle_price: '',
            newArticle_description: '',
            newArticle_status_color: 'green',
            newArticle_status_text: '',
            articleSearchTerm: '',
            articleSearchResults: []
        }
    },
    methods: {
        //der gleiche code wie im Button EventListener, nur natürlich etwas angepasst
        sendNewArticleInfo: function (){
            let name = this.$data.newArticle_name;
            let price = this.$data.newArticle_price;
            let description = this.$data.newArticle_description;
            //Clientseitige Checks
            if(name === '' || isNaN(price) || price <= 0){
                this.newArticle_status_color = 'red';
                this.newArticle_status_text = '<b>FEHLER</b>: Bitte geben Sie gültige Werte ein: Kein leerer Name und nur positive Werte für Preis';            }
            else{
                let xhr = new XMLHttpRequest();
                xhr.open('POST', '/api/articles');
                //hier per Arrow funktion wird this auf das vue objekt gebunden
                xhr.onload = () => {
                    let response = JSON.parse(xhr.responseText);
                    this.newArticle_status_text = response.message;
                    this.newArticle_status_color = 'green';
                    if(response.status === 'Fehler'){
                        this.newArticle_status_color = 'red';
                    }
                };
                let formData = new FormData();
                formData.append("name", name);
                formData.append("price", price);
                formData.append("description", description);
                xhr.send(formData);
            }
            return false;
        },
        searchArticles: async function () {
            try {
                const response = await fetch(`/api/articles?search=${this.articleSearchTerm}`)
                const results = await response.json();
                if(results.length >= 3) {
                    this.articleSearchResults = results.map(article => ({
                        id: article.id,
                        name: article.name,
                        price: article.price,
                        description: article.description,
                        creator_id: article.creator_id,
                        createdate: article.createdate
                    })).slice(0, 5);
                }
                else {
                    this.articleSearchResults = [];
                }
            } catch (error) {
                console.error('Error fetching articles: ' + error);
            }
        }
    }
}).mount('#app');

