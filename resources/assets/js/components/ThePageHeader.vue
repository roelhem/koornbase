<template>

    <b-container>
        <div class="page-header">
            <h1 class="page-title">{{ title }}</h1>
        </div>
    </b-container>

</template>

<script>
    import { mapState } from "vuex";

    export default {
        name: "the-page-header",

        computed:{
            ...mapState({
                title: state => state.pageTitle
            })
        }
    }
</script>

<style scoped>

</style>
