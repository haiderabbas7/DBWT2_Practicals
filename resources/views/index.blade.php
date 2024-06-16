<!DOCTYPE html>
<html lang="de">
<head>
    @vite('resources/js/app.js')
    <meta charset="utf-8">
    <title>Abalo</title>

</head>
<body id="app">
    <!--HIER WAR VORHER SCRIPT TAG ZUM ERSTELLEN DER NAVIGATIONSLEISTE DER HOMEPAGE-->
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
</body>
</html>
