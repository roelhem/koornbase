<template>

    <tabler-table-card
            title="Addressen"
            icon="map-pin"
            icon-from="fe"
            status="orange"
            status-left
            collapsible
            :collapsed="collapsed"
            v-on:update:collapsed="event => $emit('update:collapsed', event)"
            collapsible-with-header
            :rows="person.addresses"
            :is-loading="$apollo.queries.person.loading"
    >
        <template slot="preview" slot-scope="{ item }">
            <data-display title="Label">{{ item.label }}</data-display>:
            <span class="text-muted-dark">
                    <display-person-address v-bind="item" />
                </span>
        </template>

        <template slot="row" slot-scope="{ item }">
            <th class="font-weight-bold">{{ item.index + 1}}</th>
            <th>
                <data-display title="Label">{{ item.label }}</data-display>
            </th>
            <td v-html="item.format"></td>
        </template>
    </tabler-table-card>

</template>

<script>
    import TablerTableCard from "./TablerTableCard";
    import DataDisplay from "./displays/data-display";
    import DisplayPersonAddress from "./DisplayPersonAddress";
    import { getPersonAddressesQuery } from "../graphql/queries/persons.graphql";

    export default {


        apollo: {
            person: {
                query: getPersonAddressesQuery,
                variables() {
                    return {
                        id: this.personId
                    }
                }
            }
        },

        data: function() {
            return {
                person: {
                    addresses: []
                }
            }
        },

        props: {
            personId: {
                type: [Number,String],
                required: true
            },

            collapsed: {
                type: Boolean,
                default: false
            }
        },

        components: {
            DisplayPersonAddress,
            DataDisplay,
            TablerTableCard
        },
        name: "show-addresses-of-person-card"
    }
</script>

<style scoped>

</style>