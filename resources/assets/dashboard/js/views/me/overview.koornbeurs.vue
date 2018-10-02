<template>

    <div>

        <h1>Mijn Koornbeurs</h1>

        <h2>Lidmaatschappen bij O.J.V. de Koornbeurs</h2>

        <b-row>
            <b-col col lg="8">
                <display-membership-card v-for="(membership) in me.person.memberships"
                                         :key="membership.id"
                                         :membership="membership"
                />
            </b-col>


        </b-row>



    </div>

</template>

<script>
    import gql from "graphql-tag";
    import DisplayMembershipCard from "../../components/displays/DisplayMembershipCard";

    export default {
        components: {DisplayMembershipCard},


        apollo: {

            me: {
                query:gql`
                    query viewMeOverviewPersonal {
                        me {
                            id
                            person {
                                id
                                memberships {
                                    id
                                    ...DisplayMembershipCard
                                }
                            }
                        }
                    }
                    ${DisplayMembershipCard.fragment}
                `,
            },
        },

        data() {
            return {
                me: {
                    id:null,
                    person: {
                        id:null,
                        memberships:[],
                    }
                }
            };
        },

        name: "view-me-overview-koornbeurs"
    }
</script>

<style scoped>

</style>