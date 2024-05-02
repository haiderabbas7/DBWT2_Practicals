"use strict";

var data = {
    'produkte': [
        { name: 'Ritterburg', preis: 59.99, kategorie: 1, anzahl: 3 },
        { name: 'Gartenschlau 10m', preis: 6.50, kategorie: 2, anzahl: 5 },
        { name: 'Robomaster' ,preis: 199.99, kategorie: 1, anzahl: 2 },
        { name: 'Pool 250x400', preis: 250, kategorie: 2, anzahl: 8 },
        { name: 'Rasenmähroboter', preis: 380.95, kategorie: 2, anzahl: 4 },
        { name: 'Prinzessinnenschloss', preis: 59.99, kategorie: 1, anzahl: 5 }
    ],
    'kategorien': [
        { id: 1, name: 'Spielzeug' },
        { id: 2, name: 'Garten' }
    ]
};

function getMaxPreis(data){
    let maxPreis = Number.MIN_VALUE;
    let maxPreisProduktname = '';
    for(let produkt of data.produkte){
        if(produkt.preis > maxPreis){
            maxPreis = produkt.preis;
            maxPreisProduktname = produkt.name;
        }
    }
    return maxPreisProduktname;
}

function getMinPreisProdukt(data){
    let minPreis = Number.MAX_VALUE;
    let minPreisProdukt = null;
    for(let produkt of data.produkte){
        if(produkt.preis < minPreis){
            minPreis = produkt.preis;
            minPreisProdukt = produkt;
        }
    }
    return minPreisProdukt;
}

function getPreisSum(data){
    let sum = 0;
    for(let produkt of data.produkte){
        sum += produkt.preis;
    }
    return sum;
}

//gesamtwert = preis * anzahl für alle Produkte
function getGesamtWert(data){
    let sum = 0;
    for(let produkt of data.produkte){
        sum += produkt.preis * produkt.anzahl;
    }
    return sum;
}

function getAnzahlProdukteOfKategorie(data, kategorie){
    let sum = 0;
    for(let produkt of data.produkte){
        if(produkt.kategorie === kategorie){
            sum += produkt.anzahl;
        }
    }
    return sum;
}

console.log(getMaxPreis(data));
console.log(getMinPreisProdukt(data));
console.log(getPreisSum(data));
console.log(getGesamtWert(data));
console.log(getAnzahlProdukteOfKategorie(data, 1));
