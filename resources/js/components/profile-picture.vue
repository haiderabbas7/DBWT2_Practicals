<script>
import Avatar from "vue-boring-avatars";

export default {
    name: "profile-picture",
    components: {
        Avatar,
    },
    data() {
        return {
            size: 80,
            square: false,
            // "bauhaus" | "beam" | "marble" | "pixel" | "ring" | "sunset"
            variant: "pixel",
            //hier kann man verschiedene zeichenfolgen eingeben, um das muster zu ändern
            name: "abcde",
            colors: this.generateRandomColors(5)
        };
    },
    methods: {
        generateRandomColors(amount) {
            let colors = [];
            for (let i = 0; i < amount; i++) {
                let color = '#' + Math.floor(Math.random() * 16777215).toString(16);
                colors.push(color);
            }
            return colors;
        }
    }
};
</script>


<template>
    <div>
        <Avatar :size="size" :square="square" :variant="variant" :name="name" :colors="colors"/>
        <a href="/login">Click here to login!</a>
    </div>
</template>


<style scoped>
div{
    margin-right: 40px;
    display: flex;
    flex-direction: column;
}
a {
    text-decoration: none;
}
</style>
