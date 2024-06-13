import {createApp} from "vue";

const vm = createApp({
    data() {
        return {
            searchTerm: '',
            searchResults: []
        };
    },
    watch: {
        searchTerm(newVal) {
            if (newVal.length >= 3) {
                this.searchArticles();
            } else {
                this.searchResults = [];
            }
        }
    },
    methods: {
        async searchArticles() {
            if (this.searchTerm.length >= 3) {
                try {
                    const response = await fetch(`/api/articles?search=${this.searchTerm}`)
                    this.searchResults = await response.json();
                } catch (error) {
                    console.error('Error fetching articles: '+ error);
                }
            }
        }

    }
});

