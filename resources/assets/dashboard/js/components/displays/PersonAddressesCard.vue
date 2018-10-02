<template>
    <person-contact-entries-card
            :title="title"
            :icon="icon"
            v-bind="$attrs"
            v-on="$listeners"
            :entries="person.addresses"
            placeholder-text="Geen Addressen bekend voor deze persoon..."
    >
        <template slot="preview" slot-scope="{item}">
            <base-field title="Label" name="label">{{ item.label }}</base-field>:
            <span-address class="text-muted-dark" :address="item.postalAddress" />
            , ...
        </template>

        <person-address-table :addresses="person.addresses" class="card-table" />
    </person-contact-entries-card>
</template>

<script>
    import gql from "graphql-tag";
    import PersonContactEntriesCard from "./PersonContactEntriesCard";
    import PersonAddressTable from "./PersonAddressTable";
    import PersonAddressTableRow from "./PersonAddressTableRow";
    import BaseField from "./BaseField";
    import SpanAddress from "./spans/SpanAddress";

    export default {
        name: "person-addresses-card",

        components: {
            SpanAddress,
            BaseField,
            PersonAddressTable,
            PersonContactEntriesCard
        },


        fragment: gql`
            fragment PersonAddressesCard on Person {
                addresses {
                    id
                    label
                    ...PersonAddressTableRow
                    postalAddress { ...SpanAddress }
                }
            }
            ${PersonAddressTableRow.fragment}
            ${SpanAddress.fragment}
        `,


        props: {
            person:{
                type:Object,
                required:true,
                default() {
                    return {
                        addresses:[]
                    };
                }
            },

            title:{
                type:String,
                default:"Addressen",
            },

            icon: {
                default:"map-pin",
            },
        },


    }
</script>

<style scoped>

</style>