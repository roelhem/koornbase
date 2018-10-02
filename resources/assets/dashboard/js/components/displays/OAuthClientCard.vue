<template>

    <tabler-card no-body v-bind="$attrs" v-on="$listeners">
        <template slot="header">
            <h3 class="card-title flex-grow-1">
                <subtile-single-input-form :value="client.name" @submit="handleSubmitName" :disabled="client.revoked">
                    <span class="mr-2">OAuth Client</span> <span class="text-muted-dark">{{ client.name }}</span>
                </subtile-single-input-form>
            </h3>
        </template>



        <detail-view in-card>
            <detail-entry label="Eigenaar">
                <span v-if="client.user">
                    <user-span :user="client.user" with-email />
                </span>
                <span v-else class="text-muted font-italic">(Geen gebruiker gekoppeld.)</span>
            </detail-entry>
            <detail-entry label="Type">
                <o-auth-client-type-tag :type="client.type" large />
            </detail-entry>
            <detail-entry label="Client ID">
                {{ client.id }} <span v-if="client.revoked" class="ml-2 text-muted-dark font-italic">(Ingetrokken)</span>
            </detail-entry>


            <subtile-detail-entry-form v-if="client.type !== 'CREDENTIALS'"
                                       label="Redirect URL"
                                       :value="client.redirect"
                                       @submit="handleSubmitRedirect"
                                       :disabled="client.revoked"
            />


            <detail-entry label="Secret">
                <code>{{ client.secret }}</code>
            </detail-entry>
        </detail-view>

        <template slot="footer">
            <div class="d-flex text-right">
                <b-button v-if="!client.revoked" @click="$refs.revokeClientModal.show()" variant="danger" class="ml-1">Intrekken</b-button>

                <b-button v-if="!client.revoked && client.type === 'PERSONAL'"
                          variant="link"
                          class="ml-auto"
                          :to="{ name:'oauth.clients.request-personal-token', params: { id: clientId } }"
                >Personal Access Token Aanvragen</b-button>
            </div>

        </template>

        <tabler-modal ref="revokeClientModal"
                      title="Client intrekken."
                      ok-title="Intrekken"
                      cancel-title="Annuleren"
                      header-bg-variant="warning"
                      ok-variant="warning"
                      @ok="revoke()"
        >
            <p>Weet je zeker dat je de rechten van deze OAuth-client wilt intrekken? Je kunt deze actie niet meer ongedaan maken.</p>
            <p>Het systeem zal geen enkele request van deze Client meer accepteren. Ook is het niet meer mogelijk om de gegevens te bewerken.
                Na een tijdje zorgt het systeem er zelf voor dat de Client wordt verwijderd.</p>
        </tabler-modal>
    </tabler-card>

</template>

<script>
    import gql from "graphql-tag";
    import TablerCard from "../layouts/cards/TablerCard";
    import TablerModal from "../layouts/modals/TablerModal";
    import DetailView from "../layouts/cards/DetailView";
    import DetailEntry from "../layouts/cards/DetailEntry";
    import OAuthClientTypeTag from "./OAuthClientTypeTag";
    import UserSpan from "./UserSpan";
    import SubtileSingleInputForm from "../inputs/subtile/SubtileSingleInputForm";
    import SubtileDetailEntryForm from "../inputs/subtile/SubtileDetailEntryForm";


    export default {
        components: {
            SubtileDetailEntryForm,
            SubtileSingleInputForm,
            UserSpan,
            OAuthClientTypeTag,
            DetailEntry,
            DetailView,
            TablerCard,
            TablerModal
        },
        name: "o-auth-client-card",

        fragment: gql`
            fragment OAuthClientCard on OAuthClient {
                id
                name
                ...on OAuthAuthCodeClient {
                    redirect
                }
                ...on OAuthPasswordClient {
                    redirect
                }
                secret
                type
                revoked
                user {
                    id
                    ...UserSpan
                }
            }
            ${UserSpan.fragment}
        `,

        props: {
            client:{
                type:Object,
                required:true,
                default() {
                    return {
                        user:{}
                    };
                }
            }
        },

        methods: {

            handleSubmitName(newValue) {
                const id = this.client.id;
                const name = newValue;
                const __typename = this.client.__typename;

                this.$apollo.mutate({
                    mutation:gql`
                        mutation updateOAuthClientName($id:ID!,$name:String) {
                            updateOAuthClient(id:$id, name:$name) {
                                id name
                            }
                        }
                    `,
                    variables:{id, name},
                    optimisticResponse: {
                        __typename:'Mutation',
                        updateOAuthClient: {
                            __typename,
                            id,
                            name,
                        }
                    }
                }).then(data => console.log(data));
            },

            handleSubmitRedirect(newValue) {
                const id = this.client.id;
                const redirect = newValue;
                const __typename = this.client.__typename;

                this.$apollo.mutate({
                    mutation:gql`
                        mutation updateOAuthClientRedirect($id:ID!, $redirect:String) {
                            updateOAuthClient(id:$id, redirect:$redirect) {
                                id redirect
                            }
                        }
                    `,
                    variables:{id, redirect},
                    optimisticResponse: {
                        __typename:'Mutation',
                        updateOAuthClient: {
                            __typename,
                            id,
                            redirect,
                        }
                    }
                }).then(data => console.log(data));
            },


            revoke() {
                const id = this.client.id;
                const __typename = this.client.__typename;

                this.$apollo.mutate({
                    mutation:gql`
                        mutation revokeOAuthClient($id:ID!) {
                            revokeOAuthClient(id:$id) {
                                id revoked
                            }
                        }
                    `,
                    variables:{id},
                    optimisticResponse: {
                        __typename:'Mutation',
                        revokeOAuthClient: {
                            __typename,
                            id,
                            revoked:true,
                        }
                    }
                }).then(data => console.log(data));

            },
        }
    }
</script>

<style scoped>

</style>