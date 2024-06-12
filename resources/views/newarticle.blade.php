<!DOCTYPE html>
<html lang="de">
<head>
    @vite('resources/js/app.js')
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
</head>
<body id="app">
    <form method="post" action="/articles" id="articleForm">
        @csrf
        <input type="text" name="name" placeholder="Artikelname" v-model="newArticle_name">
        <input type="text" name="price" placeholder="Preis" v-model="newArticle_price">
        <textarea name="description" placeholder="Beschreibung" v-model="newArticle_description"></textarea>
        <button id="button" v-on:click.prevent="sendNewArticleInfo">Speichern</button>
        <p id="statusText" v-bind:style="{ color: newArticle_status_color }" v-html="newArticle_status_text"></p>
    </form>
</body>
</html>
