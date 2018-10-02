<template>


    <b-card no-body>
        <tabler-dimmer :active="!person.id">
            <detail-view in-card sm class="table-hover">

                <detail-entry icon="user" title="Naam">
                    <span-person-name :person-name="person.name" full />
                </detail-entry>

                <detail-entry icon="birthday-cake" title="Geboortedatum">
                    <span-birth-date :birth-date="person.birthDate" />
                </detail-entry>

                <detail-entry icon="book" title="Lid-status">
                    <span-membership-status :membership-status="person.membershipStatus" />
                </detail-entry>
                <!--
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
                </detail-entry>-->

                <detail-entry v-if="emailAddress" icon="at" title="E-mailadres">
                    <email-address-span :email-address="emailAddress.emailAddress" with-button />
                </detail-entry>

                <detail-entry v-if="phoneNumber" icon="phone" title="Telefoonnummer">
                    <phone-number-span :phone-number="phoneNumber.phoneNumber" with-location with-button />
                </detail-entry>

                <detail-entry v-if="address" icon="map-marker" title="Adres">
                    <span-address v-if="address.postalAddress" :address="address.postalAddress" />
                </detail-entry>
            </detail-view>
        </tabler-dimmer>
    </b-card>

</template>

<script>
    import gql from "graphql-tag";

    import DetailView from "../layouts/cards/DetailView";
    import DetailEntry from "../layouts/cards/DetailEntry";
    import SpanPersonName from "./spans/SpanPersonName";
    import SpanBirthDate from "./spans/SpanBirthDate";
    import DataDisplay from "./DataDisplay";
    import SpanMembershipStatus from "./spans/SpanMembershipStatus";
    import SpanAddress from "./spans/SpanAddress";

    import TablerDimmer from "../layouts/cards/TablerDimmer";
    import BaseField from "./BaseField";
    import PhoneNumberSpan from "./PhoneNumberSpan";
    import EmailAddressSpan from "./EmailAddressSpan";



    export default {
        name: "person-details-card-small",

        fragment:gql`
            fragment PersonDetailsCardSmall on Person {
                id
                name { ...SpanPersonName }
                birthDate
                membershipStatus { ...SpanMembershipStatus }
                emailAddresses {
                    id
                    emailAddress {
                        ...EmailAddressSpan
                    }
                }
                phoneNumbers {
                    id
                    phoneNumber {
                        ...PhoneNumberSpan
                    }
                }
                addresses {
                    id
                    postalAddress { ...SpanAddress }
                }
            }
            ${EmailAddressSpan.fragment}
            ${PhoneNumberSpan.fragment}
            ${SpanPersonName.fragment}
            ${SpanMembershipStatus.fragment}
            ${SpanAddress.fragment}
        `,

        props: {
            person:{
                type:Object,
                default:function() {
                    return {
                        name:{},
                        membershipStatus:{}
                    };
                }
            }
        },

        computed: {
            emailAddress() {
                if(this.person.emailAddresses && this.person.emailAddresses.length > 0) {
                    return this.person.emailAddresses[0];
                } else {
                    return null;
                }
            },

            phoneNumber() {
                if(this.person.phoneNumbers && this.person.phoneNumbers.length > 0) {
                    return this.person.phoneNumbers[0];
                } else {
                    return null;
                }
            },

            address() {
                if(this.person.addresses && this.person.addresses.length > 0) {
                    return this.person.addresses[0];
                } else {
                    return null;
                }
            }
        },

        components: {
            EmailAddressSpan,
            PhoneNumberSpan,
            BaseField,
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