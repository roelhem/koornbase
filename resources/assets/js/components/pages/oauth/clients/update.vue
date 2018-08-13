<template>

    <b-container>
        <tabler-page-header title="OAuth-client bewerken"
                            :breadcrumb="[
                                    {icon:'cloud', text:'OAuth Server', active:true},
                                    {text:'Clients', to:{name: 'oauth.clients.index'}},
                                    {text:'Client', to:{name: 'oauth.clients.view', params: {id: id}}},
                                    {text:'Bewerken', active:true}
                                ]"
        />

        <update-o-auth-client-form :client-id="id" />

    </b-container>


</template>

<script>
    import TablerPageHeader from "../../../TablerPageHeader";
    import UpdateOAuthClientForm from "../../../forms/UpdateOAuthClientForm";

    export default {
        components: {
            UpdateOAuthClientForm,
            TablerPageHeader},

        props: {
            id:[String,Number]
        }

    }
</script>

<style scoped>

</style>