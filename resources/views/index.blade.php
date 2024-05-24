<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <title>Abalo</title>
    <script src="{{ asset('js/cookiecheck.js') }}"> </script>
</head>
<body>
    <nav>
        <script>
            "use strict";

            let kategorien = JSON.parse('@json($kategorien)');
            let kategorienNamen = kategorien.map(kategorie => kategorie.name);

            let menu = {
                "Home": [],
                "Kategorien": kategorienNamen,
                "Verkaufen": [],
                "Unternehmen": ["Philosophie", "Karriere"]
            };

            const nav = document.createElement("nav");
            const outerList = document.createElement("ul");
            nav.appendChild(outerList);

            //mit entries bekommt man ein array der key-value paare
            for (const [key, values] of Object.entries(menu)) {
                const item = document.createElement('li');
                item.textContent = key;
                outerList.appendChild(item);
                if (values) {
                    const innerList = document.createElement('ul');
                    item.appendChild(innerList);
                    for (const value of values) {
                        const innerItem = document.createElement('li');
                        innerItem.textContent = value;
                        innerList.appendChild(innerItem);
                    }
                }
            }

            document.body.prepend(nav);
        </script>
    </nav>
</body>
</html>
