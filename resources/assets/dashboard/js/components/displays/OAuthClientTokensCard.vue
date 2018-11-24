<template>
    <tabler-card v-bind="$attrs" v-on="$listeners" no-body>
        <template slot="title">
            Access-tokens van
            <span class="text-muted">:</span>
            <base-field class="text-muted-dark small" title="Naam van de Client" name="name">{{ client.name }}</base-field>
        </template>


        <b-table class="card-table"
                 :items="tokens"
                 small
                 :fields="fields"
                 thead-class="d-none"
                 show-empty
                 empty-text="Geen tokens voor deze Client."
                 @row-clicked="rowClickedHandler"
        >

            <template slot="createdAt" slot-scope="{item:token}">
                <base-field class="small text-muted-dark"
                            v-if="token.modelInfo && token.modelInfo.createdAt"
                            title="Startdatum"
                            name="modelInfo.createdAt"
                >{{ token.modelInfo.createdAt | date('xs') }}</base-field>
            </template>

            <template slot="status" slot-scope="{item:token}">
                <o-auth-token-status :token="token" icon-only />
            </template>

            <template slot="user" slot-scope="{item:token}">
                <base-field v-if="token.user"
                            title="Naam van de gebruiker"
                            name="user.name"
                >{{ token.user.name }}</base-field>
                <span v-else class="font-italic text-muted small">(Geen gebruiker)</span>
            </template>

            <template slot="name" slot-scope="{item:token}">
                <base-field v-if="token.name" class="text-muted-dark" title="Naam van de Access-token" name="name">{{token.name}}</base-field>
                <span v-else class="font-italic text-muted small">(Geen naam)</span>
            </template>

            <o-auth-token-details slot="row-details" slot-scope="{item:token}" :token="token" />

        </b-table>

    </tabler-card>
</template>

<script>
    import gql from "graphql-tag";
    import TablerCard from "../layouts/cards/TablerCard";
    import BaseField from "./BaseField";
    import OAuthTokenStatus from "./OAuthTokenStatus";
    import UserSpan from "./UserSpan";
    import displayFilters from "../../utils/filters/display";
    import OAuthTokenDetails from "./OAuthTokenDetails";

    export default {
        components: {
            OAuthTokenDetails,
            UserSpan,
            OAuthTokenStatus,
            BaseField,
            TablerCard
        },

        name: "o-auth-client-tokens-card",

        fragment: gql`
            fragment OAuthClientTokensCard on OAuthClient {
                name
                tokens(first:50 orderBy:createdAt_DESC) {
                    edges {
                        node {
                            id
                            name
                            user {
                                id
                                name
                            }
                            ...OAuthTokenStatus
                            ...OAuthTokenDetails
                            expiresAt
                            modelInfo {
                                createdAt
                            }
                        }
                    }
                }
            }
            ${UserSpan.fragment}
            ${OAuthTokenDetails.fragment}
            ${OAuthTokenStatus.fragment}
        `,

        filters: displayFilters,

        props:{
            client:{
                type:Object,
                required:true,
            }
        },

        data() {
            return {
                openTokenIds:[],
            };
        },

        computed:{
            tokens() {
                if(!this.client.tokens) {
                    return [];
                }
                return this.client.tokens.edges.map(edge => {
                    return {
                        ...edge.node,
                        _showDetails:this.tokenIsOpen(edge.node.id)
                    }
                });
            },

            fields() {
                return [
                    {key:'status', label:''},
                    {key:'createdAt', label:'Datum'},
                    {key:'user', label:'Gebruiker'},
                    {key:'name', label:'Token-naam'},
                ];
            }
        },

        methods:{
            rowClickedHandler(item) {
                this.toggleToken(item.id);
            },

            tokenIsOpen(tokenId) {
                return this.openTokenIds.indexOf(tokenId) !== -1;
            },

            openToken(tokenId) {
                if(!this.tokenIsOpen(tokenId)) {
                    this.openTokenIds.push(tokenId);
                }
            },

            closeToken(tokenId) {
                const index = this.openTokenIds.indexOf(tokenId);
                if(index !== -1) {
                    this.openTokenIds.splice(index,1);
                }
            },

            toggleToken(tokenId) {
                if(this.tokenIsOpen(tokenId)) {
                    this.closeToken(tokenId);
                } else {
                    this.openToken(tokenId);
                }
            }
        }
    }
</script>

<style scoped>

</style>