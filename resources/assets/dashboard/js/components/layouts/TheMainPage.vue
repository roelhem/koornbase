<template>

    <div class="page-main">


        <the-top-header />
        <the-nav-header />




        <div class="my-3 my-md-5">

            <router-view />
        </div>


    </div>


</template>

<script>
    import TheTopHeader from "./header/TheTopHeader";
    import TheNavHeader from "./header/TheNavHeader";

    export default {

        data: function() {
            return {
                currentUser:{}
            }
        },

        components: {
            TheNavHeader,
            TheTopHeader
        },

        name: "the-main-page"
    }
</script>

<style scoped>

</style>