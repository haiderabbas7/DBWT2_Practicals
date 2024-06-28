<script>
    import * as math from "mathjs";

    export default {
        name: "articles",
        props: ['userId', 'articleId'],
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
                addToCartButtons: {},
                highlightedArticles: [],
                page: 1
            }
        },
        watch: {
            articleSearchTerm(newVal, oldVal) {
                if(newVal.length >= 3 || oldVal.length > newVal.length) {
                    this.page = 1;
                    this.searchArticles();
                }
                else {
                    this.getAllArticles();
                }
            },
            articleId(newVal, oldVal) {
                if (!this.highlightedArticles.includes(newVal)) {
                    this.highlightedArticles.push(newVal);
                }
                console.log([...this.highlightedArticles]);
            },
            page() {
                this.searchArticles();
            }
        },
        methods: {
            getAllArticles: function() {
                let xhr = new XMLHttpRequest();
                xhr.open('GET',`/api/articles?userId=${this.userId}`);
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
                try {
                    let xhr = new XMLHttpRequest();
                    xhr.open('GET', `/api/articles?search=${this.articleSearchTerm}&userId=${this.userId}&page=${this.page}`);
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
                        }));
                    }
                    xhr.send();
                } catch (error) {
                    console.error('Error fetching articles: ' + error);
                }
            },
            addToCart: function (article) {
                let xhr = new XMLHttpRequest();
                xhr.open('POST', '/api/shoppingcart');
                this.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
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
                this.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
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
                this.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
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
            },
            discountArticle(articleId){
                axios.post(`/api/articles/${articleId}/discounted`)
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
            this.searchArticles();

            this.updateCartDisplay();


        }
    }
</script>

<template>
    <div class="articles__cartDisplay" ref="cartDisplay">
        <h2 class="articles__cartDisplay--title">Warenkorb</h2>
        <span class="articles__cartDisplay--itemCount">Anzahl Produkte: {{shoppingCartCount}}</span> <br>
        <span class="articles__cartDisplay--totalPrice">Preis: {{shoppingCartPrice}}€</span> <br>
        <span class="articles__cartDisplay--avgPrice">Durchschnittspreis: {{shoppingCartAvg}}€</span>
        <table class="articles__cartDisplay--items">
            <tr v-for="article in articleShoppingCart">
                <td v-if="article.id">{{article.name}}</td>
                <td v-if="article.id">{{article.price}}€</td>
                <td v-if="article.id"><button class="removeFromCartButton" :data-id="'Artikel' + article.id" @click="removeFromCart(article)">-</button></td>
            </tr>
        </table>
    </div>
    <input type="text" name="search" v-model="articleSearchTerm"> <br>
    <input type="number" name="page" v-model="page" placeholder="Seite">
    <table class="articles__table">
        <tr v-for="article in articleSearchResults" v-bind:key="articleSearchResults.id"  class="articles__table__row" :class="{ 'articles__table__row--highlighted': highlightedArticles.includes(article.id.toString())}" >
            <td>{{article.name}}</td>
            <td>{{article.price}}</td>
            <td>{{article.description}}</td>
            <td>{{article.creator_id}}</td>
            <td>{{article.createdate}}</td>
            <td><img :src="article.image_path" alt="Article Image"></td>
            <td><button class="addToCartButton" :data-id="'Artikel' + article.id" @click="addToCart(article)" :ref="el => addToCartButtons[article.id] = el">+</button></td>
            <td><button class="buyButton" @click="buyArticle(article.id)">Kaufen</button></td>
            <td v-if="article.creator_id === userId"><button class="discountButton" @click="discountArticle(article.id)">Artikel als Angebot anbieten</button></td>
        </tr>
    </table>
</template>


<style lang="scss" scoped>
$font-color: #007BFF;
$background-color: lightgray;
$hover-color: #f0f0f0;

.articles {
    background-color: $background-color;
    color: $font-color;

    &__table {
        width: 100%;
        border-collapse: collapse;

        &__row {
            border-bottom: 1px solid darken($background-color, 10%);

            &:hover {
                background-color: $hover-color;
                color: darken($font-color, 22%);
                font-weight: bold;
            }

            &--highlighted {
                border: $font-color;
                box-shadow: 0 0 30px $font-color;
                background-color: #56A8FF; /* leicht transparentes Orange */
                color: black; /* Textfarbe */
                transition: all 0.3s ease; /* Übergangsanimation */
            }
        }
    }

    &__cartDisplay {
        margin-bottom: 20px;

        &--title {
            font-size: 1.5em;
            margin-bottom: 10px;
        }

        &--itemCount,
        &--totalPrice,
        &--avgPrice {
            margin-bottom: 5px;
        }
    }
}
</style>
