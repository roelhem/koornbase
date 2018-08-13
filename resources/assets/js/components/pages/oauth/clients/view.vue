<template>

    <div>

        <b-container>
            <tabler-page-header title="OAuth client"
                                :breadcrumb="[
                                    {icon:'cloud', text:'OAuth Server', active:true},
                                    {text:'Clients', to:{name: 'oauth.clients.index'}},
                                    {text:'Client', active:true}
                                ]"
            />
        </b-container>

        <b-container>
            <show-o-auth-client-card :client-id="id" />
        </b-container>

    </div>

</template>

<script>

    import ShowOAuthClientCard from "../../../ShowOAuthClientCard";
    import UpdateOAuthClientForm from "../../../forms/UpdateOAuthClientForm";
    import TablerPageHeader from "../../../TablerPageHeader";

    export default {
        components: {
            TablerPageHeader,
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