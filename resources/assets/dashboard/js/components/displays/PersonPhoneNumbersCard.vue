<template>
    <person-contact-entries-card
            :title="title"
            :icon="icon"
            v-bind="$attrs"
            v-on="$listeners"
            :entries="phoneNumbers"
    >
        <person-phone-number-table :phone-numbers="phoneNumbers" class="card-table" />

    </person-contact-entries-card>
</template>

<script>
    import gql from "graphql-tag";
    import PersonContactEntriesCard from "./PersonContactEntriesCard";
    import PersonPhoneNumberTable from "./PersonPhoneNumberTable";

    export default {
        name: "person-phone-numbers-card",


        components: {
            PersonPhoneNumberTable,
            PersonContactEntriesCard},

        fragment: gql`
            fragment PersonPhoneNumbersCard on Person {
                phoneNumbers(orderBy:[{by:index,dir:ASC}]) {
                    total
                    ...PersonPhoneNumberTable
                }
            }
            ${PersonPhoneNumberTable.fragment}
        `,

        props: {
            person:{
                type:Object
            },

            title:{
                type:String,
                default:"Telefoonnummers"
            },

            icon: {
                default:"phone"
            }
        },

        computed: {
            phoneNumbers() {
                if(this.person && this.person.phoneNumbers) {
                    return this.person.phoneNumbers;
                }
                return {data:[]};
            }
        }
    }
</script>

<style scoped>

</style>