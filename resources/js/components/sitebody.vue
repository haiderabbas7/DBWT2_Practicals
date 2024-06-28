<script>
import Impressum from "@/components/impressum.vue";
import Articles from "@/components/articles.vue"

export default {
    name: "sitebody",
    props: ['impressum', 'userId', 'articleId'],
    components: {
        Impressum,
        Articles
    }
}
</script>

<template>
    <div>
        <!-- in articles.vue -->
        <Articles v-if="!impressum" :user-id="userId" :article-id="articleId" @articleIdChanged="highlightArticle"></Articles>
        <Impressum v-else></Impressum>
    </div>
</template>

<style scoped>

</style>
