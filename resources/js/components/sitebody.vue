<script>
import Impressum from "@/components/impressum.vue";
import Articles from "@/components/articles.vue"

export default {
    name: "sitebody",
    components: {Impressum, Articles}
}
</script>

<template>
    <div id="show-impressum"></div>
    <div id="no-impressum">
        <Articles/>
    </div>
</template>

<style scoped>

</style>
