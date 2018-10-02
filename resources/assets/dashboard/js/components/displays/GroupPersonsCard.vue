<template>

    <tabler-card body-class="o-auto"
                 body-style="max-height:600px"
    >
        <template slot="title">
            Personen in
            <template v-if="group.name">'<span class="font-italic">{{ group.name }}</span>'</template>
            <template v-else>deze groep</template>
            <span class="text-muted">({{ total }})</span>
        </template>

        <template slot="options">
            <b-button variant="success"
                      @click="displayAddPersonModal"
            >
                Toevoegen
            </b-button>
        </template>

        <list-persons :persons="persons.edges ? persons.edges.map(edge => edge.node) : []"
                      removable
                      @remove="removeHandler"
        />

        <tabler-modal ref="addPersonModal"
                      title="Persoon aan groep toevoegen"
                      @cancel="resetAddPersonForm"
                      @ok="addPersonsHandler"
                      ok-title="Toevoegen"
                      ok-variant="success"
        >
            <template slot="title">
                Persoon aan '<em>{{ group.name }}</em>' toevoegen
            </template>

            <p>Kies een of meerdere personen die je aan de groep '<em>{{ group.name }}</em>' wilt toevoegen.</p>

            <person-select v-model="newPersons" multiple />

        </tabler-modal>

        <tabler-modal ref="deletePersonModal"
                      @cancel="removeCancelHandler"
                      @ok="removeOkHandler"
                      ok-title="Verwijderen"
                      ok-variant="danger"
        >
            <template slot="title">
                Persoon uit '<em>{{ group.name }}</em>' verwijderen
            </template>

            <div v-if="deletingPerson" class="d-flex mb-2">
                <div class="flex-shrink-1 p-2">
                    <person-avatar :person="deletingPerson" />
                </div>
                <div class="p-2">
                    <div>
                        <span-person-name :person-name="deletingPerson.name" with-nickname />
                    </div>
                    <div class="small">
                        <span-membership-status :membership-status="deletingPerson.SpanMembershipStatus" />
                    </div>
                </div>
            </div>

            <p class="mt-2">Weet je zeker dat je deze persoon uit de groep '<em>{{ group.name }}</em>' wilt verwijderen?</p>

        </tabler-modal>

    </tabler-card>

</template>

<script>
    import gql from "graphql-tag";
    import TablerCard from "../layouts/cards/TablerCard";
    import ListPersons from "./ListPersons";
    import ListPersonsItem from "./ListPersonsItem";
    import { removePersonFromGroup, addPersonToGroup } from "../../apis/graphql/mutations/groups.graphql";
    import PersonSelect from "../inputs/select/PersonSelect";
    import TablerModal from "../layouts/modals/TablerModal";
    import VueMultiselect from "vue-multiselect/src/Multiselect";
    import BaseTag from "./BaseTag";
    import PersonAvatar from "./PersonAvatar";
    import SpanMembershipStatus from "./spans/SpanMembershipStatus";
    import SpanPersonName from "./spans/SpanPersonName";

    export default {
        components: {
            SpanPersonName,
            SpanMembershipStatus,
            PersonAvatar,
            BaseTag,
            VueMultiselect,
            TablerModal,
            PersonSelect,
            ListPersons,
            TablerCard
        },
        name: "group-persons-card",

        fragment: gql`
            fragment GroupPersonsCard on Group {
                name
                persons {
                    totalCount
                    edges {
                        node {
                            id
                            name { ...SpanPersonName }
                            ...ListPersonsItem
                        }
                    }
                }
            }
            ${SpanPersonName.fragment}
            ${SpanMembershipStatus.fragment}
            ${ListPersonsItem.fragment}
        `,

        props: {
            group: {
                type:Object,
                default() {
                    return {
                        persons:{
                            totalCount:0,
                            edges: []
                        },
                    }
                }
            }
        },

        data() {
            return {
                newPersons:[],
                deletingPerson:null
            }
        },

        computed: {
            persons() {
                return this.group.persons;
            },

            total() {
                if(this.persons && typeof this.persons.totalCount === 'number') {
                    return this.persons.totalCount;
                }
                return 0;
            }
        },

        methods: {

            displayAddPersonModal() {
                this.$refs.addPersonModal.show();
            },

            resetAddPersonForm() {
                this.newPersons = [];
            },

            addPersonsHandler() {
                this.newPersons.forEach(this.addPersonHandler);
                this.newPersons = [];
            },

            addPersonHandler(newPerson) {
                const group_id = this.group.id;
                const person_id = newPerson.id;

                console.log(newPerson);

                this.$apollo.mutate({

                    mutation: addPersonToGroup,
                    variables: { person_id, group_id },

                    update:(store, {
                        data: {
                            createPersonGroupConnection: {
                                person, group_id
                            }
                        }
                    }) => {
                        /*const data = store.readQuery({
                            query:getGroupDetailsQuery,
                            variables: {id:group_id }
                        });

                        const persons = data.group.persons;
                        persons.push(person);

                        store.writeQuery({
                            query: getGroupDetailsQuery,
                            data
                        });*/

                    },

                    optimisticResponse: {
                        __typename:'Mutation',
                        createPersonGroupConnection: {
                            __typename:'PersonGroupConnection',
                            person_id, group_id,
                            person:newPerson
                        }
                    }

                }).then(data => console.log(data))
                    .catch(error => console.error(error));
            },

            removeHandler(person) {
                this.deletingPerson = person;
                this.$refs.deletePersonModal.show();
            },

            removeOkHandler() {
                if(this.deletingPerson) {
                    this.removePersonMutation(this.deletingPerson);
                }
                this.deletingPerson = null;
            },

            removeCancelHandler() {
                this.deletingPerson = null;
            },


            removePersonMutation(person) {
                const person_id = person.id;
                const group_id = this.group.id;

                this.$apollo.mutate({

                    mutation: removePersonFromGroup,
                    variables: {person_id, group_id},

                    update:(store, {
                        data: {
                            deletePersonGroupConnection: {
                                person_id,
                                group_id
                            }
                        }
                    }) => {
                        /*const data = store.readQuery({
                            query:getGroupDetailsQuery,
                            variables: {id:group_id }
                        });

                        const persons = data.group.persons;

                        const index = persons.findIndex(person => person.id === person_id);

                        if(index >= 0) {
                            persons.splice(index, 1);

                            store.writeQuery({query: getGroupDetailsQuery, data});
                        }*/
                    },

                    optimisticResponse: {
                        __typename:'Mutation',
                        deletePersonGroupConnection: {
                            __typename:'PersonGroupConnection',
                            person_id, group_id
                        }
                    },

                }).then(data => console.log(data))
                    .catch(error => console.error(error));
            }
        }
    }
</script>

<style scoped>

</style>