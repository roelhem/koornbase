<template>

    <div>

        <kb-person-detail-card :person="person"
                               :collapsed.sync="cards.personal.collapsed"
        />

        <show-email-addresses-of-person-card :person-id="this.currentUser.person.id"
                                             :collapsed.sync="cards.emailAddresses.collapsed"
        />

        <show-phone-numbers-of-person-card :person-id="this.currentUser.person.id"
                                           :collapsed.sync="cards.phoneNumbers.collapsed"
        />


        <show-addresses-of-person-card :person-id="this.currentUser.person.id"
                                       :collapsed.sync="cards.addresses.collapsed"
        />

    </div>

</template>

<script>
    import { currentUser } from "../../apis/graphql/dashboard.graphql";
    import DataDisplay from "../../components/displays/DataDisplay";
    import BaseAvatar from "../../components/displays/BaseAvatar";
    import BaseTag from "../../components/displays/BaseTag";
    import ShowEmailAddressesOfPersonCard from "../../components/displays/ShowEmailAddressesOfPersonCard";
    import TablerTableCard from "../../components/layouts/cards/TablerTableCard";
    import KbPersonDetailCard from "../../components/displays/KbPersonDetailCard";
    import TablerCard from "../../components/layouts/cards/TablerCard";
    import DisplayPersonAddress from "../../components/displays/DisplayPersonAddress";
    import { getPersonDetailsShowCardData } from "../../apis/graphql/queries/persons.graphql";
    import ShowPhoneNumbersOfPersonCard from "../../components/displays/ShowPhoneNumbersOfPersonCard";
    import ShowAddressesOfPersonCard from "../../components/displays/ShowAddressesOfPersonCard";

    export default {

        components: {
            ShowAddressesOfPersonCard,
            ShowPhoneNumbersOfPersonCard,
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

            currentUser:currentUser,

            person: {
                query: getPersonDetailsShowCardData,
                variables() {
                    return {
                        id: this.currentUser.person.id
                    };
                }
            }
        },


        data: function() {
            return {
                currentUser: currentUser,
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

        name: "page-me-personal-data"
    }
</script>

<style scoped>

</style>