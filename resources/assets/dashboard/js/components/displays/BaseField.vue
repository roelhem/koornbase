<template>

    <span class="data-field" :title="title"><slot>{{ value }}</slot></span>

</template>

<script>
    export default {
        name: "base-field",

        props: {
            title:String,
            name:String,
            value:null,
        }
    }
</script>

<style scoped>

</style>