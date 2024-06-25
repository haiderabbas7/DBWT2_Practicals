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
        <ul class="homepage-menu">
            <li class="homepage-menu__item" v-for="(values, key) in index_SPA_menu" :key="key">
                {{ key }}
                <ul v-if="values.length > 0">
                    <li class="homepage-menu__item--sub homepage-menu__item--sub" v-for="(value, index) in values" :key="index">
                        {{ value }}
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
</template>

<style lang="scss" scoped>
$font-color: #007BFF;

.homepage-menu {
    background-color: lightgray;
    color: $font-color;
    &__item {
        &--sub {
            list-style-type: none;
            &:hover {
                background-color: #f0f0f0;
                color: darken($font-color, 22%);
                font-weight: bold;
            }
        }
    }
}
</style>
