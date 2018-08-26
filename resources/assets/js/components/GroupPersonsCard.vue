<template>

    <tabler-card body-class="o-auto"
                 body-style="max-height:600px"
    >
        <template slot="title">
            Personen in
            <template v-if="group.name">'<span class="font-italic">{{ group.name }}</span>'</template>
            <template v-else>deze groep</template>
        </template>

        <template slot="options">
            <b-button variant="success"
                      @click="displayAddPersonModal"
            >
                Toevoegen
            </b-button>
        </template>

        <list-persons :persons="persons"
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

            <p>Kies een of meerdere personen die je aan de groep '<em>{{ group.name }}</em>' wilt toevoegen.</p>

            <person-select :value="newPersons"
                           @change="newValue => newPersons = newValue"
                           multiple
            />

            {{ newPersons }}
        </tabler-modal>

    </tabler-card>

</template>

<script>
    import TablerCard from "./TablerCard";
    import ListPersons from "./ListPersons";
    import { removePersonFromGroup, addPersonToGroup } from "../graphql/mutations/groups.graphql";
    import { getGroupDetailsQuery } from "../graphql/queries/groups.graphql";
    import PersonSelect from "./forms/select/PersonSelect";
    import TablerModal from "./TablerModal";

    export default {
        components: {
            TablerModal,
            PersonSelect,
            ListPersons,
            TablerCard
        },
        name: "group-persons-card",

        props: {
            group: {
                type:Object,
                default() {
                    return {
                        persons:[],
                    }
                }
            }
        },

        data() {
            return {
                newPersons:[]
            }
        },

        computed: {
            persons() {
                return this.group.persons;
            },
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
                        const data = store.readQuery({
                            query:getGroupDetailsQuery,
                            variables: {id:group_id }
                        });

                        const persons = data.group.persons;
                        persons.push(person);

                        store.writeQuery({
                            query: getGroupDetailsQuery,
                            data
                        });

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
                        const data = store.readQuery({
                            query:getGroupDetailsQuery,
                            variables: {id:group_id }
                        });

                        const persons = data.group.persons;

                        const index = persons.findIndex(person => person.id === person_id);

                        if(index >= 0) {
                            persons.splice(index, 1);

                            store.writeQuery({query: getGroupDetailsQuery, data});
                        }
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