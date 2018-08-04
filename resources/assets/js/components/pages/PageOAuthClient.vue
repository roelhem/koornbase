<template>

    <div>

        <b-container>
            <show-o-auth-client-card :client-id="id" />
        </b-container>

    </div>

</template>

<script>

    import ShowOAuthClientCard from "../ShowOAuthClientCard";
    import UpdateOAuthClientForm from "../forms/UpdateOAuthClientForm";

    export default {
        components: {
            UpdateOAuthClientForm,
            ShowOAuthClientCard},
        name: "page-o-auth-client",

        props: {
            id:[String,Number]
        }
    }
</script>

<style scoped>

</style>