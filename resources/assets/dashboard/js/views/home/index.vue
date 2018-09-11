<template>


    <b-container>

        <tabler-page-header title="KoornBase Dashboard" />

        <the-page-home></the-page-home>
    </b-container>

</template>

<script>

    import ThePageHome from "../../components/layouts/ThePageHome";
    import TablerPageHeader from "../../components/layouts/title/TablerPageHeader";

    export default {
        components: {
            TablerPageHeader,
            ThePageHome},
        name: "page-home",

        data: function() {
            return {
            }
        },

        mounted() {

        }
    }
</script>

<style scoped>

</style>