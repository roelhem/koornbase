<template>
    <b-card no-body>
        <b-table class="card-table" v-bind="tableProps" v-on="tableListeners">

            <user-avatar slot="avatar" slot-scope="{item:user}" :user="user" size="md" />

            <template slot="name" slot-scope="{ item:user }">
                <div><base-field name="name" title="Gebruikersnaam">{{ user.name }}</base-field></div>
                <div class="small text-muted">
                    <base-field name="email" title="(Inlog) E-mailadres">{{ user.email }}</base-field>
                </div>
            </template>

            <template slot="person" slot-scope="{ item:user }">
                <template v-if="user.person">
                    <div><span-person-name :person-name="user.person.name" /></div>
                    <div class="small text-muted-dark">
                        <span-membership-status
                                :membership-status="user.person.membershipStatus"
                                date-size="sm"
                        />
                    </div>
                </template>
                <template v-else>
                    <div class="text-muted font-italic">( Geen gekoppeld persoon. )</div>
                </template>
            </template>

            <template slot="links" slot-scope="{ item:user }">
                <router-link class="icon" :to="{name:'users.view', params: {id: user.id} }">
                    <base-icon icon="more-vertical" from="fe" />
                </router-link>
            </template>

        </b-table>
    </b-card>
</template>

<script>
    import gql from 'graphql-tag';
    import UserAvatar from "./UserAvatar";
    import BaseField from "./BaseField";
    import SpanPersonName from "./spans/SpanPersonName";
    import SpanMembershipStatus from "./spans/SpanMembershipStatus";
    import BaseIcon from "./BaseIcon";
    import dataTableMixin from "../../mixins/dataTableMixin";

    export default {
        name: "user-data-table",

        rowFragment: gql`
            fragment UserDataTableRow on User {
                id
                name
                email
                ...UserAvatar
                person {
                    id
                    name { ...SpanPersonName }
                    membershipStatus { ...SpanMembershipStatus }
                }
            }
            ${UserAvatar.fragment}
            ${SpanPersonName.fragment}
            ${SpanMembershipStatus.fragment}
        `,

        columns:{
            avatar:{
                label:'',
                name:'Avatar',
                visible:true,
                thStyle:{'width':'1px'}
            },
            id:{
                label:'ID',
                sortable:true,
            },
            name:{
                label:"Gebruiker",
                name: 'Gebruikersnaam',
                visible:true,
                sortable:true
            },
            email: {
                label: "E-mail",
                visible: false,
                sortable: true
            },
            person: {
                label:"Persoon",
                visible: true,
            },
            links: {
                label:'',
                name:'Actieknoppen',
                visible:true,
                thStyle:{'width':'1px'}
            }
        },

        mixins:[dataTableMixin],

        components:{
            BaseIcon,
            SpanPersonName,
            SpanMembershipStatus,
            BaseField,
            UserAvatar
        }
    }
</script>

<style scoped>

</style>