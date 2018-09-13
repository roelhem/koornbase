<template>

    <b-container>
        <tabler-page-header title="OAuth client"
                            :breadcrumb="[
                                {icon:'cloud', text:'OAuth Server', active:true},
                                {text:'Clients', to:{name: 'oauth.clients.index'}},
                                {text:'Client', active:true}
                            ]"
        />


        <b-row>
            <b-col lg="7">
                <o-auth-client-card :client="oAuthClient" />
            </b-col>
        </b-row>


    </b-container>

</template>

<script>
    import gql from "graphql-tag";
    import OAuthClientCard from "../../../components/displays/OAuthClientCard";
    import UpdateOAuthClientForm from "../../../components/features/crud/UpdateOAuthClientForm";
    import TablerPageHeader from "../../../components/layouts/title/TablerPageHeader";

    export default {
        components: {
            TablerPageHeader,
            UpdateOAuthClientForm,
            OAuthClientCard
        },

        name: "view-o-auth-client-view",

        apollo: {
            oAuthClient:{
                query:gql`
                    query viewOAuthClient($id:ID!) {
                        oAuthClient(id:$id) {
                            ...OAuthClientCard
                        }
                    }
                    ${OAuthClientCard.fragment}
                `,
                variables() {
                    return {id:this.id};
                }
            }
        },

        data() {
            return {
                oAuthClient:{user:{}},
            };
        },

        props: {
            id:[String,Number]
        }
    }
</script>

<style scoped>

</style>