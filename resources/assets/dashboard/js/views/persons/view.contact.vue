<template>

    <b-row class="justify-content-center">

        <b-col lg="10">
            <person-email-addresses-card
                    :person="person"
                    collapsible
                    collapsible-with-header
                    :collapsed.sync="cards.personEmailAddressesCard.collapsed"
            />
            <person-phone-numbers-card
                    :person="person"
                    collapsible
                    collapsible-with-header
                    :collapsed.sync="cards.personPhoneNumbersCard.collapsed"
            />
            <person-addresses-card
                    :person="person"
                    collapsible
                    collapsible-with-header
                    :collapsed.sync="cards.personAddressesCard.collapsed"
            />
        </b-col>

    </b-row>

</template>

<script>
    import gql from "graphql-tag";
    import PersonPhoneNumbersCard from "../../components/displays/PersonPhoneNumbersCard";
    import PersonEmailAddressesCard from "../../components/displays/PersonEmailAddressesCard";
    import PersonAddressesCard from "../../components/displays/PersonAddressesCard";

    export default {
        name: "page-person-contact",

        components: {
            PersonAddressesCard,
            PersonEmailAddressesCard,
            PersonPhoneNumbersCard

        },

        apollo: {
            person: {
                query:gql`
                    query viewPersonContact($id:ID!) {
                        person(id:$id) {
                            id
                            ...PersonPhoneNumbersCard
                            ...PersonEmailAddressesCard
                            ...PersonAddressesCard
                        }
                    }
                    ${PersonPhoneNumbersCard.fragment}
                    ${PersonEmailAddressesCard.fragment}
                    ${PersonAddressesCard.fragment}
                `,
                variables() { return {id:this.personId}; }
            }
        },

        data:function() {
            return {
                person:{},

                cards:{
                    personEmailAddressesCard:{collapsed:false},
                    personPhoneNumbersCard:{collapsed:false},
                    personAddressesCard:{collapsed:false}
                }
            };
        },

        props: {
            personId:[Number,String]
        }
    }
</script>

<style scoped>

</style>