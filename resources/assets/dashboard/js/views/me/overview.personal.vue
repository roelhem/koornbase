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
    import { CURRENT_USER } from "../../apis/graphql/queries";
    import ShowEmailAddressesOfPersonCard from "../../components/displays/ShowEmailAddressesOfPersonCard";
    import { getPersonDetailsShowCardData } from "../../apis/graphql/queries/persons.graphql";
    import ShowPhoneNumbersOfPersonCard from "../../components/displays/ShowPhoneNumbersOfPersonCard";
    import ShowAddressesOfPersonCard from "../../components/displays/ShowAddressesOfPersonCard";

    export default {

        components: {
            ShowAddressesOfPersonCard,
            ShowPhoneNumbersOfPersonCard,
            ShowEmailAddressesOfPersonCard,
        },

        apollo: {

            currentUser:CURRENT_USER,

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
                currentUser: CURRENT_USER,
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

        name: "view-me-overview-personal"
    }
</script>

<style scoped>

</style>