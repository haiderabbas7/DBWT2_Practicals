<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Abalo</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        #articleForm {
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
    </style>
</head>
<body>
<script>
    "use strict";

    let con = @json($con);
    if(con === 2){
        alert('Bitte geben Sie gültige Werte ein: Kein leerer Name und nur positive Werte für Preis.');
        con = 1;
    }
    else if(con === 3){
        alert('Fehler beim Einfügen in Datenbank, bitte gültige Werte eingeben.');
        con = 1;
    }

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
    saveButton.textContent = 'Speichern';
    form.appendChild(saveButton);

    document.body.appendChild(form);

    document.getElementById('articleForm').addEventListener('submit', function(event) {
        // verhindert die default ausführung von submit, damit man selber kontrolle hat
        event.preventDefault();

        let name = nameInput.value;
        let price = priceInput.value;

        if (name === '' || isNaN(price) || price <= 0) {
            alert('Bitte geben Sie gültige Werte ein.');
        }
        else {
            this.submit();
        }
    });
</script>
</body>
</html>
