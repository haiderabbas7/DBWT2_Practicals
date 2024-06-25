<!DOCTYPE html>
<html lang="de">
<head>
    @vite(['resources/js/app.js'])
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Abalo</title>
    @vite(['resources/css/app.scss'])

</head>
<body id="app">
<form class="articleForm" method="post" action="/articles">
    @csrf
    <input class="articleForm__input" type="text" name="name" placeholder="Artikelname" v-model="newArticle_name" >
    <input class="articleForm__input" type="text" name="price" placeholder="Preis" v-model="newArticle_price" >
    <textarea class="articleForm__input" name="description" placeholder="Beschreibung" v-model="newArticle_description" ></textarea>
    <button class="articleForm__button" v-on:click.prevent="sendNewArticleInfo">Speichern</button>
    <p class="articleForm__statusText" v-bind:style="{ color: newArticle_status_color }" v-html="newArticle_status_text"></p>
</form>
</body>
</html>
