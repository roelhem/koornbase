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
                        {{ item.nl_mobile }}
                    </data-display>
                </template>

                <template slot="row" slot-scope="{ item }">
                    <th class="font-weight-bold">{{ item.index + 1}}</th>
                    <th>
                        <data-display title="Label">{{ item.label }}</data-display>
                    </th>
                    <td class=" p-2" style="font-size: 1.18rem; letter-spacing: 1.8px; word-spacing: 4px;">
                        <data-display title="Telefoonnummer">{{ item.nl_mobile }}</data-display>
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
                    <td v-html="item.html_formatted"></td>
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
    import axios from 'axios';
    import { mapState } from "vuex";
    import TablerCard from "../TablerCard";
    import DisplayPersonAddress from "../DisplayPersonAddress";

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

        created() {
            this.loadData();
        },

        methods: {
            loadData() {
                axios.get('/api/persons/'+ this.user.person_id, {
                    params: {
                        'with':['phoneNumbers','addresses','emailAddresses'],
                        'fields':['location','type_name','html_formatted']
                    }
                }).then(result => {
                    this.person = result.data.data;
                }).catch(error => {
                    console.log(error);
                });
            }
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