<!DOCTYPE html>
<html lang="de">
<head>
    @vite('resources/js/app.js')
    <meta charset="utf-8">
    <title>Abalo</title>
    @vite('resources/css/app.scss')
</head>
<body id="app">
    @verbatim
        <nav>
            <ul>
                <li v-for="(values, key) in index_menu" :key="key">
                    {{ key }}
                    <ul v-if="values.length > 0">
                        <li v-for="(value, index) in values" :key="index">
                            {{ value }}
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
    @endverbatim
    <!--<my-counter></my-counter>-->
</body>
</html>
