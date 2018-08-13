<template>

    <tabler-table-card
            title="E-mailadressen"
            icon="at-sign"
            icon-from="fe"
            status="orange"
            :rows="person.emailAddresses"
            status-left
            no-body
            collapsible
            :collapsed="collapsed"
            v-on:update:collapsed="event => $emit('update:collapsed', event)"
            :is-loading="$apollo.queries.person.loading"
            collapsibleWithHeader
    >
        <template slot="preview" slot-scope="{ item }">
            <data-display title="Label">{{ item.label }}</data-display>:
            <data-display title="Primair e-mailadres" class="text-muted-dark">
                {{ item.email_address }}
            </data-display>
        </template>
        <template slot="row" slot-scope="{ item }">
            <th class="font-weight-bold">{{ item.index + 1}}</th>
            <th>
                <data-display title="Label">{{ item.label }}</data-display>
            </th>
            <td class="tracking-wide">
                <data-display title="E-mailadres">{{ item.email_address }}</data-display>
            </td>
        </template>
    </tabler-table-card>

</template>

<script>
    import DataDisplay from "./displays/data-display";
    import TablerTableCard from "./TablerTableCard";
    import { getPersonEmailAddressesData } from "../graphql/queries/persons.graphql";

    export default {

        components: {
            TablerTableCard,
            DataDisplay
        },
        name: "show-email-addresses-of-person-card",

        apollo: {
            person: {
                query: getPersonEmailAddressesData,
                variables() {
                    return {
                        id:this.personId,
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
                    emailAddresses: []
                }
            }
        }
    }
</script>

<style scoped>

</style>