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
                <o-auth-client-card :client="oauthClient" />

                <b-button @click="$refs.requestPersonalAccessTokenModal.show()">Personal Access Token Aanvragen</b-button>

                <request-personal-access-token-form ref="requestPersonalAccessTokenModal" modal :client="oauthClient" />

            </b-col>
            <b-col lg="5">
                <o-auth-client-tokens-card :client="oauthClient">
                </o-auth-client-tokens-card>
            </b-col>
        </b-row>


    </b-container>

</template>

<script>
    import gql from "graphql-tag";
    import OAuthClientCard from "../../../components/displays/OAuthClientCard";
    import TablerPageHeader from "../../../components/layouts/title/TablerPageHeader";
    import RequestPersonalAccessTokenForm from "../../../components/forms/oauth/RequestPersonalAccessTokenForm";
    import OAuthClientTokensCard from "../../../components/displays/OAuthClientTokensCard";

    export default {
        components: {
            OAuthClientTokensCard,
            RequestPersonalAccessTokenForm,
            TablerPageHeader,
            OAuthClientCard
        },

        name: "view-o-auth-client-view",

        apollo: {
            oauthClient:{
                query:gql`
                    query viewOAuthClient($id:ID!) {
                        oauthClient(id:$id) {
                            ...OAuthClientCard
                            ...OAuthClientTokensCard
                            ...RequestPersonalAccessTokenFormClient
                        }
                    }
                    ${OAuthClientCard.fragment}
                    ${OAuthClientTokensCard.fragment}
                    ${RequestPersonalAccessTokenForm.clientFragment}
                `,
                variables() {
                    return {id:this.id};
                }
            }
        },

        data() {
            return {
                oauthClient:{user:{}},
            };
        },

        props: {
            id:[String,Number]
        }
    }
</script>

<style scoped>

</style>