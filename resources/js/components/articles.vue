<script>
    import * as math from "mathjs";

    export default {
        name: "articles",
        data() {
            return {
                csrfToken: null,
                articleSearchTerm: '',
                articleSearchResults: [],
                articleShoppingCart: [],
                shoppingCartCount: 0,
                shoppingCartPrice: 0,
                shoppingCartAvg: 0,
                shoppingCartId: 1,
                addToCartButtons: {}
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
                        xhr.open('GET', `/api/articles?search=${this.articleSearchTerm}`);
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
                xhr.setRequestHeader('X-CSRF-TOKEN', this.csrfToken);

                xhr.onload = () => {
                    this.updateCartDisplay();
                };
                let formData = new FormData();
                formData.append("article_id", article.id);
                xhr.send(formData);
            },
            removeFromCart: function (rArticle) {
                let button = this.addToCartButtons[rArticle.id];

                let xhr = new XMLHttpRequest();
                xhr.open('DELETE', `/api/shoppingcart/${this.shoppingCartId}/articles/${rArticle.id}`);
                xhr.setRequestHeader('X-CSRF-TOKEN', this.csrfToken);
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
                xhr.setRequestHeader('X-CSRF-TOKEN', this.csrfToken);
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
                            let button = this.addToCartButtons[article.id];
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
            },
            buyArticle(articleId) {
                axios.post(`/api/articles/${articleId}/sold`)
                    .then(response => {
                        console.log(response.data);
                    })
                    .catch(error => {
                        console.error(error);
                    });
            }
        },
        mounted() {
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
            this.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        }
    }
</script>

<template>
    <div id="cartDisplay" ref="cartDisplay">
        <h2>Warenkorb</h2>
        <span id="cartCount">Anzahl Produkte: {{shoppingCartCount}}</span> <br>
        <span id="pricetag">Preis: {{shoppingCartPrice}}€</span> <br>
        <span id="avgPrice">Durchschnittspreis: {{shoppingCartAvg}}€</span>
        <table id="shoppingcartItems">
            <tr v-for="article in articleShoppingCart">
                <td v-if="article.id">{{article.name}}</td>
                <td v-if="article.id">{{article.price}}€</td>
                <td v-if="article.id"><button class="removeFromCartButton" :data-id="'Artikel' + article.id" @click="removeFromCart(article)">-</button></td>
            </tr>
        </table>
    </div>
    <input type="text" name="search" v-model="articleSearchTerm">
    <table>
        <tr v-for="article in articleSearchResults" v-bind:key="articleSearchResults.id">
            <td>{{article.name}}</td>
            <td>{{article.price}}</td>
            <td>{{article.description}}</td>
            <td>{{article.creator_id}}</td>
            <td>{{article.createdate}}</td>
            <td><img :src="article.image_path" alt="Article Image"></td>
            <td><button class="addToCartButton" :data-id="'Artikel' + article.id" @click="addToCart(article)" :ref="el => addToCartButtons[article.id] = el">+</button></td>
            <td><button class="buyButton" @click="buyArticle(article.id)">Kaufen</button></td>
        </tr>
    </table>
</template>

<style scoped>

</style>
