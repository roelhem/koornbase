<template>
    <person-contact-entry-table-row :entry="emailAddress">
        <td>
            <base-field class="tracking-wide"
                        title="E-mail adres"
                        name="email_address"
            >{{ emailAddress.email_address }}</base-field>
        </td>
    </person-contact-entry-table-row>
</template>

<script>
    import gql from "graphql-tag";
    import PersonContactEntryTableRow from "./PersonContactEntryTableRow";
    import BaseField from "./BaseField";

    export default {


        name: "person-email-address-table-row",

        fragment: gql`
            fragment PersonEmailAddressTableRow on PersonEmailAddress {
                id
                ...PersonContactEntryTableRow
                email_address
            }
            ${PersonContactEntryTableRow.fragment}
        `,


        props: {
            emailAddress:{
                type:Object,
                required:true,
                default:function() {
                    return {
                        index:null,
                        label:null,
                        email_address:null,
                    }
                }
            }
        },

        components: {
            BaseField,
            PersonContactEntryTableRow
        },

    }
</script>

<style scoped>

</style>