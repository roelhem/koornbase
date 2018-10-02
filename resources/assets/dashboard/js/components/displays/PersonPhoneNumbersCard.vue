<template>
    <person-contact-entries-card
            :title="title"
            :icon="icon"
            v-bind="$attrs"
            v-on="$listeners"
            :entries="person.phoneNumbers"
            placeholder-text="Geen Telefoonnummers bekend voor deze persoon..."
    >
        <template slot="preview" slot-scope="{item}">
            <base-field title="Label" name="label">{{ item.label }}</base-field>:
            <base-field title="Primair Telefoonnummer" name="phoneNumber.number" class="text-muted-dark">
                {{ item.phoneNumber.number }}
            </base-field>
            , ...
        </template>

        <person-phone-number-table :phone-numbers="person.phoneNumbers" class="card-table" />

    </person-contact-entries-card>
</template>

<script>
    import gql from "graphql-tag";
    import PersonContactEntriesCard from "./PersonContactEntriesCard";
    import PersonPhoneNumberTableRow from "./PersonPhoneNumberTableRow";
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
                phoneNumbers {
                    id
                    label
                    ...PersonPhoneNumberTableRow
                    phoneNumber { number }
                }
            }
            ${PersonPhoneNumberTableRow.fragment}
        `,

        props: {
            person:{
                type:Object,
                required:true,
                default() { return {phoneNumbers:[]} }
            },

            title:{
                type:String,
                default:"Telefoonnummers"
            },

            icon: {
                default:"phone"
            }
        },
    }
</script>

<style scoped>

</style>