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
                {{ client.id }}
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
            <b-button @click="$refs.updateClientForm.show()" variant="primary">Bewerken</b-button>
            <b-button variant="warning">Intrekken</b-button>
            <b-button variant="danger">Verwijderen</b-button>
        </template>


        <update-o-auth-client-form ref="updateClientForm" :client-id="clientId" :current="client" modal />
    </tabler-card>

</template>

<script>
    import { showOAuthClientCardQuery } from  "../queries/oauth.graphql";
    import TablerCard from "./TablerCard";
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
            TablerCard},
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
        }
    }
</script>

<style scoped>

</style>