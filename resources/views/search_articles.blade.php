<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Articles</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    <style>
    </style>
    <!-- JavaScripts -->
    @vite('resources/js/app.js')
</head>
<body>
    @vite('resources/js/article-search.js')
    <table id="app">
        <thead>
            <tr>
                <td>Name</td>
                <td>Price</td>
                <td>Description</td>
                <td>Creator ID</td>
                <td>Create Date</td>
            </tr>
        </thead>
        <input v-model="searchTerm" @input="searchArticles" placeholder="Suche nach Artikeln">
        <tbody>
                <tr v-for="article in searchResults.slice(0,5)" :key="article.id">
                    <td>{{article.name}}</td>
                    <td>{{article.price}}</td>
                    <td>{{article.description}}</td>
                    <td>{{article.creator_id}}</td>
                    <td>{{article.createdate}}</td>
                </tr>
        </tbody>
    </table>
</body>
