<template>

    <ul class="list-unstyled list-seperated">
        <list-persons-item v-for="(person, index) in persons"
                           :key="person.id"
                           :person="person"
                           :removable="removable"
                           @remove="removedPerson => remove(removedPerson, index)"
        />
    </ul>

</template>

<script>
    import ListPersonsItem from "./ListPersonsItem";

    export default {
        components: {ListPersonsItem},
        name: "list-persons",

        props: {
            persons: {
                type:Array,
                default() {
                    return [];
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