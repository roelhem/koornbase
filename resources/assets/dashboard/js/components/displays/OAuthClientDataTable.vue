<template>

    <b-card no-body>
        <b-table class="card-table" v-bind="tableProps" v-on="tableListeners">

            <template slot="revoked" slot-scope="{item}">
                <revoked-status-icon :revoked="item.revoked" />
            </template>

            <template slot="name" slot-scope="{ item }">
                <span :class="{ 'text-muted font-italic':item.revoked }">{{ item.name }}</span>
            </template>

            <template slot="type" slot-scope="{ item }">
                <o-auth-client-type-tag :type="item.type" :revoked="item.revoked" />
            </template>

            <template slot="redirect" slot-scope="{ item }">
                <span v-if="item.redirect" class="small" :class="{'text-muted':item.revoked}">{{ item.redirect }}</span>
                <span v-else class="small font-italic text-muted">(Geen redirect nodig)</span>
            </template>

            <template slot="user" slot-scope="{ item }">
                <template v-if="item.user">
                    <user-span :user="item.user" />
                </template>
                <span v-else class="text-muted font-italic">(Geen)</span>
            </template>

            <template slot="actions" slot-scope="{ item }">
                <router-link class="icon" :to="{name:'oauth.clients.view', params: {id: item.id} }">
                    <base-icon icon="more-vertical" from="fe" />
                </router-link>
            </template>

        </b-table>

    </b-card>

</template>

<script>

    import gql from 'graphql-tag';
    import dataTableMixin from "../../mixins/dataTableMixin";
    import RevokedStatusIcon from "./RevokedStatusIcon";
    import OAuthClientTypeTag from "./OAuthClientTypeTag";
    import UserSpan from "./UserSpan";
    import DisplayTimestamp from "./DisplayTimestamp";
    import BaseIcon from "./BaseIcon";

    export default {
        components: {
            BaseIcon,
            DisplayTimestamp,
            UserSpan,
            OAuthClientTypeTag,
            RevokedStatusIcon
        },

        name: "o-auth-client-data-table",

        rowFragment:gql`
            fragment OAuthClientDataTableRow on OAuthClient {
                id
                name
                revoked
                ...on OAuthAuthCodeClient {
                    redirect
                }
                ...on OAuthPasswordClient {
                    redirect
                }
                type
                user {
                    ...UserSpan
                }
            }
            ${UserSpan.fragment}
        `,

        mixins:[dataTableMixin],

        columns: {
            id: {
                label:'ID',
                visible:true,
                sortable:true,
            },
            revoked:{
                label:'',
                name:'Huidige client-status',
                visible:true,
                sortable:true,
                tdClass:['p-2'],
                thStyle:{'width':'1px'}
            },
            name: {
                label:'Naam',
                visible:true,
                sortable:true,
            },
            redirect:{
                label:'Redirect URL',
            },
            type: {
                label:'Type',
                name:'OAuth-client Type',
                visible:true,
                sortable:true
            },
            user: {
                label:'Eigenaar',
                name:'Eigenaar/Beheerder',
                visible:true
            },
            actions:{
                label:'',
                name:'Acties',
                thStyle:{'width':'1px'},
                visible:true
            }
        }
    }

</script>

<style scoped>

</style>