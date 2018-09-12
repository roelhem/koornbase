<template>
    <person-contact-entries-card
            :title="title"
            :icon="icon"
            v-bind="$attrs"
            v-on="$listeners"
            :entries="addresses"
            placeholder-text="Geen Addressen bekend voor deze persoon..."
    >
        <template slot="preview" slot-scope="{item}">
            <base-field title="Label" name="label">{{ item.label }}</base-field>:
            <span-address class="text-muted-dark" :address="item" />
            , ...
        </template>

        <person-address-table :addresses="addresses" class="card-table" />
    </person-contact-entries-card>
</template>

<script>
    import gql from "graphql-tag";
    import PersonContactEntriesCard from "./PersonContactEntriesCard";
    import PersonAddressTable from "./PersonAddressTable";
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
                addresses(orderBy:[{by:index,dir:ASC}]) {
                    total
                    ...PersonAddressTable
                    data {
                        ...SpanAddress
                    }
                }
            }
            ${PersonAddressTable.fragment}
            ${SpanAddress.fragment}
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