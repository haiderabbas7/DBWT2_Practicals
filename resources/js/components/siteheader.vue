<script>
import HomepageMenu from "@/components/homepage-menu.vue";
import ProfilePicture from "@/components/profile-picture.vue";
export default {
    name: "siteheader",
    components: {
        HomepageMenu,
        ProfilePicture
    }
}
</script>

<template>
    <nav>
        <HomepageMenu></HomepageMenu>
        <ProfilePicture></ProfilePicture>
    </nav>
</template>

<style scoped>

</style>
