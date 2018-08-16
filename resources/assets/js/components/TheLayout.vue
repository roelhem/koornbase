<template>

    <div class="page-main">


        <template>

            <the-top-header />
            <the-nav-header />




            <div class="my-3 my-md-5">
                <!--<the-page-header />-->

                <router-view />
            </div>

        </template>


    </div>


</template>

<script>
    import TheTopHeader from "./TheTopHeader";
    import TheNavHeader from "./TheNavHeader";
    import { mapState } from 'vuex';
    import ThePageHeader from "./ThePageHeader";

    export default {

        data: function() {
            return {}
        },

        components: {
            ThePageHeader,
            TheNavHeader,
            TheTopHeader
        },

        computed: {
            ...mapState({
                user: state => state.currentUser
            })
        },

        name: "the-layout"
    }
</script>

<style scoped>

</style>