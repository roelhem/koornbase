<template>

    <pre>{{ personId }}</pre>

</template>

<script>


    export default {
        name: "page-person-debug",

        data: function() {
            return {
            }
        },


        props: {
            personId: {
                type:[String,Number]
            }
        }
    }
</script>

<style scoped>

</style>