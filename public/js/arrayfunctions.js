import { data } from './data.js';

"use strict";

export function getMaxPreis(data){
    let maxPreis = 0;
    let maxPreisProduktname = '';
    for(let produkt of data.produkte){
        if(produkt.preis > maxPreis){
            maxPreis = produkt.preis;
            maxPreisProduktname = produkt.name;
        }
    }
    return maxPreisProduktname;
}

export function getMinPreisProdukt(){


}

console.log(getMaxPreis(data));
