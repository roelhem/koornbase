<template>

    <b-container>
        <b-row>
            <b-col lg="6">
                <display-membership-card v-for="(membership) in memberships"
                                         :key="membership.id"
                                         :membership="membership"
                                         :person-id="personId"
                />

                <div class="mb-4" v-if="canApplyToMembership && !$apollo.loading">
                    <b-button block @click="newMembership">Nieuwe inschrijving toevoegen...</b-button>
                </div>
            </b-col>

            <b-col lg="6">
                <pre>{{ person }}</pre>
            </b-col>
        </b-row>


    </b-container>

</template>

<script>
    import TablerCard from "../../components/layouts/cards/TablerCard";
    import DisplayMembershipCard from "../../components/displays/DisplayMembershipCard";
    import { getPersonMembershipsQuery } from "../../apis/graphql/queries/persons.graphql";
    import moment from "moment";
    import gql from "graphql-tag";

    export default {
        components: {
            DisplayMembershipCard,
            TablerCard
        },

        apollo: {
            person: {
                query: gql`
                    query viewPersonMembership($id:ID!) {
                        person(id:$id) {
                            id
                            memberships {
                                data {
                                    ...DisplayMembershipCard
                                }
                            }
                        }
                    }
                    ${DisplayMembershipCard.fragment}
                `,
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
                    memberships:{
                        data:[]
                    }
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
            memberships() {
                if(this.person && this.person.memberships) {
                    return this.person.memberships.data;
                }
                return [];
            },

            canApplyToMembership() {
                return this.memberships.every(membership => membership.status === 'FORMER_MEMBER' || membership.status === 'OUTSIDER');
            }
        },

        methods: {

            newMembership() {
                const person_id = this.personId;

                this.$apollo.mutate({
                    mutation:gql`mutation newMembership($person_id:ID!) {
                        membership:newMembershipApplication(person_id:$person_id) {
                            id person_id application start end status remarks
                        }
                    }`,
                    variables: {person_id},

                    update:(store, {data: {membership}}) => {
                        const query = gql`
                            query viewPersonMembership($id:ID!) {
                                person(id:$id) {
                                    id
                                    memberships {
                                        data {
                                            ...DisplayMembershipCard
                                        }
                                    }
                                }
                            }
                            ${DisplayMembershipCard.fragment}
                        `;

                        const data = store.readQuery({
                            query:query,
                            variables: {id:membership.person_id},
                        });

                        const memberships = data.person.memberships.data;
                        memberships.push(membership);

                        store.writeQuery({
                            query:query,
                            variables:{id:membership.person_id},
                            data
                        })
                    },

                    optimisticResponse: {
                        __typename:'Mutation',
                        membership: {
                            __typename:'Membership',
                            id:"-1",
                            person_id,
                            application:moment().format('YYYY-MM-DD'),
                            start:null,
                            end:null,
                            status:"NOVICE",
                            remarks:null
                        }
                    },
                });
            }

        },

        name: "page-person-membership"
    }
</script>

<style scoped>

</style>
