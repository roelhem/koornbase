<template>

    <ul class="list-unstyled list-seperated">
        <list-persons-item v-for="(person, index) in persons.data"
                           :key="person.id"
                           :person="person"
                           :removable="removable"
                           @remove="removedPerson => remove(removedPerson, index)"
        />
    </ul>

</template>

<script>
    import gql from "graphql-tag";
    import ListPersonsItem from "./ListPersonsItem";

    export default {
        components: {ListPersonsItem},
        name: "list-persons",

        fragment:gql`
            fragment ListPersons on Person_pagination {
                data {
                    ...ListPersonsItem
                }
            }
            ${ListPersonsItem.fragment}
        `,

        props: {
            persons: {
                type:Object,
                default() {
                    return {
                        data:[]
                    };
                }
            },

            removable: {
                type:Boolean,
                default:false,
            }
        },

        methods: {
            remove(person, index) {
                this.$emit('remove', person, index);
            }
        }
    }
</script>

<style scoped>

</style>