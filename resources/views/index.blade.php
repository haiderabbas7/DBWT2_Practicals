<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <title>Abalo</title>
</head>
<body>
    <nav>
        <script>
            "use strict";
            let outerlist = document.createElement("ul");
            outerlist.setAttribute('id', 'outerlist');
            document.body.append(outerlist);


            let menuItems = ['Home', 'Kategorien', 'Verkaufen', 'Unternehmen'];
            for(let i = 0; i < menuItems.length; i++){
                let item = document.createElement('li');
                item.setAttribute('id', menuItems[i]);
                item.textContent = menuItems[i];
                document.getElementById('outerlist').append(item);
            }


            let innerlistUnternehmen = document.createElement('ul');
            innerlistUnternehmen.setAttribute('id', 'innerlistUnternehmen');
            document.getElementById('Unternehmen').append(innerlistUnternehmen);
            let innerMenuItems = ['Philosophie', 'Karriere'];
            for(let i = 0; i < innerMenuItems.length; i++){
                let item = document.createElement('li');
                item.setAttribute('id', menuItems[i]);
                item.textContent = innerMenuItems[i];
                document.getElementById('innerlistUnternehmen').append(item);
            }


            let innerlistKategorien = document.createElement('ul');
            innerlistKategorien.setAttribute('id', 'innerlistKategorien');
            document.getElementById('Kategorien').append(innerlistKategorien);
            let kategorien = @json($kategorien);
            for(let kategorie of kategorien){
                let item = document.createElement('li');
                item.setAttribute('id', kategorie.name);
                item.textContent = kategorie.name;
                document.getElementById('innerlistKategorien').append(item);
            }

        </script>
    </nav>
</body>
</html>
