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
}
function addToCart(articleId, articleName, articlePrice) {
    // Überprüfen, ob der Artikel bereits im Warenkorb ist
    if (!cart.some(article => article.id === articleId)) {
        cart.push({id: articleId, name: articleName, price: articlePrice});
        updateCartDisplay();
    }
}
function removeFromCart(articleId) {
    cart = cart.filter(article => article.id !== articleId);
    updateCartDisplay();
    document.querySelector(`.addToCartButton[data-id="${articleId}"]`).disabled = false; // Aktiviert den "Hinzufügen"-Button
}

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

function sumPrices() {
    let sum = 0;
    for (let i = 0; i < cart.length; i++) {
        sum += parseFloat(cart[i].price);
    }
    return sum;
}
