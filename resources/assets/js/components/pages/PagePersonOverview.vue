<template>

    <b-row>

        <b-col lg="4">

            <b-card no-body>
                <detail-view in-card sm class="table-hover">

                    <detail-entry icon="user" title="Naam">
                        <display-person-name v-bind="person" />
                    </detail-entry>

                    <detail-entry icon="birthday-cake" title="Geboortedatum">
                        <display-person-birth-date v-bind="person" />
                    </detail-entry>

                    <detail-entry icon="book" title="Lid-status">
                        <display-membership-status v-bind="person.membership_status" />
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
                            {{ person.phoneNumber.nl_mobile }}
                        </data-display>
                    </detail-entry>

                    <detail-entry v-if="person.address" icon="map-marker" title="Adres">
                        <display-person-address v-bind="person.address" />
                    </detail-entry>
                </detail-view>
            </b-card>

        </b-col>


        <b-col>

        </b-col>


    </b-row>

</template>

<script>
    import DetailView from "../DetailView";
    import DetailEntry from "../DetailEntry";
    import DisplayPersonName from "../DisplayPersonName";
    import KbPersonDetailCard from "../KbPersonDetailCard";
    import DisplayPersonBirthDate from "../DisplayPersonBirthDate";
    import DataDisplay from "../displays/data-display";
    import DisplayMembershipStatus from "../DisplayMembershipStatus";
    import DisplayPersonAddress from "../DisplayPersonAddress";

    export default {
        components: {
            DisplayPersonAddress,
            DisplayMembershipStatus,
            DataDisplay,
            DisplayPersonBirthDate,
            KbPersonDetailCard,
            DisplayPersonName,
            DetailEntry,
            DetailView
        },
        name: "page-person-overview",

        props: {
            person:{
                type:Object,
                default:function() {
                    return {};
                }
            }
        }
    }
</script>

<style scoped>

</style>