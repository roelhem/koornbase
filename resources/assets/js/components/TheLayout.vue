<template>

    <div class="page-main">


        <template v-if="!!user">

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
    import { mapState } from 'vuex';

    export default {

        data: function() {
            return {}
        },

        components: {
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