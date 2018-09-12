<template>
    <person-contact-entries-card
            :title="title"
            :icon="icon"
            v-bind="$attrs"
            v-on="$listeners"
            :entries="addresses"
    >
        <person-address-table :addresses="addresses" class="card-table" />
    </person-contact-entries-card>
</template>

<script>
    import gql from "graphql-tag";
    import PersonContactEntriesCard from "./PersonContactEntriesCard";
    import PersonAddressTable from "./PersonAddressTable";

    export default {
        name: "person-addresses-card",

        components: {
            PersonAddressTable,
            PersonContactEntriesCard
        },


        fragment: gql`
            fragment PersonAddressesCard on Person {
                addresses(orderBy:[{by:index,dir:ASC}]) {
                    total
                    ...PersonAddressTable
                }
            }
            ${PersonAddressTable.fragment}
        `,


        props: {
            person:{
                type:Object
            },

            title:{
                type:String,
                default:"Addressen",
            },

            icon: {
                default:"map-pin",
            },
        },

        computed: {
            addresses() {
                if(this.person && this.person.addresses) {
                    return this.person.addresses;
                }
                return {data:[]};
            }
        }


    }
</script>

<style scoped>

</style>