<template>
    <person-contact-entries-card
            :title="title"
            :icon="icon"
            v-bind="$attrs"
            v-on="$listeners"
            :entries="person.emailAddresses"
            placeholder-text="Geen E-mailadressen bekend voor deze persoon..."
    >

        <template slot="preview" slot-scope="{item}">
            <base-field title="Label" name="label">{{ item.label }}</base-field>:
            <base-field title="Primair E-mailadres" name="emailAddress.email" class="text-muted-dark">{{ item.emailAddress.email }}</base-field>
            , ...
        </template>


        <person-email-address-table :email-addresses="person.emailAddresses" class="card-table" />

    </person-contact-entries-card>
</template>

<script>
    import gql from "graphql-tag";
    import PersonContactEntriesCard from "./PersonContactEntriesCard";
    import PersonEmailAddressTableRow from "./PersonEmailAddressTableRow";
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
                emailAddresses {
                    ...PersonEmailAddressTableRow
                    label
                    emailAddress { email }
                }
            }
            ${PersonEmailAddressTableRow.fragment}
        `,


        props: {
            person:{
                type:Object,
                default() {
                    return {
                        emailAddresses:[],
                    }
                }
            },

            title:{
                type:String,
                default:"E-mailadressen"
            },

            icon: {
                default:"at-sign"
            }
        },

    }
</script>

<style scoped>

</style>