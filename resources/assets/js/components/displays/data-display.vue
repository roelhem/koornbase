<template>

    <span class="data-display" :title="title"><slot></slot></span>

</template>

<script>
    export default {
        name: "data-display",

        props:['title']
    }
</script>

<style scoped>

</style>