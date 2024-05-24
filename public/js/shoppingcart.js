"use strict";

let cart = [];

window.onload = function() {
    let buttons = document.getElementsByClassName('addToCartButton');

    for(let i = 0; i < buttons.length; i++) {
        buttons[i].addEventListener('click', function() {
            let articleId = this.getAttribute('data-id');
            let articleName = this.getAttribute('data-name');
            let articlePrice = this.getAttribute('data-price');
            addToCart(articleId, articleName, articlePrice);
            this.disabled = true;
        });
    }

    let xhr = new XMLHttpRequest();
    xhr.open('GET', `/api/shoppingcart/${shoppingcart_id}`);
    xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    xhr.onload = function() {
        cart = JSON.parse(xhr.responseText);

        updateCartDisplay();
        cart.forEach(article =>{
            let button = document.querySelector(`.addToCartButton[data-id="${article.id}"]`);
            button.disabled = true;
        });
    }
    xhr.send();
}


/**
 * Adds an article to the shopping cart
 * @param articleId The ID of the article
 * @param articleName The name of the article
 * @param articlePrice The price of the article
 */
function addToCart(articleId, articleName, articlePrice) {
    cart.push({id: articleId, name: articleName, price: articlePrice});

    let xhr = new XMLHttpRequest();
    xhr.open('POST', '/api/shoppingcart');
    xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));


    xhr.onload = function() {
        updateCartDisplay();
    };

    let formData = new FormData();
    formData.append("article_id", articleId);
    xhr.send(formData);
}


/**
 * Removes an article from the shopping cart
 *
 * @param articleId The ID of the article
 */
function removeFromCart(articleId) {
    cart = cart.filter(article => article.id !== articleId);
    document.querySelector(`.addToCartButton[data-id="${articleId}"]`).disabled = false; // Aktiviert den "Hinzufügen"-Button

    let xhr = new XMLHttpRequest();
    xhr.open('DELETE', `/api/shoppingcart/${shoppingcart_id}/articles/${articleId}`);
    xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    xhr.onload = function() {
        updateCartDisplay();
        window.location.reload();
    };
    xhr.send();
}

/**
 * Updates the shopping cart display
 *
 */
function updateCartDisplay() {
    let cartDisplay = document.getElementById('cartDisplay');
    let tableHtml = '<table>';
    cart.forEach(article => {
        tableHtml += `<tr>
            <td>${article.name}</td>
            <td>${article.price}</td>
            <td><button class="removeFromCartButton" data-id="${article.id}">-</button></td>
        </tr>`;
    });

    tableHtml += '</table>';
    cartDisplay.innerHTML = '<h2>Warenkorb</h2>' +
        'Anzahl Produkte: ' + cart.length +
        '<br>' + ' Preis: ' + sumPrices() + '€' +
            '<br>' + tableHtml;

    // Event Listener für die "removeFromCartButton" hinzufügen
    document.querySelectorAll('.removeFromCartButton').forEach(item => {
        item.addEventListener('click', event => {
            // Artikel-ID aus dem data-id Attribut des Buttons holen
            let articleId = event.target.getAttribute('data-id');
            removeFromCart(articleId);
        })
    });
}

/**
 * Returns the sum of the prices of all articles in the shopping cart
 *
 * @returns The sum of the prices
 */
function sumPrices() {
    let sum = 0;
    for (let i = 0; i < cart.length; i++) {
        sum += parseFloat(cart[i].price);
    }
    return sum;
}
