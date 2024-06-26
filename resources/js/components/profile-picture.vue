<script>
import Avatar from "vue-boring-avatars";

export default {
    name: "profile-picture",
    components: {
        Avatar,
    },
    data() {
        return {
            colors: ["#92A1C6", "#146A7C", "#F0AB3D", "#C271B4", "#C20D90"],
        };
    }
};
</script>



<template>
    <Avatar :size="80" variant="bauhaus" name="Mujahid Anuar" :colors="colors" />
</template>



<style scoped>

</style>
