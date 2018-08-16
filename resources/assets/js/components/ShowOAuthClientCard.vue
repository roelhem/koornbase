<template>

    <tabler-card :is-loading="isLoading"
                 no-body
    >
        <template slot="title">
            OAuth Client <em class="text-muted-dark ml-2">{{ client.name }}</em>
        </template>
        <detail-view in-card>
            <detail-entry label="Type">
                <display-o-auth-client-type :type="client.type" large />
            </detail-entry>
            <detail-entry label="Client ID">
                {{ client.id }} <span v-if="client.revoked" class="ml-2 text-muted-dark font-italic">(Ingetrokken)</span>
            </detail-entry>
            <detail-entry v-if="client.type !== 'CREDENTIALS'" label="Redirect URL">
                {{ client.redirect }}
            </detail-entry>
            <detail-entry label="Secret">
                <code>{{ client.secret }}</code>
            </detail-entry>
            <detail-entry label="Eigenaar/Beheerder">
                <span v-if="client.user">{{ client.user.name_display }}  <span class="text-muted-dark ml-2">[ {{ client.user.email }} ]</span></span>
                <span v-else class="text-muted font-italic">(Geen gebruiker gekoppeld.)</span>
            </detail-entry>
        </detail-view>

        <template slot="footer">
            <div class="d-flex text-right">
                <b-button v-if="!client.revoked" @click="$refs.updateClientForm.show()" variant="primary">Bewerken</b-button>
                <b-button v-if="!client.revoked" @click="$refs.revokeClientModal.show()" variant="warning" class="ml-1">Intrekken</b-button>

                <b-button v-if="!client.revoked && client.type === 'PERSONAL'"
                          variant="link"
                          class="ml-auto"
                          :to="{ name:'oauth.clients.request-personal-token', params: { id: clientId } }"
                >Personal Access Token Aanvragen</b-button>
            </div>

        </template>


        <update-o-auth-client-form ref="updateClientForm" :client-id="clientId" :current="client" modal />

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
    import { showOAuthClientCardQuery } from "../graphql/queries/oauth.graphql";
    import { revokeOAuthClient } from "../graphql/mutations/oauth.graphql";
    import TablerCard from "./TablerCard";
    import TablerModal from "./TablerModal";
    import DetailView from "./DetailView";
    import DetailEntry from "./DetailEntry";
    import UpdateOAuthClientForm from "./forms/UpdateOAuthClientForm";
    import DisplayOAuthClientType from "./displays/DisplayOAuthClientType";


    export default {
        components: {
            DisplayOAuthClientType,
            UpdateOAuthClientForm,
            DetailEntry,
            DetailView,
            TablerCard,
            TablerModal
        },
        name: "show-o-auth-client-card",


        apollo: {
            client:{
                query:showOAuthClientCardQuery,
                variables() {
                    return {
                        id:this.clientId,
                    }
                }
            }
        },

        props: {
            clientId:{
                type:[String, Number],
                required:true
            }
        },

        data() {
            return {
                client:{
                    id:this.clientId,
                    name:null,
                    secret:null,
                    type:null,
                    user:null,
                    revoked:true
                }
            }
        },

        computed: {
            isLoading() {
                return this.$apollo.queries.client.loading;
            },
        },

        methods: {
            revoke() {

                const id = this.client.id;

                this.$apollo.mutate({
                    mutation:revokeOAuthClient,
                    variables:{id}
                }).then(data => console.log(data));

            },
        }
    }
</script>

<style scoped>

</style>