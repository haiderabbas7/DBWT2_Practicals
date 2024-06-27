<script>
import Impressum from "@/components/impressum.vue";
import Articles from "@/components/articles.vue"

export default {
    name: "sitebody",
    props: ['impressum'],
    components: {
        Impressum,
        Articles
    },
}
</script>

<template>
    <div>
        <Articles v-if="!impressum"></Articles>
        <Impressum v-else></Impressum>
    </div>
</template>

<style scoped>

</style>
