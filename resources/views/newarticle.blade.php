<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Abalo</title>
    <style>
        body {
            font-family: Calibri, serif;
        }
        #articleForm {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f8f8;
            border-radius: 5px;
        }
        #articleForm input, #articleForm textarea {
            width: 95%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        #articleForm button {
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        #articleForm button:hover {
            background-color: #0056b3;
        }
        #statusText {
            text-align: center;
            background-color: #f8f8f8;
            padding: 10px;
            border-radius: 4px;
            margin-top: 10px;
        }

    </style>
    <script src="{{ asset('js/cookiecheck.js') }}"> </script>
</head>
<body>
<script>
    "use strict";

    let form = document.createElement('form');
    form.setAttribute('method', 'post');
    form.setAttribute('action', '/articles');
    form.setAttribute('id', 'articleForm');

    //csrf token wird mitgeschickt, damit kein 419 error aufkommt
    let csrfInput = document.createElement('input');
    csrfInput.setAttribute('type', 'hidden');
    csrfInput.setAttribute('name', '_token');
    csrfInput.setAttribute('value', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    form.appendChild(csrfInput);

    let nameInput = document.createElement('input');
    nameInput.setAttribute('type', 'text');
    nameInput.setAttribute('name', 'name');
    nameInput.setAttribute('placeholder', 'Artikelname');
    form.appendChild(nameInput)

    let priceInput = document.createElement('input');
    priceInput.setAttribute('type', 'text');
    priceInput.setAttribute('name', 'price');
    priceInput.setAttribute('placeholder', 'Preis');
    form.appendChild(priceInput);

    let descInput = document.createElement('textarea');
    descInput.setAttribute('name', 'description');
    descInput.setAttribute('placeholder', 'Beschreibung');
    form.appendChild(descInput);

    let saveButton = document.createElement('button');
    saveButton.setAttribute('id', 'button');
    saveButton.textContent = 'Speichern';
    form.appendChild(saveButton);

    document.body.appendChild(form);

    //status-text gibt den status der anfrage aus
    let statusText = document.createElement('p');
    statusText.setAttribute('id', 'statusText');
    form.appendChild(statusText);

    document.getElementById('button').addEventListener('click', event => {
        event.preventDefault();
        let name = nameInput.value;
        let price = priceInput.value;
        let description = descInput.value;
        let statusText = document.getElementById('statusText');

        if (name === '' || isNaN(price) || price <= 0) {
            statusText.innerHTML = '<b>FEHLER</b>: Bitte geben Sie gültige Werte ein: Kein leerer Name und nur positive Werte für Preis';
            statusText.style.color = 'red';
        }
        else {
            let xhr = new XMLHttpRequest();
            xhr.open('POST', '/api/articles');
            xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            //onload ist basically wie status=4 in Aufg.1, also wenn daten vom server ankommen
            xhr.onload = function() {
                var response = JSON.parse(xhr.responseText);
                if (response.status === 'Erfolg') {
                    statusText.style.color = 'green';
                } else if (response.status === 'Fehler') {
                    statusText.style.color = 'red';
                }
                statusText.innerHTML = response.message;
            };

            let formData = new FormData();
            formData.append("name", name);
            formData.append("price", price);
            formData.append("description", description);
            xhr.send(formData);
        }
        return false;
    });
</script>
</body>
</html>
