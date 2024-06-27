"use strict";

import './bootstrap';
import './data.js'
import './cookiecheck.js'

import MyCounter from "@/components/my-counter.vue";
import Siteheader from "@/components/siteheader.vue";
import Sitebody from "@/components/sitebody.vue";
import Sitefooter from "@/components/sitefooter.vue";
import * as math from 'mathjs';

import { createApp } from 'vue';

import {boolean, forEach, map} from "mathjs";

const vm = createApp({
    data() {
        return {
            //hier benutze ich 1 als placeholder
            userID: 1,
            newArticle_name: '',
            newArticle_price: '',
            newArticle_description: '',
            newArticle_status_color: 'green',
            newArticle_status_text: '',
            impressum: false
        }
    },
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
        handleShowImpressum: function (){
            this.impressum = !this.impressum;
            console.log("Impressum is now " + this.impressum);
        }
    },
    mounted() {
        //erstellt pro Client und pro Aufgabe die Broadcaster verbindung
        let socket_maintenance = new WebSocket('ws://localhost:8081/maintenance');
        socket_maintenance.onopen = (event) => {
            //
        };
        socket_maintenance.onclose = (closeEvent) => {
            console.log(
                'Connection closed' +
                ': code=', closeEvent.code,
                '; reason=', closeEvent.reason);
        };
        socket_maintenance.onmessage = (msgEvent) => {
            let data = JSON.parse(msgEvent.data);
            let message = data.message;
            alert(message);
        };



        let socket_articleSold = new WebSocket('ws://localhost:8081/articleSold');
        socket_articleSold.onopen = (event) => {
            socket_articleSold.send(this.userID);
        };
        socket_articleSold.onclose = (closeEvent) => {
            console.log(
                'Connection closed' +
                ': code=', closeEvent.code,
                '; reason=', closeEvent.reason);
        };
        socket_articleSold.onmessage = (msgEvent) => {
            let data = JSON.parse(msgEvent.data);
            let message = data.message;
            alert(message);
        };


        //let socket_articleOnSale = new WebSocket('ws://localhost:8081/articleOnSale');
        //hier noch code zum handeln der Aufg13, mach ich gleich
    }
}).mount('#app');

