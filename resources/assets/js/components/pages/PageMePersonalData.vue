<template>

    <div>

        <kb-person-detail-card :person="person"
                               :collapsed.sync="cards.personal.collapsed"
        />

        <template v-if="person">
            <show-email-addresses-of-person-card :person="person"
                                                 :collapsed.sync="cards.emailAddresses.collapsed"
            />

            <tabler-table-card
                    title="Telefoonnummers"
                    icon="phone"
                    icon-from="fe"
                    status="orange"
                    status-left
                    collapsible
                    :collapsed.sync="cards.phoneNumbers.collapsed"
                    collapsible-with-header
                    :rows="person.phoneNumbers"
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
                        <data-display title="Type Telefoonnummer">{{ item.type_name }}</data-display>
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




            <tabler-table-card
                    title="Addressen"
                    icon="map-pin"
                    icon-from="fe"
                    status="orange"
                    status-left
                    collapsible
                    :collapsed.sync="cards.addresses.collapsed"
                    collapsible-with-header
                    :rows="person.addresses"
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

        <template v-else>
            <tabler-card no-header no-body is-loading status="orange" status-left>
                <div class="h-8"></div>
            </tabler-card>
            <tabler-card no-header no-body is-loading status="orange" status-left>
                <div class="h-8"></div>
            </tabler-card>
            <tabler-card no-header no-body is-loading status="orange" status-left>
                <div class="h-8"></div>
            </tabler-card>
        </template>


    </div>

</template>

<script>
    import DataDisplay from "../displays/data-display";
    import BaseAvatar from "../BaseAvatar";
    import BaseTag from "../BaseTag";
    import ShowEmailAddressesOfPersonCard from "../ShowEmailAddressesOfPersonCard";
    import TablerTableCard from "../TablerTableCard";
    import KbPersonDetailCard from "../KbPersonDetailCard";
    import { mapState } from "vuex";
    import TablerCard from "../TablerCard";
    import DisplayPersonAddress from "../DisplayPersonAddress";
    import gql from 'graphql-tag';

    export default {

        components: {
            DisplayPersonAddress,
            TablerCard,
            KbPersonDetailCard,
            TablerTableCard,
            ShowEmailAddressesOfPersonCard,
            BaseTag,
            BaseAvatar,
            DataDisplay
        },

        apollo: {
            person: {
                query: gql` query GetPersonDetails($id:ID!) {
                    person(id:$id) {
                        name
                        name_first
                        name_initials
                        name_short
                        name_nickname
                        name_middle
                        name_last
                        birth_date

                        emailAddresses {
                            id index label email_address
                        }

                        phoneNumbers {
                            id index label phone_number location country
                        }

                        addresses {
                            id index label address_line_1 locality country_code format(html:true)
                        }
                    }
                }`,
                variables() {
                    return {
                        id: this.user.person_id
                    };
                }
            }
        },


        data: function() {
            return {
                person: null,

                cards:{
                    personal:{collapsed: false},
                    emailAddresses:{collapsed:true},
                    phoneNumbers:{collapsed:true},
                    addresses:{collapsed:true},
                    koornbeursCards:{collapsed:true}
                },
            };
        },

        computed: {
            ...mapState({
                user: state => state.currentUser
            })
        },

        name: "page-me-personal-data"
    }
</script>

<style scoped>

</style>