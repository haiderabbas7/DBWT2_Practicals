<script>
export default {
    name: "my-counter",
    data: function () {
        return {
            mycounter: 1
        }
    },
    methods: {
        increment: function (){
            this.mycounter++;
        },
        decrement: function (){
            this.mycounter--;
        }
    }
}
</script>


<template>
    <div>
        <p>{{mycounter}}</p>
        <button @click="decrement">-</button>
        <button @click="increment">+</button>
    </div>
</template>


<style scoped>
    div {
        display: flex;
        justify-content: space-between;
        width: 100px; /* Sie können die Breite nach Ihren Bedürfnissen anpassen */
    }
</style>
