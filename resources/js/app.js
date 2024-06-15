"use strict";
import * as math from 'mathjs';

import './bootstrap';
import './data.js'
import './cookiecheck.js'
// import './shoppingcart.js'

/**
 * #TODO articleShoppingCart nach neu Laden anzeigen lassen
 */
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
            articleSearchResults: [],
            articleShoppingCart: [],
            shoppingCartCount: 0,
            shoppingCartPrice: 0,
            shoppingCartAvg: 0,
            shoppingCartId: 1
        }
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
        addToCart: function (articleId, articleName, articlePrice, button) {
            this.articleShoppingCart.push({id: articleId, name: articleName, price: articlePrice});

            let xhr = new XMLHttpRequest();
            xhr.open('POST', '/api/shoppingcart');
            xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            xhr.onload = () => {
                console.error(JSON.parse(xhr.responseText));
                this.updateCartDisplay();
            };

            let formData = new FormData();
            formData.append("article_id", articleId);
            xhr.send(formData);
            button.disabled = true;
        },
        removeFromCart: function (articleId) {
            let button = document.querySelector(`.addToCartButton[data-id="Artikel${CSS.escape(articleId)}"]`); // Aktiviert den "Hinzufügen"-Button
            if (button) {
                button.disabled = false;
            } else {
                console.error("Button not found");
            }

            let xhr = new XMLHttpRequest();
            xhr.open('DELETE', `/api/shoppingcart/${this.shoppingCartId}/articles/${articleId}`);
            xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            xhr.onload = () => {
                console.error(JSON.parse(xhr.responseText));
                this.articleShoppingCart = this.articleShoppingCart.filter(article => article.id !== articleId);
                this.updateCartDisplay();
            };
            xhr.send();
        },
        updateCartDisplay: function () {
            this.shoppingCartPrice = this.sumPrices();
            this.shoppingCartCount = this.articleShoppingCart.length;
            this.shoppingCartAvg = this.averagePrice();
            /*
            let cartDisplay = document.getElementById('cartDisplay');
            let tableHtml = '<table>';
            this.articleShoppingCart.forEach(article => {
                tableHtml += `<tr>
                    <td>${article.name}</td>
                    <td>${article.price}</td>
                    <td><button class="removeFromCartButton" data-id="${article.id}">-</button></td>
                </tr>`;
            });

            tableHtml += '</table>';
            cartDisplay.innerHTML = '<h2>Warenkorb</h2>' +
                'Anzahl Produkte: ' + this.articleShoppingCart.length +
                '<br>' + ' Preis: ' + this.sumPrices() + '€' +
                '<br>' + ' Durchschnittspreis: ' + this.averagePrice() + '€' +
                '<br>' + tableHtml;

            // Event Listener für die "removeFromCartButton" hinzufügen
            document.querySelectorAll('.removeFromCartButton').forEach(item => {
                item.addEventListener('click', event => {
                    // Artikel-ID aus dem data-id Attribut des Buttons holen
                    let articleId = event.target.getAttribute('data-id');
                    this.removeFromCart(articleId);
                });
            });

             */
        },
        initializeShoppingCart: function (buttons) {
            /*for (let i = 0; i < buttons.length; i++) {
                buttons[i].addEventListener('click', function() {
                    let articleId = this.getAttribute('data-id');
                    let articleName = this.getAttribute('data-name');
                    let articlePrice = this.getAttribute('data-price');
                    this.addToCart(articleId, articleName, articlePrice, this);
                    this.disabled = true;
                });
            }*/

            let xhr = new XMLHttpRequest();
            xhr.open('GET', `/api/shoppingcart/${this.shoppingCartId}`);
            xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            xhr.onload = function() {
                this.articleShoppingCart.push(JSON.parse(xhr.responseText));
                // #TODO articleShoppingCart anzeigen lassen
                this.articleShoppingCart.forEach(article => {
                    let button = document.querySelector(`.addToCartButton[data-id="Artikel${CSS.escape(article.id)}"]`);
                    if (article in this.articleShoppingCart && button) {
                        button.disabled = true;
                    }
                    else if(button) {
                        button.disabled = false;
                    }
                    else {
                        console.error("Button not Found");
                    }
                });
                this.updateCartDisplay();
            }
            xhr.send();
        },
        sumPrices: function () {
            let prices = this.articleShoppingCart.map(article => parseFloat(article.price));
            return prices.length ? math.sum(prices) : 0;
        },
        averagePrice: function() {
            let prices = this.articleShoppingCart.map(article => parseFloat(article.price));
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

        let buttons = document.getElementsByClassName('addToCartButton');
        if(buttons.length > 0) {
            this.initializeShoppingCart(buttons);
        }
    }
}).mount('#app');

