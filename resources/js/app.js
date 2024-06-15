"use strict";

import './bootstrap';
import './data.js'
import './cookiecheck.js'
import './shoppingcart.js'

import { createApp } from 'vue';
const vm = createApp({
    data() {
        return {
            index_menu: {
                    "Home": [],
                    "Kategorien": [],
                    "Verkaufen": [],
                    "Unternehmen": ["Philosophie", "Karriere"]
            },
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
        searchArticles: function () {
            //wenn searchTerm >= 3 ist, mach API call und pack JSON response auf vue variable articleSearchResults
            if (this.articleSearchTerm.length >= 2) {
                try {
                    let xhr = new XMLHttpRequest();
                    xhr.open('GET', `/api/articles?search=${this.articleSearchTerm}`)
                    xhr.onload = () => {
                        let results = JSON.parse(xhr.responseText);
                        this.articleSearchResults = results.map(article => ({
                            id: article.id,
                            name: article.name,
                            price: article.price,
                            description: article.description,
                            creator_id: article.creator_id,
                            createdate: article.createdate,
                            image_path: article.image_path
                        })).slice(0, 5);
                    }
                    xhr.send();
                } catch (error) {
                    console.error('Error fetching articles: ' + error);
                }
            } else {
                let xhr = new XMLHttpRequest();
                xhr.open('GET', '/api/articles');
                xhr.onload = () => {
                    if (xhr.status === 200) {
                        let results = JSON.parse(xhr.responseText);
                        this.articleSearchResults = results.map(article => ({
                            id: article.id,
                            name: article.name,
                            price: article.price,
                            description: article.description,
                            creator_id: article.creator_id,
                            createdate: article.createdate,
                            image_path: article.image_path
                        }));
                    }
                };
                xhr.send();
            }
        }
    },
    mounted() {
        //Kategorien werden per API Call geladen, nicht mehr über Controller umweg
        let xhr = new XMLHttpRequest();
        xhr.open('GET', '/api/kategorien');
        xhr.onload = () => {
            if (xhr.status === 200) {
                let data = JSON.parse(xhr.responseText);
                this.index_menu.Kategorien = data.map(kategorie => kategorie.name);
            }
        };
        xhr.send();

        // Articles werden per API Call geladen, nicht mehr über Controller Umweg
        xhr = new XMLHttpRequest();
        xhr.open('GET','/api/articles');
        xhr.onload = () => {
            if(xhr.status === 200) {
                let results = JSON.parse(xhr.responseText);
                this.articleSearchResults = results.map(article => ({
                    id: article.id,
                    name: article.name,
                    price: article.price,
                    description: article.description,
                    creator_id: article.creator_id,
                    createdate: article.createdate,
                    image_path: article.image_path
                }));
            }
        };
        xhr.send();
    }
}).mount('#app');

