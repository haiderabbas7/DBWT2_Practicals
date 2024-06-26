<script>
import Impressum from "@/app.js";

export default {
    name: "sitebody",
    components: {Impressum}
}
</script>

<template>
    <nav>
        <div v-if="show-impressum">
            <impressum>

            </impressum>
        </div>
        <div v-else>

        </div>
    </nav>
</template>

<style scoped>

</style>
