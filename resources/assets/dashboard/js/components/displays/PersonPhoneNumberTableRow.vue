<template>

    <person-contact-entry-table-row :entry="phoneNumber">

        <td class=" p-2" style="font-size: 1.18rem; letter-spacing: 1.8px; word-spacing: 4px;">
            <base-field title="Telefoonnummer" name="phone_number">{{ phoneNumber.phone_number }}</base-field>
        </td>

        <td class="text-muted font-weight-bold small">
            <base-field title="Type Telefoonnummer" name="number_type">{{ phoneNumber.number_type }}</base-field>
        </td>

        <td class="text-muted-dark">
            <base-field title="Land" name="country">{{ phoneNumber.country }}</base-field>
            <span v-if="phoneNumber.location && phoneNumber.location !== phoneNumber.country"
                  class="text-muted font-italic">
                (<base-field title="Globale locatie" name="location">{{ phoneNumber.location }}</base-field>)
            </span>
        </td>

    </person-contact-entry-table-row>

</template>

<script>
    import gql from 'graphql-tag';

    import PersonContactEntryTableRow from "./PersonContactEntryTableRow";
    import BaseField from "./BaseField";

    export default {
        name: "person-phone-number-table-row",

        components: {
            BaseField,
            PersonContactEntryTableRow
        },

        fragment: gql`
            fragment PersonPhoneNumberTableRow on PersonPhoneNumber {
                id
                ...PersonContactEntryTableRow
                phone_number
                number_type
                country
                location
            }
            ${PersonContactEntryTableRow.fragment}
        `,

        props: {
            phoneNumber:{
                type:Object,
                required:true,
                default() {
                    return {
                        index:null,
                        label:null,
                        phone_number:null,
                        number_type:null,
                        country:null,
                        location:null
                    };
                }
            }
        }
    }
</script>

<style scoped>

</style>