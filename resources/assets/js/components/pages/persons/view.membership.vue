<template>

    <b-container>
        <b-row>
            <b-col lg="6">

            </b-col>
            <b-col lg="6">
                <display-membership-card v-for="(membership) in person.memberships"
                                         :key="membership.id"
                                         :membership="membership"
                                         :person-id="personId"
                />

                <div class="mb-4" v-if="canApplyToMembership && !$apollo.loading">
                    <b-button block>Nieuwe inschrijving toevoegen...</b-button>
                </div>
            </b-col>
        </b-row>


    </b-container>

</template>

<script>
    import TablerCard from "../../TablerCard";
    import DisplayMembershipCard from "../../DisplayMembershipCard";
    import { getPersonMembershipsQuery } from "../../../graphql/queries/persons.graphql";

    export default {
        components: {
            DisplayMembershipCard,
            TablerCard
        },

        apollo: {
            person: {
                query: getPersonMembershipsQuery,
                variables() {
                    return {
                        id:this.personId
                    };
                }
            }
        },

        data() {
            return {
                person: {
                    id:"-1",
                    memberships:[]
                },

            }
        },

        props: {
            personId:{
                type:[String,Number],
                required:true,
            },
        },

        computed: {
            canApplyToMembership() {
                return this.person.memberships.every(membership => membership.status === 'FORMER_MEMBER' || membership.status === 'OUTSIDER');
            }
        },

        name: "page-person-membership"
    }
</script>

<style scoped>

</style>
