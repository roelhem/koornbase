<template>


    <b-container>
        <tabler-page-header title="Nieuwe OAuth-client Toevoegen"
                            :breadcrumb="[
                                    {icon:'cloud', text:'OAuth Server', active:true},
                                    {text:'Clients', to:{name: 'oauth.clients.index'}},
                                    {text:'Nieuwe Client', active:true}
                                ]"
        />

        <create-o-auth-client-form />


    </b-container>


</template>

<script>
    import TablerPageHeader from "../../../TablerPageHeader";
    import CreateOAuthClientForm from "../../../forms/CreateOAuthClientForm";

    export default {
        components: {
            CreateOAuthClientForm,
            TablerPageHeader}

    }
</script>

<style scoped>

</style>