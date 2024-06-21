"use strict";

import './bootstrap';
import './data.js'
import './cookiecheck.js'
// import './shoppingcart.js'

//KOMPONENTEN IMPORTS, DAS @ STEHT FÜR DAS js Verzeichnis (hat der automatisch gemacht idfk)
import MyCounter from "@/components/my-counter.vue";
import Siteheader from "@/components/siteheader.vue";
import Sitebody from "@/components/sitebody.vue";
import Sitefooter from "@/components/sitefooter.vue";

import * as math from 'mathjs';

import { createApp } from 'vue';
const vm = createApp({
    data() {
        return {
            newArticle_name: '',
            newArticle_price: '',
            newArticle_description: '',
            newArticle_status_color: 'green',
            newArticle_status_text: '',
            articleSearchTerm: '',
            articleSearchResults: [],
            articleShoppingCart: [],
            shoppingCartCount: 0,
            shoppingCartPrice: 0,
            shoppingCartAvg: 0,
            shoppingCartId: 1
        }
    },
    //NEUE KOMPONENTEN HIER EINTRAGEN UNTER DEM IMPORT NAMEN, SONST GEHTS NICHT
    components: {
        MyCounter,
        Siteheader,
        Sitebody,
        Sitefooter
    },
    watch: {
        articleSearchTerm(newVal, oldVal) {
            if(newVal.length >= 3 || oldVal.length > newVal.length) {
                this.searchArticles();
            }
            else {
                this.getAllArticles();
            }
        }
    },
    methods: {
        //der gleiche code wie im Button EventListener, nur natürlich etwas angepasst
        sendNewArticleInfo: function () {
            let name = this.$data.newArticle_name;
            let price = this.$data.newArticle_price;
            let description = this.$data.newArticle_description;
            //Clientseitige Checks
            if (name === '' || isNaN(price) || price <= 0) {
                this.newArticle_status_color = 'red';
                this.newArticle_status_text = '<b>FEHLER</b>: Bitte geben Sie gültige Werte ein: Kein leerer Name und nur positive Werte für Preis';
            } else {
                let xhr = new XMLHttpRequest();
                xhr.open('POST', '/api/articles');
                //hier per Arrow funktion wird this auf das vue objekt gebunden
                xhr.onload = () => {
                    let response = JSON.parse(xhr.responseText);
                    this.newArticle_status_text = response.message;
                    this.newArticle_status_color = 'green';
                    if (response.status === 'Fehler') {
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
        getAllArticles: function() {
            let xhr = new XMLHttpRequest();
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
        },
        searchArticles: function () { // Wenn >= 2 automatisch Ausführen
            //wenn searchTerm >= 2 ist, mach API call und pack JSON response auf vue variable articleSearchResults
            if (this.articleSearchTerm.length > 0) {
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
                this.getAllArticles();
            }
        },
        addToCart: function (article) {
            let xhr = new XMLHttpRequest();
            xhr.open('POST', '/api/shoppingcart');
            xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            xhr.onload = () => {
                this.updateCartDisplay();
            };

            let formData = new FormData();
            formData.append("article_id", article.id);
            xhr.send(formData);
        },
        removeFromCart: function (rArticle) {
            let button = document.querySelector(`.addToCartButton[data-id="Artikel${CSS.escape(rArticle.id)}"]`); // Aktiviert den "Hinzufügen"-Button

            let xhr = new XMLHttpRequest();
            xhr.open('DELETE', `/api/shoppingcart/${this.shoppingCartId}/articles/${rArticle.id}`);
            xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            xhr.onload = () => {
                this.updateCartDisplay();
                if (button) {
                    button.disabled = false;
                } else {
                    console.error("Button not found");
                }
            };
            xhr.send();

        },
        updateCartDisplay: function () {
            let xhr = new XMLHttpRequest();
            xhr.open('GET', `/api/shoppingcart/${this.shoppingCartId}`);
            xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            xhr.onload = () => {
                let result = JSON.parse(xhr.responseText);
                let array = [];
                for(let article of result) {
                    array = array.concat(article);
                }
                this.articleShoppingCart = array;
                this.shoppingCartPrice = this.sumPrices();
                this.shoppingCartCount = this.articleShoppingCart.length;
                this.shoppingCartAvg = this.averagePrice();

                if(this.articleShoppingCart.length > 0) {
                    this.articleShoppingCart.forEach(article => {
                        let button = document.querySelector(`.addToCartButton[data-id="Artikel${CSS.escape(article.id)}"]`);
                        if (button) {
                            button.disabled = this.articleShoppingCart.includes(article);
                        }
                        else {
                            console.error("Button not Found");
                        }
                    });
                }
            }
            xhr.send();
        },
        sumPrices: function () {
            let prices = this.articleShoppingCart.map(article => {
                let price = parseFloat(article.price);
                return isNaN(price) ? 0 : price; // Wenn der Preis NaN ist, verwenden Sie 0
            });
            return prices.length ? math.sum(prices) : 0;
        },
        averagePrice: function() {
            let prices = this.articleShoppingCart.map(article => {
                let price = parseFloat(article.price);
                return isNaN(price) ? 0 : price; // Wenn der Preis NaN ist, verwenden Sie 0
            });
            return prices.length ? math.mean(prices) : 0;
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

        let params = new URLSearchParams(window.location.search);
        this.articleSearchTerm = params.get('search') || '';
        // Articles werden per API Call geladen, nicht mehr über Controller Umweg
        if(this.articleSearchTerm.length > 0) {
            this.searchArticles();
        }
        else {
            this.getAllArticles();
        }
        this.updateCartDisplay();
    }
}).mount('#app');

