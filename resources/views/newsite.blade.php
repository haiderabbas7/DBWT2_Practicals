<!DOCTYPE html>
<html lang="de">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8">
    <title>Abalo</title>
    @vite('resources/js/app.js')
    @vite('resources/css/app.scss')
</head>
<body id="app">
    <siteheader></siteheader>
    <sitebody @impressum="handleShowImpressum" :impressum="impressum"></sitebody>
    <sitefooter @impressum="handleShowImpressum"></sitefooter>
</body>
</html>
