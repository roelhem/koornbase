<template>

    <div>

        <kb-person-detail-card :person="person"
                               :collapsed.sync="cards.personal.collapsed"
        />

        <show-email-addresses-of-person-card :person-id="user.person_id"
                                             :collapsed.sync="cards.emailAddresses.collapsed"
        />

        <show-phone-numbers-of-person-card :person-id="user.person_id"
                                           :collapsed.sync="cards.phoneNumbers.collapsed"
        />


        <show-addresses-of-person-card :person-id="user.person_id"
                                       :collapsed.sync="cards.addresses.collapsed"
        />

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
    import { getPersonDetailsShowCardData } from "../../queries/persons.graphql";
    import ShowPhoneNumbersOfPersonCard from "../ShowPhoneNumbersOfPersonCard";
    import ShowAddressesOfPersonCard from "../ShowAddressesOfPersonCard";

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
            person: {
                query: getPersonDetailsShowCardData,
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