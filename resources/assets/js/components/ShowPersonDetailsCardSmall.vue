<template>


    <b-card no-body>
        <tabler-dimmer :active="$apollo.queries.person.loading">
            <detail-view in-card sm class="table-hover">

                <detail-entry icon="user" title="Naam">
                    <display-person-name v-bind="person" />
                </detail-entry>

                <detail-entry icon="birthday-cake" title="Geboortedatum">
                    <display-person-birth-date v-bind="person" />
                </detail-entry>

                <detail-entry icon="book" title="Lid-status">
                    <display-membership-status :status="person.membership_status" :since="person.membership_status_since" />
                </detail-entry>

                <detail-entry icon="credit-card"
                              title="Koornbeurs-kaart"
                              v-for="activeCard in person.activeCards"
                              :key="'active-card-'+activeCard.id">
                    <data-display title="Koornbeurs-kaart referentie">{{ activeCard.ref }}</data-display>
                    <span class="text-muted">
                                (
                                <data-display title="Koornbeurs-kaart versie"
                                              class="font-italic"
                                >{{ activeCard.version }}</data-display>
                                )
                            </span>
                </detail-entry>

                <detail-entry v-if="person.emailAddress" icon="at" title="E-mailadres">
                    <data-display title="E-mailadres">
                        {{ person.emailAddress.email_address }}
                    </data-display>
                </detail-entry>

                <detail-entry v-if="person.phoneNumber" icon="phone" title="Telefoonnummer">
                    <data-display title="Telefoonnummer">
                        {{ person.phoneNumber.phone_number }}
                    </data-display>
                </detail-entry>

                <detail-entry v-if="person.address" icon="map-marker" title="Adres">
                    <display-person-address v-bind="person.address" />
                </detail-entry>
            </detail-view>
        </tabler-dimmer>
    </b-card>

</template>

<script>
    import DetailView from "./DetailView";
    import DetailEntry from "./DetailEntry";
    import DisplayPersonName from "./DisplayPersonName";
    import DisplayPersonBirthDate from "./DisplayPersonBirthDate";
    import DataDisplay from "./displays/data-display";
    import DisplayMembershipStatus from "./DisplayMembershipStatus";
    import DisplayPersonAddress from "./DisplayPersonAddress";

    import { getPersonDetailsData } from "../queries/persons.graphql";
    import TablerDimmer from "./TablerDimmer";



    export default {
        name: "show-person-details-card-small",

        props: {
            personId: {
                type:[String,Number],
                required:true
            }
        },

        apollo: {

            person:{
                query: getPersonDetailsData,
                variables() {
                    return {
                        id:this.personId
                    };
                }
            }

        },

        data:function() {
            return {
                person:{
                    activeCards:[]
                }
            }
        },

        components: {
            TablerDimmer,
            DetailView,
            DetailEntry,
            DataDisplay,
            DisplayPersonAddress,
            DisplayMembershipStatus,
            DisplayPersonBirthDate,
            DisplayPersonName
        }
    }
</script>

<style scoped>

</style>