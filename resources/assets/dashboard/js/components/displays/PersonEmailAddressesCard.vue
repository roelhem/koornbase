<template>
    <person-contact-entries-card
            :title="title"
            :icon="icon"
            v-bind="$attrs"
            v-on="$listeners"
            :entries="emailAddresses"
    >
        <person-email-address-table :email-addresses="emailAddresses" class="card-table" />

    </person-contact-entries-card>
</template>

<script>
    import gql from "graphql-tag";
    import PersonContactEntriesCard from "./PersonContactEntriesCard";
    import PersonEmailAddressTable from "./PersonEmailAddressTable";

    export default {
        name: "person-email-addresses-card",

        components: {
            PersonEmailAddressTable,
            PersonContactEntriesCard
        },


        fragment: gql`
            fragment PersonEmailAddressesCard on Person {
                emailAddresses(orderBy:[{by:index,dir:ASC}]) {
                    total
                    ...PersonEmailAddressTable
                }
            }
            ${PersonEmailAddressTable.fragment}
        `,


        props: {
            person:{
                type:Object
            },

            title:{
                type:String,
                default:"E-mailadressen"
            },

            icon: {
                default:"at-sign"
            }
        },

        computed: {
            emailAddresses() {
                if(this.person && this.person.emailAddresses) {
                    return this.person.emailAddresses;
                }
                return {data:[]};
            }
        }

    }
</script>

<style scoped>

</style>