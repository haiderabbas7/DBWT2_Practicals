<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @vite('resources/js/app.js')
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
</head>
<body>
        <div id="app">
            <input type="text" name="search" v-model="articleSearchTerm" v-on:keydown="searchArticles" >
            <table class="searchArticles">
                <thead>
                <tr>
                    <td>Name</td>
                    <td>Price</td>
                    <td>Description</td>
                    <td>Creator ID</td>
                    <td>Create Date</td>
                </tr>
                </thead>
                <tbody>
                    <tr v-for="article in articleSearchResults" v-bind:key="articleSearchResults.id">
                        @verbatim
                        <td>{{article.name}}</td>
                        <td>{{article.price}}</td>
                        <td>{{article.description}}</td>
                        <td>{{article.creator_id}}</td>
                        <td>{{article.createdate}}</td>
                        @endverbatim
                    </tr>
                </tbody>
            </table>
        </div>
</body>
