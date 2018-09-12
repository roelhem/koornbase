<template>
    <person-contact-entries-card
            :title="title"
            :icon="icon"
            v-bind="$attrs"
            v-on="$listeners"
            :entries="emailAddresses"
            placeholder-text="Geen E-mailadressen bekend voor deze persoon..."
    >
        <template slot="preview" slot-scope="{item}">
            <base-field title="Label" name="label">{{ item.label }}</base-field>:
            <base-field title="Primair E-mailadres" name="email_address" class="text-muted-dark">{{ item.email_address }}</base-field>
            , ...
        </template>


        <person-email-address-table :email-addresses="emailAddresses" class="card-table" />

    </person-contact-entries-card>
</template>

<script>
    import gql from "graphql-tag";
    import PersonContactEntriesCard from "./PersonContactEntriesCard";
    import PersonEmailAddressTable from "./PersonEmailAddressTable";
    import BaseField from "./BaseField";

    export default {
        name: "person-email-addresses-card",

        components: {
            BaseField,
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