<template>

    <div>



        <div class="my-2" v-if="token.user">
            <h6>Gebruiker</h6>
            <div class="px-1">
                <user-span v-if="token.user" :user="token.user" with-email with-username stacked />
            </div>
        </div>



        <div class="my-2" v-if="token.scopes && token.scopes.length > 0">
            <h6 class="mb-0">Scopes</h6>
            <b-form-row class="px-1">
                <b-col v-for="scope in token.scopes"
                       :key="`${token.id}-scope-${scope}`"
                >{{ scope }}</b-col>
            </b-form-row>
        </div>

        <b-row class="my-2">
            <b-col>
                <h6 class="my-0">Gemaakt op</h6>
                <div class="small px-1">
                    <base-field title="Aanmaakdatum" name="modelInfo.createdAt">{{ token.modelInfo.createdAt | date('md') }}</base-field>
                    <span class="text-muted">-</span>
                    <base-field title="Aanmaaktijd" name="modelInfo.createdAt">{{ token.modelInfo.createdAt | time('lg') }}</base-field>
                </div>
            </b-col>
            <b-col>
                <h6 class="my-0">Verloopt op</h6>
                <div class="small px-1">
                    <base-field title="Verloopdatum" name="expiresAt">{{ token.expiresAt | date('md') }}</base-field>
                    <span class="text-muted">-</span>
                    <base-field title="Verlooptijd" name="expiresAt">{{ token.expiresAt | time('lg') }}</base-field>
                </div>
            </b-col>
        </b-row>



        <div class="text-right">
            <b-button size="sm">Annuleren</b-button>
            <b-button variant="danger" size="sm">Intrekken</b-button>
        </div>


    </div>

</template>

<script>

    import gql from "graphql-tag";
    import UserSpan from "./UserSpan";
    import BaseField from "./BaseField";
    import displayFilters from "../../utils/filters/display";

    export default {
        components: {
            BaseField,
            UserSpan
        },
        name: "o-auth-token-details",

        filters: displayFilters,

        fragment:gql`
            fragment OAuthTokenDetails on OAuthToken {
                id
                name
                scopes
                user {
                    ...UserSpan
                }
                expiresAt
                modelInfo {
                    createdAt
                }
            }
            ${UserSpan.fragment}
        `,

        props:{
            token:{
                type:Object,
                required:true
            }
        }
    }
</script>

<style scoped>

</style>