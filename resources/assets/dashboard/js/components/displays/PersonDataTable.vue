<template>
    <b-card no-body>
        <b-table class="card-table" v-bind="tableProps" v-on="tableListeners">


            <template slot="avatar" slot-scope="{item:person}">
                <person-avatar :person="person" size="md" />
            </template>


            <template slot="name" slot-scope="{item:person}">
                <div>
                    <span-person-name :person-name="person.name" with-nickname />
                </div>
                <div class="small text-muted">
                    <span-person-name :person-name="person.name" formal />
                </div>
            </template>

            <template slot="birthDate" slot-scope="{item:person}">
                <span-birth-date :birth-date="person.birthDate" />
            </template>

            <template slot="membershipStatus" slot-scope="{item:person}">
                <span-membership-status :membership-status="person.membershipStatus" stacked />
            </template>

            <template slot="createdAt" slot-scope="{item:{modelInfo}}">
                <display-timestamp :timestamp="modelInfo.createdAt" />
            </template>

            <template slot="updatedAt" slot-scope="{item:{modelInfo}}">
                <display-timestamp :timestamp="modelInfo.updatedAt" />
            </template>

            <template slot="deletedAt" slot-scope="{item:{modelInfo}}">
                <display-timestamp :timestamp="modelInfo.deletedAt" />
            </template>

            <template slot="links" slot-scope="{item:person}">
                <router-link class="icon" :to="{name:'db.persons.view', params: {id: person.id} }">
                    <base-icon icon="more-vertical" from="fe" />
                </router-link>
            </template>

        </b-table>
    </b-card>
</template>

<script>
    import gql from "graphql-tag";
    import dataTableMixin from "../../mixins/dataTableMixin";
    import PersonAvatar from "./PersonAvatar";
    import SpanPersonName from "./spans/SpanPersonName";
    import SpanMembershipStatus from "./spans/SpanMembershipStatus";
    import SpanBirthDate from "./spans/SpanBirthDate";
    import BaseIcon from "./BaseIcon";
    import DisplayTimestamp from "./DisplayTimestamp";

    export default {
        name: "person-data-table",

        rowFragment: gql`
            fragment PersonDataTableRow on Person {
                id
                ...PersonAvatar
                name { ...SpanPersonName }
                birthDate
                membershipStatus { ...SpanMembershipStatus }
                modelInfo {
                    createdAt
                    updatedAt
                    deletedAt
                }
            }
            ${PersonAvatar.fragment}
            ${SpanPersonName.fragment}
            ${SpanMembershipStatus.fragment}
        `,

        columns: {
            avatar:{
                label:'',
                name:'Avatar',
                visible:true,
                thStyle:{'width':'1px'},
                tdClass:['p-3'],
            },
            id:{
                label:'ID',
                sortable:true
            },
            name:{
                label:'Hele Naam',
                name:'Volledige Naam',
                visible:true,
                sortField:'lastName',
            },
            shortName:{
                label: 'Naam',
                name:'Korte Naam',
                sortField:'firstName',
            },
            nickname:{
                label:'Bijnaam',
                sortable:true,
            },
            birthDate:{
                label:'Geboortedatum',
                visible:true,
                sortable:true,
            },
            membershipStatus:{
                label:'Status Lidmaatschap',
                visible:true,
                sortable:true,
            },
            createdAt:{
                label:'Aangemaakt Op',
                sortable:true,
            },
            updatedAt:{
                label:'Bewerkt Op',
                sortable:true,
            },
            links:{
                label:'',
                name:'Actieknoppen',
                thStyle:{'width':'1px'},
                visible:true,
            },
        },

        mixins:[dataTableMixin],

        components: {
            DisplayTimestamp,
            BaseIcon,
            SpanBirthDate,
            SpanPersonName,
            SpanMembershipStatus,
            PersonAvatar
        },
    }
</script>

<style scoped>

</style>