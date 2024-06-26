<script>
export default {
    name: "sitefooter",

    methods: {
        handleClick: function (evt) {
            this.$emit("toggleImpressum");
        }
    }
}
</script>

<template>
    <nav>
        <button href="#" id="impressum-link" @click="handleClick">Impressum</button>
    </nav>
</template>

<style scoped>

</style>
