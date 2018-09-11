<template>

    <div class="page">

        <template v-if="$apollo.queries.currentUser.loading">
            <the-startup-page />
        </template>

        <template v-else-if="locked">
            <the-locked-page />
        </template>

        <template v-else>
            <the-main-page />
            <the-footer />
        </template>

    </div>


</template>

<script>
    import { currentUser } from "./apis/graphql/dashboard.graphql";

    import TheMainPage from "./components/layouts/TheMainPage";
    import TheFooter from "./components/layouts/footer/TheFooter";
    import TheStartupPage from "./components/layouts/TheStartupPage";
    import TheLockedPage from "./components/layouts/TheLockedPage";

    export default {
        name: "app",

        apollo: { currentUser:currentUser },

        data() {
            return {
                currentUser:{},
                locked:false
            }
        },

        components: {
            TheLockedPage,
            TheMainPage,
            TheFooter,
            TheStartupPage,
        }
    }
</script>

<style scoped>

</style>