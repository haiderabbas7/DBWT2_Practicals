<script>
export default {
    name: "homepage-menu",
    data: function () {
        return {
            index_SPA_menu: {
                "Home": [],
                "Kategorien": [],
                "Verkaufen": [],
                "Unternehmen": ["Philosophie", "Karriere"]
            }
        }
    },
    mounted() {
        //Kategorien werden per API Call geladen, nicht mehr über Controller umweg
        let xhr = new XMLHttpRequest();
        xhr.open('GET', '/api/kategorien');
        xhr.onload = () => {
            if (xhr.status === 200) {
                let data = JSON.parse(xhr.responseText);
                this.index_SPA_menu.Kategorien = data.map(kategorie => kategorie.name);
            }
        };
        xhr.send();
    }
}
</script>

<template>
    <nav>
        <ul>
            <li v-for="(values, key) in index_SPA_menu" :key="key">
                {{ key }}
                <ul v-if="values.length > 0">
                    <li v-for="(value, index) in values" :key="index">
                        {{ value }}
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
</template>

<style scoped>

</style>
