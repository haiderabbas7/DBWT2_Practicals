<script>
export default {
    name: "sitefooter",
    emits: ['impressum'],
    methods: {
        showImpressum: function(evt){
            console.log("Button clicked");
            this.$emit('impressum', true);
        }
    }
}
</script>


<template>
    <div>
        <button id="impressum-button" @click="showImpressum">Impressum</button>
    </div>
</template>


<style scoped>

</style>
