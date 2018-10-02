<template>

    <div>


        <h1>Mijn Persoonsgegevens</h1>

        <person-detail-card
                :person="person"
                status="azure"
                status-left
                collapsible
                collapsibleWithHeader
                :collapsed.sync="cards.personDetailCard.collapsed"
        >
            <div class="text-right" slot="footer">
                <b-button variant="link"
                          :to="{ name:'db.persons.view.overview', params:{id:person.id} }"
                >Naar Persoonspagina...</b-button>
            </div>
        </person-detail-card>

        <h2>Contactgegevens</h2>

        <person-email-addresses-card
                :person="person"
                status="orange"
                status-left
                collapsible
                collapsibleWithHeader
                :collapsed.sync="cards.personEmailAddressesCard.collapsed"
        />

        <person-phone-numbers-card
                :person="person"
                status="orange"
                status-left
                collapsible
                collapsibleWithHeader
                :collapsed.sync="cards.personPhoneNumbersCard.collapsed"
        />
        <person-addresses-card
                :person="person"
                status="orange"
                status-left
                collapsible
                collapsibleWithHeader
                :collapsed.sync="cards.personAddressesCard.collapsed"
        />


    </div>

</template>

<script>
    import gql from "graphql-tag";
    import PersonEmailAddressesCard from "../../components/displays/PersonEmailAddressesCard";
    import PersonPhoneNumbersCard from "../../components/displays/PersonPhoneNumbersCard";
    import PersonAddressesCard from "../../components/displays/PersonAddressesCard";
    import PersonDetailCard from "../../components/displays/PersonDetailCard";

    export default {

        components: {
            PersonDetailCard,
            PersonAddressesCard,
            PersonPhoneNumbersCard,
            PersonEmailAddressesCard,
        },

        apollo: {

            me: {
                query:gql`
                    query viewMeOverviewPersonal {
                        me {
                            id
                            person {
                                ...PersonDetailCard
                                ...PersonAddressesCard
                                ...PersonPhoneNumbersCard
                                ...PersonEmailAddressesCard
                            }
                        }
                    }
                    ${PersonDetailCard.fragment}
                    ${PersonAddressesCard.fragment}
                    ${PersonPhoneNumbersCard.fragment}
                    ${PersonEmailAddressesCard.fragment}
                `,
            },
        },


        data: function() {
            return {
                me: {
                    id:null,
                    person: {
                        addresses:[],
                        emailAddresses:[],
                        phoneNumbers:[]
                    }
                },

                cards: {
                    personDetailCard:{collapsed:false},
                    personEmailAddressesCard:{collapsed:true},
                    personPhoneNumbersCard:{collapsed:true},
                    personAddressesCard:{collapsed:true}
                }
            };
        },

        computed: {
            person() {
                if(this.me && this.me.person) {
                    return this.me.person;
                }
                return {
                    addresses:[],
                    phoneNumbers:[],
                    emailAddresses:[],
                };
            }
        },

        name: "view-me-overview-personal"
    }
</script>

<style scoped>

</style>