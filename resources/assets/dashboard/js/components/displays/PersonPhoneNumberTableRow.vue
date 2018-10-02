<template>

    <person-contact-entry-table-row :entry="phoneNumber">

        <td class=" p-2" style="font-size: 1.18rem; letter-spacing: 1.8px; word-spacing: 4px;">
            <phone-number-span :phone-number="phoneNumber.phoneNumber" with-button />
        </td>

        <td class="text-muted font-weight-bold small">
            <base-field title="Type Telefoonnummer" name="phoneNumber.type" v-if="phoneNumber.phoneNumber">{{ phoneNumber.phoneNumber.type }}</base-field>
        </td>

        <td class="text-muted-dark">
            <base-field title="Land" name="country" v-if="phoneNumber.phoneNumber && phoneNumber.phoneNumber.country">{{ phoneNumber.phoneNumber.country.name }}</base-field>
            <span v-if="phoneNumber.phoneNumber && phoneNumber.phoneNumber.location && phoneNumber.phoneNumber.location !== phoneNumber.phoneNumber.country.name"
                  class="text-muted font-italic">
                (<base-field title="Globale locatie" name="location" v-if="phoneNumber.phoneNumber">{{ phoneNumber.phoneNumber.location }}</base-field>)
            </span>
        </td>

    </person-contact-entry-table-row>

</template>

<script>
    import gql from 'graphql-tag';

    import PersonContactEntryTableRow from "./PersonContactEntryTableRow";
    import BaseField from "./BaseField";
    import PhoneNumberSpan from "./PhoneNumberSpan";

    export default {
        name: "person-phone-number-table-row",

        components: {
            PhoneNumberSpan,
            BaseField,
            PersonContactEntryTableRow
        },

        fragment: gql`
            fragment PersonPhoneNumberTableRow on PersonPhoneNumber {
                id
                ...PersonContactEntryTableRow
                phoneNumber {
                    number
                    type
                    country { name }
                    location
                    ...PhoneNumberSpan
                }
            }
            ${PersonContactEntryTableRow.fragment}
            ${PhoneNumberSpan.fragment}
        `,

        props: {
            phoneNumber:{
                type:Object,
                required:true,
                default() {
                    return {
                        index:null,
                        label:null,
                        phoneNumber:{
                            number:null,
                            type:null,
                            country:{
                                name:null
                            },
                            location:null
                        },
                    };
                }
            }
        }
    }
</script>

<style scoped>

</style>