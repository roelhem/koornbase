<template>

    <b-container>

        <tabler-page-header title="OAuth Personal Client"
                            subtitle="Access Token Aanvragen"
                            no-breadcrumb
        />


        <tabler-card title="Aanvragen van een 'Personal Access Token'" v-if="!response">

            <tabler-form-group label="OAuth Client" horizontal>
                <div class="d-flex py-4">
                    <div><o-auth-client-type-tag :type="client.type" large /></div>
                    <div class="px-4">
                        <h4 class="mb-0">{{ client.name }}</h4>
                        <div class="small"><span class="text-muted-dark">Van:</span> {{ client.user.name_display }} <span class="text-muted-dark">[ {{ client.user.email }} ]</span></div>
                    </div>
                </div>
            </tabler-form-group>

            <form-simple-input id="request-personal-token--token-name"
                               label="Naam van de Access-Token"
                               placeholder="Laat leeg voor een automatisch gegenereerde naam."
                               v-model="form.name"
                               horizontal
                               class="py-2"
            />

            <tabler-form-group class="py-2" label="Token Scopes" horizontal>
                <b-form-checkbox-group stacked v-model="form.scopes">
                    <b-form-checkbox v-for="scope in scopeType.enumValues" :key="scope.name" :value="scope.name" v-b-tooltip.hover.left="scope.description">
                        <strong>{{ scope.name }}</strong>
                    </b-form-checkbox>
                </b-form-checkbox-group>
            </tabler-form-group>


            <div class="text-right" slot="footer">
                <div class="d-flex">
                    <b-button variant="link" @click="$router.back()">Annuleren</b-button>
                    <b-button variant="primary" @click="sendRequest()" class="ml-auto">Access Token Aanvragen</b-button>
                </div>
            </div>

        </tabler-card>

        <tabler-card no-body v-if="response">
            <template slot="title">
                Personal Access Token <em class="text-muted-dark">{{ response.token.name }}</em>
            </template>

            <detail-view in-card>
                <detail-entry label="ID">{{ response.token.id }}</detail-entry>
                <detail-entry label="Scopes">
                    <ul>
                        <li v-for="scope in response.token.scopes">{{ scope }}</li>
                    </ul>
                </detail-entry>
                <detail-entry label="Verloopt op">
                    {{ response.token.expires_at | date('xl') }} <em> {{ response.token.expires_at | time('lg') }}</em>
                </detail-entry>
                <detail-entry label="Token">
                    <tabler-dimmer :active="!response.accessToken">
                        <textarea class="form-control" readonly rows="8">{{ response.accessToken }}</textarea>
                    </tabler-dimmer>
                </detail-entry>
            </detail-view>

            <template slot="footer">
                <b-button :to="{name:'oauth.clients.view', params: {id: id}}" variant="link">Terug naar client</b-button>
            </template>

        </tabler-card>

    </b-container>

</template>

<script>
    import TablerPageHeader from "../../../components/layouts/title/TablerPageHeader";
    import TablerCard from "../../../components/layouts/cards/TablerCard";
    import FormSimpleInput from "../../../components/inputs/FormSimpleInput";
    import TablerFormGroup from "../../../components/layouts/forms/TablerFormGroup";
    import { showOAuthClientCardQuery } from "../../../apis/graphql/queries/oauth.graphql";
    import { requestPersonalAccessToken } from "../../../apis/graphql/mutations/oauth.graphql";
    import gql from 'graphql-tag';
    import OAuthClientTypeTag from "../../../components/displays/OAuthClientTypeTag";
    import DetailView from "../../../components/layouts/cards/DetailView";
    import DetailEntry from "../../../components/layouts/cards/DetailEntry";
    import { date, time } from "../../../utils/filters/display";
    import TablerDimmer from "../../../components/layouts/cards/TablerDimmer";

    export default {
        components: {
            TablerDimmer,
            DetailEntry,
            DetailView,
            OAuthClientTypeTag,
            TablerFormGroup,
            FormSimpleInput,
            TablerPageHeader,
            TablerCard
        },
        name: "page-request-personal-token",

        filters: { date, time },

        apollo: {
            client:{
                query:showOAuthClientCardQuery,
                variables() {
                    return {
                        id:this.id,
                    }
                }
            },
            scopeType: gql`{scopeType: __type(name:"OAuthScope") {
                               enumValues {
                                  name
                                  description
                                }
                              }
                            }`,
        },

        data() {
            return {
                form: {
                    name:null,
                    scopes:[]
                },
                response: null,
                scopeType:{enumValues:[]},
                client:{},
            }
        },

        props: {
            id:[String,Number]
        },

        methods: {
            sendRequest() {

                const clientId = this.id;
                const name = this.form.name;
                const scopes = this.form.scopes;

                this.form.name = null;
                this.form.scopes = [];

                this.$apollo.mutate({
                    mutation:requestPersonalAccessToken,
                    variables: { clientId, name, scopes },
                    update: (store, response) => {
                        console.log('update', response);
                        this.response = response.data.requestPersonalAccessToken;

                    },
                    optimisticResponse: {
                        requestPersonalAccessToken: {
                            __typename:'OAuthPersonalAccessTokenResult',
                            accessToken:null,
                            token:{
                                __typename:'OAuthToken',
                                id:'<onbekend>',
                                name:name,
                                scopes:scopes,
                                created_at:null,
                                created_by:null,
                                expires_at:null
                            }
                        }
                    }
                }).then(response => {
                    console.log('then', response);
                }).catch(error => {
                    console.error(error);
                    this.form.name = name;
                    this.form.scopes = scopes;
                });
            }
        }
    }
</script>

<style scoped>

</style>