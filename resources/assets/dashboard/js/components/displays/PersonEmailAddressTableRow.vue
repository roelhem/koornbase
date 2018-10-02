<template>
    <person-contact-entry-table-row :entry="emailAddress">
        <td>
            <email-address-span :email-address="emailAddress.emailAddress" with-button />
        </td>
    </person-contact-entry-table-row>
</template>

<script>
    import gql from "graphql-tag";
    import PersonContactEntryTableRow from "./PersonContactEntryTableRow";
    import BaseField from "./BaseField";
    import EmailAddressSpan from "./EmailAddressSpan";

    export default {


        name: "person-email-address-table-row",

        fragment: gql`
            fragment PersonEmailAddressTableRow on PersonEmailAddress {
                id
                ...PersonContactEntryTableRow
                emailAddress {
                    email
                    ...EmailAddressSpan
                }
            }
            ${PersonContactEntryTableRow.fragment}
            ${EmailAddressSpan.fragment}
        `,


        props: {
            emailAddress:{
                type:Object,
                required:true,
                default:function() {
                    return {
                        index:null,
                        label:null,
                        emailAddress:{email:null},
                    }
                }
            }
        },

        components: {
            EmailAddressSpan,
            BaseField,
            PersonContactEntryTableRow
        },

    }
</script>

<style scoped>

</style>