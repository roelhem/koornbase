<template>
    <person-contact-entries-card
            :title="title"
            :icon="icon"
            v-bind="$attrs"
            v-on="$listeners"
            :entries="phoneNumbers"
            placeholder-text="Geen Telefoonnummers bekend voor deze persoon..."
    >
        <template slot="preview" slot-scope="{item}">
            <base-field title="Label" name="label">{{ item.label }}</base-field>:
            <base-field title="Primair Telefoonnummer" name="phone_number" class="text-muted-dark">
                {{ item.phone_number }}
            </base-field>
            , ...
        </template>

        <person-phone-number-table :phone-numbers="phoneNumbers" class="card-table" />

    </person-contact-entries-card>
</template>

<script>
    import gql from "graphql-tag";
    import PersonContactEntriesCard from "./PersonContactEntriesCard";
    import PersonPhoneNumberTable from "./PersonPhoneNumberTable";
    import BaseField from "./BaseField";

    export default {
        name: "person-phone-numbers-card",


        components: {
            BaseField,
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