<template>
    <span>{{ birth_date | moment('D MMMM YYYY') }}</span>
</template>

<script>

    export default {
        name: "display-person-birth-date",
        props:{
            birth_date:null
        },
    }
</script>

<style scoped>

</style>