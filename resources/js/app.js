"use strict";

import './bootstrap';
import './data.js'
import './cookiecheck.js'
// import './shoppingcart.js'

//KOMPONENTEN IMPORTS, DAS @ STEHT FÜR DAS js Verzeichnis (hat der automatisch gemacht idfk)
import MyCounter from "@/components/my-counter.vue";
import Siteheader from "@/components/siteheader.vue";
import Sitebody from "@/components/sitebody.vue";
import Sitefooter from "@/components/sitefooter.vue";

import { createApp } from 'vue';
import {boolean, forEach, map} from "mathjs";

const vm = createApp({
    props: { },
    data() {
        return {
            index_menu: {
                "Home": [],
                "Kategorien": [],
                "Verkaufen": [],
                "Unternehmen": ["Philosophie", "Karriere"]
            },
            newArticle_name: '',
            newArticle_price: '',
            newArticle_description: '',
            newArticle_status_color: 'green',
            newArticle_status_text: '',
            showImpressum: true
        }
    },
    //NEUE KOMPONENTEN HIER EINTRAGEN UNTER DEM IMPORT NAMEN, SONST GEHTS NICHT
    components: {
        MyCounter,
        Siteheader,
        Sitebody,
        Sitefooter
    },

    methods: {
        //der gleiche code wie im Button EventListener, nur natürlich etwas angepasst
        sendNewArticleInfo: function () {
            let name = this.$data.newArticle_name;
            let price = this.$data.newArticle_price;
            let description = this.$data.newArticle_description;
            //Clientseitige Checks
            if (name === '' || isNaN(price) || price <= 0) {
                this.newArticle_status_color = 'red';
                this.newArticle_status_text = '<b>FEHLER</b>: Bitte geben Sie gültige Werte ein: Kein leerer Name und nur positive Werte für Preis';
            } else {
                let xhr = new XMLHttpRequest();
                xhr.open('POST', '/api/articles');
                //hier per Arrow funktion wird this auf das vue objekt gebunden
                xhr.onload = () => {
                    let response = JSON.parse(xhr.responseText);
                    this.newArticle_status_text = response.message;
                    this.newArticle_status_color = 'green';
                    if (response.status === 'Fehler') {
                        this.newArticle_status_color = 'red';
                    }
                };
                let formData = new FormData();
                formData.append("name", name);
                formData.append("price", price);
                formData.append("description", description);
                xhr.send(formData);
            }
            return false;
        },

        toggleImpressum: function (event) {
            this.showImpressum = !this.showImpressum;
            let impressum = document.querySelector(`#show-impressum`);
            let noImpressum = document.querySelector(`#show-impressum`);
            if(this.showImpressum) {
                impressum.enable();
                noImpressum.disable();
            }
            else {
                impressum.disable();
                noImpressum.enable();
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
                this.index_menu.Kategorien = data.map(kategorie => kategorie.name);
            }
        };
        xhr.send();
    }
}).mount('#app');

