<template>

    <div class="page">

        <template v-if="$apollo.queries.me.loading">
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
    import gql from "graphql-tag";

    import TheMainPage from "./components/layouts/TheMainPage";
    import TheFooter from "./components/layouts/footer/TheFooter";
    import TheStartupPage from "./components/layouts/TheStartupPage";
    import TheLockedPage from "./components/layouts/TheLockedPage";

    export default {
        name: "app",

        apollo: { me: gql`
            query getCurrentUser {
                me {
                    id
                    name
                    email
                    avatar {
                        image
                        letters
                        type
                    }
                }
            }
        `},

        data() {
            return {
                me:{},
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