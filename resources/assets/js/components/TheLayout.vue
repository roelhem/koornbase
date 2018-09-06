<template>

    <div class="page-main">


        <template v-if="$apollo.queries.currentUser.loading">
            <h1>Even wachten hoor...</h1>
        </template>
        <template v-else>

            <the-top-header />
            <the-nav-header />




            <div class="my-3 my-md-5">

                <router-view />
            </div>

        </template>


    </div>


</template>

<script>
    import TheTopHeader from "./TheTopHeader";
    import TheNavHeader from "./TheNavHeader";
    import { currentUser } from "../graphql/dashboard.graphql";

    export default {

        data: function() {
            return {
                currentUser:{}
            }
        },

        apollo: {
            currentUser:currentUser
        },

        components: {
            TheNavHeader,
            TheTopHeader
        },

        name: "the-layout"
    }
</script>

<style scoped>

</style>