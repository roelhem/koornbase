<template>


    <tabler-table-card
            title="Telefoonnummers"
            icon="phone"
            icon-from="fe"
            status="orange"
            status-left
            collapsible
            :collapsed="collapsed"
            v-on:update:collapsed="event => $emit('update:collapsed', event)"
            collapsible-with-header
            :rows="person.phoneNumbers"
            :is-loading="$apollo.queries.person.loading"
    >
        <template slot="preview" slot-scope="{ item }">
            <data-display title="Label"> {{ item.label }} </data-display>:
            <data-display title="Primair telefoonnummer" class="text-muted-dark">
                {{ item.phone_number }}
            </data-display>
        </template>

        <template slot="row" slot-scope="{ item }">
            <th class="font-weight-bold">{{ item.index + 1}}</th>
            <th>
                <data-display title="Label">{{ item.label }}</data-display>
            </th>
            <td class=" p-2" style="font-size: 1.18rem; letter-spacing: 1.8px; word-spacing: 4px;">
                <data-display title="Telefoonnummer">{{ item.phone_number }}</data-display>
            </td>
            <td class="text-muted font-weight-bold small">
                <data-display title="Type Telefoonnummer">{{ item.number_type }}</data-display>
            </td>
            <td class="text-muted-dark">
                <data-display title="Land">{{ item.country }}</data-display>
                <span v-if="item.location && item.location !== item.country"
                      class="text-muted font-italic">
                                (<data-display title="Globale locatie">{{ item.location }}</data-display>)
                            </span>
            </td>
        </template>

    </tabler-table-card>


</template>

<script>
    import TablerTableCard from "../layouts/cards/TablerTableCard";
    import DataDisplay from "./DataDisplay";
    import { getPersonPhoneNumbersQuery } from "../../apis/graphql/queries/persons.graphql";

    export default {
        components: {
            DataDisplay,
            TablerTableCard
        },


        apollo: {
            person: {
                query: getPersonPhoneNumbersQuery,
                variables() {
                    return {
                        id: this.personId
                    };
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

        data: function() {
            return {
                person: {
                    phoneNumbers: []
                }
            }
        },

        name: "show-phone-numbers-of-person-card"
    }
</script>

<style scoped>

</style>