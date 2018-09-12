<template>

    <person-contact-entry-table :entries="emailAddresses">
        <person-email-address-table-row
                slot="row"
                slot-scope="{item}"
                :key="item.id"
                :email-address="item"
        />
    </person-contact-entry-table>

</template>

<script>
    import gql from 'graphql-tag';

    import PersonEmailAddressTableRow from "./PersonEmailAddressTableRow";
    import PersonContactEntryTable from "./PersonContactEntryTable";

    export default {

        name:'person-email-address-table',

        fragment:gql`
            fragment PersonEmailAddressTable on PersonEmailAddress_pagination {
                data {
                    ...PersonEmailAddressTableRow
                }
            }
            ${PersonEmailAddressTableRow.fragment}
        `,

        props: {
            emailAddresses: {
                type:Object,
                required:true,
                default() {
                    return {
                        data:[]
                    }
                }
            }
        },

        components: {
            PersonContactEntryTable,
            PersonEmailAddressTableRow
        },

    }
</script>

<style scoped>

</style>