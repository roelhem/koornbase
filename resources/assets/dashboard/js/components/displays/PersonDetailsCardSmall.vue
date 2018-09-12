<template>


    <b-card no-body>
        <tabler-dimmer :active="!person.id">
            <detail-view in-card sm class="table-hover">

                <detail-entry icon="user" title="Naam">
                    <span-person-name :person="person" full />
                </detail-entry>

                <detail-entry icon="birthday-cake" title="Geboortedatum">
                    <span-birth-date v-bind="person" />
                </detail-entry>

                <detail-entry icon="book" title="Lid-status">
                    <span-membership-status :status="person.membership_status" :since="person.membership_status_since" />
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
                    <span-address :address="person.address" />
                </detail-entry>
            </detail-view>
        </tabler-dimmer>
    </b-card>

</template>

<script>
    import gql from "graphql-tag";
    import fragments from "../../apis/graphql/queries/fragments";

    import DetailView from "../layouts/cards/DetailView";
    import DetailEntry from "../layouts/cards/DetailEntry";
    import SpanPersonName from "./spans/SpanPersonName";
    import SpanBirthDate from "./spans/SpanBirthDate";
    import DataDisplay from "./DataDisplay";
    import SpanMembershipStatus from "./spans/SpanMembershipStatus";
    import SpanAddress from "./spans/SpanAddress";

    import TablerDimmer from "../layouts/cards/TablerDimmer";



    export default {
        name: "person-details-card-small",

        fragment:gql`
            fragment PersonDetailsCardSmall on Person {
                ...PersonNameSpan
                ...PersonBirthDate
                ...PersonMembershipStatus
                activeCards: cards(active:true) {
                    id
                    ref
                    version
                }
                emailAddress {
                    id
                    email_address
                }
                phoneNumber {
                    id
                    phone_number
                }
                address {
                    id
                    label
                    country_code
                    country
                    locality
                    address_line_1
                }
            }
            ${fragments.PersonNameSpan}
            ${fragments.PersonBirthDate}
            ${fragments.PersonMembershipStatus}
        `,

        props: {
            person:{
                type:Object,
                default:function() {
                    return {
                        person:{
                            activeCards:[]
                        }
                    };
                }
            }
        },

        components: {
            TablerDimmer,
            DetailView,
            DetailEntry,
            DataDisplay,
            SpanAddress,
            SpanMembershipStatus,
            SpanBirthDate,
            SpanPersonName
        }
    }
</script>

<style scoped>

</style>