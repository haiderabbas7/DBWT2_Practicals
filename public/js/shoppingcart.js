let cart = [];

window.onload = function() {
    let buttons = document.getElementsByClassName('addToCartButton');

    for(let i = 0; i < buttons.length; i++) {
        buttons[i].addEventListener('click', function() {
            let articleId = this.getAttribute('data-id');
            addToCart(articleId);
        });
    }
}
function addToCart(articleId) {
    // Überprüfen, ob der Artikel bereits im Warenkorb ist
    if (!cart.includes(articleId)) {
        cart.push(articleId);
        updateCartDisplay();
    }
}

function removeFromCart(articleId) {
    let index = cart.indexOf(articleId);
    if (index > -1) {
        cart.splice(index, 1);
        updateCartDisplay();
    }
}

function updateCartDisplay() {
    // Hier können Sie den Code hinzufügen, um die Darstellung des Warenkorbs zu aktualisieren
    // Zum Beispiel könnten Sie die Anzahl der Artikel im Warenkorb anzeigen
    document.getElementById('cartCount').innerText = cart.length;
}
